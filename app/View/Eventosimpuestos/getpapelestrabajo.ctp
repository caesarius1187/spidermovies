<script type="text/javascript">
    $(document).ready(function() {
        $(".inputWithTwoDecimals").each(function () {
            $(this).change(setTwoNumberDecimal);
        });
        $(".inputMontovto").each(function () {
            $(this).change(addTolblTotalAPagar);
        });
        $(".inputMonc").each(function () {
            $(this).change(addTolblTotalAFavor);
        });
    });
    function setTwoNumberDecimal(event) {
        this.value = parseFloat(this.value).toFixed(2);
    }
    function addTolblTotalAPagar(event) {
//        $("#lblTotalAPagar").val(0) ;
        var montovtosubtotal = 0;
        $(".inputMontovto").each(function () {
            montovtosubtotal = montovtosubtotal*1 + this.value*1;
        });
        $("#lblTotalAPagar").text("$"+parseFloat(montovtosubtotal).toFixed(2)) ;
    }
    function addTolblTotalAFavor(event) {
//        $("#lblTotalAFavor").val(0) ;
        var moncsubtotal = 0;
        $(".inputMonc").each(function () {
            moncsubtotal = moncsubtotal*1 + this.value*1;
        });
        if($("#lblTotalAFavor").length>0) {
            $("#lblTotalAFavor").text("$"+parseFloat(moncsubtotal).toFixed(2));
        }
    }
</script>
<div class="" style="color: #000000">
    <div id="divHeaderEventoImpuesto" style="" class="index">
    <?php
    $labelClifch = $cliente['Cliente']['nombre'];
    ?>
    <b style="display: inline;">
        <label id="tipoorganismoyNombre" style="display: inline;">
        <?php echo __(strtoupper($cliente['Organismosxcliente'][0]['tipoorganismo'])."-".$impuesto['nombre']); ?>
        </label>
    </b> - <b>Periodo:</b> <?php echo $periodo; ?></br>
    <b style="display: inline;">
        Contribuyente:
    </b><label style="display: inline;" id="clinombre"><?php echo $labelClifch; ?></label>
     -
    <b style="display: inline;">
        CUIT:
    </b><label style="display: inline;" id="nroCuitContribuyente"><?php echo $cliente['Cliente']['cuitcontribullente']; ?></label>
    </br>
    <?php
    $labelCuitfch ="<b> Usuario: </b>".$cliente['Organismosxcliente'][0]['usuario'].' - <b>Clave:</b> '.$cliente['Organismosxcliente'][0]['clave'];
    ?>
    <label style="width:75%; font-size:14px;"><?php echo $labelCuitfch;?></label></br>
        <div style="width: 25%; float:right;">
            <?php
            //aca vamos a mostrar informacion sobre las ventas,compras y retenciones cargadas
            $mostrarAlertaVentasComprasConceptos = false;
            switch ($impuesto['id']) {
                case 4/*Monotributo*/:
                    $mostrarAlertaVentasComprasConceptos = true;
                    break;
                case 6/*Actividades Varias*/:
                    $mostrarAlertaVentasComprasConceptos = true;
                    break;
                case 10/*SUSS*/:
                    break;
                case 12/*Cooperadora Asistencial*/:
                    break;
                case 14/*Autonomo*/:
                    break;
                case 19/*IVA*/:
                    $mostrarAlertaVentasComprasConceptos = true;
                    break;
                case 21/*Actividades Economicas*/:
                case 174/*Convenio Multilateral*/:
                    $mostrarAlertaVentasComprasConceptos = true;
                    break;
                case 37/*Casas Particulares*/:
                    $mostrarAlertaVentasComprasConceptos = false;
                    break;
                default:
                    break;
            }
            ?>
        </div>
    </div>
