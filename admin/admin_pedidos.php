<?php
session_start();
require_once '../includes/db.php';

// Seguridad: Solo admin o trabajador
if (!isset($_SESSION['user_id']) || !isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'trabajador')) {
    header("Location: ../index.php");
    exit;
}

try {
    // Consulta sin JOIN usando subconsultas para traer datos de otras tablas
    $sql = "SELECT 
                fecha_compra, 
                cantidad, 
                precio_unitario_venta, 
                id_producto, 
                id_perfil, 
                estado,
                (SELECT nombre FROM Usuario WHERE id_perfil = Opera.id_perfil) AS cliente_nombre,
                (SELECT apellido FROM Usuario WHERE id_perfil = Opera.id_perfil) AS cliente_apellido,
                (SELECT telefono FROM Usuario WHERE id_perfil = Opera.id_perfil) AS cliente_telefono,
                (SELECT nombre FROM Producto WHERE id_producto = Opera.id_producto) AS producto_nombre
            FROM Opera
            ORDER BY fecha_compra DESC";

    $stmt = $pdo->query($sql);
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Error en admin_pedidos: " . $e->getMessage());
    $pedidos = [];
}

include '../includes/header.php';
?>

<div style="padding: 40px 5%; max-width: 1400px; margin: 0 auto; font-family: 'Poppins', sans-serif;">
    
    <div style="margin-bottom: 20px;">
        <a href="index.php" style="color: #666; text-decoration: none; font-size: 0.9rem;">← Volver al Panel</a>
    </div>

    <div style="margin-bottom: 30px;">
        <h1 style="font-family: 'Playfair Display', serif; color: #EB6250; font-size: 2.5rem; margin: 0;">Gestión de Pedidos</h1>
        <p style="color: #886752;">Administra las reservas de productos y su estado de entrega.</p>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'actualizado'): ?>
        <div style="background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 12px; margin-bottom: 20px; font-size: 0.9rem; border: 1px solid #c8e6c9;">
            ✓ Estado del pedido actualizado correctamente.
        </div>
    <?php endif; ?>

    <div style="background: #FFFFFF; border-radius: 20px; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background-color: #FFF7EE;">
                <tr>
                    <th style="padding: 20px; color: #886752;">Fecha</th>
                    <th style="padding: 20px; color: #886752;">Cliente</th>
                    <th style="padding: 20px; color: #886752;">Producto</th>
                    <th style="padding: 20px; color: #886752; text-align: center;">Total</th>
                    <th style="padding: 20px; color: #886752;">Estado</th>
                    <th style="padding: 20px; color: #886752; text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pedidos)): ?>
                    <tr>
                        <td colspan="6" style="padding: 50px; text-align: center; color: #999;">No hay pedidos en la base de datos.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr style="border-bottom: 1px solid #f9f9f9; transition: background 0.3s;" onmouseover="this.style.background='#fffcf8'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 15px 20px; font-size: 0.85rem; color: #666;">
                                <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_compra'])); ?>
                            </td>
                            <td style="padding: 15px 20px;">
                                <div style="font-weight: 600; color: #333;"><?php echo htmlspecialchars($pedido['cliente_nombre'] . " " . $pedido['cliente_apellido']); ?></div>
                                <div style="font-size: 0.8rem; color: #EB6250;"><?php echo htmlspecialchars($pedido['cliente_telefono']); ?></div>
                            </td>
                            <td style="padding: 15px 20px; color: #555;">
                                <?php echo htmlspecialchars($pedido['producto_nombre']); ?>
                                <span style="font-size: 0.8rem; color: #999;">(x<?php echo $pedido['cantidad']; ?>)</span>
                            </td>
                            <td style="padding: 15px 20px; font-weight: 700; color: #333; text-align: center;">
                                <?php echo number_format($pedido['cantidad'] * $pedido['precio_unitario_venta'], 2); ?>€
                            </td>
                            <td style="padding: 15px 20px;">
                                <?php 
                                    $bg = '#F39C12'; // Pendiente (Naranja)
                                    if ($pedido['estado'] == 'Entregado') $bg = '#27AE60'; // Verde
                                    if ($pedido['estado'] == 'Cancelado') $bg = '#E74C3C'; // Rojo
                                ?>
                                <span style="background: <?php echo $bg; ?>; color: white; padding: 5px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 600; display: inline-block;">
                                    <?php echo $pedido['estado']; ?>
                                </span>
                            </td>
                            <td style="padding: 15px 20px; text-align: center;">
                                <?php if ($pedido['estado'] == 'Pendiente'): ?>
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <a href="actualizar_estado.php?nuevo_estado=Entregado&id_perfil=<?php echo $pedido['id_perfil']; ?>&id_producto=<?php echo $pedido['id_producto']; ?>&fecha=<?php echo urlencode($pedido['fecha_compra']); ?>" 
                                           title="Marcar como Entregado" 
                                           style="background: #27AE60; color: white; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 8px; text-decoration: none; font-weight: bold;">✓</a>
                                        
                                        <a href="actualizar_estado.php?nuevo_estado=Cancelado&id_perfil=<?php echo $pedido['id_perfil']; ?>&id_producto=<?php echo $pedido['id_producto']; ?>&fecha=<?php echo urlencode($pedido['fecha_compra']); ?>" 
                                           title="Cancelar Pedido" 
                                           onclick="return confirm('¿Seguro que quieres cancelar este pedido?')"
                                           style="background: #E74C3C; color: white; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 8px; text-decoration: none; font-weight: bold;">✕</a>
                                    </div>
                                <?php else: ?>
                                    <span style="color: #ccc; font-size: 0.75rem;">Completado</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>