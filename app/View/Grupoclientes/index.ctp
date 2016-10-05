<?php 
	echo $this->Html->script('Grupoclientes/index',array('inline'=>false))	
;?>

<div class="grupoclientes index">
	<table>
		<tr>
			<td><h2><?php echo __('Grupo Clientes'); ?></h2></td>
			<td style="text-align: right;">
				<div class="fab blue">
	      		<core-icon icon="add" align="center">
	      			
	      			<?php echo $this->Form->button('+', 
											array('type' => "button",
												'class' =>"btn_add",
												'onClick' => "location.href='#nuevo_grupocliente'"
												)
						);?> 
	      		</core-icon>
	     		<paper-ripple class="circle recenteringTouch" fit></paper-ripple>
	    		</div>
			</td>
		</tr>
	</table>	
	<table id ="tblListaGrupoClientes" cellpadding="0" cellspacing="0" border="0" class="display" style="margin-top:10px;">
	<!--<<tr>
			th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('descripcion'); ?></th>
			<th><?php echo $this->Paginator->sort('estado'); ?></th>
			<th><?php echo $this->Paginator->sort('estudio_id'); ?></th>
			<th class="actions"><?php echo __('Acciones'); ?></th>
	</tr>-->
	<thead>
			<tr>	
				<th>Nombre Grupo</th>
				<th>Descripci&oacute;n</th>
	            <th>Estado</th>
	            <th style='text-align:center'>Acciones</th>	            
			</tr>
	</thead>
	<tfoot>
			<tr>
				<th></th>
				<th></th>
				<th></th>
                <th></th>               	
			</tr>
	</tfoot>
	<tbody>
	<?php foreach ($grupoclientes as $grupocliente): ?>
	<tr>
		<!--<td><?php echo h($grupocliente['Grupocliente']['id']); ?>&nbsp;</td>-->
		<td><?php echo h($grupocliente['Grupocliente']['nombre']); ?>&nbsp;</td>
		<td><?php echo h($grupocliente['Grupocliente']['descripcion']); ?>&nbsp;</td>
		<td><?php echo h($grupocliente['Grupocliente']['estado']); ?>&nbsp;</td>
		<!--<td>
			<?php echo $this->Html->link($grupocliente['Estudio']['id'], array('controller' => 'estudios', 'action' => 'view', $grupocliente['Estudio']['id'])); ?>
		</td>-->
		<td class="actions">
			<!--
			<?php echo $this->Html->link(__('Ver'), array('action' => 'view', $grupocliente['Grupocliente']['id'])); ?>
			<?php echo $this->Html->link(__('Editar'), array('action' => 'edit', $grupocliente['Grupocliente']['id'])); ?>
			-->
			<?php echo $this->Form->button('Editar', array('onClick' => 'editarGrupo('.$grupocliente["Grupocliente"]["id"].')' )); ?>			
			<!--<?php echo $this->Form->postLink(__('Borrar'), array('action' => 'delete', $grupocliente['Grupocliente']['id']), null, __('Are you sure you want to delete # %s?', $grupocliente['Grupocliente']['id'])); ?>-->
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<!--<p>
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
	?>-->
	</div>
</div>

<!-- Inicio Popin Grupo Clientes -->
<a href="#x" class="overlay" id="nuevo_grupocliente"></a>
<div class="popup">
    <div class='section body'>
        <div id="form_grupocliente" class="" style="width:420px;">
        	<?php echo $this->Form->create('Grupocliente', array('action' => 'add')); ?>
            <fieldset>
            	<legend><?php echo __('Agregar Grupo de Clientes'); ?></legend>
        	</fieldset>
            <table style="width:100%;">
            	<tr>
            		<td>
            			<?php echo $this->Form->input('nombre'); ?>
            		</td>
            		<td>
            			<?php echo $this->Form->input('descripcion'); ?>
            			<?php echo $this->Form->input('estado', array('type'=>'hidden','default'=> 'habilitado')) ;?>
    					<?php echo $this->Form->input('estudio_id',array('type'=>'hidden','default'=>$this->Session->read('Auth.User.estudio_id')));?>
            		</td>
            	</tr>
            </table>
            
            <?php echo $this->Form->end(__('Guardar')); ?>
            <!--<a href="#"  onclick="agregarGrupoCliente()" class="btn_aceptar">Aceptar</a>-->
        </div>
    </div>
    <a class="close" href="#close"></a>
</div>
<!-- Fin Popin Nuevo Grupo Clientes --> 

<!-- Inicio Popin Editar Grupo Clientes -->
<a href="#x" class="overlay" id="editar_grupocliente"></a>
<div class="popup">
    <div class='section body'>
        <div id="form_editar_grupocliente" class="" style="width:420px;">

        </div>
    </div>
    <a class="close" href="#close"></a>
</div>
<!-- Fin Popin Editar Grupo Clientes -->