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
            <?php
            echo $this->Form->create('compras',[
                'action'=>'exportartxtpercepcionesdgrm',
                'url'=>'/compras/exportartxtpercepcionesdgrm/'.$cliid.'/'.$periodo
            ]);
            echo $this->Form->input('padronsipret',['required','default'=>$cliente["Cliente"]['cuitautorizada']]);
            echo $this->Form->input('objeto',['required']);
            echo $this->Form->input('original',[ 'options'=>['O'=>'O','R'=>'R'],'required']);
            echo $this->Form->input('descripcionactividad',['required']);
            echo $this->Form->end('recargar');
            ?>
        </div>
        <div style="width:auto; float: left;">
            <a id="aExportarFacturas" href="#" class="buttonImpcli" style="margin-right: 8px;width: initial;"
               onclick="downloadInnerHtml('<?php echo $cliente["Cliente"]['nombre']."-".$periodo; ?>percepcionesdgrm.txt','divFacturas','text/html')">
                Descargar Percepciones Act. Vs.
            </a>
        </div>
    </div>
    <h2>Txt Percepciones Act.Vs</h2>
    <?php
    if( isset($this->request->data['compras']['padronsipret']) ){
    ?>
    <div class="index" style="overflow-x: auto;" id="divFacturas" ><?php
        /*
         * El contenido del archivo consta de un encabezado y de un detalle.
            El primero se compone de 3 campos: Número que identifica a la cabecera, Código y Formulario y es una sola fila.
            Número que identifica a la cabecera: siempre 1
            Código: es una cadena de caracteres que está compuesta por:
            Padrón (longitud 13) +
            Período (longitud 6) +
            Objeto (longitud 13) + O de original o R de rectificativa. Longitud total 33.
            Formulario: determina el Número de Identificación de formulario. Ejemplo F978 de longitud variable hasta 5.
            Ejemplo:
            1@Codigo@Formulario
            1
            @00000000477540820100000000001338O
            @F978

        */
        $lineaInicial="";
        $num = 1;
        $lineaInicial.=$num."@";
        $comprador = str_pad($this->request->data['compras']['padronsipret'], 13, "0", STR_PAD_LEFT);
        $comprador .=  str_replace("-","",$periodo);
        $comprador .= str_pad($this->request->data['compras']['objeto'], 13, "0", STR_PAD_LEFT);
        $comprador .= $this->request->data['compras']['original'];
        $lineaInicial .= $comprador;
        $lineaInicial .= "@F978";

        echo $lineaInicial."</br>";
        foreach($compras as $c => $compra ) {
            $num = 2;
            /*
             *
             *  El detalle se compone de 8 campos:
            Código:                       Formato Texto, mismo que el encabezado.       Longitud 33
            Fecha:                        Formato Texto (##-##-####),                   Longitud 10
            Tipo de Comprobante:          Formato Texto y variable de                   Longitud hasta 50
            Nro. de Comprobante:          Formato Texto con ceros a la izquierda de     Longitud 15
            Denominación:                 Formato Texto, varíable de                    Longitud hasta 50
            CUIT:                         Formato Texto con guiones incluidos,          Longitud 13
            Padrón:                       Formato Texto,                                Longitud 13
            Monto:                        Formato Texto, variable de                    Longitud hasta 15
            Ejemplo:
            2@Código@Fecha@TipoComprobante@NroComprobante@Denominación@CUIT@Padrón@Monto
            Tanto en el encabezado y el detalle existen campos que tienen longitudes variables, por tal motivo tienen
            como separador el caracter "@" entre ellos.
            * */
            $lineaCompra = "";
            $lineaCompra .= $num."@";
            $lineaCompra .= $comprador;
            $lineaCompra .= "@";
            $lineaCompra .= date('d-m-Y', strtotime($compra['Compra']['fecha'])) ;
            $lineaCompra .=  "@";
            $lineaCompra .= $compra["Comprobante"]['abreviacion'];
             $lineaCompra .=  "@";
            $lineaCompra .= $compra['Compra']['puntosdeventa'];
            $lineaCompra .=  "-";
            $lineaCompra .= str_pad($compra['Compra']['numerocomprobante'], 15, "0", STR_PAD_LEFT);
            $lineaCompra .=  "@";
            $nombreamostrar = $compra['Provedore']['nombre'];
            $lineaCompra .= $nombreamostrar;
            $lineaCompra .=  "@";
            $identificacionnumero = $compra['Provedore']['cuit'];
            $identificacionnumero = $compra['Provedore']['cuit'];
            if(strlen($identificacionnumero)==11){
                $lineaCompra .= substr($identificacionnumero, 0,2);
                $lineaCompra .= "-";
                $lineaCompra .= substr($identificacionnumero, 2,8);
                $lineaCompra .= "-";
                $lineaCompra .= substr($identificacionnumero, -1);
            }else{
                $lineaCompra .= $identificacionnumero;
            }
            $lineaCompra .=  "@";
            $lineaCompra .= str_pad($this->request->data['compras']['padronsipret'], 13, "0", STR_PAD_LEFT);;
            $lineaCompra .=  "@";
            if($compra['Compra']["tipocredito"]=='Restitucion credito fiscal'){
                $lineaCompra .= "-";
            }else{
                $lineaCompra .= "";
            }
            $lineaCompra .= number_format($compra['Compra']['actvspercep'], 2, ".", "");
            echo $lineaCompra."</br>";
        }
    ?></div>
    <?php
        }
    ?>
</div>




