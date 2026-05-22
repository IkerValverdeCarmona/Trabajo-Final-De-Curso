<?php
session_start();
if (!defined("BASE_URL")) define("BASE_URL", "../");
if (!defined("PAGE_URL")) define("PAGE_URL", "../public/");
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $sql = "INSERT INTO Opiniones (id_perfil, puntuacion, comentario, visible) VALUES (?, ?, ?, 0)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user_id'], $_POST['puntuacion'], $_POST['comentario']]);
    
    header("Location: index.php?msg=Gracias_por_tu_resena");
}
?>
