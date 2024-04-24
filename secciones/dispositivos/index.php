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
$sentencia = $conexion->prepare("SELECT * FROM dispositivos");
$sentencia->execute();
$lista_dispositivos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
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
    
</style>


<br />
<h1 class="fw-bold text-center">Dispositivos</h1>
<div class="card card-transparent">
    <div class="card-header">
        <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar Dispositivo</a>
        <a href="../../exportardispositivos.php" class="btn btn-success">Exportar a Excel</a>
    </div>
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered" id="tabla_id">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Dispositivo</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($lista_dispositivos !== null && is_array($lista_dispositivos)) {
                        foreach ($lista_dispositivos as $registro) {
                    ?>
                            <tr class="">
                                <td scope="row"><?php echo $registro['id']; ?></td>
                                <td><?php echo $registro['nombredeldispositivo']; ?></td>
                                <td><?php echo $registro['cantidad']; ?></td>
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