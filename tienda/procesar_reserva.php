<?php
session_start();
require_once '../includes/db.php';

// Verificación estricta de seguridad
if (!isset($_SESSION['user_id']) || empty($_SESSION['carrito'])) {
    header("Location: ../public/mis_pedidos.php");
    exit;
}

$id_perfil = $_SESSION['user_id'];
$fecha_r = $_POST['fecha_recogida'];
$hora_r = $_POST['hora_recogida'];

try {
    $pdo->beginTransaction();

    foreach ($_SESSION['carrito'] as $id_prod => $item) {
        $sql = "INSERT INTO Opera (id_perfil, id_producto, fecha_compra, cantidad, precio_unitario_venta) 
                VALUES (?, ?, NOW(), ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_perfil, $id_prod, $item['cantidad'], $item['precio']]);
        
        $sql_stock = "UPDATE Producto SET stock = stock - ? WHERE id_producto = ?";
        $stmt_stock = $pdo->prepare($sql_stock);
        $stmt_stock->execute([$item['cantidad'], $id_prod]);
    }
    $pdo->commit();
    $_SESSION['carrito'] = [];
    
    $_SESSION['mensaje_exito'] = "¡Reserva de productos confirmada! Te esperamos el día " . date('d/m/Y', strtotime($fecha_r)) . " por la " . strtolower($hora_r) . " en nuestro centro para la recogida.";
    
    
    header("Location: ../public/mis_pedidos.php");
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    die("Error crítico al procesar la reserva: " . $e->getMessage());
}