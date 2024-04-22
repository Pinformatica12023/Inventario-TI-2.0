<link rel="icon" href="../../img/logoFLA.png" type="image/png">
<?php
include("../../bd.php");

if (isset($_GET['txtID'])) {
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    $sentencia = $conexion->prepare("SELECT * FROM dispositivos WHERE id=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();

    // Recuperación de los datos
    $registro_recuperado = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($registro_recuperado) {
        // Asignar los valores a las variables para usar en el formulario
        $nombredeldispositivo = $registro_recuperado["nombredeldispositivo"];
        $cantidad = $registro_recuperado["cantidad"];
    }
}

if ($_POST) {
    // Recolectamos los datos del método POST
    $txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : "";
    $nombredeldispositivo = (isset($_POST["nombredeldispositivo"]) ? $_POST["nombredeldispositivo"] : "");
    $cantidad = (isset($_POST["cantidad"]) ? $_POST["cantidad"] : "");
    
    // Actualizar los campos en la base de datos
    $sentencia = $conexion->prepare("UPDATE dispositivos SET nombredeldispositivo = :nombredeldispositivo, cantidad = :cantidad WHERE id = :id");

    // Asignando los valores que vienen del método POST (los que vienen del formulario)
    $sentencia->bindParam(":nombredeldispositivo", $nombredeldispositivo);
    $sentencia->bindParam(":cantidad", $cantidad);
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();

    $mensaje = "Registro actualizado";
    header("Location:index.php?mensaje=" . $mensaje);
}

?>

<?php include("../../estructura/header.php"); ?>

<style>
    body {
        background-image: url("../../img/FLA10.jpg");
        background-size: cover;
        /* Ajusta la imagen al tamaño del contenedor */
        background-repeat: no-repeat;
    }
</style>

<br>
<div class="card">
    <div class="card-header">
        Datos del dispositivo
    </div>
    <div class="card-body">

        <form action="" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="txtID" class="form-label">ID</label>
                <input type="text" value="<?php echo $txtID; ?>" readonly class="form-control" name="txtID" id="txtId" aria-describedby="helpId" placeholder="ID">
            </div>

            <div class="mb-3">
                <label for="nombredeldispositivo" class="form-label">Dispositivo</label>
                <input type="text" value="<?php echo $nombredeldispositivo; ?>" class="form-control" name="nombredeldispositivo" id="nombredeldispositivo" aria-describedby="helpId" placeholder="">
            </div>

            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="text" value="<?php echo $cantidad; ?>" class="form-control" name="cantidad" id="cantidad" aria-describedby="helpId" placeholder="">
            </div>

            <button type="submit" class="btn btn-success">Actualizar</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>

        </form>

    </div>
    <div class="card-footer text-muted"></div>
</div>

<?php include("../../estructura/footer.php"); ?>