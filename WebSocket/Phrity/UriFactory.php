<?php

class UriFactory
{
public function createUri(string $uri = ''): UriInterface
{
return new Uri($uri);
}
}
