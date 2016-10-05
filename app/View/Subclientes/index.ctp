<div class="personasrelacionadas index">
	<h2><?php echo __('Personasrelacionadas'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('cliente_id'); ?></th>
			<th><?php echo $this->Paginator->sort('tipo'); ?></th>
			<th><?php echo $this->Paginator->sort('Vto. del Mandat'); ?></th>
			<th><?php echo $this->Paginator->sort('porcentajeparticipacion'); ?></th>
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
	<?php foreach ($personasrelacionadas as $personasrelacionada): ?>
	<tr>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($personasrelacionada['Cliente']['nombre'], array('controller' => 'clientes', 'action' => 'view', $personasrelacionada['Cliente']['id'])); ?>
		</td>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['tipo']); ?>&nbsp;</td>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['Vto. del Mandat']); ?>&nbsp;</td>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['porcentajeparticipacion']); ?>&nbsp;</td>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['sede']); ?>&nbsp;</td>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['nombrefantasia']); ?>&nbsp;</td>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['puntodeventa']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($personasrelacionada['Localidade']['nombre'], array('controller' => 'localidades', 'action' => 'view', $personasrelacionada['Localidade']['id'])); ?>
		</td>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['calle']); ?>&nbsp;</td>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['numero']); ?>&nbsp;</td>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['piso']); ?>&nbsp;</td>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['ofidepto']); ?>&nbsp;</td>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['ruta']); ?>&nbsp;</td>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['kilometro']); ?>&nbsp;</td>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['torre']); ?>&nbsp;</td>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['manzana']); ?>&nbsp;</td>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['entrecalles']); ?>&nbsp;</td>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['codigopostal']); ?>&nbsp;</td>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['telefono']); ?>&nbsp;</td>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['movil']); ?>&nbsp;</td>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['fax']); ?>&nbsp;</td>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['email']); ?>&nbsp;</td>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['personacontacto']); ?>&nbsp;</td>
		<td><?php echo h($personasrelacionada['Personasrelacionada']['observaciones']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $personasrelacionada['Personasrelacionada']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $personasrelacionada['Personasrelacionada']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $personasrelacionada['Personasrelacionada']['id']), null, __('Are you sure you want to delete # %s?', $personasrelacionada['Personasrelacionada']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Personasrelacionada'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Localidades'), array('controller' => 'localidades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Localidade'), array('controller' => 'localidades', 'action' => 'add')); ?> </li>
	</ul>
</div>
