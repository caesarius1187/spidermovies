<div class="index">

    <?php
    $labelClifch = $cliente['Cliente']['nombre'];
    $labelCuitfch =$cliente['Organismosxcliente'][0]['usuario'].'-Clave: '.$cliente['Organismosxcliente'][0]['clave'];
    ?>
    <div>
        <h1><?php echo __($impuesto['nombre']); ?></h1>
        <label><?php echo $labelClifch;?>-Usuario:<?php echo $labelCuitfch;?></label>
    </div>
</div>
<div id="tabsTareaImpuesto" style="margin-left: 8px;">
	<div class="tabsTareaImpuesto_active" onClick="showPapelesDeTrabajo()" id="tab_PapelesDeTrabajo"><h2>Impuestos determinados</h2></div>
	<div class="tabsTareaImpuesto" onClick="showPagos()" id="tab_Pagos"><h2>Pagos</h2></div>
</div>
<div id="divPrepararPapelesDeTrabajo" class="tareapapeldetrabajo index">
    <div id="form_prepararPapeles" class="prepararPapeles"  style="float: left;">
        <?php
        //Aca vamos a personalizar distintos formularios dependiendo de que impuesto sea el que necesitamos ejecutar, ya que si bien son parecidos algunos de ellos tienen particularidades
        ?>
        <div style="width: 100%;">
            <?php
            //aca vamos a mostrar informacion sobre las ventas,compras y retenciones cargadas
            $mostrarAlertaVentasComprasConceptos = false;
            switch ($impuesto['id']) {
                case 4/*Monotributo*/:
                    $mostrarAlertaVentasComprasConceptos = true;
                    echo $this->Form->button('Papel de Trabajo', array(
                        'id' => 'buttonPDT',
                        'type' => 'button',
                        'class'=>'buttonImpcli',
                        'onClick' => 'verPapelDeTrabajoMonotributo('."'".$periodo."'".','."'".$impcliid."'".')',
                        'escape' => false
                    ));
                    break;
                case 6/*Actividades Varias*/:
                    $mostrarAlertaVentasComprasConceptos = true;
                    echo $this->Form->button('Papel de Trabajo', array(
                        'id' => 'buttonPDT',
                        'type' => 'button',
                        'class'=>'buttonImpcli',
                        'onClick' => 'verPapelDeTrabajoActividadesVarias('."'".$periodo."'".','."'".$impcliid."'".')',
                        'escape' => false
                    ));
                    break;
                case 10/*SUSS*/:
                    echo $this->Form->button('Papel de Trabajo', array(
                        'id' => 'buttonPDT',
                        'type' => 'button',
                        'class'=>'buttonImpcli',
                        'onClick' => 'verPapelDeTrabajoSUSS('."'".$periodo."'".','."'".$impcliid."'".')',
                        'escape' => false
                    ));
                    break;
                case 12/*Cooperadora Asistencial*/:
                    echo $this->Form->button('Papel de Trabajo', array(
                        'id' => 'buttonPDT',
                        'type' => 'button',
                        'class'=>'buttonImpcli',
                        'onClick' => 'verPapelDeTrabajoCooperadoraAsistencial('."'".$periodo."'".','."'".$impcliid."'".')',
                        'escape' => false
                    ));
                    break;
                case 21/*Actividades Economicas*/:
                case 174/*Convenio Multilateral*/:
                    $mostrarAlertaVentasComprasConceptos = true;
                    echo $this->Form->button('Papel de Trabajo', array(
                        'id' => 'buttonPDT',
                        'type' => 'button',
                        'class'=>'buttonImpcli',
                        'onClick' => 'verPapelDeTrabajoConvenioMultilateral('."'".$periodo."'".','."'".$impcliid."'".')',
                        'escape' => false
                    ));
                    break;

                default:
                    if($impuesto['organismo']=='sindicato'){
                        echo $this->Form->button('Papel de Trabajo', array(
                            'id' => 'buttonPDT',
                            'type' => 'button',
                            'class'=>'buttonImpcli',
                            'onClick' => 'verPapelDeTrabajoSindicato('."'".$periodo."'".','."'".$impcliid."'".')',
                            'escape' => false
                        ));
                    }
                    break;
            }
            if($mostrarAlertaVentasComprasConceptos){
            ?>
            <label>
                <?php
                if(isset($cliente['Venta'][0]['Venta'][0]['misventas'])){
                    echo $this->Html->image('test-pass-icon.png',array(
                            'alt' => 'open',
                            'class' => 'btn_exit',
                        )
                    );
                    echo "Se han cargado ".$cliente['Venta'][0]['Venta'][0]['misventas']." Ventas en este periodo</br>";
                }else{
                    echo $this->Html->image('test-fail-icon.png',array(
                            'alt' => 'open',
                            'class' => 'btn_exit',
                        )
                    );
                    echo "NO se han cargado Ventas en este periodo</br>";
                }
                if(isset($cliente['Compra'][0]['Compra'][0]['miscompras'])){
                    echo $this->Html->image('test-pass-icon.png',array(
                            'alt' => 'open',
                            'class' => 'btn_exit',
                        )
                    );
                    echo "Se han cargado ".$cliente['Compra'][0]['Compra'][0]['miscompras']." Compras en este periodo</br>";
                }else{
                    echo $this->Html->image('test-fail-icon.png',array(
                            'alt' => 'open',
                            'class' => 'btn_exit',
                        )
                    );
                    echo "NO se han cargado Compras en este periodo</br>";
                }
                if(isset($cliente['Conceptosrestante'][0]['Conceptosrestante'][0]['misconceptos'])){
                    echo $this->Html->image('test-pass-icon.png',array(
                            'alt' => 'open',
                            'class' => 'btn_exit',
                        )
                    );
                    echo "Se han cargado ".$cliente['Conceptosrestante'][0]['Conceptosrestante'][0]['misconceptos']." Pagos a cuenta en este periodo</br>";
                }else{
                    echo $this->Html->image('test-fail-icon.png',array(
                            'alt' => 'open',
                            'class' => 'btn_exit',
                        )
                    );
                    echo "NO se han cargado Pagos a cuenta  en este periodo</br>";
                }
                ?>
            </label>
            <?php } ?>
        </div>

        <div id="divVencimiento"  >
            <?php
            echo $this->Form->input('vencimientogeneral', array(
                                                  'class'=>'datepicker',
                                                  'type'=>'text',
                                                  'label'=>"Vencimiento:",
                                                  'readonly','readonly',
                                                  'value'=>$fchvto,
                                                  ));
            $mensajeAlertaFecha = "";
            switch ($fchvtoOrigen) {
                case 'diaDeHoy':
                    $mensajeAlertaFecha = "Fecha del dia de hoy recomendada";
                    break;
                case 'guardadaEnImpuesto':
                    $mensajeAlertaFecha = "Fecha Guardada previamente";
                    break;
                case 'VencimientoRecomendado':
                    $mensajeAlertaFecha = "Fecha de Vencimiento Recomendada";
                    break;
                default:
                    break;
             }
             echo $this->HTML->image('ii.png',array('style'=>'width:15px;height:15px','title'=>$mensajeAlertaFecha));
            ?>
        </div>
 	    <?php
	    echo $this->Form->create('Eventosimpuesto',array('class'=>'formTareaCarga','action'=>'realizartarea5'));

	    echo $this->Form->input('Eventosimpuesto.0.haycambio',array('value'=> true ,'type'=>'hidden','id'=>'EventosimpuestoHaycambios'));
	    echo $this->Form->input('Eventosimpuesto.0.cliente_id',array('value'=>$clienteid,'type'=>'hidden'));
	    $botonOK="Guardar";
        switch ($tipopago) {
            case 'unico':
                $botonOK="Guardar";
                $eventoid=0;
                $fchvto=date('d-m-Y');
                $montovto = 0;
                $montoc = 0;
                $descripcion = '';

                foreach ($eventosimpuestos as $eventosimpuesto){//vamos a buscar el evento para ver si ya esta creada este item
                    $eventoid = $eventosimpuesto['Eventosimpuesto']['id'];
                    $fchvto = $eventosimpuesto['Eventosimpuesto']['fchvto'];
                    $montovto = $eventosimpuesto['Eventosimpuesto']['montovto'];
                    $descripcion = $eventosimpuesto['Eventosimpuesto']['descripcion'];
                    $montoc = $eventosimpuesto['Eventosimpuesto']['monc'];
                }
                echo $this->Form->input('Eventosimpuesto.0.id',array('type'=>'hidden','value'=>$eventoid,));
                echo $this->Form->input('Eventosimpuesto.0.impcli_id',array('value'=>$impcliid,'type'=>'hidden'));
                echo $this->Form->input('Eventosimpuesto.0.periodo',array('value'=>$periodo,'type'=>'hidden'));
                echo $this->Form->input('Eventosimpuesto.0.fchvto', array(
                                                  'class'=>'hiddendatepicker',
                                                  'type'=>'hidden',
                                                  'label'=>'Fch. Vto.',
                                                  'readonly','readonly',
                                                  'style'=>'width:80px',
                                                  'value'=>date('d-m-Y',strtotime($fchvto)),
                                                  ));
                echo $this->Form->input('Eventosimpuesto.0.montovto',array('label'=>'Monto a Pagar','default'=>"0", 'style'=>'width:113px','value'=>$montovto,));
                //los sindicatos no tienen monto a favor ni el impuesto Autonomo(id=14)
                if($impuesto['organismo']!='sindicato'&&$impuesto['id']!=14/*Imp: Autonomo*/){
                    echo $this->Form->input('Eventosimpuesto.0.monc',array('label'=>'Monto a Favor','default'=>"0",'style'=>'width:113px','value'=>$montoc));
                }
                echo $this->Form->input('Eventosimpuesto.0.descripcion',array('default'=>"-", 'style'=>'width:100px','value'=>$descripcion));
            break;
            case 'provincia':
                if(count($impcliprovincias)>0){
                    switch ($impuesto['id']) {
                    case 174/*Convenio Multilateral*/:
                    case 21/*Actividades Economicas*/:
                        $eventoPos=0;
                        foreach ($impcliprovincias as $key => $impcliprovincia) {
                            $eventoid=0;
                            $fchvto=date('d-m-Y');
                            $montovto = 0;
                            $provincia = $impcliprovincia['partido_id'];
                            $montoc = 0;
                            $descripcion = '';
                            $mybaseprorrateada = array();
                            foreach ($eventosimpuestos as $eventosimpuesto){//vamos a buscar el evento para ver si ya esta creada estprovincia
                                if($eventosimpuesto['Eventosimpuesto']['partido_id']==$impcliprovincia['partido_id']){
                                    $eventoid = $eventosimpuesto['Eventosimpuesto']['id'];
                                    $fchvto = $eventosimpuesto['Eventosimpuesto']['fchvto'];
                                    $montovto = $eventosimpuesto['Eventosimpuesto']['montovto'];
                                    $descripcion = $eventosimpuesto['Eventosimpuesto']['descripcion'];
                                    $montoc = $eventosimpuesto['Eventosimpuesto']['monc'];
                                    if(isset($eventosimpuesto['Basesprorrateada'])){
                                        $mybaseprorrateada = $eventosimpuesto['Basesprorrateada'];
                                    }
                                }
                            }
                            //hay que mostrar un formulario para cada provincia que este dada de alta en estos impuestos
                            echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.id',array('type'=>'hidden','value'=>$eventoid));
                            echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.partido_id', array('default'=>$provincia,'style'=>'width:80px','label'=>$eventoPos==0?'Provincia':''));
                            echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.impcli_id',array('value'=>$impcliid,'type'=>'hidden'));
                            echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.periodo',array('value'=>$periodo,'type'=>'hidden'));
                            echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.fchvto', array(
                                                              'class'=>'hiddendatepicker',
                                                              'type'=>'text',
                                                              'label'=>$eventoPos==0?'Fch. Vto.':'',
                                                              'readonly','readonly',
                                                              'style'=>'width:80px',
                                                              'value'=>date('d-m-Y',strtotime($fchvto)),
                                                              ));
                            echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.montovto',array('label'=>$eventoPos==0?'Monto a Pagar':'','default'=>$montovto, 'style'=>'width:113px'));
                            //los sindicatos no tienen monto a favor ni el impuesto Autonomo(id=14)
                            if($impuesto['organismo']!='sindicato'&&$impuesto['id']!=14/*Imp: Autonomo*/){
                                echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.monc',array('label'=>$eventoPos==0?'Monto a Favor':'','default'=>$montoc,'style'=>'width:113px'));
                            }
                            $eventoPosBase=0;
                            //vamos a crear una base prorrateada para cada actividad para cada provincia (o sea para cada eventoimpuesto)
                            foreach ($cliente['Actividadcliente'] as $key => $actividadcliente) {
                                    $basesprorrateadaid=0;
                                    $basesprorrateadamonto=0;
                                    $actividadclienteid=$actividadcliente['id'];
                                    if(count($mybaseprorrateada)>0){
                                        foreach ($mybaseprorrateada as $key => $basesprorrateada) {
                                            if($basesprorrateada['actividadcliente_id']==$actividadcliente['id']){
                                                $basesprorrateadaid = $basesprorrateada['id'];
                                                $basesprorrateadamonto = $basesprorrateada['baseprorrateada'];
                                                $actividadclienteid = $basesprorrateada['actividadcliente_id'];
                                            }
                                        }
                                    }

                                    echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Basesprorrateada.'.$eventoPosBase.'.id',array(
                                        'value'=>$basesprorrateadaid,'type'=>'hidden', 'style'=>'width:100px'));
                                    echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Basesprorrateada.'.$eventoPosBase.'.eventosimpuesto_id',array(
                                        'value'=>$eventoid,'type'=>'hidden', 'style'=>'width:100px'));
                                    echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Basesprorrateada.'.$eventoPosBase.'.impcliprovincia_id', array(
                                        'type'=>'hidden','value'=>$impcliprovincia['id'],'style'=>'width:80px'));
                                    echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Basesprorrateada.'.$eventoPosBase.'.impcli_id',array(
                                        'value'=>$impcliid,'type'=>'hidden'));
                                    echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Basesprorrateada.'.$eventoPosBase.'.actividadcliente_id',array(
                                        'value'=>$actividadclienteid,'type'=>'hidden'));
                                    echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Basesprorrateada.'.$eventoPosBase.'.baseprorrateada',array(
                                        'value'=>$basesprorrateadamonto,'type'=>'hidden'));
                                    echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Basesprorrateada.'.$eventoPosBase.'.periodo',array(
                                        'value'=>$periodo,'type'=>'hidden'));
                                    $eventoPosBase++;
                            }
                            //ahora vamos a crear los campos para registrar los Conceptos restantes que estan relacionados a este impcli y a este periodo
                            if(isset($conceptosrestantesimpcli)){
                                foreach ($conceptosrestantesimpcli as $conceptosrestante){
                                    if($conceptosrestante['partido_id']==$impcliprovincia['partido_id']){
                                        echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Conceptosrestante.'.$eventoPos.'.id',array(
                                            'value'=>$conceptosrestante['id'],'type'=>'hidden'));
                                        echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Conceptosrestante.'.$eventoPos.'.partido_id',array(
                                            'value'=>$conceptosrestante['partido_id'],'type'=>'hidden'));
                                        echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Conceptosrestante.'.$eventoPos.'.cliente_id',array(
                                            'value'=>$conceptosrestante['cliente_id'],'type'=>'hidden'));
                                        echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Conceptosrestante.'.$eventoPos.'.impcli_id',array(
                                            'value'=>$conceptosrestante['impcli_id'],'type'=>'hidden'));
                                        echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Conceptosrestante.'.$eventoPos.'.conceptostipo_id',array(
                                            'value'=>$conceptosrestante['conceptostipo_id'],'type'=>'hidden'));
                                        echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Conceptosrestante.'.$eventoPos.'.periodo',array(
                                            'value'=>$conceptosrestante['periodo'],'type'=>'hidden'));
                                        echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Conceptosrestante.'.$eventoPos.'.montoretenido',array(
                                            'value'=>$conceptosrestante['montoretenido'],'type'=>'hidden'));
                                        echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Conceptosrestante.'.$eventoPos.'.fecha',array(
                                            'value'=>$conceptosrestante['fecha'],'type'=>'hidden'));
                                        echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Conceptosrestante.'.$eventoPos.'.descripcion',array(
                                            'value'=>$conceptosrestante['descripcion'],'type'=>'hidden'));
                                    }
                                }
                            }
                            $eventoPos++;
                            echo "</br>";
                        }
                        echo "</br>";
                    break;
                    default:
                        echo $this->Form->input('Eventosimpuesto.0.id',array('type'=>'hidden'));
                        echo $this->Form->input('Eventosimpuesto.0.partido_id', array('style'=>'width:80px'));
                        echo $this->Form->input('Eventosimpuesto.0.impcli_id',array('value'=>$impcliid,'type'=>'hidden'));
                        echo $this->Form->input('Eventosimpuesto.0.periodo',array('value'=>$periodo,'type'=>'hidden'));
                        echo $this->Form->input('Eventosimpuesto.0.fchvto', array(
                                                          'class'=>'hiddendatepicker',
                                                          'type'=>'text',
                                                          'label'=>'Fch. Vto.',
                                                          'readonly','readonly',
                                                          'style'=>'width:80px',
                                                          'value'=>date('d-m-Y',strtotime($fchvto)),
                                                          ));
                        echo $this->Form->input('Eventosimpuesto.0.montovto',array('label'=>'Monto a Pagar','default'=>"0", 'style'=>'width:113px'));
                        //los sindicatos no tienen monto a favor ni el impuesto Autonomo(id=14)
                        if($impuesto['organismo']!='sindicato'&&$impuesto['id']!=14/*Imp: Autonomo*/){
                            echo $this->Form->input('Eventosimpuesto.0.monc',array('label'=>'Monto a Favor','default'=>"0",'style'=>'width:113px'));
                        }
                        echo $this->Form->input('Eventosimpuesto.0.descripcion',array('default'=>"-", 'style'=>'width:100px'));
                    break;
                    }
                }else{
                    echo $this->Form->input('Eventosimpuesto.0.id',array('type'=>'hidden'));
                    echo $this->Form->input('Eventosimpuesto.0.partido_id', array('style'=>'width:80px'));
                    echo $this->Form->input('Eventosimpuesto.0.impcli_id',array('value'=>$impcliid,'type'=>'hidden'));
                    echo $this->Form->input('Eventosimpuesto.0.periodo',array('value'=>$periodo,'type'=>'hidden'));
                    echo $this->Form->input('Eventosimpuesto.0.fchvto', array(
                                                      'class'=>'hiddendatepicker',
                                                      'type'=>'text',
                                                      'label'=>'Fch. Vto.',
                                                      'readonly','readonly',
                                                      'style'=>'width:80px',
                                                      'value'=>date('d-m-Y',strtotime($fchvto)),
                                                      ));
                    echo $this->Form->input('Eventosimpuesto.0.montovto',array('label'=>'Monto a Pagar','default'=>"0", 'style'=>'width:113px'));
                    //los sindicatos no tienen monto a favor ni el impuesto Autonomo(id=14)
                    if($impuesto['organismo']!='sindicato'&&$impuesto['id']!=14/*Imp: Autonomo*/){
                        echo $this->Form->input('Eventosimpuesto.0.monc',array('label'=>'Monto a Favor','default'=>"0",'style'=>'width:113px'));
                    }
                    echo $this->Form->input('Eventosimpuesto.0.descripcion',array('default'=>"-", 'style'=>'width:100px'));
                }
                break;
            case 'municipio':
                if(count($impcliprovincias)>0){
                    switch ($impuesto['id']) {
                    case 6/*Actividades Varias*/:
                        $eventoPos=0;
                        foreach ($impcliprovincias as $key => $impcliprovincia) {
                            $eventoid=0;
                            $fchvto=date('d-m-Y');
                            $montovto = 0;
                            $localidad = $impcliprovincia['localidade_id'];
                            $montoc = 0;
                            $descripcion = '';
                            foreach ($eventosimpuestos as $eventosimpuesto){
                                //vamos a buscar el evento para ver si ya esta creada estprovincia
                                if($eventosimpuesto['Eventosimpuesto']['localidade_id']==$impcliprovincia['localidade_id']){
                                    $eventoid = $eventosimpuesto['Eventosimpuesto']['id'];
                                    $fchvto = $eventosimpuesto['Eventosimpuesto']['fchvto'];
                                    $montovto = $eventosimpuesto['Eventosimpuesto']['montovto'];
                                    $descripcion = $eventosimpuesto['Eventosimpuesto']['descripcion'];
                                    $montoc = $eventosimpuesto['Eventosimpuesto']['monc'];
                                }
                            }
                            //hay que mostrar un formulario para cada provincia que este dada de alta en estos impuestos
                            echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.id',array('type'=>'hidden','value'=>$eventoid));
                            echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.localidade_id', array('default'=>$localidad,'style'=>'width:80px','label'=>$eventoPos==0?'Localidad':''));
                            echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.impcli_id',array('value'=>$impcliid,'type'=>'hidden'));
                            echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.periodo',array('value'=>$periodo,'type'=>'hidden'));
                            echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.fchvto', array(
                                                              'class'=>'hiddendatepicker',
                                                              'type'=>'text',
                                                              'label'=>$eventoPos==0?'Fch. Vto.':'',
                                                              'readonly','readonly',
                                                              'style'=>'width:80px',
                                                              'value'=>date('d-m-Y',strtotime($fchvto)),
                                                              ));
                            echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.montovto',array('label'=>$eventoPos==0?'Monto a Pagar':'','default'=>$montovto, 'style'=>'width:113px'));
                            //los sindicatos no tienen monto a favor ni el impuesto Autonomo(id=14)
                            if($impuesto['organismo']!='sindicato'&&$impuesto['id']!=14/*Imp: Autonomo*/){
                                echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.monc',array('label'=>$eventoPos==0?'Monto a Favor':'','default'=>$montoc,'style'=>'width:113px'));
                            }
                            $eventoPos++;
                            echo "</br>";
                        }

                    break;
                    default:
                        echo $this->Form->input('Eventosimpuesto.0.id',array('type'=>'hidden'));
                        echo $this->Form->input('Eventosimpuesto.0.partido_id', array('style'=>'width:80px'));
                        echo $this->Form->input('Eventosimpuesto.0.impcli_id',array('value'=>$impcliid,'type'=>'hidden'));
                        echo $this->Form->input('Eventosimpuesto.0.periodo',array('value'=>$periodo,'type'=>'hidden'));
                        echo $this->Form->input('Eventosimpuesto.0.fchvto', array(
                                                          'class'=>'hiddendatepicker',
                                                          'type'=>'text',
                                                          'label'=>'Fch. Vto.',
                                                          'readonly','readonly',
                                                          'style'=>'width:80px',
                                                          'value'=>date('d-m-Y',strtotime($fchvto)),
                                                          ));
                        echo $this->Form->input('Eventosimpuesto.0.montovto',array('label'=>'Monto a Pagar','default'=>"0", 'style'=>'width:113px'));
                        //los sindicatos no tienen monto a favor ni el impuesto Autonomo(id=14)
                        if($impuesto['organismo']!='sindicato'&&$impuesto['id']!=14/*Imp: Autonomo*/){
                            echo $this->Form->input('Eventosimpuesto.0.monc',array('label'=>'Monto a Favor','default'=>"0",'style'=>'width:113px'));
                        }
                        echo $this->Form->input('Eventosimpuesto.0.descripcion',array('default'=>"-", 'style'=>'width:100px'));
                    break;
                    }

                }else{
                    echo $this->Form->input('Eventosimpuesto.0.id',array('type'=>'hidden'));
                    echo $this->Form->input('Eventosimpuesto.0.localidade_id', array('style'=>'width:80px') );
                    echo $this->Form->input('Eventosimpuesto.0.impcli_id',array('value'=>$impcliid,'type'=>'hidden'));
                    echo $this->Form->input('Eventosimpuesto.0.periodo',array('value'=>$periodo,'type'=>'hidden'));
                    echo $this->Form->input('Eventosimpuesto.0.fchvto', array(
                                                      'class'=>'hiddendatepicker',
                                                      'type'=>'hidden',
                                                      'label'=>'Fch. Vto.',
                                                      'readonly','readonly',
                                                      'style'=>'width:80px',
                                                      'value'=>date('d-m-Y',strtotime($fchvto)),
                                                      ));
                    echo $this->Form->input('Eventosimpuesto.0.montovto',array('label'=>'Montos a Pagar','default'=>"0", 'style'=>'width:113px'));
                    //los sindicatos no tienen monto a favor ni el impuesto Autonomo(id=14)
                    if($impuesto['organismo']!='sindicato'&&$impuesto['id']!=14/*Imp: Autonomo*/){
                        echo $this->Form->input('Eventosimpuesto.0.monc',array('label'=>'Monto a Favor','default'=>"0",'style'=>'width:113px'));
                    }
                    echo $this->Form->input('Eventosimpuesto.0.descripcion',array('default'=>"-", 'style'=>'width:100px'));
                  }
                break;
            case 'item':
                //PAGO POR ITEM
                //vamos a crear varios eventos con items distintos que siempre se tienen que mostrar
                $eventoPos = 0;
                $itemsACompletar = array();
                $daAFavor=true;
                switch ($impuesto['id']) {
                    case 10/*SUSS*/:
                    $itemsACompletar=$optionsSUSS;
                    $daAFavor=false;
                    break;
                    case 19/*IVA*/:
                    $itemsACompletar=$optionsIVA;
                    break;
                    case 4/*Monotributo*/:
                    $itemsACompletar=$optionsMono;
                    break;
                    default:
                    break;
                }
                foreach ($itemsACompletar as $keyOS => $valueOS){//vamos a recorrer items para asegurarnos de crear una row por item
                    $eventoid=0;
                    $fchvto=date('d-m-Y');
                    $montovto = 0;
                    $montoc = 0;
                    $descripcion = '';
                    foreach ($eventosimpuestos as $eventosimpuesto){//vamos a buscar el evento para ver si ya esta creada este item
                        if($eventosimpuesto['Eventosimpuesto']['item']==$keyOS){
                            $eventoid = $eventosimpuesto['Eventosimpuesto']['id'];
                            $fchvto = $eventosimpuesto['Eventosimpuesto']['fchvto'];
                            $montovto = $eventosimpuesto['Eventosimpuesto']['montovto'];
                            $descripcion = $eventosimpuesto['Eventosimpuesto']['descripcion'];
                            $montoc = $eventosimpuesto['Eventosimpuesto']['monc'];
                        }
                    }
                    echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.id',array('type'=>'hidden','value'=>$eventoid));
                    echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.periodo',array('type'=>'hidden','value'=>$periodo));
                    echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.impcli_id',array('value'=>$impcliid,'type'=>'hidden'));
                    echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.item', array(
                        'style'=>'width:160px',
                        'type'=>'select',
                        'options'=>$itemsACompletar,
                        'default'=>$keyOS,
                        'disabled'=>'disabled',
                        'title'=>$valueOS,
                        'class'=>'inputtodisable',
                        'label'=>$eventoPos==0?'Item':'')
                    );
                    echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.fchvto', array(	'class'=>'hiddendatepicker',
                                                                'type'=>'hidden',
                                                                'label'=>$eventoPos==0?'Fch. Vto.':'',
                                                                'readonly','readonly',
                                                                'style'=>'width:80px',
                                                                'value'=>date('d-m-Y',strtotime($fchvto)),
                                                              ));
                    echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.montovto',array('label'=>$eventoPos==0?'Monto a Pagar':'','default'=>"0", 'style'=>'width:113px','value'=>$montovto));
                    if($daAFavor) {
                        echo $this->Form->input('Eventosimpuesto.' . $eventoPos . '.monc', array('label' => $eventoPos==0?'Monto a Favor':'', 'default' => "0", 'style' => 'width:113px', 'value' => $montoc)) . "</br>";
                    }
                    $eventoPos++;
                    echo "</br>";
                }
            break;
        }
  	    echo '<a href="#" onclick="$('."'".'#EventosimpuestoRealizartarea5Form'."'".').submit();" class="btn_aceptar" style="margin-top:14px">'.$botonOK.'</a>';
	    echo '<a href="#close"  onclick="" class="btn_cancelar" style="margin-top:14px">Cancelar</a>';?>

  	    <!--<fieldset style="display:none"><?php echo  $this->Form->submit('Aceptar');?> </fieldset>-->
		
	    <?php
        if(isset($itemsACompletar)){
            echo $this->Form->input('Eventosimpuesto.0.cantItems', array('type'=>'hidden','value'=>count($itemsACompletar)));
            echo $this->Form->input('Eventosimpuesto.0.jsonItems', array('type'=>'hidden','value'=>json_encode($itemsACompletar)));
        }
        echo $this->Form->input('Eventosimpuesto.0.cantProvincias', array('type'=>'hidden','value'=>count($impcliprovincias)));
	    echo $this->Form->input('Eventosimpuesto.0.cantActividades', array('type'=>'hidden','value'=>count($cliente['Actividadcliente'])));
         if(
             $tipopago!='unico'&&
             $tipopago!='item'&&
             (
                ($tipopago=='provincia'&&count($impcliprovincias)==0)||
                ($tipopago=='municipio'&&count($impcliprovincias)==0)
             )
         )
         {
            ?>
            <table cellpadding="0" cellspacing="0" id="tablePapelesPreparados" class="tbl_papeles">
                <tr>
                    <td colspan="4s"><h3><?php echo __('Papeles preparados'); ?></h3></td>
                </tr>
                <tr>
                    <?php
                    switch ($tipopago) {
                        case 'provincia':
                            echo "<th>Provincia</th>";
                            break;
                        case 'municipio':
                            echo "<th>Municipio</th>";
                            break;
                        case 'item':
                            echo "<th>Item</th>";
                        break;
                    }?>
                    <th>Vencimiento</th>
                    <th>A Pagar</th>
                    <th>Pagado en</th>
                    <th>Pagado</th>
                    <th>A Favor</th>
                    <th>Descripcion</th>

                    <th class="actions"><?php echo __('Acciones'); ?></td>
                </tr>

                <?php foreach ($eventosimpuestos as $eventosimpuesto): ?>
                    <tr>
                        <?php
                        switch ($tipopago) {
                            case 'provincia':
                                ?><td><?php
                                    if(isset($eventosimpuesto['Partido']['nombre'])){
                                        echo h($eventosimpuesto['Partido']['nombre']); ?>&nbsp;</td><?php
                                    }
                                break;
                            case 'municipio':
                                ?><td><?php
                                if(isset($eventosimpuesto['Localidade']['nombre'])) {
                                    echo h($eventosimpuesto['Localidade']['nombre']); ?>&nbsp;</td><?php
                                }
                                break;
                            case 'item':
                                ?><td><?php
                                if(isset($eventosimpuesto['Eventosimpuesto']['item'])) {
                                    echo h($eventosimpuesto['Eventosimpuesto']['item']); ?>&nbsp;</td><?php
                                }
                            break;
                        }?>
                        <td><?php echo date('d-m-Y', strtotime(h($eventosimpuesto['Eventosimpuesto']['fchvto']))); ?>&nbsp;</td>
                        <td><?php echo h($eventosimpuesto['Eventosimpuesto']['montovto']); ?>&nbsp;</td>
                        <td><?php echo h($eventosimpuesto['Eventosimpuesto']['fchrealizado']); ?>&nbsp;</td>

                        <td><?php echo h($eventosimpuesto['Eventosimpuesto']['montorealizado']); ?>&nbsp;</td>
                        <td><?php echo h($eventosimpuesto['Eventosimpuesto']['monc']); ?>&nbsp;</td>

                        <td><?php echo h($eventosimpuesto['Eventosimpuesto']['descripcion']); ?>&nbsp;</td>

                        <td class="actions">
                            <?php
                                echo $this->Form->button('Eliminar', array(
                                    'type' => 'button',
                                    'onClick' => 'eliminarEventoImpuesto('.$eventosimpuesto['Eventosimpuesto']['id'].')',
                                    'escape' => false
                                ));
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php } ?>
	</div>
</div><?php /*Fin Preparar Papeles de Trabajo*/?>
<div id="divPagar"  class="tareapagos index">
	
</div>