<link rel="icon" href="../../img/logoFLA.png" type="image/png">
<?php
include("../../bd.php");

if ($_POST) {
    //Recolectamos los datos del método POST (Datos activo)
    $identificacion = (isset($_POST["identificacion"]) ? $_POST["identificacion"] : "");
    $nombre = (isset($_POST["nombre"]) ? $_POST["nombre"] : "");
    $cargo = (isset($_POST["cargo"]) ? $_POST["cargo"] : "");
    $dependencia = (isset($_POST["dependencia"]) ? $_POST["dependencia"] : "");


    //Preparar la insercción de los datos
    $sentencia = $conexion->prepare("INSERT INTO usuarios(id,identificacion,nombre,cargo,dependencia) 
    VALUES (NULL,:identificacion,:nombre,:cargo,:dependencia);");

    //Asignando los valores que vienen del método POST (los que vienen del formulario)
    $sentencia->bindParam(":identificacion", $identificacion);
    $sentencia->bindParam(":nombre", $nombre);
    $sentencia->bindParam(":cargo", $cargo);
    $sentencia->bindParam(":dependencia", $dependencia);
    $sentencia->execute();
    $mensaje = "Registro agregado";
    header("Location:index.php?mensaje=" . $mensaje);
}
?>

<?php include("../../estructura/header.php"); ?>

<style>
    body {
        background-image: url("../../img/FLA9.jpg");
        background-size: cover;
        /* Ajusta la imagen al tamaño del contenedor */
        background-repeat: no-repeat;
    }
</style>

<br>
<div class="card">
    <div class="card-header">
        Datos De Los Usuarios
    </div>
    <div class="card-body">

        <form action="" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="identificacion" class="form-label">Identificacion</label>
                <input type="text"  class="form-control" name="identificacion" id="identificacion" aria-describedby="helpId" placeholder="">
            </div>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" aria-describedby="helpId" placeholder="">
            </div>

            <div class="mb-3">
                <label for="cargo" class="form-label">Cargo</label>
                <input type="text" class="form-control" name="cargo" id="cargo" aria-describedby="helpId" placeholder="">
            </div>

            <div class="mb-3">
                <label for="dependencia" class="form-label">Dependencia</label>
                <input type="text" class="form-control" name="dependencia" id="dependencia" aria-describedby="helpId" placeholder="">
            </div>

            <button type="submit" class="btn btn-success">Agregar Registro</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>

        </form>

    </div>
    <div class="card-footer text-muted"></div>
</div>

<?php include("../../estructura/footer.php"); ?>