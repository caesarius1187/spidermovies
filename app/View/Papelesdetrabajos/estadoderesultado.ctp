<?php
echo $this->Html->script('jquery-ui',array('inline'=>false));
echo $this->Html->css('bootstrapmodal');
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
echo $this->Html->script('papelesdetrabajos/estadoderesultado',array('inline'=>false));
echo $this->Html->script('asientos/index',array('inline'=>false));
echo $this->Html->script('jquery.table2excel',array('inline'=>false));

echo $this->Html->script('jquery.dataTables.js',array('inline'=>false));
echo $this->Html->script('dataTables.altEditor.js',array('inline'=>false));
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
?>
<?php
/**
 * Created by PhpStorm.
 * User: caesarius
 * Date: 02/11/2016|
 * Time: 12:15 PM
 */
    $periodoPrevio = date('Y', strtotime($fechaInicioConsulta."-1 Years"));
    $periodoActual =  date('Y', strtotime($fechaInicioConsulta));
?>
<div class="index" style="padding: 0px 1%; margin-bottom: 10px;" id="headerCliente">
    <div style="width:30%; float: left;padding-top:10px">
        Contribuyente: <?php echo $cliente["Cliente"]['nombre'];
        echo $this->Form->input('clientenombre',['type'=>'hidden','value'=>$cliente["Cliente"]['nombre']]);
        echo $this->Form->input('cliid',['type'=>'hidden','value'=>$cliente["Cliente"]['id']]);?>
    </div>
    <div style="width:25%; float: left;padding-top:10px">
        Periodo: <?php echo $periodo;
        echo $this->Form->input('periodo',['type'=>'hidden','value'=>$periodo])?>
    </div>
    <div style="float:right; width:45%">
        <?php
        echo $this->Html->link(
            "Asientos",
            array(
                'controller' => 'asientos',
                'action' => 'index',
                $cliente["Cliente"]['id'],
                $periodo
            ),
            array('class' => 'buttonImpcli',
                'style'=> 'margin-right: 8px;width: initial;'
            )
        );
        echo $this->Html->link(
            "Agregar Asiento",
            array(
            ),
            array('class' => 'buttonImpcli',
                'id'=>'cargarAsiento',
                'style'=> 'margin-right: 8px;width: initial;'
            )
        );
        echo $this->Form->button('Plan de cuentas',
            array('type' => 'button',
                'class' =>"buttonImpcli",
                'style' =>"width: initial;",
                'onClick' => "location.href = serverLayoutURL+'/cuentasclientes/plancuentas/". $cliente["Cliente"]['id']."';"
            )
        );
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
<div style="width:100%; height:30px; margin-left: 10px;"  class="Formhead noExl" >
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
</div>
<div class="index" id="divContenedorBSyS" >
    <?php
    echo "<h2>Balance de Sumas y Saldos</h2>";
    echo "<h3>del periodo  ".$fechaInicioConsulta." hasta ".$fechaFinConsulta."</h3>";
    ?>
    <table id="tblsys"  class="tbl_border tblEstadoContable" cellspacing="0">
        <thead>
            <tr class="trnoclickeable">
                <td>N&uacute;mero</td>
                <td>Cuenta</td>
                <td>Saldo Anterior</td>
                <td>Saldo Actual</td>
            </tr>
        </thead>
        <tbody>
        <?php
        //$arrayTotales=[];
        $arrayCuentasxPeriodos=[];/*En este array vamos a guardar los valores de cada cuenta
        con su periodo(asociado el valor al numero de cuenta)*/        
        foreach ($cliente['Cuentascliente'] as $cuentascliente){
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
                if($movimiento['Asiento']['fecha']<$fechaInicioConsulta){
                    $periodoAImputar = date('Y', strtotime($fechaInicioConsulta."-1 Years"));
                }else{
                    $periodoAImputar = date('Y', strtotime($fechaInicioConsulta));
                }
                if(!isset($arrayPeriodos[$periodoAImputar])){
                    $arrayPeriodos[$periodoAImputar]=[];
                    $arrayPeriodos[$periodoAImputar]['debes']=0;
                    $arrayPeriodos[$periodoAImputar]['haberes']=0;
                }
                if(!isset($arrayCuentasxPeriodos[$numerodecuenta])){
                    $arrayCuentasxPeriodos[$numerodecuenta] = [];
                    $arrayCuentasxPeriodos[$numerodecuenta]['nombrecuenta'] = $cuentascliente['nombre'];
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
            <tr class="trclickeable" cuecliid="<?php echo $cuentascliente['id']?>" style="background-color: <?php echo $colorTR?>">
                <td>
                    <?php echo $cuentascliente['Cuenta']['numero']; ?>
                </td>
                <td>
                    <?php echo $cuentascliente['nombre']; ?>
                </td>
                <?php
                $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
                while($periodoAMostrar<=$fechaFinConsulta){
                    $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                    if(!isset($arrayPeriodos[$periodoAImputar])){
                        $arrayPeriodos[$periodoAImputar]=[];
                        $arrayPeriodos[$periodoAImputar]['debes']=0;
                        $arrayPeriodos[$periodoAImputar]['haberes']=0;
                    }
                    $saldo = $arrayPeriodos[$periodoAImputar]['debes']-$arrayPeriodos[$periodoAImputar]['haberes'];
                    echo '<td  class="numericTD">'.
                        number_format($saldo, 2, ",", ".")
                        ."</td>";
                    if(!isset($arrayCuentasxPeriodos[$numerodecuenta][$periodoAImputar])){
                        $arrayCuentasxPeriodos[$numerodecuenta][$periodoAImputar]=0;
                    }
                    $arrayCuentasxPeriodos[$numerodecuenta][$periodoAImputar]=$saldo;
                    $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar." +1 Year"));
                }
                //aca le vamos a agregar los datos del bien de uso al $arrayCuentasxPeriodo porque si esta cuenta apunta a un bien de uso 
                //desues vamos a necesitar los datos del BDU para mostrarlos
                //aunque si no esta relacionado a un BDU entonces vamos a mostrar loq ue se pueda y hacer los calculos q se puedan
                if(count($cuentascliente['Cuentaclientevalororigen'])>0){
                    $arrayCuentasxPeriodos[$numerodecuenta]['valororigen']=$cuentascliente['Cuentaclientevalororigen']['valororiginal'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['porcentajeamortizacion']=$cuentascliente['Cuentaclientevalororigen']['porcentajeamortizacion'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada']=$cuentascliente['Cuentaclientevalororigen']['amortizacionacumulada'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['periodo']=$cuentascliente['Cuentaclientevalororigen']['periodo'];                
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionejercicio']=$cuentascliente['Cuentaclientevalororigen']['importeamorteizaciondelperiodo']+
                            $cuentascliente['Cuentaclientevalororigen']['importeamortizacionaceleradadelperiodo'];                
                }
                if(count($cuentascliente['Cuentaclienteactualizacion'])>0){
                    $arrayCuentasxPeriodos[$numerodecuenta]['valororigen']=$cuentascliente['Cuentaclienteactualizacion']['valororiginal'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['porcentajeamortizacion']=$cuentascliente['Cuentaclienteactualizacion']['porcentajeamortizacion'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada']=$cuentascliente['Cuentaclienteactualizacion']['amortizacionacumulada'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['periodo']=$cuentascliente['Cuentaclienteactualizacion']['periodo'];                
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionejercicio']=$cuentascliente['Cuentaclienteactualizacion']['importeamorteizaciondelperiodo']+
                            $cuentascliente['Cuentaclienteactualizacion']['importeamortizacionaceleradadelperiodo'];                
                }
                if(count($cuentascliente['Cuentaclienteterreno'])>0){
                    $arrayCuentasxPeriodos[$numerodecuenta]['valororigen']=$cuentascliente['Cuentaclienteterreno']['valororiginal'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['porcentajeamortizacion']=$cuentascliente['Cuentaclienteterreno']['porcentajeamortizacion'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada']=$cuentascliente['Cuentaclienteterreno']['amortizacionacumulada'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['periodo']=$cuentascliente['Cuentaclienteterreno']['periodo'];                
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionejercicio']=$cuentascliente['Cuentaclienteterreno']['importeamorteizaciondelperiodo']+
                            $cuentascliente['Cuentaclienteterreno']['importeamortizacionaceleradadelperiodo'];                
                }
                if(count($cuentascliente['Cuentaclienteedificacion'])>0){
                    $arrayCuentasxPeriodos[$numerodecuenta]['valororigen']=$cuentascliente['Cuentaclienteedificacion']['valororiginal'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['porcentajeamortizacion']=$cuentascliente['Cuentaclienteedificacion']['porcentajeamortizacion'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada']=$cuentascliente['Cuentaclienteedificacion']['amortizacionacumulada'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['periodo']=$cuentascliente['Cuentaclienteedificacion']['periodo'];                
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionejercicio']=$cuentascliente['Cuentaclienteedificacion']['importeamorteizaciondelperiodo']+
                            $cuentascliente['Cuentaclienteedificacion']['importeamortizacionaceleradadelperiodo'];                
                }
                if(count($cuentascliente['Cuentaclientemejora'])>0){
                    $arrayCuentasxPeriodos[$numerodecuenta]['valororigen']=$cuentascliente['Cuentaclientemejora']['valororiginal'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['porcentajeamortizacion']=$cuentascliente['Cuentaclientemejora']['porcentajeamortizacion'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionacumulada']=$cuentascliente['Cuentaclientemejora']['amortizacionacumulada'];
                    $arrayCuentasxPeriodos[$numerodecuenta]['periodo']=$cuentascliente['Cuentaclientemejora']['periodo'];                
                    $arrayCuentasxPeriodos[$numerodecuenta]['amortizacionejercicio']=$cuentascliente['Cuentaclientemejora']['importeamorteizaciondelperiodo']+
                            $cuentascliente['Cuentaclientemejora']['importeamortizacionaceleradadelperiodo'];                
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
                <?php
                $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta."-1 Years"));
                while($periodoAMostrar<$fechaFinConsulta){
                    $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                    echo "<td >".$periodoAImputar."</td>";
                    $periodoAMostrar = date('Y', strtotime($periodoAMostrar." +1 Year"));
                }
                ?>
            </tr>
        </tfoot>
    </table>
</div>
<?php
$keysCuentas = array_keys($arrayCuentasxPeriodos);
?>
<div class="index" id="divContenedorNotas">
    
    <h2>Notas del Estado de Resultado</h2>
    
    <table id="tblnotas"  class="tbl_border tblEstadoContable" cellspacing="0" style="">
        <thead>
        <tr class="trnoclickeable">
            <th colspan="20" >
                <h3>Notas a los Estados Contables al 31/12/2016 comparativo con el Ejercicio Anterior</h3>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        $numeroDeNota = 1;
        $totalVentasBienes = [];
        ?>
        <tr class="trnoclickeable">
                <?php
                $arrayPrefijos=[];
                $arrayPrefijos['601010']=[];
                $arrayPrefijos['601010']['nombre']=['Ventas de Bienes'];
                $arrayPrefijos['601010']['nombrenota']=['Ventas de Bienes y Servicios'];
                /*
                 * FALTAN LOS SERVICIOS
                 * $arrayPrefijos['60101']=[];
                $arrayPrefijos['60101']['nombre']=['Ventas de Bienes'];
                $arrayPrefijos['60101']['nombrenota']=['Ventas de Bienes y Servicios'];*/
                $totalVentasBienes = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta);
                ?>
        </tr>
        <?php
        $totalReintegros = [];
        ?>
        <tr class="trnoclickeable">
                <?php
                $arrayPrefijos=[];
                $arrayPrefijos['601012']=[];
                $arrayPrefijos['601012']['nombre']=['Reintegros'];
                $arrayPrefijos['601012']['nombrenota']=['Reintegros'];
                $totalReintegros = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta);
                ?>
        </tr>
        <?php
        $numerofijo = "601013";
        $totalDesgravaciones = [];
        ?>
       <tr class="trnoclickeable">
                <?php
                $arrayPrefijos=[];
                $arrayPrefijos['601013']=[];
                $arrayPrefijos['601013']['nombre']=['Desgravaciones'];
                $arrayPrefijos['601013']['nombrenota']=['Desgravaciones'];
                
                $totalDesgravaciones = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta);
                ?>
        </tr>
        <?php
        $numerofijo = "601014";
        $totalProduccionagropecuaria = [];
        ?>
        <tr class="trnoclickeable">
                <?php
                $arrayPrefijos=[];
                $arrayPrefijos['601014']=[];
                $arrayPrefijos['601014']['nombre']=['Resultado neto por produccion agropecuaria'];
                $arrayPrefijos['601014']['nombrenota']=['Resultado neto por produccion agropecuaria'];
                
                $totalProduccionagropecuaria = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta);
                ?>
        </tr>
        <?php
        $numerofijo = "601015";
        $totalValuacionbienesdecambio = [];
        ?>
        <tr class="trnoclickeable">
                <?php
                $arrayPrefijos=[];
                $arrayPrefijos['601015']=[];
                $arrayPrefijos['601015']['nombre']=['Resultado por valuacion de bienes de cambio al VNR'];
                $arrayPrefijos['601015']['nombrenota']=['Resultado por valuacion de bienes de cambio al VNR'];
                
                $totalValuacionbienesdecambio = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta);
                ?>
        </tr>
       <?php
        $numerofijo = "504990";
        $totalOtrosGastos = [];
        ?>
        <tr class="trnoclickeable">
                <?php
                $arrayPrefijos=[];
                $arrayPrefijos['504990']=[];
                $arrayPrefijos['504990']['nombre']=['Otros Gastos'];
                $arrayPrefijos['504990']['nombrenota']=['Otros Gastos'];
                
                $totalOtrosGastos = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta);
                ?>
        </tr>
        <?php
        $numerofijo = "601016";
        $totalinversionesenentes = [];
        ?>
        <tr class="trnoclickeable">
                <?php
                $arrayPrefijos=[];
                $arrayPrefijos['601016']=[];
                $arrayPrefijos['601016']['nombre']=['Resultado de inversiones en entes relacionados'];
                $arrayPrefijos['601016']['nombrenota']=['Resultado de inversiones en entes relacionados'];
                
                $totalinversionesenentes = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta);
                ?>
        </tr>
       
        <?php
        $numerofijo = "601017";
        $totalProduccionagropecuaria = [];
        ?>
        <tr class="trnoclickeable">
                <?php
                $arrayPrefijos=[];
                $arrayPrefijos['601017']=[];
                $arrayPrefijos['601017']['nombre']=['Resultados financieros y por tenencia'];
                $arrayPrefijos['601017']['nombrenota']=['Resultados financieros y por tenencia'];
                
                $totalresultadosfinancieros = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta);
                ?>
        </tr>
        <?php
        $numerofijo = "601017";
        $totalInteresdelcapitalpropio = [];
        ?>
        <tr class="trnoclickeable">
                <?php
                $arrayPrefijos=[];
                $arrayPrefijos['601018']=[];
                $arrayPrefijos['601018']['nombre']=['Interés del capital propio'];
                $arrayPrefijos['601018']['nombrenota']=['Interés del capital propio'];
                
                $totalInteresdelcapitalpropio = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta);
                ?>
        </tr>
        <?php
        $totalOtrosIngresos = [];
        ?>
        <tr class="trnoclickeable">
                <?php
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
                ?>
        </tr>
        <?php
        $numerofijo = "506110";
        $totalImpuestoALaGanancia = [];
        ?>
        <tr class="trnoclickeable">
                <?php
                $arrayPrefijos=[];
                $arrayPrefijos['506110']=[];
                $arrayPrefijos['506110']['nombre']=['Impuesto a las ganancias'];
                $arrayPrefijos['506110']['nombrenota']=['Impuesto a las ganancias'];
                
                $totalImpuestoALaGanancia = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta);
                ?>
        </tr>
        </tbody>
        <tfoot>
        </tfoot>
    </table>
