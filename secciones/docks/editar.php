<link rel="icon" href="../../img/logoFLA.png" type="image/png">
<?php
include("../../bd.php");

if (isset($_GET['txtID'])) {
    // Obtener el ID a partir de los parámetros GET
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    $sentencia = $conexion->prepare("SELECT * FROM docks WHERE id=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();

    // Recuperación de los datos
    $registro_recuperado = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($registro_recuperado) {
        // Asignar los valores a las variables para usar en el formulario
        $numeroequipo = $registro_recuperado["numeroequipo"];
        $serialequipo = $registro_recuperado["serialequipo"];
        $marca = $registro_recuperado["marca"];
        $fechacompra = $registro_recuperado["fechacompra"];
        $añosuso = $registro_recuperado["añosuso"];
        $proveedor = $registro_recuperado["proveedor"];
        $garantia = $registro_recuperado["garantia"];
    }
}

if ($_POST) {
    // Recolectamos los datos del método POST
    $txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : "";
    $numeroequipo = (isset($_POST["numeroequipo"]) ? $_POST["numeroequipo"] : "");
    $serialequipo = (isset($_POST["serialequipo"]) ? $_POST["serialequipo"] : "");
    $marca = (isset($_POST["marca"]) ? $_POST["marca"] : "");
    $fechacompra = (isset($_POST["fechacompra"]) ? $_POST["fechacompra"] : "");
    $añosuso = (isset($_POST["añosuso"]) ? $_POST["añosuso"] : "");
    $proveedor = (isset($_POST["proveedor"]) ? $_POST["proveedor"] : "");
    $garantia = (isset($_POST["garantia"]) ? $_POST["garantia"] : "");

    
    // Actualizar los otros campos en la base de datos
    $sentencia = $conexion->prepare("UPDATE docks SET numeroequipo=:numeroequipo, serialequipo=:serialequipo, 
        marca=:marca, fechacompra=:fechacompra, añosuso=:añosuso, proveedor=:proveedor, 
        garantia=:garantia WHERE id=:id ");

    // Asignando los valores que vienen del método POST (los que vienen del formulario)
    $sentencia->bindParam(":numeroequipo", $numeroequipo);
    $sentencia->bindParam(":serialequipo", $serialequipo);
    $sentencia->bindParam(":marca", $marca);
    $sentencia->bindParam(":fechacompra", $fechacompra);
    $sentencia->bindParam(":añosuso", $añosuso);
    $sentencia->bindParam(":proveedor", $proveedor);
    $sentencia->bindParam(":garantia", $garantia);
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $mensaje = "Registro actualizado";
    header("Location:index.php?mensaje=" . $mensaje);
};

$sentencia = $conexion->prepare("SELECT * FROM docks");
$sentencia->execute();
$lista_docks = $sentencia->fetchAll(PDO::FETCH_ASSOC);

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
        Datos Del Equipo
    </div>
    <div class="card-body">

        <form action="" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="txtID" class="form-label">ID</label>
                <input type="text" value="<?php echo $txtID; ?>" readonly class="form-control" name="txtID" id="txtId" aria-describedby="helpId" placeholder="ID">
            </div>
            <div class="mb-3">
                <label for="numeroequipo" class="form-label">Numero Equipo</label>
                <input type="text" value="<?php echo $registro_recuperado['numeroequipo']; ?>" class="form-control" name="numeroequipo" id="numeroequipo">
            </div>

            <div class="mb-3">
                <label for="serialequipo" class="form-label">Serial Equipo</label>
                <input type="text" value="<?php echo $registro_recuperado['serialequipo']; ?>" class="form-control" name="serialequipo" id="serialequipo" >
            </div>

            <div class="mb-3">
                <label for="marca" class="form-label">Marca</label>
                <input type="text" value="<?php echo $registro_recuperado['marca']; ?>" class="form-control" name="marca" id="marca" >
            </div>

            <div class="mb-3">
                <label for="fechacompra" class="form-label">Fecha De Compra</label>
                <input type="date" value="<?php echo $registro_recuperado['fechacompra']; ?>" class="form-control" name="fechacompra" id="fechacompra" >
            </div>

            <div class="mb-3">
                <label for="añosuso" class="form-label">Años De Uso</label>
                <input type="text" value="<?php echo $registro_recuperado['añosuso']; ?>" class="form-control" name="añosuso" id="añosuso" >
            </div>

            <div class="mb-3">
                <label for="proveedor" class="form-label">Proveedor</label>
                <input type="text" value="<?php echo $registro_recuperado['proveedor']; ?>" class="form-control" name="proveedor" id="proveedor" >
            </div>

            <div class="mb-3">
                <label for="garantia" class="form-label">Garantía</label>
                <input type="text" value="<?php echo $registro_recuperado['garantia']; ?>" class="form-control" name="garantia" id="garantia" >
            </div>

            <button type="submit" class="btn btn-success">Actualizar</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>

        </form>

    </div>
    <div class="card-footer text-muted"></div>
</div>

<?php include("../../estructura/footer.php"); ?>