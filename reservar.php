<?php
require_once 'includes/db.php';

$mensaje = "";
$duracion_total = 65; // 60 min masaje + 5 min limpieza

// Lógica de obtención de horas ocupadas
function obtenerHorasOcupadas($pdo, $id_trabajador, $fecha) {
    $sql = "SELECT hora FROM Citas WHERE id_trabajador = ? AND fecha = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_trabajador, $fecha]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function generarHorasDisponibles($inicio, $fin, $intervalo, $ocupadas) {
    $libres = [];
    $actual = strtotime($inicio);
    $cierre = strtotime($fin);

    while ($actual + ($intervalo * 60) <= $cierre) {
        $hora_string = date("H:i", $actual);
        if (!in_array($hora_string, $ocupadas)) {
            $libres[] = $hora_string;
        }
        $actual = strtotime("+$intervalo minutes", $actual);
    }
    return $libres;
}

$id_trabajador = $_POST['id_trabajador'] ?? null;
$fecha_elegida = $_POST['fecha'] ?? date('Y-m-d');

$ocupadas = ($id_trabajador) ? obtenerHorasOcupadas($pdo, $id_trabajador, $fecha_elegida) : [];
$horas_mañana = generarHorasDisponibles('09:00', '13:00', $duracion_total, $ocupadas);
$horas_tarde = generarHorasDisponibles('16:00', '20:00', $duracion_total, $ocupadas);

if (isset($_POST['confirmar'])) {
    try {
        $sql = "INSERT INTO Citas (id_usuario, id_trabajador, id_servicio, fecha, hora) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        // ID de usuario 1 por defecto, cámbialo por $_SESSION si ya tienes login
        $stmt->execute([1, $_POST['id_trabajador'], $_POST['id_servicio'], $_POST['fecha'], $_POST['hora']]);
        $mensaje = "<div class='alert success'>¡Reserva confirmada con éxito para las " . $_POST['hora'] . "!</div>";
    } catch (PDOException $e) {
        $mensaje = "<div class='alert error'>Error en la base de datos.</div>";
    }
}

$trabajadores = $pdo->query("SELECT id_trabajador, nombre FROM Trabajadores")->fetchAll();
$servicios = $pdo->query("SELECT id_servicio, nombre FROM Servicios")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Cita | LcQuiromasajes</title>
    
    <!-- Enlace a tus fuentes de Google -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Enlace a tu archivo CSS principal -->
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .contenedor-reserva {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 50px 20px;
            flex-grow: 1;
        }
        
        .alert {
            width: 100%;
            max-width: 550px;
            padding: 15px;
            border-radius: var(--radius-input);
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
        .hint {
            font-size: 0.8rem;
            color: var(--color-text-muted);
            margin-top: -10px;
            margin-bottom: 20px;
            display: block;
        }
    </style>
</head>
<body>

    <main class="contenedor-reserva">
        <header class="presentacion">
            <h1>Reserva tu Cita</h1>
            <p>Gestiona tu bienestar eligiendo a tu profesional de confianza.</p>
        </header>

        <?php echo $mensaje; ?>

        <form method="POST" id="form-reserva">
            <div class="grupo-entrada">
                <label for="id_trabajador">1. Especialista:</label>
                <select name="id_trabajador" id="id_trabajador" onchange="this.form.submit()" required>
                    <option value="">Selecciona un profesional...</option>
                    <?php foreach($trabajadores as $t): ?>
                        <option value="<?= $t['id_trabajador'] ?>" <?= $id_trabajador == $t['id_trabajador'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grupo-entrada">
                <label for="fecha">2. Fecha de la sesión:</label>
                <input type="date" name="fecha" id="fecha" value="<?= $fecha_elegida ?>" onchange="this.form.submit()" min="<?= date('Y-m-d') ?>" required>
            </div>

            <?php if($id_trabajador): ?>
                <div class="grupo-entrada">
                    <label for="id_servicio">3. Tratamiento:</label>
                    <select name="id_servicio" id="id_servicio" required>
                        <?php foreach($servicios as $s): ?>
                            <option value="<?= $s['id_servicio'] ?>"><?= htmlspecialchars($s['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grupo-entrada">
                    <label for="hora">4. Horarios Disponibles:</label>
                    <select name="hora" id="hora" required>
                        <optgroup label="Turno Mañana">
                            <?php foreach($horas_mañana as $h): ?>
                                <option value="<?= $h ?>"><?= $h ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                        <optgroup label="Turno Tarde">
                            <?php foreach($horas_tarde as $h): ?>
                                <option value="<?= $h ?>"><?= $h ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    </select>
                    <span class="hint">* Intervalos calculados con 5 min de margen sanitario.</span>
                </div>

                <button type="submit" name="confirmar" class="boton-enviar">Confirmar mi Reserva</button>
            <?php else: ?>
                <p style="text-align: center; color: var(--color-text-muted); font-size: 0.9rem; margin-top: 20px;">
                    Por favor, selecciona un profesional para ver las horas disponibles.
                </p>
            <?php endif; ?>
        </form>
    </main>

</body>
</html>