<?php
/**
 * Created by PhpStorm.
 * User: caesarius
 * Date: 20/02/2017
 * Time: 02:45 PM
 */?>
<div class="index" style="padding: 0px 1%; margin-bottom: 10px;" id="headerCliente">
    <div style="width:30%; float: left;padding-top:10px">
        Cliente: <?php echo $cliente["Cliente"]['nombre'];
        echo $this->Form->input('clientenombre',['type'=>'hidden','value'=>$cliente["Cliente"]['nombre']]);?>
    </div>
    <div style="width:25%; float: left;padding-top:10px">
        Periodo: <?php echo $periodo;
        echo $this->Form->input('periododefault',['type'=>'hidden','value'=>$periodo]);
        $añoPeriodo=substr($periodo, 3);
		$mesPeriodo=substr($periodo, 0, 2);?>
    </div>
</div>
<div class="index" style="padding: 0px 1%; margin-bottom: 10px;" >
    <?php
    //vamos a hacer un array con las ventas del año
    $misperiodos = [];
    $periodosAnio = [
        '01-'.$añoPeriodo,
        '02-'.$añoPeriodo,
        '03-'.$añoPeriodo,
        '04-'.$añoPeriodo,
        '05-'.$añoPeriodo,
        '06-'.$añoPeriodo,
        '07-'.$añoPeriodo,
        '08-'.$añoPeriodo,
        '09-'.$añoPeriodo,
        '10-'.$añoPeriodo,
        '11-'.$añoPeriodo,
        '12-'.$añoPeriodo,
    ];
    foreach ($periodosAnio as $periodoainicializar){
        $misperiodos[$periodoainicializar]['ventas']=[];
        $misperiodos[$periodoainicializar]['ventas']['neto']=0;
        $misperiodos[$periodoainicializar]['ventas']['iva']=0;
    }
    foreach ($ventas as $venta){
        $ventaperiodo = $venta['Venta']['periodo'];
        if(!isset($misperiodos[$ventaperiodo]['ventas'])){
            $misperiodos[$ventaperiodo]['ventas']=[];
            $misperiodos[$ventaperiodo]['ventas']['neto']=0;
            $misperiodos[$ventaperiodo]['ventas']['iva']=0;
        }
        if($venta['Comprobante']['tipodebitoasociado']=='Debito fiscal o bien de uso'){
            $misperiodos[$ventaperiodo]['ventas']['neto']+=$venta[0]['neto'];
            $misperiodos[$ventaperiodo]['ventas']['iva']+=$venta[0]['iva'];
        }else if($venta['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
            $misperiodos[$ventaperiodo]['ventas']['neto']-=$venta[0]['neto'];
            $misperiodos[$ventaperiodo]['ventas']['iva']-=$venta[0]['iva'];
        }
    }
    $misCBUs = [];
    foreach ($cliente['Impcli'] as $impcli){
        foreach ($impcli['Cbu'] as $cbu){
            $cbuname = $cbu['cbu']." ".$cbu['tipocuenta'];
            $misCBUs[] = $cbuname;
            foreach ($periodosAnio as $periodoainicializar){
                $misperiodos[$periodoainicializar][$cbuname]['movimientosbancarios']=[];
                $misperiodos[$periodoainicializar][$cbuname]['movimientosbancarios']['credito']=0;
                $misperiodos[$periodoainicializar][$cbuname]['movimientosbancarios']['creditodepurado']=0;
                $misperiodos[$periodoainicializar][$cbuname]['movimientosbancarios']['debito']=0;
            }
            foreach ($cbu['Movimientosbancario'] as $movimientosbancario){
                $movimientoperiodo = $movimientosbancario['periodo'];
                $misperiodos[$movimientoperiodo][$cbuname]['movimientosbancarios']['credito']+=$movimientosbancario['credito'];
                $misperiodos[$movimientoperiodo][$cbuname]['movimientosbancarios']['debito']+=$movimientosbancario['debito'];
                if($movimientosbancario['codigoafip']=='1'){
                    $misperiodos[$movimientoperiodo][$cbuname]['movimientosbancarios']['creditodepurado']+=$movimientosbancario['credito'];
                }
            }
        }
    }

    ?>
    <table>
        <tr>
            <td colspan="4">Ventas</td>
            <td colspan="<?php echo count($misCBUs)+2;?>">Cuentas sin depurar</td>
            <td colspan="<?php echo count($misCBUs)+2;?>">Cuentas depuradas</td>
        </tr>
        <tr>
            <td>Periodo</td>
            <td>Venta Neta</td>
            <td>IVA</td>
            <td>Total</td>
            <?php
            foreach ($misCBUs as $micbu){
                echo '
                <td colspan="">'.$micbu.'</td>';
            }
            ?>
            <td colspan="1">Total sin depurar</td>
            <td colspan="1">Diferencia</td>
            <?php
            foreach ($misCBUs as $micbu){
                echo '
                <td colspan="">'.$micbu.'</td>';
            }
            ?>
            <td colspan="1">Total depurado</td>
            <td colspan="1">Diferencia</td>
        </tr>
        <?php
        //primero tengo que ordenar los periodos de las ventas
        $styleGreen = "background-color: lightgreen;";
        $styleRed = "background-color: red;";
        foreach ($periodosAnio as $periodoAMostrar){
            $totalVentaPeriodo = $misperiodos[$periodoAMostrar]['ventas']['neto']+$misperiodos[$periodoAMostrar]['ventas']['iva'];
            echo "
            <tr>
                <td>".$periodoAMostrar."</td>
                <td>".$misperiodos[$periodoAMostrar]['ventas']['neto']."</td>
                <td>".$misperiodos[$periodoAMostrar]['ventas']['iva']."</td>
                <td>".$totalVentaPeriodo."</td>";
            $misperiodos[$periodoAMostrar]['totalacreditacionsindepurar']=0;
            $misperiodos[$periodoAMostrar]['totalacreditaciondepuradas']=0;
            foreach ($misCBUs as $micbu){
                $misperiodos[$periodoAMostrar]['totalacreditacionsindepurar']+=$misperiodos[$periodoAMostrar][$micbu]['movimientosbancarios']['credito'];
                echo "                
                <td>".$misperiodos[$periodoAMostrar][$micbu]['movimientosbancarios']['credito']."</td>";
            }
            $diferenciasindepurarperiodo = $totalVentaPeriodo - $misperiodos[$periodoAMostrar]['totalacreditacionsindepurar'];
            $styletd="";
            $styletd=($diferenciasindepurarperiodo>=0)?$styleGreen:$styleRed;

            echo"
                <td> ".$misperiodos[$periodoAMostrar]['totalacreditacionsindepurar']."</td>
                <td style='".$styletd."'> ".$diferenciasindepurarperiodo."</td>";
            foreach ($misCBUs as $micbu){
                $misperiodos[$periodoAMostrar]['totalacreditaciondepuradas']+=$misperiodos[$periodoAMostrar][$micbu]['movimientosbancarios']['creditodepurado'];
                echo "                
                <td>".$misperiodos[$periodoAMostrar][$micbu]['movimientosbancarios']['creditodepurado']."</td>";
            }
            $diferenciadepuradasperiodo = $totalVentaPeriodo - $misperiodos[$periodoAMostrar]['totalacreditaciondepuradas'];
            $styletd="";
            $styletd=($diferenciadepuradasperiodo>=0)?$styleGreen:$styleRed;
            echo"
                <td> ".$misperiodos[$periodoAMostrar]['totalacreditaciondepuradas']."</td>
                <td style='".$styletd."'> ".$diferenciadepuradasperiodo."</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
