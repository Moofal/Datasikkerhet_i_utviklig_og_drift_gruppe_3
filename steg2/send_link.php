<?php
$email = $_POST['email'];
$unhashed_email = $_POST['email'];

if(isset($_POST['submit_email']) && $_POST['email']) {

  // Connect to db variable
  $db_connection = mysqli_connect("localhost","anton","DatabaseSikkerhet3!","datasikkerhet");

  // Sql query
  $select = mysqli_query($db_connection, "SELECT e_post,passord FROM foreleser WHERE e_post = '$email' LIMIT 1");
  if(mysqli_num_rows($select)==1) { 

    while($row=mysqli_fetch_array($select))
    {
      // MD5 Hash
      $email = md5($row['e_post']);
      $pass = md5($row['passord']);
    }

    // Send mail
    ini_set( 'display_errors', 1 );
    error_reporting( E_ALL );

    $url = "http://158.39.188.203/steg1/reset_pass.php?key=". $email ."&reset=".$pass;
    
    $to = $unhashed_email;
    $subject = "Tilbakestill ditt passord - HTT";
    
    $message = '<p>Du har bedt om å tilbakestille ditt passord.</p>';
    $message .= '<p>Her er linken for å tilbakestille: </br>';
    $message .= '<a href="'. $url .'">Klikk her for å tilbakestille ditt passord.</a></p>';
    
    $headers = "From: Gruppe3 <datasikkerhet.gruppe3@gmail.com>\r\n";
    $headers .= "Reply-To: datasikkerhet.gruppe3@gmail.com\r\n";
    $headers .= "Content-type: text/html\r\n"; 

    $result = mail($to, $subject, $message, $headers);

    if($result == true) {
      header("Location: index.php");
    } else {
      echo '<p>Mailen ble ikke sendt! :(</p>';
    }
  } else {
    echo '<p>Email does not exist.</p>';
  }
}
else {
  echo '<h1>Get rekt son.</h1>';
  echo '<iframe width="420" height="315" src="https://www.youtube.com/watch?v=dQw4w9WgXcQ"></iframe>';
}
?>
