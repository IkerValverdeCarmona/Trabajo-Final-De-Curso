<?php
session_start();

// 1. Verificación de seguridad
if (!isset($_SESSION['email'])) {
    // Si no hay sesión, mandamos al index (o login)
    header("Location: index.php"); 
    exit;
}

// 2. Cargamos la conexión centralizada
require_once 'includes/db.php'; 

try {
    // Usamos directamente la variable $pdo que definimos en includes/db.php
    $stmt = $pdo->prepare("SELECT email, permiso FROM Perfil WHERE email = ?");
    $stmt->execute([$_SESSION['email']]);
    $datos_usuario = $stmt->fetch();

    if (!$datos_usuario) {
        // Por si acaso el usuario no existe en la BD pero la sesión sigue activa
        session_destroy();
        header("Location: index.php");
        exit;
    }

} catch (PDOException $e) {
    die("Error al cargar el perfil: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - LC Quiromasajes</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body style="background: linear-gradient(to bottom, #FFF7EE, #FDF2D8); font-family: 'Poppins', sans-serif; min-height: 100vh;">

    <main style="max-width: 600px; margin: 80px auto; padding: 20px;">
        
        <div class="card-perfil" style="background-color: #FFFFFF; border-radius: 20px; padding: 40px; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05); text-align: center;">
            
            <h1 style="font-family: 'Playfair Display', serif; color: #333; margin-bottom: 5px; font-weight: 700;">Mi Cuenta</h1>
            <p style="color: #666; margin-bottom: 30px;">Gestiona tus datos y citas</p>

            <div style="text-align: left; margin-bottom: 30px; line-height: 1.6; background: #fdfdfd; padding: 20px; border-radius: 12px; border: 1px solid #eee;">
                <p style="margin-bottom: 10px;">
                    <strong style="color: #EB6250;">Email registrado:</strong><br> 
                    <?php echo htmlspecialchars($datos_usuario['email']); ?>
                </p>
                <p>
                    <strong style="color: #EB6250;">Tipo de cuenta:</strong><br> 
                    <span style="text-transform: capitalize;"><?php echo htmlspecialchars($datos_usuario['permiso']); ?></span>
                </p>
            </div>

            <div style="display: flex; flex-direction: column; gap: 15px; align-items: center;">
                <a href="mis_citas.php" class="btn" style="background-color: #EB6250; color: white; padding: 14px 40px; border-radius: 50px; text-decoration: none; font-weight: 600; transition: background 0.3s; width: 80%;">
                    Ver mis citas
                </a>
                
                <a href="login/logout.php" style="color: #dc3545; text-decoration: none; font-size: 0.9rem; font-weight: 500; margin-top: 10px;">
                    Cerrar Sesión
                </a>
            </div>

        </div>

        <div style="text-align: center; margin-top: 20px;">
            <a href="index.php" style="color: #666; text-decoration: none; font-size: 0.9rem;">← Volver al inicio</a>
        </div>
    </main>

</body>
</html>