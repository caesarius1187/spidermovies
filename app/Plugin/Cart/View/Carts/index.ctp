<h2><?php __d('cart', 'Your saved carts'); ?></h2>
<?php if (!empty($carts)) : ?>
	<table>
		<thead>
			<tr>
				<th><?php echo $this->Paginator->sort('name'); ?></th>
				<th><?php echo $this->Paginator->sort('total'); ?></th>
				<th><?php echo $this->Paginator->sort('item_count'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($carts as $cart) : ?>
			<tr>
				<td><?php echo $this->Html->link($cart['Cart']['name'], array('controller' => 'carts', 'action' => 'view', $cart['Cart']['id'])); ?></td>
				<td><?php echo $cart['Cart']['item_count']; ?></td>
				<td><?php echo $cart['Cart']['total']; ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php else : ?>
	<p><?php __d('cart', 'You do not have a filled cart'); ?></p>
<?php endif; ?>
