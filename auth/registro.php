<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - LcQuiromasajes</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #FFF7EE 0%, #FDF2D8 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            line-height: 1.6;
        }
        .card-auth {
            background-color: #FFFFFF;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 450px;
            text-align: center;
            box-sizing: border-box;
        }
        h2 {
            font-family: 'Playfair Display', serif;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }
        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }
        input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 12px;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 14px;
            margin-top: 10px;
            background-color: #EB6250;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }
        button:hover {
            background-color: #D75443;
        }
        .link-footer {
            display: block;
            margin-top: 25px;
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }
        .link-footer:hover {
            color: #EB6250;
        }
    </style>
</head>
<body>

<div class="card-auth">
    <h2>Unete a LcQuiromasajes</h2>
    <form action="procesar_registro.php" method="POST">
        <div class="input-group">
            <input type="text" name="nombre" placeholder="Tu Nombre" required>
        </div>
        <div class="input-group">
            <input type="text" name="apellidos" placeholder="Tus Apellidos" required>
        </div>
        <div class="input-group">
            <input type="tel" name="telefono" placeholder="Teléfono móvil" required>
        </div>
        <div class="input-group">
            <input type="email" name="email" placeholder="Correo electrónico" required>
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Crea una contraseña" required>
        </div>
        <button type="submit">Crear cuenta</button>
    </form>
    <a href="login.php" class="link-footer">¿Ya tienes cuenta? Inicia sesión aqui</a>
</div>
</body>
</html>