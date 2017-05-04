<?php
echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false));
echo $this->Html->script('conceptosrestantes/importarretencionesbancariasconveniomultilateral',array('inline'=>false)); ?>
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
                'controller' => 'conceptosrestantes',
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
        echo $this->Form->create('Conceptosrestante', array('enctype' => 'multipart/form-data'));
        ?>
        Nuevos Archivos:</br>
        Cargar Archivo de Facturas:</br>
        <?php
        echo $this->Form->file('Conceptosrestante.archivoretenciones');
        echo $this->Form->input('Conceptosrestante.cliid',array('type'=>'hidden','value'=>$cliid));
        echo $this->Form->input('Conceptosrestante.periodo',array('type'=>'hidden','value'=>$periodo));?>
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
        $dirConceptosrestantes = new Folder($folderConceptosrestantes, true, 0777);
        ?>
        Archivos Cargados previamente</br>
        Retenciones:</br>
        <?php
        $conceptosrestantesArray = array();
        /*************************************************************************************************************/
        //Aca vamos a leer los TXT y ponerlos en el array de ventas
        $filesConceptosrestantes = $dirConceptosrestantes->find('.*\.txt');
        $i=0;
        $errorInFileConceptosrestante=false;
        $mostrarTabla = false;
        foreach ($filesConceptosrestantes as $dirConceptosrestante) {
            if(is_readable($dirConceptosrestantes->pwd() . DS . $dirConceptosrestante)){
                $mostrarTabla = true;
            }else{
                echo "No se puede acceder al archivo:".$dirConceptosrestante."</br>";
                break;
            }
            $dirConceptosrestante = new File($dirConceptosrestantes->pwd() . DS . $dirConceptosrestante);
            $dirConceptosrestante->open();
            $contents = $dirConceptosrestante->read();
            // $file->delete(); // I am deleting this file
            $handler = $dirConceptosrestante->handle;
            $j=0;
            while (($line = fgets($handler)) !== false) {
                $line = utf8_decode($line);
                if(strlen($line)!=268){
                    //todo Mejorar la deteccion de errores
                    //$errorInFileVenta=true;
                    //echo strlen($line)."line lenght";
                    //break;
                }
                $conceptosrestantesArray[$i] = array();
                $conceptosrestantesArray[$i]['Conceptosrestante'] = array();
                // process the line read.
                $lineConceptosrestante = array();
                $lineConceptosrestante['jurisdiccion']=substr($line, 0,3);
                $lineConceptosrestante['identificacionnumero']=substr($line, 3,13);
                $lineConceptosrestante['original']=substr($line, 17,10);
                $lineConceptosrestante['fecha']=date('d-m-Y',strtotime(substr($line, 17,10)));
                $lineConceptosrestante['puntodeventa']=substr($line, 27,4);
                $lineConceptosrestante['numerocomprobante']=substr($line, 31,15);
                $lineConceptosrestante['tipocomprobante']=substr($line, 46,3);
                $lineConceptosrestante['numerocomprobanteoriginal']=substr($line, 49,20);
                $lineConceptosrestante['importeretenido']=substr($line, 69,10);


                $conceptosrestantesArray[$i]['Conceptosrestante']=$lineConceptosrestante;
                $i++;
                $j++;
                // $line="";
                unset($lineConceptosrestante);
            }
            $tituloButton= $errorInFileConceptosrestante?$dirConceptosrestante->name." Archivo con Error": $dirConceptosrestante->name;
            echo $this->Form->button(
                $tituloButton .'</br>
            <label>Retenciones: '.$j.'</label>',
                array(
                    'class'=>'buttonImpcli4',
                    'onClick'=>"deletefile('".$dirConceptosrestante->name."','".$cliid."','retenciones','".$periodo."')",
                    'id'=>'',
                ),
                array()
            );
            fclose ( $handler );
            $dirConceptosrestante->close(); // Be sure to close the file when you're done
            if(!is_resource($handler)){
                //echo "handler cerrado con exito";
            }else{
                //echo "handler cerrado ABIERTO!";
            }
        }

        /*************************************************************************************************************/
        ?>
    </div>
