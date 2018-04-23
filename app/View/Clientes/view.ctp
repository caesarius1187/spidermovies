<?php
echo $this->Html->css('bootstrapmodal');
echo $this->Html->script('jquery-ui',array('inline'=>false));
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
echo $this->Html->script('jquery.dataTables.js',array('inline'=>false));
echo $this->Html->script('dataTables.altEditor.js',array('inline'=>false));
echo $this->Html->script('dataTables.buttons.min.js',array('inline'=>false));
echo $this->Html->script('clientes/view',array('inline'=>false));
?>

<!--Div izquierdo que muestra lista de grupo de clientes con sus clientes-->
<?php 
$widthDivClientes=25;
$GpoCliDeshaHeight = 100;
if(!$mostrarView){
    $widthDivClientes=95;
    $GpoCliDeshaHeight = 80;
}?>
<div class="clientes_view" style="width:<?php echo $widthDivClientes;?>%; ">
    <?php 
        //echo $this->Html->script('jquery.dataTables.grouping',array('inline'=>false))   ;
    ?>
 <?php /**************************************************************************/ ?>
 <?php /*****************************Div CLientes*****************************/ ?>
 <?php /**************************************************************************/ ?>
 <div id="divClientesIndex" class="clientes index" style="margin:0px; overflow:auto;">
    <table style="margin-bottom:20px">
        <tr>
        <td style="padding-left: 0px">
            <input placeholder="Buscar Contribuyente" id="txtBuscarClintes" type="text" style="float:left; width:100%; padding-top:5px" />
        </td>                
        <td style="text-align: right;" title="Agregar Cliente">
            <div class="fab blue">
            <core-icon icon="add" align="center">
                
                <?php echo $this->Form->button('+', 
                                            array('type' => 'button',
                                                'class' =>"btn_add",
                                                'onClick' => "window.location.href='".Router::url(array(
                                                                                    'controller'=>'Clientes', 
                                                                                    'action'=>'add')
                                                                                    )."'"
                                                )
                        );?> 
            </core-icon>
            <paper-ripple class="circle recenteringTouch" fit></paper-ripple>
            </div>
        </td>
        </tr>        
    </table>    
    <!--<div class="section_view" >-->
    <div id="divGrupoClientes_0">
        <?php 
        if(count($clienteses)!=0){
             $grupoMostrar=$clienteses[0]['Grupocliente']['nombre'];
        echo $this->Html->link(
                                $clienteses[0]['Grupocliente']['nombre'], 
                                array(
                                    'controller' => 'grupoclientes', 
                                    'action' => 'view', 
                                    $clienteses[0]['Grupocliente']['id']
                                ),
                                array('class' => 'lbl_gpo_view', 
                                      'id' => 'lnkGrupoCliente_'.$clienteses[0]['Grupocliente']['id'],
                                      'style'=> 'padding-top:5px'
                                )
                              );               
         foreach ($clienteses as $clientex): ?>
                <?php 
                if($grupoMostrar!=$clientex['Grupocliente']['nombre']){
                    $grupoMostrar=$clientex['Grupocliente']['nombre'];
                    echo "</div>";
                    //echo "<div class='section_view'>";
                    echo "<div id='divGrupoClientes_".$clientex['Grupocliente']['id']."'>";
                    echo $this->Html->link(
                                            $clientex['Grupocliente']['nombre'], 
                                            array(
                                                'controller' => 'grupoclientes', 
                                                'action' => 'index', 
                                            ), 
                                            array('class' => 'lbl_gpo_view', 
                                                  'id' => 'lnkGrupoCliente_'.$clientex['Grupocliente']['id'],
                                                  'style' => 'margin-top:10px'
                                            )
                                          );
                }
                $classCliente =  "section_cli_view";
                $classLabel   =  "lbl_cli_view" ;
                $img          =  "cli_view.png";
                if($mostrarView){
                    if($clientex['Cliente']['id']==$cliente['Cliente']['id']){
                        $classCliente = "section_cli_view_selected";
                        $classLabel   = "lbl_cli_view_selected";
                        $img          = "cli_view_blanco.png";
                    }             
                }

                echo $this->Html->link(
                    $this->Html->div(
                                $classCliente, 
                                $this->Html->image($img, array('alt' => '','id'=>'imgcli','class'=>'','style'=>'float:left')).
                                __($this->Form->label('Cliente', $clientex['Cliente']['nombre'], $classLabel,array('style'=>'float:right'))), 
                                array()), 
                    array('action' => 'view', $clientex['Cliente']['id']),
                    array('escape'=>false, 'style' => 'text-decoration:none; width:100%', 'id' => 'lnkCliente_'.$clientex['Cliente']['id'])

                    ); 
                ?>

            <?php endforeach;
        }else{?>
            Agregar Grupos de Clientes
       <?php }
    echo "</div>";

       if(count($clientesesDeshabilitados)!=0){ ?>
        <div id="divClientesBorradosTitulo" style="margin-top:20px; height:'<?php echo $GpoCliDeshaHeight; ?>'px">
            <h2>  Clientes Deshabilitados </h2>
            <input placeholder="Buscar Cliente Deshabilitado" id="txtBuscarClintesDeshabilitados" type="text" style="float:left; width:97%; padding-top:5px" />
            </div>
            <div id="divGpoClienteDeshabilitado_0">
            <?php
            $grupoMostrar=$clientesesDeshabilitados[0]['Grupocliente']['nombre'];
            echo $this->Html->link(
                        $clientesesDeshabilitados[0]['Grupocliente']['nombre'], 
                        array(
                            'controller' => 'grupoclientes', 
                            'action' => 'view', 
                            $clientesesDeshabilitados[0]['Grupocliente']['id']
                        ),
                        array('class' => 'lbl_gpo_view', 'id' => 'lnkGpoClienteDeshab_0')
                        );

            foreach ($clientesesDeshabilitados as $clientex): ?>
                <?php 
                if($grupoMostrar!=$clientex['Grupocliente']['nombre']){
                    $grupoMostrar=$clientex['Grupocliente']['nombre'];
                    ?> 
                    </div>
                    <div id='divGpoClienteDeshabilitado_<?php echo $clientex['Grupocliente']['id']; ?>' >
                    <?php
                    echo $this->Html->link(
                                    __($clientex['Grupocliente']['nombre']), 
                                    array(
                                        'controller' => 'grupoclientes', 
                                        'action' => 'index', 
                                        $clientex['Grupocliente']['id']
                                    ), 
                                    array('class' => 'lbl_gpo_view_deshabilitado', 
                                          'id' => 'lnkGpoClienteDeshab_'.$clientex['Grupocliente']['id']));
                }
                ?>
                <?php 
                    $divUsuario = $this->Html->div(
                                                "section_cli_view_deshabilitado", 
                                                $this->Html->image('cli_view.png', array('alt' => '','id'=>'imgcli','class'=>'','style'=>'float:left')).
                                                __($this->Form->label('Cliente', $clientex['Cliente']['nombre'], 'lbl_cli_view',array('style'=>'float:right'))), 
                                                array()
                                                );
                    echo $this->Form->postLink(
                        $divUsuario, 
                        array(
                            'action' => 'habilitar', 
                            $clientex['Cliente']['id']
                            ), 
                        array(
                            'escape'=>false,
                            'id' => 'lnkClienteDeshab_'.$clientex['Cliente']['id'],
                            'style' => 'width:100%'
                            ), 
                        __('Esta seguro que quiere Habilitar a '.$clientex['Cliente']['nombre'].'? Aparecera en todos los Informes', $clientex['Cliente']['id']
                            )
                    ); ?>    
            <?php endforeach;
            echo "</div>";
        }else{?>
            No hay Clientes Deshabilitados
       <?php  //echo "</div>";
        }
       ?>
