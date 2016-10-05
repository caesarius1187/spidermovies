<div class="encuadrealicuotas view">
<h2><?php echo __('Encuadrealicuota'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($encuadrealicuota['Encuadrealicuota']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Actividadcliente'); ?></dt>
		<dd>
			<?php echo $this->Html->link($encuadrealicuota['Actividadcliente']['id'], array('controller' => 'actividadclientes', 'action' => 'view', $encuadrealicuota['Actividadcliente']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Impcliprovincia'); ?></dt>
		<dd>
			<?php echo $this->Html->link($encuadrealicuota['Impcliprovincia']['provincia_id'], array('controller' => 'impcliprovincias', 'action' => 'view', $encuadrealicuota['Impcliprovincia']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Alicuota'); ?></dt>
		<dd>
			<?php echo h($encuadrealicuota['Encuadrealicuota']['alicuota']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Concepto'); ?></dt>
		<dd>
			<?php echo h($encuadrealicuota['Encuadrealicuota']['concepto']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Desde'); ?></dt>
		<dd>
			<?php echo h($encuadrealicuota['Encuadrealicuota']['desde']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Hasta'); ?></dt>
		<dd>
			<?php echo h($encuadrealicuota['Encuadrealicuota']['hasta']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Encuadrealicuota'), array('action' => 'edit', $encuadrealicuota['Encuadrealicuota']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Encuadrealicuota'), array('action' => 'delete', $encuadrealicuota['Encuadrealicuota']['id']), null, __('Are you sure you want to delete # %s?', $encuadrealicuota['Encuadrealicuota']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Encuadrealicuotas'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Encuadrealicuota'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Actividadclientes'), array('controller' => 'actividadclientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Actividadcliente'), array('controller' => 'actividadclientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Impcliprovincias'), array('controller' => 'impcliprovincias', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impcliprovincia'), array('controller' => 'impcliprovincias', 'action' => 'add')); ?> </li>
	</ul>
</div>
