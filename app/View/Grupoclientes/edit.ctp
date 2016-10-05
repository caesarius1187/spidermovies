<div class="grupoclientes form">
<?php echo $this->Form->create('Grupocliente', array('action' => 'edit')); ?>
	<fieldset>
		<legend><?php echo __('Modificar Grupo de Clientes'); ?></legend>ç
		<?php echo $this->Form->input('id'); ?>
		<table>
			<tr>
				<td><?php 	echo $this->Form->input('nombre'); ?></td>
				<td><?php 	echo $this->Form->input('descripcion'); ?></td>
			</tr>
			<tr>
				<td colspan="2">	<?php 	echo $this->Form->input('estado', array(
												'type' => 'select', 
												'style' => 'width:475px',
												'options' => array(
													'habilitado'=>'Habilitado',
													'deshabilitado'=>'Deshabilitado'), 
												'default' => $this->request->data['Grupocliente']['estado'])); ?>
				</td>
			</tr>
		</table>
	
	
	

		//echo $this->Form->input('estudio_id');
	
	</fieldset>
<?php echo $this->Form->end(__('Aceptar')); ?>
</div>