</div>
</div>
<?php 
if($mostrarView){?>
<!--Div derecho que muestra los datos particulares de cada cliente-->
 <?php /**************************************************************************/ ?>
 <?php /*****************************Datos Personales*****************************/ ?>
 <?php /**************************************************************************/ ?>

<div class="clientes_header" style="width:70%;">
        <h2 class = "h2_clientes">
                <?php 
                  if($mostrarView){
                        echo __($cliente['Cliente']['nombre']);                        
                    } 
                    else{
                        echo __('Clientes'); 
                    }            
                ?>
        </h2>
</div>



<div class="clientes_view" style="width:70%;">
    <div class="" style="width:100%;height:30px;">
         <div class="cliente_view_tab"  onClick="showDatosCliente()" id="cliente_view_tab_cliente">
            <?php
               echo $this->Form->label(null, $text = 'Cliente',array('style'=>'text-align:center;margin-top:5px;cursor:pointer;')); 
               //$this->Html->image('cli_view.png', array('alt' => '','id'=>'imgcli','class'=>'','style'=>'margin-left: 50%;'));
             ?>
        </div>
         <div class="cliente_view_tab"  onClick="showDatosImpuesto()" id="cliente_view_tab_impuesto">
            <?php
                echo $this->Form->label(null, $text = 'Organismos',array('style'=>'text-align:center;margin-top:5px;cursor:pointer'));
                //$this->Html->image('ic_my_library_add_black_18dp.png', array('alt' => '','id'=>'imgcli','class'=>'','style'=>'margin-left: 50%;'));
             ?>
        </div>
         <div class="cliente_view_tab"  onClick="showDatosVenta()" id="cliente_view_tab_venta">
            <?php
                echo $this->Form->label(null, $text = 'Otros',array('style'=>'text-align:center;margin-top:5px;cursor:pointer'));             
             ?>
        </div>      
    </div>
    <div id="divCliente_Info" style="width:100%; overflow:auto">
	<table class="tbl_view" cellpadding="0" cellspacing="0">
    	<tr class="rowheaderdatosPersonales"> <!--1. Datos personales-->
        	<th colspan="6" class="tbl_view_th1" id = "viewTh1">
        		<h2 id="lblDatosPeronales" class="h2header">
        			<?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgDatosPersonales','class'=>'imgOpenClose'));?>
        			<?php echo __('Contribuyente'); ?>
        		</h2>
        	</th>                    
            <th class="tbl_view_th2" id = "viewTh2">
                    <a href="#" class="button_view" onClick="loadFormEditarPersona()"> 
                        <?php echo $this->Html->image(
                                            'edit_view.png', 
                                            array(
                                                'alt' => 'edit',
                                                'class'=>'img_edit'
                                                //,'style'=>'color:red;float:left;margin-top:10px'                                            
                                                )
                                            );
                        ?>
                    </a>
            </th>
            <th class="tbl_view_th2" id = "viewTh3">
                <?php echo $this->Form->postLink(
                                         $this->Html->image('ic_delete_black_24dp.png', array(
                                            'alt'   => 'DESHABILITAR',
                                            'class' => 'img_trash'
                                        )),
                                        array(
                                            'controller' => 'Clientes',
                                            'action' => 'deshabilitar',
                                            $cliente['Cliente']['id']
                                        ),
                                        array(
                                            'escape' => false, // Add this to avoid Cake from printing the img HTML code instead of the actual image,
                                            'style'=>'color:red;float:right;'
                                        ),
                                        __('Esta seguro que quiere Deshabilitar a '.$cliente['Cliente']['nombre'].'? Una vez deshabilitado no aparecera en ningun Informe', $cliente['Cliente']['id'])                                    
                                ); ?>
            </th>
        </tr>
        <tr class="datosPersonales" style="display: none/*inicialmente no se muestra*/"><!--1.1 Tabla datos clientes-->
            <td>
                <?php 
                echo $this->Form->create('Cliente',array('action'=>'edit','id'=>'saveDatosPersonalesForm', 'class' => 'form_popin'));            
                echo $this->Form->input('id',array('type'=>'hidden'));?>
                <table cellspacing="0" cellpadding="0" id="tableDatosPersonalesEdit" class = "tbl_datos_cli">
                    <tr>
                        <td><?php echo $this->Form->input('tipopersona',array(
                                                            'label'=>'Tipo de Persona',
                                                            'type'=>'select',
                                                            'style'=>'width:180px',
                                                            'options'=>array('juridica'=>'Juridica','fisica'=>'Fisica'),
                                                            )
                                            ); ?>
                        </td>
                        <td>
                            <?php echo $this->Form->input('tipopersonajuridica',array('label'=>'Tipo de Persona Jur&iacute;dica','style'=>'width:180px')); ?>
                            <?php echo $this->Form->input('dni',array('label'=>'DNI', 'style'=>'width:180px','maxlength'=>'8','class'=>'numeric')); ?>
                        </td>
                        <td>
                            <?php 
                            echo $this->Form->label(
                                    '',
                                    "Grupo: ".$cliente['Grupocliente']['nombre'],
                                    [
                                        'onClick'=>'loadFormCambiarGrupo();'
                                    ]);
                                            
                            ?> 
                        </td>
                    </tr>    
                    <tr>
                        <td><?php echo $this->Form->input('nombre',array('label'=>array(
                                                                            'text'=>'Apellido y Nombre o Raz&oacuten Social',
                                                                            'id'=>'clienteEditLabelNombre',
                                                                            'style'=>'width:200px')
                                                                            )); ?></td>
                        <td><?php echo $this->Form->input('cuitcontribullente',array('label'=>'CUIT','style'=>'width:180px','maxlength'=>'11','class'=>'numeric')); ?></td>
                        <td></td>
                    </tr>    
                    <tr> 
                        <td>
                         <?php 
                                 echo $this->Form->input('fchcumpleanosconstitucion', array(
                                            'class'=>'datepicker', 
                                            'type'=>'text',
                                            'value'=>date('d-m-Y',strtotime($this->request->data['Cliente']['fchcumpleanosconstitucion'])),
                                            'label'=>'Fecha de Nac. o de Constituci&oacute;n',                                    
                                            'readonly'=>'readonly')
                                 );?>
                        </td>
                        <td>
                            <?php 
                                 echo $this->Form->input('fchcorteejerciciofiscal', array(
                                            'class'=>'datepicker-day-month',
                                            'type'=>'text',
                                            'value'=>date('d-m',strtotime($this->request->data['Cliente']['fchcorteejerciciofiscal'].'-2006')),
                                            'label'=>'Fecha de Corte de Ejer. Fiscal',
                                            'readonly'=>'readonly')
                                 );?>
                        </td>    
                        <td>
                            <?php 
                                 echo $this->Form->input('honorario', array(
                                            'value'=>$this->request->data['Cliente']['honorario'],
                                            'label'=>'Honorario',                                    
                                            )
                                 );?>
                        </td>    
                    </tr>
                    <tr>
                        <td>
                            <?php
                            echo $this->Form->input('fchiniciocliente', array(
                                    'class'=>'datepicker',
                                    'value'=>date('d-m-Y',strtotime($this->request->data['Cliente']['fchiniciocliente'])),
                                    'type'=>'text',
                                    'label'=>'Cliente alta',
                                    'readonly'=>'readonly')
                            );?>
                        </td>
                        <td>
                            <?php
                            echo $this->Form->input('fchfincliente', array(
                                    'class'=>'datepicker-month-year',
                                    'value'=>$this->request->data['Cliente']['fchfincliente'],
                                    'type'=>'text',
                                    'label'=>'Cliente baja',
                                    'readonly'=>'readonly')
                            );?>
                        </td>
                    </tr>
                    <tr id="rowButtonsDetallesPersonales" style="display:none">
                        <td>
                        </td>
                        <td>&nbsp;</td>
                        <td align="right">  
                            <?php echo $this->Form->end(__('Aceptar')); ?>                                                  
                        </td>                        
                    </tr>
                </table>
            </td>
        </tr>        
 <?php /**************************************************************************/ ?>
 <?php /*****************************Domicilios***********************************/ ?>
 <?php /**************************************************************************/ ?>
        <tr  class="rowheaderdomicilios"> <!--2. Domicilio-->
            <th colspan="7" class="tbl_view_th1" id = "viewTh4">
                <h2 class="h2header" id="lblDomicilio">
                <?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgDomicilio','class'=>'imgOpenClose'));?>
                <?php echo __('Domicilios'); ?>
               </h2>
            </th>
            <th class="tbl_view_th2" id = "viewTh5">
                    <a href="#nuevo_domicilio" class="button_view"> 
                        <?php echo $this->Html->image('add_view.png', array('alt' => 'edit','class'=>'img_add'));?>
                    </a>
            </th>
        </tr>
       
        <tr class="domicilios" style="display: none/*inicialmente no se muestra*/"> <!--2.1 Tabla Domicilios-->
            <td colspan="7">
            <table id="relatedDomicilios" class="tbl_related">
                <head>
                     <tr class="domicilio">    
                        <th class="th_header"><?php echo __('Domicilio'); ?></th>     
                        <th><?php echo __('Provincia'); ?></th>  
                        <th><?php echo __('Localidad'); ?></th>     
                        <th><?php echo __('Superficie'); ?></th>     
                        <th><?php echo __('Acciones'); ?></th>    
                     </tr>  
                     <tr>
                        <th colspan="6"><hr color="#B6B6B6" width="100%"></th> 
                    </tr>
                </head>     
                <tbody>
            <?php if (!empty($cliente['Domicilio'])): ?>      
                <?php foreach ($cliente['Domicilio'] as $domicilio): ?>     
                     <tr id="rowdomicilio<?php echo $domicilio['id']; ?>" >    
                        <td><?php echo h($domicilio['calle']); ?></td> 
                        <td><?php echo h($domicilio['Localidade']['Partido']['nombre']); ?></td>
                        <td><?php echo h($domicilio['Localidade']['nombre']); ?></td> 
                        <td><?php echo h($domicilio['superficie']); ?></td> 
                        <td class="">
                            <a href="#"  onclick="loadFormDomicilio(<?php echo$domicilio['id']; ?>,<?php echo $domicilio['cliente_id'];?>)" class="button_view"> 
                             <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'img_edit'));?>
                            </a> 
                            <?php echo $this->Form->postLink(
                                         $this->Html->image('ic_delete_black_24dp.png', array(
                                            'alt' => 'Eliminar', 'class'=>'img_trash'
                                        )),
                                        array(
                                            'controller' => 'Domicilios',
                                            'action' => 'delete',
                                            $domicilio['id'],
                                            $domicilio['cliente_id']
                                        ),
                                        array(
                                            'class'=>'deleteDomicilio',                                            
                                            'escape' => false // Add this to avoid Cake from printing the img HTML code instead of the actual image
                                        ),
                                        __('Esta seguro que quiere eliminar este domicilio?')                                    
                                ); ?>
                        </td>
                    </tr>      
                <?php endforeach; ?>
            <?php endif; ?>   
                  </tbody>
             </table>  
            </td>
        </tr>
 <?php /**************************************************************************/ ?>
 <?php /*****************************Personas Relacionadas************************/ ?>
 <?php /**************************************************************************/ ?>        
        <tr class="rowheaderpersonas"> <!--4. Persona relacionada-->
            <th colspan="7" class="tbl_view_th1" id = "viewTh6">
                <h2 class="h2header" id="lblPersona">
                <?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgPersona','class'=>'imgOpenClose'));?>
                <?php echo __('Contactos'); ?>
               </h2>
            </th>
            <th class="tbl_view_th2" id = "viewTh7">
                <a href="#nuevo_persona" class="button_view"> 
                    <?php echo $this->Html->image('add_view.png', array('alt' => 'edit','class'=>'img_add'));?>
                </a>
            </th>
        </tr>
        <tr class="personas" style="display: none/*inicialmente no se muestra*/">
            <td colspan="7">
            <table id="relatedPersonas" class="tbl_related"> <!--Tabla Persona Relacionada-->
                <head>
                     <tr >    
                        <th><?php echo __('Tipo'); ?></th>     
                        <th><?php echo __('Nombre'); ?></th>  
                        <th><?php echo __('Movil'); ?></th>  
                        <th><?php echo __('Acciones'); ?></th>     
                     </tr>  
                     <tr>
                        <th colspan="6"><hr color="#B6B6B6" width="100%"></th> 
                     </tr>
                </head>     
                <tbody>
              <?php if (!empty($cliente['Personasrelacionada'])): ?>      
              <?php foreach ($cliente['Personasrelacionada'] as $persona): ?>     
                     <tr id="rowpersonarelacionada<?php echo h($persona['id']); ?>" >    
                        <td><?php echo h(ucfirst ($persona['tipo'])); ?></td>
                        <td><?php echo h($persona['nombre']); ?></td> 
                        <td><?php echo h($persona['movil']); ?></td>
                        <td class="">
                            <a href="#"  onclick="loadFormPersonaRelacionada(<?php echo$persona['id']; ?>,<?php echo $persona['cliente_id'];?>,'rowpersonarelacionada<?php echo h($persona['id']); ?>')" class="button_view"> 
                                <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'img_edit'));?> 
                            </a>      
                             <?php echo $this->Form->postLink(
                                         $this->Html->image('ic_delete_black_24dp.png', array(
                                            'alt' => 'Eliminar', 'class'=>'img_trash'
                                        )),
                                        array(
                                            'controller' => 'Personasrelacionadas',
                                            'action' => 'delete',
                                            $persona['id'],
                                            $persona['cliente_id']
                                        ),
                                        array(
                                            'escape' => false // Add this to avoid Cake from printing the img HTML code instead of the actual image
                                        ),
                                        __('Esta seguro que quiere eliminar esta persona relacionada?')                                    
                                ); ?>               
                        </td>
                    </tr>      
             <?php endforeach; ?>
            <?php endif; ?>
                </tbody>
             </table>  
            </td>
        </tr>
 <?php /**************************************************************************/ ?>
 <?php /*****************************Actividades Relacionadas************************/ ?>
 <?php /**************************************************************************/ ?>        
        <tr class="rowheaderactividades"> <!--4. Persona relacionada-->
            <th colspan="7" class="tbl_view_th1" id="viewTh8">
                <h2 class="h2header" id="lblActividad">
                <?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgActividad','class'=>'imgOpenClose'));?>
                <?php echo __('Actividades'); ?>
               </h2>
            </th>
            <th class="tbl_view_th2" id="viewTh9">
                <a href="#nuevo_actividad" class="button_view"> 
                    <?php echo $this->Html->image('add_view.png', array('alt' => 'edit','class'=>'img_add'));?>
                </a>
            </th>
        </tr>
        <tr class="actividades" style="display: none/*inicialmente no se muestra*/">
            <td colspan="7">
            <table id="relatedActividades" class="tbl_related"> <!--Tabla Persona Relacionada-->
                <head>
                     <tr >    
                        <th><?php echo __('Codigo'); ?></th>                          
                        <th><?php echo __('Actividad'); ?></th>                          
                        <th><?php echo __('Descripcion'); ?></th>                          
                        <th><?php echo __('Baja'); ?></th>
                        <th><?php echo __('Acciones'); ?></th>     
                     </tr>  
                     <tr>
                        <th colspan="6"><hr color="#B6B6B6" width="100%"></th> 
                     </tr>
                </head>     
                <tbody>
              <?php if (!empty($cliente['Actividadcliente'])): ?>      
              <?php foreach ($cliente['Actividadcliente'] as $actividad): ?>     
                     <tr id="rowActividadcliente<?php echo $actividad['id'] ?>" >    
                        <td><?php echo h($actividad['Actividade']['descripcion']); ?></td>
                         <td><?php echo h($actividad['Actividade']['nombre']); ?></td>
                         <td><?php echo h($actividad['descripcion']); ?></td>
                         <td><?php
                             if($actividad['baja']==""||$actividad['baja']=="0000-00-00"){

                             }else{
                                 echo $actividad['baja'];
                             } ?>
                         </td>
                         <td class="">
                             <a href="#"  onclick="loadFormActividadcliente(<?php echo $actividad['id'].",".$actividad['cliente_id'];?>)" class="button_view">
                                 <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'img_edit'));?>
                             </a>
                             <?php echo $this->Form->postLink(
                                         $this->Html->image('ic_delete_black_24dp.png', array(
                                            'alt' => 'Eliminar', 'class'=>'img_trash'
                                        )),
                                        array(
                                            'controller' => 'Actividadclientes',
                                            'action' => 'delete',
                                            $actividad['id'],
                                            $actividad['cliente_id']
                                        ),
                                        array(
                                            'escape' => false // Add this to avoid Cake from printing the img HTML code instead of the actual image
                                        ),
                                        __('Esta seguro que quiere eliminar esta actividad?')                                    
                                ); ?>
                        </td>
                    </tr>      
             <?php endforeach; ?>
            <?php endif; ?>   
                </tbody>
             </table>  
            </td>
        </tr>       
 <?php /**************************************************************************/ ?>
 <?php /*****************************AFIP*****************************************/ ?>
 <?php /**************************************************************************/ ?>
 	<tr class="rowheaderafip" style="display: none/*inicialmente no se muestra*/"> <!--7. AFIP-->
    	<th colspan="7" class="tbl_view_th1">
    		<h2 class="h2header" id="lblAFIP">
   				<?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgAFIP','class'=>'imgOpenClose'));?>
    			<?php echo __('AFIP'); ?></h2></th>
   		<th class="tbl_view_th2">
            <th>
                    <a href="#nuevoImpcliAfip" class="button_view"> 
                        <?php echo $this->Html->image('add_view.png', array('alt' => 'edit','class'=>'img_add','title'=>'Agregar impuesto'));?>
                    </a>                                            
            </th>
        </th>    		
    </tr> 
    <tr class="afip" style="display: none/*inicialmente no se muestra*/">
        <td>
        <table class="tbl_related"> <!--7.1 Tabla Organismos-->

        <?php if (!empty($cliente['Organismosxcliente'])): ?>
	        <?php foreach ($cliente['Organismosxcliente'] as $organizmo): ?>  
    	        <?php if ($organizmo['tipoorganismo']=='afip'): ?>
         <tr class="afip">    
            <td colspan="7">
                <?php echo $this->Form->create('Organismosxcliente',array('action'=>'edit','id'=>'formOrganismoAFIP', 'class' => 'form_popin'));?>
                <?php echo $this->Form->input('id',array('type'=>'hidden','default'=>$organizmo['id'],'label'=>false)); ?>
                <table cellpadding="0" cellspacing="0">
                    <tr>      
                         <td><?php echo $this->Form->input('usuario',array('default'=>$organizmo['usuario'],'label'=>'CUIT')); ?></td>
                         <td><?php echo $this->Form->input('clave',array('default'=>$organizmo['clave'],'label'=>'Clave')); ?></td>
                         <td><?php echo $this->Form->end('Guardar');?></td>
                    </tr>
                </table>  
            </td>    
          </tr>
        </table>
        </td>
    </tr>   		       
    <tr class="afip"  style="display: none/*inicialmente no se muestra*/">  <!--7.2 Impuestos del Organismo -->
        <td colspan="7"> 
        <table id="tablaImpAfip" class="tbl_related">    
            <tr>    
                <th><?php echo __('Impuesto'); ?></th>
                <th><?php echo __('Alta'); ?></th>                   
                <th><?php echo __('Acciones'); ?></th>
            </tr>  
            <tr>
                <th colspan="6"><hr color="#B6B6B6" width="100%"></th> 
            </tr>
            <?php if (!empty($cliente['Impcli'])): ?>                            
                <?php foreach ($cliente['Impcli'] as $impcli): ?>
                    <?php if ($impcli['Impuesto']['organismo']=='afip'): ?>    
                         <tr id="rowImpcli<?php echo $impcli['id']?>" >                                                
                            <td><?php echo $impcli['Impuesto']['nombre']; ?>
                             <?php
                                if($impcli['Impuesto']['id']==4/*Monotributo*/){
                                    echo "<span>-".$impcli['categoriamonotributo']."</span>";
                                }
                                if($impcli['Impuesto']['id']==14/*Autonomo*/){
                                    if(isset($impcli['Autonomocategoria']['codigo'])){
                                        echo "<span>-".$impcli['Autonomocategoria']['codigo']."</span>";
                                    }
                                }
                                ?></td>
                            <td>
                            <?php if(count($impcli['Periodosactivo'])){
                                echo $impcli['Periodosactivo'][0]['desde'];
                            }?>
                            </td>                                
                            <td>
                                <a href="#"  onclick="loadFormImpuesto(<?php echo$impcli['id']; ?>,<?php echo $impcli['cliente_id'];?>)" class="button_view"> 
                                 <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'img_edit', 'title' => 'Editar'));?>
                                </a>
                                <a href="#"  onclick="loadFormImpuestoPeriodos(<?php echo$impcli['id']; ?>)" class="button_view"> 
                                 <?php echo $this->Html->image('calendario.png', array('alt' => 'open','class'=>'img_calendario'));?>
                                </a>
                                <a href="#"  onclick="deleteImpcli(<?php echo$impcli['id']; ?>)" class="button_view"> 
                                 <?php echo $this->Html->image('delete.png', array('alt' => 'open','class'=>'img_trash'));?>
                                </a>
                                <?php
                                if($impcli['impuesto_id']==5/*Ganancias Sociedades*/||$impcli['impuesto_id']==160/*Ganancias Personas Físicas*/){?>
                                    <a href="#"  onclick="loadFormImpuestoCuentasganancias(<?php echo$impcli['cliente_id']; ?>)" class="button_view">
                                        <?php echo $this->Html->image('cuentas.png', array('alt' => 'open','class'=>'img_ganancias'));?>
                                    </a>
                                <?php }
                                if($impcli['impuesto_id']==160/*Ganancias Personas Físicas*/){?>
                                    <a href="#"  onclick="loadFormImpuestoDeducciones(<?php echo$impcli['id']; ?>)" class="button_view">
                                        <?php echo $this->Html->image('deduccion.jpg', array('alt' => 'deduccion','class'=>'img_ganancias'));?>
                                    </a>
                                    <!--<a href="#"  onclick="loadFormImpuestoQuebrantos(<?php echo$impcli['id']; ?>)" class="button_view">
                                        <?php echo $this->Html->image('quebranto.png', array('alt' => 'quebranto','class'=>'imgedit'));?>
                                    </a>-->
                                <?php }
                                ?>
                            </td>
                        </tr>
                     <?php endif;    ?>
                <?php endforeach; ?>
            <?php endif; ?>
            </table>
        </td>
    </tr>
        <!--FIN Impuestos del Organizmo -->
        <?php endif; ?>   		
    <?php endforeach; ?>
