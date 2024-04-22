<link rel="icon" href="../../img/logoFLA.png" type="image/png">
<?php
include("../../bd.php");

if ($_POST) {
    //Recolectamos los datos del método POST (Datos activo)
    $numeropc = (isset($_POST["numeropc"]) ? $_POST["numeropc"] : "");
    $serialpc = (isset($_POST["serialpc"]) ? $_POST["serialpc"] : "");
    $serialcargador = (isset($_POST["serialcargador"]) ? $_POST["serialcargador"] : "");
    $placa = (isset($_POST["placa"]) ? $_POST["placa"] : "");
    $activo = (isset($_POST["activo"]) ? $_POST["activo"] : "");
    $fechacompra = (isset($_POST["fechacompra"]) ? $_POST["fechacompra"] : "");
    $tipo = (isset($_POST["tipo"]) ? $_POST["tipo"] : "");
    $ram = (isset($_POST["ram"]) ? $_POST["ram"] : "");
    $procesador = (isset($_POST["procesador"]) ? $_POST["procesador"] : "");
    $marca = (isset($_POST["marca"]) ? $_POST["marca"] : "");
    $almacenamiento = (isset($_POST["almacenamiento"]) ? $_POST["almacenamiento"] : "");
    $observacion = (isset($_POST["observacion"]) ? $_POST["observacion"] : "");

    //Preparar la insercción de los datos
    $sentencia = $conexion->prepare("INSERT INTO equipos (id, numeropc, serialpc,serialcargador, placa, activo, fechacompra, tipo, 
    ram, procesador, marca, almacenamiento, observacion) 
    VALUES (NULL, :numeropc, :serialpc, :serialcargador, :placa, :activo, :fechacompra, :tipo, :ram, :procesador, :marca, :almacenamiento, :observacion)");


    //Asignando los valores que vienen del método POST (los que vienen del formulario)
    $sentencia->bindParam(":numeropc", $numeropc);
    $sentencia->bindParam(":serialpc", $serialpc);
    $sentencia->bindParam(":serialcargador", $serialcargador);
    $sentencia->bindParam(":placa", $placa);
    $sentencia->bindParam(":activo", $activo);
    $sentencia->bindParam(":fechacompra", $fechacompra);
    $sentencia->bindParam(":tipo", $tipo);
    $sentencia->bindParam(":ram", $ram);
    $sentencia->bindParam(":procesador", $procesador);
    $sentencia->bindParam(":marca", $marca);
    $sentencia->bindParam(":almacenamiento", $almacenamiento);
    $sentencia->bindParam(":observacion", $observacion);
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
        Equipos
    </div>
    <div class="card-body">

        <form action="" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="numeropc" class="form-label">Equipo</label>
                <input type="text" class="form-control" name="numeropc" id="numeropc" aria-describedby="helpId" placeholder="">
            </div>

            <div class="mb-3">
                <label for="serialpc" class="form-label">Serial PC</label>
                <input type="text" class="form-control" name="serialpc" id="serialpc" aria-describedby="helpId" placeholder="">
            </div>

            <div class="mb-3">
                <label for="serialcargador" class="form-label">Serial Cargador</label>
                <input type="text" class="form-control" name="serialcargador" id="serialcargador" readonly>
            </div>

            <div class="mb-3">
                <label for="placa" class="form-label">Placa</label>
                <input type="text" class="form-control" name="placa" id="placa" aria-describedby="helpId" placeholder="">
            </div>

            <div class="mb-3">
                <label for="activo" class="form-label">Activo</label>
                <input type="text" class="form-control" name="activo" id="activo" aria-describedby="helpId" placeholder="">
            </div>

            <div class="mb-3">
                <label for="fechacompra" class="form-label">Fecha De Compra</label>
                <input type="date" class="form-control" name="fechacompra" id="fechacompra" aria-describedby="helpId" placeholder="">
            </div>

            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select class="form-select form-select-sm" name="tipo" id="tipo">
                    <option selected>Select one</option>
                    <option value="PORTATIL">PORTATIL</option>
                    <option value="ESCRITORIO">ESCRITORIO</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="ram" class="form-label">RAM</label>
                <input type="text" class="form-control" name="ram" id="ram" aria-describedby="helpId" placeholder="">
            </div>

            <div class="mb-3">
                <label for="procesador" class="form-label">Procesador</label>
                <input type="text" class="form-control" name="procesador" id="procesador" aria-describedby="helpId" placeholder="">
            </div>

            <div class="mb-3">
                <label for="marca" class="form-label">Marca</label>
                <input type="text" class="form-control" name="marca" id="marca" aria-describedby="helpId" placeholder="">
            </div>

            <div class="mb-3">
                <label for="almacenamiento" class="form-label">Almacenamiento</label>
                <input type="text" class="form-control" name="almacenamiento" id="almacenamiento" aria-describedby="helpId" placeholder="">
            </div>

            <div class="mb-3">
                <label for="observacion" class="form-label">Observacion</label>
                <input type="text" class="form-control" name="observacion" id="observacion" aria-describedby="helpId" placeholder="">
            </div>

            <button type="submit" class="btn btn-success">Agregar Registro</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>

        </form>

    </div>
    <div class="card-footer text-muted"></div>
</div>

<?php include("../../estructura/footer.php"); ?>