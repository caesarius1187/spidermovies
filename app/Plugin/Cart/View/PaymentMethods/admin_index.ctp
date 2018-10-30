<h2><?php echo __d('cart', 'Payment Methods'); ?></h2>

<?php if (!empty($paymentMethods)) : ?>
	<ul class="payment-method-list">
		<?php foreach ($paymentMethods as $method) : ?>
			<li>
				<h3><?php echo h($method['PaymentMethod']['name']); ?></h3>
				<div class="description">
					<?php
						echo h($method['PaymentMethod']['description']);
					?>
				</div>
				<?php ?>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>