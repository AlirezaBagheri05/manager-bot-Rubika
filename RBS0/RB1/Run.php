<?php

set_time_limit(900);

$first_time = time();

$NUM = $_GET['NUM'];

$NUM = intval($NUM);

$Main_dir = dirname(dirname(__DIR__));

$ADMINS = array();

define('SITE_URL',$Main_dir.'/');

include_once(SITE_URL.'lib.php');

include_once('config.php');

include_once('SoursCode.php');

include_once('functions.php');

$lib_LR = new lib_LinkRemover(SERVER_NAME,USER_NAME,USER_PASSWORD,DB_NM);
$lib_BN = new lib_UsersBands(SERVER_NAME,USER_NAME,USER_PASSWORD,DB_NM);
$lib_CH = new lib_Chalesh(SERVER_NAME,USER_NAME,USER_PASSWORD,DB_NM);

$lib_bio = new lib_bio(SERVER_NAME,USER_NAME,USER_PASSWORD,DB_NM);
$lib_text = new lib_text(SERVER_NAME,USER_NAME,USER_PASSWORD,DB_NM);
$lib_etraf = new lib_etraf(SERVER_NAME,USER_NAME,USER_PASSWORD,DB_NM);
$lib_jock = new lib_jock(SERVER_NAME,USER_NAME,USER_PASSWORD,DB_NM);
$lib_fact = new lib_fact(SERVER_NAME,USER_NAME,USER_PASSWORD,DB_NM);

$lib_QU = new lib_QU(SERVER_NAME,USER_NAME,USER_PASSWORD,DB_NM);
$lib_ANS = new lib_ANS(SERVER_NAME,USER_NAME,USER_PASSWORD,DB_NM);
$lib = new lib(SERVER_NAME,USER_NAME,USER_PASSWORD,DB_NM);

/// count 

$chalesh_count = $lib_CH->count();
$bio_count = $lib_bio->count();
$text_count = $lib_text->count();
$etraf_count = $lib_etraf->count();
$jock_count = $lib_jock ->count();
$fact_count = $lib_fact->count();

define('chalesh_count',"$chalesh_count");
define('bio_count',"$bio_count");
define('text_count',"$text_count");
define('etraf_count',"$etraf_count");
define('jock_count',"$jock_count");
define('fact_count',"$fact_count");

$AUTHS = $lib->select_All();

if(!isset($AUTHS[$NUM])){
    exit;
}

$AUTH = $AUTHS[$NUM];

define('AUTH',"$AUTH");


$make_commend = "‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌‌‍‌‌‍‌‍‌‌‍‌‌‍‍‍‌‍‌‍‍‌‌‍‍‌‌‍‌‌‍‌‍‌‌‍‍‍‍‍‌‍‍‌‌‍‌‍‍‍‌‌‍‍‌‌‍‌‌‍‍‌‌‍‍‌‍‍‌‍‌‍‌‍‍‌‍‌‌‍‌‍‌"."\n";
define('COMD',"$make_commend");

$Minfo = $lib->select("$AUTH");

if(is_null($Minfo) || is_null($Minfo['GUID'])){
    exit;
}

$Regester =  $Minfo['Regester'];
$Regester =  intval($Regester);
$Rtime = time();
$RE = $Rtime-$Regester;
if(410 >= $RE){
    exit;
}

$phone =  $Minfo['phone'];
$phone = intval($phone);

if($phone == 0){
    exit;
}

// $phone =  $Minfo['phone'];

$GUID = $Minfo['GUID'];


$FullAdmins =  $Minfo['FullAdmins'];

$Owner =  $Minfo['Owner'];
if(is_null($Owner)){
    exit;
}

$Etime = $Minfo['EndTime'];
$Etime = intval($Etime);
$Ntime = time();
$OKtime = $Etime-$Ntime;
if($OKtime < 0){
    $lib->updateconnection_time_GUiD(AUTH,0);
    $lib->updateState_GUiD(AUTH,'false');
    exit;
}
$state = $Minfo['State'];
if($state == 'falseF'){
    exit;
}

define('AOWNER',"$Owner");

define('GUID',"$GUID");

// if(is_null($Minfo['DATE'])){
//     $lib->updateDate_GUiD(AUTH,'null');
// }
// if(is_null($Minfo['DATEX'])){
//     $lib->updateDateX_GUiD(AUTH,0);
// }

// if is for the first time 

