<?php echo $this->Html->script('jquery-ui',array('inline'=>false));?>
<?php echo $this->Html->script('impclis/papeldetrabajocasasparticulares',array('inline'=>false));?>
<div class="index" style="padding: 0px 1%; margin-bottom: 10px;" id="headerCliente">
    <div style="width:30%; float: left;padding-top:10px">
        Cliente: <?php echo $cliente["Cliente"]['nombre'];
        echo $this->Form->input('clientenombre',['type'=>'hidden','value'=>$cliente["Cliente"]['nombre']]);?>
    </div>
    <div style="width:25%; float: left;padding-top:10px">
        Periodo: <?php echo $periodo;
        echo $this->Form->input('periodoPDT',['type'=>'hidden','value'=>$periodo]);
        echo $this->Form->input('impcliidPDT',['type'=>'hidden','value'=>$cliente["Impcli"][0]['id']])?>
    </div>
    <div style="float:right; width:45%">
        <?php echo $this->Form->button('Finalizar',
            array('type' => 'button',
                'class' =>"btn_realizar_tarea",
                'div' => false,
                'style' => array('style' => 'float:right'),
                'onClick' => "realizarEventoCliente('".$periodo."',".$cliente["Cliente"]['id'].",'realizado')"
            )
        );?>
    </div>
</div>

