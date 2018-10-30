<h2><?php echo __d('cart', 'Review Your Order')?></h2>
<p>
	<?php 
		echo __d('cart', 'Please review your order and and click below to complete your payment.');
	?>
</p>

<table class="table table-striped table-bordered table-condensed">
	<thead>
		<tr>
			<th><?php echo __d('cart', 'Item'); ?></th>
			<th><?php echo __d('cart', 'Price'); ?></th>
			<th><?php echo __d('cart', 'Quantity'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($cart['CartsItem'] as $key => $item) : ?>
			<tr>
				<td><?php echo h($item['name']); ?></td>
				<td><?php echo CakeNumber::currency($item['total']); ?></td>
				<td>
					<?php
						echo h($item['quantity']);
					?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
	<tfooter>
		<tr>
			<td><?php echo __d('cart', 'Total'); ?>
			<td colspan="2"><?php echo CakeNumber::currency($cart['Cart']['total']); ?></td>
		</tr>
	</tfooter>
</table>


<h3><?php __d('cart', 'You must click on the "Complete my purchase" button below to complete your purchase'); ?></h3>
<?php 
	echo $this->Form->create('Checkout', array('url' => env('REQUEST_URI')));
	echo $this->Form->hidden('confirm_order', array('value' => 1));
	echo $this->Form->button('Cancel order', array(
		'class' => 'btn btn-danger',
		'type' => 'submit',
		'name' => 'cancel',
		'div' => false));
?>
 
<?php
	echo $this->Form->submit(__d('cart', 'Complete my order', true), array(
		'class' => 'btn btn-primary',
		'div' => false,
		'name' => 'complete',));
	echo $this->Form->end();
?>
<?php //debug($this->request); ?>

