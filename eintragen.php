<?php 
require('../Connect.inc.php');
mysql_select_db("homepage") or die ("Datenbank konnte nicht ausgewählt werden"); 

$username = $_POST["username"]; 
$password = $_POST["password"]; 
$password2 = $_POST["password2"];

if($password != $password2 OR $username == "" OR $password == "") 
    { 
    echo "Eingabefehler. Bitte alle Felder korekt ausfüllen. <a href=\"eintragen.html\">Zurück</a>"; 
    exit; 
    } 
$passwort = md5($passwort); 

$result = mysql_query("SELECT id FROM users WHERE name LIKE '$username'"); 
$menge = mysql_num_rows($result); 

if($menge == 0) 
    { 
    $eintrag = "INSERT INTO users (name, password) VALUES ('$username', '$password')"; 
    $eintragen = mysql_query($eintrag); 

    if($eintragen == true) 
        { 
        echo "Benutzername <b>$username</b> wurde erstellt. <a href=\"login.html\">Login</a>"; 
        } 
    else 
        { 
        echo "Fehler beim Speichern des Benutzernames. <a href=\"eintragen.html\">Zurück</a>"; 
        } 


    } 

else 
    { 
    echo "Benutzername schon vorhanden. <a href=\"eintragen.html\">Zurück</a>"; 
    } 
?>