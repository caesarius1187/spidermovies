<?php
echo $this->Html->css('bootstrapmodal');
echo $this->Html->script('jquery-ui',array('inline'=>false));
echo $this->Html->script('estudios/view',array('inline'=>false));
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
echo $this->Html->script('Chart',array('inline'=>false));

?>

<script type="text/javascript">
    $(document).ready(function() {
        $( "input.datepicker" ).datepicker({
                yearRange: "-100:+50",
                changeMonth: true,
                changeYear: true,
                constrainInput: false,
                dateFormat: 'dd-mm-yy',
        });
        var ventasDiarias = JSON.parse($("#ventasDiarias").val());
        var labelsv = [];
        var valuesv = [];
        $.each(ventasDiarias, function(index, value) {
                labelsv.push(index);
                valuesv.push(value);
            });
        var ctxbar = $("#myChartVentas");
        /**/
        var comprasDiarias = JSON.parse($("#comprasDiarias").val());
        var labelsc = [];
        var valuesc = [];
        $.each(comprasDiarias, function(index, value) {
                labelsc.push(index);
                valuesc.push(value);
            });
        /**/
        /**/
        var sueldosDiarias = JSON.parse($("#sueldosDiarias").val());
        var labelss = [];
        var valuess = [];
        $.each(sueldosDiarias, function(index, value) {
                labelss.push(index);
                valuess.push(value);
            });
        /**/
        /**/
        var impuestosDiarias = JSON.parse($("#impuestosDiarias").val());
        var labelsi = [];
        var valuesi = [];
        $.each(impuestosDiarias, function(index, value) {
                labelsi.push(index);
                valuesi.push(value);
            });
        /**/
        var myVentasChartbar = new Chart(ctxbar, {
            type: 'bar',
            data: {
                labels: labelsv,
                datasets: [{
                    label: 'Ventas',
                    data: valuesv,
                    borderWidth: 1,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)'     
                },
                {
                    label: 'Compras',
                    data: valuesc,
                    borderWidth: 1,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)'   
                },
                {
                    label: 'Sueldos',
                    data: valuess,
                    borderWidth: 1,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)'   
                },
                {
                    label: 'Impuestos',
                    data: valuesi,
                    borderWidth: 1,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)'   
                }],
            },
            options: {
                title: {
                    display: true,
                    text: 'Carga de Ventas/dia;Compras/dia - Liquidacion de Sueldos/dia;Impuestos/dia'
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });                  
    });
       

