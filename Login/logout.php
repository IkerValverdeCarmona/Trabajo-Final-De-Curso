<?php
session_start();
session_unset();
session_destroy();
// RUTA ACTUALIZADA A LA PÁGINA PRINCIPAL
header("Location: ../Front/pagina_principal/index.php");
exit;
?>