<?php
require_once 'includes/db.php';

$mensaje = "";
$margen_limpieza = 5; // Tus 5 minutos sagrados entre citas

// Funciones para calcular los huecos
function obtenerHorasOcupadas($pdo, $id_trabajador, $fecha) {
    $sql = "SELECT DATE_FORMAT(fecha_hora, '%H:%i') FROM Citas WHERE id_trabajador = ? AND DATE(fecha_hora) = ?";
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

// 1. Recoger datos actuales del formulario
$id_trabajador = $_POST['id_trabajador'] ?? null;
$fecha_elegida = $_POST['fecha'] ?? date('Y-m-d');
$id_servicio = $_POST['id_servicio'] ?? null;

// 2. Traer todos los datos necesarios de la BD (Solo servicios activos)
$trabajadores = $pdo->query("SELECT id_trabajador, nombre FROM Trabajadores")->fetchAll();
$servicios = $pdo->query("SELECT id_servicio, nombre, duracion_minutos, precio_actual FROM Servicios WHERE activo = 1")->fetchAll();

// 3. Variables dinámicas para el servicio elegido
$duracion_servicio = 0;
$precio_servicio = 0.00;
$nombre_servicio = "";

if ($id_servicio) {
    foreach ($servicios as $s) {
        if ($s['id_servicio'] == $id_servicio) {
            $duracion_servicio = (int)$s['duracion_minutos'];
            $precio_servicio = (float)$s['precio_actual'];
            $nombre_servicio = $s['nombre'];
            break;
        }
    }
}

// Calculamos el intervalo real: Duración del servicio + 5 min de limpieza
$duracion_total = $duracion_servicio + $margen_limpieza;

// 4. Generar horas SOLO si ya eligió servicio y trabajador
$horas_mañana = [];
$horas_tarde = [];
if ($id_trabajador && $id_servicio) {
    $ocupadas = obtenerHorasOcupadas($pdo, $id_trabajador, $fecha_elegida);
    $horas_mañana = generarHorasDisponibles('09:00', '13:00', $duracion_total, $ocupadas);
    $horas_tarde = generarHorasDisponibles('16:00', '20:00', $duracion_total, $ocupadas);
}

// 5. Guardar la cita definitiva
if (isset($_POST['confirmar'])) {
    try {
        $fecha_hora_formateada = $_POST['fecha'] . ' ' . $_POST['hora'] . ':00';
        $id_perfil_temporal = 1; // Aquí pondrás el ID del usuario logueado en el futuro

        // Insertamos usando el precio real que hemos sacado de la tabla Servicios
        $sql = "INSERT INTO Citas (id_perfil, id_trabajador, id_servicio, fecha_hora, precio_final) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_perfil_temporal, $_POST['id_trabajador'], $_POST['id_servicio'], $fecha_hora_formateada, $precio_servicio]);
        
        $mensaje = "<div class='alert success'>¡Reserva confirmada con éxito! Te esperamos el " . date('d/m/Y', strtotime($_POST['fecha'])) . " a las " . $_POST['hora'] . ".</div>";
    } catch (PDOException $e) {
        $mensaje = "<div class='alert error'>Error al procesar la reserva. Inténtalo de nuevo.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Cita | LcQuiromasajes</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .contenedor-reserva { display: flex; flex-direction: column; align-items: center; padding: 50px 20px; flex-grow: 1; }
        .alert { width: 100%; max-width: 550px; padding: 15px; border-radius: var(--radius-input); margin-bottom: 20px; text-align: center; font-weight: 500; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .hint { font-size: 0.8rem; color: var(--color-text-muted); margin-top: -10px; margin-bottom: 20px; display: block; }
        
        /* Estilo para la caja de resumen de precio y duración */
        .resumen-caja {
            background-color: var(--color-bg-start);
            border-left: 4px solid var(--color-primary);
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 0.95rem;
        }
        .resumen-caja strong { color: var(--color-primary); font-size: 1.1rem; }
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
                    <!-- Al cambiar el servicio, recarga para calcular las horas exactas de esa duración -->
                    <select name="id_servicio" id="id_servicio" onchange="this.form.submit()" required>
                        <option value="">Selecciona un tratamiento...</option>
                        <?php foreach($servicios as $s): ?>
                            <option value="<?= $s['id_servicio'] ?>" <?= $id_servicio == $s['id_servicio'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php if($id_servicio): ?>
                    <div class="resumen-caja">
                        <p>Tratamiento de <strong><?= $duracion_servicio ?> min</strong></p>
                        <p>Precio final: <strong><?= number_format($precio_servicio, 2, ',', '.') ?> €</strong></p>
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
                        <span class="hint">* Cada sesión incluye 5 min adicionales de margen sanitario.</span>
                    </div>

                    <button type="submit" name="confirmar" class="boton-enviar">Confirmar y Pagar en Centro</button>
                <?php endif; ?>
            <?php endif; ?>
        </form>
    </main>

</body>
</html>