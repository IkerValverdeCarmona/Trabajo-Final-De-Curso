<?php
session_start();

$host = 'localhost';
$dbname = 'LcQuiromasajes';
$user = 'root';

$pass = '';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($accion === 'registro') {
        if (empty($email) || empty($password)) {
            die("El email y la contraseña son obligatorios.");
        }
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO Perfil (email, contraseña, permiso) VALUES (?, ?, 'usuario')");
            $stmt->execute([$email, $passwordHash]);

            $id_perfil = $pdo->lastInsertId();
            $stmtUsuario = $pdo->prepare("INSERT INTO Usuario (id_perfil) VALUES (?)");
            $stmtUsuario->execute([$id_perfil]);

            $pdo->commit();
            // Redirige al mismo index.html del Login
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
        if (empty($email) || empty($password)) {
            die("El email y la contraseña son obligatorios.");
        }
        try {
            $stmt = $pdo->prepare("SELECT id_perfil, contraseña, permiso FROM Perfil WHERE email = ?");
            $stmt->execute([$email]);
            $perfil = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($perfil && password_verify($password, $perfil['contraseña'])) {
                $_SESSION['id_perfil'] = $perfil['id_perfil'];
                $_SESSION['permiso'] = $perfil['permiso'];
                $_SESSION['email'] = $email;

                // RUTA ACTUALIZADA A LA PÁGINA PRINCIPAL
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