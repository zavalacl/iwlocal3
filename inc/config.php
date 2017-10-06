<?php

/**
 * Auto loader
 */
require(realpath(__DIR__ . '/../') . '/vendor/autoload.php');


/* Load .env */
$dotenv = new Dotenv\Dotenv(realpath(__DIR__ . '/../'));
$dotenv->load();


/* Start Session */
@session_start();

	
define("HOME_ROOT", realpath(dirname(__FILE__) . '/../') . '/');
define("FILES_ROOT", realpath(dirname(__FILE__) . '/../../../') . '/files/iwlocal3.com/');
define("WEB_ROOT", "/");
define("WEB_ADDRESS", getenv('HTTP_ADDRESS'));

define('DB_USER', getenv('DB_USER'));
define('DB_PASS', getenv('DB_PASS'));
define('DB_HOST', getenv('DB_HOST'));
define('DB_NAME', getenv('DB_NAME'));

define('LOCAL_NUMBER', 3);

define('ENCRYPT_KEY', getenv('ENCRYPT_KEY'));

define('RECAPTCHA_PUBLIC_KEY', getenv('RECAPTCHA_PUBLIC_KEY'));
define('RECAPTCHA_PRIVATE_KEY', getenv('RECAPTCHA_PRIVATE_KEY'));
	


/* View Mobile or Full Version? */
include('mobile.php');


/* General */
require_once("constants.php");
require_once("mysqli-connect.php");
require_once("functions/general.php");
require_once("functions/sql_functions.php");
require_once("functions/validate.php");
require_once("functions/users.php");


/* Image Manipulation */
require_once("classes/Image.php");


/* Form Validation */
require_once("classes/Validator.php");


/* Alerts/Messages */
require_once("classes/Alert.php");
$alerts = new Alert();
