<script>
    var tblTablaConceptosrestantes;
    $(document).ready(function() {
        tblTablaConceptosrestantes = $('#tblTablaConceptosrestantes').DataTable({

        });
    });
    function exportarFacturas() {
        var container = $('#divFacturas');
        var anchor = $('#aExportarFacturas');
        anchor.href = 'data:text/plain;charset=utf-8,' + encodeURIComponent(container.html());
        anchor.download = 'Compras.txt';
    };
    function downloadInnerHtml(filename, elId, mimeType) {
        var elHtml = document.getElementById(elId).innerHTML;
        var elTXT = elHtml.replace(/(?:\r\n|\r|\n)/g, '<br />');
        elTXT = elTXT.replace(/&nbsp;/gi," ");
        elTXT = elTXT.replace(/<br\s*\/?>/mg,"\r\n");

        var link = document.createElement('a');
        mimeType = mimeType || 'text/plain';

        link.setAttribute('download', filename);
        link.setAttribute('href', 'data:' + mimeType + ';charset=utf-8,' + encodeURIComponent(elTXT));
        link.click();
    }
</script>
<div class="index">
    <div class="index" id="headerCliente">
        <div style="width:8%; float: left;">
            <?php  echo $this->Html->link("<- Volver",array(
                'controller' => 'compras',
                'action' => 'cargar',
                $cliid,
                $periodo,
            ),
                array(
                    'class'=>"btn_aceptar",
                    'style'=>'margin-top: 0px;'
                )
            ); 	?>
        </div>
        <div style="width:30%; float: left;padding-top:10px">
            Cliente: <?php echo $cliente["Cliente"]['nombre'];
            echo $this->Form->input('clientenombre',['type'=>'hidden','value'=>$cliente["Cliente"]['nombre']]);?>
        </div>
        <div style="width:25%; float: left;padding-top:10px">
            Periodo: <?php echo $periodo;
            echo $this->Form->input('periododefault',['type'=>'hidden','value'=>$periodo])?>
        </div>
        <div style="width:auto; float: left;">
            <a id="aExportarFacturas" href="#" class="buttonImpcli" style="margin-right: 8px;width: initial;"
               onclick="downloadInnerHtml('<?php echo $cliente["Cliente"]['nombre']."-".$periodo; ?>Compras.txt','divFacturas','text/html')">
                Descargar Compras Facturas
            </a>
            <a id="aExportarAlicuotas" href="#" class="buttonImpcli" style="margin-right: 8px;width: initial;"
               onclick="downloadInnerHtml('<?php echo $cliente["Cliente"]['nombre']."-".$periodo; ?>Alicuotas.txt','divAlicuotas','text/html')">
                Descargar Compras Alicuotas
            </a>
        </div>
    </div>
    <h2>Dcto 814 Cargado en el periodo</h2>
    <div class="index" style="overflow-x: auto;">
        <table class="" style="border:1px solid white" id="tblTablaConceptosrestantes">
            <thead>
                <tr>
                    <th>Localidad</th><!-2-->
                    <th class="sum">Monto Retenido</th><!-9-->
                    <th>Fecha</th><!-11-->
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
            <tbody id="bodyTablaConceptosrestantes">
            <?php
            foreach($cliente["Conceptosrestante"] as $conceptorestante ){
                $tdClass = "tdViewConceptosrestanteO".$conceptorestante["id"];
                ?>
                <tr id="rowconceptorestante<?php echo $conceptorestante["id"]?>" class="concepto<?php echo $conceptorestante["conceptostipo_id"];?>">
                    <td class="<?php echo $tdClass?>">
                        <?php if(isset($conceptorestante['Localidade']['Partido']["nombre"])){
                            echo $conceptorestante['Localidade']['Partido']["nombre"]."-". $conceptorestante['Localidade']["nombre"];
                        }?>
                    </td>
                    <td class="<?php echo $tdClass?>"><?php echo $conceptorestante["montoretenido"]?></td>
                    <td class="<?php echo $tdClass?>"><?php echo date('d-m-Y',strtotime($conceptorestante["fecha"]))?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
    <h2>Txt Compras Facturas</h2>
    <div class="index" style="overflow-x: auto;" id="divFacturas" ><?php
        $totalComprasExportados = 0;
         foreach($compras as $c => $compra ) {
            //aca vamos a buscar dos alicuotas de la misma compra, si esta alicuota es 0  entonces hay que borrarla
            //vamos a buscar la compra con mas de una alicuota
            if($compra[0]['cantalicuotas']>1){
                //si encuentro una busco las alicuotas de esta y si alguna tiene alic = 0 la borro
                foreach($alicuotas as $a => $alicuota ) {
                    if(
                            $compra['Compra']['comprobante_id']==$alicuota['Compra']['comprobante_id']&&
                            $compra['Compra']['puntosdeventa']==$alicuota['Compra']['puntosdeventa']&&
                            $compra['Compra']['numerocomprobante']==$alicuota['Compra']['numerocomprobante']&&
                            $compra['Compra']['provedore_id']==$alicuota['Compra']['provedore_id']
                        )
                    {
                        //encontre una alicuota de una compra con 2 alicuotas, si la alic == 0 entonces la borro
                        if($alicuota['Compra']['alicuota']=='0'){
                            //Debugger::dump("elimine:");
                            //Debugger::dump($alicuota);
                            unset($alicuotas[$a]);
                            $compras[$c][0]['cantalicuotas']--;
                        }
                    }                   
                }
            }
        }
        foreach($compras as $c => $compra ) {
            $lineaCompra = "";
    //        $linecompra['fecha']=date('d-m-Y',strtotime(substr($line, 0,8)));
            $lineaCompra = date('Y', strtotime($compra['Compra']['fecha'])) . date('m', strtotime($compra['Compra']['fecha'])) . date('d', strtotime($compra['Compra']['fecha']));
    //        $linecompra['comprobantetipo']=substr($line, 8,3);
            $lineaCompra .= str_pad($compra["Comprobante"]['codigo'], 3, "0", STR_PAD_LEFT);
    //        $linecompra['puntodeventa']=substr($line, 11,5);
            $lineaCompra .= str_pad($compra['Compra']['puntosdeventa'], 5, "0", STR_PAD_LEFT);
    //        $linecompra['comprobantenumero']=substr($line, 16,20);
            $lineaCompra .= str_pad($compra['Compra']['numerocomprobante'], 20, "0", STR_PAD_LEFT);
    //        $linecompra['numerodespacho']=substr($line, 36,16);
            $lineaCompra .= str_pad(" ", 16, " ", STR_PAD_LEFT);
    //        $linecompra['codigodocumento']=substr($line, 52,2);
            $identificacionnumero = $compra['Provedore']['cuit'];
            if(strlen($identificacionnumero)<=8)/*ES UN DNI!!!!*/
            {
                $lineaCompra .= str_pad(96, 2, "0", STR_PAD_LEFT);//todo: reemplazar codigo documento
            }else{
                $lineaCompra .= str_pad(80, 2, "0", STR_PAD_LEFT);//todo: reemplazar codigo documento
            }
    //        $linecompra['identificacionnumero']=substr($line, 54,20);
            $nombreamostrar = substr($compra['Provedore']['nombre'], 0, 29);
            $lineaCompra .= str_pad($identificacionnumero, 20, "0", STR_PAD_LEFT);
    //        $linecompra['nombre']=substr($line, 74,30);
            $lineaCompra .= str_pad( $nombreamostrar, 30, " ", STR_PAD_RIGHT);
    //        $linecompra['importetotaloperacion']=substr($line, 104,13).'.'.substr($line, 117, 2);
            if($compra['Compra']["tipocredito"]=='Restitucion credito fiscal'){
                $totalComprasExportados -= $compra[0]['total'] ;
            }else{
                $totalComprasExportados += $compra[0]['total'] ;
            }
            
            $lineaCompra .= str_pad(number_format($compra[0]['total'], 2, "", ""), 15, "0", STR_PAD_LEFT);
    //        $linecompra['importeconceptosprecionetogravado']=substr($line, 119,13).'.'.substr($line, 132, 2);
            //vamos a sumas a los no grabados los impuestos combustibles por que ese dato no lo exportamos por que
            //SIAP no los incorpora
            $noGravados = $compra[0]['nogravados']*1+$compra[0]['impcombustible']*1;
            $lineaCompra .= str_pad(number_format($noGravados, 2, "", ""), 15, "0", STR_PAD_LEFT);
    //        $linecompra['importeoperacionesexentas']=substr($line, 134,13).'.'.substr($line, 147, 2);
            $lineaCompra .= str_pad(number_format($compra[0]['exentos'], 2, "", ""), 15, "0", STR_PAD_LEFT);
    //        $linecompra['importepercepcionespagosacuentaiva']=substr($line, 149,13).'.'.substr($line, 162, 2);
            $lineaCompra .= str_pad(number_format($compra[0]['ivapercep'], 2, "", ""), 15, "0", STR_PAD_LEFT);
    //        $linecompra['importepercepcionespagosacuentaimpuestosnacionales']=substr($line, 164,13).'.'.substr($line, 177, 2);
            //vamos a agregar aca las percepciones de ganancias
            $lineaCompra .= str_pad(number_format($compra[0]['ganapercep'], 2, "", ""), 15, "0", STR_PAD_LEFT);
            //TODO: No estamos guardando importepercepcionespagosacuentaimpuestosnacionales en compras
    //        $linecompra['importeingresosbrutos']=substr($line, 179,13).'.'.substr($line, 192, 2);
            $lineaCompra .= str_pad(number_format($compra[0]['iibbpercep'], 2, "", ""), 15, "0", STR_PAD_LEFT);
    //        $linecompra['importeimpuestosmunicipales']=substr($line, 194,13).'.'.substr($line, 207, 2);
            $lineaCompra .= str_pad(number_format($compra[0]['actvspercep'], 2, "", ""), 15, "0", STR_PAD_LEFT);
    //        $linecompra['importeimpuestosinternos']=substr($line, 209,13).'.'.substr($line, 222, 2);
            $lineaCompra .= str_pad(number_format($compra[0]['impinternos'], 2, "", ""), 15, "0", STR_PAD_LEFT);
    //        $linecompra['codigomoneda']=substr($line, 224,3);
            $lineaCompra .= str_pad("PES", 3, " ", STR_PAD_LEFT);
    //        $linecompra['cambiotipo']=substr($line, 227,10);
            $lineaCompra .= str_pad("0001000000", 10, "0", STR_PAD_LEFT);
    //        $linecompra['cantidadalicuotas']=substr($line, 237,1);
            /*
             * Facturas, Notas de Credito y Notas de Debito B => No tienen alicuotas (to do lo q sea B)
             * */
                if($compra["Comprobante"]['tipo']=="B"||$compra["Comprobante"]['tipo']=="C"){
                    $lineaCompra .= str_pad(0, 1, "0", STR_PAD_LEFT);
                }else{
                    $lineaCompra .= str_pad($compra[0]['cantalicuotas'], 1, "0", STR_PAD_LEFT);
                }
            //Buscar cantidad de alicuotas para esta factura
    //        $linecompra['operacioncodigo']=substr($line, 238,1);
            if($compra['Compra']['alicuota']==0){
                $lineaCompra .= str_pad("E", 1, "0", STR_PAD_LEFT);
            }else{
                $lineaCompra .= str_pad(0, 1, "0", STR_PAD_LEFT);
            }
    //        $linecompra['creditofiscalcomputable']=substr($line, 239,15);
            $lineaCompra .= str_pad("0", 15, "0", STR_PAD_LEFT);
    //       TODO: No estamos guardando creditofiscalcomputable en compras
    //        $linecompra['otrostributos']=substr($line, 254,15);
            $lineaCompra .= str_pad("0", 15, "0", STR_PAD_LEFT);
    //       TODO: No estamos guardando otrostributos en compras
    //        $linecompra['cuit']=substr($line, 269,11);
            $lineaCompra .= str_pad("0", 11, "0", STR_PAD_LEFT);
    //       TODO: No estamos guardando cuit emisor en compras
    //        $linecompra['denominacion']=substr($line, 280,30);
            $lineaCompra .= str_pad(" ", 30, " ", STR_PAD_LEFT);
    //       TODO: No estamos guardando cuit emisor en compras
    //        $linecompra['ivacomicion']=substr($line, 310,15);
            $lineaCompra .= str_pad("0", 15, "0", STR_PAD_LEFT);
    //       TODO: No estamos guardando ivacomicion en compras
            echo $lineaCompra."</br>";
        }
        $totalMovimientosBancariosExportados = 0;
        if(isset($cuentascliente[0])) {
            if (count($cuentascliente[0]) > 0) {
                foreach ($cuentascliente[0]['Movimientosbancario'] as $movimientosbancario) {
                    $lineaCompra = "";
                    //0-8
                    $lineaCompra .= date('Y', strtotime($movimientosbancario['fecha'])) . date('m', strtotime($movimientosbancario['fecha'])) . date('d', strtotime($movimientosbancario['fecha']));
                    $lineaCompra .= str_pad(39, 3, "0", STR_PAD_LEFT);
                    $lineaCompra .= str_pad("00001", 5, "0", STR_PAD_LEFT);
                    $lineaCompra .= str_pad($movimientosbancario['id'], 20, "0", STR_PAD_LEFT);
                    $lineaCompra .= str_pad(" ", 16, " ", STR_PAD_LEFT);
                    $identificacionnumero = $movimientosbancario['Cbu']['Impcli']['Impuesto']['cuit'];
                    if (strlen($identificacionnumero) <= 8)/*ES UN DNI!!!!*/ {
                        $lineaCompra .= str_pad(96, 2, "0", STR_PAD_LEFT);//todo: reemplazar codigo documento
                    } else {
                        $lineaCompra .= str_pad(80, 2, "0", STR_PAD_LEFT);//todo: reemplazar codigo documento
                    }
                    $nombreamostrar = substr($movimientosbancario['Cbu']['Impcli']['Impuesto']['nombre'], 0, 29);
                    $lineaCompra .= str_pad($identificacionnumero, 20, "0", STR_PAD_LEFT);
                    $lineaCompra .= str_pad($nombreamostrar, 30, " ", STR_PAD_RIGHT);
                    $neto = 0;
                    $subsaldo = $movimientosbancario['debito']-$movimientosbancario['credito'];
                    $totalMovimientosBancariosExportados+=$subsaldo;
                    if ($movimientosbancario['alicuota'] == '0') {

                    } elseif ($movimientosbancario['alicuota'] == '2.5') {
                        $neto = $subsaldo / 0.025;
                    } elseif ($movimientosbancario['alicuota'] == '5') {
                        $neto = $subsaldo / 0.05;
                    } elseif ($movimientosbancario['alicuota'] == '10.5') {
                        $neto = $subsaldo / 0.105;
                    } elseif ($movimientosbancario['alicuota'] == '21') {
                        $neto = $subsaldo / 0.21;
                    } elseif ($movimientosbancario['alicuota'] == '27') {
                        $neto = $subsaldo / 0.27;
                    }
                    $lineaCompra .= str_pad(number_format($neto * 1 + $subsaldo * 1, 2, "", ""), 15, "0", STR_PAD_LEFT);
                    $lineaCompra .= str_pad(number_format(0, 2, "", ""), 15, "0", STR_PAD_LEFT);
                    $lineaCompra .= str_pad(number_format(0, 2, "", ""), 15, "0", STR_PAD_LEFT);
                    $lineaCompra .= str_pad(number_format(0, 2, "", ""), 15, "0", STR_PAD_LEFT);
                    $lineaCompra .= str_pad(number_format(0, 2, "", ""), 15, "0", STR_PAD_LEFT);
                    $lineaCompra .= str_pad(number_format(0, 2, "", ""), 15, "0", STR_PAD_LEFT);
                    $lineaCompra .= str_pad(number_format(0, 2, "", ""), 15, "0", STR_PAD_LEFT);
                    $lineaCompra .= str_pad(number_format(0, 2, "", ""), 15, "0", STR_PAD_LEFT);
                    $moneda = "PES";
                    switch ($movimientosbancario['Cbu']['tipocuenta']) {
                    case 'Caja de ahorro':
                    case 'Cuenta corriente':
                        case 'Caja de Ahorro en Moneda Local':
                        case 'Cuenta Corriente en Moneda Local':
                        case 'Plazo Fijo en Moneda Local':
                        case 'Otras':
                        $moneda = "PES";
                        break;
                        case 'Caja de Ahorro en Euros':
                        case 'Plazo Fijo en Euros':
                        case 'Cuenta Corriente en Euros':
                        $moneda = "EUR";
                        break;
                        case 'Caja de Ahorro en U$S':
                        case 'Cuenta Corriente en U$S':
                        case 'Plazo Fijo en U$S':
                        $moneda = "USD";
                            break;
                    }
                    $lineaCompra .= str_pad($moneda, 3, " ", STR_PAD_LEFT);
                    $lineaCompra .= str_pad("0001000000", 10, "0", STR_PAD_LEFT);
                    $lineaCompra .= str_pad(1, 1, "0", STR_PAD_LEFT);
                    //este es el codigo de operacion y debe ser E si esta tod o exento
                    $lineaCompra .= str_pad("0", 1, "0", STR_PAD_LEFT);
                    $lineaCompra .= str_pad("0", 15, "0", STR_PAD_LEFT);
                    $lineaCompra .= str_pad("0", 15, "0", STR_PAD_LEFT);
                    $lineaCompra .= str_pad("0", 11, "0", STR_PAD_LEFT);
                    $lineaCompra .= str_pad(" ", 30, " ", STR_PAD_LEFT);
                    $lineaCompra .= str_pad("0", 15, "0", STR_PAD_LEFT);
                    echo $lineaCompra . "</br>";
                }
            }
        }
    ?></div>
    <h2>Txt Compras Alicuotas</h2>
    <div class="index" style="overflow-x: auto;" id="divAlicuotas" ><?php
    $totalIVAAlicuotaExportados=0;
       
        foreach($alicuotas as $c => $alicuota ) {
            //El tema es que si tengo 2 alicuotas 
            $lineaAlicuota = "";
            //si el codigo del comprobante es 011 no debemos mostrar alicuota
            if($alicuota["Comprobante"]['codigo']=='011'){continue;}
            //si el codigo del comprobante es 006 no debemos mostrar alicuota
            if($alicuota["Comprobante"]['codigo']=='006'){continue;}
//            $lineAlicuota['comprobantetipo'] = substr($line, 0, 3);
            $lineaAlicuota .= str_pad($alicuota["Comprobante"]['codigo'], 3, "0", STR_PAD_LEFT);
//            $lineAlicuota['puntodeventa'] = substr($line, 3, 5);
            $lineaAlicuota .= str_pad($alicuota['Compra']['puntosdeventa'], 5, "0", STR_PAD_LEFT);
//            $lineAlicuota['comprobantenumero'] = substr($line, 8, 20);
            $lineaAlicuota .= str_pad($alicuota['Compra']['numerocomprobante'], 20, "0", STR_PAD_LEFT);
//            $lineAlicuota['codigodocumento']=substr($line, 28,2);
            $lineaAlicuota .= str_pad(80, 2, "0", STR_PAD_LEFT);//todo: reemplazar codigo documento
//            $lineAlicuota['identificacionnumero']=substr($line, 30,20);
            $identificacionnumero = $alicuota['Provedore']['cuit'];
            $lineaAlicuota .= str_pad($identificacionnumero, 20, "0", STR_PAD_LEFT);
//            $lineAlicuota['importenetogravado'] = substr($line, 50, 13).'.'.substr($line, 63, 2);
            $lineaAlicuota .= str_pad(number_format($alicuota['Compra']['neto'], 2, "", ""), 15, "0", STR_PAD_LEFT);

//            $lineAlicuota['alicuotaiva'] = substr($line, 65, 4);
            $alicCodigoAMostrar = "0003";
            foreach ($alicuotascodigoreverse as $alicCodigo => $mialicuota){
                if($alicuota['Compra']['alicuota']==$mialicuota){
                    $alicCodigoAMostrar = $alicCodigo;
                }
            }
            if($alicuota['Compra']['iva']*1==0){
                $lineaAlicuota .= str_pad("0003", 4, "0", STR_PAD_LEFT);
            }else{
                $lineaAlicuota .= str_pad($alicCodigoAMostrar, 4, "0", STR_PAD_LEFT);
            }
//            $lineAlicuota['impuestoliquidado'] = substr($line, 69, 13).'.'.substr($line, 82, 2);
            If($alicuota['Compra']["tipocredito"]=='Restitucion credito fiscal'){
                $totalIVAAlicuotaExportados -= $alicuota['Compra']['iva'] ;
            }else{
                $totalIVAAlicuotaExportados += $alicuota['Compra']['iva'] ;
            }
            $lineaAlicuota .= str_pad(number_format($alicuota['Compra']['iva'], 2, "", ""), 15, "0", STR_PAD_LEFT);
            echo $lineaAlicuota."</br>";
        }
        if(isset($cuentascliente[0])) {
            if (count($cuentascliente[0]) > 0) {
                foreach ($cuentascliente[0]['Movimientosbancario'] as $movimientosbancario) {
                    $lineaAlicuota = "";
                    $lineaAlicuota .= str_pad(39, 3, "0", STR_PAD_LEFT);
                    $lineaAlicuota .= str_pad("00001", 5, "0", STR_PAD_LEFT);
                    $lineaAlicuota .= str_pad($movimientosbancario['id'], 20, "0", STR_PAD_LEFT);
                    $lineaAlicuota .= str_pad(80, 2, "0", STR_PAD_LEFT);//todo: reemplazar codigo documento
                    $identificacionnumero = $movimientosbancario['Cbu']['Impcli']['Impuesto']['cuit'];
                    $lineaAlicuota .= str_pad($identificacionnumero, 20, "0", STR_PAD_LEFT);
                    $neto = 0;
                    $codigoAlicuota = "0001";
                    $subsaldo = $movimientosbancario['debito']-$movimientosbancario['credito'];
                    if ($movimientosbancario['alicuota'] == '0') {

                    } elseif ($movimientosbancario['alicuota'] == '2.5') {
                        $neto = $subsaldo / 0.025;
                    } elseif ($movimientosbancario['alicuota'] == '5') {
                        $neto = $subsaldo / 0.05;
                    } elseif ($movimientosbancario['alicuota'] == '10.5') {
                        $neto = $subsaldo / 0.105;
                    } elseif ($movimientosbancario['alicuota'] == '21') {
                        $neto = $subsaldo / 0.21;
                    } elseif ($movimientosbancario['alicuota'] == '27') {
                        $neto = $subsaldo / 0.27;
                    }
                    $lineaAlicuota .= str_pad(number_format($neto, 2, "", ""), 15, "0", STR_PAD_LEFT);
//            $lineAlicuota['alicuotaiva'] = substr($line, 65, 4);
                    $alicCodigoAMostrar = "0003";
                    foreach ($alicuotascodigoreverse as $alicCodigo => $mialicuota){
                        if($movimientosbancario['alicuota']==$mialicuota){
                            $alicCodigoAMostrar = $alicCodigo;
                        }
                    }
                    $lineaAlicuota .= str_pad($alicCodigoAMostrar, 4, "0", STR_PAD_LEFT);
                    $lineaAlicuota .= str_pad(number_format($subsaldo, 2, "", ""), 15, "0", STR_PAD_LEFT);
                    echo $lineaAlicuota."</br>";
                }
            }
        }

        ?></div>
</div>
<?php
echo "Total de facturas exportadas: ".$totalComprasExportados."</br>";
echo "Total de IVA en alicuotas exportadas: ".$totalIVAAlicuotaExportados."</br>";
echo "Total de IVA en movimientos bancarios: ".$totalMovimientosBancariosExportados."</br>";
?>



