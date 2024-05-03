<?php
  
if($_GET && isset($_GET["idEquipo"])){
    
   
    // Asegúrate de llamar a la función con el ID correcto, por ejemplo, desde una variable de sesión o un parámetro GET
    $numeropc = $_GET['idEquipo']; // Asegúrate de validar y sanear este valor
    
    include("../bd.php");
    $sentenciaPrestamos = $conexion->prepare("SELECT * FROM prestamoequipo WHERE modelo = :numeropc ORDER BY EstadoPrestamo ASC");
    $sentenciaPrestamos->bindParam(":numeropc", $numeropc);
    $sentenciaPrestamos->execute();
    $lista_prestamoequipo = $sentenciaPrestamos->fetchAll(PDO::FETCH_ASSOC);


    $sentenciaInformacionEquipo = $conexion->prepare("SELECT * FROM equipos WHERE numeropc = :numeropc");
    $sentenciaInformacionEquipo->bindParam(":numeropc", $numeropc);
    $sentenciaInformacionEquipo->execute();
    $equipoEncontrado = $sentenciaInformacionEquipo->fetchAll(PDO::FETCH_ASSOC);

    $resultado = array(
        "lista_prestamoEquipos"=> $lista_prestamoequipo,
        "equipo_encontrado"=> $equipoEncontrado
    );

    if($resultado){
        echo json_encode($resultado);
    }else{
        echo null;
    }


    // if(count($lista_prestamoequipo) > 0){
    //     echo json_encode($lista_prestamoequipo);
    // }else{  
    //     echo null;
    // }


    // $sentenciaNombre = $conexion->prepare("SELECT * FROM Dispositivos WHERE id = :id");
    // $sentenciaNombre->bindParam(":id", $id);
    // $sentenciaNombre->execute();
    // $dispositivoEncontrado = $sentenciaNombre->fetchAll(PDO::FETCH_ASSOC);



    // $resultado = array("lista_prestamoDispositivo"=> $lista_prestamodispositivo,
    // "dispositivo_encontrado"=> $dispositivoEncontrado) ;

    // if($resultado){
    //     echo json_encode($resultado);
    // }else{
    //     //devolvemos Array vacio    
    //     echo null;
    // }
   
}else{
    $response = array('error' => "esta entrando al else");
    echo json_encode($response);
}

