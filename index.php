<?php
/**
 * Created by PhpStorm.
 * User: Александр Пенко
 * Date: 26.05.2017
 * Time: 17:14
 */


error_reporting(E_ALL|E_STRICT);
date_default_timezone_set('Europe/Moscow');
set_include_path('.'.PATH_SEPARATOR . './library'
    .PATH_SEPARATOR.'./application/models/'
    .PATH_SEPARATOR.get_include_path()
    .PATH_SEPARATOR.''
    .PATH_SEPARATOR.'./library/Pubnub');

include "Zend/Loader.php";
Zend_Loader::loadClass('Zend_Controller_Front');
Zend_Loader::loadClass('Zend_Config_Ini');
Zend_Loader::loadClass('Zend_Registry');
Zend_Loader::loadClass('Zend_Db');
Zend_Loader::loadClass('Zend_Db_Table');

//connect to PubNub
include "library/autoloader.php";
require_once('/library/autoloader.php');
use Pubnub\Pubnub;

$pubnub = new Pubnub('pub-c-b9220392-f254-438f-b3b1-7d0bc16a5596', 'sub-c-4e8b4b84-4492-11e7-b847-0619f8945a4f');



// load configuration
$config = new Zend_Config_Ini('./config.ini', 'general');
$registry = Zend_Registry::getInstance();
$registry->set('config', $config);
$registry->set('pubnub', $pubnub);

// setup database
$db = Zend_Db::factory($config->db->adapter,
    $config->db->config->toArray());
Zend_Db_Table::setDefaultAdapter($db);

// setup controller
$frontController = Zend_Controller_Front::getInstance();
$frontController->throwExceptions(true);
$frontController->setControllerDirectory('./application/controllers');

// run!
$frontController->dispatch();