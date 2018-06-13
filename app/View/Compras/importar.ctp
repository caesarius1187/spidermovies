<?php
function customSearch($keyword, $arrayToSearch){
    foreach($arrayToSearch as $key => $arrayItem){
        if($keyword==""){

        }
        if( stristr( $arrayItem, $keyword ) ){
            return $key;
        }
    }
    unset($arrayItem);
    return 0;
}
function toNumber($target){
    $switched = str_replace('.', '', $target);
    $switched = str_replace(',', '.', $switched);
    return floatval($switched);
}
function change_key( $array, $old_key, $new_key ) {

    if( ! array_key_exists( $old_key, $array ) )
        return $array;

    $keys = array_keys( $array );
    $keys[ array_search( $old_key, $keys ) ] = $new_key;

    $array = array_combine( $keys, $array );
}
echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false));
echo $this->Html->script('compras/importar',array('inline'=>false));?>
<div class="index" style="">

    <?php
    $labelClifch = $cliente['Cliente']['nombre'];
    ?>
    <h1 style="float:right;"><?php echo __($labelClifch); ?></h1>
    <label style="float:right;margin-right: 20px;"><?php echo $periodo; ?></label>
    <?php  echo $this->Html->link("<- Volver",array(
            'controller' => 'compras',
            'action' => 'cargar',
            $cliid,
            $periodo,
        ),
        array(
            'class'=>"btn_aceptar",
            'style'=>'float: left;margin-top: 0px;'
        )
    ); 	?>
</div>
<div class="index" style="width: inherit;float: left;height: 171px;">
<?php 
echo $this->Form->create('Compra', array('enctype' => 'multipart/form-data'));
?>
Nuevos Archivos:</br>
Cargar Archivo de Facturas:</br>
<?php
echo $this->Form->file('Compra.archivocompra');
?>
</br>Cargar Archivo de Alicuotas:</br>
<?php
echo $this->Form->file('Compra.archivoalicuota');
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
<?php

    //aca vamos a crear un string id para las compras del periodo para que la busqueda sea mas facil y rapida
    foreach ($comprasperiodo as $c => $compraYaCargada) {
        $stringID = $compraYaCargada['Compra']['comprobante_id']."-".
            ($compraYaCargada['Compra']['puntosdeventa']*1)."-".
            $compraYaCargada['Compra']['numerocomprobante']."-".
            $compraYaCargada['Compra']['provedore_id'];

        if( ! array_key_exists( $c, $comprasperiodo ) )
            continue;

        $keys = array_keys( $comprasperiodo );
        $keys[ array_search( $c, $keys ) ] = $stringID;

        $comprasperiodo = array_combine( $keys, $comprasperiodo );
    }
