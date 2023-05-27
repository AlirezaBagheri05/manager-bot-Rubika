<?PHP

// set_time_limit(0);

function ROBOT_CODES($robot,$lib,$lib_ANS,$lib_QU,$Message,$guid_message,$lib_LR,$lib_BN,$lib_CH,$Setting,$Is_admin,$ADMINS,$LMessage,$Is_fulladmin,$Is_owner,$AFA,$lib_bio,$lib_text,$lib_etraf,$lib_jock,$lib_fact){
    if(is_null(GUID_U)){
        return false;
    }
    $Tod = Tod($lib_LR);
    if($Tod[12] == 1){
        if(!$Is_fulladmin){
            $info_bands = $lib_BN->selectStateMax(GUID_U,$guid_message);
            if(!is_null($info_bands)){
                $Max = $info_bands['Max'];
                $state = $info_bands['state'];
                if(!is_null($Max) && !is_null($state)){
                    if($Max <= $state){
                        BanUser($robot,$guid_message);
                    }
                }
            }
        }
    }

    $IS_YOU = true;
    if($guid_message === GUID){
        $IS_YOU = false;
    }
    $text = false;
    if(isset($Message['text'])){
        $text = $Message['text'];
    }
    $message_time = $Message["time"];
    $message_id = $Message["message_id"];
    if(isset($Message["type"])){
        $type = $Message["type"];
    }else{
        $type = false;
    }
    $Cont1 = false;
    $Cont2 = false;
    $Cont3 = false;
    $Cont4 = false;
    $Cont5 = false;
    $Cont6 = false;
    $reme = true;
    $YOUSPEAK = true;
    
    if($type == 'Event'){
        $YOUSPEAK = false;
        if(isset($Message["event_data"])){
                $event = $Message["event_data"];
                $type_event = $event['type'];
                $arrayE = ['CreateGroupVoiceChat','AddedGroupMembers','RemoveGroupMembers','PinnedMessageUpdated','JoinedGroupByLink','LeaveGroup','StopGroupVoiceChat','TitleUpdate','PhotoUpdate'];
                $fine_type = true;
                $count = count($arrayE);
                $count--;
                for($i =0;$i<=$count;$i++){
                    if($type_event == $arrayE[$i]){
                        if($i == 6){
                            $Guid_user = get_guidUser($Message);
                            if($Guid_user){
                                sendMessage_pro($robot,$lib_LR,$guid_message,$message_id,"✘ VOICE CHATE STOPED ✘");
                            }
                            $fine_type = false;
                            break;
                        }
                        if($i == 7 || $i == 8){
                            $fine_type = false;
                            break;
                        }
                        linkRemoverH($lib_LR,($i+13));
                        if($Setting[$i+13] == 2){
                            $reme = DLMS($robot,$message_id,$Is_fulladmin);
                        }
                        if($Setting[$i+13] == 3){
                            if($i == 1){
                                $i = 2;
                            }else if($i == 2){
                                $i = 3;
                            }else if($i == 4){
                                $i = 4;
                            }else if($i == 5){
                                $i = 5;
                            }
                            SendMessageX($robot,$guid_message,$i,$message_id,$lib_LR);
                            $reme = DLMS($robot,$message_id,$Is_fulladmin);
                        }
                        if($Setting[$i+13] == 4){
                            if($i == 1){
                                $i = 2;
                            }else if($i == 2){
                                $i = 3;
                            }else if($i == 4){
                                $i = 4;
                            }else if($i == 5){
                                $i = 5;
                            }
                            SendMessageX($robot,$guid_message,$i,$message_id,$lib_LR);
                        }
                        $fine_type = false;
                        break;
                    }
                }
                if($fine_type){
                    $result = var_export($Message,true);
                    $robot->sendMessage(GUID_OMG,"$result");
                    $robot->sendMessage(GUID_OMG,"این دیگه چه نوع پیامیه:|");
                }
        }
    }else if($type == 'Text'){
            $Cont1 = 90;
            if(isset($Message['forwarded_from'])){
                $Cont2 = 12;
            }
            if(isset($Message['metadata'])){
                $meta_data_parts = $Message['metadata']['meta_data_parts'];
                foreach($meta_data_parts as $meta_data_part){
                    $meta_data_part_type = $meta_data_part['type'];
                    if($meta_data_part_type == 'MentionText'){
                        $Cont3 = 3;
                    }
                }
            }
            if($text){
                $url = pro_search($text,'@');
                if($url){
                    $Cont4 = 2;
                }
                $url0 = pro_search($text,'http://');
                $url5 = pro_search($text,'https://');
                $url1 = pro_search($text,'.ir');
                $url2 = pro_search($text,'.com');
                $url3 = pro_search($text,'.org');
                $url4 = pro_search($text,'www');
                if($url1 || $url2 || $url4  || $url5 || $url3 || $url0){
                    $Cont5 = 1;
                }
            }
    }else if($type == 'RubinoPost'){
        $Cont1 = 19;
        if(isset($Message['forwarded_from'])){
            $Cont2 = 12;
        }
    }else if($type == 'RubinoStory'){
        $Cont1 = 20;
        if(isset($Message['forwarded_from'])){
            $Cont2 = 12;
        }
    }else if($type == 'Live'){
       $Cont1 = 21;
        if(isset($Message['forwarded_from'])){
            linkRemoverH($lib_LR,12);
            $Cont2 = 12;
        }
    }else if($type == 'Poll' || $type == 'Poll2'){
        $Cont1 = 4;
        if(isset($Message['forwarded_from'])){
            linkRemoverH($lib_LR,12);
            $Cont2 = 12;
        }
    }else if(isset($Message['sticker'])){
        $Cont1 = 5;
        if(isset($Message['forwarded_from'])){
            $Cont2 = 12;
        }
    }else if(isset($Message['file_inline'])){
        if(isset($Message['forwarded_from'])){
            $Cont1 = 12;
        }
        if(isset($Message['metadata'])){
            $meta_data_parts = $Message['metadata']['meta_data_parts'];
            foreach($meta_data_parts as $meta_data_part){
                $meta_data_part_type = $meta_data_part['type'];
                if($meta_data_part_type == 'MentionText'){
                    $Cont2 = 3;
                }
            }
        }
        if($text){
            $Cont6 = 90;
            $url = pro_search($text,'@');
            if($url){
                $Cont3 = 2;
            }
            $url0 = pro_search($text,'http');
            $url1 = pro_search($text,'.ir');
            $url2 = pro_search($text,'.com');
            if($url1 || $url2 || $url0){
                $Cont4 = 1;
            }
        }
        $type_file = $Message['file_inline']['type'];
        if($type_file == 'File'){
            $Cont5 = 6;
        }else if($type_file == 'Voice'){
            $Cont5 = 8;
        }else if($type_file == 'Image'){
            $Cont5 = 9;
        }else if($type_file == 'Gif'){
            $Cont5 = 7;
        }else if($type_file == 'Music'){
            $Cont5 = 10;
        }else if($type_file == 'Video'){
            $Cont5 = 11;
        }else{
            $robot->sendMessage(GUID_OMG,"$type_file");
            return true;
        }
    }else{
        $result = var_export($Message,true);
        $robot->sendMessage(GUID_OMG,"$result");
        $robot->sendMessage(GUID_OMG,"نتونستم بفهمم چه پیامی اومده :|");
        return true;
    }
    ///// CONT!
    if($Cont1 && $reme !== 'skip'){
        $reme = RemoveMember($message_id,$guid_message,$robot,$lib_LR,$lib_BN,$Setting,$Is_fulladmin,$Cont1);
    }
    if($Cont2 && $reme !== 'skip'){
            $reme = RemoveMember($message_id,$guid_message,$robot,$lib_LR,$lib_BN,$Setting,$Is_fulladmin,$Cont2);
    }
    if($Cont3 && $reme !== 'skip'){
            $reme = RemoveMember($message_id,$guid_message,$robot,$lib_LR,$lib_BN,$Setting,$Is_fulladmin,$Cont3);
    }
    if($Cont4 && $reme !== 'skip'){
            $reme = RemoveMember($message_id,$guid_message,$robot,$lib_LR,$lib_BN,$Setting,$Is_fulladmin,$Cont4);
    }
    if($Cont5 && $reme !== 'skip'){
            $reme = RemoveMember($message_id,$guid_message,$robot,$lib_LR,$lib_BN,$Setting,$Is_fulladmin,$Cont5);
    }
    if($Cont6 && $reme !== 'skip'){
            $reme = RemoveMember($message_id,$guid_message,$robot,$lib_LR,$lib_BN,$Setting,$Is_fulladmin,$Cont6);
    }
        if($reme && $reme !== 'skip' && $IS_YOU && $text){
                $level = true;
                if($Is_owner){
                    $pro_search = pro_search($text,"ارتقا به کاربر ویژه");
                    if($pro_search){
                        $level = false;
                        $pro_search = pro_search($text,"@");
                        if($pro_search){
                            $YOUSPEAK = false;
                            $text = str_replace("ارتقا به کاربر ویژه","","$text");
                            $text = str_replace("@","","$text");
                            $ID_USER = trim($text);
                            $user = GET_USER_BY_ID($ID_USER,$robot);
                            if(!$user){
                                SendMessage($robot,$lib_LR,"نتونستم انجامش بدم:(",$message_id);
                                return false;
                            }else if($user == 'skip'){
                                SendMessage($robot,$lib_LR,"اینو که نمیشه قشنگم",$message_id);
                            }else{
                                $Reply_guid_message = $user['user_guid'];
                                $is_fulladmin2 = isFullAdmins($Reply_guid_message,$lib);
        
                                if($Reply_guid_message == GUID){
                                    SendMessage($robot,$lib_LR,"عزیزم من خدم که کاربر ویژم",$message_id);
                                }else if($is_fulladmin2){
                                    if($guid_message == $Reply_guid_message){
                                        SendMessage($robot,$lib_LR,"زندگیم خودت که مالک منی",$message_id);
                                    }else{
                                        SendMessage($robot,$lib_LR,"زندگیم ایشون کاربر ویژه بودش",$message_id);
                                        setFAdmin($robot,$Reply_guid_message);
                                    }
                                }else{
                                    /// set full admin in database
                                    setFullAdmins($Reply_guid_message,$lib);
                                    $ALARM = "به کاربر ویژه ارتفا یافت. ✅";
                                    $TEXT = orders($ALARM);
                                    SendMessage($robot,$lib_LR,$TEXT,$message_id);
        
                                    setFAdmin($robot,$Reply_guid_message);
                                }
                            }
                        }else if($text =="ارتقا به کاربر ویژه"){
                            $YOUSPEAK = false;
                            $replyMessage = Reply_Message($Message,$robot);
                            if($replyMessage === 'skip'){
                                SendMessage($robot,$lib_LR,"شخصی که میخای کاربر ویژه بشه ریپلای کن",$message_id);
                            }else if(!$replyMessage){
                                SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                            }else{
                                $Reply_guid_message = get_guidUser($replyMessage);
                                if(!$Reply_guid_message){
                                    SendMessage($robot,$lib_LR,":|",$message_id);
                                }else{
                                    $Reply_message_id = $replyMessage['message_id'];
                                    $is_fulladmin2 = isFullAdmins($Reply_guid_message,$lib);
            
                                    if($Reply_guid_message == GUID){
                                        SendMessage($robot,$lib_LR,"عزیزم من خدم که کاربر ویژم",$message_id);
                                    }else if($is_fulladmin2){
                                        if($guid_message == $Reply_guid_message){
                                            SendMessage($robot,$lib_LR,"زندگیم خودت که مالک منی",$message_id);
                                        }else{
                                            SendMessage($robot,$lib_LR,"زندگیم ایشون کاربر ویژه بودش",$message_id);
                                            setFAdmin($robot,$Reply_guid_message);
                                        }
                                    }else{
                                        /// set full admin in database
                                        setFullAdmins($Reply_guid_message,$lib);
                                        
                                        $ALARM = "به کاربر ویژه ارتفا یافت. ✅";
                                        $TEXT = orders($ALARM);
                                        SendMessage($robot,$lib_LR,$TEXT,$message_id);
        
                                        setFAdmin($robot,$Reply_guid_message);
                                    }
                                }
                            }
                        }
                    }
                    $pro_search = pro_search($text,"ارتقا به ادمین");
                    if($pro_search){
                        $level = false;
                        $pro_search = pro_search($text,"@");
                        if($pro_search){
                            $YOUSPEAK = false;
                            $text = str_replace("ارتقا به ادمین","","$text");
                            $text = str_replace("@","","$text");
                            $ID_USER = trim($text);
                            $user = GET_USER_BY_ID($ID_USER,$robot);
                            if(!$user){
                                SendMessage($robot,$lib_LR,"نتونستم انجامش بدم:(",$message_id);
                                return false;
                            }else if($user == 'skip'){
                                SendMessage($robot,$lib_LR,"اینو که نمیشه قشنگم",$message_id);
                            }else{
                                $Reply_guid_message = $user['user_guid'];
        
                                if($Reply_guid_message ==  GUID){
                                    SendMessage($robot,$lib_LR,"عزیزم",$message_id);
                                }else{
                                    if($guid_message == $Reply_guid_message){
                                        SendMessage($robot,$lib_LR,"خدتو نمیتونم ادمین کنم مالک قشنگم",$message_id);
                                        setAdmin($robot,$Reply_guid_message);
                                    }else{
                                        // remove fulladmins in database
                                        unsetFullAdmins($Reply_guid_message,$lib);
        
                                        /// set admin
                                        setAdmins($Reply_guid_message,$lib);
        
                                        $ALARM = "ادمین شد. ✅";
                                        $TEXT = orders($ALARM);
                                        SendMessage($robot,$lib_LR,$TEXT,$message_id);
        
                                        setAdmin($robot,$Reply_guid_message);
                                    }
                                }
                            }
                        }else if($text == "ارتقا به ادمین"){
                            $YOUSPEAK = false;
                            $replyMessage = Reply_Message($Message,$robot);
                            if($replyMessage === 'skip'){
                                SendMessage($robot,$lib_LR,"شخصی که میخای کاربر ویژه بشه ریپلای کن",$message_id);
                            }else if(!$replyMessage){
                                SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                            }else{
                                $Reply_guid_message = get_guidUser($replyMessage);
                                if(!$Reply_guid_message){
                                    SendMessage($robot,$lib_LR,":|",$message_id);
                                }else{
                                    $Reply_message_id = $replyMessage['message_id'];
        
                                    if($Reply_guid_message == GUID){
                                        SendMessage($robot,$lib_LR,"عزیزم",$message_id);
                                    }else{
                                        if($guid_message == $Reply_guid_message){
                                            SendMessage($robot,$lib_LR,"خدتو نمیتونم ادمین کنم مالک قشنگم",$message_id);
                                            setAdmin($robot,$Reply_guid_message);
                                        }else{
                                            // remove fulladmins in database
                                            unsetFullAdmins($Reply_guid_message,$lib);
            
                                            /// set admin
                                            setAdmins($Reply_guid_message,$lib);
            
                                            $ALARM = "ادمین شد. ✅";
                                            $TEXT = orders($ALARM);
                                            SendMessage($robot,$lib_LR,$TEXT,$message_id);
            
                                            setAdmin($robot,$Reply_guid_message);
                                        }
                                    }
                                }
                            }
                        }
                        
                    }
                    $pro_search = pro_search($text,"برکناری");
                    if($pro_search){
                        $level = false;
                        $pro_search = pro_search($text,"@");
                        if($pro_search){
                            $YOUSPEAK = false;
                            $text = str_replace("برکناری","","$text");
                            $text = str_replace("@","","$text");
                            $ID_USER = trim($text);
                            $user = GET_USER_BY_ID($ID_USER,$robot);
                            if(!$user){
                                SendMessage($robot,$lib_LR,"نتونستم انجامش بدم:(",$message_id);
                                return false;
                            }else if($user == 'skip'){
                                SendMessage($robot,$lib_LR,"اینو که نمیشه قشنگم",$message_id);
                            }else{
                                $Reply_guid_message = $user['user_guid'];
        
                                if($Reply_guid_message == GUID){
                                    SendMessage($robot,$lib_LR,"عزیزم من خدم که رباتتم",$message_id);
                                }else{
                                    if($guid_message == $Reply_guid_message){
                                        SendMessage($robot,$lib_LR,"زندگیم خودت که مالک منی",$message_id);
                                    }
        
                                    // remove admin in database
                                    unsetAdmins($Reply_guid_message,$lib);
                                    // remove fulladmins in database
                                    unsetFullAdmins($Reply_guid_message,$lib);
        
                                    $ALARM = "برکنار شد."." ✅ ";
                                    $TEXT = orders($ALARM);
                                    SendMessage($robot,$lib_LR,$TEXT,$message_id);
        
                                    unsetAdmin($robot,$Reply_guid_message);
                                }
                            }
                        }else if($text == "برکناری"){
                            $YOUSPEAK = false;
                            $replyMessage = Reply_Message($Message,$robot);
                            if($replyMessage === 'skip'){
                                SendMessage($robot,$lib_LR,"شخصی که میخای برکنار بشه ریپلای کن",$message_id);
                            }else if(!$replyMessage){
                                SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                            }else{
                                $Reply_guid_message = get_guidUser($replyMessage);
                                if(!$Reply_guid_message){
                                    SendMessage($robot,$lib_LR,":|",$message_id);
                                }else{
                                    $Reply_message_id = $replyMessage['message_id'];
        
                                    if($Reply_guid_message == GUID){
                                        SendMessage($robot,$lib_LR,"عزیزم من خدم که رباتتم",$message_id);
                                    }else{
                                        if($guid_message == $Reply_guid_message){
                                            SendMessage($robot,$lib_LR,"زندگیم خودت که مالک منی",$message_id);
                                        }
        
                                        // remove admin in database
                                        unsetAdmins($Reply_guid_message,$lib);
                                        // remove fulladmins in database
                                        unsetFullAdmins($Reply_guid_message,$lib);
            
                                        $ALARM = "برکنار شد."." ✅ ";
                                        $TEXT = orders($ALARM);
                                        SendMessage($robot,$lib_LR,$TEXT,$message_id);
            
                                        unsetAdmin($robot,$Reply_guid_message);
                                    }
                                }
                            }
                        }
                        
                    }
                    if($text == 'اشتراک'){
                        setting_ENDTIME($robot,$lib);
                        SendMessage($robot,$lib_LR,"پی فرستادم سلطان. ✅",$message_id);
                        $YOUSPEAK = false; 
                    }
                    $pro_search = pro_search($text,"ربات");
                    $pro_search1 = pro_search($text,"https://rubika.ir/joing/");
                    if($pro_search && $pro_search1){
                        $YOUSPEAK = false;
                        $text = str_replace("ربات","","$text");
                        $text = str_replace("https://rubika.ir/joing/","","$text");
                        $hash_link = trim($text);
                        $result = join_groups($robot,$hash_link);
                        if($result){
                            SendMessageSpeakSelf($robot,GUID_U,$lib_LR,"اومدم عشقم",$message_id);
                        }else{
                            SendMessageSpeakSelf($robot,GUID_U,$lib_LR,"نتونستم بیام",$message_id);
                        }
                    }
                    $pro_search = pro_search($text,"GO");
                    $pro_search1 = pro_search($text,"https://rubika.ir/joing/");
                    if($pro_search && $pro_search1){
                        $YOUSPEAK = false;
                        $text = str_replace("GO","","$text");
                        $text = str_replace("https://rubika.ir/joing/","","$text");
                        $hash_link = trim($text);
                        $result = join_groups($robot,$hash_link);
                        if($result){
                            $GROUP_GUID = $result['group_guid'];
                            $lib->updateGuid_U(AUTH,$GROUP_GUID);
                            SendMessageSpeakSelf($robot,AOWNER,$lib_LR,"لینک گپ با موفقیت ثبت شد. ✅ \n\nچند دقیقه دیگه میام سلطان",$message_id);
                        }else{
                            SendMessageSpeakSelf($robot,AOWNER,$lib_LR,"نتونستم بیام",$message_id);
                        }
                    }
                    if($text == "GO OUT"){
                        $GUID_U = $lib->selectGUIDU(AUTH);
                        $GUID_U = $GUID_U['GUID_U'];
                        if(is_null($GUID_U) || $GUID_U == 'null'){
                            SendMessageSpeakSelf($robot,AOWNER,$lib_LR,"در گپی فعالیت نمیکنم سلطان",$message_id);
                        }else{
                            $YOUSPEAK = false;
                            $hash_link = trim($text);
                            $NameGap = $lib_LR->selectName_GUID(GUID_U);
                            $result = $lib->updateGuid_U(AUTH,'null');
                            if($result){
                                SendMessageSpeakSelf($robot,AOWNER,$lib_LR,"فعالیت روی گپ $NameGap با موفقیت متوقف شد. ✅ \n\nچند دقیقه دیگه غیر فعال میشه سلطان",$message_id);
                            }else{
                                SendMessageSpeakSelf($robot,AOWNER,$lib_LR,"نتونستم فعالیت رو متوقف کنم",$message_id);
                            }
                        }
                    }
                    $pro_search = pro_search($text,"ربات");
                    $pro_search1 = pro_search($text,"لف");
                    if($pro_search && $pro_search1){
                        $YOUSPEAK = false;
                        SendMessageSpeakSelf($robot,GUID_U,$lib_LR,"سلطان نمیتونم تنهات بزارم\nیپر پی وی",$message_id);
                        $TEXT = "برای لف دادنم از گپی که روش فعالیت میکنم\nاول باید فعالیتم رو متوقف کنی\nینی باید بگی \nGO OUT\n\nبعد از ۱۰ یا ۱۵ دقیقه\nفعالیت کاملا متوقف میشه\nو میتونید بگید ربات لف بده";
                        SendMessageSpeakSelf($robot,AOWNER,$lib_LR,$TEXT,$message_id);
                    }
                    if($text == 'تنظیم اسم' || $text == 'ست اسم' || $text == 'تنظیم نام'){
                        $YOUSPEAK = false;
                        $replyMessage = Reply_Message($Message,$robot);
                        if($replyMessage === 'skip'){
                            $result = setName($robot,NULL);
                        }else if(!$replyMessage){
                            SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                        }else{
                            if(isset($replyMessage['text'])){
                                $Reply_message_text = $replyMessage['text'];
                                $result = setName($robot,$Reply_message_text);
                            }else{
                                SendMessage($robot,$lib_LR,":|",$message_id);
                            }
                        }
                        if($result){
                            $TXT = 'اسمم تنظیم شد.'." ✅ ";
                            $TXT = orders($TXT);
                            SendMessage($robot,$lib_LR,"$TXT",$message_id);
                        }else{
                            $TXT = 'اسمم تنظیم نشد.'." ❌ ";
                            $TXT = orders($TXT);
                            SendMessage($robot,$lib_LR,"$TXT",$message_id);
                        }
                    }
                    if($text == 'تنظیم ایدی' || $text == 'ست ایدی' || $text == 'ست آیدی' || $text == 'تنظیم آیدی' ){
                        $YOUSPEAK = false;
                        $replyMessage = Reply_Message($Message,$robot);
                        if($replyMessage === 'skip'){
                            $result = setId($robot,NULL);
                        }else if(!$replyMessage){
                            SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                        }else{
                            if(isset($replyMessage['text'])){
                                $Reply_message_text = $replyMessage['text'];
                                $result = setId($robot,$Reply_message_text);
                            }else{
                                SendMessage($robot,$lib_LR,":|",$message_id);
                            }
                        }
                        if($result){
                            $TXT = 'ایدی تنظیم شد.'." ✅ ";
                            $TXT = orders($TXT);
                            SendMessage($robot,$lib_LR,"$TXT",$message_id);
                        }else{
                            $TXT = 'ایدی تنظیم نشد.'." ❌ ";
                            $TXT = orders($TXT);
                            SendMessage($robot,$lib_LR,"$TXT",$message_id);
                        }
                    }
                    if($text == 'تنظیم بیو' || $text == 'ست بیو' ){
                        $YOUSPEAK = false;
                        $replyMessage = Reply_Message($Message,$robot);
                        if($replyMessage === 'skip'){
                            $result = setBio($robot);
                        }else if(!$replyMessage){
                            SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                        }else{
                            if(isset($replyMessage['text'])){
                                $Reply_message_text = $replyMessage['text'];
                                $result = setBioDif($robot,$Reply_message_text);
                            }else{
                                SendMessage($robot,$lib_LR,":|",$message_id);
                            }
                        }
                        if($result){
                            $TXT = 'بیو تنظیم شد.'." ✅ ";
                            $TXT = orders($TXT);
                            SendMessage($robot,$lib_LR,"$TXT",$message_id);
                        }else{
                            $TXT = 'بیو تنظیم نشد.'." ❌ ";
                            $TXT = orders($TXT);
                            SendMessage($robot,$lib_LR,"$TXT",$message_id);
                        }
                    }
                }
                if($level){
                    if($Is_fulladmin){
                        if($text == 'ربات بیدار' || $text == 'ربات روشن'){
                            $ALARM = 'ربات بیدار است. ✅';
                            SAVEMSS($robot,7,1,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'ربات خواب' || $text == 'ربات خاموش'){
                            $ALARM = 'ربات خواب است. ✅';
                            $TEXT = orders($ALARM);
                            SendMessageZ($robot,$lib_LR,$TEXT,$message_id);
                            SAVEMSS($robot,7,2,$message_id,NULL,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'چالش خاموش'){
                            $ALARM = 'چالش خاموش است. ✅';
                            SAVEMSS($robot,15,2,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'چالش روشن'){
                            $ALARM = 'چالش روشن است. ✅';
                            SAVEMSS($robot,15,1,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'بیو خاموش'){
                            $ALARM = 'بیو خاموش است. ✅';
                            SAVEMSS($robot,16,2,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'بیو روشن'){
                            $ALARM = 'بیو روشن است. ✅';
                            SAVEMSS($robot,16,1,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'فکت خاموش'){
                            $ALARM = 'فکت خاموش است. ✅';
                            SAVEMSS($robot,17,2,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'فکت روشن'){
                            $ALARM = 'فکت روشن است. ✅';
                            SAVEMSS($robot,17,1,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'اعتراف خاموش'){
                            $ALARM = 'اعتراف خاموش است. ✅';
                            SAVEMSS($robot,18,2,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'اعتراف روشن'){
                            $ALARM = 'اعتراف روشن است. ✅';
                            SAVEMSS($robot,18,1,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'جوک خاموش'){
                            $ALARM = 'جوک خاموش است. ✅';
                            SAVEMSS($robot,19,2,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'جوک روشن'){
                            $ALARM = 'جوک روشن است. ✅';
                            SAVEMSS($robot,19,1,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'تکست خاموش'){
                            $ALARM = 'تکست خاموش است. ✅';
                            SAVEMSS($robot,20,2,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'تکست روشن'){
                            $ALARM = 'تکست روشن است. ✅';
                            SAVEMSS($robot,20,1,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'گپ باز'){
                            MGGroupDefaultAccess($robot,"SendMessages",1);
                            $ALARM = 'گپ باز است. ✅';
                            $TEXT = orders($ALARM);
                            SendMessageZ($robot,$lib_LR,$TEXT,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == 'گپ بسته'){
                            MGGroupDefaultAccess($robot,"SendMessages",2);
                            $ALARM = 'گپ بسته است. ❌';
                            $TEXT = orders($ALARM);
                            SendMessageZ($robot,$lib_LR,$TEXT,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == 'نمایش اخطار خاموش'){
                            $ALARM = 'نمایش اخطار خاموش شد. ❌';
                            SAVEMSS($robot,8,2,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'نمایش اخطار روشن'){
                            $ALARM = 'نمایش اخطار روشن شد. ✅';
                            SAVEMSS($robot,8,1,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'حالت سخنگو روشن'){
                            $ALARM = 'حالت سخنگو روشن شد. ✅';
                            SAVEMSS($robot,6,1,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'حالت سخنگو خاموش'){
                            $ALARM = 'حالت سخنگو خاموش شد. ❌';
                            SAVEMSS($robot,6,2,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'شناسایی تبچی روشن'){
                            $ALARM = 'شناسایی تبچی روشن است. ✅';
                            SAVEMSS($robot,9,1,$message_id,$ALARM,$lib_LR);
                            $TEXT = "مخای بچتی؟؟؟\n\nبپر پی ویم 😐✌️\n#ImActive";
                            $result = SendMessage($robot,$lib_LR,$TEXT,NULL);
                            if(isset($result['data']['chat_update']['chat']['last_message'])){
                                $lst_msm = $result['data']['chat_update']['chat']['last_message'];
                                $message_idMM = $lst_msm['message_id'];
                                $result = pin($robot,$message_idMM);
                                if($result){
                                    $ALARM = "پین شد."." ✅ ";
                                    $TEXT = orders($ALARM);
                                    SendMessage($robot,$lib_LR,$TEXT,NULL);
                                    setGroupDefaultAccess($robot,[]);
                                }
                            }
                            $YOUSPEAK = false;
                        }else if($text == 'شناسایی تبچی خاموش'){
                            $ALARM = 'شناسایی تبچی خاموش است. ❌';
                            SAVEMSS($robot,9,2,$message_id,$ALARM,$lib_LR);
                            setGroupDefaultAccess($robot,['SendMessages']);
                            $YOUSPEAK = false;
                        }else if($text == 'حذف کاربر قفل'){
                            $ALARM = 'حذف کاربر قفل است. ❌';
                            SAVEMSS($robot,12,2,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'محدودیت سختگیرانه ازاد' || $text == 'محدودیت سختگیرانه آزاد'){
                            $ALARM ='محدودیت سختگیرانه ازاد است. ✅';
                            SAVEMSS($robot,12,1,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'محدودیت سختگیرانه قفل'){
                            $ALARM ='محدودیت سختگیرانه قفل است. ❌';
                            SAVEMSS($robot,12,2,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'حذف کاربر ازاد' || $text == 'حذف کاربر آزاد' ){
                            $ALARM = 'حذف کاربر ازاد است. ✅';
                            SAVEMSS($robot,12,1,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'بلاک کردن روشن' || $text == 'بلاک کردن فعال' ){
                            $ALARM = ' بلاک کردن روشن است. ✅';
                            SAVEMSS($robot,13,1,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == 'بلاک کردن خاموش' || $text == 'بلاک کردن غیرفعال' ){
                            $ALARM = ' بلاک کردن خاموش است. ❌';
                            SAVEMSS($robot,13,2,$message_id,$ALARM,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == '/ListFullAdmins' || $text == 'لیست کاربران ویژه' || $text == 'لیست کاربر ویژه' ){
                            show_fulladmins($lib,$lib_BN,$robot,$message_id,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == '/ListAdmins' || $text == 'لیست مدیران' || $text == 'لیست ادمین ها'  || $text == 'لیست ادمین' ){
                            show_admins($lib,$lib_BN,$robot,$message_id,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == '/blockON'){
                            SAVEMSS($robot,13,1,$message_id,NULL,$lib_LR);
                            locks_panel($robot,$lib_LR,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == '/blockOFF'){
                            SAVEMSS($robot,13,2,$message_id,null,$lib_LR);
                            locks_panel($robot,$lib_LR,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == '/RobotON' ){
                            SAVEMSS($robot,7,1,$message_id,null,$lib_LR);
                            condition_panel($robot,$lib_LR,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == '/RobotOFF'){
                            $ALARM = 'ربات خواب است. ✅';
                            $TEXT = orders($ALARM);
                            SendMessageZ($robot,$lib_LR,$TEXT,$message_id);
                            SAVEMSS($robot,7,2,$message_id,null,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == '/WarnningOFF'){
                            SAVEMSS($robot,8,2,$message_id,null,$lib_LR);
                            condition_panel($robot,$lib_LR,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == '/WarnningON'){
                            SAVEMSS($robot,8,1,$message_id,null,$lib_LR);
                            condition_panel($robot,$lib_LR,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == '/SpeakingON'){
                            SAVEMSS($robot,6,1,$message_id,null,$lib_LR);
                            condition_panel($robot,$lib_LR,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == '/SpeakingOFF'){
                            SAVEMSS($robot,6,2,$message_id,null,$lib_LR);
                            condition_panel($robot,$lib_LR,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == '/TabchiOFF'){
                            SAVEMSS($robot,9,2,$message_id,NULL,$lib_LR);
                            setGroupDefaultAccess($robot,['SendMessages']);
                            condition_panel($robot,$lib_LR,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == '/TabchiON'){
                            SAVEMSS($robot,9,1,$message_id,NULL,$lib_LR);
                            setGroupDefaultAccess($robot,[]);
                            $TEXT = "مخای بچتی؟؟؟\n\nبپر پی ویم 😐✌️\n#ImActive";
                            $result = SendMessage($robot,$lib_LR,$TEXT,NULL);
                            if(isset($result['data']['chat_update']['chat']['last_message'])){
                                $lst_msm = $result['data']['chat_update']['chat']['last_message'];
                                $message_idMM = $lst_msm['message_id'];
                                $result = pin($robot,$message_idMM);
                                if($result){
                                    $ALARM = "پین شد."." ✅ ";
                                    $TEXT = orders($ALARM);
                                    SendMessage($robot,$lib_LR,$TEXT,NULL);
                                }
                            }
                            condition_panel($robot,$lib_LR,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == '/BanUserOFF'){
                            SAVEMSS($robot,10,2,$message_id,null,$lib_LR);
                            condition_panel($robot,$lib_LR,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == '/BanUserON'){
                            SAVEMSS($robot,10,1,$message_id,null,$lib_LR);
                            condition_panel($robot,$lib_LR,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == '/SetSpeed'){
                            speed_panel($robot,$message_id,$lib_LR);
                            $YOUSPEAK = false;
                        }else if($text == '/Set0s'){
                            SAVEMSS($robot,11,0,$message_id,null,$lib_LR);
                            condition_panel($robot,$lib_LR,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == '/Set1s'){
                            SAVEMSS($robot,11,1,$message_id,null,$lib_LR);
                            condition_panel($robot,$lib_LR,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == '/Set2s'){
                            SAVEMSS($robot,11,2,$message_id,null,$lib_LR);
                            condition_panel($robot,$lib_LR,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == '/Set3s'){
                            SAVEMSS($robot,11,3,$message_id,null,$lib_LR);
                            condition_panel($robot,$lib_LR,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == '/Set4s'){
                            SAVEMSS($robot,11,4,$message_id,null,$lib_LR);
                            condition_panel($robot,$lib_LR,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == '/Set5s'){
                            SAVEMSS($robot,11,5,$message_id,null,$lib_LR);
                            condition_panel($robot,$lib_LR,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == '/StrictOFF'){
                            SAVEMSS($robot,12,2,$message_id,null,$lib_LR);
                            condition_panel($robot,$lib_LR,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == '/StrictON'){
                            SAVEMSS($robot,12,1,$message_id,null,$lib_LR);
                            condition_panel($robot,$lib_LR,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == 'ثبت قوانین'){
                            $YOUSPEAK = false;
                            $replyMessage = Reply_Message($Message,$robot);
                            if($replyMessage === 'skip'){
                                SendMessage($robot,$lib_LR,"روی پیام قوانین ریپلای کن",$message_id);
                            }else if(!$replyMessage){
                                SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                            }else{
                                $Reply_guid_message = get_guidUser($replyMessage);
                                if(!$Reply_guid_message){
                                    SendMessage($robot,$lib_LR,":|",$message_id);
                                }else{
                                    $Reply_message_id = $replyMessage['message_id'];
                                    if(isset($replyMessage['text'])){
                                        $Reply_message_text = $replyMessage['text'];
                                        $ALARM = 'قوانین ثبت شد. ✅';
                                        SAVEMSS($robot,0,$Reply_message_text,$message_id,$ALARM,$lib_LR);                                    
                                    }else{
                                        SendMessage($robot,$lib_LR,":|",$message_id);
                                    }
                                }
                            }
                        }else if($text == 'ثبت یاداوری' || $text == 'ثبت یاد اوری' || $text == 'ثبت یادآوری' || $text == 'ثبت یاد آوری'){
                            $YOUSPEAK = false;
                            $replyMessage = Reply_Message($Message,$robot);
                            if($replyMessage === 'skip'){
                                SendMessage($robot,$lib_LR,"روی پیام یاداوری ریپلای کن ",$message_id);
                            }else if(!$replyMessage){
                                SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                            }else{
                                $Reply_guid_message = get_guidUser($replyMessage);
                                if(!$Reply_guid_message){
                                    SendMessage($robot,$lib_LR,":|",$message_id);
                                }else{
                                    $Reply_message_id = $replyMessage['message_id'];
                                    if(isset($replyMessage['text'])){
                                        $Reply_message_text = $replyMessage['text'];
                                        $ALARM = 'یاداوری ثبت شد. ✅';
                                        SAVEMSS($robot,1,$Reply_message_text,$message_id,$ALARM,$lib_LR);                                    
                                    }else{
                                        SendMessage($robot,$lib_LR,":|",$message_id);
                                    }
                                }
                            }
                        }else if($text == 'ثبت پاسخ به پیام پیوستن'){
                            $YOUSPEAK = false;
                            $replyMessage = Reply_Message($Message,$robot);
                            if($replyMessage === 'skip'){
                                SendMessage($robot,$lib_LR,"روی متن پاسخ به پیوستن ریپلای کن",$message_id);
                            }else if(!$replyMessage){
                                SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                            }else{
                                $Reply_guid_message = get_guidUser($replyMessage);
                                if(!$Reply_guid_message){
                                    SendMessage($robot,$lib_LR,":|",$message_id);
                                }else{
                                    $Reply_message_id = $replyMessage['message_id'];
                                    if(isset($replyMessage['text'])){
                                        $Reply_message_text = $replyMessage['text'];
                                        $ALARM = 'پاسخ یه پیام پیوستن ثبت شد. ✅';
                                        SAVEMSS($robot,4,$Reply_message_text,$message_id,$ALARM,$lib_LR);                                    
                                    }else{
                                        SendMessage($robot,$lib_LR,":|",$message_id);
                                    }
                                }
                            }
                        }else if($text == 'ثبت پاسخ به پیام کاربر افزوده شده'){
                            $YOUSPEAK = false;
                            $replyMessage = Reply_Message($Message,$robot);
                            if($replyMessage === 'skip'){
                                SendMessage($robot,$lib_LR,"روی متن پاسخ به کاربر افزوده شده ریپلای کن",$message_id);
                            }else if(!$replyMessage){
                                SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                            }else{
                                $Reply_guid_message = get_guidUser($replyMessage);
                                if(!$Reply_guid_message){
                                    SendMessage($robot,$lib_LR,":|",$message_id);
                                }else{
                                    $Reply_message_id = $replyMessage['message_id'];
                                    if(isset($replyMessage['text'])){
                                        $Reply_message_text = $replyMessage['text'];
                                        $ALARM = 'پاسخ به پیام کاربر افزوده شده ثبت شد. ✅';
                                        SAVEMSS($robot,2,$Reply_message_text,$message_id,$ALARM,$lib_LR);                                    
                                    }else{
                                        SendMessage($robot,$lib_LR,":|",$message_id);
                                    }
                                }
                            }
                        }else if($text == 'ثبت پاسخ به پیام کاربر حذف شده'){
                            $YOUSPEAK = false;
                            $replyMessage = Reply_Message($Message,$robot);
                            if($replyMessage === 'skip'){
                                SendMessage($robot,$lib_LR,"روی متن پاسخ به کاربر حذف شده ریپلای کن",$message_id);
                            }else if(!$replyMessage){
                                SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                            }else{
                                $Reply_guid_message = get_guidUser($replyMessage);
                                if(!$Reply_guid_message){
                                    SendMessage($robot,$lib_LR,":|",$message_id);
                                }else{
                                    $Reply_message_id = $replyMessage['message_id'];
                                    if(isset($replyMessage['text'])){
                                        $Reply_message_text = $replyMessage['text'];
                                        $ALARM = 'پاسخ به پیام کاربر حذف شده ثبت شد. ✅';
                                        SAVEMSS($robot,3,$Reply_message_text,$message_id,$ALARM,$lib_LR);                                    
                                    }else{
                                        SendMessage($robot,$lib_LR,":|",$message_id);
                                    }
                                }
                            }
                        }else if($text == 'ثبت پاسخ به پیام ترک کردن'){
                            $YOUSPEAK = false;
                            $replyMessage = Reply_Message($Message,$robot);
                            if($replyMessage === 'skip'){
                                SendMessage($robot,$lib_LR,"روی متن پاسخ به ترک کردن ریپلای کن",$message_id);
                            }else if(!$replyMessage){
                                SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                            }else{
                                $Reply_guid_message = get_guidUser($replyMessage);
                                if(!$Reply_guid_message){
                                    SendMessage($robot,$lib_LR,":|",$message_id);
                                }else{
                                    $Reply_message_id = $replyMessage['message_id'];
                                    if(isset($replyMessage['text'])){
                                        $Reply_message_text = $replyMessage['text'];
                                        $ALARM = 'پاسخ به پیام ترک کردن ثبت شد. ✅';
                                        SAVEMSS($robot,5,$Reply_message_text,$message_id,$ALARM,$lib_LR);                                    
                                    }else{
                                        SendMessage($robot,$lib_LR,":|",$message_id);
                                    }
                                }
                            }
                        }else if($text == 'ثبت پاسخ به پیام اد شدن'){
                            $YOUSPEAK = false;
                            $replyMessage = Reply_Message($Message,$robot);
                            if($replyMessage === 'skip'){
                                SendMessage($robot,$lib_LR,"روی متن پاسخ به اد شدن ریپلای کن",$message_id);
                            }else if(!$replyMessage){
                                SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                            }else{
                                $Reply_guid_message = get_guidUser($replyMessage);
                                if(!$Reply_guid_message){
                                    SendMessage($robot,$lib_LR,":|",$message_id);
                                }else{
                                    $Reply_message_id = $replyMessage['message_id'];
                                    if(isset($replyMessage['text'])){
                                        $Reply_message_text = $replyMessage['text'];
                                        $ALARM = 'پاسخ به پیام اد شدن ثبت شد. ✅';
                                        SAVEMSS($robot,14,$Reply_message_text,$message_id,$ALARM,$lib_LR);                                    
                                    }else{
                                        SendMessage($robot,$lib_LR,":|",$message_id);
                                    }
                                }
                            }
                        }else if($text == 'گوید' || $text == 'گویدش' || $text == 'گاید' || $text == 'گایدت' || $text == 'گایدش' || $text == 'گویدت'){
                            $YOUSPEAK = false;
                            $replyMessage = Reply_Message($Message,$robot);
                            if($replyMessage === 'skip'){
                                $TEXT = mini('گویدت :').$guid_message;
                                SendMessage($robot,$lib_LR,"$TEXT",$message_id);
                            }else if(!$replyMessage){
                                SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                            }else{
                                $Reply_guid_message = get_guidUser($replyMessage);
                                if(!$Reply_guid_message){
                                    SendMessage($robot,$lib_LR,":|",$message_id);
                                }else{
                                    $TEXT = mini('گویدش :').$Reply_guid_message;
                                    SendMessage($robot,$lib_LR,"$TEXT",$message_id);
                                }
                            }
                        }else if($text == 'ثبت لقب'){
                            $YOUSPEAK = false;
                            $replyMessage = Reply_Message($Message,$robot);
                            if($replyMessage === 'skip'){
                                SendMessage($robot,$lib_LR,"روی پیام اسم شخص موردنظر ریپلای کن ",$message_id);
                            }else if(!$replyMessage){
                                SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                            }else{
                                $Reply_guid_message = get_guidUser($replyMessage);
                                if(!$Reply_guid_message){
                                    SendMessage($robot,$lib_LR,":|",$message_id);
                                }else{
                                    $Reply_message_id = $replyMessage['message_id'];
                                    if(isset($replyMessage['text'])){
                                        $Reply_message_text = $replyMessage['text'];
        
                                        $TXT = 'لقبت ثبت شد. ✅';
                                        if($Reply_guid_message == GUID){
                                            $TXT = 'لقبم ثبت شد. ✅';
                                        }
                                        
                                        $text = utf8_encode($Reply_message_text);
                                        $result_B = $lib_BN->updateName(GUID_U,$Reply_guid_message,$text);
                                        if($result_B){
                                            SendMessage($robot,$lib_LR,"$TXT",$Reply_message_id);
                                        }else{
                                            return false;
                                        }
                                        
                                    }else{
                                        SendMessage($robot,$lib_LR,":|",$message_id);
                                    }
                                }
                            }
                        }else if($text == 'وضعیت گپ' || $text == 'وضعیت گروه' || $text == '/ChatStatus'){
                            $YOUSPEAK = false;
                            $TXT = Status_all($lib,$lib_LR,$robot,$lib_BN);
                            $result = SendMessage($robot,$lib_LR,$TXT,$message_id);
                        }else if($text == 'متن های ثبت شده'){
                            dashboard($lib_LR,$robot,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == 'داشبورد'){
                            state($lib_LR,$robot,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == 'مسدود' || $text == 'بلاک' ){
                            $YOUSPEAK = false;
                            $replyMessage = Reply_Message($Message,$robot);
                            if($replyMessage === 'skip'){
                                SendMessage($robot,$lib_LR,"روی شخص موردنظر ریپلای کن ",$message_id);
                            }else if(!$replyMessage){
                                SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                            }else{
                                $Reply_guid_message = get_guidUser($replyMessage);
                                if(!$Reply_guid_message){
                                    SendMessage($robot,$lib_LR,":|",$message_id);
                                }else{
                                    $is_admin = isAdmin($Reply_guid_message,$lib);
                                    if($Reply_guid_message == GUID){
                                        SendMessage($robot,$lib_LR,"عییزم:|",$message_id);
                                    }else if($is_admin){
                                        if($guid_message == $Reply_guid_message){
                                            SendMessage($robot,$lib_LR,"نپسم چرا اخه تو رو بلاک کنم",$message_id);
                                        }else{
                                            SendMessage($robot,$lib_LR,"عشقم ادمینه",$message_id);
                                        }
                                    }else{
                                        $result = block_user($robot,$Reply_guid_message);
                                        if($result){
                                            $ALARM = "مسدود شد."." ✅ ";
                                            $TEXT = orders($ALARM);
                                            SendMessage($robot,$lib_LR,$TEXT,$message_id);
                                        }else{
                                            $ALARM = "مسدود نشد."." ❌ ";
                                            $TEXT = orders($ALARM);
                                            SendMessage($robot,$lib_LR,$TEXT,$message_id);
                                        }
                                    }
                                }
                            }
                        }else if($text == 'رفع مسدودیت'){
                            $YOUSPEAK = false;
                            $replyMessage = Reply_Message($Message,$robot);
                            if($replyMessage === 'skip'){
                                SendMessage($robot,$lib_LR,"روی شخص موردنظر ریپلای کن ",$message_id);
                            }else if(!$replyMessage){
                                SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                            }else{
                                $Reply_guid_message = get_guidUser($replyMessage);
                                if(!$Reply_guid_message){
                                    SendMessage($robot,$lib_LR,":|",$message_id);
                                }else{
                                    $is_admin = setAdmins($Reply_guid_message,$lib);
                                    if($Reply_guid_message == GUID){
                                        SendMessage($robot,$lib_LR,"عییزم:|",$message_id);
                                    }else if($is_admin){
                                        if($guid_message == $Reply_guid_message){
                                            SendMessage($robot,$lib_LR,"نپسم چرا اخه تو رو بلاک کنم",$message_id);
                                        }else{
                                            SendMessage($robot,$lib_LR,"عشقم ادمینه",$message_id);
                                        }
                                    }else{
                                        $result = unblock_user($robot,$Reply_guid_message);
                                        if($result){
                                            $ALARM = "رفع مسدودیت شد."." ✅ ";
                                            $TEXT = orders($ALARM);
                                            SendMessage($robot,$lib_LR,$TEXT,$message_id);
                                        }else{
                                            $ALARM = "رفع مسدودیت نشد."." ❌ ";
                                            $TEXT = orders($ALARM);
                                            SendMessage($robot,$lib_LR,$TEXT,$message_id);
                                        }
                                    }
                                }
                            }
                        }else if($text == 'تغییر لینک'){
                            $YOUSPEAK = false;
                            $result = setLink($robot);
                            if($result){
                                $ALARM = "لینک گروه تغییر یافت."." ✅ ";
                                $TEXT = orders($ALARM);
                                SendMessage($robot,$lib_LR,$TEXT,$message_id);
                                $lib->updateconnection_time_GUiD(AUTH,0);
                            }
                        }else if($text == 'بررسی' || $text == 'برسی'){
                            $YOUSPEAK = false;
                            $replyMessage = Reply_Message($Message,$robot);
                            if($replyMessage === 'skip'){
                                $result_B = $lib_BN->select(GUID_U,$guid_message);
                                if(is_null($result_B)){
                                    $user = GET_USER_BY_GUID($guid_message,$robot);
                                    $FLName = 'NO NAME!';
                                    if(isset($user['first_name'])){
                                        $FLName = $user['first_name'];
                                    }
                                    $max_array = $lib_LR->selectMax_GUID(GUID_U);
                                    $FLName = utf8_encode($FLName);
                                    $info = utf8_encode('اصلی ثبت نشده');
                                    $Max = $max_array['Max'];
                                    $info_bands['Guid_gap'] = GUID_U;
                                    $info_bands['Guid_user'] = $guid_message;
                                    $info_bands['state'] = 0;
                                    $info_bands['Max'] = $Max;
                                    $info_bands['report'] =0;
                                    $info_bands['info'] = $info;
                                    $info_bands['name'] = "$FLName";
                                    $lib_BN->insert($info_bands);
                                    SendMessage($robot,$lib_LR,"ردپایی ازش در گپ نیست!",$message_id);
                                }else{
                                    $name = $result_B['name'];
                                    $max = $result_B['Max'];
                                    $state = $result_B['state'];
                                    if(AOWNER == $guid_message){
                                        $who = 'مالک';
                                    }else{
                                        $isit = isFullAdmins($guid_message,$lib);
                                        if($isit){
                                            $who = "کاربر ویژه";
                                        }else{
                                            $isit = isAdmin($guid_message,$lib);
                                            if($isit){
                                                $who = "ادمین";
                                            }else{
                                                $who = "کاربر عادی";
                                            }
                                        }
                                    }
                                    $name = utf8_decode($name);
                                    $TEXT = title('وضعیت شخص');
                                    $TEXT .= mini('لقب : ').$name."\n";
                                    $TEXT .= mini('اخطار : ').$state.' از '.$max."\n";
                                    $TEXT .= mini('مقام : ').$who;
                                    $TEXT = end_form($TEXT);
                                    SendMessage($robot,$lib_LR,"$TEXT",$message_id);
                                }
                            }else if(!$replyMessage){
                                SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                            }else{
                                $Reply_guid_message = get_guidUser($replyMessage);
                                if(!$Reply_guid_message){
                                    SendMessage($robot,$lib_LR,":|",$message_id);
                                }else{
                                    $result_B = $lib_BN->select(GUID_U,$Reply_guid_message);
                                    if(is_null($result_B)){
                                        $user = GET_USER_BY_GUID($Reply_guid_message,$robot);
                                        if(!$user){
                                            return false;
                                        }
                                        $FLName = $user['first_name'];
                                        $max_array = $lib_LR->selectMax_GUID(GUID_U);
                                        $FLName = utf8_encode($FLName);
                                        $info = utf8_encode('اصلی ثبت نشده');
                                        $Max = $max_array['Max'];
                                        $info_bands['Guid_gap'] =GUID_U;
                                        $info_bands['Guid_user'] =$Reply_guid_message;
                                        $info_bands['state'] = 0;
                                        $info_bands['Max'] = $Max;
                                        $info_bands['report'] =$Max;
                                        $info_bands['info'] = $info;
                                        $info_bands['name'] = "$FLName";
                                        $lib_BN->insert($info_bands);
                                        SendMessage($robot,$lib_LR,"ردپایی ازش در گپ نیست!",$message_id);
                                    }else{
                                        $name = $result_B['name'];
                                        $max = $result_B['Max'];
                                        $state = $result_B['state'];
                                        if(AOWNER == $Reply_guid_message){
                                            $who = 'مالک';
                                        }else{
                                            $isit = isFullAdmins($Reply_guid_message,$lib);
                                            if($isit){
                                                $who = "کاربر ویژه";
                                            }else{
                                                $isit = isAdmin($Reply_guid_message,$lib);
                                                if($isit){
                                                    $who = "ادمین";
                                                }else{
                                                    $who = "کاربر عادی";
                                                }
                                            }
                                        }
                                        $name = utf8_decode($name);
                                        $TEXT = title('وضعیت شخص');
                                        $TEXT .= mini('لقب : ').$name."\n";
                                        $TEXT .= mini('اخطار : ').$state.' از '.$max."\n";
                                        $TEXT .= mini('مقام : ').$who;
                                        $TEXT = end_form($TEXT);
                                        SendMessage($robot,$lib_LR,"$TEXT",$message_id);
                                    }
                                }
                            }
                        }else if($text == 'پنل' || $text == '/BACKPanel'){
                            $TEXT = "💠 | ᑭᗩᑎᗴᒪ"."\n";
                            $TEXT .= COMD;
                            $TEXT .= "/SETTING"." လ "."تنظیمات"."\n\n";
                            $TEXT .= "/CONDITION"." လ "."وضعیت"."\n\n";
                            $TEXT .= "/STATUS"." လ "."آمار"."\n\n";
                            $TEXT .= "/LOCKS"." လ "."قفل"."\n\n";
                            // $TEXT .= "/TOOLS"." လ "."ابزار"."\n\n";
                            $TEXT .= "/GAMES"." လ "."بازی"."\n\n";
                            // $TEXT .= "/SAVES"." လ "."ذخیره شده ها"."\n\n";
                            SendMessage($robot,$lib_LR,"$TEXT",$message_id);
                            $YOUSPEAK = false;
                        }else if($text == 'تنظیمات' || $text == '/SETTING'){
                            setting_panel($robot,$lib_LR,$message_id);
                            $YOUSPEAK = false; 
                        }else if($text == 'وضعیت' || $text == '/CONDITION'){
                            condition_panel($robot,$lib_LR,$message_id);
                            $YOUSPEAK = false; 
                        }else if($text == 'آمار' ||$text == 'امار' || $text == '/STATUS'){
                            $TEXT = "📜 | ՏTᗩTᑌՏ"."\n";
                            $TEXT .= COMD;
                            $TEXT .= "/ChatActivity"." လ "."فعالیت گپ"."\n\n";
                            $TEXT .= "/MemberActivity"." လ "."فعالیت اعضا"."\n\n";
                            $TEXT .= "/ChatStatus"." လ "."وضعیت گپ"."\n\n";
                            $TEXT .= "/ListChats"." လ "."لیست گپ ها"."\n\n";
                            $TEXT .= "/TopMembers"." လ "."برترین کاربران"."\n\n";
                            $TEXT .= "/BACKPanel"." ⌫ "."بازگشت";
                            SendMessage($robot,$lib_LR,"$TEXT",$message_id);
                            $YOUSPEAK = false;
                        }else if($text == "قفل ها" || $text == "قفل" || $text == '/LOCKS'){
                            locks_panel($robot,$lib_LR,$message_id);
                            $YOUSPEAK = false;
                        }else if($text == "کاربردی" || $text == '/PRACTICALS'){
                            $TEXT = "🛠 | ᑭᖇᗩTIᑕᗩᒪՏ"."\n";
                            $TEXT .= COMD;
                            $TEXT .= "هنوز این بخش اماده نشده است."."\n\n";
                            $TEXT .= "/BACKPanel"." ⌫ "."بازگشت";
                            SendMessage($robot,$lib_LR,"$TEXT",$message_id);
                            $YOUSPEAK = false;
                        }else if($text == "بازی ها" || $text == "بازی" || $text == '/GAMES'){
                            $TEXT = "💎 | ᘜᗩᗰᗴՏ"."\n";
                            $TEXT .= COMD;
                            $TEXT .= "/Challenge"." လ "."چالش"."\n\n";
                            $TEXT .= "/BACKPanel"." ⌫ "."بازگشت";
                            SendMessage($robot,$lib_LR,"$TEXT",$message_id);
                            $YOUSPEAK = false;
                        }else if($text == 'لیست گپ ها' || $text == 'لیست گروه ها' || $text == '/ListChats'){
                            $YOUSPEAK = false;
                            $TXT = Gaps_all($lib_LR,$lib);
                            SendMessage($robot,$lib_LR,$TXT,$message_id);
                        }else if($text == 'ثبت اصل'){
                            $YOUSPEAK = false;
                            $replyMessage = Reply_Message($Message,$robot);
                            if($replyMessage === 'skip'){
                                SendMessage($robot,$lib_LR,"روی پیام اصل شخص موردنظر ریپلای کن ",$message_id);
                            }else if(!$replyMessage){
                                SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                            }else{
                                $Reply_guid_message = get_guidUser($replyMessage);
                                if(!$Reply_guid_message){
                                    SendMessage($robot,$lib_LR,":|",$message_id);
                                }else{
                                    $Reply_message_id = $replyMessage['message_id'];
                                    if(isset($replyMessage['text'])){
                                        $Reply_message_text = $replyMessage['text'];
                                        
                                        $TXT = 'اصلت ثبت شد.'." ✅ ";
                                        if($Reply_guid_message == GUID){
                                            $TXT = 'اصلم ثبت شد. ✅';
                                        }
                                        $TXT = orders($TXT);
                                        
                                        $text = utf8_encode($Reply_message_text);
                                        $result_B = $lib_BN->updateInfo(GUID_U,$Reply_guid_message,$text);
                                        if($result_B){
                                            SendMessage($robot,$lib_LR,"$TXT",$Reply_message_id);
                                        }else{
                                            return false;
                                        }
                                        
                                    }else{
                                        SendMessage($robot,$lib_LR,":|",$message_id);
                                    }
                                }
                            }
                        }else if($text == 'ویس کال' || $text == 'ویس چت' || $text == 'گفتگو صوتی' || $text == 'کال'){
                            $YOUSPEAK = false;
                            $result = voice_call($robot);
                            if($result){
                                $ALARM = "ویس کال ایجاد شد."." ✅ ";
                                $TEXT = orders($ALARM);
                                SendMessage($robot,$lib_LR,$TEXT,$message_id);
                            }else{
                                $ALARM = "ویس کال ایجاد نشد."." ❌ ";
                                $TEXT = orders($ALARM);
                                SendMessage($robot,$lib_LR,$TEXT,$message_id);
                            }
                        }else if($text == 'قطع ویس کال' || $text == 'قطع ویس چت' || $text == 'قطع گفتگو صوتی' || $text == 'قطع کال'){
                            $YOUSPEAK = false;
                            $result = stop_voiceChat($robot,$message_id,$lib_LR);
                            if($result){
                                $ALARM = "ویس کال قطع شد."." ✅ ";
                                $TEXT = orders($ALARM);
                                SendMessage($robot,$lib_LR,$TEXT,$message_id);
                            }else{
                                $ALARM = "ویس کال قطع نشد."." ❌ ";
                                $TEXT = orders($ALARM);
                                SendMessage($robot,$lib_LR,$TEXT,$message_id);
                            }
                        }else if($text == 'پین' || $text == 'سنجاق'){
                            $YOUSPEAK = false;
                            $replyMessage = Reply_Message($Message,$robot);
                            if($replyMessage === 'skip'){
                                SendMessage($robot,$lib_LR,"پیامی که میخای پین بشه ریپلای کن",$message_id);
                            }else if(!$replyMessage){
                                SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                            }else{
                                $Reply_message_id = $replyMessage['message_id'];
                                $result = pin($robot,$Reply_message_id);
                                if($result){
                                    $ALARM = "پین شد."." ✅ ";
                                    $TEXT = orders($ALARM);
                                    SendMessage($robot,$lib_LR,$TEXT,$message_id);
                                }else{
                                    $ALARM = "پین نشد."." ❌ ";
                                    $TEXT = orders($ALARM);
                                    SendMessage($robot,$lib_LR,$TEXT,$message_id);
                                }
                            }
                        }else if($text == '/Challenge' || $text == 'بازی چالش'){
                            $TEXT = "💠 #ꕥᑕᕼᗩᒪᒪᗴᑎᘜᵍᵃᵐᵉ

💠 | بازی چالش‍ــ...

★ برای پرسش از خودتون فقط کافیه کلمه #چالش رو بفرس

★ برای پرسش از شخص دیگری ، روی پیامش ریپلای کنید و کلمه #چالش رو بفرس 

★ برای پرسش از روی لیست سوالات خودتون فقط کافیه روی لیست سوالات ریپلای کنی و بگی #بپرس 

که ربات بصورت تصادفی یک سوال رو انتخاب می‌کنه و شماره سوالو ارسال میکنه 

★ برای ثبت سوالات جرعت حقیقت کافیه‌ بگی

- یاد بگیر ج ح #نام.دلخواه = سوالات جرعت حقیقت

و برای دریافت سوالات کافیه بگی 

- ج ح #نام.دلخواه

~ برای مثال

#ثبت‌سوالات 
یاد بگیر ج ح مثبت ۱۸ = لیست سوالات 

#دریافت‌سوالات
ج ح مثبت ۱۸

★ برای خاموش یا روشن کردن بازی
    
- چالش خاموش
- چالش روشن";
                            SendMessage($robot,$lib_LR,"$TEXT",$message_id);
                            $YOUSPEAK = false;
                        }
                        if($YOUSPEAK){
                            $pro_search = pro_search($text,"بن");
                            if($pro_search){
                                $pro_search = pro_search($text,"@");
                                if($pro_search){
                                    $YOUSPEAK = false;
                                    $text = str_replace("بن","","$text");
                                    $text = str_replace("@","","$text");
                                    $ID_USER = trim($text);
                                    $user = GET_USER_BY_ID($ID_USER,$robot);
                                    if(!$user){
                                        SendMessage($robot,$lib_LR,"نتونستم انجامش بدم:(",$message_id);
                                    }else if($user == 'skip'){
                                        SendMessage($robot,$lib_LR,"اینو که نمیشه قشنگم",$message_id);
                                    }else{
                                        $Reply_guid_message = $user['user_guid'];
            
                                        $is_fulladmin2 = isFullAdmins($Reply_guid_message,$lib);
                                        if($Reply_guid_message == GUID){
                                            SendMessage($robot,$lib_LR,"پفیوز خدمو برا چی اخه بپاکم",$message_id);
                                        }else if(AOWNER == $Reply_guid_message){
                                            SendMessage($robot,$lib_LR,"عشق زندگیمو ک نمیتونم بپاکم:|",$message_id);
                                        }else if($is_fulladmin2){
                                            if($guid_message == $Reply_guid_message){
                                                SendMessage($robot,$lib_LR,"خدتو نمیتونم بپاکم:|",$message_id);
                                            }else{
                                                SendMessage($robot,$lib_LR,"جونم کاربر ویژه رو نمیتونی بپاکی",$message_id);
                                            }
                                        }else{
                                            $result = BanUser($robot,$Reply_guid_message);
                                            if($result){
                                                $result_B = $lib_BN->select(GUID_U,$Reply_guid_message);
                                                if(is_null($result_B)){
                                                    $user = GET_USER_BY_GUID($Reply_guid_message,$robot);
                                                    if(!$user){
                                                        return false;
                                                    }
                                                    $FLName = $user['first_name'];
                                                    $max_array = $lib_LR->selectMax_GUID(GUID_U);
                                                    $FLName = utf8_encode($FLName);
                                                    $info = utf8_encode('اصلی ثبت نشده');
                                                    $Max = $max_array['Max'];
                                                    $info_bands['Guid_gap'] =GUID_U;
                                                    $info_bands['Guid_user'] =$Reply_guid_message;
                                                    $info_bands['state'] = 0;
                                                    $info_bands['Max'] = $Max;
                                                    $info_bands['report'] =$Max;
                                                    $info_bands['info'] = $info;
                                                    $info_bands['name'] = "$FLName";
                                                    $lib_BN->insert($info_bands);
                                                }else{
                                                    $Max = $result_B['Max'];
                                                    $lib_BN->updateState(GUID_U,$Reply_guid_message,$Max);
                                                }
                                                if(isset($result['data']['chat_update']['chat']['last_message'])){
                                                    $left_info = $result['data']['chat_update']['chat']['last_message'];
                                                    $leftedUser = $left_info['message_id'];
                                                    SendMessage($robot,$lib_LR,"بای بای",$leftedUser);
                                                }else{
                                                    $ALARM = "بن شد."." ✅ ";
                                                    $TEXT = orders($ALARM);
                                                    SendMessage($robot,$lib_LR,$TEXT,$message_id);
                                                }
                                            }
                                        }
                                    }
                                }else if($text == 'بن'){
                                    $YOUSPEAK = false;
                                    $replyMessage = Reply_Message($Message,$robot);
                                    if($replyMessage === 'skip'){
                                        SendMessage($robot,$lib_LR,"کسی که میخای بن بشه ریپلای کن",$message_id);
                                    }else if(!$replyMessage){
                                        SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                                    }else{
                                        $Reply_guid_message = get_guidUser($replyMessage);
                                        if(!$Reply_guid_message){
                                            SendMessage($robot,$lib_LR,":|",$message_id);
                                        }else{
                                            $Reply_message_id = $replyMessage['message_id'];
                                            $Reply_message_time = $replyMessage['time'];
            
                                            $is_fulladmin2 = isFullAdmins($Reply_guid_message,$lib);
                                            if($Reply_guid_message == GUID){
                                                SendMessage($robot,$lib_LR,"پفیوز خدمو برا چی اخه بپاکم",$message_id);
                                            }else if(AOWNER == $Reply_guid_message){
                                                SendMessage($robot,$lib_LR,"عشق زندگیمو ک نمیتونم بپاکم:|",$message_id);
                                            }else if($is_fulladmin2){
                                                if($guid_message == $Reply_guid_message){
                                                    SendMessage($robot,$lib_LR,"خدتو نمیتونم بپاکم:|",$message_id);
                                                }else{
                                                    SendMessage($robot,$lib_LR,"جونم کاربر ویژه رو نمیتونی بپاکی",$message_id);
                                                }
                                            }else{
                                                $result = BanUser($robot,$Reply_guid_message);
                                                if($result){
                                                    $result_B = $lib_BN->select(GUID_U,$Reply_guid_message);
                                                    if(is_null($result_B)){
                                                        $user = GET_USER_BY_GUID($Reply_guid_message,$robot);
                                                        if(!$user){
                                                            return false;
                                                        }
                                                        $FLName = $user['first_name'];
                                                        $max_array = $lib_LR->selectMax_GUID(GUID_U);
                                                        $FLName = utf8_encode($FLName);
                                                        $info = utf8_encode('اصلی ثبت نشده');
                                                        $Max = $max_array['Max'];
                                                        $info_bands['Guid_gap'] =GUID_U;
                                                        $info_bands['Guid_user'] =$Reply_guid_message;
                                                        $info_bands['state'] = 0;
                                                        $info_bands['Max'] = $Max;
                                                        $info_bands['report'] =$Max;
                                                        $info_bands['info'] = $info;
                                                        $info_bands['name'] = "$FLName";
                                                        $lib_BN->insert($info_bands);
                                                    }else{
                                                        $Max = $result_B['Max'];
                                                        $lib_BN->updateState(GUID_U,$Reply_guid_message,$Max);
                                                    }
                                                    if(isset($result['data']['chat_update']['chat']['last_message'])){
                                                        $left_info = $result['data']['chat_update']['chat']['last_message'];
                                                        $leftedUser = $left_info['message_id'];
                                                        SendMessage($robot,$lib_LR,"بای بای",$leftedUser);
                                                    }else{
                                                        $ALARM = "بن شد."." ✅ ";
                                                        $TEXT = orders($ALARM);
                                                        SendMessage($robot,$lib_LR,$TEXT,$message_id);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            $pro_search = pro_search($text,"اخطار");
                            if($pro_search){
                                $pro_search = pro_search($text,"@");
                                if($pro_search){
                                    $YOUSPEAK = false;
                                    $text = str_replace("اخطار","","$text");
                                    $text = str_replace("@","","$text");
                                    $ID_USER = trim($text);
                                    $user = GET_USER_BY_ID($ID_USER,$robot);
                                    if(!$user){
                                        SendMessage($robot,$lib_LR,"نتونستم انجامش بدم:(",$message_id);
                                        return false;
                                    }else if($user == 'skip'){
                                        SendMessage($robot,$lib_LR,"اینو که نمیشه قشنگم",$message_id);
                                    }else{
                                        $Reply_guid_message = $user['user_guid'];
                                        $is_fulladmin2 = isFullAdmins($Reply_guid_message,$lib);
                                        if($Reply_guid_message == GUID){
                                            SendMessage($robot,$lib_LR,"عوضی خدمو برا چی اخه اخطار بدم",$message_id);
                                        }else if(AOWNER == $Reply_guid_message){
                                            SendMessage($robot,$lib_LR,"عشق زندگیمو ک نمیتونم اخطار بدم:|",$message_id);
                                        }else if($is_fulladmin2){
                                            if($guid_message == $Reply_guid_message){
                                                SendMessage($robot,$lib_LR,"خدتو اخطار بدم:|",$message_id);
                                            }else{
                                                SendMessage($robot,$lib_LR,"فداتشم کاربر ویژه رو نمیتونی اخطار بدی",$message_id);
                                            }
                                        }else{
                                            RemoveMember(NULL,$Reply_guid_message,$robot,$lib_LR,$lib_BN,$Setting,$Is_admin,100);
                                        }
                                    }
                                }else if($text == 'اخطار'){
                                    $YOUSPEAK = false;
                                    $replyMessage = Reply_Message($Message,$robot);
                                    if($replyMessage === 'skip'){
                                        SendMessage($robot,$lib_LR,"کسی که میخای اخطار بدی ریپلای کن",$message_id);
                                    }else if(!$replyMessage){
                                        SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                                    }else{
                                        $Reply_guid_message = get_guidUser($replyMessage);
                                        if(!$Reply_guid_message){
                                            SendMessage($robot,$lib_LR,":|",$message_id);
                                        }else{
                                            $Reply_message_id = $replyMessage['message_id'];
                                            $is_fulladmin2 = isFullAdmins($Reply_guid_message,$lib);
                                            if($Reply_guid_message == GUID){
                                                SendMessage($robot,$lib_LR,"اشغال خدمو برا چی اخه اخطار بدم",$message_id);
                                            }else if(AOWNER == $Reply_guid_message){
                                                SendMessage($robot,$lib_LR,"عشق زندگیمو ک نمیتونم اخطار بدم:|",$message_id);
                                            }else if($is_fulladmin2){
                                                if($guid_message == $Reply_guid_message){                               
                                                    SendMessage($robot,$lib_LR,"خدتو اخطار بدم:|",$message_id);
                                                }else{         
                                                    SendMessage($robot,$lib_LR,"فداتشم کاربر ویژه رو نمیتونی اخطار بدی",$message_id);
                                                }
                                            }else{
                                                RemoveMember($Reply_message_id,$Reply_guid_message,$robot,$lib_LR,$lib_BN,$Setting,$Is_admin,100);
                                            }
                                        }
                                    }
                                }
                            }
                            $pro_search = pro_search($text,"ارتقا");
                            if($pro_search){
                                $pro_search = pro_search($text,"@");
                                if($pro_search){
                                    $YOUSPEAK = false;
                                    $text = str_replace("ارتقا","","$text");
                                    $text = str_replace("@","","$text");
                                    $ID_USER = trim($text);
                                    $user = GET_USER_BY_ID($ID_USER,$robot);
                                    if(!$user){
                                        SendMessage($robot,$lib_LR,"نتونستم انجامش بدم:(",$message_id);
                                        return false;
                                    }else if($user == 'skip'){
                                        SendMessage($robot,$lib_LR,"اینو که نمیشه قشنگم",$message_id);
                                    }else{
                                        $Reply_guid_message = $user['user_guid'];
            
                                        $is_admin = isAdmin($Reply_guid_message,$lib);
                                        
                                        $is_fulladmin2 = isFullAdmins($Reply_guid_message,$lib);
            
                                        if($Reply_guid_message == GUID){
                                            SendMessage($robot,$lib_LR,"عزیزم خودم کاربر ویژم",$message_id);
                                        }else if(AOWNER == $Reply_guid_message){
                                            SendMessage($robot,$lib_LR,"عشق زندگیمه ایشون:|",$message_id);
                                        }else if($is_fulladmin2){
                                            if($guid_message == $Reply_guid_message){
                                                SendMessage($robot,$lib_LR,"چی میزنی یکم ب ماهم بده",$message_id);
                                            }else{
                                                SendMessage($robot,$lib_LR,"عشقم کاربر ویژه هستش",$message_id);
                                            }
                                        }else if($is_admin){
                                            if($guid_message == $Reply_guid_message){
                                                SendMessage($robot,$lib_LR,"چی میزنی یکم ب ماهم بده",$message_id);
                                                setAdmin($robot,$Reply_guid_message);
                                            }else{
                                                SendMessage($robot,$lib_LR,"عشقم ادمینه",$message_id);
                                                setAdmin($robot,$Reply_guid_message);
                                            }
                                        }else{
                                            setAdmins($Reply_guid_message,$lib);
                                            $ALARM = "ادمین شد."." ✅ ";
                                            $TEXT = orders($ALARM);
                                            SendMessage($robot,$lib_LR,$TEXT,$message_id);
        
                                            setAdmin($robot,$Reply_guid_message);
                                        }
                                    }
                                }else if($text == 'ارتقا'){
                                    $YOUSPEAK = false;
                                    $replyMessage = Reply_Message($Message,$robot);
                                    if($replyMessage === 'skip'){
                                        SendMessage($robot,$lib_LR,"شخصی که میخای ادمین بشه ریپلای کن",$message_id);
                                    }else if(!$replyMessage){
                                        SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                                    }else{
                                        $Reply_guid_message = get_guidUser($replyMessage);
                                        if(!$Reply_guid_message){
                                            SendMessage($robot,$lib_LR,":|",$message_id);
                                        }else{
                                            $Reply_message_id = $replyMessage['message_id'];
        
                                            $is_admin = isAdmin($Reply_guid_message,$lib);
                                            
                                            $is_fulladmin2 = isFullAdmins($Reply_guid_message,$lib);
                                            
                                            if($Reply_guid_message == GUID){
                                                SendMessage($robot,$lib_LR,"عزیزم خودم کاربر ویژم",$message_id);
                                            }else if(AOWNER == $Reply_guid_message){
                                                SendMessage($robot,$lib_LR,"عشق زندگیمه ایشون  :|",$message_id);
                                            }else if($is_fulladmin2){
                                                if($guid_message == $Reply_guid_message){
                                                    SendMessage($robot,$lib_LR,"چی میزنی یکم ب ماهم بده",$message_id);
                                                }else{
                                                    SendMessage($robot,$lib_LR,"عشقم کاربر ویژه هستش",$message_id);
                                                }
                                            }else if($is_admin){
                                                if($guid_message == $Reply_guid_message){
                                                    SendMessage($robot,$lib_LR,"چی میزنی یکم ب ماهم بده",$message_id);
                                                    setAdmin($robot,$Reply_guid_message);
                                                }else{
                                                    SendMessage($robot,$lib_LR,"عشقم ادمینه",$message_id);
                                                    setAdmin($robot,$Reply_guid_message);
                                                }
                                            }else{
                                                setAdmins($Reply_guid_message,$lib);
                                                $ALARM = "ادمین شد."." ✅ ";
                                                $TEXT = orders($ALARM);
                                                SendMessage($robot,$lib_LR,$TEXT,$message_id);
        
                                                setAdmin($robot,$Reply_guid_message);
                                            }
                                        }
                                    }
                                }
                                
                            }
                            $pro_search = pro_search($text,"برکناری");
                            if($pro_search){
                                $pro_search = pro_search($text,"@");
                                if($pro_search){
                                    $YOUSPEAK = false;
                                    $text = str_replace("برکناری","","$text");
                                    $text = str_replace("@","","$text");
                                    $ID_USER = trim($text);
                                    $user = GET_USER_BY_ID($ID_USER,$robot);
                                    if(!$user){
                                        SendMessage($robot,$lib_LR,"نتونستم انجامش بدم:(",$message_id);
                                    }else if($user == 'skip'){
                                        SendMessage($robot,$lib_LR,"اینو که نمیشه قشنگم",$message_id);
                                    }else{
                                        $Reply_guid_message = $user['user_guid'];
        
                                        $is_admin = isAdmin($Reply_guid_message,$lib);
                                        
                                        $is_fulladmin2 = isFullAdmins($Reply_guid_message,$lib);
                                        
                                        if($Reply_guid_message == GUID){
                                            SendMessage($robot,$lib_LR,"نپسم|:",$message_id);
                                        }else if(AOWNER == $Reply_guid_message){
                                            SendMessage($robot,$lib_LR,"عشق زندگیمه ایشون  :|",$message_id);
                                        }else if($is_fulladmin2){
                                            SendMessage($robot,$lib_LR,"زندگیم کاربر ویژه رو نمیتونم برکنار کنم",$message_id);
                                        }else if(!$is_admin){
                                            SendMessage($robot,$lib_LR,"عشقم ادمین نیست",$message_id);
                                        }else{
                                            if($guid_message == $Reply_guid_message){
                                                SendMessage($robot,$lib_LR,"خدش خدشو برکنار کرد:|",$message_id);
                                            }
                                            unsetAdmins($Reply_guid_message,$lib);
                                            $ALARM = "برکنار شد."." ✅ ";
                                            $TEXT = orders($ALARM);
                                            SendMessage($robot,$lib_LR,$TEXT,$message_id);
                                            unsetAdmin($robot,$Reply_guid_message);
                                        }
                                    }
                                }else if($text == 'برکناری'){
                                    $YOUSPEAK = false;
                                    $replyMessage = Reply_Message($Message,$robot);
                                    if($replyMessage === 'skip'){
                                        SendMessage($robot,$lib_LR,"شخصی که میخای برکناری بشه ریپلای کن",$message_id);
                                    }else if(!$replyMessage){
                                        SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                                    }else{
                                        $Reply_guid_message = get_guidUser($replyMessage);
                                        if(!$Reply_guid_message){
                                            SendMessage($robot,$lib_LR,":|",$message_id);
                                        }else{
                                            $Reply_message_id = $replyMessage['message_id'];
        
                                            $is_admin = isAdmin($Reply_guid_message,$lib);
                                            
                                            $is_fulladmin2 = isFullAdmins($Reply_guid_message,$lib);
                                            
                                            if($Reply_guid_message == GUID){
                                                SendMessage($robot,$lib_LR,"نپسم|:",$message_id);
                                            }else if(AOWNER == $Reply_guid_message){
                                                SendMessage($robot,$lib_LR,"عشق زندگیمه ایشون  :|",$message_id);
                                            }else if($is_fulladmin2){
                                                SendMessage($robot,$lib_LR,"کاربر ویژه رو نمیتونی برکنار کنی",$message_id);
                                            }else if(!$is_admin){
                                                SendMessage($robot,$lib_LR,"عشقم ادمین نیست",$message_id);
                                                unsetAdmin($robot,$Reply_guid_message);
                                            }else{
                                                if($guid_message == $Reply_guid_message){
                                                    SendMessage($robot,$lib_LR,"خدش خدشو برکنار کرد:|",$message_id);
                                                }
                                                unsetAdmins($Reply_guid_message,$lib);
                                                $ALARM = "برکنار شد."." ✅ ";
                                                $TEXT = orders($ALARM);
                                                SendMessage($robot,$lib_LR,"برکنار شد.",$message_id);
        
                                                unsetAdmin($robot,$Reply_guid_message);
                                                
                                            }
                                        }
                                    }
                                }
                            }
                            $pro_search = pro_search($text,"رفع محدودیت");
                            if($pro_search){
                                $pro_search = pro_search($text,"@");
                                if($pro_search){
                                    $YOUSPEAK = false;
                                    $text = str_replace("رفع محدودیت","","$text");
                                    $text = str_replace("@","","$text");
                                    $ID_USER = trim($text);
                                    $user = GET_USER_BY_ID($ID_USER,$robot);
                                    if(!$user){
                                        SendMessage($robot,$lib_LR,"نتونستم انجامش بدم:(",$message_id);
                                        return false;
                                    }else if($user == 'skip'){
                                        SendMessage($robot,$lib_LR,"اینو که نمیشه قشنگم",$message_id);
                                    }else{
                                        $Reply_guid_message = $user['user_guid'];
                                        if(!$Reply_guid_message){
                                            SendMessage($robot,$lib_LR,":|",$message_id);
                                        }else{
                                            $result = unBanUser($robot,$Reply_guid_message);
                                            if($result){
                                                $lib_BN->updateState(GUID_U,$Reply_guid_message,0);
                                                $ALARM = "رفع محدودیت شد."." ✅ ";
                                                $TEXT = orders($ALARM);
                                                SendMessage($robot,$lib_LR,$TEXT,$message_id);
                                            }
                                        }
                                    }
                                }else if($text == 'رفع محدودیت'){
                                    $YOUSPEAK = false;
                                    $replyMessage = Reply_Message($Message,$robot);
                                    if($replyMessage === 'skip'){
                                        SendMessage($robot,$lib_LR,"شخصی که میخای رفع محدودیت بشه ریپلای کن",$message_id);
                                    }else if(!$replyMessage){
                                        SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                                    }else{
                                        $Reply_guid_message = get_guidUser($replyMessage);
                                        if(!$Reply_guid_message){
                                            SendMessage($robot,$lib_LR,":|",$message_id);
                                        }else{
                                            $result = unBanUser($robot,$Reply_guid_message);
                                            if($result){
                                                $lib_BN->updateState(GUID_U,$Reply_guid_message,0);
                                                $ALARM = "رفع محدودیت شد."." ✅ ";
                                                $TEXT = orders($ALARM);
                                                SendMessage($robot,$lib_LR,"رفع محدودیت شد.",$message_id);
                                            }
                                        }
                                    }
                                }
                            }
                            $pro_search = pro_search($text,"حذف اخطار");
                            if($pro_search){
                                $pro_search = pro_search($text,"@");
                                if($pro_search){
                                    $YOUSPEAK = false;
                                    $text = str_replace("حذف اخطار","","$text");
                                    $text = str_replace("@","","$text");
                                    $ID_USER = trim($text);
                                    $user = GET_USER_BY_ID($ID_USER,$robot);
                                    if(!$user){
                                        SendMessage($robot,$lib_LR,"نتونستم انجامش بدم:(",$message_id);
                                        return false;
                                    }else if($user == 'skip'){
                                        SendMessage($robot,$lib_LR,"اینو که نمیشه قشنگم",$message_id);
                                    }else{
                                        $Reply_guid_message = $user['user_guid'];
                                        if(!$Reply_guid_message){
                                            SendMessage($robot,$lib_LR,":|",$message_id);
                                        }else{
                                            $lib_BN->updateState(GUID_U,$Reply_guid_message,0);
                                            $ALARM = "اخطار ها حذف شد."." ✅ ";
                                            $TEXT = orders($ALARM);
                                            SendMessage($robot,$lib_LR,"$TEXT",$message_id);
                                        }
                                    }
                                }else if($text == 'حذف اخطار'){
                                    $YOUSPEAK = false;
                                    $replyMessage = Reply_Message($Message,$robot);
                                    if($replyMessage === 'skip'){
                                        SendMessage($robot,$lib_LR,"روی پیامی ریپلای کن",$message_id);
                                    }else if(!$replyMessage){
                                        SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                                    }else{
                                        $Reply_guid_message = get_guidUser($replyMessage);
                                        if(!$Reply_guid_message){
                                            SendMessage($robot,$lib_LR,":|",$message_id);
                                        }else{
                                            $lib_BN->updateState(GUID_U,$Reply_guid_message,0);
                                            $ALARM = "اخطار ها حذف شد."." ✅ ";
                                            $TEXT = orders($ALARM);
                                            SendMessage($robot,$lib_LR,"$TEXT",$message_id);
                                        }
                                    }
                                }
                            }
                            $pro_search = pro_search($text,"افزودن");
                            if($pro_search){
                                $pro_search = pro_search($text,"@");
                                if($pro_search){
                                    $YOUSPEAK = false;
                                    $text = str_replace("افزودن","","$text");
                                    $text = str_replace("@","","$text");
                                    $ID_USER = trim($text);
                                    $user = GET_USER_BY_ID($ID_USER,$robot);
                                    if(!$user){
                                        SendMessage($robot,$lib_LR,"نتونستم انجامش بدم:(",$message_id);
                                    }else if($user == 'skip'){
                                        SendMessage($robot,$lib_LR,"اینو که نمیشه قشنگم",$message_id);
                                    }else{
                                        $user_guid = $user['user_guid'];
                                        $result = add_user($robot,["$user_guid"]);
                                        if($result){
                                            $ALARM = "اضافه شد."." ✅ ";
                                            $TEXT = orders($ALARM);
                                            SendMessage($robot,$lib_LR,$TEXT,$message_id);
                                        }
                                    }
                                }else if($text == 'افزودن'){
                                    $YOUSPEAK = false;
                                    SendMessage($robot,$lib_LR,"ایدی فردی که میخای به گروه اد کنی رو کنار کلمه افزودن بفرس. ",$message_id);
                                }
                            }
                            $pro_search = pro_search($text,"تنظیم");
                                if($pro_search){
                                    $warningms = pro_search($text,"اخطار");
                                    if($warningms){
                                        $YOUSPEAK = false;
                                        $number =  preg_replace("/[^0-9]/",'', $text);
                                        if($number || $number == '0'){
                                            $number = intval($number);
                                            $result = $lib_LR->updateMax_GUiD(GUID_U,$number);
                                            $result2 = $lib_BN->updateMax(GUID_U,$number);
                                            if($result || $result2){
                                                $ALARM = "تنظیم اخطار با موفقیت انجام شد. ✅";
                                                $TEXT = orders($ALARM);
                                                SendMessage($robot,$lib_LR,$TEXT,$message_id);
                                            }
                                        }else{
                                            SendMessage($robot,$lib_LR,"لطفا تعداد اخطار را هم به انگلیسی بنویسید مثلا\n\nتنظیم اخطار 5",$message_id);
                                        }
                                    }
                            }
                            $pro_search = pro_search($text,"تنظیم");
                                if($pro_search){
                                    $warningms = pro_search($text,"تایمر");
                                    if($warningms){
                                        $YOUSPEAK = false;
                                        $number =  preg_replace("/[^0-9]/",'', $text);
                                        if($number || $number == '0'){
                                            $number = intval($number);
                                            if($number >= 3600){
                                                $number = 3600;
                                            }else if($number >= 900){
                                                $number = 900;
                                            }else if($number >= 300){
                                                $number = 300;
                                            }else if($number >= 60){
                                                $number = 60;
                                            }else if($number >= 30){
                                                $number = 30;
                                            }else if($number >= 10){
                                                $number = 10;
                                            }else{
                                                $number = 0;
                                            }
                                            $result = set_timer($robot,$number);
                                            if($result){
                                                $ALARM = "تایمر $number تنظیم شد. ✅";
                                                $TEXT = orders($ALARM);
                                                SendMessage($robot,$lib_LR,$TEXT,$message_id);
                                            }
                                        }else{
                                            SendMessage($robot,$lib_LR,"لطفا مدت زمان را به ثانیه و به انگلیسی بنویسید مثلا\n\nتنظیم تایمر 60",$message_id);
                                        }
                                    }
                            }
                            $pro_search = pro_search($text,"تنظیم");
                                if($pro_search){
                                    $warningms = pro_search($text,"سرعت");
                                    if($warningms){
                                        $YOUSPEAK = false;
                                        $number =  preg_replace("/[^0-9]/",'', $text);
                                        if($number || $number == '0'){
                                            $number = intval($number);
                                            if($number >= 5){
                                                $number = 5;
                                            }else if($number >= 4){
                                                $number = 4;
                                            }else if($number >= 3){
                                                $number = 3;
                                            }else if($number >= 2){
                                                $number = 2;
                                            }else if($number >= 1){
                                                $number = 1;
                                            }else{
                                                $number = 0;
                                            }
                                            SAVEMSS($robot,11,$number,$message_id,null,$lib_LR);
                                            $ALARM = "سرعت $number تنظیم شد. ✅";
                                            $TEXT = orders($ALARM);
                                            SendMessage($robot,$lib_LR,$TEXT,$message_id);
                                        }else{
                                            SendMessage($robot,$lib_LR,"لطفا مدت زمان را به ثانیه و به انگلیسی بنویسید مثلا\n\nتنظیم سرعت 0",$message_id);
                                        }
                                    }
                            }
                            $pro_search = pro_search($text,"امارش");
                            $pro_search1 = pro_search($text,"آمارش");
                            $pro_search2 = pro_search($text,"آمارم");
                            $pro_search3 = pro_search($text,"امارم");
                            if($pro_search || $pro_search1 || $pro_search2 || $pro_search3){
                                $pro_search = pro_search($text,"@");
                                if($pro_search){
                                    $YOUSPEAK = false;
                                    $text = str_replace("امارش","","$text");
                                    $text = str_replace("آمارش","","$text");
                                    $text = str_replace("آمارم","","$text");
                                    $text = str_replace("امارم","","$text");
                                    $text = str_replace("@","","$text");
                                    $ID_USER = trim($text);
                                    $user = GET_USER_BY_ID($ID_USER,$robot);
                                    if(!$user){
                                        SendMessage($robot,$lib_LR,"نتونستم انجامش بدم:(",$message_id);
                                    }else if($user == 'skip'){
                                        SendMessage($robot,$lib_LR,"اینو که نمیشه قشنگم",$message_id);
                                    }
                                    $Reply_guid_message = $user['user_guid'];
                                    if($guid_message == $Reply_guid_message){                                    
                                        SendMessage($robot,$lib_LR,"عشقم میتونی ریپلای نزنی:)",$message_id);
                                    }
                                    YOUR_STATUS($lib,$lib_BN,$lib_LR,$Reply_guid_message,$message_id,$ADMINS,$robot,$AFA);
                                }else if($text == 'امارش' || $text == 'آمارش' || $text == 'آمارم' || $text == 'امارم'){
                                    $YOUSPEAK = false;
                                    $replyMessage = Reply_Message($Message,$robot);
                                    if($replyMessage === 'skip'){
                                        YOUR_STATUS($lib,$lib_BN,$lib_LR,$guid_message,$message_id,$ADMINS,$robot,$AFA);
                                    }else if(!$replyMessage){
                                        SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                                    }else{
                                        $Reply_guid_message = get_guidUser($replyMessage);
                                        if(!$Reply_guid_message){
                                            SendMessage($robot,$lib_LR,":|",$message_id);
                                        }else{
                                            $Reply_message_id = $replyMessage['message_id'];                                    
                                            if($guid_message == $Reply_guid_message){                                    
                                                SendMessage($robot,$lib_LR,"عشقم میتونی ریپلای نزنی:)",$message_id);
                                            }
                                            YOUR_STATUS($lib,$lib_BN,$lib_LR,$Reply_guid_message,$message_id,$ADMINS,$robot,$AFA);
                                        }
                                    }
                                }
                            }
                            $pro_search = pro_search($text,"یاد بگیر");
                            $pro_search1 = pro_search($text,"یادبگیر");
                            if($pro_search || $pro_search1){
                                $pro_search = pro_search($text,"=");
                                if($pro_search){
                                    $YOUSPEAK = false;
                                    $need = '=';
                                    $pos = strpos($text,$need);
                                    $QU =  substr($text,0,$pos);
                                    $QU = str_replace("یاد بگیر","","$QU");
                                    $QU = str_replace("یادبگیر","","$QU");
                                    $QU = trim($QU);
                                    $ANS =  substr($text,$pos);
                                    $ANS = str_replace("=","","$ANS");
                                    $ANS = trim($ANS);
                                    if(!is_null($ANS) && !is_null($QU)){
                                        $pats['QU'] =$QU;
                                        $pats['LEVEL'] =1;
                                        $pats['ANS'] =$ANS;
                                        $pats['GUID_U'] =GUID;
                                        $result = $lib_ANS->insert($pats);
                                        $result1 = $lib_QU->insert($pats);
                                        if($result && $result1){
                                            SendMessage($robot,$lib_LR,$ANS,$message_id);
                                        }
                                    }
                                    return true;
                                }
                            }
                            $pro_search = pro_search($text,"فراموش کن");
                            if($pro_search){
                                $pro_search = pro_search($text,"=");
                                if($pro_search){
                                    $YOUSPEAK = false;
                                    $need = '=';
                                    $pos = strpos($text,$need);
                                    $QU =  substr($text,0,$pos);
                                    $QU = str_replace("فراموش کن","","$QU");
                                    $QU = trim($QU);
                                    $ANS =  substr($text,$pos);
                                    $ANS = str_replace("=","","$ANS");
                                    $ANS = trim($ANS);
                                    if(!is_null($ANS) && !is_null($QU)){
                                        $result = $lib_ANS->delete($QU,$ANS,GUID);
                                        $result1 = $lib_QU->delete($QU,GUID);
                                        if($result || $result1){
                                            $ALARM = "فراموش شد."." ✅ ";
                                            $TEXT = orders($ALARM);
                                            SendMessage($robot,$lib_LR,$TEXT,$message_id);
                                        }else{
                                            SendMessage($robot,$lib_LR,"فراموش نشد.",$message_id);
                                        }
                                    }
                                    return true;
                                }else if($text == 'فراموش کن'){
                                    $YOUSPEAK = false;
                                    $replyMessage = Reply_Message($Message,$robot);
                                    if($replyMessage === 'skip'){
                                        SendMessage($robot,$lib_LR,"ریپلای بزن تا جواب مورد نظر حذف شود.",$message_id);
                                    }else if(!$replyMessage){
                                        SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                                    }else{
                                        $Reply_message_text = $replyMessage['text'];
                                        $Reply_message_text = substr($Reply_message_text, 0, -4);
                                        $Reply_message_text = trim($Reply_message_text);
                                        // SendMessage($robot,$lib_LR,"$Reply_message_text",$message_id);
                                        $result = $lib_ANS->delete_ANS($Reply_message_text,GUID);
                                        if($result){
                                            $ALARM = "فراموش شد."." ✅ ";
                                            $TEXT = orders($ALARM);
                                            SendMessage($robot,$lib_LR,$TEXT,$message_id);
                                        }else{
                                            SendMessage($robot,$lib_LR,"فراموش نشد.",$message_id);
                                        }
                                    }
                                }
                            }
                            $pro_search = pro_search($text,"/Spage");
                            if($text == 'فعالیت اعضا' || $pro_search || $text == 'فعالیت عضوها' || $text == '/MemberActivity'){
                                $YOUSPEAK = false;
                                if($text == "/Spage1"){
                                    $TXTR = ALLmembers($lib_BN,$lib,'Spage1');
                                }else if($text == "/Spage2"){
                                    $TXTR = ALLmembers($lib_BN,$lib,'Spage2');
                                }else if($text == "/Spage3"){
                                    $TXTR = ALLmembers($lib_BN,$lib,'Spage3');
                                }else if($text == "/Spage4"){
                                    $TXTR = ALLmembers($lib_BN,$lib,'Spage4');
                                }else{
                                    $TXTR = ALLmembers($lib_BN,$lib,'Spage1');
                                }
                                SendMessage($robot,$lib_LR,$TXTR,$message_id);
                            }
                            $pro_search = pro_search($text,"/Apage");
                            if($text == 'فعالیت گپ' || $pro_search || $text == 'فعالیت گروه' || $text == '/ChatActivity'){
                                $YOUSPEAK = false;
                                if($text == "/Apage1"){
                                    $TXT =  reaports_all($lib_LR,'Apage1');
                                }else if($text == "/Apage2"){
                                    $TXT =  reaports_all($lib_LR,'Apage2');
                                }else if($text == "/Apage3"){
                                    $TXT =  reaports_all($lib_LR,'Apage3');
                                }else if($text == "/Apage4"){
                                    $TXT =  reaports_all($lib_LR,'Apage4');
                                }else{
                                    $TXT =  reaports_all($lib_LR,'Apage1');
                                }
                                $result = SendMessage($robot,$lib_LR,$TXT,$message_id);
                            }
                            $pro_search = pro_search($text,"/Dpage");
                            if($text == 'برترین عضو ها' || $pro_search ||  $text == 'برترین عضوها' || $text == '/TopMembers'){
                                $YOUSPEAK = false;
                                if($text == "/Dpage1"){
                                    $TXT = Top_users($lib_BN,$lib,$lib_LR,'Dpage1');
                                }else if($text == "/Dpage2"){
                                    $TXT = Top_users($lib_BN,$lib,$lib_LR,'Dpage2');
                                }else if($text == "/Dpage3"){
                                    $TXT = Top_users($lib_BN,$lib,$lib_LR,'Dpage3');
                                }else if($text == "/Dpage4"){
                                    $TXT = Top_users($lib_BN,$lib,$lib_LR,'Dpage4');
                                }else{
                                    $TXT = Top_users($lib_BN,$lib,$lib_LR,'Dpage1');
                                }
                                SendMessage($robot,$lib_LR,$TXT,$message_id);
                            }
                            $pro_search = pro_search($text,"ازاد");
                            $pro_search1 = pro_search($text,"آزاد");
                            $pro_search2 = pro_search($text,"/");
                            if($pro_search || $pro_search1 || $pro_search2){
                                if($text == 'متن ازاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,0,1,$message_id);
                                }else if($text == 'لینک ازاد' || $text == 'لینک آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,1,1,$message_id);
                                }else if($text == 'ارسال پیام ازاد' || $text == 'ارسال پیام آزاد'){
                                    MGGroupDefaultAccess($robot,"SendMessages",1);
                                    $ALARM = "ارسال پیام ازاد است ✅";
                                    $ALARM = orders($ALARM);
                                    SendMessage($robot,$lib_LR,$ALARM,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == '/SendMessagesON'){
                                    MGGroupDefaultAccess($robot,"SendMessages",1);
                                    setting_panel($robot,$lib_LR,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == "افزودن عضو ازاد" || $text == "افزودن عضو آزاد"){
                                    MGGroupDefaultAccess($robot,"AddMember",1);
                                    $ALARM = "افزودن عضو ازاد است ✅";
                                    $ALARM = orders($ALARM);
                                    SendMessage($robot,$lib_LR,$ALARM,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == '/AddMemberON'){
                                    MGGroupDefaultAccess($robot,"AddMember",1);
                                    setting_panel($robot,$lib_LR,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == "مشاهده مدیران ازاد" || $text == "مشاهده مدیران آزاد"){
                                    MGGroupDefaultAccess($robot,"ViewAdmins",1);
                                    $ALARM = "مشاهده مدیران ازاد است ✅";
                                    $ALARM = orders($ALARM);
                                    SendMessage($robot,$lib_LR,$ALARM,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == '/ViewAdminsON'){
                                    MGGroupDefaultAccess($robot,"ViewAdmins",1);
                                    setting_panel($robot,$lib_LR,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == "مشاهده اعضا ازاد" || $text == "مشاهده اعضا آزاد"){
                                    MGGroupDefaultAccess($robot,"ViewMembers",1);
                                    $ALARM = "مشاهده اعضا اعضا است ✅";
                                    $ALARM = orders($ALARM);
                                    SendMessage($robot,$lib_LR,$ALARM,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == '/ViewMembersON'){
                                    MGGroupDefaultAccess($robot,"ViewMembers",1);
                                    setting_panel($robot,$lib_LR,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == '/linkON'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,1,1,NULL);
                                    locks_panel($robot,$lib_LR,$message_id);
                                }else if($text == 'ایدی ازاد' || $text == 'ایدی آزاد' || $text == 'آیدی آزاد' || $text == 'ایدی ازاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,2,1,$message_id);
                                }else if($text == '/idON'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,2,1,NULL);
                                    locks_panel($robot,$lib_LR,$message_id);
                                }else if($text == 'منشن ازاد' || $text == 'منشن آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,3,1,$message_id);
                                }else if($text == 'نظرسنجی ازاد' || $text == 'نظرسنجی آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,4,1,$message_id);
                                }else if($text == 'استیکر ازاد' || $text == 'استیکر آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,5,1,$message_id);
                                }else if($text == 'فایل ازاد' || $text == 'فایل آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,6,1,$message_id);
                                }else if($text == 'گیف ازاد' || $text == 'گیف آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,7,1,$message_id);
                                }else if($text == 'ویس ازاد' || $text == 'ویس آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,8,1,$message_id);
                                }else if($text == 'تصویر ازاد' || $text == 'تصویر آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,9,1,$message_id);
                                }else if($text == 'اهنگ ازاد' || $text == 'اهنگ آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,10,1,$message_id);
                                }else if($text == 'ویدیو ازاد' || $text == 'ویدیو آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,11,1,$message_id);
                                }else if($text == 'پیام هدایت شده ازاد' || $text == 'پیام هدایت شده آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,12,1,$message_id);
                                }else if($text == '/forwardON'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,12,1,NULL);
                                    locks_panel($robot,$lib_LR,$message_id);
                                }else if($text == 'گفتگو صوتی ازاد' || $text == 'گفتگو صوتی آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,13,1,$message_id);
                                }else if($text == 'پیام کاربر افزوده شده ازاد' || $text == 'پیام کاربر افزوده شده آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,14,1,$message_id);
                                }else if($text == 'پیام کاربر حذف شده ازاد' || $text == 'پیام کاربر حذف شده آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,15,1,$message_id);
                                }else if($text == 'پیام سنجاق پیام ازاد' || $text == 'پیام سنجاق پیام آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,16,1,$message_id);
                                }else if($text == 'پیام پیوستن ازاد' || $text == 'پیام پیوستن آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,17,1,$message_id);
                                }else if($text == 'خوشامدگویی ازاد' || $text == 'خوش امدگویی آزاد' || $text == 'خوش آمدگویی آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,37,4,$message_id);
                                }else if($text == 'پیام ترک کردن ازاد' || $text == 'پیام ترک کردن آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,18,1,$message_id);
                                }else if($text == 'پست روبینو ازاد' || $text == 'پست روبینو آزاد' ){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,19,1,$message_id);
                                }else if($text == 'استوری روبینو ازاد' || $text == 'استوری روبینو آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,20,1,$message_id);
                                }else if($text == 'لایو ازاد' || $text == 'لایو آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,21,1,$message_id);
                                }else if($text == 'پاسخ به پیام کاربر افزوده شده ازاد' || $text == 'پاسخ به پیام کاربر افزوده شده آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,34,4,$message_id);
                                }else if($text == '/wmAdON'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,34,4,NULL);
                                    locks_panel($robot,$lib_LR,$message_id);
                                }else if($text == 'پاسخ به پیام کاربر حذف شده ازاد' || $text == 'پاسخ به پیام کاربر حذف شده آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,35,4,$message_id);
                                }else if($text == 'پاسخ به پیام پیوستن ازاد' || $text == 'پاسخ به پیام پیوستن آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,37,4,$message_id);
                                }else if($text == '/WelcomeON'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,37,4,NULL);
                                    locks_panel($robot,$lib_LR,$message_id);
                                }else if($text == 'پاسخ به پیام ترک کردن ازاد' || $text == 'پاسخ به پیام ترک کردن آزاد'){
                                    $YOUSPEAK = false;
                                    manange_setting($lib_LR,$robot,38,4,$message_id);
                                }
                            }
                            $pro_search = pro_search($text,"قفل");
                            $pro_search1 = pro_search($text,"/");
                            if($pro_search || $pro_search1){
                                if($text == 'متن قفل'){
                                    manange_setting($lib_LR,$robot,0,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'لینک قفل'){
                                    manange_setting($lib_LR,$robot,1,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'ارسال پیام قفل'){
                                    MGGroupDefaultAccess($robot,"SendMessages",2);
                                    $ALARM = "ارسال پیام قفل است ❌";
                                    $ALARM = orders($ALARM);
                                    SendMessage($robot,$lib_LR,$ALARM,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == '/SendMessagesOFF'){
                                    MGGroupDefaultAccess($robot,"SendMessages",2);
                                    setting_panel($robot,$lib_LR,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == "افزودن عضو قفل"){
                                    MGGroupDefaultAccess($robot,"AddMember",2);
                                    $ALARM = "افزودن عضو قفل است ❌";
                                    $ALARM = orders($ALARM);
                                    SendMessage($robot,$lib_LR,$ALARM,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == '/AddMemberOFF'){
                                    MGGroupDefaultAccess($robot,"AddMember",2);
                                    setting_panel($robot,$lib_LR,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == "مشاهده مدیران قفل"){
                                    MGGroupDefaultAccess($robot,"ViewAdmins",2);
                                    $ALARM = "مشاهده مدیران قفل است ❌";
                                    $ALARM = orders($ALARM);
                                    SendMessage($robot,$lib_LR,$ALARM,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == '/ViewAdminsOFF'){
                                    MGGroupDefaultAccess($robot,"ViewAdmins",2);
                                    setting_panel($robot,$lib_LR,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == "مشاهده لیست اعضا قفل"){
                                    MGGroupDefaultAccess($robot,"ViewMembers",2);
                                    $ALARM = "مشاهده لیست اعضا قفل است ❌";
                                    $ALARM = orders($ALARM);
                                    SendMessage($robot,$lib_LR,$ALARM,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == '/ViewMembersOFF'){
                                    MGGroupDefaultAccess($robot,"ViewMembers",2);
                                    setting_Panel($robot,$lib_LR,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == '/linkOFF'){
                                    manange_setting($lib_LR,$robot,1,2,NULL);
                                    locks_panel($robot,$lib_LR,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'ایدی قفل' || $text == 'قفل آیدی'){
                                    manange_setting($lib_LR,$robot,2,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == '/idOFF'){
                                    manange_setting($lib_LR,$robot,2,2,NULL);
                                    locks_panel($robot,$lib_LR,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'منشن قفل'){
                                    manange_setting($lib_LR,$robot,3,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'نظرسنجی قفل'){
                                    manange_setting($lib_LR,$robot,4,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'استیکر قفل'){
                                    manange_setting($lib_LR,$robot,5,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'فایل قفل'){
                                    manange_setting($lib_LR,$robot,6,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'گیف قفل'){
                                    manange_setting($lib_LR,$robot,7,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'ویس قفل'){
                                    manange_setting($lib_LR,$robot,8,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'تصویر قفل'){
                                    manange_setting($lib_LR,$robot,9,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'اهنگ قفل' || $text == 'قفل آهنگ'){
                                    manange_setting($lib_LR,$robot,10,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'ویدیو قفل'){
                                    manange_setting($lib_LR,$robot,11,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'پیام هدایت شده قفل'){
                                    manange_setting($lib_LR,$robot,12,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == '/forwardOFF'){
                                    manange_setting($lib_LR,$robot,12,2,NULL);
                                    locks_panel($robot,$lib_LR,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'گفتگو صوتی قفل'){
                                    manange_setting($lib_LR,$robot,13,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'پیام کاربر افزوده شده قفل'){
                                    manange_setting($lib_LR,$robot,14,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'پیام کاربر حذف شده قفل'){
                                    manange_setting($lib_LR,$robot,15,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'پیام سنجاق پیام قفل'){
                                    manange_setting($lib_LR,$robot,16,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'پیام پیوستن قفل'){
                                    manange_setting($lib_LR,$robot,17,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'خوشامدگویی قفل' || $text == 'خوش امدگویی قفل' || $text == 'خوش آمدگویی قفل'){
                                    manange_setting($lib_LR,$robot,17,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'پیام ترک کردن قفل'){
                                    manange_setting($lib_LR,$robot,18,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'پست روبینو قفل'){
                                    manange_setting($lib_LR,$robot,19,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'استوری روبینو قفل'){
                                    manange_setting($lib_LR,$robot,20,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'لایو قفل'){
                                    manange_setting($lib_LR,$robot,21,2,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'پاسخ به پیام کاربر افزوده شده قفل'){
                                    manange_setting($lib_LR,$robot,34,1,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == '/wmAdOFF'){
                                    manange_setting($lib_LR,$robot,34,1,NULL);
                                    locks_panel($robot,$lib_LR,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'پاسخ به پیام کاربر حذف شده قفل'){
                                    manange_setting($lib_LR,$robot,35,1,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == 'پاسخ به پیام پیوستن قفل'){
                                    manange_setting($lib_LR,$robot,37,1,$message_id);
                                    $YOUSPEAK = false;
                                }else if($text == '/WelcomeOFF'){
                                    manange_setting($lib_LR,$robot,37,1,NULL);
                                    locks_panel($robot,$lib_LR,$message_id);
                                    $YOUSPEAK = false;
                                }
                            }
                        }
                    }else{
                        if($Is_admin){
                            $level = false;
                            if($text == 'ثبت اصل'){
                                $YOUSPEAK = false;
                                $replyMessage = Reply_Message($Message,$robot);
                                if($replyMessage === 'skip'){
                                    SendMessage($robot,$lib_LR,"روی پیام اصل شخص موردنظر ریپلای کن ",$message_id);
                                }else if(!$replyMessage){
                                    SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                                }else{
                                    $Reply_guid_message = get_guidUser($replyMessage);
                                    if(!$Reply_guid_message){
                                        SendMessage($robot,$lib_LR,":|",$message_id);
                                    }else{
                                        $Reply_message_id = $replyMessage['message_id'];
                                        if(isset($replyMessage['text'])){
                                            $Reply_message_text = $replyMessage['text'];
                                            
                                            $TXT = 'اصلت ثبت شد. ✅';
                                            if($Reply_guid_message == GUID){
                                                $TXT = 'اصلم ثبت شد. ✅';
                                            }
                                            
                                            $text = utf8_encode($Reply_message_text);
                                            $result_B = $lib_BN->updateInfo(GUID_U,$Reply_guid_message,$text);
                                            if($result_B){
                                                SendMessage($robot,$lib_LR,"$TXT",$Reply_message_id);
                                            }else{
                                                return false;
                                            }
                                            
                                        }else{
                                            SendMessage($robot,$lib_LR,":|",$message_id);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                if($YOUSPEAK){
                    if($text == 'ROBOT' || $text == 'Robot'  || $text == 'robot'){
                        $YOUSPEAK = false;
                        sendMessage_pro($robot,$lib_LR,$guid_message,$message_id,"✘ ROBOT IS ACTIVE ✘");
                    }else if($text == 'بررسی' || $text == 'امارم' || $text == 'آمارم' ){
                        $YOUSPEAK = false;
                        $result_B = $lib_BN->select(GUID_U,$guid_message);
                        if(!is_null($result_B)){
                            $name = $result_B['name'];
                            $max = $result_B['Max'];
                            $who = isAdmin($guid_message,$lib);
                            $state = $result_B['state'];
                            $is_admin = 'کاربر عادی';
                            if($who){
                                $is_admin = 'ادمین';
                            }
                            $name = utf8_decode($name);
                            $TEXT = title('وضعیت شخص');
                            $TEXT .= mini('لقب : ').$name."\n";
                            $TEXT .= mini('اخطار : ').$state.' از '.$max."\n";
                            $TEXT .= mini('مقام : ').$is_admin;
                            $TEXT = end_form($TEXT);
                            SendMessage($robot,$lib_LR,"$TEXT",$message_id);
                        }else{
                            $user = GET_USER_BY_GUID($guid_message,$robot);
                            $FLName = 'NO NAME!';
                            if(isset($user['first_name'])){
                                $FLName = $user['first_name'];
                            }
                            $max_array = $lib_LR->selectMax_GUID(GUID_U);
                            $FLName = utf8_encode($FLName);
                            $info = utf8_encode('اصلی ثبت نشده');
                            $Max = $max_array['Max'];
                            $info_bands['Guid_gap'] = GUID_U;
                            $info_bands['Guid_user'] = $guid_message;
                            $info_bands['state'] = 0;
                            $info_bands['Max'] = $Max;
                            $info_bands['report'] =0;
                            $info_bands['info'] = $info;
                            $info_bands['name'] = "$FLName";
                            $lib_BN->insert($info_bands);
                            SendMessage($robot,$lib_LR,"ردپایی ازش در گپ نیست!",$message_id);
                        }
                    }else if($text == 'لقب' ||$text == 'لقبش' ||$text == 'لقبم'){
                        $YOUSPEAK = false;
                        $replyMessage = Reply_Message($Message,$robot);
                        if($replyMessage === 'skip'){
                            $result_B = $lib_BN->select(GUID_U,$guid_message);
                            if(is_null($result_B)){
                                $user = GET_USER_BY_GUID($guid_message,$robot);
                                $FLName = 'NO NAME!';
                                if(isset($user['first_name'])){
                                    $FLName = $user['first_name'];
                                }
                                $max_array = $lib_LR->selectMax_GUID(GUID_U);
                                $FLName = utf8_encode($FLName);
                                $info = utf8_encode('اصلی ثبت نشده');
                                $Max = $max_array['Max'];
                                $info_bands['Guid_gap'] = GUID_U;
                                $info_bands['Guid_user'] = $guid_message;
                                $info_bands['state'] = 0;
                                $info_bands['Max'] = $Max;
                                $info_bands['report'] =0;
                                $info_bands['info'] = $info;
                                $info_bands['name'] = "$FLName";
                                $lib_BN->insert($info_bands);
                                SendMessage($robot,$lib_LR,"چرا من تو رو یادم نمیاد:(",$message_id);
                            }else{
                                $name = $result_B['name'];
                                $name = utf8_decode($name);
                                $TEXT = mini('لقبت : ').$name;
                                SendMessage($robot,$lib_LR,"$TEXT",$message_id);
                            }
                        }else if(!$replyMessage){
                            SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                        }else{
                            $Reply_guid_message = get_guidUser($replyMessage);
                            if(!$Reply_guid_message){
                                SendMessage($robot,$lib_LR,":|",$message_id);
                            }else{
                                $Reply_message_id = $replyMessage['message_id'];
                                if(isset($replyMessage['text'])){
                                    $result_B = $lib_BN->select(GUID_U,$Reply_guid_message);
                                    if(!is_null($result_B)){
                                        $name = $result_B['name'];
                                        $name = utf8_decode($name);
                                        $TEXT = mini('لقبش : ').$name;
                                        SendMessage($robot,$lib_LR,"$TEXT",$message_id);
                                    }else{
                                        SendMessage($robot,$lib_LR,"چرا من یادم نمیاد:(",$Reply_message_id);
                                    }
                                }else{
                                    SendMessage($robot,$lib_LR,":|",$message_id);
                                }
                            }
                        }
                    }else if($text == 'اصل' || $text == 'اصلم' ||$text == 'اصلش'){
                        $YOUSPEAK = false;
                        $replyMessage = Reply_Message($Message,$robot);
                        if($replyMessage === 'skip'){
                            $result_B = $lib_BN->select(GUID_U,$guid_message);
                            if(is_null($result_B)){
                                $user = GET_USER_BY_GUID($guid_message,$robot);
                                $FLName = 'NO NAME!';
                                if(isset($user['first_name'])){
                                    $FLName = $user['first_name'];
                                }
                                $max_array = $lib_LR->selectMax_GUID(GUID_U);
                                $FLName = utf8_encode($FLName);
                                $info = utf8_encode('اصلی ثبت نشده');
                                $Max = $max_array['Max'];
                                $info_bands['Guid_gap'] = GUID_U;
                                $info_bands['Guid_user'] = $guid_message;
                                $info_bands['state'] = 0;
                                $info_bands['Max'] = $Max;
                                $info_bands['report'] =0;
                                $info_bands['info'] = $info;
                                $info_bands['name'] = "$FLName";
                                $lib_BN->insert($info_bands);
                                SendMessage($robot,$lib_LR,"چرا من تو رو یادم نمیاد:(",$message_id);
                            }else{
                                $info = $result_B['info'];
                                $info = utf8_decode($info);
                                $TEXT = mini('اصلت : ').$info;
                                SendMessage($robot,$lib_LR,"$TEXT",$message_id);
                            }
                        }else if(!$replyMessage){
                            SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                        }else{
                            $Reply_guid_message = get_guidUser($replyMessage);
                            if(!$Reply_guid_message){
                                SendMessage($robot,$lib_LR,":|",$message_id);
                            }else{
                                $Reply_message_id = $replyMessage['message_id'];
                                if(isset($replyMessage['text'])){
                                    $result_B = $lib_BN->select(GUID_U,$Reply_guid_message);
                                    if(!is_null($result_B)){
                                        $info = $result_B['info'];
                                        $info = utf8_decode($info);
                                        $TEXT = mini('اصلش : ').$info;
                                        SendMessage($robot,$lib_LR,"$TEXT",$message_id);
                                    }else{
                                        SendMessage($robot,$lib_LR,"چرا من تو رو یادم نمیاد:(",$Reply_message_id);
                                    }
                                }else{
                                    SendMessage($robot,$lib_LR,":|",$message_id);
                                }
                            }
                        }
                    }else if($text == 'چالش'){
                        $isAllow = $Tod[15];
                        if($isAllow == 1){
                            $YOUSPEAK = false;
                            $rand = rand(1,chalesh_count);
                            $QUA = $lib_CH->select($rand);
                            $QU = $QUA['QU'];
                            $QU = utf8_decode($QU);
                            $replyMessage = Reply_Message($Message,$robot);
                            if($replyMessage === 'skip'){
                                SendMessage($robot,$lib_LR,"$QU",$message_id);
                            }else if(!$replyMessage){
                                SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                            }else{
                                $Reply_guid_message = get_guidUser($replyMessage);
                                if(!$Reply_guid_message){
                                    SendMessage($robot,$lib_LR,":|",$message_id);
                                }else{
                                    $Reply_message_id = $replyMessage['message_id'];
                                    if($Reply_guid_message == GUID){
                                        SendMessage($robot,$lib_LR,"$QU",$message_id);
                                    }else{
                                        if($guid_message == $Reply_guid_message){                                    
                                            SendMessage($robot,$lib_LR,"روی خدشم ریپ میزنه:|",$message_id);
                                        }
                                        SendMessage($robot,$lib_LR,"$QU",$Reply_message_id);
                                    }
                                }
                            }
                        }
                    }else if($text == 'بیو'){
                        $isAllow = $Tod[16];
                        if($isAllow == 1){
                            $YOUSPEAK = false;
                            $rand = rand(1,bio_count);
                            $QUA = $lib_bio->select($rand);
                            $QU = $QUA['QU'];
                            $QU = utf8_decode($QU);
                            $QU = trim($QU);
                            $QU = "| #"."ᗷIO"."\n".$QU;
                            SendMessage($robot,$lib_LR,"$QU",$message_id);
                        }
                    }else if($text == 'فکت'){
                        $isAllow = $Tod[17];
                        if($isAllow == 1){
                            $YOUSPEAK = false;
                            $rand = rand(1,fact_count);
                            $QUA = $lib_fact->select($rand);
                            $QU = $QUA['QU'];
                            $QU = utf8_decode($QU);
                            $QU = trim($QU);
                            $QU = "| #"."ᖴᗩᑕT"."\n".$QU;
                            SendMessage($robot,$lib_LR,"$QU",$message_id);
                        }
                    }else if($text == 'اعتراف'){
                        $isAllow = $Tod[18];
                        if($isAllow == 1){
                            $YOUSPEAK = false;
                            $rand = rand(1,etraf_count);
                            $QUA = $lib_etraf->select($rand);
                            $QU = $QUA['QU'];
                            $QU = utf8_decode($QU);
                            $QU = trim($QU);
                            $QU = "| #"."ᗴTᖇᗩᖴ"."\n".$QU;
                            SendMessage($robot,$lib_LR,"$QU",$message_id);
                        }
                    }else if($text == 'جوک'){
                        $isAllow = $Tod[19];
                        if($isAllow == 1){
                            $YOUSPEAK = false;
                            $rand = rand(1,jock_count);
                            $QUA = $lib_jock->select($rand);
                            $QU = $QUA['QU'];
                            $QU = utf8_decode($QU);
                            $QU = trim($QU);
                            $QU = "| #"."ᒍOᑕK"."\n".$QU;
                            SendMessage($robot,$lib_LR,"$QU",$message_id);
                        }
                    }else if($text == 'تکست'){
                        $isAllow = $Tod[20];
                        if($isAllow == 1){
                            $YOUSPEAK = false;
                            $rand = rand(1,text_count);
                            $QUA = $lib_text->select($rand);
                            $QU = $QUA['QU'];
                            $QU = utf8_decode($QU);
                            $QU = trim($QU);
                            $QU = "| #"."TᗴXT"."\n".$QU;
                            SendMessage($robot,$lib_LR,"$QU",$message_id);
                        }
                    }else if($text == 'بپرس'){
                        $YOUSPEAK = false;
                        $replyMessage = Reply_Message($Message,$robot);
                        if($replyMessage === 'skip'){
                            SendMessage($robot,$lib_LR,"روی لیست ج ح ریپلای بزن که بپرسم",$message_id);
                        }else if(!$replyMessage){
                            SendMessage($robot,$lib_LR,"پیامش پاک شده",$message_id);
                        }else{
                            if(isset($replyMessage['text'])){
                                $Reply_message_id = $replyMessage['message_id'];
                                $Reply_message_text = $replyMessage['text'];
                                $count = substr_count($Reply_message_text,"\n");
                                $count = $count+1;
                                $rand = rand(1,$count);
                                SendMessage($robot,$lib_LR,"$rand",$Reply_message_id);
                            }else{
                                SendMessage($robot,$lib_LR,":|",$message_id);
                            }
                            
                        }
                    }else if($text == 'لینک'){
                        $YOUSPEAK = false;
                        $link_info =  GetLink($robot);
                        if($link_info){
                            if(isset($link_info['status']) && $link_info['status'] === 'OK'){
                                $link = $link_info['data']['join_link'];
                                SendMessage($robot,$lib_LR,"$link",$message_id);
                            }
                        }
                    }else if($text == 'تاریخ' || $text == 'ساعت'){
                        $YOUSPEAK = false;
                        $d = time();
                        $date = date("Y-m-d h:i:sa", $d);
                        SendMessage($robot,$lib_LR,$date,$message_id);
                    }else if($text == 'تاس'){
                        $YOUSPEAK = false;
                        $rand = rand(1,6);
                        if($rand == 1){
                            $shape = "⬤";
                        }else if($rand == 2){
                            $shape = "⬤ ⬤";
                        }else if($rand == 3){
                            $shape = "⬤ ⬤\n  ⬤";
                        }else if($rand == 4){
                            $shape = "⬤ ⬤\n⬤ ⬤";
                        }else if($rand == 5){
                            $shape = "⬤ ⬤\n  ⬤\n⬤ ⬤";
                        }else if($rand == 6){
                            $shape = "⬤ ⬤\n⬤ ⬤\n⬤ ⬤";
                        }
                        SendMessage($robot,$lib_LR,$shape,$message_id);
                    }else if($text == 'سکه'){
                        $YOUSPEAK = false;
                        $rand = rand(1,2);
                        if($rand == 1){
                            $text = '⦿ #شیر ⦿';
                        }else{
                            $text = '⊝ #خط ⊝';
                        }
                        SendMessage($robot,$lib_LR,$text,$message_id);
                    }else if($text == 'عدد شانسی'){
                        $YOUSPEAK = false;
                        $rand = rand(0,1000);
                        $rand = '#'.$rand;
                        SendMessage($robot,$lib_LR,$rand,$message_id);
                    }else if($text == 'قوانین'){
                        $YOUSPEAK = false;
                        SendMessageX($robot,$guid_message,0,$message_id,$lib_LR);
                    }else if($text == 'یاداوری' || $text == 'یاد اوری' || $text == 'یادآوری' || $text == 'یاد آوری'){
                        $YOUSPEAK = false;
                        SendMessageX($robot,$guid_message,1,$message_id,$lib_LR);
                    }else if($text == 'پاسخ به پیام کاربر افزوده شده'){
                        $YOUSPEAK = false;
                        SendMessageX($robot,$guid_message,2,$message_id,$lib_LR);
                    }else if($text == 'پاسخ به پیام کاربر حذف شده'){
                        $YOUSPEAK = false;
                        SendMessageX($robot,$guid_message,3,$message_id,$lib_LR);
                    }else if($text == 'پاسخ به پیام پیوستن'){
                        $YOUSPEAK = false;
                        SendMessageX($robot,$guid_message,4,$message_id,$lib_LR);
                    }else if($text == 'پاسخ به پیام ترک کردن'){
                        $YOUSPEAK = false;
                        SendMessageX($robot,$guid_message,5,$message_id,$lib_LR);
                    }else if($text == 'پاسخ به پیام اد شدن'){
                        $YOUSPEAK = false;
                        SendMessageX($robot,$guid_message,14,$message_id,$lib_LR);
                    }else if($text == 'دستورات' || $text == 'راهنما' || $text == 'لیست'){
                        $YOUSPEAK = false;
                        // $TXT = "⚙️ دستورات\n\n💎  بن \nکاربر را حذف میکنه\n\n💎 رفع محدودیت \nکاربر را از لیست سیاه بیرون میاره\n\n💎  برکناری \nکاربر را از ادمینی بیرون میاره\n\n💎  اخطار \nبه کاربر اخطار میده\n\n💎  چالش \nیک سوال چالشی از خودتون یا هرکسی که ریپلای کنید میپرسه\n\n💎 آمار گپ\nفعالیت گپ تا کنون را ارسال می‌کنه\n\n💎 ویس کال\nگفتگو صوتی ایجاد می‌کنه\n\n💎 پین\nپیام ریپلای شده را پین می‌کنه\n\n💎 ارتقا\nکاربر را ادمین معمولی که تنها مجاز حذف پیام هست می‌کنه\n\n💎 لینک\nلینک گروه رو میده\n\n💎 تاریخ\nتاریخ و ساعت رو میگه\n\n💎 تاس\nیک عدد شانسی از تاس (1،6) رو میگه\n\n💎 عدد شانسی\nیک عدد از یک تا هزار رو شانسی میگه\n\n💎 تنظیم اخطار X\nتنظیم می‌کنه بعد از چند اخطار از گپ حذف شود\n___برای مثال\nتنظیم اخطار 5\nبه این معنا که کاربر اگر بیش از 3 لینک یا تبلیغ ارسال کنه از گپ حذف میشود";
                        SendMessage($robot,$lib_LR,"دستورات در این چنل قرار گرفته است\n\n@L8PSTUDIO_HELP",$message_id);
                    }else if($text == 'محدودیت ها' || $text == 'محدودیت'){
                        $YOUSPEAK = false;
                        limits($lib_LR,$robot,$message_id);
                    }
                }
        }
    
    $Tod = Tod($lib_LR);
    if($Tod[6] == 1){
        if($IS_YOU && $reme && $reme !== 'skip' && $text && $YOUSPEAK){
            // $Lguid_message = false;
            // if(!isset($LMessage['author_object_guid'])){
            //     if(isset($LMessage["event_data"])){
            //         $event = $LMessage["event_data"];
            //         if(isset($event['performer_object'])){
            //             $info_object = $event['performer_object'];
            //             if(isset($info_object["object_guid"])){
            //                 $Lguid_message = $info_object["object_guid"];
            //             }
            //         }
            //     }
            // }else{
            //     $Lguid_message = $LMessage['author_object_guid'];
            // }
            $YOUSPEAK2 = true;
            $pro_search1 = pro_search($text,"بگو");
            $MEname = "ربات";
            if($pro_search1){
                $re = $lib_BN->select(GUID_U,GUID);
                if(!is_null($re)){
                    $MEname = $re['name'];
                    $MEname = utf8_decode($MEname);
                }
                $pro_search3 = pro_search($text,"$MEname");
                $pro_search = pro_search($text,"ربات");
                if($pro_search || $pro_search3 ){
                    $YOUSPEAK2 = false;
                    $text = str_replace("ربات","","$text");
                    $text = str_replace("$MEname","","$text");
                    $text = str_replace("بگو","","$text");
                    $text = trim($text);
                    SendMessage($robot,$lib_LR,$text,$message_id);
                }
            }
            if($YOUSPEAK2){
                $SEND = false;
                $Rip = false;
                $RipX = false;
                if(isset($Message['reply_to_message_id'])){
                        $RipX = true;
                        $Reply_message_id = $Message['reply_to_message_id'];
                        $allMSG = $lib_LR->selectAMS_GUID(GUID_U);
                        if(!is_null($allMSG)){
                            $MSG = $allMSG['AMS'];
                            $MS = explode("-",$MSG);
                            foreach($MS as $M){
                                if($M == $Reply_message_id){
                                    $SEND = true;
                                    $Rip = true;
                                    break;
                                }
                            }
                        }
                }
                $re = $lib_BN->select(GUID_U,GUID);
                if(!is_null($re)){
                    $MEname = $re['name'];
                    $MEname = utf8_decode($MEname);
                }
                if(!$SEND && !$Rip && !$RipX){
                    $pro_search4 = pro_search($text,"$MEname");
                    $pro_searchS1 = pro_search($text,"ربات");
                    $pro_searchS2 = pro_search($text,"رباط");
                    $pro_searchS3 = pro_search($text,"روبات");
                    $pro_search5 = pro_search($text,"ج ح");
                    if($pro_searchS1 || $pro_searchS2 || $pro_searchS3 || $pro_search4 || $pro_search5){
                        $SEND = true;
                        $Rip = true;
                    }
                }
                
                // if(!$SEND && !$Rip && !$RipX){
                //     if($Lguid_message == GUID){
                //         $SEND = true;
                //     }
                // }
                if($SEND){
                    $textx = str_replace("$MEname",'',$text);
                    $textx = str_replace("ربات",'',$text);
                    $ALL_QUs = $lib_QU->select(1,GUID);
                    $ISQU = false;
                    uasort($ALL_QUs,'sortByLength');
                    foreach($ALL_QUs as $QU){
                        $ISIT = pro_search($textx,$QU);
                        if($ISIT){
                            $ISQU = $QU;
                            break;
                        }
                    }
                    if(!$ISQU){
                        foreach($ALL_QUs as $QU){
                            $ISIT = pro_search($text,$QU);
                            if($ISIT){
                                $ISQU = $QU;
                                break;
                            }
                        }
                    }
                    if($ISQU){
                        $ALL_ANS = $lib_ANS->select($QU,GUID);
                        if(!is_null($ALL_ANS)){
                            $cont = count($ALL_ANS);
                            $cont--;
                            $rand = rand(0,$cont);
                            if(isset($ALL_ANS[$rand])){
                                $FANS = $ALL_ANS[$rand];
                                if($Rip){
                                    SendMessageSpeak($robot,$lib_LR,$FANS,$message_id);
                                }else{
                                    SendMessageSpeak($robot,$lib_LR,$FANS,null);
                                }
                                return true;
                            }
                        }
                    }
                    
                    $ALL_QUs = $lib_QU->select_Def(1);
                    $ISQU = false;
                    uasort($ALL_QUs,'sortByLength');
                    foreach($ALL_QUs as $QU){
                        $ISIT = pro_search($textx,$QU);
                        if($ISIT){
                            $ISQU = $QU;
                            break;
                        }
                    }
                    if(!$ISQU){
                        foreach($ALL_QUs as $QU){
                            $ISIT = pro_search($text,$QU);
                            if($ISIT){
                                $ISQU = $QU;
                                break;
                            }
                        }
                    }
                    if($ISQU){
                        $ALL_ANS = $lib_ANS->select_Def($QU);
                        if(!is_null($ALL_ANS)){
                            $cont = count($ALL_ANS);
                            $cont--;
                            $rand = rand(0,$cont);
                            if(isset($ALL_ANS[$rand])){
                                $FANS = $ALL_ANS[$rand];
                                if($Rip){
                                    SendMessageSpeak($robot,$lib_LR,$FANS,$message_id);
                                }else{
                                    SendMessageSpeak($robot,$lib_LR,$FANS,null);
                                }
                                return true;
                            }
                        }
                    }
                }
                if(!$RipX){
                    $goodnight = false;
                    $goodmorning = false;
                    $re = $lib_BN->select(GUID_U,GUID);
                    $MEname = 'ربات';
                    if(!is_null($re)){
                        $MEname = $re['name'];
                        $MEname = utf8_decode($MEname);
                    }
                    $pro_search = pro_search($text,"$MEname");
                    $pro_searchr = pro_search($text,'ربات');
                    if($pro_search || $pro_searchr){
                        $result_B = $lib_BN->select(GUID_U,$guid_message);
                        $name = '';
                        if(!is_null($result_B)){
                            $name = $result_B['name'];
                            $name = utf8_decode($name);
                        }
                        $TEXT = "جووونم"." ".$name;
                        SendMessageSpeak($robot,$lib_LR,$TEXT,$message_id);
                        return true;
                    }
                    $keys  = ['سلام','های','شب خش','شب شیک','چت خش','چت خوش','شب خوش','شب بخیر','صبح بخیر','صب بخیر','بای','خدافظ','خداحافظ','بحی','گودبای','گود بای'];
                    $key = false;
                    foreach($keys as $ke){
                        $pro_search1 = pro_search($text,"$ke");
                        if($pro_search1){
                            $result_B = $lib_BN->select(GUID_U,$guid_message);
                            $name = '';
                            if(!is_null($result_B)){
                                $name = $result_B['name'];
                                $name = utf8_decode($name);
                            }
                            $TEXT = "$ke"." ".$name;
                            SendMessageSpeak($robot,$lib_LR,$TEXT,$message_id);
                            return true;
                        }
                    }
                }
            }
        }
    }
    return $reme;
}

function selfBot($Message,$Main_guid,$text,$message_id,$guid_message,$lib,$lib_LR,$lib_QU,$lib_ANS,$robot,$lib_BN,$Is_fulladmin,$Is_owner,$METHOD){
        $YOUSPEAK = true;
        if($Is_owner){
            $pro_search = pro_search($text,"ربات");
            $pro_search1 = pro_search($text,"https://rubika.ir/joing/");
            if($pro_search && $pro_search1){
                $YOUSPEAK = false;
                $text = str_replace("ربات","","$text");
                $text = str_replace("https://rubika.ir/joing/","","$text");
                $hash_link = trim($text);
                $result = join_groups($robot,$hash_link);
                if($result){
                    SendMessageSpeakSelf($robot,$Main_guid,$lib_LR,"اومدم عشقم",$message_id);
                }else{
                    SendMessageSpeakSelf($robot,$Main_guid,$lib_LR,"نتونستم بیام",$message_id);
                }
            }
            $pro_search = pro_search($text,"GO");
            $pro_search1 = pro_search($text,"https://rubika.ir/joing/");
            if($pro_search && $pro_search1){
                $YOUSPEAK = false;
                $text = str_replace("GO","","$text");
                $text = str_replace("https://rubika.ir/joing/","","$text");
                $hash_link = trim($text);
                $result = join_groups($robot,$hash_link);
                if($result){
                    $GROUP_GUID = $result['group_guid'];
                    $lib->updateGuid_U(AUTH,$GROUP_GUID);
                    $lib->updateRegester(AUTH,0);
                    SendMessageSpeakSelf($robot,AOWNER,$lib_LR,"لینک گپ با موفقیت ثبت شد. ✅ \n\nچند دقیقه دیگه میام سلطان",$message_id);
                }else{
                    SendMessageSpeakSelf($robot,AOWNER,$lib_LR,"نتونستم بیام",$message_id);
                }
            }
            if($text == "GO OUT"){
                $GUID_U = $lib->selectGUIDU(AUTH);
                $GUID_U = $GUID_U['GUID_U'];
                if(is_null($GUID_U) || $GUID_U == 'null'){
                    SendMessageSpeakSelf($robot,AOWNER,$lib_LR,"در گپی فعالیت نمیکنم سلطان",$message_id);
                }else{
                    $YOUSPEAK = false;
                    $hash_link = trim($text);
                    $NameGap = $lib_LR->selectName_GUID(GUID_U);
                    $result = $lib->updateGuid_U(AUTH,'null');
                    $lib->updateRegester(AUTH,0);
                    if($result){
                        SendMessageSpeakSelf($robot,AOWNER,$lib_LR,"فعالیت روی گپ $NameGap با موفقیت متوقف شد. ✅ \n\nچند دقیقه دیگه غیر فعال میشه سلطان",$message_id);
                    }else{
                        SendMessageSpeakSelf($robot,AOWNER,$lib_LR,"نتونستم فعالیت رو متوقف کنم",$message_id);
                    }
                }
            }
            $pro_search = pro_search($text,"ربات");
            $pro_search1 = pro_search($text,"لف");
            if($pro_search && $pro_search1){
                $YOUSPEAK = false;
                $result = leeve_groups($robot,$Main_guid);
                if($result){
                    SendMessageSpeakSelf($robot,AOWNER,$lib_LR,"لف دادم سلطان",$message_id);
                }else{
                    SendMessageSpeakSelf($robot,AOWNER,$lib_LR,"نتونستم لف بدم",$message_id);
                }
            }
            if($text == 'اشتراک' || $text == 'وضعیت اشتراک'){
                setting_ENDTIME($robot,$lib);
                $YOUSPEAK = false; 
            }
        }
        
        $pro_search = pro_search($text,"ChangeBot");
        if($pro_search){
            $text = str_replace("ChangeBot","","$text");
            $text = trim($text);
            $change = explode("\n",$text);
            $New = $change[0];
            $Old = $change[1];
            if($Old == GUID){
                $lib_QU->updateGUID_U($New,$Old);
                $lib_ANS->updateGUID_U($New,$Old);
                SendMessageSpeakSelf($robot,$Main_guid,$lib_LR,"انتقال با موفقعیت انجامید. ✅",$message_id);
            }else{
                SendMessageSpeakSelf($robot,$Main_guid,$lib_LR," دستور اشتباه وارد کردید. ❌",$message_id);
            }
        }

        $pro_search = pro_search($text,"یاد بگیر");
        $pro_search1 = pro_search($text,"یادبگیر");
        if($pro_search || $pro_search1){
            $pro_search = pro_search($text,"=");
            if($pro_search){
                $YOUSPEAK = false;
                $need = '=';
                $pos = strpos($text,$need);
                $QU =  substr($text,0,$pos);
                $QU = str_replace("یاد بگیر","","$QU");
                $QU = str_replace("یادبگیر","","$QU");
                $QU = trim($QU);
                $ANS =  substr($text,$pos);
                $ANS = str_replace("=","","$ANS");
                $ANS = trim($ANS);
                if(!is_null($ANS) && !is_null($QU)){
                    $pats['QU'] =$QU;
                    $pats['LEVEL'] =1;
                    $pats['ANS'] =$ANS;
                    $pats['GUID_U'] =GUID;
                    $result = $lib_ANS->insert($pats);
                    $result1 = $lib_QU->insert($pats);
                    if($result && $result1){
                        SendMessageSpeakSelf($robot,$Main_guid,$lib_LR,$ANS,$message_id);
                    }
                }
                return true;
            }
        }
        $pro_search = pro_search($text,"فراموش کن");
        if($pro_search){
            $pro_search = pro_search($text,"=");
            if($pro_search){
                $YOUSPEAK = false;
                $need = '=';
                $pos = strpos($text,$need);
                $QU =  substr($text,0,$pos);
                $QU = str_replace("فراموش کن","","$QU");
                $QU = trim($QU);
                $ANS =  substr($text,$pos);
                $ANS = str_replace("=","","$ANS");
                $ANS = trim($ANS);
                if(!is_null($ANS) && !is_null($QU)){
                    $result = $lib_ANS->delete($QU,$ANS,GUID);
                    $result1 = $lib_QU->delete($QU,GUID);
                    if($result || $result1){
                        $ALARM = "فراموش شد."." ✅ ";
                        $TEXT = orders($ALARM);
                        SendMessageSpeakSelf($robot,$Main_guid,$lib_LR,$TEXT,$message_id);
                    }else{
                        SendMessageSpeakSelf($robot,$Main_guid,$lib_LR,"فراموش نشد.",$message_id);
                    }
                }
                return true;
            }else if($text == 'فراموش کن'){
                $YOUSPEAK = false;
                $replyMessage = Reply_MessageX($Message,$robot,$Main_guid);
                if($replyMessage === 'skip'){
                    SendMessageSpeakSelf($robot,$Main_guid,$lib_LR,"ریپلای بزن تا جواب مورد نظر حذف شود.",$message_id);
                }else if(!$replyMessage){
                    SendMessageSpeakSelf($robot,$Main_guid,$lib_LR,"پیامش پاک شده",$message_id);
                }else{
                    $Reply_message_text = $replyMessage['text'];
                    $Reply_message_text = substr($Reply_message_text, 0, -4);
                    $Reply_message_text = trim($Reply_message_text);
                    // SendMessage($robot,$lib_LR,"$Reply_message_text",$message_id);
                    $result = $lib_ANS->delete_ANS($Reply_message_text,GUID);
                    if($result){
                        $ALARM = "فراموش شد."." ✅ ";
                        $TEXT = orders($ALARM);
                        SendMessageSpeakSelf($robot,$Main_guid,$lib_LR,$TEXT,$message_id);
                    }else{
                        SendMessageSpeakSelf($robot,$Main_guid,$lib_LR,"فراموش نشد.",$message_id);
                    }
                }
            }
        }

        $MEname = 'ربات';
        if($METHOD){
            $re = $lib_BN->select(GUID_U,GUID);
            if(!is_null($re)){
                $MEname = $re['name'];
                $MEname = utf8_decode($MEname);
            }
        }
        $pro_search3 = pro_search($text,"$MEname");
        $pro_search = pro_search($text,"ربات");
        $pro_search1 = pro_search($text,"بگو");
        if($pro_search || $pro_search3){
            if($pro_search1){
                $YOUSPEAK = false;
                $text = str_replace("ربات","","$text");
                $text = str_replace("$MEname","","$text");
                $text = str_replace("بگو","","$text");
                $text = trim($text);
                SendMessageSpeakSelf($robot,$Main_guid,$lib_LR,$text,$message_id);
            }
        }
        if($YOUSPEAK && $METHOD){
            if($text == 'لینک'){
                $YOUSPEAK = false;
                $link_info =  GetLink($robot);
                if($link_info){
                    if(isset($link_info['status']) && $link_info['status'] === 'OK'){
                        $link = $link_info['data']['join_link'];
                        SendMessageSpeakSelf($robot,$Main_guid,$lib_LR,"$link",$message_id);
                    }
                }
            }
        }
        if($YOUSPEAK){
            $SEND = false;
            $Rip = false;
            $RipX = false;
            if(isset($Message['reply_to_message_id'])){
                    $RipX = true;
                    $Reply_message_id = $Message['reply_to_message_id'];
                    $allMSG = $lib_LR->selectAMS_GUID(GUID_U);
                    if(!is_null($allMSG)){
                        $MSG = $allMSG['AMS'];
                        $MS = explode("-",$MSG);
                        foreach($MS as $M){
                            if($M == $Reply_message_id){
                                $SEND = true;
                                $Rip = true;
                                break;
                            }
                        }
                    }
            }
            if(!$SEND && !$Rip && !$RipX){
                $MEname = 'ربات';
                if($METHOD){
                    $re = $lib_BN->select(GUID_U,GUID);
                    if(!is_null($re)){
                        $MEname = $re['name'];
                        $MEname = utf8_decode($MEname);
                    }
                }
                $pro_search4 = pro_search($text,"$MEname");
                $pro_searchS1 = pro_search($text,"ربات");
                $pro_searchS2 = pro_search($text,"رباط");
                $pro_searchS3 = pro_search($text,"روبات");
                if($pro_searchS1 || $pro_searchS2 || $pro_searchS3 || $pro_search4){
                    $SEND = true;
                    $Rip = true;
                }
            }
            // if(!$SEND && !$Rip && !$RipX){
            //     if($Lguid_message == GUID){
            //         $SEND = true;
            //     }
            // }
            // $SEND = true;

            if($SEND){
                $MEname = 'ربات';
                if($METHOD){
                    $re = $lib_BN->select(GUID_U,GUID);
                    if(!is_null($re)){
                        $MEname = $re['name'];
                        $MEname = utf8_decode($MEname);
                    }
                }
                $textX = str_replace("$MEname",'',$text);
                $textx = str_replace("ربات",'',$text);
                $ALL_QUs = $lib_QU->select(1,GUID);
                $ISQU = false;
                uasort($ALL_QUs,'sortByLength');
                foreach($ALL_QUs as $QU){
                    $ISIT = pro_search($textx,$QU);
                    if($ISIT){
                        $ISQU = $QU;
                        break;
                    }
                }
                if(!$ISQU){
                    foreach($ALL_QUs as $QU){
                        $ISIT = pro_search($text,$QU);
                        if($ISIT){
                            $ISQU = $QU;
                            break;
                        }
                    }
                }
                if($ISQU){
                    $ALL_ANS = $lib_ANS->select($QU,GUID);
                    if(!is_null($ALL_ANS)){
                        $cont = count($ALL_ANS);
                        $cont--;
                        $rand = rand(0,$cont);
                        if(isset($ALL_ANS[$rand])){
                            $FANS = $ALL_ANS[$rand];
                            if($Rip){
                                SendMessageSpeakSelf($robot,$Main_guid,$lib_LR,$FANS,$message_id);
                            }else{
                                SendMessageSpeakSelf($robot,$Main_guid,$lib_LR,$FANS,null);
                            }
                            return true;
                        }
                    }
                }
                
                $ALL_QUs = $lib_QU->select_Def(1);
                $ISQU = false;
                uasort($ALL_QUs,'sortByLength');
                foreach($ALL_QUs as $QU){
                    $ISIT = pro_search($textx,$QU);
                    if($ISIT){
                        $ISQU = $QU;
                        break;
                    }
                }
                if(!$ISQU){
                    foreach($ALL_QUs as $QU){
                        $ISIT = pro_search($text,$QU);
                        if($ISIT){
                            $ISQU = $QU;
                            break;
                        }
                    }
                }
                if($ISQU){
                    $ALL_ANS = $lib_ANS->select_Def($QU);
                    if(!is_null($ALL_ANS)){
                        $cont = count($ALL_ANS);
                        $cont--;
                        $rand = rand(0,$cont);
                        if(isset($ALL_ANS[$rand])){
                            $FANS = $ALL_ANS[$rand];
                            if($Rip){
                                SendMessageSpeakSelf($robot,$Main_guid,$lib_LR,$FANS,$message_id);
                            }else{
                                SendMessageSpeakSelf($robot,$Main_guid,$lib_LR,$FANS,null);
                            }
                            return true;
                        }
                    }
                }
            }

            if($METHOD){
                $goodnight = false;
                $goodmorning = false;
                $re = $lib_BN->select(GUID_U,GUID);
                $MEname = 'ربات';
                if(!is_null($re)){
                    $MEname = $re['name'];
                    $MEname = utf8_decode($MEname);
                }
                $pro_search = pro_search($text,"$MEname");
                $pro_search1 = pro_search($text,'ربات');
                if($pro_search || $pro_search1){
                    $result_B = $lib_BN->select(GUID_U,$guid_message);
                    $name = '';
                    if(!is_null($result_B)){
                        $name = $result_B['name'];
                        $name = utf8_decode($name);
                    }
                    $TEXT = "جووونم"." ".$name;
                    SendMessageSpeakSelf($robot,$Main_guid,$lib_LR,$TEXT,$message_id);
                    return true;
                }
                $keys  = ['سلام','های','شب خش','شب شیک','چت خش','چت خوش','شب خوش','شب بخیر','صبح بخیر','صب بخیر','بای','خدافظ','خداحافظ','بحی','گودبای','گود بای'];
                $key = false;
                foreach($keys as $ke){
                    $pro_search1 = pro_search($text,"$ke");
                    if($pro_search1){
                        $result_B = $lib_BN->select(GUID_U,$guid_message);
                        $name = '';
                        if(!is_null($result_B)){
                            $name = $result_B['name'];
                            $name = utf8_decode($name);
                        }
                        $TEXT = "$ke"." ".$name;
                        SendMessageSpeakSelf($robot,$Main_guid,$lib_LR,$TEXT,$message_id);
                        return true;
                    }
                }
            
            }

        }
}