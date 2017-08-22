<script>
    $(document).ready(function() {
        $(".codigocomprasselect").each(function () {
            $(this).trigger('change');
        });
    });
    function exportarFacturas() {
        var container = $('#divFacturas');
        var anchor = $('#aExportarFacturas');
        anchor.href = 'data:text/plain;charset=utf-8,' + encodeURIComponent(container.html());
        anchor.download = 'Compras.txt';
    };
    function downloadInnerHtml(filename, elId, mimeType) {
        $('.spancodigo').contents().unwrap();
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
    function cambiarCodigo(posCompra){
        $("#codigoCompra"+posCompra).html(
            $("#Compra"+posCompra+"Codigo").val()
        );
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
               onclick="downloadInnerHtml('<?php echo $cliente["Cliente"]['nombre']."-".$periodo; ?>percepcionesiva.txt','divFacturas','text/html')">
                Descargar Percepciones IVA
            </a>
        </div>
    </div>
    <h2>Seleccionar Codigo Régimen</h2>
    <div>
        <?php
        $codigos = [
            '233'=>'233 - IVA-PERCEPCION-OP.COMPRAVTA CHEQUES PAGO DIFERIDO Y CERT.AVA',
            '254'=>'254 - PERCEPCIONES R.G. 2459/2008 CANJE GRANOS SUJETO INCL RFOG',
            '255'=>'255 - PERCEPCIONES R.G. 2459/2008 CANJE GRANOS SUJETO NO INCL RFOG',
            '265'=>'265 - IVA IMPORTACION COSAS MUEBLES, FRUTAS, LEGUMB HORTALIZAS',
            '267'=>'267 - IVA IMPORT COSAS MUEBLES NO REVISTEN CARACTER BIEN DE USO',
            '270'=>'270 - IVA OPERACIONES CON SUJETOS NO CATEGORIZADOS',
            '493'=>'493 - IVA PERCEP EMPRESAS PROVEEDORAS.',
            '663'=>'663 - IVA VENTA DE CUEROS ART. 11 INC. C ESTAB FAENADORES',
            '668'=>'668 - IVA VENTA DE CUEROS ART. 11 USUARIOS SERV FAENADORES',
            '805'=>'805 - Operadores de comercio electrónico - Resp Insc - Alícuota 1%',
            '806'=>'806 - Operadores de comercio electrónico - Resp Insc - Alicuota 3%',
            '807'=>'807 - Op. de com electr.- Sujetos no cat o Mono Exd -Alícuota 5%',
            '838'=>'838 - Percep RG 3411. RI e incluido en el Regis. Alíc 6% o reduc.',
            '839'=>'839 - Percep RG 3411. RI no induído en el Regis. Alíc 12% o red.',
            '840'=>'840 - Percep RG 3411. Monotrib incluido en el Regis. Alícuota 15%',
            '841'=>'841 - Percep RG 3411. Monotrib no induído en el Regis. Alíc 21%',
            '842'=>'842 - Percep RG 3411. No Categ. y no induid en el Regis. Alíc 21%',
            '848'=>'848 - Op. comercio electr - Bienes Usados - RI -Alícuota 1%',
            '849'=>'849 - Op. comercio electr - Bienes Usados - RI - Alícuota 3%',
            '850'=>'850 - Op. comercio electr - Bienes Usados - RI - Alícuota 9%',
            '851'=>'851 - Op. comercio electr - Bienes Usados - Monotrib - Alíc. 9%',
            '852'=>'852 - Op. comercio electr - Bienes Usados -Monotnb -Alic.18%',
            '853'=>'853 - Op. comercio electr - Bienes Usados - Resto -Alicuota 21%',
        ];
        foreach($compras as $c => $compra ) {
            echo $this->Form->input('Compra.'.$c.'.codigo',[
                'style'=>'display:inline',
                'type'=>'select',
                'onchange'=>'cambiarCodigo('.$c.')',
                'options'=>$codigos,
                'class'=>'codigocomprasselect',
             ]);
            $lineaCompra = date('d/m/Y', strtotime($compra['Compra']['fecha'])) ;
            $identificacionnumero = $compra['Provedore']['cuit'];
            $lineaCompra .= str_pad($identificacionnumero, 11, "0", STR_PAD_LEFT);
            $lineaCompra .= str_pad($compra['Compra']['numerocomprobante'], 16, " ", STR_PAD_RIGHT);

            if($compra['Compra']["tipocredito"]=='Restitucion credito fiscal'){
                $lineaCompra .= "-";
            }else{
                $lineaCompra .= "0";
            }
            $lineaCompra .= str_pad(number_format($compra['Compra']['ivapercep'], 2, ".", ""), 15, "0", STR_PAD_LEFT);
            echo $lineaCompra."</br>";
        }
        ?>
    </div>
    <h2>Txt Percepciones IVA</h2>
    <div class="index" style="overflow-x: auto;" id="divFacturas" ><?php
        foreach($compras as $c => $compra ) {
            $lineaCompra = '<span class="spancodigo" id="codigoCompra'.$c.'">714</span>';
            $lineaCompra .= date('d/m/Y', strtotime($compra['Compra']['fecha'])) ;
            $identificacionnumero = $compra['Provedore']['cuit'];
            $lineaCompra .= str_pad($identificacionnumero, 11, "0", STR_PAD_LEFT);
            $lineaCompra .= str_pad($compra['Compra']['numerocomprobante'], 16, " ", STR_PAD_RIGHT);

            if($compra['Compra']["tipocredito"]=='Restitucion credito fiscal'){
                $lineaCompra .= "-";
            }else{
                $lineaCompra .= "0";
            }
            $lineaCompra .= str_pad(number_format($compra['Compra']['ivapercep'], 2, ".", ""), 15, "0", STR_PAD_LEFT);

            echo $lineaCompra."</br>";
        }
        ?></div>
</div>




