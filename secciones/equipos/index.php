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

function obtenerEquipos(){
    include("../../bd.php");
//Mostrar registros
$sentencia = $conexion->prepare("SELECT * FROM equipos ORDER BY Estado");
$sentencia->execute();
return $sentencia->fetchAll(PDO::FETCH_ASSOC);
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
<div class="row">
    <div class="col-lg-4">
        <div class="card card-transparent ">
            <div class="card-header">
                <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar Equipos</a>
                <a href="../../exportarequipos.php" class="btn btn-success">Exportar a Excel</a>
            
            
            
            </div>
            <div class="card-body " >

                <div class="table-responsive">
                    <table class="table table-bordered" id="tabla_id">
                        <thead>
                            <tr>
                            
                                <th scope="col" class="hidden-column">ID</th>
                                <th scope="col">Equipo</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Estado</th>


                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                            if (obtenerEquipos() !== null && is_array(obtenerEquipos())) {
                                foreach (obtenerEquipos() as $registro) {?>
                                    <!-- Modal -->
                                    <div class="modal fade" id="info<?php  echo $registro["id"] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl ">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Detalles equipo: <?php echo $registro['numeropc'] ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body  card-transparent">
                                                    <div class="row mb-2">
            
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
                                                    <a class="btn btn-info text-light" href="editar.php?txtID=<?php echo $registro['id']; ?>" role="button">Editar</a>
                                                    <a class="btn btn-danger" href="javascript:borrar(<?php echo $registro['id']; ?>);" role="button">Eliminar</a>  
                                                </div>
                                                

                                                
                                                
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <tr id="<?php echo $registro["numeropc"] ?>">
                                        
                                        <td scope="row" class="hidden-column"><?php echo $registro['id']; ?></td>
                                        <td  ><?php echo $registro['numeropc']; ?></td>
                                                            
                                        <td ><?php echo $registro['tipo']; ?></td>
                                        <td  class="<?php echo $registro['Estado'] == 'DISPONIBLE' ? 'text-success' : 'text-danger'; ?>"><?php echo $registro['Estado']; ?></td>
                                                            
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
    <div class="col-lg-8"> 
        <div class="row">
            <div id="PrestamosRelacionados" class="mt-2" style="display: none;" >
                <div class="card card-transparent">
                    <div class="card-header fs-4 fw-bold" id="tituloTabla">
                    
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="tabla_id_prestamos"   >
                                <thead>
                                    <tr>
                                        <!-- <th scope="col">Acciones</th> -->
                                        <th style="width: 220px;" scope="col">Actas</th>
                                        <th scope="col">Identificacion</th>
                                        <th scope="col">Usuario</th>
                                        <th scope="col">Dependencia</th>
                                       
                                        <th scope="col">Acta</th>
                                        <th scope="col">Estado</th> 
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div id="informacionEquipo"  style="display: none;">
                <div class="card card-transparent">
                    <div class="card-header fs-4 fw-bold"  id="tituloEquipo">
                        
                    </div>
                    <div class="card-body" >
                        <div class="row mb-2">

                            <div class="form-group col-lg-4">
                                <label for="" class="form-label fs-5 ">Serial equipo</label>
                                <input type="text" id="serialequipo" class="form-control" readonly >
                            </div>


                            <div id="divSerialCargador" class="form-group col-lg-4">
                                <label for="" class="form-label fs-5 ">Serial cargador</label>
                                <input type="text" id="serialCargador" class="form-control" readonly >
                            </div>

                            <div class="form-group col-lg-4">
                                <label for="" class="form-label fs-5">Placa</label>
                                <input type="text" id="placaequipo" class="form-control" readonly >
                            </div>

                            <div class="form-group col-lg-4">
                                <label for="" class="form-label fs-5">Tipo ordenador</label>
                                <input type="text" id="tipoequipo" class="form-control" readonly >
                            </div>

                            <div class="form-group col-lg-4">
                                <label for="" class="form-label fs-5">Marca</label>
                                <input type="text" id="marcaequipo" class="form-control" readonly >
                            </div>

                            <div class="form-group col-lg-4">
                                <label for="" class="form-label fs-5">Proveedor</label>
                                <input type="text" id="proveedorequipo" class="form-control" readonly >
                            </div>

                            <div class="form-group col-lg-4">
                                <label for="" class="form-label fs-5">Procesador</label>
                                <input type="text" id="procesadorequipo" class="form-control" readonly >
                            </div>

                            <div class="form-group col-lg-4">
                                <label for="" class="form-label fs-5">Almacenamiento</label>
                                <input type="text" id="almacenamientoequipo" class="form-control" readonly >
                            </div>

                            <div class="form-group col-lg-4">
                                <label for="" class="form-label fs-5">Fecha Compra</label>
                                <input type="text" id="fechacompraequipo" class="form-control" readonly placeholder="No hay fecha de compra registrada en este equipo">
                            </div>
                        
                            <div class="form-group">
                                <label for="" class="form-label fs-5 ">Obsevaciones</label>
                                <textarea readonly id="observacionequipo" class="form-control">hola</textarea>
                            </div>
                        </div>
                        <div id="footerCard"></div>
                        <!-- <a class="btn btn-info text-light" href="editar.php?txtID=<?php echo $registro['id']; ?>" role="button">Editar</a>
                        <a class="btn btn-danger" href="javascript:borrar(<?php echo $registro['id']; ?>);" role="button">Eliminar</a>   -->
   
                    </div>
                </div>
            </div>
           
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
    // $(document).ready(function() {
    //     $("tr").click(function() {
    //         // Obtiene el id del registro del atributo id del <tr>
    //         var id = $(this).attr("id");
    //         console.log("holaaaaaa");
    //         // Muestra el modal correspondiente al hacer clic en el registro
    //         mostrarModal(id);
    //     });
    // });

    $(document).ready(function() {
        $("tr").click(function() {
            var id = $(this).attr("id");
            console.log("vamos a buscar los registros de numero de pc: "+id);
           obtenerYProcesarPrestamos(id);
           obtenerYProcesarInformacionEquipo(id);
        });
    });

    async function obtenerYProcesarInformacionEquipo(idEquipo){

        try {

            const divInfo = document.getElementById("informacionEquipo");
            divInfo.style.display = 'none';

            const headerCard = document.getElementById('tituloEquipo');
            headerCard.innerHTML  = '';
            const titulo = document.createElement('p');

            const informacionEquipo = await fetchPrestamosRelacionados(idEquipo);
            const informacionEquipoObjeto =JSON.parse(informacionEquipo);

            const equipo = informacionEquipoObjeto["equipo_encontrado"][0]["numeropc"];
            titulo.innerHTML = `Informacion equipo ${equipo}`;
            headerCard.appendChild(titulo);

            const divFooter = document.getElementById("footerCard");
            divFooter.innerHTML  = "";

            // Cree los botones "Editar" y "Eliminar"
            const botonEditar = document.createElement('a');
            botonEditar.classList.add('btn', 'btn-info', 'text-light');
            botonEditar.setAttribute('href', 'editar.php?txtID=' + informacionEquipoObjeto["equipo_encontrado"][0]["id"]);
            botonEditar.setAttribute('role', 'button');
            botonEditar.textContent = 'Editar';

            const botonEliminar = document.createElement('a');
            botonEliminar.classList.add('btn', 'btn-danger');
            botonEliminar.setAttribute('href', 'javascript:borrar(' + informacionEquipoObjeto["equipo_encontrado"][0]["id"] + ');');
            botonEliminar.setAttribute('role', 'button');
            botonEliminar.textContent = 'Eliminar';

           
            divFooter.appendChild(botonEditar);
            divFooter.appendChild(botonEliminar);

            // // Agregue los botones al DOM
            // divInfo.appendChild(botonEditar);
            // divInfo.appendChild(botonEliminar);



            if(informacionEquipoObjeto["equipo_encontrado"].length>0){
                console.log("aca va la informacion del equipo");
                tipo = informacionEquipoObjeto["equipo_encontrado"][0]["tipo"];
                placa = informacionEquipoObjeto["equipo_encontrado"][0]["placa"];
                serialCargador = informacionEquipoObjeto["equipo_encontrado"][0]["serialCargador"];
                proveedor = informacionEquipoObjeto["equipo_encontrado"][0]["activo"];
                almacenamiento  = informacionEquipoObjeto["equipo_encontrado"][0]["almacenamiento"];
                fechacompra = informacionEquipoObjeto["equipo_encontrado"][0]["fechacompra"];
                marca = informacionEquipoObjeto["equipo_encontrado"][0]["marca"];
                procesador = informacionEquipoObjeto["equipo_encontrado"][0]["procesador"];
                serialEquipo = informacionEquipoObjeto["equipo_encontrado"][0]["serialpc"];
                observacionEquipo = informacionEquipoObjeto["equipo_encontrado"][0]["observacion"];
                if (tipo) {
                    document.getElementById("tipoequipo").value = tipo;
                } else {
                    document.getElementById("tipoequipo").value = "No hay información";
                }
                if(placa){
                    document.getElementById("placaequipo").value = placa;
                }else{
                    document.getElementById("placaequipo").value = "No hay informacion"
                }
                if(serialCargador){
                    document.getElementById("serialCargador").value = serialCargador;
                }else{
                    document.getElementById("serialCargador").value = "No hay informacion";
                }
                if(proveedor){
                    document.getElementById("proveedorequipo").value  = proveedor;
                }else{
                    document.getElementById("proveedorequipo").value  ="No hay informacion";
                }
                if(procesador){
                    document.getElementById("procesadorequipo").value = procesador;
                }else{
                    document.getElementById("procesadorequipo").value = "No hay informacion";
                }
                if(almacenamiento){
                    document.getElementById("almacenamientoequipo").value = almacenamiento;
                }else{
                    document.getElementById("almacenamientoequipo").value = "No hay información";
                }
                if(fechacompra){
                    document.getElementById("fechacompraequipo").value = fechacompra;
                }else{
                    document.getElementById("fechacompraequipo").value = "No hay informacion";
                }
                if (marca) {
                    document.getElementById("marcaequipo").value  = marca;
                } else {
                    document.getElementById("marcaequipo").value = "No hay información";
                }
                if(serialEquipo){
                    document.getElementById("serialequipo").value = serialEquipo;
                }else{
                    document.getElementById("serialequipo").value = "No hay informacion";
                }
                if(observacionEquipo){
                    document.getElementById("observacionequipo").value = observacionEquipo;
                }else{
                    document.getElementById("observacionequipo").value = "No hay informacion";
                }

                if(tipo=="ESCRITORIO"){
                    document.getElementById("divSerialCargador").style.display = 'none';
                }else{
                    document.getElementById("divSerialCargador").style.display = 'block';
                }
                divInfo.style.display = 'block';
                
            }else{
                console.log("no esta encontrando ningun equipo");
            }

            // <a class="btn btn-info text-light" href="editar.php?txtID=<?php echo $registro['id']; ?>" role="button">Editar</a>
            // <a class="btn btn-danger" href="javascript:borrar(<?php echo $registro['id']; ?>);" role="button">Eliminar</a>  

            
        } catch (error) {
            console.error(error);
        }

    }


    async function obtenerYProcesarPrestamos(idEquipo){
        try {

            const prestamosRelacionados = await fetchPrestamosRelacionados(idEquipo);
            const divPrestamosRelacionados = document.getElementById('PrestamosRelacionados');
            const headerCard = document.getElementById('tituloTabla');
            headerCard.innerHTML  = '';
            const titulo = document.createElement('p');

            const tablaPrestamos = document.getElementById('tabla_id_prestamos');
            const tablaBody = tablaPrestamos.querySelector('tbody');
            tablaBody.innerHTML = ''; // Limpiar el contenido existente

            const prestamosRelacionadosObjeto = JSON.parse(prestamosRelacionados);
            // const nombreDispositivo  = prestamosRelacionadosObjeto["dispositivo_encontradlo"]["0"]["nombredeldispositivo"]
            // titulo.innerHTML = `Historial Préstamo dispositivos ${nombreDispositivo}`;
            console.log(prestamosRelacionadosObjeto);
            const equipo = prestamosRelacionadosObjeto["equipo_encontrado"]["0"]["numeropc"];


            titulo.innerHTML = `Historial Préstamo Equipo ${equipo}`;
            headerCard.appendChild(titulo);

            if(prestamosRelacionadosObjeto["lista_prestamoEquipos"].length>0){
                console.log("mi so si hay datos");
                
                prestamosRelacionadosObjeto["lista_prestamoEquipos"].forEach((prestamo) => {
                    console.log(prestamo.identificacion);

                    const filaPrestamo = document.createElement('tr');
                   const tdEstadoPrestamo = document.createElement('td');
                    const estadoPrestamo = prestamo.EstadoPrestamo;

                    if (estadoPrestamo === 'EN_CURSO') {
                        tdEstadoPrestamo.classList.add('text-success');
                    } else{
                        tdEstadoPrestamo.classList.add('text-danger');
                    }
                    tdEstadoPrestamo.textContent =estadoPrestamo;
                    
                    filaPrestamo.innerHTML = `
                    <td>
                        <a class="btn btn-dark" href="../../acta_entrega_devolucion/actaentregadispositivo.php?txtID=${prestamo.id}" role="button" target="_blank">Entrega</a>
                        <a class="btn btn-warning" href="../../acta_entrega_devolucion/actadevoluciondispositivo.php?txtID=${prestamo.id}" role="button" target="_blank">Devolución</a>
                    </td>
                    <td>${prestamo.identificacion}</td>
                    <td>${prestamo.nombre}</td>
                    <td>${prestamo.dependencia}</td>
                    
                    <td >
                        ${prestamo.acta !== '' && prestamo.acta !== null ? `<a target="_blank" href="../../secciones/actas/${prestamo.acta}">${prestamo.acta}</a>` : `<span style="color: red; font-weight: bold;">No hay actas disponibles</span>`}
                    </td>
                
                    `;
                    filaPrestamo.appendChild(tdEstadoPrestamo);
                    tablaBody.appendChild(filaPrestamo);
                });
                divPrestamosRelacionados.style.display = 'block'; // Mostrar el div
            }else{
                console.log("no hay datos");
                const filaVacia = document.createElement('tr');
                filaVacia.innerHTML = '<td colspan="7">No se encontraron préstamos relacionados</td>';
                tablaBody.appendChild(filaVacia);
                divPrestamosRelacionados.style.display = 'block'; // Mostrar el div

            }

        } catch (error) {
            
        }

    }

    async function fetchPrestamosRelacionados(idEquipo) {
        
        let url = '../../Controllers/equipos.php?idEquipo=' + idEquipo;
        try {
            const response = await $.ajax({
                url: url,
                type: 'GET',
                // data: { idDispositivo: idDispositivo },
                // dataType: 'json',
            });
            console.log("esto nos devolvio la funcion php"+response); // Procesar el resultado de la función PHP

          
            return response; // Retornar la respuesta si es necesario
        } catch (error) {
            console.error('Error:', error);
            throw error; // Lanzar el error para que se maneje fuera de la función
        }
    }

    $(document).ready(function() {
        $("#tabla_id_prestamos").DataTable({
            "order": [
                [4, 'desc']
            ], // Ordena la primera columna (ID) de manera descendente
           
            "pageLength": 5,
            "lengthMenu": [
                [5, 10],
                [5, 10]
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json"
            }
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