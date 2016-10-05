<?php 
	$respuesta = array();
	$respuesta['clientes']=$clientes;
	$respuesta['shownombre']=$shownombre;
	echo json_encode($respuesta);
?>