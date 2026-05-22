<?php
session_start();
if (!defined("BASE_URL")) define("BASE_URL", "../");
if (!defined("PAGE_URL")) define("PAGE_URL", "../public/");
require_once '../includes/db.php';

// Verificamos que se haya enviado la ID y que el usuario esté logueado
// NOTA: se usa $_SESSION['user_id'], igual que en el resto de la web
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_cita']) && isset($_SESSION['user_id'])) {

    $id_cita   = $_POST['id_cita'];
    $id_perfil = $_SESSION['user_id'];

    try {
        $sql  = "UPDATE Citas SET estado = 'Cancelada' WHERE id_cita = ? AND id_perfil = ?";
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

header("Location: " . PAGE_URL . "mis_citas.php");
exit();