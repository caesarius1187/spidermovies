<h2><?php echo __d('cart', 'Payment Methods'); ?></h2>
<p>
	<?php echo __d('cart', 'Please choose your payment method.'); ?>
</p>
<?php if (!empty($paymentMethods)) : ?>
	<?php echo $this->Form->create(); ?>
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
	<?php echo $this->Form->submit(__d('cart', 'Submit')); ?>
	<?php echo $this->Form->end(); ?>
<?php endif; ?>