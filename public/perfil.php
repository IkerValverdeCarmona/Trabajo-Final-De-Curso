<?php
session_start();
if (!defined("BASE_URL")) define("BASE_URL", "../");
if (!defined("PAGE_URL")) define("PAGE_URL", "../public/");
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit;
}

$id_perfil = $_SESSION['user_id'];
$mensaje = "";
$es_error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    
    $nuevo_nombre = mb_convert_case(trim($_POST['nombre']), MB_CASE_TITLE, "UTF-8");
    $nuevo_apellido = mb_convert_case(trim($_POST['apellido']), MB_CASE_TITLE, "UTF-8");
    $nuevo_telefono = trim($_POST['telefono']); 

    $regex_letras = '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u';
    
    if (!preg_match($regex_letras, $nuevo_nombre) || !preg_match($regex_letras, $nuevo_apellido)) {
        $mensaje = "El nombre y los apellidos solo pueden contener letras.";
        $es_error = true;
    } 
    elseif (!preg_match('/^[0-9]{9}$/', $nuevo_telefono)) {
        $mensaje = "El teléfono debe contener exactamente 9 números.";
        $es_error = true;
    } 
    else {
        try {
            $sql = "UPDATE Usuario SET nombre = ?, apellido = ?, telefono = ? WHERE id_perfil = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nuevo_nombre, $nuevo_apellido, $nuevo_telefono, $id_perfil]);
            
            $_SESSION['nombre_real'] = $nuevo_nombre;
            $mensaje = "¡Datos actualizados correctamente!";
            $es_error = false;
        } catch (PDOException $e) {
            $mensaje = "Error al actualizar los datos en la base de datos.";
            $es_error = true;
        }
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

include '../includes/header.php';
?>

<div class="hero-seccion" style="padding-bottom: 100px;"></div>

<main class="contenedor-perfil" style="margin-top: -80px;">
    <div class="tarjeta-base">
        
        <div class="cabecera-avatar">
            <div class="avatar-grande">
                <?= strtoupper(substr($user['nombre'] ?? 'U', 0, 1)) ?>
            </div>
            <h1 style="font-family: var(--font-title); margin: 0; font-size: 2rem;">Mi Perfil</h1>
            <p style="opacity: 0.9; margin-top: 5px;">Gestiona tu información personal</p>
        </div>

        <div class="cuerpo-perfil">
            <?php if ($mensaje): ?>
                <div class="<?= $es_error ? 'alerta-error' : 'alerta-exito' ?>" 
                     style="text-align: center; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; 
                     <?= $es_error ? 'background-color: #fee2e2; color: #991b1b; border: 1px solid #f87171;' : 'background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0;' ?>">
                    <?= $es_error ? '⚠️' : '✨' ?> <?= $mensaje ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="perfil.php" class="grid-formularios">
                <div class="col-span-2">
                    <label>Correo Electrónico (No editable)</label>
                    <input type="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" disabled class="input-disabled">
                </div>

                <div class="grupo-entrada">
                    <label>Nombre</label>
                    <input type="text" 
                           name="nombre" 
                           value="<?= htmlspecialchars($user['nombre'] ?? '') ?>" 
                           required 
                           class="input-control"
                           pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+"
                           title="Solo se permiten letras"
                           style="text-transform: capitalize;">
                </div>

                <div class="grupo-entrada">
                    <label>Apellidos</label>
                    <input type="text" 
                           name="apellido" 
                           value="<?= htmlspecialchars($user['apellido'] ?? '') ?>" 
                           required 
                           class="input-control"
                           pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+"
                           title="Solo se permiten letras"
                           style="text-transform: capitalize;">
                </div>

                <div class="col-span-2 grupo-entrada">
                    <label>Teléfono de contacto</label>
                    <input type="tel" 
                           name="telefono" 
                           value="<?= htmlspecialchars($user['telefono'] ?? '') ?>" 
                           required 
                           class="input-control"
                           pattern="[0-9]{9}" 
                           maxlength="9"
                           title="Debe contener exactamente 9 números"
                           placeholder="Ej: 612345678">
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

<?php include '../includes/footer.php'; ?>