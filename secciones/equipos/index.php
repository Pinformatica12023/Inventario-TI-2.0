<link rel="icon" href="../../img/logoFLA.png" type="image/png">

<?php
include("../../bd.php");

if (isset($_GET['txtID'])) {
    //Eliminar campo donde esta el id -->
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";
    // Borrado
    $sentencia = $conexion->prepare("DELETE FROM equipos WHERE id=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $mensaje = "Registro eliminado";
    header("Location:index.php?mensaje=" . $mensaje);
}

//Mostrar registros
$sentencia = $conexion->prepare("SELECT * FROM equipos ORDER BY id DESC");
$sentencia->execute();
$lista_equipos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
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

<div class="card card-transparent">
    <div class="card-header">
        <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar Equipos</a>
        <a href="../../exportarequipos.php" class="btn btn-success">Exportar a Excel</a>
    </div>
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered" id="tabla_id">
                <thead>
                    <tr>
                        <th scope="col">Acciones</th>
                        <th scope="col" class="hidden-column">ID</th>
                        <th scope="col">Equipo</th>
                        <th scope="col">Serial PC</th>
                        <th scope="col">Serial Cargador</th>
                        <th scope="col">Placa</th>
                        <th scope="col">Activo</th>
                        <th scope="col">Fecha Compra</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">RAM</th>
                        <th scope="col">Procesador</th>
                        <th scope="col">Marca</th>
                        <th scope="col">Almacenamiento</th>
                        <th scope="col">Observación</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($lista_equipos !== null && is_array($lista_equipos)) {
                        foreach ($lista_equipos as $registro) {
                    ?>
                            <tr class="">
                                <td>
                                    <a class="btn btn-info" href="editar.php?txtID=<?php echo $registro['id']; ?>" role="button">Editar</a>
                                    |<a class="btn btn-danger" href="javascript:borrar(<?php echo $registro['id']; ?>);" role="button">Eliminar</a>
                                </td>
                                <td scope="row" class="hidden-column"><?php echo $registro['id']; ?></td>
                                <td><?php echo $registro['numeropc']; ?></td>
                                <td><?php echo $registro['serialpc']; ?></td>
                                <td><?php echo $registro['serialcargador']; ?></td>
                                <td><?php echo $registro['placa']; ?></td>
                                <td><?php echo $registro['activo']; ?></td>
                                <td><?php echo $registro['fechacompra']; ?></td>
                                <td><?php echo $registro['tipo']; ?></td>
                                <td><?php echo $registro['ram']; ?></td>
                                <td><?php echo $registro['procesador']; ?></td>
                                <td><?php echo $registro['marca']; ?></td>
                                <td><?php echo $registro['almacenamiento']; ?></td>
                                <td><?php echo $registro['observacion']; ?></td>                            
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