if(is_null($Minfo['State'])){
    $lib->updateState_GUiD(AUTH,'false');
}
if(is_null($Minfo['connection_time'])){
    $lib->updateconnection_time_GUiD(AUTH,0);
}
if(is_null($Minfo['Time'])){
    $lib->updatetime_GUiD(AUTH,0);
}
if(is_null($Minfo['State_X'])){
    $lib->updateState_X_GUiD(AUTH,'false');
}



$lib->updateState_GUiD(AUTH,'load');


$robot = new lib_rubika(AUTH,GUID);

// $DATEE = date("Y-m-d H:i:sa", $first_time);
// $robot->sendMessage(GUID_OMG,"login; ✅ ' $DATEE");


$hour = 86400;
if($OKtime <= ($hour*1) && $OKtime >= (($hour*1)-720)){
    $robot->sendMessage(AOWNER,"مهلت اشتراک شما رو به اتمام است. تنها یک روز دیگر باقی مانده است.");
    // FORCONTROL($robot,"I love u\nI'm active ✅");
}else if($OKtime <= ($hour*2) && $OKtime >= (($hour*2)-720)){
    $robot->sendMessage(AOWNER,"مهلت اشتراک شما رو به اتمام است. تنها دو روز دیگر باقی مانده است.");
    // FORCONTROL($robot,"I love u\nI'm active ✅");
}else if($OKtime <= ($hour*5) && $OKtime >= (($hour*5)-720)){
    $robot->sendMessage(AOWNER,"مهلت اشتراک شما رو به اتمام است. تنها پنج روز دیگر باقی مانده است.");
    // FORCONTROL($robot,"I love u\nI'm active ✅");
}

$GUID_U = $Minfo['GUID_U'];

$needle = "https://rubika.ir/joing/";

/// make GUID_U
$search_link = pro_search($GUID_U,$needle);
if($search_link){
    /// get group link
    $final = 30;
    while(true){
        if($final <= 0){
            exit;
        }
        $hash_link = str_replace($needle,'',$GUID_U);
        $Joined = $robot->joinGroup($hash_link);
        if(is_null($Joined)){
            $final--;
            sleep(1);
            continue;
        }
        if(!isset($Joined['status']) || $Joined['status'] !== 'OK'){
            exit;
        }
        if(isset($Joined['data']['group'])){
            $group = $Joined['data']['group'];
            $GUID_U = $group['group_guid'];
            $lib->updateGuid_U(AUTH,$GUID_U);
            break;
        }else{
            $final--;
            sleep(1);
            continue;
        }
        
    }
}

$METHOD = true;

if(is_null($GUID_U) || $GUID_U == 'null'){
    $GUID_U = NULL;
    $METHOD = false;
}

