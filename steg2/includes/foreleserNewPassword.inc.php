<?php
session_start();
// Change this to your connection info.
include_once 'foreleserdbh.inc.php';

if (isset($_POST['submit'])) {
require_once 'functions.inc.php';	
$oldPassword = mysqli_real_escape_string ($connForeleser, $_POST["oldpassword"]);
$newPassword = mysqli_real_escape_string ($connForeleser, $_POST["newpassword"]);
$newPasswordRep = mysqli_real_escape_string ($connForeleser, $_POST["newpasswordrep"]);
$code = mysqli_real_escape_string ($connForeleser, $_POST["code"]);

	if (password_verify("qwerty".$_SESSION['user_id'], $_POST['submit'])) {

		if (passordulike($newPassword, $newPasswordRep) !== false){
                        header("location: ../ForeleserPassordBytt.php?error=passordulike");
                        exit();
                }

                if (passordkort($newPassword) !== false){
                        header("location: ../ForeleserPassordBytt.php?error=passordforkort");
                        exit();
		}
		require_once 'vendor/phpgangsta/googleauthenticator/PHPGangsta/GoogleAuthenticator.php';
                                $ga = new PHPGangsta_GoogleAuthenticator();
		if ($stmt = $connForeleser->prepare('SELECT id, passord, secret 
					FROM foreleser fo
					INNER JOIN foreleser_secret fs ON fo.id = fs.foreleser_id 
					WHERE id = ?;')) {
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
        $stmt->bind_param('s', $_SESSION['user_id']);
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();

  if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $passord, $secret);
        $stmt->fetch();
        // Account exists, now we verify the password.
        // Note: remember to use password_hash in your registration file to store the hashed passwords.
    // HER MÅ DET ENDRES: med linja under, skal funke med hashed passord
	if (password_verify($oldPassword, $passord)) {
		$checkResult = $ga->verifyCode($secret, $code, 4);
		if ($checkResult){ 
			$sql = "UPDATE foreleser SET passord = ? WHERE foreleser.id = ?;";
			$stmt_update = mysqli_stmt_init($connForeleser);
			if(!mysqli_stmt_prepare($stmt_update, $sql)) {
                                header("location: ../ForeleserPassordBytt.php");
                                exit();
			} else {
				$hassedPassword = password_hash($newPassword, PASSWORD_BCRYPT );
			 mysqli_stmt_bind_param($stmt_update, "si", $hassedPassword, $_SESSION['user_id']);
			 mysqli_stmt_execute($stmt_update);
			 header("location: ../ForeleserPassordBytt.php?submit=done");
                        exit();
			}
		} else {
		header("location: ../ForeleserPassordBytt.php?GA=tryagain");
                        exit();
		}
	} else {
	header("location: ../ForeleserPassordBytt.php?password=wrong");
                        exit();
	}
  }
		}
	} else {
	header("location: ../ForeleserPassordBytt.php?submit=wrong");
                        exit();
	}
} else {
header("location: ../ForeleserPassordBytt.php?submit=notclicked");
                        exit();
}

