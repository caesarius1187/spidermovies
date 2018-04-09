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

echo $this->Html->script('ventas/cargar',array('inline'=>false));
//Vamos a guardar las variables de configuracion del cliente para consultarlas a todas de las misma manera
/*AFIP*/
$tieneMonotributo=$cliente["Cliente"]['impuestosactivos']['monotributo'];
$tieneIVA=$cliente["Cliente"]['impuestosactivos']['iva'];
$tieneIVAPercepciones=$cliente["Cliente"]['impuestosactivos']['ivapercepciones'];
$tieneImpuestoInterno=$cliente["Cliente"]['impuestosactivos']['impuestointerno'];
/*DGR*/
$tieneAgenteDePercepcionIIBB=$cliente["Cliente"]['impuestosactivos']['agenteDePercepcionIIBB'];
/*DGRM*/
$tieneAgenteDePercepcionActividadesVarias=$cliente["Cliente"]['impuestosactivos']['agenteDePercepcionActividadesVarias'];
$contabiliza=$cliente["Cliente"]['impuestosactivos']['contabiliza'];
echo $this->Form->input('cliid',array('default'=>$cliente["Cliente"]['id'],'type'=>'hidden'));
$fchcumpleanosconstitucion = date('d-m-Y',strtotime($cliente["Cliente"]['fchcumpleanosconstitucion']));
echo $this->Form->input('fchcumpleanosconstitucion',array('default'=>$fchcumpleanosconstitucion,'type'=>'hidden'));
$condicioniva = $tieneMonotributo?'Monotributista':'Insctipto';
echo $this->Form->input('condicioniva',array('default'=>$condicioniva,'type'=>'hidden'));
echo $this->Form->input('periodo',array('default'=>$periodo,'type'=>'hidden'));
$domicilio = "";
if(isset($cliente["Domicilio"][0])){
    $domicilio = $cliente["Domicilio"][0]['calle'];
}
echo $this->Form->input('domiciliocliente',array('default'=>$domicilio,'type'=>'hidden'));

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
    <div id="bodyCarga" style="width:100%;height:35px;">
        <div class="" style="width:100%;height:30px; margin-left:10px " id="divAllTabs">
            <div class="cliente_view_tab" style="width:18.5%;margin-right:0px"  onClick="" id="tabVentas">
                <?php
                echo $this->Form->label(null, $text = 'Ventas',array('style'=>'text-align:center;margin-top:5px;cursor:pointer'));
                ?>
            </div>
        </div>
        <?php /**************************************************************************/ ?>
        <?php /*****************************Ventas***************************************/ ?>
        <?php /**************************************************************************/ ?>
        <div id="form_venta" class="tabVentas index" style="width:96%;float:left; ">
          <?php
              //****Aca vamos a controlar los Impuestos con periodo activo que influyen en los campos que se van a mostrar en el formulario de ventas******/
              /*
                AFIP(Venta)
                  Monotributista
                    -CondIVA
                    -Tipo de Debito
                    -Total
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
              echo $this->Html->link(
                  "Importar Ventas",
                  array(
                      'controller' => 'ventas',
                      'action' => 'importar',
                      $cliente["Cliente"]['id'],
                      $periodo
                  ),
                  array('class' => 'buttonImpcli',
                      'style'=> 'margin-right: 8px;width: initial;'
                  )
              );
            echo $this->Html->link(
              "TXT SIAP",
              array(
                  'controller' => 'Ventas',
                  'action' => 'exportartxt',
                  $cliente["Cliente"]['id'],
                  $periodo
              ),
              array('class' => 'buttonImpcli',
                  'style'=> 'margin-right: 8px;width: initial;'
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
            echo $this->Form->input('contabiliza',array('value'=>$contabiliza,'type'=>'hidden'));
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
                  //si no tiene categorizado ganancias no se debe poder cargar ventas
                  //a menos que sea monotributista y no tenga activado ganancias
                  //aca tengo que recorrer las actividades del cliente para relacionar las actividades con sus categorias de
                  // ganancias para que por javascript  las limite solo a las que tienen q ser
                  $actividadesCategorias = [];
                  foreach ($cliente['Actividadcliente'] as $actividadcliente) {
                      foreach ($actividadcliente['Cuentasganancia'] as $cuentaganancia){
                          $cuentaGananciaTraducida = "";
                          switch ($cuentaganancia['categoria']){
                              case 'primeracateg':
                                  $cuentaGananciaTraducida = "primera";
                                  break;
                              case 'segundacateg':
                                  $cuentaGananciaTraducida = "segunda";
                                  break;
                              case 'terceracateg':
                                  $cuentaGananciaTraducida = "tercera";
                                  break;
                              case 'terceracateg45':
                                  $cuentaGananciaTraducida = "tercera otros";
                                  break;
                              case 'cuartacateg':
                                  $cuentaGananciaTraducida = "cuarta";
                                  break;
                          }
                          $actividadesCategorias[$actividadcliente['id']]= $cuentaGananciaTraducida;
                      }
                  }
                  echo $this->Form->input('actividadescategorias', [
                      'type'=>'select',
                      'options'=>$actividadesCategorias,
                      'id'=>'jsonactividadescategorias',
                      'div'=>[
                          'style'=>'display:none'
                      ]
                  ]);
                  $display="content;";
                  if($contabiliza!=1){
                      $display="none;";
                  }
                  echo $this->Form->input('jsonalltiposingresosbiendeuso',array(
                          'value'=>json_encode($ingresosBienDeUso) ,
                          'type'=>'hidden',
                          "id"=>"jsonalltiposingresosbiendeuso"
                      )
                  );
                echo $this->Form->input('tipogasto_id',array(
                    'type'=>'select',
                    'label'=>'Tipo Ingreso',
                    'style' => 'width:150px; ',
                    'div' => ['style'=>'display:'.$display],
                    'class'=>"chosen-select",
                ));
                echo $this->Form->input('localidade_id',array(
                  'label'=>'Localidad',
                  'class'=>"chosen-select",
                  'style' => 'width:100px'
                  ));

                if($tieneMonotributo=='1'){
                  echo $this->Form->input('alicuota',array(
                      'options' => $alicuotas,
                      'style' => 'width:60px',
                      'type'=>'hidden'
                    ));
                  echo $this->Form->input('neto',array(
                    'readonly'=>'readonly',
                    'style' => 'width:75px',
                      'type'=>'hidden'
                    ));
                  echo $this->Form->input('iva',array(
                    'readonly'=>'readonly',
                    'style' => 'width:75px',
                      'type'=>'hidden'
                    ));
                    echo $this->Form->input('nogravados',array(
                        'label'=>'No Gravados',
                        'style' => 'width:75px',
                        'type'=>'hidden'
                    ));
                    echo $this->Form->input('excentos',array(
                        'label'=>'Exento',
                        'style' => 'width:75px',
                        'type'=>'hidden',
                    ));
                }else{
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
                    echo $this->Form->input('nogravados',array(
                        'label'=>'No Gravados',
                        'style' => 'width:75px'
                    ));
                    echo $this->Form->input('excentos',array(
                        'label'=>'Exento',
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
                          'class'=>'img_edit',
                          'style'=>'width:25px;height:25px;margin-top:8px',
                          'title'=>'Agregar'
                          )
                    );
                echo $this->Form->end();
          ?>
        </div>
        <div style="overflow-x:auto;width:96%; float:left;margin-top:10px;min-height: 1400px" class="tareaCargarIndexTable tabVentas index" id="divTablaVentas">
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
