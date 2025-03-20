<?php
session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "kardex";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}

include_once "includes/header.php";
?>
    <div class="card shadow-lg">
    <div class="card-header bg-primary text-white">
        Tarjeta de Control Visible (TCV)
    </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <?php echo (isset($alert)) ? $alert : ''; ?>
                    <form action="" method="post" autocomplete="off" id="formulario">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card shadow-lg">
                                    <div class="card-header bg-primary text-white">
                                        Consulta de Productos en el kardex
                                    </div>
                                    <div class="card-body">
                                        <form action="range_fechas.php" method="post" autocomplete="off" id="formulario">
                                            <?php echo isset($alert) ? $alert : ''; ?>
                                            <div class="row">
                                                <!--<div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="buscarproducto" class=" text-dark font-weight-bold">Producto</label>
                                                        <input type="text" placeholder="Ingrese nombre del producto" name="buscarproducto" id="buscarproducto" class="form-control">
                                                    </div>
                                                </div>-->
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inicio" class=" text-dark font-weight-bold">Fecha</label>
                                                        <input id="inicio" class="form-control" type="date" name="inicio" placeholder="Start" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inicio" class=" text-dark font-weight-bold"></label>
                                                        <input id="fin" class="form-control" type="date" name="fin" placeholder="Start" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="submit" value="Buscar" class="btn btn-primary" id="search" name="search">
                                                    <!--<button class="btn btn-primary" name="search">Buscar</button> <a href="../kardex.php.php" type="button"></a>-->
                                                    <!--<input type="button" value="Nuevo" onclick="limpiar()" class="btn btn-success" id="">-->
                                                </div>
                                                <script>
                                                    function check(){
                                                        if(document.getElementById("inicio").value>document.getElementById("fin").value){
                                                            alert("La fecha de inicio es mayor que la final.");
                                                            search.disabled = true;
                                                        }else{
                                                            search.disabled = false;
                                                        }
                                                    }
                                                </script>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="tbl">
                            <thead class="thead-dark">
                            <tr>
                                <th>Cod. Mov.</th>
                                <th>Concepto</th>
                                <th>Fecha</th>
                                <th>Cod. Med</th>
                                <th>Medicam</th>
                                <th>Lote</th>
                                <th>REG. SAN.</th>
                                <th>Entrada</th>
                                <th>Salida</th>
                                <th>Saldo</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php include'range_fechas.php'?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include_once "includes/footer.php"; ?>