<?php endif; ?>   	    
<?php /**************************************************************************/ ?>
<?php /*****************************DGR******************************************/ ?>
<?php /**************************************************************************/ ?>        
        <tr class="rowheaderdgr"  style="display: none/*inicialmente no se muestra*/"><!--8. DGR-->
        	<th  colspan="7" class="tbl_view_th1">
        		<h2 class="h2header" id="lblDGR">
       				<?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgDGR','class'=>'imgOpenClose'));?>
        			<?php echo __('DGR'); ?></h2></th>
	   		<th class="tbl_view_th2">
                <th>
                    <a href="#nuevo_DGR" class="button_view"> 
                        <?php echo $this->Html->image('add_view.png', array('alt' => 'edit','class'=>'img_add'));?>
                    </a>
                </th>
            </th>		
        </tr>
        <tr class="dgr"  style="display: none/*inicialmente no se muestra*/">
            <td>
                <table class="tbl_related"> <!--7.1 Tabla Organismos-->

                <?php if (!empty($cliente['Organismosxcliente'])): ?>
                    <?php foreach ($cliente['Organismosxcliente'] as $organizmo): ?>  
                        <?php if ($organizmo['tipoorganismo']=='dgr'): ?>
                 <tr class="dgr">    
                    <td colspan="7">
                        <?php echo $this->Form->create('Organismosxcliente',array('action'=>'edit','id'=>'formOrganismoDGR', 'class' => 'form_popin'));?>
                        <?php echo $this->Form->input('id',array('type'=>'hidden','default'=>$organizmo['id'],'label'=>false)); ?>
                        <table>
                            <tr>      
                                 <td><?php echo $this->Form->input('usuario',array('default'=>$organizmo['usuario'],'label'=>'CUIT')); ?></td>
                                 <td><?php echo $this->Form->input('clave',array('default'=>$organizmo['clave'],'label'=>'Clave')); ?></td>
                                 <td><?php echo $this->Form->end('Guardar');?></td>
                            </tr>
                        </table>  
                    </td>    
                  </tr>
                </table>       
            </td>
        </tr>         
        <tr class="dgr"  style="display: none/*inicialmente no se muestra*/">
            <td colspan="7"> 
                <table id="tablaImpDGR" class="tbl_related">   <!--8.2 Impuestos del Organismo -->  
                    <tr>    
                        
                        <th><?php echo __('Impuesto'); ?></th>
                        <th><?php echo __('Alta'); ?></th>                        
                        <th><?php echo __('Acciones'); ?></th>
                    </tr>
                    <tr>
                        <th colspan="6"><hr color="#B6B6B6" width="100%"></th> 
                    </tr>  
                    <?php if (!empty($cliente['Impcli'])): ?>                            
                        <?php foreach ($cliente['Impcli'] as $impcli): ?>
                            <?php if ($impcli['Impuesto']['organismo']=='dgr'): ?>    
                                 <tr id="rowImpcli<?php echo $impcli['id']?>">                                                
                                    <td><?php echo $impcli['Impuesto']['nombre']; ?></td>
                                    <td>
                                    <?php if(count($impcli['Periodosactivo'])){
                                        echo $impcli['Periodosactivo'][0]['desde'];
                                    }?>
                                    </td>                                   
                                    <td>
                                        <a href="#"  onclick="loadFormImpuesto(<?php echo$impcli['id']; ?>,<?php echo $impcli['cliente_id'];?>)" class="button_view"> 
                                         <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'img_edit'));?>
                                            </a>
                                        <a href="#"  onclick="loadFormImpuestoPeriodos(<?php echo$impcli['id']; ?>)" class="button_view"> 
                                         <?php echo $this->Html->image('calendario.png', array('alt' => 'open','class'=>'img_calendario'));?>
                                        </a>
                                        <a href="#"  onclick="deleteImpcli(<?php echo$impcli['id']; ?>)" class="button_view"> 
                                         <?php echo $this->Html->image('delete.png', array('alt' => 'open','class'=>'img_trash'));?>
                                        </a>
                                        <?php 
                                        //aca vamos a agregar la opcion de manejar las Provincias de un impuesto que debe relacionar Provincias 
                                        if($impcli['impuesto_id']==174/*Convenio Multilateral*/||$impcli['impuesto_id']==21/*Convenio Multilateral*/){?>
                                        <a href="#"  onclick="loadFormImpuestoProvincias(<?php echo$impcli['id']; ?>)" class="button_view"> 
                                         <?php echo $this->Html->image('mapa_regiones.png', array('alt' => 'open','class'=>'img_prov'));?>
                                        </a>
                                        <?php }
                                        ?>
                                    </td>
                                </tr>
                             <?php endif;    ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            </td>
        </tr>
        <!--FIN Impuestos del Organismo -->
		        <?php endif; ?>   		
	        <?php endforeach; ?>
        <?php endif; ?>  
 <?php /**************************************************************************/ ?>
 <?php /*****************************DGRM*****************************************/ ?>
 <?php /**************************************************************************/ ?>
        <tr class="rowheaderdgrm" style="display: none/*inicialmente no se muestra*/"><!--9. DGRM-->
        	<th  colspan="7" class="tbl_view_th1">
        		<h2 class="h2header" id="lblDGRM">
       				<?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgDGRM','class'=>'imgOpenClose'));?>
        			<?php echo __('DGRM'); ?></h2></th>
	   		<th class="tbl_view_th2">
                <th>
                    <a href="#nuevo_DGRM" class="button_view"> 
                                <?php echo $this->Html->image('add_view.png', array('alt' => 'add','class'=>'img_add'));?>
                    </a>
                </th>
            </th>                                                                                       
        </tr> 
        <tr class="dgrm" style="display: none/*inicialmente no se muestra*/"><!--9.1 Tabla DGRM -->
            <td> 
                <table class="tbl_related"> <!--7.1 Tabla Organismos-->

                <?php if (!empty($cliente['Organismosxcliente'])): ?>
                    <?php foreach ($cliente['Organismosxcliente'] as $organizmo): ?>  
                        <?php if ($organizmo['tipoorganismo']=='dgrm'): ?>
                 <tr class="dgrm">    
                    <td colspan="7">
                        <?php echo $this->Form->create('Organismosxcliente',array('action'=>'edit','id'=>'formOrganismoDGRM', 'class' => 'form_popin'));?>
                        <?php echo $this->Form->input('id',array('type'=>'hidden','default'=>$organizmo['id'],'label'=>false)); ?>
                        <table>
                            <tr>      
                                 <td><?php echo $this->Form->input('usuario',array('default'=>$organizmo['usuario'],'label'=>'Usuario')); ?></td>
                                 <td><?php echo $this->Form->input('clave',array('default'=>$organizmo['clave'],'label'=>'Clave')); ?></td>
                                 <td><?php echo $this->Form->end('Guardar',array(),array('style'=>'margin:0px'));?></td>
                            </tr>
                        </table>  
                    </td>    
                  </tr>
                </table> 
            </td>
        </tr>
        <!--9.2 Impuestos del Organismo -->        
        <tr class="dgrm" style="display: none/*inicialmente no se muestra*/">
            <td colspan="7"> 
            <table id="tablaImpDGRM" class="tbl_related">    
                <tr>     
                    <th><?php echo __('Impuesto'); ?></th>
                    <th><?php echo __('Alta'); ?></th>                   
                    <th><?php echo __('Acciones'); ?></th>        
                </tr>  
                <tr>
                    <th colspan="6"><hr color="#E4E4E4" width="100%"></th> 
                </tr> 
                <?php if (!empty($cliente['Impcli'])): ?>                            
                    <?php foreach ($cliente['Impcli'] as $impcli): ?>
                        <?php if ($impcli['Impuesto']['organismo']=='dgrm'): ?>    
                             <tr id="rowImpcli<?php echo $impcli['id']?>">                                                
                                <td><?php echo $impcli['Impuesto']['nombre']; ?></td>
                                <td>
                                <?php if(count($impcli['Periodosactivo'])){
                                    echo $impcli['Periodosactivo'][0]['desde'];
                                }?>
                                </td>                               
                                <td>
                                    <a href="#"  onclick="loadFormImpuesto(<?php echo$impcli['id']; ?>,<?php echo $impcli['cliente_id'];?>)" class="button_view"> 
                                     <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'img_edit'));?>
                                    </a>
                                     <a href="#"  onclick="loadFormImpuestoPeriodos(<?php echo$impcli['id']; ?>)" class="button_view"> 
                                     <?php echo $this->Html->image('calendario.png', array('alt' => 'open','class'=>'img_calendario'));?>
                                    </a>
                                    <a href="#"  onclick="deleteImpcli(<?php echo$impcli['id']; ?>)" class="button_view"> 
                                        <?php echo $this->Html->image('delete.png', array('alt' => 'open','class'=>'img_trash'));?>
                                    </a>
                                    <?php
                                    //aca vamos a agregar la opcion de manejar las Provincias de un impuesto que debe relacionar Provincias 
                                    if($impcli['impuesto_id']==6/*Actividades Varias*/){?>
                                    <a href="#"  onclick="loadFormImpuestoLocalidades(<?php echo$impcli['id']; ?>)" class="button_view"> 
                                     <?php echo $this->Html->image('localidad.png', array('alt' => 'open','class'=>'img_localidad'));?>
                                    </a>
                                    <?php } ?>
                                </td>
                            </tr>
                         <?php endif;    ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                </table>
            </td>
        </tr>
        <!--FIN Impuestos del Organizmo -->
		        <?php endif; ?>   		
	        <?php endforeach; ?>
        <?php endif; ?>
 <?php /**************************************************************************/ ?>
 <?php /*****************************Sindicatos***********************************/ ?>
 <?php /**************************************************************************/ ?>        
        <tr  class="rowheadersindicatos" style="display: none/*inicialmente no se muestra*/" ><!--9.1. SINDICATO-->
            <th  colspan="7" class="tbl_view_th1">
                <h2 class="h2header" id="lblSINDICATO">
                    <?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'ImgSindicatos','class'=>'imgOpenClose'));?>
                    <?php echo __('Sindicatos'); ?></h2></th>
            <th class="tbl_view_th2">
                <th>
                    <a href="#nuevo_SINDICATO" class="button_view"> 
                        <?php echo $this->Html->image('add_view.png', array('alt' => 'add','class'=>'img_add'));?>
                    </a>
                </th>
            </th>
        </tr>
        <!--9.2 Impuestos del Organismo -->        
        <tr class="sindicatos" style="display: none/*inicialmente no se muestra*/">
            <td colspan="7">

            <table id="tablaImpSINDICATO" class="tbl_related">
                <tr>     
                    <th><?php echo __('Impuesto'); ?></th>
                    <th><?php echo __('Alta'); ?></th>                   
                    <th><?php echo __('Usuario'); ?></th>                   
                    <th><?php echo __('Clave'); ?></th>                   
                    <th><?php echo __('Acciones'); ?></th>                          
                </tr>  
                <tr>
                    <th colspan="6"><hr color="#E4E4E4" width="100%"></th> 
                </tr> 
                <?php if (!empty($cliente['Impcli'])): ?>                            
                    <?php foreach ($cliente['Impcli'] as $impcli): ?>
                        <?php if ($impcli['Impuesto']['organismo']=='sindicato'): ?>    
                             <tr id="rowImpcli<?php echo $impcli['id']?>">                                                
                                <td><?php echo $impcli['Impuesto']['nombre']; ?></td>
                                <td>
                                <?php if(count($impcli['Periodosactivo'])){
                                    echo $impcli['Periodosactivo'][0]['desde'];
                                }?>
                                </td> 
                                <td><?php echo $impcli['usuario']; ?></td>
                                <td><?php echo $impcli['clave']; ?></td>
                                <td>
                                    <a href="#"  onclick="loadFormImpuesto(<?php echo$impcli['id']; ?>,<?php echo $impcli['cliente_id'];?>)" class="button_view"> 
                                        <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'img_edit'));?>
                                    </a>
                                    <a href="#"  onclick="loadFormImpuestoPeriodos(<?php echo$impcli['id']; ?>)" class="button_view"> 
                                        <?php echo $this->Html->image('calendario.png', array('alt' => 'open','class'=>'img_calendario'));?>
                                    </a>
                                    <a href="#"  onclick="deleteImpcli(<?php echo$impcli['id']; ?>)" class="button_view"> 
                                        <?php echo $this->Html->image('delete.png', array('alt' => 'open','class'=>'img_trash'));?>
                                    </a>
                                </td>
                            </tr>
                         <?php endif;    ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                </table>
            </td>
        </tr>
        <!--FIN Impuestos del Organizmo -->
 <?php /**************************************************************************/ ?>
 <?php /*****************************Bancos***************************************/ ?>      
 <?php /**************************************************************************/ ?>                   
        <tr  class="rowheaderbancos" style="display: none/*inicialmente no se muestra*/" ><!--9.1. BANCO-->
            <th  colspan="7" class="tbl_view_th1">
                <h2 class="h2header" id="lblBANCO">
                    <?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgBancos','class'=>'imgOpenClose'));?>
                    <?php echo __('Bancos'); ?>
                </h2>
            </th>
            <th class="tbl_view_th2">
                <th>
                    <a href="#nuevo_Banco" class="button_view"> 
                        <?php echo $this->Html->image('add_view.png', array('alt' => 'add','class'=>'img_add'));?>
                    </a>
                </th>
            </th>        
        </tr> 
        <tr class="bancos" style="display: none/*inicialmente no se muestra*/">
            <td colspan="6"> 
            <table id="tablaImpBanco" class="tbl_related">    
                <tr>     
                    <th><?php echo __('Impuesto'); ?></th>
                    <th><?php echo __('Alta'); ?></th>    
                    <th><?php echo __('Usuario'); ?></th>                   
                    <th><?php echo __('Clave'); ?></th>                    
                    <th><?php echo __('Acciones'); ?></th>                                
                                         
                </tr>  
                <tr>
                    <th colspan="6"><hr color="#E4E4E4" width="100%"></th> 
                </tr> 
                <?php if (!empty($cliente['Impcli'])): ?>                            
                    <?php foreach ($cliente['Impcli'] as $impcli): ?>
                        <?php if ($impcli['Impuesto']['organismo']=='banco'): ?>    
                            <tr id="rowImpcli<?php echo $impcli['id']?>">                                                
                                <td><?php echo $impcli['Impuesto']['nombre']; ?></td>
                                <td>
                                <?php if(count($impcli['Periodosactivo'])){
                                    echo $impcli['Periodosactivo'][0]['desde'];
                                }?>
                                </td>    
                                <td><?php echo $impcli['usuario']; ?></td>
                                <td><?php echo $impcli['clave']; ?></td>                           
                                <td>
                                    <a href="#"  onclick="loadFormImpuesto(<?php echo $impcli['id']; ?>,<?php echo $impcli['cliente_id'];?>)" class="button_view">
                                        <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'img_edit'));?>
                                    </a>
                                    <a href="#"  onclick="loadFormImpuestoPeriodos(<?php echo $impcli['id']; ?>)" class="button_view">
                                        <?php echo $this->Html->image('calendario.png', array('alt' => 'open','class'=>'img_calendario'));?>
                                    </a>
                                    <a href="#"  onclick="deleteImpcli(<?php echo $impcli['id']; ?>)" class="button_view">
                                        <?php echo $this->Html->image('delete.png', array('alt' => 'open','class'=>'img_trash'));?>
                                    </a>
                                    <a href="#"  onclick="loadCbus(<?php echo $impcli['id']; ?>)" class="button_view">
                                        <?php echo $this->Html->image('cuentabancaria.png', array('alt' => 'open','class'=>'img_cbu'));?>
                                    </a>
                                </td>
                            </tr>                            
                         <?php endif;    ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                </table>
            </td>
        </tr>
        <!--FIN Impuestos del Organizmo -->            
 <?php /**************************************************************************/ ?>
 <?php /*****************************Puntos de Ventas*****************************/ ?>
 <?php /**************************************************************************/ ?>                
        <tr class="rowheaderpuntosdeventas" style="display: none/*inicialmente no se muestra*/" ><!--15. Puntos de Ventas-->
            <th colspan="7" class="tbl_view_th1">
                <h2 class="h2header" id="lblPuntosdeventas">
                    <?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgPuntosdeventas','class'=>'imgOpenClose'));?>
                    <?php echo __('Puntos de Ventas'); ?>                    
                </h2>
            </th>
            <th class="tbl_view_th2">
                <a href="#nuevo_puntodeventa" class="button_view"> 
                    <?php echo $this->Html->image('add_view.png', array('alt' => 'add','class'=>'img_add'));?>
                </a>
            </th>
        </tr>
        <tr class="puntosdeventa" style="display: none/*inicialmente no se muestra*/">
            <td>
                <table class="tbl_related" id="tablepuntosdeventas">
                <tr class="puntosdeventa">

                    <th><?php echo __('Numero'); ?></th>
                    <th><?php echo __('Sistema'); ?></th>
                    <th><?php echo __('Domicilio'); ?></th>
                    <th class=""><?php echo __('Acciones'); ?></th>
                </tr>
                <tr>
                    <th colspan="7"><hr color="#E4E4E4" width="100%"></th> 
                </tr> 
                 <?php if (!empty($cliente['Puntosdeventa'])): ?>
                    <?php foreach ($cliente['Puntosdeventa'] as $puntodeventa): ?>
                    <tr class="puntosdeventa" id="rowPuntodeVenta<?php echo $puntodeventa['id']; ?>">
                        
                        <td><?php echo $puntodeventa['nombre']; ?></td>
                        <td><?php echo $puntodeventa['sistemafacturacion']; ?></td>
                        <td><?php echo $puntodeventa['Domicilio']['calle'].'-'.$puntodeventa['Domicilio']['Localidade']['Partido']['nombre'].'-'.$puntodeventa['Domicilio']['Localidade']['nombre']; ?></td>
                         <td >
                            <a href="#"  onclick="loadFormPuntoDeVenta(<?php echo$puntodeventa['id']; ?>)" class="button_view" id="editLinkPuntoVenta<?php echo$puntodeventa['id']; ?>"> 
                             <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'img_edit'));?>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tr>
                </table>
            </td>
        </tr>
        <?php /**************************************************************************/ ?>
        <?php /*****************************SubClientes***********************************/ ?>
        <?php /**************************************************************************/ ?>
        <tr class="rowheadersubclientes" style="display: none/*inicialmente no se muestra*/" ><!--15. Sub Clientes-->
            <th colspan="7" class="tbl_view_th1">
                <h2 class="h2header" id="lblSubclientes">
                    <?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgSubclientes','class'=>'imgOpenClose'));?>
                    <?php echo __('Clientes'); ?>
                </h2>
            </th>
            <th class="tbl_view_th2">
                <a href="#nuevo_subcliente" class="button_view">
                    <?php echo $this->Html->image('add_view.png', array('alt' => 'add','class'=>'img_add'));?>
                </a>
            </th>
        </tr>

        <tr class="subcliente" style="display: none/*inicialmente no se muestra*/">
            <td colspan = "20">
                <?php
                echo $this->Form->input('tablaSubclienteVacia',array('value'=>1,'type'=>'hidden'));
                ?>
                <table id="subclientesDatatable"  class="tbl_related">
                    <thead>
                    <tr class="subcliente">
                        <th><?php echo __('CUIT'); ?></th>
                        <th><?php echo __('DNI'); ?></th>
                        <th><?php echo __('Nombre'); ?></th>
                        <th class=""><?php echo __('Acciones'); ?></th>
                    </tr>
                    </thead>
                    <tfoot>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </td>
        </tr>
        <?php /**************************************************************************/ ?>
        <?php /*****************************Provedores***********************************/ ?>
        <?php /**************************************************************************/ ?>
        <tr class="rowheaderprovedores" style="display: none/*inicialmente no se muestra*/" ><!--16. Provedores-->
            <th colspan="7" class="tbl_view_th1">
                <h2 class="h2header" id="lblProvedores">
                    <?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgProvedores','class'=>'imgOpenClose'));?>
                    <?php echo __('Proveedores'); ?>
                </h2>
            </th>
            <th class="tbl_view_th2">
                <a href="#nuevo_provedor" class="button_view">
                    <?php echo $this->Html->image('add_view.png', array('alt' => 'add','class'=>'img_add'));?>
                </a>
            </th>
        </tr>
       
        <tr class="rowheaderprovedores" style="display: none/*inicialmente no se muestra*/">
            <td class="provedor">
                <?php
                echo $this->Form->input('tablaProvedoresVacia',array('value'=>1,'type'=>'hidden'));
                ?>
                <table id="provedoresDatatable" class="tbl_related">
                    <thead>
                    <tr class="provedor">
                        <th><?php echo __('DNI'); ?></th>
                        <th><?php echo __('Nombre'); ?></th>
                        <th><?php echo __('CUIT'); ?></th>
                        <th class=""><?php echo __('Acciones'); ?></th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr class="provedor">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </tfoot>
                    <tbody></tbody>
                </table>
            </td>
        </tr>
        <?php /**************************************************************************/ ?>
        <?php /*****************************Empleados***********************************/ ?>
        <?php /**************************************************************************/ ?>
        <tr class="rowheaderempleados" style="display: none/*inicialmente no se muestra*/" ><!--17. Empleados-->
            <th colspan="7" class="tbl_view_th1">
                <h2 class="h2header" id="lblEmpleados">
                    <?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgEmpleados','class'=>'imgOpenClose'));?>
                    <?php echo __('Empleados'); ?>
                </h2>
            </th>
            <th class="tbl_view_th2">
                <a class="button_view" onclick="loadFormAddEmpleado();">
                    <?php echo $this->Html->image('add_view.png', array('alt' => 'add','class'=>'img_add'));?>
                </a>
            </th>
        </tr>
        <tr class="rowheaderempleados" style="display: none/*inicialmente no se muestra*/">
            <td class="empleado">
                <table id="relatedEmpleados" class="tbl_related">
                    <thead>
                    <tr class="empleado">
                        <th><?php echo __('N&deg;'); ?></th>
                        <th><?php echo __('Apellido y Nombre'); ?></th>
                        <th><?php echo __('DNI'); ?></th>
                        <th><?php echo __('CUIL'); ?></th>
                        <th><?php echo __('F&deg; Ingreso'); ?></th>
                        <th class=""><?php echo __('Acciones'); ?></th>
                    </tr>
                    </thead>
                    <tfoot>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tfoot>
                    <tbody>
                    <?php if (!empty($cliente['Empleado'])): ?>
                        <?php foreach ($cliente['Empleado'] as $empleado): ?>
                            <tr class="empleado" id="rowEmpleado<?php echo $empleado['id']; ?>">
                                <td><?php echo $empleado['legajo']; ?></td>
                                <td><?php echo $empleado['nombre']; ?></td>
                                <td><?php echo $empleado['dni']; ?></td>
                                <td><?php echo $empleado['cuit']; ?></td>
                                <td><?php echo date('d-m-Y',strtotime($empleado['fechaingreso'])) ?></td>
                                <td >
                                    <a href="#"  onclick="loadFormEmpleado(<?php echo $empleado['id']; ?>)" class="button_view">
                                        <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'img_edit'));?>
                                    </a>
                                    <?php echo $this->Form->postLink(
                                        $this->Html->image('ic_delete_black_24dp.png', array(
                                            'alt' => 'Eliminar',
                                            'class'=> 'img_trash'
                                        )),
                                        array(
                                            'controller' => 'Empleados',
                                            'action' => 'delete',
                                            $empleado['id'],
                                        ),
                                        array(
                                            'class'=>'deleteEmpleado',
                                            'escape' => false // Add this to avoid Cake from printing the img HTML code instead of the actual image
                                        ),
                                        __('Esta seguro que quiere eliminar este provedor?')
                                    ); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </td>
        </tr>
        <?php /**************************************************************************/ ?>
        <?php /*****************************Bienes de Uso********************************/ ?>
        <?php /**************************************************************************/ ?>
        <tr class="rowheaderbienesdeusos" style="display: none/*inicialmente no se muestra*/" ><!--18. Bienes de usos-->
            <th colspan="7" class="tbl_view_th1">
                <h2 class="h2header" id="lblBienesdeusos">
                    <?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgBienesdeusos','class'=>'imgOpenClose'));?>
                    <?php echo __('Bienes de usos'); ?>
                </h2>
            </th>
            <th class="tbl_view_th2">
                <a class="button_view" onclick="loadFormBiendeuso(<?php echo $cliente['Cliente']['id'].",0,0"?>);">
                    <?php echo $this->Html->image('add_view.png', array('alt' => 'add','class'=>'img_add'));?>
                </a>
            </th>
        </tr>
        <tr class="rowheaderbienesdeusos" style="display: none/*inicialmente no se muestra*/">
            <td class="biendeuso">
                <table id="relatedBienesdeusos" class="tbl_related">
                    <thead>
                    <tr class="biendeuso">
                        <th><?php echo __('Tipo'); ?></th>
                        <th><?php echo __('Periodo'); ?></th>
                        <th><?php echo __('Descripcion'); ?></th>
                        <th class=""><?php echo __('Acciones'); ?></th>
                    </tr>
                    </thead>
                    <tfoot>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tfoot>
                    <tbody>
                    <?php if (!empty($cliente['Bienesdeuso'])): ?>
                        <?php foreach ($cliente['Bienesdeuso'] as $bienesdeuso): ?>
                            <tr class="biendeuso" id="rowBiendeuso<?php echo $bienesdeuso['id']; ?>">
                                <td><?php echo $bienesdeuso['tipo']; ?></td>
                                <td><?php echo $bienesdeuso['periodo']; ?></td>
                                <?php
                                $descripcionBDU = "";
                                //todo separar en case desc Bien de uso
                                //esto seria mas correcto si lo separamos en un case
                                switch ($bienesdeuso['tipo']){
                                    //Empresa
                                    case 'Rodado':
                                        if($bienesdeuso['patente']!="")
                                            $descripcionBDU  .= $bienesdeuso['patente'];
                                        if($bienesdeuso['aniofabricacion']!="")
                                            $descripcionBDU  .= " -".$bienesdeuso['aniofabricacion'];
                                        
                                        break;
                                    case 'Inmueble':
                                        if($bienesdeuso['calle']!="")
                                            $descripcionBDU  .= $bienesdeuso['calle'];
                                        if($bienesdeuso['numero']!="")
                                            $descripcionBDU  .= " -".$bienesdeuso['numero'];
                                        if($bienesdeuso['catastro']!="")
                                            $descripcionBDU  .= " -".$bienesdeuso['catastro'];
                                       
                                        break;
                                    case 'Instalaciones':
                                        if($bienesdeuso['descripcion']!="")
                                            $descripcionBDU  .= $bienesdeuso['descripcion'];
                                        
                                        break;
                                    case 'Otros bienes de uso Muebles':
                                        if($bienesdeuso['descripcion']!="")
                                            $descripcionBDU  .= $bienesdeuso['descripcion'];
                                       
                                        break;
                                    case 'Otros bienes de uso Maquinas':
                                        if($bienesdeuso['descripcion']!="")
                                            $descripcionBDU  .= $bienesdeuso['descripcion'];
                                       
                                        break;
                                    case 'Otros bienes de uso Activos Biologicos':
                                        if($bienesdeuso['descripcion']!="")
                                            $descripcionBDU  .= $bienesdeuso['descripcion'];
                                         
                                        break;
                                    //NO empresa
                                    case 'Inmuebles':
                                        if($bienesdeuso['calle']!="")
                                            $descripcionBDU  .= $bienesdeuso['calle'];
                                        if($bienesdeuso['numero']!="")
                                            $descripcionBDU  .= " -".$bienesdeuso['numero'];
                                        if($bienesdeuso['catastro']!="")
                                            $descripcionBDU  .= " -".$bienesdeuso['catastro'];
                                        break;
                                    case 'Automotor':
                                        if($bienesdeuso['patente']!="")
                                            $descripcionBDU  .= $bienesdeuso['patente'];
                                        if($bienesdeuso['aniofabricacion']!="")
                                            $descripcionBDU  .= " -".$bienesdeuso['aniofabricacion'];
                                        break;
                                    case 'Naves, Yates y similares':
                                        if($bienesdeuso['marca']!="")
                                            $descripcionBDU  .= $bienesdeuso['marca'];
                                        if($bienesdeuso['modelo']!="")
                                            $descripcionBDU  .= " -".$bienesdeuso['modelo'];
                                         
                                        break;
                                    case 'Aeronave':
                                        if($bienesdeuso['matricula']!="")
                                            $descripcionBDU  .= $bienesdeuso['matricula'];
                                        if($bienesdeuso['fechaadquisicion']!="")
                                            $descripcionBDU  .= " -".$bienesdeuso['fechaadquisicion'];
                                         
                                        break;
                                    case 'Bien mueble registrable':
                                        if($bienesdeuso['descripcion']!="")
                                            $descripcionBDU  .= $bienesdeuso['descripcion'];
                                         
                                        break;
                                    case 'Otros bienes':
                                        if($bienesdeuso['descripcion']!="")
                                            $descripcionBDU  .= $bienesdeuso['descripcion'];                                         
                                        break;
                                }                               
                                ?>
                                <td><?php echo $descripcionBDU; ?></td>
                                <td >
                                    <a href="#"  onclick="loadFormBiendeuso(<?php echo $cliente['Cliente']['id'].",".$bienesdeuso['id'].",0"?>)" class="button_view">
                                        <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'img_edit'));?>
                                    </a>
                                    <?php echo $this->Form->postLink(
                                        $this->Html->image('ic_delete_black_24dp.png', array(
                                            'alt' => 'Eliminar','class'=>'img_trash'
                                        )),
                                        array(
                                            'controller' => 'Bienesdeusos',
                                            'action' => 'delete',
                                            $bienesdeuso['id'],
                                        ),
                                        array(
                                            'class'=>'deleteBiendeuso',
                                            'escape' => false // Add this to avoid Cake from printing the img HTML code instead of the actual image
                                        ),
                                        __('Esta seguro que quiere eliminar este bien de uso?')
                                    ); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