<!--    <hr style="height: 1px;width:100%; border-style: double;margin-bottom: 2px;" >-->
    <div id="divPrepararPapelesDeTrabajo" class="tareapapeldetrabajo index" style="/*width:100%;*/">
        <div id="form_prepararPapeles" class="prepararPapeles"  style="float: left; width:100%">
            <?php
            $totalAPagar=0;
            $totalAFavor=0;
            $mostrarAFavor=true;
            $marginextra = 0;
            //Aca vamos a personalizar distintos formularios dependiendo de que impuesto sea el que necesitamos ejecutar, ya que si bien son parecidos algunos de ellos tienen particularidades
            ?>
            <div id="divVencimiento" style="margin-bottom: 20px;">
                <b>Impuesto determinado -
                <?php
                echo $this->Form->input('vencimientogeneral', array(
                                                      'class'=>'datepicker',
                                                      'type'=>'text',
                                                      'label'=>array(
                                                        'text'=>"Vencimiento:",
                                                        "style"=>"display:inline",
                                                        ),
                                                      'readonly','readonly',
                                                      'value'=>$fchvto,
                                                      'div' => false,
                                                      'style'=> 'width:100px;height:9px;display:inline'
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
                </b>
            </div>
            <?php
            echo $this->Form->create('Eventosimpuesto',array('class'=>'formTareaCarga','action'=>'realizartarea5'));
            echo $this->Form->input('Eventosimpuesto.0.haycambio',array('value'=> true ,'type'=>'hidden','id'=>'EventosimpuestoHaycambios'));
            echo $this->Form->input('Eventosimpuesto.0.cliente_id',array('value'=>$clienteid,'type'=>'hidden'));
            $botonOK="Guardar";
            $faltanEventosAMostrar = false;
            switch ($tipopago) {
                case 'unico':
                    $eventoid=0;
                    $fchvto=date('d-m-Y');
                    $montovto = 0;
                    $montoc = 0;
                    $descripcion = '';

                    foreach ($eventosimpuestos as $key => $eventosimpuesto){//vamos a buscar el evento para ver si ya esta creada este item
                        $botonOK="Modificar";
                        if(!isset( $eventosimpuestos[$key]['Eventosimpuesto']['mostrado'])){
                            $eventosimpuestos[$key]['Eventosimpuesto']['mostrado']=0;
                        }
                        $eventoid = $eventosimpuesto['Eventosimpuesto']['id'];
                        $fchvto = $eventosimpuesto['Eventosimpuesto']['fchvto'];
                        $montovto = $eventosimpuesto['Eventosimpuesto']['montovto'];
                        $descripcion = $eventosimpuesto['Eventosimpuesto']['descripcion'];
                        $montoc = $eventosimpuesto['Eventosimpuesto']['monc'];
                        $eventosimpuestos[$key]['Eventosimpuesto']['mostrado']=1;
                        $totalAPagar+=$montovto;
                        $totalAFavor+=$montoc;
                        $botonOK="Modificar";

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
                    echo $this->Form->input('Eventosimpuesto.0.montovto',[
                        'label'=>'Monto a Pagar',
                        'default'=>"0",
                        'style'=>'width:113px; margin-left: 29px;',
                        'value'=>$montovto,
                        'class'=>'inputWithTwoDecimals inputMontovto tdWithNumber',
                    ]);

                    //los sindicatos no tienen monto a favor ni el impuesto Autonomo(id=14)
                    if($impuesto['organismo']!='sindicato'&&$impuesto['id']!=14/*Imp: Autonomo*/){
                        echo $this->Form->input('Eventosimpuesto.0.monc',[
                            'label'=>'Monto a Favor',
                            'default'=>"0",
                            'style'=>'width:113px',
                            'class'=>'inputWithTwoDecimals inputMonc tdWithNumber',
                            'value'=>$montoc]
                        );
                    }else{
                        $mostrarAFavor=false;
                    }
                    echo $this->Form->input('Eventosimpuesto.0.descripcion',array('default'=>"-", 'style'=>'width:100px','value'=>$descripcion));

                    //vamos a cargar el saldo a favor del periodo actual
                    //La variable se llama $SaldosLibreDisponibilidadimpcli por que la creamos con el IVA pero son Saldos A Favor del periodo actual
                    if(isset($SaldosLibreDisponibilidadimpcli)){
                        foreach ($SaldosLibreDisponibilidadimpcli as $conceptosrestante){
                            echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.id',array(
                                'value'=>$conceptosrestante['id'],'type'=>'hidden'));
                            echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.cliente_id',array(
                                'value'=>$conceptosrestante['cliente_id'],'type'=>'hidden'));
                            echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.impcli_id',array(
                                'value'=>$conceptosrestante['impcli_id'],'type'=>'hidden'));
                            echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.conceptostipo_id',array(
                                'value'=>$conceptosrestante['conceptostipo_id'],'type'=>'hidden'));
                            echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.periodo',array(
                                'value'=>$conceptosrestante['periodo'],'type'=>'hidden'));
                            echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.montoretenido',array(
                                'value'=>$conceptosrestante['montoretenido'],'type'=>'hidden'));
                            echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.fecha',array(
                                'value'=>$conceptosrestante['fecha'],'type'=>'hidden'));
                            echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.descripcion',array(
                                'value'=>$conceptosrestante['descripcion'],'type'=>'hidden'));
                        }
                    }
                    if(isset($conceptosrestantesimpcli)){
                        foreach ($conceptosrestantesimpcli as $conceptosrestante){
                            echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.id',array(
                                'value'=>$conceptosrestante['id'],'type'=>'hidden'));
                            echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.cliente_id',array(
                                'value'=>$conceptosrestante['cliente_id'],'type'=>'hidden'));
                            echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.impcli_id',array(
                                'value'=>$conceptosrestante['impcli_id'],'type'=>'hidden'));
                            echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.conceptostipo_id',array(
                                'value'=>$conceptosrestante['conceptostipo_id'],'type'=>'hidden'));
                            echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.periodo',array(
                                'value'=>$conceptosrestante['periodo'],'type'=>'hidden'));
                            echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.montoretenido',array(
                                'value'=>$conceptosrestante['montoretenido'],'type'=>'hidden'));
                            echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.fecha',array(
                                'value'=>$conceptosrestante['fecha'],'type'=>'hidden'));
                            echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.descripcion',array(
                                'value'=>$conceptosrestante['descripcion'],'type'=>'hidden'));
                        }
                    }
                    echo "</br>";
                break;
                case 'provincia':
                    $marginextra = 155;
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
                                $repetido = 0;
                                foreach ($eventosimpuestos as $key => $eventosimpuesto){//vamos a buscar el evento para ver si ya esta creada estprovincia
                                    $botonOK="Modificar";
                                    if(!isset( $eventosimpuestos[$key]['Eventosimpuesto']['mostrado'])){
                                        $eventosimpuestos[$key]['Eventosimpuesto']['mostrado']=0;
                                    }
                                    if($eventosimpuesto['Eventosimpuesto']['partido_id']==$impcliprovincia['partido_id']){
                                        $repetido++;
                                        if($repetido>1){
                                            $eventosimpuestos[$key]['Eventosimpuesto']['mostrado']=0;
                                        }else{
                                            $eventosimpuestos[$key]['Eventosimpuesto']['mostrado']=1;
                                        }
                                        $eventoid = $eventosimpuesto['Eventosimpuesto']['id'];
                                        $fchvto = $eventosimpuesto['Eventosimpuesto']['fchvto'];
                                        $montovto = $eventosimpuesto['Eventosimpuesto']['montovto'];
                                        $descripcion = $eventosimpuesto['Eventosimpuesto']['descripcion'];
                                        $montoc = $eventosimpuesto['Eventosimpuesto']['monc'];

                                        if(isset($eventosimpuesto['Basesprorrateada'])){
                                            $mybaseprorrateada = $eventosimpuesto['Basesprorrateada'];
                                        }
                                        $totalAPagar+=$montovto;
                                        $totalAFavor+=$montoc;
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
                                echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.montovto',[
                                    'label'=>$eventoPos==0?'Monto a Pagar':'',
                                    'default'=>$montovto,
                                    'style'=>'width:113px; margin-left: 29px;',
                                    'class'=>'inputWithTwoDecimals inputMontovto tdWithNumber',
                                ]);
                                //los sindicatos no tienen monto a favor ni el impuesto Autonomo(id=14)
                                if($impuesto['organismo']!='sindicato'&&$impuesto['id']!=14/*Imp: Autonomo*/){
                                    echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.monc',[
                                        'label'=>$eventoPos==0?'Monto a Favor':'',
                                        'default'=>$montoc,
                                        'style'=>'width:113px',
                                        'class'=>'inputWithTwoDecimals inputMonc tdWithNumber',
                                        ]
                                    );
                                }else{

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
                            echo $this->Form->input('Eventosimpuesto.0.montovto',[
                                'label'=>'Monto a Pagar',
                                'default'=>"0",
                                'style'=>'width:113px; margin-left: 29px;',
                                'class'=>'inputWithTwoDecimals inputMontovto tdWithNumber',
                            ]);
                            //los sindicatos no tienen monto a favor ni el impuesto Autonomo(id=14)
                            if($impuesto['organismo']!='sindicato'&&$impuesto['id']!=14/*Imp: Autonomo*/){
                                echo $this->Form->input('Eventosimpuesto.0.monc',[
                                    'label'=>'Monto a Favor',
                                    'default'=>"0",
                                    'style'=>'width:113px',
                                    'class'=>'inputWithTwoDecimals inputMonc tdWithNumber',
                                    ]
                                );
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
                        echo $this->Form->input('Eventosimpuesto.0.montovto',[
                            'label'=>'Monto a Pagar',
                            'default'=>"0",
                            'style'=>'width:113px; margin-left: 29px;',
                            'class'=>'inputWithTwoDecimals inputMontovto tdWithNumber',
                        ]);
                        //los sindicatos no tienen monto a favor ni el impuesto Autonomo(id=14)
                        if($impuesto['organismo']!='sindicato'&&$impuesto['id']!=14/*Imp: Autonomo*/){
                            echo $this->Form->input('Eventosimpuesto.0.monc',[
                                'label'=>'Monto a Favor',
                                'default'=>"0",
                                'style'=>'width:113px',
                                'class'=>'inputWithTwoDecimals inputMonc tdWithNumber',
                                ]
                            );
                        }
                        echo $this->Form->input('Eventosimpuesto.0.descripcion',array('default'=>"-", 'style'=>'width:100px'));
                    }
                    break;
                case 'municipio':
                    $marginextra = 155;
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
                                foreach ($eventosimpuestos as $key => $eventosimpuesto){
                                    $botonOK="Modificar";
                                    //vamos a buscar el evento para ver si ya esta creada estprovincia
                                    if(!isset( $eventosimpuestos[$key]['Eventosimpuesto']['mostrado'])){
                                        $eventosimpuestos[$key]['Eventosimpuesto']['mostrado']=0;
                                    }
                                    if($eventosimpuesto['Eventosimpuesto']['localidade_id']==$impcliprovincia['localidade_id']){
                                        $eventoid = $eventosimpuesto['Eventosimpuesto']['id'];
                                        $fchvto = $eventosimpuesto['Eventosimpuesto']['fchvto'];
                                        $montovto = $eventosimpuesto['Eventosimpuesto']['montovto'];
                                        $descripcion = $eventosimpuesto['Eventosimpuesto']['descripcion'];
                                        $montoc = $eventosimpuesto['Eventosimpuesto']['monc'];
                                        $eventosimpuestos[$key]['Eventosimpuesto']['mostrado']=1;
                                        $totalAPagar+=$montovto;
                                        $totalAFavor+=$montoc;
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
                                echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.montovto',[
                                    'label'=>$eventoPos==0?'Monto a Pagar':'',
                                    'default'=>$montovto,
                                    'style'=>'width:113px; margin-left: 29px;',
                                    'class'=>'inputWithTwoDecimals inputMontovto tdWithNumber',
                                ]);
                                //los sindicatos no tienen monto a favor ni el impuesto Autonomo(id=14)
                                if($impuesto['organismo']!='sindicato'&&$impuesto['id']!=14/*Imp: Autonomo*/){
                                    echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.monc',[
                                        'label'=>$eventoPos==0?'Monto a Favor':'',
                                        'default'=>$montoc,
                                        'style'=>'width:113px',
                                        'class'=>'inputWithTwoDecimals inputMonc tdWithNumber',
                                        ]
                                    );
                                }
                                //ahora vamos a crear los campos para registrar los Conceptos restantes que estan relacionados a este impcli y a este periodo
                                if(isset($conceptosrestantesimpcli)){
                                    foreach ($conceptosrestantesimpcli as $conceptosrestante){
                                        if($conceptosrestante['localidade_id']==$impcliprovincia['localidade_id']){
                                            echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Conceptosrestante.'.$eventoPos.'.id',array(
                                                'value'=>$conceptosrestante['id'],'type'=>''));
                                            echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Conceptosrestante.'.$eventoPos.'.loalidade_id',array(
                                                'value'=>$conceptosrestante['partido_id'],'type'=>''));
                                            echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Conceptosrestante.'.$eventoPos.'.cliente_id',array(
                                                'value'=>$conceptosrestante['cliente_id'],'type'=>''));
                                            echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Conceptosrestante.'.$eventoPos.'.impcli_id',array(
                                                'value'=>$conceptosrestante['impcli_id'],'type'=>''));
                                            echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Conceptosrestante.'.$eventoPos.'.conceptostipo_id',array(
                                                'value'=>$conceptosrestante['conceptostipo_id'],'type'=>''));
                                            echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Conceptosrestante.'.$eventoPos.'.periodo',array(
                                                'value'=>$conceptosrestante['periodo'],'type'=>''));
                                            echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Conceptosrestante.'.$eventoPos.'.montoretenido',array(
                                                'value'=>$conceptosrestante['montoretenido'],'type'=>''));
                                            echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Conceptosrestante.'.$eventoPos.'.fecha',array(
                                                'value'=>$conceptosrestante['fecha'],'type'=>''));
                                            echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.Conceptosrestante.'.$eventoPos.'.descripcion',array(
                                                'value'=>$conceptosrestante['descripcion'],'type'=>''));
                                        }
                                    }
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
                            echo $this->Form->input('Eventosimpuesto.0.montovto',[
                                'label'=>'Monto a Pagar',
                                'default'=>"0",
                                'style'=>'width:113px; margin-left: 29px;',
                                'class'=>'inputWithTwoDecimals inputMontovto tdWithNumber',
                            ]);
                            //los sindicatos no tienen monto a favor ni el impuesto Autonomo(id=14)
                            if($impuesto['organismo']!='sindicato'&&$impuesto['id']!=14/*Imp: Autonomo*/){
                                echo $this->Form->input('Eventosimpuesto.0.monc',array(
                                    'label'=>'Monto a Favor',
                                    'default'=>"0",
                                    'style'=>'width:113px'));
                            }else{
                                $mostrarAFavor=false;
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
                        echo $this->Form->input('Eventosimpuesto.0.montovto',[
                            'label'=>'Montos a Pagar',
                            'default'=>"0",
                            'style'=>'width:113px',
                            'class'=>'inputWithTwoDecimals inputMontovto tdWithNumber',
                        ]);
                        //los sindicatos no tienen monto a favor ni el impuesto Autonomo(id=14)
                        if($impuesto['organismo']!='sindicato'&&$impuesto['id']!=14/*Imp: Autonomo*/){
                            echo $this->Form->input('Eventosimpuesto.0.monc',[
                                'label'=>'Monto a Favor',
                                'default'=>"0",
                                'style'=>'width:113px',
                                'class'=>'inputWithTwoDecimals inputMonc tdWithNumber',
                            ]);
                        }else{
                            $mostrarAFavor=false;
                        }
                        echo $this->Form->input('Eventosimpuesto.0.descripcion',array('default'=>"-", 'style'=>'width:100px'));
                      }
                    break;
                case 'item':
                    $marginextra = 155;
                    //PAGO POR ITEM
                    //vamos a crear varios eventos con items distintos que siempre se tienen que mostrar
                    $eventoPos = 0;
                    $itemsACompletar = array();
                    $daAFavor=true;
                    switch ($impuesto['id']) {
                        case 10/*SUSS*/:
                        $itemsACompletar=$optionsSUSS;
                        $daAFavor=false;
                        $mostrarAFavor=false;
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
                        $eventosimpuestoUsosaldo = array();
                        foreach ($eventosimpuestos as $key => $eventosimpuesto){//vamos a buscar el evento para ver si ya esta creada este item
                            $botonOK="Modificar";
                            if(!isset( $eventosimpuestos[$key]['Eventosimpuesto']['mostrado'])){
                                $eventosimpuestos[$key]['Eventosimpuesto']['mostrado']=0;
                            }
                            if($eventosimpuesto['Eventosimpuesto']['item']==$keyOS){
                                $eventoid = $eventosimpuesto['Eventosimpuesto']['id'];
                                $fchvto = $eventosimpuesto['Eventosimpuesto']['fchvto'];
                                $montovto = $eventosimpuesto['Eventosimpuesto']['montovto'];
                                $descripcion = $eventosimpuesto['Eventosimpuesto']['descripcion'];
                                $montoc = $eventosimpuesto['Eventosimpuesto']['monc'];
                                $eventosimpuestos[$key]['Eventosimpuesto']['mostrado']=1;
                                $eventosimpuestoUsosaldo = $eventosimpuestos[$key]['Usosaldo'];
                                $totalAPagar+=$montovto;
                                $totalAFavor+=$montoc;
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
    //                        'disabled'=>'disabled',
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
                        echo $this->Form->input('Eventosimpuesto.'.$eventoPos.'.montovto',[
                            'label'=>$eventoPos==0?'Monto a Pagar':'',
                            'default'=>"0",
                            'style'=>'width:113px; margin-left: 29px;',
                            'value'=>$montovto,
                            'class'=>'inputWithTwoDecimals inputMontovto tdWithNumber',
                        ]);
                        if($daAFavor) {
                            echo $this->Form->input('Eventosimpuesto.' . $eventoPos . '.monc', [
                                'label' => $eventoPos==0?'Monto a Favor':'',
                                'default' => "0",
                                'style' => 'width:113px',
                                'value' => $montoc,
                                'class'=>'inputWithTwoDecimals inputMonc tdWithNumber',
                                ]) . "</br>";
                        }

                        $eventoPos++;
                        echo "</br>";
                    }
                    //ahora vamos a crear los campos para registrar los Saldos A Favor de este periodo en este caso vamos
                    //a agregar solo para el IVA un Saldo de Libre Disponibilidad
                    if($impuesto['id']=19/*IVA*/){
    //                    Debugger::dump($SaldosLibreDisponibilidadimpcli);
                        if(isset($SaldosLibreDisponibilidadimpcli)){
                            foreach ($SaldosLibreDisponibilidadimpcli as $conceptosrestante){
                                if($conceptosrestante['conceptostipo_id']==1){
                                    echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.id',array(
                                        'value'=>$conceptosrestante['id'],'type'=>'hidden'));
                                    echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.partido_id',array(
                                        'value'=>$conceptosrestante['partido_id'],'type'=>'hidden'));
                                    echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.cliente_id',array(
                                        'value'=>$conceptosrestante['cliente_id'],'type'=>'hidden'));
                                    echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.impcli_id',array(
                                        'value'=>$conceptosrestante['impcli_id'],'type'=>'hidden'));
                                    echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.conceptostipo_id',array(
                                        'value'=>$conceptosrestante['conceptostipo_id'],'type'=>'hidden'));
                                    echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.periodo',array(
                                        'value'=>$conceptosrestante['periodo'],'type'=>'hidden'));
                                    echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.montoretenido',array(
                                        'value'=>$conceptosrestante['montoretenido'],
                                        'type'=>'text',
                                        'div'=>[
                                            'style'=>'width:260px',
                                        ],
                                        'style'=>'display:inline',
                                        'label'=>[
                                            'text'=>'Saldo de Libre Disponibilidad: ',
                                            'style'=>'display:inline',
                                            ]
                                        )
                                    );
                                    echo $this->HTML->image('ii.png',array('style'=>'width:15px;height:15px','title'=>"Este campo se ha guardado como un Pago a Cuenta del tipo Saldo de Libre Disponibilidaden el periodo ".$periodo));
                                    echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.fecha',array(
                                        'value'=>$conceptosrestante['fecha'],'type'=>'hidden'));
                                    echo $this->Form->input('Eventosimpuesto.0.Conceptosrestante.0.descripcion',array(
                                        'value'=>$conceptosrestante['descripcion'],'type'=>'hidden'))."</br>";

                                }
                            }
                        }
                        if(count($eventosimpuestoUsosaldo>0)){
                            foreach ($eventosimpuestoUsosaldo as $usosaldo){
                                echo $this->Form->input('Eventosimpuesto.0.Usosaldo.0.id',array(
                                    'value'=>$usosaldo['id'],'type'=>'hidden'));
                                echo $this->Form->input('Eventosimpuesto.0.Usosaldo.0.eventosimpuesto_id',array(
                                    'value'=>$usosaldo['eventosimpuesto_id'],'type'=>'hidden'));
                                echo $this->Form->input('Eventosimpuesto.0.Usosaldo.0.conceptosrestante_id',array(
                                    'value'=>$usosaldo['conceptosrestante_id'],'type'=>'hidden'));
                                echo $this->Form->input('Eventosimpuesto.0.Usosaldo.0.importe',array(
                                    'value'=>$usosaldo['importe'],
                                    'type'=>'text',
                                    'div'=>[
                                        'style'=>'width:260px',
                                    ],
                                    'style'=>'display:inline',
                                    'label'=>[
                                        'text'=>'Uso Saldo Libre Disponibilidad:',
                                        'style'=>'display:inline',
                                    ]
                                    )
                                );
                                    $mensajeAlertaUsoSaldo = 'Este es el importe del uso ya descontado del Saldo de libre 
                                    disponibilidad del periodo anterior para pagar este impuesto';
                                echo $this->HTML->image('ii.png',array('style'=>'width:15px;height:15px','title'=>$mensajeAlertaUsoSaldo));
                                echo $this->Form->input('Eventosimpuesto.0.Usosaldo.0.fecha',array(
                                    'value'=>$usosaldo['fecha'],'type'=>'hidden'))."</br>";
                            }
                        }
                    }
                break;
            }
            foreach ($eventosimpuestos as $key => $eventosimpuesto) {//vamos a buscar el evento para ver si ya esta creada este item
                if(!isset($eventosimpuesto['Eventosimpuesto']['mostrado'])){
                    $eventosimpuestos[$key]['Eventosimpuesto']['mostrado']=0;
                    $eventosimpuesto['Eventosimpuesto']['mostrado']=0;
                }
                if(!$eventosimpuesto['Eventosimpuesto']['mostrado']){
                    $faltanEventosAMostrar = true;
                    break;
                }else{

                }
            }
            ?>
            <div id="totalAPagar" style="width:<?php echo 145+$marginextra?>px">
             <?php
             echo $this->Form->label('lblTotalTotal',
                 'Total',
                 [
                     'id'=>'lblTotalTotal',
                     'style'=>"float:left;display: inline;"
                 ]
             );
             echo $this->Form->label('lblTotalAPagar',
                 '$'.number_format($totalAPagar, 2, ",", "."),
                 [
                     'id'=>'lblTotalAPagar',
                     'style'=>"float:right;display: inline;"
                 ]
             );
             ?>
            </div>
            <?php
            if($mostrarAFavor){
            ?>
            <div id="totalAFavor" style="width:113px">
            <?php
                echo $this->Form->label('lblTotalAFavor',
                    "$".number_format($totalAFavor, 2, ",", "."),
                    [
                        'id'=>'lblTotalAFavor',
                        'style'=>"display: inline;float:right;"
                    ]
                );
            ?>
            </div>
            <?php
            }
