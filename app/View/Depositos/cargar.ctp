 <?php /*Este es el formulario para Cargar Recibos(depositos) */ ?>
<div id="Formhead" class="clientes informefinancierotributario index" style="margin-bottom:20px; font-family: 'Arial'">
	<div id="form_depositos" class="Deposito"  style="float: left; margin-bottom:10px">
		<fieldset style="float: left;">
			<legend><?php echo __('Recibo del periodo: '.$periodo); ?></legend>
		</fieldset>

		<?php
		$params= "'0','reciboscargados','".$periodo."','".$cliid."','realizado'";

		 echo $this->Form->button('Finalizar',
			array('type' => 'button',
				'class' =>"btn_realizar_tarea",
				'div' => false,
				'style' => array('style' => 'float:right'),
				'onClick' => "realizarEventoCliente(".$params.")"
			)
		);



		echo $this->Form->create('Deposito',array(
			'id'=>'formAddDeposito',
			'controller'=>'depositos',
			'action'=>'add',
			'class'=>'formTareaCarga',
			)
		); 
		echo $this->Form->input('id',array('type'=>'hidden'));
		echo $this->Form->input('cliente_id',array('value'=>$cliid,'readonly'=>'readonly','type'=>'hidden'));
		echo $this->Form->input('periodo',array('value'=>$periodo,'type'=>'hidden')); 
		echo $this->Form->input('id',array('type'=>'hidden')); 
		echo $this->Form->input('evento_id',array('type'=>'hidden')); 
		echo $this->Form->input('monto', array(
												'div' => array(
													'style'=>'width:20%;'
													),
											  )
								); 
		$maxDepo = 0;
		if(isset($maxdeposito)){
			foreach ($maxdeposito as $md) {
				$maxDepo = $md[0]['depomax']+1;
			}
		}
		echo $this->Form->input('numero',array(
												'value'=>$maxDepo,'type'=>'number',
												'div' => array(
													'style'=>'width:20%;'
													),
												)
								); 
		echo $this->Form->input('factura', array('style'=>'width:80px;',
												 'div' => array(
													 'style'=>'width:20%;'
													 ),
												)
								); 
		echo $this->Form->input('fecha', array(
								  'class'=>'datepicker', 
								  'type'=>'text',
								  'label'=>'Fecha',                                    
								  'readonly'=>'readonly',
								  'style'=>'width:80px;',
								  'div' => array(
												'style'=>'width:20%;'
												),
								  )
							 ); 
		echo $this->Form->input('descripcion', array(
													'style'=>'width:100%;',
													'div' => array(
																	'style'=>'width:76%;'
																  ),
													)
								); 
		echo  $this->Form->submit('Agregar'); echo  $this->Form->end();?>	
		
	</div>
	<table cellpadding="0" cellspacing="0" id="tablePapelesPreparados" class="tbl_papeles" style="">
	<tr>
		<td colspan="6"><h3><?php echo __('Recibos Agregados'); ?></h3></td>
	</tr>
	<tr>
		<th>Monto</th>
		<th>Numero</th>
		<th>Factura</th>
		<th>Fecha</th>
		<th>Descripcion</th>
		<th style="text-align:center">Acciones</td>
	</tr>

	<?php foreach ($depositos as $deposito): ?>
	<tr>
		<td><?php echo h($deposito['Deposito']['monto']); ?>&nbsp;</td>
		<td><?php echo h($deposito['Deposito']['numero']); ?>&nbsp;</td>
		<td><?php echo h($deposito['Deposito']['factura']); ?>&nbsp;</td>
		<td><?php echo date('d-m-Y', strtotime(h($deposito['Deposito']['fecha']))); ?>&nbsp;</td>
		<td><?php echo h($deposito['Deposito']['descripcion']); ?>&nbsp;</td>
		<td class="actions" style="text-align:center">			
			<?php
				echo $this->Form->button('Eliminar', array(
				    'type' => 'link',
				    'onClick' => 'eliminarDeposito('.$deposito['Deposito']['id'].')',
				    'escape' => false
				));
			    echo $this->Html->link(__('Imprimir'), 
			    	array('controller'=>'depositos','action' => 'view', $deposito['Deposito']['id']),
			    	array('target' => '_blank')
			    	);
			?>	
		</td>
	</tr>
	<?php endforeach; ?>
</table>
</div>
