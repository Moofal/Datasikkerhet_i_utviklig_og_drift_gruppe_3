<?php
include_once 'header.php';
session_unset();
session_destroy();
?>


<div class="login">
	<a href="login_foreleser.html">Logg inn som foreleser</a>
	<a href="login_student.html">Logg inn som student</a>
	<a href="gjesteloggin.php">Logg inn som gjest</a>
</div>


</main>
</body>
</html>

<style>
.login {
	 display: flex;
	 flex-direction: column;
	 align-items: center
}
.login_form {
	 display: flex;
	 flex-direction: column;
}
</style>
