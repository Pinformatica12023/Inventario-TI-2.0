<?php
// Incluir el archivo de conexión a la base de datos
include "bd.php";

try {
    // Obtener los datos de la base de datos con la información del nombre del dispositivo
    $sentencia = $conexion->prepare("SELECT p.identificacion, p.nombreusuario, p.nombredependencia, d.nombredeldispositivo AS dispositivo,
    p.fechadispositivo, p.Estado_Prestamo, p.observacion FROM prestamodispositivo p
    INNER JOIN dispositivos d ON p.dispositivo = d.id");
    $sentencia->execute();
    $datos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    // Encabezados CSV
    $csvFileName = "registros_prestramodispositivos.csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $csvFileName . '"');

    // Crear un recurso de archivo temporal para escribir los datos
    $csvFile = fopen('php://output', 'w');

    // Cambiar el delimitador a punto y coma
    $delimiter = ';';

    // Escribir los encabezados
    $encabezados = array(
        'Identificación',
        'Nombre',
        'Dependencia',
        'Dispositivo',
        'Fecha De Asignación',
        'Estado',
        'Observación',
    );
    fputcsv($csvFile, $encabezados, $delimiter);

    // Escribir los datos
    foreach ($datos as $registro) {
        // Crear un array de valores en el orden deseado
        $fila = array(
            $registro['identificacion'],
            $registro['nombreusuario'],
            $registro['nombredependencia'],
            $registro['dispositivo'], // Ahora se muestra el nombre del dispositivo
            $registro['fechadispositivo'],
            $registro['Estado_Prestamo'],
            $registro['observacion'],
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