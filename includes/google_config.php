<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once 'db.php'; // Para usar BASE_URL

$clientID = '972400545344-30tnhtblltv9pb8kmvf45s1pdecal044.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = BASE_URL . 'auth/google_callback.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");
?>