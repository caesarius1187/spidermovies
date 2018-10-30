<?php
if (!empty($paymentMethods)) : ?>
	<ul class="payment-methods">
		<?php 

		foreach ($paymentMethods as $paymentMethod) : ?>
			<li>
				<?php //debug($paymentMethod); ?>
				<h3>
					<?php
						echo $this->Html->link($paymentMethod['PaymentMethod']['name'], array(
							'controller' => 'checkout', 'action' => 'checkout', $paymentMethod['PaymentMethod']['class']));
					?>
				</h3>
				<?php
					if (!empty($paymentMethod['PaymentMethod']['logo'])) :
						$image = $this->Html->image($paymentMethod['PaymentMethod']['logo'], array(
							'alt' => $paymentMethod['PaymentMethod']['name']));
					endif;
				?>
				<?php echo $paymentMethod['PaymentMethod']['description']; ?>
			</li>
		<?php endforeach;?>
	</ul>
<?php endif; ?>