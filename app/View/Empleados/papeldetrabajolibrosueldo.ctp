<?php
    echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
    echo $this->Form->input('empidPDT',array('value'=>$empid,'type'=>'hidden'));
 echo $this->Form->button('Imprimir',
    array('type' => 'button',
        'class' =>"btn_imprimir",
        'onClick' => "openWinLibroSueldo()"
    )
);
?>
    <div id="sheetCooperadoraAsistencial" class="index" style="margin: 10px 0px;">
       <?php
        $empleadoDatos = array();
        $miempleado = array();
        if(!isset($miempleado['horasDias'])) {
            $miempleado['basico'] = 0;
            $miempleado['sueldo'] = 0;
            $miempleado['adicionales'] = 0;
            $miempleado['antiguedad'] = 0;
            $miempleado['presentismo'] = 0;
            $miempleado['sac'] = 0;
            $miempleado['acuerdonoremunerativo'] = 0;
            $miempleado['antiguedadnoremunerativo'] = 0;
            $miempleado['presentismonoremunerativo'] = 0;
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
            $miempleado['totalremuneracion'] = 0;
            $miempleado['totaldescuento'] = 0;
            $miempleado['neto'] = 0;
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
        $antiguedad = 0;
        $presentismo = 0;

        $sac = 0;
        $acuerdonoremunerativo = 0;
        $antiguedadnoremunerativo = 0;
        $presentismonoremunerativo = 0;
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

        $totalremuneracion = 0;
        $totaldescuento = 0;
        $neto = 0;
        $redondeo = 0;
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
            if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='20'/*Vacaciones Remunerativas*/){
                $sueldo -= $valorrecibo['valor'];
            }
            //Antiguedad
            if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                array('18'/*Antiguedad*/), true )){
                $antiguedad += $valorrecibo['valor'];
            }
            //Presentismo
            if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                array('77'/*Presentismo*/), true )){
                $presentismo += $valorrecibo['valor'];
            }

            //S.A.C. Remunerativo 1
            if (in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                array('92'/*S.A.C. Remunerativo 1*/), true )){
                $sac += $valorrecibo['valor'];
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
            //Redondeo
            if (
            in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                array('125'/*Redondeo*/), true )
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
        $miempleado['basico']=$basico;
        $miempleado['adicionales']=$adicionales;
        $miempleado['antiguedad']=$antiguedad;
        $miempleado['presentismo']=$presentismo;
        $miempleado['sac'] = $sac;
        $miempleado['acuerdonoremunerativo'] = $acuerdonoremunerativo;
        $miempleado['antiguedadnoremunerativo'] = $antiguedadnoremunerativo;
        $miempleado['presentismonoremunerativo'] = $presentismonoremunerativo;
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
        $miempleado['totalremuneracion']=$totalremuneracion;
        $miempleado['totaldescuento']=$totaldescuento;
        $miempleado['neto']=$neto;
        $miempleado['redondeo']=$redondeo;
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
                <div style="float: right">
                <?php echo $this->Form->input('hoja',
                    [
                        'div'=>false,
                        'label'=>[
                            'style'=>'display:inline-block;height: 3px;font-size:10px'
                        ],
                        'style'=>'height: 5px;max-width:30px'
                    ]); ?> &nbsp; &nbsp; <?php
                    echo $this->Form->input('tomo',[
                        'div'=>false,
                        'value'=>$empleado['Cliente']['padron'],
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
                Cargo: <?php echo $empleado['Empleado']['categoria'] ?>
                <?php echo $empleado['Empleado']['jornada']=='0.5'?"Media":"" ?>
                Jornada: <?php echo $empleado['Empleado']['jornada']=='0.5'?"":"Completa" ?>
                F. Ingreso:  <?php echo date('d-m-Y',strtotime($empleado['Empleado']['fechaingreso'])); ?>
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
                Codigo
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
            <td></td>
        </tr>
        <?php
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
            <td></td>
        </tr>
    <?php }
    if($miempleado['jubilacion']*1>0){ ?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>
                701
            </td>
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
            <td></td>
            <td></td>
            <td></td>
            <td>
                706
            </td>
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
            <td></td>
            <td></td>
            <td></td>
            <td>
                721
            </td>
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
            <td></td>
            <td></td>
            <td></td>
            <td>
                722
            </td>
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
            <td></td>
            <td></td>
            <td></td>
            <td>
                821
            </td>
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
            <td></td>
            <td></td>
            <td></td>
            <td>
                822
            </td>
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
            <td></td>
            <td></td>
            <td></td>
            <td>
                823
            </td>
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
            <td></td>
            <td></td>
            <td></td>
            <td>
                824
            </td>
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
            <td></td>
            <td></td>
            <td></td>
            <td>
                825
            </td>
            <td>
                <?php echo $miempleado['cuotasindical3nombre']; ?>

            </td>
            <td class="tdWithNumber">
                <?php echo number_format($miempleado['cuotasindical3'], 2, ",", "."); ?>
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
                <?php echo number_format($neto*1-$miempleado['redondeo']*1, 2, ",", "."); ?>
            </td>
            <td></td>
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
                <?php echo number_format($totalremuneracion, 2, ",", "."); ?>
            </td>
            <td>990</td>
            <td>
                Total descuentos
            </td>
            <td class="tdWithNumber">
                <?php echo number_format($totaldescuento, 2, ",", "."); ?>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
            </td>
            <td>
            </td>
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
            <td colspan="4" align="right">
               <div style="float:right;"> Firma del Empleado:</div>
            </td>
            <td colspan="10" style="vertical-align: bottom;">
                <hr width="450px" class="dottedhr" />
            </td>
        </tr>
        <tr>
            <td colspan="4">
            </td>
            <td colspan="10">
                <div style="width:100%;text-align: center;">
                <?php echo $empleado['Empleado']['nombre'];?>
                </div>
            </td>
        </tr>
    </table>
</div>
