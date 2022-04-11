<?php
session_start();
include_once "gjestdbh.inc.php";

if (isset($_POST["submit"])) {

	if (!isset($_SESSION['gjest_komentar'])){
        $_SESSION['gjest_komentar'] = 0;
}
if(isset($_SESSION['gjest_komentar_again'])){
        $now = time();
        if($now >= $_SESSION['gjest_komentar_again']){
		unset($_SESSION['gjest_komentar']);
		unset($_SESSION['gjest_komentar_again']);
	}
}

if($_SESSION['gjest_komentar'] === 5 || $_SESSION['gjest_komentar'] > 5){
        $_SESSION['error'] = 'For mange forsøk, prøv igjen om 5 minutter';
	echo "For mange forsøk, prøv igjen om 5 minutter";
	header("location: ../gjesteskrivekommentar.php?svar=formange");
	exit();
} else {
	$tidspunkt = date("Y-m-d H:i:s");
	$kommentar = $_POST["svar"];
	$tilbakemeldingstudentID = $_SESSION['gtm'];


	$sql = "INSERT INTO kommentar_gjest (tidspunkt,kommentar,tilbakemelding_student_id)
        VALUES (?,?,?);";

	$stmt = mysqli_stmt_init($connGjest);

	if(!mysqli_stmt_prepare($stmt, $sql)){
    	die("Statement did not prepare");

	}else{
    	mysqli_stmt_bind_param($stmt, "ssi", $tidspunkt, $kommentar, $tilbakemeldingstudentID);
	mysqli_stmt_execute($stmt);
	$_SESSION['gjest_komentar'] += 1;
          //set the time to allow login if third attempt is reach
          if($_SESSION['gjest_komentar'] === 5){
                  $_SESSION['gjest_komentar_again'] = time() + (5*60);
                  //note 5*60 = 5mins, 60*60 = 1hr, to set to 2hrs change it to 2*60*60
          }

	header("location: ../gjesteskrivekommentar.php?svar=sent");
	exit();
	}
}
} else {
	header("location: ../gjesteskrivekommentar.php?button=notClicked");
	exit();	
}

