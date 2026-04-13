<?php
session_start();

// Cargamos la conexión centralizada
require_once '../includes/db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $password_input = $_POST['password'] ?? ''; 

    if ($accion === 'registro') {
        if (empty($email) || empty($password_input)) {
            die("El email y la contraseña son obligatorios.");
        }
        
        $passwordHash = password_hash($password_input, PASSWORD_BCRYPT);

        try {
            $pdo->beginTransaction();
            
            // 1. Insertamos en Perfil
            $stmt = $pdo->prepare("INSERT INTO Perfil (email, contraseña, permiso) VALUES (?, ?, 'usuario')");
            $stmt->execute([$email, $passwordHash]);
            $id_perfil = $pdo->lastInsertId();

            // 2. Insertamos en Usuario (CORREGIDO: Le pasamos un nombre por defecto para evitar el error de NOT NULL)
            $stmtUsuario = $pdo->prepare("INSERT INTO Usuario (id_perfil, nombre) VALUES (?, 'Nuevo Cliente')");
            $stmtUsuario->execute([$id_perfil]);

            $pdo->commit();
            echo "<script>alert('Registro exitoso. Ya puedes iniciar sesión.'); window.location.href='index.html';</script>";
        }
        catch (PDOException $e) {
            $pdo->rollBack();
            if ($e->getCode() == 23000) {
                echo "<script>alert('Ese correo electrónico ya está registrado.'); window.history.back();</script>";
            }
            else {
                die("Error en el registro: " . $e->getMessage());
            }
        }
    }
    elseif ($accion === 'login') {
        if (empty($email) || empty($password_input)) {
            die("El email y la contraseña son obligatorios.");
        }
        try {
            // Buscamos al usuario
            $stmt = $pdo->prepare("SELECT id_perfil, contraseña, permiso FROM Perfil WHERE email = ?");
            $stmt->execute([$email]);
            $perfil = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificamos el hash
            if ($perfil && password_verify($password_input, $perfil['contraseña'])) {
                $_SESSION['id_perfil'] = $perfil['id_perfil'];
                $_SESSION['permiso'] = $perfil['permiso'];
                $_SESSION['email'] = $email;

                // MEJORA PRO: Redirección inteligente según el rol
                if ($perfil['permiso'] === 'admin') {
                    header("Location: ../admin.php"); // Lidia va al panel
                } else {
                    header("Location: ../index.php"); // Los clientes van al inicio
                }
                exit;
            }
            else {
                echo "<script>alert('Correo o contraseña incorrectos.'); window.history.back();</script>";
            }
        }
        catch (PDOException $e) {
            die("Error en el inicio de sesión: " . $e->getMessage());
        }
    }
}
?>