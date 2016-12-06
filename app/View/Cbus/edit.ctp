<div class="cbuses form">
<?php 

echo $this->Form->create('Cbus',array('controller'=>'cbuses','action' => 'edit', )); ?>
	<fieldset>
		<legend><?php echo __('Editar CBU:'); ?></legend>
		 <?php
                echo $this->Form->input('id');
                echo $this->Form->input('bancosysindicato_id',array('type'=>'hidden'));
                echo $this->Form->input('clienteid',array('type'=>'hidden','default'=>$clienteid));
                ?>
	<?php
		echo $this->Form->input('tipo');
		echo $this->Form->input('numero');
		echo $this->Form->input('cbu');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Guardar')); ?>
</div>

