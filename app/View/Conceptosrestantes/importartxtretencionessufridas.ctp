<?php
echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false));
echo $this->Html->script('conceptosrestantes/importartxtretencionessufridas',array('inline'=>false)); ?>
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
        Cargar Archivo de Recaudaciones Bancarias:</br>
        <?php
        echo $this->Form->file('Conceptosrestante.archivoretencionesbancarias');
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
        Recaudaciones Bancarias:</br>
        <?php
        $conceptosrestantesArray = array();
        /*************************************************************************************************************/
        //Aca vamos a leer los TXT y ponerlos en el array de ventas
        $filesConceptosrestantes = $dirConceptosrestantes->find('.*\.txt');
        $i=0;
        $errorInFileVenta=false;
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
                $lineconceptosrestante = array();
                $lineconceptosrestante['fecha']=substr($line, 0,8);
                $lineconceptosrestante['constancia']=substr($line, 8,20);
                $lineconceptosrestante['nombre']=substr($line, 28,60);
                $lineconceptosrestante['cuit']=substr($line, 88,11);
                $lineconceptosrestante['importe']=substr($line, 99,16);
                $conceptosrestantesArray[$i]['Conceptosrestante']=$lineconceptosrestante;
                $i++;
                $j++;
                // $line="";
                unset($lineconceptosrestante);
            }
            $tituloButton= $errorInFileVenta?$dirConceptosrestante->name." Archivo con Error": $dirConceptosrestante->name;
            echo $this->Form->button(
                $tituloButton .'</br>
            <label>Retenciones Sufridas: '.$j.'</label>',
                array(
                    'class'=>'buttonImpcli4',
                    'onClick'=>"deletefile('".$dirConceptosrestante->name."','".$cliid."','retencionesbancarias','".$periodo."')",
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
    ?>
    </div>
    <div class="index">
        <?php
        //formulario donde se van a llenar todos los datos importados, que luego seran enviador por ajax a traves del formulario anterior
        echo $this->Form->create('Conceptosrestante',array(
                'controller'=>'Conceptosrestante',
                'action'=>'cargarconceptosrestantes',
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
        $tiposcuenta=['Caja de Ahorro'=>'CA','Cuenta Corriente'=>'CC','Otro'=>'OT'];
        $tiposmoneda=['Moneda Ext.'=>'E','Peso Arg.'=>'P','Otro'=>'O'];
        ?>
        Retenciones ya cargadas :
        <table style="width: 100%;padding: 0px;margin: 0px;" id="tablaFormVentas" >
            <?php
            $i=1;
            $cantConceptosrestanteYaguardadas = 0;
            $misConceptosrestantesYaCargadas = "";
            foreach ($conceptosrestantesArray as $keyConceptosrestante => $conceptosrestante) {
                //hay que controlar que las venas anteriores cargadas no contengan la venta que estamos por mostrar
                $conceptosrestanteCargadaPreviamente = false;
                $comprobanteConstancia= $conceptosrestante['Conceptosrestante']['constancia'];
                foreach ($conceptosrestantesperiodo as $conceptosrestanteYaCargada) {
                    $igualConstancia=false;
                    if($comprobanteConstancia==$conceptosrestanteYaCargada['Conceptosrestante']['numerocomprobante']){
                        $igualConstancia = true;
                    }
                    if ($igualConstancia){
                        $misConceptosrestantesYaCargadas +=
                            $conceptosrestante['Conceptosrestante']['constancia']."-".
                            $conceptosrestante['Conceptosrestante']['importe']." // ";
                        $conceptosrestanteCargadaPreviamente = true;
                        $cantConceptosrestanteYaguardadas++;
                        unset($conceptosrestantesArray[$keyConceptosrestante]);
                        break;
                    }
                }
                if(!$conceptosrestanteCargadaPreviamente) {
                    ?>
                    <tr>
                        <td style="width: 100%;padding: 0px;margin: 0px; ">
                            <div style="margin-top: 1px;background-color: white;" class="ventaFormVertical">
                                <?php
                                echo $this->Form->input('Conceptosrestante.' . $i . '.i', array(
                                        'label' => ($i + 9) % 10 == 0 ? 'N°' : '',
                                        'value' => $i,
                                        'style' => "width: 20px;",
                                    )
                                );
                                echo $this->Form->input('Conceptosrestante.' . $i . '.id', array('type' => 'hidden'));
                                echo $this->Form->input('Conceptosrestante.' . $i . '.cliente_id', [
                                    'type' => 'hidden', 'value' => $cliid]
                                );
                                echo $this->Form->input('Conceptosrestante.' . $i . '.impcli_id', [
                                    'type' => 'hidden', 'value' => $impcliid]
                                );
                                echo $this->Form->input('Conceptosrestante.' . $i . '.conceptostipo_id', [
                                    'type' => 'hidden', 'value' => 2]
                                );
                                echo $this->Form->input('Conceptosrestante.' . $i . '.periodo', [
                                    'type' => 'hidden', 'value' => $periodo]
                                );

                                //Este Array de comprobantes debe incluir array{id,codigo} y se debe seleccionar por codigo
                                echo $this->Form->input('Conceptosrestante.' . $i . '.partido_id', array(
                                    'style' => "width: 150px;",
                                    'label' => ($i + 9) % 10 == 0 ? 'Partido' : '',
                                ));
                                //seleccionar el punto de venta por "numero(nombre)"
                                echo $this->Form->input('Conceptosrestante.' . $i . '.cuit', array(
                                    'label' => ($i + 9) % 10 == 0 ? 'CUIT' : '',
                                    'style' => 'width:135px;',
                                    'value' =>  str_replace("-", "", $conceptosrestante['Conceptosrestante']['cuit']),
                                ));
                                echo $this->Form->input('Conceptosrestante.' . $i . '.numerocomprobante', array(
                                    'value' => $conceptosrestante['Conceptosrestante']['constancia'],
                                    'label' => ($i + 9) % 10 == 0 ? 'Num. Comprobante' : '',
                                    'style' => 'width:180px;',
                                ));
                                $conceptosrestante['Conceptosrestante']['fecha'] = substr_replace($conceptosrestante['Conceptosrestante']['fecha'], '-', 2, 0);
                                $conceptosrestante['Conceptosrestante']['fecha'] = substr_replace($conceptosrestante['Conceptosrestante']['fecha'], '-', 5, 0);
                                $fecha = date('d-m-Y', strtotime($conceptosrestante['Conceptosrestante']['fecha']));
                                echo $this->Form->input('Conceptosrestante.' . $i . '.fecha', array(
                                    'type' => 'text',
                                    'class' => 'date-picker',
                                    'style' => 'width: 80px;',
                                    'label' => ($i + 9) % 10 == 0 ? 'Fecha' : '',
                                    'value' => $fecha,
                                ));
                                echo $this->Form->input('Conceptosrestante.' . $i . '.montoretenido', array(
                                    'style' => 'max-width: 100px;',
                                    'label' => ($i + 9) % 10 == 0 ? 'Monto' : '',
                                    'value' => (float)str_replace(",", ".", $conceptosrestante['Conceptosrestante']['importe']),
                                ));
                                $i++;
                                ?>
                            </div>
                        </td>
                    </tr>
                    <?php
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
                    'class'=>'img_edit',
                    'style'=>'width:25px;height:25px;'
                )
            );
        }
        echo $this->Form->end();
        echo $misConceptosrestantesYaCargadas; ?>
    </div>
