<?php
if(count($empleado)==0){
    return '<div class="divLiquidacionSueldo"></div>';
}
?>
<div class="divLiquidacionSueldo parafiltrarempleados" valorparafiltrar="<?php echo $empleado['Empleado']['nombre']." ".$empleado['Empleado']['cuit']?>">
    <?php
    /*
     * 1: quincena 1;
     * 2: quincena 2;
     * 3: Mensual;
     * 4: presupuesto 1;
     * 5: presupuesto 2;
     * 6: presupueto 3
     * 7: SAC
     * */
    //Vamos a ver que liquidacion ya tiene hecha el compadre
    echo $this->Form->input('indiceCargaEmpleado'.$empleado['Empleado']['id'],array('type'=>'hidden','value'=>0));

     echo $this->Form->create('Valorrecibo',array(
         'class'=>'formTareaCarga',
         'id'=>'ValorreciboPapeldetrabajosueldosForm'.$empleado['Empleado']['id'],
         'inputDefaults' => array(
                'label' => false,
            )
        )
     );
    ?>
    <div id="sueldoContent" style="">
        <table class="tbl_border tbl_sueldo" style="width:100%" cellspacing="0" cellpadding="0" id="pdtsueldo">
            <thead>
                <tr>
                    <td colspan="7" >
                        <span class="spanempleado" onclick="showHideColumnsEmpleado('<?php echo $empleado['Empleado']['id']?>')" data-identificacion="<?php echo $empleado['Empleado']['id']?>">
                        <?php
                            echo $empleado['Empleado']['nombre'];
                        ?>
                        </span>
                    </td>
