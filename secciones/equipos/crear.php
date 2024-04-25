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

    /* Asterisco campos obligatorios */
    .required::after {
        content: "*";
        color: red;
     
        margin-left: 4px;
    }
</style>

<br>
<div class="card">
    <div class="card-header">
        Equipos
    </div>
    <div class="card-body">

        <form action="" method="post" id="formularioCrearEquipo" enctype="multipart/form-data">
            <div class="row">


                <div class="mb-3 col-lg-6">
                    <label for="numeropc" class="form-label required">Equipo</label>
                    <input type="text" class="form-control" name="numeropc" id="numeropc" aria-describedby="helpId" placeholder="">
                    <span id="mensajeErrorNumeropc" style="display: none; color: red;">Completa este campo por favor</span>
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="serialpc" class="form-label required">Serial PC</label>
                    <input type="text" class="form-control" name="serialpc" id="serialpc" aria-describedby="helpId" placeholder="">
                    <span id="mensajeErrorSerialpc" style="display: none; color: red;">Completa este campo por favor</span>
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="serialcargador" class="form-label">Serial Cargador</label>
                    <input type="text" class="form-control" name="serialcargador" id="serialcargador" readonly>
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="placa" class="form-label required">Placa</label>
                    <input type="text" class="form-control" name="placa" id="placa" aria-describedby="helpId" placeholder="">
                    <span id="mensajeErrorPlacapc" style="display: none; color: red;">Completa este campo por favor</span>
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="activo" class="form-label">Proveedor</label>
                    <input type="text" class="form-control" name="activo" id="activo" aria-describedby="helpId" placeholder="">
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="fechacompra" class="form-label ">Fecha De Compra</label>
                    <input type="date" class="form-control" name="fechacompra" id="fechacompra" aria-describedby="helpId" placeholder="">
                    <span id="mensajeErrorFecha" style="display: none; color: red;">La fecha de compra no puede ser futura</span>
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="tipo" class="form-label required">Tipo</label>
                    <select class="form-select form-select-sm" name="tipo" id="tipo">
                        <option selected>Seleccione uno</option>
                        <option value="PORTATIL">PORTATIL</option>
                        <option value="ESCRITORIO">ESCRITORIO</option>
                    </select>
                    <span id="mensajeErrorTipopc" style="display: none; color: red;">Debes seleccionar un tipo</span>
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="ram" class="form-label required">RAM</label>
                    <input type="text" class="form-control" name="ram" id="ram" aria-describedby="helpId" placeholder="">
                    <span id="mensajeErrorRampc" style="display: none; color: red;">Completa este campo por favor</span>
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="procesador" class="form-label required">Procesador</label>
                    <input type="text" class="form-control" name="procesador" id="procesador" aria-describedby="helpId" placeholder="">
                    <span id="mensajeErrorProcesadorpc" style="display: none; color: red;">Completa este campo por favor</span>
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="marca" class="form-label required">Marca</label>
                    <input type="text" class="form-control" name="marca" id="marca" aria-describedby="helpId" placeholder="">
                    <span id="mensajeErrorMarcapc" style="display: none; color: red;">Completa este campo por favor</span>
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="almacenamiento" class="form-label required">Almacenamiento</label>
                    <input type="text" class="form-control" name="almacenamiento" id="almacenamiento" aria-describedby="helpId" placeholder="">
                    <span id="mensajeErrorAlmacenamientopc" style="display: none; color: red;">Completa este campo por favor</span>
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="observacion" class="form-label">Observacion</label>
                    <input type="text" class="form-control" name="observacion" id="observacion" aria-describedby="helpId" placeholder="">
                </div>
            </div>

            <button type="submit" class="btn btn-success">Agregar Registro</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>

        </form>

    </div>
    <div class="card-footer text-muted"></div>
</div>

