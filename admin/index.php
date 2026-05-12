<?php
session_start();
require_once '../includes/db.php';

// Seguridad: Solo admin o trabajador pueden acceder
if (!isset($_SESSION['user_id']) || !isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'trabajador')) {
    header("Location: ../index.php");
    exit;
}

include '../includes/header.php';
?>

<div class="contenedor-admin" style="text-align: center; padding-top: 60px;">
    <div class="cabecera-admin">
        <h1 style="font-size: 2.8rem;">Panel de Administración</h1>
        <p style="font-size: 1.1rem;">
            Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['nombre_real']); ?></strong>. Centro de gestión de LC Quiromasajes.
        </p>
    </div>
</div>

<main class="contenedor-admin" style="padding-top: 0;">
    <div class="grid-dashboard">
        
        <a href="admin_citas.php" class="tarjeta-dashboard">
            <div>
                <div class="icono-grande">🗓️</div>
                <h2 style="font-family: var(--font-title); margin-bottom: 15px;">Gestión de Citas</h2>
                <p class="text-muted">Revisa las próximas reservas, cambia estados de citas y organiza la agenda diaria del centro.</p>
            </div>
            <div class="enlace-destacado">Ver Agenda →</div>
        </a>

        <a href="admin_pedidos.php" class="tarjeta-dashboard">
            <div>
                <div class="icono-grande">📦</div>
                <h2 style="font-family: var(--font-title); margin-bottom: 15px;">Pedidos y Ventas</h2>
                <p class="text-muted">Consulta qué productos han reservado los clientes, accede a sus datos de contacto y gestiona entregas.</p>
            </div>
            <div class="enlace-destacado">Ver Pedidos →</div>
        </a>

        <a href="admin_productos.php" class="tarjeta-dashboard">
            <div>
                <div class="icono-grande">🧴</div>
                <h2 style="font-family: var(--font-title); margin-bottom: 15px;">Inventario</h2>
                <p class="text-muted">Controla el stock de tus aceites y productos, actualiza precios de venta y añade nuevos artículos.</p>
            </div>
            <div class="enlace-destacado">Gestionar Stock →</div>
        </a>

    </div>

    <div class="consejo-gestion">
        <div class="icono-consejo">💡</div>
        <div>
            <h4 style="margin: 0; font-family: var(--font-title); color: #333;">Consejo de gestión</h4>
            <p style="margin: 5px 0 0 0; color: #888; font-size: 0.9rem;">
                Si un cliente no viene a recoger su pedido en 48h, puedes usar su número de teléfono en la sección de <strong>Pedidos</strong> para contactar con él.
            </p>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>