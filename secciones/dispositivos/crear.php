<link rel="icon" href="../../img/logoFLA.png" type="image/png">
<?php
include("../../bd.php");

if ($_POST) {
    //Recolectamos los datos del método POST (Datos activo)
    $nombredeldispositivo = (isset($_POST["nombredeldispositivo"])?$_POST["nombredeldispositivo"]:"");
    $cantidad = (isset($_POST["cantidad"])?$_POST["cantidad"]:"");

    //Preparar la insercción de los datos
    $sentencia = $conexion->prepare("INSERT INTO dispositivos(id,nombredeldispositivo,cantidad) 
    VALUES (NULL,:nombredeldispositivo,:cantidad);");

    //Asignando los valores que vienen del método POST (los que vienen del formulario)
    $sentencia->bindParam(":nombredeldispositivo", $nombredeldispositivo);
    $sentencia->bindParam(":cantidad", $cantidad);
    $sentencia->execute();
    $mensaje = "Registro agregado";
    header("Location:index.php?mensaje=".$mensaje);
}

?>


<?php include("../../estructura/header.php"); ?>

<style>
    body {
        background-image: url("../../img/FLA13.jpg");
        background-size: cover; /* Ajusta la imagen al tamaño del contenedor */
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
        Dispositivos
    </div>
    <div class="card-body">

        <form action="" method="post" enctype="multipart/form-data">

            <div class="row">

                <div class="mb-3 col-lg-6">
                    <label for="nombredeldispositivo" class="form-label">Dispositivo</label>
                    <input type="text" class="form-control" name="nombredeldispositivo" id="nombredeldispositivo" aria-describedby="helpId" placeholder="">
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="text" class="form-control" name="cantidad" id="cantidad" aria-describedby="helpId" placeholder="">
                    <span id="mensajeError" style="display: none; color: red;">Solo se permiten números</span>
                </div>

            </div>
           

            <button type="submit" class="btn btn-success">Agregar Registro</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>

        </form>

    </div>
    <div class="card-footer text-muted"></div>
</div>
<script>
    const inputCantidad = document.getElementById('cantidad');
    const mensajeError = document.getElementById('mensajeError');

    inputCantidad.addEventListener('keypress', function(event) {
        const teclaPresionada = event.key;
        if (isNaN(teclaPresionada) && teclaPresionada !== ' ') {
            event.preventDefault(); // Cancela el evento de escritura
            mensajeError.style.display = 'inline'; // Muestra el span
        } else {
            mensajeError.style.display = 'none'; // Oculta el span
        }
    });
</script>

<?php include("../../estructura/footer.php"); ?>