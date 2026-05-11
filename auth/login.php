<?php
session_start();
require_once '../includes/db.php';

// Si ya está logueado, lo mandamos al inicio
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = 'Por favor, rellena todos los campos.';
    } else {
        try {
            // Buscamos el email en la base de datos
            $stmt = $pdo->prepare("SELECT * FROM Perfil WHERE email = ?");
            $stmt->execute([$email]);
            $perfil = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificamos la contraseña encriptada
            if ($perfil && password_verify($password, $perfil['password'])) {
                
                // --- 1. CREAR LA SESIÓN BÁSICA ---
                $_SESSION['user_id'] = $perfil['id_perfil'];
                $_SESSION['rol'] = $perfil['rol'];
                
                // Obtener el nombre para mostrarlo en el Header
                if ($perfil['rol'] === 'admin' || $perfil['rol'] === 'trabajador') {
                    $stmt_nom = $pdo->prepare("SELECT nombre FROM Trabajadores WHERE id_perfil = ?");
                } else {
                    $stmt_nom = $pdo->prepare("SELECT nombre FROM Usuario WHERE id_perfil = ?");
                }
                $stmt_nom->execute([$perfil['id_perfil']]);
                $datos_usuario = $stmt_nom->fetch();
                $_SESSION['nombre_real'] = $datos_usuario ? $datos_usuario['nombre'] : 'Usuario';


                // --- 2. LA MAGIA: RECUPERAR EL CARRITO GUARDADO ---
                $stmt_cart = $pdo->prepare("SELECT id_producto, cantidad FROM Carrito WHERE id_perfil = ?");
                $stmt_cart->execute([$perfil['id_perfil']]);
                $carrito_recuperado = $stmt_cart->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($carrito_recuperado)) {
                    // Si no existe la sesión del carrito, la creamos
                    if (!isset($_SESSION['carrito'])) {
                        $_SESSION['carrito'] = [];
                    }
                    
                    // Metemos los productos recuperados
                    foreach ($carrito_recuperado as $item) {
                        $_SESSION['carrito'][$item['id_producto']] = [
                            'cantidad' => $item['cantidad']
                        ];
                    }
                    
                    // Borramos el carrito de la BD para que no se duplique luego
                    $pdo->prepare("DELETE FROM Carrito WHERE id_perfil = ?")->execute([$perfil['id_perfil']]);
                }

                // Redirigir al inicio o a la tienda
                header("Location: ../index.php");
                exit;

            } else {
                $error = 'Email o contraseña incorrectos.';
            }
        } catch (PDOException $e) {
            $error = 'Error de conexión: ' . $e->getMessage();
        }
    }
}

include '../includes/header.php';
?>

<div style="background: linear-gradient(135deg, #FFF7EE 0%, #FDF2D8 100%); min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px; font-family: 'Poppins', sans-serif;">
    <div style="background: #FFFFFF; padding: 50px 40px; border-radius: 20px; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05); max-width: 450px; width: 100%; text-align: center;">
        
        <h2 style="font-family: 'Playfair Display', serif; color: #EB6250; font-size: 2.2rem; margin-bottom: 10px;">¡Hola de nuevo!</h2>
        <p style="color: #886752; margin-bottom: 30px; font-size: 0.95rem;">Accede a tu cuenta de LC Quiromasajes</p>

        <?php if ($error): ?>
            <div style="background: #fce8e6; color: #c5221f; padding: 12px; border-radius: 12px; margin-bottom: 25px; font-size: 0.9rem; border: 1px solid #f5c6cb;">
                ⚠️ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" style="display: flex; flex-direction: column; gap: 20px;">
            <div style="text-align: left;">
                <label style="font-size: 0.85rem; color: #555; font-weight: 500; margin-bottom: 8px; display: block;">Correo Electrónico</label>
                <input type="email" name="email" required style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1px solid #ddd; font-family: 'Poppins', sans-serif; font-size: 0.95rem; box-sizing: border-box; outline: none; transition: border-color 0.3s;" onfocus="this.style.borderColor='#EB6250'" onblur="this.style.borderColor='#ddd'">
            </div>

            <div style="text-align: left;">
                <label style="font-size: 0.85rem; color: #555; font-weight: 500; margin-bottom: 8px; display: block;">Contraseña</label>
                <input type="password" name="password" required style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1px solid #ddd; font-family: 'Poppins', sans-serif; font-size: 0.95rem; box-sizing: border-box; outline: none; transition: border-color 0.3s;" onfocus="this.style.borderColor='#EB6250'" onblur="this.style.borderColor='#ddd'">
            </div>

            <button type="submit" style="background: #EB6250; color: white; padding: 14px; border: none; border-radius: 50px; font-weight: 600; font-size: 1rem; cursor: pointer; margin-top: 10px; transition: background 0.3s;" onmouseover="this.style.background='#D75443'" onmouseout="this.style.background='#EB6250'">
                Iniciar Sesión
            </button>
        </form>

        <p style="margin-top: 30px; font-size: 0.9rem; color: #666;">
            ¿No tienes cuenta? <a href="registro.php" style="color: #EB6250; text-decoration: none; font-weight: 600;">Regístrate aquí</a>
        </p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>