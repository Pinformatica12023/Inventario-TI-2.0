<link rel="icon" href="../../img/logoFLA.png" type="image/png">

<?php
include("../../bd.php");
if (isset($_GET['ChangeStatusID'])) {

        $txtID = (isset($_GET['ChangeStatusID'])) ? $_GET['ChangeStatusID'] : "";
        //Finalizado
        $sentenciaCambioEstado  = $conexion->prepare("UPDATE prestamodispositivo SET Estado_prestamo  = 'FINALIZADO' WHERE id = :id");
        $sentenciaCambioEstado->bindParam(":id",$txtID);
        $sentenciaCambioEstado->execute();

        //obtenemos el prestamo, para obtener el dispositivo y asi poder actualizarle la cantidad
        $sentencia = $conexion->prepare("SELECT * FROM prestamodispositivo WHERE id = :id");
        $sentencia->bindParam(":id",$txtID);
    
        $sentenciaCambioEstado = $conexion->prepare("UPDATE dispositivos SET cantidad = cantidad + 1 WHERE id = :id ");
        $sentenciaCambioEstado->bindParam("",$txtID);
        $mensaje = "Préstamo finalizado exitosamente";
        header("Location:index.php?mensaje=" . $mensaje);
}


if (isset($_GET['txtID'])) {
    //Eliminar campo donde esta el id -->
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    //Buscar el archivo PDF relacionado con el bien (activo)
    $sentencia = $conexion->prepare("SELECT acta FROM prestamodispositivo WHERE id=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $registro_recuperado = $sentencia->fetch(PDO::FETCH_LAZY);

    if (isset($registro_recuperado["acta"]) && $registro_recuperado["acta"] !== "") {
        if (file_exists("../../secciones/actas/" . $registro_recuperado["acta"])) {
            unlink("../../secciones/actas/" . $registro_recuperado["acta"]);
        }
    }

    // Borrado
    $sentencia = $conexion->prepare("DELETE FROM prestamodispositivo WHERE id=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $mensaje = "Registro eliminado";
    header("Location:index.php?mensaje=" . $mensaje);
}

//Mostrar registros ordenados por ID descendente
$sentencia = $conexion->prepare("SELECT * FROM prestamodispositivo ORDER BY id DESC");
$sentencia->execute();
$lista_prestamodispositivo = $sentencia->fetchAll(PDO::FETCH_ASSOC);



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
        <a href="../../exportarprestamodispositivo.php" class="btn btn-success">Exportar a Excel</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="tabla_id"  >
                <thead>
                    <tr>
                        <th scope="col">Acciones</th>
                        <th scope="col">Actas</th>
                        <th scope="col" class="hidden-column">Id</th>
                        <th scope="col">Estado Préstamo</th>
                        <th scope="col">Identificación</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Dependencia</th>
                        <th scope="col">Dispositivo</th>
                        <th scope="col">Fecha De Asignación</th>
                        <th scope="col">Acta</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Observación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($lista_prestamodispositivo !== null && is_array($lista_prestamodispositivo)) {
                        foreach ($lista_prestamodispositivo as $registro) {
                    ?>
                            <tr class="">
                                <td>
                                    <a class="btn btn-info" href="editar.php?txtID=<?php echo $registro['id']; ?>" role="button">Editar</a>
                                    |<a class="btn btn-danger" href="javascript:borrar(<?php echo $registro['id']; ?>);" role="button">Eliminar</a>
                                    <a class="btn btn-success" href="javascript:finalizarPrestamo(<?php echo $registro['id']; ?>);" role="button">Finalizar</a>
                                </td>
                                <td>
                                    <a class="btn btn-dark" href="../../acta_entrega_devolucion/actaentregadispositivo.php?txtID=<?php echo $registro['id']; ?>" role="button" target="_blank">Entrega</a>
                                    |<a class="btn btn-warning" href="../../acta_entrega_devolucion/actadevoluciondispositivo.php?txtID=<?php echo $registro['id']; ?>" role="button" target="_blank">Devolución</a>
                                </td>
                                <td scope="row" class="hidden-column"><?php echo $registro['id']; ?></td>
                                <td class="<?php echo $registro['Estado_prestamo'] == 'EN_CURSO' ? 'text-success' : 'text-danger'; ?>"><?php echo $registro['Estado_prestamo']; ?></td>
                                <td><?php echo $registro['identificacion']; ?></td>
                                <td><?php echo $registro['nombreusuario']; ?></td>
                                <td><?php echo $registro['nombredependencia']; ?></td>
                                <td>
                                    <?php
                                    $idDispositivo = $registro['dispositivo'];
                                    $sentenciaModelo = $conexion->prepare("SELECT nombredeldispositivo FROM dispositivos WHERE id=:id");
                                    $sentenciaModelo->bindParam(":id", $idDispositivo);
                                    $sentenciaModelo->execute();
                                    $dispositivo = $sentenciaModelo->fetch(PDO::FETCH_ASSOC);
                                    echo ($dispositivo) ? $dispositivo['nombredeldispositivo'] : 'No disponible';
                                    ?>
                                </td>
                                <td><?php echo $registro['fechadispositivo']; ?></td>
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