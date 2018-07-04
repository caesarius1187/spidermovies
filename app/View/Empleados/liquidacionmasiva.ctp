<?php
echo $this->Html->css('bootstrapmodal');

echo $this->Html->script('jquery-ui.js',array('inline'=>false));

echo $this->Html->script('languages.js',array('inline'=>false));
echo $this->Html->script('numeral.js',array('inline'=>false));
echo $this->Html->script('moment.js',array('inline'=>false));
echo $this->Html->script('jquery-calx-2.2.6',array('inline'=>false));

echo $this->Html->script('empleados/liquidacionmasiva',array('inline'=>false));
echo $this->Form->input('cliid',array('default'=>$cliente["Cliente"]['id'],'type'=>'hidden'));
echo $this->Form->input('periodo',array('default'=>$periodo,'type'=>'hidden'));

?>

<div class="index" style="" id="headerCliente">
  <div style="width:30%; float: left;padding-top:10px">
    Cliente: <?php echo $cliente["Cliente"]['nombre'];
      echo $this->Form->input('clientenombre',['type'=>'hidden','value'=>$cliente["Cliente"]['nombre']]);
      echo $this->Form->input('cliid',['type'=>'hidden','value'=>$cliente["Cliente"]['id']]);
      echo $this->Form->input('indiceLiquidacion',['type'=>'hidden','value'=>0]);
      ?>
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
  <?php ?>
  </div>
