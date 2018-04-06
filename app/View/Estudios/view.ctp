<?php
echo $this->Html->css('bootstrapmodal');
echo $this->Html->script('jquery-ui',array('inline'=>false));
echo $this->Html->script('estudios/view',array('inline'=>false));
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
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
	});

</script>
<div class="estudios index">
	<div class="fab blue" style="float: right;">
		<core-icon icon="add" align="center" style="margin: 8px 14px;position: absolute;">
			<?php echo $this->Form->button(
				$this->Html->image(
					'edit_view.png',
					array(
						'alt' => 'edit',
						'class'=>'imgedit',
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
            foreach ($ventas as $key => $venta) {
                if($topVentas<$venta[0]['total']){
                    if($topVentas2<$topVentas){
                        if($topVentas3<$topVentas2){
                            if($topVentas4<$topVentas3){
                                if($topVentas5<$topVentas4){
                                    $topVentas5 = $topVentas4;
                                    $nombreVentas5 = $nombreVentas4;
                                }
                                $topVentas4 = $topVentas3;
                                $nombreVentas4 = $nombreVentas3;
                            }
                            $topVentas3 = $topVentas2;
                            $nombreVentas3 = $nombreVentas2;
                        }
                        $topVentas2 = $topVentas;
                        $nombreVentas2 = $nombreVentas;
                    }
                    $topVentas = $venta[0]['total'];
                    $nombreVentas = $nombreClientes[$venta['Venta']['cliente_id']];
                }
            }
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
            foreach ($compras as $key => $compra) {
                if($topCompras < $compra[0]['total']*1){
                    if($topCompras2 < $topCompras){
                        if($topCompras3 < $topCompras2){
                            if($topCompras4 < $topCompras3){
                                if($topCompras5 < $topCompras4){
                                   $topCompras5 = $topCompras4;
                                    $nombrecompra5 = $nombrecompra4;
                                }
                                $topCompras4 = $topCompras3;
                                $nombrecompra4 = $nombrecompra3;
                            }
                            $topCompras3 = $topCompras2;
                            $nombrecompra3 = $nombrecompra2;
                        }
                        $topCompras2 = $topCompras;
                        $nombrecompra2 = $nombrecompra;
                    }
                    $topCompras = $compra[0]['total']*1;
                    $nombrecompra = $nombreClientes[$compra['Compra']['cliente_id']];
                }
            }
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

