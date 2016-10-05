<div class="conceptosrestantes index">
	<h2><?php echo __('Conceptosrestantes'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('partido_id'); ?></th>
			<th><?php echo $this->Paginator->sort('cliente_id'); ?></th>
			<th><?php echo $this->Paginator->sort('impcli_id'); ?></th>
			<th><?php echo $this->Paginator->sort('periodo'); ?></th>
			<th><?php echo $this->Paginator->sort('comprobante_id'); ?></th>
			<th><?php echo $this->Paginator->sort('rectificativa'); ?></th>
			<th><?php echo $this->Paginator->sort('numerocomprobante'); ?></th>
			<th><?php echo $this->Paginator->sort('razonsocial'); ?></th>
			<th><?php echo $this->Paginator->sort('monto'); ?></th>
			<th><?php echo $this->Paginator->sort('montoretenido'); ?></th>
			<th><?php echo $this->Paginator->sort('cuit'); ?></th>
			<th><?php echo $this->Paginator->sort('fecha'); ?></th>
			<th><?php echo $this->Paginator->sort('conceptostipo_id'); ?></th>
			<th><?php echo $this->Paginator->sort('numerodespachoaduanero'); ?></th>
			<th><?php echo $this->Paginator->sort('anticipo'); ?></th>
			<th><?php echo $this->Paginator->sort('cbu'); ?></th>
			<th><?php echo $this->Paginator->sort('tipocuenta'); ?></th>
			<th><?php echo $this->Paginator->sort('tipomoneda'); ?></th>
			<th><?php echo $this->Paginator->sort('agente'); ?></th>
			<th><?php echo $this->Paginator->sort('enterecaudador'); ?></th>
			<th><?php echo $this->Paginator->sort('regimen'); ?></th>
			<th><?php echo $this->Paginator->sort('descripcion'); ?></th>
			<th><?php echo $this->Paginator->sort('numeropadron'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($conceptosrestantes as $conceptosrestante): ?>
	<tr>
		<td><?php echo h($conceptosrestante['Conceptosrestante']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($conceptosrestante['Partido']['codname'], array('controller' => 'partidos', 'action' => 'view', $conceptosrestante['Partido']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($conceptosrestante['Cliente']['nombre'], array('controller' => 'clientes', 'action' => 'view', $conceptosrestante['Cliente']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($conceptosrestante['Impcli']['id'], array('controller' => 'impclis', 'action' => 'view', $conceptosrestante['Impcli']['id'])); ?>
		</td>
		<td><?php echo h($conceptosrestante['Conceptosrestante']['periodo']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($conceptosrestante['Comprobante']['nombre'], array('controller' => 'comprobantes', 'action' => 'view', $conceptosrestante['Comprobante']['id'])); ?>
		</td>
		<td><?php echo h($conceptosrestante['Conceptosrestante']['rectificativa']); ?>&nbsp;</td>
		<td><?php echo h($conceptosrestante['Conceptosrestante']['numerocomprobante']); ?>&nbsp;</td>
		<td><?php echo h($conceptosrestante['Conceptosrestante']['razonsocial']); ?>&nbsp;</td>
		<td><?php echo h($conceptosrestante['Conceptosrestante']['monto']); ?>&nbsp;</td>
		<td><?php echo h($conceptosrestante['Conceptosrestante']['montoretenido']); ?>&nbsp;</td>
		<td><?php echo h($conceptosrestante['Conceptosrestante']['cuit']); ?>&nbsp;</td>
		<td><?php echo h($conceptosrestante['Conceptosrestante']['fecha']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($conceptosrestante['Conceptostipo']['nombre'], array('controller' => 'conceptostipos', 'action' => 'view', $conceptosrestante['Conceptostipo']['id'])); ?>
		</td>
		<td><?php echo h($conceptosrestante['Conceptosrestante']['numerodespachoaduanero']); ?>&nbsp;</td>
		<td><?php echo h($conceptosrestante['Conceptosrestante']['anticipo']); ?>&nbsp;</td>
		<td><?php echo h($conceptosrestante['Conceptosrestante']['cbu']); ?>&nbsp;</td>
		<td><?php echo h($conceptosrestante['Conceptosrestante']['tipocuenta']); ?>&nbsp;</td>
		<td><?php echo h($conceptosrestante['Conceptosrestante']['tipomoneda']); ?>&nbsp;</td>
		<td><?php echo h($conceptosrestante['Conceptosrestante']['agente']); ?>&nbsp;</td>
		<td><?php echo h($conceptosrestante['Conceptosrestante']['enterecaudador']); ?>&nbsp;</td>
		<td><?php echo h($conceptosrestante['Conceptosrestante']['regimen']); ?>&nbsp;</td>
		<td><?php echo h($conceptosrestante['Conceptosrestante']['descripcion']); ?>&nbsp;</td>
		<td><?php echo h($conceptosrestante['Conceptosrestante']['numeropadron']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $conceptosrestante['Conceptosrestante']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $conceptosrestante['Conceptosrestante']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $conceptosrestante['Conceptosrestante']['id']), null, __('Are you sure you want to delete # %s?', $conceptosrestante['Conceptosrestante']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Conceptosrestante'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Partidos'), array('controller' => 'partidos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Partido'), array('controller' => 'partidos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Impclis'), array('controller' => 'impclis', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impcli'), array('controller' => 'impclis', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Comprobantes'), array('controller' => 'comprobantes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Comprobante'), array('controller' => 'comprobantes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Conceptostipos'), array('controller' => 'conceptostipos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Conceptostipo'), array('controller' => 'conceptostipos', 'action' => 'add')); ?> </li>
	</ul>
</div>
