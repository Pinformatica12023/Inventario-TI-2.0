<link rel="icon" href="../../img/logoFLA.png" type="image/png">
<?php
include("../../bd.php");


if ($_POST) {
    //Recolectamos los datos del método POST (Datos activo)
    $identificacion = (isset($_POST["identificacion"]) ? $_POST["identificacion"] : "");
    $nombre = (isset($_POST["nombreUsuario"]) ? $_POST["nombreUsuario"] : "");
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
        background-image: url("../../img/FLA13.jpg");
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

    .card-transparent {
        background-color: rgba(255, 255, 255, 0.9);
        /* Cambia los valores RGB y el valor alfa (0.7) según tus preferencias */
    }
</style>

<br>
<div class="card card-transparent">
    <div class="card-header">
        Prestamo Equipos
    </div>
    <div class="card-body">


        <form action="" id="formularioCrearPrestamo" method="post" enctype="multipart/form-data">

            <div class="row">

        
                <div class="mb-3 col-lg-6 ">
                   
                    <?php 
                    $usuarios = $conexion->prepare("SELECT * FROM usuarios");
                    $usuarios->execute();
                    $lista_usuarios = $usuarios->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <select class="form-select "  placeholder="Ingrese Nombre de usuario" id="usuarios">
                       <option></option>
                        <?php foreach ($lista_usuarios as $registro) { ?>
                            <option value="<?php echo $registro['identificacion']; ?>">
                                <?php echo $registro['nombre']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3 col-lg-6">
                   
                    <select class="form-select" id="modelo">
                    <option></option>
                        <?php foreach ($lista_equipos as $registro) {
                            if($registro['Estado']==='DISPONIBLE'){
                                ?>
                                <option value="<?php echo $registro['numeropc']; ?>">
                                    <?php echo $registro['numeropc']; ?></option>
                            <?php
                            }
                        } ?>
                    </select>
                </div>

               


                <div class="col-lg-6">
                    <label for="usuario" class="form-label required">Nombre Usuario</label>
                    <input type="text" class="form-control" name="nombreUsuario" id="nombreUsuario">
                        
                </div>

                <div class="col-lg-6">
                    <label for="equipo" class="form-label required">Numero Pc</label>
                    <input type="text" class="form-control" name="modelo" id="numeropc">
                        
                </div>

               

                <div class=" col-lg-6 mb-3">
                    <label for="identificacion" class="form-label required">Identificación</label>
                    <input type="text" class="form-control" name="identificacion" id="identificacion">
                    <span id="mensajeErrorIdentificacion" style="display: none; color: red;">Completa este campo por favor</span>
                </div>


                <!-- <div class="mb-3 col-lg-6">
                    <label for="nombre" class="form-label required">Nombre</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" readonly>
                </div> -->

              
                

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

            $(document).ready(function(){
                $('#usuarios').select2({
                    placeholder:"Ingrese Nombre de usuario"
                });
                $('#modelo').select2({
                    placeholder:"Seleccione el equipo asignar"
                });
            })

 

            $(document).ready(function() {
                $('#usuarios').on('change', function() {
                    var identificacion = $(this).val();
                    console.log(identificacion);

                    $.ajax({
                        method: "POST",
                        url: "../../buscarusuario.php",
                        data: {
                            identificacion: identificacion
                        },
                        success: function(response) {
                            var usuario = JSON.parse(response);
                            console.log(usuario);
                            $('#identificacion').val(usuario.identificacion);
                            $('#nombreUsuario').val(usuario.nombre);
                            $('#dependencia').val(usuario.dependencia);
                        },
                        error: function(xhr, status, error) {
                            // Manejo de errores de la solicitud AJAX
                        }
                    });
                });
            });

            $(document).ready(function() {
                $('#modelo').on('change', function() {
                    var modeloSeleccionado = $(this).val();
                    console.log(modeloSeleccionado);

                    $.ajax({
                        method: "POST",
                        url: "../../obtenerDatosEquipo.php", // Ruta al archivo PHP que obtiene los datos
                        data: {
                            modelo: modeloSeleccionado
                        },
                        success: function(response) {
                           
                            var datosEquipo = JSON.parse(response);
                            console.log(datosEquipo);
                            $('#numeropc').val(datosEquipo.numeropc);
                            $('#serialpc').val(datosEquipo.serialpc);
                            $('#marca').val(datosEquipo.marca);
                            $('#serialcargador').val(datosEquipo.serialcargador);
                        },
                        error: function(xhr, status, error) {
                            // Manejo de errores de la solicitud AJAX
                            console.log(error);
                        }
                    });
                });
            });

            function procesarPrestamoEquipo(event) {
          
          // Evitamos que el formulario se envíe automáticamente
          event.preventDefault();
          console.log("llegamos al formulario");
          // Obtenemos el valor del campo numeropc y lo recortamos
          var identificacion = document.getElementById('identificacion').value.trim();
          console.log(identificacion);
          var equipo = document.getElementById('modelo').value.trim();
          console.log(equipo);
          var mensajeErrorIdentificacion =document.getElementById('mensajeErrorIdentificacion');
          var mensajeErrorEquipo =document.getElementById('mensajeErrorIdentificacion');
          if(identificacion === '' || equipo === ''){
              if(identificacion==''){
                  // Mostramos el mensaje de error
                  mensajeErrorIdentificacion.style.display = 'inline';
              }else{
                  // En caso contrario, ocultamos el mensaje de error (si estaba visible)
                  mensajeErrorIdentificacion.style.display = 'none';
              }
              
              if(equipo==''){
                  // Mostramos el mensaje de error
                  mensajeErrorEquipo.style.display = 'inline';
              }else{
                  // En caso contrario, ocultamos el mensaje de error (si estaba visible)
                  mensajeErrorEquipo.style.display = 'none';
              }
          }else{
            console.log("vamos a enviarlos");
              // En caso contrario, ocultamos el mensaje de error (si estaba visible)
              mensajeErrorIdentificacion.style.display = 'none';
              mensajeErrorEquipo.style.display = 'none';
              form.submit();
        }
      }
      
        var form = document.getElementById('formularioCrearPrestamo');
        // Agregamos un listener para el evento submit del formulario
        form.addEventListener('submit',procesarPrestamoEquipo );
        </script>



        <!-- JavaScript/jQuery para autocompletar los campos de serialpc, serialcargador y marca -->
    


    </div>
    <div class="card-footer text-muted"></div>
</div>






<?php include("../../estructura/footer.php"); ?>
<?php
if (isset($_GET['mensaje'])) { ?>
    <script>
        Swal.fire({icon: "error",title: "<?php echo $_GET['mensaje']; ?>"});
    </script>
    <?php
 }  ?>