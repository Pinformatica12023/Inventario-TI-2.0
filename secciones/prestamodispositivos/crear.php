<link rel="icon" href="../../img/logoFLA.png" type="image/png">
<?php
include("../../bd.php");

if ($_POST) {
    // Recolectamos los datos del método POST
    $identificacion = (isset($_POST["identificacion"]) ? $_POST["identificacion"] : '');
    $nombreusuario = (isset($_POST["nombreusuario"]) ? $_POST["nombreusuario"] : '');
    $nombredependencia = (isset($_POST["nombredependencia"]) ? $_POST["nombredependencia"] : '');
    $dispositivo = (isset($_POST["dispositivo"]) ? $_POST["dispositivo"] : '');
    $fechadispositivo = (isset($_POST["fechadispositivo"]) ? $_POST["fechadispositivo"] : NULL);
    $acta = (isset($_FILES["acta"]['name']) ? $_FILES["acta"]['name'] : '');
    $estado = (isset($_POST["estado"]) ? $_POST["estado"] : '');
    $observacion = (isset($_POST["observacion"]) ? $_POST["observacion"] : '');

    // Verificar si hay suficientes dispositivos disponibles
    $stmtCantidad = $conexion->prepare("SELECT cantidad FROM dispositivos WHERE id = :dispositivo");
    $stmtCantidad->bindParam(":dispositivo", $dispositivo);
    $stmtCantidad->execute();
    $resultCantidad = $stmtCantidad->fetch(PDO::FETCH_ASSOC);
    $cantidadDisponible = $resultCantidad['cantidad'];

    // Verificar si la cantidad disponible es mayor que cero para permitir el préstamo
    if ($cantidadDisponible > 0) {
        // Preparar la inserción de los datos en la tabla prestamodispositivo
        $sentencia = $conexion->prepare("INSERT INTO prestamodispositivo(id, identificacion, nombreusuario, nombredependencia, dispositivo, fechadispositivo, acta, estado, observacion) 
            VALUES (NULL, :identificacion, :nombreusuario, :nombredependencia, :dispositivo, :fechadispositivo, :acta, :estado, :observacion);");

        // Procesamiento del archivo adjunto (acta)
        $fecha_acta = new DateTime();
        $nombreaArchivo_Acta = ($acta != '') ? $fecha_acta->getTimestamp() . "_" . $_FILES["acta"]['name'] : "";
        $tmp_acta = $_FILES["acta"]['tmp_name'];

        if ($tmp_acta != '') {
            move_uploaded_file($tmp_acta, "../../secciones/actas/" . $nombreaArchivo_Acta);
        }

        // Vincular los valores que provienen del formulario al SQL preparado
        $sentencia->bindParam(":identificacion", $identificacion);
        $sentencia->bindParam(":nombreusuario", $nombreusuario);
        $sentencia->bindParam(":nombredependencia", $nombredependencia);
        $sentencia->bindParam(":dispositivo", $dispositivo);
        $sentencia->bindParam(":fechadispositivo", $fechadispositivo);
        $sentencia->bindParam(":acta", $nombreaArchivo_Acta);
        $sentencia->bindParam(":estado", $estado);
        $sentencia->bindParam(":observacion", $observacion);

        // Ejecutar la inserción y redirigir
        $sentencia->execute();
        $mensaje = "Registro agregado";
        header("Location:index.php?mensaje=" . $mensaje);

        // Actualizar la cantidad disponible del dispositivo si es mayor que cero
        $stmtActualizarCantidad = $conexion->prepare("UPDATE dispositivos SET cantidad = cantidad - 1 WHERE id = :dispositivo");
        $stmtActualizarCantidad->bindParam(":dispositivo", $dispositivo);
        $stmtActualizarCantidad->execute();
    } else {
        // Si la cantidad disponible es cero, muestra el mensaje con JavaScript
        echo '<script type="text/javascript">alert("NO PUEDES AGREGAR EL DISPOSITIVO, DISPOSITIVO NO DISPONIBLE\n\n ACTUALIZA LA CANTIDAD ");</script>';
        
    }
}

// Obtener la lista de dispositivos para el select
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
        Prestamo Dispositivos
    </div>
    <div class="card-body">

        <form action="" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="identificacion" class="form-label">Identificación</label>
                <input type="text" class="form-control" name="identificacion" id="identificacion">
            </div>

            <div class="mb-3">
                <label for="nombreusuario" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombreusuario" id="nombreusuario" readonly>
            </div>

            <div class="mb-3">
                <label for="nombredependencia" class="form-label">Dependencia</label>
                <input type="text" class="form-control" name="nombredependencia" id="nombredependencia" readonly>
            </div>

            <div class="mb-3">
                <label for="dispositivo" class="form-label">Dispositivo</label>
                <select class="form-select form-select-sm" name="dispositivo" id="dispositivo">
                    <?php foreach ($lista_dispositivos as $registro) { ?>
                        <option value="<?php echo $registro['id']; ?>">
                            <?php echo $registro['nombredeldispositivo']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div id="info-dispositivo" class="mt-3">
                <strong>Información del dispositivo:</strong><br>
                <span id="cantidad-disponible"></span><br>
                <span id="cantidad-restante"></span>
            </div>

            <br>

            <script>
                $(document).ready(function() {
                    $('#dispositivo').on('change', function() {
                        var dispositivoSeleccionado = $(this).val();

                        $.ajax({
                            method: "POST",
                            url: "../../obtener_cantidad_dispositivos.php", // Reemplaza con la ruta correcta de tu archivo PHP para obtener la cantidad
                            data: {
                                dispositivo: dispositivoSeleccionado
                            },
                            success: function(response) {
                                var cantidadInfo = JSON.parse(response);
                                $('#cantidad-disponible').text('Cantidad disponible: ' + cantidadInfo.cantidadDisponible);
                                $('#cantidad-restante').text('Cantidad restante: ' + cantidadInfo.cantidadRestante);
                            },
                            error: function(xhr, status, error) {
                                // Manejo de errores de la solicitud AJAX
                            }
                        });
                    });
                });
            </script>

            <div class="mb-3">
                <label for="fechadispositivo" class="form-label">Fecha Asignación</label>
                <input type="date" class="form-control" name="fechadispositivo" id="fechadispositivo" aria-describedby="helpId" placeholder="">
            </div>

            <script>
                // Obtener la fecha actual en el formato YYYY-MM-DD
                let today = new Date().toISOString().substr(0, 10);

                // Establecer la fecha actual como valor predeterminado
                document.getElementById("fechadispositivo").value = today;
            </script>

            <div class="mb-3">
                <label for="" class="form-label">Acta:</label>
                <input type="file" class="form-control" name="acta" id="acta" aria-describedby="helpId" placeholder="">
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select form-select-sm" name="estado" id="estado">
                    <option value="En uso">En uso</option>
                    <option value="Sin uso">Sin uso</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="observacion" class="form-label">Observación</label>
                <input type="text" class="form-control" name="observacion" id="observacion" aria-describedby="helpId" placeholder="">
            </div>

            <button type="submit" class="btn btn-success" onclick="return ConfirmDispositivo()" >Agregar Registro</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>

        </form>

        




        <!-- JavaScript/jQuery para autocompletar los campos de nombre y dependencia -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#identificacion').on('input', function() {
                    var identificacion = $(this).val();

                    $.ajax({
                        method: "POST",
                        url: "../../buscarusuario.php",
                        data: {
                            identificacion: identificacion
                        },
                        success: function(response) {
                            var usuario = JSON.parse(response);
                            $('#nombreusuario').val(usuario.nombre);
                            $('#nombredependencia').val(usuario.dependencia);
                        },
                        error: function(xhr, status, error) {
                            // Manejo de errores de la solicitud AJAX
                        }
                    });
                });
            });
        </script>


    </div>
    <div class="card-footer text-muted"></div>
</div>

<?php include("../../estructura/footer.php"); ?>