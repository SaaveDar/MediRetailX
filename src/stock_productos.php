
<link href="../../assets/css/boostrap.css" rel="stylesheet">
<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>

<?php
session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "stock_Productos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
if (!empty($_POST)) {
    $alert = "";
    $id = $_POST['id'];
    $codmed = $_POST['codmed'];
    $codigobarra = $_POST['codigobarra'];
    $producto = $_POST['producto'];
    $lote = $_POST['lote'];
    $regsan = $_POST['regsan'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $tipo = $_POST['tipo'];
    $presentacion = $_POST['presentacion'];
    $laboratorio = $_POST['laboratorio'];
    $vencimiento = $_POST['vencimiento'];
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
        if (empty($id)) {
            $query_update = mysqli_query($conexion, "UPDATE mstockalm SET vencimiento = '$vencimiento' WHERE codmed = '$codmed'");
            if ($query_update) {
                $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Fecha de vencimiento del producto actualizado correctamente!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            }
        } else {
           $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Error al modificar
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            }

    }
}
include_once "includes/header.php";
?>
<div class="alert alert-info" role="alert">
    <strong>Estimado usuario!</strong> Los campos remarcados con <label class="text-danger">*</label> son necesarios.
    <br>
    <strong>En esta ventana puede cambiar la FVenc. del producto si es necesario.</strong>
</div>
<div class="card shadow-lg">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        Consultar stock de Productos
                    </div>
                    <div class="card-body">
                        <form action="" method="post" autocomplete="off" id="formulario">
                            <?php echo isset($alert) ? $alert : ''; ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="codmed" class=" text-dark font-weight-bold">Cod. Producto<span class="text-danger">*</span></label>
                                        <input type="text" name="codmed" id="codmed" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="codigo" class=" text-dark font-weight-bold"><i class="fas fa-barcode"></i> Código de Barras</label>
                                        <input type="text" placeholder="(código de barras)" name="codigobarra" id="codigobarra" class="form-control" readonly>
                                        <input type="hidden" id="id" name="id">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="producto" class=" text-dark font-weight-bold">Producto<span class="text-danger">*</span></label>
                                        <input type="text" placeholder="(producto)" name="producto" id="producto" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="lote" class=" text-dark font-weight-bold">Lote</label>
                                        <input type="text" placeholder="(lote)" name="lote" id="lote" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="rs" class=" text-dark font-weight-bold">REG. SAN.<span class="text-danger">*</span></label>
                                        <input type="text" id="regsan" name="regsan" class="form-control"  readonly="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="precio" class=" text-dark font-weight-bold">Precio<span class="text-danger">*</span></label>
                                        S/.<input type="text" placeholder="(precio)" class="form-control" name="precio" id="precio" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="cantidad" class=" text-dark font-weight-bold">Cantidad<span class="text-danger">*</span></label>
                                        <input type="number" placeholder="(cantidad)" class="form-control" name="cantidad" id="cantidad" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="tipo" class=" text-dark font-weight-bold">Tipo</label>
                                        <input type="text" id="tipo" class="form-control" name="tipo" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="presentacion" class=" text-dark font-weight-bold">Presentación</label>
                                        <input type="text" id="presentacion" class="form-control" name="presentacion" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="laboratorio" class=" text-dark font-weight-bold">Laboratorio</label>
                                        <input type="text" id="laboratorio" class="form-control" name="laboratorio" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="vencimiento" class=" text-dark font-weight-bold">Vencimiento</label>
                                        <input id="vencimiento" class="form-control" type="date" name="vencimiento">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <input type="submit" value="Modificar" class="btn btn-primary" id="">
                                    <input type="button" value="Nuevo" onclick="limpiar()" class="btn btn-success" id="btnNuevo">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tbl">
                    <thead class="thead-dark">
                        <tr>
                            <th>Cod Med</th>
                            <th>Producto</th>
                            <th>Lote</th>
                            <th>Reg. San</th>
                            <th>Precio</th>
                            <th>Fech. Vcto</th>
                            <th>Stock</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include "../conexion.php";

                        $query = mysqli_query($conexion, "select m.*, m.codmed, m.med, m.lote, r.codigoreg as regsan, m.precio, m.vencimiento, m.stock from mstockalm m inner join regsan r on m.idregsan = r.idreg");
                        $result = mysqli_num_rows($query);
                        if ($result > 0) {
                            while ($data = mysqli_fetch_assoc($query)) { ?>
                                <tr>
                                    <td><?php echo $data['codmed']; ?></td>
                                    <td><?php echo $data['med']; ?></td>
                                    <td><?php echo $data['lote']; ?></td>
                                    <td><?php echo $data['regsan']; ?></td>
                                    <td>S/.<?php echo $data['precio']; ?></td>
                                    <td><?php echo $data['vencimiento']; ?></td>
                                    <td><?php echo $data['stock']; ?></td>
                                    <td>
                                        <a href="#" onclick="editarvenci(<?php echo $data['idstock']; ?>)" class="btn btn-primary"><i class='fas fa-edit'></i></a>

                                    </td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>-->
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>