/// for manage gap
/// validate the guid
if($METHOD){
    /// instal ROBOT
    $info_LR = $lib_LR->selectAllTXTS_GUID($GUID_U);
    if(is_null($info_LR)){
        sleep(1);
        $info_LR = $lib_LR->selectAllTXTS_GUID($GUID_U);
        if(is_null($info_LR)){
            $pats['Guid_U'] = $GUID_U;
            $pats['All'] = '0-0-0-0-0-0-0-0-0-0-0-0-0-0-0-0-0-0-0-0-0-0';
            $pats['Date'] = time();
            $pats['Setting'] = '1-2-2-1-1-1-1-1-1-1-1-1-2-1-4-4-1-4-2-2-2-1';
            $pats['Max'] = '3';
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
            $pats['TXTS'] = $qavanin.'`'.$remember.'`'.$add.'`'.$remove.'`'.$join.'`'.$leave.'`1`1`1`1`1`0`2`2`'.$addpv.'`1`1`1`1`1`1';
            $pats['AMS'] = "1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1-1";
            $final = 3;
            while(true){
                if($final <= 0){
                    break;
                }
                $infoGap = $robot->getGroupInfo($GUID_U);
                if(is_null($infoGap)){
                    $final--;
                    sleep(1);
                    continue;
                }
                if(isset($infoGap['status']) && $infoGap['status'] === 'OK'){
                    $infogroup  = $infoGap['data']['group'];
                    $Name = $infogroup['group_title'];
                    $Name = utf8_encode($Name);
                    break;
                }else{
                    $final--;
                    sleep(1);
                    continue;
                }
            }
            $pats['Name'] = $Name;
            $result = $lib_LR->insert($pats);
            $LOOK = "HI I AM ROBOT :)";
            $d = time();
            $date = date("Y-m-d h:i:sa", $d);
            $length = strlen($LOOK);
            $result = $robot->sendMessage_mentionX($GUID_U,GUID,"$LOOK\n\nDate : $date\n\n ربات با موفقیت بر روی گپ نصب شد 🥳🤓\nلطفا فول ادمینم کنید تا بدون هیچ مشکلی بر روی گپ فعالیت کنم☹️\n\nبرای کار با من ی سر به دستوراتم بزنید🤩‍ 🔮\n\n@L8PSTUDIO_HELP",null,$length);
            $ME_ms_id = $result['data']['message_update']['message_id'];
            $allMSG = $lib_LR->selectAMS_GUID($GUID_U);
            $MSG = $allMSG['AMS'];
            $MS = explode("-",$MSG);
            $AMS = $MS[1].'-'.$MS[2].'-'.$MS[3].'-'.$MS[4].'-'.$MS[5].'-'.$MS[6].'-'.$MS[7].'-'.$MS[8].'-'.$MS[9].'-'.$MS[10].'-'.$MS[11].'-'.$MS[12].'-'.$MS[13].'-'.$MS[14].'-'.$MS[15].'-'.$MS[16].'-'.$MS[17].'-'.$MS[18].'-'.$MS[19].'-'.$MS[20].'-'.$MS[21].'-'.$MS[22].'-'.$MS[23].'-'.$MS[24].'-'.$MS[25].'-'.$MS[26].'-'.$MS[27].'-'.$MS[28].'-'.$MS[29].'-'.$MS[30].'-'.$MS[31].'-'.$MS[32].'-'.$MS[33].'-'.$MS[34].'-'.$MS[35].'-'.$MS[36].'-'.$MS[37].'-'.$MS[38].'-'.$MS[39].'-'.$MS[40].'-'.$MS[41].'-'.$MS[42].'-'.$MS[43].'-'.$MS[44].'-'.$MS[45].'-'.$MS[46].'-'.$MS[47].'-'.$MS[48].'-'.$MS[49].'-'.$ME_ms_id;
            $lib_LR->updateAMS_GUiD($GUID_U,$AMS);
        }
    }
    // insert information
    $conection = $lib->selectconnection_time_GUID(AUTH);
    if(is_null($conection['connection_time']) || $conection['connection_time'] == 'null' && $conection['connection_time'] != 0){
        $timeup = time();
        $lib->updateconnection_time_GUiD(AUTH,$timeup);
        $TIME_UP = $timeup;
    }else{
        $TIME_UP = $conection['connection_time'];
    }
    $timehere = time();
    $TIME_UP = intval($TIME_UP);
    $result = $timehere-$TIME_UP;
    $result = intval($result);
    if($result >= 43200){

        $robot->sendMessage(GUID_OMG,"I'm active ✅ 1.2");

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

        $final = 3;
        /// get group link and set bio and name
        $changeBio = pro_search($Rbio,CHANNEL);
        if(!$changeBio){
            while(true){
                if($final <= 0){
                    break;
                }
    
                $link_info = $robot->getGroupLink($GUID_U);
                if(is_null($link_info)){
                    $final--;
                    sleep(1);
                    continue;
                }
                if(isset($link_info['status']) && $link_info['status'] === 'OK'){
                    $link = $link_info['data']['join_link'];
                }else{
                    $final--;
                    sleep(1);
                    continue;
                }
                
                $bio = "ＬＩＮＫ\n".$link."\n.\nＭＡＫＥＲ'\n".CHANNEL;
    
                if($bio){
                    $finalX = 3;
                    while(true){
                        if($finalX <= 0){
                            break;
                        }
                        // $ok = $robot->UpdateProfile("↻ ᖇOᗷOT","","$bio");
                        $ok = $robot->UpdateProfile("$RFLName","$RLLName","$bio");
                        if(is_null($ok)){
                            $finalX--;
                            sleep(1);
                            continue;
                        }
                        if(!isset($ok['status']) || $ok['status'] !== 'OK'){
                            $finalX--;
                            sleep(1);
                            continue;
                        }
                        break;
                    }
                    break;
                }else{
                    break;
                }
            }
        }

        // get group info and save the name of gap
        $final = 3;
        while(true){
            if($final <= 0){
                break;
            }

            $infoGap = $robot->getGroupInfo($GUID_U);
            if(is_null($infoGap)){
                $final--;
                sleep(1);
                continue;
            }
            if(isset($infoGap['status']) && $infoGap['status'] === 'OK'){
                $infogroup  = $infoGap['data']['group'];
                $Name = $infogroup['group_title'];
                $Name = utf8_encode($Name);
                $lib_LR->updateName_GUiD($GUID_U,$Name);
                break;
            }else{
                $final--;
                sleep(1);
                continue;
            }
        }
        /// set id
        /*
        $sear = pro_search($RID,'L8PSTUDIO_');
        if(!$sear){
            $final = 3;
            while(true){

                if($final <= 0){
                    break;
                }

                $ID = rand(1000000,9999999);
                $ID = "L8PSTUDIO_".$ID;
                
                sleep(1);
                
                $IDresutl = $robot->UpdateUsername("$ID");
                if(is_null($IDresutl)){
                    $final--;
                    sleep(1);
                    continue;
                }
                if(!isset($IDresutl['status']) || $IDresutl['status'] !== 'OK'){
                    $final--;
                    sleep(1);
                    continue;
                }
                if(isset($IDresutl['data']['status'])){
                    $result = $IDresutl['data']['status'];
                    if($result == 'UsernameExist'){
                        continue;
                    }
                }
                break;
            }
        }
        */
        $lib->updateconnection_time_GUiD(AUTH,$timehere);
    }
    /// get group admins
    $CNTRL = 30;
    while(true){
        if($CNTRL <= 0){
            break;
        }
        $result_ads = $robot->getGroupAdmins($GUID_U);
        if(is_null($result_ads)){
            $CNTRL--;
            sleep(1);
            continue;
        }
        if(!isset($result_ads['status']) || !isset($result_ads['data']['in_chat_members']) || $result_ads['status'] !== 'OK'){
            $CNTRL--;
            sleep(1);
            continue;
        }
        $admins_info = $result_ads['data']['in_chat_members'];
        $PERMESITION = true;
        $ADMINS = '';
        foreach($admins_info as $admin_info){
            $member_guid = $admin_info['member_guid'];
            $ADMINS .= '-'.$member_guid;
        }
        $lib->updateAdmins(AUTH,$ADMINS);
        break;
    }
}else{
    // insert pesonal information
    $conection = $lib->selectconnection_time_GUID(AUTH);
    if(is_null($conection['connection_time']) || $conection['connection_time'] == 'null' && $conection['connection_time'] != 0){
        $timeup = time();
        $lib->updateconnection_time_GUiD(AUTH,$timeup);
        $TIME_UP = $timeup;
    }else{
        $TIME_UP = $conection['connection_time'];
    }
    $timehere = time();
    $TIME_UP = intval($TIME_UP);
    $result = $timehere-$TIME_UP;
    $result = intval($result);
    if($result >= 43200){

        $robot->sendMessage(GUID_OMG,"I'm active ✅ 1.2");


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

        $finalX = 3;
        /// get group link and set bio and name
        $changeBio = pro_search($Rbio,CHANNEL);
        if(!$changeBio){
            $bio = "ＬＩＮＫ\n"."\n.\nＭＡＫＥＲ'\n".CHANNEL;
            if($bio){
                while(true){
                    if($finalX <= 0){
                        break;
                    }
                    // $ok = $robot->UpdateProfile("↻ ᖇOᗷOT","","$bio");
                    $ok = $robot->UpdateProfile("$RFLName","$RLLName","$bio");
                    if(is_null($ok)){
                        $finalX--;
                        sleep(1);
                        continue;
                    }
                    if(!isset($ok['status']) || $ok['status'] !== 'OK'){
                        $finalX--;
                        sleep(1);
                        continue;
                    }
                    break;
                }
            }
        
        }
        $lib->updateconnection_time_GUiD(AUTH,$timehere);
    }
}

