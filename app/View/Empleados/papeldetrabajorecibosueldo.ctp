<?php
if(count($empleado['Valorrecibo'])==0){
    //este empleado no tiene liquidacion
    return "";
}
?>
<div  style="width:100%;height: 1px; /*break-before: page!important*/"></div>
<div id="reciboContenedor"  style="width: 100%;">
    <?php
    echo $this->Form->button('Imprimir',
        array('type' => 'button',
            'class' =>"btn_imprimir",
//            'style'=>"float:left",
            'onClick' => "openWin()"
        )
    );
    ?>
    <div id="divToPrintRecibo" style="margin-right: 10px;" class="divToPrint">
        <div id="divRecibo<?php echo $empid;?>" >
            <div id="reciboOriginal<?php echo $empid;?>" class="tblReciboSueldo divToLeft" style="margin: 0px 10px;width: 480px; float:left; ">
                    <?php
                    $empleadoDatos = [];
                    $miempleado = [];
                    $valores = [];

                    $liquidacion = $empleado['Valorrecibo'][0]['tipoliquidacion'];

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
                            $cantidad = '1';
                            if(isset($valores[$conceptocantidad]['valor'])){
                                $cantidad = $valores[$conceptocantidad]['valor'];
                            }
                            if($valorrecibo['Cctxconcepto']['Concepto']['id']=='95'){
                                //calculo de la cantidad para Acuerdo Remunerativo
                                $cantidad=
                                    $valores[12]['valor']/*Días Trabajados u Horas */
                                    +$valores[55]['valor']/*Inasistencias Pagas*/
                                    -$valores[135]['valor']/*Suspensiones*/
                                    -$valores[56]['valor']/*Inasistencias Descontadas*/;
                            }
                            $valores[$conceptoid]['cantidad']=$cantidad;
                        }
                        $valores[$conceptoid]['valor'] += $valorrecibo['valor'];
                    }
                    ?>
                    <table id="tblReciboSueldo" cellspacing="0" class="tblInforme" style="padding:0px">
                        <tr>
                            <td colspan="20" class="tdWithBorder" style="border-top: 0px;border-left: 0px;border-right: 0px;">
                                <b>Recibo de remuneraciones - Periodo: <?php
                                    $timeperiodo =strtotime('01-'.$pemes.'-'.$peanio.' +1 months');
                                    $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
                                    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
