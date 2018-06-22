<?php
echo $this->Html->script('jquery-ui',array('inline'=>false));
echo $this->Html->css('bootstrapmodal');
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
//echo $this->Html->script('jquery.table2excel',array('inline'=>false));
echo $this->Html->script('table2excel',array('inline'=>false));
echo $this->Html->script('jquery.dataTables.js',array('inline'=>false));
echo $this->Html->script('dataTables.altEditor.js',array('inline'=>false));
echo $this->Html->script('papelesdetrabajos/ganancias',array('inline'=>false));
echo $this->Html->script('raphael.js',array('inline'=>false));
echo $this->Html->script('progressStep.js',array('inline'=>false));
echo $this->Html->css('progressbarstyle');

/**
 * Created by PhpStorm.
 * User: caesarius
 * Date: 02/11/2016|
 * Time: 12:15 PM
 */
    $periodoActual =  date('Y', strtotime($fechaInicioConsulta));
    $fechaInicioConsultaSiguiente =  date('d-m-Y', strtotime($fechaInicioConsulta." + 1 Years"));
?>
<div class="index" style="padding: 0px 4%; margin-bottom: 11px;margin-left:16px;float: left;" id="headerCliente">
    <div style="width:30%; float: left;padding-top:11px">
        Contribuyente: <?php echo $cliente["Cliente"]['nombre'];
        echo $this->Form->input('clientenombre',['type'=>'hidden','value'=>$cliente["Cliente"]['nombre']]);
        echo $this->Form->input('cliid',['type'=>'hidden','value'=>$cliente["Cliente"]['id']]);
        echo $this->Form->input('impcliid',['type'=>'hidden','value'=>$cliente['Impcli'][0]['id']]);
        echo $this->Form->input('tienetercera',['type'=>'hidden','value'=>$tienetercera]);?>
    </div>
    <div style="width:25%; float: left;padding-top:11px">
        Periodo: <?php echo $periodo;
        echo $this->Form->input('periodo',['type'=>'hidden','value'=>$periodo]);
        echo $this->Form->input('fechaInicioConsultaSiguiente',['type'=>'hidden','value'=>$fechaInicioConsultaSiguiente]);
        echo $this->Form->input('fechaInicioConsulta',['type'=>'hidden','value'=>$fechaInicioConsulta]);
        echo $this->Form->input('fechaFinConsulta',['type'=>'hidden','value'=>date('d-m-Y', strtotime($fechaFinConsulta))]);
        ?>
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
        );
        
        $paramsPrepPapeles="'".$cliente['Cliente']['id']."','".$periodo."'";
        $paramsPrepPapeles2="'".$cliente['Impcli'][0]['id']."','".$periodo."'";
        $tieneasientodeApertura=false;
        $tieneasientodeexistenciafinal=false;
        $tieneasientodeganancias=false;
        foreach ($cuentasclientes as $kc => $cuentascliente){
            foreach ($cuentascliente['Movimiento'] as $movimiento){
                if($movimiento['Asiento']['tipoasiento']=='Apertura'){
                    $tieneasientodeApertura=true;
                }
                if($movimiento['Asiento']['tipoasiento']=='Existencia Final'){
                    $tieneasientodeexistenciafinal=true;
                }
            }
        }
        foreach($cliente['Impcli'][0]['Asiento'] as $asiento){
            $tieneasientodeganancias=true;
            break;
        }
        echo $this->Form->input('tieneasientodeApertura',['type'=>'hidden','value'=>$tieneasientodeApertura]);
        echo $this->Form->input('tieneasientodeexistenciafinal',['type'=>'hidden','value'=>$tieneasientodeexistenciafinal]);
        echo $this->Form->input('tieneasientodeGanancias',['type'=>'hidden','value'=>$tieneasientodeganancias]);?>
        
        <div id="progressBar" style="width: 200px;height: 65px;padding: 5px 5px;float:left;"></div>
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
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Determinacion del impuesto G</label>
    </div>    
    <div id="tabJustVarPat" class="cliente_view_tab" onclick="CambiarTab('justificacionvarpat');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Just. Var. Pat.</label>
    </div>
    <div id="tabQuebranto" class="cliente_view_tab" onclick="CambiarTab('quebrantos');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Quebrantos</label>
    </div>   
    <div id="tabDetImpBP" class="cliente_view_tab" onclick="CambiarTab('detImpBP');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Determinacion del impuesto BP</label>
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
                <td>Apertura</td>
                <td>Saldo Actual</td>
                <td>BP Gravado</td>
                <td>BP Exento/No Gravado</td>
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
                    $arrayPeriodos[$periodoAImputar]['costoscompra']=0;
                    $arrayPeriodos[$periodoAImputar]['noDedGeneral']=0;

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
                    $arrayCuentasxPeriodos[$numerodecuenta]['noDedGeneral']=[];
                    $arrayCuentasxPeriodos[$numerodecuenta]['costoscompra']=[];
                    $arrayCuentasxPeriodos[$numerodecuenta]['bienpersonal']=0;
                    $arrayCuentasxPeriodos[$numerodecuenta]['exento']=0;
                }          
                if($movimiento['Asiento']['tipoasiento']=='costoscompra'){
                    $arrayPeriodos[$periodoAImputar]['costoscompra']+=round($movimiento['debe'], 2);
                    $arrayPeriodos[$periodoAImputar]['costoscompra']-=round($movimiento['haber'], 2);
                   
                }
                if($movimiento['Asiento']['tipoasiento']!='Deduccion General'){
                    $arrayPeriodos[$periodoAImputar]['noDedGeneral']+=round($movimiento['debe'], 2);
                    $arrayPeriodos[$periodoAImputar]['noDedGeneral']-=round($movimiento['haber'], 2);
                }
                if($movimiento['Asiento']['tipoasiento']=='Apertura'){
                    $arrayPeriodos[$periodoAImputar]['apertura']+=round($movimiento['debe'], 2);
                    $arrayPeriodos[$periodoAImputar]['apertura']-=round($movimiento['haber'], 2);
                }else{
                    $arrayPeriodos[$periodoAImputar]['movimiento']+=round($movimiento['debe'], 2);
                    $arrayPeriodos[$periodoAImputar]['movimiento']-=round($movimiento['haber'], 2);
                }
                $arrayPeriodos[$periodoAImputar]['debes']+=round($movimiento['debe'], 2);
                $arrayPeriodos[$periodoAImputar]['haberes']+=round($movimiento['haber'], 2);
                
                $saldoCalculado += round($movimiento['debe'], 2);
                $saldoCalculado -= round($movimiento['haber'], 2);
                
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
            $saldoCalculado = round($saldoCalculado, 2);
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
                    $arrayPeriodos[$periodoActual]['noDedGeneral']=0;                    
                }
                $saldo = $arrayPeriodos[$periodoActual]['debes']-$arrayPeriodos[$periodoActual]['haberes'];
                $apertura = $arrayPeriodos[$periodoActual]['apertura'];
                $bienespersonales = $saldo;
                $exento =0;
                if(count($cuentascliente['Bienespersonale'])>0){
                    if($cuentascliente['Bienespersonale'][0]['periodo']==$periodo){
                        $bienespersonales = $cuentascliente['Bienespersonale'][0]['monto'];
                        $exento = $cuentascliente['Bienespersonale'][0]['exento'];
                    }
                }
                echo '<td  class="numericTD">'.
                    number_format($apertura, 2, ",", ".")
                    ."</td>";
                echo '<td  class="numericTD">'.
                    number_format($saldo, 2, ",", ".")
                    ."</td>";
                
                echo '<td  class="numericTD">'.
                    number_format($bienespersonales, 2, ",", ".")
                    ."</td>";
                echo '<td  class="numericTD">'.
                    number_format($exento, 2, ",", ".")
                    ."</td>";
                
                
                if(!isset($arrayCuentasxPeriodos[$numerodecuenta][$periodoActual])){
                    $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual]=0;
                    $arrayCuentasxPeriodos[$numerodecuenta]['bienpersonal']=0;
                    $arrayCuentasxPeriodos[$numerodecuenta]['exento']=0;
                    $arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual] = 0;
                    $arrayCuentasxPeriodos[$numerodecuenta]['movimiento'][$periodoActual] = 0;                       
                    $arrayCuentasxPeriodos[$numerodecuenta]['costoscompra'][$periodoActual]= 0;                       
                    $arrayCuentasxPeriodos[$numerodecuenta]['noDedGeneral'][$periodoActual]= 0;                       
                }
                $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual]=$saldo;
                $arrayCuentasxPeriodos[$numerodecuenta]['bienpersonal']=$bienespersonales;
                $arrayCuentasxPeriodos[$numerodecuenta]['exento']=$exento;
                $arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual]=$arrayPeriodos[$periodoActual]['apertura'];
                $arrayCuentasxPeriodos[$numerodecuenta]['movimiento'][$periodoActual]=$arrayPeriodos[$periodoActual]['movimiento'];       
                $arrayCuentasxPeriodos[$numerodecuenta]['costoscompra'][$periodoActual]=$arrayPeriodos[$periodoActual]['costoscompra'];    
                $arrayCuentasxPeriodos[$numerodecuenta]['noDedGeneral'][$periodoActual]=$arrayPeriodos[$periodoActual]['noDedGeneral'];    
                
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
    <?php
    //todo esto vamos a mostrar solo si es 3ra nomas che 
    if($tienetercera){
    ?>
    <table style="display:none">
        <thead>
            <tr class="trnoclickeable trTitle">
                <th colspan="6" style="text-align: left">
                    Contribuyente <?php echo $cliente['Cliente']['nombre'];?>
                </th>
            </tr>
            <tr class="trnoclickeable trTitle">
                <th colspan="6" style="text-align: center">
                    Resumen de carga de aplicativo Para 3er Categoria Empresas
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Compras y Gastos Imputables al Costo: Efectuado en el País - Ver Anexo II</td>
                <?php
                    //501000001 Costo de Venta
                    //110502012 Prod. Terminado XX Compras
                    //110504012 Prod. en Proceso XX Compras
                    //110506012 MP y Materiales XX Compras
                    //110507012 Otros bienes de cambio XX Compras*/
                    $existenciaComprasMercaderias = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110500012"],$periodoActual,$keysCuentas,'costoscompra',-1);
                    $existenciaComprasProdTerminado = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110502012"],$periodoActual,$keysCuentas,'costoscompra',-1);
                    $existenciaComprasProdEnProc = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110504012"],$periodoActual,$keysCuentas,'costoscompra',-1);
                    $existenciaComprasMpMaterials = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110506012"],$periodoActual,$keysCuentas,'costoscompra',-1);
                    $existenciaComprasOtros = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110507012"],$periodoActual,$keysCuentas,'costoscompra',-1);
                    $totalPeriodoCompras[$periodoActual] = $existenciaComprasMercaderias + $existenciaComprasProdTerminado + $existenciaComprasProdEnProc
                    + $existenciaComprasMpMaterials + $existenciaComprasOtros;    
                ?>
                <td class="tdWithNumber">
                    <?php echo number_format($totalPeriodoCompras[$periodoActual], 2, ",", ".");?>
                </td>
            </tr>
            <tr>
                <td>Compras y Gastos No Imputables al Costo: Efectuado en el País - Ver Anexo I</td>
                <?php
                    $arrayPrefijosGastos3raEmpresa=[];
                    $arrayPrefijosGastos3raEmpresa[]='5010';
                    $arrayPrefijosGastos3raEmpresa[]='5020';
                    $arrayPrefijosGastos3raEmpresa[]='5030';
                    $arrayPrefijosGastos3raEmpresa[]='50401';
                    $arrayPrefijosGastos3raEmpresa[]='50402';
                    $arrayPrefijosGastos3raEmpresa[]='50403';
                    $arrayPrefijosGastos3raEmpresa[]='50405';
                    $arrayPrefijosGastos3raEmpresa[]='50406';
                    $arrayPrefijosGastos3raEmpresa[]='50407';
                    $arrayPrefijosGastos3raEmpresa[]='50408';
                    $arrayPrefijosGastos3raEmpresa[]='50409';
                    $arrayPrefijosGastos3raEmpresa[]='50410';
                    $arrayPrefijosGastos3raEmpresa[]='50411';
                    $arrayPrefijosGastos3raEmpresa[]='50412';
                    $arrayPrefijosGastos3raEmpresa[]='50413';

                    if($tienetercera){
                        $arrayPrefijosGastos3raEmpresa[]='50499';
                        $arrayPrefijosGastos3raEmpresa[]='5050';
                        $arrayPrefijosGastos3raEmpresa[]='50612';
                        $arrayPrefijosGastos3raEmpresa[]='50613';
                        $arrayPrefijosGastos3raEmpresa[]='50614';
                        $arrayPrefijosGastos3raEmpresa[]='5062';
                    }
                    //tal vez las contribuciones tambien las tendriamos q agregar al if tiene tercera
                    $arrayPrefijosGastos3raEmpresa[]='5063';
                    $arrayPrefijosGastos3raEmpresa[]='507';
                    $arrayPrefijosGastos3raEmpresa[]='50800';
                    $arrayPrefijosGastos3raEmpresa[]='50801';
                    $arrayPrefijosGastos3raEmpresa[]='50802';
                    $arrayPrefijosGastos3raEmpresa[]='50803';
                    $arrayPrefijosGastos3raEmpresa[]='5090';
                    $totalGastos = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijosGastos3raEmpresa,$periodoActual,$keysCuentas,'todos',1);
                    $totalCostoMV = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,['501000003'],$periodoActual,$keysCuentas,'todos',1);
                    $totalGastosSinCMV = $totalGastos-$totalCostoMV;
                    
                    $arrayPrefijosAmortizaciones=[];
                    $arrayPrefijosAmortizaciones[]='50404';
                    $totalAmortizaciones = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijosAmortizaciones,$periodoActual,$keysCuentas,'todos',1);

                ?>
                <td class="tdWithNumber"><?php echo number_format($totalGastosSinCMV, 2, ",", ".");?></td>
            </tr>
            <tr>
                <td>Compras y Gastos atribuibles a ganancias exentas</td>
                <td class="tdWithNumber"><?php echo number_format(0, 2, ",", ".");?></td>
                <td>Deduccione y desgravaciones que no implican erogaciones de fondos Excepto Amortizaciones</td>
                <td class="tdWithNumber"><?php echo number_format(0, 2, ",", ".");?></td>
            </tr>
            <tr>
                <td>Amortizaciones</td>
                <td class="tdWithNumber"><?php echo number_format($totalAmortizaciones, 2, ",", ".");?></td>
            </tr>
        </tbody>
    </table>
    
    <?php
    }
    //Fin de Anexos de calculo de costos y cuadro de gastos para tercer categoria emppresas
    ?>
    <table class="toExcelTable tbl_border tblEstadoContable splitForPrint">
        <thead>
            <tr>
                <td>
                    Contribuyente <?php echo $cliente["Cliente"]['nombre'] ?>
                </td>
                <td>
                     Anexo I: Ingresos y Gastos por categoria Año Fiscal <?php echo $periodoActual ?>
                </td>               
            </tr>
        </thead>
        <tbody>
            
            <?php
            $arrayPrefijos=[];
            /*Columnas 1er Categoria INGGRESOS
             * Gravado IVA - No Gravado IVA
             * 
             * Columnas 1er Categoria Gastos
             * Que Implican Erogacion - Que no Implican Erogacion - Amortizacion
             */
            
            initializeConcepto($arrayPrefijos,'60201','Gravado IVA');
            initializeConcepto($arrayPrefijos,'60202','No Gravado IVA');            
            if(!$tienetercera&&$tieneprimera){
                initializeConcepto($arrayPrefijos,'6010','Gravado IVA');
            }
            $columnas=['Ingresos',"Gravados",'Gravado IVA','No Gravado IVA'];
            $totalIngresos1ra = mostrarIngresos($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,"Ingresos 1&#176;",$columnas,"Gravados",2,
                    $fechaInicioConsulta,$fechaFinConsulta,null);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'510001001','Que Imp. Erog.');
            initializeConcepto($arrayPrefijos,'510002001','Que No Imp. Erog.');
            //gastos de 3ra que tienen que salir en esta categoria
            if(!$tienetercera&&$tieneprimera){
                initializeConcepto($arrayPrefijos,'5030','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'50499','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'5050','Que Imp. Erog.');//Estas son cuentas del banco    
                initializeConcepto($arrayPrefijos,'50612','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'50613','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'50614','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'5062','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'5063','Que Imp. Erog.');
            }
            $columnas=['Gastos',"Deducibles",'Que Imp. Erog.','Que No Imp. Erog.','Amortiz.'];
            $totalGastos1ra = mostrarIngresos($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,"Gastos 1&#176;",$columnas,"Deducibles",3,
                    $fechaInicioConsulta,$fechaFinConsulta,$totalIngresos1ra);
            
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'60301','Gravado IVA');
            initializeConcepto($arrayPrefijos,'60302','No Gravado IVA');
            if(!$tienetercera&&!$tieneprimera&&$tienesegunda){
                initializeConcepto($arrayPrefijos,'6010','Gravado IVA');
            }
            $columnas=['Ingresos',"Gravados",'Gravado IVA','No Gravado IVA'];
            $totalIngresos2da = mostrarIngresos($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,"Ingresos 2&#176;",$columnas,"Otros ing. gravados",2,
                    $fechaInicioConsulta,$fechaFinConsulta,null);
          
           
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'511001','Que Imp. Erog.');
            initializeConcepto($arrayPrefijos,'5110021','Que Imp. Erog.');
            initializeConcepto($arrayPrefijos,'5110022','Que Imp. Erog.');
            initializeConcepto($arrayPrefijos,'5110023','Que Imp. Erog.');
            initializeConcepto($arrayPrefijos,'5110024','Que No Imp. Erog.');
            initializeConcepto($arrayPrefijos,'5110025','Que Imp. Erog.');
            initializeConcepto($arrayPrefijos,'511003','Que Imp. Erog.');
            initializeConcepto($arrayPrefijos,'511004','Que Imp. Erog.');
            initializeConcepto($arrayPrefijos,'511005','Que Imp. Erog.');
            initializeConcepto($arrayPrefijos,'511006','Que Imp. Erog.');
            initializeConcepto($arrayPrefijos,'511007','Que Imp. Erog.');
            initializeConcepto($arrayPrefijos,'511008','Que Imp. Erog.');
            $columnas=['Gastos',"Otros conceptos deducibles",'Que Imp. Erog.','Que No Imp. Erog.','Amortiz.'];            
            //gastos de 3ra que tienen que salir en esta categoria
            if(!$tienetercera&&!$tieneprimera&&$tienesegunda){
                initializeConcepto($arrayPrefijos,'5030','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'50499','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'5050','Que Imp. Erog.');//Estas son cuentas del banco    
                initializeConcepto($arrayPrefijos,'50612','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'50613','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'50614','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'5062','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'5063','Que Imp. Erog.');
            }
            $totalGastos2da = mostrarIngresos($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,"Gastos 2&#176;",$columnas,"Otros ing. gravados",3,
                    $fechaInicioConsulta,$fechaFinConsulta,$totalIngresos2da);
            
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'60401','Gravado IVA');
            initializeConcepto($arrayPrefijos,'60402','No Gravado IVA');
            if(!$tienetercera&&!$tieneprimera&&!$tienesegunda&&$tieneterceraauxiliar){
                initializeConcepto($arrayPrefijos,'6010','Gravado IVA');
            }
            $columnas=['Ingresos','Gravados','Gravado IVA','No Gravado IVA'];            
            $totalIngresos3ra = mostrarIngresos($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,"Ingresos 3&#176;",$columnas,"Gravados",2,
                    $fechaInicioConsulta,$fechaFinConsulta,null);

            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'512001','Que Imp. Erog.');
            initializeConcepto($arrayPrefijos,'512002001','Que Imp. Erog.');
            initializeConcepto($arrayPrefijos,'512002002','Que Imp. Erog.');
            initializeConcepto($arrayPrefijos,'512002003','Que Imp. Erog.');
            initializeConcepto($arrayPrefijos,'512002004','Que No Imp. Erog.');
            initializeConcepto($arrayPrefijos,'512003','Que Imp. Erog.');
            initializeConcepto($arrayPrefijos,'512004','Que Imp. Erog.');
            initializeConcepto($arrayPrefijos,'512005','Que Imp. Erog.');
            initializeConcepto($arrayPrefijos,'512006','Que Imp. Erog.');
            initializeConcepto($arrayPrefijos,'512007','Que Imp. Erog.');
            initializeConcepto($arrayPrefijos,'512008','Que Imp. Erog.');            
            $columnas=['Gastos','Deducibles','Que Imp. Erog.','Que No Imp. Erog.','Amortiz.','No deducibles'];  
            //gastos de 3ra que tienen que salir en esta categoria
            if(!$tienetercera&&!$tieneprimera&&!$tienesegunda&&$tieneterceraauxiliar){
                initializeConcepto($arrayPrefijos,'5030','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'50499','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'5050','Que Imp. Erog.');//Estas son cuentas del banco    
                initializeConcepto($arrayPrefijos,'50612','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'50613','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'50614','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'5062','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'5063','Que Imp. Erog.');
            }
            $totalGastos3ra = mostrarIngresos($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,"Gastos 3&#176;",$columnas,"Deducibles",3,
                    $fechaInicioConsulta,$fechaFinConsulta,$totalIngresos3ra);
            
            $arrayPrefijos=[];
            
            if($tienetercera){
                initializeConcepto($arrayPrefijos,'6010','Gravado IVA');
            }
            $columnas=['Ingresos','Gravados','Gravado IVA','No Gravado IVA'];            
            $totalIngresos3raEmpresa = mostrarIngresos($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,"Ingresos 3&#176;(EU)",$columnas,"Gravados",2,
                    $fechaInicioConsulta,$fechaFinConsulta,null);
            
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'5010','Compras y Gastos Imp. al costo');
            initializeConcepto($arrayPrefijos,'5020','Que No Imp. Erog.');
            initializeConcepto($arrayPrefijos,'50401','Compras y Gastos Imp. al costo');
            initializeConcepto($arrayPrefijos,'50402','Compras y Gastos Imp. al costo');
            initializeConcepto($arrayPrefijos,'50403','Compras y Gastos Imp. al costo');
            initializeConcepto($arrayPrefijos,'50404','Amortiz.');
            initializeConcepto($arrayPrefijos,'50405','Compras y Gastos Imp. al costo');
            initializeConcepto($arrayPrefijos,'50406','Compras y Gastos Imp. al costo');
            initializeConcepto($arrayPrefijos,'50407','Compras y Gastos Imp. al costo');
            initializeConcepto($arrayPrefijos,'50408','Compras y Gastos Imp. al costo');
            initializeConcepto($arrayPrefijos,'50409','Compras y Gastos Imp. al costo');
            initializeConcepto($arrayPrefijos,'50410','Compras y Gastos Imp. al costo');
            initializeConcepto($arrayPrefijos,'50411','Compras y Gastos Imp. al costo');
            initializeConcepto($arrayPrefijos,'50412','Compras y Gastos Imp. al costo');
            initializeConcepto($arrayPrefijos,'50413','Compras y Gastos Imp. al costo');

            if($tienetercera){
                initializeConcepto($arrayPrefijos,'5030','Compras y Gastos Imp. al costo');
                initializeConcepto($arrayPrefijos,'50499','Compras y Gastos Imp. al costo');
                initializeConcepto($arrayPrefijos,'5050','Compras y Gastos Imp. al costo');//Estas son cuentas del banco    
                initializeConcepto($arrayPrefijos,'50612','Compras y Gastos Imp. al costo');
                initializeConcepto($arrayPrefijos,'50613','Compras y Gastos Imp. al costo');
                initializeConcepto($arrayPrefijos,'50614','Compras y Gastos Imp. al costo');
                initializeConcepto($arrayPrefijos,'5062','Compras y Gastos Imp. al costo');
                initializeConcepto($arrayPrefijos,'5063','Compras y Gastos Imp. al costo');
            }
            //tal vez las contribuciones tambien las tendriamos q agregar al if tiene tercera
            initializeConcepto($arrayPrefijos,'507','Compras y Gastos Imp. al costo');
            initializeConcepto($arrayPrefijos,'50800','Compras y Gastos Imp. al costo');
            initializeConcepto($arrayPrefijos,'50801','Compras y Gastos Imp. al costo');
            initializeConcepto($arrayPrefijos,'50802','Compras y Gastos Imp. al costo');
            initializeConcepto($arrayPrefijos,'50803','Compras y Gastos Imp. al costo');
            initializeConcepto($arrayPrefijos,'5090','Compras y Gastos Imp. al costo');
            $columnas=['Compras y Gastos','Gastos deducibles','Compras y Gastos Imp. al costo','Compras y Gastos No Imp. al costo','Que No Imp. Erog.','Amortiz.'];            
            $totalGastos3raEmpresa = mostrarIngresos($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,"Gastos 3&#176;(EU)",$columnas,"Gravados",3,
                    $fechaInicioConsulta,$fechaFinConsulta,$totalIngresos3raEmpresa);
            
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'60501','Gravado IVA');
            initializeConcepto($arrayPrefijos,'60502','No Gravado IVA');
            if(!$tienetercera&&!$tieneprimera&&!$tienesegunda&&!$tieneterceraauxiliar&&$tienecuarta){
                initializeConcepto($arrayPrefijos,'6010','Gravado IVA');
            }
            $columnas=['Ingresos','Gravados','Gravado IVA','No Gravado IVA'];            
            $totalIngresos4ta = mostrarIngresos($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,"Ingresos 4&#176;",$columnas,"Gravados",2,
                    $fechaInicioConsulta,$fechaFinConsulta,null);
           
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'513000001','Que Imp. Erog.');
            initializeConcepto($arrayPrefijos,'513000002','Que No Imp. Erog.');
            initializeConcepto($arrayPrefijos,'513000003','Que No Imp. Erog.');
            initializeConcepto($arrayPrefijos,'513000004','Que No Imp. Erog.');
            initializeConcepto($arrayPrefijos,'513000005','Que Imp. Erog.');
            initializeConcepto($arrayPrefijos,'51300100','Amortiz.');
            initializeConcepto($arrayPrefijos,'51300200','Amortiz.');
            initializeConcepto($arrayPrefijos,'51300300','Amortiz.');
            initializeConcepto($arrayPrefijos,'51300400','Amortiz.');
            initializeConcepto($arrayPrefijos,'51300500','Amortiz.');
            initializeConcepto($arrayPrefijos,'51300600','Amortiz.');
            $columnas=['Gastos','Deducibles','Que Imp. Erog.','Que No Imp. Erog.','Amortiz.'];    
            //gastos de 3ra que tienen que salir en esta categoria
            if(!$tienetercera&&!$tieneprimera&&!$tienesegunda&&!$tieneterceraauxiliar&&$tienecuarta){
                initializeConcepto($arrayPrefijos,'5030','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'50499','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'5050','Que Imp. Erog.');//Estas son cuentas del banco    
                initializeConcepto($arrayPrefijos,'50612','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'50613','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'50614','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'5062','Que Imp. Erog.');
                initializeConcepto($arrayPrefijos,'5063','Que Imp. Erog.');
            }
            $totalGastos4ta = mostrarIngresos($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,"Gastos 4&#176;",$columnas,"Deducibles",3,
                    $fechaInicioConsulta,$fechaFinConsulta,$totalIngresos4ta);
            //Debugger::dump($totalGastos4ta)
            ?>
        </tbody>
    </table>        
    <?php
    if($tienetercera){
        ?>
    <table   style="display:none" id="tblAnexoI"  class="toExcelTable tblEstadoContable tbl_verticalborder tblAnexoI" cellspacing="0">
        <thead>
            <tr class="trnoclickeable trTitle">
                <th colspan="6" style="text-align: left">
                    Contribuyente <?php echo $cliente['Cliente']['nombre'];?>
                </th>
            </tr>
            <tr class="trnoclickeable trTitle">
                <th colspan="6" style="text-align: center">
                    Anexo II: Costo de los Bienes Vendidos, Servicios Prestados y de Producci&oacute;n al
                    <?php echo date('d-m-Y', strtotime($fechaFinConsulta)); ?>  
                </th>
            </tr>
        </thead>
        <tbody>
        <tr class="trTitle">
            <th colspan="2">
                Existencia Inicial
            </th>
            <th colspan="2" style="text-align: center;width: 90px">
            </th>

        </tr>         
            <?php

            //aca vamos a buscar los valores de la cuenta 	110500011 Mercaderia Inicial que esten en un asiento de apertura
            //capas que no sea necesario usar el asiento de apertura por que
            $totalPeriodoExistenciaInicial=[];
            if(!isset($totalPeriodoExistenciaInicial[$periodoActual])){
                $totalPeriodoExistenciaInicial[$periodoActual] = 0;//existen estos valores
            }
            //existencia final del periodo anterior es la inicial de Actual
            //110500013 Mercader&iacute;as XX E Final
            //110502013 Prod. Terminado XX E Final
            //110504013 Prod. en Proceso XX E Final
            //110506013 MP y Materiales XX EFIN
            //110507013 Otros Bienes de Cambio EFin*/
            $existenciaInicialMercaderias = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110500011"],$periodoActual,$keysCuentas,'apertura',1);
            $existenciaInicialProdTerminado = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110502011"],$periodoActual,$keysCuentas,'apertura',1);
            $existenciaInicialProdEnProc = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110504011"],$periodoActual,$keysCuentas,'apertura',1);
            $existenciaInicialMpMaterials = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110506011"],$periodoActual,$keysCuentas,'apertura',1);
            $existenciaInicialOtros = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110507011"],$periodoActual,$keysCuentas,'apertura',1);
            $totalPeriodoExistenciaInicial[$periodoActual] += $existenciaInicialMercaderias + $existenciaInicialProdTerminado + $existenciaInicialProdEnProc
                    + $existenciaInicialMpMaterials + $existenciaInicialOtros;

            ?>                    
        <tr class="trTitle">
            <th class="" colspan="2">
                Total Existencia Inicial
            </th>
            <?php
            echo '<th colspan="2" class="numericTD " style=";width: 90px">' .
                    number_format($totalPeriodoExistenciaInicial[$periodoActual], 2, ",", ".")
                    . "</th>";
            ?>
        </tr>
        <tr class="trTitle">
            <th colspan="2">
                Compras
            </th>
            <th colspan="2" style="text-align: center;width: 90px">
            </th>
        </tr>
            <?php
            $totalPeriodoExistenciaFinal=[] ;
            //COMPRAS PERIODO ACTUAL + Existencia FINAL ACTUAL - EXISTENCIA INICIAL ACTUAL

        
            //110500013 Mercader&iacute;as XX E Final
            //110502013 Prod. Terminado XX E Final
            //110504013 Prod. en Proceso XX E Final
            //110506013 MP y Materiales XX EFIN
            //110507013 Otros Bienes de Cambio EFin
            $existenciaFinalMercaderias = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110500013"],$periodoActual,$keysCuentas,'todos',-1);
            $existenciaFinalProdTerminado = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110502013"],$periodoActual,$keysCuentas,'todos',-1);
            $existenciaFinalProdEnProc = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110504013"],$periodoActual,$keysCuentas,'todos',-1);
            $existenciaFinalMpMaterials = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110506013"],$periodoActual,$keysCuentas,'todos',-1);
            $existenciaFinalOtros = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110507013"],$periodoActual,$keysCuentas,'todos',-1);

            $totalPeriodoExistenciaFinal[$periodoActual] = $existenciaFinalMercaderias + $existenciaFinalProdTerminado + $existenciaFinalProdEnProc 
                    + $existenciaFinalMpMaterials + $existenciaFinalOtros;
            //estoy sacando esta variable de dos lugares 
            //o lo calculamos o lo traemos de contabilidad

        
        
            //COMPRAS PERIODO ANTERIOR + Existencia FINAL ANTERIOR - EXISTENCIA INICIAL ANTERIOR

            //110500012 Mercader&iacute;as XX Compras
            //110502012 Prod. Terminado XX Compras
            //110504012 Prod. en Proceso XX Compras
            //110506012 MP y Materiales XX Compras
            //110507012 Otros bienes de cambio XX Compras*/

        showRowAnexoICostos("Mercader&iacute;as",$existenciaComprasMercaderias);              
        //mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoCompras,"Productos Terminados","110502012",$fechaInicioConsulta,$fechaFinConsulta);
        //mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoCompras,"Producci&oacute;n en Proceso","110504012",$fechaInicioConsulta,$fechaFinConsulta);
        //mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoCompras,"Materias Primas e Insumos incorporados a la producci&oacute;n","110506012",$fechaInicioConsulta,$fechaFinConsulta);
        showRowAnexoICostos("Productos Terminados",$existenciaComprasProdTerminado);
        showRowAnexoICostos("Producci&oacute;n en Proceso",$existenciaComprasProdEnProc);
        showRowAnexoICostos("Materias Primas e Insumos incorporados a la producci&oacute;n",$existenciaComprasMpMaterials);
        showRowAnexoICostos("Insumos Incorporados a la Prestaci&oacute;n de Servicios",0);
        showRowAnexoICostos("Otros: Gastos Activados",$existenciaComprasOtros);
        showRowAnexoICostos("Participaci&oacute;n en negocios conjuntos",0);
        ?>         
        <tr class="trTitle">
            <th class="" colspan="2">
                Total de Compras
            </th>
            <?php
            echo '<th colspan="2" class="numericTD " style="width: 90px">' .
                    number_format($totalPeriodoCompras[$periodoActual], 2, ",", ".")
                    . "</th>";
            ?>
        </tr>            
        <tr class="trTitle">
            <th colspan="2">
                Devoluciones de Compras
            </th>
            <th colspan="2" style="text-align: center;width: 90px">
            </th>

        </tr>
        <?php
        $totalPeriodoDevoluciones = [];
        mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoDevoluciones,"Mercader&iacute;as","110500014",$fechaInicioConsulta,$fechaFinConsulta);
        mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoDevoluciones,"Productos Terminados","110502014",$fechaInicioConsulta,$fechaFinConsulta);
        mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoDevoluciones,"Producci&oacute;n en Proceso","110504014",$fechaInicioConsulta,$fechaFinConsulta);
        mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoDevoluciones,"Materias Primas e Insumos incorporados a la producci&oacute;n","110506014",$fechaInicioConsulta,$fechaFinConsulta);

        showRowAnexoICostos("Insumos Incorporados a la Prestaci&oacute;n de Servicios",0);
        showRowAnexoICostos("Otros",0);
        showRowAnexoICostos("Participaci&oacute;n en negocios conjuntos",0);

        ?>
        <tr class="trTitle">
            <th class="" colspan="2">
                Total de Devoluciones de Compras
            </th>
            <?php
            echo '<th colspan="2" class="numericTD " style="width: 90px">' .
                    number_format($totalPeriodoDevoluciones[$periodoActual], 2, ",", ".")
                    . "</th>";              
            ?>
        </tr>

        <tr class="trTitle">
            <th colspan="2">
                Existencia Final
            </th>
            <th colspan="2" style="text-align: center">
            </th>
        </tr>
        <tr>
            <td colspan="2">
                Mercader&iacute;as
            </td>
            <?php
             echo '<td colspan="2" class="numericTD" style="width: 90px">' .
                    number_format($totalPeriodoExistenciaFinal[$periodoActual], 2, ",", ".")
                    . "</td>";
            ?>
        </tr>
        <?php
         mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoExistenciaFinal,"Productos Terminados","110502013",$fechaInicioConsulta,$fechaFinConsulta);
         mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoExistenciaFinal,"Producci&oacute;n en Proceso","110504013",$fechaInicioConsulta,$fechaFinConsulta);
         mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoExistenciaFinal,"Materias Primas y Materiales","110506013",$fechaInicioConsulta,$fechaFinConsulta);
        ?>
        <tr class="trTitle">
            <th class=" " colspan="2">
                Total Existencia Final
            </th>
            <?php
           echo '<th colspan="2" class="numericTD  " style="width: 90px">' .
                    number_format($totalPeriodoExistenciaFinal[$periodoActual], 2, ",", ".")
                    . "</th>";
            ?>
        </tr>
        <tr  class="trTitle">
            <th colspan="2">
                Costo de los Bienes, de los Servicios y de Producci&oacute;n
            </th>
            <?php
            $totalPeriodoCostoBienesServiciosProduccion = [];
            echo '<th colspan="2" class="numericTD" style="width: 90px">' ;
                $totalPeriodo = $totalPeriodoExistenciaInicial[$periodoActual];
                $totalPeriodo += $totalPeriodoCompras[$periodoActual];
                $totalPeriodo -= $totalPeriodoDevoluciones[$periodoActual];
                $totalPeriodo += $totalPeriodoExistenciaFinal[$periodoActual];

                echo number_format($totalPeriodo, 2, ",", ".");
                echo  "</th>";
                if(!isset($totalPeriodoCostoBienesServiciosProduccion[$periodoActual])){
                    $totalPeriodoCostoBienesServiciosProduccion[$periodoActual] = 0;//existen estos valores
                }
                $totalPeriodoCostoBienesServiciosProduccion[$periodoActual] += $totalPeriodo;
            ?>
        </tr>
        </tbody>
    </table>
    <?php
    //Fin Anexo II Costo de los Bienes Vendidos, Servicios Prestados y de Producci&oacute;n
    }
    ?>
