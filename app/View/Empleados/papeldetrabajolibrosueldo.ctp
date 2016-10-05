<?php
    echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
    echo $this->Form->input('empidPDT',array('value'=>$empid,'type'=>'hidden'));
?>
    <div id="sheetCooperadoraAsistencial" class="index">
        <?php
        $empleadoDatos = array();
        $miempleado = array();
        if(!isset($miempleado['horasDias'])) {
            $miempleado['sueldo'] = 0;
            $miempleado['adicionales'] = 0;
            $miempleado['antiguedad'] = 0;
            $miempleado['presentismo'] = 0;
            $miempleado['otros'] = 0;
            $miempleado['jubilacion'] = 0;
            $miempleado['ley19032'] = 0;
            $miempleado['obrasocial'] = 0;
            $miempleado['obrasocialextraordinario'] = 0;
            $miempleado['cuotasindical'] = 0;
            $miempleado['cuotasindical1'] = 0;
            $miempleado['cuotasindical2'] = 0;
            $miempleado['cuotasindical3'] = 0;
            $miempleado['totalremuneracion'] = 0;
            $miempleado['totaldescuento'] = 0;
            $miempleado['neto'] = 0;
        }
        $jubilacion=0;
        $adicionales=0;
        $sueldo=0;
        $antiguedad = 0;
        $presentismo = 0;
        $ley19032 = 0;
        $otros = 0;
        $obrasocial = 0;
        $obrasocialextraordinario = 0;
        $cuotasindical = 0;
        $cuotasindical1 = 0;
        $cuotasindical2 = 0;
        $cuotasindical3 = 0;
        $totalremuneracion = 0;
        $totaldescuento = 0;
        $neto = 0;


        foreach ($empleado['Valorrecibo'] as $valorrecibo) {
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
            }
            //Obra Social Extraordinario
            if (
            in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                array('35'/*Obra Social Extraordinario*/), true )
            ){
                $obrasocialextraordinario += $valorrecibo['valor'];
            }
            //Cuota Sindical
            if (
            in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                array('36'/*Cuota Sindical*/), true )
            ){
                $cuotasindical += $valorrecibo['valor'];
            }

            //Cuota Sindical 1
            if (
            in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                array('37'/*Cuota Sindical 1*/), true )
            ){
                $cuotasindical1 += $valorrecibo['valor'];
            }

            //Cuota Sindical 2
            if (
            in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                array('38'/*Cuota Sindical 2*/), true )
            ){
                $cuotasindical2 += $valorrecibo['valor'];
            }
            //Cuota Sindical 3
            if (
            in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                array('114'/*Cuota Sindical 3*/), true )
            ){
                $cuotasindical3 += $valorrecibo['valor'];
            }

            //Total Remunerativos
            if (
            in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                array('44'/*Cuota Sindical 3*/), true )
            ){
                $totalremuneracion += $valorrecibo['valor'];
            }
            //Total Aportes
            if (
            in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                array('45'/*Cuota Sindical 3*/), true )
            ){
                $totaldescuento += $valorrecibo['valor'];
            }
            //Neto
            if (
            in_array($valorrecibo['Cctxconcepto']['Concepto']['id'],
                array('46'/*Cuota Sindical 3*/), true )
            ){
                $neto += $valorrecibo['valor'];
            }
        }

        $miempleado['sueldo']=$sueldo;
        $miempleado['adicionales']=$adicionales;
        $miempleado['antiguedad']=$antiguedad;
        $miempleado['presentismo']=$presentismo;
        $miempleado['jubilacion']=$jubilacion;
        $miempleado['ley19032']=$ley19032;
        $miempleado['obrasocial']=$obrasocial;
        $miempleado['obrasocialextraordinario']=$obrasocialextraordinario;
        $miempleado['cuotasindical']=$cuotasindical;
        $miempleado['cuotasindical1']=$cuotasindical1;
        $miempleado['cuotasindical2']=$cuotasindical2;
        $miempleado['cuotasindical3']=$cuotasindical3;
        $miempleado['totalremuneracion']=$totalremuneracion;
        $miempleado['totaldescuento']=$totaldescuento;
        $miempleado['neto']=$neto;
        ?>
        <table id="tblLibroSueldo" class="tblInforme tbl_border" cellspacing="0">
            <tr><td>Periodo: <?php echo $periodo ?></td></tr>
            <tr>
                <td>LIBRO DE SUELDOS - LEY 20744 t.c. Art.52 - Hojas moviles</td>
                <td>Hoja</td>
                <td><?php echo $this->Form->input('hoja')?> </td>
                <td>Tomo</td>
                <td><?php echo $this->Form->input('tomo')?> </td>
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
                    Domicilio: <?php echo $empleado['Cliente']['Domicilio'][0]['calle']; ?>
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
            </tr><!--1-->

        </table>
        <?php //Debugger::dump($empleadoDatos);?>
</div>
