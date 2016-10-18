<?php
    echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
    echo $this->Form->input('empidPDT',array('value'=>$empid,'type'=>'hidden'));
?>
    <div id="sheetCooperadoraAsistencial" class="index" style="margin: 10px 25px;">
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
        }
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


        foreach ($empleado['Valorrecibo'] as $valorrecibo) {
            //Basico
            if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='52'/*Total basicos*/){
                $basico = $valorrecibo['valor']; // este no se deberia acumular
            }
            //Sueldo
            if ($valorrecibo['Cctxconcepto']['Concepto']['id']=='21'/*Total basicos*/){
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
        ?>
        <table id="tblLibroSueldo" class="tblInforme" cellspacing="0">
            <tr><td>Periodo: <?php echo $periodo ?></td></tr>
            <tr>
                <td>LIBRO DE SUELDOS - LEY 20744 t.c. Art.52 - Hojas moviles</td>
                <td colspan="2"><?php echo $this->Form->input('hoja')?> </td>
                <td colspan="2"><?php echo $this->Form->input('tomo')?> </td>
            </tr>
            <tr>
                <td colspan="20">
                    EMPRESA: <?php echo $empleado['Cliente']['nombre']; ?>
                    ACTIVIDAD : <?php
                        foreach ($empleado['Cliente']['Actividadcliente'] as $actividad){
                            echo $actividad['Actividade']['nombre'];
                        }  ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Domicilio: <?php echo $empleado['Domicilio']['calle']; ?>
                </td>
                <td colspan="2">
                    CUIT: <?php echo $empleado['Cliente']['cuitcontribullente']; ?>
                </td>
            </tr>
            <tr>
                <td colspan = "20" >
                    L.P.: <?php echo $empleado['Empleado']['nombre'] ?>
                    CUIL <?php echo $empleado['Empleado']['cuit'] ?>
                </td>
            </tr><!--5-->
            <tr>
                <td></td>
                <td>
                    * <?php echo $empleado['Domicilio']['Localidade']['Partido']['nombre'] ?>
                </td>
                <td>
                    DU N° <?php echo $empleado['Empleado']['dni'] ?>
                </td>
                <td>
                    Cargo <?php echo $empleado['Empleado']['categoria'] ?>
                </td>
            </tr><!--6-->
            <tr>
                <td>F. Nacimiento  <?php echo $empleado['Empleado']['nacimiento']; ?></td>
                <td>F. Ingreso  <?php echo $empleado['Empleado']['fechaingreso']; ?></td>
                <td>F. Baja  <?php echo $empleado['Empleado']['fechaegreso']; ?></td>
                <td>
                    Basico <?php echo $miempleado['basico']; ?>
                </td>
            </tr><!--7-->
            <tr>
                <td>MODALIDAD DE CONTRATACION:  <?php echo $empleado['Empleado']['codigoafip']; ?></td>
            </tr><!--8-->
            <tr>
                <td colspan="20"> <hr width="450px" class="dottedhr" /></td>
            </tr><!--9-->
            <tr>
                <td>
                    CODIGO
                </td>
                <td >
                    HABERES CON/SIN APORTE
                </td>
                <td>
                    IMPORTE
                </td>
                <td>
                    CODIGO
                </td>
                <td>
                    DESCUENTOS
                </td>
                <td>
                    IMPORTE
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
                    SUELDO MENSUAL
                </td>
                <td>
                    <?php echo $miempleado['basico']; ?>
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
                    ANTIGÜEDAD
                </td>
                <td>
                    <?php echo $miempleado['antiguedad']; ?>
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
                    PRESENTISMO BASICO
                </td>
                <td>
                    <?php echo $miempleado['presentismo']; ?>
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
                        JUBILACION
                    </td>
                    <td>
                        <?php echo $miempleado['jubilacion']; ?>
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
                        LEY 19032-INSSJP
                    </td>
                    <td>
                        <?php echo $miempleado['ley19032']; ?>
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
                    <td>
                        <?php echo $miempleado['obrasocial']; ?>
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
                    <td>
                        <?php echo $miempleado['obrasocialextraordinario']; ?>
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
                    <td>
                        <?php echo $miempleado['cuotasindical']; ?>
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
                    <td>
                        <?php echo $miempleado['cuotasindical1']; ?>
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
                    <td>
                        <?php echo $miempleado['cuotasindical2']; ?>
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
                    <td>
                        <?php echo $miempleado['cuotasindical3']; ?>
                    </td>
                </tr>
            <?php }
            if($miempleado['redondeo']*1>0){ ?>
                <tr>
                    <td>980</td>
                    <td>
                        REDONDEO
                    </td>
                    <td>
                        <?php echo $miempleado['redondeo']; ?>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            <?php } ?>
                <tr>
                    <td>990</td>
                    <td>
                        TOTAL REMUNERACIONES
                    </td>
                    <td>
                        <?php echo $totalremuneracion; ?>
                    </td>
                    <td>990</td>
                    <td>
                        TOTAL DESCUENTOS
                    </td>
                    <td>
                        <?php echo $totaldescuento; ?>
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
                        NETO:
                    </td>
                    <td>
                        <?php echo $neto; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="20"> <hr width="450px" class="dottedhr" /></td>
                </tr>
                <tr>
                    <td>991</td>
                    <td>
                        T.HABERES C/APORTES
                    </td>
                    <td>
                        <?php echo $neto; ?>
                    </td>
                    <td>991</td>
                    <td>
                        T.RETEN. DE LEY
                    </td>
                    <td>
                        <?php echo $totaldescuento; ?>
                    </td>
                </tr>
                <tr>
                    <td>990</td>
                    <td>
                        TOTAL REMUNERACIONES
                    </td>
                    <td>
                        <?php echo $remuneracioncd; ?>
                    </td>
                    <td></td>
                    <td>
                    </td>
                    <td>
                    </td>
                </tr>
            </table>
        <?php //Debugger::dump($empleadoDatos);?>
</div>
