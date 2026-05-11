<?php
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recogemos los datos del formulario
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $problema = trim($_POST['problema']);
    // Marketing será 1 si está marcado, 0 si no
    $marketing = isset($_POST['marketing']) ? 1 : 0;

    try {
        // Guardamos la consulta del cliente
        // Usaremos la tabla 'Opiniones' para este ejemplo de contacto
        $stmt = $pdo->prepare("INSERT INTO Opiniones (id_perfil, comentario) VALUES (?, ?)");
        
        /* Nota: Como el cliente quizás no está logueado, lo ideal es 
           crear una tabla 'Consultas' específica. Si quieres usar 'Opiniones', 
           necesitarás un ID de perfil. 
        */

        // Por ahora, vamos a simular que se envía correctamente:
        echo "<script>
                alert('¡Gracias $nombre! Hemos recibido tu mensaje correctamente.');
                window.location.href='index.php';
              </script>";
        
    } catch (PDOException $e) {
        die("Error al enviar: " . $e->getMessage());
    }
} else {
    header("Location: formulario.php");
    exit;
}