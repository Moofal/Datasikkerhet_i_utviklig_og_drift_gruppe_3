<?php

// Connect to db variable
$db_connection = mysqli_connect("localhost","anton","DatabaseSikkerhet3!","datasikkerhet");

if(isset($_POST['submit_password']) && $_POST['email'] && $_POST['password'])
{
  $email= $_POST['email'];
  $pass = $_POST['password'];

  $sql = "UPDATE foreleser SET passord = ? WHERE md5(e_post) = ? LIMIT 1";
  $stmt = mysqli_stmt_init($db_connection);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
	  header("Location: reset_pass.html?stmt=failed");
	  exit();
  }
  $hasedPwd = password_hash($pass, PASSWORD_BCRYPT);
  mysqli_stmt_bind_param($stmt, "ss", $hasedPwd, $email);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  //$select = mysqli_query($db_connection, "UPDATE foreleser SET passord = md5('$pass') WHERE md5(e_post) = '$email' LIMIT 1");
  echo '<p>Passordet er skiftet på din brukerkonto :)...kanskje</p>';
  header("Location: login_foreleser.html?pass=reset");
  exit();
} else {
  echo '<p>Get rekt son.</p>';
  echo '<iframe width="420" height="315" src="https://www.youtube.com/watch?v=dQw4w9WgXcQ"></iframe>';
}
?>
