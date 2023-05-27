<?php

//CREATE TABLE `alirezaz_Robots`.`chalesh` ( `id` INT NOT NULL AUTO_INCREMENT , `QU` VARCHAR(5000) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

// define('SERVER_NAME','localhost:3306');

// define("USER_NAME","alirezaz");

// define("USER_PASSWORD","Ab_4317247");

// define("DB_NM","alirezaz_Robots");

// define("ROBOTS","BIO");

class lib_bio{
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
        if(empty($qr)){
            return "qurery is empty";
        }
        $result = mysqli_query($link,$qr);
        mysqli_close($link);
        return $result;
    }
    // INSERT INTO `BIO` (`id`, `username`, `password`, `Date`) VALUES (NULL, 'username', 'password', 'Date ')
    public function insert($QU){
        $qr = "INSERT INTO `".BIO."` (`id`, `QU`) VALUES (NULL, '$QU');";
        $result = $this->query($qr);
        return $result;
    }

    public function select($id){
        $qr = "SELECT `QU` FROM `".BIO."` WHERE id = '$id'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }

    public function delete($id){
        $qr = "DELETE FROM `".BIO."` WHERE id = '$id'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }    
    // INSERT INTO `BIO` (`id`, `username`, `password`, `Date`) VALUES (NULL, 'username', 'password', 'Date ')
    
    public function update($id,$QU){
        $qr = "UPDATE `".BIO."` SET `QU`='$QU' WHERE `id`='$id'";
        $result = $this->query($qr);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    public function count(){
        $qr = "select * from ".BIO;
        $result = $this->query($qr);
        return mysqli_num_rows($result);
    }
    
} 