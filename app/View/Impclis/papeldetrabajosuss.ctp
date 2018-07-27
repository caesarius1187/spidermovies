<?php 
echo $this->Html->css('bootstrapmodal');

echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false));
echo $this->Html->script('jquery.table2excel',array('inline'=>false));
echo $this->Html->script('impclis/papeldetrabajosuss',array('inline'=>false));
echo $this->Html->script('jquery-ui',array('inline'=>false));
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));

echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
echo $this->Form->input('impcliidPDT',array('value'=>$impcliid,'type'=>'hidden'));
echo $this->Form->input('clinombre',array('value'=>$impcli['Cliente']['nombre'],'type'=>'hidden'));
echo $this->Form->input('impcliid',array('value'=>$impcliid,'type'=>'hidden'));
echo $this->Form->input('cliid',array('value'=>$impcli['Cliente']['id'],'type'=>'hidden'));?>
<script>
    function exportarFacturas() {
        var container = $('#divEmpleados');
        var anchor = $('#aExportarEmpleados');
        anchor.href = 'data:text/plain;charset=utf-8,' + encodeURIComponent(container.html());
        anchor.download = 'Exportacion SUSS.txt';
    };
    function downloadInnerHtml(filename, elId, mimeType) {
        var elHtml = document.getElementById(elId).innerHTML;
        var elTXT = elHtml.replace(/(?:\r\n|\r|\n)/g, '<br />');
        elTXT = elTXT.replace(/&nbsp;/gi," ");
        elTXT = elTXT.replace(/<br\s*\/?>/mg,"\r\n");

        var link = document.createElement('a');
        mimeType = mimeType || 'text/plain';

        link.setAttribute('download', filename);
        link.setAttribute('href', 'data:' + mimeType + ';charset=utf-8,' + encodeURIComponent(elTXT));
        link.click();
    }
</script>
<div class="eventosclientes index">
    <div id="divLiquidarSUSS">
    </div>
	<div id="sheetCooperadoraAsistencial" class="index" style="overflow: auto; margin-bottom:10px;">
		<!--Esta es la tabla original y vamos a recorrer todos los empleados por cada una de las
		rows por que -->
        <b style="display: inline">Papel de Trabajo</b>
        <?php echo $this->Form->button('Imprimir',
            array('type' => 'button',
                'id'=>"btnImprimir",
                'class' =>"btn_imprimir",
                'onClick' => "openWin()"
            )
        );?>
        <?php echo $this->Form->button('Excel',
            array('type' => 'button',
                'id'=>"clickExcel",
                'class' =>"btn_imprimir",
            )
        );?>
        <?php         
        if($impuestosactivos['ivaId']!=0){ 
            echo $this->Form->button('Generar Dcto 814',
                array('type' => 'button',
                    'id'=>"btnImprimir",
                    'class' =>"btn_imprimir",
                    'style' =>"width: auto;",
                    'onClick' => "guardarDcto814()"
                )
            );
        }?>
        
        <!-- Solo para Excel Export -->
        <table id="tblExcelHeader" class="tbl_tareas" style="border-collapse: collapse; width:100%;">
        </table>
        <!-- Solo para Excel Export -->
        <?php
        $empleadoDatos = array();
        $sindicatos['sindicato']=[];
        foreach ($impcli['Cliente']['Empleado'] as $empleado) {
            $miempleado = array();
            if(!isset($miempleado['horasDias'])) {
                $miempleado['jornada'] = 0;
                $miempleado['sindicato'] = [];// empleador puede tener muchos sindicatos
                $miempleado['sindicatoextra'] = [];//el sindicato asociado por ej FAECYS etc. que pueden ser varios tambien
                $miempleado['redondeo'] = 0;
                $miempleado['osdelpersdedireccion'] = false;
                $miempleado['coberturaart'] = true;//todo inicializar en false cuando tengamos de donde sacar el dato
                $miempleado['segurodevida'] = true;//todo inicializar en false cuando tengamos de donde sacar el dato
                $miempleado['adherente'] = $empleado['adherente'];

                $miempleado['horasDias'] = 0;
                $miempleado['sueldo'] = 0;
                $miempleado['diadelgremiotrabajado'] = 0;
                $miempleado['diadelgremio'] = 0;
                $miempleado['adicionales'] = 0;
                $miempleado['embargos'] = 0;
                $miempleado['horasextras'] = 0;
                $miempleado['importehorasextras'] = 0;
                $miempleado['diassac'] = 0;
                $miempleado['SAC'] = 0;
                $miempleado['vacaciones'] = 0;
                $miempleado['diasvacaciones'] = 0;
                $miempleado['premios'] = 0;
                $miempleado['maternidad'] = 0;
                $miempleado['conceptosnorem'] = 0;
                
                $miempleado['situacionrevista1'] = 01;                             
                $miempleado['situacionrevista2'] = 00;                             
                $miempleado['situacionrevista3'] = 00;      
                $miempleado['diasituacionrevista1'] = 01;      
                $miempleado['diasituacionrevista2'] = 00;      
                $miempleado['diasituacionrevista3'] = 00;      
                
                $miempleado['remtotal'] = 0;
                $miempleado['rem1'] = 0;
                $miempleado['titleRem10'] = 0;
                $miempleado['rem2'] = 0;
                $miempleado['importeADetraer'] = 0;
                $miempleado['rem3'] = 0;
                $miempleado['rem4'] = 0;
                $miempleado['rem5'] = 0;
                $miempleado['rem6'] = 0;
                $miempleado['rem7'] = 0;
                $miempleado['rem8'] = 0;
                $miempleado['rem9'] = 0;
                $miempleado['rem10'] = 0;
                //Seguridad Social
                $miempleado['seguridadsocialaporteadicional'] = 0;
                $miempleado['seguridadsocialcontribtareadif'] = 0;
                //Obra Social
                $miempleado['obrasocialaporteadicional'] = 0;
                $miempleado['obrasocialcontribucionadicional'] = 0;
                //Contrib SS
                $miempleado['ContribSSjubilacionsipa'] = 0;
                $miempleado['INSSJP'] = 0;
                $miempleado['ContribSScontribtareadif'] = 0;
                $miempleado['FNE'] = 0;
                $miempleado['ContribSSANSSAL'] = 0;
                $miempleado['asignacionfamiliar'] = 0;
                $miempleado['totalContribucionesSS'] = 0;
                //RENATEA
                $miempleado['contribucionrenatea'] = 0;
                $miempleado['aporterenatea'] = 0;
                //Aporte SS
                $miempleado['AporteSSjubilacionsipa'] = 0;
                $miempleado['ley19032'] = 0;
                $miempleado['aporteadicionalss'] = 0;
                $miempleado['AporteSSANSSAL'] = 0;
                $miempleado['AporteSStotal'] = 0;
                //Contribucion OS
                $miempleado['ContribucionesOScontribucionos'] = 0;
                $miempleado['ContribucionesOScontribucionadicionalos'] = 0;
                $miempleado['ContribucionesOSANSSAL'] = 0;
                $miempleado['ContribucionesOStotal'] = 0;
                //Aportes OS
                $miempleado['AporteOSaporteos'] = 0;
                $miempleado['AporteOSaporteadicionalos'] = 0;
                $miempleado['AporteOSANSSAL'] = 0;
                $miempleado['AporteOSadicionaladherente'] = 0;
                $miempleado['AporteOStotal'] = 0;
                $miempleado['ARTart'] = 0;
                $miempleado['SeguroDeVidaObligatorio'] = 0;
                //calculo auxiliar para sheetAPagar
                $miempleado['TotalRemCD'] = 0;
                $miempleado['codigoafip'] = 0;
                $miempleado['totalAPagar'] = 0;
            }
            $osdelpersdedireccion = $miempleado['osdelpersdedireccion'];//todo inicializar en false cuando tengamos de donde sacar el dato
            $coberturaart = $miempleado['coberturaart'];//todo inicializar en false cuando tengamos de donde sacar el dato
            $segurodevida = $miempleado['segurodevida'];//todo inicializar en false cuando tengamos de donde sacar el dato

            $horasDias=0;$jornada=0;
            $sueldo=0;
            $diadelgremiotrabajado=0;
            $diadelgremio=0;
            $adicionales=0;
            $embargos=0;
            $horasextras=0;$importehorasextras=0;
            $diassac=0;
            $SAC=0;
            $vacaciones=0;
            $diasvacaciones=0;
            $premios=0;$maternidad=0;$conceptosnorem=0;
            
            $situacionrevista1=01;
            $situacionrevista2=01;
            $situacionrevista3=01;
            $diasituacionrevista1=01;
            $diasituacionrevista2=00;
            $diasituacionrevista3=00;
            
            $remtotal=0;$rem1=0;$rem2=0;$rem3=0;$rem4=0;
            $rem5=0;$rem6=0;$rem7=0;$rem8=0;$rem9=0;
            $rem10=0;
            $importeADetraer=0;
            $redondeo=0;
            //Seguridad Social
            $seguridadsocialaporteadicional=0;
            $seguridadsocialcontribtareadif=0;
            //Obra Social
            $obrasocialaporteadicional=0;
            $obrasocialcontribucionadicional=0;
            $minimoOS=0;

            //Contrib SS
            $ContribSSjubilacionsipa=0;$INSSJP=0;
            $ContribSScontribtareadif=0;$FNE=0;$ContribSSANSSAL=0;$asignacionfamiliar=0;$totalContribucionesSS=0;
            $ContribSScontribtareadifAux=0;
            //RENATEA
            $contribucionrenatea = 0;
            $trabajadorAgrario = false;
            $aporterenatea = 0;
            //Aportes SS
            $AporteSSjubilacionsipa=0;
            $ley19032 = 0;
            $AporteSSaporteadicional=0;
            $AporteSSANSSAL=0;;
            $AporteSStotal=0;
            //Contribucion OS
            $ContribucionesOScontribucionos=0;
            $ContribucionesOScontribucionadicionalos=0;
            $ContribucionesOSANSSAL=0;
            $ContribucionesOStotal=0;
            //Aportes OS
            $AporteOSaporteos=0;
            $AporteOSaporteadicionalos=0;
            $AporteOSANSSAL=0;
            $AporteOSadicionaladherente = 0;
            $AporteOStotal = 0;

            $SeguroDeVidaObligatorio = 0;
            $ARTart = 0;
            $rem4aportenegativo = 0;
            $rem4menorminimo = 0;
            $cantidadAdherentes = $miempleado['adherente'] ;/*todo Este es un campo que tiene que estar en Empleado y lo tengo que levantar de ahi*/

            //calculo auxiliar para sheetAPagar
            $TotalRemCD = 0;
            $codigoafip = $empleado['adherente']!=null?$empleado['adherente']:0;
            //todo esto hay que cambiarlo por el codigo afip que tenga el empleado
            /*Aca voy a averiguar si paga INACAP ( si tiene SEC paga INACAP) */
            $contibuciones=[];
            if($empleado['Conveniocolectivotrabajo']['impuesto_id']=='11'/*SEC*/){
                //INACAP solo paga comercio asi que hay que ver que sean los empleados de comercio los que paguen esto y no otros
                $empeadosdecomercio=0;
                foreach ($impcli['Cliente']['Empleado'] as $empleadodecomercio){
                    if($empleadodecomercio['conveniocolectivotrabajo_id']=='3'/*Si es comercio Acumulo*/){
                        $empeadosdecomercio++;
                    }
                }
                $contibuciones['INACAP']=13658.32*0.005*$empeadosdecomercio; //Esto tiene q salir de la escala salarial
                $contibuciones['SEC'] =  8; //Esto tiene q salir de la escala salarial
            }

            foreach ($empleado['Valorrecibo'] as $valorrecibo) {
                //Codigo AFIP
                if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('51'/*codigo afip*/), true )){
                    $codigoafip = $valorrecibo['valor'];
                }
                //Sindicato
                if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('36'/*cuota sindical*/,'37'/*cuota sindical 1*/,'38'/*cuota sindical 2*/,), true )){
                    if(isset($empleado['Conveniocolectivotrabajo']['Impuesto']['nombre'])){
                        $nombresindicato=$empleado['Conveniocolectivotrabajo']['Impuesto']['nombre'];
                        if(!isset($sindicatos['sindicato'][$nombresindicato])){
                            $sindicatos['sindicato'][$nombresindicato] = 0;//El sindicato propiamente dicho
                        }
                    }
                    $sindicatos['sindicato'][$nombresindicato] += $valorrecibo['valor'];
                }
                //Sindicato Extra (FAECYS, etc)
                if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='114'/*cuota sindical 3*/){
                    if(isset($empleado['Conveniocolectivotrabajo']['Impuesto']['nombre'])){
                        $nombresindicato=$empleado['Conveniocolectivotrabajo']['Impuesto']['nombre'];
                        //ACA deben estar todas las relaciones con sindicatos que dependen de otros sindicatos
                        //Todo Mantener Relacion dependencia sindicatos actualizada
                        if($nombresindicato=="SEC"){
                            $nombresindicato="FAECYS";
                        }else{

                        }
                        if(!isset($sindicatosextra['sindicatoextra'][$nombresindicato])){
                            $sindicatosextra['sindicatoextra'][$nombresindicato] = 0;//el sindicato asociado por ej FAECYS etc.
                        }
                    }
                    $sindicatosextra['sindicatoextra'][$nombresindicato] += $valorrecibo['valor'];
                }
                //Sindicato Extra (Seg vida Compercio, etc)
                if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='134'/*cuota sindical 4*/){
                    if(isset($empleado['Conveniocolectivotrabajo']['Impuesto']['nombre'])){
                        $nombresindicato=$empleado['Conveniocolectivotrabajo']['Impuesto']['nombre'];
                        //ACA deben estar todas las relaciones con sindicatos que dependen de otros sindicatos
                        //Todo Mantener Relacion dependencia sindicatos actualizada
                        if($nombresindicato=="SEC"){
                            $nombresindicato="Seguro de vida obligatorio comercio";
                        }else{
                        }
                        if(!isset($sindicatosextra['sindicatoextra'][$nombresindicato])){
                            $sindicatosextra['sindicatoextra'][$nombresindicato] = 0;//el sindicato asociado por ej FAECYS etc.
                        }
                    }
                    $sindicatosextra['sindicatoextra'][$nombresindicato] += $valorrecibo['valor'];
                }
                //Jornada
                if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='11'/*jornada*/){
                    $jornada =$valorrecibo['valor'];
                }
                //Horas Diarias
                if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='12'/*Dias u Horas*/){
                    $horasDias +=$valorrecibo['valor'];
                }
                //Sueldo
                if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='21'/*Total basicos*/){
                    $sueldo += $valorrecibo['valor'];
                }
                //si no trabajo el dia del gremio hay que sumarlo al basico, si lo trabajo al adicional
                if($valorrecibo['Cctxconcepto']['Concepto']['id']=='178'/*Dia del gremio trabajado*/){
                    $diadelgremiotrabajado = $valorrecibo['valor'];
                }
                if ($valorrecibo['Cctxconcepto']['Concepto']['id'] == '75'/*Dia del gremio*/) {
                    $diadelgremio += $valorrecibo['valor'];
                }
                //Redondeo
                if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='124'/*Redondeo*/){
                    $redondeo +=$valorrecibo['valor'];
                }
                if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='20'/*Vacaciones Remunerativas*/){
                    $sueldo -= $valorrecibo['valor'];
                }                
                //Adicionales
                if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    [
                        '18'/*Antiguedad*/,
                        '76'/*Presentismo*/,
//                        '81'/*Plus Vacacional*/,
//                        '82'/*Adicional Complemento SS*/,
                        '127'/*Total Acuerdo Remunerativo*/,
                    ], true )){
                    $adicionales += $valorrecibo['valor'];
                }
                //Embargos
                if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('133'/*Embargos*/), true )){
                    $embargos+= $valorrecibo['valor'];
                }
                //Cantidad de Horas Extras
                if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('22'/*Horas al 50%*/,'23'/*Horas al 100%*/), true )){
                    $horasextras += $valorrecibo['valor'];
                }
                //Importe Horas Extras
                if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('24'/*Total Horas Extras*/), true )
                ){
                    $importehorasextras += $valorrecibo['valor'];
                }
                //dias sac
                if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('57'/*sac*/), true )
                ){
                    $diassac += $valorrecibo['valor'];
                }
                //SAC
                if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('92'/*S.A.C. Remunerativo 1*/,'93'/*S.A.C. Remunerativo 2*/,'94'/*Total SAC Remunerativo*/), true )
                ){
                    $SAC += $valorrecibo['valor'];
                }
                //Vacaciones Remunerativas
                if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('20'/*Vacaciones Remunerativas*/), true )
                ){
                    $vacaciones += $valorrecibo['valor'];
                }
                //Dias Vacaciones
                if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    [
                        '60'/*Vacaciones No Gozadas*/,
                        '13'/*Vacaciones*/,//                     
                    ], true )){
                    $diasvacaciones += $valorrecibo['valor'];
                }
                //Situacion Revista 1
                if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    [
                        '190'/*Situacion revista 1*/,//                     
                    ], true )){
                    if(!is_null($valorrecibo['valor'])&&$valorrecibo['valor']!=""&&$valorrecibo['valor']!="01")
                        $situacionrevista1 += $valorrecibo['valor'];
                }
                //Situacion Revista 2
                if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    [
                        '192'/*Situacion revista 2*/,//                     
                    ], true )){
                    if(!is_null($valorrecibo['valor'])&&$valorrecibo['valor']!=""&&$valorrecibo['valor']!="01")
                        $situacionrevista2 += $valorrecibo['valor'];
                }
                //Situacion Revista 3
                if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    [
                        '194'/*Situacion revista 3*/,//                     
                    ], true )){
                    if(!is_null($valorrecibo['valor'])&&$valorrecibo['valor']!=""&&$valorrecibo['valor']!="01")
                        $situacionrevista3 += $valorrecibo['valor'];
                }
                //Dia Situacion Revista 1
                if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    [
                        '191'/*Situacion revista 1*/,//                     
                    ], true )){
                    $diasituacionrevista1 += $valorrecibo['valor'];
                }
                //Dia Situacion Revista 2
                if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    [
                        '193'/*Situacion revista 2*/,//                     
                    ], true )){
                    $diasituacionrevista2 += $valorrecibo['valor'];
                }
                //Dia Situacion Revista 3
                if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    [
                        '195'/*Situacion revista 3*/,//                     
                    ], true )){
                    $diasituacionrevista3 += $valorrecibo['valor'];
                }
                //Premios
                if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('91'/*Total otros R32*/), true )
                ){
                    $premios += $valorrecibo['valor'];
                }
                //unificar TOTAL OTROS en todos los convenios
                //otros:
                /*
                 * a cuenta de aumento
                 * ajuste retroactivo
                 * gratificacion
                 * ormigon
                 * plus vacaciona
                 * adicional complemento de servicio
                 * adicional por titulo
                 * adicional por curso
                 * adicional por idioma
                 * ripa de trabajo
                 * viatico
                 * vianda
                 * zona desfaborable
                 * premio
                 * */

                //Maternidad
                /*
                 * El ANSES paga to do el tiempo de maternidad que esta persona este de lic por maternidad
                 * puede ser una parte del mes y la otra la paga el empleador
                 * todo hablar con maria
                 * podriamos hacer un recibo de sueldo por separado para lo calculado en maternidad
                 * */
                if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('29'/*Maternidad*/), true )
                ){
                    if($valorrecibo['valor']){
                        $maternidad = $valorrecibo['valor'];
                    }
                }
                //Conceptos No Remunerativos
                if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('109'/*Total Remunerativos S/D*/), true )
                ){
                    $conceptosnorem += $valorrecibo['valor'];
                }
                //Remuneracion Total
                if (
                in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('27'/*Total Remunerativos C/D*/,'109'/*Total Remunerativos S/D*/,'108'/*Total Rem. S/D Indemnizatorios*/), true )
                ){
                    $remtotal += $valorrecibo['valor'];
                }
                //Remuneracion 1 y 2 
                if (
                in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('27'/*Total Remunerativos C/D*/), true )
                ){
                    $rem1 += $valorrecibo['valor'];
                    $rem2 += $valorrecibo['valor'];
                }
               
                
                //Remuneracion 3
                $rem3 = $rem1;
                //Seguridad Social Aporte Adicional
                if (
                in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('119'/*Aportes Adicionales Seguridad Social*/), true )
                ){
                    $seguridadsocialaporteadicional += $valorrecibo['valor'];
                }

                //Obra Social Aporte Adicional
                if (
                in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('117'/*Aportes Adicionales Obra Social*/), true )
                ){
                    $obrasocialaporteadicional += $valorrecibo['valor'];
                }
                //Obra Social Contribucion Adicional
                if (
                in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('118'/*Contribuciones Adicionales Obra Social*/), true )
                ){
                    $obrasocialcontribucionadicional += $valorrecibo['valor'];
                }
                //Remuneracion 4 Primera parte
                //minimo OS
                if (
                in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('34'/*OSUTHGRA Minimo*/), true )
                ){
                    $minimoOS += $valorrecibo['valor'];
                }

                if (
                in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('27'/*Total Remunerativos C/D*/,'109'/*Total Remunerativos S/D*/), true )
                ){
                    $rem4aportenegativo += $valorrecibo['valor'];
                }
                if (
                in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('27'/*Total Remunerativos C/D*/,'109'/*Total Remunerativos S/D*/), true )
                ){
                    $rem4menorminimo += $valorrecibo['valor'];
                }
                if (
                in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('108'/*Total Rem. S/D Indemnizatorios	*/), true )
                ){
                    $rem4aportenegativo -= $valorrecibo['valor'];
                }

                //Remuneracion 9 (
                //La remuneracion 9 tiene que pagar por los no rem pero no por los no rem indemnizatorios
                //)
                if (
                    in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('27'/*Total Remunerativos C/D*/,'103'/*Total Remunerativos S/D*/), true )
                ){
                    $rem9 += $valorrecibo['valor'];
                }
