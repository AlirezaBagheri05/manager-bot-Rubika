<?PHP

function pro_search($text,$needle){
    $result = stripos("$text","$needle");
    if($result || $result === 0){
        return true;
    }else{
        return false;
    }
}
function seenChats($seen_list,$robot){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $ok = $robot->seenChats($seen_list);
        if(is_null($ok)){
            $LIMIT--;
            continue;
        }
        if(isset($ok['status']) && $ok['status'] == 'OK'){
            return true;
        }
        $LIMIT--;
        continue;
    }
}

function getChats($robot){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $Requ = $robot->getChats();
        if(is_null($Requ)){
            $LIMIT--;
            continue;
        }
        if(!isset($Requ['status']) || $Requ['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        return $Requ;
        
    }
}
function linkRemoverH($lib_LR,$TY){
    $info_LR = $lib_LR->select_Guid(GUID_U);
    $All_LR = $info_LR['All'];
    $All = explode("-",$All_LR);
    $A = $All[$TY];
    $A = intval($A);
    $All[$TY] = $A+1;
    $pats1['All'] = $All['0'].'-'.$All['1'].'-'.$All['2'].'-'.$All['3'].'-'.$All['4'].'-'.$All['5'].'-'.$All['6'].'-'.$All['7'].'-'.$All['8'].'-'.$All['9'].'-'.$All['10'].'-'.$All['11'].'-'.$All['12'].'-'.$All['13'].'-'.$All['14'].'-'.$All['15'].'-'.$All['16'].'-'.$All['17'].'-'.$All['18'].'-'.$All['19'].'-'.$All['20'].'-'.$All['21'];
    $lib_LR->update_GUID(GUID_U,$pats1);
}
function RemoveMember($message_id,$guid_message,$robot,$lib_LR,$lib_BN,$Setting,$Is_admin,$Content){
    if($Content == 90){$Content = 0;}
    if($Content != 100){
        linkRemoverH($lib_LR,$Content);
    }

    $info_bands = $lib_BN->select(GUID_U,$guid_message);
    if(is_null($info_bands)){
        $user = GET_USER_BY_GUID($guid_message,$robot);
        if(!$user){
            return 'skip';
        } 
        $FLName = '';
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
    }
    
    $NM = trim($info_bands['name']);
    $NMC = strlen($NM);
    if(is_null($NM) || $NMC == 0){
        $user = GET_USER_BY_GUID($guid_message,$robot);
        if(!$user){
            return 'skip';
        }
        $FLName = '';
        if(isset($user['first_name'])){
            $FLName = $user['first_name'];
        }
        $text = utf8_encode($FLName);
        $result_B = $lib_BN->updateName(GUID_U,$guid_message,$text);
        if(!$result_B){
            return false;
        }
    }

    if($Content != 100){
        if($Is_admin){
            $reports = $info_bands['report'];
            $reports = $reports+1;
            $lib_BN->updateReport(GUID_U,$guid_message,$reports);
            return true;
        }
    }
    if(isset($Setting[$Content])){
            $reports = $info_bands['report'];
            $reports = $reports+1;
            $lib_BN->updateReport(GUID_U,$guid_message,$reports);
            if($Setting[$Content] == 1){
            return true;
            }
    }


    $Tod = Tod($lib_LR);
    $Max = $info_bands['Max'];
    $state = $info_bands['state'];
    $Max = intval($Max);
    $state = intval($state);
    $state++;
    $names = ['متن','لینک','ایدی','منشن','نظرسنجی','استیکر','فایل','گیف','ویس','تصویر','اهنگ','ویدیو','پیام هدایت شده','گفتگو صوتی','کاربر افزوده شده','کاریر حذف شده','سنجاق پیام','عضو شده با لینک','ترک گروه','پست روبینو','استوری روبینو','لایو'];
    usleep(100000);
    if($Max == $state){
        DELETEXX($robot,["$message_id"]);
        if($Tod[10] == 1){
            BanUser($robot,$guid_message);
        }
    }else{
        DELETEXX($robot,["$message_id"]);
    }
    usleep(100000);
    if($Content == 100){
        $SW = "اخطار"." $state "."از"." $Max"." ⚠️";;
        sendMessage_MNTION($robot,$lib_LR,$guid_message,$message_id,"",$SW);
    }else{
        if($Max > $state){
            if($Tod[8] == 1){
                $WAR = $names[$Content];
                $WAR = "$WAR"." ممنوع است."." ⚠️";
                $SW = "اخطار"." $state "."از"." $Max \n";
                sendMessage_MNTION($robot,$lib_LR,$guid_message,$message_id,$WAR,$SW);
            }
        }
        if($Max == $state){
            if($Tod[8] == 1){
                $WAR = $names[$Content];
                $WAR = "$WAR"." ممنوع است."." ⚠️";
                $SW = "اخطار"." $state "."از"." $Max \n"."🚫 اخراج شد 🚫";
                sendMessage_MNTION($robot,$lib_LR,$guid_message,$message_id,$WAR,$SW);
            }
        }
    }
    $reports = $info_bands['report'];
    $reports = intval($reports);
    $reports = $reports+1;
    $lib_BN->updateReport(GUID_U,$guid_message,$reports);
    $lib_BN->updateState(GUID_U,$guid_message,$state);
    return 'skip';
}
function reaports_all($lib_LR,$step){
        $info_LR = $lib_LR->select_Guid(GUID_U);
        $Today_LR = $info_LR['All'];
        $Tod = explode("-",$Today_LR);
        $ALL = 0;
        foreach($Tod as $T){
            $T = intval($T);
            $ALL = $ALL+$T;
        }
        $TXT = title('آمار کلی فعالیت های گپ تا این لحظه');
        $day = dayp(time());
        $date = date_time(time());
        $TXT .= mini("$day")." ، $date"."\n";
        $TXT .= mini("ساعت")." : ".clock(time())."\n";
        $TXT .= mini("کل پیام ها")." : ".$ALL."\n\n";
        $count_Tod = count($Tod);
        $count_Tod--;
        $names = ['متن','لینک','ایدی','منشن','نظرسنجی','استیکر','فایل','گیف','ویس','تصویر','اهنگ','ویدیو','پیام هدایت شده','گفتگو صوتی','کاربر افزوده شده','کاریر حذف شده','سنجاق پیام','عضو شده با لینک','ترک گروه','پست روبینو','استوری روبینو','لایو'];
        
        $d = 0;
        $max = 25;
        if($step == "Apage1"){
            $d = 0;
            $max = 10;
        }else if($step == "Apage2"){
            $d = 10;
            $max = 20;
        }else if($step == "Apage3"){
            $d = 20;
            $max = 30;
        }else if($step == "Apage4"){
            $d = 30;
            $max = 40;
        }
        for($i=$d;$i<=$max;$i++){
            if(isset($names[$i])){
                $name = $names[$i];
                $count =  $Tod[$i];
                $percent = (100*$count)/$ALL;
                $percent = round($percent);
                $TXT .= mini("$name")." : ".intval($Tod[$i])."\n";
                $TXT .= shaps($percent);
            }
        }
        

        if($max > 30){
            $TXT .= COMD;
            $TXT .= "/Apage3"." ⬅️ "."صفحه قبلی";
            $TXT .= "\n\n";
        }else if($max > 20){
            $TXT .= COMD;
            $TXT .= "/Apage2"." ⬅️ "."صفحه قبلی";
            $TXT .= "\n\n";
            $TXT .= "/Apage4"." ➡️ "."صفحه بعدی";
        }else if($max > 10){
            $TXT .= COMD;
            $TXT .= "/Apage1"." ⬅️ "."صفحه قبلی";
            $TXT .= "\n\n";
            $TXT .= "/Apage3"." ➡️ "."صفحه بعدی";
        }else{
            $TXT .= COMD;
            $TXT .= "/Apage2"." ➡️ "."صفحه بعدی";
        }

        $TXT = end_form($TXT);
        return $TXT;
}
function sorchChaps($lib_LR,$lib){
    $alls = $lib_LR->select_Alls();
    $GUIDS = $lib->select_GUID_U();
    $LMS = null;
    foreach($GUIDS as $guid){
        $all = $lib_LR->select_Guid($guid);
        if(isset($all['All'])){
            $all = $all['All'];
            $all = explode('-',$all);
            $ms = $all[0];
            if(is_null($LMS)){
                $LMS = $ms;
                $ALL[] = $ms;
            }else if($LMS != $ms){
                $ALL[] = $ms;
            }
        }
    }
    rsort($ALL);
    $GAPS = $lib_LR->select_AllsGuids();
    $ALLGAPS = array();
    foreach($ALL as $num){
        foreach($GAPS as $GAP){
            $GUID_Y = $GAP[0];
            $ALL_R = $GAP[1];
            $Name = $GAP[2];
            if(is_null($Name)){
                $Name = 'NO_NAME';
            }else{
                $Name = utf8_decode($Name);
            }
            $ok = explode('-',$ALL_R);
            $ms = $ok[0];
            if($ms == $num){
                $ALLGAPS[] = ["$num","$Name","$GUID_Y"];
                break;
            }
        }
    }
    return $ALLGAPS;
}
function Gaps_all($lib_LR,$lib){
        $ALLGAPS = sorchChaps($lib_LR,$lib);
        $ALL = 0;
        foreach($ALLGAPS as $AGAP){
            $ms = $AGAP[0];
            $ms = intval($ms);
            $ALL = $ALL+$ms;
        }
        $ALM = ":/";
        $myScore = ':/';
        $NameM = ':/';
        $count_gaps = $lib->count();
        for($i=0;$i<$count_gaps;$i++){
            if(isset($ALLGAPS[$i])){
                $ALMS = $ALLGAPS[$i][0];
                $Name = $ALLGAPS[$i][1];
                $GUID_Y = $ALLGAPS[$i][2];
                if($GUID_Y == GUID_U){
                    $ALM = $ALMS;
                    $NameM = $Name;
                    $myScore = $i+1;
                    break;
                }
            }
        }
        $TXT = title('فعال ترین گپ به ترتیب');
        $day = dayp(time());
        $date = date_time(time());
        $TXT .= mini("$day")." ، $date"."\n";
        $TXT .= mini("ساعت")." : ".clock(time())."\n";
        $TXT .= mini("گپ")." : ".$NameM."\n";
        $TXT .= mini("رتبه گپ")." : ".$myScore.' با '."$ALM".' پیام'."\n\n";
        $TXT .= mini("تعداد گپ ها")." : ".$count_gaps."\n";
        $TXT .= mini("کل پیام ها")." : ".$ALL."\n\n";
        $rang = 1;
        $mmax =  $ALLGAPS[0][0];
        for($i=0;$i<=4;$i++){
            if(isset($ALLGAPS[$i])){
                $count =  $ALLGAPS[$i][0];
                $name = $ALLGAPS[$i][1];
                $percent = (100*$count)/$mmax;
                $percent = round($percent);
                $name = 'گپ'.' '.$name.' با '.$count.' '.'پیام';
                $name = mini("$name");
                $TXT .= $rang.' '.$name."\n\n";
                $TXT .= shaps($percent)."\n";
                $rang++;
            }
        }

        $TXT = end_form($TXT);
        return $TXT;
}
function sorchUsers($lib_BN,$lib){
    $GUIDUS = $lib->select_GUID_U();
    $GUIDS = $lib->select_GUID();
    $ALL = array();
    $Lreport = null;
    foreach($GUIDUS as $guidd){
        $infos = $lib_BN->select_allRepoartX($guidd);
        foreach($infos as $info){
            $report = $info[1];
            if($report > 2000){
                $Guid_user = $info[0];
                $ve = true;
                foreach($GUIDS as $GUIDms){
                    if($GUIDms == $Guid_user){
                        $ve = false;
                        break;
                    }
                }
                if(!$ve){
                    continue;
                }
                if(is_null($Lreport)){
                    $Lreport = $report;
                    $ALL[] = $report;
                    continue;
                }else{
                    if($Lreport == $report){
                        continue;
                    }else{
                        $ALL[] = $report;
                        $Lreport = $report;
                    }
                }
            }
        }
    }
    rsort($ALL);
    $ARRAYusers = array();
    $f = 0;
    foreach($ALL as $one){
        $user = $lib_BN->select_BY_reports($one);
        foreach($user as $name){
            $name = utf8_decode($name);
            $s = $f-1;
            if(isset($ARRAYusers[$s])){
                $last_us = $ARRAYusers[$s];
                $namex =  $last_us[0];
                if($namex == $name){
                    continue;
                }
            }
            $ARRAYusers[$f] = ["$name","$one"];
            $f++;
        }
    }
    return $ARRAYusers;
}
function Top_users($lib_BN,$lib,$lib_LR,$step){
    $ARRAYusers = sorchUsers($lib_BN,$lib);
    $NAMEG = $lib_LR->selectName_GUID(GUID_U);
    $TXT = title('فعال ترین کاربران روبیکا به ترتیب');
    $day = dayp(time());
    $date = date_time(time());
    $TXT .= mini("$day")." ، $date"."\n";
    $TXT .= mini("ساعت")." : ".clock(time())."\n";
    $TXT .= mini("گپ")." : ".$NAMEG."\n\n";

    $d = 0;
    $max = 10;
    if($step == "Dpage1"){
        $d = 0;
        $max = 10;
    }else if($step == "Dpage2"){
        $d = 10;
        $max = 20;
    }else if($step == "Dpage3"){
        $d = 20;
        $max = 30;
    }else if($step == "Dpage4"){
        $d = 30;
        $max = 40;
    }

    $rang = $d+1;
    $mmax =  $ARRAYusers[0][1];

    for($i=$d;$i<$max;$i++){
        if(isset($ARRAYusers[$i])){
            $name =  $ARRAYusers[$i][0];
            $count = $ARRAYusers[$i][1];
            $count = intval($count);
            $percent = (100*$count)/$mmax;
            $percent = round($percent);
            $name = 'کاربر'.' '.$name.' با '.$count.' '.'پیام';
            $name = mini("$name");
            $TXT .= $rang.' '.$name."\n\n";
            $TXT .= shaps($percent)."\n";
            $rang++;
        }
    }

    if($max > 30){
        $TXT .= COMD;
        $TXT .= "/Dpage3"." ⬅️ "."صفحه قبلی";
        $TXT .= "\n\n";
    }else if($max > 20){
        $TXT .= COMD;
        $TXT .= "/Dpage2"." ⬅️ "."صفحه قبلی";
        $TXT .= "\n\n";
        $TXT .= "/Dpage4"." ➡️ "."صفحه بعدی";
    }else if($max > 10){
        $TXT .= COMD;
        $TXT .= "/Dpage1"." ⬅️ "."صفحه قبلی";
        $TXT .= "\n\n";
        $TXT .= "/Dpage3"." ➡️ "."صفحه بعدی";
    }else{
        $TXT .= COMD;
        $TXT .= "/Dpage2"." ➡️ "."صفحه بعدی";
    }

    $TXT = end_form($TXT);
    return $TXT;
}
function condition_panel($robot,$lib_LR,$message_id){
    $Tod = Tod($lib_LR);
    $DO= 'ON';
    $DO1= 'ON';
    $DO2= 'ON';
    $DO3= 'ON';
    $DO4= 'ON';
    $DO6= 'ON';
    $O = 'خواب است ❌';
    if($Tod[7] == 1){
        $O = 'بیدار است ✅';
        $DO = 'OFF';
    } 
    $O1 = 'خاموش است ❌';
    if($Tod[6] == 1){
        $O1 = 'روشن است ✅';
        $DO1 = 'OFF';
    }    
    $O2 = 'خاموش است ❌';
    if($Tod[8] == 1){
        $O2 = 'روشن است ✅';
        $DO2 = 'OFF';
    }
    $O3 = 'خاموش است ❌';
    if($Tod[9] == 1){
        $O3 = 'روشن است ✅';
        $DO3 = 'OFF';
    }
    $O4 = 'قفل است ❌';
    if($Tod[10] == 1){
        $O4 = 'ازاد است ✅';
        $DO4 = 'OFF';
    }
    $O5 = 'NONE';
    if(isset($Tod[11])){
        $O5 = $Tod[11];
        $O5 = intval($O5);
        $O5 = round($O5);
    }
    $O6 = 'خاموش است ❌';
    if($Tod[12] == 1){
        $O6 = 'روشن است ✅';
        $DO6 = 'OFF';
    }
    $TEXT = "⚙ | ᑕOᑎᗪITIOᑎ"."\n";
    $TEXT .= COMD;
    $TEXT .= "/Robot"."$DO"." လ "."ربات"." $O "."\n\n";
    $TEXT .= "/Speaking"."$DO1"." လ "."حالت سخنگو"." $O1 "."\n\n";
    $TEXT .= "/Warnning"."$DO2"." လ "."نمایش اخطار"." $O2 "."\n\n";
    $TEXT .= "/Tabchi"."$DO3"." လ "."شناسایی تبچی"." $O3 "."\n\n";
    $TEXT .= "/BanUser"."$DO4"." လ "."حذف کاربر"." $O4 "."\n\n";
    $TEXT .= "/SetSpeed"." လ "."سرعت پاسخگویی"." ".$O5." ثانیه "."است"."\n\n";
    $TEXT .= "/Strict"."$DO6"." လ "."محدودیت سختگیرانه"." $O6 "."\n\n";
    $TEXT .= "/BACKPanel"." ⌫ "."بازگشت";
    SendMessage($robot,$lib_LR,"$TEXT",$message_id);
}
function setting_panel($robot,$lib_LR,$message_id){
    $allAccess = getGroupDefaultAccess($robot);
    $AAccess = $allAccess['access_list'];
    // $arrayAccess = ['SendMessages','AddMember','ViewAdmins','ViewMembers'];
    $SendMessages = false;
    $AddMember = false;
    $ViewAdmins = false;
    $ViewMembers = false;
    
    foreach($AAccess as $Access){
        if($Access == 'SendMessages'){
            $SendMessages = true;
        }else if($Access == 'AddMember'){
            $AddMember = true;
        }else if($Access == 'ViewAdmins'){
            $ViewAdmins = true;
        }else if($Access == 'ViewMembers'){
            $ViewMembers = true;
        }
    }
    
    $DO= 'ON';
    $DO1= 'ON';
    $DO2= 'ON';
    $DO3= 'ON';

    $O = 'قفل است ❌';
    if($SendMessages){
        $O = 'ازاد است ✅';
        $DO = 'OFF';
    } 
    $O1 = 'قفل است ❌';
    if($AddMember){
        $O1 = 'ازاد است ✅';
        $DO1 = 'OFF';
    }    
    $O2 = 'قفل است ❌';
    if($ViewAdmins){
        $O2 = 'ازاد است ✅';
        $DO2 = 'OFF';
    }
    $O3 = 'قفل است ❌';
    if($ViewMembers){
        $O3 = 'ازاد است ✅';
        $DO3 = 'OFF';
    }

    $TEXT = "⚙ | ՏᗴTTIᑎᘜ"."\n";
    $TEXT .= COMD;
    $TEXT .= "/SendMessages"."$DO"." လ "."ارسال پیام"." $O "."\n\n";
    $TEXT .= "/AddMember"."$DO1"." လ "."افزودن عضو"." $O1 "."\n\n";
    $TEXT .= "/ViewAdmins"."$DO2"." လ "."مشاهده مدیران"." $O2 "."\n\n";
    $TEXT .= "/ViewMembers"."$DO3"." လ "."مشاهده اعضا"." $O3 "."\n\n";
    $TEXT .= "/ListFullAdmins"." လ "."لیست کاربران ویژه"."\n\n";
    $TEXT .= "/ListAdmins"." လ "."لیست ادمین ها"."\n\n";
    $TEXT .= "/BACKPanel"." ⌫ "."بازگشت";
    SendMessage($robot,$lib_LR,"$TEXT",$message_id);
}
function List_fulladmins($lib,$lib_BN){
    $AFAA = $lib->selectFullAdmins(AUTH);
    $AFAA = $AFAA['FullAdmins'];
    $AFA = explode('-',$AFAA);
    $array = array();
    foreach($AFA as $FA){
        $Nuser = $lib_BN->selectName(GUID_U,$FA);
        $user = $Nuser['name'];
        $user = utf8_decode($user);
        $array[] = $user;
    }
    return $array;
}
function show_fulladmins($lib,$lib_BN,$robot,$message_id,$lib_LR){
    $Fadmins = List_fulladmins($lib,$lib_BN);
    $All = '';
    $All = title('لیست کاربران ویژه');
    foreach($Fadmins as $F){
        if(is_null($F)){
            continue;
        }
        $All .= mini("کاربر"." ".$F)."\n";
    }
    $All = end_form($All);
    SendMessage($robot,$lib_LR,"$All",$message_id);
}
function List_admins($lib,$lib_BN){
    $AFAA = $lib->selectAdmins(AUTH);
    $AFAA = $AFAA['Admins'];
    $AFA = explode('-',$AFAA);
    $array = array();
    foreach($AFA as $FA){
        $Nuser = $lib_BN->selectName(GUID_U,$FA);
        $user = $Nuser['name'];
        $user = utf8_decode($user);
        $array[] = $user;
    }
    return $array;
}
function show_admins($lib,$lib_BN,$robot,$message_id,$lib_LR){
    $admins = List_admins($lib,$lib_BN);
    $All = '';
    $All = title('لیست مدیران');
    foreach($admins as $F){
        $All .= mini("کاربر"." ".$F)."\n";
    }
    $All = end_form($All);
    SendMessage($robot,$lib_LR,"$All",$message_id);
}
function locks_panel($robot,$lib_LR,$message_id){
    $names = ['متن','لینک','ایدی','منشن','نظرسنجی','استیکر','فایل','گیف','ویس','تصویر','اهنگ','ویدیو','پیام هدایت شده','گفتگو صوتی','پیام کاربر افزوده شده','پیام کاربر حذف شده','سنجاق پیام','پیام پیوستن','پیام ترک کردن','پست روبینو','استوری روبینو','لایو'];
    // $namesX = ['افزودن کاربر','حذف کاربر','پیوستن','ترک کردن'];
    $st = $lib_LR->selectSetting_GUID(GUID_U);
    $Setting = $st['Setting'];
    $SAT = explode("-",$Setting);

    $Tod = Tod($lib_LR);
    $DO= 'ON';
    $DO1= 'ON';
    $DO2= 'ON';
    $DO3= 'ON';
    $DO4= 'ON';
    $DO5= 'ON';

    $O = 'غیرفعال است ❌';
    if($SAT[17] == 3  || $SAT[17] == 4){
        $O = 'فعال است ✅';
        $DO = 'OFF';
    }
    $O1 = 'قفل است ❌';
    if($SAT[1] == 1){
        $O1 = 'ازاد است ✅';
        $DO1 = 'OFF';
    }
    $O2 = 'قفل است ❌';
    if($SAT[12] == 1){
        $O2 = 'ازاد است ✅';
        $DO2 = 'OFF';
    }
    $O3 = 'قفل است ❌';
    if($SAT[2] == 1){
        $O3 = 'ازاد است ✅';
        $DO3 = 'OFF';
    }
    $O4 = 'غیرفعال است ❌';
    if($Tod[13] == 1){
        $O4 = 'فعال است ✅';
        $DO4 = 'OFF';
    }
    $O5 = 'غیرفعال است ❌';
    if($SAT[14] == 3  || $SAT[14] == 4){
        $O5 = 'فعال است ✅';
        $DO5 = 'OFF';
    }
    $TEXT = "⚠️ | ᒪOᑕKՏ"."\n";
    $TEXT .= COMD;
    $TEXT .= "/Welcome"."$DO"." လ "."خوشامدگویی"." $O "."\n\n";
    $TEXT .= "/link"."$DO1"." လ "."لینک"." $O1 "."\n\n";
    $TEXT .= "/forward"."$DO2"." လ "."پیام هدایت شده"." $O2 "."\n\n";
    $TEXT .= "/id"."$DO3"." လ "."ایدی"." $O3 "."\n\n";
    $TEXT .= "/block"."$DO4"." လ "."بلاک کردن"." $O4 "."\n\n";
    $TEXT .= "/wmAd"."$DO5"." လ "."خوشامدگویی به ادمینا"." $O5 "."\n\n";
    $TEXT .= "/BACKPanel"." ⌫ "."بازگشت";
    SendMessage($robot,$lib_LR,"$TEXT",$message_id);
}
function MGGroupDefaultAccess($robot,$Key,$action){
    $allAccess = getGroupDefaultAccess($robot);
    $access_list = $allAccess['access_list'];
    if($action == 1){
        $access_list[] = $Key;
    }else{
        $m = 0;
        foreach($access_list as $access){
            if($access == $Key){
                unset($access_list[$m]);
            }
            $m++;
        }
    }
    setGroupDefaultAccess($robot,$access_list);
}
function speed_panel($robot,$message_id,$lib_LR){
    $TEXT = "⏰ | Տᑭᗴᗴᗪ"."\n";
    $TEXT .= COMD;
    $TEXT .= "/Set0s"." လ "."یک پیام در 0 ثانیه"."\n\n";
    $TEXT .= "/Set1s"." လ "."یک پیام در 1 ثانیه"."\n\n";
    $TEXT .= "/Set2s"." လ "."یک پیام در 2 ثانیه"."\n\n";
    $TEXT .= "/Set3s"." လ "."یک پیام در 3 ثانیه"."\n\n";
    $TEXT .= "/Set4s"." လ "."یک پیام در 4 ثانیه"."\n\n";
    $TEXT .= "/Set5s"." လ "."یک پیام در 5 ثانیه"."\n\n";
    $TEXT .= "/BACKPanel"." ⌫ "."بازگشت";
    SendMessage($robot,$lib_LR,"$TEXT",$message_id);
}
function setting_ENDTIME($robot,$lib){
    $End_time = $lib->selectEndTime(AUTH);
    $endtime = $End_time['EndTime'];
    $endtime = intval($endtime);
    $result = $endtime-time();
    $dateEnd =  date_all($endtime);
    $hours = $result/3600/24;
    $hoursX = $result%(3600*24);
    $hs = $hoursX/3600;
    $minn = $hoursX%3600;
    $minn = $minn/60;
    $hours = floor($hours);
    $hs = floor($hs);
    $minn = floor($minn);
    $TXT = title("اشتراک خریداری شده");
    $TXT .= mini("تاریخ سررسید")." : "."$dateEnd"."\n\n";
    $TXT .= mini("$hours"." "." روز و "."$hs"." ساعت و "."$minn"." دقیقه باقی مانده است. ");
    $robot->sendMessage_reply(AOWNER,"$TXT",NULL);
}
function Status_all($lib,$lib_LR,$robot,$lib_BN){
        $info_LR = $lib_LR->select_Guid(GUID_U);
        $Today_LR = $info_LR['All'];
        $Tod = explode("-",$Today_LR);
        $ALL = 0;
        foreach($Tod as $T){
            $T = intval($T);
            $ALL = $ALL+$T;
        }

        $count_Tod = count($Tod);
        $count_Tod--;
        $names = ['متن','لینک','ایدی','منشن','نظرسنجی','استیکر','فایل','گیف','ویس','تصویر','اهنگ','ویدیو','پیام هدایت شده','گفتگو صوتی','کاربر افزوده شده','کاریر حذف شده','سنجاق پیام','عضو شده با لینک','ترک گروه','پست روبینو','استوری روبینو','لایو'];
        
        // negative
        $Dlink = $Tod[1];
        $Did = $Tod[2];
        $DforWard = $Tod[12];

        $step1 = $Dlink*3;
        $step2 = $Did*2;
        $step3 = $DforWard*5;

        $steps = $step1+$step2+$step3;
        $steps = $steps/20;
        $negatives = round($steps);

        // posetive
        $Dtext = $Tod[0];
        $Dgif = $Tod[7];
        $Dvoice = $Tod[8];
        $Dpictur = $Tod[9];
        $Dmusice = $Tod[10];
        $Dvideo = $Tod[11];
        $DvoiceCall = $Tod[13];
        $DpostRubino = $Tod[19];
        $DstoryRubino = $Tod[20];

        $step1 = $Dvoice*6;
        $step2 = $DvoiceCall*10;
        $step3 = $Dmusice*4;
        $step4 = $Dpictur*3;
        $step5 = $Dvideo*4;
        $step6 = $DpostRubino*5;
        $step7 = $DstoryRubino*5;
        $step8 = $Dtext*1;
        $step9 = $Dgif*2;

        $steps = $step1+$step2+$step3+$step4+$step5+$step6+$step7+$step8+$step9;
        $steps = $steps/40;
        $posetivs = round($steps);
        if($posetivs == 0){
            $percent = 1;
        }else{
            $percent = (($posetivs-$negatives)/$posetivs)*100;
        }
        $percent = round($percent);
        if($percent >= 90){
            $status = 'عالی.';
        }else if($percent >= 80){
            $status = 'خیلی خوب.';
        }else if($percent >= 70){
            $status = 'خوب.';
        }else if($percent >= 50){
            $status = 'بد نیست.';
        }else if($percent >= 30){
            $status = 'ضعیف.';
        }else if($percent >= 20){
            $status = 'خیلی ضعیف.';
        }
        
        $infoGap = Get_GroupInfo($robot);

        if($infoGap){
            $Group = $infoGap['data']['group'];
            $count_member = $Group['count_members'];
            $states = $lib_BN->select_allState(GUID_U);
            $Max =  $lib_LR->selectMax_GUID(GUID_U);
            $Max = $Max['Max'];
            $Bans = 0;
            foreach($states as $state){
                if($Max <= $state){
                    $Bans++;
                }
            }
            
            $ALLMEMBER = SORTCH($lib_BN,$lib);
            $COUNT = count($ALLMEMBER);
            $COUNT--;
            $Activs = 0;
            for($i = 0;$i < $COUNT;$i++){
                if(!isset($ALLMEMBER[$i])){
                    break;
                }
                $report = $ALLMEMBER[$i]['report'];
                if($report >= 50){
                    $Activs++;
                }
            }

        }else{
            return false;
        }
        
        
        $TXT = title('وضعیت گپ تا این لحظه');
        $day = dayp(time());
        $date = date_time(time());
        $TXT .= mini("$day")." ، $date"."\n";
        $TXT .= mini("ساعت")." : ".clock(time())."\n";
        $TXT .= mini("کل پیام ها")." : ".$ALL."\n\n";
        $TXT .= mini('افراد اخراج شده :')."$Bans"."\n";
        $TXT .= mini('افراد ثبت شده :')."$COUNT"."\n\n";
        $TXT .= mini("وضعیت")." : "." $status "."\n\n";
        $TXT .= shaps($percent)."\n";
        $TXT .= mini('اعضای فعال :')."$Activs"." از "." $count_member"."\n\n";
        $percent = (100*$Activs)/$count_member;
        $percent = round($percent);
        $TXT .= shaps($percent);
        $TXT = end_form($TXT);
        return $TXT;
}
function shaps($percent){
    $p = "█";
    $n = "▒";
    $flex = 100-$percent;
    $pc = 0;
    $nc = 10;
    $shap = '';
    for($i = 10;$i<=$percent;$i = $i+10){
        $pc++;
        $nc--;
    }
    for($i = $pc;$i > 0;$i--){
        $shap = $shap.$p;
    }
    for($i = $nc;$i > 0;$i--){
        $shap = $shap.$n;
    }
    $TXT = $shap." "."%".$percent."\n";
    return $TXT;
}
function Get_GroupInfo($robot){

    $LIMIT = 12;
    while(true){

        if($LIMIT < 1){
            return false;
        }
        usleep(200000);

        $infoGap = $robot->getGroupInfo(GUID_U);
        if(is_null($infoGap)){
            $LIMIT--;
            continue;
        }
        if(isset($infoGap['status']) && $infoGap['status'] === 'OK'){
            // $infogroup  = $infoGap['data']['group'];
            return $infoGap;
        }else{
            $LIMIT--;
            continue;
        }
    }
}
function DLMS($robot,$message_id,$Is_admin){
    if(!$Is_admin){
        DELETEXX($robot,["$message_id"]);
        return 'skip';
    }else{
        return true;
    }
}
function Reply_Message($Message,$robot){
    if(isset($Message['reply_to_message_id'])){
        $LIMIT = 12;
        while(true){
            if($LIMIT < 1){
                return false;
            }
            usleep(200000);
            $Reply_text_id = $Message['reply_to_message_id'];
            $reply_ms_info = $robot->getMessagesInfo(GUID_U,["$Reply_text_id"]);
            if(!is_null($reply_ms_info)){
                if(isset($reply_ms_info['status']) && $reply_ms_info['status'] === 'OK'){
                    if(isset($reply_ms_info['data']['messages'][0])){
                        $replyMessage = $reply_ms_info['data']['messages'][0];
                        break;
                     }
                }
            }
            $LIMIT--;
        }
    }else{
        return 'skip';
    }
    return $replyMessage;

}
function Reply_MessageX($Message,$robot,$guid_u){
    if(isset($Message['reply_to_message_id'])){
        $LIMIT = 12;
        while(true){
            if($LIMIT < 1){
                return false;
            }
            usleep(200000);
            $Reply_text_id = $Message['reply_to_message_id'];
            $reply_ms_info = $robot->getMessagesInfo($guid_u,["$Reply_text_id"]);
            if(!is_null($reply_ms_info)){
                if(isset($reply_ms_info['status']) && $reply_ms_info['status'] === 'OK'){
                    if(isset($reply_ms_info['data']['messages'][0])){
                        $replyMessage = $reply_ms_info['data']['messages'][0];
                        break;
                     }
                }
            }
            $LIMIT--;
        }
    }else{
        return 'skip';
    }
    return $replyMessage;

}
function get_guidUser($replyMessage){
    $Reply_guid_message = null;
    if(!isset($replyMessage['author_object_guid'])){
        if(isset($replyMessage["event_data"])){
            $event = $replyMessage["event_data"];
            if(isset($event['performer_object'])){
                $info_object = $event['performer_object'];
                if(isset($info_object["object_guid"])){
                    $Reply_guid_message = $info_object["object_guid"];
                }
            }
        }
    }else{
        $Reply_guid_message = $replyMessage['author_object_guid'];
    }
    if(!is_null($Reply_guid_message)){
        return $Reply_guid_message;
    }else{
        return false;
    }
}
function GET_USER_BY_GUID($guid_message,$robot){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $ok = $robot->getUserInfo($guid_message);
        if(is_null($ok)){
            $LIMIT--;
            continue;
        }
        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        break;
    }
    $user = $ok['data']["user"];
    return $user;
}
function GET_USER_BY_ID($ID_USER,$robot){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $ok = $robot->getInfoByUsername($ID_USER);
        if(is_null($ok)){
            $LIMIT--;
            continue;
        }
        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        
        break;
    }
    if(!isset($ok['data']["user"])){
        return 'skip';
    }
    $user = $ok['data']["user"];
    return $user;
}
function CHALENCH($lib_BN){
    $ALL =  $lib_BN->select_allRepoart(GUID_U);
    $ok = array();
    foreach($ALL as $mi){
        if($mi > 50){
            $ok[] = $mi;
        }
    }
    return $ok;
}
function SORTCH($lib_BN,$lib){
    $ALL = CHALENCH($lib_BN);
    rsort($ALL);
    $ALLREADY = [];
    $cou = count($ALL);
    $cou--;
    $GUIDS = $lib->select_GUID();
    $i = 0;
    $d = 0;
    while($i <= $cou){
        $ZOM = $lib_BN->select_BY_report(GUID_U,$ALL[$i]);
        if($d >= 100){
            break;
        }
        foreach($ZOM as $ZOOM){
            $ve = true;
            foreach($GUIDS as $GUIDms){
                if($GUIDms == $ZOOM){
                    $ve = false;
                    break;
                }
            }
            if($ve){
                $ALLREADY[$d]['guid'] = $ZOOM;
                $ALLREADY[$d]['report'] = $ALL[$i];
                $d++;
            }
        }
        $i++;
    }
    return $ALLREADY;
}
function YOUR_STATUS($lib,$lib_BN,$lib_LR,$guid_message,$message_id,$ADMINS,$robot,$AFA){
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
    }else{
        $info = '';
        if(isset($result_B['info'])){
            $info = $result_B['info'];
            $info = utf8_decode($info);
        }
        $Max = '';
        if(isset($result_B['Max'])){
            $Max = $result_B['Max'];
        }
        $ALLMSR = '';
        if($guid_message == GUID){
            $ALLMSR = 'NONE';
        }else if(isset($result_B['report'])){
            $ALLMSR = $result_B['report'];
        }
        $state = '';
        if(isset($result_B['state'])){
            $state = $result_B['state'];
        }
        $name = '';
        if(isset($result_B['name'])){
            $name = $result_B['name'];
            $name = utf8_decode($name);
        }
        $is_admin = '';
        if(AOWNER == $guid_message){
            $is_admin = 'مالک';
        }else{
            $is_admin = 'کاربر عادی';
            $cou = count($ADMINS);
            $cou--;
            for($i = $cou;$i>=0;$i--){
                if($ADMINS[$i] == $guid_message){
                    $is_admin = 'ادمین';
                }
            }
            $cou = count($AFA);
            $cou--;
            for($i = $cou;$i>=0;$i--){
                if($AFA[$i] == $guid_message){
                    $is_admin = 'کاربر ویژه';
                }
            }
        }
        $user = GET_USER_BY_GUID($guid_message,$robot);
        if(!$user){
            return false;
        }
        $ID = '';
        $bio = '';
        if(!isset($user['last_name'])){
            $user['last_name'] = '';
        }
        $FLName = $user['first_name']." ".$user['last_name'];
        if(isset($user['username'])){
            $ID = $user['username'];
        }
        if(isset($user['bio'])){
            $bio = $user['bio'];
        }
        $ALLMEMBER = SORTCH($lib_BN,$lib);
        $COUNT = count($ALLMEMBER);
        $COUNT--;
        $TXTR = "\n\n";
        $Lname = null;
        $max= 3;
        for($i = 0;$i < $max;$i++){
            if(!isset($ALLMEMBER[$i])){
                break;
            }
            $guid = $ALLMEMBER[$i]['guid'];
            $report = $ALLMEMBER[$i]['report'];
            $result_d = $lib_BN->select(GUID_U,$guid);
            $namex = $result_d['name'];
            $namex = utf8_decode($namex);
            if(is_null($Lname)){
                $Lname = $namex;
                $TXTR .= ($i+1)."- کاربر ".$namex." با ".$report." پیام\n";
            }else{
                if($namex == $Lname){
                    $max = $max+1;
                    continue;
                }
                $TXTR .= ($i+1)."- کاربر ".$namex." با ".$report." پیام\n";
                $Lname = $namex;
            }
        }
        $YOURMG = null;
        if($guid_message == GUID){
            $YOURMG = 'NONE';
        }else{
            for($i = 0;$i <= $COUNT;$i++){
                $guid = $ALLMEMBER[$i]['guid'];
                if($guid == $guid_message){
                    $YOURMG = $i+1;
                    break;
                }
            }
        }
        if(is_null($YOURMG)){
            $YOURMG = 'NONE';
        }
        
        $TXT = title('آمار کلی شخص');
        $TXT .= mini("اسم : ")."$FLName\n";
        $TXT .= mini("ایدی : ")."@$ID\n";
        $TXT .= mini("اصل : ")."$info\n";
        $TXT .= mini("لقب : ")."$name\n";
        $TXT .= mini("اخطار : ")."$state"." از ".$Max."\n";
        $TXT .= mini("مقام : ")."$is_admin\n";
        $TXT .= mini("گوید : ")."$guid_message\n";
        $TXT .= mini("رتبه در گپ : ")."$YOURMG\n";
        $TXT .= mini("تعداد پیام : ")."$ALLMSR\n\n";
        $TXT .= mini("افراد برتر : ")."$TXTR\n";
        $TXT .= "______\n\n";
        $TXT .= "$bio";
        $TXT = end_form($TXT);
        SendMessage($robot,$lib_LR,$TXT,$message_id);
        return true;
    }
}
function SendMessage($robot,$lib_LR,$TEXT,$message_id){
        $LIMIT = 12;
        $Tod = Tod($lib_LR);
        if($Tod[7] == 2){
            return false;
        }
        while(true){
            if($LIMIT < 1){
                return false;
            }
            usleep(200000);
            $ok = $robot->sendMessage_reply(GUID_U,"$TEXT",$message_id);
            if(is_null($ok)){
                $LIMIT--;
                continue;
            }
            if(!isset($ok['status']) || $ok['status'] !== 'OK'){
                $LIMIT--;
                continue;
            }
            break;
        }
        MSspeak($ok,$lib_LR);
        return $ok;
}
function SendMessageZ($robot,$lib_LR,$TEXT,$message_id){
        $LIMIT = 12;
        while(true){
            if($LIMIT < 1){
                return false;
            }
            usleep(200000);
            $ok = $robot->sendMessage_reply(GUID_U,"$TEXT",$message_id);
            if(is_null($ok)){
                $LIMIT--;
                continue;
            }
            if(!isset($ok['status']) || $ok['status'] !== 'OK'){
                $LIMIT--;
                continue;
            }
            break;
        }
        MSspeak($ok,$lib_LR);
        return $ok;
}
function SendMessageX($robot,$guid_message,$CODE,$message_id,$lib_LR){
        $CODE = intval($CODE);
        $TXTSA = $lib_LR->selectTXTS_GUID(GUID_U);
        $TXTS = $TXTSA['TXTS'];
        $Tod = explode("`",$TXTS);
        $TEXT = $Tod[$CODE];
        $TEXT = utf8_decode($TEXT);
        if($CODE == 2 || $CODE == 4){
            sendMessage_WELCOME($robot,$lib_LR,$guid_message,$message_id,$TEXT);
        }else{
            SendMessage($robot,$lib_LR,$TEXT,$message_id);
        }
}
function SAVEMSS($robot,$CODE,$TEXT,$message_id,$ALARM,$lib_LR){
        $CODE = intval($CODE);
        $TEXT = str_replace('`','',$TEXT);
        $TEXT = utf8_encode($TEXT);
        $TXTSA = $lib_LR->selectTXTS_GUID(GUID_U);
        if(is_null($TXTSA['TXTS']) || empty($TXTSA['TXTS']) || !isset($TXTSA['TXTS'])){
            $qavanin = "قوانین برای این گروه نوشته نشده است.";
            $remember = "هنوز نوشته ای برای یادآوری ذخیره نشده است";
            $add = "خیلییی خوش اومدی عزیز دلم😍😍🥹";
            $remove = "حقش بود ریم بشه 😂😐";
            $join ="خیلییی خوش اومدی عزیز دلم😍😍🥹";
            $leave = "😐از سایه برو پوستت اوف نشه";
            $addpv = "ادمین شدی خشگلم";
            $qavanin = utf8_encode($qavanin);
            $remember = utf8_encode($remember);
            $add = utf8_encode($add);
            $remove = utf8_encode($remove);
            $join = utf8_encode($join);
            $leave = utf8_encode($leave);
            $addpv = utf8_encode($addpv);
            $TXTS= $qavanin.'`'.$remember.'`'.$add.'`'.$remove.'`'.$join.'`'.$leave.'`1`1`1`1`1`0`2`2`'.$addpv.'`1';
            $result = $lib_LR->updateTXTS_GUiD(GUID_U,$TXTS);
        }else{
            $TXTS = $TXTSA['TXTS'];
        }
        $Tod = explode("`",$TXTS);
        if(!isset($Tod[13])){
            $Tod[13] = 2;
        }
        if(!isset($Tod[14])){
            $text = "اد شدی عزیزم";
            $text = utf8_encode($text);
            $Tod[14] = $text;
        }
        if(!isset($Tod[15])){
            $Tod[15] = 1;
        }
        if(!isset($Tod[16])){
            $Tod[16] = 1;
        }
        if(!isset($Tod[17])){
            $Tod[17] = 1;
        }
        if(!isset($Tod[18])){
            $Tod[18] = 1;
        }
        if(!isset($Tod[19])){
            $Tod[19] = 1;
        }
        if(!isset($Tod[20])){
            $Tod[20] = 1;
        }
        $Tod[$CODE] = $TEXT;
        $TEXTX =  $Tod[0].'`'.$Tod[1].'`'.$Tod[2].'`'.$Tod[3].'`'.$Tod[4].'`'.$Tod[5].'`'.$Tod[6].'`'.$Tod[7].'`'.$Tod[8].'`'.$Tod[9].'`'.$Tod[10].'`'.$Tod[11].'`'.$Tod[12].'`'.$Tod[13].'`'.$Tod[14].'`'.$Tod[15].'`'.$Tod[16].'`'.$Tod[17].'`'.$Tod[18].'`'.$Tod[19].'`'.$Tod[20];
        $lib_LR->updateTXTS_GUiD(GUID_U,$TEXTX);
        if(!is_null($ALARM)){
            $ALARM = orders($ALARM);
            SendMessage($robot,$lib_LR,$ALARM,$message_id);
        }
}
function manange_setting($lib_LR,$robot,$type,$action,$message_id){
    
    $names = ['متن','لینک','ایدی','منشن','نظرسنجی','استیکر','فایل','گیف','ویس','تصویر','اهنگ','ویدیو','پیام هدایت شده','گفتگو صوتی','پیام کاربر افزوده شده','پیام کاربر حذف شده','سنجاق پیام','پیام پیوستن','پیام ترک کردن','پست روبینو','استوری روبینو','لایو'];
    // $namesX = ['افزودن کاربر','حذف کاربر','پیوستن','ترک کردن'];
    $st = $lib_LR->selectSetting_GUID(GUID_U);
    $Setting = $st['Setting'];
    $SAT = explode("-",$Setting);
    $NX = false;

    if($type == 34){
        if($action == 1){
            if($SAT[14] == 1){
                $action = 1;
            }else if($SAT[14] == 2){
                $action = 2;
            }else if($SAT[14] == 3){
                $action = 2;
            }else if($SAT[14] == 4){
                $action = 1;
            }
        }else if($action == 4){
            if($SAT[14] == 1){
                $action = 4;
            }else if($SAT[14] == 2){
                $action = 3;
            }else if($SAT[14] == 3){
                $action = 3;
            }else if($SAT[14] == 4){
                $action = 4;
            }
        }
        $type = 14;
        $NX  = true;
    }else if($type == 35){
        if($action == 1){
            if($SAT[15] == 1){
                $action = 1;
            }else if($SAT[15] == 2){
                $action = 2;
            }else if($SAT[15] == 3){
                $action = 2;
            }else if($SAT[15] == 4){
                $action = 1;
            }
        }else if($action == 4){
            if($SAT[15] == 1){
                $action = 4;
            }else if($SAT[15] == 2){
                $action = 3;
            }else if($SAT[15] == 3){
                $action = 3;
            }else if($SAT[15] == 4){
                $action = 4;
            }
        }
        $type = 15;
        $NX  = true;
    }else if($type == 37){
        if($action == 1){
            if($SAT[17] == 1){
                $action = 1;
            }else if($SAT[17] == 2){
                $action = 2;
            }else if($SAT[17] == 3){
                $action = 2;
            }else if($SAT[17] == 4){
                $action = 1;
            }
        }else if($action == 4){
            if($SAT[17] == 1){
                $action = 4;
            }else if($SAT[17] == 2){
                $action = 3;
            }else if($SAT[17] == 3){
                $action = 3;
            }else if($SAT[17] == 4){
                $action = 4;
            }
        }
        $type = 17;
        $NX  = true;
    }else if($type == 38){
        if($action == 1){
            if($SAT[18] == 1){
                $action = 1;
            }else if($SAT[18] == 2){
                $action = 2;
            }else if($SAT[18] == 3){
                $action = 2;
            }else if($SAT[18] == 4){
                $action = 1;
            }
        }else if($action == 4){
            if($SAT[18] == 1){
                $action = 4;
            }else if($SAT[18] == 2){
                $action = 3;
            }else if($SAT[18] == 3){
                $action = 3;
            }else if($SAT[18] == 4){
                $action = 4;
            }
        }
        $type = 18;
        $NX  = true;
    }else if($type == 14){
        if($action == 1){
            if($SAT[14] == 1){
                $action = 1;
            }else if($SAT[14] == 2){
                $action = 1;
            }else if($SAT[14] == 3){
                $action = 4;
            }else if($SAT[14] == 4){
                $action = 4;
            }
        }else if($action == 2){
            if($SAT[14] == 1){
                $action = 2;
            }else if($SAT[14] == 2){
                $action = 2;
            }else if($SAT[14] == 3){
                $action = 3;
            }else if($SAT[14] == 4){
                $action = 3;
            }
        }
        $NX  = true;
    }else if($type == 15){
        if($action == 1){
            if($SAT[15] == 1){
                $action = 1;
            }else if($SAT[15] == 2){
                $action = 1;
            }else if($SAT[15] == 3){
                $action = 4;
            }else if($SAT[15] == 4){
                $action = 4;
            }
        }else if($action == 2){
            if($SAT[15] == 1){
                $action = 2;
            }else if($SAT[15] == 2){
                $action = 2;
            }else if($SAT[15] == 3){
                $action = 3;
            }else if($SAT[15] == 4){
                $action = 3;
            }
        }
        $NX  = true;
    }else if($type == 17){
        if($action == 1){
            if($SAT[17] == 1){
                $action = 1;
            }else if($SAT[17] == 2){
                $action = 1;
            }else if($SAT[17] == 3){
                $action = 4;
            }else if($SAT[17] == 4){
                $action = 4;
            }
        }else if($action == 2){
            if($SAT[17] == 1){
                $action = 2;
            }else if($SAT[17] == 2){
                $action = 2;
            }else if($SAT[17] == 3){
                $action = 3;
            }else if($SAT[17] == 4){
                $action = 3;
            }
        }
        $NX  = true;
    }else if($type == 18){
        if($action == 1){
            if($SAT[18] == 1){
                $action = 1;
            }else if($SAT[18] == 2){
                $action = 1;
            }else if($SAT[18] == 3){
                $action = 4;
            }else if($SAT[18] == 4){
                $action = 4;
            }
        }else if($action == 2){
            if($SAT[18] == 1){
                $action = 2;
            }else if($SAT[18] == 2){
                $action = 2;
            }else if($SAT[18] == 3){
                $action = 3;
            }else if($SAT[18] == 4){
                $action = 3;
            }
        }
        $NX  = true;
    }
    
    $TY = $names[$type];

    $SAT[$type] = $action;
    $Setting = $SAT['0'].'-'.$SAT['1'].'-'.$SAT['2'].'-'.$SAT['3'].'-'.$SAT['4'].'-'.$SAT['5'].'-'.$SAT['6'].'-'.$SAT['7'].'-'.$SAT['8'].'-'.$SAT['9'].'-'.$SAT['10'].'-'.$SAT['11'].'-'.$SAT['12'].'-'.$SAT['13'].'-'.$SAT['14'].'-'.$SAT['15'].'-'.$SAT['16'].'-'.$SAT['17'].'-'.$SAT['18'].'-'.$SAT['19'].'-'.$SAT['20'].'-'.$SAT['21'];
    $lib_LR->updateSetting_GUiD(GUID_U,$Setting);

    if($NX){
        $state = 'ازاد '.'و'.' '.'پاسخ بهش'.' '.'قفل شد ❌';
        if($action == 2){
            $state = 'قفل '.'و'.' '.'پاسخ بهش'.' '.'قفل شد ❌';
        }else if($action == 3){
            $state = 'قفل '.'و'.' '.'پاسخ بهش'.' '.'ازاد شد ✅';
        }else if($action == 4){
            $state = 'ازاد '.'و'.' '.'پاسخ بهش'.' '.'ازاد شد ✅';
        }
    }else{
        $state = 'ازاد '.'شد ✅';
        if($action == 2){
            $state = 'قفل '.'شد ❌';
        }
    }
    if(!is_null($message_id)){
        $TEXT = $TY.' '.$state;
        $TEXT = orders($TEXT);
        SendMessage($robot,$lib_LR,$TEXT,$message_id);
    }
}
function DELETEXX($robot,$ARRAY){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $ok = $robot->deleteMessages(GUID_U,$ARRAY);
        if(is_null($ok)){
            $LIMIT--;
            continue;
        }
        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        
        break;
    }
    return true;
}
function sendMessage_MNTION($robot,$lib_LR,$guid_message,$message_id,$WAR,$SW){
    $LIMIT = 12;    
    $Tod = Tod($lib_LR);
    if($Tod[7] == 2){
        return false;
    }
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $LOOK = "!! TAKE CARE !!";
        $length = strlen($LOOK);
        $ok = $robot->sendMessage_mentionX(GUID_U,$guid_message,"$LOOK\n$WAR\n$SW",$message_id,$length);
        if(is_null($ok)){
            $LIMIT--;
            continue;
        }
        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        break;
    }
    MSspeak($ok,$lib_LR);
    return $ok;
}
function FORCONTROL($robot,$note){
    $LIMIT = 12;    
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $LOOK = 'MY OWNER';
        $length = strlen($LOOK);
        // $ok = $robot->sendMessage_mentionX(GUID_OMG,AOWNER,"$LOOK\n$note",NULL,$length);
        $ok = $robot->sendMessage_mentionX("u0Cpj5A0870feb0fe19501ff946bdb04",AOWNER,"$LOOK\n$note",NULL,$length);
        if(is_null($ok)){
            $LIMIT--;
            continue;
        }
        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        break;
    }
    return $ok;
}
function sendMessage_WELCOME($robot,$lib_LR,$guid_message,$message_id,$TEXT){
    $LIMIT = 12;    
    $Tod = Tod($lib_LR);
    if($Tod[7] == 2){
        return false;
    }
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $d = time();
        $date = date("Y-m-d H:i:sa", $d);
        $wel = "♡ WELCOME ♡";
        $length = strlen($wel);
        $length = $length-4;
        $ok = $robot->sendMessage_mentionX(GUID_U,$guid_message,"$wel\nDate : $date\n\n$TEXT",$message_id,$length);
        if(is_null($ok)){
            $LIMIT--;
            continue;
        }
        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        break;
    }
    MSspeak($ok,$lib_LR);
    return $ok;
}
function unBanUser($robot,$guid_message){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $ok = $robot->unBanGroupMember(GUID_U,$guid_message);
        if(is_null($ok)){
            $LIMIT--;
            continue;
        }
        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        
        break;
    }
    return true;
}
function BanUser($robot,$guid_message){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $ok = $robot->banGroupMember(GUID_U,$guid_message);
        if(is_null($ok)){
            $LIMIT--;
            continue;
        }
        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        
        break;
    }
    return $ok;
}
function sendMessage_pro($robot,$lib_LR,$Guid_user,$message_id,$text){
    $LIMIT = 12; 
    $Tod = Tod($lib_LR);
    if($Tod[7] == 2){
        return false;
    }
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $ok = $robot->sendMessage_mentionPro(GUID_U,$Guid_user,$message_id,$text);
        if(is_null($ok)){
            $LIMIT--;
            continue;
        }
        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        break;
    }
    MSspeak($ok,$lib_LR);
    return true;
}
function GetLink($robot){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $ok = $robot->getGroupLink(GUID_U);
        if(is_null($ok)){
            $LIMIT--;
            continue;
        }
        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        break;
    }
    return $ok;
}
function unsetAdmin($robot,$guid_message){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $ok = $robot->UnsetGroupAdmin(GUID_U,$guid_message);
        if(is_null($ok)){
            $LIMIT--;
            continue;
        }
        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        break;
    }
    return true;
}
function setAdmin($robot,$guid_message){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        // $ok = $robot->setGroupAdmin(GUID_U,["DeleteGlobalAllMessages"],$guid_message);
        $ok = $robot->setGroupAdmin(GUID_U,[],$guid_message);
        if(is_null($ok)){
            $LIMIT--;
            continue;
        }
        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        break;
    }
    return true;
}
function setFAdmin($robot,$guid_message){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $ok = $robot->setGroupAdmin(GUID_U,[],$guid_message);
        if(is_null($ok)){
            $LIMIT--;
            continue;
        }
        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        break;
    }
    return true;
}
function pin($robot,$guid_message){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $ok = $robot->setPinMessage(GUID_U,$guid_message);
        if(is_null($ok)){
            $LIMIT--;
            continue;
        }
        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        break;
    }
    return true;
}
function voice_call($robot){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $ok = $robot->createGroupVoiceChat(GUID_U);
        if(is_null($ok)){
            $LIMIT--;
            continue;
        }
        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        break;
    }
    return $ok;
}
function set_timer($robot,$number){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $ok = $robot->setGroupTimer(GUID_U,$number);
        if(is_null($ok)){
            $LIMIT--;
            continue;
        }
        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        break;
    }
    return true;
}
function add_user($robot,$user_guid){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $ok = $robot->addGroupMembers($user_guid,GUID_U);
        if(is_null($ok)){
            $LIMIT--;
            continue;
        }
        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        break;
    }
    return true;
}
function setLink($robot){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $ok = $robot->setGroupLink(GUID_U);
        if(is_null($ok)){
            $LIMIT--;
            continue;
        }
        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        break;
    }
    return true;
}
function block_user($robot,$user_guid){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $ok = $robot->BlockUser($user_guid);
        if(is_null($ok)){
            $LIMIT--;
            continue;
        }
        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        break;
    }
    return true;
}
function unblock_user($robot,$user_guid){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $ok = $robot->UnBlockUser($user_guid);
        if(is_null($ok)){
            $LIMIT--;
            continue;
        }
        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        break;
    }
    return true;
}
function limits($lib_LR,$robot,$message_id){
    
    $names = ['متن','لینک','ایدی','منشن','نظرسنجی','استیکر','فایل','گیف','ویس','تصویر','اهنگ','ویدیو','پیام هدایت شده','گفتگو صوتی','پیام کاربر افزوده شده','پیام کاربر حذف شده','سنجاق پیام','پیام پیوستن','پیام ترک کردن','پست روبینو','استوری روبینو','لایو'];
    // $namesX = ['افزودن کاربر','حذف کاربر','پیوستن','ترک کردن'];
    $st = $lib_LR->selectSetting_GUID(GUID_U);
    $Setting = $st['Setting'];
    $SAT = explode("-",$Setting);
    $TEXT = title('محدودیت های گروه');
    $con = 0 ;
    foreach($SAT as $S){
        $S = intval($S);
        $bool = "❖";
        if($S == 1){
            $bool = " "."✅";
        }else if($S == 2){
            $bool = " "."❌";
        }else if($S == 3){
            $bool = " "."❌";
        }else if($S == 4){
            $bool = " "."✅";
        }
        $name = $names[$con];
        $TEXT .= mini($name)." : ".$bool."\n";
        $con++;
    }
    $TEXT = end_form($TEXT);
    SendMessage($robot,$lib_LR,$TEXT,$message_id);
}
function Tod($lib_LR){
    $TXTSA = $lib_LR->selectTXTS_GUID(GUID_U);
    $TXTS = $TXTSA['TXTS'];
    $Tod = explode("`",$TXTS);
    return $Tod;
}
function TodX($lib_LR,$GUID_U){
    $TXTSA = $lib_LR->selectTXTS_GUID($GUID_U);
    $TXTS = $TXTSA['TXTS'];
    $Tod = explode("`",$TXTS);
    return $Tod;
}
function MSspeak($result,$lib_LR){
    $ME_ms_id = $result['data']['message_update']['message_id'];
    $allMSG = $lib_LR->selectAMS_GUID(GUID_U);
    $MSG = $allMSG['AMS'];
    $MS = explode("-",$MSG);
    $AMS = $MS[1].'-'.$MS[2].'-'.$MS[3].'-'.$MS[4].'-'.$MS[5].'-'.$MS[6].'-'.$MS[7].'-'.$MS[8].'-'.$MS[9].'-'.$MS[10].'-'.$MS[11].'-'.$MS[12].'-'.$MS[13].'-'.$MS[14].'-'.$MS[15].'-'.$MS[16].'-'.$MS[17].'-'.$MS[18].'-'.$MS[19].'-'.$MS[20].'-'.$MS[21].'-'.$MS[22].'-'.$MS[23].'-'.$MS[24].'-'.$MS[25].'-'.$MS[26].'-'.$MS[27].'-'.$MS[28].'-'.$MS[29].'-'.$MS[30].'-'.$MS[31].'-'.$MS[32].'-'.$MS[33].'-'.$MS[34].'-'.$MS[35].'-'.$MS[36].'-'.$MS[37].'-'.$MS[38].'-'.$MS[39].'-'.$MS[40].'-'.$MS[41].'-'.$MS[42].'-'.$MS[43].'-'.$MS[44].'-'.$MS[45].'-'.$MS[46].'-'.$MS[47].'-'.$MS[48].'-'.$MS[49].'-'.$ME_ms_id;
    $lib_LR->updateAMSLMS_GUiD(GUID_U,$AMS,$ME_ms_id);
}
function SendMessageSpeak($robot,$lib_LR,$TEXT,$message_id){
        $Tod = Tod($lib_LR);
        if($Tod[7] == 2){
            return false;
        } 
        if($Tod[6] == 2){
            return false;
        }
        $emojis = ['😂','😘',' ','😁','😍','🙂','😔',' ','😊','😄','😃','😀',' ','😂','😅','🙄','😑','🥲','😿',' ','🌝',' ','🌚','💩','🍫','🍬',' ','🍭','🫤','☹️','😔','🙃',' ','🚶🏼‍♀️','🚶🏼‍♀️🚶🏼‍♀️','🗿','🗿🗿','😶',' ','😙','🥹','😌','😊',' '];
        $cont = count($emojis);
        $cont--;
        $chose = rand(0,$cont);
        $emoji = $emojis[$chose];
        $LIMIT = 12;
        while(true){
            if($LIMIT < 1){
                return false;
            }
            usleep(200000);
            $ok = $robot->sendMessage_reply(GUID_U,"$TEXT $emoji",$message_id);
            if(is_null($ok)){
                $LIMIT--;
                continue;
            }
            if(!isset($ok['status']) || $ok['status'] !== 'OK'){
                $LIMIT--;
                continue;
            }
            break;
        }
        MSspeak($ok,$lib_LR);
        return $ok;
}
function SendMessageSpeakSelf($robot,$GGUID_U,$lib_LR,$TEXT,$message_id){
        $Tod = Tod($lib_LR);
        if($Tod[7] == 2){
            return false;
        }
        if($Tod[6] == 2){
            return false;
        }
        $emojis = ['😂','😘',' ','😁','😍','🙂','😔',' ','😊','😄','😃','😀',' ','😂','😅','🙄','😑','🥲','😿',' ','🌝',' ','🌚','💩','🍫','🍬',' ','🍭','🫤','☹️','😔','🙃',' ','🚶🏼‍♀️','🚶🏼‍♀️🚶🏼‍♀️','🗿','🗿🗿','😶',' ','😙','🥹','😌','😊',' '];
        $cont = count($emojis);
        $cont--;
        $chose = rand(0,$cont);
        $emoji = $emojis[$chose];
        $LIMIT = 12;
        while(true){
            if($LIMIT < 1){
                return false;
            }
            usleep(200000);
            $ok = $robot->sendMessage_reply($GGUID_U,"$TEXT $emoji",$message_id);
            if(is_null($ok)){
                $LIMIT--;
                continue;
            }
            if(!isset($ok['status']) || $ok['status'] !== 'OK'){
                $LIMIT--;
                continue;
            }
            break;
        }
        MSspeak($ok,$lib_LR);
        return $ok;
}
function dashboard($lib_LR,$robot,$message_id){
    $TXTSA = $lib_LR->selectTXTS_GUID(GUID_U);
    $TXTS = $TXTSA['TXTS'];
    $Tod = explode("`",$TXTS);
    $TEXT = title('تمامی پیام های ثبت شده');
    $names = ['قوانین','یادآوری','پاسخ به پیام پیوستن','پاسخ به پیام کاربر حذف شده','پاسخ به پیام پیوستن','پاسخ به پیام ترک کردن'];
    $con = 0;
    foreach($Tod as $T){
        $T = utf8_decode($T);
        if(isset($names[$con])){
            $name = $names[$con];
            $name = $name;
            $TEXT .= mini($name)."\n\n".$T."\n\n";
            $con++;
        }else{
            break;
        }
    }
    $TEXT = end_form($TEXT);
    SendMessage($robot,$lib_LR,$TEXT,$message_id);
}
function state($lib_LR,$robot,$message_id){
    $TXTSA = $lib_LR->selectTXTS_GUID(GUID_U);
    $TXTS = $TXTSA['TXTS'];
    $Tod = explode("`",$TXTS);
    $TEXT = title('داشبورد');
    $O = 'خواب است ❌';
    $O1 = 'خاموش است ❌'; 
    $O2 = 'خاموش است ❌';
    if($Tod[7] == 1){
        $O = 'بیدار است ✅';
    } 
    if($Tod[6] == 1){
        $O1 = 'روشن است ✅';
    }    
    if($Tod[8] == 1){
        $O2 = 'روشن است ✅';
    }
    $TEXT .= mini("ربات : ").$O."\n\n";
    $TEXT .= mini("حالت سخنگو : ").$O1."\n\n";
    $TEXT .= mini("نمایش اخطار : ").$O2."\n\n";
    SendMessage($robot,$lib_LR,$TEXT,$message_id);
}
function sortByLength($a, $b) {
    return strlen($b) - strlen($a);
}
function ALLmembers($lib_BN,$lib,$step){
    $ALLMEMBER = SORTCH($lib_BN,$lib);
    $TXTR = title('فعالیت اعضای گروه تا به امروز');
    $COUNT = count($ALLMEMBER);
    $COUNT--;
    $d = 0;
    $max = 25;
    if($step == "Spage1"){
        $d = 0;
        $max = 25;
    }else if($step == "Spage2"){
        $d = 25;
        $max = 50;
    }else if($step == "Spage3"){
        $d = 50;
        $max = 75;
    }else if($step == "Spage4"){
        $d = 75;
        $max = 100;
    }
    $Lname = null;
    for($i = $d;$i < $max;$i++){
        if(!isset($ALLMEMBER[$i])){
            break;
        }
        $guid = $ALLMEMBER[$i]['guid'];
        $report = $ALLMEMBER[$i]['report'];
        $result_d = $lib_BN->select(GUID_U,$guid);
        $name = $result_d['name'];
        $name = utf8_decode($name);
        if(is_null($Lname)){
            $Lname = $name;
            $TXTR .= ($i+1)."- کاربر ".$name." با ".$report." پیام\n";
        }else{
            if($name == $Lname){
                continue;
            }
            $TXTR .= ($i+1)."- کاربر ".$name." با ".$report." پیام\n";
            $Lname = $name;
        }
    }
    if($max > 75){
        $TXTR .= COMD;
        $TXTR .= "/Spage3"." ⬅️ "."صفحه قبلی";
        $TXTR .= "\n\n";
    }else if($max > 50){
        $TXTR .= COMD;
        $TXTR .= "/Spage2"." ⬅️ "."صفحه قبلی";
        $TXTR .= "\n\n";
        $TXTR .= "/Spage4"." ➡️ "."صفحه بعدی";
    }else if($max > 25){
        $TXTR .= COMD;
        $TXTR .= "/Spage1"." ⬅️ "."صفحه قبلی";
        $TXTR .= "\n\n";
        $TXTR .= "/Spage3"." ➡️ "."صفحه بعدی";
    }else{
        $TXTR .= COMD;
        $TXTR .= "/Spage2"." ➡️ "."صفحه بعدی";
    }
    
    return $TXTR;
}
function join_groups($robot,$link){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $needle = "https://rubika.ir/joing/";
        $hash_link = str_replace($needle,'',$link);
        $Joined = $robot->joinGroup($hash_link);
        if(is_null($Joined)){
            $LIMIT--;
            continue;
        }
        if(!isset($Joined['status']) || $Joined['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        if(isset($Joined['data']['group'])){
            $group = $Joined['data']['group'];
            return $group;
        }else{
            return false;
        }
        
    }
}
function join_channel($robot,$link){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $needle = "https://rubika.ir/joinc/";
        $hash_link = str_replace($needle,'',$link);
        $Joined = $robot->joinChannel($hash_link);
        if(is_null($Joined)){
            $LIMIT--;
            continue;
        }
        if(!isset($Joined['status']) || $Joined['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        return true;
        
    }
}

function leeve_groups($robot,$group_guid){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $Joined = $robot->leaveGroup($group_guid);
        if(is_null($Joined)){
            $LIMIT--;
            continue;
        }
        if(isset($Joined['status']) || $Joined['status'] === 'OK'){
            return true;
        }else{
            $LIMIT--;
            continue;
        }
    }
}
function stop_voiceChat($robot,$message_id,$lib_LR){
            $voiceChat = voice_call($robot);
            if(isset($voiceChat['data']['exist_group_voice_chat'])){
                $exist_group_voice_chat = $voiceChat['data']['exist_group_voice_chat'];
                $voice_chat_id = $exist_group_voice_chat['voice_chat_id'];
                $LIMIT = 12;
                while(true){
                    if($LIMIT < 1){
                        return false;
                    }
                    usleep(200000);
                    $DisVoice = $robot->discardGroupVoiceChat(GUID_U,$voice_chat_id);
                    if(is_null($DisVoice)){
                        $LIMIT--;
                        continue;
                    }
                    if(isset($DisVoice['status']) || $DisVoice['status'] === 'OK'){
                        return $DisVoice;
                    }else{
                        $LIMIT--;
                        continue;
                    }
                }
            }else{
                SendMessage($robot,$lib_LR,"عشقم ویس چتی نیست که قطع کنم",$message_id);
                return false;
            }
}
function setGroupDefaultAccess($robot,$access_list){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        
        $result = $robot->setGroupDefaultAccess($access_list,GUID_U);
        if(is_null($result)){
            $LIMIT--;
            continue;
        }
        if(isset($result['status']) || $result['status'] === 'OK'){
            return true;
        }else{
            $LIMIT--;
            continue;
        }
    }
}
function getGroupDefaultAccess($robot){
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        
        $result = $robot->getGroupDefaultAccess(GUID_U);
        if(is_null($result)){
            $LIMIT--;
            continue;
        }
        if(isset($result['status']) || $result['status'] === 'OK'){
            return $result['data'];
        }else{
            $LIMIT--;
            continue;
        }
    }
}
function setFullAdmins($Reply_guid_message,$lib){
    // setFAdmin($robot,$Reply_guid_message);
    /// set full admin in database
    $AFAA = $lib->selectFullAdmins(AUTH);
    $AFAA = $AFAA['FullAdmins'];
    $AFAA = $AFAA.'-'.$Reply_guid_message;
    $lib->updateFullAdmins(AUTH,$AFAA);
}
function setAdmins($Reply_guid_message,$lib){
    // setAdmin($robot,$Reply_guid_message);
    /// set full admin in database
    $AFAA = $lib->selectAdmins(AUTH);
    $AFAA = $AFAA['Admins'];
    $AFAA = $AFAA.'-'.$Reply_guid_message;
    $lib->updateAdmins(AUTH,$AFAA);
}
function unsetFullAdmins($Reply_guid_message,$lib){
    $AFAA = $lib->selectFullAdmins(AUTH);
    $AFAA = $AFAA['FullAdmins'];
    $is_fa = pro_search($AFAA,$Reply_guid_message);
    if($is_fa){
        $AFAA = str_replace("-"."$Reply_guid_message","","$AFAA");
        $lib->updateFullAdmins(AUTH,$AFAA);
    }
}
function unsetAdmins($Reply_guid_message,$lib){
    $AFAA = $lib->selectAdmins(AUTH);
    $AFAA = $AFAA['Admins'];
    $is_fa = pro_search($AFAA,$Reply_guid_message);
    if($is_fa){
        $AFAA = str_replace("-"."$Reply_guid_message","","$AFAA");
        $lib->updateAdmins(AUTH,$AFAA);
    }
}
function isAdmin($guid_message,$lib){
    $AFAA = $lib->selectAdmins(AUTH);
    $AFAA = $AFAA['Admins'];
    $AFA = explode('-',$AFAA);
    $Is_admin = false;
    foreach($AFA as $FA){
        if($FA == $guid_message){
            $Is_admin = true;
            break;
        }
    }
    return $Is_admin;
}
function isFullAdmins($guid_message,$lib){
    $AFAA = $lib->selectFullAdmins(AUTH);
    $AFAA = $AFAA['FullAdmins'];
    $AFA = explode('-',$AFAA);
    $Is_fulladmin = false;
    foreach($AFA as $FA){
        if($FA == $guid_message){
            $Is_fulladmin = true;
            break;
        }
    }
    return $Is_fulladmin;
}
function isOwner($guid_message){
    $Is_owner = false;
    if($guid_message == AOWNER){
        $Is_owner = true;
    }
    return $Is_owner;
}
function setName($robot,$Name){
    $user = GET_USER_BY_GUID(GUID,$robot);
                
    $RFLName = false;
    $RID = false;
    $Rbio = false;

    $RFLName='';
    $RLLName='';
    $RID='';
    $Rbio='';

    if(isset($user['first_name'])){
        $RFLName =  $user['first_name'];
    }
    if(isset($user['last_name'])){
        $RLLName =  $user['last_name'];
    }
    if(isset($user['username'])){
        $RID = $user['username'];
    }
    if(isset($user['bio'])){
        $Rbio = $user['bio'];
    }
    if(is_null($Name)){
        $Name = "↻ ᖇOᗷOT";
    }
    $LIMIT = 12;
    while(true){
        if($LIMIT < 1){
            return false;
        }
        usleep(200000);
        $ok = $robot->UpdateProfile("$Name","","$Rbio");
        if(is_null($ok)){
            $LIMIT--;
            continue;
        }
        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
            $LIMIT--;
            continue;
        }
        return true;
    }
}
function setBio($robot){
    $user = GET_USER_BY_GUID(GUID,$robot);

    $RFLName = false;
    $RID = false;
    $Rbio = false;

    $RFLName='';
    $RLLName='';
    $RID='';
    $Rbio='';

    if(isset($user['first_name'])){
        $RFLName =  $user['first_name'];
    }
    if(isset($user['last_name'])){
        $RLLName =  $user['last_name'];
    }
    if(isset($user['username'])){
        $RID = $user['username'];
    }
    if(isset($user['bio'])){
        $Rbio = $user['bio'];
    }

    /// get group link and set bio and name
    $final = 12;
    while(true){
        if($final <= 0){
            return false;
        }
        usleep(200000);
        $link_info = $robot->getGroupLink(GUID_U);
        if(is_null($link_info)){
            $final--;
            continue;
        }
        if(isset($link_info['status']) && $link_info['status'] === 'OK'){
            $link = $link_info['data']['join_link'];
        }else{
            $final--;
            continue;
        }
        
        $bio = "ＬＩＮＫ\n".$link."\n.\nＭＡＫＥＲ'\n".CHANNEL;

        if($bio){
            $finalX = 12;
            while(true){
                if($finalX <= 0){
                    return false;
                }
                usleep(200000);
                // $ok = $robot->UpdateProfile("↻ ᖇOᗷOT","","$bio");
                $ok = $robot->UpdateProfile("$RFLName","$RLLName","$bio");
                if(is_null($ok)){
                    $finalX--;
                    continue;
                }
                if(!isset($ok['status']) || $ok['status'] !== 'OK'){
                    $finalX--;
                    continue;
                }
                return true;
            }
            return false;
        }
    }
}
function setBioDif($robot,$text){
    $user = GET_USER_BY_GUID(GUID,$robot);
    $RFLName='';
    $RLLName='';

    if(isset($user['first_name'])){
        $RFLName =  $user['first_name'];
    }
    if(isset($user['last_name'])){
        $RLLName =  $user['last_name'];
    }
    /// get group link and set bio and name

    $bio = "$text\n".CHANNEL;

    $finalX = 12;
    while(true){
        if($finalX <= 0){
            return false;
        }
        usleep(200000);
        // $ok = $robot->UpdateProfile("↻ ᖇOᗷOT","","$bio");
        $ok = $robot->UpdateProfile("$RFLName","$RLLName","$bio");
        if(is_null($ok)){
            $finalX--;
            continue;
        }
        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
            $finalX--;
            continue;
        }
        return true;
    }
    return false;
    
}
function setId($robot,$Yid){
        $LIMIT = 12;
        while(true){
            if($LIMIT < 1){
                return false;
            }
            usleep(200000);
            if(is_null($Yid)){
                $ID = rand(10000000,99999999);
                $ID = "L8PSTUDIO_".$ID;
            }else{
                $ID = $Yid;
            }
            $ok = $robot->UpdateUsername("$ID");
            if(is_null($ok)){
                $LIMIT--;
                continue;
            }
            if(!isset($ok['status']) || $ok['status'] !== 'OK'){
                $LIMIT--;
                continue;
            }
            return true;
        }
}
function end_form($txt){
    $ST = "\n\n"."─┅━━━━━━━┅─";
    $ok = $txt.$ST;
    return $ok;
}
function title($txt){
    $ST = "◄"." ";
    $ok = $ST.$txt."\n\n";
    return $ok;
}
function mini($txt){
    $ST = "•"." ";
    $ok = $ST.$txt;
    return $ok;
}
function miniO($txt){
    $ST = "❖"." ";
    $ok = $ST.$txt;
    return $ok;
}
function day(){
    $day = date("l",time());
    return $day;
}
function dayp($time){
    $day = date("l",$time);
    $DAYSM = ['Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday','Friday'];
    $DAYSP = ['شنبه','یکشنبه','دوشنبه','سه شنبه','چهارشنبه','پنج شنبه','جمعه'];
    $N = 0;
    foreach($DAYSM as $DAY){
        if($DAY == $day){
            $NMP = $N;
            break;
        }
        $N++;
    }
    $dayp = $DAYSP[$NMP];
    return $dayp;
}
function date_time($time){
    $date = date("Y-m-d",$time);
    return $date;
}
function clock($time){
    $clock = date("H:i",$time);
    return $clock;
}
function date_all($time){
    $day = dayp($time);
    $clock = clock($time);
    $date = date_time($time);
    $TXT = $day." ".$clock." ".$date;
    return $TXT;
}
function orders($ORDER){
    $TEXT = miniO($ORDER);
    return $TEXT;
}
