<div class="bienesdeusos index">
	<h2><?php echo __('Bienesdeusos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('compra_id'); ?></th>
			<th><?php echo $this->Paginator->sort('tipo'); ?></th>
			<th><?php echo $this->Paginator->sort('periodo'); ?></th>
			<th><?php echo $this->Paginator->sort('titularidad'); ?></th>
			<th><?php echo $this->Paginator->sort('marca'); ?></th>
			<th><?php echo $this->Paginator->sort('modelo'); ?></th>
			<th><?php echo $this->Paginator->sort('fabrica'); ?></th>
			<th><?php echo $this->Paginator->sort('aniofabricacion'); ?></th>
			<th><?php echo $this->Paginator->sort('patente'); ?></th>
			<th><?php echo $this->Paginator->sort('valor'); ?></th>
			<th><?php echo $this->Paginator->sort('amortizado'); ?></th>
			<th><?php echo $this->Paginator->sort('tipoautomotor'); ?></th>
			<th><?php echo $this->Paginator->sort('fechaadquisicion'); ?></th>
			<th><?php echo $this->Paginator->sort('tipoinmueble'); ?></th>
			<th><?php echo $this->Paginator->sort('calle'); ?></th>
			<th><?php echo $this->Paginator->sort('destino'); ?></th>
			<th><?php echo $this->Paginator->sort('numero'); ?></th>
			<th><?php echo $this->Paginator->sort('piso'); ?></th>
			<th><?php echo $this->Paginator->sort('depto'); ?></th>
			<th><?php echo $this->Paginator->sort('localidade_id'); ?></th>
			<th><?php echo $this->Paginator->sort('codigopostal'); ?></th>
			<th><?php echo $this->Paginator->sort('catastro'); ?></th>
			<th><?php echo $this->Paginator->sort('partido'); ?></th>
			<th><?php echo $this->Paginator->sort('partida'); ?></th>
			<th><?php echo $this->Paginator->sort('digito'); ?></th>
			<th><?php echo $this->Paginator->sort('tipoembarcacion'); ?></th>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('eslora'); ?></th>
			<th><?php echo $this->Paginator->sort('manga'); ?></th>
			<th><?php echo $this->Paginator->sort('tonelajeneto'); ?></th>
			<th><?php echo $this->Paginator->sort('registro'); ?></th>
			<th><?php echo $this->Paginator->sort('registrojur'); ?></th>
			<th><?php echo $this->Paginator->sort('otroregistro'); ?></th>
			<th><?php echo $this->Paginator->sort('matricula'); ?></th>
			<th><?php echo $this->Paginator->sort('cantidadmotores'); ?></th>
			<th><?php echo $this->Paginator->sort('marcamotor'); ?></th>
			<th><?php echo $this->Paginator->sort('modelomotor'); ?></th>
			<th><?php echo $this->Paginator->sort('potencia'); ?></th>
			<th><?php echo $this->Paginator->sort('numeromotor'); ?></th>
			<th><?php echo $this->Paginator->sort('origen'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($bienesdeusos as $bienesdeuso): ?>
	<tr>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($bienesdeuso['Compra']['numerocomprobante'], array('controller' => 'compras', 'action' => 'view', $bienesdeuso['Compra']['id'])); ?>
		</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['tipo']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['periodo']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['titularidad']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['marca']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['modelo']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['fabrica']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['aniofabricacion']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['patente']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['valor']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['amortizado']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['tipoautomotor']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['fechaadquisicion']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['tipoinmueble']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['calle']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['destino']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['numero']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['piso']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['depto']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($bienesdeuso['Localidade']['nombre'], array('controller' => 'localidades', 'action' => 'view', $bienesdeuso['Localidade']['id'])); ?>
		</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['codigopostal']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['catastro']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['partido']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['partida']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['digito']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['tipoembarcacion']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['nombre']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['eslora']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['manga']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['tonelajeneto']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['registro']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['registrojur']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['otroregistro']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['matricula']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['cantidadmotores']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['marcamotor']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['modelomotor']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['potencia']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['numeromotor']); ?>&nbsp;</td>
		<td><?php echo h($bienesdeuso['Bienesdeuso']['origen']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $bienesdeuso['Bienesdeuso']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $bienesdeuso['Bienesdeuso']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $bienesdeuso['Bienesdeuso']['id']), array(), __('Are you sure you want to delete # %s?', $bienesdeuso['Bienesdeuso']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Bienesdeuso'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Compras'), array('controller' => 'compras', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Compra'), array('controller' => 'compras', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Localidades'), array('controller' => 'localidades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Localidade'), array('controller' => 'localidades', 'action' => 'add')); ?> </li>
	</ul>
</div>
