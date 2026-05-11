<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['contraseña']; 

    try {
        // 1. Buscamos el perfil por email
        // IMPORTANTE: He puesto 'contrasena', cámbialo a 'password' si así se llama tu columna
        $stmt = $pdo->prepare("SELECT id_perfil, email, constraseña, rol FROM Perfil WHERE email = ?");
        $stmt->execute([$email]);
        $perfil = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Verificamos si existe y si la contraseña coincide
        if ($perfil && password_verify($password, $perfil['contraseña'])) {
            
            // Creamos las variables de sesión básicas
            $_SESSION['user_id'] = $perfil['id_perfil'];
            $_SESSION['rol'] = $perfil['rol'];

            // 3. Obtenemos el nombre real para el header
            $tabla = ($perfil['rol'] === 'admin' || $perfil['rol'] === 'trabajador') ? 'Trabajadores' : 'Usuario';
            $stmt_name = $pdo->prepare("SELECT nombre FROM $tabla WHERE id_perfil = ?");
            $stmt_name->execute([$perfil['id_perfil']]);
            $user_data = $stmt_name->fetch();
            $_SESSION['nombre_real'] = $user_data ? $user_data['nombre'] : 'Usuario';

            // --- 4. RECUPERAR EL CARRITO DE LA BASE DE DATOS ---
            try {
                $stmt_cart = $pdo->prepare("SELECT id_producto, cantidad FROM Carrito WHERE id_perfil = ?");
                $stmt_cart->execute([$perfil['id_perfil']]);
                $items_guardados = $stmt_cart->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($items_guardados)) {
                    // Inicializamos el carrito en la sesión si no existe
                    if (!isset($_SESSION['carrito'])) {
                        $_SESSION['carrito'] = [];
                    }

                    foreach ($items_guardados as $item) {
                        $id_p = $item['id_producto'];
                        $_SESSION['carrito'][$id_p] = [
                            'cantidad' => $item['cantidad']
                        ];
                    }

                    // Borramos el carrito de la base de datos para que no se duplique
                    $pdo->prepare("DELETE FROM Carrito WHERE id_perfil = ?")->execute([$perfil['id_perfil']]);
                }
            } catch (PDOException $e_cart) {
                // Si falla el carrito, no bloqueamos el login, solo seguimos
            }
            // ---------------------------------------------------

            // Login correcto: Al inicio
            header("Location: ../index.php");
            exit;

        } else {
            // Error de credenciales
            header("Location: login.php?error=1");
            exit;
        }

    } catch (PDOException $e) {
        die("Error en el servidor: " . $e->getMessage());
    }
} else {
    header("Location: login.php");
    exit;
}