<div id="headerCarga" style="width:100%">

    <!--</div>-->
    <?php /**************************************************************************/ ?>
    <?php /*****************************TABS*****************************************/ ?>
    <?php /**************************************************************************/ ?>
    <div id="bodyCarga" style="width:100%;height:35px;">
        <div class="" style="width:100%;height:30px; margin-left:10px " id="divAllTabs">
            <div class="cliente_view_tab" style="width:18.5%;margin-right:0px"  onClick="" id="tabNovedades">
                <?php
                echo $this->Form->label(null, $text = 'Empleados',array('style'=>'text-align:center;margin-top:5px;cursor:pointer'));
                ?>
            </div>
        </div>
        <?php /**************************************************************************/ ?>
        <?php /*****************************Novedades************************************/ ?>
        <?php /**************************************************************************/ ?>
        <div id="form_empleados" class="tabNovedades " style="width:96%;float:left;">
            <?php
            $arrayEmpleados=[];
            $arrayConvenios=[];
            $arrayButtonsConvenios=[];
            $liquidaPrimeraQuincena = false;
            $liquidaSegundaQuincena = false;
            $liquidaMensual= false;
            $optionsLiquidacion = [];
            foreach ($cliente['Empleado'] as $empleadolista) {
                if($empleadolista['liquidaprimeraquincena']){
                    $liquidaPrimeraQuincena=true;
                    $optionsLiquidacion['liquidaprimeraquincena']='liquida primera quincena';
                }
                if($empleadolista['liquidasegundaquincena']){
                    $liquidaSegundaQuincena=true;
                    $optionsLiquidacion['liquidasegundaquincena']='liquida segunda quincena';
                }
                if($empleadolista['liquidamensual']){
                    $liquidaMensual=true;
                    $optionsLiquidacion['liquidamensual']='liquida mensual';
                }
                if($empleadolista['liquidasac']){
                    $liquidaSAC=true;
                    $optionsLiquidacion['liquidasac']='liquida sac';
                }
                $classButtonEmpleado = "btn_empleados";
                //vamos a agregar a este array solo los empleadosque tengan este convenio
                if(!isset($arrayEmpleados[$empleadolista['conveniocolectivotrabajo_id']])){
                    $arrayEmpleados[$empleadolista['conveniocolectivotrabajo_id']]=[];
                }
                $arrayConvenios[$empleadolista['conveniocolectivotrabajo_id']]=$this->Form->button(
                    $empleadolista['Conveniocolectivotrabajo']['nombre'],
                    array(
                        'class'=>'btn_empleados',
                        'onClick'=>"cargarTodosLosSueldos(".$empleadolista['conveniocolectivotrabajo_id'].");",
                        'id'=>'buttonConvenio'.$empleadolista['conveniocolectivotrabajo_id'],
                    ),
                    array()
                );
                $arrayEmpleados[$empleadolista['conveniocolectivotrabajo_id']][]=$empleadolista['id'];
                if(count($empleadolista['Valorrecibo'])>0){
                    $classButtonEmpleado = "btn_empleados_liq";
                }

            }
            ?>
        </div>
        <div id="form_FuncionImprimir" class="tabNovedades index" style="width:96%;float:left;">
            <?php
            echo $this->Form->input('tipoliquidacion',[
                'type'=>'select',
                'options'=>$optionsLiquidacion
            ]);
            echo $this->Form->input('arrayEmpleados',[
                'type'=>'hidden',
                'value'=>json_encode($arrayEmpleados)
            ]);
            echo $this->Form->button(
                "Formularios 102",
                [
                    'class'=>'btn_sueldo',
                    'style'=>'width:inherit;min-width: 141px;',
                    'onClick'=>"cargarTodosLosFormularios102()",
                    'id'=>'buttonImprimirRecibos',
                ],[]
            );
            ?>
        </div>
        <div id="divLiquidarCasasParticulares">

        </div>
        <div style="overflow:auto;width:96%; min-height: 400px; margin-top: 10px" class="tareaCargarIndexTable tabNovedades index">
            <div id="divSueldoForm"  style="width: 623px;">
            </div>
        </div>
        <?php
        if($tieneMonotributo=='true'){ ?>
        <div id="divContenedorContabilidad" style="margin-top:10px">  </div>
        <?php
        }else{ ?>
            <div id="divContenedorContabilidad" style="margin-top:10px">
                <div class="index">
                    <?php
                    $Asientoid=0;
                    $movId=[];
                    if(isset($impcli['Asiento'])){
                        if(count($impcli['Asiento'])>0) {
                            $Asientoid = $impcli['Asiento'][0]['id'];
                            foreach ($impcli['Asiento'][0]['Movimiento'] as $mimovimiento) {
                                $movId[$mimovimiento['Cuentascliente']['cuenta_id']] = $mimovimiento['id'];
                            }
                        }
                    }
                    //ahora vamos a reccorer las cuentas relacionadas al IVA y las vamos a cargar en un formulario de Asiento nuevo
                    echo $this->Form->create('Asiento',['class'=>'formTareaCarga formAsiento','controller'=>'asientos','action'=>'add']);
                    echo $this->Form->input('Asiento.0.id',['value'=>$Asientoid]);
                    $d = new DateTime( '01-'.$periodo );

                    echo $this->Form->input('Asiento.0.fecha',array(
                        'class'=>'datepicker',
                        'type'=>'text',
                        'label'=>array(
                            'text'=>"Fecha:",
                        ),
                        'readonly','readonly',
                        'value'=>$d->format( 't-m-Y' ),
                        'div' => false,
                        'style'=> 'height:9px;display:inline'
                    ));
                    echo $this->Form->input('Asiento.0.nombre',['readonly'=>'readonly','value'=>"Devengamiento Act. Varias" ,'style'=>'width:250px']);
                    echo $this->Form->input('Asiento.0.descripcion',['readonly'=>'readonly','value'=>"Automatico",'style'=>'width:250px']);
                    echo $this->Form->input('Asiento.0.cliente_id',['value'=>$cliente['Cliente']['id'],'type'=>'hidden']);
                    echo $this->Form->input('Asiento.0.impcli_id',['value'=>$impcli['Impcli']['id'],'type'=>'hidden']);
                    echo $this->Form->input('Asiento.0.periodo',['value'=>$periodo,'type'=>'hidden']);
                    echo $this->Form->input('Asiento.0.tipoasiento',['value'=>'impuestos','type'=>'hidden'])."</br>";
                    $i=0;
                    $totalDebe=0;
                    $totalHaber=0;
                    foreach ($impcli['Impuesto']['Asientoestandare'] as $asientoestandaractvs) {
                        if(!isset($movId[$asientoestandaractvs['cuenta_id']])){
                            $movId[$asientoestandaractvs['cuenta_id']]=0;
                        }
                        $cuentaclienteid=0;
                        $cuentaclientenombre=$asientoestandaractvs['Cuenta']['nombre'];
                        foreach ($cliente['Cuentascliente'] as $cuentaclientaIVA){
                            if($cuentaclientaIVA['cuenta_id']==$asientoestandaractvs['cuenta_id']){
                                $cuentaclienteid=$cuentaclientaIVA['id'];
                                $cuentaclientenombre=$cuentaclientaIVA['nombre'];
                                break;
                            }
                        }
                        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.id',['value'=>$movId[$asientoestandaractvs['cuenta_id']],]);
                        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.fecha', array(
                            'readonly'=>'readonly',
                            'class'=>'datepicker',
                            'type'=>'hidden',
                            'label'=>array(
                                'text'=>"Vencimiento:",
                                "style"=>"display:inline",
                            ),
                            'readonly','readonly',
                            'value'=>date('d-m-Y'),
                            'div' => false,
                            'style'=> 'height:9px;display:inline'
                        ));

                        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuentascliente_id',['readonly'=>'readonly','type'=>'hidden','value'=>$cuentaclienteid]);
                        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuenta_id',['readonly'=>'readonly','type'=>'hidden','orden'=>$i,'value'=>$asientoestandaractvs['cuenta_id'],'id'=>'cuenta'.$asientoestandaractvs['cuenta_id']]);
                        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.numero',['label'=>($i!=0)?false:'Numero','readonly'=>'readonly','value'=>$asientoestandaractvs['Cuenta']['numero'],'style'=>'width:82px']);
                        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.nombre',['label'=>($i!=0)?false:'Nombre','readonly'=>'readonly','value'=>$cuentaclientenombre,'type'=>'text','style'=>'width:250px']);
                        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe',[
                            'label'=>($i!=0)?false:'Debe',
                            'value'=>0,
                            'class'=>"inputDebe "
                        ]);
                        echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber',[
                                'label'=>($i!=0)?false:'Haber',
                                'value'=>0,
                                'class'=>"inputHaber "
                            ])."</br>";
                        $i++;
                    }

                    echo $this->Form->submit('Contabilizar',['style'=>'display:none']);
                    echo $this->Form->end();
                    $totalDebe=0;
                    $totalHaber=0;
                    echo $this->Form->label('','&nbsp; ',[
                        'style'=>"display: -webkit-inline-box;width:355px;"
                    ]);
                    echo $this->Form->label('lblTotalDebe',
                        "$".number_format($totalDebe, 2, ".", ""),
                        [
                            'id'=>'lblTotalDebe',
                            'style'=>"display: inline;"
                        ]
                    );
                    echo $this->Form->label('','&nbsp;',['style'=>"display: -webkit-inline-box;width:100px;"]);
                    echo $this->Form->label('lblTotalHaber',
                        "$".number_format($totalHaber, 2, ".", ""),
                        [
                            'id'=>'lblTotalHaber',
                            'style'=>"display: inline;"
                        ]
                    );
                    if(number_format($totalDebe, 2, ".", "")==number_format($totalHaber, 2, ".", "")){
                        echo $this->Html->image('test-pass-icon.png',array(
                                'id' => 'iconDebeHaber',
                                'alt' => 'open',
                                'class' => 'btn_exit',
                                'title' => 'Debe igual al Haber diferencia: '.number_format(($totalDebe-$totalHaber), 2, ".", ""),
                            )
                        );
                    }else{
                        echo $this->Html->image('test-fail-icon.png',array(
                                'id' => 'iconDebeHaber',
                                'alt' => 'open',
                                'class' => 'btn_exit',
                                'title' => 'Debe distinto al Haber diferencia: '.number_format(($totalDebe-$totalHaber), 2, ".", ""),
                            )
                        );
                    }
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>