</script>
<div class="estudios index" style="margin-bottom:5px;width: 43%;float:left">
    <div class="fab blue" style="float: right;">
        <core-icon icon="add" align="center" style="margin: 8px 14px;position: absolute;">
                <?php echo $this->Form->button(
                        $this->Html->image(
                                'edit_view.png',
                                array(
                                        'alt' => 'edit',
                                        'class'=>'img_edit',
                                )
                        ),
                        array('type' => 'button',
                                'style' =>"padding:0px;",
                                'class' =>"btn_add",
                                'escape' =>false,
                                'onClick' => "$('#myModal').modal('show');"
                                        )
                        );
                ?>
        </core-icon>
        <paper-ripple class="circle recenteringTouch" fit></paper-ripple>
    </div>
    <h2><?php echo __('Mi Cuenta'); ?></h2>
    <dl style="width: 100%">
        <dt><?php echo __('Nombre'); ?></dt>
        <dd>
            <?php echo h($estudio['Estudio']['nombre']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Propietario'); ?></dt>
        <dd>
            <?php echo h($estudio['Estudio']['propietario']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Direccion'); ?></dt>
        <dd>
            <?php echo h($estudio['Estudio']['direccion']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('CUIT'); ?></dt>
        <dd>
            <?php echo h($estudio['Estudio']['cuit']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Ingresos Brutos'); ?></dt>
        <dd>
            <?php echo h($estudio['Estudio']['cuit']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Inicio de actividades'); ?></dt>
        <dd>
            <?php echo h($estudio['Estudio']['inicioactividades']); ?>
            &nbsp;
        </dd>
    </dl>
    
    <?php
    $ventasDiarias = [];
    $dia = 01;
    $ultimoDia = date('t', strtotime('01-'.$periodomes.'-'.$periodoanio));
    $totalVenta = 0;
    $totalCompra = 0;
    $totalSueldos = 0;
    $totalImpuestos = 0;
    while ($dia<=$ultimoDia) {
        foreach ($misVentasDiarias as $kvd => $venta) {
            $diaVenta = date('d', strtotime($venta['Venta']['created']));
            if($diaVenta==$dia){
               $ventasDiarias[$diaVenta*1] = $venta[0]['diario'];
               $totalVenta+=$venta[0]['diario'];
            }
        }
        if(!isset( $ventasDiarias[$dia*1]))
            $ventasDiarias[$dia*1]=0;
        $dia++;
    }
    echo $this->Form->input('ventasDiarias',['type'=>'hidden','value'=> json_encode($ventasDiarias)]);

    $comprasDiarias = [];
    $dia = 01;
    while ($dia<=$ultimoDia) {
        foreach ($misComprasDiarias as $kvd => $compra) {
            $diaCompra= date('d', strtotime($compra['Compra']['created']));
            if($diaCompra==$dia){
               $comprasDiarias[$diaCompra*1] = $compra[0]['diario'];        
               $totalCompra+=$compra[0]['diario'];
            }
           
        }
        if(!isset( $comprasDiarias[$dia*1]))
            $comprasDiarias[$dia*1]=0;
        $dia++;
    }
    echo $this->Form->input('comprasDiarias',['type'=>'hidden','value'=> json_encode($comprasDiarias)]);
    
    $sueldosDiarias = [];
    $dia = 01;
    while ($dia<=$ultimoDia) {
        foreach ($misSueldosDiarias as $kvr => $valorrecibo) {
            $diaValorRecibo= date('d', strtotime($valorrecibo['Valorrecibo']['created']));
            if($diaValorRecibo==$dia){
               $sueldosDiarias[$diaValorRecibo*1] = $valorrecibo[0]['diario'];     
               $totalSueldos+=$valorrecibo[0]['diario'];
            }
        }
        if(!isset( $sueldosDiarias[$dia*1]))
            $sueldosDiarias[$dia*1]=0;
        $dia++;
    }
    echo $this->Form->input('sueldosDiarias',['type'=>'hidden','value'=> json_encode($sueldosDiarias)]);
    
    $impuestosDiarias = [];
    $dia = 01;
    while ($dia<=$ultimoDia) {
        foreach ($misImpuestosDiarias as $kei => $eventoimpuesto) {
            $diaEventoImpuesto = date('d', strtotime($eventoimpuesto['Eventosimpuesto']['created']));
            if($diaEventoImpuesto==$dia){
               $impuestosDiarias[$diaEventoImpuesto*1] = $eventoimpuesto[0]['diario'];        
               $totalImpuestos+=$eventoimpuesto[0]['diario'];
            }
        }
        if(!isset( $impuestosDiarias[$dia*1]))
            $impuestosDiarias[$dia*1]=0;
        $dia++;
    }
    echo $this->Form->input('impuestosDiarias',['type'=>'hidden','value'=> json_encode($impuestosDiarias)]);
    ?>
    <dl style="width: 100%">
        <dt><h2><?php echo __('Reindimiento'); ?></h2></dt>
        <dd style="margin-left: 33%">
            Total 
        </dd>        
        <dd style="margin-left: 66%">
            Promedio 
        </dd>        
        <dt><?php echo __('Ventas'); ?></dt>
        <dd style="margin-left: 33%">
            <?php echo number_format($totalVenta,2,",","."); ?>
        </dd>        
        <dd style="margin-left: 66%">
            <?php echo number_format($totalVenta/$ultimoDia,2,",","."); ?>
        </dd>        
        <dt><?php echo __('Compras'); ?></dt>
        <dd style="margin-left: 33%">
            <?php echo number_format($totalCompra,2,",","."); ?>
        </dd>        
        <dd style="margin-left: 66%">
            <?php echo number_format($totalCompra/$ultimoDia,2,",","."); ?>
        </dd>        
        <dt><?php echo __('Sueldos'); ?></dt>
        <dd style="margin-left: 33%">
            <?php echo number_format($totalSueldos,2,",","."); ?>
        </dd>        
        <dd style="margin-left: 66%">
            <?php echo number_format($totalSueldos/$ultimoDia,2,",","."); ?>
        </dd>        
        <dt><?php echo __('Impuestos'); ?></dt>
        <dd style="margin-left: 33%">
            <?php echo number_format($totalImpuestos,2,",","."); ?>
        </dd>        
        <dd style="margin-left: 66%">
            <?php echo number_format($totalImpuestos/$ultimoDia,2,",","."); ?>
        </dd>        
    </dl>
    
    <?php
    
    $boton375 = 'none';
    $boton500 = 'none';
    $boton1000 = 'none';
    $boton1500 = 'none';
    $boton2000 = 'none';
    switch ($estudio['Estudio']['preciosubscripcion']) {
        case '375':
            $boton375 = 'inherit';
            break;
        case '500':
            $boton500 = 'inherit';
            break;
        case '1000':
            $boton1000 = 'inherit';
            break;
        case '1500':
            $boton1500 = 'inherit';
            break;
        case '2000':
            $boton2000 = 'inherit';
            break;
        default:
            break;
    }
    ?>
    
    <div id="boton375" style="display:<?php echo $boton375;?>">
        <a mp-mode="dftl" href="https://www.mercadopago.com/mla/checkout/start?pref_id=334169085-b2bdb039-1a32-480a-aece-9918632129c1" name="MP-payButton" class='blue-ar-l-sq-arall'>Pagar</a>
        <script type="text/javascript">
        (function(){function $MPC_load(){window.$MPC_loaded !== true && (function(){var s = document.createElement("script");s.type = "text/javascript";s.async = true;s.src = document.location.protocol+"//secure.mlstatic.com/mptools/render.js";var x = document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s, x);window.$MPC_loaded = true;})();}window.$MPC_loaded !== true ? (window.attachEvent ?window.attachEvent('onload', $MPC_load) : window.addEventListener('load', $MPC_load, false)) : null;})();
        </script>
    </div>
    <div id="boton500" style="display:<?php echo $boton500;?>">
        <a mp-mode="dftl" href="https://www.mercadopago.com/mla/checkout/start?pref_id=334169085-8898772b-982b-47e0-a81d-f73a11341be0" name="MP-payButton" class='blue-ar-l-sq-arall'>Pagar</a>
        <script type="text/javascript">
        (function(){function $MPC_load(){window.$MPC_loaded !== true && (function(){var s = document.createElement("script");s.type = "text/javascript";s.async = true;s.src = document.location.protocol+"//secure.mlstatic.com/mptools/render.js";var x = document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s, x);window.$MPC_loaded = true;})();}window.$MPC_loaded !== true ? (window.attachEvent ?window.attachEvent('onload', $MPC_load) : window.addEventListener('load', $MPC_load, false)) : null;})();
        </script>
        <!--subsctipcion 500-->
        <a mp-mode="dftl" href="http://mpago.la/zjNn" name="MP-payButton" class='blue-ar-l-sq-undefined'>Suscribirme</a>
        <script type="text/javascript">
            (function() {
                function $MPC_load() {
                    window.$MPC_loaded !== true && (function() {
                        var s = document.createElement("script");
                        s.type = "text/javascript";
                        s.async = true;
                        s.src = document.location.protocol + "//secure.mlstatic.com/mptools/render.js";
                        var x = document.getElementsByTagName('script')[0];
                        x.parentNode.insertBefore(s, x);
                        window.$MPC_loaded = true;
                    })();
                }
                window.$MPC_loaded !== true ? (window.attachEvent ? window.attachEvent('onload', $MPC_load) : window.addEventListener('load', $MPC_load, false)) : null;
            })();
        </script>


    </div>
    <div id="boton1000" style="display:<?php echo $boton1000;?>">
    <a mp-mode="dftl" href="https://www.mercadopago.com/mla/checkout/start?pref_id=334169085-cdfecbb5-24f3-45a3-b42d-080c47eaff67" name="MP-payButton" class='blue-ar-l-sq-arall'>Pagar</a>
    <script type="text/javascript">
    (function(){function $MPC_load(){window.$MPC_loaded !== true && (function(){var s = document.createElement("script");s.type = "text/javascript";s.async = true;s.src = document.location.protocol+"//secure.mlstatic.com/mptools/render.js";var x = document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s, x);window.$MPC_loaded = true;})();}window.$MPC_loaded !== true ? (window.attachEvent ?window.attachEvent('onload', $MPC_load) : window.addEventListener('load', $MPC_load, false)) : null;})();
    </script>
        <a mp-mode="dftl" href="http://mpago.la/1PHK" name="MP-payButton" class='blue-ar-l-sq-undefined'>Suscribirme</a>
        <!--subsctipcion 1000-->
        <a mp-mode="dftl" href="http://mpago.la/eGkv" name="MP-payButton" class='blue-ar-l-sq-undefined'>Suscribirme</a>
        <script type="text/javascript">
            (function() {
                function $MPC_load() {
                    window.$MPC_loaded !== true && (function() {
                        var s = document.createElement("script");
                        s.type = "text/javascript";
                        s.async = true;
                        s.src = document.location.protocol + "//secure.mlstatic.com/mptools/render.js";
                        var x = document.getElementsByTagName('script')[0];
                        x.parentNode.insertBefore(s, x);
                        window.$MPC_loaded = true;
                    })();
                }
                window.$MPC_loaded !== true ? (window.attachEvent ? window.attachEvent('onload', $MPC_load) : window.addEventListener('load', $MPC_load, false)) : null;
            })();
        </script>
    </div>
    <div id="boton1500" style="display:<?php echo $boton1500;?>">
        <a mp-mode="dftl" href="https://www.mercadopago.com/mla/checkout/start?pref_id=334169085-4ca816b0-0301-4f36-a3d8-4a3a88b68635" name="MP-payButton" class='blue-ar-l-sq-arall'>Pagar</a>
        <script type="text/javascript">
        (function(){function $MPC_load(){window.$MPC_loaded !== true && (function(){var s = document.createElement("script");s.type = "text/javascript";s.async = true;s.src = document.location.protocol+"//secure.mlstatic.com/mptools/render.js";var x = document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s, x);window.$MPC_loaded = true;})();}window.$MPC_loaded !== true ? (window.attachEvent ?window.attachEvent('onload', $MPC_load) : window.addEventListener('load', $MPC_load, false)) : null;})();
        </script>
        <!--subsctipcion 1500-->
        <a mp-mode="dftl" href="http://mpago.la/6frY" name="MP-payButton" class='blue-ar-l-rn-none'>Suscribirme</a>
        <script type="text/javascript">
            (function() {
                function $MPC_load() {
                    window.$MPC_loaded !== true && (function() {
                        var s = document.createElement("script");
                        s.type = "text/javascript";
                        s.async = true;
                        s.src = document.location.protocol + "//secure.mlstatic.com/mptools/render.js";
                        var x = document.getElementsByTagName('script')[0];
                        x.parentNode.insertBefore(s, x);
                        window.$MPC_loaded = true;
                    })();
                }
                window.$MPC_loaded !== true ? (window.attachEvent ? window.attachEvent('onload', $MPC_load) : window.addEventListener('load', $MPC_load, false)) : null;
            })();
        </script>
    </div>
    <div id="boton2000" style="display:<?php echo $boton2000;?>">
        <a mp-mode="dftl" href="https://www.mercadopago.com/mla/checkout/start?pref_id=334169085-dfd5bdde-faa8-45ee-9c5b-1d5658b4caaa" name="MP-payButton" class='blue-ar-l-sq-arall'>Pagar</a>
        <script type="text/javascript">
        (function(){function $MPC_load(){window.$MPC_loaded !== true && (function(){var s = document.createElement("script");s.type = "text/javascript";s.async = true;s.src = document.location.protocol+"//secure.mlstatic.com/mptools/render.js";var x = document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s, x);window.$MPC_loaded = true;})();}window.$MPC_loaded !== true ? (window.attachEvent ?window.attachEvent('onload', $MPC_load) : window.addEventListener('load', $MPC_load, false)) : null;})();
        </script>
        <!--subsctipcion 2000-->
        <a mp-mode="dftl" href="http://mpago.la/sXUk" name="MP-payButton" class='blue-ar-l-sq-undefined'>Suscribirme</a>
        <script type="text/javascript">
            (function() {
                function $MPC_load() {
                    window.$MPC_loaded !== true && (function() {
                        var s = document.createElement("script");
                        s.type = "text/javascript";
                        s.async = true;
                        s.src = document.location.protocol + "//secure.mlstatic.com/mptools/render.js";
                        var x = document.getElementsByTagName('script')[0];
                        x.parentNode.insertBefore(s, x);
                        window.$MPC_loaded = true;
                    })();
                }
                window.$MPC_loaded !== true ? (window.attachEvent ? window.attachEvent('onload', $MPC_load) : window.addEventListener('load', $MPC_load, false)) : null;
            })();
        </script>
    </div>
