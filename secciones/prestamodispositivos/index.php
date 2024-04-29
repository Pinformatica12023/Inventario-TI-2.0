<link rel="icon" href="../../img/logoFLA.png" type="image/png">

<?php
    include("../../bd.php");
    if (isset($_GET['ChangeStatusID'])) {

        $txtID = (isset($_GET['ChangeStatusID'])) ? $_GET['ChangeStatusID'] : "";
        try {
            // Iniciar transacción
            $conexion->beginTransaction();

            // Cambiar el estado del préstamo a 'FINALIZADO'
            $sentenciaCambioEstadoPrestamo = $conexion->prepare("UPDATE prestamodispositivo SET Estado_prestamo = 'FINALIZADO' WHERE id = :id");
            $sentenciaCambioEstadoPrestamo->bindParam(":id", $txtID);
            $sentenciaCambioEstadoPrestamo->execute();
            
            // echo "<script>console.log('ya actualizamos el estado' );</script>";
            // Obtener el préstamo para actualizar la cantidad del dispositivo
            $sentenciaObtenerPrestamo = $conexion->prepare("SELECT dispositivo FROM prestamodispositivo WHERE id = :id");
            $sentenciaObtenerPrestamo->bindParam(":id", $txtID);
            $sentenciaObtenerPrestamo->execute();
            $prestamo = $sentenciaObtenerPrestamo->fetch(PDO::FETCH_ASSOC);
            $dispositivo = $prestamo["dispositivo"];


            // Actualizar la cantidad del dispositivo
            $sentenciaActualizarDispositivo = $conexion->prepare("UPDATE dispositivos SET cantidad = cantidad + 1 WHERE id = :id");
            $sentenciaActualizarDispositivo->bindParam(":id",$dispositivo);
            $sentenciaActualizarDispositivo->execute();

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
        background-image: url("../../img/FLA13.jpg");
        background-size: cover;
        /* Ajusta la imagen al tamaño del contenedor */
        background-repeat: no-repeat;
    }
</style>

<!--Fondo transparente para el cuadro -->
<style>
    .card-transparent {
        background-color: rgba(255, 255, 255, 0.8);
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
<H1 class="fw-bold text-light text-center ">Préstamo Dispositivos</H1>

   
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
                        <!-- <th scope="col">Acciones</th> -->
                        <th scope="col">Actas</th>
                        <th scope="col">Acta</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Dispositivo</th>
                        <th scope="col">Dependencia</th>
                        <th scope="col">Estado</th>
                        <th scope="col" class="hidden-column">Id</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($lista_prestamodispositivo !== null && is_array($lista_prestamodispositivo)) {
                        foreach ($lista_prestamodispositivo as $registro) {
                    ?>
                            <div class="modal fade" id="info<?php echo $registro['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Detalles Préstamo Dispositivo:   <?php
                                                $idDispositivo = $registro['dispositivo'];
                                                $sentenciaModelo = $conexion->prepare("SELECT nombredeldispositivo FROM dispositivos WHERE id=:id");
                                                $sentenciaModelo->bindParam(":id", $idDispositivo);
                                                $sentenciaModelo->execute();
                                                $dispositivo = $sentenciaModelo->fetch(PDO::FETCH_ASSOC);
                                                echo ($dispositivo) ? $dispositivo['nombredeldispositivo'] : 'No disponible';
                                                ?>
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="form-group col-lg-6">
                                                    <label for="" class="form-label fs-5 ">Identificación Usuario</label>
                                                    <input type="text" class="form-control" readonly value="<?php if($registro['identificacion']=="" || $registro["identificacion"]==null){
                                                        echo "No hay usuario registrado en este préstamo";
                                                    }else{
                                                        echo $registro['identificacion'];
                                                    }?>">
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <label for="" class="form-label fs-5 ">Nombre Usuario</label>
                                                    <input type="text" class="form-control" readonly value="<?php if($registro['nombreusuario']=="" || $registro["nombreusuario"]==null){
                                                        echo "No hay usuario registrado en este préstamo";
                                                    }else{
                                                        echo $registro['nombreusuario'];
                                                    }?>">
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <label for="" class="form-label fs-5 ">Fecha asignación</label>
                                                    <input type="date" class="form-control" readonly value="<?php if($registro['fechadispositivo']=="" || $registro["fechadispositivo"]==null){
                                                        echo "No hay fecha de asignación registrada en este prestamo";
                                                    }else{
                                                        echo $registro['fechadispositivo'];
                                                    }?>">
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <label for="" class="form-label fs-5 ">Estado</label>
                                                    <input type="text" class="form-control <?php echo $registro['Estado_prestamo'] == 'EN_CURSO' ? 'text-success' : 'text-danger'; ?>" readonly value="<?php if($registro['Estado_prestamo']=="" || $registro["Estado_prestamo"]==null){
                                                        echo "No hay estado registrado en este préstamo";
                                                    }else{
                                                        echo $registro['Estado_prestamo'];
                                                    }?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="form-label fs-5 ">observación</label>
                                                    <input type="text" class="form-control" readonly value="<?php if($registro['observacion']=="" || $registro["observacion"]==null){
                                                        echo "No hay observaciones registradas en este préstamo";
                                                    }else{
                                                        echo $registro['observacion'];
                                                    }?>">
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <a class="btn btn-info text-light" href="editar.php?txtID=<?php echo $registro['id']; ?>" role="button">Editar</a>
                                            <a class="btn btn-danger" href="javascript:borrar(<?php echo $registro['id']; ?>);" role="button">Eliminar</a>
                                            <a class="btn btn-success" href="javascript:finalizarPrestamo(<?php echo $registro['id']; ?>);" role="button">Finalizar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <tr id="<?php echo $registro['id'] ?>">
                                
                                <td>
                                    <a class="btn btn-dark" href="../../acta_entrega_devolucion/actaentregadispositivo.php?txtID=<?php echo $registro['id']; ?>" role="button" target="_blank">Entrega</a>
                                    <a class="btn btn-warning" href="../../acta_entrega_devolucion/actadevoluciondispositivo.php?txtID=<?php echo $registro['id']; ?>" role="button" target="_blank">Devolución</a>
                                </td>
                                <td>
                                    <?php if (!empty($registro['acta'])) : ?>
                                        <a href="../../secciones/actas/<?php echo $registro['acta']; ?>"><?php echo $registro['acta']; ?></a>
                                    <?php else : ?>
                                        <span style="color: red; font-weight: bold;">No hay actas disponibles</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $registro['nombreusuario']; ?></td>
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
                                <td><?php echo $registro['nombredependencia']; ?></td>
                                <td class="<?php echo $registro['Estado_prestamo'] == 'EN_CURSO' ? 'text-success' : 'text-danger'; ?>"><?php echo $registro['Estado_prestamo']; ?></td>
                                <td scope="row" class="hidden-column"><?php echo $registro['id']; ?></td>
                              
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

<script>
    // Función para mostrar el modal cuando se hace clic en el registro
    function mostrarModal(id) {
        // Construye el id del modal basado en el id del registro
        var modalId = "info" + id;
        console.log(modalId);
        // Muestra el modal correspondiente
        $("#" + modalId).modal("show");
    }

    // Agrega un evento de clic a todos los elementos <tr>
    $(document).ready(function() {
        $("tr").click(function() {
            // Obtiene el id del registro del atributo id del <tr>
            var id = $(this).attr("id");
            
            // Muestra el modal correspondiente al hacer clic en el registro
            mostrarModal(id);
        });
    });
</script>
    





<?php include("../../estructura/footer.php"); ?>