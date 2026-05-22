<?php
session_start();
if (!defined("BASE_URL")) define("BASE_URL", "../");
if (!defined("PAGE_URL")) define("PAGE_URL", "../public/");
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit;
}
$id_perfil_actual = $_SESSION['user_id']; 
$mensaje = "";
$margen_limpieza = 5;

function obtenerHorasOcupadas($pdo, $id_trabajador, $fecha) {
    $sql = "SELECT DATE_FORMAT(fecha_hora, '%H:%i') FROM Citas WHERE id_trabajador = ? AND DATE(fecha_hora) = ? AND estado != 'Cancelada'";
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
        if (!in_array($hora_string, $ocupadas)) $libres[] = $hora_string;
        $actual = strtotime("+$intervalo minutes", $actual);
    }
    return $libres;
}

$id_trabajador = $_POST['id_trabajador'] ?? null;
$fecha_elegida = $_POST['fecha'] ?? date('Y-m-d');
$id_servicio = $_POST['id_servicio'] ?? null;

$trabajadores = $pdo->query("SELECT id_trabajador, nombre FROM Trabajadores")->fetchAll();
$servicios = $pdo->query("SELECT id_servicio, nombre, duracion_minutos, precio_actual FROM Servicios WHERE activo = 1")->fetchAll();

$duracion_servicio = 0; $precio_servicio = 0.00;
if ($id_servicio) {
    foreach ($servicios as $s) {
        if ($s['id_servicio'] == $id_servicio) {
            $duracion_servicio = (int)$s['duracion_minutos'];
            $precio_servicio = (float)$s['precio_actual']; break;
        }
    }
}
$duracion_total = $duracion_servicio + $margen_limpieza;

$horas_mañana = []; $horas_tarde = [];
if ($id_trabajador && $id_servicio) {
    $ocupadas = obtenerHorasOcupadas($pdo, $id_trabajador, $fecha_elegida);
    $horas_mañana = generarHorasDisponibles('09:00', '13:00', $duracion_total, $ocupadas);
    $horas_tarde = generarHorasDisponibles('16:00', '20:00', $duracion_total, $ocupadas);
}

if (isset($_POST['confirmar'])) {
    try {
        $fecha_hora_formateada = $_POST['fecha'] . ' ' . $_POST['hora'] . ':00';
        
        // 1. Capturamos la nota del cliente y la saneamos
        $notas_cliente = isset($_POST['notas_cliente']) ? trim($_POST['notas_cliente']) : '';

        // 2. Añadimos 'notas_cliente' a la consulta SQL
        $sql = "INSERT INTO Citas (id_perfil, id_trabajador, id_servicio, fecha_hora, precio_final, estado, notas_cliente) 
                VALUES (?, ?, ?, ?, ?, 'Pendiente', ?)";
        
        $stmt = $pdo->prepare($sql);
        
        // 3. Pasamos el valor al array del execute (en el mismo orden que los '?')
        $stmt->execute([
            $id_perfil_actual, 
            $_POST['id_trabajador'], 
            $_POST['id_servicio'], 
            $fecha_hora_formateada, 
            $precio_servicio,
            htmlspecialchars($notas_cliente) // Guardamos con seguridad
        ]);
        
        $_SESSION['mensaje_exito'] = "¡Reserva confirmada con éxito! Te esperamos el " . date('d/m/Y', strtotime($_POST['fecha'])) . " a las " . $_POST['hora'] . ".";
        header("Location: " . PAGE_URL . "mis_citas.php");
        exit();
    } catch (PDOException $e) {
        $mensaje = "<div class='alerta-aviso'><strong>Error al procesar la reserva:</strong><br>" . $e->getMessage() . "</div>";
    }
}

include '../includes/header.php';
?>

<div class="hero-seccion">
    <h1>Reserva tu Cita</h1>
    <p>Gestiona tu bienestar eligiendo a tu profesional de confianza.</p>
</div>

<main class="contenedor-principal" style="padding-top: 40px;">
    <div class="tarjeta-base" style="width: 100%; max-width: 550px; padding: 40px;">
        <?php echo $mensaje; ?>

        <form method="POST" id="form-reserva" style="box-shadow: none; padding: 0;">
            <div class="grupo-entrada">
                <label>1. Especialista:</label>
                <select name="id_trabajador" onchange="this.form.submit()" required class="input-control">
                    <option value="">Selecciona un profesional...</option>
                    <?php foreach($trabajadores as $t): ?>
                        <option value="<?= $t['id_trabajador'] ?>" <?= $id_trabajador == $t['id_trabajador'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grupo-entrada">
                <label>2. Fecha de la sesión:</label>
                <input type="date" name="fecha" value="<?= $fecha_elegida ?>" onchange="this.form.submit()" min="<?= date('Y-m-d') ?>" required class="input-control">
            </div>

            <?php if($id_trabajador): ?>
                <div class="grupo-entrada">
                    <label>3. Tratamiento:</label>
                    <select name="id_servicio" onchange="this.form.submit()" required class="input-control">
                        <option value="">Selecciona un tratamiento...</option>
                        <?php foreach($servicios as $s): ?>
                            <option value="<?= $s['id_servicio'] ?>" <?= $id_servicio == $s['id_servicio'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php if($id_servicio): ?>
                    <div class="aviso-reserva">
                        <p style="margin: 0;">Tratamiento de <strong><?= $duracion_servicio ?> min</strong></p>
                        <p style="margin: 5px 0 0 0;">Precio final: <strong><?= number_format($precio_servicio, 2, ',', '.') ?> €</strong></p>
                    </div>

                    <div class="grupo-entrada">
                        <label>4. Horarios Disponibles:</label>
                        <select name="hora" required class="input-control">
                            <?php if(empty($horas_mañana) && empty($horas_tarde)): ?>
                                <option value="">No hay huecos disponibles para este día</option>
                            <?php else: ?>
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
                            <?php endif; ?>
                        </select>
                        <span style="font-size: 0.8rem; color: #888; margin-top: 5px; display: block;">* Incluye 5 min de margen sanitario.</span>
                    </div>
                    <div class="mb-3" style="margin-top: 20px;">
                        <label for="notas_cliente" class="form-label" style="font-weight: 600;">Notas para el terapeuta</label>
                        <textarea 
                            class="form-control" 
                            id="notas_cliente" 
                            name="notas_cliente" 
                            rows="3" 
                            placeholder="Indica si tienes alguna lesión, alergia o preferencia para el tratamiento..."
                            style="border-radius: 12px; border: 1px solid #ddd; padding: 12px;"
                        ></textarea>
                        <small class="text-muted">Opcional: Esta información ayudará a personalizar tu masaje.</small>
                    </div>

                    <button type="submit" name="confirmar" class="btn btn-primary boton-enviar">Confirmar y Pagar en Centro</button>
                <?php endif; ?>
            <?php endif; ?>
        </form>
    </div>
</main>
<?php include '../includes/footer.php'; ?>