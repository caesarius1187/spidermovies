<div class="valorrecibos view">
<h2><?php echo __('Valorrecibo'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($valorrecibo['Valorrecibo']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cctxconcepto'); ?></dt>
		<dd>
			<?php echo $this->Html->link($valorrecibo['Cctxconcepto']['id'], array('controller' => 'cctxconceptos', 'action' => 'view', $valorrecibo['Cctxconcepto']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Empleado'); ?></dt>
		<dd>
			<?php echo $this->Html->link($valorrecibo['Empleado']['nombre'], array('controller' => 'empleados', 'action' => 'view', $valorrecibo['Empleado']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Periodo'); ?></dt>
		<dd>
			<?php echo h($valorrecibo['Valorrecibo']['periodo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Unidademedida'); ?></dt>
		<dd>
			<?php echo h($valorrecibo['Valorrecibo']['unidademedida']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Valor'); ?></dt>
		<dd>
			<?php echo h($valorrecibo['Valorrecibo']['valor']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Valorrecibo'), array('action' => 'edit', $valorrecibo['Valorrecibo']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Valorrecibo'), array('action' => 'delete', $valorrecibo['Valorrecibo']['id']), null, __('Are you sure you want to delete # %s?', $valorrecibo['Valorrecibo']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Valorrecibos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Valorrecibo'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cctxconceptos'), array('controller' => 'cctxconceptos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cctxconcepto'), array('controller' => 'cctxconceptos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Empleados'), array('controller' => 'empleados', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Empleado'), array('controller' => 'empleados', 'action' => 'add')); ?> </li>
	</ul>
</div>
