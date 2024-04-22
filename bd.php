<?php

$server = "127.0.0.1";
$bd = "inventariolocal";
$user = "root";
$password = "";


try{
    $conexion = new PDO("mysql:host=$server;dbname=$bd",$user,$password);
}catch(PDOException $error){
    echo $error->getMessage();
}

?>