<?php
session_start();
include "../conexion.php";

if (!empty($_POST)) {
    $alert = "";
    $ids = $_POST['ids'];
    $codigomovs = $_POST['codigomovs'];
    $conceptos = $_POST['conceptos'];
    $codmeds = $_POST['codmeds'];
    date_default_timezone_set('America/Lima');
    $fecha_actuals = date("Y-m-d H:i:s");
    $tipomovs = $_POST['tts'];
    $codigobarras = $_POST['codigobarras'];
    $prosalida = $_POST['prosalida'];
    $lotes = $_POST['lotes'];
    $regsans = $_POST['regsans'];
    $precios = $_POST['precios'];
    $nmaximo = $_POST['stocks'];
    $nminimo = $_POST['cantidads'];
    $vencimiento = $_POST['vencs'];
    $nstock =$_POST['ts'];
    $idmovs = $_POST['idmovs'];

    if (!empty($_POST['accion'])) {
        $vencimiento = $_POST['vencs'];
    }
    if (empty($prosalida)  || empty($precios) || $precios <  0 || empty($nminimo) || $nminimo <  0) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todo los campos son obligatorios
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    } else {
        if($nminimo< $nmaximo){
            $query_insert = mysqli_query($conexion, "INSERT INTO tmovim(codigomov, concepto, fecha,cod_med,tipo,salida) values ( '$codigomovs','$conceptos','$fecha_actuals', '$codmeds', '$tipomovs', '$nstock')");
            if ($query_insert) {
                $query_insert2 = mysqli_query($conexion, "UPDATE mstockalm SET stock='$nstock', idmovis= '$idmovs' where idstock ='$ids'");
                if ($query_insert2){
                    //$alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    //      Registrado correctamente!!
                    //    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    //      <span aria-hidden="true">&times;</span>
                    //</button>
                    // </div>';
                    echo "<script type='text/javascript'>alert('Registrado correctamente!');</script>";
                    echo "<script>window.location.href ='transferencia.php'</script>";
                }
            }
        }
        else {
          //  $alert = '<div class="alert alert-danger" role="alert">
            //        Error al registrar el producto
              //    </div>';
            echo "<script type='text/javascript'>alert('Error: La cantidad a salir no debe ser mayor al stock actual!');</script>";
            echo "<script>window.location.href ='transferencia.php'</script>";
        }
    }
}
?>