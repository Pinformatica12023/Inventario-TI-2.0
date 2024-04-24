<link rel="icon" href="../../img/logoFLA.png" type="image/png">

<?php
include("../../bd.php");

if (isset($_GET['ChangeStatusID'])) {

    $txtID = (isset($_GET['ChangeStatusID'])) ? $_GET['ChangeStatusID'] : "";
    try {
        // Iniciar transacción
        $conexion->beginTransaction();

        // Cambiar el estado del préstamo a 'FINALIZADO'
        $sentenciaCambioEstadoPrestamo = $conexion->prepare("UPDATE prestamoequipo SET EstadoPrestamo = 'FINALIZADO' WHERE id = :id");
        $sentenciaCambioEstadoPrestamo->bindParam(":id", $txtID);
        $sentenciaCambioEstadoPrestamo->execute();

        //obtener el prestamo actualizado
        $sentenciaObtenerPrestamo = $conexion->prepare("SELECT * FROM prestamoequipo WHERE id = :id");
        $sentenciaObtenerPrestamo->bindParam(":id", $txtID);
        $sentenciaObtenerPrestamo->execute();
        $registro_recuperado = $sentenciaObtenerPrestamo->fetch(PDO::FETCH_ASSOC);


        // actualizamos el estado del equipo relacionado al préstamo a disponible
        $sentenciaActualizarEquipo = $conexion->prepare("UPDATE  equipos set Estado  = 'DISPONIBLE' WHERE numeropc = :numeropc");
        $sentenciaActualizarEquipo->bindParam(":numeropc", $registro_recuperado["modelo"]);
        $sentenciaActualizarEquipo->execute();
       

        // Confirmar la transacción
        $conexion->commit();

        $mensaje = "Préstamo finalizado exitosamente";
        header("Location:index.php?mensaje=" . $mensaje);
    } catch (PDOException $e) {
        // Revertir la transacción en caso de error
        $conexion->rollBack();
        // Manejar el error, por ejemplo, mostrando un mensaje al usuario
        echo "Error: " . $e->getMessage();
    }
}

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
        font-size: 15px;
        /* Reducir aún más el tamaño de la fuente */
    }

    #tabla_id .btn {
        font-size: 15px;
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
<h1 class="text-center fw-bold">Préstamo equipos</h1>
<div class="col-lg-12 col-12" style="width: 1500px;">
<div class="card card-transparent">
    <div class="card-header">
        <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar Prestamo</a>
        <a href="../../exportarprestamoequipo.php" class="btn btn-success">Exportar a Excel</a>
    </div>
   
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="tabla_id" >
                    <thead>
                        <tr>
                            <th scope="col">Acciones</th>
                            <th scope="col">Actas</th>
                            <th scope="col" class="hidden-column">Id</th>
                            <th scope="col">Acta</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Dependencia</th>
                            <th scope="col">Equipo</th>
                            <th scope="col">Estado</th>
                           
                    </thead>
                    <tbody>
                        <?php
                        if ($lista_prestamoequipo !== null && is_array($lista_prestamoequipo)) {
                            foreach ($lista_prestamoequipo as $registro) {
                        ?>
                                <tr class="">
                                    <td>
                                      
                                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#info<?php echo $registro["id"] ?>">
                                            Detalles
                                        </button>

                                        <div class="modal fade" id="info<?php echo $registro["id"] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Detalles Préstamo equipo <?php echo $registro["modelo"] ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="" class="form-label fs-5 ">Identificación Usuario</label>
                                                            <input type="text" class="form-control" readonly value=<?php if($registro['identificacion']=="" || $registro["identificacion"]==null){
                                                                echo "No hay usuario registrado en este préstamo";
                                                            }else{
                                                                echo $registro['identificacion'];
                                                            }?>>
                                                        
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="" class="form-label fs-5 ">Nombre Usuario</label>
                                                            <input type="text" class="form-control" readonly value=<?php if($registro['nombre']=="" || $registro["nombre"]==null){
                                                                echo "No hay usuario registrado en este préstamo";
                                                            }else{
                                                                echo $registro['nombre'];
                                                            }?>>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="" class="form-label fs-5 ">Dependencia</label>
                                                            <input type="text" class="form-control" readonly value="<?php if($registro['dependencia']=="" || $registro["dependencia"]==null){
                                                                echo "No hay dependencia Registrada en este préstamo";
                                                            }else{
                                                                echo $registro['dependencia'];
                                                            }?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="" class="form-label fs-5 ">Equipo</label>
                                                            <input type="text" class="form-control" readonly value=<?php
                                                                $nombreModelo = $registro['modelo'];
                                                                $sentenciaModelo = $conexion->prepare("SELECT numeropc FROM equipos WHERE numeropc=:nombre");
                                                                $sentenciaModelo->bindParam(":nombre", $nombreModelo);
                                                                $sentenciaModelo->execute();
                                                                $equipo = $sentenciaModelo->fetch(PDO::FETCH_ASSOC);
                                                                echo ($equipo) ? $equipo['numeropc'] : 'No disponible';
                                                                ?>>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="" class="form-label fs-5 ">Serial PC</label>
                                                            <input type="text" class="form-control" readonly value=<?php if($registro['serialpc']=="" || $registro["serialpc"]==null){
                                                                echo "No hay serial registrado en este préstamo";
                                                            }else{
                                                                echo $registro['serialpc'];
                                                            }?>>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="" class="form-label fs-5 ">Marca</label>
                                                            <input type="text" class="form-control" readonly value=<?php if($registro['marca']=="" || $registro["marca"]==null){
                                                                echo "No hay marca registrada en este préstamo";
                                                            }else{
                                                                echo $registro['marca'];
                                                            }?>>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="" class="form-label fs-5 ">Fecha asignación</label>
                                                            <input type="date" class="form-control" readonly value=<?php if($registro['fechaequipo']=="" || $registro["fechaequipo"]==null){
                                                                echo "No hay fecha de asignación registrada en este prestamo";
                                                            }else{
                                                                echo $registro['fechaequipo'];
                                                            }?>>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="" class="form-label fs-5 ">Estado</label>
                                                            <input type="text" class="form-control" readonly value=<?php if($registro['EstadoPrestamo']=="" || $registro["EstadoPrestamo"]==null){
                                                                echo "No hay estado registrado en este préstamo";
                                                            }else{
                                                                echo $registro['EstadoPrestamo'];
                                                            }?>>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="" class="form-label fs-5 ">observación</label>
                                                            <input type="text" class="form-control" readonly value="<?php if($registro['observacion']=="" || $registro["observacion"]==null){
                                                                echo "No hay observaciones registradas en este préstamo";
                                                            }else{
                                                                echo $registro['observacion'];
                                                            }?>">
                                                        </div>

                                                    <div class="modal-footer">
                                                        
                                                        <a class="btn btn-info" href="editar.php?txtID=<?php echo $registro['id']; ?>" role="button">Editar</a>
                                                        <a class="btn btn-success" href="javascript:finalizarPrestamo(<?php echo $registro['id']; ?>);" role="button">Finalizar</a>
                                                        <a class="btn btn-danger" href="javascript:borrar(<?php echo $registro['id']; ?>);" role="button">Eliminar</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </td>
                                    <td>
                                        <a class="btn btn-dark" href="../../acta_entrega_devolucion/actaentregaequipo.php?txtID=<?php echo $registro['id']; ?>" role="button" target="_blank">Entrega</a>
                                        |<a class="btn btn-warning" href="../../acta_entrega_devolucion/actadevolucionequipo.php?txtID=<?php echo $registro['id']; ?>" role="button" target="_blank">Devolución</a>
                                    </td>
                                    <td scope="row" class="hidden-column"><?php echo $registro['id']; ?></td>
                                    <td>
                                        <?php if (!empty($registro['acta'])) : ?>
                                            <a href="../../secciones/actas/<?php echo $registro['acta']; ?>"><?php echo $registro['acta']; ?></a>
                                        <?php else : ?>
                                            <span style="color: red; font-weight: bold;">No hay actas disponibles</span>
                                        <?php endif; ?>
                                    </td>
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
                                    <td class="<?php echo $registro['EstadoPrestamo'] == 'EN_CURSO' ? 'text-success' : 'text-danger'; ?>"><?php echo $registro['EstadoPrestamo']; ?></td>
                                  
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
</div>


<?php include("../../estructura/footer.php"); ?>