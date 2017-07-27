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
        'onClick' => "openWinForm102()",
        'style' => 'margin-left:2%; margin-bottom:2%'
    )
);

?>

<div id="contenedor" class="index f102" style="width: 94%">
	<table id="volantepago<?php echo $empleado['Empleado']['id']; ?>" cellspacing="0" style="margin-bottom:2px">
		<tr class="row">
            <td colspan="20" style="padding: 0px">
                <table cellspacing="0" style="margin-bottom: 0px">
                    <tr>
                        <td  style="padding:0px" width="30%">
                            <div class="roundcorner" style="height:105px">
                                <div style="width: 100%;border-bottom: 1px solid black; height:80px">
                                    <?php
                                    echo $this->Html->image('afip2.jpg',array('style'=>'width: 85%; height:60px; margin-top: 5%;'));
                                    ?>
                                </div>
                                <div style="width: 100%;font-size: 20px;padding-top:0px">F.102/RT</div>
                            </div>
                        </td>
                        <td style="padding: 0px" width="70%">
                            <div style="text-align: center;height:105px;width:98%;margin-left:2%" class="roundcorner">
                                <div style="font-size: 12px;width: 100%;border-bottom: 1px solid black">
                                    <b>
                                        <div style="width:100%">VOLANTE DE PAGO TRABAJADOR</div>
                                        <div style="width:100%">DE CASAS PARTICULARES</div>
                                        <div style="width:100%">APORTES Y CONTRIBUCIONES</div>
                                    </b>
                                </div>
                                <div style="width: 100%">
                                    <table cellspacing="0" style="margin-bottom:0px;width:50%;margin-left:25%;margin-top:2px">
                                        <tr>
                                            <td style="padding:0px;font-size: 12px">
                                                CUIL TRABAJADOR: &nbsp;
                                            </td>
                                            <td style="padding:0px;font-size: 12px">
                                                <?php
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
                                                ?>
                                            </td>
                                        </tr>
                                    </table>                                    
                                </div>
                                <div style="width: 100%;">
                                    <table cellspacing="0" style="margin-bottom:0px;width:50%;margin-left:25%">
                                        <tr>
                                            <td style="padding:0px;">&nbsp;</td>    
                                            <td style="padding:0px;font-size:10px;border: 1px solid;text-align:center">
                                                MES
                                            </td>
                                            <td style="padding:0px;font-size:10px;text-align:center;border-bottom: 1px solid; border-right: 1px solid;border-top: 1px solid">
                                                AÑO
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:0px;font-size:10px;text-align:center">
                                                PERIODO:
                                            </td>    
                                            <td style="padding:0px;font-size:16px;text-align:center;border-left: 1px solid;border-bottom: 1px solid; border-right: 1px solid">
                                                <?php echo $pemes;?>
                                            </td>
                                            <td style="padding:0px;font-size:16px;text-align:center;border-bottom: 1px solid; border-right: 1px solid">
                                                <?php echo $peanio;?>
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
        <!-- RUBRO I -->
		<tr class="row" class="roundcorner">
            <td colspan="20" style="padding: 0px">
                <div style="text-align: left; font-size: 12px; padding-left: 10px"  class="roundcorner">
			        <b>Rubro I - </b> INGRESO DE LA OBLIGACION MENSUAL
                </div>
            </td>
		</tr>
		<tr class="row" style="padding: 0px">
            <td colspan="20" style="padding: 0px">            
                <table cellspacing="0" style="margin-bottom: 2px">
                    <tr style="padding-bottom: 0px;">
                        <td style="padding:0px;">
                            <div style="margin-right: 1%">
                            <div class="roundcorner" style="width: 99%; font-size: 10px; margin-bottom:2px;margin-top:2px">
                                <p style="display: inline;width: 29%;border-right: 1px solid;">
                                    <b style="padding-right:10px">F.1026</b>
                                </p>
                                <label style="display: inline;width:60%; font-size: 10px; padding-left:4px">
                                    TRABAJADORES ACTIVOS
                                </label>
                            </div>
                            <table cellspacing="0" class="roundcorner">
                                <tr style="width: 100%;">
                                    <td style=" padding: 0px;font-size:6px; width:69%; border-right: 1px solid;text-align:center;border-bottom: 1px solid;">
                                        <div style="width: 100%;">HORAS TRABAJADAS SEMANALMENTE</div>
                                        <div style="font-size: 5px;width: 100%;padding-bottom:2px">(marcar con "X" lo que no corresponda)</div>
                                    </td>
                                    <td style="padding:0px;width:30%;font-size: 8px; text-align:center;border-bottom: 1px solid;">
                                        Importe
                                    </td>
                                </tr>
                                <tr style="width: 100%;">
                                    <td style="padding: 0px;font-size: 10px;width: 69%;border-right: 1px solid; border-bottom: 1px solid">
                                        <input style="margin:2px;" type="radio" name="rubroI<?php echo $empleado['Empleado']['cuit'];?>"
                                                class="checkboxrubroI trabajadoractivo menosde12" value="176.00"
                                                aportes="34" contribuciones="142">
                                        <label style="margin-top:2px;">- Menos de 12</label>
                                    </td>
                                    <td style="padding: 0px;width: 30%; text-align:center; border-bottom: 1px solid;font-size: 10px">(*)</td>
                                </tr>
                                <tr style="width: 100%;">
                                    <td style="padding: 0px;font-size: 10px;width: 69%;border-right: 1px solid; border-bottom: 1px solid">
                                        <input style="margin:2px;" type="radio" name="rubroI<?php echo $empleado['Empleado']['cuit'];?>"
                                               class="checkboxrubroI trabajadoractivo desde12a16" value="252.00"
                                               aportes="63" contribuciones="189">
                                        <label style="margin-top:2px;">- Desde 12 a menos 16</label>
                                    </td>
                                    <td style="padding: 0px;width: 30%;text-align:center; border-bottom: 1px solid;font-size: 10px">
                                        (**)</td>
                                </tr>
                                <tr style="width: 100%;">
                                    <td style="padding: 0px;font-size: 10px;width: 69%;border-right: 1px solid">
                                        <input style="margin:2px;" type="radio" name="rubroI<?php echo $empleado['Empleado']['cuit'];?>"
                                               class="checkboxrubroI trabajadoractivo 16omas" value="684.00"
                                               aportes="419" contribuciones="265">
                                        <label style="margin-top:2px;">- 16 o mas</label>
                                    </td>
                                    <td style="padding: 0px;width: 30%;text-align:center;font-size: 10px">(***)</td>
                                </tr>
                            </table>
                        </div>
                        </td>
                        <td style="padding:0px;">
                            <div style="margin-right: 1%">
                            <div class="roundcorner" style="width: 99%; font-size: 10px; margin-bottom:2px;margin-top:2px">
                                <p style="display: inline;width: 29%;border-right: 1px solid">
                                    <b style="padding-right:10px">F.1027</b>
                                </p>
                                <label style="display: inline;width:60%; font-size: 10px; padding-left:4px">
                                    TRABAJADORES JUBILADOS
                                </label>
                            </div>
                            <table cellspacing="0" class="roundcorner">
                                <tr style="width: 100%;border-bottom: 1px solid black">
                                    <td style=" padding: 0px;font-size:6px; width:69%; border-right: 1px solid;text-align:center;border-bottom: 1px solid;">
                                        <div style="width: 100%;">HORAS TRABAJADAS SEMANALMENTE</div>
                                        <div style="font-size: 5px;width: 100%;padding-bottom:2px">
                                            (marcar con "X" lo que no corresponda)
                                        </div>
                                    </td>
                                    <td style="padding:0px;width:30%;font-size: 8px; text-align:center;border-bottom: 1px solid;">    Importe
                                    </td>
                                </tr>
                                <tr style="width: 100%;">
                                    <td style="padding: 0px;font-size: 10px;width: 69%;border-right: 1px solid; border-bottom: 1px solid">
                                        <input style="margin:2px;" type="radio" name="rubroI<?php echo $empleado['Empleado']['cuit'];?>"
                                               class="checkboxrubroI trabajadorjubilado menosde12" value="142.00"
                                               aportes="0" contribuciones="142">
                                        <label style="margin-top:2px;">- Menos de 12</label>
                                    </td>
                                    <td style="padding: 0px;width: 30%;text-align:center;border-bottom: 1px solid;font-size: 10px">
                                        $142.00
                                    </td>
                                </tr>
                                <tr style="width: 100%;">
                                    <td style="padding: 0px;font-size: 10px;width: 69%;border-right: 1px solid; border-bottom: 1px solid">
                                        <input style="margin:2px;" type="radio" name="rubroI<?php echo $empleado['Empleado']['cuit'];?>"
                                               class="checkboxrubroI trabajadorjubilado desde12a16" value="189.00"
                                               aportes="0" contribuciones="189">
                                        <label style="margin-top:2px;">- Desde 12 a menos 16</label>
                                    </td>
                                    <td style="padding: 0px;width: 30%;text-align:center;border-bottom: 1px solid;font-size: 10px">$189.00</td>
                                </tr>
                                <tr style="width: 100%;">
                                    <td style="padding: 0px;font-size: 10px;width: 69%;border-right: 1px solid">
                                        <input style="margin:2px;" type="radio" name="rubroI<?php echo $empleado['Empleado']['cuit'];?>"
                                               class="checkboxrubroI trabajadorjubilado 16omas" value="265.00"
                                               aportes="0" contribuciones="265">
                                        <label style="margin-top:2px;">- 16 o mas</label>
                                    </td>
                                    <td style="padding: 0px;width: 30%;text-align:center;font-size: 10px">$265.00</td>
                                </tr>
                            </table>
                        </div>
                        </td>
                        <td style="padding:0px;">
                        <div>
                            <div class="roundcorner" style="width: 99%; font-size: 10px; margin-bottom:2px;margin-top:2px">
                                <p style="display: inline;width: 29%;border-right: 1px solid">
                                    <b style="padding-right:10px">F.1028</b>
                                </p>
                                <label style="display: inline;width:60%; font-size: 10px; padding-left:4px">
                                    TRABAJADORES MENORES
                                </label>
                            </div>
                            <table cellspacing="0" class="roundcorner">
                                <tr style="width: 100%;border-bottom: 1px solid black">
                                    <td style="padding: 0px;font-size:6px; width:69%; border-right: 1px solid;text-align:center;border-bottom: 1px solid;">
                                        <div style="width: 100%;">HORAS TRABAJADAS SEMANALMENTE</div>
                                        <div style="font-size: 5px;width: 100%;padding-bottom:2px">
                                            (marcar con "X" lo que no corresponda)
                                        </div>
                                    </td>
                                    <td style="padding:0px;width:30%;font-size: 8px; text-align:center;border-bottom: 1px solid;">    Importe
                                    </td>
                                </tr>
                                <tr style="width: 100%;">
                                    <td style="padding: 0px;font-size: 10px;width: 69%;border-right: 1px solid;border-bottom: 1px solid;">
                                        <input style="margin:2px;" type="radio" name="rubroI<?php echo $empleado['Empleado']['cuit'];?>"
                                               class="checkboxrubroI trabajadormenores 16omas" value="164.00"
                                               aportes="34" contribuciones="130">
                                        <label style="margin-top:2px;">- Menos de 12</label>
                                    </td>
                                    <td style="padding: 0px;width: 30%;text-align:center;font-size: 10px;border-bottom: 1px solid;">(I)</td>
                                </tr>
                                <tr style="width: 100%;">
                                    <td style="padding: 0px;font-size: 10px;width: 69%;border-right: 1px solid;border-bottom: 1px solid;">
                                        <input style="margin:2px;" type="radio" name="rubroI<?php echo $empleado['Empleado']['cuit'];?>"
                                               class="checkboxrubroI trabajadormenores desde12a16" value="228.00"
                                               aportes="63" contribuciones="165">
                                        <label style="margin-top:2px;">- Desde 12 a menos 16</label>
                                    </td>
                                    <td style="padding: 0px;width: 30%;text-align:center;font-size: 10px;border-bottom: 1px solid;">(II)</td>
                                </tr>
                                <tr style="width: 100%;">
                                    <td style="padding: 0px;font-size: 10px;width: 69%;border-right: 1px solid">
                                        <input style="margin:2px;" type="radio" name="rubroI<?php echo $empleado['Empleado']['cuit'];?>"
                                               class="checkboxrubroI trabajadormenores 16omas" value="649.00"
                                               aportes="419" contribuciones="230">- 16 o mas</td>
                                    <td style="padding: 0px;width: 30%;text-align:center;font-size: 10px">(III)</td>
                                </tr>
                            </table>
                        </div>
                        </td>
                    </tr>
                </table>
                </form>
            </td>
		</tr>
        <tr class="row" style="padding: 0px">
            <td style="padding: 0px;width:40%">                
                <div style="font-size: 8px; width: 100%; text-align:right">
                    <b>(*) Importe hasta el 05/16 $161 - Desde el 06/16 $176</b>
                </div>
                <div style="font-size: 8px; width: 100%; text-align:right">
                    <b>(**) Importe hasta el 05/16 $224 - Desde el 06/16 $252</b>
                </div>
                <div style="font-size: 8px; width: 100%; text-align:right">
                    <b>(**) Importe hasta el 05/16 $498 - Desde el 06/16 $684</b>
                </div>               
            </td>            
            <td  style="padding: 0px;width:60%">                
                <div style="font-size: 8px; width: 100%; text-align:right;">
                    <b style="padding-right:20px">(I)Importe hasta el 05/16 $149 - Desde el 06/16 $164</b>
                </div>
                <div style="font-size: 8px; width: 100%; text-align:right;">
                    <b style="padding-right:20px">(II)Importe hasta el 05/16 $200 - Desde el 06/16 $228</b>
                </div>
                <div style="font-size: 8px; width: 100%; text-align:right;">
                    <b style="padding-right:20px">(III)Importe hasta el 05/16 $463 - Desde el 06/16 $649</b>
                </div>                
            </td>
        </tr>
        <tr class="row">
            <!--<td style="padding: 0px; width:100%">-->
            <table class="roundcorner">
                <td  style="padding: 0px; width:60%">
                    <div style="width:100%;text-align: left" class="" >
                        <label style="font-size: 10px; margin-bottom:1px">IMPORTE DE LA OBLIGACIÓN MENSUAL</label>
                        <label style="font-size: 8px; margin-bottom:0px">
                            (TRANSCRIBA EL IMPORTE CORRESPONDIENTE A LA CANTIDAD DE HORAS TRABAJADOS)
                        </label>
                    </div>
                </td>
                <td  style="padding: 0px;width:30%">
                    <table style="font-size: 12px;" class="roundcorner" >
                        <tr >
                            <td style="padding: 0px;width: 30%;text-align: left">
                               $
                            </td>
                            <td style="padding: 0px;width: 70%;text-align: right">
                            <b>
                                <p id="obligacionmensual<?php echo $empleado['Empleado']['id']?>" class="obligacionmensual">684.00</p>
                            </b>
                            </td>
                        </tr>
                    </table>
                </td>
            </table>
            <!--</td>-->
        </tr>
        <!-- FIN RUBRO I -->
    </table>    
    <!-- RUBRO II y RUBRO III -->
    <table style="margin-bottom:0px">
        <tr class="row">
            <td class="roundcorner" style="padding: 0px; width:50%">
                <div style="font-size: 10px; width:99%;float:left;text-align:left">
                    <b style="padding-left:10px">RUBRO II - </b>INGRESO DE INTERESES RESARCITORIOS
                </div>
            </td>
            <td class="roundcorner" style="padding: 0px;width:50%">
                <div style="font-size: 10px; width:99%;float:right;text-align:left">
                    <b style="padding-left:10px">RUBRO III - </b>INGRESO DE UNTERESES CAPITALIZABLES
                </div>
            </td>
        </tr>
    </table>
    <table style="margin-bottom:0px;padding:0px">
        <tr class="row">
            <td style="padding:0px;width:50%">
                <table class="roundcorner" style="margin-bottom:0px;padding:0px">
                    <tr>
                        <td style="padding:0px;font-size:10px;border-right: 1px solid;vertical-align:middle">
                            <b>F.1029</b>
                        </td>
                        <td style="padding:0px;text-align:center;border-right: 1px solid">
                            <label style="margin-bottom:0px;font-size:8px">CONDICIÓN</label>
                            <p style="font-size:6px;">(marcar con "X" la condición)</p>
                        </td>
                        <td style="padding:0px;vertical-align:middle">
                            <table style="margin-bottom:0px;">
                                <tr>
                                    <td style="padding:0px;">
                                        <input type="radio" style="margin:0px;">
                                    </td>
                                    <td style="padding:0px;">
                                        <label style="padding-bottom:0px;padding-top:2px;font-size:8px">ACTIVO</label>
                                    </td>
                                    <td style="padding:0px;">
                                        <input type="radio" style="margin:0px;" >
                                    </td>
                                    <td style="padding:0px;font-size: 8px">
                                        <label style="padding-bottom:0px;padding-top:2px;font-size:8px">JUBILADO</label>
                                    </td>
                                    <td style="padding:0px;">
                                        <input type="radio" style="margin:0px;" >
                                    </td>
                                    <td style="padding:0px;font-size: 8px">
                                        <label style="padding-bottom:0px;padding-top:2px;font-size:8px">MENOR</label>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>                
            </td>
            <td style="padding: 0px;width:50%">
                <table class="roundcorner" style="margin-bottom:0px;padding:0px">
                    <tr>
                        <td style="padding:0px;font-size:10px;border-right: 1px solid;vertical-align:middle">
                            <b>F.1030</b>
                        </td>
                        <td style="padding:0px;text-align:center;border-right: 1px solid">
                            <label style="padding-bottom:0px;font-size:8px">CONDICIÓN</label>
                            <p style="font-size:6px;">(marcar con "X" la condición)</p>
                        </td>
                        <td style="padding:0px;vertical-align:middle">
                            <table style="margin-bottom:0px;">
                                <tr>
                                    <td style="padding:0px;">
                                        <input type="radio" style="margin:0px;">
                                    </td>
                                    <td style="padding:0px;">
                                        <label style="padding-bottom:0px;padding-top:2px;font-size:8px">ACTIVO</label>
                                    </td>
                                    <td style="padding:0px;">
                                        <input type="radio" style="margin:0px;" >
                                    </td>
                                    <td style="padding:0px;font-size: 8px">
                                        <label style="padding-bottom:0px;padding-top:2px;font-size:8px">JUBILADO</label>
                                    </td>
                                    <td style="padding:0px;">
                                        <input type="radio" style="margin:0px;" >
                                    </td>
                                    <td style="padding:0px;font-size: 8px">
                                        <label style="padding-bottom:0px;padding-top:2px;font-size:8px">MENOR</label>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>                
            </td>
        </tr>
    </table>   
    <table style="margin-bottom:0px;padding:0px"> 
        <tr class="row">
            <td style="padding: 0px; width:50%">
                <table class="roundcorner" cellspacing="0" style="margin-bottom:0px;padding:0px;width:100%">
                    <tr>
                        <td style="padding:0px;width:70%;text-align:center;border-right:1px solid;border-bottom:1px solid">
                            <label style="margin-bottom:0px;font-size: 8px">HORAS TRABAJADAS SEMANALMENTE</label>
                            <p style="font-size: 6px;">(marcar con "X" la condición)</p>
                        </td>
                        <td style="padding:0px;width:30%;text-align:center;border-bottom:1px solid">
                            <label style="margin-bottom:0px;padding-top:2px;font-size: 10px">Importe</label>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0px;width:70%;border-right:1px solid;border-bottom:1px solid">
                            <input type="radio" style="margin:2px;">
                            <label style="padding-top:2px;font-size: 10px">- Menos de 12</label>
                        </td>
                        <td style="padding:0px;width:30%;border-bottom:1px solid">
                            &nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0px;width:70%;border-right:1px solid;border-bottom:1px solid">
                           <input type="radio" style="margin:2px;" >
                            <label style="padding-top:2px;font-size: 10px">- Desde 12 a menos de 16</label>
                        </td>
                        <td style="padding:0px;width:30%;border-bottom:1px solid">
                            &nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0px;width:70%;border-right:1px solid">
                            <input type="radio" style="margin:2px;" >
                            <label style="padding-top:2px;font-size: 10px">- 16 o más</label>
                        </td>
                        <td style="padding:0px;width:30%;">
                            &nbsp;
                        </td>
                    </tr>
                </table>                        
            </td>
            <td style="padding: 0px;width:50%">
                <table class="roundcorner" cellspacing="0" style="margin-bottom:0px;padding:0px;width:100%">
                    <tr>
                        <td style="padding:0px;width:70%;text-align:center;border-right:1px solid;border-bottom:1px solid">
                            <label style="margin-bottom:0px;font-size: 8px">HORAS TRABAJADAS SEMANALMENTE</label>
                            <p style="font-size: 6px;">(marcar con "X" la condición)</p>
                        </td>
                        <td style="padding:0px;width:30%;text-align:center;border-bottom:1px solid">
                            <label style="margin-bottom:0px;padding-top:2px;font-size: 10px">Importe</label>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0px;width:70%;border-right:1px solid;border-bottom:1px solid">
                            <input type="radio" style="margin:2px;">
                            <label style="padding-top:2px;font-size: 10px">- Menos de 12</label>
                        </td>
                        <td style="padding:0px;width:30%;border-bottom:1px solid">
                            &nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0px;width:70%;border-right:1px solid;border-bottom:1px solid">
                           <input type="radio" style="margin:2px;" >
                            <label style="padding-top:2px;font-size: 10px">- Desde 12 a menos de 16</label>
                        </td>
                        <td style="padding:0px;width:30%;border-bottom:1px solid">
                            &nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0px;width:70%;border-right:1px solid">
                            <input type="radio" style="margin:2px;" >
                            <label style="padding-top:2px;font-size: 10px">- 16 o más</label>
                        </td>
                        <td style="padding:0px;width:30%;">
                            &nbsp;
                        </td>
                    </tr>
                </table>
            </td>
        </tr>        
    </table>
    <!-- FIN RUBRO II y RUBRO III -->            
    <!-- RUBRO IV -->
    <table style="margin-bottom:0px">
        <tr class="row">
            <td class="roundcorner" style="padding: 0px; width:100%">
                <div style="font-size: 10px; width:99%;text-align:left">
                    <b style="padding-left:10px">RUBRO IV - </b>COBERTURA ASEGURADORA RIESGOS DE TRABAJO
                </div>
            </td>            
        </tr>
        <tr>
            <td class="roundcorner" style="padding: 0px; width:100%">
                <div style="font-size: 10px; width:99%;">
                    <div style="font-size: 10px; width:20%; float:left;text-align: left;">
                        <label style="padding-left:10px; margin-bottom:0px; margin-top:2px">
                            CUIT/CUIL EMPLEADOR:
                        </label>
                    </div>
                    <div style="font-size: 10px; width:80%; float:left;text-align: left">
                        <label style="padding-left:10px; margin-bottom:0px; margin-top:2px">
                        <?php
                        $identificacionnumeroEmpleador = $cliente['Cliente']['cuitautorizada']!=""?$cliente['Cliente']['cuitautorizada']:$cliente['Cliente']['cuitcontribullente'];
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
                        </label>
                    </div>
                </div>
            </td>  
        </tr>
        <tr class="row">
            <td style="padding: 0px; width:100%">
                <hr style="border-bottom: dashed 1px;width: 100%" />
            </td>
        </tr>
    </table>
    <!-- FIN RUBRO IV -->
    
    <!-- RECIBO DE PAGO -->
    <table style="margin-bottom:0px">
        <tr class="row">
            <td style="padding: 0px; width:30%; text-align:right">
                <b style="font-size: 12px">RECIBO DE PAGO </b>                
            </td>
            <td style=" padding: 0px; width:70%">
                <label style="font-size: 8px; margin-bottom:0px; margin-top:2px; margin-left: 5px">
                    (Imprimir por duplicado y entregar una copia al trabajador)
                </label>
            </td>
        </tr>
    </table>

    <table style="margin-bottom:0px" class="roundcorner">
        <tr style="row">
            <td style="padding:0px;width:50%;">
                <div style="width:100%;text-align:left">
                    <label style="margin-bottom:0px; margin-left: 5px; font-size: 10px">
                        Empleador (Apellido y Nombre):
                    </label>
                </div>
                <div style="width:100%;text-align:left">
                    <label style="margin-bottom:0px; margin-left: 5px;font-size: 10px">
                    <?php
                        echo  $cliente['Cliente']['nombre'];
                    ?>
                    </label>
                </div>
                <div style="width:100%;text-align:left">
                    <label style="margin-bottom:0px; margin-left: 5px;font-size: 10px">
                    Domicilio de trabajo:
                    </label>
                </div>
                <div style="width:100%;text-align:left">
                    <label style="margin-bottom:0px; margin-left: 5px;font-size: 10px">
                        <?php
                        echo $empleado['Domicilio']['calle'];
                        ?>
                    </label>
                </div>                
            </td>
            <td style="padding:0px;width:50%;">
                <table style="margin-bottom:0px;width:80%">
                    <tr>
                        <td style="padding: 0px;text-align:right;width:50%">
                            <label style="margin-bottom:0px;font-size: 12px">
                            CUIT/CUIL:
                            </label>
                        </td>
                        <td style="padding:0px;width:50%">
                            <label style="margin-bottom:0px;margin-left:5px;font-size: 12px">
                            <?php
                                echo $cuitEmpleador;
                            ?>
                            </label>
                        </td>
                    </tr>                    
                </table>                
            </td>
        </tr>
    </table>

    <table class="roundcorner" style="margin-top:2px">
        <tr class="row">
            <td style="padding:0px;width:50%;">
                <div style="width:100%;text-align:left">
                    <label style="margin-bottom:0px; margin-left: 5px; font-size: 10px">
                        Trabajador (Apellido y Nombre):
                    </label>
                </div>
                <div style="width:100%;text-align:left">
                    <label style="margin-bottom:0px; margin-left: 5px;font-size: 10px">
                    <?php
                        echo $empleado['Empleado']['nombre'];
                    ?>
                    </label>
                </div>
