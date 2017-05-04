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

echo $this->Html->script('empleados/cargar',array('inline'=>false));
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
      <?php
      echo $this->Html->link(
          "Carga Masivas",
          array(
              'controller' => 'empleados',
              'action' => 'cargamasiva',
              $cliente["Cliente"]['id'],
              $periodo
          ),
          array('class' => 'buttonImpcli',
              'style'=> 'margin-right: 8px;width: initial;'
          )
      );
      echo $this->Form->button('Finalizar',
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
        </div>
       <?php /**************************************************************************/ ?>
       <?php /*****************************Novedades************************************/ ?>
       <?php /**************************************************************************/ ?>
          <div id="form_empleados" class="tabNovedades index" style="width:96%;float:left;">
                <?php
                $arrayEmpleados=[];
                $liquidaPrimeraQuincena = false;
                $liquidaSegundaQuincena = false;
                $liquidaMensual= false;
                $liquidaSAC= false;
                foreach ($cliente['Empleado'] as $empleadolista) {
                    if($empleadolista['liquidaprimeraquincena']){
                        $liquidaPrimeraQuincena=true;
                    }
                    if($empleadolista['liquidasegundaquincena']){
                        $liquidaSegundaQuincena=true;
                    }
                    if($empleadolista['liquidamensual']){
                        $liquidaMensual=true;
                    }
                    if($empleadolista['liquidasac']){
                        $liquidaSAC=true;
                    }

                    $classButtonEmpleado = "btn_empleados";
                    $arrayEmpleados[]=$empleadolista['id'];
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
          <div id="form_FuncionImprimir" class="tabNovedades index" style="width:96%;float:left;">
                <?php
                echo $this->Form->input('arrayEmpleados',[
                    'type'=>'hidden',
                    'value'=>json_encode($arrayEmpleados)
                ]);
                if($liquidaPrimeraQuincena){?>
                    <fieldset style="border: 1px solid #1e88e5; width: 28%; float: left;">
                        <legend style="color:#1e88e5;font-weight:normal;">Primera Quincena</legend>
                        <?php
                        echo $this->Form->button(
                            "Libros de sueldos",
                            array(
                                'class'=>'btn_sueldo',
                                'style'=>'width:inherit;min-width: 141px;',
                                'onClick'=>"cargarTodosLosLibros(1)",
                                'id'=>'buttonImprimirRecibos',
                            ),
                            array()
                        );
                        echo $this->Form->button(
                            "Recibos de sueldos",
                            array(
                                'class'=>'btn_sueldo',
                                'style'=>'width:inherit;min-width: 155px;',
                                'onClick'=>"cargarTodosLosRecibos(1)",
                                'id'=>'buttonImprimirRecibos',
                            ),
                            array()
                        );
                        ?>
                    </fieldset>
                <?php
                }
                if($liquidaSegundaQuincena){?>
                    <fieldset style="border: 1px solid #1e88e5;width: 28%; float: left;">
                        <legend style="color:#1e88e5;font-weight:normal;">Segunda Quincena</legend>
                        <?php
                        echo $this->Form->button(
                            "Libros de sueldos",
                            array(
                                'class'=>'btn_sueldo',
                                'style'=>'width:inherit;min-width: 141px;',
                                'onClick'=>"cargarTodosLosLibros(2)",
                                'id'=>'buttonImprimirRecibos',
                            ),
                            array()
                        );
                        echo $this->Form->button(
                            "Recibos de sueldos",
                            array(
                                'class'=>'btn_sueldo',
                                'style'=>'width:inherit;min-width: 155px;',
                                'onClick'=>"cargarTodosLosRecibos(2)",
                                'id'=>'buttonImprimirRecibos',
                            ),
                            array()
                        );
                        ?>
                    </fieldset>
                    <?php
                }
                if($liquidaMensual){?>
                    <fieldset style="border: 1px solid #1e88e5;width: 28%; float: left;">
                        <legend style="color:#1e88e5;font-weight:normal;">Mensual</legend>
                        <?php
                        echo $this->Form->button(
                            "Libros de sueldos",
                            array(
                                'class'=>'btn_sueldo',
                                'style'=>'width:inherit;min-width: 141px;',
                                'onClick'=>"cargarTodosLosLibros(3)",
                                'id'=>'buttonImprimirRecibos',
                            ),
                            array()
                        );
                        echo $this->Form->button(
                            "Recibos de sueldos",
                            array(
                                'class'=>'btn_sueldo',
                                'style'=>'width:inherit;min-width: 155px;',
                                'onClick'=>"cargarTodosLosRecibos(3)",
                                'id'=>'buttonImprimirRecibos',
                            ),
                            array()
                        );
                        ?>
                    </fieldset>
                    <?php
                }
                ?>
            </div>
          <div style="overflow:auto;width:96%; min-height: 400px; margin-top: 10px" class="tareaCargarIndexTable tabNovedades index" id="divSueldoForm">

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
