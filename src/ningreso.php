<?php
session_start();
include "../conexion.php";


if (!empty($_POST)) {
    $alert = "";
    //$id = $_POST['id'];
    $codigomov = $_POST['codigomov'];
    $concepto = $_POST['concepto'];
    $codmed = $_POST['codmed'];
    date_default_timezone_set('America/Lima');
    $fecha_actual = date("Y-m-d H:i:s");
    $tipomov = $_POST['tt'];
    $entrada = $_POST['cantidad'];

    $codigobarra = $_POST['codigobarra'];
    $producto = $_POST['buscarproducto'];
    $lote = $_POST['lote'];
    $regsan = $_POST['regsan'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $ff = $_POST['ff'];
    //$presentacion = $_POST['presentacion'];
    $laboratorio = $_POST['laboratorio'];
    $vencimiento = $_POST['vencimiento'];
    $idmov = $_POST['idmov'];
    if (!empty($_POST['accion'])) {
        $vencimiento = $_POST['vencimiento'];
    }
    if (empty($producto)  || empty($precio) || $precio <  0 || empty($cantidad) || $cantidad <  0) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todo los campos son obligatorios
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    } else {
        $query_insert = mysqli_query($conexion, "INSERT INTO tmovim(codigomov, concepto, fecha,cod_med,tipo,entrada) values ( '$codigomov','$concepto','$fecha_actual', '$codmed', '$tipomov', '$entrada')");
        if ($query_insert) {
            $query_insert2 = mysqli_query($conexion, "INSERT INTO mstockalm(codmed,codigobarra, med,lote,idregsan,precio,vencimiento,stock, idmov) values ('$codmed','$codigobarra' ,'$producto', '$lote', '$regsan', '$precio','$vencimiento', '$cantidad', '$idmov')");
            if ($query_insert2){
                //$alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  //      Registrado correctamente!!
                    //    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      //      <span aria-hidden="true">&times;</span>
                        //</button>
                   // </div>';
                echo "<script type='text/javascript'>alert('Proucto ingresado correctamente!');</script>";
                echo "<script>window.location.href ='transferencia.php'</script>";
            }

        } else {
            $alert = '<div class="alert alert-danger" role="alert">
                    Error al registrar el producto
                  </div>';
        }


    }
}

?>