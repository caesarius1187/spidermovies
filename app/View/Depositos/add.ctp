<?php
$miRespuesta = array();
if(isset($respuesta)){ 
     $miRespuesta['respuesta'] = $respuesta; 
}else{ 
    if(!isset($deposito)){ 
 	   $miRespuesta['accion']= 'agregar';
    }else{ 
       $miRespuesta['accion']= 'editar';
       $miRespuesta['deposito']= $deposito['Deposito'];
    }   
     $miRespuesta['evento']= $evento;
} 
echo json_encode($miRespuesta);
?>