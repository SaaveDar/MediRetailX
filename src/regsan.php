<?php
session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "regsan";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}

if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['code']) || empty($_POST['marca'])) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todo los campos son obligatorio
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    } else {
        $id = $_POST['id'];
        $code = $_POST['code'];
        $marca = $_POST['marca'];
        $result = 0;
        if (empty($id)) {
            $query = mysqli_query($conexion, "SELECT * FROM regsan WHERE codigoreg = '$code' AND marca = '$marca'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        El REG. SAN. ya existe
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {
                $query_insert = mysqli_query($conexion, "INSERT INTO regsan(codigoreg, marca) values ('$code','$marca')");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Registro Sanitario registrado correctamente.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                } else {
                    $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Error al registrar
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                }
            }
        } else {
            $sql_update = mysqli_query($conexion, "UPDATE regsan SET codigoreg = '$code', marca = '$marca' WHERE idreg = $id");
            if ($sql_update) {
                $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Registro Sanitario Modificado correctamente.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {
                $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Error al modificar
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            }
        }
    }
    mysqli_close($conexion);
}
include_once "includes/header.php";
?>
    <div class="alert alert-info" role="alert">
        <strong>Estimado usuario!</strong> Los campos remarcados con <label class="text-danger">*</label> son necesarios.
        <br>
        <strong>En esta ventana registra, modifica y elimina la informacion de REG. SAN.</strong>
    </div>
<div class="card">
    <div class="card-header bg-primary text-white">
        Registro de Reg. SAN.
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?php echo (isset($alert)) ? $alert : ''; ?>
                <form action="" method="post" autocomplete="off" id="formulario">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nombre" class="text-dark font-weight-bold">Código<span class="text-danger">*</span></label>
                                <input type="text" placeholder="Ingrese codigo" name="code" id="code" class="form-control" required>
                                <input type="hidden" name="id" id="id">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="nombre" class="text-dark font-weight-bold">Marca<span class="text-danger">*</span></label>
                                <input type="text" placeholder="Ingrese marca" name="marca" id="marca" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <input type="submit" value="Registrar" class="btn btn-primary" id="btnAccion">
                            <input type="button" value="Nuevo" class="btn btn-success" id="btnNuevo" onclick="limpiar()">
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="tbl">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Código</th>
                                <th>Marca</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include "../conexion.php";
                            $query = mysqli_query($conexion, "SELECT * FROM regsan");
                            $result = mysqli_num_rows($query);
                            if ($result > 0) {
                                while ($data = mysqli_fetch_assoc($query)) { ?>
                                    <tr>
                                        <td><?php echo $data['idreg']; ?></td>
                                        <td><?php echo $data['codigoreg']; ?></td>
                                        <td><?php echo $data['marca']; ?></td>
                                        <td style="width: 200px;">
                                            <a href="#" onclick="editarRegsan(<?php echo $data['idreg']; ?>)" class="btn btn-primary"><i class='fas fa-edit'></i></a>
                                            <form action="eliminar_regsan.php?id=<?php echo $data['idreg']; ?>" method="post" class="confirmar d-inline">
                                                <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                            </form>
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
</div>
<?php include_once "includes/footer.php"; ?>