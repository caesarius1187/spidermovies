<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Add User'); ?></legend>
	<?php
		echo $this->Form->input('dni');
		echo $this->Form->input('telefono');
		echo $this->Form->input('cel');
		echo $this->Form->input('mail');
		echo $this->Form->input('estudio_id',array('type'=>'hidden','default'=>$this->Session->read('Auth.User.estudio_id')));
		echo $this->Form->input('nombre');
		echo $this->Form->input('username');
		echo $this->Form->input('password');
		echo $this->Form->input('tipo', array(
            'options' => array('administrador' => 'Administrador', 'cliente' => 'Cliente', 'operario' => 'Operario')
        ));
		echo $this->Form->input('estado',array(
			'type'=>'select',
			'options'=>array(
				'habilitado'=>'habilitado',
				'deshabilitado'=>'deshabilitado')
			)
		);
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
