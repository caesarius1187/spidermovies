<?php
echo $this->Html->css('bootstrapmodal');

echo $this->Html->script('jquery-ui.js',array('inline'=>false));

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

echo $this->Html->script('conceptosrestantes/cargar',array('inline'=>false));
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
            <div class="cliente_view_tab" style="width:18.5%;" onClick="" id="tabConceptosrestantes">
                <?php
                    echo $this->Form->label(null, $text = 'Pagos a cuenta',array('style'=>'text-align:center;margin-top:5px;cursor:pointer'));
                ?>
            </div>
        </div>
        <?php /**************************************************************************/ ?>
        <?php /*****************************Conceptosrestantes************************************/ ?>
        <?php /**************************************************************************/ ?>
        <div id="form_conceptosrestante" class="tabConceptosrestantes index" style="width:96%;float:left;">
                <?php
                $mostrarBotonRetenciones = false;
                $iibbId=0;
                foreach ($cliente['Impcli'] as $impcli) {
                    if($impcli['impuesto_id']=='174'/*Convenio Multilateral*/){
                        $mostrarBotonRetenciones = true;
                        $iibbId = $impcli['id'];
                    }
                }
                if($mostrarBotonRetenciones){
                    echo $this->Html->link(
                        "Exportar TXT Retenciones IIBB",
                        array(
                            'controller' => 'conceptosrestantes',
                            'action' => 'exportartxtretencionessifere',
                            $cliente["Cliente"]['id'],
                            $periodo,
                            $iibbId
                        ),
                        array('class' => 'buttonImpcli',
                            'style'=> 'margin-right: 8px;width: initial;',
                        )
                    );
                    echo $this->Html->link(
                        "Exportar TXT Recaudaciones Bancarias IIBB",
                        array(
                            'controller' => 'conceptosrestantes',
                            'action' => 'exportartxtrecaudacionesbancarias',
                            $cliente["Cliente"]['id'],
                            $periodo,
                            $iibbId
                        ),
                        array('class' => 'buttonImpcli',
                            'style'=> 'margin-right: 8px;width: initial;',
                        )
                    );
                    echo $this->Html->link(
                        "Importar TXT RecaudacionesBancarias IIBB",
                        array(
                            'controller' => 'conceptosrestantes',
                            'action' => 'importartxtrecaudacionesbancarias',
                            $cliente["Cliente"]['id'],
                            $periodo,
                            $iibbId
                        ),
                        array('class' => 'buttonImpcli',
                            'style'=> 'margin-right: 8px;width: initial;',
                        )
                    );
                }

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
                echo $this->Form->input('numerocomprobante', array(
                        'label'=> 'N&deg; Comprobante',
                    )
                );
                echo $this->Form->input('comprobante_id', array(
                        'label'=> 'Tipo Comprobante','empty'=>'Seleccionar Tipo Comprobante',
                    )
                );

                echo $this->Form->input('puntosdeventa', array(
                        'label'=> 'Punto de venta',
                        'type'=> 'text',
                    )
                );
                echo $this->Form->input('numerofactura', array(
                        'label'=> 'N&deg; Factura',
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
                echo $this->Form->input('numerodespachoaduanero',array('label'=>'N&deg; Despacho Aduanero'));
                echo $this->Form->input('anticipo',['title'=>'aaaa/mm']);
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
                echo $this->Form->input('ordendepago',array());
                echo $this->Form->submit('+', array('type'=>'image',
                    'src' => $this->webroot.'img/add_view.png',
                    'class'=>'img_edit',
                    'title' => 'Agregar',
                    'style'=>'width:25px;height:25px;margin-top:8px'));
                echo $this->Html->image('ii.png',array(
                        'id' => 'iconInfo',
                        'alt' => 'open',
                        'title' => 'Recuerde que al cargar Recaudaciones Bancarias de Actividades Economicas estos serán automáticamente '.
                            'computados en el papel de trabajo de liquidacion del impuesto a las Actividades Economicas, '.
                            'como asi tambien los Movimientos bancarios relacionados a la cuenta "110404298   I.I.B.B. - '.
                            'Percepciones Bancarias". Evite duplicarlos.',
                    )
                );
                echo $this->Form->end();  ?>
            </div>
        <div style="overflow:auto;width:96%; float:left;margin-top:10px;min-height: 400px;" class="tareaCargarIndexTable tabConceptosrestantes index">
            <table class="" style="border:1px solid white" id="tblTablaConceptosrestantes">
                <thead>
                <tr>
                    <th>Impuesto</th><!-0-->
                    <th>Partido</th><!-1-->
                    <th>Localidad</th><!-2-->
                    <th>Concepto</th><!-3-->
                    <th>Tipo Comprobante</th><!-4-->
                    <th>N&deg; Comprobante</th><!-5-->
                    <th>Rectificativa</th><!-6-->
                    <th>Nombre/Razon Social</th><!-7-->
                    <th class="sum">Monto</th><!-8-->
                    <th class="sum">Monto Retenido</th><!-9-->
                    <th>CUIT</th><!-10-->
                    <th>Fecha</th><!-11-->
                    <th>N&deg; Desp. Aduanero</th><!-12-->
                    <th>Anticipo</th><!-13-->
                    <th>CBU</th><!-14-->
                    <th>Tipo Cuenta</th><!-15-->
                    <th>Moneda</th><!-16-->
                    <th>Agente</th><!-17-->
                    <th>Ente Recaudador</th><!-18-->
                    <th>Regimen</th><!-19-->
                    <th>Descripcion</th><!-20-->
                    <th>Numero Padron</th><!-21-->
                    <th>Punto de Venta</th><!-22-->
                    <th>Numero Factura</th><!-23-->
                    <th>Orden de pago</th><!-24-->
                    <th>Actions</th><!-25-->
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </tfoot>
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
                        <td class="<?php echo $tdClass?>"><?php echo $conceptorestante["puntosdeventa"]?></td>
                        <td class="<?php echo $tdClass?>"><?php echo $conceptorestante["numerofactura"]?></td>
                        <td class="<?php echo $tdClass?>"><?php echo $conceptorestante["ordendepago"]?></td>
                        <td class="<?php echo $tdClass?>">
                            <?php
                            $paramsConceptorestante=$conceptorestante["id"];
                            echo $this->Html->image('edit_view.png',array('width' => '20', 'height' => '20','onClick'=>"modificarConceptosrestante(".$paramsConceptorestante.")"));
                            echo $this->Html->image('eliminar.png',array('width' => '20', 'height' => '20','onClick'=>"eliminarConceptosrestante(".$paramsConceptorestante.")"));
                            if(
                                $conceptorestante['Impcli']['impuesto_id']=='19'/*IVA*/ &&
                                $conceptorestante['Conceptostipo']['id']==1 )
                            {
                                echo $this->Html->image('usosaldo.png',array('width' => '20', 'height' => '20','onClick'=>"usosSLD(".$paramsConceptorestante.")"));
                            }
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
<a href="#x" class="overlay" id="popinUsosaldos"></a>
<div class="popup">
    <div id="divUsosaldos" >

       
    </div>
</div>
<!-- Fin Popin Nuevo Domicilio -->

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
