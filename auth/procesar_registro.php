<?php
require_once '../includes/db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recogemos los datos y los limpiamos
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    // Todos los registros por la web son clientes estándar
    $permiso = 'usuario'; 

    try {
        // Iniciamos transacción para que la inserción doble sea segura
        $pdo->beginTransaction();

        // Insertamos las credenciales en la tabla Perfil
        $sql_perfil = "INSERT INTO Perfil (email, contraseña, permiso) VALUES (?, ?, ?)";
        $stmt_perfil = $pdo->prepare($sql_perfil);
        $stmt_perfil->execute([$email, $password, $permiso]);
        // Capturamos el ID autoincremental recién creado
        $id_perfil_nuevo = $pdo->lastInsertId();
        // Insertamos los datos personales en la tabla Usuario vinculando el ID
        $sql_usuario = "INSERT INTO Usuario (id_perfil, nombre, apellidos, telefono) VALUES (?, ?, ?, ?)";
        $stmt_usuario = $pdo->prepare($sql_usuario);
        $stmt_usuario->execute([$id_perfil_nuevo, $nombre, $apellidos, $telefono]);
        // Confirmamos los cambios en la base de datos
        $pdo->commit();
        // Redirigimos al login con un mensaje de éxito
        header("Location: login.php?mensaje=registro_exito");
        exit();
    } catch (PDOException $e) {
        // Si hay error (ej: correo duplicado), deshacemos todo
        $pdo->rollBack();
        // Redirigimos con error genérico para que no se rompa la vista
        header("Location: registro.php?error=db");
        exit();
    }
} else {
    // Si alguien intenta entrar a este archivo directamente por URL, no lo dejamos entrar y lo mandamos al registro
    header("Location: registro.php");
    exit();
}
?>