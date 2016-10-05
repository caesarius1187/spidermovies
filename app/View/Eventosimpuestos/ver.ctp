<div class="eventosimpuestos view">
<?php if(isset($error)){
	echo $error;
}else{
	
	switch ($tarea){
	case 'tarea5':
	//Prepara Papeles de Trabajo

	?>
	<?php echo $this->Form->create('Eventosimpuesto',array('action'=>'edit')); ?>
	<table>
		<tr>
			<td>
				<?php echo $this->Form->input('periodo'); ?>
			</td>	
			<td>
				<?php echo $this->Form->input('fchvto',array('label'=>'Fecha de Vencimiento')); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo $this->Form->input('montovto',array('label'=>'Monto de Vencimiento')); ?>
			</td>	
			<td>
				<?php echo $this->Form->input('monc',array('label'=>'Monto a favor')); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo $this->Form->input('descripcion'); ?>
			</td>	
		</tr>
	</table>
	<?php echo $this->Form->end(__('Aceptar')); ?>

	<?php
	break;
	case 'tarea13':
	//pagar
	?>
	<table>
		<tr>
			<td>
				Periodo:
				<?php echo h($eventosimpuesto['Eventosimpuesto']['periodo']); ?>
			</td>	
			<td>
				Fecha de Vencimiento:
				<?php echo h($eventosimpuesto['Eventosimpuesto']['fchvto']); ?>
			</td>
		</tr>
		<tr>
			<td>
				Monto Vencimiento:
				<?php echo h($eventosimpuesto['Eventosimpuesto']['montovto']); ?>
			</td>	
			<td>
				Monto a Favor:
				<?php echo h($eventosimpuesto['Eventosimpuesto']['monc']); ?>
			</td>
		</tr>
		<tr>
			<td>
				Descripcion:
				<?php echo h($eventosimpuesto['Eventosimpuesto']['descripcion']); ?>
			</td>	

		</tr>
	</table>
	<?php
	break;
	}
?> 



<?php }?>	