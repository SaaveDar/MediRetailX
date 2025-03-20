<?php
require_once "../conexion.php";
session_start();
if (isset($_GET['q'])) {
    $datos = array();
    $nombre = $_GET['q'];
    $cliente = mysqli_query($conexion, "SELECT * FROM cliente WHERE nombre LIKE '%$nombre%' OR doc LIKE '%$nombre%'");
    while ($row = mysqli_fetch_assoc($cliente)) {
        $data['id'] = $row['idcliente'];
        $data['label'] = $row['nombre'];
        $data['direccion'] = $row['direccion'];
        $data['telefono'] = $row['telefono'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
}else if (isset($_GET['pro'])) {
    $datos = array();
    $nombre = $_GET['pro'];
    $hoy = date('Y-m-d');
    $producto = mysqli_query($conexion, "SELECT * FROM mstockalm WHERE stock > 0 and vencimiento >= CURDATE() and med LIKE '%" . $nombre . "%' OR codmed LIKE '%" . $nombre . "%'");
    while ($row = mysqli_fetch_assoc($producto)) {
        $data['id'] = $row['idstock'];
        $data['codmed'] = $row['codmed'];
        $data['label'] = $row['codigobarra'] . ' - ' .$row['med']. ' Lote: '.$row['lote']. '- FV: ' .$row['vencimiento'];
        $data['value'] = $row['med'];
        $data['precio'] = $row['precio'];
        $data['existencia'] = $row['stock'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
}else if (isset($_GET['buscarpro'])) {
    $datos = array();
    $nombre = $_GET['buscarpro'];
    $hoy = date('Y-m-d');
    $producto = mysqli_query($conexion, "SELECT * FROM PRODUCTO WHERE descripcion LIKE '%" . $nombre . "%' and estado='A'");
    while ($row = mysqli_fetch_assoc($producto)) {
        $data['id'] = $row['codproducto'];
        $data['codmed'] = $row['codmed'];
        $data['codigobarra'] = $row['codigobarra'];
        $data['label'] = $row['descripcion'].' - ' .$row['prese'].' - '.$row['cnc'].' - '. $row['ff'].' - '.$row['codigobarra'];
        $data['prese'] = $row['prese'];
        $data['cnc'] = $row['cnc'];
        $data['ff'] = $row['ff'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
}else if (isset($_GET['buscarprosalida'])) {
    $datos = array();
    $nombres = $_GET['buscarprosalida'];
    $hoy = date('Y-m-d');
    $productos = mysqli_query($conexion, "SELECT m.*, r.*, r.codigoreg FROM mstockalm m inner join regsan r on m.idregsan=r.idreg WHERE m.med LIKE '%$nombres%'");
    while ($rows = mysqli_fetch_assoc($productos)) {
        $data['ids'] = $rows['idstock'];
        $data['codmeds'] = $rows['codmed'];
        $data['codigobarras'] = $rows['codigobarra'];
        $data['label'] = $rows['med'];
        $data['lotes'] = $rows['lote'];
        $data['precios'] = $rows['precio'];
        $data['regsans'] = $rows['codigoreg'];
        $data['stocks'] = $rows['stock'];
        $data['vencs'] = $rows['vencimiento'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
}else if (isset($_GET['detalle'])) {
    $id = $_SESSION['idUser'];
    $datos = array();
    $detalle = mysqli_query($conexion, "SELECT d.*, s.idstock, s.med, s.codmed  FROM detalle_temp d INNER JOIN mstockalm s ON d.id_producto = s.idstock WHERE d.id_usuario = $id");
    while ($row = mysqli_fetch_assoc($detalle)) {
        $data['id'] = $row['id'];
        $data['codigo'] = $row['codmed'];
        $data['descripcion'] = $row['med'];
        $data['cantidad'] = $row['cantidad'];
        $data['descuento'] = $row['descuento'];
        $data['precio_venta'] = $row['precio_venta'];
        $data['sub_total'] = $row['total'];
        array_push($datos, $data);
    }
    echo json_encode($datos);
    die();
} else if (isset($_GET['delete_detalle'])) {
    $id_detalle = $_GET['id'];
    $query = mysqli_query($conexion, "DELETE FROM detalle_temp WHERE id = $id_detalle");
    if ($query) {
        $msg = "ok";
    } else {
        $msg = "Error";
    }
    echo $msg;
    die();
} else if (isset($_GET['procesarVenta'])) {
    $id_cliente = $_GET['id'];
    $id_user = $_SESSION['idUser'];
    $consulta = mysqli_query($conexion, "SELECT SUM(total) AS total_pagar FROM detalle_temp WHERE id_usuario = '$id_user'");
    $result = mysqli_fetch_assoc($consulta);
    $total = $result['total_pagar'];
    //
    $base = $total / 1.18;
    $igv = $total - $base;
    //
    //inicio
    $v = mysqli_query($conexion, "SELECT RIGHT(nv,9) as nventas FROM ventas  ORDER BY nv DESC LIMIT 1");
    $countv = mysqli_num_rows($v);
    if ($countv <> 0) {
        $data_idv = mysqli_fetch_assoc($v);
        $ve = $data_idv['nventas'] + 1;
    } else {
        $ve = 1;
    }
    $tahunv          = date("Ym");
    $buat_idv = str_pad($ve, 9, "0", STR_PAD_LEFT);
    $nv  = "NV-$tahunv-$buat_idv";
    //fin
    $insertar = mysqli_query($conexion, "INSERT INTO ventas(id_cliente, total, id_usuario, nv, igv, opegravada) VALUES ($id_cliente, '$total', $id_user, '$nv', '$igv', '$base')");
    if ($insertar) {
        $id_maximo = mysqli_query($conexion, "SELECT MAX(id) AS total FROM ventas");
        $resultId = mysqli_fetch_assoc($id_maximo);
        $ultimoId = $resultId['total'];
        $consultaDetalle = mysqli_query($conexion, "SELECT * from detalle_temp where id_usuario = '$id_user'");
        while ($row = mysqli_fetch_assoc($consultaDetalle)) {
            $id_producto = $row['id_producto'];
            //$codmed = $row['codmed'];
            $cantidad = $row['cantidad'];
            $desc = $row['descuento'];
            $precio = $row['precio_venta'];
            $total = $row['total'];
            $insertarDet = mysqli_query($conexion, "INSERT INTO detalle_venta (id_producto, id_venta, cantidad, precio, descuento, total) VALUES ($id_producto, $ultimoId, '$cantidad', '$precio', '$desc', '$total')");
            $stockActual = mysqli_query($conexion, "SELECT * FROM mstockalm WHERE idstock = '$id_producto' ");
            $stockNuevo = mysqli_fetch_assoc($stockActual);
            $sku = $stockNuevo['codmed'];
            $stockTotal = $stockNuevo['stock'] - $cantidad;
            $stock = mysqli_query($conexion, "UPDATE mstockalm SET stock = '$stockTotal' WHERE idstock = '$id_producto'");

            // insert movimiento (inicio)
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
            $codigo  = "TV-$tahun-$buat_id";
            // fin
            $concepto = "VENTA";
            date_default_timezone_set('America/Lima');
            $fecha_actualv = date("Y-m-d H:i:s");
            $tipo = "V";
            $insertarmov = mysqli_query($conexion, "INSERT INTO tmovim (codigomov, concepto, fecha, cod_med, tipo, salida) VALUES ('$codigo', '$concepto', '$fecha_actualv', '$sku', '$tipo', '$cantidad')");
            // ACTUALIZAR IDMOVIM
            $cidm = mysqli_query($conexion, "SELECT idmov as codigo FROM tmovim
                                                ORDER BY idmov DESC LIMIT 1");
            $data_idm = mysqli_fetch_assoc($cidm);
            $cidmovs = $data_idm['codigo'];

            $query_insert2 = mysqli_query($conexion, "UPDATE mstockalm SET idmovis= '$cidmovs' where idstock ='$id_producto'");

        } 
        if ($insertarDet) {
            $eliminar = mysqli_query($conexion, "DELETE FROM detalle_temp WHERE id_usuario = $id_user");
            $msg = array('id_cliente' => $id_cliente, 'id_venta' => $ultimoId);
        } 
    }else{
        $msg = array('mensaje' => 'error');
    }
    echo json_encode($msg);
    die();
}else if (isset($_GET['descuento'])) {
    $id = $_GET['id'];
    $desc = $_GET['desc'];
    $consulta = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE id = $id");
    $result = mysqli_fetch_assoc($consulta);
    $total_desc = $desc + $result['descuento'];
    $total = $result['total'] - $desc;
    $insertar = mysqli_query($conexion, "UPDATE detalle_temp SET descuento = '$total_desc', total = '$total'  WHERE id = '$id'");
    if ($insertar) {
        $msg = array('mensaje' => 'descontado');
    }else{
        $msg = array('mensaje' => 'error');
    }
    echo json_encode($msg);
    die();
}else if(isset($_GET['editarCliente'])){
    $idcliente = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $idcliente");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
} else if (isset($_GET['editarUsuario'])) {
    $idusuario = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM usuario WHERE idusuario = $idusuario");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
} else if (isset($_GET['editarProducto'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $id");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
}else if (isset($_GET['editarvenci'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($conexion, "select m.codmed, m.med, m.lote, r.codigoreg, m.precio, m.vencimiento, sum(m.stock) as stock from mstockalm m inner join regsan r on m.idregsan = r.idreg where m.idstock= $id");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
}else if (isset($_GET['nsalidapro'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($conexion, "select m.idstock, m.codmed, m.med, m.lote, r.codigoreg, m.precio, m.vencimiento, m.stock from mstockalm m inner join regsan r on m.idregsan = r.idreg where m.idstock= $id");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
}else if (isset($_GET['editarTipo'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM tipos WHERE id = $id");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
}else if (isset($_GET['editarRegsan'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM regsan WHERE idreg = $id");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
}else if (isset($_GET['editarPresent'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM presentacion WHERE id = $id");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
} else if (isset($_GET['editarLab'])) {
    $id = $_GET['id'];
    $sql = mysqli_query($conexion, "SELECT * FROM laboratorios WHERE id = $id");
    $data = mysqli_fetch_array($sql);
    echo json_encode($data);
    exit;
}
if (isset($_POST['regDetalle'])) {
    $id = $_POST['id'];
    //$codmed = $_POST['codmed'];
    $cant = $_POST['cant'];
    $precio = $_POST['precio'];
    $id_user = $_SESSION['idUser'];
    $total = $precio * $cant;
    $verificar = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE id_producto = '$id' AND id_usuario = '$id_user'");
    $result = mysqli_num_rows($verificar);
    $datos = mysqli_fetch_assoc($verificar);
    if ($result > 0) {
        $cantidad = $datos['cantidad'] + $cant;
        $total_precio = ($cantidad * $total);
        $query = mysqli_query($conexion, "UPDATE detalle_temp SET cantidad = '$cantidad', total = '$total_precio' WHERE id_producto = '$id' AND id_usuario = '$id_user'");
        if ($query) {
            $msg = "actualizado";
        } else {
            $msg = "Error al ingresar";
        }
    }else{
        $query = mysqli_query($conexion, "INSERT INTO detalle_temp(id_usuario, id_producto, cantidad ,precio_venta, total) VALUES ($id_user, $id, $cant,'$precio', '$total')");
        if ($query) {
            $msg = "registrado";
        }else{
            $msg = "Error al ingresar";
        }
    }
    echo json_encode($msg);
    die();
}else if (isset($_POST['cambio'])) {
    if (empty($_POST['actual']) || empty($_POST['nueva'])) {
        $msg = 'Los campos estan vacios';
    } else {
        $id = $_SESSION['idUser'];
        $actual = md5($_POST['actual']);
        $nueva = md5($_POST['nueva']);
        $consulta = mysqli_query($conexion, "SELECT * FROM usuario WHERE clave = '$actual' AND idusuario = $id");
        $result = mysqli_num_rows($consulta);
        if ($result == 1) {
            $query = mysqli_query($conexion, "UPDATE usuario SET clave = '$nueva' WHERE idusuario = $id");
            if ($query) {
                $msg = 'ok';
            }else{
                $msg = 'error';
            }
        } else {
            $msg = 'dif';
        }
        
    }
    echo $msg;
    die();
    
}

