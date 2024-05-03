<?php
include("../bd.php");

date_default_timezone_set('America/Bogota'); // Establecer la zona horaria de Bogotá, Colombia
// Obtener la fecha actual
$fecha_actual = date('Y-m-d'); // Formato: Año-Mes-Día

if (isset($_GET['txtID'])) {
    // Obtener el ID a partir de los parámetros GET
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    $sentencia = $conexion->prepare("SELECT p.*, e.procesador, e.ram, e.almacenamiento 
    FROM prestamoequipo p INNER JOIN equipos e ON p.modelo = e.numeropc 
    WHERE p.id=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();

    // Recuperación de los datos
    $registro_recuperado = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($registro_recuperado) {
        // Asignar los valores a las variables para usar en el formulario
        $identificacion = $registro_recuperado["identificacion"];
        $nombre = $registro_recuperado["nombre"];
        $dependencia = $registro_recuperado["dependencia"];
        $modelo = $registro_recuperado["modelo"];
        $serialpc = $registro_recuperado["serialpc"];
        $marca = $registro_recuperado["marca"];
        $fechaequipo = $registro_recuperado["fechaequipo"];
        $acta = $registro_recuperado["acta"];
        $estado = $registro_recuperado["EstadoPrestamo"];
        $observacion = $registro_recuperado["observacion"];

        // Obtener los campos de equipos
        $procesador = $registro_recuperado["procesador"];
        $ram = $registro_recuperado["ram"];
        $almacenamiento = $registro_recuperado["almacenamiento"];
    }
}

//PDF Del acta

ob_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Acta De Devolución</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }

        .header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo {
            height: 80px;
        }

        .title {
            text-align: center;
            font-size: 12px;
            margin-bottom: 6px;
        }

        .subtitle {
            text-align: center;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .text {
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            width: 92%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .signature-line {
            width: 46%;
            text-align: center;
            display: inline-block;
            vertical-align: top;
        }

        .note {
            font-style: italic;
            text-align: center;
        }

        .space-sign {
            border: none;
            height: 2px;
            /* Ajustar el grosor de la línea */
            background-color: #000;
            /* Color negro */
            margin: 10px 0;
        }

        .date {
            position: absolute;
            top: 0;
            right: 20px;
            /* Ajusta el espacio desde el borde derecho */
        }
    </style>
</head>

<body>
    <div class="header">
        <h2 class="title">ACTA DE INVENTARIO Y/O TRASLADO DE INFORMÁTICA</h2>
        <h4 class="subtitle">ACTA DE DEVOLUCIÓN</h4>
        <div class="date">
            <p>Fecha: <?php echo $fecha_actual; ?> </p>
        </div>
        <img src="http://localhost/InventarioTI2.0/img/logoFLA.png" alt="" class="logo" style="height: 120px; margin-top: -120px;">
    </div>

    <div class="text">
        <p>
            ME COMPROMETO A HACER BUEN USO DE LOS ACTIVOS FIJOS QUE RECIBO,
            CUIDAR DE SUS PARTES COMO (SOFTWARE, LICENCIAS Y SISTEMA OPERATIVO),
            NO RETIRAR NI BORRAR LOS ADHESIVOS QUE CONTIENEN LOS SERIALES Y LA PLACA DE
            IDENTIFICACIÓN DE LA DIRECCIÓN DE ADQUISICIONES, BIENES Y SEGUROS.
        </p>
    </div>

    <table>
        <tr>
            <th>Número Equipo</th>
            <th>Serial Pc</th>
            <th>Marca</th>
            <th>Observación</th>
        </tr>
        <tr>
            <td><?php echo $modelo; ?></td>
            <td><?php echo $registro_recuperado["serialpc"]; ?></td>
            <td><?php echo $registro_recuperado["marca"]; ?></td>
            <td><?php echo $registro_recuperado["observacion"]; ?></td>
        </tr>
    </table>

    <table>
        <tr>
            <th>Procesador</th>
            <th>Ram</th>
            <th>Almacenamiento</th>
        </tr>
        <tr>
            <td><?php echo $procesador; ?></td>
            <td><?php echo $ram; ?></td>
            <td><?php echo $almacenamiento; ?></td>
        </tr>
    </table>

    <br>
    <br>
    <br>
    <br>

    <div class="signatures">
        <div class="signature-line">
            <hr class="space-sign">
            <p><strong>Funcionario Entrega</p>
            <p><?php echo $registro_recuperado["nombre"]; ?></p>
            <p><strong>Documento</p>
            <p><?php echo $registro_recuperado["identificacion"]; ?></p>
            <p><strong>Dependencia</p>
            <p><?php echo $registro_recuperado["dependencia"]; ?></p>
        </div>

        <div class="signature-line">
            <hr class="space-sign">
            <p><strong>Funcionario Recibe</p>
            <p>JUAN JOSE YEPES POSADA</p>
            <p><strong>Documento</strong></p>
            <p>1128418787</p>
            <p><strong>Dependencia</strong></p>
            <p>INFORMÁTICA</p>
        </div>
    </div>

    <p class="note">
        NOTA: RECUERDE QUE SI VA A TRASLADAR LOS BIENES A OTRO PISO, DEPENDENCIA O FUERA DEL ÁREA,
        DEBE SER AUTORIZADO POR EL PERSONAL DE INFORMÁTICA.
    </p>
</body>

</html>



<?php
require_once("../libs/autoload.inc.php");

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$HTML = ob_get_clean();
$opciones = $dompdf->getOptions();
$opciones->set(array("isRemoteEnabled" => true));
$dompdf->setOptions($opciones);
$dompdf->loadHtml($HTML);
//$dompdf->setPaper('letter'); // Establece el papel en vertical
$dompdf->setPaper('letter','landscape');  //Establece el papel en horizontal
$dompdf->render();
$dompdf->stream("entrega_equipo.pdf", array("Attachment" => false));
?>