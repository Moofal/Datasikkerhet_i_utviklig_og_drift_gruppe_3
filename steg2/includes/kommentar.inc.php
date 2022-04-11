<?php
include_once 'foreleserdbh.inc.php';
session_start();

function validate_input($data) {
        $data = trim($data); // removes unnecessary characters
        $data = stripslashes($data); // remove backslashes
        $data = htmlspecialchars($data); // converts specialchar
        return $data;
}


if (isset($_POST["submit"])) {
	 $tidspunkt = mysqli_real_escape_string($connForeleser, date("Y-m-d"));
	 $svar = mysqli_real_escape_string($connForeleser, validate_input($_POST['svar']));
	 $foreleser_id = mysqli_real_escape_string($connForeleser, $_SESSION['user_id']);
         $tilbakemelding_student_id = mysqli_real_escape_string($connForeleser, $_SESSION['tm']);
		 
	 if (!empty($svar)) {
		 try {
			 if (!preg_match('/^[a-åA-Å]*$/',  $svar)) {
			 	include_once '/var/www/logfiles/kommentar.php';
			 }
            $sql = "INSERT INTO svar_foreleser (tidspunkt, svar, tilbakemelding_student_id, foreleser_id)
               		 VALUES (?, ?, ?, ?);";
                 $studentTableSet = "UPDATE tilbakemelding_student SET svar_gitt_foreleser = '1' WHERE tilbakemelding_student.id = ?;";

                $stmtIn = mysqli_stmt_init($connForeleser);
                $stmtSt = mysqli_stmt_init($connForeleser);
                        if(!mysqli_stmt_prepare($stmtIn, $sql)) {
				throw new Exception(mysqli_stmt_error);
                                exit();
                        } else {
                                mysqli_stmt_bind_param($stmtIn, "ssii", $tidspunkt, $svar, $tilbakemelding_student_id, $foreleser_id);
                                mysqli_stmt_execute($stmtIn);

                                if (!mysqli_stmt_prepare($stmtSt, $studentTableSet)) {
					throw new Exception(mysqli_stmt_error);
					exit();
                                } else {
                                        mysqli_stmt_bind_param($stmtSt, "i", $tilbakemelding_student_id);
                                        mysqli_stmt_execute($stmtSt);
                                }
			}
			header("LOCATION: ../ForeleserKomentar.php?svar=sent");
			exit();
		 } catch (Exception $e) {
			 include_once '/var/www/logfiles/exceptionLogger.php';
			 header("LOCATION: ../ForeleserKomentar.php?request=bad");
		 }
	 	} else {
			header("LOCATION: ../ForeleserKomentar.php?svar=empty");
			exit();
		}
	} else {
		header("LOCATION: ../ForeleserKomentar.php?button=notClicked");
		exit();
	}
?>
