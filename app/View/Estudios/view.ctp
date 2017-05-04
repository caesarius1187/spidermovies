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

