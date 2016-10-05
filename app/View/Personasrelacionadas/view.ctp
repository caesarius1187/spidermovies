<div class="personasrelacionadas view">
<h2><?php echo __('Personasrelacionada'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cliente'); ?></dt>
		<dd>
			<?php echo $this->Html->link($personasrelacionada['Cliente']['nombre'], array('controller' => 'clientes', 'action' => 'view', $personasrelacionada['Cliente']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipo'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['tipo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Vto. Del Mandat'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['Vto. del Mandat']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Porcentajeparticipacion'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['porcentajeparticipacion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Sede'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['sede']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombrefantasia'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['nombrefantasia']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Puntodeventa'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['puntodeventa']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Localidade'); ?></dt>
		<dd>
			<?php echo $this->Html->link($personasrelacionada['Localidade']['nombre'], array('controller' => 'localidades', 'action' => 'view', $personasrelacionada['Localidade']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Calle'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['calle']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Numero'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['numero']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Piso'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['piso']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Ofidepto'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['ofidepto']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Ruta'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['ruta']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Kilometro'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['kilometro']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Torre'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['torre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Manzana'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['manzana']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Entrecalles'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['entrecalles']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Codigopostal'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['codigopostal']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Telefono'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['telefono']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Movil'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['movil']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fax'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['fax']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['email']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Personacontacto'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['personacontacto']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Observaciones'); ?></dt>
		<dd>
			<?php echo h($personasrelacionada['Personasrelacionada']['observaciones']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Personasrelacionada'), array('action' => 'edit', $personasrelacionada['Personasrelacionada']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Personasrelacionada'), array('action' => 'delete', $personasrelacionada['Personasrelacionada']['id']), null, __('Are you sure you want to delete # %s?', $personasrelacionada['Personasrelacionada']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Personasrelacionadas'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Personasrelacionada'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Localidades'), array('controller' => 'localidades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Localidade'), array('controller' => 'localidades', 'action' => 'add')); ?> </li>
	</ul>
</div>
