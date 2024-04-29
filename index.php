

<?php include("estructura/header.php"); ?>

<style>
        #video-background {
        position: fixed;
        right: 0;
        bottom: 0;
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        z-index: -1;
    }
</style>
<!-- <link rel="icon" href="img/video/video1.mp4" type="image/png"> -->
<video autoplay muted loop id="video-background">
    <source src="img/video/video1.mp4" type="video/mp4">
</video>

<br>


<?php

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    // Redirige al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: login.php");
    exit();
}
?>

<!--  -->

<?php include("estructura/footer.php"); ?>
