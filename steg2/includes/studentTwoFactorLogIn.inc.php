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

	if (preg_match("/^[0-9]{6}$/", $code)) {
		 require_once 'vendor/phpgangsta/googleauthenticator/PHPGangsta/GoogleAuthenticator.php';
                 $ga = new PHPGangsta_GoogleAuthenticator();
		 $sql = "SELECT secret FROM student_secret WHERE student_id = ?;";
		 $stmt = mysqli_stmt_init($connStudent);
		 if(!mysqli_stmt_prepare($stmt, $sql)) {
		 	 header("LOCATION: ../studentLogInTwoFactor.php?request=bad");
		 } else {
		 	mysqli_stmt_bind_param($stmt, "i",  $_SESSION['temp_id']);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			while ($row = mysqli_fetch_assoc($result)) {
				$secret = $row['secret'];
				$checkResult = $ga->verifyCode($secret, $code, 4);
				if ($checkResult){
					session_regenerate_id();
                        		$_SESSION['loggedin'] = TRUE;
                        		$_SESSION['name'] = $_POST['username'];
                        		$_SESSION['user_id'] = $_SESSION['temp_id'];
					$_SESSION['type'] = "s";
					unset($_SESSION['temp_id']);
                        		header('Location: ../StudentHjemmeSide.php');
                        		exit();
				} else {
					header('LOCATION: ../studentLogInTwoFactor.php');
				}
			}
		 }
	} else {
		header('LOCATION: ../studentLogInTwoFactor.php');
	}

} else {
	header('LOCATION: ../studentLogInTwoFactor.php');
}


