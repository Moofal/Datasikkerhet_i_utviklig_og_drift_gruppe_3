<?php
require_once 
'vendor/phpgangsta/googleauthenticator/PHPGangsta/GoogleAuthenticator.php';

$ga = new PHPGangsta_GoogleAuthenticator();
$secret = $ga->createSecret();
echo "Registrer denn koden i Google Authenticator: ".$secret."\n\n";
?>