//            echo '<hr style="height: 1px;width:100%; border-style: double;margin-bottom: 2px;" >';
            $this->Form->end();
            echo '<div style="width:100%;" id="divAsientoDeEventoImpuesto"></div>';
            echo '<div style="width:100%; float:right;">
                    <a href="#close"  onclick="" class="btn_cancelar" style="margin-top:14px">Cancelar</a>
                    <a href="#" onclick="$('."'".'#EventosimpuestoRealizartarea5Form'."'".').submit();" class="btn_aceptar" style="margin-top:14px">'.$botonOK.'</a>
                    </div>';
            if(isset($itemsACompletar)){
                echo $this->Form->input('Eventosimpuesto.0.cantItems', array('type'=>'hidden','value'=>count($itemsACompletar)));
                echo $this->Form->input('Eventosimpuesto.0.jsonItems', array('type'=>'hidden','value'=>json_encode($itemsACompletar)));
            }
            echo $this->Form->input('Eventosimpuesto.0.cantProvincias', array('type'=>'hidden','value'=>count($impcliprovincias)));
            echo $this->Form->input('Eventosimpuesto.0.cantActividades', array('type'=>'hidden','value'=>count($cliente['Actividadcliente'])));
            $NoTienePagoDefinido =   ($tipopago!='unico')&&( $tipopago!='item');
            $NoTieneCargadaProvincia = ($tipopago=='provincia'&&count($impcliprovincias)==0)||($tipopago=='municipio'&&count($impcliprovincias)==0);
            if($NoTienePagoDefinido&&$NoTieneCargadaProvincia||$faltanEventosAMostrar)
            {   ?>
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

                    <?php foreach ($eventosimpuestos as $key => $eventosimpuesto):
                        if(!isset($eventosimpuesto['Eventosimpuesto']['mostrado'])){
                            $eventosimpuestos[$key]['Eventosimpuesto']['mostrado']=0;
                            $eventosimpuesto['Eventosimpuesto']['mostrado']=0;
                        }
                        if(!$eventosimpuesto['Eventosimpuesto']['mostrado']){
                        ?>
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
                    <?php
                        }
                    endforeach; ?>
                </table>
            <?php }
            ?>
        </div>
    </div><?php /*Fin Preparar Papeles de Trabajo*/?>
</div>
