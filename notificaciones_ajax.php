<?php
session_start();
require_once "includes/System.class.php";
$db = Database::getInstance();

// Consulta básica, ajusta según tu estructura de tabla
$sql = "SELECT * FROM reporte_rescates WHERE estado = 'no validado' ORDER BY fecha DESC LIMIT 5";
$result = $db->dameQuery($sql);

$notificaciones = [];

while ($row = mysqli_fetch_assoc($result)) {
    $notificaciones[] = $row;
}

echo json_encode($notificaciones);
