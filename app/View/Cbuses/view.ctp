<div class="cbuses view">
<h2><?php echo __('Cbus'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($cbus['Cbus']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Bancosysindicato'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cbus['Bancosysindicato'][''], array('controller' => 'bancosysindicatos', 'action' => 'view', $cbus['Bancosysindicato']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipo'); ?></dt>
		<dd>
			<?php echo h($cbus['Cbus']['tipo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Numero'); ?></dt>
		<dd>
			<?php echo h($cbus['Cbus']['numero']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cbu'); ?></dt>
		<dd>
			<?php echo h($cbus['Cbus']['cbu']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Cbus'), array('action' => 'edit', $cbus['Cbus']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Cbus'), array('action' => 'delete', $cbus['Cbus']['id']), null, __('Are you sure you want to delete # %s?', $cbus['Cbus']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Cbuses'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cbus'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Bancosysindicatos'), array('controller' => 'bancosysindicatos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Bancosysindicato'), array('controller' => 'bancosysindicatos', 'action' => 'add')); ?> </li>
	</ul>
</div>
