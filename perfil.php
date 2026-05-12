<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$id_perfil = $_SESSION['user_id'];
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    $nuevo_nombre = $_POST['nombre'];
    $nuevo_apellido = $_POST['apellido'];
    $nuevo_telefono = $_POST['telefono'];

    try {
        $sql = "UPDATE Usuario SET nombre = ?, apellido = ?, telefono = ? WHERE id_perfil = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nuevo_nombre, $nuevo_apellido, $nuevo_telefono, $id_perfil]);
        
        $_SESSION['nombre_real'] = $nuevo_nombre;
        $mensaje = "¡Datos actualizados correctamente!";
    } catch (PDOException $e) {
        $mensaje = "Error al actualizar los datos.";
    }
}

try {
    $sql = "SELECT *, (SELECT email FROM Perfil WHERE id_perfil = Usuario.id_perfil) AS email 
            FROM Usuario WHERE id_perfil = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_perfil]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    die("Error al cargar el perfil.");
}

include 'includes/header.php';
?>

<div class="hero-seccion" style="padding-bottom: 100px;"></div>

<main class="contenedor-perfil" style="margin-top: -80px;">
    <div class="tarjeta-base">
        
        <div class="cabecera-avatar">
            <div class="avatar-grande">
                <?= strtoupper(substr($user['nombre'], 0, 1)) ?>
            </div>
            <h1 style="font-family: var(--font-title); margin: 0; font-size: 2rem;">Mi Perfil</h1>
            <p style="opacity: 0.9; margin-top: 5px;">Gestiona tu información personal</p>
        </div>

        <div class="cuerpo-perfil">
            <?php if ($mensaje): ?>
                <div class="alerta-exito" style="text-align: center;">✨ <?= $mensaje ?></div>
            <?php endif; ?>

            <form method="POST" action="perfil.php" class="grid-formularios">
                <div class="col-span-2">
                    <label>Correo Electrónico (No editable)</label>
                    <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled class="input-disabled">
                </div>

                <div class="grupo-entrada">
                    <label>Nombre</label>
                    <input type="text" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>" required class="input-control">
                </div>

                <div class="grupo-entrada">
                    <label>Apellidos</label>
                    <input type="text" name="apellido" value="<?= htmlspecialchars($user['apellido']) ?>" required class="input-control">
                </div>

                <div class="col-span-2 grupo-entrada">
                    <label>Teléfono de contacto</label>
                    <input type="text" name="telefono" value="<?= htmlspecialchars($user['telefono']) ?>" required class="input-control">
                </div>

                <div class="col-span-2">
                    <button type="submit" name="actualizar" class="btn btn-primary boton-enviar">Guardar Cambios</button>
                </div>
            </form>

            <div class="info-footer-perfil">
                Miembro desde: <?= date('d/m/Y', strtotime($user['fecha_reg'] ?? 'now')) ?>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>