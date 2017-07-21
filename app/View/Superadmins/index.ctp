<script type="text/javascript">
	$(document).ready(function() {
		var iTableHeight = $(window).height();
		iTableHeight = (iTableHeight < 200) ? 200 : (iTableHeight - 320);
		//alert(iTableHeight)
		$("#tblListaUsuarios").dataTable( { 
			"sPaginationType": "full_numbers",
			"sScrollY": iTableHeight + "px",
		    "bScrollCollapse": true,
		    "iDisplayLength":50,
		});	
	});
</script>

<div class="estudios index">
	<div style="width:100%">
		<div style="width:30%">
			<h2><?php echo __('Estudios'); ?></h2>
		</div>
		<!--<td style="text-align: right;" title="Agregar Cliente">-->
	    <div class="fab blue" style="float: right; text-align: right;" title="Agregar Cliente">
	    <core-icon icon="add" align="center">
	        
	        <?php echo $this->Form->button('+', 
	                                    array('type' => 'button',
	                                          'class' =>"btn_add",
	                                          'onClick' => "window.location.href='".Router::url(array('controller'=>'Estudios',
	                                        			    'action'=>'superadminestudioadd'))."'"
	                                         )
	                );?> 
	    </core-icon>
	    <paper-ripple class="circle recenteringTouch" fit></paper-ripple>
	    </div>
	</div>
    <!--</td>-->

	<table id = "tblListaUsuarios" cellpadding="0" cellspacing="0">	
	<thead>
		<tr>
			<th>Estudio</th>						
			<th>Propietario</th>			
			<th>Direccion</th>			
			<th class="actions" style="text-align:center">Acciones</th>
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
	<?php foreach ($estudios as $estudio){ ?>
	<tr>
		<!--<td><?php echo h($estudio['Estudio']['id']); ?>&nbsp;</td>-->
		<td><?php echo h($estudio['Estudio']['nombre']); ?>&nbsp;</td>
		<td><?php echo h($estudio['Estudio']['propietario']); ?>&nbsp;</td>
		<td><?php echo h($estudio['Estudio']['direccion']); ?>&nbsp;</td>
		<td class="actions">
			<!--<?php echo $this->Html->link(__('View'), array('action' => 'view', $estudio['Estudio']['id'])); ?>-->
			<?php echo $this->Html->link(__('Editar'), array('action' => 'edit', $estudio['Estudio']['id'])); ?>
			<?php echo $this->Form->postLink(__('Deshabilitar'), array('action' => 'deshabilitar', $estudio['Estudio']['id']), null, __('Esta seguro que desea deshabilitar el estudio: %s?', $estudio['Estudio']['nombre'])); ?>
		</td>
	</tr>
	<?php }; ?>
	</tbody>
	</table>	
</div>
