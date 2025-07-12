<?php
require_once "../includes/System.class.php";
$db = Database::getInstance();
$accion = $_GET['accion'] ?? $_POST['accion'] ?? null;
switch ($accion) {
case 'grafico':
    $sql = "SELECT 
                DATE_FORMAT(fecha_ingreso, '%M %Y') AS mes,
                COUNT(*) AS total
            FROM animales
            GROUP BY DATE_FORMAT(fecha_ingreso, '%M %Y')
            ORDER BY MIN(fecha_ingreso)";
    
    $res = $db->dameQuery($sql);
    $meses = [];
    $datos = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $meses[] = $row['mes'];
        $datos[] = $row['total'];
    }
    echo json_encode(['labels' => $meses, 'data' => $datos]);
    break;

 }