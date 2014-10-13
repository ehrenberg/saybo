<?php
$con = mysql_connect("localhost","root","");
if (!$con)
{
	die('Konnte keine Verbindung zur Datenbank aufbauen: ' . mysql_error());
}
mysql_select_db("saybo", $con);
?>