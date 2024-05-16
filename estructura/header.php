<?php
// $url_base = "http://flawfws01.flaeice.local/InventarioTI2.0/";

// $url_base = "C:\xampp\htdocs\xampp\InventarioTi\InventarioTI2.0\InventarioTI2.0";
$url_base = "http://localhost/InventarioTI2.0/";


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

    <!--select2 (buscador en select) -->

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    

    <style>
        /* body {
            background-image: url('img/FLA15.jpg'); 
            background-size: cover;
            background-repeat: no-repeat;
        } */

        
        .card-transparent {
            background-color: rgba(255, 255, 255, 0.8);
            /* Cambia los valores RGB y el valor alfa (0.7) según tus preferencias */
            /* background-color: #ffffff;
            opacity: 0.5; */
        }
    
        

        .nav-link.active {
            background-color: #cccccc; /* Cambia el color de fondo */
            color: #ffffff;
            border-radius: 20px;
            
        }

    </style>
   


</head>

<body>
   

    <header>
        <!-- place navbar here -->
    </header>
    <main>

        <nav class="navbar navbar-expand-lg   p-3">
            <div class="container-fluid card-transparent">
                <a class="navbar-brand text-dark fw-bold" href="#">InventarioTI</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
            
                <div class=" collapse navbar-collapse  " id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto ">
                    <li class="nav-item">
                    <a class="nav-link active text-dark fw-bold" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link mx-2 text-dark fw-bold" href="<?php echo $url_base; ?>secciones/equipos/" data-tab="Equipos">Equipos</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link mx-2 text-dark fw-bold" href="<?php echo $url_base; ?>secciones/dispositivos/" data-tab="Dispositivos">Dispositivos</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link mx-2 text-dark fw-bold" href="<?php echo $url_base; ?>secciones/docks/" data-tab="Docks">Docks</a>
                    </li>
                    <li class="nav-item dropdown">
                    <a class="nav-link mx-2 dropdown-toggle text-dark fw-bold" href="#" id="navbarDropdownMenuLink" data-tab="Prestamos" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Préstamos
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item text-dark fw-bold" href=" <?php echo $url_base; ?>secciones/prestamoequipos/">Préstamos Equipos</a></li>
                        <li><a class="dropdown-item text-dark fw-bold" href="<?php echo $url_base; ?>secciones/prestamodispositivos/">Préstamos Dispositivos</a></li>
                    </ul>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link mx-2 text-dark fw-bold" href="<?php echo $url_base; ?>secciones/usuarios/" data-tab="Usuarios">Usuarios</a>
                    </li>
                
                </ul>
                <ul class="navbar-nav ms-auto d-none d-lg-inline-flex">
                    <ul class="text-end text-dark fw-bold">
                        <a href="<?php echo $url_base; ?>cerrar.php">
                        <svg xmlns="http://www.w3.org/2000/svg"  width="25" height="25" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                            <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                        </svg>
                        </a>
                    </ul>
                </ul>
                </div>
            </div>
        </nav>

        <!-- <nav class="navbar navbar-expand  bg-light">
            <ul class="nav navbar-nav card-transparent">
                <li class="nav-item">
                    <a  class="nav-link active" href="<?php echo $url_base; ?>index.php" data-tab="Inicio" aria-current="page">Home<span class="visually-hidden">(current)</span> </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href=" <?php echo $url_base; ?>secciones/prestamoequipos/" data-tab="PrestamoEquipos">PrestamoEquipos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $url_base; ?>secciones/prestamodispositivos/" data-tab="PrestamoDispositivos">PrestamoDispositivos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $url_base; ?>secciones/equipos/" data-tab="Equipos">Equipos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $url_base; ?>secciones/dispositivos/" data-tab="Dispositivos">Dispositivos</a>
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
            <ul class="text-end">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                </svg>
            </ul>
        </nav> -->
    <main class="container-fluid p-4 ">
     
<?php if (isset($_GET['mensaje'])) { ?>
    <script>
        Swal.fire({icon: "success",title: "<?php echo $_GET['mensaje']; ?>"});
    </script>
<?php } ?>

