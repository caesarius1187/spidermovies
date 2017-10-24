<?php
echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false));
echo $this->Html->script('movimientosbancarios/importar',array('inline'=>false));?>
<div class="index" style="width: inherit;float: left;height: 250px;">

    <?php
    $labelClifch = $cliente['Cliente']['nombre'];
    ?>
    <div style="position: relative;height: 100%;">
        <h1><?php echo __($labelClifch); ?></h1>
        <label><?php echo $periodo; ?></label>
        <?php  echo $this->Html->link("<- Volver",array(
                'controller' => 'movimientosbancarios',
                'action' => 'cargar',
                $cliid,
                $periodo,
            ),
            array(
                'class'=>"btn_aceptar",
                'style'=>'position: absolute;bottom: 0px;'
            )
        ); 	
        $abreviacionCBUTipo = "";
        switch ($cbu['Cbu']['tipocuenta']) {
            case 'Caja de Ahorro en Euros':
                $abreviacionCBUTipo = "CA €";
            break;
            case 'Caja de Ahorro en Moneda Local':
                $abreviacionCBUTipo = "CA $";
            break;
            case 'Caja de Ahorro en U$S':
                $abreviacionCBUTipo = "CA U$ S";
            break;
            case 'Cuenta Corriente en Euros':
                $abreviacionCBUTipo = "CC €";
            break;
            case 'Cuenta Corriente en Moneda Local':
                $abreviacionCBUTipo = "CC $";
            break;
            case 'Cuenta Corriente en U$S':
                $abreviacionCBUTipo = "CC U$ S";
            break;
            case 'Otras':
                $abreviacionCBUTipo = "Otras";
            break;
            case 'Plazo Fijo en Euros':
                $abreviacionCBUTipo = "PF €";
            break;
            case 'Plazo Fijo en U$S':
             $abreviacionCBUTipo = "PF U$ S";
            break;
            case 'Plazo Fijo en Moneda Local':
                $abreviacionCBUTipo = "PF $";
            break;
            default:
                $abreviacionCBUTipo = "cc $";
            break;
        }
        ?>
            <legend style="color:#1e88e5;font-weight:normal;">
                <?php echo $cbu['Impcli']['Impuesto']['nombre']." ".substr($cbu['Cbu']['numerocuenta'], -5)." ".
                    $abreviacionCBUTipo?></legend>
    </div>
