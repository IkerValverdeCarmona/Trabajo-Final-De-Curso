<?php
session_start();
$_SESSION = array();
session_destroy();
header("Location: ../Front/pagina_principal/index.php");
exit;
?>