<?php
echo $this->Html->script('jquery-ui.js',array('inline'=>false));
echo $this->Html->script('languages.js',array('inline'=>false));
echo $this->Html->script('numeral.js',array('inline'=>false));
echo $this->Html->script('moment.js',array('inline'=>false));
echo $this->Html->script('jquery-calx-2.2.6',array('inline'=>false));
echo $this->Html->script('jquery.dataTables.js',array('inline'=>false));
echo $this->Html->script('clientes/tareacargar',array('inline'=>false));
//Vamos a guardar las variables de configuracion del cliente para consultarlas a todas de las misma manera
/*AFIP*/
$tieneMonotributo=$cliente["Cliente"]['tieneMonotributo'];
$tieneIVA=$cliente["Cliente"]['tieneIVA'];
$tieneIVAPercepciones=$cliente["Cliente"]['tieneIVAPercepciones'];
$tieneImpuestoInterno=$cliente["Cliente"]['tieneImpuestoInterno'];
/*DGR*/
$tieneAgenteDePercepcionIIBB=$cliente["Cliente"]['tieneAgenteDePercepcionIIBB'];
/*DGRM*/
$tieneAgenteDePercepcionActividadesVarias=$cliente["Cliente"]['tieneAgenteDePercepcionActividadesVarias'];
?>
<!--<div class="" style="float:none; width: 100%; margin: 0px 4px">  -->
  <div class="index" style="padding: 0px 1%; margin-bottom: 10px;" id="headerCliente">
      <div style="width:30%; float: left;padding-top:10px">
        Cliente: <?php echo $cliente["Cliente"]['nombre']?>
      </div>
      <div style="width:25%; float: left;padding-top:10px">
        Periodo: <?php echo $periodo?>
      </div>
      <div style="float:right; width:45%">
      <?php echo $this->Form->button('Finalizar', 
              array('type' => 'button',
                'class' =>"btn_realizar_tarea",
                'div' => false,
                'style' => array('style' => 'float:right'),
                'onClick' => "realizarEventoCliente('".$periodo."',".$cliente["Cliente"]['id'].",'realizado')"
                )
      );?> 
      </div>
  </div>  
  <div id="headerCarga" style="width:100%">
    
  <!--</div>-->
    <?php /**************************************************************************/ ?>
    <?php /*****************************TABS*****************************************/ ?>
    <?php /**************************************************************************/ ?> 
  <div id="bodyCarga" style="width:100%;height:35px;">
    <div class="" style="width:100%;height:30px; margin-left:10px " id="divAllTabs">
      <div class="cliente_view_tab_active" style="width:23%;margin-right:0px"  onClick="" id="tabVentas">
        <?php
           echo $this->Form->label(null, $text = 'Ventas',array('style'=>'text-align:center;margin-top:5px;cursor:pointer')); 
         ?>
      </div>
      <div class="cliente_view_tab" style="width:23%;margin-right:0px"  onClick="" id="tabCompras">
        <?php
            echo $this->Form->label(null, $text = 'Compras',array('style'=>'text-align:center;margin-top:5px;cursor:pointer'));
         ?>
      </div>
      <div class="cliente_view_tab" style="width:23%;margin-right:0px"  onClick="" id="tabNovedades">
        <?php
            echo $this->Form->label(null, $text = 'Empleados',array('style'=>'text-align:center;margin-top:5px;cursor:pointer'));
            //$this->Html->image('cli_view.png', array('alt' => '','id'=>'imgcli','class'=>'','style'=>'margin-left: 50%;'));
         ?>
      </div>
      <div class="cliente_view_tab" style="width:23%;" onClick="" id="tabConceptosrestantes">
        <?php
            echo $this->Form->label(null, $text = 'Pagos a cuenta',array('style'=>'text-align:center;margin-top:5px;cursor:pointer'));
            //$this->Html->image('cli_view.png', array('alt' => '','id'=>'imgcli','class'=>'','style'=>'margin-left: 50%;'));
         ?>
      </div>
    </div>
    <?php /**************************************************************************/ ?>
    <?php /*****************************Ventas***************************************/ ?>
    <?php /**************************************************************************/ ?> 
    <div id="form_venta" class="tabVentas index" style="width:96%;float:left; margin-left:5px;margin-top:10px;">
      <?php
          //****Aca vamos a controlar los Impuestos con periodo activo que influyen en los campos que se van a mostrar en el formulario de ventas******/
          /*
            AFIP(Venta)
              Monotributista
                -CondIVA
                -Tipo de Debito
                -Alicuota
                -Neto
                -IVA
              Responsable Inscripto(IVA)
                +CondIVA
                +Tipo de Debito
                +Alicuota
                +Neto
                +IVA
              IVA Percepciones (Agregar Impuesto en el Organismo IVA )
               +Iva Percepciones
              
              Impuesto Interno (Agregar este impuesto)
                +ImpInterno
          DGR(solo para Ventas)
              Agente de Percepción de IIBB
                   +IIBB Percep(Agente de percepción de ingresos brutos)
             {*El impuesto Actividades Economicas deberia llamarse Ingresos Brutos*}

          DGRM(solo para Ventas)
              Agente de Percepción de Actividades Varias(TISH)(Agregar Este Impuesto)
          */
          
          
          echo $this->Form->input('jsonallcomprobantes',array(
                  'value'=>json_encode($allcomprobantes) ,
                  'type'=>'hidden',
                  "id"=>"jsonallcomprobantes"
                  )
          );
          /*echo $this->Form->input('jsonlocalidadpuntosdeventa',array(
                  'value'=>json_encode($localidadpuntosdeventa) ,
                  'type'=>'hidden',
                  "id"=>"jsonlocalidadpuntosdeventa"
                  )
          );*/
          echo $this->Html->link(
              "Importar Ventas",
              array(
                  'controller' => 'ventas',
                  'action' => 'importar',
                  $cliente["Cliente"]['id'],
                  $periodo
              ),
              array('class' => 'buttonImpcli',
                  'style'=> 'width:'
              )
          );
        echo $this->Form->create('Venta',array(
            'id'=>'saveVentasForm',
            'action'=>'addajax',
            'class'=>'formTareaCarga formAddVenta',
            $cliente["Cliente"]['id'],
            $periodo
            )
          );
        echo $this->Form->input('cliente_id',array('default'=>$cliente["Cliente"]['id'],'type'=>'hidden'));
        //Vamos a enviar la situacion del cliente para no recalcularla en el controlador cada ves que guardemos una venta
        /*AFIP*/
        echo $this->Form->input('tieneMonotributo',array('value'=>$tieneMonotributo,'type'=>'hidden'));
        echo $this->Form->input('tieneIVA',array('value'=>$tieneIVA,'type'=>'hidden'));
        echo $this->Form->input('tieneIVAPercepciones',array('value'=>$tieneIVAPercepciones,'type'=>'hidden'));
        echo $this->Form->input('tieneImpuestoInterno',array('value'=>$tieneImpuestoInterno,'type'=>'hidden'));
        /*DGR*/
        echo $this->Form->input('tieneAgenteDePercepcionIIBB',array('value'=>$tieneAgenteDePercepcionIIBB,'type'=>'hidden'));
        /*DGRM*/
        echo $this->Form->input('tieneAgenteDePercepcionActividadesVarias',array('value'=>$tieneAgenteDePercepcionActividadesVarias,'type'=>'hidden'));
        echo $this->Form->input('fecha', array(
                'class'=>'datepicker-dia',
                'style'=>'width:40px',
                'type'=>'text',
                'default'=>"",
                'readonly'=>'readonly',
                'required'=>true
                )
         );
            //Aca tenemos que sacar los tipos de comprobantes que el cliente puede emitir                              
            echo $this->Form->input('comprobante_id', array(
                'label'=>'Comprobante',
                'style'=>'width:71px',
                'class' => 'chosen-select',  
              )                              
            ); 
            echo $this->Form->input('puntosdeventa_id', array(
                'label'=>'&nbsp',
                //'label'=> false,
                'style'=>'width:73px',
                'options' => $puntosdeventas,  
                'class' => 'chosen-select',                              
                )
            ); 
            echo $this->Form->input('numerocomprobante', array(
                'label'=>'&nbsp',                
                )
            );  
            echo $this->Form->input('subcliente_id', array(
                'options' => $subclientes, 
                'label' => 'Nom/DNI/CUIT',                             
                'required' => true,                             
                'class' => 'chosen-select',  
                'style' => 'width:150px'  
                )
            );   

            echo '<div>';           
            echo $this->Form->button($this->Html->image("addcli_view.png", 
                                                  array(
                                                    'alt' => 'Agregar',
                                                    'style'=>'width:20px;height:20px;', 
                                                    )
                                                  )."",
                                                  array(
                                                    'class'=>"btnAgregar",
                                                    'escape'=>false,
                                                    //'div' => true,
                                                    'type'=>"button",
                                                    'title'=>'Agregar Cliente',
                                                    'style'=>'margin-top:15px; cursor: pointer;',
                                                    'onClick'=>"location.href='#nuevo_subcliente'"
                                                  )
                                ); 
            echo '</div>';           

            echo $this->Form->input('condicioniva',array(
                'type'=>'select',
                'label'=>'Cond. IVA',
                'options'=>$condicionesiva,
                'class' => 'chosen-select',  
                'style' => 'width:150px' 
                ));    

            echo $this->Form->input('actividadcliente_id',array(
                'type'=>'select',
                'label'=>'Actividad',
                'options'=>$actividades,
                'class' => 'chosen-select',  
                'style' => 'width:150px' 

                ));    
            echo $this->Form->input('localidade_id',array(
              'label'=>'Localidad',
              'class'=>"chosen-select",
              'style' => 'width:100px'
              ));    
            
            if($tieneMonotributo){                
              echo $this->Form->input('tipodebito',array(
              'options' => $tipodebitos,
              'label' => 'Tipo Debito',
              'class' => 'chosen-select',  
              'style' => 'width:80px'
                ));
              echo $this->Form->input('alicuota',array(
              'options' => $alicuotas,
              'class' => 'chosen-select',
              'style' => 'width:60px' 
                )); 
              echo $this->Form->input('neto',array(
                'readonly'=>'readonly',
                'style' => 'width:75px'
                ));    
              echo $this->Form->input('iva',array(
                'readonly'=>'readonly',
                'style' => 'width:75px'
                ));    
            }else{
              echo $this->Form->input('tipodebito',array(
              'options' => $tipodebitos,
              'label' => 'Tipo Debito',
              'class' => 'chosen-select',  
              'style' => 'width:150px' 
                ));
              echo $this->Form->input('alicuota',array(
              'options' => $alicuotas,
              'class' => 'chosen-select',  
              'style' => 'width:60px' 
                )); 
              echo $this->Form->input('neto',array(
                'readonly'=>'readonly',
                'style' => 'width:75px'
                ));    
              echo $this->Form->input('iva',array(
                'readonly'=>'readonly',
                'style' => 'width:75px'
                ));  
            }   
            if($tieneIVAPercepciones){
              echo $this->Form->input('ivapercep',array(
                ));
            }
            if($tieneAgenteDePercepcionIIBB){
              
              echo $this->Form->input('iibbpercep',array(
                ));
            }
            if($tieneAgenteDePercepcionActividadesVarias){
              echo $this->Form->input('actvspercep',array(
                ));
            }
            if($tieneImpuestoInterno){
              echo $this->Form->input('impinternos',array(
                ));
            }
            echo $this->Form->input('nogravados',array(
              'label'=>'No Gravados',
              'style' => 'width:75px'
            ));
            echo $this->Form->input('excentos',array(
                'label'=>'Exento',
                'style' => 'width:75px'
            ));
            echo $this->Form->input('exentosactividadeseconomicas',array(
                'label'=>'Exento Act. Eco.',
                'style' => 'width:75px'
            ));
            echo $this->Form->input('exentosactividadesvarias',array(
                'label'=>'Exento Act. Var.',
                'style' => 'width:75px'
            ));
            echo $this->Form->input('total',array(
                'style' => 'width:75px'
                ));     
            echo $this->Form->input('asiento',array(
                'type'=>'hidden'
            ));      
            echo $this->Form->input('periodo',array('default'=>$periodo,'type'=>'hidden'));      
            echo $this->Form->submit('+', array(
                      'type'=>'image',
                      'src' => $this->webroot.'img/add_view.png',
                      'class'=>'imgedit',
                      'style'=>'width:25px;height:25px;margin-top:8px',
                      'title'=>'Agregar'
                      )
                );  
            echo $this->Form->end();  ?>  
    </div>        
    <div style="overflow-x:auto;width:96%; float:left;margin-left: 5px;margin-top:10px;min-height: 1400px" class="tareaCargarIndexTable tabVentas index">
      <table class="tbl_vtas_det" style="border:1px solid white" id="tablaVentas" cellspacing="0" cellpadding="0" >
        <thead>
          <tr>
            <th style="width: 47px">Fecha</th><!--1-->
            <th style="width: 275px;">Comprobante</th><!--2-->
            <th style="width: 95px;">CUIT</th><!--3-->
            <th style="width: 95px;">Nombre</th><!--4-->
            <th style="width: 95px;">Condicion IVA</th><!--5-->
            <th style="width: 144px;">Actividad</th><!--6-->
            <th style="width: 144px;">Localidad</th><!--7-->
            <?php
            if(!$cliente['Cliente']['tieneMonotributo']){
              echo 
              '<th style="width: 89px;">Debito</th><!--8-->
                <th style="width:64px">Alicuota</th> <!--9-->
               <th style="width: 89px;" class="sum">Neto</th><!--10-->
               <th style="width: 89px;">IVA</th>   <!--11-->
               ';   
            }
            if($cliente['Cliente']['tieneIVAPercepciones']){
              echo 
              '<th style="width: 89px;" >IVA Percep</th><!--12-->';
            }
            if($cliente['Cliente']['tieneAgenteDePercepcionIIBB']){
               echo 
              '<th style="width: 89px;" >IIBB Percep</th><!--13-->';
            }
            if($cliente['Cliente']['tieneAgenteDePercepcionActividadesVarias']){
              echo 
              '<th style="width: 89px;" >Act Vs Perc</th><!--14-->';
            }
            if($cliente['Cliente']['tieneImpuestoInterno']){
              echo 
              '<th style="width: 89px;" >Imp Internos</th><!--15-->';
            }    
            ?>
              <th style="width: 89px;" >No Gravados</th><!--16-->
              <th style="width: 89px;" >Exento</th><!--17-->
              <th style="width: 89px;" >Exento Act. Econom.</th><!--18-->
              <th style="width: 89px;" >Exento Act. Varias</th><!--19-->
              <th style="width: 89px;" class="sum">Total</th><!--20-->
            <th>Acciones</td><!--21-->
          </tr>
        </thead>
        <tbody id="bodyTablaVentas">
          <?php
          foreach($cliente["Venta"] as $venta ){
            echo $this->Form->create('Venta',array('controller'=>'Venta','action'=>'edit'));
            $tdClass = "tdViewVenta".$venta["id"];
            $titleComprobante = $venta["Comprobante"]['nombre']."-".$venta["Puntosdeventa"]['nombre']."-".$venta["numerocomprobante"];?>
            <tr id="rowventa<?php echo $venta["id"]?>">
              <td class="<?php echo $tdClass ?>"><?php echo date('d',strtotime($venta["fecha"]))?></td><!--1-->
              <td class="<?php echo $tdClass ?>" title="<?php echo $titleComprobante; ?>" > <?php echo $titleComprobante; ?> </td><!--2-->
              <td class="<?php echo $tdClass ?>"><?php echo $venta["Subcliente"]["cuit"]?></td><!--3-->
              <td class="<?php echo $tdClass ?>" title="<?php echo $venta["Subcliente"]["nombre"]?>"><?php echo $venta["Subcliente"]["nombre"]?></td><!--4-->
              <td class="<?php echo $tdClass ?>"><?php
                  switch ($venta["condicioniva"]) {
                    case 'monotributista':
                      echo 'Monotribut.';
                    break;
                    case 'responsableinscripto':
                      echo 'Resp. Insci.';
                    break;
                    case 'consf/exento/noalcanza':
                      echo 'Con. Fin/Exe/No Alca.';
                    break;
                    default:
                      echo 'Monotribut.';
                    break;
                  }
                   ?>
                  </td><!--5-->
              <td class="<?php echo $tdClass?>" title="<?php echo $venta["Actividadcliente"]["Actividade"]["nombre"]?>">
                  <?php echo $venta["Actividadcliente"]["Actividade"]["nombre"]?>
              </td><!--6-->
              <td class="<?php echo $tdClass?>" title="<?php echo $venta["Localidade"]["nombre"]?>">
                  <?php echo $venta["Localidade"]['Partido']["nombre"].'-'.$venta["Localidade"]["nombre"]?>
              </td><!--7-->
              <?php
              if($venta['Comprobante']["tipodebitoasociado"]=='Restitucion de debito fiscal'){
                  $venta["neto"] = $venta["neto"]*-1;
                  $venta["total"] = $venta["total"]*-1;
              }
              if(!$cliente['Cliente']['tieneMonotributo']){?>
                <td class="<?php echo $tdClass?>"><?php echo $venta["tipodebito"]?></td><!--8-->
                <td class="<?php echo $tdClass?>"><?php echo number_format($venta["alicuota"], 2, ",", ".")?>%</td><!--9-->
                <td class="<?php echo $tdClass?>"><?php echo number_format($venta["neto"], 2, ",", ".")?></td><!--10-->
                <td class="<?php echo $tdClass?>"><?php echo number_format($venta["iva"], 2, ",", ".")?></td><!--11-->
                <?php
                  //Hay que agregar Condicion frente al IVA ??     
              }   
              if($cliente['Cliente']['tieneIVAPercepciones']){?>
                <td class="<?php echo $tdClass?>"><?php echo number_format($venta["ivapercep"], 2, ",", ".")?></td><!--12-->
                <?php
              }
              if($cliente['Cliente']['tieneAgenteDePercepcionIIBB']){?>
                <td class="<?php echo $tdClass?>"><?php echo number_format($venta["iibbpercep"], 2, ",", ".")?></td><!--13-->
                <?php
              }
              if($cliente['Cliente']['tieneAgenteDePercepcionActividadesVarias']){?>
                <td class="<?php echo $tdClass?>"><?php echo number_format($venta["actvspercep"], 2, ",", ".")?></td><!--14-->
                <?php
              }
              if($cliente['Cliente']['tieneImpuestoInterno']){?>
                <td class="<?php echo $tdClass?>"><?php echo number_format($venta["impinternos"], 2, ",", ".")?></td><!--15-->
                <?php
              }    
              ?>
                <td class="<?php echo $tdClass?>"><?php echo number_format($venta["nogravados"], 2, ",", ".")?></td><!--16-->
                <td class="<?php echo $tdClass?>"><?php echo number_format($venta["excentos"], 2, ",", ".")?></td><!--17-->
                <td class="<?php echo $tdClass?>"><?php echo number_format($venta["exentosactividadeseconomicas"], 2, ",", ".")?></td><!--18-->
                <td class="<?php echo $tdClass?>"><?php echo number_format($venta["exentosactividadesvarias"], 2, ",", ".")?></td><!--19-->
                <td class="<?php echo $tdClass?>"><?php echo number_format($venta["total"], 2, ",", ".")?></td><!--20-->
              <td class="<?php echo $tdClass?>">
                <?php 
                $paramsVenta = $venta["id"];
                echo $this->Html->image('edit_view.png',array('width' => '20', 'height' => '20','onClick'=>"modificarVenta(".$paramsVenta.")"));
                echo $this->Html->image('eliminar.png',array('width' => '20', 'height' => '20','onClick'=>"eliminarVenta(".$paramsVenta.")"));
                echo $this->Form->end();  ?>  
              </td><!--21-->
            </tr>
            <?php
          }
          ?>
        </tbody>
        <tfoot>
          <tr>
              <th >Totales</th><!--1-->
              <th ></th><!--2-->
              <th ></th><!--3-->
              <th ></th><!--4-->
              <th ></th><!--5-->
              <th ></th><!--6-->
              <th ></th><!--7-->
              <?php
              if(!$cliente['Cliente']['tieneMonotributo']){
                  echo
                  '<th></th><!--8-->
                   <th></th><!--9--> 
                   <th></th><!--10-->
                   <th></th><!--11-->   
                   ';
              }
              if($cliente['Cliente']['tieneIVAPercepciones']){
                  echo
                  '<th></th><!--12-->';
              }
              if($cliente['Cliente']['tieneAgenteDePercepcionIIBB']){
                  echo
                  '<th></th><!--13-->';
              }
              if($cliente['Cliente']['tieneAgenteDePercepcionActividadesVarias']){
                  echo
                  '<th></th><!--14-->';
              }
              if($cliente['Cliente']['tieneImpuestoInterno']){
                  echo
                  '<th></th><!--15-->';
              }
              ?>
              <th></th><!--16-->
              <th></th><!--17-->
              <th></th><!--18-->
              <th></th><!--19-->
              <th></th><!--20-->
              <th></th><!--21-->
          </tr>
        </tfoot>
      </table>
    </div> 
   <?php /**************************************************************************/ ?>
   <?php /*****************************Compras**************************************/ ?>
   <?php /**************************************************************************/ ?>        
    <div id="form_compra" class="tabCompras index" style="width:96%;float:left;">
      <?php
          echo $this->Html->link(
              "Importar Compras",
              array(
                  'controller' => 'compras',
                  'action' => 'importar',
                  $cliente["Cliente"]['id'],
                  $periodo
              ),
              array('class' => 'buttonImpcli',
                  'style'=> 'width:132px;'
              )
          );
          echo $this->Form->create('Compra',array(
            'id'=>'saveComprasForm',
            'action'=>'addajax',
            'class'=>'formTareaCarga formAddCompra'
            ));
          echo $this->Form->input('cliente_id',array('default'=>$cliente["Cliente"]['id'],'type'=>'hidden'));
          //Vamos a enviar la situacion del cliente para no recalcularla en el controlador cada ves que guardemos una venta
          /*AFIP*/
          echo $this->Form->input('tieneMonotributo',array('value'=>$cliente["Cliente"]['tieneMonotributo'],'type'=>'hidden'));
          echo $this->Form->input('tieneIVA',array('value'=>$cliente["Cliente"]['tieneIVA'],'type'=>'hidden'));
          echo $this->Form->input('tieneIVAPercepciones',array('value'=>$cliente["Cliente"]['tieneIVAPercepciones'],'type'=>'hidden'));
          echo $this->Form->input('tieneImpuestoInterno',array('value'=>$cliente["Cliente"]['tieneImpuestoInterno'],'type'=>'hidden'));
          /*DGR*/
          echo $this->Form->input('tieneAgenteDePercepcionIIBB',array('value'=>$cliente["Cliente"]['tieneAgenteDePercepcionIIBB'],'type'=>'hidden'));
          /*DGRM*/
          echo $this->Form->input('tieneAgenteDePercepcionActividadesVarias',array('value'=>$cliente["Cliente"]['tieneAgenteDePercepcionActividadesVarias'],'type'=>'hidden'));
          echo $this->Form->input('fecha', array(
                  'class'=>'datepicker', 
                  'type'=>'text',
                  'label'=>'Fecha',
                  'default'=>"",
                  'readonly'=>'readonly',
                  'required'=>true,
                  'style'=>'width:60px;font:8px'
                  )
           );            
          //Aca tenemos que sacar los tipos de comprobantes que el cliente puede emitir                              
          echo $this->Form->input('comprobante_id', array(
                      'style'=>'width:150px;padding:0;margin:0',
                      'label'=>'Comprobante',
                      'options'=>$comprobantesCompra,
                      'class' => 'chosen-select',
                      )
          ); 
          echo $this->Form->input('puntosdeventa', array(
              'style'=>'width:70px;padding:0;margin:0',
              'type'=>'text',
              'maxlength'=>'4',
              'label'=>'&nbsp;'                
              )
          ); 
          echo $this->Form->input('numerocomprobante', array(
              'label'=>'&nbsp;'                
              )
          );  
          echo $this->Form->input('provedore_id', array(
              'options' => $provedores,        
              'class'=>'chosen-select',
              'style'=>'width:150px;padding:0;margin:0',                      
              'label'=>'Proveedores'
              )
          );  

          echo '<div>';           
          echo $this->Form->button($this->Html->image("addcli_view.png", 
                                                array(
                                                  "alt" => "Agregar",
                                                  'style'=>'width:20px;height:20px', 
                                                  )
                                                )."",
                                                array(
                                                  'class'=>"btnAgregar",
                                                  'escape'=>false,
                                                  'title'=>'Agregar Cliente',
                                                  'type'=>"button",
                                                  'style'=>'margin-top:15px; cursor: pointer;',
                                                  'onClick'=>"location.href='#nuevo_provedor'"
                                                )
                              ); 
          echo '</div>';           

          echo $this->Form->input('condicioniva', array(                      
                  'type'=>'select',
                  'label'=>'Cond. IVA',
                  'options'=>$condicionesiva,
                  'style' => 'width:150px',
                  'class'=>'chosen-select',
                  )
           );
          echo $this->Form->input('actividadcliente_id',array(
              'type'=>'select',
              'label'=>'Actividad',
              'options'=>$actividades,
              'style' => 'width:150px',
              'class'=>'chosen-select',
              ));                
          echo $this->Form->input('localidade_id',array(
              'class'=>'chosen-select',
              'style' => 'width:150px'                   
              )
          );    
          echo $this->Form->input('tipocredito',array(
                'label'=>'Tipo Credito',
                'options'=>$tipocreditos,
                'style' => 'width:150px',
                'class'=>'chosen-select',                 
            ));               
          echo $this->Form->input('tipogasto_id', array(                      
                'label'=>'Tipo Gasto',
                'style' => 'width:150px',
                'class'=>'chosen-select',                 
                )
          );
          echo $this->Form->input('tipoiva',array(
                'label'=>'Tipo (IVA)',
                'options'=>array('directo'=>'Directo','prorateable'=>'Prorateable'),
                'style' => 'width:150px',
                'class'=>'chosen-select',                 
              ));    
          echo $this->Form->input('imputacion',array(
                'type'=>'select',
                'options'=>$imputaciones,
                'style' => 'width:150px',
                'class'=>'chosen-select',
                ));   
          echo $this->Form->input('alicuota',array(
           'options' => $alicuotas,
            'style'=>'width:60px',            
            'class'=>'chosen-select',
            ));    
          echo $this->Form->input('neto',array());    
          echo $this->Form->input('iva',array());    
          echo $this->Form->input('ivapercep',array(
                'label'=> 'IVA Percep',                              
              ));    
          echo $this->Form->input('iibbpercep',array(
              'label'=> 'IIBB Percep',                              
            ));
          echo $this->Form->input('actvspercep',array(
              'label'=> 'Act vs Percep',
            ));
          echo $this->Form->input('impinternos',array('label'=>'Imp. Int.'));
          echo $this->Form->input('impcombustible',array('label'=>'Imp Comb'));
          echo $this->Form->input('nogravados',array());
          echo $this->Form->input('exentos',array(
              'label'=> 'Exentos',
          ));
          echo $this->Form->input('kw',array(
                'label'=>'KW'
              ));     
          echo $this->Form->input('total',array(
                'label'=>'Total'
              ));     
          echo $this->Form->input('asiento',array('type'=>'hidden'));      
          echo $this->Form->input('periodo',array('default'=>$periodo,'type'=>'hidden'));      
          echo $this->Form->submit('+', array('type'=>'image',
              'src' => $this->webroot.'img/add_view.png',
              'class'=>'imgedit',              
              'style'=>'width:25px;height:25px;margin-top:10px; cursor: pointer;',
              ));  
          echo $this->Form->end();  ?>  
    </div>
    <div style="overflow:auto;width:96%; float:left; margin-top:10px;min-height: 400px;" class="tareaCargarIndexTable tabCompras index">
      <table class="display" id="tablaCompras" cellpadding="0" cellspacing="0" border="0">
        <thead>
          <tr>
            <th style="width: 95px;">Fecha</th>
            <th style="width: 200px;">Comprobante</th>
            <th style="width: 117px;">Provedor</th>
            <th style="width: 106px;">Cond.IVA</th>
            <th style="width: 131px;">Actividad</th>
            <th style="width: 95px;">Localidad</th>
            <th style="width: 95px;">Tipo Cred</th>
            <th style="width: 77px;">Tipo Gasto</th>
            <th style="width: 88px;">Tipo(IVA)</th>
            <th style="width: 76px;">Impuntacion</th>
            <th style="width: 70px;">Alicuota</th>
            <th style="width: 81px;"  class="sum">Neto</th>
            <th style="width: 73px;">IVA</th>
            <th style="width: 76px;" >IVA Percep</th>
            <th style="width: 76px;">IIBB Percep</th>
            <th style="width: 76px;" >Act Vs Perc</th>
            <th style="width: 76px;" >Imp Interno</th>
            <th style="width: 76px;" >Imp Comb</th>
            <th style="width: 76px;" >No Gravados</th>
            <th style="width: 76px;" >Exentos</th>
            <th style="width: 76px;" >KW</th>
            <th style="width: 76px;" class="sum">Total</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="bodyTablaCompras">
          <?php
          foreach($cliente["Compra"] as $compra ){
            echo $this->Form->create('Compra',array('controller'=>'Compra','action'=>'edit')); 
            $tdClass = "tdViewCompra".$compra["id"];
              if($compra["tipocredito"]=='Restitucion credito fiscal'){
                  $compra["neto"] = $compra["neto"]*-1;
                  $compra["total"] = $compra["total"]*-1;
              }
            ?>
            <tr id="rowcompra<?php echo $compra["id"]?>"> 
              <td class="<?php echo $tdClass?>"><?php echo date('d-m-Y',strtotime($compra["fecha"]))?></td><!--1-->
              <td class="<?php echo $tdClass?>"><?php echo $compra["Comprobante"]['nombre']?>-
              <?php echo $compra['puntosdeventa']?>-
              <?php echo $compra["numerocomprobante"]?></td><!--2-->
              <td class="<?php echo $tdClass?>"><?php if(isset($compra["Provedore"]["nombre"])) echo $compra["Provedore"]["nombre"]?></td><!--3-->
              <td class="<?php echo $tdClass?>"><?php echo $compra["condicioniva"]?></td><!--4-->
              <td class="<?php echo $tdClass?>"><?php echo $compra["Actividadcliente"]['Actividade']['nombre']?></td><!--5-->
              <td class="<?php echo $tdClass?>"><?php echo $compra["Localidade"]['Partido']["nombre"].'-'.$compra["Localidade"]["nombre"]?></td><!--6-->
              <td class="<?php echo $tdClass?>"><?php echo $compra["tipocredito"]?></td><!--7-->
              <td class="<?php echo $tdClass?>"><?php echo $compra["Tipogasto"]["nombre"]?></td><!--8-->
              <td class="<?php echo $tdClass?>"><?php echo $compra["tipoiva"]?></td><!--9-->
              <td class="<?php echo $tdClass?>"><?php echo $compra["imputacion"]?></td><!--10-->
              <td class="<?php echo $tdClass?>"><?php echo number_format($compra["alicuota"], 2, ",", ".")?>%</td><!--11-->
              <td class="<?php echo $tdClass?>"><?php echo number_format($compra["neto"], 2, ",", ".")?></td><!--12-->
              <td class="<?php echo $tdClass?>"><?php echo number_format($compra["iva"], 2, ",", ".")?></td><!--13-->
              <td class="<?php echo $tdClass?>"><?php echo number_format($compra["ivapercep"], 2, ",", ".")?></td><!--14-->
              <td class="<?php echo $tdClass?>"><?php echo number_format($compra["iibbpercep"], 2, ",", ".")?></td><!--15-->
              <td class="<?php echo $tdClass?>"><?php echo number_format($compra["actvspercep"], 2, ",", ".")?></td><!--16-->
              <td class="<?php echo $tdClass?>"><?php echo number_format($compra["impinternos"], 2, ",", ".")?></td><!--17-->
              <td class="<?php echo $tdClass?>"><?php echo number_format($compra["impcombustible"], 2, ",", ".")?></td><!--18-->
                <td class="<?php echo $tdClass?>"><?php echo number_format($compra["nogravados"], 2, ",", ".")?></td><!--19-->
                <td class="<?php echo $tdClass?>"><?php echo number_format($compra["exentos"], 2, ",", ".")?></td><!--20-->
              <td class="<?php echo $tdClass?>"><?php echo number_format($compra["kw"], 2, ",", ".")."KW"?></td><!--21-->
              <td class="<?php echo $tdClass?>"><?php echo number_format($compra["total"], 2, ",", ".")?></td><!--22-->
              <td class="<?php echo $tdClass?>">
                <?php 
                $paramsCompra=$compra["id"];
                echo $this->Html->image('edit_view.png',array('width' => '20', 'height' => '20','onClick'=>"modificarCompra(".$paramsCompra.")"));
                echo $this->Html->image('eliminar.png',array('width' => '20', 'height' => '20','onClick'=>"eliminarCompra(".$paramsCompra.")"));
                echo $this->Form->end(); 
                ?> 
              </td><!--23-->
            </tr>
            <?php

          }
          ?>
        </tbody>
        <tfoot>
          <tr>
              <th >Totales</th><!--1-->
              <th ></th><!--2-->
              <th ></th><!--3-->
              <th ></th><!--4-->
              <th ></th><!--5-->
              <th ></th><!--6-->
              <th ></th><!--7-->
              <th ></th><!--8-->
              <th ></th><!--9-->
              <th ></th><!--10-->
              <th ></th><!--11-->
              <th ></th><!--12-->
              <th ></th><!--13-->
              <th ></th><!--14-->
              <th ></th><!--15-->
              <th ></th><!--16-->
              <th ></th><!--17-->
              <th ></th><!--18-->
              <th ></th><!--19-->
              <th ></th><!--20-->
              <th ></th><!--21-->
              <th></th><!--22-->
              <th></th><!--23-->
            </tr>
          </tfoot>
      </table>  
    </div> 
   <?php /**************************************************************************/ ?>
   <?php /*****************************Novedades************************************/ ?>
   <?php /**************************************************************************/ ?>
      <div id="formOldSueldo">
          <div id="form_sueldo" class="tabNovedades index" style="width:96%;float:left;">
              <?php
              echo $this->Form->create('Sueldo',array(
                      'controller'=>'sueldos',
                      'id'=>'saveSueldosForm',
                      'action'=>'addajax',
                      'class'=>'formTareaCarga',
                  )
              );
              echo $this->Form->input('cliente_id',array('default'=>$cliente["Cliente"]['id'],'type'=>'hidden'));
              echo $this->Form->input('periodo',array('value'=>$periodo,'type'=>'hidden'));
              echo $this->Form->input('fecha', array(
                      'class'=>'datepicker',
                      'type'=>'text',
                      'label'=>'Fecha',
                      'default'=>"",
                      'readonly'=>'readonly',
                      'required'=>true,
                      'style' => 'width:80px;'
                  )
              );
              echo $this->Form->input('monto', array(
                      'label'=> 'Monto',
                  )
              );
              echo $this->Form->submit('+', array('type'=>'image',
                  'src' => $this->webroot.'img/add_view.png',
                  'class'=>'imgedit',
                  'title'=>'Agregar',
                  'style'=>'width:25px;height:25px;margin-top:8px;'));
              echo $this->Form->end();  ?>
          </div>
          <div style="overflow:auto;width:96%; float:left;min-height: 120px; margin-top:10px;" class="tareaCargarIndexTable tabNovedades index">
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
                      <td class="<?php echo $tdClass?>"><?php echo date('d-m-Y',strtotime($sueldo["fecha"]))?></td>
                      <td class="<?php echo $tdClass?>"><?php echo $sueldo["monto"]?></td>
                      <td class="<?php echo $tdClass?>">
                          <?php
                          $paramsSueldo=$sueldo["id"];
                          echo $this->Html->image('edit_view.png',array('width' => '20', 'height' => '20','onClick'=>"modificarSueldo(".$paramsSueldo.")"));
                          echo $this->Html->image('eliminar.png',array('width' => '20', 'height' => '20','onClick'=>"eliminarSueldo(".$paramsSueldo.")"));
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
      <div id="form_empleados" class="tabNovedades index" style="width:96%;float:left;">
            <?php
            foreach ($cliente['Empleado'] as $empleadolista) {
                $classButtonEmpleado = "btn_empleados";
                if(count($empleadolista['Valorrecibo'])>0){
                    $classButtonEmpleado = "btn_empleados_liq";
                }
                echo $this->Form->button(
                    $empleadolista['nombre'],
                    array(
                        'class'=>$classButtonEmpleado,
                        'onClick'=>"cargarSueldoEmpleado('".$cliente["Cliente"]['id']."','".$periodo."','".$empleadolista['id']."')",
                        'id'=>'buttonEmpleado'.$empleadolista['id'],
                    ),
                    array()
                );
            }
            ?>
      </div>
      <div style="overflow:auto;width:96%; float:left;min-height: 400px; margin-top: 10px" class="tareaCargarIndexTable tabNovedades index" id="divSueldoForm">

      </div>
