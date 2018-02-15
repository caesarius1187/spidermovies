<div id="contentToPrint">
<?php
echo $this->Html->script('jquery-ui',array('inline'=>false));
echo $this->Html->css('bootstrapmodal');
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
echo $this->Html->script('papelesdetrabajos/estadoderesultado',array('inline'=>false));
//echo $this->Html->script('jquery.table2excel',array('inline'=>false));
echo $this->Html->script('table2excel',array('inline'=>false));

echo $this->Html->script('jquery.dataTables.js',array('inline'=>false));
echo $this->Html->script('dataTables.altEditor.js',array('inline'=>false));
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));

//orden de impresion/
/*
01 esp
02 eerr
03 eepn
04 efe
05 nesp
06 abduv
07 neerr
08 aeerr
09 nefe
10 ajuste
*/
?>
<?php
/**
 * Created by PhpStorm.
 * User: caesarius
 * Date: 02/11/2016|
 * Time: 12:15 PM
 */
/*if(date('Y', strtotime($fechaInicioConsulta))!=date('Y', strtotime($fechaFinConsulta))){
    $periodoPrevio = date('Y', strtotime($fechaInicioConsulta));
    $periodoActual =  date('Y', strtotime($fechaFinConsulta));
}else{
     $periodoPrevio = date('Y', strtotime($fechaInicioConsulta."-1 Years"));
    $periodoActual =  date('Y', strtotime($fechaInicioConsulta));
}*/
    $periodoPrevio = date('Y', strtotime($fechaFinConsulta." -1 Years"));
    $periodoActual =  date('Y', strtotime($fechaFinConsulta));
?>
<div id="contentEstadosContables">
<div class="index" style="padding: 0px 1%; margin-bottom: 11px;" id="headerCliente">
    <div style="width:30%; float: left;padding-top:11px">
        Contribuyente: <?php echo $cliente["Cliente"]['nombre'];
        echo $this->Form->input('clientenombre',['type'=>'hidden','value'=>$cliente["Cliente"]['nombre']]);
        echo $this->Form->input('cliid',['type'=>'hidden','value'=>$cliente["Cliente"]['id']]);?>
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
        );
        $paramsPrepPapeles="'".$cliente['Cliente']['id']."','".$periodo."'";
        if(isset($cliente['Impcli'][0])){
            $paramsPrepPapeles2="'".$cliente['Impcli'][0]['id']."','".$periodo."'";
        }
        
        $tieneasientodeApertura=false;
        $tieneasientodeexistenciafinal=false;
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
        if($tieneasientodeApertura){
            $buttonclass="buttonImpcliRealizado";
        }else{
            $buttonclass="buttonImpcliListo";
        }
         echo $this->Form->button(
            'As. Apertura',
            array(
                'class'=>$buttonclass." progress-button state-loading",
                'onClick'=>"contabilizarApertura(".$paramsPrepPapeles.")",
                'id'=>'buttonAsApertura',
            ),
            array());
        if($tieneasientodeexistenciafinal){
            $buttonclass="buttonImpcliRealizado";
        }else{
            $buttonclass="buttonImpcliListo";
        }
        echo $this->Form->button(
            'As. Existencia Final',
            array(
                'class'=>$buttonclass." progress-button state-loading",
                'onClick'=>"contabilizarexistenciafinal(".$paramsPrepPapeles.")",
                'id'=>'buttonAsExistenciaFinal',
            ),
        array());
        $buttonclass="buttonImpcliListo";
        if(isset($cliente['Impcli'][0])){
            echo $this->Form->button(
                'As. Ganancias',
                array(
                    'class'=>$buttonclass." progress-button state-loading",
                    'onClick'=>"contabilizarganancias(".$paramsPrepPapeles2.")",
                    'id'=>'buttonAsExistenciaFinal',
                ),
            array());
        }?>
    </div>
</div>
<div style="width:100%; height:30px; margin-left: 11px;"  class="Formhead noExl" id="divTabs" >
    <div id="tabSumasYSaldos" class="cliente_view_tab_active" onclick="CambiarTab('sumasysaldos');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Balance de Sumas y Saldos</label>
    </div>
    <div id="tabEstadoDeResultados" class="cliente_view_tab" onclick="CambiarTab('estadoderesultado');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Estado de resultados</label>
    </div>
    <div id="tabNotas" class="cliente_view_tab" onclick="CambiarTab('notas');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Notas Estado de resultado</label>
    </div>
    <div id="tabAnexos" class="cliente_view_tab" onclick="CambiarTab('anexos');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Anexos</label>
    </div>
    <div id="tabEvolucionPatrimonioNeto" class="cliente_view_tab" onclick="CambiarTab('patrimonioneto');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Estado de Evolucion del Patrimonio Neto</label>
    </div>
    <div id="tabEvolucionSitacionPatrimonial" class="cliente_view_tab" onclick="CambiarTab('situacionpatrimonial');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Estado de Situacion Patrimonial</label>
    </div>
    <div id="tabEvolucionNotasSitacionPatrimonial" class="cliente_view_tab" onclick="CambiarTab('notassituacionpatrimonial');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Notas Estado de Situacion Patrimonial</label>
    </div>
    <div id="tabEvolucionAnexoIBienesdeUso" class="cliente_view_tab" onclick="CambiarTab('anexoibienesdeuso');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Anexo I Bienes de Uso</label>
    </div>
    <div id="tabFlujoEfectivo" class="cliente_view_tab" onclick="CambiarTab('flujoefectivo');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Estado de Flujo de Efectivo</label>
    </div>
    <div id="tabNotaFlujoEfectivo" class="cliente_view_tab" onclick="CambiarTab('notaflujoefectivo');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Notas Flujo de Efectivo</label>
    </div>
    <div id="tabAjuste" class="cliente_view_tab" onclick="CambiarTab('ajustes');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Ajustes Contables</label>
    </div>
    <div id="tabAuditor" class="cliente_view_tab" onclick="CambiarTab('auditor');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Informe Auditor</label>
    </div>
    <div id="tabNotasAclaratorias" class="cliente_view_tab" onclick="CambiarTab('notasAclaratorias');" style="width:14%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Notas Aclaratorias</label>
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
                <td>Apertura Actual</td>
                <td>Saldo Actual</td>
                <td>Apertura Anterior</td>
                <td>Saldo Anterior</td>
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
                //si la fecha del asiento es menor que la fecha de consulta inicio
                //entonces el periodo a imputar es 2016 sino 2017 por ej.
                $movimiento['debe'] = round($movimiento['debe'], 2);
                $movimiento['haber'] = round($movimiento['haber'], 2);
                if($movimiento['Asiento']['fecha']<$fechaInicioConsulta){
                    $periodoAImputar = $periodoPrevio;
                }else{
                    $periodoAImputar = $periodoActual;
                }
                if(!isset($arrayPeriodos[$periodoAImputar])){
                    $arrayPeriodos[$periodoAImputar]=[];
                    $arrayPeriodos[$periodoAImputar]['debes']=0;
                    $arrayPeriodos[$periodoAImputar]['haberes']=0;
                    $arrayPeriodos[$periodoAImputar]['apertura']=0;
                    $arrayPeriodos[$periodoAImputar]['costoscompra']=0;
                    $arrayPeriodos[$periodoAImputar]['movimiento']=0;
                    $arrayPeriodos[$periodoAImputar]['distribucion de dividendos']=0;
                    $arrayPeriodos[$periodoAImputar]['Absorcion de perdida acumulada']=0;
                }
                if(!isset($arrayCuentasxPeriodos[$numerodecuenta])){
                    $arrayCuentasxPeriodos[$numerodecuenta] = [];
                    $arrayCuentasxPeriodos[$numerodecuenta]['nombrecuenta'] = $cuentascliente['Cuentascliente']['nombre'];
                    $arrayCuentasxPeriodos[$periodoAImputar] = 0;
                    $arrayCuentasxPeriodos[$numerodecuenta]['apertura'] = [];
                    $arrayCuentasxPeriodos[$numerodecuenta]['costoscompra'] = [];
                    $arrayCuentasxPeriodos[$numerodecuenta]['movimiento'] = [];     
                    $arrayCuentasxPeriodos[$numerodecuenta]['distribucion de dividendos'] = [];     
                    $arrayCuentasxPeriodos[$numerodecuenta]['Absorcion de perdida acumulada'] = [];                                                             
                }          
                
                if($movimiento['Asiento']['tipoasiento']=='Apertura'){
                    $arrayPeriodos[$periodoAImputar]['apertura']+=round($movimiento['debe'], 2);
                    $arrayPeriodos[$periodoAImputar]['apertura']-=round($movimiento['haber'], 2);
                }else{
                    $arrayPeriodos[$periodoAImputar]['movimiento']+=round($movimiento['debe'], 2);
                    $arrayPeriodos[$periodoAImputar]['movimiento']-=round($movimiento['haber'], 2);
                }
                if($movimiento['Asiento']['tipoasiento']=='Distribucion de dividendos'){
                    $arrayPeriodos[$periodoAImputar]['distribucion de dividendos']+=round($movimiento['debe'], 2);
                    $arrayPeriodos[$periodoAImputar]['distribucion de dividendos']-=round($movimiento['haber'], 2);
                }
                if($movimiento['Asiento']['tipoasiento']=='Absorcion de perdida acumulada'){
                    $arrayPeriodos[$periodoAImputar]['Absorcion de perdida acumulada']+=round($movimiento['debe'], 2);
                    $arrayPeriodos[$periodoAImputar]['Absorcion de perdida acumulada']-=round($movimiento['haber'], 2);
                }
                if($movimiento['Asiento']['tipoasiento']=='costoscompra'){
                    $arrayPeriodos[$periodoAImputar]['costoscompra']+=round($movimiento['debe'], 2);
                    $arrayPeriodos[$periodoAImputar]['costoscompra']-=round($movimiento['haber'], 2);
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
            $saldoCalculado= round($saldoCalculado, 2);
            switch ($charinicial){
                case "1":
                case "3":
                case "5":
                    if($saldoCalculado>=0){
                        $colorTR= "";
                    }else{
                        $colorTR= "#ffae00";
                    }
                    break;
                case "2":
                case "6":
                    if($saldoCalculado<=0){
                        $colorTR= "";
                    }else{
                        $colorTR= "#ffae00";
                    }
                    break;
            }
            
            ?>
            <tr class="trclickeable" cuecliid="<?php echo $cuentascliente['Cuentascliente']['id']?>" style="background-color: <?php echo $colorTR?>;">
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
                    $arrayPeriodos[$periodoActual]['costoscompra']=0;
                    $arrayPeriodos[$periodoActual]['movimiento']=0;
                    $arrayPeriodos[$periodoActual]['distribucion de dividendos']=0;
                    $arrayPeriodos[$periodoActual]['Absorcion de perdida acumulada']=0;
                }if(!isset($arrayPeriodos[$periodoPrevio])){
                    $arrayPeriodos[$periodoPrevio]=[];
                    $arrayPeriodos[$periodoPrevio]['debes']=0;
                    $arrayPeriodos[$periodoPrevio]['haberes']=0;
                    $arrayPeriodos[$periodoPrevio]['apertura']=0;
                    $arrayPeriodos[$periodoPrevio]['costoscompra']=0;
                    $arrayPeriodos[$periodoPrevio]['movimiento']=0;
                    $arrayPeriodos[$periodoPrevio]['distribucion de dividendos']=0;
                    $arrayPeriodos[$periodoPrevio]['Absorcion de perdida acumulada']=0;
                }
                $saldo = $arrayPeriodos[$periodoActual]['debes']-$arrayPeriodos[$periodoActual]['haberes'];
                $apertura = $arrayPeriodos[$periodoActual]['apertura'];
                echo '<td  class="numericTD">'.
                    number_format($apertura, 2, ",", ".")
                    ."</td>";
                echo '<td  class="numericTD">'.
                    number_format($saldo, 2, ",", ".")
                    ."</td>";
                
                
                if(!isset($arrayCuentasxPeriodos[$numerodecuenta][$periodoActual])){
                    $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual]=0;
                    $arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual] = 0;
                    $arrayCuentasxPeriodos[$numerodecuenta]['costoscompra'][$periodoActual] = 0;
                    $arrayCuentasxPeriodos[$numerodecuenta]['movimiento'][$periodoActual] = 0;   
                    $arrayCuentasxPeriodos[$numerodecuenta][$periodoPrevio]=0;
                    $arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoPrevio] = 0;
                    $arrayCuentasxPeriodos[$numerodecuenta]['costoscompra'][$periodoPrevio] = 0;
                    $arrayCuentasxPeriodos[$numerodecuenta]['movimiento'][$periodoPrevio] = 0;   
                }
                $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual]=$saldo;
                $arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoActual]=$arrayPeriodos[$periodoActual]['apertura'];
                $arrayCuentasxPeriodos[$numerodecuenta]['costoscompra'][$periodoActual]=$arrayPeriodos[$periodoActual]['costoscompra'];
                $arrayCuentasxPeriodos[$numerodecuenta]['movimiento'][$periodoActual]=$arrayPeriodos[$periodoActual]['movimiento'];
                
                $saldo = $arrayPeriodos[$periodoPrevio]['debes']-$arrayPeriodos[$periodoPrevio]['haberes'];
                $apertura = $arrayPeriodos[$periodoPrevio]['apertura'];
                echo '<td  class="numericTD">'.
                    number_format($apertura, 2, ",", ".")
                    ."</td>";
                echo '<td  class="numericTD">'.
                    number_format($saldo, 2, ",", ".")
                    ."</td>";
                
                $arrayCuentasxPeriodos[$numerodecuenta][$periodoPrevio]=$saldo;
                $arrayCuentasxPeriodos[$numerodecuenta]['apertura'][$periodoPrevio]=$arrayPeriodos[$periodoPrevio]['apertura'];
                $arrayCuentasxPeriodos[$numerodecuenta]['costoscompra'][$periodoPrevio]=$arrayPeriodos[$periodoPrevio]['costoscompra'];
                $arrayCuentasxPeriodos[$numerodecuenta]['movimiento'][$periodoPrevio]=$arrayPeriodos[$periodoPrevio]['movimiento'];
                
               
                if(!isset($arrayCuentasxPeriodos[$numerodecuenta]['distribucion de dividendos'])){
                    $arrayCuentasxPeriodos[$numerodecuenta]['distribucion de dividendos']=[];
                    $arrayCuentasxPeriodos[$numerodecuenta]['distribucion de dividendos'][$periodoActual]=0;
                    $arrayCuentasxPeriodos[$numerodecuenta]['distribucion de dividendos'][$periodoPrevio]=0;
                }
                if(!isset($arrayCuentasxPeriodos[$numerodecuenta]['Absorcion de perdida acumulada'])){
                    $arrayCuentasxPeriodos[$numerodecuenta]['Absorcion de perdida acumulada']=[];
                    $arrayCuentasxPeriodos[$numerodecuenta]['Absorcion de perdida acumulada'][$periodoActual]=0;
                    $arrayCuentasxPeriodos[$numerodecuenta]['Absorcion de perdida acumulada'][$periodoPrevio]=0;
                }
                $arrayCuentasxPeriodos[$numerodecuenta]['distribucion de dividendos'][$periodoActual]=$arrayPeriodos[$periodoActual]['distribucion de dividendos'];
                $arrayCuentasxPeriodos[$numerodecuenta]['distribucion de dividendos'][$periodoPrevio]=$arrayPeriodos[$periodoPrevio]['distribucion de dividendos'];

                $arrayCuentasxPeriodos[$numerodecuenta]['Absorcion de perdida acumulada'][$periodoActual]=$arrayPeriodos[$periodoActual]['Absorcion de perdida acumulada'];
                $arrayCuentasxPeriodos[$numerodecuenta]['Absorcion de perdida acumulada'][$periodoPrevio]=$arrayPeriodos[$periodoPrevio]['Absorcion de perdida acumulada'];
                
                //aca le vamos a agregar los datos del bien de uso al $arrayCuentasxPeriodo porque si esta cuenta apunta a un bien de uso 
                //desues vamos a necesitar los datos del BDU para mostrarlos
                //aunque si no esta relacionado a un BDU entonces vamos a mostrar loq ue se pueda y hacer los calculos q se puedan
                if(count($cuentascliente['Cuentaclientevalororigen'])>0){
                    $arrayCuentasxPeriodos[$numerodecuenta]['valororigen']=$cuentascliente['Cuentaclientevalororigen'][0]['valororiginal'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['porcentajeamortizacion']=$cuentascliente['Cuentaclientevalororigen'][0]['porcentajeamortizacion'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada']=$cuentascliente['Cuentaclientevalororigen'][0]['amortizacionacumulada'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['periodo']=$cuentascliente['Cuentaclientevalororigen'][0]['periodo'];                
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionejercicio']=$cuentascliente['Cuentaclientevalororigen'][0]['importeamorteizaciondelperiodo']+
                            $cuentascliente['Cuentaclientevalororigen'][0]['importeamortizacionaceleradadelperiodo'];         
                    foreach ($cuentascliente['Cuentaclientevalororigen'][0]['Amortizacione'] as $ka => $amortizacione) {
                        if(!isset($arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'])){
                            $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial']=[];
                        }
                        $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'][$amortizacione['periodo']]=[];
                        $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'][$amortizacione['periodo']]['ejercicio']=$amortizacione['amortizacionejercicio'];
                        $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'][$amortizacione['periodo']]['amortizacion']=$amortizacione['amortizacionacumulada'];
                    }
                }
                if(count($cuentascliente['Cuentaclienteactualizacion'])>0){
                    $arrayCuentasxPeriodos[$numerodecuenta]['valororigen']=$cuentascliente['Cuentaclienteactualizacion'][0]['valororiginal'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['porcentajeamortizacion']=$cuentascliente['Cuentaclienteactualizacion'][0]['porcentajeamortizacion'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada']=$cuentascliente['Cuentaclienteactualizacion'][0]['amortizacionacumulada'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['periodo']=$cuentascliente['Cuentaclienteactualizacion'][0]['periodo'];                
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionejercicio']=$cuentascliente['Cuentaclienteactualizacion'][0]['importeamorteizaciondelperiodo']+
                            $cuentascliente['Cuentaclienteactualizacion'][0]['importeamortizacionaceleradadelperiodo'];    
                    foreach ($cuentascliente['Cuentaclientevalororigen'][0]['Amortizacione'] as $ka => $amortizacione) {
                        if(!isset($arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'])){
                            $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial']=[];
                        }
                        $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'][$amortizacione['periodo']]=[];
                        $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'][$amortizacione['periodo']]['ejercicio']=$amortizacione['amortizacionejercicio'];
                        $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'][$amortizacione['periodo']]['amortizacion']=$amortizacione['amortizacionacumulada'];
                    }
                }
                if(count($cuentascliente['Cuentaclienteterreno'])>0){
                    $arrayCuentasxPeriodos[$numerodecuenta]['valororigen']=$cuentascliente['Cuentaclienteterreno'][0]['valororiginal'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['porcentajeamortizacion']=$cuentascliente['Cuentaclienteterreno'][0]['porcentajeamortizacion'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada']=$cuentascliente['Cuentaclienteterreno'][0]['amortizacionacumulada'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['periodo']=$cuentascliente['Cuentaclienteterreno'][0]['periodo'];                
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionejercicio']=$cuentascliente['Cuentaclienteterreno'][0]['importeamorteizaciondelperiodo']+
                            $cuentascliente['Cuentaclienteterreno'][0]['importeamortizacionaceleradadelperiodo'];            
                    foreach ($cuentascliente['Cuentaclientevalororigen'][0]['Amortizacione'] as $ka => $amortizacione) {
                        if(!isset($arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'])){
                            $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial']=[];
                        }
                        $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'][$amortizacione['periodo']]=[];
                        $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'][$amortizacione['periodo']]['ejercicio']=$amortizacione['amortizacionejercicio'];
                        $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'][$amortizacione['periodo']]['amortizacion']=$amortizacione['amortizacionacumulada'];
                    }
                }
                if(count($cuentascliente['Cuentaclienteedificacion'])>0){
                    $arrayCuentasxPeriodos[$numerodecuenta]['valororigen']=$cuentascliente['Cuentaclienteedificacion'][0]['valororiginal'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['porcentajeamortizacion']=$cuentascliente['Cuentaclienteedificacion'][0]['porcentajeamortizacion'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada']=$cuentascliente['Cuentaclienteedificacion'][0]['amortizacionacumulada'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['periodo']=$cuentascliente['Cuentaclienteedificacion'][0]['periodo'];                
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionejercicio']=$cuentascliente['Cuentaclienteedificacion'][0]['importeamorteizaciondelperiodo']+
                            $cuentascliente['Cuentaclienteedificacion'][0]['importeamortizacionaceleradadelperiodo'];                
                    foreach ($cuentascliente['Cuentaclientevalororigen'][0]['Amortizacione'] as $ka => $amortizacione) {
                        if(!isset($arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'])){
                            $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial']=[];
                        }
                        $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'][$amortizacione['periodo']]=[];
                        $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'][$amortizacione['periodo']]['ejercicio']=$amortizacione['amortizacionejercicio'];
                        $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'][$amortizacione['periodo']]['amortizacion']=$amortizacione['amortizacionacumulada'];
                    }
                }
                if(count($cuentascliente['Cuentaclientemejora'])>0){
                    $arrayCuentasxPeriodos[$numerodecuenta]['valororigen']=$cuentascliente['Cuentaclientemejora'][0]['valororiginal'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['porcentajeamortizacion']=$cuentascliente['Cuentaclientemejora'][0]['porcentajeamortizacion'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada']=$cuentascliente['Cuentaclientemejora'][0]['amortizacionacumulada'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['periodo']=$cuentascliente['Cuentaclientemejora'][0]['periodo'];                
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionejercicio']=$cuentascliente['Cuentaclientemejora'][0]['importeamorteizaciondelperiodo']+
                            $cuentascliente['Cuentaclientemejora'][0]['importeamortizacionaceleradadelperiodo'];                
                    foreach ($cuentascliente['Cuentaclientevalororigen'][0]['Amortizacione'] as $ka => $amortizacione) {
                        if(!isset($arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'])){
                            $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial']=[];
                        }
                        $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'][$amortizacione['periodo']]=[];
                        $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'][$amortizacione['periodo']]['ejercicio']=$amortizacione['amortizacionejercicio'];
                        $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'][$amortizacione['periodo']]['amortizacion']=$amortizacione['amortizacionacumulada'];
                    }
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
<div class="index estadocontable" id="divContenedorNotas">
    <table id="tblnotas"  class="toExcelTable tblEstadoContable" cellspacing="0" style="">
        <thead>
            <tr class="trnoclickeable trTitle">
                <th colspan="4" style="text-align: left">
                    Notas y Anexos Estado de Resultados</br>
                    Denominacion: <?php echo $cliente['Cliente']['nombre'];?>
                </th>
            </tr>
            <tr class="trTitle">
                <th colspan="4" style="text-align: center">
                    Notas y Anexos Estado de Resultados
                </th>
            </tr>
            <tr class="trTitle" style="text-align: center">
                <th colspan="4" >
                    Notas a los Estados de Contables al <?php echo date('d-m-Y', strtotime($fechaFinConsulta));; ?> comparativo con el Ejercicio anterior
                </th>
            </tr>
        </thead>
        <tbody>
        <?php
        $numeroDeNota = 1;
        $totalVentasBienes = [];
       
        $arrayPrefijos=[];
        $arrayPrefijos['601010']=[];
        $arrayPrefijos['601010']['nombre']=['Ventas de Bienes'];
        $arrayPrefijos['601010']['nombrenota']=['Ventas de Bienes y Servicios'];
        $arrayPrefijos['601011']['nombre']=['Ventas de Bienes Exentos'];
        $arrayPrefijos['601011']['nombrenota']=['Ventas de Bienes y Servicios'];
        /*
         * FALTAN LOS SERVICIOS
         * $arrayPrefijos['60101']=[];
        $arrayPrefijos['60101']['nombre']=['Ventas de Bienes'];
        $arrayPrefijos['60101']['nombrenota']=['Ventas de Bienes y Servicios'];*/
        $totalVentasBienes = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta);
               
        $totalReintegros = [];
       
        $arrayPrefijos=[];
        $arrayPrefijos['601012']=[];
        $arrayPrefijos['601012']['nombre']=['Reintegros'];
        $arrayPrefijos['601012']['nombrenota']=['Reintegros'];
        $totalReintegros = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta);
                
        $numerofijo = "601013";
        $totalDesgravaciones = [];
       
        $arrayPrefijos=[];
        $arrayPrefijos['601013']=[];
        $arrayPrefijos['601013']['nombre']=['Desgravaciones'];
        $arrayPrefijos['601013']['nombrenota']=['Desgravaciones'];

        $totalDesgravaciones = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta);
               
        $numerofijo = "601014";
        $totalProduccionagropecuaria = [];
       
        $arrayPrefijos=[];
        $arrayPrefijos['601014']=[];
        $arrayPrefijos['601014']['nombre']=['Resultado neto por produccion agropecuaria'];
        $arrayPrefijos['601014']['nombrenota']=['Resultado neto por produccion agropecuaria'];

        $totalProduccionagropecuaria = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta);
               
        $numerofijo = "601015";
        $totalValuacionbienesdecambio = [];
       
        $arrayPrefijos=[];
        $arrayPrefijos['601015']=[];
        $arrayPrefijos['601015']['nombre']=['Resultado por valuacion de bienes de cambio al VNR'];
        $arrayPrefijos['601015']['nombrenota']=['Resultado por valuacion de bienes de cambio al VNR'];

        $totalValuacionbienesdecambio = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta);
        
        /*$numerofijo = "504990";
        $totalOtrosGastos = [];
        
        $arrayPrefijos=[];
        $arrayPrefijos['504990']=[];
        $arrayPrefijos['504990']['nombre']=['Otros Gastos'];
        $arrayPrefijos['504990']['nombrenota']=['Otros Gastos'];

        $totalOtrosGastos = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta);
        */
        $numerofijo = "601016";
        $totalinversionesenentes = [];
      
        $arrayPrefijos=[];
        $arrayPrefijos['601016']=[];
        $arrayPrefijos['601016']['nombre']=['Resultado de inversiones en entes relacionados'];
        $arrayPrefijos['601016']['nombrenota']=['Resultado de inversiones en entes relacionados'];

        $totalinversionesenentes = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta);

        $numerofijo = "601017";
        $totalresultadosfinancieros = [];
        
        $arrayPrefijos=[];
        $arrayPrefijos['601017']=[];
        $arrayPrefijos['601017']['nombre']=['Resultados financieros y por tenencia'];
        $arrayPrefijos['601017']['nombrenota']=['Resultados financieros y por tenencia'];

        $totalresultadosfinancieros = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta);
                     
        $numerofijo = "601017";
        $totalInteresdelcapitalpropio = [];

        $arrayPrefijos=[];
        $arrayPrefijos['601018']=[];
        $arrayPrefijos['601018']['nombre']=['Inter&eacute;s del capital propio'];
        $arrayPrefijos['601018']['nombrenota']=['Inter&eacute;s del capital propio'];

        $totalInteresdelcapitalpropio = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta);       
        $totalOtrosIngresos = [];
        $arrayPrefijos=[];

        $arrayPrefijos['601019']=[];
        $arrayPrefijos['601019']['nombre']=['Otros Ingresos'];
        $arrayPrefijos['601019']['nombrenota']=['Total de Otros Ingresos No Prorrateables'];
        $arrayPrefijos['601022']=[];
        $arrayPrefijos['601022']['nombre']=['Ingresos Extraordinarios'];
        $arrayPrefijos['601022']['nombrenota']=['Total de Otros Ingresos No Prorrateables'];
        $arrayPrefijos['607030']=[];
        $arrayPrefijos['607030']['nombre']=['Donaciones'];
        $arrayPrefijos['607030']['nombrenota']=['Total de Otros Ingresos No Prorrateables'];
        $arrayPrefijos['608020']=[];
        $arrayPrefijos['608020']['nombre']=['Otros Ingresos No Prorrateables'];
        $arrayPrefijos['608020']['nombrenota']=['Total de Otros Ingresos No Prorrateables'];
        $totalOtrosIngresos = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta);
        $numerofijo = "506110";
        $totalImpuestoALaGanancia = [];
        ?>
        <?php
        $arrayPrefijos=[];
        $arrayPrefijos['506110']=[];
        $arrayPrefijos['506110']['nombre']=['Impuesto a las ganancias'];
        $arrayPrefijos['506110']['nombrenota']=['Impuesto a las ganancias'];
        $arrayPrefijos['601021']=[];
        $arrayPrefijos['601021']['nombre']=['Impuesto a las ganancias Rtdo +'];
        $arrayPrefijos['601021']['nombrenota']=['Impuesto a las ganancias Rtdo +'];

        $totalImpuestoALaGanancia = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta);
        ?>
        </tbody>
        <tfoot>
        </tfoot>
    </table>
</div>
 
<div class="index estadocontable" id="divContenedorAnexos">
    <div id="AnexoI" class="" style="">
        <table id="tblAnexoI"  class="toExcelTable tblEstadoContable tbl_verticalborder tblAnexoI" cellspacing="0">
            <thead>
                <tr class="trnoclickeable trTitle">
                    <th colspan="6" style="text-align: left">
                        Notas y Anexos Estado de Resultados</br>
                        Denominacion: <?php echo $cliente['Cliente']['nombre'];?>
                    </th>
                </tr>
                <tr class="trnoclickeable trTitle">
                    <th colspan="6" style="text-align: center">
                        Anexo I: Costo de los Bienes Vendidos, Servicios Prestados y de Producci&oacute;n al
                        <?php echo date('d-m-Y', strtotime($fechaFinConsulta)); ?>  comparativo con el Ejercicio Anterior
                    </th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <th class="" colspan="2">
                    
                </th>
                <th class="" colspan="2" style="text-align: center;width: 90px">
                    Actual
                </th>
                <th class="" colspan="2" style="text-align: center;width: 90px">
                    Anterior
                </th>
                
            </tr>
            <tr class="trTitle">
                <th colspan="2">
                    Existencia Inicial
                </th>
                <th colspan="2" style="text-align: center;width: 90px">
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
                if(!isset($totalPeriodoExistenciaInicial[$periodoPrevio])){
                                    $totalPeriodoExistenciaInicial[$periodoPrevio] = 0;//existen estos valores
                                } /*existencia inicial del periodo anterior
                  * 110500011 Mercader&iacute;as XX E Inicial
                110502011 Prod. Terminado XX E Inicial
                110504011 Prod. en Proceso XX E Inicial
                110506011 MP y Materiales XX E Inicial
                110507011 Otros Bienes de Cambio E Inicial*/
                $existenciaInicialMercaderiasAnterior = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110500011"],$periodoPrevio,$keysCuentas,'apertura',1);
                $existenciaInicialProdTerminadoAnterior = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110502011"],$periodoPrevio,$keysCuentas,'apertura',1);
                $existenciaInicialProdEnProcAnterior = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110504011"],$periodoPrevio,$keysCuentas,'apertura',1);
                $existenciaInicialMpMaterialsAnterior = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110506011"],$periodoPrevio,$keysCuentas,'apertura',1);
                $existenciaInicialOtrosAnterior = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110507011"],$periodoPrevio,$keysCuentas,'apertura',1);
                $totalPeriodoExistenciaInicial[$periodoPrevio] += $existenciaInicialMercaderiasAnterior + $existenciaInicialProdTerminadoAnterior 
                        + $existenciaInicialProdEnProcAnterior + $existenciaInicialMpMaterialsAnterior + $existenciaInicialOtrosAnterior;
                showRowAnexoICostos("Mercader&iacute;as",$existenciaInicialMercaderias,$existenciaInicialMercaderiasAnterior);    
                showRowAnexoICostos("Productos Terminados",$existenciaInicialProdTerminado,$existenciaInicialProdTerminadoAnterior);    
                showRowAnexoICostos("Producci&oacute;n en Proceso",$existenciaInicialProdEnProc,$existenciaInicialProdEnProcAnterior);    
                showRowAnexoICostos("Materias Primas e Insumos incorporados a la producci&oacute;n",$existenciaInicialMpMaterialsAnterior,$existenciaInicialMpMaterials);    
                showRowAnexoICostos("Insumos Incorporados a la Prestaci&oacute;n de Servicios",0,0);    
                showRowAnexoICostos("Otros",$existenciaInicialOtros,$existenciaInicialOtrosAnterior);    
                showRowAnexoICostos("Participaci&oacute;n en negocios conjuntos",0,0);    
                ?>                    
            <tr class="trTitle">
                <th class="" colspan="2">
                    Total Existencia Inicial
                </th>
                <?php
                echo '<th colspan="2" class="numericTD " style=";width: 90px">' .
                        number_format($totalPeriodoExistenciaInicial[$periodoActual], 2, ",", ".")
                        . "</th>";
                echo '<th colspan="2" class="numericTD " style=";width: 90px">' .
                        number_format($totalPeriodoExistenciaInicial[$periodoPrevio], 2, ",", ".")
                        . "</th>";
                ?>
            </tr>
            <tr class="trTitle">
                <th colspan="2">
                    Compras
                </th>
                <th colspan="2" style="text-align: center;width: 90px">
                </th>
                <th colspan="2" style="text-align: center;width: 90px">
                </th>
            </tr>
                <?php
                $totalPeriodoExistenciaFinal=[] ;
                //COMPRAS PERIODO ACTUAL + Existencia FINAL ACTUAL - EXISTENCIA INICIAL ACTUAL
                
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
                
                //110500013 Mercader&iacute;as XX E Final
                //110502013 Prod. Terminado XX E Final
                //110504013 Prod. en Proceso XX E Final
                //110506013 MP y Materiales XX EFIN
                //110507013 Otros Bienes de Cambio EFin*/
                $existenciaFinalMercaderias = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110500013"],$periodoActual,$keysCuentas,'todos',-1);
                $existenciaFinalProdTerminado = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110502013"],$periodoActual,$keysCuentas,'todos',-1);
                $existenciaFinalProdEnProc = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110504013"],$periodoActual,$keysCuentas,'todos',-1);
                $existenciaFinalMpMaterials = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110506013"],$periodoActual,$keysCuentas,'todos',-1);
                $existenciaFinalOtros = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110507013"],$periodoActual,$keysCuentas,'todos',-1);
                
                $totalPeriodoExistenciaFinal[$periodoActual] = $existenciaFinalMercaderias + $existenciaFinalProdTerminado + $existenciaFinalProdEnProc 
                        + $existenciaFinalMpMaterials + $existenciaFinalOtros;
                //estoy sacando esta variable de dos lugares 
                //o lo calculamos o lo traemos de contabilidad
                
                /*$existenciaComprasMercaderias = $existenciaComprasMercaderias + $existenciaFinalMercaderias - $existenciaInicialMercaderias;
                $existenciaComprasProdTerminado = $existenciaComprasProdTerminado + $existenciaFinalProdTerminado - $existenciaInicialProdTerminado;
                $existenciaComprasProdEnProc = $existenciaComprasProdEnProc + $existenciaFinalProdEnProc - $existenciaInicialProdEnProc;
                $existenciaComprasMpMaterials = $existenciaComprasMpMaterials + $existenciaFinalMpMaterials - $existenciaInicialMpMaterials;
                $existenciaComprasOtros = $existenciaComprasOtros + $existenciaFinalOtros - $existenciaInicialOtros;*/
                $totalPeriodoCompras[$periodoActual] = $existenciaComprasMercaderias + $existenciaComprasProdTerminado + $existenciaComprasProdEnProc
                        + $existenciaComprasMpMaterials + $existenciaComprasOtros;
                //COMPRAS PERIODO ANTERIOR + Existencia FINAL ANTERIOR - EXISTENCIA INICIAL ANTERIOR
                
                //110500012 Mercader&iacute;as XX Compras
                //110502012 Prod. Terminado XX Compras
                //110504012 Prod. en Proceso XX Compras
                //110506012 MP y Materiales XX Compras
                //110507012 Otros bienes de cambio XX Compras*/
                
                $existenciaComprasMercaderiasAnterior = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110500012"],$periodoPrevio,$keysCuentas,'costoscompra',-1);
                $existenciaComprasProdTerminadoAnterior  = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110502012"],$periodoPrevio,$keysCuentas,'costoscompra',-1);
                $existenciaComprasProdEnProcAnterior  = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110504012"],$periodoPrevio,$keysCuentas,'costoscompra',-1);
                $existenciaComprasMpMaterialsAnterior  = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110506012"],$periodoPrevio,$keysCuentas,'costoscompra',-1);
                $existenciaComprasOtrosAnterior  = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110507012"],$periodoPrevio,$keysCuentas,'costoscompra',-1);

            //110500013 Mercader&iacute;as XX E Final
            //110502013 Prod. Terminado XX E Final
            //110504013 Prod. en Proceso XX E Final
            //110506013 MP y Materiales XX EFIN
            //110507013 Otros Bienes de Cambio EFin*/
            $existenciaFinalMercaderiasAnterior  = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110500013"],$periodoPrevio,$keysCuentas,'todos',-1);
            $existenciaFinalProdTerminadoAnterior  = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110502013"],$periodoPrevio,$keysCuentas,'todos',-1);
            $existenciaFinalProdEnProcAnterior  = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110504013"],$periodoPrevio,$keysCuentas,'todos',-1);
            $existenciaFinalMpMaterialsAnterior  = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110506013"],$periodoPrevio,$keysCuentas,'todos',-1);
            $existenciaFinalOtrosAnterior  = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110507013"],$periodoPrevio,$keysCuentas,'todos',-1);

            $totalPeriodoExistenciaFinal[$periodoPrevio] = $existenciaFinalMercaderiasAnterior + $existenciaFinalProdTerminadoAnterior + $existenciaFinalProdEnProcAnterior 
                    + $existenciaFinalMpMaterialsAnterior + $existenciaFinalOtrosAnterior;

            //estoy sacando esta variable de dos lugares 
            //o lo calculamos o lo traemos de contabilidad
           /* $existenciaComprasMercaderiasAnterior  = $existenciaComprasMercaderiasAnterior  + $existenciaFinalMercaderiasAnterior  - $existenciaInicialMercaderiasAnterior ;
            $existenciaComprasProdTerminadoAnterior  = $existenciaComprasProdTerminadoAnterior  + $existenciaFinalProdTerminadoAnterior - $existenciaInicialProdTerminadoAnterior ;
            $existenciaComprasProdEnProcAnterior  = $existenciaComprasProdEnProcAnterior  + $existenciaFinalProdEnProcAnterior  - $existenciaInicialProdEnProcAnterior ;
            $existenciaComprasMpMaterialsAnterior  = $existenciaComprasMpMaterialsAnterior  + $existenciaFinalMpMaterialsAnterior  - $existenciaInicialMpMaterialsAnterior ;
            $existenciaComprasOtrosAnterior  = $existenciaComprasOtrosAnterior  + $existenciaFinalOtrosAnterior  - $existenciaInicialOtrosAnterior ;*/

            $totalPeriodoCompras[$periodoPrevio] = $existenciaComprasMercaderiasAnterior + $existenciaComprasProdTerminadoAnterior + $existenciaComprasProdEnProcAnterior
                    + $existenciaComprasMpMaterialsAnterior + $existenciaComprasOtrosAnterior;


            showRowAnexoICostos("Mercader&iacute;as",$existenciaComprasMercaderias,$existenciaComprasMercaderiasAnterior);
              
            //mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoCompras,"Productos Terminados","110502012",$fechaInicioConsulta,$fechaFinConsulta);
            //mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoCompras,"Producci&oacute;n en Proceso","110504012",$fechaInicioConsulta,$fechaFinConsulta);
            //mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoCompras,"Materias Primas e Insumos incorporados a la producci&oacute;n","110506012",$fechaInicioConsulta,$fechaFinConsulta);
            showRowAnexoICostos("Productos Terminados",$existenciaComprasProdTerminado,$existenciaComprasProdTerminadoAnterior);
            showRowAnexoICostos("Producci&oacute;n en Proceso",$existenciaComprasProdEnProc,$existenciaComprasProdEnProcAnterior);
            showRowAnexoICostos("Materias Primas e Insumos incorporados a la producci&oacute;n",$existenciaComprasMpMaterials,$existenciaComprasMpMaterialsAnterior);
            showRowAnexoICostos("Insumos Incorporados a la Prestaci&oacute;n de Servicios",0,0);
            showRowAnexoICostos("Otros: Gastos Activados",$existenciaComprasOtros,$existenciaComprasOtrosAnterior);
            showRowAnexoICostos("Participaci&oacute;n en negocios conjuntos",0,0);
            ?>         
            <tr class="trTitle">
                <th class="" colspan="2">
                    Total de Compras
                </th>
                <?php
                echo '<th colspan="2" class="numericTD " style="width: 90px">' .
                        number_format($totalPeriodoCompras[$periodoActual], 2, ",", ".")
                        . "</th>";
                 echo '<th colspan="2" class="numericTD " style="width: 90px">' .
                        number_format($totalPeriodoCompras[$periodoPrevio], 2, ",", ".")
                        . "</th>";
                ?>
            </tr>
            
            <tr class="trTitle">
                <th colspan="2">
                    Devoluciones de Compras
                </th>
                <th colspan="2" style="text-align: center;width: 90px">
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

            showRowAnexoICostos("Insumos Incorporados a la Prestaci&oacute;n de Servicios",0,0);
            showRowAnexoICostos("Otros",0,0);
            showRowAnexoICostos("Participaci&oacute;n en negocios conjuntos",0,0);
            
            ?>
            <tr class="trTitle">
                <th class="" colspan="2">
                    Total de Devoluciones de Compras
                </th>
                <?php
                echo '<th colspan="2" class="numericTD " style="width: 90px">' .
                        number_format($totalPeriodoDevoluciones[$periodoActual], 2, ",", ".")
                        . "</th>";
                echo '<th colspan="2" class="numericTD " style="width: 90px">' .
                        number_format($totalPeriodoDevoluciones[$periodoPrevio], 2, ",", ".")
                        . "</th>";
                ?>
            </tr>
            <!--
            <tr>
                <th class="" colspan="2">
                    Gastos Imputables al Costo
                </th>
                <th class="" colspan="2" style="width: 90px"></th>
                <th class="" colspan="2" style="width: 90px"></th>
            </tr>
            <tr>
                <th class="" colspan="2">
                    Costos Financieros Activados
                </th>
                <th class="" colspan="2" style="width: 90px"></th>
                <th class="" colspan="2" style="width: 90px"></th>
            </tr>
            <tr>
                <th colspan="2">
                    Resultado por Tenencia
                </th>
                <th class=" " colspan="2" style="width: 90px"></th>
                <th class=" " colspan="2" style="width: 90px"></th>
            </tr>
            <tr>
                <th class=" " colspan="2">
                    Otros
                </th>
                <th class=" " colspan="2" style="width: 90px"></th>
                <th class=" " colspan="2" style="width: 90px"></th>
            </tr>-->
            <tr class="trTitle">
                <th colspan="2">
                    Existencia Final
                </th>
                <th colspan="2" style="text-align: center">
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
                  echo '<td colspan="2" class="numericTD" style="width: 90px">' .
                        number_format($totalPeriodoExistenciaFinal[$periodoPrevio], 2, ",", ".")
                        . "</td>";
                ?>
            </tr>
            <?php
             mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoExistenciaFinal,"Productos Terminados","110502013",$fechaInicioConsulta,$fechaFinConsulta);
             mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoExistenciaFinal,"Producci&oacute;n en Proceso","110504013",$fechaInicioConsulta,$fechaFinConsulta);
             mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoExistenciaFinal,"Materias Primas y Materiales","110506013",$fechaInicioConsulta,$fechaFinConsulta);
            ?>
            <!--
            <tr>
                <td colspan="2">
                    Insumos Incorporados a la Prestaci&oacute;n de Servicios
                </td>
                <td colspan="2" style="width: 90px"></td>
                <td colspan="2" style="width: 90px"></td>
            </tr>
            <tr>
                <td colspan="2">
                    Otros
                </td>
                <td colspan="2" style="width: 90px"></td>
                <td colspan="2" style="width: 90px"></td>
            </tr>
            <tr>
                <td colspan="2">
                    Participaci&oacute;n en negocios conjuntos
                </td>
                <td colspan="2" style="width: 90px"></td>
                <td colspan="2" style="width: 90px"></td>
            </tr>
            -->
            <tr class="trTitle">
                <th class=" " colspan="2">
                    Total Existencia Final
                </th>
                <?php
               echo '<th colspan="2" class="numericTD  " style="width: 90px">' .
                        number_format($totalPeriodoExistenciaFinal[$periodoActual], 2, ",", ".")
                        . "</th>";
               echo '<th colspan="2" class="numericTD  " style="width: 90px">' .
                        number_format($totalPeriodoExistenciaFinal[$periodoPrevio], 2, ",", ".")
                        . "</th>";
                ?>
            </tr>
            <!--
            <tr class="trTitle">
                <th colspan="2">
                    Prestaci&oacute;n de Servicios
                </th>
                <th colspan="2" style="width: 90px"></th>
                <th colspan="2" style="width: 90px"></th>
            </tr>
            <tr>
                <td colspan="2">
                    Costo de Prestaci&oacute;n Servicios
                </td>
                <td colspan="2" style="width: 90px"></td>
                <td colspan="2" style="width: 90px"></td>
            </tr>
            <tr>
                <td colspan="2">
                    Indumentaria
                </td>
                <td colspan="2" style="width: 90px"></td>
                <td colspan="2" style="width: 90px"></td>
            </tr>
            <tr>
                <td colspan="2">
                    Insumo
                </td>
                <td colspan="2" style="width: 90px"></td>
                <td colspan="2" style="width: 90px"></td>
            </tr>
            <tr class="trTitle">
                <th class=" " colspan="2">
                    Total Existencia Final
                </th>
                <th class=" " colspan="2" style="width: 90px"></th>
                <th class="" colspan="2" style="width: 90px"></th>
            </tr>-->
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
                echo '<th colspan="2" class="numericTD" style="width: 90px">' ;
                    $totalPeriodo = $totalPeriodoExistenciaInicial[$periodoPrevio];
                    $totalPeriodo += $totalPeriodoCompras[$periodoPrevio];
                    $totalPeriodo -= $totalPeriodoDevoluciones[$periodoPrevio];
                    $totalPeriodo += $totalPeriodoExistenciaFinal[$periodoPrevio];
                   
                    echo number_format($totalPeriodo, 2, ",", ".");
                    echo  "</th>";
                    if(!isset($totalPeriodoCostoBienesServiciosProduccion[$periodoPrevio])){
                        $totalPeriodoCostoBienesServiciosProduccion[$periodoPrevio] = 0;//existen estos valores
                    }
                    $totalPeriodoCostoBienesServiciosProduccion[$periodoPrevio] += $totalPeriodo;
                ?>
            </tr>
            </tbody>
        </table>
    </div>
    <div id="AnexoII" class="" style="">
        <table id="tblAnexoII" class="toExcelTable tbl_border tblEstadoContable tblAnexoII">
            <thead>
                <tr class="trnoclickeable trTitle">
                    <th colspan="6" style="text-align: left">
                        Notas y Anexos Estado de Resultados</br>
                        Denominacion: <?php echo $cliente['Cliente']['nombre'];?>
                    </th>
                </tr>
                <tr class="trnoclickeable trTitle">
                    <th colspan="6" style="text-align: center">
                        Anexo II de Costos de Producci&oacute;n y Gastos clasificados por su naturaleza al
                        <?php echo date('d-m-Y', strtotime($fechaFinConsulta)); ?>  comparativo con el Ejercicio Anterior
                    </th>
                </tr>
                <tr>
                    <th rowspan="2"></th>
                    <th colspan="4" style="text-align: center">Ejercicio Actual</th>
                    <th colspan="1" style="text-align: center">Ejercicio Anterior</th>
                </tr>
                <tr>
                    <th style="width:70px;text-align: center;font-size: 8px;">Costo de vta, producc. y adq. de bs</th>
                    <th style="width:70px;text-align: center;font-size: 8px;">Gtos de Admin</th>
                    <th style="width:70px;text-align: center;font-size: 8px;">Gtos de Comerc</th>
                    <th style="width:70px;text-align: center;font-size: 8px;">Total</th>
                    <th style="width:70px;text-align: center;font-size: 8px;">Total</th>
                </tr>
                <tr>
                    <th>Porcentajes para el prorrateo</th>
                    <th style="width:70px;text-align: center;font-size: 8px;">0%</th>
                    <th style="width:70px;text-align: center;font-size: 8px;">25%</th>
                    <th style="width:70px;text-align: center;font-size: 8px;">75%</th>
                    <th style="width:70px;text-align: center;font-size: 8px;">100%</th>
                    <th style="width:70px;text-align: center;font-size: 8px;"></th>
                </tr>
            </thead>
            <tbody>
             <?php
                $totalAnexoII = [];
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Combustibles, Lubricantes y FM","50401000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Servicios P&uacute;blicos","50402000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Alquileres y Expensas","50403000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                ?>
                <tr >
                    <th colspan="">Amortizaciones</th>
                    <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
                </tr>
                <?php
                $subtotalAmortizacionesInmuebles = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Amortizaci&oacute;n Inmuebles","50404100",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalAmortizacionesRodados = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Amortizaci&oacute;n Rodados","50404200",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalAmortizacionesInstalaciones = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Amortizaci&oacute;n Instalaciones","50404300",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalAmortizacionesMueblesYUtiles = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Amortizaci&oacute;n Muebles y Utiles","50404400",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalAmortizacionesMaquinarias = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Amortizaci&oacute;n Maquinarias","50404500",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalAmortizacionesActivosBiologicos = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Amortizaci&oacute;n Activos Biol&oacute;gico","50404600",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalTraslados = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Gastos de Traslados","50405000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalSeguros = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Seguros","50406000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalCortesiaYHomenaje = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Gastos de Cortesia y Homenaje", "50407000", $fechaInicioConsulta, $fechaFinConsulta,$totalAnexoII);
                $subtotalMantenimientoReparacion = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Mantenimiento, Reparaci&oacute;n, etc","50408000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalLibreria = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Gastos de Librer&iacute;a","50409000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalVarios = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Gastos Varios","50410000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalIncobrables = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Incobrables", "50411000", $fechaInicioConsulta, $fechaFinConsulta,$totalAnexoII);
                $subtotalHonorariosDirectores = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Honorarios Directores","50412000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalHonorariosSindicos = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Honorarios Sindicos","50413000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalOtrosGastos = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Otros Gastos (no prorrateables)", "5049900", $fechaInicioConsulta, $fechaFinConsulta,$totalAnexoII);
               ?>
                <tr class="trTitle">
                    <th colspan="">Gastos Financieros</th>
                    <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
               </tr>
               <tr class="trTitle">
                    <th colspan="">Gtos. Financ. de Act. Operativ</th>
                    <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
               </tr>
            <?php
                //CONSULTAR POR QUE NO TENEMOS LA CUENTA PROVDORES!!!!!!
                //mostrarNotasDeGastos($arrayCuentasxPeriodos,"Proveedores","50501010",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalAcredoresVarios = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Acreedores Varios","505030",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
            ?>
            <tr class="trTitle">
                <th colspan="">Entidades Crediticias</th>
                <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
            </tr>
            <?php
                $subtotalBancos1 = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Banco","5050101",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalBancos2 = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Banco","5050102",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalBancos3 = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Banco","5050203",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalBancos4 = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Banco","5050204",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalBancos5 = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Banco","5050205",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalBancos6 = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Banco","5050206",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalBancos7 = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Banco","5050207",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalBancos8 = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Banco","5050208",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalBancos9 = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Banco","5050209",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalBancos10 = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Banco","5050210",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                
                //NO TENEMOS OTRAS ENTIDADES CREDITICIAS
                //mostrarNotasDeGastos($arrayCuentasxPeriodos,"Otras Entidades Crediticias","50503000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
            ?>
            <tr >
                <th colspan="">Organismos P&uacute;blicos</th>
                <th colspan=""></th>
                <th colspan=""></th>
                <th colspan=""></th>
                <th colspan=""></th>
                <th colspan=""></th>
            </tr>
            <?php
                $subtotalAFIP = mostrarNotasDeGastos($arrayCuentasxPeriodos,"AFIP","50504010",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalDGR =  mostrarNotasDeGastos($arrayCuentasxPeriodos,"DGR","5050402",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalDGRM =  mostrarNotasDeGastos($arrayCuentasxPeriodos,"DGRM","5050403",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
            ?>
            <tr class="trTitle"><th colspan="">Gastos Fiscales</th>
                <th colspan=""></th>
                <th colspan=""></th>
                <th colspan=""></th>
                <th colspan=""></th>
                <th colspan=""></th>
            </tr>
            <tr class="trTitle"><th colspan="">Gastos Fiscales - AFIP</th>
                <th colspan=""></th>
                <th colspan=""></th>
                <th colspan=""></th>
                <th colspan=""></th>
                <th colspan=""></th>
            </tr>
            <?php
                //Preguntar si este no va!!!
                //mostrarNotasDeGastos($arrayCuentasxPeriodos,"Ganancias","50611000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalGananciaMinimaPresunta = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Ganancia M&iacute;n. Presunta","50612000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalBienesPersonales = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Bienes Personales","50613000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalOtrosImpuestosNacionales = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Otros Impuestos Nacionales","5061400",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
            ?>
            <tr class="trTitle"><th colspan="">Gastos Fiscales - DGR</th>
                <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
            </tr>
            <?php
                $subtotalIngresosbrutos = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Ingresos Brutos","50621000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalCooperadoraAsistencial = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Cooperadoras Asistenciales","50622000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalInmobiliarioRural = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Inmobiliario Rural","50623000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalImpuestoaSellos= mostrarNotasDeGastos($arrayCuentasxPeriodos,"Impuesto a los Sellos","50624000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
            ?>
            <tr class="trTitle"><th colspan="">Gastos Fiscales - DGRM</th>
                <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
            </tr>
            <?php
                $subtotalActividadesVarias = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Actividades Varias","50631",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalTasadepublicidadypropaganda = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Tasa de publicidad y Propaganda","50632000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalInmobiliarioUrbano = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Inmobiliario Urbano","50633000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalAlumbradoyLimpieza = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Alumbrado y Limpieza","50634000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalImpuestoAutomotor = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Impuesto Automotor","50635000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
            ?>
            <tr ><th colspan="">Remuneraciones y Cargas Sociales</th>
                <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
                    <th colspan=""></th>
            </tr>
            <?php
                $subtotalManodeObra = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Mano de Obra","50302000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalContribucionesEmpleador = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Contribuciones Empleador","503030",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalManodeObra2 = mostrarNotasDeGastos( $arrayCuentasxPeriodos,"Mano de Obra", "5071000", $fechaInicioConsulta, $fechaFinConsulta,$totalAnexoII);
                $subtotalContribucionesEmpleador2 = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Contribuciones Empleador","5072000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                $subtotalGastosExtraordinarios = mostrarNotasDeGastos($arrayCuentasxPeriodos,"Gastos Extraordinarios","50900000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
            $mesAMostrar = $periodoActual;
            $subtottalPeriodo = $totalAnexoII[$mesAMostrar];
            echo '<tr class="trTitle">
                    <th>Total Ejercicio Actual</th>
                    <th  class="numericTD">' .
                number_format($subtottalPeriodo*0.0, 2, ",", ".")
                . "</th>";
            $totalAnexoIIActualAdmin = $subtottalPeriodo*0.25;
            $totalAnexoIIActualAdmin = round($totalAnexoIIActualAdmin,2,PHP_ROUND_HALF_UP);
            $totalAnexoIIActualComer = $subtottalPeriodo*0.75;
            $totalAnexoIIActualComer = round($totalAnexoIIActualComer,2,PHP_ROUND_HALF_DOWN);
            echo '<th  class="numericTD"  title="'.$subtottalPeriodo*0.25.'">' .
                number_format($totalAnexoIIActualAdmin, 2, ",", ".")
                . "</th>";
            echo '<th  class="numericTD"  title="'.$subtottalPeriodo*0.75.'">' .
                number_format($totalAnexoIIActualComer, 2, ",", ".")
                . "</th>";
            echo '<th  class="numericTD">' .
                number_format($subtottalPeriodo, 2, ",", ".")
                . "</th>";
            $mesAMostrar = $periodoPrevio;
            $subtottalPeriodo = $totalAnexoII[$mesAMostrar];
            $totalAnexoIIPrevioAdmin = $subtottalPeriodo*0.25;
            $totalAnexoIIPrevioAdmin = round($totalAnexoIIPrevioAdmin,2,PHP_ROUND_HALF_UP);
            $totalAnexoIIPrevioComer = $subtottalPeriodo*0.75;
            $totalAnexoIIPrevioComer = round($totalAnexoIIPrevioComer,2,PHP_ROUND_HALF_DOWN);
            echo '<th  class="numericTD">' .
                number_format($subtottalPeriodo, 2, ",", ".")
                . "</th></tr>";
            echo '<tr class="trTitle">
                    <th>Total Ejercicio Anterior</th>
                    <th  class="numericTD">' .
                number_format($subtottalPeriodo*0.0, 2, ",", ".")
                . "</th>";
            echo '<th  class="numericTD"  title="'.$subtottalPeriodo*0.25.'">' .
                number_format($totalAnexoIIPrevioAdmin, 2, ",", ".")
                . "</th>";
            echo '<th  class="numericTD"  title="'.$subtottalPeriodo*0.75.'">' .
                number_format($totalAnexoIIPrevioComer, 2, ",", ".")
                . "</th>";
            echo '<th  class="numericTD">' .
                number_format($subtottalPeriodo, 2, ",", ".")
                . "</th></tr>";
            ?>
            </tbody>
        </table>
    </div><!--Anexo II-->
</div>
 
<div class="index estadocontable" id="divContenedorEstadosResultados">
    <table id="tblestadoderesultado"  class="toExcelTable tbl_border tblEstadoContable" cellspacing="0" style="">
        <thead>
            <tr class="trnoclickeable trTitle">
                <th colspan="4" style="text-align: left">
                    Estado de Resultados</br>
                    Denominacion: <?php echo $cliente['Cliente']['nombre'];?>
                </th>
            </tr>
            <tr class="trnoclickeable trTitle">
                <th colspan="4" style="text-align: center">
                    Estado de Resultados por el Ejercicio Anual Finalizado el <?php echo date('d-m-Y', strtotime($fechaFinConsulta));; ?> comparativo con el ejercicio anterior
                </th>
            </tr>
        </thead>
        <tbody>
        <tr class="trTitle">
            <th>Resultados de las operaciones que contin&uacute;an</th>
            <th></th>
            <th style="width:80px;">Actual</th>
            <th style="width:80px;">Anterior</th>
        </tr>
        <tr>
            <td>
                Ventas netas de bienes y servicios
                <?php
                $numeroDeNota = "";
                if(isset($totalVentasBienes['numeronota'])){
                    $numeroDeNota = $totalVentasBienes['numeronota'];//existen estos valores
                }
                $totalPeriodo=[];
                ?>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
           
                if(!isset($totalPeriodo[$periodoActual])){
                    $totalPeriodo[$periodoActual] = 0;//existen estos valores
                }
                 if(!isset($totalPeriodo[$periodoPrevio])){
                    $totalPeriodo[$periodoPrevio] = 0;//existen estos valores
                }
                $totalActual = isset($totalVentasBienes[$periodoActual])?$totalVentasBienes[$periodoActual]:0;
                $totalPrevio = isset($totalVentasBienes[$periodoPrevio])?$totalVentasBienes[$periodoPrevio]:0;
                showRowEERR($totalActual,$totalPrevio,$periodoActual,$periodoPrevio,$totalPeriodo)
            ?>
        </tr>
        <tr>
            <td>
                Costo de bienes vendidos y servicios prestados
            </td>
            <td>
                Anexo I
            </td>
            <?php
                showRowEERR($totalPeriodoCostoBienesServiciosProduccion[$periodoActual]*-1,
                        $totalPeriodoCostoBienesServiciosProduccion[$periodoPrevio]*-1,
                        $periodoActual,$periodoPrevio,$totalPeriodo)
            ?>
        </tr>
        <?php
        $numeroDeNota = "";
        if(isset($totalReintegros['numeronota'])){
            $numeroDeNota = $totalReintegros['numeronota'];//existen estos valores
            ?>
        <tr>
            <td>
                Reintegros
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
                $totalActual = isset($totalReintegros[$periodoActual])?$totalReintegros[$periodoActual]:0;
                $totalPrevio = isset($totalReintegros[$periodoPrevio])?$totalReintegros[$periodoPrevio]:0;
                showRowEERR($totalActual,$totalPrevio,$periodoActual,$periodoPrevio,$totalPeriodo)
            ?>
        </tr>
            <?php
            }
                ?>
        <?php
        $numeroDeNota = "";
        if(isset($totalDesgravaciones['numeronota'])){
            $numeroDeNota = $totalDesgravaciones['numeronota'];//existen estos valores
        ?><tr>
            <td>
                Desgravaciones
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
                $totalActual = isset($totalDesgravaciones[$periodoActual])?$totalDesgravaciones[$periodoActual]:0;
                $totalPrevio = isset($totalDesgravaciones[$periodoPrevio])?$totalDesgravaciones[$periodoPrevio]:0;
                showRowEERR($totalActual,$totalPrevio,$periodoActual,$periodoPrevio,$totalPeriodo)
            ?>
        </tr>
         <?php }
        ?>
        <tr class="trTitle">
            <th>Ganancia Bruta</th>
            <th>
            </th>
            <?php
            echo '<th  class="numericTD">' .
                    number_format($totalPeriodo[$periodoActual], 2, ",", ".")
                    . "</th>";
            echo '<th  class="numericTD">' .
                    number_format($totalPeriodo[$periodoPrevio], 2, ",", ".")
                    . "</th>";
            ?>
        </tr>
        <?php
        $numeroDeNota = "";
        if(isset($totalProduccionagropecuaria['numeronota'])){
            $numeroDeNota = $totalProduccionagropecuaria['numeronota'];//existen estos valores
            ?>
        <tr>
            <td>Resultado neto por producci&oacute;n agropecuaria</td>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
                $totalActual = isset($totalProduccionagropecuaria[$periodoActual])?$totalProduccionagropecuaria[$periodoActual]:0;
                $totalPrevio = isset($totalProduccionagropecuaria[$periodoPrevio])?$totalProduccionagropecuaria[$periodoPrevio]:0;
                showRowEERR($totalActual,$totalPrevio,$periodoActual,$periodoPrevio,$totalPeriodo)
            ?>
            
        </tr>
        <?php  }
            ?>
         <?php
        $numeroDeNota = "";
        if(isset($totalValuacionbienesdecambio['numeronota'])){
            $numeroDeNota = $totalValuacionbienesdecambio['numeronota'];//existen estos valores
            ?>
        <tr>
            <td>Rtdo por valuaci&oacute;n de bienes de cambio al VNR
               
               
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
                $totalActual = isset($totalValuacionbienesdecambio[$periodoActual])?$totalValuacionbienesdecambio[$periodoActual]:0;
                $totalPrevio = isset($totalValuacionbienesdecambio[$periodoPrevio])?$totalValuacionbienesdecambio[$periodoPrevio]:0;
                showRowEERR($totalActual,$totalPrevio,$periodoActual,$periodoPrevio,$totalPeriodo)
            ?>
            
        </tr>
        <?php }
                ?>
        <tr>
            <td>Gastos indirectos de prestaci&oacute;n de servicios</td>
            <td>
                Anexo II
            </td>
            <td class="numericTD">
                <?php
                echo number_format($totalAnexoII[$periodoActual]*0.0, 2, ",", ".");
                ?>
            </td>
            <td class="numericTD">
                <?php
                echo number_format($totalAnexoII[$periodoPrevio]*0.0, 2, ",", ".");
                ?>
            </td>
            
        </tr>
        <tr>
            <td>Gasto de comercializaci&oacute;n</td>
            <td>
                Anexo II
            </td>
            <td class="numericTD"  title="<?php echo $totalAnexoII[$periodoActual]*0.75; ?>">
                <?php
                echo number_format($totalAnexoII[$periodoActual]*0.75, 2, ",", ".");
                ?>
            </td>
            <td class="numericTD"  title="<?php echo $totalAnexoII[$periodoPrevio]*0.75; ?>">
                <?php
                echo number_format($totalAnexoII[$periodoPrevio]*0.75, 2, ",", ".");
                ?>
            </td>
           
        </tr>
        <tr>
            <td>Gasto de administraci&oacute;n</td>
            <td>
                Anexo II
            </td>
            <td class="numericTD" title="<?php echo $totalAnexoII[$periodoActual]*0.25; ?>">
                <?php echo number_format($totalAnexoII[$periodoActual]*0.25, 2, ",", "."); ?>
            </td>
            <td class="numericTD" title="<?php echo $totalAnexoII[$periodoPrevio]*0.25; ?>">
                <?php
                echo number_format($totalAnexoII[$periodoPrevio]*0.25, 2, ",", ".");
                if(!isset($totalPeriodo[$periodoActual])){
                    $totalPeriodo[$periodoActual] = 0;//existen estos valores
                }
                $totalPeriodo[$periodoActual] += $totalAnexoII[$periodoActual];
                if(!isset($totalPeriodo[$periodoPrevio])){
                    $totalPeriodo[$periodoPrevio] = 0;//existen estos valores
                }
                $totalPeriodo[$periodoPrevio] += $totalAnexoII[$periodoPrevio];
                ?>
            </td>
            
        </tr>
        <!--
        <tr>
            <td>Gastos operativos</td>
            <td>
                
            </td>
            <td class="numericTD">
                0.00
            </td>
            <td class="numericTD">
                0.00
            </td>
           
        </tr>
        -->
        <?php /*
        <tr>
            <td>Otros gastos
                <?php
                $numeroDeNota = "";
                if(isset($totalOtrosGastos['numeronota'])){
                    $numeroDeNota = $totalOtrosGastos['numeronota'];//existen estos valores
                }
                ?>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
                $totalActual = isset($totalOtrosGastos[$periodoActual])?$totalOtrosGastos[$periodoActual]:0;
                $totalPrevio = isset($totalOtrosGastos[$periodoPrevio])?$totalOtrosGastos[$periodoPrevio]:0;
                showRowEERR($totalActual,$totalPrevio,$periodoActual,$periodoPrevio,$totalPeriodo)
            ?>
        </tr>
        */ ?>
        <?php
        $numeroDeNota = "";
        if(isset($totalinversionesenentes['numeronota'])){
            $numeroDeNota = $totalinversionesenentes['numeronota'];//existen estos valores
            ?>
        <tr>
            <td>Resultado de inversiones en entes relacionados
                
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
                $totalActual = isset($totalinversionesenentes[$periodoActual])?$totalinversionesenentes[$periodoActual]:0;
                $totalPrevio = isset($totalinversionesenentes[$periodoPrevio])?$totalinversionesenentes[$periodoPrevio]:0;
                showRowEERR($totalActual,$totalPrevio,$periodoActual,$periodoPrevio,$totalPeriodo);
            ?>
        </tr>
        <?php
        }
        ?>       
         <?php
        $numeroDeNota = "";
        if(isset($totalresultadosfinancieros['numeronota'])){
            $numeroDeNota = $totalresultadosfinancieros['numeronota'];//existen estos valores
            ?>
        <tr>
            <td>Resultados financieros y por tenencia
               
              
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
                $totalActual = isset($totalresultadosfinancieros[$periodoActual])?$totalresultadosfinancieros[$periodoActual]:0;
                $totalPrevio = isset($totalresultadosfinancieros[$periodoPrevio])?$totalresultadosfinancieros[$periodoPrevio]:0;
                showRowEERR($totalActual,$totalPrevio,$periodoActual,$periodoPrevio,$totalPeriodo);
            ?>
        </tr>
        <?php
          }
                ?>
         <?php
        $numeroDeNota = "";
        if(isset($totalInteresdelcapitalpropio['numeronota'])){
            $numeroDeNota = $totalInteresdelcapitalpropio['numeronota'];//existen estos valores
            ?>
        <tr>
            <td>Inter&eacute;s del capital propio
               
               
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
                $totalActual = isset($totalInteresdelcapitalpropio[$periodoActual])?$totalInteresdelcapitalpropio[$periodoActual]:0;
                $totalPrevio = isset($totalInteresdelcapitalpropio[$periodoPrevio])?$totalInteresdelcapitalpropio[$periodoPrevio]:0;
                showRowEERR($totalActual,$totalPrevio,$periodoActual,$periodoPrevio,$totalPeriodo);
            ?>
        </tr>
        <?php 
         }
                ?>
         <?php
        $numeroDeNota = "";
        if(isset($totalOtrosIngresos['numeronota'])){
            $numeroDeNota = $totalOtrosIngresos['numeronota'];//existen estos valores
            ?>
        <tr>
            <td>Otros ingresos
               
            
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:"";?>
            </td>
            <?php
                $totalActual = isset($totalOtrosIngresos[$periodoActual])?$totalOtrosIngresos[$periodoActual]:0;
                $totalPrevio = isset($totalOtrosIngresos[$periodoPrevio])?$totalOtrosIngresos[$periodoPrevio]:0;
                showRowEERR($totalActual,$totalPrevio,$periodoActual,$periodoPrevio,$totalPeriodo);
            ?>
        </tr>
        <?php
            }
                ?>
        <tr class="trTitle">
            <th>
                Ganancia antes del impuesto a las ganancias de operaciones que contin&uacute;an
            </th>
            <th></th>
            <?php
            $GananciaAntesDeImpuestoALasGanancias=$totalPeriodo[$periodoActual];
            echo '<td  class="numericTD">' .
                    number_format($totalPeriodo[$periodoActual], 2, ",", ".")
                    . "</td>";
            echo '<td  class="numericTD">' .
                    number_format($totalPeriodo[$periodoPrevio], 2, ",", ".")
                    . "</td>";
            ?>
        </tr>
        <tr>
            <td>
                Impuesto a las ganancias 	
                <?php
                $numeroDeNota = "";
                if(isset($totalImpuestoALaGanancia['numeronota'])){
                    $numeroDeNota = $totalImpuestoALaGanancia['numeronota'];//existen estos valores
                }
                ?>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php           
                $totalActualImpuetoALaGanancia = isset($totalImpuestoALaGanancia[$periodoActual])?$totalImpuestoALaGanancia[$periodoActual]:0;
                $totalPrevioImpuetoALaGanancia = isset($totalImpuestoALaGanancia[$periodoPrevio])?$totalImpuestoALaGanancia[$periodoPrevio]:0;
                showRowEERR($totalActualImpuetoALaGanancia,$totalPrevioImpuetoALaGanancia,$periodoActual,$periodoPrevio,$totalPeriodo);
            ?>
        </tr>
        <tr class="trTitle">
            <th>
                Ganancia de las operaciones que contin&uacute;an
            </th>
            <th></th>
            <?php
            echo '<td  class="numericTD">' .
                    number_format($totalPeriodo[$periodoActual], 2, ",", ".")
                    . "</td>";
            echo '<td  class="numericTD">' .
                    number_format($totalPeriodo[$periodoPrevio], 2, ",", ".")
                    . "</td>";
            ?>
        </tr>
        <!--<tr class="trTitle">
            <th>
                Resultado por las operaciones en descontinuaci&oacute;n
            </th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td>
                Resultados de las operaciones antes del impuesto a las gananancias
            </td>
            <td></td>
            <td>0,00</td>
            <td>0,00</td>
        </tr>
        <tr>
            <td>
                Impuesto a las ganancias sobre los resultados de las operaciones
            </td>
            <td></td>
            <td>0,00</td>
            <td>0,00</td>
        </tr>
        <tr>
            <td>
                Resultado de las operaciones
            </td>
            <td></td>
            <td>0,00</td>
            <td>0,00</td>
        </tr>
        <tr>
            <td>
                Resultado de por disposici&oacute;n de activos o cancelaci&oacute;n de pasivos antes de impuesto a las ganancias (nota)
            </td>
            <td></td>
            <td>0,00</td>
            <td>0,00</td>
        </tr>
        <tr>
            <td>
                Impuesto a las ganancias sobre los resultados por disposici&oacute;n de activos y/o liquidaci&oacute;n de deudas
            </td>
            <td></td>
            <td>0,00</td>
            <td>0,00</td>
        </tr>
        <tr>
            <td>
                Resultado por disposici&oacute;n de activos y/o cancelaci&oacute;n de pasivos
            </td>
            <td></td>
            <td>0,00</td>
            <td>0,00</td>
        </tr>
        <tr class="trTitle">
            <th>
                Perdida por las operaciones en descontinuaci&oacute;n
            </th>
            <th></th>
            <th>0,00</th>
            <th>0,00</th>
        </tr>
        <tr class="trTitle">
            <th>
                Ganancia de las operaciones ordinarias
            </th>
            <th></th>
           <?php
            echo '<td  class="numericTD">' .
                    number_format($totalPeriodo[$periodoActual], 2, ",", ".")
                    . "</td>";
            echo '<td  class="numericTD">' .
                    number_format($totalPeriodo[$periodoPrevio], 2, ",", ".")
                    . "</td>";
            ?>
        </tr>
        <tr>
            <td>
                Resultado de las operaciones extraordinarias
            </td>
            <td></td>
            <td>0,00</td>
            <td>0,00 Chekiar esto</td>
        </tr>-->
        <tr class="trTitle">
            <th>
                Ganancia del ejercicio
            </th>
            <th></th>
            <?php
            echo '<td  class="numericTD">' .
                    number_format($totalPeriodo[$periodoActual], 2, ",", ".")
                    . "</td>";
            echo '<td  class="numericTD">' .
                    number_format($totalPeriodo[$periodoPrevio], 2, ",", ".")
                    . "</td>";
            ?>
        </tr>

        </tbody>       
    </table>
</div><!--Estado de resultados-->
 
<div class="index estadocontable" id="divContenedorEvolucionPatrimonioNeto">
    <table class="toExcelTable tbl_border tblEEPN tblEstadoContable " cellspacing="0" id="tableEvolucionPatrimonioNeto">
        <thead>
            <tr class="trnoclickeable trTitle">
                <td colspan="15" style="text-align: left;font-size: 14px;font-weight: bold;border-collapse: collapse;">
                    Estado de Evoluci&oacute;n del Patrimonio Neto</br>
                    Denominacion: <?php echo $cliente['Cliente']['nombre'];?>
                </td>
            </tr>
            <tr class="trnoclickeable trTitle">
                <td colspan="15" style="text-align: center">
                    Estado de Evoluci&oacute;n del Patrimonio Neto por el Ejercicio Anual Finalizado el <?php echo date('d-m-Y', strtotime($fechaFinConsulta));; ?> comparativo con el ejercicio anterior
                </td>
            </tr>
            <tr>
                <td rowspan="3"style="text-align: center">Rubro</td>
                <td colspan="5" style="text-align: center">Aporte de los Propietarios</td>
                <td colspan="7" style="text-align: center">Resultados Acumulados</td>
                <td colspan="2" style="text-align: center">Totales del Ejercicio</td>
            </tr>
            <tr>
                <th rowspan="2" style="text-align: center"><span>Capital Social</span></th>
                <th rowspan="2" style="text-align: center"><span>Ajuste del Capital</span></th>
                <th rowspan="2" style="text-align: center"><span>Aporte Irrevocable</span></th>
                <th rowspan="2" style="text-align: center"><span>Primera Emision</span></th>
                <th rowspan="2" style="text-align: center"><span>Total</span></th>
                <td colspan="3" style="text-align: center">Ganancias Reservadas</td>
                <td colspan="2" style="text-align: center">Resultados Diferidos</td>
                <th rowspan="2" style="text-align: center"><span>Rtdo No Asign</span></th>
                <th rowspan="2" style="text-align: center"><span>Total</span></th>
                <th rowspan="2" style="text-align: center;width: 80px"><span>Actual</span></th>
                <th rowspan="2" style="text-align: center;width: 80px"><span>Anterior</span></th>
            </tr>
            <tr>
                <th style="text-align: center"><span>Legal</span></th>
                <th style="text-align: center"><span>Otras Reservas</span></th>
                <th style="text-align: center"><span>Total</span></th>
                <th style="text-align: center"><span>Por Diferencias </br>de Conversi&oacute;n</span></th>
                <th style="text-align: center"><span>Total</span></th>
            </tr>
        </thead>
        
        <tbody>
            <tr>
                <?php
                //vamos a inicializar la row
                $saldoinicioejercicio=[];initializeRubtoEEPN($saldoinicioejercicio);                      
                $saldoinicioejercicio['capitalsocial'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["4101000"],$periodoActual,$keysCuentas,'apertura',-1)-sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["410100099"],$periodoActual,$keysCuentas,'movimientos',-1);
                $saldoinicioejercicio['ajustedelcapital'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["410200001"],$periodoActual,$keysCuentas,'apertura',-1);
                $saldoinicioejercicio['aportesirrevocables'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["410300001"],$periodoActual,$keysCuentas,'apertura',-1);
                $saldoinicioejercicio['primadeemision'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["4104"],$periodoActual,$keysCuentas,'apertura',-1);
                $saldoinicioejercicio['legal'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["420100001"],$periodoActual,$keysCuentas,'apertura',-1);
                $saldoinicioejercicio['otrasreservas'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["420100002","420100003","420100004","420199999"],$periodoActual,$keysCuentas,'apertura',-1);
                $saldoinicioejercicio['podiferenciasdeconversion'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["4202001"],$periodoActual,$keysCuentas,'apertura',-1);
                $saldoinicioejercicio['porinstrumentosderivados'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["4202002"],$periodoActual,$keysCuentas,'apertura',-1);
                $saldoinicioejercicio['resultadosnoasignados'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["420300"],$periodoActual,$keysCuentas,'apertura',-1);
                
                $saldoinicioejercicio['totalanterior'] = 
                        sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["4101000"],$periodoPrevio,$keysCuentas,'apertura',-1)
                        -sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["410100099"],$periodoPrevio,$keysCuentas,'movimientos',-1)
                        +sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["410200001"],$periodoPrevio,$keysCuentas,'apertura',-1)
                        +sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["410300001"],$periodoPrevio,$keysCuentas,'apertura',-1)
                        +sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["4104"],$periodoPrevio,$keysCuentas,'apertura',-1)
                        +sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["420100001"],$periodoPrevio,$keysCuentas,'apertura',-1)
                        +sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["420100002","420100003","420100004","420199999"],$periodoPrevio,$keysCuentas,'apertura',-1)
                        +sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["4202001"],$periodoPrevio,$keysCuentas,'apertura',-1)
                        +sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["4202002"],$periodoPrevio,$keysCuentas,'apertura',-1)
                        +sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["420300"],$periodoPrevio,$keysCuentas,'apertura',-1);
                showRowEEPN($saldoinicioejercicio,"Saldos al Inicio del Ejercicio");
                ?>
            </tr>
            <tr>
                <?php
                //vamos a inicializar la row
                $modificacioninicioejercicio=[];initializeRubtoEEPN($modificacioninicioejercicio);                             
                showRowEEPN($modificacioninicioejercicio,"Modificaci&oacute;n S. al inicio del Ejer.");
                ?>
            </tr>
            <tr class="trTitle">
                <?php
                //vamos a inicializar la row
                $saldoinicioejerciciomodificado=[];initializeRubtoEEPN($saldoinicioejerciciomodificado);              
                $saldoinicioejerciciomodificado['capitalsocial'] = $saldoinicioejercicio['capitalsocial'] + $modificacioninicioejercicio['capitalsocial'] ;
                $saldoinicioejerciciomodificado['ajustedelcapital'] = $saldoinicioejercicio['ajustedelcapital'] + $modificacioninicioejercicio['ajustedelcapital'] ;
                $saldoinicioejerciciomodificado['aportesirrevocables'] = $saldoinicioejercicio['aportesirrevocables'] + $modificacioninicioejercicio['aportesirrevocables'] ;
                $saldoinicioejerciciomodificado['primadeemision'] = $saldoinicioejercicio['primadeemision'] + $modificacioninicioejercicio['primadeemision'] ;
                $saldoinicioejerciciomodificado['legal'] = $saldoinicioejercicio['legal'] + $modificacioninicioejercicio['legal'];
                $saldoinicioejerciciomodificado['otrasreservas'] = $saldoinicioejercicio['otrasreservas'] + $modificacioninicioejercicio['otrasreservas'];
                $saldoinicioejerciciomodificado['podiferenciasdeconversion'] = $saldoinicioejercicio['podiferenciasdeconversion'] + $modificacioninicioejercicio['podiferenciasdeconversion'];
                $saldoinicioejerciciomodificado['resultadosnoasignados'] = $saldoinicioejercicio['resultadosnoasignados'] + $modificacioninicioejercicio['resultadosnoasignados'];
                $saldoinicioejerciciomodificado['totalanterior'] =  $saldoinicioejercicio['totalanterior'];
                showRowEEPN($saldoinicioejerciciomodificado,"Saldos al Inicio del Ejer. Modif.");
                   ?>
            </tr>
            <tr>
                <?php
                //vamos a inicializar la row
                $suscripcioncapitalsocial=[];initializeRubtoEEPN($suscripcioncapitalsocial);              
                $suscripcioncapitalsocial['capitalsocial'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["410100099"],$periodoActual,$keysCuentas,'movimientos',-1);
                $suscripcioncapitalsocial['totalanterior'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["410100099"],$periodoPrevio,$keysCuentas,'movimientos',-1);
                showRowEEPN($suscripcioncapitalsocial,"Suscripci&oacute;n de capital social");
                ?>
            </tr>
            <tr>
                <?php
                //vamos a inicializar la row
                $capitalicacionaportesirrevocables=[];initializeRubtoEEPN($capitalicacionaportesirrevocables);     
                $capitalicacionaportesirrevocables['ajustedelcapital'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["410300001"],$periodoActual,$keysCuentas,'movimientos',-1);
                $capitalicacionaportesirrevocables['aportesirrevocables'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["410300001"],$periodoActual,$keysCuentas,'movimientos',-1);
                $capitalicacionaportesirrevocables['totalanterior'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["410300001"],$periodoPrevio,$keysCuentas,'movimientos',-1)+sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["410300001"],$periodoPrevio,$keysCuentas,'movimientos',-1);
                showRowEEPN($capitalicacionaportesirrevocables,"Capitalizaci&oacute;n aportes irrevocab.");
               ?>
            </tr>
            <tr>
                <?php
                //vamos a inicializar la row
                $capitalicacionaportespropietarios=[];initializeRubtoEEPN($capitalicacionaportespropietarios);          
                $capitalicacionaportespropietarios['ajustedelcapital'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["4102000"],$periodoActual,$keysCuentas,'movimientos',-1);	
                $capitalicacionaportespropietarios['totalanterior'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["4102000"],$periodoPrevio,$keysCuentas,'movimientos',-1);	
                showRowEEPN($capitalicacionaportespropietarios,"Capitalizaci&oacute;n de ap. Propietarios");
                ?>
            </tr>
            <tr>
                <?php
                //vamos a inicializar la row
                $aporteparaabsorberquebrantos=[];initializeRubtoEEPN($aporteparaabsorberquebrantos);       
                $aporteparaabsorberquebrantos['primadeemision'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["4104"],$periodoActual,$keysCuentas,'movimientos',-1);
                $aporteparaabsorberquebrantos['totalanterior'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["4104"],$periodoPrevio,$keysCuentas,'movimientos',-1);
                showRowEEPN($aporteparaabsorberquebrantos,"Aportes para absorber quebrantos");
                ?>
            <tr>
                 <?php
                //vamos a inicializar la row
                $distribucionresultadosnoasignados=[];initializeRubtoEEPN($distribucionresultadosnoasignados);              
                showRowEEPN($distribucionresultadosnoasignados,"Distribuci&oacute;n rtdos no asignados");
                ?>
            </tr>
            <tr>
                 <?php
                //vamos a inicializar la row
                $reservalegal=[];initializeRubtoEEPN($reservalegal);           
                $reservalegal['legal'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["420100001"],$periodoActual,$keysCuentas,'movimientos',-1);
                $reservalegal['resultadosnoasignados'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["420100001"],$periodoActual,$keysCuentas,'movimientos',1);
                $reservalegal['totalanterior'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["420100001"],$periodoPrevio,$keysCuentas,'movimientos',-1)
                        +sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["420100001"],$periodoPrevio,$keysCuentas,'movimientos',1);
                showRowEEPN($reservalegal,"Reserva legal");
                ?>
            </tr>
            <tr>
                 <?php
                //vamos a inicializar la row
                $otrasreservas=[];initializeRubtoEEPN($otrasreservas);   
                $otrasreservas['otrasreservas'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["420100002","420100003","420100004","420100999"],$periodoActual,$keysCuentas,'movimientos',-1);
                $otrasreservas['resultadosnoasignados'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["420100002","420100003","420100004","420100999"],$periodoActual,$keysCuentas,'movimientos',1);
                $otrasreservas['totalanterior'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["420100002","420100003","420100004","420100999"],$periodoPrevio,$keysCuentas,'movimientos',-1)
                        +sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["420100002","420100003","420100004","420100999"],$periodoPrevio,$keysCuentas,'movimientos',1);
                showRowEEPN($otrasreservas,"Otras reservas");
                ?>
            </tr>
            <tr>
                 <?php
                //vamos a inicializar la row
                $dividendosenefectivos=[];initializeRubtoEEPN($dividendosenefectivos);         
                $dividendosenefectivos['resultadosnoasignados'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["430200001"],$periodoActual,$keysCuentas,'distribucion de dividendos',1);
                $dividendosenefectivos['totalanterior'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["430200001"],$periodoPrevio,$keysCuentas,'distribucion de dividendos',1);
                showRowEEPN($dividendosenefectivos,"Dividendos en efectivos");
                ?>
            </tr>
            <!--<tr>
                <td>Dividendos en especies</td>
                 <?php
                //vamos a inicializar la row
                //$dividendosenespecie=[];initializeRubtoEEPN($dividendosenespecie);              
                //showRowEEPN($dividendosenespecie);
                ?>
            </tr>
            <tr>
                <td>Dividendo en acciones/cuota</td>
                 <?php
                //vamos a inicializar la row
                //$dividendosenaccionescuotas=[];initializeRubtoEEPN($dividendosenaccionescuotas);              
                //showRowEEPN($dividendosenaccionescuotas);
                ?>
            </tr>
            <tr>
                <td>Desafectaci&oacute;n de reservas (Nota)</td>
                 <?php
                //vamos a inicializar la row
                //$desafectaciondereservas=[];initializeRubtoEEPN($desafectaciondereservas);              
                //showRowEEPN($desafectaciondereservas);
                ?>
            </tr>-->
            <tr>
                 <?php
                //vamos a inicializar la row
                $aportesirrevocables=[];initializeRubtoEEPN($aportesirrevocables);   
                $aportesirrevocables['aportesirrevocables'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["410300001"],$periodoActual,$keysCuentas,'movimientos',-1);
                $aportesirrevocables['totalanterior'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["410300001"],$periodoPrevio,$keysCuentas,'movimientos',-1);
                showRowEEPN($aportesirrevocables,'Aportes irrevocables');
                ?>
            </tr>
            <tr>
                 <?php
                //vamos a inicializar la row
                $absorciondeperdidaacumulada=[];initializeRubtoEEPN($absorciondeperdidaacumulada);  
                $absorciondeperdidaacumulada['resultadosnoasignados'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["430200001"],$periodoActual,$keysCuentas,'Absorcion de perdida acumulada',1);
                $absorciondeperdidaacumulada['totalanterior'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["430200001"],$periodoPrevio,$keysCuentas,'Absorcion de perdida acumulada',1);
                showRowEEPN($absorciondeperdidaacumulada,"Absorci&oacute;n de perdida acumuladas");
                ?>
            </tr>
            <!--<tr>
                <td>Incremento de rtdos diferidos</td>
                 <?php
                //vamos a inicializar la row
                //$incrementodertdosdiferidos=[];initializeRubtoEEPN($incrementodertdosdiferidos);              
                //showRowEEPN($incrementodertdosdiferidos);
                ?>
            </tr>
            <tr>
                <td>Desafectaci&oacute;n de rtdos diferidos</td>
                 <?php
                //vamos a inicializar la row
                //$desafectaciondertdosdiferidos=[];initializeRubtoEEPN($desafectaciondertdosdiferidos);              
                //showRowEEPN($desafectaciondertdosdiferidos);
                ?>
            </tr>-->
            <tr>
                 <?php
                //vamos a inicializar la row
                $otros=[];initializeRubtoEEPN($otros);              
                showRowEEPN($otros,"Otros");
                ?>
            </tr>
            <tr>
                 <?php
                //vamos a inicializar la row
                $resultadodelejercicio=[];initializeRubtoEEPN($resultadodelejercicio);     
                $resultadodelejercicio['resultadosnoasignados']=$totalPeriodo[$periodoActual];
                $resultadodelejercicio['totalanterior']=$totalPeriodo[$periodoPrevio];
                showRowEEPN($resultadodelejercicio,'Resultado del Ejercicio');
                ?>
            </tr>
            <tr class="trTitle">
                 <?php
                //vamos a inicializar la row
                $saldoalcierre=[];initializeRubtoEEPN($saldoalcierre);       
                foreach ($saldoalcierre as $k => $value) {
                    $subtotal=0;
                    $subtotal+=$saldoinicioejerciciomodificado[$k]*1;
                    $subtotal+=$suscripcioncapitalsocial[$k]*1;
                    $subtotal+=$capitalicacionaportesirrevocables[$k]*1;
                    $subtotal+=$aporteparaabsorberquebrantos[$k]*1;
                    $subtotal+=$capitalicacionaportespropietarios[$k]*1;
                    $subtotal+=$distribucionresultadosnoasignados[$k]*1;
                    $subtotal+=$reservalegal[$k]*1;
                    $subtotal+=$otrasreservas[$k]*1;
                    $subtotal+=$dividendosenefectivos[$k]*1;
                    $subtotal+=$aportesirrevocables[$k]*1;
                    $subtotal+=$absorciondeperdidaacumulada[$k]*1;
                    $subtotal+=$resultadodelejercicio[$k]*1;
                    $subtotal+=$otros[$k]*1;
                    $saldoalcierre[$k]=$subtotal;
                }
                showRowEEPN($saldoalcierre,'Saldo al cierre');
                ?>
            </tr>
        </tbody>
    </table>
</div><!--Estado de evolucion de patrimonio neto-->
 
<div class="index estadocontable" id="divContenedorNotaSituacionPatrimonial">
    <table id="tblnotas"  class="toExcelTable tblEstadoContable tblNotasEEPN" cellspacing="0" style="">
        <thead>
            <tr class="trnoclickeable trTitle">
                <td colspan="15" style="text-align: left;font-size: 14px;font-weight: bold;border-collapse: collapse;">
                    Notas y Anexos al Estado de Situaci&oacute;n Patrimonial</br>
                    Denominaci&oacute;n: <?php echo $cliente['Cliente']['nombre'];?>
                </td>
            </tr>            
        </thead>
        <tbody>
        <tr class="trnoclickeable">
            <th  colspan="6" class="tdnoborder trTitle">Nota 1: Normas Contables</th>
        </tr>
        <tr class="trnoclickeable">
            <th  colspan="6" class="tdnoborder">
                1.1 Consideracion del efecto por inflaci&oacute;n
            </th>
        </tr>
        <tr class="trnoclickeable">
            <td  colspan="6" class="tdnoborder">
                <span id="spnESPNota1">
                    Los Estados Contables han sido preparados sobre base hist&oacute;ricas, 
                    todo de acuerdo a lo establecido por la Resoluci&oacute;n General 
                    &numero; 287/2003 de la Federaci&oacute;n Argentina de Consejos 
                    Profesionales en Ciencias Econ&oacute;micas. Los Estados 
                    Contables han sido preparados de acuerdo a los lineamientos de 
                    presentaci&oacute;n prescriptos por la R.T. &numero;  9 de la 
                    F.A.C.P.C.E.
                </span>																						
            </td>
        </tr>
        <?php
        $numeroDeNota = 2;
        $totalCajayBancos = [];
        $arrayPrefijos=[];
        $arrayPrefijos['Caja y Valores']=[];
        $arrayPrefijos['Caja y Valores']['prefijocorriente']='110101';

        $arrayPrefijos['Bancos']=[];
        $arrayPrefijos['Bancos']['prefijocorriente']='110102';

        $arrayPrefijos['Cheques en Cartera']=[];
        $arrayPrefijos['Cheques en Cartera']['prefijocorriente']='110103';

        $arrayPrefijos['Moneda Extranjera']=[];
        $arrayPrefijos['Moneda Extranjera']['prefijocorriente']='110104';

        $totalCajayBancos = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Caja y Bancos",$fechaInicioConsulta,$fechaFinConsulta);
      /*
    <tr class="trnoclickeable">
        <td colspan="4">Nota <?php //echo $numeroDeNota; ?>: Inversiones</h3>**TEXTO*</td>
        <?php //$numeroDeNota++; ?>
    </tr>
    <tr class="trnoclickeable">
        <td colspan="4">Composicion y Evolucion del Rubro</br>(Ver Anexo Inversiones)</td>
    </tr>*/        
        $totalInversiones = [];
        $arrayPrefijos=[];
        $arrayPrefijos['Inversiones']=[];
        $arrayPrefijos['Inversiones']['prefijocorriente']='1102';
        $arrayPrefijos['Inversiones']['prefijonocorriente']='1202';
        $totalInversiones = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Inversiones",$fechaInicioConsulta,$fechaFinConsulta);
        
        $totalCreditosxVentas = [];
        $arrayPrefijos=[];
        $arrayPrefijos['Creditos por Ventas']=[];
        $arrayPrefijos['Creditos por Ventas']['prefijocorriente']='11030';
        $arrayPrefijos['Creditos por Ventas']['prefijonocorriente']='12030';
        $totalCreditosxVentas = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Creditos por Ventas",$fechaInicioConsulta,$fechaFinConsulta);
        
        $totalOtrosCreditos = [];

        $arrayPrefijos=[];
        $arrayPrefijos['Sociedades Vinculadas']=[];
        $arrayPrefijos['Sociedades Vinculadas']['prefijocorriente']='110401';
        $arrayPrefijos['Sociedades Vinculadas']['prefijonocorriente']='120401';

        $arrayPrefijos['Cta. Particular y Aporte Socio']=[];
        $arrayPrefijos['Cta. Particular y Aporte Socio']['prefijocorriente']='110402';
        $arrayPrefijos['Cta. Particular y Aporte Socio']['prefijonocorriente']='120402';

        $arrayPrefijos['Ganancias - Creditos']=[];
        $arrayPrefijos['Ganancias - Creditos']['TituloRubro']='Creditos Impositivos - AFIP';
        $arrayPrefijos['Ganancias - Creditos']['prefijocorriente']='1104031';
        $arrayPrefijos['Ganancias - Creditos']['prefijonocorriente']='1204031';

        $arrayPrefijos['Ganancia M&iacute;n. Presunta - Credi']=[];
        $arrayPrefijos['Ganancia M&iacute;n. Presunta - Credi']['prefijocorriente']='1104032';
        $arrayPrefijos['Ganancia M&iacute;n. Presunta - Credi']['prefijonocorriente']='1204032';

        $arrayPrefijos['Bienes Personales - Creditos']=[];
        $arrayPrefijos['Bienes Personales - Creditos']['prefijocorriente']='1104033';
        $arrayPrefijos['Bienes Personales - Creditos']['prefijonocorriente']='1204033';

        $arrayPrefijos['Impuesto al Valor Agregado - C']=[];
        $arrayPrefijos['Impuesto al Valor Agregado - C']['prefijocorriente']='1104034';
        $arrayPrefijos['Impuesto al Valor Agregado - C']['prefijonocorriente']='1204034';

        $arrayPrefijos['Otros Impuestos Nacionales - C']=[];
        $arrayPrefijos['Otros Impuestos Nacionales - C']['prefijocorriente']='1104038';
        $arrayPrefijos['Otros Impuestos Nacionales - C']['prefijonocorriente']='1204038';

        $arrayPrefijos['Seguridad Social - Creditos']=[];
        $arrayPrefijos['Seguridad Social - Creditos']['prefijocorriente']='1104039';
        $arrayPrefijos['Seguridad Social - Creditos']['prefijonocorriente']='1204039';

        $arrayPrefijos['Ingresos Brutos - Creditos']=[];
        $arrayPrefijos['Ingresos Brutos - Creditos']['TituloRubro']='Creditos Impositivos - DGR';
        $arrayPrefijos['Ingresos Brutos - Creditos']['prefijocorriente']='1104041';
        $arrayPrefijos['Ingresos Brutos - Creditos']['prefijonocorriente']='1204041';

        $arrayPrefijos['Cooperadoras Asistenciales - C']=[];
        $arrayPrefijos['Cooperadoras Asistenciales - C']['prefijocorriente']='1104044';
        $arrayPrefijos['Cooperadoras Asistenciales - C']['prefijonocorriente']='1204044';

        $arrayPrefijos['Impuesto a los Sellos - Credit']=[];
        $arrayPrefijos['Impuesto a los Sellos - Credit']['prefijocorriente']='1104045';
        $arrayPrefijos['Impuesto a los Sellos - Credit']['prefijonocorriente']='1204045';

        $arrayPrefijos['Actividades Varias - Creditos']=[];
        $arrayPrefijos['Actividades Varias - Creditos']['TituloRubro']='Creditos Impositivos - DGRM';
        $arrayPrefijos['Actividades Varias - Creditos']['prefijocorriente']='1104051';
        $arrayPrefijos['Actividades Varias - Creditos']['prefijonocorriente']='1204051';

        $arrayPrefijos['Otros Cr&eacute;ditos']=[];
        $arrayPrefijos['Otros Cr&eacute;ditos']['prefijocorriente']='110406';
        $arrayPrefijos['Otros Cr&eacute;ditos']['prefijonocorriente']='120406';

        $arrayPrefijos['Cr&eacute;ditos Varios']=[];
        $arrayPrefijos['Cr&eacute;ditos Varios']['prefijocorriente']='110407';
        $arrayPrefijos['Cr&eacute;ditos Varios']['prefijonocorriente']='120407';

        $arrayPrefijos['Anticipo a Proveedores']=[];
        $arrayPrefijos['Anticipo a Proveedores']['prefijocorriente']='110408';
        $arrayPrefijos['Anticipo a Proveedores']['prefijonocorriente']='120408';

        $arrayPrefijos['Previsiones Otros Creditos']=[];
        $arrayPrefijos['Previsiones Otros Creditos']['prefijocorriente']='110409';
        $arrayPrefijos['Previsiones Otros Creditos']['prefijonocorriente']='120409';

        $totalOtrosCreditos = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Otros Creditos",$fechaInicioConsulta,$fechaFinConsulta);

        $totalBienesdeCambio = [];

        $arrayPrefijos=[];
        $arrayPrefijos['Mercaderias']=[];
        $arrayPrefijos['Mercaderias']['prefijocorriente']='1105000';
        $arrayPrefijos['Mercaderias']['prefijonocorriente']='1205000';

        $arrayPrefijos['Producto Terminado']=[];
        $arrayPrefijos['Producto Terminado']['prefijocorriente']='1105020';
        $arrayPrefijos['Producto Terminado']['prefijonocorriente']='1205020';

        $arrayPrefijos['Producto en Proceso']=[];
        $arrayPrefijos['Producto en Proceso']['prefijocorriente']='1105040';
        $arrayPrefijos['Producto en Proceso']['prefijonocorriente']='1205040';

        $arrayPrefijos['Materias Primas y Materiales']=[];
        $arrayPrefijos['Materias Primas y Materiales']['prefijocorriente']='1105060';
        $arrayPrefijos['Materias Primas y Materiales']['prefijonocorriente']='1205060';

        $arrayPrefijos['Otros bienes de cambio']=[];
        $arrayPrefijos['Otros bienes de cambio']['prefijocorriente']='1105070';
        $arrayPrefijos['Otros bienes de cambio']['prefijonocorriente']='1205070';

        $totalBienesdeCambio = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Bienes de Cambio",$fechaInicioConsulta,$fechaFinConsulta);

        $totalOtrosActivos = [];

        $arrayPrefijos=[];

        $arrayPrefijos['Otros Activos Corrientes']=[];
        $arrayPrefijos['Otros Activos Corrientes']['prefijocorriente']='110600';
        $arrayPrefijos['Otros Activos Corrientes']['prefijonocorriente']='120900';

        $totalOtrosActivos = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Otros Activos",$fechaInicioConsulta,$fechaFinConsulta);

        $totalLlavenegocio = [];

        $arrayPrefijos=[];

        $arrayPrefijos['Llave de negocio']=[];
        $arrayPrefijos['Llave de negocio']['prefijocorriente']='110700';
        $arrayPrefijos['Llave de negocio']['prefijonocorriente']='121000';

        $totalLlavenegocio = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Llave de Negocio",$fechaInicioConsulta,$fechaFinConsulta);
         
        $totalBienesdeUso = [];
        ?>
        <tr class="trnoclickeable trTitle ">
            <th colspan="6" class="tdnoborder">Nota <?php echo $numeroDeNota; ?>: Bienes de Uso Comprosicion y Evolucion del Rubro</br>(Ver Anexo I Bienes de Uso)</th>
            <?php 
            $numeroNotaBienesdeUso=$numeroDeNota;

            $numeroDeNota++; ?>
        </tr>
        <tr>
            <td colspan="6">
                <hr style=" display: none;margin-top: 0.5em;margin-bottom: 0.5em;margin-left: auto;margin-right: auto;border-style: inset;border-width: 1px;">
            </td>
        </tr>
         <?php
         $totalparticipacionensociedades=[];
         $rowTituloBienDeUso='
        Informacion a revelar sobre participaciones Permanentes en Sociedades</br>**TEXTO**
        <tr class="trnoclickeable">
            <td colspan="4">Informacion sobre la aplicacion del metodo de "Valuacion Patrimonial Proporcional</br>**TEXTO**</td>
        </tr>';
                $arrayPrefijos=[];
                $arrayPrefijos['Participacion en Sociedades']=[];
                $arrayPrefijos['Participacion en Sociedades']['TituloRubro']=$rowTituloBienDeUso;
                $arrayPrefijos['Participacion en Sociedades']['prefijonocorriente']='120700';
                                                      
                $totalparticipacionensociedades = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Participacion en sociedades",$fechaInicioConsulta,$fechaFinConsulta);
           
        /*<tr class="trnoclickeable">
            <td colspan="4">Nota <?php //echo $numeroDeNota; ?>: Activos Intangibleso</h3>Comprosicion y Evolucion del Rubro</br>(Ver Anexo)</td>
            <?php //$numeroDeNota++; ?>
        </tr>*/
         
        $totalActivosIntangibles = [];
       
                $arrayPrefijos=[];
                
                $arrayPrefijos['Activos Intangibles']=[];
//                $arrayPrefijos['Activos Intangibles']['prefijocorriente']='1208';
                $arrayPrefijos['Activos Intangibles']['prefijonocorriente']='1208';
                              
                $totalActivosIntangibles = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Activos Intangibles",$fechaInicioConsulta,$fechaFinConsulta);
          
                $totalDeudasComerciales=[];
                        
                $arrayPrefijos=[];
                $arrayPrefijos['Deudas Comerciales']=[];
                $arrayPrefijos['Deudas Comerciales']['prefijocorriente']='210100';
                $arrayPrefijos['Deudas Comerciales']['prefijonocorriente']='220100';
                
                $arrayPrefijos['Acredores']=[];
                $arrayPrefijos['Acredores']['prefijocorriente']='210708';
                $arrayPrefijos['Acredores']['prefijonocorriente']='220708';
                                                      
                $arrayPrefijos['Honorarios Directores']=[];
                $arrayPrefijos['Honorarios Directores']['prefijocorriente']='210709';
                $arrayPrefijos['Honorarios Directores']['prefijonocorriente']='220709';
                                                      
                $arrayPrefijos['Honorarios S&iacute;ndicos']=[];
                $arrayPrefijos['Honorarios S&iacute;ndicos']['prefijocorriente']='210710';
                $arrayPrefijos['Honorarios S&iacute;ndicos']['prefijonocorriente']='220710';
                                                      
                $totalDeudasComerciales = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Deudas Comerciales",$fechaInicioConsulta,$fechaFinConsulta);
        
                $totalPrestamos=[];
         
                $arrayPrefijos=[];
                $arrayPrefijos['Deudas Bancarias y Financiaras Locales']=[];
                $arrayPrefijos['Deudas Bancarias y Financiaras Locales']['prefijocorriente']='21021';
                $arrayPrefijos['Deudas Bancarias y Financiaras Locales']['prefijonocorriente']='22021';
                
                $arrayPrefijos['Deudas Bancarias y Financiaras Exterior']=[];
                $arrayPrefijos['Deudas Bancarias y Financiaras Exterior']['prefijocorriente']='21022';
                $arrayPrefijos['Deudas Bancarias y Financiaras Exterior']['prefijonocorriente']='22022';
                
                $arrayPrefijos['Otras Entidades Crediticias']=[];
                $arrayPrefijos['Otras Entidades Crediticias']['prefijocorriente']='210230000';
                $arrayPrefijos['Otras Entidades Crediticias']['prefijonocorriente']='220230000';
                 
                
                // ME FALTA 21024 QUE SERIAN PERSTAMOS DESDE ORGANISMOS PUBLICOS
               /* $arrayPrefijos['Deudas Fiscales - AFIP']=[];
                $arrayPrefijos['Deudas Fiscales - AFIP']['prefijocorriente']='210401';
                $arrayPrefijos['Deudas Fiscales - AFIP']['prefijonocorriente']='220401';
                                                      
                $arrayPrefijos['Deudas Fiscales - DGR']=[];
                $arrayPrefijos['Deudas Fiscales - DGR']['prefijocorriente']='210402';
                $arrayPrefijos['Deudas Fiscales - DGR']['prefijonocorriente']='220402';
                
                $arrayPrefijos['Deudas Fiscales - DGRM']=[];
                $arrayPrefijos['Deudas Fiscales - DGRM']['prefijocorriente']='210403';
                $arrayPrefijos['Deudas Fiscales - DGRM']['prefijonocorriente']='220403';*/
                $totalPrestamos = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Prestamos",$fechaInicioConsulta,$fechaFinConsulta);

                $totalremuneracionycargassociales=[];
      
                $arrayPrefijos=[];
                $arrayPrefijos['Sueldos Netos a Pagar']=[];
                $arrayPrefijos['Sueldos Netos a Pagar']['prefijocorriente']='210301';
                $arrayPrefijos['Sueldos Netos a Pagar']['prefijonocorriente']='220301';
                
                $arrayPrefijos['Aportes a Depositar']=[];
                $arrayPrefijos['Aportes a Depositar']['prefijocorriente']='210302';
                $arrayPrefijos['Aportes a Depositar']['prefijonocorriente']='220302';
                                                      
                $arrayPrefijos['Contribuciones a Pagar']=[];
                $arrayPrefijos['Contribuciones a Pagar']['prefijocorriente']='210303';
                $arrayPrefijos['Contribuciones a Pagar']['prefijonocorriente']='220303';
                
                $totalremuneracionycargassociales = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Remuneracion y Cargas Sociales",$fechaInicioConsulta,$fechaFinConsulta);
             
                $totalcargasfiscales=[];
             
                $arrayPrefijos=[];
                $arrayPrefijos['Ganancias - Deudas']=[];
                $arrayPrefijos['Ganancias - Deudas']['TituloRubro']='Deudas Fiscales - AFIP';
                $arrayPrefijos['Ganancias - Deudas']['prefijocorriente']='2104011';
                $arrayPrefijos['Ganancias - Deudas']['prefijonocorriente']='2204011';
                
                $arrayPrefijos['Ganancia Min. Presunta - Deudas']=[];
                $arrayPrefijos['Ganancia Min. Presunta - Deudas']['prefijocorriente']='2104012';
                $arrayPrefijos['Ganancia Min. Presunta - Deudas']['prefijonocorriente']='2204012';
                                                      
                $arrayPrefijos['Bienes Personales - Deudas']=[];
                $arrayPrefijos['Bienes Personales - Deudas']['prefijocorriente']='2104013';
                $arrayPrefijos['Bienes Personales - Deudas']['prefijonocorriente']='2204013';

                $arrayPrefijos['Impuesto al Valor Agregado - Deudas']=[];
                $arrayPrefijos['Impuesto al Valor Agregado - Deudas']['prefijocorriente']='2104014';
                $arrayPrefijos['Impuesto al Valor Agregado - Deudas']['prefijonocorriente']='2204014';
                
                $arrayPrefijos['Otros Impuestos Nacionales - Deudas']=[];
                $arrayPrefijos['Otros Impuestos Nacionales - Deudas']['prefijocorriente']='2104018';
                $arrayPrefijos['Otros Impuestos Nacionales - Deudas']['prefijonocorriente']='2204018';
                
                $arrayPrefijos['Seguridad Social - Deudas']=[];
                $arrayPrefijos['Seguridad Social - Deudas']['prefijocorriente']='2104019';
                $arrayPrefijos['Seguridad Social - Deudas']['prefijonocorriente']='2204019';
                
                $arrayPrefijos['Ingresos Brutos - Deudas']=[];
                $arrayPrefijos['Ingresos Brutos - Deudas']['TituloRubro']='Deudas Fiscales - DGR';
                $arrayPrefijos['Ingresos Brutos - Deudas']['prefijocorriente']='2104021';
                $arrayPrefijos['Ingresos Brutos - Deudas']['prefijonocorriente']='2204021';
                
                $arrayPrefijos['Cooperadoras Asistenciales - Deudas']=[];
                $arrayPrefijos['Cooperadoras Asistenciales - Deudas']['prefijocorriente']='2104022';
                $arrayPrefijos['Cooperadoras Asistenciales - Deudas']['prefijonocorriente']='2204022';
                
                $arrayPrefijos['Inmobiliario Rural - Deudas']=[];
                $arrayPrefijos['Inmobiliario Rural - Deudas']['prefijocorriente']='2104023';
                $arrayPrefijos['Inmobiliario Rural - Deudas']['prefijonocorriente']='2204023';
                
                $arrayPrefijos['Actividades Varias - Deudas']=[];
                $arrayPrefijos['Actividades Varias - Deudas']['TituloRubro']='Deudas Fiscales - DGRM';
                $arrayPrefijos['Actividades Varias - Deudas']['prefijocorriente']='2104031';
                $arrayPrefijos['Actividades Varias - Deudas']['prefijonocorriente']='2204031';
                
                $arrayPrefijos['Tasa de Publicidad y Propaganda - Deudas']=[];
                $arrayPrefijos['Tasa de Publicidad y Propaganda - Deudas']['prefijocorriente']='2104032';
                $arrayPrefijos['Tasa de Publicidad y Propaganda - Deudas']['prefijonocorriente']='2204032';
                
                $arrayPrefijos['Inmobiliario Urbano - Deudas']=[];
                $arrayPrefijos['Inmobiliario Urbano - Deudass']['prefijocorriente']='2104033';
                $arrayPrefijos['Inmobiliario Urbano - Deudas']['prefijonocorriente']='2204033';
                
                $arrayPrefijos['Alumbrado y Limpieza - Deudas']=[];
                $arrayPrefijos['Alumbrado y Limpieza - Deudass']['prefijocorriente']='2104034';
                $arrayPrefijos['Alumbrado y Limpieza - Deudas']['prefijonocorriente']='2204034';
                
                $arrayPrefijos['Impuesto Automotor - Deudas']=[];
                $arrayPrefijos['Impuesto Automotor - Deudass']['prefijocorriente']='2104035';
                $arrayPrefijos['Impuesto Automotor - Deudas']['prefijonocorriente']='2204035';
                
                $totalcargasfiscales = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Cargas Fiscales",$fechaInicioConsulta,$fechaFinConsulta);
             
                $totalanticipodeclientes=[];
             
                $arrayPrefijos=[];
                $arrayPrefijos['Anticipo de Clientes']=[];
                $arrayPrefijos['Anticipo de Clientes']['prefijocorriente']='21051';
                $arrayPrefijos['Anticipo de Clientes']['prefijonocorriente']='22051';
                
                $arrayPrefijos['Anticipo de Obras']=[];
                $arrayPrefijos['Anticipo de Obras']['prefijocorriente']='21053';
                $arrayPrefijos['Anticipo de Obras']['prefijonocorriente']='22053';
                                                      
                $arrayPrefijos['Otros Anticipos']=[];
                $arrayPrefijos['Otros Anticipos']['prefijocorriente']='210599';
                $arrayPrefijos['Otros Anticipos']['prefijonocorriente']='220599';
                
                $totalanticipodeclientes = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Anticipo de Clientes",$fechaInicioConsulta,$fechaFinConsulta);
              
                $totaldividendosapagar=[];
               
                $arrayPrefijos=[];
                $arrayPrefijos['Dividendos a Pagar']=[];
                $arrayPrefijos['Dividendos a Pagar']['prefijocorriente']='2106';
                $arrayPrefijos['Dividendos a Pagar']['prefijonocorriente']='2206';
                
                $totaldividendosapagar = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Dividendos a Pagar",$fechaInicioConsulta,$fechaFinConsulta);
              
                $totalotrasdeudas=[];
               
                $arrayPrefijos=[];
                $arrayPrefijos['Planes de Pagos AFIP']=[];
                $arrayPrefijos['Planes de Pagos AFIP']['prefijocorriente']='210701';
                $arrayPrefijos['Planes de Pagos AFIP']['prefijonocorriente']='220701';
                
                $arrayPrefijos['Planes de Pagos DGR']=[];
                $arrayPrefijos['Planes de Pagos DGR']['prefijocorriente']='210702';
                $arrayPrefijos['Planes de Pagos DGR']['prefijonocorriente']='220702';
                
                $arrayPrefijos['Planes de Pagos DGRM']=[];
                $arrayPrefijos['Planes de Pagos DGRM']['prefijocorriente']='210703';
                $arrayPrefijos['Planes de Pagos DGRM']['prefijonocorriente']='220703';
                
                $arrayPrefijos['Cta Particular Socios']=[];
                $arrayPrefijos['Cta Particular Socios']['prefijocorriente']='210704';
                $arrayPrefijos['Cta Particular Socios']['prefijonocorriente']='220704';
                
                $arrayPrefijos['Otros Pasivos en el Pais']=[];
                $arrayPrefijos['Otros Pasivos en el Pais']['prefijocorriente']='210705';
                $arrayPrefijos['Otros Pasivos en el Pais']['prefijonocorriente']='220705';
                
                $arrayPrefijos['Sociedades Vinculadas - Pasivos']=[];
                $arrayPrefijos['Sociedades Vinculadas - Pasivos']['prefijocorriente']='210707';
                $arrayPrefijos['Sociedades Vinculadas - Pasivos']['prefijonocorriente']='220707';
                
                /*$arrayPrefijos['Acreedores']=[];
                $arrayPrefijos['Acreedores']['prefijocorriente']='210708';
                $arrayPrefijos['Acreedores']['prefijonocorriente']='220708';
                 Esto no va por que ya esta en Deudas Comerciales*/
                
                $totalotrasdeudas = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Otras Deudas",$fechaInicioConsulta,$fechaFinConsulta);
               
                $totalprevisiones=[];
          
                $arrayPrefijos=[];
                $arrayPrefijos['Deducidas en el Activo']=[];
                $arrayPrefijos['Deducidas en el Activo']['TituloRubro']='Previsiones';
                $arrayPrefijos['Deducidas en el Activo']['prefijocorriente']='210801';
                $arrayPrefijos['Deducidas en el Activo']['prefijonocorriente']='220801';
                
                $arrayPrefijos['Incluidas en el Pasivo']=[];
                $arrayPrefijos['Incluidas en el Pasivo']['prefijocorriente']='210802';
                $arrayPrefijos['Incluidas en el Pasivo']['prefijonocorriente']='220802';
                
                $totalprevisiones = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Previsiones",$fechaInicioConsulta,$fechaFinConsulta);
                ?>
        </tbody>
    </table>
</div><!--Estado de NOTAS de Situacion Patrimonialo-->
 
<div class="index estadocontable" id="divContenedorAnexoIBienesdeUso">
   
    <table id="tblanexoIBienesdeuso"  class="toExcelTable tbl_border tblEstadoContable tblBiendeUso" cellspacing="0" style="">
        <thead>
            <tr class="trnoclickeable trTitle">
                <td colspan="17" style="text-align: left;font-size: 14px;font-weight: bold;border-collapse: collapse;">
                    Notas y Anexos al Estado de Situacion Patrimonial</br>
                    Denominacion: <?php echo $cliente['Cliente']['nombre'];?>
                </td>
            </tr>
            <tr>
                <td colspan="17" style="text-align: center">
                    Anexo I de Bienes de Uso correspondiente al ejercicio finalizado el <?php echo date('d-m-Y', strtotime($fechaFinConsulta));; ?> comparativo con el ejercicio anterior
                </td>
            </tr>
            <tr>
                <th rowspan="2" style="text-align: center;border-right: 2px black solid">Rubros</th>
                <th colspan="4" style="text-align: center;border-right: 2px black solid">Valores Originales</th>
                <th colspan="4" style="text-align: center;border-right: 2px black solid">Depreciacion</th>
                <th colspan="2" style="text-align: center">Valor Neto al Cierre</th>
            </tr>
            <tr>
                <th style="text-align: center">Al Inicio</th>
                <th style="text-align: center">Altas</th>
                <th style="text-align: center;display: none">Transferencias</th>
                <th style="text-align: center">Bajas</th>
                <th style="text-align: center;display: none">Revaluo</th>
                <th style="text-align: center;display: none">Desvalorizacion</th>
                <th style="text-align: center;display: none">Recupero Desvalorizacion</th>
                <th style="text-align: center;border-right: 2px black solid">Al Cierre</th>
                <th style="text-align: center">Al Inicio</th>
                <th style="text-align: center">Bajas</th>
                <th style="text-align: center">Del Ejercicio</th>
                <th style="text-align: center;display: none"">Desvalorizacion</th>
                <th style="text-align: center;display: none">Recupero Desvalorizacion</th>
                <th style="text-align: center;border-right: 2px black solid">Al Cierre</th>
                <th style="text-align: center">Ejercicio Actual</th>
                <th style="text-align: center">Ejercicio Anterior</th>
            </tr>
        </thead>
        <tbody>
         <?php
        $arrayPrefijos=[];
        $arrayPrefijos['Inmuebles']=[];
        $arrayPrefijos['Inmuebles']['prefijocorriente']='1206010';
        
        $arrayPrefijos['Rodados']=[];
        $arrayPrefijos['Rodados']['prefijocorriente']='1206020';
        
        $arrayPrefijos['Instalaciones']=[];
        $arrayPrefijos['Instalaciones']['prefijocorriente']='1206030';
        
        $arrayPrefijos['Muebles y &Uacute;tiles']=[];
        $arrayPrefijos['Muebles y &Uacute;tiles']['prefijocorriente']='1206040';
        
        $arrayPrefijos['Maquinarias']=[];
        $arrayPrefijos['Maquinarias']['prefijocorriente']='1206050';
        
        $arrayPrefijos['Activos Biol&oacute;gicos']=[];
        $arrayPrefijos['Activos Biol&oacute;gicos']['prefijocorriente']='1206060';
                
        $totalBienesDeUso = mostrarBienDeUso($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$fechaInicioConsulta,$fechaFinConsulta);          
        $totalBienesDeUso['numeroDeNota']=$numeroNotaBienesdeUso;
        ?>
        </tbody>
    </table>
</div>
 
<div class="index estadocontable" id="divContenedorSituacionPatrimonial">
    <table id="tblEstadoSituacionPatrimonial" class="toExcelTable tbl_border tblEstadoContable" collspace="0">
        <tr class="trnoclickeable trTitle">
            <td colspan="17" style="text-align: left;font-size: 14px;font-weight: bold;border-collapse: collapse;">
                Estado de Situacion Patrimonial</br>
                Denominacion: <?php echo $cliente['Cliente']['nombre'];?>
            </td>
        </tr>
        <tr class="trnoclickeable trTitle">
            <td colspan="17" style="text-align: center">
                Estado de Situacion Patrimonial por el Ejercicio Anual Finalizado el <?php echo date('d-m-Y', strtotime($fechaFinConsulta));; ?> comparativo con el ejercicio anterior
            </td>
        </tr>       
        <tr>
          <!--Activo Corriente-->
        <tr>
            <th colspan="2">Activo</th>
            <th style="width: 70px">Actual</th>
            <th style="width: 70px">Anterior</th>
            <th colspan="2">Pasivo</th>
            <th style="width: 70px">Actual</th>
            <th style="width: 70px">Anterior</th>
        </tr>
        <tr>
            <th colspan="2">
                Activo Corriente
            </th>
            <th style="width: 70px"></th>
            <th style="width: 70px"></th>
            <th colspan="2">
                Pasivo Corriente
            </th>
            <th style="width: 70px"></th>
            <th style="width: 70px"></th>
        </tr>
        <?php
        $totalActivoCorriente=[];
        $totalActivoNOCorriente=[];
        $totalPasivoCorriente=[];
        $totalPasivoNoCorriente=[];
        if(!isset($totalActivoCorriente[$periodoActual])){
            $totalActivoCorriente[$periodoActual]=0;
            $totalActivoCorriente[$periodoPrevio]=0;
        }
         if(!isset($totalPasivoCorriente[$periodoActual])){
            $totalPasivoCorriente[$periodoActual]=0;
            $totalPasivoCorriente[$periodoPrevio]=0;
        }
        if(!isset($totalActivoNoCorriente[$periodoActual])){
            $totalActivoCorriente[$periodoActual]=0;
            $totalActivoCorriente[$periodoPrevio]=0;
        }
         if(!isset($totalPasivoNoCorriente[$periodoActual])){
            $totalPasivoCorriente[$periodoActual]=0;
            $totalPasivoCorriente[$periodoPrevio]=0;
        }
        
        $rowsCorriente=[];
        $rowsCorriente['Activo']=[];
        $rowsCorriente['Activo']['Corriente']=[];
        $rowsCorriente['Activo']['NoCorriente']=[];
        $rowsCorriente['Pasivo']=[];
        $rowsCorriente['Pasivo']['Corriente']=[];
        $rowsCorriente['Pasivo']['NoCorriente']=[];
        //Activo Corriente
        if(isset($totalCajayBancos['numeronota'])){
             $rowsCorriente['Activo']['Corriente'][]= showRowESP($totalCajayBancos,'Cajas y Bancos',$fechaFinConsulta,$totalActivoCorriente,'corriente');
        }
        if(isset($totalInversiones['numeronota'])){
             $rowsCorriente['Activo']['Corriente'][]= showRowESP($totalInversiones,'Inversiones',$fechaFinConsulta,$totalActivoCorriente,'corriente');
        }
        if(isset($totalCreditosxVentas['numeronota'])){
             $rowsCorriente['Activo']['Corriente'][]= showRowESP($totalCreditosxVentas,'Creditos por Ventas',$fechaFinConsulta,$totalActivoCorriente,'corriente');
        }
        if(isset($totalOtrosCreditos['numeronota'])){
            $rowsCorriente['Activo']['Corriente'][]= showRowESP($totalOtrosCreditos,'Otros Creditos',$fechaFinConsulta,$totalActivoCorriente,'corriente');
        }
        if(isset($totalBienesdeCambio['numeronota'])){
            $rowsCorriente['Activo']['Corriente'][]= showRowESP($totalBienesdeCambio,'Bienes de Cambio',$fechaFinConsulta,$totalActivoCorriente,'corriente');
        }
        if(isset($totalOtrosActivos['numeronota'])){
            $rowsCorriente['Activo']['Corriente'][]= showRowESP($totalOtrosActivos,'Otros Activos',$fechaFinConsulta,$totalActivoCorriente,'corriente');
        }
        //Pasivo Corriente
        if(isset($totalDeudasComerciales['numeronota'])){
            $rowsCorriente['Pasivo']['Corriente'][]=showRowESP($totalDeudasComerciales,'Deudas Comerciales',$fechaFinConsulta,$totalPasivoCorriente,'corriente');
        }
        if(isset($totalPrestamos['numeronota'])){
            $rowsCorriente['Pasivo']['Corriente'][]=showRowESP($totalPrestamos,'Prestamos',$fechaFinConsulta,$totalPasivoCorriente,'corriente');
        }
        if(isset($totalremuneracionycargassociales['numeronota'])){
            $rowsCorriente['Pasivo']['Corriente'][]=showRowESP($totalremuneracionycargassociales,'Remunerac. y Cargas Sociales',$fechaFinConsulta,$totalPasivoCorriente,'corriente');
        }
        if(isset($totalcargasfiscales['numeronota'])){
            $rowsCorriente['Pasivo']['Corriente'][]=showRowESP($totalcargasfiscales,'Cargas Fiscales',$fechaFinConsulta,$totalPasivoCorriente,'corriente');
        }
        if(isset($totalanticipodeclientes['numeronota'])){
            $rowsCorriente['Pasivo']['Corriente'][]=showRowESP($totalanticipodeclientes,'Anticipos de Clientes',$fechaFinConsulta,$totalPasivoCorriente,'corriente');
        }
        if(isset($totaldividendosapagar['numeronota'])){
            $rowsCorriente['Pasivo']['Corriente'][]=showRowESP($totaldividendosapagar,'Dividendos a pagar',$fechaFinConsulta,$totalPasivoCorriente,'corriente');
        }
        if(isset($totalotrasdeudas['numeronota'])){
            $rowsCorriente['Pasivo']['Corriente'][]=showRowESP($totalotrasdeudas,'Otras deudas',$fechaFinConsulta,$totalPasivoCorriente,'corriente');
        }

        //mostrar
        foreach ($rowsCorriente['Activo']['Corriente'] as $k => $value) {
            echo '<tr id="'.$k.'"> ';
            $myRowToSow = "<tr>".$value;
            if(isset($rowsCorriente['Pasivo']['Corriente'][$k])){
                $myRowToSow .=  $rowsCorriente['Pasivo']['Corriente'][$k]."</tr>";
            }else{
                $myRowToSow .=  '<td style="border-right: 1px solid #fff;"></td><td style="border-left: 1px solid #fff;"></td><td></td><td></td>';
            }
            echo $myRowToSow;
            echo '</tr>';
        }
        if(count($rowsCorriente['Pasivo']['Corriente'])>count($rowsCorriente['Activo']['Corriente'])){
            foreach ($rowsCorriente['Pasivo']['Corriente'] as $j => $value) {    
                if($j>=count($rowsCorriente['Activo']['Corriente'])){
                    echo '<tr id="'.$j.'"> ';
                    if(isset($rowsCorriente['Pasivo']['Corriente'][$j])){
                        $myRowToSow='<td style="border-right: 1px solid #fff;"></td><td style="border-left: 1px solid #fff;"></td><td></td><td></td>';
                        $myRowToSow .= $rowsCorriente['Pasivo']['Corriente'][$j];
                    }
                    echo $myRowToSow;
                    echo '</tr>';
                }      
               
            }
        }
        
        ?>
        <tr>
            <td colspan="2">Subtotal Activo Corriente</td>
            <td class="numericTD"><?php echo number_format($totalActivoCorriente[$periodoActual],2,",",".")?></td>
            <td class="numericTD"><?php echo number_format($totalActivoCorriente[$periodoPrevio],2,",",".")?></td>
            <td colspan="2">Total deudas</td>
            <td class="numericTD"><?php echo number_format($totalPasivoCorriente[$periodoActual],2,",",".")?></td>
            <td class="numericTD"><?php echo number_format($totalPasivoCorriente[$periodoPrevio],2,",",".")?></td>
        </tr>
        <?php
        if(isset($totalLlavenegocio['numeronota'])){
            $rowsCorriente['Activo']['Corriente']=showRowESP($totalLlavenegocio,'Llave de Negocio',$fechaFinConsulta,$totalActivoCorriente,'corriente');
            echo "<tr>".$rowsCorriente['Activo']['Corriente'][$k+1];
        }else{
            if(isset($totalprevisiones['numeronota'])){
                echo '<tr><td style="border-right: 1px solid #fff;"></td><td style="border-left: 1px solid #fff;"></td><td></td><td></td>';
            }
        }
        if(isset($totalprevisiones['numeronota'])){
            $rowsCorriente['Pasivo']['Corriente']=showRowESP($totalprevisiones,'Previsiones',$fechaFinConsulta,$totalPasivoCorriente,'corriente');
            echo $rowsCorriente['Pasivo']['Corriente'][$j+1]."</tr>";
        }else{
            if(isset($totalLlavenegocio['numeronota'])){
                echo '<td style="border-right: 1px solid #fff;"></td><td style="border-left: 1px solid #fff;"></td><td></td><td></td></tr>';
            }
        }
        ?>
        </tr>
        <tr>
            <th colspan="2">Total del Activo Corriente</th>
            <th class="numericTD" style="width: 70px;"><?php echo number_format($totalActivoCorriente[$periodoActual],2,",",".")?></th>
            <th class="numericTD" style="width: 70px;"><?php echo number_format($totalActivoCorriente[$periodoPrevio],2,",",".")?></th>
            <th colspan="2">Total del Pasivo Corriente</th>
            <th class="numericTD" style="width: 70px;"><?php echo number_format($totalPasivoCorriente[$periodoActual],2,",",".")?></th>
            <th class="numericTD" style="width: 70px;"><?php echo number_format($totalPasivoCorriente[$periodoPrevio],2,",",".")?></th>
        </tr>
        <tr>
        <tr>
            <th colspan="2">
                Activo no Corriente
            </th>
            <th class="numericTD" style="width: 70px"></th>
            <th class="numericTD" style="width: 70px"></th>
             <th colspan="2">
                Pasivo no Corriente
            </th>
            <th class="numericTD" style="width: 70px"></th>
            <th class="numericTD" style="width: 70px"></th>
        </tr>
        <?php
        if(isset($totalInversiones['numeronota'])){
             $rowsCorriente['Activo']['NoCorriente'][]=showRowESP($totalInversiones,'Inversiones',$fechaFinConsulta,$totalActivoNOCorriente,'nocorriente');
        }
        if(isset($totalCreditosxVentas['numeronota'])){
             $rowsCorriente['Activo']['NoCorriente'][]=showRowESP($totalCreditosxVentas,'Creditos por Ventas',$fechaFinConsulta,$totalActivoNOCorriente,'nocorriente');
        }
        if(isset($totalOtrosCreditos['numeronota'])){
            $rowsCorriente['Activo']['NoCorriente'][]=showRowESP($totalOtrosCreditos,'Otros Creditos',$fechaFinConsulta,$totalActivoNOCorriente,'nocorriente');
        }
        if(isset($totalBienesdeCambio['numeronota'])){
           $rowsCorriente['Activo']['NoCorriente'][]= showRowESP($totalBienesdeCambio,'Bienes de Cambio',$fechaFinConsulta,$totalActivoNOCorriente,'nocorriente');
        }
        //aca me falta mostrary sumar los bienes de uso
        $Actual = $totalBienesDeUso['ejercicioActual'];
        $Previo = $totalBienesDeUso['ejercicioAnterior'];;
        
        if(($Actual+$Previo)!=0){
        $myRow='<td style="border-right: 1px solid #fff;">Bienes de Uso</td>';
        $myRow.='<td align="right" style="width:60px;border-left: 1px solid #fff;">';
        $myRow.='<label>Nota: '.$totalBienesDeUso['numeroDeNota'].'</label>';
        $myRow.='</td>';
        $myRow.='<td class="numericTD">'. number_format($Actual, 2, ",", ".").'</td>';
        $myRow.='<td class="numericTD">'.number_format($Previo, 2, ",", ".").'</td>';
     
        
        $totalActivoNOCorriente[$periodoActual]+=$Actual;
        $totalActivoNOCorriente[$periodoPrevio]+=$Previo;
        $rowsCorriente['Activo']['NoCorriente'][]= $myRow;
        }
        //fin bienes de uso
        if(isset($totalparticipacionensociedades['numeronota'])){
            $rowsCorriente['Activo']['NoCorriente'][]=showRowESP($totalparticipacionensociedades,'Participacion en Sociedades',$fechaFinConsulta,$totalActivoNOCorriente,'nocorriente');
        }
        /*--if(isset($totalBienesdeCambio['numeronota'])){
            $rowsCorriente['Activo']['NoCorriente']=showRowESP($totalBienesdeCambio,'Inversiones',$fechaInicioConsulta,$totalActivoCorriente,'nocorriente');
        }*/
        if(isset($totalActivosIntangibles['numeronota'])){
            $rowsCorriente['Activo']['NoCorriente'][]=showRowESP($totalActivosIntangibles,'Activos Intangibles',$fechaFinConsulta,$totalActivoNOCorriente,'nocorriente');
        }

        if(isset($totalOtrosActivos['numeronota'])){
            $rowsCorriente['Activo']['NoCorriente'][]=showRowESP($totalOtrosActivos,'Otros Activos',$fechaFinConsulta,$totalActivoNOCorriente,'nocorriente');
        }
         $totalPasivoNOCorriente=[];
        if(isset($totalDeudasComerciales['numeronota'])){
            $rowsCorriente['Pasivo']['NoCorriente'][]=showRowESP($totalDeudasComerciales,'Deudas Comerciales',$fechaFinConsulta,$totalPasivoNOCorriente,'nocorriente');
        }
        if(isset($totalPrestamos['numeronota'])){
            $rowsCorriente['Pasivo']['NoCorriente'][]=showRowESP($totalPrestamos,'Prestamos',$fechaFinConsulta,$totalPasivoNOCorriente,'nocorriente');
        }
        if(isset($totalremuneracionycargassociales['numeronota'])){
            $rowsCorriente['Pasivo']['NoCorriente'][]=showRowESP($totalremuneracionycargassociales,'Remunerac. y Cargas Sociales',$fechaFinConsulta,$totalPasivoNOCorriente,'nocorriente');
        }
        if(isset($totalcargasfiscales['numeronota'])){
            $rowsCorriente['Pasivo']['NoCorriente'][]=showRowESP($totalcargasfiscales,'Cargas Fiscales',$fechaFinConsulta,$totalPasivoNOCorriente,'nocorriente');
        }
        if(isset($totalanticipodeclientes['numeronota'])){
            $rowsCorriente['Pasivo']['NoCorriente'][]=showRowESP($totalanticipodeclientes,'Anticipos de Clientes',$fechaFinConsulta,$totalPasivoNOCorriente,'nocorriente');
        }
        if(isset($totaldividendosapagar['numeronota'])){
            $rowsCorriente['Pasivo']['NoCorriente'][]=showRowESP($totaldividendosapagar,'Dividendos a pagar',$fechaFinConsulta,$totalPasivoNOCorriente,'nocorriente');
        }
        if(isset($totalotrasdeudas['numeronota'])){
            $rowsCorriente['Pasivo']['NoCorriente'][]=showRowESP($totalotrasdeudas,'Otras deudas',$fechaFinConsulta,$totalPasivoNOCorriente,'nocorriente');
        }
         //mostrar
        foreach ($rowsCorriente['Activo']['NoCorriente'] as $k => $value) {
            echo '<tr id="'.$k.'"> ';
            $myRowToSow = "<tr>".$value;
            if(isset($rowsCorriente['Pasivo']['NoCorriente'][$k])){
                $myRowToSow .=  $rowsCorriente['Pasivo']['NoCorriente'][$k]."</tr>";
            }else{
                $myRowToSow .=  '<td style="border-right: 1px solid #fff;"></td>'
                            . '<td style="border-left: 1px solid #fff;"></td>'
                            . "<td></td>"
                            . "<td></td>";
            }
            echo $myRowToSow;
            echo '</tr>';
        }
        if(count($rowsCorriente['Pasivo']['NoCorriente'])>count($rowsCorriente['Activo']['NoCorriente'])){
            foreach ($rowsCorriente['Pasivo']['NoCorriente'] as $j => $value) {    
                if($j>=count($rowsCorriente['Activo']['NoCorriente'])){
                    echo '<tr id="'.$j.'"> ';
                    if(isset($rowsCorriente['Pasivo']['NoCorriente'][$j])){
                        $myRowToSow='<td style="border-right: 1px solid #fff;"></td><td style="border-left: 1px solid #fff;"></td><td></td><td></td>';
                        $myRowToSow .= $rowsCorriente['Pasivo']['NoCorriente'][$j];
                    }
                    echo $myRowToSow;
                    echo '</tr>';
                }      
               
            }
        }
        if(!isset($totalActivoNOCorriente[$periodoActual])){
            $totalActivoNOCorriente[$periodoActual]=0;
            $totalActivoNOCorriente[$periodoPrevio]=0;
        }
         if(!isset($totalPasivoNOCorriente[$periodoActual])){
            $totalPasivoNOCorriente[$periodoActual]=0;
            $totalPasivoNOCorriente[$periodoPrevio]=0;
        }
        ?>
        <tr>
            <td colspan="2">Subtotal Activo no Corriente</td>
            <td class="numericTD"><?php echo number_format($totalActivoNOCorriente[$periodoActual],2,",",".")?></td>
            <td class="numericTD"><?php echo number_format($totalActivoNOCorriente[$periodoPrevio],2,",",".")?></td>
            <td colspan="2">Total deudas</td>
            <td class="numericTD"><?php echo number_format($totalPasivoNOCorriente[$periodoActual],2,",",".")?></td>
            <td class="numericTD"><?php echo number_format($totalPasivoNOCorriente[$periodoPrevio],2,",",".")?></td>
        </tr>
         <?php
        if(isset($totalLlavenegocio['numeronota'])){
            $rowsCorriente['Activo']['NoCorriente'][]=showRowESP($totalLlavenegocio,'Llave de Negocio',$fechaFinConsulta,$totalActivoNOCorriente,'nocorriente');
            echo "<tr>".$rowsCorriente['Activo']['NoCorriente'][$k+1];
        }else{
            if(isset($totalprevisiones['numeronota'])){
                echo "<tr><td></td><td></td><td></td><td></td>";
            }
        }
        if(isset($totalprevisiones['numeronota'])){
            $rowsCorriente['Pasivo']['NoCorriente'][]=showRowESP($totalprevisiones,'Previsiones',$fechaFinConsulta,$totalPasivoNOCorriente,'nocorriente');
            echo $rowsCorriente['Pasivo']['NoCorriente'][$j+1]."</tr>";
        }else{
             if(isset($totalLlavenegocio['numeronota'])){
                echo "<td></td><td></td><td></td><td></td></tr>";
             }
        }
        ?>                    
        <tr>
            <td rowspan="2" colspan="2"></td>
            <td rowspan="2" colspan="1"></td>
            <td rowspan="2" colspan="1"></td>
            <td colspan="2">Total del Pasivo no Corriente</td>
            <td class="numericTD"><?php echo number_format($totalPasivoNOCorriente[$periodoActual],2,",",".")?></td>
            <td class="numericTD"><?php echo number_format($totalPasivoNOCorriente[$periodoPrevio],2,",",".")?></td>
        </tr>
        <tr>
            <td colspan="2">Total del Pasivo</td>
            <td class="numericTD"><?php 
            $pasivoActual = $totalPasivoCorriente[$periodoActual]+$totalPasivoNOCorriente[$periodoActual];
            $pasivoPrevio = $totalPasivoCorriente[$periodoPrevio]+$totalPasivoNOCorriente[$periodoPrevio];
                        echo number_format($pasivoActual,2,",",".")?>
            </td>
            <td class="numericTD"><?php   echo number_format($pasivoPrevio,2,",",".")?></td>
        </tr>
        <tr>
            <td colspan="2">Total de Activo no Corriente</td>
            <td class="numericTD" style="width: 70px;"><?php echo number_format($totalActivoNOCorriente[$periodoActual],2,",",".")?></td>
            <td class="numericTD" style="width: 70px;"><?php echo number_format($totalPasivoNOCorriente[$periodoPrevio],2,",",".")?></td>
            <td colspan="2">Patrimonio Neto (Segun E. correspondiente)</td>
            <td class="numericTD" style="width: 70px;"><?php echo number_format($saldoalcierre['totalactual'],2,",",".");?></td>
            <td class="numericTD" style="width: 70px;"><?php echo number_format($saldoalcierre['totalanterior'],2,",",".")?></td>
        </tr>
        <tr class="trTitle">
            <th colspan="2">Total de Activo</th>
            <th class="numericTD" style="width: 70px;"><?php echo number_format($totalActivoCorriente[$periodoActual]+$totalActivoNOCorriente[$periodoActual],2,",",".");?></th>
            <th class="numericTD" style="width: 70px;"><?php echo number_format($totalActivoCorriente[$periodoPrevio]+$totalActivoNOCorriente[$periodoPrevio],2,",",".");?></th>
            <th colspan="2">Total Pasivo y Patrimonio Neto</th>
            <th class="numericTD" style="width: 70px;"><?php echo number_format($saldoalcierre['totalactual']+$pasivoActual,2,",",".");?></th>
            <th class="numericTD" style="width: 70px;"><?php echo number_format($saldoalcierre['totalanterior']+$pasivoPrevio,2,",",".")?></th>
        </tr>
    </table>
</div> <!--Estado de Situacion Patrimonial-->
 
<div class="index estadocontable" id="divContenedorNotaFlujoEfectivo">
    <table class="toExcelTable tblEstadoContable" collspace="0">
        <thead>
            <tr class="trnoclickeable trTitle">
                <td class="tdnoborder" colspan="17" style="text-align: left;font-size: 14px;font-weight: bold;border-collapse: collapse;">
                    Estado de Flujo de Efectivo  M&eacute;todo Directo</br>
                    Denominacion: <?php echo $cliente['Cliente']['nombre'];?>
                </td>
            </tr>
            <tr class="trTitle">
                <th class="tdnoborder" colspan="4" style="text-align: center">Notas al Estado de Flujo de Efectivo</th>
            </tr>
            <tr class="trTitle">
                <th colspan="17" style="text-align: left" class="tdnoborder">
                    Notas a los Estados Contables al <?php echo date('d-m-Y', strtotime($fechaFinConsulta));; ?> comparativo con el ejercicio anterior
                </th>
            </tr>       
            <tr>
                <td colspan="17">
                    <hr style=" display: none;margin-top: 0.5em;margin-bottom: 0.5em;margin-left: auto;margin-right: auto;border-style: inset;border-width: 1px;">
                </td>
            </tr>
        </thead>
        <tbody>
            
            <?php
            $numeroNota = 1;
            $mostrarNota = false;
            //nota: Efectivo al Inicio
            $notaEfectivoAlInicio = [];
            $notaEfectivoAlInicio['nombreNota']='Efectivo al Inicio';
                                        
            $arrayPrefijos=[];
            $arrayPrefijos[]='110101';
            $arrayPrefijos[]='110102';
            $arrayPrefijos[]='110103';
            $arrayPrefijos[]='110104';
            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',1);
             
            $totalEfectivoAlInicio=[]; 
            $totalEfectivoAlInicio[$periodoActual]=$subtotalActual;
            $totalEfectivoAlInicio[$periodoPrevio]=$subtotalPrevio;
            $notaEfectivoAlInicio['conceptos']['Efectivo al Inicio']['valores']=$totalEfectivoAlInicio;
            $notaEfectivoAlInicio['conceptos']['Efectivo al Inicio']['composicion']='periodos';
            $notaEfectivoAlInicio['conceptos']['Efectivo al Inicio']['resta']=false;
            $notaEfectivoAlInicio['conceptos']['Efectivo al Inicio']['title']='Cuentas 110101/110102/110103/110104 en el asiento de apertura';
                                    
            mostrarNotaEFE($notaEfectivoAlInicio,$periodoPrevio, $periodoActual,$numeroNota);
            
             //nota: Conciliacion entre efectivo y sus equivalentes y las apartidas del estado de sitaucion patrimonial
            $notaConciliacionEfectivo = [];
            $notaConciliacionEfectivo['nombreNota']='Conciliacion entre efectivo y sus equivalentes y las partidas del estado de situacion patrimonial';
                                                                  
            $notaConciliacionEfectivo['conceptos']['Caja y Banco']['valores']=$totalCajayBancos;
            $notaConciliacionEfectivo['conceptos']['Caja y Banco']['composicion']='arrayprefijo';
            $notaConciliacionEfectivo['conceptos']['Caja y Banco']['resta']=false;
            $notaConciliacionEfectivo['conceptos']['Caja y Banco']['title']='Cajas y Bancos de Estado de Evolucion de Situacion Patrimonial';
            
            $totalinversiones=[]; 
            $totalinversiones[$periodoActual]=0;
            $totalinversiones[$periodoPrevio]=0;
            $notaConciliacionEfectivo['conceptos']['Inversiones Temporarias']['valores']=$totalinversiones;
            $notaConciliacionEfectivo['conceptos']['Inversiones Temporarias']['composicion']='periodos';
            $notaConciliacionEfectivo['conceptos']['Inversiones Temporarias']['resta']=false;
            $notaConciliacionEfectivo['conceptos']['Inversiones Temporarias']['title']='';
            
            $totalSubtotalesEstadoSituacionPatrimonial=[]; 
            $totalSubtotalesEstadoSituacionPatrimonial[$periodoActual]=0;
            $totalSubtotalesEstadoSituacionPatrimonial[$periodoPrevio]=0;
            $notaConciliacionEfectivo['conceptos']['Subtotales Estado de Situaci&oacute;n Patrimonial']['valores']=$totalSubtotalesEstadoSituacionPatrimonial;
            $notaConciliacionEfectivo['conceptos']['Subtotales Estado de Situaci&oacute;n Patrimonial']['composicion']='periodos';
            $notaConciliacionEfectivo['conceptos']['Subtotales Estado de Situaci&oacute;n Patrimonial']['resta']=false;
            $notaConciliacionEfectivo['conceptos']['Subtotales Estado de Situaci&oacute;n Patrimonial']['title']='';

            
            $totalInversionesquenocalificancomoefectivo=[]; 
            $totalInversionesquenocalificancomoefectivo[$periodoActual]=0;
            $totalInversionesquenocalificancomoefectivo[$periodoPrevio]=0;
            $notaConciliacionEfectivo['conceptos']['Menos: Inversiones que no califican como Efectivo y sus equivalentes']['valores']=$totalInversionesquenocalificancomoefectivo;
            $notaConciliacionEfectivo['conceptos']['Menos: Inversiones que no califican como Efectivo y sus equivalentes']['composicion']='periodos';
            $notaConciliacionEfectivo['conceptos']['Menos: Inversiones que no califican como Efectivo y sus equivalentes']['resta']=false;
            $notaConciliacionEfectivo['conceptos']['Menos: Inversiones que no califican como Efectivo y sus equivalentes']['title']='';
                                    
            mostrarNotaEFE($notaConciliacionEfectivo,$periodoPrevio, $periodoActual,$numeroNota);
            
            //Nota: Cobro por venta de bienes y servicios
            
            $notaCobroVentas = [];
            $notaCobroVentas['nombreNota']='Cobro por ventas de bienes y servicios';
            $notaCobroVentas['conceptos']['Ventas de bienes y servicios']['valores']=$totalVentasBienes;
            $notaCobroVentas['conceptos']['Ventas de bienes y servicios']['composicion']='periodos';
            $notaCobroVentas['conceptos']['Ventas de bienes y servicios']['resta']=false;
            $notaCobroVentas['conceptos']['Ventas de bienes y servicios']['title']='Venta de Bienes y Servicios de Estado de Evolucion de Patrimonio Neto';
                        
            $arrayPrefijos=[];
            $arrayPrefijos[]='110301001';
            /*$arrayPrefijos[]='110102';
            $arrayPrefijos[]='110103';
            $arrayPrefijos[]='110104';*/
            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',-1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',-1);
             
            $totalCajayBancosInicio=[]; 
            $totalCajayBancosInicio[$periodoActual]=$subtotalActual;
            $totalCajayBancosInicio[$periodoPrevio]=$subtotalPrevio;
                    
            $notaCobroVentas['conceptos']['Cr&eacute;ditos por Ventas Corrientes al Inicio']['valores']=$totalCajayBancosInicio;
            $notaCobroVentas['conceptos']['Cr&eacute;ditos por Ventas Corrientes al Inicio']['composicion']='periodos';
            $notaCobroVentas['conceptos']['Cr&eacute;ditos por Ventas Corrientes al Inicio']['title']='Cuenta 110301001 en el asiento de apertura';
           
            $notaCobroVentas['conceptos']['Cr&eacute;ditos por Ventas Corrientes al Cierre']['valores']=$totalCreditosxVentas;
            $notaCobroVentas['conceptos']['Cr&eacute;ditos por Ventas Corrientes al Cierre']['composicion']='corriente';
            $notaCobroVentas['conceptos']['Cr&eacute;ditos por Ventas Corrientes al Cierre']['title']='Creditos por Ventas del ESP';
            //$notaCobroVentas['conceptos']['Cr&eacute;ditos por Ventas Corrientes al Cierre']['resta']=false;
            
            $arrayPrefijos=[];
            
            $subtotalActual =  0;//sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura');
            $subtotalPrevio =  0;//sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura');
             
            $totalCreditosxVentasInicio=[]; 
            $totalCreditosxVentasInicio[$periodoActual]=$subtotalActual;
            $totalCreditosxVentasInicio[$periodoPrevio]=$subtotalPrevio;
            
            $notaCobroVentas['conceptos']['Cr&eacute;ditos por Ventas No Corrientes al Inicio']['valores']=$totalCreditosxVentasInicio;
            $notaCobroVentas['conceptos']['Cr&eacute;ditos por Ventas No Corrientes al Inicio']['composicion']='periodos';
            $notaCobroVentas['conceptos']['Cr&eacute;ditos por Ventas No Corrientes al Inicio']['title']='';
            
            //restar aporte de 
            
            $arrayPrefijos=[];
            $arrayPrefijos[]='110402001';
            
            $arrayPrefijos[]='220100';
            $arrayPrefijos[]='220708';
            $arrayPrefijos[]='220709';
            $arrayPrefijos[]='220710';

            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',-1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',-1);        
           
                      
            $totalAportesDeCapital1=[]; 
            $totalAportesDeCapital1[$periodoActual]=-$subtotalActual;
            $totalAportesDeCapital1[$periodoPrevio]=-$subtotalPrevio;     
           
            $subtotalActualCierre1 =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'todos',-1);
            $subtotalPrevioCierre1 =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'todos',-1);      
            
            $totalAportesDeCapitalCierre1=[]; 
            $totalAportesDeCapitalCierre1[$periodoActual]=$subtotalActualCierre1;
            $totalAportesDeCapitalCierre1[$periodoPrevio]=$subtotalPrevioCierre1;                 
            
            $nombreCuenta110402001 = isset($arrayCuentasxPeriodos['110402001']['nombrecuenta'])?$arrayCuentasxPeriodos['110402001']['nombrecuenta']:"sin nombre";
            $notaCobroVentas['conceptos'][$nombreCuenta110402001.' al inicio']['valores']=$totalAportesDeCapital1;
            $notaCobroVentas['conceptos'][$nombreCuenta110402001.' al inicio']['composicion']='periodos';            
            $notaCobroVentas['conceptos'][$nombreCuenta110402001.' al inicio']['title']='Cuenta 110402001/220100/220708/220709/220710 en el asiento de apertura';
            
            $arrayPrefijos=[];
            $arrayPrefijos[]='110402002';

            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',-1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',-1);        
                      
            $totalAportesDeCapital2=[]; 
            $totalAportesDeCapital2[$periodoActual]=-$subtotalActual;
            $totalAportesDeCapital2[$periodoPrevio]=-$subtotalPrevio;     
            
            $subtotalActualCierre2 =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'todos',-1);
            $subtotalPrevioCierre2 =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'todos',-1);      
            
            $totalAportesDeCapitalCierre2=[]; 
            $totalAportesDeCapitalCierre2[$periodoActual]=$subtotalActualCierre2;
            $totalAportesDeCapitalCierre2[$periodoPrevio]=$subtotalPrevioCierre2;   
            
            $nombreCuenta110402002 = isset($arrayCuentasxPeriodos['110402002']['nombrecuenta'])?$arrayCuentasxPeriodos['110402002']['nombrecuenta']:"sin nombre";
            $notaCobroVentas['conceptos'][$nombreCuenta110402002.' al inicio']['valores']=$totalAportesDeCapital2;
            $notaCobroVentas['conceptos'][$nombreCuenta110402002.' al inicio']['composicion']='periodos';            
            $notaCobroVentas['conceptos'][$nombreCuenta110402002.' al inicio']['title']='Cuenta 110402002 en el asiento de apertura';
            
            $arrayPrefijos=[];
            $arrayPrefijos[]='110402003';

            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',-1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',-1);        
                      
            $totalAportesDeCapital3=[]; 
            $totalAportesDeCapital3[$periodoActual]=-$subtotalActual;
            $totalAportesDeCapital3[$periodoPrevio]=-$subtotalPrevio;     
            
            $subtotalActualCierre3 =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'todos',-1);
            $subtotalPrevioCierre3 =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'todos',-1);      
            
            $totalAportesDeCapitalCierre3=[]; 
            $totalAportesDeCapitalCierre3[$periodoActual]=$subtotalActualCierre3;
            $totalAportesDeCapitalCierre3[$periodoPrevio]=$subtotalPrevioCierre3;   
            
            $nombreCuenta110402003 = isset($arrayCuentasxPeriodos['110402003']['nombrecuenta'])?$arrayCuentasxPeriodos['110402003']['nombrecuenta']:"sin nombre";
            $notaCobroVentas['conceptos'][$nombreCuenta110402003.' al inicio']['valores']=$totalAportesDeCapital3;
            $notaCobroVentas['conceptos'][$nombreCuenta110402003.' al inicio']['composicion']='periodos';            
            $notaCobroVentas['conceptos'][$nombreCuenta110402003.' al inicio']['title']='Cuenta 110402003 en el asiento de apertura';
            
            $arrayPrefijos=[];
            $arrayPrefijos[]='110402004';

            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',-1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',-1);        
                      
            $totalAportesDeCapital4=[]; 
            $totalAportesDeCapital4[$periodoActual]=-$subtotalActual;
            $totalAportesDeCapital4[$periodoPrevio]=-$subtotalPrevio;     
            
            $subtotalActualCierre4 =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'todos',-1);
            $subtotalPrevioCierre4 =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'todos',-1);      
            
            $totalAportesDeCapitalCierre4=[]; 
            $totalAportesDeCapitalCierre4[$periodoActual]=$subtotalActualCierre4;
            $totalAportesDeCapitalCierre4[$periodoPrevio]=$subtotalPrevioCierre4;   
            
            $nombreCuenta110402004 = isset($arrayCuentasxPeriodos['110402004']['nombrecuenta'])?$arrayCuentasxPeriodos['110402004']['nombrecuenta']:"sin nombre"; 
            //Aca falta completar hasta la cuenta 10!!
            
            $notaCobroVentas['conceptos'][$nombreCuenta110402004.' al inicio']['valores']=$totalAportesDeCapital4;
            $notaCobroVentas['conceptos'][$nombreCuenta110402004.' al inicio']['composicion']='periodos';            
            $notaCobroVentas['conceptos'][$nombreCuenta110402004.' al inicio']['title']='Cuenta 110402004 en el asiento de apertura';
                     
           
            $notaCobroVentas['conceptos'][$nombreCuenta110402001.' al cierre']['valores']=$totalAportesDeCapitalCierre1;
            $notaCobroVentas['conceptos'][$nombreCuenta110402001.' al cierre']['composicion']='periodos';            
            $notaCobroVentas['conceptos'][$nombreCuenta110402001.' al cierre']['title']='Cuenta 110402001/220100/220708/220709/220710 en el Cierre';
            
            $notaCobroVentas['conceptos'][$nombreCuenta110402002.' al cierre']['valores']=$totalAportesDeCapitalCierre2;
            $notaCobroVentas['conceptos'][$nombreCuenta110402002.' al cierre']['composicion']='periodos';            
            $notaCobroVentas['conceptos'][$nombreCuenta110402002.' al cierre']['title']='Cuenta 110402002 en el Cierre';
            
            $notaCobroVentas['conceptos'][$nombreCuenta110402003.' al cierre']['valores']=$totalAportesDeCapitalCierre3;
            $notaCobroVentas['conceptos'][$nombreCuenta110402003.' al cierre']['composicion']='periodos';            
            $notaCobroVentas['conceptos'][$nombreCuenta110402003.' al cierre']['title']='Cuenta 110402003 en el Cierre';
            
            $notaCobroVentas['conceptos'][$nombreCuenta110402004.' al cierre']['valores']=$totalAportesDeCapitalCierre4;
            $notaCobroVentas['conceptos'][$nombreCuenta110402004.' al cierre']['composicion']='periodos';            
            $notaCobroVentas['conceptos'][$nombreCuenta110402004.' al cierre']['title']='Cuenta 110402004 en el Cierre';
            
            
            
            $arrayPrefijos=['110401','110402','1104031','1104032','1104033','1104034','1104038','1104039','1104041','1104044','1104045','1104051','110406','110407','110408','110409'];
            $totalOtrosCreditosInicio=[]; 
            acumularPrefijos($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$periodoPrevio,$keysCuentas,'apertura',$totalOtrosCreditosInicio);
            
            $notaCobroVentas['conceptos']['Otros Cr&eacute;ditos al Inicio']['valores']=$totalOtrosCreditosInicio;
            $notaCobroVentas['conceptos']['Otros Cr&eacute;ditos al Inicio']['composicion']='periodos';
            $notaCobroVentas['conceptos']['Otros Cr&eacute;ditos al Inicio']['title']='Cuentas 110401/110402/1104031/1104032/1104033/1104034/1104038/1104039/1104041/1104044/1104045/1104051/110406/110407/110408/110409 en el asiento de apertura';
            $notaCobroVentas['conceptos']['Otros Cr&eacute;ditos al Inicio']['resta']=false;
            
            $totalOtrosCreditosCierre=[]; 
            $totalOtrosCreditosCierre[$periodoActual]=$totalOtrosCreditos['corriente'][$periodoActual];
            $totalOtrosCreditosCierre[$periodoPrevio]=$totalOtrosCreditos['corriente'][$periodoPrevio];
                    
            $notaCobroVentas['conceptos']['Otros Cr&eacute;ditos al Cierre']['valores']=$totalOtrosCreditosCierre;
            $notaCobroVentas['conceptos']['Otros Cr&eacute;ditos al Cierre']['composicion']='periodos';
            $notaCobroVentas['conceptos']['Otros Cr&eacute;ditos al Cierre']['title']='Otros Creditos del Estado de Situacion Patrimonial';
            
            
            mostrarNotaEFE($notaCobroVentas,$periodoPrevio, $periodoActual,$numeroNota);
            
            $notaPagoaprovedores = [];
            $notaPagoaprovedores['nombreNota']='Pago a provededoresdebienes y servicios';
            $notaPagoaprovedores['conceptos']['Costo de vta, producc. y adquis de bs de uso, intang. y otros activos']['valores']= $totalPeriodoCostoBienesServiciosProduccion;
            $notaPagoaprovedores['conceptos']['Costo de vta, producc. y adquis de bs de uso, intang. y otros activos']['composicion']='periodos';
            //$notaPagoaprovedores['conceptos']['Costo de vta, producc. y adquis de bs de uso, intang. y otros activos']['resta']=false;
            $notaPagoaprovedores['conceptos']['Costo de vta, producc. y adquis de bs de uso, intang. y otros activos']['title']='Costo de los Bienes, de los Servicios y de Producci&oacute;n del Anexo I Costos';
            $totalAnexoIIAdministracion=[]; 
            $totalAnexoIIAdministracion[$periodoActual]=$totalAnexoII[$periodoActual]*0.25;
            $totalAnexoIIAdministracion[$periodoPrevio]=$totalAnexoII[$periodoPrevio]*0.25;
            
            $notaPagoaprovedores['conceptos']['Gastos de Administracion']['valores']=  $totalAnexoIIAdministracion;
            $notaPagoaprovedores['conceptos']['Gastos de Administracion']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Gastos de Administracion']['resta']=false;
            $notaPagoaprovedores['conceptos']['Gastos de Administracion']['title']='Total de Anexo II Administracion';

            $totalAnexoIIComercializacion=[]; 
            $totalAnexoIIComercializacion[$periodoActual]=$totalAnexoII[$periodoActual]*0.75;
            $totalAnexoIIComercializacion[$periodoPrevio]=$totalAnexoII[$periodoPrevio]*0.75;
            
            $notaPagoaprovedores['conceptos']['Gastos de Comercializacion']['valores']=  $totalAnexoIIComercializacion;
            $notaPagoaprovedores['conceptos']['Gastos de Comercializacion']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Gastos de Comercializacion']['resta']=false;
            $notaPagoaprovedores['conceptos']['Gastos de Comercializacion']['title']='Total de Anexo II Comercializacion';

            $totalAnexoIIOperatios=[]; 
            $totalAnexoIIOperatios[$periodoActual]=$totalAnexoII[$periodoActual]*0;
            $totalAnexoIIOperatios[$periodoPrevio]=$totalAnexoII[$periodoPrevio]*0;
            
            $notaPagoaprovedores['conceptos']['Gastos de Operativos']['valores']=  $totalAnexoIIOperatios;
            $notaPagoaprovedores['conceptos']['Gastos de Operativos']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Gastos de Operativos']['resta']=false;
            $notaPagoaprovedores['conceptos']['Gastos de Operativos']['title']='Total de Anexo II Operativos';

            $totalAltaBDUInicio=[]; 
            $totalAltaBDUInicio[$periodoActual]=$totalBienesDeUso['altas']*-1;
            $totalAltaBDUInicio[$periodoPrevio]=$totalBienesDeUso['altasAnterior']*-1;
            //NO TENGO PARA EL EJ ANTERIOR DE BDU 
            
            /*$notaPagoaprovedores['conceptos']['Alta y Baja de Bienes de Uso']['valores']=$totalAltaBDUInicio;
            $notaPagoaprovedores['conceptos']['Alta y Baja de Bienes de Uso']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Alta y Baja de Bienes de Uso']['title']='Alta de Bienes de Usos del Anexo de Bienes de Uso del Estadod de Situacion Patrimonial';
             * */
             
            
            
            $notaPagoaprovedores['conceptos']['Amortizacion Inmuebles']['valores']=  $subtotalAmortizacionesInmuebles;
            $notaPagoaprovedores['conceptos']['Amortizacion Inmuebles']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Amortizacion Inmuebles']['title']='Anexo II Total Amortizacion Inmuebles';
            
            $notaPagoaprovedores['conceptos']['Amortizacion Rodados']['valores']=  $subtotalAmortizacionesRodados;
            $notaPagoaprovedores['conceptos']['Amortizacion Rodados']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Amortizacion Rodados']['title']='Anexo II Total Amortizacion Rodados';
            
            $notaPagoaprovedores['conceptos']['Amortizacion Instalaciones']['valores']=  $subtotalAmortizacionesInstalaciones;
            $notaPagoaprovedores['conceptos']['Amortizacion Instalaciones']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Amortizacion Instalaciones']['title']='Anexo II Total Amortizacion Instalaciones';
            
            $notaPagoaprovedores['conceptos']['Amortizacion Muebles y Utiles']['valores']=  $subtotalAmortizacionesMueblesYUtiles;
            $notaPagoaprovedores['conceptos']['Amortizacion Muebles y Utiles']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Amortizacion Muebles y Utiles']['title']='Anexo II Total Amortizacion Muebles y Utiles';
            
            $notaPagoaprovedores['conceptos']['Amortizacion Maquinarias']['valores']=  $subtotalAmortizacionesMaquinarias;
            $notaPagoaprovedores['conceptos']['Amortizacion Maquinarias']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Amortizacion Maquinarias']['title']='Anexo II Total Amortizacion Maquinarias';
            
            $notaPagoaprovedores['conceptos']['Amortizacion Activos Biologicos']['valores']=  $subtotalAmortizacionesActivosBiologicos;
            $notaPagoaprovedores['conceptos']['Amortizacion Activos Biologicos']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Amortizacion Activos Biologicos']['title']='Anexo II Total Amortizacion Activos Biologicos';
            
            $notaPagoaprovedores['conceptos']['Acredores Varios']['valores']=  $subtotalAcredoresVarios;
            $notaPagoaprovedores['conceptos']['Acredores Varios']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Acredores Varios']['title']='Anexo II Total Gtos. Financ. de Act. Operativ';
            
            $arrayPrefijos=[];
            $arrayPrefijos[]='505010125';
            $subtotalBancos1Intereses=[]; 
            acumularPrefijos($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$periodoPrevio,$keysCuentas,'todos',$subtotalBancos1Intereses);
            $notaPagoaprovedores['conceptos']['Bco 1 Intereses Sobre Descubiertos']['valores']=  $subtotalBancos1Intereses;
            $notaPagoaprovedores['conceptos']['Bco 1 Intereses Sobre Descubiertos']['composicion']='periodos';   
            $notaPagoaprovedores['conceptos']['Bco 1 Intereses Sobre Descubiertos']['title']='Cuenta 505010125';
            
            $arrayPrefijos=[];
            $arrayPrefijos[]='505010216';
            $subtotalBancos2Intereses=[]; 
            acumularPrefijos($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$periodoPrevio,$keysCuentas,'todos',$subtotalBancos2Intereses);
            $notaPagoaprovedores['conceptos']['Bco 2 Intereses Sobre Descubiertos']['valores']=  $subtotalBancos2Intereses;
            $notaPagoaprovedores['conceptos']['Bco 2 Intereses Sobre Descubiertos']['composicion']='periodos';   
            $notaPagoaprovedores['conceptos']['Bco 2 Intereses Sobre Descubiertos']['title']='Cuenta 505010216';
            $arrayPrefijos=[];
            $arrayPrefijos[]='505020325';
            $subtotalBancos3Intereses=[]; 
            acumularPrefijos($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$periodoPrevio,$keysCuentas,'todos',$subtotalBancos3Intereses);
            $notaPagoaprovedores['conceptos']['Bco 3 Intereses Sobre Descubiertos']['valores']=  $subtotalBancos3Intereses;
            $notaPagoaprovedores['conceptos']['Bco 3 Intereses Sobre Descubiertos']['composicion']='periodos';   
            $notaPagoaprovedores['conceptos']['Bco 3 Intereses Sobre Descubiertos']['title']='Cuenta 505020325'; 
            
            $arrayPrefijos=[];
            $arrayPrefijos[]='505020425';
            $subtotalBancos4Intereses=[]; 
            acumularPrefijos($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$periodoPrevio,$keysCuentas,'todos',$subtotalBancos4Intereses);
            $notaPagoaprovedores['conceptos']['Bco 4 Intereses Sobre Descubiertos']['valores']=  $subtotalBancos4Intereses;
            $notaPagoaprovedores['conceptos']['Bco 4 Intereses Sobre Descubiertos']['composicion']='periodos';   
            $notaPagoaprovedores['conceptos']['Bco 4 Intereses Sobre Descubiertos']['title']='Cuenta 505020425'; 
            
            $arrayPrefijos=[];
            $arrayPrefijos[]='505020525';
            $subtotalBancos5Intereses=[]; 
            acumularPrefijos($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$periodoPrevio,$keysCuentas,'todos',$subtotalBancos5Intereses);
            $notaPagoaprovedores['conceptos']['Bco 5 Intereses Sobre Descubiertos']['valores']=  $subtotalBancos5Intereses;
            $notaPagoaprovedores['conceptos']['Bco 5 Intereses Sobre Descubiertos']['composicion']='periodos';   
            $notaPagoaprovedores['conceptos']['Bco 5 Intereses Sobre Descubiertos']['title']='Cuenta 505020525'; 
            
            $arrayPrefijos=[];
            $arrayPrefijos[]='505020606';
            $subtotalBancos6Intereses=[]; 
            acumularPrefijos($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$periodoPrevio,$keysCuentas,'todos',$subtotalBancos6Intereses);
            $notaPagoaprovedores['conceptos']['Bco 6 Intereses Sobre Descubiertos']['valores']=  $subtotalBancos6Intereses;
            $notaPagoaprovedores['conceptos']['Bco 6 Intereses Sobre Descubiertos']['composicion']='periodos';   
            $notaPagoaprovedores['conceptos']['Bco 6 Intereses Sobre Descubiertos']['title']='Cuenta 505020606'; 
            
            $arrayPrefijos=[];
            $arrayPrefijos[]='505020706';
            $subtotalBancos7Intereses=[]; 
            acumularPrefijos($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$periodoPrevio,$keysCuentas,'todos',$subtotalBancos7Intereses);
            $notaPagoaprovedores['conceptos']['Bco 7 Intereses Sobre Descubiertos']['valores']=  $subtotalBancos7Intereses;
            $notaPagoaprovedores['conceptos']['Bco 7 Intereses Sobre Descubiertos']['composicion']='periodos';   
            $notaPagoaprovedores['conceptos']['Bco 7 Intereses Sobre Descubiertos']['title']='Cuenta 505020706'; 
            
            $arrayPrefijos=[];
            $arrayPrefijos[]='505020806';
            $subtotalBancos8Intereses=[]; 
            acumularPrefijos($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$periodoPrevio,$keysCuentas,'todos',$subtotalBancos8Intereses);
            $notaPagoaprovedores['conceptos']['Bco 8 Intereses Sobre Descubiertos']['valores']=  $subtotalBancos8Intereses;
            $notaPagoaprovedores['conceptos']['Bco 8 Intereses Sobre Descubiertos']['composicion']='periodos';   
            $notaPagoaprovedores['conceptos']['Bco 8 Intereses Sobre Descubiertos']['title']='Cuenta 505020806'; 
            
            $arrayPrefijos=[];
            $arrayPrefijos[]='505020906';
            $subtotalBancos9Intereses=[]; 
            acumularPrefijos($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$periodoPrevio,$keysCuentas,'todos',$subtotalBancos9Intereses);
            $notaPagoaprovedores['conceptos']['Bco 9 Intereses Sobre Descubiertos']['valores']=  $subtotalBancos9Intereses;
            $notaPagoaprovedores['conceptos']['Bco 9 Intereses Sobre Descubiertos']['composicion']='periodos';   
            $notaPagoaprovedores['conceptos']['Bco 9 Intereses Sobre Descubiertos']['title']='Cuenta 505020906'; 
            
            $arrayPrefijos=[];
            $arrayPrefijos[]='505021006';
            $subtotalBancos10Intereses=[]; 
            acumularPrefijos($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$periodoPrevio,$keysCuentas,'todos',$subtotalBancos10Intereses);
            $notaPagoaprovedores['conceptos']['Bco 10 Intereses Sobre Descubiertos']['valores']=  $subtotalBancos10Intereses;
            $notaPagoaprovedores['conceptos']['Bco 10 Intereses Sobre Descubiertos']['composicion']='periodos';   
            $notaPagoaprovedores['conceptos']['Bco 10 Intereses Sobre Descubiertos']['title']='Cuenta 505021006'; 
                        
            $notaPagoaprovedores['conceptos']['AFIP Intereses Generales']['valores']=  $subtotalAFIP;
            $notaPagoaprovedores['conceptos']['AFIP Intereses Generales']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['AFIP Intereses Generales']['title']='Anexo II Organismos Publicos AFIP'; 
            
            $notaPagoaprovedores['conceptos']['DGR Intereses Generales']['valores']=  $subtotalDGR;
            $notaPagoaprovedores['conceptos']['DGR Intereses Generales']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['DGR Intereses Generales']['title']='Anexo II Organismos Publicos DGR'; 
            
            $notaPagoaprovedores['conceptos']['DGRM Intereses Generales']['valores']=  $subtotalDGRM;
            $notaPagoaprovedores['conceptos']['DGRM Intereses Generales']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['DGRM Intereses Generales']['title']='Anexo II Organismos Publicos DGRM'; 
            
            $notaPagoaprovedores['conceptos']['Ganancia Minima Presunta']['valores']=  $subtotalGananciaMinimaPresunta;
            $notaPagoaprovedores['conceptos']['Ganancia Minima Presunta']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Ganancia Minima Presunta']['title']='Anexo II Gastos Fiscales - AFIP'; 
            
            $notaPagoaprovedores['conceptos']['Bienes Personales']['valores']=  $subtotalBienesPersonales;
            $notaPagoaprovedores['conceptos']['Bienes Personales']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Bienes Personales']['title']='Anexo II Organismos Publicos DGRM'; 
            
            $notaPagoaprovedores['conceptos']['Otros Impuestos Nacionales']['valores']=  $subtotalOtrosImpuestosNacionales;
            $notaPagoaprovedores['conceptos']['Otros Impuestos Nacionales']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Otros Impuestos Nacionales']['title']='Anexo II Gastos Fiscales - AFIP'; 
            
            $notaPagoaprovedores['conceptos']['Ingresos brutos']['valores']=  $subtotalIngresosbrutos;
            $notaPagoaprovedores['conceptos']['Ingresos brutos']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Ingresos brutos']['title']='Anexo II Gastos Fiscales - DGR'; 
            
            $notaPagoaprovedores['conceptos']['Cooperadoras Asistensiales']['valores']=  $subtotalCooperadoraAsistencial;
            $notaPagoaprovedores['conceptos']['Cooperadoras Asistensiales']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Cooperadoras Asistensiales']['title']='Anexo II Gastos Fiscales - DGR'; 
            
            $notaPagoaprovedores['conceptos']['Inmobiliaria Rural']['valores']=  $subtotalInmobiliarioRural;
            $notaPagoaprovedores['conceptos']['Inmobiliaria Rural']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Inmobiliaria Rural']['title']='Anexo II Gastos Fiscales - DGR'; 
            
            $notaPagoaprovedores['conceptos']['Impuesto a los Sellos']['valores']=  $subtotalImpuestoaSellos;
            $notaPagoaprovedores['conceptos']['Impuesto a los Sellos']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Impuesto a los Sellos']['title']='Anexo II Gastos Fiscales - DGR'; 
            
            $notaPagoaprovedores['conceptos']['Actividades Varias']['valores']=  $subtotalActividadesVarias;
            $notaPagoaprovedores['conceptos']['Actividades Varias']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Actividades Varias']['title']='Anexo II Gastos Fiscales - DGRM'; 
            
            $notaPagoaprovedores['conceptos']['Tasa de Publicidad y Propaganda']['valores']=  $subtotalTasadepublicidadypropaganda;
            $notaPagoaprovedores['conceptos']['Tasa de Publicidad y Propaganda']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Tasa de Publicidad y Propaganda']['title']='Anexo II Gastos Fiscales - DGRM'; 
            
            $notaPagoaprovedores['conceptos']['Inmobiliario Urbano']['valores']=  $subtotalInmobiliarioUrbano;
            $notaPagoaprovedores['conceptos']['Inmobiliario Urbano']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Inmobiliario Urbano']['title']='Anexo II Gastos Fiscales - DGRM'; 
            $notaPagoaprovedores['conceptos']['Inmobiliario Urbano']['composicion']='periodos';
            
            $notaPagoaprovedores['conceptos']['Alumbrado y Limpieza']['valores']=  $subtotalAlumbradoyLimpieza;
            $notaPagoaprovedores['conceptos']['Alumbrado y Limpieza']['title']='Anexo II Gastos Fiscales - DGRM'; 
            $notaPagoaprovedores['conceptos']['Alumbrado y Limpieza']['composicion']='periodos';
            
            $notaPagoaprovedores['conceptos']['Impuesto al Automotor']['valores']=  $subtotalImpuestoAutomotor;
            $notaPagoaprovedores['conceptos']['Impuesto al Automotor']['title']='Anexo II Gastos Fiscales - DGRM'; 
            $notaPagoaprovedores['conceptos']['Impuesto al Automotor']['composicion']='periodos';
            
            $notaPagoaprovedores['conceptos']['Mano de Obra']['valores']=  $subtotalManodeObra;
            $notaPagoaprovedores['conceptos']['Mano de Obra']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Mano de Obra']['title']='Remuneraciones y Cargas Sociales'; 
            
            $notaPagoaprovedores['conceptos']['Contribucion Empleador']['valores']=  $subtotalContribucionesEmpleador;
            $notaPagoaprovedores['conceptos']['Contribucion Empleador']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Contribucion Empleador']['title']='Remuneraciones y Cargas Sociales'; 
            
            $notaPagoaprovedores['conceptos']['Mano de Obra.']['valores']=  $subtotalManodeObra2;
            $notaPagoaprovedores['conceptos']['Mano de Obra.']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Mano de Obra.']['title']='Remuneraciones y Cargas Sociales'; 
                    
            $notaPagoaprovedores['conceptos']['Contribucion Empleador.']['valores']=  $subtotalContribucionesEmpleador2;
            $notaPagoaprovedores['conceptos']['Contribucion Empleador.']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Contribucion Empleador.']['title']='Remuneraciones y Cargas Sociales'; 
            
            $notaPagoaprovedores['conceptos']['Gastos Extraordinarios']['valores']=  $subtotalGastosExtraordinarios;
            $notaPagoaprovedores['conceptos']['Gastos Extraordinarios']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Gastos Extraordinarios']['title']='Remuneraciones y Cargas Sociales'; 
                       
            //Deudas Comerciales Corriente Inicio
            $arrayPrefijos=['210100','210708','210709','210710'];
                                  
            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',1);
             
            $totalDeudasComercialesCorrienteInicio=[]; 
            $totalDeudasComercialesCorrienteInicio[$periodoActual]=$subtotalActual*-1;
            $totalDeudasComercialesCorrienteInicio[$periodoPrevio]=$subtotalPrevio*-1;
            
            $notaPagoaprovedores['conceptos']['Deudas comerciales Corrientes al Inicio']['valores']=  $totalDeudasComercialesCorrienteInicio;
            $notaPagoaprovedores['conceptos']['Deudas comerciales Corrientes al Inicio']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Deudas comerciales Corrientes al Inicio']['title']='Cuentas 210100/210708/210709/210710 en el asiento de apertura';
            
            $notaPagoaprovedores['conceptos']['Deudas comerciales Corrientes al Cierre']['valores']=  $totalDeudasComerciales;
            $notaPagoaprovedores['conceptos']['Deudas comerciales Corrientes al Cierre']['composicion']='corriente';                     
            $notaPagoaprovedores['conceptos']['Deudas comerciales Corrientes al Cierre']['title']='Deudas Comerciales Corrientes del Estado de Situacion Patrimonial';
            $notaPagoaprovedores['conceptos']['Deudas comerciales Corrientes al Cierre']['resta']=false;
            
            //Deudas Comerciales NO Corriente Inicio
            $arrayPrefijos=['220100','220708','220709','220710'];
            
            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',1);
             
            $totalDeudasComercialesNoCorrienteInicio=[]; 
            $totalDeudasComercialesNoCorrienteInicio[$periodoActual]=$subtotalActual*-1;
            $totalDeudasComercialesNoCorrienteInicio[$periodoPrevio]=$subtotalPrevio*-1;
            
            $notaPagoaprovedores['conceptos']['Deudas comerciales No Corrientes al Inicio']['valores']=  $totalDeudasComercialesNoCorrienteInicio;
            $notaPagoaprovedores['conceptos']['Deudas comerciales No Corrientes al Inicio']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Deudas comerciales No Corrientes al Inicio']['title']='Cuentas 220100/220708/220709/220710 en el asiento de apertura';
            
            $notaPagoaprovedores['conceptos']['Deudas comerciales No Corrientes al Cierre']['valores']=  $totalDeudasComerciales;
            $notaPagoaprovedores['conceptos']['Deudas comerciales No Corrientes al Cierre']['composicion']='nocorriente';
            $notaPagoaprovedores['conceptos']['Deudas comerciales No Corrientes al Cierre']['title']='Deudas Comerciales No Corrientes del Estado de Situacion Patrimonial';
            
            //Anticipos de Clientes Corrientes Al inicio
            $arrayPrefijos=['21051','21053','210599'];
            
            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',1);
             
            $totalanticipodeclientesCorrienteInicio=[]; 
            $totalanticipodeclientesCorrienteInicio[$periodoActual]=$subtotalActual*-1;
            $totalanticipodeclientesCorrienteInicio[$periodoPrevio]=$subtotalPrevio*-1;
            
            $notaPagoaprovedores['conceptos']['Anticipo de clientes Corrientes al Inicio']['valores']=  $totalanticipodeclientesCorrienteInicio;
            $notaPagoaprovedores['conceptos']['Anticipo de clientes Corrientes al Inicio']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Anticipo de clientes Corrientes al Inicio']['title']='Cuentas 21051/21053/210599 en el asiento de apertura';
            
            $notaPagoaprovedores['conceptos']['Anticipo de clientes Corrientes al Cierre']['valores']=  $totalanticipodeclientes;
            $notaPagoaprovedores['conceptos']['Anticipo de clientes Corrientes al Cierre']['composicion']='corriente';
            $notaPagoaprovedores['conceptos']['Anticipo de clientes Corrientes al Cierre']['title']='Anticipo de Clientes Corrientes del Estado de Situacion Patrimonial';
            
            //Anticipos de Clientes No Corrientes al inicio
            $arrayPrefijos=['22051','22053','220599',];

           
            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',1);
             
            $totalanticipodeclientesNoCorrienteInicio=[]; 
            $totalanticipodeclientesNoCorrienteInicio[$periodoActual]=$subtotalActual*-1;
            $totalanticipodeclientesNoCorrienteInicio[$periodoPrevio]=$subtotalPrevio*-1;
            
            $notaPagoaprovedores['conceptos']['Anticipo de clientes No Corrientes al Inicio']['valores']=  $totalanticipodeclientesNoCorrienteInicio;
            $notaPagoaprovedores['conceptos']['Anticipo de clientes No Corrientes al Inicio']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Anticipo de clientes No Corrientes al Inicio']['title']='Cuentas 22051/22053/220599 en el asiento de apertura';
            
            $notaPagoaprovedores['conceptos']['Anticipo de clientes No Corrientes al Cierre']['valores']=  $totalanticipodeclientes;
            $notaPagoaprovedores['conceptos']['Anticipo de clientes No Corrientes al Cierre']['composicion']='nocorriente';
            $notaPagoaprovedores['conceptos']['Anticipo de clientes No Corrientes al Cierre']['title']='Anticipo de Clientes No Corrientes del Estado de Situacion Patrimonial';
            //Bienes de Cambios Corrientes Al inicio
            $arrayPrefijos=[];
            $arrayPrefijos[]='1105000';
            $arrayPrefijos[]='1105020';
            $arrayPrefijos[]='1105040';
            $arrayPrefijos[]='1105060';
            $arrayPrefijos[]='1105070';
            
            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',1);
             
            $totalBienesdeCambioCorrienteInicio=[]; 
            $totalBienesdeCambioCorrienteInicio[$periodoActual]=$subtotalActual*-1;
            $totalBienesdeCambioCorrienteInicio[$periodoPrevio]=$subtotalPrevio*-1;
            
            $notaPagoaprovedores['conceptos']['Bienes de Cambio Corrientes al Inicio']['valores']=  $totalBienesdeCambioCorrienteInicio;
            $notaPagoaprovedores['conceptos']['Bienes de Cambio Corrientes al Inicio']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Bienes de Cambio Corrientes al Inicio']['title']='Cuentas 1105000/1105020/1105040/1105060/1105070 en el asiento de apertura';
            
            $notaPagoaprovedores['conceptos']['Bienes de Cambio Corrientes al Cierre']['valores']=  $totalBienesdeCambio;
            $notaPagoaprovedores['conceptos']['Bienes de Cambio Corrientes al Cierre']['composicion']='corriente';
            $notaPagoaprovedores['conceptos']['Bienes de Cambio Corrientes al Cierre']['title']='Bienes de Cambio Corrientes del Estado de Situacion Patrimonial';
            //$notaPagoaprovedores['conceptos']['Bienes de Cambio Corrientes al Cierre']['resta']=false;
            
            $arrayPrefijos=[];
            $arrayPrefijos[]='1205000';
            $arrayPrefijos[]='1205020';
            $arrayPrefijos[]='1205040';
            $arrayPrefijos[]='1205060';
            $arrayPrefijos[]='1205060';
            
            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',1);
             
            $totalBienesdeCambioNoCorrienteInicio=[]; 
            $totalBienesdeCambioNoCorrienteInicio[$periodoActual]=$subtotalActual*-1;
            $totalBienesdeCambioNoCorrienteInicio[$periodoPrevio]=$subtotalPrevio*-1;
            
            $notaPagoaprovedores['conceptos']['Bienes de Cambio No Corrientes al Inicio']['valores']=  $totalBienesdeCambioNoCorrienteInicio;
            $notaPagoaprovedores['conceptos']['Bienes de Cambio No Corrientes al Inicio']['composicion']='periodos';
            $notaPagoaprovedores['conceptos']['Bienes de Cambio No Corrientes al Inicio']['title']='Cuentas 1205000/1205020/1205040/1205060/1205070 en el asiento de apertura';
            
            $notaPagoaprovedores['conceptos']['Bienes de Cambio No Corrientes al Cierre']['valores']=  $totalBienesdeCambio;
            $notaPagoaprovedores['conceptos']['Bienes de Cambio No Corrientes al Cierre']['composicion']='nocorriente';
            $notaPagoaprovedores['conceptos']['Bienes de Cambio No Corrientes al Cierre']['title']='Bienes de Cambio No Corrientes del Estado de Situacion Patrimonial';
            
            
            mostrarNotaEFE($notaPagoaprovedores,$periodoPrevio, $periodoActual,$numeroNota);
            
                       
            //NOTA: Pagos al personal y cargas sociales
            $notaPagoalpersonalycargassociales = [];
            $notaPagoalpersonalycargassociales['nombreNota']='Pago al Personal y Cargas Sociales';
            $notaPagoalpersonalycargassociales['conceptos']['Mano de Obra']['valores']=  $subtotalManodeObra;
            $notaPagoalpersonalycargassociales['conceptos']['Mano de Obra']['composicion']='periodos';
            $notaPagoalpersonalycargassociales['conceptos']['Mano de Obra']['resta']=false;
            $notaPagoalpersonalycargassociales['conceptos']['Mano de Obra']['title']='Anexo II Remuneraciones y Cargas Sociales'; 

            $notaPagoalpersonalycargassociales['conceptos']['Contribucion Empleador']['valores']=  $subtotalContribucionesEmpleador;
            $notaPagoalpersonalycargassociales['conceptos']['Contribucion Empleador']['composicion']='periodos';
            $notaPagoalpersonalycargassociales['conceptos']['Contribucion Empleador']['resta']=false;
            $notaPagoalpersonalycargassociales['conceptos']['Contribucion Empleador']['title']='Anexo II Remuneraciones y Cargas Sociales'; 
            
            $notaPagoalpersonalycargassociales['conceptos']['Mano de Obra.']['valores']=  $subtotalManodeObra2;
            $notaPagoalpersonalycargassociales['conceptos']['Mano de Obra.']['composicion']='periodos';
            $notaPagoalpersonalycargassociales['conceptos']['Mano de Obra.']['resta']=false;
            $notaPagoalpersonalycargassociales['conceptos']['Mano de Obra.']['title']='Anexo II Remuneraciones y Cargas Sociales'; 
                    
            $notaPagoalpersonalycargassociales['conceptos']['Contribucion Empleador.']['valores']=  $subtotalContribucionesEmpleador2;
            $notaPagoalpersonalycargassociales['conceptos']['Contribucion Empleador.']['composicion']='periodos';
            $notaPagoalpersonalycargassociales['conceptos']['Contribucion Empleador.']['resta']=false;
            $notaPagoalpersonalycargassociales['conceptos']['Contribucion Empleador.']['title']='Anexo II Remuneraciones y Cargas Sociales'; 
            
            //Remunerac. y Cargas Sociales Corrientes al Inicio
            $arrayPrefijos=[];
            $arrayPrefijos[]='210301';
            $arrayPrefijos[]='210302';
            $arrayPrefijos[]='210303';

            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',1);
             
            $totalremuneracionycargassocialesInicioCorriente=[]; 
            $totalremuneracionycargassocialesInicioCorriente[$periodoActual]=$subtotalActual*-1;
            $totalremuneracionycargassocialesInicioCorriente[$periodoPrevio]=$subtotalPrevio*-1;
            
            $notaPagoalpersonalycargassociales['conceptos']['Remunerac. y Cargas sociales Corrientes al Inicio']['valores']=  $totalremuneracionycargassocialesInicioCorriente;
            $notaPagoalpersonalycargassociales['conceptos']['Remunerac. y Cargas sociales Corrientes al Inicio']['composicion']='periodos';
            $notaPagoalpersonalycargassociales['conceptos']['Remunerac. y Cargas sociales Corrientes al Inicio']['title']='Cuentas 210301/210302/210303 en el asiento de apertura';
            
            $notaPagoalpersonalycargassociales['conceptos']['Remunerac. y Cargas sociales Corrientes al Cierre']['valores']=  $totalremuneracionycargassociales;
            $notaPagoalpersonalycargassociales['conceptos']['Remunerac. y Cargas sociales Corrientes al Cierre']['composicion']='corriente';
            $notaPagoalpersonalycargassociales['conceptos']['Remunerac. y Cargas sociales Corrientes al Cierre']['resta']=false;
            $notaPagoalpersonalycargassociales['conceptos']['Remunerac. y Cargas sociales Corrientes al Cierre']['title']='Remuneraciones y Cargas Sociales Corrientes del Estado de Situacion Patrimonial';

            $arrayPrefijos=[];
            $arrayPrefijos[]='220301';
            $arrayPrefijos[]='220302';                                                      
            $arrayPrefijos[]='220303';    
                
            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',1);
             
            $totalremuneracionycargassocialesInicioNoCorriente=[]; 
            $totalremuneracionycargassocialesInicioNoCorriente[$periodoActual]=$subtotalActual*-1;
            $totalremuneracionycargassocialesInicioNoCorriente[$periodoPrevio]=$subtotalPrevio*-1;
            
            $notaPagoalpersonalycargassociales['conceptos']['Remunerac. y Cargas sociales No Corrientes al Inicio']['valores']=  $totalremuneracionycargassocialesInicioNoCorriente;
            $notaPagoalpersonalycargassociales['conceptos']['Remunerac. y Cargas sociales No Corrientes al Inicio']['composicion']='periodos';
            $notaPagoalpersonalycargassociales['conceptos']['Remunerac. y Cargas sociales No Corrientes al Inicio']['title']='Cuentas 220301/220302/220303 en el asiento de apertura';
            
            $notaPagoalpersonalycargassociales['conceptos']['Remunerac. y Cargas sociales No Corrientes al Cierre']['valores']=  $totalremuneracionycargassociales;
            $notaPagoalpersonalycargassociales['conceptos']['Remunerac. y Cargas sociales No Corrientes al Cierre']['composicion']='nocorriente';
            $notaPagoalpersonalycargassociales['conceptos']['Remunerac. y Cargas sociales No Corrientes al Cierre']['resta']=false;
            $notaPagoalpersonalycargassociales['conceptos']['Remunerac. y Cargas sociales No Corrientes al Cierre']['title']='Remuneraciones y Cargas Sociales No Corrientes del Estado de Situacion Patrimonial';
            
            mostrarNotaEFE($notaPagoalpersonalycargassociales,$periodoPrevio, $periodoActual,$numeroNota);
            
            //NOTA: Pago de Impuestos
            $notaPagodeImpuestos = [];
            $notaPagodeImpuestos['nombreNota']='Pago de impuestos';
            /*$notaPagodeImpuestos['conceptos']['AFIP Intereses Generales']['valores']=  $subtotalAFIP;
            $notaPagodeImpuestos['conceptos']['AFIP Intereses Generales']['composicion']='periodos';
            $notaPagodeImpuestos['conceptos']['AFIP Intereses Generales']['resta']=false;
            $notaPagodeImpuestos['conceptos']['AFIP Intereses Generales']['title']='Anexo II Organismos Publicos'; 
 
            $notaPagodeImpuestos['conceptos']['DGR Intereses Generales']['valores']=  $subtotalDGR;
            $notaPagodeImpuestos['conceptos']['DGR Intereses Generales']['composicion']='periodos';
            $notaPagodeImpuestos['conceptos']['DGR Intereses Generales']['resta']=false;
            $notaPagodeImpuestos['conceptos']['DGR Intereses Generales']['title']='Anexo II Organismos Publicos'; 

            $notaPagodeImpuestos['conceptos']['DGRM Intereses Generales']['valores']=  $subtotalDGRM;
            $notaPagodeImpuestos['conceptos']['DGRM Intereses Generales']['composicion']='periodos';
            $notaPagodeImpuestos['conceptos']['DGRM Intereses Generales']['resta']=false;
            $notaPagodeImpuestos['conceptos']['DGRM Intereses Generales']['title']='Anexo II Organismos Publicos'; */
            
            $notaPagodeImpuestos['conceptos']['Ganancia Minima Presunta']['valores']=  $subtotalGananciaMinimaPresunta;
            $notaPagodeImpuestos['conceptos']['Ganancia Minima Presunta']['composicion']='periodos';
            $notaPagodeImpuestos['conceptos']['Ganancia Minima Presunta']['resta']=false;            
            $notaPagodeImpuestos['conceptos']['Ganancia Minima Presunta']['title']='Anexo II Gastos Fiscales - AFIP'; 
            
            $notaPagodeImpuestos['conceptos']['Bienes Personales']['valores']=  $subtotalBienesPersonales;
            $notaPagodeImpuestos['conceptos']['Bienes Personales']['composicion']='periodos';
            $notaPagodeImpuestos['conceptos']['Bienes Personales']['resta']=false;
            $notaPagodeImpuestos['conceptos']['Bienes Personales']['title']='Anexo II Gastos Fiscales - AFIP'; 
            
            $notaPagodeImpuestos['conceptos']['Otros Impuestos Nacionales']['valores']=  $subtotalOtrosImpuestosNacionales;
            $notaPagodeImpuestos['conceptos']['Otros Impuestos Nacionales']['composicion']='periodos';
            $notaPagodeImpuestos['conceptos']['Otros Impuestos Nacionales']['resta']=false;
            $notaPagodeImpuestos['conceptos']['Otros Impuestos Nacionales']['title']='Anexo II Gastos Fiscales - AFIP'; 
            
            $notaPagodeImpuestos['conceptos']['Ingresos brutos']['valores']=  $subtotalIngresosbrutos;
            $notaPagodeImpuestos['conceptos']['Ingresos brutos']['composicion']='periodos';
            $notaPagodeImpuestos['conceptos']['Ingresos brutos']['resta']=false;
            $notaPagodeImpuestos['conceptos']['Ingresos brutos']['title']='Anexo II Gastos Fiscales - DGR'; 
            
            $notaPagodeImpuestos['conceptos']['Cooperadoras Asistensiales']['valores']=  $subtotalCooperadoraAsistencial;
            $notaPagodeImpuestos['conceptos']['Cooperadoras Asistensiales']['composicion']='periodos';
            $notaPagodeImpuestos['conceptos']['Cooperadoras Asistensiales']['resta']=false;
            $notaPagodeImpuestos['conceptos']['Cooperadoras Asistensiales']['title']='Anexo II Gastos Fiscales - DGR'; 
            
            $notaPagodeImpuestos['conceptos']['Inmobiliaria Rural']['valores']=  $subtotalInmobiliarioRural;
            $notaPagodeImpuestos['conceptos']['Inmobiliaria Rural']['composicion']='periodos';
            $notaPagodeImpuestos['conceptos']['Inmobiliaria Rural']['resta']=false;
            $notaPagodeImpuestos['conceptos']['Inmobiliaria Rural']['title']='Anexo II Gastos Fiscales - DGR'; 
            
            $notaPagodeImpuestos['conceptos']['Impuesto a los Sellos']['valores']=  $subtotalImpuestoaSellos;
            $notaPagodeImpuestos['conceptos']['Impuesto a los Sellos']['composicion']='periodos';
            $notaPagodeImpuestos['conceptos']['Impuesto a los Sellos']['resta']=false;
            $notaPagodeImpuestos['conceptos']['Impuesto a los Sellos']['title']='Anexo II Gastos Fiscales - DGR'; 
            
            $notaPagodeImpuestos['conceptos']['Actividades Varias']['valores']=  $subtotalActividadesVarias;
            $notaPagodeImpuestos['conceptos']['Actividades Varias']['composicion']='periodos';
            $notaPagodeImpuestos['conceptos']['Actividades Varias']['resta']=false;
            $notaPagodeImpuestos['conceptos']['Actividades Varias']['title']='Anexo II Gastos Fiscales - DGRM'; 
            
            $notaPagodeImpuestos['conceptos']['Tasa de Publicidad y Propaganda']['valores']=  $subtotalTasadepublicidadypropaganda;
            $notaPagodeImpuestos['conceptos']['Tasa de Publicidad y Propaganda']['composicion']='periodos';
            $notaPagodeImpuestos['conceptos']['Tasa de Publicidad y Propaganda']['resta']=false;
            $notaPagodeImpuestos['conceptos']['Tasa de Publicidad y Propaganda']['title']='Anexo II Gastos Fiscales - DGRM'; 
            
            $notaPagodeImpuestos['conceptos']['Inmobiliario Urbano']['valores']=  $subtotalInmobiliarioUrbano;
            $notaPagodeImpuestos['conceptos']['Inmobiliario Urbano']['composicion']='periodos';
            $notaPagodeImpuestos['conceptos']['Inmobiliario Urbano']['resta']=false;
            $notaPagodeImpuestos['conceptos']['Inmobiliario Urbano']['title']='Anexo II Gastos Fiscales - DGRM'; 
            
            $notaPagodeImpuestos['conceptos']['Alumbrado y Limpieza']['valores']=  $subtotalAlumbradoyLimpieza;
            $notaPagodeImpuestos['conceptos']['Alumbrado y Limpieza']['composicion']='periodos';
            $notaPagodeImpuestos['conceptos']['Alumbrado y Limpieza']['resta']=false;
            $notaPagodeImpuestos['conceptos']['Alumbrado y Limpieza']['title']='Anexo II Gastos Fiscales - DGRM'; 
            
            $notaPagodeImpuestos['conceptos']['Impuesto al Automotor']['valores']=  $subtotalImpuestoAutomotor;
            $notaPagodeImpuestos['conceptos']['Impuesto al Automotor']['composicion']='periodos';
            $notaPagodeImpuestos['conceptos']['Impuesto al Automotor']['resta']=false;
            $notaPagodeImpuestos['conceptos']['Impuesto al Automotor']['title']='Anexo II Gastos Fiscales - DGRM'; 
            
            //Cargas Fiscales Corrientes al Inicio 
            $arrayPrefijos=['2104011','2104012','2104013','2104014','2104018','2104019','2104021','2104022','2104023','2104031','2104032','2104033','2104034','2104035'];

            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',1);
             
            $totalcargasfiscalesInicioCorriente=[]; 
            $totalcargasfiscalesInicioCorriente[$periodoActual]=$subtotalActual*-1;
            $totalcargasfiscalesInicioCorriente[$periodoPrevio]=$subtotalPrevio*-1;
            
            $notaPagodeImpuestos['conceptos']['Cargas Fiscales Corrientes al Inicio']['valores']=  $totalcargasfiscalesInicioCorriente;
            $notaPagodeImpuestos['conceptos']['Cargas Fiscales Corrientes al Inicio']['composicion']='periodos';
            $notaPagodeImpuestos['conceptos']['Cargas Fiscales Corrientes al Inicio']['title']='Cuentas 2104011/2104012/2104013/2104014/2104018/2104019/2104021/2104022/2104023/2104031/2104032/2104033/2104034/2104035 en el asiento de apertura';

            $notaPagodeImpuestos['conceptos']['Cargas Fiscales Corrientes al Cierre']['valores']=  $totalcargasfiscales;
            $notaPagodeImpuestos['conceptos']['Cargas Fiscales Corrientes al Cierre']['composicion']='corriente';
            $notaPagodeImpuestos['conceptos']['Cargas Fiscales Corrientes al Cierre']['resta']=false;
            $notaPagodeImpuestos['conceptos']['Cargas Fiscales Corrientes al Cierre']['title']='Cargas Fiscales Corrientes del Estado de Situacion Patrimonial' ;

            $arrayPrefijos=['2204011','2204012','2204013','2204014','2204018','2204019','2204011','2204022','2204023','2204031','2204032','2204033','2204034','2204035',];
            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',1);
             
            $totalcargasfiscalesInicioNOCorriente=[]; 
            $totalcargasfiscalesInicioNOCorriente[$periodoActual]=$subtotalActual*-1;
            $totalcargasfiscalesInicioNOCorriente[$periodoPrevio]=$subtotalPrevio*-1;
            
            $notaPagodeImpuestos['conceptos']['Cargas Fiscales No Corrientes al Inicio']['valores']=  $totalcargasfiscalesInicioNOCorriente;
            $notaPagodeImpuestos['conceptos']['Cargas Fiscales No Corrientes al Inicio']['composicion']='periodos';
            $notaPagodeImpuestos['conceptos']['Cargas Fiscales No Corrientes al Inicio']['title']='Cuentas 2204011/2204012/2204013/2204014/2204018/2204019/2204011/2204022/2204023/2204031/2204032/2204033/2204034/2204035 en el asiento de apertura';
            
            $notaPagodeImpuestos['conceptos']['Cargas Fiscales No Corrientes al Cierre']['valores']=  $totalcargasfiscales;
            $notaPagodeImpuestos['conceptos']['Cargas Fiscales No Corrientes al Cierre']['composicion']='nocorriente';
            $notaPagodeImpuestos['conceptos']['Cargas Fiscales No Corrientes al Cierre']['resta']=false;
            $notaPagodeImpuestos['conceptos']['Cargas Fiscales No Corrientes al Cierre']['title']='Cargas Fiscales No Corrientes del Estado de Situacion Patrimonial' ;


            mostrarNotaEFE($notaPagodeImpuestos,$periodoPrevio, $periodoActual,$numeroNota);
            
            //NOTA: Cobro de Dividendos
            $notaCobroDividendos = [];
            $notaCobroDividendos['nombreNota']='Cobro de dividendos';
            $notaCobroDividendos['conceptos']['Resultado de inversion en entes relacionados']['valores']=$totalinversionesenentes;
            $notaCobroDividendos['conceptos']['Resultado de inversion en entes relacionados']['composicion']='periodos';
            $notaCobroDividendos['conceptos']['Resultado de inversion en entes relacionados']['resta']=false;
            $notaCobroDividendos['conceptos']['Resultado de inversion en entes relacionados']['title']='Resultado de inversiones en entes relacionados del Estado de Resultados';
            
            //Participacion en Sociedades al Inicio
            $arrayPrefijos=[];
            $arrayPrefijos[]='120700';
            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',1);             
            $totalparticipacionensociedadesInicioNOCorriente=[]; 
            $totalparticipacionensociedadesInicioNOCorriente[$periodoActual]=$subtotalActual*-1;
            $totalparticipacionensociedadesInicioNOCorriente[$periodoPrevio]=$subtotalPrevio*-1;            
            $notaCobroDividendos['conceptos']['Participaci&oacute;n en Sociedades al Inicio']['valores']=  $totalparticipacionensociedadesInicioNOCorriente;
            $notaCobroDividendos['conceptos']['Participaci&oacute;n en Sociedades al Inicio']['composicion']='periodos';        
            $notaCobroDividendos['conceptos']['Participaci&oacute;n en Sociedades al Inicio']['title']='Cuenta  120700 en el asiento de apertura';   

            $notaCobroDividendos['conceptos']['Participaci&oacute;n en Sociedades al Cierre']['valores']=  $totalparticipacionensociedades;
            $notaCobroDividendos['conceptos']['Participaci&oacute;n en Sociedades al Cierre']['composicion']='nocorriente';
            $notaCobroDividendos['conceptos']['Participaci&oacute;n en Sociedades al Cierre']['resta']=false;
            $notaCobroDividendos['conceptos']['Participaci&oacute;n en Sociedades al Cierre']['title']='Participacion en Sociedades del Estado de Situacion Patrimonial';

            mostrarNotaEFE($notaCobroDividendos,$periodoPrevio, $periodoActual,$numeroNota);
            
             //NOTA: Pago de Dividendos
            $notaPagoDividendos = [];
            $notaPagoDividendos['nombreNota']='Cobro de dividendos';
            
            /*$notaPagoDividendos['conceptos']['Resultado de inversion en entes relacionados']['valores']=$totalinversionesenentes;
            $notaPagoDividendos['conceptos']['Resultado de inversion en entes relacionados']['composicion']='periodos';
            $notaPagoDividendos['conceptos']['Resultado de inversion en entes relacionados']['resta']=false;*/
            //Participacion en Sociedades al Inicio
            $arrayPrefijos=[];
            $arrayPrefijos[]='2106';
            $arrayPrefijos[]='2206';
            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',1);             
            $totaldividendosapagarInicioCorriente=[]; 
            $totaldividendosapagarInicioCorriente[$periodoActual]=$subtotalActual*-1;
            $totaldividendosapagarInicioCorriente[$periodoPrevio]=$subtotalPrevio*-1;            
            $notaPagoDividendos['conceptos']['Dividendos a Pagar al Inicio']['valores']=  $totaldividendosapagarInicioCorriente;
            $notaPagoDividendos['conceptos']['Dividendos a Pagar al Inicio']['composicion']='periodos';       
            $notaPagoDividendos['conceptos']['Dividendos a Pagar al Inicio']['title']='Cuenta 2106/2206 en el asiento de apertura';   

            $notaPagoDividendos['conceptos']['Dividendos a Pagar al Cierre']['valores']=  $totaldividendosapagar;
            $notaPagoDividendos['conceptos']['Dividendos a Pagar al Cierre']['composicion']='corriente';
            $notaPagoDividendos['conceptos']['Dividendos a Pagar al Cierre']['resta']=false;
            $notaPagoDividendos['conceptos']['Dividendos a Pagar al Cierre']['title']='Dividendos a Pagar del Estado de Situacion Patrimonial';
                      
            mostrarNotaEFE($notaPagoDividendos,$periodoPrevio, $periodoActual,$numeroNota);
            
             //NOTA: Pago de Intereses
            $notaPagoIntereses = [];
            $notaPagoIntereses['nombreNota']='Pago de Intereses';
            
            $notaPagoIntereses['conceptos']['Int. generados por acreed. vs']['valores']=$subtotalAcredoresVarios;
            $notaPagoIntereses['conceptos']['Int. generados por acreed. vs']['composicion']='periodos';
            $notaPagoIntereses['conceptos']['Int. generados por acreed. vs']['resta']=false;
            $notaPagoIntereses['conceptos']['Int. generados por acreed. vs']['title']='Acredores Varios del Estado de Situacion Patrimonial';
            
            $notaPagoIntereses['conceptos']['Banco 1']['valores']=  $subtotalBancos1Intereses;
            $notaPagoIntereses['conceptos']['Banco 1']['composicion']='periodos';        
            $notaPagoIntereses['conceptos']['Banco 1']['resta']=false;            
            $notaPagoIntereses['conceptos']['Banco 1']['title']='Banco 1 Intereses ';            

            $notaPagoIntereses['conceptos']['Banco 2']['valores']=  $subtotalBancos2Intereses;
            $notaPagoIntereses['conceptos']['Banco 2']['composicion']='periodos';
            $notaPagoIntereses['conceptos']['Banco 2']['resta']=false;            
            $notaPagoIntereses['conceptos']['Banco 2']['title']='Banco 2 Intereses';            
                        
            $notaPagoIntereses['conceptos']['Banco 3']['valores']=  $subtotalBancos3Intereses;
            $notaPagoIntereses['conceptos']['Banco 3']['composicion']='periodos';
            $notaPagoIntereses['conceptos']['Banco 3']['resta']=false;            
            $notaPagoIntereses['conceptos']['Banco 3']['title']='Banco 3 Intereses';            
                        
            $notaPagoIntereses['conceptos']['Banco 4']['valores']=  $subtotalBancos4Intereses;
            $notaPagoIntereses['conceptos']['Banco 4']['composicion']='periodos';
            $notaPagoIntereses['conceptos']['Banco 4']['resta']=false;            
            $notaPagoIntereses['conceptos']['Banco 4']['title']='Banco 4 Intereses';            
                        
            $notaPagoIntereses['conceptos']['Banco 5']['valores']=  $subtotalBancos5Intereses;
            $notaPagoIntereses['conceptos']['Banco 5']['composicion']='periodos';
            $notaPagoIntereses['conceptos']['Banco 5']['resta']=false;            
            $notaPagoIntereses['conceptos']['Banco 5']['title']='Banco 5 Intereses';            
                        
            $notaPagoIntereses['conceptos']['Banco 6']['valores']=  $subtotalBancos6Intereses;
            $notaPagoIntereses['conceptos']['Banco 6']['composicion']='periodos';
            $notaPagoIntereses['conceptos']['Banco 6']['resta']=false;            
            $notaPagoIntereses['conceptos']['Banco 6']['title']='Banco 6 Intereses';            
                        
            $notaPagoIntereses['conceptos']['Banco 7']['valores']=  $subtotalBancos7Intereses;
            $notaPagoIntereses['conceptos']['Banco 7']['composicion']='periodos';
            $notaPagoIntereses['conceptos']['Banco 7']['resta']=false;            
            $notaPagoIntereses['conceptos']['Banco 7']['title']='Banco 7 Intereses';            
                        
            $notaPagoIntereses['conceptos']['Banco 8']['valores']=  $subtotalBancos8Intereses;
            $notaPagoIntereses['conceptos']['Banco 8']['composicion']='periodos';
            $notaPagoIntereses['conceptos']['Banco 8']['resta']=false;            
            $notaPagoIntereses['conceptos']['Banco 8']['title']='Banco 8 Intereses';            
                        
            $notaPagoIntereses['conceptos']['Banco 9']['valores']=  $subtotalBancos9Intereses;
            $notaPagoIntereses['conceptos']['Banco 9']['composicion']='periodos';
            $notaPagoIntereses['conceptos']['Banco 9']['resta']=false;            
            $notaPagoIntereses['conceptos']['Banco 9']['title']='Banco 9 Intereses';            
                        
            $notaPagoIntereses['conceptos']['Banco 10']['valores']=  $subtotalBancos10Intereses;
            $notaPagoIntereses['conceptos']['Banco 10']['composicion']='periodos';
            $notaPagoIntereses['conceptos']['Banco 10']['resta']=false;            
            $notaPagoIntereses['conceptos']['Banco 10']['title']='Banco 10 Intereses';            
                        
            
            
            $notaPagoIntereses['conceptos']['AFIP Intereses Generales']['valores']=  $subtotalAFIP;
            $notaPagoIntereses['conceptos']['AFIP Intereses Generales']['composicion']='periodos';
            $notaPagoIntereses['conceptos']['AFIP Intereses Generales']['resta']=false;            
            $notaPagoIntereses['conceptos']['AFIP Intereses Generales']['title']='Anexo II Organismos Publicos'; 
            
            $notaPagoIntereses['conceptos']['DGR Intereses Generales']['valores']=  $subtotalDGR;
            $notaPagoIntereses['conceptos']['DGR Intereses Generales']['composicion']='periodos';
            $notaPagoIntereses['conceptos']['DGR Intereses Generales']['resta']=false;            
            $notaPagoIntereses['conceptos']['DGR Intereses Generales']['title']='Anexo II Organismos Publicos'; 
            
            $notaPagoIntereses['conceptos']['DGRM Intereses Generales']['valores']=  $subtotalDGRM;
            $notaPagoIntereses['conceptos']['DGRM Intereses Generales']['composicion']='periodos';
            $notaPagoIntereses['conceptos']['DGRM Intereses Generales']['resta']=false;            
            $notaPagoIntereses['conceptos']['DGRM Intereses Generales']['title']='Anexo II Organismos Publicos'; 
                                                          
            mostrarNotaEFE($notaPagoIntereses,$periodoPrevio, $periodoActual,$numeroNota);
            
            //NOTA: Pago de Impusto a las Ganancias
            $notaPagoGanancias = [];
            $notaPagoGanancias['nombreNota']='Pago de Impuesto a las Ganancias';
            $notaPagoGanancias['conceptos']['Imp. a las Ganancias']['valores']=$totalImpuestoALaGanancia;
            $notaPagoGanancias['conceptos']['Imp. a las Ganancias']['composicion']='periodos';
            $notaPagoGanancias['conceptos']['Imp. a las Ganancias']['resta']=false;       
            $notaPagoGanancias['conceptos']['Imp. a las Ganancias']['title']='Impuesto a las ganancias del Estado de Resultado';       
            
            $arrayPrefijos=[];
            $arrayPrefijos[]='506110';
            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',1);             
            $totalImpuestoALaGananciaInicio=[]; 
            $totalImpuestoALaGananciaInicio[$periodoActual]=$subtotalActual*-1;
            $totalImpuestoALaGananciaInicio[$periodoPrevio]=$subtotalPrevio*-1;            
            $notaPagoGanancias['conceptos']['Imp a las Ganancias al Inicio']['valores']=  $totalImpuestoALaGananciaInicio;
            $notaPagoGanancias['conceptos']['Imp a las Ganancias al Inicio']['composicion']='periodos';        
            $notaPagoGanancias['conceptos']['Imp a las Ganancias al Inicio']['title']='Cuenta 506110 en el asiento de apertura'; 
             
            mostrarNotaEFE($notaPagoGanancias,$periodoPrevio, $periodoActual,$numeroNota);
            
            //NOTA: Otros Ingresos
            $notaOtrosCobros = [];
            $notaOtrosCobros['nombreNota']='Otros Cobros';
            $notaOtrosCobros['conceptos']['Otros Ingresos']['valores']=$totalOtrosIngresos;
            $notaOtrosCobros['conceptos']['Otros Ingresos']['composicion']='periodos';
            $notaOtrosCobros['conceptos']['Otros Ingresos']['resta']=false;                          
            $notaOtrosCobros['conceptos']['Otros Ingresos']['title']='Otros Ingresos del Estado de Resultado';                          
            mostrarNotaEFE($notaOtrosCobros,$periodoPrevio, $periodoActual,$numeroNota);
            
            //NOTA: Pago por compra de bienes de usos y activos intangibles
            $notaPagoBienDeuso = [];
            $notaPagoBienDeuso['nombreNota']='Pago por compra de bienes de usos y activos intangibles';
                      
            //NO TENGO PARA EL EJ ANTERIOR DE BDU 
            
            $notaPagoBienDeuso['conceptos']['Alta de Bienes de Uso']['valores']=$totalAltaBDUInicio;
            $notaPagoBienDeuso['conceptos']['Alta de Bienes de Uso']['composicion']='periodos';
            $notaPagoBienDeuso['conceptos']['Alta de Bienes de Uso']['title']='Alta de Bienes de Usos del Anexo de Bienes de Uso del Estadod de Situacion Patrimonial';
            $notaPagoBienDeuso['conceptos']['Alta de Bienes de Uso']['resta']=false;
            
            mostrarNotaEFE($notaPagoBienDeuso,$periodoPrevio, $periodoActual,$numeroNota);             
            
            //NOTA: Pago de Impusto a las Ganancias
            $notaPagoPrestamosFinanciacion = [];
            $notaPagoPrestamosFinanciacion['nombreNota']='Pago de Prestamos';
            
            $arrayPrefijos=[];
            $arrayPrefijos[]='21021';
            $arrayPrefijos[]='21022';
            $arrayPrefijos[]='210230000';
            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',1);             
            $totalPrestamosCorrienteInicio=[]; 
            $totalPrestamosCorrienteInicio[$periodoActual]=$subtotalActual*-1;
            $totalPrestamosCorrienteInicio[$periodoPrevio]=$subtotalPrevio*-1;     
            
            $notaPagoPrestamosFinanciacion['conceptos']['Prestamos Corrientes al Inicio']['valores']=$totalPrestamosCorrienteInicio;
            $notaPagoPrestamosFinanciacion['conceptos']['Prestamos Corrientes al Inicio']['composicion']='periodos';                                               
            $notaPagoPrestamosFinanciacion['conceptos']['Prestamos Corrientes al Inicio']['title']='Cuenta 21021/21022/210230000 en el asiento de apertura'; ;                                               
            $notaPagoPrestamosFinanciacion['conceptos']['Prestamos Corrientes al Cierre']['valores']=$totalPrestamos;
            $notaPagoPrestamosFinanciacion['conceptos']['Prestamos Corrientes al Cierre']['composicion']='corriente';
            $notaPagoPrestamosFinanciacion['conceptos']['Prestamos Corrientes al Cierre']['title']='Prestamos Corrientes del Estado de Situacion Patrimonial';
            $notaPagoPrestamosFinanciacion['conceptos']['Prestamos Corrientes al Cierre']['resta']=false;
            
            $arrayPrefijos=[];
            $arrayPrefijos[]='22022';
            $arrayPrefijos[]='22021';
            $arrayPrefijos[]='220230000';            
            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',1);             
            $totalPrestamosNoCorrienteInicio=[]; 
            $totalPrestamosNoCorrienteInicio[$periodoActual]=$subtotalActual*-1;
            $totalPrestamosNoCorrienteInicio[$periodoPrevio]=$subtotalPrevio*-1;     
            
            $notaPagoPrestamosFinanciacion['conceptos']['Prestamos No Corrientes al inicio']['valores']=$totalPrestamosNoCorrienteInicio;
            $notaPagoPrestamosFinanciacion['conceptos']['Prestamos No Corrientes al inicio']['composicion']='periodos';
            $notaPagoPrestamosFinanciacion['conceptos']['Prestamos No Corrientes al inicio']['title']='Cuenta 22021/22022/220230000 en el asiento de apertura'; ;
                         
            $notaPagoPrestamosFinanciacion['conceptos']['Prestamos No Corrientes al Cierre']['valores']=$totalPrestamos;
            $notaPagoPrestamosFinanciacion['conceptos']['Prestamos No Corrientes al Cierre']['composicion']='nocorriente';
            $notaPagoPrestamosFinanciacion['conceptos']['Prestamos No Corrientes al Cierre']['title']='Prestamos No Corrientes del Estado de Situacion Patrimonial';
            $notaPagoPrestamosFinanciacion['conceptos']['Prestamos No Corrientes al Cierre']['resta']=false;
            
            mostrarNotaEFE($notaPagoPrestamosFinanciacion,$periodoPrevio, $periodoActual,$numeroNota);
            
            //NOTA: Aportes en efectivo de los propietarios
            $notaAportesenEfectivo = [];
            $notaAportesenEfectivo['nombreNota']='Aportes en efectivo de los propietarios';
            
            $arrayPrefijos=[];
            $arrayPrefijos[]='110402001';
            $arrayPrefijos[]='220100';
            $arrayPrefijos[]='220708';
            $arrayPrefijos[]='220709';
            $arrayPrefijos[]='220710';

            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',-1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',-1);        
           
                      
            $totalAportesDeCapital1=[]; 
            $totalAportesDeCapital1[$periodoActual]=-$subtotalActual;
            $totalAportesDeCapital1[$periodoPrevio]=-$subtotalPrevio;     
           
            $subtotalActualCierre1 =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'todos',-1);
            $subtotalPrevioCierre1 =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'todos',-1);      
            
            $totalAportesDeCapitalCierre1=[]; 
            $totalAportesDeCapitalCierre1[$periodoActual]=$subtotalActualCierre1;
            $totalAportesDeCapitalCierre1[$periodoPrevio]=$subtotalPrevioCierre1;                 
            
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402001.' al inicio']['valores']=$totalAportesDeCapital1;
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402001.' al inicio']['composicion']='periodos';            
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402001.' al inicio']['title']='Cuenta 110402001/220100/220708/220709/220710 en el asiento de apertura';
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402001.' al inicio']['resta']=false;
            
            $arrayPrefijos=[];
            $arrayPrefijos[]='110402002';

            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',-1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',-1);        
                      
            $totalAportesDeCapital2=[]; 
            $totalAportesDeCapital2[$periodoActual]=-$subtotalActual;
            $totalAportesDeCapital2[$periodoPrevio]=-$subtotalPrevio;     
            
            $subtotalActualCierre2 =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'todos',-1);
            $subtotalPrevioCierre2 =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'todos',-1);      
            
            $totalAportesDeCapitalCierre2=[]; 
            $totalAportesDeCapitalCierre2[$periodoActual]=$subtotalActualCierre2;
            $totalAportesDeCapitalCierre2[$periodoPrevio]=$subtotalPrevioCierre2;   
            
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402002.' al inicio']['valores']=$totalAportesDeCapital2;
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402002.' al inicio']['composicion']='periodos';            
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402002.' al inicio']['title']='Cuenta 110402002 en el asiento de apertura';
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402002.' al inicio']['resta']=false;
            
            $arrayPrefijos=[];
            $arrayPrefijos[]='110402003';

            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',-1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',-1);        
                      
            $totalAportesDeCapital3=[]; 
            $totalAportesDeCapital3[$periodoActual]=-$subtotalActual;
            $totalAportesDeCapital3[$periodoPrevio]=-$subtotalPrevio;     
            
            $subtotalActualCierre3 =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'todos',-1);
            $subtotalPrevioCierre3 =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'todos',-1);      
            
            $totalAportesDeCapitalCierre3=[]; 
            $totalAportesDeCapitalCierre3[$periodoActual]=$subtotalActualCierre3;
            $totalAportesDeCapitalCierre3[$periodoPrevio]=$subtotalPrevioCierre3;   
            
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402003.' al inicio']['valores']=$totalAportesDeCapital3;
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402003.' al inicio']['composicion']='periodos';            
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402003.' al inicio']['title']='Cuenta 110402003 en el asiento de apertura';
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402003.' al inicio']['resta']=false;
            
            $arrayPrefijos=[];
            $arrayPrefijos[]='110402004';

            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',-1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',-1);        
                      
            $totalAportesDeCapital4=[]; 
            $totalAportesDeCapital4[$periodoActual]=-$subtotalActual;
            $totalAportesDeCapital4[$periodoPrevio]=-$subtotalPrevio;     
            
            $subtotalActualCierre4 =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'todos',-1);
            $subtotalPrevioCierre4 =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'todos',-1);      
            
            $totalAportesDeCapitalCierre4=[]; 
            $totalAportesDeCapitalCierre4[$periodoActual]=$subtotalActualCierre4;
            $totalAportesDeCapitalCierre4[$periodoPrevio]=$subtotalPrevioCierre4;   
            
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402004.' al inicio']['valores']=$totalAportesDeCapital4;
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402004.' al inicio']['composicion']='periodos';            
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402004.' al inicio']['title']='Cuenta 110402004 en el asiento de apertura';
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402004.' al inicio']['resta']=false;
                     
           
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402001.' al cierre']['valores']=$totalAportesDeCapitalCierre1;
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402001.' al cierre']['composicion']='periodos';            
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402001.' al cierre']['title']='Cuenta 110402001/220100/220708/220709/220710 en el Cierre';
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402001.' al cierre']['resta']=false;
            
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402002.' al cierre']['valores']=$totalAportesDeCapitalCierre2;
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402002.' al cierre']['composicion']='periodos';            
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402002.' al cierre']['title']='Cuenta 110402002 en el Cierre';
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402002.' al cierre']['resta']=false;
            
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402003.' al cierre']['valores']=$totalAportesDeCapitalCierre3;
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402003.' al cierre']['composicion']='periodos';            
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402003.' al cierre']['title']='Cuenta 110402003 en el Cierre';
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402003.' al cierre']['resta']=false;
            
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402004.' al cierre']['valores']=$totalAportesDeCapitalCierre4;
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402004.' al cierre']['composicion']='periodos';            
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402004.' al cierre']['title']='Cuenta 110402004 en el Cierre';
            $notaAportesenEfectivo['conceptos'][$nombreCuenta110402004.' al cierre']['resta']=false;
                                                                        
            $notaAportesenEfectivo['conceptos']['Cancelaci&oacute;n de pasivo - Cuentas Particulares de los Socios']['valores']=$totalDeudasComerciales;
            $notaAportesenEfectivo['conceptos']['Cancelaci&oacute;n de pasivo - Cuentas Particulares de los Socios']['composicion']='nocorriente';
            $notaAportesenEfectivo['conceptos']['Cancelaci&oacute;n de pasivo - Cuentas Particulares de los Socios']['title']='Deudas Comerciales No Corrientes del Estado de Situacion Patrimonial';
            
            mostrarNotaEFE($notaAportesenEfectivo,$periodoPrevio, $periodoActual,$numeroNota);      
            
            //NOTA:  Actividades de Financiacion otros pagos
            $notaOtrosPagosFinanciacion = [];
            $notaOtrosPagosFinanciacion['nombreNota']='Otros Pagos';
            $arrayPrefijos=['210701','210702','210703','210704','210705','210707','210708'];

            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',1);        
           
            $totalOtrasDeudasCorrientesInicio=[]; 
            $totalOtrasDeudasCorrientesInicio[$periodoActual]=$subtotalActual;
            $totalOtrasDeudasCorrientesInicio[$periodoPrevio]=$subtotalPrevio;     
            
            $notaOtrosPagosFinanciacion['conceptos']['Otras Deudas Corrientes al Inicio']['valores']=$totalOtrasDeudasCorrientesInicio;
            $notaOtrosPagosFinanciacion['conceptos']['Otras Deudas Corrientes al Inicio']['composicion']='periodos';   
            $notaOtrosPagosFinanciacion['conceptos']['Otras Deudas Corrientes al Inicio']['title']='Cuentas 210701/210702/210703/210704/210705/21070/210707/210708 en el asiento de apertura';  
            $notaOtrosPagosFinanciacion['conceptos']['Otras Deudas Corrientes al Inicio']['resta']=false;
            $notaOtrosPagosFinanciacion['conceptos']['Otras Deudas Corrientes al Cierre']['valores']=$totalotrasdeudas;
            $notaOtrosPagosFinanciacion['conceptos']['Otras Deudas Corrientes al Cierre']['composicion']='corriente';   
            $notaOtrosPagosFinanciacion['conceptos']['Otras Deudas Corrientes al Cierre']['title']='Otras Deudas Corrientes del Estado de Situacion Patrimonial';
            $notaOtrosPagosFinanciacion['conceptos']['Otras Deudas Corrientes al Cierre']['resta']=false;
            
            $arrayPrefijos=['220701','220702','220703','220704','220705','220707','220708',];
            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',1);        
           
            $totalOtrasDeudasNoCorrientesInicio=[]; 
            $totalOtrasDeudasNoCorrientesInicio[$periodoActual]=$subtotalActual;
            $totalOtrasDeudasNoCorrientesInicio[$periodoPrevio]=$subtotalPrevio;     
            
            $notaOtrosPagosFinanciacion['conceptos']['Otras Deudas No Corrientes al Inicio']['valores']=$totalOtrasDeudasNoCorrientesInicio;
            $notaOtrosPagosFinanciacion['conceptos']['Otras Deudas No Corrientes al Inicio']['composicion']='periodos';       
            $notaOtrosPagosFinanciacion['conceptos']['Otras Deudas No Corrientes al Inicio']['title']='Cuentas 220701/220702/220703/220704/220705/22070/210708 en el asiento de apertura';
            $notaOtrosPagosFinanciacion['conceptos']['Otras Deudas No Corrientes al Inicio']['resta']=false;

            $notaOtrosPagosFinanciacion['conceptos']['Otras Deudas No Corrientes al Cierre']['valores']=$totalotrasdeudas;
            $notaOtrosPagosFinanciacion['conceptos']['Otras Deudas No Corrientes al Cierre']['composicion']='nocorriente';       
            $notaOtrosPagosFinanciacion['conceptos']['Otras Deudas No Corrientes al Cierre']['title']='Total Otras Deudas del Estado de Situacion Patrimonial';       
            $notaOtrosPagosFinanciacion['conceptos']['Otras Deudas No Corrientes al Cierre']['resta']=false;
            
            mostrarNotaEFE($notaOtrosPagosFinanciacion,$periodoPrevio, $periodoActual,$numeroNota);    
            
            //NOTA: otros Activos
            $notaOtrosActivos = [];
            $notaOtrosActivos['nombreNota']='Otros Activos';
            
            $arrayPrefijos=[];
            $arrayPrefijos[]='210801';
            $arrayPrefijos[]='210802';
            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',1);        
           
            $totalOtrosActivosCorrientesInicio=[]; 
            $totalOtrosActivosCorrientesInicio[$periodoActual]=$subtotalActual*-1;
            $totalOtrosActivosCorrientesInicio[$periodoPrevio]=$subtotalPrevio*-1;     
            
            $notaOtrosActivos['conceptos']['Otros Activos Corrientes al Inicio']['valores']=$totalOtrosActivosCorrientesInicio;
            $notaOtrosActivos['conceptos']['Otros Activos Corrientes al Inicio']['composicion']='periodos';       
            $notaOtrosActivos['conceptos']['Otros Activos Corrientes al Inicio']['title']='Cuentas 210801/210802 en el asiento de apertura';
            $notaOtrosActivos['conceptos']['Otros Activos Corrientes al Cierre']['valores']=$totalOtrosActivos;
            $notaOtrosActivos['conceptos']['Otros Activos Corrientes al Cierre']['composicion']='corriente';       
            $notaOtrosActivos['conceptos']['Otros Activos Corrientes al Cierre']['title']='Total Otros Activos Corrientes del Estado de Situacion Patrimonial'; 
            
            $arrayPrefijos[]='220801';
            $arrayPrefijos[]='220802';
            $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'apertura',1);
            $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,'apertura',1);        
           
            $totalOtrosActivosNoCorrientesInicio=[]; 
            $totalOtrosActivosNoCorrientesInicio[$periodoActual]=$subtotalActual*-1;
            $totalOtrosActivosNoCorrientesInicio[$periodoPrevio]=$subtotalPrevio*-1;     
            
            $notaOtrosActivos['conceptos']['Otros Activos No Corrientes al Inicio']['valores']=$totalOtrosActivosNoCorrientesInicio;
            $notaOtrosActivos['conceptos']['Otros Activos No Corrientes al Inicio']['composicion']='periodos';       
            $notaOtrosActivos['conceptos']['Otros Activos No Corrientes al Inicio']['title']='Cuentas 220801/220802 en el asiento de apertura';
            $notaOtrosActivos['conceptos']['Otros Activos No Corrientes al Cierre']['valores']=$totalOtrosActivos;
            $notaOtrosActivos['conceptos']['Otros Activos No Corrientes al Cierre']['composicion']='nocorriente';     
            $notaOtrosActivos['conceptos']['Otros Activos No Corrientes al Cierre']['title']='Total Otros Activos No Corrientes del Estado de Situacion Patrimonial'; 
            
            mostrarNotaEFE($notaOtrosActivos,$periodoPrevio, $periodoActual,$numeroNota); 
            
            //NOTA: Aportes en efectivo de los propietarios
            $notaResultadoFinanciero = [];
            $notaResultadoFinanciero['nombreNota']='Resultados financieros y por tenencia generados por el efectivo y sus equivalentes';
                                                
            $notaResultadoFinanciero['conceptos']['Resultados financieros y por tenencia generados por el efectivo y sus equivalentes']['valores']=$totalresultadosfinancieros;
            $notaResultadoFinanciero['conceptos']['Resultados financieros y por tenencia generados por el efectivo y sus equivalentes']['composicion']='periodos';
            $notaResultadoFinanciero['conceptos']['Resultados financieros y por tenencia generados por el efectivo y sus equivalentes']['title']='Resultados financieros y por tenencia del Estado de Resultados';
            $notaResultadoFinanciero['conceptos']['Resultados financieros y por tenencia generados por el efectivo y sus equivalentes']['resta']=false;       
            
            mostrarNotaEFE($notaResultadoFinanciero,$periodoPrevio, $periodoActual,$numeroNota);   
            
            ?>
        </tbody>
    </table>
</div><!--Estado de NOTAS de Flujo de efectivo-->
 
<div class="index estadocontable" id="divContenedorFlujoEfectivo">
    <table class="toExcelTable tbl_border tblEFE" cellspacing="0">
        <thead>
            <tr class="trnoclickeable trTitle">
                <td colspan="17" style="text-align: left;font-weight: bold;border-collapse: collapse;">
                    Estado de Flujo de Efectivo  M&eacute;todo Directo</br>
                    Denominacion: <?php echo $cliente['Cliente']['nombre'];?>
                </td>
            </tr>
            <tr class="trTitle">
                <td colspan="17" style="text-align: center">
                   Estado de Flujo de Efectivo M&eacute;todo Directo por el Ejercicio Anual Finalizado el <?php echo date('d-m-Y', strtotime($fechaFinConsulta));; ?> comparativo con el ejercicio anterior
                </td>
            </tr>   
            <tr>
                <th style="text-align: center"></th>
                <th style="text-align: center;width: 60px;"></th>
                <th style="text-align: center">Actual</th>
                <th style="text-align: center">Anterior</th>
            </tr>
        </thead>
        <tbody>
            <tr class="trTitle">
                <th>Variaciones del Efectivo</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <?php
            $totalEFE=[];
            mostrarRowEFE("Efectivo al Inicio", $notaEfectivoAlInicio, $periodoActual, $periodoPrevio, $totalEFE,1);
            ?>
            <!--<tr>
               <td>Modificacion de Ejercicios Anteriores</td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>-->
            <tr>
               <td>Efectivo Modificado al Inicio del ejercicio</td>
               <td></td>
               <td style="text-align: right"><?php echo number_format($totalEFE[$periodoActual],2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format($totalEFE[$periodoPrevio],2,",",".")?></td>
            </tr>
             <?php
            mostrarRowEFE("Efectivo al Cierre del ejercicio", $notaConciliacionEfectivo, $periodoActual, $periodoPrevio, $totalEFE,-1);
            ?>
            <tr>
               <td><?php 
               echo ((-$notaEfectivoAlInicio[$periodoActual]+$notaConciliacionEfectivo[$periodoActual])>=0)?"Aumento neto del efectivo":"Disminuci&oacute;n neta del efectivo";?></td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(-$notaEfectivoAlInicio[$periodoActual]+$notaConciliacionEfectivo[$periodoActual],2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(-$notaEfectivoAlInicio[$periodoPrevio]+$notaConciliacionEfectivo[$periodoPrevio],2,",",".")?></td>
            </tr>
            <tr class="trTitle">
                <th>Causa de las Variaciones</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr class="trTitle">
                <th>Actividades Operativas</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <?php
            $totalFlujoNeto=[];
            mostrarRowEFE("Cobro por ventas de bienes y servicios", $notaCobroVentas, $periodoActual, $periodoPrevio, $totalFlujoNeto,1);
            mostrarRowEFE("Pago a proveedores de bienes y servicios", $notaPagoaprovedores, $periodoActual, $periodoPrevio, $totalFlujoNeto,1);
            mostrarRowEFE("Pagos al personal y cargas sociales", $notaPagoalpersonalycargassociales, $periodoActual, $periodoPrevio, $totalFlujoNeto,1);
            mostrarRowEFE("Pago de impuestos", $notaPagodeImpuestos, $periodoActual, $periodoPrevio, $totalFlujoNeto,1);
            mostrarRowEFE("Cobro de dividentos", $notaCobroDividendos, $periodoActual, $periodoPrevio, $totalFlujoNeto,1);
            mostrarRowEFE("Pago de dividendos", $notaPagoDividendos, $periodoActual, $periodoPrevio, $totalFlujoNeto,1);
            ?>
            <!--<tr>
               <td>Cobro de intereses</td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>-->
             <?php
            mostrarRowEFE("Pago de intereses", $notaPagoIntereses, $periodoActual, $periodoPrevio, $totalFlujoNeto,1);         
            ?>
            <!--<tr>
                <td>Pagos por compra de acciones o t&iacute;tulos de deuda de negociaci&oacute;n habitual</td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>
            <tr>
               <td>Cobros por compra de acciones o t&iacute;tulos de deuda de negociaci&oacute;n habitual</td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>-->
             <?php
            mostrarRowEFE("Pagos de Impuesto a las Ganancias ", $notaPagoGanancias, $periodoActual, $periodoPrevio, $totalFlujoNeto,1);         
            mostrarRowEFE("Otros Cobros", $notaOtrosCobros, $periodoActual, $periodoPrevio, $totalFlujoNeto,1);         
            ?>
            <!--<tr>
               <td>Otros Pagos</td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>
            <tr>
               <td>Intereses generados por el efectivo y equivalentes de efectivo </td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>
            <tr>
               <td>Diferencias de cambio generadas por el efectivo y equivalentes de efectivo </td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>
            <tr>
               <td>RECPAM generado por el efectivo y equivalentes de efectivo</td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>-->
            <tr>
                <th><?php
                echo ($totalFlujoNeto[$periodoActual]>=0)?"Flujo neto de efectivo generado antes de las actividades operativas extraordinarias":
                        "Flujo neto de efectivo utilizado antes de las actividades operativas extraordinarias";
                ?></th>
                <th></th>
                <th style="text-align: right"><?php echo number_format($totalFlujoNeto[$periodoActual],2,",",".")?></td>
                <th style="text-align: right"><?php echo number_format($totalFlujoNeto[$periodoPrevio],2,",",".")?></td>
            </tr>
            
            <?php //siguiente tabla ?>
            
            <tr class="trTitle">
                <th>
                    Actividades de Inversion
                    <?php $totalActInv=[];?>
                </th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <!--<tr>
               <td>Cobros por venta de bienes de uso</td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>-->
            <?php
            mostrarRowEFE("Pagos por compra de bienes de uso y activos intangibles", $notaPagoBienDeuso, $periodoActual, $periodoPrevio, $totalActInv,1);       
            ?>
            <!--<tr>
                <td>Pagos por compra de compa&ncaron;&iacute;as </td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>
            <tr>
               <td>Cobros de intereses</td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>
             <tr>
               <td>Cobros de dividendos</td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>
             <tr>
               <td>Cobros por colocaciones de inversiones que no son equivalentes de efectivo</td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>
             <tr>
               <td>Pagos por colocaciones de inversiones que no son equivalentes de efectivo</td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>
             <tr>
               <td>Pagos del Impuesto a la Ganancias</td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>
             <tr>
               <td>Otros cobros</td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>
            <tr>
               <td>Otros pagos</td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>-->
            <tr>
                <th><?php
                echo ($totalActInv[$periodoActual]>=0)?"Flujo neto de efectivo generado las actividades de inversi&oacute;n":
                        "Flujo neto de efectivo utilizado en las actividades de inversi&oacute;n";
                ?></th>
                <th></th>
                <th style="text-align: right"><?php echo number_format($totalActInv[$periodoActual],2,",",".")?></td>
                <th style="text-align: right"><?php echo number_format($totalActInv[$periodoPrevio],2,",",".")?></td>
            </tr>             
            <tr class="trTitle">
                 <th>
                    Actividades de Financiacion
                    <?php $totalActFinanciacion=[];?>
                </th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <!--<tr>
               <td>Cobros por la emisi&oacute;n de obligaciones negociables</td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>-->
            <?php
            mostrarRowEFE("Aportes en efectivo de los propietarios", $notaAportesenEfectivo, $periodoActual, $periodoPrevio, $totalActFinanciacion,1);       
            mostrarRowEFE("Pago de prestamos", $notaPagoPrestamosFinanciacion, $periodoActual, $periodoPrevio, $totalActFinanciacion,1);       
            ?>           
            <!--<tr>
               <td>Cobros por prestamos de terceros</td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>
            <tr>
               <td>Pagos de Intereses</td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>
            <tr>
               <td>Pagos de dividendos</td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>
            <tr>
               <td>Pagos del Impuesto a la Ganancias</td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>
            <tr>
               <td>Otros cobros </td>
               <td></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
               <td style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>-->
            <?php
            mostrarRowEFE("Otros Pagos", $notaOtrosPagosFinanciacion, $periodoActual, $periodoPrevio, $totalActFinanciacion,1);       
            ?>
            <tr>
                <th><?php
                echo ($totalActFinanciacion[$periodoActual]>=0)?"Flujo neto de efectivo generado por las actividades de financiaci&oacute;n":
                        "Flujo neto de efectivo utilizado en las actividades de financiaci&oacute;n";
                ?></th>
                <th></th>
                <th style="text-align: right"><?php echo number_format($totalActFinanciacion[$periodoActual],2,",",".")?></td>
                <th style="text-align: right"><?php echo number_format($totalActFinanciacion[$periodoPrevio],2,",",".")?></td>
            </tr>   
            <tr class="trTitle">
                 <th>
                    Resultados financieros y por tenencia generados por el efectivo y sus equivalentes
                </th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <!--<tr>
                <th>Resultados financieros y por tenencia que generan movimientos de fondos</th>
                <th></th>
                <th style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
                <th style="text-align: right"><?php echo number_format(0,2,",",".")?></td>
            </tr>   -->
            <tr>
                <th>Resultados financieros y por tenencia que no generan movimientos de fondos</th>
                <th></th>
                <th style="text-align: right"><?php echo number_format($notaResultadoFinanciero[$periodoActual],2,",",".")?></td>
                <th style="text-align: right"><?php echo number_format($notaResultadoFinanciero[$periodoPrevio],2,",",".")?></td>
            </tr>   
            <tr class="trTitle">
                <th><?php
                
                $aumentoEfeActual=$totalFlujoNeto[$periodoActual]+$totalActInv[$periodoActual]+$totalActFinanciacion[$periodoActual]+$notaResultadoFinanciero[$periodoActual];
                $aumentoEfePrevio=$totalFlujoNeto[$periodoPrevio]+$totalActInv[$periodoPrevio]+$totalActFinanciacion[$periodoPrevio]+$notaResultadoFinanciero[$periodoPrevio];
                echo ($aumentoEfeActual>=0)?"Aumento neto del efectivo":
                        "Disminuci&oacute;n neta del efectivo";
                ?></th>
                <th></th>
                <th style="text-align: right"><?php echo number_format($aumentoEfeActual,2,",",".")?></td>
                <th style="text-align: right"><?php echo number_format($aumentoEfePrevio,2,",",".")?></td>
            </tr>   
        </tbody>
    </table>    
</div><!--Estado de Flujo de efectivo-->
<div class="index estadocontable" id="divAjustes" >    
    <div class="noprint">
        <?php
        $tiposAjuste=[
            'Ajustes Resultado de Fuente Argentina - Aumentan utilidad o disminuyen perdida'=>'Ajustes Resultado de Fuente Argentina - Aumentan utilidad o disminuyen perdida',
            'Ajustes Resultado de Fuente Argentina - Dismunuyen utilidad o aumentan perdida'=>'Ajustes Resultado de Fuente Argentina - Dismunuyen utilidad o aumentan perdida'
            ];
        $conceptosAjuste=[
        	'01'=>'Gastos de Mantenimiento y funcionamiento de  automoviles no deducibles',
                '02'=>'Gastos no deducibles',
                '03'=>'Ases. Tecnico del exterior',
                '04'=>'Honorarios directores',
                '05'=>'Rvas. Matematicas y similares en Compania de Seguros',
                '06'=>'Dif. de Cambio en exceso',
                '07'=>'Inv. de Capital o mejoras permanentes',
                '08'=>'Ajuste por precio de transferencia',
                '09'=>'Impuesto a las ganancias',
                '10'=>'Aj. Disminuyen Queb. por Ventas de Acciones',
                '11'=>'Ajustes que incrementan Utilidades por Venta de Acciones',
                '12'=>'Aj. Que disminuyen Queb. Por Instrum. Finan. Derivados      ',
                '13'=>'Aj. Incrementan utilidades por Instrum. Finan. Derivados',
                '14'=>'Intereses',
                '15'=>'Resutados por exposicion a la inflacion (REI)',
                '16'=>'Perdidas extraordinarias',
                '17'=>'Otros ajustes',
                '18'=>'Ajuste NIIF',
                'Total Amortizaciones y Castigos en exceso'=>[			
                        '19'=>'Bienes Muebles',
                        '20'=>'Bienes Inmuebles',
                        '21'=>'Bienes Intangibles',
                        '22'=>'Bienes que no revisten el caracter de bienes de cambio',
                        '23'=>'Otros (Amortizaciones y Castigos en exceso)',
                ],
                'Total Ajustes por diferencia de valuacion'=>[
                        '24'=>'Leasing Financiero',
                        '25'=>'Acciones',
                        '26'=>'Titulos Publicos',
                        '27'=>'Fondo Comun de Inversion',
                        '28'=>'Bines de Cambio',
                        '29'=>'Otros Ajustes por diferencias de valuacion',
                ],
                'Total Provisiones, Previsiones y reservas no deducibles o deducidas en exceso'=>[
                        '30'=>'Incobrables',
                        '31'=>'Bienes de Cambio (Provis.)',
                        '32'=>'Inversiones ',
                        '33'=>'Otras Previsiones',
                        '34'=>'Provisiones y reservas no deducibles o deducidas en exceso',
                        '35'=>'Total Previsiones no deducibles o deducibles en exceso',
                ],
                'Devoluciones al Aporte a Sociedades de Garantia Reciproca deducido'=>[
                        '36'=>'Importe',
                ],
                '37'=>'Ajustes que aumentan Quebrantos por Instrumentos Financieros Derivados',
                '38'=>'Ajustes que disminuyen Utilidades por Instrumentos Financieros Derivados',
                '39'=>'Agustes que aumentan Quebrantos por Venta de Acciones',
                '40'=>'Ajustes que disminuyen Utilidades por Ventas de Acciones',
                '41'=>'Ajustes correlativos por precio de transferencia',
                '42'=>'Deduccion de Gastos Art. 12 Ley 24196',
                '43'=>'Rentas exentas o no gravadas',
                '44'=>'Resultado por exposicion a la inflacion',
                '45'=>'Reservas matematicas y similares en Compania de Seguros',
                '46'=>'Perdidas extraordinarias',
                '47'=>'Honorarios directores',
                '48'=>'Otros ajustes',
                '49'=>'Ajuste NIIF',
                'Aportes a Instituciones de Capital Emprendedor'=>[
                        '50'=>'Monto del aporte',
                ],
                'Total Amortizaciones y Castigos en Defecto'=>[				
                        '51'=>'Bienes Muebles',
                        '52'=>'Bienes Muebles Amortizacion Acelerada',
                        '53'=>'Bienes que no revisten el caracter de Bienes de Cambio',
                        '54'=>'Bienes Inmateriales',
                        '55'=>'Bienes Inmateriales Amortizacion Acelerada',
                        '56'=>'Otros (Amortizaciones y Castigos por defecto)',
                        '57'=>'Bienes Inmuebles',
                        '58'=>'Bienes Inmuebles Amortizacion Acelerada',
                        '59'=>'% de amortizacion utilizados por ley 24196',
                ],
                'Total Ajustes por diferecnias de valuacion'=>[
                        '60'=>'Leasing Financiero',
                        '61'=>'Titulos Publicos',
                        '62'=>'Fondo Comun de Inversion',
                        '63'=>'Bienes de Cambio',
                        '64'=>'Otros Ajustes por diferencias de valuacion',
                ],
                'Aportes a Sociedades de Garantia Reciproca'=>[
                        '65'=>'Importe del aporte'
                ]
        ];
        $conceptosAjusteValores=[
            '01'=>'Gastos de Mantenimiento y funcionamiento de  automoviles no deducibles',
            '02'=>'Gastos no deducibles',
            '03'=>'Ases. Tecnico del exterior',
            '04'=>'Honorarios directores',
            '05'=>'Rvas. Matematicas y similares en Compania de Seguros',
            '06'=>'Dif. de Cambio en exceso',
            '07'=>'Inv. de Capital o mejoras permanentes',
            '08'=>'Ajuste por precio de transferencia',
            '09'=>'Impuesto a las ganancias',
            '10'=>'Aj. Disminuyen Queb. por Ventas de Acciones',
            '11'=>'Ajustes que incrementan Utilidades por Venta de Acciones',
            '12'=>'Aj. Que disminuyen Queb. Por Instrum. Finan. Derivados      ',
            '13'=>'Aj. Incrementan utilidades por Instrum. Finan. Derivados',
            '14'=>'Intereses',
            '15'=>'Resutados por exposicion a la inflacion (REI)',
            '16'=>'Perdidas extraordinarias',
            '17'=>'Otros ajustes',
            '18'=>'Ajuste NIIF',
            '19'=>'Bienes Muebles',
            '20'=>'Bienes Inmuebles',
            '21'=>'Bienes Intangibles',
            '22'=>'Bienes que no revisten el caracter de bienes de cambio',
            '23'=>'Otros (Amortizaciones y Castigos en exceso)',
            '24'=>'Leasing Financiero',
            '25'=>'Acciones',
            '26'=>'Titulos Publicos',
            '27'=>'Fondo Comun de Inversion',
            '28'=>'Bines de Cambio',
            '29'=>'Otros Ajustes por diferencias de valuacion',
            '30'=>'Incobrables',
            '31'=>'Bienes de Cambio (Provis.)',
            '32'=>'Inversiones ',
            '33'=>'Otras Previsiones',
            '34'=>'Provisiones y reservas no deducibles o deducidas en exceso',
            '35'=>'Total Previsiones no deducibles o deducibles en exceso',
            '36'=>'Importe',
            '37'=>'Ajustes que aumentan Quebrantos por Instrumentos Financieros Derivados',
            '38'=>'Ajustes que disminuyen Utilidades por Instrumentos Financieros Derivados',
            '39'=>'Agustes que aumentan Quebrantos por Venta de Acciones',
            '40'=>'Ajustes que disminuyen Utilidades por Ventas de Acciones',
            '41'=>'Ajustes correlativos por precio de transferencia',
            '42'=>'Deduccion de Gastos Art. 12 Ley 24196',
            '43'=>'Rentas exentas o no gravadas',
            '44'=>'Resultado por exposicion a la inflacion',
            '45'=>'Reservas matematicas y similares en Compania de Seguros',
            '46'=>'Perdidas extraordinarias',
            '47'=>'Honorarios directores',
            '48'=>'Otros ajustes',
            '49'=>'Ajuste NIIF',
            '50'=>'Monto del aporte',
            '51'=>'Bienes Muebles',
            '52'=>'Bienes Muebles Amortizacion Acelerada',
            '53'=>'Bienes que no revisten el caracter de Bienes de Cambio',
            '54'=>'Bienes Inmateriales',
            '55'=>'Bienes Inmateriales Amortizacion Acelerada',
            '56'=>'Otros (Amortizaciones y Castigos por defecto)',
            '57'=>'Bienes Inmuebles',
            '58'=>'Bienes Inmuebles Amortizacion Acelerada',
            '59'=>'% de amortizacion utilizados por ley 24196',
            '60'=>'Leasing Financiero',
            '61'=>'Tiulos Publicos',
            '62'=>'Fondo Comun de Inversion',
            '63'=>'Bienes de Cambio',
            '64'=>'Otros Ajustes por diferencias de valuacio',
            '65'=>'Importe del aporte'
        ];
        echo $this->Form->create('Ajustescontable',['class'=>'formTareaCarga formAsiento','action'=>'add','style'=>' min-width: max-content;']);
        echo $this->Form->input('Ajustescontable.id',['type'=>'hidden']);
        echo $this->Form->input('Ajustescontable.impcli_id',['default'=>$cliente['Impcli'][0]['id'],'type'=>'hidden']);
        echo $this->Form->input('Ajustescontable.periodo',['default'=>$periodoActual,'type'=>'hidden']);
        echo $this->Form->input('Ajustescontable.tipo',['options'=>$tiposAjuste]);
        echo $this->Form->input('Ajustescontable.concepto',['options'=>$conceptosAjuste]);
        echo $this->Form->input('Ajustescontable.monto',[]);
        echo $this->Form->end(__('Guardar')); ?>
    </div>
    <?php
    echo $this->Form->create('Ajustescontable',['class'=>'formTareaCarga formAsiento formAjusteContable','action'=>'edit','style'=>' min-width: max-content;']);
    ?>
    <table class="toExcelTable tbl_border tblEstadoContable splitForPrint">
        <thead>
            <tr>
                <td>
                    Contribuyente <?php echo $cliente["Cliente"]['nombre'] ?>
                    A&ncaron;o Fiscal <?php echo $periodoActual ?>
                </td>               
                <td>
                    Ajustes Contables
                </td>               
            </tr>
        </thead>
        <tbody>    
            <tr class="trTitle">
                <th>
                    Ganancia del ejercicio
                </th>
                <?php
                //le restamos lo calculado de imp a las ganancias del periodo actual por q sino lo tenemos en cuenta 2 veces
                echo '<td  class="numericTD">' .
                        number_format($totalPeriodo[$periodoActual], 2, ",", ".")
                . "</td>";
                ?>
            </tr>
            <tr class="trTitle">
                <td>
                    Ajustes Resultado de Fuente Argentina - Aumentan utilidad o disminuyen p&eacute;rdida
                </td>
                <td></td>               
            </tr>
            <?php 
            $mostrarTotalAumentaUtilidad = false;
            $totalAumentaUtilidad = false;
            $i=0;
            $yaCargueImpALasGana=false;
            foreach ($cliente['Impcli'][0]['Ajustescontable'] as $kaju => $ajustescontable) {
                if($ajustescontable['tipo']!='Ajustes Resultado de Fuente Argentina - Aumentan utilidad o disminuyen perdida'){
                    continue;
                }
                ?>
                <tr>
                    <td>
                        <?php echo $conceptosAjusteValores[$ajustescontable['concepto']];?>
                    </td>
                     <td class="numericTD">
                        <?php 
                        $ajusteImpuestoALaGanancia="";
                        if($ajustescontable['concepto']=='09'){
                            /*Impuesto A las Ganancias*/
                            $yaCargueImpALasGana=true;
                            $ajusteImpuestoALaGanancia="ajusteImpuestoALaGanancia";
                        }
                        echo $this->Form->input('Ajustescontable.'.$i.'.id',['default'=>$ajustescontable['id'],'type'=>'hidden']);
                        echo $this->Form->input('Ajustescontable.'.$i.'.impcli_id',['default'=>$ajustescontable['impcli_id'],'type'=>'hidden']);
                        echo $this->Form->input('Ajustescontable.'.$i.'.periodo',['default'=>$ajustescontable['periodo'],'type'=>'hidden']);
                        echo $this->Form->input('Ajustescontable.'.$i.'.tipo',[
                            'default'=>$ajustescontable['tipo'],
                            'type'=>'hidden']);
                        echo $this->Form->input('Ajustescontable.'.$i.'.concepto',['default'=>$ajustescontable['concepto'],'type'=>'hidden']);
                        echo $this->Form->input('Ajustescontable.'.$i.'.monto',[
                            'class'=>$ajusteImpuestoALaGanancia." noprint",
                            'label'=>number_format($ajustescontable['monto'], 2, ",", "."),
                            'default'=>$ajustescontable['monto']]);
                       $i++;
                        ?>
                    </td> 
                </tr>
                <?php
                $totalAumentaUtilidad+=$ajustescontable['monto'];
                $mostrarTotalAumentaUtilidad = true;
            }
            //si no mostre el ajuste de impuesto a las ganancias vamos a crear uno aca extra para modificarlo por js y darle valor
            if(!$yaCargueImpALasGana){
            ?>
            <tr>
                <td>Impuesto a las ganancias</td>
                 <td class="numericTD">
                    <?php 
                        $peanio = substr($periodo, 3);
                        echo $this->Form->input('Ajustescontable.'.$i.'.id',['type'=>'hidden']);
                        echo $this->Form->input('Ajustescontable.'.$i.'.impcli_id',['default'=>$cliente['Impcli'][0]['id'],'type'=>'hidden']);
                        echo $this->Form->input('Ajustescontable.'.$i.'.periodo',['default'=>$peanio,'type'=>'hidden']);
                        echo $this->Form->input('Ajustescontable.'.$i.'.tipo',['default'=>'Ajustes Resultado de Fuente Argentina - Aumentan utilidad o disminuyen perdida','type'=>'hidden']);
                        echo $this->Form->input('Ajustescontable.'.$i.'.concepto',['default'=>'09','type'=>'hidden']);
                        echo $this->Form->input('Ajustescontable.'.$i.'.monto',[
                            'default'=>0,
                            //'disabled'=>'disabled',
                            'label'=>number_format(0, 2, ",", "."),
                            'title'=>'Este valor se cargara cuando se guarde el asiento a las ganancias',
                            'class'=>'ajusteImpuestoALaGanancia noprint',
                            ]);
                        $i++;                   
                    ?>
                </td> 
            </tr>
            <?php 
            }
            if($mostrarTotalAumentaUtilidad){
                ?>
                <tr class="trTitle">
                    <td>
                        Total
                    </td>
                    <td class="numericTD">
                        <?php echo number_format($totalAumentaUtilidad, 2, ",", ".")?>
                    </td> 
                </tr>
                <?php
            }
            ?>
            <tr class="trTitle">
                <td>
                    Ajustes Resultado de Fuente Argentina - Dismunuyen utilidad o aumentan perdida
                </td>
                <td></td>               
            </tr>
            <?php 
            $mostrarTotalDisminuyeUtilidad = false;
            $totalDisminuyeUtilidad = 0;
            foreach ($cliente['Impcli'][0]['Ajustescontable'] as $kaju => $ajustescontable) {
                
                if($ajustescontable['tipo']!='Ajustes Resultado de Fuente Argentina - Dismunuyen utilidad o aumentan perdida'){
                    continue;
                }
                ?>
                <tr>
                    <td>
                        <?php echo $conceptosAjusteValores[$ajustescontable['concepto']];?>
                    </td>
                    <td class="numericTD">
                        <?php 
                        echo $this->Form->input('Ajustescontable.'.$i.'.id',['default'=>$ajustescontable['id'],'type'=>'hidden']);
                        echo $this->Form->input('Ajustescontable.'.$i.'.impcli_id',['default'=>$ajustescontable['impcli_id'],'type'=>'hidden']);
                        echo $this->Form->input('Ajustescontable.'.$i.'.periodo',['default'=>$ajustescontable['periodo'],'type'=>'hidden']);
                        echo $this->Form->input('Ajustescontable.'.$i.'.tipo',['default'=>$ajustescontable['tipo'],'type'=>'hidden']);
                        echo $this->Form->input('Ajustescontable.'.$i.'.concepto',['default'=>$ajustescontable['concepto'],'type'=>'hidden']);
                        echo $this->Form->input('Ajustescontable.'.$i.'.monto',[
                            'class'=>"noprint",
                            'label'=>number_format($ajustescontable['monto'], 2, ",", "."),
                            'default'=>$ajustescontable['monto']]);
                        $i++;?>
                    </td> 
                </tr>
                <?php
                $totalDisminuyeUtilidad+=$ajustescontable['monto'];
                $mostrarTotalDisminuyeUtilidad = true;
            }
            if($mostrarTotalDisminuyeUtilidad){
                ?>
                <tr class="trTitle">
                    <td>
                        Total
                    </td>
                    <td class="numericTD">
                        <?php echo number_format($totalDisminuyeUtilidad, 2, ",", ".")?>
                    </td> 
                </tr>
                <?php
            }
            $resultadoimpositivo = $totalPeriodo[$periodoActual]+$totalAumentaUtilidad-$totalDisminuyeUtilidad;
            ?>
            <tr class="trTitle">
                <td>
                    Resultado Impositivo
                </td>
                <td class="numericTD">
                    <?php echo number_format($resultadoimpositivo, 2, ",", ".");?>
                </td> 
            </tr>
            <tr class="trTitle">
                <td>
                    Impuesto determinado
                </td>
                <td class="numericTD">
                    <?php 
                    $impuestodeterminadofinal = $resultadoimpositivo*0.35;
                    echo number_format($impuestodeterminadofinal, 2, ",", ".");
                    $arrayPrefijos=[];
                    $arrayPrefijos[]='110403103';
                    $activoimpuestodiferido =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'todos',1);
                     ?>
                </td> 
            </tr>
        </tbody>
    </table>
    <?php  echo $this->Form->submit(__('Guardar Ajustes'),['class'=>'noprint'],['class'=>'noprint']); 
        echo $this->Form->end();
        $resultadoimpositivo = number_format($resultadoimpositivo, 2, ".", "");
        $activoimpuestodiferido = number_format($activoimpuestodiferido, 2, ".", "");
        
        echo $this->Form->input('resultadoimpositivo',['type'=>'hidden','value'=>$resultadoimpositivo]);
        echo $this->Form->input('impuestodeterminadofinal',['type'=>'hidden','value'=>$impuestodeterminadofinal]);
        echo $this->Form->input('activoimpuestodiferido',['type'=>'hidden','value'=>$activoimpuestodiferido]);
        $arrayPrefijos=[];
        $arrayPrefijos[]='110403101';
        $saldolibredisponibilidad =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'todos',1);
        $saldolibredisponibilidad = number_format($saldolibredisponibilidad, 2, ".", "");
        echo $this->Form->input('saldolibredisponibilidad',['type'=>'hidden','value'=>$saldolibredisponibilidad]);
        $arrayPrefijos=[];
        $arrayPrefijos[]='110403105';
        $retenciones =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'todos',1);
        $retenciones = number_format($retenciones, 2, ".", "");
        echo $this->Form->input('retenciones',['type'=>'hidden','value'=>$retenciones]);
        $arrayPrefijos=[];
        $arrayPrefijos[]='110403102';
        $anticiposacomput =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'todos',1);
        $anticiposacomput = number_format($anticiposacomput, 2, ".", "");
        echo $this->Form->input('anticiposacomput',['type'=>'hidden','value'=>$anticiposacomput]);
        $arrayPrefijos=[];
        $arrayPrefijos[]='110403201';
        $igmpsaldoafavor =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'todos',1);
        $igmpsaldoafavor = number_format($igmpsaldoafavor, 2, ".", "");
        echo $this->Form->input('igmpsaldoafavor',['type'=>'hidden','value'=>$igmpsaldoafavor]);
        $arrayPrefijos=[];
        $arrayPrefijos[]='110403202';
        $igmpanticipos =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'todos',1);
        $igmpanticipos = number_format($igmpanticipos, 2, ".", "");
        echo $this->Form->input('igmpanticipos',['type'=>'hidden','value'=>$igmpanticipos]);
        $arrayPrefijos=[];
        $arrayPrefijos[]='110403802';
        $ley25413 =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,'todos',1);
        $ley25413 = number_format($ley25413, 2, ".", "");
        echo $this->Form->input('ley25413',['type'=>'hidden','value'=>$ley25413]);
        
?>

</div>
 <?php
 echo $this->Form->create('liquidaciondetalle',['id'=>'liquidaciondetalle','action' => 'add']);
 ?>
<div class="index noprint estadocontable" id="divAuditor" >    
    <?php
    $id=0;
    $fechainforme= date('d-m-Y');
    $nota1="";
    if(isset($cliente['Impcli'][0]['Liquidaciondetalle'][0])){
        $id=$cliente['Impcli'][0]['Liquidaciondetalle'][0]['id'];
        $fechainforme= date('d-m-Y',strtotime($cliente['Impcli'][0]['Liquidaciondetalle'][0]['fechainforme']));
        $nota1=$cliente['Impcli'][0]['Liquidaciondetalle'][0]['nota1'];
    }
    echo $this->Form->input('id',
        [
            'type'=>"hidden",            
            'value'=>$id,
        ]);
    echo $this->Form->input('periodo',
        [
            'type'=>"hidden",            
            'value'=>$periodoActual,
        ]);
    echo $this->Form->input('impcli_id',
        [
            'type'=>"hidden",            
            'value'=>$cliente['Impcli'][0]['id'],
        ]);
    echo $this->Form->input('fechainforme',
        [
            'type'=>"text",
            'class'=>"datepicker",
            'required'=>"required",
            'readonly'=>"readonly",
            'style'=>"width:120px",
            'onChange'=>"loadInformeAuditor()",
            'label'=>"Fecha Informe Auditor",
            'value'=>$fechainforme,
        ]);
    
   
      echo $this->Form->submit('Guardar');
    ?>
</div>
<div class="index noprint estadocontable" id="divNotasAclaratorias" >    
    <?php
    echo $this->Form->input('nota1',
        [
            'type'=>'textarea',
            'value'=>$nota1,
            'onChange'=>'loadNotasYDatos()'
        ]);
    echo $this->Form->submit('Guardar');
    ?>
</div>
<?php
echo $this->Form->end();
?>
<div class="divFooter" style="/*display:block*/">
    <div style="" class="divToLeft">
        <?php
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        ?>
        <label style='font-size: 8px' id="">Firmado a efectos de su identificacion con mi informe de fecha <span id="spanFechaFooter"> <?php echo date('d')?> del mes de <?php echo $meses[date('n')-1]?> de <?php echo date('Y')?> </span>
            </br><?php
        if(isset($cliente['Personasrelacionada'][0])){
            echo $cliente['Personasrelacionada'][0]['nombre'];
        }else{
            echo "Agregar Contacto del Tipo Socio Gerente";
        }
        ?> </br>
        Socio Gerente</label>
    </div>
    <div style="text-align:center;" class="divToRight" >
        <span style="display:inline-block;vertical-align:middle;line-height:normal;">
            <label style="font-size: 8px"><?php echo $user['User']['nombre']; ?></br>
            Contador Publico Nacional</br>
            Mat.<?php echo $user['User']['matricula']?> T.V. F.<?php echo $user['User']['folio']?> C.P.C.E. Salta</label>
        </span>
    </div>
</div>

</div>
<?php
function showRowAnexoICostos($titulo,$actual,$anterior){
    if($actual==0&&$anterior==0)return;
    ?>
    <tr>
        <td colspan="2">
            <?php echo $titulo?>
        </td>
        <td colspan="2" class="numericTD" style="width: 90px">
        <?php
         echo number_format($actual, 2, ",", ".");
         ?></td>
        <td colspan="2" class="numericTD" style="width: 90px">
        <?php
          echo number_format($anterior, 2, ",", ".");
        ?>
        </td>
    </tr>
    <?php
}
function acumularPrefijos($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$periodoPrevio,$keysCuentas,$tipoAsiento,&$total){
                $subtotalActual =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoActual,$keysCuentas,$tipoAsiento,1);
                $subtotalPrevio =  sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayPrefijos,$periodoPrevio,$keysCuentas,$tipoAsiento,1);
                if(!isset($total[$periodoActual]))$total[$periodoActual]=0;
                if(!isset($total[$periodoPrevio]))$total[$periodoPrevio]=0;
                $total[$periodoActual]=$subtotalActual;
                $total[$periodoPrevio]=$subtotalPrevio;
            }
function mostrarNotaEFE(&$nota,$periodoPrevio, $periodoActual,&$numeroNota){
    $mostrarNota=false;
    if(!isset($nota[$periodoActual]))$nota[$periodoActual]=0;
    if(!isset($nota[$periodoPrevio]))$nota[$periodoPrevio]=0;
    foreach ($nota['conceptos'] as $nombreConcepto => $total) {
        $suma = -1;
        //por defecto le cambio el signo
        if(isset($total['resta'])){
            if(!$total['resta']){
                $suma = 1;
            }
        }
        $title="";
        if(isset($total['title'])){
            $title=$total['title'];
        }
        switch ($total['composicion']){
            case 'periodos':
                if(!isset($total['valores'][$periodoPrevio])){
                    Debugger::dump($nombreConcepto);
                    Debugger::dump($total);
                }
                $valorPrevio = $total['valores'][$periodoPrevio]*$suma;
                $valorActual = $total['valores'][$periodoActual]*$suma;
                break;
            case 'corriente':
                if(!isset($total['valores']['corriente'][$periodoPrevio])){
                    Debugger::dump($nombreConcepto);
                    Debugger::dump($total);
                }
                $valorPrevio = $total['valores']['corriente'][$periodoPrevio]*$suma;
                $valorActual = $total['valores']['corriente'][$periodoActual]*$suma;
                break;
            case 'nocorriente':
                $valorPrevio = $total['valores']['nocorriente'][$periodoPrevio]*$suma;
                $valorActual = $total['valores']['nocorriente'][$periodoActual]*$suma;
                break;
            case 'arrayprefijo':
                foreach ($total['valores']['arrayprefijo'] as $concepto => $values) {
                    $valorPrevio = $values[$periodoPrevio]*$suma;
                    $valorActual = $values[$periodoActual]*$suma;
                    $nota[$periodoPrevio]+=$valorPrevio;
                    $nota[$periodoActual]+=$valorActual;        
                    
                    $nombresubConcepto=$concepto;
                    $title="Cuenta Involucrada: ".$values['prefijocorriente'];
                    if($valorPrevio!=0||$valorActual!=0){
                        if(!$mostrarNota){

                            //es la primera vez que vamos a mostrar la nota asi que vamos a hacer una table y vamos a mostrara la row
                            ?>
                            <tr class="trTitle ">
                                <th colspan="4" class="tdnoborder">Nota <?php echo $numeroNota.": ".$nota['nombreNota'];$nota['numeronota']=$numeroNota;$numeroNota++;?></th>
                            </tr>
                            <tr>
                                <td>Composicion</td>
                                <td class="tdborder " style="text-align: center;width: 80px">Actual</td>
                                <td class="tdborder " style="text-align: center;width: 80px">Anterior</td>
                            </tr>
                            <?php            
                        }
                        $mostrarNota=true;
                        ?>
                        <tr>
                            <td title="<?php echo $title;?>"><?php echo $nombresubConcepto;?></td>
                            <td class="tdborder tdWithNumber " style="width: 80px"><?php echo number_format($valorActual, 2, ",", ".");?></td>
                            <td class="tdborder tdWithNumber" style="width: 80px"><?php echo number_format($valorPrevio, 2, ",", ".");?></td>
                        </tr>
                        <?php    
                    }          
                }
                $valorPrevio = 0;
                $valorActual = 0;
                break;
        }            
        $nota[$periodoPrevio]+=$valorPrevio;
        $nota[$periodoActual]+=$valorActual;        
        if($valorPrevio!=0||$valorActual!=0){
            if(!$mostrarNota){
                
                //es la primera vez que vamos a mostrar la nota asi que vamos a hacer una table y vamos a mostrara la row
                ?>
                <tr class="trTitle">
                    <th colspan="4" class="tdnoborder">Nota <?php echo $numeroNota." : ".$nota['nombreNota'];$nota['numeronota']=$numeroNota;$numeroNota++;?></th>
                </tr>
                <tr>
                    <td>Composicion</td>
                    <td class="tdborder " style="text-align: center;width: 80px">Actual</td>
                    <td class="tdborder " style="text-align: center;width: 80px">Anterior</td>
                </tr>
                <?php            
            }
            $mostrarNota=true;
            ?>
            <tr>
                <td title="<?php echo $title;?>"><?php echo $nombreConcepto;?></td>
                <td class="tdborder tdWithNumber " style="width: 80px"><?php echo number_format($valorActual, 2, ",", ".");?></td>
                <td class="tdborder tdWithNumber" style="width: 80px"><?php echo number_format($valorPrevio, 2, ",", ".");?></td>
            </tr>
            <?php    
        }                
    }    
    if($mostrarNota){
        
        ?>
        <tr>
            <th class="tdnoborder">Total</th>
            <th class="tdWithNumber" style="width: 80px"><?php echo number_format( $nota[$periodoActual], 2, ",", ".");?></td>
            <th class="tdWithNumber" style="width: 80px"><?php echo number_format( $nota[$periodoPrevio], 2, ",", ".");?></td>
        </tr>
        <tr class="spacing">
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <?php
    }
}
function mostrarRowEFE($nombreLinea,$nota,$periodoActual,$periodoPrevio,&$total,$suma){
    //si esta todo en cero no mostremos
    $subtotalactual = isset($nota[$periodoActual])?$nota[$periodoActual]:0;
    $subtotalprevio = isset($nota[$periodoPrevio])?$nota[$periodoPrevio]:0;
    if(!isset($total[$periodoActual])){
                $total[$periodoActual] = 0;//existen estos valores
            }
    if(!isset($total[$periodoPrevio])){
                $total[$periodoPrevio] = 0;//existen estos valores
            }
    if($subtotalactual==0&&$subtotalprevio==0){
        return;
    }
    
    ?>
    <tr>
        <td>            
            <?php
            echo $nombreLinea;
            $numeroDeNota = "";
            if(isset($nota['numeronota'])){
                $numeroDeNota = $nota['numeronota'];//existen estos valores
            }
            ?>
        </td>
        <td>
            <?php echo $numeroDeNota!=""?"Nota: ".$numeroDeNota:""; ?>
        </td>
        <?php
            $total[$periodoActual] += $subtotalactual*$suma;
            echo '<td  class="numericTD">' .
                number_format($subtotalactual*$suma, 2, ",", ".")
                . "</td>";
            if(!isset($total[$periodoPrevio])){
                $total[$periodoPrevio] = 0;//existen estos valores
            }
            $total[$periodoPrevio] += $subtotalprevio*$suma;
            echo '<td  class="numericTD">' .
                number_format($subtotalprevio*$suma, 2, ",", ".")
                . "</td>";
        ?>
    </tr>
    <?php
}
function initializeRubtoEEPN(&$rubro){
    $rubro['capitalsocial']=0;
    $rubro['ajustedelcapital']=0;
    $rubro['aportesirrevocables']=0;
    $rubro['primadeemision']=0;
    $rubro['totalaportedepropietarios']=0;
    $rubro['legal']=0;
    $rubro['otrasreservas']=0;
    $rubro['totaldegananciasreservadas']=0;
    $rubro['podiferenciasdeconversion']=0;
    $rubro['porinstrumentosderivados']=0;
    $rubro['totalresultadosdiferidos']=0;
    $rubro['resultadosnoasignados']=0;
    $rubro['totalresultadosacumulados']=0;
    $rubro['totalactual']=0;
    $rubro['totalanterior']=0;
}
//esta funcion devuelve un solo valor sumando los saldos de las cuentas que vienen en $arrayCuentas para el $periodoparasumar
function sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayCuentas,$periodoparasumar,$keysCuentas,$tipoasiento,$suma=null){
    
    $total = 0;
    $suma = ($suma==null)?1:$suma;
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
function showRowEEPN(&$rowInfo,$title){
    $tieneValor=0;
    
    foreach ($rowInfo as $k => $value) {
        $tieneValor+=$value*1;
    }
    if($tieneValor==0){
        return;
    }
    ?>
    <td>
        <?php echo $title ?>
    </td>
    <td class="tdWithNumber">
        <?php
        echo number_format($rowInfo['capitalsocial'], 2, ",", ".");
        ?>
    </td>
    <td class="tdWithNumber">
        <?php
        echo number_format($rowInfo['ajustedelcapital'], 2, ",", ".");
        ?>
    </td>
    <td class="tdWithNumber">
        <?php
        echo number_format($rowInfo['aportesirrevocables'], 2, ",", ".");
        ?>
    </td>
    <td class="tdWithNumber">
        <?php
        echo number_format($rowInfo['primadeemision'], 2, ",", ".");
        ?>
    </td>
    <td class="tdWithNumber">
        <?php
       $rowInfo['totalaportedepropietarios'] = $rowInfo['capitalsocial'] + $rowInfo['ajustedelcapital'] 
                + $rowInfo['aportesirrevocables'] + $rowInfo['primadeemision'];
        echo number_format($rowInfo['totalaportedepropietarios'], 2, ",", ".");
        ?>
    </td>
     <td class="tdWithNumber">
        <?php
        echo number_format($rowInfo['legal'], 2, ",", ".");
        ?>
    </td>
    <td class="tdWithNumber">
        <?php
        echo number_format($rowInfo['otrasreservas'], 2, ",", ".");
        ?>
    </td>
    <td class="tdWithNumber">
        <?php                
        $rowInfo['totaldegananciasreservadas'] = $rowInfo['legal'] + $rowInfo['otrasreservas'];
        echo number_format($rowInfo['totaldegananciasreservadas'], 2, ",", ".");
        ?>
    </td>
    <td class="tdWithNumber">
        <?php
        echo number_format($rowInfo['podiferenciasdeconversion'], 2, ",", ".");
        ?>
    </td>
    <td class="tdWithNumber">
        <?php
        $rowInfo['totalresultadosdiferidos'] = $rowInfo['podiferenciasdeconversion'];
        echo number_format($rowInfo['totalresultadosdiferidos'], 2, ",", ".");
        ?>
    </td>
    <td class="tdWithNumber">
        <?php
        echo number_format($rowInfo['resultadosnoasignados'], 2, ",", ".");
        ?>
    </td>
    <td class="tdWithNumber">
        <?php
        $rowInfo['totalresultadosacumulados'] = $rowInfo['resultadosnoasignados'] + $rowInfo['totalresultadosdiferidos']
                + $rowInfo['totaldegananciasreservadas'];
        echo number_format($rowInfo['totalresultadosacumulados'], 2, ",", ".");
        ?>
    </td>
    <td class="tdWithNumber">
        <?php
        $rowInfo['totalactual'] = $rowInfo['totalaportedepropietarios'] + $rowInfo['totalresultadosacumulados'];
        echo number_format($rowInfo['totalactual'], 2, ",", ".");
        ?>
    </td>
    <td class="tdWithNumber">
        <?php

        echo number_format($rowInfo['totalanterior'], 2, ",", ".");
        ?>
    </td>
    <?php
}
function showRowESP($nota,$nombreNota,$fechaFinConsulta,&$total,$tipo){

    $periodoPrevio =  date('Y', strtotime($fechaFinConsulta." -1 Years"));
    $periodoActual = date('Y', strtotime($fechaFinConsulta));
    $Actual = isset($nota[$tipo][$periodoActual])?$nota[$tipo][$periodoActual]:0;
    $Previo = isset($nota[$tipo][$periodoPrevio])?$nota[$tipo][$periodoPrevio]:0;
    
    $myRow='<td style="border-right: 1px solid #fff;width:90px;">'.$nombreNota.'</td>';
    $myRow.='<td align="right" style="width:60px;border-left: 1px solid #fff;">';
    $myRow.='<label>Nota: '.$nota['numeronota'].'</label>';
    $myRow.='</td>';
    $myRow.='<td class="numericTD" style="width:90px;">'. number_format($Actual, 2, ",", ".").'</td>';
    $myRow.='<td class="numericTD" style="width:90px;">'.number_format($Previo, 2, ",", ".").'</td>';
    
    if(!isset($total[$periodoActual])){
        $total[$periodoActual]=0;
        $total[$periodoPrevio]=0;
    }
    $total[$periodoActual]+=$Actual;
    $total[$periodoPrevio]+=$Previo;
    return $myRow;
}

function mostrarBienDeUso($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$fechaInicioConsulta,$fechaFinConsulta){
   
    $mostrarTotal = false;
            
    $totalNota = [];
    $totalNota['alinicio'] = 0;
    $totalNota['altas'] = 0;
    $totalNota['transferencias'] = 0;
    $totalNota['bajas'] = 0;
    $totalNota['revaluo'] = 0;
    $totalNota['desvalorizacion'] = 0;
    $totalNota['recuperodesvalorizacion'] = 0;
    $totalNota['alcierre'] = 0;          
    $totalNota['depreciacionalinicio'] = 0;
    $totalNota['depreciacionbajas'] = 0;
    $totalNota['depreciaciondelejercicio'] = 0;
    $totalNota['depreciaciondesvalorizacion'] = 0;
    $totalNota['depreciacionrecuperodesvalorizacion'] = 0;
    $totalNota['depreciacionalcierre'] = 0;
    $totalNota['ejercicioActual'] = 0;
    $totalNota['ejercicioAnterior'] = 0;
    $totalNota['alinicioAnterior'] = 0;
    $totalNota['altasAnterior'] = 0;

    foreach ($arrayPrefijos as $nombreprefijo => $valoresPrefijo) {                   
        $totalPrefijo = [];
        $totalPrefijo['alinicio'] = 0;
        $totalPrefijo['altas'] = 0;
        $totalPrefijo['transferencias'] = 0;
        $totalPrefijo['bajas'] = 0;
        $totalPrefijo['revaluo'] = 0;
        $totalPrefijo['desvalorizacion'] = 0;
        $totalPrefijo['recuperodesvalorizacion'] = 0;
        $totalPrefijo['alcierre'] = 0;          
        $totalPrefijo['depreciacionalinicio'] = 0;
        $totalPrefijo['depreciacionbajas'] = 0;
        $totalPrefijo['depreciaciondelejercicio'] = 0;
        $totalPrefijo['depreciaciondesvalorizacion'] = 0;
        $totalPrefijo['depreciacionrecuperodesvalorizacion'] = 0;
        $totalPrefijo['depreciacionalcierre'] = 0;
        $totalPrefijo['ejercicioActual'] = 0;
        $totalPrefijo['ejercicioAnterior'] = 0;
        $totalPrefijo['alinicioAnterior'] = 0;
        $totalPrefijo['altasAnterior'] = 0;

        $numerofijo = $valoresPrefijo['prefijocorriente'];
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
            <th colspan="1" style="width: 150px;border-right: 2px black solid"><?php echo $nombreprefijo; ?></th>
            <th colspan="1"></th>
            <th colspan="1"></th>
            <th colspan="1"></th>
            <th colspan="1" style="border-right: 2px black solid"></th>
            <th colspan="1"></th>
            <th colspan="1"></th>
            <th colspan="1"></th>
            <th colspan="1" style="border-right: 2px black solid"></th>
            <th colspan="1"></th>
            <th colspan="1"></th>
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
            
            $periodoActual = date('Y', strtotime($fechaFinConsulta));
            $periodoPrevio = date('Y', strtotime($fechaFinConsulta." -1 Years"));
            $valororigen =  $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual];                
            $valororigenAnterior =  $arrayCuentasxPeriodos[$numerodecuenta][$periodoActual];                
            $porcentajeamortizacion =  1;                
            if(isset($arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada'])){
                $amortizacionacumulada =  $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada'];
                $porcentajeamortizacion =  $arrayCuentasxPeriodos[$numerodecuenta]['porcentajeamortizacion'];
                $amortizacionEjercicio =  $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionejercicio'];
                $amortizacionacumuladaAnterior =  $amortizacionacumulada-$amortizacionEjercicio;//le sacamos una amortizacion mas
            }else{
                $amortizacionacumulada =  0;
                $porcentajeamortizacion =  0;
                $amortizacionEjercicio =  0;
                $amortizacionacumuladaAnterior =  0;
            }
            //calcular la amortizacion acumulada en funcion de la alicuota , el periodo y el valor original
            $d1 = new DateTime('01-'.$periodoBDU);
            $d2 = new DateTime($fechaFinConsulta);
            $d3 = new DateTime($fechaFinConsulta." -1 Years");
            $interval = $d2->diff($d1);
            $interval2 = $d3->diff($d1);
            $aniosamortizados = $interval->format('%y')*1 + (($interval->format('%m')*1>0)?1:0);
            $aniosamortizadosAnterior = $interval2->format('%y')*1 + (($interval2->format('%m')*1>0)?1:0);
            $topeAmortizacion = ($porcentajeamortizacion!=0)?(100/$porcentajeamortizacion):1000;
            
            if($aniosamortizados<$topeAmortizacion){
                if(($aniosamortizados)<=1){
                    $amortizacionacumulada = 0;
                }else{
                    $amortizacionacumulada = ($porcentajeamortizacion/100)*$valororigen*($aniosamortizados-1);
                }
                if(($aniosamortizados)==0){
                    $amortizacionEjercicio = 0;
                }else{
                    $amortizacionEjercicio = ($porcentajeamortizacion/100)*$valororigen;
                    //Debugger::dump("aniosamort < tope=>(".$porcentajeamortizacion."/100)*".$valororigen);
                    //Debugger::dump($amortizacionEjercicio);
                }
            }else{
                $amortizacionacumulada = $valororigen;
                $amortizacionEjercicio =  0;
            }    
           
            //si esta echo el asiento entonces la amortizacion acumulada tiene que restar la amortizacion del ejercicio
            $topeAmortizacionAnterior = ($porcentajeamortizacion!=0)?(100/$porcentajeamortizacion):1000;
           
            if($aniosamortizadosAnterior<$topeAmortizacionAnterior){
                if(($aniosamortizadosAnterior)<=1){
                    $amortizacionAcumuladaAnterior = 0;
                }else{
                    $amortizacionAcumuladaAnterior = $valororigen*($aniosamortizadosAnterior-1)*($porcentajeamortizacion/100);
                }
                if(($aniosamortizadosAnterior)==0){
                    $amortizacionEjercicioAnterior = 0;
                }else{
                    $amortizacionEjercicioAnterior = ($porcentajeamortizacion/100)*$valororigen;
                }
            }else{
                $amortizacionAcumuladaAnterior = $valororigen;
                $amortizacionEjercicioAnterior =  0;
            }               
            //si esta echo el asiento entonces la amortizacion acumulada tiene que restar la amortizacion del ejercicio
            //$amortizacionacumulada-=$amortizacionEjercicio;
            //$amortizacionAcumuladaAnterior-=$amortizacionEjercicio;
            
            //aca si esta definido la amortizacion especial
            //vamos a reemplazar todos los calculos por lo que se ha guardado
            $pemes = date('m', strtotime($fechaFinConsulta));
            $peanio = date('Y', strtotime($fechaFinConsulta));
            $peanioAnterior=  date('Y', strtotime($fechaFinConsulta." -1 Years"));
            if(isset($arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'])){
                foreach ($arrayCuentasxPeriodos[$numerodecuenta]['amortizacionespecial'] as $kae => $amortespecial) {
                    if($kae==$peanio){
                        //aca podemos estar seguros q hay una amortizacion esecial para este periodo 
                        $amortizacionEjercicio = $amortespecial['ejercicio'];
                        $amortizacionacumulada = $amortespecial['amortizacion'];
                        //Debugger::dump("alv todo tengo amort esp");
                        //Debugger::dump($amortespecial['ejercicio']);
                    }
                    if($kae==$peanioAnterior){
                        //aca podemos estar seguros q hay una amortizacion esecial para este periodo 
                        $amortizacionEjercicioAnterior = $amortespecial['ejercicio'];
                        $amortizacionacumuladaAnterior = $amortespecial['amortizacion'];
                    }
                }
            }         
            $subtotalAmortizacion = $amortizacionacumulada+$amortizacionEjercicio;
            $subtotalAmortizacionanterior = $amortizacionAcumuladaAnterior+$amortizacionEjercicioAnterior;
            
            $valorresidual = $valororigen - $subtotalAmortizacion;
            $valorresidualAnterior = $valororigenAnterior - $subtotalAmortizacionanterior;
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
            $depreciaciondelejercicio=$amortizacionEjercicio;
            $depreciaciondesvalorizacion=0;//DE DONDE SALE ESTO???
            $depreciacionrecuperodesvalorizacion=0;//DE DONDE SALE ESTO???
            $depreciacionalcierre=$depreciacionalinicio-$depreciacionbajas+$depreciaciondelejercicio;
            
            $ejercicioActual = $alcierre - $depreciacionalcierre;
             
            
            if(($aniosamortizadosAnterior)!=0){
                $alinicioAnterior = ($peanioAnterior==$peanioBDU)?0:$valororigenAnterior;
                $altasAnterior = ($peanioAnterior==$peanioBDU)?$valororigenAnterior:0;
            }else{
                $alinicioAnterior = 0;
                $altasAnterior = 0;
            }
            $bajasAnterior=0;//DE DONDE SALE ESTO???
            $alcierreAnterior= $alinicioAnterior+$altasAnterior-$bajasAnterior;
            
            $depreciacionalinicioAnterior = $amortizacionAcumuladaAnterior;     
            $depreciaciondelejercicioAnterior = $amortizacionEjercicioAnterior;
            $depreciacionalcierreAnterior=$depreciacionalinicioAnterior-$depreciacionbajas+$depreciaciondelejercicioAnterior;
            $ejercicioAnterior = $alcierreAnterior - $depreciacionalcierreAnterior;
          

            $totalPrefijo['alinicio'] += $alinicio;
            $totalPrefijo['altas'] += $altas;
            $totalPrefijo['transferencias'] += $transferencias;
            $totalPrefijo['bajas'] += $bajas;
            $totalPrefijo['revaluo'] += $revaluo;
            $totalPrefijo['desvalorizacion'] += $desvalorizacion;
            $totalPrefijo['recuperodesvalorizacion'] += $recuperodesvalorizacion;
            $totalPrefijo['alcierre'] += $alcierre;          
            $totalPrefijo['depreciacionalinicio'] += $depreciacionalinicio;
            $totalPrefijo['depreciacionbajas'] += $depreciacionbajas;
            $totalPrefijo['depreciaciondelejercicio'] += $depreciaciondelejercicio;
            $totalPrefijo['depreciaciondesvalorizacion'] += $depreciaciondesvalorizacion;
            $totalPrefijo['depreciacionrecuperodesvalorizacion'] += $depreciacionrecuperodesvalorizacion;
            $totalPrefijo['depreciacionalcierre'] += $depreciacionalcierre;
            $totalPrefijo['ejercicioActual'] += $ejercicioActual;
            $totalPrefijo['ejercicioAnterior'] += $ejercicioAnterior;
            $totalPrefijo['alinicioAnterior'] += $alinicioAnterior;
            $totalPrefijo['altasAnterior'] += $altasAnterior;
            
            $totalNota['alinicio'] += $alinicio;
            $totalNota['altas'] += $altas;
            $totalNota['transferencias'] += $transferencias;
            $totalNota['bajas'] += $bajas;
            $totalNota['revaluo'] += $revaluo;
            $totalNota['desvalorizacion'] += $desvalorizacion;
            $totalNota['recuperodesvalorizacion'] += $recuperodesvalorizacion;
            $totalNota['alcierre'] += $alcierre;          
            $totalNota['depreciacionalinicio'] += $depreciacionalinicio;
            $totalNota['depreciacionbajas'] += $depreciacionbajas;
            $totalNota['depreciaciondelejercicio'] += $depreciaciondelejercicio;
            $totalNota['depreciaciondesvalorizacion'] += $depreciaciondesvalorizacion;
            $totalNota['depreciacionrecuperodesvalorizacion'] += $depreciacionrecuperodesvalorizacion;
            $totalNota['depreciacionalcierre'] += $depreciacionalcierre;
            $totalNota['ejercicioActual'] += $ejercicioActual;
            $totalNota['ejercicioAnterior'] += $ejercicioAnterior;
            $totalNota['alinicioAnterior'] += $alinicioAnterior;
            $totalNota['altasAnterior'] += $altasAnterior;
            ?>
            <tr>
                <td style="border-right: 2px black solid"><?php echo $arrayCuentasxPeriodos[$numerodecuenta]['nombrecuenta'];?></td>
                <td class="tdWithNumber"><?php echo number_format($alinicio, 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($altas, 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="display: none"><?php echo number_format($transferencias, 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($bajas, 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="display: none"><?php echo number_format($revaluo, 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="display: none"><?php echo number_format($desvalorizacion, 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="display: none"><?php echo number_format($recuperodesvalorizacion, 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="border-right: 2px black solid"><?php echo number_format($alcierre, 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($depreciacionalinicio, 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($depreciacionbajas, 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($depreciaciondelejercicio, 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="display: none"><?php echo number_format($depreciaciondesvalorizacion, 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="display: none"><?php echo number_format($depreciacionrecuperodesvalorizacion, 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="border-right: 2px black solid"><?php echo number_format($depreciacionalcierre, 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($ejercicioActual, 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($ejercicioAnterior, 2, ",", ".") ?></td>
            </tr>
            <?php
           
        }
         ?>
            <tr>
                <th style="border-right: 2px black solid"> Total <?php echo $nombreprefijo;?></th>
                <td class="tdWithNumber"><?php echo number_format($totalPrefijo['alinicio'], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($totalPrefijo['altas'], 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="display: none"><?php echo number_format($totalPrefijo['transferencias'], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($totalPrefijo['bajas'], 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="display: none"><?php echo number_format($totalPrefijo['revaluo'], 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="display: none"><?php echo number_format($totalPrefijo['desvalorizacion'], 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="display: none"><?php echo number_format($totalPrefijo['recuperodesvalorizacion'], 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="border-right: 2px black solid"><?php echo number_format($totalPrefijo['alcierre'], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($totalPrefijo['depreciacionalinicio'], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($totalPrefijo['depreciacionbajas'], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($totalPrefijo['depreciaciondelejercicio'], 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="display: none"><?php echo number_format($totalPrefijo['depreciaciondesvalorizacion'], 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="display: none"><?php echo number_format($totalPrefijo['depreciacionrecuperodesvalorizacion'], 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="border-right: 2px black solid"><?php echo number_format($totalPrefijo['depreciacionalcierre'], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($totalPrefijo['ejercicioActual'], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($totalPrefijo['ejercicioAnterior'], 2, ",", ".") ?></td>
            </tr>
           <?php
    }
    ?>
            <tr>
                <th style="border-right: 2px black solid">Total Bienes de Uso</th>
                <td class="tdWithNumber"><?php echo number_format($totalNota['alinicio'], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($totalNota['altas'], 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="display: none"><?php echo number_format($totalNota['transferencias'], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($totalNota['bajas'], 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="display: none"><?php echo number_format($totalNota['revaluo'], 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="display: none"><?php echo number_format($totalNota['desvalorizacion'], 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="display: none"><?php echo number_format($totalNota['recuperodesvalorizacion'], 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="border-right: 2px black solid"><?php echo number_format($totalNota['alcierre'], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($totalNota['depreciacionalinicio'], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($totalNota['depreciacionbajas'], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($totalNota['depreciaciondelejercicio'], 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="display: none"><?php echo number_format($totalNota['depreciaciondesvalorizacion'], 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="display: none"><?php echo number_format($totalNota['depreciacionrecuperodesvalorizacion'], 2, ",", ".") ?></td>
                <td class="tdWithNumber" style="border-right: 2px black solid"><?php echo number_format($totalNota['depreciacionalcierre'], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($totalNota['ejercicioActual'], 2, ",", ".") ?></td>
                <td class="tdWithNumber"><?php echo number_format($totalNota['ejercicioAnterior'], 2, ",", ".") ?></td>
            </tr>
           <?php
    return $totalNota;
}
function showRowEERR($totalActual,$totalPrevio,$periodoActual,$periodoPrevio,&$total){
   echo '<td  class="numericTD">' .
   number_format($totalActual, 2, ",", ".")
   . "</td>";
    $total[$periodoActual] += $totalActual;
   echo '<td  class="numericTD">' .
   number_format($totalPrevio, 2, ",", ".")
   . "</td>";
   $total[$periodoPrevio] += $totalPrevio;
}
function mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,&$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta){
    //vamos a extender la funcionalidad de esta funcion para que abarque tmb mostrary no solo calcular
    //$numerofijo = "60101";
    //Una nota puede tener muchos prefijos y vamos a totalizar los prefijos por separado
    //y devolver el total de la nota.
    $mostrarTotal = false;
    $nombreNota = array_values($arrayPrefijos)[0]['nombrenota'][0];;
    $periodoPrevio = date('Y', strtotime($fechaFinConsulta." -1 Years"));
    $periodoActual = date('Y', strtotime($fechaFinConsulta));
    $totalNota = [];
    if(!isset($totalNota[$periodoPrevio])) {
        $totalNota[$periodoPrevio]=0;
        $totalNota[$periodoActual]=0;
    }
    foreach ($arrayPrefijos as $prefijo => $valoresPrefijo) {      
        $totalPrefijo = [];
        $numerofijo = $prefijo;
        $nombrePrefijo = $valoresPrefijo['nombre'][0];
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
                ?>
                <tr class="trnoclickeable trTitle">
                    <th colspan="4" class="tdnoborder ">Nota <?php echo $numeroDeNota; ?>:  <?php echo $nombreNota; ?></th>
                </tr>
                <tr>
                    <td colspan="2" class="tdnoborder">Conceptos</td>
                    <td colspan="2" style="text-align: center" class="tdborder">Corriente</td>
                </tr>
                <tr>
                    <th colspan="2" class="tdnoborder"><?php echo $nombrePrefijo?></th>
                    <td style="text-align: center" class="tdborder">Actual</td>
                    <td style="text-align: center" class="tdborder">Anterior</td>
                </tr>
                <?php
            }
            $mostrarTotal = true;?>
           
            <?php
            $titleRow = "";
            foreach ($indexCuentasNumeroFijo as $index) {
                $numeroCuenta = $keysCuentas[$index];
                $titleRow="Cuentas incluidas en las notas: ".$numeroCuenta."/"
                ?>
                <tr>
                    <td class="tdnoborder" colspan="2" title="<?php echo $titleRow ?>"><?php echo $arrayCuentasxPeriodos[$numeroCuenta]['nombrecuenta'] ?></td>
                <?php
                    if(!isset($totalNota[$periodoActual])) {
                        $totalNota[$periodoActual]=0;
                    }
                    if(!isset($totalNota[$periodoPrevio])) {
                        $totalNota[$periodoActual]=0;
                    }
                    if(!isset($totalPrefijo[$periodoActual])) {
                        $totalPrefijo[$periodoActual]=0;
                    }
                    if(!isset($totalPrefijo[$periodoPrevio])) {
                        $totalPrefijo[$periodoPrevio]=0;
                    }
                    $charinicial = substr($numerofijo, 0, 1);
                    
                    //vamos a cambiar el signo si es Perdida O Ganancia
                    $suma = 1;
                    switch ($charinicial){
                        case "1":
                            $suma = 1;
                            break;
                        case "2":
                             $suma = 1;
                            break;
                        case "3":
                            $suma = 1;
                            break;
                        case "4":
                            $suma = 1;
                            break;
                        case "5":
                            $suma = -1;
                            break;                       
                        case "6":
                            $suma = -1;
                            break;
                    }
                    $subtotal = $arrayCuentasxPeriodos[$numeroCuenta][$periodoActual]*$suma;
                    echo '<td  class="numericTD tdborder" style="width:90px">' .
                        number_format($subtotal, 2, ",", ".")
                        . "</td>";
                    $totalPrefijo[$periodoActual]+=$subtotal;
                    $totalNota[$periodoActual]+=$subtotal;
                    
                    $subtotal = $arrayCuentasxPeriodos[$numeroCuenta][$periodoPrevio]*$suma;
                    echo '<td  class="numericTD tdborder" style="width:90px">' .
                        number_format($subtotal, 2, ",", ".")
                        . "</td>";
                    $totalPrefijo[$periodoPrevio]+=$subtotal;
                    $totalNota[$periodoPrevio]+=$subtotal;
                    ?>
                </tr>
               <?php
            }
            ?>
            <tr>
                <th colspan="2" class="tdnoborder">SubTotal <?php echo $nombrePrefijo ?></th>
                <?php
                echo '<th  class="numericTD">' .
                    number_format($totalPrefijo[$periodoActual], 2, ",", ".")
                    . "</th>";
                echo '<th  class="numericTD">' .
                    number_format($totalPrefijo[$periodoPrevio], 2, ",", ".")
                    . "</th>";
            ?>
            </tr>
            <?php
        }
    }
    if($mostrarTotal){ ?>
    <tr class="trnoclickeable">
        <th colspan="2" class="tdnoborder">Total de  <?php  echo $nombreNota; ?></th>
        <?php
            $totalPeriodo=0;
            $totalPeriodo += isset($totalNota[$periodoActual])?$totalNota[$periodoActual]:0;
	//                $totalPeriodo += isset($totalVentasServicios[v])?$totalVentasServicios[$periodoActual]:0;;
            echo '<th  class="numericTD">' .
                number_format($totalPeriodo, 2, ",", ".")
                . "</th>";
            $totalPeriodo=0;
            $totalPeriodo += isset($totalNota[$periodoPrevio])?$totalNota[$periodoPrevio]:0;
	//                $totalPeriodo += isset($totalVentasServicios[$periodoAImputar])?$totalVentasServicios[$periodoAImputar]:0;;
            echo '<th  class="numericTD">' .
                number_format($totalPeriodo, 2, ",", ".")
                . "</th>";
        ?>
    </tr>
    <tr class="spacing">
        <td></td>
        <td></td>
        <td></td>
    </tr>
        <?php
        $totalNota['numeronota']=$numeroDeNota;
        $numeroDeNota ++;
        /* ?>
        </table>
        </td>
        <?php*/
    }
    return $totalNota;
}
function mostrarNotaDeESP($arrayCuentasxPeriodos,&$arrayPrefijos,$keysCuentas,&$numeroDeNota,$nombreNota,$fechaInicioConsulta,$fechaFinConsulta){
    //vamos a extender la funcionalidad de esta funcion para que abarque tmb mostrary no solo calcular
    //$numerofijo = "60101";
    //Una nota puede tener muchos prefijos y vamos a totalizar los prefijos por separado
    //y devolver el total de la nota.
    /*Estructura de ejemplo
     * $arrayPrefijos['Moneda Extranjera']=[];
     * $arrayPrefijos['Moneda Extranjera']['prefijocorriente']=[];
     * $arrayPrefijos['Moneda Extranjera']['prefijonocorriente']=[];
     * $arrayPrefijos['Moneda Extranjera']['prefijocorriente']['110104']=[];
     * $arrayPrefijos['Moneda Extranjera']['prefijocorriente']['120104']=[];
     * 
     */
    $periodoPrevio = date('Y', strtotime($fechaFinConsulta."-1 Years"));
    $periodoActual =  date('Y', strtotime($fechaFinConsulta));
    
    $mostrarTotal = false;
    $mostrarTotalCorriente = false;
    $mostrarTotalNoCorriente = false;
    
    $totalNota = [];    
    $totalNota['corriente'] = [];
    $totalNota['nocorriente'] = [];
    if(!isset($totalNota['corriente'][$periodoPrevio])) {
        $totalNota['corriente'][$periodoPrevio]=0;
        $totalNota['corriente'][$periodoActual]=0;
        $totalNota['nocorriente'][$periodoPrevio]=0;
        $totalNota['nocorriente'][$periodoActual]=0;
    }
    $totalNota['arrayprefijo']=$arrayPrefijos;
            
    foreach ($arrayPrefijos as $nombreRubro => $valoresPrefijo) {      
        $mostrarCorriente = false;
        $mostrarNoCorriente = false;
        $totalPrefijo = [];
        $totalPrefijo['corriente'] = [];
        $totalPrefijo['nocorriente'] = [];
        
        $totalNota['arrayprefijo'][$nombreRubro][$periodoActual]=0;
        $totalNota['arrayprefijo'][$nombreRubro][$periodoPrevio]=0;

        $nombrePrefijo = $nombreRubro;

        $indexCuentasNumeroFijoCorriente=[];
        if(isset($valoresPrefijo['prefijocorriente'])){
            $mostrarCorriente = true;
            
            $numerofijo = $valoresPrefijo['prefijocorriente'];
            $indexCuentasNumeroFijoCorriente = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
        }
        $indexCuentasNumeroNOCorriente=[];
        if(isset($valoresPrefijo['prefijonocorriente'])){
            $mostrarNoCorriente = true;
            
            $numerofijonocorriente = $valoresPrefijo['prefijonocorriente'];
            $indexCuentasNumeroNOCorriente = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijonocorriente){
                        return (substr( $var, 0, strlen($numerofijonocorriente)  ) == $numerofijonocorriente);
                    }
                )
            );
        }
        /*if($valoresPrefijo['prefijonocorriente']=='210203'){
            Debugger::dump($indexCuentasNumeroFijoCorriente);
            Debugger::dump($indexCuentasNumeroNOCorriente);
        }*/
        /*vamos a ver si hay valores en los indexs encontrados, si no hay 
         * valores no vamos a mostrar esto*/
        $tieneValores=0;
        foreach ($indexCuentasNumeroFijoCorriente as $ki =>$index) {              
            $numeroCuentaCorriente = $keysCuentas[$index];
            if(!isset($arrayCuentasxPeriodos[$numeroCuentaCorriente][$periodoActual])){
                Debugger::dump($arrayCuentasxPeriodos[$numeroCuentaCorriente]);
            }
            $tieneValores+=$arrayCuentasxPeriodos[$numeroCuentaCorriente][$periodoActual];
            $tieneValores+=$arrayCuentasxPeriodos[$numeroCuentaCorriente][$periodoPrevio];
        }
        foreach ($indexCuentasNumeroNOCorriente as $k2 =>$index2) {              
            $numeroCuentaNOCorriente = $keysCuentas[$index2];
            $tieneValores+=$arrayCuentasxPeriodos[$numeroCuentaNOCorriente][$periodoActual];
            $tieneValores+=$arrayCuentasxPeriodos[$numeroCuentaNOCorriente][$periodoPrevio];
        }
       $tieneValores = round($tieneValores, 2);
        if((count($indexCuentasNumeroFijoCorriente)!=0 || count($indexCuentasNumeroNOCorriente)!=0 )&&($tieneValores!=0)){ 
            
            if(!$mostrarTotal){
                ?>
                <tr class="trnoclickeable">
                    <th  colspan="6" class="tdnoborder trTitle">Nota <?php echo $numeroDeNota.": ".$nombreNota; ?></th>
                </tr>
                <tr>
                    <td class="tdnoborder">Conceptos</td>
                    <?php
                    if($mostrarCorriente){
                    ?>
                    <td colspan="2" style="text-align: center" class="tdborder">Corriente</td>
                    <td class="tdnoborder"></td>
                    <?php
                    }
                    if($mostrarNoCorriente){
                    ?>
                    <td colspan="2" style="text-align: center" class="tdborder">No Corriente</td>
                    <?php } ?>
                </tr>
                <tr>
                    <td class="tdnoborder"><?php echo $nombrePrefijo ?></td>
                    <?php
                    if($mostrarCorriente){
                    ?>
                    <td style="text-align: center" class="tdborder">Actual</td>
                    <td style="text-align: center" class="tdborder">Anterior</td>
                    <td class="tdnoborder"></td>
                     <?php
                    }
                    if($mostrarNoCorriente){
                    ?>
                    <td style="text-align: center" class="tdborder">Actual</td>
                    <td style="text-align: center" class="tdborder">Anterior</td>
                     <?php } ?>
                </tr>
                <?php
            }           
            $mostrarTotal = true;
            if(isset($valoresPrefijo['TituloRubro'])){ ?>
                <tr class="trnoclickeable ">
                    <th  colspan="1" class="tdnoborder"><?php echo $valoresPrefijo['TituloRubro']; ?></th>
               
                 <?php
                    if($mostrarCorriente){
                    ?>
                    <td style="text-align: center" class="tdborder"></td>
                    <td style="text-align: center" class="tdborder"></td>
                    <td class="tdnoborder"></td>
                     <?php
                    }
                    if($mostrarNoCorriente){
                    ?>
                    <td style="text-align: center" class="tdborder"></td>
                    <td style="text-align: center" class="tdborder"></td>
                     <?php } ?>
                </tr>
            <?php 
            }
            ?>
            
            <tr class="trnoclickeable">
                <th  colspan="1" class="tdnoborder">  <?php echo $nombrePrefijo; ?></ths>
                    <?php if($mostrarCorriente){
                    ?>
                    <td style="text-align: center" class="tdborder"></td>
                    <td style="text-align: center" class="tdborder"></td>
                    <td class="tdnoborder"></td>
                     <?php
                    }
                    if($mostrarNoCorriente){
                    ?>
                    <td style="text-align: center" class="tdborder"></td>
                    <td style="text-align: center" class="tdborder"></td>
                     <?php } ?>
            </tr>
           
            
            <?php
            if(!isset($totalNota['corriente'][$periodoPrevio])) {
                $totalNota['corriente'][$periodoPrevio]=0;
                $totalNota['corriente'][$periodoActual]=0;
            }
            if(!isset($totalPrefijo['corriente'][$periodoPrevio])) {
                $totalPrefijo['corriente'][$periodoPrevio]=0;
                $totalPrefijo['corriente'][$periodoActual]=0;
            }
            if(!isset($totalNota['nocorriente'][$periodoPrevio])) {
                $totalNota['nocorriente'][$periodoPrevio]=0;
                $totalNota['nocorriente'][$periodoActual]=0;
            }
            if(!isset($totalPrefijo['nocorriente'][$periodoPrevio])) {
                $totalPrefijo['nocorriente'][$periodoPrevio]=0;
                $totalPrefijo['nocorriente'][$periodoActual]=0;
            }

            $titleRow = "";
            $numerosCuentaNoCorrienteYaMostrados=[];
            foreach ($indexCuentasNumeroFijoCorriente as $ki =>$index) {                
                $numeroCuentaCorriente = $keysCuentas[$index];
                    $charinicial = substr($numeroCuentaCorriente, 0, 1);
                    //vamos a cambiar el signo si es Perdida O Ganancia
                    $suma = 1;
                    switch ($charinicial){
                        case "1":
                            $suma = 1;
                            break;
                        case "2":
                             $suma = -1;
                            break;
                        case "3":
                            $suma = -1;
                            break;
                        case "4":
                            $suma = -1;
                            break;
                        case "5":
                            $suma = 1;
                            break;                       
                        case "6":
                            $suma = -1;
                            break;
                    }
                $numeroCuentaNoCorriente = "";
                $titleRow="Cuentas incluidas en las notas: ".$numeroCuentaCorriente;
                
                if($mostrarNoCorriente){
                    //$numeroCuentaNoCorriente = $keysCuentas[$indexCuentasNumeroNOCorriente[$ki]];
                    //vamos a transformar el numero de cuenta corriente en no corriente reemplazando 1104 por 1204
                    
                    //$numeroCuentaNoCorriente = str_replace ("1104","1204",$numeroCuentaCorriente);
                    $numeroCuentaNoCorriente = substr_replace($numeroCuentaCorriente, '2', 1, 1); 
                    //ahora tengo que eliminar de $indexCuentasNumeroNOCorriente esta $numeroCuentaNoCorriente
                    $noCorrienteActual = 0;
                    $noCorrientePrevio = 0;
                    if(isset($arrayCuentasxPeriodos[$numeroCuentaNoCorriente])){
                        $numerosCuentaNoCorrienteYaMostrados[]=$numeroCuentaNoCorriente;
                        $titleRow.="/".$numeroCuentaNoCorriente."/";
                        $noCorrienteActual = $arrayCuentasxPeriodos[$numeroCuentaNoCorriente][$periodoActual]*$suma;
                        $noCorrientePrevio = $arrayCuentasxPeriodos[$numeroCuentaNoCorriente][$periodoPrevio]*$suma;
                    }                   
                }
                ?>
                <tr>
                    <td title="<?php echo $titleRow ?>" style="padding: 1px 10px"><?php echo $arrayCuentasxPeriodos[$numeroCuentaCorriente]['nombrecuenta'] ?></td>
                <?php
                    if($mostrarCorriente){
                        $mostrarTotalCorriente = true;
                        echo '<td  class="numericTD tdborder" style="width:90px" >' .
                            number_format($arrayCuentasxPeriodos[$numeroCuentaCorriente][$periodoActual]*$suma, 2, ",", ".")
                            . "</td>";
                        echo '<td  class="numericTD tdborder" style="width:90px" >' .
                            number_format($arrayCuentasxPeriodos[$numeroCuentaCorriente][$periodoPrevio]*$suma, 2, ",", ".")
                            . "</td>";
                        echo '<td class="tdnoborder"></td>';
                        $totalPrefijo['corriente'][$periodoActual]+=$arrayCuentasxPeriodos[$numeroCuentaCorriente][$periodoActual]*$suma;
                        $totalPrefijo['corriente'][$periodoPrevio]+=$arrayCuentasxPeriodos[$numeroCuentaCorriente][$periodoPrevio]*$suma;
                        $totalNota['corriente'][$periodoActual]+=$arrayCuentasxPeriodos[$numeroCuentaCorriente][$periodoActual]*$suma;
                        $totalNota['corriente'][$periodoPrevio]+=$arrayCuentasxPeriodos[$numeroCuentaCorriente][$periodoPrevio]*$suma;
                        
                        
                    }
                    if($mostrarNoCorriente){
                        $mostrarTotalNoCorriente = true;
                        echo '<td  class="numericTD tdborder" style="width:90px" >' .
                            number_format($noCorrienteActual, 2, ",", ".")
                            . "</td>";
                        echo '<td  class="numericTD tdborder" style="width:90px" >' .
                            number_format($noCorrientePrevio, 2, ",", ".")
                            . "</td>";
                        $totalPrefijo['nocorriente'][$periodoActual]+=$noCorrienteActual;
                        $totalPrefijo['nocorriente'][$periodoPrevio]+=$noCorrientePrevio;                    
                        $totalNota['nocorriente'][$periodoActual]+=$noCorrienteActual;
                        $totalNota['nocorriente'][$periodoPrevio]+=$noCorrientePrevio;
                    }
                 ?>
                </tr>
               <?php
            }
            
            //perfecto ahi mostramos las cuentas corrientes con su espejo no corriente
            //ahora hay que ver si vienen NO corrientes y mostrar con su espejo corriente
            foreach ($indexCuentasNumeroNOCorriente as $kinc =>$indexnc) {
                $numeroCuentaNoCorriente = $keysCuentas[$indexnc];
                $charinicial = substr($numeroCuentaNoCorriente, 0, 1);
                    //vamos a cambiar el signo si es Perdida O Ganancia
                    $suma = 1;
                    switch ($charinicial){
                        case "1":
                            $suma = 1;
                            break;
                        case "2":
                             $suma = -1;
                            break;
                        case "3":
                            $suma = -1;
                            break;
                        case "4":
                            $suma = -1;
                            break;
                        case "5":
                            $suma = 1;
                            break;                       
                        case "6":
                            $suma = -1;
                            break;
                    }
                    
                $titleRow="Cuentas incluidas en las notas: ".$numeroCuentaNoCorriente;
                if(in_array($numeroCuentaNoCorriente, $numerosCuentaNoCorrienteYaMostrados)){                   
                    continue;
                }
                //$numeroCuentaNoCorriente = $keysCuentas[$indexCuentasNumeroNOCorriente[$ki]];
                //vamos a transformar el numero de cuenta no corriente en corriente reemplazando 1204 por 1104 
                $numeroCuentaCorriente = substr_replace($numeroCuentaNoCorriente, '1', 1, 1); 
                //ahora tengo que eliminar de $indexCuentasNumeroNOCorriente esta $numeroCuentaNoCorriente
                $CorrienteActual = 0;
                $CorrientePrevio = 0;
                if(isset($arrayCuentasxPeriodos[$numeroCuentaCorriente])){
                    $titleRow.="/".$numeroCuentaCorriente."/";
                    $CorrienteActual = $arrayCuentasxPeriodos[$numeroCuentaCorriente][$periodoActual]*$suma;
                    $CorrientePrevio = $arrayCuentasxPeriodos[$numeroCuentaCorriente][$periodoPrevio]*$suma;
                }          
                ?>
                <tr>
                    <td title="<?php echo $titleRow ?>"><?php echo $arrayCuentasxPeriodos[$numeroCuentaNoCorriente]['nombrecuenta'] ?></td>
                <?php
                    if($mostrarCorriente){
                        $mostrarTotalCorriente = true;
                        echo '<td  class="numericTD tdborder" style="width:90px" >' .
                            number_format($CorrienteActual, 2, ",", ".")
                            . "</td>";
                        echo '<td  class="numericTD tdborder" style="width:90px" >' .
                            number_format($CorrientePrevio, 2, ",", ".")
                            . "</td>";
                        echo '<td class="tdnoborder"></td>';
                        $totalPrefijo['corriente'][$periodoActual]+=$CorrienteActual;
                        $totalPrefijo['corriente'][$periodoPrevio]+=$CorrientePrevio;
                        $totalNota['corriente'][$periodoActual]+=$CorrienteActual;
                        $totalNota['corriente'][$periodoPrevio]+=$CorrientePrevio;
                    }
                    if($mostrarNoCorriente){
                        $mostrarTotalNoCorriente = true;
                        echo '<td  class="numericTD tdborder" style="width:90px" >' .
                            number_format($arrayCuentasxPeriodos[$numeroCuentaNoCorriente][$periodoActual]*$suma, 2, ",", ".")
                            . "</td>";
                        echo '<td  class="numericTD tdborder" style="width:90px" >' .
                            number_format($arrayCuentasxPeriodos[$numeroCuentaNoCorriente][$periodoPrevio]*$suma, 2, ",", ".")
                            . "</td>";
                        $totalPrefijo['nocorriente'][$periodoActual]+=$arrayCuentasxPeriodos[$numeroCuentaNoCorriente][$periodoActual]*$suma;
                        $totalPrefijo['nocorriente'][$periodoPrevio]+=$arrayCuentasxPeriodos[$numeroCuentaNoCorriente][$periodoPrevio]*$suma;                    
                        $totalNota['nocorriente'][$periodoActual]+=$arrayCuentasxPeriodos[$numeroCuentaNoCorriente][$periodoActual]*$suma;
                        $totalNota['nocorriente'][$periodoPrevio]+=$arrayCuentasxPeriodos[$numeroCuentaNoCorriente][$periodoPrevio]*$suma;
                    }
                 ?>
                </tr>
               <?php
            }
            $totalNota['arrayprefijo'][$nombreRubro][$periodoActual]=$totalPrefijo['corriente'][$periodoActual];
            $totalNota['arrayprefijo'][$nombreRubro][$periodoPrevio]=$totalPrefijo['corriente'][$periodoPrevio];
        }
    }
    if($mostrarTotal){ ?>
        <tr class="trnoclickeable">
            <th class="tdnoborder">Total de  <?php  echo $nombreNota; ?></th>
            <?php
                if($mostrarTotalCorriente){
                    echo '<td  class="numericTD tdborder">' .
                        number_format($totalNota['corriente'][$periodoActual], 2, ",", ".")
                        . "</td>";
                    echo '<td  class="numericTD tdborder">' .
                        number_format($totalNota['corriente'][$periodoPrevio], 2, ",", ".")
                        . "</td>";    
                    echo '<td class="tdnoborder"></td>';

                }else{
                    echo '<td  class="numericTD tdnoborder"></td>';
                    echo '<td  class="numericTD tdnoborder"></td>';    
                    echo '<td class="tdnoborder"></td>';
                }
                if($mostrarTotalNoCorriente){
                    echo '<td  class="numericTD tdborder">' .
                        number_format($totalNota['nocorriente'][$periodoActual], 2, ",", ".")
                        . "</td>";
                    echo '<td  class="numericTD tdborder">' .
                        number_format($totalNota['nocorriente'][$periodoPrevio], 2, ",", ".")
                       . "</td>";            
                }else{
                    echo '<td  class="numericTD tdnoborder"></td>';
                    echo '<td  class="numericTD tdnoborder"></td>';    
                }
                ?>
        </tr>
        <tr>
            <td colspan="6">
                <hr style=" display: none;margin-top: 0.5em;margin-bottom: 0.5em;margin-left: auto;margin-right: auto;border-style: inset;border-width: 1px;">
            </td>
        </tr>
            <?php
            $totalNota['numeronota']=$numeroDeNota;
            $numeroDeNota ++;
    }
    return $totalNota;
}
function mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,&$total,$titulo,$numerocuenta,$fechaInicioConsulta,$fechaFinConsulta){
    $periodoPrevio = date('Y', strtotime($fechaFinConsulta."-1 Years"));
    $periodoActual =  date('Y', strtotime($fechaFinConsulta));
    $totalPeriodo1 = isset($arrayCuentasxPeriodos[$numerocuenta][$periodoActual])?$arrayCuentasxPeriodos[$numerocuenta][$periodoActual]:0;
    $totalPeriodo2 = isset($arrayCuentasxPeriodos[$numerocuenta][$periodoPrevio])?$arrayCuentasxPeriodos[$numerocuenta][$periodoPrevio]:0;
    if(!isset($total[$periodoActual])){
                $total[$periodoActual] = 0;//existen estos valores
            }
    if(!isset($total[$periodoPrevio])){
                $total[$periodoPrevio] = 0;//existen estos valores
            }
    if($totalPeriodo1==0&$totalPeriodo2==0)return;
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
            
            echo '<td colspan="2" class="numericTD" style="width: 90px">' .
                number_format($totalPeriodo2, 2, ",", ".")
                . "</td>";
           
            $total[$periodoPrevio]+=$totalPeriodo2;
        ?>
    </tr>    
    <?php
}
function mostrarNotasDeGastos($arrayCuentasxPeriodos,$nombreNota,$numerofijo,$fechaInicioConsulta,$fechaFinConsulta,&$totalAnexoII){
    $mesAMostrar = date('Y', strtotime($fechaFinConsulta));
    $mesAMostrarPrevio = date('Y', strtotime($mesAMostrar."-01-01 -1 Year"));
    $subtotal = [];
    $subtotal[$mesAMostrar] = 0;
    $subtotal[$mesAMostrarPrevio] = 0;
    $keysCuentas = array_keys($arrayCuentasxPeriodos);
    $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
    if(!isset($totalAnexoII[$mesAMostrar])) {
        $totalAnexoII[$mesAMostrar]=0;
    }
    if(!isset($subtotal[$mesAMostrar])) {
        $subtotal[$mesAMostrar];
    }
     if(!isset($totalAnexoII[$mesAMostrarPrevio])) {
        $totalAnexoII[$mesAMostrarPrevio]=0;
    }
    if(!isset($subtotal[$mesAMostrarPrevio])) {
        $subtotal[$mesAMostrarPrevio];
    }
     if(count($indexCuentas)!=0) {                   
        
    ?>
        <tr class="trTitle">
            <th colspan=""><?php echo $nombreNota?></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <?php
        foreach ($indexCuentas as $index) {
            $numeroCuenta = $keysCuentas[$index];
            echo '<tr>';
            echo '<td title="'.$numerofijo.'" style="padding: 1px 10px">'.$arrayCuentasxPeriodos[$numeroCuenta]['nombrecuenta'].' </td>' ;
            //como son dos estados vamos a mostrar cuando estemos en el ultimo y de ahi para abajo :S
            if(!isset($totalAnexoII[$mesAMostrar])) {
                $totalAnexoII[$mesAMostrar]=0;
            }
            if(!isset($subtotal[$mesAMostrar])) {
                $subtotal[$mesAMostrar];
            }
            
            $charinicial = substr($numerofijo, 0, 1);
            //vamos a cambiar el signo si es Perdida O Ganancia
            $suma = 1;
            switch ($charinicial){
                case "1":
                    $suma = 1;
                    break;
                case "2":
                     $suma = 1;
                    break;
                case "3":
                    $suma = 1;
                    break;
                case "4":
                    $suma = 1;
                    break;
                case "5":
                    $suma = -1;
                    break;                       
                case "6":
                    $suma = -1;
                    break;
            }
            $subtottalPeriodo = $arrayCuentasxPeriodos[$numeroCuenta][$mesAMostrar]*$suma;
            echo '<td  class="numericTD" style="width:70px">' .
                number_format($subtottalPeriodo*0.0, 2, ",", ".")
                . "</td>";
            echo '<td  class="numericTD" style="width:70px">' .
                number_format($subtottalPeriodo*0.25, 2, ",", ".")
                . "</td>";
            echo '<td  class="numericTD" style="width:70px">' .
                number_format($subtottalPeriodo*0.75, 2, ",", ".")
                . "</td>";
            echo '<td  class="numericTD" style="width:70px">' .
                number_format($subtottalPeriodo, 2, ",", ".")
                . "</td>";
            $totalAnexoII[$mesAMostrar]+=$subtottalPeriodo;
            $subtotal[$mesAMostrar]+=$subtottalPeriodo;
            //ahora vamos a mostrar el ano anterior
            $mesAMostrarPrevio = date('Y', strtotime($mesAMostrar."-01-01 -1 Year"));
            if(!isset($totalAnexoII[$mesAMostrarPrevio])) {
                $totalAnexoII[$mesAMostrarPrevio]=0;
            }
            if(!isset($subtotal[$mesAMostrarPrevio])) {
                $subtotal[$mesAMostrarPrevio]=0;
            }
            $subtottalPeriodo = $arrayCuentasxPeriodos[$numeroCuenta][$mesAMostrarPrevio]*$suma;
            $totalAnexoII[$mesAMostrarPrevio]+=$subtottalPeriodo;
            $subtotal[$mesAMostrarPrevio]+=$subtottalPeriodo;
            echo '<td  class="numericTD" style="width:70px">' .
                number_format($subtottalPeriodo, 2, ",", ".")
                . "</td>";
            echo "</tr>";
        }
        ?>
    <?php
     }
     return $subtotal;
}
function mostrarSumaEvolucPatNeto($arrayCuentasxPeriodos,$indexCuentas,$fechaInicioConsulta,$fechaFinConsulta){
    $keysCuentas = array_keys($arrayCuentasxPeriodos);
    $totalNota = [];
    foreach ($indexCuentas as $index) {
        $numeroCuenta = $keysCuentas[$index];
        $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
        while($mesAMostrar<=$fechaFinConsulta) {
            $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
            if(!isset($totalNota[$mesAMostrar])) {
                $totalNota[$mesAMostrar]=0;
            }
            $totalNota[$mesAMostrar]+=$arrayCuentasxPeriodos[$numeroCuenta][$periodoMesAMostrar];
            $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
        }
    }
    return $totalNota;
}
?>
</div>
<!-- Popin Modal para edicion de ventas a utilizar por datatables-->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
		<div class="modal-dialog" style="width:90%;">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<!--						<span aria-hidden="true">&times;</span>-->
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