</div>
<div  style="width:100%;height: 1px; page-break-before:always"></div>
<div class="index estadocontable" id="divPatrimonioTercera" >
    <table class="toExcelTable tbl_border tblEstadoContable splitForPrint">
        <thead>
            <tr>
                <td colspan="4">
                    Contribuyente <?php echo $cliente["Cliente"]['nombre'] ?>
                </td>
                <td colspan="4">
                    Año Fiscal <?php echo $periodoActual ?>
                </td>               
            </tr>
        </thead>
        <tbody>    
            <tr>
                <th colspan="4"></th>
                <th colspan="2">Ganancias</th>
                <th colspan="2">Bienes Personales</th>
            </tr>
            <tr>
                <th colspan="4">Detalle del capital afectado a la actividad</th>
                <th colspan="">Inicial</th>
                <th colspan="">Final</th>
                <th colspan="">Gravado</th>
                <th colspan="">Exento/No Gravado</th>
            </tr>
            <tr class="trTitle">
                <th colspan="4">Activo</th>
                <th colspan=""></th>
                <th colspan=""></th>
                <th colspan=""></th>
                <th colspan=""></th>
            </tr>
            <?php
            $totalActivos=[];
            $totalPasivos=[];
            if($tienetercera){
                $arrayPrefijos=[];
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
                initializeConcepto($arrayPrefijos,'1206010','');
                $inmuebles = mostrarBienDeUsoTercera($arrayCuentasxPeriodos, $arrayPrefijos, $keysCuentas, 'Inmuebles ',$fechaInicioConsulta, $fechaFinConsulta,$totalActivos);
                $arrayPrefijos=[];
                initializeConcepto($arrayPrefijos,'1206020','');
                //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
                //$rodados = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Rodados',$fechaInicioConsulta,$fechaFinConsulta,$totalActivos);
                $rodados = mostrarBienDeUsoTercera($arrayCuentasxPeriodos, $arrayPrefijos, $keysCuentas, 'Rodados',$fechaInicioConsulta, $fechaFinConsulta,$totalActivos);
                $arrayPrefijos=[];
                initializeConcepto($arrayPrefijos,'1206030','');
                //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
                //$instalaciones = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Instalaciones',$fechaInicioConsulta,$fechaFinConsulta,$totalActivos);
                $instalaciones = mostrarBienDeUsoTercera($arrayCuentasxPeriodos, $arrayPrefijos, $keysCuentas, 'Instalaciones',$fechaInicioConsulta, $fechaFinConsulta,$totalActivos);
                $arrayPrefijos=[];
                initializeConcepto($arrayPrefijos,'1206040','');
                initializeConcepto($arrayPrefijos,'1206050','');
                initializeConcepto($arrayPrefijos,'1206060','');
                initializeConcepto($arrayPrefijos,'1206070','');
                initializeConcepto($arrayPrefijos,'1206080','');
                initializeConcepto($arrayPrefijos,'1209000','');
                //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
                //$otrosbienesdeuso = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Otros bienes de uso',$fechaInicioConsulta,$fechaFinConsulta,$totalActivos);
                $otrosBienesDeUso = mostrarBienDeUsoTercera($arrayCuentasxPeriodos, $arrayPrefijos, $keysCuentas, 'Otros bienes de uso',$fechaInicioConsulta, $fechaFinConsulta,$totalActivos);
                 $arrayPrefijos=[];
                initializeConcepto($arrayPrefijos,'120800','');
                initializeConcepto($arrayPrefijos,'1210000','');
                //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
                $bienesintangibles = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Bienes Intangibles',$fechaInicioConsulta,$fechaFinConsulta,$totalActivos);
            }
            $totalActivos['apertura']=isset($totalActivos['apertura'])?$totalActivos['apertura']:0;
            $totalActivos[$periodoActual]=isset($totalActivos[$periodoActual])?$totalActivos[$periodoActual]:0;
            $totalActivos['bienpersonal']=isset($totalActivos['bienpersonal'])?$totalActivos['bienpersonal']:0;
            $totalActivos['exento']=isset($totalActivos['exento'])?$totalActivos['exento']:0;
            
            $totalPasivos['apertura']=isset($totalPasivos['apertura'])?$totalPasivos['apertura']:0;
            $totalPasivos[$periodoActual]=isset($totalPasivos[$periodoActual])?$totalPasivos[$periodoActual]:0;
            $totalPasivos['bienpersonal']=isset($totalPasivos['bienpersonal'])?$totalPasivos['bienpersonal']:0;
            $totalPasivos['exento']=isset($totalPasivos['exento'])?$totalPasivos['exento']:0;
            ?>
            <tr class="trTitle">
                <td colspan="4">Total del Activo</td>
                <td class="tdWithNumber"> <?php echo number_format($totalActivos['apertura'], 2, ",", ".")?></td>
                <td class="tdWithNumber"> <?php echo number_format($totalActivos[$periodoActual], 2, ",", ".")?></td>
                <td class="tdWithNumber"> <?php echo number_format($totalActivos['bienpersonal'], 2, ",", ".")?></td>
                <td class="tdWithNumber"> <?php echo number_format($totalActivos['exento'], 2, ",", ".")?></td>
            </tr>
            <tr class="trTitle">
                <th colspan="4">Pasivo</th>               
                <th colspan="1"></th>               
                <th colspan="1"></th>               
                <th colspan="1"></th>               
                <th colspan="1"></th>               
            </tr>
            <?php
            if($tienetercera){
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
                initializeConcepto($arrayPrefijos,'2105','');
                $anticipoclientes = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Anticipos clientes',$fechaInicioConsulta,$fechaFinConsulta,$totalPasivos);
                $arrayPrefijos=[];
                initializeConcepto($arrayPrefijos,'2106','');
                $dividendosapagar = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Dividendos a pagar',$fechaInicioConsulta,$fechaFinConsulta,$totalPasivos);
                $arrayPrefijos=[];
                initializeConcepto($arrayPrefijos,'2107','');
                initializeConcepto($arrayPrefijos,'2207','');
                $otrasdeudas = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Otras Deudas',$fechaInicioConsulta,$fechaFinConsulta,$totalPasivos);
                $arrayPrefijos=[];
                initializeConcepto($arrayPrefijos,'2108','');
                initializeConcepto($arrayPrefijos,'2208','');
                $previsiones = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Previsiones',$fechaInicioConsulta,$fechaFinConsulta,$totalPasivos);
            }
            ?>
            <tr class="trTitle">
                <td colspan="4">Total del Pasivo</td>
                <td class="tdWithNumber"> <?php echo number_format($totalPasivos['apertura'], 2, ",", ".")?></td>
                <td class="tdWithNumber"> <?php echo number_format($totalPasivos[$periodoActual], 2, ",", ".")?></td>
                <td class="tdWithNumber"> <?php echo number_format($totalPasivos['bienpersonal'], 2, ",", ".")?></td>
                <td class="tdWithNumber"> <?php echo number_format($totalPasivos['exento'], 2, ",", ".")?></td>
            </tr>
            <tr class="trTitle">
                <td colspan="4">Patrimonio Neto</td>
                 <?php
                 $patrimonioNeto=[];
                 $patrimonioNeto['apertura']=$totalActivos['apertura']+$totalPasivos['apertura'];
                 $patrimonioNeto[$periodoActual]=$totalActivos[$periodoActual]+$totalPasivos[$periodoActual];
                 $patrimonioNeto['bienpersonal']=$totalActivos['bienpersonal']+$totalPasivos['bienpersonal'];
                 $patrimonioNeto['exento']=$totalActivos['exento']+$totalPasivos['exento'];
                 ?>
                <td class="tdWithNumber"> <?php echo number_format($patrimonioNeto['apertura'], 2, ",", ".")?></td>
                <td class="tdWithNumber"> <?php echo number_format($patrimonioNeto[$periodoActual], 2, ",", ".")?></td>
                <td class="tdWithNumber"> <?php echo number_format($patrimonioNeto['bienpersonal'], 2, ",", ".")?></td>
                <td class="tdWithNumber"> <?php echo number_format($patrimonioNeto['exento'], 2, ",", ".")?></td>
            </tr>
        </tbody>
    </table>
