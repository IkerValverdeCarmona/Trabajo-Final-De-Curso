<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - LcQuiromasajes</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #FFF7EE 0%, #FDF2D8 100%); min-height: 100vh; display: flex; justify-content: center; align-items: center; margin: 0; line-height: 1.6;}
        .card-auth { background-color: #FFFFFF; padding: 40px; border-radius: 20px; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05); width: 100%; max-width: 400px; text-align: center; box-sizing: border-box; }
        h2 { font-family: 'Playfair Display', serif; color: #333; margin-bottom: 30px; font-size: 28px;}
        input { width: 100%; padding: 12px 15px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 12px; box-sizing: border-box; font-family: 'Poppins', sans-serif; font-size: 14px;}
        button { width: 100%; padding: 14px; margin-top: 10px; background-color: #EB6250; color: white; border: none; border-radius: 50px; font-size: 16px; font-weight: 500; cursor: pointer; transition: background-color 0.3s ease; font-family: 'Poppins', sans-serif; }
        button:hover { background-color: #D75443; }
        .link-footer { display: block; margin-top: 25px; color: #666; text-decoration: none; font-size: 14px; }
        .link-footer:hover { color: #EB6250; }
        .alerta-exito { background-color: #e8f5e9; color: #2d9c32; padding: 10px; border-radius: 12px; margin-bottom: 20px; font-size: 14px; }
        .alerta-error { background-color: #e8f5e9; color: #c62828; padding: 10px; border-radius: 12px; margin-bottom: 20px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="card-auth">
        <h2>Bienvenido de nuevo</h2>
        <?php if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'registro_exito'): ?>
            <div class="alerta-exito">¡Cuenta creada! Ya puedes iniciar sesión.</div>
        <?php endif; ?>
        <?php if(isset($_GET['error'])): ?>
            <div class="alerta-error">Correo o contraseña incorrectos.</div>
        <?php endif; ?>
        <form action="procesar_login.php" method="POST">
            <input type="email" name="email" placeholder="Tu correo electrónico" required>
            <input type="password" name="password" placeholder="Tu contraseña" required>
            <button type="submit">Entrar</button>
        </form>
        <?php 
            require_once '../includes/google_config.php'; 
            $google_login_url = $client->createAuthUrl();
            ?>

            <div style="margin-top: 20px; text-align: center;">
                <p style="color: #666; font-size: 0.9rem;">O también puedes</p>
                <a href="<?php echo $google_login_url; ?>" style="display: flex; align-items: center; justify-content: center; gap: 10px; border: 1px solid #ddd; padding: 10px; border-radius: 50px; text-decoration: none; color: #333; font-weight: 500;">
                    <img src="https://imgs.search.brave.com/k2-S1pIsJJWeoDgoAUhsAtERnbHDVnxX8Bvunk641Z8/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9zdGF0/aWMudmVjdGVlenku/Y29tL3N5c3RlbS9y/ZXNvdXJjZXMvdGh1/bWJuYWlscy8wNjAv/MzAxLzkyMC9zbWFs/bC9nb29nbGUtbG9n/by1vbi1idXR0b24t/ZnJlZS1wbmcucG5n" width="50" alt="Google Logo">
                    Continuar con Google
                </a>
            </div>
        <a href="registro.php" class="link-footer">¿No tienes cuenta? Registrate aquí</a>
        <a href="../index.php" class="link-footer" style="margin-top: 10px; font-size: 12px;">← Volver al inicio</a>
    </div>
    
</body>
</html>