<?php /**************************************************************************/ ?>
<?php /*****************************Conceptosrestantes************************************/ ?>
<?php /**************************************************************************/ ?>
    <div id="form_conceptosrestante" class="tabConceptosrestantes index" style="width:96%;float:left; margin-left:5px;margin-top:10px">
      <?php
      echo $this->Form->create('Conceptosrestante',array(
            'controller'=>'conceptosrestantes',
            'id'=>'saveConceptosrestantesForm',
            'action'=>'addajax',
            'class'=>'formTareaCarga',
            )
          );
      echo $this->Form->input('cliente_id',array('default'=>$cliente["Cliente"]['id'],'type'=>'hidden'));
      //Vamos a enviar la situacion del cliente para no recalcularla en el controlador cada ves que guardemos un concepto que resta
      /*AFIP*/
      echo $this->Form->input('tieneMonotributo',array('value'=>$tieneMonotributo,'type'=>'hidden'));
      echo $this->Form->input('tieneIVA',array('value'=>$tieneIVA,'type'=>'hidden'));
      echo $this->Form->input('tieneIVAPercepciones',array('value'=>$tieneIVAPercepciones,'type'=>'hidden'));
      echo $this->Form->input('tieneImpuestoInterno',array('value'=>$tieneImpuestoInterno,'type'=>'hidden'));
      /*DGR*/
      echo $this->Form->input('tieneAgenteDePercepcionIIBB',array('value'=>$tieneAgenteDePercepcionIIBB,'type'=>'hidden'));
      /*DGRM*/
      echo $this->Form->input('tieneAgenteDePercepcionActividadesVarias',array('value'=>$tieneAgenteDePercepcionActividadesVarias,'type'=>'hidden'));
      echo $this->Form->input('impclisid',array('type'=>'select','options'=>$impclisid,'style'=>'display:none','div'=>false,'label'=>false));
      echo $this->Form->input('impcli_id',array('class'=>'chosen-select', 
                                                'label' => 'Impuesto',
                                                'style' => 'width:150px;'));
      echo $this->Form->input('partido_id',array('empty'=>'Seleccionar Provincia','class'=>'chosen-select'));
      echo $this->Form->input('localidade_id',array('empty'=>'Seleccionar Localidad',
                                                    'class'=>'chosen-select',
                                                    'label' => 'Localidades', 
                                                    'style' => 'width:150px;'));
      echo $this->Form->input('conceptostipo_id',array('class'=>'chosen-select', 
                                                       'label' => 'Concepto',
                                                       'style' => 'width:150px;'));
      echo $this->Form->input('periodo',array('value'=>$periodo,'type'=>'hidden'));
      echo $this->Form->input('concepto',array('label'=>'Concepto'));
      echo $this->Form->input('comprobante_id', array(
          'label'=> 'Tipo Comprobante','empty'=>'Seleccionar Tipo Comprobante',
          )
      );
      echo $this->Form->input('numerocomprobante', array(
          'label'=> 'N° Comprobante',
          )
      );
      echo $this->Form->input('rectificativa', array(
          )
      );
      echo $this->Form->input('razonsocial',array('label'=>'Nombre/Razon Social'));
      echo $this->Form->input('monto', array(
          'label'=> 'Monto',
          )
      );  
      echo $this->Form->input('montoretenido', array(
          'label'=> 'Monto Retenido',
          )
      );
      echo $this->Form->input('cuit',array('label'=>'CUIT'));     
      echo $this->Form->input('fecha', array(
              'class'=>'datepicker', 
              'type'=>'text',
              'label'=>'Fecha',
              'default'=>"",
              'readonly'=>'readonly',
              'required'=>true,
              'style'=> 'width:80px;'
              )
       );                
      echo $this->Form->input('numerodespachoaduanero',array('label'=>'N° Despacho Aduanero'));     
      echo $this->Form->input('anticipo',array());     
      echo $this->Form->input('cbu',array());     
      echo $this->Form->input('tipocuenta',array(
        'options'=>array('Caja de Ahorro'=>'Caja de Ahorro','Cuenta Corriente'=>'Cuenta Corriente','Otro'=>'Otro'),
        'empty'=>'Seleccionar Tipo de cuenta',
        )
      );     
      echo $this->Form->input('tipomoneda',array(
        'options'=>array('Moneda Ext.'=>'Moneda Ext.','Peso Arg.'=>'Peso Arg.','Otro'=>'Otro'),
        'empty'=>'Seleccionar Moneda',
        )
      );     
      echo $this->Form->input('agente',array());     
      echo $this->Form->input('enterecaudador',array());     
      echo $this->Form->input('regimen',array());     
      echo $this->Form->input('descripcion',array('style' => 'width:200px;'));     
      echo $this->Form->input('numeropadron',array());     
        
      echo $this->Form->submit('+', array('type'=>'image',
        'src' => $this->webroot.'img/add_view.png',
        'class'=>'imgedit',
        'title' => 'Agregar',
        'style'=>'width:25px;height:25px;margin-top:8px'));  
      echo $this->Form->end();  ?>  
    </div>
    <div style="overflow:auto;width:96%; float:left;margin-left:5px;margin-top:10px;min-height: 400px;" class="tareaCargarIndexTable tabConceptosrestantes index">
      <table class="" style="border:1px solid white" id="tblTablaConceptosrestantes">
        <thead>
          <tr>
            <th>Impuesto</th><!-0-->
            <th>Partido</th><!-1-->
            <th>Localidad</th><!-2-->
            <th>Concepto</th><!-3-->
            <th>Tipo Comprobante</th><!-4-->
            <th>N° Comprobante</th><!-5-->
            <th>Rectificativa</th><!-6-->
            <th>Nombre/Razon Social</th><!-7-->
            <th>Monto</th><!-8-->
            <th>Monto Retenido</th><!-9-->
            <th>CUIT</th><!-10-->
            <th>Fecha</th><!-11-->
            <th>N° Desp. Aduanero</th><!-12-->
            <th>Anticipo</th><!-13-->
            <th>CBU</th><!-14-->
            <th>Tipo Cuenta</th><!-15-->
            <th>Moneda</th><!-16-->
            <th>Agente</th><!-17-->
            <th>Ente Recaudador</th><!-18-->
            <th>Regimen</th><!-19-->
            <th>Descripcion</th><!-20-->
            <th>Numero Padron</th><!-21-->
            <th>Actions</th><!-22-->
          </tr>
        </thead>
        <tbody id="bodyTablaConceptosrestantes">
          <?php
          foreach($cliente["Conceptosrestante"] as $conceptorestante ){
            echo $this->Form->create('Conceptosrestante',array('controller'=>'Conceptosrestante','action'=>'edit')); 
            $tdClass = "tdViewConceptosrestanteO".$conceptorestante["id"];
            ?>
            <tr id="rowconceptorestante<?php echo $conceptorestante["id"]?>" class="concepto<?php echo $conceptorestante["conceptostipo_id"];?>">
                <td class="<?php echo $tdClass?>"><?php echo $conceptorestante['Impcli']['Impuesto']["nombre"]?></td>
                <td class="<?php echo $tdClass?>">
                    <?php if(isset($conceptorestante['Partido']["nombre"])){
                        echo $conceptorestante['Partido']["nombre"];
                    }?>
                </td>
                <td class="<?php echo $tdClass?>">
                    <?php if(isset($conceptorestante['Localidade']['Partido']["nombre"])){
                        echo $conceptorestante['Localidade']['Partido']["nombre"]."-". $conceptorestante['Localidade']["nombre"];
                    }?>
                </td>
                <td class="<?php echo $tdClass?>"><?php
                    if(
                        $conceptorestante['Impcli']['impuesto_id']=='19'/*IVA*/ &&
                        $conceptorestante['Conceptostipo']['id']==1 )
                    {
                        $conceptorestante['Conceptostipo']["nombre"] = "Saldo de Libre Disponibilidad";
                    }
                    echo $conceptorestante['Conceptostipo']["nombre"]
                    ?></td>
                <td class="<?php echo $tdClass?>">
                    <?php if(isset($conceptorestante['Comprobante']["nombre"])){
                        echo $conceptorestante["Comprobante"]["nombre"];
                    }?>
                </td>
                <td class="<?php echo $tdClass?>"><?php echo $conceptorestante["numerocomprobante"]?></td>
                <td class="<?php echo $tdClass?>"><?php echo $conceptorestante["rectificativa"]?></td>
                <td class="<?php echo $tdClass?>"><?php echo $conceptorestante["razonsocial"]?></td>
                <td class="<?php echo $tdClass?>"><?php echo $conceptorestante["monto"]?></td>
                <td class="<?php echo $tdClass?>"><?php echo $conceptorestante["montoretenido"]?></td>
                <td class="<?php echo $tdClass?>"><?php echo $conceptorestante["cuit"]?></td>
                <td class="<?php echo $tdClass?>"><?php echo date('d-m-Y',strtotime($conceptorestante["fecha"]))?></td>
                <td class="<?php echo $tdClass?>"><?php echo $conceptorestante["numerodespachoaduanero"]?></td>
                <td class="<?php echo $tdClass?>"><?php echo $conceptorestante["anticipo"]?></td>
                <td class="<?php echo $tdClass?>"><?php echo $conceptorestante["cbu"]?></td>
                <td class="<?php echo $tdClass?>"><?php echo $conceptorestante["tipocuenta"]?></td>
                <td class="<?php echo $tdClass?>"><?php echo $conceptorestante["tipomoneda"]?></td>
                <td class="<?php echo $tdClass?>"><?php echo $conceptorestante["agente"]?></td>
                <td class="<?php echo $tdClass?>"><?php echo $conceptorestante["enterecaudador"]?></td>
                <td class="<?php echo $tdClass?>"><?php echo $conceptorestante["regimen"]?></td>
                <td class="<?php echo $tdClass?>"><?php echo $conceptorestante["descripcion"]?></td>
                <td class="<?php echo $tdClass?>"><?php echo $conceptorestante["numeropadron"]?></td>
                <td class="<?php echo $tdClass?>">
                    <?php
                    $paramsConceptorestante=$conceptorestante["id"];
                    echo $this->Html->image('edit_view.png',array('width' => '20', 'height' => '20','onClick'=>"modificarConceptosrestante(".$paramsConceptorestante.")"));
                    echo $this->Html->image('eliminar.png',array('width' => '20', 'height' => '20','onClick'=>"eliminarConceptosrestante(".$paramsConceptorestante.")"));
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
<?php /**************************************************************************/ ?>
<?php /**************************************************************************/ ?>
<?php /**************************************************************************/ ?>
<?php /**************************************************************************/ ?>
<?php /**************************************************************************/ ?>
<?php /*****************************Inicio Popins********************************/ ?>
<?php /**************************************************************************/ ?>
<?php /**************************************************************************/ ?>
<?php /**************************************************************************/ ?>
<?php /**************************************************************************/ ?> 
<!-- Inicio Popin VerEventoCliente -->
<a href="#x" class="overlay" id="popInModificarVenta"></a>
<div  class="popup">
<div class='section body' id="divModificarVenta">
   
</div>
<a class="close" href="#close"></a>
</div>
<!-- Fin Popin VerEventoCliente--> 
<!-- Inicio Popin Nuevo Cliente -->
<a href="#x" class="overlay" id="nuevo_subcliente"></a>
<div class="popup">
  <div id="form_subcliente" class="" style="width: 94%;">             
    <?php echo $this->Form->create('Subcliente',array('controller'=>'Subclientes','action'=>'add')); ?>   
    <h3><?php echo __('Agregar Cliente'); ?></h3>
    <table style="margin-bottom:0px">
      <tr>
        <td colspan="2">
          <?php echo $this->Form->input('cliente_id',array('default'=>$cliente['Cliente']['id'],'type'=>'hidden')); ?>
        </td>
      </tr>
      </tr>
        <td colspan="2">
          <?php echo $this->Form->input('cuit', array('label' => 'CUIT', 'style' => 'width: 100%')); ?> 
        </td>  
      </tr>
      </tr>
        <td colspan="2">
          <?php echo $this->Form->input('dni', array('label' => 'DNI', 'style' => 'width: 100%')); ?> 
        </td>  
      </tr>
      </tr>
        <td colspan="2">
          <?php echo $this->Form->input('nombre', array('label' => 'Nombre', 'style' => 'width: 100%')); ?>
        </td>
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
  <div id="form_subcliente" class="" style="width: 94%;">             
    <?php echo $this->Form->create('Provedore',array('controller'=>'Provedore','action'=>'add')); ?>   
    <h3><?php echo __('Agregar Proveedor'); ?></h3>
    <table style="margin-bottom:0px">      
      </tr>
        <td colspan="2">
          <?php echo $this->Form->input('cliente_id',array('default'=>$cliente['Cliente']['id'],'type'=>'hidden')); ?>
          <?php echo $this->Form->input('cuit', array('label' => 'CUIT', 'style' => 'width: 100%')); ?> 
        </td>  
      </tr>
      </tr>
        <td colspan="2">
          <?php echo $this->Form->input('dni', array('label' => 'DNI', 'style' => 'width: 100%')); ?> 
        </td>  
      </tr>
      </tr>
        <td colspan="2">
          <?php echo $this->Form->input('nombre', array('label' => 'Nombre', 'style' => 'width: 100%')); ?>
        </td>
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
<!-- Inicio Popin Nuevo Domicilio -->
<a href="#x" class="overlay" id="nuevo_valorrecibopersonalizado"></a>
<div class="popup">
    <div id="form_valorrecibopersonalizado" >

        <h3><?php echo __('Agregar Nuevo Concepto al recibo de sueldo de </br> todos los empleados de este Convenio'); ?></h3>
        <?php
        echo $this->Form->create('Cctxconcepto');

        echo $this->Form->input('campopersonalizado',array('type'=>'hidden','value'=>'1'));
        echo $this->Form->input('cliente_id',array('default'=>$impcli['Cliente']['id'],'type'=>'hidden'));
        echo $this->Form->input('conveniocolectivotrabajo_id',array('default'=>$empleadolista['conveniocolectivotrabajo_id'],'type'=>'hidden'));
        echo $this->Form->input('concepto_id',array('type'=>'hidden','value'=>null));
        echo $this->Form->input('nombre');
        echo $this->Form->input('calculado',array('label'=>'Es un campo calculado?','type'=>'checkbox'));
        echo $this->Form->input('funcionaaplicar');
        echo $this->Form->input('unidaddemedida');
        echo $this->Form->input('orden');

        echo $this->Form->end('Agregar concepto')
        ?>
    </div>
    <a class="close" href="#close"></a>
</div>
<!-- Fin Popin Nuevo Domicilio -->