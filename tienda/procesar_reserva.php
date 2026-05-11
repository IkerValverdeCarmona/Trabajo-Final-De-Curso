<?php
session_start();
require_once '../includes/db.php';

// Verificación estricta de seguridad
if (!isset($_SESSION['user_id']) || empty($_SESSION['carrito'])) {
    header("Location: index.php");
    exit;
}

$id_perfil = $_SESSION['user_id'];
$fecha_r = $_POST['fecha_recogida'];
$hora_r = $_POST['hora_recogida'];

try {
    // Iniciamos una transacción: o se guardan todos los productos, o ninguno (Seguridad de BD)
    $pdo->beginTransaction();

    foreach ($_SESSION['carrito'] as $id_prod => $item) {
        
        // 1. Insertamos en la tabla Opera (tu tabla de ventas)
        // NOTA: Usamos las columnas exactas de tu captura
        $sql = "INSERT INTO Opera (id_perfil, id_producto, fecha_compra, cantidad, precio_unitario_venta) 
                VALUES (?, ?, NOW(), ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_perfil, $id_prod, $item['cantidad'], $item['precio']]);
        
        // 2. Restamos el stock del producto en la tabla Producto
        $sql_stock = "UPDATE Producto SET stock = stock - ? WHERE id_producto = ?";
        $stmt_stock = $pdo->prepare($sql_stock);
        $stmt_stock->execute([$item['cantidad'], $id_prod]);
    }

    // Si todo ha ido bien, confirmamos los cambios en la BD
    $pdo->commit();

    // Vaciamos el carrito
    $_SESSION['carrito'] = [];
    
    // Mandamos el mensaje de éxito usando los datos de recogida que hemos procesado
    $_SESSION['mensaje_exito'] = "¡Reserva de productos confirmada! Te esperamos el día " . date('d/m/Y', strtotime($fecha_r)) . " por la " . strtolower($hora_r) . " en nuestro centro para la recogida.";
    
    // Redirigimos a Mis Citas (o donde prefieras que vea el mensaje de éxito)
    header("Location: ../mis_citas.php");
    exit;

} catch (PDOException $e) {
    // Si algo falla, revertimos todo para no dejar bases de datos a medias
    $pdo->rollBack();
    die("Error crítico al procesar la reserva: " . $e->getMessage());
}