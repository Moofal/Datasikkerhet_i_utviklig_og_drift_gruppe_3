<div class="fag-seksjon">
        <h2>Liste med fag</h2>
        <div>
            <ul>
		<?php 
		if (!mysqli_stmt_prepare($fog_stmt, $fog_sql)) {
			echo "SQL statment failed";
		} else {
			mysqli_stmt_bind_param($fog_stmt, "i", $_SESSION['user_id']);
			mysqli_stmt_execute($fog_stmt);
			$fog_result = mysqli_stmt_get_result($fog_stmt);
			while($fag_row = mysqli_fetch_assoc($fog_result)) {
				array_push($arrayOfEmneId, $fag_row["id"]);
				echo 
				'<li>
				<form method="POST">					
					<input type="hidden" name="emne" value="',$nr_of_emne,'">
					<button type="submit">', $fag_row["navn"], '</button>			
				</form>   
				</li>';
				$nr_of_emne++;
			} 	
		}?>
		<?php
			function setEmne($arrayOfEmneId) {
				$emneNr = $_POST['emne'];
				$_SESSION['emne'] = $arrayOfEmneId[$emneNr];
				header("LOCATION: ForeleserEmneMeldinger.php");
			} 
			if (isset($_POST['emne'])) {
			   if(array_key_exists($_POST['emne'], $arrayOfEmneId)) {
                                setEmne($arrayOfEmneId);
			   } else {
			  	include_once '/var/www/logfiles/emneIdLog.php'; 
			   }	
			}
		?>
            </ul>
        </div>
    </div>
    <div class="student-info">
        <h2>Foreleser Informasjon</h2>
        <table>
            <tr>
                <th>Navn</th>
                <th>Epost</th>
            </tr>
			<?php 
			if (!mysqli_stmt_prepare($foi_stmt, $foi_sql)) {
				echo "SQL statment failed";
			} else {
				mysqli_stmt_bind_param($foi_stmt, "i", $_SESSION['user_id']);
				mysqli_stmt_execute($foi_stmt);
				$foi_result = mysqli_stmt_get_result($foi_stmt);
				while($foreleser_row = mysqli_fetch_assoc($foi_result)) {
					echo '
					<tr>
					<th>',$foreleser_row["navn"],' ',$foreleser_row["etternavn"],'</th>
					<th>',$foreleser_row["e_post"],'</th>
					</tr>
					</table>
					<img src="http://158.39.188.203:81/steg2/uploads/',$foreleser_row["file_destination"],'" alt="',$foreleser_row["bilde_navn"],'">';
					if ($foreleser_row['two_factor'] === 0) {
						echo'
                        	<a href="ForeleserToFaktor.php">
                  		<p>Registrer to faktor autentisering</p>
                  		</a>
                  		</div>';
				}
			 } }?>
				<a href="ForeleserPassordBytt.php">
                                <p>Bytt Passord</p>
                                </a>    
</div>
