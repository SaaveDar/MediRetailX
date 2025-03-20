<?php
session_start();
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "ventas";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
//$query = mysqli_query($conexion, "SELECT v.*, c.idcliente, c.nombre FROM ventas v INNER JOIN cliente c ON v.id_cliente = c.idcliente order by v.fecha desc");
include_once "includes/header.php";
?>

    <div class="alert alert-info" role="alert">
        <strong>Estimado usuario!</strong> Los campos remarcados con <label class="text-danger">*</label> son necesarios.
        <br>
        <strong>En esta ventana se visualiza el historial de ventas y consulta por fecha especifica.</strong>
    </div>
<div class="card">
    <form action="" method="post" autocomplete="off" id="formulario">
        <div class="row">
            <div class="col-md-12">
                    <div class="card-header bg-primary text-white">
                        Consulta de historial de ventas.
                    </div>
                    <div class="card-body">
                        <form action="" method="post" autocomplete="off" id="formulario">
                            <?php echo isset($alert) ? $alert : ''; ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php
                                        $inicio = new DateTime();
                                        $inicio->modify('first day of this month');
                                        //echo $inicio->format('d/m/Y');
                                        $in= $inicio->format('Y-m-d');
                                        ?>
                                        <label for="inicio" class=" text-dark font-weight-bold">Fecha<span class="text-danger">*</span></label>
                                        <input id="date1" class="form-control" type="date"  value="<?php echo $in;?>" name="date1" oninput="check()"  required/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php
                                        $final = new DateTime();
                                        $final->modify('last day of this month');
                                        //echo $fecha->format('d/m/Y');
                                        $fi = $final->format('Y-m-d');
                                        ?>
                                        <label for="fin" class=" text-dark font-weight-bold"></label>
                                        <input id="date2" class="form-control" type="date" value="<?php echo $fi;?>" name="date2" oninput="check()" required>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <input type="submit" value="Buscar" class="btn btn-primary" id="buscar" name="buscar" >
                                </div>
                                <script>
                                    function check(){
                                        if(document.getElementById("date1").value>document.getElementById("date2").value){
                                            alert("La fecha de inicio es mayor que la final.");
                                            buscar.disabled = true;
                                        }else{
                                            buscar.disabled = false;
                                        }
                                    }
                                </script>
                            </div>

                        </form>
                    </div>
            </div>
        </div>
    </form>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-light" id="tbl">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>IGV(18%)</th>
                        <th>OP. Gravada</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php include'range_fechasventas.php'?>
                </tbody>
            </table>

        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>