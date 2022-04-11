<?php

function validate_input($data) {
        $data = trim($data); // removes unnecessary characters
        $data = stripslashes($data); // remove backslashes
        $data = htmlspecialchars($data); // converts specialchar
        return $data;
}

if(isset($_POST['submit'])) {
	session_start();
	include_once 'foreleserdbh.inc.php';
	
	$code = mysqli_real_escape_string($connForeleser, validate_input($_POST['code']));
	$secret = validate_input($_POST['secret']);
	$user_id = mysqli_real_escape_string($connForeleser, $_SESSION['user_id']);
	$verify = $secret.$user_id;
		
		if (password_verify($verify, $_POST['submit'])){
			if (preg_match("/^[0-9]{6}$/", $code)) {
				require_once 'vendor/phpgangsta/googleauthenticator/PHPGangsta/GoogleAuthenticator.php';
				$ga = new PHPGangsta_GoogleAuthenticator();
				$checkResult = $ga->verifyCode($secret, $code, 4);
				if ($checkResult){
					try{
			$student_secret_sql = "INSERT INTO foreleser_secret (secret, foreleser_id)
						VALUES (?, ?);";
			$student_update_factor = "UPDATE foreleser SET two_factor = '1' WHERE foreleser.id = ?;";
			$stmt_secret = mysqli_stmt_init($connForeleser);
			$stmt_update = mysqli_stmt_init($connForeleser);
			if(!mysqli_stmt_prepare($stmt_secret, $student_secret_sql)) {
                                throw new Exception(mysqli_stmt_error);
                                exit();
                        } else {
                                mysqli_stmt_bind_param($stmt_secret, "si", $secret,$user_id);
                                mysqli_stmt_execute($stmt_secret);

                                if (!mysqli_stmt_prepare($stmt_update, $student_update_factor)) {
                                        throw new Exception(mysqli_stmt_error);
                                        exit();
				} else {
                                        mysqli_stmt_bind_param($stmt_update, "i", $user_id);
                                        mysqli_stmt_execute($stmt_update);
                                }
                        }
                        header("LOCATION: ../ForeleserHjemmeSide.php?tofaktor=registrert");
                        exit();
					} catch (Exception $e) {
                        	 include_once '/var/www/logfiles/exceptionLogger.php';
                         	header("LOCATION: ../ForeleserToFaktor.php?request=bad");
                	 	}
				} else 
				header('LOCATION: ../ForeleserToFaktor.php?error=nykode');
		  	      exit();
	
			} else {
        		header('LOCATION: ../ForeleserToFaktor.php?error=nykode');
        		exit();
			}
		} else {
			header('LOCATION: ../ForeleserToFaktor.php?error=nykode');
        		exit();
		}
	} else {
		header('LOCATION: ../ForeleserToFaktor.php?error=nykode');
                exit();
	}

