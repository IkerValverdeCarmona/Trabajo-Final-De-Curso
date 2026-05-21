<?php     
if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || 
    (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
    $protocolo = "https";
} else {
    $protocolo = "http";
}
define('BASE_URL', $protocolo . '://' . $_SERVER['HTTP_HOST'] . '/');

$host = 'sql113.byetcluster.com';
$db   = 'if0_41971932_lcquiro';
$user = 'if0_41971932';
$pass = 'Yfd7usEiAovZ'; 
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>