<div class="cbuses index">
	<h2><?php echo __('CBUs del banco: '.$impcli['Impuesto']['nombre']); ?></h2>
	<h2><?php echo __('Editar CBU');?></h2>
	<?php
	echo $this->Form->create('Cbu',['action'=>'edit']);
	echo $this->Form->input('id',['default'=>$impcli['Impcli']['id'],'type'=>'hidden']);
	echo $this->Form->input('impcli_id',['type'=>'hidden']);
	echo $this->Form->input('cuentascliente_id',[]);
	echo $this->Form->input('numerocuenta');
	echo $this->Form->input('cbu',['type'=>'text']);
	echo $this->Form->input('tipocuenta',[
		'options'=>[
			'Caja de Ahorro en Euros'=>'Caja de Ahorro en Euros',
			'Caja de Ahorro en Moneda Local'=>'Caja de Ahorro en Moneda Local',
			'Caja de Ahorro en U$S'=>'Caja de Ahorro en U$S',
			'Cuenta Corriente en Euros'=>'Cuenta Corriente en Euros',
			'Cuenta Corriente en Moneda Local'=>'Cuenta Corriente en Moneda Local',
			'Cuenta Corriente en U$S'=>'Cuenta Corriente en U$S',
			'Otras'=>'Otras',
			'Plazo Fijo en Euros'=>'Plazo Fijo en Euros',
			'Plazo Fijo en Moneda Local'=>'Plazo Fijo en Moneda Local',
			'Plazo Fijo en U$S'=>'Plazo Fijo en U$S'
		]
	]);
	echo $this->Form->end(__('Modificar')); ?>
</div>