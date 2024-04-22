
<link rel="icon" href="./img/logoFLA.png" type="image/png">
<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include("bd.php");

    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];

    try {
        $sentencia = $conexion->prepare("SELECT usuario, contrasena, count(*) as n_usuario
        FROM credenciales WHERE usuario=:usuario AND contrasena=:contrasena GROUP BY usuario, contrasena"); 
        
        $sentencia->bindParam(":usuario", $usuario);
        $sentencia->bindParam(":contrasena", $contrasena);
        $sentencia->execute();

        $registro = $sentencia->fetch(PDO::FETCH_ASSOC);

        if ($registro && $registro["n_usuario"] > 0) {
            $_SESSION['usuario'] = $registro["usuario"];
            $_SESSION['logueado'] = true;
            // Redirigir al usuario a la página principal (index.php)
            header("Location: index.php");
            exit; // Importante terminar la ejecución después de redirigir
        } else {
            $mensaje = "Error: El usuario o contraseña son incorrectos";
        }
    } catch (PDOException $e) {
        // Manejo de excepciones de la base de datos
        $mensaje = "Error en la base de datos: " . $e->getMessage();
    }
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


    <style>
        /*Darle diseño gris transparente al login */
        .login-card {
            background-color: rgba(255, 255, 255, 0.7); /*Fondo semi-transparente */
            padding: 20px; /*Añade espacio para que el contenido sea visible */
        }
    </style>
    <link rel="stylesheet" href="styles.css">

    <!-- Icono FLA -->
    <style>
    .bg-transparent-gray {
        background-color: rgba(169, 169, 169, 0.7);
    }
    </style>

    <style>
    body {
        overflow: hidden; /* Oculta los desbordamientos del video */
    }

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

    .login-card {
        background-color: rgba(255, 255, 255, 0.7);
        padding: 20px;
        position: relative;
        z-index: 1;
    }
</style>



</head>

<body>
    <!--Video fondo FLA -->
    <video autoplay muted loop id="video-background">
        <source src="img/video/ViajeDeLaTransformación.mp4" type="video/mp4">
    </video>
    

    <header>
        <!-- place navbar here -->
    </header>
    <main class="container">

        <div class="row">
            <div class="col-md-4">

            </div>
            <div class="col-md-4">
                <br>
                <br>
                <div class="card login-card"> <!--Aqui va el cuadro de login -->
                    <div class="card-header">
                        Login
                    </div>
                    <div class="card-body">

                        <?php if (isset($mensaje)) { ?>
                            <div class="alert alert-danger" role="alert">
                                <strong><?php echo $mensaje; ?></strong>
                            </div>
                        <?php } ?>

                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario:</label>
                                <input type="text" class="form-control" name="usuario" id="usuario" placeholder="">
                            </div>

                            <div class="mb-3">
                                <label for="contrasena" class="form-label">Contraseña:</label>
                                <input type="password" class="form-control" name="contrasena" id="contrasena" placeholder="">
                            </div>
                            <button type="submit" class="btn btn-primary">Iniciar sesión</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <footer>
        <!-- place footer here -->
    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
    </script>
</body>

</html>