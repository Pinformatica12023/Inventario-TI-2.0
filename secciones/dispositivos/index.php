

<link rel="icon" href="../../img/logoFLA.png" type="image/png">

<?php
include("../../bd.php");







if (isset($_GET['txtID'])) {
    //Eliminar campo donde esta el id -->
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";
    // Borrado
    $sentencia = $conexion->prepare("DELETE FROM dispositivos WHERE id=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $mensaje = "Registro eliminado";
    header("Location:index.php?mensaje=" . $mensaje);
}

//Mostrar registros
$sentencia = $conexion->prepare("SELECT * FROM dispositivos ORDER BY cantidad DESC");
$sentencia->execute();
$lista_dispositivos = $sentencia->fetchAll(PDO::FETCH_ASSOC);


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

    .fila-seleccionada {
        background-color: #FFF000; /* Cambia este color al que desees */
    }
    
    
</style>


<br />
<div class="row">
<h1 class="text-center text-light fw-bold">Dispositivos</h1>
    <div class="col-lg-5">
        <div class="card card-transparent" >
            <div class="card-header">
                <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar Dispositivo</a>
                <a href="../../exportardispositivos.php" class="btn btn-success">Exportar a Excel</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="tabla_id">
                        <thead>
                            <tr >
                           
                                <th scope="col">ID</th>
                                <th scope="col">Dispositivo</th>
                                <th scope="col">Disponibles</th>
                                <th scope="col"> Prestados </th>
                                <th scope="col"> Total Dispositivos </th>
                               
                            </tr>
                        </thead>
                        <tbody >
                            <?php
                            if ($lista_dispositivos !== null && is_array($lista_dispositivos)) {
                                foreach ($lista_dispositivos as $registro) {
                            ?>
                                <tr  id="<?php echo $registro['id'] ?>  "  >
    
                                    <td scope="row" ><?php echo $registro['id']; ?></td>
                                    <td ><?php echo $registro['nombredeldispositivo']; ?></td>
                                    <td class="<?php if($registro['cantidad']<=0){?>text-danger<?php }else if($registro['cantidad']>=1 && $registro['cantidad']<=3){?> text-warning  <?php }else{?>text-success <?php } ?>" ><?php echo $registro['cantidad']; ?></td>
                                    <td> <?php  echo $registro['CantidadPrestamo']?></td>
                                    <td><?php  echo ($registro['cantidad']+$registro['CantidadPrestamo'])?></td>
                                    <!-- <td >
                                        <a class="btn btn-info" href="editar.php?txtID=<?php echo $registro['id']; ?>" role="button">Editar</a>
                                        |<a class="btn btn-danger" href="javascript:borrar(<?php echo $registro['id']; ?>);" role="button">Eliminar</a>
                                    </td> -->
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
    <div class="col-lg-7">
        <div id="PrestamosRelacionados" class="mt-2" style="display: none;" >
            <div class="card card-transparent">
                <div class="card-header fs-4 fw-bold" id="tituloTabla">
                  
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tabla_id_prestamos">
                            <thead>
                                <tr>
                                    <!-- <th scope="col">Acciones</th> -->
                                    <th style="width: 220px;" scope="col">Actas</th>
                                    <th scope="col">Acta</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Dependencia</th>
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
    
   
</div>
<script>


    $(document).ready(function() {
        $("tr").click(function() {
              // Agrega la clase al tr seleccionado
            $(this).removeClass('odd');
            $(this).removeClass('even');
            $(this).addClass('fila-seleccionada');
            
            // $(this).addClass("fila-seleccionada");
            console.log("dimos click");

            // Obtiene el id del registro del atributo id del <tr>
            var id = $(this).attr("id");
            console.log("vamos a buscar los registros de este id: "+id);
            // Remueve la clase de cualquier fila previamente seleccionada

            // Muestra el modal correspondiente al hacer clic en el registro
           obtenerYProcesarPrestamos(id);
        });
    });

    async function obtenerYProcesarPrestamos(idDispositivo) {
    try {
        const prestamosRelacionados = await fetchPrestamosRelacionados(idDispositivo);
         // Mostrar/ocultar el div en función de la clase 'fila-activa'
        const divPrestamosRelacionados = document.getElementById('PrestamosRelacionados');
        const headerCard = document.getElementById('tituloTabla');
        headerCard.innerHTML  = '';
        const titulo = document.createElement('p');
      
         // Actualizar la tabla con información de préstamos relacionados
        const tablaPrestamos = document.getElementById('tabla_id_prestamos');
        const tablaBody = tablaPrestamos.querySelector('tbody');
        tablaBody.innerHTML = ''; // Limpiar el contenido existente


        
            const prestamosRelacionadosObjeto = JSON.parse(prestamosRelacionados);
            // const nombreDispositivo  = prestamosRelacionadosObjeto["dispositivo_encontradlo"]["0"]["nombredeldispositivo"]
            // titulo.innerHTML = `Historial Préstamo dispositivos ${nombreDispositivo}`;
            console.log(typeof prestamosRelacionadosObjeto); // Debería ser "object"

            console.log(prestamosRelacionadosObjeto);
            // console.log(prestamosRelacionadosObjeto.dispositivo_encontrado.nombredeldispositivo);
            const dispositivo = prestamosRelacionadosObjeto["dispositivo_encontrado"]["0"]["nombredeldispositivo"];
            console.log(dispositivo);
            titulo.innerHTML = `Historial Préstamo dispositivos ${dispositivo}`;
            headerCard.appendChild(titulo);


            if(prestamosRelacionadosObjeto["lista_prestamoDispositivo"].length>0){
                console.log("mi so si hay datos");
                prestamosRelacionadosObjeto["lista_prestamoDispositivo"].forEach((prestamo) => {
                const filaPrestamo = document.createElement('tr');
                filaPrestamo.innerHTML = `
                <td>
                    <a class="btn btn-dark" href="../../acta_entrega_devolucion/actaentregadispositivo.php?txtID=${prestamo.id}" role="button" target="_blank">Entrega</a>
                    <a class="btn btn-warning" href="../../acta_entrega_devolucion/actadevoluciondispositivo.php?txtID=${prestamo.id}" role="button" target="_blank">Devolución</a>
                </td>
                <td >
                    ${prestamo.acta !== '' && prestamo.acta !== null ? `<a target="_blank" href="../../secciones/actas/${prestamo.acta}">${prestamo.acta}</a>` : `<span style="color: red; font-weight: bold;">No hay actas disponibles</span>`}
                </td>
                <td>${prestamo.nombreusuario}</td>
                <td>${prestamo.nombredependencia}</td>
                <td>${prestamo.Estado_Prestamo}</td>
               
                `;
                tablaBody.appendChild(filaPrestamo);
                });
                divPrestamosRelacionados.style.display = 'block'; // Mostrar el div
            }else{
                console.log("mi so no hay datos");
               
                const filaVacia = document.createElement('tr');
                filaVacia.innerHTML = '<td colspan="7">No se encontraron préstamos relacionados</td>';
                tablaBody.appendChild(filaVacia);
                divPrestamosRelacionados.style.display = 'block'; // Mostrar el div
            }

        // }else{

        //     console.log("despues corregimos esta expecion");
        //     console.log("no hay prestamos relacionados");
        //         const filaVacia = document.createElement('tr');
        //             filaVacia.innerHTML = '<td colspan="7">No se encontraron préstamos relacionados</td>';
        //         tablaBody.appendChild(filaVacia);
        //         divPrestamosRelacionados.style.display = 'block'; // Ocultar el div
        // }
        // Convertir la cadena JSON a un objeto JavaScript
       
        // Verificar el tipo de dato después de la conversión
      
    } catch (error) {
        // Manejar errores aquí
        console.error('Error en la obtención de préstamos relacionados:', error);
    }
}


    async function fetchPrestamosRelacionados(idDispositivo) {
        console.log("vamos hacer la funcion php");
        let url = '../../Controllers/dispositivos.php?idDispositivo=' + idDispositivo;
        try {
            const response = await $.ajax({
                url: url,
                type: 'GET',
                // data: { idDispositivo: idDispositivo },
                // dataType: 'json',
            });
            console.log("esto nos devolvio la funcion php"+response); // Procesar el resultado de la función PHP

            console.log("vamos a mandarlos");
            return response; // Retornar la respuesta si es necesario
        } catch (error) {
            console.error('Error:', error);
            throw error; // Lanzar el error para que se maneje fuera de la función
        }
    }





</script>

<?php include("../../estructura/footer.php"); ?>



