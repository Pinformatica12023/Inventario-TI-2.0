<?php
// Incluir el archivo de conexión a la base de datos
include("bd.php");

// Verificar si la solicitud es por método POST y si se envió la identificación
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["identificacion"])) {
    // Obtener la identificación del usuario desde la solicitud POST
    $identificacion = $_POST["identificacion"];

    // Realizar la consulta para obtener el nombre y la dependencia del usuario por su identificación
    $sentencia = $conexion->prepare("SELECT * FROM usuarios WHERE identificacion = :identificacion");
    $sentencia->bindParam(":identificacion", $identificacion);
    $sentencia->execute();

    // Obtener los datos del usuario
    $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);

    // Devolver los datos en formato JSON
    if ($resultado) {
        echo json_encode($resultado);
    } else {
        // Si no se encuentra el usuario, devolver un objeto vacío
        echo json_encode(array());
    }
} else {
    // Si la solicitud no es por POST o falta la identificación, retornar un mensaje de error
    echo json_encode(array("error" => "Solicitud incorrecta o falta de identificación"));
}
?>