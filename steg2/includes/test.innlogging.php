<?php
require_once 
'vendor/phpgangsta/googleauthenticator/PHPGangsta/GoogleAuthenticator.php';

$secret =  $argv[1];
echo "Secret: " . $secret;
$ga = new PHPGangsta_GoogleAuthenticator();
$qrCodeUrl = $ga->getQRCodeGoogleUrl('Blog', $secret);
echo "Google Charts URL for the QR-Code: ".$qrCodeUrl."\n\n";
//$oneCode = $ga->getCode($secret);
//echo "Checking Code '$oneCode' and Secret '$secret':\n";
$oneCode = readline("GA-kode: ");
$checkResult = $ga->verifyCode($secret, $oneCode, 2);    // 2 = 2*30sec clock tolerance
if ($checkResult) {
echo 'OK';
} else {
echo 'FAILED';
}
?>
