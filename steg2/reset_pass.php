<?php

// Connect to db variable
$db_connection = mysqli_connect("localhost","anton","DatabaseSikkerhet3!","datasikkerhet");

if($_GET['key'] && $_GET['reset'])
{
  $email = $_GET['key'];
  $pass = $_GET['reset'];
  $select = mysqli_query($db_connection, "SELECT e_post,passord FROM foreleser WHERE md5(e_post) = '$email' AND md5(passord) = '$pass' LIMIT 1");

  if(mysqli_num_rows($select)==1)   
  {
    ?>
    <form method="post" action="submit_new.php">
    <input type="hidden" name="email" value="<?php echo $email;?>">
    <p>Skriv det nye passordet ditt:</p>
    <input type="password" name="password">
    <input type="submit" name="submit_password">
    </form>
    <?php
  } else {
    echo '<p>Get lost.</p>';
  }
} else {
  echo '<p>Nope.</p>';
}
?>