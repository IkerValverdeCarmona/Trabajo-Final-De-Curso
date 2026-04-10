<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: acceso.php"); 
    exit;
}
$host = 'localhost';
$dbname = 'LcQuiromasajes';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buscamos los datos usando el email que guardamos en la sesión
    $stmt = $pdo->prepare("SELECT email, permiso FROM Perfil WHERE email = ?");
    $stmt->execute([$_SESSION['email']]);
    $datos_usuario = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - LC Quiromasajes</title>
    <link rel="stylesheet" href="style.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body style="background: linear-gradient(to bottom, #FFF7EE, #FDF2D8); font-family: 'Poppins', sans-serif;">

    <main style="max-width: 600px; margin: 50px auto; padding: 20px;">
        
        <div class="card-perfil" style="background-color: #FFFFFF; border-radius: 20px; padding: 40px; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05); text-align: center;">
            
            <h1 style="font-family: 'Playfair Display', serif; color: #333; margin-bottom: 5px;">Mi Cuenta</h1>
            <p style="color: #666; margin-bottom: 30px;">Gestiona tus datos y citas</p>

            <div style="text-align: left; margin-bottom: 30px; line-height: 1.6;">
                <p><strong>Email registrado:</strong> <?php echo htmlspecialchars($datos_usuario['email']); ?></p>
                <p><strong>Tipo de cuenta:</strong> <span style="text-transform: capitalize;"><?php echo htmlspecialchars($datos_usuario['permiso']); ?></span></p>
                </div>

            <div style="display: flex; gap: 15px; justify-content: center;">
                <a href="mis_citas.php" class="btn" style="background-color: #EB6250; color: white; padding: 12px 30px; border-radius: 50px; text-decoration: none; font-weight: 500; transition: background 0.3s;">
                    Ver mis citas
                </a>
                
                <a href="../../login/logout.php" class="btn-logout" style="background-color: #f8f9fa; color: #dc3545; border: 1px solid #dc3545; padding: 12px 30px; border-radius: 50px; text-decoration: none; font-weight: 500; transition: all 0.3s;">
                    Cerrar Sesión
                </a>
            </div>

        </div>
    </main>

</body>
</html>