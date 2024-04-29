<link rel="icon" href="../../img/logoFLA.png" type="image/png">
<?php
include("../../bd.php");

if (isset($_GET['txtID'])) {
    // Obtener el ID a partir de los parámetros GET
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    $sentencia = $conexion->prepare("SELECT * FROM prestamoequipo WHERE id=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();

    // Recuperación de los datos
    $registro_recuperado = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($registro_recuperado) {
        // Asignar los valores a las variables para usar en el formulario
        $identificacion = $registro_recuperado["identificacion"];
        $nombre = $registro_recuperado["nombre"];
        $dependencia = $registro_recuperado["dependencia"];
        $modelo = $registro_recuperado["modelo"];
        $serialpc = $registro_recuperado["serialpc"];
        $serialcargador = $registro_recuperado["serialcargador"];
        $marca = $registro_recuperado["marca"];
        $fechaequipo = $registro_recuperado["fechaequipo"];
        $acta = $registro_recuperado["acta"];
        $estado = $registro_recuperado["EstadoPrestamo"];
        $observacion = $registro_recuperado["observacion"];
    }
}

if ($_POST) {
    // Recolectamos los datos del método POST
    $txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : "";
    $identificacion = (isset($_POST["identificacion"])?$_POST["identificacion"]:"");
    $nombre = (isset($_POST["nombre"])?$_POST["nombre"]:"");
    $dependencia = (isset($_POST["dependencia"])?$_POST["dependencia"]:"");
    $modelo = (isset($_POST["modelo"])?$_POST["modelo"]:"");
    $serialpc = (isset($_POST["serialpc"])?$_POST["serialpc"]:"");
    $serialcargador = (isset($_POST["serialcargador"])?$_POST["serialcargador"]:"");
    $marca = (isset($_POST["marca"])?$_POST["marca"]:"");
    $fechaequipo = (isset($_POST["fechaequipo"])?$_POST["fechaequipo"]:"");
    $estado = (isset($_POST["EstadoPrestamo"])?$_POST["EstadoPrestamo"]:"");
    $observacion = (isset($_POST["observacion"])?$_POST["observacion"]:"");

    // Verificar si se subió un nuevo archivo de acta
    if (isset($_FILES["acta"]) && $_FILES["acta"]["error"] === UPLOAD_ERR_OK) {
        // Subida del nuevo archivo de acta
        $acta = $_FILES["acta"]; // Archivo PDF

        // Actualización de la fecha del acta
        $fecha_acta = new DateTime(); // Obtenemos la fecha actual
        $fecha_acta_str = $fecha_acta->format('Y-m-d H:i:s');

        // Crear un nombre de archivo único para el acta
        $nombreaArchivo_Acta = $fecha_acta->getTimestamp() . "_" . basename($acta["name"]);
        $rutaArchivo_Acta = "../../secciones/actas/" . $nombreaArchivo_Acta;

        // Obtener el nombre del archivo antiguo (si existe)
        $sentencia = $conexion->prepare("SELECT acta FROM prestamoequipo WHERE id=:id");
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();
        $registro_recuperado = $sentencia->fetch(PDO::FETCH_ASSOC);
        $nombreArchivoAntiguo = $registro_recuperado["acta"];

        if (move_uploaded_file($acta["tmp_name"], $rutaArchivo_Acta)) {
            // Eliminar el archivo antiguo del acta si existe
            if (!empty($nombreArchivoAntiguo) && file_exists($nombreArchivoAntiguo)) {
                unlink($nombreArchivoAntiguo);
            }

            // Actualizar el archivo del acta en la base de datos
            $sentencia = $conexion->prepare("UPDATE prestamoequipo SET acta=:acta WHERE id=:id");
            $sentencia->bindParam(':acta', $nombreaArchivo_Acta);
            $sentencia->bindParam(':id', $txtID);
            $sentencia->execute();
        } else {
            echo "Error al subir el archivo del acta.";
        }
    }

    // Actualizar los otros campos en la base de datos
    $sentencia = $conexion->prepare("UPDATE prestamoequipo SET identificacion=:identificacion, nombre=:nombre, 
        dependencia=:dependencia, modelo=:modelo, serialpc=:serialpc, serialcargador=:serialcargador, 
        marca=:marca, fechaequipo=:fechaequipo,EstadoPrestamo=:estado, 
        observacion=:observacion WHERE id=:id ");

    // Asignando los valores que vienen del método POST (los que vienen del formulario)
    $sentencia->bindParam(":identificacion", $identificacion);
    $sentencia->bindParam(":nombre", $nombre);
    $sentencia->bindParam(":dependencia", $dependencia);
    $sentencia->bindParam(":modelo", $modelo);
    $sentencia->bindParam(":serialpc", $serialpc);
    $sentencia->bindParam(":serialcargador", $serialcargador);
    $sentencia->bindParam(":marca", $marca);
    $sentencia->bindParam(":fechaequipo", $fechaequipo);
    $sentencia->bindParam(":estado", $estado);
    $sentencia->bindParam(":observacion", $observacion);
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $mensaje = "Registro actualizado";
    header("Location:index.php?mensaje=" . $mensaje);
};

$sentencia = $conexion->prepare("SELECT * FROM equipos");
$sentencia->execute();
$lista_equipos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include("../../estructura/header.php"); ?>

<style>
    body {
        background-image: url("../../img/FLA13.jpg");
        background-size: cover;
        /* Ajusta la imagen al tamaño del contenedor */
        background-repeat: no-repeat;
    }

    .card-transparent {
        background-color: rgba(255, 255, 255, 0.9);
        /* Cambia los valores RGB y el valor alfa (0.7) según tus preferencias */
    }
</style>

<br>
<div class="card card-transparent">
    <div class="card-header">
        Datos Del Equipo
    </div>
    <div class="card-body">

        <form action="" method="post" enctype="multipart/form-data">
            <div class="row" >
                <div class="mb-3 col-lg-3">
                    <label for="txtID" class="form-label">ID</label>
                    <input type="text" value="<?php echo $txtID; ?>" readonly class="form-control" name="txtID" id="txtId" aria-describedby="helpId" placeholder="ID">
                </div>

                <div class="mb-3 col-lg-5">
                    <label for="fechaequipo" class="form-label">Fecha Asignación</label>
                    <input type="date" value="<?php echo $registro_recuperado['fechaequipo']; ?>" class="form-control" name="fechaequipo" id="fechaequipo" aria-describedby="helpId" placeholder="">
                </div>

                
                <div class="mb-3 col-lg-4">
                    <label for="estado" class="form-label">Estado</label>
                    <select value="<?php echo $registro_recuperado['EstadoPrestamo']; ?>" class="form-select form-select" name="EstadoPrestamo" id="EstadoPrestamo">
                        <option value="En uso">EN_CURSO</option>
                        <option value="Sin uso">FINALIZADO</option>
                    </select>
                </div>


                <div class="mb-3 col-lg-6">
                    <label for="identificacion " class="form-label">Identificación</label>
                    <input type="text"  value="<?php echo $registro_recuperado['identificacion']; ?>"class="form-control" name="identificacion" id="identificacion" readonly>
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="modelo" class="form-label">Equipo</label>
                    <input type="text" value="<?php echo $registro_recuperado['modelo']; ?>" class="form-control" name="modelo" id="modelo" >
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" value="<?php echo $registro_recuperado['nombre']; ?>" class="form-control" name="nombre" id="nombre" readonly >
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="serialpc" class="form-label">Serial PC</label>
                    <input type="text" value="<?php echo $registro_recuperado['serialpc']; ?>"  class="form-control" name="serialpc" id="serialpc" readonly>
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="dependencia" class="form-label">Dependencia</label>
                    <input type="text" value="<?php echo $registro_recuperado['dependencia']; ?>"  class="form-control" name="dependencia" id="dependencia" readonly>
                </div>    


                <div class="mb-3 col-lg-6">
                    <label for="serialcargador" class="form-label">Serial Cargador</label>
                    <input type="text" value="<?php echo $registro_recuperado['serialcargador']; ?>"  class="form-control" name="serialcargador" id="serialcargador">
                </div> 

                <!-- Acta -->
                <div class="mb-3 col-lg-6">
                    <label for="acta" class="form-label">Acta:</label>
                    <input type="file" value="<?php echo $registro_recuperado['acta']; ?>" name="acta" class="form-control">
                    <br>
                    <?php
                    // Mostrar el enlace al archivo acta (si existe)
                    if (!empty($registro_recuperado['acta'])) {
                        echo '<a href="../../secciones/actas/' . $registro_recuperado['acta'] . '">' . $registro_recuperado['acta'] . '</a>';
                    }
                    ?>
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="marca" class="form-label">Marca</label>
                    <input type="text" value="<?php echo $registro_recuperado['marca']; ?>"  class="form-control" name="marca" id="marca" >
                </div> 

      
                


                <div class="mb-3 col-lg-12">
                    <label for="observacion" class="form-label">Observación</label>
                    <input type="text" value="<?php echo $registro_recuperado['observacion']; ?>" class="form-control" name="observacion" id="observacion" aria-describedby="helpId" placeholder="">
                </div>

            </div>

           

            <button type="submit" class="btn btn-success">Actualizar</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>

        </form>

    </div>
    <div class="card-footer text-muted"></div>
</div>

<?php include("../../estructura/footer.php"); ?>