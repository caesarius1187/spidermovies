<?php
    echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
    echo $this->Form->input('empidPDT',array('value'=>$empid,'type'=>'hidden'));
    if(count($empleado['Valorrecibo'])==0){
        //este empleado no tiene liquidacion
        return "";
    }
 echo $this->Form->button('Imprimir',
    array('type' => 'button',
        'class' =>"btn_imprimir",
        'onClick' => "openWinLibroSueldo()"
    )
);
?>
<div id="divLibroSueldo">
    <div id="libroSueldoContent<?php echo $empid; ?>" name="" class="index" style="margin: 10px 0px; /*page-break-before:always*/">
        <?php
        $liquidacion = $empleado['Valorrecibo'][0]['tipoliquidacion'];
        $empleadoDatos = array();
        $miempleado = array();
        if(!isset($miempleado['horasDias'])) {
            $miempleado['basico'] = 0;
            $miempleado['sueldo'] = 0;
            $miempleado['inasistencia'] = 0;
            $miempleado['inasistenciapagas'] = 0;
            $miempleado['inasistenciapagascantidad'] = 0;
            $miempleado['suspensiones'] = 0;
            $miempleado['vacacionesremunerativas'] = 0;
            $miempleado['vacacionescantidad'] = 0;
            $miempleado['vacacionesnogozadas'] = 0;
            $miempleado['vacacionesnogozadascantidad'] = 0;
            $miempleado['feriadoscantidad'] = 0;
            $miempleado['feriadospagos'] = 0;
            $miempleado['feriadosnotrabajadoscantidad'] = 0;
            $miempleado['feriadosnotrabajadospagos'] = 0;
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
            $miempleado['feriadosnoremunerativo'] = 0;
            $miempleado['adicionales'] = 0;
            $miempleado['antiguedad'] = 0;
            $miempleado['acuerdoremunerativonobasico'] = 0;
            $miempleado['presentismo'] = 0;
            $miempleado['ajusteretroactivo'] = 0;
            $miempleado['sac'] = 0;
            $miempleado['fondoceselaboral'] = 0;
            $miempleado['sacIndemnizatorio'] = 0;
            $miempleado['acuerdonoremunerativo'] = 0;
            $miempleado['antiguedadnoremunerativo'] = 0;
            $miempleado['presentismonoremunerativo'] = 0;
            $miempleado['vacacionesnoremunerativas'] = 0;
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
            $miempleado['totaldescuento'] = 0;

            $miempleado['anticipos'] = 0;

            $miempleado['neto'] = 0;
            $miempleado['embargo'] = 0;
            $miempleado['redondeo'] = 0;
            $miempleado['remuneracioncd'] = 0;
            $miempleado['adicionalcomplementarioss'] = 0;
            $miempleado['acuerdoremunerativo'] = 0;
            $miempleado['plusvacacional'] = 0;
        }
        $jubilacion=0;
        $basico=0;
        $adicionales=0;
        $sueldo=0;
        $inasistencia=0;
        $suspensiones=0;
        $vacacionesremunerativas=0;
        $vacacionescantidad=0;
        $inasistenciapagas=0;
        $inasistenciapagascantidad=0;
        $vacacionesnogozadascantidad=0;
        $vacacionesnogozadas=0;
        $feriadoscantidad=0;
        $feriadospagos=0;
        $feriadosnotrabajadoscantidad=0;
        $feriadosnotrabajadospagos=0;
        $feriadosnoremunerativo = 0;
        $horas50cantidad = 0;
        $horas100cantidad = 0;
        $horas50remunerativa = 0;
        $horas100remunerativa = 0;
        $horas50noremunerativo = 0;
        $horas100noremunerativo = 0;
        $horasDecoracion = 0;
        $horasDecoracionCantidad = 0;
        $horasSubmuracion = 0;
        $horasSubmuracionCantidad = 0;
        $horasZanja = 0;
        $horasZanjaCantidad = 0;
        $horasHormigon = 0;
        $horasHormigonCantidad = 0;
        $antiguedad = 0;
        $acuerdoremunerativonobasico = 0;
        $presentismo = 0;
        $ajusteretroactivo = 0;
        $sac = 0;
        $sacIndemnizatorio = 0;
        $fondoceselaboral = 0;
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
        $renatea = 0;
        $totalremuneracion = 0;
        $totaldescuento = 0;
        $neto = 0;
        $anticipos = 0;
        $redondeo = 0;
        $embargo = 0;
        $remuneracioncd = 0;
        $adicionalcomplementarioss = 0;
        $acuerdoremunerativo = 0;
        $plusvacacional = 0;

        foreach ($empleado['Valorrecibo'] as $valorrecibo) {

            //Basico
            if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='52'/*Total basicos*/){
                $basico = $valorrecibo['valor']; // este no se deberia acumular
            }
            //Sueldo
            if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='6'/*sueldo basico*/){
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
            //Vacaciones Remunerativas
            if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='20'/*Vacaciones Remunerativas*/){
                $vacacionesremunerativas += $valorrecibo['valor'];
            }
            //Vacaciones No Gozadas
            if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='105'/*Vacaciones no gozadas*/){
                $vacacionesnogozadas += $valorrecibo['valor'];
            }
            //Inasistencia
            if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='68'/*inasistencia*/){
                $inasistencia += $valorrecibo['valor'];
            }
            //Inasistencia pagas
            if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='150'/*inasistencia*/){
                $inasistenciapagas += $valorrecibo['valor'];
            }
            //Inasistencia pagas cantidad
            if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='55'/*inasistencia*/){
                $inasistenciapagascantidad += $valorrecibo['valor'];
            }
            //Suspensiones
            if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='136'/*Suspensiones*/){
                $suspensiones += $valorrecibo['valor'];
            }
            //Vacaciones Cantidad
            if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='13'/*Vacaciones*/){
                $vacacionescantidad += $valorrecibo['valor'];
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
            //Antiguedad
            if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                array('18'/*Antiguedad*/), true )){
                $antiguedad += $valorrecibo['valor'];
            }
            //acuerdoremunerativonobasico
            if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                array('129'/*acuerdoremunerativonobasico*/), true )){
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
            //Presentismo no remunerativo
            if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                array('97'/*Presentismo no remunerativo*/), true )){
                $presentismonoremunerativo += $valorrecibo['valor'];
            }
            //Vacaciones no remunerativas
            if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                array('96'/*vacacionesnoremunerativas*/), true )){
                $vacacionesnoremunerativas += $valorrecibo['valor'];
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
            //RENATEA Aporte
            if (
            in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                array('121'/*RENATEA Aporte*/), true )
            ){
                $renatea += $valorrecibo['valor'];
            }
            //Total Remunerativos
            if (
            in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                array('44'/*Total Remunerativos*/), true )
            ){
                $totalremuneracion += $valorrecibo['valor'];
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
            //anticipos
            if (
            in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                array('132'/*anticipos*/), true )
            ){
                $anticipos += $valorrecibo['valor'];
            }
            //embargo
            if (
            in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                array('131'/*embargo*/), true )
            ){
                $embargo += $valorrecibo['valor'];
            }
            //Redondeo
            if (
            in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                array('124'/*Redondeo*/), true )
            ){
                $redondeo += $valorrecibo['valor'];
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
        $miempleado['inasistencia']=$inasistencia;
        $miempleado['inasistenciapagas']=$inasistenciapagas;
        $miempleado['inasistenciapagascantidad']=$inasistenciapagascantidad;
        $miempleado['suspensiones']=$suspensiones;
        $miempleado['vacacionesremunerativas']=$vacacionesremunerativas;
        $miempleado['vacacionescantidad']=$vacacionescantidad;
        $miempleado['vacacionesnogozadas']=$vacacionesnogozadas;
        $miempleado['vacacionesnogozadascantidad']=$vacacionesnogozadascantidad;
        $miempleado['feriadoscantidad']=$feriadoscantidad;
        $miempleado['feriadospagos']=$feriadospagos;
        $miempleado['feriadosnotrabajadoscantidad']=$feriadosnotrabajadoscantidad;
        $miempleado['feriadosnotrabajadospagos']=$feriadosnotrabajadospagos;
        $miempleado['horasDecoracion'] = $horasDecoracion;
        $miempleado['horasDecoracionCantidad'] = $horasDecoracionCantidad;
        $miempleado['horasSubmuracion'] = $horasSubmuracion;
        $miempleado['horasSubmuracionCantidad'] = $horasSubmuracionCantidad;
        $miempleado['horasZanja'] = $horasZanja;
        $miempleado['horasZanjaCantidad'] = $horasZanjaCantidad;
        $miempleado['horasHormigon'] = $horasHormigon;
        $miempleado['horasHormigonCantidad'] = $horasHormigonCantidad;

        $miempleado['feriadosnoremunerativo'] = $feriadosnoremunerativo;
        $miempleado['horas50remunerativa'] = $horas50remunerativa;
        $miempleado['horas100remunerativa'] = $horas100remunerativa;
        $miempleado['horas50noremunerativo'] = $horas50noremunerativo;
        $miempleado['horas100noremunerativo'] = $horas100noremunerativo;
        $miempleado['horas50cantidad'] = $horas50cantidad;
        $miempleado['horas100cantidad'] = $horas100cantidad;
        $miempleado['basico']=$basico;
        $miempleado['adicionales']=$adicionales;
        $miempleado['antiguedad']=$antiguedad;
        $miempleado['acuerdoremunerativonobasico']=$acuerdoremunerativonobasico;
        $miempleado['presentismo']=$presentismo;
        $miempleado['ajusteretroactivo']=$ajusteretroactivo;
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
        $miempleado['totaldescuento']=$totaldescuento;
        $miempleado['neto']=$neto;
        $miempleado['anticipos']=$anticipos;
        $miempleado['redondeo']=$redondeo;
        $miempleado['embargo']=$embargo;
        $miempleado['remuneracioncd']=$remuneracioncd;
        $miempleado['adicionalcomplementarioss']=$adicionalcomplementarioss;
        $miempleado['acuerdoremunerativo']=$acuerdoremunerativo;
        $miempleado['plusvacacional']=$plusvacacional;
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
                Basico: <?php echo number_format($miempleado['basico'], 2, ",", "."); ?>
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
        //aca vamos a mostrar las rows solo si sus valoresrecibos son > 0 sino no tiene sentido
        //SUELDO MENSUAL
        if($miempleado['sueldo']*1>0) {
            ?>
            <tr>
            <td>1</td>
            <td>
                Sueldo mensual
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['sueldo'], 2, ",", "."); ?>
            </td>
            <td></td>
            <td></td>
            </tr>
            <?php
        }
        if($miempleado['horasDecoracion']*1>0) {
            ?>
            <tr>
                <td>2</td>
                <td>
                    Horas Decoracion
                </td>
                <td class="tdWithNumber">
                    <?php echo number_format($miempleado['horasDecoracion'], 2, ",", "."); ?>
                </td>
                <td></td>
                <td></td>
            </tr>
            <?php
        }
        if($miempleado['horasSubmuracion']*1>0) {
            ?>
            <tr>
                <td>3</td>
                <td>
                    Horas Submuracion
                </td>
                <td class="tdWithNumber">
                    <?php echo number_format($miempleado['horasSubmuracion'], 2, ",", "."); ?>
                </td>
                <td></td>
                <td></td>
            </tr>
            <?php
        }
        if($miempleado['horasZanja']*1>0) {
            ?>
            <tr>
                <td>4</td>
                <td>
                    Horas Zanja
                </td>
                <td class="tdWithNumber">
                    <?php echo number_format($miempleado['horasZanja'], 2, ",", "."); ?>
                </td>
                <td></td>
                <td></td>
            </tr>
            <?php
        }
        if($miempleado['horasHormigon']*1>0) {
            ?>
            <tr>
                <td>5</td>
                <td>
                    Horas Hormigon
                </td>
                <td class="tdWithNumber">
                    <?php echo number_format($miempleado['horasHormigon'], 2, ",", "."); ?>
                </td>
                <td></td>
                <td></td>
            </tr>
            <?php
        }
        if($miempleado['inasistenciapagas']*1>0) {
            ?>
            <tr>
                <td>5</td>
                <td>
                   Inasistencias pagas
                </td>
                <td class="tdWithNumber">
                    <?php echo number_format($miempleado['inasistenciapagas'], 2, ",", "."); ?>
                </td>
                <td></td>
                <td></td>
            </tr>
            <?php
        }
        if($miempleado['inasistencia']*1>0){ ?>
            <tr>
                <td>7</td>
                <td>
                    Inasistencias
                </td>
                <td class="tdWithNumber">
                    -<?php echo number_format($miempleado['inasistencia'], 2, ",", "."); ?>
                </td>
                <td></td>
                <td></td>
            </tr>
        <?php }
        if($miempleado['suspensiones']*1>0){ ?>
            <tr>
                <td>8</td>
                <td>
                    Suspensiones
                </td>
                <td class="tdWithNumber">
                    -<?php echo number_format($miempleado['suspensiones'], 2, ",", "."); ?>
                </td>
                <td></td>
                <td></td>
            </tr>
        <?php }
        if($miempleado['feriadospagos']*1>0){ ?>
            <tr>
                <td>21</td>
                <td>
                   Feriados Pagos
                </td>
                <td class="tdWithNumber">
                    <?php echo number_format($miempleado['feriadospagos'], 2, ",", "."); ?>
                </td>
                <td></td>
                <td></td>
            </tr>
        <?php }
        if($miempleado['feriadosnotrabajadospagos']*1>0){ ?>
            <tr>
                <td>22</td>
                <td>
                   Feriados no trabajados Pagos
                </td>
                <td class="tdWithNumber">
                    <?php echo number_format($miempleado['feriadosnotrabajadospagos'], 2, ",", "."); ?>
                </td>
                <td></td>
                <td></td>
            </tr>
        <?php }
        if($miempleado['vacacionesremunerativas']*1>0){ ?>
            <tr>
                <td>51</td>
                <td>
                    Vacaciones Remunerativas
                </td>
                <td class="tdWithNumber">
                    <?php echo number_format($miempleado['vacacionesremunerativas'], 2, ",", "."); ?>
                </td>
                <td></td>
                <td></td>
            </tr>
        <?php }
        if($miempleado['antiguedad']*1>0){ ?>
        <tr>
            <td>71</td>
            <td>
                Antigüedad
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['antiguedad'], 2, ",", "."); ?>
            </td>
            <td></td>
            <td></td>
        </tr>
    <?php }
    if($miempleado['presentismo']*1>0){ ?>
        <tr>
            <td>91</td>
            <td>
                Presentismo basico
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['presentismo'], 2, ",", "."); ?>
            </td>
            <td></td>
            <td></td>
        </tr>
    <?php }
    if($miempleado['ajusteretroactivo']*1>0){ ?>
        <tr>
            <td>92</td>
            <td>
                Ajuste retroactivo
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['ajusteretroactivo'], 2, ",", "."); ?>
            </td>
            <td></td>
            <td></td>
        </tr>
    <?php }
    if($miempleado['horas50remunerativa']*1>0){ ?>
        <tr>
            <td>101</td>
            <td>
                Horas al 50
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['horas50remunerativa'], 2, ",", "."); ?>
            </td>
            <td></td>
            <td></td>
        </tr>
    <?php }
        if($miempleado['horas100remunerativa']*1>0){ ?>
        <tr>
            <td>102</td>
            <td>
                Horas al 100
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['horas100remunerativa'], 2, ",", "."); ?>
            </td>
            <td></td>
            <td></td>
        </tr>
    <?php }
    if($miempleado['adicionalcomplementarioss']*1>0){ ?>
        <tr>
            <td>122</td>
            <td>
                Adicional Complemento SS
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['adicionalcomplementarioss'], 2, ",", "."); ?>
            </td>
            <td></td>
            <td></td>
        </tr>
    <?php }
    if($miempleado['acuerdoremunerativo']*1>0){ ?>
        <tr>
            <td>146</td>
            <td>
                Acuerdo Remunerativo
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['acuerdoremunerativo'], 2, ",", "."); ?>
            </td>
            <td></td>
            <td></td>
        </tr>
    <?php }
        if($miempleado['acuerdoremunerativonobasico']*1>0){ ?>
        <tr>
            <td>146</td>
            <td>
                Acuerdo Remunerativo
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['acuerdoremunerativonobasico'], 2, ",", "."); ?>
            </td>
            <td></td>
            <td></td>
        </tr>
    <?php }
    if($miempleado['sac']*1>0){ ?>
        <tr>
            <td>281</td>
            <td>
                S.A.C.
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['sac'], 2, ",", "."); ?>
            </td>
            <td></td>
            <td></td>
        </tr>
    <?php }
    if($miempleado['acuerdonoremunerativo']*1>0){ ?>
        <tr>
            <td>301</td>
            <td>
                Acuerdo no remunerativo
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['acuerdonoremunerativo'], 2, ",", "."); ?>
            </td>
            <td></td>
            <td></td>
        </tr>
    <?php }
        if($miempleado['vacacionesnoremunerativas']*1>0){ ?>
            <tr>
                <td>321</td>
                <td>
                   Vacaciones. No Remunerativas
                </td>
                <td class="tdWithNumber">
                    <?php echo number_format($miempleado['vacacionesnoremunerativas'], 2, ",", "."); ?>
                </td>
                <td></td>
                <td></td>
            </tr>
    <?php }
        if($miempleado['antiguedadnoremunerativo']*1>0){ ?>
            <tr>
                <td>331</td>
                <td>
                    Antig. No Remunerativa
                </td>
                <td class="tdWithNumber">
                    <?php echo number_format($miempleado['antiguedadnoremunerativo'], 2, ",", "."); ?>
                </td>
                <td></td>
                <td></td>
            </tr>
    <?php }
        if($miempleado['presentismonoremunerativo']*1>0){ ?>
            <tr>
                <td>351</td>
                <td>
                   Presentismo no remunerativo
                </td>
                <td class="tdWithNumber">
                    <?php echo number_format($miempleado['presentismonoremunerativo'], 2, ",", "."); ?>
                </td>
                <td></td>
                <td></td>
            </tr>
    <?php }
        if($miempleado['feriadosnoremunerativo']*1>0){ ?>
            <tr>
                <td>352</td>
                <td>
                    Feriados No Remunerativos
                </td>
                <td class="tdWithNumber">
                    <?php echo number_format($miempleado['feriadosnoremunerativo'], 2, ",", "."); ?>
                </td>
                <td></td>
                <td></td>
            </tr>
        <?php }
        if($miempleado['horas50noremunerativo']*1>0){ ?>
            <tr>
                <td>353</td>
                <td>
                    Horas al 50
                </td>
                <td class="tdWithNumber">
                    <?php echo number_format($miempleado['horas50noremunerativo'], 2, ",", "."); ?>
                </td>
                <td></td>
                <td></td>
            </tr>
        <?php }
        if($miempleado['horas100noremunerativo']*1>0){ ?>
            <tr>
                <td>354</td>
                <td>
                    Horas al 100
                </td>
                <td class="tdWithNumber">
                    <?php echo number_format($miempleado['horas100noremunerativo'], 2, ",", "."); ?>
                </td>
                <td></td>
                <td></td>
            </tr>
        <?php }
    if($miempleado['sacnoremunerativo']*1>0){ ?>
        <tr>
            <td>481</td>
            <td>
               SAC no remunerativo
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['sacnoremunerativo'], 2, ",", "."); ?>
            </td>
            <td></td>
            <td></td>
        </tr>
    <?php }
    if($miempleado['vacacionesnogozadas']*1>0){ ?>
        <tr>
            <td>511</td>
            <td>
               Vacaciones no gozadas
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['vacacionesnogozadas'], 2, ",", "."); ?>
            </td>
            <td></td>
            <td></td>
        </tr>
    <?php }
    if($miempleado['sacIndemnizatorio']*1>0){ ?>
        <tr>
            <td>542</td>
            <td>
                SAC s/Conceptos Indemnizatorios
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['sacIndemnizatorio'], 2, ",", "."); ?>
            </td>
            <td></td>
            <td></td>
        </tr>
    <?php }
    if($miempleado['fondoceselaboral']*1>0){ ?>
        <tr>
            <td>543</td>
            <td>
                Fondo de Cese Laboral Efectivo
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['fondoceselaboral'], 2, ",", "."); ?>
            </td>
            <td></td>
            <td></td>
        </tr>
    <?php }

    if($miempleado['plusvacacional']*1>0){ ?>
        <tr>
            <td>000</td>
            <td>
                Plus Vacacional
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['plusvacacional'], 2, ",", "."); ?>
            </td>
            <td></td>
            <td></td>
        </tr>
    <?php }
    if($miempleado['jubilacion']*1>0){ ?>
        <tr>
            <td>
                701
            </td>
            <td></td>
            <td></td>

            <td>
                Jubilacion
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['jubilacion'], 2, ",", "."); ?>
            </td>
        </tr>
    <?php }
    if($miempleado['ley19032']*1>0){ ?>
        <tr>
            <td>
                706
            </td>
            <td></td>
            <td></td>

            <td>
                Ley 19032-INSSJP
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['ley19032'], 2, ",", "."); ?>
            </td>
        </tr>
    <?php }
    if($miempleado['obrasocial']*1>0){ ?>
        <tr>
            <td>
                721
            </td>
            <td></td>
            <td></td>

            <td>
                <?php echo $miempleado['obrasocialnombre']; ?>

            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['obrasocial'], 2, ",", "."); ?>
            </td>
        </tr>
    <?php }
    if($miempleado['obrasocialextraordinario']*1>0){ ?>
        <tr>
            <td>
                722
            </td>
            <td></td>
            <td></td>

            <td>
                <?php echo $miempleado['obrasocialextraordinarionombre']; ?>

            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['obrasocialextraordinario'], 2, ",", "."); ?>
            </td>
        </tr>
    <?php }
    if($miempleado['cuotasindical']*1>0){ ?>
        <tr>
            <td>
                821
            </td>
            <td></td>
            <td></td>

            <td>
                <?php echo $miempleado['cuotasindicalnombre']; ?>

            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['cuotasindical'], 2, ",", "."); ?>
            </td>
        </tr>
    <?php }
    if($miempleado['cuotasindical1']*1>0){ ?>
        <tr>
            <td>
                822
            </td>
            <td></td>
            <td></td>

            <td>
                <?php echo $miempleado['cuotasindical1nombre']; ?>

            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['cuotasindical1'], 2, ",", "."); ?>
            </td>
        </tr>
    <?php }
    if($miempleado['cuotasindical2']*1>0){ ?>
        <tr>
            <td>
                823
            </td>
            <td></td>
            <td></td>

            <td>
                <?php echo $miempleado['cuotasindical2nombre']; ?>

            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['cuotasindical2'], 2, ",", "."); ?>
            </td>
        </tr>
    <?php }
    if($miempleado['cuotasindical4']*1>0){ ?>
        <tr>
            <td>
                824
            </td>
            <td></td>
            <td></td>

            <td>
                <?php echo $miempleado['cuotasindical4nombre']; ?>

            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['cuotasindical4'], 2, ",", "."); ?>
            </td>
        </tr>
    <?php }
    if($miempleado['cuotasindical3']*1>0){ ?>
        <tr>
            <td>
                825
            </td>
            <td></td>
            <td></td>

            <td>
                <?php echo $miempleado['cuotasindical3nombre']; ?>

            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['cuotasindical3'], 2, ",", "."); ?>
            </td>
        </tr>
    <?php }
    if($miempleado['renatea']*1>0){ ?>
        <tr>
            <td>
                825
            </td>
            <td></td>
            <td></td>

            <td>
                RENATEA Aporte
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['renatea'], 2, ",", "."); ?>
            </td>
        </tr>
    <?php }
    if($miempleado['embargo']*1>0){ ?>
        <tr>
            <td>902</td>
            <td>
            </td>
            <td >
            </td>
            <td>Embargo judicial</td>
            <td class="tdWithNumber"> <?php
                echo number_format($miempleado['embargo'] , 2, ",", ".");
                ?>
            </td>
        </tr>
    <?php }
    if($miempleado['anticipos']*1>0){ ?>
        <tr>
            <td>911</td>
            <td>
            </td>
            <td >
            </td>
            <td>Anticipos</td>
            <td class="tdWithNumber"> <?php
                echo number_format($miempleado['anticipos'] , 2, ",", ".");
                ?>
            </td>
        </tr>
    <?php }
    if($miempleado['redondeo']*1>0){ ?>
        <tr>
            <td>980</td>
            <td>
                Redondeo
            </td>
            <td class="tdWithNumber">
                <?php
                    echo number_format($miempleado['redondeo'] , 2, ",", ".");
                ?>
            </td>
            <td></td>
            <td></td>
        </tr>
    <?php } ?>
        <tr>
            <td>990</td>
            <td>
                Total remuneraciones
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($totalremuneracion+$miempleado['redondeo'], 2, ",", "."); ?>
            </td>
            <td>
                Total descuentos
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($totaldescuento+$miempleado['embargo']+$miempleado['anticipos'], 2, ",", "."); ?>
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
                <?php echo number_format($neto, 2, ",", "."); ?>
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
