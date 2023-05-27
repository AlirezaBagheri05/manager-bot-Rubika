<?php

// $Main_dir = dirname(dirname(__DIR__));

// define('SITE_URL',$Main_dir.'/');

$main = SITE_URL.'WebSocket/';
$mini = SITE_URL.'WebSocket/Message/';
$log = SITE_URL.'WebSocket/log/';
$Phrity = SITE_URL.'WebSocket/Phrity/';
$Uri = SITE_URL.'WebSocket/Uri/';

// Message

include_once($mini.'Message.php');
include_once($mini.'Binary.php');
include_once($mini.'Close.php');
include_once($mini.'Factory.php');
include_once($mini.'Ping.php');
include_once($mini.'Pong.php');
include_once($mini.'Text.php');

// Uri 

include_once($Uri.'MessageInterface.php');
include_once($Uri.'RequestInterface.php');
include_once($Uri.'ResponseInterface.php');
include_once($Uri.'ServerRequestInterface.php');
include_once($Uri.'StreamInterface.php');
include_once($Uri.'UploadedFileInterface.php');
include_once($Uri.'UriInterface.php');

// Phrity

include_once($Phrity.'ErrorHandler.php');
include_once($Phrity.'Uri.php');
include_once($Phrity.'UriFactory.php');


// log

include_once($log.'LoggerInterface.php');
include_once($log.'InvalidArgumentException.php');
include_once($log.'LoggerAwareInterface.php');
include_once($log.'LoggerAwareTrait.php');
include_once($log.'LoggerTrait.php');
include_once($log.'LogLevel.php');
include_once($log.'AbstractLogger.php');
include_once($log.'NullLogger.php');



// in WebSocket

include_once($main.'BadOpcodeException.php');
include_once($main.'BadUriException.php');
include_once($main.'Client.php');
include_once($main.'Connection.php');
include_once($main.'ConnectionException.php');
include_once($main.'Exception.php');
include_once($main.'OpcodeTrait.php');
include_once($main.'Server.php');
include_once($main.'TimeoutException.php');

// main 


require_once(SITE_URL.'crypto.php');
include_once(SITE_URL.'lib_rubika.php');
include_once(SITE_URL.'lib_LinkRemover.php');
include_once(SITE_URL.'lib_UsersBands.php');
include_once(SITE_URL.'lib.php');
include_once(SITE_URL.'lib_Chalesh.php');

include_once(SITE_URL.'lib_bio.php');
include_once(SITE_URL.'lib_etraf.php');
include_once(SITE_URL.'lib_jock.php');
include_once(SITE_URL.'lib_text.php');
include_once(SITE_URL.'lib_fact.php');

include_once(SITE_URL.'lib_answers.php');
include_once(SITE_URL.'lib_speaking.php');