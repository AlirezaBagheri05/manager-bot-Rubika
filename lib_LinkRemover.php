<?php

//CREATE TABLE `alirezaz_Robots`.`LinkRemoverHistory` ( `id` INT NOT NULL AUTO_INCREMENT , `Guid_U` VARCHAR(50) NOT NULL , `All` VARCHAR(2000) NOT NULL , `Today` VARCHAR(2000) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;.0 

// define('SERVER_NAME','localhost:3306');

// define("USER_NAME","alirezaz");

// define("USER_PASSWORD","Ab_4317247");

// define("DB_NM","alirezaz_Robots");

class lib_LinkRemover{
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
            echo "Failed to connect to MySQL: " . mysqli_connect_error($link);
        }
        return $link;
    }

    private function query($qr){
        $link = $this->connect();
        $result = mysqli_query($link,$qr);
        mysqli_close($link);
        return $result;
    }
    // INSERT INTO `LinkRemoverHistory` (`id`, `Guid_U`, `All`, `Today`) VALUES (NULL, 'Guid_U', 'All', 'Today')

    public function insert($pats){
        $Guid_U = $pats['Guid_U'];
        $All = $pats['All'];
        $Date = $pats['Date'];
        $Setting = $pats['Setting'];
        $Max = $pats['Max'];
        $TXTS = $pats['TXTS'];
        $AMS = $pats['AMS'];
        $Name = $pats['Name'];
        $LastMessage = $pats['LastMessage'];
        $qr = "INSERT INTO `".LINKST."` (`id`, `Guid_U`, `All`, `Date`, `Setting`, `Max`, `TXTS`, `AMS`, `Name`, `LastMessage`) VALUES (NULL, '$Guid_U', '$All', '$Date', '$Setting', '$Max', '$TXTS', '$AMS', '$Name', '$LastMessage');";
        $result = $this->query($qr);
        return $result;
    }

    public function select($id){
        $qr = "SELECT `Guid_U`, `All` FROM `".LINKST."` WHERE id = '$id'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function select_Guid($GUID_U){
        $qr = "SELECT `All` FROM `".LINKST."` WHERE Guid_U = '$GUID_U'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function select_LastMessage($GUID_U){
        $qr = "SELECT `LastMessage` FROM `".LINKST."` WHERE Guid_U = '$GUID_U'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function select_Alls(){
        $qr = "SELECT `All` FROM `".LINKST."` WHERE 1";
        $result = $this->query($qr);
        $array = [];
        while($rows = mysqli_fetch_row($result)){
            foreach($rows as $row){
                $array[] = $row;
            }
        }
        return $array;
    }
    public function select_AllsGuids(){
        $qr = "SELECT  `GUID_U` , `All`, `Name` FROM `".LINKST."` WHERE 1";
        $result = $this->query($qr);
        $array = [];
        while($rows = mysqli_fetch_row($result)){
            $array[] = $rows;
        }
        return $array;
    }
    public function selectAll_GUID($GUID_U){
        $qr = "SELECT `All` FROM `".LINKST."` WHERE `Guid_U` = '$GUID_U'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectName_GUID($GUID_U){
        $qr = "SELECT `Name` FROM `".LINKST."` WHERE `Guid_U` = '$GUID_U'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        $Name = $row['Name'];
        $Name = utf8_decode($Name);
        return $Name;
    }
    public function selectAllTXTS_GUID($GUID_U){
        $qr = "SELECT `All` , `TXTS`  FROM `".LINKST."` WHERE `Guid_U` = '$GUID_U'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectTXTS_GUID($GUID_U){
        $qr = "SELECT `TXTS` FROM `".LINKST."` WHERE `Guid_U` = '$GUID_U'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectAMS_GUID($GUID_U){
        $qr = "SELECT `AMS` FROM `".LINKST."` WHERE `Guid_U` = '$GUID_U'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectSetting_GUID($GUID_U){
        $qr = "SELECT `Setting` FROM `".LINKST."` WHERE `Guid_U` = '$GUID_U'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectMax_GUID($GUID_U){
        $qr = "SELECT `Max` FROM `".LINKST."` WHERE `Guid_U` = '$GUID_U'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function delete($id){
        $qr = "DELETE FROM `".LINKST."` WHERE id = '$id'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    // `id`, `Guid_U`, `All`, `Today`
    public function update($id,$pats){
        $Guid_U = $pats['Guid_U'];
        $All = $pats['All'];
        $qr = "UPDATE `".LINKST."` SET `Guid_U`='$Guid_U',`All`='$All' WHERE `id`='$id'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function update_GUID($GUID_U,$pats){
        $All = $pats['All'];
        $qr = "UPDATE `".LINKST."` SET `All`='$All' WHERE `Guid_U`='$GUID_U'";
        $result = $this->query($qr);
        return $result;
    }
    // update_options
    public function updateAll_GUiD($GUID_U,$All){
        $qr = "UPDATE `".LINKST."` SET `All` = '$All' WHERE  `GUID_U`='$GUID_U'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateTXTS_GUiD($GUID_U,$TXTS){
        $qr = "UPDATE `".LINKST."` SET `TXTS` = '$TXTS' WHERE  `GUID_U`='$GUID_U'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateSetting_GUiD($GUID_U,$Setting){
        $qr = "UPDATE `".LINKST."` SET `Setting` = '$Setting' WHERE  `GUID_U`='$GUID_U'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateDateLimit_GUiD($GUID_U,$DateLimit){
        $qr = "UPDATE `".LINKST."` SET `DateLimit` = '$DateLimit' WHERE  `GUID_U`='$GUID_U'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateMax_GUiD($GUID_U,$Max){
        $qr = "UPDATE `".LINKST."` SET `Max` = '$Max' WHERE  `GUID_U`='$GUID_U'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateName_GUiD($GUID_U,$Name){
        $qr = "UPDATE `".LINKST."` SET `Name` = '$Name' WHERE  `GUID_U`='$GUID_U'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateAMS_GUiD($GUID_U,$AMS){
        $qr = "UPDATE `".LINKST."` SET `AMS` = '$AMS' WHERE  `GUID_U`='$GUID_U'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateLastMessage_GUiD($GUID_U,$LastMessage){
        $qr = "UPDATE `".LINKST."` SET `LastMessage` = '$LastMessage' WHERE  `GUID_U`='$GUID_U'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateAMSLMS_GUiD($GUID_U,$AMS,$LastMessage){
        $qr = "UPDATE `".LINKST."` SET `AMS`='$AMS',`LastMessage` = '$LastMessage' WHERE  `GUID_U`='$GUID_U'";
        $result = $this->query($qr);
        return $result;
    }
    public function count(){
        $qr = "select * from ".LINKST;
        $result = $this->query($qr);
        return mysqli_num_rows($result);
    }
    
} 