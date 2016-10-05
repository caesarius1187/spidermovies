<div class="organismosxclientes index">
	<h2><?php echo __('Organismosxclientes'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('cliente_id'); ?></th>
			<th><?php echo $this->Paginator->sort('tipoorganismo'); ?></th>
			<th><?php echo $this->Paginator->sort('usuario'); ?></th>
			<th><?php echo $this->Paginator->sort('clave'); ?></th>
			<th><?php echo $this->Paginator->sort('codigoactividad'); ?></th>
			<th><?php echo $this->Paginator->sort('descripcionactividad'); ?></th>
			<th><?php echo $this->Paginator->sort('descripcion'); ?></th>
			<th><?php echo $this->Paginator->sort('vencimiento'); ?></th>
			<th><?php echo $this->Paginator->sort('expediente'); ?></th>
			<th><?php echo $this->Paginator->sort('estado'); ?></th>
			<th><?php echo $this->Paginator->sort('observaciones'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($organismosxclientes as $organismosxcliente): ?>
	<tr>
		<td><?php echo h($organismosxcliente['Organismosxcliente']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($organismosxcliente['Cliente']['nombre'], array('controller' => 'clientes', 'action' => 'view', $organismosxcliente['Cliente']['id'])); ?>
		</td>
		<td><?php echo h($organismosxcliente['Organismosxcliente']['tipoorganismo']); ?>&nbsp;</td>
		<td><?php echo h($organismosxcliente['Organismosxcliente']['usuario']); ?>&nbsp;</td>
		<td><?php echo h($organismosxcliente['Organismosxcliente']['clave']); ?>&nbsp;</td>
		<td><?php echo h($organismosxcliente['Organismosxcliente']['codigoactividad']); ?>&nbsp;</td>
		<td><?php echo h($organismosxcliente['Organismosxcliente']['descripcionactividad']); ?>&nbsp;</td>
		<td><?php echo h($organismosxcliente['Organismosxcliente']['descripcion']); ?>&nbsp;</td>
		<td><?php echo h($organismosxcliente['Organismosxcliente']['vencimiento']); ?>&nbsp;</td>
		<td><?php echo h($organismosxcliente['Organismosxcliente']['expediente']); ?>&nbsp;</td>
		<td><?php echo h($organismosxcliente['Organismosxcliente']['estado']); ?>&nbsp;</td>
		<td><?php echo h($organismosxcliente['Organismosxcliente']['observaciones']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $organismosxcliente['Organismosxcliente']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $organismosxcliente['Organismosxcliente']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $organismosxcliente['Organismosxcliente']['id']), null, __('Are you sure you want to delete # %s?', $organismosxcliente['Organismosxcliente']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Organismosxcliente'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
	</ul>
</div>
