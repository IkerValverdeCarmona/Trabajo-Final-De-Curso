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
    $stmt = $pdo->prepare("SELECT * FROM Perfil WHERE email = ?");
    $stmt->execute([$email]);
    $perfil = $stmt->fetch();

    if (!$perfil) {
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
        $id_perfil = $perfil['id_perfil'];
        $_SESSION['user_id'] = $id_perfil;
        $_SESSION['rol'] = $perfil['permiso'];
    }

    $_SESSION['nombre_real'] = $nombre;
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
                $stmt_prod = $pdo->prepare("SELECT nombre, precio_actual FROM Producto WHERE id_producto = ?");
                $stmt_prod->execute([$id_p]);
                $producto_info = $stmt_prod->fetch(PDO::FETCH_ASSOC);

                if ($producto_info) {
                    $_SESSION['carrito'][$id_p] = [
                        'nombre' => $producto_info['nombre'],
                        'precio' => $producto_info['precio_actual'],
                        'cantidad' => $item['cantidad']
                    ];
                }
            }
            $pdo->prepare("DELETE FROM Carrito WHERE id_perfil = ?")->execute([$id_perfil]);
        }
    } catch (PDOException $e_cart) {
        error_log("Error recuperando carrito Google: " . $e_cart->getMessage());
    }
    header("Location: ../index.php");
    exit();
}
?>