<?php
require('Connect.inc.php');

$dbhost = "localhost";
$dbusername = "root";
$dbpassword = "";

$config = array(
    "db" => array(
        "db1" => array(
            "dbname" => "localhost",
            "username" => $dbusername,
            "password" => $dbpassword,
            "host" => $dbhost
        )
    ),
    "paths" => array(
    	"file_images" => array(
        		"main" => "\img_main.php"
        ),
    	"templates" => "/templates",
        "images" => array(
            "content" => $_SERVER["DOCUMENT_ROOT"] . "/images/content",
            "layout" => $_SERVER["DOCUMENT_ROOT"] . "/images/layout"
        ),
    	"templates" => array(),
    	"current_template" => ""
    )
);

$result = mysql_query("SELECT * FROM settings WHERE active=true");

while($row = mysql_fetch_array($result))
{
	$config['paths']['current_template'] = $row['template'].'/';
	$config['paths']['current_template_admin'] = $row['template_admin'].'/';
	$config['paths']['current_language'] = $row['language'];
}
defined("CURRENT_LANGUAGE")
or define("CURRENT_LANGUAGE",$config['paths']['current_language']);

defined("PATH_TEMPLATES")
	or define("PATH_TEMPLATES", 'templates/');
defined("PATH_IMAGES")
	or define("PATH_IMAGES", 'images/');

defined("PATH_CURRENT_TEMPLATE")
    or define("PATH_CURRENT_TEMPLATE",$config['paths']['current_template']);
defined("PATH_CURRENT_TEMPLATE_ADMIN")
    or define("PATH_CURRENT_TEMPLATE_ADMIN",$config['paths']['current_template_admin']);
	
defined("DB_USERNAME")
	or define("DB_USERNAME", $config['db']['db1']['username']);
defined("DB_PASSWORD")
	or define("DB_PASSWORD", $config['db']['db1']['password']);
defined("DB_HOST")
or define("DB_HOST", $config['db']['db1']['host']);

/*
    Error reporting. 
*/  
ini_set("error_reporting", "true");  
error_reporting(E_ALL|E_STRCT);

$GLOBALS['config'];
?>