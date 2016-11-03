<div id="divDDJJ" class="index">
    <table>
        <tr>
            <td colspan="20">
                DDJJ Infomariva Monotributo</td>
        </tr>
        <tr>
            <td colspan="20">
                Datos a Informar</td>
        </tr>
        <tr>
            <td colspan="20">Corresponderá que se informen, respecto de cada cuatrimestre calendario, y en la medida que resulten aplicables conforme a la actividad que desarrolle el sujeto obligado, entre otros,
                los datos que, por cada concepto, se indican a continuación:.
            </td>
        </tr>
        <tr>
            <td colspan="20">
                a) Documentación que respalda las operaciones efectuadas durante el cuatrimestre.
                1. Forma de emisión, indicando si se efectúa por sistema manual o por controlador fiscal.
            </td>
        </tr>

    <?php
        foreach ($puntosdeventas as $puntosdeventa){
        echo $puntosdeventa['Puntosdeventa']['nombre']."-"  .$puntosdeventa['Puntosdeventa']['sistemafacturacion']."</br>";
        }
        unset($puntosdeventa);
    ?>
<!--        <tr>-->
<!--            <td colspan="20">-->
                2. Nº de la primera y última factura o documento equivalente emitido.</br>
<!--                <div  class="" style="width: 100%;">-->
<!--        <table>-->
            <tr>
                <td>Punto de Venta</td>
                <td>Comprobante</td>
                <td>primera</td>
                <td>última</td>
            </tr>
            <?php
            $totalVentasCuatrimestre = 0;
            foreach ($ultimasventas as $miventa){
                $tipodebitoasociado = "";?>
                <tr>
                    <td><?php
                                echo $miventa['Puntosdeventa']['nombre']
                        ?></td>
                    <td><?php
                                $tipodebitoasociado = $miventa['Comprobante']['tipodebitoasociado'];
                                echo $miventa['Comprobante']['nombre'];
                        ?></td>
                    <td><?php echo $miventa[0]['minnumerocomprobante']; ?></td>
                    <td><?php echo $miventa[0]['maxnumerocomprobante']; ?></td>
                </tr>
                <?php
                if($tipodebitoasociado == 'Restitucion de debito fiscal'){
                    $totalVentasCuatrimestre -= $miventa[0]['total'];
                }else{
                    $totalVentasCuatrimestre += $miventa[0]['total'];
                }
            }
            unset($miventa);
            ?>
<!--        </table>-->
<!--    </div>-->
<!--            </td>-->
<!--        </tr>-->
        <tr>
            <td colspan="20">
                3. Monto de las operaciones del período.	<?php echo $totalVentasCuatrimestre>0?$totalVentasCuatrimestre:0; ?></br>
            </td>
        </tr>
        <tr>
            <td colspan="20">
                b) Proveedores
            </td>
        </tr>
        <tr>
            <td colspan="20">
                1. Clave Unica de Identificación Tributaria (C.U.I.T.) de los CINCO (5) principales proveedores, en función al monto de las operaciones.
            </td>
        </tr>
        <tr>
            <td colspan="20">
                2. Monto de compras efectuadas a dichos proveedores.
            </td>
        </tr>
        <tr>
            <td colspan="20">
                3. Cantidad de facturas o documentos equivalentes emitidos por el proveedor al pequeño contribuyente.
            </td>
        </tr>
<!--        <tr>-->
<!--            <td colspan="20">-->
<!--                <table>-->
                    <tr><td>1. CUIT</td><td>2. Monto</td><td>3. Cantidad</td></tr>
                    <?php
                    foreach ($comprastop as $compratop){
                        echo "<tr>
                                <td>".$compratop['Provedore']['cuit']."</td>
                                <td>".$compratop[0]['total']."</td>
                                <td>".$compratop[0]['cantidad']."</td>
                              </tr>";
                    }
                    unset($compratop);
                    ?></br>
<!--                </table>-->
<!--            </td>-->
<!--        </tr>-->
        <tr>
            <td colspan="20">
                 c) Clientes
            </td>
        </tr>
        <tr>
            <td colspan="20">
    1. Clave Unica de Identificación Tributaria (C.U.I.T.), Código Unico de Identificación Laboral (C.U.I.L.) o Clave de Identificación (C.D.I.), de los CINCO (5) principales clientes en función al monto de las operaciones.
            </td>
        </tr>
        <tr>
            <td colspan="20">
                2. Monto facturado a dichos clientes.
            </td>
        </tr>
        <tr>
            <td colspan="20">
                3. Cantidad de facturas o documentos equivalentes emitidos a dichos clientes.
            </td>
        </tr>