</div>

<div class="estudios index" style="width: 43%;float:left">
    <h2><?php echo __('Mi Estudio en el periodo '.$periodomes.'-'.$periodoanio); ?></h2>
    <?php  ?>
    <?php 
         echo $this->Html->link("<- Ver Periodo Anterior",array(
            'controller' => 'estudios',
            'action' => 'view',
            $estudio['Estudio']['id'],
            date('m-Y', strtotime('01-'.$periodomes.'-'.$periodoanio.' -1 months')),
        ),
            array(
                'class'=>"btn_aceptar",
                'style'=>'bottom: 0px;float: right;margin-top: 0px;width:100%'
            )
        ); 
        ?>
    <dl  style="width: 100%">
        
        <dt><?php echo __('Monotributistas'); ?></dt>
        <dd>
            <?php
            $monotributistas = 0;
            $respInscriptos = 0;
            $topEmpleados=0;
            $convenios=[];
            $nombreClientes = [];
            $nombreEmpleados = "";
            foreach ($estudio['Grupocliente'] as $grupocliente) {
                foreach ($grupocliente['Cliente'] as $cliente) {
                    $nombreClientes[$cliente['id']]=$cliente['nombre'];
                    foreach ($cliente['Impcli'] as $impcli ) {
                        if($impcli['impuesto_id']==4&&count($impcli['Periodosactivo'])>0){
                            $monotributistas++;
                        }
                        if($impcli['impuesto_id']==19&&count($impcli['Periodosactivo'])>0){
                            $respInscriptos++;
                        }
                    }
                    //Debugger::dump($cliente['nombre'].": ".count($cliente['Empleado'])."</br>");
                    if($topEmpleados<count($cliente['Empleado'])){
                        $topEmpleados = count($cliente['Empleado']);
                        $nombreEmpleados = $cliente['nombre'];
                    }
                    foreach ($cliente['Empleado'] as $empleado ) {
                        if(!in_array($empleado['conveniocolectivotrabajo_id'], $convenios)){
                            $convenios[]=$empleado['conveniocolectivotrabajo_id'];
                        }
                    }
                }
            }
            ?>
                <?php echo h($monotributistas); ?>
                &nbsp;
        </dd>
        <dt><?php echo __('Responsables Inscriptos'); ?></dt>
        <dd>            
                <?php echo h($respInscriptos); ?>
                &nbsp;
        </dd>
        <dt><?php echo __('Maximas Ventas por contribuyente'); ?></dt>
           
            <?php
            $topVentas=0;
            $topVentas2=0;
            $topVentas3=0;
            $topVentas4=0;
            $topVentas5=0;
            $nombreVentas=0;
            $nombreVentas2=0;
            $nombreVentas3=0;
            $nombreVentas4=0;
            $nombreVentas5=0;
            $arrayMaximos = [];
            
            usort($ventas, function ($a, $b) { return $b[0]['total'] - $a[0]['total']; });
            $top5 = array_slice($ventas, 0, 5);
                      
            $topVentas = isset($ventas[0])?$ventas[0][0]['total']:"";
            $nombreVentas = isset($ventas[0])?$nombreClientes[$ventas[0]['Venta']['cliente_id']]:"";
            $topVentas2 = isset($ventas[1])?$ventas[1][0]['total']:"";
            $nombreVentas2 = isset($ventas[1])?$nombreClientes[$ventas[1]['Venta']['cliente_id']]:"";
            $topVentas3 = isset($ventas[2])?$ventas[2][0]['total']:"";
            $nombreVentas3 = isset($ventas[2])?$nombreClientes[$ventas[2]['Venta']['cliente_id']]:"";
            $topVentas4 = isset($ventas[3])?$ventas[3][0]['total']:"";
            $nombreVentas4 = isset($ventas[3])?$nombreClientes[$ventas[3]['Venta']['cliente_id']]:"";
            $topVentas5 = isset($ventas[4])?$ventas[4][0]['total']:"";
            $nombreVentas5 = isset($ventas[4])?$nombreClientes[$ventas[4]['Venta']['cliente_id']]:"";
            
            ?>
        <dd>         
                <?php echo h($nombreVentas.": ".$topVentas); ?>
                &nbsp;
        </dd>
        <dt>2&ordm;</dt>
        <dd>         
                <?php echo h($nombreVentas2.": ".$topVentas2); ?>
                &nbsp;
        </dd>
        <dt>3&ordm;</dt>
        <dd>         
                <?php echo h($nombreVentas3.": ".$topVentas3); ?>
                &nbsp;
        </dd>
        <dt>4&ordm;</dt>
        <dd>         
                <?php echo h($nombreVentas4.": ".$topVentas4); ?>
                &nbsp;
        </dd>
        <dt>5&ordm;</dt>
        <dd>         
                <?php echo h($nombreVentas5.": ".$topVentas5); ?>
                &nbsp;
        </dd>
        <dt><?php echo __('Maximas Compras por contribuyente'); ?></dt>
        <dd>            
            <?php
            $topCompras=0;
            $nombrecompra="";
            $topCompras2=0;
            $nombrecompra2="";
            $topCompras3=0;
            $nombrecompra3="";
            $topCompras4=0;
            $nombrecompra4="";
            $topCompras5=0;
            $nombrecompra5="";
            
            usort($compras, function ($a, $b) { return $b[0]['total'] - $a[0]['total']; });
            $topCompras = isset($compras[0])?$compras[0][0]['total']:"";
            $nombrecompra = isset($compras[0])?$nombreClientes[$compras[0]['Compra']['cliente_id']]:"";
            $topCompras2 = isset($compras[1])?$compras[1][0]['total']:"";
            $nombrecompra2 = isset($compras[1])?$nombreClientes[$compras[1]['Compra']['cliente_id']]:"";
            $topCompras3 = isset($compras[2])?$compras[2][0]['total']:"";
            $nombrecompra3 = isset($compras[2])?$nombreClientes[$compras[2]['Compra']['cliente_id']]:"";
            $topCompras4 = isset($compras[3])?$compras[3][0]['total']:"";
            $nombrecompra4 = isset($compras[3])?$nombreClientes[$compras[3]['Compra']['cliente_id']]:"";
            $topCompras5 = isset($compras[4])?$compras[4][0]['total']:"";
            $nombrecompra5 = isset($compras[4])?$nombreClientes[$compras[4]['Compra']['cliente_id']]:"";
            ?>
                <?php echo h($nombrecompra.": ".$topCompras); ?>
                &nbsp;
        </dd>
        <dt>2&ordm;</dt>
        <dd>         
                <?php echo h($nombrecompra2.": ".$topCompras2); ?>
                &nbsp;
        </dd>
        <dt>3&ordm;</dt>
        <dd>         
                <?php echo h($nombrecompra3.": ".$topCompras3); ?>
                &nbsp;
        </dd>
        <dt>4&ordm;</dt>
        <dd>         
                <?php echo h($nombrecompra4.": ".$topCompras4); ?>
                &nbsp;
        </dd>
        <dt>5&ordm;</dt>
        <dd>         
                <?php echo h($nombrecompra5.": ".$topCompras5); ?>
                &nbsp;
        </dd>
        
        <dt><?php echo __('Max Empleados x contribuyente'); ?></dt>
        <dd>                      
                <?php echo h($nombreEmpleados.': '.$topEmpleados); ?>
                &nbsp;
        </dd>
        <dt><?php echo __('Convenios Utilizados'); ?></dt>
        <dd>                      
                <?php echo h(count($convenios)); ?>
                &nbsp;
        </dd>
    </dl>
