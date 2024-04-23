<?php

// $server = "localhost";
// $bd = "inventarioti";
// $user = "root";
// $password = "Ti2023*";

$server = "127.0.0.1";
$bd = "inventariolocal";
$user = "root";
$password = "";


try{
    $conexion = new PDO("mysql:host=$server;dbname=$bd",$user,$password);
}catch(PDOException $error){
    echo $error->getMessage();
}

$sentencia = $conexion->prepare("SELECT * FROM prestamoequipo ORDER BY id DESC");
$sentencia->execute();
?>

</main>
<footer>
    <!-- place footer here -->
</footer>
<!-- Bootstrap JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
</script>

<!--script de borrado -->
<script>
    function borrar(id) {
        Swal.fire({
            title: '¿Quiere eliminar el registro?',
            showCancelButton: true,
            confirmButtonText: 'Si, Borrar',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = "index.php?txtID=" + id;
            }
        })

    }


    function finalizarPrestamo(id) {
        Swal.fire({
            title: '¿Seguro que desea Finalizar el préstamo?',
            showCancelButton: true,
            confirmButtonText: 'Si, Finalizar',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = "index.php?ChangeStatusID=" + id;
            }
        })

    }


</script>

<!--llamar tabla -->
<script>
    $(document).ready(function() {
        $("#tabla_id").DataTable({
            "order": [
                [0, 'desc']
            ], // Ordena la primera columna (ID) de manera descendente
            "responsive": false, // Deshabilita la funcionalidad responsive
            "pageLength": 5,
            "lengthMenu": [
                [5, 10, 25, 50],
                [5, 10, 25, 50]
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json"
            }
        });
    });
</script>

</body>

</html>