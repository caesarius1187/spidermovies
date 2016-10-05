<div class="compras view">
<h2><?php echo __('Compra'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($compra['Compra']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cliente'); ?></dt>
		<dd>
			<?php echo $this->Html->link($compra['Cliente']['nombre'], array('controller' => 'clientes', 'action' => 'view', $compra['Cliente']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Condicioniva'); ?></dt>
		<dd>
			<?php echo h($compra['Compra']['condicioniva']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fecha'); ?></dt>
		<dd>
			<?php echo h($compra['Compra']['fecha']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipocomprobante'); ?></dt>
		<dd>
			<?php echo h($compra['Compra']['tipocomprobante']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Puntosdeventa'); ?></dt>
		<dd>
			<?php echo $this->Html->link($compra['Puntosdeventa']['nombre'], array('controller' => 'puntosdeventas', 'action' => 'view', $compra['Puntosdeventa']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Numerocomprobante'); ?></dt>
		<dd>
			<?php echo h($compra['Compra']['numerocomprobante']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subcliente'); ?></dt>
		<dd>
			<?php echo $this->Html->link($compra['Subcliente']['nombre'], array('controller' => 'subclientes', 'action' => 'view', $compra['Subcliente']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Localidade'); ?></dt>
		<dd>
			<?php echo $this->Html->link($compra['Localidade']['nombre'], array('controller' => 'localidades', 'action' => 'view', $compra['Localidade']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipogasto'); ?></dt>
		<dd>
			<?php echo h($compra['Compra']['tipogasto']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Imputacion'); ?></dt>
		<dd>
			<?php echo h($compra['Compra']['imputacion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipocredito'); ?></dt>
		<dd>
			<?php echo h($compra['Compra']['tipocredito']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Alicuota'); ?></dt>
		<dd>
			<?php echo h($compra['Compra']['alicuota']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Neto'); ?></dt>
		<dd>
			<?php echo h($compra['Compra']['neto']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipoiva'); ?></dt>
		<dd>
			<?php echo h($compra['Compra']['tipoiva']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Iva'); ?></dt>
		<dd>
			<?php echo h($compra['Compra']['iva']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Ivapercep'); ?></dt>
		<dd>
			<?php echo h($compra['Compra']['ivapercep']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Iibbpercep'); ?></dt>
		<dd>
			<?php echo h($compra['Compra']['iibbpercep']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Actvspercep'); ?></dt>
		<dd>
			<?php echo h($compra['Compra']['actvspercep']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Impinternos'); ?></dt>
		<dd>
			<?php echo h($compra['Compra']['impinternos']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Impcombustible'); ?></dt>
		<dd>
			<?php echo h($compra['Compra']['impcombustible']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nogravados'); ?></dt>
		<dd>
			<?php echo h($compra['Compra']['nogravados']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nogravadogeneral'); ?></dt>
		<dd>
			<?php echo h($compra['Compra']['nogravadogeneral']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Total'); ?></dt>
		<dd>
			<?php echo h($compra['Compra']['total']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Asiento'); ?></dt>
		<dd>
			<?php echo h($compra['Compra']['asiento']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Periodo'); ?></dt>
		<dd>
			<?php echo h($compra['Compra']['periodo']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Compra'), array('action' => 'edit', $compra['Compra']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Compra'), array('action' => 'delete', $compra['Compra']['id']), null, __('Are you sure you want to delete # %s?', $compra['Compra']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Compras'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Compra'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clientes'), array('controller' => 'clientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cliente'), array('controller' => 'clientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Puntosdeventas'), array('controller' => 'puntosdeventas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Puntosdeventa'), array('controller' => 'puntosdeventas', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Subclientes'), array('controller' => 'subclientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Subcliente'), array('controller' => 'subclientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Localidades'), array('controller' => 'localidades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Localidade'), array('controller' => 'localidades', 'action' => 'add')); ?> </li>
	</ul>
</div>
