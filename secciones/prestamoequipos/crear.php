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
    // $acta = (isset($_FILES["acta"]['name']) ? $_FILES["acta"]['name'] : "");
    // $estado = (isset($_POST["estado"]) ? $_POST["estado"] : "");
    $observacion = (isset($_POST["observacion"]) ? $_POST["observacion"] : "");


    $sentencia  = $conexion->prepare("SELECT * FROM prestamoequipo WHERE modelo = :modelo && EstadoPrestamo = 'EN_CURSO';");
    $sentencia->bindParam(":modelo",$modelo);
    $sentencia->execute();
    $prestamoEncontrado  = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    if ($prestamoEncontrado!=null) {
        $mensaje = "No se pudo realizar la acción ya que este equipo ya ha sido asignado";
        header("Location:crear.php?mensaje=" . $mensaje);
       
    }else{


        //Preparar la insercción de los datos
        $sentencia = $conexion->prepare("INSERT INTO prestamoequipo(id,identificacion,nombre,dependencia,
        modelo,serialpc,serialcargador,marca,fechaequipo,EstadoPrestamo,observacion) 
        VALUES (NULL,:identificacion,:nombre,:dependencia,:modelo,:serialpc,:serialcargador,:marca,:fechaequipo,:estado,:observacion);");


        //Visualizar archivo PDF
        $fecha_acta = new DateTime();

        $nombreaArchivo_Acta = ($acta != '') ? $fecha_acta->getTimestamp() . "_" . $_FILES["acta"]['name'] : "";
        $tmp_acta = $_FILES["acta"]['tmp_name'];
        if ($tmp_acta != '') {
            move_uploaded_file($tmp_acta, "../../secciones/actas/" . $nombreaArchivo_Acta);
        }

        $estado = "EN_CURSO";

        //Asignando los valores que vienen del método POST (los que vienen del formulario)
        $sentencia->bindParam(":identificacion", $identificacion);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":dependencia", $dependencia);
        $sentencia->bindParam(":modelo", $modelo);
        $sentencia->bindParam(":serialpc", $serialpc);
        $sentencia->bindParam(":serialcargador", $serialcargador);
        $sentencia->bindParam(":marca", $marca);
        $sentencia->bindParam(":fechaequipo", $fechaequipo);
        // $sentencia->bindParam(":acta", $nombreaArchivo_Acta);
        $sentencia->bindParam(":estado", $estado);
        $sentencia->bindParam(":observacion", $observacion);
        $sentencia->execute();

        //Cambiamos el estado del equipo a EN PRESTAMO

        $sentenciaActualizarEquipo = $conexion->prepare("UPDATE equipos SET Estado = 'EN_PRESTAMO' WHERE numeropc = :numeropc");
        $sentenciaActualizarEquipo->bindParam(":numeropc",$modelo);
        $sentenciaActualizarEquipo->execute();

        $mensaje = "Registro agregado";
        header("Location:index.php?mensaje=" . $mensaje);
    }
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

     /* Asterisco campos obligatorios */
     .required::after {
        content: "*";
        color: red;
     
        margin-left: 4px;
    }
</style>

<br>
<div class="card shadow-lg">
    <div class="card-header">
        Prestamo Equipos
    </div>
    <div class="card-body">


        <form action="" id="formularioCrearPrestamo" method="post" enctype="multipart/form-data">
            <div class="row">

            
                <div class=" col-lg-6 mb-3">
                    <label for="identificacion" class="form-label required">Identificación</label>
                    <input type="text" class="form-control" name="identificacion" id="identificacion">
                    <span id="mensajeErrorIdentificacion" style="display: none; color: red;">Completa este campo por favor</span>
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="modelo" class="form-label required">Equipo</label>
                    <input type="text" class="form-control" name="modelo" id="modelo">
                    <span id="mensajeErrorEquipo" style="display: none; color: red;">Completa este campo por favor</span>
                </div>


                <div class="mb-3 col-lg-6">
                    <label for="nombre" class="form-label required">Nombre</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" readonly>
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="serialpc" class="form-label required">Serial PC</label>
                    <input type="text" class="form-control" name="serialpc" id="serialpc" readonly>
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="dependencia" class="form-label required">Dependencia</label>
                    <input type="text" class="form-control" name="dependencia" id="dependencia" readonly>
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="serialcargador" class="form-label">Serial Cargador</label>
                    <input type="text" class="form-control" name="serialcargador" id="serialcargador" readonly>
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="fechaequipo" class="form-label required">Fecha Asignación</label>
                    <input type="date" class="form-control" name="fechaequipo" id="fechaequipo" aria-describedby="helpId" placeholder="">
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="marca" class="form-label required">Marca</label>
                    <input type="text" class="form-control" name="marca" id="marca">
                </div>

             

                <script>
                    // Obtener la fecha actual en el formato YYYY-MM-DD
                    let today = new Date().toISOString().substr(0, 10);

                    // Establecer la fecha actual como valor predeterminado
                    document.getElementById("fechaequipo").value = today;
                </script>

                <!-- <div class="mb-3">
                    <label for="" class="form-label">Acta:</label>
                    <input type="file" class="form-control" name="acta" id="acta" aria-describedby="helpId" placeholder="">
                </div> -->

                <!-- <div class="mb-3">
                    <label for="estado" class="form-label required">Estado</label>
                    <select class="form-select form-select-sm" name="estado" id="estado">
                        <option value="EN_CURSO">EN CURSO</option>
                        <option value="FINALIZADO">FINALIZADO</option>
                    </select>
                </div> -->

                <div class="mb-3">
                    <label for="observacion" class="form-label">Observación</label>
                    <textarea class="form-control" name="observacion" id="observacion" aria-describedby="helpId" placeholder=""> </textarea>

                </div>
            </div>
            
            <div class="text-end">
            <button type="submit" class="btn btn-success">Agregar Registro</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
            </div>
           

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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Seleccionamos el formulario por su ID
        var form = document.getElementById('formularioCrearPrestamo');

        // Agregamos un listener para el evento submit del formulario
        form.addEventListener('submit', function(event) {
            // Evitamos que el formulario se envíe automáticamente
            event.preventDefault();

            // Obtenemos el valor del campo numeropc y lo recortamos
            var identificacion = document.getElementById('identificacion').value.trim();
            var equipo = document.getElementById('modelo').value.trim();
            if(identificacion === '' || equipo === ''){
                if(identificacion==''){
                    // Mostramos el mensaje de error
                    document.getElementById('mensajeErrorIdentificacion').style.display = 'inline';
                }else{
                    // En caso contrario, ocultamos el mensaje de error (si estaba visible)
                    document.getElementById('mensajeErrorIdentificacion').style.display = 'none';
                }
                
                if(equipo==''){
                    // Mostramos el mensaje de error
                    document.getElementById('mensajeErrorEquipo').style.display = 'inline';
                }else{
                    // En caso contrario, ocultamos el mensaje de error (si estaba visible)
                    document.getElementById('mensajeErrorEquipo').style.display = 'none';
                }
            }else{
                // En caso contrario, ocultamos el mensaje de error (si estaba visible)
                document.getElementById('mensajeErrorIdentificacion').style.display = 'none';
                document.getElementById('mensajeErrorEquipo').style.display = 'none';
                form.submit();
            }
        });
    });
</script>




<?php include("../../estructura/footer.php"); ?>
<?php
if (isset($_GET['mensaje'])) { ?>
    <script>
        Swal.fire({icon: "error",title: "<?php echo $_GET['mensaje']; ?>"});
    </script>
    <?php
 }  ?>