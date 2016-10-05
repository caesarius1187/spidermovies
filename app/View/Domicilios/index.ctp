<div class="domicilios index">
	<h2><?php echo __('Domicilios'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('cliente_id'); ?></th>
			<th><?php echo $this->Paginator->sort('tipo'); ?></th>
			<th><?php echo $this->Paginator->sort('sede'); ?></th>
			<th><?php echo $this->Paginator->sort('nombrefantasia'); ?></th>
			<th><?php echo $this->Paginator->sort('puntodeventa'); ?></th>
			<th><?php echo $this->Paginator->sort('localidade_id'); ?></th>
			<th><?php echo $this->Paginator->sort('calle'); ?></th>
			<th><?php echo $this->Paginator->sort('numero'); ?></th>
			<th><?php echo $this->Paginator->sort('piso'); ?></th>
			<th><?php echo $this->Paginator->sort('ofidepto'); ?></th>
			<th><?php echo $this->Paginator->sort('ruta'); ?></th>
			<th><?php echo $this->Paginator->sort('kilometro'); ?></th>
			<th><?php echo $this->Paginator->sort('torre'); ?></th>
			<th><?php echo $this->Paginator->sort('manzana'); ?></th>
			<th><?php echo $this->Paginator->sort('entrecalles'); ?></th>
			<th><?php echo $this->Paginator->sort('codigopostal'); ?></th>
			<th><?php echo $this->Paginator->sort('telefono'); ?></th>
			<th><?php echo $this->Paginator->sort('movil'); ?></th>
			<th><?php echo $this->Paginator->sort('fax'); ?></th>
			<th><?php echo $this->Paginator->sort('email'); ?></th>
			<th><?php echo $this->Paginator->sort('personacontacto'); ?></th>
			<th><?php echo $this->Paginator->sort('observaciones'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($domicilios as $domicilio): ?>
	<tr>
		<td><?php echo h($domicilio['Domicilio']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($domicilio['Cliente']['nombre'], array('controller' => 'clientes', 'action' => 'view', $domicilio['Cliente']['id'])); ?>
		</td>
		<td><?php echo h($domicilio['Domicilio']['tipo']); ?>&nbsp;</td>
		<td><?php echo h($domicilio['Domicilio']['sede']); ?>&nbsp;</td>
		<td><?php echo h($domicilio['Domicilio']['nombrefantasia']); ?>&nbsp;</td>
		<td><?php echo h($domicilio['Domicilio']['puntodeventa']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($domicilio['Localidade']['nombre'], array('controller' => 'localidades', 'action' => 'view', $domicilio['Localidade']['id'])); ?>
		</td>
		<td><?php echo h($domicilio['Domicilio']['calle']); ?>&nbsp;</td>
		<td><?php echo h($domicilio['Domicilio']['numero']); ?>&nbsp;</td>
		<td><?php echo h($domicilio['Domicilio']['piso']); ?>&nbsp;</td>
		<td><?php echo h($domicilio['Domicilio']['ofidepto']); ?>&nbsp;</td>
		<td><?php echo h($domicilio['Domicilio']['ruta']); ?>&nbsp;</td>
		<td><?php echo h($domicilio['Domicilio']['kilometro']); ?>&nbsp;</td>
		<td><?php echo h($domicilio['Domicilio']['torre']); ?>&nbsp;</td>
		<td><?php echo h($domicilio['Domicilio']['manzana']); ?>&nbsp;</td>
		<td><?php echo h($domicilio['Domicilio']['entrecalles']); ?>&nbsp;</td>
		<td><?php echo h($domicilio['Domicilio']['codigopostal']); ?>&nbsp;</td>
		<td><?php echo h($domicilio['Domicilio']['telefono']); ?>&nbsp;</td>
		<td><?php echo h($domicilio['Domicilio']['movil']); ?>&nbsp;</td>
		<td><?php echo h($domicilio['Domicilio']['fax']); ?>&nbsp;</td>
		<td><?php echo h($domicilio['Domicilio']['email']); ?>&nbsp;</td>
		<td><?php echo h($domicilio['Domicilio']['personacontacto']); ?>&nbsp;</td>
		<td><?php echo h($domicilio['Domicilio']['observaciones']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $domicilio['Domicilio']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $domicilio['Domicilio']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $domicilio['Domicilio']['id']), null, __('Are you sure you want to delete # %s?', $domicilio['Domicilio']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Domicilio'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Localidades'), array('controller' => 'localidades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Localidade'), array('controller' => 'localidades', 'action' => 'add')); ?> </li>
	</ul>
</div>
