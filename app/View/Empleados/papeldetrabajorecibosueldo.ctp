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
                    $empleadoDatos = array();
                    $miempleado = array();
                    if(!isset($miempleado['horasDias'])) {
                        $miempleado['basico'] = 0;
                        $miempleado['sueldo'] = 0;
                        $miempleado['inasistencias'] = 0;
                        $miempleado['inasistenciascantidad'] = 0;
                        $miempleado['inasistenciapagas'] = 0;
                        $miempleado['inasistenciapagascantidad'] = 0;
                        $miempleado['suspenciones'] = 0;
                        $miempleado['suspencionescantidad'] = 0;
                        $miempleado['vacacionesremunerativas'] = 0;
                        $miempleado['vacacionescantidad'] = 0;
                        $miempleado['vacacionesnogozadas'] = 0;
                        $miempleado['vacacionesnogozadascantidad'] = 0;
                        $miempleado['feriadoscantidad'] = 0;
                        $miempleado['feriadospagos'] = 0;
                        $miempleado['feriadosnotrabajadoscantidad'] = 0;
                        $miempleado['feriadosnotrabajadospagos'] = 0;
                        $miempleado['adicionales'] = 0;
                        $miempleado['antiguedad'] = 0;
                        $miempleado['acuerdoremunerativonobasico'] = 0;
                        $miempleado['presentismo'] = 0;
                        $miempleado['ajusteretroactivo'] = 0;
                        $miempleado['sac'] = 0;
                        $miempleado['sacIndemnizatorio'] = 0;
                        $miempleado['fondoceselaboral'] = 0;
                        $miempleado['acuerdonoremunerativo'] = 0;
                        $miempleado['antiguedadnoremunerativo'] = 0;
                        $miempleado['presentismonoremunerativo'] = 0;
                        $miempleado['vacacionesnoremunerativas'] = 0;
                        $miempleado['horasDecoracion'] = 0;
                        $miempleado['horasDecoracionCantidad'] = 0;
                        $miempleado['horasSubmuracion'] = 0;
                        $miempleado['horasSubmuracionCantidad'] = 0;
                        $miempleado['horasZanja'] = 0;
                        $miempleado['horasZanjaCantidad'] = 0;
                        $miempleado['horasHormigon'] = 0;
                        $miempleado['horasHormigonCantidad'] = 0;
                        $miempleado['feriadosnoremunerativo'] = 0;
                        $miempleado['horas50cantidad'] = 0;
                        $miempleado['horas100cantidad'] = 0;
                        $miempleado['horas50remunerativa'] = 0;
                        $miempleado['horas100remunerativa'] = 0;
                        $miempleado['horas50noremunerativo'] = 0;
                        $miempleado['horas100noremunerativo'] = 0;
                        $miempleado['sacnoremunerativo'] = 0;
                        $miempleado['otros'] = 0;
                        $miempleado['jubilacion'] = 0;
                        $miempleado['ley19032'] = 0;
                        $miempleado['obrasocial'] = 0;
                        $miempleado['obrasocialnombre'] = 0;
                        $miempleado['obrasocialextraordinario'] = 0;
                        $miempleado['cuotasindical'] = 0;
                        $miempleado['cuotasindicalnombre'] = 0;
                        $miempleado['cuotasindical1'] = 0;
                        $miempleado['cuotasindical2'] = 0;
                        $miempleado['cuotasindical3'] = 0;
                        $miempleado['cuotasindical4'] = 0;
                        $miempleado['renatea'] = 0;
                        $miempleado['totalremuneracion'] = 0;
                        $miempleado['totalremuneracionsd'] = 0;
                        $miempleado['totaldescuento'] = 0;
                        $miempleado['neto'] = 0;
                        $miempleado['anticipos'] = 0;
                        $miempleado['redondeo'] = 0;
                        $miempleado['embargo'] = 0;
                        $miempleado['remuneracioncd'] = 0;
                        $miempleado['diastrabajados'] = 0;
                        $miempleado['adicionalcomplementarioss'] = 0;
                        $miempleado['acuerdoremunerativo'] = 0;
                        $miempleado['plusvacacional'] = 0;
                    }
                    $diastrabajados=0;
                    $jubilacion=0;
                    $basico=0;
                    $adicionales=0;
                    $sueldo=0;
                    $inasistencias=0;
                    $inasistenciascantidad=0;
                    $inasistenciapagas=0;
                    $inasistenciapagascantidad=0;
                    $suspenciones=0;
                    $suspencionescantidad=0;
                    $vacacionesremunerativas=0;
                    $vacacionescantidad=0;
                    $vacacionesnogozadascantidad=0;
                    $vacacionesnogozadas=0;
                    $vacacionesnogozadasIndemnizacion=0;
                    $feriadoscantidad=0;
                    $feriadospagos=0;
                    $feriadosnotrabajadoscantidad=0;
                    $feriadosnotrabajadospagos=0;
                    $horasDecoracion = 0;
                    $horasDecoracionCantidad = 0;
                    $horasSubmuracion = 0;
                    $horasSubmuracionCantidad = 0;
                    $horasZanja = 0;
                    $horasZanjaCantidad = 0;
                    $horasHormigon = 0;
                    $horasHormigonCantidad = 0;
                    $feriadosnoremunerativo = 0;
                    $horas50cantidad = 0;
                    $horas100cantidad = 0;
                    $horas50remunerativa = 0;
                    $horas100remunerativa = 0;
                    $horas50noremunerativo = 0;
                    $horas100noremunerativo = 0;
                    $antiguedad = 0;
                    $acuerdoremunerativonobasico = 0;
                    $presentismo = 0;
                    $ajusteretroactivo = 0;
                    $sac = 0;
                    $sacIndemnizatorio = 0;
                    $fondoceselaboral = 0;
                    $segurodevidaobligatorio = 0;
                    $acuerdonoremunerativo = 0;
                    $antiguedadnoremunerativo = 0;
                    $presentismonoremunerativo = 0;
                    $vacacionesnoremunerativas = 0;
                    $sacnoremunerativo = 0;
                    $ley19032 = 0;
                    $otros = 0;
                    $obrasocial = 0;
                    $obrasocialnombre = "";
                    $obrasocialextraordinario = 0;
                    $obrasocialextraordinarionombre = "";
                    $cuotasindical = 0;
                    $cuotasindicalnombre = "";
                    $cuotasindical1 = 0;
                    $cuotasindical1nombre = "";
                    $cuotasindical2 = 0;
                    $cuotasindical2nombre = "";
                    $cuotasindical3 = 0;
                    $cuotasindical3nombre = "";
                    $cuotasindical4 = 0;
                    $cuotasindical4nombre = "";
                    $renatea= 0;
                    $totalremuneracion = 0;
                    $totalremuneracionsd= 0;
                    $totaldescuento = 0;
                    $neto = 0;
                    $anticipos = 0;
                    $redondeo = 0;
                    $embargo = 0;
                    $remuneracioncd = 0;
                    $adicionalcomplementarioss = 0;
                    $acuerdoremunerativo = 0;
                    $plusvacacional = 0;

                    $liquidacion = $empleado['Valorrecibo'][0]['tipoliquidacion'];

                    foreach ($empleado['Valorrecibo'] as $valorrecibo) {
                        //Dias Trabajados u Horas
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='12'/*Dias Trabajados*/){
                            $diastrabajados += $valorrecibo['valor'];
                        }
                        //Vacaciones Cantidad
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='13'/*Vacaciones*/){
                            $vacacionescantidad += $valorrecibo['valor'];
                        }
                        //Vacaciones No Gozadas
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='105'/*Vacaciones no gozadas*/){
                            $vacacionesnogozadas += $valorrecibo['valor'];
                        }
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='156'/*Vacaciones no gozadas Indemnizacion*/){
                            $vacacionesnogozadasIndemnizacion += $valorrecibo['valor'];
                        }
                        //Vacaciones No Gozadas Cantidad
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='60'/*Vacaciones No Gozadas Cantidad*/){
                            $vacacionesnogozadascantidad += $valorrecibo['valor'];
                        }
                        //Feriados Cantidad
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='5'/*Dias Feriados*/){
                            $feriadoscantidad += $valorrecibo['valor'];
                        }
                        //Feriados Pagos
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='19'/*Dias Feriados Pagos*/){
                            $feriadospagos += $valorrecibo['valor'];
                        }
                        //Feriados no trabajado Cantidad
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='15'/*Dias Feriados*/){
                            $feriadosnotrabajadoscantidad += $valorrecibo['valor'];
                        }
                        //Feriados no trabajado Pagos
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='151'/*Dias Feriados Pagos*/){
                            $feriadosnotrabajadospagos+= $valorrecibo['valor'];
                        }
                        //Feriados No Remunerativo
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='137'/*Dias Feriados Pagos*/){
                            $feriadosnoremunerativo += $valorrecibo['valor'];
                        }
                        // Horas al 50 Remunerativa
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='25'/*Dias Feriados Pagos*/){
                            $horas50remunerativa += $valorrecibo['valor'];
                        }
                        // Horas al 50 Cantidad
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='22'/*Dias Feriados Pagos*/){
                            $horas50cantidad += $valorrecibo['valor'];
                        }
                        // Horas al 100 Cantidad
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='23'/*Dias Feriados Pagos*/){
                            $horas100cantidad += $valorrecibo['valor'];
                        }
                        // Horas al 100 Remunerativa
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='26'/*Dias Feriados Pagos*/){
                            $horas100remunerativa += $valorrecibo['valor'];
                        }
                        // Horas al 50  NO Remunerativa
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='138'/*Horas extras al 50%*/){
                            $horas50noremunerativo += $valorrecibo['valor'];
                        }
                        // Horas al 100 NO Remunerativa
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='139'/*Horas extras al 100%*/){
                            $horas100noremunerativo += $valorrecibo['valor'];
                        }
                        //Basico
                        if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            [
                                '52'/*sueldo basico*/,
                                '10'/*jornal basico*/
                            ], true
                        )
                        ){
                            if($valorrecibo['valor']*1!=0)
                                $basico = $valorrecibo['valor']; // este no se deberia acumular
                        }
                        //Sueldo
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='6'/*Total basicos*/){
                            $sueldo += $valorrecibo['valor'];
                        }
                        //Horas Decoracion
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='144'/*Horas Decoracion*/){
                            $horasDecoracion += $valorrecibo['valor'];
                        }
                        //Horas Decoracion Cantidad
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='140'/*Horas Decoracion Cantidad*/){
                            $horasDecoracionCantidad += $valorrecibo['valor'];
                        }
                        //Horas Submuracion
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='145'/*Horas Submuracion*/){
                            $horasSubmuracion += $valorrecibo['valor'];
                        }
                        //Horas Submuracion Cantidad
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='141'/*Horas Submuracion Cantidad*/){
                            $horasSubmuracion += $valorrecibo['valor'];
                        }
                        //Horas Zanja
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='146'/*Horas Zanja */){
                            $horasZanja += $valorrecibo['valor'];
                        }
                        //Horas Zanja  Cantidad
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='142'/*Horas Zanja  Cantidad*/){
                            $horasZanjaCantidad += $valorrecibo['valor'];
                        }
                        //Horas Hormigon
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='147'/*Horas Hormigon */){
                            $horasHormigon += $valorrecibo['valor'];
                        }
                        //Horas Hormigon  Cantidad
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='143'/*Horas Hormigon  Cantidad*/){
                            $horasHormigonCantidad += $valorrecibo['valor'];
                        }
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='20'/*Vacaciones Remunerativas*/){
                            $vacacionesremunerativas += $valorrecibo['valor'];
                        }
                        //Inasistencias
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='68'/*Inasistencias*/){
                            $inasistencias += $valorrecibo['valor'];
                        }
                        //Inasistencias cantidad
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='56'/*Inasistencias cantidad*/){
                            $inasistenciascantidad += $valorrecibo['valor'];
                        }
                        //Inasistencia pagas
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='150'/*inasistencia*/){
                            $inasistenciapagas += $valorrecibo['valor'];
                        }
                        //Inasistencia pagas cantidad
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='55'/*inasistencia*/){
                            $inasistenciapagascantidad += $valorrecibo['valor'];
                        }
                        //Suspenciones
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='136'/*Suspenciones*/){
                            $suspenciones += $valorrecibo['valor'];
                        }
                        //Inasistencias cantidad
                        if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='135'/*Suspenciones cantidad*/){
                            $suspencionescantidad += $valorrecibo['valor'];
                        }
                        //Antiguedad
                        if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('18'/*Antiguedad*/), true )){
                            $antiguedad += $valorrecibo['valor'];
                        }
                        //Acuerdo Remunerativo No Al Basico
                        if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('129'/*Acuerdo Remunerativo No Al Basico*/), true )){
                            $acuerdoremunerativonobasico += $valorrecibo['valor'];
                        }
                        //Presentismo
                        if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('77'/*Presentismo*/), true )){
                            $presentismo += $valorrecibo['valor'];
                        }
                        //Ajuste Retroactivo
                        if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('159'/*ajuste retroactivo*/), true )){
                            $ajusteretroactivo += $valorrecibo['valor'];
                        }

                        //S.A.C. Remunerativo 1
                        if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('92'/*S.A.C. Remunerativo 1*/), true )){
                            $sac += $valorrecibo['valor'];
                        }
                        //S.A.C. Remunerativo 1
                        if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('157'/*SAC s/Conceptos Indemnizatorios*/), true )){
                            $sacIndemnizatorio += $valorrecibo['valor'];
                        }
                        //Fondo de Cese Laboral Efectivo
                        if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('158'/*Fondo de Cese Laboral Efectivo*/), true )){
                            $fondoceselaboral += $valorrecibo['valor'];
                        }
                        //Acuerdo 1
                        if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('95'/*Acuerdo 1*/), true )){
                            $acuerdonoremunerativo += $valorrecibo['valor'];
                        }
                        //Por Antigüedad
                        if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('104'/*Por Antigüedad*/), true )){
                            $antiguedadnoremunerativo += $valorrecibo['valor'];
                        }
                        //Vacaciones No remunerativas
                        if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('96'/*Vacaciones No remunerativas*/), true )){
                            $vacacionesnoremunerativas += $valorrecibo['valor'];
                        }
                        //Presentismo no remunerativo
                        if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('97'/*Presentismo no remunerativo*/), true )){
                            $presentismonoremunerativo += $valorrecibo['valor'];
                        }
                        //S.A.C. Proporcional no remunerativo
                        if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('102'/*S.A.C. Proporcional no remunerativo*/), true )){
                            $presentismonoremunerativo += $valorrecibo['valor'];
                        }

                         //Otros
                        if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array( '91'/*Otros*/), true )){
                            $otros += $valorrecibo['valor'];
                        }
                        //Aportes Seguridad Social Jubilacion Aporte SS
                        if (
                        in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('28'/*Jubilacion*/), true )
                        ){
                            $jubilacion += $valorrecibo['valor'];
                        }
                        //Seguridad Social Ley 19032
                        if (
                        in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('32'/*Ley 19032*/), true )
                        ){
                            $ley19032 += $valorrecibo['valor'];
                        }
                        //Obra Social
                        if (
                        in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('33'/*Obra Social*/), true )
                        ){
                            $obrasocial += $valorrecibo['valor'];
                            $obrasocialnombre = $valorrecibo['Cctxconcepto']['nombre'];
                        }
                        //Obra Social Extraordinario
                        if (
                        in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('35'/*Obra Social Extraordinario*/), true )
                        ){
                            $obrasocialextraordinario += $valorrecibo['valor'];
                            $obrasocialextraordinarionombre = $valorrecibo['Cctxconcepto']['nombre'];
                        }
                        //Cuota Sindical
                        if (
                        in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('36'/*Cuota Sindical*/), true )
                        ){
                            $cuotasindical += $valorrecibo['valor'];
                            $cuotasindicalnombre = $valorrecibo['Cctxconcepto']['nombre'];
                        }

                        //Cuota Sindical 1
                        if (
                        in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('37'/*Cuota Sindical 1*/), true )
                        ){
                            $cuotasindical1 += $valorrecibo['valor'];
                            $cuotasindical1nombre = $valorrecibo['Cctxconcepto']['nombre'];
                        }

                        //Cuota Sindical 2
                        if (
                        in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('38'/*Cuota Sindical 2*/), true )
                        ){
                            $cuotasindical2 += $valorrecibo['valor'];
                            $cuotasindical2nombre = $valorrecibo['Cctxconcepto']['nombre'];
                        }
                        //Cuota Sindical 3
                        if (
                        in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('114'/*Cuota Sindical 3*/), true )
                        ){
                            $cuotasindical3 += $valorrecibo['valor'];
                            $cuotasindical3nombre = $valorrecibo['Cctxconcepto']['nombre'];
                        }
                        //Cuota Sindical 4
                        if (
                        in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('134'/*Cuota Sindical 4*/), true )
                        ){
                            $cuotasindical4 += $valorrecibo['valor'];
                            $cuotasindical4nombre = $valorrecibo['Cctxconcepto']['nombre'];
                        }
                        //RENATEA
                        if (
                        in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('121'/*RENATEA Aporte*/), true )
                        ){
                            $renatea += $valorrecibo['valor'];
                        }

                        //Total Remunerativos
                        if (
                        in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('41'/*Total Remunerativos*/), true )
                        ){
                            $totalremuneracion += $valorrecibo['valor'];
                        }
                        //Total Remunerativos S/D
                        if (
                        in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('42'/*Total Remunerativos S/D*/), true )
                        ){
                            $totalremuneracionsd += $valorrecibo['valor'];
                        }
                        //Total Aportes
                        if (
                        in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('45'/*Total Aportes*/), true )
                        ){
                            $totaldescuento += $valorrecibo['valor'];
                        }
                        //Neto
                        if (
                        in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('46'/*Neto*/), true )
                        ){
                            $neto += $valorrecibo['valor'];
                        }
                        //Anticipos
                        if (
                        in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('132'/*Anticipos*/), true )
                        ){
                            $anticipos += $valorrecibo['valor'];
                        }
                        //Redondeo
                        if (
                        in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('124'/*Redondeo*/), true )
                        ){
                            $redondeo += $valorrecibo['valor'];
                        }
                        //Embargo
                        if (
                        in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('131'/*Embargo*/), true )
                        ){
                            $embargo += $valorrecibo['valor'];
                        }
                        //Remuneracion C/D
                        if (
                        in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('41'/*Remuneracion C/D*/), true )
                        ){
                            $remuneracioncd += $valorrecibo['valor'];
                        }
                        //Adicional Complemento SS
                        if (
                        in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('82'/*Adicional Complemento SS*/), true )
                        ){
                            $adicionalcomplementarioss += $valorrecibo['valor'];
                        }
                        //Acuerdo Remunerativo
                        if (
                        in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('127'/*Total Acuerdo Remunerativo*/), true )
                        ){
                            $acuerdoremunerativo += $valorrecibo['valor'];
                        }
                        //Plus Vacacional
                        if (
                        in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                            array('81'/*Plus Vacacional*/), true )
                        ){
                            $plusvacacional += $valorrecibo['valor'];
                        }

                    }

                    $miempleado['sueldo']=$sueldo;
                    $miempleado['inasistencias']=$inasistencias;
                    $miempleado['inasistenciascantidad']=$inasistenciascantidad;
                    $miempleado['inasistenciapagas']=$inasistenciapagas;
                    $miempleado['inasistenciapagascantidad']=$inasistenciapagascantidad;
                    $miempleado['suspenciones']=$suspenciones;
                    $miempleado['suspencionescantidad']=$suspencionescantidad;
                    $miempleado['vacacionescantidad']=$vacacionescantidad;
                    $miempleado['vacacionesnogozadas']=$vacacionesnogozadas;
                    $miempleado['vacacionesnogozadasIndemnizacion']=$vacacionesnogozadasIndemnizacion;
                    $miempleado['vacacionesnogozadascantidad']=$vacacionesnogozadascantidad;
                    $miempleado['feriadoscantidad']=$feriadoscantidad;
                    $miempleado['feriadospagos']=$feriadospagos;
                    $miempleado['feriadosnotrabajadoscantidad']=$feriadosnotrabajadoscantidad;
                    $miempleado['feriadosnotrabajadospagos']=$feriadosnotrabajadospagos;
                    $miempleado['vacacionesremunerativas']=$vacacionesremunerativas;
                    $miempleado['basico']=$basico;

                    $miempleado['horasDecoracion'] = $horasDecoracion;
                    $miempleado['horasDecoracionCantidad'] = $horasDecoracionCantidad;
                    $miempleado['horasSubmuracion'] = $horasSubmuracion;
                    $miempleado['horasSubmuracionCantidad'] = $horasSubmuracionCantidad;
                    $miempleado['horasZanja'] = $horasZanja;
                    $miempleado['horasZanjaCantidad'] = $horasZanjaCantidad;
                    $miempleado['horasHormigon'] = $horasHormigon;
                    $miempleado['horasHormigonCantidad'] = $horasHormigonCantidad;

                    $miempleado['adicionales']=$adicionales;
                    $miempleado['antiguedad']=$antiguedad;
                    $miempleado['acuerdoremunerativonobasico']=$acuerdoremunerativonobasico;
                    $miempleado['presentismo']=$presentismo;
                    $miempleado['ajusteretroactivo']=$ajusteretroactivo;
                    $miempleado['feriadosnoremunerativo'] = $feriadosnoremunerativo;
                    $miempleado['horas50remunerativa'] = $horas50remunerativa;
                    $miempleado['horas100remunerativa'] = $horas100remunerativa;
                    $miempleado['horas50noremunerativo'] = $horas50noremunerativo;
                    $miempleado['horas100noremunerativo'] = $horas100noremunerativo;
                    $miempleado['horas50cantidad'] = $horas50cantidad;
                    $miempleado['horas100cantidad'] = $horas100cantidad;
                    $miempleado['sac'] = $sac;
                    $miempleado['sacIndemnizatorio'] = $sacIndemnizatorio;
                    $miempleado['fondoceselaboral'] = $fondoceselaboral;
                    $miempleado['acuerdonoremunerativo'] = $acuerdonoremunerativo;
                    $miempleado['antiguedadnoremunerativo'] = $antiguedadnoremunerativo;
                    $miempleado['presentismonoremunerativo'] = $presentismonoremunerativo;
                    $miempleado['vacacionesnoremunerativas'] = $vacacionesnoremunerativas;
                    $miempleado['sacnoremunerativo'] = $sacnoremunerativo;
                    $miempleado['jubilacion']=$jubilacion;
                    $miempleado['ley19032']=$ley19032;
                    $miempleado['obrasocial']=$obrasocial;
                    $miempleado['obrasocialnombre']=$obrasocialnombre;
                    $miempleado['obrasocialextraordinario']=$obrasocialextraordinario;
                    $miempleado['obrasocialextraordinarionombre']=$obrasocialextraordinarionombre;
                    $miempleado['cuotasindical']=$cuotasindical;
                    $miempleado['cuotasindicalnombre']=$cuotasindicalnombre;
                    $miempleado['cuotasindical1']=$cuotasindical1;
                    $miempleado['cuotasindical1nombre']=$cuotasindical1nombre;
                    $miempleado['cuotasindical2']=$cuotasindical2;
                    $miempleado['cuotasindical2nombre']=$cuotasindical2nombre;
                    $miempleado['cuotasindical3']=$cuotasindical3;
                    $miempleado['cuotasindical3nombre']=$cuotasindical3nombre;
                    $miempleado['cuotasindical4']=$cuotasindical4;
                    $miempleado['cuotasindical4nombre']=$cuotasindical4nombre;
                    $miempleado['renatea']=$renatea;
                    $miempleado['totalremuneracion']=$totalremuneracion;
                    $miempleado['totalremuneracionsd']=$totalremuneracionsd;
                    $miempleado['totaldescuento']=$totaldescuento;
                    $miempleado['neto']=$neto;
                    $miempleado['anticipos']=$anticipos;
                    $miempleado['embargo']=$embargo;
                    $miempleado['redondeo']=$redondeo;
                    $miempleado['remuneracioncd']=$remuneracioncd;
                    $miempleado['diastrabajados']=$diastrabajados;
                    $miempleado['adicionalcomplementarioss']=$adicionalcomplementarioss;
                    $miempleado['acuerdoremunerativo']=$acuerdoremunerativo;
                    $miempleado['plusvacacional']=$plusvacacional;
                    ?>
                    <table id="tblReciboSueldo" cellspacing="0" class="tblInforme" style="padding:0px">
                        <tr>
                            <td colspan="20" class="tdWithBorder" style="border-top: 0px;border-left: 0px;border-right: 0px;">
                                <b>Recibo de remuneraciones - Periodo: <?php
                                    $timeperiodo =strtotime('01-'.$pemes.'-'.$peanio);
                                    $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
                                    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
