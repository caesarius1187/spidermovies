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
            <a id="aExportarFacturas" href="#" class="buttonImpcli" style="margin-right: 8px;width: initial;"
               onclick="downloadInnerHtml('<?php echo $cliente["Cliente"]['nombre']."-".$periodo; ?>Compras.txt','divFacturas','text/html')">
                Descargar Compras Facturas
            </a>
        </div>
    </div>
    <h2>Txt Compras Facturas</h2>
    <div class="index" style="overflow-x: auto;" id="divFacturas" ><?php
        foreach($compras as $c => $compra ) {
        $lineaCompra = "";
        $lineaCompra .= str_pad($compra["Compra"]['tipoautorizacion'], 4, " ", STR_PAD_RIGHT);
        $lineaCompra .= str_pad($compra["Provedore"]['cuit'], 11, "0", STR_PAD_LEFT);
        $lineaCompra .= str_pad($compra["Compra"]['autorizacion'], 14, "0", STR_PAD_LEFT);//CAE/CAEA/CAI
        $lineaCompra .= date('Y', strtotime($compra['Compra']['fecha'])) . date('m', strtotime($compra['Compra']['fecha'])) . date('d', strtotime($compra['Compra']['fecha']));
        $lineaCompra .= str_pad($compra["Comprobante"]['codigo']*1, 2, "0", STR_PAD_LEFT);
        $lineaCompra .= str_pad($compra['Compra']['puntosdeventa']*1, 4, "0", STR_PAD_LEFT);
        $lineaCompra .= str_pad($compra['Compra']['numerocomprobante']*1, 8, "0", STR_PAD_LEFT);
        $lineaCompra .= str_pad(number_format($compra[0]['total'], 2, "", ""), 15, "0", STR_PAD_LEFT);
        $lineaCompra .= str_pad(80, 2, "0", STR_PAD_LEFT);
        $lineaCompra .= str_pad($cliente["Cliente"]['cuitcontribullente'], 8, "0", STR_PAD_LEFT);

        echo $lineaCompra."</br>";
    }
    ?></div>
</div>
<div class="index" >
    <?php
    echo $this->Form->create('Compra', array('enctype' => 'multipart/form-data'));
    ?>
    Nuevos Archivos:</br>
    Cargar Archivo de Facturas:</br>
    <?php
    echo $this->Form->file('Compra.archivocompra');
    echo $this->Form->input('Compra.cliid',array('type'=>'hidden','value'=>$cliid));
    echo $this->Form->input('Compra.periodo',array('type'=>'hidden','value'=>$periodo));?>
    <div style="height: 75px;text-align: -webkit-right;">
        <?php
        echo $this->Form->submit('Subir',
            array(
                'class'=>"btn_aceptar",
                'div'=>array(
                    "style"=>"position: relative;height: 100%;"
                ),
            ));
        echo $this->Form->end();
        ?>
    </div>