</div>
<div class="index">
    <h2>Rendimiendo diario de Mi Estudio</h2>
    <div class="index" style="/*width:42%;height:700px;float:left*/">
        <canvas id="myChartVentas" ></canvas>
    </div>
<?php
    
?>
   
</div>
<!-- Popin Modal para edicion de ventas a utilizar por datatables-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
	<div class="modal-dialog" style="width:90%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<!--					<span aria-hidden="true">&times;</span>-->
				</button>
				<h4 class="modal-title">Editar Datos Estudio</h4>
			</div>
			<div class="modal-body">
                    <?php echo $this->Form->create('Estudio',['class'=>'formTareaCarga']); ?>
                    <?php echo $this->Form->input('id',['type'=>'hidden']); ?>
                    <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td ><?php echo $this->Form->input('nombre',['style'=>'width:250px']); ?></td>
                                <td colspan="2"><?php echo $this->Form->input('propietario',['style'=>'width:250px']);?></td>   
                            </tr>
                            <tr>
                                <td><?php echo $this->Form->input('direccion',['style'=>'width:350px']);?></td>
                                <td  colspan="2"><?php echo $this->Form->input('cuit',['style'=>'width:100px']);?></td>   
                            </tr>
                            <tr>
                                <td><?php echo $this->Form->input('ingresosbrutos',['style'=>'width:100px','label'=>'Ingresos brutos']);?></td>
                                <td colspan="2"><?php   echo $this->Form->input('inicioactividades', array(
                                        'class'=>'datepicker',
                                        'type'=>'text',
                                        'label'=>'Inicio de Actividades',
                                        'required'=>true,
                                        'default'=>date('d-m-Y',strtotime($this->request->data['Estudio']['inicioactividades'])),
                                        'readonly'=>'readonly')
                                    );?>
                                </td>   
                            </tr>
                            <tr>
                                <td></td>
                                <td><a href="#close"  onclick="" class="btn_cancelar" style="margin-top:14px">Cancelar</a></td>
                                <td><?php echo $this->Form->end(__('ACEPTAR')); ?></td>

                            </tr>
                    </table>
					
            
                   
					
            
			<div class="modal-footer">
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

