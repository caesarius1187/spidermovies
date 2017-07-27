<?php
echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false));
echo $this->Html->script('ventas/importar',array('inline'=>false));
function toNumber($target){
    $switched = str_replace('.', '', $target);
    $switched = str_replace(',', '.', $switched);
    return floatval($switched);
}

?>
    <SCRIPT>
        //$("#loading").css('visibility','visible');
    </SCRIPT>
    <div class="index" style="width: inherit;float: left;height: 250px;">

        <?php
        $labelClifch = $cliente['Cliente']['nombre'];
        ?>
        <div style="position: relative;height: 100%;">
            <h1><?php echo __($labelClifch); ?></h1>
            <label><?php echo $periodo; ?></label>
            <?php  echo $this->Html->link("<- Volver",array(
                'controller' => 'ventas',
                'action' => 'cargar',
                $cliid,
                $periodo,
            ),
                array(
                    'class'=>"btn_aceptar",
                    'style'=>'position: absolute;bottom: 0px;'
                )
            );  ?>
        </div>
    </div>
    <div class="index" style="width: inherit;float: left;margin-left: -10px;height: 250px;">
        <?php
        echo $this->Form->create('Venta', array('enctype' => 'multipart/form-data'));
        ?>
        Nuevos Archivos:</br>
        Cargar Archivo de Facturas:</br>
        <?php
        echo $this->Form->file('Venta.archivoventa');
        ?>
        </br></br> </br></br></br>Cargar Archivo de Alicuotas:</br>
        <?php
        echo $this->Form->file('Venta.archivoalicuota');
        echo $this->Form->input('Venta.cliid',array('type'=>'hidden','value'=>$cliid));
        echo $this->Form->input('Venta.periodo',array('type'=>'hidden','value'=>$periodo));?>
        <div style="height: 75px;text-align: -webkit-right;">
            <?php
            echo $this->Form->submit('Subir',
                array(
                    'class'=>"btn_aceptar",
                    'div'=>array(
                        "style"=>"position: relative;height: 100%;"
                    ),
                )
            );
            echo $this->Form->end();
            ?>
        </div>
    </div>

    <div class="index" style="width: inherit;float: left;margin-left: -10px;height: 250px;">
        <?php
        $dirVentas = new Folder($folderVentas, true, 0777);
        $dirAlicuotas = new Folder($folderAlicuotas, true, 0777);
        ?>
        Archivos Cargados previamente</br>
        Ventas:</br>
        <?php
        $ventasArray = array();
        /*************************************************************************************************************/
        //Aca vamos a leer los TXT y ponerlos en el array de ventas
        $filesVentas = $dirVentas->find('.*\.txt');
        $i=0;
        $errorInFileVenta=false;
        $mostrarTabla = false;
        foreach ($filesVentas as $dirVenta) {
            if(is_readable($dirVentas->pwd() . DS . $dirVenta)){
                $mostrarTabla = true;
            }else{
                echo "No se puede acceder al archivo:".$dirVenta."</br>";
                break;
            }
            $dirVenta = new File($dirVentas->pwd() . DS . $dirVenta);
            $dirVenta->open();
            $contents = $dirVenta->read();
            // $file->delete(); // I am deleting this file
            $handler = $dirVenta->handle;
            $j=0;
            while (($line = fgets($handler)) !== false) {
                $line = utf8_decode($line);
                if(strlen($line)!=268){
                    //todo Mejorar la deteccion de errores
                    //$errorInFileVenta=true;
                    //echo strlen($line)."line lenght";
                    //break;
                }
                $ventasArray[$i] = array();
                $ventasArray[$i]['Venta'] = array();
                // process the line read.
                $lineVenta = array();
                $lineVenta['fecha']=date('d-m-Y',strtotime(substr($line, 0,8)));
                $lineVenta['comprobantetipo']=substr($line, 8,3);
                $lineVenta['puntodeventa']=substr($line, 11,5);
                $lineVenta['comprobantenumero']=substr($line, 16,20);
                $lineVenta['comprobantenumerohasta']=substr($line, 36,20);
                $lineVenta['codigodocumento']=substr($line, 56,2);
                $lineVenta['identificacionnumero']=substr($line, 58,20);
                $lineVenta['nombre']=substr($line, 78,30);
                //aveces la identificacionnumero viene vacia (todos 0) entonces vamos a poner el nombre
                // en estos casos como identificacion numero
                if(ltrim($lineVenta['identificacionnumero'],'0')==''){
                    $lineVenta['identificacionnumero'] = $lineVenta['nombre'];
                }
                //hay algunos casos donde los registros vienen sin nombre y sin cuit, en estos casos
                //vamos a poner que el subcliente es un consumidor final y lo vamos a cargar
                //el formato del consumidor final es
                //Nombre:   Consumidor Final
                //CUIT:     20000000001
                //DNI:      20000000001
                if(ltrim($lineVenta['identificacionnumero'],' ')=='' && ltrim($lineVenta['nombre'],' ')==''){
                    $lineVenta['nombre'] = 'Consumidor Final';
                    $lineVenta['identificacionnumero'] = '20000000001';
                }
                $lineVenta['importetotaloperacion']=substr($line, 108,13).'.'.substr($line, 121, 2);
                $lineVenta['importeconceptosprecionetogravado']=substr($line, 123,13).'.'.substr($line, 136, 2);
                $lineVenta['percepcionesnocategorizados']=substr($line, 138,13).'.'.substr($line, 151, 2);
                $lineVenta['importeoperacionesexentas']=substr($line, 153,13).'.'.substr($line, 166, 2);
                $lineVenta['importepercepcionespagosacuenta']=substr($line, 168,13).'.'.substr($line, 181, 2);
                $lineVenta['importeingresosbrutos']=substr($line, 183,13).'.'.substr($line, 196, 2);
                $lineVenta['importeimpuestosmunicipales']=substr($line, 198,13).'.'.substr($line, 211, 2);
                $lineVenta['importeimpuestosinternos']=substr($line, 213,13).'.'.substr($line, 223, 2);
                $lineVenta['codigomoneda']=substr($line, 228,3);
                $lineVenta['cambiotipo']=substr($line, 231,10);
                $lineVenta['cantidadalicuotas']=substr($line, 241,1);
                $lineVenta['operacioncodigo']=substr($line, 242,1);
                $lineVenta['otrostributos']=substr($line, 243,13).'.'.substr($line, 256, 2);
                $lineVenta['fechavencimientopago']=substr($line, 258,8);
                //$lineVenta['lineacompleta']=$line;
                $ventasArray[$i]['Venta']=$lineVenta;
                $i++;
                $j++;
                // $line="";
                unset($lineVenta);
            }
            $tituloButton= $errorInFileVenta?$dirVenta->name." Archivo con Error": $dirVenta->name;
            echo $this->Form->button(
                $tituloButton .'</br>
            <label>Ventas: '.$j.'</label>',
                array(
                    'class'=>'buttonImpcli4',
                    'onClick'=>"deletefile('".$dirVenta->name."','".$cliid."','ventas','".$periodo."')",
                    'id'=>'',
                ),
                array()
            );
            fclose ( $handler );
            $dirVenta->close(); // Be sure to close the file when you're done
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

        $filesVentas = $dirVentas->find('.*\.csv');
        $errorInFileVenta=false;

        foreach ($filesVentas as $dirVenta) {
            if(is_readable($dirVentas->pwd() . DS . $dirVenta)){
                $mostrarTabla=true;
            }else{
                echo "No se puede acceder al archivo:".$dirVenta."</br>";
                break;
            }

            $dirVenta = new File($dirVentas->pwd() . DS . $dirVenta);
            $dirVenta->open();
            $contents = $dirVenta->read();
            // $file->delete(); // I am deleting this file
            $handler = $dirVenta->handle;
            $j=0;

            while (($line = fgetcsv($handler, 1000, ";")) !== false) {
//                $line = utf8_decode($line);
                $date = date_parse($line[0]."-".$line[1]."-".$line[2]);
                if ($date["error_count"] == 0 && checkdate($date["month"], $date["day"], $date["year"]))
                    $parceLine = true;
                else
                    $parceLine = false;

                if((!$parceLine)||$line[1]==""||$line[2]==""||$line[3]==""||$line[4]==""){
                    continue;
                }

                // process the line read.
                $lineVenta = array();


                $lineVenta['fecha']=date('d-m-Y',strtotime($line[0]."-".$line[1]."-".$line[2]));
                $lineVenta['comprobantetipo']=$line[3];
                $lineVenta['puntodeventa']=$line[4];
                $lineVenta['comprobantenumero']=$line[5];
                $lineVenta['codigodocumento']=80;
                $lineVenta['identificacionnumero']=$line[7];
                $lineVenta['nombre']=$line[6];

                //todo primero que nada tengo que buscar si esta venta no existe ya con otra alicuota
                $numeroVenta = $i;
                $ventayacargadaenformulario = false;
                foreach ($ventasArray as $v => $venta) {
                    if(isset($venta['Venta']['comprobantenumero'])){
                        $mismocomprobante = $venta['Venta']['comprobantenumero']==$lineVenta['comprobantenumero'];
                        $mismopuntodeventa = $venta['Venta']['puntodeventa']==$lineVenta['puntodeventa'];
                        $mismotipocomprobante = $venta['Venta']['comprobantetipo']==$lineVenta['comprobantetipo'];
                        if($mismocomprobante&&$mismopuntodeventa&&$mismotipocomprobante){
                            $lineVenta = $venta['Venta'];
                            $numeroVenta = $v;
                            $ventayacargadaenformulario = true;
                            break;
                        }
                    }
                }
                if(!$ventayacargadaenformulario){
                    $ventasArray[$numeroVenta] = array();
                    $ventasArray[$numeroVenta]['Venta'] = array();
                }

                //aveces la identificacionnumero viene vacia (todos 0) entonces vamos a poner el nombre
                // en estos casos como identificacion numero
                if(ltrim($lineVenta['identificacionnumero'],'0')==''){
                    $lineVenta['identificacionnumero'] = '20000000001';
                }
                //hay algunos casos donde los registros vienen sin nombre y sin cuit, en estos casos
                //vamos a poner que el subcliente es un consumidor final y lo vamos a cargar
                //el formato del consumidor final es
                //Nombre:   Consumidor Final
                //CUIT:     20000000001
                //DNI:      20000000001
                if(ltrim($lineVenta['identificacionnumero'],' ')=='' && ltrim($lineVenta['nombre'],' ')==''){
                    $lineVenta['nombre'] = 'Consumidor Final';
                    $lineVenta['identificacionnumero'] = '20000000001';
                }
//                $lineVenta['condicioniva']=$line[8];
//                $lineVenta['provincia']=$line[9];
//                $lineVenta['localidad']=$line[10];
//                $lineVenta['tipodebito']=$line[11];

                //hay que acumular por que puede haber varias ventas con los mismos datos por las distintas alicuotas
                if(!isset($lineVenta['importetotaloperacion'])){
                    $lineVenta['importetotaloperacion'] = 0;
                    $lineVenta['importeconceptosprecionetogravado'] = 0;
                    $lineVenta['percepcionesnocategorizados'] = 0;
                    $lineVenta['importeoperacionesexentas'] = 0;
//                    $lineVenta['importepercepcionespagosacuenta'] = 0;
                    $lineVenta['importeingresosbrutos'] = 0;
                    $lineVenta['importeimpuestosmunicipales'] = 0;
                    $lineVenta['importeimpuestosinternos'] = 0;
                    $lineVenta['cantidadalicuotas'] = 0;
                }
                $lineVenta['importetotaloperacion'] += toNumber($line[17]);
                $lineVenta['importeconceptosprecionetogravado']+= toNumber($line[15]);
                $lineVenta['percepcionesnocategorizados']+= toNumber($line[11]);
                $lineVenta['importeoperacionesexentas']+= toNumber($line[16]);
//                $lineVenta['importepercepcionespagosacuenta']+= toNumber($line[12]);
                $lineVenta['importeingresosbrutos']+= toNumber($line[12]);
                $lineVenta['importeimpuestosmunicipales']+= toNumber($line[13]);
                $lineVenta['importeimpuestosinternos']+= toNumber($line[14]);

                //$lineVenta['codigomoneda']=$line[16];//no se carga
                //$lineVenta['cambiotipo']=$line[17];//no se carga
                $lineVenta['cantidadalicuotas']+=1;
//                $lineVenta['operacioncodigo']=$line[19];
//                $lineVenta['otrostributos']=$line[20];
//                $lineVenta['fechavencimientopago']=$line[21];

                $ventasArray[$numeroVenta]['Venta']=$lineVenta;

                $lineAlicuota = array();
                $lineAlicuota['comprobantetipo'] = $lineVenta['comprobantetipo'];
                $lineAlicuota['puntodeventa'] = $lineVenta['puntodeventa'];
                $lineAlicuota['comprobantenumero'] = $lineVenta['comprobantenumero'];
                $lineAlicuota['importenetogravado'] = toNumber($line[9]);
                $lineAlicuota['alicuotaiva'] = $line[8];

                $lineAlicuota['impuestoliquidado'] = toNumber($line[10]);

                if(!isset($ventasArray[$numeroVenta]['Alicuota'])){
                    $ventasArray[$numeroVenta]['Alicuota']=array();
                }
                array_push($ventasArray[$numeroVenta]['Alicuota'], $lineAlicuota);

                $i++;
                $j++;
                // $line="";
                unset($lineVenta);
            }
            $tituloButton= $errorInFileVenta?$dirVenta->name." Archivo con Error": $dirVenta->name;
            echo $this->Form->button(
                $tituloButton .'</br>
                <label>Ventas: '.$j.'</label>',
                array(
                    'class'=>'buttonImpcli4',
                    'onClick'=>"deletefile('".$dirVenta->name."','".$cliid."','ventas','".$periodo."')",
                    'id'=>'',
                ),
                array()
            );
            fclose ( $handler );
            $dirVenta->close(); // Be sure to close the file when you're done
            if(!is_resource($handler)){
                //echo "handler cerrado con exito";
            }else{
                //echo "handler cerrado ABIERTO!";
            }
        }
        ?>
        </br></br></br></br></br>Alicuotas:</br>
        <?php
        //ACA VAMOS A BUSCAR LOS TXT
        $filesAlicuotas = $dirAlicuotas->find('.*\.txt');
        //vamos a crear un array de Ventas con los datos que vayamos recavando de cada archivo
        $i=0;
        $alicuotasArray = array();
        $errorInFileAlicuota=false;
        foreach ($filesAlicuotas as $dirAlicuota) {
            if(is_readable($dirAlicuotas->pwd() . DS . $dirAlicuota)){
                $mostrarTabla = true;
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
                $lineAlicuota['importenetogravado'] = substr($line, 28, 13).'.'.substr($line, 41, 2);
                $lineAlicuota['alicuotaiva'] = substr($line, 43, 4);
                $lineAlicuota['impuestoliquidado'] = substr($line, 47, 13).'.'.substr($line, 60, 2);
                $i++;
                $j++;
                //ahora que tenemos la alicuota en un array tenemos que buscar la venta a la que pertenece y agregarla
                $k=0;
                foreach ($ventasArray as $venta) {
                    $mismocomprobante = $venta['Venta']['comprobantenumero']==$lineAlicuota['comprobantenumero'];
                    $mismopuntodeventa = $venta['Venta']['puntodeventa']==$lineAlicuota['puntodeventa'];
                    $mismotipocomprobante = $venta['Venta']['comprobantetipo']==$lineAlicuota['comprobantetipo'];
                    if($mismocomprobante&&$mismopuntodeventa&&$mismotipocomprobante){
                        if(!isset($venta['Alicuota'])){
                            $venta['Alicuota']=array();
                        }
                        array_push($venta['Alicuota'], $lineAlicuota);
                        $ventasArray[$k]=$venta;
                        break;
                    }
                    $k++;
                }
            }
            echo $this->Form->button(
                $dirAlicuota->name.'</br>
            <label>Alicuotas: '.$j.'</label>',
                array(
                    'class'=>'buttonImpcli4',
                    'onClick'=>"deletefile('".$dirAlicuota->name."','".$cliid."','alicuotas','".$periodo."')",
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

        ?>

    </div>
    <div  class="index" style="width: inherit;float: left;margin-left: -10px;height: 250px;">
        Ultimas ventas cargadas en el sistema
        <table>
            <tr>
                <td>Punto de Venta</td>
                <td>Comprobante</td>
                <td>Numero Comprobante</td>
            </tr>
            <?php
            foreach ($ultimasventas as $miventa){?>
                <tr>
                    <td><?php
                        foreach ($puntosdeventas as $pv => $puntosdeventa){
                            if($pv==$miventa['Puntosdeventa']['id']){
                                echo $puntosdeventa;
                                break;
                            }
                        }
                        ?></td>
                    <td><?php
                        foreach ($comprobantes as $c => $comprobante){
                            if($c==$miventa['Comprobante']['id']){
                                echo $comprobante;
                                break;
                            }
                        }
                        ?></td>
                    <td><?php echo $miventa[0]['maxnumerocomprobante']; ?></td>
                </tr>
                <?php
            }

            ?>
        </table>
    </div>
<?php
//die("pre puntos de ventas no cargados");
$PuntoDeVentaNoCargado=array();
$SubclienteNoCargado=array();
$VentasConFechasIncorrectas = array();

foreach ($ventasArray as $venta) {
    $agregarPuntoDeVenta=true;
    foreach ($puntosdeventas as $puntosdeventa) {
        if($venta['Venta']['puntodeventa']== $puntosdeventa){
            $agregarPuntoDeVenta=false;
        }
    }
    if($agregarPuntoDeVenta){
        $miPuntoDeVenta=array();
        $miPuntoDeVenta['nombre']=$venta['Venta']['puntodeventa'];
        if(!in_array($miPuntoDeVenta,$PuntoDeVentaNoCargado)){
            $PuntoDeVentaNoCargado[]=$miPuntoDeVenta;
        }
    }
    /*
     * $lineVenta['codigodocumento']=substr($line, 56,2);
     * $lineVenta['identificacionnumero']=substr($line, 58,20);
     */
    $agregarSubcliente=true;
    foreach ($subclientes as $subcliente){
        if (strpos($subcliente, ltrim($venta['Venta']['identificacionnumero'],'0')) !== false) {
            $agregarSubcliente=false;
            break;
        }
    }
    if($agregarSubcliente){
        $miSubcliente=array();
        $miSubcliente['cuit']=ltrim($venta['Venta']['identificacionnumero'],'0');
        $miSubcliente['nombre']=$venta['Venta']['nombre'];
        if(!in_array($miSubcliente,$SubclienteNoCargado)){
            $SubclienteNoCargado[]=$miSubcliente;
        }
    }
    $periodoVenta = date('m-Y',strtotime($venta['Venta']['fecha']));
    //vamos a controlar que las fechas de las ventas sean las correctas (esten en este periodo)
    if($periodo!=$periodoVenta){
        //esta venta no es del periodo
        $VentasConFechasIncorrectas[]=$venta['Venta']['fecha'];
    }

}
if(count($PuntoDeVentaNoCargado)!=0||count($SubclienteNoCargado)!=0||count($VentasConFechasIncorrectas)!=0||!$mostrarTabla){ ?>
    <div class="index">
        <?php
        if($mostrarTabla) {
            echo $this->Form->create('Venta', array(
                    'action' => 'agregarparaimportar',
                    'id' => 'VentaAgregarImportar',
                    'class' => 'formTareaCarga'
                )
            );
            ?>
            <table class="tablePuntodeVentaEditForm">
                <?php
                if (count($PuntoDeVentaNoCargado) != 0) {
                    ?>
                    <tr>
                        <td colspan="0">Nuevos Puntos de Ventas que se necesitan agregar para procesar las ventas de los
                            archivos
                        </td>
                    </tr>
                    <tr>
                        <?php
                        foreach ($PuntoDeVentaNoCargado as $puntodeventa) { ?>
                            <td><?php
                                echo $this->Form->input('Puntosdeventa.' . $i . '.cliente_id', array('type' => 'hidden', 'value' => $cliid));
                                echo $this->Form->input('Puntosdeventa.' . $i . '.nombre', array(
                                    'label' => '',
                                    'maxlength' => '4',
                                    'value' => str_pad($puntodeventa['nombre'], 5, "0", STR_PAD_LEFT)
                                ));
                                echo $this->Form->input('Puntosdeventa.' . $i . '.sistemafacturacion', array(
                                    'label' => '',
                                    'type' => 'select',
                                    'options' => $optionSisFact));
                                echo $this->Form->input('Puntosdeventa.' . $i . '.domicilio_id', array(
                                    'required' => 'required',
                                    'label' => ''
                                    )
                                );
                                echo $this->Form->input('Puntosdeventa.' . $i . '.periodo', array('type' => 'hidden', 'value' => $periodo));
                                ?>
                            </td>
                            <?php
                            $i++;
                        } ?>
                    </tr>
                    <?php
                }
                if (count($SubclienteNoCargado) != 0) {
                    ?>
                    <tr>
                        <td colspan="0">Nuevos Clientes que se necesitan agregar para procesar las ventas de los archivos
                        </td>
                    </tr>
                    <?php
                    foreach ($SubclienteNoCargado as $subcliente) { ?>
                        <tr>
                            <td>
                                <?php
                                echo $this->Form->input('Subcliente.' . $j . '.cliente_id', array('type' => 'hidden', 'value' => $cliid));
                                echo $this->Form->input('Subcliente.' . $j . '.cuit', array(
                                        'type' => 'hidden',
                                        'value' => ltrim($subcliente['cuit'], '0'),
                                        'style' => "width:200px",
                                    )
                                );
                                echo $this->Form->input('Subcliente.' . $j . '.dni', array(
                                        'label' => '',
                                        'value' => ltrim($subcliente['cuit'], '0'),
                                        'style' => "width:200px",
                                    )
                                );
                                echo $this->Form->input('Subcliente.' . $j . '.nombre', array(
                                        'label' => '',
                                        'value' => ltrim($subcliente['nombre'], '0'),
                                        'style' => "width:400px",
                                    )
                                );
                                echo $this->Form->input('Subcliente.' . $j . '.periodo', array('type' => 'hidden', 'value' => $periodo));

                                ?>
                            </td>
                        </tr>
                        <?php
                        $j++;
                    }
                }
                if (count($VentasConFechasIncorrectas) != 0) { ?>
                    <tr>
                        <td colspan="0">Las siguientes ventas tienen errores con las fechas, por favor elimine el archivo
                            y suba uno con ventas del periodo: <?php echo $periodo; ?></td>
                    </tr>
                    <tr>
                        <td colspan="0">
                            <?php
                            foreach ($VentasConFechasIncorrectas as $m => $item) {
                                echo $m . ":" . $item;
                                echo ($m + 1) % 10 == 0 ? '</br>' : '//';
                            }
                            unset($item); ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php
            echo $this->Form->submit('Agregar Nuevos', array('style' => 'width: 200px;'));
            echo $this->Form->end();
        }
        ?>
    </div>
    <?php
} else {
    ?>
    <div class="index">
        <?php

        echo $this->Form->input('puntosdeventasdomicilio', array(
            'options' => $puntosdeventasdomicilio,
            'div'=>array(
                'style'=>'display:none'
            )
        ));
        //formulario oculto que va a contener en json todos los datos del formulario que esta debajo(lo hacemos asi para automatizar el envio)
        echo $this->Form->create('Venta',array(
                'controller'=>'Venta',
                'action'=>'cargarventas',
                'id'=>'VentaImportarAEnviar',
                'class'=>'formTareaCarga',
                'inputDefaults' => array(
                    'div' => true,
                    'label' => false,
                ),
            )
        );
        echo $this->Form->input('Venta.0.jsonencript',array(
                'label'=>($i+9)%10==0?'N°':'',
                'value'=>'',
                'type'=>'hidden',
            )
        );
        echo $this->Form->submit('+', array(
                'div'=> array('style'=>'display:none')
            )
        );
        echo $this->Form->end();
        ?>
        <table id="filtros" >
            <tr>
                <td style="width:106px;padding: 4px 0px;">
                    Filtros
                </td>
                <td style="width:50px">
                    <?php
                    echo $this->Form->input('Filtro.0.comprobante_id', array(
                            'empty' => 'Cmprobante',
                            'style'=>"width: 50px;",
                        )
                    );?>
                </td>
                <td style="width:65px;padding: 4px 0px;">
                    <?php
                    echo $this->Form->input('Filtro.0.puntosdeventa_id', array(
                            'empty' => 'Punto de V.',
                            'style'=>"width: 65px;",
                        )
                    );?>
                </td>
                <td style="width:55px"></td>
                <td style="width:176px;padding: 4px 0px;">
                    <?php
                    echo $this->Form->input('Filtro.0.subcliente_id', array(
                            'empty' => 'Cliente',
                            'style'=>"width: 176px;",
                        )
                    );?>
                </td>
                <td style="width:80px;padding: 4px 0px;">
                    <?php
                    echo $this->Form->input('Filtro.0.condicioniva', array(
                            'empty' => 'Condicion',
                            'style'=>"width: 80px;",
                            'options'=>$condicionesiva,
                        )
                    );?>
                </td>
                <td style="width:80px;padding: 4px 0px;">
                    <?php
                    echo $this->Form->input('Filtro.0.actividadcliente_id', array(
                            'empty' => 'Actividad',
                            'style'=>"width: 80px;",
                            'options'=>$actividades,
                        )
                    );?>
                </td>
                <td style="width:150px"></td>
                <td style="width:83px;padding: 4px 0px;">
                    <?php
                    echo $this->Form->input('Filtro.0.tipodebito', array(
                            'empty' => 'Debito',
                            'style'=>"width: 83px;",
                            'options'=>$tipodebitos,
                        )
                    );?>
                </td>
                <td style="width:55px;padding: 4px 0px;">
                    <?php
                    echo $this->Form->input('Filtro.0.alicuota', array(
                            'empty' => 'Alicuota',
                            'style'=>"width: 55px;",
                            'options'=>$alicuotas,
                        )
                    );?>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <?php
        //formulario donde se van a llenar todos los datos importados, que luego seran enviador por ajax a traves del formulario anterior
        echo $this->Form->create('Venta',array(
                'controller'=>'Venta',
                'action'=>'cargarventas',
                'id'=>'VentaImportar',
                'class'=>'formTareaCarga',
                'inputDefaults' => array(
                    'div' => true,
                    'label' => false,
                ),
            )
        );
        function customSearch($keyword, $arrayToSearch){
            foreach($arrayToSearch as $key => $arrayItem){
                if( stristr( $arrayItem, $keyword ) ){
                    return $key;
                }
            }
            return 0;
        }
        $ventaNumero = 1;
        ?>
        Ventas ya cargadas :

        <table style="width: 100%;padding: 0px;margin: 0px;" id="tablaFormVentas" >
            <?php
            $i=1;
            $cantVentasYaguardadas = 0;
            $misVentasYaCargadas = "";
            foreach ($ventasArray as $keyVenta => $venta) {
                $numalicuota=0;
                foreach ($venta['Alicuota'] as $keyAlicuota => $alicuota) {
                    //hay que controlar que las venas anteriores cargadas no contengan la venta que estamos por mostrar
                    $ventaCargadaPreviamente = false;
                    $comprobanteTipoNuevo = ltrim(customSearch($venta['Venta']['comprobantetipo'],$comprobantes), '0');
                    if($comprobanteTipoNuevo==0){
                        Debugger::dump("ERROR:: no se encontro el comprobante: ".$venta['Venta']['comprobantetipo']);
                    }
                    $pdvNuevo = ltrim(customSearch($venta['Venta']['puntodeventa'],$puntosdeventas), '0');
                    $alicuotaNuevo = customSearch($alicuota['alicuotaiva'],$alicuotas);
                    $numeroComprobante = $venta['Venta']['comprobantenumero'];
                    $clienteNuevo = customSearch(ltrim($venta['Venta']['identificacionnumero'], '0'),$subclientes);

                    foreach ($ventasperiodo as $ventaYaCargada) {
                        $igualTipoComprobante=false;
                        $igualPuntoDV=false;
                        $igualAlicuota=false;
                        $igualNumeroComprobante=false;
                        if($comprobanteTipoNuevo==$ventaYaCargada['Venta']['comprobante_id']){
                            $igualTipoComprobante = true;
                        }
                        if($pdvNuevo==$ventaYaCargada['Venta']['puntosdeventa_id']){
                            $igualPuntoDV = true;
                        }
                        if($alicuotaNuevo==$ventaYaCargada['Venta']['alicuota']){
                            $igualAlicuota = true;
                        }
                        if($numeroComprobante*1==$ventaYaCargada['Venta']['numerocomprobante']*1){
                            $igualNumeroComprobante = true;
                        }
                        if ($igualTipoComprobante&&$igualPuntoDV&&$igualAlicuota&&$igualNumeroComprobante){
                            $misVentasYaCargadas +=
                                $venta['Venta']['comprobantetipo']."-".
                                $venta['Venta']['puntodeventa']."-".
                                $numeroComprobante." // ";
                            $ventaCargadaPreviamente = true;
                            $cantVentasYaguardadas++;
                            unset($alicuota[$keyAlicuota]);
                            unset($venta[$keyVenta]);
                            break;
                        }
                    }
                    if(!$ventaCargadaPreviamente) {
                        $class = "par";
                        if ($ventaNumero%2==0){
                            $class = "par";
                        }else{
                            $class = "impar";

                        }
                        $ventaNumero++;
                        ?>
                        <tr>
                            <td style="width: 100%;padding: 0px;margin: 0px; ">
                                <div style="margin-top: 1px;" class="ventaFormVertical <?php echo $class;?>" >
                                    <?php
                                    echo $this->Form->input('Venta.' . $i . '.i', array(
                                            'label' => ($i + 9) % 10 == 0 ? 'N°' : '',
                                            'value' => $i,
                                            'style' => "width: 20px;",
                                        )
                                    );
                                    echo $this->Form->input('Venta.' . $i . '.id', array('type' => 'hidden'));
                                    echo $this->Form->input('Venta.' . $i . '.cliente_id', array('type' => 'hidden', 'value' => $cliid));
                                    echo $this->Form->input('Venta.' . $i . '.fecha', array(
                                            'class' => 'datepicker',
                                            'type' => 'text',
                                            'label' => ($i + 9) % 10 == 0 ? 'Fecha' : '',
                                            'readonly' => 'readonly',
                                            'default' => date('d-m-Y', strtotime($venta['Venta']['fecha'])),
                                            'style' => "width:75px"
                                        )
                                    );
                                    //Este Array de comprobantes debe incluir array{id,codigo} y se debe seleccionar por codigo
                                    echo $this->Form->input('Venta.' . $i . '.comprobante_id', array(
                                        'default' => $comprobanteTipoNuevo,
                                        'style' => "width: 50px;",
                                        'label' => ($i + 9) % 10 == 0 ? 'Comp.' : '',
                                        'class' => 'filtrocomprobante'
                                    ));
                                    //seleccionar el punto de venta por "numero(nombre)"
                                    echo $this->Form->input('Venta.' . $i . '.puntosdeventa_id', array(
                                        'options' => $puntosdeventas,
                                        'default' => $pdvNuevo,
                                        'label' => ($i + 9) % 10 == 0 ? 'P.Vent.' : '',
                                        'style' => 'width:65px;',
                                        'class' => 'filtropuntodeventa',
                                    ));
                                    echo $this->Form->input('Venta.' . $i . '.numerocomprobante', array(

                                        //el numero de comprobante deberia tener 20 digitos pero tiene 8 entonces vamos a tomar los ultimos 8
                                        'value' => $peanio = substr($numeroComprobante, -8),
                                        'label' => ($i + 9) % 10 == 0 ? 'Num.' : '',
                                        'style' => 'width:60px;',
                                    ));
                                    //se supone que cuando generemos este formulario ya van a estar creados todos los subclientes
                                    //asi que solo tendriamos queseleccionar por numero de idenficicacion
                                    echo $this->Form->input('Venta.' . $i . '.subcliente_id', array(
                                            'default' => $clienteNuevo,
                                            'defaultmio' => $clienteNuevo,
                                            'required' => true,
                                            'type' => 'hidden',
                                            'div'=>['style'=>'display:none']
                                        )
                                    );
                                    echo $this->Form->input('Venta.' . $i . '.subclientenombre', array(
                                            'label' => ($i + 9) % 10 == 0 ? 'Cliente' : '',
                                            'style' => 'width:84px;',
                                            'class' => 'filtrosubcliente',
                                            'disabled' => 'disabled',
                                            'value' => $venta['Venta']['nombre']
                                        )
                                    );
                                    echo $this->Form->input('Venta.' . $i . '.subclientecuit', array(
                                            'label' => ($i + 9) % 10 == 0 ? 'CUIT' : '',
                                            'style' => 'width:84px;',
                                            'disabled' => 'disabled',
                                            'value' => $venta['Venta']['identificacionnumero']
                                        )
                                    );
                                    $condicioniva = 'monotributista';//defaultavalue
                                    $mitipodebito = 'Debito Fiscal';//Default Value
                                    $classcondicionIVA="";
                                    foreach ($supercomprobantes as $micomprobante) {
                                        if ($venta['Venta']['comprobantetipo'] == $micomprobante['Comprobante']['codigo']) {
                                            if ($micomprobante['Comprobante']['tipo'] == 'A' ) {
                                                $condicioniva = 'Responsable Inscripto';//defaultavalue
                                            } else  if ($micomprobante['Comprobante']['tipo'] == 'B'){
                                                //aca me tengo que fijar si no tiene cuit es consumidor final
                                                //y si no tiene cuit voy a marcar monotributista pero tengo q resaltarlo
                                                //para que se chekee si hay que buscar ese cuit y chekiar q no sea excento
                                                if($venta['Venta']['identificacionnumero'] == '20000000001'){
                                                }else{
                                                    $classcondicionIVA="controlarInput";
                                                }
                                                $condicioniva = '"Cons. F/Exento/No Alcanza"';
                                            }else{
                                                $condicioniva = 'Monotributista';
                                            }
                                            if ($micomprobante['Comprobante']['tipodebitoasociado'] == 'Debito fiscal o bien de uso') {
                                                $mitipodebito = 'Debito Fiscal';
                                            } else {
                                                $mitipodebito = 'Restitucion debito fiscal';
                                            }
                                            break;
                                        }
                                    }
                                    if($venta['Venta']['identificacionnumero'] == '20000000001'){
                                        $condicioniva = '"Cons. F/Exento/No Alcanza"';
                                    }
                                    echo $this->Form->input('Venta.' . $i . '.condicioniva', array(
                                            'type' => 'select',
                                            'label' => ($i + 9) % 10 == 0 ? 'Cond.IVA' : '',
                                            'options' => $condicionesiva,
                                            'style' => 'width:80px',
                                            'div' => array('class' => 'inputAControlar'),
                                            'defaultoption' => $condicioniva,
                                            'class' => 'aplicableATodos filtrocondicioniva '.$classcondicionIVA,
                                            'inputclass' => 'VentaAddCondicionIVA',
                                        )
                                    );
                                    //esto no trae asi que vamos a tener que elegir
                                    echo $this->Form->input('Venta.' . $i . '.actividadcliente_id', array(
                                            'type' => 'select',
                                            'options' => $actividades,
                                            'label' => ($i + 9) % 10 == 0 ? 'Actividad' : '',
                                            'style' => 'width:80px',
                                            'div' => array('class' => 'inputAControlar'),
                                            'class' => 'aplicableATodos filtroactividadcliente',
                                            'inputclass' => 'VentaAddActividad',
                                        )
                                    );
                                    //esto no trae asi que vamos a tener que elegir
                                    echo $this->Form->input('Venta.' . $i . '.localidade_id', array(
                                            'class' => "chosen-select",
                                            'label' => ($i + 9) % 10 == 0 ? 'Localidad' : '',
                                            'style' => 'width:150px',
                                            'defaultoptionlocalidade' => str_pad($venta['Venta']['puntodeventa'], 5, "0", STR_PAD_LEFT),
                                            'class' => 'aplicableATodos chosen-select',
                                            'inputclass' => 'VentaAddLocalidad',
                                            'required' => true,
                                            'div' => array('class' => 'inputAControlar')
                                        )
                                    );
                                    //esto no trae asi que vamos a tener que elegir
                                    echo $this->Form->input('Venta.' . $i . '.tipodebito', array(
                                        'defaultoption' => $mitipodebito,
                                        'options' => $tipodebitos,
                                        'label' => ($i + 9) % 10 == 0 ? 'Tipo Deb.' : '',
                                        'style' => 'width:83px',
                                        'class' => 'aplicableATodos chosen-select filtrotipodebito',
                                        'inputclass' => 'VentaAddTipoDebito',
                                        'div' => array('class' => 'inputAControlar'),


                                    ));
                                    echo $this->Form->input('Venta.' . $i . '.alicuota', array(
                                        'defaultoption' => $alicuota['alicuotaiva'],
                                        'label' => ($i + 9) % 10 == 0 ? 'Alicuota' : '',
                                        'style' => 'width:55px',
                                        'class' => 'aplicableATodos filtroalicuota',
                                        'inputclass' => 'VentaAddAlicuota',
                                    ));
                                    $importenetogravado = 0;
                                    $tieneMonotributo = $cliente['Cliente']['tieneMonotributo'];
                                    if ($tieneMonotributo) {
                                        $importenetogravado = $venta['Venta']['importetotaloperacion'] * 1;
                                    } else {
                                        $importenetogravado = $alicuota['importenetogravado'] * 1;
                                    }
                                    echo $this->Form->input('Venta.' . $i . '.neto', array(
                                        'style' => 'max-width: 100px;',
                                        'label' => ($i + 9) % 10 == 0 ? 'Neto' : '',
                                        'value' => $importenetogravado,
                                    ));
                                    echo $this->Form->input('Venta.' . $i . '.iva', array(
                                        'style' => 'max-width: 100px;',
                                        'label' => ($i + 9) % 10 == 0 ? 'IVA' : '',
                                        'value' => $alicuota['impuestoliquidado']*1,
                                    ));
                                    echo $this->Form->input('Venta.' . $i . '.ivapercep', array(
                                        'style' => 'max-width: 70px;',
                                        'label' => ($i + 9) % 10 == 0 ? 'IVA Perc.' : '',
                                        'value' => $numalicuota==0?$venta['Venta']['percepcionesnocategorizados']*1:0,
                                    ));
                                    echo $this->Form->input('Venta.' . $i . '.iibbpercep', array(
                                        'style' => 'max-width: 70px;',
                                        'label' => ($i + 9) % 10 == 0 ? 'IIBB Perc.' : '',
                                        'value' => $numalicuota==0?$venta['Venta']['importeingresosbrutos']*1:0,
                                    ));
                                    echo $this->Form->input('Venta.' . $i . '.actvspercep', array(
                                        'style' => 'max-width: 70px;',
                                        'label' => ($i + 9) % 10 == 0 ? 'Ac.Vs. Perc.' : '',
                                        'value' => $numalicuota==0?$venta['Venta']['importeimpuestosmunicipales']*1:0,
                                    ));
                                    echo $this->Form->input('Venta.' . $i . '.impinternos', array(
                                        'label' => ($i + 9) % 10 == 0 ? 'Imp.Inter.' : '',
                                        'style' => 'max-width: 70px;',
                                        'value' => $numalicuota==0?$venta['Venta']['importeimpuestosinternos']*1:0,
                                    ));
                                    echo $this->Form->input('Venta.' . $i . '.nogravados', array(
                                        'style' => 'max-width: 70px;',
                                        'label' => ($i + 9) % 10 == 0 ? 'No Gravado' : '',
                                        'value' => $numalicuota==0?$venta['Venta']['importeconceptosprecionetogravado']*1:0,
                                    ));
                                    echo $this->Form->input('Venta.' . $i . '.excentos', array(
                                        'style' => 'max-width: 70px;',
                                        'label' => ($i + 9) % 10 == 0 ? 'Exento IVA' : '',
                                        'value' => $numalicuota==0?$venta['Venta']['importeoperacionesexentas']*1:0,
                                    ));
                                    echo $this->Form->input('Venta.' . $i . '.exentosactividadeseconomicas', array(
                                        'label' => ($i + 9) % 10 == 0 ? 'Exen. Ac.Ec' : '',
                                        'style' => 'max-width: 70px;',
                                        'value' => 0,
                                    ));
                                    echo $this->Form->input('Venta.' . $i . '.exentosactividadesvarias', array(
                                        'style' => 'max-width: 70px;',
                                        'label' => ($i + 9) % 10 == 0 ? 'Exento Ac.Vs' : '',
                                        'value' => 0,
                                    ));
                                    //$lineVenta['importepercepcionespagosacuenta']=substr($line, 168,15);
                                    echo $this->Form->input('Venta.' . $i . '.periodo', array('type' => 'hidden', 'value' => $periodo));
                                    //si hay mas de una alicuota este total se debe recalcular por que sino se va a cargar 2(n) veces uno para cada alicuota
                                    if(count($venta['Alicuota'])>=2){
                                        $totalrecalculado = 0;
                                        $totalrecalculado += $alicuota['importenetogravado'] * 1;
                                        $totalrecalculado += $alicuota['impuestoliquidado'];
                                        if($numalicuota==0){
                                            $totalrecalculado += $venta['Venta']['percepcionesnocategorizados'];
                                            $totalrecalculado += $venta['Venta']['importeingresosbrutos'];
                                            $totalrecalculado += $venta['Venta']['importeimpuestosmunicipales'];
                                            $totalrecalculado += $venta['Venta']['importeimpuestosinternos'];
                                            $totalrecalculado += $venta['Venta']['importeconceptosprecionetogravado'];
                                        }
                                    }else{
                                        $totalrecalculado = $venta['Venta']['importetotaloperacion'] * 1;
                                    }
                                    echo $this->Form->input('Venta.' . $i . '.total', array(
                                        'label' => ($i + 9) % 10 == 0 ? 'Total.' : '',
                                        'style' => 'max-width: 100px;',
                                        'value' => $totalrecalculado,
                                    ));
                                    $numalicuota++;
                                    //echo "<label>".json_encode($venta)."</label>";
                                    $i++;
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                }
                if($i==100){
                    if(count($ventasArray)>100){
                        echo $cantVentasYaguardadas." Ventas ya cargadas, faltan importar estas 100 ventas y ".(count($ventasArray)-$i-$cantVentasYaguardadas)." ventas mas, por favor continue cargando hasta que
                            finalize la carga de todas las ventas del archivo";
                    }else{
                        echo $cantVentasYaguardadas." Ventas ya cargadas, faltan importar estas ".$i." ventas por favor continue cargando hasta que
                            finalize la carga de todas las ventas del archivo";
                    }
                    break ;
                }
            }
            ?>
        </table>
        <?php
        if ($i > 1){
            echo $this->Form->submit('Importar', array(
                    'type'=>'image',
                    'title'=>'Importar',
                    'src' => $this->webroot.'img/check.png',
                    'class'=>'imgedit',
                    'style'=>'width:25px;height:25px;'
                )
            );
        }
        echo $this->Form->end();
        echo $misVentasYaCargadas; ?>
    </div>
    <?php
}
//Debugger::dump($ventasArray);
?>