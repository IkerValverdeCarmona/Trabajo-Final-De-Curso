<?php
session_start();

$host = 'localhost';
$dbname = 'LcQuiromasajes';
$user = 'root';
$pass = '';

try {
    // utf8mb4 es clave aquí para que lea la "ñ" correctamente
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $password_input = $_POST['password'] ?? ''; 

    if ($accion === 'registro') {
        if (empty($email) || empty($password_input)) {
            die("El email y la contraseña son obligatorios.");
        }
        
        $passwordHash = password_hash($password_input, PASSWORD_BCRYPT);

        try {
            $pdo->beginTransaction();
            
            // Insertamos usando la columna "contraseña"
            $stmt = $pdo->prepare("INSERT INTO Perfil (email, contraseña, permiso) VALUES (?, ?, 'usuario')");
            $stmt->execute([$email, $passwordHash]);

            $id_perfil = $pdo->lastInsertId();
            $stmtUsuario = $pdo->prepare("INSERT INTO Usuario (id_perfil) VALUES (?)");
            $stmtUsuario->execute([$id_perfil]);

            $pdo->commit();
            echo "<script>alert('Registro exitoso. Ya puedes iniciar sesión.'); window.location.href='index.html';</script>";
        }
        catch (PDOException $e) {
            $pdo->rollBack();
            if ($e->getCode() == 23000) {
                echo "<script>alert('Ese correo electrónico ya está registrado.'); window.history.back();</script>";
            }
            else {
                die("Error en el registro: " . $e->getMessage());
            }
        }
    }
    elseif ($accion === 'login') {
        if (empty($email) || empty($password_input)) {
            die("El email y la contraseña son obligatorios.");
        }
        try {
            // Buscamos usando la columna "contraseña"
            $stmt = $pdo->prepare("SELECT id_perfil, contraseña, permiso FROM Perfil WHERE email = ?");
            $stmt->execute([$email]);
            $perfil = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificamos el hash comparando con $perfil['contraseña']
            if ($perfil && password_verify($password_input, $perfil['contraseña'])) {
                $_SESSION['id_perfil'] = $perfil['id_perfil'];
                $_SESSION['permiso'] = $perfil['permiso'];
                $_SESSION['email'] = $email;

                header("Location: ../Front/pagina_principal/index.php");
                exit;
            }
            else {
                echo "<script>alert('Correo o contraseña incorrectos.'); window.history.back();</script>";
            }
        }
        catch (PDOException $e) {
            die("Error en el inicio de sesión: " . $e->getMessage());
        }
    }
}
?>