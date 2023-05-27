<?php


class Client
{

protected static $default_options = [
'context'       => null,
'filter'        => ['text', 'binary'],
'fragment_size' => 4096,
'headers'       => null,
'logger'        => null,
'origin'        => null, // @deprecated
'persistent'    => false,
'return_obj'    => false,
'timeout'       => 450,
];

private $socket_uri;
private $connection;
private $options = [];
private $listen = false;
private $last_opcode = null;
private static $opcodes = [
'continuation' => 0,
'text'         => 1,
'binary'       => 2,
'close'        => 8,
'ping'         => 9,
'pong'         => 10,
];

public function __construct($uri, array $options = [])
{
$this->socket_uri = $this->parseUri($uri);
$this->options = array_merge(self::$default_options, [
'logger' => new NullLogger(),
], $options);
$this->setLogger($this->options['logger']);
}

public function setLogger($logger): void
{
$this->logger = $logger;
}

public function __toString(): string
{
return sprintf(
"%s(%s)",
get_class($this),
$this->getName() ?: 'closed'
);
}

public function setTimeout(int $timeout): void
{
$this->options['timeout'] = $timeout;
if (!$this->isConnected()) {
return;
}
$this->connection->setTimeout($timeout);
$this->connection->setOptions($this->options);
}

public function setFragmentSize(int $fragment_size): self
{
$this->options['fragment_size'] = $fragment_size;
$this->connection->setOptions($this->options);
return $this;
}

public function getFragmentSize(): int
{
return $this->options['fragment_size'];
}

public function text(string $payload): void
{
$this->send($payload);
}

public function binary(string $payload): void
{
$this->send($payload, 'binary');
}

public function ping(string $payload = ''): void
{
$this->send($payload, 'ping');
}

public function pong(string $payload = ''): void
{
$this->send($payload, 'pong');
}

public function send(string $payload, string $opcode = 'text', bool $masked = true): void
{
if (!$this->isConnected()) {
$this->connect();
}

if (!in_array($opcode, array_keys(self::$opcodes))) {
$warning = "Bad opcode '{$opcode}'.  Try 'text' or 'binary'.";
$this->logger->warning($warning);
throw new BadOpcodeException($warning);
}

$factory = new Factory();
$message = $factory->create($opcode, $payload);
$this->connection->pushMessage($message, $masked);
}

public function close(int $status = 1000, string $message = 'ttfn'): void
{
if (!$this->isConnected()) {
return;
}
$this->connection->close($status, $message);
}

public function disconnect(): void
{
if ($this->isConnected()) {
$this->connection->disconnect();
}
}

public function receive()
{
$filter = $this->options['filter'];
$return_obj = $this->options['return_obj'];

if (!$this->isConnected()) {
$this->connect();
}

while (true) {
$message = $this->connection->pullMessage();
$opcode = $message->getOpcode();
if (in_array($opcode, $filter)) {
$this->last_opcode = $opcode;
$return = $return_obj ? $message : $message->getContent();
break;
} elseif ($opcode == 'close') {
$this->last_opcode = null;
$return = $return_obj ? $message : null;
break;
}
}
return $return;
}

public function getLastOpcode(): ?string
{
return $this->last_opcode;
}

public function getCloseStatus(): ?int
{
return $this->connection ? $this->connection->getCloseStatus() : null;
}

public function isConnected(): bool
{
return $this->connection && $this->connection->isConnected();
}

public function getName(): ?string
{
return $this->isConnected() ? $this->connection->getName() : null;
}

public function getRemoteName(): ?string
{
return $this->isConnected() ? $this->connection->getRemoteName() : null;
}

public function getPier(): ?string
{
trigger_error(
'getPier() is deprecated and will be removed in future version. Use getRemoteName() instead.',
E_USER_DEPRECATED
);
return $this->getRemoteName();
}

protected function connect(): void
{
$this->connection = null;

$host_uri = $this->socket_uri
->withScheme($this->socket_uri->getScheme() == 'wss' ? 'ssl' : 'tcp')
->withPort($this->socket_uri->getPort() ?? ($this->socket_uri->getScheme() == 'wss' ? 443 : 80))
->withPath('')
->withQuery('')
->withFragment('')
->withUserInfo('');

$http_path = $this->socket_uri->getPath();
if ($http_path === '' || $http_path[0] !== '/') {
$http_path = "/{$http_path}";
}

$http_uri = (new Uri())
->withPath($http_path)
->withQuery($this->socket_uri->getQuery());

if (isset($this->options['context'])) {
if (@get_resource_type($this->options['context']) === 'stream-context') {
$context = $this->options['context'];
} else {
$error = "Stream context in \$options['context'] isn't a valid context.";
$this->logger->error($error);
throw new \InvalidArgumentException($error);
}
} else {
$context = stream_context_create();
}

$persistent = $this->options['persistent'] === true;
$flags = STREAM_CLIENT_CONNECT;
$flags = $persistent ? $flags | STREAM_CLIENT_PERSISTENT : $flags;
$socket = null;

try {
$handler = new ErrorHandler();
$socket = $handler->with(function () use ($host_uri, $flags, $context) {
$error = $errno = $errstr = null;
return stream_socket_client(
$host_uri,
$errno,
$errstr,
$this->options['timeout'],
$flags,
$context
);
});
if (!$socket) {
throw new ErrorException('No socket');
}
} catch (ErrorException $e) {
$error = "Could not open socket to \"{$host_uri->getAuthority()}\": {$e->getMessage()} ({$e->getCode()}).";
$this->logger->error($error, ['severity' => $e->getSeverity()]);
throw new ConnectionException($error, 0, [], $e);
}

$this->connection = new Connection($socket, $this->options);
$this->connection->setLogger($this->logger);
if (!$this->isConnected()) {
$error = "Invalid stream on \"{$host_uri->getAuthority()}\".";
$this->logger->error($error);
throw new ConnectionException($error);
}

if (!$persistent || $this->connection->tell() == 0) {
$this->connection->setTimeout($this->options['timeout']);

$key = self::generateKey();
$headers = [
'Host'                  => $host_uri->getAuthority(),
'User-Agent'            => 'websocket-client-php',
'Connection'            => 'Upgrade',
'Upgrade'               => 'websocket',
'Sec-WebSocket-Key'     => $key,
'Sec-WebSocket-Version' => '13',
];

if ($userinfo = $this->socket_uri->getUserInfo()) {
$headers['authorization'] = 'Basic ' . base64_encode($userinfo);
}

if (isset($this->options['origin'])) {
$headers['origin'] = $this->options['origin'];
}

if (isset($this->options['headers'])) {
$headers = array_merge($headers, $this->options['headers']);
}

$header = "GET {$http_uri} HTTP/1.1\r\n" . implode(
"\r\n",
array_map(
function ($key, $value) {
return "$key: $value";
},
array_keys($headers),
$headers
)
) . "\r\n\r\n";

$this->connection->write($header);

$response = '';
try {
do {
$buffer = $this->connection->gets(1024);
$response .= $buffer;
} while (substr_count($response, "\r\n\r\n") == 0);
} catch (Exception $e) {
throw new ConnectionException('Client handshake error', $e->getCode(), $e->getData(), $e);
}

if (!preg_match('#Sec-WebSocket-Accept:\s(.*)$#mUi', $response, $matches)) {
$error = sprintf(
"Connection to '%s' failed: Server sent invalid upgrade response: %s",
(string)$this->socket_uri,
(string)$response
);
$this->logger->error($error);
throw new ConnectionException($error);
}

$keyAccept = trim($matches[1]);
$expectedResonse = base64_encode(
pack('H*', sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11'))
);

if ($keyAccept !== $expectedResonse) {
$error = 'Server sent bad upgrade response.';
$this->logger->error($error);
throw new ConnectionException($error);
}
}

$this->logger->info("Client connected to {$this->socket_uri}");
}

protected static function generateKey(): string
{
$key = '';
for ($i = 0; $i < 16; $i++) {
$key .= chr(rand(33, 126));
}
return base64_encode($key);
}

protected function parseUri($uri): UriInterface
{
if ($uri instanceof UriInterface) {
$uri = $uri;
} elseif (is_string($uri)) {
try {
$uri = new Uri($uri);
} catch (InvalidArgumentException $e) {
throw new BadUriException("Invalid URI '{$uri}' provided.", 0, $e);
}
} else {
throw new BadUriException("Provided URI must be a UriInterface or string.");
}
if (!in_array($uri->getScheme(), ['ws', 'wss'])) {
throw new BadUriException("Invalid URI scheme, must be 'ws' or 'wss'.");
}
return $uri;
}
}
