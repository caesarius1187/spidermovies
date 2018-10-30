<h2><?php echo __d('cart', 'Your order #%s', $order['Order']['id']); ?></h2>

<dl>
	<dt><?php echo __d('cart', 'Invoice Number'); ?></dt>
	<dd><?php echo h($order['Order']['invoice_number']); ?></dd>
	<dt><?php echo __d('cart', 'Total'); ?></dt>
	<dd><?php echo h($order['Order']['total']); ?></dd>
	<dt><?php echo __d('cart', 'Created'); ?></dt>
	<dd><?php echo h($order['Order']['created']); ?></dd>
	<dt><?php echo __d('cart', 'Payment Method'); ?></dt>
	<dd><?php echo h($order['Order']['processor']); ?></dd>
</dl>

<?php
	//debug($orderItems);
	//debug($order);
	//debug($this->layout);
?>

<h3><?php echo __d('cart', 'Ordered Items'); ?></h3>

<table class="table table-striped table-bordered table-condensed">
	<tr>
		<th><?php echo $this->Paginator->sort('OrderItem.quantity', __d('cart', 'Quantity')); ?></th>
		<th><?php echo $this->Paginator->sort('OrderItem.name', __d('cart', 'Name')); ?></th>
		<th><?php echo $this->Paginator->sort('OrderItem.price', __d('cart', 'Price')); ?></th>
		<th><?php echo $this->Paginator->sort('OrderItem.virtual', __d('cart', 'Virtual')); ?></th>
	</tr>
	<?php foreach ($orderItems as $item) : ?>
		<tr>
			<td>
				<?php echo h($item['OrderItem']['quantity']); ?>
			</td>
			<td>
				<?php echo h($item['OrderItem']['name']); ?>
			</td>
			<td>
				<?php echo h($item['OrderItem']['price']); ?>
			</td>
			<td>
				<?php
					if ($item['OrderItem']['virtual'] == 1) :
						echo __d('cart', 'Yes');
					else :
						echo __d('cart', 'No');
					endif;
				?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>