<?php
$host = 'localhost';
$dbname = 'LcQuiromasajes';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h3>Actualizando contraseñas...</h3>";

    // Escribe aquí las contraseñas reales que quieras asignarles
    $usuarios = [
        [
            'email' => 'lidia@lcquiromasajes.com', 
            'nueva_pass' => '12345'
        ],
        [
            'email' => 'iker@lcquiromasajes.com', 
            'nueva_pass' => '12345'
        ],
        [
            'email' => 'laura_staff@lcquiromasajes.com', 
            'nueva_pass' => '12345'
        ]
    ];

    // Actualizamos apuntando a la columna "contraseña"
    $stmt = $pdo->prepare("UPDATE Perfil SET contraseña = ? WHERE email = ?");

    foreach ($usuarios as $u) {
        $hash_perfecto = password_hash($u['nueva_pass'], PASSWORD_BCRYPT);
        $stmt->execute([$hash_perfecto, $u['email']]);
        
        if ($stmt->rowCount() > 0) {
            echo "✅ El usuario <b>" . $u['email'] . "</b> se ha actualizado correctamente.<br>";
        } else {
            echo "❌ No se pudo actualizar <b>" . $u['email'] . "</b>.<br>";
        }
    }

    echo "<br><br><a href='../Front/pagina_principal/index.php'>Ir al Login para probar</a>";

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>