</div>
<div class="index" id="divContenedorAnexos">
    <?php
    echo "<h2>Anexos</h2>";
    $totalAnexoII = [];
    ?>
    <div id="AnexoI" class="index" style="">
        <table id="tblAnexoI"  class="tbl_border tblEstadoContable" cellspacing="0">
            <thead>
                <tr>
                    <th colspan="20">
                        <h3>Anexo I: Costo de los Bienes Vendidos, Servicios Prestados y de Producción al
                        31/12/2016  comparativo con el Ejercicio Anterior</h3>
                    </th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <th>
                    Descripción Actividad
                </th>
                <th>
                    Anterior
                </th>
                <th>
                    Actual
                </th>
            </tr>
            <tr>
                <th colspan="20" style="background-color: #91a7ff">
                    Existencia Inicial
                </th>

            </tr>
            <tr>
                <td>
                    Mercaderías
                </td>
                <?php
                //aca vamos a buscar los valores de la cuenta 	110500011 Mercaderia Inicial que esten en un asiento de apertura
                //capas que no sea necesario usar el asiento de apertura por que
                $totalPeriodoExistenciaInicial=[];
                if(!isset($totalPeriodoExistenciaInicial[$periodoActual])){
                                    $totalPeriodoExistenciaInicial[$periodoActual] = 0;//existen estos valores
                                }
                //existencia final del periodo anterior es la inicial de Actual
                //110500013 Mercaderías XX E Final
                //110502013 Prod. Terminado XX E Final
                //110504013 Prod. en Proceso XX E Final
                //110506013 MP y Materiales XX EFIN
                //110507013 Otros Bienes de Cambio EFin*/
                $existenciaInicialMercaderias = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110500013"],$periodoPrevio,$keysCuentas);
                $existenciaInicialProdTerminado = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110502013"],$periodoPrevio,$keysCuentas);
                $existenciaInicialProdEnProc = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110504013"],$periodoPrevio,$keysCuentas);
                $existenciaInicialMpMaterials = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110506013"],$periodoPrevio,$keysCuentas);
                $existenciaInicialOtros = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110507013"],$periodoPrevio,$keysCuentas);
                $totalPeriodoExistenciaInicial[$periodoActual] += $existenciaInicialMercaderias + $existenciaInicialProdTerminado + $existenciaInicialProdEnProc
                        + $existenciaInicialMpMaterials + $existenciaInicialOtros;
                if(!isset($totalPeriodoExistenciaInicial[$periodoPrevio])){
                                    $totalPeriodoExistenciaInicial[$periodoPrevio] = 0;//existen estos valores
                                } /*existencia inicial del periodo anterior
                  * 110500011 Mercaderías XX E Inicial
                110502011 Prod. Terminado XX E Inicial
                110504011 Prod. en Proceso XX E Inicial
                110506011 MP y Materiales XX E Inicial
                110507011 Otros Bienes de Cambio E Inicial*/
                 $existenciaInicialMercaderiasAnterior = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110500011"],$periodoPrevio,$keysCuentas);
                $existenciaInicialProdTerminadoAnterior = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110502011"],$periodoPrevio,$keysCuentas);
                $existenciaInicialProdEnProcAnterior = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110504011"],$periodoPrevio,$keysCuentas);
                $existenciaInicialMpMaterialsAnterior = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110506011"],$periodoPrevio,$keysCuentas);
                $existenciaInicialOtrosAnterior = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110507011"],$periodoPrevio,$keysCuentas);
                $totalPeriodoExistenciaInicial[$periodoPrevio] += $existenciaInicialMercaderiasAnterior + $existenciaInicialProdTerminadoAnterior 
                        + $existenciaInicialProdEnProcAnterior + $existenciaInicialMpMaterialsAnterior + $existenciaInicialOtrosAnterior;
                     echo '<td  class="numericTD">' .
                        number_format($existenciaInicialMercaderiasAnterior, 2, ",", ".")
                        . "</td>";
                    echo '<td  class="numericTD">' .
                        number_format($existenciaInicialMercaderias, 2, ",", ".")
                        . "</td>";
                    
                ?>
            </tr>
            <tr>
                <td>
                    Productos Terminados
                </td>
                <?php
                
                    echo '<td  class="numericTD">' .
                        number_format($existenciaInicialProdTerminadoAnterior, 2, ",", ".")
                        . "</td>";
                    echo '<td  class="numericTD">' .
                        number_format($existenciaInicialProdTerminado, 2, ",", ".")
                        . "</td>";
                ?>
            </tr>
            <tr>
                <td>
                    Producción en Proces
                </td>
                 <?php
               
                    echo '<td  class="numericTD">' .
                        number_format($existenciaInicialProdEnProcAnterior, 2, ",", ".")
                        . "</td>";
                     echo '<td  class="numericTD">' .
                        number_format($existenciaInicialProdEnProc, 2, ",", ".")
                        . "</td>";
                ?>
            </tr>
            <tr>
                <td>
                    Materias Primas e Insumos incorporados a la producción
                </td>
                <?php
               
                    echo '<td  class="numericTD">' .
                        number_format($existenciaInicialMpMaterials, 2, ",", ".")
                        . "</td>";
                     echo '<td  class="numericTD">' .
                        number_format($existenciaInicialMpMaterialsAnterior, 2, ",", ".")
                        . "</td>";
                ?>
            </tr>
            <?php
                //mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoExistenciaInicial,"Productos Terminados","110502011",$fechaInicioConsulta,$fechaFinConsulta);
               // mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoExistenciaInicial,"Producción en Proceso","110504011",$fechaInicioConsulta,$fechaFinConsulta);
                //mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoExistenciaInicial,"Materias Primas e Insumos incorporados a la producción","110506011",$fechaInicioConsulta,$fechaFinConsulta);
                ?>
            <tr>
                <td>
                    Insumos Incorporados a la Prestación de Servicios
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    Otros
                </td>
                  <?php
                
                    echo '<td  class="numericTD">' .
                        number_format($existenciaInicialOtrosAnterior, 2, ",", ".")
                        . "</td>";
                    echo '<td  class="numericTD">' .
                        number_format($existenciaInicialOtros, 2, ",", ".")
                        . "</td>";
                ?>
            </tr>
            <tr>
                <td>
                    Participación en negocios conjuntos
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr style="background-color: #d0d9ff">
                <th>
                    Total Existencia Inicial
                </th>
                <?php
                $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
                while($periodoAMostrar<=$fechaFinConsulta) {
                    $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                    echo '<th  class="numericTD">' .
                        number_format($totalPeriodoExistenciaInicial[$periodoAImputar], 2, ",", ".")
                        . "</th>";
                    $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
                }
                ?>
            </tr>
            <tr>
                <th colspan="20" style="background-color: #91a7ff">
                    Compras
                </th>
            </tr>
            <tr>
                <td>
                    Mercaderías
                </td>
                <?php
                $totalPeriodoExistenciaFinal=[] ;
                //COMPRAS PERIODO ACTUAL + Existencia FINAL ACTUAL - EXISTENCIA INICIAL ACTUAL
                
                //501000001 Costo de Venta
                $existenciaComprasMercaderias = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["501000003"],$periodoActual,$keysCuentas);
                
                //110502012 Prod. Terminado XX Compras
                //110504012 Prod. en Proceso XX Compras
                //110506012 MP y Materiales XX Compras
                //110507012 Otros bienes de cambio XX Compras*/
                $existenciaComprasProdTerminado = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110502012"],$periodoActual,$keysCuentas);
                $existenciaComprasProdEnProc = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110504012"],$periodoActual,$keysCuentas);
                $existenciaComprasMpMaterials = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110506012"],$periodoActual,$keysCuentas);
                $existenciaComprasOtros = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110507012"],$periodoActual,$keysCuentas);
                
                //110500013 Mercaderías XX E Final
                //110502013 Prod. Terminado XX E Final
                //110504013 Prod. en Proceso XX E Final
                //110506013 MP y Materiales XX EFIN
                //110507013 Otros Bienes de Cambio EFin*/
                $existenciaFinalMercaderias = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110500013"],$periodoActual,$keysCuentas);
                $existenciaFinalProdTerminado = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110502013"],$periodoActual,$keysCuentas);
                $existenciaFinalProdEnProc = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110504013"],$periodoActual,$keysCuentas);
                $existenciaFinalMpMaterials = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110506013"],$periodoActual,$keysCuentas);
                $existenciaFinalOtros = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110507013"],$periodoActual,$keysCuentas);
                
                $totalPeriodoExistenciaFinal[$periodoActual] = $existenciaFinalMercaderias + $existenciaFinalProdTerminado + $existenciaFinalProdEnProc 
                        + $existenciaFinalMpMaterials + $existenciaFinalOtros;
                        
                $existenciaComprasMercaderias = $existenciaComprasMercaderias + $existenciaFinalMercaderias - $existenciaInicialMercaderias;
                $existenciaComprasProdTerminado = $existenciaComprasProdTerminado + $existenciaFinalProdTerminado - $existenciaInicialProdTerminado;
                $existenciaComprasProdEnProc = $existenciaComprasProdEnProc + $existenciaFinalProdEnProc - $existenciaInicialProdEnProc;
                $existenciaComprasMpMaterials = $existenciaComprasMpMaterials + $existenciaFinalMpMaterials - $existenciaInicialMpMaterials;
                $existenciaComprasOtros = $existenciaComprasOtros + $existenciaFinalOtros - $existenciaInicialOtros;
                $totalPeriodoCompras[$periodoActual] = $existenciaComprasMercaderias + $existenciaComprasProdTerminado + $existenciaComprasProdEnProc
                        + $existenciaComprasMpMaterials + $existenciaComprasOtros;
                //COMPRAS PERIODO ANTERIOR + Existencia FINAL ANTERIOR - EXISTENCIA INICIAL ANTERIOR
                
                //110500012 Mercaderías XX Compras
                //110502012 Prod. Terminado XX Compras
                //110504012 Prod. en Proceso XX Compras
                //110506012 MP y Materiales XX Compras
                //110507012 Otros bienes de cambio XX Compras*/
                $existenciaComprasMercaderiasAnterior = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110500012"],$periodoPrevio,$keysCuentas);
                $existenciaComprasProdTerminadoAnterior  = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110502012"],$periodoPrevio,$keysCuentas);
                $existenciaComprasProdEnProcAnterior  = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110504012"],$periodoPrevio,$keysCuentas);
                $existenciaComprasMpMaterialsAnterior  = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110506012"],$periodoPrevio,$keysCuentas);
                $existenciaComprasOtrosAnterior  = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110507012"],$periodoPrevio,$keysCuentas);
                
                //110500013 Mercaderías XX E Final
                //110502013 Prod. Terminado XX E Final
                //110504013 Prod. en Proceso XX E Final
                //110506013 MP y Materiales XX EFIN
                //110507013 Otros Bienes de Cambio EFin*/
                $existenciaFinalMercaderiasAnterior  = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110500013"],$periodoPrevio,$keysCuentas);
                $existenciaFinalProdTerminadoAnterior  = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110502013"],$periodoPrevio,$keysCuentas);
                $existenciaFinalProdEnProcAnterior  = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110504013"],$periodoPrevio,$keysCuentas);
                $existenciaFinalMpMaterialsAnterior  = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110506013"],$periodoPrevio,$keysCuentas);
                $existenciaFinalOtrosAnterior  = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["110507013"],$periodoPrevio,$keysCuentas);
                
                $totalPeriodoExistenciaFinal[$periodoPrevio] = $existenciaFinalMercaderiasAnterior + $existenciaFinalProdTerminadoAnterior + $existenciaFinalProdEnProcAnterior 
                        + $existenciaFinalMpMaterialsAnterior + $existenciaFinalOtrosAnterior;
                
                $existenciaComprasMercaderiasAnterior  = $existenciaComprasMercaderiasAnterior  + $existenciaFinalMercaderiasAnterior  - $existenciaInicialMercaderiasAnterior ;
                $existenciaComprasProdTerminadoAnterior  = $existenciaComprasProdTerminadoAnterior  + $existenciaFinalProdTerminadoAnterior - $existenciaInicialProdTerminadoAnterior ;
                $existenciaComprasProdEnProcAnterior  = $existenciaComprasProdEnProcAnterior  + $existenciaFinalProdEnProcAnterior  - $existenciaInicialProdEnProcAnterior ;
                $existenciaComprasMpMaterialsAnterior  = $existenciaComprasMpMaterialsAnterior  + $existenciaFinalMpMaterialsAnterior  - $existenciaInicialMpMaterialsAnterior ;
                $existenciaComprasOtrosAnterior  = $existenciaComprasOtrosAnterior  + $existenciaFinalOtrosAnterior  - $existenciaInicialOtrosAnterior ;
                
                $totalPeriodoCompras[$periodoPrevio] = $existenciaComprasMercaderiasAnterior + $existenciaComprasProdTerminadoAnterior + $existenciaComprasProdEnProcAnterior
                        + $existenciaComprasMpMaterialsAnterior + $existenciaComprasOtrosAnterior;
                echo '<td  class="numericTD">' .
                    number_format($existenciaComprasMercaderiasAnterior, 2, ",", ".")
                    . "</td>";
                echo '<td  class="numericTD">' .
                        number_format($existenciaComprasMercaderias, 2, ",", ".")
                        . "</td>";
                
                ?>

            </tr>
            <?php
            //mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoCompras,"Productos Terminados","110502012",$fechaInicioConsulta,$fechaFinConsulta);
            //mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoCompras,"Producción en Proceso","110504012",$fechaInicioConsulta,$fechaFinConsulta);
            //mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoCompras,"Materias Primas e Insumos incorporados a la producción","110506012",$fechaInicioConsulta,$fechaFinConsulta);
            ?>
            <tr>
                <td>
                    Productos Terminados
                </td>
                <?php
                echo '<td  class="numericTD">' .
                        number_format($existenciaComprasProdTerminadoAnterior, 2, ",", ".")
                    . "</td>";
                echo '<td  class="numericTD">' .
                        number_format($existenciaComprasProdTerminado, 2, ",", ".")
                    . "</td>";
                
                ?>
            </tr>
             <tr>
                <td>
                    Producción en Proceso
                </td>
                <?php
                echo '<td  class="numericTD">' .
                        number_format($existenciaComprasProdEnProcAnterior, 2, ",", ".")
                    . "</td>";
                echo '<td  class="numericTD">' .
                        number_format($existenciaComprasProdEnProc, 2, ",", ".")
                    . "</td>";
                
                ?>
            </tr>
             <tr>
                <td>
                    Materias Primas e Insumos incorporados a la producción
                </td>
                <?php
                echo '<td  class="numericTD">' .
                        number_format($existenciaComprasMpMaterialsAnterior, 2, ",", ".")
                    . "</td>";
                echo '<td  class="numericTD">' .
                        number_format($existenciaComprasMpMaterials, 2, ",", ".")
                    . "</td>";
                
                ?>
            </tr>
             <tr>
                <td>
                    Insumos Incorporados a la Prestación de Servicios
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    Otros: Gastos Activados
                </td>
                 <?php
                echo '<td  class="numericTD">' .
                        number_format($existenciaComprasOtrosAnterior, 2, ",", ".")
                    . "</td>";
                echo '<td  class="numericTD">' .
                        number_format($existenciaComprasOtros, 2, ",", ".")
                    . "</td>";
                
                ?>
            </tr>
            <tr>
                <td>
                    Participación en negocios conjuntos
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr style="background-color: #d0d9ff">
                <th>
                    Total de Compras
                </th>
                <?php
               $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
                while($periodoAMostrar<=$fechaFinConsulta) {
                    $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                    
                    echo '<th  class="numericTD">' .
                        number_format($totalPeriodoCompras[$periodoAImputar], 2, ",", ".")
                        . "</th>";
                    $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
                }
                ?>
            </tr>
            <tr>
                <th colspan="20" style="background-color: #91a7ff">
                    Devoluciones de Compras
                </th>
            </tr>
            <tr>
                <td>
                    Mercaderías
                </td>
                <?php
                $totalPeriodoDevoluciones = [];
                mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoDevoluciones,"Mercaderías","110500014",$fechaInicioConsulta,$fechaFinConsulta);
                mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoDevoluciones,"Productos Terminados","110502014",$fechaInicioConsulta,$fechaFinConsulta);
                mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoDevoluciones,"Producción en Proceso","110504014",$fechaInicioConsulta,$fechaFinConsulta);
                mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoDevoluciones,"Materias Primas e Insumos incorporados a la producción","110506014",$fechaInicioConsulta,$fechaFinConsulta);
                ?>
            </tr>
            <tr>
                <td>
                    Insumos Incorporados a la Prestación de Servicios
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    Otros
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    Participación en negocios conjuntos
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr style="background-color: #d0d9ff">
                <th>
                    Total de Devoluciones de Compras
                </th>
                <?php
                $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
                while($periodoAMostrar<=$fechaFinConsulta) {
                    $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                    echo '<th  class="numericTD">' .
                        number_format($totalPeriodoDevoluciones[$periodoAImputar], 2, ",", ".")
                        . "</th>";
                    $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
                }
                ?>
            </tr>
            <tr>
                <th>
                    Gastos Imputables al Costo
                </th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th>
                    Costos Financieros Activados
                </th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td>
                    Resultado por Tenencia
                </td>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th>
                    Otros
                </th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th colspan="20" style="background-color: #91a7ff">
                    Existencia Final
                </th>
            </tr>
            <tr>
                <td >
                    Mercaderías
                </td>
                <?php
                $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
                while($periodoAMostrar<=$fechaFinConsulta) {
                    $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodoExistenciaFinal[$periodoAImputar], 2, ",", ".")
                        . "</td>";
                    $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
                }
                ?>
            </tr>
            <?php
             mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoExistenciaFinal,"Productos Terminados","110502013",$fechaInicioConsulta,$fechaFinConsulta);
             mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoExistenciaFinal,"Producción en Proceso","110504013",$fechaInicioConsulta,$fechaFinConsulta);
             mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,$totalPeriodoExistenciaFinal,"Materias Primas y Materiales","110506013",$fechaInicioConsulta,$fechaFinConsulta);
            ?>
            <tr>
                <td>
                    Insumos Incorporados a la Prestación de Servicios
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    Otros
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    Participación en negocios conjuntos
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr style="background-color: #d0d9ff">
                <th>
                    Total Existencia Final
                </th>
                <?php
                $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
                while($periodoAMostrar<=$fechaFinConsulta) {
                    $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                    echo '<th  class="numericTD">' .
                        number_format($totalPeriodoExistenciaFinal[$periodoAImputar], 2, ",", ".")
                        . "</th>";
                    $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
                }
                ?>
            </tr>
            <tr>
                <th colspan="20" style="background-color: #91a7ff">
                    Prestación de Servicios
                </th>
            </tr>
            <tr>
                <td>
                    Costo de Prestación Servicios
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    Indumentaria
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    Insumo
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr style="background-color: #d0d9ff">
                <th>
                    Total Existencia Final
                </th>
                <th></th>
                <th></th>
            </tr>
            <tr  style="background-color: #d0d9ff">
                <th>
                    Costo de los Bienes, de los Servicios y de Producción
                </th>
                <?php
                $totalPeriodoCostoBienesServiciosProduccion = [];
                $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
                while($periodoAMostrar<=$fechaFinConsulta) {
                    echo '<th  class="numericTD">' ;
                    $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                    $totalPeriodo = $totalPeriodoExistenciaInicial[$periodoAImputar];
                    $totalPeriodo += $totalPeriodoCompras[$periodoAImputar];
                    $totalPeriodo -= $totalPeriodoDevoluciones[$periodoAImputar];
                    $totalPeriodo -= $totalPeriodoExistenciaFinal[$periodoAImputar];
                   
                    echo number_format($totalPeriodo, 2, ",", ".");
                    echo  "</th>";
                    if(!isset($totalPeriodoCostoBienesServiciosProduccion[$periodoAImputar])){
                        $totalPeriodoCostoBienesServiciosProduccion[$periodoAImputar] = 0;//existen estos valores
                    }
                    $totalPeriodoCostoBienesServiciosProduccion[$periodoAImputar] += $totalPeriodo;
                    $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
                }
                ?>
            </tr>
            </tbody>
        </table>
    </div>
    <div id="AnexoII" class="index" style="">
        <table id="tblAnexoII" class="tbl_border tblEstadoContable">
            <thead>
                <tr style="background-color: #91a7ff">
                    <th colspan="20">
                        <h3>Anexo II de Costos de Producción y Gastos clasificados por su naturaleza al 31/12/2016 comparativo con el Ejercicio Anterior</h3>
                    </th>
                </tr>
                <tr>
                    <th rowspan="2"></th>
                    <th colspan="4">Ejercicio Actual</th>
                    <th colspan="1">Ejercicio Anterior</th>
                </tr>
                <tr>
                    <th style="width:90px">Costo de vta, producc. y adquis de bs de uso, intang. y otros activos</th>
                    <th style="width:90px">Gastos de Administración</th>
                    <th style="width:90px">Gastos de Comercialización</th>
                    <th style="width:90px">Total</th>
                    <th style="width:90px">Total</th>
                </tr>
                <tr>
                    <th>Porcentajes para el prorrateo</th>
                    <th style="width:90px">0%</th>
                    <th style="width:90px">25%</th>
                    <th style="width:90px">75%</th>
                    <th style="width:90px">100%</th>
                    <th style="width:90px"></th>
                </tr>
            </thead>
            <tbody>
             <?php
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Combustibles, Lubricantes y FM","50401000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Servicios Públicos","50402000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Alquileres y Expensas","50403000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                ?>
                <tr ><th colspan="20">Amortizaciones</th></tr>
                <?php

               mostrarNotasDeGastos($arrayCuentasxPeriodos,"Amortización Inmuebles","50404100",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
               mostrarNotasDeGastos($arrayCuentasxPeriodos,"Amortización Rodados","50404200",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
               mostrarNotasDeGastos($arrayCuentasxPeriodos,"Amortización Instalaciones","50404300",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
               mostrarNotasDeGastos($arrayCuentasxPeriodos,"Amortización Muebles y Utiles","50404400",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
               mostrarNotasDeGastos($arrayCuentasxPeriodos,"Amortización Maquinarias","50404500",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
               mostrarNotasDeGastos($arrayCuentasxPeriodos,"Amortización Activos Biológico","50404600",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
               mostrarNotasDeGastos($arrayCuentasxPeriodos,"Gastos de Traslados","50405000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
               mostrarNotasDeGastos($arrayCuentasxPeriodos,"Seguros","50406000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
               mostrarNotasDeGastos( $arrayCuentasxPeriodos,"Gastos de Cortesia y Homenaje", "50407000", $fechaInicioConsulta, $fechaFinConsulta,$totalAnexoII);
               mostrarNotasDeGastos($arrayCuentasxPeriodos,"Mantenimiento, Reparación, etc","50408000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
               mostrarNotasDeGastos($arrayCuentasxPeriodos,"Gastos de Librería","50409000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
               mostrarNotasDeGastos($arrayCuentasxPeriodos,"Gastos Varios","50410000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
               mostrarNotasDeGastos( $arrayCuentasxPeriodos,"Incobrables", "50411000", $fechaInicioConsulta, $fechaFinConsulta,$totalAnexoII);
               mostrarNotasDeGastos($arrayCuentasxPeriodos,"Honorarios Directores","50412000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
               mostrarNotasDeGastos($arrayCuentasxPeriodos,"Honorarios Sindicos","50413000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
               mostrarNotasDeGastos( $arrayCuentasxPeriodos,"Otros Gastos (no prorrateables)", "50499000", $fechaInicioConsulta, $fechaFinConsulta,$totalAnexoII);
               ?>
               <tr ><th colspan="20">Gastos Financieros</th></tr>
               <tr ><th colspan="20">Gtos. Financ. de Act. Operativ</th></tr>
            <?php
                //CONSULTAR POR QUE NO TENEMOS LA CUENTA PROVDORES!!!!!!
                //mostrarNotasDeGastos($arrayCuentasxPeriodos,"Proveedores","50501010",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Acreedores Varios","505030",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
            ?>
            <tr ><th colspan="20">Entidades Crediticias</th></tr>
            <?php
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Banco","5050101",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Banco","5050102",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Banco","5050203",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Banco","5050204",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Banco","5050205",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Banco","5050206",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Banco","5050207",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Banco","5050208",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Banco","5050209",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Banco","5050210",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                
                //NO TENEMOS OTRAS ENTIDADES CREDITICIAS
                //mostrarNotasDeGastos($arrayCuentasxPeriodos,"Otras Entidades Crediticias","50503000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
            ?>
            <tr ><th colspan="20">Organismos Públicos</th></tr>
            <?php
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"AFIP","50504010",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"DGR","50504020",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"DGRM","50504030",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
            ?>
            <tr ><th colspan="20">Gastos Fiscales</th></tr>
            <tr ><th colspan="20">Gastos Fiscales - AFIP</th></tr>
            <?php
                //Preguntar si este no va!!!
                //mostrarNotasDeGastos($arrayCuentasxPeriodos,"Ganancias","50611000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Ganancia Mín. Presunta","50612000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Bienes Personales","50613000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Otros Impuestos Nacionales","50614000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
            ?>
            <tr ><th colspan="20">Gastos Fiscales - DGR</th></tr>
            <?php
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Ingresos Brutos","50621000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Cooperadoras Asistenciales","50622000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Inmobiliario Rural","50623000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Impuesto a los Sellos","50624000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
            ?>
            <tr ><th colspan="20">Gastos Fiscales - DGRM</th></tr>
            <?php
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Actividades Varias","50631000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Tasa de publicidad y Propaganda","50632000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Inmobiliario Urbano","50633000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Alumbrado y Limpieza","50634000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Impuesto Automotor","50635000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
            ?>
            <tr ><th colspan="20">Remuneraciones y Cargas Sociales</th></tr>
            <?php
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Mano de Obra","50302000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Contribuciones Empleador","5030300",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos( $arrayCuentasxPeriodos,"Mano de Obra", "5071000", $fechaInicioConsulta, $fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Contribuciones Empleador","5072000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
                mostrarNotasDeGastos($arrayCuentasxPeriodos,"Gastos Extraordinarios","50900000",$fechaInicioConsulta,$fechaFinConsulta,$totalAnexoII);
            $mesAMostrar = date('Y', strtotime($fechaFinConsulta.'-01-01'));
            $subtottalPeriodo = $totalAnexoII[$mesAMostrar];
            echo '<tr style="background-color: #d0d9ff">
                    <th>Total Ejercicio Actual</th>
                    <th  class="numericTD">' .
                number_format($subtottalPeriodo*0.0, 2, ",", ".")
                . "</th>";
            echo '<th  class="numericTD">' .
                number_format($subtottalPeriodo*0.25, 2, ",", ".")
                . "</th>";
            echo '<th  class="numericTD">' .
                number_format($subtottalPeriodo*0.75, 2, ",", ".")
                . "</th>";
            echo '<th  class="numericTD">' .
                number_format($subtottalPeriodo, 2, ",", ".")
                . "</th>";
            $mesAMostrar = date('Y', strtotime($fechaInicioConsulta .'-01-01'));
            $subtottalPeriodo = $totalAnexoII[$mesAMostrar];
            echo '<th  class="numericTD">' .
                number_format($subtottalPeriodo, 2, ",", ".")
                . "</th></tr>";
            echo '<tr style="background-color: #d0d9ff">
                    <th>Total Ejercicio Anterior</th>
                    <th  class="numericTD">' .
                number_format($subtottalPeriodo*0.0, 2, ",", ".")
                . "</th>";
            echo '<th  class="numericTD">' .
                number_format($subtottalPeriodo*0.25, 2, ",", ".")
                . "</th>";
            echo '<th  class="numericTD">' .
                number_format($subtottalPeriodo*0.75, 2, ",", ".")
                . "</th>";
            echo '<th  class="numericTD">' .
                number_format($subtottalPeriodo, 2, ",", ".")
                . "</th></tr>";
            ?>
            </tbody>
        </table>
    </div><!--Anexo II-->
</div>
<div class="index" id="divContenedorEstadosResultados">
    <?php
    echo "<h2>Estados de resultados</h2>";
    ?>
    <table id="tblestadoderesultado"  class="tbl_border tblEstadoContable" cellspacing="0" style="">
        <thead>
        <tr class="trnoclickeable" style="background-color: #91a7ff">
            <th colspan="20"><h3>Estado de Resultados por el Ejercicio Anual Finalizado el 31/12/2016 comparativo con el
                    ejercicio anterior</h3>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr style="background-color: #91a7ff">
            <th>Resultados de las operaciones que continúan</th>
            <th></th>
            <th>Anterior</th>
            <th>Actual</th>
        </tr>
        <tr>
            <td>
                Ventas netas de bienes y servicios
                <?php
                $numeroDeNota = "";
                if(isset($totalVentasBienes)){
                    $numeroDeNota = $totalVentasBienes['numeronota'];//existen estos valores
                }
                $totalPeriodo=[];
                ?>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
            $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
            while($periodoAMostrar<=$fechaFinConsulta) {
                $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                if(!isset($totalPeriodo[$periodoAImputar])){
                    $totalPeriodo[$periodoAImputar] = 0;//existen estos valores
                }
                $total = isset($totalVentasBienes[$periodoAImputar])?$totalVentasBienes[$periodoAImputar]:0;
                $totalPeriodo[$periodoAImputar] += $total;
//                $totalPeriodo += isset($totalVentasServicios[$periodoAImputar])?$totalVentasServicios[$periodoAImputar]:0;;
                echo '<td  class="numericTD">' .
                    number_format($total, 2, ",", ".")
                    . "</td>";
                $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
            }
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
            $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
            while($periodoAMostrar<=$fechaFinConsulta) {
                $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                echo '<td  class="numericTD">' .
                    number_format($totalPeriodoCostoBienesServiciosProduccion[$periodoAImputar]*-1, 2, ",", ".")
                    . "</td>";
                $totalPeriodo[$periodoAImputar] -= $totalPeriodoCostoBienesServiciosProduccion[$periodoAImputar];
                $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
            }
            ?>
        </tr>
        <tr>
            <td>
                Reintegros
                <?php
                $numeroDeNota = "";
                if(isset($totalReintegros['numeronota'])){
                    $numeroDeNota = $totalReintegros['numeronota'];//existen estos valores
                }
                ?>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
            $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
            while($periodoAMostrar<=$fechaFinConsulta) {
                $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                $total = isset($totalReintegros[$periodoAImputar])?$totalReintegros[$periodoAImputar]:0;
                $totalPeriodo[$periodoAImputar] += $total;
                echo '<td  class="numericTD">' .
                    number_format($total, 2, ",", ".")
                    . "</td>";
                $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
            }
            ?>
        </tr>
        <tr>
            <td>
                Desgravaciones
                <?php
                $numeroDeNota = "";
                if(isset($totalDesgravaciones['numeronota'])){
                    $numeroDeNota = $totalDesgravaciones['numeronota'];//existen estos valores
                }
                ?>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
            $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
            while($periodoAMostrar<=$fechaFinConsulta) {
                $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                $total = isset($totalDesgravaciones[$periodoAImputar])?$totalDesgravaciones[$periodoAImputar]:0;
                $totalPeriodo[$periodoAImputar] += $total;
                echo '<td  class="numericTD">' .
                    number_format($total, 2, ",", ".")
                    . "</td>";
                $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
            }
            ?>
        </tr>
        <tr style="background-color: #d0d9ff">
            <th>Ganancia Bruta</th>
            <th>

            </th>
            <?php
            $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
            while($periodoAMostrar<=$fechaFinConsulta) {
                $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                echo '<th  class="numericTD">' .
                    number_format($totalPeriodo[$periodoAImputar], 2, ",", ".")
                    . "</th>";
                $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
            }
            ?>
        </tr>
        <tr>
            <td>Resultado neto por producción agropecuaria</td>
            <?php
            $numeroDeNota = "";
            if(isset($totalProduccionagropecuaria['numeronota'])){
                $numeroDeNota = $totalProduccionagropecuaria['numeronota'];//existen estos valores
            }
            ?>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
            $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
            while($periodoAMostrar<=$fechaFinConsulta) {
                $periodoAImputar = date('Y', strtotime($periodoAMostrar));                
                $total = isset($totalProduccionagropecuaria[$periodoAMostrar])?$totalProduccionagropecuaria[$periodoAMostrar]:0;
                $totalPeriodo[$periodoAImputar] += $total;
                echo '<td  class="numericTD">' .
                    number_format($total, 2, ",", ".")
                    . "</td>";
                $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
            }
            ?>
        </tr>
        <tr>
            <td>Rtdo por valuación de bienes de cambio al VNR
                <?php
                $numeroDeNota = "";
                if(isset($totalValuacionbienesdecambio['numeronota'])){
                    $numeroDeNota = $totalValuacionbienesdecambio['numeronota'];//existen estos valores
                }
                ?>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
            $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
            while($periodoAMostrar<=$fechaFinConsulta) {
                $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                if(!isset($totalPeriodo[$periodoAImputar])){
                    $totalPeriodo[$periodoAImputar] = 0;//existen estos valores
                }
                $total = isset($totalValuacionbienesdecambio[$periodoAImputar])?$totalValuacionbienesdecambio[$periodoAImputar]:0;
                $totalPeriodo[$periodoAImputar] += $total;
                echo '<td  class="numericTD">' .
                    number_format($total, 2, ",", ".")
                    . "</td>";
                $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
            }
            ?>
        </tr>
        <tr>
            <td>Gastos indirectos de prestación de servicios</td>
            <td>
                Anexo II
            </td>
            <td class="numericTD">
                <?php
                $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
                $periodoAMostrarFIN = date('Y', strtotime($fechaInicioConsulta));
                $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                $periodoAImputarFIN = date('Y', strtotime($periodoAMostrar));

                echo number_format($totalAnexoII[$periodoAImputar]*0.0, 2, ",", ".");
                ?>
            </td>
            <td class="numericTD">
                <?php
                echo number_format($totalAnexoII[$periodoAMostrarFIN]*0.0, 2, ",", ".");
                ?>
            </td>
        </tr>
        <tr>
            <td>Gasto de comercialización</td>
            <td>
                Anexo II
            </td>
            <td class="numericTD">
                <?php
                echo number_format($totalAnexoII[$periodoAImputar]*-0.75, 2, ",", ".");
                ?>
            </td>
            <td class="numericTD">
                <?php
                echo number_format($totalAnexoII[$periodoAMostrarFIN]*-0.75, 2, ",", ".");
                ?>
            </td>
        </tr>
        <tr>
            <td>Gasto de administración</td>
            <td>
                Anexo II
            </td>
            <td class="numericTD">
                <?php
                echo number_format($totalAnexoII[$periodoAImputar]*-0.25, 2, ",", ".");
                ?>
            </td>
            <td class="numericTD">
                <?php
                echo number_format($totalAnexoII[$periodoAMostrarFIN]*-0.25, 2, ",", ".");
                if(!isset($totalPeriodo[$periodoAImputar])){
                    $totalPeriodo[$periodoAImputar] = 0;//existen estos valores
                }
                $totalPeriodo[$periodoAImputar] -= $totalAnexoII[$periodoAImputar];
                if(!isset($totalPeriodo[$periodoAMostrarFIN])){
                    $totalPeriodo[$periodoAMostrarFIN] = 0;//existen estos valores
                }
                $totalPeriodo[$periodoAMostrarFIN] -= $totalAnexoII[$periodoAMostrarFIN];
                ?>
            </td>
        </tr>
        <tr>
            <td>Gastos operativos</td>
            <td>
                
            </td>
            <td class="numericTD">
                0.000
            </td>
            <td class="numericTD">
                0.000
            </td>
        </tr>
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
            $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
            while($periodoAMostrar<=$fechaFinConsulta) {
                $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                if(!isset($totalPeriodo[$periodoAImputar])){
                    $totalPeriodo[$periodoAImputar] = 0;//existen estos valores
                }
                $total = isset($totalOtrosGastos[$periodoAImputar])?$totalOtrosGastos[$periodoAImputar]:0;
                $totalPeriodo[$periodoAImputar] += $total;
                echo '<td  class="numericTD">' .
                    number_format($total, 2, ",", ".")
                    . "</td>";
                $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
            }
            ?>
        </tr>
        <tr>
            <td>Resultado de inversiones en entes relacionados
                <?php
                $numeroDeNota = "";
                if(isset($totalinversionesenentes['numeronota'])){
                    $numeroDeNota = $totalinversionesenentes['numeronota'];//existen estos valores
                }
                ?>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
            $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
            while($periodoAMostrar<=$fechaFinConsulta) {
                $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                if(!isset($totalPeriodo[$periodoAImputar])){
                    $totalPeriodo[$periodoAImputar] = 0;//existen estos valores
                }
                $total = isset($totalinversionesenentes[$periodoAImputar])?$totalinversionesenentes[$periodoAImputar]:0;
                $totalPeriodo[$periodoAImputar] += $total;
                echo '<td  class="numericTD">' .
                    number_format($total, 2, ",", ".")
                    . "</td>";
                $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
            }
            ?>
        </tr>
        <tr>
            <td>Depreciación llave de negocio</td>
            <td>

            </td>
            <td>
                0.000
            </td>
            <td>
                0.000
            </td>
        </tr>
        <tr>
            <td>Resultados financieros y por tenencia
                <?php
                $numeroDeNota = "";
                if(isset($totalresultadosfinancieros['numeronota'])){
                    $numeroDeNota = $totalresultadosfinancieros['numeronota'];//existen estos valores
                }
                ?>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
            $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
            while($periodoAMostrar<=$fechaFinConsulta) {
                $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                if(!isset($totalPeriodo[$periodoAImputar])){
                    $totalPeriodo[$periodoAImputar] = 0;//existen estos valores
                }
                $total = isset($totalresultadosfinancieros[$periodoAImputar])?$totalresultadosfinancieros[$periodoAImputar]:0;
                $totalPeriodo[$periodoAImputar] += $total;
                echo '<td  class="numericTD">' .
                    number_format($total, 2, ",", ".")
                    . "</td>";
                $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
            }
            ?>
        </tr>
        <tr>
            <td>Interés del capital propio
                <?php
                $numeroDeNota = "";
                if(isset($totalInteresdelcapitalpropio['numeronota'])){
                    $numeroDeNota = $totalInteresdelcapitalpropio['numeronota'];//existen estos valores
                }
                ?>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
            $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
            while($periodoAMostrar<=$fechaFinConsulta) {
                $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                if(!isset($totalPeriodo[$periodoAImputar])){
                    $totalPeriodo[$periodoAImputar] = 0;//existen estos valores
                }
                $total = isset($totalInteresdelcapitalpropio[$periodoAImputar])?$totalInteresdelcapitalpropio[$periodoAImputar]:0;
                $totalPeriodo[$periodoAImputar] += $total;
                echo '<td  class="numericTD">' .
                    number_format($total, 2, ",", ".")
                    . "</td>";
                $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
            }
            ?>
        </tr>
        <tr>
            <td>Otros ingresos
                <?php
                $numeroDeNota = "";
                if(isset($totalOtrosIngresos['numeronota'])){
                    $numeroDeNota = $totalOtrosIngresos['numeronota'];//existen estos valores
                }
                ?>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:"";?>
            </td>
            <?php
            $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
            while($periodoAMostrar<=$fechaFinConsulta) {
                $periodoAImputar = date('Y', strtotime($periodoAMostrar));             
                $total = isset($totalOtrosIngresos[$periodoAImputar])?$totalOtrosIngresos[$periodoAImputar]:0;
                $totalPeriodo[$periodoAImputar] += $total;
                echo '<td  class="numericTD">' .
                    number_format($total, 2, ",", ".")
                    . "</td>";
                $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
            }
            ?>
        </tr>
        <tr style="background-color: #d0d9ff">
            <th>
                Ganancia antes del impuesto a las ganancias de operaciones que continúan
            </th>
            <th></th>
            <?php
            $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
            while($periodoAMostrar<=$fechaFinConsulta) {
                $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                echo '<td  class="numericTD">' .
                    number_format($totalPeriodo[$periodoAImputar], 2, ",", ".")
                    . "</td>";
                $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
            }
            ?>
        </tr>
        <tr>
            <td>
                Impuesto a las ganancias 															 			Nota			3
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
            $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
            while($periodoAMostrar<=$fechaFinConsulta) {
                $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                if(!isset($totalPeriodo[$periodoAImputar])){
                    $totalPeriodo[$periodoAImputar] = 0;//existen estos valores
                }
                $total = isset($totalImpuestoALaGanancia[$periodoAImputar])?$totalImpuestoALaGanancia[$periodoAImputar]:0;
                $totalPeriodo[$periodoAImputar] -= $total;
                echo '<td  class="numericTD">' .
                    number_format($total*-1, 2, ",", ".")
                    . "</td>";
                $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
            }
            ?>
        </tr>
        <tr style="background-color: #d0d9ff">
            <th>
                Ganancia de las operaciones que continúan
            </th>
            <th></th>
            <?php
            $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
            while($periodoAMostrar<=$fechaFinConsulta) {
                $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                echo '<td  class="numericTD">' .
                    number_format($totalPeriodo[$periodoAImputar], 2, ",", ".")
                    . "</td>";
                $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
            }
            ?>
        </tr>
        <tr style="background-color: #d0d9ff">
            <th>
                Resultado por las operaciones en descontinuación
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
                Resultado de por disposición de activos o cancelación de pasivos antes de impuesto a las ganancias (nota)
            </td>
            <td></td>
            <td>0,00</td>
            <td>0,00</td>
        </tr>
        <tr>
            <td>
                Impuesto a las ganancias sobre los resultados por disposición de activos y/o liquidación de deudas
            </td>
            <td></td>
            <td>0,00</td>
            <td>0,00</td>
        </tr>
        <tr>
            <td>
                Resultado por disposición de activos y/o cancelación de pasivos
            </td>
            <td></td>
            <td>0,00</td>
            <td>0,00</td>
        </tr>
        <tr style="background-color: #d0d9ff">
            <th>
                Perdida por las operaciones en descontinuación
            </th>
            <th></th>
            <th>0,00</th>
            <th>0,00</th>
        </tr>
        <tr style="background-color: #d0d9ff">
            <th>
                Ganancia de las operaciones ordinarias
            </th>
            <th></th>
           <?php
            $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
            while($periodoAMostrar<=$fechaFinConsulta) {
                $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                echo '<th  class="numericTD">' .
                    number_format($totalPeriodo[$periodoAImputar], 2, ",", ".")
                    . "</th>";
                $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
            }
            ?>
        </tr>
        <tr>
            <td>
                Resultado de las operaciones extraordinarias
            </td>
            <td></td>
            <td>0,00</td>
            <td>0,00 Chekiar esto</td>
        </tr>
        <tr style="background-color: #91a7ff">
            <th>
                Ganancia del ejercicio
            </th>
            <th></th>
            <?php
            $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
            while($periodoAMostrar<=$fechaFinConsulta) {
                $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                echo '<th  class="numericTD">' .
                    number_format($totalPeriodo[$periodoAImputar], 2, ",", ".")
                    . "</th>";
                $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
            }
            ?>
        </tr>

        </tbody>
        <tfoot>
        <tr class="trnoclickeable">
            <td></td>
        </tr>
        </tfoot>
    </table>
</div><!--Estado de resultados-->
<div class="index" id="divContenedorEvolucionPatrimonioNeto">
    <table class="tbl_border tblEEPN tblEstadoContable" cellspacing="0">
        <thead>
            <tr>
                <th colspan="20">
                    Estado de Evolución del Patrimonio Neto por el Ejercicio Anual Finalizado el 31/12/2015 comparativo con el ejercicio anterior
                </th>
            </tr>
            <tr>
                <th rowspan="3">Rubro</th>
                <th colspan="5">Aporte de los Propietarios</th>
                <th colspan="7">Resultados Acumulados</th>
                <th colspan="2">Totales del Ejercicio</th>
            </tr>
            <tr>
                <th rowspan="2">Capital Social</th>
                <th rowspan="2">Ajuste del Capital</th>
                <th rowspan="2">Aportes Irrevocables</th>
                <th rowspan="2">Primera Emision</th>
                <th rowspan="2">Total</th>
                <th colspan="3">Ganancias Reservadas</th>
                <th colspan="2">Resultados Diferidos</th>
                <th rowspan="2">Resultados No Asignados</th>
                <th rowspan="2">Total</th>
                <th rowspan="2">Actual</th>
                <th rowspan="2">Anterior</th>
            </tr>
            <tr>
                <th>Legal</th>
                <th>Otras Reservas</th>
                <th>Total de Ganancias Reservadas</th>
                <th>Por Diferencias de Conversión</th>
                <th>Total</th>
            </tr>
        </thead>
        
        <tbody>
            <tr>
                <td>Saldos al Inicio del Ejercicio</td>
                <?php
                //vamos a inicializar la row
                $saldoinicioejercicio=[];initializeRubtoEEPN($saldoinicioejercicio);      
                $saldoinicioejercicio['capitalsocial'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["410100001"],$periodoPrevio,$keysCuentas);
                $saldoinicioejercicio['ajustedelcapital'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["410200001"],$periodoPrevio,$keysCuentas);
                $saldoinicioejercicio['aportesirrevocables'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["410300001"],$periodoPrevio,$keysCuentas);
                $saldoinicioejercicio['primadeemision'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["4104"],$periodoPrevio,$keysCuentas);
                $saldoinicioejercicio['legal'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["420100001"],$periodoPrevio,$keysCuentas);
                $saldoinicioejercicio['otrasreservas'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["420100002","420100003","420100004","420199999"],$periodoPrevio,$keysCuentas);
                $saldoinicioejercicio['podiferenciasdeconversion'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["4202001"],$periodoPrevio,$keysCuentas);
                $saldoinicioejercicio['porinstrumentosderivados'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["4202002"],$periodoPrevio,$keysCuentas);
                $saldoinicioejercicio['resultadosnoasignados'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["420300"],$periodoActual,$keysCuentas);
                showRowEEPN($saldoinicioejercicio);
                ?>
            </tr>
            <tr>
                <td>Modificación S. al inicio del Ejer. </td>
                <?php
                //vamos a inicializar la row
                $modificacioninicioejercicio=[];initializeRubtoEEPN($modificacioninicioejercicio);                             
                showRowEEPN($modificacioninicioejercicio);
                ?>
            </tr>
            <tr>
                <td>Saldos al Inicio del Ejer. Modif.</td>
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
                showRowEEPN($saldoinicioejerciciomodificado);
                   ?>
            </tr>
            <tr>
                <td>Suscripción de capital social</td>
                <?php
                //vamos a inicializar la row
                $suscripcioncapitalsocial=[];initializeRubtoEEPN($suscripcioncapitalsocial);              
                $suscripcioncapitalsocial['capitalsocial'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["410100099"],$periodoActual,$keysCuentas);
                showRowEEPN($suscripcioncapitalsocial);
                ?>
            </tr>
            <tr>
                <td>Capitalización aportes irrevocab.</td>
                <?php
                //vamos a inicializar la row
                $capitalicacionaportesirrevocables=[];initializeRubtoEEPN($capitalicacionaportesirrevocables);     
                $capitalicacionaportesirrevocables['ajustedelcapital'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["410300001"],$periodoActual,$keysCuentas);
                $capitalicacionaportesirrevocables['aportesirrevocables'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["410300001"],$periodoActual,$keysCuentas)*-1;
                showRowEEPN($capitalicacionaportesirrevocables);
               ?>
            </tr>
            <tr>
                <td>Capitalización de ap. Propietarios</td>
                <?php
                //vamos a inicializar la row
                $capitalicacionaportespropietarios=[];initializeRubtoEEPN($capitalicacionaportespropietarios);          
                $capitalicacionaportespropietarios['ajustedelcapital'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["4102000"],$periodoActual,$keysCuentas);	
                showRowEEPN($capitalicacionaportespropietarios);
                ?>
            </tr>
            <tr>
                <td>Aportes para absorber quebrantos</td>
                <?php
                //vamos a inicializar la row
                $aporteparaabsorberquebrantos=[];initializeRubtoEEPN($aporteparaabsorberquebrantos);       
                $aporteparaabsorberquebrantos['primadeemision'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["4104"],$periodoActual,$keysCuentas);
                showRowEEPN($aporteparaabsorberquebrantos);
                ?>
            <tr>
                <td>Distribución rtdos no asignados</td>
                 <?php
                //vamos a inicializar la row
                $distribucionresultadosnoasignados=[];initializeRubtoEEPN($distribucionresultadosnoasignados);              
                showRowEEPN($distribucionresultadosnoasignados);
                ?>
            </tr>
            <tr>
                <td>Reserva legal</td>
                 <?php
                //vamos a inicializar la row
                $reservalegal=[];initializeRubtoEEPN($reservalegal);           
                $reservalegal['legal'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["420100001"],$periodoActual,$keysCuentas);
                $reservalegal['resultadosnoasignados'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["420100001"],$periodoActual,$keysCuentas)*-1;
                showRowEEPN($capitalicacionaportespropietarios);
                ?>
            </tr>
            <tr>
                <td>Otras reservas</td>
                 <?php
                //vamos a inicializar la row
                $otrasreservas=[];initializeRubtoEEPN($otrasreservas);   
                $otrasreservas['otrasreservas'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["420100001"],$periodoActual,$keysCuentas);
                $otrasreservas['resultadosnoasignados'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["420100002","420100003","420100004","420100999"],$periodoActual,$keysCuentas)*-1;
                showRowEEPN($otrasreservas);
                ?>
            </tr>
            <tr>
                <td>Dividendos en efectivos</td>
                 <?php
                //vamos a inicializar la row
                $dividendosenefectivos=[];initializeRubtoEEPN($dividendosenefectivos);              
                showRowEEPN($dividendosenefectivos);
                ?>
            </tr>
            <tr>
                <td>Dividendos en especie</td>
                 <?php
                //vamos a inicializar la row
                $dividendosenespecie=[];initializeRubtoEEPN($dividendosenespecie);              
                showRowEEPN($dividendosenespecie);
                ?>
            </tr>
            <tr>
                <td>Dividendo en acciones/cuota</td>
                 <?php
                //vamos a inicializar la row
                $dividendosenaccionescuotas=[];initializeRubtoEEPN($dividendosenaccionescuotas);              
                showRowEEPN($dividendosenaccionescuotas);
                ?>
            </tr>
            <tr>
                <td>Desafectación de reservas (Nota …)</td>
                 <?php
                //vamos a inicializar la row
                $desafectaciondereservas=[];initializeRubtoEEPN($desafectaciondereservas);              
                showRowEEPN($desafectaciondereservas);
                ?>
            </tr>
            <tr>
                <td>Aportes irrevocables</td>
                 <?php
                //vamos a inicializar la row
                $aportesirrevocables=[];initializeRubtoEEPN($aportesirrevocables);   
                $aportesirrevocables['aportesirrevocables'] = sumarCuentasEnPeriodo($arrayCuentasxPeriodos,["410300001"],$periodoActual,$keysCuentas);
                showRowEEPN($aportesirrevocables);
                ?>
            </tr>
            <tr>
                <td>Absorción de perdida acumuladas</td>
                 <?php
                //vamos a inicializar la row
                $absorciondeperdidaacumulada=[];initializeRubtoEEPN($absorciondeperdidaacumulada);              
                showRowEEPN($absorciondeperdidaacumulada);
                ?>
            </tr>
            <tr>
                <td>Incremento de rtdos diferidos</td>
                 <?php
                //vamos a inicializar la row
                $incrementodertdosdiferidos=[];initializeRubtoEEPN($incrementodertdosdiferidos);              
                showRowEEPN($incrementodertdosdiferidos);
                ?>
            </tr>
            <tr>
                <td>Desafectación de rtdos diferidos</td>
                 <?php
                //vamos a inicializar la row
                $desafectaciondertdosdiferidos=[];initializeRubtoEEPN($desafectaciondertdosdiferidos);              
                showRowEEPN($desafectaciondertdosdiferidos);
                ?>
            </tr>
            <tr>
                <td>Otros</td>
                 <?php
                //vamos a inicializar la row
                $otros=[];initializeRubtoEEPN($otros);              
                showRowEEPN($otros);
                ?>
            </tr>
            <tr>
                <td>Resultado del Ejercicio</td>
                 <?php
                //vamos a inicializar la row
                $resultadodelejercicio=[];initializeRubtoEEPN($resultadodelejercicio);              
                showRowEEPN($resultadodelejercicio);
                ?>
            </tr>
             <tr>
                <td>Saldo al cierre</td>
                 <?php
                //vamos a inicializar la row
                $saldoalcierre=[];initializeRubtoEEPN($saldoalcierre);              
                showRowEEPN($saldoalcierre);
                ?>
            </tr>
        </tbody>
    </table>
</div><!--Estado de evolucion de patrimonio neto-->
<div class="index" id="divContenedorNotaSituacionPatrimonial">
    <h2>Notas del Estado de Situacion Patrimonial</h2>
    <table id="tblnotas"  class="tbl_border tblEstadoContable" cellspacing="0" style="">
        <thead>
        <tr class="trnoclickeable">
            <th colspan="20">
                <h3>Notas a los Estados Contables al 31/12/2016 comparativo con el Ejercicio Anterior</h3>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        $numeroDeNota = 1;
        $totalCajayBancos = [];
        ?>
        <tr class="trnoclickeable">
            <?php
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
            ?>
        </tr>
        <!--
        <tr class="trnoclickeable">
            <td colspan="20">Nota <?php //echo $numeroDeNota; ?>: Inversiones</h3>**TEXTO*</td>
            <?php //$numeroDeNota++; ?>
        </tr>
        <tr class="trnoclickeable">
            <td colspan="20">Composicion y Evolucion del Rubro</br>(Ver Anexo Inversiones)</td>
        </tr>-->
        <tr>
        <?php            
            $totalCreditosxVentas = [];
            //$arrayPrefijos['Creditos por Ventas']=[];
            //$arrayPrefijos['Creditos por Ventas']['prefijocorriente']='11030';
            //$arrayPrefijos['Creditos por Ventas']['prefijonocorriente']='12030';
            //$totalCreditosxVentas = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Creditos por Ventas",$fechaInicioConsulta,$fechaFinConsulta);
            ?>    
        </tr>
        <?php
        $totalOtrosCreditos = [];
        ?>
        <tr class="trnoclickeable">
                <?php
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
                
                $arrayPrefijos['Ganancia Mín. Presunta - Credi']=[];
                $arrayPrefijos['Ganancia Mín. Presunta - Credi']['prefijocorriente']='1104032';
                $arrayPrefijos['Ganancia Mín. Presunta - Credi']['prefijonocorriente']='1204032';
                
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
                
                $arrayPrefijos['Otros Créditos']=[];
                $arrayPrefijos['Otros Créditos']['prefijocorriente']='110406';
                $arrayPrefijos['Otros Créditos']['prefijonocorriente']='120406';
                
                $arrayPrefijos['Créditos Varios']=[];
                $arrayPrefijos['Créditos Varios']['prefijocorriente']='110407';
                $arrayPrefijos['Créditos Varios']['prefijonocorriente']='120407';
                
                $arrayPrefijos['Anticipo a Proveedores']=[];
                $arrayPrefijos['Anticipo a Proveedores']['prefijocorriente']='110408';
                $arrayPrefijos['Anticipo a Proveedores']['prefijonocorriente']='120408';
                
                $arrayPrefijos['Previsiones Otros Creditos']=[];
                $arrayPrefijos['Previsiones Otros Creditos']['prefijocorriente']='110409';
                $arrayPrefijos['Previsiones Otros Creditos']['prefijonocorriente']='120409';
                
                $totalOtrosCreditos = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Otros Creditos",$fechaInicioConsulta,$fechaFinConsulta);
            ?>
        </tr>
        <?php
        $totalBienesdeCambio = [];
        ?>
        <tr class="trnoclickeable">
                <?php
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
                $arrayPrefijos['Otros bienes de cambio']['prefijocorriente']='1105060';
                $arrayPrefijos['Otros bienes de cambio']['prefijonocorriente']='1205060';
                              
                $totalBienesdeCambio = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Bienes de Cambio",$fechaInicioConsulta,$fechaFinConsulta);
            ?>
        </tr>
        <?php
        $totalOtrosActivos = [];
        ?>
        <tr class="trnoclickeable">
                <?php
                $arrayPrefijos=[];
                
                $arrayPrefijos['Otros Activos Corrientes']=[];
                $arrayPrefijos['Otros Activos Corrientes']['prefijocorriente']='110600';
                $arrayPrefijos['Otros Activos Corrientes']['prefijonocorriente']='110600';
                
                $totalOtrosActivos = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Otros Activos",$fechaInicioConsulta,$fechaFinConsulta);
            ?>
        </tr>
        <?php
        $totalLlavenegocio = [];
        ?>
        <tr class="trnoclickeable">
                <?php
                $arrayPrefijos=[];
                
                $arrayPrefijos['Llave de negocio']=[];
                $arrayPrefijos['Llave de negocio']['prefijocorriente']='110700';
                $arrayPrefijos['Llave de negocio']['prefijonocorriente']='110700';
                              
                $totalLlavenegocio = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Llave de Negocio",$fechaInicioConsulta,$fechaFinConsulta);
            ?>
        </tr>
        <?php
        $totalBienesdeUso = [];
        ?>
        <tr class="trnoclickeable">
            <td colspan="20">Nota <?php echo $numeroDeNota; ?>: Bienes de Uso</h3>Comprosicion y Evolucion del Rubro</br>(Ver Anexo I Bienes de Uso)</td>
            <?php $numeroDeNota++; ?>
        </tr>
         <?php
         $totalparticipacionensociedades=[];
         $rowTituloBienDeUso='
        Informacion a revelar sobre participaciones Permanentes en Sociedades</br>**TEXTO**
        <tr class="trnoclickeable">
            <td colspan="20">Informacion sobre la aplicacion del metodo de "Valuacion Patrimonial Proporcional</br>**TEXTO**</td>
        </tr>';?>
        <tr class="trnoclickeable">
                <?php
                $arrayPrefijos=[];
                $arrayPrefijos['Participacion en Sociedades']=[];
                $arrayPrefijos['Participacion en Sociedades']['TituloRubro']=$rowTituloBienDeUso;
                $arrayPrefijos['Participacion en Sociedades']['prefijonocorriente']='120700';
                                                      
                $totalparticipacionensociedades = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Participacion en sociedades",$fechaInicioConsulta,$fechaFinConsulta);
            ?>
        </tr>
        <tr class="trnoclickeable">
            <td colspan="20">Nota <?php echo $numeroDeNota; ?>: Activos Intangibleso</h3>Comprosicion y Evolucion del Rubro</br>(Ver Anexo)</td>
            <?php $numeroDeNota++; ?>
        </tr>
        <?php
                $totalDeudasComerciales=[];
                ?>
        <tr class="trnoclickeable">
                <?php
               /* 
                $arrayPrefijos=[];
                $arrayPrefijos['Deudas Comerciales']=[];
                $arrayPrefijos['Deudas Comerciales']['prefijocorriente']='210100';
                $arrayPrefijos['Deudas Comerciales']['prefijonocorriente']='220100';
                
                $arrayPrefijos['Acredores']=[];
                $arrayPrefijos['Acredores']['prefijocorriente']='210100';
                $arrayPrefijos['Acredores']['prefijonocorriente']='220100';
                                                      
                $totalDeudasComerciales = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Caja y Bancos",$fechaInicioConsulta,$fechaFinConsulta);
            */?>
        </tr>
        <?php
                $totalPrestamos=[];
                //FALTA ESTO!!!
                
                $totalremuneracionycargassociales=[];
                ?>
        <tr class="trnoclickeable">
                <?php
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
                ?>
        </tr>
        <?php
                $totalcargasfiscales=[];
                ?>
         <tr class="trnoclickeable">
                <?php
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
                $arrayPrefijos['Ingresos Brutos - Deudas']['prefijonocorriente']='2204011';
                
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
                ?>
        </tr>
        <?php
                $totalanticipodeclientes=[];
                ?>
        <tr class="trnoclickeable">
                <?php
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
                ?>
        </tr>
        <?php
                $totaldividendosapagar=[];
                ?>
        <tr class="trnoclickeable">
                <?php
                $arrayPrefijos=[];
                $arrayPrefijos['Dividendos a Pagar']=[];
                $arrayPrefijos['Dividendos a Pagar']['prefijocorriente']='2106';
                $arrayPrefijos['Dividendos a Pagar']['prefijonocorriente']='2206';
                
                $totaldividendosapagar = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Dividendos a Pagar",$fechaInicioConsulta,$fechaFinConsulta);
                ?>
        </tr>
         <?php
                $totalotrasdeudas=[];
                ?>
        <tr class="trnoclickeable">
                <?php
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
                
                $arrayPrefijos['Acreedores']=[];
                $arrayPrefijos['Acreedores']['prefijocorriente']='210707';
                $arrayPrefijos['Acreedores']['prefijonocorriente']='220707';
                
                $totalotrasdeudas = mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$numeroDeNota,"Otras Deudas",$fechaInicioConsulta,$fechaFinConsulta);
                ?>
        </tr>
         <?php
                $totalprevisiones=[];
                ?>
        <tr class="trnoclickeable">
            <?php
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
        </tr>
        </tbody>
    </table>
</div><!--Estado de NOTAS de Situacion Patrimonialo-->
<div class="index" id="divContenedorAnexoIBienesdeUso">
    <h2>Anexo I de Bienes de Uso correspondiente al ejercicio finalizado el 31/12/2016 comparativo con el ejercicio anterior</h2>
    <table id="tblanexoIBienesdeuso"  class="tbl_border tblEstadoContable" cellspacing="0" style="">
        <tr>
            <td rowspan="2">Rubros</td>
            <td colspan="8">Valores Originales</td>
            <td colspan="6">Depreciacion</td>
            <td colspan="2">Valor Neto al Cierre</td>
        </tr>
        <tr>
            <td>Al Inicio</td>
            <td>Altas</td>
            <td>Transferencias</td>
            <td>Bajas</td>
            <td>Revaluo</td>
            <td>Desvalorizacion</td>
            <td>Recupero Desvalorizacion</td>
            <td>Al Cierre</td>
            <td>Al Inicio</td>
            <td>Bajas</td>
            <td>Del Ejercicio</td>
            <td>Desvalorizacion</td>
            <td>Recupero Desvalorizacion</td>
            <td>Al Cierre</td>
            <td>Ejercicio Actual</td>
            <td>Ejercicio Anterior</td>
        </tr>
         <?php
        $arrayPrefijos=[];
        $arrayPrefijos['Inmuebles']=[];
        $arrayPrefijos['Inmuebles']['prefijocorriente']='1206010';
        
        $arrayPrefijos['Rodados']=[];
        $arrayPrefijos['Rodados']['prefijocorriente']='1206020';
        
        $arrayPrefijos['Instalaciones']=[];
        $arrayPrefijos['Instalaciones']['prefijocorriente']='1206030';
        
        $arrayPrefijos['Muebles y Útiles']=[];
        $arrayPrefijos['Muebles y Útiles']['prefijocorriente']='1206040';
        
        $arrayPrefijos['Maquinarias']=[];
        $arrayPrefijos['Maquinarias']['prefijocorriente']='1206050';
        
        $arrayPrefijos['Activos Biológicos']=[];
        $arrayPrefijos['Activos Biológicos']['prefijocorriente']='1206060';
        
        

        $totalBienesDeUso = mostrarBienDeUso($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,$fechaInicioConsulta,$fechaFinConsulta);            
        ?>
    </table>
</div>
<div class="index" id="divContenedorSituacionPatrimonial">
    <table class="tbl_border tblEstadoContable" collspace="0">
        <tr>
            <td colspan="3"><!--Activo Corriente-->
                <table class="tbl_border tblEstadoContable" collspace="0">
                    <tr>
                        <th colspan="2"></th>
                        <th style="width: 80px">Actual</th>
                        <th style="width: 80px">Anterior</th>
                    </tr>
                    <tr>
                        <th colspan="2">
                            Activo
                        </th>
                        <th style="width: 80px"></th>
                        <th style="width: 80px"></th>
                    </tr>
                    <tr>
                        <th colspan="2">
                            Activo Corriente
                        </th>
                        <th style="width: 80px"></th>
                        <th style="width: 80px"></th>
                    </tr>
                    <?php
                    $totalActivoCorriente=[];
                    if(isset($totalCajayBancos['numeronota'])){
                        showRowESP($totalCajayBancos,'Cajas y Bancos',$fechaInicioConsulta,$totalActivoCorriente,'corriente');
                    }
                    if(isset($totalCreditosxVentas['numeronota'])){
                        showRowESP($totalCreditosxVentas,'Creditos por Ventas',$fechaInicioConsulta,$totalActivoCorriente,'corriente');
                    }
                    if(isset($totalOtrosCreditos['numeronota'])){
                        showRowESP($totalOtrosCreditos,'Otros Creditos',$fechaInicioConsulta,$totalActivoCorriente,'corriente');
                    }
                    if(isset($totalBienesdeCambio['numeronota'])){
                        showRowESP($totalBienesdeCambio,'Bienes de Cambio',$fechaInicioConsulta,$totalActivoCorriente,'corriente');
                    }
                    if(isset($totalOtrosActivos['numeronota'])){
                        showRowESP($totalOtrosActivos,'Otros Activos',$fechaInicioConsulta,$totalActivoCorriente,'corriente');
                    }
                    ?>
                    <tr>
                        <td colspan="2">Subtotal Activo Corriente</td>
                        <td><?php echo $totalActivoCorriente[$periodoActual]?></td>
                        <td><?php echo $totalActivoCorriente[$periodoPrevio]?></td>
                    </tr>
                    <?php
                    if(isset($totalLlavenegocio['numeronota'])){
                        showRowESP($totalLlavenegocio,'Llave de Negocio',$fechaInicioConsulta,$totalActivoCorriente,'corriente');
                    }
                    ?>
                    <tr>
                        
                    </tr>
                </table>
            </td>     
            <td colspan="3"><!--Pasivo Corriente-->
                <table class="tbl_border tblEstadoContable" collspace="0">
                    <tr>
                        <th colspan="2"></th>
                        <th style="width: 80px">Actual</th>
                        <th style="width: 80px">Anterior</th>
                    </tr>
                    <tr>
                        <th colspan="2">
                            Pasivo
                        </th>
                        <th style="width: 80px"></th>
                        <th style="width: 80px"></th>
                    </tr>
                    <tr>
                        <th colspan="2">
                            Pasivo Corriente
                        </th>
                        <th style="width: 80px"></th>
                        <th style="width: 80px"></th>
                    </tr>
                    <?php
                    $totalPasivoCorriente=[];
                    if(isset($totalDeudasComerciales['numeronota'])){
                        showRowESP($totalDeudasComerciales,'Deudas Comerciales',$fechaInicioConsulta,$totalPasivoCorriente,'corriente');
                    }
                    if(isset($totalPrestamos['numeronota'])){
                        showRowESP($totalPrestamos,'Prestamos',$fechaInicioConsulta,$totalPasivoCorriente,'corriente');
                    }
                    if(isset($totalremuneracionycargassociales['numeronota'])){
                        showRowESP($totalremuneracionycargassociales,'Remunerac. y Cargas Sociales',$fechaInicioConsulta,$totalPasivoCorriente,'corriente');
                    }
                    if(isset($totalcargasfiscales['numeronota'])){
                        showRowESP($totalcargasfiscales,'Cargas Fiscales',$fechaInicioConsulta,$totalPasivoCorriente,'corriente');
                    }
                    if(isset($totalanticipodeclientes['numeronota'])){
                        showRowESP($totalanticipodeclientes,'Anticipos de Clientes',$fechaInicioConsulta,$totalPasivoCorriente,'corriente');
                    }
                    if(isset($totaldividendosapagar['numeronota'])){
                        showRowESP($totaldividendosapagar,'Dividendos a pagar',$fechaInicioConsulta,$totalPasivoCorriente,'corriente');
                    }
                    if(isset($totalotrasdeudas['numeronota'])){
                        showRowESP($totalotrasdeudas,'Otras deudas',$fechaInicioConsulta,$totalPasivoCorriente,'corriente');
                    }
                    ?>
                    <tr>
                        <td colspan="2">Total deudas</td>
                        <td><?php echo $totalPasivoCorriente[$periodoActual]?></td>
                        <td><?php echo $totalPasivoCorriente[$periodoPrevio]?></td>
                    </tr>
                    <?php
                    if(isset($totalprevisiones['numeronota'])){
                        showRowESP($totalprevisiones,'Previsiones',$fechaInicioConsulta,$totalPasivoCorriente,'corriente');
                    }
                    ?>
                </table>
            </td>
        </tr>
        <tr>
            <td>Total del Activo Corriente</td>
            <td style="width: 80px;"><?php echo $totalActivoCorriente[$periodoActual]?></td>
            <td style="width: 80px;"><?php echo $totalActivoCorriente[$periodoPrevio]?></td>
            <td>Total del Pasivo Corriente</td>
            <td style="width: 80px;"><?php echo $totalPasivoCorriente[$periodoActual]?></td>
            <td style="width: 80px;"><?php echo $totalPasivoCorriente[$periodoPrevio]?></td>
        </tr>
        <tr>
            <td colspan="3"><!--Activo NO Corriente-->
                <table class="tbl_border tblEstadoContable" collspace="0">
                    <tr>
                        <th colspan="2">
                            Activo no Corriente
                        </th>
                        <td style="width: 80px"></th>
                        <th style="width: 80px"></th>
                    </tr>
                    <?php
                    $totalActivoNOCorriente=[];
                    if(isset($totalCreditosxVentas['numeronota'])){
                        showRowESP($totalCreditosxVentas,'Creditos por Ventas',$fechaInicioConsulta,$totalActivoNOCorriente,'nocorriente');
                    }
                    if(isset($totalOtrosCreditos['numeronota'])){
                        showRowESP($totalOtrosCreditos,'Otros Creditos',$fechaInicioConsulta,$totalActivoNOCorriente,'nocorriente');
                    }
                    if(isset($totalBienesdeCambio['numeronota'])){
                        showRowESP($totalBienesdeCambio,'Bienes de Cambio',$fechaInicioConsulta,$totalActivoNOCorriente,'nocorriente');
                    }
                    if(isset($totalparticipacionensociedades['numeronota'])){
                        showRowESP($totalparticipacionensociedades,'Participacion en Sociedades',$fechaInicioConsulta,$totalActivoNOCorriente,'nocorriente');
                    }
                    /*--if(isset($totalBienesdeCambio['numeronota'])){
                        showRowESP($totalBienesdeCambio,'Inversiones',$fechaInicioConsulta,$totalActivoCorriente,'nocorriente');
                    }*/
                    /*--if(isset($totalBienesdeCambio['numeronota'])){
                        showRowESP($totalBienesdeCambio,'Activos Intangibles',$fechaInicioConsulta,$totalActivoCorriente,'nocorriente');
                    }*/
                    if(isset($totalOtrosActivos['numeronota'])){
                        showRowESP($totalOtrosActivos,'Otros Activos',$fechaInicioConsulta,$totalActivoNOCorriente,'nocorriente');
                    }
                    ?>
                    <tr>
                        <td colspan="2">Subtotal Activo no Corriente</td>
                        <td><?php echo $totalActivoNOCorriente[$periodoActual]?></td>
                        <td><?php echo $totalActivoNOCorriente[$periodoPrevio]?></td>
                    </tr>
                    <?php
                    if(isset($totalLlavenegocio['numeronota'])){
                        showRowESP($totalLlavenegocio,'Llave de Negocio',$fechaInicioConsulta,$totalActivoNOCorriente,'nocorriente');
                    }
                    ?>
                </table>
            </td>
            <td colspan="3"><!--Pasivo NO Corriente-->
                <table class="tbl_border tblEstadoContable" collspace="0">
                    <tr>
                        <th colspan="2">
                            Pasivo no Corriente
                        </th>
                        <th style="width: 80px"></th>
                        <th style="width: 80px"></th>
                    </tr>
                     <?php
                    $totalPasivoNOCorriente=[];
                    if(isset($totalDeudasComerciales['numeronota'])){
                        showRowESP($totalDeudasComerciales,'Deudas Comerciales',$fechaInicioConsulta,$totalPasivoNOCorriente,'nocorriente');
                    }
                    if(isset($totalPrestamos['numeronota'])){
                        showRowESP($totalPrestamos,'Prestamos',$fechaInicioConsulta,$totalPasivoNOCorriente,'nocorriente');
                    }
                    if(isset($totalremuneracionycargassociales['numeronota'])){
                        showRowESP($totalremuneracionycargassociales,'Remunerac. y Cargas Sociales',$fechaInicioConsulta,$totalPasivoNOCorriente,'nocorriente');
                    }
                    if(isset($totalcargasfiscales['numeronota'])){
                        showRowESP($totalcargasfiscales,'Cargas Fiscales',$fechaInicioConsulta,$totalPasivoNOCorriente,'nocorriente');
                    }
                    if(isset($totalanticipodeclientes['numeronota'])){
                        showRowESP($totalanticipodeclientes,'Anticipos de Clientes',$fechaInicioConsulta,$totalPasivoNOCorriente,'nocorriente');
                    }
                    if(isset($totaldividendosapagar['numeronota'])){
                        showRowESP($totaldividendosapagar,'Dividendos a pagar',$fechaInicioConsulta,$totalPasivoNOCorriente,'nocorriente');
                    }
                    if(isset($totalotrasdeudas['numeronota'])){
                        showRowESP($totalotrasdeudas,'Otras deudas',$fechaInicioConsulta,$totalPasivoNOCorriente,'nocorriente');
                    }
                    '';
                    ?>
                    <tr>
                        <td colspan="2">Total deudas</td>
                        <td><?php echo $totalPasivoNOCorriente[$periodoActual]?></td>
                        <td><?php echo $totalPasivoNOCorriente[$periodoPrevio]?></td>
                    </tr>
                    <?php
                    if(isset($totalprevisiones['numeronota'])){
                        showRowESP($totalprevisiones,'Previsiones',$fechaInicioConsulta,$totalPasivoNOCorriente,'nocorriente');
                    }
                    '';
                     ?>
                    <tr>
                       
                    </tr>
                    <tr>
                        
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td rowspan="2" colspan="3"></td>
            <td>Total del Pasivo no Corriente</td>
            <td><?php echo $totalPasivoNOCorriente[$periodoActual]?></td>
            <td><?php echo $totalPasivoNOCorriente[$periodoPrevio]?></td>
        </tr>
        <tr>
            <td>Total del Pasivo</td>
            <td><?php 
            $pasivoActual = $totalPasivoCorriente[$periodoActual]+$totalPasivoNOCorriente[$periodoActual];
            $pasivoPrevio = $totalPasivoCorriente[$periodoPrevio]+$totalPasivoNOCorriente[$periodoPrevio];
                        echo $pasivoActual?>
            </td>
            <td><?php   echo $pasivoPrevio?></td>
        </tr>
        <tr>
            <td>Total de Activo no Corriente</td>
            <td style="width: 80px;"><?php echo $totalActivoNOCorriente[$periodoActual]?></td>
            <td style="width: 80px;"><?php echo $totalActivoNOCorriente[$periodoPrevio]?></td>
            <td>Patrimonio Neto (Segun E. correspondiente)</td>
            <td style="width: 80px;"><?php echo $saldoalcierre['totalresultadosacumulados'];?></td>
            <td style="width: 80px;"><?php echo $saldoalcierre['totalanterior']?></td>
        </tr>
        <tr>
            <td>Total de Activo</td>
            <td style="width: 80px;"><?php echo $totalActivoCorriente[$periodoActual]+$totalActivoNOCorriente[$periodoActual];?></td>
            <td style="width: 80px;"><?php echo $totalActivoCorriente[$periodoPrevio]+$totalActivoNOCorriente[$periodoPrevio];?></td>
            <td>Total Pasivo y Patrimonio Neto</td>
            <td style="width: 80px;"><?php echo $saldoalcierre['totalresultadosacumulados']+$pasivoActual;?></td>
            <td style="width: 80px;"><?php echo $saldoalcierre['totalanterior']+$pasivoPrevio?></td>
        </tr>
    </table>
</div> <!--Estado de Situacion Patrimonial-->
<div class="index" id="divContenedorFlujoEfectivo">
    
</div><!--Estado de Flujo de efectivo-->
<div class="index" id="divContenedorNotaFlujoEfectivo">
    
</div><!--Estado de NOTAS de Flujo de efectivo-->
<?php
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
function sumarCuentasEnPeriodo($arrayCuentasxPeriodos,$arrayCuentas,$periodoparasumar,$keysCuentas){
    $total = 0;
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
                $total += $arrayCuentasxPeriodos[$numeroCuenta][$periodoparasumar];
            }
        }
    }
    return $total;
}
function showRowEEPN(&$rowInfo){
    ?>
    <td>
        <?php
        echo number_format($rowInfo['capitalsocial'], 2, ",", ".");
        ?>
    </td>
    <td>
        <?php
        echo number_format($rowInfo['ajustedelcapital'], 2, ",", ".");
        ?>
    </td>
    <td>
        <?php
        echo number_format($rowInfo['aportesirrevocables'], 2, ",", ".");
        ?>
    </td>
    <td>
        <?php
        echo number_format($rowInfo['primadeemision'], 2, ",", ".");
        ?>
    </td>
    <td>
        <?php
       $rowInfo['totalaportedepropietarios'] = $rowInfo['capitalsocial'] + $rowInfo['ajustedelcapital'] 
                + $rowInfo['aportesirrevocables'] + $rowInfo['primadeemision'];
        echo number_format($rowInfo['totalaportedepropietarios'], 2, ",", ".");
        ?>
    </td>
     <td>
        <?php
        echo number_format($rowInfo['legal'], 2, ",", ".");
        ?>
    </td>
    <td>
        <?php
        echo number_format($rowInfo['otrasreservas'], 2, ",", ".");
        ?>
    </td>
    <td>
        <?php                
        $rowInfo['totaldegananciasreservadas'] = $rowInfo['legal'] + $rowInfo['otrasreservas'];
        echo number_format($rowInfo['totaldegananciasreservadas'], 2, ",", ".");
        ?>
    </td>
    <td>
        <?php
        echo number_format($rowInfo['podiferenciasdeconversion'], 2, ",", ".");
        ?>
    </td>
    <td>
        <?php
        $rowInfo['totalresultadosdiferidos'] = $rowInfo['podiferenciasdeconversion'];
        echo number_format($rowInfo['totalresultadosdiferidos'], 2, ",", ".");
        ?>
    </td>
    <td>
        <?php
        echo number_format($rowInfo['resultadosnoasignados'], 2, ",", ".");
        ?>
    </td>
    <td>
        <?php
        $rowInfo['totalresultadosacumulados'] = $rowInfo['resultadosnoasignados'] + $rowInfo['totalresultadosdiferidos']
                + $rowInfo['totaldegananciasreservadas'];
        echo number_format($rowInfo['totalresultadosacumulados'], 2, ",", ".");
        ?>
    </td>
    <td>
        <?php
        $rowInfo['totalactual'] = $rowInfo['totalaportedepropietarios'] + $rowInfo['totalresultadosacumulados'];
        echo number_format($rowInfo['totalactual'], 2, ",", ".");
        ?>
    </td>
    <td>
        <?php

        echo number_format($rowInfo['totalanterior'], 2, ",", ".");
        ?>
    </td>
    <?php
}
function showRowESP($nota,$nombreNota,$fechaInicioConsulta,&$total,$tipo){
    $periodoPrevio =  date('Y', strtotime($fechaInicioConsulta." -1 Years"));
    $periodoActual = date('Y', strtotime($fechaInicioConsulta));
    $Actual = isset($nota[$tipo][$periodoActual])?$nota[$tipo][$periodoActual]:0;
    $Previo = isset($nota[$tipo][$periodoPrevio])?$nota[$tipo][$periodoPrevio]:0;
    ?>
    <tr>
        <td>
            <?php
            echo $nombreNota;
            ?>
        </td>   
        <td align="right" style="width:60px;">
            <label>
            Nota: <?php
            echo $nota['numeronota'];
            ?></label>
        </td>   
        <td>
            <?php
            echo number_format($Actual, 2, ",", ".");
            ?>
        </td>
        <td>
            <?php
            echo number_format($Previo, 2, ",", ".");
            ?>
        </td>    
    </tr>
    <?php
    if(!isset($total[$periodoActual])){
        $total[$periodoActual]=0;
        $total[$periodoPrevio]=0;
    }
    $total[$periodoActual]+=$Actual;
    $total[$periodoPrevio]+=$Previo;
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
            <th colspan="20"><?php echo $nombreprefijo; ?></th>
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
            ?>
            <tr>
                <td><?php echo $arrayCuentasxPeriodos[$numerodecuenta]['nombrecuenta'];?></td>
                <td><?php echo number_format($alinicio, 2, ",", ".") ?></td>
                <td><?php echo number_format($altas, 2, ",", ".") ?></td>
                <td><?php echo number_format($transferencias, 2, ",", ".") ?></td>
                <td><?php echo number_format($bajas, 2, ",", ".") ?></td>
                <td><?php echo number_format($revaluo, 2, ",", ".") ?></td>
                <td><?php echo number_format($desvalorizacion, 2, ",", ".") ?></td>
                <td><?php echo number_format($recuperodesvalorizacion, 2, ",", ".") ?></td>
                <td><?php echo number_format($alcierre, 2, ",", ".") ?></td>
                <td><?php echo number_format($depreciacionalinicio, 2, ",", ".") ?></td>
                <td><?php echo number_format($depreciacionbajas, 2, ",", ".") ?></td>
                <td><?php echo number_format($depreciaciondelejercicio, 2, ",", ".") ?></td>
                <td><?php echo number_format($depreciaciondesvalorizacion, 2, ",", ".") ?></td>
                <td><?php echo number_format($depreciacionrecuperodesvalorizacion, 2, ",", ".") ?></td>
                <td><?php echo number_format($depreciacionalcierre, 2, ",", ".") ?></td>
                <td><?php echo number_format($ejercicioActual, 2, ",", ".") ?></td>
                <td><?php echo number_format($ejercicioAnterior, 2, ",", ".") ?></td>
            </tr>
            <?php
           
        }
         ?>
            <tr>
                <th> Total <?php echo $nombreprefijo;?></th>
                <td><?php echo number_format($totalPrefijo['alinicio'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalPrefijo['altas'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalPrefijo['transferencias'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalPrefijo['bajas'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalPrefijo['revaluo'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalPrefijo['desvalorizacion'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalPrefijo['recuperodesvalorizacion'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalPrefijo['alcierre'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalPrefijo['depreciacionalinicio'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalPrefijo['depreciacionbajas'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalPrefijo['depreciaciondelejercicio'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalPrefijo['depreciaciondesvalorizacion'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalPrefijo['depreciacionrecuperodesvalorizacion'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalPrefijo['depreciacionalcierre'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalPrefijo['ejercicioActual'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalPrefijo['ejercicioAnterior'], 2, ",", ".") ?></td>
            </tr>
           <?php
        
    }
    ?>
            <tr>
                <th>Total Bienes de Uso</th>
                <td><?php echo number_format($totalNota['alinicio'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalNota['altas'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalNota['transferencias'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalNota['bajas'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalNota['revaluo'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalNota['desvalorizacion'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalNota['recuperodesvalorizacion'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalNota['alcierre'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalNota['depreciacionalinicio'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalNota['depreciacionbajas'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalNota['depreciaciondelejercicio'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalNota['depreciaciondesvalorizacion'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalNota['depreciacionrecuperodesvalorizacion'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalNota['depreciacionalcierre'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalNota['ejercicioActual'], 2, ",", ".") ?></td>
                <td><?php echo number_format($totalNota['ejercicioAnterior'], 2, ",", ".") ?></td>
            </tr>
           <?php
    return $totalNota;
}
function mostrarNotaDelGrupo($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,&$numeroDeNota,$fechaInicioConsulta,$fechaFinConsulta){
    //vamos a extender la funcionalidad de esta funcion para que abarque tmb mostrary no solo calcular
    //$numerofijo = "60101";
    //Una nota puede tener muchos prefijos y vamos a totalizar los prefijos por separado
    //y devolver el total de la nota.
    $mostrarTotal = false;
    $nombreNota = array_values($arrayPrefijos)[0]['nombrenota'][0];;
    
    $totalNota = [];
    $totalPrefijo = [];
    foreach ($arrayPrefijos as $prefijo => $valoresPrefijo) {       
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
                <td colspan="20">
                    <table id="<?php echo $nombreNota ?>" cellspacing="0">
                <?php
            }
            $mostrarTotal = true;?>
            <tr class="trnoclickeable">
                <th colspan="20">Nota <?php echo $numeroDeNota; ?>:  <?php echo $nombrePrefijo; ?></th>
            </tr>
            <tr>
                <th>Conceptos</th>
                <th colspan="2">Corriente</th>
            </tr>
            <tr>
                <th><?php echo $nombrePrefijo?></th>
                <th>Anteriort</th>
                <th>Actual</th>
            </tr>
            <?php
            $titleRow = "";
            foreach ($indexCuentasNumeroFijo as $index) {
                $numeroCuenta = $keysCuentas[$index];
                $titleRow="Cuentas incluidas en las notas: ".$numeroCuenta."/"
                ?>
                <tr>
                    <td title="<?php echo $titleRow ?>"><?php echo $arrayCuentasxPeriodos[$numeroCuenta]['nombrecuenta'] ?></td>
                <?php
                $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
                while($periodoAMostrar<=$fechaFinConsulta) {
                   $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                    if(!isset($totalNota[$periodoAImputar])) {
                        $totalNota[$periodoAImputar]=0;
                    }
                     if(!isset($totalPrefijo[$periodoAImputar])) {
                        $totalPrefijo[$periodoAImputar]=0;
                    }
                    echo '<td  class="numericTD" style="width:90px">' .
                        number_format($arrayCuentasxPeriodos[$numeroCuenta][$periodoAImputar], 2, ",", ".")
                        . "</td>";
                    $totalPrefijo[$periodoAImputar]+=$arrayCuentasxPeriodos[$numeroCuenta][$periodoAImputar];
                    $totalNota[$periodoAImputar]+=$arrayCuentasxPeriodos[$numeroCuenta][$periodoAImputar];
                    $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
                }
                 ?>
                </tr>
               <?php
            }
            ?>
            <tr>
                <th>Total <?php echo $nombrePrefijo ?></th>
                <?php
            $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
            while($periodoAMostrar<=$fechaFinConsulta) {
                $periodoAImputar = date('Y', strtotime($periodoAMostrar));
                echo '<th  class="numericTD">' .
                    number_format($totalPrefijo[$periodoAImputar], 2, ",", ".")
                    . "</th>";
                $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
            }
            ?>
            </tr>
            <?php
        }
    }
    if($mostrarTotal){ ?>
    <tr class="trnoclickeable">
        <th>Total de  <?php  echo $nombreNota; ?></th>
        <?php
        $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
        while($periodoAMostrar<=$fechaFinConsulta) {
            $periodoAImputar = date('Y', strtotime($periodoAMostrar));
            $totalPeriodo=0;
            $totalPeriodo += isset($totalNota[$periodoAImputar])?$totalNota[$periodoAImputar]:0;
//                $totalPeriodo += isset($totalVentasServicios[$periodoAImputar])?$totalVentasServicios[$periodoAImputar]:0;;
            echo '<th  class="numericTD">' .
                number_format($totalPeriodo, 2, ",", ".")
                . "</th>";
            $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
        }
        ?>
    </tr>
        <?php
        $totalNota['numeronota']=$numeroDeNota;
        $numeroDeNota ++;
         ?>
        </table>
        </td>
        <?php
    }
     
    return $totalNota;
   
}
function mostrarNotaDeESP($arrayCuentasxPeriodos,$arrayPrefijos,$keysCuentas,&$numeroDeNota,$nombreNota,$fechaInicioConsulta,$fechaFinConsulta){
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
    $periodoPrevio = date('Y', strtotime($fechaInicioConsulta."-1 Years"));
    $periodoActual =  date('Y', strtotime($fechaInicioConsulta));
    
    $mostrarTotal = false;
    $mostrarTotalCorriente = false;
    $mostrarTotalNoCorriente = false;
    
    $totalNota = [];    
    $totalNota['corriente'] = [];
    $totalNota['nocorriente'] = [];
        
    foreach ($arrayPrefijos as $nombreRubro => $valoresPrefijo) {      
        $mostrarCorriente = false;
        $mostrarNoCorriente = false;
        $totalPrefijo = [];
        $totalPrefijo['corriente'] = [];
        $totalPrefijo['nocorriente'] = [];


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
        if(count($indexCuentasNumeroFijoCorriente)!=0 || count($indexCuentasNumeroNOCorriente)!=0){ 
            if(!$mostrarTotal){
                ?>
                <td colspan="20">
                    <table id="<?php echo $nombreNota ?>" cellspacing="0">
                        <tr class="trnoclickeable">
                            <td colspan="20"><h3>Nota <?php echo $numeroDeNota.":".$nombreNota; ?></h3></td>
                        </tr>
                <?php
            }           
            $mostrarTotal = true;
            if(isset($valoresPrefijo['TituloRubro'])){ ?>
                 <tr class="trnoclickeable">
                    <td colspan="20"><?php echo $valoresPrefijo['TituloRubro']; ?></h3></td>
                </tr>
            <?php 
            }
            ?>
            <tr class="trnoclickeable">
                <th colspan="20">  <?php echo $nombrePrefijo; ?></td>
            </tr>
            <tr>
                <?php
                if($mostrarCorriente){
                ?>
                <td>Conceptos</td>
                <td colspan="2">Corriente</td>
                <?php
                }
                if($mostrarNoCorriente){
                ?>
                <td colspan="2">No Corriente</td>
                <?php } ?>
            </tr>
            <tr>
                <td><?php echo $nombrePrefijo ?></td>
                <?php
                if($mostrarCorriente){
                ?>
                <td>Actual</td>
                <td>Anterior</td>
                 <?php
                }
                if($mostrarNoCorriente){
                ?>
                <td>Actual</td>
                <td>Anterior</td>
                 <?php } ?>
            </tr>
            <?php
            if(!isset($totalNota['corriente'][$periodoPrevio])) {
                $totalNota['corriente'][$periodoPrevio]=0;
                $totalNota['corriente'][$periodoActual]=0;
                $totalNota['nocorriente'][$periodoPrevio]=0;
                $totalNota['nocorriente'][$periodoActual]=0;
            }
            if(!isset($totalPrefijo['corriente'][$periodoPrevio])) {
                $totalPrefijo['corriente'][$periodoPrevio]=0;
                $totalPrefijo['corriente'][$periodoActual]=0;
                $totalPrefijo['nocorriente'][$periodoPrevio]=0;
                $totalPrefijo['nocorriente'][$periodoActual]=0;
            }

            $titleRow = "";
            $numerosCuentaNoCorrienteYaMostrados=[];
            foreach ($indexCuentasNumeroFijoCorriente as $ki =>$index) {
                $numeroCuentaCorriente = $keysCuentas[$index];
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
                        $noCorrienteActual = $arrayCuentasxPeriodos[$numeroCuentaNoCorriente][$periodoActual];
                        $noCorrientePrevio = $arrayCuentasxPeriodos[$numeroCuentaNoCorriente][$periodoPrevio];
                    }                   
                }
                ?>
                <tr>
                    <td title="<?php echo $titleRow ?>"><?php echo $arrayCuentasxPeriodos[$numeroCuentaCorriente]['nombrecuenta'] ?></td>
                <?php
                    if($mostrarCorriente){
                        $mostrarTotalCorriente = true;
                        echo '<td  class="numericTD" style="width:90px">' .
                            number_format($arrayCuentasxPeriodos[$numeroCuentaCorriente][$periodoActual], 2, ",", ".")
                            . "</td>";
                        echo '<td  class="numericTD" style="width:90px">' .
                            number_format($arrayCuentasxPeriodos[$numeroCuentaCorriente][$periodoPrevio], 2, ",", ".")
                            . "</td>";
                        $totalPrefijo['corriente'][$periodoActual]+=$arrayCuentasxPeriodos[$numeroCuentaCorriente][$periodoActual];
                        $totalPrefijo['corriente'][$periodoPrevio]+=$arrayCuentasxPeriodos[$numeroCuentaCorriente][$periodoPrevio];
                        $totalNota['corriente'][$periodoActual]+=$arrayCuentasxPeriodos[$numeroCuentaCorriente][$periodoActual];
                        $totalNota['corriente'][$periodoPrevio]+=$arrayCuentasxPeriodos[$numeroCuentaCorriente][$periodoPrevio];
                    }
                    if($mostrarNoCorriente){
                        $mostrarTotalNoCorriente = true;
                        echo '<td  class="numericTD" style="width:90px">' .
                            number_format($noCorrienteActual, 2, ",", ".")
                            . "</td>";
                        echo '<td  class="numericTD" style="width:90px">' .
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
                    $CorrienteActual = $arrayCuentasxPeriodos[$numeroCuentaCorriente][$periodoActual];
                    $CorrientePrevio = $arrayCuentasxPeriodos[$numeroCuentaCorriente][$periodoPrevio];
                }          
                
                ?>
                <tr>
                    <td title="<?php echo $titleRow ?>"><?php echo $arrayCuentasxPeriodos[$numeroCuentaNoCorriente]['nombrecuenta'] ?></td>
                <?php
                    if($mostrarCorriente){
                        $mostrarTotalCorriente = true;
                        echo '<td  class="numericTD" style="width:90px">' .
                            number_format($CorrienteActual, 2, ",", ".")
                            . "</td>";
                        echo '<td  class="numericTD" style="width:90px">' .
                            number_format($CorrientePrevio, 2, ",", ".")
                            . "</td>";
                        $totalPrefijo['corriente'][$periodoActual]+=$CorrienteActual;
                        $totalPrefijo['corriente'][$periodoPrevio]+=$CorrientePrevio;
                        $totalNota['corriente'][$periodoActual]+=$CorrienteActual;
                        $totalNota['corriente'][$periodoPrevio]+=$CorrientePrevio;
                    }
                    if($mostrarNoCorriente){
                        $mostrarTotalNoCorriente = true;
                        echo '<td  class="numericTD" style="width:90px">' .
                            number_format($arrayCuentasxPeriodos[$numeroCuentaNoCorriente][$periodoActual], 2, ",", ".")
                            . "</td>";
                        echo '<td  class="numericTD" style="width:90px">' .
                            number_format($arrayCuentasxPeriodos[$numeroCuentaNoCorriente][$periodoPrevio], 2, ",", ".")
                            . "</td>";
                        $totalPrefijo['nocorriente'][$periodoActual]+=$arrayCuentasxPeriodos[$numeroCuentaNoCorriente][$periodoActual];
                        $totalPrefijo['nocorriente'][$periodoPrevio]+=$arrayCuentasxPeriodos[$numeroCuentaNoCorriente][$periodoPrevio];                    
                        $totalNota['nocorriente'][$periodoActual]+=$arrayCuentasxPeriodos[$numeroCuentaNoCorriente][$periodoActual];
                        $totalNota['nocorriente'][$periodoPrevio]+=$arrayCuentasxPeriodos[$numeroCuentaNoCorriente][$periodoPrevio];
                    }
                 ?>
                </tr>
               <?php
            }/*
            ?>
            <tr>
                <td><h4>Total <?php echo $nombrePrefijo ?></h4></td>
                <?php
                if($mostrarCorriente){
                    echo '<td  class="numericTD">' .
                        number_format($totalPrefijo['corriente'][$periodoActual], 2, ",", ".")
                        . "</td>";
                    echo '<td  class="numericTD">' .
                        number_format($totalPrefijo['corriente'][$periodoPrevio], 2, ",", ".")
                        . "</td>";     
                }
                if($mostrarNoCorriente){
                    echo '<td  class="numericTD">' .
                        number_format($totalPrefijo['nocorriente'][$periodoActual], 2, ",", ".")
                        . "</td>";
                    echo '<td  class="numericTD">' .
                        number_format($totalPrefijo['nocorriente'][$periodoPrevio], 2, ",", ".")
                        . "</td>";            
                }
            ?>
            </tr>
            <?php
             
             */
        }
        
    }
    if($mostrarTotal){ ?>
        <tr class="trnoclickeable">
            <td><h1>Total de  <?php  echo $nombreNota; ?></h1></td>
            <?php
                if($mostrarTotalCorriente){
                    echo '<td  class="numericTD">' .
                        number_format($totalNota['corriente'][$periodoActual], 2, ",", ".")
                        . "</td>";
                    echo '<td  class="numericTD">' .
                        number_format($totalNota['corriente'][$periodoPrevio], 2, ",", ".")
                        . "</td>";    
                }
                if($mostrarTotalNoCorriente){
                    echo '<td  class="numericTD">' .
                        number_format($totalNota['nocorriente'][$periodoActual], 2, ",", ".")
                        . "</td>";
                    echo '<td  class="numericTD">' .
                        number_format($totalNota['nocorriente'][$periodoPrevio], 2, ",", ".")
                       . "</td>";            
                }?>
        </tr>
            <?php
            $totalNota['numeronota']=$numeroDeNota;
            $numeroDeNota ++;
             ?>
    </table>
    </td>
    <?php
    }
    return $totalNota;
}
function mostrarCostoDeBienesVendidos($arrayCuentasxPeriodos,&$total,$titulo,$numerocuenta,$fechaInicioConsulta,$fechaFinConsulta){
    ?>
    <tr>
        <td>
            <?php echo $titulo ?>
        </td>
        <?php
        $periodoAMostrar = date('Y-m-d', strtotime($fechaInicioConsulta." -1 Years"));
        while($periodoAMostrar<=$fechaFinConsulta) {
            $periodoAImputar = date('Y', strtotime($periodoAMostrar));
            $totalPeriodo = isset($arrayCuentasxPeriodos[$numerocuenta][$periodoAImputar])?$arrayCuentasxPeriodos[$numerocuenta][$periodoAImputar]:0;
            echo '<td  class="numericTD">' .
                number_format($totalPeriodo, 2, ",", ".")
                . "</td>";
            if(!isset($total[$periodoAImputar])){
                $total[$periodoAImputar] = 0;//existen estos valores
            }
            $total[$periodoAImputar]+=$totalPeriodo;
            $periodoAMostrar = date('Y-m-d', strtotime($periodoAMostrar . " +1 Year"));
        }
        ?>
    </tr>    
    <?php
}
function mostrarNotasDeGastos($arrayCuentasxPeriodos,$nombreNota,$numerofijo,$fechaInicioConsulta,$fechaFinConsulta,&$totalAnexoII){
    
    $keysCuentas = array_keys($arrayCuentasxPeriodos);
    $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
     if(count($indexCuentas)!=0) {                   
        
    ?>
        <tr style="background-color: #91a7ff">
            <th colspan="20"><?php echo $nombreNota?></th>
        </tr>
        <?php
        foreach ($indexCuentas as $index) {
            $numeroCuenta = $keysCuentas[$index];
            echo '<tr>';
            echo '<td title="'.$numerofijo.'">'.$arrayCuentasxPeriodos[$numeroCuenta]['nombrecuenta'].'</td>' ;
            $mesAMostrar = date('Y', strtotime($fechaFinConsulta.'-01-01'));
            //como son dos estados vamos a mostrar cuando estemos en el ultimo y de ahi para abajo :S
            $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
            if(!isset($totalAnexoII[$mesAMostrar])) {
                $totalAnexoII[$mesAMostrar]=0;
            }
            $subtottalPeriodo = $arrayCuentasxPeriodos[$numeroCuenta][$periodoMesAMostrar];
            echo '<td  class="numericTD" style="width:90px">' .
                number_format($subtottalPeriodo*0.0, 2, ",", ".")
                . "</td>";
            echo '<td  class="numericTD" style="width:90px">' .
                number_format($subtottalPeriodo*0.25, 2, ",", ".")
                . "</td>";
            echo '<td  class="numericTD" style="width:90px">' .
                number_format($subtottalPeriodo*0.75, 2, ",", ".")
                . "</td>";
            echo '<td  class="numericTD" style="width:90px">' .
                number_format($subtottalPeriodo, 2, ",", ".")
                . "</td>";
            $totalAnexoII[$mesAMostrar]+=$subtottalPeriodo;
            //ahora vamos a mostrar el año anterior
            $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 -1 Year"));
            if(!isset($totalAnexoII[$mesAMostrar])) {
                $totalAnexoII[$mesAMostrar]=0;
            }
            $subtottalPeriodo = $arrayCuentasxPeriodos[$numeroCuenta][$mesAMostrar];
            $totalAnexoII[$mesAMostrar]+=$subtottalPeriodo;
            echo '<td  class="numericTD" style="width:90px">' .
                number_format($subtottalPeriodo, 2, ",", ".")
                . "</td>";
            echo "</tr>";
        }
        ?>
    <?php
     }
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