<?php
  include_once 'includes/foreleserdbh.inc.php';
$stu_sql = "SELECT two_factor FROM foreleser WHERE foreleser.id=?";
  $stu_stmt = mysqli_stmt_init($connForeleser);
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hjemmeside</title>
</head>
<body>
<main>

<div class="login">
<?php
  
  require_once 'includes/vendor/phpgangsta/googleauthenticator/PHPGangsta/GoogleAuthenticator.php';
$ga = new PHPGangsta_GoogleAuthenticator();
$secret = $ga->createSecret();
$qrCodeUrl = $ga->getQRCodeGoogleUrl('Foreleser', $secret);
$submitHash = password_hash($secret.$_SESSION['user_id'], PASSWORD_DEFAULT);
echo "Scan denn qr koden i Google Authenticator:\n\n";
echo '<img src="'.$qrCodeUrl.'">';
	if (!mysqli_stmt_prepare($stu_stmt, $stu_sql)) {
		echo "SQL statment failed";
	} else {
		mysqli_stmt_bind_param($stu_stmt, "i", $_SESSION['user_id']);
                mysqli_stmt_execute($stu_stmt);
                $stu_result = mysqli_stmt_get_result($stu_stmt);
		while($student_row = mysqli_fetch_assoc($stu_result)) {
			if ($student_row["two_factor"] === 0) {
			echo'
		<form action="includes/foreleserTwoFactor.inc.php" method="POST" autocomplete="off">
		<label>Engangs kode</label>
		<input type"text" name="code">
		<input type="hidden" name="secret" value="'.$secret.'">
		<button type="submit" name="submit" value="'.$submitHash.'">Regitrer to faktor</button>
</form>
                </div>';
		
			}
		}}
			if ($_GET['error'] === 'nyKode') {
                        echo 'Skan den nye QR koden og slett den gamle fra GA';
                        }
?>
<a href="ForeleserHjemmeSide.php"><p>tilbake</p</a>
</main>
</body>
</html>

<style>
.login {
	 display: flex;
	 flex-direction: column;
	 align-items: center
}
.login_form {
	 display: flex;
	 flex-direction: column;
}
</style>
