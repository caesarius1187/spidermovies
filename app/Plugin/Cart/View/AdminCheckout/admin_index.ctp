<h2><?php echo __d('cart', 'Buy Items for a user'); ?></h2>

<?php
	echo $this->Form->create();
	echo $this->Form->input('username');
	echo $this->Form->input('email');
	echo $this->Form->submit(__d('cart', 'Submit'));
	echo $this->Form->end();
?>