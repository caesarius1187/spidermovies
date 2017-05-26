<?php echo $this->Html->script('jquery-ui',array('inline'=>false));?>
<?php echo $this->Html->script('empleados/formulario102',array('inline'=>false));?>

<style>
    .f102 {
        font-size: 9px;
    }
    .f102 td div{
        /*float: left;*/
        text-align: center;
        margin: 0px;
        padding: 0px;
    }
    .f102 .row{
        width: 100%;
    }
    .f102 input{
        display: block;
    }
    .roundcorner{
        border:1px solid #000;
        border-radius: 10px;
        margin: 0px;
        padding: 2px;
    }
</style>
<?php
$pemes = substr($periodo, 0, 2);
$peanio = substr($periodo, 3);

echo $this->Form->button('Imprimir',
    array('type' => 'button',
        'class' =>"btn_imprimir",
        'onClick' => "openWinForm102()"
    )
);

?>

<div id="contenedor" class="index f102">
	<table id="volantepago<?php echo $empleado['Empleado']['id']; ?>" cellspacing="0">
		<tr class="row">
            <td colspan="20" style="padding: 0px">
                <table cellspacing="0">
                    <tr>
                        <td  style="padding: 0px" width="30%">
                            <div class="roundcorner">
                                <div style="width: 100%;border-bottom: 1px solid black"><?php
                                    echo $this->Html->image('afip2.jpg',array('style'=>'width: 57%;margin-top: 5%;'));
                                    ?></div>
                                <div style="width: 100%;font-size: 20px">F.102/RT</div>
                            </div>
                        </td>
                        <td style="padding: 0px">
                            <div style="text-align: center;%"  class="roundcorner">
                                <div style="font-size: 10px;width: 100%;border-bottom: 1px solid black">
                                    <b>VOLANTA DE PAGO TRABAJADOR
                                        DE CASAS PARTICULARES
                                        APORTES Y CONTRIBUCIONES</b>
                                </div>
                                <div style="width: 100%">
                                    <div style="width: 100%;font-size: 10px;">
                                        CUIL TRABAJADOR: <?php
                                        $identificacionnumero = $empleado['Empleado']['cuit'];
                                        $cuit="";
                                        if(strlen($identificacionnumero)==11){
                                            $cuit .= substr($identificacionnumero, 0,2);
                                            $cuit .= "-";
                                            $cuit .= substr($identificacionnumero, 2,8);
                                            $cuit .= "-";
                                            $cuit .= substr($identificacionnumero, -1);
                                        }else{
                                            $cuit .= $identificacionnumero;
                                        }
                                        echo $cuit;
                                        ?></br>
                                    </div>
                                    <table cellspacing="0">
                                        <tr>
                                            <td style="padding: 0px">
                                                <div style=";vertical-align: middle;font-size: 10px;">PERIODO:</div>
                                            </td>
                                            <td style="padding: 0px">
                                                <div style="">
                                                    <table  cellspacing="0" class="tbl_border" style="font-size: 8px;width:100px;">
                                                        <tr>
                                                            <td>MES</td>
                                                            <td>AÑO</td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo $pemes;?></td>
                                                            <td><?php echo $peanio;?></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
		</tr>
		<tr class="row" class="roundcorner">
            <td colspan="20" style="padding: 0px">
                <div style="text-align: left;%"  class="roundcorner">
			        <b>Rubro I - </b> INGRESO DE LA OBLIGACION MENSUAL
                </div>
            </td>
		</tr>
		<tr class="row" style="padding: 0px">
            <td colspan="20" style="padding: 0px">
            <form>
                <table cellspacing="0">
                    <tr>
                        <td>
                            <div style="margin-right: 1%">
                            <div class="roundcorner" style="width: 100%;">
                                <p style="display: inline;width: 29%;border-right: 1px solid black">
                                    <b>F.1026</b>
                                </p>
                                <label style="display: inline;width:60%;">TRABAJADORES ACTIVOS</label>
                            </div>
                            <table cellspacing="0" class="roundcorner">
                                <tr style="width: 100%;border-bottom: 1px solid black">
                                    <td style=" padding: 0px;font-size: 7px;width: 69%;border-right: 1px solid black">
                                        HORAS TRABAJADAS SEMANALMENTE</br>
                                        <p style="font-size: 6px">(marcar con "X" lo que no corresponda)</p>
                                    </td>
                                    <td style="padding: 0px;width: 30%;">Importe</td>
                                </tr>
                                <tr style="width: 100%;border-bottom: 1px solid black">
                                    <td style="padding: 0px;font-size: 10px;width: 69%;border-right: 1px solid black">
                                        <input type="radio" name="rubroI" class="checkboxrubroI trabajadoractivo menosde12" value="176.00">- Menos de 12
                                    </td>
                                    <td style="padding: 0px;width: 30%;">(*)</td>
                                </tr>
                                <tr style="width: 100%;border-bottom: 1px solid black">
                                    <td style="padding: 0px;font-size: 10px;width: 69%;border-right: 1px solid black">
                                        <input type="radio" name="rubroI" class="checkboxrubroI trabajadoractivo desde12a16" value="252.00" >- Desde 12 a menos 16</td>
                                    <td style="padding: 0px;width: 30%;">(**)</td>
                                </tr>
                                <tr style="width: 100%;">
                                    <td style="padding: 0px;font-size: 10px;width: 69%;border-right: 1px solid black">
                                        <input type="radio" name="rubroI" class="checkboxrubroI trabajadoractivo 16omas" value="684.00" >- 16 o mas</td>
                                    <td style="padding: 0px;width: 30%;">(***)</td>
                                </tr>
                            </table>
                        </div>
                        </td>
                        <td>
                            <div style="margin-right: 1%">
                            <div class="roundcorner" style="width: 100%;">
                                <p style="display: inline;width: 29%;border-right: 1px solid black">
                                    <b>F.1027</b>
                                </p>
                                <label style="display: inline;width:60%;">TRABAJADORES ACTIVOS</label>
                            </div>
                            <table cellspacing="0" class="roundcorner">
                                <tr style="width: 100%;border-bottom: 1px solid black">
                                    <td style="padding: 0px;font-size: 7px;width: 69%;border-right: 1px solid black">
                                        HORAS TRABAJADAS SEMANALMENTE</br>
                                        <p style="font-size: 6px">(marcar con "X" lo que no corresponda)</p>
                                    </td>
                                    <td style="width: 30%;padding: 0px;">Importe</td>
                                </tr>
                                <tr style="width: 100%;border-bottom: 1px solid black">
                                    <td style="padding: 0px;font-size: 10px;width: 69%;border-right: 1px solid black">
                                        <input type="radio" name="rubroI" class="checkboxrubroI trabajadorjubilado menosde12" value="142.00">- Menos de 12</td>
                                    <td style="padding: 0px;width: 30%;">$142.00</td>
                                </tr>
                                <tr style="width: 100%;border-bottom: 1px solid black">
                                    <td style="padding: 0px;font-size: 10px;width: 69%;border-right: 1px solid black">
                                        <input type="radio" name="rubroI" class="checkboxrubroI trabajadorjubilado desde12a16" value="189.00" >- Desde 12 a menos 16</td>
                                    <td style="padding: 0px;width: 30%;">$189.00</td>
                                </tr>
                                <tr style="width: 100%;">
                                    <td style="padding: 0px;font-size: 10px;width: 69%;border-right: 1px solid black">
                                        <input type="radio" name="rubroI" class="checkboxrubroI trabajadorjubilado 16omas" value="265.00">- 16 o mas</td>
                                    <td style="padding: 0px;width: 30%;">$265.00</td>
                                </tr>
                            </table>
                        </div>
                        </td>
                        <td>
                            <div style="margin-right: 1%">
                            <div class="roundcorner" style="width: 100%;">
                                <p style="display: inline;width: 29%;border-right: 1px solid black">
                                    <b>F.1028</b>
                                </p>
                                <label style="display: inline;width:60%;">TRABAJADORES ACTIVOS</label>
                            </div>
                            <table cellspacing="0" class="roundcorner">
                                <tr style="width: 100%;border-bottom: 1px solid black">
                                    <td style="padding: 0px;font-size: 7px;width: 69%;border-right: 1px solid black">
                                        HORAS TRABAJADAS SEMANALMENTE</br>
                                        <p style="font-size: 6px">(marcar con "X" lo que no corresponda)</p>
                                    </td>
                                    <td style="padding: 0px;width: 30%;">Importe</td>
                                </tr>
                                <tr style="width: 100%;border-bottom: 1px solid black">
                                    <td style="padding: 0px;font-size: 10px;width: 69%;border-right: 1px solid black">
                                        <input type="radio" name="rubroI" class="checkboxrubroI trabajadormenores 16omas" value="164.00">- Menos de 12</td>
                                    <td style="padding: 0px;width: 30%;">(I)</td>
                                </tr>
                                <tr style="width: 100%;border-bottom: 1px solid black">
                                    <td style="padding: 0px;font-size: 10px;width: 69%;border-right: 1px solid black">
                                        <input type="radio" name="rubroI" class="checkboxrubroI trabajadormenores desde12a16" value="228.00">- Desde 12 a menos 16</td>
                                    <td style="padding: 0px;width: 30%;">(II)</td>
                                </tr>
                                <tr style="width: 100%;">
                                    <td style="padding: 0px;font-size: 10px;width: 69%;border-right: 1px solid black">
                                        <input type="radio" name="rubroI" class="checkboxrubroI trabajadormenores 16omas" value="649.00">- 16 o mas</td>
                                    <td style="padding: 0px;width: 30%;">(III)</td>
                                </tr>
                            </table>
                        </div>
                        </td>
                    </tr>
                </table>
                </form>
            </td>
		</tr>
        <tr class="row">
            <td    style="padding: 0px;">
                <div style="font-size: 8px">
                    <div style="width: 100%"><b>(*) Importe hasta el 05/16 $161 - Desde el 06/16 $176</b></div>
                    <div style="width: 100%"><b>(**) Importe hasta el 05/16 $224 - Desde el 06/16 $252</b></div>
                    <div style="width: 100%"><b>(**) Importe hasta el 05/16 $498 - Desde el 06/16 $684</b></div>
                </div>
            </td>
            <td  style="padding: 0px;">
                <div style="font-size: 8px">
                    <div style="width: 100%"><b>(I)Importe hasta el 05/16 $149 - Desde el 06/16 $228</b></div>
                    <div style="width: 100%"><b>(II)Importe hasta el 05/16 $149 - Desde el 06/16 $228</b></div>
                    <div style="width: 100%"><b>(III)Importe hasta el 05/16 $149 - Desde el 06/16 $228</b></div>
                </div>
            </td>
        </tr>
        <tr class="row roundcorner">
            <td  style="padding: 0px;">
                <div style="width:100%;text-align: left" class="" >
                    IMPORTE DE LA OBLIGACIÓN MENSUAL</br>
                    <p style="font-size: 8px">
                        (TRANSCRIBA EL IMPORTE CORRESPONDIENTE A LA CANTIDAD DE HORAS TRABAJADOS)
                    </p>
                </div>
            </td>
            <td  style="padding: 0px;">
                <div style="font-size: 16px;" class="roundcorner" >
                    <div style="width: 30%;text-align: left">$</div>
                    <div style="width: 70%;text-align: right"><b><p id="obligacionmensual">684.00</p></b></div>
                </div>
            </td>
        </tr>
        <tr class="row">
            <td style="padding: 0px;">
                <div class="roundcorner" style="font-size: 9px"><b>RUBRO II - </b>INGRESO DE INTERESES RESARCITORIOS</div>
            </td>
            <td style="padding: 0px;">
                <div class="roundcorner" style="font-size: 9px"><b>RUBRO III - </b>INGRESO DE UNTERESES CAPITALIZABLES</div>
            </td>
        </tr>
        <tr class="row">
            <td style="padding: 0px;">
                <div style="width: 100%" class="roundcorner">
                    <table cellspacing="0">
                        <tr>
                            <td style=" padding: 0px;border-right: 1px solid black;"><b>F.1029</b></td>
                            <td style=" padding: 0px;font-size: 8px;border-right: 1px solid black;">CONDICIÓN</br>
                                <p style="font-size: 6px;">(marcar con "X" la condición)</p></td>
                            <td style=" padding: 0px;font-size: 8px"">
                                <input type="radio" >ACTIVO
                            </td>
                            <td style=" padding: 0px;font-size: 8px"">
                                <input type="radio" >JUBILADO
                            </td>
                            <td style=" padding: 0px;font-size: 8px;">
                                <input type="radio" >MENOR
                            </td>
                        </tr>
                    </table>
                </div>
                <table cellspacing="0" style="width: 100%" class="roundcorner">
                    <tr style="width: 100%;">
                        <td style=" padding: 0px;font-size: 8px;border:1px solid black;height:33px;">HORAS TRABAJADAS SEMANALMENTE</br>(marcarcon "X" la condición)</td>
                        <td style=" padding: 0px;font-size: 8px;border:1px solid black;height:33px;">Importe </td>
                    </tr>
                    <tr style="width: 100%;">
                        <td style=" padding: 0px;font-size: 7px;border:1px solid black;height:30px;text-align: left">
                            <input type="radio" style="margin-top: 2px">- Menos de 12</td>
                        <td style=" padding: 0px;border:1px solid black;height:30px;"> </td>
                    </tr>
                    <tr style="width: 100%;">
                        <td style=" padding: 0px;font-size: 7px;border:1px solid black;height:30px;;text-align: left">
                            <input type="radio" style="margin-top: 2px">- Desde 12 a menos de 16</td>
                        <td style=" padding: 0px;border:1px solid black;height:30px;"> </td>
                    </tr>
                    <tr style="width: 100%;">
                        <td style=" padding: 0px;font-size: 7px;border:1px solid black;height:30px;;text-align: left">
                            <input type="radio" style="margin-top: 2px">- 16 o más</td>
                        <td style=" padding: 0px;border:1px solid black;height:30px;"> </td>
                    </tr>
                </table>
            </td>
            <td>
                <div style="width: 100%" class="roundcorner">
                    <table cellspacing="0">
                        <tr>
                            <td style=" padding: 0px;border-right: 1px solid black;"><b>F.1030</b></td>
                            <td style=" padding: 0px;font-size: 8px;border-right: 1px solid black;">CONDICIÓN</br>
                                <p style="font-size: 6px;">(marcar con "X" la condición)</p></td>
                            <td style=" padding: 0px;font-size: 8px;">
                                <input type="radio" >ACTIVO
                            </td>
                            <td style=" padding: 0px;font-size: 8px;">
                                <input type="radio" >JUBILADO
                            </td>
                            <td style=" padding: 0px;font-size: 8px;">
                                <input type="radio" >MENOR
                            </td>
                        </tr>
                    </table>
                </div>
                <table cellspacing="0" style="width: 100%" class="roundcorner">
                    <tr style="width: 100%;">
                        <td style=" padding: 0px;font-size: 8px;border:1px solid black;height:33px;">HORAS TRABAJADAS SEMANALMENTE</br>(marcarcon "X" la condición)</td>
                        <td style=" padding: 0px;font-size: 8px;border:1px solid black;height:33px;">Importe </td>
                    </tr>
                    <tr style="width: 100%;">
                        <td style=" padding: 0px;font-size: 7px;border:1px solid black;height:30px;text-align: left">
                            <input type="checkbox" style="margin-top: 2px">- Menos de 12</td>
                        <td style=" padding: 0px;border:1px solid black;height:30px;"> </td>
                    </tr>
                    <tr style="width: 100%;">
                        <td style=" padding: 0px;font-size: 7px;border:1px solid black;height:30px; text-align: left">
                            <input type="checkbox" style="margin-top: 2px">- Desde 12 a menos de 16</td>
                        <td style=" padding: 0px;border:1px solid black;height:30px;"> </td>
                    </tr>
                    <tr style="width: 100%;">
                        <td style=" padding: 0px;font-size: 7px;border:1px solid black;height:30px; text-align: left">
                            <input type="checkbox" style="margin-top: 2px">- 16 o más</td>
                        <td style=" padding: 0px;border:1px solid black;height:30px;"> </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style="text-align: left">
            <td class="row roundcorner"  colspan="20"><b>RUBRO IV -</b> COBERTURA ASEGURADORA RIESGOS DE TRABAJO</td>
        </tr>
        <tr style="text-align: left">
            <td  class="row roundcorner" colspan="20">CUIT/CUIL EMPLEADOR
            <?php
            $identificacionnumeroEmpleador = $cliente['Cliente']['cuitautorizada'];
            $cuitEmpleador="";
            if(strlen($identificacionnumeroEmpleador)==11){
                $cuitEmpleador .= substr($identificacionnumeroEmpleador, 0,2);
                $cuitEmpleador .= "-";
                $cuitEmpleador .= substr($identificacionnumeroEmpleador, 2,8);
                $cuitEmpleador .= "-";
                $cuitEmpleador .= substr($identificacionnumeroEmpleador, -1);
            }else{
                $cuitEmpleador .= $identificacionnumeroEmpleador;
            }
            echo $cuitEmpleador;
            ?>
            </td>
        </tr>
        <tr class="row">
            <td colspan="20">
            <hr style="border-top: dotted 2px;width: 100%" />
            </td>
        </tr>
        <tr class="row">
            <td colspan="20">
            <b>RECIBO DE PAGO </b>(Imprimir por duplicado y entregar una copia al trabajador)
            </td>
        </tr>
        <tr style="">
            <td  class="row roundcorner"  colspan="20">
                <table cellspacing="0">
                    <tr>
                        <td style="font-size: 8px">
                            <div style="width: 100%">
                                <div style="width: 100%;text-align: left">
                                    <b>Empleador </b>(Apellido y Nombre):</br>
                                    <?php
                                    echo  $cliente['Cliente']['nombre'];
                                    ?></div>
                                <div style="width: 100%;text-align: left">Domicilio de trabajo: </br>
                                    <?php
                                    echo  $cliente['Domicilio'][0]['calle'];
                                    ?></div>
                            </div>
                        </td>
                        <td style="font-size: 8px">
                            <div style="width: 100%" class="row">
                                CUIT/CUIL:
                                <?php
                                echo $cuitEmpleador;
                                ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr  style="text-align: left">
            <td class="row roundcorner" colspan="20">
                <table cellspacing="0">
                    <tr>
                        <td style="font-size: 8px">
                            <div style="width: 49%">
                                <div style="width: 100%;text-align: left"><b>Trabajador</b> (Apellido y Nombre):</br> <b>
                                        <?php
                                        echo $empleado['Empleado']['nombre'];
                                        ?>
                                    </b></div>
                                <div style="width: 100%;text-align: left">Domicilio de trabajo: </br>
                                    <?php
                                    echo $empleado['Domicilio']['calle'];
                                    ?></div>
                            </div>
                        </td>
                        <td style="font-size: 8px">
                            <div style="width: 49%">
                                <div style="width: 100%" class="row">
                                    CUIT/CUIL: <?php
                                    echo $cuit;
                                    ?>
                                </div>
                                <div style="width: 100%" class="row">
                                    <div></div>
                                    <div>
                                        <table cellspacing="0" class="tbl_border">
                                            <tr>
                                                <td style="font-size: 8px" rowspan="2"><b>Fecha de Ingreso:</b></td>
                                                <td style="font-size: 8px">DIA</td>
                                                <td style="font-size: 8px">MES</td>
                                                <td style="font-size: 8px">AÑO</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 8px"><?php
                                                    echo date('d',strtotime($empleado['Empleado']['fechaalta']));
                                                    ?>
                                                </td>
                                                <td style="font-size: 8px"><?php
                                                    echo date('m',strtotime($empleado['Empleado']['fechaalta']));
                                                    ?>
                                                </td>
                                                <td style="font-size: 8px"><?php
                                                    echo date('Y',strtotime($empleado['Empleado']['fechaalta']));
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr >
            <td class="row roundcorner" colspan="20">
                <table cellspacing="0">
                    <tr>
                        <td style="padding:0px;font-size: 8px">
                            Detalle del período:<input type="text"  maxlength="50" style="display: inline;">
                        </td>
                        <td style="padding:0px;font-size: 8px">
                            Puesto desempeñado:<input type="text"  maxlength="50" style="display: inline;">
                        </td>
                    </tr>
                    <tr>
                        <td  style="padding:0px;font-size: 8px">
                            Desde:
                            <table cellspacing="0" class="tbl_border" style="display: inline;">
                                <tr>
                                    <td style="padding:1px;font-size: 8px">DIA</td>
                                    <td style="padding:1px;font-size: 8px">MES</td>
                                    <td style="padding:1px;font-size: 8px">AÑO</td>
                                </tr>
                                <tr>
                                    <td style="padding:1px;font-size: 8px">01
                                    </td>
                                    <td style="padding:1px;font-size: 8px"><?php
                                        echo $pemes
                                        ?>
                                    </td>
                                    <td style="padding:1px;font-size: 8px"><?php
                                        echo $peanio
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="font-size: 8px">
                            Hasta:
                            <table cellspacing="0" class="tbl_border" style="display: inline;">
                                <tr>
                                    <td style="padding:1px;font-size: 8px">DIA</td>
                                    <td style="padding:1px;font-size: 8px">MES</td>
                                    <td style="padding:1px;font-size: 8px">AÑO</td>
                                </tr>
                                <tr>
                                    <td style="padding:1px;font-size: 8px">
                                        <?php
                                        echo date('m',strtotime('01-'.$pemes.'-'.$peanio));
                                        ?>
                                    </td>
                                    <td style="padding:1px;font-size: 8px">
                                        <?php
                                        echo $pemes
                                        ?>
                                    </td>
                                    <td style="padding:1px;font-size: 8px">
                                        <?php
                                        echo $peanio
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr>
            <td class="row roundcorner" colspan ="20">
                <?php
                //aca vamos a calcular los datos del recibo
                $valores = [];
                foreach ($empleado['Valorrecibo'] as $valorrecibo) {
                    //Dias Trabajados u Horas
                    $conceptoid = $valorrecibo['Cctxconcepto']['Concepto']['id'];
                    if(!isset($valores[$conceptoid])){
                        $valores[$conceptoid]=[];
                        $valores[$conceptoid]['valor']=0;
                        $valores[$conceptoid]['concepto']=$valorrecibo['Cctxconcepto']['Concepto']['nombre'];
                    }
                    $valores[$conceptoid]['valor'] += $valorrecibo['valor'];
                }
                ?>
                <table cellspacing="0">
                    <tr>
                        <td style="padding: 0px;" rowspan="2">
                            Modalidad de Liquidacion:
                        </td>
                        <td style="padding: 0px;">
                            <table style="width:120px; display: inline">
                                <tr>
                                    <td style="padding: 0px;">
                                        Diaria <input type="radio" name="modalidadliquidacion">
                                    </td>
                                    <td style="padding: 0px;">
                                        Quincenal <input type="radio" name="modalidadliquidacion" >
                                    </td>
                                    <td style="padding: 0px;">
                                        Otras <input type="radio" name="modalidadliquidacion" ></br>
                                    </td>
                                </tr>
                            </table>
                            <div style="float: right;width: 260px;text-align: right ; display: inline">
                                Cantidad de Horas
                                <?php
                                $horastrabajadas = 0;
                                if(isset($valores['12'])) {//Días Trabajados u Horas
                                    $horastrabajadas+=$valores['12']['valor']*8;
                                }
                                if(isset($valores['140'])) {//Horas Decoracion(horas trabajadas)
                                    $horastrabajadas+=$valores['140']['valor']*8;
                                }
                                ?>
                                <input type="text"  maxlength="3" value="<?php echo $horastrabajadas ?>" style="width: 40px;display: inline;"></br>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0px;">
                            <table style="width:120px; display: inline">
                                <tr>
                                    <td style="padding: 0px;">
                                        Semanal <input type="radio" name="modalidadliquidacion" >
                                    </td>
                                    <td style="padding: 0px;">
                                        Mensual <input type="radio" name="modalidadliquidacion" >
                                    </td>
                                    <td style="padding: 0px;">
                                        Descripción:<input type="text" style="display: inline;">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table cellspacing="0" style="width:100%;">
                    <tr class="row roundcorner" style="margin-right: 2px">
                        <td style="padding: 0px;" colspan="2">
                            <div style="width: 98%;text-align: left;">
                                Remuneracion:
                            </div>
                        </td>
                        <td style="padding: 0px;">
                            Son Pesos:</br>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0px;">  Basico:</td>
                        <td style="padding: 0px;    padding-right: 5px;text-align: right">
                            <div style="float:right;">
                                $<?php
                                if(isset($valores['6'])) {//Sueldo Basico
                                    echo  number_format($valores['6']['valor'], 2, ",", ".");
                                }
                                ?>
                            </div>
                        </td>
                        <td style="padding: 0px;">
                            <?php
                            if(isset($valores['125'])) {//Sueldo
                                echo num2letras(number_format($valores['125']['valor'], 2, ".", ""));
                            }
                            ?>.-</br>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0px;">
                            S.A.C.:
                        </td>
                        <td style="padding: 0px;    padding-right: 5px;text-align: right">
                            $<?php
                            if(isset($valores['12'])) {//S.A.C. Remunerativo
                                echo  number_format($valores['12']['valor'], 2, ",", ".");
                            }
                            ?>
                        </td>
                        <td style="padding: 0px;">
                            Lugar y Fecha:</br>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0px;">
                            Vacaciones:
                        </td>
                        <td style="padding: 0px;    padding-right: 5px;text-align: right">
                            $<?php
                            if(isset($valores['20'])) {//Vacaciones Remunerativas
                                echo  number_format($valores['20']['valor'], 2, ",", ".");
                            }
                            ?>
                        </td>
                        <td style="padding: 0px;">
                            <?php $mesesN=array(1=>"Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
                                "Agosto","Septiembre","Octubre","Noviembre","Diciembre");?>
                            Salta,  <?php echo date('d')." de ".$mesesN[date('n')]." de ".date('Y');?>  de Abril de 2017</br>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0px;">
                            Otros conceptos:</br>
                            Aportes:
                        </td>
                        <td style="padding: 0px;    padding-right: 5px;text-align: right">
                            - $
                            <?php
                            if(isset($valores['40'])) {//Total Aportes
                                echo  number_format($valores['40']['valor'], 2, ",", ".");
                                echo  '<p id="aportes">'.$valores['40']['valor'].'</p>';
                            }else{
                                echo  '<p id="aportes">0</p>';
                            }?>
                        </td>
                        <td style="padding: 0px;">
                            N° de comprobante de pago de aportes y contribuciones(*):</br>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0px;">
                            Suma Total
                        </td>
                        <td style="padding: 0px; padding-right: 5px;text-align: right">
                            $
                            <?php
                            if(isset($valores['125'])) {//Sueldo
                                echo  number_format($valores['125']['valor'], 2, ",", ".");
                                echo  '<p id="sueldoTotal">'.$valores['125']['valor'].'</p>';
                            }else{
                                echo  '<p id="sueldoTotal">0</p>';
                            }?>
                        </td>
                        <td style="padding: 0px;">
                            ______________________________</br>
                        </td>
                    </tr>
                </table>
                <table cellspacing="0">
                    <tr>
                        <td class="roundcorner" style="width:49%;height: 80px;"> Firma del Empleador:</td>
                        <td class="roundcorner" style="width:49%;height: 80px;">Firma del Trabajador:</td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>
</div>
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