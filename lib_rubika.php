<?php


class lib_rubika{

    private $auth;
    private $encryptKey;
    private $user_guid;
    public $serverInfos;
    private $client =  [
        'app_name' => 'Main',
        'app_version' => '3.1.15',
        'platform' => 'Web',
        'package' => 'web.rubika.ir',
        'lang_code' => 'fa'
    ];
    private $api_version = '5';
    public function __construct($auth,$user_guid)
    {
        $encryptKey = crypto::createSecretPassphrase($auth);
        $this->auth = $auth;
        $this->encryptKey = $encryptKey;
        $this->user_guid = $user_guid;
        $this->updateServerInfos();
    }


    public function updateServerInfos()
    {
        for ($i = 0; $i < 5; $i++) {
            $result = $this->request('https://getdcmess.iranlms.ir/', [
                'api_version' => '4',
                'method' => 'getDCs',
                'client' => $this->client
            ], true, false);

            if (isset($result['status']) && $result['status'] === 'OK') {
                $this->serverInfos = $result['data'];
                return true;
            }
            sleep(mt_rand(4, 8));
        }
        return false;
    }

    public function request(string $url, array $data = [], bool $jsonDecode = true, bool $isJsonData = true)
    {
        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            // CURLOPT_VERBOSE => true,
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; rv:78.0) Gecko/20100101 Firefox/78.0',
                'Accept: application/json, text/plain, */*',
                'Accept-Language: en-US,en;q=0.5',
                'Accept-Encoding: gzip, deflate, br',
                'Referer: https://web.rubika.ir/',
                'Origin: https://web.rubika.ir',
                'Connection: keep-alive',
            ],
        ];
        if ($data !== []) {
            $options[CURLOPT_POST] = true;
            if ($isJsonData) {
                $options[CURLOPT_HTTPHEADER][] =  'Content-Type: application/json';
                $data = json_encode($data);
            } else {
                $data = http_build_query($data);
            }
            $options[CURLOPT_POSTFIELDS] = $data;
        }
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);
        return ($jsonDecode) ? json_decode("$response", true) : $response;
    }

    public function sendOptionRequest(string $url)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'OPTIONS',
            CURLOPT_RETURNTRANSFER => true,
            // CURLOPT_HEADER => true,
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; rv:78.0) Gecko/20100101 Firefox/78.0',
                'Accept: */*',
                'Accept-Language: en-US,en;q=0.5',
                'Accept-Encoding: gzip, deflate, br',
                'Access-Control-Request-Method: POST',
                'Access-Control-Request-Headers: content-type',
                'Referer: https://web.rubika.ir/',
                'Origin: https://web.rubika.ir',
                'Connection: keep-alive',
            ],
            CURLOPT_NOBODY => true,
            // CURLOPT_VERBOSE => true,
        ]);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $httpCode;
    }

    public function sendRequest(array $data, bool $setTmpSession = false)
    {
        $default_api_urls = $this->serverInfos['default_api_urls'];
        if(!is_null($default_api_urls)){
            $data['client'] = $this->client;
            foreach ($default_api_urls as $url) {
                $responseHttpCode = $this->sendOptionRequest($url);
                if ($responseHttpCode === 200) {
                    $requestData = [
                        'api_version' => $this->api_version,
                        'data_enc' => crypto::aes_256_cbc_encrypt(json_encode($data), $this->encryptKey),
                    ];
                    $dataKeyName = (!$setTmpSession) ? 'auth' : 'tmp_session';
                    $requestData[$dataKeyName] = $this->auth;
                    $response = $this->request($url, $requestData);
    
                    if (is_array($response)) {
                        if (isset($response['data_enc'])) {
                            $response = $this->decryptRequest($response['data_enc']);
                        }
                        return json_decode("$response", true);
                    }
                }
            }
        }
    }

    public function run(string $method, array $input = [], bool $setTmpSession = false)
    {
        return $this->sendRequest([
            'method' => $method,
            'input' => $input,
        ], $setTmpSession);
    }

    public function decryptRequest(string $data_enc, string $auth = '')
    {
        return crypto::aes_256_cbc_decrypt($data_enc, (empty($auth) ? $this->encryptKey : crypto::createSecretPassphrase($auth)));
    }

    public function onUpdate(callable $callback)
    {
        while (true) {

            $default_sockets = $this->serverInfos['default_sockets'];
            foreach ($default_sockets as $socket) {

    
                // echo "$socket\n";
                try {
                    $client = new Client($socket, [
                        'headers' => [ // Additional headers, used to specify subprotocol
                            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; rv:78.0) Gecko/20100101 Firefox/78.0',
                            'origin' => 'https://web.rubika.ir',
                        ],
                        'timeout' => 450, // 1 minute time out
                    ]);
                    $client->text(json_encode([
                        'api_version' => '5',
                        'auth' => $this->auth,
                        'data' => '',
                        'method' => 'handShake'
                    ]));
                    $timeSecCount = 0;
                    // echo "Connect {$socket}\n";
                    while (true) {
                        if (($timeSecCount % 15) === 0) {
                            $client->text('{}');
                        }
                        $timeSecCount++;
                        $message = json_decode($client->receive(), true);
                        if (isset($message['data_enc'])) {
                            $message['data_enc'] = json_decode($this->decryptRequest($message['data_enc']), true);
                        }
                        $callback($message);


                    }
                } catch (ConnectionException $e) {
                    // Possibly log errors
                    // print_r($e);
                }  
                // $client->close();
            }
            $this->updateServerInfos();
        }
    }
    // usleep(500000);
    /** 
     * get all of chats
     * @return array on success return all of chats
     */
    public function getChats()
    {
        return $this->run('getChats');
    }

    /** 
     * get all of chats ads
     * @return array on success return  all of chat ads
     */
    public function getChatAds()
    {
        return $this->run('getChatAds');
    }

    /** 
     * get new messages
     * @return array
     */
    public function getChatsUpdates($time)
    {
        return $this->run('getChatsUpdates', ['state' => "$time"]);
    }

    /** 
     * get account sessions
     * @return array 
     */
    public function getMySessions()
    {
        return $this->run('getMySessions');
    }

    /** 
     * @param string $user_guid user_guid of user to get info
     * @return array user info request response
     */
    public function getUserInfo(string $user_guid)
    {
        return $this->run('getUserInfo', ["user_guid" => $user_guid]);
    }

    public function getInfoByUsername(string $username)
    {
        return $this->run('getObjectByUsername', ['username' => $username]);
    }
    /** 
     * @param string $group group to get info
     * @return array group info request response
     */
    public function getGroupInfo(string $group_guid)
    {
        return $this->run('getGroupInfo', ["group_guid" => $group_guid]);
    }

    /** 
     * @param string $channel channel to get info
     * @return array channel info request response
     */
    public function getChannelInfo(string $channel_guid)
    {
        return $this->run('getChannelInfo', ["channel_guid" => $channel_guid]);
    }

    public function getBannedGroupMembers(string $group_guid)
    {
        return $this->run('getBannedGroupMembers', ["group_guid" => $group_guid]);
    }
    
    public function getGroupAdmins(string $group_guid)
    {
        return $this->run('getGroupAdminMembers', ["group_guid" => $group_guid]);
    }
    // public function getGroupAdmins(string $group_guid)
    // {
    //     return $this->run('GetGroupAllMembers', ["group_guid" => $group_guid]);
    // }

    public function getGroupVoiceChatUpdates(string $group_guid)
    {
        return $this->run('getGroupVoiceChatUpdates', ["state" => mt_rand(100000, 999999),"chat_guid"=>$chat_guid,"voice_chat_id"=>$voice_chat_id]);
    }
    public function getMyStickerSets()
    {
        return $this->run('getMyStickerSets');
    }
    
    
    
    /** 
     * @param array $objects_guids
     * @return array
     */
    public function getAbsObjects(array $objects_guids)
    {
        return $this->run('getAbsObjects', ["objects_guids" => $objects_guids]);
    }

    /** 
     * get message interval
     * @param string $object_guid
     * @param int $middle_message_id message id of message
     * @return array
     */
    // public function getMessagesInterval(string $object_guid, int $middle_message_id)
    // {
    //     return $this->run('getMessagesInterval', ['object_guid' => $object_guid, 'middle_message_id' => $middle_message_id]);
    // }
    public function getMessagesInterval($object_guid,$middle_message_id)
    {
        return $this->run('getMessagesInterval', ['object_guid' => $object_guid, 'middle_message_id' => $middle_message_id]);
    }

    /** 
     * get update of messages
     * @param string $object_guid 
     * @return array
     */
    public function getMessagesUpdates(string $object_guid)
    {
        return $this->run('getMessagesUpdates', ['object_guid' => $object_guid, 'state' => "1665427144"]);
    }

    /** 
     * get message by filter
     * @param string $object_guid
     * @param string $sort @example FromMax 
     * @param string $filter_type type of content @example Media Music Voice File
     * @param int $max_id max message id @example 76213478446577
     * @return array
     */
    public function getMessages(string $object_guid, string $sort, string $filter_type, int $max_id)
    {
        return $this->run('getMessages', ['object_guid' => $object_guid, 'sort' => $sort, 'filter_type' => $filter_type, 'max_id' => $max_id]);
    }

    /** 
     * get message by message id
     * @param string $object_guid
     * @param array $message_ids
     * @return array
     */
    public function getMessagesInfo(string $object_guid, array $message_ids)
    {
        return $this->run('getMessagesByID', ['object_guid' => $object_guid, 'message_ids' => $message_ids]);
    }


    
    /** 
     * get status of poll
     * @param string $poll_id
     * @return array
     */
    public function getPollStatus(string $poll_id)
    {
        return $this->run('getPollStatus', ['poll_id' => $poll_id]);
    }

    /** 
     * @param array $seen_list list of message seened ['object_guid' => 'middle_message_id']
     * @return array 
     */

    public function addGroupMembers($member_guids,$group_guid)
    {
        return $this->run('addGroupMembers', ['member_guids' => $member_guids,'group_guid' => $group_guid]);
    }

    public function addChannelMembers($member_guids,$group_guid)
    {
        return $this->run('addChannelMembers', ['member_guids' => $member_guids,'channel_guid' => $group_guid]);
    }


    public function banGroupMember($group_guid,$member_guid)
    {
        return $this->run('banGroupMember', ['action'=>'Set','group_guid'=>$group_guid,'member_guid'=>$member_guid]);
    }
    public function unBanGroupMember($group_guid,$member_guid)
    {
        return $this->run('banGroupMember', ['action'=>'Unset','group_guid'=>$group_guid,'member_guid'=>$member_guid]);
    }

    public function getChannelAllMembers($channel_guid)
    {
        return $this->run('getChannelAllMembers', ['channel_guid'=>$channel_guid]);
    }

    public function getGroupAllMembers($group_guid)
    {
        return $this->run('getGroupAllMembers', ['group_guid'=>$group_guid]);
    }

    // ['SendMessages','ViewAdmins','ViewMembers','AddMember']
    public function setGroupDefaultAccess($access_list,$group_guid)
    {
        return $this->run('setGroupDefaultAccess', ['access_list'=>$access_list,'group_guid'=>$group_guid]);
    }    
    public function getGroupDefaultAccess($group_guid)
    {
        return $this->run('getGroupDefaultAccess', ['group_guid'=>$group_guid]);
    }
    public function joinGroupVoiceChat($chat_guid,$voice_chat_id)
    {
        // $sdp_offer_data = "v=0\r\no=- 7025254686977085379 2 IN IP4 127.0.0.1\r\ns=-\r\nt=0 0\r\na=group:BUNDLE 0\r\na=extmap-allow-mixed\r\na=msid-semantic: WMS LjIerKYwibTOvR0Ewwk1PBsYYxTInaoXObBE\r\nm=audio 9 UDP/TLS/RTP/SAVPF 111 63 103 104 9 0 8 106 105 13 110 112 113 126\r\nc=IN IP4 0.0.0.0\r\na=rtcp:9 IN IP4 0.0.0.0\r\na=ice-ufrag:6Hy7\r\na=ice-pwd:pyrxfUF+roBFRHDy6qgiKSAp\r\na=ice-options:trickle\r\na=fingerprint:sha-256 8C:90:E9:0C:E7:A4:79:7E:BF:78:81:ED:A7:19:82:64:71:F7:21:AB:43:4F:4B:3A:4C:EB:B5:3C:6A:01:CB:13\r\na=setup:actpass\r\na=mid:0\r\na=extmap:1 urn:ietf:params:rtp-hdrext:ssrc-audio-level\r\na=extmap:2 http://www.webrtc.org/experiments/rtp-hdrext/abs-send-time\r\na=extmap:3 http://www.ietf.org/id/draft-holmer-rmcat-transport-wide-cc-extensions-01\r\na=extmap:4 urn:ietf:params:rtp-hdrext:sdes:mid\r\na=sendrecv\r\na=msid:LjIerKYwibTOvR0Ewwk1PBsYYxTInaoXObBE 00f6113c-f01a-447a-a72e-c989684b627a\r\na=rtcp-mux\r\na=rtpmap:111 opus/48000/2\r\na=rtcp-fb:111 
        // transport-cc\r\na=fmtp:111 minptime=10;useinbandfec=1\r\na=rtpmap:63 red/48000/2\r\na=fmtp:63 111/111\r\na=rtpmap:103 ISAC/16000\r\na=rtpmap:104 ISAC/32000\r\na=rtpmap:9 G722/8000\r\na=rtpmap:0 PCMU/8000\r\na=rtpmap:8 PCMA/8000\r\na=rtpmap:106 CN/32000\r\na=rtpmap:105 CN/16000\r\na=rtpmap:13 CN/8000\r\na=rtpmap:110 telephone-event/48000\r\na=rtpmap:112 telephone-event/32000\r\na=rtpmap:113 telephone-event/16000\r\na=rtpmap:126 telephone-event/8000\r\na=ssrc:1614457217 cname:lYBnCNdQcW/DEUj9\r\na=ssrc:1614457217 msid:LjIerKYwibTOvR0Ewwk1PBsYYxTInaoXObBE 00f6113c-f01a-447a-a72e-c989684b627a\r\n";
        return $this->run('joinGroupVoiceChat', ['chat_guid'=>$chat_guid,'voice_chat_id'=>$voice_chat_id]);
    }

    public function getGroupVoiceChatParticipants($chat_guid,$voice_chat_id)
    {
        // $sdp_offer_data = "v=0\r\no=- 7025254686977085379 2 IN IP4 127.0.0.1\r\ns=-\r\nt=0 0\r\na=group:BUNDLE 0\r\na=extmap-allow-mixed\r\na=msid-semantic: WMS LjIerKYwibTOvR0Ewwk1PBsYYxTInaoXObBE\r\nm=audio 9 UDP/TLS/RTP/SAVPF 111 63 103 104 9 0 8 106 105 13 110 112 113 126\r\nc=IN IP4 0.0.0.0\r\na=rtcp:9 IN IP4 0.0.0.0\r\na=ice-ufrag:6Hy7\r\na=ice-pwd:pyrxfUF+roBFRHDy6qgiKSAp\r\na=ice-options:trickle\r\na=fingerprint:sha-256 8C:90:E9:0C:E7:A4:79:7E:BF:78:81:ED:A7:19:82:64:71:F7:21:AB:43:4F:4B:3A:4C:EB:B5:3C:6A:01:CB:13\r\na=setup:actpass\r\na=mid:0\r\na=extmap:1 urn:ietf:params:rtp-hdrext:ssrc-audio-level\r\na=extmap:2 http://www.webrtc.org/experiments/rtp-hdrext/abs-send-time\r\na=extmap:3 http://www.ietf.org/id/draft-holmer-rmcat-transport-wide-cc-extensions-01\r\na=extmap:4 urn:ietf:params:rtp-hdrext:sdes:mid\r\na=sendrecv\r\na=msid:LjIerKYwibTOvR0Ewwk1PBsYYxTInaoXObBE 00f6113c-f01a-447a-a72e-c989684b627a\r\na=rtcp-mux\r\na=rtpmap:111 opus/48000/2\r\na=rtcp-fb:111 
        // transport-cc\r\na=fmtp:111 minptime=10;useinbandfec=1\r\na=rtpmap:63 red/48000/2\r\na=fmtp:63 111/111\r\na=rtpmap:103 ISAC/16000\r\na=rtpmap:104 ISAC/32000\r\na=rtpmap:9 G722/8000\r\na=rtpmap:0 PCMU/8000\r\na=rtpmap:8 PCMA/8000\r\na=rtpmap:106 CN/32000\r\na=rtpmap:105 CN/16000\r\na=rtpmap:13 CN/8000\r\na=rtpmap:110 telephone-event/48000\r\na=rtpmap:112 telephone-event/32000\r\na=rtpmap:113 telephone-event/16000\r\na=rtpmap:126 telephone-event/8000\r\na=ssrc:1614457217 cname:lYBnCNdQcW/DEUj9\r\na=ssrc:1614457217 msid:LjIerKYwibTOvR0Ewwk1PBsYYxTInaoXObBE 00f6113c-f01a-447a-a72e-c989684b627a\r\n";
        return $this->run('getGroupVoiceChatParticipants', ['chat_guid'=>$chat_guid,'voice_chat_id'=>$voice_chat_id]);
    }

    public function setGroupLink($group_guid)
    {
        return $this->run('setGroupLink', ['group_guid'=>$group_guid]);
    }
    public function editGroupInfo($group_guid,$time)
    {
        return $this->run('editGroupInfo', ['group_guid'=>$group_guid,'slow_mode'=>$time,'updated_parameters'=>["slow_mode"]]);
    }

    public function setGroupAdmin($group_guid,$access_list,$member_guid)
    {
        return $this->run('setGroupAdmin', ['group_guid'=>$group_guid,'access_list'=>$access_list,'action'=>"SetAdmin",'member_guid'=>$member_guid]);
    }
    public function UnsetGroupAdmin($group_guid,$member_guid)
    {
        return $this->run('setGroupAdmin', ['group_guid'=>$group_guid,'action'=>"UnsetAdmin",'member_guid'=>$member_guid]);
    }

    public function setPinMessage($chat_id,$message_id)
    {
        return $this->run('setPinMessage', ['action'=>"Pin",'message_id'=>$message_id,'object_guid'=>$chat_id]);
    }

    public function UnsetPinMessage($message_id,$chat_id)
    {
        return $this->run('setPinMessage', ['action'=>"Unpin",'message_id'=>$message_id,'object_guid'=>$chat_id]);
    }
    public function joinGroup($hash_link)
    {
        return $this->run('joinGroup', ['hash_link'=>$hash_link]);
    }
    public function leaveGroup($group_guid)
    {
        return $this->run('leaveGroup', ['group_guid'=>$group_guid]);
    }
    public function joinChannel($hash_link)
    {
        return $this->run('joinChannelByLink', ['hash_link'=>$hash_link]);
    }
    public function leaveChannel($hash_link)
    {
        return $this->run('leaveChannel', ['hash_link'=>$hash_link]);
    }
                           
    public function getGroupLink(string $group_guid)
    {
        return $this->run('getGroupLink', ['group_guid' => $group_guid]);
    }
    
    public function getChannelLink(string $channel_guid)
    {
        return $this->run('getChannelLink', ['channel_guid' => $channel_guid]);
    }
    public function BlockUser(string $user_guid)
    {
        return $this->run('setBlockUser', ['action' => "Block","user_guid"=>$user_guid]);
    }
    public function UnBlockUser(string $user_guid)
    {
        return $this->run('setBlockUser', ['action' => "Unblock","user_guid"=>$user_guid]);
    }
                    
    public function seenChats(array $seen_list)
    {
        return $this->run('seenChats', ['seen_list' => $seen_list]);
    }

    

    /** 
     * @param string $object_guid send message to object_guid
     * @param string $text text to send
     * @return array request response
     */
    public function sendMessage(string $object_guid, string $text)
    {
        return $this->run('sendMessage', ['object_guid' => $object_guid, 'text' => $text, 'rnd' => mt_rand(100000, 999999)]);
        // return $this->run('sendMessage', ['object_guid' => $object_guid, 'text' => $text, 'reply_to_message_id' => 297593634750373, 'rnd' => mt_rand(100000, 999999)]);
    }
    public function sendMessage_mention($GUID,$object_guid,$message_id,$text)
    {
        $length = strlen($text);
        $metadata = array(
            'meta_data_parts'=>array(
                0 => array(
                    'from_index'=>0,
                    'length'=>$length,
                    'type'=>'MentionText',
                    'mention_text_object_guid'=>"$object_guid"
                ),
            ),
        );
        return $this->run('sendMessage', ['object_guid' => "$GUID", 'text' => "$text", 'rnd' => mt_rand(100000, 999999),'metadata'=>$metadata, 'reply_to_message_id' => $message_id]);
        // return $this->run('sendMessage', ['object_guid' => $object_guid, 'text' => $text, 'reply_to_message_id' => 297593634750373, 'rnd' => mt_rand(100000, 999999)]);
    }
    public function sendMessage_mentionPro($GUID,$object_guid,$message_id,$text)
    {
        $length = strlen($text);
        $length = $length-4;
        $metadata = array(
            'meta_data_parts'=>array(
                0 => array(
                    'from_index'=>0,
                    'length'=>$length,
                    'type'=>'MentionText',
                    'mention_text_object_guid'=>"$object_guid"
                ),
            ),
        );
        return $this->run('sendMessage', ['object_guid' => "$GUID", 'text' => "$text", 'rnd' => mt_rand(100000, 999999),'metadata'=>$metadata, 'reply_to_message_id' => $message_id]);
        // return $this->run('sendMessage', ['object_guid' => $object_guid, 'text' => $text, 'reply_to_message_id' => 297593634750373, 'rnd' => mt_rand(100000, 999999)]);
    }
    public function sendMessage_mentionX($GUID,$object_guid,$text,$message_id,$length)
    {
        $metadata = array(
            'meta_data_parts'=>array(
                0 => array(
                    'from_index'=>0,
                    'length'=>$length,
                    'type'=>'MentionText',
                    'mention_text_object_guid'=>"$object_guid"
                ),
            ),
        );
        return $this->run('sendMessage', ['object_guid' => "$GUID", 'text' => "$text", 'rnd' => mt_rand(100000, 999999),'metadata'=>$metadata, 'reply_to_message_id' => $message_id]);
        // return $this->run('sendMessage', ['object_guid' => $object_guid, 'text' => $text, 'reply_to_message_id' => 297593634750373, 'rnd' => mt_rand(100000, 999999)]);
    }
    public function sendMessage_Bold($object_guid,$text,$metadata)
    {
        if(is_null($metadata)){
            $metadata = array(
                'meta_data_parts'=>array(
                    0 => array(
                        'from_index'=> 0 ,
                        'length'=>2,
                        'type'=>'Bold'
                    ),
                ),
            );
        }
        return $this->run('sendMessage', ['object_guid' => "$object_guid", 'text' => "$text", 'rnd' => mt_rand(100000, 999999),'metadata'=>$metadata]);
        // return $this->run('sendMessage', ['object_guid' => $object_guid, 'text' => $text, 'reply_to_message_id' => 297593634750373, 'rnd' => mt_rand(100000, 999999)]);
    }
    public function sendMessage_Mono($object_guid,$text,$metadata)
    {
        if(is_null($metadata)){
            $metadata = array(
                'meta_data_parts'=>array(
                    0 => array(
                        'from_index'=> 0 ,
                        'length'=>2,
                        'type'=>'Mono'
                    ),
                ),
            );
        }
        return $this->run('sendMessage', ['object_guid' => "$object_guid", 'text' => "$text", 'rnd' => mt_rand(100000, 999999),'metadata'=>$metadata]);
        // return $this->run('sendMessage', ['object_guid' => $object_guid, 'text' => $text, 'reply_to_message_id' => 297593634750373, 'rnd' => mt_rand(100000, 999999)]);
    }
    public function sendMessage_Italic($object_guid,$text,$metadata)
    {
        if(is_null($metadata)){
            $metadata = array(
                'meta_data_parts'=>array(
                    0 => array(
                        'from_index'=> 0 ,
                        'length'=>2,
                        'type'=>'italic'
                    ),
                ),
            );
        }
        return $this->run('sendMessage', ['object_guid' => "$object_guid", 'text' => "$text", 'rnd' => mt_rand(100000, 999999),'metadata'=>$metadata]);
        // return $this->run('sendMessage', ['object_guid' => $object_guid, 'text' => $text, 'reply_to_message_id' => 297593634750373, 'rnd' => mt_rand(100000, 999999)]);
    }
    public function sendMessage_reply(string $object_guid, string $text,$message_id)
    {
        return $this->run('sendMessage', ['object_guid' => $object_guid, 'text' => $text, 'reply_to_message_id' => $message_id, 'rnd' => mt_rand(100000, 999999)]);
    }
    
    public function editMessage($message_id,string $object_guid, string $text)
    {
        return $this->run('editMessage', ["message_id" =>$message_id,'object_guid' => $object_guid, 'text' => $text]);
        // return $this->run('sendMessage', ['object_guid' => $object_guid, 'text' => $text, 'reply_to_message_id' => 297593634750373, 'rnd' => mt_rand(100000, 999999)]);
    }

    public function UpdateUsername($username)
    {
        return $this->run('updateUsername', ["username" =>$username]);
        // return $this->run('sendMessage', ['object_guid' => $object_guid, 'text' => $text, 'reply_to_message_id' => 297593634750373, 'rnd' => mt_rand(100000, 999999)]);
    }
    public function UpdateProfile($first_name,$last_name,$bio)
    {
        
        // $input=array("updated_parameters"=>true,"params"=>array("first_name" =>$first_name,"last_name" =>$last_name,"bio"=>$bio));

        return $this->run('updateProfile',["first_name" =>$first_name,'last_name' => $last_name, 'bio' => $bio,"updated_parameters"=>[0=>'first_name',1=>'last_name',2=>"bio"]]);
        // return $this->run('sendMessage', ['object_guid' => $object_guid, 'text' => $text, 'reply_to_message_id' => 297593634750373, 'rnd' => mt_rand(100000, 999999)]);
    }
    
    public function UpdateBio($bio)
    {
        return $this->run('updateProfile',['bio' => $bio,"updated_parameters"=>[0=>"bio"]]);
    }    
    
    public function UpdateName($first_name)
    {
        return $this->run('updateProfile',["first_name" =>$first_name,"updated_parameters"=>[0=>'first_name']]);
    }

    /** 
     * @param string $object_guid send message to object_guid
     * @param string $text text to send
     * @return array request response
     */
    public function forwardMessages($from_object_guid,$message_ids,$to_object_guid)
    {
        return $this->run('forwardMessages', ['from_object_guid' => $from_object_guid,'message_ids' => $message_ids, 'rnd' => mt_rand(100000, 999999), 'to_object_guid' => $to_object_guid]);
    }

    public function setGroupTimer($group_guid,$slow_mode)
    {
        return $this->run('editGroupInfo',['group_guid'=>$group_guid,'slow_mode'=>$slow_mode,'updated_parameters'=>["slow_mode"]]);
    }
    
    
    public function deleteMessages($object_guid,$message_id)
    {
        return $this->run('deleteMessages', ['object_guid' => $object_guid, 'message_ids' => $message_id, 'type' => "Global"]);
    }

    public function requestSendFile(string $file_name,string $size,string $mime)
    {
        return $this->run('requestSendFile', ['file_name' => $file_name, 'size' => $size,'mime' =>$mime]);
    }
    
    // chat_id : str , file_id : str , mime : str , dc_id : str , access_hash_rec : str , file_name : str , size : str , thumbnail : bytes , width : str , height : str , caption : bool = None , message_id : bool = None
    public function sendFile(string $object_guid,array $file_inline)
    {
        return $this->run('sendMessage', ['object_guid' => $object_guid, 'rnd' => mt_rand(100000, 999999),'file_inline' =>$file_inline]);
    }
    
    
    public function createGroupVoiceChat(string $chat_guid)
    {
        return $this->run('createGroupVoiceChat', ['chat_guid' => $chat_guid]);
    }
    public function discardGroupVoiceChat(string $chat_guid,$voice_chat_id)
    {
        return $this->run('discardGroupVoiceChat', ['chat_guid' => $chat_guid,"voice_chat_id" => $voice_chat_id]);
    }
    
    public function Block(string $user_guid)
    {
        return $this->run('setBlockUser', ['action' => "Block",'user_guid' => $user_guid]);
    }
    public function unBlock(string $user_guid)
    {
        return $this->run('setBlockUser', ['action' => "Unblock",'user_guid' => $user_guid]);
    }
    public function getAvatars(string $object_guid)
    {
        return $this->run('getAvatars', ['object_guid' => $object_guid]);
    }
    public function UploadAvatar($object_guid,$main_file_id,$thumbnail_file_id)
    {
        return $this->run('UploadAvatar', ['object_guid'=>$object_guid,'main_file_id'=>$main_file_id,'thumbnail_file_id'=>$thumbnail_file_id]);
    }

    

    /** 
     * @param string $object_guid send message to object_guid
     * @param string $text text to send
     * @return array request response
     */

    /** 
     * @param string $poll_id
     * @param string $selection_index index of vote
     * @return array request response
     */
    public function votePoll(string $poll_id, int $selection_index)
    {
        return $this->run('votePoll', ['poll_id' => $poll_id, 'selection_index' => $selection_index]);
    }
/** 
     * search text for a special chat
     * @param string $search_text text for search
     * @param string $type @example Text
     * @param string $object_guid grop or user or channel or ... id for search
     * @return array
     */
    public function searchChatMessages(string $search_text, string $type, string $object_guid)
    {
        return $this->run('searchChatMessages', ['search_text' => $search_text, 'type' => $type, 'object_guid' => $object_guid]);
    }

    /** 
     * search text global to find user channel group or ... 
     * @param string $search_text text for search
     * @return array
     */
    public function searchGlobalObjects(string $search_text)
    {
        return $this->run('searchGlobalObjects', ['search_text' => $search_text]);
    }

    /** 
     * @param string $search_text text for search
     * @param string $type @example Text
     * @return array 
     */
    public function searchGlobalMessages(string $search_text, string $type)
    {
        return $this->run('searchGlobalMessages', ['search_text' => $search_text, 'type' => $type]);
    }

}