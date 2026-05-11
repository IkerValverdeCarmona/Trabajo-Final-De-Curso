<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    try {
        // 1. Buscamos el correo en Perfil
        $stmt = $pdo->prepare("SELECT * FROM Perfil WHERE email = ?");
        $stmt->execute([$email]);
        $perfil = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si existe el usuario y la contraseña es correcta
        if ($perfil && password_verify($password, $perfil['contraseña'])) {
            
            $id_perfil = $perfil['id_perfil'];
            $rol = $perfil['permiso'];
            $nombre_real = "Usuario";

            // 2. Buscamos el nombre según el rol
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
                $stmt_nombre = $pdo->prepare("SELECT nombre FROM Administrador WHERE id_perfil = ?");
                $stmt_nombre->execute([$id_perfil]);
                $datos = $stmt_nombre->fetch();
                if($datos) $nombre_real = $datos['nombre'];
            }

            // 3. Guardamos en la sesión
            $_SESSION['user_id'] = $id_perfil;
            $_SESSION['rol'] = $rol;
            $_SESSION['nombre_real'] = $nombre_real;

            // 4. Redirección inteligente
            if ($rol === 'admin' || $rol === 'trabajador') {
                header("Location: ../admin/index.php");
            } else {
                header("Location: ../index.php");
            }
            exit();

        } else {
            // Si falla la contraseña o el correo
            header("Location: index.php?error=1");
            exit();
        }

    } catch (PDOException $e) {
        // Esto solo saldrá si hay un error real de base de datos
        echo "Error crítico: " . $e->getMessage();
        exit();
    }
}
?>