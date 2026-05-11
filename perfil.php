<?php
session_start();
require_once 'includes/db.php';

// 1. SEGURIDAD: Si no hay sesión, mandamos al login
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$id_perfil = $_SESSION['user_id'];
$mensaje = "";

// 2. LÓGICA DE ACTUALIZACIÓN: Por si el usuario quiere cambiar sus datos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    $nuevo_nombre = $_POST['nombre'];
    $nuevo_apellido = $_POST['apellido'];
    $nuevo_telefono = $_POST['telefono'];

    try {
        $sql = "UPDATE Usuario SET nombre = ?, apellido = ?, telefono = ? WHERE id_perfil = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nuevo_nombre, $nuevo_apellido, $nuevo_telefono, $id_perfil]);
        
        // Actualizamos también la variable de sesión para que el header se refresque
        $_SESSION['nombre_real'] = $nuevo_nombre;
        $mensaje = "¡Datos actualizados correctamente!";
    } catch (PDOException $e) {
        $mensaje = "Error al actualizar los datos.";
    }
}

// 3. OBTENER DATOS: Traemos la info de Usuario y el Email usando subconsulta
try {
    $sql = "SELECT 
                *, 
                (SELECT email FROM Perfil WHERE id_perfil = Usuario.id_perfil) AS email 
            FROM Usuario 
            WHERE id_perfil = ?";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_perfil]);
    $user = $stmt->fetch();
    
} catch (PDOException $e) {
    die("Error al cargar el perfil.");
}

include 'includes/header.php';
?>

<main style="background: linear-gradient(135deg, #FFF7EE 0%, #FDF2D8 100%); min-height: 90vh; padding: 60px 20px;">
    <div style="max-width: 800px; margin: 0 auto;">
        
        <div style="background: #FFFFFF; border-radius: 20px; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05); overflow: hidden;">
            
            <div style="background: #EB6250; padding: 40px; text-align: center; color: white;">
                <div style="width: 100px; height: 100px; background: white; border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: #EB6250; font-weight: bold; border: 4px solid rgba(255,255,255,0.3);">
                    <?= strtoupper(substr($user['nombre'], 0, 1)) ?>
                </div>
                <h1 style="font-family: 'Playfair Display', serif; margin: 0; font-size: 2rem;">Mi Perfil</h1>
                <p style="font-family: 'Poppins', sans-serif; opacity: 0.9; margin-top: 5px;">Gestiona tu información personal</p>
            </div>

            <div style="padding: 40px; font-family: 'Poppins', sans-serif;">
                
                <?php if ($mensaje): ?>
                    <div style="background: #e6f4ea; color: #1e7e34; padding: 15px; border-radius: 12px; margin-bottom: 25px; text-align: center; border: 1px solid #c3e6cb;">
                        ✨ <?= $mensaje ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="perfil.php" style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                    
                    <div style="grid-column: span 2;">
                        <label style="display: block; color: #888; font-size: 0.85rem; margin-bottom: 8px;">Correo Electrónico (No editable)</label>
                        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1px solid #eee; background: #f9f9f9; color: #999; cursor: not-allowed;">
                    </div>

                    <div>
                        <label style="display: block; color: #333; font-size: 0.9rem; margin-bottom: 8px; font-weight: 500;">Nombre</label>
                        <input type="text" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>" required style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1px solid #ddd; font-family: 'Poppins', sans-serif;">
                    </div>

                    <div>
                        <label style="display: block; color: #333; font-size: 0.9rem; margin-bottom: 8px; font-weight: 500;">Apellidos</label>
                        <input type="text" name="apellido" value="<?= htmlspecialchars($user['apellido']) ?>" required style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1px solid #ddd; font-family: 'Poppins', sans-serif;">
                    </div>

                    <div style="grid-column: span 2;">
                        <label style="display: block; color: #333; font-size: 0.9rem; margin-bottom: 8px; font-weight: 500;">Teléfono de contacto</label>
                        <input type="text" name="telefono" value="<?= htmlspecialchars($user['telefono']) ?>" required style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1px solid #ddd; font-family: 'Poppins', sans-serif;">
                    </div>

                    <div style="grid-column: span 2; margin-top: 10px;">
                        <button type="submit" name="actualizar" style="width: 100%; background: #EB6250; color: white; border: none; padding: 15px; border-radius: 50px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: 0.3s; font-family: 'Poppins', sans-serif;" onmouseover="this.style.background='#D75443'" onmouseout="this.style.background='#EB6250'">
                            Guardar Cambios
                        </button>
                    </div>

                </form>

                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center;">
                    <p style="color: #888; font-size: 0.85rem;">Miembro desde: <?= date('d/m/Y', strtotime($user['fecha_reg'] ?? 'now')) ?></p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>