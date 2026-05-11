<?php
session_start();
require_once '../includes/db.php';

// 1. SEGURIDAD: Solo admin o trabajador
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'trabajador')) {
    header("Location: ../index.php");
    exit;
}

$rol_actual = $_SESSION['rol'];

try {
    // 2. CONSULTA PARA OBTENER LOS PEDIDOS Y DATOS DEL USUARIO
    // Unimos la tabla Opera con Usuario y Producto
    $sql = "SELECT 
                o.fecha_compra, 
                o.cantidad, 
                o.precio_unitario_venta,
                o.notas,
                u.nombre AS cliente_nombre, 
                u.apellido AS cliente_apellido, 
                u.telefono AS cliente_telefono,
                p.nombre AS producto_nombre
            FROM Opera o
            JOIN Usuario u ON o.id_perfil = u.id_perfil
            JOIN Producto p ON o.id_producto = p.id_producto
            ORDER BY o.fecha_compra DESC";

    $stmt = $pdo->query($sql);
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Error al consultar pedidos: " . $e->getMessage());
    $pedidos = [];
}

include '../includes/header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css?v=<?php echo time(); ?>">

<div style="padding: 40px 5%; max-width: 1400px; margin: 0 auto; font-family: 'Poppins', sans-serif;">
    
    <div style="margin-bottom: 20px;">
        <a href="index.php" style="color: #666; text-decoration: none; font-size: 0.9rem;">← Volver al Panel</a>
    </div>

    <div style="margin-bottom: 30px;">
        <h1 style="font-family: 'Playfair Display', serif; color: #EB6250; font-size: 2.5rem; margin: 0;">Gestión de Pedidos</h1>
        <p style="color: #886752;">Listado de productos reservados por los clientes para recogida en centro.</p>
    </div>

    <div class="card" style="background: #FFFFFF; padding: 0; overflow: hidden; border-radius: 20px; border: none; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background-color: #FFF7EE;">
                <tr>
                    <th style="padding: 20px; color: #886752; font-weight: 600;">Fecha</th>
                    <th style="padding: 20px; color: #886752; font-weight: 600;">Cliente</th>
                    <th style="padding: 20px; color: #886752; font-weight: 600;">Contacto</th>
                    <th style="padding: 20px; color: #886752; font-weight: 600;">Producto</th>
                    <th style="padding: 20px; color: #886752; font-weight: 600;">Cant.</th>
                    <th style="padding: 20px; color: #886752; font-weight: 600;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pedidos)): ?>
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: #999;">No hay pedidos registrados todavía.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr style="border-bottom: 1px solid #f9f9f9; transition: background 0.3s;" onmouseover="this.style.background='#fffcf8'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 15px 20px; color: #666; font-size: 0.9rem;">
                                <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_compra'])); ?>
                            </td>
                            <td style="padding: 15px 20px;">
                                <div style="font-weight: 600; color: #333;">
                                    <?php echo htmlspecialchars($pedido['cliente_nombre'] . " " . $pedido['cliente_apellido']); ?>
                                </div>
                            </td>
                            <td style="padding: 15px 20px; color: #EB6250; font-weight: 500;">
                                <?php echo htmlspecialchars($pedido['cliente_telefono']); ?>
                            </td>
                            <td style="padding: 15px 20px; color: #555;">
                                <?php echo htmlspecialchars($pedido['producto_nombre']); ?>
                            </td>
                            <td style="padding: 15px 20px; color: #666; text-align: center;">
                                <?php echo $pedido['cantidad']; ?>
                            </td>
                            <td style="padding: 15px 20px; font-weight: 700; color: #333;">
                                <?php echo number_format($pedido['cantidad'] * $pedido['precio_unitario_venta'], 2); ?>€
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>