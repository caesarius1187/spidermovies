<div class="shippingMethods index">
<h2><?php echo __d('cart', 'Shipping Methods');?></h2>
<p>
<?php
echo $this->Paginator->counter(array(
'format' => __d('cart', 'Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%')
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $this->Paginator->sort('id');?></th>
	<th><?php echo $this->Paginator->sort('name');?></th>
	<th><?php echo $this->Paginator->sort('price');?></th>
	<th><?php echo $this->Paginator->sort('currency');?></th>
	<th><?php echo $this->Paginator->sort('position');?></th>
	<th><?php echo $this->Paginator->sort('created');?></th>
	<th><?php echo $this->Paginator->sort('modified');?></th>
	<th class="actions"><?php echo __d('cart', 'Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($shippingMethods as $shippingMethod):
	$class = null;
	if ($i++ % 2 == 0) :
		$class = ' class="altrow"';
	endif;
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $shippingMethod['ShippingMethod']['id']; ?>
		</td>
		<td>
			<?php echo $shippingMethod['ShippingMethod']['name']; ?>
		</td>
		<td>
			<?php echo $shippingMethod['ShippingMethod']['price']; ?>
		</td>
		<td>
			<?php echo $shippingMethod['ShippingMethod']['currency']; ?>
		</td>
		<td>
			<?php echo $shippingMethod['ShippingMethod']['position']; ?>
		</td>
		<td>
			<?php echo $shippingMethod['ShippingMethod']['created']; ?>
		</td>
		<td>
			<?php echo $shippingMethod['ShippingMethod']['modified']; ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__d('cart', 'View'), array('action' => 'view', $shippingMethod['ShippingMethod']['id'])); ?>
			<?php echo $this->Html->link(__d('cart', 'Edit'), array('action' => 'edit', $shippingMethod['ShippingMethod']['id'])); ?>
			<?php echo $this->Html->link(__d('cart', 'Delete'), array('action' => 'delete', $shippingMethod['ShippingMethod']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
<?php echo $this->element('paging', array(), array('plugin' => 'Cart')); ?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__d('cart', 'New Shipping Method'), array('action' => 'add')); ?></li>
	</ul>
</div>
