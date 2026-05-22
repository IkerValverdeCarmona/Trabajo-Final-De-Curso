<?php
session_start();
require_once '../includes/db.php';
// Seguridad: Solo admin o trabajador
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'trabajador')) {
    header("Location: ../index.php");
    exit;
}
// Recogemos los datos de la URL
if (isset($_GET['id_perfil']) && isset($_GET['id_producto']) && isset($_GET['fecha']) && isset($_GET['nuevo_estado'])) {
    $id_perfil = $_GET['id_perfil'];
    $id_producto = $_GET['id_producto'];
    $fecha = $_GET['fecha'];
    $nuevo_estado = $_GET['nuevo_estado'];

    try {
        // Actualizamos el estado en la tabla Opera
        $sql = "UPDATE Opera SET estado = ? 
                WHERE id_perfil = ? AND id_producto = ? AND fecha_compra = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nuevo_estado, $id_perfil, $id_producto, $fecha]);
        // Volvemos al panel con un mensaje de éxito
        header("Location: admin_pedidos.php?msg=actualizado");
        exit;
    } catch (PDOException $e) {
        die("Error al actualizar el estado del pedido: " . $e->getMessage());
    }
} else {
    // Si faltan datos volvemos a la página de los pedidos
    header("Location: admin_pedidos.php");
    exit;
}