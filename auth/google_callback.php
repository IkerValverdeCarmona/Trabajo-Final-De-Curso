<?php
session_start();
require_once '../includes/google_config.php';

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    
    $email = $google_account_info->email;
    $nombre = $google_account_info->givenName;
    $apellido = $google_account_info->familyName;

    // 1. Verificamos si ya existe en Perfil
    $stmt = $pdo->prepare("SELECT * FROM Perfil WHERE email = ?");
    $stmt->execute([$email]);
    $perfil = $stmt->fetch();

    if (!$perfil) {
        // REGISTRO AUTOMÁTICO si no existe
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("INSERT INTO Perfil (email, contraseña, permiso) VALUES (?, ?, 'usuario')");
        $stmt->execute([$email, password_hash(bin2hex(random_bytes(10)), PASSWORD_DEFAULT)]);
        $id_perfil = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO Usuario (id_perfil, nombre, apellido) VALUES (?, ?, ?)");
        $stmt->execute([$id_perfil, $nombre, $apellido]);
        
        $pdo->commit();
        
        $_SESSION['user_id'] = $id_perfil;
        $_SESSION['rol'] = 'usuario';
    } else {
        $_SESSION['user_id'] = $perfil['id_perfil'];
        $_SESSION['rol'] = $perfil['permiso'];
    }

    $_SESSION['nombre_real'] = $nombre;
    header("Location: ../index.php");
    exit();
}