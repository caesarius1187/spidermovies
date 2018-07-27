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
    <div id="notaLibroSueldoContent<?php echo $cliid; ?>" name="" class="index" style="margin: 10px 0px; /*page-break-before:always*/">
        <?php
        $empleadoDatos = array();
        $miempleado = array();
        $valores = [];
        $cantidadLiquidaciones = 0;
        foreach ($empleados as $kemp => $empleado) {
            foreach ($empleado['Valorrecibo'] as $valorrecibo) {
                //Vamos a tomar un Valor Recibo por empleado
                $cantidadLiquidaciones ++;
                break;
            }
        }
        $dia = date('d');
        $mes = date('m');
        $ano = date('Y');
        ?>
        <div style="text-align: right">Salta,<?php echo $dia." de ".$mes." de ".$ano ;?></div>
        MINISTERIO DE TRABAJO</br>
        SECRETARIA DE TRABAJO</br>
        BOLIVAR N° 141</br>
        </br>
        </br>
        Me dirijo a Ud. a fin de solicitarle la rúbrica de hojas móviles correspondientes a: </br>
        </br>
        •	RAZON SOCIAL: <?php echo $cliente['Cliente']['nombre']; ?>	</br>
        •	CUIT: <?php echo $cliente['Cliente']['cuitcontribullente']; ?></br>
        <?php 
        $numeroPadron = 0;
        if(isset($cliente['Impcli'][0])){
            $numeroPadron = $cliente['Impcli'][0]['padron'];
        }
        ?>
        •	PADRON NUMERO: <?php echo $numeroPadron; ?></br>
        <?php 
        $domicilio = 0;
        if(isset($cliente['Domicilio'][0])){
            $domicilio = $cliente['Domicilio'][0]['calle']." - ";
            $domicilio .= $cliente['Domicilio'][0]['Localidade']['Partido']['nombre'];
            $domicilio .= $cliente['Domicilio'][0]['Localidade']['nombre'];
        }
        ?>
        •	DOMICILIO: <?php echo $domicilio; ?></br>
        •	CANTIDAD DE PERSONAL: <?php echo $cantidadLiquidaciones; ?> (<?php 
                echo num2letras(number_format($cantidadLiquidaciones/*neto*/, 2, ".", "")); 
                ?>)</br>
        •	PERIODO: JUNIO  2018</br>
        •	HOJAS:  </br>
        </br>
        </br>
        Sin otro particular saludo a Ud.</br>
        </br>
        Atentamente,</br>
        </br>
        </br>
        FIRMA DEL CONTRIBUYENTE</br>
        CUIT <?php echo $cliente['Cliente']['cuitcontribullente']; ?></br>
    </div>
</div>
<div  style="width:100%;height: 1px; page-break-before:always"></div>
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
<?php 
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
    $end_num1=ucfirst($tex)/*.' pesos '*//*.$float[1].'/100 M.N.'*/;

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
    if ((int)$ent === 0) return $end_num1./*'con Cero Centavos' .*/ $fin;
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