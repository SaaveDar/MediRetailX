<?php
include "../conexion.php";

if(ISSET($_POST['search'])){
    $date1 = date("Y-m-d", strtotime($_POST['inicio']));
    $date2 = date("Y-m-d", strtotime($_POST['fin']));
    $query=mysqli_query($conexion, "select m.estado, m.codigomov, m.concepto,DATE_FORMAT(m.fecha, '%Y-%m-%d') as sFecha, m.fecha, s.lote, r.codigoreg, m.tipo, m.entrada,m.salida, s.stock, s.codmed, s.med from tmovim m inner join mstockalm s on s.codmed = m.cod_med inner join regsan r on s.idregsan=r.idreg WHERE m.fecha BETWEEN '$date1' AND '$date2'") or die(mysqli_error());
    $row=mysqli_num_rows($query);
    if($row>0){
        while($data=mysqli_fetch_array($query)){
            ?>
            <tr>
                <td><?php echo $data['codigomov']; ?></td>
                <td><?php echo $data['concepto']; ?></td>
                <td><?php echo $data['sFecha']; ?></td>
                <td><?php echo $data['codmed']; ?></td>
                <td><?php echo $data['med']; ?></td>
                <td><?php echo $data['lote']; ?></td>
                <td><?php echo $data['codigoreg']; ?></td>
                <td><?php echo $data['entrada']; ?></td>
                <td><?php echo $data['salida']; ?></td>
                <td><?php echo $data['stock'];?></td>
            </tr>
            <?php
        }
    }else{
        echo'
			<tr>
				<td colspan = "4"><center>No existen registros.</center></td>
			</tr>';
    }
}else{
    $query=mysqli_query($conexion, "select m.estado, m.codigomov, m.concepto,DATE_FORMAT(m.fecha, '%Y-%m-%d') as sFecha ,m.fecha, s.lote, r.codigoreg, m.tipo, m.entrada,m.salida, s.stock, s.codmed, s.med from tmovim m inner join mstockalm s on s.codmed = m.cod_med inner join regsan r on s.idregsan=r.idreg ORDER BY m.fecha desc") or die(mysqli_error());
    while($data=mysqli_fetch_array($query)){
        ?>
        <tr>
            <td><?php echo $data['codigomov']; ?></td>
            <td><?php echo $data['concepto']; ?></td>
            <td><?php echo $data['sFecha']; ?></td>
            <td><?php echo $data['codmed']; ?></td>
            <td><?php echo $data['med']; ?></td>
            <td><?php echo $data['lote']; ?></td>
            <td><?php echo $data['codigoreg']; ?></td>
            <td><?php echo $data['entrada']; ?></td>
            <td><?php echo $data['salida']; ?></td>
            <td><?php echo $data['stock'];?></td>
        </tr>
        <?php
    }
}

?>