</div>
<div id="headerCarga" style="width:100%">
    
  <!--</div>-->
    <?php /**************************************************************************/ ?>
    <?php /*****************************TABS*****************************************/ ?>
    <?php /**************************************************************************/ ?> 
    <div id="bodyCarga" style="width:100%;height:35px;">
       <?php /**************************************************************************/ ?>
       <?php /*****************************Novedades************************************/ ?>
       <?php /**************************************************************************/ ?>
          
        <?php
        $arrayEmpleados=[];

        $Convenio_Empleado_Liquidacion = ',';
        $Conv_Emp_Liq_liquidaprimeraquincena = ',';
        $Conv_Emp_Liq_liquidasegundaquincena = ',';
        $Conv_Emp_Liq_liquidamensual = ',';
        $Conv_Emp_Liq_liquidasac = ',';
        $Conv_Emp_Liq_liquidapresupuestoprimera = ',';
        $Conv_Emp_Liq_liquidapresupuestosegunda = ',';
        $Conv_Emp_Liq_liquidapresupuestomensual = ',';

        $ConvenioEmpleado = '';
        $ConvenioEmpleadoLiquidacion = ',';

        $arrayConvenios=[];
        $arrayButtonsConvenios=[];
        $liquidaPrimeraQuincena = false;
        $liquidaSegundaQuincena = false;
        $liquidaMensual= false;
        $liquidaSAC=false;      
        $liquidaPresupuestoPrimera=false;                                          
        $liquidaPresupuestoSegunda=false;                        
        $liquidaPresupuestoMensual=false;
        $optionsLiquidacion = [];    
        $optionsEmpleados = [];           
        $optionsEmpleados["0"] = "Seleccione Empleado";           
        
        foreach ($cliente['Empleado'] as $empleadolista) {                    

            if($empleadolista['liquidaprimeraquincena'])
            {
                $liquidaPrimeraQuincena=true;   
                $ConvenioEmpleado = $empleadolista['conveniocolectivotrabajo_id'].'_'.$empleadolista['id'];
                $Conv_Emp_Liq_liquidaprimeraquincena = $Conv_Emp_Liq_liquidaprimeraquincena.$ConvenioEmpleado.',';
            }
            if($empleadolista['liquidasegundaquincena'])
            {
                $liquidaSegundaQuincena=true; 
                $ConvenioEmpleado = $empleadolista['conveniocolectivotrabajo_id'].'_'.$empleadolista['id'];
                $Conv_Emp_Liq_liquidasegundaquincena = $Conv_Emp_Liq_liquidasegundaquincena.$ConvenioEmpleado.',';
            }
            if($empleadolista['liquidamensual'])
            {
                $liquidaMensual=true;   
                $ConvenioEmpleado = $empleadolista['conveniocolectivotrabajo_id'].'_'.$empleadolista['id'];
                $Conv_Emp_Liq_liquidamensual = $Conv_Emp_Liq_liquidamensual.$ConvenioEmpleado.',';
            }
            if($empleadolista['liquidasac'])
            {
                $liquidaSAC=true;    
                $ConvenioEmpleado = $empleadolista['conveniocolectivotrabajo_id'].'_'.$empleadolista['id'];
                $Conv_Emp_Liq_liquidasac = $Conv_Emp_Liq_liquidasac.$ConvenioEmpleado.',';
            }
            if($empleadolista['liquidapresupuestoprimera'])
            {
                $liquidaPresupuestoPrimera=true;  
                $ConvenioEmpleado = $empleadolista['conveniocolectivotrabajo_id'].'_'.$empleadolista['id'];
                $Conv_Emp_Liq_liquidapresupuestoprimera = $Conv_Emp_Liq_liquidapresupuestoprimera.$ConvenioEmpleado.',';
            }
            if($empleadolista['liquidapresupuestosegunda'])
            {
                $liquidaPresupuestoSegunda=true; 
                $ConvenioEmpleado = $empleadolista['conveniocolectivotrabajo_id'].'_'.$empleadolista['id'];
                $Conv_Emp_Liq_liquidapresupuestosegunda = $Conv_Emp_Liq_liquidapresupuestosegunda.$ConvenioEmpleado.',';
            }
            if($empleadolista['liquidapresupuestomensual'])
            {
                $liquidaPresupuestoMensual=true;       
                $ConvenioEmpleado = $empleadolista['conveniocolectivotrabajo_id'].'_'.$empleadolista['id'];
                $Conv_Emp_Liq_liquidapresupuestomensual = $Conv_Emp_Liq_liquidapresupuestomensual.$ConvenioEmpleado.',';
            }
            $classButtonEmpleado = "btn_empleados";
            //vamos a agregar a este array solo los empleadosque tengan este convenio
            if(!isset($arrayEmpleados[$empleadolista['conveniocolectivotrabajo_id']])){
                $arrayEmpleados[$empleadolista['conveniocolectivotrabajo_id']]=[];
            }
           /* $arrayConvenios[$empleadolista['conveniocolectivotrabajo_id']]=$this->Form->button(
                $empleadolista['Conveniocolectivotrabajo']['nombre'],
                array(
                    'class'=>'btn_empleados',
                    'onClick'=>"cargarTodosLosSueldos(".$empleadolista['conveniocolectivotrabajo_id'].");",
                    'id'=>'buttonConvenio'.$empleadolista['conveniocolectivotrabajo_id'],
                ),
                array()
            );*/
            $arrayConvenios[$empleadolista['conveniocolectivotrabajo_id']]= $empleadolista['Conveniocolectivotrabajo']['nombre'];
            $arrayEmpleados[$empleadolista['conveniocolectivotrabajo_id']][]=$empleadolista['id'];
            /*if(count($empleadolista['Valorrecibo'])>0){
                $classButtonEmpleado = "btn_empleados_liq";
                $optionsEmpleados['Liquidados'][$empleadolista['id'].'_'.$cliente["Cliente"]['id']] = $empleadolista['nombre'];
            }
            else {
                $optionsEmpleados['Sin Liquidar'][$empleadolista['id'].'_'.$cliente["Cliente"]['id']] = $empleadolista['nombre'];
            }  */          
            /*
            echo $this->Form->button(
                $empleadolista['nombre'],
                array(
                    'class'=>$classButtonEmpleado." parafiltrarempleados",
                    'onClick'=>"cargarunsueldoempleado('".$cliente["Cliente"]['id']."','".$periodo."','".$empleadolista['id']."','0','".$arrayConvenios[$empleadolista['conveniocolectivotrabajo_id']]."')",
                    'id'=>'buttonEmpleado'.$empleadolista['id'],
                    'valorparafiltrar'=>$empleadolista['nombre']." ".$empleadolista['cuit'],
                ),
                array()
            );
            */
        }
        $optionsLiquidacion['0']='Seleccione tipo de liquidacion';        
        if($liquidaPrimeraQuincena)
        {
            $optionsLiquidacion['liquidaprimeraquincena']='liquida primera quincena';        
            echo $this->Form->input('hdnConvEmp_liquidaprimeraquincena',[
            'type'=>'hidden',
            'value'=>$Conv_Emp_Liq_liquidaprimeraquincena
            ]);    
        }
        if($liquidaSegundaQuincena)
        {
            $optionsLiquidacion['liquidasegundaquincena']='liquida segunda quincena';                
            echo $this->Form->input('hdnConvEmp_liquidasegundaquincena',[
            'type'=>'hidden',
            'value'=>$Conv_Emp_Liq_liquidasegundaquincena
            ]);    
        }
        if($liquidaMensual){
            $optionsLiquidacion['liquidamensual']='liquida mensual';                
            echo $this->Form->input('hdnConvEmp_liquidamensual',[
            'type'=>'hidden',
            'value'=>$Conv_Emp_Liq_liquidamensual
            ]);    
        }
        if($liquidaSAC)
        {
            $optionsLiquidacion['liquidasac']='liquida sac';        
            echo $this->Form->input('hdnConvEmp_liquidasac',[
            'type'=>'hidden',
            'value'=>$Conv_Emp_Liq_liquidasac
            ]);    
        }
        if($liquidaPresupuestoPrimera)
        {
            $optionsLiquidacion['liquidapresupuestoprimera']='liquida presupuesto primera';                
            echo $this->Form->input('hdnConvEmp_liquidapresupuestoprimera',[
            'type'=>'hidden',
            'value'=>$Conv_Emp_Liq_liquidapresupuestoprimera
            ]);    
        }
        if($liquidaPresupuestoSegunda)
        {
            $optionsLiquidacion['liquidapresupuestosegunda']='liquida presupuesto segunda';        
            echo $this->Form->input('hdnConvEmp_liquidapresupuestosegunda',[
            'type'=>'hidden',
            'value'=>$Conv_Emp_Liq_liquidapresupuestosegunda
            ]);    
        }
        if($liquidaPresupuestoMensual)
        {
            $optionsLiquidacion['liquidapresupuestomensual']='liquida presupuesto mensual';
            echo $this->Form->input('hdnConvEmp_liquidapresupuestomensual',[
            'type'=>'hidden',
            'value'=>$Conv_Emp_Liq_liquidapresupuestomensual
            ]);    
        }
        ?>         
        <div id="form_FuncionImprimir" class="tabNovedades index" style="float:left;">
            <div style="width:40%; float:left">
                <?php
                echo $this->Form->input('tipoliquidacion',[
                    'type'=>'select',
                    'options'=>$optionsLiquidacion,
                    'onchange' => 'cargarPaginasPorConvenio(this)',
                    'label' => 'Tipo de liquidacion',
                    'div' => false
                ]);
                ?>
            </div>
            <div style="width:30%; float:left">
            <?php
            /*echo $this->Form->input('ddlEmpleados',[
                'type'=>'select',
                'options'=>$optionsEmpleados,
                'onchange' => 'ddlCargarunsueldoempleado(this)',
                'label' => 'Empleados',                    
                'div' => false
            ]);*/
            ?>
            </div>
            <div style="width:30%; float:right; text-align:center">
                <?php
                echo $this->Form->button(
                    "Guardar liquidaciones",
                    array(
                        'class' => 'btn_cargarliq',
                        'onClick' => "GenerarYGuardarTodosLosSueldos()",
                    ),
                    array()
                );
                ?>
            </div>
                <?php
                foreach ($arrayEmpleados as $kemp => $value) {
                    echo $this->Form->input('arrayEmpleados'.$kemp,[
                        'type'=>'hidden',
                        'value'=>json_encode($arrayEmpleados[$kemp])
                    ]);

                    echo $this->Form->input('hdnConvenioNombre_'.$kemp,[
                        'type'=>'hidden',
                        'value'=> $arrayConvenios[$kemp]
                    ]);                    
                    echo '<div id="divPaginasConvenio_'.$kemp.'">';
                    /*Aqui van las Paginas.*/
                    echo '</div>';
                }                
                echo '</br>';
                echo $this->Form->input('arrayEmpleados',[
                    'type'=>'hidden',
                    'value'=>json_encode($arrayEmpleados)
                ]);
                
                ?>           
        </div>
        <div style="overflow:auto; margin-top: 10px" class="tareaCargarIndexTable tabNovedades index">
            <div id="">
                <?php
                if(isset($convenio)){
                    echo $this->Form->input('conceptos',[
                        'multiple' => 'multiple',
                        'type' => 'select',
                        'value'=>$conceptos,
                        'class'=>'chosen-select'
                    ]);
                    echo $this->Form->input('convenio',[
                        'type'=>'hidden',
                    ]);
                    echo $this->Form->input('tipoliquidacionAguardar',[
                        'type'=>'hidden',
                        'value'=>$tipoliquidacion
                    ]);
                    $listaempleados=[];
                    foreach ($empleados as $ke => $empleado) {
                        $listaempleados[]=$empleado['Empleado']['id'];
                    }
                    echo $this->Form->input('empleadosALiquidar',[
                        'type'=>'hidden',
                        'value'=> json_encode($listaempleados)
                    ]);
                    echo $this->Form->input('empleadosJson',[
                        'type'=>'hidden',
                        'value'=> json_encode($empleados)
                    ]);
                    //vamos a mostrar la tabla con los conceptos
                    echo $this->Form->create('Valorrecibo',[
                        'action'=>'guardardatosmasivos'
                    ]);
                      ?>
                <table id="tblLiquidacionMasiva" >
                    <tr header="1">
                        <td>Empleados/Novedades</td>
                    </tr>
                        <?php 
                        foreach ($empleados as $ke => $empleado) {
                            ?>
                            <tr empid="<?php echo $empleado['Empleado']['id'];?>" >
                               <td >
                                   <?php
                                   echo $empleado['Empleado']['nombre'];
                                   ?>
                               </td>
                           </tr>
                        <?php
                        }
                    ?>
                    
                    <?php
                    $i=0;
                    
                    ?>
                </table>
                      <?php
                     echo $this->Form->submit('Guardar Datos',[
                         'style'=>'width:154px',
                         'div'=>[ 'style'=>'float:left']
                     ]);
                  }
                  ?>
            </div>
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
