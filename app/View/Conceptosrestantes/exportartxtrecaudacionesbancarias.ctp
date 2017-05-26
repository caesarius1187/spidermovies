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
                'controller' => 'conceptosrestantes',
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
               onclick="downloadInnerHtml('<?php echo $cliente["Cliente"]['nombre']."-".$periodo; ?>RecaudacionesBancarias.txt','divFacturas','text/html')">
                Descargar Retenciones IIBB
            </a>
        </div>
    </div>
    <h2>Txt Recaudaciones Bancarias</h2>
    <div class="index" style="overflow-x: auto;" id="divFacturas" ><?php
        $tiposcuenta=['Caja de Ahorro'=>'CA','Cuenta Corriente'=>'CC','Otro'=>'OT'];
        $tiposmoneda=['Moneda Ext.'=>'E','Peso Arg.'=>'P','Otro'=>'O'];
        foreach($conceptosrestantes as $c => $conceptosrestante ) {
            $lineaRecaudacion = "";
//            Jurisdiccion,comboBoxJurisdiccion,1,3
            $lineaRecaudacion .= $conceptosrestante["Partido"]['codigo'];
//            Cuit,cuit,4,16
            $identificacionnumero = $conceptosrestante['Conceptosrestante']['cuit'];
            $identAMostrar="";
            if(strlen($identificacionnumero)==11){
                $identAMostrar .= substr($identificacionnumero, 0,2);
                $identAMostrar .= "-";
                $identAMostrar .= substr($identificacionnumero, 2,8);
                $identAMostrar .= "-";
                $identAMostrar .= substr($identificacionnumero, -1);
            }else{
                $identAMostrar .= $identificacionnumero;
            }
            $lineaRecaudacion .=str_pad( $identAMostrar , 13, "0", STR_PAD_LEFT);

            $lineaRecaudacion .=str_pad( $conceptosrestante['Conceptosrestante']['anticipo'] , 7, "0", STR_PAD_LEFT);
            $lineaRecaudacion .= str_pad(trim($conceptosrestante["Cbu"]['cbu']), 22, "0", STR_PAD_LEFT);
            if($conceptosrestante['Conceptosrestante']['tipocuenta']==""){
                $lineaRecaudacion .= str_pad(number_format($conceptosrestante['Conceptosrestante']['montoretenido'], 2, ",", ""), 10, "0", STR_PAD_LEFT);
                echo "Error debe seleccionar Tipo Cuenta:".$lineaRecaudacion."</br>";;
                continue;
            }
            if($conceptosrestante['Conceptosrestante']['tipomoneda']==""){
                $lineaRecaudacion .= str_pad(number_format($conceptosrestante['Conceptosrestante']['montoretenido'], 2, ",", ""), 10, "0", STR_PAD_LEFT);
                echo "Error debe seleccionar Tipo Moneda:".$lineaRecaudacion."</br>";;
                continue;
            }
            //Falta TipoCuenta
            $lineaRecaudacion .= str_pad($tiposcuenta[$conceptosrestante['Conceptosrestante']['tipocuenta']], 2, "0", STR_PAD_LEFT);
            //Falta TipoMoneda
            $lineaRecaudacion .= str_pad($tiposmoneda[$conceptosrestante['Conceptosrestante']['tipomoneda']], 1, "0", STR_PAD_LEFT);
            $lineaRecaudacion .= str_pad(number_format($conceptosrestante['Conceptosrestante']['montoretenido'], 2, ",", ""), 10, "0", STR_PAD_LEFT);

            echo $lineaRecaudacion."</br>";
        }
    ?></div>
</div>




