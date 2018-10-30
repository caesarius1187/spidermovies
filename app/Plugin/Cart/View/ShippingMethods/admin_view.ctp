<div class="shippingMethods view">
<h2><?php  echo __d('cart', 'Shipping Method');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('cart', 'Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shippingMethod['ShippingMethod']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('cart', 'Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shippingMethod['ShippingMethod']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('cart', 'Price'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shippingMethod['ShippingMethod']['price']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('cart', 'Currency'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shippingMethod['ShippingMethod']['currency']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('cart', 'Position'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shippingMethod['ShippingMethod']['position']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('cart', 'Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shippingMethod['ShippingMethod']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('cart', 'Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shippingMethod['ShippingMethod']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__d('cart', 'Edit Shipping Method'), array('action' => 'edit', $shippingMethod['ShippingMethod']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__d('cart', 'Delete Shipping Method'), array('action' => 'delete', $shippingMethod['ShippingMethod']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__d('cart', 'List Shipping Methods'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__d('cart', 'New Shipping Method'), array('action' => 'add')); ?> </li>
	</ul>
</div>
