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
    <div id="tabSumasYSaldos" class="cliente_view_tab_active" onclick="CambiarTab('sumasysaldos');" style="width:22%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Balance de Sumas y Saldos</label>
    </div>
    <div id="tabEstadoDeResultados" class="cliente_view_tab" onclick="CambiarTab('estadoderesultado');" style="width:22%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Estado de resultados</label>
    </div>
    <div id="tabNotas" class="cliente_view_tab" onclick="CambiarTab('notas');" style="width:22%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Notas</label>
    </div>
    <div id="tabAnexos" class="cliente_view_tab" onclick="CambiarTab('anexos');" style="width:22%;">
        <label style="text-align:center;margin-top:5px;cursor:pointer" for="">Anexos</label>
    </div>
</div>
<div class="index" id="divContenedorBSyS" >
    <?php
    echo "<h2>Balance de Sumas y Saldos</h2>";
    echo "<h3>del periodo  01-01-".$fechaInicioConsulta." hasta 01-01-".$fechaFinConsulta."</h3>";
    ?>
    <table id="tblsys"  class="tbl_border" cellspacing="0">
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
        $arrayTotales=[];
        $arrayCuentasxPeriodos=[];/*En este array vamos a guardar los valores de cada cuenta
        con su periodo(asociado el valor al numero de cuenta)*/        
        foreach ($cliente['Cuentascliente'] as $cuentascliente){
            $numerodecuenta = $cuentascliente['Cuenta']['numero'];

            //si no hay movimientos para esta cuentacliente no la vamos a mostrar en el suma y saldos
            if(count($cuentascliente['Movimiento'])==0){
                continue;
            }
            $saldoCalculado = 0;
            $debes = 0;
            $haberes = 0;
            $arrayPeriodos = [];
            foreach ($cuentascliente['Movimiento'] as $movimiento){
                $periodoAImputar = date('Y', strtotime($movimiento['Asiento']['fecha']));
                if(!isset($arrayPeriodos[$periodoAImputar])){
                    $arrayPeriodos[$periodoAImputar]=[];
                    $arrayPeriodos[$periodoAImputar]['debes']=0;
                    $arrayPeriodos[$periodoAImputar]['haberes']=0;
                }
                if(!isset($arrayTotales[$periodoAImputar])){
                    $arrayTotales[$periodoAImputar]=[];
                    $arrayTotales[$periodoAImputar]['debes']=0;
                    $arrayTotales[$periodoAImputar]['haberes']=0;
                }
                if(!isset($arrayCuentasxPeriodos[$numerodecuenta])){
                    $arrayCuentasxPeriodos[$numerodecuenta] = [];
                    $arrayCuentasxPeriodos[$numerodecuenta]['nombrecuenta'] = $cuentascliente['Cuenta']['nombre'];
                }
                $arrayPeriodos[$periodoAImputar]['debes']+=$movimiento['debe'];
                $arrayPeriodos[$periodoAImputar]['haberes']+=$movimiento['haber'];
                $debes+= $movimiento['debe'];
                $haberes+= $movimiento['haber'];
                $saldoCalculado += $movimiento['debe'];
                $saldoCalculado -= $movimiento['haber'];
                $arrayTotales[$periodoAImputar]['debes']+= $movimiento['debe'];
                $arrayTotales[$periodoAImputar]['haberes']+= $movimiento['haber'];
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
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta){
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar.'-01-01'));
                    if(!isset($arrayPeriodos[$periodoMesAMostrar])){
                        $arrayPeriodos[$periodoMesAMostrar]=[];
                        $arrayPeriodos[$periodoMesAMostrar]['debes']=0;
                        $arrayPeriodos[$periodoMesAMostrar]['haberes']=0;
                    }
                    $saldo = $arrayPeriodos[$periodoMesAMostrar]['debes']-$arrayPeriodos[$periodoMesAMostrar]['haberes'];
                    echo '<td  class="numericTD">'.
                        number_format($arrayPeriodos[$periodoMesAMostrar]['debes']-$arrayPeriodos[$periodoMesAMostrar]['haberes'], 2, ",", ".")
                        ."</td>";
                    if(!isset($arrayCuentasxPeriodos[$numerodecuenta][$periodoMesAMostrar])){
                        $arrayCuentasxPeriodos[$numerodecuenta][$periodoMesAMostrar]=0;
                    }
                    $arrayCuentasxPeriodos[$numerodecuenta][$periodoMesAMostrar]=$saldo;
                    $mesAMostrar = date('Y', strtotime($mesAMostrar."-01-01 +1 Year"));

                }
                //                <td>
                //                    <?php echo number_format($debes, 2, ",", ".");
                //                </td>
                //                <td>
                //                    <?php echo number_format($haberes, 2, ",", ".");
                //                </td>
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
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<$fechaFinConsulta){
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar));
                    echo "<td >".$periodoMesAMostrar."</td>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar." +1 Year"));
                }
                ?>
                <!--                <td></td>-->
                <!--                <td></td>-->
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
<div class="index" id="divContenedorNotas">
    <?php
    echo "<h2>Notas del Estado de Resultado</h2>";
    ?>
    <table id="tblnotas"  class="tbl_border" cellspacing="0" style="width:600px;">
        <thead>
        <tr class="trnoclickeable">
            <td colspan="20" >
                Notas a los Estados Contables al 31/12/2016 comparativo con el Ejercicio Anterior
            </td>
        </tr>
        </thead>
        <tbody>
        <?php
        $numeroDeNota = 1;
        $keysCuentas = array_keys($arrayCuentasxPeriodos);
        $numerofijo = "60101";
        $indexCuentas60101 = array_keys(
            array_filter(
                $keysCuentas,
                function($var) use ($numerofijo){
                    return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                }
            )
        );
        if(count($indexCuentas60101)!=0){
            ?>
        <tr class="trnoclickeable">
            <td colspan="20"><h3>Nota <?php echo $numeroDeNota; ?>:  Ventas netas de bienes y servicios</h3></td>
        </tr>
        <?php
            $totalVentasBienes = [];
        ?>
        <tr class="trnoclickeable">
            <td colspan="20">
                <?php
                $totalVentasBienes = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$indexCuentas60101,"Ventas de Bienes",$numerofijo,$fechaInicioConsulta,$fechaFinConsulta);
                $totalVentasBienes['numeronota']=$numeroDeNota;
                $numeroDeNota ++;
                ?>
            </td>
        </tr>
        <tr class="trnoclickeable">
            <td>Total de Venta de Bienes y Servicios</td>
            <?php
            $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
            while($mesAMostrar<=$fechaFinConsulta) {
                $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                $totalPeriodo=0;
                $totalPeriodo += isset($totalVentasBienes[$periodoMesAMostrar])?$totalVentasBienes[$periodoMesAMostrar]:0;
//                $totalPeriodo += isset($totalVentasServicios[$periodoMesAMostrar])?$totalVentasServicios[$periodoMesAMostrar]:0;;
                echo '<td  class="numericTD">' .
                    number_format($totalPeriodo, 2, ",", ".")
                    . "</td>";
                $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
            }
            ?>
        </tr>
            <?php
        }
        $numerofijo = "601012";
        $indexCuentas601012 = array_keys(
            array_filter(
                $keysCuentas,
                function($var) use ($numerofijo){
                    return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                }
            )
        );
        if(count($indexCuentas601012)!=0){
            ?>
        <tr class="trnoclickeable">
            <td colspan="20"><h3>Nota <?php echo $numeroDeNota; ?>:  Reintegros</h3></td>
        </tr>
        <?php
            $totalReintegros = [];
        ?>
        <tr class="trnoclickeable">
            <td colspan="20">
                <?php
                $totalReintegros = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$indexCuentas601012,"Reintegros",$numerofijo,$fechaInicioConsulta,$fechaFinConsulta);
                $totalReintegros['numeronota']=$numeroDeNota;
                $numeroDeNota ++;?>
            </td>
        </tr>
        <tr class="trnoclickeable">
            <td>Total de Reintegros</td>
            <?php
            $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
            while($mesAMostrar<=$fechaFinConsulta) {
                $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                $totalPeriodo=0;
                $totalPeriodo += isset($totalReintegros[$periodoMesAMostrar])?$totalReintegros[$periodoMesAMostrar]:0;
//                $totalPeriodo += isset($totalVentasServicios[$periodoMesAMostrar])?$totalVentasServicios[$periodoMesAMostrar]:0;;
                echo '<td  class="numericTD">' .
                    number_format($totalPeriodo, 2, ",", ".")
                    . "</td>";
                $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
            }
            ?>
        </tr>
            <?php
        }
        $numerofijo = "601013";
        $indexCuentas601013 = array_keys(
            array_filter(
                $keysCuentas,
                function($var) use ($numerofijo){
                    return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                }
            )
        );
        if(count($indexCuentas601013)!=0){
            ?>
            <tr class="trnoclickeable">
                <td colspan="20"><h3>Nota <?php echo $numeroDeNota; ?>:  Desgravaciones</h3></td>
            </tr>
            <?php
            $totalDesgravaciones = [];
            ?>
            <tr class="trnoclickeable">
                <td colspan="20">
                    <?php
                    $totalDesgravaciones = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$indexCuentas601013,"Reintegros",$numerofijo,$fechaInicioConsulta,$fechaFinConsulta);
                    $totalDesgravaciones['numeronota']=$numeroDeNota;
                    $numeroDeNota ++;?>
                </td>
            </tr>
            <tr class="trnoclickeable">
                <td>Total de Reintegros</td>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo=0;
                    $totalPeriodo += isset($totalDesgravaciones[$periodoMesAMostrar])?$totalDesgravaciones[$periodoMesAMostrar]:0;
