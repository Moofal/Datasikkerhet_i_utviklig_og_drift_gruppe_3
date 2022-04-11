	<?php 

	if (isset($_POST["submit"])){
		require_once 'studentdbh.inc.php';
		require_once 'functions.inc.php';
		$navn = mysqli_real_escape_string ($connStudent, $_POST["navn"]);
		$etternavn = mysqli_real_escape_string($connStudent, $_POST["etternavn"]);
		$e_post = mysqli_real_escape_string($connStudent, $_POST["e_post"]); 
		$passord = mysqli_real_escape_string($connStudent,$_POST["passord"] );
		$Bpassord = mysqli_real_escape_string($connStudent, $_POST["Bpassord"]);
		$studiekull = mysqli_real_escape_string($connStudent, $_POST["studiekull"]);
		$studieretning = mysqli_real_escape_string($connStudent, $_POST["studieretning_id"]);
		$fulltnavn = $navn . $etternavn;

		if (!isset($_SESSION['bruker_lagd'])){
                        $_SESSION['bruker_lagd'] = 0;
                }
                if(isset($_SESSION['bruker_lagd_again'])){
                        $now = time();
                        if($now  >= $_SESSION['bruker_lagd_again']){
                                unset($_SESSION['bruker_lagd']);
                                unset($_SESSION['bruker_lagd_again']);
                        }
                }

                if($_SESSION['bruker_lagd'] === 5 || $_SESSION['bruker_lagd'] > 5){
                        header("location: ../Reg2for.php?error=vent5min");
                        exit();
                } else {

		if(emptyInputSignup($navn, $etternavn, $e_post, $passord, $Bpassord, $studiekull, $studieretning) !== false){
			header ("location: ../Reg2.php?error=emptyinput");
			exit();
		}
		if (invalidUid($fulltnavn) !== false){
			header("location: ../Reg2.php?error=invalidUid");
			exit();
			}
		if (invalidepost($e_post) !== false){
			header("location: ../Reg2.php?error=invalidepost");
			exit();
		}
		if (passordulike($passord, $Bpassord) !== false){
			header("location: ../Reg2.php?error=passordulike");
			exit();
		}
		if (eposttatt($connStudent, $e_post) !== false){
			header("location: ../Reg2.php?error=eposttatt");
			exit();
			} 
		if (passordkort($passord)!== false){
			header("location: ../Reg2.php?error=passordetforkort");
			exit();
} 
			
	   createUser($connStudent, $navn, $etternavn, $e_post, $passord, $studiekull, $studieretning);
	    $_SESSION['bruker_lagd'] += 1;
          //set the time to allow login if third attempt is reach
          if($_SESSION['bruker_lagd'] === 5){
                  $_SESSION['bruker_lagd_again'] = time() + (5*60);
                  //note 5*60 = 5mins, 60*60 = 1hr, to set to 2hrs change it to 2*60*60
          }
		}
	}
	else {
			header("location: ../Reg2.php");
			exit();
	}
		
