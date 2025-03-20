<?php
//    $host = "localhost";
//    $user = "root";
//    $clave = "root";
//    $bd = "lujan_farmacia";
//    $conexion = mysqli_connect($host,$user,$clave,$bd);
//    if (mysqli_connect_errno()){
//        echo "No se pudo conectar a la base de datos";
//        exit();
//    }
//    mysqli_select_db($conexion,$bd) or die("No se encuentra la base de datos");
//    mysqli_set_charset($conexion,"utf8");
?>

<?php
$conexion=new mysqli("localhost","root","root","lujan_farmacia","3306");
$conexion->set_charset("utf8");  
?>
