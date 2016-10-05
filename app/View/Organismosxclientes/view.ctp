<div class="organismosxclientes view">
<h2><?php echo __('Organismosxcliente'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($organismosxcliente['Organismosxcliente']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cliente'); ?></dt>
		<dd>
			<?php echo $this->Html->link($organismosxcliente['Cliente']['nombre'], array('controller' => 'clientes', 'action' => 'view', $organismosxcliente['Cliente']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipoorganismo'); ?></dt>
		<dd>
			<?php echo h($organismosxcliente['Organismosxcliente']['tipoorganismo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Usuario'); ?></dt>
		<dd>
			<?php echo h($organismosxcliente['Organismosxcliente']['usuario']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Clave'); ?></dt>
		<dd>
			<?php echo h($organismosxcliente['Organismosxcliente']['clave']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Codigoactividad'); ?></dt>
		<dd>
			<?php echo h($organismosxcliente['Organismosxcliente']['codigoactividad']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Descripcionactividad'); ?></dt>
		<dd>
			<?php echo h($organismosxcliente['Organismosxcliente']['descripcionactividad']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Descripcion'); ?></dt>
		<dd>
			<?php echo h($organismosxcliente['Organismosxcliente']['descripcion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Vencimiento'); ?></dt>
		<dd>
			<?php echo h($organismosxcliente['Organismosxcliente']['vencimiento']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Expediente'); ?></dt>
		<dd>
			<?php echo h($organismosxcliente['Organismosxcliente']['expediente']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Estado'); ?></dt>
		<dd>
			<?php echo h($organismosxcliente['Organismosxcliente']['estado']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Observaciones'); ?></dt>
		<dd>
			<?php echo h($organismosxcliente['Organismosxcliente']['observaciones']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Organismosxcliente'), array('action' => 'edit', $organismosxcliente['Organismosxcliente']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Organismosxcliente'), array('action' => 'delete', $organismosxcliente['Organismosxcliente']['id']), null, __('Are you sure you want to delete # %s?', $organismosxcliente['Organismosxcliente']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Organismosxclientes'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Organismosxcliente'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
	</ul>
</div>
