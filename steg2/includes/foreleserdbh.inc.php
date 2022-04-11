<?php
$dbServername = "localhost";
$dbUsername = "foreleser";
$dbPassword = "ElgenGikkOverElven3!";
$dbName = "Steg2";

$connForeleser = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);
 if(!$connForeleser){
	die("Tilkobling Feilet :". mysqli_connect_error());
}

