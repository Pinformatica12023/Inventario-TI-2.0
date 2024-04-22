<link rel="icon" href="../../img/logoFLA.png" type="image/png">

<?php
include("../../bd.php");

if (isset($_GET['txtID'])) {
    //Eliminar campo donde esta el id -->
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    //Buscar el archivo PDF relacionado con el bien (activo)
    $sentencia = $conexion->prepare("SELECT acta FROM prestamoequipo WHERE id=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $registro_recuperado = $sentencia->fetch(PDO::FETCH_LAZY);

    if (isset($registro_recuperado["acta"]) && $registro_recuperado["acta"] !== "") {
        if (file_exists("../../secciones/actas/" . $registro_recuperado["acta"])) {
            unlink("../../secciones/actas/" . $registro_recuperado["acta"]);
        }
    }

    // Borrado
    $sentencia = $conexion->prepare("DELETE FROM prestamoequipo WHERE id=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $mensaje = "Registro eliminado";
    header("Location:index.php?mensaje=" . $mensaje);
}

//Mostrar registros ordenados por ID descendente
$sentencia = $conexion->prepare("SELECT * FROM prestamoequipo ORDER BY id DESC");
$sentencia->execute();
$lista_prestamoequipo = $sentencia->fetchAll(PDO::FETCH_ASSOC);

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
        <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar Prestamo</a>
        <a href="../../exportarprestamoequipo.php" class="btn btn-success">Exportar a Excel</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="tabla_id">
                <thead>
                    <tr>
                        <th scope="col">Acciones</th>
                        <th scope="col">Actas</th>
                        <th scope="col" class="hidden-column">Id</th>
                        <th scope="col">Identificación</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Dependencia</th>
                        <th scope="col">Equipo</th>
                        <th scope="col">Serial PC</th>
                        <th scope="col">Marca</th>
                        <th scope="col">Fecha De Asignación</th>
                        <th scope="col">Acta</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Observación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($lista_prestamoequipo !== null && is_array($lista_prestamoequipo)) {
                        foreach ($lista_prestamoequipo as $registro) {
                    ?>
                            <tr class="">
                                <td>
                                    <a class="btn btn-info" href="editar.php?txtID=<?php echo $registro['id']; ?>" role="button">Editar</a>
                                    |<a class="btn btn-danger" href="javascript:borrar(<?php echo $registro['id']; ?>);" role="button">Eliminar</a>
                                </td>
                                <td>
                                    <a class="btn btn-dark" href="../../acta_entrega_devolucion/actaentregaequipo.php?txtID=<?php echo $registro['id']; ?>" role="button" target="_blank">Entrega</a>
                                    |<a class="btn btn-warning" href="../../acta_entrega_devolucion/actadevolucionequipo.php?txtID=<?php echo $registro['id']; ?>" role="button" target="_blank">Devolución</a>
                                </td>
                                <td scope="row" class="hidden-column"><?php echo $registro['id']; ?></td>
                                <td><?php echo $registro['identificacion']; ?></td>
                                <td><?php echo $registro['nombre']; ?></td>
                                <td><?php echo $registro['dependencia']; ?></td>
                                <td>
                                    <?php
                                    $nombreModelo = $registro['modelo'];
                                    $sentenciaModelo = $conexion->prepare("SELECT numeropc FROM equipos WHERE numeropc=:nombre");
                                    $sentenciaModelo->bindParam(":nombre", $nombreModelo);
                                    $sentenciaModelo->execute();
                                    $equipo = $sentenciaModelo->fetch(PDO::FETCH_ASSOC);
                                    echo ($equipo) ? $equipo['numeropc'] : 'No disponible';
                                    ?>
                                </td>
                                <td><?php echo $registro['serialpc']; ?></td>
                                <td><?php echo $registro['marca']; ?></td>
                                <td><?php echo $registro['fechaequipo']; ?></td>
                                <td>
                                    <?php if (!empty($registro['acta'])) : ?>
                                        <a href="../../secciones/actas/<?php echo $registro['acta']; ?>"><?php echo $registro['acta']; ?></a>
                                    <?php else : ?>
                                        <span style="color: red; font-weight: bold;">No hay actas disponibles</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $registro['estado']; ?></td>
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