<!--                <div style="width:100%;text-align:left">-->
<!--                    <label style="margin-bottom:0px; margin-left: 5px;font-size: 10px">-->
<!--                    Domicilio de trabajo:-->
<!--                    </label>-->
<!--                </div>-->
<!--                <div style="width:100%;text-align:left">-->
<!--                    <label style="margin-bottom:0px; margin-left: 5px;font-size: 10px">-->
<!--                    --><?php
//                        echo $empleado['Domicilio']['calle'];
//                    ?>
<!--                    </label>-->
<!--                </div>-->
            </td>
            <td style="padding:0px;width:50%;">
                <table style="margin-bottom:0px; width:80%">
                    <tr>
                        <td style="padding: 0px;text-align:right; width:50%">
                            <label style="margin-bottom:0px;font-size: 12px">
                            CUIT/CUIL:
                            </label>
                        </td>
                        <td style="padding:0px;width:50%">
                            <label style="margin-bottom:0px;margin-left:5px;font-size: 12px">
                            <?php
                                echo $cuit;
                            ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0px;text-align:right;width:50%">
                            <label style="font-size: 10px;margin-top:25px; margin-bottom:0px">Fecha de Ingreso:</label>
                        </td>
                        <td style="padding: 0px;width:50%">
                            <table cellspacing="0" class="tbl_border" style="margin-bottom:0px">
                            <tr>                                
                                <td style="font-size:10px;text-align:center">DIA</td>
                                <td style="font-size:10px;text-align:center">MES</td>
                                <td style="font-size:10px;text-align:center">AÑO</td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;text-align:center;"><?php
                                    echo date('d',strtotime($empleado['Empleado']['fechaalta']));
                                    ?>
                                </td>
                                <td style="font-size:10px;text-align:center"><?php
                                    echo date('m',strtotime($empleado['Empleado']['fechaalta']));
                                    ?>
                                </td>
                                <td style="font-size:10px;text-align:center"><?php
                                    echo date('Y',strtotime($empleado['Empleado']['fechaalta']));
                                    ?>
                                </td>
                            </tr>
                        </table>
                        </td>
                    </tr>
                </table>                
            </td>
        </tr>
    </table>

    <table class="roundcorner" style="margin-top:2px;">
        <tr class="row">
            <td style="padding:0px;">
                <div style="padding-left:5px;font-size: 10px; text-align:left">
                Detalle del período:<input type="text" maxlength="50" style="display: inline; width:60%">
                </div>
            </td>
            <td style="padding:0px;font-size: 10px">
                <div style="padding-left:5px;font-size: 10px; text-align:left">
                Puesto desempeñado:<input type="text"  maxlength="50" style="display: inline;width:60%">
                </div>
            </td>
        </tr>
        <tr>
            <td style="padding:0px;font-size: 10px;border-bottom: 1px solid">
                <div style="padding-left:5px;text-align:left; margin-bottom:5px">
                    Desde:
                    <table cellspacing="0" class="tbl_border" style="display: inline; margin-left:5px">
                        <tr>
                            <td style="font-size:10px;text-align:center">DIA</td>
                            <td style="font-size:10px;text-align:center">MES</td>
                            <td style="font-size:10px;text-align:center">AÑO</td>
                        </tr>
                        <tr>
                            <td style="font-size:10px;text-align:center">01
                            </td>
                            <td style="font-size:10px;text-align:center"><?php
                                echo $pemes
                                ?>
                            </td>
                            <td style="font-size:10px;text-align:center"><?php
                                echo $peanio
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <td style="padding:0px;font-size: 10px;border-bottom: 1px solid">
                <div style="padding-left:5px;text-align:left;margin-bottom:5px">
                    Hasta:
                    <table cellspacing="0" class="tbl_border" style="display: inline; margin-left:5px">
                        <tr>
                            <td style="font-size:10px;text-align:center">DIA</td>
                            <td style="font-size:10px;text-align:center">MES</td>
                            <td style="font-size:10px;text-align:center">AÑO</td>
                        </tr>
                        <tr>
                            <td style="font-size:10px;text-align:center">
                                <?php
                                echo date('t',strtotime('01-'.$pemes.'-'.$peanio));
                                ?>
                            </td>
                            <td style="font-size:10px;text-align:center">
                                <?php
                                echo $pemes
                                ?>
                            </td>
                            <td style="font-size:10px;text-align:center">
                                <?php
                                echo $peanio
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td style="padding: 0px;" colspan="2">
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
                <table style="margin-bottom: 0px; width:100%">
                    <tr>
                        <td style="padding: 0px;font-size:10px">
                            Modalidad de Liquidacion:
                        </td>
                        <td style="padding: 0px; font-size:10px">
                            Diaria                                
                        </td>                            
                        <td style="padding: 0px;">
                            <input type="checkbox" style="margin:0px" name="modalidadliquidacion">
                        </td>
                        <td style="padding: 0px; font-size:10px">                                
                            Quincenal
                        </td>
                        <td style="padding: 0px;">
                            <input type="checkbox" style="margin:0px" name="modalidadliquidacion" >
                        </td>
                        <td style="padding: 0px; font-size:10px">
                            Otras                            
                        </td>
                        <td style="padding: 0px;">
                            <input type="checkbox" style="margin:0px" name="modalidadliquidacion" >
                        </td>
                        <td style="padding: 0px; text-align:right; font-size:10px">
                            <label style="margin-bottom:0px; margin-top:2px">Cantidad de Horas: &nbsp;</label>
                        </td>
                        <td style="padding: 0px;">                                
                            <?php
                            $horastrabajadas = 0;
                            if(isset($valores['12'])) {//Días Trabajados u Horas
                                $horastrabajadas+=$valores['12']['valor']*8;
                            }
                            if(isset($valores['140'])) {//Horas Decoracion(horas trabajadas)
                                $horastrabajadas+=$valores['140']['valor']*8;
                            }
                            ?>
                            <input type="text" class="cantHoras" id="cantidadHoras<?php echo $empleado['Empleado']['id']?>"  maxlength="3" value="<?php echo $horastrabajadas ?>" style="width: 40px;" >
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0px;">
                            &nbsp;
                        </td>
                        <td style="padding: 0px;font-size:10px">
                            Semanal
                        </td>                            
                        <td style="padding: 0px;">
                            <input type="checkbox" style="margin:0px" name="modalidadliquidacion" >
                        </td>
                        <td style="padding: 0px;font-size:10px">                                
                            Mensual 
                        </td>
                        <td style="padding: 0px;">
                            <input type="checkbox" style="margin:0px" name="modalidadliquidacion" >
                        </td>
                        <td style="padding: 0px;font-size:10px">
                            Descripción:
                        </td>
                        <td style="padding: 0px;" colspan="3">
                            <input type="text" style="width: 90%;">
                        </td>                        
                    </tr>
                </table>                
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 0px">
                <table class="roundcorner">
                    <tr>
                        <td style="padding: 0px;border-right:1px solid; width:50%">
                            <table style="margin-bottom:0px">
                                <tr style="">
                                    <td colspan="2" style="padding:0px;font-size:10px;text-align:center;border-bottom:1px solid">
                                        Remuneracion:
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0px;font-size:10px;width:60%">
                                        Basico:
                                    </td>
                                    <td style="padding: 0px;font-size:10px;width:40%">
                                        $<?php
                                        if(isset($valores['6'])) {//Sueldo Basico
                                            echo  '<p id="sueldoBasico" class="sueldoBasico" style="display: initial;">'.
                                                number_format($valores['6']['valor'], 2, ",", ".").
                                                '</p>';
                                        }else{
                                            echo  '<p id="sueldoBasico" class="sueldoBasico" style="display: initial;"></p>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0px;font-size:10px;width:60%">
                                        S.A.C:
                                    </td>
                                    <td style="padding: 0px;font-size:10px;width:40%">
                                        $<?php
                                        if(isset($valores['92'])) {//S.A.C. Remunerativo
                                            echo  '<p id="SAC" class="SAC" style="display: initial;">'.
                                                number_format($valores['92']['valor'], 2, ",", ".").
                                                '</p>';
                                        }else{
                                            echo  '<p id="SAC" class="SAC" style="display: initial;"></p>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0px;font-size:10px;width:60%">
                                        Vacaciones:
                                    </td>
                                    <td style="padding: 0px;font-size:10px;width:40%">
                                        $<?php
                                        if(isset($valores['20'])) {//Vacaciones Remunerativas
                                            echo  '<p id="vacaciones" class="vacaciones" style="display: initial;">'.
                                                number_format($valores['20']['valor'], 2, ",", ".").
                                                '</p>';
                                        }else{
                                            echo  '<p id="vacaciones" class="vacaciones" style="display: initial;"></p>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0px;font-size:10px;width:60%">
                                        Otros conceptos:                            
                                    </td>
                                    <td style="padding: 0px;font-size:10px;width:40%">

                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0px;font-size:10px;width:60%">
                                        Aportes:
                                    </td>
                                    <td style="padding: 0px;font-size:10px;width:40%">
                                        <?php
                                        if(isset($valores['40'])) {//Total Aportes
                                            echo  '<p id="aportes" class="aportes">$-'
                                                .number_format($valores['40']['valor'], 2, ",", ".").
                                                '</p>';
                                        }else{
                                            echo  '<p id="aportes" class="aportes">$0</p>';
                                        }?>
                                    </td>
                                </tr>
                                <?php
                                if(
                                    isset($valores['165'])
                                    &&
                                    (($valores['165']['valor']*1)>0)
                                ) { ?>
                                <tr>
                                    <td style="padding: 0px;font-size:10px;width:60%">                            
                                        Adicional:
                                    </td>
                                    <td style="padding: 0px;font-size:10px;width:40%">                                
                                        <?php
                                        if(isset($valores['165'])) {//Adicional
                                            echo  '<p id="adicional" class="adicional" style="display: initial;">'.
                                                number_format($valores['165']['valor'], 2, ",", ".").
                                                '</p>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                    <?php } else{
                                    echo  '<p id="adicional" class="adicional" style="display: none;"></p>';
                                }?>
                                <?php
                                if(
                                    isset($valores['105'])
                                    &&
                                    (($valores['105']['valor']*1)>0)
                                ) { ?>
                                    <tr>
                                        <td style="padding: 0px;font-size:10px;width:60%">
                                            Vacaciones no gozadas:
                                        </td>
                                        <td style="padding: 0px;font-size:10px;width:40%">
                                            <?php
                                            if(isset($valores['105'])) {//Vacaciones no gozadas
                                                echo  '<p id="vacacionesnogozadas" class="vacacionesnogozadas" style="display: initial;">'.
                                                    number_format($valores['105']['valor'], 2, ",", ".").
                                                    '</p>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php }else{
                                    echo  '<p id="vacacionesnogozadas" class="vacacionesnogozadas" style="display: none;"></p>';
                                } ?>
                                <tr>
                                    <td style="padding: 0px;font-size:10px;width:60%">
                                        Suma Total
                                    </td>
                                    <td style="padding: 0px;font-size:10px;width:40%">
                                        $
                                        <?php
                                        if(isset($valores['46'])) {//Sueldo
                                            echo  '<p id="sueldoTotal" class="sueldoTotal" style="display: initial;">'.
                                                number_format($valores['46']['valor'], 2, ",", ".").
                                                '</p>';
                                        }else{
                                            echo  '<p id="sueldoTotal" class="sueldoTotal" style="display: initial;">0</p>';
                                        }?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="padding: 0px; width:50%">
                            <table style="margin-bottom:0px">
                                <tr>
                                    <td colspan="2" style="padding: 0px;font-size:10px;">
                                        Son Pesos:
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding:0px;font-size:10px;">
                                        <?php
                                        if(isset($valores['46'])) {//Neto
                                            echo num2letras(number_format($valores['46']['valor'], 2, ".", "")) .".-";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding:0px;font-size:10px;">
                                        &nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding: 0px;font-size:10px;">
                                        Lugar y Fecha:
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding:0px;font-size:10px;">
                                        <?php $mesesN=array(1=>"Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
                                            "Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                                        $fechaPago = strtotime('05-'.$pemes."-".$peanio." +1 months");
                                        ?>
                                        Salta,  <?php echo "05 de ".$mesesN[date('n',$fechaPago)]." de ".date('Y',$fechaPago);?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding:0px;font-size:10px;">
                                        &nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding: 0px;font-size:10px;">
                                        N° de comprobante de pago de aportes y contribuciones(*):
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding:0px;font-size:10px;">
                                        <br>______________________________
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>                        
        </tr>
        <tr>
            <td class="roundcorner" style="width:49%;height: 70px;padding: 0px;text-align-left">
                <label style="padding-top:5px; padding-left:5px;">
                    Firma del Empleador:
                </label>
            </td>
            <td class="roundcorner" style="width:49%;height: 70px;padding: 0px;text-align-left">
                <label style="padding-top:5px; padding-left:5px;">
                    Firma del Trabajador:
                </label>
            </td>
        </tr>
    </table>
    <!-- FIN RECIBO DE PAGO -->      
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