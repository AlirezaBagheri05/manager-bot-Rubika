<?php

define('SETTING',"update_bot");
define('LINKST',"LinkRemoverHistory");
define('BANDUSER',"UsersBands");
define('CHALESH',"chalesh");

define('BIO',"bio");
define('TEXT',"text");
define('JOCK',"jock");
define('FACT',"fact");
define('ETRAF',"etraf");

define('QUSPEAK',"questions");
define('ANSSPEAK',"answers");
define('DEBUGMS',"All things are ok now...");
define('CHANNEL',"@L8PSTUDIO");

define('TIMEMAIN',600);
define('STEP_1S',300);
define('STEP_2S',400);
define('STEP_3S',500);
define('STEP_4S',600);
define('TIMEOUTS',30);
define('TIMENULLS',30);

define('GUID_OWNER','u0Dile909969f5fa7c856253d420c117');

define('GUID_OMG','u0Dile909969f5fa7c856253d420c117');

define('SERVER_NAME','xxxx');

define("USER_NAME","xxxx");

define("USER_PASSWORD","xxxx");

define("DB_NM","xxxx");

class lib{
    private $SERVER_NAME;
    private $USER_NAME;
    private $USER_PASSWORD;
    private $DB_NM;

    public function __Construct($SERVER_NAME,$USER_NAME,$USER_PASSWORD,$DB_NM){
        $this->SERVER_NAME = $SERVER_NAME;
        $this->USER_NAME = $USER_NAME;
        $this->USER_PASSWORD = $USER_PASSWORD;
        $this->DB_NM = $DB_NM;
    }

    private function cov(){
        $SERVER_NAME = $this->SERVER_NAME;
        $USER_NAME = $this->USER_NAME;
        $USER_PASSWORD = $this->USER_PASSWORD;
        $DB_NM = $this->DB_NM;
        $all =  array(
            'S'=>$SERVER_NAME,
            'UN'=>$USER_NAME,
            'UP'=>$USER_PASSWORD,
            'D'=>$DB_NM
        );
        return $all;
    }

    private function connect(){
        $all = $this->cov();
        $link = mysqli_connect($all['S'],$all['UN'],$all['UP'],$all['D']);
        if (mysqli_connect_errno($link)){
            return "Failed to connect to MySQL: " . mysqli_connect_error($link);
        }
        return $link;
    }

    private function query($qr){
        $link = $this->connect();
        $result = mysqli_query($link,$qr);
        mysqli_close($link);
        return $result;
    }
    // SELECT * FROM `update_bot` WHERE `id` = 1 AND `State` LIKE 'false'
    public function insert($pats){
        $phone = $pats['phone'];
        $AUTH = $pats['AUTH'];
        $GUID = $pats['GUID'];
        $GUID_U = $pats['GUID_U'];
        $DATE = $pats['DATE'];
        $DATEX = $pats['DATEX'];
        $State = $pats['State'];
        $connection_time = $pats['connection_time'];
        $Time = $pats['Time'];
        $State_X = $pats['State_X'];
        $EndTime = $pats['EndTime'];
        $EndTime = $pats['Regester'];
        $qr = "INSERT INTO `".SETTING."` (`id`,`phone`,`AUTH`, `GUID`, `GUID_U`, `DATE`, `DATEX` , `State`, `connection_time`, `Time`, `State_X`, `EndTime`, `Regester`, `Owner`, `FullAdmins`) VALUES (NULL, '$phone', '$AUTH', '$GUID', '$GUID_U', '$DATE', '$DATEX', '$State', '$connection_time', '$Time', '$State_X', '$EndTime', '$Regester', '$Owner', '$FullAdmins')";
        $result = $this->query($qr);
        return $result;
    }
    public function insert_new($AUTH,$GUID,$EndTime,$guidowner,$phone){
        $qr = "INSERT INTO `".SETTING."` (`id`, `phone` ,  `AUTH`, `GUID`, `GUID_U`, `DATE`, `DATEX` , `State`, `connection_time`, `Time`, `State_X`, `EndTime`, `Regester`, `Owner`, `FullAdmins`, `Admins`) VALUES (NULL,'$phone', '$AUTH', '$GUID',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$EndTime',0,'$guidowner',NULL,'NULL')";
        $result = $this->query($qr);
        return $result;
    }

