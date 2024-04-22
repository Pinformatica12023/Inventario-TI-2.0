<link rel="icon" href="../../img/logoFLA.png" type="image/png">
<?php
include("../../bd.php");

if ($_POST) {
    //Recolectamos los datos del método POST (Datos activo)
    $identificacion = (isset($_POST["identificacion"]) ? $_POST["identificacion"] : "");
    $nombre = (isset($_POST["nombre"]) ? $_POST["nombre"] : "");
    $dependencia = (isset($_POST["dependencia"]) ? $_POST["dependencia"] : "");
    $modelo = (isset($_POST["modelo"]) ? $_POST["modelo"] : "");
    $serialpc = (isset($_POST["serialpc"]) ? $_POST["serialpc"] : "");
    $serialcargador = (isset($_POST["serialcargador"]) ? $_POST["serialcargador"] : "");
    $marca = (isset($_POST["marca"]) ? $_POST["marca"] : "");
    $fechaequipo = (isset($_POST["fechaequipo"]) ? $_POST["fechaequipo"] : null);
    $acta = (isset($_FILES["acta"]['name']) ? $_FILES["acta"]['name'] : "");
    $estado = (isset($_POST["estado"]) ? $_POST["estado"] : "");
    $observacion = (isset($_POST["observacion"]) ? $_POST["observacion"] : "");

    //Preparar la insercción de los datos
    $sentencia = $conexion->prepare("INSERT INTO prestamoequipo(id,identificacion,nombre,dependencia,
    modelo,serialpc,serialcargador,marca,fechaequipo,acta,estado,observacion) 
    VALUES (NULL,:identificacion,:nombre,:dependencia,:modelo,:serialpc,:serialcargador,:marca,:fechaequipo,:acta,:estado,:observacion);");


    //Visualizar archivo PDF
    $fecha_acta = new DateTime();

    $nombreaArchivo_Acta = ($acta != '') ? $fecha_acta->getTimestamp() . "_" . $_FILES["acta"]['name'] : "";
    $tmp_acta = $_FILES["acta"]['tmp_name'];
    if ($tmp_acta != '') {
        move_uploaded_file($tmp_acta, "../../secciones/actas/" . $nombreaArchivo_Acta);
    }

    //Asignando los valores que vienen del método POST (los que vienen del formulario)
    $sentencia->bindParam(":identificacion", $identificacion);
    $sentencia->bindParam(":nombre", $nombre);
    $sentencia->bindParam(":dependencia", $dependencia);
    $sentencia->bindParam(":modelo", $modelo);
    $sentencia->bindParam(":serialpc", $serialpc);
    $sentencia->bindParam(":serialcargador", $serialcargador);
    $sentencia->bindParam(":marca", $marca);
    $sentencia->bindParam(":fechaequipo", $fechaequipo);
    $sentencia->bindParam(":acta", $nombreaArchivo_Acta);
    $sentencia->bindParam(":estado", $estado);
    $sentencia->bindParam(":observacion", $observacion);
    $sentencia->execute();
    $mensaje = "Registro agregado";
    header("Location:index.php?mensaje=" . $mensaje);
}

$sentencia = $conexion->prepare("SELECT * FROM equipos");
$sentencia->execute();
$lista_equipos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

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
        Prestamo Equipos
    </div>
    <div class="card-body">

        <form action="" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="identificacion" class="form-label">Identificación</label>
                <input type="text" class="form-control" name="identificacion" id="identificacion">
            </div>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" readonly>
            </div>

            <div class="mb-3">
                <label for="dependencia" class="form-label">Dependencia</label>
                <input type="text" class="form-control" name="dependencia" id="dependencia" readonly>
            </div>

            <div class="mb-3">
                <label for="modelo" class="form-label">Equipo</label>
                <input type="text" class="form-control" name="modelo" id="modelo">
            </div>

            <div class="mb-3">
                <label for="serialpc" class="form-label">Serial PC</label>
                <input type="text" class="form-control" name="serialpc" id="serialpc" readonly>
            </div>

            <div class="mb-3">
                <label for="serialcargador" class="form-label">Serial Cargador</label>
                <input type="text" class="form-control" name="serialcargador" id="serialcargador" readonly>
            </div>

            <div class="mb-3">
                <label for="marca" class="form-label">Marca</label>
                <input type="text" class="form-control" name="marca" id="marca">
            </div>

            <div class="mb-3">
                <label for="fechaequipo" class="form-label">Fecha Asignación</label>
                <input type="date" class="form-control" name="fechaequipo" id="fechaequipo" aria-describedby="helpId" placeholder="">
            </div>

            <script>
                // Obtener la fecha actual en el formato YYYY-MM-DD
                let today = new Date().toISOString().substr(0, 10);

                // Establecer la fecha actual como valor predeterminado
                document.getElementById("fechaequipo").value = today;
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

            <button type="submit" class="btn btn-success">Agregar Registro</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>

        </form>

        <!-- JavaScript/jQuery para autocompletar los campos de nombre y dependencia -->

        <!-- Autocompletado de usuarios -->
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
                            $('#nombre').val(usuario.nombre);
                            $('#dependencia').val(usuario.dependencia);
                        },
                        error: function(xhr, status, error) {
                            // Manejo de errores de la solicitud AJAX
                        }
                    });
                });
            });
        </script>

        <!-- JavaScript/jQuery para autocompletar los campos de serialpc, serialcargador y marca -->
        <script>
            $(document).ready(function() {
                $('#modelo').on('change', function() {
                    var modeloSeleccionado = $(this).val();

                    $.ajax({
                        method: "POST",
                        url: "../../obtenerDatosEquipo.php", // Ruta al archivo PHP que obtiene los datos
                        data: {
                            modelo: modeloSeleccionado
                        },
                        success: function(response) {
                            var datosEquipo = JSON.parse(response);
                            $('#serialpc').val(datosEquipo.serialpc);
                            $('#marca').val(datosEquipo.marca);
                            $('#serialcargador').val(datosEquipo.serialcargador);
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