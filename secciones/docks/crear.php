<link rel="icon" href="../../img/logoFLA.png" type="image/png">
<?php
include("../../bd.php");

if ($_POST) {
    //Recolectamos los datos del método POST (Datos activo)
    $numeroequipo = (isset($_POST["numeroequipo"]) ? $_POST["numeroequipo"] : "");
    $serialequipo = (isset($_POST["serialequipo"]) ? $_POST["serialequipo"] : "");
    $marca = (isset($_POST["marca"]) ? $_POST["marca"] : "");
    $fechacompra = (isset($_POST["fechacompra"]) ? $_POST["fechacompra"] : "");
    $añosuso = (isset($_POST["añosuso"]) ? $_POST["añosuso"] : "");
    $proveedor = (isset($_POST["proveedor"]) ? $_POST["proveedor"] : "");
    $garantia = (isset($_POST["garantia"]) ? $_POST["garantia"] : "");

    //Preparar la insercción de los datos
    $sentencia = $conexion->prepare("INSERT INTO docks(id,numeroequipo,
    serialequipo,marca,
    fechacompra,añosuso,proveedor,garantia) 
    VALUES (NULL,:numeroequipo,:serialequipo,:marca,:fechacompra,:añosuso,:proveedor,
    :garantia);");

    //Asignando los valores que vienen del método POST (los que vienen del formulario)
    $sentencia->bindParam(":numeroequipo", $numeroequipo);
    $sentencia->bindParam(":serialequipo", $serialequipo);
    $sentencia->bindParam(":marca", $marca);
    $sentencia->bindParam(":fechacompra", $fechacompra);
    $sentencia->bindParam(":añosuso", $añosuso);
    $sentencia->bindParam(":proveedor", $proveedor);
    $sentencia->bindParam(":garantia", $garantia);
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
        Docks
    </div>
    <div class="card-body">

        <form action="" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="numeroequipo" class="form-label">Numero Equipo</label>
                <input type="text" class="form-control" name="numeroequipo" id="numeroequipo">
            </div>

            <div class="mb-3">
                <label for="serialequipo" class="form-label">Serial Equipo</label>
                <input type="text" class="form-control" name="serialequipo" id="serialequipo" readonly>
            </div>

            <div class="mb-3">
                <label for="marca" class="form-label">Marca</label>
                <input type="text" class="form-control" name="marca" id="marca" readonly>
            </div>

            <div class="mb-3">
                <label for="fechacompra" class="form-label">Fecha De Compra</label>
                <input type="date" class="form-control" name="fechacompra" id="fechacompra" >
            </div>

            <div class="mb-3">
                <label for="añosuso" class="form-label">Años De Uso</label>
                <input type="text" class="form-control" name="añosuso" id="añosuso" readonly>
            </div>

            <div class="mb-3">
                <label for="proveedor" class="form-label">Proveedor</label>
                <input type="text" class="form-control" name="proveedor" id="proveedor" readonly>
            </div>

            <div class="mb-3">
                <label for="garantia" class="form-label">Garantía</label>
                <input type="text" class="form-control" name="garantia" id="garantia" >
            </div>


            <button type="submit" class="btn btn-success">Agregar Registro</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>

        </form>

    <div class="card-footer text-muted"></div>
</div>

<?php include("../../estructura/footer.php"); ?>