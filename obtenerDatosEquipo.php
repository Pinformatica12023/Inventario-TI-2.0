<?php
// Reemplaza esta lógica con la conexión a tu base de datos y la consulta adecuada
include("bd.php");

if ($_POST && isset($_POST['modelo'])) {
    $modeloSeleccionado = $_POST['modelo'];

    $sentencia = $conexion->prepare("SELECT serialpc,serialcargador, marca FROM equipos WHERE numeropc = :modelo");
    $sentencia->bindParam(":modelo", $modeloSeleccionado);
    $sentencia->execute();
    
    $datosEquipo = $sentencia->fetch(PDO::FETCH_ASSOC);

    // Devolver los datos como respuesta en formato JSON
    echo json_encode($datosEquipo);
} else {
    echo json_encode(array()); // Devolver un array vacío si no se proporciona un modelo
}
?>