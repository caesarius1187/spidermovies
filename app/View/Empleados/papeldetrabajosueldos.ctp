<h3 id="TituloPdtSueldo">Liquidacion: </h3>
    <?php
    /*
     * 1: quincena 1;
     * 2: quincena 2;
     * 3: Mensual;
     * 4: presupuesto 1;
     * 5: presupuesto 2;
     * 6: presupueto 3
     * */
    //Vamos a ver que liquidacion ya tiene hecha el compadre
    $liquidacionesActivas = [
        '1' => false,
        '2' => false,
        '3' => false,
        '4' => false,
        '5' => false,
        '6' => false,
    ];
    foreach ($tieneLiquidacion['Conveniocolectivotrabajo']['Cctxconcepto'] as $cctxconcepto) {
        foreach ($cctxconcepto['Valorrecibo'] as $valorrecibo) {
            $liquidacionesActivas[$valorrecibo['tipoliquidacion']] = true;
        }
    }
    //Vamos a mostrar los botones solo si los empleados tienen Liquidaciones Activadas
    if($empleado['Empleado']['liquidaprimeraquincena']){
        $classAMostrar="btn_cargarliq";
        if($liquidacionesActivas['1']){
            $classAMostrar="btn_cargarliq_liq";
        }
        if($tipoliquidacion==1){
            $classAMostrar = " btn_cargarliq_selected ".$classAMostrar;
        }
        echo $this->Form->button(
            "Primera Quincena",
            array(
                'class'=>$classAMostrar,
                'onClick'=>"cargarSueldoEmpleado('".$empleado['Empleado']['cliente_id']."','".$periodo."','".$empleado['Empleado']['id']."',1)",
                'id'=>'buttonQuincena1',
            ),
            array()
        );
        echo $this->Form->button(
            "Libro de Sueldo PQ",
            array(
                'class'=>'btn_sueldo',
                'onClick'=>"cargarLibroSueldo('".$empleado['Empleado']['id']."','".$periodo."',1)",
                'id'=>'buttonLibroSueldo',
            ),
            array()
        );
        echo $this->Form->button(
            "Recibo de Sueldo PQ",
            array(
                'class'=>'btn_sueldo',
                'onClick'=>"cargarReciboSueldo('".$empleado['Empleado']['id']."','".$periodo."',1)",
                'id'=>'buttonReciboSueldo',
            ),
            array()
        );
    }
    if($empleado['Empleado']['liquidasegundaquincena']){
        $classAMostrar="btn_cargarliq";
        if($liquidacionesActivas['2']){
            $classAMostrar="btn_cargarliq_liq";
        }
        if($tipoliquidacion==2){
            $classAMostrar = " btn_cargarliq_selected ".$classAMostrar;
        }
        echo $this->Form->button(
            "Segunda Quincena",
            array(
                'class'=>$classAMostrar,
                'onClick'=>"cargarSueldoEmpleado('".$empleado['Empleado']['cliente_id']."','".$periodo."','".$empleado['Empleado']['id']."',2)",
                'id'=>'buttonQuincena1',
            ),
            array()
        );
        echo $this->Form->button(
            "Libro de Sueldo SQ",
            array(
                'class'=>'btn_sueldo',
                'onClick'=>"cargarLibroSueldo('".$empleado['Empleado']['id']."','".$periodo."',2)",
                'id'=>'buttonLibroSueldo',
            ),
            array()
        );
        echo $this->Form->button(
            "Recibo de Sueldo SQ",
            array(
                'class'=>'btn_sueldo',
                'onClick'=>"cargarReciboSueldo('".$empleado['Empleado']['id']."','".$periodo."',2)",
                'id'=>'buttonReciboSueldo',
            ),
            array()
        );
    }
    if($empleado['Empleado']['liquidamensual']) {
        $classAMostrar = "btn_cargarliq";
        if ($liquidacionesActivas['3']) {
            $classAMostrar = "btn_cargarliq_liq";
        }
        if ($tipoliquidacion == 3) {
            $classAMostrar = " btn_cargarliq_selected " . $classAMostrar;
        }
        echo $this->Form->button(
            "Mensual",
            array(
                'class' => $classAMostrar,
                'onClick' => "cargarSueldoEmpleado('" . $empleado['Empleado']['cliente_id'] . "','" . $periodo . "','" . $empleado['Empleado']['id'] . "',3)",
                'id' => 'buttonQuincena1',
            ),
            array()
        );
        echo $this->Form->button(
            "Libro de Sueldo M",
            array(
                'class'=>'btn_sueldo',
                'onClick'=>"cargarLibroSueldo('".$empleado['Empleado']['id']."','".$periodo."',3)",
                'id'=>'buttonLibroSueldo',
            ),
            array()
        );
        echo $this->Form->button(
            "Recibo de Sueldo M",
            array(
                'class'=>'btn_sueldo',
                'onClick'=>"cargarReciboSueldo('".$empleado['Empleado']['id']."','".$periodo."',3)",
                'id'=>'buttonReciboSueldo',
            ),
            array()
        );
    }
    if($empleado['Empleado']['liquidapresupuestoprimera']) {
        $classAMostrar = "btn_cargarliq";
        if ($liquidacionesActivas['4']) {
            $classAMostrar = "btn_cargarliq_liq";
        }
        if ($tipoliquidacion == 4) {
            $classAMostrar = " btn_cargarliq_selected " . $classAMostrar;
        }
        echo $this->Form->button(
            "Presupuesto 1",
            array(
                'class' => $classAMostrar,
                'onClick' => "cargarSueldoEmpleado('" . $empleado['Empleado']['cliente_id'] . "','" . $periodo . "','" . $empleado['Empleado']['id'] . "',4)",
                'id' => 'buttonQuincena1',
            ),
            array()
        );
    }
    if($empleado['Empleado']['liquidapresupuestoprimera']) {
        $classAMostrar = "btn_cargarliq";
        if ($liquidacionesActivas['5']) {
            $classAMostrar = "btn_cargarliq_liq";
        }
        if ($tipoliquidacion == 5) {
            $classAMostrar = " btn_cargarliq_selected " . $classAMostrar;
        }
        echo $this->Form->button(
            "Presupuesto 2",
            array(
                'class' => $classAMostrar,
                'onClick' => "cargarSueldoEmpleado('" . $empleado['Empleado']['cliente_id'] . "','" . $periodo . "','" . $empleado['Empleado']['id'] . "',5)",
                'id' => 'buttonQuincena1',
            ),
            array()
        );
    }
    if($empleado['Empleado']['liquidapresupuestomensual']) {
        $classAMostrar = "btn_cargarliq";
        if ($liquidacionesActivas['6']) {
            $classAMostrar = "btn_cargarliq_liq";
        }
        if ($tipoliquidacion == 6) {
            $classAMostrar = " btn_cargarliq_selected " . $classAMostrar;
        }
        echo $this->Form->button(
            "Presupuesto 3",
            array(
                'class' => $classAMostrar,
                'onClick' => "cargarSueldoEmpleado('" . $empleado['Empleado']['cliente_id'] . "','" . $periodo . "','" . $empleado['Empleado']['id'] . "',6)",
                'id' => 'buttonQuincena1',
            ),
            array()
        );
    }

     echo $this->Form->create('Valorrecibo',array(
         'class'=>'formTareaCarga',
         'inputDefaults' => array(
                'label' => false,
            )
        )
     );
    ?>
    <div id="sueldoContent" style="width: 1340px;">
        <table class="tbl_border tbl_sueldo" style="width:100%" cellspacing="0" cellpadding="0" id="pdtsueldo">
            <thead>
                <tr>
                    <td colspan="7">
                        <?php
                        echo "Nombre del empleado: ".$empleado['Empleado']['nombre'];
                        ?>
                    </td>
                    <td style="text-align: right;" rowspan="2">
                        <div class="fab blue">
                            <core-icon icon="add" align="center">
                                <?php echo $this->Form->button('+',
                                    array('type' => 'button',
                                        'class' =>"btn_add",
                                        'onClick' => "location.href='#nuevo_valorrecibopersonalizado'",
                                    )
                                );?>
                            </core-icon>
                            <paper-ripple class="circle recenteringTouch" fit></paper-ripple>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="7">
                        <?php
                        echo "Convenio del empleado: ".$empleado['Conveniocolectivotrabajo']['nombre'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>

                    </td>
                    <td style="width:250px;">

                    </td>
                    <td>
                        Valor
                    </td>
                    <td>
                        Codigo
                    </td>
                    <td>
                        %
                    </td>
                    <td>
                        Codigo
                    </td>
                    <td>
                        Formula
                    </td>
                </tr>
            </thead>
        <?php if(!is_null($empleado['Conveniocolectivotrabajo']['nombre'])) { ?>
            <tbody>
                <?php
                echo "Recibo de sueldo:</br>";

                $i=0;
                $miseccionamostrada="sinseccion";
                $numSeccion = 0;
                foreach ($empleado['Conveniocolectivotrabajo']['Cctxconcepto'] as $conceptoobligatorio) {
                    $styleForTd = "";
                    if($conceptoobligatorio['Concepto']['estotal']){
                        $styleForTd = "
                            color: white;
                            background-color: #9E9E9E;
                            ";
                    }
                    //le vamos a asignar una clase para marcar si es calculado o no para saber si lo podemos ocultar o no
                    $classTRCalculada = "nocalculado";
                    if($conceptoobligatorio['calculado']) {
                        $classTRCalculada = "calculado";
                    }
                    ?>
                    <tr style="<?php echo $styleForTd; ?>" class="<?php echo $classTRCalculada; ?>">
                        <?php
                        /*vamos a contar la cantidad de veces que aparece este concepto para poner el colspan del TD*/
                        $cantSecciones = array();
                        foreach ($empleado['Conveniocolectivotrabajo']['Cctxconcepto'] as $cctxc){
                            $miseccion="sinseccion";
                            if(isset($cctxc['Concepto']['seccion'])){
                                $miseccion=$cctxc['Concepto']['seccion'];
                            }else{
                                $miseccion=$cctxc['seccionpersonalizada'];
                            }
                            if(!isset($cantSecciones[$miseccion])){
                                $cantSecciones[$miseccion]=0;
                            }
                            $cantSecciones[$miseccion]++;
                        }
                        ?>
                        <?php
                        if(isset($conceptoobligatorio['Concepto']['seccion'])){
                            $miseccionamostrar=$conceptoobligatorio['Concepto']['seccion'];
                        }else{
                            $miseccionamostrar=$conceptoobligatorio['seccionpersonalizada'];
                        }
                        $headSeccion = false;
                        if($miseccionamostrada!=$miseccionamostrar){
                            $headSeccion = true;?>
                            <td rowspan="<?php echo $cantSecciones[$miseccionamostrar];?>" style="font-size: 13px; vertical-align:middle!important;" id="seccion<?php echo $numSeccion;?>" >
                                <div style="transform: rotate(270deg); float:left;">
                                    <?php
                                    $miseccionamostrada = $miseccionamostrar;
                                    echo $miseccionamostrar;?>
                                </div>
                            </td><?php
                            $numSeccion ++;
                        }
                        $styleForTd = "
                            display: flex ;
                            width:250px;  
                            border-bottom: 0px; 
                            border-left: 0px; 
                            border-right: 0px;";
                        if($conceptoobligatorio['Concepto']['estotal']){

                        }
                        ?>

                        <td  style="<?php echo $styleForTd; ?>">
                            <?php
                            //aca podriamos buscar el valor que ya guardardamos para este concepto
                            //y mostrar un formulario para modificarlo
                            $valor = 0;
                            $porcentaje = 0;
                            $valorreciboid = 0;
                            $aplicafuncion = true;
                            if(count($conceptoobligatorio['Valorrecibo'])>0){
                                $valor = $conceptoobligatorio['Valorrecibo'][0]['valor'];
                                $valorreciboid = $conceptoobligatorio['Valorrecibo'][0]['id'];
                            }else{

                            }
                            switch ($conceptoobligatorio['Concepto']['id']){
                                case 9:/*Precio de la Hora*/
                                    /* Aca vamos a preguntar si el empleado tiene un cargo definido y si este cargo
                                      tiene un precio de la hora cargado*/
                                    if(isset($empleado['Cargo']['preciohora'])&&$empleado['Cargo']['preciohora']*1!=0){
                                        $valor = $empleado['Cargo']['preciohora']*1;
                                    }
                                    break;
                                case 10:/*Jornal*/
                                    /* Aca vamos a preguntar si el empleado tiene un cargo definido y si este cargo
                                      tiene un jornal cargado*/
                                    if(isset($empleado['Cargo']['jornal'])&&$empleado['Cargo']['jornal']*1!=0){
                                        $valor = $empleado['Cargo']['jornal']*1;
                                    }
                                    break;
                                case 11:/*Jornada*/
                                    $valor = $empleado['Empleado']['jornada'];
                                    break;
                                case 16:/*Ingreso*/
                                    $valor = $empleado['Empleado']['fechaingreso'];
                                    break;
                                case 17:/*Periodo*/
                                    $pemes = substr($periodo, 0, 2);
                                    $peanio = substr($periodo, 3);
                                    $valor = date("Y-m-d",(mktime(0,0,0,$pemes+1,1,$peanio)-1));
                                    break;
                                case 33:/*Obra Social*/
                                    //$conceptoobligatorio['nombre'] = $empleado['Empleado']['obrasocial'];
                                    //$conceptoobligatorio['porcentaje']=$empleado['Empleado']['porcentajeos'];
                                    break;
                                case 34:/*Obra Social Minimo*/
                                    //$valor = $empleado['Empleado']['minimoos'];
                                    break;
                                case 35:/*Obra Social Extraordinario*/
                                    break;
                                case 39:/*Afiliado al Sindicato*/
                                    $valor = $empleado['Empleado']['afiliadosindicato'];
                                    break;
                                case 52:/*Sueldo basico*/
                                        /* Aca vamos a preguntar si el empleado tiene un cargo definido y si este cargo 
                                        tiene un sueldo basico cargado*/
                                        if(isset($empleado['Cargo']['sueldobasico'])&&$empleado['Cargo']['sueldobasico']*1!=0){
                                            $valor = $empleado['Cargo']['sueldobasico']*1;
                                        }
                                    break;
                                case 53:/*Acuerdos No Remunerativos*/
                                        /* Aca vamos a preguntar si el empleado tiene un cargo definido y si este cargo
                                        tiene un Acuerdos No Remunerativos cargado*/
                                        if(isset($empleado['Cargo']['acuerdonoremunerativo'])&&$empleado['Cargo']['acuerdonoremunerativo']*1!=0){
                                            $valor = $empleado['Cargo']['acuerdonoremunerativo']*1;
                                        }
                                    break;
                                case 54:/*Sueldo Sereno(UOCRA)*/
                                        /* Aca vamos a preguntar si el empleado tiene un cargo definido y si este cargo
                                        tiene un sueldo sereno cargado*/
                                        if(isset($empleado['Cargo']['sueldosereno'])&&$empleado['Cargo']['sueldosereno']*1!=0){
                                            $valor = $empleado['Cargo']['sueldosereno']*1;
                                        }
                                    break;
                                case 126:/*Acuerdos Remunerativos*/
                                        /* Aca vamos a preguntar si el empleado tiene un cargo definido y si este cargo
                                        tiene un Acuerdos Remunerativos cargado*/
                                        if(isset($empleado['Cargo']['acuerdoremunerativo'])&&$empleado['Cargo']['acuerdoremunerativo']*1!=0){
                                            $valor = $empleado['Cargo']['acuerdoremunerativo']*1;
                                        }
                                    break;
                                case 134:/*cuota sindical extra 4*/
                                    /*si el impcli al que pertenece el convenio es SEC entonces vamos a preguntar si
                                    tiene activado el "pago del seguro de vida obligatorio*/
                                    if($empleado['Conveniocolectivotrabajo']['Impuesto']['id']==11/*Es SEC?*/){
                                        if(!$empleado['Conveniocolectivotrabajo']['Impuesto']['Impcli'][0]['segurovidaobligatorio']*1){
                                            $aplicafuncion=false;
                                        }else{
                                            //aca tambien tenemos que ver si el IMPCLI(SEC) tiene el dato "primasvo" y asignarlo
                                            //a el % que tendria q usar este campillo
                                            if($empleado['Conveniocolectivotrabajo']['Impuesto']['Impcli'][0]['primasvo']*1!=0){
                                                $porcentaje = $empleado['Conveniocolectivotrabajo']['Impuesto']['Impcli'][0]['primasvo'];
                                            }
                                        }
                                    }
                                    break;
                                /*case 36:/*Cuota Sindical aca estabamos guardando la cuota sindical extra en el empleado pero
                                debe ser la misma para todos dependiendo del convenio
                                    $conceptoobligatorio['nombre'] = $empleado['Empleado']['cuotasindical'];
                                    $conceptoobligatorio['porcentaje']=$empleado['Empleado']['porcentajecs'];
                                    break;
                                /*case 37:/*Cuota Sindical Extra 1 aca estabamos guardando la cuota sindical extra en el empleado pero
                                debe ser la misma para todos dependiendo del convenio
                                    $conceptoobligatorio['nombre'] = $empleado['Empleado']['cuotasindicalextraordinario'];
                                    $conceptoobligatorio['porcentaje']=$empleado['Empleado']['porcentajecse'];
                                    break;*/
                            }
                            echo $conceptoobligatorio['nombre'];
                            ?>
                        </td>
                        <td >
                            <?php

                            $funcionaaplicar="";
                            if($conceptoobligatorio['calculado']&&$aplicafuncion){
                                $funcionaaplicar=$conceptoobligatorio['funcionaaplicar'];
                            }else{
                                $funcionaaplicar = "";
                            }
                            $unidadmedida = $conceptoobligatorio['unidaddemedida'];
                            $datacell = 0;
                            if(!$conceptoobligatorio['campopersonalizado']){
                                $datacell = $conceptoobligatorio['Concepto']['codigo'];
                            }else{
                                $datacell = $conceptoobligatorio['codigopersonalizado'];
                            }
                            echo $this->Form->input('Valorrecibo.'.$i.'.id',array('type'=>'hidden','value'=>$valorreciboid));
                            echo $this->Form->input('Valorrecibo.'.$i.'.periodo',array('type'=>'hidden','value'=>$periodo));
                            echo $this->Form->input('Valorrecibo.'.$i.'.tipoliquidacion',array('type'=>'hidden','value'=>$tipoliquidacion));
                            echo $this->Form->input('Valorrecibo.'.$i.'.cctxconcepto_id',array('type'=>'hidden','value'=>$conceptoobligatorio['id']));
                            echo $this->Form->input('Valorrecibo.'.$i.'.empleado_id',array('type'=>'hidden','value'=>$empleado['Empleado']['id']));
                            //si es boolean vamos a mostrar un Si con true y un no con False para que elija
                            ?>
                            <?php
                            if($unidadmedida=="boolean"){
                                $optionsValor=array(
                                    'type'=>'checkbox',
                                    'data-cell'=>$datacell ,
                                    'valor'=>$valor ,
                                    'data-formula'=>$funcionaaplicar
                                );
                                if($valor){
                                    $optionsValor['checked']='checked';
                                }
                                echo $this->Form->input('Valorrecibo.'.$i.'.valor',$optionsValor);
                            }else if($unidadmedida=="fecha"){
                                echo $this->Form->input('Valorrecibo.'.$i.'.valor',array(
                                    'value'=>$valor,
                                    'data-cell'=>$datacell ,
                                ));
                            }else{
                                echo $this->Form->input('Valorrecibo.'.$i.'.valor',array(
                                    'value'=>$valor,
                                    'data-cell'=>$datacell ,
                                    'data-formula'=>$funcionaaplicar,
                                    'data-format'=>'00[.]00'
                                ));
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $this->Form->label('Valorrecibo.'.$i.'.codigo',$datacell);
                            ?>
                        </td>
                        <td>
                                <?php
                                    $porcentajeDataCell = "";
                                    if($conceptoobligatorio['conporcentaje']){
                                        //vamos a sacar el codigo del porcentaje que va ha ser
                                        $porcentajeDataCell = "B".substr($datacell, 1);
                                        //si $porcentaje es != 0 es por que ya le asignamos algun valor
                                        if($porcentaje==0){
                                            $porcentaje = $conceptoobligatorio['porcentaje'];
                                        }

                                        echo $this->Form->input('Valorrecibo.'.$i.'.porcentaje',array(
                                                    'type'=>'text',
                                                    'value'=>$porcentaje,
                                                    'data-cell'=>$porcentajeDataCell ,
                                                    'style' => 'width:28px'
                                                )
                                            );
                                    }
                                ?>
                        </td>
                        <td>
                            <?php echo $porcentajeDataCell; ?>
                        </td>
                        <td>
                            <?php
                            if($conceptoobligatorio['calculado']){
                                echo $this->Form->input('Valorrecibo.'.$i.'.formula',array('type'=>'text',
                                        'value'=>$funcionaaplicar,
                                        'posicion'=>$i,
                                        'headseccion'=>$headSeccion?'1':'0',
                                        'seccion'=>$numSeccion-1,
                                        'class'=>'funcionAAplicar',
                                        'style'=>'width:600px; padding: 0px'
                                    )
                                );
                            }
                            if($conceptoobligatorio['campopersonalizado']){
                                echo
                                $this->Form->postLink(
                                    __('x'),
                                    array(
                                        'controller' => 'cctxconceptos',
                                        'action' => 'delete',
                                        $conceptoobligatorio['id']
                                    ),
                                    null,
                                    __('Esta seguro que quiere eliminar el conceptos # %s?', $conceptoobligatorio['id']));
                            }
                            ?>
                        </td>
                    </tr>
                        <?php
                        $i++;
                    }
                }else{
                    echo "El empleado no esta inscripto en ningun convenio colectivo de trabajo";
                }
                ?>
                <tr>
                    <td colspan="7" align="right"><div style="float: right;"> <?php echo $this->Form->end('Guardar');?></div></td>
                </tr>
            </tbody>
        </table>
    </div>

