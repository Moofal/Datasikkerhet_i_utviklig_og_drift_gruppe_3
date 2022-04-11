<?php

function validate_input($data) {
        $data = trim($data); // removes unnecessary characters
        $data = stripslashes($data); // remove backslashes
        $data = htmlspecialchars($data); // converts specialchar
        return $data;
}

if(isset($_POST['submit'])) {
	session_start();
	include_once 'studentdbh.inc.php';
	
	$code = mysqli_real_escape_string($connStudent, validate_input($_POST['code']));
	$secret = validate_input($_POST['secret']);
	$user_id = mysqli_real_escape_string($connStudent, $_SESSION['user_id']);
	$verify = $secret.$user_id;
		
		if (password_verify($verify, $_POST['submit'])){
			if (preg_match("/^[0-9]{6}$/", $code)) {
				require_once 'vendor/phpgangsta/googleauthenticator/PHPGangsta/GoogleAuthenticator.php';
				$ga = new PHPGangsta_GoogleAuthenticator();
				$checkResult = $ga->verifyCode($secret, $code, 4);
				if ($checkResult){
					try{
			$student_secret_sql = "INSERT INTO student_secret (secret, student_id)
						VALUES (?, ?);";
			$student_update_factor = "UPDATE student SET two_factor = '1' WHERE student.id = ?;";
			$stmt_secret = mysqli_stmt_init($connStudent);
			$stmt_update = mysqli_stmt_init($connStudent);
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
                        header("LOCATION: ../StudentHjemmeSide.php?tofaktor=registrert");
                        exit();
					} catch (Exception $e) {
                        	 include_once '/var/www/logfiles/exceptionLogger.php';
                         	header("LOCATION: ../StudentToFaktor.php?request=bad");
                	 	}
				} else {
				header('LOCATION: ../StudentToFaktor.php?error=nykode');
		  	      exit();
	
			}
		} else {
			header('LOCATION: ../StudentToFaktor.php?error=nykode');
        		exit();
		}
	} else {
		header('LOCATION: ../StudentToFaktor.php?error=nykode');
                exit();
	}
} else {
	header('LOCATION: ../StudentToFaktor.php?error=nykode');
	exit();
}
