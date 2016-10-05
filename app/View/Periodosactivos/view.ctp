<div class="periodosactivos view">
<h2><?php echo __('Periodosactivo'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($periodosactivo['Periodosactivo']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Impcli'); ?></dt>
		<dd>
			<?php echo $this->Html->link($periodosactivo['Impcli']['id'], array('controller' => 'impclis', 'action' => 'view', $periodosactivo['Impcli']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Desde'); ?></dt>
		<dd>
			<?php echo h($periodosactivo['Periodosactivo']['desde']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Hasta'); ?></dt>
		<dd>
			<?php echo h($periodosactivo['Periodosactivo']['hasta']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Periodosactivo'), array('action' => 'edit', $periodosactivo['Periodosactivo']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Periodosactivo'), array('action' => 'delete', $periodosactivo['Periodosactivo']['id']), null, __('Are you sure you want to delete # %s?', $periodosactivo['Periodosactivo']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Periodosactivos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Periodosactivo'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Impclis'), array('controller' => 'impclis', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impcli'), array('controller' => 'impclis', 'action' => 'add')); ?> </li>
	</ul>
</div>
