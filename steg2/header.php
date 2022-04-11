<?php
	include_once 'includes/foreleserdbh.inc.php';
	session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
	<style>
		.nav-bar {
        display: flex;
        justify-content: space-evenly;
        align-items: center;
    }
	</style>
</head>
<body>
<header>
    <div class="nav-bar">
        <h1>Datasikkerhet Gruppe 3</h1>
		<?php
			if (isset($_SESSION["user_id"]) || isset($_SESSION['type'])) {
				if ($_SESSION['type'] === "s") {
					echo '<a href="StudentHjemmeside.php">Din Side</a>';
				}
				if ($_SESSION['type'] === "f") {
					echo '<a href="ForeleserHjemmeSide.php">Din Side</a>';
				}
				echo '
					<form action="includes/logout.php" method="POST">
					<button type="submit" name="submitLogout">Logg ut</button>
					</form>
					';
				}
		?>
    </div>
</header>
