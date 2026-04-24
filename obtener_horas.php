<?php
// obtener_horas.php
require_once 'includes/db.php';

// Comprobamos que nos han enviado una fecha
if (isset($_GET['fecha'])) {
    $fecha = $_GET['fecha'];

    try {
        // Buscamos todas las horas reservadas para esa fecha específica
        // Usamos TIME() para extraer solo la hora de la columna fecha_hora (ej: 10:00:00)
        $stmt = $pdo->prepare("
            SELECT TIME(fecha_hora) as hora_reservada 
            FROM Citas 
            WHERE DATE(fecha_hora) = ? AND estado != 'Cancelado'
        ");
        $stmt->execute([$fecha]);
        
        // PDO::FETCH_COLUMN nos devuelve un array simple solo con las horas: ['10:00:00', '16:00:00']
        $reservas = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Devolvemos la respuesta en formato JSON para que JavaScript la entienda
        echo json_encode(['ocupadas' => $reservas]);

    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error en la base de datos']);
    }
}
?>