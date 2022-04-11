<?php
		
if (isset($_POST["submit"])){
		require_once 'foreleserdbh.inc.php';
		require_once 'functions.inc.php';
		
		$navn = mysqli_real_escape_string ($connForeleser, $_POST["navn"]);
		$etternavn = mysqli_real_escape_string($connForeleser, $_POST["etternavn"]);
		$e_post = mysqli_real_escape_string($connForeleser, $_POST["e_post"]); 
		$passord = mysqli_real_escape_string($connForeleser,$_POST["passord"] );
		$Bpassord = mysqli_real_escape_string($connForeleser, $_POST["Bpassord"]);
		$emneListe = $_POST["emne_liste"];
		$file = $_FILES["file"];
		$fulltnavn = $navn.$etternavn;
		
		if (!isset($_SESSION['bruker_lagd'])){
        		$_SESSION['bruker_lagd'] = 0;
		}
		if(isset($_SESSION['bruker_lagd_again'])){
        		$now = time();
        		if($now	 >= $_SESSION['bruker_lagd_again']){
                		unset($_SESSION['bruker_lagd']);
                		unset($_SESSION['bruker_lagd_again']);
        		}
		}

		if($_SESSION['bruker_lagd'] === 5 || $_SESSION['bruker_lagd'] > 5){
			header("location: ../Reg2for.php?error=vent5min");
			exit();
		} else {


		 if(emptyInputSignupForeleser($navn, $etternavn, $e_post, $passord, $Bpassord, $file, $emneListe) !== false){
			 header ("location: ../Reg2for.php?error=emptyinput");
			exit();
		}
		
		if (imgAllowed($file) !== false){			
			include_once '/var/www/logfiles/Reglogg.php'; 
			header("location: ../Reg2for.php?error=imgInvalid");
			exit();
		}
	
		if (invalidUid($fulltnavn) !== false){
			include_once '/var/www/logfiles/Reglogg.php';
			header("location: ../Reg2for.php?error=invalidUid");
			exit();
			} 
		
		if (invalidepost($e_post) !== false){
			include_once '/var/www/logfiles/Reglogg.php';
			header("location: ../Reg2for.php?error=invalidepost");
			exit();
		}
		
		if (passordulike($passord, $Bpassord) !== false){
		        header("location: ../Reg2for.php?error=passordulike");
			exit();
		}

		if (passordkort($passord) !== false){
			header("location: ../Reg2for.php?error=passordforkort");
			exit();		
}
		
		if (eposttattFor($connForeleser, $e_post) !== false){
			header("location: ../Reg2for.php?error=eposttatt");
			exit();
		}	
	$two_factor = "0";	
	   createForeleser($connForeleser, $navn, $etternavn, $e_post, $passord, $two_factor);
		$userExists = eposttattFor ($connForeleser, $e_post);
		$foreleserid = $userExists["id"];
		registrerEmner($connForeleser, $foreleserid, $emneListe);
		registrerBilde($connForeleser, $file, $foreleserid);
		$_SESSION['bruker_lagd'] += 1;
          //set the time to allow login if third attempt is reach
          if($_SESSION['bruker_lagd'] === 5){
                  $_SESSION['bruker_lagd_again'] = time() + (5*60);
                  //note 5*60 = 5mins, 60*60 = 1hr, to set to 2hrs change it to 2*60*60
          }
		
		}	
}
	
	else {
			header("location: ../Reg2for.php");
			exit();
	} 