//                $totalPeriodo += isset($totalVentasServicios[$periodoMesAMostrar])?$totalVentasServicios[$periodoMesAMostrar]:0;;
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
                ?>
            </tr>
            <?php
        }
        $numerofijo = "601014";
        $indexCuentas601014 = array_keys(
            array_filter(
                $keysCuentas,
                function($var) use ($numerofijo){
                    return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                }
            )
        );
        if(count($indexCuentas601014)!=0){
            ?>
            <tr class="trnoclickeable">
                <td colspan="20"><h3>Nota <?php echo $numeroDeNota; ?>:  Resultado neto por produccion agropecuaria</h3></td>
            </tr>
            <?php
            $totalProduccionagropecuaria = [];
            ?>
            <tr class="trnoclickeable">
                <td colspan="20">
                    <?php
                    $totalProduccionagropecuaria = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$indexCuentas601013,"Resultado neto por produccion agropecuaria",$numerofijo,$fechaInicioConsulta,$fechaFinConsulta);
                    $totalProduccionagropecuaria['numeronota']=$numeroDeNota;
                    $numeroDeNota ++;?>
                </td>
            </tr>
            <tr class="trnoclickeable">
                <td>Total de Resultado neto por produccion agropecuaria</td>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo=0;
                    $totalPeriodo += isset($totalProduccionagropecuaria[$periodoMesAMostrar])?$totalProduccionagropecuaria[$periodoMesAMostrar]:0;
//                $totalPeriodo += isset($totalVentasServicios[$periodoMesAMostrar])?$totalVentasServicios[$periodoMesAMostrar]:0;;
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
                ?>
            </tr>
            <?php
        }
        $numerofijo = "601015";
        $indexCuentas601015 = array_keys(
            array_filter(
                $keysCuentas,
                function($var) use ($numerofijo){
                    return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                }
            )
        );
        if(count($indexCuentas601015)!=0){
            ?>
            <tr class="trnoclickeable">
                <td colspan="20"><h3>Nota <?php echo $numeroDeNota; ?>:  Rtdo por valuación de bienes de cambio al VNR</h3></td>
            </tr>
            <?php
            $totalValuacionbienesdecambio = [];
            ?>
            <tr class="trnoclickeable">
                <td colspan="20">
                    <?php
                    $totalValuacionbienesdecambio = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$indexCuentas601015,"Rtdo por valuación de bienes de cambio al VNR",$numerofijo,$fechaInicioConsulta,$fechaFinConsulta);
                    $totalValuacionbienesdecambio['numeronota']=$numeroDeNota;
                    $numeroDeNota ++;?>
                </td>
            </tr>
            <tr class="trnoclickeable">
                <td>Total de Rtdo por valuación de bienes de cambio al VNR</td>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo=0;
                    $totalPeriodo += isset($totalValuacionbienesdecambio[$periodoMesAMostrar])?$totalValuacionbienesdecambio[$periodoMesAMostrar]:0;
//                $totalPeriodo += isset($totalVentasServicios[$periodoMesAMostrar])?$totalVentasServicios[$periodoMesAMostrar]:0;;
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
                ?>
            </tr>
            <?php
        }
        $numerofijo = "504990";
        $indexCuentas504990 = array_keys(
            array_filter(
                $keysCuentas,
                function($var) use ($numerofijo){
                    return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                }
            )
        );
        if(count($indexCuentas504990)!=0) {
            ?>
            <tr class="trnoclickeable">
                <td colspan="20"><h3>Nota <?php echo $numeroDeNota; ?>: Otros Gastos</h3></td>
            </tr>
            <?php
            $totalOtrosGastos = [];
            ?>
            <tr class="trnoclickeable">
                <td colspan="20">
                    <?php
                    $totalOtrosGastos = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$indexCuentas504990,"Otros Gastos",$numerofijo,$fechaInicioConsulta,$fechaFinConsulta);
                    $totalOtrosGastos['numeronota']=$numeroDeNota;
                    $numeroDeNota ++;?>
                </td>
            </tr>
            <tr class="trnoclickeable">
                <td>Total Otros Gastos</td>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo=0;
                    $totalPeriodo += isset($totalOtrosGastos[$periodoMesAMostrar])?$totalOtrosGastos[$periodoMesAMostrar]:0;
//                $totalPeriodo += isset($totalVentasServicios[$periodoMesAMostrar])?$totalVentasServicios[$periodoMesAMostrar]:0;;
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
                ?>
            </tr>
            <?php
        }
        $numerofijo = "601016";
        $indexCuentas601016 = array_keys(
            array_filter(
                $keysCuentas,
                function($var) use ($numerofijo){
                    return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                }
            )
        );
        if(count($indexCuentas601016)!=0) {
            ?>
            <tr class="trnoclickeable">
                <td colspan="20"><h3>Nota <?php echo $numeroDeNota; ?>: Resultado de inversiones en entes relacionados</h3></td>
            </tr>
            <?php
            $totalinversionesenentes = [];
            ?>
            <tr class="trnoclickeable">
                <td colspan="20">
                    <?php
                    $totalinversionesenentes = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$indexCuentas601016,"Resultado de inversiones en entes relacionados",$numerofijo,$fechaInicioConsulta,$fechaFinConsulta);
                    $totalinversionesenentes['numeronota']=$numeroDeNota;
                    $numeroDeNota ++;?>
                </td>
            </tr>
            <tr class="trnoclickeable">
                <td>Total Resultado de inversiones en entes relacionados</td>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo=0;
                    $totalPeriodo += isset($totalinversionesenentes[$periodoMesAMostrar])?$totalinversionesenentes[$periodoMesAMostrar]:0;
