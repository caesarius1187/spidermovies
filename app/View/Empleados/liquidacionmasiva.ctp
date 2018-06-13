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

echo $this->Html->script('empleados/liquidacionmasiva',array('inline'=>false));
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
      echo $this->Form->input('clientenombre',['type'=>'hidden','value'=>$cliente["Cliente"]['nombre']]);
      echo $this->Form->input('cliid',['type'=>'hidden','value'=>$cliente["Cliente"]['id']]);
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
  </div>
</div>
<div id="headerCarga" style="width:100%">
    
  <!--</div>-->
    <?php /**************************************************************************/ ?>
    <?php /*****************************TABS*****************************************/ ?>
    <?php /**************************************************************************/ ?> 
    <div id="bodyCarga" style="width:100%;height:35px;">
        <div class="" style="width:96%;height:30px; margin-left:10px " id="divAllTabs">
            <div class="cliente_view_tab_active" id="divTabEmpleados" style="width:18.5%;margin-right:0px"  onClick="SeleccionarTab('1');">
                <?php
                    echo $this->Form->label(null, $text = 'Empleados',array('style'=>'text-align:center;margin-top:5px;cursor:pointer'));
                ?>
            </div>
        </div>
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
          <div id="form_FuncionImprimir" class="tabNovedades index" style="width:96%;float:left;">
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
                <div style="width:30%; float:left; text-align:center">
                <?php
                echo $this->Form->button(
                    "Guardar liquidaciones",
                    array(
                        'class' => 'btn_cargarliq',
                        'onClick' => "guardarTodosLosSueldos()",
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
                echo $this->Form->input('conceptos',[
                    'multiple' => 'multiple',
                    'type' => 'select',
                    'value'=>$conceptos,
                    'class'=>'chosen-select'
                ]);
                ?>           
            </div>
        <div style="overflow:auto;width:96%; min-height: 400px; margin-top: 10px" class="tareaCargarIndexTable tabNovedades index">
            <div id="divSueldoForm">
                <?php
                if(isset($convenio)){
                      //vamos a mostrar la tabla con los conceptos
                      ?>
                <table id="tblLiquidacionMasiva">
                    <tr>
                        <td>Novedades/Empleados</td>
                        <?php 
                        foreach ($empleados as $ke => $empleado) {
                            ?>
                        <td><?php
                            echo $empleado['Empleado']['nombre'];
                            ?>
                        </td>
                        <?php
                        }
                    ?>
                    </tr>
                    <?php
                    $i=0;
                    
                    foreach ($convenio['Cctxconcepto'] as $kcctxc => $cctxc) {
                        if($cctxc['Concepto']['seccion']!='DATOS')continue;
                        ?>
                    <tr>
                        <td><?php echo $cctxc['nombre']?></td>
                        <?php 
                            foreach ($empleados as $ke => $empleado) {
                                    //aca buscamos el valor que ya guardardamos para este concepto
                                    //y mostramos un formulario para modificarlo
                                    $valor = 0;                                    
                                    $valordefault = '';
                                    $porcentaje = 0;
                                    $valorreciboid = 0;
                                    $aplicafuncion = true;
                                    $muestraAplicarATodos = false;
                                    $formulamodificada = false;
                                    $nuevaformula = "";
                                    $conceptoACargado = [];
                                    $conceptoACargado['Valorrecibo'] = [];
                                    foreach ($empleado['Valorrecibo'] as $kvr => $valorrecibo) {
                                        if($valorrecibo['cctxconcepto_id']==$cctxc['id']){
                                            $conceptoACargado['Valorrecibo'] = $valorrecibo;
                                        }
                                    }
                                    if(count($conceptoACargado['Valorrecibo'])>0){
                                        $valor = $conceptoACargado['Valorrecibo']['valor'];
                                        $valordefault = $conceptoACargado['Valorrecibo']['valor'];
                                        $valorreciboid = $conceptoACargado['Valorrecibo']['id'];
                                    }else{

                                    }
                                    if($cctxc['Concepto']['aplicaatodos']){
                                        $muestraAplicarATodos=true;
                                    }
                                    $options = [];
                                    switch ($cctxc['Concepto']['id']){
                                        case 9:/*Precio de la Hora*/
                                            /* Aca vamos a preguntar si el empleado tiene un cargo definido y si este cargo
                                              tiene un precio de la hora cargado*/
                                            if(isset($empleado['Cargo']['preciohora'])&&$empleado['Cargo']['preciohora']*1!=0){
                                                $valor = $empleado['Cargo']['preciohora']*1;
                                            }
                                            break;
                                        case 10:/*Jornal*/
                                            /* Aca vamos a preguntar si el empleado tiene un cargo definido y si este cargo
                                              tiene un jornal cargado*/
                                            if(isset($empleado['Cargo']['jornal'])&&$empleado['Cargo']['jornal']*1!=0){
                                                $valor = $empleado['Cargo']['jornal']*1;
                                            }
                                            break;
                                        case 11:/*Jornada*/
                                            $valor = $empleado['Empleado']['jornada'];
                                            break;
                                        case 16:/*Ingreso*/
                                            $valor = $empleado['Empleado']['fechaingreso'];
                                            break;
                                        case 67:/*Egreso*/
                                            $valor = $empleado['Empleado']['fechaegreso'];
                                            break;
                                        case 17:/*Periodo*/
                                            $pemes = substr($periodo, 0, 2);
                                            $peanio = substr($periodo, 3);
                                            $valor = date("Y-m-d",(mktime(0,0,0,$pemes+1,1,$peanio)-1));
                                            break;
                                        case 33:/*Obra Social*/
                                            //$conceptoobligatorio['nombre'] = $empleado['Empleado']['obrasocial'];
                                            //$conceptoobligatorio['porcentaje']=$empleado['Empleado']['porcentajeos'];
                                            break;
                                        case 34:/*Obra Social Minimo*/
                                            //$valor = $empleado['Empleado']['minimoos'];
                                            break;
                                        case 35:/*Obra Social Extraordinario*/
                                            break;
                                        case 38:/*cuota sindical extra 2*/
                                            if($empleado['Conveniocolectivotrabajo']['id']==5/*Es Construcción Quincenal?*/){
                                                //el segurod e vida obligatorio se paga solo si estamos en la segunda quincena
                                                //o si la fecha de baja esta dentro de la primera quincena
                                                if($tipoliquidacion != 2){
                                                    //No estamos en la segunda quincena

                                                    $aplicafuncion = false;
                                                    //$aplicafuncion = true;

                                                    //tengo que preguntar si la fecha de baja es mayor que 01-periodo
                                                    //y menor que 15-periodo
                                                    $inicioperiodo=date('Y-m-d',strtotime('01-'.$pemes.'-'.$peanio));
                                                    $finPrimeraQuincena=date('Y-m-d',strtotime('15-'.$pemes.'-'.$peanio));
                                                    $confecha = $empleado['Empleado']['fechaegreso']!="";
                                                    $mayorqueinicio =  $inicioperiodo<$empleado['Empleado']['fechaegreso'];
                                                    $menorquefin =  $empleado['Empleado']['fechaegreso']<$finPrimeraQuincena;
                                                    if($confecha&&$mayorqueinicio&&$menorquefin)
                                                    {
                                                        $aplicafuncion = true;
                                                    }
                                                }else{
                                                    //preguntemos si la fecha de despido cae en la primera quincena
                                                    $inicioperiodo=date('Y-m-d',strtotime('01-'.$pemes.'-'.$peanio));
                                                    $finPrimeraQuincena=date('Y-m-d',strtotime('15-'.$pemes.'-'.$peanio));
                                                    $confecha = $empleado['Empleado']['fechaegreso']!="";
                                                    $mayorqueinicio =  $inicioperiodo<$empleado['Empleado']['fechaegreso'];
                                                    $menorquefin =  $empleado['Empleado']['fechaegreso']<$finPrimeraQuincena;
                                                    if($confecha&&$mayorqueinicio&&$menorquefin)
                                                    {
                                                        $aplicafuncion = false;
                                                    }
                                                }
                                            }
                                            break;
                                        case 39:/*Afiliado al Sindicato*/
                                            $valor = $empleado['Empleado']['afiliadosindicato'];
                                            break;
                                        case 51:/*CODIGO AFIP*/
                                            $valor = $empleado['Empleado']['codigoafip'];
                                            break;
                                        case 52:/*Sueldo basico*/
                                                /* Aca vamos a preguntar si el empleado tiene un cargo definido y si este cargo
                                                tiene un sueldo basico cargado*/
                                                if(isset($empleado['Cargo']['sueldobasico'])&&$empleado['Cargo']['sueldobasico']*1!=0){
                                                    $valor = $empleado['Cargo']['sueldobasico']*1;
                                                }
                                            break;
                                        case 53:/*Acuerdos No Remunerativos*/
                                                /* Aca vamos a preguntar si el empleado tiene un cargo definido y si este cargo
                                                tiene un Acuerdos No Remunerativos cargado*/
                                                if(isset($empleado['Cargo']['acuerdonoremunerativo'])&&$empleado['Cargo']['acuerdonoremunerativo']*1!=0){
                                                    $valor = $empleado['Cargo']['acuerdonoremunerativo']*1;
                                                }
                                            break;
                                        case 54:/*Sueldo Sereno(UOCRA)*/
                                                /* Aca vamos a preguntar si el empleado tiene un cargo definido y si este cargo
                                                tiene un sueldo sereno cargado*/
                                                if(isset($empleado['Cargo']['sueldosereno'])&&$empleado['Cargo']['sueldosereno']*1!=0){
                                                    $valor = $empleado['Cargo']['sueldosereno']*1;
                                                }
                                            break;
                                        case 75:/*Dia del Gremio*/
                                                /* Solo SEC tiene esto y se tiene que activar solo si estamos en Septiembre*/
                                                if($empleado['Conveniocolectivotrabajo']['Impuesto']['id']==11/*Es SEC?*/){
                                                    $pemes = substr($periodo, 0, 2);
                                                    if($pemes!='09'){
                                                        $aplicafuncion=false;
                                                    }
                                                }
                                            break;

                                        case 117:/*Aporte Adicional OS O3*/
                                            /* si es construccion no aplica en el SAC*/
                                            if($empleado['Conveniocolectivotrabajo']['id']==5/*Es Construcción Quincenal?*/){
                                                if($tipoliquidacion== 7){
                                                    //SAC de construccion = 0, o sea no aplica funcion
                                                    $aplicafuncion = false;
                                                }
                                            }
                                            break;
                                        case 123:/*Contribucion Tarea Diff*/
                                                /* si es UOCRA tenemos que poner que es un 5%*/
                                                 if($empleado['Conveniocolectivotrabajo']['impuesto_id']==41/*Es UOCRA?*/){
                                                    $valor = 5;
                                                }
                                            break;
                                        case 126:/*Acuerdos Remunerativos*/
                                                /* Aca vamos a preguntar si el empleado tiene un cargo definido y si este cargo
                                                tiene un Acuerdos Remunerativos cargado*/
                                                if(isset($empleado['Cargo']['acuerdoremunerativo'])&&$empleado['Cargo']['acuerdoremunerativo']*1!=0){
                                                    $valor = $empleado['Cargo']['acuerdoremunerativo']*1;
                                                }
                                            break;

                                        case 134:/*cuota sindical extra 4*/
                                            /*si el impcli al que pertenece el convenio es SEC entonces vamos a preguntar si
                                            tiene activado el "pago del seguro de vida obligatorio*/
                                            if($empleado['Conveniocolectivotrabajo']['Impuesto']['id']==11/*Es SEC?*/){
                                                if(!$empleado['Conveniocolectivotrabajo']['Impuesto']['Impcli'][0]['segurovidaobligatorio']*1){

                                                    $aplicafuncion=false;
                                                }else{
                                                    //aca tambien tenemos que ver si el IMPCLI(SEC) tiene el dato "primasvo" y asignarlo
                                                    //a el % que tendria q usar este campillo
                                                    if($empleado['Conveniocolectivotrabajo']['Impuesto']['Impcli'][0]['primasvo']*1!=0){
                                                        $porcentaje = $empleado['Conveniocolectivotrabajo']['Impuesto']['Impcli'][0]['primasvo'];
                                                    }
                                                }
                                            }

                                            break;
                                        case 152:/*Mejor Remunerativos*/
                                            //si ya guardamos un valo no reemplazemos por el nuevo, mostremos el guardado
                                            if($valorreciboid==0){
                                                $valor = isset($mayorRemunerativo)?$mayorRemunerativo:0;
                                            }
                                            break;
                                        case 153:/*Mejor NO Remunerativos*/
                                            /* Aca vamos a preguntar si el empleado tiene un cargo definido y si este cargo
                                            tiene un Acuerdos Remunerativos cargado*/
                                            if($valorreciboid==0) {
                                                $valor = isset($mayorNORemunerativo)?$mayorNORemunerativo:0;
                                            }
                                            break;
                                        case 161:/*Basico Categoria Minima*//*Basico Adm 2da*/
                                            if($valorreciboid==0) {
                                                $valor = $basicoMinimoCargo;
                                            }
                                            break;
                                        case 162:/*Almuerzo o Refrigerio*/                                  
                                            $valor = 1;
                                            break;


                                        case 175:/*Asignacion Rem 1er Quincena*/
                                            if($tipoliquidacion == 1) {
                                                //Estamos en la primer quincena
                                                if (isset($empleado['Cargo']['remprimerquincena']) && $empleado['Cargo']['remprimerquincena'] * 1 != 0) {
                                                    $valor = $empleado['Cargo']['remprimerquincena'] * 1;
                                                }
                                            }
                                            break;
                                        case 176:/*Asignacion Rem 2da Quincena*/
                                            if($tipoliquidacion == 2) {
                                                //Estamos en la segunda quincena
                                                if (isset($empleado['Cargo']['remsegundaquincena']) && $empleado['Cargo']['remsegundaquincena'] * 1 != 0) {
                                                    $valor = $empleado['Cargo']['remsegundaquincena'] * 1;
                                                }
                                            }
                                            break;
                                         case 177:/*Dia del Gremio no remunerativo*/
                                                /* Solo SEC tiene esto y se tiene que activar solo si estamos en Septiembre*/
                                                if($empleado['Conveniocolectivotrabajo']['Impuesto']['id']==11/*Es SEC?*/){
                                                    $pemes = substr($periodo, 0, 2);
                                                    if($pemes!='09'){
                                                        $aplicafuncion=false;
                                                    }
                                                }
                                            break;      
                                         case 183:/*Titulo Universitario*/                                  
                                            $valor = $empleado['Empleado']['titulouniversitario'];
                                            break;
                                        case 184:/*Titulo Secundario*/                                  
                                            $valor = $empleado['Empleado']['titulosecundario'];
                                            break;
                                        case 185:/*Ad km menor que 100*/                                  
                                            if (isset($empleado['Cargo']['kmmenortope']) && $empleado['Cargo']['kmmenortope'] * 1 != 0) {
                                                $valor = $empleado['Cargo']['kmmenortope'] * 1;
                                            }
                                            break;
                                        case 186:/*Ad km mayor que 100*/                                  
                                            if (isset($empleado['Cargo']['kmmayortope']) && $empleado['Cargo']['kmmayortope'] * 1 != 0) {
                                                $valor = $empleado['Cargo']['kmmayortope'] * 1;
                                            }
                                            break;    
                                        case 190:/*Situacion de Revista 1*/                                  
                                        case 192:/*Situacion de Revista 1*/                                  
                                        case 194:/*Situacion de Revista 1*/                                  
                                           $options = $codigorevista;
                                           if($valordefault==''){
                                               $valordefault = '01';
                                           }
                                            break;    
                                        case 197:/*Paga Fallo de Caja*/                                  
                                           $valor = $empleado['Empleado']['fallodecaja'];
                                            break;    
                                        /*case 36:/*Cuota Sindical aca estabamos guardando la cuota sindical extra en el empleado pero
                                        debe ser la misma para todos dependiendo del convenio
                                            $conceptoobligatorio['nombre'] = $empleado['Empleado']['cuotasindical'];
                                            $conceptoobligatorio['porcentaje']=$empleado['Empleado']['porcentajecs'];
                                            break;
                                        /*case 37:/*Cuota Sindical Extra 1 aca estabamos guardando la cuota sindical extra en el empleado pero
                                        debe ser la misma para todos dependiendo del convenio
                                            $conceptoobligatorio['nombre'] = $empleado['Empleado']['cuotasindicalextraordinario'];
                                            $conceptoobligatorio['porcentaje']=$empleado['Empleado']['porcentajecse'];
                                            break;*/
                                    }
                                $classInputValor="".$cctxc['Concepto']['codigo'];
                                $inputClass="";

                                if($muestraAplicarATodos){
                                    $classInputValor .= " aplicableATodos";
                                    $inputClass = "input".$cctxc['Concepto']['codigo'];
                                }?>
                                <td width="80px" class="tdvalor" style='height:30px'>
                                    <?php
                                    $funcionaaplicar="";
                                    if($cctxc['calculado']&&$aplicafuncion){
                                        //aca aplico la formula del cctxconcepto pero si se ha modificado para este valorrecibo
                                        //muestro la modificada
                                        if($formulamodificada){
                                            $funcionaaplicar=$nuevaformula;
                                        }else{
                                            $funcionaaplicar=$cctxc['funcionaaplicar'];
                                        }
                                    }else{
                                        $funcionaaplicar = "";
                                    }
                                    $unidadmedida = $cctxc['unidaddemedida'];
                                    $datacell = 0;
                                    if(!$cctxc['campopersonalizado']){
                                        $datacell = $cctxc['Concepto']['codigo'];
                                    }else{
                                        $datacell = $cctxc['codigopersonalizado'];
                                    }
                                    echo $this->Form->input('Valorrecibo.'.$i.'.id',array('type'=>'hidden','value'=>$valorreciboid));
                                    echo $this->Form->input('Valorrecibo.'.$i.'.periodo',array('type'=>'hidden','value'=>$periodo));
                                    echo $this->Form->input('Valorrecibo.'.$i.'.tipoliquidacion',array('type'=>'hidden','value'=>$tipoliquidacion));
                                    echo $this->Form->input('Valorrecibo.'.$i.'.cctxconcepto_id',array('type'=>'hidden','value'=>$cctxc['id']));
                                    echo $this->Form->input('Valorrecibo.'.$i.'.empleado_id',array('type'=>'hidden','value'=>$empleado['Empleado']['id']));

                                    //si es boolean vamos a mostrar un Si con true y un no con False para que elija
                                    ?>
                                    <?php
                                    if($unidadmedida=="boolean"){
                                        $optionsValor=array(
                                            'type'=>'checkbox',
                                            'data-cell'=>$datacell ,
                                            'valor'=>$valor ,
                                            'data-formula'=>$funcionaaplicar,
                                            'class'=>$classInputValor,
                                            'inputclass' => $inputClass,
                                            'label' => false,
                                            'valdata-codigo' => $cctxc['Concepto']['codigo'],
                                        );
                                        if($valor){
                                            $optionsValor['checked']='checked';
                                        }
                                        echo $this->Form->input('Valorrecibo.'.$i.'.valor',$optionsValor);
                                    }else if($unidadmedida=="fecha"){
                                        echo $this->Form->input('Valorrecibo.'.$i.'.valor',array(
                                            'value'=>$valor,
                                            'data-cell'=>$datacell ,
                                            'class'=>$classInputValor,
                                            'inputclass' => $inputClass,
                                            'label' => false,
                                            'valdata-codigo' => $cctxc['Concepto']['codigo'],
                                        ));
                                    }else if($unidadmedida=="select"){
                                        echo $this->Form->input('Valorrecibo.'.$i.'.valor',array(
                                            'default'=>$valordefault,
                                            'value'=>$valordefault,
                                            'options' => $options,
                                            'type' => 'select',
                                            'label' => false,
                                        )); 
                                    }else{
                                        echo $this->Form->input('Valorrecibo.'.$i.'.valor',array(
                                            'value'=>$valor,
                                            'data-cell'=>$datacell ,
                                            'label' => false,
                                            'data-formula'=>$funcionaaplicar,
                                            'data-format'=>'00[.]00',
                                            'class'=>$classInputValor,
                                            'inputclass' => $inputClass,
                                            'valdata-codigo' => $cctxc['Concepto']['codigo'],
                                            'style' => 'padding:0px'
                                            ));
                                    }
                                    ?>
                                </td>
                                                                
                                <?php
                                $i++;
                            }
                        ?>
                    </tr>
                        <?php
                    }
                    ?>
                </table>
                      <?php
                  }
                  ?>
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
