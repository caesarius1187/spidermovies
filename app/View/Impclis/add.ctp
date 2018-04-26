<?php
$miRespuesta = array();
if(isset($respuesta)){ 
     $miRespuesta['respuesta'] = $respuesta; 
	 $miRespuesta['algo'] = 1; 
}else{ 
    $class='add';
    if(!isset($impcliCreado)){ 
 	   $miRespuesta['accion']= 'agregar';
    }else{ 
       $miRespuesta['accion']= 'editar';
       $miRespuesta['impid']= $impcli['Impcli']['id'];
    }
    $rowUsuClave="";
    if($impcli['Impuesto']['organismo']=='sindicato'||$impcli['Impuesto']['organismo']=='banco'){
        $rowUsuClave='
        <td>'.$impcli['Impcli']['usuario'].'</td>
        <td>'.$impcli['Impcli']['clave'].'</td>';
    }
     $tdImpcliprov="";
    if($impcli['Impuesto']['id']=='174'/*Convenio Multilateral*/||$impcli['Impuesto']['id']=='21'/*Convenio Multilateral*/){
        $tdImpcliprov=' <a href="#" onclick="loadFormImpuestoProvincias('.$impcli['Impcli']['id'].')" class="button_view"> 
                            '.$this->Html->image('mapa_regiones.png', array('alt' => 'open','class'=>'img_edit')).'
                        </a>';
    }
    //aca vamos a agregar la opcion de manejar las Provincias de un impuesto que debe relacionar Provincias
    if($impcli['Impuesto']['id']=='6'/*Actividades Varias*/){
        $tdImpcliprov='<a href="#"  onclick="loadFormImpuestoLocalidades('.$impcli['Impcli']['id'].')" class="button_view">
            '.$this->Html->image('localidad.png', array('alt' => 'open','class'=>'img_edit')).'
        </a>';
   }
    if($impcli['Impuesto']['organismo']=='banco'){
        $tdImpcliprov.='
         <a href="#"  onclick="loadCbus('.$impcli['Impcli']['id'].')" class="button_view">
            '.$this->Html->image("cuentabancaria.png", array('alt' => 'open','class'=>'img_edit')).'
        </a>
        ';
    }
    $miRespuesta['impclirow']= '
    <tr id="rowImpcli'.$impcli['Impcli']['id'].'">                                                
        <td>'.$impcli['Impuesto']['nombre'].'</td>
        <td>'.$Periodoalta.'</td>                                    
        '.$rowUsuClave.'
        <td>
            <a href="#"  onclick="loadFormImpuesto('.$impcli['Impcli']['id'].','.$impcli['Impcli']['cliente_id'].')" class="button_view"> 
             '.$this->Html->image('edit_view.png', array('alt' => 'open','class'=>'img_edit')).'
                </a>
            <a href="#"  onclick="loadFormImpuestoPeriodos('.$impcli['Impcli']['id'].')" class="button_view"> 
             '.$this->Html->image('calendario.png', array('alt' => 'open','class'=>'img_calendario')).'
            </a>
            <a href="#"  onclick="deleteImpcli('.$impcli['Impcli']['id'].')" class="button_view"> 
             '.$this->Html->image('delete.png', array('alt' => 'open','class'=>'img_trash')).'
            </a>
            '.$tdImpcliprov.'
        </td>
    </tr>';
} 
echo json_encode($miRespuesta);
?>