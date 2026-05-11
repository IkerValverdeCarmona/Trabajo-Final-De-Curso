<?php
session_start();
require_once '../includes/db.php';

// 1. SEGURIDAD: Solo admin o trabajador
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'trabajador')) {
    header("Location: ../index.php");
    exit;
}

try {
    // 2. CONSULTA SIN JOIN (Usando subconsultas)
    // Buscamos los datos de Usuario y Producto mediante sub-SELECTs
    $sql = "SELECT 
                fecha_compra, 
                cantidad, 
                precio_unitario_venta,
                notas,
                id_producto,
                id_perfil,
                (SELECT nombre FROM Usuario WHERE id_perfil = Opera.id_perfil) AS cliente_nombre,
                (SELECT apellido FROM Usuario WHERE id_perfil = Opera.id_perfil) AS cliente_apellido,
                (SELECT telefono FROM Usuario WHERE id_perfil = Opera.id_perfil) AS cliente_telefono,
                (SELECT nombre FROM Producto WHERE id_producto = Opera.id_producto) AS producto_nombre
            FROM Opera
            ORDER BY fecha_compra DESC";

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
        <h1 style="font-family: 'Playfair Display', serif; color: #EB6250; font-size: 2.5rem; margin: 0;">Pedidos Recibidos</h1>
        <p style="color: #886752;">Control de productos reservados para recogida en el centro.</p>
    </div>

    <div class="card" style="background: #FFFFFF; padding: 0; overflow: hidden; border-radius: 20px; border: none; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background-color: #FFF7EE;">
                <tr>
                    <th style="padding: 20px; color: #886752;">Fecha</th>
                    <th style="padding: 20px; color: #886752;">Cliente</th>
                    <th style="padding: 20px; color: #886752;">Teléfono</th>
                    <th style="padding: 20px; color: #886752;">Producto</th>
                    <th style="padding: 20px; color: #886752;">Cant.</th>
                    <th style="padding: 20px; color: #886752;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pedidos)): ?>
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: #999;">No hay pedidos registrados todavía.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr style="border-bottom: 1px solid #f9f9f9;">
                            <td style="padding: 15px 20px; color: #666;">
                                <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_compra'])); ?>
                            </td>
                            <td style="padding: 15px 20px;">
                                <div style="font-weight: 600; color: #333;">
                                    <?php echo htmlspecialchars(($pedido['cliente_nombre'] ?? 'Desconocido') . " " . ($pedido['cliente_apellido'] ?? '')); ?>
                                </div>
                            </td>
                            <td style="padding: 15px 20px;">
                                <a href="tel:<?php echo $pedido['cliente_telefono']; ?>" style="color: #EB6250; text-decoration: none; font-weight: 500;">
                                    <?php echo htmlspecialchars($pedido['cliente_telefono'] ?? 'S/T'); ?>
                                </a>
                            </td>
                            <td style="padding: 15px 20px; color: #555;">
                                <?php echo htmlspecialchars($pedido['producto_nombre'] ?? 'Producto eliminado'); ?>
                            </td>
                            <td style="padding: 15px 20px; text-align: center;">
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