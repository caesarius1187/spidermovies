<script>
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
                'controller' => 'clientes',
                'action' => 'tareacargar',
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
    <h2>Txt Compras Facturas</h2>
    <div class="index" style="overflow-x: auto;" id="divFacturas" ><?php
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
        $lineaCompra .= str_pad(number_format($compra[0]['total'], 2, "", ""), 15, "0", STR_PAD_LEFT);
//        $linecompra['importeconceptosprecionetogravado']=substr($line, 119,13).'.'.substr($line, 132, 2);
        $lineaCompra .= str_pad(number_format($compra[0]['nogravados'], 2, "", ""), 15, "0", STR_PAD_LEFT);
//        $linecompra['importeoperacionesexentas']=substr($line, 134,13).'.'.substr($line, 147, 2);
        $lineaCompra .= str_pad(number_format($compra[0]['exentos'], 2, "", ""), 15, "0", STR_PAD_LEFT);
//        $linecompra['importepercepcionespagosacuentaiva']=substr($line, 149,13).'.'.substr($line, 162, 2);
        $lineaCompra .= str_pad(number_format($compra[0]['ivapercep'], 2, "", ""), 15, "0", STR_PAD_LEFT);
//        $linecompra['importepercepcionespagosacuentaimpuestosnacionales']=substr($line, 164,13).'.'.substr($line, 177, 2);
        $lineaCompra .= str_pad(number_format(0, 2, "", ""), 15, "0", STR_PAD_LEFT);
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
        $lineaCompra .= str_pad("0", 1, "0", STR_PAD_LEFT);
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
    ?></div>
    <h2>Txt Compras Alicuotas</h2>
    <div class="index" style="overflow-x: auto;" id="divAlicuotas" ><?php
        foreach($alicuotas as $c => $alicuota ) {
            $lineaAlicuota = "";

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
            $lineaAlicuota .= str_pad($alicCodigoAMostrar, 4, "0", STR_PAD_LEFT);
//            $lineAlicuota['impuestoliquidado'] = substr($line, 69, 13).'.'.substr($line, 82, 2);
            $lineaAlicuota .= str_pad(number_format($alicuota['Compra']['iva'], 2, "", ""), 15, "0", STR_PAD_LEFT);
            echo $lineaAlicuota."</br>";
        }
        ?></div>
</div>



