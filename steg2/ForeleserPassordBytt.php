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
$submitHash = password_hash("qwerty".$_SESSION['user_id'], PASSWORD_DEFAULT);
	if (!mysqli_stmt_prepare($stu_stmt, $stu_sql)) {
		echo "SQL statment failed";
	} else {
		mysqli_stmt_bind_param($stu_stmt, "i", $_SESSION['user_id']);
                mysqli_stmt_execute($stu_stmt);
                $stu_result = mysqli_stmt_get_result($stu_stmt);
		while($student_row = mysqli_fetch_assoc($stu_result)) {
			if ($student_row["two_factor"] === 1) {
			echo'
		<form action="includes/foreleserNewPassword.inc.php" method="POST" autocomplete="off">
		<label>Gamle Passord</label>
		<input type="password" name="oldpassword">
		<label>Nytt passord</label>
                <input type="password" name="newpassword">
		<label>Gjenta nytt passord</label>
		<input type"password" name="newpasswordrep">
		<label>GA Engangs kode</label>
                <input type="text" name="code">
		<button type="submit" name="submit" value="'.$submitHash.'">Bytt passord</button>
</form>
                </div>';
		
			} else {
				echo 'Aktiver to faktor først!';
			}
		}}
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
