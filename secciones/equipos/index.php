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
<h1 class="text-center fw-bold">Equipos</h1>
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
                       
                        <th scope="col">Tipo</th>
                        <th scope="col">RAM</th>
                        <th scope="col">Procesador</th>
                        <th scope="col">Marca</th>
                        <th scope="col">Almacenamiento</th>
                       
                        
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
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#info<?php echo $registro["id"] ?>">
                                    ver información
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="info<?php  echo $registro["id"] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Detalles equipo: <?php echo $registro['numeropc'] ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="" class="form-label fs-5 fw-bold">Serial cargador</label>
                                                    <input type="text" class="form-control" readonly value="<?php if($registro['serialcargador']==""){
                                                        echo "No hay registrado un serial de cargador para este equipo";
                                                    }else{
                                                        echo $registro['serialcargador'];
                                                    }?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="form-label fs-5">placa</label>
                                                    <input type="text" class="form-control" readonly value=<?php if($registro["placa"]==""){echo "No hay registrado una placa para este equipo";}else{echo $registro["placa"];}?>>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="form-label fs-5">Activo</label>
                                                    <input type="text" class="form-control" readonly value=<?php if($registro["activo"]==""){echo "No hay registrado un activo para este equipo";}else{echo $registro["activo"];}?>>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="form-label fs-5 fw-bold">Fecha Compra:</label>
                                                    <input type="date" class="form-control" readonly value="<?php if($registro["fechacompra"]==null){
                                                        echo "No hay fecha de compra registrada en este equipo";
                                                    }else{
                                                        echo $registro["fechacompra"];
                                                    } ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="form-label fs-5 fw-bold">Obsevaciones</label>
                                                    <textarea readonly  class="form-control"> <?php if($registro['observacion']==""){
                                                        echo "No hay Observaciones en este equipo";
                                                    }else{
                                                        echo $registro["observacion"];
                                                    } ?></textarea>
                                                </div>

                                              
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                             
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td scope="row" class="hidden-column"><?php echo $registro['id']; ?></td>
                                <td><?php echo $registro['numeropc']; ?></td>
                                <td><?php echo $registro['serialpc']; ?></td>                           
                                
                                <td><?php echo $registro['tipo']; ?></td>
                                <td><?php echo $registro['ram']; ?></td>
                                <td><?php echo $registro['procesador']; ?></td>
                                <td><?php echo $registro['marca']; ?></td>
                                <td><?php echo $registro['almacenamiento']; ?></td>
                                                          
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