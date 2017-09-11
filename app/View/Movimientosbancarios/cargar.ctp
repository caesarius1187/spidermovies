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

echo $this->Html->script('movimientosbancarios/cargar',array('inline'=>false));
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
            <div class="cliente_view_tab" style="width:18.5%;margin-right:0px"  onClick="" id="tabBancos">
                <?php
                    echo $this->Form->label(null, $text = 'Bancos',array('style'=>'text-align:center;margin-top:5px;cursor:pointer'));
                ?>
            </div>
        </div>
        <?php /**************************************************************************/ ?>
        <?php /*****************************Bancos************************************/ ?>
        <?php /**************************************************************************/ ?>
        <div id="form_banco" class="tabBancos index" style="width:96%;float:left;">
            <?php
            //aca vamos a tener que mostrar un boton de importar por cada cuenta que tengamos
            foreach ($cliente["Impcli"] as $bancimpcli) {
                foreach ($bancimpcli["Cbu"] as $cbu) {
                    $abreviacionCBUTipo = "";
                    switch ($cbu['tipocuenta']) {
                        case 'Caja de Ahorro en Euros':
                        $abreviacionCBUTipo = "CA €";
                        break;
                        case 'Caja de Ahorro en Moneda Local':
                        $abreviacionCBUTipo = "CA $";
                        break;
                        case 'Caja de Ahorro en U$S':
                            $abreviacionCBUTipo = "CA U$ S";
                        break;
                        case 'Cuenta Corriente en Euros':
                            $abreviacionCBUTipo = "CC €";
                        break;
                        case 'Cuenta Corriente en Moneda Local':
                        $abreviacionCBUTipo = "CC $";
                        break;
                        case 'Cuenta Corriente en U$S':
                            $abreviacionCBUTipo = "CC U$ S";
                        break;
                        case 'Otras':
                            $abreviacionCBUTipo = "Otras";
                        break;
                        case 'Plazo Fijo en Euros':
                            $abreviacionCBUTipo = "PF €";
                        break;
                        case 'Plazo Fijo en U$S':
                            $abreviacionCBUTipo = "PF U$ S";
                        break;
                        case 'Plazo Fijo en Moneda Local':
                            $abreviacionCBUTipo = "PF $";
                        break;
                        default:
                            $abreviacionCBUTipo = "cc $";
                        break;
                    }
                    ?>
                    <fieldset style="border: 1px solid #1e88e5; width: 25%; float: left;margin: 0px 5px;">
                         <legend style="color:#1e88e5;font-weight:normal;"><?php echo $bancimpcli['Impuesto']['nombre']." ".substr($cbu['numerocuenta'], -5)." ".$abreviacionCBUTipo?></legend>
                        <?php
                    echo $this->Html->link(
                        "Importar Res.Banc",
                        array(
                            'controller' => 'movimientosbancarios',
                            'action' => 'importar',
                            $cliente["Cliente"]['id'],
                            $periodo,
                            $bancimpcli['id'],
                            $cbu['id']
                        ),
                        array('class' => 'buttonImpcli',
                            'style'=> 'margin-right: 8px;width: initial;'
                        )
                    );
                        ?>
                    </fieldset>
                    <?php
                }
            }
            echo $this->Form->create('Movimientosbancario',array(
                    'controller'=>'movimientosbancarios',
                    'id'=>'saveMovimientosbancariosForm',
                    'action'=>'addajax',
                    'class'=>'formTareaCarga',
                )
            );
            echo $this->Form->input('periodo',array('value'=>$periodo,'type'=>'hidden'));
            echo $this->Form->input('cbu_id',array('label'=>'CBU'));
            echo $this->Form->input('ordencarga', array('label'=>'Orden',));
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
            echo $this->Form->input('concepto', array('required'=>'required',));
            echo $this->Form->input('debito', array());
            echo $this->Form->input('credito', array());
            echo $this->Form->input('saldo', array('required'=>'required',));
            echo $this->Form->input('cuentascliente_id',array('label'=>'Cuenta contable'));
            echo $this->Form->input('codigoafip', array('label'=> 'AFIP Codigo',));
            echo $this->Form->input('alicuota', array(
                    'label'=> 'alicuota',
                    'title'=>'Solo se debe completar este campo si se selecciono la cuenta IVA-Credito Fiscal'
                )
            );
            echo $this->Form->submit('+', array('type'=>'image',
                'src' => $this->webroot.'img/add_view.png',
                'class'=>'imgedit',
                'title' => 'Agregar',
                'style'=>'width:25px;height:25px;margin-top:8px'));
            echo $this->Html->image('ii.png',array(
                    'id' => 'iconInfo',
                    'alt' => 'open',
                    'title' => 'Recuerde que al cargar Movimientos bancarios relacionados a la cuenta '.
                        '"110404298   I.I.B.B. - Percepciones Bancarias", estos debitos serán automáticamente '.
                        'cargados en el papel de trabajo de liquidacion del impuesto a las Actividades Economicas, '.
                        'como asi tambien las recaudaciones bancarias cargadas en Pagos a cuenta. Evite duplicarlos.',
                )
            );
            echo $this->Form->end();  ?>
        </div>
        <div style="overflow:auto;width:96%; float:left;margin-top:10px;min-height: 400px;" class="tareaCargarIndexTable tabBancos index">
            <table class="" style="border:1px solid white" id="tblTablaMovimientosBancarios">
                <thead>
                    <tr>
                        <th>Cbu</th><!-0-->
                        <th>Orden</th><!-1-->
                        <th>Fecha</th><!-2-->
                        <th>Concepto</th><!-3-->
                        <th class="sum">Debito</th><!-4-->
                        <th class="sum">Credito</th><!-5-->
                        <th>Saldo</th><!-6-->
                        <th>Cuenta</th><!-7-->
                        <th>Codigo AFIP</th><!-8-->
                        <th title="Solo se debe completar este campo si se selecciono la cuenta IVA-Credito Fiscal">Alicuota</th><!-8-->
                        <th>Actions</th><!-9-->
                    </tr>
                </thead>
                <tbody id="bodyTablaConceptosrestantes">
                    <?php
                    foreach($cliente["Impcli"] as $impcli ){
                        foreach ($impcli['Cbu'] as $cbu){
                            foreach ($cbu['Movimientosbancario'] as $movimientobancario){
                            $tdClass = "tdViewMovimientosBancario".$movimientobancario["id"];
                            ?>
                            <tr id="rowmovimientosbancarios<?php echo $movimientobancario["id"]?>" class="movimientosbancario<?php echo $movimientobancario["id"];?>">
                                <td class="<?php echo $tdClass?>">
                                    <?php
                                    $abreviacionCBUTipo = "";
                                    switch ($cbu['tipocuenta']) {
                                        case 'Caja de Ahorro en Euros':
                                            $abreviacionCBUTipo = "CA €";
                                            break;
                                        case 'Caja de Ahorro en Moneda Local':
                                            $abreviacionCBUTipo = "CA $";
                                            break;
                                        case 'Caja de Ahorro en U$S':
                                            $abreviacionCBUTipo = "CA U$ S";
                                            break;
                                        case 'Cuenta Corriente en Euros':
                                            $abreviacionCBUTipo = "CC €";
                                            break;
                                        case 'Cuenta Corriente en Moneda Local':
                                            $abreviacionCBUTipo = "CC $";
                                            break;
                                        case 'Cuenta Corriente en U$S':
                                            $abreviacionCBUTipo = "CC U$ S";
                                            break;
                                        case 'Otras':
                                            $abreviacionCBUTipo = "Otras";
                                            break;
                                        case 'Plazo Fijo en Euros':
                                            $abreviacionCBUTipo = "PF €";
                                            break;
                                        case 'Plazo Fijo en U$S':
                                            $abreviacionCBUTipo = "PF U$ S";
                                            break;
                                        case 'Plazo Fijo en Moneda Local':
                                            $abreviacionCBUTipo = "PF $";
                                            break;
                                        default:
                                            $abreviacionCBUTipo = "cc $";
                                            break;
                                    }
                                    $cbuname = $impcli['Impuesto']['nombre']." ".substr($cbu['numerocuenta'], -5)." ".$abreviacionCBUTipo;
                                    echo $cbuname
                                    ?>
                                </td>
                                <td class="<?php echo $tdClass?>"><?php echo $movimientobancario["ordencarga"]?></td>
                                <td class="<?php echo $tdClass?>"><?php echo $movimientobancario["fecha"]?></td>
                                <td class="<?php echo $tdClass?>"><?php echo $movimientobancario["concepto"]?></td>
                                <td class="<?php echo $tdClass?>"><?php echo number_format($movimientobancario["debito"], 2, ",", ".")?></td>
                                <td class="<?php echo $tdClass?>"><?php echo number_format($movimientobancario["credito"], 2, ",", ".")?></td>
                                <td class="<?php echo $tdClass?>"><?php echo number_format($movimientobancario["saldo"], 2, ",", ".")?></td>
                                <td class="<?php echo $tdClass?>"><?php echo $movimientobancario["Cuentascliente"]['nombre']?></td>
                                <td class="<?php echo $tdClass?>"><?php echo $movimientobancario["codigoafip"]?></td>
                                <td class="<?php echo $tdClass?>"><?php echo $movimientobancario["alicuota"]?></td>
                                <td class="<?php echo $tdClass?>">
                                    <?php
                                    $paramsConceptorestante=$movimientobancario["id"];
                                    echo $this->Html->image('edit_view.png',array('width' => '20', 'height' => '20','onClick'=>"modificarMovimientosbancario(".$paramsConceptorestante.")"));
                                    echo $this->Html->image('eliminar.png',array('width' => '20', 'height' => '20','onClick'=>"eliminarMovimientosbancario(".$paramsConceptorestante.")"));
                                    echo $this->Form->end();  ?>
                                </td>
                            </tr>
                        <?php }
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                <tr>
                    <th></th><!-0-->
                    <th></th><!-1-->
                    <th></th><!-2-->
                    <th></th><!-3-->
                    <th></th><!-4-->
                    <th></th><!-5-->
                    <th></th><!-6-->
                    <th></th><!-7-->
                    <th></th><!-8-->
                    <th></th><!-9-->
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
