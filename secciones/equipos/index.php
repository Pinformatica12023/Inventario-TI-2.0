<link rel="icon" href="../../img/logoFLA.png" type="image/png">

<?php
include("../../bd.php");

if (isset($_GET['txtID'])) {
    //Eliminar campo donde esta el id -->
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";
    // Borrado (no es eliminar si no que dar de baja al equipo)
   
    $sentenciaBuscarEquipo  = $conexion->prepare("SELECT * FROM equipos WHERE id = :id");
    $sentenciaBuscarEquipo->bindParam(":id", $txtID);
    $sentenciaBuscarEquipo->execute();
    $equipoEncontrado = $sentenciaBuscarEquipo->fetch(PDO::FETCH_ASSOC);

    if ($equipoEncontrado!=null){
         //antes de dar de baja al equipo validamos que no este en préstamo
        $sentenciaBuscarPrestamo = $conexion->prepare("SELECT * FROM prestamoequipo WHERE modelo = :modelo && EstadoPrestamo = 'EN_CURSO';");
        $sentenciaBuscarPrestamo->bindParam(":modelo", $equipoEncontrado["numeropc"]);
        $sentenciaBuscarPrestamo->execute();

        $prestamoEncontrado = $sentenciaBuscarPrestamo->fetch(PDO::FETCH_ASSOC);

        if ($prestamoEncontrado!=null){
            $mensaje = "No se pudo realizar la acción ya que este equipo se encuentra en un préstamo en curso";
            header("Location:index.php?mensaje=" . $mensaje ."&icono=" . urlencode("warning"));
        }else{
            $sentencia = $conexion->prepare("UPDATE equipos SET Estado = 'DE_BAJA' WHERE id=:id");
            $sentencia->bindParam(":id", $txtID);
            $sentencia->execute();
            $mensaje = "Equipo dado de abaja exitosamente";
            // header("Location:index.php?mensaje=" . $mensaje);
            header("Location:index.php?mensaje=" . $mensaje ."&icono=" . urlencode("success"));
        }

      
    }


  
}

//Mostrar registros
$sentencia = $conexion->prepare("SELECT * FROM equipos ORDER BY Estado");
$sentencia->execute();
$lista_equipos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
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
<h1 class="text-center text-light fw-bold">Equipos</h1>
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
                       
                        <th scope="col" class="hidden-column">ID</th>
                        <th scope="col">Equipo</th>
                        <th scope="col">Serial PC</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Procesador</th>
                        <th scope="col">Marca</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($lista_equipos !== null && is_array($lista_equipos)) {
                        foreach ($lista_equipos as $registro) {?>
                            <!-- Modal -->
                            <div class="modal fade" id="info<?php  echo $registro["id"] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg ">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Detalles equipo: <?php echo $registro['numeropc'] ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body  card-transparent">
                                        <div class="row">
 
                                            <div class="form-group col-lg-4">
                                                <label for="" class="form-label fs-5 ">Serial cargador</label>
                                                <input type="text" class="form-control" readonly value="<?php if($registro['serialcargador']=="" || $registro["serialcargador"]==null){
                                                    echo "No hay registrado un serial de cargador para este equipo";
                                                }else{
                                                    echo $registro['serialcargador'];
                                                }?>">
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label for="" class="form-label fs-5">Placa</label>
                                                <input type="text" class="form-control" readonly value="<?php if($registro["placa"]=="" || $registro["placa"]==null  ){echo "No hay registrado una placa para este equipo";}else{echo $registro["placa"];}?>">
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label for="" class="form-label fs-5">Tipo ordenador</label>
                                                <input type="text" class="form-control" readonly value="<?php if($registro["tipo"]=="" || $registro["tipo"]==null){echo "No hay registrado un tipo de ordenador para este equipo";}else{echo $registro["tipo"];}?>">
                                            </div>

                                            <div class="form-group col-lg-4">
                                                <label for="" class="form-label fs-5">Proveedor</label>
                                                <input type="text" class="form-control" readonly value="<?php if($registro["activo"]=="" || $registro["activo"]==null){echo "No hay registrado un activo para este equipo";}else{echo $registro["activo"];}?>">
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label for="" class="form-label fs-5">Almacenamiento</label>
                                                <input type="text" class="form-control" readonly value="<?php if($registro["almacenamiento"]=="" || $registro["almacenamiento"]==null ){echo "No hay registrado un almacenamiento para este equipo";}else{echo $registro["almacenamiento"];}?>">
                                            </div>
                                            <div class="form-group col-lg-4">

                                            <?php 
                                            if($registro["fechacompra"]==null){
                                            ?>
                                                
                                                <label for="" class="form-label fs-5">Fecha Compra</label>
                                                <input type="text" class="form-control" readonly placeholder="No hay fecha de compra registrada en este equipo">
                                                
                                            <?php
                                            }else{
                                                ?>
                                                
                                                <label for="" class="form-label fs-5 ">Fecha Compra:</label>
                                                <input type="date" class="form-control" readonly value="<?php echo $registro["fechacompra"];?>">
                                                
                                            <?php
                                            }
                                            ?>
                                            </div>
                                        
                                            <div class="form-group">
                                                <label for="" class="form-label fs-5 ">Obsevaciones</label>
                                                <textarea readonly  class="form-control"> <?php if($registro['observacion']==""){
                                                    echo "No hay Observaciones en este equipo";
                                                }else{
                                                    echo $registro["observacion"];
                                                } ?></textarea>
                                            </div>
                                        </div>  
                                    </div>

                                    <div class="modal-footer">
                                    <a class="btn btn-info text-light" href="editar.php?txtID=<?php echo $registro['id']; ?>" role="button">Editar</a>
                                    <a class="btn btn-danger" href="javascript:borrar(<?php echo $registro['id']; ?>);" role="button">Eliminar</a>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <tr class="" id="<?php echo $registro["id"] ?>">
                                
                                <td scope="row" class="hidden-column"><?php echo $registro['id']; ?></td>
                                <td><?php echo $registro['numeropc']; ?></td>
                                <td><?php echo $registro['serialpc']; ?></td>                           
                                <td><?php echo $registro['tipo']; ?></td>
                                <td class="<?php echo $registro['Estado'] == 'DISPONIBLE' ? 'text-success' : 'text-danger'; ?>"><?php echo $registro['Estado']; ?></td>
                                <td><?php echo $registro['procesador']; ?></td>
                                <td><?php echo $registro['marca']; ?></td>                         
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
            console.log("holaaaaaa");
            // Muestra el modal correspondiente al hacer clic en el registro
            mostrarModal(id);
        });
    });
</script>


<?php include("../../estructura/footer.php"); ?>

<?php
if (isset($_GET['mensaje']) && isset($_GET['icono'])) { ?>
    <script>
        Swal.fire({icon: "<?php echo $_GET['icono'] ?>",title: "<?php echo $_GET['mensaje']; ?>"});
    </script>
    <?php
 }  ?>