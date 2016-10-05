<div class="domicilios view">
<h2><?php echo __('Domicilio'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($domicilio['Domicilio']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cliente'); ?></dt>
		<dd>
			<?php echo $this->Html->link($domicilio['Cliente']['nombre'], array('controller' => 'clientes', 'action' => 'view', $domicilio['Cliente']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipo'); ?></dt>
		<dd>
			<?php echo h($domicilio['Domicilio']['tipo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Sede'); ?></dt>
		<dd>
			<?php echo h($domicilio['Domicilio']['sede']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombrefantasia'); ?></dt>
		<dd>
			<?php echo h($domicilio['Domicilio']['nombrefantasia']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Puntodeventa'); ?></dt>
		<dd>
			<?php echo h($domicilio['Domicilio']['puntodeventa']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Localidade'); ?></dt>
		<dd>
			<?php echo $this->Html->link($domicilio['Localidade']['nombre'], array('controller' => 'localidades', 'action' => 'view', $domicilio['Localidade']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Calle'); ?></dt>
		<dd>
			<?php echo h($domicilio['Domicilio']['calle']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Numero'); ?></dt>
		<dd>
			<?php echo h($domicilio['Domicilio']['numero']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Piso'); ?></dt>
		<dd>
			<?php echo h($domicilio['Domicilio']['piso']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Ofidepto'); ?></dt>
		<dd>
			<?php echo h($domicilio['Domicilio']['ofidepto']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Ruta'); ?></dt>
		<dd>
			<?php echo h($domicilio['Domicilio']['ruta']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Kilometro'); ?></dt>
		<dd>
			<?php echo h($domicilio['Domicilio']['kilometro']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Torre'); ?></dt>
		<dd>
			<?php echo h($domicilio['Domicilio']['torre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Manzana'); ?></dt>
		<dd>
			<?php echo h($domicilio['Domicilio']['manzana']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Entrecalles'); ?></dt>
		<dd>
			<?php echo h($domicilio['Domicilio']['entrecalles']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Codigopostal'); ?></dt>
		<dd>
			<?php echo h($domicilio['Domicilio']['codigopostal']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Telefono'); ?></dt>
		<dd>
			<?php echo h($domicilio['Domicilio']['telefono']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Movil'); ?></dt>
		<dd>
			<?php echo h($domicilio['Domicilio']['movil']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fax'); ?></dt>
		<dd>
			<?php echo h($domicilio['Domicilio']['fax']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($domicilio['Domicilio']['email']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Personacontacto'); ?></dt>
		<dd>
			<?php echo h($domicilio['Domicilio']['personacontacto']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Observaciones'); ?></dt>
		<dd>
			<?php echo h($domicilio['Domicilio']['observaciones']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Domicilio'), array('action' => 'edit', $domicilio['Domicilio']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Domicilio'), array('action' => 'delete', $domicilio['Domicilio']['id']), null, __('Are you sure you want to delete # %s?', $domicilio['Domicilio']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Domicilios'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Domicilio'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Localidades'), array('controller' => 'localidades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Localidade'), array('controller' => 'localidades', 'action' => 'add')); ?> </li>
	</ul>
</div>
