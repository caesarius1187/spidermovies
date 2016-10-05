<div class="compras index">
	<h2><?php echo __('Compras'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('cliente_id'); ?></th>
			<th><?php echo $this->Paginator->sort('condicioniva'); ?></th>
			<th><?php echo $this->Paginator->sort('fecha'); ?></th>
			<th><?php echo $this->Paginator->sort('tipocomprobante'); ?></th>
			<th><?php echo $this->Paginator->sort('puntosdeventa_id'); ?></th>
			<th><?php echo $this->Paginator->sort('numerocomprobante'); ?></th>
			<th><?php echo $this->Paginator->sort('subcliente_id'); ?></th>
			<th><?php echo $this->Paginator->sort('localidade_id'); ?></th>
			<th><?php echo $this->Paginator->sort('tipogasto'); ?></th>
			<th><?php echo $this->Paginator->sort('imputacion'); ?></th>
			<th><?php echo $this->Paginator->sort('tipocredito'); ?></th>
			<th><?php echo $this->Paginator->sort('alicuota'); ?></th>
			<th><?php echo $this->Paginator->sort('neto'); ?></th>
			<th><?php echo $this->Paginator->sort('tipoiva'); ?></th>
			<th><?php echo $this->Paginator->sort('iva'); ?></th>
			<th><?php echo $this->Paginator->sort('ivapercep'); ?></th>
			<th><?php echo $this->Paginator->sort('iibbpercep'); ?></th>
			<th><?php echo $this->Paginator->sort('actvspercep'); ?></th>
			<th><?php echo $this->Paginator->sort('impinternos'); ?></th>
			<th><?php echo $this->Paginator->sort('impcombustible'); ?></th>
			<th><?php echo $this->Paginator->sort('nogravados'); ?></th>
			<th><?php echo $this->Paginator->sort('nogravadogeneral'); ?></th>
			<th><?php echo $this->Paginator->sort('total'); ?></th>
			<th><?php echo $this->Paginator->sort('asiento'); ?></th>
			<th><?php echo $this->Paginator->sort('periodo'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($compras as $compra): ?>
	<tr>
		<td><?php echo h($compra['Compra']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($compra['Cliente']['nombre'], array('controller' => 'clientes', 'action' => 'view', $compra['Cliente']['id'])); ?>
		</td>
		<td><?php echo h($compra['Compra']['condicioniva']); ?>&nbsp;</td>
		<td><?php echo h($compra['Compra']['fecha']); ?>&nbsp;</td>
		<td><?php echo h($compra['Compra']['tipocomprobante']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($compra['Puntosdeventa']['nombre'], array('controller' => 'puntosdeventas', 'action' => 'view', $compra['Puntosdeventa']['id'])); ?>
		</td>
		<td><?php echo h($compra['Compra']['numerocomprobante']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($compra['Subcliente']['nombre'], array('controller' => 'subclientes', 'action' => 'view', $compra['Subcliente']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($compra['Localidade']['nombre'], array('controller' => 'localidades', 'action' => 'view', $compra['Localidade']['id'])); ?>
		</td>
		<td><?php echo h($compra['Compra']['tipogasto']); ?>&nbsp;</td>
		<td><?php echo h($compra['Compra']['imputacion']); ?>&nbsp;</td>
		<td><?php echo h($compra['Compra']['tipocredito']); ?>&nbsp;</td>
		<td><?php echo h($compra['Compra']['alicuota']); ?>&nbsp;</td>
		<td><?php echo h($compra['Compra']['neto']); ?>&nbsp;</td>
		<td><?php echo h($compra['Compra']['tipoiva']); ?>&nbsp;</td>
		<td><?php echo h($compra['Compra']['iva']); ?>&nbsp;</td>
		<td><?php echo h($compra['Compra']['ivapercep']); ?>&nbsp;</td>
		<td><?php echo h($compra['Compra']['iibbpercep']); ?>&nbsp;</td>
		<td><?php echo h($compra['Compra']['actvspercep']); ?>&nbsp;</td>
		<td><?php echo h($compra['Compra']['impinternos']); ?>&nbsp;</td>
		<td><?php echo h($compra['Compra']['impcombustible']); ?>&nbsp;</td>
		<td><?php echo h($compra['Compra']['nogravados']); ?>&nbsp;</td>
		<td><?php echo h($compra['Compra']['nogravadogeneral']); ?>&nbsp;</td>
		<td><?php echo h($compra['Compra']['total']); ?>&nbsp;</td>
		<td><?php echo h($compra['Compra']['asiento']); ?>&nbsp;</td>
		<td><?php echo h($compra['Compra']['periodo']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $compra['Compra']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $compra['Compra']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $compra['Compra']['id']), null, __('Are you sure you want to delete # %s?', $compra['Compra']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Compra'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Puntosdeventas'), array('controller' => 'puntosdeventas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Puntosdeventa'), array('controller' => 'puntosdeventas', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Subclientes'), array('controller' => 'subclientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Subcliente'), array('controller' => 'subclientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Localidades'), array('controller' => 'localidades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Localidade'), array('controller' => 'localidades', 'action' => 'add')); ?> </li>
	</ul>
</div>
