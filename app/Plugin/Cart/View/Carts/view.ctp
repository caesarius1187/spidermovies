<div class="index">
	<h2>
		<?php echo __d('cart', 'Carrito'); ?>
	</h2>

	<?php if (!empty($cart['CartsItem'])) : ?>
		<?php echo $this->Form->create('Cart'); ?>
			<table class="table table-striped table-bordered table-condensed">
				<thead>
					<tr>
						<th><?php echo __d('cart', 'Pelicula'); ?></th>
						<th><?php echo __d('cart', 'Precio'); ?></th>
						<th><?php echo __d('cart', 'Cantidad'); ?></th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($cart['CartsItem'] as $key => $item) : ?>
						<tr>
							<td><?php echo h($item['name']); ?></td>
							<td><?php echo CakeNumber::currency($item['total']); ?></td>
							<td>
								<?php
									if ($item['quantity_limit'] != 1) {
										echo $this->Form->input('CartsItem.' . $key . '.quantity', array(
											'div' => false,
											'label' => false,
											'default' => $item['quantity'],
											'class' => 'input-small'));
									} else {
										echo ' ' . $item['quantity_limit'] . ' ';
									}
									echo $this->Form->hidden('CartsItem.' . $key . '.model');
									echo $this->Form->hidden('CartsItem.' . $key . '.foreign_key');
								?>
							</td>
							<td>
								<?php
									echo $this->Html->link(__d('cart', 'eliminar'),
										array(
											'action' => 'remove_item',
											'id' => $item['foreign_key'],
											'model' => $item['model']),
										array(
											'class' => ''));
								?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<td><?php echo __d('cart', 'Total'); ?>
						<td colspan="3"><?php echo CakeNumber::currency($cart['Cart']['total']); ?></td>
					</tr>
				</tfoot>
			</table>
		<?php
			echo $this->Form->submit(__d('cart', 'Actualizar carrito'), array(
				'class' => 'btn btn-primary'));
			echo $this->Form->end();

			echo $this->Html->link(__d('ordena', 'Ordenar!', 'Checkout'), array(
				'controller' => 'checkout',
				'action' => 'index'));
		?>

	<?php else : ?>

		<p><?php echo __d('cart', 'Your cart is empty.'); ?></p>

	<?php endif; ?>
</div>