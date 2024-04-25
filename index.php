<style>
    .bg-transparent-gray {
        background-color: rgba(169, 169, 169, 0.7);
    }
</style>

<link rel="icon" href="./img/logoFLA.png" type="image/png">


<?php include("estructura/header.php"); ?>
<br>

<?php

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    // Redirige al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: login.php");
    exit();
}
?>

<div class="mb-4 ">
    <div class="container-fluid ">
        <h1 class=" fw-bold">Bienvenido al sistema</h1>
        <!-- <p class="col-md-8 fs-4">Usuario: <?php echo $_SESSION['usuario']; ?></p>
        <p class="col-md-8 fs-4">Sistema de inventario de equipos TI</p>
        <p class="col-md-8 fs-4">Área De Informática</p>
            <a href="secciones/prestamoequipos/index.php">
                <button class="btn btn-primary btn-lg" type="button">Continuar</button>
            </a> -->
    </div>
</div>

<?php include("estructura/footer.php"); ?>
