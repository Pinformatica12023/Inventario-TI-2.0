<?php
include("bd.php");

if ($_POST && isset($_POST['dispositivo'])) {
    $dispositivoId = $_POST['dispositivo'];

    $stmtCantidad = $conexion->prepare("SELECT cantidad FROM dispositivos WHERE id = :dispositivo");
    $stmtCantidad->bindParam(":dispositivo", $dispositivoId);
    $stmtCantidad->execute();
    $resultCantidad = $stmtCantidad->fetch(PDO::FETCH_ASSOC);
    $cantidadDisponible = $resultCantidad['cantidad'];

    $stmtPrestados = $conexion->prepare("SELECT COUNT(*) AS cantidad_prestada FROM prestamodispositivo WHERE dispositivo = :dispositivo AND estado = 'Prestado'");
    $stmtPrestados->bindParam(":dispositivo", $dispositivoId);
    $stmtPrestados->execute();
    $resultPrestados = $stmtPrestados->fetch(PDO::FETCH_ASSOC);
    $cantidadPrestada = $resultPrestados['cantidad_prestada'];

    $cantidadRestante = $cantidadDisponible - $cantidadPrestada;

    $info = array(
        'cantidadDisponible' => $cantidadDisponible,
        'cantidadRestante' => $cantidadRestante
    );

    echo json_encode($info);
}
?>