</div>

 <?php /**************************************************************************/ ?>
 <?php /*****************************Inicio de POPINS***********************************/ ?>
 <?php /**************************************************************************/ ?>


 <?php /**************************************************************************/ ?>
 <?php /*****************************Inicio de POPINS***********************************/ ?>
 <?php /**************************************************************************/ ?>


 <?php /**************************************************************************/ ?>
 <?php /*****************************Inicio de POPINS***********************************/ ?>
 <?php /**************************************************************************/ ?>

<!-- Inicio Popin Nuevo Domicilio -->
<a href="#x" class="overlay" id="nuevo_domicilio"></a>
<div class="popup">
        <div id="form_domicilio" >
           
                <h3><?php echo __('Agregar Domicilio'); ?></h3>
                <?php
                    echo $this->Form->create('Domicilio',array('action'=>'add'));
                    echo $this->Form->input('cliente_id',array('default'=>$cliente['Cliente']['id'],'type'=>'hidden'));
                ?>
                    <table cellpadding="0" cellspacing="0" border="0">
                      
                        <tr>  
                            <td style="width: 150px;"><?php  echo $this->Form->input('tipo', array('class'=>'chosen-select', 'label'=>'Tipo','type'=>'select','options'=>array('comercial'=>'Comercial','fiscal'=>'Fiscal','personal'=>'Personal','laboral'=>'Laboral')));?>
                            </td>                         
                            <td><?php echo $this->Form->input('localidade_id', array(
                                                                    'label'=>'Localidad',
                                                                    'class'=>'chosen-select'
                                                                    )
                                                                );?>
                            </td>
                            <td><?php echo $this->Form->input('codigopostal', array('label'=>'C&oacute;d. Postal', 'size' => '3'));?></td>
                            <td><?php echo $this->Form->input('superficie', array('label'=>'Superficie','title'=>'Este valor sera utilizado al generar la DDJJ para Monotributo', 'style'=>'width:85%'));?></td> 
                        </tr>   
                        <tr>
                            <td colspan="5"><?php echo $this->Form->input('calle', array('label'=>'Domicilio','style'=>'width:95%'));?></td> 
                           
                         </tr>                                                
                         <tr>                            
                            <td colspan="5"><?php echo $this->Form->input('observaciones', array('label'=>'Observaciones', 'style'=>'width:95%'));?></td>    
                         </tr> 
                          <tr> 
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>    
                            <td>
                                <a href="#close"  onclick="" class="btn_cancelar" style="margin-top:14px">Cancelar</a>  
                            </td>
                            <td>
                                <?php echo $this->Form->end('Aceptar');?>
                                
                            </td>    
                         </tr>     
            </table>
        </div>
    <a class="close" href="#close"></a>
