<script>
    function exportarFacturas() {
        var container = $('#divFacturas');
        var anchor = $('#aExportarFacturas');
        anchor.href = 'data:text/plain;charset=utf-8,' + encodeURIComponent(container.html());
        anchor.download = 'Ventas.txt';
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
                'controller' => 'ventas',
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
               onclick="downloadInnerHtml('<?php echo $cliente["Cliente"]['nombre']."-".$periodo; ?>ventas.txt','divFacturas','text/html')">
                Descargar Ventas Facturas
            </a>
            <a id="aExportarAlicuotas" href="#" class="buttonImpcli" style="margin-right: 8px;width: initial;"
               onclick="downloadInnerHtml('<?php echo $cliente["Cliente"]['nombre']."-".$periodo; ?>alicuotas.txt','divAlicuotas','text/html')">
                Descargar Ventas Alicuotas
            </a>
        </div>
    </div>
    <h2>Txt Ventas Facturas</h2>
    <div style="overflow-x:auto;" class="index" id="divFacturas"><?php
        foreach($ventas as $c => $venta ) {
            //aca vamos a buscar dos alicuotas de la misma venta, si esta alicuota es 0  entonces hay que borrarla
            //vamos a buscar la venta con mas de una alicuota
            if($venta[0]['cantalicuotas']>1){
                //si encuentro una busco las alicuotas de esta y si alguna tiene alic = 0 la borro
                foreach($alicuotas as $a => $alicuota ) {
                    if(
                            $venta['Venta']['comprobante_id']==$alicuota['Venta']['comprobante_id']&&
                            $venta['Venta']['puntosdeventa_id']==$alicuota['Venta']['puntosdeventa_id']&&
                            $venta['Venta']['numerocomprobante']==$alicuota['Venta']['numerocomprobante']&&
                            $venta['Venta']['subcliente_id']==$alicuota['Venta']['subcliente_id']
                        )
                    {
                        //encontre una alicuota de una compra con 2 alicuotas, si la alic == 0 entonces la borro
                        if($alicuota['Venta']['alicuota']=='0'){
                            //Debugger::dump("elimine:");
                            //Debugger::dump($alicuota);
                            unset($alicuotas[$a]);
                            $ventas[$c][0]['cantalicuotas']--;
                        }
                    }                   
                }
            }
        }
        foreach($ventas as $v => $venta ) {
            $totalVenta  = 0;
            $lineVenta = "";
            //         $lineVenta = array();
            //        $lineVenta['fecha']=date('d-m-Y',strtotime(substr($line, 0,8)));
            $lineVenta = date('Y', strtotime($venta['Venta']['fecha'])) . date('m', strtotime($venta['Venta']['fecha'])) . date('d', strtotime($venta['Venta']['fecha']));
            //        $lineVenta['comprobantetipo']=substr($line, 8,3);
            $lineVenta .= str_pad($venta["Comprobante"]['codigo'], 3, "0", STR_PAD_LEFT);
            //        $lineVenta['puntodeventa']=substr($line, 11,5);
            $lineVenta .= str_pad($venta["Puntosdeventa"]['nombre'], 5, "0", STR_PAD_LEFT);
            //        $lineVenta['comprobantenumero']=substr($line, 16,20);
            $trimNumComprobante = ltrim($venta['Venta']['numerocomprobante']," ");
            if($trimNumComprobante*1==442){
                /*Debugger::dump($venta[0]['totalfactura']);
                Debugger::dump($venta['Venta']['excentos']);
                Debugger::dump($venta[0]['excentos']);*/
            }
            $lineVenta .= str_pad($trimNumComprobante, 20, "0", STR_PAD_LEFT);
            //        $lineVenta['comprobantenumerohasta']=substr($line, 36,20);
            $lineVenta .= str_pad($trimNumComprobante, 20, "0", STR_PAD_LEFT);
            //        $lineVenta['codigodocumento']=substr($line, 56,2);

            //si CUIT mostrar 80, SI DNI(lenght = 8) mostrar 96
            $nombreamostrar = $venta['Subcliente']['nombre'];
            $identificacionnumero = $venta['Subcliente']['cuit'];
            if(strlen($identificacionnumero)<=8)/*ES UN DNI!!!!*/
            {
                $lineVenta .= str_pad(96, 2, "0", STR_PAD_LEFT);//todo: reemplazar codigo documento
            }else{
                $lineVenta .= str_pad(80, 2, "0", STR_PAD_LEFT);//todo: reemplazar codigo documento
            }
            //        $lineVenta['identificacionnumero']=substr($line, 58,20);

            $lineVenta .= str_pad($identificacionnumero, 20, "0", STR_PAD_LEFT);//todo: reemplazar codigo documento
            //        $lineVenta['nombre']=substr($line, 78,30);

            $lineVenta .= substr(str_pad($nombreamostrar, 30, " ", STR_PAD_RIGHT), 0, 30);
            //todo: reemplazar codigo documento
            //        //aveces la identificacionnumero viene vacia (todos 0) entonces vamos a poner el nombre
            //        // en estos casos como identificacion numero
            //        if(ltrim($lineVenta['identificacionnumero'],'0')==''){
            //            $lineVenta['identificacionnumero'] = $lineVenta['nombre'];
            //        }
            //        //hay algunos casos donde los registros vienen sin nombre y sin cuit, en estos casos
            //        //vamos a poner que el subcliente es un consumidor final y lo vamos a cargar
            //        //el formato del consumidor final es
            //        //Nombre:   Consumidor Final
            //        //CUIT:     20000000001
            //        //DNI:      20000000001
            //        if(ltrim($lineVenta['identificacionnumero'],' ')=='' && ltrim($lineVenta['nombre'],' ')==''){
            //            $lineVenta['nombre'] = 'Consumidor Final';
            //            $lineVenta['identificacionnumero'] = '20000000001';
            //        }
            //        $lineVenta['importetotaloperacion']=substr($line, 108,13).'.'.substr($line, 121, 2);
            $lineVenta .= str_pad(number_format($venta[0]['totalfactura'], 2, "", ""), 15, "0", STR_PAD_LEFT);
            //        $lineVenta['importeconceptosprecionetogravado']=substr($line, 123,13).'.'.substr($line, 136, 2);
            $lineVenta .= str_pad(number_format($venta[0]['nogravados'], 2, "", ""), 15, "0", STR_PAD_LEFT);
            //        $lineVenta['percepcionesnocategorizados']=substr($line, 138,13).'.'.substr($line, 151, 2);
            $lineVenta .= str_pad(number_format($venta[0]['ivapercep'], 2, "", ""), 15, "0", STR_PAD_LEFT);
            //        $lineVenta['importeoperacionesexentas']=substr($line, 153,13).'.'.substr($line, 166, 2);
            $lineVenta .= str_pad(number_format($venta[0]['excentos'], 2, "", ""), 15, "0", STR_PAD_LEFT);
            //        $lineVenta['importepercepcionespagosacuenta']=substr($line, 168,13).'.'.substr($line, 181, 2);
            $lineVenta .= str_pad(number_format(0, 2, "", ""), 15, "0", STR_PAD_LEFT);
            //Todo: Buscar importepercepcionespagosacuenta en ventas importar y recuperarlos al exportar
            //        $lineVenta['importeingresosbrutos']=substr($line, 183,13).'.'.substr($line, 196, 2);
            $lineVenta .= str_pad(number_format($venta[0]['iibbpercep'], 2, "", ""), 15, "0", STR_PAD_LEFT);
            //        $lineVenta['importeimpuestosmunicipales']=substr($line, 198,13).'.'.substr($line, 211, 2);
            $lineVenta .= str_pad(number_format($venta[0]['actvspercep'], 2, "", ""), 15, "0", STR_PAD_LEFT);
            //        $lineVenta['importeimpuestosinternos']=substr($line, 213,13).'.'.substr($line, 223, 2);
            $lineVenta .= str_pad(number_format($venta[0]['impinternos'], 2, "", ""), 15, "0", STR_PAD_LEFT);
            //        $lineVenta['codigomoneda']=substr($line, 228,3);
            $lineVenta .= str_pad("PES", 3, " ", STR_PAD_LEFT);
            //        $lineVenta['cambiotipo']=substr($line, 231,10);
            $lineVenta .= str_pad("0001000000", 10, "0", STR_PAD_LEFT);
            //        $lineVenta['cantidadalicuotas']=substr($line, 241,1);
            $lineVenta .= str_pad($venta[0]['cantalicuotas'], 1, "0", STR_PAD_LEFT);
            //        $lineVenta['operacioncodigo']=substr($line, 242,1);
            if($venta['Venta']['alicuota']==0){
                $lineVenta .= str_pad("E", 1, "0", STR_PAD_LEFT);
            }else{
                $lineVenta .= str_pad(0, 1, "0", STR_PAD_LEFT);
            }
            
            //        $lineVenta['otrostributos']=substr($line, 243,13).'.'.substr($line, 256, 2);
            $lineVenta .= str_pad(0, 15, "0", STR_PAD_LEFT);
            //        $lineVenta['fechavencimientopago']=substr($line, 258,8);
            $lineVenta .= str_pad(0, 8, "0", STR_PAD_LEFT);
//            $lineVenta['lineacompleta']=$line;
            //        $ventasArray[$i]['Venta']=$lineVenta;
            echo $lineVenta."</br>";
        }
        ?></div>
    <h2>Txt Ventas Alicuotas</h2>
    <div class="index" style="overflow-x: auto;" id="divAlicuotas" ><?php
        foreach($alicuotas as $c => $alicuota ) {
            $lineaAlicuota = "";

//            $lineAlicuota['comprobantetipo'] = substr($line, 0, 3);
//            $lineAlicuota['puntodeventa'] = substr($line, 3, 5);
//            $lineAlicuota['comprobantenumero'] = substr($line, 8, 20);
//            $lineAlicuota['importenetogravado'] = substr($line, 28, 13).'.'.substr($line, 41, 2);
//            $lineAlicuota['alicuotaiva'] = substr($line, 43, 4);
//            $lineAlicuota['impuestoliquidado'] = substr($line, 47, 13).'.'.substr($line, 60, 2);

//            $lineAlicuota['comprobantetipo'] = substr($line, 0, 3);
            $lineaAlicuota .= str_pad($alicuota["Comprobante"]['codigo'], 3, "0", STR_PAD_LEFT);
//            $lineAlicuota['puntodeventa'] = substr($line, 3, 5);
            $lineaAlicuota .= str_pad($alicuota['Puntosdeventa']['nombre'], 5, "0", STR_PAD_LEFT);
//            $lineAlicuota['comprobantenumero'] = substr($line, 8, 20);
            $trimNumComprobante = ltrim($alicuota['Venta']['numerocomprobante']," ");
            $lineaAlicuota .= str_pad($trimNumComprobante, 20, "0", STR_PAD_LEFT);
//            $lineAlicuota['importenetogravado'] = substr($line, 50, 13).'.'.substr($line, 63, 2);
            $lineaAlicuota .= str_pad(number_format($alicuota['Venta']['neto'], 2, "", ""), 15, "0", STR_PAD_LEFT);

//            $lineAlicuota['alicuotaiva'] = substr($line, 65, 4);
            $alicCodigoAMostrar = "0003";
            foreach ($alicuotascodigoreverse as $alicCodigo => $mialicuota){
                if($alicuota['Venta']['alicuota']==$mialicuota){
                    $alicCodigoAMostrar = $alicCodigo;
                }
            }
            $lineaAlicuota .= str_pad($alicCodigoAMostrar, 4, "0", STR_PAD_LEFT);
//            $lineAlicuota['impuestoliquidado'] = substr($line, 69, 13).'.'.substr($line, 82, 2);
            $lineaAlicuota .= str_pad(number_format($alicuota['Venta']['iva'], 2, "", ""), 15, "0", STR_PAD_LEFT);
            echo $lineaAlicuota."</br>";
        }
        ?></div>
</div>

