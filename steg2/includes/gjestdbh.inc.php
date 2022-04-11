<?php
$dbServername = "localhost";
$dbUsername = "gjest";
$dbPassword = "MammaSendteMelding3!";
$dbName = "Steg2";

$connGjest = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);
 if(!$connGjest){
	die("Tilkobling Feilet :". mysqli_connect_error());
}