<script>
    
    // Esperamos a que se cargue todo el contenido del DOM
    document.addEventListener("DOMContentLoaded", function() {
        // Seleccionamos el formulario por su ID
        var form = document.getElementById('formularioCrearEquipo');

        // Agregamos un listener para el evento submit del formulario
        form.addEventListener('submit', function(event) {
            // Evitamos que el formulario se envíe automáticamente
            event.preventDefault();

            // Obtenemos el valor del campo numeropc y lo recortamos
            var numeropc = document.getElementById('numeropc').value.trim();
            var serialpc = document.getElementById('serialpc').value.trim();
            var placapc = document.getElementById('placa').value.trim();
            var tipo  = document.getElementById('tipo').value;
            var ram  = document.getElementById('ram').value.trim();
            var procesador  = document.getElementById('procesador').value.trim();
            var marca  = document.getElementById('marca').value.trim();
            var almacenamiento  = document.getElementById('almacenamiento').value.trim();

            // Verificamos si hay campos vacios
            if (numeropc === '' || serialpc === '' || placapc === '' || tipo == 'Seleccione uno'|| ram === '' || procesador === '' || marca === '' || almacenamiento ==='') {

                if(numeropc === ''){
                    // Mostramos el mensaje de error
                    document.getElementById('mensajeErrorNumeropc').style.display = 'inline';
                }else{
                    // En caso contrario, ocultamos el mensaje de error (si estaba visible)
                    document.getElementById('mensajeErrorNumeropc').style.display = 'none';
                }
                
                if (serialpc === ''){
                    document.getElementById('mensajeErrorSerialpc').style.display = 'inline';
                }else{
                    document.getElementById('mensajeErrorSerialpc').style.display = 'none';
                }

                if (placapc === ''){
                    document.getElementById('mensajeErrorPlacapc').style.display = 'inline';
                }else{
                    document.getElementById('mensajeErrorPlacapc').style.display = 'none';
                }

                if (tipo == 'Seleccione uno'){
                    document.getElementById('mensajeErrorTipopc').style.display = 'inline';
                }else{
                    document.getElementById('mensajeErrorTipopc').style.display = 'none';
                }

                if (ram === ''){
                    document.getElementById('mensajeErrorRampc').style.display = 'inline';
                }else{
                    document.getElementById('mensajeErrorRampc').style.display = 'none';
                }

                if (procesador === ''){
                    document.getElementById('mensajeErrorProcesadorpc').style.display = 'inline';
                }else{
                    document.getElementById('mensajeErrorProcesadorpc').style.display = 'none';
                }

                if (marca === ''){
                    document.getElementById('mensajeErrorMarcapc').style.display = 'inline';
                }else{
                    document.getElementById('mensajeErrorMarcapc').style.display = 'none';
                }

                if (almacenamiento === ''){
                    document.getElementById('mensajeErrorAlmacenamientopc').style.display = 'inline';
                }else{
                    document.getElementById('mensajeErrorAlmacenamientopc').style.display = 'none';
                }
                console.log("hay campos vacios");

               
            } else {
                // En caso contrario, ocultamos el mensaje de error (si estaba visible)
                document.getElementById('mensajeErrorNumeropc').style.display = 'none';
                document.getElementById('mensajeErrorSerialpc').style.display = 'none';
                document.getElementById('mensajeErrorPlacapc').style.display = 'none';
                document.getElementById('mensajeErrorTipopc').style.display = 'none';
                document.getElementById('mensajeErrorRampc').style.display = 'none';
                document.getElementById('mensajeErrorProcesadorpc').style.display = 'none';
                document.getElementById('mensajeErrorMarcapc').style.display = 'none';
                document.getElementById('mensajeErrorAlmacenamientopc').style.display = 'none';

                console.log("todos los campos estan llenos y procedemos a enviar el formulario");

                // Aquí puedes realizar alguna acción adicional, como enviar el formulario
                form.submit(); // Esto enviaría el formulario, pero lo comenté para que lo manejes como desees
            }
        });
    });


    
    document.getElementById('fechacompra').addEventListener('change', function() {
        var fechaCompra = new Date(this.value); // Obtener la fecha de compra ingresada
        var fechaActual = new Date(); // Obtener la fecha actual

        // Comparar las fechas
        if (fechaCompra > fechaActual) {
            document.getElementById('mensajeErrorFecha').style.display = 'inline'; // Mostrar el span si la fecha de compra es futura
        } else {
            document.getElementById('mensajeErrorFecha').style.display = 'none'; // Ocultar el span si la fecha de compra es actual o anterior
        }
    });
</script>

<?php include("../../estructura/footer.php"); ?>