</div>
<div class="index" style="width: inherit;float: left;margin-left: -10px;height: 250px;">
<?php 
echo $this->Form->create('Movimientosbancario', array('enctype' => 'multipart/form-data'));
?>
Nuevos Archivos:</br>
Cargar Archivo de Resumen bancario:</br>
<?php
echo $this->Form->file('Movimientosbancario.archivoresumen');
echo $this->Form->input('Movimientosbancario.cliid',array('type'=>'hidden','value'=>$cliid));
echo $this->Form->input('Movimientosbancario.periodo',array('type'=>'hidden','value'=>$periodo));?>
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
<div class="index" style="width: inherit;float: left;margin-left: -10px;height: 250px;overflow: auto;" >
	<?php
    $dirMovimientosbancarios = new Folder($folderMovimientosbancarios, true, 0777);
	?>
	Archivos Cargador previamente</br>
	Resumen:</br>
	<?php
    $movbancariosArray = array();

	$filesMovimientosbancarios = $dirMovimientosbancarios->find('.*\.csv');
    $i=0;
    $ordenImportacion=0;
    $errorInFileMovimientosbancarios=false;
    $mostrarTabla=false;
    $movimientosbancariosArray = [];
    $conceptos = [];
    $moneyChars = ['.','$'];
    $movimientosRepetidos = "";
    foreach ($filesMovimientosbancarios as $dirMovimientosbancario) {
        if(is_readable($dirMovimientosbancarios->pwd() . DS . $dirMovimientosbancario)){
            $mostrarTabla=true;
        }else{
            echo "No se puede acceder al archivo:".$dirMovimientosbancario."</br>";
            break;
        }
        $dirMovimientosbancario = new File($dirMovimientosbancarios->pwd() . DS . $dirMovimientosbancario);
        $dirMovimientosbancario->open();
        $contents = $dirMovimientosbancario->read();
        // $file->delete(); // I am deleting this file
        $handler = $dirMovimientosbancario->handle;
        $j=0;
        while (($line = fgetcsv($handler, 1000, ";")) !== false) {


            // process the line read.
            $linemovimiento = array();
            $linemovimiento['fecha']= str_replace("/", "-", $line[0]);
            $linemovimiento['concepto']=utf8_decode($line[1]);

            $debe = $line[2]?0:$line[2];
            $debe = str_replace($moneyChars, "", $line[2]);
            $debe = str_replace(",", ".", $debe) + 0;
            $haber = str_replace($moneyChars, "", $line[3]);
            $haber = str_replace(",", ".", $haber) + 0;
           // $saldo = $line[4];
            $saldo = str_replace($moneyChars, "", $line[4]);
            $saldo = str_replace(",", ".", $saldo) + 0;
            $linemovimiento['debe']=$debe;
            $linemovimiento['haber']=$haber;
            $linemovimiento['saldo']=$saldo;
            $linemovimiento['ordencarga']=$ordenImportacion;
            $ordenImportacion++;
            //Primero vamos a ver si este movimiento no fue cargado anteriormente
            //para ello vamos a recorrer los ya cargados y fijarnos si coincide fecha concepto debito credito y saldo
            $mismaFecha = false;
            $mismoDebito = false;
            $mismoCredito = false;
            $mismoSaldo = false;
            $mismoConcepto = false;
            $cargarMovimientoEnForm = true;
            foreach ($movimientosbancariosperiodo as $movimientosyacargados) {
                //vamos a transformar las dos fechas para que no haya confucion
                $fechacargada = date('d-m-Y',strtotime($movimientosyacargados['Movimientosbancario']['fecha']));
                $fechanueva = date('d-m-Y',strtotime($linemovimiento['fecha']));
                if($fechacargada==$fechanueva){
                    $mismaFecha = true;
                }
                if($movimientosyacargados['Movimientosbancario']['debito']==$linemovimiento['debe']){
                    $mismoDebito = true;
                }
                if($movimientosyacargados['Movimientosbancario']['credito']==$linemovimiento['haber']){
                    $mismoCredito = true;
                }
                if($movimientosyacargados['Movimientosbancario']['saldo']==$linemovimiento['saldo']){
                    $mismoSaldo = true;
                }
                if($movimientosyacargados['Movimientosbancario']['concepto']==$linemovimiento['concepto']){
                    $mismoConcepto = true;
                }
//                if(($mismaFecha*1+$mismoDebito*1+$mismoCredito*1+$mismoSaldo*1+$mismoConcepto*1)>2){
//                    echo ($mismaFecha*1)." ".($mismoDebito*1)." ".($mismoCredito*1)." ".($mismoSaldo*1)." ".($mismoConcepto*1)."</br>";
//                    echo "2 o mas : Ya Cargado ".date('d-m-Y',strtotime($movimientosyacargados['Movimientosbancario']['fecha']))."-".
//                        $movimientosyacargados['Movimientosbancario']['concepto']."-".
//                        $movimientosyacargados['Movimientosbancario']['debito']."-".
//                        $movimientosyacargados['Movimientosbancario']['credito']."-".
//                        $movimientosyacargados['Movimientosbancario']['saldo']."</br> ".
//                        " Nuevo Movimiento ".date('d-m-Y',strtotime($linemovimiento['fecha']))."-".
//                        $linemovimiento['concepto']."-".
//                        $linemovimiento['debe']."-".
//                        $linemovimiento['haber']."-".
//                        $linemovimiento['saldo']."</br> "
//                    ;
//                }
                if( $mismaFecha&&$mismoDebito&&$mismoCredito&&$mismoSaldo&&$mismoConcepto){
                    $movimientosRepetidos .= date('d-m-Y',strtotime($movimientosyacargados['Movimientosbancario']['fecha']))."-".
                        $movimientosyacargados['Movimientosbancario']['concepto']."-".
                        $movimientosyacargados['Movimientosbancario']['debito']."-".
                        $movimientosyacargados['Movimientosbancario']['credito']."-".
                        $movimientosyacargados['Movimientosbancario']['saldo']."</br> ";
                    $cargarMovimientoEnForm = false;
                    break;
                }
                $j++;
            }
            if($cargarMovimientoEnForm){
                if(!in_array(utf8_decode($line[1]), $conceptos)){
                    array_push($conceptos,utf8_decode($line[1]));
                }
                $movimientosbancariosArray[$i] = array();
                $movimientosbancariosArray[$i]['Movimientosbancario'] = array();
                $movimientosbancariosArray[$i]['Movimientosbancario']=$linemovimiento;
                $i++;
                if($i==3500){
//                        die($i."hasta aca llegue3".$line);
                }
            }
        }

        $tituloButton= $dirMovimientosbancario->name;
//        $tituloButton= $errorInFileCompra?$dirCompra->name." Archivo con Error": $dirCompra->name;
            echo $this->Form->button(
                $tituloButton .'</br>
            <label>Movimientos Bancarios: '.$j.'</label>',
            array(
                'class'=>'buttonImpcli4',
                'onClick'=>"deletefile('".$dirMovimientosbancario->name."','".$cliid."','movimientosbancarios','".$periodo."','".$impcliid."','".$cbuid."')",
                'style'=>'white-space: nowrap;overflow: hidden;text-overflow: ellipsis;',
                'id'=>'',
            ),
            array()
        );
        fclose ( $handler );
        $dirMovimientosbancario->close(); // Be sure to close the file when you're done
        if(!is_resource($handler)){
            //echo "handler cerrado con exito";
        }else{
            //echo "handler cerrado ABIERTO!";
        }
    }
    unset($dirMovimientosbancario); ?>
