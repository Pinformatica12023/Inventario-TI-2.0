<?php

$server = "127.0.0.1";
$bd = "inventario";
$user = "root";
$password = "Ti2023*";


try{
    $conexion = new PDO("mysql:host=$server;dbname=$bd",$user,$password);
}catch(PDOException $error){
    echo $error->getMessage();
}

?>