</div>
<!-- Fin Popin Nuevo Domicilio --> 

<!-- Inicio Popin Modificar Domicilio-->
<a href="#x" class="overlay" id="modificar_domicilio"></a>
<div class="popup">
        <div id="form_modificar_domicilio">          
        </div>
    <a class="close" href="#close"></a>
</div>
<!-- Fin Popin Modificar Domicilio-->

<!-- Inicio Popin Nueva Persona Relacionada -->
<a href="#x" class="overlay" id="nuevo_persona"></a>
<div class="popup">
        <div id="form_persona" >
            <?php echo $this->Form->create('Personasrelacionada',array('controller'=>'Personasrelacionadas','action'=>'add')); ?>

                <h3><?php echo __('Agregar Contacto'); ?></h3>
                <?php
                    echo $this->Form->input('cliente_id',array('default'=>$cliente['Cliente']['id'],'type'=>'hidden'));
                ?>
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <?php echo $this->Form->input('nombre',array('label'=>'Apellido y Nombre'));?>
                        </td>
                        <td>
                            <?php echo $this->Form->input('documento');?>
                        </td> 
                        <td>
                            <?php echo $this->Form->input('cuit',array('label'=>'CUIT','maxlength'=>'11'));?>
                        </td>                         
                         <td><?php echo $this->Form->input('tipo',array('type'=>'select', 'class'=>'chosen-select','options'=>array(
                            'conyuge'=>'Conyuge',
                            'familiar'=>'Familiar',
                            'representante'=>'Representante',
                            'socio'=>'Socio',                            
                            'presidente'=>'Presidente',
                            'gerente'=>'Gerente',
                            'titular'=>'Titular',
                            'empleado'=>'Empleado',
                            'encargado'=>'Encargado',
                        )));?>
                        </td>
                                               
                    </tr>
                    <tr> 
                        <td><?php echo $this->Form->input('telefono', array('label'=>'Tel&eacute;fono', 'size' => '11'));?></td>  
                        <td><?php echo $this->Form->input('movil', array('label'=>'M&oacute;vil', 'size' => '11'));?></td>
                        <td><?php echo $this->Form->input('email',array('label'=>'E-mail'));?></td>
                        <td></td>     
                     </tr>
                     <tr> 
                        <td colspan="4"><?php echo $this->Form->input('observaciones', array('label'=>'Observaciones', 'size' => '35'));?></td>    
                     </tr>    
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>
                            <a href="#close" onclick="" class="btn_cancelar" style="margin-top:15px">Cancelar</a>
                        </td>
                        <td align="right">
                            <?php echo $this->Form->end(__('Aceptar',array('class' =>'btn_aceptar'))); ?>                          
                        </td>
                    </tr>
                </table>
        </div>
    <a class="close" href="#close"></a>
