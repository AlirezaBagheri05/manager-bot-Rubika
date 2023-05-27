<?php

//CREATE TABLE `alirezaz_Robots`.`LinkRemoverHistory` ( `id` INT NOT NULL AUTO_INCREMENT , `Guid_U` VARCHAR(50) NOT NULL , `All` VARCHAR(2000) NOT NULL , `Today` VARCHAR(2000) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;.0 

// define('SERVER_NAME','localhost:3306');

// define("USER_NAME","alirezaz");

// define("USER_PASSWORD","Ab_4317247");

// define("DB_NM","alirezaz_Robots");

class lib_QU{
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
        $QU = $pats['QU'];
        $LEVEL = $pats['LEVEL'];
        $GUID_U = $pats['GUID_U'];
        $QU = utf8_encode($QU);
        $qr = "INSERT INTO `".QUSPEAK."` (`id`, `QU` , `LEVEL`, `GUID_U`) VALUES (NULL, '$QU', '$LEVEL', '$GUID_U');";
        $result = $this->query($qr);
        return $result;
    }

    public function select($LEVEL,$GUID_U){
        $qr = "SELECT `QU` FROM `".QUSPEAK."` WHERE `LEVEL` = '$LEVEL' AND `GUID_U` = '$GUID_U'";
        $result = $this->query($qr);
        $array = [];
        while($rows = mysqli_fetch_row($result)){
            foreach($rows as $row){
                    $QU = utf8_decode($row);
                    $array[] = $QU;
            }
        }
        return $array;
    }
    public function select_Def($LEVEL){
        $qr = "SELECT `QU` FROM `".QUSPEAK."` WHERE `LEVEL` = '$LEVEL' AND `GUID_U` = 'ALL'";
        $result = $this->query($qr);
        $array = [];
        while($rows = mysqli_fetch_row($result)){
            foreach($rows as $row){
                    $QU = utf8_decode($row);
                    $array[] = $QU;
            }
        }
        return $array;
    }
    public function updateGUID_U($GUID_new,$GUID_old){
        $qr = "UPDATE `".QUSPEAK."` SET `GUID_U` = '$GUID_new' WHERE  `GUID_U`='$GUID_old'";
        $result = $this->query($qr);
        return $result;
    }
    public function delete($QU,$GUID_U){
        $QU = utf8_encode($QU);
        $qr = "DELETE FROM `".QUSPEAK."` WHERE `GUID_U` = '$GUID_U' AND `QU` = '$QU'";
        $result = $this->query($qr);
        return $result;
    }
    public function count(){
        $qr = "select * from ".LINKST;
        $result = $this->query($qr);
        return mysqli_num_rows($result);
    }
    
} 