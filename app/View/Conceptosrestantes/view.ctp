<div class="conceptosrestantes view">
<h2><?php echo __('Conceptosrestante'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($conceptosrestante['Conceptosrestante']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Partido'); ?></dt>
		<dd>
			<?php echo $this->Html->link($conceptosrestante['Partido']['codname'], array('controller' => 'partidos', 'action' => 'view', $conceptosrestante['Partido']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cliente'); ?></dt>
		<dd>
			<?php echo $this->Html->link($conceptosrestante['Cliente']['nombre'], array('controller' => 'clientes', 'action' => 'view', $conceptosrestante['Cliente']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Impcli'); ?></dt>
		<dd>
			<?php echo $this->Html->link($conceptosrestante['Impcli']['id'], array('controller' => 'impclis', 'action' => 'view', $conceptosrestante['Impcli']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Periodo'); ?></dt>
		<dd>
			<?php echo h($conceptosrestante['Conceptosrestante']['periodo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Comprobante'); ?></dt>
		<dd>
			<?php echo $this->Html->link($conceptosrestante['Comprobante']['nombre'], array('controller' => 'comprobantes', 'action' => 'view', $conceptosrestante['Comprobante']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Rectificativa'); ?></dt>
		<dd>
			<?php echo h($conceptosrestante['Conceptosrestante']['rectificativa']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Numerocomprobante'); ?></dt>
		<dd>
			<?php echo h($conceptosrestante['Conceptosrestante']['numerocomprobante']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Razonsocial'); ?></dt>
		<dd>
			<?php echo h($conceptosrestante['Conceptosrestante']['razonsocial']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Monto'); ?></dt>
		<dd>
			<?php echo h($conceptosrestante['Conceptosrestante']['monto']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Montoretenido'); ?></dt>
		<dd>
			<?php echo h($conceptosrestante['Conceptosrestante']['montoretenido']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cuit'); ?></dt>
		<dd>
			<?php echo h($conceptosrestante['Conceptosrestante']['cuit']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fecha'); ?></dt>
		<dd>
			<?php echo h($conceptosrestante['Conceptosrestante']['fecha']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Conceptostipo'); ?></dt>
		<dd>
			<?php echo $this->Html->link($conceptosrestante['Conceptostipo']['nombre'], array('controller' => 'conceptostipos', 'action' => 'view', $conceptosrestante['Conceptostipo']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Numerodespachoaduanero'); ?></dt>
		<dd>
			<?php echo h($conceptosrestante['Conceptosrestante']['numerodespachoaduanero']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Anticipo'); ?></dt>
		<dd>
			<?php echo h($conceptosrestante['Conceptosrestante']['anticipo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cbu'); ?></dt>
		<dd>
			<?php echo h($conceptosrestante['Conceptosrestante']['cbu']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipocuenta'); ?></dt>
		<dd>
			<?php echo h($conceptosrestante['Conceptosrestante']['tipocuenta']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipomoneda'); ?></dt>
		<dd>
			<?php echo h($conceptosrestante['Conceptosrestante']['tipomoneda']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Agente'); ?></dt>
		<dd>
			<?php echo h($conceptosrestante['Conceptosrestante']['agente']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Enterecaudador'); ?></dt>
		<dd>
			<?php echo h($conceptosrestante['Conceptosrestante']['enterecaudador']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Regimen'); ?></dt>
		<dd>
			<?php echo h($conceptosrestante['Conceptosrestante']['regimen']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Descripcion'); ?></dt>
		<dd>
			<?php echo h($conceptosrestante['Conceptosrestante']['descripcion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Numeropadron'); ?></dt>
		<dd>
			<?php echo h($conceptosrestante['Conceptosrestante']['numeropadron']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Conceptosrestante'), array('action' => 'edit', $conceptosrestante['Conceptosrestante']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Conceptosrestante'), array('action' => 'delete', $conceptosrestante['Conceptosrestante']['id']), null, __('Are you sure you want to delete # %s?', $conceptosrestante['Conceptosrestante']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Conceptosrestantes'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Conceptosrestante'), array('action' => 'add')); ?> </li>
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
