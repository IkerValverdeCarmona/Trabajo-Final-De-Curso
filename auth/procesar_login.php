<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['contraseña'];

    try {
        // 1. Buscamos el correo en Perfil
        $stmt = $pdo->prepare("SELECT * FROM Perfil WHERE email = ?");
        $stmt->execute([$email]);
        $perfil = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si existe el usuario y la contraseña es correcta (usando tu campo 'contraseña')
        if ($perfil && password_verify($password, $perfil['contraseña'])) {
            
            $id_perfil = $perfil['id_perfil'];
            $rol = $perfil['permiso']; // Usando tu campo 'permiso'
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

            // --- NUEVO: RECUPERAR EL CARRITO DE LA BASE DE DATOS ---
            try {
                $stmt_cart = $pdo->prepare("SELECT id_producto, cantidad FROM Carrito WHERE id_perfil = ?");
                $stmt_cart->execute([$id_perfil]);
                $items_guardados = $stmt_cart->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($items_guardados)) {
                    if (!isset($_SESSION['carrito'])) {
                        $_SESSION['carrito'] = [];
                    }

                    foreach ($items_guardados as $item) {
                        $id_p = $item['id_producto'];
                        $_SESSION['carrito'][$id_p] = [
                            'cantidad' => $item['cantidad']
                        ];
                    }

                    // Limpiamos la tabla para que no se dupliquen la próxima vez
                    $pdo->prepare("DELETE FROM Carrito WHERE id_perfil = ?")->execute([$id_perfil]);
                }
            } catch (PDOException $e_cart) {
                // Si falla el carrito (p.ej. no existe la tabla todavía), el login sigue funcionando
            }
            // -------------------------------------------------------

            // 4. Redirección inteligente
            if ($rol === 'admin' || $rol === 'trabajador') {
                header("Location: ../admin/index.php");
            } else {
                header("Location: ../index.php");
            }
            exit();

        } else {
            // Si falla la contraseña o el correo
            header("Location: ../login.php?error=1");
            exit();
        }

    } catch (PDOException $e) {
        echo "Error crítico: " . $e->getMessage();
        exit();
    }
}
?>