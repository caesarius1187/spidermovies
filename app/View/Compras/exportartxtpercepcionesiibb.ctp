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
               onclick="downloadInnerHtml('<?php echo $cliente["Cliente"]['nombre']."-".$periodo; ?>percepcionesiibb.txt','divFacturas','text/html')">
                Descargar Percepciones IIBB
            </a>
        </div>
    </div>
    <h2>Txt Percepciones IIBB</h2>
    <div class="index" style="overflow-x: auto;" id="divFacturas" ><?php
        foreach($compras as $c => $compra ) {
        $lineaCompra = "";
        $lineaCompra = date('dmY', strtotime($compra['Compra']['fecha'])) ;
        $lineaCompra .= str_pad($compra["Comprobante"]['abreviacion'], 3, "0", STR_PAD_LEFT);
        $lineaCompra .= str_pad($compra['Compra']['numerocomprobante'], 20, "0", STR_PAD_LEFT);
        $nombreamostrar = $compra['Provedore']['nombre'];
        $identificacionnumero = $compra['Provedore']['cuit'];
//        $nombreamostrar=    str_replace(" ","&nbsp",$nombreamostrar);
            $lineaCompra .= str_pad($nombreamostrar, 60, " ", STR_PAD_RIGHT);
            $lineaCompra .= str_pad($identificacionnumero, 11, "0", STR_PAD_LEFT);
            
            if($compra['Compra']["tipocredito"]=='Restitucion credito fiscal'){
                $lineaCompra .= "-";
            }else{
                $lineaCompra .= "0";
            }
            $lineaCompra .= str_pad(number_format($compra['Compra']['iibbpercep'], 2, ".", ""), 14, "0", STR_PAD_LEFT);
        echo $lineaCompra."</br>";
    }
    ?></div>
</div>




