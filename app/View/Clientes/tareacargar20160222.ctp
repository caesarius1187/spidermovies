<?php echo $this->Html->script('jquery-ui.js',array('inline'=>false));?>
<?php echo $this->Html->script('clientes/tareacargar',array('inline'=>false));?>
<div class="index" style="float:none; width: 125%; margin: 0px 4px">  
  <div id="headerCarga" style="height:48px;text-align: center;">
    <div style="padding: 0px">
      <table cellpadding="0" cellspacing="0">
          <tr>
            <td>Cliente</td>
            <td><?php echo $cliente["Cliente"]['nombre']?></td>
            <td>Periodo</td>
            <td><?php echo $periodo?></td>
            <td> 
              <?php echo $this->Form->button('Finalizar', 
                      array('type' => 'button',
                        'class' =>"btn_realizar_tarea",
                        'onClick' => "realizarEventoCliente(".$periodo.",".$cliente["Cliente"]['nombre'].",realizado)"
                        )
              );?> 
          </td>
          </tr>
      </table>
        <?php /*echo $this->Form->button('Realizado', 
                      array('type' => 'button',
                        'class' =>"btn_realizar_tarea",
                        'onClick' => "window.location.href='".Router::url(array(
                                          'controller'=>'Clientes', 
                                          'action'=>'add')
                                          )."'"
                        )
            );*/?> 
    </div>  
  </div>
    <?php /**************************************************************************/ ?>
    <?php /*****************************TABS*****************************************/ ?>
    <?php /**************************************************************************/ ?> 
  <div id="bodyCarga"  >
    <div class="" style="width:100%;height:30px;">
      <div class="cliente_view_tab_active" style="width:23%;"  onClick="" id="tabVentas">
        <?php
           echo $this->Form->label(null, $text = 'Ventas',array('style'=>'text-align:center;margin-top:5px;cursor:pointer')); 
         ?>
      </div>
      <div class="cliente_view_tab" style="width:23%;"  onClick="" id="tabCompras">
        <?php
            echo $this->Form->label(null, $text = 'Compras',array('style'=>'text-align:center;margin-top:5px;cursor:pointer'));
         ?>
      </div>
      <div class="cliente_view_tab" style="width:23%;"  onClick="" id="tabNovedades">
        <?php
            echo $this->Form->label(null, $text = 'Novedades',array('style'=>'text-align:center;margin-top:5px;cursor:pointer'));

            //$this->Html->image('cli_view.png', array('alt' => '','id'=>'imgcli','class'=>'','style'=>'margin-left: 50%;'));
         ?>
      </div>
      <div class="cliente_view_tab" style="width:23%;" onClick="" id="tabRetenciones">
        <?php
            echo $this->Form->label(null, $text = 'Retenciones',array('style'=>'text-align:center;margin-top:5px;cursor:pointer'));

            //$this->Html->image('cli_view.png', array('alt' => '','id'=>'imgcli','class'=>'','style'=>'margin-left: 50%;'));
         ?>
      </div>
    </div>
    <?php /**************************************************************************/ ?>
    <?php /*****************************Ventas**************************************/ ?>
    <?php /**************************************************************************/ ?> 
    <div id="form_venta" class="tabVentas" style="overflow:auto;width:100%;margin: 0 0 0 0%;">             
      <?php
          echo $this->Form->create('Venta',array('id'=>'saveVentasForm','action'=>'addajax')); 
          echo $this->Form->input('cliente_id',array('default'=>$cliente["Cliente"]['id'],'type'=>'hidden'));
       ?> 
      <table class="tareaCargarFormTable" style="border:1px solid white" cellspacing="0" cellpading="0">
        <tr>
            <td>Fechass</td>
            <td>Numero Comprobante</td>
            <td>Cliente</td>
            <td>Localidad</td>
            <td>Alicuota</td>
            <td>Neto</td>
            <td>IVA</td>
            <td>IVA Percep</td>
            <td>Act vs Percep</td>
            <td>Imp Internos</td>
            <td>No Gravados</td>
            <td>Excentos</td>
            <td>Otros</td>
            <td>Total</td>
        </tr>
        <tr class="">
          <td class="tareaCargarFormTD"><?php
              echo $this->Form->input('fecha', array(
                      'class'=>'datepicker', 
                      'type'=>'text',
                      'label'=>'',
                      'default'=>"",
                      'readonly'=>'readonly',
                      'required'=>true
                      )
               );?>                                
          </td>                                                                                    
          <td class="tareaCargarFormTD" style="padding:0">
            <table style="margin:0;" cellspacing="0" cellpadding="0">
              <tr>
                <td class="tareaCargarFormTD" style="padding:0"><?php             
                    //Aca tenemos que sacar los tipos de comprobantes que el cliente puede emitir                              
                    echo $this->Form->input('tipocomprobante', array(
                        'options' => array(
                            'A'=>'A', 
                            'B'=>'B', 
                            'C'=>'C',                                         
                            ),                              
                        'label'=> ' ',
                        'style'=>'width:49px;padding:0;margin:0')
                    ); 
                    ?>
                </td>
               <td class="tareaCargarFormTD">                                   
                <?php                      
                    echo $this->Form->input('puntosdeventa_id', array(
                        'options' => $puntosdeventas,                              
                        'label'=> '',
                        'style'=>'width:49px;padding:0;margin:0'
                        )
                    ); 
                    ?>
                </td>
               <td class="tareaCargarFormTD" style="padding:0">
                      <?php                   
                  echo $this->Form->input('numerocomprobante', array(
                      'label'=> ' ',
                        'style'=>'width:49px;padding:0;margin:0'
                      )
                  );  
                  ?>
                </td>                
              </tr>
            </table>  
          </td>
          <td class="tareaCargarFormTD"><?php                   
                echo $this->Form->input('subcliente_id', array(
                    'options' => $subclientes,                              
                    'label'=> '',
                    )
                );  
                ?>
          </td>
          <td class="tareaCargarFormTD">          
              <?php  echo $this->Form->input('localidade_id',array('label'=>'','options'=>$localidades)); ?>
          </td>
          <td class="tareaCargarFormTD"><?php                   
                echo $this->Form->input('alicuota',array(
                   'options' => $alicuotas,
                   'style'=>'width:60px',
                   'label'=>''
                   
                    ));    
                ?>
          </td>
          <td class="tareaCargarFormTD"><?php                   
              echo $this->Form->input('neto',array(
                  'label' => ''
                  ));    
              ?>
          </td> 
          <td class="tareaCargarFormTD"><?php                   
              echo $this->Form->input('iva',array(
                  'label' => ''
                  ));    
              ?>
          </td>
          <td class="tareaCargarFormTD"><?php                   
              echo $this->Form->input('ivapercep',array(
                    'label'=> '',                              
                  ));    
              ?>
          </td>
          <td class="tareaCargarFormTD"><?php                   
              echo $this->Form->input('actvspercep',array(
                    'label'=> '',
                  ));    
              ?>
          </td>
          <td class="tareaCargarFormTD"><?php                   
              echo $this->Form->input('impinternos',array(
                  'label'=> '',
                  ));    
              ?>
          </td>
          <td class="tareaCargarFormTD"><?php                   
              echo $this->Form->input('nogravados',array(
                    'label'=> '',
                  ));    
              ?>
          </td>
          <td class="tareaCargarFormTD"><?php                   
              echo $this->Form->input('excentos',array(
                    'label' => ''
                  ));     
              ?>
          </td>
          <td class="tareaCargarFormTD"><?php                  
              echo $this->Form->input('comercioexterior',array(
                  'label'=>'',
                  ));      
              ?>
          </td>
          <td class="tareaCargarFormTD"><?php                 
              echo $this->Form->input('total',array(
                  'label'=>''
                  ));     
              ?>
          </td>
          <td class="tareaCargarFormTD">               
              <?php                  
               echo $this->Form->input('asiento',array('type'=>'hidden'));      
              ?>
              <?php                  
                echo $this->Form->input('periodo',array('default'=>$periodo,'type'=>'hidden'));      
              ?>        
              <?php echo $this->Form->submit('+', array('type'=>'image','src' => '/sigesec/img/add_view.png','class'=>'imgedit','style'=>'width:25px;height:25px;','div'=>false));  ?>  
              <?php echo $this->Form->end();  ?>  
          </td>
                                            
        </tr>
      </table>                    
    </div>        
    <div style="overflow:auto;width:100%;margin: 0 0 0 0%;" class="tareaCargarIndexTable tabVentas">
      <table class="tbl_vtas_det" style="border:1px solid white" id="bodyTablaVentas" cellspacing="0" cellpadding="0" >
        <thead>
          <tr>
            <td>Fecha</td>
            <td>Comprobante</td>
            <td>Cliente</td>
            <td>Provincia</td>
            <td>Localidad</td>
            <td>Alicuota</td>
            <td>Neto</td>
            <td>IVA</td>
            <td>IVA Percep</td>
            <td>Act Vs Perc</td>
            <td>Imp Internos</td>
            <td>No Gravados</td>
            <td>Excentos</td>
            <td>Otros</td>
            <td>Total</td>
            <td></td>
          </tr>
        </thead>
        <tbody id="bodyTablaVentas">
          <?php
          foreach($cliente["Venta"] as $venta ){
            echo $this->Form->create('Venta',array('controller'=>'Venta','action'=>'edit')); 
            $tdClass = "tdViewVenta".$venta["id"];
            ?>
            <tr id="rowventa<?php echo $venta["id"]?>"> 
              <td class="<?php echo $tdClass?>"><?php echo $venta["fecha"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $venta["tipocomprobante"]?>-
              <?php if(isset($venta["Subcliente"]["nombre"])) echo $venta["Puntosdeventa"]['nombre']?>-
              <?php echo $venta["numerocomprobante"]?></td>
              <td class="<?php echo $tdClass?>"><?php if(isset($venta["Subcliente"]["nombre"])) echo $venta["Subcliente"]["nombre"]?></td>
              <td><?php if(isset($venta["Localidade"]["Partido"]["nombre"])) echo $venta["Localidade"]["Partido"]["nombre"]?></td>
              <td class="<?php echo $tdClass?>"><?php if(isset($venta["Localidade"]["nombre"])) echo $venta["Localidade"]["nombre"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $venta["alicuota"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $venta["neto"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $venta["iva"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $venta["ivapercep"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $venta["actvspercep"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $venta["impinternos"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $venta["nogravados"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $venta["excentos"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $venta["comercioexterior"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $venta["total"]?></td>
              <td class="<?php echo $tdClass?>"> 
                <?php 
                $paramsVenta=$venta["id"];
                echo $this->Html->image('edit_view.png',array('width' => '20', 'height' => '20','onClick'=>"modificarVenta(".$paramsVenta.")"));
                echo $this->Form->end();  ?>  
              </td>
            </tr>
            <?php

          }
          ?>
        </tbody>
      </table>  
    </div> 
   <?php /**************************************************************************/ ?>
   <?php /*****************************Compras**************************************/ ?>
   <?php /**************************************************************************/ ?>        
    <div id="form_compra" class="tabCompras" style="overflow:auto;width:100%;margin: 0 0 0 0%;">             
      <?php
          echo $this->Form->create('Compra',array('id'=>'saveComprasForm','action'=>'addajax')); 
          echo $this->Form->input('cliente_id',array('default'=>$cliente["Cliente"]['id'],'type'=>'hidden'));
       ?> 
      <table class="tareaCargarFormTable tabCompras" style="border:1px solid white" cellspacing="0" cellpading="0">
        <tr class="">
          <td class="tareaCargarFormTD"><?php
              echo $this->Form->input('fecha', array(
                      'class'=>'datepicker', 
                      'type'=>'text',
                      'label'=>'Fecha',
                      'default'=>"",
                      'readonly'=>'readonly',
                      'required'=>true
                      )
               );?>                                
          </td>
          <td class="tareaCargarFormTD" style="padding:0" >
            <table style="margin:0;" cellspacing="0" cellpadding="0">
              <tr>
                <td>Numero Comprobante</td>
              </tr>
              <tr>
                <td class="tareaCargarFormTD" style="padding:0"><?php             
                    //Aca tenemos que sacar los tipos de comprobantes que el cliente puede emitir                              
                    echo $this->Form->input('tipocomprobante', array(
                        'options' => array(
                            'A'=>'A', 
                            'B'=>'B', 
                            'C'=>'C',                                         
                            ),                              
                        'label'=> ' ',
                        'style'=>'width:49px;padding:0;margin:0')
                    ); 
                    ?>
                </td>
                <td class="tareaCargarFormTD" style="padding:0">                                   
                <?php                      
                    echo $this->Form->input('puntosdeventa_id', array(
                        'options' => $puntosdeventas,                              
                        'label'=> ' ',
                        'style'=>'width:49px;padding:0;margin:0'
                        )
                    ); 
                    ?>
                </td>
                <td class="tareaCargarFormTD" style="padding:0">
                      <?php                   
                  echo $this->Form->input('numerocomprobante', array(
                      'label'=> ' ',
                        'style'=>'width:49px;padding:0;margin:0'
                      )
                  );  
                  ?>
                </td>                
              </tr>
            </table>  
          </td>
          <td class="tareaCargarFormTD"><?php                   
              echo $this->Form->input('subcliente_id', array(
                  'options' => $subclientes,                              
                  'label'=> 'Cliente',
                  )
              );  
              ?>
          </td>
           <td class="tareaCargarFormTD"><?php
              echo $this->Form->input('condicioniva', array(                      
                      'label'=>'Cond. IVA',
                      )
               );?>                                
          </td> 
          <td class="tareaCargarFormTD"><?php
              echo $this->Form->input('tipogasto', array(                      
                      'label'=>'Tipo Gasto',
                      )
               );?>                                
          </td>      
          <td class="tareaCargarFormTD"><?php                   
            echo $this->Form->input('partido_id',array( ));   
             echo $this->Form->input('localidade_id',array( ));    
              ?>
          </td>
          <td class="tareaCargarFormTD"><?php
              echo $this->Form->input('imputacion');?>                                
          </td>  
          <td class="tareaCargarFormTD"><?php
              echo $this->Form->input('tipocredito');?>                                
          </td> 
                                                                                  
          <td class="tareaCargarFormTD"><?php                   
              echo $this->Form->input('alicuota',array(
                 'options' => $alicuotas,
                  'style'=>'width:60px'));    
              ?>
          </td>
          <td class="tareaCargarFormTD"><?php                   
              echo $this->Form->input('neto',array(
                  'label'=>''));    
              ?>
          </td>           
          <td class="tareaCargarFormTD"><?php                   
              echo $this->Form->input('iva',array(
                  'label'=>''));    
              ?>
          </td>
          <td class="tareaCargarFormTD"><?php                   
              echo $this->Form->input('tipoiva',array(
                    'label'=>'Tipo (IVA)',
                    'options'=>array('directo'=>'Directo','prorateable'=>'Prorateable')
                  ));    
              ?>
          </td>
          <td class="tareaCargarFormTD"><?php                   
              echo $this->Form->input('ivapercep',array(
                    'label'=> 'IVA Percep',                              
                  ));    
              ?>
          </td>
           <td class="tareaCargarFormTD"><?php                   
              echo $this->Form->input('iibbpercep',array(
                    'label'=> 'IIBB Percep',                              
                  ));    
              ?>
          </td>
          <td class="tareaCargarFormTD"><?php                   
              echo $this->Form->input('actvspercep',array(
                    'label'=> 'Act vs Percep',
                  ));    
              ?>
          </td>
          <td class="tareaCargarFormTD"><?php                   
              echo $this->Form->input('impinternos',array(
                  'label'=> '',
                  ));    
              ?>
          </td>
          <td class="tareaCargarFormTD"><?php                   
              echo $this->Form->input('impcombustible',array(
                  'label'=> 'Imp Comb',
                  ));    
              ?>
          </td>
          <td class="tareaCargarFormTD"><?php                   
              echo $this->Form->input('nogravados',array(
                    'label'=> '',
                  ));    
              ?>
          </td> 
          <td class="tareaCargarFormTD"><?php                   
              echo $this->Form->input('nogravadogeneral',array(
                    'label'=>'',
                  ));    
              ?>
          </td>         
          <td class="tareaCargarFormTD"><?php                 
              echo $this->Form->input('total',array(
                    'label'=>'Total'
                  ));     
              ?>
          </td>
          <td class="tareaCargarFormTD">               
              <?php                  
               echo $this->Form->input('asiento',array('type'=>'hidden'));      
              ?>
              <?php                  
                echo $this->Form->input('periodo',array('default'=>$periodo,'type'=>'hidden'));      
              ?>        
              <?php echo $this->Form->submit('+', array('type'=>'image','src' => '/sigesec/img/add_view.png','class'=>'imgedit','style'=>'width:25px;height:25px;','div'=>false));  ?>  
              <?php echo $this->Form->end();  ?>  
          </td>                                          
        </tr>
      </table>                
    </div>
    <div style="overflow:auto;width:100%;margin: 0 0 0 0%;" class="tareaCargarIndexTable tabCompras">
      <table class="" style="border:1px solid white" id="bodyTablaCompras">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Comprobante</th>
            <th>SubCliente</th>
            <th>Cond.IVA</th>
            <th>Tipo Gasto</th>
            <th>Localidad</th>
            <th>Impuntacion</th>
            <th>Tipo Cred</th>
            <th>Alicuota</th>
            <th>Neto</th>
            <th>IVA</th>
            <th>Tipo(IVA)</th>
            <th>IVA Percep</th>
            <th>IIBB Percep</th>
            <th>Act Vs Perc</th>
            <th>Imp Internos</th>
            <th>No Gravados</th>
            <th>No Grav Gral</th>
            <th>Total</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="bodyTablaCompras">
          <?php
          foreach($cliente["Compra"] as $compra ){
            echo $this->Form->create('Compra',array('controller'=>'Compra','action'=>'edit')); 
            $tdClass = "tdViewCompra".$compra["id"];
            ?>
            <tr id="rowcompra<?php echo $compra["id"]?>"> 
              <td class="<?php echo $tdClass?>"><?php echo $compra["fecha"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $compra["tipocomprobante"]?>-
              <?php if(isset($compra["Subcliente"]["nombre"])) echo $compra["Puntosdeventa"]['nombre']?>-
              <?php echo $compra["numerocomprobante"]?></td>
              <td class="<?php echo $tdClass?>"><?php if(isset($compra["Subcliente"]["nombre"])) echo $compra["Subcliente"]["nombre"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $compra["condicioniva"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $compra["tipogasto"]?></td>              
              <td class="<?php echo $tdClass?>"><?php if(isset($compra["Localidade"]["nombre"])) echo $compra["Localidade"]["nombre"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $compra["imputacion"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $compra["tipocredito"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $compra["alicuota"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $compra["neto"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $compra["iva"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $compra["tipoiva"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $compra["ivapercep"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $compra["iibbpercep"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $compra["actvspercep"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $compra["impinternos"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $compra["nogravados"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $compra["nogravadogeneral"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $compra["total"]?></td>
              <td class="<?php echo $tdClass?>"> 
                <?php 
                $paramsCompra=$compra["id"];
                echo $this->Html->image('edit_view.png',array('width' => '20', 'height' => '20','onClick'=>"modificarCompra(".$paramsCompra.")"));
                echo $this->Form->end(); 
                ?> 
              </td>
            </tr>
            <?php

          }
          ?>
        </tbody>
      </table>  
    </div> 
    <?php /**************************************************************************/ ?>
   <?php /*****************************Novedades**************************************/ ?>
   <?php /**************************************************************************/ ?>        
    <div id="form_sueldo" class="tabNovedades" style="overflow:auto;width:50%;margin: 0 0 0 0%;">           
      <?php
      echo $this->Form->create('Sueldo',array('controller'=>'sueldos','id'=>'saveSueldosForm','action'=>'addajax')); 
      echo $this->Form->input('cliente_id',array('default'=>$cliente["Cliente"]['id'],'type'=>'hidden'));
      ?> 
      <table class="tareaCargarFormTable tabNovedades" style="border:1px solid white" cellspacing="0" cellpading="0">
        <tr class="">
          <td class="tareaCargarFormTD"><?php
              echo $this->Form->input('fecha', array(
                      'class'=>'datepicker', 
                      'type'=>'text',
                      'label'=>'Fecha',
                      'default'=>"",
                      'readonly'=>'readonly',
                      'required'=>true
                      )
               );?>                                
          </td>
          <td class="tareaCargarFormTD"><?php                   
              echo $this->Form->input('monto', array(
                  'label'=> 'Monto',
                  )
              );  
              ?>
          </td>
          <td class="tareaCargarFormTD">               
              <?php echo $this->Form->submit('+', array('type'=>'image','src' => '/sigesec/img/add_view.png','class'=>'imgedit','style'=>'width:25px;height:25px;','div'=>false));  ?>  
              <?php echo $this->Form->end();  ?>  
          </td>                                          
        </tr>
      </table>                
    </div>
    <div style="overflow:auto;width:50%;margin: 0 0 0 0%;" class="tareaCargarIndexTable tabNovedades">
      <table class="" style="border:1px solid white" id="bodyTablaSueldos">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Monto</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="bodyTablaSueldos">
          <?php
          foreach($cliente["Sueldo"] as $sueldo ){
            echo $this->Form->create('Sueldo',array('controller'=>'Sueldo','action'=>'edit')); 
            $tdClass = "tdViewSueldo".$sueldo["id"];
            ?>
            <tr id="rowsueldo<?php echo $sueldo["id"]?>"> 
              <td class="<?php echo $tdClass?>"><?php echo $sueldo["fecha"]?></td>
              <td class="<?php echo $tdClass?>"><?php echo $sueldo["monto"]?></td>
              <td class="<?php echo $tdClass?>"> 
                <?php 
                $paramsSueldo=$sueldo["id"];
                echo $this->Html->image('edit_view.png',array('width' => '20', 'height' => '20','onClick'=>"modificarSueldo(".$paramsSueldo.")"));
                echo $this->Form->end();  ?>  
              </td>
            </tr>
            <?php
          }
          ?>
        </tbody>
      </table>  
    </div> 
  </div>
</div>
<!-- Inicio Popin VerEventoCliente -->
<a href="#x" class="overlay" id="popInModificarVenta"></a>
<div  class="popup">
<div class='section body' id="divModificarVenta">
   
</div>
<a class="close" href="#close"></a>
</div>
<!-- Fin Popin VerEventoCliente--> 