//                if (
//                in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
//                    array('108'/*Total Remunerativos S/D*/), true )
//                ){
//                    $rem9 -= $valorrecibo['valor'];
//                }
                //contribucionrenatea Primera parte
                if(isset($valorrecibo['Cctxconcepto']['Conveniocolectivotrabajo']['trabajorural'])){
                    if ($valorrecibo['Cctxconcepto']['Conveniocolectivotrabajo']['trabajorural']){
                        $trabajadorAgrario = true;
                    }
                }
                //Aporte RENATEA
                if (
                in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('121'/*Aporte RENATEA*/), true )
                ){
                    $aporterenatea += $valorrecibo['valor'];
                }
                //Aportes Seguridad Social Jubilacion Aporte SS
                if (
                in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('28'/*Jubilacion*/), true )
                ){
                    $AporteSSjubilacionsipa += $valorrecibo['valor'];
                }
                //Seguridad Social Ley 19032
                if (
                in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('32'/*Ley 19032*/), true )
                ){
                    $ley19032 += $valorrecibo['valor'];
                }
                //Seguridad Social Aportes Adicionales
                if (
                in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('119'/*Obra Social Extraordinaria*/), true )
                ){
                    $AporteSSaporteadicional += $valorrecibo['valor'];
                }
                //Contribucion Adicional OS
                if (
                in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('118'/*Contribucion Adicional OS*/), true )
                ){
                    $ContribucionesOScontribucionadicionalos += $valorrecibo['valor'];
                }
                //Aporte OS aporte adicional os
                if (
                in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('117'/*Aporte Adicional OS*/), true )
                ){
                    $AporteOSaporteadicionalos += $valorrecibo['valor'];
                }
                //calculo Auxiliar para sheetAPagar
                if (
                in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('27'/*Total Remunerativos C/D*/), true )
                ){
                    $TotalRemCD += $valorrecibo['valor'];
                }
                //calculo Auxiliar para Contribucion Tarea Diff
                if (
                in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('123'/*Contribucion tarea Diff*/), true )
                ){
                    if($valorrecibo['valor']*1!=0){
                        $seguridadsocialcontribtareadif = $valorrecibo['valor'];
                    }
                }
            }
            
            $titleRem10 = "Rem2 = ".$rem2;
            //vamos a aplicar nueva reglamentacion a partir del 01-2018
            //si el codigo AFIP es 0 => $rem2 = ($rem2 - (12000*alicuota*jornada*sac))
            $alicuotasContribuciones = [
                2018=>0.20,
                2019=>0.40,
                2020=>0.60,
                2021=>0.80,
                2022=>1,
            ];
            $pemes = substr($periodo, 0, 2)*1;
            $peanio = substr($periodo, 3)*1;
            if($diassac>0){
                $porcentajeSAC = 1 + $diassac/360 ;    
            }else{
                $porcentajeSAC = 1;
            }
            $reduccionPorCodigoContratacion = 1;
            if($codigoafip=='0'){
                //reduccion 0%
                $titleRem10 .= " reduccion 0%";
                $reduccionPorCodigoContratacion = 1;
            }
            if($codigoafip=='1'){
                //reduccion 50%
                $titleRem10 .= " reduccion 50%";
                $reduccionPorCodigoContratacion = 0.5;
            }
            if($codigoafip=='2'){
                //reduccion 25%
                $titleRem10 .= " reduccion 25%";
                $reduccionPorCodigoContratacion = 0.75;
            }
            if($codigoafip=='3'){
                //reduccion 75%
                $titleRem10 .= " reduccion 75%";
                $reduccionPorCodigoContratacion = 0.25;
            }
            if($codigoafip=='4'){
                //reduccion 100%
                $titleRem10 .= " reduccion 100%";
                $reduccionPorCodigoContratacion = 0;
            }
            $rem10 = $rem2;
            $porcentajeReduccionPorJornada = $jornada;
            /*if($jornada<=0.66){
                $porcentajeReduccionPorJornada = 0.66;
            }*/
            
            if(isset($alicuotasContribuciones[$peanio])&&$codigoafip=='0'&&$impcli['Impcli']['aplicaley27430']=='1'){
                //si tiene vacaciones NO se multiplica por la jornada
                if($diasvacaciones>0){
                    $reduccion = (12000*$alicuotasContribuciones[$peanio]*$porcentajeSAC);
                    $titleRem10 .= " monto reduccion: (12000*".$alicuotasContribuciones[$peanio]."*".$porcentajeSAC.")=".$reduccion;
                }else{
                    $reduccion = (12000*$alicuotasContribuciones[$peanio]*$porcentajeReduccionPorJornada*$porcentajeSAC);
                    $titleRem10 .= " monto reduccion: (12000[monto total reduccion]*".$alicuotasContribuciones[$peanio]."[porcentaje anual habilitado]*".$porcentajeReduccionPorJornada."[jornada][".$jornada."]*".$porcentajeSAC."[incremental por SAC])=".$reduccion;
                }
               
                $rem10 = ($rem2 - $reduccion);
                $importeADetraer = $reduccion;
                if($rem10<0)$rem10=0;
            }
            $valorMopre = 840.20;
            $minimoContribuciones = 3*840.20;
            //si la REm2 < que 3MOPRE => se toma REM2 para contribuciones y REM10 = 0;
            if($rem2<$minimoContribuciones){
                $titleRem10 .= " Rem2 < Minimo: ".$minimoContribuciones.", entonces rem10 = 0 y se toma Rem 2 para calcular contribuciones";
                $importeADetraer=0;
                $rem10=$rem2;
            }
           

            //Remuneracion 4 Segunda Parte
            $titlerem4="";
            if($AporteOSaporteadicionalos>0){
                $titlerem4="Aporte adicional > 0, entonces Rem 4 = Rem 1 ";
                $rem4 = $rem1;
            }else{
                $titlerem4="Aporte adicional <= 0, ";
                if($rem4aportenegativo<=$minimoOS){
                    $titlerem4 .= $rem4aportenegativo."<=".$minimoOS." entonces Rem 4 = minimo OS:".$minimoOS;
                    $rem4=$minimoOS;
                }else{
                    $titlerem4 .= $rem4aportenegativo.">=".$minimoOS." entonces Rem 4 = Total Remunerativos C/D+S/D: ".$rem4menorminimo;
                    $rem4=$rem4aportenegativo;
//                    $rem4=$rem4menorminimo;
                }
            }
            //Rem. 8 (Cont. OS)
            $rem8 = $rem4;
            //Jubilacion SIPA
            //todo cambiar por concepto guardado solo si se ah guardado un concepto "CODIGO AFIP"
            //Y si no tiene codigo que use 0
            
            //Cuando salgan las alicuotas de Contribucion hay que cargarlas aca
            //una por cada año 2017/2018/2019/2020
            $porcentajes = [
                2017=>0.17,
                2018=>0.175,
                2019=>0.18,
                2020=>0.185,
                2021=>0.19,
                2022=>0.195,
            ];
            /*$proporcionContribuciones = [
                'inssjp'=>0.088235,//0,088034
                'sipa'=>0.598235,//0,59852
                'FNE'=>0.052353,//0,052594
                'AsigFliar'=>0.261176//0,261247
            ];*/
            $proporcionContribuciones = [
                'inssjp'=>0.088034,
                'sipa'=>0.59852,
                'FNE'=>0.052594,
                'AsigFliar'=>0.261247,
            ];
            
            $nuevaContSSJubSIPA = $porcentajes[$peanio]*$proporcionContribuciones['sipa'];
            $nuevaContSSinssjp = $porcentajes[$peanio]*$proporcionContribuciones['inssjp'];
            $nuevaContSSFNE = $porcentajes[$peanio]*$proporcionContribuciones['FNE'];
            $nuevaContSSAsigFliar = $porcentajes[$peanio]*$proporcionContribuciones['AsigFliar'];
            
            if($codigoafip=='0'){
                //reduccion 0%
                $ContribSSjubilacionsipa+=$rem10*$nuevaContSSJubSIPA;
                //Debugger::dump("codigo: 0; rem 2: ".$rem10);
                //Debugger::dump("nuevo sipa: ".$nuevaContSSJubSIPA);
                $INSSJP+=$rem10*$nuevaContSSinssjp;
                //Asignaciones Familiares
                $asignacionfamiliar+=$rem10*$nuevaContSSAsigFliar;
            }else{
                $ContribSSjubilacionsipa+=$rem10*$nuevaContSSJubSIPA*$reduccionPorCodigoContratacion;
                $INSSJP+=$rem10*$nuevaContSSinssjp*$reduccionPorCodigoContratacion;
                 //Asignaciones Familiares
                $asignacionfamiliar+=$rem10*$nuevaContSSAsigFliar*$reduccionPorCodigoContratacion;
                
            }
            //Contrib Tarea Dif
            $ContribSScontribtareadif = $rem1*($seguridadsocialcontribtareadif/100);

            //Jubilacion FNE
            if(!$trabajadorAgrario){
                $FNE+=$rem10*$nuevaContSSFNE*$reduccionPorCodigoContratacion;
            }else{

            }
             if($rem2<$minimoContribuciones){
                $rem10=0;
            }
           
            //Contrib Seg Soc ANSSAL
            $minimoANSSAL = 2400;
            /*este dato de obra social sindical debe ser marcado en el empleado
            para que cambie la distribucion de ANSSAL con este formato
            Para las Obras Sociales Sindicales:
            Hasta $ 2.400.-: 90% Obra social y 10% ANSSAL
            Más de $ 2.400.-: 85% Obra social y 15% ANSSAL.
            Obras Sociales del Personal de Dirección y de las Asociaciones profesionales de empresarios:
            Hasta $ 2.400.-: 85% Obra social y 15% ANSSAL
            Más de $ 2.400.-: 80% Obra social y 20% ANSSAL
            */
            $ObraSocialSindical = $empleado['obrasocialsindical'];
            $alicuotaMinimoANSSAL = 0.1;
            $alicuotaANSSAL = 0.15;
            $alicuotaAporteObraSocial = 0.0255;
            $alicuotaContribucionObraSocial = 0.051;
            if(!$ObraSocialSindical){
                $alicuotaMinimoANSSAL = 0.15;
                $alicuotaANSSAL = 0.20;
                $alicuotaAporteObraSocial = 0.024;
                $alicuotaContribucionObraSocial = 0.048;
            }

            if($rem8<$minimoANSSAL){
                $ContribSSANSSAL+=($rem8*0.060+$ContribucionesOScontribucionadicionalos)*$alicuotaMinimoANSSAL;
            }else{
                $ContribSSANSSAL+=($rem8*0.060+$ContribucionesOScontribucionadicionalos)*$alicuotaANSSAL;
            }
           
            //Total Contribuciones
            $totalContribucionesSS =
                $ContribSSjubilacionsipa + $INSSJP +
                $ContribSScontribtareadif + $FNE +
                $ContribSSANSSAL + $asignacionfamiliar;
            //Contribucion Renatea
            if($trabajadorAgrario){
                $contribucionrenatea = $rem3*0.01500;
            }
            //Aporte Seguridad Social ANSSAL
            if($rem8<$minimoANSSAL){
                $AporteSSANSSAL = (($rem4*0.03000)+($rem4*0.01500*$cantidadAdherentes)+($AporteOSaporteadicionalos))*$alicuotaMinimoANSSAL;
            }else{
                $AporteSSANSSAL = (($rem4*0.03000)+($rem4*0.01500*$cantidadAdherentes)+($AporteOSaporteadicionalos))*$alicuotaANSSAL;
            }
            //Aporte Seguridad Social Total
            $AporteSStotal = $AporteSSjubilacionsipa+$ley19032+$AporteSSaporteadicional+$AporteSSANSSAL;
            //Contribucion OS contribucion os

            if($rem8<$minimoANSSAL) {
                $ContribucionesOScontribucionos = $rem8 * 0.054;
            }else{
                $ContribucionesOScontribucionos = $rem8 * $alicuotaContribucionObraSocial;
            }
            //Contribucion OS ANSSAL
            if($rem8<$minimoANSSAL){
                $ContribucionesOSANSSAL = $ContribucionesOScontribucionadicionalos*$alicuotaMinimoANSSAL;
            }else{
                $ContribucionesOSANSSAL = $ContribucionesOScontribucionadicionalos*$alicuotaANSSAL;
            }
            //Contribucion OS TOTAL
            $ContribucionesOStotal = $ContribucionesOScontribucionos+$ContribucionesOScontribucionadicionalos-$ContribucionesOSANSSAL;
            //Aporte OS aporte os
            if($rem4<$minimoANSSAL){
                $AporteOSaporteos = $rem4 * 0.027;
            }else{
                $AporteOSaporteos = $rem4 * $alicuotaAporteObraSocial;
            }
            //Aporte OS ANSSAL

            if($rem4<$minimoANSSAL){
                $AporteOSANSSAL = $AporteOSaporteadicionalos * $alicuotaMinimoANSSAL;
            }else{
                $AporteOSANSSAL = $AporteOSaporteadicionalos * $alicuotaANSSAL;
            }
            //Aporte OS Adicional Adherente
            if($cantidadAdherentes>0){
                if($rem1<$minimoANSSAL){
                    $AporteOSadicionaladherente = $rem1*0.1;
                }else{
                    $AporteOSadicionaladherente = $rem1*0.15;
                }
            }
            //Aporte OS Total
            $AporteOStotal =
                $AporteOSaporteos+
                $AporteOSaporteadicionalos-
                $AporteOSANSSAL+
                $AporteOSadicionaladherente;
            //ART
            if($coberturaart){
                $ARTart = (($rem9 *$impcli['Impcli']['alicuotaart']) / 100) + $impcli['Impcli']['fijoart'];
            }
            //Seguro de Vida obligatorio Seguro de Vida
            If($segurodevida){
                $SeguroDeVidaObligatorio = $impcli['Impcli']['segurodevida'];
            }
            $miempleado['horasDias']=$horasDias;
            $miempleado['jornada']=$jornada;
            $miempleado['redondeo']=$redondeo;
            $miempleado['cantidadadherente']=$cantidadAdherentes;
            $miempleado['sueldo']=$sueldo;
            $miempleado['diadelgremiotrabajado']=$diadelgremiotrabajado;
            $miempleado['diadelgremio']=$diadelgremio;
            $miempleado['adicionales']=$adicionales;
            $miempleado['embargos']=$embargos;
            $miempleado['horasextras']=$horasextras;
            $miempleado['importehorasextras']=$importehorasextras;
            $miempleado['SAC']=$SAC;
            $miempleado['vacaciones']=$vacaciones;
            
            $miempleado['situacionrevista1']=$situacionrevista1;            
            $miempleado['situacionrevista2']=$situacionrevista2;            
            $miempleado['situacionrevista3']=$situacionrevista3;            
            $miempleado['diasituacionrevista1']=$diasituacionrevista1;            
            $miempleado['diasituacionrevista2']=$diasituacionrevista2;            
            $miempleado['diasituacionrevista3']=$diasituacionrevista3;            
            
            $miempleado['premios']=$premios;
            $miempleado['maternidad']=$maternidad;
            $miempleado['conceptosnorem']=$conceptosnorem;
            $miempleado['remtotal']=$remtotal;
            $miempleado['rem1']=$rem1;
            $miempleado['rem2']=$rem2;
            $miempleado['importeADetraer']=$importeADetraer;            
            $miempleado['titleRem10']=$titleRem10;
            $miempleado['rem3']=$rem3;
            $miempleado['titlerem4']=$titlerem4;
            $miempleado['rem4']=$rem4;
            $miempleado['rem5']=$rem1;
            $miempleado['rem6']=0;//algunos regimenes especiales deben darle valor a este campo
            $miempleado['rem7']=0;//pero de todas formas no se usa para calcular naada
            $miempleado['rem8']=$rem4;
            $miempleado['rem9']=$rem9;
            $miempleado['rem10']=$rem10;
            $miempleado['seguridadsocialaporteadicional']=$seguridadsocialaporteadicional;
            $miempleado['seguridadsocialcontribtareadif']=$seguridadsocialcontribtareadif;
            $miempleado['obrasocialaporteadicional']=$obrasocialaporteadicional;
            $miempleado['obrasocialcontribucionadicional']=$obrasocialcontribucionadicional;
            $miempleado['minimoOS']=$minimoOS;

            $miempleado['ContribSSjubilacionsipa']=$ContribSSjubilacionsipa;
            $miempleado['INSSJP']=$INSSJP;
            $miempleado['ContribSScontribtareadif']=$ContribSScontribtareadif;
            $miempleado['FNE']=$FNE;
            $miempleado['ContribSSANSSAL']=$ContribSSANSSAL;
            $miempleado['asignacionfamiliar']=$asignacionfamiliar;
            $miempleado['totalContribucionesSS']=$totalContribucionesSS;
            $miempleado['contribucionrenatea']=$contribucionrenatea;
            $miempleado['aporterenatea']=$aporterenatea;
            $miempleado['AporteSSjubilacionsipa']=$AporteSSjubilacionsipa;
            $miempleado['ley19032']=$ley19032;
            $miempleado['AporteSSaporteadicional']=$AporteSSaporteadicional;
            $miempleado['AporteSSANSSAL']=$AporteSSANSSAL;
            $miempleado['AporteSStotal']=$AporteSStotal;
            $miempleado['ContribucionesOScontribucionos']=$ContribucionesOScontribucionos;
            $miempleado['ContribucionesOScontribucionadicionalos']=$ContribucionesOScontribucionadicionalos;
            $miempleado['ContribucionesOSANSSAL']=$ContribucionesOSANSSAL;
            $miempleado['ContribucionesOStotal']=$ContribucionesOStotal;
            $miempleado['AporteOSaporteos']=$AporteOSaporteos;
            $miempleado['AporteOSaporteadicionalos']=$AporteOSaporteadicionalos;
            $miempleado['AporteOSANSSAL']=$AporteOSANSSAL;
            $miempleado['AporteOSadicionaladherente']=$AporteOSadicionaladherente;
            $miempleado['AporteOStotal']=$AporteOStotal;
            $miempleado['ARTart']=$ARTart;
            $miempleado['SeguroDeVidaObligatorio']=$SeguroDeVidaObligatorio;
            $miempleado['SeguroDeVidaObligatorio']=$SeguroDeVidaObligatorio;
            $miempleado['TotalRemCD'] = $TotalRemCD;
            $miempleado['codigoafip'] = $codigoafip;
            $empleadoid = $empleado['id'];
            if(!isset($empleadoDatos[$empleadoid]))
                $empleadoDatos[$empleadoid]=array();
            $empleadoDatos[$empleadoid] = $miempleado;
        }
        unset($miempleado);
        $styleForTotalTd = "
                    color: white;
                    background-color: grey;
                    ";
        $cantidadDePaginas = ceil(count($empleadoDatos)/5);
        echo $this->Form->input('cantidadDePaginas',['value'=>$cantidadDePaginas,'type'=>'hidden']);
        for ($index = 0; $index < $cantidadDePaginas; $index++) {
            echo '<button class="btn_realizar_tarea" style="width:40px; margin:2px" onclick="mostrarEmpleado('.$index.');">'.$index.'</button>';
        }
        ?>
        
        <table id="tblDatosAIngresar" class="tbl_border" cellspacing="0">
            <thead>
                <tr>
                    <td class="dontHide"></td>
                    <td class="dontHide">Legajo</td>
                    <?php
                        foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                            echo '<td class="dontHide">'.$empleado['legajo']."</td>";
                        }
                        ?>
                    <td  class="dontHide" style="width:111px;border: 0px;"></td>
                </tr>
                <tr>
                    <td  class="dontHide"></td>
                    <td class="dontHide">Apellido y Nombre</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo '<td class="dontHide">'.$empleado['nombre']."</td>";
                    }
                    ?>
                    <td  class="dontHide"></td>
                </tr><!--1-->
            </thead>
            <tbody>
                <tr>
                    <td  class="dontHide" rowspan="16" style=" vertical-align:middle!important;">
                        <div >
                            Datos
                        </div>
                    </td>
                    <td class="dontHide">CUIL</td>
                    <?php
                        foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                                echo "<td>".$empleado['cuit']."</td>";
                        }
                    ?>
                    <td  class="dontHide"></td>
                </tr>
                <tr>
                    <td class="dontHide">OS del Pers de Dirección</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo $empleadoDatos[$empleadoid]['osdelpersdedireccion']?'SI':'NO';
                        echo "</td>";
                    }
                    ?>
                    <td  class="dontHide"></td>
                </tr>
                <tr>
                    <td class="dontHide">Cobertura ART</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo $empleadoDatos[$empleadoid]['coberturaart']?'SI':'NO';
                        echo "</td>";
                    }
                    ?>
                    <td  class="dontHide"></td>
                </tr>
                <tr>
                    <td class="dontHide">Seguro de Vida</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo $empleadoDatos[$empleadoid]['segurodevida']?'SI':'NO';
                        echo "</td>";
                    }
                    ?>
                    <td  class="dontHide"></td>
                </tr>
                <tr>
                    <td class="dontHide">Código AFIP</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        $reduccion= 0;
                        switch ($empleadoDatos[$empleado['id']]['codigoafip']){
                            case 0:
                                $reduccion= 0;
                                break;
                            case 1:
                                $reduccion= 50;
                                break;
                            case 2:
                                $reduccion= 75;
                                break;
                            case 3:
                                $reduccion= 25;
                                break;
                           case 4:
                                $reduccion= 100;
                                break;
                        }
                        echo "<td> reduccion del ".$reduccion."%</td>";
                    }
                    ?>
                    <td  class="dontHide"></td>
                </tr>
                <tr>
                    <td>Día de inicio</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>" . $empleado['fechaingreso'] . "</td>";
                    }
                    ?>
                    <td ></td>
                </tr>
                <tr>
                    <td class="dontHide">Días Trabajados u Horas</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo $empleadoDatos[$empleadoid]['horasDias']."</td>";
                    }
                    ?>
                    <td  class="dontHide"></td>
                </tr>
                <tr>
                    <td class="dontHide">Sueldo</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        //si no trabajo el dia del gremio hay que sumarlo al basico, si lo trabajo al adicional
                        $empleadoid = $empleado['id'];
                        $sueldoAMostrar = $empleadoDatos[$empleadoid]['sueldo'];
                        if($empleadoDatos[$empleadoid]['diadelgremiotrabajado']==0){
                            $sueldoAMostrar += $empleadoDatos[$empleadoid]['diadelgremio'];
                        }
                        echo "<td>";
                        echo number_format($sueldoAMostrar, 2, ",", ".")."</td>";
                    }
                    ?>
                    <td  class="dontHide"></td>
                </tr>
                <tr>
                    <td class="dontHide">Adicionales</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        $adicionalesAMostrar = $empleadoDatos[$empleadoid]['adicionales'];
                        if($empleadoDatos[$empleadoid]['diadelgremiotrabajado']==1){
                            $adicionalesAMostrar += $empleadoDatos[$empleadoid]['diadelgremio'];
                        }
                        echo number_format($adicionalesAMostrar, 2, ",", ".")."</td>";
                    }
                    ?>
                    <td  class="dontHide"></td>
                </tr><!--10-->
                <tr>
                    <td>Cantidad de Horas Extra</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['horasextras'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td  class="dontHide"></td>
                </tr>
                <tr>
                    <td class="dontHide">Importe Horas extras</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['importehorasextras'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td  class="dontHide"></td>
                </tr>
                <tr>
                    <td class="dontHide">SAC</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['SAC'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td  class="dontHide"></td>
                </tr>
                <tr>
                    <td class="dontHide">Vacaciones</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['vacaciones'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td  class="dontHide"></td>
                </tr>
                <tr>
                    <td class="dontHide">Premios</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['premios'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td  class="dontHide" rowspan="2">
                        Totales
                    </td>
                </tr>
                <tr>
                    <td class="dontHide">Maternidad
                    </td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo $empleadoDatos[$empleadoid]['maternidad']?'SI':'NO'."</td>";
                    }
                    ?>
                    <td class="dontHide"></td>
                </tr>
                <tr style="border-spacing: 5em 10em">
                    <td class="dontHide">Conceptos no Remunerativos</td>
                    <?php
                    $totalConceptosNoRemunerativos=0;
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['conceptosnorem'], 2, ",", ".")."</td>";
                        $totalConceptosNoRemunerativos+=$empleadoDatos[$empleadoid]['conceptosnorem'];
                    }
                    echo '<td  class="dontHide">';
                    echo number_format($totalConceptosNoRemunerativos, 2, ",", ".")."</td>";
                    ?>                    
                </tr>
                <tr>
                    <td  class="dontHide" rowspan="11" style=" vertical-align:middle!important;">
                        <div >
                            Remunerativos
                        </div>
                    </td>
                    <td class="dontHide">Rem. Total</td>
                    <?php
                    $redondeoTotal=0;
                    $remtotal=0;$rem1=0;$rem2=0;$rem3=0;$rem4=0;
                    $rem5=0;$rem6=0;$rem7=0;$rem8=0;$rem9=0;$rem10=0;
                    //Seguridad Social
                    $seguridadsocialaporteadicional=0;
                    $seguridadsocialcontribtareadif=0;
                    //Obra Social
                    $obrasocialaporteadicional=0;
                    $obrasocialcontribucionadicional=0;
                    //Contrib SS
                    $ContribSSjubilacionsipa=0;$INSSJP=0;
                    $ContribSScontribtareadif=0;$FNE=0;$ContribSSANSSAL=0;$asignacionfamiliar=0;
                    $totalContribucionesSS=0;
                    //RENATEA
                    $contribucionrenatea = 0;
                    $trabajadorAgrario = false;
                    $aporterenatea = 0;
                    //Aportes SS
                    $AporteSSjubilacionsipa=0;
                    $ley19032 = 0;
                    $AporteSSaporteadicional=0;
                    $AporteSSANSSAL=0;;
                    $AporteSStotal=0;
                    //Contribucion OS
                    $ContribucionesOScontribucionos=0;
                    $ContribucionesOScontribucionadicionalos=0;
                    $ContribucionesOSANSSAL=0;
                    $ContribucionesOStotal=0;
                    //Aportes OS
                    $AporteOSaporteos=0;
                    $AporteOSaporteadicionalos=0;
                    $AporteOSANSSAL=0;
                    $AporteOSadicionaladherente = 0;
                    $AporteOStotal = 0;

                    $SeguroDeVidaObligatorio = 0;
                    $ARTart = 0;
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        $empleadoid = $empleado['id'];
                        //en este primer loop vamos a calcular todos los siguientes totales
                        $redondeoTotal+=$empleadoDatos[$empleadoid]['redondeo'];
                        $remtotal+=$empleadoDatos[$empleadoid]['remtotal'];
                        $rem1+=$empleadoDatos[$empleadoid]['rem1'];
                        $rem2+=$empleadoDatos[$empleadoid]['rem2'];
                        $titleRem10=$empleadoDatos[$empleadoid]['titleRem10'];
                        $rem3+=$empleadoDatos[$empleadoid]['rem3'];
                        $rem4+=$empleadoDatos[$empleadoid]['rem4'];
                        $rem5+=$empleadoDatos[$empleadoid]['rem5'];
                        $rem6+=$empleadoDatos[$empleadoid]['rem6'];
                        $rem7+=$empleadoDatos[$empleadoid]['rem7'];
                        $rem8+=$empleadoDatos[$empleadoid]['rem8'];
                        $rem9+=$empleadoDatos[$empleadoid]['rem9'];
                        $rem10+=$empleadoDatos[$empleadoid]['rem10'];
                        $seguridadsocialaporteadicional+=$empleadoDatos[$empleadoid]['seguridadsocialaporteadicional'];
                        $seguridadsocialcontribtareadif+=$empleadoDatos[$empleadoid]['seguridadsocialcontribtareadif'];
                        $obrasocialaporteadicional+=$empleadoDatos[$empleadoid]['obrasocialaporteadicional'];
                        $obrasocialcontribucionadicional+=$empleadoDatos[$empleadoid]['obrasocialcontribucionadicional'];
                        $ContribSSjubilacionsipa+=$empleadoDatos[$empleadoid]['ContribSSjubilacionsipa'];
                        $INSSJP+=$empleadoDatos[$empleadoid]['INSSJP'];
                        $ContribSScontribtareadif+=$empleadoDatos[$empleadoid]['ContribSScontribtareadif'];
                        $FNE+=$empleadoDatos[$empleadoid]['FNE'];
                        $ContribSSANSSAL+=$empleadoDatos[$empleadoid]['ContribSSANSSAL'];
                        $asignacionfamiliar+=$empleadoDatos[$empleadoid]['asignacionfamiliar'];
                        $totalContribucionesSS+=$empleadoDatos[$empleadoid]['totalContribucionesSS'];
                        //RENATEA
                        $contribucionrenatea+=$empleadoDatos[$empleadoid]['contribucionrenatea'];
                        $aporterenatea+=$empleadoDatos[$empleadoid]['aporterenatea'];
                        //Aportes SS
                        $AporteSSjubilacionsipa+=$empleadoDatos[$empleadoid]['AporteSSjubilacionsipa'];
                        $ley19032 +=$empleadoDatos[$empleadoid]['ley19032'];
                        $AporteSSaporteadicional+=$empleadoDatos[$empleadoid]['AporteSSaporteadicional'];
                        $AporteSSANSSAL+=$empleadoDatos[$empleadoid]['AporteSSANSSAL'];
                        $AporteSStotal+=$empleadoDatos[$empleadoid]['AporteSStotal'];
                        //Contribucion OS
                        $ContribucionesOScontribucionos+=$empleadoDatos[$empleadoid]['ContribucionesOScontribucionos'];
                        $ContribucionesOScontribucionadicionalos+=$empleadoDatos[$empleadoid]['ContribucionesOScontribucionadicionalos'];
                        $ContribucionesOSANSSAL+=$empleadoDatos[$empleadoid]['ContribucionesOSANSSAL'];
                        $ContribucionesOStotal+=$empleadoDatos[$empleadoid]['ContribucionesOStotal'];
                        //Aportes OS
                        $AporteOSaporteos+=$empleadoDatos[$empleadoid]['AporteOSaporteos'];
                        $AporteOSaporteadicionalos+=$empleadoDatos[$empleadoid]['AporteOSaporteadicionalos'];
                        $AporteOSANSSAL+=$empleadoDatos[$empleadoid]['AporteOSANSSAL'];
                        $AporteOSadicionaladherente +=$empleadoDatos[$empleadoid]['AporteOSadicionaladherente'];
                        $AporteOStotal +=$empleadoDatos[$empleadoid]['AporteOStotal'];
                        $SeguroDeVidaObligatorio+=$empleadoDatos[$empleadoid]['SeguroDeVidaObligatorio'];
                        $ARTart+=$empleadoDatos[$empleadoid]['ARTart'];
                        echo "<td>";
                        echo $empleadoDatos[$empleadoid]['remtotal']."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php
                        echo number_format($remtotal, 2, ",", ".");
                        echo $this->Form->input(
                            'RemuneracionTotal',
                            array(
                                'type'=>'hidden',
                                'id'=> 'RemuneracionTotal',
                                'value'=>$remtotal
                            )
                        );
                        echo $this->Form->input(
                            'redondeoTotal',
                            array(
                                'type'=>'hidden',
                                'id'=> 'redondeoTotal',
                                'value'=>$redondeoTotal
                            )
                        );
                        ?></td>
                </tr>
                <tr>
                    <td class="dontHide">Rem. 1 (SIPA)</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['rem1'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($rem1, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td class="dontHide">Rem. 2 (Cont. SIPA + INSSJP)</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo '<td >';
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['rem2'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($rem2, 2, ",", "."); ?></td>
                </tr><!--20-->
                <tr>
                    <td class="dontHide">Rem. 3 (Cont. FNE + RENATRE)</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['rem3'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($rem3, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td class="dontHide">Rem. 4 (Ap. OS + FSR o ANSSAL)</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo '<td title="'.$empleadoDatos[$empleadoid]['titlerem4'].'">';
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['rem4'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($rem4, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td class="dontHide">Rem. 5 (Ap. INSSJP</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['rem5'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($rem5, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td class="dontHide">Rem. 6</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['rem6'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($rem6, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td class="dontHide">Rem. 7</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['rem7'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($rem7, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td class="dontHide">Rem. 8 (Cont. OS)</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['rem8'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($rem8, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td class="dontHide">Rem. 9 (ART)</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['rem9'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($rem9, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td class="dontHide">Rem. 10 </td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        $empleadoid = $empleado['id'];
                        echo '<td title="'.$empleadoDatos[$empleadoid]['titleRem10'].'">';
                        echo number_format($empleadoDatos[$empleadoid]['rem10'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($rem10, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td class="dontHide" rowspan="2" style=" vertical-align:middle!important;">
                        <div >
                            Seguridad Social
                        </div>
                    </td>
                    <td class="dontHide">Aporte Adicional</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['seguridadsocialaporteadicional'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($seguridadsocialaporteadicional, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td class="dontHide">Contrib Tarea Dif</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['seguridadsocialcontribtareadif'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($seguridadsocialcontribtareadif, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td class="dontHide" rowspan="4" style=" vertical-align:middle!important;">
                        <div >
                            Obra Social
                        </div>
                    </td>
                    <td class="dontHide">Nombre de la OS</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        echo $empleado['obrasocial']."</td>";
                    }
                    ?>
                    <td class="dontHide" ></td>
                </tr><!--30-->
                <tr>
                    <td class="dontHide">Cantidad de adherentes</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo $empleadoDatos[$empleadoid]['cantidadadherente']."</td>";
                    }
                    ?>
                    <td class="dontHide" ></td>
                </tr>
                <tr>
                    <td class="dontHide">Aporte Adicional</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['obrasocialaporteadicional'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($obrasocialaporteadicional, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td class="dontHide">Contribución Adicional</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['obrasocialcontribucionadicional'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($obrasocialcontribucionadicional, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td  class="dontHide" rowspan="7" style=" vertical-align:middle!important;">
                        <div >
                            Contrib SS
                        </div>
                    </td>
                    <td  class="dontHide">Jubilación (SIPA)</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['ContribSSjubilacionsipa'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($ContribSSjubilacionsipa, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td class="dontHide">INSSJP</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['INSSJP'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo $INSSJP; ?></td>
                </tr>
                <tr>
                    <td class="dontHide">Contrib Tarea Dif</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['ContribSScontribtareadif'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($ContribSScontribtareadif, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td class="dontHide">FNE</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['FNE'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($FNE, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td class="dontHide">ANSSAL</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['ContribSSANSSAL'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($ContribSSANSSAL, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td class="dontHide">Asig Fliares</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['asignacionfamiliar'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($asignacionfamiliar, 2, ",", "."); ?></td>
                </tr>
                <tr style="<?php echo  $styleForTotalTd; ?>">
                    <td class="dontHide">Total Contribuciones SS</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['totalContribucionesSS'], 2, ",", ".")."</td>";
                        $empleadoDatos[$empleadoid]['totalAPagar']+=$empleadoDatos[$empleadoid]['totalContribucionesSS'];
                    }
                    ?>
                    <td class="dontHide">
                        <?php echo number_format($totalContribucionesSS, 2, ",", ".");
                        echo $this->Form->input(
                            'apagar351ContribucionesSegSocial',
                            array(
                                'type'=>'hidden',
                                'id'=> 'apagar351ContribucionesSegSocial',
                                'value'=>number_format($totalContribucionesSS, 2, ".", "")
                            )
                        );
                        ?>
                    </td>
                </tr>
                <tr style="<?php echo  $styleForTotalTd; ?>">
                    <td class="dontHide" rowspan="2" style=" vertical-align:middle!important;">
                        <div >
                            RENATEA
                        </div>
                    </td>
                    <td class="dontHide" >Contribución</td>
                    <?php
                    $totalembargos=0;
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['contribucionrenatea'], 2, ",", ".")."</td>";
                        $totalembargos += $empleadoDatos[$empleadoid]['embargos'];
                        $empleadoDatos[$empleadoid]['totalAPagar']+=$empleadoDatos[$empleadoid]['contribucionrenatea'];
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($contribucionrenatea, 2, ",", ".");
                        echo $this->Form->input(
                            'apagar360ContribucionRENATEA',
                            array(
                                'id'=>'apagar360ContribucionRENATEA',
                                'type'=>'hidden',
                                'value'=>$contribucionrenatea
                            )
                        );
                        //Aca vamos a poner el Input de embargos
                        echo $this->Form->input(
                            'apagarEmbargos',
                            array(
                                'id'=>'apagarEmbargos',
                                'type'=>'hidden',
                                'value'=>$totalembargos
                            )
                        );
                        ?></td>
                </tr>
                <tr style="<?php echo  $styleForTotalTd; ?>">
                    <td class="dontHide">Aporte</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['aporterenatea'], 2, ",", ".")."</td>";
                        $empleadoDatos[$empleadoid]['totalAPagar']+=$empleadoDatos[$empleadoid]['aporterenatea'];
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($aporterenatea, 2, ",", ".");
                        echo $this->Form->input(
                            'apagar935RENATEA',
                            array(
                                'id'=>'apagar935RENATEA',
                                'type'=>'hidden',
                                'value'=>number_format($aporterenatea, 2, ".", "")
                            )
                        );?></td>
                </tr>
                <tr>
                    <td  class="dontHide" rowspan="5" style=" vertical-align:middle!important;">
                        <div>
                            Aportes SS
                        </div>
                    </td>
                    <td class="dontHide">Jubilac (SIPA)</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['AporteSSjubilacionsipa'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($AporteSSjubilacionsipa, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td class="dontHide">Ley 19.032</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['ley19032'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($ley19032, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td class="dontHide">Aporte Adicional</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['AporteSSaporteadicional'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($AporteSSaporteadicional, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td class="dontHide">ANSSAL</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['AporteSSANSSAL'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($AporteSSANSSAL, 2, ",", "."); ?></td>
                </tr>
                <tr style="<?php echo  $styleForTotalTd; ?>">
                    <td class="dontHide">Total Aportes SS</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['AporteSStotal'], 2, ",", ".")."</td>";
                        $empleadoDatos[$empleadoid]['totalAPagar']+=$empleadoDatos[$empleadoid]['AporteSStotal'];
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($AporteSStotal, 2, ",", ".");
                        echo $this->Form->input(
                            'apagar301EmpleadorAportesSegSocial',
                            array(
                                'id'=>'apagar301EmpleadorAportesSegSocial',
                                'type'=>'hidden',
                                'value'=>number_format($AporteSStotal, 2, ".", "")
                            )
                        );?></td>
                </tr>
                <tr>
                    <td  class="dontHide"rowspan="4" style=" vertical-align:middle!important;">
                        <div >
                            Contribuciones OS
                        </div>
                    </td>
                    <td class="dontHide">Contribucion OS</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['ContribucionesOScontribucionos'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($ContribucionesOScontribucionos, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td class="dontHide">Contribucion Adicional OS</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['ContribucionesOScontribucionadicionalos'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide">
                        <?php
                        echo number_format($ContribucionesOScontribucionadicionalos, 2, ",", ".");

                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="dontHide">ANSAAL</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['ContribucionesOSANSSAL'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($ContribucionesOSANSSAL, 2, ",", "."); ?></td>
                </tr>
                <tr style="<?php echo  $styleForTotalTd; ?>">
                    <td class="dontHide">Total Contribuciones OS</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['ContribucionesOStotal'], 2, ",", ".")."</td>";
                        $empleadoDatos[$empleadoid]['totalAPagar']+=$empleadoDatos[$empleadoid]['ContribucionesOStotal'];
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($ContribucionesOStotal, 2, ",", ".");
                        echo $this->Form->input(
                            'apagar352ContribucionesObraSocial',
                            array(
                                'id'=>'apagar352ContribucionesObraSocial',
                                'type'=>'hidden',
                                'value'=>number_format($ContribucionesOStotal, 2, ".", "")
                            )
                        );?></td>
                </tr>
                <tr>
                    <td class="dontHide" rowspan="5" style=" vertical-align:middle!important;">
                        <div >
                            Aportes OS
                        </div>
                    </td>
                    <td class="dontHide">Aporte OS</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['AporteOSaporteos'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($AporteOSaporteos, 2, ",", ".");?></td>
                </tr>
                <tr>
                    <td class="dontHide">Aporte Adicional OS</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['AporteOSaporteadicionalos'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($AporteOSaporteadicionalos, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td class="dontHide">ANSSAL</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['AporteOSANSSAL'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($AporteOSANSSAL, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td class="dontHide">Adicional Adherente</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['AporteOSadicionaladherente'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($AporteOSadicionaladherente, 2, ",", "."); ?></td>
                </tr>
                <tr style="<?php echo  $styleForTotalTd; ?>">
                    <td class="dontHide">Total Aporte OS</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['AporteOStotal'], 2, ",", ".")."</td>";
                        $empleadoDatos[$empleadoid]['totalAPagar']+=$empleadoDatos[$empleadoid]['AporteOStotal'];
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($AporteOStotal, 2, ",", ".");
                        echo $this->Form->input(
                            'apagar302AportesObrasSociales',
                            array(
                                'id'=>'apagar302AportesObrasSociales',
                                'type'=>'hidden',
                                'value'=>number_format($AporteOStotal, 2, ".", "")
                            )
                        );?></td>
                </tr>
                <tr style="<?php echo  $styleForTotalTd; ?>">
                    <td class="dontHide" style=" vertical-align:middle!important;">
                        <div >
                            ART
                        </div>
                    </td>
                    <td class="dontHide">ART</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['ARTart'], 2, ",", ".")."</td>";
                        $empleadoDatos[$empleadoid]['totalAPagar']+=$empleadoDatos[$empleadoid]['ARTart'];
                    }
                    ?>
                    <td class="dontHide"><?php echo number_format($ARTart, 2, ",", ".");
                        echo $this->Form->input(
                            'apagar312AsegRiesgodeTrabajoL24557',
                            array(
                                'id'=>'apagar312AsegRiesgodeTrabajoL24557',
                                'type'=>'hidden',
                                'value'=>number_format($ARTart, 2, ".", "")
                            )
                        );?></td>
                </tr>
                <tr style="<?php echo  $styleForTotalTd; ?>">
                    <td class="dontHide"style=" vertical-align:middle!important;">
                        <div >
                            Seguro de Vida Oblig.
                        </div>
                    </td>
                    <td class="dontHide">Seguro de Vida Obligatorio</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['SeguroDeVidaObligatorio'], 2, ",", ".")."</td>";
                        $empleadoDatos[$empleadoid]['totalAPagar']+=$empleadoDatos[$empleadoid]['SeguroDeVidaObligatorio'];
                    }
                    ?>
                    <td class="dontHide"><?php
                        echo number_format($SeguroDeVidaObligatorio, 2, ",", ".");
                        echo $this->Form->input(
                            'apagar28SegurodeVidaColectivo',
                            array(
                                'id'=>'apagar28SegurodeVidaColectivo',
                                'type'=>'hidden',
                                'value'=>number_format($SeguroDeVidaObligatorio, 2, ".", "")
                            )
                        ); ?></td>
                </tr>
                <tr style="<?php echo  $styleForTotalTd; ?>">
                    <td class="dontHide" style=" vertical-align:middle!important;">
                        <div >
                            Totales
                        </div>
                    </td>
                    <td class="dontHide">A Pagar</td>
                    <?php
                    $totalSUSSAPAGAR=0;
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format( $empleadoDatos[$empleadoid]['totalAPagar'], 2, ",", ".")."</td>";
                        $totalSUSSAPAGAR+= $empleadoDatos[$empleadoid]['totalAPagar'];
                    }
                    ?>
                    <td class="dontHide"><?php
                        echo number_format($totalSUSSAPAGAR, 2, ",", ".");
                       ?></td>
                </tr>
            </tbody>
	</table>
    </div>
    <div>
        <h2>Txt Exportacion SUSS</h2>
        <a id="aExportarEmpleados" href="#" class="buttonImpcli" style="margin-right: 8px;width: initial;"
               onclick="downloadInnerHtml('<?php echo $impcli["Cliente"]['nombre']."-".$periodo; ?>SUSS.txt','divEmpleados','text/html')">
                Descargar TXT SUSS
            </a>
        <div class="index" style="overflow-x: auto;" id="divEmpleados" ><?php
            foreach ($impcli['Cliente']['Empleado'] as $empleado) {           
                $empleadoid = $empleado['id'];
                //Debugger::dump($empleado);
                $lineaEmpleado = "";
                //1 CUIL 1 - 11
                $lineaEmpleado .= str_pad($empleado['cuit'], 11, " ", STR_PAD_LEFT);
                //2 nombre 12 - 30
                $lineaEmpleado .= str_pad($empleado['nombre'], 30, " ", STR_PAD_RIGHT);
                //3conyugue  42 - 1
                $lineaEmpleado .= ($empleado['conyugue']==1)?"1":"0";
                //4cantidad hijos  43 - 2
                $lineaEmpleado .= str_pad($empleado['hijos'], 2, "0", STR_PAD_LEFT);
                //5Codigo situacion  45 - 2
                $lineaEmpleado .= str_pad($empleado['codigosituacion'], 2, "0", STR_PAD_LEFT);
                //6Codigo condicion  47 - 2
                $lineaEmpleado .= str_pad($empleado['codigocondicion'], 2, "0", STR_PAD_LEFT);
                //7Codigo actividad  49 - 3
                $lineaEmpleado .= str_pad($empleado['codigoactividad'], 3, "0", STR_PAD_LEFT);
                //8Codigo de zona  52 - 2
                $lineaEmpleado .= str_pad($empleado['codigozona'], 2, "0", STR_PAD_LEFT);
                //9Porcentaje aporte adicional SS  54 - 5
                $lineaEmpleado .= str_pad(number_format(0, 2, ",", ""), 5, " ", STR_PAD_LEFT);
                //10codigo de modalidad de contratacion  59 - 3
                $lineaEmpleado .= str_pad($empleado['codigomodalidadcontratacion'], 3, "0", STR_PAD_LEFT);
                //11codigo de obra social 62 - 6               
                if(isset($empleado['Obrassociale']['codigo'])){
                    $lineaEmpleado .= str_pad($empleado['Obrassociale']['codigo'], 6, "0", STR_PAD_LEFT);
                }else{
                    $lineaEmpleado .= str_pad(0, 6, "0", STR_PAD_LEFT);
                }
                //12cantidad adherentes 68 - 2
                $lineaEmpleado .= str_pad($empleado['adherente'], 2, "0", STR_PAD_LEFT);
                //13remuneracion total 70 - 12
                $lineaEmpleado .= str_pad(number_format($empleadoDatos[$empleadoid]['remtotal'], 2, ",", ""), 12, " ", STR_PAD_LEFT);
                //14remuneracion imponible 1 82 - 12
                $lineaEmpleado .= str_pad(number_format($empleadoDatos[$empleadoid]['rem1'], 2, ",", ""), 12, " ", STR_PAD_LEFT);
                //15Asignaciones Familiares Pagadas 94 - 9
                $lineaEmpleado .= str_pad(number_format(0, 2, ",", ""), 9, " ", STR_PAD_LEFT);
                //16Importe aporte voluntario 103 - 9 
                /*Aportes voluntarios: se elimina el campo a partir de 03-2009 aún cuando desde 11-
                    2008 no deberían haberse informado aportes voluntarios en este campo.*/
                $lineaEmpleado .= str_pad(number_format(0, 2, ",", ""), 9, " ", STR_PAD_LEFT);
                //17Importe adicional OS 112 - 9
                $lineaEmpleado .= str_pad(number_format($empleadoDatos[$empleadoid]['obrasocialcontribucionadicional'], 2, ",", ""), 9, " ", STR_PAD_LEFT);
                //18Importe Excedentes Aporte SS 121 - 9
                $lineaEmpleado .= str_pad(number_format($empleadoDatos[$empleadoid]['ley19032'], 2, ",", ""), 9, " ", STR_PAD_LEFT);
                //19Importe Excedentes Aporte OS 130 - 9
                $lineaEmpleado .= str_pad(number_format($empleadoDatos[$empleadoid]['obrasocialaporteadicional'], 2, ",", ""), 9, " ", STR_PAD_LEFT);
                //20Provincia Localidad 139 - 50
                if(isset($empleado['Domicilio'])){
                    $lineaEmpleado .= str_pad($empleado['Domicilio']['Localidade']['Partido']['nombre']." - ".$empleado['Domicilio']['Localidade']['nombre'] , 50, " ", STR_PAD_RIGHT);                
                }else{
                    $lineaEmpleado .= str_pad(" ", 50, " ", STR_PAD_RIGHT);                
                }
                //21Remuneracion Imponible 2 189 - 12
                $lineaEmpleado .= str_pad(number_format($empleadoDatos[$empleadoid]['rem2'], 2, ",", "") , 12, " ", STR_PAD_LEFT);
                //22Remuneracion Imponible 3 201 - 12
                $lineaEmpleado .= str_pad(number_format($empleadoDatos[$empleadoid]['rem3'], 2, ",", "") , 12, " ", STR_PAD_LEFT);
                //23Remuneracion Imponible 4 213 - 12
                $lineaEmpleado .= str_pad(number_format($empleadoDatos[$empleadoid]['rem4'], 2, ",", "") , 12, " ", STR_PAD_LEFT);
                //24Codigo siniestrado 225 - 2
                $lineaEmpleado .= str_pad($empleado['codigosiniestrado'], 2, " ", STR_PAD_LEFT);
                /*Completar*/
                //25marca de Corresponde Reduccion 227 - 1
                $lineaEmpleado .= str_pad(1, 1, " ", STR_PAD_LEFT);
                /*Completar*/
                //26Capital de recomposicion de LRT 228 - 9
                $lineaEmpleado .= str_pad(number_format(0, 2, ",", ""), 9, " ", STR_PAD_LEFT);
                 /*Completar*/
                //27Tipo de empresa 237 - 1
                $lineaEmpleado .= str_pad(1, 1, " ", STR_PAD_LEFT);
                 /*Completar*/
                //28Aporte adicional de obra social 238 - 9
                $lineaEmpleado .= str_pad(number_format($empleadoDatos[$empleadoid]['obrasocialaporteadicional'], 2, ",", ""), 9, " ", STR_PAD_LEFT);
                /*Completar*/
                //29Regimen 247 - 1
                $lineaEmpleado .= str_pad(0, 1, " ", STR_PAD_LEFT);
                /*Completar*/
                //30Situacion de Revista 1 248 - 2
                $lineaEmpleado .= str_pad($empleadoDatos[$empleadoid]['situacionrevista1'], 2, "0", STR_PAD_LEFT);
                /*Situacion Revista se carga en las liquidaciones y si no existen se pone el standar*/
                //31Dia Inicio de Revista 1 250 - 2
                $lineaEmpleado .= str_pad($empleadoDatos[$empleadoid]['diasituacionrevista1'], 2, "0", STR_PAD_LEFT);
                /*Completar*/
                //32Situacion de Revista 2 252 - 2
                $lineaEmpleado .= str_pad($empleadoDatos[$empleadoid]['situacionrevista2'], 2, "0", STR_PAD_LEFT);
                /*Completar*/
                //33Dia Inicio de Revista 2 254 - 2
                $lineaEmpleado .= str_pad($empleadoDatos[$empleadoid]['diasituacionrevista2'], 2, "0", STR_PAD_LEFT);
                /*Completar*/
                //34Situacion de Revista 3 256 - 2
                $lineaEmpleado .= str_pad($empleadoDatos[$empleadoid]['situacionrevista3'], 2, "0", STR_PAD_LEFT);
                /*Completar*/
                //35Dia Inicio de Revista 3 258 - 2
                $lineaEmpleado .= str_pad($empleadoDatos[$empleadoid]['diasituacionrevista3'], 2, "0", STR_PAD_LEFT);
                //36Sueldo + Adicionales 260 - 12
                $sueldoAMostrar = $empleadoDatos[$empleadoid]['sueldo'];
                if($empleadoDatos[$empleadoid]['diadelgremiotrabajado']==0){
                    $sueldoAMostrar += $empleadoDatos[$empleadoid]['diadelgremio'];
                }
                $adicionalesAMostrar = $empleadoDatos[$empleadoid]['adicionales'];
                if($empleadoDatos[$empleadoid]['diadelgremiotrabajado']==1){
                    $adicionalesAMostrar += $empleadoDatos[$empleadoid]['diadelgremio'];
                }
                $sueldoMasAdicional = $sueldoAMostrar/*+$adicionalesAMostrar*/;
                $lineaEmpleado .= str_pad(number_format($sueldoMasAdicional, 2, ",", ""), 12, " ", STR_PAD_LEFT);
                //37SAC 272 - 12
                $lineaEmpleado .= str_pad(number_format($empleadoDatos[$empleadoid]['SAC'], 2, ",", ""), 12, " ", STR_PAD_LEFT);
                //38Horas Extras 284 - 12
                $lineaEmpleado .= str_pad(number_format($empleadoDatos[$empleadoid]['importehorasextras'], 2, ",", ""), 12, " ", STR_PAD_LEFT);
                /*Completar*/
                //39Zona desfavorable 296 - 12
                $lineaEmpleado .= str_pad(number_format(0, 2, ",", ""), 12, " ", STR_PAD_LEFT);
                //40Vacaciones 308 - 12
                $lineaEmpleado .= str_pad(number_format($empleadoDatos[$empleadoid]['vacaciones'], 2, ",", ""), 12, " ", STR_PAD_LEFT);
                //41Cantidad de dias trabajados 320 - 9
                $diasTrabajados = 0;
                if($empleado['Cargo']['formapago']=='dia'){
                    $diasTrabajados = $empleadoDatos[$empleadoid]['horasDias'];
                }                
                $lineaEmpleado .= str_pad(number_format($diasTrabajados, 2, ",", ""), 9, " ", STR_PAD_LEFT);                
                //42Remuneracion Imponible 5 329 - 12
                $lineaEmpleado .= str_pad(number_format($empleadoDatos[$empleadoid]['rem5'], 2, ",", ""), 12, " ", STR_PAD_LEFT);
                /*Completar*/
                //43Trabajador Convenicionado 341 - 1
                $lineaEmpleado .= str_pad("T", 1, " ", STR_PAD_LEFT);
                //44Remuneracion Imponible 6 342 - 12
                $lineaEmpleado .= str_pad(number_format($empleadoDatos[$empleadoid]['rem6'], 2, ",", ""), 12, " ", STR_PAD_LEFT);
                /*Completar*/
                //45Tipo de operacion 354 - 1
                $lineaEmpleado .= str_pad(0, 1, " ", STR_PAD_LEFT);
                //46Adicionales 355 - 12
                $lineaEmpleado .= str_pad(number_format($empleadoDatos[$empleadoid]['adicionales'], 2, ",", ""), 12, " ", STR_PAD_LEFT);
                //47Premios 367 - 12
                $lineaEmpleado .= str_pad(number_format($empleadoDatos[$empleadoid]['premios'], 2, ",", ""), 12, " ", STR_PAD_LEFT);
                //48Rm.Dec 788/05 - Rem Impon. 8 379 - 12
                $lineaEmpleado .= str_pad(number_format($empleadoDatos[$empleadoid]['rem8'], 2, ",", ""), 12, " ", STR_PAD_LEFT);
                //49Remuneracion Imponible 7 391 - 12
                $lineaEmpleado .= str_pad(number_format($empleadoDatos[$empleadoid]['rem7'], 2, ",", ""), 12, " ", STR_PAD_LEFT);
                //50Cantidad de Horas Extras 403 - 3
                $lineaEmpleado .= str_pad($empleadoDatos[$empleadoid]['horasextras'], 3, "0", STR_PAD_LEFT);
                //51Conceptos no remunerativos 406 - 12
                $lineaEmpleado .= str_pad(number_format($empleadoDatos[$empleadoid]['conceptosnorem'], 2, ",", ""), 12, " ", STR_PAD_LEFT);
                /*Completar*/
                //52Maternidad 418 - 12
                $lineaEmpleado .= str_pad(number_format(0, 2, ",", ""), 12, " ", STR_PAD_LEFT);
                /*Completar*/
                //53Rectificacion de remuneracion 430 - 9
                $lineaEmpleado .= str_pad(number_format(0, 2, ",", ""), 9, " ", STR_PAD_LEFT);
                //54Remuneracion Imponible 9 439 - 12
                $lineaEmpleado .= str_pad(number_format($empleadoDatos[$empleadoid]['rem9'], 2, ",", ""), 12, " ", STR_PAD_LEFT);
                //54Contribucion tarea diferencial (%) 451 - 9
                $lineaEmpleado .= str_pad(number_format($empleadoDatos[$empleadoid]['seguridadsocialcontribtareadif'], 2, ",", ""), 9, " ", STR_PAD_LEFT);
                //55Horas trabajadas 460 - 3
                $horasTrabajados = 0;
                if($empleado['Cargo']['formapago']=='hora'){
                    $horasTrabajados = $empleadoDatos[$empleadoid]['horasDias'];
                }
                $lineaEmpleado .= str_pad($horasTrabajados, 3, "0", STR_PAD_LEFT);
                //57Seguro Colectivo de vida Obligatorio 463 - 1
                $lineaEmpleado .= str_pad(($empleadoDatos[$empleadoid]['SeguroDeVidaObligatorio']>0)?"T":0, 1, " ", STR_PAD_LEFT);
                //58Importe a Detraer 464 - 12
                $lineaEmpleado .= str_pad(number_format($empleadoDatos[$empleadoid]['importeADetraer'], 2, ",", ""), 12, " ", STR_PAD_LEFT);

                //$lineaEmpleado = date('Y', strtotime($empleado['CUIT'])) . date('m', strtotime($empleado['fecha'])) . date('d', strtotime($empleado['fecha']));
                
                echo $lineaEmpleado."</br>";
            }
        ?></div>
    </div>
    <?php
    if(!$impuestosactivos['contabiliza']){ ?>
        <div id="divContenedorContabilidad" style="margin-top:10px">  </div>
        <?php
    }else{ ?>
    <div id="divContenedorContabilidad" style="margin-top:10px;width: 100%">
        <div class="index_pdt" id="AsientoAutomaticoDevengamiento931">
            <b>Asiento de Devengamiento</b>
            <?php
            echo $this->Form->input('cuentasdeSUSSAportesSindicatos',[
                'id'=> "cuentasdeSUSSAportesSindicatos",
                'value'=>json_encode($aportesSindicatos),
                'type'=>'hidden'
            ]);
            echo $this->Form->input('cuentasdeSUSSContribucionesSindicatos',[
                'id'=> "cuentasdeSUSSContribucionesSindicatos",
                'value'=>json_encode($contribucionesSindicatos),
                'type'=>'hidden'
            ]);
            $Asientoid=0;
            $movId=[];
            if(isset($impcli['Asiento'])) {
                if (count($impcli['Asiento']) > 0) {
                    foreach ($impcli['Asiento'] as $asiento){
                        if($asiento['tipoasiento']=='impuestos'){
                            $Asientoid = $asiento['id'];
                            foreach ($asiento['Movimiento'] as $mimovimiento) {
                                $movId[$mimovimiento['Cuentascliente']['cuenta_id']] = $mimovimiento['id'];
                            }
                        }
                    }
                }
            }
            //ahora vamos a reccorer las cuentas relacionadas al IVA y las vamos a cargar en un formulario de Asiento nuevo
            echo $this->Form->create('Asiento',['class'=>'formTareaCarga formAsiento','controller'=>'asientos','action'=>'add']);
            echo $this->Form->input('Asiento.0.id',['value'=>$Asientoid]);
            $d = new DateTime( '01-'.$periodo );
            echo $this->Form->input('Asiento.0.fecha',array(
                'class'=>'datepicker',
                'type'=>'text',
                'label'=>array(
                    'text'=>"Fecha:",
                ),
                'readonly','readonly',
                'value'=>$d->format( 't-m-Y' ),
//                'div' => false,
                'style'=> 'width:82px'
            ));
            echo $this->Form->input('Asiento.0.nombre',['readonly'=>'readonly','value'=>"Devengamiento SUSS" ,'style'=>'width:250px']);
            echo $this->Form->input('Asiento.0.descripcion',['readonly'=>'readonly','value'=>"Automatico",'style'=>'width:250px']);
            echo $this->Form->input('Asiento.0.cliente_id',['value'=>$impcli['Cliente']['id'],'type'=>'hidden']);
            echo $this->Form->input('Asiento.0.impcli_id',['value'=>$impcli['Impcli']['id'],'type'=>'hidden']);
            echo $this->Form->input('Asiento.0.periodo',['value'=>$periodo,'type'=>'hidden']);
            echo $this->Form->input('Asiento.0.tipoasiento',['value'=>'impuestos','type'=>'hidden'])."</br>";
            $i=0;
            //los asientos estandares son los que van a darle forma a la parte estatica del asiento
            foreach ($impcli['Impuesto']['Asientoestandare'] as $asientoestandarasuss) {
                if(!isset($movId[$asientoestandarasuss['cuenta_id']])){
                    $movId[$asientoestandarasuss['cuenta_id']]=0;
                }
                $cuentaclienteid=0;
                $cuentaclientenombre=$asientoestandarasuss['Cuenta']['nombre'];
                foreach ($impcli['Cliente']['Cuentascliente'] as $cuentaclientaSUSS){
                    if($cuentaclientaSUSS['cuenta_id']==$asientoestandarasuss['cuenta_id']){
                        $cuentaclienteid=$cuentaclientaSUSS['id'];
                        $cuentaclientenombre=$cuentaclientaSUSS['nombre'];
                        break;
                    }
                }
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.id',['value'=>$movId[$asientoestandarasuss['cuenta_id']],]);
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.fecha', array(
                    'readonly'=>'readonly',
                    'class'=>'datepicker',
                    'type'=>'hidden',
                    'label'=>array(
                        'text'=>"Vencimiento:",
                        "style"=>"display:inline",
                    ),
                    'readonly','readonly',
                    'value'=>date('d-m-Y'),
                    'div' => false,
                    'style'=> 'height:9px;display:inline'
                ));
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuentascliente_id',['readonly'=>'readonly','type'=>'hidden','value'=>$cuentaclienteid]);
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuenta_id',['readonly'=>'readonly','type'=>'hidden','orden'=>$i,'value'=>$asientoestandarasuss['cuenta_id'],'id'=>'cuenta'.$asientoestandarasuss['cuenta_id']]);
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.numero',['label'=>($i!=0)?false:'Numero','readonly'=>'readonly','value'=>$asientoestandarasuss['Cuenta']['numero'],'style'=>'width:82px']);
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.nombre',['label'=>($i!=0)?false:'Cuenta','readonly'=>'readonly','value'=>$cuentaclientenombre,'type'=>'text','style'=>'width:250px']);
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe',[
                    'label'=>($i!=0)?false:'Debe',
                    'value'=>0,
                    'class'=>"inputDebe "
                ]);
                echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber',[
                        'label'=>($i!=0)?false:'Haber',
                        'value'=>0,
                        'class'=>"inputHaber "
                    ]) . "</br>";
                $i++;
            }
            
            //las contribuciones se calculan aca, y los aportes son todos del recibo de sueldo por que se restan de ahi,
            // a diferencia de las contribuciones que cada una tiene su "a pagar"
            
            //hay aportes y contribuciones sindicales que dan de alta ciertas cuentas relacionadas a los sindicatos que paga el cliente
            //estas cuentas deben ser mostradas en el asiento solo si estan activadas en una cuentacliente


            foreach ($aportesSindicatos as $cuentaaportesindicato) {
                if(!isset($movId[$cuentaaportesindicato])){
                    $movId[$cuentaaportesindicato]=0;
                }
                $cuentaclienteid=0;
                $cuentaclientenombre="dar de alta cuenta de sindicato faltante";//$asientoestandarasuss['Cuenta']['nombre'];
                $cuentaclientenumero="dar de alta cuenta de sindicato faltante";//$asientoestandarasuss['Cuenta']['nombre'];
                $mostrar=false;
                foreach ($impcli['Cliente']['Cuentascliente'] as $cuentaclientaSUSS){
                    //si esta activada la cuenta de este sindicato entonces muestro
                    if($cuentaclientaSUSS['cuenta_id']==$cuentaaportesindicato){
                        $cuentaclienteid=$cuentaclientaSUSS['id'];
                        $cuentaclientenombre=$cuentaclientaSUSS['nombre'];
                        $cuentaclientenumero=$cuentaclientaSUSS['Cuenta']['numero'];
                        $mostrar=true;
                        break;
                    }
                }
                if($mostrar) {
                    /*Aca vamos a recorrer los sindicatos que hemos buscado para preguntar
                                                                  si calculamos el valor de el que va a mostrarse*/
                    $valor = 0;
                    foreach ($sindicatos['sindicato'] as $nomSindicato => $sindicato) {
                        $nombre = "Ap. ".$nomSindicato . " a Pagar";
                        if(trim($nombre)==trim($cuentaclientenombre)){
                            $valor = $sindicato;
                        }else{
                        }
                    }
                    if(isset($sindicatosextra['sindicatoextra'])) {
                        foreach ($sindicatosextra['sindicatoextra'] as $nomSindicato => $sindicato) {
                            $nombre = "Ap. " . $nomSindicato . " a Pagar";
                            if (trim($nombre) == trim($cuentaclientenombre)) {
                                $valor = $sindicato;
                            }
                        }
                    }
                    /*Estos son aportes y se sacan del recibo de sueldo por lo que aca deberia preguntar
                    si hay un calculo para empleados que tengan el nombre del sindicato en la cuenta y ahi mustro ese valor
                    por que la cuenta se da de alta con ese valro*/
                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.id', ['value' => $movId[$cuentaaportesindicato],]);
                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.fecha', array(
                        'readonly' => 'readonly',
                        'class' => 'datepicker',
                        'type' => 'hidden',
                        'label' => array(
                            'text' => "Vencimiento:",
                            "style" => "display:inline",
                        ),
                        'readonly', 'readonly',
                        'value' => date('d-m-Y'),
                        'div' => false,
                        'style' => 'height:9px;display:inline'
                    ));
                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.cuentascliente_id', ['readonly' => 'readonly', 'type' => 'hidden', 'value' => $cuentaclienteid]);
                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.cuenta_id', ['readonly' => 'readonly', 'type' => 'hidden', 'orden' => $i, 'value' => $cuentaaportesindicato, 'id' => 'cuenta' . $cuentaaportesindicato]);
                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.numero', ['label'=>($i!=0)?false:'Numero','readonly' => 'readonly', 'value' => $cuentaclientenumero, 'style' => 'width:82px']);
                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.nombre', ['label'=>($i!=0)?false:'Cuenta','readonly' => 'readonly', 'value' => $cuentaclientenombre, 'type' => 'text', 'style' => 'width:250px']);
                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.debe', [
                        'label'=>($i!=0)?false:'Debe',
                        'value' => 0,
                        'class'=>"inputDebe "
                    ]);
                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.haber', [
                            'label'=>($i!=0)?false:'Haber',
                            'value' => $valor,
                            'class'=>"inputHaber "
                        ]). "</br>";
                    $i++;
                }
            }
//            foreach ($contribucionesSindicatos as $cuentacontribucionindicato) {
//                if(!isset($movId[$cuentacontribucionindicato])){
//                    $movId[$cuentacontribucionindicato]=0;
//                }
//                $cuentaclienteid=0;
//                $cuentaclientenombre="dar de alta cuenta de sindicato faltante";//$asientoestandarasuss['Cuenta']['nombre'];
//                $cuentaclientenumero="dar de alta cuenta de sindicato faltante";//$asientoestandarasuss['Cuenta']['nombre'];
//                $mostrar=false;
//                foreach ($impcli['Cliente']['Cuentascliente'] as $cuentaclientaSUSS){
//                    if($cuentaclientaSUSS['cuenta_id']==$cuentacontribucionindicato){
//                        $cuentaclienteid=$cuentaclientaSUSS['id'];
//                        $cuentaclientenombre=$cuentaclientaSUSS['nombre'];
//                        $cuentaclientenumero=$cuentaclientaSUSS['Cuenta']['numero'];
//                        $mostrar=true;
//                        break;
//                    }
//                }
//                if($mostrar) {
//                    $debe =0;
//                    $haber =0;
//                    foreach ($contibuciones as $nomContribucion => $monto) {
//                        $nombre1 = "Contribucion-".$nomContribucion."-";
//                        //$nombre2 = "Contribucion-".$nomContribucion."-A Pagar";
//                        $nombre3 = "Cont.Seg. De Vida Oblig. Mercantil-".$nomContribucion."-Creo que esto no va";
//                        //$nombre4 = "Cont.Seg. De Vida Oblig. Mercantil-".$nomContribucion."-A Pagar";
//                        if($nombre1==$cuentaclientenombre||$nombre3==$cuentaclientenombre){
//                            $debe = $monto;
//                        }
////                      elseif ($nombre2==$cuentaclientenombre||$nombre4==$cuentaclientenombre){
////                            $haber = $monto;
////                        }else{
//////                            echo $cuentaclientenombre."=".$nombre1."</br>";
////                        }
//                    }
//                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.id', ['value' => $movId[$cuentacontribucionindicato],]);
//                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.fecha', array(
//                        'readonly' => 'readonly',
//                        'class' => 'datepicker',
//                        'type' => 'hidden',
//                        'label' => array(
//                            'text' => "Vencimiento:",
//                            "style" => "display:inline",
//                        ),
//                        'readonly', 'readonly',
//                        'value' => date('d-m-Y'),
//                        'div' => false,
//                        'style' => 'height:9px;display:inline'
//                    ));
//                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.cuentascliente_id', ['readonly' => 'readonly', 'type' => 'hidden', 'value' => $cuentaclienteid]);
//                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.cuenta_id', ['readonly' => 'readonly', 'type' => 'hidden', 'orden' => $i, 'value' => $cuentacontribucionindicato, 'id' => 'cuenta' . $cuentacontribucionindicato]);
//                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.numero', ['label'=>($i!=0)?false:'Numero','readonly' => 'readonly', 'value' => $cuentaclientenumero, 'style' => 'width:82px']);
//                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.nombre', ['label'=>($i!=0)?false:'Nombre','readonly' => 'readonly', 'value' => $cuentaclientenombre, 'type' => 'text', 'style' => 'width:250px']) ;
//                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.debe', ['label'=>($i!=0)?false:'Debe','readonly' => 'readonly', 'value' => $debe,]);
//                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.haber', ['label'=>($i!=0)?false:'Haber','readonly' => 'readonly', 'value' => $haber,]). "</br>";
//                    $i++;
//                }
//            }
//            foreach ($contribucionesSindicatosPasivo as $cuentacontribucionindicatopasivo) {
//                if(!isset($movId[$cuentacontribucionindicatopasivo])){
//                    $movId[$cuentacontribucionindicatopasivo]=0;
//                }
//                $cuentaclienteid=0;
//                $cuentaclientenombre="dar de alta cuenta de sindicato faltante";//$asientoestandarasuss['Cuenta']['nombre'];
//                $cuentaclientenumero="dar de alta cuenta de sindicato faltante";//$asientoestandarasuss['Cuenta']['nombre'];
//                $mostrar=false;
//                foreach ($impcli['Cliente']['Cuentascliente'] as $cuentaclientaSUSS){
//                    if($cuentaclientaSUSS['cuenta_id']==$cuentacontribucionindicatopasivo){
//                        $cuentaclienteid=$cuentaclientaSUSS['id'];
//                        $cuentaclientenombre=$cuentaclientaSUSS['nombre'];
//                        $cuentaclientenumero=$cuentaclientaSUSS['Cuenta']['numero'];
//                        $mostrar=true;
//                        break;
//                    }
//                }
//                if($mostrar) {
//                    /*Aca vamos a recorrer los sindicatos que hemos buscado para preguntar
//                                                si calculamos el valor de el que va a mostrarse*/
//                    $valor = 0;
//                    foreach ($sindicatos['sindicato'] as $nomSindicato => $sindicato) {
//                        $nombre = "Contribucion-".$nomSindicato."-";
//                        if($nombre==$cuentaclientenombre){
//                            $valor = $sindicato;
//                        }
//                    }
//                    if(isset($sindicatosextra['sindicatoextra'])) {
//                        foreach ($sindicatosextra['sindicatoextra'] as $nomSindicato => $sindicato) {
//                            $nombre = "Contribucion-" . $nomSindicato . "-";
//                            if ($nombre == $cuentaclientenombre) {
//                                $valor = $sindicato;
//                            }
//                            //En estas cuentas el Contribucion-Seguro de vida obligatorio comercio- paga 2/3 de este monto (que es 1/3) por
//                            //eso lo vamos a multiplicar por 2
//                            if ($nombre == "Contribucion-Seguro de vida obligatorio comercio-") {
//                                $valor = $valor * 2;
//                            }
//                        }
//                    }
//                    /*Estos son aportes y se sacan del recibo de sueldo por lo que aca deberia preguntar
//                    si hay un calculo para empleados que tengan el nombre del sindicato en la cuenta y ahi mustro ese valor
//                    por que la cuenta se da de alta con ese valro*/
//                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.id', ['value' => $movId[$cuentaaportesindicato],]);
//                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.fecha', array(
//                        'readonly' => 'readonly',
//                        'class' => 'datepicker',
//                        'type' => 'hidden',
//                        'label' => array(
//                            'text' => "Vencimiento:",
//                            "style" => "display:inline",
//                        ),
//                        'readonly', 'readonly',
//                        'value' => date('d-m-Y'),
//                        'div' => false,
//                        'style' => 'height:9px;display:inline'
//                    ));
//                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.cuentascliente_id', ['readonly' => 'readonly', 'type' => 'hidden', 'value' => $cuentaclienteid]);
//                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.cuenta_id', ['readonly' => 'readonly', 'type' => 'hidden', 'orden' => $i, 'value' => $cuentaaportesindicato, 'id' => 'cuenta' . $cuentaaportesindicato]);
//                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.numero', ['label'=>($i!=0)?false:'Numero','readonly' => 'readonly', 'value' => $cuentaclientenumero, 'style' => 'width:82px']);
//                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.nombre', ['label'=>($i!=0)?false:'Cuenta','readonly' => 'readonly', 'value' => $cuentaclientenombre, 'type' => 'text', 'style' => 'width:250px']);
//                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.debe', ['label'=>($i!=0)?false:'Debe','readonly' => 'readonly', 'value' => 0,]);
//                    echo $this->Form->input('Asiento.0.Movimiento.' . $i . '.haber', ['label'=>($i!=0)?false:'Haber','readonly' => 'readonly', 'value' => $valor,]). "</br>";
//                    $i++;
//                }
//            }
//            echo $this->Form->submit('Contabilizar');
            echo $this->Form->end();
            $totalDebe=0;
            $totalHaber=0;
            echo $this->Form->label('','Total ',[
                'style'=>"display: -webkit-inline-box;width:355px;"
            ]);
            ?>
            <div style="width:98px;">
                <?php
                echo $this->Form->label('lblTotalDebe',
                    "$".number_format($totalDebe, 2, ".", ""),
                    [
                        'id'=>'lblTotalDebe',
                        'style'=>"display: inline;float:right"
                    ]
                );
                ?>
            </div>
            <div style="width:124px;">
                <?php
                echo $this->Form->label('lblTotalHaber',
                    "$".number_format($totalHaber, 2, ".", ""),
                    [
                        'id'=>'lblTotalHaber',
                        'style'=>"display: inline;float:right"
                    ]
                );
                ?>
            </div>
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
    </div>
    <?php } ?>
</div>
<?php
    //Aca vamos a definir la tabla de la que se va a sacar los valores del Dcto814
    $tablaDcto814 = [];
    $tablaDcto814['2018'] = [];
    $tablaDcto814['2019'] = [];
    $tablaDcto814['2020'] = [];
    $tablaDcto814['2021'] = [];
    $tablaDcto814['2022'] = [];
    
    function inicializarArrayProvincias(&$arrayAnio){
        $arrayAnio['60 Salta']=0;
        $arrayAnio['61 Resto de Salta']=0;
    }
    inicializarArrayProvincias($tablaDcto814['2018']);
    inicializarArrayProvincias($tablaDcto814['2019']);
    inicializarArrayProvincias($tablaDcto814['2020']);
    inicializarArrayProvincias($tablaDcto814['2021']);
    inicializarArrayProvincias($tablaDcto814['2022']);
    $tablaDcto814['2018']['60 Salta']=9.70;
    $tablaDcto814['2018']['61 Resto de Salta']=10.75;
    $tablaDcto814['2018']['1 Ciudad Autonoma de Buenos Aires']=0;
    $tablaDcto814['2018']['2 Gran Buenos Aires']=0;
    $tablaDcto814['2018']['3 Tercer Cinturon del GBA']=0.85;
    $tablaDcto814['2018']['4 Resto de Buenos Aires']=1.9;
    $tablaDcto814['2018']['5 Buenos Aires Patagones']=2.95;
    $tablaDcto814['2018']['6 Buenos Aitres - Carmen de Patagones']=4;
    $tablaDcto814['2018']['7 Cordoba - Cruz del Eje']=5.05;
    $tablaDcto814['2018']['8 Bs. As. - Villarino']=2.95;
    $tablaDcto814['2018']['9 Gran Catamarca']=7.6;
    $tablaDcto814['2018']['10 Resto de Catamarca']=8.65;
    $tablaDcto814['2018']['11 Ciudad de Corrientes']=9.7;
    $tablaDcto814['2018']['12 Formosa - Ciudad de Formosa']=10.75;
    $tablaDcto814['2018']['13 Cordoba - Sobremonte']=7.6;
    $tablaDcto814['2018']['14 Resto de Chaco']=11.80;
    $tablaDcto814['2018']['15 Cordoba - Rio Seco']=7.6;
    $tablaDcto814['2018']['16 Cordoba - Tulumba']=7.6;
    $tablaDcto814['2018']['17 Cordoba - Minas']=5.05;
    $tablaDcto814['2018']['18 Cordoba - Pocho']=5.05;
    $tablaDcto814['2018']['19 Cordoba - San Alberto']=5.05;
    $tablaDcto814['2018']['20 Cordoba - San Javier']=5.05;
    $tablaDcto814['2018']['21 Cordoba']=1.9;
    $tablaDcto814['2018']['22 Resto de Cordoba']=2.95;
    $tablaDcto814['2018']['23 Corrientes - Esquina']=7.6;
    $tablaDcto814['2018']['24 Corrientes - Sauce']=7.6;
    $tablaDcto814['2018']['25 Corrientes - Curuzu Cuatia']=7.6;
    $tablaDcto814['2018']['26 Corrientes - Monte Caseros']=7.6;
    $tablaDcto814['2018']['27 Resto de Corrientes']=9.6;
    $tablaDcto814['2018']['28 Gran Resistencia']=9.70;
    $tablaDcto814['2018']['29 Chubut - Rauson Trelew']=7.6;
    $tablaDcto814['2018']['30 Resto de Chubut']=8.65;
    $tablaDcto814['2018']['31 Entre Rios - Federacion']=7.6;
    $tablaDcto814['2018']['32 Entre Rios - Feliciano']=7.6;
    $tablaDcto814['2018']['33 Entre Rios - Parana']=2.95;
    
    
    $tablaDcto814['2019']['60 Salta']=7.3;
    $tablaDcto814['2019']['61 Resto de Salta']=8.05;
    $tablaDcto814['2020']['60 Salta']=4.85;
    $tablaDcto814['2020']['61 Resto de Salta']=5.40;
    $tablaDcto814['2021']['60 Salta']=2.45;
    $tablaDcto814['2021']['61 Resto de Salta']=2.70;
    $tablaDcto814['2022']['60 Salta']=0;
    $tablaDcto814['2022']['61 Resto de Salta']=0;

?>
<!-- Popin Modal para edicion de ventas a utilizar por datatables-->
<div class="modal fade" id="myModalAddConceptosrestante" tabindex="-1" role="dialog">
    <div class="modal-dialog" style="width:90%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
<!--                    <span aria-hidden="true">&times;</span>-->
                </button>
                <h4 class="modal-title">Generar Dcto 814</h4>
            </div>
            <div class="modal-body">
                <div id="Div_form_Conceptosrestante" class="form" style="width: 94%;float: none; ">
                    <?php
                    echo $this->Form->create('Conceptosrestante',array(
                            'controller'=>'conceptosrestantes',
                            'id'=>'saveConceptosrestantesForm',
                            'action'=>'addajax',
                            'class'=>'formTareaCarga',
                        )
                    );
                    $conceptosrestanteId=0;
                    if(isset($conceptosrestante)){
                        $conceptosrestanteId = $conceptosrestante['Conceptosrestante']['id'];
                    }
                    //Suma de Remunerativos sujetos a Descuentos x empleado
                    //la 10 si la 10 es != de 0 sino la 8
                    $titleRemSujDesc = "Se usara la Remuneracion ";
                    $remSujetaADescuento = 0;
                    if($impcli['Impcli']['aplicaley27430']=='1'){
                        $titleRemSujDesc = "10 por que aplica ley 27430";
                        $remSujetaADescuento = $rem10;
                    }else{
                        $titleRemSujDesc = "8 por que no aplica ley 27430";
                        $remSujetaADescuento = $rem8;
                    }
                    $peanio = substr($periodo, 3)*1;
                    
                    echo $this->Form->input('id',array('default'=>$conceptosrestanteId,'type'=>'hidden'));
                    echo $this->Form->input('cliente_id',array('default'=>$impcli['Cliente']['id'],'type'=>'hidden'));
                    //Vamos a enviar la situacion del cliente para no recalcularla en el controlador cada ves que guardemos un concepto que resta                    
                    echo $this->Form->input('impclisid',array('type'=>'select','options'=>$impcliid,'style'=>'display:none','div'=>false,'label'=>false));
                    //aca tenemos que poner el impcliId de IVA
                    echo $this->Form->input('impcli_id',array(
                        'type' => 'hidden',
                        'label' => 'Impuesto',
                        'value' => $impuestosactivos['ivaId'],
                        'style' => 'width:150px;'));
                    echo $this->Form->input('partido_id',array('type'=>'hidden'));
                    echo $this->Form->input('localidade_id', ['type'=>'hidden']);
                    echo $this->Form->input('conceptostipo_id',array(
                        'type'=>'hidden',
                        'value'=>12/*Decreto 814*/,
                        ));
                    echo $this->Form->input('periodo',array('value'=>$periodo,'type'=>'hidden'));
                    echo $this->Form->input('concepto',array('type'=>'hidden'));
                    echo $this->Form->input('numerocomprobante',array('type'=>'hidden'));
                    echo $this->Form->input('comprobante_id', ['type'=>'hidden']);
                    echo $this->Form->input('puntosdeventa', array('type'=>'hidden'));
                    echo $this->Form->input('numerofactura', array('type'=>'hidden'));
                    echo $this->Form->input('rectificativa', array('type'=>'hidden'));
                    echo $this->Form->input('razonsocial',array('type'=>'hidden'));
                    
                    $optionsAlicuota = [];
                    foreach ($tablaDcto814[$peanio] as $key => $value) {
                        $optionsAlicuota[$value." "] = $key." ".$value;
                    }   
                    echo $this->Form->input('alicuota', array(
                        'type'=>'select',
                        'options'=>$optionsAlicuota,
                    ));
                    echo $this->Form->input('baseimponible', array(
                        'type'=>'text',
                        'title'=>$titleRemSujDesc,
                        'value'=>$remSujetaADescuento
                    ));
                    
                    echo $this->Form->input('monto', array('type'=>'hidden'));
                    echo $this->Form->input('montoretenido', array(
                            'label'=> 'Monto Retenido',
                            'value'=>0
                        )
                    );
                    echo $this->Form->input('cuit',array('type'=>'hidden'));
                    echo $this->Form->input('fecha', array(
                            'class'=>'datepicker',
                            'type'=>'text',
                            'label'=>'Fecha',
                            'default'=>date('d-m-Y'),
                            'readonly'=>'readonly',
                            'required'=>true,
                            'style'=> 'width:80px;'
                        )
                    );
                    echo $this->Form->input('numerodespachoaduanero',array('type'=>'hidden'));
                    echo $this->Form->input('anticipo',array('type'=>'hidden'));
                    echo $this->Form->input('cbu',array('type'=>'hidden'));
                    echo $this->Form->input('tipocuenta',array('type'=>'hidden'));
                    echo $this->Form->input('tipomoneda',array('type'=>'hidden'));
                    echo $this->Form->input('agente',array('type'=>'hidden'));
                    echo $this->Form->input('enterecaudador',array('type'=>'hidden'));
                    echo $this->Form->input('regimen',array('type'=>'hidden'));
                    echo $this->Form->input('descripcion',array('type'=>'hidden'));
                    echo $this->Form->input('numeropadron',array('type'=>'hidden'));
                    echo $this->Form->input('ordendepago',array('type'=>'hidden'));
                    echo $this->Form->submit('+', array('type'=>'image',
                        'src' => $this->webroot.'img/add_view.png',
                        'class'=>'img_edit',
                        'title' => 'Agregar',
                        'style'=>'width:25px;height:25px;margin-top:8px'));                    
                    echo $this->Form->end();  ?>
                 
                </div>        
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
