<?php echo $this->Html->script('jquery-ui',array('inline'=>false));?>
<?php echo $this->Html->script('clientes/view',array('inline'=>false));?>
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
            <h2>
                <?php 
                  if($mostrarView){
                        echo __($cliente['Cliente']['nombre']);                        
                    } 
                    else{
                        echo __('Clientes'); 
                    }            
                ?>
            </h2>
            <input placeholder="Buscar Cliente" id="txtBuscarClintes" type="text" style="float:left; width:100%; padding-top:5px" />
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
                if($mostrarView){
                    if($clientex['Cliente']['id']==$cliente['Cliente']['id']){
                        $classCliente = "section_cli_view_selected";
                    }             
                }

                echo $this->Html->link(
                    $this->Html->div(
                                $classCliente, 
                                $this->Html->image('cli_view.png', array('alt' => '','id'=>'imgcli','class'=>'','style'=>'float:left')).
                                __($this->Form->label('Cliente', $clientex['Cliente']['nombre'], 'lbl_cli_view',array('style'=>'float:right'))), 
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
<div class="clientes_view" style="width:70%;">
    <div class="" style="width:100%;height:30px;">
         <div class="cliente_view_tab"  onClick="showDatosCliente()" id="cliente_view_tab_cliente">
            <?php
               echo $this->Form->label(null, $text = 'Cliente',array('style'=>'text-align:center;margin-top:5px;cursor:pointer')); 
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
        	<th colspan="6" class="tbl_view_th1">
        		<h2 id="lblDatosPeronales" class="h2header">
        			<?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgDatosPersonales','class'=>'imgOpenClose'));?>
        			<?php echo __('Contribuyente'); ?>
        		</h2>
        	</th>                    
            <th class="tbl_view_th2">
                <a href="#" class="button_view" onClick="loadFormEditarPersona()"> 
                    <?php echo $this->Html->image(
                                        'edit_view.png', 
                                        array(
                                            'alt' => 'edit',
                                            'class'=>'imgedit',
                                            'style'=>'color:red;float:left;margin-top:10px'                                            
                                            )
                                        );
                    ?>
                </a>
            </th>
            <th class="tbl_view_th2">

                <?php echo $this->Form->postLink(
                                         $this->Html->image('ic_delete_black_24dp.png', array(
                                            'alt' => 'DESHABILITAR',
                                        )),
                                        array(
                                            'controller' => 'Clientes',
                                            'action' => 'deshabilitar',
                                            $cliente['Cliente']['id']
                                        ),
                                        array(
                                            'escape' => false, // Add this to avoid Cake from printing the img HTML code instead of the actual image,
                                            'class'=>' imgedit',
                                            'style'=>'color:red;float:right;margin-top:10px'
                                        ),
                                        __('Esta seguro que quiere Deshabilitar a '.$cliente['Cliente']['nombre'].'? Una vez deshabilitado no aparecera en ningun Informe', $cliente['Cliente']['id'])                                    
                                ); ?>
            </th>
        </tr>
        <tr class="datosPersonales"><!--1.1 Tabla datos clientes-->
            <td>
                <?php 
                echo $this->Form->create('Cliente',array('action'=>'edit','id'=>'saveDatosPersonalesForm', 'class' => 'form_popin'));            
                echo $this->Form->input('id',array('type'=>'hidden'));?>
                <table cellspacing="0" cellpadding="0" id="tableDatosPersonalesEdit">
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
                        </td>
                        <td>
                            <?php echo $this->Form->input('grupocliente_id',array('label'=>'Grupo Clientes', 'style'=>'width:180px')); ?> 
                        </td>
                    </tr>    
                    <tr>
                        <td><?php echo $this->Form->input('nombre',array('label'=>array(
                                                                            'text'=>'Apellido y Nombre o Raz&oacuten Social',
                                                                            'id'=>'clienteEditLabelNombre',
                                                                            'style'=>'width:200px')
                                                                            )); ?></td>
                        <td><?php echo $this->Form->input('cuitcontribullente',array('label'=>'CUIT','style'=>'width:180px','maxlength'=>'11','class'=>'numeric')); ?></td>
                        <td><?php echo $this->Form->input('dni',array('label'=>'DNI', 'style'=>'width:180px','maxlength'=>'8','class'=>'numeric')); ?></td>
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
                    <tr>
                        <td>
                            <?php
                            echo $this->Form->input('alicuotaart', array(
                                    'label'=>'Alicuota ART')
                            );?>
                        </td>
                        <td>
                            <?php
                            echo $this->Form->input('fijoart', array(
                                    'label'=>'Cuota Fija LRT')
                            );?>
                        </td>
                        <td>
                            <?php
                            echo $this->Form->input('segurodevida', array(
                                    'label'=>'Seguro de vida')
                            );?>
                        </td>
                    </tr>
                    <span style="display:none">
                    </span>
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
            <th colspan="7" class="tbl_view_th1">
                <h2 class="h2header" id="lblDomicilio">
                <?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgDomicilio','class'=>'imgOpenClose'));?>
                <?php echo __('Domicilios'); ?>
               </h2>
            </th>
            <th class="tbl_view_th2">
                <a href="#nuevo_domicilio" class="button_view"> 
                    <?php echo $this->Html->image('add_view.png', array('alt' => 'edit','class'=>'imgedit'));?>
                </a>
            </th>
        </tr>
       
        <tr class="domicilios"> <!--2.1 Tabla Domicilios-->
            <td colspan="7">
            <table id="relatedDomicilios" class="tbl_related">
                <head>
                     <tr class="domicilio">    
                        <th><?php echo __('Domicilio'); ?></th>     
                        <th><?php echo __('Provincia'); ?></th>  
                        <th><?php echo __('Localidad'); ?></th>     
                        <th><?php echo __('Superficie'); ?></th>     
                        <th><?php echo __('Acciones'); ?></th>    
                     </tr>  
                     <tr>
                        <th colspan="6"><hr color="#E4E4E4" width="100%"></th> 
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
                             <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'imgedit'));?>
                            </a> 
                            <?php echo $this->Form->postLink(
                                         $this->Html->image('ic_delete_black_24dp.png', array(
                                            'alt' => 'Eliminar',
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
            <th colspan="7" class="tbl_view_th1">
                <h2 class="h2header" id="lblPersona">
                <?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgPersona','class'=>'imgOpenClose'));?>
                <?php echo __('Contactos'); ?>
               </h2>
            </th>
            <th class="tbl_view_th2">
                <a href="#nuevo_persona" class="button_view"> 
                    <?php echo $this->Html->image('add_view.png', array('alt' => 'edit','class'=>'imgedit'));?>
                </a>
            </th>
        </tr>
        <tr class="personas">
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
                        <th colspan="6"><hr color="#E4E4E4" width="100%"></th> 
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
                                <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'imgedit'));?> 
                            </a>      
                             <?php echo $this->Form->postLink(
                                         $this->Html->image('ic_delete_black_24dp.png', array(
                                            'alt' => 'Eliminar',
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
            <th colspan="7" class="tbl_view_th1">
                <h2 class="h2header" id="lblActividad">
                <?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgActividad','class'=>'imgOpenClose'));?>
                <?php echo __('Actividades'); ?>
               </h2>
            </th>
            <th class="tbl_view_th2">
                <a href="#nuevo_actividad" class="button_view"> 
                    <?php echo $this->Html->image('add_view.png', array('alt' => 'edit','class'=>'imgedit'));?>
                </a>
            </th>
        </tr>
        <tr class="actividades">
            <td colspan="7">
            <table id="relatedActividades" class="tbl_related"> <!--Tabla Persona Relacionada-->
                <head>
                     <tr >    
                        <th><?php echo __('Codigo'); ?></th>                          
                        <th><?php echo __('Actividad'); ?></th>                          
                        <th><?php echo __('Acciones'); ?></th>     
                     </tr>  
                     <tr>
                        <th colspan="6"><hr color="#E4E4E4" width="100%"></th> 
                     </tr>
                </head>     
                <tbody>
              <?php if (!empty($cliente['Actividadcliente'])): ?>      
              <?php foreach ($cliente['Actividadcliente'] as $actividad): ?>     
                     <tr >    
                        <td><?php echo h($actividad['Actividade']['descripcion']); ?></td> 
                        <td><?php echo h($actividad['Actividade']['nombre']); ?></td> 
                        <td class="">
                            <?php echo $this->Form->postLink(
                                         $this->Html->image('ic_delete_black_24dp.png', array(
                                            'alt' => 'Eliminar',
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
 <?php /*****************************Facturacion***********************************/ ?>
 <?php /**************************************************************************/ ?>       
        <tr class="rowheaderfacturacion"> <!--5. Facturacion-->
        	<th colspan="7" class="tbl_view_th1">
                <h2 class="h2header" id="lblFacturacion">
       			<?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgFacturacion','class'=>'imgOpenClose'));?>
        		<?php echo __('Facturaci&oacuten'); ?>
        	   </h2>
            </th>
            <th class="tbl_view_th2">
                 <a href="#editarFacturacion"  class="button_view"> 
                    <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'imgedit'));?> 
                </a>    
            </th>
            </tr>
            <tr class="facturacion">
                <td> <!--5.1 Tabla facturacion-->
                    <table class="tbl_related">
                    <tr class="facturacion">    
                        <td><?php echo __('CPA'); ?></td>     
                        <td><?php echo h($cliente['Cliente']['cpa']); ?></td>             
                    </tr>        
                    <tr class="facturacion" >    
                      	<td colspan="4"><?php echo __('Tipo de Factura que emite'); ?></td> 
                    </tr>  
                    <tr class="facturacion">    
                        <td><?php echo __('A'); ?></td>     
                        <td><?php 
                        if($cliente['Cliente']['emitefacturaa']){
                            echo h("Si");
                        }else{
                             echo h("No");
                        }; ?></td>  
                        <td><?php echo __('Vencimiento del CAI'); ?></td>     
                        <td><?php echo h(date('d-m-Y',strtotime($cliente['Cliente']['vtocaia'])));?></td>             
                    </tr>
                    <tr class="facturacion">    
                        <td><?php echo __('B'); ?></td>     
                        <td><?php 
                        if($cliente['Cliente']['emitefacturab']){
                            echo h("Si");
                        }else{
                             echo h("No");
                        }; ?></td>  
                        <td><?php echo __('Vencimiento del CAI'); ?></td>     
                        <td><?php echo h(date('d-m-Y',strtotime($cliente['Cliente']['vtocaib'])));?></td>             
                    </tr>
                    <tr class="facturacion">    
                        <td><?php echo __('C'); ?></td>     
                         <td><?php 
                        if($cliente['Cliente']['emitefacturac']){
                            echo h("Si");
                        }else{
                             echo h("No");
                        }; ?></td> 
                        <td><?php echo __('Vencimiento del CAI'); ?></td>     
                        <td><?php echo h(date('d-m-Y',strtotime($cliente['Cliente']['vtocaic'])));?></td>             
                    </tr> 
                    </table> 
                </tD>
            </tr>     
 <?php /**************************************************************************/ ?>
 <?php /*****************************AFIP*****************************************/ ?>
 <?php /**************************************************************************/ ?>
 	<tr class="rowheaderafip"> <!--7. AFIP-->
    	<th colspan="7" class="tbl_view_th1">
    		<h2 class="h2header" id="lblAFIP">
   				<?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgAFIP','class'=>'imgOpenClose'));?>
    			<?php echo __('AFIP'); ?></h2></th>
   		<th class="tbl_view_th2">
           
        </th>    		
    </tr> 
    <tr class="afip">
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
    <tr class="afip">  <!--7.2 Impuestos del Organismo -->   
        <td colspan="7"> 
        <table id="tablaImpAfip" class="tbl_related">    
            <tr>    
                <th><?php echo __('Impuesto'); ?></th>
                <th><?php echo __('Alta'); ?></th>                   
                <th><?php echo __('Acciones'); ?></th>
                <th>
                    <a href="#nuevoImpcliAfip" class="button_view"> 
                        <?php echo $this->Html->image('add_view.png', array('alt' => 'edit','class'=>'imgedit','title'=>'Agregar impuesto'));?>
                    </a>                                            
                </th>  
            </tr>  
            <tr>
                <th colspan="6"><hr color="#E4E4E4" width="100%"></th> 
            </tr>
            <?php if (!empty($cliente['Impcli'])): ?>                            
                <?php foreach ($cliente['Impcli'] as $impcli): ?>
                    <?php if ($impcli['Impuesto']['organismo']=='afip'): ?>    
                         <tr id="rowImpcli<?php echo $impcli['id']?>" >                                                
                            <td><?php echo $impcli['Impuesto']['nombre']; ?>
                             <?php if($impcli['Impuesto']['id']==4){
                                    echo "<span>".$impcli['categoriamonotributo']."</span>";
                                }?></td>
                            <td>
                            <?php if(count($impcli['Periodosactivo'])){
                                echo $impcli['Periodosactivo'][0]['desde'];
                            }?>
                            </td>                                
                            <td>
                                <a href="#"  onclick="loadFormImpuesto(<?php echo$impcli['id']; ?>,<?php echo $impcli['cliente_id'];?>)" class="button_view"> 
                                 <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'imgedit', 'title' => 'Editar'));?>
                                </a>
                                <a href="#"  onclick="loadFormImpuestoPeriodos(<?php echo$impcli['id']; ?>)" class="button_view"> 
                                 <?php echo $this->Html->image('calendario.png', array('alt' => 'open','class'=>'imgedit'));?>
                                </a>
                                <a href="#"  onclick="deleteImpcli(<?php echo$impcli['id']; ?>)" class="button_view"> 
                                 <?php echo $this->Html->image('delete.png', array('alt' => 'open','class'=>'imgedit'));?>
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
        <?php endif; ?>   		
    <?php endforeach; ?>
<?php endif; ?>   	    
<?php /**************************************************************************/ ?>
<?php /*****************************DGR******************************************/ ?>
<?php /**************************************************************************/ ?>        
        <tr class="rowheaderdgr"><!--8. DGR-->
        	<th  colspan="7" class="tbl_view_th1">
        		<h2 class="h2header" id="lblDGR">
       				<?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgDGR','class'=>'imgOpenClose'));?>
        			<?php echo __('DGR'); ?></h2></th>
	   		<th class="tbl_view_th2">
                
            </th>		
        </tr>
        <tr class="dgr"> 
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
        <tr class="dgr"> 
            <td colspan="7"> 
                <table id="tablaImpDGR" class="tbl_related">   <!--8.2 Impuestos del Organismo -->  
                    <tr>    
                        
                        <th><?php echo __('Impuesto'); ?></th>
                        <th><?php echo __('Alta'); ?></th>                        
                        <th><?php echo __('Acciones'); ?></th>
                        <th>
                            <a href="#nuevo_DGR" class="button_view"> 
                                <?php echo $this->Html->image('add_view.png', array('alt' => 'edit','class'=>'imgedit'));?>
                            </a>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="6"><hr color="#E4E4E4" width="100%"></th> 
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
                                         <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'imgedit'));?>
                                            </a>
                                        <a href="#"  onclick="loadFormImpuestoPeriodos(<?php echo$impcli['id']; ?>)" class="button_view"> 
                                         <?php echo $this->Html->image('calendario.png', array('alt' => 'open','class'=>'imgedit'));?>
                                        </a>
                                        <a href="#"  onclick="deleteImpcli(<?php echo$impcli['id']; ?>)" class="button_view"> 
                                         <?php echo $this->Html->image('delete.png', array('alt' => 'open','class'=>'imgedit'));?>
                                        </a>
                                        <?php 
                                        //aca vamos a agregar la opcion de manejar las Provincias de un impuesto que debe relacionar Provincias 
                                        if($impcli['impuesto_id']==174/*Convenio Multilateral*/||$impcli['impuesto_id']==21/*Convenio Multilateral*/){?>
                                        <a href="#"  onclick="loadFormImpuestoProvincias(<?php echo$impcli['id']; ?>)" class="button_view"> 
                                         <?php echo $this->Html->image('mapa_regiones.png', array('alt' => 'open','class'=>'imgedit'));?>
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
        <!--FIN Impuestos del Organismo -->
		        <?php endif; ?>   		
	        <?php endforeach; ?>
        <?php endif; ?>  
 <?php /**************************************************************************/ ?>
 <?php /*****************************DGRM*****************************************/ ?>
 <?php /**************************************************************************/ ?>
        <tr  class="rowheaderdgrm" ><!--9. DGRM-->
        	<th  colspan="7" class="tbl_view_th1">
        		<h2 class="h2header" id="lblDGRM">
       				<?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgDGRM','class'=>'imgOpenClose'));?>
        			<?php echo __('DGRM'); ?></h2></th>
	   		<th class="tbl_view_th2">
            </th>
        </tr> 
        <tr class="dgrm"><!--9.1 Tabla DGRM -->
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
        <tr class="dgrm"> 
            <td colspan="7"> 
            <table id="tablaImpDGRM" class="tbl_related">    
                <tr>     
                    <th><?php echo __('Impuesto'); ?></th>
                    <th><?php echo __('Alta'); ?></th>                   
                    <th><?php echo __('Acciones'); ?></th>    
                    <th> 
                        <a href="#nuevo_DGRM" class="button_view"> 
                            <?php echo $this->Html->image('add_view.png', array('alt' => 'add','class'=>'imgedit'));?>
                        </a> 
                    </th>    
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
                                     <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'imgedit'));?>
                                    </a>
                                     <a href="#"  onclick="loadFormImpuestoPeriodos(<?php echo$impcli['id']; ?>)" class="button_view"> 
                                     <?php echo $this->Html->image('calendario.png', array('alt' => 'open','class'=>'imgedit'));?>
                                    </a>
                                    <a href="#"  onclick="deleteImpcli(<?php echo$impcli['id']; ?>)" class="button_view"> 
                                        <?php echo $this->Html->image('delete.png', array('alt' => 'open','class'=>'imgedit'));?>
                                    </a>
                                    <?php
                                    //aca vamos a agregar la opcion de manejar las Provincias de un impuesto que debe relacionar Provincias 
                                    if($impcli['impuesto_id']==6/*Actividades Varias*/){?>
                                    <a href="#"  onclick="loadFormImpuestoLocalidades(<?php echo$impcli['id']; ?>)" class="button_view"> 
                                     <?php echo $this->Html->image('localidad.png', array('alt' => 'open','class'=>'imgedit'));?>
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
        <tr  class="rowheadersindicatos" ><!--9.1. SINDICATO-->
            <th  colspan="7" class="tbl_view_th1">
                <h2 class="h2header" id="lblSINDICATO">
                    <?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'ImgSindicatos','class'=>'imgOpenClose'));?>
                    <?php echo __('Sindicatos'); ?></h2></th>
            <th class="tbl_view_th2">
                
            </th>                
        <!--9.2 Impuestos del Organismo -->        
        <tr class="sindicatos"> 
            <td colspan="7"> 
            <table id="tablaImpSINDICATO" class="tbl_related">    
                <tr>     
                    <th><?php echo __('Impuesto'); ?></th>
                    <th><?php echo __('Alta'); ?></th>                   
                    <th><?php echo __('Usuario'); ?></th>                   
                    <th><?php echo __('Clave'); ?></th>                   
                    <th><?php echo __('Acciones'); ?></th>    
                    <th>
                        <a href="#nuevo_SINDICATO" class="button_view"> 
                        <?php echo $this->Html->image('add_view.png', array('alt' => 'add','class'=>'imgedit'));?>
                        </a>
                    </th>                        
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
                                        <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'imgedit'));?>
                                    </a>
                                    <a href="#"  onclick="loadFormImpuestoPeriodos(<?php echo$impcli['id']; ?>)" class="button_view"> 
                                        <?php echo $this->Html->image('calendario.png', array('alt' => 'open','class'=>'imgedit'));?>
                                    </a>
                                    <a href="#"  onclick="deleteImpcli(<?php echo$impcli['id']; ?>)" class="button_view"> 
                                        <?php echo $this->Html->image('delete.png', array('alt' => 'open','class'=>'imgedit'));?>
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
        <tr  class="rowheaderbancos" ><!--9.1. BANCO-->
            <th  colspan="7" class="tbl_view_th1">
                <h2 class="h2header" id="lblBANCO">
                    <?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgBancos','class'=>'imgOpenClose'));?>
                    <?php echo __('Bancos'); ?>
                </h2>
            </th>
            <th class="tbl_view_th2">
                
            </th>        
        </tr> 
        <tr class="bancos"> 
            <td colspan="7"> 
            <table id="tablaImpBanco" class="tbl_related">    
                <tr>     
                    <th><?php echo __('Impuesto'); ?></th>
                    <th><?php echo __('Alta'); ?></th>    
                    <th><?php echo __('Usuario'); ?></th>                   
                    <th><?php echo __('Clave'); ?></th>                    
                    <th><?php echo __('Acciones'); ?></th>                                
                    <th>
                        <a href="#nuevo_Banco" class="button_view"> 
                            <?php echo $this->Html->image('add_view.png', array('alt' => 'add','class'=>'imgedit'));?>
                        </a>
                    </th>                       
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
                                    <a href="#"  onclick="loadFormImpuesto(<?php echo$impcli['id']; ?>,<?php echo $impcli['cliente_id'];?>)" class="button_view"> 
                                        <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'imgedit'));?>
                                    </a>
                                    <a href="#"  onclick="loadFormImpuestoPeriodos(<?php echo$impcli['id']; ?>)" class="button_view"> 
                                        <?php echo $this->Html->image('calendario.png', array('alt' => 'open','class'=>'imgedit'));?>
                                    </a>
                                    <a href="#"  onclick="deleteImpcli(<?php echo$impcli['id']; ?>)" class="button_view"> 
                                        <?php echo $this->Html->image('delete.png', array('alt' => 'open','class'=>'imgedit'));?>
                                    </a>
                                    <a href="#"  onclick="loadCbus(<?php echo$impcli['id']; ?>)" class="button_view">
                                        <?php echo $this->Html->image('cuentabancaria.png', array('alt' => 'open','class'=>'imgedit'));?>
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
        <tr class="rowheaderpuntosdeventas" ><!--15. Puntos de Ventas-->
            <th colspan="7" class="tbl_view_th1">
                <h2 class="h2header" id="lblPuntosdeventas">
                    <?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgPuntosdeventas','class'=>'imgOpenClose'));?>
                    <?php echo __('Puntos de Ventas'); ?>                    
                </h2>
            </th>
            <th class="tbl_view_th2">
                <a href="#nuevo_puntodeventa" class="button_view"> 
                    <?php echo $this->Html->image('add_view.png', array('alt' => 'add','class'=>'imgedit'));?>
                </a>
            </th>
        </tr>
        <tr class="puntosdeventa">
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
                             <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'imgedit'));?>
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
        <tr class="rowheadersubclientes" ><!--15. Sub Clientes-->
            <th colspan="7" class="tbl_view_th1">
                <h2 class="h2header" id="lblSubclientes">
                    <?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgSubclientes','class'=>'imgOpenClose'));?>
                    <?php echo __('Clientes'); ?>
                </h2>
            </th>
            <th class="tbl_view_th2">
                <a href="#nuevo_subcliente" class="button_view">
                    <?php echo $this->Html->image('add_view.png', array('alt' => 'add','class'=>'imgedit'));?>
                </a>
            </th>
        </tr>
        <tr class="subcliente">
            <td>
                <table id="relatedClientes" class="tbl_related">
                    <tr class="subcliente">
                        <th><?php echo __('CUIT'); ?></th>
                        <th><?php echo __('DNI'); ?></th>
                        <th><?php echo __('Nombre'); ?></th>
                        <th class=""><?php echo __('Acciones'); ?></th>
                    </tr>
                    <tr>
                        <th colspan="7"><hr color="#E4E4E4" width="100%"></th>
                    </tr>
                    <?php if (!empty($cliente['Subcliente'])): ?>
                        <?php foreach ($cliente['Subcliente'] as $subcliente): ?>
                            <tr class="subcliente" id="rowSubcliente<?php echo $subcliente['id']; ?>">
                                <td><?php echo $subcliente['cuit']; ?></td>
                                <td><?php echo $subcliente['dni']; ?></td>
                                <td><?php echo $subcliente['nombre']; ?></td>
                                <td >
                                    <a href="#"  onclick="loadFormSubcliente(<?php echo $subcliente['id']; ?>)" class="button_view">
                                        <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'imgedit'));?>
                                    </a>
                                    <?php echo $this->Form->postLink(
                                        $this->Html->image('ic_delete_black_24dp.png', array(
                                            'alt' => 'Eliminar',
                                        )),
                                        array(
                                            'controller' => 'Subclientes',
                                            'action' => 'delete',
                                            $subcliente['id'],
                                        ),
                                        array(
                                            'class'=>'deleteSubcliente',
                                            'escape' => false // Add this to avoid Cake from printing the img HTML code instead of the actual image
                                        ),
                                        __('Esta seguro que quiere eliminar este subcliente?')
                                    ); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tr>
                </table>
            </td>
        </tr>
        <?php /**************************************************************************/ ?>
        <?php /*****************************Provedores***********************************/ ?>
        <?php /**************************************************************************/ ?>
        <tr class="rowheaderprovedores" ><!--16. Provedores-->
            <th colspan="7" class="tbl_view_th1">
                <h2 class="h2header" id="lblProvedores">
                    <?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgProvedores','class'=>'imgOpenClose'));?>
                    <?php echo __('Provedores'); ?>
                </h2>
            </th>
            <th class="tbl_view_th2">
                <a href="#nuevo_provedor" class="button_view">
                    <?php echo $this->Html->image('add_view.png', array('alt' => 'add','class'=>'imgedit'));?>
                </a>
            </th>
        </tr>
        <tr class="rowheaderprovedores">
            <td class="provedor">
                <table id="relatedProvedores" class="tbl_related">
                    <tr class="provedor">
                        <th><?php echo __('CUIT'); ?></th>
                        <th><?php echo __('DNI'); ?></th>
                        <th><?php echo __('Nombre'); ?></th>
                        <th class=""><?php echo __('Acciones'); ?></th>
                    </tr>
                    <tr class="provedor">
                        <th colspan="7"><hr color="#E4E4E4" width="100%"></th>
                    </tr>
                    <?php if (!empty($cliente['Provedore'])): ?>
                        <?php foreach ($cliente['Provedore'] as $provedor): ?>
                            <tr class="provedor" id="rowProvedore<?php echo $provedor['id']; ?>">
                                <td><?php echo $provedor['cuit']; ?></td>
                                <td><?php echo $provedor['dni']; ?></td>
                                <td><?php echo $provedor['nombre']; ?></td>
                                <td >
                                    <a href="#"  onclick="loadFormProvedore(<?php echo $provedor['id']; ?>)" class="button_view">
                                        <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'imgedit'));?>
                                    </a>
                                    <?php echo $this->Form->postLink(
                                        $this->Html->image('ic_delete_black_24dp.png', array(
                                            'alt' => 'Eliminar',
                                        )),
                                        array(
                                            'controller' => 'Provedores',
                                            'action' => 'delete',
                                            $provedor['id'],
                                        ),
                                        array(
                                            'class'=>'deleteProvedore',
                                            'escape' => false // Add this to avoid Cake from printing the img HTML code instead of the actual image
                                        ),
                                        __('Esta seguro que quiere eliminar este provedor?')
                                    ); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tr>
                </table>
            </td>
        </tr>
        <?php /**************************************************************************/ ?>
        <?php /*****************************Empleados***********************************/ ?>
        <?php /**************************************************************************/ ?>
        <tr class="rowheaderempleados" ><!--17. Empleados-->
            <th colspan="7" class="tbl_view_th1">
                <h2 class="h2header" id="lblEmpleados">
                    <?php echo $this->Html->image('mas2.png', array('alt' => 'open','id'=>'imgEmpleados','class'=>'imgOpenClose'));?>
                    <?php echo __('Empleados'); ?>
                </h2>
            </th>
            <th class="tbl_view_th2">
                <a href="#nuevo_empleado" class="button_view">
                    <?php echo $this->Html->image('add_view.png', array('alt' => 'add','class'=>'imgedit'));?>
                </a>
            </th>
        </tr>
        <tr class="rowheaderempleados">
            <td class="empleado">
                <table id="relatedEmpleados" class="tbl_related">
                    <tr class="empleado">
                        <th><?php echo __('LEGAJO'); ?></th>
                        <th><?php echo __('CUIT'); ?></th>
                        <th><?php echo __('DNI'); ?></th>
                        <th><?php echo __('Nombre'); ?></th>
                        <th><?php echo __('Fecha Ingreso'); ?></th>
                        <th class=""><?php echo __('Acciones'); ?></th>
                    </tr>
                    <tr class="empleado">
                        <th colspan="7"><hr color="#E4E4E4" width="100%"></th>
                    </tr>
                    <?php if (!empty($cliente['Empleado'])): ?>
                        <?php foreach ($cliente['Empleado'] as $empleado): ?>
                            <tr class="empleado" id="rowEmpleado<?php echo $empleado['id']; ?>">
                                <td><?php echo $empleado['legajo']; ?></td>
                                <td><?php echo $empleado['cuit']; ?></td>
                                <td><?php echo $empleado['dni']; ?></td>
                                <td><?php echo $empleado['nombre']; ?></td>
                                <td><?php echo $empleado['fechaingreso']; ?></td>
                                <td >
                                    <a href="#"  onclick="loadFormEmpleado(<?php echo $empleado['id']; ?>)" class="button_view">
                                        <?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'imgedit'));?>
                                    </a>
                                    <?php echo $this->Form->postLink(
                                        $this->Html->image('ic_delete_black_24dp.png', array(
                                            'alt' => 'Eliminar',
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
                    </tr>
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
                            <?php echo $this->Form->end(__('Agregar',array('class' =>'btn_aceptar'))); ?>                          
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
                        <td colspan="3">
                            <?php echo $this->Form->input('actividade_id',array(
                                                                'label'=>'Actividad',
                                                                'class'=>'chosen-select',
                                                                'style'=>'width:88%'
                                                                )
                            );?>
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
 <!-- Inicio Popin Editar Facturacion -->
<a href="#x" class="overlay" id="editarFacturacion"></a>
<div id="divNuevoCbu" class="popup" style="width:45%">
        <div id="form_persona">
        <?php echo $this->Form->create('Cliente',array('action'=>'edit')); 
           echo $this->Form->input('id',array('type'=>'hidden'));?>

         <table style="width:100%">
            <tr class="">
                <td><?php echo __('CPA'); ?></th>
                <td colspan="2"><?php echo $this->Form->input('cpa',array('label'=>false, 'div' => false)); ?></td>
               
            </tr>
            <tr class="">      
                <td><?php echo __('Emite Factura A'); ?></td>
                <td valign="middle"><?php echo $this->Form->input('emitefacturaa',array('label'=>'')); ?></td>
                <td><?php echo __('Vencimiento del CAI  '); ?></td>
                <td valign="top"><?php 
                echo $this->Form->input('vtocaia', array(
                                'class'=>'datepicker', 
                                'type'=>'text',
                                'label'=> false,    
                                'div' => false,
                                'value'=>date('d-m-Y',strtotime($this->request->data['Cliente']['fchcumpleanosconstitucion'])),
                                'readonly'=>'readonly')
                     );
                ?></td>                            
            </tr>    
              <tr class="">      
                 <td valign="baseline"><?php echo __('Emite Factura B'); ?></td>
                 <td><?php echo $this->Form->input('emitefacturab',array('label'=>'')); ?></td>
                 <td valign="baseline"><?php echo __('Vencimiento del CAI '); ?></td>
                 <td><?php 
                 echo $this->Form->input('vtocaib', array(
                                'class'=>'datepicker', 
                                'type'=>'text',
                                'label'=>false, 
                                'div' => false,           
                                 'value'=>date('d-m-Y',strtotime($this->request->data['Cliente']['vtocaib'])),
                                'readonly'=>'readonly')
                     );
                 ?></td>                                
            </tr>       
            <tr class="">      
                <td><?php echo __('Emite Factura C'); ?></td>
                <td><?php echo $this->Form->input('emitefacturac',array('label'=>'')); ?></td>
                <td><?php echo __('Vencimiento del CAI  '); ?></td>
                <td valign="top"><?php 
                  $dat1= date('d-m-Y',strtotime($this->request->data['Cliente']['vtocaic']));
                  echo $this->Form->input('vtocaic', array(
                                'class'=>'datepicker', 
                                'type'=>'text',
                                'label'=>false,   
                                'div' => false, 
                                 'value'=>$dat1,
                                'readonly'=>'readonly')
                     );
                ?></td>
            </tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td> 
                    <?php echo $this->Form->end(__('Aceptar'), array('style' =>'display:none')); ?>
                    <a href="#close" class="btn_cancelar" style="margin-top:-28px">Cancelar</a>
                </td>
            <tr>
            </tr>       
        </table>
           
        </div>    
    <a class="close" href="#close"></a>
</div>
<!-- Fin Popin Editar Facturacion -->

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
                        <td id="tdcategoriamonotributo"><?php echo $this->Form->input('categoriamonotributo', array('label' => 'Categori&oacutea Monotributo','style'=>'width:95%','type'=>'select','options'=>$categoriasmonotributos));?>
                        </td>
                        <td colspan="3"><?php echo $this->Form->input('descripcion', array('label' => 'Descripci&oacuten','style'=>'width:95%'));?>
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
        <div id="form_empleado" class="form" style="width: 94%;">
            <?php
            //todo: AGREGAR SI TIENE conyugue, hijos, adherente,
            //todo: codigoactividad, codigosituacion, codigocondicion, codigozona, codigomodalidadcontratacion
            //todo: codigosiniestrado(codigo incapacidad), tipoempresa
            echo $this->Form->create('Empleado',array('class'=>'formTareaCarga','controller'=>'Empelados','action'=>'add')); ?>
            <h3><?php echo __('Agregar Empleado'); ?></h3>
            <?php
            echo $this->Form->input('id',array('type'=>'hidden'));
            echo $this->Form->input('cliente_id',array('default'=>$cliente['Cliente']['id'],'type'=>'hidden'));
            echo $this->Form->input('nombre',array('style'=>'width:150px'));
            echo $this->Form->input('cuit',array('label'=>'CUIT','maxlength'=>'11'));
            echo $this->Form->input('dni',array('label'=>'DNI'));
            echo $this->Form->input('legajo',array('label'=>'Legajo'));
            echo $this->Form->input('categoria',array('label'=>'Categoria'));
            echo $this->Form->input('codigoafip',array('label'=>'Codigo Afip','options'=>array('0','1','2','3')));
            echo "</br>";
            echo $this->Form->input('fechaingreso', array(
                    'class'=>'datepicker',
                    'type'=>'text',
                    'label'=>'Ingreso',
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
            echo $this->Form->input('conveniocolectivotrabajo_id',array('label'=>'Convenio Colectivo de Trabajo'));
            echo $this->Form->input('jornada',array('label'=>'Jornada','type'=>'select','options'=>array('0.5'=>"Media Jornada",'1'=>"Jornada Completa")));
            echo $this->Form->input('exentocooperadoraasistencial',array('label'=>'Excento Coop. Asistencial','value'=>0))."</br>";

            echo $this->Form->input('conyugue',array('label'=>'Conyugue','value'=>0));
            echo $this->Form->input('hijos',array('label'=>'Hijos','value'=>0));
            echo $this->Form->input('adherente',array('label'=>'Adherentes','value'=>0));
            echo $this->Form->input('codigoactividad',array('label'=>'Codigo Actividad','options'=>$codigoactividad));
            echo $this->Form->input('codigosituacion',array('label'=>'Codigo Situacion'));
            echo $this->Form->input('codigocondicion',array('label'=>'Codigo Condicion'));
            echo $this->Form->input('codigozona',array('label'=>'Codigo Zona','options'=>$codigozona));
            echo $this->Form->input('codigomodalidadcontratacion',array('label'=>'Codigo Modalidad Contratacion','options'=>$codigomodalidadcontratacion));
            echo $this->Form->input('codigosiniestrado',array('label'=>'Codigo Siniestrado','options'=>$codigosiniestrado));
            echo $this->Form->input('tipoempresa',array('label'=>'Tipo empresa','options'=>$tipoempresa))."</br>";

            echo $this->Form->label("Liquidaciones:");
            echo $this->Form->input('liquidaprimeraquincena',array('label'=>'Primera Quincena'));
            echo $this->Form->input('liquidasegundaquincena',array('label'=>'Segunda Quincena'));
            echo $this->Form->input('liquidamensual',array('label'=>'Mensual'));
            echo $this->Form->input('liquidapresupuestoprimera',array('label'=>'Presupuesto 1'));
            echo $this->Form->input('liquidapresupuestosegunda',array('label'=>'Presupuesto 2'));
            echo $this->Form->input('liquidapresupuestomensual',array('label'=>'Presupuesto 3'));
            echo $this->Form->end(__('Aceptar')); ?>
        </div>
        <a class="close" href="#close"></a>
    </div>
    <a href="#x" class="overlay" id="modificar_empleado"></a>
        <div class="popup" id="form_modificar_empleado"></div>
        <a class="close" href="#close"></a>
    </div>

<!-- Fin Popin Nuevo Punto de venta -->
<?php }//fin if(mostrarView) ?>
<!-- Fin Popin Nuevo ImpClis DGRM --> 