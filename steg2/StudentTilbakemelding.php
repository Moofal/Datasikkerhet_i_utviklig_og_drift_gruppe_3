<!DOCTYPE html>	

<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
?>

	<html lang="nb">
	
		<!-- Information about the page -->
		<head>
			<title lang="nb">Steg 1</title>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="description" content="Meldingstjeneste for tilbakemeldinger på emner">
			<meta name="keywords" content="Tilbake melding, emne">
			
			<link rel="stylesheet" media="screen" href="stil.css" />
		</head>
		
		<!-- The visible content of the page -->
		<body>
		
			<!-- header content -->
			<header>
				<h1>Steg 1</h1>
			</header>
			
			<!-- navigation -->
			<nav>
				<ul>
					<li><a href="StudentHjemmeSide.php">Hjem</a></li>
				</ul>
			</nav>
			
			<!-- main body -->
			<main>
				<section>
					<h2>Emne navn</h2>
					<!--php script: student_feedback.php-->

					<form action="student_feedback.php" method="POST">
					<time>Dato: <?php echo date('Y.m.d G:i'); ?></time>
						<label for="emne" >Velg et emne:</label>
							<select id="emne" name="fag">
							<?php include_once 'emner.php'; foreach($emner as $emne):
								echo "<option value=\"" .$emne['navn']."\">". $emne['navn'] ."</option>";
							endforeach;?>
							</select>
						<label for="message-box" name="emne-tilbakemelding"></label><br>
						<textarea id="message-box" name="message-box" rows="20" cols="100"
						placeholder="Skriv din tilbakemelding her" maxlength="500"></textarea>
						<input type = "submit" value = "Send din melding" />
					</form>
				</section>
				<section>
					<h2>Bilde av foreleserne i dine emner</h2>
					<?php include_once 'emner.php'; foreach($bilder as $bilde):
						foreach($emner as $emne):
							if($bilde['navn'] === $emne['navn']){
								echo "<img src=\"http://158.39.188.203:81/steg2/uploads/".$bilde['file_destination']. "\" alt=\"".$bilde['bilde_navn']. "\">";
								echo "<p>";
								echo 'Foreleser i ';
								echo ''.$emne['navn'].'';
								echo ' - ';
								echo ''.$bilde['fornavn'], $bilde['etternavn'].'';
								echo "</p>";
							}
						endforeach;
					endforeach;?>
				</section>
			</main>
			
			<!-- footer content-->
			<footer>				

			</footer>
		</body>
	</html>
