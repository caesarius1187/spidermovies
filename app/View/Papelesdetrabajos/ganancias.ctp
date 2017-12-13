<?php
echo $this->Html->script('jquery-ui',array('inline'=>false));
echo $this->Html->css('bootstrapmodal');
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
//echo $this->Html->script('jquery.table2excel',array('inline'=>false));
echo $this->Html->script('table2excel',array('inline'=>false));

echo $this->Html->script('jquery.dataTables.js',array('inline'=>false));
echo $this->Html->script('dataTables.altEditor.js',array('inline'=>false));
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));

echo $this->Html->script('papelesdetrabajos/ganancias',array('inline'=>false));


/**
 * Created by PhpStorm.
 * User: caesarius
 * Date: 02/11/2016|
 * Time: 12:15 PM
 */
    $periodoActual =  date('Y', strtotime($fechaInicioConsulta));
?>
<div class="index" style="padding: 0px 1%; margin-bottom: 11px;" id="headerCliente">
    <div style="width:30%; float: left;padding-top:11px">
        Contribuyente: <?php echo $cliente["Cliente"]['nombre'];
        echo $this->Form->input('clientenombre',['type'=>'hidden','value'=>$cliente["Cliente"]['nombre']]);
        echo $this->Form->input('cliid',['type'=>'hidden','value'=>$cliente["Cliente"]['id']]);
        echo $this->Form->input('tienetercera',['type'=>'hidden','value'=>$tienetercera]);?>
    </div>
    <div style="width:25%; float: left;padding-top:11px">
        Periodo: <?php echo $periodo;
        echo $this->Form->input('periodo',['type'=>'hidden','value'=>$periodo])?>
    </div>
    <div style="float:right; width:45%">
        <?php                       
        echo $this->Form->button('Imprimir',
            array('type' => 'button',
                'class' =>"btn_imprimir",
                'onClick' => "imprimir()"
            )
        );
        ?>
        <div style="display: none">
            <?php
            echo $this->Form->create('cuentascliente',['id'=>'plandecuentasForm','action' => 'plancuentas']); ?>
            <?php
            echo $this->Form->input('clis', array(
                //'multiple' => 'multiple',
                'type' => 'hidden',
                'value' => $cliente["Cliente"]['id'],
                'class'=>'btn_imprimir',
                'label' => false,
            )); ?>
            <?php echo $this->Form->end(__('Plan de cuentas')); ?>
        </div>
        <?php
        echo $this->Form->button('Excel',
            array('type' => 'button',
                'id'=>"clickExcel",
                'class' =>"btn_imprimir",
            )
        );?>
    </div>
</div>
<div style="width:100%; height:30px; margin-left: 11px;"  class="Formhead noExl" id="divTabs" >
    <div id="tabSumasYSaldos" class="cliente_view_tab_active" onclick="CambiarTab('sumasysaldos');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Balance de Sumas y Saldos</label>
    </div>
    <div id="tabPrimeraCategoria" class="cliente_view_tab" onclick="CambiarTab('primeracategoria');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">1&#176;,2&#176;,3&#176;,3&#176;EU,4&#176; Cat.</label>
    </div>
    <div id="tabPatrimoniotercera" class="cliente_view_tab" onclick="CambiarTab('patrimoniotercera');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Pat 3&#176;(EU)</label>
    </div>
    <div id="tabTercerarRestoCategoria" class="cliente_view_tab" onclick="CambiarTab('tercerarestocategoria');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Patrimonio</label>
    </div>
    <div id="tabTerceraCategoria" class="cliente_view_tab" onclick="CambiarTab('terceracategoria');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Ded. Grales.</label>
    </div>
    <div id="tabCuartaABCCategoria" class="cliente_view_tab" onclick="CambiarTab('cuartaabccategoria');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Ded. Pers.</label>
    </div>
    <div id="tabCuartaDEFCategoria" class="cliente_view_tab" onclick="CambiarTab('cuartadefcategoria');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Determinacion del impuesto</label>
    </div>
    <div id="tabJustVarPat" class="cliente_view_tab" onclick="CambiarTab('justificacionvarpat');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Just. Var. Pat.</label>
    </div>
</div>
<div class="index estadocontable" id="divContenedorBSyS" >
    <?php
    echo "<h3>Balance de Sumas y Saldos del periodo  ".date('d-m-Y', strtotime($fechaInicioConsulta))." hasta ".date('d-m-Y', strtotime($fechaFinConsulta))."</h3>";
    ?>
    <table id="tblsys"  class="toExcelTable tbl_border tblEstadoContable splitForPrint" cellspacing="0">
        <thead>
            <tr class="trnoclickeable">
                <td>N&uacute;mero</td>
                <td>Cuenta</td>
                <td>Saldo Actual</td>
            </tr>
        </thead>
        <tbody>
        <?php
        //$arrayTotales=[];
        $arrayCuentasxPeriodos=[];/*En este array vamos a guardar los valores de cada cuenta
        con su periodo(asociado el valor al numero de cuenta)*/        
        foreach ($cuentasclientes as $kc => $cuentascliente){
            $numerodecuenta = $cuentascliente['Cuenta']['numero'];
            //si no hay movimientos para esta cuentacliente no la vamos a mostrar en el suma y saldos
            if(count($cuentascliente['Movimiento'])==0){
                continue;
            }
            $saldoCalculado = 0;
            $arrayPeriodos = [];
            foreach ($cuentascliente['Movimiento'] as $movimiento){
                
                $periodoAImputar = date('Y', strtotime($fechaInicioConsulta));
                
                if(!isset($arrayPeriodos[$periodoAImputar])){
                    $arrayPeriodos[$periodoAImputar]=[];
                    $arrayPeriodos[$periodoAImputar]['debes']=0;
                    $arrayPeriodos[$periodoAImputar]['haberes']=0;
                    $arrayPeriodos[$periodoAImputar]['apertura']=0;
                    $arrayPeriodos[$periodoAImputar]['movimiento']=0;
                    $arrayPeriodos[$periodoAImputar]['activo']=0;
                    $arrayPeriodos[$periodoAImputar]['pasivo']=0;
                    $arrayPeriodos[$periodoAImputar]['perdida']=0;
                    $arrayPeriodos[$periodoAImputar]['ganancia']=0;
                }
                if(!isset($arrayCuentasxPeriodos[$numerodecuenta])){
                    $arrayCuentasxPeriodos[$numerodecuenta] = [];
                    $arrayCuentasxPeriodos[$numerodecuenta]['nombrecuenta'] = $cuentascliente['Cuentascliente']['nombre'];
                    $arrayCuentasxPeriodos[$periodoAImputar] = 0;
                    $arrayCuentasxPeriodos[$numerodecuenta]['apertura'] = [];
                    $arrayCuentasxPeriodos[$numerodecuenta]['movimiento'] = [];     
                    $arrayCuentasxPeriodos[$numerodecuenta]['activo']=0;
                    $arrayCuentasxPeriodos[$numerodecuenta]['pasivo']=0;
                    $arrayCuentasxPeriodos[$numerodecuenta]['perdida']=0;
                    $arrayCuentasxPeriodos[$numerodecuenta]['ganancia']=0;
                }          
                
                if($movimiento['Asiento']['tipoasiento']=='Apertura'){
                    $arrayPeriodos[$periodoAImputar]['apertura']+=$movimiento['debe'];
                    $arrayPeriodos[$periodoAImputar]['apertura']-=$movimiento['haber'];
                }else{
                    $arrayPeriodos[$periodoAImputar]['movimiento']+=$movimiento['debe'];
                    $arrayPeriodos[$periodoAImputar]['movimiento']-=$movimiento['haber'];
                }
                $arrayPeriodos[$periodoAImputar]['debes']+=$movimiento['debe'];
                $arrayPeriodos[$periodoAImputar]['haberes']+=$movimiento['haber'];
                
                $saldoCalculado += $movimiento['debe'];
                $saldoCalculado -= $movimiento['haber'];
            }
            //Saldos de cuentas esperados
            //1 +
            //2 -
            //3 +
            //4 +/-
            //5 +
            //6 -
            //Blanco saldo esperado
            //Naranja saldo fuera de contexto
            $charinicial = substr($cuentascliente['Cuenta']['numero'], 0, 1);
            $colorTR = "";
            switch ($charinicial){
                case "1":
                    if($saldoCalculado>=0){
                        $colorTR= "";
                    }else{
                        $colorTR= "#ffae00";
                    }
                    $arrayCuentasxPeriodos[$numerodecuenta]['activo']=$saldoCalculado;
                    break;
                case "2":
                    if($saldoCalculado<=0){
                        $colorTR= "";
                    }else{
                        $colorTR= "#ffae00";
                    }
                    $arrayCuentasxPeriodos[$numerodecuenta]['pasivo']=$saldoCalculado*-1;
                    break;    
                case "3":
                    if($saldoCalculado>=0){
                        $colorTR= "";
                    }else{
                        $colorTR= "#ffae00";
                    }
                    break;
                case "4":
                    $arrayCuentasxPeriodos[$numerodecuenta]['pasivo']=$saldoCalculado*-1;
                break;
                case "5":
                    if($saldoCalculado>=0){
                        $colorTR= "";
                    }else{
                        $colorTR= "#ffae00";
                    }
                    $arrayCuentasxPeriodos[$numerodecuenta]['perdida']=$saldoCalculado;                    
                    break;
                
                case "6":
                    if($saldoCalculado<=0){
                        $colorTR= "";
                    }else{
                        $colorTR= "#ffae00";
                    }
                    $arrayCuentasxPeriodos[$numerodecuenta]['ganancia']=$saldoCalculado*-1;                                        
                    break;
            }            
            ?>
            <tr class="trclickeable" cuecliid="<?php echo $cuentascliente['Cuentascliente']['id']?>" style="background-color: <?php echo $colorTR?>">
                <td>
                    <?php echo $cuentascliente['Cuenta']['numero']; ?>
                </td>
                <td>
                    <?php echo $cuentascliente['Cuentascliente']['nombre']; ?>
                </td>
                <?php
                if(!isset($arrayPeriodos[$periodoActual])){
                    $arrayPeriodos[$periodoActual]=[];
                    $arrayPeriodos[$periodoActual]['debes']=0;
                    $arrayPeriodos[$periodoActual]['haberes']=0;
                    $arrayPeriodos[$periodoActual]['apertura']=0;
                    $arrayPeriodos[$periodoActual]['movimiento']=0;                    
                }
                $saldo = $arrayPeriodos[$periodoActual]['debes']-$arrayPeriodos[$periodoActual]['haberes'];
                echo '<td  class="numericTD">'.
                    number_format($saldo, 2, ",", ".")
                    ."</td>";
                
                
                if(!isset($arrayCuentasxPeriodos[$numerodecuenta][$periodoActual])){
                    $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual]=0;
                    $arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual] = 0;
                    $arrayCuentasxPeriodos[$numerodecuenta]['movimiento'][$periodoActual] = 0;                       
                }
                $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual]=$saldo;
                $arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual]=$arrayPeriodos[$periodoActual]['apertura'];
                $arrayCuentasxPeriodos[$numerodecuenta]['movimiento'][$periodoActual]=$arrayPeriodos[$periodoActual]['movimiento'];                                         
                
                //aca le vamos a agregar los datos del bien de uso al $arrayCuentasxPeriodo porque si esta cuenta apunta a un bien de uso 
                //desues vamos a necesitar los datos del BDU para mostrarlos
                //aunque si no esta relacionado a un BDU entonces vamos a mostrar loq ue se pueda y hacer los calculos q se puedan
                if(count($cuentascliente['Cuentaclientevalororigen'])>0){
                    $arrayCuentasxPeriodos[$numerodecuenta]['biendeuso']=$cuentascliente['Cuentaclientevalororigen'][0];
                    $arrayCuentasxPeriodos[$numerodecuenta]['valororigen']=$cuentascliente['Cuentaclientevalororigen'][0]['valororiginal'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['porcentajeamortizacion']=$cuentascliente['Cuentaclientevalororigen'][0]['porcentajeamortizacion'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada']=$cuentascliente['Cuentaclientevalororigen'][0]['amortizacionacumulada'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['periodo']=$cuentascliente['Cuentaclientevalororigen'][0]['periodo'];                
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionejercicio']=$cuentascliente['Cuentaclientevalororigen'][0]['importeamorteizaciondelperiodo']+
                            $cuentascliente['Cuentaclientevalororigen'][0]['importeamortizacionaceleradadelperiodo'];                
                }
                if(count($cuentascliente['Cuentaclienteactualizacion'])>0){
                    $arrayCuentasxPeriodos[$numerodecuenta]['biendeuso']=$cuentascliente['Cuentaclienteactualizacion'][0];
                    $arrayCuentasxPeriodos[$numerodecuenta]['valororigen']=$cuentascliente['Cuentaclienteactualizacion'][0]['valororiginal'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['porcentajeamortizacion']=$cuentascliente['Cuentaclienteactualizacion'][0]['porcentajeamortizacion'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada']=$cuentascliente['Cuentaclienteactualizacion'][0]['amortizacionacumulada'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['periodo']=$cuentascliente['Cuentaclienteactualizacion'][0]['periodo'];                
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionejercicio']=$cuentascliente['Cuentaclienteactualizacion'][0]['importeamorteizaciondelperiodo']+
                            $cuentascliente['Cuentaclienteactualizacion'][0]['importeamortizacionaceleradadelperiodo'];                
                }
                if(count($cuentascliente['Cuentaclienteterreno'])>0){
                    $arrayCuentasxPeriodos[$numerodecuenta]['biendeuso']=$cuentascliente['Cuentaclienteterreno'][0];
                    $arrayCuentasxPeriodos[$numerodecuenta]['valororigen']=$cuentascliente['Cuentaclienteterreno'][0]['valororiginal'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['porcentajeamortizacion']=$cuentascliente['Cuentaclienteterreno'][0]['porcentajeamortizacion'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada']=$cuentascliente['Cuentaclienteterreno'][0]['amortizacionacumulada'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['periodo']=$cuentascliente['Cuentaclienteterreno'][0]['periodo'];                
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionejercicio']=$cuentascliente['Cuentaclienteterreno'][0]['importeamorteizaciondelperiodo']+
                            $cuentascliente['Cuentaclienteterreno'][0]['importeamortizacionaceleradadelperiodo'];                
                }
                if(count($cuentascliente['Cuentaclienteedificacion'])>0){
                    $arrayCuentasxPeriodos[$numerodecuenta]['biendeuso']=$cuentascliente['Cuentaclienteedificacion'][0];
                    $arrayCuentasxPeriodos[$numerodecuenta]['valororigen']=$cuentascliente['Cuentaclienteedificacion'][0]['valororiginal'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['porcentajeamortizacion']=$cuentascliente['Cuentaclienteedificacion'][0]['porcentajeamortizacion'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada']=$cuentascliente['Cuentaclienteedificacion'][0]['amortizacionacumulada'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['periodo']=$cuentascliente['Cuentaclienteedificacion'][0]['periodo'];                
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionejercicio']=$cuentascliente['Cuentaclienteedificacion'][0]['importeamorteizaciondelperiodo']+
                            $cuentascliente['Cuentaclienteedificacion'][0]['importeamortizacionaceleradadelperiodo'];                
                }
                if(count($cuentascliente['Cuentaclientemejora'])>0){
                    $arrayCuentasxPeriodos[$numerodecuenta]['biendeuso']=$cuentascliente['Cuentaclientemejora'][0];
                    $arrayCuentasxPeriodos[$numerodecuenta]['valororigen']=$cuentascliente['Cuentaclientemejora'][0]['valororiginal'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['porcentajeamortizacion']=$cuentascliente['Cuentaclientemejora'][0]['porcentajeamortizacion'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada']=$cuentascliente['Cuentaclientemejora'][0]['amortizacionacumulada'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['periodo']=$cuentascliente['Cuentaclientemejora'][0]['periodo'];                
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionejercicio']=$cuentascliente['Cuentaclientemejora'][0]['importeamorteizaciondelperiodo']+
                            $cuentascliente['Cuentaclientemejora'][0]['importeamortizacionaceleradadelperiodo'];                
                }
                if(count($cuentascliente['Cbu'])>0){
                $arrayCuentasxPeriodos[$numerodecuenta]['Cbu']=$cuentascliente['Cbu'][0];
                }
                ?>
            </tr>
            <?php
        }
        ?>
        </tbody>
        <tfoot>
            <tr class="trnoclickeable">
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
<?php
$keysCuentas = array_keys($arrayCuentasxPeriodos);
?>
<div  style="width:100%;height: 1px; page-break-before:always"></div>
<div class="index estadocontable" id="divPrimeraCategoria" >
    <table class="toExcelTable tbl_border tblEstadoContable splitForPrint">
        <thead>
            <tr>
                <td>
                    Contribuyente <?php echo $cliente["Cliente"]['nombre'] ?>
                </td>
                <td>
                    Año Fiscal <?php echo $periodoActual ?>
                </td>               
            </tr>
        </thead>
        <tbody>
            
            <?php
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'60201','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'60202','Que Generan mov. de fondos');            
            $columnas=['Ingresos',"Gravados",'Que Generan mov. de fondos','Que no generan mov. de fondos','Exentos, no grav. o no comput'];
            $totalIngresos1ra = mostrarIngresos1ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,"Ingresos 1&#176;",$columnas,"Gravados",2,
                    $fechaInicioConsulta,$fechaFinConsulta,null);
            
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'510001001','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'510002001','Que no generan mov. de fondos');
            $columnas=['Gastos',"Deducibles",'Que Generan mov. de fondos','Que no generan mov. de fondos','Amortiz.','No deducibles'];

            $totalGastos1ra = mostrarIngresos1ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,"Gastos 1&#176;",$columnas,"Deducibles",3,
                    $fechaInicioConsulta,$fechaInicioConsulta,$fechaFinConsulta,$totalIngresos1ra);
            
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'60301','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'60302','Que Generan mov. de fondos');
            $columnas=['Ingresos','Instrumentos Fcieros. deriv.','Vts. acciones etc.',"Otros ing. gravados",'Que Generan mov. de fondos','Que no generan mov. de fondos','No computab.','Exentos, no grav. o no grav.'];
            $totalIngresos2da = mostrarIngresos1ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,"Ingresos 2&#176;",$columnas,"Otros ing. gravados",2,
                    $fechaInicioConsulta,$fechaFinConsulta,null);
           
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'511001','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'5110021','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'5110022','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'5110023','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'5110024','Que no generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'5110025','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'511003','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'511004','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'511005','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'511006','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'511007','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'511008','Que Generan mov. de fondos');
            $columnas=['Gastos','Instrumentos Fcieros. deriv.','Vts. acciones etc.',"Otros conceptos deducibles",'Que Generan mov. de fondos','Que no generan mov. de fondos','Amortiz.','No deducibles'];            
            $totalGastos2da = mostrarIngresos1ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,"Gastos 2&#176;",$columnas,"Otros ing. gravados",3,
                    $fechaInicioConsulta,$fechaFinConsulta,$totalIngresos2da);
            
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'60401','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'60402','Que Generan mov. de fondos');
            $columnas=['Ingresos','Gravados','Que Generan mov. de fondos','Que no generan mov. de fondos','No computab.','Exentos'];            
            $totalIngresos3ra = mostrarIngresos1ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,"Ingresos 3&#176;",$columnas,"Gravados",2,
                    $fechaInicioConsulta,$fechaFinConsulta,null);

            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'512001','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'512002001','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'512002002','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'512002003','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'512002004','Que no generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'512003','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'512004','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'512005','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'512006','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'512007','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'512008','Que Generan mov. de fondos');            
            $columnas=['Gastos','Deducibles','Que Generan mov. de fondos','Que no generan mov. de fondos','Amortiz.','No deducibles'];            
            $totalGastos3ra = mostrarIngresos1ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,"Gastos 3&#176;",$columnas,"Deducibles",3,
                    $fechaInicioConsulta,$fechaFinConsulta,$totalIngresos3ra);
            
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'6010','Que Generan mov. de fondos');
            $columnas=['Ingresos','Gravados','Que Generan mov. de fondos','Que no generan mov. de fondos','No computab.','Exentos o no gravados'];            
            $totalIngresos3raEmpresa = mostrarIngresos1ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,"Ingresos 3&#176;(EU)",$columnas,"Gravados",2,
                    $fechaInicioConsulta,$fechaFinConsulta,null);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'5010','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'5020','Que no generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'5030','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'50401','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'50402','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'50403','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'50404','Amortiz.');
            initializeConcepto($arrayPrefijos,'50405','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'50406','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'50407','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'50408','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'50409','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'50410','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'50411','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'50412','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'50413','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'50499','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'5050','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'5060','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'5070','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'50800','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'50801','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'50802','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'50803','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'5090','Que Generan mov. de fondos');
            $columnas=['Compras y Gastos','Costo de bienes de cambio','Gastos deducibles','Que Generan mov. de fondos','Que no generan mov. de fondos','Amortiz.','No deducibles'];            
            $totalGastos3raEmpresa = mostrarIngresos1ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,"Gastos 3&#176;(EU)",$columnas,"Gravados",3,
                    $fechaInicioConsulta,$fechaFinConsulta,$totalIngresos3raEmpresa);
            
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'60501','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'60502','Que Generan mov. de fondos');
            $columnas=['Ingresos','Que Generan mov. de fondos','Que no generan mov. de fondos','No computab.','Exentos o no gravados'];            
            $totalIngresos4ta = mostrarIngresos1ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,"Ingresos 4&#176;",$columnas,"Gravados",2,
                    $fechaInicioConsulta,$fechaFinConsulta,null);
           
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'513000001','Que Generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'513000002','Que no generan mov. de fondos');
            initializeConcepto($arrayPrefijos,'51300100','Amortiz.');
            initializeConcepto($arrayPrefijos,'51300200','Amortiz.');
            initializeConcepto($arrayPrefijos,'51300300','Amortiz.');
            initializeConcepto($arrayPrefijos,'51300400','Amortiz.');
            initializeConcepto($arrayPrefijos,'51300500','Amortiz.');
            initializeConcepto($arrayPrefijos,'51300600','Amortiz.');
            $columnas=['Gastos','Que Generan mov. de fondos','Que no generan mov. de fondos','Amortiz.','No deducibles'];            
            $totalGastos4ta = mostrarIngresos1ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,"Gastos 4&#176;",$columnas,"Deducibles",3,
                    $fechaInicioConsulta,$fechaFinConsulta,$totalIngresos4ta);
            ?>
        </tbody>
    </table>        
