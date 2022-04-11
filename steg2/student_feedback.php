<?php
include_once 'includes/studentdbh.inc.php';

/*function for validating data, code from w3schools*/
function validate_input($data) {
	$data = trim($data); // removes unnecessary characters
	$data = stripslashes($data); // remove backslashes
	$data = htmlspecialchars($data); // converts specialchar
	return $data;
}

// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
$id = $_SESSION['user_id'];

$datetime = date('Y-m-d H:i:s');
$emne = ''; //set an empty variable
$message_box = ""; //set an empty variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$emne = validate_input($_POST["fag"]);	//Get user input from dropdown
	$message_box = $_POST["message-box"];	//Get user input from textarea
	try{
	$regmatch = preg_match('/<|(\/>)/', $message_box);
	$message_box = validate_input($message_box);
	if($regmatch === 1){
		throw new Exception('scripttag i input fra bruker');
		}
	}
	catch(Exception $e){
	include_once '/var/www/logfiles/studentFeedbackLog.php';
	}
}

$emne_sql_query = "select id from emne where navn = '$emne'";	//sql_query template
$emne_stmt = mysqli_stmt_init($connStudent);	//connecting to database

try{

	if(!mysqli_stmt_prepare($emne_stmt,$emne_sql_query)) {
		throw new Exception('koblings feil');
	}else {
		mysqli_stmt_execute($emne_stmt);	//the prepared statment workes and the connection is executed
		$emneRes = mysqli_stmt_get_result($emne_stmt);	//Get the result from the sql query
		$string_emne = $emneRes->fetch_array()[0]??'';	//Converts the object returned from the query to string
	}

	// Student_id needs to be changed
	$tilbakemelding = "INSERT INTO `tilbakemelding_student` (`id`, `tidspunkt`, `tilbakemelding`, `svar_gitt_foreleser`,`emne_id`, `student_id`) VALUES (NULL, '$datetime', '$message_box', '0','$string_emne', '$id')";
	$tilbakemelding_init = mysqli_stmt_init($connStudent);


	if(!mysqli_stmt_prepare($tilbakemelding_init, $tilbakemelding)) {
		throw new Exception('koblings feil');
	}else{
		mysqli_stmt_execute($tilbakemelding_init);
	}
}catch(Exception $e){
	include_once '/var/www/logfiles/studentFeedbackLog.php';
	header("Location ../StudentHjemmeSide.php?noe=galt");
}
$connStudent->close();	//close the connection to the database

// Change to student homepage
header("Location:StudentHjemmeSide.php");
?>
