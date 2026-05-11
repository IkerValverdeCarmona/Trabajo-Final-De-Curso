<?php
require_once 'includes/db.php';

// 1. Pon aquí el correo EXACTO que Marta tiene en tu base de datos
$email_marta = 'marta@lcquiromasajes.com'; 

// 2. Esta será su nueva contraseña
$nueva_password = 'Marta123'; 

// 3. Encriptamos la contraseña como le gusta a PHP
$password_encriptada = password_hash($nueva_password, PASSWORD_DEFAULT);

try {
    // Actualizamos el Perfil de Marta con la contraseña encriptada
    $stmt = $pdo->prepare("UPDATE Perfil SET contraseña = ? WHERE email = ?");
    $stmt->execute([$password_encriptada, $email_marta]);
    
    // Verificamos si realmente se cambió algo
    if ($stmt->rowCount() > 0) {
        echo "<h2 style='color: green;'>¡Éxito! La contraseña de Marta ha sido reparada y encriptada correctamente.</h2>";
        echo "<p>Su nueva contraseña es: <strong>Marta123</strong></p>";
        echo "<a href='auth/login.php'>Ir al Login para probar</a>";
    } else {
        echo "<h2 style='color: red;'>Error: No se encontró a nadie con el correo '$email_marta'.</h2>";
        echo "<p>Revisa en phpMyAdmin cuál es el correo exacto de Marta y cámbialo en este script.</p>";
    }

} catch (PDOException $e) {
    echo "Error en la base de datos: " . $e->getMessage();
}
?>