<?php
session_start();
require_once '../includes/db.php';

// Seguridad: Solo admin o trabajador
if (!isset($_SESSION['user_id']) || !isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'trabajador')) {
    header("Location: ../index.php");
    exit;
}

try {
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

<div class="contenedor-admin">
    
    <a href="index.php" class="enlace-volver">← Volver al Panel</a>

    <div class="cabecera-admin">
        <h1>Gestión de Pedidos</h1>
        <p>Administra las reservas de productos y su estado de entrega.</p>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'actualizado'): ?>
        <div class="alerta-exito">✓ Estado del pedido actualizado correctamente.</div>
    <?php endif; ?>

    <div class="tarjeta-admin">
        <table class="tabla-admin">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Producto</th>
                    <th style="text-align: center;">Total</th>
                    <th>Estado</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pedidos)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No hay pedidos en la base de datos.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td>
                                <strong><?php echo date('d/m/Y', strtotime($pedido['fecha_compra'])); ?></strong><br>
                                <?php echo date('H:i', strtotime($pedido['fecha_compra'])); ?>
                            </td>
                            <td>
                                <div class="texto-destacado"><?php echo htmlspecialchars($pedido['cliente_nombre'] . " " . $pedido['cliente_apellido']); ?></div>
                                <div class="texto-acento"><?php echo htmlspecialchars($pedido['cliente_telefono']); ?></div>
                            </td>
                            <td>
                                <span class="etiqueta-servicio"><?php echo htmlspecialchars($pedido['producto_nombre']); ?></span>
                                <span class="texto-acento" style="color: #999; margin-left: 5px;">(x<?php echo $pedido['cantidad']; ?>)</span>
                            </td>
                            <td class="texto-destacado" style="text-align: center;">
                                <?php echo number_format($pedido['cantidad'] * $pedido['precio_unitario_venta'], 2); ?>€
                            </td>
                            <td>
                                <?php 
                                    $clase_estado = 'estado-pendiente';
                                    if ($pedido['estado'] == 'Entregado') $clase_estado = 'estado-completado';
                                    if ($pedido['estado'] == 'Cancelado') $clase_estado = 'estado-cancelado';
                                ?>
                                <span class="etiqueta-estado <?php echo $clase_estado; ?>">
                                    <?php echo $pedido['estado']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($pedido['estado'] == 'Pendiente'): ?>
                                    <div class="contenedor-acciones">
                                        <a href="actualizar_estado.php?nuevo_estado=Entregado&id_perfil=<?php echo $pedido['id_perfil']; ?>&id_producto=<?php echo $pedido['id_producto']; ?>&fecha=<?php echo urlencode($pedido['fecha_compra']); ?>" 
                                           title="Marcar como Entregado" class="btn-accion btn-ok">✓</a>
                                        
                                        <a href="actualizar_estado.php?nuevo_estado=Cancelado&id_perfil=<?php echo $pedido['id_perfil']; ?>&id_producto=<?php echo $pedido['id_producto']; ?>&fecha=<?php echo urlencode($pedido['fecha_compra']); ?>" 
                                           title="Cancelar Pedido" onclick="return confirm('¿Seguro que quieres cancelar este pedido?')" class="btn-accion btn-ko">✕</a>
                                    </div>
                                <?php else: ?>
                                    <span style="color: #ccc; font-size: 0.75rem; display: block; text-align: center;">Completado</span>
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