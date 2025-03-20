
<link href="../../assets/css/boostrap.css" rel="stylesheet">
<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>

<?php
session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "movimientos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}

include_once "includes/header.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {font-family: Arial;}

        /* Style the tab */
        .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #FFFFFF;
        }

        /* Style the buttons inside the tab */
        .tab button {
            background-color: #FFFFFF;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            font-size: 17px;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
            background-color: #FFFFFF;
        }

        /* Create an active/current tablink class */
        .tab button.active {
            background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
            background-color: #FFFFFF;
            display: none;
            padding: 6px 12px;
            border: 1px solid #FFFFFF;
            border-top: none;
        }
    </style>
</head>
<body>
<div class="card-header bg-primary text-white">
    <h2>Transferencias o movimientos</h2>
</div>
<p>Estimado usuario! Puedes registrar sus transferencias de entrada y salida de tus productos</p>
<div class="tab">
    <button class="tablinks" onclick="openCity(event, 'Entrada')">Nota de Entrada</button>
    <button class="tablinks" onclick="openCity(event, 'Salida')">Nota de Salida</button>
</div>

<div id="Entrada" class="tabcontent">
    <div class="alert alert-info" role="alert">
        <strong>Estimado usuario!</strong> Los campos remarcados con <label class="text-danger">*</label> son necesarios.
        <br>
        <strong>En esta ventana puede ingresar los productos al stock.</strong>
    </div>
             <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-lg">
                        <div class="card-header bg-primary text-white">
                           Ingreso de Productos a su kardex
                        </div>
                        <div class="card-body">
                            <form action="ningreso.php" method="post" autocomplete="off" id="formulario">
                                <?php echo isset($alert) ? $alert : ''; ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <?PHP
                                            $query_id = mysqli_query($conexion, "SELECT RIGHT(codigomov,9) as codigo FROM tmovim
                                                ORDER BY codigomov DESC LIMIT 1");
                                            $count = mysqli_num_rows($query_id);
                                            if ($count <> 0) {
                                                $data_id = mysqli_fetch_assoc($query_id);
                                                $codigo = $data_id['codigo'] + 1;
                                            } else {
                                                $codigo = 1;
                                            }
                                            $tahun          = date("Ym");
                                            $buat_id = str_pad($codigo, 9, "0", STR_PAD_LEFT);
                                            $codigo  = "TI-$tahun-$buat_id";
                                            ?>
                                            <?PHP
                                            $cidm = mysqli_query($conexion, "SELECT idmov as codigo FROM tmovim
                                                ORDER BY idmov DESC LIMIT 1");
                                            $count = mysqli_num_rows($cidm);
                                            if ($count <> 0) {
                                                $data_idm = mysqli_fetch_assoc($cidm);
                                                $cidmov = $data_idm['codigo'] + 1;
                                            } else {
                                                $cidmov = 1;
                                            }
                                            ?>
                                            <label for="codmed" class=" text-dark font-weight-bold">Cod. Transferencia<span class="text-danger">*</span></label>
                                            <input type="text" name="codigomov" id="codigomov" value="<?php echo $codigo; ?>" class="form-control" readonly>
                                            <input type="hidden" id="tt" name="tt" value="E">
                                            <input type="hidden" id="concepto" name="concepto" value="DISTRIBUCION">
                                            <input type="hidden" id="idmov" name="idmov" value="<?php echo $cidmov;?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="codmed" class=" text-dark font-weight-bold">Cod. Producto<span class="text-danger">*</span></label>
                                            <input type="text" name="codmed" id="codmed"  class="form-control" readonly>
                                            <input type="hidden" id="tt" name="tt" value="E">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="codigo" class=" text-dark font-weight-bold"><i class="fas fa-barcode"></i> Código de Barras</label>
                                            <input type="text" placeholder="código de barras" name="codigobarra" id="codigobarra" class="form-control" readonly>
                                            <input type="hidden" id="id" name="id">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="buscarproducto" class=" text-dark font-weight-bold">Producto<span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Ingrese nombre del producto" name="buscarproducto" id="buscarproducto" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="presentacion" class=" text-dark font-weight-bold">Presentación</label>
                                            <input type="text" id="prese" class="form-control" name="prese" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="cnc" class=" text-dark font-weight-bold">Concentración</label>
                                            <input type="text" id="cnc" class="form-control" name="cnc" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="ff" class=" text-dark font-weight-bold">F. Farmaceutica<span class="text-danger">*</span></label>
                                            <input type="text" id="ff" class="form-control" name="ff" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="laboratorio" class=" text-dark font-weight-bold">Laboratorio</label>
                                            <select id="laboratorio" class="form-control" name="laboratorio">
                                                <option value="">--seleccione--</option>
                                                <?php
                                                $query_lab = mysqli_query($conexion, "SELECT * FROM laboratorios");
                                                while ($datos = mysqli_fetch_assoc($query_lab)) { ?>
                                                    <option value="<?php echo $datos['id'] ?>"><?php echo $datos['laboratorio'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="lote" class=" text-dark font-weight-bold">Lote<span class="text-danger">*</span></label>
                                            <input type="text" placeholder="Ingrese lote" name="lote" id="lote" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="rs" class=" text-dark font-weight-bold">REG. SAN.<span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <select id="regsan" name="regsan" class="selectpicker" data-show-subtext="true" data-live-search="true" required>
                                                    <option value="">--seleccione--</option>
                                                    <?php
                                                    $reg = mysqli_query($conexion, "SELECT * FROM regsan");
                                                    while ($datosrs = mysqli_fetch_assoc($reg)) { ?>
                                                        <option value="<?php echo $datosrs['idreg'] ?>"><?php echo $datosrs['codigoreg']?> - <?php echo $datosrs['marca']?> </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="precio" class=" text-dark font-weight-bold">Precio<span class="text-danger">*</span></label>
                                            S/.<input type="text" placeholder="Ingrese precio" class="form-control" name="precio" id="precio" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="cantidad" class=" text-dark font-weight-bold">Cantidad<span class="text-danger">*</span></label>
                                            <input type="number" placeholder="Ingrese cantidad" class="form-control" name="cantidad" id="cantidad" required >
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="vencimiento" class=" text-dark font-weight-bold">Vencimiento<span class="text-danger">*</span></label>
                                            <input id="vencimiento" class="form-control" type="date" name="vencimiento" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="submit" value="Registrar" class="btn btn-primary" id="btnAccion">
                                        <input type="button" value="Nuevo" onclick="limpiar()" class="btn btn-success" id="btnNuevo">
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">

            </div>

</div>
<div id="Salida" class="tabcontent">
<?php include 'notasalida.php'; ?>
</div>

<script>
    function openCity(evt, cityName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }
</script>

</body>
</html>


<?php include_once "includes/footer.php"; ?>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>-->
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>

