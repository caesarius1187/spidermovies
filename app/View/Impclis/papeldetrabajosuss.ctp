<?php echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false));
echo $this->Html->script('jquery.table2excel',array('inline'=>false));
echo $this->Html->script('impclis/papeldetrabajosuss',array('inline'=>false));

echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
echo $this->Form->input('impcliidPDT',array('value'=>$impcliid,'type'=>'hidden'));
echo $this->Form->input('clinombre',array('value'=>$impcli['Cliente']['nombre'],'type'=>'hidden'));
echo $this->Form->input('impcliid',array('value'=>$impcliid,'type'=>'hidden'));
echo $this->Form->input('cliid',array('value'=>$impcli['Cliente']['id'],'type'=>'hidden'));?>
<div class="eventosclientes index">
    <div id="divLiquidarSUSS">
    </div>
	<div id="sheetCooperadoraAsistencial" class="index" style="overflow: auto; margin-bottom:10px;">
		<!--Esta es la tabla original y vamos a recorrer todos los empleados por cada una de las
		rows por que -->
        <b style="display: inline">Papel de Trabajo</b>
        <?php echo $this->Form->button('Imprimir',
            array('type' => 'button',
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
                $miempleado['adicionales'] = 0;
                $miempleado['embargos'] = 0;
                $miempleado['horasextras'] = 0;
                $miempleado['importehorasextras'] = 0;
                $miempleado['SAC'] = 0;
                $miempleado['vacaciones'] = 0;
                $miempleado['premios'] = 0;
                $miempleado['maternidad'] = 0;
                $miempleado['conceptosnorem'] = 0;
                $miempleado['remtotal'] = 0;
                $miempleado['rem1'] = 0;
                $miempleado['rem2'] = 0;
                $miempleado['rem3'] = 0;
                $miempleado['rem4'] = 0;
                $miempleado['rem5'] = 0;
                $miempleado['rem6'] = 0;
                $miempleado['rem7'] = 0;
                $miempleado['rem8'] = 0;
                $miempleado['rem9'] = 0;
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

            $horasDias=0;$jornada=0;$sueldo=0;
            $adicionales=0;
            $embargos=0;
            $horasextras=0;$importehorasextras=0;
            $SAC=0;$vacaciones=0;$premios=0;$maternidad=0;$conceptosnorem=0;
            $remtotal=0;$rem1=0;$rem2=0;$rem3=0;$rem4=0;
            $rem5=0;$rem6=0;$rem7=0;$rem8=0;$rem9=0;
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
//                        '127'/*Total Acuerdo Remunerativo*/,
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
                    array('27'/*Total Remunerativos C/D*/,'109'/*Total Remunerativos C/D*/), true )
                ){
                    $remtotal += $valorrecibo['valor'];
                }
                //Remuneracion 1
                if (
                in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('27'/*Total Remunerativos C/D*/), true )
                ){
                    $rem1 += $valorrecibo['valor'];
                }
                //Remuneracion 2
                if (
                in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('27'/*Total Remunerativos C/D*/), true )
                ){
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
            //Remuneracion 4 Segunda Parte
            $titlerem4="";
            if($AporteOSaporteadicionalos>0){
                $titlerem4="Aporte adicional > 0, entonces Rem 4 = Rem 1 ";
                $rem4 = $rem1;
            }else{
                $titlerem4="Aporte adicional < 0, ";
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
            if($codigoafip=='0'){
                $ContribSSjubilacionsipa+=$rem2*0.1017;
            }
            if($codigoafip=='1'){
                $ContribSSjubilacionsipa+=$rem2*0.1017*0.5;
            }
            if($codigoafip=='2'){
                $ContribSSjubilacionsipa+=$rem2*0.1017*0.75;
            }
            if($codigoafip=='3'){
                $ContribSSjubilacionsipa+=$rem2*0.1017*0.25;
            }
            //Jubilacion INSSJP
            if($codigoafip=='0'){
                $INSSJP+=$rem2*0.01500;
            }
            if($codigoafip=='1'){
                $INSSJP+=$rem2*0.01500*0.5;
            }
            if($codigoafip=='2'){
                $INSSJP+=$rem2*0.01500*0.75;
            }
            if($codigoafip=='3'){
                $INSSJP+=$rem2*0.01500*0.25;
            }
            //Contrib Tarea Dif
            $ContribSScontribtareadif = $rem1*($seguridadsocialcontribtareadif/100);

            //Jubilacion FNE
            if(!$trabajadorAgrario){
                if($codigoafip=='0'){
                    $FNE+=$rem1*0.0089;
                }
                if($codigoafip=='1'){
                    $FNE+=$rem1*0.0089*0.5;
                }
                if($codigoafip=='2'){
                    $FNE+=$rem1*0.0089*0.75;
                }
                if($codigoafip=='3'){
                    $FNE+=$rem1*0.0089*0.25;
                }
            }else{}
            //Contrib Seg Soc ANSSAL
            $minimoANSSAL = 2400;
            if($rem8<$minimoANSSAL){
                $ContribSSANSSAL+=($rem8*0.060+$ContribucionesOScontribucionadicionalos)*0.1;
            }else{
                $ContribSSANSSAL+=($rem8*0.060+$ContribucionesOScontribucionadicionalos)*0.15;
            }
            //Asignaciones Familiares
            if($codigoafip=='0'){
                $asignacionfamiliar+=$rem1*0.04440;
            }
            if($codigoafip=='1'){
                $asignacionfamiliar+=$rem1*0.04440*0.5;
            }
            if($codigoafip=='2'){
                $asignacionfamiliar+=$rem1*0.04440*0.75;
            }
            if($codigoafip=='3'){
                $asignacionfamiliar+=$rem1*0.04440*0.25;
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
                $AporteSSANSSAL = (($rem4*0.03000)+($rem4*0.01500*$cantidadAdherentes)+($AporteOSaporteadicionalos))*0.1;
            }else{
                $AporteSSANSSAL = (($rem4*0.03000)+($rem4*0.01500*$cantidadAdherentes)+($AporteOSaporteadicionalos))*0.15;
            }
            //Aporte Seguridad Social Total
            $AporteSStotal = $AporteSSjubilacionsipa+$ley19032+$AporteSSaporteadicional+$AporteSSANSSAL;
            //Contribucion OS contribucion os

            if($rem8<$minimoANSSAL) {
                $ContribucionesOScontribucionos = $rem8 * 0.054;
            }else{
                $ContribucionesOScontribucionos = $rem8 * 0.051;
            }
            //Contribucion OS ANSSAL
            if($rem8<$minimoANSSAL){
                $ContribucionesOSANSSAL = $ContribucionesOScontribucionadicionalos*0.1;
            }else{
                $ContribucionesOSANSSAL = $ContribucionesOScontribucionadicionalos*0.15;
            }
            //Contribucion OS TOTAL
            $ContribucionesOStotal = $ContribucionesOScontribucionos+$ContribucionesOScontribucionadicionalos-$ContribucionesOSANSSAL;
            //Aporte OS aporte os
            if($rem4<$minimoANSSAL){
                $AporteOSaporteos = $rem4 * 0.027;
            }else{
                $AporteOSaporteos = $rem4 * 0.0255;
            }
            //Aporte OS ANSSAL

            if($rem4<$minimoANSSAL){
                $AporteOSANSSAL = $AporteOSaporteadicionalos * 0.1;
            }else{
                $AporteOSANSSAL = $AporteOSaporteadicionalos * 0.15;
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
            $AporteOStotal = $AporteOSaporteos+$AporteOSaporteadicionalos-$AporteOSANSSAL+$AporteOSadicionaladherente;
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
            $miempleado['adicionales']=$adicionales;
            $miempleado['embargos']=$embargos;
            $miempleado['horasextras']=$horasextras;
            $miempleado['importehorasextras']=$importehorasextras;
            $miempleado['SAC']=$SAC;
            $miempleado['vacaciones']=$vacaciones;
            $miempleado['premios']=$premios;
            $miempleado['maternidad']=$maternidad;
            $miempleado['conceptosnorem']=$conceptosnorem;
            $miempleado['remtotal']=$remtotal;
            $miempleado['rem1']=$rem1;
            $miempleado['rem2']=$rem1;
            $miempleado['rem3']=$rem1;
            $miempleado['titlerem4']=$titlerem4;
            $miempleado['rem4']=$rem4;
            $miempleado['rem5']=$rem1;
            $miempleado['rem6']=$rem1;
            $miempleado['rem7']=$rem1;
            $miempleado['rem8']=$rem4;
            $miempleado['rem9']=$rem9;
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
        ?>
        <table id="tblDatosAIngresar" class="tbl_border" cellspacing="0">
            <thead>
                <tr>
                    <td></td>
                    <td>Legajo</td>
                    <?php
                        foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                            echo "<td>".$empleado['legajo']."</td>";
                        }
                        ?>
                    <td style="width:111px;border: 0px;"></td>
                </tr>
                <tr>
                    <td ></td>
                    <td>Apellido y Nombre</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>".$empleado['nombre']."</td>";
                    }
                    ?>
                    <td ></td>
                </tr><!--1-->
            </thead>
            <tbody>
                <tr>
                    <td rowspan="16" style=" vertical-align:middle!important;">
                        <div >
                            Datos
                        </div>
                    </td>
                    <td>CUIL</td>
                    <?php
                        foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                                echo "<td>".$empleado['cuit']."</td>";
                        }
                    ?>
                    <td ></td>
                </tr>
                <tr>
                    <td>OS del Pers de Dirección</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo $empleadoDatos[$empleadoid]['osdelpersdedireccion']?'SI':'NO';
                        echo "</td>";
                    }
                    ?>
                    <td ></td>
                </tr>
                <tr>
                    <td>Cobertura ART</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo $empleadoDatos[$empleadoid]['coberturaart']?'SI':'NO';
                        echo "</td>";
                    }
                    ?>
                    <td ></td>
                </tr>
                <tr>
                    <td>Seguro de Vida</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo $empleadoDatos[$empleadoid]['segurodevida']?'SI':'NO';
                        echo "</td>";
                    }
                    ?>
                    <td ></td>
                </tr>
                <tr>
                    <td>Código AFIP</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>".$empleadoDatos[$empleado['id']]['codigoafip']."</td>";
                    }
                    ?>
                    <td ></td>
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

                    <td>Días Trabajados u Horas</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo $empleadoDatos[$empleadoid]['horasDias']."</td>";
                    }
                    ?>
                    <td ></td>
                </tr>
                <tr>
                    <td>Sueldo</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['sueldo'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td ></td>
                </tr>
                <tr>
                    <td>Adicionales</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['adicionales'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td ></td>
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
                    <td ></td>
                </tr>
                <tr>
                    <td>Importe Horas extras</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['importehorasextras'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td ></td>
                </tr>
                <tr>
                    <td>SAC</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['SAC'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td ></td>
                </tr>
                <tr>
                    <td>Vacaciones</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['vacaciones'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td ></td>
                </tr>
                <tr>
                    <td>Premios</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['premios'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td rowspan="2">
                        Totales
                    </td>
                </tr>
                <tr>
                    <td>Maternidad
                    </td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo $empleadoDatos[$empleadoid]['maternidad']?'SI':'NO'."</td>";
                    }
                    ?>
                </tr>
                <tr style="border-spacing: 5em 10em">
                    <td>Conceptos no Remunerativos</td>
                    <?php
                    $totalConceptosNoRemunerativos=0;
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['conceptosnorem'], 2, ",", ".")."</td>";
                        $totalConceptosNoRemunerativos+=$empleadoDatos[$empleadoid]['conceptosnorem'];
                    }
                    echo "<td>";
                    echo number_format($totalConceptosNoRemunerativos, 2, ",", ".")."</td>";
                    ?>
                </tr>
                <tr>
                    <td rowspan="10" style=" vertical-align:middle!important;">
                        <div >
                            Remunerativos
                        </div>
                    </td>
                    <td>Rem. Total</td>
                    <?php
                    $redondeoTotal=0;
                    $remtotal=0;$rem1=0;$rem2=0;$rem3=0;$rem4=0;
                    $rem5=0;$rem6=0;$rem7=0;$rem8=0;$rem9=0;
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
                        $rem3+=$empleadoDatos[$empleadoid]['rem3'];
                        $rem4+=$empleadoDatos[$empleadoid]['rem4'];
                        $rem5+=$empleadoDatos[$empleadoid]['rem5'];
                        $rem6+=$empleadoDatos[$empleadoid]['rem6'];
                        $rem7+=$empleadoDatos[$empleadoid]['rem7'];
                        $rem8+=$empleadoDatos[$empleadoid]['rem8'];
                        $rem9+=$empleadoDatos[$empleadoid]['rem9'];
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
                    <td><?php
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
                    <td>Rem. 1 (SIPA)</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['rem1'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($rem1, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td>Rem. 2 (Cont. SIPA + INSSJP)</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['rem2'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($rem2, 2, ",", "."); ?></td>
                </tr><!--20-->
                <tr>
                    <td>Rem. 3 (Cont. FNE + RENATRE)</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['rem3'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($rem3, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td>Rem. 4 (Ap. OS + FSR o ANSSAL)</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo '<td title="'.$empleadoDatos[$empleadoid]['titlerem4'].'">';
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['rem4'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($rem4, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td>Rem. 5 (Ap. INSSJP</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['rem5'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($rem5, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td>Rem. 6</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['rem6'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($rem6, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td>Rem. 7</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['rem7'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($rem7, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td>Rem. 8 (Cont. OS)</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['rem8'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($rem8, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td>Rem. 9 (ART)</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['rem9'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($rem9, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td rowspan="2" style=" vertical-align:middle!important;">
                        <div >
                            Seguridad Social
                        </div>
                    </td>
                    <td>Aporte Adicional</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['seguridadsocialaporteadicional'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($seguridadsocialaporteadicional, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td>Contrib Tarea Dif</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['seguridadsocialcontribtareadif'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($seguridadsocialcontribtareadif, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td rowspan="4" style=" vertical-align:middle!important;">
                        <div >
                            Obra Social
                        </div>
                    </td>
                    <td>Nombre de la OS</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        echo $empleado['obrasocial']."</td>";
                    }
                    ?>
                    <td ></td>
                </tr><!--30-->
                <tr>
                    <td>Cantidad de adherentes</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo $empleadoDatos[$empleadoid]['cantidadadherente']."</td>";
                    }
                    ?>
                    <td ></td>
                </tr>
                <tr>
                    <td>Aporte Adicional</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['obrasocialaporteadicional'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($obrasocialaporteadicional, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td>Contribución Adicional</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['obrasocialcontribucionadicional'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($obrasocialcontribucionadicional, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td rowspan="7" style=" vertical-align:middle!important;">
                        <div >
                            Contrib SS
                        </div>
                    </td>
                    <td>Jubilación (SIPA)</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['ContribSSjubilacionsipa'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($ContribSSjubilacionsipa, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td>INSSJP</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['INSSJP'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo $INSSJP; ?></td>
                </tr>
                <tr>
                    <td>Contrib Tarea Dif</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['ContribSScontribtareadif'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($ContribSScontribtareadif, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td>FNE</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['FNE'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($FNE, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td>ANSSAL</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['ContribSSANSSAL'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($ContribSSANSSAL, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td>Asig Fliares</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['asignacionfamiliar'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($asignacionfamiliar, 2, ",", "."); ?></td>
                </tr>
                <tr style="<?php echo  $styleForTotalTd; ?>">
                    <td>Total Contribuciones SS</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['totalContribucionesSS'], 2, ",", ".")."</td>";
                        $empleadoDatos[$empleadoid]['totalAPagar']+=$empleadoDatos[$empleadoid]['totalContribucionesSS'];
                    }
                    ?>
                    <td>
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
                    <td rowspan="2" style=" vertical-align:middle!important;">
                        <div >
                            RENATEA
                        </div>
                    </td>
                    <td>Contribución</td>
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
                    <td><?php echo number_format($contribucionrenatea, 2, ",", ".");
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
                    <td>Aporte</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['aporterenatea'], 2, ",", ".")."</td>";
                        $empleadoDatos[$empleadoid]['totalAPagar']+=$empleadoDatos[$empleadoid]['aporterenatea'];
                    }
                    ?>
                    <td><?php echo number_format($aporterenatea, 2, ",", ".");
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
                    <td rowspan="5" style=" vertical-align:middle!important;">
                        <div>
                            Aportes SS
                        </div>
                    </td>
                    <td>Jubilac (SIPA)</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['AporteSSjubilacionsipa'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($AporteSSjubilacionsipa, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td>Ley 19.032</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['ley19032'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($ley19032, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td>Aporte Adicional</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['AporteSSaporteadicional'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($AporteSSaporteadicional, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td>ANSSAL</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['AporteSSANSSAL'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($AporteSSANSSAL, 2, ",", "."); ?></td>
                </tr>
                <tr style="<?php echo  $styleForTotalTd; ?>">
                    <td>Total Aportes SS</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['AporteSStotal'], 2, ",", ".")."</td>";
                        $empleadoDatos[$empleadoid]['totalAPagar']+=$empleadoDatos[$empleadoid]['AporteSStotal'];
                    }
                    ?>
                    <td><?php echo number_format($AporteSStotal, 2, ",", ".");
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
                    <td rowspan="4" style=" vertical-align:middle!important;">
                        <div >
                            Contribuciones OS
                        </div>
                    </td>
                    <td>Contribucion OS</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['ContribucionesOScontribucionos'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($ContribucionesOScontribucionos, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td>Contribucion Adicional OS</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['ContribucionesOScontribucionadicionalos'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td>
                        <?php
                        echo number_format($ContribucionesOScontribucionadicionalos, 2, ",", ".");

                        ?>
                    </td>
                </tr>
                <tr>
                    <td>ANSAAL</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['ContribucionesOSANSSAL'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($ContribucionesOSANSSAL, 2, ",", "."); ?></td>
                </tr>
                <tr style="<?php echo  $styleForTotalTd; ?>">
                    <td>Total Contribuciones OS</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['ContribucionesOStotal'], 2, ",", ".")."</td>";
                        $empleadoDatos[$empleadoid]['totalAPagar']+=$empleadoDatos[$empleadoid]['ContribucionesOStotal'];
                    }
                    ?>
                    <td><?php echo number_format($ContribucionesOStotal, 2, ",", ".");
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
                    <td rowspan="5" style=" vertical-align:middle!important;">
                        <div >
                            Aportes OS
                        </div>
                    </td>
                    <td>Aporte OS</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['AporteOSaporteos'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($AporteOSaporteos, 2, ",", ".");?></td>
                </tr>
                <tr>
                    <td>Aporte Adicional OS</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['AporteOSaporteadicionalos'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($AporteOSaporteadicionalos, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td>ANSSAL</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['AporteOSANSSAL'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($AporteOSANSSAL, 2, ",", "."); ?></td>
                </tr>
                <tr>
                    <td>Adicional Adherente</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['AporteOSadicionaladherente'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($AporteOSadicionaladherente, 2, ",", "."); ?></td>
                </tr>
                <tr style="<?php echo  $styleForTotalTd; ?>">
                    <td>Total Aporte OS</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['AporteOStotal'], 2, ",", ".")."</td>";
                        $empleadoDatos[$empleadoid]['totalAPagar']+=$empleadoDatos[$empleadoid]['AporteOStotal'];
                    }
                    ?>
                    <td><?php echo number_format($AporteOStotal, 2, ",", ".");
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
                    <td style=" vertical-align:middle!important;">
                        <div >
                            ART
                        </div>
                    </td>
                    <td>ART</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['ARTart'], 2, ",", ".")."</td>";
                        $empleadoDatos[$empleadoid]['totalAPagar']+=$empleadoDatos[$empleadoid]['ARTart'];
                    }
                    ?>
                    <td><?php echo number_format($ARTart, 2, ",", ".");
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
                    <td style=" vertical-align:middle!important;">
                        <div >
                            Seguro de Vida Oblig.
                        </div>
                    </td>
                    <td>Seguro de Vida Obligatorio</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['SeguroDeVidaObligatorio'], 2, ",", ".")."</td>";
                        $empleadoDatos[$empleadoid]['totalAPagar']+=$empleadoDatos[$empleadoid]['SeguroDeVidaObligatorio'];
                    }
                    ?>
                    <td><?php
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
                    <td style=" vertical-align:middle!important;">
                        <div >
                            Totales
                        </div>
                    </td>
                    <td>A Pagar</td>
                    <?php
                    $totalSUSSAPAGAR=0;
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format( $empleadoDatos[$empleadoid]['totalAPagar'], 2, ",", ".")."</td>";
                        $totalSUSSAPAGAR+= $empleadoDatos[$empleadoid]['totalAPagar'];
                    }
                    ?>
                    <td><?php
                        echo number_format($totalSUSSAPAGAR, 2, ",", ".");
                       ?></td>
                </tr>
            </tbody>
	</table>
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
