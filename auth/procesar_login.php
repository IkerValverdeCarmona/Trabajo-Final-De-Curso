<?php
session_start();
require_once '../includes/db.php';

// Activamos el modo "chivato" para ver todos los errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    try {
        // 1. Buscamos el correo
        $stmt = $pdo->prepare("SELECT * FROM Perfil WHERE email = ?");
        $stmt->execute([$email]);
        $perfil = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si el correo no existe, paramos y avisamos
        if (!$perfil) {
            die("DIAGNÓSTICO: No existe nadie registrado con el correo: " . htmlspecialchars($email));
        }

        // 2. Comprobamos la contraseña
        if (!password_verify($password, $perfil['contraseña'])) {
            die("DIAGNÓSTICO: La contraseña es incorrecta. Has puesto '$password' pero no coincide con la base de datos.");
        }

        // 3. Si llega aquí, la contraseña era correcta. Buscamos el nombre.
        $id_perfil = $perfil['id_perfil'];
        $rol = $perfil['permiso'];
        $nombre_real = "Desconocido";

        if ($rol === 'usuario') {
            $stmt_nombre = $pdo->prepare("SELECT nombre FROM Usuario WHERE id_perfil = ?");
            $stmt_nombre->execute([$id_perfil]);
            $datos = $stmt_nombre->fetch();
            if($datos) $nombre_real = $datos['nombre'];
            
        } elseif ($rol === 'trabajador') {
            $stmt_nombre = $pdo->prepare("SELECT nombre FROM Trabajadores WHERE id_perfil = ?");
            $stmt_nombre->execute([$id_perfil]);
            $datos = $stmt_nombre->fetch();
            if($datos) $nombre_real = $datos['nombre'];

        } elseif ($rol === 'admin') {
            // A ver si la tabla Administrador no se llama exactamente así o falta la columna nombre
            $stmt_nombre = $pdo->prepare("SELECT nombre FROM Administrador WHERE id_perfil = ?");
            $stmt_nombre->execute([$id_perfil]);
            $datos = $stmt_nombre->fetch();
            if($datos) $nombre_real = $datos['nombre'];
        }

        // Si todo sale bien, mostramos este mensaje gigante
        die("<h1>¡ÉXITO!</h1><p>Contraseña correcta. Eres: <b>$nombre_real</b> y tu rol es: <b>$rol</b>.</p><p>Si ves esto, el código está perfecto y el fallo está en el archivo admin.php</p>");

    } catch (PDOException $e) {
        // Si hay fallo con los nombres de las tablas o columnas, saldrá aquí
        die("ERROR CRÍTICO EN LA BASE DE DATOS: " . $e->getMessage());
    }
}
?>