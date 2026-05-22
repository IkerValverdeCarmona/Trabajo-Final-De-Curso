<?php
$host = 'sql113.byetcluster.com';
$db   = 'if0_41971932_lcquiro';
$user = 'if0_41971932';
$pass = 'Yfd7usEiAovZ';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    echo "<h1>¡CONEXIÓN PERFECTA, IKER! ERES UN MÁQUINA.</h1>";
} catch (PDOException $e) {
    echo "<h1>Sigue fallando: " . $e->getMessage() . "</h1>";
}
?>