//                $totalPeriodo += isset($totalVentasServicios[$periodoMesAMostrar])?$totalVentasServicios[$periodoMesAMostrar]:0;;
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
                ?>
            </tr>
            <?php
        }
        $numerofijo = "601017";
        $indexCuentas601017 = array_keys(
            array_filter(
                $keysCuentas,
                function($var) use ($numerofijo){
                    return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                }
            )
        );
        if(count($indexCuentas601017)!=0) {
            ?>
            <tr class="trnoclickeable">
                <td colspan="20"><h3>Nota <?php echo $numeroDeNota; ?>: Resultados financieros y por tenencia</h3></td>
            </tr>
            <?php
            $totalresultadosfinancieros = [];
            ?>
            <tr class="trnoclickeable">
                <td colspan="20">
                    <?php
                    $totalresultadosfinancieros = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$indexCuentas601017,"Resultados financieros y por tenencia",$numerofijo,$fechaInicioConsulta,$fechaFinConsulta);
                    $totalresultadosfinancieros['numeronota']=$numeroDeNota;
                    $numeroDeNota ++;?>
                </td>
            </tr>
            <tr class="trnoclickeable">
                <td>Total Resultados financieros y por tenencia</td>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo=0;
                    $totalPeriodo += isset($totalresultadosfinancieros[$periodoMesAMostrar])?$totalresultadosfinancieros[$periodoMesAMostrar]:0;
//                $totalPeriodo += isset($totalVentasServicios[$periodoMesAMostrar])?$totalVentasServicios[$periodoMesAMostrar]:0;;
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
                ?>
            </tr>
            <?php
        }
        $numerofijo = "601018";
        $indexCuentas601018 = array_keys(
            array_filter(
                $keysCuentas,
                function($var) use ($numerofijo){
                    return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                }
            )
        );
        if(count($indexCuentas601018)!=0) {
            ?>
            <tr class="trnoclickeable">
                <td colspan="20"><h3>Nota <?php echo $numeroDeNota; ?>: Interés del capital propio</h3></td>
            </tr>
            <?php
            $totalInteresdelcapitalpropio = [];
            ?>
            <tr class="trnoclickeable">
                <td colspan="20">
                    <?php
                    $totalInteresdelcapitalpropio = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$indexCuentas601018,"Resultados financieros y por tenencia",$numerofijo,$fechaInicioConsulta,$fechaFinConsulta);
                    $totalDesgravaciones['numeronota']=$numeroDeNota;
                    $numeroDeNota ++;?>
                </td>
            </tr>
            <tr class="trnoclickeable">
                <td>Total Interés del capital propio</td>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo=0;
                    $totalPeriodo += isset($totalresultadosfinancieros[$periodoMesAMostrar])?$totalresultadosfinancieros[$periodoMesAMostrar]:0;
