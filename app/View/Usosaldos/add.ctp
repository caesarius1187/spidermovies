<?php
$miRespuesta = array();
if(isset($respuesta)){ 
     $miRespuesta['respuesta'] = $respuesta; 
}else{ 
    if(!isset($deposito)){ 
 	   $miRespuesta['accion']= 'agregar';
    }else{ 
       $miRespuesta['accion']= 'editar';
       $miRespuesta['sueldo']= $sueldo['sueldo'];
    }   
} 
echo json_encode($miRespuesta);
?>