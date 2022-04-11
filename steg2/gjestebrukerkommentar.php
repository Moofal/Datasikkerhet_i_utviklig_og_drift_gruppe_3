<?php 
session_start();
include_once "includes/gjestdbh.inc.php";
include_once "includes/studentdbh.inc.php";
include_once "includes/foreleserdbh.inc.php";
$sql = "SELECT ts.id, ts.tilbakemelding 
		FROM Steg2.tilbakemelding_student ts
		INNER JOIN Steg2.emne ON ts.emne_id = emne.id
		WHERE pin_kode=?;";
$stmt = mysqli_stmt_init($connStudent);

$foi_sql = "SELECT Steg2.emne.navn, Steg2.bilde.file_destination, Steg2.bilde.bilde_navn
				FROM Steg2.bilde 
				INNER JOIN Steg2.foreleser fo ON fo.id = Steg2.bilde.foreleser_id
				INNER JOIN Steg2.foreleser_has_emne fhe ON fo.id = fhe.foreleser_id
				INNER JOIN Steg2.emne ON Steg2.emne.id = fhe.emne_id
				WHERE Steg2.emne.pin_kode = ?;";
$foi_stmt = mysqli_stmt_init($connForeleser);

$arrayOfTmId = array();
    $nrOfTm = 0;
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Gjestebruker </title>
    <link rel="stylesheet" href="stil.css">
    </head>

        <body>
		<?php
	        //Prepare the prepared statement
	        if (!mysqli_stmt_prepare($foi_stmt, $foi_sql)){
		    echo "SQL statment failed";
	    }	else {
		    //Bind parameters to the placeholder
		    mysqli_stmt_bind_param($foi_stmt, "i", $_SESSION["PIN"]);
		    //Run parameters inside database
		    mysqli_stmt_execute($foi_stmt);
		    $result = mysqli_stmt_get_result($foi_stmt);
		    while ($row = mysqli_fetch_assoc($result)) {
			    echo 
				    '<div>
					   <img src="http://158.39.188.203/steg1/uploads/',$row["file_destination"],'" alt="',$row["bilde_navn"],'">
				    </div>';
		}
	}
	?>

        <header>
        <h2> Gjesteinnlogging </h2>
        <form action="includes/logout.php" method="POST">
		<button type="submit" name="submitLogout">Logg ut</button>
		</form>
        </header>

<?php
                //Prepare the prepared statement
                if (!mysqli_stmt_prepare($foi_stmt, $foi_sql)){
                    echo "SQL statment failed";
            }   else {
                    //Bind parameters to the placeholder
                    mysqli_stmt_bind_param($foi_stmt, "i", $_SESSION["PIN"]);
                    //Run parameters inside database
                    mysqli_stmt_execute($foi_stmt);
                    $result = mysqli_stmt_get_result($foi_stmt);
                    while ($row = mysqli_fetch_assoc($result)) {
                            echo
                                    '<h2>',$row["navn"],'</h2>';
                }
        }
        ?>
        
            <?php
	        //Prepare the prepared statement
	        if (!mysqli_stmt_prepare($stmt, $sql)){
		    echo "SQL statment failed";
	    }	else {
		    //Bind parameters to the placeholder
		    mysqli_stmt_bind_param($stmt, "i", $_SESSION["PIN"]);
		    //Run parameters inside database
		    mysqli_stmt_execute($stmt);
		    $st_result = mysqli_stmt_get_result($stmt);
		
		    while ($st_row = mysqli_fetch_assoc($st_result)) {
			    array_push($arrayOfTmId, $st_row["id"]);
			    echo 
				    '<div class="melding">
					    <p>', $st_row["tilbakemelding"], '</p>
					    <form method="POST">
						    <input type="hidden" name="melding" value="',$nrOfTm,'">
							    <button>Svar</button>
					    </form>
				    </div>';
				
			$nrOfTm++;
		}
	}
	?>
		<?php
				function setTm($arrayOfTmId) {
					$tmNr = $_POST['melding'];
					$_SESSION['gtm'] = $arrayOfTmId[$tmNr];
					header("LOCATION: gjesteskrivekommentar.php");
				}
				if(array_key_exists('melding', $_POST)) {
					setTm($arrayOfTmId);
				}
		?>
            
        </body>


</html>

<style>
img {
		border: 1px solid #ddd;
		border-radius: 4px;
		padding: 5px;
		width: 150px;
	}
</style>
