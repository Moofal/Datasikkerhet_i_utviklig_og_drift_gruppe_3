<?php
$dbServername = "localhost";
$dbUsername = "student";
$dbPassword = "DetRegnetNårDetSkulleSnø3!";
$dbName = "Steg2";

$connStudent = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);
 if(!$connStudent){
	die("Tilkobling Feilet :". mysqli_connect_error());
}

