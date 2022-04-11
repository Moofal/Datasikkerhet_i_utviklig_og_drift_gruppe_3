<?php
session_start();
// Change this to your connection info.
include_once 'includes/studentdbh.inc.php';
// Try and connect using the info above.
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['username'], $_POST['password']) ) {
	// Could not get the data that should have been sent.
	exit('fyll inn brukernavn og passord!');
}

if (!isset($_SESSION['attempt'])){
	$_SESSION['attempt'] = 0;
}

//check if there are 3 attempts already
if($_SESSION['attempt'] == 3 || $_SESSION['attempt'] > 3){
	$_SESSION['error'] = 'For mange forsøk, prøv igjen om 5 minutter';
	echo "For mange forsøk, prøv igjen om 5 minutter";
}
else{

// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $connStudent->prepare('SELECT id, passord, two_factor FROM student WHERE e_post = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	// Store the result so we can check if the account exists in the database.
	$stmt->store_result();
  if ($stmt->num_rows > 0) {
  	$stmt->bind_result($id, $passord, $two_factor);
  	$stmt->fetch();
  	// Account exists, now we verify the password.
  	// Note: remember to use password_hash in your registration file to store the hashed passwords.
    // HER MÅ DET ENDRES: med linja under, skal funke med hashed passord
    if (password_verify($_POST['password'], $passord)) {
  	//if ($_POST['password'] === $passord) {
  		// Verification success! User has logged-in!
  		// Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
  	$stu_sql = "SELECT two_factor FROM student, studieretning WHERE student.id=? AND student.studieretning_id = studieretning.id;";
	  $stu_stmt = mysqli_stmt_init($connStudent);
        if (!mysqli_stmt_prepare($stu_stmt, $stu_sql)) {
                echo "SQL statment failed";
        } else {
                mysqli_stmt_bind_param($stu_stmt, "i", $id);
                mysqli_stmt_execute($stu_stmt);
                $stu_result = mysqli_stmt_get_result($stu_stmt);
                while($student_row = mysqli_fetch_assoc($stu_result)) {
			if ($student_row["two_factor"] === 1) {
				 $_SESSION['temp_id'] = $id;
				header('LOCATION: studentLogInTwoFactor.php');
				exit();
//gå til to faktor side og set session variabler
                    } else {
			//gå til hjemme side og set session variabler
			session_regenerate_id();
                	$_SESSION['loggedin'] = TRUE;
                	$_SESSION['name'] = $_POST['username'];
			$_SESSION['user_id'] = $id;
			$_SESSION['type'] = "s";
			header('Location: StudentHjemmeSide.php');
			exit();
		    }
		}
	}
  	} else {
  		// Incorrect password
  		echo 'Feil brukernavn eller/og passord1';
  	}
  } else {
  	// Incorrect username
  	echo 'Feil brukernavn eller/og passord2';
	  $_SESSION['attempt'] += 1;
	  //set the time to allow login if third attempt is reach
	  if($_SESSION['attempt'] == 3){
		  $_SESSION['attempt_again'] = time() + (5*60);
		  //note 5*60 = 5mins, 60*60 = 1hr, to set to 2hrs change it to 2*60*60
	  }
	  echo $_SESSION['attempt'];
  }

	$stmt->close();
}
?>
