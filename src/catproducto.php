
<link href="../../assets/css/boostrap.css" rel="stylesheet">
<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>

<?php
session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "CATALOGO_PRODUCTOS";
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
    $presentacion = $_POST['presentacion'];
    $cnc = $_POST['cnc'];
    $ff = $_POST['ff'];
    $tipo = $_POST['tipo'];
    $estado = $_POST['estado'];

    $vencimiento = '';
    if (!empty($_POST['accion'])) {
        $vencimiento = $_POST['vencimiento'];
    }
    if (empty($codmed) || empty($producto) || empty($tipo) ) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todo los campos son obligatorios
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    } else {
        if (empty($id)) {
            $query = mysqli_query($conexion, "SELECT * FROM producto WHERE codmed = '$codmed'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        El codigo ya existe
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {

                $query_insert = mysqli_query($conexion, "INSERT INTO producto(codmed, codigobarra,descripcion,prese,cnc,ff,tipo,estado) values ('$codmed','$codigobarra', '$producto', '$presentacion', '$cnc','$ff','$tipo', '$estado')");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Producto registrado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">
                    Error al registrar el producto
                  </div>';
                }
            }
        } else {
            $query_update = mysqli_query($conexion, "UPDATE producto SET codmed = '$codmed', codigobarra = '$codigobarra', descripcion = '$producto', prese = '$presentacion', cnc = '$cnc' , ff= '$ff', tipo = '$tipo', estado = '$estado' WHERE codproducto = $id");
            if ($query_update) {
                $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Producto Modificado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
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
}
include_once "includes/header.php";
?>
<div class="alert alert-info" role="alert">
    <strong>Estimado usuario!</strong> Los campos remarcados con <span class="text-danger">*</span> son necesarios.
    <br>
    <strong>Puedes visualizar, registrar y/o modificar.</strong>
</div>
<div class="card shadow-lg">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        Catalogo de Productos
                    </div>
                    <div class="card-body">
                        <form action="" method="post" autocomplete="off" id="formulario">
                            <?php echo isset($alert) ? $alert : ''; ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?PHP
                                        $query_id = mysqli_query($conexion, "SELECT RIGHT(codmed,6) as codigo FROM producto
                                                ORDER BY codmed DESC LIMIT 1");
                                        $count = mysqli_num_rows($query_id);
                                        if ($count <> 0) {
                                            $data_id = mysqli_fetch_assoc($query_id);
                                            $codigo = $data_id['codigo'] + 1;
                                        } else {
                                            $codigo = 1;
                                        }
                                        $buat_id = str_pad($codigo, 6, "0", STR_PAD_LEFT);
                                        $codigo = "FL$buat_id";
                                        ?>
                                        <label for="codmed" class=" text-dark font-weight-bold">Cod. Producto</label>
                                        <input type="text" name="codmed" id="codmed" value="<?php echo $codigo; ?>" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="codigo" class=" text-dark font-weight-bold"><i class="fas fa-barcode"></i> Código de Barras</label>
                                        <input type="text" placeholder="Ingrese código de barras" name="codigobarra" id="codigobarra" class="form-control" >
                                        <input type="hidden" id="id" name="id">
                                        <input type="hidden" id="vencimiento" name="vencimiento">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="producto" class=" text-dark font-weight-bold">Producto</label>
                                        <input type="text" placeholder="Ingrese nombre del producto" name="producto" id="producto" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="presentacion" class=" text-dark font-weight-bold">Presentación</label>
                                        <input type="text" placeholder="presentacion" name="presentacion" id="presentacion" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="cnc" class=" text-dark font-weight-bold">Concentración</label>
                                        <div class="row-fluid">
                                            <input  id="cnc" name="cnc" class="form-control" placeholder="concentracion">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="ff" class=" text-dark font-weight-bold">Forma Farmaceutica</label>
                                        <input type="text" placeholder="F. farmaceutico" class="form-control" name="ff" id="ff">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="tipo" class=" text-dark font-weight-bold">Tipo</label>
                                        <input type="text" placeholder="Ingrese tipo" class="form-control" name="tipo" id="tipo" >
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="estado" class=" text-dark font-weight-bold">Estado</label>
                                        <select id="estado" class="form-control" name="estado" required>
                                            <option value="">--seleccione--</option>
                                           <option value="A"><- ACTIVO -></option>
                                            <option value="I"><- INACTIVO -></option>
                                        </select>
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
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tbl">
                    <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Cod Med</th>
                        <th>Cod. Barra</th>
                        <th>Producto</th>
                        <th>Presentación</th>
                        <th>Concentración</th>
                        <th>Forma Farmaceutica</th>
                        <th>Atribución</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    include "../conexion.php";

                    $query = mysqli_query($conexion, "select codproducto, codmed, codigobarra, descripcion, prese, cnc, ff, atrib, tipo, estado from producto");
                    $result = mysqli_num_rows($query);
                    if ($result > 0) {
                        while ($data = mysqli_fetch_assoc($query)) { ?>
                            <tr>
                                <td><?php echo $data['codproducto'];?></td>
                                <td><?php echo $data['codmed']; ?></td>
                                <td><?php echo $data['codigobarra']; ?></td>
                                <td><?php echo $data['descripcion']; ?></td>
                                <td><?php echo $data['prese']; ?></td>
                                <td><?php echo $data['cnc']; ?></td>
                                <td><?php echo $data['ff']; ?></td>
                                <td><?php echo $data['atrib']; ?></td>
                                <td><?php echo $data['tipo']; ?></td>
                                <td><?php echo $data['estado']; ?></td>
                                <td>
                                    <a href="#" onclick="editarProducto(<?php echo $data['codproducto']; ?>)" class="btn btn-primary"><i class='fas fa-edit'></i></a>

                                    <!--<form action="eliminar_producto.php?id=<?php echo $data['codproducto']; ?>" method="post" class="confirmar d-inline">
                                            <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                        </form>-->
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