?>
    <div class="index" style="width: inherit;float: left;margin-left: -10px;height: 171px;">
	<?php
	$dirCompras = new Folder($folderCompras, true, 0777);
	$dirAlicuotas = new Folder($folderAlicuotas, true, 0777);
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
            if($i>100){
                $j++;
                continue;
            }
            $line = utf8_decode($line);
            if(strlen($line)!=328){
                //$errorInFileCompra=true;
                //echo strlen($line)."line lenght";
                //break;
            }
            // process the line read.
            $lineCompra = array();
            $lineCompra['fecha']=date('d-m-Y',strtotime(substr($line, 0,8)));
            $lineCompra['comprobantetipo']=substr($line, 8,3);
            $lineCompra['puntodeventa']=substr($line, 11,5);
            $lineCompra['comprobantenumero']=substr($line, 16,20);
            $lineCompra['numerodespacho']=substr($line, 36,16);
            $lineCompra['codigodocumento']=substr($line, 52,2);
            $lineCompra['identificacionnumero']=substr($line, 54,20);
            $lineCompra['nombre']=substr($line, 74,30);
            $lineCompra['importetotaloperacion']=substr($line, 104,13).'.'.substr($line, 117, 2);
            $lineCompra['importeconceptosprecionetogravado']=substr($line, 119,13).'.'.substr($line, 132, 2);
            $lineCompra['importeoperacionesexentas']=substr($line, 134,13).'.'.substr($line, 147, 2);
            $lineCompra['importepercepcionespagosacuentaiva']=substr($line, 149,13).'.'.substr($line, 162, 2);
            $lineCompra['importepercepcionespagosacuentaimpuestosnacionales']=substr($line, 164,13).'.'.substr($line, 177, 2);
            $lineCompra['importeingresosbrutos']=substr($line, 179,13).'.'.substr($line, 192, 2);
            $lineCompra['importeimpuestosmunicipales']=substr($line, 194,13).'.'.substr($line, 207, 2);
            $lineCompra['importeimpuestosinternos']=substr($line, 209,13).'.'.substr($line, 222, 2);
            $lineCompra['codigomoneda']=substr($line, 224,3);
            $lineCompra['cambiotipo']=substr($line, 227,10);
            $lineCompra['cantidadalicuotas']=substr($line, 237,1);
            $lineCompra['operacioncodigo']=substr($line, 238,1);
            $lineCompra['creditofiscalcomputable']=substr($line, 239,15);
            $lineCompra['otrostributos']=substr($line, 254,15);
            $lineCompra['cuit']=substr($line, 269,11);
            $lineCompra['denominacion']=substr($line, 280,30);
            $lineCompra['ivacomicion']=substr($line, 310,15);

            $comprobanteTipoNuevo = ltrim(customSearch($lineCompra['comprobantetipo'],$comprobantes), '0');
            $pdvNuevo = $lineCompra['puntodeventa'];
            $numeroComprobante = ltrim($lineCompra['comprobantenumero'], '0');


            //aveces la identificacionnumero viene vacia (todos 0) entonces vamos a poner el nombre
            // en estos casos como identificacion numero
            if(ltrim($lineCompra['identificacionnumero'],'0')==''||ltrim($lineCompra['identificacionnumero'],' ')==''){
                $lineCompra['identificacionnumero'] = '20000000001';
            }
            //hay algunos casos donde los registros vienen sin nombre y sin cuit, en estos casos
            //vamos a poner que el subcliente es un consumidor final y lo vamos a cargar
            //el formato del consumidor final es
            //Nombre:   Consumidor Final
            //CUIT:     20000000001
            //DNI:      20000000001
            if(ltrim($lineCompra['identificacionnumero'],' ')=='' && ltrim($lineCompra['nombre'],' ')==''){
                $lineCompra['nombre'] = 'Consumidor Final';
                $lineCompra['identificacionnumero'] = '20000000001';
            }
            $provedorNuevo = customSearch(ltrim($lineCompra['identificacionnumero'], '0'),$provedores);
            $lineCompra['provedornuevo']=$provedorNuevo;

            $stringID = $comprobanteTipoNuevo."-".$pdvNuevo."-".$numeroComprobante."-".$provedorNuevo;

            $compraCargadaPreviamente = array_key_exists( $stringID , $comprasperiodo );

            if($compraCargadaPreviamente){
                $textoCompraYaCargada .=
                    $lineCompra['comprobantetipo']."-".
                    $lineCompra['puntodeventa']."-".
                    $numeroComprobante." // ";
                $compraCargadaPreviamente = true;
                $cantComprasYaguardadas++;                
            }
            if(!$compraCargadaPreviamente&&$i<=100){
                //la compra no estaba entre las ya guardadas entonces la agrego y subo una posicion
                if(!isset($comprasArray[$stringID] )){
                    $comprasArray[$stringID] = array();
                    $comprasArray[$stringID]['Compra'] = array();
                }
                $comprasArray[$stringID]['Compra']=$lineCompra;
                /*si la compra que agregamos es tipo C tendriamos que crear una alicuota "a mano" por que no va a haber una*/
                if($comprobanteTipoNuevo=='470'/*Es Factura C*/){
                    $lineAlicuota = array();
                    $lineAlicuota['comprobantetipo'] = '011';
                    $lineAlicuota['puntodeventa'] = $lineCompra['puntodeventa'];
                    $lineAlicuota['comprobantenumero'] = $lineCompra['comprobantenumero'];
                    $lineAlicuota['codigodocumento']=$lineCompra['codigodocumento'];
                    $lineAlicuota['identificacionnumero']=$lineCompra['identificacionnumero'];
                    $lineAlicuota['importenetogravado'] = '0.00';
                    $lineAlicuota['alicuotaiva'] = '0003';
                    $lineAlicuota['impuestoliquidado'] = '0.00';
                    if(!isset($comprasArray[$stringID]['Alicuota'])){
                        $comprasArray[$stringID]['Alicuota']=array();
                        $comprasArray[$stringID]['Alicuota'][0]=array();
                    }
                    $comprasArray[$stringID]['Alicuota'][0]=$lineAlicuota;
                }
                $i++;

            }
            $j++;
            //if($j>100) break;
        }

        $tituloButton= $dirCompra->name;
