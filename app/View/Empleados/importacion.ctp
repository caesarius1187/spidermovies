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
echo $this->Html->script('empleados/importacion',array('inline'=>false));?>
<div class="index" style="">

    <?php
    $labelClifch = $cliente['Cliente']['nombre'];
    ?>
    <h1 style="float:right;"><?php echo __($labelClifch); ?></h1>
    <?php  echo $this->Html->link("<- Volver",array(
            'controller' => 'empleados',
            'action' => 'cargarempleados',
            $cliid,
        ),
        array(
            'class'=>"btn_aceptar",
            'style'=>'float: left;margin-top: 0px;'
        )
    ); 	?>
</div>
<div class="index" style="width: inherit;float: left;height: 171px;">
<?php 
echo $this->Form->create('Empleado', array('enctype' => 'multipart/form-data'));
?>
Nuevos Archivos:</br>
Cargar Archivo de Nomina de Empleados:</br>
<?php
echo $this->Form->file('Empleado.archivoempleado');
echo $this->Form->input('Empleado.cliid',array('type'=>'hidden','value'=>$cliid));
?>
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
<div class="index" style="width: inherit;float: left;margin-left: -10px;height: 171px;">
    <?php
    $dirEmpleados = new Folder($folderEmpleados, true, 0777);
    ?>
    Archivos Cargador previamente</br>
    Empleados:</br>
	<?php
    $empleadosArray = array();

    $filesEmpleados = $dirEmpleados->find('.*\.txt');
    $i=0;
    $errorInFileEmpleado=false;
    $mostrarTabla=false;    
    
    foreach ($filesEmpleados as $dirEmpleado) {
        if(is_readable($dirEmpleados->pwd() . DS . $dirEmpleado)){
            $mostrarTabla=true;
        }else{
            echo "No se puede acceder al archivo:".$dirEmpleado."</br>";
            break;
        }
        $dirEmpleado = new File($dirEmpleados->pwd() . DS . $dirEmpleado);
        $dirEmpleado->open();
        $contents = $dirEmpleado->read();
	    // $file->delete(); // I am deleting this file
        $handler = $dirEmpleado->handle;
        $j=0;
        while (($line = fgets($handler)) !== false) {
            if($i>100||$i==0){
                $i++;
                $j++;
                continue;
            }
            $empleadoyacargado = false;
            $line = utf8_decode($line);
            // process the line read.
            $lineEmpleado = array();
            $lineEmpleado['cuil']=rtrim(ltrim(substr($line, 0,11)));
            //vamos a buscar el cuit del empleado y si ya existe no lo vamos a cargar
            foreach ($empleados as $empleadocargado){
                if($empleadocargado['Empleado']['cuit']==$lineEmpleado['cuil']){
                    $empleadoyacargado = true;
                    break;
                }
            }
            $lineEmpleado['apellidoynombre']=rtrim(ltrim(substr($line, 12,60),' '),' ');
            $lineEmpleado['obrasocial']=rtrim(ltrim(substr($line, 72,160),' '),' ');
            $lineEmpleado['modalcontrato']=rtrim(ltrim(substr($line, 232,100),' '),' ');
            $lineEmpleado['actividadlaboral']=rtrim(ltrim(substr($line, 332,60),' '),' ');
            $lineEmpleado['puestodesemp']=rtrim(ltrim(substr($line, 392,255),' '),' ');
            $lineEmpleado['retribpactada']=rtrim(ltrim(substr($line, 647,24),' '),' ');
            $lineEmpleado['modliq']=rtrim(ltrim(substr($line, 671,30),' '),' ');
            $lineEmpleado['trabajoagrop']=rtrim(ltrim(substr($line, 701,13),' '),' ');
            $lineEmpleado['fechainicio']=rtrim(ltrim(substr($line, 714,13),' '),' ');
            $lineEmpleado['fechafin']=rtrim(ltrim(substr($line, 727,13),' '),' ');
            $lineEmpleado['situacionbaja']=rtrim(ltrim(substr($line, 740,70),' '),' ');
            $lineEmpleado['conveniocolectivo']=rtrim(ltrim(substr($line, 811,70),' '),' ');
            $lineEmpleado['categoria']=rtrim(ltrim(substr($line, 811,70),' '),' ');
            
            $lineEmpleado['fechainicio']= str_replace("/","-",$lineEmpleado['fechainicio']);
            $lineEmpleado['fechafin']= str_replace("/","-",$lineEmpleado['fechafin']);
            
            $i ++;
            $j ++;
            if(!$empleadoyacargado){
                $empleadosArray[]=$lineEmpleado;            
            }
        }    
        
        $tituloButton= $dirEmpleado->name;
//        $tituloButton= $errorInFileCompra?$dirCompra->name." Archivo con Error": $dirCompra->name;
            echo $this->Form->button(
                $tituloButton .'</br>
            <label>Empleados: '.$j.'</label>',
            array(
                'class'=>'buttonImpcli4',
                'onClick'=>"deletefile('".$dirEmpleado->name."','".$cliid."','empleados')",
                'style'=>'white-space: nowrap;overflow: hidden;text-overflow: ellipsis;',
                'id'=>'',
            ),
            array()
        );
        fclose ( $handler );
        $dirEmpleado->close(); // Be sure to close the file when you're done
        if(!is_resource($handler)){
            //echo "handler cerrado con exito";
        }else{
            //echo "handler cerrado ABIERTO!";
        }
    }
    ?>
