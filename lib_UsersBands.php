<?php

//CREATE TABLE `alirezaz_Robots`.`LinkRemoverHistory` ( `id` INT NOT NULL AUTO_INCREMENT , `Guid_U` VARCHAR(50) NOT NULL , `All` VARCHAR(2000) NOT NULL , `Today` VARCHAR(2000) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;.0 

// define('SERVER_NAME','localhost:3306');

// define("USER_NAME","alirezaz");

// define("USER_PASSWORD","Ab_4317247");

// define("DB_NM","alirezaz_Robots");

class lib_UsersBands{
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
    // INSERT INTO `LinkRemoverHistory` (`id`, `Guid_U`, `All`, `Today`) VALUES (NULL, 'Guid_U', 'All', 'Today')
    public function insert($pats){
        $Guid_gap = $pats['Guid_gap'];
        $Guid_user = $pats['Guid_user'];
        $state = $pats['state'];
        $Max = $pats['Max'];
        $report = $pats['report'];
        $info = $pats['info'];
        $name = $pats['name'];
        $qr = "INSERT INTO `".BANDUSER."` (`id`, `Guid_gap`, `Guid_user`, `state`, `Max` , `report`, `info`, `name`) VALUES (NULL, '$Guid_gap', '$Guid_user', '$state', '$Max', '$report', '$info', '$name');";
        $result = $this->query($qr);
        return $result;
    }
    public function selectStateMax($Guid_gap,$Guid_user){
        $qr = "SELECT `state`, `Max` FROM `".BANDUSER."` WHERE Guid_gap = '$Guid_gap' AND `Guid_user` = '$Guid_user'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function select($Guid_gap,$Guid_user){
        $qr = "SELECT `state`, `Max` , `report`, `info`, `name` FROM `".BANDUSER."` WHERE Guid_gap = '$Guid_gap' AND `Guid_user` = '$Guid_user'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function selectName($Guid_gap,$Guid_user){
        $qr = "SELECT `name` FROM `".BANDUSER."` WHERE Guid_gap = '$Guid_gap' AND `Guid_user` = '$Guid_user'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function select_allRepoart($Guid_gap){
        $qr = "SELECT  `report` FROM `".BANDUSER."` WHERE Guid_gap = '$Guid_gap'";
        $result = $this->query($qr);
        $array = [];
        while($rows = mysqli_fetch_row($result)){
            foreach($rows as $row){
                $array[] = $row;
            }
        }
        return $array;
    }
    public function select_allRepoartX($Guid_gap){
        $qr = "SELECT  `Guid_user` , `report` FROM `".BANDUSER."` WHERE Guid_gap = '$Guid_gap'";
        $result = $this->query($qr);
        $array = [];
        while($rows = mysqli_fetch_row($result)){
                $array[] = $rows;
        }
        return $array;
    }
    public function select_allState($Guid_gap){
        $qr = "SELECT `state` FROM `".BANDUSER."` WHERE Guid_gap = '$Guid_gap'";
        $result = $this->query($qr);
        $array = [];
        while($rows = mysqli_fetch_row($result)){
            foreach($rows as $row){
                    $array[] = $row;
            }
        }
        return $array;
    }
    public function select_BY_report($Guid_gap,$report){
        $qr = "SELECT `Guid_user` FROM `".BANDUSER."` WHERE Guid_gap = '$Guid_gap' AND report = '$report'";
        $result = $this->query($qr);
        $array = [];
        while($rows = mysqli_fetch_row($result)){
            foreach($rows as $row){
                    $array[] = $row;
            }
        }
        
        
        return $array;
    }
    public function select_BY_reports($report){
        $qr = "SELECT `name` FROM `".BANDUSER."` WHERE report = '$report'";
        $result = $this->query($qr);
        $array = [];
        while($rows = mysqli_fetch_row($result)){
            foreach($rows as $row){
                    $array[] = $row;
            }
        }
        
        
        return $array;
    }
    public function updateState($Guid_gap,$Guid_user,$state){
        $qr = "UPDATE `".BANDUSER."` SET `state`='$state' WHERE Guid_gap = '$Guid_gap' AND `Guid_user` = '$Guid_user'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateReport($Guid_gap,$Guid_user,$report){
        $qr = "UPDATE `".BANDUSER."` SET `report`='$report' WHERE Guid_gap = '$Guid_gap' AND `Guid_user` = '$Guid_user'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateInfo($Guid_gap,$Guid_user,$info){
        $qr = "UPDATE `".BANDUSER."` SET `info`='$info' WHERE Guid_gap = '$Guid_gap' AND `Guid_user` = '$Guid_user'";
        $result = $this->query($qr);
        return $result;
    }
    public function updateName($Guid_gap,$Guid_user,$name){
        $qr = "UPDATE `".BANDUSER."` SET `name`='$name' WHERE Guid_gap = '$Guid_gap' AND `Guid_user` = '$Guid_user'";
        $result = $this->query($qr);
        return $result;
    }
    // update_options
    public function updateMax($Guid_gap,$Max){
        $qr = "UPDATE `".BANDUSER."` SET `Max` = '$Max' WHERE  `Guid_gap`='$Guid_gap'";
        $result = $this->query($qr);
        return $result;
    }
    public function count(){
        $qr = "select * from ".BANDUSER;
        $result = $this->query($qr);
        return mysqli_num_rows($result);
    }
    
} 