<?php
//Define our site URL
define("BASE_PATH", "https://localhost/");

date_default_timezone_set('Asia/Manila');

//Hide all the errors
error_reporting (E_ALL ^ E_NOTICE);

//Define our basepath
$path = "odll/";

//Folders
define("css",	BASE_PATH.$path."application/css/");
define("icons",	BASE_PATH.$path."application/icons/");
define("view",	BASE_PATH.$path."application/view/");
define("img",	BASE_PATH.$path."application/img/");
define("img2",	BASE_PATH.$path."application/img-slideshow/");
define("img3",	BASE_PATH.$path."application/img-teachers/");
define("js",	BASE_PATH.$path."application/js/");
define("fb",	BASE_PATH.$path."application/facebook-sdk-v5/");

//Classes
define("home",		BASE_PATH.$path."home/");
//define("book",		BASE_PATH.$path."search/book/");
define("book",		BASE_PATH.$path."book/");
define("search",	BASE_PATH.$path."search/");
define("profile",	BASE_PATH.$path."profile/");
define("form_addchild",			BASE_PATH.$path."form_addchild/");
define("form_bookmark",			BASE_PATH.$path."form_bookmark/");
define("form_cancelbooking",	BASE_PATH.$path."form_cancelbooking/");
define("form_contactus",		BASE_PATH.$path."form_contactus/");
define("form_inquiretutor",		BASE_PATH.$path."form_inquiretutor/");
define("form_submitapplication",		BASE_PATH.$path."form_submitapplication/");
define("form_updatechild",		BASE_PATH.$path."form_updatechild/");
define("form_updateprofile",	BASE_PATH.$path."form_updateprofile/");

//Take the initial PATH.
$url = $_SERVER['REQUEST_URI'];
//if ($url == "/odll/") $url = "/odll/home/underconstruction";
if ($url == "/odll/") $url = "/odll/home";
$url = str_replace($path,"",$url);


//cria um array com o resto do url
	//$array_tmp_uri = preg_split('[\\/]', $url, -1, PREG_SPLIT_NO_EMPTY);
/*
if ( ! isset( $array_tmp_uri[1])) {
    $array_tmp_uri[1] = null;
}
if ( ! isset( $array_tmp_uri[2])) {
    $array_tmp_uri[2] = null;
}
*/
//cria um array com o resto do url
	$array_tmp_uri = preg_split('[\\/]', $url, -1, PREG_SPLIT_NO_EMPTY);

	//Aqui vamos definir o que щ representa o resto do URL
	$array_uri['controller'] 	= $array_tmp_uri[0]; //a class
	$array_uri['method']		= $array_tmp_uri[1]; //a funчуo
	$array_uri['var']			= $array_tmp_uri[2]; //a variavel

//Load our database
require_once("application/connectdatabase.php");

//Load our base API
require_once("application/base.php");
//require_once __DIR__ . '/application/facebook-sdk-v5/autoload.php';

//loads our controller
$application = new Application($array_uri);
$application->loadController($array_uri['controller']);
?>