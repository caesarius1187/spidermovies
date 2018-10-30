<div class="shippingMethods form">
<?php echo $this->Form->create('ShippingMethod', array('url' => array('action' => 'add')));?>
	<fieldset>
 		<legend><?php echo __d('cart', 'Admin Add Shipping Method');?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('price');
		echo $this->Form->input('currency');
		echo $this->Form->input('position');
	?>
	</fieldset>
<?php echo $this->Form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__d('cart', 'List Shipping Methods'), array('action' => 'index'));?></li>
	</ul>
</div>