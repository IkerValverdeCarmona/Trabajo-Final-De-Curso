<?php
// Cambia lo que hay entre comillas por las contraseñas reales que quieres ponerles
$pass_admin1 = "12345"; 
$pass_admin2 = "admin_seguro";
$pass_trabajadora = "lucia2024";

echo "<h3>Hashes generados:</h3>";
echo "<strong>Copia estos códigos largos y pégalos en tu base de datos:</strong><br><br>";

echo "Admin 1: <br><code>" . password_hash($pass_admin1, PASSWORD_BCRYPT) . "</code><br><br>";
echo "Admin 2: <br><code>" . password_hash($pass_admin2, PASSWORD_BCRYPT) . "</code><br><br>";
echo "Trabajadora: <br><code>" . password_hash($pass_trabajadora, PASSWORD_BCRYPT) . "</code><br><br>";
?>