<h2><?php echo __d('cart', 'Orders'); ?></h2>

<?php
	echo $this->Form->create();
	echo $this->Form->input('invoice_number', array(
		'label' => __d('cart', 'Invoice Number')));
	echo $this->Form->input('total', array(
		'label' => __d('cart', 'Total')));
	echo $this->Form->input('username', array(
		'label' => __d('cart', 'Username')));
	echo $this->Form->input('email', array(
		'label' => __d('cart', 'Email')));
	echo $this->Form->end(__d('cart', 'Search'));
?>

<?php if (!empty($orders)) : ?>
	<?php echo $this->element('paging'); ?>
	<table class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th><?php echo $this->Paginator->sort('total', __d('cart', 'Total')); ?></th>
				<th><?php echo $this->Paginator->sort('currency', __d('cart', 'Currency')); ?></th>
				<th><?php echo $this->Paginator->sort('order_number', __d('cart', 'Order #')); ?></th>
				<th><?php echo $this->Paginator->sort('invoice_number', __d('cart', 'Invoice #')); ?></th>
				<th><?php echo $this->Paginator->sort('processor', __d('cart', 'Payment Method')); ?></th>
				<th><?php echo $this->Paginator->sort('created', __d('cart', 'Created')); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($orders as $order) : ?>
				<tr>
					<td>
						<?php
							echo $this->Html->link($order['Order']['total'], array(
								'action' => 'view', $order['Order']['id']));
						?>
					</td>
					<td><?php echo $order['Order']['currency']; ?></td>
					<td><?php echo $this->Number->currency($order['Order']['total'], $order['Order']['currency']); ?></td>
					<td><?php echo $order['Order']['invoice_number']; ?></td>
					<td><?php echo $order['Order']['processor']; ?></td>
					<td><?php echo $order['Order']['created']; ?></td>
					<td>
						<?php
							echo $this->Html->link(__d('cart', 'view'), array('action' => 'view', $order['Order']['id'])) . ' | ';
							echo $this->Html->link(__d('cart', 'refund'), array('action' => 'refund', $order['Order']['id']));
						?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<p><?php echo __d('cart', 'No orders'); ?></p>
<?php endif; ?>