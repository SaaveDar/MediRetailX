<?php
require "../conexion.php";
$usuarios = mysqli_query($conexion, "SELECT * FROM usuario");
$total['usuarios'] = mysqli_num_rows($usuarios);
$clientes = mysqli_query($conexion, "SELECT * FROM cliente");
$total['clientes'] = mysqli_num_rows($clientes);
$productos = mysqli_query($conexion, "SELECT * FROM producto");
$total['productos'] = mysqli_num_rows($productos);
$ventas = mysqli_query($conexion, "SELECT * FROM ventas WHERE fecha <= CURDATE()");
$total['ventas'] = mysqli_num_rows($ventas);
session_start();
include_once "includes/header.php";
?>
<!-- Content Row -->
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
                <div class="card-icon">
                    <i class="fas fa-user fa-2x"></i>
                </div>
                <a href="usuarios.php" class="card-category text-warning font-weight-bold">
                    Usuarios
                </a>
                <h3 class="card-title"><?php echo $total['usuarios']; ?></h3>
            </div>
            <div class="card-footer bg-warning text-white">
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-success card-header-icon">
                <div class="card-icon">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <a href="clientes.php" class="card-category text-success font-weight-bold">
                    Clientes
                </a>
                <h3 class="card-title"><?php echo $total['clientes']; ?></h3>
            </div>
            <div class="card-footer bg-secondary text-white">
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-danger card-header-icon">
                <div class="card-icon">
                    <i class="fab fa-product-hunt fa-2x"></i>
                </div>
                <a href="productos.php" class="card-category text-danger font-weight-bold">
                    Productos
                </a>
                <h3 class="card-title"><?php echo $total['productos']; ?></h3>
            </div>
            <div class="card-footer bg-primary">
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="fas fa-cash-register fa-2x"></i>
                </div>
                <a href="ventas.php" class="card-category text-info font-weight-bold">
                    Ventas
                </a>
                <h3 class="card-title"><?php echo $total['ventas']; ?></h3>
            </div>
            <div class="card-footer bg-danger text-white">
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Productos por vencerse
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-danger table-bordered" id="tbl">
                    <thead class="thead-dark">
                        <tr>
                            <th>Cod Med</th>
                            <th>Producto - Pres. - Conc. FF. </th>
                            <th>Lote</th>
                            <th>Reg. San</th>
                            <th>Precio</th>
                            <th>Fech. Vcto</th>
                            <th>Stock</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include "../conexion.php";
                        $hoy = date('Y-m-d');
                        $fv = mysqli_query($conexion, "SELECT fv FROM configuracion");

                        $query = mysqli_query($conexion, "select m.*, m.codmed, m.med, m.lote, r.codigoreg as regsan, m.precio, m.vencimiento, m.stock from mstockalm m inner join regsan r on m.idregsan = r.idreg where m.stock>0 and m.vencimiento between curdate() and date_add(curdate(), interval 130 day)");
                        //$query = mysqli_query($conexion, "select m.*, m.codmed, m.med, m.lote, r.codigoreg as regsan, m.precio, m.vencimiento, m.stock from mstockalm m inner join regsan r on m.idregsan = r.idreg where m.vencimiento <= CURDATE() and date_add(curdate(), interval 130 day)");
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
                                  <!--      <form action="eliminar_producto.php?id=<?php echo $data['idstock']; ?>" method="post" class="confirmar d-inline">
                                            <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                        </form> -->
                                    </td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header card-header-primary">
                <h3 class="title-2 m-b-40">Productos con stock mínimo</h3>
            </div>
            <div class="card-body">
                <canvas id="stockMinimo"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header card-header-primary">
                <h3 class="title-2 m-b-40">Productos más vendidos</h3>
            </div>
            <div class="card-body">
                <canvas id="ProductosVendidos"></canvas>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>
