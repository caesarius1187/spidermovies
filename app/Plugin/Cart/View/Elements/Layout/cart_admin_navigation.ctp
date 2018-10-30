<ul class="cart-admin-nav">
	<li>
		<?php
			echo $this->Html->link(__d('cart', 'Orders'), array(
				'controller' => 'orders',
				'action' => 'index'));
		?>
	</li>
	<li>
		<?php
			echo $this->Html->link(__d('cart', 'Addresses'), array(
				'controller' => 'shipping_methods',
				'action' => 'index'));
		?>
	</li>
	<li>
		<?php
			echo $this->Html->link(__d('cart', 'Shipping Methods'), array(
				'controller' => 'shipping_methods',
				'action' => 'index'));
		?>
	</li>
	<li>
		<?php
			echo $this->Html->link(__d('cart', 'Payment Methods'), array(
				'controller' => 'payment_methods',
				'action' => 'index'));
		?>
	</li>
</ul>