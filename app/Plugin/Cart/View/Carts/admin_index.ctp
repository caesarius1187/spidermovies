<h2><?php echo __d('cart', 'Carts'); ?></h2>
<?php if (!empty($carts)) : ?>
	<table>
		<thead>
			<tr>
				<th><?php echo $this->Paginator->sort('User.username'); ?></th>
				<th><?php echo $this->Paginator->sort('name'); ?></th>
				<th><?php echo $this->Paginator->sort('total'); ?></th>
				<th><?php echo $this->Paginator->sort('item_count'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($carts as $cart) : ?>
			<tr>
				<td><?php echo h($cart['User']['username']); ?></td>
				<td><?php echo $this->Html->link($cart['Cart']['name'], array('controller' => 'carts', 'action' => 'view', $cart['Cart']['id'])); ?></td>
				<td><?php echo $cart['Cart']['item_count']; ?></td>
				<td><?php echo $cart['Cart']['total']; ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php else : ?>
	<p><?php echo __d('cart', 'No carts found.'); ?></p>
<?php endif; ?>