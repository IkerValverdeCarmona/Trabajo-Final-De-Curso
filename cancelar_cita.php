<?php
session_start();
require_once 'includes/db.php';

// Verificamos que se haya enviado la ID y que el usuario esté logueado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_cita']) && isset($_SESSION['id_perfil'])) {
    
    $id_cita = $_POST['id_cita'];
    $id_perfil = $_SESSION['id_perfil'];

    try {
        // Ejecutamos el UPDATE asegurándonos de que la cita sea del usuario que intenta borrarla
        $sql = "UPDATE Citas SET estado = 'Cancelada' WHERE id_cita = ? AND id_perfil = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_cita, $id_perfil]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['mensaje_exito'] = "Cita cancelada con éxito.";
        } else {
            $_SESSION['mensaje_error'] = "No se pudo cancelar la cita.";
        }

    } catch (PDOException $e) {
        $_SESSION['mensaje_error'] = "Error en el sistema: " . $e->getMessage();
    }
}

// Volvemos al panel de citas
header("Location: mis_citas.php");
exit();