</div>
<!-- Fin Popin Nueva Persona Relacionada--> 

<!-- Inicio Popin Nueva Actividad Relacionada -->
<a href="#x" class="overlay" id="nuevo_actividad"></a>
<div class="popup">
        <div id="form_actividad" >
            <?php echo $this->Form->create('Actividadcliente',array('controller'=>'Actividadclientes','action'=>'add')); ?>

                <h3><?php echo __('Agregar Actividad'); ?></h3>
                <?php
                    echo $this->Form->input('cliente_id',array('default'=>$cliente['Cliente']['id'],'type'=>'hidden'));
                ?>
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td colspan="2">
                            <?php echo $this->Form->input('actividade_id',array(
                                                                'label'=>'Actividad',
                                                                'class'=>'chosen-select',
                                                                'style'=>'width:88%'
                                                                )
                            );?>
                        </td>
                        <td colspan="2">
                            <?php echo $this->Form->input('descripcion');?>
                        </td>
                        <td colspan="2">
                            <?php
                            echo $this->Form->input('baja', array(
                                    'class'=>'datepicker-month-year',
                                    'type'=>'text',
                                    'label'=>'Baja',
                                    'required'=>true,
                                    'readonly'=>'readonly')
                            );
                            ?>
                        </td>
                    </tr>                                                                                                                                        
                    <tr>
                        <td width="350">&nbsp;</td>
                        <td>
                            <a href="#close" onclick="" class="btn_cancelar" style="margin-top:15px">Cancelar</a>
                        </td>
                        <td>
                            <?php echo $this->Form->end(__('Aceptar',array('class' =>'btn_aceptar'))); ?>                          
                        </td>
                    </tr>
                </table>
        </div>
    <a class="close" href="#close"></a>
</div>
<!-- Fin Popin Nueva Actividadd Relacionada--> 

<!-- Inicio Popin Modificar Persona-->
<a href="#x" class="overlay" id="modificar_persona"></a>
<div class="popup">
        <div id="form_modificar_persona" class="persona" style="width:100%">   
        </div>
    <a class="close" href="#close"></a>
</div>
<!-- Fin Popin Modificar Persona-->
<!-- Inicio Popin Modificar Persona-->
<a href="#x" class="overlay" id="modificar_periodoactivo"></a>
<div class="popup">
    <div id="form_modificar_periodosactivos" class="persona form" style="width:100%">
    </div>
    <a class="close" href="#close"></a>
</div>
<!-- Fin Popin Modificar Persona-->
<!-- Inicio Popin Cbus-->
<a href="#x" class="overlay" id="load_cbuform"></a>
<div class="popup">
    <div id="form_cbus" class="persona form" style="width:100%">
    </div>
    <a class="close" href="#close"></a>
</div>
<!-- Fin Popin Cbus-->

<!-- Inicio Popin Modificar Impuesto-->
<a href="#x" class="overlay" id="modificar_impcli"></a>
<div class="popup">
        <div id="form_modificar_impcli" > 
        </div>
    <a class="close" href="#close"></a>
</div>
<!-- Fin Popin Modificar Impuesto-->

<!-- Inicio Popin Modificar Recibo-->
<a href="#x" class="overlay" id="modificar_recibo"></a>
<div class="popup">
        <div id="form_modificar_recibo" class="recibo form">
        </div>
    <a class="close" href="#close"></a>
</div>
<!-- Fin Popin Modificar Recibo-->

<!-- Inicio Popin Modificar Ingreso-->
<a href="#x" class="overlay" id="modificar_ingreso"></a>
<div class="popup">
        <div id="form_modificar_ingreso" class="ingreso form"> 
        </div>
    <a class="close" href="#close"></a>
</div>
<!-- Fin Popin Modificar Ingreso-->

 <!-- Inicio Popin Nuevo ImpClisAFIP -->
<a href="#x" class="overlay" id="nuevoImpcliAfip"></a>
<div id="divNuevoCbu" class="popup" style="width:38%;">        
        <div id="form_impcli_afip">
        <?php if (!empty($impuestosafip)): ?>

       
            <?php echo $this->Form->create('Impcli',array('controller'=>'Impclis','action'=>'add','id'=>'FormImpcliAFIP')); ?>
                
                <h3><?php echo __('Relacionar Impuesto'); ?></h3>
                <?php
                    echo $this->Form->input('cliente_id',array('default'=>$cliente['Cliente']['id'],'type'=>'hidden'));?>
                    <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <?php
                        //ya traemos todos los impuestos de afip junto con los periodos abiertos de cada uno
                        //asi que si vemos que un impuesto tiene un periodo abiero no tenemos que agregarlo a la variable de esta lista
                        //hay que armar el array de opciones por que no las tenemos disponibles
                        $listaAfip = array ();
                        foreach ($impuestosafip as $impAfip) {
                            $agregarImpuesto = true;
                            foreach ($impAfip['Impcli'] as $impcliAfip) 
                            {                    

                                    foreach ($impcliAfip['Periodosactivo'] as $periodoactivo) {                                    
                                        //si no existe este periodo es por que el impcli no esta abierto
                                         $agregarImpuesto = false;
                                    }                              
                            }
                            if($agregarImpuesto)
                            {
                                $listaAfip[ $impAfip['Impuesto']['id'] ] = $impAfip['Impuesto']['nombre'];        
                            }                  
                        }
                        ?>
                        <td colspan="2">
                            <?php 
                            echo $this->Form->input('impuesto_id', array(
                                'empty'=>'Seleccionar Impuesto', 
                                'div' => false ,
                                'type'=>'select', 
                                'class'=>'chosen-select',
                                'options'=>$listaAfip
                                )
                            );
                            ?>
                        </td>
                        <td>
                            <?php 
                            echo $this->Form->input('altaafip', array(
                                        'class'=>'datepicker-month-year', 
                                        'type'=>'text',
                                        'label'=>'Alta', 
                                        'required'=>true, 
                                        'style' => 'width:100px',                                   
                                        'readonly'=>'readonly')
                                );
                            echo $this->Form->input('alta', array(
                                        'type'=>'hidden',
                                        )
                                );
                            ?>
                        </td>
                    </tr>                                    
                    <tr>  
                        <td id="tdcategoriamonotributo">
                            <?php
                                echo $this->Form->input('categoriamonotributo',
                                    array('label' => 'Categori&oacutea Monotributo','style'=>'width:95%','type'=>'select','options'=>$categoriasmonotributos));
                            ?>
                        </td>
                        <td id="tdcategoriaautonomo">
                            <?php
                                echo $this->Form->input('autonomocategoria_id',
                                    array('label' => 'Categori&oacutea Auto&oacutenomo','style'=>'width:95%','type'=>'select'));
                            ?>
                        </td>
                        <td colspan="3">
                            <?php
                                echo $this->Form->input('descripcion',
                                    array('label' => 'Descripci&oacuten','style'=>'width:95%'));
                            ?>
                        </td>

                    </tr>
                    <tr>  
                        <td width="200">&nbsp;</td>
                        <td><a href="#close"  onclick="" class="btn_cancelar" style="margin-top:15px"> Cancelar </a></td>
                        <td ><?php echo $this->Form->end('Aceptar');?></td>
                    </tr>
               </table>
        </div>
        <?php else:?>
                <h3><?php echo __('Todos los impuestos han sido agregados'); ?></h3>
        <?php endif?>       
         <a class="close" href="#close"></a>
</div>
   
</div>
<!-- Fin Popin Nuevo ImpClis --> 
 <!-- Inicio Popin Nuevo ImpClisDGR -->
<a href="#x" class="overlay" id="nuevo_DGR"></a>
<div id="divNuevoCbu" class="popup" style="width:38%;">
       
        <div id="form_impcli_dgr">
            <?php if (!empty($impuestosdgr)): ?>  
            <?php echo $this->Form->create('Impcli',array('controller'=>'Impclis','action'=>'add','id'=>'FormImpcliDGR')); ?>

                <h3><?php echo __('Relacionar Impuesto'); ?></h3>
                <?php echo $this->Form->input('cliente_id',array('default'=>$cliente['Cliente']['id'],'type'=>'hidden')); ?>
                
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <?php
                        //ya traemos todos los impuestos de dgr junto con los periodos abiertos de cada uno
                        //asi que si vemos que un impuesto tiene un periodo abiero no tenemos que agregarlo a la variable de esta lista
                        //hay que armar el array de opciones por que no las tenemos disponibles
                        $listaDGR = array ();
                        foreach ($impuestosdgr as $impDgr) {
                            $agregarImpuesto = true;
                            foreach ($impDgr['Impcli'] as $impcliDgr) 
                            {
                               // if(isset($impcliAfip['PeriodosActivo']))
                                //{
                      
                                    foreach ($impcliDgr['Periodosactivo'] as $periodoactivo) {                                    
                                        //si no existe este periodo es por que el impcli no esta abierto
                                         $agregarImpuesto = false;
                                         //echo "no agregar impuesto".$impAfip['Impuesto']['nombre'];
                                    }
                               // }
                            }
                            if($agregarImpuesto)
                            {
                                $listaDGR[ $impDgr['Impuesto']['id'] ] = $impDgr['Impuesto']['nombre'];        
                            }                  
                        }
                        ?>
                        <td colspan="2">
                            <?php 
                            echo $this->Form->input('impuesto_id', array(
                                'empty'=>'Seleccionar Impuesto', 
                                'type'=>'select', 
                                'div' => false ,
                                'class'=>'chosen-select',
                                'options'=>$listaDGR));?>
                        </td>
                        <td>
                            <?php 
                            echo $this->Form->input('altadgr', array(
                                        'class'=>'datepicker-month-year', 
                                        'type'=>'text',
                                        'label'=>'Alta', 
                                        'required'=>true, 
                                        'style' => 'width:100px',                                   
                                        'readonly'=>'readonly')
                                );
                             echo $this->Form->input('alta', array(
                                        'type'=>'hidden',
                                        )
                                );
                            ?>
                        </td>
                    </tr>
                    <tr>                   
                        <td colspan="3"><?php echo $this->Form->input('descripcion', array('label' => 'Descripci&oacute;n', 'style'=>'width:95%'));?></td>
                    </tr>
                    <tr>
                        <td width="200">&nbsp;</td>
                        <td > <a href="#close" onclick="" class="btn_cancelar" style="margin-top:15px">Cancelar</a></td>
                        <td ><?php echo $this->Form->end('Aceptar');?></td>
                    </tr>
                </table>
            
            </div>
            <?php else:?>        
            <h3><?php echo __('Todos los impuestos han sido agregados'); ?></h3>
            </div>
            <?php endif?>            
        
        <a class="close" href="#close"></a>
    </div>
    
