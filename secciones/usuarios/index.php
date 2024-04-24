<link rel="icon" href="../../img/logoFLA.png" type="image/png">

<?php
include("../../bd.php");

if (isset($_GET['txtID'])) {
    //Eliminar campo donde esta el id -->
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    // Borrado
    $sentencia = $conexion->prepare("DELETE FROM usuarios WHERE id=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $mensaje = "Registro eliminado";
    header("Location:index.php?mensaje=" . $mensaje);
}

//Mostrar registros
$sentencia = $conexion->prepare("SELECT * FROM usuarios");
$sentencia->execute();
$lista_usuarios = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>



<?php include("../../estructura/header.php"); ?>

<style>
    body {
        background-image: url("../../img/Chispa_multimarca.jpg");
        background-size: cover;
        /* Ajusta la imagen al tamaño del contenedor */
        background-repeat: no-repeat;
    }
</style>

<!--Fondo transparente para el cuadro -->
<style>
    .card-transparent {
        background-color: rgba(255, 255, 255, 0.9);
        /* Cambia los valores RGB y el valor alfa (0.7) según tus preferencias */
    }

    /* Estilo para la tabla */
    #tabla_id {
        width: max-content;
        /* Ajusta el ancho máximo del contenido */
        overflow-x: auto;
        table-layout: auto;
        /* Distribución automática de las columnas */
    }

    #tabla_id th,
    #tabla_id td {
        white-space: nowrap;
        font-size: 10px;
        /* Reducir aún más el tamaño de la fuente */
    }

    #tabla_id .btn {
        font-size: 10px;
        /* Reducir el tamaño de la fuente de los botones */
        padding: 3px 8px;
        /* Ajustar el relleno para adaptarse al nuevo tamaño */
    }

    #tabla_id th:nth-child(1),
    #tabla_id td:nth-child(1) {
        width: 15%;
    }

    #tabla_id th:nth-child(2),
    #tabla_id td:nth-child(2) {
        width: 10%;
    }
    
    .hidden-column {
        display: none;
    }
</style>


<br />
<h1 class="fw-bold text-center">Usuarios</h1>
<div class="card card-transparent">
    <div class="card-header">
        <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar Usuario</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="tabla_id">
                <thead>
                    <tr>
                        <th scope="col" class="hidden-column">Id</th>
                        <th scope="col">Identificación</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Cargo</th>
                        <th scope="col">Dependencia</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($lista_usuarios !== null && is_array($lista_usuarios)) {
                        foreach ($lista_usuarios as $registro) {
                    ?>
                            <tr class="">
                                <td scope="row" class="hidden-column"><?php echo $registro['id']; ?></td>
                                <td><?php echo $registro['identificacion']; ?></td>
                                <td><?php echo $registro['nombre']; ?></td>
                                <td><?php echo $registro['cargo']; ?></td>
                                <td><?php echo $registro['dependencia']; ?></td>
                                <td>
                                    <a class="btn btn-info" href="editar.php?txtID=<?php echo $registro['id']; ?>" role="button">Editar</a>
                                    |<a class="btn btn-danger" href="javascript:borrar(<?php echo $registro['id']; ?>);" role="button">Eliminar</a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='15'>No hay datos disponibles.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php include("../../estructura/footer.php"); ?>