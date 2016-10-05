
<?php echo $this->Form->create('User', array('action' => 'edit')); ?>
		
		<?php echo $this->Form->input('id') ;?>
		<h3><?php echo __('Modificar Usuario'); ?></h3>
		<table style="width:85%; margin-bottom:0px">
			<tr>
				<td><?php echo $this->Form->input('nombre',array('style' => 'width:200px')) ;?></td>
				<td><?php echo $this->Form->input('dni',array('style' => 'width:200px')) ;?></td>	
			</tr>
			<tr>
				<td><?php echo $this->Form->input('telefono',array('style' => 'width:200px')) ;?></td>
				<td><?php echo $this->Form->input('cel',array('style' => 'width:200px', 'label' => 'Celular')) ;?></td>
			</tr>
			<tr >
				<td colspan="2"><?php echo $this->Form->input('mail', array('style' => 'width:423px', 'label' => 'E-mail')) ;?></td>
			</tr>
			<tr>
				<td><?php echo $this->Form->input('username', array('style' => 'width:200px', 'label' => 'Usuarios')) ;?></td>
				<td><?php echo $this->Form->input('password', array('style' => 'width:200px', 'label' => 'Contrase&ntilde;a')) ;?></td>	
			</tr>	
			<tr>
				<td><?php echo $this->Form->input('tipo',array('style' => 'width:200px')) ;?></td>
				<td><?php echo $this->Form->input('estado',array('style' => 'width:200px'));?></td>
			</tr>
			<tr>
					<td colspan="2">
						<table style="margin-bottom:0px">
							<tr>
								<td width="300">&nbsp;</td>
								<td><div class="submit"><a href="#close" class="btn_cancelar"style="margin-top:3px;">Cancelar</a></div></td>
								<td><?php echo $this->Form->end(__('Aceptar')); ?></td>
							</tr>
						</table>
					</td>	
			</tr>		
	</table>
	



