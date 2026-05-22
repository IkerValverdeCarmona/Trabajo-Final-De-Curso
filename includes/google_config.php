<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once 'db.php'; 

$clientID = '972400545344-30tnhtblltv9pb8kmvf45s1pdecal044.apps.googleusercontent.com';
$clientSecret = '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';

// 1. Detectamos de forma segura si es HTTP o HTTPS
$protocolo = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' || 
              isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ? "https://" : "http://";

// 2. Sacamos el dominio (localhost o tu web de InfinityFree)
$dominio = $_SERVER['HTTP_HOST'];

// 3. Detectamos si estás usando la carpeta de XAMPP local
$carpeta_base = "";
if (strpos($_SERVER['REQUEST_URI'], '/LcQuiromasajes/') !== false) {
    $carpeta_base = "/LcQuiromasajes";
}

// 4. Construimos la URL ABSOLUTA que exige Google
$redirectUri = $protocolo . $dominio . $carpeta_base . '/auth/google_callback.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
// Le pasamos la URL absoluta corregida
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");
?>