//        $tituloButton= $errorInFileCompra?$dirCompra->name." Archivo con Error": $dirCompra->name;
            echo $this->Form->button(
                $tituloButton .'</br>
            <label>Compras: '.$j.'</label>',
            array(
                'class'=>'buttonImpcli4',
                'onClick'=>"deletefile('".$dirCompra->name."','".$cliid."','compras','".$periodo."')",
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

    /*************************************************************************************************************/
    //Aca vamos a leer los CSV y ponerlos en el array de ventas
    $errorInFileMovimientosbancarios=false;
    $mostrarTabla=false;
    $moneyChars = ['.','$'];

    $filesCompras = $dirCompras->find('.*\.csv');
    $errorInFileCompra=false;

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

        while (($line = fgetcsv($handler, 1000, ";")) !== false) {
            if($i > 100){
                $j++;
                continue;
            }
//                $line = utf8_decode($line);
            $date = date_parse($line[0]."-".$line[1]."-".$line[2]);
            if ($date["error_count"] == 0 && checkdate($date["month"], $date["day"], $date["year"])) {
                $parceLine = true;
            }
            else {
                $parceLine = false;
            }
            if((!$parceLine)||$line[1]==""||$line[2]==""||$line[3]==""||$line[4]==""){
                continue;
            }
            // process the line read.
            $lineCompra = array();


            $lineCompra['fecha']=date('d-m-Y',strtotime($line[0]."-".$line[1]."-".$line[2]));
            $lineCompra['comprobantetipo']=$line[3];
            $lineCompra['puntodeventa']=$line[4];
            $lineCompra['comprobantenumero']=$line[5];
            $lineCompra['identificacionnumero']=$line[7];
            $lineCompra['nombre']=$line[6];
            //primero que nada tengo que buscar si esta venta no existe ya con otra alicuota

            $comprobanteTipoNuevo = ltrim(customSearch($lineCompra['comprobantetipo'],$comprobantes), '0');
            $pdvNuevo = ltrim($lineCompra['puntodeventa'], '0');
            //$alicuotaNuevo = customSearch($alicuota['alicuotaiva'],$alicuotas);
            $numeroComprobante = ltrim($lineCompra['comprobantenumero'], '0');

            //aveces la identificacionnumero viene vacia (todos 0) entonces vamos a poner el nombre
            // en estos casos como identificacion numero
            if(ltrim($lineCompra['identificacionnumero'],'0')==''){
                $lineCompra['identificacionnumero'] = '20000000001';
            }
            //hay algunos casos donde los registros vienen sin nombre y sin cuit, en estos casos
            //vamos a poner que el subcliente es un consumidor final y lo vamos a cargar
            //el formato del consumidor final es
            //Nombre:   Consumidor Final
            //CUIT:     20000000001
            //DNI:      20000000001
            if(ltrim($lineCompra['identificacionnumero'],' ')=='' && ltrim($lineCompra['nombre'],' ')==''){
                $lineCompra['nombre'] = 'Consumidor Final';
                $lineCompra['identificacionnumero'] = '20000000001';
            }
            $provedorNuevo = customSearch(ltrim($lineCompra['identificacionnumero'], '0'),$provedores);
            $lineCompra['provedornuevo']=$provedorNuevo;

            $stringID = $comprobanteTipoNuevo."-".$pdvNuevo."-".$numeroComprobante."-".$provedorNuevo;

            $lineCompra['importepercepcionespagosacuentaiva']= toNumber($line[11]);
            $lineCompra['importeingresosbrutos']= toNumber($line[12]);
            $lineCompra['importeimpuestosmunicipales']= toNumber($line[13]);
            $lineCompra['importeimpuestosinternos']= toNumber($line[14]);
            $lineCompra['importeconceptosprecionetogravado']= toNumber($line[15]);
            $lineCompra['importeoperacionesexentas']= toNumber($line[16]);
            $lineCompra['importetotaloperacion']= toNumber($line[17]);

            //ya tenemos toda la compra ahora vamos a ver si la agregamos al formulario o no
            //Antes agregabamos todas las compras a este array pero se congestiona la pantalla
            //asi que vamos a recorrer las compras ya cargadas para ver si no estamos guardando sin necesidad
            $compraCargadaPreviamente = false;
            $lineCompra['provedornuevo']=$provedorNuevo;

            $compraCargadaPreviamente = array_key_exists( $stringID , $comprasperiodo );

            if ($compraCargadaPreviamente){
                $textoCompraYaCargada .=
                    $lineCompra['comprobantetipo']."-".
                    $lineCompra['puntodeventa']."-".
                    $numeroComprobante." // ";
                $compraCargadaPreviamente = true;
                $cantComprasYaguardadas++;
//                break;
            }else{
//                die();
            }
            if(!$compraCargadaPreviamente&&$i<=100){
                //la compra no estaba entre las ya guardadas entonces la agrego y subo una posicion
                $comprasArray[$stringID]['Compra']=$lineCompra;
                $lineAlicuota = array();
                $lineAlicuota['comprobantetipo'] = $lineCompra['comprobantetipo'];
                $lineAlicuota['puntodeventa'] = $lineCompra['puntodeventa'];
                $lineAlicuota['comprobantenumero'] = $lineCompra['comprobantenumero'];
                $lineAlicuota['alicuotaiva'] = $line[8];
                $lineAlicuota['importenetogravado'] = toNumber($line[9]);
                $lineAlicuota['impuestoliquidado'] = toNumber($line[10]);
                if(!isset($comprasArray[$stringID]['Alicuota'])){
                    $comprasArray[$stringID]['Alicuota']=array();
                }
                array_push($comprasArray[$stringID]['Alicuota'], $lineAlicuota);
                $i++;
            }else{
            }
            $j++;
            // $line="";
            unset($lineCompra);
//            if($i>100){
//                die("-3.".$i);
//            }
        }
        $tituloButton= $errorInFileCompra?$dirCompra->name." Archivo con Error": $dirCompra->name;
        echo $this->Form->button(
            $tituloButton .'</br>
                <label>Compras: '.$j.'</label>',
            array(
                'class'=>'buttonImpcli4',
                'onClick'=>"deletefile('".$dirCompra->name."','".$cliid."','compras','".$periodo."')",
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
//    die("0");
    ?>
    </br>Alicuotas:</br>
	<?php
	$filesAlicuotas = $dirAlicuotas->find('.*\.txt');
    //vamos a crear un array de Ventas con los datos que vayamos recavando de cada archivo
    $i=0;
    $alicuotasArray = array();
    $errorInFileAlicuota=false;
    foreach ($filesAlicuotas as $dirAlicuota) {
        if(is_readable($dirAlicuotas->pwd() . DS . $dirAlicuota)){
            $mostrarTabla=true;
        }else{
            echo "No se puede acceder al archivo:".$dirAlicuota."</br>";
            break;
        }
        $dirAlicuota = new File($dirAlicuotas->pwd() . DS . $dirAlicuota);
        $dirAlicuota->open();
        $contents = $dirAlicuota->read();
        // $file->delete(); // I am deleting this file
        $handler = $dirAlicuota->handle;
        $j=0;
        while (($line = fgets($handler)) !== false) {
            // process the line read.
            $lineAlicuota = array();
            $lineAlicuota['comprobantetipo'] = substr($line, 0, 3);

            $lineAlicuota['puntodeventa'] = substr($line, 3, 5);
            $lineAlicuota['comprobantenumero'] = substr($line, 8, 20);
            $lineAlicuota['codigodocumento']=substr($line, 28,2);
            $lineAlicuota['identificacionnumero']=substr($line, 30,20);

            //aveces la identificacionnumero viene vacia (todos 0) entonces vamos a poner el nombre
            // en estos casos como identificacion numero
            if(ltrim($lineAlicuota['identificacionnumero'],'0')==''||ltrim($lineAlicuota['identificacionnumero'],' ')==''){
                $lineAlicuota['identificacionnumero'] = '20000000001';
            }

            $lineAlicuota['importenetogravado'] = substr($line, 50, 13).'.'.substr($line, 63, 2);
            $lineAlicuota['alicuotaiva'] = substr($line, 65, 4);
            $lineAlicuota['impuestoliquidado'] = substr($line, 69, 13).'.'.substr($line, 82, 2);
            $i++;
            $j++;
            //ahora que tenemos la alicuota en un array tenemos que buscar la venta a la que pertenece y agregarla
            foreach ($comprasArray as $strid => $compra) {
                $mismoidentificacion = $compra['Compra']['identificacionnumero']==$lineAlicuota['identificacionnumero'];
                $mismocomprobante = $compra['Compra']['comprobantenumero']==$lineAlicuota['comprobantenumero'];
                $mismopuntodeventa = $compra['Compra']['puntodeventa']==$lineAlicuota['puntodeventa'];
                $mismotipocomprobante = $compra['Compra']['comprobantetipo']==$lineAlicuota['comprobantetipo'];
                if($mismoidentificacion&&$mismocomprobante&&$mismopuntodeventa&&$mismotipocomprobante){
                    if(!isset($compra['Alicuota'])){
                        $compra['Alicuota']=array();
                    }
                    array_push($compra['Alicuota'], $lineAlicuota);
                    $comprasArray[$strid]=$compra;
                }else{

                }
            }
            unset($compra);
        }
        echo $this->Form->button(
            $dirAlicuota->name.'</br>
            <label>Alicuotas: '.$j.'</label>',
            array(
                'class'=>'buttonImpcli4',
                'onClick'=>"deletefile('".$dirAlicuota->name."','".$cliid."','alicuotas','".$periodo."')",
                'style'=>'white-space: nowrap;overflow: hidden;text-overflow: ellipsis;',
                'id'=>'',
            ),
            array()
        );
        fclose ( $handler );
        $dirAlicuota->close(); // Be sure to close the file when you're done
        if(!is_resource($handler)){
            //echo "handler cerrado con exito 2";
        }else{
            //echo "handler cerrado ABIERTO! 2";
        }
    }
    unset($dirAlicuota); ?>
</div>
    <div class="index" style="width: inherit;float: left;margin-left: -10px;display:none">
        </br>Compras ya Cargadas Previamente:</br>
        <?php //echo $textoCompraYaCargada; ?>
    </div>


<?php
    $ProvedoreNoCargado=array();
    $ComprasConFechasIncorrectas=array();
    foreach ($comprasArray as $compra) {
        $agregarProvedore=true;
        foreach ($provedores as $provedore){
            if (strpos($provedore, ltrim($compra['Compra']['identificacionnumero'],'0'))) {
                $agregarProvedore=false;
            }
        }
        if($agregarProvedore){
            $miProvedore=array();
            $miProvedore['cuit']=ltrim($compra['Compra']['identificacionnumero'],'0');
            $miProvedore['nombre']=$compra['Compra']['nombre'];
            if(!in_array($miProvedore,$ProvedoreNoCargado)){
                $ProvedoreNoCargado[]=$miProvedore;
            }
        }
        $periodocompra = date('Y-m',strtotime($compra['Compra']['fecha']));
        //vamos a controlar que las fechas de las ventas sean las correctas (esten en este periodo)
        $fechaperiodoactual = date('Y-m',strtotime('01-'.$periodo));
        if($periodocompra>$fechaperiodoactual){
            //esta venta no es del periodo
            $ComprasConFechasIncorrectas[]=$compra['Compra']['fecha'];
        }
    }
    unset($compra);

if((count($ProvedoreNoCargado)!=0||count($ComprasConFechasIncorrectas)!=0)||!$mostrarTabla){ ?>
<div class="index" style="width: inherit;float: left;padding-left: 0">
    <?php
    if($mostrarTabla) {
        echo $this->Form->create('Compra', array(
                'action' => 'agregarparaimportar',
                'id' => 'CompraAgregarImportar',
                'class' => 'formTareaCarga'
            )
        );
        ?>
        <table class="tableProvedoreEditForm">
            <?php if (count($ProvedoreNoCargado) != 0) { ?>
                <tr>
                    <td colspan="0">Nuevos Provedores que se necesitan agregar para procesar las compras de los archivos
                    </td>
                </tr>
                <?php
                foreach ($ProvedoreNoCargado as $provedore) { ?>
                    <tr>
                        <td>
                            <?php
                            echo $this->Form->input('Provedore.' . $j . '.cliente_id', array('type' => 'hidden', 'value' => $cliid));
                            echo $this->Form->input('Provedore.' . $j . '.cuit', array(
                                    'label' => '',
                                    'value' => ltrim($provedore['cuit'], '0'),
                                    'style' => "width:200px",
                                )
                            );
                            echo $this->Form->input('Provedore.' . $j . '.dni', array(
                                    'label' => '',
                                    'value' => ltrim($provedore['cuit'], '0'),
                                    'style' => "width:200px",
                                )
                            );
                            echo $this->Form->input('Provedore.' . $j . '.nombre', array(
                                    'label' => '',
                                    'value' => ltrim($provedore['nombre'], '0'),
                                    'style' => "width:400px",
                                )
                            );
                            echo $this->Form->input('Provedore.' . $j . '.periodo', array('type' => 'hidden', 'value' => $periodo));

                            ?>
                        </td>
                    </tr>
                    <?php
                    $j++;
                }
                unset($provedore);

            }
            if (count($ComprasConFechasIncorrectas) != 0) { ?>
                <tr>
                    <td colspan="0">Las siguientes compras tienen errores con las fechas, por favor elimine el archivo
                        y suba uno con compras del periodo: <?php echo $periodo; ?></td>
                </tr>
                <tr>
                    <td colspan="0">
                        <?php
                        foreach ($ComprasConFechasIncorrectas as $m => $item) {
                            echo $m . ":" . $item;
                            echo ($m + 1) % 10 == 0 ? '</br>' : '//';
                        }
                        unset($item); ?>
                    </td>
                </tr>
                <?php
            } ?>
        </table>
        <?php
        echo $this->Form->submit('Agregar Nuevos', array('style' => 'width: 200px;float: none;'));
        echo $this->Form->end();
    }
    ?>
</div>
<?php
} else { ?>
    <div class="index" style="overflow-x: visible;overflow-y: visible">
    <?php
    //formulario oculto que va a contener en json todos los datos del formulario que esta debajo(lo hacemos asi para automatizar el envio)
    echo $this->Form->create('Compra',array(
            'controller'=>'Compra',
            'action'=>'cargarcompras',
            'id'=>'CompraImportarAEnviar',
            'class'=>'formTareaCarga',
            'inputDefaults' => array(
                'div' => true,
                'label' => false,
            ),
        )
    );
    echo $this->Form->input('Compra.0.jsonencript',array(
            'label'=>($i+9)%10==0?'N°':'',
            'value'=>'',
            'type'=>'hidden',
        )
    );
    echo $this->Form->submit('+', array(
            'type'=>'image',
            'src' => $this->webroot.'img/check.png',
            'class'=>'img_edit',
            'style'=>'width:25px;height:25px;',
            'div'=> array('style'=>'display:none'))
    );
    echo $this->Form->end();
    //formulario donde se van a llenar todos los datos importados, que luego seran enviador por ajax a traves del formulario anterior

    //si no tiene categorizado ganancias no se debe poder cargar compras
    //a menos que sea monotributista y no tenga activado ganancias
    //aca tengo que recorrer las actividades del cliente para relacionar las actividades con sus categorias de
    // ganancias para que por javascript  las limite solo a las que tienen q ser
    $actividadesCategorias = [];
    foreach ($cliente['Actividadcliente'] as $actividadcliente) {
        foreach ($actividadcliente['Cuentasganancia'] as $cuentaganancia){
            $cuentaGananciaTraducida = "";
            switch ($cuentaganancia['categoria']){
                case 'primeracateg':
                    $cuentaGananciaTraducida = "primera";
                    break;
                case 'segundacateg':
                    $cuentaGananciaTraducida = "segunda";
                    break;
                case 'terceracateg':
                    $cuentaGananciaTraducida = "tercera";
                    break;
                case 'terceracateg45':
                    $cuentaGananciaTraducida = "tercera otros";
                    break;
                case 'cuartacateg':
                    $cuentaGananciaTraducida = "cuarta";
                    break;
            }
            $actividadesCategorias[$actividadcliente['id']]= $cuentaGananciaTraducida;
        }
    }


    echo $this->Form->create('Compra',array(
            'controller'=>'Compra',
            'action'=>'cargarcompras',
            'id'=>'CompraImportar',
            'class'=>'formTareaCarga',
            'inputDefaults' => array(
                'div' => true,
                'label' => false,
            ),
        )
    );
    ?>
    <table style="width: 1965px; padding: 0px;margin: 0px;border-collapse: collapse;" id="tblAddCompras">
     <?php
     $i=1;
     //vamos a recorrer los domicilios de los clientes tratando de usar el fiscal o algun otro
     $defaultDomicilio = "";
     $tengoDomFiscal = false;
     foreach ($cliente['Domicilio'] as $domicilioCli){
         if($domicilioCli['tipo']=='fiscal'){
             $defaultDomicilio = $domicilioCli['localidade_id'];
             $tengoDomFiscal = true;
         }else{
             if(!$tengoDomFiscal){
                 $defaultDomicilio = $domicilioCli['localidade_id'];
             }
         }
     }
     unset($domicilioCli);
     $compraNumero=1;

     foreach ($comprasArray as $compra) {
         if(!isset($compra['Alicuota'])) continue;
         foreach ($compra['Alicuota'] as $alicuota) {
             //hay que controlar que las compras anteriores cargadas no contengan la compra que estamos por mostrar(vamos a incluir solo este periodo)
                 $class = "par";
                 if ($compraNumero%2==0){
                     $class = "par";
                 }else{
                     $class = "impar";
                 }
                 $compraNumero++;
                 $rowheight = ($i + 9) % 10 == 0 ? 41 : 31;
                ?>
                 <tr id="row<?php echo $i; ?>">
                     <td style="width: 100%;padding: 0px;margin: 0px; height: <?php echo $rowheight ?>px; border-bottom: 0px;" colspan="25">
                         <div style="margin-top: 0px;    height:<?php echo $rowheight ?>px" class="compraFormVertical <?php /*echo $class;*/?>">
                             <?php
                               echo $this->Form->label('Compra.' . $i . '.i',str_pad($i, 2, "0", STR_PAD_LEFT), 
                                            [
                                                "style"=>"display:inline"
                                            ]
                                    );
                             echo $this->Form->input('Compra.' . $i . '.id', array('type' => 'hidden'));
                             echo $this->Form->input('Compra.' . $i . '.cliente_id', array('type' => 'hidden', 'value' => $cliid));
                             echo $this->Form->input('Compra.' . $i . '.fecha', array(
                                     'class' => 'datepicker',
                                     'type' => 'text',
                                     'label' => ($i + 9) % 10 == 0 ? 'Fecha' : false,
                                     'readonly' => 'readonly',
                                     'default' => date('d-m-Y', strtotime($compra['Compra']['fecha'])),
                                     'style' => "width:75px",
                                     'class'=>'row'.$i
                                 )
                             );
                             //Este Array de comprobantes debe incluir array{id,codigo} y se debe seleccionar por codigo
                             echo $this->Form->input('Compra.' . $i . '.comprobante_id', array(
                                 'value' =>customSearch($compra['Compra']['comprobantetipo'], $comprobantes),
                                 'type'=>'hidden',
                             ));
                             echo $this->Form->input('Compra.' . $i . '.comprobanteid', array(
                                 'value' => $compra['Compra']['comprobantetipo'],
                                 'style' => "width: 31px;",
                                    'readonly' => 'readonly',
                                 'label' => ($i + 9) % 10 == 0 ? 'Comp.' : false,
                                 'class'=>'row'.$i,
                             ));
                             //seleccionar el punto de venta por "numero(nombre)"
                             echo $this->Form->input('Compra.' . $i . '.puntosdeventa', array(
                                 'value' => $compra['Compra']['puntodeventa'],
                                 'label' => ($i + 9) % 10 == 0 ? 'P.Vent.' : false,
                                 'style' => 'width:41px;',
                                 'readonly' => 'readonly',
                                 'class'=>'row'.$i
                             ));
                             echo $this->Form->input('Compra.' . $i . '.numerocomprobante', array(
                                 'value' => ltrim($compra['Compra']['comprobantenumero'], '0'),
                                 'label' => ($i + 9) % 10 == 0 ? 'Num.' : false,
                                 'style' => 'width:60px;',
                                 'readonly' => 'readonly',
                                 'class'=>'row'.$i
                             ));
                             //se supone que cuando generemos este formulario ya van a estar creados todos los subclientes
                             //asi que solo tendriamos queseleccionar por numero de idenficicacion
                             echo $this->Form->input('Compra.' . $i . '.provedore_id', array(
                                     'value' => $compra['Compra']['provedornuevo'],
                                     'defaultoption' => $compra['Compra']['provedornuevo'],
                                     'required' => true,
                                     'label' => ($i + 9) % 10 == 0 ? 'Provedor ID' : false,
                                     'style' => 'width:90px;',
                                     'class' => 'row'.$i,
                                     'type' => 'hidden',
                                 )
                             );
                             echo $this->Form->input('Compra.' . $i . '.provedorenumero', array(
                                     'value' => ltrim($compra['Compra']['identificacionnumero'],'0'),
                                     'defaultoption' => $compra['Compra']['identificacionnumero'],
                                     'required' => true,
                                     'label' => ($i + 9) % 10 == 0 ? 'Identificacion' : false,
                                     'style' => 'width:93px;',
                                     'class' => 'row'.$i,
                                    'readonly' => 'readonly',
                                 )
                             );
                             echo $this->Form->input('Compra.' . $i . '.provedorenombre', array(
                                     'value' => $compra['Compra']['nombre'],
                                     'defaultoption' => $compra['Compra']['nombre'],
                                     'required' => true,
                                     'label' => ($i + 9) % 10 == 0 ? 'Nombre' : false,
                                     'style' => 'width:176px;',
                                     'class' => 'row'.$i,
                                    'readonly' => 'readonly',
                                 )
                             );
                             //esto no trae asi que vamos a tener que elegir
                             $condicionIVAArray = array(
                                 'type' => 'select',
                                 'label' => ($i + 9) % 10 == 0 ? 'Cond.IVA' : false,
                                 'options' => $condicionesiva,
                                 'style' => 'width:80px',
                                 'defaultoption' => 'Responsable Inscripto',
                                 'inputclass' => 'CompraAddCondicioniva',
                                 'class'=>'row'.$i.' aplicableATodos'
                             );
                             //este es el ID del comprobante que trajimos para esta compra
                             $tipocreditocompra = 'Credito Fiscal';
                             $x=0;
                             foreach ($miscomprobantes as $micomprobante) {
                                 //aca vamos a buscar el comprobante de esta venta segun su ID y vamos a ver si su tipocreditoasociado es
                                 //restitucion
                                 $mismoID=false;
                                 $tipoRestitucion = false;
                                 //echo "comprobanteTipoNuevo: ".$comprobanteTipoNuevo;
                                 $comprobanteTipoNuevo = ltrim(customSearch($compra['Compra']['comprobantetipo'],$comprobantes), '0');
                                 if($micomprobante['Comprobante']['id']==$comprobanteTipoNuevo){
                                     $mismoID=true;
                                 }
                                 if($mismoID){
                                     if ($micomprobante['Comprobante']['tipo'] == 'A') {
                                         $condicionIVAArray['defaultoption'] = 'Responsable Inscripto';
                                     } else if ($micomprobante['Comprobante']['tipo'] == 'B'){
                                         $condicionIVAArray['defaultoption'] = 'Responsable Inscripto';
                                     }else{
                                         $condicionIVAArray['defaultoption'] = "Monotributista";
                                     }
                                     if($micomprobante['Comprobante']['tipocreditoasociado']=='Restitucion credito fiscal'){
                                         $tipocreditocompra = 'Restitucion credito fiscal';
                                     }
                                     break;
                                 }
                                 $x++;
                             }
                             echo $this->Form->input('Compra.' . $i . '.condicioniva', $condicionIVAArray);
                             echo $this->Form->input('Compra.' . $i . '.actividadcliente_id', array(
                                 'type' => 'select',
                                 'options' => $actividades,
                                 'label' => ($i + 9) % 10 == 0 ? 'Actividad' : false,
                                 'style' => 'width:80px',
                                 'div' => array('class' => 'inputAControlar'),
                                 'inputclass' => 'CompraAddActividadCliente',
                                 'class'=>'row'.$i.' aplicableATodos inputactividad',
                                 'ordecompra'=>$i
                             ));
                             echo $this->Form->input('Compra.' . $i . '.actividadescategorias', [
                                 'type'=>'select',
                                 'options'=>$actividadesCategorias,
                                 'id'=>'Compra' . $i . 'jsonactividadescategorias',
                                 'div'=>[
                                          'style'=>'display:none'
                                        ]
                            ]);
                             echo $this->Form->input('Compra.' . $i . '.localidade_id', array(
                                 'class' => "chosen-select",
                                 'label' => ($i + 9) % 10 == 0 ? 'Localidad' : false,
                                 'style' => 'width:150px',
                                 'class' => 'chosen-select row'.$i.' aplicableATodos',
                                 'inputclass' => 'CompraAddLocalidades',
                                 'div' => array('class' => 'inputAControlar'),
                                 'defaultoptionlocalidade' => $defaultDomicilio,
                             ));
                             echo $this->Form->input('Compra.' . $i . '.tipocredito', array(
                                 'default' => $tipocreditocompra,
                                 'options' => $tipocreditos,
                                 'label' => ($i + 9) % 10 == 0 ? 'Tipo Cre.' : false,
                                 'style' => 'width:55px',
                                 'div' => array('class' => 'inputAControlar'),
                                 'inputclass' => 'CompraAddTipoCredito',
                                 'class'=>'row'.$i.' aplicableATodos'
                             ));
                             echo $this->Form->input('Compra.' . $i . '.tipogasto_id', array(
                                 'default' => '',
                                 'options' => $tipogastos,
                                 'label' => ($i + 9) % 10 == 0 ? 'Tipo Gasto.' : false,
                                 'style' => 'width:83px',
                                 'div' => array('class' => 'inputAControlar'),
                                 'inputclass' => 'CompraAddTipoGasto',
                                 'class'=>'row'.$i.' aplicableATodos'
                             ));
                             echo $this->Form->input('Compra.' . $i . '.tipoiva', array(
                                 'label' => ($i + 9) % 10 == 0 ? 'Tipo(IVA)' : false,
                                 'style' => 'width:55px',
                                 'options' => array('directo' => 'Directo', 'prorateable' => 'Prorateable'),
                                 'div' => array('class' => 'inputAControlar'),
                                 'inputclass' => 'CompraAddTipoIVA',
                                 'class'=>'row'.$i.' aplicableATodos'
                             ));
                             echo $this->Form->input('Compra.' . $i . '.imputacion', array(
                                     'type' => 'select',
                                     'style' => 'width:55px',
                                     'label' => ($i + 9) % 10 == 0 ? 'Imput.' : false,
                                     'options' => $imputaciones,
                                     'div' => array('class' => 'inputAControlar'),
                                     'inputclass' => 'CompraAddTipoImputacio',
                                     'class'=>'row'.$i.' aplicableATodos'
                                 )
                             );
                             echo $this->Form->input('Compra.' . $i . '.alicuota', array(
                                 'value' => customSearch($alicuota['alicuotaiva'], $alicuotas),
                                 'type'=>'hidden'
                             ));
                            echo $this->Form->input('Compra.' . $i . '.showalicuotas', array(
                                'value' => $alicuotas[customSearch($alicuota['alicuotaiva'], $alicuotas)],
                                'label' => ($i + 9) % 10 == 0 ? 'Alic.' : false,
                                'style' => 'width:27px;',
                                'inputclass' => 'CompraAddAlicuota',
                                'readonly' => 'readonly',
                             ));
                             //si el neto es 0 vamos a preguntar si el total es != 0 si es asi
                             //el neto va a ser igual al total - iva percep iibb percep acvspercep impinter nograbado
                             $neto = $alicuota['importenetogravado']*1 ;
                             $total = $compra['Compra']['importetotaloperacion']*1 ;
                             if($neto==0 && $total!=0){
                                 $neto = $total;
                                 $neto -= $compra['Compra']['importepercepcionespagosacuentaiva'] * 1;
                                 $neto -= $compra['Compra']['importeingresosbrutos'] * 1;
                                 $neto -= $compra['Compra']['importeimpuestosmunicipales'] * 1;
                                 $neto -= $compra['Compra']['importeimpuestosinternos'] * 1;
                                 $neto -= $compra['Compra']['importeconceptosprecionetogravado'] * 1;
                             }
                             echo $this->Form->input('Compra.' . $i . '.neto', array(
                                 //'style' => 'max-width: 60px;',
                                 'label' => ($i + 9) % 10 == 0 ? 'Neto' : false,
                                 'value' => $neto * 1,
                                 'class'=>'row'.$i,
                                'readonly' => 'readonly',
                             ));
                             echo $this->Form->input('Compra.' . $i . '.iva', array(
                                 //'style' => 'max-width: 60px;',
                                 'label' => ($i + 9) % 10 == 0 ? 'IVA' : false,
                                 'value' => $alicuota['impuestoliquidado'] * 1,
                                 'class'=>'row'.$i,
                                'readonly' => 'readonly',
                             ));
                             echo $this->Form->input('Compra.' . $i . '.ivapercep', array(
                                 'label' => ($i + 9) % 10 == 0 ? 'IVA Perc.' : false,
                                 'value' => $compra['Compra']['importepercepcionespagosacuentaiva'] * 1,
                                 //'style' => 'max-width: 60px;',
                                 'class'=>'row'.$i,
                                'readonly' => 'readonly',
                             ));
                             echo $this->Form->input('Compra.' . $i . '.iibbpercep', array(
                                 //'style' => 'max-width: 60px;',
                                 'label' => ($i + 9) % 10 == 0 ? 'IIBB Perc.' : false,
                                 'value' => $compra['Compra']['importeingresosbrutos'] * 1,
                                 'class'=>'row'.$i,
                                'readonly' => 'readonly',
                             ));
                             echo $this->Form->input('Compra.' . $i . '.actvspercep', array(
                                 //'style' => 'max-width: 60px;',
                                 'label' => ($i + 9) % 10 == 0 ? 'Ac.Vs.Perc.' : false,
                                 'value' => $compra['Compra']['importeimpuestosmunicipales'] * 1,
                                 'class'=>'row'.$i,
                                'readonly' => 'readonly',
                             ));
                             echo $this->Form->input('Compra.' . $i . '.impinternos', array(
                                 'label' => ($i + 9) % 10 == 0 ? 'Imp.Inter.' : false,
                                 'style' => 'max-width: 60px;',
                                 'value' => $compra['Compra']['importeimpuestosinternos'] * 1,
                                 'class'=>'row'.$i,
                                'readonly' => 'readonly',
                             ));
                             echo $this->Form->input('Compra.' . $i . '.impcombustible', array(
                                 //'style' => 'max-width: 60px;',
                                 'label' => ($i + 9) % 10 == 0 ? 'Imp.Comb.' : false,
                                 'value' => 0 * 1,
                                 'class'=>'row'.$i,
                                'readonly' => 'readonly',
                             ));
                             echo $this->Form->input('Compra.' . $i . '.nogravados', array(
                                 //'style' => 'max-width: 60px;',
                                 'label' => ($i + 9) % 10 == 0 ? 'No Gravado' : false,
                                 'value' => $compra['Compra']['importeconceptosprecionetogravado'] * 1,
                                 'class'=>'row'.$i,
                                'readonly' => 'readonly',
                             ));
                             echo $this->Form->input('Compra.' . $i . '.exentos', array(
                                 //'style' => 'max-width: 60px;',
                                 'label' => ($i + 9) % 10 == 0 ? 'Exento IVA' : false,
                                 'value' => $compra['Compra']['importeoperacionesexentas'] * 1,
                                 'class'=>'row'.$i,
                                'readonly' => 'readonly',
                             ));
                             echo $this->Form->input('Compra.' . $i . '.periodo', array('type' => 'hidden', 'value' => $periodo));
                             echo $this->Form->input('Compra.' . $i . '.total', array(
                                 'label' => ($i + 9) % 10 == 0 ? 'Total.' : false,
                                 //'style' => 'max-width: 60px;',
                                 'value' => $compra['Compra']['importetotaloperacion'] * 1,
                                 'class'=>'row'.$i,
                                'readonly' => 'readonly',
                             ));
                             echo $this->Form->button(
                                 $this->Html->image("cruz.png",
                                     array(
                                         "alt" => "eliminar",
                                         'style'=>'width:20px;height:20px',
                                     )
                                 )."",
                                 array(
                                     'class'=>"btnAgregar row".$i,
                                     'escape'=>false,
                                     'title'=>'eliminar',
                                     'type'=>"button",
                                     'style'=>'margin-top:15px; cursor: pointer;',
                                     'onClick'=>"deleterow(".$i.")"
                                 )
                             );
                             $i++;
                             if($i>100)
                                die("1");
                             ?>
                         </div>
                         <?php
                         ?>
                     </td>
                 </tr>
                 <?php
             if($i==100){
                 if(count($comprasArray)>100){
                     echo $cantComprasYaguardadas." Compras ya cargadas, faltan importar estas 100 compras, por favor continue cargando hasta que
                            finalize la carga de todas las compras del archivo";
                 }else{
                     echo $cantComprasYaguardadas." Compras ya cargadas, faltan importar estas ".$i." compras por favor continue cargando hasta que
                            finalize la carga de todas las compras del archivo";
                 }
                 break 2;
             }
         }
         unset($alicuota);
     }
     unset($compra);
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