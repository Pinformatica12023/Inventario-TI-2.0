<?php
  
if($_GET && isset($_GET["idDispositivo"])){
    
   
    // Asegúrate de llamar a la función con el ID correcto, por ejemplo, desde una variable de sesión o un parámetro GET
    $id = $_GET['idDispositivo']; // Asegúrate de validar y sanear este valor
    
    include("../bd.php");
    $sentenciaPrestamos = $conexion->prepare("SELECT * FROM prestamodispositivo WHERE dispositivo = :id");
    $sentenciaPrestamos->bindParam(":id", $id);
    $sentenciaPrestamos->execute();
    $lista_prestamodispositivo = $sentenciaPrestamos->fetchAll(PDO::FETCH_ASSOC);


    $sentenciaNombre = $conexion->prepare("SELECT * FROM Dispositivos WHERE id = :id");
    $sentenciaNombre->bindParam(":id", $id);
    $sentenciaNombre->execute();
    $dispositivoEncontrado = $sentenciaNombre->fetchAll(PDO::FETCH_ASSOC);

    $resultado = array("lista_prestamoDispositivo"=> $lista_prestamodispositivo,
    "dispositivo_encontrado"=> $dispositivoEncontrado) ;

    if($resultado){
        echo json_encode($resultado);
    }else{
        //devolvemos Array vacio    
        echo null;
    }
   
}else{
    $response = array('error' => "esta entrando al else");
    echo json_encode($response);
}

