<?php
echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false));
echo $this->Html->script('compras/importar',array('inline'=>false));?>
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
        ); 	?>
    </div>
</div>
<div class="index" style="width: inherit;float: left;margin-left: -10px;height: 250px;">
<?php 
echo $this->Form->create('Compra', array('enctype' => 'multipart/form-data'));
?>
Nuevos Archivos:</br>
Cargar Archivo de Facturas:</br>
<?php
echo $this->Form->file('Compra.archivocompra');
?>
</br></br> </br></br></br>Cargar Archivo de Alicuotas:</br>
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
<div class="index" style="width: inherit;float: left;margin-left: -10px;height: 250px;">
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
        while (($line = utf8_decode(fgets($handler))) !== false) {
            if(strlen($line)!=327){
                $errorInFileCompra=true;
                //echo strlen($line)."line lenght";
                break;
            }
            $comprasArray[$i] = array();
            $comprasArray[$i]['Compra'] = array();
            // process the line read.
            $linecompra = array();
            $linecompra['fecha']=date('d-m-Y',strtotime(substr($line, 0,8)));
            $linecompra['comprobantetipo']=substr($line, 8,3);
            $linecompra['puntodeventa']=substr($line, 11,5);
            $linecompra['comprobantenumero']=substr($line, 16,20);
            $linecompra['numerodespacho']=substr($line, 36,16);
            $linecompra['codigodocumento']=substr($line, 52,2);
            $linecompra['identificacionnumero']=substr($line, 54,20);
            $linecompra['nombre']=substr($line, 74,30);
            $linecompra['importetotaloperacion']=substr($line, 104,13).'.'.substr($line, 117, 2);
            $linecompra['importeconceptosprecionetogravado']=substr($line, 119,13).'.'.substr($line, 132, 2);
            $linecompra['importeoperacionesexentas']=substr($line, 134,13).'.'.substr($line, 147, 2);
            $linecompra['importepercepcionespagosacuentaiva']=substr($line, 149,13).'.'.substr($line, 162, 2);
            $linecompra['importepercepcionespagosacuentaimpuestosnacionales']=substr($line, 164,13).'.'.substr($line, 177, 2);
            $linecompra['importeingresosbrutos']=substr($line, 179,13).'.'.substr($line, 192, 2);
            $linecompra['importeimpuestosmunicipales']=substr($line, 194,13).'.'.substr($line, 207, 2);
            $linecompra['importeimpuestosinternos']=substr($line, 209,13).'.'.substr($line, 222, 2);
            $linecompra['codigomoneda']=substr($line, 224,3);
            $linecompra['cambiotipo']=substr($line, 237,10);
            $linecompra['cantidadalicuotas']=substr($line, 237,1);
            $linecompra['operacioncodigo']=substr($line, 238,1);
            $linecompra['creditofiscalcomputable']=substr($line, 239,15);
            $linecompra['otrostributos']=substr($line, 254,15);
            $linecompra['cuit']=substr($line, 269,11);
            $linecompra['denominacion']=substr($line, 280,30);
            $linecompra['ivacomicion']=substr($line, 310,15);
            $linecompra['lineacompleta']=$line;
            $comprasArray[$i]['Compra']=$linecompra;
            $i++;
            $j++;
            $line="";
        }
        $tituloButton= $errorInFileCompra?$dirCompra->name." Archivo con Error": $dirCompra->name;
            echo $this->Form->button(
                $tituloButton .'</br>
            <label>Compras: '.$j.'</label>',
            array(
                'class'=>'buttonImpcli4',
                'onClick'=>"deletefile('".$dirCompra->name."','".$cliid."','".$periodo."')",
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
    }?>
    </br></br> </br></br></br>Alicuotas:</br>
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
            $lineAlicuota['importenetogravado'] = substr($line, 50, 13).'.'.substr($line, 63, 2);
            $lineAlicuota['alicuotaiva'] = substr($line, 65, 4);
            $lineAlicuota['impuestoliquidado'] = substr($line, 69, 13).'.'.substr($line, 82, 2);
            $i++;
            $j++;
            //ahora que tenemos la alicuota en un array tenemos que buscar la venta a la que pertenece y agregarla
            $k=0;
            foreach ($comprasArray as $compra) {
                $mismocomprobante = $compra['Compra']['comprobantenumero']==$lineAlicuota['comprobantenumero'];
                $mismopuntodeventa = $compra['Compra']['puntodeventa']==$lineAlicuota['puntodeventa'];
                if($mismocomprobante&&$mismopuntodeventa){
                    if(!isset($compra['Alicuota'])){
                        $compra['Alicuota']=array();
                    }
                    array_push($compra['Alicuota'], $lineAlicuota);
                    $comprasArray[$k]=$compra;
                }
                $k++;
            }
        }
        echo $this->Form->button(
            $dirAlicuota->name.'</br>
            <label>Alicuotas: '.$j.'</label>',
            array(
                'class'=>'buttonImpcli4',
                'onClick'=>"deletefile('".$dirAlicuota->name."','".$cliid."','".$periodo."')",
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
    }?>
</div>
<?php
//Debugger::dump($comprasArray);
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
} else {
?>
    <div class="index" style="overflow: auto;">
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
            'class'=>'imgedit',
            'style'=>'width:25px;height:25px;',
            'div'=> array('style'=>'display:none'))
    );
    echo $this->Form->end();
    //formulario donde se van a llenar todos los datos importados, que luego seran enviador por ajax a traves del formulario anterior
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
    <table style="width: 2230px; padding: 0px;margin: 0px;">
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
     $cantComprasYaguardadas=0;
     foreach ($comprasArray as $compra) {
         foreach ($compra['Alicuota'] as $alicuota) {
             //hay que controlar que las compras anteriores cargadas no contengan la compra que estamos por mostrar(vamos a incluir solo este periodo)

             $compraCargadaPreviamente = false;
             $comprobanteTipoNuevo = ltrim(customSearch($compra['Compra']['comprobantetipo'],$comprobantes), '0');
             $pdvNuevo = ltrim($compra['Compra']['puntodeventa'], '0');
             $alicuotaNuevo = customSearch($alicuota['alicuotaiva'],$alicuotas);
             $numeroComprobante = ltrim($compra['Compra']['comprobantenumero'], '0');
             $provedorNuevo = customSearch(ltrim($compra['Compra']['identificacionnumero'], '0'),$provedores);
             
             foreach ($comprasperiodo as $compraYaCargada) {
                 $igualTipoComprobante=false;
                 $igualPuntoDV=false;
                 $igualAlicuota=false;
                 $igualNumeroComprobante=false;
                 $igualProvedor=false;
                 if($comprobanteTipoNuevo==$compraYaCargada['Compra']['comprobante_id']){
                     $igualTipoComprobante = true;
                 }
                 if($pdvNuevo==$compraYaCargada['Compra']['puntosdeventa']){
                     $igualPuntoDV = true;
                 }
                 if($alicuotaNuevo==$compraYaCargada['Compra']['alicuota']){
                     $igualAlicuota = true;
                 }
                 if($numeroComprobante==$compraYaCargada['Compra']['numerocomprobante']){
                     $igualNumeroComprobante = true;
                 }
                 if($provedorNuevo==$compraYaCargada['Compra']['numerocomprobante']){
                     $igualNumeroComprobante = true;
                 }
                 if ($igualTipoComprobante&&$igualPuntoDV&&$igualAlicuota&&$igualNumeroComprobante){
                     echo
                         $compra['Compra']['comprobantetipo']."-".
                         $compra['Compra']['puntodeventa']."-".
                         $numeroComprobante." // ";
                     $compraCargadaPreviamente = true;
                     $cantComprasYaguardadas++;
                     break;
                 }
             }
             if(!$compraCargadaPreviamente) { ?>
                 <tr>
                     <td style="width: 100%;padding: 0px;margin: 0px; " colspan="25">
                         <div style="margin-top: 1px;background-color: white;" class="compraFormVertical">
                             <?php
                             echo $this->Form->input('Compra.' . $i . '.i', array(
                                     'label' => ($i + 9) % 10 == 0 ? 'N�' : '',
                                     'value' => $i,
                                     'style' => "width: 20px;",
                                 )
                             );
                             echo $this->Form->input('Compra.' . $i . '.id', array('type' => 'hidden'));
                             echo $this->Form->input('Compra.' . $i . '.cliente_id', array('type' => 'hidden', 'value' => $cliid));
                             echo $this->Form->input('Compra.' . $i . '.fecha', array(
                                     'class' => 'datepicker',
                                     'type' => 'text',
                                     'label' => ($i + 9) % 10 == 0 ? 'Fecha' : '',
                                     'readonly' => 'readonly',
                                     'default' => date('d-m-Y', strtotime($compra['Compra']['fecha'])),
                                     'style' => "width:75px"
                                 )
                             );
                             //Este Array de comprobantes debe incluir array{id,codigo} y se debe seleccionar por codigo
                             echo $this->Form->input('Compra.' . $i . '.comprobante_id', array(
                                 'defaultoption' => $compra['Compra']['comprobantetipo'],
                                 'style' => "width: 50px;",
                                 'label' => ($i + 9) % 10 == 0 ? 'Comp.' : '',
                             ));
                             //seleccionar el punto de venta por "numero(nombre)"
                             echo $this->Form->input('Compra.' . $i . '.puntosdeventa', array(
                                 'value' => $compra['Compra']['puntodeventa'],
                                 'label' => ($i + 9) % 10 == 0 ? 'P.Vent.' : '',
                                 'style' => 'width:65px;',
                             ));
                             echo $this->Form->input('Compra.' . $i . '.numerocomprobante', array(
                                 'value' => ltrim($compra['Compra']['comprobantenumero'], '0'),
                                 'label' => ($i + 9) % 10 == 0 ? 'Num.' : '',
                                 'style' => 'width:60px;',
                             ));
                             //se supone que cuando generemos este formulario ya van a estar creados todos los subclientes
                             //asi que solo tendriamos queseleccionar por numero de idenficicacion
                             echo $this->Form->input('Compra.' . $i . '.provedore_id', array(
                                     'options' => $provedores,
                                     'defaultoption' => ltrim($compra['Compra']['identificacionnumero'], '0'),
                                     'required' => true,
                                     'class' => 'chosen-select',
                                     'label' => ($i + 9) % 10 == 0 ? 'Provedor' : '',
                                     'style' => 'width:176px;',
                                     'class' => 'chosen-select'
                                 )
                             );
                             //esto no trae asi que vamos a tener que elegir
                             $condicionIVAArray = array();
                             foreach ($miscomprobantes as $micomprobante) {
                                  if ($micomprobante['Comprobante']['tipo'] == 'A' || $micomprobante['Comprobante']['tipo'] == 'B') {
                                     $condicionIVAArray = array(
                                         'type' => 'select',
                                         'label' => ($i + 9) % 10 == 0 ? 'Cond.IVA' : '',
                                         'options' => $condicionesiva,
                                         'style' => 'width:80px',
                                         'defaultoption' => 'Responsable Inscripto',
                                     );
                                 } else {
                                     $condicionIVAArray = array(
                                         'type' => 'select',
                                         'label' => ($i + 9) % 10 == 0 ? 'Cond.IVA' : '',
                                         'options' => $condicionesiva,
                                         'style' => 'width:80px',
                                         'defaultoption' => 'Monotributista',
                                     );
                                 }
                             }
                             echo $this->Form->input('Compra.' . $i . '.condicioniva', $condicionIVAArray);
                             //esto no trae asi que vamos a tener que elegir
                             echo $this->Form->input('Compra.' . $i . '.actividadcliente_id', array(
                                 'type' => 'select',
                                 'options' => $actividades,
                                 'label' => ($i + 9) % 10 == 0 ? 'Actividad' : '',
                                 'style' => 'width:80px',
                                 'div' => array('class' => 'inputAControlar')
                             ));

                             echo $this->Form->input('Compra.' . $i . '.localidade_id', array(
                                 'class' => "chosen-select",
                                 'label' => ($i + 9) % 10 == 0 ? 'Localidad' : '',
                                 'style' => 'width:150px',
                                 'class' => 'chosen-select',
                                 'div' => array('class' => 'inputAControlar'),
                                 'defaultoptionlocalidade' => $defaultDomicilio,
                             ));
                             //esto no trae asi que vamos a tener que elegir
                             echo $this->Form->input('Compra.' . $i . '.tipodebito', array(
                                 'default' => '',
                                 'options' => $tipocreditos,
                                 'label' => ($i + 9) % 10 == 0 ? 'Tipo Cre.' : '',
                                 'style' => 'width:55px',
                                 'div' => array('class' => 'inputAControlar')
                             ));
                             echo $this->Form->input('Compra.' . $i . '.tipogasto_id', array(
                                 'default' => '',
                                 'options' => $tipogastos,
                                 'label' => ($i + 9) % 10 == 0 ? 'Tipo Gasto.' : '',
                                 'style' => 'width:83px',
                                 'div' => array('class' => 'inputAControlar')
                             ));
                             echo $this->Form->input('Compra.' . $i . '.tipoiva', array(
                                 'label' => ($i + 9) % 10 == 0 ? 'Tipo(IVA)' : '',
                                 'style' => 'width:55px',
                                 'options' => array('directo' => 'Directo', 'prorateable' => 'Prorateable'),
                                 'div' => array('class' => 'inputAControlar')
                             ));
                             echo $this->Form->input('Compra.' . $i . '.imputacion', array(
                                     'type' => 'select',
                                     'style' => 'width:55px',
                                     'label' => ($i + 9) % 10 == 0 ? 'Imput.' : '',
                                     'options' => $imputaciones,
                                     'div' => array('class' => 'inputAControlar')
                                 )
                             );
                             echo $this->Form->input('Compra.' . $i . '.alicuota', array(
                                 'defaultoption' => $alicuota['alicuotaiva'],
                                 'label' => ($i + 9) % 10 == 0 ? 'Alicuota' : '',
                                 'style' => 'width:55px;',
                             ));
                             echo $this->Form->input('Compra.' . $i . '.neto', array(
                                 'style' => 'max-width: 60px;',
                                 'label' => ($i + 9) % 10 == 0 ? 'Neto' : '',
                                 'value' => $alicuota['importenetogravado'] * 1,
                             ));
                             echo $this->Form->input('Compra.' . $i . '.iva', array(
                                 'style' => 'max-width: 60px;',
                                 'label' => ($i + 9) % 10 == 0 ? 'IVA' : '',
                                 'value' => $alicuota['impuestoliquidado'] * 1,
                             ));
                             echo $this->Form->input('Compra.' . $i . '.ivapercep', array(
                                 'label' => ($i + 9) % 10 == 0 ? 'IVA Perc.' : '',
                                 'value' => $compra['Compra']['importepercepcionespagosacuentaiva'] * 1,
                                 'style' => 'max-width: 60px;',
                             ));
                             echo $this->Form->input('Compra.' . $i . '.iibbpercep', array(
                                 'style' => 'max-width: 60px;',
                                 'label' => ($i + 9) % 10 == 0 ? 'IIBB Perc.' : '',
                                 'value' => $compra['Compra']['importeingresosbrutos'] * 1,
                             ));
                             echo $this->Form->input('Compra.' . $i . '.actvspercep', array(
                                 'style' => 'max-width: 60px;',
                                 'label' => ($i + 9) % 10 == 0 ? 'Ac.Vs.Perc.' : '',
                                 'value' => $compra['Compra']['importeimpuestosmunicipales'] * 1,
                             ));
                             echo $this->Form->input('Compra.' . $i . '.impinternos', array(
                                 'label' => ($i + 9) % 10 == 0 ? 'Imp.Inter.' : '',
                                 'style' => 'max-width: 60px;',
                                 'value' => $compra['Compra']['importeimpuestosinternos'] * 1,
                             ));
                             echo $this->Form->input('Compra.' . $i . '.impcombustible', array(
                                 'style' => 'max-width: 60px;',
                                 'label' => ($i + 9) % 10 == 0 ? 'Imp.Comb.' : '',
                                 'value' => 0 * 1,
                             ));
                             echo $this->Form->input('Compra.' . $i . '.nogravados', array(
                                 'style' => 'max-width: 60px;',
                                 'label' => ($i + 9) % 10 == 0 ? 'No Gravado' : '',
                                 'value' => $compra['Compra']['importeconceptosprecionetogravado'] * 1,
                             ));
                             echo $this->Form->input('Compra.' . $i . '.exentos', array(
                                 'style' => 'max-width: 60px;',
                                 'label' => ($i + 9) % 10 == 0 ? 'Exento IVA' : '',
                                 'value' => $compra['Compra']['importeoperacionesexentas'] * 1,
                             ));
                             echo $this->Form->input('Compra.' . $i . '.periodo', array('type' => 'hidden', 'value' => $periodo));
                             echo $this->Form->input('Compra.' . $i . '.total', array(
                                 'label' => ($i + 9) % 10 == 0 ? 'Total.' : '',
                                 'style' => 'max-width: 60px;',
                                 'value' => $compra['Compra']['importetotaloperacion'] * 1,
                             ));
                             $i++; ?>
                         </div>
                         <?php
                         ?>
                     </td>
                 </tr>
                 <?php
             }
             if($i==100){
                 if(count($ventasArray)>100){
                     echo $cantComprasYaguardadas." Compras ya cargadas, faltan importar estas 100 compras y ".(count($comprasArray)-$i-$cantComprasYaguardadas)." compras mas, por favor continue cargando hasta que
                            finalize la carga de todas las compras del archivo";
                 }else{
                     echo $cantComprasYaguardadas." Compras ya cargadas, faltan importar estas ".$i." compras por favor continue cargando hasta que
                            finalize la carga de todas las compras del archivo";
                 }
                 break ;
             }
         }
     }
     ?>
    </table>
    <?php
        if($i > 1){
            echo $this->Form->submit('Importar', array(
                    'type'=>'image',
                    'title'=>'Importar',
                    'src' => $this->webroot.'img/check.png',
                    'class'=>'imgedit',
                    'style'=>'width:25px;height:25px;')
            );
        }
        echo $this->Form->end();
    ?>
</div>
<?php
}
?>