<!--        <tr>-->
<!--            <td colspan="20">-->
<!--                <table>-->
                    <tr><td>1. CUIT</td><td>2. Monto</td><td>3. Cantidad</td></tr>
                    <?php
                    foreach ($ventastop as $ventatop){
                        echo "<tr>
                                <td>".$ventatop['Subcliente']['cuit']."</td>
                                <td>".$ventatop[0]['total']."</td>
                                <td>".$ventatop[0]['cantidad']."</td>
                              </tr>";
                    }
                    unset($ventatop);
                    ?>
<!--                </table>-->
<!--            </td>-->
<!--        </tr>-->
        <tr>
            <td colspan="20">
                d) Local o establecimiento en el que se desarrolla la actividad.
            </td>
        </tr>
        <tr>
            <td colspan="20">
                1. Condición de propietario o inquilino, o si la actividad se desarrolla sin local o establecimiento fijo.
            </td>
        </tr>
        <tr>
            <td colspan="20">
                <?php echo $this->Form->input('domicilio_id', array('label' => '','empty'=>'La actividad se desarrolla sin local')); ?>
            </td>
        </tr>
        <tr>
            <td colspan="20">
                En caso que la condición sea la de inquilino:
            </td>
        </tr>
        <tr>
            <td colspan="20">
                - Clave Unica de Identificación Tributaria (C.U.I.T.), Código Unico de Identificación Laboral (C.U.I.L.) o Clave de Identificación (C.D.I.) del propietario del inmueble.
            </td>
        </tr>
    <?php
    $subtotalCompraP0=0;
    $subtotalCompraKW=0;
    $subtotalCompraAlquiler=0;
    foreach ($compraskw as $compra){
        if($compra['Compra']['tipogasto_id']=='19'/*Luz*/){
            $subtotalCompraP0 += $compra[0]['total'];
            $subtotalCompraKW += $compra[0]['kw'];
        }
        if($compra['Compra']['tipogasto_id']=='21'/*Alquiler*/){
            $subtotalCompraAlquiler += $compra[0]['total'];
        }
    }
    unset($compra);?>
        <tr>
            <td colspan="20">
                <?php echo $this->Form->input('cuitalquiler', array('label' => '',)); ?>
            </td>
        </tr>
        <tr>
            <td colspan="20">
    - Monto de los alquileres devengados en el cuatrimestre.	0
            </td>
        </tr>
        <tr>
            <td colspan="20">
                <?php echo $this->Form->input('montoalquiler', array('label' => '','value'=>$subtotalCompraAlquiler)); ?>
            </td>
        </tr>
        <tr>
            <td colspan="20">
    - Fecha de inicio del contrato de locación.
            </td>
        </tr>
        <tr>
            <td colspan="20">
    <?php echo $this->Form->input('fechainicioalquiler', array('label' => '')); ?>
            </td>
        </tr>
        <tr>
            <td colspan="20">
    - Fecha de finalización del contrato de locación.
            </td>
        </tr>
        <tr>
            <td colspan="20">
    <?php echo $this->Form->input('fechafinalquiler', array('label' => '')); ?>
            </td>
        </tr>
        <tr>
            <td colspan="20">
    2. Número de partida y de inscripción dominial, de corresponder.
            </td>
        </tr>
        <tr>
            <td colspan="20">
    e) Energía eléctrica
            </td>
        </tr>
        <tr>
            <td colspan="20">
    1. Números de las facturas cuyos vencimientos para el pago se hayan producido en el cuatrimestre.
            </td>
        </tr>
        <tr>
            <td colspan="20">
    2. Clave Unica de Identificación Tributaria (C.U.I.T.) de la empresa proveedora del servicio de suministro eléctrico.
            </td>
        </tr>
        <tr>
            <td colspan="20">
    3. Kilovatios consumidos en el cuatrimestre.
            </td>
        </tr>
        <tr>
            <td colspan="20">
    4. Importe de la Factura
            </td>
        </tr>
        <tr>
            <td colspan="20">
    5. Clave Unica de Identificación Tributaria (C.U.I.T.) del titular del servicio (sujeto a cuyo nombre se emite la factura).
            </td>
        </tr>
        <tr>
