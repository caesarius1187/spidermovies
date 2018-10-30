<h2><?php echo __d('cart', 'View Cart'); ?></h2>
<?php debug($cart); ?>

<h3><?php echo __d('cart', 'Items'); ?></h3>
<?php if (!empty($cart['CartsItem'])) : ?>
	<?php foreach ($cart['CartsItem'] as $item) : ?>

	<?php endforeach; ?>
<?php endif; ?>