</div>
<div class="index" style="overflow: auto;">
    <?php
    //formulario oculto que va a contener en json todos los datos del formulario que esta debajo(lo hacemos asi para automatizar el envio)
    echo $this->Form->create('Movimientosbancario',array(
            'controller'=>'Movimientosbancario',
            'action'=>'cargarmovimientosbancarios',
            'id'=>'MovimientosbancarioImportarAEnviar',
            'class'=>'formTareaCarga',
            'inputDefaults' => array(
                'div' => true,
                'label' => false,
            ),
        )
    );
    echo $this->Form->input('Movimientosbancario.0.jsonencript',array(
            'value'=>'',
            'type'=>'hidden',
        )
    );
    echo $this->Form->submit('+', array(
            'div'=> array('style'=>'display:none')
        )
    );
    echo $this->Form->end();
    //formulario donde se van a llenar todos los datos importados, que luego seran enviador por ajax a traves del formulario anterior
    echo $this->Form->create('Movimientosbancario',array(
            'controller'=>'Movimientosbancario',
            'action'=>'cargarmovimientosbancarios',
            'id'=>'MovimientosbancarioImportar',
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
        unset($arrayItem);
        return 0;
    }
    ?>
    Info: Este formulario de importación permite cargar los movimientos bancarios de una cuenta a partir de un csv que cuente con las
    siguientes columnas (sin cabecera) </br>1: Fecha</br>2: Concepto</br>3: Debito</br>4: Credito</br>5: Saldo</br>
    Tambien te permite asignarle un codigo de AFIP a los movimientos y una cuenta contable a la que se imputara el mismo.
    </br>Solo se cargarán los registros que tengan asignadas una cuenta contable.
    </br>Si un registro ya se ha guardado no se cargará en el formulario.
    Deberá ser editado desde la seccion de carga. Si es necesario.
    </br>Movimientos Bancarios ya cargados :
    <?php
    $i=1;
    foreach ($movimientosbancariosperiodo as $movimientosyacargados) {
        echo date('d-m-Y',strtotime($movimientosyacargados['Movimientosbancario']['fecha']))."-".
        $movimientosyacargados['Movimientosbancario']['concepto']."-".
        $movimientosyacargados['Movimientosbancario']['debito']."-".
        $movimientosyacargados['Movimientosbancario']['credito']."-".
        $movimientosyacargados['Movimientosbancario']['saldo']."</br> ";
    } ?>
    Movimientos Repetidos :
    <?php
    $i=1;
    echo $movimientosRepetidos;
    ?>
    <table id="filtros" style="width: 1000px;" >
        <tr>
            <td style="width:20px;"></td>
            <td style="width:75px;"></td>
            <td style="width:150px;">
                <?php
                echo $this->Form->input('Filtro.0.concepto', array(
                        'empty' => 'filtrar',
                        'style'=>"width: 150px;",
                        'type' => "select",
                        'options' => $conceptos,
                    )
                );?>
            </td>
            <td style="width:150px;">
                <?php
                echo $this->Form->input('Filtro.0.debito', array(
                        'empty' => 'filtrar',
                        'style'=>"width: 150px;",
                        'type' => "select",
                        'options' => ['0'=>'0'],
                    )
                );?>
            </td>
            <td style="width:150px;">
                <?php
                echo $this->Form->input('Filtro.0.credito', array(
                        'empty' => 'filtrar',
                        'style'=>"width: 150px;",
                        'type' => "select",
                        'options' => ['0'=>'0'],
                    )
                );?>
            </td>
            <td style="width:150px;"></td>
            <td style="width:75px;"></td>
            <td style="width:150px;"></td>
            <td style="width:160px;">
                <?php
                echo $this->Form->input('Filtro.0.cuentascliente_id', array(
                        'empty' => 'filtrar',
                        'style'=>"width: 160px;",
                    )
                );?>
            </td>
        </tr>
    </table>
    <table style="width: 2230px; padding: 0px;margin: 0px;" id="tablaFormMovimientosbancarios">
    <?php
    foreach ($movimientosbancariosArray as $movimintosbancario){
        if($movimintosbancario['Movimientosbancario']['concepto']==""){
            continue;
        }
        ?>
         <tr id="row<?php echo $i?>">
             <td><?php
                 echo $this->Form->button(
                        $this->Html->image("cruz.png",
                             array(
                                 "alt" => "eliminar",
                                 'style'=>'width:20px;height:20px',
                             )
                        )."",
                        array(
                            'class'=>"btnAgregar",
                            'escape'=>false,
                            'title'=>'eliminar',
                            'type'=>"button",
                            'style'=>'margin-top:15px; cursor: pointer;',
                            'onClick'=>"deleterow(".$i.")"
                        )
                 );
                 ?></td>
             <td style="padding: 0px;margin: 0px; " colspan="25">
                 <div style="margin-top: 1px;background-color: white;" class="compraFormVertical">
                     <?php
                     echo $this->Form->input('Movimientosbancario.' . $i . '.i', array(
                             'label' => ($i + 9) % 10 == 0 ? 'N�' : '',
                             'value' => $i,
                             'style' => "width: 20px;",
                         )
                     );
                     echo $this->Form->input('Movimientosbancario.' . $i . '.id', array('type' => 'hidden'));
                     echo $this->Form->input('Movimientosbancario.' . $i . '.periodo', array('type' => 'hidden','value' => $periodo));
                     echo $this->Form->input('Movimientosbancario.' . $i . '.impcli_id', array('type' => 'hidden', 'value' => $impcliid));
                     echo $this->Form->input('Movimientosbancario.' . $i . '.cbu_id', array('type' => 'hidden', 'value' => $cbuid));
                     echo $this->Form->input('Movimientosbancario.' . $i . '.ordencarga', array(
                             'label' => ($i + 9) % 10 == 0 ? 'Orden' : '     ',
                             'value' => $movimintosbancario['Movimientosbancario']['ordencarga']
                         )
                     );
                     echo $this->Form->input('Movimientosbancario.' . $i . '.fecha', array(
                             'class' => 'datepicker',
                             'type' => 'text',
                             'label' => ($i + 9) % 10 == 0 ? 'Fecha' : '     ',
                             'readonly' => 'readonly',
                             'fechaoriginal' => $movimintosbancario['Movimientosbancario']['fecha'],
                             'default' => date('d-m-Y', strtotime($movimintosbancario['Movimientosbancario']['fecha'])),
                             'style' => "width:80px"
                         )
                     );

                     echo $this->Form->input('Movimientosbancario.' . $i . '.concepto', array(
                         'value' => $movimintosbancario['Movimientosbancario']['concepto'],
                         'title' => $movimintosbancario['Movimientosbancario']['concepto'],
                         'style' => "width: 200px;",
                         'class' => "filtroconcepto",
                         'label' => ($i + 9) % 10 == 0 ? 'Concepto' : '       ',
                     ));
                     echo $this->Form->input('Movimientosbancario.' . $i . '.debito', array(
                         'value' => $movimintosbancario['Movimientosbancario']['debe'],
                         'style' => "width: 150px; text-align: right;",
                         'type' => "text",
                         'class' => "filtrodebito inputDebe",
                         'label' => ($i + 9) % 10 == 0 ? 'Debito' : '    ',
                         "align"=>"right"
                     ));
                     echo $this->Form->input('Movimientosbancario.' . $i . '.credito', array(
                         'value' => $movimintosbancario['Movimientosbancario']['haber'],
                         'type' => "text",
                         'style' => "width: 150px; text-align: right;",
                         'class' => "filtrocredito inputHaber",
                         'label' => ($i + 9) % 10 == 0 ? 'Credito' : '     ',
                         "align"=>"right"
                     ));
                     echo $this->Form->input('Movimientosbancario.' . $i . '.saldo', array(
                         'value' => $movimintosbancario['Movimientosbancario']['saldo'],
                         'style' => "width: 150px; text-align: right; ",
                         'type' => "text",
                         'label' => ($i + 9) % 10 == 0 ? 'Saldo' : '     ',
                         'class'=>'inputSaldo'
                     ));
                     //seleccionar el punto de venta por "numero(nombre)"
                     $codigosAFIP=[
                         '1'=>'1',
                         '2'=>'2',
                         '3'=>'3',
                         '4'=>'4',
                         '5'=>'5',
                         '6'=>'6',
                         '7'=>'7',
                         '8'=>'8',
                         '9'=>'9',
                         '10'=>'10',
                         '11'=>'11',
                         '12'=>'12',                         
                         '99'=>'99'];
                     echo $this->Form->input('Movimientosbancario.' . $i . '.codigoafip', array(
                         'type'=>'select',
                         'empty' => 'sin codigo',
                         'default' => ($movimintosbancario['Movimientosbancario']['haber']>0)?'1':'',
                         'options' => $codigosAFIP,
                         'label' => ($i + 9) % 10 == 0 ? 'Codigo AFIP.' : '',
                         'style' => 'width:65px;',
                         'class' => 'aplicableATodos',
                         'inputclass' => 'MovimientobancarioAddCodigoAFIP',
                     ));
                     echo $this->Form->input('Movimientosbancario.' . $i . '.cuentascliente_id', array(
                         'type'=>'select',
                         'empty' => 'elegir cuenta',
                         'label' => ($i + 9) % 10 == 0 ? 'Cuenta contable.' : '          ',
                         'orden' => $i,
                         'class' => "filtrocuentascontable aplicableATodos",
                         'style' => 'width:160px;',
                         'required' => false,
                         'message'=>'Por favor seleccione una cuenta contable para imputar este movimiento bancario',
                         'inputclass' => 'MovimientobancarioAddCuentasCliente',
                     ));
                     echo $this->Form->input('Movimientosbancario.' . $i . '.alicuota', array(
                         'style' => "width: 150px; text-align: right;",
                         'title'=>'Solo se debe completar este campo si se selecciono la cuenta IVA-Credito Fiscal',
                         'label' => ($i + 9) % 10 == 0 ? 'Alicuota' : '     ',
                         "align"=>"right"
                     ));
                     $i++;
                     ?>
                 </div>
             </td>
         </tr>
         <?php
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
    echo $this->Form->input('Movimientosbancario.1.cantmovimientos', array('value'=>$i,'type'=>'hidden'));
    ?>
</div>