<!-- Fin Popin Nuevo ImpClis DGR --> 
 <!-- Inicio Popin Nuevo ImpClisDGRM -->
<a href="#x" class="overlay" id="nuevo_DGRM"></a>
<div id="divNuevoCbu" class="popup" style="width:38%;">
    <div id="form_impcli_dgrm">
        <?php if (!empty($impuestosdgrm)): ?>
            <?php echo $this->Form->create('Impcli',array('controller'=>'Impclis','action'=>'add','id'=>'FormImpcliDGRM')); ?>

            <h3><?php echo __('Relacionar Impuesto'); ?></h3>
            <table cellpadding="0" cellspacing="0" border="0">
                <?php
                echo $this->Form->input('cliente_id',array('default'=>$cliente['Cliente']['id'],'type'=>'hidden'));?>
                <tr>
                     <?php
                        //ya traemos todos los impuestos de dgr junto con los periodos abiertos de cada uno
                        //asi que si vemos que un impuesto tiene un periodo abiero no tenemos que agregarlo a la variable de esta lista
                        //hay que armar el array de opciones por que no las tenemos disponibles
                        $listaDGRM = array ();
                        foreach ($impuestosdgrm as $impDgrm) {
                            $agregarImpuesto = true;
                            foreach ($impDgrm['Impcli'] as $impcliDgrm) 
                            {
                                    foreach ($impcliDgrm['Periodosactivo'] as $periodoactivo) {                                    
                                        //si no existe este periodo es por que el impcli no esta abierto
                                         $agregarImpuesto = false;
                                         //echo "no agregar impuesto".$impAfip['Impuesto']['nombre'];
                                    }
                            }
                            if($agregarImpuesto)
                            {
                                $listaDGRM[ $impDgrm['Impuesto']['id'] ] = $impDgrm['Impuesto']['nombre'];        
                            }                  
                        }
                        ?>
                    <td colspan="2"><?php echo $this->Form->input('impuesto_id', array(
                                    'empty'=>'Seleccionar Impuesto', 
                                    'div' => false ,
                                    'type'=>'select', 
                                    'class'=>'chosen-select',
                                    'options'=>$listaDGRM))
                                ;?>
                    </td>
                    <td>
                    <?php 
                        echo $this->Form->input('altadgrm', array(
                                    'class'=>'datepicker-month-year', 
                                    'type'=>'text',
                                    'label'=>'Alta', 
                                    'required'=>true,  
                                    'style' => 'width:100px',                                  
                                    'readonly'=>'readonly')
                            );
                        echo $this->Form->input('alta', array(
                                'type'=>'hidden',
                                )
                        );
                        ?>
                    </td>
                </tr>
                <tr>            
                    <td colspan="3"><?php echo $this->Form->input('descripcion', array('label' => 'Descripci&oacute;n', 'style'=>'width:95%'));?></td>
                </tr>
                <tr>
                    <td width="200">&nbsp;</td>
                    <td > <a href="#close" onclick="" class="btn_cancelar" style="margin-top:15px">Cancelar</a></td>
                    <td ><?php echo $this->Form->end('Aceptar');?></td>
                </tr>
            </table>
        <?php else:?>
            <h3><?php echo __('Todos los impuestos han sido agregados'); ?></h3>
        <?php endif?>               
    </div>
    <a class="close" href="#close"></a>
    </div>
</div>
<!-- Inicio Popin Nuevo Impcliprovincias -->
<a href="#x" class="overlay" id="nuevo_IMPProv"></a>
<div id="divNuevoCbu" class="popup" style="width:50%;">
    <div id="form_impcli_dgrm_provincia">
        
    </div>
    <a class="close" href="#close"></a>
</div>
<!-- Inicio Popin Nuevo nuevo_SINDICATO -->
<a href="#x" class="overlay" id="nuevo_SINDICATO"></a>
<div id="divSindicato" class="popup" style="width:38%;">    
    <div id="form_impcliOrganismo_sindicato">
        <?php if (!empty($impuestossindicato)){ 
            echo $this->Form->create('Impcli',array('controller'=>'Impclis','action'=>'add','id'=>'FormImpcliSindicato')); ?>            
            <h3><?php echo __('Relacionar Sindicato'); ?></h3>
            <table cellpadding="0" cellspacing="0" border="0" class="tabla">
                <?php
                echo $this->Form->input('cliente_id',array('default'=>$cliente['Cliente']['id'],'type'=>'hidden'));?>
                <tr>
                    <?php
                    //ya traemos todos los impuestos de dgr junto con los periodos abiertos de cada uno
                    //asi que si vemos que un impuesto tiene un periodo abiero no tenemos que agregarlo a la variable de esta lista
                    //hay que armar el array de opciones por que no las tenemos disponibles
                    $listaSindicato = array ();
                    foreach ($impuestossindicato as $impSindicato) {
                        $agregarImpuesto = true;
                        foreach ($impSindicato['Impcli'] as $impcliSindicato) 
                        {
                                foreach ($impcliSindicato['Periodosactivo'] as $periodoactivo) {                                    
                                    //si no existe este periodo es por que el impcli no esta abierto
                                     $agregarImpuesto = false;
                                     //echo "no agregar impuesto".$impAfip['Impuesto']['nombre'];
                                }
                        }
                        if($agregarImpuesto)
                        {
                            $listaSindicato[ $impSindicato['Impuesto']['id'] ] = $impSindicato['Impuesto']['nombre'];        
                        }                  
                    }
                    ?>
                <td colspan="2">
                    <?php echo $this->Form->input('impuesto_id', array(
                                'label'=>'Sindicato',
                                'empty'=>'Seleccionar Sindicato', 
                                'type'=>'select', 
                                'div' => false ,
                                'class'=>'chosen-select',
                                'options'=>$listaSindicato)
                    );?>
                </td>
                <td>
                    <?php echo $this->Form->input('altasindicato', array(
                                'class'=>'datepicker-month-year', 
                                'type'=>'text',
                                'label'=>'Alta', 
                                'required'=>true, 
                                'style' => 'width:100px',                                   
                                'readonly'=>'readonly')
                        );
                    echo $this->Form->input('alta', array(
                            'type'=>'hidden',
                            )
                    );
                ?>
                </td>
            </tr>           
            <tr>            
                <td colspan="3">
                    <?php echo $this->Form->input('descripcion', array('label' => 'Descripci&oacute;n','style'=>'width:95%'));?>
                </td>
            </tr>
            <tr>
                <td width="200"></td>
                <td > <a href="#close" onclick="" class="btn_cancelar" style="margin-top:15px">Cancelar</a></td>
                <td ><?php echo $this->Form->end('Aceptar');?></td>
            </tr>
        </table>
        
        <?php } else { ?>
            <h3><?php echo __('Todos los sindicatos han sido agregados'); ?></h3>
    </div>
        <?php };?>
    
    <a class="close" href="#close"></a>
</div>
</div>

<!-- Inicio Popin Nuevo nuevo_Banco -->
<a href="#x" class="overlay" id="nuevo_Banco"></a>
<div id="divNuevoBanco" class="popup" style="width:38%;">    
    <div id="form_impcliOrganismo_Banco">
        <?php if (!empty($impuestosbancos)){ 
                echo $this->Form->create('Impcli',array('controller'=>'Impclis','action'=>'add','id'=>'FormImpcliBanco')); ?>  
                <h3><?php echo __('Relacionar Banco'); ?></h3>
                <table cellpadding="0" cellspacing="0" border="0" class="tabla">
                    <?php
                    echo $this->Form->input('cliente_id',array('default'=>$cliente['Cliente']['id'],'type'=>'hidden'));?>
                    <tr>
                        <?php
                        //ya traemos todos los impuestos de dgr junto con los periodos abiertos de cada uno
                        //asi que si vemos que un impuesto tiene un periodo abiero no tenemos que agregarlo a la variable de esta lista
                        //hay que armar el array de opciones por que no las tenemos disponibles
                        $listaBanco = array ();
                        foreach ($impuestosbancos as $impBanco) {
                            $agregarImpuesto = true;
                            foreach ($impBanco['Impcli'] as $impcliBanco) 
                            {
                                    foreach ($impcliBanco['Periodosactivo'] as $periodoactivo) {                                    
                                        //si no existe este periodo es por que el impcli no esta abierto
                                         $agregarImpuesto = false;
                                         //echo "no agregar impuesto".$impAfip['Impuesto']['nombre'];
                                    }
                            }
                            if($agregarImpuesto)
                            {
                                $listaBanco[ $impBanco['Impuesto']['id'] ] = $impBanco['Impuesto']['nombre'];        
                            }                  
                        }
                        ?>
                        <td colspan="2"><?php echo $this->Form->input('impuesto_id', 
                                                array('label'=>'Banco',
                                                'empty'=>'Seleccionar Banco', 
                                                'type'=>'select', 
                                                'div' => false ,
                                                'class'=>'chosen-select',
                                                'options'=>$listaBanco));?>
                        </td>
                        <td>
                        <?php 
                            echo $this->Form->input('altabanco', array(
                                        'class'=>'datepicker-month-year', 
                                        'type'=>'text',
                                        'label'=>'Alta', 
                                        'required'=>true,  
                                        'style' => 'width:100px',                                  
                                        'readonly'=>'readonly')
                                );
                            echo $this->Form->input('alta', array(
                                    'type'=>'hidden',
                                    )
                            );
                        ?>
                        </td>
                    </tr>           
                    <tr>            
                        <td colspan="3">
                            <?php echo $this->Form->input('descripcion', array('label' => 'Descripci&oacute;n','style'=>'width:95%'));?>
                        </td>
                    </tr>
                    <tr>
                        <td width="200"></td>
                        <td > <a href="#close" onclick="" class="btn_cancelar" style="margin-top:15px">Cancelar</a></td>
                        <td ><?php echo $this->Form->end('Aceptar');?></td>
                    </tr>
                </table>
        
        <?php } else { ?>
        <h3><?php echo __('Todos los banco han sido agregados'); ?></h3>
    </div>
        <?php };?>
    
    <a class="close" href="#close"></a>
</div>
</div>

<!-- Inicio Popin Nuevo SubCliente -->
<a href="#x" class="overlay" id="nuevo_subcliente"></a>
<div class="popup">
    <div id="form_subcliente" class="form" style="width: 94%;">             
        <?php echo $this->Form->create('Subcliente',array('controller'=>'Subclientes','action'=>'add')); ?>   

        <h3><?php echo __('Agregar Cliente'); ?></h3>
        <table style="margin-bottom:0px">
            <tr>
                <td colspan="2"><?php echo $this->Form->input('cliente_id',array('default'=>$cliente['Cliente']['id'],'type'=>'hidden')); ?></td>
            </tr>
            <tr>
                <td colspan="2"><?php echo $this->Form->input('cuit', array('label' => 'CUIT','maxlength'=>'11')); ?> </td>
            </tr>
            <tr>
                <td colspan="2"><?php echo $this->Form->input('dni', array('label' => 'DNI','maxlength'=>'8')); ?> </td>
            </tr>
            <tr>
                <td colspan="2"><?php echo $this->Form->input('nombre', array('label' => 'Nombre')); ?></td>
            </tr>
            <tr>
                <td><a href="#close" class="btn_cancelar" style="margin-top:12px">Cancelar</a></td>
                <td><?php echo $this->Form->end(__('Aceptar')); ?></td> 
            </tr>
        </table>
    </div>
    <a class="close" href="#close"></a>
