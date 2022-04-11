<?php
	include_once 'header.php';
	//Created a template
	$st_sql = "SELECT id, tilbakemelding FROM tilbakemelding_student WHERE emne_id=?";
	//create a prepared statement
	$stmt = mysqli_stmt_init($connForeleser);
	
	$navn_sql = "SELECT navn FROM emne WHERE id=?";
	$navn_stmt = mysqli_stmt_init($connForeleser);

	$arrayOfTmId = array();
	$nrOfTm = 0;
?>
<main>
    <?php 
     if (!mysqli_stmt_prepare($navn_stmt, $navn_sql)){
                echo "SQL statment failed";
        }       else {
                //Bind parameters to the placeholder
                mysqli_stmt_bind_param($navn_stmt, "i", $_SESSION['emne']);
                //Run parameters inside database
                mysqli_stmt_execute($navn_stmt);
                $navn_result = mysqli_stmt_get_result($navn_stmt);

		while ($navn_row = mysqli_fetch_assoc($navn_result)) {
		 echo '<h2 class="tittel">',$navn_row["navn"],'</h2>';
		}}
    ?>
    
    <h3>Meldinger</h3>
    <div class="meldinger">
	<?php
	//Prepare the prepared statement
	if (!mysqli_stmt_prepare($stmt, $st_sql)){
		echo "SQL statment failed";
	}	else {
		//Bind parameters to the placeholder
		mysqli_stmt_bind_param($stmt, "i", $_SESSION['emne']);
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
				$_SESSION['tm'] = $arrayOfTmId[$tmNr];					
				header("Location: ForeleserKomentar.php");
			}
			 if (isset($_POST['melding'])) {
                           if(array_key_exists($_POST['melding'], $arrayOfTmId)) {
                                setTm($arrayOfTmId);
                           } else {
                                include_once '/var/www/logfiles/tbmIdLog.php';
                           }
                        }
		?>
    </div>
</main>
<footer>
    <p>Footer Info</p>
</footer>
</body>
</html>

<style>
    .nav-bar {
        display: flex;
        justify-content: space-evenly;
        align-items: center;
    }
    .tittel {
        display: flex;
        justify-content: center;
    }
    h3 {
        display: flex;
        justify-content: center;
    }
    .meldinger {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-evenly;
    }
    .melding {
        border: solid black;
    }
</style>
