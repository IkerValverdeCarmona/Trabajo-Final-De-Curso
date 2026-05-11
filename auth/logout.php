<?php
session_start();
require_once '../includes/db.php';

// Si el usuario está logueado, gestionamos su carrito antes de destruir la sesión
if (isset($_SESSION['user_id'])) {
    $id_perfil = $_SESSION['user_id'];
    
    try {
        // 1. Primero limpiamos cualquier carrito viejo que tuviera guardado en la BD
        $stmt = $pdo->prepare("DELETE FROM Carrito WHERE id_perfil = ?");
        $stmt->execute([$id_perfil]);
        
        // 2. Si su carrito actual tiene productos, los guardamos en la BD
        if (!empty($_SESSION['carrito'])) {
            $stmtInsert = $pdo->prepare("INSERT INTO Carrito (id_perfil, id_producto, cantidad) VALUES (?, ?, ?)");
            foreach ($_SESSION['carrito'] as $id_prod => $item) {
                $stmtInsert->execute([$id_perfil, $id_prod, $item['cantidad']]);
            }
        }
    } catch (PDOException $e) {
        // Si hay un error con la BD, lo registramos pero dejamos que el usuario salga
        error_log("Error al guardar el carrito: " . $e->getMessage());
    }
}

// Limpiamos y destruimos la sesión
session_unset();
session_destroy();

// Redirigimos a la página principal
header("Location: ../index.php");
exit;
?>