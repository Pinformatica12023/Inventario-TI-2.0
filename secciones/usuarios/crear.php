<link rel="icon" href="../../img/logoFLA.png" type="image/png">
<?php
include("../../bd.php");

if ($_POST) {
    //Recolectamos los datos del método POST (Datos activo)
    $identificacion = (isset($_POST["identificacion"]) ? $_POST["identificacion"] : "");
    $nombre = (isset($_POST["nombre"]) ? $_POST["nombre"] : "");
    $cargo = (isset($_POST["cargo"]) ? $_POST["cargo"] : "");
    $dependencia = (isset($_POST["dependencia"]) ? $_POST["dependencia"] : "");


    //Preparar la insercción de los datos
    $sentencia = $conexion->prepare("INSERT INTO usuarios(id,identificacion,nombre,cargo,dependencia) 
    VALUES (NULL,:identificacion,:nombre,:cargo,:dependencia);");

    //Asignando los valores que vienen del método POST (los que vienen del formulario)
    $sentencia->bindParam(":identificacion", $identificacion);
    $sentencia->bindParam(":nombre", $nombre);
    $sentencia->bindParam(":cargo", $cargo);
    $sentencia->bindParam(":dependencia", $dependencia);
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
        Datos De Los Usuarios
    </div>
    <div class="card-body">

        <form id="formularioCrearUsuario" action="" method="post" enctype="multipart/form-data">

            <div class="row">

                <div class="mb-3 col-lg-6">
                    <label for="identificacion" class="form-label required">Identificacion</label>
                    <input type="text"  class="form-control" name="identificacion" id="identificacion" aria-describedby="helpId" placeholder="">
                    <span id="mensajeErrorIdentificacion" style="display: none; color: red;">Completa este campo por favor</span>
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="nombre" class="form-label required">Nombre</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" aria-describedby="helpId" placeholder="Nombre completo con Apellidos ">
                    <span id="mensajeErrorNombre" style="display: none; color: red;">Completa este campo por favor</span>
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="cargo" class="form-label required">Cargo</label>
                    <input type="text" class="form-control" name="cargo" id="cargo" aria-describedby="helpId" placeholder="">
                    <span id="mensajeErrorCargo" style="display: none; color: red;">Completa este campo por favor</span>
                </div>

                <div class="mb-3 col-lg-6">
                    <label for="dependencia" class="form-label required">Dependencia</label>
                    <input type="text" class="form-control" name="dependencia" id="dependencia" aria-describedby="helpId" placeholder="">
                    <span id="mensajeErrorDependencia" style="display: none; color: red;">Completa este campo por favor</span>
                </div>

            </div>
          
            <button type="submit" class="btn btn-success">Agregar Registro</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>

        </form>

    </div>
    <div class="card-footer text-muted"></div>
</div>

<script>
     document.addEventListener("DOMContentLoaded", function() {
        // Seleccionamos el formulario por su ID
        var form = document.getElementById('formularioCrearUsuario');

        // Agregamos un listener para el evento submit del formulario
        form.addEventListener('submit', function(event) {
            // Evitamos que el formulario se envíe automáticamente
            event.preventDefault();

            // Obtenemos el valor del campo numeropc y lo recortamos
            var identificacion = document.getElementById('identificacion').value.trim();
            var nombre= document.getElementById('nombre').value.trim();
            var cargo = document.getElementById('cargo').value.trim();
            var dependencia = document.getElementById('dependencia').value.trim();

            if(identificacion === '' || nombre === '' || cargo === '' || dependencia === ''){
                if(identificacion==''){
                    // Mostramos el mensaje de error
                    document.getElementById('mensajeErrorIdentificacion').style.display = 'inline';
                }else{
                    // En caso contrario, ocultamos el mensaje de error (si estaba visible)
                    document.getElementById('mensajeErrorIdentificacion').style.display = 'none';
                }
                
                if(nombre==''){
                    // Mostramos el mensaje de error
                    document.getElementById('mensajeErrorNombre').style.display = 'inline';
                }else{
                    // En caso contrario, ocultamos el mensaje de error (si estaba visible)
                    document.getElementById('mensajeErrorNombre').style.display = 'none';
                }

                if(cargo==''){
                    // Mostramos el mensaje de error
                    document.getElementById('mensajeErrorCargo').style.display = 'inline';
                }else{
                    // En caso contrario, ocultamos el mensaje de error (si estaba visible)
                    document.getElementById('mensajeErrorCargo').style.display = 'none';
                }

                if(dependencia==''){
                    // Mostramos el mensaje de error
                    document.getElementById('mensajeErrorDependencia').style.display = 'inline';
                }else{
                    // En caso contrario, ocultamos el mensaje de error (si estaba visible)
                    document.getElementById('mensajeErrorDependencia').style.display = 'none';
                }
            }else{
                // En caso contrario, ocultamos el mensaje de error (si estaba visible)
                document.getElementById('mensajeErrorIdentificacion').style.display = 'none';
                document.getElementById('mensajeErrorNombre').style.display = 'none';
                document.getElementById('mensajeErrorCargo').style.display = 'none';
                document.getElementById('mensajeErrorDependencia').style.display = 'none';
                form.submit();
            }
        });
    });
</script>

<?php include("../../estructura/footer.php"); ?>