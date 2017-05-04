<?php
echo $this->Html->css('bootstrapmodal');

echo $this->Html->script('jquery-ui.js',array('inline'=>false));

echo $this->Html->script('languages.js',array('inline'=>false));
echo $this->Html->script('numeral.js',array('inline'=>false));
echo $this->Html->script('moment.js',array('inline'=>false));
echo $this->Html->script('jquery-calx-2.2.6',array('inline'=>false));

echo $this->Html->script('jquery.dataTables.js',array('inline'=>false));
echo $this->Html->script('dataTables.altEditor.js',array('inline'=>false));
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
echo $this->Html->script('dataTables.buttons.min.js',array('inline'=>false));
echo $this->Html->script('buttons.print.min.js',array('inline'=>false));
echo $this->Html->script('buttons.flash.min.js',array('inline'=>false));
echo $this->Html->script('jszip.min.js',array('inline'=>false));
echo $this->Html->script('pdfmake.min.js',array('inline'=>false));
echo $this->Html->script('vfs_fonts.js',array('inline'=>false));
echo $this->Html->script('buttons.html5.min.js',array('inline'=>false));

echo $this->Html->script('compras/cargar',array('inline'=>false));
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
echo $this->Form->input('cliid',array('default'=>$cliente["Cliente"]['id'],'type'=>'hidden'));
echo $this->Form->input('periodo',array('default'=>$periodo,'type'=>'hidden'));

?>
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>-->
<!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css"/>-->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.2/css/buttons.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.1.2/css/select.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.0.2/css/responsive.dataTables.min.css"/>

<!--<script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>-->

