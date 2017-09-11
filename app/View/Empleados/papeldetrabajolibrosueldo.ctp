<?php
    echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
    echo $this->Form->input('empidPDT',array('value'=>$empid,'type'=>'hidden'));
    if(count($empleado['Valorrecibo'])==0){
        //este empleado no tiene liquidacion
        return "";
    }
    if($empleado['Empleado']['conveniocolectivotrabajo_id']=='10'){
        /*Servicio domestico no tiene q responder recibo*/
        return "";
    }
 echo $this->Form->button('Imprimir',
    array('type' => 'button',
        'class' =>"btn_imprimir",
        'onClick' => "openWinLibroSueldo()"
    )
);
?>
<div id="divLibroSueldo" class="parafiltrarempleados" valorparafiltrar="<?php echo $empleado['Empleado']['nombre']." ".$empleado['Empleado']['cuit']?>" >
    <div id="libroSueldoContent<?php echo $empid; ?>" name="" class="index" style="margin: 10px 0px; /*page-break-before:always*/">
        <?php
        $liquidacion = $empleado['Valorrecibo'][0]['tipoliquidacion'];
        $empleadoDatos = array();
        $miempleado = array();
        $valores = [];
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
        ?>
    <table id="tblLibroSueldo" class="tblInforme" cellspacing="0">
        <tr><td colspan="20"></td></tr>
        <tr>
            <td colspan="20">
                LIBRO DE SUELDOS - LEY 20744 t.c. Art.52 - Hojas moviles Periodo: <?php echo $periodo ?>
                <?php
                switch ($liquidacion){
                    case '1':
                        echo "Primera Quincena";
                        break;
                    case '2':
                        echo "Segunda Quincena";
                        break;
                    case '7':
                        echo "SAC";
                        break;
                }
                ?>
                <div style="float: right" class="divToRight">
                <?php echo $this->Form->input('hoja',
                    [
                        'value'=>'1',
                        'class'=>'hideOnPrint',
                        'div'=>false,
                        'label'=>[
                            'style'=>'display:inline-block;height: 3px;font-size:10px'
                        ],
                        'style'=>'height: 5px;max-width:30px'
                    ]); ?> &nbsp; &nbsp; <?php
                    //vamos a buscar el numero de padron del impuesto SUSS
                    $numeroPadron = 0;
                    if(isset($empleado['Cliente']['Impcli'][0])){
                        $numeroPadron = $empleado['Cliente']['Impcli'][0]['padron'];
                    }
                    echo $this->Form->input('tomo',[
                        'div'=>false,
                        'class'=>'hideOnPrint',
                        //Esto lo tengo que sacar del Impcli SUSS/*impuesto_id = 10*/
                        'value'=>$numeroPadron,
                        'label'=>[
                            'style'=>'display:inline-block;height: 3px;font-size:10px',
                            'text'=>'Padron'
                        ],
                        'style'=>'height: 5px;max-width:30px',
                    ])?>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="20">
                Empresa: <?php echo $empleado['Cliente']['nombre']; ?>
                CUIT: <?php echo $empleado['Cliente']['cuitcontribullente']; ?>
                Domicilio: <?php echo $empleado['Domicilio']['calle']." ".
                    $empleado['Domicilio']['Localidade']['Partido']['nombre']." ".
                    $empleado['Domicilio']['Localidade']['nombre']." "; ?>
            </td>
        </tr>
        <tr>
            <td colspan="20">
                Actividad : <?php
                foreach ($empleado['Cliente']['Actividadcliente'] as $actividad){
                    echo $actividad['Actividade']['nombre'];
                    break;
                }  ?>
            </td>
        </tr>
        <tr>
            <td colspan="20"> <hr width="450px" class="dottedhr" /></td>
        </tr>
        <tr>
            <td colspan = "20" >
                L.P.: <?php echo $empleado['Empleado']['legajo']." Apellido y nombre: ".$empleado['Empleado']['nombre'] ?>
                CUIL: <?php echo $empleado['Empleado']['cuit'] ?>
                Provincia: <?php echo $empleado['Domicilio']['Localidade']['Partido']['nombre'] ?>
                DNI N° <?php echo $empleado['Empleado']['dni'] ?>
                Cargo: <?php echo $empleado['Cargo']['nombre'] ?>
                <?php echo $empleado['Empleado']['jornada']=='0.5'?"Media":"" ?>
                Jornada: <?php echo $empleado['Empleado']['jornada']=='0.5'?"":"Completa" ?>
                F. Ingreso:  <?php echo date('d-m-Y',strtotime($empleado['Empleado']['fechaalta'])); ?>
                Basico: <?php echo number_format($empleado['Cargo']['sueldobasico']+$empleado['Cargo']['preciohora'], 2, ",", "."); ?>
                Modalid ad de contratacion:  <?php echo $empleado['Empleado']['codigoafip']; ?>
            </td>
        </tr><!--5-->
        <tr>
            <td colspan="20"> <hr width="450px" class="dottedhr" /></td>
        </tr><!--7-->
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
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td colspan="3" align="right">
               <div style="float:right;"> Firma del Empleado:</div>
            </td>
            <td colspan="10" style="vertical-align: bottom;">
                <hr width="450px" class="dottedhr" />
            </td>
        </tr>
        <tr>
            <td colspan="3">
            </td>
            <td colspan="10">
                <div style="width:100%;text-align: center;">
                <?php echo $empleado['Empleado']['nombre'];?>
                </div>
            </td>
        </tr>
    </table>
</div>
</div>
<div  style="width:100%;height: 1px; page-break-before:always"></div>
