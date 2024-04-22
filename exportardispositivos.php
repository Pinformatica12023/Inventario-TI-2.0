<?php
// Incluir el archivo de conexión a la base de datos
include "bd.php";

try {
    // Obtener los datos de la base de datos
    $sentencia = $conexion->prepare("SELECT nombredeldispositivo FROM dispositivos");
    $sentencia->execute();
    $datos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    // Encabezados CSV
    $csvFileName = "registrosDispositivos.csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $csvFileName . '"');

    // Crear un recurso de archivo temporal para escribir los datos
    $csvFile = fopen('php://output', 'w');

    // Cambiar el delimitador a punto y coma
    $delimiter = ';';

    // Escribir los encabezados
    $encabezados = array(
        'Dispositivo',
    );
    fputcsv($csvFile, $encabezados, $delimiter);

    // Escribir los datos
    foreach ($datos as $registro) {
        
        // Crear un array de valores en el orden deseado
        $fila = array(
            $registro['nombredeldispositivo'],
        );
        fputcsv($csvFile, $fila, $delimiter);
    }

    // Cerrar el archivo CSV
    fclose($csvFile);
    exit;
} catch (Exception $e) {
    // Manejo de errores
    echo 'Error: ' . $e->getMessage();
    exit;
}
?>
