<div class="conveniocolectivotrabajos index">
	<h2><?php echo __('Convenio colectivo trabajos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th>impuesto_id</th>
			<th>nombre</th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($conveniocolectivotrabajos as $conveniocolectivotrabajo): ?>
	<tr>
		<td>
			<?php //echo $this->Html->link($conveniocolectivotrabajo['Impuesto']['nombre'], array('controller' => 'impuestos', 'action' => 'view', $conveniocolectivotrabajo['Impuesto']['id'])); ?>
			<?php echo $conveniocolectivotrabajo['Impuesto']['nombre']; ?>
		</td>
		<td><?php echo h($conveniocolectivotrabajo['Conveniocolectivotrabajo']['nombre']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Ver'), array('action' => 'view', $conveniocolectivotrabajo['Conveniocolectivotrabajo']['id'])); ?>		
		</td>
	</tr>
<?php endforeach; ?>
	</table>
</div>