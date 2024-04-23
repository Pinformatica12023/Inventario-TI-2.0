<link rel="icon" href="../../img/logoFLA.png" type="image/png">
<?php
include("../../bd.php");

if (isset($_GET['txtID'])) {
    // Actualizar campo donde esta el id -->
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    $sentencia = $conexion->prepare("SELECT * FROM prestamodispositivo WHERE id=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();

    // Recuperación de los datos
    $registro_recuperado = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($registro_recuperado) {
        // Asignar los valores a las variables para usar en el formulario
        $identificacion = $registro_recuperado["identificacion"];
        $nombreusuario = $registro_recuperado["nombreusuario"];
        $nombredependencia = $registro_recuperado["nombredependencia"];
        $dispositivo = $registro_recuperado["dispositivo"];
        $fechadispositivo = $registro_recuperado["fechadispositivo"];
        $acta = $registro_recuperado["acta"];
        $estado = $registro_recuperado["estado"];
        $observacion = $registro_recuperado["observacion"];

        $sentenciaDispositivo = $conexion->prepare("SELECT * FROM dispositivos Where id=:dispositivo");
        $sentenciaDispositivo->bindParam(":dispositivo", $dispositivo);
        $sentenciaDispositivo->execute();

        $registro_recuperadoDispositivo = $sentenciaDispositivo->fetch(PDO::FETCH_ASSOC);
        if($registro_recuperadoDispositivo){
            $nombreDispositivo = $registro_recuperadoDispositivo["nombredeldispositivo"];
        }else{
            $nombreDispositivo = "no esta encontrando el nombre";
            echo ("hola");
        }
    }
}

if ($_POST) {
    // Recolectamos los datos del método POST
    $txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : "";
    $nombreusuario = (isset($_POST["nombreusuario"]) ? $_POST["nombreusuario"] : "");
    $nombredependencia = (isset($_POST["nombredependencia"]) ? $_POST["nombredependencia"] : "");
    $dispositivo = (isset($_POST["dispositivo"]) ? $_POST["dispositivo"] : "");
    $fechadispositivo = (isset($_POST["fechadispositivo"]) ? $_POST["fechadispositivo"] : "");
    $acta = (isset($_FILES["acta"]['name']) ? $_FILES["acta"]['name'] : "");
    $estado = (isset($_POST["estado"]) ? $_POST["estado"] : "");
    $observacion = (isset($_POST["observacion"]) ? $_POST["observacion"] : "");

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

        if (move_uploaded_file($acta["tmp_name"], $rutaArchivo_Acta)) {
            // Eliminar el archivo antiguo del acta si existe
            $sentencia = $conexion->prepare("SELECT acta FROM prestamodispositivo WHERE id=:id");
            $sentencia->bindParam(":id", $txtID);
            $sentencia->execute();
            $registro_recuperado = $sentencia->fetch(PDO::FETCH_ASSOC);

            if (isset($registro_recuperado["acta"]) && $registro_recuperado["acta"] !== "") {
                if (file_exists($registro_recuperado["acta"])) {
                    unlink($registro_recuperado["acta"]);
                }
            }

            // Actualizar el archivo del acta en la base de datos
            $sentencia = $conexion->prepare("UPDATE prestamodispositivo SET acta=:acta WHERE id=:id");
            $sentencia->bindParam(':acta', $nombreaArchivo_Acta);
            $sentencia->bindParam(':id', $txtID);
            $sentencia->execute();
        } else {
            echo "Error al subir el archivo del acta.";
        }
    }

    // Actualizar los otros campos en la base de datos
    $sentencia = $conexion->prepare("UPDATE prestamodispositivo SET identificacion=:identificacion, 
    nombreusuario=:nombreusuario, 
    nombredependencia=:nombredependencia, dispositivo=:dispositivo, fechadispositivo=:fechadispositivo, acta=:acta, 
    estado=:estado, observacion=:observacion WHERE id=:id ");

    // Asignando los valores que vienen del método POST (los que vienen del formulario)
    $sentencia->bindParam(":identificacion", $identificacion);
    $sentencia->bindParam(":nombreusuario", $nombreusuario);
    $sentencia->bindParam(":nombredependencia", $nombredependencia);
    $sentencia->bindParam(":dispositivo", $dispositivo);
    $sentencia->bindParam(":fechadispositivo", $fechadispositivo);
    $sentencia->bindParam(":acta", $nombreaArchivo_Acta);
    $sentencia->bindParam(":estado", $estado);
    $sentencia->bindParam(":observacion", $observacion);
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $mensaje = "Registro actualizado";
    header("Location:index.php?mensaje=" . $mensaje);
};

$sentencia = $conexion->prepare("SELECT * FROM dispositivos");
$sentencia->execute();
$lista_dispositivos = $sentencia->fetchAll(PDO::FETCH_ASSOC);




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
        Datos Del dispositivo
    </div>
    <div class="card-body">

        <form action="" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="txtID" class="form-label">ID</label>
                <input type="text" value="<?php echo $txtID; ?>" readonly class="form-control" name="txtID" id="txtId" aria-describedby="helpId" placeholder="ID">
            </div>

            <div class="mb-3">
                <label for="identificacion" class="form-label">Identificación</label>
                <input type="text" value="<?php echo $registro_recuperado['identificacion']; ?>" class="form-control" name="identificacion" id="identificacion" readonly>
            </div>

            <div class="mb-3">
                <label for="nombreusuario" class="form-label">Nombre</label>
                <input type="text" value="<?php echo $registro_recuperado['nombreusuario']; ?>" class="form-control" name="nombreusuario" id="nombreusuario" readonly>
            </div>

            <div class="mb-3">
                <label for="nombredependencia" class="form-label">Dependencia</label>
                <input type="text" value="<?php echo $registro_recuperado['nombredependencia']; ?>" class="form-control" name="nombredependencia" id="nombredependencia" readonly>
            </div>

            <div class="mb-3">
                <label for="dispositivo" class="form-label">Dispositivo</label>
                <select value="<?php echo $registro_recuperado['dispositivo']; ?>" class="form-select form-select-sm" name="dispositivo" id="dispositivo">
                <option value="<?php echo $registro_recuperado['dispositivo']; ?>" selected><?php echo $nombreDispositivo?></option>
                    <?php foreach ($lista_dispositivos as $registro) { ?>
                        <option value="<?php echo $registro['id'] ?>">
                            <?php echo $registro['nombredeldispositivo'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="fechadispositivo" class="form-label">Fecha Asignación</label>
                <input type="date" value="<?php echo $registro_recuperado['fechadispositivo']; ?>" class="form-control" name="fechadispositivo" id="fechadispositivo" aria-describedby="helpId" placeholder="">
            </div>

            <!-- Acta -->
            <div class="mb-3">
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

            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select value="<?php echo $registro_recuperado['estado']; ?>" class="form-select form-select-sm" name="estado" id="estado">
                    <option value="En uso">En uso</option>
                    <option value="Sin uso">Sin uso</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="observacion" class="form-label">Observación</label>
                <input type="text" value="<?php echo $registro_recuperado['observacion']; ?>" class="form-control" name="observacion" id="observacion" aria-describedby="helpId" placeholder="">
            </div>

            <button type="submit" class="btn btn-success">Agregar</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>

        </form>


    </div>
    <div class="card-footer text-muted"></div>
</div>


<?php include("../../estructura/footer.php"); ?>