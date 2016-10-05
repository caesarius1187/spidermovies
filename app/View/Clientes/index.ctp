<?php 
	echo $this->Html->script('jquery.dataTables.grouping',array('inline'=>false))	
;?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#TablaListaClientes").dataTable( { 
			"sPaginationType": "full_numbers",
			"sScrollY": "600px",
		    "bScrollCollapse": true,
		    "iDisplayLength":100,
		}).rowGrouping({bExpandableGrouping: true});	
	});
</script>

<div class="clientes index" >
	<table>
		<td><h2><?php echo __('Clientes'); ?></h2></td>
		<td style="text-align: right; cursor:pointer;" title="Agregar Cliente">
			<div class="fab blue">
	      	<core-icon icon="add" align="center">
	      		
				<?php echo $this->Form->button('+', 
											array('type' => 'button',
												'class' =>"btn_add",
												'onClick' => "window.location.href='".Router::url(array(
																					'controller'=>'Clientes', 
																					'action'=>'add')
																					)."'"
												)
						);?> 
	      	</core-icon>
	     	<paper-ripple class="circle recenteringTouch" fit></paper-ripple>
	    	</div>
		</td>
	</table>

	<table id="TablaListaClientes" cellpadding="0" cellspacing="0" border="0" class="display">
	<!--<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('grupocliente_id'); ?></th>
			
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>-->
	<thead>
			<tr>	
				<th>Nombre Grupo</th>
				<th>Nombre Cliente</th>					            
	            <th style='text-align:center'>Acciones</th>	            
			</tr>
	</thead>
	<tfoot>
			<tr>
				<th></th>
				<th></th>
                <th></th>               	
			</tr>
	</tfoot>
	<tbody>
	<?php foreach ($clientes as $cliente): ?>
	<tr>
		<!--<td><?php echo h($cliente['Cliente']['id']); ?>&nbsp;</td>-->	

		<td>
			<?php echo $this->Html->link($cliente['Grupocliente']['nombre'], array('controller' => 'grupoclientes', 'action' => 'view', $cliente['Grupocliente']['id'])); ?>
		</td>
		<td><?php echo h($cliente['Cliente']['nombre']); ?>&nbsp;</td>		
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $cliente['Cliente']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $cliente['Cliente']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $cliente['Cliente']['id']), null, __('Are you sure you want to delete # %s?', $cliente['Cliente']['id'])); ?>
		</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>

</div>

