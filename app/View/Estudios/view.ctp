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
        var myVentasChartbar = new Chart(ctxbar, {
            type: 'bar',
            data: {
                labels: labelsv,
                datasets: [{
                    label: 'Ventas',
                    data: valuesv,
                    borderWidth: 1
                },
                {
                    label: 'Compras',
                    data: valuesc,
                    borderWidth: 1
                }],
            },
            options: {
                title: {
                    display: true,
                    text: 'Ventas/dia'
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
       
        var ctxbar = $("#myChartCompras");
        var myVentasChartbar = new Chart(ctxbar, {
            type: 'bar',
            data: {
                labels: labelsc,
                datasets: [{
                    label: labelsc,
                    data: valuesc,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Compras/dia'
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
<div class="estudios index" style="margin-bottom:5px">
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
	<dl>
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
</div>

<div class="estudios index">
    <h2><?php echo __('Mi Estudio en el periodo '.$periodomes.'-'.$periodoanio); ?></h2>
    <dl>
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
    <div class="index" style="width:42%;height:700px;float:left">
        <canvas id="myChartVentas" width="250" height="250"></canvas>
    </div>
<?php
    $ventasDiarias = [];
    $dia = 01;
    $ultimoDia = date('t', strtotime('01-'.$periodomes.'-'.$periodoanio));
    while ($dia<=$ultimoDia) {
        foreach ($misVentasDiarias as $kvd => $venta) {
            $diaVenta = date('d', strtotime($venta['Venta']['created']));
            if($diaVenta==$dia){
               $ventasDiarias[$diaVenta*1] = $venta[0]['diario'];
            }
        }
        if(!isset( $ventasDiarias[$dia*1]))
            $ventasDiarias[$dia*1]=0;
        $dia++;
    }
    echo $this->Form->input('ventasDiarias',['type'=>'hidden','value'=> json_encode($ventasDiarias)]);
?>
    <div class="index" style="width:42%;height:700px;float:right">
        <canvas id="myChartCompras" width="250" height="250"></canvas>
    </div>
<?php
    $comprasDiarias = [];
    $dia = 01;
    $ultimoDia = date('t', strtotime('01-'.$periodomes.'-'.$periodoanio));
    while ($dia<=$ultimoDia) {
        foreach ($misComprasDiarias as $kvd => $compra) {
            $diaCompra= date('d', strtotime($compra['Compra']['created']));
            if($diaCompra==$dia){
               $comprasDiarias[$diaCompra*1] = $compra[0]['diario'];              
            }
        }
        if(!isset( $comprasDiarias[$dia*1]))
            $comprasDiarias[$dia*1]=0;
        $dia++;
    }
    echo $this->Form->input('comprasDiarias',['type'=>'hidden','value'=> json_encode($comprasDiarias)]);
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
				<div class="estudios form">
					<?php echo $this->Form->create('Estudio',['class'=>'formTareaCarga']); ?>
						<?php
						echo $this->Form->input('id',['type'=>'hidden']);
						echo $this->Form->input('nombre',['style'=>'width:250px']);
						echo $this->Form->input('propietario',['style'=>'width:250px']);
						echo $this->Form->input('direccion',['style'=>'width:250px']);
						echo $this->Form->input('cuit',['style'=>'width:100px']);
						echo $this->Form->input('ingresosbrutos',['style'=>'width:100px']);
						echo $this->Form->input('inicioactividades', array(
								'class'=>'datepicker',
								'type'=>'text',
								'label'=>'inicio de Actividades',
								'required'=>true,
								'default'=>date('d-m-Y',strtotime($this->request->data['Estudio']['inicioactividades'])),
								'readonly'=>'readonly')
						);
						?>
					<?php echo $this->Form->end(__('Modificar')); ?>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<!--                <button type="button" class="btn btn-primary">Save changes</button>-->
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

