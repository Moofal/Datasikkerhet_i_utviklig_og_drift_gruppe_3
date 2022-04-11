<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hjemmeside</title>
</head>
<body>
<main>
<form action="includes/studentTwoFactorLogIn.inc.php" method="POST" autocomplete="off">
                <label>Engangs kode</label>
                <input type"text" name="code">
                <button type="submit" name="submit">Valider to faktor</button>
</form>

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

