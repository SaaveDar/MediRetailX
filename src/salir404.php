<?php
	require '../conexion.php';
	session_destroy();
	$url = "../src";
	header("Location: $url");
?>
