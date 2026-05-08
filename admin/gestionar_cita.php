<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['rol'])) {
    $id_cita = $_POST['id_cita'];
    $nuevo_estado = $_POST['nuevo_estado'];

    $sql = "UPDATE Citas SET estado = ? WHERE id_cita = ?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$nuevo_estado, $id_cita])) {
        header("Location: admin.php?res=ok");
    } else {
        header("Location: admin.php?res=error");
    }
    exit;
}