<script src="https://cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/select/1.1.2/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.0.2/js/dataTables.responsive.min.js"></script>
<!--<div class="" style="float:none; width: 100%; margin: 0px 4px">  -->
  <div class="index" style="padding: 0px 1%; margin-bottom: 10px;" id="headerCliente">
      <div style="width:30%; float: left;padding-top:10px">
        Cliente: <?php echo $cliente["Cliente"]['nombre'];
          echo $this->Form->input('clientenombre',['type'=>'hidden','value'=>$cliente["Cliente"]['nombre']]);?>
      </div>
      <div style="width:25%; float: left;padding-top:10px">
        Periodo: <?php echo $periodo;
          echo $this->Form->input('periododefault',['type'=>'hidden','value'=>$periodo])?>
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
            <div class="cliente_view_tab" style="width:18.5%;margin-right:0px"  onClick="" id="tabCompras">
                <?php
                echo $this->Form->label(null, $text = 'Compras',array('style'=>'text-align:center;margin-top:5px;cursor:pointer'));
                ?>
            </div>
        </div>
       <?php /**************************************************************************/ ?>
       <?php /*****************************Compras**************************************/ ?>
       <?php /**************************************************************************/  ?>
        <div id="form_compra" class="tabCompras index" style="width:96%;float:left;">
          <?php
          echo $this->Form->input('jsonallcomprobantes',array(
                  'value'=>json_encode($allcomprobantes) ,
                  'type'=>'hidden',
                  "id"=>"jsonallcomprobantes"
              )
          );
          echo $this->Html->link(
              "Importar Compras",
              array(
                  'controller' => 'compras',
                  'action' => 'importar',
                  $cliente["Cliente"]['id'],
                  $periodo
              ),
              array('class' => 'buttonImpcli',
                  'style'=> 'margin-right: 8px;width: initial;',
              )
          );
          echo $this->Html->link(
              "TXT SIAP",
              array(
                  'controller' => 'Compras',
                  'action' => 'exportartxt',
                  $cliente["Cliente"]['id'],
                  $periodo
              ),
              array('class' => 'buttonImpcli',
                  'style'=> 'margin-right: 8px;width: initial;'
              )
          );
          echo $this->Html->link(
              "TXT Percepciones SIFERE",
              array(
                  'controller' => 'compras',
                  'action' => 'exportartxtpercepcionessifere',
                  $cliente["Cliente"]['id'],
                  $periodo
              ),
              array('class' => 'buttonImpcli',
                  'style'=> 'margin-right: 8px;width: initial;',
              )
          );
          echo $this->Html->link(
              "TXT Percepciones IIBB",
              array(
                  'controller' => 'compras',
                  'action' => 'exportartxtpercepcionesiibb',
                  $cliente["Cliente"]['id'],
                  $periodo
              ),
              array('class' => 'buttonImpcli',
                  'style'=> 'margin-right: 8px;width: initial;',
              )
          );
          echo $this->Html->link(
              "TXT Percepciones Act. Vs.",
              array(
                  'controller' => 'compras',
                  'action' => 'exportartxtpercepcionesdgrm',
                  $cliente["Cliente"]['id'],
                  $periodo
              ),
              array('class' => 'buttonImpcli',
                  'style'=> 'margin-right: 8px;width: initial;',
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
                <th style="width: 250px;">Comprobante</th>
                <th style="width: 117px;">Provedor</th>
                <th style="width: 106px;">Cond.IVA</th>
                <th style="width: 131px;">Actividad</th>
                <th style="width: 95px;">Localidad</th>
                <th style="width: 95px;">Tipo Cred</th>
                <th style="width: 77px;">Tipo Gasto</th>
                <th style="width: 88px;">Tipo(IVA)</th>
                <th style="width: 76px;">Imputacion</th>
                <th style="width: 70px;">Alicuota</th>
                <th style="width: 81px;" class="sum">Neto</th>
                <th style="width: 73px;" class="sum">IVA</th>
                <th style="width: 76px;" class="sum">IVA Percep</th>
                <th style="width: 76px;" class="sum">IIBB Percep</th>
                <th style="width: 76px;" class="sum">Act Vs Perc</th>
                <th style="width: 76px;" class="sum">Imp Interno</th>
                <th style="width: 76px;" class="sum">Imp Comb</th>
                <th style="width: 76px;" class="sum">No Gravados</th>
                <th style="width: 76px;" class="sum">Exentos</th>
                <th style="width: 76px;" class="sum">Total</th>
                  <th style="width: 76px;" class="sum">KW</th>
                  <th>Acciones</th>
                  <th>Creado</th>
              </tr>
            </thead>
            <tbody id="bodyTablaCompras">
              <?php

              foreach($cliente["Compra"] as $c => $compra ){
                echo $this->Form->create('Compra',array('controller'=>'Compra','action'=>'edit'));
                $tdClass = "tdViewCompra".$compra["id"];
                  if($compra["tipocredito"]=='Restitucion credito fiscal'){
                      $compra["neto"] = $compra["neto"]*-1;
                      $compra["total"] = $compra["total"]*-1;
                      $compra["iva"] = $compra["iva"]*-1;
                      $compra["ivapercep"] = $compra["ivapercep"]*-1;
                      $compra["iibbpercep"] = $compra["iibbpercep"]*-1;
                      $compra["actvspercep"] = $compra["actvspercep"]*-1;
                      $compra["impinternos"] = $compra["impinternos"]*-1;
                      $compra["impcombustible"] = $compra["impcombustible"]*-1;
                      $compra["nogravados"] = $compra["nogravados"]*-1;
                      $compra["kw"] = $compra["kw"]*-1;
                  }
                ?>
                <tr id="rowcompra<?php echo $compra["id"]?>">
                  <td class="<?php echo $tdClass?>">
                      <span style='display: none;'> <?php echo $compra["fecha"]?></span>
                      <?php echo date('d-m-Y',strtotime($compra["fecha"]))?></td><!--1-->
                    <?php
                    $titleComprobanteCompra = $compra["Comprobante"]['nombre']."-".$compra['puntosdeventa']."-".$compra["numerocomprobante"];
                    $labelComprobanteCompra = $compra["Comprobante"]['abreviacion']."-".$compra['puntosdeventa']."-".$compra["numerocomprobante"]; ?>
                  <td class="<?php echo $tdClass?>" title="<?php echo $titleComprobanteCompra ?>"><?php echo $labelComprobanteCompra?></td><!--2-->
                  <td class="<?php echo $tdClass?>"><?php if(isset($compra["Provedore"]["nombre"])) echo $compra["Provedore"]["nombre"]?></td><!--3-->
                  <td class="<?php echo $tdClass?>"><?php echo $compra["condicioniva"]?></td><!--4-->
                  <td class="<?php echo $tdClass?>"><?php echo $compra["Actividadcliente"]['Actividade']['nombre']?></td><!--5-->
                  <td class="<?php echo $tdClass?>"><?php echo $compra["Localidade"]['Partido']["nombre"].'-'.$compra["Localidade"]["nombre"]?></td><!--6-->
                  <td class="<?php echo $tdClass?>"><?php echo $compra["tipocredito"]?></td><!--7-->
                  <td class="<?php echo $tdClass?>"><?php
                      if(isset($compra["Tipogasto"]["nombre"]))
                      echo $compra["Tipogasto"]["nombre"]
                      ?></td><!--8-->
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
                  <td class="<?php echo $tdClass?>"><?php echo number_format($compra["total"], 2, ",", ".")?></td><!--22-->
                    <td class="<?php echo $tdClass?>"><?php echo number_format($compra["kw"], 2, ",", ".")?></td><!--21-->
                    <td class="<?php echo $tdClass?>">
                    <?php
                    $paramsCompra=$compra["id"];
                    echo $this->Html->image('edit_view.png',array('width' => '20', 'height' => '20','onClick'=>"modificarCompra(".$paramsCompra.")"));
                    echo $this->Html->image('eliminar.png',array('width' => '20', 'height' => '20','onClick'=>"eliminarCompra(".$paramsCompra.")"));
                    echo $this->Form->end();
                    ?>
                  </td><!--23-->
                    <td class="<?php echo $tdClass?>"><?php echo $compra["created"]; ?></td><!--21-->
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
                  <th></th><!--23 Acciones-->
                  <th></th><!--24 Creado-->
                </tr>
              </tfoot>
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
