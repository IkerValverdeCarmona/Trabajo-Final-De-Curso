<?php
session_start();
require_once '../includes/db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    try {
        // 1. Buscamos el usuario
        $stmt = $pdo->prepare("SELECT * FROM Perfil WHERE email = ?");
        $stmt->execute([$email]);
        $perfil = $stmt->fetch(PDO::FETCH_ASSOC);

        // --- DEPURACIÓN: Si no encuentra el email ---
        if (!$perfil) {
            $error = "El correo electrónico no está registrado.";
        } 
        // 2. Verificamos contraseña (USA EL NOMBRE DE TU COLUMNA: 'password' o 'contrasena')
        // Si tu columna se llama 'contrasena', cambia $perfil['password'] por $perfil['contrasena']
        else if (password_verify($password, $perfil['password'])) {
            
            $_SESSION['user_id'] = $perfil['id_perfil'];
            $_SESSION['rol'] = $perfil['rol'];
            
            // 3. Obtener nombre real
            $tabla_nombre = ($perfil['rol'] === 'admin' || $perfil['rol'] === 'trabajador') ? 'Trabajadores' : 'Usuario';
            $stmt_nom = $pdo->prepare("SELECT nombre FROM $tabla_nombre WHERE id_perfil = ?");
            $stmt_nom->execute([$perfil['id_perfil']]);
            $datos_usuario = $stmt_nom->fetch();
            $_SESSION['nombre_real'] = $datos_usuario ? $datos_usuario['nombre'] : 'Usuario';

            // 4. RECUPERAR CARRITO (Aquí puede estar el fallo si la tabla no existe)
            try {
                $stmt_cart = $pdo->prepare("SELECT id_producto, cantidad FROM Carrito WHERE id_perfil = ?");
                $stmt_cart->execute([$perfil['id_perfil']]);
                $carrito_recuperado = $stmt_cart->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($carrito_recuperado)) {
                    if (!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];
                    foreach ($carrito_recuperado as $item) {
                        $_SESSION['carrito'][$item['id_producto']] = ['cantidad' => $item['cantidad']];
                    }
                    $pdo->prepare("DELETE FROM Carrito WHERE id_perfil = ?")->execute([$perfil['id_perfil']]);
                }
            } catch (PDOException $e_cart) {
                // Si falla el carrito, no bloqueamos el login, solo lo registramos
                error_log("Error recuperando carrito: " . $e_cart->getMessage());
            }

            header("Location: ../index.php");
            exit;

        } else {
            $error = "La contraseña es incorrecta.";
        }
    } catch (PDOException $e) {
        $error = "Error crítico de base de datos: " . $e->getMessage();
    }
}

include '../includes/header.php';
?>

<div style="background: linear-gradient(135deg, #FFF7EE 0%, #FDF2D8 100%); min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px; font-family: 'Poppins', sans-serif;">
    <div style="background: #FFFFFF; padding: 50px 40px; border-radius: 20px; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05); max-width: 450px; width: 100%; text-align: center;">
        <h2 style="font-family: 'Playfair Display', serif; color: #EB6250; font-size: 2.2rem; margin-bottom: 10px;">Iniciar Sesión</h2>
        
        <?php if ($error): ?>
            <div style="background: #fce8e6; color: #c5221f; padding: 12px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #f5c6cb;">
                ⚠️ <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Correo" required style="width: 100%; padding: 12px; margin-bottom: 15px; border-radius: 12px; border: 1px solid #ddd;">
            <input type="password" name="password" placeholder="Contraseña" required style="width: 100%; padding: 12px; margin-bottom: 15px; border-radius: 12px; border: 1px solid #ddd;">
            <button type="submit" style="background: #EB6250; color: white; width: 100%; padding: 14px; border: none; border-radius: 50px; font-weight: 600; cursor: pointer;">Entrar</button>
        </form>
    </div>
</div>
<?php include '../includes/footer.php'; ?>