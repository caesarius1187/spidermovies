<?php
$miRespuesta = array();
if(isset($respuesta)){ 
     $miRespuesta['respuesta'] = $respuesta; 
}else{ 
    if(!isset($honorario)){ 
 	   $miRespuesta['accion']= 'agregar';
    }else{ 
       $miRespuesta['accion']= 'editar';
       $miRespuesta['honorario']= $honorario['Honorario'];
    }   
} 
echo json_encode($miRespuesta);
?>