</div>
<div class="index" >
    <?php
    $dirCompras = new Folder($folderCompras, true, 0777);
    ?>
    Archivos Cargador previamente</br>
    Compras:</br>
    <?php
    $comprasArray = array();

    $filesCompras = $dirCompras->find('.*\.txt');
    $i=0;
    $errorInFileCompra=false;
    $mostrarTabla=false;
    $cantComprasYaguardadas=0;
    $textoCompraYaCargada = "";
    foreach ($filesCompras as $dirCompra) {
        if(is_readable($dirCompras->pwd() . DS . $dirCompra)){
            $mostrarTabla=true;
        }else{
            echo "No se puede acceder al archivo:".$dirCompra."</br>";
            break;
        }
        $dirCompra = new File($dirCompras->pwd() . DS . $dirCompra);
        $dirCompra->open();
        $contents = $dirCompra->read();
        // $file->delete(); // I am deleting this file
        $handler = $dirCompra->handle;
        $j=0;
        while (($line = fgets($handler)) !== false) {
            $line = utf8_decode($line);
            // process the line read.
            $linecompra = array();
            $linecompra['registroingreso']=substr($line, 0,79);
            $linecompra['resultado']=substr($line, 79,1);
            $linecompra['fecha']=date('d-m-Y',strtotime(substr($line, 80,14)));
            $linecompra['separador']=substr($line, 94,1);
            $linecompra['validaciones']=substr($line, 95);

            //la compra no estaba entre las ya guardadas entonces la agrego y subo una posicion
            if(!isset($comprasArray[$i] )){
                $comprasArray[$i] = array();
                $comprasArray[$i]['Compra'] = array();
            }
            $comprasArray[$i]['Compra']=$linecompra;
            $i++;
            $j++;
        }
        $tituloButton= $dirCompra->name;
        echo $this->Form->button(
            $tituloButton .'</br>
            <label>Resultados: '.$j.'</label>',
            array(
                'class'=>'buttonImpcli4',
                'onClick'=>"deletefile('".$dirCompra->name."','".$cliid."','consultaporlote','".$periodo."')",
                'style'=>'white-space: nowrap;overflow: hidden;text-overflow: ellipsis;',
                'id'=>'',
            ),
            array()
        );
        fclose ( $handler );
        $dirCompra->close(); // Be sure to close the file when you're done
        if(!is_resource($handler)){
            //echo "handler cerrado con exito";
        }else{
            //echo "handler cerrado ABIERTO!";
        }
    }
