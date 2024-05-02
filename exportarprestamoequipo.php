<?php
// Incluir el archivo de conexión a la base de datos
include "bd.php";

try {
    // Obtener los datos de la base de datos para equipos cuyo modelo comience con '2023'
    $sentencia = $conexion->prepare("SELECT identificacion, nombre, dependencia, modelo, serialpc, marca, 
    fechaequipo, EstadoPrestamo, observacion FROM prestamoequipo ");
    
    $sentencia->execute();
    $datos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    // Encabezados CSV
    $csvFileName = "registros_prestamoequipos_2023.csv"; // Nombre del archivo con equipos cuyo modelo comienza con 2023
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
        'Modelo',
        'Serial PC',
        'Marca',
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
            $registro['nombre'],
            $registro['dependencia'],
            $registro['modelo'], // Aquí se muestra el nombre del modelo
            $registro['serialpc'],
            $registro['marca'],
            $registro['fechaequipo'],
            $registro['EstadoPrestamo'],
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