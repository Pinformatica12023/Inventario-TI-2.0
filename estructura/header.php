<?php
// $url_base = "http://flawfws01.fla.local/InventarioTI2.0/";

// $url_base = "C:\xampp\htdocs\xampp\InventarioTi\InventarioTI2.0\InventarioTI2.0";
$url_base = "http://localhost/xampp/InventarioTi/InventarioTI2.0/InventarioTI2.0/";


// URL base de la aplicación.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location:".$url_base."login.php");
    exit();
}


?>
<!doctype html>
<html lang="es">

<head>
    <title>InventarioTI 2.0</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <!-- Agrega las referencias a jQuery y jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!--Datatables -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>

    <!-- Alertas -->
    <script src="http://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Sweetalert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Fondo sistema Web -->
    <link rel="stylesheet" href="../css/styles.css"> 

    <style>
        body {
            background-image: url('img/FLA8.jpg'); 
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>
    <link rel="stylesheet" href="styles.css">


</head>

<body>

    <header>
        <!-- place navbar here -->
    </header>
    <main>

        <nav class="navbar navbar-expand navbar-light bg-light">
            <ul class="nav navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo $url_base; ?>index.php" aria-current="page">Sistema Web<span class="visually-hidden">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href=" <?php echo $url_base; ?>secciones/prestamoequipos/">PrestamoEquipos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $url_base; ?>secciones/prestamodispositivos/">PrestamoDispositivos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $url_base; ?>secciones/equipos/">Equipos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $url_base; ?>secciones/dispositivos/">Dispositivos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $url_base; ?>secciones/docks/">Docks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $url_base; ?>secciones/usuarios/">Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $url_base; ?>cerrar.php">Cerrar sesión</a>
                </li>
            </ul>
        </nav>
        <main class="container">
<?php if (isset($_GET['mensaje'])) { ?>
    <script>
        Swal.fire({icon: "success",title: "<?php echo $_GET['mensaje']; ?>"});
    </script>
<?php } ?>