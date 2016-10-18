<?php echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false)); ?>
<?php echo $this->Html->script('impclis/papeldetrabajosuss',array('inline'=>false)); ?>
<?php echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
 echo $this->Form->input('impcliidPDT',array('value'=>$impcliid,'type'=>'hidden'));?>
<div class="index">
	<div id="Formhead" class="clientes papeldetrabajosuss index" style="margin-bottom:10px;">
		<h2>SUSS:</h2>
		Contribuyente: <?php echo $impcli['Cliente']['nombre']; ?></br>
		CUIT: <?php echo $impcli['Cliente']['cuitcontribullente']; ?></br>
		Periodo: <?php echo $periodo; ?>
	</div>
	<div id="sheetCooperadoraAsistencial" class="index"  >
		<!--Esta es la tabla original y vamos a recorrer todos los empleados por cada una de las
		rows por que -->
        <?php
        $empleadoDatos = array();
        foreach ($impcli['Cliente']['Empleado'] as $empleado) {
            $miempleado = array();
            if(!isset($miempleado['horasDias'])) {
                $miempleado['osdelpersdedireccion'] = false;
                $miempleado['coberturaart'] = true;//todo inicializar en false cuando tengamos de donde sacar el dato
                $miempleado['segurodevida'] = true;//todo inicializar en false cuando tengamos de donde sacar el dato
                $miempleado['adherente'] = $empleado['adherente'];

                $miempleado['horasDias'] = 0;
                $miempleado['sueldo'] = 0;
                $miempleado['adicionales'] = 0;
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
            }
            $osdelpersdedireccion = $miempleado['osdelpersdedireccion'];//todo inicializar en false cuando tengamos de donde sacar el dato
            $coberturaart = $miempleado['coberturaart'];//todo inicializar en false cuando tengamos de donde sacar el dato
            $segurodevida = $miempleado['segurodevida'];//todo inicializar en false cuando tengamos de donde sacar el dato

            $horasDias=0;$sueldo=0;$adicionales=0;$horasextras=0;$importehorasextras=0;
            $SAC=0;$vacaciones=0;$premios=0;$maternidad=0;$conceptosnorem=0;
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
            $codigoafip = 0;

            foreach ($empleado['Valorrecibo'] as $valorrecibo) {
                //Horas Diarias
                if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='112'/*Horas*/){
                    $horasDias +=$valorrecibo['valor'];
                }
                //Sueldo
                if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='21'/*Total basicos*/){
                    $sueldo += $valorrecibo['valor'];
                }
                if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='20'/*Vacaciones Remunerativas*/){
                    $sueldo -= $valorrecibo['valor'];
                }
                //Adicionales
                if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('18'/*Antiguedad*/,'77'/*Presentismo*/,'91'/*Otros*/,), true )){
                    $adicionales += $valorrecibo['valor'];
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
                    array('94'/*Total SAC Remunerativo*/), true )
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
                    array('120'/*Premios*/), true )
                ){
                    $premios += $valorrecibo['valor'];
                }
                //Maternidad
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
                    array('27'/*Total Remunerativos C/D*/,'109'/*Total Remunerativos S/D*/), true )
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
                    $rem4aportenegativo += $valorrecibo['valor'];
                    $rem4menorminimo += $valorrecibo['valor'];
                }
                if (
                in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                    array('108'/*Total Rem. S/D Indemnizatorios	*/), true )
                ){
                    $rem4aportenegativo -= $valorrecibo['valor'];
                }
                //Remuneracion 9
                $rem9 = $remtotal;
                //contribucionrenatea Primera parte
                if ($valorrecibo['Cctxconcepto']['Conveniocolectivotrabajo']['trabajorural']){
                    $trabajadorAgrario = true;
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
                    $seguridadsocialcontribtareadif = $valorrecibo['valor'];
                }
            }
            //Remuneracion 4 Segunda Parte
            if($AporteOSaporteadicionalos>0){
                $rem4 = $rem1;
            }else{
                if($rem4aportenegativo<=$empleado['minimoos']){
                    $rem4=$empleado['minimoos'];
                }else{
                    $rem4=$rem4menorminimo;
                }
            }
            //Rem. 8 (Cont. OS)
            $rem8 = $rem4;
            //Jubilacion SIPA
            if($empleado['codigoafip']=='0'){
                $ContribSSjubilacionsipa+=$rem2*0.1017;
            }
            if($empleado['codigoafip']=='1'){
                $ContribSSjubilacionsipa+=$rem2*0.1017*0.5;
            }
            if($empleado['codigoafip']=='2'){
                $ContribSSjubilacionsipa+=$rem2*0.1017*0.75;
            }
            //Jubilacion INSSJP
            if($empleado['codigoafip']=='0'){
                $INSSJP+=$rem2*0.01500;
            }
            if($empleado['codigoafip']=='1'){
                $INSSJP+=$rem2*0.01500*0.5;
            }
            if($empleado['codigoafip']=='2'){
                $INSSJP+=$rem2*0.01500*0.75;
            }
            //Contrib Tarea Dif
            $ContribSScontribtareadif = $rem1*($seguridadsocialcontribtareadif/100);

            //Jubilacion FNE
            if(!$trabajadorAgrario){
                if($empleado['codigoafip']=='0'){
                    $FNE+=$rem1*0.0089;
                }
                if($empleado['codigoafip']=='1'){
                    $FNE+=$rem1*0.0089*0.5;
                }
                if($empleado['codigoafip']=='2'){
                    $FNE+=$rem1*0.0089*0.75;
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
            if($empleado['codigoafip']=='0'){
                $asignacionfamiliar+=$rem1*0.04440;
            }
            if($empleado['codigoafip']=='1'){
                $asignacionfamiliar+=$rem1*0.04440*0.5;
            }
            if($empleado['codigoafip']=='2'){
                $asignacionfamiliar+=$rem1*0.04440*0.75;
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
                $AporteOSaporteos = $rem4 * 0.024;
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
            //Debugger::dump($rem9);
            if($coberturaart){
                $ARTart = (($rem9 *$impcli['Cliente']['alicuotaart']) / 100) + $impcli['Cliente']['fijoart'];
            }
            //Seguro de Vida obligatorio Seguro de Vida
            If($segurodevida){
                $SeguroDeVidaObligatorio = $impcli['Cliente']['segurodevida'];
            }
            $codigoafip = $empleado['codigoafip'];
            $miempleado['horasDias']=$horasDias;
            $miempleado['cantidadadherente']=$cantidadAdherentes;
            $miempleado['sueldo']=$sueldo;
            $miempleado['adicionales']=$adicionales;
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
        //Debugger::dump($empleadoDatos);
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
                    <td >

                    </td>
                    <td>Apellido y Nombre</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>".$empleado['nombre']."</td>";
                    }
                    ?>
                </tr><!--1-->
            </thead>
            <tbody>
                <tr>
                    <td rowspan="26" style=" vertical-align:middle!important;">
                        <div >
                            Remunerativos
                        </div>
                    </td>
                    <td>CUIL</td>
                    <?php
                        foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                                echo "<td>".$empleado['cuit']."</td>";
                        }
                    ?>
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
                </tr>
                <tr>
                    <td>Código AFIP</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>".$empleado['codigoafip']."</td>";
                    }
                    ?>
                </tr>
                <tr>
                    <td>Día de inicio</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>" . $empleado['fechaingreso'] . "</td>";
                    }
                    ?>
                </tr>
                <tr>
                    <td>Q Días Trabajados u Horas</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo $empleadoDatos[$empleadoid]['horasDias']."</td>";
                    }
                    ?>
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
                </tr>
                <tr>
                    <td>Adicionales</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo $empleadoDatos[$empleadoid]['adicionales']."</td>";
                    }
                    ?>
                </tr><!--10-->
                <tr>
                    <td>Cantidad de Horas Extra</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo $empleadoDatos[$empleadoid]['horasextras']."</td>";
                    }
                    ?>
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
                    <td rowspan="3">
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
                <tr>
                    <td>Conceptos no Remunerativos</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['conceptosnorem'], 2, ",", ".")."</td>";
                    }
                    ?>
                </tr>
                <tr>
                    <td>Rem. Total</td>
                    <?php
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
                    <td><?php echo $remtotal; ?></td>
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
                        echo "<td>";
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
                    }
                    ?>
                    <td>
                        <?php echo number_format($totalContribucionesSS, 2, ",", ".");
                        echo $this->Form->input(
                            'apagar351ContribucionesSegSocial',
                            array(
                                'type'=>'hidden',
                                'id'=> 'apagar351ContribucionesSegSocial',
                                'value'=>$totalContribucionesSS
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
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['contribucionrenatea'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($contribucionrenatea, 2, ",", ".");
                        echo $this->Form->input(
                            'apagar360ContribuciónRENATEA',
                            array(
                                'id'=>'apagar360ContribuciónRENATEA',
                                'type'=>'hidden',
                                'value'=>$contribucionrenatea
                            )
                        );?></td>
                </tr>
                <tr style="<?php echo  $styleForTotalTd; ?>">
                    <td>Aporte</td>
                    <?php
                    foreach ($impcli['Cliente']['Empleado'] as $empleado) {
                        echo "<td>";
                        $empleadoid = $empleado['id'];
                        echo number_format($empleadoDatos[$empleadoid]['aporterenatea'], 2, ",", ".")."</td>";
                    }
                    ?>
                    <td><?php echo number_format($aporterenatea, 2, ",", ".");
                        echo $this->Form->input(
                            'apagar935RENATEA',
                            array(
                                'id'=>'apagar935RENATEA',
                                'type'=>'hidden',
                                'value'=>$aporterenatea
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
                    }
                    ?>
                    <td><?php echo number_format($AporteSStotal, 2, ",", ".");
                        echo $this->Form->input(
                            'apagar301EmpleadorAportesSegSocial',
                            array(
                                'id'=>'apagar301EmpleadorAportesSegSocial',
                                'type'=>'hidden',
                                'value'=>$AporteSStotal
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
                    }
                    ?>
                    <td><?php echo number_format($ContribucionesOStotal, 2, ",", ".");
                        echo $this->Form->input(
                            'apagar352ContribucionesObraSocial',
                            array(
                                'id'=>'apagar352ContribucionesObraSocial',
                                'type'=>'hidden',
                                'value'=>$ContribucionesOStotal
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
                    <td><?php echo number_format($AporteOSaporteos, 2, ",", "."); ?></td>
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
                    }
                    ?>
                    <td><?php echo number_format($AporteOStotal, 2, ",", ".");
                        echo $this->Form->input(
                            'apagar302AportesObrasSociales',
                            array(
                                'id'=>'apagar302AportesObrasSociales',
                                'type'=>'hidden',
                                'value'=>$AporteOStotal
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
                    }
                    ?>
                    <td><?php echo number_format($ARTart, 2, ",", ".");
                        echo $this->Form->input(
                            'apagar312AsegRiesgodeTrabajoL24557',
                            array(
                                'id'=>'apagar312AsegRiesgodeTrabajoL24557',
                                'type'=>'hidden',
                                'value'=>$ARTart
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
                    }
                    ?>
                    <td><?php
                        echo number_format($SeguroDeVidaObligatorio, 2, ",", ".");
                        echo $this->Form->input(
                            'apagar28SegurodeVidaColectivo',
                            array(
                                'id'=>'apagar28SegurodeVidaColectivo',
                                'type'=>'hidden',
                                'value'=>$SeguroDeVidaObligatorio
                            )
                        );?></td>
                </tr>
            </tbody>
	</table>
        <?php //Debugger::dump($empleadoDatos);?>
	</div>
	<div id="divLiquidarSUSS">
	</div>
</div>
