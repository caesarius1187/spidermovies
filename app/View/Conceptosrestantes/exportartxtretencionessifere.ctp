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
               onclick="downloadInnerHtml('<?php echo $cliente["Cliente"]['nombre']."-".$periodo; ?>retencionessifere.txt','divFacturas','text/html')">
                Descargar Percepciones IIBB
            </a>
        </div>
    </div>
    <h2>Txt Retenciones</h2>
    <div class="index" style="overflow-x: auto;" id="divFacturas" ><?php
        foreach($conceptosrestantes as $c => $conceptosrestante ) {
            $lineaRetencion = "";
//            Jurisdiccion,comboBoxJurisdiccion,1,3
            $lineaRetencion .= $conceptosrestante["Partido"]['codigo'];
//            Cuit,cuit,4,16
            $identificacionnumero = $conceptosrestante['Conceptosrestante']['cuit'];
            if(strlen($identificacionnumero)==11){
                $lineaRetencion .= substr($identificacionnumero, 0,2);
                $lineaRetencion .= "-";
                $lineaRetencion .= substr($identificacionnumero, 2,8);
                $lineaRetencion .= "-";
                $lineaRetencion .= substr($identificacionnumero, -1);
            }else{
                $lineaRetencion .= $identificacionnumero;
            }
//            Fecha de retencion,fecha,17,26
            $lineaRetencion .= date('d/m/Y', strtotime($conceptosrestante['Conceptosrestante']['fecha'])) ;
//            Numero de sucursal,numerico,27,30
            $lineaRetencion .= str_pad(substr($conceptosrestante['Conceptosrestante']['puntosdeventa'], -4), 4, "0", STR_PAD_LEFT);;
//            Numero de constancia,numerico,31,46
            $lineaRetencion .= str_pad($conceptosrestante['Conceptosrestante']['numerocomprobante'], 15, "0", STR_PAD_LEFT);;
            /*Esto tenemos que agregar por que no tenemos*/
//            Tipo de comprobante,comboBoxTipoComprobante,47,47
//            Letra de comprobante,comboBoxLetraComprobante,48,48
            $lineaRetencion .= str_pad($conceptosrestante["Comprobante"]['abreviacion2'], 3, "0", STR_PAD_LEFT);
//            Nro de comprobante original,texto,49,68
            $lineaRetencion .= str_pad($conceptosrestante["Conceptosrestante"]['numerofactura'], 20, "0", STR_PAD_LEFT);
//            Importe retenido,importe,69,79
            if($conceptosrestante['Comprobante']["tipocreditoasociado"]=='Restitucion credito fiscal'){
                $lineaRetencion .= "-";
            }else{
                $lineaRetencion .= "0";
            }
            $lineaRetencion .= str_pad(number_format($conceptosrestante['Conceptosrestante']['montoretenido'], 2, ",", ""), 10, "0", STR_PAD_LEFT);
            echo $lineaRetencion."</br>";
        }
    ?></div>
</div>




