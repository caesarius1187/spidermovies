<script type="text/javascript">
	/*
	$(document).ready(function() {		
	});
	*/	
</script>
<div class="index">
	<table>
	<tr>
		<td style="text-align: left;">
			<h2><?php echo __('Movimientos'); ?></h2>
		</td>	
		<td style="text-align: right; cursor:pointer;" title="Agregar Usuario">
		
		<div class="fab blue">
            <core-icon icon="add" align="center">
                
                <?php echo $this->Form->button('+', 
                                            array('type' => 'button',
                                                'class' =>"btn_add",
                                                'onClick' => "agregarUsuario()"
                                                )
                        );?> 
            </core-icon>
            <paper-ripple class="circle recenteringTouch" fit></paper-ripple>
       	</div>


		</td>
	</tr>
	</table>	
	<table id="tblListaMovimientos" cellpadding="0" cellspacing="0" border="0" class="display">
		<thead>
			<tr>
				<th>Asiento</th>
				<th>fecha Asiento</th>
				<th>Total Debe</th>
				<th>Total Haber</th>				
				<th class="actions" style="text-align:center"><?php echo __('Acciones'); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th></th>
				<th></th>
				<th></th>
                <th></th>  
                <th></th>				
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($movimientos as $movimiento)
		{ 
		?>
		<tr>
			<td>				
				<?php echo h($movimiento['Asiento']['descripcion']); ?>
			</td>
			<td>
				<?php echo h($movimiento['Asiento']['fecha']); ?>
			</td>
			<td>
				<?php echo h($movimiento['Movimiento']['totaldebe']); ?>
			</td>			
			<td>
				<?php echo h($movimiento['Movimiento']['totalhaber']); ?>
			</td>
			
			<td class="actions">
				<?php //echo $this->Form->button('Editar', array('onClick' => 'editarUsuario('')' )); ?>
			</td>
		</tr>
		<?php 
		} 
		?>
		</tbody>
	</table>		
</div>
