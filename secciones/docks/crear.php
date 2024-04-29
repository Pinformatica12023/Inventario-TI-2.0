<link rel="icon" href="../../img/logoFLA.png" type="image/png">
<?php
include("../../bd.php");

if ($_POST) {
    
//Recolectamos los datos del método POST (Datos activo)
    $numeroequipo = (isset($_POST["numeroequipo"]) ? $_POST["numeroequipo"] : "");
    $marca = (isset($_POST["marca"]) ? $_POST["marca"] : "");
    $serialequipo = (isset($_POST["serialequipo"]) ? $_POST["serialequipo"] : "");
    $observacion = (isset($_POST["observacion"]) ? $_POST["observacion"] : null);
    $uso = 0;
    // $observacion = null;

    
    date_default_timezone_set('America/Bogota'); // Establecer la zona horaria de Bogotá, Colombia

    // Obtener la fecha actual
    $fecha_actual = Date('Y-m-d');

    //Preparar la insercción de los datos
    $sentencia = $conexion->prepare("INSERT INTO docks(numeroequipo,serialequipo,marca,fechacompra,añosuso,observacion) 
    VALUES (:numeroequipo,:serialequipo,:marca,:fechacompra,:uso,:observacion);");

    //Asignando los valores que vienen del método POST (los que vienen del formulario)
    $sentencia->bindParam(":numeroequipo", $numeroequipo);
    $sentencia->bindParam(":serialequipo", $serialequipo);
    $sentencia->bindParam(":marca", $marca);
    $sentencia->bindParam(":fechacompra", $fecha_actual);
    $sentencia->bindParam(":uso", $uso);
    $sentencia->bindParam(":observacion", $observacion);

    // $sentencia->bindParam(":proveedor", $proveedor);
    // $sentencia->bindParam(":garantia", $garantia);
    $sentencia->execute();
    $mensaje = "Registro agregado";
    header("Location:index.php?mensaje=" . $mensaje);
}

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
        Docks
    </div>
    <div class="card-body">

        <form action="" method="post" enctype="multipart/form-data" id="formularioCrearDock">
            <div class="row">

                <div class="mb-3 col-lg-6">
                    <label for="numeroequipo" class="form-label required">Número Equipo</label>
                    <input type="text" class="form-control" name="numeroequipo" id="numeroequipo">
                    <span id="mensajeErrorNumeroequipo" class="text-danger" style="display: none;" >Completa este campo por favor</span>
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="marca" class="form-label required">Marca</label>
                    <input type="text" class="form-control" name="marca" id="marca" >
                    <span id="mensajeErrormarca" class="text-danger" style="display: none;">Completa este campo por favor</span>
                </div>


                <div class="mb-3 col-lg-4">
                    <label for="serialequipo" class="form-label required">Serial Equipo</label>
                    <input type="text" class="form-control" name="serialequipo" id="serialequipo">
                    <span id="mensajeErrorSerialequipo" class="text-danger" style="display: none;">Completa este campo por favor</span>
                </div>
                
                <div class="mb-3 col-lg-8">
                    <label for="observacion" class="form-label ">Observaciones</label>
                    <input type="text" class="form-control" name="observacion" id="observacion">
                  
                </div>


            </div>

         

      
            <!-- <div class="mb-3">
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
            </div> -->


            <button type="submit" class="btn btn-success">Agregar Registro</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>

        </form>

    <div class="card-footer text-muted"></div>
</div>
<script>
      // Esperamos a que se cargue todo el contenido del DOM
      document.addEventListener("DOMContentLoaded", function() {
        // Seleccionamos el formulario por su ID
        var form = document.getElementById('formularioCrearDock');

        // Agregamos un listener para el evento submit del formulario
        form.addEventListener('submit', function(event) {
            // Evitamos que el formulario se envíe automáticamente
            event.preventDefault();

            // Obtenemos el valor del campo numeropc y lo recortamos
            var numeroequipo = document.getElementById('numeroequipo').value.trim();
            var serialequipo = document.getElementById('serialequipo').value.trim();
            var marca = document.getElementById('marca').value.trim();
          

            // Verificamos si hay campos vacios
            if (numeroequipo === '' || serialequipo === '' || marca === '' ) {

                if(numeroequipo === ''){
                    // Mostramos el mensaje de error
                    document.getElementById('mensajeErrorNumeroequipo').style.display = 'inline';
                }else{
                    // En caso contrario, ocultamos el mensaje de error (si estaba visible)
                    document.getElementById('mensajeErrorNumeroequipo').style.display = 'none';
                }
                
                if (serialequipo === ''){
                    document.getElementById('mensajeErrorSerialequipo').style.display = 'inline';
                }else{
                    document.getElementById('mensajeErrorSerialequipo').style.display = 'none';
                }

                if (marca === ''){
                    document.getElementById('mensajeErrormarca').style.display = 'inline';
                }else{
                    document.getElementById('mensajeErrormarca').style.display = 'none';
                }


               
            } else {
                // En caso contrario, ocultamos el mensaje de error (si estaba visible)
                document.getElementById('mensajeErrorNumeroequipo').style.display = 'none';
                document.getElementById('mensajeErrorSerialequipo').style.display = 'none';
                document.getElementById('mensajeErrormarca').style.display = 'none';

                console.log("todos los campos estan llenos y procedemos a enviar el formulario");

                // Aquí puedes realizar alguna acción adicional, como enviar el formulario
                form.submit(); // Esto enviaría el formulario, pero lo comenté para que lo manejes como desees
            }
        });
    });
</script>

<?php include("../../estructura/footer.php"); ?>