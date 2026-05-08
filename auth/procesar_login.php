<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    // Buscamos el registro de acceso
    $stmt = $pdo->prepare("SELECT * FROM Perfil WHERE email = ?");
    $stmt->execute([$email]);
    $perfil = $stmt->fetch(PDO::FETCH_ASSOC);
    // Comprobamos existencia y contraseña encriptada
    if ($perfil && password_verify($password, $perfil['contraseña'])) {
        $id_perfil = $perfil['id_perfil'];
        $rol = $perfil['permiso'];
        $nombre_real = "Invitado"; 
        // Extraemos el nombre real dependiendo del rol
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

        // Creamos la sesión oficial
        $_SESSION['user_id'] = $id_perfil;
        $_SESSION['rol'] = $rol;
        $_SESSION['nombre_real'] = $nombre_real;

        // Redireccionamiento inteligente según jerarquía
        if ($rol === 'admin' || $rol === 'trabajador') {// Admins y trabajadores van al panel de control
            header("Location: ../admin/admin.php");
        } else {// Usuarios normales van al inicio
            header("Location: ../index.php");
        }
        exit();

    } else { // Si no existe el email o la contraseña no coincide, redirigimos con error
        header("Location: login.php?error=1");
        exit();
    }
}
?>