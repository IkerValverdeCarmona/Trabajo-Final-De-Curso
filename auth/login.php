<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - LC Quiromasajes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css"> 
</head>
<body class="auth-body">
    <div class="card-auth">
        <h2>Bienvenido de nuevo</h2>
        
        <?php if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'registro_exito'): ?>
            <div class="alerta-exito">¡Cuenta creada! Ya puedes iniciar sesión.</div>
        <?php endif; ?>
        
        <?php if(isset($_GET['error'])): ?>
            <div class="alerta-error">Correo o contraseña incorrectos.</div>
        <?php endif; ?>
        
        <form action="procesar_login.php" method="POST" style="box-shadow: none; padding: 0; background: transparent; max-width: 100%;">
            <div class="grupo-entrada">
                <input type="email" name="email" placeholder="Tu correo electrónico" required class="input-control">
            </div>
            <div class="grupo-entrada">
                <input type="password" name="password" placeholder="Tu contraseña" required class="input-control">
            </div>
            <button type="submit" class="btn btn-primary boton-enviar">Entrar</button>
        </form>
        
        <?php 
            require_once '../includes/google_config.php'; 
            $google_login_url = $client->createAuthUrl();
        ?>

        <div style="margin-top: 20px; text-align: center;">
            <p style="color: var(--color-text-muted); font-size: 0.9rem;">O también puedes</p>
            <a href="<?php echo $google_login_url; ?>" class="btn-google">
                <img src="https://imgs.search.brave.com/k2-S1pIsJJWeoDgoAUhsAtERnbHDVnxX8Bvunk641Z8/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9zdGF0/aWMudmVjdGVlenku/Y29tL3N5c3RlbS9y/ZXNvdXJjZXMvdGh1/bWJuYWlscy8wNjAv/MzAxLzkyMC9zbWFs/bC9nb29nbGUtbG9n/by1vbi1idXR0b24t/ZnJlZS1wbmcucG5n" width="30" alt="Google Logo" style="border-radius: 50%;">
                Continuar con Google
            </a>
        </div>
        
        <a href="registro.php" class="link-footer">¿No tienes cuenta? Regístrate aquí</a>
        <a href="../index.php" class="link-footer" style="margin-top: 10px; font-size: 0.8rem;">← Volver al inicio</a>
    </div>
</body>
</html>