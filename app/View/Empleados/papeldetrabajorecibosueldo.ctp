<?php
    echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
    echo $this->Form->input('empidPDT',array('value'=>$empid,'type'=>'hidden'));
?>
<div id="sheetCooperadoraAsistencial" class="index" style="margin: 10px 25px;"
     xmlns:float="http://www.w3.org/1999/xhtml">
        <?php echo $this->Form->button('Imprimir',
            array('type' => 'button',
                'class' =>"btn_imprimir",
                'onClick' => "PrintElem($('#sheetCooperadoraAsistencial'))"
            )
        );
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
            if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='52'/*Total basicos*/){
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
        <table id="tblLibroSueldo" class="tblInforme tbl_border" cellspacing="0">
            <tr>
                <td colspan="3">
                   <?php echo $empleado['Cliente']['nombre']; ?>
                </td>
                <td colspan="3">
                    Recibo de remuneraciones
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    Domicilio: <?php echo $empleado['Domicilio']['calle']; ?>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                        <?php echo $empleado['Domicilio']['Localidade']['Partido']['nombre'] ?>
                </td>
                <td colspan="3">
                        BANCO:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</br>
                        Periodo: <?php echo $periodo ?>
                </td>
            </tr>
            </tr>
            <tr>
                <td colspan="3">
                    CUIT: <?php echo $empleado['Cliente']['cuitcontribullente']; ?></br>
                    CUIL: <?php echo $empleado['Cliente']['cuitcontribullente']; ?>
                    O.S.: <?php echo $obrasocialnombre; ?>
                </td>
                <td colspan="3">
                    Condición:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</br>
                    <?php echo $empleado['Empleado']['codigoafip']; ?>
                </td>
            </tr>
            <tr>
                <td>LEGAJO </td>
                <td colspan="2">APELLIDO Y NOMBRE </td>
                <td colspan="2">FECHA DE INGRESO</td>
                <td colspan="1">Nº RECIBO: 1 Nº RECIBO: 1</td>
            </tr>
            <tr>
                <td><?php echo $empleado['Empleado']['legajo'] ?></td>
                <td colspan="2"><?php echo $empleado['Empleado']['nombre'] ?></td>
                <td colspan="2"><?php echo $empleado['Empleado']['fechaingreso'] ?></td>
                <td colspan="1">SUELDO</td>
            </tr>
            <tr>
                <td colspan="3">
                    ULTIMO DEPOSITO
                </td>
                <td colspan="2">
                    LIQUIDACION
                </td>
                <td colspan="1">
                    BASICO <?php echo $miempleado['basico']; ?>
                </td>
            </tr>
            <tr>
                <td>FECHA</td>
                <td>LAPSO</td>
                <td>BANCO</td>
                <td colspan="3">Cargo</td>
            </tr>
            <tr>
                <td><?php echo date('d-m-Y'); ?></td>
                <td><?php echo $periodo; ?></td>
                <td>BANCO</td>
                <td colspan="3"><?php echo $empleado['Empleado']['categoria'] ?></td>
            </tr>
            <tr>
                <td>
                    COD.
                </td>
                <td>
                    CONCEPTO
                </td>
                <td>
                    C/Hs./%
                </td>
                <td>
                    REM. C/D
                </td>
                <td>
                    REM. S/D
                </td>
                <td>
                    DEDUCCIONES
                </td>
            </tr>

            <tr>
                <td>
                    1
                </td>
                <td>
                    SUELDO MENSUAL
                </td>
                <td>
                    <?php echo $miempleado['diastrabajados']; ?>
                </td>
                <td>
                    <?php echo $miempleado['sueldo']; ?>
                </td>
                <td>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                    71
                </td>
                <td>
                    ANTIGÜEDAD
                </td>
                <td>
                    1
                </td>
                <td>
                    <?php echo $miempleado['antiguedad']; ?>
                </td>
                <td>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                    91
                </td>
                <td>
                    PRESENTISMO BASICO
                </td>
                <td>
                    1
                </td>
                <td>
                    <?php echo $miempleado['presentismo']; ?>
                </td>
                <td>
                </td>
                <td>
                </td>
            </tr>
            <?php
            if($miempleado['adicionalcomplementarioss']*1>0){ ?>
                <tr>
                    <td>
                        122
                    </td>
                    <td>
                        Adicional Complemento SS
                    </td>
                    <td>
                        1
                    </td>
                    <td>
                        <?php echo $miempleado['adicionalcomplementarioss']; ?>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                </tr>
            <?php }
            if($miempleado['acuerdoremunerativo']*1>0){ ?>
                <tr>
                    <td>
                        146
                    </td>
                    <td>
                        Acuerdo Remunerativo
                    </td>
                    <td>
                        1
                    </td>
                    <td>
                        <?php echo $miempleado['acuerdoremunerativo']; ?>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                </tr>
            <?php }
            if($miempleado['plusvacacional']*1>0){ ?>
                <tr>
                    <td>
                        000
                    </td>
                    <td>
                        Plus Vacacional
                    </td>
                    <td>
                        1
                    </td>
                    <td>
                        <?php echo $miempleado['plusvacacional']; ?>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                </tr>
            <?php }
            if($miempleado['jubilacion']*1>0){ ?>
                <tr>
                    <td>
                        701
                    </td>
                    <td>
                        JUBILACION
                    </td>
                    <td>
                        1
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                        <?php echo $miempleado['jubilacion']; ?>
                    </td>
                </tr>
            <?php }
            if($miempleado['ley19032']*1>0){ ?>
            <tr>
                <td>
                    706
                </td>
                <td>
                    LEY 19032-INSSJP
                </td>
                <td>
                    1
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                    <?php echo $miempleado['ley19032']; ?>
                </td>
            </tr>
            <?php }
            if($miempleado['obrasocial']*1>0){ ?>
            <tr>
                <td>
                    721
                </td>
                <td>
                    <?php echo $miempleado['obrasocialnombre']; ?>
                </td>
                <td>
                    1
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                    <?php echo $miempleado['obrasocial']; ?>
                </td>
            </tr>
            <?php }
            if($miempleado['obrasocialextraordinario']*1>0){ ?>
            <tr>
                <td>
                    722
                </td>
                <td>
                    <?php echo $miempleado['obrasocialextraordinarionombre']; ?>
                </td>
                <td>
                    1
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                    <?php echo $miempleado['obrasocialextraordinario']; ?>
                </td>
            </tr>
            <?php }
            if($miempleado['cuotasindical']*1>0){ ?>
            <tr>
                <td>
                    821
                </td>
                <td>
                    <?php echo $miempleado['cuotasindicalnombre']; ?>
                </td>
                <td>
                    1
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                    <?php echo $miempleado['cuotasindical']; ?>
                </td>
            </tr>
            <?php }
            if($miempleado['cuotasindical1']*1>0){ ?>
            <tr>
                <td>
                    822
                </td>
                <td>
                    <?php echo $miempleado['cuotasindical1nombre']; ?>
                </td>
                <td>
                    1
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                    <?php echo $miempleado['cuotasindical1']; ?>
                </td>
            </tr>
            <?php }
            if($miempleado['cuotasindical2nombre']*1>0){ ?>
            <tr>
                <td>
                    823
                </td>
                <td>
                    <?php echo $miempleado['cuotasindical2nombre']; ?>
                </td>
                <td>
                    1
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                    <?php echo $miempleado['cuotasindical2']; ?>
                </td>
            </tr>
            <?php }
            if($miempleado['cuotasindical3nombre']*1>0){ ?>
            <tr>
                <td>
                    825
                </td>
                <td>
                    <?php echo $miempleado['cuotasindical3nombre']; ?>
                </td>
                <td>
                    1
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                    <?php echo $miempleado['cuotasindical3']; ?>
                </td>
            </tr>
            <?php }
            if($miempleado['redondeo']*1>0){ ?>
            <tr>
                <td>
                    980
                </td>
                <td>
                    REDONDEO
                </td>
                <td>
                    1
                </td>
                <td>
                </td>
                <td>
                    <?php echo $miempleado['redondeo']; ?>
                </td>
                <td>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <td colspan="2">LUGAR DE PAGO</td>
                <td>FECHA</td>
                <td>TOTAL</td>
                <td>TOTAL</td>
                <td>TOTAL</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
                <td><?php echo date('d-m-Y');?></td>
                <td><?php echo $miempleado['totalremuneracion'];?></td>
                <td><?php echo $miempleado['redondeo'];?></td>
                <td><?php echo $miempleado['totaldescuento'];?></td>
            </tr>
            <tr>
                <td colspan="4" rowspan="3">
                    RECIBI CONFORME LA SUMA DE:
                    SON PESOS:
                    EN CONCEPTO DE REMUNERACIONES CORRESPONDIENTES AL
                    PERIODO ARRIBA INDICADO DEJANDO CONSTANCIA DE HABER
                    RECIBIDO COPIA FIEL DE ESTE RECIBO.
                </td>
                <td colspan="2">NETO</td>
            </tr>
            <tr>
                <td colspan="2"><?php echo $miempleado['neto'];?></td>
            </tr>
            <tr>
                <td colspan="2">
                    </br>
                    .......................</br>
                    FIRMA EMPLEADO
                </td>
            </tr>
        </table>


</div>
