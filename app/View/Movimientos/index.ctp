<script type="text/javascript">
	$(document).ready(function() {		
		$('.chosen-select').chosen({search_contains:true});
		$('#AsientoAddForm').submit(function(){
			//serialize form data
			var formData = $(this).serialize();
			//get form action
			var formUrl = $(this).attr('action');
			$.ajax({
				type: 'POST',
				url: formUrl,
				data: formData,
				success: function(data,textStatus,xhr){
					var respuesta = JSON.parse(data);
					callAlertPopint(respuesta.respuesta);
				},
				error: function(xhr,textStatus,error){
					alert(textStatus);
				}
			});
			return false;
		});
	});
	function agregarMovimiento()
	{
		var tablaAsiento = $("#tablasiento");
		/*Sacar numero de movimiento siguiente*/
		/*Tengo que agregar cuentacliente_id*/
		/*Tengo que agregar debe*/
		/*Tengo que agregar haber*/
	}

</script>
		<?php
		$asiento = $asientos[0];
		?>
<div class="index" style="float: none;">
	<h3>Agregar movimiento al asiento</h3>
	<?php
	echo $this->Form->create('Movimiento',['class'=>'formTareaCarga','id'=>'FormAgregaMovimiento']);
	echo $this->Form->input('cuentascliente',[
		'id'=>'FormEditaMovimientoCuentascliente',
		'style'=>'width:300px',
		'div'=>['style'=>'width:300px'],
		'type'=>'select',
		'class'=>'chosen-select',
	]);
	echo $this->Form->input('debe');
	echo $this->Form->input('haber');
	echo $this->Form->input('fecha',['type'=>'hidden','value'=>date('d-m-Y')]);
	echo $this->Form->end('Agregar');
	?>
</div>
<div class="index" style="float: none;">
	<h3>Modificar Asiento</h3>
	<?php
	echo $this->Form->create('Asiento',[
		'class'=>'formTareaCarga formAsiento',
		'action'=>'add','controller'=>'asientos',
		'id'=>'FormEditAsiento']);
	echo $this->Form->input('Asiento.0.id',[
		'value'=>$asiento['Asiento']['id'],
		]
	);
	echo $this->Form->input('Asiento.0.cliente_id',[
		'value'=>$asiento['Asiento']['cliente_id'],
		'type'=>'hidden',
		]
	);
	echo $this->Form->input('Asiento.0.nombre',
		['value'=>$asiento['Asiento']['nombre'],
			'style'=>"width:300px"]);
	echo $this->Form->input('Asiento.0.descripcion',
		['value'=>$asiento['Asiento']['descripcion'],
			'style'=>"width:300px"]);
	echo $this->Form->input('Asiento.0.fecha',
		['value'=>$asiento['Asiento']['fecha'],
			'style'=>"width:120px"]);
	echo "</br>";
	?>

	<table id="tablaModificarAsiento" class="tbl_border"><?php
	foreach ($asiento['Movimiento'] as $m => $movimiento){
		?>
		<tr id="movimientoeditnumero<?php echo $m ?>" >
			<td style="width: 300px"><?php
				echo $this->Form->input('Asiento.0.Movimiento.'.$m.'.id',
					['value'=>$movimiento['id']]);
				echo $this->Form->input('Asiento.0.Movimiento.'.$m.'.cuentascliente_id',
					[
						'value'=>$movimiento['cuentascliente_id'],
						'type'=>'hidden',
					]);
				echo $this->Form->input('Asiento.0.Movimiento.'.$m.'.fecha',
					[
						'value'=>$movimiento['fecha'],
						'default'=>date('d-m-Y'),
						'type'=>'hidden',
					]);
				echo $this->Form->input('Asiento.0.Movimiento.'.$m.'.cuenta_id',
					[
						'value'=>$movimiento['Cuentascliente']['cuenta_id'],
						'type'=>'hidden',
					]);

		echo $this->Form->label('nombreCuenta',
				$movimiento['Cuentascliente']['nombre'],
				[
					'style'=>"display:initial",
				]
			);
				?>
			</td>
			<td><?php
		echo $this->Form->input('Asiento.0.Movimiento.'.$m.'.debe',
			['value'=>$movimiento['debe'],
				'style'=>"width:auto",
				'label'=>false]);
		echo $this->Form->input('Asiento.0.Movimiento.'.$m.'.haber',
			['value'=>$movimiento['haber'],
				'style'=>"width:auto",
				'label'=>false]);
		echo $this->Html->image('eliminar.png',array('width' => '20', 'height' => '20'
		,'onClick'=>"deleteRowMovimientoEdit(".$m.")"));

				?>
			</td>
		</tr>
		<?php
	}
	?>
	</table>
	<?php
	echo $this->Form->end('Modificar')
	?>
	<?php
	echo $this->Form->input('topmovimiento',['value'=>$m]);
	?>
</div>
