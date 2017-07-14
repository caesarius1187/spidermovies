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

echo $this->Html->script('empleados/cargamasiva',array('inline'=>false));
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
            <div class="cliente_view_tab" style="width:18.5%;margin-right:0px"  onClick="" id="tabNovedades">
                <?php
                    echo $this->Form->label(null, $text = 'Empleados',array('style'=>'text-align:center;margin-top:5px;cursor:pointer'));
                ?>
            </div>
            <div class="cliente_view_tab" style="width:275px;margin-right:0px"  onClick="" id="tabNovedades">
                <?php
                    echo $this->Form->input('buscarempleado',[
                        'type'=>'text',
                        'label'=>[
                            'text'=>'Filtrar Empleados',
                            'style'=>'display:inline'
                        ],
                        'style'=>'display:inline'
                    ]);
                ?>
            </div>
        </div>
       <?php /**************************************************************************/ ?>
       <?php /*****************************Novedades************************************/ ?>
       <?php /**************************************************************************/ ?>
          <div id="form_empleados" class="tabNovedades index" style="width:96%;float:left;">
                <?php
                $arrayEmpleados=[];
                $arrayConvenios=[];
                $arrayButtonsConvenios=[];
                $liquidaPrimeraQuincena = false;
                $liquidaSegundaQuincena = false;
                $liquidaMensual= false;
                $optionsLiquidacion = [];
                foreach ($cliente['Empleado'] as $empleadolista) {
                    if($empleadolista['liquidaprimeraquincena']){
                        $liquidaPrimeraQuincena=true;
                        $optionsLiquidacion['liquidaprimeraquincena']='liquida primera quincena';
                    }
                    if($empleadolista['liquidasegundaquincena']){
                        $liquidaSegundaQuincena=true;
                        $optionsLiquidacion['liquidasegundaquincena']='liquida segunda quincena';
                    }
                    if($empleadolista['liquidamensual']){
                        $liquidaMensual=true;
                        $optionsLiquidacion['liquidamensual']='liquida mensual';
                    }
                    if($empleadolista['liquidasac']){
                        $liquidaSAC=true;
                        $optionsLiquidacion['liquidasac']='liquida sac';
                    }
                    $classButtonEmpleado = "btn_empleados";
                    //vamos a agregar a este array solo los empleadosque tengan este convenio
                    if(!isset($arrayEmpleados[$empleadolista['conveniocolectivotrabajo_id']])){
                        $arrayEmpleados[$empleadolista['conveniocolectivotrabajo_id']]=[];
                    }
                    $arrayConvenios[$empleadolista['conveniocolectivotrabajo_id']]=$this->Form->button(
                        $empleadolista['Conveniocolectivotrabajo']['nombre'],
                        array(
                            'class'=>'btn_empleados',
                            'onClick'=>"cargarTodosLosSueldos(".$empleadolista['conveniocolectivotrabajo_id'].");",
                            'id'=>'buttonConvenio'.$empleadolista['conveniocolectivotrabajo_id'],
                        ),
                        array()
                    );
                    $arrayEmpleados[$empleadolista['conveniocolectivotrabajo_id']][]=$empleadolista['id'];
                    if(count($empleadolista['Valorrecibo'])>0){
                        $classButtonEmpleado = "btn_empleados_liq";
                    }
                    echo $this->Form->button(
                        $empleadolista['nombre'],
                        array(
                            'class'=>$classButtonEmpleado." parafiltrarempleados",
                            'onClick'=>"cargarunsueldoempleado('".$cliente["Cliente"]['id']."','".$periodo."','".$empleadolista['id']."','-1','0')",
                            'id'=>'buttonEmpleado'.$empleadolista['id'],
                            'valorparafiltrar'=>$empleadolista['nombre']." ".$empleadolista['cuit'],
                        ),
                        array()
                    );
                }
                ?>
          </div>
          <div id="form_FuncionImprimir" class="tabNovedades index" style="width:96%;float:left;">

                <?php
                echo $this->Form->input('tipoliquidacion',[
                    'type'=>'select',
                    'options'=>$optionsLiquidacion
                ]);
                foreach ($arrayConvenios as $arrayConvenio) {
                    echo $arrayConvenio;
                }
                echo $this->Form->button(
                    "Guardar liquidaciones",
                    array(
                        'class' => 'btn_cargarliq',
                        'onClick' => "guardarTodosLosSueldos()",
                    ),
                    array()
                );
                echo $this->Form->input('arrayEmpleados',[
                    'type'=>'hidden',
                    'value'=>json_encode($arrayEmpleados)
                ]);
                echo $this->Form->button(
                    "Libros de sueldos",
                    array(
                        'class'=>'btn_sueldo',
                        'style'=>'width:inherit;min-width: 141px;',
                        'onClick'=>"cargarTodosLosLibros()",
                        'id'=>'buttonImprimirRecibos',
                    ),
                    array()
                );
                echo $this->Form->button(
                    "Recibos de sueldos",
                    array(
                        'class'=>'btn_sueldo',
                        'style'=>'width:inherit;min-width: 155px;',
                        'onClick'=>"cargarTodosLosRecibos()",
                        'id'=>'buttonImprimirRecibos',
                    ),
                    array()
                );
                ?>
            </div>
          <div style="overflow:auto;width:96%; min-height: 400px; margin-top: 10px" class="tareaCargarIndexTable tabNovedades index">
              <div id="divSueldoForm">
              </div>
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