</div>
<div  style="width:100%;height: 1px; page-break-before:always"></div>
<div class="index estadocontable" id="divPatrimonioTercera" >
    <table class="toExcelTable tbl_border tblEstadoContable splitForPrint">
        <thead>
            <tr>
                <td>
                    Contribuyente <?php echo $cliente["Cliente"]['nombre'] ?>
                </td>
                <td>
                    Año Fiscal <?php echo $periodoActual ?>
                </td>               
            </tr>
        </thead>
        <tbody>    
            <tr>
                <th colspan="4"></th>
                <th colspan="2">Ganancias</th>
            </tr>
            <tr>
                <th colspan="4">Detalle del capital afectado a la actividad</th>
                <th colspan="">Inicial</th>
                <th colspan="">Final</th>
            </tr>
            <tr class="trTitle">
                <th colspan="4">Activo</th>
                <th colspan=""></th>
                <th colspan=""></th>
            </tr>
            <?php
            $arrayPrefijos=[];
            $totalActivos=[];
            $totalPasivos=[];
            initializeConcepto($arrayPrefijos,'1101','');
            $disponibilidades = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Disponibilidades',$fechaInicioConsulta,$fechaFinConsulta,$totalActivos);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1103','');
            initializeConcepto($arrayPrefijos,'1104','');
            $creditos = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Creditos EU',$fechaInicioConsulta,$fechaFinConsulta,$totalActivos);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1105','');
            $bienesdecambio = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Bienes de Cambio',$fechaInicioConsulta,$fechaFinConsulta,$totalActivos);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1102','');
            initializeConcepto($arrayPrefijos,'1202','');
            $Inversiones = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Inversiones',$fechaInicioConsulta,$fechaFinConsulta,$totalActivos);
            //$arrayPrefijos=[];
            
            //$inmueblesedificado = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Inmuebles Edificado',$fechaInicioConsulta,$fechaFinConsulta,$totalActivos);
            //$arrayPrefijos=[];
           
            //$inmueblesmejora = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Inmuebles Mejora',$fechaInicioConsulta,$fechaFinConsulta,$totalActivos);
            
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'120601002','');
            initializeConcepto($arrayPrefijos,'120601006','');
            initializeConcepto($arrayPrefijos,'120601010','');
            initializeConcepto($arrayPrefijos,'120601014','');
            initializeConcepto($arrayPrefijos,'120601018','');
            initializeConcepto($arrayPrefijos,'120601022','');
            initializeConcepto($arrayPrefijos,'120601026','');
            initializeConcepto($arrayPrefijos,'120601030','');
            initializeConcepto($arrayPrefijos,'120601034','');
            initializeConcepto($arrayPrefijos,'120601038','');
            initializeConcepto($arrayPrefijos,'120601042','');
            initializeConcepto($arrayPrefijos,'120601046','');
            initializeConcepto($arrayPrefijos,'120601050','');
            initializeConcepto($arrayPrefijos,'120601054','');
            mostrarBienDeUsoTercera($arrayCuentasxPeriodos, $arrayPrefijos, $keysCuentas, 'Inmuebles Edificado',$fechaInicioConsulta, $fechaFinConsulta,$totalActivos);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'120601003','');
            initializeConcepto($arrayPrefijos,'120601007','');
            initializeConcepto($arrayPrefijos,'120601011','');
            initializeConcepto($arrayPrefijos,'120601015','');
            initializeConcepto($arrayPrefijos,'120601019','');
            initializeConcepto($arrayPrefijos,'120601023','');
            initializeConcepto($arrayPrefijos,'120601027','');
            initializeConcepto($arrayPrefijos,'120601031','');
            initializeConcepto($arrayPrefijos,'120601035','');
            initializeConcepto($arrayPrefijos,'120601039','');
            initializeConcepto($arrayPrefijos,'120601043','');
            initializeConcepto($arrayPrefijos,'120601047','');
            initializeConcepto($arrayPrefijos,'120601051','');
            initializeConcepto($arrayPrefijos,'120601055','');
            mostrarBienDeUsoTercera($arrayCuentasxPeriodos, $arrayPrefijos, $keysCuentas, 'Inmuebles Mejora',$fechaInicioConsulta, $fechaFinConsulta,$totalActivos);
            
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1206020','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            //$rodados = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Rodados',$fechaInicioConsulta,$fechaFinConsulta,$totalActivos);
            mostrarBienDeUsoTercera($arrayCuentasxPeriodos, $arrayPrefijos, $keysCuentas, 'Rodados',$fechaInicioConsulta, $fechaFinConsulta,$totalActivos);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1206030','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            //$instalaciones = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Instalaciones',$fechaInicioConsulta,$fechaFinConsulta,$totalActivos);
             mostrarBienDeUsoTercera($arrayCuentasxPeriodos, $arrayPrefijos, $keysCuentas, 'Instalaciones',$fechaInicioConsulta, $fechaFinConsulta,$totalActivos);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1206040','');
            initializeConcepto($arrayPrefijos,'1206050','');
            initializeConcepto($arrayPrefijos,'1206060','');
            initializeConcepto($arrayPrefijos,'1206070','');
            initializeConcepto($arrayPrefijos,'1206080','');
            initializeConcepto($arrayPrefijos,'1209000','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            //$otrosbienesdeuso = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Otros bienes de uso',$fechaInicioConsulta,$fechaFinConsulta,$totalActivos);
            mostrarBienDeUsoTercera($arrayCuentasxPeriodos, $arrayPrefijos, $keysCuentas, 'Otros bienes de uso',$fechaInicioConsulta, $fechaFinConsulta,$totalActivos);
             $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'120800','');
            initializeConcepto($arrayPrefijos,'1210000','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $bienesintangibles = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Bienes Intangibles',$fechaInicioConsulta,$fechaFinConsulta,$totalActivos);
            ?>
            <tr>
                <td colspan="4">Total del Activo</td>
                <td> <?php echo number_format($totalActivos['apertura'], 2, ",", ".")?></td>
                <td> <?php echo number_format($totalActivos[$periodoActual], 2, ",", ".")?></td>
            </tr>
            <tr class="trTitle">
                <th colspan="4">Pasivo</th>
                <th colspan=""></th>
                <th colspan=""></th>
            </tr>
            <?php
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'2101001','');
            initializeConcepto($arrayPrefijos,'2101002','');
            initializeConcepto($arrayPrefijos,'2201001','');
            initializeConcepto($arrayPrefijos,'2201002','');
            $deudascomerciales = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Deudas Comerciales',$fechaInicioConsulta,$fechaFinConsulta,$totalPasivos);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'21021','');
            initializeConcepto($arrayPrefijos,'21022','');
            initializeConcepto($arrayPrefijos,'21023','');
            initializeConcepto($arrayPrefijos,'22021','');
            initializeConcepto($arrayPrefijos,'22022','');
            initializeConcepto($arrayPrefijos,'22023','');
            $deudasfinancieras = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Deudas Financieras',$fechaInicioConsulta,$fechaFinConsulta,$totalPasivos);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'2103','');
            initializeConcepto($arrayPrefijos,'2203','');
            $deudassociales = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Deudas Sociales',$fechaInicioConsulta,$fechaFinConsulta,$totalPasivos);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'2104','');
            initializeConcepto($arrayPrefijos,'2204','');
            $deudasfiscales = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Deudas Fiscales',$fechaInicioConsulta,$fechaFinConsulta,$totalPasivos);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'2107','');
            initializeConcepto($arrayPrefijos,'2207','');
            $otrasdeudas = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Otras Deudas',$fechaInicioConsulta,$fechaFinConsulta,$totalPasivos);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'2108','');
            initializeConcepto($arrayPrefijos,'2208','');
            $previsiones = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Previsiones',$fechaInicioConsulta,$fechaFinConsulta,$totalPasivos);
            ?>
            <tr>
                <td colspan="4">Total del Pasivo</td>
                <td> <?php echo number_format($totalPasivos['apertura'], 2, ",", ".")?></td>
                <td> <?php echo number_format($totalPasivos[$periodoActual], 2, ",", ".")?></td>
            </tr>
        </tbody>
    </table>
