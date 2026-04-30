<?php
// Incluimos la conexión que ya configuramos en includes/db.php
require_once 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente_id = $_POST['cliente_id'];
    $servicio_id = $_POST['servicio_id'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];

    try {
        // Preparar la inserción en la tabla Citas
        $sql = "INSERT INTO Citas (id_usuario, id_servicio, fecha, hora) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$cliente_id, $servicio_id, $fecha, $hora]);
        
        $mensaje = "¡Reserva realizada con éxito!";
    } catch (PDOException $e) {
        $mensaje = "Error al reservar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservar Cita — LcQuiromasajes</title>
    <!-- Aplicamos tus fuentes: Playfair Display y Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { 
            /* Fondo con tu degradado corporativo */
            background: linear-gradient(135deg, #FFF7EE 0%, #FDF2D8 100%); 
            font-family: 'Poppins', sans-serif; 
            line-height: 1.6;
        }
        h1 { font-family: 'Playfair Display', serif; color: #333; }
        
        /* Estilo de las Cards según tu guía (shadow y border-radius) */
        .card {
            background: #FFFFFF;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05);
            max-width: 500px;
            margin: 50px auto;
        }

        /* Inputs con el border-radius de 12px que pediste */
        input, select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 12px;
            box-sizing: border-box;
        }

        /* Botón Pill-style con tu color #EB6250 */
        .btn-reservar {
            background-color: #EB6250;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            width: 100%;
            font-weight: 600;
            transition: background 0.3s;
        }

        .btn-reservar:hover {
            background-color: #D75443; /* Efecto hover solicitado */
        }
    </style>
</head>
<body>

<div class="card">
    <h1>Nueva Reserva</h1>
    <?php if(isset($mensaje)) echo "<p>$mensaje</p>"; ?>
    
    <form method="POST">
        <label>Selecciona Servicio:</label>
        <select name="servicio_id" required>
            <!-- Aquí podrías hacer un fetch de la tabla Servicios -->
            <option value="1">Quiromasaje Relajante</option>
            <option value="2">Tratamiento Corporal</option>
        </select>

        <label>Fecha:</label>
        <input type="date" name="fecha" required>

        <label>Hora:</label>
        <input type="time" name="hora" required>
        
        <!-- ID del cliente (lo normal es sacarlo de la sesión) -->
        <input type="hidden" name="cliente_id" value="1">

        <button type="submit" class="btn-reservar">Confirmar Cita</button>
    </form>
</div>

</body>
</html>