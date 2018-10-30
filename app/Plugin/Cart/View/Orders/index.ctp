<h2><?php echo __d('cart', 'Orders'); ?></h2>
<?php if (!empty($orders)) : ?>
	<table>
		<thead>
			<tr>
				<th><?php echo $this->Paginator->sort('invoice_number'); ?></th>
				<th><?php echo $this->Paginator->sort('total'); ?></th>
				<th><?php echo $this->Paginator->sort('created'); ?></th>
				<th>-</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($orders as $order) : ?>
				<tr>
					<td><?php echo h($order['Order']['invoice_number']); ?></td>
					<td><?php echo $this->Number->currency($order['Order']['total'], $order['Order']['currency']); ?></td>
					<td><?php echo h($order['Order']['created']); ?></td>
					<td><?php echo $this->Html->link(__d('cart', 'view'), array('action' => 'view', $order['Order']['id'])); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<p><?php echo __d('cart', 'No orders'); ?></p>
<?php endif; ?>