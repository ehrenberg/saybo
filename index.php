<?php
require_once('config.php');
include('Template.class.php');

header("Content-Type: text/html; charset=utf-8");

if(isset($_GET['p']))
	$get = mysql_real_escape_string($_GET['p']);
else $get=null;

if($get == 3)header("Location: admin.php");

$tpl = new Template();
$tpl->LoadTemplate(PATH_TEMPLATES.PATH_CURRENT_TEMPLATE."index.tpl");

$lang = $tpl->LoadLanguage();
$img = $tpl->LoadImages($config['paths']['file_images']['main']);

$tpl->Assign("website_title","Titel");
$tpl->Assign("time",date("H:i"));
$tpl->Assign("template_folder",PATH_CURRENT_TEMPLATE);

$navigation = $tpl->BuildNavigation('0',$get,$lang);
$tpl->Assign("navigation",$navigation);

$stylesheets = $tpl->SearchForCss(PATH_CURRENT_TEMPLATE);
$tpl->Assign("stylesheets",$stylesheets);

$content = $tpl->GenerateContent($get);
$tpl->Assign("content",$content);

$tpl->display();
mysql_close($con);
?>