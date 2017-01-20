<?php
    echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
    echo $this->Form->input('empidPDT',array('value'=>$empid,'type'=>'hidden'));
?>
<div id="reciboContenedor" class="" style="margin: 10px 25px;width: 100%;float: left">

    <?php
    echo $this->Form->button('Imprimir',
        array('type' => 'button',
            'class' =>"btn_imprimir",
            'style'=>"float:left",
            'onClick' => "openWin()"
        )
    );
    ?>
    </br>
    </br>
    </br>
    <div id="divToPrintRecibo">
        <div id="reciboOriginal" class="tblReciboSueldo" style="margin: 10px 0px;width: 520px; float:left">
                <?php
                $empleadoDatos = array();
                $miempleado = array();
                if(!isset($miempleado['horasDias'])) {
                    $miempleado['basico'] = 0;
                    $miempleado['sueldo'] = 0;
                    $miempleado['adicionales'] = 0;
                    $miempleado['antiguedad'] = 0;
                    $miempleado['presentismo'] = 0;
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
                    $miempleado['totalremuneracion'] = 0;
                    $miempleado['totaldescuento'] = 0;
                    $miempleado['neto'] = 0;
                    $miempleado['redondeo'] = 0;
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
                $antiguedad = 0;
                $presentismo = 0;
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
                $totalremuneracion = 0;
                $totaldescuento = 0;
                $neto = 0;
                $redondeo = 0;
                $remuneracioncd = 0;
                $adicionalcomplementarioss = 0;
                $acuerdoremunerativo = 0;
                $plusvacacional = 0;


                foreach ($empleado['Valorrecibo'] as $valorrecibo) {
                    //Dias Trabajados u Horas
                    if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='12'/*Dias Trabajados*/){
                        $diastrabajados += $valorrecibo['valor'];
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
                $miempleado['totalremuneracion']=$totalremuneracion;
                $miempleado['totaldescuento']=$totaldescuento;
                $miempleado['neto']=$neto;
                $miempleado['redondeo']=$redondeo;
                $miempleado['remuneracioncd']=$remuneracioncd;
                $miempleado['diastrabajados']=$diastrabajados;
                $miempleado['adicionalcomplementarioss']=$adicionalcomplementarioss;
                $miempleado['acuerdoremunerativo']=$acuerdoremunerativo;
                $miempleado['plusvacacional']=$plusvacacional;
                ?>
                <table id="tblReciboSueldo" cellspacing="0" class="tblInforme" style="padding:0px">
                    <tr>
                        <td colspan="20" class="tdWithBorder">
                            <b>Recibo de remuneraciones - Periodo: <?php echo $periodo ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="20" class="tdWithBorder">
                            <b>Empleador: </b><?php echo $empleado['Cliente']['nombre']; ?>
                            <b>CUIT: </b><?php echo $empleado['Cliente']['cuitcontribullente']; ?>
                            <b>Domicilio: </b><?php echo $empleado['Domicilio']['calle']; ?>
                            <b>Provincia: </b><?php echo $empleado['Domicilio']['Localidade']['Partido']['nombre'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="20" class="tdWithBorder">
                           <b> Empleado:</b>Legajo: <?php echo $empleado['Empleado']['legajo'] ?>
                            <b>Apellido y nombre:</b> <?php echo $empleado['Empleado']['nombre'] ?>
                            <b>Fecha de ingreso:</b> <?php echo date('d/m/Y',strtotime($empleado['Empleado']['fechaingreso'])); ?>
                           <b> Nº Recibo:</b> 1
                           <b> <?php echo $empleado['Empleado']['jornada']=='0.5'?"Media":"" ?>
                            Jornada <?php echo $empleado['Empleado']['jornada']=='0.5'?"":"Completa" ?></b>
                            </br>
                            <b>CUIL: </b><?php echo $empleado['Cliente']['cuitcontribullente']; ?>
                            <b>O.S.:</b> <?php echo $obrasocialnombre; ?>
                            <b>Condición:</b> <?php echo $empleado['Empleado']['codigoafip']; ?>
                            <b>Banco:</b> FALTA
                            </br>
                            <b>Cargo: </b><?php echo $empleado['Empleado']['categoria'] ?>
                            <b>Basico: </b><?php echo $miempleado['basico']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="30px" class="tdWithBorder">
                            <b>COD.</b>
                        </td>
                        <td class="tdWithBorder">
                            <b>CONCEPTO</b>
                        </td>
                        <td width="30px" class="tdWithBorder">
                            <b>C/Hs./%</b>
                        </td>
                        <td width="30px" class="tdWithBorder">
                            <b>REM. C/D</b>
                        </td>
                        <td width="30px" class="tdWithBorder">
                            <b>REM. S/D</b>
                        </td>
                        <td width="30px" class="tdWithBorder">
                            <b>DEDUCCIONES</b>
                        </td>
                    </tr>
                    <tr>
                        <td class="tdWithLeftRightBorder">
                            1
                        </td>
                        <td class="tdWithLeftRightBorder">
                            SUELDO MENSUAL
                        </td>
                        <td class="tdWithLeftRightBorder">
                            <?php echo $miempleado['diastrabajados']; ?>
                        </td>
                        <td class="tdWithLeftRightBorder">
                            <?php echo $miempleado['sueldo']; ?>
                        </td>
                        <td class="tdWithLeftRightBorder">
                        </td>
                        <td class="tdWithLeftRightBorder">
                        </td>
                    </tr>
                    <tr>
                        <td class="tdWithLeftRightBorder">
                            71
                        </td>
                        <td class="tdWithLeftRightBorder">
                            ANTIGÜEDAD
                        </td>
                        <td class="tdWithLeftRightBorder">
                            1
                        </td>
                        <td class="tdWithLeftRightBorder">
                            <?php echo $miempleado['antiguedad']; ?>
                        </td>
                        <td class="tdWithLeftRightBorder">
                        </td>
                        <td class="tdWithLeftRightBorder">
                        </td>
                    </tr>
                    <tr>
                        <td class="tdWithLeftRightBorder">
                            91
                        </td>
                        <td class="tdWithLeftRightBorder">
                            PRESENTISMO BASICO
                        </td>
                        <td class="tdWithLeftRightBorder">
                            1
                        </td>
                        <td class="tdWithLeftRightBorder">
                            <?php echo $miempleado['presentismo']; ?>
                        </td>
                        <td class="tdWithLeftRightBorder">
                        </td>
                        <td class="tdWithLeftRightBorder">
                        </td>
                    </tr>
                    <?php
                    if($miempleado['adicionalcomplementarioss']*1>0){ ?>
                        <tr>
                            <td class="tdWithLeftRightBorder">
                                122
                            </td>
                            <td class="tdWithLeftRightBorder">
                                Adicional Complemento SS
                            </td>
                            <td class="tdWithLeftRightBorder">
                                1
                            </td>
                            <td class="tdWithLeftRightBorder">
                                <?php echo $miempleado['adicionalcomplementarioss']; ?>
                            </td>
                            <td class="tdWithLeftRightBorder">
                            </td>
                            <td class="tdWithLeftRightBorder">
                            </td>
                        </tr>
                    <?php }
                    if($miempleado['acuerdoremunerativo']*1>0){ ?>
                        <tr>
                            <td class="tdWithLeftRightBorder">
                                146
                            </td>
                            <td class="tdWithLeftRightBorder">
                                Acuerdo Remunerativo
                            </td>
                            <td class="tdWithLeftRightBorder">
                                1
                            </td>
                            <td class="tdWithLeftRightBorder">
                                <?php echo $miempleado['acuerdoremunerativo']; ?>
                            </td>
                            <td class="tdWithLeftRightBorder">
                            </td>
                            <td class="tdWithLeftRightBorder">
                            </td>
                        </tr>
                    <?php }
                    if($miempleado['plusvacacional']*1>0){ ?>
                        <tr>
                            <td class="tdWithLeftRightBorder">
                                000
                            </td>
                            <td class="tdWithLeftRightBorder">
                                Plus Vacacional
                            </td>
                            <td class="tdWithLeftRightBorder">
                                1
                            </td>
                            <td class="tdWithLeftRightBorder">
                                <?php echo $miempleado['plusvacacional']; ?>
                            </td>
                            <td class="tdWithLeftRightBorder">
                            </td>
                            <td class="tdWithLeftRightBorder">
                            </td>
                        </tr>
                    <?php }
                    if($miempleado['jubilacion']*1>0){ ?>
                        <tr>
                            <td class="tdWithLeftRightBorder">
                                701
                            </td>
                            <td class="tdWithLeftRightBorder">
                                JUBILACION
                            </td>
                            <td class="tdWithLeftRightBorder">
                                1
                            </td>
                            <td class="tdWithLeftRightBorder">
                            </td>
                            <td class="tdWithLeftRightBorder">
                            </td>
                            <td class="tdWithLeftRightBorder">
                                <?php echo $miempleado['jubilacion']; ?>
                            </td>
                        </tr>
                    <?php }
                    if($miempleado['ley19032']*1>0){ ?>
                    <tr>
                        <td class="tdWithLeftRightBorder">
                            706
                        </td>
                        <td class="tdWithLeftRightBorder">
                            LEY 19032-INSSJP
                        </td>
                        <td class="tdWithLeftRightBorder">
                            1
                        </td>
                        <td class="tdWithLeftRightBorder">
                        </td>
                        <td class="tdWithLeftRightBorder">
                        </td>
                        <td class="tdWithLeftRightBorder">
                            <?php echo $miempleado['ley19032']; ?>
                        </td>
                    </tr>
                    <?php }
                    if($miempleado['obrasocial']*1>0){ ?>
                    <tr>
                        <td class="tdWithLeftRightBorder">
                            721
                        </td>
                        <td class="tdWithLeftRightBorder">
                            <?php echo $miempleado['obrasocialnombre']; ?>
                        </td>
                        <td class="tdWithLeftRightBorder">
                            1
                        </td>
                        <td class="tdWithLeftRightBorder">
                        </td>
                        <td class="tdWithLeftRightBorder">
                        </td>
                        <td class="tdWithLeftRightBorder">
                            <?php echo $miempleado['obrasocial']; ?>
                        </td>
                    </tr>
                    <?php }
                    if($miempleado['obrasocialextraordinario']*1>0){ ?>
                    <tr>
                        <td class="tdWithLeftRightBorder">
                            722
                        </td>
                        <td class="tdWithLeftRightBorder">
                            <?php echo $miempleado['obrasocialextraordinarionombre']; ?>
                        </td>
                        <td class="tdWithLeftRightBorder">
                            1
                        </td>
                        <td class="tdWithLeftRightBorder">
                        </td>
                        <td class="tdWithLeftRightBorder">
                        </td>
                        <td class="tdWithLeftRightBorder">
                            <?php echo $miempleado['obrasocialextraordinario']; ?>
                        </td>
                    </tr>
                    <?php }
                    if($miempleado['cuotasindical']*1>0){ ?>
                    <tr>
                        <td class="tdWithLeftRightBorder">
                            821
                        </td>
                        <td class="tdWithLeftRightBorder">
                            <?php echo $miempleado['cuotasindicalnombre']; ?>
                        </td>
                        <td class="tdWithLeftRightBorder">
                            1
                        </td>
                        <td class="tdWithLeftRightBorder">
                        </td>
                        <td class="tdWithLeftRightBorder">
                        </td>
                        <td class="tdWithLeftRightBorder">
                            <?php echo $miempleado['cuotasindical']; ?>
                        </td>
                    </tr>
                    <?php }
                    if($miempleado['cuotasindical1']*1>0){ ?>
                    <tr>
                        <td class="tdWithLeftRightBorder">
                            822
                        </td>
                        <td class="tdWithLeftRightBorder">
                            <?php echo $miempleado['cuotasindical1nombre']; ?>
                        </td>
                        <td class="tdWithLeftRightBorder">
                            1
                        </td>
                        <td class="tdWithLeftRightBorder">
                        </td>
                        <td class="tdWithLeftRightBorder">
                        </td>
                        <td class="tdWithLeftRightBorder">
                            <?php echo $miempleado['cuotasindical1']; ?>
                        </td>
                    </tr>
                    <?php }
                    if($miempleado['cuotasindical2nombre']*1>0){ ?>
                    <tr>
                        <td class="tdWithLeftRightBorder">
                            823
                        </td>
                        <td class="tdWithLeftRightBorder">
                            <?php echo $miempleado['cuotasindical2nombre']; ?>
                        </td>
                        <td class="tdWithLeftRightBorder">
                            1
                        </td>
                        <td class="tdWithLeftRightBorder">
                        </td>
                        <td class="tdWithLeftRightBorder">
                        </td>
                        <td class="tdWithLeftRightBorder">
                            <?php echo $miempleado['cuotasindical2']; ?>
                        </td>
                    </tr>
                    <?php }
                    if($miempleado['cuotasindical3nombre']*1>0){ ?>
                    <tr>
                        <td class="tdWithLeftRightBorder">
                            825
                        </td>
                        <td class="tdWithLeftRightBorder">
                            <?php echo $miempleado['cuotasindical3nombre']; ?>
                        </td>
                        <td class="tdWithLeftRightBorder">
                            1
                        </td>
                        <td class="tdWithLeftRightBorder">
                        </td>
                        <td class="tdWithLeftRightBorder">
                        </td>
                        <td class="tdWithLeftRightBorder">
                            <?php echo $miempleado['cuotasindical3']; ?>
                        </td>
                    </tr>
                    <?php }
                    if($miempleado['redondeo']*1>0){ ?>
                    <tr>
                        <td class="tdWithLeftRightBorder">
                            980
                        </td>
                        <td class="tdWithLeftRightBorder">
                            REDONDEO
                        </td>
                        <td class="tdWithLeftRightBorder">
                            1
                        </td>
                        <td class="tdWithLeftRightBorder">
                        </td>
                        <td class="tdWithLeftRightBorder">
                            <?php echo $miempleado['redondeo']; ?>
                        </td>
                        <td class="tdWithLeftRightBorder">
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="2" class="tdWithBorder"><b>Lugar de pago: </b>
                            <?php
                            echo $empleado['Domicilio']['Localidade']['Partido']['nombre']."-".$empleado['Domicilio']['Localidade']['nombre']
                            ?>
                        </td>
                        <td class="tdWithBorder"></td>
                        <td class="tdWithBorder"><b></b>Total</b></td>
                        <td class="tdWithBorder"><b></b>Total</b></td>
                        <td class="tdWithBorder"><b></b>Total</b></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="tdWithBorder"><b>Fecha de pago</b><?php echo date('d-m-Y');?></td>
                        <td class="tdWithBorder"></td>
                        <td class="tdWithBorder" style="text-align: right;"><?php echo $miempleado['totalremuneracion'];?></td>
                        <td class="tdWithBorder" style="text-align: right;"><?php echo $miempleado['redondeo'];?></td>
                        <td class="tdWithBorder" style="text-align: right;"><?php echo $miempleado['totaldescuento'];?></td>
                    </tr>
                    <tr>
                        <td colspan="3" rowspan="2" class="tdWithBorder">
                            Recibi conforme la suma de :
                            Son pesos:
                            En concepto de remuneraciones correspondintes al periodo arriba indicado dejando constancia
                            de haber recibido copia fiel de este recibo.
                        </td>
                        <td colspan="3" class="tdWithBorder"><b>Neto</b></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="tdWithBorder" style="text-align: right;"><?php echo $miempleado['neto'];?></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="tdWithBorder">
                        <td colspan="3" class="tdWithBorder" style="text-align: center;">
                            </br>
                            ................................</br>
                            <p id="firmaempleador"><b>Firma empleado</b></p>
                        </td>
                    </tr>
                </table>
        </div>
        <div id="reciboDuplicado" class="tblReciboSueldo" style="margin: 10px 0px;width: 520px;float: left"></div>
    </div>
</div>