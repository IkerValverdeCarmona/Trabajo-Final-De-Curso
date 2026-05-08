<?php
session_start();
require_once '../includes/db.php';

if (isset($_GET['id']) && $_SESSION['rol'] === 'admin') {
    $id = $_GET['id'];
    $sql = "DELETE FROM Citas WHERE id_cita = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}

header("Location: admin.php");
exit;