<?php
$ConceptosrestantesConFechasIncorrectas = array();
echo json_encode($conceptosrestantesArray);
foreach ($conceptosrestantesArray as $conceptosrestante) {
//
//    $periodoVenta = date('m-Y',strtotime($venta['Venta']['fecha']));
//    //vamos a controlar que las fechas de las ventas sean las correctas (esten en este periodo)
//    if($periodo!=$periodoVenta){
//        //esta venta no es del periodo
//        $VentasConFechasIncorrectas[]=$venta['Venta']['fecha'];
//    }

}
if(($ConceptosrestantesConFechasIncorrectas)!=0||!$mostrarTabla){
    //todo: volver a copiar lo que habia aca!!!!
} else {
    ?>
    <div class="index">
        <?php
        //formulario oculto que va a contener en json todos los datos del formulario que esta debajo(lo hacemos asi para automatizar el envio)
        echo $this->Form->create('Conceptosrestante',array(
                'controller'=>'Conceptosrestantes',
                'action'=>'cargarconceptosrestante',
                'id'=>'ConceptosrestanteImportarAEnviar',
                'class'=>'formTareaCarga',
                'inputDefaults' => array(
                    'div' => true,
                    'label' => false,
                ),
            )
        );
        echo $this->Form->input('Conceptosrestante.0.jsonencript',array(
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
        //formulario donde se van a llenar todos los datos importados, que luego seran enviador por ajax a traves del formulario anterior
        echo $this->Form->create('Conceptosrestante',array(
                'controller'=>'Conceptosrestantes',
                'action'=>'cargarconceptosrestante',
                'id'=>'ConceptosrestanteImportar',
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
        Retenciones ya cargadas :
        <table style="width: 100%;padding: 0px;margin: 0px;" id="tablaFormRetenciones" >
            <?php
            $i=1;
            $cantRetencionesYaguardadas = 0;
            $misRetencionesYaCargadas = "";
            foreach ($conceptosrestantesArray as $keyRetencion => $retencion) {
                //hay que controlar que las venas anteriores cargadas no contengan la venta que estamos por mostrar
                $retencionCargadaPreviamente = false;

                $partidoNuevo = ltrim(customSearch($retencion['Conceptosrestante']['jurisdiccion'],$partidos), '0');
                $numeroComprobanteNuevo = $retencion['Conceptosrestante']['identificacionnumero'];
                $puntodeventaNuevo = $retencion['Conceptosrestante']['puntodeventa'];
                $comprobanteTipoNuevo = ltrim(customSearch($retencion['Conceptosrestante']['tipocomprobante'],$comprobantes), '0');

                foreach ($conceptosrestantesperiodo as $conceptosYaCargado) {
                    $igualTipoComprobante=false;
                    $igualPuntoDV=false;
                    $igualPartido=false;
                    $igualNumeroComprobante=false;
                    if($comprobanteTipoNuevo==$conceptosYaCargado['Conceptosrestante']['comprobante_id']){
                        $igualTipoComprobante = true;
                    }
                    if($pdvNuevo*1==$conceptosYaCargado['Conceptosrestante']['Puntosdeventa']['nombre']*1){
                        $igualPuntoDV = true;
                    }
                    if($partidoNuevo==$conceptosYaCargado['Conceptosrestante']['partido_id']){
                        $igualPartido = true;
                    }
                    if($numeroComprobante*1==$ventaYaCargada['Venta']['numerocomprobante']*1){
                        $igualNumeroComprobante = true;
                    }
                    if ($igualTipoComprobante&&$igualPuntoDV&&$igualPartido&&$igualNumeroComprobante){
                        $misRetencionesYaCargadas =
                            $retencion['Conceptosrestante']['tipocomprobante']."-".
                            $retencion['Conceptosrestante']['puntodeventa']."-".
                            $retencion['Conceptosrestante']['identificacionnumero']."-".
                            " // ";
                        $retencionCargadaPreviamente = true;
                        $cantRetencionesYaguardadas++;
                        break;
                    }
                }
                if(!$cantRetencionesYaguardadas) {
                    ?>
                    <tr>
                        <td style="width: 100%;padding: 0px;margin: 0px; ">
                            <div style="margin-top: 1px;background-color: white;" class="ventaFormVertical">
                                <?php
                                echo $this->Form->input('Conceptosrestante.'.$i.'.i', array(
                                        'label' => ($i + 9) % 10 == 0 ? 'N°' : '',
                                        'value' => $i,
                                        'style' => "width: 20px;",
                                    )
                                );
                                echo $this->Form->input('Conceptosrestante.'.$i.'.id', array('type' => 'hidden'));
                                echo $this->Form->input('Conceptosrestante.'.$i.'.cliente_id', array('type' => 'hidden', 'value' => $cliid));
                                echo $this->Form->input('Conceptosrestante.'.$i.'.fecha', array(
                                        'class' => 'datepicker',
                                        'type' => 'text',
                                        'label' => ($i + 9) % 10 == 0 ? 'Fecha' : '',
                                        'readonly' => 'readonly',
                                        'default' => date('d-m-Y', strtotime( $retencion['Conceptosrestante']['fecha'])),
                                        'style' => "width:75px"
                                    )
                                );
                                //Este Array de comprobantes debe incluir array{id,codigo} y se debe seleccionar por codigo
                                echo $this->Form->input('Conceptosrestante.' . $i . '.comprobante_id', array(
                                    'default' => $comprobanteTipoNuevo,
                                    'style' => "width: 50px;",
                                    'label' => ($i + 9) % 10 == 0 ? 'Comp.' : '',
                                    'class' => 'filtrocomprobante'
                                ));
                                //seleccionar el punto de venta por "numero(nombre)"
                                echo $this->Form->input('Conceptosrestante.' . $i . '.puntosdeventa', array(
                                    'value' => $pdvNuevo,
                                    'label' => ($i + 9) % 10 == 0 ? 'P.Vent.' : '',
                                    'style' => 'width:65px;',
                                    'class' => 'filtropuntodeventa',
                                ));
                                echo $this->Form->input('Venta.' . $i . '.numerocomprobante', array(

                                    //el numero de comprobante deberia tener 20 digitos pero tiene 8 entonces vamos a tomar los ultimos 8
                                    'value' => $numeroComprobanteNuevo,
                                    'label' => ($i + 9) % 10 == 0 ? 'Num.' : '',
                                    'style' => 'width:60px;',
                                ));

                                //esto no trae asi que vamos a tener que elegir
                                echo $this->Form->input('Venta.' . $i . '.partido_id', array(
                                        'class' => "chosen-select",
                                        'label' => ($i + 9) % 10 == 0 ? 'Partido' : '',
                                        'style' => 'width:150px',
                                        'defaultoptionlocalidade' => $partidoNuevo,
                                    )
                                );
                                $importenetogravado = $retencion['Conceptosrestante']['importeretenido'] * 1;
                                echo $this->Form->input('Conceptosrestante.' . $i . '.montoretenido', array(
                                    'style' => 'max-width: 100px;',
                                    'label' => ($i + 9) % 10 == 0 ? 'Monto retenido' : '',
                                    'value' => $importenetogravado,
                                ));
                                $i++;
                                ?>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                if($i==100){
                    if(count($conceptosrestantesArray)>100){
                        echo $cantRetencionesYaguardadas." Ventas ya cargadas, faltan importar estas 100 ventas y ".(count($ventasArray)-$i-$cantVentasYaguardadas)." ventas mas, por favor continue cargando hasta que
                            finalize la carga de todas las ventas del archivo";
                    }else{
                        echo $cantRetencionesYaguardadas." Ventas ya cargadas, faltan importar estas ".$i." ventas por favor continue cargando hasta que
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
        echo $misRetencionesYaCargadas; ?>
    </div>
    <?php
}
//Debugger::dump($ventasArray);
?>