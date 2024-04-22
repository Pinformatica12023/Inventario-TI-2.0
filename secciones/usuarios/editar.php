<link rel="icon" href="../../img/logoFLA.png" type="image/png">
<?php
include("../../bd.php");

if (isset($_GET['txtID'])) {
    // Actualizar campo donde esta el id -->
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    $sentencia = $conexion->prepare("SELECT * FROM usuarios WHERE id=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();

    // Recuperación de los datos
    $registro_recuperado = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($registro_recuperado) {
        // Asignar los valores a las variables para usar en el formulario
        $identificacion = $registro_recuperado["identificacion"];
        $nombre = $registro_recuperado["nombre"];
        $cargo = $registro_recuperado["cargo"];
        $dependencia = $registro_recuperado["dependencia"];
    }
}

if ($_POST) {
    // Recolectamos los datos del método POST
    $txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : "";
    $identificacion = (isset($_POST["identificacion"]) ? $_POST["identificacion"] : "");
    $nombre = (isset($_POST["nombre"]) ? $_POST["nombre"] : "");
    $cargo = (isset($_POST["cargo"]) ? $_POST["cargo"] : "");
    $dependencia = (isset($_POST["dependencia"]) ? $_POST["dependencia"] : "");

    // Actualizar los otros campos en la base de datos
    $sentencia = $conexion->prepare("UPDATE usuarios SET identificacion=:identificacion, 
    nombre=:nombre, cargo=:cargo, dependencia=:dependencia WHERE id=:id ");

    // Asignando los valores que vienen del método POST (los que vienen del formulario)
    $sentencia->bindParam(":identificacion", $identificacion);
    $sentencia->bindParam(":nombre", $nombre);
    $sentencia->bindParam(":cargo", $cargo);
    $sentencia->bindParam(":dependencia", $dependencia);
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();

    $mensaje = "Registro actualizado";
    header("Location:index.php?mensaje=" . $mensaje);
};

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
        Datos de los bienes
    </div>
    <div class="card-body">

        <form action="" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="txtID" class="form-label">ID</label>
                <input type="text" value="<?php echo $txtID; ?>" readonly class="form-control" name="txtID" id="txtId" aria-describedby="helpId" placeholder="ID">
            </div>

            <div class="mb-3">
                <label for="identificacion" class="form-label">Identificacion</label>
                <input type="text" value="<?php echo $registro_recuperado['identificacion']; ?>" class="form-control" name="identificacion" id="identificacion" aria-describedby="helpId" placeholder="">
            </div>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" value="<?php echo $registro_recuperado['nombre']; ?>" class="form-control" name="nombre" id="nombre" aria-describedby="helpId" placeholder="">
            </div>

            <div class="mb-3">
                <label for="cargo" class="form-label">Cargo</label>
                <input type="text" value="<?php echo $registro_recuperado['cargo']; ?>" class="form-control" name="cargo" id="cargo" aria-describedby="helpId" placeholder="">
            </div>

            <div class="mb-3">
                <label for="dependencia" class="form-label">Dependencia</label>
                <input type="text" value="<?php echo $registro_recuperado['dependencia']; ?>" class="form-control" name="dependencia" id="dependencia" aria-describedby="helpId" placeholder="">
            </div>
            


            <button type="submit" class="btn btn-success">Actualizar</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>

        </form>

    </div>
    <div class="card-footer text-muted"></div>
</div>

<?php include("../../estructura/footer.php"); ?>