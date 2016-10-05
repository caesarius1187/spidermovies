<div class="impcliprovincias view">
<h2><?php echo __('Impcliprovincia'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($impcliprovincia['Impcliprovincia']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Partido'); ?></dt>
		<dd>
			<?php echo $this->Html->link($impcliprovincia['Partido']['nombre'], array('controller' => 'partidos', 'action' => 'view', $impcliprovincia['Partido']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Ano'); ?></dt>
		<dd>
			<?php echo h($impcliprovincia['Impcliprovincia']['ano']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Coeficiente'); ?></dt>
		<dd>
			<?php echo h($impcliprovincia['Impcliprovincia']['coeficiente']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Ejercicio'); ?></dt>
		<dd>
			<?php echo h($impcliprovincia['Impcliprovincia']['ejercicio']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Impcliprovincia'), array('action' => 'edit', $impcliprovincia['Impcliprovincia']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Impcliprovincia'), array('action' => 'delete', $impcliprovincia['Impcliprovincia']['id']), null, __('Are you sure you want to delete # %s?', $impcliprovincia['Impcliprovincia']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Impcliprovincias'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impcliprovincia'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Partidos'), array('controller' => 'partidos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Partido'), array('controller' => 'partidos', 'action' => 'add')); ?> </li>
	</ul>
</div>
