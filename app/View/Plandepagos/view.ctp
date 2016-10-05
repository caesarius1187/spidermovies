<div class="plandepagos view">
<h2><?php echo __('Plandepago'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($plandepago['Plandepago']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Impcli'); ?></dt>
		<dd>
			<?php echo $this->Html->link($plandepago['Impcli']['id'], array('controller' => 'impclis', 'action' => 'view', $plandepago['Impcli']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($plandepago['User']['nombre'], array('controller' => 'users', 'action' => 'view', $plandepago['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Item'); ?></dt>
		<dd>
			<?php echo h($plandepago['Plandepago']['item']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Plan'); ?></dt>
		<dd>
			<?php echo h($plandepago['Plandepago']['plan']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cbu'); ?></dt>
		<dd>
			<?php echo h($plandepago['Plandepago']['cbu']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Periodo'); ?></dt>
		<dd>
			<?php echo h($plandepago['Plandepago']['periodo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fchvto'); ?></dt>
		<dd>
			<?php echo h($plandepago['Plandepago']['fchvto']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Montovto'); ?></dt>
		<dd>
			<?php echo h($plandepago['Plandepago']['montovto']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fchrealizado'); ?></dt>
		<dd>
			<?php echo h($plandepago['Plandepago']['fchrealizado']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Montorealizado'); ?></dt>
		<dd>
			<?php echo h($plandepago['Plandepago']['montorealizado']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Monc'); ?></dt>
		<dd>
			<?php echo h($plandepago['Plandepago']['monc']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Estado'); ?></dt>
		<dd>
			<?php echo h($plandepago['Plandepago']['estado']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($plandepago['Plandepago']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Descripcion'); ?></dt>
		<dd>
			<?php echo h($plandepago['Plandepago']['descripcion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Publico'); ?></dt>
		<dd>
			<?php echo h($plandepago['Plandepago']['publico']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Archnombre'); ?></dt>
		<dd>
			<?php echo h($plandepago['Plandepago']['archnombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Archfecha'); ?></dt>
		<dd>
			<?php echo h($plandepago['Plandepago']['archfecha']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Informar'); ?></dt>
		<dd>
			<?php echo h($plandepago['Plandepago']['informar']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Plandepago'), array('action' => 'edit', $plandepago['Plandepago']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Plandepago'), array('action' => 'delete', $plandepago['Plandepago']['id']), null, __('Are you sure you want to delete # %s?', $plandepago['Plandepago']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Plandepagos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Plandepago'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Impclis'), array('controller' => 'impclis', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impcli'), array('controller' => 'impclis', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
