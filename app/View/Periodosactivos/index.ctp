<div class="periodosactivos form" >
	<h3><?php echo __('Periodos Activos de '.$impcli['Impuesto']['nombre']); ?></h3>

	<?php foreach ($periodosactivos as $periodosactivo): 
	if ($periodosactivo['Periodosactivo']['hasta']==null){?>
	
			<?php echo $this->Form->create('Periodosactivo',array('id'=>'formPeriodosActivosAdd','action'=>'add')); ?>			
			<table cellpadding="0" cellspacing="0">
			<tr>

				<?php echo $this->Form->input('id',array('type'=>'hidden','value'=>$periodosactivo['Periodosactivo']['id'])) ?>
				<?php echo $this->Form->input('impcli_id',array('type'=>'hidden','value'=>$impcli['Impcli']['id'])) ?>
				<td>
					<?php 
		                 echo $this->Form->input('desde', array(
		                            'class'=>'datepicker-month-year', 
		                            'type'=>'text',
		                            'label'=>'Alta',         
		                            'required'=>true,         
		                            'value'=> $periodosactivo['Periodosactivo']['desde'],                 
		                            'readonly'=>'readonly')
		                 );
		             ?>
		        </td>
				<td>
					<?php 
		                 echo $this->Form->input('hasta', array(
		                            'class'=>'datepicker-month-year', 
		                            'type'=>'text',
		                            'label'=>'Baja',        
		                            'value'=> $periodosactivo['Periodosactivo']['hasta'],                                             
		                            'readonly'=>'readonly')
		                 );
		             ?>
		        </td>
				<td><?php echo $this->Form->end(__('Modificar')); ?></td>
			</tr>	
         	</table>
	<?php
	}
	endforeach; ?>
</div>

<div class="periodosactivos form">
	<table cellpadding="0" cellspacing="0" class="tabla">
	<tr>
		<th>Altas</th>
		<th>Bajas</th>
		<th width="177">&nbsp;</th>
	</tr>
	<?php foreach ($periodosactivos as $periodosactivo): ?>
	<tr>
		<td><?php echo h($periodosactivo['Periodosactivo']['desde']); ?></td>
		<td><?php echo h($periodosactivo['Periodosactivo']['hasta']); ?></td>	
		<td>&nbsp;</td>
	</tr>
<?php endforeach; ?>
	</table>	
</div>