</div>
<?php
if(!$mostrarTabla){ 
} else { 
    ?>
    <div class="index" style="/*overflow-x: visible;overflow-y: visible*/">
    <?php
    //formulario oculto que va a contener en json todos los datos del formulario que esta debajo(lo hacemos asi para automatizar el envio)
    echo $this->Form->create('Empleado',array(
            'controller'=>'Empleado',
            'action'=>'cargarempleados',
            'id'=>'EmpleadoImportarAEnviar',
            'class'=>'formTareaCarga',
            'inputDefaults' => array(
                'div' => true,
                'label' => false,
            ),
        )
    );
    echo $this->Form->input('Empleado.0.jsonencript',array(
            'label'=>($i+9)%10==0?'N°':'',
            'value'=>'',
            'type'=>'hidden',
        )
    );
    echo $this->Form->submit('+', array(
            'type'=>'image',
            'src' => $this->webroot.'img/check.png',
            'div'=> array('style'=>'display:none'))
    );
    echo $this->Form->end();
    //formulario donde se van a llenar todos los datos importados, que luego seran enviador por ajax a traves del formulario anterior


    echo $this->Form->create('Empleado',array(
            'controller'=>'Empleado',
            'action'=>'cargarempleados',
            'id'=>'EmpleadoImportar',
            'class'=>'formTareaCarga',
            'inputDefaults' => array(
                'div' => true,
                'label' => false,
            ),
        )
    );
    ?>
    <table style="padding: 0px;margin: 0px;border-collapse: collapse;" id="tblAddEmpleados">
     <?php
     $i=1;
     $compraNumero=1;

     foreach ($empleadosArray as $empleado) {
         //if(!isset($compra['Alicuota'])) continue;
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
            <td style="width: 100%;padding: 0px;margin: 0px;/* height: <?php echo $rowheight ?>px;*/ border: 2px solid #1e88e5;" colspan="25">
                <div style="margin-top: 0px;  /*  height:<?php echo $rowheight ?>px*/" class="compraFormVertical <?php /*echo $class;*/?>">
                    <?php
                      echo $this->Form->label($i,str_pad($i, 2, "0", STR_PAD_LEFT), 
                                   [
                                       "style"=>"display:inline"
                                   ]
                           );
                    echo $this->Form->input('Empleado.' . $i . '.id', array('type' => 'hidden'));
                    echo $this->Form->input('Empleado.' . $i . '.cliente_id', array('type' => 'hidden', 'value' => $cliid));
                    ?>
                    <fieldset style="/*border: 1px solid #1e88e5;*/">
                        <legend style="color:#1e88e5;font-weight:normal;">Datos Personales</legend>
                        <?php
                        echo $this->Form->input('Empleado.' . $i . '.id',array('type'=>'hidden'));
                        echo $this->Form->input('Empleado.' . $i . '.cliente_id',array('default'=>$cliente['Cliente']['id'],'type'=>'hidden'));
                        echo $this->Form->input('Empleado.' . $i . '.legajo',array('label'=>'Legajo','style'=>'width:30px'));
                        echo $this->Form->input('Empleado.' . $i . '.nombre',array(
                            'style'=>'width:150px',
                            'label'=>'Apellido y nombre',
                            'value'=>$empleado['apellidoynombre'],
                            ));
                        echo $this->Form->input('Empleado.' . $i . '.cuit',array(
                            'label'=>'CUIL',
                            'maxlength'=>'11',
                            'value'=>$empleado['cuil'],
                            'style'=>'width:95px',
                            ));
                        echo $this->Form->input('Empleado.' . $i . '.dni',array(
                            'label'=>'DNI',
                            'value'=> substr($empleado['cuil'], 2, 8),
                            ));
                        echo $this->Form->input('Empleado.' . $i . '.localidade_id',array(
                            'label'=>'Localidad',
                            'type'=>'select',
                            'class'=>'chosen-select',
                            'options'=>$localidades,
                            'style'=>'width:250px'
                            )
                        );
                        echo $this->Form->input('Empleado.' . $i . '.domicilio',array('label'=>'Domicilio','type'=>'text','style'=>'width:250px'));
                        echo $this->Form->input('Empleado.' . $i . '.hijos',array('label'=>'Hijos','value'=>0));
                        echo "</br>";
                        echo $this->Form->input('Empleado.' . $i . '.titulosecundario',array('label'=>'Titulo Secundario'));
                        echo $this->Form->input('Empleado.' . $i . '.titulouniversitario',array('label'=>'Titulo Universitario'));
                        echo $this->Form->input('Empleado.' . $i . '.conyugue',array('label'=>'Conyugue','value'=>0));
                        
                        ?>
                    </fieldset>
                    <fieldset style="/*border: 1px solid #1e88e5;*/">
                        <legend style="color:#1e88e5;font-weight:normal;">Laborales</legend>
                        <?php
                        echo $this->Form->input('Empleado.' . $i . '.fechaingreso', array(
                                'class'=>'datepicker',
                                'type'=>'text',
                                'label'=>'Ingreso',
                                'required'=>true,
                                'readonly'=>'readonly',
                                'value'=>date('d-m-Y', strtotime($empleado['fechainicio'])),
                                'initialvalue'=>$empleado['fechainicio']
                            )
                        );
                        echo $this->Form->input('Empleado.' . $i . '.fechaalta', array(
                                'class'=>'datepicker',
                                'type'=>'text',
                                'label'=>'Alta(AFIP)',
                                'required'=>true,
                                'readonly'=>'readonly',
                                'value'=>date('d-m-Y', strtotime($empleado['fechainicio'])),
                                'initialvalue'=>$empleado['fechainicio']
                            )
                        );
                        //Alta AFIP
                        
                        echo $this->Form->input('Empleado.' . $i . '.fechaegreso', array(
                                'class'=>'datepicker',
                                'type'=>'text',
                                'label'=>'Egreso',
                                'required'=>true,
                                'readonly'=>'readonly',
                                'value'=>($empleado['fechafin']!="")?date('d-m-Y', strtotime($empleado['fechafin'])):""
                            )
                        );

                        echo $this->Form->input('Empleado.' . $i . '.domicilio_id',array('label'=>'Domicilio de explotacion'));
                        echo $this->Form->input('Empleado.' . $i . '.impuesto_id',array(
                            'label'=>'Banco',
                            'title'=>'Elija el banco donde se va a pagarle al empleado',
                            'empty'=>'Efectivo',
                            'options'=>$bancos
                        ));
                        echo $this->Form->input('Empleado.' . $i . '.conveniocolectivotrabajo_id',array(
                            'label'=>'Convenio Colectivo de Trabajo',
                            'orden'=>$i,
                            'class'=>'conveniocolectivo'
                            ));
                        echo $this->Form->input('Empleado.' . $i . '.cargo_id',array(
                            'label'=>'Cargo', 
                            'required'=>true,
                            'orden'=>$i,
                            'class'=>'cargo'
                            ));
                        echo $this->Form->input('Empleado.' . $i . '.jornada',array('label'=>'Jornada','type'=>'select','options'=>array('0.5'=>"Media Jornada",'1'=>"Jornada Completa")));                        
                        echo $this->Form->label("Liquidaciones:");
                        echo $this->Form->input('Empleado.' . $i . '.liquidaprimeraquincena',array(
                            'label'=>'Primera Quincena',
                            'checked'=>($empleado['modliq']=='Hora')?'checked':FALSE,
                            ));
                        echo $this->Form->input('Empleado.' . $i . '.liquidasegundaquincena',array(
                            'label'=>'Segunda Quincena',
                            'checked'=>($empleado['modliq']=='Hora')?'checked':FALSE,
                            ));
                        echo $this->Form->input('Empleado.' . $i . '.liquidamensual',array(
                            'label'=>'Mensual',
                             'checked'=>($empleado['modliq']=='MES')?'checked':FALSE,
                            ));
                        echo $this->Form->input('Empleado.' . $i . '.liquidasac',array('label'=>'SAC(Solo si se liquida en recibo de sueldo separado)'));                       
                        echo "</br>";
                        echo $this->Form->input('Empleado.' . $i . '.afiliadosindicato',array('label'=>'Afiliado al sindicato'));
                        echo $this->Form->input('Empleado.' . $i . '.fallodecaja',array('label'=>'Paga Fallo de Caja'));
                        ?>
                    </fieldset>                   
                    <fieldset style="/*border: 1px solid #1e88e5;*/">
                        <legend style="color:#1e88e5;font-weight:normal;">Datos Impositivos</legend>
                        <?php
                        echo $this->Form->input('Empleado.' . $i . '.codigoafip',array(
                                'label'=>'Codigo Afip',
                                'options'=>array(
                                    '0'=>'0%',
                                    '2'=>'25%',                            
                                    '1'=>'50%',
                                    '3'=>'75%',
                                    '4'=>'100%',
                                )
                            )
                        );
                        echo $this->Form->input('Empleado.' . $i . '.adherente',array('label'=>'Adherentes','value'=>0));
                        echo "</br>";
                        echo $this->Form->input('Empleado.' . $i . '.exentocooperadoraasistencial',array('label'=>'Exento Coop. Asistencial','value'=>0));
                        echo $this->Form->input('Empleado.' . $i . '.obrasocialsindical',array(
                            'label'=>'Obra social Sindical',
                            'value'=>1,
                            'checked'=>'checked',
                            'title'=>'Indicar si el empleado tiene una obra social que no sea sindical'
                            ));
                        echo "</br>";
                        echo $this->Form->input('Empleado.' . $i . '.obrassociale_id',array(
                                    'label'=>'Obra Social',
                                    'class'=>'chosen-select autoselect',                            
                                    'defaultoption'=>substr($empleado['obrasocial'], 5)
                           
                                    )
                                );
                        
                        echo $this->Form->input('Empleado.' . $i . '.codigoactividad',array(
                            'label'=>'Codigo Actividad',
                            'options'=>$codigoactividad,
                            'class'=>'chosen-select aplicableATodos',
                            'inputclass'=>'codigoactividad',));
                        echo $this->Form->input('Empleado.' . $i . '.codigosituacion',array(
                            'label'=>'Codigo Situacion',
                            'options'=>$codigorevista,
                            'class'=>'chosen-select aplicableATodos',
                            'inputclass'=>'codigosituacion',));
                        echo $this->Form->input('Empleado.' . $i . '.codigocondicion',array(
                            'label'=>'Codigo Condicion',
                            'options'=>$codigocondicion,
                            'class'=>'chosen-select aplicableATodos',
                            'inputclass'=>'codigocondicion',));
                        echo $this->Form->input('Empleado.' . $i . '.codigozona',array(
                            'label'=>'Codigo Zona',
                            'options'=>$codigozona,
                            'class'=>'chosen-select aplicableATodos',
                            'inputclass'=>'codigozona',));
                        echo $this->Form->input('Empleado.' . $i . '.codigomodalidadcontratacion',array(
                            'label'=>'Codigo Modalidad Contratacion',
                            'options'=>$codigomodalidadcontratacion,
                            'class'=>'chosen-select autoselect aplicableATodos',
                            'inputclass'=>'codigomodalidadcontratacion',
                            'defaultoption'=>$empleado['modalcontrato'],
                            ));
                        echo $this->Form->input('Empleado.' . $i . '.codigosiniestrado',array(
                            'label'=>'Codigo Siniestrado',
                            'options'=>$codigosiniestrado,
                            'class'=>'chosen-select aplicableATodos',
                            'inputclass'=>'codigosiniestrado',
                            ));
                        echo $this->Form->input('Empleado.' . $i . '.tipoempresa',array(
                            'label'=>'Tipo empresa',
                            'options'=>$tipoempresa,
                            'class'=>'chosen-select aplicableATodos',
                            'inputclass'=>'tipoempresa',
                            ))."</br>";
                        ?>
                    </fieldset>
                    <?php
                    $i++;
                    ?>
                </div>
                <?php
                ?>
            </td>
        </tr>
        <tr>
            <td>
                <hr width="450px" color="#1e88e5" style='width:100%; ' />
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