//                                    setlocale(LC_ALL,"es_ES");
//                                    $timestr =
//                                        strftime('%B del %Y',strtotime('01-'.$pemes.'-'.$peanio));
                                    $timestr =
                                        $meses[date('n',$timeperiodo)]. " del ".date('Y',$timeperiodo) ;
                                    echo $timestr ;

                                    ?>
                                <?php
                                $diasTop=4;
                                switch ($liquidacion){
                                    case '1':
                                        echo "Primera Quincena";
                                        $diasTop= 16;
                                        break;
                                    case '2':
                                        echo "Segunda Quincena";
                                        break;
                                    case '7':
                                        echo "SAC";
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
                                <b>O.S.: </b> <?php echo $obrasocialnombre; ?>
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
                        if($miempleado['sueldo']*1>0) { ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    1
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    Sueldo mensual
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo $miempleado['diastrabajados']; ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['sueldo'], 2, ",", "."); ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                            <?php
                        }
                        if($miempleado['horasDecoracion']*1>0) { ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    2
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    Horas Decoracion
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo $miempleado['horasDecoracionCantidad']; ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['horasDecoracion'], 2, ",", "."); ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                            <?php
                        }
                        if($miempleado['horasSubmuracion']*1>0) { ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    3
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    Horas Submuracion
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo $miempleado['horasSubmuracionCantidad']; ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['horasSubmuracion'], 2, ",", "."); ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                            <?php
                        }
                        if($miempleado['horasZanja']*1>0) { ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    4
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    Horas Zanja
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo $miempleado['horasZanjaCantidad']; ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['horasZanja'], 2, ",", "."); ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                            <?php
                        }
                        if($miempleado['horasHormigon']*1>0) { ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    5
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    Horas Hormigon
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo $miempleado['horasHormigonCantidad']; ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['horasHormigon'], 2, ",", "."); ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                            <?php
                        }
                        if($miempleado['inasistenciapagas']*1>0) { ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    5
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    Inasistencias pagas
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo $miempleado['inasistenciapagascantidad']; ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['inasistenciapagas'], 2, ",", "."); ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                            <?php
                        }
                        if($miempleado['inasistencias']*1>0){ ?>
                        <tr class="trConceptoRecibo">
                            <td class="tdWithLeftRightBorder">
                                7
                            </td>
                            <td class="tdWithLeftRightBorder">
                                Inasistencias
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                <?php echo $miempleado['inasistenciascantidad']; ?>
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                -<?php echo number_format($miempleado['inasistencias'], 2, ",", "."); ?>
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                        </tr>
                        <?php }
                        if($miempleado['suspenciones']*1>0){ ?>
                        <tr class="trConceptoRecibo">
                            <td class="tdWithLeftRightBorder">
                                8
                            </td>
                            <td class="tdWithLeftRightBorder">
                                Suspensiones
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                <?php echo $miempleado['suspencionescantidad']; ?>
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                -<?php echo number_format($miempleado['suspenciones'], 2, ",", "."); ?>
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                        </tr>
                        <?php }
                        if($miempleado['feriadospagos']*1>0){ ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    21
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    Feriados Pagos
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo $miempleado['feriadoscantidad']; ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['feriadospagos'], 2, ",", "."); ?>

                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                        <?php }
                        if($miempleado['feriadosnotrabajadospagos']*1>0){ ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    22
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    Feriados no trabajados Pagos
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo $miempleado['feriadosnotrabajadoscantidad']; ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['feriadosnotrabajadospagos'], 2, ",", "."); ?>

                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                        <?php }
                        if($miempleado['vacacionesremunerativas']*1>0){ ?>
                        <tr class="trConceptoRecibo">
                            <td class="tdWithLeftRightBorder">
                                51
                            </td>
                            <td class="tdWithLeftRightBorder">
                                Vacaciones Remunerativas
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                <?php echo $miempleado['vacacionescantidad']; ?>
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                <?php echo number_format($miempleado['vacacionesremunerativas'], 2, ",", "."); ?>
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                        </tr>
                        <?php }
                        if($miempleado['antiguedad']*1>0){ ?>
                        <tr class="trConceptoRecibo">
                            <td class="tdWithLeftRightBorder">
                                71
                            </td>
                            <td class="tdWithLeftRightBorder">
                                Antigüedad
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                1
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                <?php echo number_format($miempleado['antiguedad'], 2, ",", "."); ?>
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                        </tr>
                        <?php }
                        if($miempleado['presentismo']*1>0){ ?>
                        <tr class="trConceptoRecibo">
                            <td class="tdWithLeftRightBorder">
                                91
                            </td>
                            <td class="tdWithLeftRightBorder">
                                Presentismo Basico
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                1
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                <?php echo number_format($miempleado['presentismo'], 2, ",", "."); ?>
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                        </tr>
                        <?php }
                        if($miempleado['ajusteretroactivo']*1>0){ ?>
                        <tr class="trConceptoRecibo">
                            <td class="tdWithLeftRightBorder">
                                92
                            </td>
                            <td class="tdWithLeftRightBorder">
                                Ajuste retroactivo
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                1
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                <?php echo number_format($miempleado['ajusteretroactivo'], 2, ",", "."); ?>
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                        </tr>
                        <?php }
                        if($miempleado['horas50remunerativa']*1>0){ ?>
                        <tr class="trConceptoRecibo">
                            <td class="tdWithLeftRightBorder">
                                101
                            </td>
                            <td class="tdWithLeftRightBorder">
                                Horas al 50
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                <?php echo $miempleado['horas50cantidad']; ?>
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                <?php echo number_format($miempleado['horas50remunerativa'], 2, ",", "."); ?>
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                        </tr>
                        <?php }
                        if($miempleado['horas100remunerativa']*1>0){ ?>
                        <tr class="trConceptoRecibo">
                            <td class="tdWithLeftRightBorder">
                                102
                            </td>
                            <td class="tdWithLeftRightBorder">
                                Horas al 100
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                <?php echo $miempleado['horas100cantidad']; ?>
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                <?php echo number_format($miempleado['horas100remunerativa'], 2, ",", "."); ?>
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                        </tr>
                        <?php }
                        if($miempleado['adicionalcomplementarioss']*1>0){ ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    122
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    Adicional Complemento SS
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    1
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['adicionalcomplementarioss'], 2, ",", "."); ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                        <?php }
                        if($miempleado['acuerdoremunerativo']*1>0){ ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    146
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    Acuerdo Remunerativo
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    1
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['acuerdoremunerativo'], 2, ",", "."); ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                        <?php }
                        if($miempleado['acuerdoremunerativonobasico']*1>0){ ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    146
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    Acuerdo Remunerativo
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    1
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['acuerdoremunerativonobasico'], 2, ",", "."); ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                        <?php }
                        if($miempleado['plusvacacional']*1>0){ ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    000
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    Plus Vacacional
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    1
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['plusvacacional'], 2, ",", "."); ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                        <?php }
                        if($miempleado['sac']*1>0){ ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    281
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    S.A.C.
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    184<?php /*agregar cantidad SAC*/ ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['sac'], 2, ",", "."); ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                        <?php }
                        if($miempleado['acuerdonoremunerativo']*1>0){ ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    301
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    Acuerdo No Remunerativo
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php
                                    $cantAcNoRem=
                                        $miempleado['diastrabajados']+ $miempleado['inasistenciapagascantidad'] -$miempleado['suspencionescantidad']-$miempleado['inasistenciascantidad'];
                                     echo $cantAcNoRem;  ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php
                                    echo number_format($miempleado['acuerdonoremunerativo'], 2, ",", "."); ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                        <?php }

                        if($miempleado['vacacionesnoremunerativas']*1>0){ ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    321
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    Vacaciones No Remunerativas
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo $miempleado['vacacionescantidad']; ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['vacacionesnoremunerativas'], 2, ",", "."); ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                        <?php }
                        if($miempleado['antiguedadnoremunerativo']*1>0){ ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    331
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    Antig. No Remunerativa
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php
                                    $cantAcNoRem = $miempleado['diastrabajados']+ $miempleado['inasistenciapagascantidad'] -$miempleado['suspencionescantidad']-$miempleado['inasistenciascantidad'];
                                    echo $cantAcNoRem; ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['antiguedadnoremunerativo'], 2, ",", "."); ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                        <?php }
                        if($miempleado['presentismonoremunerativo']*1>0){ ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    351
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    Presentismo No Remunerativo
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    1
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['presentismonoremunerativo'], 2, ",", "."); ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                        <?php }
                        if($miempleado['feriadosnoremunerativo']*1>0){ ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    352
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    Feriados No Remunerativo
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo $miempleado['feriadoscantidad']; ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['feriadosnoremunerativo'], 2, ",", "."); ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                        <?php }
                        if($miempleado['horas50noremunerativo']*1>0){ ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    353
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    Horas al 50
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo $miempleado['horas50cantidad']; ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['horas50cantidad'], 2, ",", "."); ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                        <?php }
                        if($miempleado['horas100noremunerativo']*1>0){ ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    354
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    Horas al 100
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo $miempleado['horas100cantidad']; ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['horas100noremunerativo'], 2, ",", "."); ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                        <?php }
                        if($miempleado['sacnoremunerativo']*1>0){ ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    481
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    SAC No Remunerativo
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    184
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['sacnoremunerativo'], 2, ",", "."); ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                        <?php }
                        if($miempleado['vacacionesnogozadas']*1>0){ ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    511
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    Vacaciones no gozadas
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo $miempleado['vacacionesnogozadascantidad']; ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['vacacionesnogozadas'], 2, ",", "."); ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                        <?php }
                        if($miempleado['sacIndemnizatorio']*1>0){ ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    542
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    SAC s/Conceptos Indemnizatorios
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    180
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['sacIndemnizatorio'], 2, ",", "."); ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                        <?php }
                        if($miempleado['fondoceselaboral']*1>0){ ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    543
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    Fondo de Cese Laboral Efectivo
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    1
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['fondoceselaboral'], 2, ",", "."); ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                            </tr>
                        <?php }

                        if($miempleado['jubilacion']*1>0){ ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    701
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    Jubilacion
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    1
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['jubilacion'], 2, ",", "."); ?>
                                </td>
                            </tr>
                        <?php }
                        if($miempleado['ley19032']*1>0){ ?>
                        <tr class="trConceptoRecibo">
                            <td class="tdWithLeftRightBorder">
                                706
                            </td>
                            <td class="tdWithLeftRightBorder">
                                Ley 19032-INSSJP
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                1
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                <?php echo number_format($miempleado['ley19032'], 2, ",", "."); ?>
                            </td>
                        </tr>
                        <?php }
                        if($miempleado['obrasocial']*1>0){ ?>
                        <tr class="trConceptoRecibo">
                            <td class="tdWithLeftRightBorder">
                                721
                            </td>
                            <td class="tdWithLeftRightBorder">
                                <?php echo $miempleado['obrasocialnombre']; ?>
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                1
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                <?php echo number_format($miempleado['obrasocial'], 2, ",", "."); ?>
                            </td>
                        </tr>
                        <?php }
                        if($miempleado['obrasocialextraordinario']*1>0){ ?>
                        <tr class="trConceptoRecibo">
                            <td class="tdWithLeftRightBorder">
                                722
                            </td>
                            <td class="tdWithLeftRightBorder">
                                <?php echo $miempleado['obrasocialextraordinarionombre']; ?>
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                1
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                <?php echo number_format($miempleado['obrasocialextraordinario'], 2, ",", "."); ?>
                            </td>
                        </tr>
                        <?php }
                        if($miempleado['cuotasindical']*1>0){ ?>
                        <tr class="trConceptoRecibo">
                            <td class="tdWithLeftRightBorder">
                                821
                            </td>
                            <td class="tdWithLeftRightBorder">
                                <?php echo $miempleado['cuotasindicalnombre']; ?>
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                1
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                <?php echo number_format($miempleado['cuotasindical'], 2, ",", "."); ?>
                            </td>
                        </tr>
                        <?php }
                        if($miempleado['cuotasindical1']*1>0){ ?>
                        <tr class="trConceptoRecibo">
                            <td class="tdWithLeftRightBorder">
                                822
                            </td>
                            <td class="tdWithLeftRightBorder">
                                <?php echo $miempleado['cuotasindical1nombre']; ?>
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                1
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                <?php echo number_format($miempleado['cuotasindical1'], 2, ",", "."); ?>
                            </td>
                        </tr>
                        <?php }
                        if($miempleado['cuotasindical2']*1>0){ ?>
                        <tr class="trConceptoRecibo">
                            <td class="tdWithLeftRightBorder">
                                823
                            </td>
                            <td class="tdWithLeftRightBorder">
                                <?php echo $miempleado['cuotasindical2nombre']; ?>
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                1
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                <?php echo number_format($miempleado['cuotasindical2'], 2, ",", "."); ?>
                            </td>
                        </tr>
                        <?php }
                        if($miempleado['cuotasindical4']*1>0){ ?>
                            <tr class="trConceptoRecibo">
                                <td class="tdWithLeftRightBorder">
                                    824
                                </td>
                                <td class="tdWithLeftRightBorder">
                                    <?php echo $miempleado['cuotasindical4nombre']; ?>
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    1
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                </td>
                                <td class="tdWithLeftRightBorder tdWithNumber">
                                    <?php echo number_format($miempleado['cuotasindical4'], 2, ",", "."); ?>
                                </td>
                            </tr>
                        <?php }
                        if($miempleado['cuotasindical3']*1>0){ ?>
                        <tr class="trConceptoRecibo">
                            <td class="tdWithLeftRightBorder">
                            825
                            </td>
                            <td class="tdWithLeftRightBorder">
                            <?php echo $miempleado['cuotasindical3nombre']; ?>
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            1
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            <?php echo number_format($miempleado['cuotasindical3'], 2, ",", "."); ?>
                            </td>
                        </tr>
                        <?php }
                        if($miempleado['renatea']*1>0){ ?>
                        <tr class="trConceptoRecibo">
                            <td class="tdWithLeftRightBorder">
                                863
                            </td>
                            <td class="tdWithLeftRightBorder">
                                RENATEA Aporte
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                1
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                <?php echo number_format($miempleado['renatea']*1, 2, ",", "."); ?>
                            </td>
                        </tr>
                        <?php }
                        if($miempleado['embargo']*1>0){ ?>
                        <tr class="trConceptoRecibo">
                            <td class="tdWithLeftRightBorder">
                                902
                            </td>
                            <td class="tdWithLeftRightBorder">
                                Embargo Judicial
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                1
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                <?php echo number_format($miempleado['embargo']*1, 2, ",", "."); ?>
                            </td>
                        </tr>
                        <?php }
                        if($miempleado['anticipos']*1>0){ ?>
                        <tr class="trConceptoRecibo">
                            <td class="tdWithLeftRightBorder">
                                911
                            </td>
                            <td class="tdWithLeftRightBorder">
                                Anticipos
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                1
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                <?php echo number_format($miempleado['anticipos']*1, 2, ",", "."); ?>
                            </td>
                        </tr>
                        <?php }
                        if($miempleado['redondeo']*1>0){ ?>
                        <tr class="trConceptoRecibo">
                            <td class="tdWithLeftRightBorder">
                                980
                            </td>
                            <td class="tdWithLeftRightBorder">
                                Redondeo
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                1
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                                <?php echo number_format($miempleado['redondeo']*1, 2, ",", "."); ?>
                            </td>
                            <td class="tdWithLeftRightBorder tdWithNumber">
                            </td>
                        </tr>
                        <?php } ?>
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
                                echo number_format($miempleado['totalremuneracion'], 2, ",", ".");?>
                            </td>
                            <?php
                            $totalRemSD = $miempleado['redondeo']*1 + $miempleado['totalremuneracionsd']*1
                                        ;
                            ?>
                            <td class="tdWithBorder tdWithNumber" style="text-align: right;"><?php echo number_format($totalRemSD, 2, ",", ".");?></td>
                            <td class="tdWithBorder tdWithNumber" style="text-align: right;">
                                <?php
                                echo number_format(
                                    $miempleado['totaldescuento']*1+
                                    $miempleado['embargo']*1+$miempleado['anticipos']*1 , 2, ",", ".");?></td>
                        </tr>
                        <tr>
                            <td colspan="3" rowspan="2" class="tdWithBorder" style="vertical-align: text-top;text-align:
                            left;border-bottom: 0px;border-left: 0px;">
                                Recibi conforme la suma de : $<?php echo number_format($miempleado['neto'], 2, ",", ".");?>
                                Son pesos: <?php echo num2letras(number_format($miempleado['neto'], 2, ".", "")); ?>
                                En concepto de remuneraciones correspondintes al periodo arriba indicado dejando constancia
                                de haber recibido copia fiel de este recibo.
                            </td>
                            <td colspan="3" class="tdWithBorder" style="text-align: -webkit-center;vertical-align: text-top;">
                                <b>Neto: </b><?php echo number_format($miempleado['neto'], 2, ",", ".");?>
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