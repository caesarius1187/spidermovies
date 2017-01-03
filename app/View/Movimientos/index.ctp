<script type="text/javascript">
	$(document).ready(function() {		
		$('.chosen-select').chosen({search_contains:true});
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
	echo $this->Form->create('Movimiento',['class'=>'formTareaCarga']);
	echo $this->Form->input('cuentascliente',[
		'type'=>'select',
		'class'=>'chosen-select',
		'style'=>'width:80px'
	]);
	echo $this->Form->input('debe');
	echo $this->Form->input('haber');
	echo $this->Form->end('Agregar');
	?>
</div>
<div class="index" style="float: none;">
	<h3>Modificar Asiento</h3>
	<?php
	echo $this->Form->create('Asiento',['class'=>'formTareaCarga']);
	echo $this->Form->input('Asiento.0.id',[
		'value'=>$asiento['Asiento']['id'],
		'style'=>"width:auto"
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
			'style'=>"width:80px"]);
	echo "</br>";
	?>

	<table id="tablaasiento"><?php
	foreach ($asiento['Movimiento'] as $m => $movimiento){
		?>
		<tr>
			<td style="width: 300px"><?php
		echo $this->Form->input('Asiento.0.Movimiento.'.$m.'.id',
			['value'=>$movimiento['id']]);
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
		?>
			</td>
		</tr>
		<?php
	}
	?>
		<tr>
			<td colspan="20">
				<?php
				echo $this->Form->end('Modificar')
				?>
			</td>
		</tr>
	</table>
	<?php
	$this->Form->input('nextmovimiento',['value'=>$m]);
	?>
</div>
