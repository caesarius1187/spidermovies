<?php //ini_set('memory_limit', '-1');
echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false));
echo $this->Html->script('ventas/importar',array('inline'=>false)); ?>
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
                'controller' => 'clientes',
                'action' => 'tareacargar',
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
        Archivos Cargador previamente</br>
        Ventas:</br>
        <?php
        $ventasArray = array();

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
            while (($line = utf8_decode(fgets($handler))) !== false) {
                if(strlen($line)!=268){
                    //todo Mejorar la deteccion de errores
                    //$errorInFileVenta=true;
                    //echo strlen($line)."line lenght";
                    break;
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
                $lineVenta['lineacompleta']=$line;
                $ventasArray[$i]['Venta']=$lineVenta;
                $i++;
                $j++;
                $line="";
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
        }?>
        </br></br></br></br></br>Alicuotas:</br>
        <?php
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
                    if($mismocomprobante&&$mismopuntodeventa){
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
        }?>
    </div>
<?php //Debugger::dump($ventasArray)?>
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
                                    'label' => '', 'maxlength' => '4', 'value' => $puntodeventa['nombre']));
                                echo $this->Form->input('Puntosdeventa.' . $i . '.sistemafacturacion', array(
                                    'label' => '',
                                    'type' => 'select',
                                    'options' => $optionSisFact));
                                echo $this->Form->input('Puntosdeventa.' . $i . '.domicilio_id', array('label' => ''));
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
            echo $this->Form->submit('Agregar Nuevos', array('style' => 'width: 200px;float: none;'));
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
                <td style="width:20px"></td>
                <td style="width:75px"></td>
                <td style="width:50px">
                    <?php
                    echo $this->Form->input('Filtro.0.comprobante_id', array(
                            'empty' => 'filtrar',
                            'style'=>"width: 50px;",
                        )
                    );?>
                </td>
                <td style="width:65px">
                    <?php
                    echo $this->Form->input('Filtro.0.puntosdeventa_id', array(
                            'empty' => 'filtrar',
                            'style'=>"width: 65px;",
                        )
                    );?>
                </td>
                <td style="width:50px"></td>
                <td style="width:176px">
                    <?php
                    echo $this->Form->input('Filtro.0.subcliente_id', array(
                            'empty' => 'filtrar',
                            'style'=>"width: 176px;",
                        )
                    );?>
                </td>
                <td style="width:80px">
                    <?php
                    echo $this->Form->input('Filtro.0.condicioniva', array(
                            'empty' => 'filtrar',
                            'style'=>"width: 80px;",
                            'options'=>$condicionesiva,
                        )
                    );?>
                </td>
                <td style="width:80px">
                    <?php
                    echo $this->Form->input('Filtro.0.actividadcliente_id', array(
                            'empty' => 'filtrar',
                            'style'=>"width: 80px;",
                            'options'=>$actividades,
                        )
                    );?>
                </td>
                <td style="width:150px"></td>
                <td style="width:83px">
                    <?php
                    echo $this->Form->input('Filtro.0.tipodebito', array(
                            'empty' => 'filtrar',
                            'style'=>"width: 83px;",
                            'options'=>$tipodebitos,
                        )
                    );?>
                </td>
                <td style="width:55px">
                    <?php
                    echo $this->Form->input('Filtro.0.alicuota', array(
                            'empty' => 'filtrar',
                            'style'=>"width: 55px;",
                            'options'=>$alicuotas,
                        )
                    );?>
                </td>
                <td style="width:100px"></td>
                <td style="width:100px"></td>
                <td style="width:70px"></td>
                <td style="width:70px"></td>
                <td style="width:70px"></td>
                <td style="width:70px"></td>
                <td style="width:70px"></td>
                <td style="width:70px"></td>
                <td style="width:70px"></td>
                <td style="width:70px"></td>
                <td style="width:70px"></td>
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
        /*Debugger::dump($comprobantes);
        Debugger::dump($puntosdeventas);*/
        function customSearch($keyword, $arrayToSearch){
            foreach($arrayToSearch as $key => $arrayItem){
                if( stristr( $arrayItem, $keyword ) ){
                    return $key;
                }
            }
            return 0;
        }
        ?>
        Ventas ya cargadas :
        <table style="width: 100%;padding: 0px;margin: 0px;" id="tablaFormVentas" >
            <?php
            $i=1;
            $cantVentasYaguardadas = 0;
            foreach ($ventasArray as $keyVenta => $venta) {
                foreach ($venta['Alicuota'] as $keyAlicuota => $alicuota) {
                    //hay que controlar que las venas anteriores cargadas no contengan la venta que estamos por mostrar
                    $ventaCargadaPreviamente = false;
                    $comprobanteTipoNuevo = ltrim(customSearch($venta['Venta']['comprobantetipo'],$comprobantes), '0');
                    $pdvNuevo = ltrim(customSearch($venta['Venta']['puntodeventa'],$puntosdeventas), '0');
                    $alicuotaNuevo = customSearch($alicuota['alicuotaiva'],$alicuotas);
                    $numeroComprobante = ltrim($venta['Venta']['comprobantenumero'], '0');
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
                        if($numeroComprobante==$ventaYaCargada['Venta']['numerocomprobante']){
                            $igualNumeroComprobante = true;
                        }
                        if ($igualTipoComprobante&&$igualPuntoDV&&$igualAlicuota&&$igualNumeroComprobante){
                            echo
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
                        ?>
                        <tr>
                            <td style="width: 100%;padding: 0px;margin: 0px; ">
                                <div style="margin-top: 1px;background-color: white;" class="ventaFormVertical">
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
                                        'value' => $numeroComprobante,
                                        'label' => ($i + 9) % 10 == 0 ? 'Num.' : '',
                                        'style' => 'width:60px;',
                                    ));
                                    //se supone que cuando generemos este formulario ya van a estar creados todos los subclientes
                                    //asi que solo tendriamos queseleccionar por numero de idenficicacion
                                    echo $this->Form->input('Venta.' . $i . '.subcliente_id', array(
                                            'options' => $subclientes,
                                            'default' => $clienteNuevo,
                                            'defaultmio' => $clienteNuevo,
                                            'required' => true,
                                            'class' => 'chosen-select',
                                            'label' => ($i + 9) % 10 == 0 ? 'Cliente' : '',
                                            'style' => 'width:176px;',
                                            'class' => 'chosen-select filtrosubcliente'
                                        )
                                    );
                                    $condicioniva = 'monotributista';//defaultavalue
                                    $mitipodebito = 'Debito Fiscal';//Default Value
                                    foreach ($supercomprobantes as $micomprobante) {
                                        if ($venta['Venta']['comprobantetipo'] == $micomprobante['Comprobante']['codigo']) {
                                            if ($micomprobante['Comprobante']['tipo'] == 'A' || $micomprobante['Comprobante']['tipo'] == 'B') {
                                                $condicioniva = 'Responsable Inscripto';//defaultavalue
                                            } else {
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
                                    echo $this->Form->input('Venta.' . $i . '.condicioniva', array(
                                            'type' => 'select',
                                            'label' => ($i + 9) % 10 == 0 ? 'Cond.IVA' : '',
                                            'options' => $condicionesiva,
                                            'style' => 'width:80px',
                                            'div' => array('class' => 'inputAControlar'),
                                            'defaultoption' => $condicioniva,
                                            'class' => 'filtrocondicioniva',
                                        )
                                    );
                                    //esto no trae asi que vamos a tener que elegir
                                    echo $this->Form->input('Venta.' . $i . '.actividadcliente_id', array(
                                            'type' => 'select',
                                            'options' => $actividades,
                                            'label' => ($i + 9) % 10 == 0 ? 'Actividad' : '',
                                            'style' => 'width:80px',
                                            'div' => array('class' => 'inputAControlar'),
                                            'class' => 'filtroactividadcliente',
                                        )
                                    );
                                    //esto no trae asi que vamos a tener que elegir
                                    echo $this->Form->input('Venta.' . $i . '.localidade_id', array(
                                            'class' => "chosen-select",
                                            'label' => ($i + 9) % 10 == 0 ? 'Localidad' : '',
                                            'style' => 'width:150px',
                                            'defaultoptionlocalidade' => $venta['Venta']['puntodeventa'],
                                            'class' => 'chosen-select',
                                            'div' => array('class' => 'inputAControlar')
                                        )
                                    );
                                    //esto no trae asi que vamos a tener que elegir
                                    echo $this->Form->input('Venta.' . $i . '.tipodebito', array(
                                        'defaultoption' => $mitipodebito,
                                        'options' => $tipodebitos,
                                        'label' => ($i + 9) % 10 == 0 ? 'Tipo Deb.' : '',
                                        'style' => 'width:83px',
                                        'class' => 'chosen-select filtrotipodebito',
                                        'div' => array('class' => 'inputAControlar'),


                                    ));
                                    echo $this->Form->input('Venta.' . $i . '.alicuota', array(
                                        'defaultoption' => $alicuota['alicuotaiva'],
                                        'label' => ($i + 9) % 10 == 0 ? 'Alicuota' : '',
                                        'style' => 'width:55px',
                                        'class' => 'filtroalicuota'
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
                                        'value' => $alicuota['impuestoliquidado'] * 1,
                                    ));
                                    echo $this->Form->input('Venta.' . $i . '.ivapercep', array(
                                        'style' => 'max-width: 70px;',
                                        'label' => ($i + 9) % 10 == 0 ? 'IVA Perc.' : '',
                                        'value' => $venta['Venta']['percepcionesnocategorizados'] * 1,
                                    ));
                                    echo $this->Form->input('Venta.' . $i . '.iibbpercep', array(
                                        'style' => 'max-width: 70px;',
                                        'label' => ($i + 9) % 10 == 0 ? 'IIBB Perc.' : '',
                                        'value' => $venta['Venta']['importeingresosbrutos'] * 1,
                                    ));
                                    echo $this->Form->input('Venta.' . $i . '.actvspercep', array(
                                        'style' => 'max-width: 70px;',
                                        'label' => ($i + 9) % 10 == 0 ? 'Ac.Vs. Perc.' : '',
                                        'value' => $venta['Venta']['importeimpuestosmunicipales'] * 1,
                                    ));
                                    echo $this->Form->input('Venta.' . $i . '.impinternos', array(
                                        'label' => ($i + 9) % 10 == 0 ? 'Imp.Inter.' : '',
                                        'style' => 'max-width: 70px;',
                                        'value' => $venta['Venta']['importeimpuestosinternos'] * 1,
                                    ));
                                    echo $this->Form->input('Venta.' . $i . '.nogravados', array(
                                        'style' => 'max-width: 70px;',
                                        'label' => ($i + 9) % 10 == 0 ? 'No Gravado' : '',
                                        'value' => $venta['Venta']['importeconceptosprecionetogravado'] * 1,
                                    ));
                                    echo $this->Form->input('Venta.' . $i . '.excentos', array(
                                        'style' => 'max-width: 70px;',
                                        'label' => ($i + 9) % 10 == 0 ? 'Exento IVA' : '',
                                        'value' => $venta['Venta']['importeoperacionesexentas'] * 1,
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
                                    //$lineVenta['percepcionesnocategorizados']=substr($line, 138,15);
                                    //$lineVenta['importepercepcionespagosacuenta']=substr($line, 168,15);
                                    echo $this->Form->input('Venta.' . $i . '.periodo', array('type' => 'hidden', 'value' => $periodo));
                                    echo $this->Form->input('Venta.' . $i . '.total', array(
                                        'label' => ($i + 9) % 10 == 0 ? 'Total.' : '',
                                        'style' => 'max-width: 100px;',
                                        'value' => $venta['Venta']['importetotaloperacion'] * 1,
                                    ));
                                    //echo "<label>".json_encode($venta)."</label>";
                                    $i++;
                                    ?>
                                </div>
                            </td>
                        </tr>

                        <?php
                    }else{

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
        echo $this->Form->end(); ?>
    </div>
    <?php
}
//Debugger::dump($ventasArray);
?>