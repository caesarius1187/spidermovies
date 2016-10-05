<div class="vencimientos view">
<h2><?php echo __('Vencimiento'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($vencimiento['Vencimiento']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Periodo'); ?></dt>
		<dd>
			<?php echo h($vencimiento['Vencimiento']['periodo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Anio'); ?></dt>
		<dd>
			<?php echo h($vencimiento['Vencimiento']['anio']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Dia'); ?></dt>
		<dd>
			<?php echo h($vencimiento['Vencimiento']['dia']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Desde'); ?></dt>
		<dd>
			<?php echo h($vencimiento['Vencimiento']['desde']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Hasta'); ?></dt>
		<dd>
			<?php echo h($vencimiento['Vencimiento']['hasta']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Impuesto'); ?></dt>
		<dd>
			<?php echo $this->Html->link($vencimiento['Impuesto']['id'], array('controller' => 'impuestos', 'action' => 'view', $vencimiento['Impuesto']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Vencimiento'), array('action' => 'edit', $vencimiento['Vencimiento']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Vencimiento'), array('action' => 'delete', $vencimiento['Vencimiento']['id']), null, __('Are you sure you want to delete # %s?', $vencimiento['Vencimiento']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Vencimientos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Vencimiento'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Impuestos'), array('controller' => 'impuestos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impuesto'), array('controller' => 'impuestos', 'action' => 'add')); ?> </li>
	</ul>
</div>