//                $totalPeriodo += isset($totalVentasServicios[$periodoMesAMostrar])?$totalVentasServicios[$periodoMesAMostrar]:0;;
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
                ?>
            </tr>
            <?php
        }

        $numerofijo = "608100";
        $indexCuentas608100 = array_keys(
            array_filter(
                $keysCuentas,
                function($var) use ($numerofijo){
                    return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                }
            )
        );
        if(count($indexCuentas608100)!=0){?>
            <tr class="trnoclickeable">
                <td colspan="20"><h3>Nota <?php echo $numeroDeNota; ?>:  Otros Ingresos</h3></td>
            </tr>
            <tr class="trnoclickeable">
                <td colspan="20">
                    <?php
                    $totalOtrosIngresos = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$indexCuentas608100,"Otros Ingresos",$numerofijo,$fechaInicioConsulta,$fechaFinConsulta);
                    $totalOtrosIngresos['numeronota']=$numeroDeNota;
                    $numeroDeNota ++;?>
                </td>
            </tr>
            <tr class="trnoclickeable">
                <td>Total Impuesto a las gananciass</td>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo = $totalOtrosIngresos[$periodoMesAMostrar];
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
                ?>
            </tr>
        <?php }

        $numerofijo = "506110";
        $indexCuentas = array_keys(
            array_filter(
                $keysCuentas,
                function($var) use ($numerofijo){
                    return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                }
            )
        );
        if(count($indexCuentas)!=0){?>
            <tr class="trnoclickeable">
                <td colspan="20"><h3>Nota <?php echo $numeroDeNota; ?>:  Impuesto a las ganancias</h3></td>
            </tr>
            <tr class="trnoclickeable">
                <td colspan="20">
                    <?php
                    $totalImpuestoALaGanancia = mostrarNotaDelGrupo($arrayCuentasxPeriodos,$indexCuentas,"Impuesto a las ganancias",$numerofijo,$fechaInicioConsulta,$fechaFinConsulta);
                    $totalImpuestoALaGanancia['numeronota']=$numeroDeNota;
                    $numeroDeNota ++;?>
                </td>
            </tr>
            <tr class="trnoclickeable">
                <td>Total Impuesto a las gananciass</td>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo = $totalImpuestoALaGanancia[$periodoMesAMostrar];
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
                ?>
            </tr>
        <?php }?>
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
    <div id="AnexoI" class="index" style="width:650px;float: left;">
        <table id="tblAnexoI"  class="tbl_border" cellspacing="0" style="width:600px;">
            <thead>
            <tr>
                <th colspan="20" style="background-color: #91a7ff">
                    Anexo I: Costo de los Bienes Vendidos, Servicios Prestados y de Producción al
                    31/12/2016  comparativo con el Ejercicio Anterior
                </th>
            </tr>

            </thead>
            <tbody>
            <tr>
                <td>
                    Descripción Actividad
                </td>
                <td>
                    Anterior
                </td>
                <td>
                    Actual
                </td>
            </tr>
            <tr>
                <td colspan="20" style="background-color: #91a7ff">
                    Existencia Inicial
                </td>

            </tr>
            <tr>
                <td>
                    Mercaderías
                </td>
                <?php
                //aca vamos a buscar los valores de la cuenta 	110500011 Mercaderia Inicial que esten en un asiento de apertura
                //capas que no sea necesario usar el asiento de apertura por que
                $totalPeriodoExistenciaInicial=[];
                foreach ($cliente['Cuentascliente'] as $cuentascliente) {
                    $numerodecuenta = $cuentascliente['Cuenta']['numero'];
                    if($numerodecuenta == '110500011'){
                        foreach ($cuentascliente['Movimiento'] as $movimiento){
                            if($movimiento['Asiento']['tipoasiento']== 'Apertura'){
                                //entonces este es el valor del asiento de apertura de este periodo
                                $mesAMostrar = date('Y', strtotime($movimiento['Asiento']['fecha']));
                                if(!isset($totalPeriodoExistenciaInicial[$mesAMostrar])){
                                    $totalPeriodoExistenciaInicial[$mesAMostrar] = 0;//existen estos valores
                                }
                                $totalPeriodoExistenciaInicial[$mesAMostrar]+=$movimiento['debe'];
                                $totalPeriodoExistenciaInicial[$mesAMostrar]-=$movimiento['haber'];
                            }
                        }
                    }
                }
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo = isset($totalPeriodoExistenciaInicial[$periodoMesAMostrar])?$totalPeriodoExistenciaInicial[$periodoMesAMostrar]:0;
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
                ?>
            </tr>
            <tr>
                <td>
                    Productos Terminados
                </td>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo = isset($arrayCuentasxPeriodos['110502011'][$periodoMesAMostrar])?$arrayCuentasxPeriodos['110502011'][$periodoMesAMostrar]:0;
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    if(!isset($totalPeriodoExistenciaInicial[$mesAMostrar])){
                        $totalPeriodoExistenciaInicial[$mesAMostrar] = 0;//existen estos valores
                    }
                    $totalPeriodoExistenciaInicial[$mesAMostrar]+=$totalPeriodo;
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
                ?>
            </tr>
            <tr>
                <td>
                    Producción en Proceso
                </td>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo = isset($arrayCuentasxPeriodos['110504011'][$periodoMesAMostrar])?$arrayCuentasxPeriodos['110502011'][$periodoMesAMostrar]:0;
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    if(!isset($totalPeriodoExistenciaInicial[$mesAMostrar])){
                        $totalPeriodoExistenciaInicial[$mesAMostrar] = 0;//existen estos valores
                    }
                    $totalPeriodoExistenciaInicial[$mesAMostrar]+=$totalPeriodo;
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
                ?>
            </tr>
            <tr>
                <td>
                    Materias Primas e Insumos incorporados a la producción
                </td>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo = isset($arrayCuentasxPeriodos['110506011'][$periodoMesAMostrar])?$arrayCuentasxPeriodos['110502011'][$periodoMesAMostrar]:0;
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    if(!isset($totalPeriodoExistenciaInicial[$mesAMostrar])){
                        $totalPeriodoExistenciaInicial[$mesAMostrar] = 0;//existen estos valores
                    }
                    $totalPeriodoExistenciaInicial[$mesAMostrar]+=$totalPeriodo;
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
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
                    Total Existencia Inicial
                </th>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    echo '<th  class="numericTD">' .
                        number_format($totalPeriodoExistenciaInicial[$mesAMostrar], 2, ",", ".")
                        . "</th>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
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
                //vamos a calcular las existencias finales y despues las mostramos mas abajo
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo = isset($arrayCuentasxPeriodos['110500013'][$periodoMesAMostrar])?$arrayCuentasxPeriodos['110500013'][$periodoMesAMostrar]:0;
                    if(!isset($totalPeriodoExistenciaFinal[$mesAMostrar])){
                        $totalPeriodoExistenciaFinal[$mesAMostrar] = 0;//existen estos valores
                    }
                    $totalPeriodoExistenciaFinal[$mesAMostrar]+=$totalPeriodo;
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }

                $totalPeriodoCompras = [];
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo = isset($arrayCuentasxPeriodos['501000001'][$periodoMesAMostrar])?$arrayCuentasxPeriodos['501000001'][$periodoMesAMostrar]:0;
                    $totalPeriodo += $totalPeriodoExistenciaFinal[$mesAMostrar];
                    $totalPeriodo -= $totalPeriodoExistenciaInicial[$mesAMostrar];
                    if(!isset($totalPeriodoCompras[$mesAMostrar])){
                        $totalPeriodoCompras[$mesAMostrar] = 0;//existen estos valores
                    }
                    $totalPeriodoCompras[$mesAMostrar] += $totalPeriodo;
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
                ?>

            </tr>
            <tr>
                <td>
                    Productos Terminados
                </td>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo = isset($arrayCuentasxPeriodos['110502012'][$periodoMesAMostrar])?$arrayCuentasxPeriodos['110502012'][$periodoMesAMostrar]:0;
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    if(!isset($totalPeriodoCompras[$mesAMostrar])){
                        $totalPeriodoCompras[$mesAMostrar] = 0;//existen estos valores
                    }
                    $totalPeriodoCompras[$mesAMostrar]+=$totalPeriodo;
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
                ?>
            </tr>
            <tr>
                <td>
                    Producción en Proceso
                </td>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo = isset($arrayCuentasxPeriodos['110504012'][$periodoMesAMostrar])?$arrayCuentasxPeriodos['110504012'][$periodoMesAMostrar]:0;
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    if(!isset($totalPeriodoCompras[$mesAMostrar])){
                        $totalPeriodoCompras[$mesAMostrar] = 0;//existen estos valores
                    }
                    $totalPeriodoCompras[$mesAMostrar]+=$totalPeriodo;
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
                ?>
            </tr>
            <tr>
                <td>
                    Materias Primas e Insumos incorporados a la producción
                </td>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo = isset($arrayCuentasxPeriodos['110506012'][$periodoMesAMostrar])?$arrayCuentasxPeriodos['110506012'][$periodoMesAMostrar]:0;
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    if(!isset($totalPeriodoCompras[$mesAMostrar])){
                        $totalPeriodoCompras[$mesAMostrar] = 0;//existen estos valores
                    }
                    $totalPeriodoCompras[$mesAMostrar]+=$totalPeriodo;
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
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
                    Total de Compras
                </th>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    echo '<th  class="numericTD">' .
                        number_format($totalPeriodoCompras[$mesAMostrar], 2, ",", ".")
                        . "</th>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
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
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo = isset($arrayCuentasxPeriodos['110500014'][$periodoMesAMostrar])?$arrayCuentasxPeriodos['110500014'][$periodoMesAMostrar]:0;
                    if(!isset($totalPeriodoDevoluciones[$mesAMostrar])){
                        $totalPeriodoDevoluciones[$mesAMostrar] = 0;//existen estos valores
                    }
                    $totalPeriodoDevoluciones[$mesAMostrar] += $totalPeriodo;
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
                ?>
            </tr>
            <tr>
                <td>
                    Productos Terminados
                </td>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo = isset($arrayCuentasxPeriodos['110502014'][$periodoMesAMostrar])?$arrayCuentasxPeriodos['110502014'][$periodoMesAMostrar]:0;
                    if(!isset($totalPeriodoDevoluciones[$mesAMostrar])){
                        $totalPeriodoDevoluciones[$mesAMostrar] = 0;//existen estos valores
                    }
                    $totalPeriodoDevoluciones[$mesAMostrar] += $totalPeriodo;
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
                ?>
            </tr>
            <tr>
                <td>
                    Producción en Proceso
                </td>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo = isset($arrayCuentasxPeriodos['110504014'][$periodoMesAMostrar])?$arrayCuentasxPeriodos['110504014'][$periodoMesAMostrar]:0;
                    if(!isset($totalPeriodoDevoluciones[$mesAMostrar])){
                        $totalPeriodoDevoluciones[$mesAMostrar] = 0;//existen estos valores
                    }
                    $totalPeriodoDevoluciones[$mesAMostrar] += $totalPeriodo;
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
                ?>
            </tr>
            <tr>
                <td>
                    Materias Primas e Insumos incorporados a la producción
                </td>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo = isset($arrayCuentasxPeriodos['110506014'][$periodoMesAMostrar])?$arrayCuentasxPeriodos['110506014'][$periodoMesAMostrar]:0;
                    if(!isset($totalPeriodoDevoluciones[$mesAMostrar])){
                        $totalPeriodoDevoluciones[$mesAMostrar] = 0;//existen estos valores
                    }
                    $totalPeriodoDevoluciones[$mesAMostrar] += $totalPeriodo;
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
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
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    echo '<th  class="numericTD">' .
                        number_format($totalPeriodoDevoluciones[$mesAMostrar], 2, ",", ".")
                        . "</th>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
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
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodoExistenciaFinal[$mesAMostrar], 2, ",", ".")
                        . "</td>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
                ?>
            </tr>
            <tr>
                <td>
                    Productos Terminados
                </td>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo = isset($arrayCuentasxPeriodos['110502013'][$periodoMesAMostrar])?$arrayCuentasxPeriodos['110502013'][$periodoMesAMostrar]:0;
                    if(!isset($totalPeriodoExistenciaFinal[$mesAMostrar])){
                        $totalPeriodoExistenciaFinal[$mesAMostrar] = 0;//existen estos valores
                    }
                    $totalPeriodoExistenciaFinal[$mesAMostrar] += $totalPeriodo;
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
                ?>
            </tr>
            <tr>
                <td>
                    Producción en Proceso
                </td>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo = isset($arrayCuentasxPeriodos['110504013'][$periodoMesAMostrar])?$arrayCuentasxPeriodos['110504013'][$periodoMesAMostrar]:0;
                    if(!isset($totalPeriodoExistenciaFinal[$mesAMostrar])){
                        $totalPeriodoExistenciaFinal[$mesAMostrar] = 0;//existen estos valores
                    }
                    $totalPeriodoExistenciaFinal[$mesAMostrar] += $totalPeriodo;
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
                ?>
            </tr>
            <tr>
                <td>
                    Materias Primas y Materiales
                </td>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo = isset($arrayCuentasxPeriodos['110506013'][$periodoMesAMostrar])?$arrayCuentasxPeriodos['110506013'][$periodoMesAMostrar]:0;
                    if(!isset($totalPeriodoExistenciaFinal[$mesAMostrar])){
                        $totalPeriodoExistenciaFinal[$mesAMostrar] = 0;//existen estos valores
                    }
                    $totalPeriodoExistenciaFinal[$mesAMostrar] += $totalPeriodo;
                    echo '<td  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</td>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
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
                    Total Existencia Final
                </th>
                <?php
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    echo '<th  class="numericTD">' .
                        number_format($totalPeriodoExistenciaFinal[$mesAMostrar], 2, ",", ".")
                        . "</th>";
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
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
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                while($mesAMostrar<=$fechaFinConsulta) {
                    $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                    $totalPeriodo = $totalPeriodoExistenciaInicial[$mesAMostrar];
                    $totalPeriodo += $totalPeriodoCompras[$mesAMostrar];
                    $totalPeriodo -= $totalPeriodoDevoluciones[$mesAMostrar];
                    $totalPeriodo -= $totalPeriodoExistenciaFinal[$mesAMostrar];
                    echo '<th  class="numericTD">' .
                        number_format($totalPeriodo, 2, ",", ".")
                        . "</th>";
                    if(!isset($totalPeriodoCostoBienesServiciosProduccion[$mesAMostrar])){
                        $totalPeriodoCostoBienesServiciosProduccion[$mesAMostrar] = 0;//existen estos valores
                    }
                    $totalPeriodoCostoBienesServiciosProduccion[$mesAMostrar] += $totalPeriodo;
                    $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
                }
                ?>
            </tr>
            </tbody>
        </table>
    </div>
    <div id="AnexoII" class="index" style="width:700px;float: left">
        <table id="tblAnexoII" class="tbl_border">
            <thead>
                <tr style="background-color: #91a7ff">
                    <th colspan="20">
                        Anexo II de Costos de Producción y Gastos clasificados por su naturaleza al 31/12/2016 comparativo con el Ejercicio Anterior
                    </th>
                </tr>
                <tr>
                    <td rowspan="2"></td>
                    <td colspan="4"></td>
                    <td colspan="1">Ejercicio Anterior</td>
                </tr>
                <tr>
                    <td>Costo de vta, producc. y adquis de bs de uso, intang. y otros activos</td>
                    <td>Gastos de Administración</td>
                    <td>Gastos de Comercialización</td>
                    <td>Total</td>
                    <td>Total</td>
                </tr>
                <tr>
                    <td>Porcentajes para el prorrateo</td>
                    <td>0%</td>
                    <td>25%</td>
                    <td>75%</td>
                    <td>100%</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
            <?php
            $numerofijo = "50401000";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Combustibles, Lubricantes y FM",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);

            }
            $numerofijo = "50402000";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Servicios Públicos",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "50403000";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Alquileres y Expensas",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "50403000";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Alquileres y Expensas",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            ?>
            <tr><th colspan="20">Amortizaciones</th></tr>
             <?php
            $numerofijo = "50404100";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Amortización Inmuebles",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "50404200";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Amortización Rodados",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "50404300";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Amortización Instalaciones",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
             $numerofijo = "50404400";
             $indexCuentas = array_keys(
                 array_filter(
                     $keysCuentas,
                     function($var) use ($numerofijo){
                         return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                     }
                 )
             );
             if(count($indexCuentas)!=0) {
                 $totalGastosCombustibles = mostrarNotasDeGastos(
                     $arrayCuentasxPeriodos,
                     $indexCuentas,
                     "Amortización Muebles y Utiles",
                     $numerofijo,
                     $fechaInicioConsulta,
                     $fechaFinConsulta,$totalAnexoII);
             }
             $numerofijo = "50404500";
             $indexCuentas = array_keys(
                 array_filter(
                     $keysCuentas,
                     function($var) use ($numerofijo){
                         return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                     }
                 )
             );
             if(count($indexCuentas)!=0) {
                 $totalGastosCombustibles = mostrarNotasDeGastos(
                     $arrayCuentasxPeriodos,
                     $indexCuentas,
                     "Amortización Maquinarias",
                     $numerofijo,
                     $fechaInicioConsulta,
                     $fechaFinConsulta,$totalAnexoII);
             }
             $numerofijo = "50404600";
             $indexCuentas = array_keys(
                 array_filter(
                     $keysCuentas,
                     function($var) use ($numerofijo){
                         return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                     }
                 )
             );
             if(count($indexCuentas)!=0) {
                 $totalGastosCombustibles = mostrarNotasDeGastos(
                     $arrayCuentasxPeriodos,
                     $indexCuentas,
                     "Amortización Activos Biológico",
                     $numerofijo,
                     $fechaInicioConsulta,
                     $fechaFinConsulta,$totalAnexoII);
             }
             $numerofijo = "50405000";
             $indexCuentas = array_keys(
                 array_filter(
                     $keysCuentas,
                     function($var) use ($numerofijo){
                         return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                     }
                 )
             );
             if(count($indexCuentas)!=0) {
                 $totalGastosCombustibles = mostrarNotasDeGastos(
                     $arrayCuentasxPeriodos,
                     $indexCuentas,
                     "Gastos de Traslados",
                     $numerofijo,
                     $fechaInicioConsulta,
                     $fechaFinConsulta,$totalAnexoII);
             }
             $numerofijo = "50406000";
             $indexCuentas = array_keys(
                 array_filter(
                     $keysCuentas,
                     function($var) use ($numerofijo){
                         return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                     }
                 )
             );
             if(count($indexCuentas)!=0) {
                 $totalGastosCombustibles = mostrarNotasDeGastos(
                     $arrayCuentasxPeriodos,
                     $indexCuentas,
                     "Seguros",
                     $numerofijo,
                     $fechaInicioConsulta,
                     $fechaFinConsulta,$totalAnexoII);
             }
             $numerofijo = "50407000";
             $indexCuentas = array_keys(
                 array_filter(
                     $keysCuentas,
                     function($var) use ($numerofijo){
                         return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                     }
                 )
             );
             if(count($indexCuentas)!=0) {
                 $totalGastosCombustibles = mostrarNotasDeGastos(
                     $arrayCuentasxPeriodos,
                     $indexCuentas,
                     "Gastos de Cortesia y Homenaje",
                     $numerofijo,
                     $fechaInicioConsulta,
                     $fechaFinConsulta,$totalAnexoII);
             }
             $numerofijo = "50408000";
             $indexCuentas = array_keys(
                 array_filter(
                     $keysCuentas,
                     function($var) use ($numerofijo){
                         return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                     }
                 )
             );
             if(count($indexCuentas)!=0) {
                 $totalGastosCombustibles = mostrarNotasDeGastos(
                     $arrayCuentasxPeriodos,
                     $indexCuentas,
                     "Mantenimiento, Reparación, etc",
                     $numerofijo,
                     $fechaInicioConsulta,
                     $fechaFinConsulta,$totalAnexoII);
             }
             $numerofijo = "50409000";
             $indexCuentas = array_keys(
                 array_filter(
                     $keysCuentas,
                     function($var) use ($numerofijo){
                         return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                     }
                 )
             );
             if(count($indexCuentas)!=0) {
                 $totalGastosCombustibles = mostrarNotasDeGastos(
                     $arrayCuentasxPeriodos,
                     $indexCuentas,
                     "Gastos de Librería",
                     $numerofijo,
                     $fechaInicioConsulta,
                     $fechaFinConsulta,$totalAnexoII);
             }
             $numerofijo = "50410000";
             $indexCuentas = array_keys(
                 array_filter(
                     $keysCuentas,
                     function($var) use ($numerofijo){
                         return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                     }
                 )
             );
             if(count($indexCuentas)!=0) {
                 $totalGastosCombustibles = mostrarNotasDeGastos(
                     $arrayCuentasxPeriodos,
                     $indexCuentas,
                     "Gastos Varios",
                     $numerofijo,
                     $fechaInicioConsulta,
                     $fechaFinConsulta,$totalAnexoII);
             }
             $numerofijo = "50411000";
             $indexCuentas = array_keys(
                 array_filter(
                     $keysCuentas,
                     function($var) use ($numerofijo){
                         return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                     }
                 )
             );
             if(count($indexCuentas)!=0) {
                 $totalGastosCombustibles = mostrarNotasDeGastos(
                     $arrayCuentasxPeriodos,
                     $indexCuentas,
                     "Incobrables",
                     $numerofijo,
                     $fechaInicioConsulta,
                     $fechaFinConsulta,$totalAnexoII);
             }
             $numerofijo = "50412000";
             $indexCuentas = array_keys(
                 array_filter(
                     $keysCuentas,
                     function($var) use ($numerofijo){
                         return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                     }
                 )
             );
             if(count($indexCuentas)!=0) {
                 $totalGastosCombustibles = mostrarNotasDeGastos(
                     $arrayCuentasxPeriodos,
                     $indexCuentas,
                     "Honorarios Directores",
                     $numerofijo,
                     $fechaInicioConsulta,
                     $fechaFinConsulta,$totalAnexoII);
             }
             $numerofijo = "50413000";
             $indexCuentas = array_keys(
                 array_filter(
                     $keysCuentas,
                     function($var) use ($numerofijo){
                         return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                     }
                 )
             );
             if(count($indexCuentas)!=0) {
                 $totalGastosCombustibles = mostrarNotasDeGastos(
                     $arrayCuentasxPeriodos,
                     $indexCuentas,
                     "Honorarios Sindicos",
                     $numerofijo,
                     $fechaInicioConsulta,
                     $fechaFinConsulta,$totalAnexoII);
             }
             $numerofijo = "50499000";
             $indexCuentas = array_keys(
                 array_filter(
                     $keysCuentas,
                     function($var) use ($numerofijo){
                         return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                     }
                 )
             );
             if(count($indexCuentas)!=0) {
                 $totalGastosCombustibles = mostrarNotasDeGastos(
                     $arrayCuentasxPeriodos,
                     $indexCuentas,
                     "Otros Gastos (no prorrateables)",
                     $numerofijo,
                     $fechaInicioConsulta,
                     $fechaFinConsulta,$totalAnexoII);
             }?>
            <tr><th colspan="20">Gastos Financieros</th></tr>
            <tr><th colspan="20">Gtos. Financ. de Act. Operativ</th></tr>
            <?php
            $numerofijo = "50501010";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Proveedores",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "50501020";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Acreedores Varios",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }?>
            <tr><th colspan="20">Entidades Crediticias</th></tr>
            <?php
            $numerofijo = "5050201";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Banco",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "5050202";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Banco",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "5050203";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Banco",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "5050204";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Banco",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "5050205";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Banco",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "5050206";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Banco",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "5050207";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Banco",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "5050208";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Banco",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "5050209";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Banco",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "5050210";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Banco",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "50503000";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Otras Entidades Crediticias",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            ?>
            <tr><th colspan="20">Organismos Públicos</th></tr>
            <?php
            $numerofijo = "50504010";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "AFIP",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "50504020";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "DGR",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "50504030";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "DGRM",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            ?>
            <tr><th colspan="20">Gastos Fiscales</th></tr>
            <tr><th colspan="20">Gastos Fiscales - AFIP</th></tr>
            <?php
            $numerofijo = "50611000";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Ganancias",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "50612000";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Ganancia Mín. Presunta",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "50613000";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Bienes Personales",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "50614000";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Otros Impuestos Nacionales",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            ?>
            <tr><th colspan="20">Gastos Fiscales - DGR</th></tr>
            <?php
            $numerofijo = "50621000";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Ingresos Brutos",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "50622000";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Cooperadoras Asistenciales",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "50623000";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Inmobiliario Rural",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "50624000";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Impuesto a los Sellos",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            ?>
            <tr><th colspan="20">Gastos Fiscales - DGR</th></tr>
            <?php
            $numerofijo = "50631000";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Actividades Varias",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "50632000";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Tasa de publicidad y Propaganda",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "50633000";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Inmobiliario Urbano",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "50634000";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Alumbrado y Limpieza",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "50635000";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Impuesto Automotor",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            ?>
            <tr><th colspan="20">Remuneraciones y Cargas Sociales</th></tr>
            <?php
            $numerofijo = "50302000";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Mano de Obra",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "5030300";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Contribuciones Empleador",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "5071000";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Mano de Obra",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "5072000";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Contribuciones Empleador",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $numerofijo = "50900000";
            $indexCuentas = array_keys(
                array_filter(
                    $keysCuentas,
                    function($var) use ($numerofijo){
                        return (substr( $var, 0, strlen($numerofijo)  ) == $numerofijo);
                    }
                )
            );
            if(count($indexCuentas)!=0) {
                $totalGastosCombustibles = mostrarNotasDeGastos(
                    $arrayCuentasxPeriodos,
                    $indexCuentas,
                    "Gastos Extraordinarios",
                    $numerofijo,
                    $fechaInicioConsulta,
                    $fechaFinConsulta,$totalAnexoII);
            }
            $mesAMostrar = date('Y', strtotime($fechaFinConsulta.'-01-01'));
            $subtottalPeriodo = $totalAnexoII[$mesAMostrar];
            echo '<tr style="background-color: #d0d9ff">
                    <td>Total Ejercicio Actual</td>
                    <td  class="numericTD">' .
                number_format($subtottalPeriodo*0.0, 2, ",", ".")
                . "</td>";
            echo '<td  class="numericTD">' .
                number_format($subtottalPeriodo*0.25, 2, ",", ".")
                . "</td>";
            echo '<td  class="numericTD">' .
                number_format($subtottalPeriodo*0.75, 2, ",", ".")
                . "</td>";
            echo '<td  class="numericTD">' .
                number_format($subtottalPeriodo, 2, ",", ".")
                . "</td>";
            $mesAMostrar = date('Y', strtotime($fechaInicioConsulta .'-01-01'));
            $subtottalPeriodo = $totalAnexoII[$mesAMostrar];
            echo '<td  class="numericTD">' .
                number_format($subtottalPeriodo, 2, ",", ".")
                . "</td></tr>";


            echo '<tr style="background-color: #d0d9ff">
                    <td>Total Ejercicio Anterior</td>
                    <td  class="numericTD">' .
                number_format($subtottalPeriodo*0.0, 2, ",", ".")
                . "</td>";
            echo '<td  class="numericTD">' .
                number_format($subtottalPeriodo*0.25, 2, ",", ".")
                . "</td>";
            echo '<td  class="numericTD">' .
                number_format($subtottalPeriodo*0.75, 2, ",", ".")
                . "</td>";
            echo '<td  class="numericTD">' .
                number_format($subtottalPeriodo, 2, ",", ".")
                . "</td></tr>";
            ?>
            </tbody>
        </table>
    </div>