</div>
<div  style="width:100%;height: 1px; page-break-before:always"></div>
<div class="index estadocontable" id="divPatrimonio" >
    <table class="toExcelTable tbl_border tblEstadoContable splitForPrint">
        <thead>
            <tr>
                <td colspan="3">
                    Contribuyente <?php echo $cliente["Cliente"]['nombre'] ?>
                </td>
                <td>
                    Año Fiscal <?php echo $periodoActual ?>
                </td>               
                <td colspan="2">
                    Ganancias
                </td>               
                <td colspan="2">
                    Bienes Personales
                </td>               
            </tr>
        </thead>
        <tbody>    
            <tr class="trTitle">
                <th colspan="4">Patrimonio en el pais</th>
                <th colspan="1">Inicial</th>
                <th colspan="1">Final Actual</th>
                <th colspan="1">Gravado</th>
                <th colspan="1">Exento/No Gravado</th>
            </tr>
            <?php
            //Patrimonio 3ra que aparece aca por que se usaron cuentas de 3ra pero no tiene 3ra
            if(!$tienetercera){
                $arrayPrefijos=[];
                initializeConcepto($arrayPrefijos,'1101','');
                $patdisponibilidades = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Disponibilidades',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
                $arrayPrefijos=[];
                initializeConcepto($arrayPrefijos,'1103','');
                initializeConcepto($arrayPrefijos,'1104','');
                $patcreditos = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Creditos EU',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
                $arrayPrefijos=[];                                 
                initializeConcepto($arrayPrefijos,'1105','');
                $patbienesdecambio = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Bienes de Cambio',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
                $arrayPrefijos=[];
                initializeConcepto($arrayPrefijos,'1102','');
                initializeConcepto($arrayPrefijos,'1202','');
                $patInversiones = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Inversiones',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
                //$arrayPrefijos=[];
                //$inmueblesedificado = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Inmuebles Edificado',$fechaInicioConsulta,$fechaFinConsulta,$totalActivos);
                //$arrayPrefijos=[];
                //$inmueblesmejora = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Inmuebles Mejora',$fechaInicioConsulta,$fechaFinConsulta,$totalActivos);
                $arrayPrefijos=[];
                initializeConcepto($arrayPrefijos,'1206010','');
                $patinmuebles = mostrarBienDeUsoTercera($arrayCuentasxPeriodos, $arrayPrefijos, $keysCuentas, 'Inmuebles',$fechaInicioConsulta, $fechaFinConsulta,$totalPatrimonio);              
                $arrayPrefijos=[];
                initializeConcepto($arrayPrefijos,'1206020','');
                //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
                //$rodados = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Rodados',$fechaInicioConsulta,$fechaFinConsulta,$totalActivos);
                $patrodados = mostrarBienDeUsoTercera($arrayCuentasxPeriodos, $arrayPrefijos, $keysCuentas, 'Rodados',$fechaInicioConsulta, $fechaFinConsulta,$totalPatrimonio);
                $arrayPrefijos=[];
                initializeConcepto($arrayPrefijos,'1206030','');
                //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
                //$instalaciones = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Instalaciones',$fechaInicioConsulta,$fechaFinConsulta,$totalActivos);
                $patinstalaciones = mostrarBienDeUsoTercera($arrayCuentasxPeriodos, $arrayPrefijos, $keysCuentas, 'Instalaciones',$fechaInicioConsulta, $fechaFinConsulta,$totalPatrimonio);
                $arrayPrefijos=[];
                initializeConcepto($arrayPrefijos,'1206040','');
                initializeConcepto($arrayPrefijos,'1206050','');
                initializeConcepto($arrayPrefijos,'1206060','');
                initializeConcepto($arrayPrefijos,'1206070','');
                initializeConcepto($arrayPrefijos,'1206080','');
                initializeConcepto($arrayPrefijos,'1209000','');
                //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
                //$otrosbienesdeuso = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Otros bienes de uso',$fechaInicioConsulta,$fechaFinConsulta,$totalActivos);
                $patotrosBienesDeUso = mostrarBienDeUsoTercera($arrayCuentasxPeriodos, $arrayPrefijos, $keysCuentas, 'Otros bienes de uso',$fechaInicioConsulta, $fechaFinConsulta,$totalPatrimonio);
                $arrayPrefijos=[];
                initializeConcepto($arrayPrefijos,'120800','');
                initializeConcepto($arrayPrefijos,'1210000','');
                //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
                $patbienesintangibles = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Bienes Intangibles',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            }
            //Patrimonio NO Tercera
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301010','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $patinmueble = mostrarBienDeUso($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Inmuebles',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'130102','');
            $patderechosreales = mostrarPatrimonio($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Derechos Reales',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301030','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $patautomotores = mostrarBienDeUso($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Automotores',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301040','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $patnavesyates = mostrarBienDeUso($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Naves, Yates y similares',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301050','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $pataeronaves = mostrarBienDeUso($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Aeronave',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
          
            
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            //$empresas = mostrarPatrimonio($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Patrimonio de Eampresas o Explotación Unipersonal',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            //if($patrimonioNeto[$periodoActual]!=0||$patrimonioNeto['apertura']){ 
                ?>
                <tr class="trnoclickeable trTitle">
                    <th colspan="4">Patrimonio de Empresas o Explotación Unipersonal</th>
                    <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
                </tr>
                <?php
                $totalPatrimonio['apertura'] += $patrimonioNeto['apertura'];
                $totalPatrimonio[$periodoActual] += $patrimonioNeto[$periodoActual];
                $totalPatrimonio['bienpersonal'] += $patrimonioNeto['bienpersonal'];
                $totalPatrimonio['exento'] += $patrimonioNeto['exento'];
                ?>
                <tr>
                    <td colspan="4">Patrimonio de Empresas o Explotación Unipersonal</td>
                    <td class="tdWithNumber"><?php echo number_format($patrimonioNeto['apertura'], 2, ",", ".") ?></td>
                    <td class="tdWithNumber"><?php echo number_format($patrimonioNeto[$periodoActual], 2, ",", ".") ?></td>                
                    <td class="tdWithNumber"><?php echo number_format($patrimonioNeto['bienpersonal'], 2, ",", ".") ?></td>                
                    <td class="tdWithNumber"><?php echo number_format($patrimonioNeto['exento'], 2, ",", ".") ?></td>                
                </tr>
                <?php
                $arrayPrefijos=[];
                initializeConcepto($arrayPrefijos,'1301060','');
                //Preguntar esto se carga en el SYS o tiene que salir del calculo anterior $patrimonioNeto
                $patparticipacionempresa = mostrarPatrimonio($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Empresa Unipersonal',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);            
                ?>
                <tr>
                    <th colspan="4"> Subtotal Patrimonio de Eampresas o Explotación Unipersonal</th>
                    <td class="tdWithNumber"><?php echo number_format($patrimonioNeto['apertura']+$patparticipacionempresa['apertura'], 2, ",", ".") ?></td>
                    <td class="tdWithNumber"><?php echo number_format($patrimonioNeto[$periodoActual]+$patparticipacionempresa['periodoActual'], 2, ",", ".") ?></td>            
                    <td class="tdWithNumber"><?php echo number_format($patrimonioNeto['bienpersonal']+$patparticipacionempresa['bienpersonal'], 2, ",", ".") ?></td>            
                    <td class="tdWithNumber"><?php echo number_format($patrimonioNeto['exento']+$patparticipacionempresa['exento'], 2, ",", ".") ?></td>            
                </tr>            
                <?php             
            //}            
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301070','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $pataccionesconcotizacion = mostrarPatrimonio($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Acciones/ Fondos Comun Inv/Oblig. Negociable con Contizacion',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301080','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $pataccionessincontizacion = mostrarPatrimonio($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Acciones/ Cuotas/ Participaciones sociales sin cotizacion',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301090','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $pattitulossincotizacion = mostrarPatrimonio($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Titulos publicos y privados sin cotizacion',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301100','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $pattitulosconcotizacion = mostrarPatrimonio($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Titulos publicos y privados con cotizacion',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301110','');
            if(!$tienetercera){
                //initializeConcepto($arrayPrefijos,'1103','');
                //initializeConcepto($arrayPrefijos,'1104','');
            }
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $patcreditospf = mostrarPatrimonio($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Creditos_PF',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301120','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $patdepositosdinero = mostrarPatrimonio($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Depositos en dinero',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301130','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $patdepositosefectivo = mostrarPatrimonio($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Dinero en Efectivo',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301140','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $patbienesmueblesregistrables = mostrarBienDeUso($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Bienes Muebles Registrables',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);
            $arrayPrefijos=[];
            initializeConcepto($arrayPrefijos,'1301150','');
            //esto esta como el culo por que un bien de uso tiene una sola linea no dos(una v/o y otra actualizacion
            $patotrosbienes = mostrarBienDeUso($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Otros bienes',$fechaInicioConsulta,$fechaFinConsulta,$totalPatrimonio);     
            ?>
            <tr class="trTitle">
                <th colspan="4">Subtotal Patrimonio en el pais</th>
                <th colspan="1" class="tdWithNumber"><?php echo  number_format($totalPatrimonio['apertura'], 2, ",", ".")?></th>
                <th colspan="1" class="tdWithNumber"><?php echo number_format($totalPatrimonio[$periodoActual], 2, ",", ".")?></th>
                <th colspan="1" class="tdWithNumber"><?php echo number_format($totalPatrimonio['bienpersonal'], 2, ",", ".")?></th>
                <th colspan="1" class="tdWithNumber"><?php echo number_format($totalPatrimonio['exento'], 2, ",", ".")?></th>
            </tr>
            <tr class="trTitle">
                <th colspan="4">Deudas en el pais</th>
                <th colspan="1"></th>
                <th colspan="1"></th>
                <th colspan="1"></th>
                <th colspan="1"></th>
            </tr>
            <?php
            $totalDeudaPais=[];
            if(!$tienetercera){
                $arrayPrefijos=[];
                initializeConcepto($arrayPrefijos,'2101001','');
                initializeConcepto($arrayPrefijos,'2101002','');
                initializeConcepto($arrayPrefijos,'2201001','');
                initializeConcepto($arrayPrefijos,'2201002','');
                $deudascomerciales = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Deudas Comerciales',$fechaInicioConsulta,$fechaFinConsulta,$totalDeudaPais);
                $arrayPrefijos=[];
                initializeConcepto($arrayPrefijos,'21021','');
                initializeConcepto($arrayPrefijos,'21022','');
                initializeConcepto($arrayPrefijos,'21023','');
                initializeConcepto($arrayPrefijos,'22021','');
                initializeConcepto($arrayPrefijos,'22022','');
                initializeConcepto($arrayPrefijos,'22023','');
                $deudasfinancieras = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Deudas Financieras',$fechaInicioConsulta,$fechaFinConsulta,$totalDeudaPais);
                $arrayPrefijos=[];
                initializeConcepto($arrayPrefijos,'2103','');
                initializeConcepto($arrayPrefijos,'2203','');
                $deudassociales = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Deudas Sociales',$fechaInicioConsulta,$fechaFinConsulta,$totalDeudaPais);
                $arrayPrefijos=[];
                initializeConcepto($arrayPrefijos,'2104','');
                initializeConcepto($arrayPrefijos,'2204','');
                $deudasfiscales = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Deudas Fiscales',$fechaInicioConsulta,$fechaFinConsulta,$totalDeudaPais);
                $arrayPrefijos=[];
                initializeConcepto($arrayPrefijos,'2107','');
                initializeConcepto($arrayPrefijos,'2207','');
                $otrasdeudas = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Otras Deudas',$fechaInicioConsulta,$fechaFinConsulta,$totalDeudaPais);
                $arrayPrefijos=[];
                initializeConcepto($arrayPrefijos,'2108','');
                initializeConcepto($arrayPrefijos,'2208','');
                $previsiones = mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,'Previsiones',$fechaInicioConsulta,$fechaFinConsulta,$totalDeudaPais,2);
            }
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
                <th colspan="4">Subtotal deudas en el pais</th>
                <th colspan="1" class="tdWithNumber"><?php echo  number_format($totalDeudaPais['apertura'], 2, ",", ".")?></th>
                <th colspan="1" class="tdWithNumber"><?php echo number_format($totalDeudaPais[$periodoActual], 2, ",", ".")?></th>
                <th colspan="1" class="tdWithNumber"><?php echo number_format($totalDeudaPais['bienpersonal'], 2, ",", ".")?></th>
                <th colspan="1" class="tdWithNumber"><?php echo number_format($totalDeudaPais['exento'], 2, ",", ".")?></th>
            </tr>
            <tr class="trTitle">
                <th colspan="4">Patrimonio Neto</th>
                <?php
                $patrimonioNetoPF=[];
                $patrimonioNetoPF['apertura']=$totalPatrimonio['apertura']+$totalDeudaPais['apertura'];
                $patrimonioNetoPF[$periodoActual]=$totalPatrimonio[$periodoActual]+$totalDeudaPais[$periodoActual];
                $patrimonioNetoPF['bienpersonal']=$totalPatrimonio['bienpersonal']+$totalDeudaPais['bienpersonal'];
                $patrimonioNetoPF['exento']=$totalPatrimonio['exento']+$totalDeudaPais['exento'];
                ?>
                <th colspan="1" class="tdWithNumber"><?php echo  number_format($patrimonioNetoPF['apertura'], 2, ",", ".")?></th>
                <th colspan="1" class="tdWithNumber"><?php echo number_format($patrimonioNetoPF[$periodoActual], 2, ",", ".")?></th>
                <th colspan="1" class="tdWithNumber"><?php echo number_format($patrimonioNetoPF['bienpersonal'], 2, ",", ".")?></th>
                <th colspan="1" class="tdWithNumber"><?php echo number_format($patrimonioNetoPF['exento'], 2, ",", ".")?></th>
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
            $ingresosGravados['primera']=(isset($totalIngresos1ra['Gravado IVA']))?$totalIngresos1ra['Gravado IVA']:0;
            $ingresosGravados['primera']+=(isset($totalIngresos1ra['No Gravado IVA']))?$totalIngresos1ra['No Gravado IVA']:0;
            $ingresosGravados['segunda']=(isset($totalIngresos2da['Gravado IVA']))?$totalIngresos2da['Gravado IVA']:0;
            $ingresosGravados['segunda']+=(isset($totalIngresos2da['No Gravado IVA']))?$totalIngresos2da['No Gravado IVA']:0;
            //$ingresosGravados['segunda']+=(isset($totalIngresos2da['Vts. acciones etc.']))?$totalIngresos2da['Vts. acciones etc.']:0;
            $ingresosGravados['tercera']=(isset($totalIngresos3ra['Gravado IVA']))?$totalIngresos3ra['Gravado IVA']:0;
            $ingresosGravados['tercera']+=(isset($totalIngresos3ra['No Gravado IVA']))?$totalIngresos3ra['No Gravado IVA']:0;
            $ingresosGravados['cuarta']=(isset($totalIngresos4ta['Gravado IVA']))?$totalIngresos4ta['Gravado IVA']:0;
            $ingresosGravados['cuarta']+=(isset($totalIngresos4ta['No Gravado IVA']))?$totalIngresos4ta['No Gravado IVA']:0;
            $resultadoneto['primera']= $ingresosGravados['primera'];
            $resultadoneto['segunda']= $ingresosGravados['segunda'];
            $resultadoneto['tercera']= $ingresosGravados['tercera'];
            $resultadoneto['cuarta']= $ingresosGravados['cuarta'];
            $ingresoGravadoTotal = $ingresosGravados['primera']+$ingresosGravados['segunda']+$ingresosGravados['tercera']+$ingresosGravados['cuarta'];
            $participacionEnEmpresaTotal = isset($totalGastos3raEmpresa['resultadoneto'])?$totalGastos3raEmpresa['resultadoneto']:0;
            $resultadoneto['tercera']+= $participacionEnEmpresaTotal;
            
            $egresosGravados=[];            
            $egresosGravados['primera']=(isset($totalGastos1ra['Que Imp. Erog.']))?$totalGastos1ra['Que Imp. Erog.']:0;
            $egresosGravados['segunda']=(isset($totalGastos2da['Que Imp. Erog.']))?$totalGastos2da['Que Imp. Erog.']:0;
            //$egresosGravados['segunda']-=(isset($totalGastos2da['Instrumentos Fcieros. deriv.']))?$totalGastos2da['Instrumentos Fcieros. deriv.']:0;
            //$egresosGravados['segunda']-=(isset($totalGastos2da['Vts. acciones etc.']))?$totalGastos2da['Vts. acciones etc.']:0;
            $egresosGravados['tercera']=(isset($totalGastos3ra['Que Imp. Erog.']))?$totalGastos3ra['Que Imp. Erog.']:0;
            $egresosGravados['cuarta']=(isset($totalGastos4ta['Que Imp. Erog.']))?$totalGastos4ta['Que Imp. Erog.']:0;
            $resultadoneto['primera']-= $egresosGravados['primera'];
            $resultadoneto['segunda']-= $egresosGravados['segunda'];
            $resultadoneto['tercera']-= $egresosGravados['tercera'];
            $resultadoneto['cuarta']-= $egresosGravados['cuarta'];
            $gastosGravadosTotal = $egresosGravados['primera']+$egresosGravados['segunda']+$egresosGravados['tercera']+$egresosGravados['cuarta'];
            $resultadoNetoTotal=$resultadoneto['primera']+$resultadoneto['segunda']+$resultadoneto['tercera']+$resultadoneto['cuarta'];
            
            //Debugger::dump($ingresosGravados);
            //Debugger::dump($egresosGravados);
            //Debugger::dump($resultadoneto);
            
            $minimoNoImponible=[
                2016=>42318,
                2017=>51967,
                2018=>51967
            ];
            $conyugueNoImponible=[
                2016=>39778,
                2017=>48447,
                2018=>48447,
            ];
            $hijoNoImponible=[
                2016=>19889,
                2017=>24432,
                2018=>24432,
            ];
            $otrascargasNoImponible=[
                2016=>19889,
                2017=>24432,
                2018=>24432,
            ];
            $montoActualGanancias = $minimoNoImponible[$periodoActual]; //minimo no imponible
            $montoActualConyugue = $conyugueNoImponible[$periodoActual];
            $montoActualHijo = $hijoNoImponible[$periodoActual];
            $montoActualOtrasCargas = $otrascargasNoImponible[$periodoActual];
?>
<div class="index estadocontable" id="divQuebrantos" >
    <a href="#"  onclick="loadFormImpuestoQuebrantos(<?php echo $cliente['Impcli'][0]['id']; ?>,'<?php echo $periodo; ?>')" class="button_view">
        <?php echo $this->Html->image('quebranto.png', array('alt' => 'quebranto','class'=>'img_edit','style'=>'width: 20px;'));?>
    </a>
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
                    Quebrantos
                </td>               
            </tr>
        </thead>
        <tbody>    
            <tr class="trTitle">
                <th colspan="4">Quebrantos</th>
            </tr>
            <tr class="trTitle">
                <th colspan="1">A&ntilde;o Fiscal</th>
                <th colspan="1">Quebranto</th>
                <th colspan="1">Importe computable</th>
                <th colspan="1">Importe trasladable</th>
            </tr>
            <?php
            $resultadoNetoTotal;
            $QuebrantoComputable = $resultadoNetoTotal>0?$resultadoNetoTotal:0;
            $totalcomputado=0;
            $totalquebranto=0;
            $totaltrasladable=0;
            foreach ($cliente['Impcli'][0]['Quebranto'] as $kque => $quebranto) {
                ?>
                <tr>
                    <td><?php echo $quebranto['periodogenerado'];?></td>
                    <td class="tdWithNumber"><?php echo number_format($quebranto['monto'], 2, ".", "");?></td>
                    <?php
                    $title="";
                    $titleTrasladable="";
                    $styletd="";
                    $styletdtrasladable="";
                        if($QuebrantoComputable>=$quebranto['monto']){
                           
                            if($quebranto['usado']==$quebranto['monto']){
                                $title="Se puede computar $".$QuebrantoComputable." todavia, asi que se incluyo todo el quebranto en el ejercicio";
                                 $QuebrantoComputable-=$quebranto['monto'];
                                 
                            }else{
                                $title="Se puede computar ".($quebranto['monto']-$quebranto['usado'])." todavia, por favor corrija aumentando en este monto el quebranto computado";
                                $styletd="background-color: #E6B800";
                                $QuebrantoComputable-=$quebranto['usado'];
                            }
                        }else{
                            if($quebranto['usado']>$QuebrantoComputable){
                                $title="Solo se puede computar ".$QuebrantoComputable." en este periodo por favor corrija";
                                $styletd="background-color: #E6B800";
                                $QuebrantoComputable=0;
                            }else{
                                $title="Se puede computar ".($quebranto['monto']-$quebranto['usado'])." todavia, por favor corrija aumentando en este monto el quebranto computado";
                                $styletd="background-color: #E6B800";
                                $QuebrantoComputable-=$quebranto['usado'];
                            }
                             
                        }
                    ?>
                    <td title="<?php echo $title?>" style="<?php echo $styletd;?>"><?php  echo  number_format($quebranto['usado'], 2, ".", "");?></td>                                               
                        <?php
                        if(($quebranto['monto']-$quebranto['usado'])!=$quebranto['saldo']){
                            $titleTrasladable="Este importe trasladable deberia ser ".$quebranto['monto']-$quebranto['usado'];
                            $styletdtrasladable="background-color: #E6B800";
                        }else{
                            
                        }                        
                        ?>
                    <td title="<?php echo $titleTrasladable?>" style="<?php echo $styletdtrasladable;?>"><?php echo number_format($quebranto['saldo'], 2, ".", "");?></td>
                </tr>
                <?php
                $totalcomputado+=$quebranto['usado'];
                $totalquebranto+=$quebranto['monto'];
                $totaltrasladable+=$quebranto['saldo'];
            }
            ?>
            <tr class="trTitle">
                <th colspan="1"></th>
                <th colspan="1"><?php echo number_format($totalquebranto, 2, ".", "");?></th>
                <th colspan="1"><?php echo number_format($totalcomputado, 2, ".", "");?></th>
                <th colspan="1"><?php echo number_format($totaltrasladable, 2, ".", "");?></th>
            </tr>
        </tbody>
    </table>
</div>
<div  style="width:100%;height: 1px; page-break-before:always"></div>
<div class="index estadocontable" id="divDeduccionesGenerales" >
    <table class="toExcelTable tbl_border tblEstadoContable splitForPrint noprint">
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
                    <div class="index" id="AsientoDeduccionesGenerales">
                        <h3><?php echo __('Asiento de Deducciones Generales')?></h3>
                        <?php
                        $id = 0;
                        $peanioBDU = date('Y',strtotime($fechaInicioConsulta));
                        $nombre = "Deducciones Generales: 12-".$peanioBDU;
                        $descripcion = "Manual";
                        $fecha = date('t-12-Y', strtotime($fechaInicioConsulta));
                        $miAsiento=array();
                        $totalDebe=0;
                        $totalHaber=0;
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

                        echo $this->Form->create('Asiento',[
                            'class'=>'formTareaCarga formAsiento',
                            'action'=>'add',
                            'style'=>' min-width: max-content;',
                            'id'=>'AsientoDeduccionGeneral']);
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
                            2776=>-1,
                            2778=>$resultadoNetoTotal5,
                            2779=>$resultadoNetoTotal5,
                            2780=>-1,
                            2782=>$resultadoNetoTotal5,
                            2783=>"20.000,",
                            2789=>$montoActualGanancias,
                        ];
                        //aca vamos a guardar los valores de la contabilidad para despues rellenarlos cuando mostremos el asiento
                        $valorContabilidad=[
                            2773=>sumarCuentasEnPeriodo($arrayCuentasxPeriodos,['514000001'],$periodoActual,$keysCuentas,'noDedGeneral',1),
                            2774=>sumarCuentasEnPeriodo($arrayCuentasxPeriodos,['514000002'],$periodoActual,$keysCuentas,'noDedGeneral',1),
                            2775=>sumarCuentasEnPeriodo($arrayCuentasxPeriodos,['514000003'],$periodoActual,$keysCuentas,'noDedGeneral',1),
                            2776=>sumarCuentasEnPeriodo($arrayCuentasxPeriodos,['514000004'],$periodoActual,$keysCuentas,'noDedGeneral',1),
                            2777=>sumarCuentasEnPeriodo($arrayCuentasxPeriodos,['514000005'],$periodoActual,$keysCuentas,'noDedGeneral',1),
                            2778=>sumarCuentasEnPeriodo($arrayCuentasxPeriodos,['514000006'],$periodoActual,$keysCuentas,'noDedGeneral',1),
                            2779=>sumarCuentasEnPeriodo($arrayCuentasxPeriodos,['514000007'],$periodoActual,$keysCuentas,'noDedGeneral',1),
                            2780=>sumarCuentasEnPeriodo($arrayCuentasxPeriodos,['514000008'],$periodoActual,$keysCuentas,'noDedGeneral',1),
                            2782=>sumarCuentasEnPeriodo($arrayCuentasxPeriodos,['514000010'],$periodoActual,$keysCuentas,'noDedGeneral',1),
                            2783=>sumarCuentasEnPeriodo($arrayCuentasxPeriodos,['514000011'],$periodoActual,$keysCuentas,'noDedGeneral',1),
                            2784=>sumarCuentasEnPeriodo($arrayCuentasxPeriodos,['514000012'],$periodoActual,$keysCuentas,'noDedGeneral',1),
                            2785=>sumarCuentasEnPeriodo($arrayCuentasxPeriodos,['514000013'],$periodoActual,$keysCuentas,'noDedGeneral',1),
                            2789=>sumarCuentasEnPeriodo($arrayCuentasxPeriodos,['514000017'],$periodoActual,$keysCuentas,'noDedGeneral',1),
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
                            $cuentaBlokeadaPorUsoEnContabilidad = false;
                            if(isset($valorContabilidad[$asientoestandar['Cuenta']['id']])){
                                if($valorContabilidad[$asientoestandar['Cuenta']['id']]!=0){
                                    $cuentaBlokeadaPorUsoEnContabilidad = true;
                                    if($valorContabilidad[$asientoestandar['Cuenta']['id']]<0){
                                       $haber=0;
                                    }else{
                                       $debe=0;
                                    }                                                                               
                                }
                            }
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
                            $label="&nbsp;";
                            if(isset($topeCuentaCliente[$asientoestandar['Cuenta']['id']])){
                                if($topeCuentaCliente[$asientoestandar['Cuenta']['id']]!=-1){
                                    $label="El tope de la cuenta es $".$topeCuentaCliente[$asientoestandar['Cuenta']['id']]."." ;                                
                                }else{
                                    $label="Sin tope." ;
                                }
                            }
                            if($cuentaBlokeadaPorUsoEnContabilidad){
                                $label.="Cuenta utilizada en la contabilidad. Desactivada para evitar duplicaciond de uso." ;
                            }
                            echo $this->Form->label('',$label);
                            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.id',['default'=>$movid]);
                            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.asiento_id',['default'=>$asiento_id,'type'=>'hidden']);
                            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.fecha',['default'=>$fecha,'type'=>'hidden']);
                            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuenta_id',['readonly'=>'readonly','type'=>'hidden','orden'=>$i,'value'=>$asientoestandar['Cuenta']['id'],'id'=>'cuenta'.$asientoestandar['Cuenta']['id']]);
                            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuentascliente_id',[
                                'default'=>$cuentaclienteid,
                                'options'=>$allcuentasclientes,
                                'label'=>false,
                                ]);
                           
                            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe',[
                                'class'=>'inputDebe',
                                'default'=>number_format($debe, 2, ".", ""),
                                'label'=>false,
                                'readonly'=>$cuentaBlokeadaPorUsoEnContabilidad,
                                ]);
                            echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber',[
                                'class'=>'inputHaber',                                
                                'default'=>number_format($haber, 2, ".", ""),
                                'label'=>false,                                
                                'readonly'=>$cuentaBlokeadaPorUsoEnContabilidad,                                
                                ]);
                            echo "</br>";
                            $i++;
                            $totalDebe+=$debe;
                            $totalHaber+=$haber;
                        }                                          
                        echo $this->Form->end('Guardar asiento');
                        //Debugger::dump($miAsiento['Movimiento']);
                      
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
            $tope=100000 ;
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
<?php 
    $subtotalImpositivo=$resultadoNetoTotal-$totalDeudasGenerales['computable'];
    $resultadoImpositivo=$subtotalImpositivo-$totalDeudasGenerales5['computable'];
    $resultadoFinal=$resultadoImpositivo-$totalcomputado;

 //Datos auxiliares para calculo tope deduccion especial
$integracion4ta = 0;
$totalGastos3raEmpresaresultadoneto=isset($totalGastos3raEmpresa['resultadoneto'])?$totalGastos3raEmpresa['resultadoneto']:0;
if($resultadoFinal>=($resultadoneto['cuarta']+$totalGastos3raEmpresaresultadoneto)){
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
                <td class="tdWithNumber"><?php echo number_format($montoActualGanancias, 2, ",", ".")?></td>
                <td class="tdWithNumber"><?php echo number_format($gananciaNoImponible, 2, ",", ".")?></td>
                <td class="tdWithNumber"><?php 
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
            $tieneDeduccionIncrementada=false;
            foreach ($cliente['Impcli'][0]['Deduccione'] as $kded => $deduccion) {
                if(
                        $deduccion['clase']=='Deduccion especial'){
                    $tieneDeduccionEspecial=true;
                }
                if(
                        $deduccion['clase']=='Deduccion incrementada'){
                    $tieneDeduccionIncrementada=true;
                }
            }
            $montoDeduccionEspecialSimple = $montoActualGanancias*($tieneDeduccionEspecial?1:0);   
            //aca falta diferenciar estos dos por que no se activan juntos
            $montoDeduccionEspecialIncrementada = $montoActualGanancias*($tieneDeduccionIncrementada?1:0)*4.8;
            if($resultadoFinal<=0){
                $deduccionEspecialDeducible=0;
            }else{
                if($tieneDeduccionIncrementada){
                    if($integracion4ta>=$montoDeduccionEspecialIncrementada){
                        $deduccionEspecialDeducible=$montoDeduccionEspecialIncrementada;
                    }else{
                        $deduccionEspecialDeducible=$integracion4ta;
                    }
                }
                if($tieneDeduccionEspecial&&!$tieneDeduccionIncrementada){                                        
                    if($integracion4ta<=$montoDeduccionEspecialSimple){
                        $deduccionEspecialDeducible=$montoDeduccionEspecialSimple;
                    }else{
                        $deduccionEspecialDeducible=$integracion4ta;
                    }
                }              
            }
           
            ?>
            <tr>
                <td>Deducci&oacute;n especial</td>              
                <td><?php echo $tieneDeduccionEspecial?'SI':'NO';?></td>
                <td></td>
                <td></td>
                <td class="tdWithNumber"><?php echo number_format($deduccionEspecialDeducible, 2, ",", ".")?></td>
            </tr>
            <?php
            if($tieneDeduccionEspecial){
            ?>
            <tr>
                <td>Simple</td>                
                <td> </td>
                <td class="tdWithNumber"><?php echo number_format($montoDeduccionEspecialSimple, 2, ",", ".")?></td>
                <td class="tdWithNumber"><?php echo number_format($montoDeduccionEspecialSimple, 2, ",", ".")?></td>
                <td> </td>
            </tr>
            <tr>
                <td>Incrementada</td>
                <td> </td>
                <td class="tdWithNumber"><?php echo number_format($montoDeduccionEspecialIncrementada, 2, ",", ".")?></td>
                <td class="tdWithNumber"><?php echo number_format($montoDeduccionEspecialIncrementada, 2, ",", ".")?></td>
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
                <td class="tdWithNumber"><?php echo number_format($montoActualConyugue, 2, ",", ".")?></td>
                <td class="tdWithNumber"><?php echo number_format($totalConyugue, 2, ",", ".")?></td>
                <td class="tdWithNumber"><?php
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
                <td class="tdWithNumber"><?php echo $cantidadHijos?></td>
                <td class="tdWithNumber"><?php echo number_format($montoActualHijo, 2, ",", ".")?></td>
                <td class="tdWithNumber"><?php echo number_format($totalHijos, 2, ",", ".")?></td>
                <td class="tdWithNumber"><?php
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
                <td class="tdWithNumber"><?php echo $cantidadOtrasCargas?></td>
                <td class="tdWithNumber"><?php echo number_format($montoActualOtrasCargas, 2, ",", ".")?></td>
                <td class="tdWithNumber"><?php echo number_format($totalOtrasCargas, 2, ",", ".")?></td>
                <td class="tdWithNumber"><?php
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
                <td class="tdWithNumber"><?php
                    $totalDeduccionesPersonalesComputables=
                            $gananciaNoImponibleDeducible+
                            $deduccionEspecialDeducible+
                            $totalConyugueDeducible+
                            $totalHijosDeducible+
                            $totalOtrasCargasDeducible;              
                    if($resultadoFinal<$totalDeduccionesPersonalesComputables){
                        $totalDeduccionesPersonalesComputables = $resultadoFinal;
                    }
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
                <td colspan="3">
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
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($ingresoGravadoTotal, 2, ",", ".")?></td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($ingresosGravados['primera'], 2, ",", ".")?> </td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($ingresosGravados['segunda'], 2, ",", ".")?> </td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($ingresosGravados['tercera'], 2, ",", ".")?> </td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($ingresosGravados['cuarta'], 2, ",", ".")?> </td>
            </tr>
            <tr class="">
                <th colspan="1">  </th>
                <th colspan="1">  </th>
                <td colspan="1"> Total de participaci&oacute;n en empresas</td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($participacionEnEmpresaTotal, 2, ",", ".")?></td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format(0, 2, ",", ".")?> </td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format(0, 2, ",", ".")?> </td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($participacionEnEmpresaTotal, 2, ",", ".")?> </td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format(0, 2, ",", ".")?> </td>
            </tr>
            <?php
            
          
            ?>
            <tr class="">
                <th colspan="1">  </th>
                <th colspan="1">  </th>
                <td colspan="1"> Gastos y deducciones especialmente admitidos que consumen fondos </td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($gastosGravadosTotal, 2, ",", ".")?></td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($egresosGravados['primera'], 2, ",", ".")?> </td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($egresosGravados['segunda'], 2, ",", ".")?> </td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($egresosGravados['tercera'], 2, ",", ".")?> </td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($egresosGravados['cuarta'], 2, ",", ".")?> </td>
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
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($resultadoNetoTotal, 2, ",", ".")?></td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($resultadoneto['primera'], 2, ",", ".")?></td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($resultadoneto['segunda'], 2, ",", ".")?> </td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($resultadoneto['tercera'], 2, ",", ".")?></td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($resultadoneto['cuarta'], 2, ",", ".")?></td>
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
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($resultadoNetoTotal, 2, ",", ".")?></td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($resultadoneto['primera'], 2, ",", ".")?></td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($resultadoneto['segunda'], 2, ",", ".")?> </td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($resultadoneto['tercera'], 2, ",", ".")?></td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($resultadoneto['cuarta'], 2, ",", ".")?></td>
            </tr>
            <tr class="trTitle">
                <th colspan="3"> Ganancia (Quebranto) neto total </th>                
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($resultadoNetoTotal, 2, ",", ".")?></td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($resultadoneto['primera'], 2, ",", ".")?></td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($resultadoneto['segunda'], 2, ",", ".")?> </td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($resultadoneto['tercera'], 2, ",", ".")?></td>
                <td colspan="1" class="tdWithNumber"> <?php echo number_format($resultadoneto['cuarta'], 2, ",", ".")?></td>
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
                <td><?php echo ($resultadoNetoTotal>=0)?"Ganancia":"Quebranto";?> neto total</td>
                <td class="tdWithNumber"><?php echo number_format($resultadoNetoTotal, 2, ",", ".")?></td>
            </tr>
            <tr>
                <td></td>
                <td>Desgravaciones</td>
                <td class="tdWithNumber"><?php echo number_format($resultadoNetoTotal, 2, ",", ".")?></td>
            </tr>
            <tr>
                <td></td>
                <td>Deducciones Generales</br>Excepto tope 5%</td>
                <td class="tdWithNumber"><?php echo number_format($totalDeudasGenerales['computable']*-1, 2, ",", ".")?></td>
            </tr>
            <tr>
                <td></td>
                <td>Subtotal</td>
                <td class="tdWithNumber"><?php 
                echo number_format($subtotalImpositivo, 2, ",", ".")?></td>
            </tr>
            <tr>
                <td></td>
                <td>Ded. Grales. con tope 5% sobre el Subtotal anterior</td>
                <td class="tdWithNumber"><?php
                echo number_format($totalDeudasGenerales5['computable']*-1, 2, ",", ".")?></td>
            </tr>
            <tr>
                <td></td>
                <td>Resultado Impositivo</td>
                <td class="tdWithNumber"><?php echo number_format($resultadoImpositivo, 2, ",", ".")?></td>
            </tr>
            <tr>
                <td></td>
                <td>Quebrantos de ejercicios anteriores, computables</td>
                <td class="tdWithNumber"><?php echo number_format($totalcomputado, 2, ",", ".")?></td>
            </tr>
            <tr>
                <td></td>
                <td>Resultado Final</td>
                <td class="tdWithNumber"><?php                 
                echo number_format($resultadoFinal, 2, ",", ".")?></td>
            </tr>
            <tr>
                <td></td>
                <td>Deducciones Personales</td>
                <td class="tdWithNumber"><?php echo number_format($totalDeduccionesPersonalesComputables*-1, 2, ",", ".")?></td>
            </tr>
            <tr class="trTitle">
                <td></td>
                <td>Ganancia neta sujeta a impuesto</td>
                <td class="tdWithNumber"><?php 
                    $ganancianetasujetaaimpuesto = 0;
                    
                    $ganancianetasujetaaimpuesto = $resultadoFinal-$totalDeduccionesPersonalesComputables;
                    
                echo number_format($ganancianetasujetaaimpuesto, 2, ",", ".")?></td>
            </tr>
            <?php
            $tablaAlicuotaGanancias=
                [
                    "2016"=>[
                        [
                                'desde'=>0,
                                'hasta'=>10000,
                                'alicuota'=>9,
                                'resta'=>0,
                                'base'=>0,
                        ],
                        [
                                'desde'=>10000,
                                'hasta'=>20000,
                                'alicuota'=>14,
                                'resta'=>500,
                                'base'=>0,
                        ],
                        [
                                'desde'=>20000,
                                'hasta'=>30000,
                                'alicuota'=>19,
                                'resta'=>1500,
                                'base'=>0,
                        ],
                        [
                                'desde'=>30000,
                                'hasta'=>60000,
                                'alicuota'=>23,
                                'resta'=>2700,
                                'base'=>0,
                        ],
                        [
                                'desde'=>60000,
                                'hasta'=>90000,
                                'alicuota'=>27,
                                'resta'=>5100,
                                'base'=>0,
                        ],
                        [
                                'desde'=>90000,
                                'hasta'=>120000,
                                'alicuota'=>31,
                                'resta'=>8700,
                                'base'=>0,
                        ],
                        [
                                'desde'=>120000,
                                'hasta'=>999999999,
                                'alicuota'=>35,
                                'resta'=>13500,
                                'base'=>0,
                        ]
                            ],
                    "2017"=>[
                        [
                                'desde'=>0,
                                'hasta'=>20000,
                                'alicuota'=>5,
                                'base'=>0,
                                'resta'=>0,
                        ],
                        [
                                'desde'=>20000,
                                'hasta'=>40000,
                                'alicuota'=>9,
                                'base'=>1000,
                                'resta'=>0,
                        ],
                        [
                                'desde'=>40000,
                                'hasta'=>60000,
                                'alicuota'=>12,
                                'base'=>2800,
                                'resta'=>0,
                        ],
                        [
                                'desde'=>60000,
                                'hasta'=>80000,
                                'alicuota'=>15,
                                'base'=>5200,
                                'resta'=>0,
                        ],
                        [
                                'desde'=>80000,
                                'hasta'=>120000,
                                'alicuota'=>19,
                                'base'=>8200,
                                'resta'=>0,
                        ],
                        [
                                'desde'=>120000,
                                'hasta'=>160000,
                                'alicuota'=>23,
                                'base'=>15800,
                                'resta'=>0,
                        ],
                        [
                                'desde'=>160000,
                                'hasta'=>240000,
                                'alicuota'=>27,
                                'base'=>25000,
                                'resta'=>0,
                        ],
                        [
                                'desde'=>240000,
                                'hasta'=>320000,
                                'alicuota'=>31,
                                'base'=>46600,
                                'resta'=>0,
                        ],
                        [
                                'desde'=>320000,
                                'hasta'=>999999999,
                                'alicuota'=>35,
                                'base'=>71400,
                                'resta'=>0,
                        ]
                    ],
                    "2018"=>[
                        [
                                'desde'=>0,
                                'hasta'=>20000,
                                'alicuota'=>5,
                                'base'=>0,
                                'resta'=>0,
                        ],
                        [
                                'desde'=>20000,
                                'hasta'=>40000,
                                'alicuota'=>9,
                                'base'=>1000,
                                'resta'=>0,
                        ],
                        [
                                'desde'=>40000,
                                'hasta'=>60000,
                                'alicuota'=>12,
                                'base'=>2800,
                                'resta'=>0,
                        ],
                        [
                                'desde'=>60000,
                                'hasta'=>80000,
                                'alicuota'=>15,
                                'base'=>5200,
                                'resta'=>0,
                        ],
                        [
                                'desde'=>80000,
                                'hasta'=>120000,
                                'alicuota'=>19,
                                'base'=>8200,
                                'resta'=>0,
                        ],
                        [
                                'desde'=>120000,
                                'hasta'=>160000,
                                'alicuota'=>23,
                                'base'=>15800,
                                'resta'=>0,
                        ],
                        [
                                'desde'=>160000,
                                'hasta'=>240000,
                                'alicuota'=>27,
                                'base'=>25000,
                                'resta'=>0,
                        ],
                        [
                                'desde'=>240000,
                                'hasta'=>320000,
                                'alicuota'=>31,
                                'base'=>46600,
                                'resta'=>0,
                        ],
                        [
                                'desde'=>320000,
                                'hasta'=>999999999,
                                'alicuota'=>35,
                                'base'=>71400,
                                'resta'=>0,
                        ]
                    ],
                ];
            ?>
            <tr class="trTitle">
                <td></td>
                <td>Impuesto Determinado</td>
                <td class="tdWithNumber"><?php 
                $impuestodeterminadofinal=0;
                foreach ($tablaAlicuotaGanancias[$periodoActual] as $tag) {
                    if($ganancianetasujetaaimpuesto>$tag['desde']&&$ganancianetasujetaaimpuesto<=$tag['hasta']){
                        
                        $exedente = $ganancianetasujetaaimpuesto*1 - $tag['desde']*1;
                        $impuestodeterminadofinal = ($exedente*$tag['alicuota']/100)-$tag['resta']+$tag['base'];
                    }
                }
                echo number_format($impuestodeterminadofinal,2,',','.');
                echo $this->Form->input('impuestodeterminadofinal',['type'=>'hidden','value'=>$impuestodeterminadofinal]);
                ?>
                </td>
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
                <td class="tdWithNumber"><?php echo number_format($integracionResto, 2, ",", ".")?></td>
                <td class="tdWithNumber"><?php echo number_format($integracion3raEmp, 2, ",", ".")?></td>
                <td class="tdWithNumber"><?php echo number_format($integracion4ta, 2, ",", ".")?></td>
                <td class="tdWithNumber"><?php echo number_format($integracion3raEmp+$integracion4ta, 2, ",", ".")?></td>
            </tr>           
        </tbody>
    </table>
     <table class="toExcelTable tbl_border tblEstadoContable splitForPrint">
        <tbody>
            <tr>
                <td colspan="4">Determinac&iacute;on del saldo de declaraci&oacute;n jurada</td>
            </tr>
            <tr>
                <td>Impuesto determinado</td>
                <td class="tdWithNumber"><?php echo number_format($impuestodeterminadofinal,2,',','.'); ?></td>
            </tr>
            <tr>
                <td>Menos:</td>
                <td class="tdWithNumber"></td>
            </tr>
            <tr>
                <td>Ganancias M&iacute;nima Presunta comp. a cta.</td>
                <?php 
                $gananciaMinimaPresunta =sumarCuentasEnPeriodo($arrayCuentasxPeriodos,['110403203'],$periodoActual,$keysCuentas,'todos',1);
                $retenciones=sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110403105"],$periodoActual,$keysCuentas,'todos',1);
                $percepciones=sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110403104"],$periodoActual,$keysCuentas,'todos',1);
                $anticiposacomputar=sumarCuentasEnPeriodo($arrayCuentasxPeriodos,['110403102'],$periodoActual,$keysCuentas,'todos',1);
                $ley25413=sumarCuentasEnPeriodo($arrayCuentasxPeriodos,['110403802'],$periodoActual,$keysCuentas,'todos',1);
                $saldoAFavorPeriodoAnterior=sumarCuentasEnPeriodo($arrayCuentasxPeriodos,['110403101'],$periodoActual,$keysCuentas,'todos',1);
                $pagosacuenta=sumarCuentasEnPeriodo($arrayCuentasxPeriodos,['110403106'],$periodoActual,$keysCuentas,'todos',1);
                
                $reduccionesQueGeneranSLD = $retenciones+$percepciones+$anticiposacomputar+$saldoAFavorPeriodoAnterior;
                $reduccionesQueNOGeneranSLD = $gananciaMinimaPresunta+$ley25413+$pagosacuenta;
                
                $saldo=$impuestodeterminadofinal;
                $saldoLD=0;
                $usadoley25413 = $ley25413;
                $usadogananciaMinimaPresunta = $gananciaMinimaPresunta;
                $usadoPagosACuenta = $gananciaMinimaPresunta;
                //tengo que usar primero las cuentas que no sumas SLD y despues las que si suman                
                if($saldo>0){
                    if( $saldo < $ley25413*1){
                        $usadoley25413 = $saldo*1;
                        $saldo = 0;
                    } else {
                        $saldo -= $ley25413*1;
                    }
                    if($saldo>0){
                        if( $saldo < $gananciaMinimaPresunta*1){
                            $usadogananciaMinimaPresunta = $saldo;
                            $saldo = 0;
                        }else{
                            $saldo-=$gananciaMinimaPresunta;
                        }
                        if($saldo>0){
                            if( $saldo < $pagosacuenta*1){
                                $usadoPagosACuenta = $saldo;
                                $saldo = 0;
                            }else{
                                $saldo -= $pagosacuenta*1;
                            }
                        }
                    }
                }
                if($reduccionesQueGeneranSLD<$saldo){
                    //las reducciones no alcanzan hay que usar las que no generan sld
                    $saldo -= $reduccionesQueGeneranSLD;
                    $saldoLD=0;
                }else{
                    $saldoLD = $reduccionesQueGeneranSLD-$saldo;
                    $saldo=0;
                }
                
               
                ?>
                <td class="tdWithNumber"><?php echo number_format($gananciaMinimaPresunta,2,',','.');?></td>
            </tr>
            <tr>
                <td>Ley 25413</td>
                <td class="tdWithNumber"><?php echo number_format($ley25413,2,',','.');?></td>
            </tr>
            <tr>
                <td>Retenciones</td>
                <td class="tdWithNumber"><?php echo number_format($retenciones,2,',','.');?></td>
            </tr>
            <tr>
                <td>Percepciones</td>
                <td class="tdWithNumber"><?php echo number_format($percepciones,2,',','.');?></td>
            </tr>
            <tr>
                <td>Anticipos a computar</td>
                <td class="tdWithNumber"><?php echo number_format($anticiposacomputar,2,',','.');?></td>
            </tr>            
            <tr>
                <td>Pagos a cuenta</td>
                <td class="tdWithNumber"><?php echo number_format($pagosacuenta,2,',','.');?></td>
            </tr>            
            <tr>
                <td>Saldo a favor cyte. Período ant.</td>
                <td class="tdWithNumber"><?php echo number_format($saldoAFavorPeriodoAnterior,2,',','.');?></td>
            </tr>
            <tr>
                <td>Saldo a pagar </td>
                <td class="tdWithNumber"><?php 
                $saldoAPagar = $saldo;
                echo number_format($saldoAPagar,2,',','.');?></td>
            </tr>
            <tr>
                <td>Saldo a favor contribuyente</td>
                <td class="tdWithNumber"><?php 
                $saldoAFavor = $saldoLD;
                echo number_format($saldoAFavor,2,',','.');?></td>
            </tr>
            <?php 
            echo $this->Form->input('gananciaMinimaPresunta',['type'=>'hidden','value'=>$gananciaMinimaPresunta]);
            echo $this->Form->input('ley25413',['type'=>'hidden','value'=>$ley25413]);
            echo $this->Form->input('retenciones',['type'=>'hidden','value'=>$retenciones]);
            echo $this->Form->input('percepciones',['type'=>'hidden','value'=>$percepciones]);
            echo $this->Form->input('anticiposacomputar',['type'=>'hidden','value'=>$anticiposacomputar]);
            echo $this->Form->input('pagosacuenta',['type'=>'hidden','value'=>$pagosacuenta]);
            echo $this->Form->input('saldoAFavorPeriodoAnterior',['type'=>'hidden','value'=>$saldoAFavorPeriodoAnterior]);
            echo $this->Form->input('saldoDeLibreDisponibilidadPeriodo',['type'=>'hidden','value'=>$saldoLD]);
            echo $this->Form->input('apagar',['type'=>'hidden','value'=>$saldo]);
?>
        </tbody>
    </table>
    <table class="toExcelTable tbl_border tblEstadoContable splitForPrint">
        <thead>
            <tr>
                <td colspan="4">Subtotal por Cuenta Bancaria Ley 25413</td>
            </tr>
        </thead>
        <tbody>
            <?php
            $cbuMovimientos = [];
            if(isset($cuentasLey25413['Movimientosbancario'])){
                foreach ($cuentasLey25413['Movimientosbancario'] as $kcl => $movimientos) {
                    if(!isset($cbuMovimientos[$movimientos['cbu_id']]))$cbuMovimientos[$movimientos['cbu_id']]=0;
                    $cbuMovimientos[$movimientos['cbu_id']]+=$movimientos['debito'];
                    $cbuMovimientos[$movimientos['cbu_id']]-=$movimientos['credito'];
                }
            }
            foreach ($impcliCBU as $kicbu => $iccbu) {
                foreach ($iccbu['Cbu'] as $kic => $cbu) {
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
            <tr>
                <td> 
                    <legend style="color:#1e88e5;font-weight:normal;">
                            <?php echo $iccbu['Impuesto']['nombre']." ".substr($cbu['numerocuenta'], -5)." ".
                                $abreviacionCBUTipo?></legend>
                </td>
                <td>                 
                    <?php echo isset($cbuMovimientos[$cbu['id']])?$cbuMovimientos[$cbu['id']]:0;?>
                </td>
            </tr>
                        <?php
                }   
            }
            
            ?>
        </tbody>
    </table>
    <?php
   
    ?>
</div>
<div  style="width:100%;height: 1px; page-break-before:always"></div>
<div class="index estadocontable" id="divJustificacionVarPat" >
    <table class="toExcelTable tbl_border tblEstadoContable splitForPrint">
        <thead>
            <tr>
                <td colspan="3">
                    Contribuyente <?php echo $cliente["Cliente"]['nombre'] ?>
                </td>
                <td>
                    Año Fiscal <?php echo $periodoActual ?>
                </td>               
                <td colspan="13">
                    Justificaci&oacute;n de las variaci&oacute;nes patrimoniales
                </td>               
            </tr>
        </thead>
        <tbody>    
            <tr class="trTitle">
                <th colspan="3">  </th>
                <th colspan="1"> Columna I </th>
                <th colspan="1"> Columna II </th>               
            </tr>            
            <tr class="">
                <th colspan="3">Monto consumido(por diferencia)</th>
                <?php
                $patrimonioInicial = $totalPatrimonio['apertura']+$totalDeudaPais['apertura'];
                $patrimonioFinal = $totalPatrimonio[$periodoActual]+$totalDeudaPais[$periodoActual];
                
                $bienesHerencia=sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["6070"],$periodoActual,$keysCuentas,'todos',-1);
                
                /*$ingresosPresuntos['primera']=(isset($totalIngresos3ra['No Gravado IVA']))?$totalIngresos3ra['No Gravado IVA']:0;
                $ingresosPresuntos['segunda']=(isset($totalIngresos2da['No Gravado IVA']))?$totalIngresos2da['No Gravado IVA']:0;
                $ingresosPresuntos['tercera']=(isset($totalIngresos3ra['No Gravado IVA']))?$totalIngresos3ra['No Gravado IVA']:0;
                $ingresosPresuntos['cuarta']=(isset($totalIngresos4ta['No Gravado IVA']))?$totalIngresos4ta['No Gravado IVA']:0;*/
                $ingresospresuntosTotal =0; //$ingresosPresuntos['primera']+$ingresosPresuntos['segunda']+$ingresosPresuntos['tercera']+$ingresosPresuntos['cuarta'];
                
                /*$gastosNoDeducibles['primera']=(isset($totalGastos1ra['No deducibles']))?$totalGastos1ra['No deducibles']:0;
                $gastosNoDeducibles['segunda']=(isset($totalGastos2da['No deducibles']))?$totalGastos2da['No deducibles']:0;
                $gastosNoDeducibles['tercera']=(isset($totalGastos3ra['No deducibles']))?$totalGastos3ra['No deducibles']:0;
                $gastosNoDeducibles['terceraEmpresa']=(isset($totalGastos3raEmpresa['No deducibles']))?$totalGastos3raEmpresa['No deducibles']:0;
                $gastosNoDeducibles['cuarta']=(isset($totalGastos4ta['Que no generan mov. de fondos']))?$totalGastos4ta['Que no generan mov. de fondos']:0;    */           
                /*
                 * Tengo que mostrar esto
                Debugger::dump($gastosNoDeducibles['primera']);
                Debugger::dump($gastosNoDeducibles['segunda']);
                Debugger::dump($gastosNoDeducibles['tercera']);
                Debugger::dump($gastosNoDeducibles['terceraEmpresa']);
                Debugger::dump($gastosNoDeducibles['cuarta']);
                */
                $gastosNoDeduciblesTotal = 0;/*$gastosNoDeducibles['primera']+$gastosNoDeducibles['segunda']
                        +$gastosNoDeducibles['tercera']+$gastosNoDeducibles['terceraEmpresa']+$gastosNoDeducibles['cuarta']*/
                
                //aca vamos a sumar en gastos no deducibles todas las cuentas del rubro 5980010 menos 598001001 que es consumos
                
                $gastosNoDeduciblesTotal+= sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001002"],$periodoActual,$keysCuentas,'todos',1);                
                $gastosNoDeduciblesTotal+= sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001003"],$periodoActual,$keysCuentas,'todos',1);                
                $gastosNoDeduciblesTotal+= sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001004"],$periodoActual,$keysCuentas,'todos',1);                
                $gastosNoDeduciblesTotal+= sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001005"],$periodoActual,$keysCuentas,'todos',1);                
                $gastosNoDeduciblesTotal+= sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001006"],$periodoActual,$keysCuentas,'todos',1);                
                $gastosNoDeduciblesTotal+= sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001007"],$periodoActual,$keysCuentas,'todos',1);                
                $gastosNoDeduciblesTotal+= sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001008"],$periodoActual,$keysCuentas,'todos',1);                
                $gastosNoDeduciblesTotal+= sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001009"],$periodoActual,$keysCuentas,'todos',1);                
                $gastosNoDeduciblesTotal+= sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001010"],$periodoActual,$keysCuentas,'todos',1);                
                $gastosNoDeduciblesTotal+= sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001011"],$periodoActual,$keysCuentas,'todos',1);                
                $gastosNoDeduciblesTotal+= sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001012"],$periodoActual,$keysCuentas,'todos',1);                
                $gastosNoDeduciblesTotal+= sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001013"],$periodoActual,$keysCuentas,'todos',1);                
                $gastosNoDeduciblesTotal+= sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001014"],$periodoActual,$keysCuentas,'todos',1);                
                $gastosNoDeduciblesTotal+= sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001015"],$periodoActual,$keysCuentas,'todos',1);                
                $gastosNoDeduciblesTotal+= sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001016"],$periodoActual,$keysCuentas,'todos',1);                
                $gastosNoDeduciblesTotal+= sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001017"],$periodoActual,$keysCuentas,'todos',1);                
                
                /*$gananciasexentas['primera']=(isset($totalIngresos1ra['Exentos, no grav. o no comput']))?$totalIngresos1ra['Exentos, no grav. o no comput']:0;
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
                        +$gananciasexentas['tercera']+$gananciasexentas['terceraEmpresa']+$gananciasexentas['cuarta'];*/
                
                $gananciasexentasTotal2 = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["606"],$periodoActual,$keysCuentas,'todos',-1);
                $conceptosQueJustificanErog = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["6080"],$periodoActual,$keysCuentas,'todos',1);
                
                $Amortizacionesdejejercicio['primera']=(isset($totalGastos1ra['Amortiz.']))?$totalGastos1ra['Amortiz.']:0;
                $Amortizacionesdejejercicio['segunda']=(isset($totalGastos2da['Amortiz.']))?$totalGastos2da['Amortiz.']:0;
                $Amortizacionesdejejercicio['tercera']=(isset($totalGastos3ra['Amortiz.']))?$totalGastos3ra['Amortiz.']:0;
                $Amortizacionesdejejercicio['terceraEmpresa']=(isset($totalGastos3raEmpresa['Amortiz.']))?$totalGastos3raEmpresa['Amortiz.']:0;
                $Amortizacionesdejejercicio['cuarta']=(isset($totalGastos4ta['Amortiz.']))?$totalGastos4ta['Amortiz.']:0;
                $AmortizacionesdejejercicioTotal = $Amortizacionesdejejercicio['primera']+$Amortizacionesdejejercicio['segunda']
                        +$Amortizacionesdejejercicio['tercera']+$Amortizacionesdejejercicio['terceraEmpresa']+$Amortizacionesdejejercicio['cuarta'];
                
                /*$gastospresuntos['primera']=(isset($totalGastos1ra['Que no generan mov. de fondos']))?$totalGastos1ra['Que no generan mov. de fondos']:0;
                $gastospresuntos['segunda']=(isset($totalGastos2da['Que no generan mov. de fondos']))?$totalGastos2da['Que no generan mov. de fondos']:0;
                $gastospresuntos['tercera']=(isset($totalGastos3ra['Que no generan mov. de fondos']))?$totalGastos3ra['Que no generan mov. de fondos']:0;
                $gastospresuntos['terceraEmpresa']=(isset($totalGastos3raEmpresa['Que no generan mov. de fondos']))?$totalGastos3raEmpresa['Que no generan mov. de fondos']:0;
                $gastospresuntos['cuarta']=(isset($totalGastos4ta['Que no generan mov. de fondos']))?$totalGastos4ta['Que no generan mov. de fondos']:0;*/
                $gastospresuntosTotal = 0/*$gastospresuntos['primera']+$gastospresuntos['segunda']
                        +$gastospresuntos['tercera']+$gastospresuntos['terceraEmpresa']+$gastospresuntos['cuarta']*/;
                
                /*$primera=$ingresosPresuntos['primera']+$gastosNoDeducibles['primera'];
                $segunda=$ingresosPresuntos['segunda']+$gastosNoDeducibles['segunda'];
                $tercera=$ingresosPresuntos['tercera']+$gastosNoDeducibles['tercera'];
                $cuarta=$ingresosPresuntos['cuarta']+$gastosNoDeducibles['cuarta'];*/
                $totalOtrosConceptosQueNoJustificanErog=$ingresospresuntosTotal+$gastosNoDeduciblesTotal;
                //sumar la cuenta de ganancias aca
                $totalOtrosConceptosQueNoJustificanErog+=sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["506110001"],$periodoActual,$keysCuentas,'todos',1);                
                $totalOtrosConceptosQueNoJustificanErog+=sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["597000"],$periodoActual,$keysCuentas,'todos',1);     
                $totalOtrosConceptosQueNoJustificanErog+=sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["599001002"],$periodoActual,$keysCuentas,'todos',1);     
                $totalOtrosConceptosQueNoJustificanErog+=sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["599001003"],$periodoActual,$keysCuentas,'todos',1);     
                $totalOtrosConceptosQueNoJustificanErog+=sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["599001004"],$periodoActual,$keysCuentas,'todos',1);     
                $totalOtrosConceptosQueNoJustificanErog+=sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["599001005"],$periodoActual,$keysCuentas,'todos',1);     
                
                $columnaItotal=$patrimonioNetoPF[$periodoActual] + (($resultadoImpositivo<0)?$resultadoImpositivo*-1:0)+$totalOtrosConceptosQueNoJustificanErog+$totalDeudasGenerales['nodeducible'];
                $columnaIItotal=$patrimonioInicial + (($resultadoImpositivo>=0)?$resultadoImpositivo:0)
                       /*+$gananciasexentasTotal*/+$gananciasexentasTotal2+$AmortizacionesdejejercicioTotal+$gastospresuntosTotal+$bienesHerencia;                                
                $consumoPorDiferencia=$columnaIItotal-$columnaItotal;
                ?>
                <th colspan="1" class="tdWithNumber"><?php echo number_format( $consumoPorDiferencia, 2, ",", ".");?></th>
                <th colspan="1" class="tdWithNumber"></th>                               
            </tr>
            <tr class="">
                <th colspan="3">Otros conceptos que no justifican erog. o increm. patrimoniales </th>
                <th colspan="1" class="tdWithNumber"> <?php echo number_format($totalOtrosConceptosQueNoJustificanErog, 2, ",", ".");?> </th>
                <td colspan="1">  </td>
            </tr>                                            
            <?php
                mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["506110001"],$periodoActual,$keysCuentas,'todos',1,1);                

                //completar esto
                mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001002"],$periodoActual,$keysCuentas,'todos',1,1);                
                mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001003"],$periodoActual,$keysCuentas,'todos',1,1);                
                mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001004"],$periodoActual,$keysCuentas,'todos',1,1);                
                mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001005"],$periodoActual,$keysCuentas,'todos',1,1);                
                mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001006"],$periodoActual,$keysCuentas,'todos',1,1);                
                mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001007"],$periodoActual,$keysCuentas,'todos',1,1);                
                mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001008"],$periodoActual,$keysCuentas,'todos',1,1);                
                mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001009"],$periodoActual,$keysCuentas,'todos',1,1);                
                mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001010"],$periodoActual,$keysCuentas,'todos',1,1);                
                mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001011"],$periodoActual,$keysCuentas,'todos',1,1);                
                mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001012"],$periodoActual,$keysCuentas,'todos',1,1);                
                mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001013"],$periodoActual,$keysCuentas,'todos',1,1);                
                mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001014"],$periodoActual,$keysCuentas,'todos',1,1);                
                mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001015"],$periodoActual,$keysCuentas,'todos',1,1);                
                mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001016"],$periodoActual,$keysCuentas,'todos',1,1);                
                mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001017"],$periodoActual,$keysCuentas,'todos',1,1);                

                mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["597000"],$periodoActual,$keysCuentas,'todos',1,1);     
                mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["599001002"],$periodoActual,$keysCuentas,'todos',1,1);     
                mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["599001003"],$periodoActual,$keysCuentas,'todos',1,1);     
                mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["599001004"],$periodoActual,$keysCuentas,'todos',1,1);     
                mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["599001005"],$periodoActual,$keysCuentas,'todos',1,1);     
            ?>
            <tr class="">
                <th colspan="3">Deducciones generales que exceden el tope</th>                
                <th colspan="1" class="tdWithNumber"> <?php echo number_format($totalDeudasGenerales['nodeducible'], 2, ",", ".");?> </th>
                <td colspan="1">  </td>                
            </tr>                                            
            <tr class="">
                <th colspan="3"> Ganancias exentas o no gravadas o no computables </th>                
                <th colspan="1" class="tdWithNumber">  </th>
                <th colspan="1" class="tdWithNumber"> <?php /*echo number_format($gananciasexentasTotal, 2, ",", ".");*/?>
                    <?php echo number_format($gananciasexentasTotal2, 2, ",", ".");?> </th>
            </tr>
            <?php
            mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["606"],$periodoActual,$keysCuentas,'todos',-1,2);
            ?>
            <tr class="">
                <th colspan="3"> Bs. recibidos por herencia, legado o donaci&oacute;n </th>
                <?php
                ?>
                <th colspan="1"> </th>
                <th colspan="1" class="tdWithNumber"> <?php echo number_format($bienesHerencia, 2, ",", ".");?> </th>
            </tr>
            <?php
            mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["6070"],$periodoActual,$keysCuentas,'todos',-1,2);
            ?>
            <tr class="">
                <th colspan="3"> Gastos que no implican erogaciones de fondos 
                    correspondientes a cada categoria</th>
                <th colspan="1"> </th>
                <th colspan="1" class="tdWithNumber"> <?php echo number_format($conceptosQueJustificanErog, 2, ",", ".");?> </th>
            </tr>
            <?php
            mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,["6080"],$periodoActual,$keysCuentas,'todos',1,2);
            ?>
            <tr class="">
                <th colspan="3"> Otros conceptos que justifican erogacion y/o 
                    aumentos patrimoniales(Incluye amortizacion de cada categoria)</th>
                <th colspan="1"> </th>
                <td colspan="1"> </td>
            </tr>
            <tr class="">
                <th colspan="1"> </th>
                <th colspan="2"> Amortizaciones del ejercicio</th>
                <td colspan="1"> </td>
                <td colspan="1" class="tdWithNumber"><?php echo number_format( $AmortizacionesdejejercicioTotal, 2, ",", ".");?> </td>
            </tr>
            <tr class="">
                <th colspan="1"> </th>
                <th colspan="2"> Gastos presuntos</th>
                <td colspan="1"> </td>
                <td colspan="1" class="tdWithNumber"><?php echo number_format( $gastospresuntosTotal, 2, ",", ".");?> </td>
            </tr>
            <tr class="">
                <th colspan="1"> </th>
                <th colspan="2"> C&oacute;mputo quebrantos espec&iacute;ficos</th>                
                <td colspan="1"> </td>
                <td colspan="1"> </td>
            </tr>
            
            <tr class="">
                <th colspan="3"> Resultado Impositivo(quebranto Col I; ganancia Col II) </th>
                <th colspan="1" class="tdWithNumber"> <?php 
                if($resultadoImpositivo<0){echo number_format($resultadoImpositivo*-1, 2, ",", ".");}?> </th>
                <th colspan="1" class="tdWithNumber"> <?php 
                if($resultadoImpositivo>=0){echo number_format($resultadoImpositivo, 2, ",", ".");}?> </th>
            </tr>
            <tr class="">
                <th colspan="3"> Patrimonio Neto al Inicio </th>
                <th colspan="1">  </th>
                <th colspan="1" class="tdWithNumber"> <?php 
                        echo number_format($patrimonioInicial, 2, ",", ".")?> </th>
            </tr>
            <tr class="">
                <th colspan="3"> Patrimonio Neto al cierre </th>
                <th colspan="1" class="tdWithNumber"> <?php 
                        echo number_format($patrimonioNetoPF[$periodoActual], 2, ",", ".")?> </th>
                <th colspan="1" class="tdWithNumber"></th>                
            </tr>
            <tr class="">
                <th colspan="3">Subtotales</th>
                <th colspan="1" class="tdWithNumber"><?php echo number_format( $columnaItotal+$consumoPorDiferencia, 2, ",", ".");?></th>
                <th colspan="1" class="tdWithNumber"><?php echo number_format( $columnaIItotal, 2, ",", ".");?></th>                
            </tr>
            <tr class="">
                <th colspan="3">Monto consumido(por contabilidad)</th>
                <?php
                $consumoPorContabilidad=sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["598001001"],$periodoActual,$keysCuentas,'todos',1);
                ?>
                <th colspan="1" class="tdWithNumber"><?php echo number_format( $consumoPorContabilidad, 2, ",", ".");?></th>
                <th colspan="1" class="tdWithNumber"></th>                
            </tr>
        </tbody>
    </table>
