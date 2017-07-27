<div class="bienesdeusos view">
<h2><?php echo __('Bienesdeuso'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Compra'); ?></dt>
		<dd>
			<?php echo $this->Html->link($bienesdeuso['Compra']['numerocomprobante'], array('controller' => 'compras', 'action' => 'view', $bienesdeuso['Compra']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipo'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['tipo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Periodo'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['periodo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Titularidad'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['titularidad']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Marca'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['marca']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modelo'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['modelo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fabrica'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['fabrica']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Aniofabricacion'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['aniofabricacion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Patente'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['patente']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Valor'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['valor']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Amortizado'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['amortizado']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipoautomotor'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['tipoautomotor']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fechaadquisicion'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['fechaadquisicion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipoinmueble'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['tipoinmueble']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Calle'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['calle']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Destino'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['destino']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Numero'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['numero']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Piso'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['piso']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Depto'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['depto']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Localidade'); ?></dt>
		<dd>
			<?php echo $this->Html->link($bienesdeuso['Localidade']['nombre'], array('controller' => 'localidades', 'action' => 'view', $bienesdeuso['Localidade']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Codigopostal'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['codigopostal']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Catastro'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['catastro']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Partido'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['partido']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Partida'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['partida']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Digito'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['digito']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipoembarcacion'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['tipoembarcacion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Eslora'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['eslora']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Manga'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['manga']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tonelajeneto'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['tonelajeneto']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Registro'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['registro']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Registrojur'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['registrojur']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Otroregistro'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['otroregistro']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Matricula'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['matricula']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cantidadmotores'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['cantidadmotores']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Marcamotor'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['marcamotor']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modelomotor'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['modelomotor']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Potencia'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['potencia']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Numeromotor'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['numeromotor']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Origen'); ?></dt>
		<dd>
			<?php echo h($bienesdeuso['Bienesdeuso']['origen']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Bienesdeuso'), array('action' => 'edit', $bienesdeuso['Bienesdeuso']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Bienesdeuso'), array('action' => 'delete', $bienesdeuso['Bienesdeuso']['id']), array(), __('Are you sure you want to delete # %s?', $bienesdeuso['Bienesdeuso']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Bienesdeusos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Bienesdeuso'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Compras'), array('controller' => 'compras', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Compra'), array('controller' => 'compras', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Localidades'), array('controller' => 'localidades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Localidade'), array('controller' => 'localidades', 'action' => 'add')); ?> </li>
	</ul>
</div>