</div>
<div class="index" id="divContenedorEstadosResultados">
    <?php
    echo "<h2>Estados de resultados</h2>";
    ?>
    <table id="tblestadoderesultado"  class="tbl_border" cellspacing="0" style="width:1050px;">
        <thead>
        <tr class="trnoclickeable" style="background-color: #91a7ff">
            <td colspan="20">Estado de Resultados por el Ejercicio Anual Finalizado el 31/12/2016 comparativo con el
                ejercicio anterior
            </td>
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
            $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
            while($mesAMostrar<=$fechaFinConsulta) {
                $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                if(!isset($totalPeriodo[$mesAMostrar])){
                    $totalPeriodo[$mesAMostrar] = 0;//existen estos valores
                }
                $total = isset($totalVentasBienes[$periodoMesAMostrar])?$totalVentasBienes[$periodoMesAMostrar]:0;
                $totalPeriodo[$mesAMostrar] += $total;
//                $totalPeriodo += isset($totalVentasServicios[$periodoMesAMostrar])?$totalVentasServicios[$periodoMesAMostrar]:0;;
                echo '<td  class="numericTD">' .
                    number_format($total, 2, ",", ".")
                    . "</td>";
                $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
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
            $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
            while($mesAMostrar<=$fechaFinConsulta) {
                echo '<td  class="numericTD">' .
                    number_format($totalPeriodoCostoBienesServiciosProduccion[$mesAMostrar], 2, ",", ".")
                    . "</td>";
                if(!isset($totalPeriodo[$mesAMostrar])){
                    $totalPeriodo[$mesAMostrar] = 0;//existen estos valores
                }
                $totalPeriodo[$mesAMostrar] -= $totalPeriodoCostoBienesServiciosProduccion[$mesAMostrar];
                $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
            }
            ?>
        </tr>
        <tr>
            <td>
                Reintegros
                <?php
                $numeroDeNota = "";
                if(isset($totalReintegros)){
                    $numeroDeNota = $totalReintegros['numeronota'];//existen estos valores
                }
                ?>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
            $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
            while($mesAMostrar<=$fechaFinConsulta) {
                $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                if(!isset($totalPeriodo[$mesAMostrar])){
                    $totalPeriodo[$mesAMostrar] = 0;//existen estos valores
                }
                $total = isset($totalReintegros[$periodoMesAMostrar])?$totalReintegros[$periodoMesAMostrar]:0;
                $totalPeriodo[$mesAMostrar] += $total;
                echo '<td  class="numericTD">' .
                    number_format($total, 2, ",", ".")
                    . "</td>";
                $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
            }
            ?>
        </tr>
        <tr>
            <td>
                Desgravaciones
                <?php
                $numeroDeNota = "";
                if(isset($totalDesgravaciones)){
                    $numeroDeNota = $totalDesgravaciones['numeronota'];//existen estos valores
                }
                ?>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
            $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
            while($mesAMostrar<=$fechaFinConsulta) {
                $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                if(!isset($totalPeriodo[$mesAMostrar])){
                    $totalPeriodo[$mesAMostrar] = 0;//existen estos valores
                }
                $total = isset($totalDesgravaciones[$periodoMesAMostrar])?$totalDesgravaciones[$periodoMesAMostrar]:0;
                $totalPeriodo[$mesAMostrar] += $total;
                echo '<td  class="numericTD">' .
                    number_format($total, 2, ",", ".")
                    . "</td>";
                $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
            }
            ?>
        </tr>
        <tr style="background-color: #d0d9ff">
            <th>Ganancia Bruta</th>
            <th>

            </th>
            <?php
            $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
            while($mesAMostrar<=$fechaFinConsulta) {
                echo '<th  class="numericTD">' .
                    number_format($totalPeriodo[$mesAMostrar], 2, ",", ".")
                    . "</th>";
                $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
            }
            ?>
        </tr>
        <tr>
            <td>Resultado neto por producción agropecuaria</td>
            <?php
            $numeroDeNota = "";
            if(isset($totalProduccionagropecuaria)){
                $numeroDeNota = $totalProduccionagropecuaria['numeronota'];//existen estos valores
            }
            ?>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
            $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
            while($mesAMostrar<=$fechaFinConsulta) {
                $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                if(!isset($totalPeriodo[$mesAMostrar])){
                    $totalPeriodo[$mesAMostrar] = 0;//existen estos valores
                }
                $total = isset($totalProduccionagropecuaria[$periodoMesAMostrar])?$totalProduccionagropecuaria[$periodoMesAMostrar]:0;
                $totalPeriodo[$mesAMostrar] += $total;
                echo '<td  class="numericTD">' .
                    number_format($total, 2, ",", ".")
                    . "</td>";
                $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
            }
            ?>
        </tr>
        <tr>
            <td>Rtdo por valuación de bienes de cambio al VNR
                <?php
                $numeroDeNota = "";
                if(isset($totalValuacionbienesdecambio)){
                    $numeroDeNota = $totalValuacionbienesdecambio['numeronota'];//existen estos valores
                }
                ?>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
            $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
            while($mesAMostrar<=$fechaFinConsulta) {
                $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                if(!isset($totalPeriodo[$mesAMostrar])){
                    $totalPeriodo[$mesAMostrar] = 0;//existen estos valores
                }
                $total = isset($totalValuacionbienesdecambio[$periodoMesAMostrar])?$totalValuacionbienesdecambio[$periodoMesAMostrar]:0;
                $totalPeriodo[$mesAMostrar] += $total;
                echo '<td  class="numericTD">' .
                    number_format($total, 2, ",", ".")
                    . "</td>";
                $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
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
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                echo number_format($totalAnexoII[$mesAMostrar]*0.0, 2, ",", ".");
                ?>
            </td>
            <td class="numericTD">
                <?php
                $mesAMostrar = date('Y', strtotime($fechaFinConsulta.'-01-01'));
                echo number_format($totalAnexoII[$mesAMostrar]*0.0, 2, ",", ".");
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
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                echo number_format($totalAnexoII[$mesAMostrar]*-0.75, 2, ",", ".");
                ?>
            </td>
            <td class="numericTD">
                <?php
                $mesAMostrar = date('Y', strtotime($fechaFinConsulta.'-01-01'));
                echo number_format($totalAnexoII[$mesAMostrar]*-0.75, 2, ",", ".");
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
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                echo number_format($totalAnexoII[$mesAMostrar]*-0.25, 2, ",", ".");
                ?>
            </td>
            <td class="numericTD">
                <?php
                $mesAMostrar = date('Y', strtotime($fechaFinConsulta.'-01-01'));
                echo number_format($totalAnexoII[$mesAMostrar]*-0.25, 2, ",", ".");
                $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
                if(!isset($totalPeriodo[$mesAMostrar])){
                    $totalPeriodo[$mesAMostrar] = 0;//existen estos valores
                }
                $totalPeriodo[$mesAMostrar] -= $totalAnexoII[$mesAMostrar];
                $mesAMostrar = date('Y', strtotime($fechaFinConsulta.'-01-01'));
                if(!isset($totalPeriodo[$mesAMostrar])){
                    $totalPeriodo[$mesAMostrar] = 0;//existen estos valores
                }
                $totalPeriodo[$mesAMostrar] -= $totalAnexoII[$mesAMostrar];
                ?>
            </td>
        </tr>
        <tr>
            <td>Gastos operativos</td>
            <td>
                0.000
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
                if(isset($totalOtrosGastos)){
                    $numeroDeNota = $totalOtrosGastos['numeronota'];//existen estos valores
                }
                ?>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
            $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
            while($mesAMostrar<=$fechaFinConsulta) {
                $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                if(!isset($totalPeriodo[$mesAMostrar])){
                    $totalPeriodo[$mesAMostrar] = 0;//existen estos valores
                }
                $total = isset($totalOtrosGastos[$periodoMesAMostrar])?$totalOtrosGastos[$periodoMesAMostrar]:0;
                $totalPeriodo[$mesAMostrar] += $total;
                echo '<td  class="numericTD">' .
                    number_format($total, 2, ",", ".")
                    . "</td>";
                $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
            }
            ?>
        </tr>
        <tr>
            <td>Resultado de inversiones en entes relacionados
                <?php
                $numeroDeNota = "";
                if(isset($totalinversionesenentes)){
                    $numeroDeNota = $totalinversionesenentes['numeronota'];//existen estos valores
                }
                ?>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
            $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
            while($mesAMostrar<=$fechaFinConsulta) {
                $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                if(!isset($totalPeriodo[$mesAMostrar])){
                    $totalPeriodo[$mesAMostrar] = 0;//existen estos valores
                }
                $total = isset($totalinversionesenentes[$periodoMesAMostrar])?$totalinversionesenentes[$periodoMesAMostrar]:0;
                $totalPeriodo[$mesAMostrar] += $total;
                echo '<td  class="numericTD">' .
                    number_format($total, 2, ",", ".")
                    . "</td>";
                $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
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
                if(isset($totalresultadosfinancieros)){
                    $numeroDeNota = $totalresultadosfinancieros['numeronota'];//existen estos valores
                }
                ?>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
            $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
            while($mesAMostrar<=$fechaFinConsulta) {
                $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                if(!isset($totalPeriodo[$mesAMostrar])){
                    $totalPeriodo[$mesAMostrar] = 0;//existen estos valores
                }
                $total = isset($totalresultadosfinancieros[$periodoMesAMostrar])?$totalresultadosfinancieros[$periodoMesAMostrar]:0;
                $totalPeriodo[$mesAMostrar] += $total;
                echo '<td  class="numericTD">' .
                    number_format($total, 2, ",", ".")
                    . "</td>";
                $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
            }
            ?>
        </tr>
        <tr>
            <td>Interés del capital propio
                <?php
                $numeroDeNota = "";
                if(isset($totalInteresdelcapitalpropio)){
                    $numeroDeNota = $totalInteresdelcapitalpropio['numeronota'];//existen estos valores
                }
                ?>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
            $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
            while($mesAMostrar<=$fechaFinConsulta) {
                $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                if(!isset($totalPeriodo[$mesAMostrar])){
                    $totalPeriodo[$mesAMostrar] = 0;//existen estos valores
                }
                $total = isset($totalInteresdelcapitalpropio[$periodoMesAMostrar])?$totalInteresdelcapitalpropio[$periodoMesAMostrar]:0;
                $totalPeriodo[$mesAMostrar] += $total;
                echo '<td  class="numericTD">' .
                    number_format($total, 2, ",", ".")
                    . "</td>";
                $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
            }
            ?>
        </tr>
        <tr>
            <td>Otros ingresos
                <?php
                $numeroDeNota = "";
                if(isset($totalOtrosIngresos)){
                    $numeroDeNota = $totalOtrosIngresos['numeronota'];//existen estos valores
                }
                ?>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
            $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
            while($mesAMostrar<=$fechaFinConsulta) {
                $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                if(!isset($totalPeriodo[$mesAMostrar])){
                    $totalPeriodo[$mesAMostrar] = 0;//existen estos valores
                }
                $total = isset($totalOtrosIngresos[$periodoMesAMostrar])?$totalOtrosIngresos[$periodoMesAMostrar]:0;
                $totalPeriodo[$mesAMostrar] += $total;
                echo '<td  class="numericTD">' .
                    number_format($total, 2, ",", ".")
                    . "</td>";
                $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
            }
            ?>
        </tr>
        <tr style="background-color: #d0d9ff">
            <th>
                Ganancia antes del impuesto a las ganancias de operaciones que continúan
            </th>
            <th></th>
            <?php
            $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
            while($mesAMostrar<=$fechaFinConsulta) {
                echo '<td  class="numericTD">' .
                    number_format($totalPeriodo[$mesAMostrar], 2, ",", ".")
                    . "</td>";
                $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
            }
            ?>
        </tr>
        <tr>
            <td>
                Impuesto a las ganancias 															 			Nota			3
                <?php
                $numeroDeNota = "";
                if(isset($totalImpuestoALaGanancia)){
                    $numeroDeNota = $totalImpuestoALaGanancia['numeronota'];//existen estos valores
                }
                ?>
            </td>
            <td>
                <?php echo $numeroDeNota!=""?"Nota ".$numeroDeNota:""; ?>
            </td>
            <?php
            $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
            while($mesAMostrar<=$fechaFinConsulta) {
                $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                if(!isset($totalPeriodo[$mesAMostrar])){
                    $totalPeriodo[$mesAMostrar] = 0;//existen estos valores
                }
                $total = isset($totalImpuestoALaGanancia[$periodoMesAMostrar])?$totalImpuestoALaGanancia[$periodoMesAMostrar]:0;
                $totalPeriodo[$mesAMostrar] += $total;
                echo '<td  class="numericTD">' .
                    number_format($total, 2, ",", ".")
                    . "</td>";
                $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
            }
            ?>
        </tr>
        <tr style="background-color: #d0d9ff">
            <th>
                Ganancia de las operaciones que continúan
            </th>
            <th></th>
            <?php
            $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
            while($mesAMostrar<=$fechaFinConsulta) {
                echo '<td  class="numericTD">' .
                    number_format($totalPeriodo[$mesAMostrar], 2, ",", ".")
                    . "</td>";
                $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
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
            $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
            while($mesAMostrar<=$fechaFinConsulta) {
                echo '<th  class="numericTD">' .
                    number_format($totalPeriodo[$mesAMostrar], 2, ",", ".")
                    . "</th>";
                $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
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
            $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
            while($mesAMostrar<=$fechaFinConsulta) {
                echo '<th  class="numericTD">' .
                    number_format($totalPeriodo[$mesAMostrar], 2, ",", ".")
                    . "</th>";
                $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
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
</div>
<?php
function mostrarNotaDelGrupo($arrayCuentasxPeriodos,$indexCuentas,$nombreNota,$numerofijo,$fechaInicioConsulta,$fechaFinConsulta){
    $keysCuentas = array_keys($arrayCuentasxPeriodos);
    ?>
    <table>
        <tr>
            <td>Conceptos</td>
            <td colspan="2">Corriente</td>
        </tr>
        <tr>
            <td><?php echo $nombreNota?></td>
            <td>Anterior</td>
            <td>Actual</td>
        </tr>
        <?php
        $totalNota = [];
        foreach ($indexCuentas as $index) {
            $numeroCuenta = $keysCuentas[$index];
            echo "<tr>";
            echo '<td>'.$arrayCuentasxPeriodos[$numeroCuenta]['nombrecuenta'].'</td>' ;
            $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
            while($mesAMostrar<=$fechaFinConsulta) {
                $periodoMesAMostrar = date('Y', strtotime($mesAMostrar . '-01-01'));
                if(!isset($totalNota[$mesAMostrar])) {
                    $totalNota[$mesAMostrar]=0;
                }
                echo '<td  class="numericTD">' .
                    number_format($arrayCuentasxPeriodos[$numeroCuenta][$periodoMesAMostrar], 2, ",", ".")
                    . "</td>";
                $totalNota[$mesAMostrar]+=$arrayCuentasxPeriodos[$numeroCuenta][$periodoMesAMostrar];

                $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
            }
            echo "</tr>";
        }
        echo "<tr><td>Total ".$nombreNota."</td>";
        $mesAMostrar = date('Y', strtotime($fechaInicioConsulta.'-01-01'));
        while($mesAMostrar<=$fechaFinConsulta) {
            echo '<td  class="numericTD">' .
                number_format($totalNota[$mesAMostrar], 2, ",", ".")
                . "</td>";
            $mesAMostrar = date('Y', strtotime($mesAMostrar . "-01-01 +1 Year"));
        }
        echo "</tr>";
        ?>
    </table>
    <?php
    return $totalNota;
}
function mostrarNotasDeGastos($arrayCuentasxPeriodos,$indexCuentas,$nombreNota,$numerofijo,$fechaInicioConsulta,$fechaFinConsulta,&$totalAnexoII){
    $keysCuentas = array_keys($arrayCuentasxPeriodos);
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
            echo '<td  class="numericTD">' .
                number_format($subtottalPeriodo*0.0, 2, ",", ".")
                . "</td>";
            echo '<td  class="numericTD">' .
                number_format($subtottalPeriodo*0.25, 2, ",", ".")
                . "</td>";
            echo '<td  class="numericTD">' .
                number_format($subtottalPeriodo*0.75, 2, ",", ".")
                . "</td>";
            echo '<td  class="numericTD">' .
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
            echo '<td  class="numericTD">' .
                number_format($subtottalPeriodo, 2, ",", ".")
                . "</td>";
            echo "</tr>";
        }
        ?>
    <?php
    return $totalAnexoII;
}
?>