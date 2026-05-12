<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - LC Quiromasajes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css"> 
</head>
<body class="auth-body">

<div class="card-auth">
    <h2>Únete a LC Quiromasajes</h2>
    <form action="procesar_registro.php" method="POST" style="box-shadow: none; padding: 0; background: transparent; max-width: 100%;">
        <div class="grupo-entrada">
            <input type="text" name="nombre" placeholder="Tu Nombre" required class="input-control">
        </div>
        <div class="grupo-entrada">
            <input type="text" name="apellidos" placeholder="Tus Apellidos" required class="input-control">
        </div>
        <div class="grupo-entrada">
            <input type="tel" name="telefono" placeholder="Teléfono móvil" required class="input-control">
        </div>
        <div class="grupo-entrada">
            <input type="email" name="email" placeholder="Correo electrónico" required class="input-control">
        </div>
        <div class="grupo-entrada">
            <input type="password" name="password" placeholder="Crea una contraseña" required class="input-control">
        </div>
        <button type="submit" class="btn btn-primary boton-enviar">Crear cuenta</button>
    </form>
    <a href="login.php" class="link-footer">¿Ya tienes cuenta? Inicia sesión aquí</a>
</div>

</body>
</html>