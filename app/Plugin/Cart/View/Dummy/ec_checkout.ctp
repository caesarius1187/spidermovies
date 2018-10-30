<h2><?php __d('cart', 'Dummy Express Checkout'); ?></h2>
<p>
	<?php echo __d('cart', 'Dummy express checkout process for demonstration purpose'); ?>
</p>
<?php
	echo $this->Form->create('Dummy');
	echo $this->Form->input('confirm', array(
		'type' => 'checkbox',
		'default' => 1));
	echo $this->Form->end(__d('cart', 'Pay', true));
?>