</div>
<!-- Fin Popin Nuevo SubCliente -->

<!-- Inicio Popin Nuevo Provedor -->
    <a href="#x" class="overlay" id="nuevo_provedor"></a>
    <div class="popup">
        <div id="form_provedor" class="form" style="width: 94%;">
            <?php echo $this->Form->create('Provedore',array('controller'=>'Provedores','action'=>'add')); ?>

            <h3><?php echo __('Agregar Provedor'); ?></h3>
            <table style="margin-bottom:0px">
                <tr>
                    <td colspan="2"><?php echo $this->Form->input('cliente_id',array('default'=>$cliente['Cliente']['id'],'type'=>'hidden')); ?></td>
                </tr>
                <tr>
                    <td colspan="2"><?php echo $this->Form->input('cuit', array('label' => 'CUIT','maxlength'=>'11')); ?> </td>
                </tr>
                <tr>
                    <td colspan="2"><?php echo $this->Form->input('dni', array('label' => 'DNI','maxlength'=>'8')); ?> </td>
                </tr>
                <tr>
                    <td colspan="2"><?php echo $this->Form->input('nombre', array('label' => 'Nombre')); ?></td>
                </tr>
                <tr>
                    <td><a href="#close" class="btn_cancelar" style="margin-top:12px">Cancelar</a></td>
                    <td><?php echo $this->Form->end(__('Aceptar')); ?></td>
                </tr>
            </table>
        </div>
        <a class="close" href="#close"></a>
    </div>
<!-- Fin Popin Nuevo Provedor -->
<!-- Inicio Popin Nuevo Punto de venta -->
    <a href="#x" class="overlay" id="nuevo_puntodeventa"></a>
    <div class="popup">
        <div id="form_puntodeventa" class="form" style="width: 94%;">
            <?php echo $this->Form->create('Puntosdeventa',array('controller'=>'Puntosdeventa','action'=>'add')); ?>

            <h3><?php echo __('Agregar Punto de venta'); ?></h3>
            <table style="margin-bottom:0px">
                <tr>
                    <td colspan="2"><?php echo $this->Form->input('cliente_id',array('default'=>$cliente['Cliente']['id'],'type'=>'hidden')); ?></td>
                </tr>
                <tr>
                    <td colspan="2"><?php echo $this->Form->input('nombre', array('label' => 'Numero', 'div' => false,'maxlength'=>'5'));  ?></td>
                </tr>
                <tr>
                    <td colspan="2"><?php echo $this->Form->input('sistemafacturacion', array(
                            'label' => 'Sis. Fact.',
                            'div' => false,
                            'type'=>'select',
                            'maxlength'=>'4',
                            'options'=>$optionSisFact));  ?></td>
                </tr>
                <tr>
                    <td colspan="2"><?php echo $this->Form->input('domicilio_id', array(
                            'required' => 'required',
                            'label' => 'Domicilio',
                            'div' => false,
                            'type'=>'select')); ?></td>
                </tr>
                <tr>
                    <td><a href="#close" class="btn_cancelar" style="margin-top:12px">Cancelar</a></td>
                    <td><?php echo $this->Form->end(__('Aceptar')); ?></td>
                </tr>

            </table>
        </div>
        <a class="close" href="#close"></a>
    </div>
<!-- Fin Popin Nuevo Punto de venta -->
<!-- Inicio Popin Nuevo Empleado -->
    <a href="#x" class="overlay" id="nuevo_empleado"></a>
    <div class="popup" >
        <div id="form_empleado" class="form" style="width: 94%;float: none; ">
            <?php
            //todo: AGREGAR SI TIENE conyugue, hijos, adherente,
            //todo: codigoactividad, codigosituacion, codigocondicion, codigozona, codigomodalidadcontratacion
            //todo: codigosiniestrado(codigo incapacidad), tipoempresa
            echo $this->Form->create('Empleado',array('class'=>'formTareaCarga','controller'=>'Empelados','action'=>'add')); ?>
            <h3><?php echo __('Agregar Empleado'); ?></h3>
            <fieldset style="border: 1px solid #1e88e5;">
                <legend style="color:#1e88e5;font-weight:normal;">Datos Personales</legend>
                <?php
                echo $this->Form->input('id',array('type'=>'hidden'));
                echo $this->Form->input('cliente_id',array('default'=>$cliente['Cliente']['id'],'type'=>'hidden'));
                echo $this->Form->input('legajo',array('label'=>'Legajo'));
                echo $this->Form->input('nombre',array('style'=>'width:150px','label'=>'Apellido y nombre'));
                echo $this->Form->input('cuit',array('label'=>'CUIL','maxlength'=>'11'));
                echo $this->Form->input('dni',array('label'=>'DNI'));
                echo $this->Form->input('localidade_id',array(
                    'label'=>'Localidad',
                    'type'=>'select',
                    'class'=>'chosen-select',
                    'options'=>$localidades,
                    'style'=>'width:250px'
                    )
                );
                echo $this->Form->input('domicilio',array('label'=>'Domicilio','type'=>'text','style'=>'width:250px'));
                echo $this->Form->input('titulosecundario',array('label'=>'Titulo Secundario'));
		echo $this->Form->input('titulouniversitario',array('label'=>'Titulo Universitario'));
                ?>
            </fieldset>
            <fieldset style="border: 1px solid #1e88e5;">
                <legend style="color:#1e88e5;font-weight:normal;">Laborales</legend>
                <?php
                echo $this->Form->input('fechaingreso', array(
                        'class'=>'datepicker',
                        'type'=>'text',
                        'label'=>'Ingreso',
                        'required'=>true,
                        'readonly'=>'readonly')
                );
                echo $this->Form->input('fechaalta', array(
                        'class'=>'datepicker',
                        'type'=>'text',
                        'label'=>'Alta',
                        'required'=>true,
                        'readonly'=>'readonly')
                );
                echo $this->Form->input('fechaegreso', array(
                        'class'=>'datepicker',
                        'type'=>'text',
                        'label'=>'Egreso',
                        'required'=>true,
                        'readonly'=>'readonly')
                );

                echo $this->Form->input('domicilio_id',array('label'=>'Domicilio'));
                echo $this->Form->input('impuesto_id',array(
                    'label'=>'Banco',
                    'title'=>'Elija el banco donde se va a pagarle al empleado',
                    'empty'=>'Efectivo',
                    'options'=>$bancos
                ));
                echo $this->Form->input('conveniocolectivotrabajo_id',array('label'=>'Convenio Colectivo de Trabajo'));
                echo $this->Form->input('cargo_id',array('label'=>'Cargo', 'required'=>true,));
                echo $this->Form->input('afiliadosindicato',array('label'=>'Afiliado al sindicato'));
                echo $this->Form->label("Liquidaciones:");
                echo $this->Form->input('liquidaprimeraquincena',array('label'=>'Primera Quincena'));
                echo $this->Form->input('liquidasegundaquincena',array('label'=>'Segunda Quincena'));
                echo $this->Form->input('liquidamensual',array('label'=>'Mensual'));
                echo $this->Form->input('liquidasac',array('label'=>'SAC'));
                echo $this->Form->input('liquidapresupuestoprimera',array('label'=>'Presupuesto 1'));
                echo $this->Form->input('liquidapresupuestosegunda',array('label'=>'Presupuesto 2'));
                echo $this->Form->input('liquidapresupuestomensual',array('label'=>'Presupuesto 3'));
                ?>
            </fieldset>
            <fieldset style="border: 1px solid #1e88e5;">
                <legend style="color:#1e88e5;font-weight:normal;">Familiares</legend>
                <?php
                echo $this->Form->input('conyugue',array('label'=>'Conyugue','value'=>0));
                echo $this->Form->input('hijos',array('label'=>'Hijos','value'=>0));
                ?>
            </fieldset>
            <fieldset style="border: 1px solid #1e88e5;">
                <legend style="color:#1e88e5;font-weight:normal;">Datos AFIP</legend>
                <?php
                echo $this->Form->input('jornada',array('label'=>'Jornada','type'=>'select','options'=>array('0.5'=>"Media Jornada",'1'=>"Jornada Completa")));
                echo $this->Form->input('exentocooperadoraasistencial',array('label'=>'Exento Coop. Asistencial','value'=>0));
                echo $this->Form->input('codigoafip',array(
                        'label'=>'Codigo Afip',
                        'options'=>array(
                            '0'=>'0%',
                            '2'=>'25%',                            
                            '1'=>'50%',
                            '3'=>'75%',
                            '4'=>'100%',
                        )
                    )
                );
                echo $this->Form->input('afiliadosindicato',array('label'=>'Afiliado al sindicato'));
                echo $this->Form->input('adherente',array('label'=>'Adherentes','value'=>0));
                echo $this->Form->input('obrassociale_id',array(
                            'label'=>'Obra Social',
                            'class'=>'chosen-select',
                            )
                        );
                echo $this->Form->input('obrasocialsindical',array(
                    'label'=>'Obra social Sindical',
                    'value'=>1,
                    'checked'=>'checked',
                    'title'=>'Indicar si el empleado tiene una obra social que no sea sindical'));
                echo $this->Form->input('codigoactividad',array('label'=>'Codigo Actividad','options'=>$codigoactividad,'class'=>'chosen-select',));
                echo $this->Form->input('codigosituacion',array('label'=>'Codigo Situacion','options'=>$codigorevista,'class'=>'chosen-select',));
                echo $this->Form->input('codigocondicion',array('label'=>'Codigo Condicion','options'=>$codigoactividad,'class'=>'chosen-select',));
                echo $this->Form->input('codigozona',array('label'=>'Codigo Zona','options'=>$codigozona,'class'=>'chosen-select',));
                echo $this->Form->input('codigomodalidadcontratacion',array('label'=>'Codigo Modalidad Contratacion','options'=>$codigomodalidadcontratacion,'class'=>'chosen-select',));
                echo $this->Form->input('codigosiniestrado',array('label'=>'Codigo Siniestrado','options'=>$codigosiniestrado,'class'=>'chosen-select',));
                echo $this->Form->input('tipoempresa',array('label'=>'Tipo empresa','options'=>$tipoempresa,'class'=>'chosen-select',))."</br>";


                ?>
            </fieldset>
                <?php
            echo $this->Form->end(__('Aceptar')); ?>
        </div>
        <a class="close" href="#close"></a>
    </div>
    <a href="#x" class="overlay" id="modificar_empleado"></a>
        <div class="popup" id="form_modificar_empleado">
            <a class="close" href="#close"></a>
        </div>
    </div>

<!-- Fin Popin Nuevo Punto de venta -->
<?php }//fin if(mostrarView) ?>
<!-- Fin Popin Nuevo ImpClis DGRM -->
<!-- Popin Modal para edicion de ventas a utilizar por datatables-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" style="width:90%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
<!--                    <span aria-hidden="true">&times;</span>-->
                </button>
                <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
                <p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <!--                <button type="button" class="btn btn-primary">Save changes</button>-->
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<div id="divCambioGrupo" style="display:none">
    <p><label>Cambiar un contribuyente de grupo generar&aacute; deudas o recibos de dinero que hoy no existen en el Informe Tributario Financiero</label></p>
    <p><label>Por lo que cuando un contribuyente abandona un grupo se crear&aacute; (para cada periodo en el que este contribuyente haya echo 
        recibos, c&aacute;lculo de impuestos, planes de pagos o honorarios) un recibo, en un contribuyente que se queda en el grupo, que 
        balancear&aacute; las deudas o recibos de dinero que produce su salida. Tambien se crear&aacute;n recibos en el mismo contribuyente
        que se va del grupo para que el grupo al que llega no se vea afectado por este movimiento.</label>
    </p>
    <?php
        echo $this->Form->create('Cliente',array('action'=>'cambiardegrupo','id'=>'cambiargrupoForm', 'class' => 'form_popin'));            
        echo $this->Form->input('cliente_id',array(
            'type'=>'hidden',
            'value'=>isset($cliente['Cliente']['nombre'])?$cliente['Cliente']['id']:0));
        echo $this->Form->input('grupocliente_id',array(
            'label'=>'Seleccionar Nuevo grupo'
        ));
        echo $this->Form->end();        
    ?>
</div>