</div>
<div  style="width:100%;height: 1px; page-break-before:always"></div>
<div class="index estadocontable" id="divDeterminacionBP" style="display:none">
    <table class="toExcelTable tbl_border tblEstadoContable splitForPrint">
        <thead>
            <tr>
                <td colspan="3">
                    Contribuyente <?php echo $cliente["Cliente"]['nombre'] ?>
                </td>
                <td>
                    Año Fiscal <?php echo $periodoActual ?>
                </td>                            
            </tr>
            <tr>
                <td colspan="4">
                    Determinación Impuesto sobre bienes personales
                </td>               
            </tr>
        </thead>
        <tbody>    
            <tr class="">
                <th colspan="2">  </th>
                <th colspan="1"> Importes gravados</th>
                <th colspan="1"> Importes exentos/no gravados </th>               
            </tr>
            <tr class="">
                <th colspan="2"> Bienes situados en el país </th>
                <th colspan="1">  </th>
                <th colspan="1">  </th>
            </tr>
            <?php
            $totalBP = [];
            $totalBP['gravado'] = 0;
            $totalBP['exento'] = 0;
            function showRowDetBP($nombre,$rowToShow,&$totalBP){
                if(($rowToShow['bienpersonal']==0&&$rowToShow['exento']==0)||($rowToShow['bienpersonal']<0||$rowToShow['exento']<0))
                    return;
                $totalBP['gravado']+=$rowToShow['bienpersonal'];
                $totalBP['exento']+=$rowToShow['exento'];
                ?>
                <tr>
                   <th colspan="1" >  </th>
                   <th colspan="1"> <?php echo $nombre ?> </th>
                   <th colspan="1" class="numericTD"> <?php echo number_format($rowToShow['bienpersonal'], 2, ",", ".") ?></th>
                   <th colspan="1" class="numericTD"> <?php echo number_format($rowToShow['exento'], 2, ",", ".") ?> </th>
                </tr>
                <?php
            }
            if(isset($patdisponibilidades)){
                    showRowDetBP('Disponibilidades',$patdisponibilidades,$totalBP);
                    }
            if(isset($patcreditos)){
                    showRowDetBP('Creditos',$patcreditos,$totalBP);
                    }
            if(isset($patbienesdecambio)){
                    showRowDetBP('Bienes de cambio',$patbienesdecambio,$totalBP);
                    }
            if(isset($patInversiones)){
                    showRowDetBP('Inversiones',$patInversiones,$totalBP);
                    }            
            if(isset($patinmuebles)){
                    showRowDetBP('Inmuebles',$patinmuebles,$totalBP);
                    }
            if(isset($patrodados)){
                    showRowDetBP('Rodados',$patrodados,$totalBP);
                    }
            if(isset($patinstalaciones)){
                    showRowDetBP('Instalaciones',$patinstalaciones,$totalBP);
                    }
            if(isset($patotrosBienesDeUso)){
                    showRowDetBP('Otros Bienes de uso',$patotrosBienesDeUso,$totalBP);
                    }
            if(isset($patbienesintangibles)){
                    showRowDetBP('Bienes intangibles',$patbienesintangibles,$totalBP);
                    }
            if(isset($patinmueble)){
                    showRowDetBP('Inmueble',$patinmueble,$totalBP);
                    }
            if(isset($patderechosreales)){
                    showRowDetBP('Derechos reales',$patderechosreales,$totalBP);
                    }
            if(isset($patautomotores)){
                    showRowDetBP('Automotores',$patautomotores,$totalBP);
                    }
            if(isset($patnavesyates)){
                    showRowDetBP('Naves y yates',$patnavesyates,$totalBP);
                    }
            if(isset($pataeronaves)){
                    showRowDetBP('Aeronaves',$pataeronaves,$totalBP);
                    }
            if(isset($patpatrimonioNeto)){
                    showRowDetBP('Patrimonio Neto',$patpatrimonioNeto,$totalBP);
                    }
            if(isset($patrimonioNeto)){
                    showRowDetBP('Participacion de Empresas o Explotacion unipersonal',$patrimonioNeto,$totalBP);
                    }
            if(isset($patparticipacionempresa)){
                    showRowDetBP('Participacion Empresa',$patparticipacionempresa,$totalBP);
                    }
            if(isset($pataccionesconcotizacion)){
                    showRowDetBP('Acciones con cotizacion',$pataccionesconcotizacion,$totalBP);
                    }
            if(isset($pataccionessincontizacion)){
                    showRowDetBP('Acciones sin contizacion',$pataccionessincontizacion,$totalBP);
                    }
            if(isset($pattitulossincotizacion)){
                    showRowDetBP('Titulos sin cotizacion',$pattitulossincotizacion,$totalBP);
                    }
            if(isset($pattitulosconcotizacion)){
                    showRowDetBP('Titulos con cotizacion',$pattitulosconcotizacion,$totalBP);
                    }
            if(isset($patcreditospf)){
                    showRowDetBP('Creditos pf',$patcreditospf,$totalBP);
                    }
            if(isset($patdepositosdinero)){
                    showRowDetBP('Depositos Dinero',$patdepositosdinero,$totalBP);
                    }
            if(isset($patdepositosefectivo)){
                    showRowDetBP('Depositos Efectivo',$patdepositosefectivo,$totalBP);
                    }
            if(isset($patbienesmueblesregistrables)){
                    showRowDetBP('Bienes muebles registrables',$patbienesmueblesregistrables,$totalBP);
                    }
            if(isset($patotrosbienes)){
                    showRowDetBP('Otros bienes',$patotrosbienes,$totalBP);
                    }
            ?>
            <tr class="trTitle">
                <td colspan="2">Subtotal Bienes en el país, excluidos bienes del hogar y UP</td>
                <td colspan="1" class="numericTD"> <?php echo number_format($totalBP['gravado'], 2, ",", ".") ?> </td>
                <td colspan="1" class="numericTD"> <?php echo number_format($totalBP['exento'], 2, ",", ".") ?> </td>
            </tr>    
            <tr class="trTitle">
                <td colspan="1"></td>
                <td colspan="1">Bienes del hogar y uso personal</td>
                <td colspan="1" class="numericTD"> </td>
                <td colspan="1" class="numericTD">  </td>
            </tr>    
            <tr class="">
                <td colspan="1"></td>
                <td colspan="1">Importe presunto 5%</td>
                <?php 
                $importepresunto = $totalBP['gravado']*0.05;
                $totalBP['gravado']+=$importepresunto;
                ?>
                <td colspan="1" class="numericTD"> <?php echo number_format($importepresunto, 2, ",", ".") ?> </td>
                <td colspan="1" class="numericTD"> </td>
            </tr>    
            <tr class="">
                <td colspan="1"></td>
                <td colspan="1">Importe declarado</td>
                <td colspan="1" class="numericTD"> </td>
                <td colspan="1" class="numericTD">  </td>
            </tr>    
            <tr class="trTitle">
                <td colspan="2">Total Bienes en el país</td>
                <td colspan="1" class="numericTD"> <?php echo number_format($totalBP['gravado'], 2, ",", ".") ?> </td>
                <td colspan="1" class="numericTD"> <?php echo number_format($totalBP['exento'], 2, ",", ".") ?> </td>
            </tr> 
        </tbody>
    </table>   
    <table class="toExcelTable tbl_border tblEstadoContable splitForPrint">
        <thead>            
            <tr>
                <td colspan="2">
                    Determinación del impuesto
                </td>               
                <td></td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td>Total de bienes sujetos al impuesto</td>
                <td colspan="1" class="numericTD"> <?php echo number_format($totalBP['gravado'], 2, ",", ".") ?> </td>
            </tr>
            <tr>
                <td></td>
                <td>Minimo exento</td>
                <?php 
                $minimoexento  = [];
                $minimoexento['2016']  = 800000;
                $minimoexento['2017']  = 950000;
                $minimoexento['2018']  = 950000;
                
                ?>
                <td colspan="1" class="numericTD"> <?php echo number_format($minimoexento[$periodoActual], 2, ",", ".") ?> </td>
            </tr>
            <tr>
                <td></td>
                <td>Diferencia</td>
                <?php 
                $diferencia = $totalBP['gravado']-$minimoexento[$periodoActual];
                $diferencia = ($diferencia>0)?$diferencia:0;
                ?>
                <td colspan="1" class="numericTD"> <?php echo number_format($diferencia, 2, ",", ".") ?> </td>
            </tr>
            <tr>
                <td></td>
                <td>Alicuota del impuesto</td>
                <?php 
                $alicuotadelimpuesto  = [];
                $alicuotadelimpuesto['2016']  = 0.0075;
                $alicuotadelimpuesto['2017']  = 0.005;
                $alicuotadelimpuesto['2018']  = 0.0025;
                ?>
                <td colspan="1" class="numericTD"> <?php echo number_format($alicuotadelimpuesto[$periodoActual], 2, ",", ".") ?> </td>
            </tr>
            <tr>
                <td></td>
                <td>Impuesto determinado</td>               
                <?php
                $BPImpuestoDeterminado = $diferencia*$alicuotadelimpuesto[$periodoActual];
                ?>
                <td colspan="1" class="numericTD"> <?php echo number_format($BPImpuestoDeterminado, 2, ",", ".") ?> </td>
            </tr>
        </tbody>
    </table>
    <table class="toExcelTable tbl_border tblEstadoContable splitForPrint">
        <thead>            
            <tr>
                <td colspan="2">
                    Determinación del saldo de Declaracion Jurada
                </td>               
                <td></td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td>Total de bienes sujetos al impuesto </td>
                <td colspan="1" class="numericTD"> <?php echo number_format($BPImpuestoDeterminado, 2, ",", ".") ?> </td>
            </tr>
            <tr>
                <td></td>
                <td>Menos:</td>
                <td colspan="1" class="numericTD"> </td>
            </tr>
            <tr>
                <td></td>
                <td>Total de anticipos cancelados</td>
                <?php 
                $bpAnticipos  = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110403301"],$periodoActual,$keysCuentas,'todos',1);                
                ?>
                <td colspan="1" class="numericTD"> <?php echo number_format($bpAnticipos, 2, ",", ".") ?> </td>
            </tr>
            <tr>
                <td></td>
                <td>Total de pagos a cuenta</td>
                <?php 
                $bpPagosACuenta  = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110403305"],$periodoActual,$keysCuentas,'todos',1);  
                ?>
                <td colspan="1" class="numericTD"> <?php echo number_format($bpPagosACuenta, 2, ",", ".") ?> </td>
            </tr>
            <tr>
                <td></td>
                <td>Saldo a favor del período anterior</td>
                <?php 
                $bpSLD  = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110403303"],$periodoActual,$keysCuentas,'todos',1);  
                ?>
                <td colspan="1" class="numericTD"> <?php echo number_format($bpSLD, 2, ",", ".") ?> </td>
            </tr>
            <tr>
                <td></td>
                <td>Impuesto determinado  </td>
                <?php
                $BPImpuestoDeterminado -= $bpAnticipos;
                $BPImpuestoDeterminado -= $bpPagosACuenta;
                $BPImpuestoDeterminado -= $bpSLD;
                ?>
                <td colspan="1" class="numericTD"> <?php echo number_format($BPImpuestoDeterminado, 2, ",", ".") ?> </td>
            </tr>
        </tbody>
    </table>
    <?php
   
    ?>
