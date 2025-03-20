<?php
include "../conexion.php";

if(ISSET($_POST['buscar'])){
    //CUANDO LA COL "FECHA" ESTA EN timestamp
    $inicio = date("Y-m-d 00:00:00", strtotime($_POST['date1']));
    $fin = date("Y-m-d 23:59:59", strtotime($_POST['date2']));
    //descuento
    $cdes = mysqli_query($conexion, "SELECT sum(d.descuento) as descuento FROM detalle_venta d inner join ventas v on d.id_venta=v.id where v.fecha BETWEEN '$inicio' and '$fin'");
    $des = mysqli_fetch_assoc($cdes);
    $descuento = $des['descuento'];
    // total, igv, op gravada
    $cventas =mysqli_query($conexion, "select sum(total) as mtotal, sum(igv) as igv, sum(opegravada) as gravada from ventas where fecha BETWEEN '$inicio' AND '$fin'" );
    $ve = mysqli_fetch_assoc($cventas);
    $total = $ve['mtotal'];
    $igv = $ve['igv'];
    $gravada = $ve['gravada'];
    //
    $query = mysqli_query($conexion, "SELECT v.*, c.idcliente, DATE_FORMAT(v.fecha, '%Y-%m-%d') as sFecha,DATE_FORMAT(v.fecha,'%H:%i:%s') as sHora , c.nombre FROM ventas v INNER JOIN cliente c ON v.id_cliente = c.idcliente where v.fecha BETWEEN '$inicio' AND '$fin'");
    $row=mysqli_num_rows($query);
    if($row>0){
        while ($row = mysqli_fetch_assoc($query)) {
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nombre']; ?></td>
                <td>S/.<?php echo $row['igv']; ?></td>
                <td>S/.<?php echo $row['opegravada']; ?></td>
                <td>S/.<?php echo $row['total']; ?></td>
                <td><?php echo $row['sFecha']; ?></td>
                <td><?php echo $row['sHora']; ?></td>
                <td>
                    <a href="pdf/generar.php?cl=<?php echo $row['id_cliente'] ?>&v=<?php echo $row['id'] ?>" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
                </td>
            </tr>
            <?php
        }
    }else{
        echo'
			<tr>
				<td colspan = "4"><center>No existen registros. </center></td>
			</tr>';
    }
}

else {
    //echo $inicio = date("Y-m-t 23:59:59"); #2018-08-31
    // fecha inicio
    $ini = new DateTime();
    $ini->modify('first day of this month');
    $i= $ini->format('Y-m-d 00:00:00');
    // fecha fin
    $fina = new DateTime();
    $fina->modify('last day of this month');
    $fi = $fina->format('Y-m-d 23:59:59');
    //descuento
    $cdes = mysqli_query($conexion, "SELECT sum(d.descuento) as descuento FROM detalle_venta d inner join ventas v on d.id_venta=v.id where v.fecha BETWEEN '$i' AND '$fi' "); //where v.fecha BETWEEN '$inicio' AND '$fin'
    $des = mysqli_fetch_assoc($cdes);
    $descuento = $des['descuento'];
    //ventas igv op gravada
    $cventas =mysqli_query($conexion, "select sum(total) as mtotal, sum(igv) as igv, sum(opegravada) as gravada from ventas where fecha BETWEEN '$i' AND '$fi' " );
    $ve = mysqli_fetch_assoc($cventas);
    $total = $ve['mtotal'];
    $igv = $ve['igv'];
    $gravada = $ve['gravada'];
    //
    $query = mysqli_query($conexion, "SELECT v.*, c.idcliente,DATE_FORMAT(v.fecha, '%Y-%m-%d') as sFecha,DATE_FORMAT(v.fecha,'%H:%i:%s') as sHora, c.nombre FROM ventas v INNER JOIN cliente c ON v.id_cliente = c.idcliente where v.fecha BETWEEN '$i' AND '$fi' order by v.fecha desc");
    while ($row = mysqli_fetch_assoc($query)) {
        ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['nombre']; ?></td>
            <td>S/.<?php echo $row['igv']; ?></td>
            <td>S/.<?php echo $row['opegravada']; ?></td>
            <td>s/.<?php echo $row['total']; ?></td>
            <td><?php echo $row['sFecha']; ?></td>
            <td><?php echo $row['sHora']; ?></td>
            <td>
                <a href="pdf/generar.php?cl=<?php echo $row['id_cliente'] ?>&v=<?php echo $row['id'] ?>" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
            </td>
        </tr>
        <?php
    }
}

?>
<span>Total S/.</span>
<input type="number" value="<?php echo $total;?>"name="t" id="t" class="" min="0" max="99999999" step="1" pattern="^[0-9]+" disabled>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<span>IGV(18%) S/.</span>
<input type="number" value="<?php echo $igv;?>" name="i" id="i" class="" min="0" max="99999999" step="1" pattern="^[0-9]+"  disabled>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<span>OP. Gravada S/.</span>
<input type="number" value="<?php echo $gravada;?>" name="op" id="op" class="" min="0" max="99999999" step="1" pattern="^[0-9]+"  disabled>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<span>Descuento S/.</span>
<input type="number" value="<?php echo $descuento;?>" name="op" id="op" class="" min="0" max="99999999" step="1" pattern="^[0-9]+"  disabled>