    public function select($AUTH){
        $qr = "SELECT  `phone`,`GUID`, `GUID_U`,`DATE`, `DATEX` , `State`, `connection_time`, `Time`, `State_X`, `EndTime`, `Regester` , `Owner`, `FullAdmins`, `Admins` FROM `".SETTING."` WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function select_All(){
        $qr = "SELECT  `AUTH` FROM `update_bot` WHERE 1";
        $result = $this->query($qr);
        $array = [];
        while($rows = mysqli_fetch_row($result)){
            foreach($rows as $row){
                    $array[] = $row;
            }
        }
        return $array;
    }
    public function select_GUID_U(){
        $qr = "SELECT  `GUID_U` FROM `update_bot` WHERE 1";
        $result = $this->query($qr);
        $array = [];
        while($rows = mysqli_fetch_row($result)){
            foreach($rows as $row){
                    $array[] = $row;
            }
        }
        return $array;
    }
    public function select_GUID(){
        $qr = "SELECT  `GUID` FROM `update_bot` WHERE 1";
        $result = $this->query($qr);
        $array = [];
        while($rows = mysqli_fetch_row($result)){
            foreach($rows as $row){
                    $array[] = $row;
            }
        }
        return $array;
    }
    public function selectEndTime($AUTH){
        $qr = "SELECT `EndTime` FROM `".SETTING."` WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectOwner($AUTH){
        $qr = "SELECT `Owner` FROM `".SETTING."` WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectFullAdmins($AUTH){
        $qr = "SELECT `FullAdmins` FROM `".SETTING."` WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectFullAdminsX($AUTH){
        $qr = "SELECT `FullAdmins`,`Admins` FROM `".SETTING."` WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectAdmins($AUTH){
        $qr = "SELECT `Admins` FROM `".SETTING."` WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectRegester($AUTH){
        $qr = "SELECT `Regester` FROM `".SETTING."` WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectDate_GUID($AUTH){
        $qr = "SELECT `DATE` FROM `".SETTING."` WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectId_GUID_U($AUTH){
        $qr = "SELECT `id` FROM `".SETTING."` WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectAuthGuid_GUID_U($AUTH){
        $qr = "SELECT `GUID_U` , `GUID` FROM `".SETTING."` WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectGUIDU($AUTH){
        $qr = "SELECT `GUID_U` FROM `".SETTING."` WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectGUID($GUID_U){
        $qr = "SELECT `GUID` FROM `".SETTING."` WHERE `GUID_U` = '$GUID_U'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectDateX_GUID($AUTH){
        $qr = "SELECT `DATEX` FROM `".SETTING."` WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectDateDateX_GUID($AUTH){
        $qr = "SELECT `DATE`, `DATEX` FROM `".SETTING."` WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectDateTime_GUiD($AUTH){
        $qr = "SELECT `DATE`, `Time` FROM `".SETTING."` WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    
    public function selectState_GUID($AUTH){
        $qr = "SELECT `State` FROM `".SETTING."` WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectState_X_GUID($AUTH){
        $qr = "SELECT `State_X` FROM `".SETTING."` WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectconnection_time_GUID($AUTH){
        $qr = "SELECT `connection_time` FROM `".SETTING."` WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectrequests_GUID($AUTH){
        $qr = "SELECT `requests` FROM `".SETTING."` WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selecttime_GUID($AUTH){
        $qr = "SELECT `Time` FROM `".SETTING."` WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectTime_null_GUID($AUTH){
        $qr = "SELECT `Time_null` FROM `".SETTING."` WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }

    public function delete($id){
        $qr = "DELETE FROM `".SETTING."` WHERE id = '$id'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }    
    
    public function update($id,$pats){
        $AUTH = $pats['AUTH'];
        $GUID = $pats['GUID'];
        $GUID_U = $pats['GUID_U'];
        $DATE = $pats['DATE'];
        $State = $pats['State'];
        $connection_time = $pats['connection_time'];
        $State_X = $pats['State_X'];
        $qr = "UPDATE `".SETTING."` SET `AUTH`='$AUTH',`GUID`='$GUID',`GUID_U`='$GUID_U',`DATE`='$DATE',`State`='$State',`connection_time`='$connection_time',`State_X`='$State_X' WHERE `id`='$id'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    // update_options
    public function updateGuidU($AUTH,$GUID){
        $qr = "UPDATE `".SETTING."` SET `GUID`='$GUID' WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateRegester($AUTH,$Regester){
        $qr = "UPDATE `".SETTING."` SET `Regester`='$Regester' WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateGuid_U($AUTH,$GUID_U){
        $qr = "UPDATE `".SETTING."` SET `GUID_U` = '$GUID_U'  WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateDate_GUiD($AUTH,$DATE){
        $qr = "UPDATE `".SETTING."` SET `DATE` = '$DATE' WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateDateX_GUiD($AUTH,$DATEX){
        $qr = "UPDATE `".SETTING."` SET `DATEX` = '$DATEX' WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateDateDateX_GUiD($AUTH,$DATE,$DATEX){
        $qr = "UPDATE `".SETTING."` SET `DATE` = '$DATE' , `DATEX` = '$DATEX' WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateDateTime_GUiD($AUTH,$DATE,$Time){
        $qr = "UPDATE `".SETTING."` SET `DATE` = '$DATE' , `Time` = '$Time' WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        return $result;
    }
    
    public function updateState_GUiD($AUTH,$State){
        $qr = "UPDATE `".SETTING."` SET `State` = '$State' WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateState_X_GUiD($AUTH,$State_X){
        $qr = "UPDATE `".SETTING."` SET `State_X` = '$State_X' WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateconnection_time_GUiD($AUTH,$connection_time){
        $qr = "UPDATE `".SETTING."` SET `connection_time` = '$connection_time' WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        return $result;
    }
    public function updaterequests_GUiD($AUTH,$requests){
        $qr = "UPDATE `".SETTING."` SET `requests` = '$requests' WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        return $result;
    }
    public function updatetime_GUiD($AUTH,$time){
        $qr = "UPDATE `".SETTING."` SET `Time` = '$time' WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateTime_null_GUiD($AUTH,$Time_null){
        $qr = "UPDATE `".SETTING."` SET `Time_null` = '$Time_null' WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateDateTime_null_GUiD($AUTH,$DATE,$Time_null){
        $qr = "UPDATE `".SETTING."` SET `DATE` = '$DATE' , `Time_null` = '$Time_null' WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        return $result;
    }

    public function updateFullAdmins($AUTH,$FullAdmins){
        $qr = "UPDATE `".SETTING."` SET `FullAdmins` = '$FullAdmins' WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateAdmins($AUTH,$Admins){
        $qr = "UPDATE `".SETTING."` SET `Admins` = '$Admins' WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateOwner($AUTH,$Owner){
        $qr = "UPDATE `".SETTING."` SET `Owner` = '$Owner' WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateEndTime($AUTH,$EndTime){
        $qr = "UPDATE `".SETTING."` SET `EndTime` = '$EndTime' WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        return $result;
    }
    public function updatephone($AUTH,$phone){
        $qr = "UPDATE `".SETTING."` SET `phone` = '$phone' WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        return $result;
    }
    
    public function count(){
        $qr = "select * from ".SETTING;
        $result = $this->query($qr);
        return mysqli_num_rows($result);
    }
    public function Delete_robot($AUTH){
        $qr = "DELETE FROM `".SETTING."` WHERE `AUTH` = '$AUTH'";
        $result = $this->query($qr);
        return mysqli_num_rows($result);
    }
    
} 