<!--                    <td style="text-align: right;" rowspan="2">-->
<!--                        <div class="fab blue">-->
<!--                            <core-icon icon="add" align="center">-->
<!--                                --><?php //echo $this->Form->button('+',
//                                    array('type' => 'button',
//                                        'class' =>"btn_add",
//                                        'onClick' => "location.href='#nuevo_valorrecibopersonalizado'",
//                                    )
//                                );?>
<!--                            </core-icon>-->
<!--                            <paper-ripple class="circle recenteringTouch" fit></paper-ripple>-->
<!--                        </div>-->
<!--                    </td>-->
                </tr>
                <tr>
                    <td colspan="7">
                       <span>
                        <?php
                        echo "Convenio: ".$empleado['Conveniocolectivotrabajo']['nombre'];
                        ?>
                       </span>
                    </td>
                </tr>
                <tr>
                    <td class="tdseccion">

                    </td>
                    <td class="tdconcepto" style="width:100px;">

                    </td>
                    <td width="80px" class="tdvalor">
                        Valor
                    </td>
                    <td width="15px" class="tdcodigo">
                        Codigo
                    </td>
                    <td width="15px" class="tdalicuota">
                        %
                    </td>
                    <td width="15px" class="tdcodigoalicuota">
                        Codigo
                    </td>
                    <td class="tdformula">
                        Formula
                    </td>
                </tr>
            </thead>
        <?php if(!is_null($empleado['Conveniocolectivotrabajo']['nombre'])) { ?>
            <tbody><?php
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
                            <td  class="tdseccion seccion<?php echo $numSeccion;?>" width="15px" rowspan="<?php echo $cantSecciones[$miseccionamostrar];?>" style="font-size: 13px; vertical-align:middle!important;" id="seccion<?php echo $numSeccion;?>" >
                                <div style="transform: rotate(270deg); float:left;width: 10px;">
                                    <?php
                                    $miseccionamostrada = $miseccionamostrar;
                                    echo $miseccionamostrar;?>
                                </div>
                            </td><?php
                            $numSeccion ++;
                        }
                        $styleForTd = "
                            display: flex ;
                            width:200px;  
                            border-bottom: 0px; 
                            border-left: 0px; 
                            border-right: 0px;
                            white-space: nowrap;";
                        ?>

                        <td  class="tdconcepto" style="<?php echo $styleForTd; ?>" orden="<?php echo $conceptoobligatorio['orden']?>">
                            <?php
                            //aca buscamos el valor que ya guardardamos para este concepto
                            //y mostramos un formulario para modificarlo
                            $valor = 0;
                            $porcentaje = 0;
                            $valorreciboid = 0;
                            $aplicafuncion = true;
                            $muestraAplicarATodos = false;
                            $formulamodificada = false;
                            $nuevaformula = "";
                            if(count($conceptoobligatorio['Valorrecibo'])>0){
                                $valor = $conceptoobligatorio['Valorrecibo'][0]['valor'];
                                $formulamodificada = $conceptoobligatorio['Valorrecibo'][0]['formulamodificada'];
                                $nuevaformula = $conceptoobligatorio['Valorrecibo'][0]['nuevaformula'];
                                $valorreciboid = $conceptoobligatorio['Valorrecibo'][0]['id'];
                            }else{

                            }
                            if($conceptoobligatorio['Concepto']['aplicaatodos']){
                                $muestraAplicarATodos=true;
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
                                case 67:/*Egreso*/
                                    $valor = $empleado['Empleado']['fechaegreso'];
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
                                case 38:/*cuota sindical extra 2*/
                                    if($empleado['Conveniocolectivotrabajo']['id']==5/*Es Construcción Quincenal?*/){
                                        //el segurod e vida obligatorio se paga solo si estamos en la segunda quincena
                                        //o si la fecha de baja esta dentro de la primera quincena
                                        if($numeroliquidacion != 2){
                                            //No estamos en la segunda quincena

                                            $aplicafuncion = false;
                                            //$aplicafuncion = true;

                                            //tengo que preguntar si la fecha de baja es mayor que 01-periodo
                                            //y menor que 15-periodo
                                            $inicioperiodo=date('Y-m-d',strtotime('01-'.$pemes.'-'.$peanio));
                                            $finPrimeraQuincena=date('Y-m-d',strtotime('15-'.$pemes.'-'.$peanio));
                                            $confecha = $empleado['Empleado']['fechaegreso']!="";
                                            $mayorqueinicio =  $inicioperiodo<$empleado['Empleado']['fechaegreso'];
                                            $menorquefin =  $empleado['Empleado']['fechaegreso']<$finPrimeraQuincena;
                                            if($confecha&&$mayorqueinicio&&$menorquefin)
                                            {
                                                $aplicafuncion = true;
                                            }
                                        }else{
                                            //preguntemos si la fecha de despido cae en la primera quincena
                                            $inicioperiodo=date('Y-m-d',strtotime('01-'.$pemes.'-'.$peanio));
                                            $finPrimeraQuincena=date('Y-m-d',strtotime('15-'.$pemes.'-'.$peanio));
                                            $confecha = $empleado['Empleado']['fechaegreso']!="";
                                            $mayorqueinicio =  $inicioperiodo<$empleado['Empleado']['fechaegreso'];
                                            $menorquefin =  $empleado['Empleado']['fechaegreso']<$finPrimeraQuincena;
                                            if($confecha&&$mayorqueinicio&&$menorquefin)
                                            {
                                                $aplicafuncion = false;
                                            }
                                        }
                                    }
                                    break;
                                case 39:/*Afiliado al Sindicato*/
                                    $valor = $empleado['Empleado']['afiliadosindicato'];
                                    break;
                                case 51:/*CODIGO AFIP*/
                                    $valor = $empleado['Empleado']['codigoafip'];
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

                                case 117:/*Aporte Adicional OS O3*/
                                    /* si es construccion no aplica en el SAC*/
                                    if($empleado['Conveniocolectivotrabajo']['id']==5/*Es Construcción Quincenal?*/){
                                        if($numeroliquidacion == 7){
                                            //SAC de construccion = 0, o sea no aplica funcion
                                            $aplicafuncion = false;
                                        }
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
                                case 152:/*Mejor Remunerativos*/
                                    //si ya guardamos un valo no reemplazemos por el nuevo, mostremos el guardado
                                    if($valorreciboid==0){
                                        $valor = $mayorRemunerativo;
                                    }
                                    break;
                                case 153:/*Mejor NO Remunerativos*/
                                    /* Aca vamos a preguntar si el empleado tiene un cargo definido y si este cargo
                                    tiene un Acuerdos Remunerativos cargado*/
                                    if($valorreciboid==0) {
                                        $valor = $mayorNORemunerativo;
                                    }
                                    break;
                                case 161:/*Basico Categoria Minima*//*Basico Adm 2da*/
                                    if($valorreciboid==0) {
                                        $valor = $basicoMinimoCargo;
                                    }
                                    break;
                                case 162:/*Almuerzo o Refrigerio*/
                                    /* Aca vamos a preguntar si el empleado tiene un cargo definido y si este cargo
                                    tiene un Acuerdos Remunerativos cargado*/
                                    $valor = 1;
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
                        <?php


                        $classInputValor="".$conceptoobligatorio['Concepto']['codigo'];
                        $inputClass="";

                        if($muestraAplicarATodos){
                            $classInputValor .= " aplicableATodos";
                            $inputClass = "input".$conceptoobligatorio['Concepto']['codigo'];
                        }?>
                        <td width="80px" class="tdvalor">
                            <?php
                            $funcionaaplicar="";
                            if($conceptoobligatorio['calculado']&&$aplicafuncion){
                                //aca aplico la formula del cctxconcepto pero si se ha modificado para este valorrecibo
                                //muestro la modificada
                                if($formulamodificada){
                                    $funcionaaplicar=$nuevaformula;
                                }else{
                                    $funcionaaplicar=$conceptoobligatorio['funcionaaplicar'];
                                }
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
                            echo $this->Form->input('Valorrecibo.'.$i.'.tipoliquidacion',array('type'=>'hidden','value'=>$numeroliquidacion));
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
                                    'data-formula'=>$funcionaaplicar,
                                    'class'=>$classInputValor,
                                    'inputclass' => $inputClass,
                                    'valdata-codigo' => $conceptoobligatorio['Concepto']['codigo'],
                                );
                                if($valor){
                                    $optionsValor['checked']='checked';
                                }
                                echo $this->Form->input('Valorrecibo.'.$i.'.valor',$optionsValor);
                            }else if($unidadmedida=="fecha"){
                                echo $this->Form->input('Valorrecibo.'.$i.'.valor',array(
                                    'value'=>$valor,
                                    'data-cell'=>$datacell ,
                                    'class'=>$classInputValor,
                                    'inputclass' => $inputClass,
                                    'valdata-codigo' => $conceptoobligatorio['Concepto']['codigo'],
                                ));
                            }else{
                                echo $this->Form->input('Valorrecibo.'.$i.'.valor',array(
                                    'value'=>$valor,
                                    'data-cell'=>$datacell ,
                                    'data-formula'=>$funcionaaplicar,
                                    'data-format'=>'00[.]00',
                                    'class'=>$classInputValor,
                                    'inputclass' => $inputClass,
                                    'valdata-codigo' => $conceptoobligatorio['Concepto']['codigo'],
                                    ));
                            }
                            ?>
                        </td>
                        <td  width="15px" class="tdcodigo">
                            <?php
                            echo $this->Form->label('Valorrecibo.'.$i.'.codigo',$datacell);
                            ?>
                        </td>
                        <td  width="15px" class="tdalicuota">
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
                        <td  width="15px" class="tdcodigoalicuota">
                            <?php echo $porcentajeDataCell; ?>
                        </td>
                        <td class="tdformula" width="276px">
                            <?php
                            if($conceptoobligatorio['calculado']){

                                $optionsFormulaMofidicada=array(
                                    'type'=>'checkbox',
                                    'valor'=>$formulamodificada ,
                                    'value'=>$formulamodificada ,
                                    'style'=>'display:none'
                                );
                                if($formulamodificada){
                                    $optionsFormulaMofidicada['checked']='checked';
                                }
                                echo $this->Form->input('Valorrecibo.'.$i.'.formulamodificada',$optionsFormulaMofidicada);


                                echo $this->Form->input('Valorrecibo.'.$i.'.nuevaformula',array('value'=>$nuevaformula,'style'=>'display:none'));
                                echo $this->Form->input('Valorrecibo.'.$i.'.formula',array('type'=>'text',
                                        'value'=>$funcionaaplicar,
                                        'posicion'=>$i,
                                        'headseccion'=>$headSeccion?'1':'0',
                                        'seccion'=>$numSeccion-1,
                                        'class'=>'funcionAAplicar',
                                        'style'=>'width:235px; padding: 0px',
                                        'data-codigo' => $conceptoobligatorio['Concepto']['codigo'],
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
                    <td colspan="7" align="right"><div style="float: right;"> <?php echo $this->Form->end();?></div></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>