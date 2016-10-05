<div class="grupoclientes view">
<h2><?php echo __('Grupocliente'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($grupocliente['Grupocliente']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($grupocliente['Grupocliente']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Descripcion'); ?></dt>
		<dd>
			<?php echo h($grupocliente['Grupocliente']['descripcion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Estado'); ?></dt>
		<dd>
			<?php echo h($grupocliente['Grupocliente']['estado']); ?>
			&nbsp;
		</dd>		
	</dl>
</div>