</div>
<div  style="width:100%;height: 1px; page-break-before:always"></div>
<div class="index estadocontable" id="divPatrimonio" >
    <table class="toExcelTable tbl_border tblEstadoContable splitForPrint">
        <thead>
            <tr>
                <td>
                    Contribuyente <?php echo $cliente["Cliente"]['nombre'] ?>
                </td>
                <td>
                    Año Fiscal <?php echo $periodoActual ?>
                </td>               
                <td>
                    Patrimonio
                </td>               
            </tr>
        </thead>
        <tbody>    
            <tr class="trTitle">
                <th colspan="1">Patrimonio en el pais</th>
            </tr>
            <?php
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301010','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $inmuebles = mostrarBienDeUso($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Inmuebles',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'130102','');
            $derechosreales = mostrarPatrimonio($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Derechos Reales',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301030','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $automotores = mostrarBienDeUso($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Automotores',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301040','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $navesyates = mostrarBienDeUso($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Naves, Yates y similares',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301050','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $aeronaves = mostrarBienDeUso($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Aeronave',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301060','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $empresas = mostrarPatrimonio($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Patrimonio de Empresas o Explotación Unipersonal',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301070','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $accionesconcotizacion = mostrarPatrimonio($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Acciones/ Fondos Comun Inv/Oblig. Negociable con Contizacion',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301080','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $accionessincontizacion = mostrarPatrimonio($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Acciones/ Cuotas/ Participaciones sociales sin cotizacion',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301090','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $titulossincotizacion = mostrarPatrimonio($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Titulos publicos y privados sin cotizacion',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301100','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $titulosconcotizacion = mostrarPatrimonio($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Titulos publicos y privados con cotizacion',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301110','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $creditospf = mostrarPatrimonio($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Creditos_PF',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301120','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $depositosdinero = mostrarPatrimonio($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Depositos en dinero',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301130','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $depositosefectivo = mostrarPatrimonio($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Depositos en dinero',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301140','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $bienesmueblesregistrables = mostrarBienDeUso($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Bienes Muebles Registrables',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301150','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $otrosbienes = mostrarBienDeUso($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Otros bienes',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);     
            ?>
            <tr class="trTitle">
                <th colspan="1">Subtotal patrimonio neto en el pais</th>
                <th colspan="1"><?php echo  number_format($totalPatrimonio['apertura'], 2, ",", ".")?></th>
                <th colspan="1"><?php echo number_format($totalPatrimonio[$periodoActual], 2, ",", ".")?></th>
            </tr>
            <tr class="trTitle">
                <th colspan="1">Deudas en el pais</th>
            </tr>
            <?php
            $totalDeudaPais=[];
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'230101','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $deudasenelpaisPH = mostrarPatrimonio($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Deudas en el Pais Persona Humana',$fechaInicioConsulta,$fechaFinConsulta,$totalDeudaPais);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'230102','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $deudasenelpaisPJ = mostrarPatrimonio($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Deudas en el Pais Persona juridica',$fechaInicioConsulta,$fechaFinConsulta,$totalDeudaPais);
            ?>
             <tr class="trTitle">
                <th colspan="1">Subtotal deudas en el pais</th>
                <th colspan="1"><?php echo  number_format($totalDeudaPais['apertura'], 2, ",", ".")?></th>
                <th colspan="1"><?php echo number_format($totalDeudaPais[$periodoActual], 2, ",", ".")?></th>
            </tr>
        </tbody>
    </table>
</div>
<div  style="width:100%;height: 1px; page-break-before:always"></div>
<?php
//ACA vamos a calcular los valores de la Ganancia de la Determinacion de GPF 
//para poder calcular los topes de ganancias
            $ingresosGravados=[];            
            $resultadoneto=[];            
            $ingresosGravados['primera']=(isset($totalIngresos1ra['Que Generan mov. de fondos']))?$totalIngresos1ra['Que Generan mov. de fondos']:0;
            $ingresosGravados['primera']+=(isset($totalIngresos1ra['Que no generan mov. de fondos']))?$totalIngresos1ra['Que no generan mov. de fondos']:0;
            $ingresosGravados['segunda']=(isset($totalIngresos2da['Que Generan mov. de fondos']))?$totalIngresos2da['Que Generan mov. de fondos']:0;
            $ingresosGravados['segunda']+=(isset($totalIngresos2da['Que no generan mov. de fondos']))?$totalIngresos2da['Que no generan mov. de fondos']:0;
            $ingresosGravados['segunda']+=(isset($totalIngresos2da['Vts. acciones etc.']))?$totalIngresos2da['Vts. acciones etc.']:0;
            $ingresosGravados['tercera']=(isset($totalIngresos1ra['Que Generan mov. de fondos']))?$totalIngresos1ra['Que Generan mov. de fondos']:0;
            $ingresosGravados['tercera']+=(isset($totalIngresos1ra['Que no generan mov. de fondos']))?$totalIngresos1ra['Que no generan mov. de fondos']:0;
            $ingresosGravados['cuarta']=(isset($totalIngresos4ta['Que Generan mov. de fondos']))?$totalIngresos4ta['Que Generan mov. de fondos']:0;
            $ingresosGravados['cuarta']+=(isset($totalIngresos4ta['Que no generan mov. de fondos']))?$totalIngresos4ta['Que no generan mov. de fondos']:0;
            $resultadoneto['primera']= $ingresosGravados['primera'];
            $resultadoneto['segunda']= $ingresosGravados['segunda'];
            $resultadoneto['tercera']= $ingresosGravados['tercera'];
            $resultadoneto['cuarta']= $ingresosGravados['cuarta'];
            $ingresoGravadoTotal = $ingresosGravados['primera']+$ingresosGravados['segunda']+$ingresosGravados['tercera']+$ingresosGravados['cuarta'];
            $participacionEnEmpresaTotal = $totalGastos3raEmpresa['resultadoneto'];
            $resultadoneto['tercera']+= $participacionEnEmpresaTotal;
            
            $egresosGravados=[];            
            $egresosGravados['primera']=(isset($totalGastos1ra['Que Generan mov. de fondos']))?$totalGastos1ra['Que Generan mov. de fondos']:0;
            $egresosGravados['segunda']=(isset($totalIngresos2da['Que Generan mov. de fondos']))?$totalIngresos2da['Que Generan mov. de fondos']:0;
            $egresosGravados['segunda']-=(isset($totalIngresos2da['Instrumentos Fcieros. deriv.']))?$totalIngresos2da['Instrumentos Fcieros. deriv.']:0;
            $egresosGravados['segunda']-=(isset($totalIngresos2da['Vts. acciones etc.']))?$totalIngresos2da['Vts. acciones etc.']:0;
            $egresosGravados['tercera']=(isset($totalGastos3ra['Que Generan mov. de fondos']))?$totalGastos3ra['Que Generan mov. de fondos']:0;
            $egresosGravados['cuarta']=(isset($totalGastos4ta['Que Generan mov. de fondos']))?$totalGastos4ta['Que Generan mov. de fondos']:0;
            $resultadoneto['primera']+= $egresosGravados['primera'];
            $resultadoneto['segunda']+= $egresosGravados['segunda'];
            $resultadoneto['tercera']+= $egresosGravados['tercera'];
            $resultadoneto['cuarta']+= $egresosGravados['cuarta'];
            $gastosGravadosTotal = $egresosGravados['primera']+$egresosGravados['segunda']+$egresosGravados['tercera']+$egresosGravados['cuarta'];
            $resultadoNetoTotal=$resultadoneto['primera']+$resultadoneto['segunda']+$resultadoneto['tercera']+$resultadoneto['cuarta'];
            $resultadoImpositivo=$resultadoNetoTotal;
            $resultadoFinal=$resultadoImpositivo;
            
            //Datos auxiliares para calculo tope deduccion especial
            $integracion4ta = 0;
            if($resultadoFinal>=($resultadoneto['cuarta']+$totalGastos3raEmpresa['resultadoneto'])){
                $integracion4ta=$resultadoneto['cuarta'];
            }else{
                if($resultadoFinal>=$resultadoneto['cuarta']){
                    $integracion4ta=$resultadoneto['cuarta'];
                }else{
                    $integracion4ta=$resultadoFinal;
                }
            }
            
            $integracion3raEmp = 0;
            if(($resultadoFinal-$integracion4ta)<=0){
                $integracion3raEmp = 0;
            }else{
                if(($resultadoFinal-$integracion4ta-$totalGastos3raEmpresa['resultadoneto'])<=0){
                    $integracion3raEmp=$resultadoFinal-$integracion4ta;
                }else{
                    $integracion3raEmp=$totalGastos3raEmpresa['resultadoneto'];
                }
            }
            $integracionResto = ($resultadoFinal-($integracion3raEmp+$integracion4ta))*($resultadoFinal>0?1:0);
?>
<?php
            $montoActualGanancias = 42318; //minimo no imponible
            $montoActualConyugue = 39778;
            $montoActualHijo = 19889;
            $montoActualOtrasCargas = 19889;
?>
<div class="index estadocontable" id="divDeduccionesGenerales" >
    <table class="toExcelTable tbl_border tblEstadoContable splitForPrint">
        <thead>
            <tr>
                <td>
                    Contribuyente <?php echo $cliente["Cliente"]['nombre'] ?>
                </td>
                <td>
                    Año Fiscal <?php echo $periodoActual ?>
                </td>               
                <td>
                    Asiento de Deducciones Generales
                </td>               
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4">
                    <div class="index">
                        <h3><?php echo __('Asiento de Deducciones Generales')?></h3>
                        <?php
                        $id = 0;
                        $peanioBDU = date('Y',strtotime($fechaInicioConsulta));
                        $nombre = "Deducciones Generales: 12-".$peanioBDU;
                        $descripcion = "Manual";
                        $fecha = date('t-12-Y', strtotime($fechaInicioConsulta));
                        $miAsiento=array();
                        if(!isset($miAsiento['Movimiento'])){
                            $miAsiento['Movimiento']=array();
                        }
                        if(isset($asientoDeduccionGeneral['Asiento']['id'])){
                            $miAsiento = $asientoDeduccionGeneral;
                            $id = $miAsiento['Asiento']['id'];
                            $nombre = $miAsiento['Asiento']['nombre'];
                            $descripcion = $miAsiento['Asiento']['descripcion'];
                            //$fecha = date('d-m-Y',strtotime($miAsiento['Asiento']['fecha']));
                        }

                        echo $this->Form->create('Asiento',['class'=>'formTareaCarga formAsiento','action'=>'add','style'=>' min-width: max-content;']);
                        echo $this->Form->input('Asiento.0.id',['default'=>$id]);
                        echo $this->Form->input('Asiento.0.nombre',['default'=>$nombre]);
                        echo $this->Form->input('Asiento.0.descripcion',['default'=>$descripcion]);
                        echo $this->Form->input('Asiento.0.fecha',['default'=>$fecha]);
                        echo $this->Form->input('Asiento.0.cliente_id',['default'=>$cliente["Cliente"]['id'],'type'=>'hidden']);
                        echo $this->Form->input('Asiento.0.periodo',['value'=>'12-'.$peanioBDU]);
                        echo $this->Form->input('Asiento.0.tipoasiento',['default'=>'Deduccion General','readonly'=>'readonly']);
                        /*1.Preguntar si existe la cuenta cliente que apunte a la cuenta 8(idDeCuenta) */
                        /*2. Si no existe se la crea y la traigo*/
                        /*3. Si existe la traigo*/
                        $i=0;
                        echo "</br>";
                        $cuentaclienteid = 0;
                        $resultadoNetoTotal5 = ($resultadoNetoTotal>0)?$resultadoNetoTotal*0.05:0;
                        $topeCuentaCliente=[
                            2773=>"996,31",
                            2774=>"996,31",
                            2775=>"996,31",
                            2776=>$montoActualGanancias,
                            2778=>$resultadoNetoTotal5,
                            2779=>$resultadoNetoTotal5,
                            2780=>$montoActualGanancias,
                            2782=>$resultadoNetoTotal5,
                            2783=>"20.000,",
                            2789=>$montoActualGanancias,
                            2789=>$montoActualGanancias,
                        ];
                        foreach ($asientoestandares as $asientoestandar) {
                            $cuentaclienteid = $asientoestandar['Cuenta']['Cuentascliente'][0]['id'];
                            if($asientoestandar['Cuenta']['id']=='5'){
                                if(!$tienetercera){
                                    continue;
                                }
                            }
                            if($asientoestandar['Cuenta']['id']=='1069'){
                                if($tienetercera){
                                    continue;
                                }
                            }
                            /*lo mismo que hicimos con el asiento vamos a hacer con los movimientos, si existe un movimiento
                                    con la cuentacliente_id que estamos queriendo armar rellenamos los datos*/
                            $movid=0;
                            $asiento_id=0;
                            $debe=0;
                            $haber=0;
                            $key=0;
                            
                            foreach ($miAsiento['Movimiento'] as $kMov => $movimiento){
                                if(!isset($miAsiento['Movimiento'][$kMov]['cargado'])) {
                                    $miAsiento['Movimiento'][$kMov]['cargado'] = false;
                                }
                                if($cuentaclienteid==$movimiento['cuentascliente_id']){

                                    $key=$kMov;
                                    $movid=$movimiento['id'];
                                    $asiento_id=$movimiento['asiento_id'];
                                    $debe=$movimiento['debe'];
                                    $haber=$movimiento['haber'];
                                    $miAsiento['Movimiento'][$kMov]['cargado']=true;
                                }
                            }
                            //este asiento estandar carece de esta cuenta para este cliente por lo que hay que agregarla
                            //echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.key',['default'=>$key]);
                            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.id',['default'=>$movid]);
                            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.asiento_id',['default'=>$asiento_id,'type'=>'hidden']);
                            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.fecha',['default'=>$fecha,'type'=>'hidden']);
                            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuenta_id',['readonly'=>'readonly','type'=>'hidden','orden'=>$i,'value'=>$asientoestandar['Cuenta']['id'],'id'=>'cuenta'.$asientoestandar['Cuenta']['id']]);
                            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuentascliente_id',[
                                'default'=>$cuentaclienteid,
                                'options'=>$allcuentasclientes,
                                'label'=>"&nbsp;",
                                ]);
                            $label="&nbsp;";
                            if(isset($topeCuentaCliente[$asientoestandar['Cuenta']['id']])){
                                $label="El tope de la cuenta es $".$topeCuentaCliente[$asientoestandar['Cuenta']['id']] ;
                            }
                            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe',[
                                'class'=>'inputDebe',
                                'default'=>number_format($debe, 2, ".", ""),
                                'label'=>$label,
                                ]);
                            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber',[
                                'class'=>'inputHaber',                                
                                'default'=>number_format($haber, 2, ".", ""),
                                'label'=>"&nbsp;",                                
                                ]);
                            echo "</br>";
                            $i++;
                        }
                        /*aca sucede que pueden haber movimientos extras para este asieto estandar, digamos agregados a mano
                        entonces tenemos que recorrer los movimientos y aquellos que esten marcados como cargado=false se deben mostrar*/
                        foreach ($miAsiento['Movimiento'] as $kMov => $movimiento){
                            $movid=0;
                            $asiento_id=0;
                            $debe=0;
                            $haber=0;
                            $cuentaclienteid=0;
                            if( $miAsiento['Movimiento'][$kMov]['cargado']==false){
                                $movid=$movimiento['id'];
                                $asiento_id=$movimiento['asiento_id'];
                                $debe=$movimiento['debe'];
                                $haber=$movimiento['haber'];
                                $cuentaclienteid=$movimiento['cuentascliente_id'];
                                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.id',['default'=>$movid]);
                                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.asiento_id',['default'=>$asiento_id,'type'=>'hidden']);
                                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.fecha',['default'=>$fecha,'type'=>'hidden']);
                                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuentascliente_id',[
                                    'default'=>$cuentaclienteid,
                                    'options'=>$allcuentasclientes,
                                    'label'=>"&nbsp;",                                   
                                    ]);
                                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe',[
                                    'default'=>number_format($debe, 2, ".", ""),
                                    'label'=>"&nbsp;", 
                                    ]);
                                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber',[
                                    'default'=>number_format($haber, 2, ".", ""),
                                    'label'=>"&nbsp;",                                     
                                    ]);
                                echo "</br>";
                                $i++;
                            }
                        }
                        echo $this->Form->end('Guardar asiento');
                        //Debugger::dump($miAsiento['Movimiento']);
                        $totalDebe=0;
                        $totalHaber=0;
                        echo $this->Form->label('','Total ',[
                            'style'=>"display: -webkit-inline-box;width:355px;"
                        ]);
                        ?>
                        <?php
                        echo $this->Form->label('',
                            "Debe: ",
                            [
                                'style'=>"display: inline;"
                            ]
                        );
                        echo $this->Form->label('lblTotalDebe',
                            "$".number_format($totalDebe, 2, ".", ""),
                            [
                                'id'=>'lblTotalDebe',
                                'style'=>"display: inline;"
                            ]
                        );
                        ?>
                        <?php
                        echo $this->Form->label('',
                            "Haber: ",
                            [
                                'style'=>"display: inline;"
                            ]
                        );
                        echo $this->Form->label('lblTotalHaber',
                            "$".number_format($totalHaber, 2, ".", ""),
                            [
                                'id'=>'lblTotalHaber',
                                'style'=>"display: inline;"
                            ]
                        );
                        ?>
                        <?php
                        if(number_format($totalDebe, 2, ".", "")==number_format($totalHaber, 2, ".", "")){
                                echo $this->Html->image('test-pass-icon.png',array(
                                                'id' => 'iconDebeHaber',
                                                'alt' => 'open',
                                                'class' => 'btn_exit',
                                                'title' => 'Debe igual al Haber diferencia: '.number_format(($totalDebe-$totalHaber), 2, ".", ""),
                                        )
                                );
                        }else{
                                echo $this->Html->image('test-fail-icon.png',array(
                                                'id' => 'iconDebeHaber',
                                                'alt' => 'open',
                                                'class' => 'btn_exit',
                                                'title' => 'Debe distinto al Haber diferencia: '.number_format(($totalDebe-$totalHaber), 2, ".", ""),
                                        )
                                );
                        }
                        ?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="toExcelTable tbl_border tblEstadoContable splitForPrint">
        <thead>
            <tr>
                <td>
                    Contribuyente <?php echo $cliente["Cliente"]['nombre'] ?>
                </td>
                <td>
                    Año Fiscal <?php echo $periodoActual ?>
                </td>               
                <td>
                    Deducciones Generales
                </td>               
            </tr>
        </thead>
        <tbody>    
            <tr class="trTitle">
                <th colspan="1"> Detalle </th>
                <th colspan="1"> Real </th>
                <th colspan="1"> Tope </th>
                <th colspan="1"> Computable </th>
                <th colspan="1"> No deducible </th>
            </tr>
            <?php
            $totalDeduccionesGenerales=[];
            
            $totalDeudasGenerales=[];
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'514000001','');
            $tope=996.31;
            $DeduccionSegurodevida = mostrarDeduccionGeneral($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Seguro de vida',$tope,$fechaInicioConsulta,$fechaFinConsulta,$totalDeudasGenerales);

            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'514000002','');
            $tope=996.31;
            $DeduccionSepelio = mostrarDeduccionGeneral($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Gastos de sepelio',$tope,$fechaInicioConsulta,$fechaFinConsulta,$totalDeudasGenerales);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'514000003','');
            $tope=996.31;
            $DeduccionObraSocial = mostrarDeduccionGeneral($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Aporte a Obras Sociales',$tope,$fechaInicioConsulta,$fechaFinConsulta,$totalDeudasGenerales);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'514000004','');
            initializeConcepto($arrayPrefijos,'514000017','');
            $tope=$montoActualGanancias ;
            $DeduccionServicioDomestico = mostrarDeduccionGeneral($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Servicio Dom&eacute;stico',$tope,$fechaInicioConsulta,$fechaFinConsulta,$totalDeudasGenerales);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'514000008','');
            $tope=$montoActualGanancias ;
            $DeduccionAportes = mostrarDeduccionGeneral($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Aportes al R&eacute;gimen de Trabajadores Aut&oacute;nomos',$tope,$fechaInicioConsulta,$fechaFinConsulta,$totalDeudasGenerales);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'514000009','');
            initializeConcepto($arrayPrefijos,'514000005','');
            initializeConcepto($arrayPrefijos,'514000014','');
            initializeConcepto($arrayPrefijos,'514000015','');
            $tope=0 ;
            $DeduccionotrosFondos = mostrarDeduccionGeneral($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Otros fondeos de jubilaciones, pensiones, retiros o subsidios',$tope,$fechaInicioConsulta,$fechaFinConsulta,$totalDeudasGenerales);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'514000011','');
            $tope=20000 ;
            $DeduccionIntereses = mostrarDeduccionGeneral($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Intereses cr&eacute;dito hipotecario para adquisici&oacute;n o construcci&oacute;n casa habitaci&oacute;n',$tope,$fechaInicioConsulta,$fechaFinConsulta,$totalDeudasGenerales);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'514000012','');
            $tope=0 ;
            $DeduccionAportesSociedades = mostrarDeduccionGeneral($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Aportes a sociedades de garant&iacute;a reciproca',$tope,$fechaInicioConsulta,$fechaFinConsulta,$totalDeudasGenerales);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'514000013','');
            $tope=0 ;
            $DeduccionOtras = mostrarDeduccionGeneral($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Otras',$tope,$fechaInicioConsulta,$fechaFinConsulta,$totalDeudasGenerales);
            ?>
            <tr>
                <td>Subtotal Deducciones Generales, Excepto: Donaciones, cuota m&eacute;dico-asistencial y honorarios m&eacute;d.</td>
                <?php
                    echo '<td  class="numericTD tdborder" style="width:90px">'. number_format($totalDeudasGenerales['real'], 2, ",", ".").'</td>';
                    echo '<td  class="numericTD tdborder" style="width:90px"> </td>';
                    echo '<td  class="numericTD tdborder" style="width:90px">'. number_format($totalDeudasGenerales['computable'], 2, ",", ".").'</td>';
                    echo '<td  class="numericTD tdborder" style="width:90px">'. number_format($totalDeudasGenerales['nodeducible'], 2, ",", ".").'</td>';
                ?>
            </tr>
            <tr>
                <th>Deducciones generales con Tope del 5% de la ganancia neta antes de estas deducciones:(donaciones, cuota-m&eacute;dico-asistencial y honorarios m&eacute;d.)</th>
                <th colspan="1"> Real </th>
                <th colspan="1"> Tope </th>
                <th colspan="1"> Computable </th>
                <th colspan="1"> No deducible </th>
            </tr>
            <?php
            $totalDeudasGenerales5=[];
            $tope5 = $resultadoNetoTotal5;
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'514000006','');
            $tope=$tope5 ;
            $DonacionesOtras = mostrarDeduccionGeneral($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Donaciones',$tope,$fechaInicioConsulta,$fechaFinConsulta,$totalDeudasGenerales5);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'514000007','');
            $tope=$tope5 ;
            $DonacionesOtras = mostrarDeduccionGeneral($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Cuota m&eacute;dico-asistencial',$tope,$fechaInicioConsulta,$fechaFinConsulta,$totalDeudasGenerales5);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'514000010','');
            $tope=$tope5 ;
            $DonacionesOtras = mostrarDeduccionGeneral($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Honorarios por servicios de asistencia sanitaria, m&eacute;dica y param&eacute;dica',$tope,$fechaInicioConsulta,$fechaFinConsulta,$totalDeudasGenerales5);
            ?>
            <tr>
                <td>Subtotal Deducciones Generales  con tope del 5% de la ganancia neta antes de estas deducciones</td>
                <?php
                    echo '<td  class="numericTD tdborder" style="width:90px">'. number_format($totalDeudasGenerales5['real'], 2, ",", ".").'</td>';
                    echo '<td  class="numericTD tdborder" style="width:90px"> </td>';
                    echo '<td  class="numericTD tdborder" style="width:90px">'. number_format($totalDeudasGenerales5['computable'], 2, ",", ".").'</td>';
                    echo '<td  class="numericTD tdborder" style="width:90px">'. number_format($totalDeudasGenerales5['nodeducible'], 2, ",", ".").'</td>';
                ?>
            </tr>
            <tr>
                <td>Total Deducciones Generales</td>
                <?php
                    echo '<td  class="numericTD tdborder" style="width:90px">'. number_format($totalDeudasGenerales['real']+$totalDeudasGenerales5['real'], 2, ",", ".").'</td>';
                    echo '<td  class="numericTD tdborder" style="width:90px"> </td>';
                    echo '<td  class="numericTD tdborder" style="width:90px">'. number_format($totalDeudasGenerales['computable']+$totalDeudasGenerales5['computable'], 2, ",", ".").'</td>';
                    echo '<td  class="numericTD tdborder" style="width:90px">'. number_format($totalDeudasGenerales['nodeducible']+$totalDeudasGenerales5['nodeducible'], 2, ",", ".").'</td>';
                ?>
            </tr>
        </tbody>
    </table>
</div>
<div  style="width:100%;height: 1px; page-break-before:always"></div>
<div class="index estadocontable" id="divDeduccionesPersonales" >
    <table class="toExcelTable tbl_border tblEstadoContable splitForPrint">
        <thead>
            <tr>
                <td>
                    Contribuyente <?php echo $cliente["Cliente"]['nombre'] ?>
                </td>
                <td>
                    Año Fiscal <?php echo $periodoActual ?>
                </td>               
                <td>
                    Deducciones Personales
                </td>               
            </tr>
        </thead>
        <tbody>    
            <tr class="trTitle">
                <th colspan="1"> Detalle </th>
                <th colspan="1"> Si/No </th>
                <th colspan="1"> Importe </th>
                <th colspan="1"> Cantidad x Importe </th>
                <th colspan="1"> Deducible </th>
            </tr>
             <?php 
           
            //Siempre se aplica el Minimo no imponible
            $tieneGananciaNoImponible=true;
            
           
                $gananciaNoImponible = $montoActualGanancias;
            ?>
            <tr>
                 <td>Ganancia no imponible</td>              
                <td> </td>
                <td><?php echo number_format($montoActualGanancias, 2, ",", ".")?></td>
                <td><?php echo number_format($gananciaNoImponible, 2, ",", ".")?></td>
                <td><?php 
                    $gananciaNoImponibleDeducible=0;
                    if($gananciaNoImponible>$resultadoFinal){
                        if($resultadoFinal<=0){
                            $gananciaNoImponibleDeducible=0;
                        }else{
                            $gananciaNoImponibleDeducible=$resultadoFinal;
                        }
                    }else{
                        $gananciaNoImponibleDeducible=$gananciaNoImponible;
                    }
                echo number_format($gananciaNoImponibleDeducible, 2, ",", ".")?></td>
            </tr>
            <?php 
           
            $deduccionEspecialDeducible=0;
           
            $tieneDeduccionEspecial=false;
            foreach ($cliente['Impcli'][0]['Deduccione'] as $kded => $deduccion) {
                if(
                        $deduccion['clase']=='Deduccion especial'||
                        $deduccion['clase']=='Deduccion especial simple'||$deduccion['clase']=='Deduccion especial incrementada'){
                    $tieneDeduccionEspecial=true;
                }
            }
            $montoDeduccionEspecialSimple = $montoActualGanancias*($tieneDeduccionEspecial?1:0);   
            $montoDeduccionEspecialIncrementada = $montoActualGanancias*($tieneDeduccionEspecial?1:0)*4.8;
            if($resultadoFinal<=0){
                $deduccionEspecialDeducible=0;
            }else{
                if($integracion4ta>=$montoDeduccionEspecialIncrementada){
                    $deduccionEspecialDeducible=$montoDeduccionEspecialIncrementada;
                }else{
                    if($integracion4ta>=$montoDeduccionEspecialSimple){
                        $deduccionEspecialDeducible=$integracion4ta;
                    }else{
                        if($integracion4ta+$integracion3raEmp>=$montoDeduccionEspecialSimple){
                            $deduccionEspecialDeducible=$montoDeduccionEspecialSimple;
                        }else{
                            $deduccionEspecialDeducible=$integracion4ta+$integracion3raEmp;
                        } 
                    } 
                }
            }
           
            ?>
            <tr>
                <td>Deducci&oacute;n especial</td>              
                <td><?php echo $tieneDeduccionEspecial?'SI':'NO';?></td>
                <td></td>
                <td></td>
                <td><?php echo number_format($deduccionEspecialDeducible, 2, ",", ".")?></td>
            </tr>
            <?php
            if($tieneDeduccionEspecial){
            ?>
            <tr>
                <td>Simple</td>                
                <td> </td>
                <td><?php echo number_format($montoDeduccionEspecialSimple, 2, ",", ".")?></td>
                <td><?php echo number_format($montoDeduccionEspecialSimple, 2, ",", ".")?></td>
                <td> </td>
            </tr>
            <tr>
                <td>Incrementada</td>
                <td> </td>
                <td><?php echo number_format($montoDeduccionEspecialIncrementada, 2, ",", ".")?></td>
                <td><?php echo number_format($montoDeduccionEspecialIncrementada, 2, ",", ".")?></td>
                <td> </td>
            </tr>
            <?php
            }
            $tieneConyugue=false;
            $cantidadConyugue=0;
            foreach ($cliente['Impcli'][0]['Deduccione'] as $kded => $deduccion) {
                if($deduccion['clase']=='Conyuge'){
                    $tieneConyugue=true;
                    $cantidadConyugue++;
                }
            }
            $totalConyugue=$montoActualConyugue*$cantidadConyugue;
            $totalConyugueDeducible=0;
            if($tieneConyugue){
            ?>
            <tr>
                <td>Conyugue</td>                
                <td><?php echo $cantidadConyugue?></td>
                <td><?php echo number_format($montoActualConyugue, 2, ",", ".")?></td>
                <td><?php echo number_format($totalConyugue, 2, ",", ".")?></td>
                <td><?php
                    if($resultadoFinal<=0){
                        $totalConyugueDeducible=0;
                    }else{
                        if(($resultadoFinal-$deduccionEspecialDeducible)>=$totalConyugue){
                            $totalConyugueDeducible=$totalConyugue;
                        }else{
                            $totalConyugueDeducible=$resultadoFinal-$deduccionEspecialDeducible;
                        }
                    }
                    echo number_format($totalConyugueDeducible, 2, ",", ".")
                ?></td>
            </tr>            
            <?php
            }
            $tieneHijos=false;
            $cantidadHijos=0;
            foreach ($cliente['Impcli'][0]['Deduccione'] as $kded => $deduccion) {
                if($deduccion['clase']=='Hijos'){
                    $tieneHijos=true;
                    $cantidadHijos++;
                }
            }
            $totalHijos=$montoActualHijo*$cantidadHijos;
            $totalHijosDeducible=0;
             if($tieneHijos){
            ?>
            <tr>
                <td>Hijos</td>                
                <td><?php echo $cantidadHijos?></td>
                <td><?php echo number_format($montoActualHijo, 2, ",", ".")?></td>
                <td><?php echo number_format($totalHijos, 2, ",", ".")?></td>
                <td><?php
                    if($resultadoFinal<=0){
                        $totalHijosDeducible=0;
                    }else{
                        if(($resultadoFinal-$deduccionEspecialDeducible-$totalConyugueDeducible)>=$totalHijos){
                            $totalHijosDeducible=$totalHijos;
                        }else{
                            $totalHijosDeducible=$resultadoFinal-$deduccionEspecialDeducible-$totalConyugueDeducible;
                        }
                    }
                    echo number_format($totalHijosDeducible, 2, ",", ".")
                ?></td>
            </tr>            
            <?php
            }
            $tieneOtrasCargas=false;
            $cantidadOtrasCargas=0;
            foreach ($cliente['Impcli'][0]['Deduccione'] as $kded => $deduccion) {
                if($deduccion['clase']=='Otras Cargas'){
                    $tieneOtrasCargas=true;
                    $cantidadOtrasCargas++;
                }
            }
            $totalOtrasCargas=$montoActualOtrasCargas*$cantidadOtrasCargas;
             $totalOtrasCargasDeducible=0;
             if($tieneOtrasCargas){
            ?>
            <tr>
                <td>Otras Cargas</td>                
                <td><?php echo $cantidadHijos?></td>
                <td><?php echo number_format($montoActualOtrasCargas, 2, ",", ".")?></td>
                <td><?php echo number_format($totalOtrasCargas, 2, ",", ".")?></td>
                <td><?php
                    if($resultadoFinal<=0){
                        $totalOtrasCargasDeducible=0;
                    }else{
                        if(($resultadoFinal-$deduccionEspecialDeducible-$totalConyugueDeducible-$totalHijosDeducible)>=$totalOtrasCargas){
                            $totalOtrasCargasDeducible=$totalOtrasCargas;
                        }else{
                            $totalOtrasCargasDeducible=$resultadoFinal-$deduccionEspecialDeducible-$totalConyugueDeducible-$totalHijosDeducible;
                        }
                    }
                    echo number_format($totalOtrasCargasDeducible, 2, ",", ".")
                ?> </td>
            </tr>            
            <?php
            }
            ?>
            <tr>
                <td colspan="4">Total de Deducciones Personales Computables</td>                
                <td><?php
                    $totalDeduccionesPersonalesComputables=
                            $gananciaNoImponibleDeducible+
                            $deduccionEspecialDeducible+
                            $totalConyugueDeducible+
                            $totalHijosDeducible+
                            $totalOtrasCargasDeducible;                    
                    echo number_format($totalDeduccionesPersonalesComputables, 2, ",", ".")
                ?> </td>
            </tr>            
        </tbody>
    </table>
</div>
<div  style="width:100%;height: 1px; page-break-before:always"></div>
<div class="index estadocontable" id="divDeterminacionGPF" >
    <table class="toExcelTable tbl_border tblEstadoContable splitForPrint">
        <thead>
            <tr>
                <td>
                    Contribuyente <?php echo $cliente["Cliente"]['nombre'] ?>
                </td>
                <td>
                    Año Fiscal <?php echo $periodoActual ?>
                </td>               
                <td colspan="6">
                    Determinación Ganancia Neta Sujeta a Impuesto, Impuesto y Saldo de DDJJ
                </td>               
            </tr>
        </thead>
        <tbody>    
            <tr class="trTitle">
                <th colspan="3">  </th>
                <th colspan="1"> Total </th>
                <th colspan="1"> 1&#176; Categoria </th>
                <th colspan="1"> 2&#176; Categoria </th>
                <th colspan="1"> 3&#176; Categoria </th>
                <th colspan="1"> 4&#176; Categoria </th>
            </tr>
            <tr class="trTitle">
                <th colspan="3"> Determinación del resultado de las cuatro categorías </th>
                <th colspan="1">  </th>
                <th colspan="1">  </th>
                <th colspan="1">  </th>
                <th colspan="1">  </th>
                <th colspan="1">  </th>
            </tr>
            <tr class="trTitle">
                <th colspan="1">  </th>                
                <th colspan="2"> Resultado de fuente argentina </th>
                <th colspan="1">  </th>
                <th colspan="1">  </th>
                <th colspan="1">  </th>
                <th colspan="1">  </th>
                <th colspan="1">  </th>
            </tr>
          
            <tr class="">
                <th colspan="1">  </th>
                <th colspan="1">  </th>
                <td colspan="1"> Total de ingresos gravados </td>
                <td colspan="1"> <?php
                echo number_format($ingresoGravadoTotal, 2, ",", ".")?></td>
                <td colspan="1"> <?php echo number_format($ingresosGravados['primera'], 2, ",", ".")?> </td>
                <td colspan="1"> <?php echo number_format($ingresosGravados['segunda'], 2, ",", ".")?> </td>
                <td colspan="1"> <?php echo number_format($ingresosGravados['tercera'], 2, ",", ".")?> </td>
                <td colspan="1"> <?php echo number_format($ingresosGravados['cuarta'], 2, ",", ".")?> </td>
            </tr>
            <tr class="">
                <th colspan="1">  </th>
                <th colspan="1">  </th>
                <td colspan="1"> Total de participaci&oacute;n en empresas</td>
                <td colspan="1"> <?php
                echo number_format($participacionEnEmpresaTotal, 2, ",", ".")?></td>
                <td colspan="1"> <?php echo number_format(0, 2, ",", ".")?> </td>
                <td colspan="1"> <?php echo number_format(0, 2, ",", ".")?> </td>
                <td colspan="1"> <?php echo number_format($participacionEnEmpresaTotal, 2, ",", ".")?> </td>
                <td colspan="1"> <?php echo number_format(0, 2, ",", ".")?> </td>
            </tr>
            <?php
            
          
            ?>
            <tr class="">
                <th colspan="1">  </th>
                <th colspan="1">  </th>
                <td colspan="1"> Gastos y deducciones especialmente admitidos que consumen fondos </td>
                <td colspan="1"> <?php
                echo number_format($gastosGravadosTotal, 2, ",", ".")?></td>
                <td colspan="1"> <?php echo number_format($egresosGravados['primera'], 2, ",", ".")?> </td>
                <td colspan="1"> <?php echo number_format($egresosGravados['segunda'], 2, ",", ".")?> </td>
                <td colspan="1"> <?php echo number_format($egresosGravados['tercera'], 2, ",", ".")?> </td>
                <td colspan="1"> <?php echo number_format($egresosGravados['cuarta'], 2, ",", ".")?> </td>
            </tr>
            <tr class="">
                <th colspan="1"> </th>
                <th colspan="1"> </th>
                <td colspan="1"> Deducciones admitidas (que no consumen fondos) </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
            </tr>
            <tr class="">
                <th colspan="1"> </th>
                <th colspan="1"> </th>
                <td colspan="1"> Resultado Neto </td>
                <td colspan="1"> <?php 
                                    echo number_format($resultadoNetoTotal, 2, ",", ".")?></td>
                <td colspan="1"> <?php echo number_format($resultadoneto['primera'], 2, ",", ".")?></td>
                <td colspan="1"> <?php echo number_format($resultadoneto['segunda'], 2, ",", ".")?> </td>
                <td colspan="1"> <?php echo number_format($resultadoneto['tercera'], 2, ",", ".")?></td>
                <td colspan="1"> <?php echo number_format($resultadoneto['cuarta'], 2, ",", ".")?></td>
            </tr>
            <tr class="">
                <th colspan="1"> </th>
                <th colspan="1"> </th>
                <td colspan="1"> Quebranto especifico por instrumentos financieros derivados </td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
            </tr>
            <tr class="">
                <th colspan="1"> </th>
                <th colspan="1"> </th>
                <td colspan="1"> Quebranto específico por acciones </td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
            </tr>
            <tr class="">
                <th colspan="1">  </th>                
                <th colspan="2"> Resultado neto de fuente argentina </th>
                <td colspan="1"> <?php echo number_format($resultadoNetoTotal, 2, ",", ".")?></td>
                <td colspan="1"> <?php echo number_format($resultadoneto['primera'], 2, ",", ".")?></td>
                <td colspan="1"> <?php echo number_format($resultadoneto['segunda'], 2, ",", ".")?> </td>
                <td colspan="1"> <?php echo number_format($resultadoneto['tercera'], 2, ",", ".")?></td>
                <td colspan="1"> <?php echo number_format($resultadoneto['cuarta'], 2, ",", ".")?></td>
            </tr>
            <tr class="trTitle">
                <th colspan="3"> Ganancia (Quebranto) neto total </th>                
                <td colspan="1"> <?php echo number_format($resultadoNetoTotal, 2, ",", ".")?></td>
                <td colspan="1"> <?php echo number_format($resultadoneto['primera'], 2, ",", ".")?></td>
                <td colspan="1"> <?php echo number_format($resultadoneto['segunda'], 2, ",", ".")?> </td>
                <td colspan="1"> <?php echo number_format($resultadoneto['tercera'], 2, ",", ".")?></td>
                <td colspan="1"> <?php echo number_format($resultadoneto['cuarta'], 2, ",", ".")?></td>
            </tr>
        </tbody>
    </table>
    <table class="toExcelTable tbl_border tblEstadoContable splitForPrint">
        <tbody>
            <tr class="trTitle">
                <td colspan="2">Determinacion de la ganancia neta sujeta a impuesto del impuesto</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>Ganancia(quebranto) neto total</td>
                <td><?php echo number_format($resultadoNetoTotal, 2, ",", ".")?></td>
            </tr>
            <tr>
                <td></td>
                <td>Desgravaciones</td>
                <td><?php echo number_format($resultadoNetoTotal, 2, ",", ".")?></td>
            </tr>
            <tr>
                <td></td>
                <td>Deducciones Generales</br>Excepto tope 5%</td>
                <td><?php echo number_format($totalDeudasGenerales['computable']*-1, 2, ",", ".")?></td>
            </tr>
            <tr>
                <td></td>
                <td>Subtotal</td>
                <td><?php 
                $resultadoNetoTotal-=$totalDeudasGenerales['computable'];
                echo number_format($resultadoNetoTotal, 2, ",", ".")?></td>
            </tr>
            <tr>
                <td></td>
                <td>Ded. Grales. con tope 5% sobre el Subtotal anterior</td>
                <td><?php 
                $resultadoNetoTotal-=$totalDeudasGenerales5['computable'];
                echo number_format($totalDeudasGenerales5['computable']*-1, 2, ",", ".")?></td>
            </tr>
            <tr>
                <td></td>
                <td>Resultado Impositivo</td>
                <td><?php 
                
                echo number_format($resultadoNetoTotal, 2, ",", ".")?></td>
            </tr>
            <tr>
                <td></td>
                <td>Quebrantos de ejercicios anteriores, computables</td>
                <td><?php echo number_format(0, 2, ",", ".")?></td>
            </tr>
            <tr>
                <td></td>
                <td>Resultado Final</td>
                <td><?php echo number_format($resultadoNetoTotal, 2, ",", ".")?></td>
            </tr>
            <tr>
                <td></td>
                <td>Deducciones Personales</td>
                <td><?php echo number_format($totalDeduccionesPersonalesComputables*-1, 2, ",", ".")?></td>
            </tr>
            <tr class="trTitle">
                <td></td>
                <td>Ganancia neta sujeta a impuesto</td>
                <td><?php 
                    $ganancianetasujetaaimpuesto = 0;
                    if(($resultadoNetoTotal+$totalDeduccionesPersonalesComputables)>0){
                        $ganancianetasujetaaimpuesto = $resultadoNetoTotal-$totalDeduccionesPersonalesComputables;
                    }
                echo number_format($ganancianetasujetaaimpuesto, 2, ",", ".")?></td>
            </tr>
        </tbody>
    </table>
    <table class="toExcelTable tbl_border tblEstadoContable splitForPrint">
        <tbody>
            <tr>
                <td colspan="4">Datos Auxiliares para C&aacute;lculo tope deduccion especial</td>
            </tr>
            <tr>
                <td colspan="4">Integraci&oacute;n del resultado final</td>
            </tr>
            <tr>
                <td>Resto</td>
                <td>3&#176;Emp</td>
                <td>4&#176;</td>
                <td>4&#176;+3&#176;Emp</td>
            </tr>
            <tr>
                <td><?php echo number_format($integracionResto, 2, ",", ".")?></td>
                <td><?php echo number_format($integracion3raEmp, 2, ",", ".")?></td>
                <td><?php echo number_format($integracion4ta, 2, ",", ".")?></td>
                <td><?php echo number_format($integracion3raEmp+$integracion4ta, 2, ",", ".")?></td>
            </tr>           
        </tbody>
    </table>
</div>
<div  style="width:100%;height: 1px; page-break-before:always"></div>
<div class="index estadocontable" id="divJustificacionVarPat" >
    <table class="toExcelTable tbl_border tblEstadoContable splitForPrint">
        <thead>
            <tr>
                <td>
                    Contribuyente <?php echo $cliente["Cliente"]['nombre'] ?>
                </td>
                <td>
                    Año Fiscal <?php echo $periodoActual ?>
                </td>               
                <td>
                    Justificacion de las variaciones patrimoniales
                </td>               
            </tr>
        </thead>
        <tbody>    
            <tr class="trTitle">
                <th colspan="3">  </th>
                <th colspan="1"> Columna I </th>
                <th colspan="1"> Columna II </th>
                <th colspan="1"> </th>
                <th colspan="1"> 1&#176; Cat.</th>
                <th colspan="1"> 2&#176; Cat.</th>
                <th colspan="1"> 3&#176; Cat.(Resto)</th>
                <th colspan="1"> 3&#176; Cat.(Empresas)</th>
                <th colspan="1"> 4&#176; Cat.</th>
                <th colspan="1"> Otros ingresos no grav. o gastos no deduc.</th>
                <th colspan="1"> Deducciones generales</th>
            </tr>
            <tr class="">
                <th colspan="3"> Patrimonio Inicial </th>
                <th colspan="1">  </th>
                <th colspan="1"> <?php 
                $patrimonioInicial = $totalDeudaPais['apertura'];
                        echo number_format($totalDeudaPais['apertura'], 2, ",", ".")?> </th>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
            </tr>
            <tr class="">
                <th colspan="3"> Patrimonio Final </th>
                <th colspan="1"> <?php 
                $patrimonioFinal = $totalDeudaPais[$periodoActual];
                        echo number_format($patrimonioFinal, 2, ",", ".")?> </th>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
            </tr>
            <tr class="">
                <th colspan="3"> ResultadoImpositivo(quebranto Col I; ganancia Col II) </th>
                <th colspan="1"> <?php 
                if($resultadoImpositivo<0){echo number_format($resultadoImpositivo*-1, 2, ",", ".");}?> </th>
                 <th colspan="1"> <?php 
                if($resultadoImpositivo>=0){echo number_format($resultadoImpositivo, 2, ",", ".");}?> </th>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
            </tr>
            <tr class="">
                <th colspan="3">Otros conceptos que no justifican erog. o increm. patrimoniales </th>
                <th colspan="1">  </th>
                <th colspan="1"> </th>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
            </tr>
            <tr class="">
                <th colspan="1"> </th>
                <th colspan="2"> Ingresos </th>
                <?php 
                $ingresosPresuntos['primera']=(isset($totalIngresos1ra['Que no generan mov. de fondos']))?$totalIngresos1ra['Que no generan mov. de fondos']:0;
                $ingresosPresuntos['segunda']=(isset($totalIngresos2da['Que no generan mov. de fondos']))?$totalIngresos2da['Que no generan mov. de fondos']:0;
                $ingresosPresuntos['tercera']=(isset($totalIngresos3ra['Que no generan mov. de fondos']))?$totalIngresos3ra['Que no generan mov. de fondos']:0;
                $ingresosPresuntos['cuarta']=(isset($totalIngresos4ta['Que no generan mov. de fondos']))?$totalIngresos4ta['Que no generan mov. de fondos']:0;
                $ingresospresuntosTotal = $ingresosPresuntos['primera']+$ingresosPresuntos['segunda']+$ingresosPresuntos['tercera']+$ingresosPresuntos['cuarta'];
                ?>
                <th colspan="1"> <?php echo number_format($ingresospresuntosTotal, 2, ",", ".");?> </th>
                <td colspan="1"> </td>
                <td colspan="1"> </td>                
                <td colspan="1"><?php echo number_format( $ingresosPresuntos['primera'], 2, ",", ".");?> </td>
                <td colspan="1"><?php echo number_format( $ingresosPresuntos['segunda'], 2, ",", ".");?> </td>
                <td colspan="1"><?php echo number_format( $ingresosPresuntos['tercera'], 2, ",", ".");?> </td>
                <td colspan="1"> </td>
                <td colspan="1"><?php echo number_format( $ingresosPresuntos['cuarta'], 2, ",", ".");?> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
            </tr>
            <tr class="">
                <th colspan="1"> </th>
                <th colspan="2"> Amort. Acum. Bs. Vendidos </th>                
                <th colspan="1"> </th>
                <td colspan="1"> </td>
                <td colspan="1"> </td>                
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
            </tr>
            <tr class="">
                <th colspan="1"> </th>
                <th colspan="2"> Gastos no deducibles </th>
                <?php 
                $gastosNoDeducibles['primera']=(isset($totalGastos1ra['No deducibles']))?$totalGastos1ra['No deducibles']:0;
                $gastosNoDeducibles['segunda']=(isset($totalGastos2da['No deducibles']))?$totalGastos2da['No deducibles']:0;
                $gastosNoDeducibles['tercera']=(isset($totalGastos3ra['No deducibles']))?$totalGastos3ra['No deducibles']:0;
                $gastosNoDeducibles['terceraEmpresa']=(isset($totalGastos3raEmpresa['No deducibles']))?$totalGastos3raEmpresa['No deducibles']:0;
                $gastosNoDeducibles['cuarta']=(isset($totalGastos4ta['Que no generan mov. de fondos']))?$totalGastos4ta['Que no generan mov. de fondos']:0;
                $gastosNoDeduciblesTotal = $gastosNoDeducibles['primera']+$gastosNoDeducibles['segunda']
                        +$gastosNoDeducibles['tercera']+$gastosNoDeducibles['terceraEmpresa']+$gastosNoDeducibles['cuarta'];
                ?>
                <th colspan="1"> <?php echo number_format($gastosNoDeduciblesTotal, 2, ",", ".");?> </th>
                <td colspan="1"> </td>
                <td colspan="1"> </td>                
                <td colspan="1"><?php echo number_format( $gastosNoDeducibles['primera'], 2, ",", ".");?> </td>
                <td colspan="1"><?php echo number_format( $gastosNoDeducibles['segunda'], 2, ",", ".");?> </td>
                <td colspan="1"><?php echo number_format( $gastosNoDeducibles['tercera'], 2, ",", ".");?> </td>
                <td colspan="1"><?php echo number_format( $gastosNoDeducibles['terceraEmpresa'], 2, ",", ".");?> </td>
                <td colspan="1"><?php echo number_format( $gastosNoDeducibles['cuarta'], 2, ",", ".");?> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
            </tr>
            <tr class="">
                <th colspan="3">Conceptos que justifican erog. o increm. patrimoniales </th>
                <th colspan="1">  </th>
                <th colspan="1"> </th>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
            </tr>
            <tr class="">
                <th colspan="1"> </th>
                <th colspan="2"> Ganancias exentas o no gravadas o no computables </th>
                &
                <?php 
                $gananciasexentas['primera']=(isset($totalIngresos1ra['Exentos, no grav. o no comput']))?$totalIngresos1ra['Exentos, no grav. o no comput']:0;
                $gananciasexentas['segunda']=(isset($totalIngresos2da['No computab.']))?$totalIngresos2da['No computab.']:0;
                $gananciasexentas['segunda']+=(isset($totalIngresos2da['Exentos, no grav. o no grav.']))?$totalIngresos2da['Exentos, no grav. o no grav.']:0;
                $gananciasexentas['tercera']=(isset($totalIngresos3ra['No computab.']))?$totalIngresos3ra['No computab.']:0;
                $gananciasexentas['tercera']+=(isset($totalIngresos3ra['Exentos']))?$totalIngresos3ra['Exentos']:0;
                $gananciasexentas['terceraEmpresa']=(isset($totalIngresos3raEmpresa['No computab.']))?$totalIngresos3raEmpresa['No computab.']:0;
                $gananciasexentas['terceraEmpresa']+=(isset($totalIngresos3raEmpresa['Exentos, no grav. o no grav.']))?$totalIngresos3raEmpresa['Exentos, no grav. o no grav.']:0;
                $gananciasexentas['cuarta']=(isset($totalIngresos3raEmpresa['No computab.']))?$totalIngresos3raEmpresa['No computab.']:0;
                $gananciasexentas['cuarta']+=(isset($totalIngresos4ta['No computab.']))?$totalIngresos4ta['No computab.']:0;
                $gananciasexentas['cuarta']=(isset($totalIngresos4ta['Exentos o no gravados']))?$totalIngresos4ta['Exentos o no gravados']:0;
                $gananciasexentasTotal = $gananciasexentas['primera']+$gananciasexentas['segunda']
                        +$gananciasexentas['tercera']+$gananciasexentas['terceraEmpresa']+$gananciasexentas['cuarta'];
                ?>
                <th colspan="1"> <?php echo number_format($gananciasexentasTotal, 2, ",", ".");?> </th>
                <td colspan="1"> </td>
                <td colspan="1"> </td>                
                <td colspan="1"><?php echo number_format( $gananciasexentas['primera'], 2, ",", ".");?> </td>
                <td colspan="1"><?php echo number_format( $gananciasexentas['segunda'], 2, ",", ".");?> </td>
                <td colspan="1"><?php echo number_format( $gananciasexentas['tercera'], 2, ",", ".");?> </td>
                <td colspan="1"><?php echo number_format( $gananciasexentas['terceraEmpresa'], 2, ",", ".");?> </td>
                <td colspan="1"><?php echo number_format( $gananciasexentas['cuarta'], 2, ",", ".");?> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
            </tr>
            <tr class="">
                <th colspan="1"> </th>
                <th colspan="2"> Bs. recibidos por herencia, legado o donaci&oacute;n </th>
                <th colspan="1"> </th>
                <td colspan="1"> </td>
                <td colspan="1"> </td>                
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
            </tr>
            <tr class="">
                <th colspan="1"> </th>
                <th colspan="2"> Gastos o deducciones que no implican erogaciones de fondos </th>
                <th colspan="1"> </th>
                <td colspan="1"> </td>
                <td colspan="1"> </td>                
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
            </tr>
            <tr class="">
                <th colspan="1"> </th>
                <th colspan="1"> </th>
                <th colspan="1"> Amortizaciones del ejercicio</th>
                <?php 
                $Amortizacionesdejejercicio['primera']=(isset($totalGastos1ra['Amortiz.']))?$totalGastos1ra['Amortiz.']:0;
                $Amortizacionesdejejercicio['segunda']=(isset($totalGastos2da['Amortiz.']))?$totalGastos2da['Amortiz.']:0;
                $Amortizacionesdejejercicio['tercera']=(isset($totalGastos3ra['Amortiz.']))?$totalGastos3ra['Amortiz.']:0;
                $Amortizacionesdejejercicio['terceraEmpresa']=(isset($totalGastos3raEmpresa['Amortiz.']))?$totalGastos3raEmpresa['Amortiz.']:0;
                $Amortizacionesdejejercicio['cuarta']=(isset($totalGastos4ta['Amortiz.']))?$totalGastos4ta['Amortiz.']:0;
                $AmortizacionesdejejercicioTotal = $Amortizacionesdejejercicio['primera']+$Amortizacionesdejejercicio['segunda']
                        +$Amortizacionesdejejercicio['tercera']+$Amortizacionesdejejercicio['terceraEmpresa']+$Amortizacionesdejejercicio['cuarta'];
                ?>
                <td colspan="1"><?php echo number_format( $AmortizacionesdejejercicioTotal, 2, ",", ".");?> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"><?php echo number_format( $Amortizacionesdejejercicio['primera'], 2, ",", ".");?> </td>
                <td colspan="1"><?php echo number_format( $Amortizacionesdejejercicio['segunda'], 2, ",", ".");?> </td>
                <td colspan="1"><?php echo number_format( $Amortizacionesdejejercicio['tercera'], 2, ",", ".");?> </td>
                <td colspan="1"><?php echo number_format( $Amortizacionesdejejercicio['terceraEmpresa'], 2, ",", ".");?> </td>
                <td colspan="1"><?php echo number_format( $Amortizacionesdejejercicio['cuarta'], 2, ",", ".");?> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
            </tr>
            <tr class="">
                <th colspan="1"> </th>
                <th colspan="1"> </th>
                <th colspan="1"> Gastos presuntos</th>
                <?php 
                $gastospresuntos['primera']=(isset($totalGastos1ra['Que no generan mov. de fondos']))?$totalGastos1ra['Que no generan mov. de fondos']:0;
                $gastospresuntos['segunda']=(isset($totalGastos2da['Que no generan mov. de fondos']))?$totalGastos2da['Que no generan mov. de fondos']:0;
                $gastospresuntos['tercera']=(isset($totalGastos3ra['Que no generan mov. de fondos']))?$totalGastos3ra['Que no generan mov. de fondos']:0;
                $gastospresuntos['terceraEmpresa']=(isset($totalGastos3raEmpresa['Que no generan mov. de fondos']))?$totalGastos3raEmpresa['Que no generan mov. de fondos']:0;
                $gastospresuntos['cuarta']=(isset($totalGastos4ta['Que no generan mov. de fondos']))?$totalGastos4ta['Que no generan mov. de fondos']:0;
                $gastospresuntosTotal = $gastospresuntos['primera']+$gastospresuntos['segunda']
                        +$gastospresuntos['tercera']+$gastospresuntos['terceraEmpresa']+$gastospresuntos['cuarta'];
                ?>
                <td colspan="1"><?php echo number_format( $gastospresuntosTotal, 2, ",", ".");?> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"><?php echo number_format( $gastospresuntos['primera'], 2, ",", ".");?> </td>
                <td colspan="1"><?php echo number_format( $gastospresuntos['segunda'], 2, ",", ".");?> </td>
                <td colspan="1"><?php echo number_format( $gastospresuntos['tercera'], 2, ",", ".");?> </td>
                <td colspan="1"><?php echo number_format( $gastospresuntos['terceraEmpresa'], 2, ",", ".");?> </td>
                <td colspan="1"><?php echo number_format( $gastospresuntos['cuarta'], 2, ",", ".");?> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
            </tr>
            <tr class="">
                <th colspan="1"> </th>
                <th colspan="1"> </th>
                <th colspan="1"> C&oacute;mputo quebrantos espec&iacute;ficos</th>                
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
            </tr>
            <tr class="">
                <th colspan="3">Subtotales</th>
                <?php
                $columnaItotal=$patrimonioFinal + (($resultadoImpositivo<0)?$resultadoImpositivo*-1:0)+$ingresospresuntosTotal+$gastosNoDeduciblesTotal;
                $columnaIItotal=$patrimonioInicial + (($resultadoImpositivo>=0)?$resultadoImpositivo:0)
                        +$gananciasexentasTotal+$AmortizacionesdejejercicioTotal+$gastospresuntosTotal;                
                ?>
                <th colspan="1"><?php echo number_format( $columnaItotal, 2, ",", ".");?></th>
                <th colspan="1"><?php echo number_format( $columnaIItotal, 2, ",", ".");?></th>                
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
                <td colspan="1"> </td>
            </tr>
            <?php
            
            ?>
        </tbody>
    </table>
</div>
<?php
function mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$nombreNota,$fechaInicioConsulta,$fechaFinConsulta,&$total){
    $mostrarTotal = false;
    $periodoActual = date('Y', strtotime($fechaInicioConsulta));
    $totalNota = [];
    $totalNota[$periodoActual]=0;    
    $totalNota['apertura']=0;   
    if (!isset($total[$periodoActual])){
        $total[$periodoActual]=0;    
        $total['apertura']=0;   
    }
    foreach ($arrayPrefijos as $prefijo => $valoresPrefijo) {       
        $numerofijo = $valoresPrefijo['prefijo'];
        $indexCuentasNumeroFijo = array_keys(
            array_filter(
                $keysCuentas,
                function($var) use ($numerofijo){
                    return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                }
            )
        );
        if(count($indexCuentasNumeroFijo)!=0){ 
            if(!$mostrarTotal){
                //vamos a mostrar las columnas
               
                 ?>
                <tr class="trnoclickeable trTitle">
                    <th colspan="20" class="tdnoborder "><?php echo $nombreNota ?></th>
                </tr>
                <?php
            }
            $mostrarTotal = true;
            foreach ($indexCuentasNumeroFijo as $index) {
                $numeroCuenta = $keysCuentas[$index];
                 //si tiene CBU vamos a mostrar los datos
                $datosCBU=" ";
                if(isset($arrayCuentasxPeriodos[$numeroCuenta]['Cbu'])){
                    $datosCBU.="Numero Cuenta:".$arrayCuentasxPeriodos[$numeroCuenta]['Cbu']['numerocuenta']."/ CBU:".$arrayCuentasxPeriodos[$numeroCuenta]['Cbu']['cbu']."/ ".$arrayCuentasxPeriodos[$numeroCuenta]['Cbu']['tipocuenta'];
                }
                ?>
                 <tr>
                    <td colspan="4"><?php echo $arrayCuentasxPeriodos[$numeroCuenta]['nombrecuenta'].$datosCBU;//aca tengo que agregar el CBU si existe ?></td>
                <?php                   
                    $charinicial = substr($numeroCuenta, 0, 1);        
                    $subtotal = $arrayCuentasxPeriodos[$numeroCuenta][$periodoActual];;
                    $subtotalapertura = $arrayCuentasxPeriodos[$numeroCuenta]['apertura'][$periodoActual];;
                    switch ($charinicial){
                        case "1":
                            $subtotal = $arrayCuentasxPeriodos[$numeroCuenta]['activo'];
                            break;
                        case "2":
                            $subtotal = $arrayCuentasxPeriodos[$numeroCuenta]['pasivo'];
                            break;    
                        case "3":
                            $subtotal = $arrayCuentasxPeriodos[$numeroCuenta][$periodoActual];
                            break;
                        case "4":
                            $subtotal = $arrayCuentasxPeriodos[$numeroCuenta]['pasivo'];
                        break;
                        case "5":
                            $subtotal = $arrayCuentasxPeriodos[$numeroCuenta]['perdida'];                    
                            break;
                        case "6":
                            $subtotal = $arrayCuentasxPeriodos[$numeroCuenta]['ganancia'];                                        
                            break;
                    }            
                    echo '<td  class="numericTD tdborder" style="width:90px">'. number_format($subtotalapertura, 2, ",", ".").'</td>';
                    echo '<td  class="numericTD tdborder" style="width:90px">'. number_format($subtotal, 2, ",", ".").'</td>';
                    $totalNota['apertura']+=$subtotalapertura;
                    $totalNota[$periodoActual]+=$subtotal;
                    $total['apertura']+=$subtotalapertura;
                    $total[$periodoActual]+=$subtotal;
                    ?>
                </tr>
               <?php
            }
        }
    }
    if($mostrarTotal){ ?>
        <tr class="trnoclickeable">
            <th colspan="4" class="tdnoborder">Subtotal</th>
            <th  class="numericTD tdborder" style="width:90px"><?php echo number_format($totalNota['apertura'], 2, ",", ".")?></td>
            <th  class="numericTD tdborder" style="width:90px"><?php echo number_format($totalNota[$periodoActual], 2, ",", ".")?></td>            
        </tr>
    <?php
    
    }
    return $totalNota;
}
function mostrarBienDeUsoTercera($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$nombrePrefijo,$fechaInicioConsulta,$fechaFinConsulta,&$total){
   
    $mostrarTotal = false;
            
    $totalNota = [];
    $totalNota['alinicio'] = 0;
    $totalNota['altas'] = 0;
    $totalNota['valorresidual'] = 0;
    $totalNota['apertura'] = 0;
    $totalNota['periodoActual'] = 0;    
    $totalNota['depreciacionalinicio'] = 0;    

    foreach ($arrayPrefijos as $valoresPrefijo) {                   
        $totalPrefijo = [];
        $totalPrefijo['alinicio'] = 0;
        $totalPrefijo['altas'] = 0;
        $totalPrefijo['valorresidual'] = 0;
        $totalPrefijo['apertura'] = 0;
        $totalPrefijo['periodoActual'] = 0;      
        $totalPrefijo['depreciacionalinicio'] = 0;       

        $numerofijo = $valoresPrefijo['prefijo'];
        $indexCuentasNumeroFijo = array_keys(
            array_filter(
                $keysCuentas,
                function($var) use ($numerofijo){
                    return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                }
            )
        );
        if(count($indexCuentasNumeroFijo)==0){ 
            continue;        
        }
        ?>
        <tr class="trnoclickeable">
            <th colspan="1"><?php echo $nombrePrefijo; ?></th>
            <th colspan="1">V/O</th>
            <th colspan="1">AAC</th>
            <th colspan="1">V/R</th>
            <th colspan="1"></th>
            <th colspan="1"></th>
            
            <?php
            switch ($nombrePrefijo) {
                case 'Inmuebles Edificado':
                case 'Inmuebles Mejora':                         					 	
                    ?>
                    <th colspan="">Fecha adq.</th>
                    <th colspan="">Tipo</th>
                    <th colspan="">Destino</th>
                    <th colspan="">Calle</th>
                    <th colspan="">Piso</th>
                    <th colspan="">Dpto.</th>
                    <th colspan="">Localidad</th>
                    <th colspan="">Cód. post.</th>
                    <th colspan="">Pcia.</th>
                    <th colspan="">Catastro</th>
                    <th colspan="">Pdo./Pda./Dig.</th>
                    <th colspan="">Amortización del periodo</th>
                    <th colspan="">Total de inmueble</th>
                    <?php
                    break;
                case 'Rodados':
                     ?>
                    <th colspan="">MM/AAAA de adq.</th>
                    <th colspan="">% titularidad</th>
                    <th colspan="">Marca</th>
                    <th colspan="">Modelo</th>
                    <th colspan="">Fábrica</th>
                    <th colspan="">Año de fabricación</th>
                    <th colspan="">Patente</th>
                    <th colspan="">Valor de tabla</th>
                    <th colspan="">¿Amortizado totalmente?</th>
                    <th colspan="">¿Bien afectado a 3&#176; categ.?</th>
                    <th colspan="">¿Bien afectado a 4&#176; categ.?</th>
                    <th colspan="">Amortización del periodo</th>
                    <?php
                    break;
                case 'Otros bienes de uso':
                     ?>
                    <th colspan="">Fecha adq.</th>
                    <th colspan="">Detalle</th>
                    <th colspan="">Marca</th>
                    <th colspan="">Amortizacion del periodo</th>                    
                    <?php
                    break;

                default:
                    break;
            }
        ?>
        </tr>
        <?php
        $titleRow = "";
        foreach ($indexCuentasNumeroFijo as $index) {
            $numerodecuenta = $keysCuentas[$index];
            $titleRow="Cuentas incluidas en las notas: ".$numerodecuenta."/";

            if(isset($arrayCuentasxPeriodos[$numerodecuenta]['periodo'])){
                $periodoBDU = $arrayCuentasxPeriodos[$numerodecuenta]['periodo'];  
            }else{
                $periodoBDU = date('m-Y', strtotime($fechaInicioConsulta));
            }

            $pemesBDU = substr($periodoBDU, 0, 2);
            $peanioBDU = substr($periodoBDU, 3);
            
            $cuatrimestre = 1;
            switch ($pemesBDU){
                case '1':
                case '2':
                case '3':
                case '4':
                    $cuatrimestre = 1;
                break;
                case '5':
                case '6':
                case '7':
                case '8':
                    $cuatrimestre = 2;
                break;
                case '9':
                case '10':
                case '11':
                case '12':
                    $cuatrimestre = 3;
                break;
            }    
            $periodoActual = date('Y', strtotime($fechaInicioConsulta));
            $valororigen =  $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual];                
            if(isset($arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada'])){
                $amortizacionacumulada =  $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada'];
                $porcentajeamortizacion =  $arrayCuentasxPeriodos[$numerodecuenta]['porcentajeamortizacion'];
                $amortizacionejercicio =  $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionejercicio'];
            }else{
                $amortizacionacumulada =  0;
                $porcentajeamortizacion =  0;
                $amortizacionejercicio =  0;
            }

            $subtotalAmortizacion = $amortizacionacumulada+$amortizacionejercicio;
            $valorresidual = $valororigen - $subtotalAmortizacion;
            $coefActualizacion = 1;//DE DONDE SALE ESTO???
            $amortizacionDeducible = $amortizacionejercicio * $coefActualizacion;
            $valorResidualImpositivo = $coefActualizacion * $valorresidual;

            $pemes = date('m', strtotime($fechaInicioConsulta));
            $peanio = date('Y', strtotime($fechaInicioConsulta));

            $alinicio = ($peanio==$peanioBDU)?0:$valororigen;
            $altas = ($peanio==$peanioBDU)?$valororigen:0;
            $transferencias=0;//DE DONDE SALE ESTO???
            $bajas=0;//DE DONDE SALE ESTO???
            $revaluo=0;//DE DONDE SALE ESTO???
            $desvalorizacion=0;//DE DONDE SALE ESTO???
            $recuperodesvalorizacion=0;//DE DONDE SALE ESTO???
            $alcierre= $alinicio+$altas-$bajas;

            $depreciacionalinicio = $amortizacionacumulada;                                
            $depreciacionbajas=0;//DE DONDE SALE ESTO???
            $depreciaciondelejercicio=$amortizacionejercicio;
            $depreciaciondesvalorizacion=0;//DE DONDE SALE ESTO???
            $depreciacionrecuperodesvalorizacion=0;//DE DONDE SALE ESTO???
            $depreciacionalcierre=$depreciacionalinicio-$depreciacionbajas+$depreciaciondelejercicio;

            $ejercicioActual = $alcierre + $depreciacionalcierre;
            $ejercicioAnterior = 0;
            
            $totalPrefijo['alinicio'] += $alinicio;
            $totalPrefijo['altas'] += $altas;
            $totalPrefijo['valorresidual'] += $valorresidual;      
            $totalPrefijo['depreciacionalinicio'] += $depreciacionalinicio;
            $totalPrefijo['apertura'] += $arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual];
            $totalPrefijo['periodoActual'] += $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual];
                      
            $totalNota['alinicio'] += $alinicio;
            $totalNota['altas'] += $altas;
            $totalNota['valorresidual'] += $valorresidual;
            $totalNota['depreciacionalinicio'] += $depreciacionalinicio;
            $totalNota['apertura'] += $arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual];
            $totalNota['periodoActual'] += $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual];
         
            ?>
            <tr>
                <td><?php echo $arrayCuentasxPeriodos[$numerodecuenta]['nombrecuenta'];?></td>
                <td><?php echo number_format($alinicio+$altas, 2, ",", ".") ?></td>
                <td><?php echo number_format($depreciacionalinicio, 2, ",", ".") ?></td>
                <td><?php echo number_format($valorresidual, 2, ",", ".") ?></td>
                <td><?php echo number_format($arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual], 2, ",", ".") ?></td>
                <td><?php echo number_format($arrayCuentasxPeriodos[$numerodecuenta][$periodoActual], 2, ",", ".") ?></td>
                <!--vamos a mostrar las cells extras de bien de uso-->
                <?php
                            
                if(!isset($arrayCuentasxPeriodos[$numerodecuenta]['biendeuso'])){
                      ?>
                    <td colspan ="13">Esta cuenta no tiene un bien de uso relacionado></td>
                    <?php
                }else{
                    $bienDeuso=$arrayCuentasxPeriodos[$numerodecuenta]['biendeuso'];
                    if($bienDeuso['tipo']=='Inmueble'){
                        ?>
                        <td><?php echo $bienDeuso['fechaadquisicion'];?></td>
                        <td><?php echo $bienDeuso['tipoinmueble'];?></td>
                        <td><?php echo $bienDeuso['destino'];?></td>
                        <td><?php echo $bienDeuso['calle'];?></td>
                        <td><?php echo $bienDeuso['piso'];?></td>
                        <td><?php echo $bienDeuso['depto'];?></td>
                        <td><?php echo $bienDeuso['Localidad']['nombre'];?></td>
                        <td><?php echo $bienDeuso['codigopostal'];?></td>
                        <td><?php echo $bienDeuso['Localidade']['Partido']['nombre'];?></td>
                        <td><?php echo $bienDeuso['catastro'];?></td>
                        <td><?php echo $bienDeuso['partido']."/".$bienDeuso['partida']."/".$bienDeuso['digito'];?></td>
                        <td><?php echo $bienDeuso['importeamorteizaciondelperiodo'];?></td>
                        <td><?php echo number_format($alinicio+$altas, 2, ",", ".") ?></td>
                        <?php
                    }else if($bienDeuso['tipo']=='Rodado'){
                        ?>
                        <td><?php echo $bienDeuso['fechaadquisicion'];?></td>
                        <td><?php echo $bienDeuso['titularidad'];?></td>
                        <td><?php echo $bienDeuso['Modelo']['Marca']['nombre'];?></td>
                        <td><?php echo $bienDeuso['Modelo']['nombre'];?></td>
                        <td><?php echo $bienDeuso['fabrica'];?></td>
                        <td><?php echo $bienDeuso['aniofabricacion'];?></td>
                        <td><?php echo $bienDeuso['patente'];?></td>
                        <td><?php echo $bienDeuso['valororiginal'];?></td>
                        <td><?php echo ($bienDeuso['valororiginal']*1==$bienDeuso['amortizacionacumulada']*1)?'SI':'NO';?></td>
                        <td><?php echo ($bienDeuso['bienafectadoatercera']*1==1)?'SI':'NO';?></td>
                        <td><?php echo ($bienDeuso['bienafectadoacuarta']*1==1)?'SI':'NO';?></td>
                        <td><?php echo $bienDeuso['importeamorteizaciondelperiodo'];?></td>
                        <td><?php echo number_format($alinicio+$altas, 2, ",", ".") ?></td>
                        <?php
                    }else if($bienDeuso['tipo']=='Instalaciones'){
                       
                    }else{
                          ?>
                        <td><?php echo $bienDeuso['fechaadquisicion'];?></td>
                        <td><?php echo $bienDeuso['descripcion'];?></td>
                        <td><?php echo $bienDeuso['Modelo']['nombre'];?></td>
                        <td><?php echo $bienDeuso['importeamorteizaciondelperiodo'];?></td>
                        <td><?php echo number_format($alinicio+$altas, 2, ",", ".") ?></td>
                        <?php
                    }
                }            
                
                ?>
            </tr>
            <?php
            if(!isset($total['apertura']))$total['apertura']=0;
            if(!isset($total[$periodoActual]))$total[$periodoActual]=0;
            $total['apertura']+=$arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual];
            $total[$periodoActual]+=$arrayCuentasxPeriodos[$numerodecuenta][$periodoActual];
        }
         ?>
            <tr>
                <th> Total <?php echo $nombrePrefijo;?></th>
                <td><?php echo number_format($totalPrefijo['alinicio']+$totalPrefijo['altas'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalPrefijo['depreciacionalinicio'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalPrefijo['valorresidual'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalPrefijo['apertura'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalPrefijo['periodoActual'], 2, ",", ".") ?></td>
            </tr>
           <?php
        
    }
    return $totalNota;
}

function mostrarBienDeUso($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$nombrePrefijo,$fechaInicioConsulta,$fechaFinConsulta,&$total){
    $mostrarTotal = false;
    $totalNota = [];
    $totalNota['alinicio'] = 0;
    $totalNota['altas'] = 0;
    $totalNota['valorresidual'] = 0;
    $totalNota['apertura'] = 0;
    $totalNota['periodoActual'] = 0;    
    $totalNota['depreciacionalinicio'] = 0;    

    foreach ($arrayPrefijos as $valoresPrefijo) {                   
        $totalPrefijo = [];
        $totalPrefijo['alinicio'] = 0;
        $totalPrefijo['altas'] = 0;
        $totalPrefijo['valorresidual'] = 0;
        $totalPrefijo['apertura'] = 0;
        $totalPrefijo['periodoActual'] = 0;      
        $totalPrefijo['depreciacionalinicio'] = 0;       

        $numerofijo = $valoresPrefijo['prefijo'];
        $indexCuentasNumeroFijo = array_keys(
            array_filter(
                $keysCuentas,
                function($var) use ($numerofijo){
                    return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                }
            )
        );
        if(count($indexCuentasNumeroFijo)==0){ 
            continue;        
        }
        ?>
        <tr class="trnoclickeable">
            <th colspan="1"><?php echo $nombrePrefijo; ?></th>
        </tr>
        <?php
        $titleRow = "";
        foreach ($indexCuentasNumeroFijo as $index) {
            $numerodecuenta = $keysCuentas[$index];
            $titleRow="Cuentas incluidas en las notas: ".$numerodecuenta."/";

            if(isset($arrayCuentasxPeriodos[$numerodecuenta]['periodo'])){
                $periodoBDU = $arrayCuentasxPeriodos[$numerodecuenta]['periodo'];  
            }else{
                $periodoBDU = date('m-Y', strtotime($fechaInicioConsulta));
            }

            $pemesBDU = substr($periodoBDU, 0, 2);
            $peanioBDU = substr($periodoBDU, 3);
            
            $cuatrimestre = 1;
            switch ($pemesBDU){
                case '1':
                case '2':
                case '3':
                case '4':
                    $cuatrimestre = 1;
                break;
                case '5':
                case '6':
                case '7':
                case '8':
                    $cuatrimestre = 2;
                break;
                case '9':
                case '10':
                case '11':
                case '12':
                    $cuatrimestre = 3;
                break;
            }    
            $periodoActual = date('Y', strtotime($fechaInicioConsulta));
            $valororigen =  $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual];                
            if(isset($arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada'])){
                $amortizacionacumulada =  $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada'];
                $porcentajeamortizacion =  $arrayCuentasxPeriodos[$numerodecuenta]['porcentajeamortizacion'];
                $amortizacionejercicio =  $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionejercicio'];
            }else{
                $amortizacionacumulada =  0;
                $porcentajeamortizacion =  0;
                $amortizacionejercicio =  0;
            }

            $subtotalAmortizacion = $amortizacionacumulada+$amortizacionejercicio;
            $valorresidual = $valororigen - $subtotalAmortizacion;
            $coefActualizacion = 1;//DE DONDE SALE ESTO???
            $amortizacionDeducible = $amortizacionejercicio * $coefActualizacion;
            $valorResidualImpositivo = $coefActualizacion * $valorresidual;

            $pemes = date('m', strtotime($fechaInicioConsulta));
            $peanio = date('Y', strtotime($fechaInicioConsulta));

            $alinicio = ($peanio==$peanioBDU)?0:$valororigen;
            $altas = ($peanio==$peanioBDU)?$valororigen:0;
            $transferencias=0;//DE DONDE SALE ESTO???
            $bajas=0;//DE DONDE SALE ESTO???
            $revaluo=0;//DE DONDE SALE ESTO???
            $desvalorizacion=0;//DE DONDE SALE ESTO???
            $recuperodesvalorizacion=0;//DE DONDE SALE ESTO???
            $alcierre= $alinicio+$altas-$bajas;

            $depreciacionalinicio = $amortizacionacumulada;                                
            $depreciacionbajas=0;//DE DONDE SALE ESTO???
            $depreciaciondelejercicio=$amortizacionejercicio;
            $depreciaciondesvalorizacion=0;//DE DONDE SALE ESTO???
            $depreciacionrecuperodesvalorizacion=0;//DE DONDE SALE ESTO???
            $depreciacionalcierre=$depreciacionalinicio-$depreciacionbajas+$depreciaciondelejercicio;

            $ejercicioActual = $alcierre + $depreciacionalcierre;
            $ejercicioAnterior = 0;
            
            $totalPrefijo['alinicio'] += $alinicio;
            $totalPrefijo['altas'] += $altas;
            $totalPrefijo['valorresidual'] += $valorresidual;      
            $totalPrefijo['depreciacionalinicio'] += $depreciacionalinicio;
            $totalPrefijo['apertura'] += $arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual];
            $totalPrefijo['periodoActual'] += $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual];
                      
            $totalNota['alinicio'] += $alinicio;
            $totalNota['altas'] += $altas;
            $totalNota['valorresidual'] += $valorresidual;
            $totalNota['depreciacionalinicio'] += $depreciacionalinicio;
            $totalNota['apertura'] += $arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual];
            $totalNota['periodoActual'] += $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual];
         
            ?>
            <tr>
                <td><?php echo $arrayCuentasxPeriodos[$numerodecuenta]['nombrecuenta'];?></td>
                <td><?php echo number_format($alinicio+$altas, 2, ",", ".") ?></td>
                <td><?php echo number_format($valorresidual, 2, ",", ".") ?></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <!--vamos a mostrar una nueva row con los datos extras del bien de uso-->
                <?php
                            
                if(!isset($arrayCuentasxPeriodos[$numerodecuenta]['biendeuso'])){
                      ?>
                    <td colspan ="13">Esta cuenta no tiene un bien de uso relacionado</td>
                    <?php
                }else{
                    $bienDeuso=$arrayCuentasxPeriodos[$numerodecuenta]['biendeuso'];
                    if($bienDeuso['tipo']=='Inmuebles'){
                        ?>
                        <td colspan="4">
                            <p>Fecha adq:<?php echo $bienDeuso['fechaadquisicion'];?> 
                             Tipo:<?php echo $bienDeuso['tipoinmueble'];?>
                             Destino:<?php echo $bienDeuso['destino'];?>
                             Calle:<?php echo $bienDeuso['calle'];?>
                             Piso:<?php echo $bienDeuso['piso'];?>
                             Dpto:<?php echo $bienDeuso['depto'];?>
                             Localidad:<?php echo $bienDeuso['Localidade']['nombre'];?>
                             Cod.Post:<?php echo $bienDeuso['codigopostal'];?>
                             Pcia:<?php echo $bienDeuso['Localidade']['Partido']['nombre'];?>
                             Catastro:<?php echo $bienDeuso['catastro'];?>
                             Titularidad%:<?php echo $bienDeuso['titularidad'];?>
                                Pdo./Pda./Dig:<?php echo $bienDeuso['partido']."/".$bienDeuso['partida']."/".$bienDeuso['digito'];?>
                            </p>
                        </td>
                        <?php
                    }else if($bienDeuso['tipo']=='Automotores'){
                        ?>
                        <td colspan="4"><p> 
                            Fecha adq:<?php echo $bienDeuso['fechaadquisicion'];?>
                            Titularidad:<?php echo $bienDeuso['titularidad'];?>
                            Modelo:<?php echo $bienDeuso['Modelo']['Marca']['nombre'];?>
                            Marca:<?php echo $bienDeuso['Modelo']['nombre'];?>
                            Fabrica:<?php echo $bienDeuso['fabrica'];?>
                            A&ncaron;o Fabricacion:<?php echo $bienDeuso['aniofabricacion'];?>
                            Patente:<?php echo $bienDeuso['patente'];?>
                            Valor de tabla(*): <?php echo $bienDeuso['valororiginal'];?>
                            ¿Amortizado totalmente?:<?php echo ($bienDeuso['valororiginal']*1==$bienDeuso['amortizacionacumulada']*1)?'SI':'NO';?>
                            ¿Bien afectado a 3&#176; categ.?:<?php echo ($bienDeuso['bienafectadoatercera']*1==1)?'SI':'NO';?>
                            ¿Bien afectado a 4&#176; categ.?:<?php echo ($bienDeuso['bienafectadoacuarta']*1==1)?'SI':'NO';?></p>
                        </td>
                        <?php
                    }else if($bienDeuso['tipo']=='Naves, Yates y similares'){
                        ?>
                        <td colspan="4"><p>
                            Fecha adq:<?php echo $bienDeuso['fechaadquisicion'];?>
                            Titularidad:<?php echo $bienDeuso['titularidad'];?>
                            Modelo:<?php echo $bienDeuso['Modelo']['Marca']['nombre'];?>
                            Marca:<?php echo $bienDeuso['Modelo']['nombre'];?>
                            Matricula:<?php echo $bienDeuso['matricula'];?>
                            A&ncaron;o Fabricacion:<?php echo $bienDeuso['aniofabricacion'];?>
                            ¿Bien afectado a 3&#176; categ.?:<?php echo ($bienDeuso['bienafectadoatercera']*1==1)?'SI':'NO';?>
                            ¿Bien afectado a 4&#176; categ.?:<?php echo ($bienDeuso['bienafectadoacuarta']*1==1)?'SI':'NO';?></p>
                        </td>
                        <?php
                    }else if($bienDeuso['tipo']=='Aeronave'){
                        ?>
                        <td colspan="4"><p>
                            Fecha adq:<?php echo $bienDeuso['fechaadquisicion'];?>
                            Titularidad:<?php echo $bienDeuso['titularidad'];?>
                            Modelo:<?php echo $bienDeuso['Modelo']['Marca']['nombre'];?>
                            Marca:<?php echo $bienDeuso['Modelo']['nombre'];?>
                            Matricula:<?php echo $bienDeuso['matricula'];?>
                            ¿Bien afectado a 3&#176; categ.?:<?php echo ($bienDeuso['bienafectadoatercera']*1==1)?'SI':'NO';?>
                            ¿Bien afectado a 4&#176; categ.?:<?php echo ($bienDeuso['bienafectadoacuarta']*1==1)?'SI':'NO';?></p>
                        </td>
                        <?php
                    }else if($bienDeuso['tipo']=='Instalaciones'){
                       
                    }else if(in_array ($bienDeuso['tipo'],['Bien mueble registrable','Otros bienes'])){
                          ?>
                        <td colspan="4"><p>
                        Fecha adq:<?php echo $bienDeuso['fechaadquisicion'];?>
                        Detalle:<?php echo $bienDeuso['detalle'];?></p></td>
                        <?php
                    }
                }            
                
                ?>
            </tr>
            <?php
            if(!isset($total['apertura']))$total['apertura']=0;
            if(!isset($total[$periodoActual]))$total[$periodoActual]=0;
            $total['apertura']+=$arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual];
            $total[$periodoActual]+=$arrayCuentasxPeriodos[$numerodecuenta][$periodoActual];
        }
         ?>
            <tr>
                <th> Total <?php echo $nombrePrefijo;?></th>
                <td><?php echo number_format($totalPrefijo['alinicio']+$totalPrefijo['altas'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalPrefijo['valorresidual'], 2, ",", ".") ?></td>
                <td></td>
                <td></td>
            </tr>
           <?php
        
    }
    return $totalNota;
}
function mostrarPatrimonio($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$nombrePrefijo,$fechaInicioConsulta,$fechaFinConsulta,&$total)    {
   
    $mostrarTotal = false;
    $periodoActual = date('Y', strtotime($fechaInicioConsulta));
    $totalNota = [];
    $totalNota['apertura'] = 0;
    $totalNota['periodoActual'] = 0;
    if(!isset($total['apertura']))$total['apertura']=0;
    if(!isset($total[$periodoActual]))$total[$periodoActual]=0;
    foreach ($arrayPrefijos as $valoresPrefijo) {                   
        $totalPrefijo = [];
        $totalPrefijo['apertura'] = 0;
        $totalPrefijo['periodoActual'] = 0;

        $numerofijo = $valoresPrefijo['prefijo'];
        $indexCuentasNumeroFijo = array_keys(
            array_filter(
                $keysCuentas,
                function($var) use ($numerofijo){
                    return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                }
            )
        );
        if(count($indexCuentasNumeroFijo)==0){ 
            continue;        
        }
        ?>
        <tr class="trnoclickeable">
            <th colspan="1"><?php echo $nombrePrefijo; ?></th>
        </tr>
        <?php
        $titleRow = "";
        foreach ($indexCuentasNumeroFijo as $index) {
            $numerodecuenta = $keysCuentas[$index];
            $titleRow="Cuentas incluidas en las notas: ".$numerodecuenta."/";

            
            $valororigen =  $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual];                
          

            $pemes = date('m', strtotime($fechaInicioConsulta));
            $peanio = date('Y', strtotime($fechaInicioConsulta));


            $totalPrefijo['apertura'] += $arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual];
            $totalPrefijo['periodoActual'] += $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual];
                      
            $totalNota['apertura'] += $arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual];
            $totalNota['periodoActual'] += $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual];
         
            ?>
            <tr>
                <td><?php echo $arrayCuentasxPeriodos[$numerodecuenta]['nombrecuenta'];?></td>
                <td><?php echo number_format($arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual], 2, ",", ".") ?></td>
                <td><?php echo number_format($arrayCuentasxPeriodos[$numerodecuenta][$periodoActual], 2, ",", ".") ?></td>
                <td></td>
                <td></td>
            </tr>
            <?php
           
            $total['apertura']+=$arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual];
            $total[$periodoActual]+=$arrayCuentasxPeriodos[$numerodecuenta][$periodoActual];
        }
         ?>
        <tr>
            <th> Total <?php echo $nombrePrefijo;?></th>
            <td><?php echo number_format($totalPrefijo['apertura'], 2, ",", ".") ?></td>
            <td><?php echo number_format($totalPrefijo['periodoActual'], 2, ",", ".") ?></td>
            <td></td>
            <td></td>
        </tr>
       <?php
        
    }
    return $totalNota;
}    
function mostrarIngresos1ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$nombreNota,$columnas,$nombreGastos,$colspanGastos,
        $fechaInicioConsulta,$fechaFinConsulta,$totalIngreso){
    //vamos a extender la funcionalidad de esta funcion para que abarque tmb mostrary no solo calcular
    //$numerofijo = "60101";
    //Una nota puede tener muchos prefijos y vamos a totalizar los prefijos por separado
    //y devolver el total de la nota.
    $mostrarTotal = false;
    $periodoActual = date('Y', strtotime($fechaInicioConsulta));
    $totalNota = [];
    $totalNota[$periodoActual]=0;
    foreach ($columnas as $columna) {
        if(!isset($totalNota[$columna]))$totalNota[$columna]=0;
    }
    $columnasAgrupadoras=[
        'Gravados','Deducibles','Otros ing. gravados','Otros conceptos deducibles',
        'Gastos deducibles'
    ];
    $columnasAgrupadas=[
        'Que Generan mov. de fondos','Que no generan mov. de fondos','Amortiz.'
    ];
    $columnasInicio=[
        'Ingresos','Compras y Gastos','Gastos'
    ];
    foreach ($arrayPrefijos as $prefijo => $valoresPrefijo) {       
        $numerofijo = $valoresPrefijo['prefijo'];
        $indexCuentasNumeroFijo = array_keys(
            array_filter(
                $keysCuentas,
                function($var) use ($numerofijo){
                    return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                }
            )
        );
        if(count($indexCuentasNumeroFijo)!=0){ 
            if(!$mostrarTotal){
                //vamos a mostrar las columnas
                 ?>
                <tr class="trnoclickeable trTitle">
                    <th colspan="20" class="tdnoborder "><?php echo $nombreNota; ?></th>
                </tr>
                <tr>
                <?php
                $columnas3ra=['Ingresos','Que Generan mov. de fondos','Que no generan mov. de fondos','No computab.','Exentos o no gravados'];            
                foreach ($columnas as $kc => $columna) {
                    if(in_array($columna,$columnasAgrupadoras)){
                        echo '<td colspan="'.$colspanGastos.'">'.$columna.'</td>';
                    } else {
                        if(in_array($columna,$columnasAgrupadas))continue;
                        echo '<td rowspan="2">'.$columna.'</td>';                    
                    }
                }
                ?>
                </tr>
                <tr>
                <?php
                foreach ($columnas as $kc => $columna) {
                     if(!in_array($columna,$columnasAgrupadas))continue;
                        echo '<td>'.$columna.'</td>';                    
                }
                ?>
                </tr>
                
                <?php
            }
            $mostrarTotal = true;?>                            
            <?php
            foreach ($indexCuentasNumeroFijo as $index) {
                $numeroCuenta = $keysCuentas[$index];
                ?>
                 <tr>
                    <td><?php echo $arrayCuentasxPeriodos[$numeroCuenta]['nombrecuenta'] ?></td>
                <?php                   
                    $charinicial = substr($numeroCuenta, 0, 1);                    
                    switch ($charinicial){
                        case "1":
                            $subtotal = $arrayCuentasxPeriodos[$numeroCuenta]['activo'];
                            break;
                        case "2":
                            $subtotal = $arrayCuentasxPeriodos[$numeroCuenta]['pasivo'];
                            break;    
                        case "3":
                            $subtotal = $arrayCuentasxPeriodos[$numeroCuenta][$periodoActual];
                            break;
                        case "4":
                            $subtotal = $arrayCuentasxPeriodos[$numeroCuenta]['pasivo'];
                        break;
                        case "5":
                            $subtotal = $arrayCuentasxPeriodos[$numeroCuenta]['perdida'];                    
                            break;
                        case "6":
                            $subtotal = $arrayCuentasxPeriodos[$numeroCuenta]['ganancia'];                                        
                            break;
                    }            
                    foreach ($columnas as $kc => $columna) {
                        if(!in_array($columna,$columnasAgrupadoras)&&!in_array($columna, $columnasInicio)){
                            if($valoresPrefijo['tipo']==$columna){
                                echo '<td  class="numericTD tdborder" style="width:90px">'. number_format($subtotal, 2, ",", ".").'</td>';
                            }else{
                                echo '<td  class="numericTD tdborder" style="width:90px">0,00</td>';
                            }                         
                        } 
                        if(!isset($totalNota[$columna])) {
                            $totalNota[$columna]=0;
                        }
                        if($valoresPrefijo['tipo']==$columna){
                            $totalNota[$columna]+=$subtotal;
                        }
                    }
                    ?>
                </tr>
               <?php
            }
        }
    }
    if($mostrarTotal){ ?>
        <tr class="trnoclickeable">
            <th class="tdnoborder">Total de  <?php  echo $nombreNota; ?></th>
            <?php
                foreach ($columnas as $kc => $columna) {
                    if(!in_array($columna,$columnasAgrupadoras)&&!in_array($columna, $columnasInicio)){
                         echo '<th  class="numericTD tdborder" style="width:90px">'. number_format($totalNota[$columna], 2, ",", ".").'</td>';
                    } 
                }
            ?>
        </tr>
        <?php
        if($totalIngreso!=null){?>
            <tr class="trnoclickeable">
                <th class="tdnoborder">Diferencia (Ingresos - Gastos)</th>
                <?php
                $totalDiferencia=[];
                    foreach ($columnas as $kc => $columna) {
                        if(!in_array($columna,$columnasAgrupadoras)&&!in_array($columna, $columnasInicio)){
                            //if columna = "Amortiz." entonces en ingreso es "No computab."
                            if($columna=="Amortiz."){
                                $subtotal = $totalIngreso["No computab."]-$totalNota[$columna];                                
                            }else if($columna=="No deducibles"){
                                $subtotal = $totalIngreso["Exentos o no gravados"]-$totalNota[$columna];                                
                            }else if($columna=='Costo de bienes de cambio'){
                                $subtotal = $totalNota[$columna];                                
                            }else{
                                $subtotal = $totalIngreso[$columna]-
                                        $totalNota[$columna];                                
                            }//creo que en la primer categoria vamos a tener que agregar no deducibles
                             echo '<th  class="numericTD tdborder" style="width:90px">'. number_format($subtotal, 2, ",", ".").'</td>';
                             $totalDiferencia[$columna]=$subtotal;
                        } 
                    }
                ?>
            </tr>
            <tr class="trnoclickeable">
                <th class="tdnoborder">Resultado neto de  <?php  echo $nombreNota; ?></th>
                <?php
                    $costoventa = isset($totalNota['Costo de bienes de cambio'])?$totalNota['Costo de bienes de cambio']:0;
                    $resultadoneto = 
                            $totalDiferencia['Que Generan mov. de fondos']+
                            $totalDiferencia['Que no generan mov. de fondos']-
                            $totalNota['Amortiz.']-$costoventa;
                     $totalNota['resultadoneto']= $resultadoneto;
                    //falta restar Costo de ventas
                    echo '<th  class="numericTD tdborder" style="width:90px">'. number_format($resultadoneto, 2, ",", ".").'</td>';
                ?>
                <th class="tdnoborder" colspan="2">Importe neto que mueve fondos</th>
                <?php
                    $importenetoquemuevefondos = 
                            $totalDiferencia['Que Generan mov. de fondos'];
                            if(isset($totalDiferencia['Instrumentos Fcieros. deriv.'])){
                                $resultadoneto+=$totalDiferencia['Instrumentos Fcieros. deriv.'];                             
                            }
                            if(isset($totalIngreso['No computab.'])){
                                $resultadoneto+=$totalIngreso['No computab.'];                             
                            }
                            if(isset($totalDiferencia['Vts. acciones etc.'])){
                                $resultadoneto+=$totalDiferencia['Vts. acciones etc.'];                             
                            }
                            if(isset($totalDiferencia['No deducibles'])){
                                $resultadoneto+=$totalDiferencia['No deducibles'];                             
                            }
                            if(isset($totalNota['Costo de bienes de cambio'])){
                                $resultadoneto+=$totalNota['Costo de bienes de cambio'];                             
                            };
                    //falta restar Costo de ventas
                    echo '<th  class="numericTD tdborder" style="width:90px">'. number_format($importenetoquemuevefondos, 2, ",", ".").'</td>';
                ?>
            </tr>
            <tr class="trnoclickeable">
                <th class="tdnoborder">Resultado exento/no deducible</th>
                <?php
                    $resultadoexento = 
                            $totalDiferencia['No deducibles'];
                     if(isset($totalIngreso['No computab.'])){
                                $resultadoneto+=$totalIngreso['No computab.'];                             
                            };
                    echo '<th  class="numericTD tdborder" style="width:90px">'. number_format($resultadoexento, 2, ",", ".").'</td>';
                ?>
                <th class="tdnoborder" colspan="2">Importe neto que no mueve fondos</th>
                <?php
                    $importenetoquenomuevefondos = 
                            $totalDiferencia['Que no generan mov. de fondos'];
                            if(isset($totalDiferencia['Amortiz.'])){
                                $resultadoneto-=$totalDiferencia['Amortiz.'];                             
                            }                            
                    //falta restar Costo de ventas
                    echo '<th  class="numericTD tdborder" style="width:90px">'. number_format($importenetoquenomuevefondos, 2, ",", ".").'</td>';
                ?>
            </tr>
            <tr class="trnoclickeable">
                <th class="tdnoborder">Total</th>
                <?php
                    $total1 =$resultadoneto+$resultadoexento;
                    echo '<th  class="numericTD tdborder" style="width:90px">'. number_format($total1, 2, ",", ".").'</td>';
                ?>
                <th class="tdnoborder" colspan="2">Total</th>
                <?php
                    $total2 =$importenetoquemuevefondos+$importenetoquenomuevefondos;                          
                    //falta restar Costo de ventas
                    echo '<th  class="numericTD tdborder" style="width:90px">'. number_format($total2, 2, ",", ".").'</td>';
                ?>
            </tr>
        <?php
        }
    }
    return $totalNota;
}
function initializeConcepto(&$arrayprefijo,$prefijo,$tipo){
    $arrayprefijo[]=[
        'prefijo'=>$prefijo,   
        'tipo'=>$tipo,   
    ];
}
function mostrarDeduccionGeneral($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$nombreNota,$tope,$fechaInicioConsulta,$fechaFinConsulta,&$total){
    $mostrarTotal = false;
    $periodoActual = date('Y', strtotime($fechaInicioConsulta));
    $totalNota = [];
    $totalNota['real']=0;    
    $totalNota['tope']=0;   
    $totalNota['computable']=0;   
    $totalNota['nodeducible']=0;   
    $topeRestante=$tope;
    if (!isset($total['real'])){
        $total['real']=0;    
        $total['tope']=0;   
        $total['computable']=0;   
        $total['nodeducible']=0;   
    }
    foreach ($arrayPrefijos as $prefijo => $valoresPrefijo) {       
        $numerofijo = $valoresPrefijo['prefijo'];
        $indexCuentasNumeroFijo = array_keys(
            array_filter(
                $keysCuentas,
                function($var) use ($numerofijo){
                    return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                }
            )
        );
        if(count($indexCuentasNumeroFijo)!=0){ 
            if(!$mostrarTotal){
                //vamos a mostrar las columnas
               
                 ?>
                <tr class="trnoclickeable trTitle">
                    <th colspan="20" class="tdnoborder "><?php echo $nombreNota ?></th>
                </tr>
                <?php
            }
            $mostrarTotal = true;
            foreach ($indexCuentasNumeroFijo as $index) {
                $numeroCuenta = $keysCuentas[$index];                
                ?>
                 <tr>
                    <td><?php echo $arrayCuentasxPeriodos[$numeroCuenta]['nombrecuenta']; ?></td>
                <?php                   
                    $subtotal = $arrayCuentasxPeriodos[$numeroCuenta][$periodoActual];
                   $computable=0;
                   $noDeducible=0;
                   $noDeducibleContabilidad=0;
                    $numeroCuentaMayorTope= substr_replace($numeroCuenta, '599', 0, 3);                     
                    if(isset($arrayCuentasxPeriodos[$numeroCuentaMayorTope][$periodoActual])){
                        $noDeducibleContabilidad = $arrayCuentasxPeriodos[$numeroCuentaMayorTope][$periodoActual];
                    }
                   
                    echo '<td  class="numericTD tdborder" style="width:90px">'. number_format($subtotal, 2, ",", ".").'</td>';
                    echo '<td  class="numericTD tdborder" style="width:90px">'. number_format($topeRestante, 2, ",", ".").'</td>';
                    
                    if($tope==0){
                        $computable=$topeRestante;
                    }else{
                        if($topeRestante>$subtotal){
                            $topeRestante-=$subtotal;                           
                            $computable=$subtotal;
                            $subtotal-$computable;
                        }else{
                            $computable=$topeRestante;
                            $topeRestante=0;     
                            $noDeducible = $subtotal-$computable;
                        }
                    }
                    
                    echo '<td  class="numericTD tdborder" style="width:90px">'. number_format($computable, 2, ",", ".").'</td>';
                    echo '<td  class="numericTD tdborder" style="width:90px">'. number_format($noDeducibleContabilidad+$noDeducible, 2, ",", ".").'</td>';
                    
                    $totalNota['real']+=$subtotal;
                    $totalNota['tope']+=$tope;
                    $totalNota['computable']+=$computable;
                    $totalNota['nodeducible']+=$noDeducibleContabilidad+$noDeducible;
                    
                    $total['real']+=$subtotal;
                    $total['tope']+=$tope;
                    $total['computable']+=$computable;
                    $total['nodeducible']+=$noDeducibleContabilidad+$noDeducible;
                    ?>
                </tr>
               <?php
            }
        }
    }
    if($mostrarTotal){ ?>
        <tr class="trnoclickeable">
            <th class="tdnoborder">Subtotal</th>
            <th  class="numericTD tdborder" style="width:90px"><?php echo number_format($totalNota['real'], 2, ",", ".")?></td>
            <th  class="numericTD tdborder" style="width:90px"><?php echo number_format($totalNota['tope'], 2, ",", ".")?></td>
            <th  class="numericTD tdborder" style="width:90px"><?php echo number_format($totalNota['computable'], 2, ",", ".")?></td>
            <th  class="numericTD tdborder" style="width:90px"><?php echo number_format($totalNota['nodeducible'], 2, ",", ".")?></td>
        </tr>
    <?php
    
    }
    return $totalNota;
}