define('GUID_U',"$GUID_U");

$Tod = Tod($lib_LR);
if(!isset($Tod[13])){
    SAVEMSS($robot,14,"اد شدی عزیزم:)",NULL,"",$lib_LR);
}

// conecte
$CONECTED = getChats($robot);
/// load robot 

$lib->updatetime_GUiD(AUTH,time());
$lib->updateState_GUiD(AUTH,'true');

$lib->updateRegester(AUTH,$first_time);

$robot->onUpdate(function (array $update) use ($robot,$lib,$lib_ANS,$lib_QU,$lib_LR,$lib_BN,$lib_CH,$METHOD,$lib_bio,$lib_text,$lib_etraf,$lib_jock,$lib_fact) {
    $LMessage = null;
    if (isset($update['data_enc'])) {

        $message = $update['data_enc'];

        if(isset($message['message_updates'])){

            $sleeps = 0;
            $Tod = Tod($lib_LR);
            if(isset($Tod[11])){
                $sleeps = $Tod[11];
                $sleeps = intval($sleeps);
                $sleeps = round($sleeps);
            }
            /// get owner and special persens
            $FullAdminsX = $lib->selectFullAdminsX(AUTH);
    
            $FullAdmins = $FullAdminsX['FullAdmins'];
            $ADMINSXX = $FullAdminsX['Admins'];
    
            
            $AFA = array();
            $AFA[0] = GUID_OMG;
            $AFA[1] = GUID;
            $i = 2;
            if(!is_null($FullAdmins)){
                $FAdmins = explode("-",$FullAdmins);
                foreach($FAdmins as $FAdmin){
                    $AFA[$i] = $FAdmin;
                    $i++;
                }
            }
            $ADMINS = array();
            $g = 0;
            if(!is_null($ADMINSXX)){
                $FAdmins = explode("-",$ADMINSXX);
                foreach($FAdmins as $FAdmin){
                    $ADMINS[$g] = $FAdmin;
                    $g++;
                }
            }
            foreach ($message['message_updates'] as $value){
                if($sleeps > 0){
                    sleep($sleeps);
                }
                // get message information
                $guid_message = null;
                $Main_type = $value['type'];
                $Main_guid = $value['object_guid'];
                $Message = $value['message'];
                $type = $Message['type'];
                $message_id = $Message['message_id'];
                if(!isset($Message['author_object_guid'])){
                    if(isset($Message["event_data"])){
                        $event = $Message["event_data"];
                        if(isset($event['performer_object'])){
                            $info_object = $event['performer_object'];
                            if(isset($info_object["object_guid"])){
                                $guid_message = $info_object["object_guid"];
                            }
                        }
                    }
                }else{
                    $guid_message = $Message['author_object_guid'];
                }
                if(is_null($guid_message)){
                    continue;
                }
    
                $Is_fulladmin = false;
                foreach($AFA as $FA){
                    if($FA == $guid_message){
                        $Is_fulladmin = true;
                        $Is_admin = true;
                        break;
                    }
                }
    
                $Is_owner = false;
                if($guid_message == AOWNER){
                    $Is_owner = true;
                    $Is_fulladmin = true;
                    $Is_admin = true;
                }
                
                if($Main_guid == GUID_U && $Main_type == 'Group' && $METHOD) {
                    $setting_a = $lib_LR->selectSetting_GUID(GUID_U);
                    $Ssetting = $setting_a['Setting'];
                    $Setting = explode("-",$Ssetting);
                    $Is_admin = false;
                    foreach($ADMINS as $admin){
                        if($admin == $guid_message){
                            $Is_admin = true;
                            break;
                        }
                    }
                    ROBOT_CODES($robot,$lib,$lib_ANS,$lib_QU,$Message,$guid_message,$lib_LR,$lib_BN,$lib_CH,$Setting,$Is_admin,$ADMINS,$LMessage,$Is_fulladmin,$Is_owner,$AFA,$lib_bio,$lib_text,$lib_etraf,$lib_jock,$lib_fact);
                }else if($Is_owner || $Is_fulladmin){
                    if(isset($Message['text'])){
                        $text = $Message['text'];
                        if(isset($text)){
                            selfBot($Message,$Main_guid,$text,$message_id,$guid_message,$lib,$lib_LR,$lib_QU,$lib_ANS,$robot,$lib_BN,$Is_fulladmin,$Is_owner,$METHOD);
                        }
                    }
                }else if($Main_type == 'User'){
                    if($METHOD){
                        if($Tod[9] == 1){
                            $Is_admin = false;
                            foreach($ADMINS as $admin){
                                if($admin == $guid_message){
                                    $Is_admin = true;
                                    break;
                                }
                            }
                            if(!$Is_admin){
                                $result = setAdmin($robot,$guid_message);
                                if($result){
                                    SendMessageX($robot,$guid_message,2,NULL,$lib_LR);
                                    setAdmins($guid_message,$lib);
                                    $wel = $Tod[14];
                                    $wel = utf8_decode($wel);
                                    SendMessageSpeakSelf($robot,$guid_message,$lib_LR,"$wel",$message_id);
                                }
                            }
                        }
                        if($Tod[13] == 1){
                            block_user($robot,$guid_message);
                        }
                    }
                    // $seen_list = ["$Main_guid" => "$message_id"];

                    // seenChats($seen_list,$robot);
                }
            }
        }
    }
});