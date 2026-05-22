<?php
session_start();
require_once '../includes/db.php';

// Verificación de sesión y rol (asegurando que es un administrador o trabajador autorizado)
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_POST['guardar_producto'])) {
    $nombre = $_POST['nombre'];
    $desc = $_POST['descripcion'];
    $precio = $_POST['precio_actual'];
    $stock = $_POST['stock'];
    
    // 1. Imagen por defecto por si el usuario decide no subir ninguna foto
    $nombre_imagen = 'default.jpg';

    // 2. Procesar la imagen si se ha seleccionado un archivo
    if (isset($_FILES['imagen_producto']) && $_FILES['imagen_producto']['error'] === UPLOAD_ERR_OK) {
        $archivo_temporal = $_FILES['imagen_producto']['tmp_name'];
        $nombre_archivo_original = $_FILES['imagen_producto']['name'];
        
        // Obtenemos la extensión del archivo
        $extension = strtolower(pathinfo($nombre_archivo_original, PATHINFO_EXTENSION));
        
        // Definimos las extensiones seguras y permitidas
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($extension, $extensiones_permitidas)) {
            // Creamos un nombre único para evitar que fotos con el mismo nombre se sobrescriban
            $nombre_imagen = uniqid('prod_') . '.' . $extension;
            
            $carpeta_destino = '../assets/img/productos/';
            $ruta_destino = $carpeta_destino . $nombre_imagen;
            
            // Creamos la carpeta automáticamente si por algún motivo no existe
            if (!is_dir($carpeta_destino)) {
                mkdir($carpeta_destino, 0777, true);
            }

            // Movemos el archivo subido a la carpeta final
            if (!move_uploaded_file($archivo_temporal, $ruta_destino)) {
                $error = "Hubo un problema al guardar la imagen en el servidor.";
            }
        } else {
            $error = "Formato de imagen no permitido. Por favor, sube archivos JPG, PNG o WEBP.";
        }
    }

    // 3. Si no ha habido errores con la imagen, guardamos en la Base de Datos
    if (!isset($error)) {
        try {
            // Añadida la columna 'imagen' a la consulta SQL
            $sql = "INSERT INTO Producto (nombre, descripcion, precio_actual, stock, imagen) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $desc, $precio, $stock, $nombre_imagen]);
            
            $_SESSION['mensaje_admin'] = "El producto '$nombre' ha sido publicado en la tienda.";
            header("Location: admin_productos.php");
            exit;
        } catch (PDOException $e) {
            $error = "Error al guardar: " . $e->getMessage();
        }
    }
}

include '../includes/header.php';
?>

<div class="contenedor-admin" style="text-align: center;">
    <div class="cabecera-admin">
        <h1>Nuevo Producto</h1>
    </div>
</div>

<main class="contenedor-principal">
    <div style="width: 100%; max-width: 600px; margin: 0 auto;">
        <a href="admin_productos.php" class="enlace-volver">← Volver al inventario</a>

        <form method="POST" action="nuevo_producto.php" style="max-width: 100%; margin-top: 10px;" enctype="multipart/form-data">
            
            <?php if (isset($error)): ?>
                <div class="alerta-error" style="background-color: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                    ❌ <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <div class="grupo-entrada">
                <label>Nombre del Producto:</label>
                <input type="text" name="nombre" maxlength="50" required class="input-control">
            </div>

            <div class="grupo-entrada">
                <label>Descripción:</label>
                <textarea name="descripcion" maxlength="300" required class="input-control" style="height: 120px;"></textarea>
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="grupo-entrada" style="flex: 1;">
                    <label>Precio (€):</label>
                    <input type="number" step="0.01" name="precio_actual" min="0" required class="input-control">
                </div>
                <div class="grupo-entrada" style="flex: 1;">
                    <label>Stock Inicial:</label>
                    <input type="number" name="stock" min="0" value="0" required class="input-control">
                </div>
            </div>

            <div class="grupo-entrada" style="margin-top: 15px; margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Imagen del Producto (Opcional):</label>
                <input type="file" name="imagen_producto" accept="image/png, image/jpeg, image/webp" class="input-control" style="padding: 10px;">
                <small style="color: #666; font-size: 0.85rem; display: block; margin-top: 5px;">Formatos permitidos: JPG, PNG, WEBP. Si no subes ninguna, se usará una por defecto.</small>
            </div>

            <button type="submit" name="guardar_producto" class="btn btn-primary boton-enviar" style="width: 100%;">
                Publicar Producto
            </button>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>