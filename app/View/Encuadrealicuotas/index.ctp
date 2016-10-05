<div class="encuadrealicuotas index">
	<h2><?php echo __('Encuadrealicuotas'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('actividadcliente_id'); ?></th>
			<th><?php echo $this->Paginator->sort('impcliprovincia_id'); ?></th>
			<th><?php echo $this->Paginator->sort('alicuota'); ?></th>
			<th><?php echo $this->Paginator->sort('concepto'); ?></th>
			<th><?php echo $this->Paginator->sort('desde'); ?></th>
			<th><?php echo $this->Paginator->sort('hasta'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($encuadrealicuotas as $encuadrealicuota): ?>
	<tr>
		<td><?php echo h($encuadrealicuota['Encuadrealicuota']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($encuadrealicuota['Actividadcliente']['id'], array('controller' => 'actividadclientes', 'action' => 'view', $encuadrealicuota['Actividadcliente']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($encuadrealicuota['Impcliprovincia']['provincia_id'], array('controller' => 'impcliprovincias', 'action' => 'view', $encuadrealicuota['Impcliprovincia']['id'])); ?>
		</td>
		<td><?php echo h($encuadrealicuota['Encuadrealicuota']['alicuota']); ?>&nbsp;</td>
		<td><?php echo h($encuadrealicuota['Encuadrealicuota']['concepto']); ?>&nbsp;</td>
		<td><?php echo h($encuadrealicuota['Encuadrealicuota']['desde']); ?>&nbsp;</td>
		<td><?php echo h($encuadrealicuota['Encuadrealicuota']['hasta']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $encuadrealicuota['Encuadrealicuota']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $encuadrealicuota['Encuadrealicuota']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $encuadrealicuota['Encuadrealicuota']['id']), null, __('Are you sure you want to delete # %s?', $encuadrealicuota['Encuadrealicuota']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Encuadrealicuota'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Actividadclientes'), array('controller' => 'actividadclientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Actividadcliente'), array('controller' => 'actividadclientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Impcliprovincias'), array('controller' => 'impcliprovincias', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impcliprovincia'), array('controller' => 'impcliprovincias', 'action' => 'add')); ?> </li>
	</ul>
</div>