<!--            <td colspan="20">-->
<!--    <table>-->
            <td>1. Periodo </td><td>2. CUIT</td><td>3. Kilovatios</td><td>4. Importe</td><td>5. CUIT</td>
        </tr>
        <?php
        //aca tenemos que empezar en el $periodoInicioCuatrimestre y subir 4 meses a ver si tenemos facturas de luz en esos periodos
        //y mostrarlas en el orden correcto
        $dateInicioCuatrimestre = strtotime('01-'.$periodoInicioCuatrimestre);
        $periodo0 = date('m-Y',$dateInicioCuatrimestre);
        $dateInicioCuatrimestre = strtotime('01-'.$periodoInicioCuatrimestre.' +1 months');
        $periodo1 = date('m-Y',$dateInicioCuatrimestre);
        $dateInicioCuatrimestre = strtotime('01-'.$periodoInicioCuatrimestre.' +2 months');
        $periodo2 = date('m-Y',$dateInicioCuatrimestre);
        $dateInicioCuatrimestre = strtotime('01-'.$periodoInicioCuatrimestre.' +3 months');
        $periodo3 = date('m-Y',$dateInicioCuatrimestre);
        ?>
        <tr>
            <?php
            $nohaycompra = true;
            foreach ($compraskw as $compra) {
                if ($compra['Compra']['tipogasto_id'] == '19'/*Luz*/ && $periodo0 == $compra[0]['periodo']) {
                    echo "
                        <td>" . $compra[0]['periodo'] . "</td>
                        <td>" . $compra[0]['provedore_id'] . "</td>
                        <td>" . $compra[0]['kw'] . "</td>
                        <td>" . $compra[0]['total'] . "</td>
                        <td>" . $compra[0]['total'] . "</td>
                        ";
                    $mnohaycompra = false;
                }
            }
            unset($compra);
            if($nohaycompra){?>
                <td><?php echo $periodo0 ?></td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
            <?php } ?>
        </tr>
        <tr>
            <?php
            $nohaycompra = true;
            foreach ($compraskw as $compra) {
                if ($compra['Compra']['tipogasto_id'] == '19'/*Luz*/ && $periodo1 == $compra[0]['periodo']) {
                    echo "
                        <td>" . $compra[0]['periodo'] . "</td>
                        <td>" . $compra[0]['provedore_id'] . "</td>
                        <td>" . $compra[0]['kw'] . "</td>
                        <td>" . $compra[0]['total'] . "</td>
                        <td>" . $compra[0]['total'] . "</td>
                        ";
                    $mnohaycompra = false;
                }
            }
            unset($compra);
            if($nohaycompra){?>
                <td><?php echo $periodo1 ?></td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
            <?php } ?>
        </tr>
        <tr>
            <?php
            $nohaycompra = true;
            foreach ($compraskw as $compra) {
                if ($compra['Compra']['tipogasto_id'] == '19'/*Luz*/ && $periodo2 == $compra[0]['periodo']) {
                    echo "
                        <td>" . $compra[0]['periodo'] . "</td>
                        <td>" . $compra[0]['provedore_id'] . "</td>
                        <td>" . $compra[0]['kw'] . "</td>
                        <td>" . $compra[0]['total'] . "</td>
                        <td>" . $compra[0]['total'] . "</td>
                        ";
                    $mnohaycompra = false;
                }
            }
            unset($compra);
            if($nohaycompra){?>
                <td><?php echo $periodo2 ?></td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
            <?php } ?>
        </tr>
        <tr>
            <?php
            $nohaycompra = true;
            foreach ($compraskw as $compra) {
                if ($compra['Compra']['tipogasto_id'] == '19'/*Luz*/ && $periodo3 == $compra[0]['periodo']) {
                    echo "
                        <td>" . $compra[0]['periodo'] . "</td>
                        <td>" . $compra[0]['provedore_id'] . "</td>
                        <td>" . $compra[0]['kw'] . "</td>
                        <td>" . $compra[0]['total'] . "</td>
                        <td>" . $compra[0]['total'] . "</td>
                        ";
                    $mnohaycompra = false;
                }
            }
            unset($compra);
            if($nohaycompra){?>
                <td><?php echo $periodo3 ?></td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
            <?php } ?>
<!--        </tr>-->
<!--        </tbody>-->
<!--    </table>-->
            </td>
        </tr>
    </table>
</div>