</div>
<div  style="width:100%;height: 1px; page-break-before:always"></div>
<?php
function mostrarPatrimonio3ra($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$nombreNota,$fechaInicioConsulta,$fechaFinConsulta,&$total,$firstColumnColspan=null){
    if(is_null($firstColumnColspan)){
        $firstColumnColspan=4;
    }
    $mostrarTotal = false;
    $periodoActual = date('Y', strtotime($fechaInicioConsulta));
    $totalNota = [];
    $totalNota[$periodoActual]=0;    
    $totalNota['apertura']=0;   
    $totalNota['bienpersonal']=0;   
    $totalNota['exento']=0;   
     if (!isset($total[$periodoActual])){
        $total[$periodoActual]=0;    
        $total['apertura']=0;   
        $total['bienpersonal']=0;   
        $total['exento']=0;   
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
                    <th colspan="<?php echo $firstColumnColspan?>" class=" "><?php echo $nombreNota ?></th>
                    <th colspan="" class=" "></th>
                    <th colspan="" class=" "></th>
                    <th colspan="" class=" "></th>
                    <th colspan="" class=" "></th>
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
                    <td colspan="<?php echo $firstColumnColspan?>"><?php echo $arrayCuentasxPeriodos[$numeroCuenta]['nombrecuenta'].$datosCBU;//aca tengo que agregar el CBU si existe ?></td>
                <?php                   
                    $charinicial = substr($numeroCuenta, 0, 1);        
                    $subtotal = $arrayCuentasxPeriodos[$numeroCuenta][$periodoActual];;
                    $subtotalapertura = $arrayCuentasxPeriodos[$numeroCuenta]['apertura'][$periodoActual];;
                    switch ($charinicial){
                        case "1":
                            $subtotal = $arrayCuentasxPeriodos[$numeroCuenta]['activo'];
                            break;
                        case "2":
                            $subtotal = $arrayCuentasxPeriodos[$numeroCuenta]['pasivo']*-1;
                            break;    
                        case "3":
                            $subtotal = $arrayCuentasxPeriodos[$numeroCuenta][$periodoActual];
                            break;
                        case "4":
                            $subtotal = $arrayCuentasxPeriodos[$numeroCuenta]['pasivo']*-1;
                        break;
                        case "5":
                            $subtotal = $arrayCuentasxPeriodos[$numeroCuenta]['perdida'];                    
                            break;
                        case "6":
                            $subtotal = $arrayCuentasxPeriodos[$numeroCuenta]['ganancia']*-1;                                        
                            break;
                    }            
                    $bienpersonal = $arrayCuentasxPeriodos[$numeroCuenta]['bienpersonal'];          
                    $exento = $arrayCuentasxPeriodos[$numeroCuenta]['exento'];          
                    echo '<td  class="numericTD tdborder" style="width:90px">'. number_format($subtotalapertura, 2, ",", ".").'</td>';
                    echo '<td  class="numericTD tdborder" style="width:90px">'. number_format($subtotal, 2, ",", ".").'</td>';
                    echo '<td  class="numericTD tdborder" style="width:90px">'. number_format($bienpersonal, 2, ",", ".").'</td>';
                    echo '<td  class="numericTD tdborder" style="width:90px">'. number_format($exento, 2, ",", ".").'</td>';
                    $totalNota['apertura']+=$subtotalapertura;
                    $totalNota[$periodoActual]+=$subtotal;
                    $totalNota['bienpersonal']+=$bienpersonal;
                    $totalNota['exento']+=$exento;
                    $total['apertura']+=$subtotalapertura;
                    $total[$periodoActual]+=$subtotal;
                    $total['bienpersonal']+=$bienpersonal;
                    $total['exento']+=$exento;
                    ?>
                </tr>
               <?php
            }
        }
    }
    if($mostrarTotal){ ?>
        <tr class="trnoclickeable">
            <th colspan="<?php echo $firstColumnColspan?>" class="">Subtotal <?php echo $nombreNota ?></th>
            <th  class="numericTD tdborder" style="width:90px"><?php echo number_format($totalNota['apertura'], 2, ",", ".")?></th>
            <th  class="numericTD tdborder" style="width:90px"><?php echo number_format($totalNota[$periodoActual], 2, ",", ".")?></th>            
            <th  class="numericTD tdborder" style="width:90px"><?php echo number_format($totalNota['bienpersonal'], 2, ",", ".")?></th>            
            <th  class="numericTD tdborder" style="width:90px"><?php echo number_format($totalNota['exento'], 2, ",", ".")?></th>            
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
    $totalNota['bienpersonal'] = 0;    
    $totalNota['exento'] = 0;    

    foreach ($arrayPrefijos as $valoresPrefijo) {                   
        $totalPrefijo = [];
        $totalPrefijo['alinicio'] = 0;
        $totalPrefijo['altas'] = 0;
        $totalPrefijo['valorresidual'] = 0;
        $totalPrefijo['apertura'] = 0;
        $totalPrefijo['periodoActual'] = 0;      
        $totalPrefijo['depreciacionalinicio'] = 0;       
        $totalPrefijo['bienpersonal'] = 0;    
        $totalPrefijo['exento'] = 0;    

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
            <th colspan="1"></th>
            <th colspan="1"></th>
            
            <?php
            switch ($nombrePrefijo) {
                case 'Inmuebles':
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
                $periodoBDU = -1;
            }
            if($periodoBDU!=-1){
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
            }else{
                $periodoBDU="01-1990";
                $peanioBDU="1990";
            }
            
            $periodoActual = date('Y', strtotime($fechaInicioConsulta));
            $valororigen =  $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual];   
            $porcentajeamortizacion =  1;                
            if(isset($arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada'])){
                $amortizacionacumulada =  $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada'];
                $porcentajeamortizacion =  $arrayCuentasxPeriodos[$numerodecuenta]['porcentajeamortizacion'];
                $amortizacionejercicio =  $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionejercicio'];
            }else{
                $amortizacionacumulada =  0;
                $porcentajeamortizacion =  0;
                $amortizacionejercicio =  0;
            }
            $d1 = new DateTime('01-'.$periodoBDU);
            $d2 = new DateTime($fechaFinConsulta);
            $interval = $d2->diff($d1);
            $aniosamortizados = $interval->format('%y')*1 + (($interval->format('%m')*1>0)?1:0);
            $topeAmortizacion = ($porcentajeamortizacion!=0)?(100/$porcentajeamortizacion):1000;
            /*Debugger::dump( $arrayCuentasxPeriodos[$numerodecuenta]['nombrecuenta']);
            Debugger::dump('aniosamortizados');
            Debugger::dump($aniosamortizados);
            Debugger::dump('topeAmortizacion');
            Debugger::dump($topeAmortizacion);*/
             if($aniosamortizados<=$topeAmortizacion){
                if(($aniosamortizados)<=1){
                    $amortizacionacumulada = 0;
                }else{
                    $amortizacionacumulada = ($porcentajeamortizacion/100)*$valororigen*($aniosamortizados-1);
                }
                if(($aniosamortizados)==0){
                    $amortizacionEjercicio = 0;
                }else{
                    $amortizacionEjercicio = ($porcentajeamortizacion/100)*$valororigen;
                }
            }else{
                $amortizacionacumulada = $valororigen;
                $amortizacionEjercicio =  0;
            }    
           
            //si esta echo el asiento entonces la amortizacion acumulada tiene que restar la amortizacion del ejercicio
            $topeAmortizacionAnterior = ($porcentajeamortizacion!=0)?(100/$porcentajeamortizacion):1000;        
                       
            $subtotalAmortizacion = $amortizacionacumulada/*+$amortizacionejercicio*/;
            $valorresidual = $valororigen - $subtotalAmortizacion;
            $coefActualizacion = 1;//DE DONDE SALE ESTO???
            $amortizacionDeducible = $amortizacionejercicio * $coefActualizacion;
            $valorResidualImpositivo = $coefActualizacion * $valorresidual;

            $pemes = date('m', strtotime($fechaInicioConsulta));
            $peanio = date('Y', strtotime($fechaInicioConsulta));

            if(isset($arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'])){
                foreach ($arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'] as $kae => $amortespecial) {
                    if($kae==$peanio){
                        //aca podemos estar seguros q hay una amortizacion esecial para este periodo 
                        $amortizacionEjercicio = $amortespecial['ejercicio'];
                        $amortizacionacumulada = $amortespecial['amortizacion'];
                        //Debugger::dump("alv todo tengo amort esp");
                        //Debugger::dump($amortespecial['ejercicio']);
                    }
                }
            }         
            
           
            $valorresidual = $valororigen - $subtotalAmortizacion;
            $coefActualizacion = 1;//DE DONDE SALE ESTO???
            $amortizacionDeducible = $amortizacionEjercicio * $coefActualizacion;
            $valorResidualImpositivo = $coefActualizacion * $valorresidual;
          
            if(($aniosamortizados)!=0){
                $alinicio = ($peanio==$peanioBDU)?0:$valororigen;
                $altas = ($peanio==$peanioBDU)?$valororigen:0;
            }else{
                $alinicio = 0;
                $altas = 0;
            }
            
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
            $totalPrefijo['bienpersonal'] += $arrayCuentasxPeriodos[$numerodecuenta]['bienpersonal'];
            $totalPrefijo['exento'] += $arrayCuentasxPeriodos[$numerodecuenta]['exento'];
                      
            $totalNota['alinicio'] += $alinicio;
            $totalNota['altas'] += $altas;
            $totalNota['valorresidual'] += $valorresidual;
            $totalNota['depreciacionalinicio'] += $depreciacionalinicio;
            $totalNota['apertura'] += $arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual];
            $totalNota['periodoActual'] += $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual];
            $totalNota['bienpersonal'] += $arrayCuentasxPeriodos[$numerodecuenta]['bienpersonal'];
            $totalNota['exento'] += $arrayCuentasxPeriodos[$numerodecuenta]['exento'];
         
            ?>
            <tr>
                <td class=""><?php echo $arrayCuentasxPeriodos[$numerodecuenta]['nombrecuenta'];?></td>
                <td class="tdWithNumber"><?php echo number_format($alinicio+$altas, 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($depreciacionalinicio, 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($valorresidual, 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($arrayCuentasxPeriodos[$numerodecuenta][$periodoActual], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($arrayCuentasxPeriodos[$numerodecuenta]['bienpersonal'], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($arrayCuentasxPeriodos[$numerodecuenta]['exento'], 2, ",", ".") ?></td>
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
                        <td><?php echo $bienDeuso['Localidade']['nombre'];?></td>
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
                        <td class="tdWithNumber"><?php echo $bienDeuso['valororiginal'];?></td>
                        <td><?php echo ($bienDeuso['valororiginal']*1==$bienDeuso['amortizacionacumulada']*1)?'SI':'NO';?></td>
                        <td><?php echo ($bienDeuso['bienafectadoatercera']*1==1)?'SI':'NO';?></td>
                        <td><?php echo ($bienDeuso['bienafectadoacuarta']*1==1)?'SI':'NO';?></td>
                        <td class="tdWithNumber"><?php echo $bienDeuso['importeamorteizaciondelperiodo'];?></td>
                        <td class="tdWithNumber"><?php echo number_format($alinicio+$altas, 2, ",", ".") ?></td>
                        <?php
                    }else if($bienDeuso['tipo']=='Instalaciones'){
                       
                    }else{
                          ?>
                        <td><?php echo $bienDeuso['fechaadquisicion'];?></td>
                        <td><?php echo $bienDeuso['descripcion'];?></td>
                        <td class="tdWithNumber"><?php echo $bienDeuso['importeamorteizaciondelperiodo'];?></td>
                        <td class="tdWithNumber"><?php echo number_format($alinicio+$altas, 2, ",", ".") ?></td>
                        <?php
                    }
                }            
                
                ?>
            </tr>
            <?php
            if(!isset($total['apertura']))$total['apertura']=0;
            if(!isset($total[$periodoActual]))$total[$periodoActual]=0;
            if(!isset($total['exento']))$total['exento']=0;
            $total['apertura']+=$arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual];
            $total[$periodoActual]+=$arrayCuentasxPeriodos[$numerodecuenta][$periodoActual];
            $total['bienpersonal']+=$arrayCuentasxPeriodos[$numerodecuenta]['bienpersonal'];
            $total['exento']+=$arrayCuentasxPeriodos[$numerodecuenta]['exento'];
        }
         ?>
            <tr>
                <th> Total <?php echo $nombrePrefijo;?></th>
                <td class="tdWithNumber"><?php echo number_format($totalPrefijo['alinicio']+$totalPrefijo['altas'], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($totalPrefijo['depreciacionalinicio'], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($totalPrefijo['valorresidual'], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($totalPrefijo['apertura'], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($totalPrefijo['periodoActual'], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($totalPrefijo['bienpersonal'], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($totalPrefijo['exento'], 2, ",", ".") ?></td>
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
    $totalNota['bienpersonal'] = 0;    
    $totalNota['exento'] = 0;    

    foreach ($arrayPrefijos as $valoresPrefijo) {                   
        $totalPrefijo = [];
        $totalPrefijo['alinicio'] = 0;
        $totalPrefijo['altas'] = 0;
        $totalPrefijo['valorresidual'] = 0;
        $totalPrefijo['apertura'] = 0;
        $totalPrefijo['periodoActual'] = 0;      
        $totalPrefijo['depreciacionalinicio'] = 0;       
        $totalPrefijo['bienpersonal'] = 0;       
        $totalPrefijo['exento'] = 0;       

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
        <tr class="trnoclickeable trTitle">
            <th colspan="4"><?php echo $nombrePrefijo; ?></th>
            <th colspan=""></th>
            <th colspan=""></th>
            <th colspan=""></th>
            <th colspan=""></th>
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
            
            
            $valororigen =  $arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual];          
            $valorfinal =  $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual];          

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
            
            $pemes = date('m', strtotime($fechaInicioConsulta));
            $peanio = date('Y', strtotime($fechaInicioConsulta));
            
            if($peanio==$peanioBDU){
                $valorresidual = $valorfinal - $subtotalAmortizacion;
            }else{
                $valorresidual = $valororigen - $subtotalAmortizacion;
            }
            $coefActualizacion = 1;//DE DONDE SALE ESTO???
            $amortizacionDeducible = $amortizacionejercicio * $coefActualizacion;
            $valorResidualImpositivo = $coefActualizacion * $valorresidual;

            
            $alinicio = ($peanio==$peanioBDU)?0:$valororigen;
            $altas = 0;//$peanio==$peanioBDU)?$valororigen:0;
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
            $totalPrefijo['bienpersonal'] += $arrayCuentasxPeriodos[$numerodecuenta]['bienpersonal'];
            $totalPrefijo['exento'] += $arrayCuentasxPeriodos[$numerodecuenta]['exento'];
                      
            $totalNota['alinicio'] += $alinicio;
            $totalNota['altas'] += $altas;
            $totalNota['valorresidual'] += $valorresidual;
            $totalNota['depreciacionalinicio'] += $depreciacionalinicio;
            $totalNota['apertura'] += $arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual];
            $totalNota['periodoActual'] += $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual];
            $totalNota['bienpersonal'] += $arrayCuentasxPeriodos[$numerodecuenta]['bienpersonal'];
            $totalNota['exento'] += $arrayCuentasxPeriodos[$numerodecuenta]['exento'];
         
            ?>
            <tr>
                <td colspan="4"><?php echo $arrayCuentasxPeriodos[$numerodecuenta]['nombrecuenta'];?></td>
                <td class="tdWithNumber"><?php echo number_format($alinicio+$altas, 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($valorresidual, 2, ",", ".") ?></td>               
                <td class="tdWithNumber"><?php echo number_format($arrayCuentasxPeriodos[$numerodecuenta]['bienpersonal'], 2, ",", ".") ?></td>               
                <td class="tdWithNumber"><?php echo number_format($arrayCuentasxPeriodos[$numerodecuenta]['exento'], 2, ",", ".") ?></td>               
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
                    }else if($bienDeuso['tipo']=='Automotor'){
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
                        Detalle:<?php echo $bienDeuso['descripcion'];?></p></td>
                        <?php
                    }
                }            
                
                ?>
            </tr>
            <?php
            if(!isset($total['apertura']))$total['apertura']=0;
            if(!isset($total[$periodoActual]))$total[$periodoActual]=0;
            if(!isset($total['bienpersonal']))$total['bienpersonal']=0;
            if(!isset($total['exento']))$total['exento']=0;
            $total['apertura']+=$alinicio+$altas;
            $total[$periodoActual]+=$valorresidual;
            $total['bienpersonal']+=$arrayCuentasxPeriodos[$numerodecuenta]['bienpersonal'];
            $total['exento']+=$arrayCuentasxPeriodos[$numerodecuenta]['exento'];
        }
         ?>
            <tr>
                <th colspan="4"> Subtotal <?php echo $nombrePrefijo;?></th>
                <td class="tdWithNumber"><?php echo number_format($totalPrefijo['alinicio']+$totalPrefijo['altas'], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($totalPrefijo['valorresidual'], 2, ",", ".") ?></td>               
                <td class="tdWithNumber"><?php echo number_format($totalPrefijo['bienpersonal'], 2, ",", ".") ?></td>               
                <td class="tdWithNumber"><?php echo number_format($totalPrefijo['exento'], 2, ",", ".") ?></td>               
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
    $totalNota['bienpersonal'] = 0;
    $totalNota['exento'] = 0;
    if(!isset($total['apertura']))$total['apertura']=0;
    if(!isset($total[$periodoActual]))$total[$periodoActual]=0;
    if(!isset($total['bienpersonal']))$total['bienpersonal']=0;
    if(!isset($total['exento']))$total['exento']=0;
    foreach ($arrayPrefijos as $valoresPrefijo) {                   
        $totalPrefijo = [];
        $totalPrefijo['apertura'] = 0;
        $totalPrefijo['periodoActual'] = 0;
        $totalPrefijo['bienpersonal'] = 0;
        $totalPrefijo['exento'] = 0;

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
        <tr class="trnoclickeable trTitle">
            <th colspan="4"><?php echo $nombrePrefijo; ?></th>
            <th colspan=""></th>
            <th colspan=""></th>
            <th colspan=""></th>
            <th colspan=""></th>
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
            $totalPrefijo['bienpersonal'] += $arrayCuentasxPeriodos[$numerodecuenta]['bienpersonal'];
            $totalPrefijo['exento'] += $arrayCuentasxPeriodos[$numerodecuenta]['exento'];
                      
            $totalNota['apertura'] += $arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual];
            $totalNota['periodoActual'] += $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual];
            $totalNota['bienpersonal'] += $arrayCuentasxPeriodos[$numerodecuenta]['bienpersonal'];
            $totalNota['exento'] += $arrayCuentasxPeriodos[$numerodecuenta]['exento'];
         
            ?>
            <tr>
                <td colspan="4"><?php echo $arrayCuentasxPeriodos[$numerodecuenta]['nombrecuenta'];?></td>
                <td class="tdWithNumber"><?php echo number_format($arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($arrayCuentasxPeriodos[$numerodecuenta][$periodoActual], 2, ",", ".") ?></td>                
                <td class="tdWithNumber"><?php echo number_format($arrayCuentasxPeriodos[$numerodecuenta]['bienpersonal'], 2, ",", ".") ?></td>                
                <td class="tdWithNumber"><?php echo number_format($arrayCuentasxPeriodos[$numerodecuenta]['exento'], 2, ",", ".") ?></td>                
            </tr>
            <?php
           
            $total['apertura']+=$arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual];
            $total[$periodoActual]+=$arrayCuentasxPeriodos[$numerodecuenta][$periodoActual];
            $total['bienpersonal']+=$arrayCuentasxPeriodos[$numerodecuenta]['bienpersonal'];
            $total['exento']+=$arrayCuentasxPeriodos[$numerodecuenta]['exento'];
        }
         ?>
        <tr>
            <th colspan="4"> Subtotal <?php echo $nombrePrefijo;?></th>
            <th class="tdWithNumber"><?php echo number_format($totalPrefijo['apertura'], 2, ",", ".") ?></th>
            <th class="tdWithNumber"><?php echo number_format($totalPrefijo['periodoActual'], 2, ",", ".") ?></th>            
            <th class="tdWithNumber"><?php echo number_format($totalPrefijo['bienpersonal'], 2, ",", ".") ?></th>            
            <th class="tdWithNumber"><?php echo number_format($totalPrefijo['exento'], 2, ",", ".") ?></th>            
        </tr>
       <?php
        
    }
    return $totalNota;
}    
function mostrarIngresos($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$nombreNota,$columnas,$nombreGastos,$colspanGastos,
        $fechaInicioConsulta,$fechaFinConsulta,$totalIngreso){
     //vamos a extender la funcionalidad de esta funcion para que abarque tmb mostrary no solo calcular
    //$numerofijo = "60101";
    //Una nota puede tener muchos prefijos y vamos a totalizar los prefijos por separado
    //y devolver el total de la nota.
    $mostrarTotal = false;
    $periodoActual = date('Y', strtotime($fechaInicioConsulta));
    $totalNota = [];
    $totalNota[$periodoActual]=0;
    $totalNota['resultadoneto']=0;
    foreach ($columnas as $columna) {
        if(!isset($totalNota[$columna]))$totalNota[$columna]=0;
    }
    $columnasAgrupadoras=[
        'Gravados','Deducibles','Otros ing. gravados','Otros conceptos deducibles',
        'Gastos deducibles'
    ];
    $columnasAgrupadas=[
        'Que Generan mov. de fondos','Que no generan mov. de fondos','Amortiz.',
        'Gravado IVA','No Gravado IVA',
        'Que Imp. Erog.','Que No Imp. Erog.',
        'Compras y Gastos Imp. al costo','Compras y Gastos No Imp. al costo'
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
                    <th colspan="20" class=" "><?php echo $nombreNota; ?></th>
                </tr>
                <tr>
                <?php
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
    foreach ($columnas as $kc => $columna) {
        if(!isset($totalNota[$columna])) {
            $totalNota[$columna]=0;
        }
    }
    if($mostrarTotal){ ?>
        <tr class="trnoclickeable">
            <th class="tdborder">Total de  <?php  echo $nombreNota; ?></th>
            <?php
                $totalGeneral=0;
                foreach ($columnas as $kc => $columna) {
                    if(!in_array($columna,$columnasAgrupadoras)&&!in_array($columna, $columnasInicio)){
                         echo '<th  class="numericTD tdborder" style="width:90px">'. number_format($totalNota[$columna], 2, ",", ".").'</td>';
                         $totalGeneral += $totalNota[$columna];
                    } 
                }
            ?>
            <th  class="numericTD tdborder" style="width:90px"><?php echo number_format($totalGeneral, 2, ",", "."); ?></td>
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
                                $subtotal = (isset($totalIngreso["No computab."])?$totalIngreso["No computab."]:0)-$totalNota[$columna];                                
                            }else if($columna=="No deducibles"){
                                $subtotal = (isset($totalIngreso["Exentos o no gravados"])?$totalIngreso["Exentos o no gravados"]:0)-$totalNota[$columna];                                
                            }else if($columna=='Costo de bienes de cambio'){
                                $subtotal = $totalNota[$columna];                                
                            }else if($columna=='Costo de bienes de cambio'){
                                $subtotal = $totalNota[$columna];                                
                            }else if($columna=='Que Imp. Erog.'){
                                if(isset($totalIngreso['Que Imp. Erog.'])){
                                    $subtotal = $totalIngreso[$columna]-
                                        $totalNota[$columna];     
                                }else{
                                    $subtotal = $totalIngreso['Gravado IVA']-
                                        $totalNota[$columna];
                                }
                            }else if($columna=='Que No Imp. Erog.'){
                                if(isset($totalIngreso['Que No Imp. Erog.'])){
                                    $subtotal = $totalIngreso[$columna]-
                                        $totalNota[$columna];     
                                }else{
                                    $subtotal = $totalIngreso['No Gravado IVA']-
                                        $totalNota[$columna];
                                }
                            }else if($columna=='Compras y Gastos Imp. al costo'){
                                if(isset($totalIngreso['Compras y Gastos Imp. al costo.'])){
                                    $subtotal = $totalIngreso[$columna]-
                                        $totalNota[$columna];     
                                }else{
                                    $subtotal = $totalIngreso['Gravado IVA']-
                                        $totalNota[$columna];
                                }
                            }else if($columna=='Compras y Gastos No Imp. al costo'){
                                if(isset($totalIngreso['Compras y Gastos No Imp. al costo'])){
                                    $subtotal = $totalIngreso[$columna]-
                                        $totalNota[$columna];     
                                }else{
                                    $subtotal = $totalIngreso['No Gravado IVA']-
                                        $totalNota[$columna];
                                }
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
                    $resultadoneto = 0;

                    $resultadoneto +=(isset($totalIngreso['Gravado IVA'])?$totalIngreso['Gravado IVA']:0);
                    $resultadoneto +=(isset($totalIngreso['No Gravado IVA'])?$totalIngreso['No Gravado IVA']:0);

                    $resultadoneto -=(isset($totalNota['Compras y Gastos Imp. al costo'])?$totalNota['Compras y Gastos Imp. al costo']:0);
                    $resultadoneto -=(isset($totalNota['Compras y Gastos No Imp. al costo'])?$totalNota['Compras y Gastos No Imp. al costo']:0);

                    $resultadoneto -=(isset($totalDiferencia['Que Imp. Erog.'])?$totalDiferencia['Que Imp. Erog.']:0);
                    $resultadoneto -=(isset($totalDiferencia['Que No Imp. Erog.'])?$totalDiferencia['Que No Imp. Erog.']:0);
                    $resultadoneto -=(isset($totalDiferencia['Que Generan mov. de fondos'])?$totalDiferencia['Que Generan mov. de fondos']:0);
                    $resultadoneto -=(isset($totalDiferencia['Que no generan mov. de fondos'])?$totalDiferencia['Que no generan mov. de fondos']:0);
                    $resultadoneto -=(isset($totalNota['Amortiz.'])?$totalNota['Amortiz.']:0);
                    $resultadoneto -=$costoventa;
                     $totalNota['resultadoneto']= $resultadoneto;
                    //falta restar Costo de ventas
                    echo '<th  class="numericTD tdborder" style="width:90px">'. number_format($resultadoneto, 2, ",", ".").'</td>';
                ?>
                <th class="tdnoborder" colspan="2">Importe neto que mueve fondos</th>
                <?php
                    $importenetoquemuevefondos = 
                            (isset($totalDiferencia['Que Generan mov. de fondos'])?$totalDiferencia['Que Generan mov. de fondos']:0)
                            +(isset($totalDiferencia['Compras y Gastos Imp. al costo'])?$totalDiferencia['Compras y Gastos Imp. al costo']:0)
                            +(isset($totalDiferencia['Compras y Gastos No Imp. al costo'])?$totalDiferencia['Compras y Gastos No Imp. al costo']:0);

                            if(isset($totalDiferencia['Que Imp. Erog.'])){
                                $importenetoquemuevefondos+=$totalDiferencia['Que Imp. Erog.'];
                            }else{
                                };
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
                            isset($totalDiferencia['No deducibles'])?$totalDiferencia['No deducibles']:0;
                     if(isset($totalIngreso['No computab.'])){
                                $resultadoneto+=$totalIngreso['No computab.'];                             
                            };
                    echo '<th  class="numericTD tdborder" style="width:90px">'. number_format($resultadoexento, 2, ",", ".").'</td>';
                ?>
                <th class="tdnoborder" colspan="2">Importe neto que no mueve fondos</th>
                <?php
                    $importenetoquenomuevefondos = 
                            isset($totalDiferencia['Que no generan mov. de fondos'])?$totalDiferencia['Que no generan mov. de fondos']:0
                            -isset($totalDiferencia['Que No Imp. Erog.'])?$totalDiferencia['Que No Imp. Erog.']:0;
                            if(isset($totalDiferencia['Amortiz.'])){
                                $resultadoneto-=$totalDiferencia['Amortiz.'];                             
                            }                            
                            
                    //falta restar Costo de ventas
                    echo '<th  class="numericTD tdborder" style="width:90px">'. number_format($importenetoquenomuevefondos, 2, ",", ".").'</td>';
                ?>
            </tr>
            <tr class="trnoclickeable tdnoborder">
                <th class="tdnoborder" style="border: 0px solid #000;">Total</th>
                <?php
                    $total1 =$resultadoneto+$resultadoexento;
                    echo '<th  class="numericTD tdborder" style="width:90px">'. number_format($total1, 2, ",", ".").'</td>';
                ?>
                <th class="" colspan="2">Total</th>
                <?php
                    $total2 = $importenetoquemuevefondos+$importenetoquenomuevefondos;                          
                    //falta restar Costo de ventas
                    echo '<th  class="numericTD tdborder" style="width:90px">'. number_format($total2, 2, ",", ".").'</td>';
                ?>
            </tr>
        <?php
        }
        ?>
        <tr>
            <td colspan="16" class="tdnoborder" style="border: 0px solid #000;">
                <hr style=" display: none;margin-top: 0.5em;margin-bottom: 0.5em;margin-left: auto;margin-right: auto;border-style: inset;border-width: 1px;">
            </td>
        </tr>
        <?php
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
                    <th colspan="20" class=" "><?php echo $nombreNota ?></th>
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
            <th class="">Subtotal</th>
            <th  class="numericTD tdborder" style="width:90px"><?php echo number_format($totalNota['real'], 2, ",", ".")?></td>
            <th  class="numericTD tdborder" style="width:90px"><?php echo number_format($totalNota['tope'], 2, ",", ".")?></td>
            <th  class="numericTD tdborder" style="width:90px"><?php echo number_format($totalNota['computable'], 2, ",", ".")?></td>
            <th  class="numericTD tdborder" style="width:90px"><?php echo number_format($totalNota['nodeducible'], 2, ",", ".")?></td>
        </tr>
    <?php
    
    }
    return $totalNota;
}
function sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayCuentas,$periodoparasumar,$keysCuentas,$tipoasiento,$suma=null){
    
    $total = 0;
    $suma = ($suma==null)?0:$suma;
    foreach ($arrayCuentas as $prefijo) {       
        $numerofijo = $prefijo;
        $indexCuentasNumeroFijo = array_keys(
            array_filter(
                $keysCuentas,
                function($var) use ($numerofijo){
                    return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                }
            )
        );
        if(count($indexCuentasNumeroFijo)!=0){ 
            foreach ($indexCuentasNumeroFijo as $index) {
                $numeroCuenta = $keysCuentas[$index];
              
                switch ($tipoasiento) {
                    case 'apertura':
                        $total += $arrayCuentasxPeriodos[$numeroCuenta]['apertura'][$periodoparasumar];
                        break;
                    case 'movimientos':
                        $total += $arrayCuentasxPeriodos[$numeroCuenta]['movimiento'][$periodoparasumar];
                        break;
                    case 'distribucion de dividendos':
                        $total += $arrayCuentasxPeriodos[$numeroCuenta]['distribucion de dividendos'][$periodoparasumar];
                        break;
                    case 'Absorcion de perdida acumulada':
                        $total += $arrayCuentasxPeriodos[$numeroCuenta]['Absorcion de perdida acumulada'][$periodoparasumar];
                        break;
                    case 'costoscompra':                       
                        $total += $arrayCuentasxPeriodos[$numeroCuenta]['costoscompra'][$periodoparasumar];
                        break;
                    case 'noDedGeneral':                       
                        $total += $arrayCuentasxPeriodos[$numeroCuenta]['noDedGeneral'][$periodoparasumar];
                        break;
                    case 'todos':
                        $total += $arrayCuentasxPeriodos[$numeroCuenta][$periodoparasumar];
                        break;
                    default:
                        $total += $arrayCuentasxPeriodos[$numeroCuenta][$periodoparasumar];
                        break;
                }
               
            }
        }
    }
    return $total*$suma;
}
function mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,&$total,$titulo,$numerocuenta,$fechaInicioConsulta,$fechaFinConsulta){
    $periodoPrevio = date('Y', strtotime($fechaFinConsulta."-1 Years"));
    $periodoActual =  date('Y', strtotime($fechaFinConsulta));
    $totalPeriodo1 = isset($arrayCuentasxPeriodos[$numerocuenta][$periodoActual])?$arrayCuentasxPeriodos[$numerocuenta][$periodoActual]:0;
    if(!isset($total[$periodoActual])){
                $total[$periodoActual] = 0;//existen estos valores
            }    
    if($totalPeriodo1==0)return;
    ?>
    <tr>
        <td colspan="2">
            <?php echo $titulo ?>
        </td>
        <?php
            echo '<td colspan="2" class="numericTD" style="width: 90px">' .
                number_format($totalPeriodo1, 2, ",", ".")
                . "</td>";
            
            $total[$periodoActual]+=$totalPeriodo1;
         
        ?>
    </tr>    
    <?php
}
function showRowAnexoICostos($titulo,$actual){
    if($actual==0)return;
    ?>
    <tr>
        <td colspan="2">
            <?php echo $titulo?>
        </td>
        <td colspan="2" class="numericTD" style="width: 90px">
        <?php
         echo number_format($actual, 2, ",", ".");
         ?></td>
    </tr>
    <?php
}
function mostrarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayCuentas,$periodoparasumar,$keysCuentas,$tipoasiento,$suma=null,$columna){
    $total = 0;
    $suma = ($suma==null)?0:$suma;
    foreach ($arrayCuentas as $prefijo) {       
        $numerofijo = $prefijo;
        $indexCuentasNumeroFijo = array_keys(
            array_filter(
                $keysCuentas,
                function($var) use ($numerofijo){
                    return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                }
            )
        );
        if(count($indexCuentasNumeroFijo)!=0){ 
            foreach ($indexCuentasNumeroFijo as $index) {
                $numeroCuenta = $keysCuentas[$index];
                $valorcuenta=0;   
                switch ($tipoasiento) {
                    case 'apertura':
                        $valorcuenta += $arrayCuentasxPeriodos[$numeroCuenta]['apertura'][$periodoparasumar];
                        $total += $arrayCuentasxPeriodos[$numeroCuenta]['apertura'][$periodoparasumar];
                        break;
                    case 'movimientos':
                        $valorcuenta += $arrayCuentasxPeriodos[$numeroCuenta]['movimiento'][$periodoparasumar];
                        $total += $arrayCuentasxPeriodos[$numeroCuenta]['movimiento'][$periodoparasumar];
                        break;
                    case 'distribucion de dividendos':
                        $valorcuenta += $arrayCuentasxPeriodos[$numeroCuenta]['distribucion de dividendos'][$periodoparasumar];
                        $total += $arrayCuentasxPeriodos[$numeroCuenta]['distribucion de dividendos'][$periodoparasumar];
                        break;
                    case 'Absorcion de perdida acumulada':
                        $valorcuenta += $arrayCuentasxPeriodos[$numeroCuenta]['Absorcion de perdida acumulada'][$periodoparasumar];
                        $total += $arrayCuentasxPeriodos[$numeroCuenta]['Absorcion de perdida acumulada'][$periodoparasumar];
                        break;
                    case 'costoscompra':                       
                        $valorcuenta += $arrayCuentasxPeriodos[$numeroCuenta]['costoscompra'][$periodoparasumar];
                        $total += $arrayCuentasxPeriodos[$numeroCuenta]['costoscompra'][$periodoparasumar];
                        break;
                    case 'todos':
                        $valorcuenta += $arrayCuentasxPeriodos[$numeroCuenta][$periodoparasumar];
                        $total += $arrayCuentasxPeriodos[$numeroCuenta][$periodoparasumar];
                        break;
                    default:
                        $valorcuenta += $arrayCuentasxPeriodos[$numeroCuenta][$periodoparasumar];
                        $total += $arrayCuentasxPeriodos[$numeroCuenta][$periodoparasumar];
                        break;
                }
               ?>
                <tr>
                    <td>
                       
                    </td>
                    <td colspan="2">
                        <?php
                        echo $arrayCuentasxPeriodos[$numeroCuenta]['nombrecuenta']
                        ?>
                    </td>
                    <?php
                    if($columna==1){
                    ?>
                    <td class="tdWithNumber">
                        <?php
                         echo number_format($valorcuenta*$suma, 2, ",", ".")
                        ?>
                    </td>
                    <?php }else{
                    ?>
                    <td class="tdWithNumber">
                       
                    </td>                   
                    <?php    
                    } 
                    if($columna==2){
                    ?>
                    <td class="tdWithNumber">
                       <?php
                         echo number_format($valorcuenta*$suma, 2, ",", ".")
                        ?>
                    </td>                   
                    <?php
                    }else{
                    ?>
                    <td class="tdWithNumber">
                       
                    </td>                   
                    <?php    
                    }
                    ?>
                </tr>
                <?php
            }
        }
    }
    
    return $total*$suma;
}
?>
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