//                                    setlocale(LC_ALL,"es_ES");
//                                    $timestr =
//                                        strftime('%B del %Y',strtotime('01-'.$pemes.'-'.$peanio));
                                    $timestr =
                                        $meses[date('n',$timeperiodo)-1]. " del ".date('Y',$timeperiodo) ;
                                    echo $timestr ;
                                    ?>
                                <?php
                                $diasTop=4;
                                switch ($liquidacion){
                                    case '1':
                                        echo "Primera Quincena";
                                        $diasTop= 20;
                                        break;
                                    case '2':
                                        echo "Segunda Quincena";
                                        $diasTop= 36;
                                        break;
                                    case '3':
                                        $diasTop= 36;
                                        break;
                                    case '7':
                                        echo "SAC";
                                        $diasTop= 36;
                                        break;
                                }
                                ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="20" class="tdWithBorder tdWithText">
                                <b>Empleador: </b><?php echo $empleado['Cliente']['nombre']; ?>
                                <b>CUIT: </b><?php echo $empleado['Cliente']['cuitcontribullente']; ?>
                                <b>Domicilio: </b><?php echo $empleado['Domicilio']['calle']; ?>
                                <b>Provincia: </b><?php echo $empleado['Domicilio']['Localidade']['Partido']['nombre'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="20" class="tdWithBorder tdWithText">
                                <b> Empleado: </b>Legajo <?php echo $empleado['Empleado']['legajo'] ?>
                                <b>Apellido y nombre: </b> <?php echo $empleado['Empleado']['nombre'] ?>
                                <b>CUIL: </b><?php echo $empleado['Empleado']['cuit']; ?>
                                </br>
                                <b>Fecha de ingreso: </b> <?php echo date('d/m/Y',strtotime($empleado['Empleado']['fechaalta'])); ?>
                                <b>O.S.: </b> <?php echo $valores['33']['concepto']; ?>
                                <b>Condición: </b> <?php echo $empleado['Empleado']['codigoafip']; ?>
                                <b>Banco: </b> <p id="pbancoempleado" style="display: inline;"></p>
                                    <?php
                                    echo $empleado['Empleado']['impuesto_id'];
                                    echo $this->Form->input('bancoempleados',[
                                        'label'=>false,
                                        'type'=>'select',
                                        'options'=>$impuestos,
                                        'value'=>$empleado['Empleado']['impuesto_id'],
                                        'class'=>'hideOnPrint',
                                        'empty'=>'Efectivo'
                                    ]); ?>
                                <b>Cargo: </b><?php echo $empleado['Cargo']['nombre'] ?>
                                <b>Jornada: </b><?php echo $empleado['Empleado']['jornada']=='0.5'?"Media":"Completa" ?>
                                <b>Basico: </b><?php echo number_format($empleado['Cargo']['sueldobasico']+$empleado['Cargo']['preciohora'], 2, ",", "."); ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan = "22">
                                <b>Aportes: </b>
                                <b>Lapso: </b><?php echo $pemes."-".$peanio; ?>
                                <b>Fecha: </b> <?php echo $fchvto; ?>
                                <b id="bbanco">Banco: </b><p id="pbanco" style="display: inline;"></p> <?php echo $this->Form->input('bancos',[
                                        'label'=>false,'type'=>'select','options'=>$impclis,'class'=>'hideOnPrint'
                                    ]); ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="30px" class="tdWithBorder">
                                <b>Cod.</b>
                            </td>
                            <td class="tdWithBorder">
                                <b>Concepto</b>
                            </td>
                            <td width="20px" class="tdWithBorder">
                                <b>Q</b>
                            </td>
                            <td width="35px" class="tdWithBorder">
                                <b>Rem.c/d</b>
                            </td>
                            <td width="35px" class="tdWithBorder">
                                <b>Rem.s/d</b>
                            </td>
                            <td width="35px" class="tdWithBorder">
                                <b>Deducciones</b>
                            </td>
                        </tr>
                        <?php

                        foreach ($valores as $valor){
                            if($valor['seccion']=='DATOS')continue;
                            if($valor['seccion']=='TOTALES'){
                                //si es el redondeo mostralo sino segui la flecha guacho
                                if(!in_array($valor['numero'],['124','132','133'])){
                                    continue;
                                }
                            }
                            if($valor['seccion']=='OBRA SOCIAL')continue;
                            if($valor['valor']*1==0)continue;
                            if($valor['estotal']=='1')continue;
                            ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    <?php echo $valor['codigorecibo']; ?>
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    <?php echo $valor['concepto']; ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo $valor['cantidad']; ?>
                                </td>
                                <?php
                                    switch ($valor['seccion']){
                                        case 'REMUNERATIVOS':
                                            $suma = 1;
                                            if($valor['resta']*1==1){
                                                $suma = -1;
                                            }
                                            //hacerlo para totales tmb
                                            
                                            ?>
                                            <td class="tdWithLeftRightBorder tdWithNumber">
                                                <?php echo number_format($valor['valor']*$suma, 2, ",", "."); ?>
                                            </td>
                                            <td class="tdWithLeftRightBorder tdWithNumber"></td>
                                            <td class="tdWithLeftRightBorder tdWithNumber"></td>
                                            <?php
                                            break;
                                        case 'NO REMUNERATIVOS':
                                            ?>
                                            <td class="tdWithLeftRightBorder tdWithNumber"></td>
                                            <td class="tdWithLeftRightBorder tdWithNumber">
                                                <?php echo number_format($valor['valor'], 2, ",", "."); ?>
                                            </td>
                                            <td class="tdWithLeftRightBorder tdWithNumber"></td>
                                            <?php
                                            break;
                                        case 'APORTES':
                                            ?>
                                            <td class="tdWithLeftRightBorder tdWithNumber"></td>
                                            <td class="tdWithLeftRightBorder tdWithNumber"></td>
                                            <td class="tdWithLeftRightBorder tdWithNumber">
                                            <?php echo number_format($valor['valor'], 2, ",", "."); ?>
                                            </td>
                                            <?php
                                            break;
                                        case 'TOTALES':
                                            switch ($valor['numero']){
                                                case '124':
                                                    ?>
                                                    <td class="tdWithLeftRightBorder tdWithNumber"></td>
                                                    <td class="tdWithLeftRightBorder tdWithNumber">
                                                        <?php echo number_format($valor['valor'], 2, ",", "."); ?>
                                                    </td>
                                                    <td class="tdWithLeftRightBorder tdWithNumber"></td>
                                                    <?php
                                                break;
                                                case '132':
                                                case '133':
                                                    ?>
                                                    <td class="tdWithLeftRightBorder tdWithNumber"></td>
                                                    <td class="tdWithLeftRightBorder tdWithNumber"></td>
                                                    <td class="tdWithLeftRightBorder tdWithNumber">
                                                        <?php echo number_format($valor['valor'], 2, ",", "."); ?>
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
                            <td colspan="3" class="tdWithBorder">
                                <b>Fecha de pago: </b><?php
                                    //Si la fecha de hoy es anterior al 5 del periodo que estamos pagando poner esa fecha
                                    //sino tiene q ser el dia 5
                                    $topDate = date('d-m-Y',strtotime('01-'.$periodo.' +'.$diasTop.' days'));

//                                    $today = date('d-m-Y');
//                                    if($today<$topDate){
//                                        echo $today;
//                                    }else{
                                        echo $topDate;
//                                    }
                                    ?>
                                <b> Lugar de pago: </b><?php
                                echo $empleado['Domicilio']['Localidade']['Partido']['nombre']."-"
                                    .$empleado['Domicilio']['Localidade']['nombre']
                                ?>
                            </td>
                            <td class="tdWithBorder tdWithNumber" style="text-align: right;"><?php
                                echo number_format($valores['41']['valor'], 2, ",", ".");?>
                            </td>
                            <?php
                            $totalRemSD = $valores['124']['valor']*1//redondeo
                                         + $valores['42']['valor']*1//Total Remunerativos S/D Excepto INdemnizatorio
                                         + $valores['43']['valor']*1//Total Remunerativos S/D Indemnizatorio
                                        ;
                            ?>
                            <td class="tdWithBorder tdWithNumber" style="text-align: right;"><?php echo number_format($totalRemSD, 2, ",", ".");?></td>
                            <td class="tdWithBorder tdWithNumber" style="text-align: right;">
                                <?php
                                echo number_format(
                                    $valores['45']['valor']*1+//total descuento
                                    $valores['131']['valor']*1+//embargos
                                    $valores['132']['valor']*1//anticipos
                                    , 2, ",", ".");?></td>
                        </tr>
                        <tr>
                            <td colspan="3" rowspan="2" class="tdWithBorder" style="vertical-align: text-top;text-align:
                            left;border-bottom: 0px;border-left: 0px;">
                                Recibi conforme la suma de : $<?php echo number_format($valores['46']['valor']/*neto*/, 2, ",", ".");?>
                                Son pesos: <?php echo num2letras(number_format($valores['46']['valor']/*neto*/, 2, ".", "")); ?>
                                En concepto de remuneraciones correspondintes al periodo arriba indicado dejando constancia
                                de haber recibido copia fiel de este recibo.
                            </td>
                            <td colspan="3" class="tdWithBorder" style="text-align: -webkit-center;vertical-align: text-top;">
                                <b>Neto: </b><?php echo number_format($valores['46']['valor']/*neto*/, 2, ",", ".");?>
                            </td>
                        </tr>
                        <tr >
                            <td colspan="3" class="tdWithBorder" style="text-align: center;border-bottom: 0px;border-right: 0px;" >
                                </br> </br>
                                ................................</br>
                                <p id="firmaempleador"><b>Firma empleado</b></p>
                            </td>
                        </tr>
                    </table>
            </div>
            <div id="reciboDuplicado<?php echo $empid;?>" class="tblReciboSueldo divToRight" style="margin: 0px 10px;width: 480px; float: right; "></div>
        </div>
    </div>
</div>
<div  style="width:100%;height: 1px; page-break-before:always"></div>

<?php
/*! 
  @function num2letras () 
  @abstract Dado un n?mero lo devuelve escrito. 
  @param $num number - N?mero a convertir. 
  @param $fem bool - Forma femenina (true) o no (false). 
  @param $dec bool - Con decimales (true) o no (false). 
  @result string - Devuelve el n?mero escrito en letra. 

*/
function num2letras($num, $fem = false, $dec = true) {
    $matuni[2]  = "dos";
    $matuni[3]  = "tres";
    $matuni[4]  = "cuatro";
    $matuni[5]  = "cinco";
    $matuni[6]  = "seis";
    $matuni[7]  = "siete";
    $matuni[8]  = "ocho";
    $matuni[9]  = "nueve";
    $matuni[10] = "diez";
    $matuni[11] = "once";
    $matuni[12] = "doce";
    $matuni[13] = "trece";
    $matuni[14] = "catorce";
    $matuni[15] = "quince";
    $matuni[16] = "dieciseis";
    $matuni[17] = "diecisiete";
    $matuni[18] = "dieciocho";
    $matuni[19] = "diecinueve";
    $matuni[20] = "veinte";
    $matunisub[2] = "dos";
    $matunisub[3] = "tres";
    $matunisub[4] = "cuatro";
    $matunisub[5] = "quin";
    $matunisub[6] = "seis";
    $matunisub[7] = "sete";
    $matunisub[8] = "ocho";
    $matunisub[9] = "nove";

    $matdec[2] = "veint";
    $matdec[3] = "treinta";
    $matdec[4] = "cuarenta";
    $matdec[5] = "cincuenta";
    $matdec[6] = "sesenta";
    $matdec[7] = "setenta";
    $matdec[8] = "ochenta";
    $matdec[9] = "noventa";
    $matsub[3]  = 'mill';
    $matsub[5]  = 'bill';
    $matsub[7]  = 'mill';
    $matsub[9]  = 'trill';
    $matsub[11] = 'mill';
    $matsub[13] = 'bill';
    $matsub[15] = 'mill';
    $matmil[4]  = 'millones';
    $matmil[6]  = 'billones';
    $matmil[7]  = 'de billones';
    $matmil[8]  = 'millones de billones';
    $matmil[10] = 'trillones';
    $matmil[11] = 'de trillones';
    $matmil[12] = 'millones de trillones';
    $matmil[13] = 'de trillones';
    $matmil[14] = 'billones de trillones';
    $matmil[15] = 'de billones de trillones';
    $matmil[16] = 'millones de billones de trillones';

    //Zi hack
    $float=explode('.',$num);
    $num=$float[0];

    $num = trim((string)@$num);
    if ($num[0] == '-') {
        $neg = 'menos ';
        $num = substr($num, 1);
    }else
        $neg = '';
    while ($num[0] == '0') $num = substr($num, 1);
    if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
    $zeros = true;
    $punt = false;
    $ent = '';
    $fra = '';
    for ($c = 0; $c < strlen($num); $c++) {
        $n = $num[$c];
        if (! (strpos(".,'''", $n) === false)) {
            if ($punt) break;
            else{
                $punt = true;
                continue;
            }

        }elseif (! (strpos('0123456789', $n) === false)) {
            if ($punt) {
                if ($n != '0') $zeros = false;
                $fra .= $n;
            }else

                $ent .= $n;
        }else

            break;

    }
    $ent = '     ' . $ent;
    if ($dec and $fra and ! $zeros) {
        $fin = ' coma';
        for ($n = 0; $n < strlen($fra); $n++) {
            if (($s = $fra[$n]) == '0')
                $fin .= ' cero';
            elseif ($s == '1')
                $fin .= $fem ? ' una' : ' un';
            else
                $fin .= ' ' . $matuni[$s];
        }
    }else
        $fin = '';
    if ((int)$ent === 0) return 'con Cero centavos ' . $fin;
    $tex = '';
    $sub = 0;
    $mils = 0;
    $neutro = false;
    while ( ($num = substr($ent, -3)) != '   ') {
        $ent = substr($ent, 0, -3);
        if (++$sub < 3 and $fem) {
            $matuni[1] = 'una';
            $subcent = 'as';
        }else{
            $matuni[1] = $neutro ? 'un' : 'uno';
            $subcent = 'os';
        }
        $t = '';
        $n2 = substr($num, 1);
        if ($n2 == '00') {
        }elseif ($n2 < 21)
            $t = ' ' . $matuni[(int)$n2];
        elseif ($n2 < 30) {
            $n3 = $num[2];
            if ($n3 != 0) $t = 'i' . $matuni[$n3];
            $n2 = $num[1];
            $t = ' ' . $matdec[$n2] . $t;
        }else{
            $n3 = $num[2];
            if ($n3 != 0) $t = ' y ' . $matuni[$n3];
            $n2 = $num[1];
            $t = ' ' . $matdec[$n2] . $t;
        }
        $n = $num[0];
        if ($n == 1) {
            $t = ' ciento' . $t;
        }elseif ($n == 5){
            $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
        }elseif ($n != 0){
            $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
        }
        if ($sub == 1) {
        }elseif (! isset($matsub[$sub])) {
            if ($num == 1) {
                $t = ' mil';
            }elseif ($num > 1){
                $t .= ' mil';
            }
        }elseif ($num == 1) {
            $t .= ' ' . $matsub[$sub] . '?n';
        }elseif ($num > 1){
            $t .= ' ' . $matsub[$sub] . 'ones';
        }
        if ($num == '000') $mils ++;
        elseif ($mils != 0) {
            if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
            $mils = 0;
        }
        $neutro = true;
        $tex = $t . $tex;
    }
    $tex = $neg . substr($tex, 1) . $fin;
    //Zi hack --> return ucfirst($tex);
    $end_num1=ucfirst($tex).' pesos '/*.$float[1].'/100 M.N.'*/;

    $num=$float[1];

    $num = trim((string)@$num);
    if ($num[0] == '-') {
        $neg = 'menos ';
        $num = substr($num, 1);
    }else
        $neg = '';
    while ($num[0] == '0') $num = substr($num, 1);
    if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
    $zeros = true;
    $punt = false;
    $ent = '';
    $fra = '';
    for ($c = 0; $c < strlen($num); $c++) {
        $n = $num[$c];
        if (! (strpos(".,'''", $n) === false)) {
            if ($punt) break;
            else{
                $punt = true;
                continue;
            }

        }elseif (! (strpos('0123456789', $n) === false)) {
            if ($punt) {
                if ($n != '0') $zeros = false;
                $fra .= $n;
            }else

                $ent .= $n;
        }else

            break;

    }
    $ent = '     ' . $ent;
    if ($dec and $fra and ! $zeros) {
        $fin = ' coma';
        for ($n = 0; $n < strlen($fra); $n++) {
            if (($s = $fra[$n]) == '0')
                $fin .= ' cero';
            elseif ($s == '1')
                $fin .= $fem ? ' una' : ' un';
            else
                $fin .= ' ' . $matuni[$s];
        }
    }else
        $fin = '';
    if ((int)$ent === 0) return $end_num1.'con Cero Centavos' . $fin;
    $tex = '';
    $sub = 0;
    $mils = 0;
    $neutro = false;
    while ( ($num = substr($ent, -3)) != '   ') {
        $ent = substr($ent, 0, -3);
        if (++$sub < 3 and $fem) {
            $matuni[1] = 'una';
            $subcent = 'as';
        }else{
            $matuni[1] = $neutro ? 'un' : 'uno';
            $subcent = 'os';
        }
        $t = '';
        $n2 = substr($num, 1);
        if ($n2 == '00') {
        }elseif ($n2 < 21)
            $t = ' ' . $matuni[(int)$n2];
        elseif ($n2 < 30) {
            $n3 = $num[2];
            if ($n3 != 0) $t = 'i' . $matuni[$n3];
            $n2 = $num[1];
            $t = ' ' . $matdec[$n2] . $t;
        }else{
            $n3 = $num[2];
            if ($n3 != 0) $t = ' y ' . $matuni[$n3];
            $n2 = $num[1];
            $t = ' ' . $matdec[$n2] . $t;
        }
        $n = $num[0];
        if ($n == 1) {
            $t = ' ciento' . $t;
        }elseif ($n == 5){
            $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
        }elseif ($n != 0){
            $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
        }
        if ($sub == 1) {
        }elseif (! isset($matsub[$sub])) {
            if ($num == 1) {
                $t = ' mil';
            }elseif ($num > 1){
                $t .= ' mil';
            }
        }elseif ($num == 1) {
            $t .= ' ' . $matsub[$sub] . '?n';
        }elseif ($num > 1){
            $t .= ' ' . $matsub[$sub] . 'ones';
        }
        if ($num == '000') $mils ++;
        elseif ($mils != 0) {
            if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
            $mils = 0;
        }
        $neutro = true;
        $tex = $t . $tex;
    }
    $tex = $neg . substr($tex, 1) . $fin;
    //Zi hack --> return ucfirst($tex);
    $end_num = $end_num1.' con '.ucfirst($tex).' centavos ';
    return $end_num;
}


?> 