<?php
echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
echo $this->Form->input('cliidPDT',array('value'=>$cliid,'type'=>'hidden'));
echo $this->Form->button('Imprimir',
   array('type' => 'button',
       'class' =>"btn_imprimir",
       'onClick' => "openWinLibroSueldo()"
    )
);
?>
<div id="divResumenLibroSueldo" class="parafiltrarempleados">
    <div id="resumenlibroSueldoContent<?php echo $cliid; ?>" name="" class="index" style="margin: 10px 0px; /*page-break-before:always*/">
        <?php
        $empleadoDatos = array();
        $miempleado = array();
        $valores = [];
        foreach ($empleados as $kemp => $empleado) {
            foreach ($empleado['Valorrecibo'] as $valorrecibo) {
                //Dias Trabajados u Horas
                $conceptoid = $valorrecibo['Cctxconcepto']['Concepto']['id'];
                if(!isset($valores[$conceptoid])){
                    $valores[$conceptoid]=[];
                    $valores[$conceptoid]['valor']=0;
                    $valores[$conceptoid]['numero']=$valorrecibo['Cctxconcepto']['Concepto']['id'];
                    $valores[$conceptoid]['codigorecibo']=$valorrecibo['Cctxconcepto']['Concepto']['codigorecibo'];
                    $valores[$conceptoid]['seccion']=$valorrecibo['Cctxconcepto']['Concepto']['seccion'];
                    $valores[$conceptoid]['concepto']=$valorrecibo['Cctxconcepto']['nombre'];
                    $valores[$conceptoid]['estotal']=$valorrecibo['Cctxconcepto']['Concepto']['estotal'];
                    $valores[$conceptoid]['resta']=$valorrecibo['Cctxconcepto']['Concepto']['resta'];
                    $conceptocantidad = $valorrecibo['Cctxconcepto']['Concepto']['conceptocantidad'];
                    $cantidad = '';
                    if(isset($valores[$conceptocantidad]['valor'])){
                        $cantidad = $valores[$conceptocantidad]['valor'];
                    }
                    $valores[$conceptoid]['cantidad']=$cantidad;
                }
                $valores[$conceptoid]['valor'] += $valorrecibo['valor'];
            }
        }
        ?>
    <table id="tblResumenLibroSueldo" class="tblInforme" cellspacing="0">
        <tr><td colspan="20"></td></tr>
        <tr>
            <td colspan="20">
                Resumen LIBRO DE SUELDOS - Periodo: <?php echo $periodo ?>                
            </td>
        </tr>
        <tr>
            <td colspan="20">
                Empresa: <?php echo $cliente['Cliente']['nombre']; ?>
                CUIT: <?php echo $cliente['Cliente']['cuitcontribullente']; ?>
                Domicilio: <?php echo $cliente['Domicilio'][0]['calle']." ".
                    $cliente['Domicilio'][0]['Localidade']['Partido']['nombre']." ".
                    $cliente['Domicilio'][0]['Localidade']['nombre']." "; ?>
            </td>
        </tr>
        <tr>
            <td colspan="20">
                Actividad : <?php
                foreach ($cliente['Actividadcliente'] as $actividad){
                    echo $actividad['Actividade']['nombre']." ";
                    break;
                }  ?>
            </td>
        </tr>
        <tr>
            <td colspan="20"> <hr width="450px" class="dottedhr" /></td>
        </tr>              
        <tr>
            <td width="60px">
                Codigo
            </td>
            <td >
                Haberes con/sin aporte
            </td>
            <td>
                Importe
            </td>
            <td>
                Descuentos
            </td>
            <td>
                Importe
            </td>
        </tr><!--10-->
        <tr>
            <td colspan="20"> <hr width="450px" class="dottedhr" /></td>
        </tr><!--11-->
        <?php
        foreach ($valores as $valor){
            if($valor['seccion']=='DATOS')continue;
            if($valor['seccion']=='OBRA SOCIAL')continue;
            if($valor['seccion']=='TOTALES'){
                //si es el redondeo mostralo sino segui la flecha guacho
                if(!in_array($valor['numero'],['124','132','133'])){
                    continue;
                }
            }
            if($valor['valor']*1==0)continue;
            if($valor['estotal']=='1')continue;
            ?>
            <tr class="trConceptoRecibo">
                <td>
                    <?php echo $valor['codigorecibo']; ?>
                </td>

                <?php
                switch ($valor['seccion']){
                    case 'REMUNERATIVOS':
                        $suma = 1;
                        if($valor['resta']*1==1){
                            $suma = -1;
                        }
                        ?>
                        <td>
                            <?php echo $valor['concepto']; ?>
                        </td>
                        <td class="tdWithNumber">
                            <?php echo number_format($valor['valor']*$suma, 2, ",", "."); ?>
                        </td>
                        <td></td>
                        <td></td>
                        <?php
                        break;
                    case 'NO REMUNERATIVOS':
                         $suma = 1;
                        if($valor['resta']*1==1){
                            $suma = -1;
                        }
                        ?>
                        <td>
                            <?php echo $valor['concepto']; ?>
                        </td>
                        <td class="tdWithNumber">
                            <?php echo number_format($valor['valor']*$suma, 2, ",", "."); ?>
                        </td>
                        <td></td>
                        <td></td>
                    <?php
                        break;
                    case 'APORTES':
                        ?>
                        <td></td>
                        <td></td>
                        <td>
                            <?php echo $valor['concepto']; ?>
                        </td>
                        <td class="tdWithNumber">
                            <?php echo number_format($valor['valor'], 2, ",", "."); ?>
                        </td>
                        <?php
                        break;
                    case 'TOTALES':
                        switch ($valor['numero']){
                            case '124':
                                ?>
                                <td>
                                    <?php echo $valor['concepto']; ?>
                                </td>
                                <td class="tdWithNumber">
                                    <?php echo number_format($valor['valor'], 2, ",", "."); ?>
                                </td>
                                <td></td>
                                <td></td>
                                <?php
                                break;
                            case '132':
                            case '133':
                                $suma = 1;
                                if($valor['resta']*1==1){
                                    $suma = -1;
                                }
                                ?>
                                <td></td>
                                <td></td>
                                <td>
                                    <?php echo $valor['concepto']; ?>
                                </td>
                                <td class="tdWithNumber">
                                    <?php echo number_format($valor['valor']*$suma, 2, ",", "."); ?>
                                </td>
                                <?php
                                break;
                        }
                        break;
                }
                ?>
            </tr>
            <?php
        };
        ?>
        <tr>
            <td>990</td>
            <td>
                Total remuneraciones
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($valores['44']['valor']/*Total Remuneraciones*/*1+$valores['124']['valor']/*Redondeo*/*1, 2, ",", "."); ?>
            </td>
            <td>
                Total descuentos
            </td>
            <td class="tdWithNumber">
                <?php echo number_format(
                    $valores['45']['valor']/*Total Aportes*/*1+
                    $valores['131']['valor']/*Embargos*/*1+
                    $valores['132']['valor']/*Anticips*/*1
                    , 2, ",", "."); ?>
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>
                Neto:
            </td>
            <td class="tdWithNumber">
                <?php echo number_format( $valores['46']['valor']/*Neto*/*1, 2, ",", "."); ?>
            </td>
        </tr>
        <tr>
            <td colspan="20"> <hr width="450px" class="dottedhr" /></td>
        </tr>
        <!--
        <tr>
            <td>991</td>
            <td>
                T.Haberes c/aportes
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($neto, 2, ",", "."); ?>
            </td>
            <td>991</td>
            <td>
                T.reten. de ley
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($totaldescuento, 2, ",", "."); ?>
            </td>
        </tr>
        <tr>
            <td>990</td>
            <td>
                Total remuneraciones
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($remuneracioncd, 2, ",", "."); ?>
            </td>
            <td></td>
            <td>
            </td>
            <td>
            </td>
        </tr>
        -->
    </table>
</div>
</div>
<div  style="width:100%;height: 1px; page-break-before:always"></div>
