<?php
session_start();
require_once 'includes/db.php';

// 1. SEGURIDAD: Solo usuarios logueados pueden reservar
if (!isset($_SESSION['id_perfil'])) {
    header("Location: login/index.html");
    exit;
}
$id_perfil_actual = $_SESSION['id_perfil'];

$mensaje = "";
$margen_limpieza = 5;

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

// Recoger datos del formulario
$id_trabajador = $_POST['id_trabajador'] ?? null;
$fecha_elegida = $_POST['fecha'] ?? date('Y-m-d');
$id_servicio = $_POST['id_servicio'] ?? null;

// Cargar listas desde BD
$trabajadores = $pdo->query("SELECT id_trabajador, nombre FROM Trabajadores")->fetchAll();
$servicios = $pdo->query("SELECT id_servicio, nombre, duracion_minutos, precio_actual FROM Servicios WHERE activo = 1")->fetchAll();

// Lógica de duración y precio
$duracion_servicio = 0;
$precio_servicio = 0.00;

if ($id_servicio) {
    foreach ($servicios as $s) {
        if ($s['id_servicio'] == $id_servicio) {
            $duracion_servicio = (int)$s['duracion_minutos'];
            $precio_servicio = (float)$s['precio_actual'];
            break;
        }
    }
}

$duracion_total = $duracion_servicio + $margen_limpieza;

// Generar huecos si hay selección
$horas_mañana = [];
$horas_tarde = [];
if ($id_trabajador && $id_servicio) {
    $ocupadas = obtenerHorasOcupadas($pdo, $id_trabajador, $fecha_elegida);
    $horas_mañana = generarHorasDisponibles('09:00', '13:00', $duracion_total, $ocupadas);
    $horas_tarde = generarHorasDisponibles('16:00', '20:00', $duracion_total, $ocupadas);
}

// Guardar reserva y redirigir
if (isset($_POST['confirmar'])) {
    try {
        $fecha_hora_formateada = $_POST['fecha'] . ' ' . $_POST['hora'] . ':00';
        
        $sql = "INSERT INTO Citas (id_perfil, id_trabajador, id_servicio, fecha_hora, precio_final) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_perfil_actual, $_POST['id_trabajador'], $_POST['id_servicio'], $fecha_hora_formateada, $precio_servicio]);
        
        // Creamos el mensaje y saltamos a mis_citas
        $_SESSION['mensaje_exito'] = "¡Reserva confirmada con éxito! Te esperamos el " . date('d/m/Y', strtotime($_POST['fecha'])) . " a las " . $_POST['hora'] . ".";
        header("Location: mis_citas.php");
        exit();
    } catch (PDOException $e) {
        $mensaje = "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 12px; margin-bottom: 20px; text-align: center;'>Error al procesar la reserva.</div>";
    }
}

include 'includes/header.php';
?>

<div style="background-color: #FFF7EE; padding: 40px 20px; text-align: center;">
    <h1 style="font-family: 'Playfair Display', serif; color: #EB6250;">Reserva tu Cita</h1>
    <p style="color: #666; max-width: 600px; margin: 0 auto;">Gestiona tu bienestar eligiendo a tu profesional de confianza.</p>
</div>

<main style="display: flex; flex-direction: column; align-items: center; padding: 40px 20px; flex-grow: 1;">
    
    <div style="width: 100%; max-width: 550px; background: white; padding: 40px; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.05);">
        <?php echo $mensaje; ?>

        <form method="POST" id="form-reserva">
            <div style="margin-bottom: 20px;">
                <label style="font-weight: 500; font-size: 0.9rem; margin-bottom: 8px; display: block;">1. Especialista:</label>
                <select name="id_trabajador" onchange="this.form.submit()" required style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #E0E0E0; font-family: 'Poppins', sans-serif;">
                    <option value="">Selecciona un profesional...</option>
                    <?php foreach($trabajadores as $t): ?>
                        <option value="<?= $t['id_trabajador'] ?>" <?= $id_trabajador == $t['id_trabajador'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="font-weight: 500; font-size: 0.9rem; margin-bottom: 8px; display: block;">2. Fecha de la sesión:</label>
                <input type="date" name="fecha" value="<?= $fecha_elegida ?>" onchange="this.form.submit()" min="<?= date('Y-m-d') ?>" required style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #E0E0E0; font-family: 'Poppins', sans-serif;">
            </div>

            <?php if($id_trabajador): ?>
                <div style="margin-bottom: 20px;">
                    <label style="font-weight: 500; font-size: 0.9rem; margin-bottom: 8px; display: block;">3. Tratamiento:</label>
                    <select name="id_servicio" onchange="this.form.submit()" required style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #E0E0E0; font-family: 'Poppins', sans-serif;">
                        <option value="">Selecciona un tratamiento...</option>
                        <?php foreach($servicios as $s): ?>
                            <option value="<?= $s['id_servicio'] ?>" <?= $id_servicio == $s['id_servicio'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php if($id_servicio): ?>
                    <div style="background-color: #FFF7EE; border-left: 4px solid #EB6250; padding: 15px; margin-bottom: 20px; border-radius: 8px;">
                        <p style="margin: 0;">Tratamiento de <strong><?= $duracion_servicio ?> min</strong></p>
                        <p style="margin: 5px 0 0 0;">Precio final: <strong><?= number_format($precio_servicio, 2, ',', '.') ?> €</strong></p>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="font-weight: 500; font-size: 0.9rem; margin-bottom: 8px; display: block;">4. Horarios Disponibles:</label>
                        <select name="hora" required style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #E0E0E0; font-family: 'Poppins', sans-serif;">
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
                        <span style="font-size: 0.8rem; color: #888; margin-top: 5px; display: block;">* Incluye 5 min de margen sanitario.</span>
                    </div>

                    <button type="submit" name="confirmar" style="background-color: #EB6250; color: white; padding: 15px 30px; border: none; border-radius: 50px; width: 100%; font-weight: 600; font-family: 'Poppins', sans-serif; cursor: pointer;">
                        Confirmar y Pagar en Centro
                    </button>
                <?php endif; ?>
            <?php endif; ?>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>