?>
</div>
<?php
if($mostrarTabla){
?>
    <div class="index" style="overflow: auto;">
        <table style="padding: 0px;margin: 0px;" id="tblAddCompras">
            <?php
            $i=1;
            foreach ($comprasArray as $compra) {
                //hay que controlar que las compras anteriores cargadas no contengan la compra que estamos por mostrar(vamos a incluir solo este periodo)
                ?>
                <tr id="row<?php echo $i; ?>">
                    <td>
                        <?php
                        echo $compra['Compra']['registroingreso']
                        ?>
                    </td>
                    <td>
                        <?php
                        switch ($compra['Compra']['resultado']){
                            case 'A':
                                echo "Aprobado";
                                break;
                            case 'R':
                                echo "Rechazo";
                                break;
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $compra['Compra']['fecha']
                        ?>
                    </td>
                    <td>
                        <?php
                         $listaerrores = explode($compra['Compra']['separador'], $compra['Compra']['validaciones']);
                        foreach ($listaerrores as $listaerror) {
                            echo "Codigo ".$listaerror.": ";
                            switch ($listaerror){
                                //Codigos de Errores de Formato
                                case '001':
                                    echo 'El tipo de autorizacion debe ser alfanumérico de 4 caracteres como máximo y debe ser alguno de los habilitados ("CAE", "CAEA", "CAI")';
                                    break;
                                case '002':
                                    echo 'La cuit del emisor indicado debe ser numérica de 11 dígitos y debe ser valida';
                                    break;
                                case '010':
                                    echo 'Código de autorización del comprobante, debe ser de 14 caracteres numéricos.';
                                    break;
                                case '006':
                                    echo 'Campo correspondiente a la fecha de emisión, debe tener el siguiente formato yyyymmdd';
                                    break;
                                case '004':
                                    echo 'El tipo de comprobante debe ser numérico de 2 dígitos y debe ser alguno de los definidos en la tabla Tipo de Comprobantes';
                                    break;
                                case '003':
                                    echo 'El punto de venta debe ser numérico de 4 dígitos como máximo y debe estar comprendido entre 1 y 9998.';
                                    break;
                                case '005':
                                    echo 'Campo correspondiente al N° de comprobante, debe ser numérico de 8 dígitos como máximo y se debe encontrar entre 1 y 99999999';
                                    break;
                                case '007':
                                    echo 'Campo correspondiente al importe total del comprobante. Debe ser numérico mayor o igual a 0 de 13 enteros y 2 decimales';
                                    break;
                                case '008':
                                    echo 'El tipo de documento del receptor debe ser numérico de 2 dígitos y debe ser alguno de los definidos en la tabla Tipo Documentos del Receptor';
                                    break;
                                case '009':
                                    echo 'El número de documento del receptor, debe contener un valor numérico de 11 caracteres. Si el número del doucumento contiene letras no informarlas, solamente informar los caracteres numéricos';
                                    break;
                                case '011':
                                    echo 'El tamaño del registro debe estar conformado por la suma de las posiciones de todos los campos';
                                    break;
                                //Codigos de Errores Funcionales
                                case '100':
                                    echo 'Verificar que el CAE/CAI/CAEA exista registrado y autorizado en las bases del organismo';
                                    break;
                                case '101':
                                    echo 'La fecha de emision no podrá ser anterior a 20130101.';
                                    break;
                                case '102':
                                    echo 'Verifica que la CUIT del emisor informada se corresponda con la cuit registrada bajo el código de autorización';
                                    break;
                                case '103':
                                    echo 'Verifica que el tipo de comprobante se corresponda con el registrado bajo el código de autorización informad';
                                    break;
                                case '104':
                                    echo 'Verifica que el punto de venta se corresponda con el punto de venta registrado bajo el código de autorización informado';
                                    break;
                                case '105':
                                    echo 'Verifica que el Nº de comprobante se corresponda con el Nº de comprobante registrado bajo el código de autorización informado';
                                    break;
                                case '106':
                                    echo 'Para tipo de autorización = "CAEA" , verifica cuando el comprobante no se encuentra rendido que la fecha tope para informarlo ya se encuentra vencida';
                                    break;
                                case '107':
                                    echo 'Para tipo de autorización = "CAE" o "CAEA", verifica que la fecha de emision se corresponda con el código de autorización informado';
                                    break;
                                case '108':
                                    echo 'Verifica que la CUIT del emisor informada se corresponda con la cuit registrada bajo el código de autorización';
                                    break;
                                case '109':
                                    echo 'Para tipo de autorización = "CAEA", verifica que el punto de venta sea un punto de venta habilitado para emitir comprobantes';
                                    break;
                                case '110':
                                    echo 'Verificar que el importe de la operación informado se corresponda con lo registrado en las bases del organismo.</br>
Margen de error:</br>
Error relativo porcentual deberá ser <= 0.01% o el error absoluto <=1.';
                                    break;
                                case '111':
                                    echo 'Verifica que el tipo de documento del receptor se corresponda con el registrado bajo el código de autorización informad';
                                    break;
                                case '112':
                                    echo 'Verifica que el número de documento del receptor se corresponda con el registrado bajo el código de autorización informa';
                                    break;
                                case '113':
                                    echo 'Para comprobantes tipo A o tipo M, el tipo de documento del receptor es obligatorio informarlo y debe ser CUIT (80).';
                                    break;
                                case '114':
                                    echo 'Para comprobantes tipo A o tipo M, el Nº de documento del receptor es obligatorio informarlo.';
                                    break;
                                case '115':
                                    echo 'Para comprobantes tipo B, C , R, 31, 30, 37, 38, 41 y 49 el tipo de documento del receptor solo es obligatorio informarlo cuando el importe es superior a 1000 pesos';
                                    break;
                                case '116':
                                    echo 'Para comprobantes tipo B, C , R, 31, 30, 37, 38, 41 y 49 , el número de documento del receptor solo es obligatorio informarlo cuando el importe es superior a 1000 pesos';
                                    break;
                                case '117':
                                    echo 'Si informa tipo de documento del receptor o n° de documento del receptor es obligatorio informar ambos';
                                    break;
                                case '200':
                                    echo 'Para tipo de autorización = "CAEA" , verifica cuando el comprobante no se encuentra rendido que la fecha tope para informarlo aún no se encuentra vencida';
                                    break;
                            }
                        }
                        ?>
                    </td>
                </tr>
                <?php
                $i++;
            }
            ?>
        </table>
        <?php
        if($i > 1){
            echo $this->Form->submit('Importar', array(
                    'type'=>'image',
                    'title'=>'Importar',
                    'src' => $this->webroot.'img/check.png',
                    'class'=>'img_edit',
                    'style'=>'width:25px;height:25px;')
            );
        }
        echo $this->Form->end();
        ?>
    </div>
    <?php
}
?>




