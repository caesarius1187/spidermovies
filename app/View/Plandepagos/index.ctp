<?php echo $this->Html->script('jquery-ui',array('inline'=>false));?>
<?php echo $this->Html->script('plandepagos/index',array('inline'=>false));?>
<div class="plandepagos index">
	<h2><?php echo __('Planes de pago'); ?></h2>
	<div class="plandepagos form">
		<?php		
		echo $this->Form->create('Plandepago',array('class'=>'formTareaCarga','action'=>'index')); 
				echo $this->Form->input('cliente_id');
				echo $this->Form->input('organismo',array('type'=>'select','options'=>$misorganismos));
				echo $this->Form->input('plan',array('required'=>true,'label'=>'N. Plan'));
				echo $this->Form->input('cuotas',array('required'=>true));		
				echo $this->Form->input('dia', array(
											'required'=>true,
                                            'class'=>'datepicker', 
                                            'type'=>'text',
                                            'readonly'=>'readonly',
                                            'label' => 'Fecha',
                                            'size' => '16')
                                 );		
				echo $this->Form->input('montocuota',array('required'=>true,'label'=>'Total'));		
				echo $this->Form->input('cbu',array('label'=>'CBU'));

				
                $options = array(
                    'label' => 'Crear plan de pago',
                    'div' => array('class' => 'btn_acept'),
                    'style' => 'width:180px; margin-top:12px'
                );
            
				echo $this->Form->end($options); 
		if(isset($cuotas)){
			echo '<div id="divPlandepagoAddForm">';
			echo $this->Form->create('Plandepago',array('action'=>'add','class'=>'formTareaCarga','inputDefaults' => array('label' => false))); ;?>
			</br>
			
				<h3><?php echo __('Agregar Plan de pago'); ?></h3>
				<table class="tbl_planpago" cellpadding="0" cellspacing="0" >
					<tr>

						<?php echo $this->Form->input('Plandepago.0.plan',array('type'=>'hidden','value'=>$plan)); ?>
						<?php echo $this->Form->input('Plandepago.0.organismo',array('type'=>'hidden','value'=>$organismo)); ?>
						<?php echo $this->Form->input('Plandepago.0.cbu',array('type'=>'hidden','value'=>$cbu)); ?>
						<?php echo $this->Form->input('Plandepago.0.cliente_id',array('type'=>'hidden','value'=>$clienteid));?>
						<?php echo $this->Form->input('Plandepago.0.user_id',array('type'=>'hidden','value'=>$user));?>
						<td><?php echo $this->Form->input('Plandepago.0.item',array('label'=>'Item','value'=>"1/".$cuotas));?></td>
						<td><?php echo $this->Form->input('Plandepago.0.periodo', array(
	                                            'label'=>'Periodo', 
	                                            'class'=>'datepicker-day-month', 
	                                            'type'=>'text',
	                                            'readonly'=>'readonly',
	                                            'value'=>date('m-Y',strtotime($dia)))
	                                 );?></td>
						<td><?php echo $this->Form->input('Plandepago.0.fchvto', array(
	                                            'label'=>'Vencimiento', 
	                                            'class'=>'datepicker', 
	                                            'type'=>'text',
	                                            'readonly'=>'readonly',
	                                            'value'=>date('d-m-Y',strtotime($dia))
	                                            )
	                                 );?></td>
						<td><?php echo $this->Form->input('Plandepago.0.montovto',array('label'=>'Monto','value'=>$montocuota));?></td>
						<td><?php	echo $this->Form->input('Plandepago.0.descripcion',array('label'=>'Descripcion'));
					 	?></td>
					</tr>
					<tr>
						<?php for($j=1;  $j<$cuotas; $j++){?>
						<?php	echo $this->Form->input('Plandepago.'.$j.'.plan',array('type'=>'hidden','value'=>$plan));?>
						<?php	echo $this->Form->input('Plandepago.'.$j.'.organismo',array('type'=>'hidden','value'=>$organismo));?>
						<?php	echo $this->Form->input('Plandepago.'.$j.'.cbu',array('type'=>'hidden','value'=>$cbu));?>
						<?php	echo $this->Form->input('Plandepago.'.$j.'.cliente_id',array('type'=>'hidden','value'=>$clienteid));?>
						<?php	echo $this->Form->input('Plandepago.'.$j.'.user_id',array('type'=>'hidden','value'=>$user));?>
						<td><?php	echo $this->Form->input('Plandepago.'.$j.'.item',array('value'=>($j+1)."/".$cuotas));?></td>
						<td><?php	echo $this->Form->input('Plandepago.'.$j.'.periodo', array(
			                                            'class'=>'datepicker-day-month', 
			                                            'type'=>'text',
			                                            'readonly'=>'readonly',
			                                            'value'=>date('m-Y',strtotime("+".$j." months",strtotime($dia)))
			                                            )
			                                 );?></td>
						<td><?php	echo $this->Form->input('Plandepago.'.$j.'.fchvto', array(
			                                            'class'=>'datepicker', 
			                                            'type'=>'text',
			                                            'readonly'=>'readonly',
			                                            'value'=>date('d-m-Y',strtotime("+".$j." months",strtotime($dia)))
			                                            )
			                                 );?></td>
						<td><?php	echo $this->Form->input('Plandepago.'.$j.'.montovto',array('value'=>$montocuota));?></td>
						<td><?php	echo $this->Form->input('Plandepago.'.$j.'.descripcion');?></td>
					</tr>
					<?php	;} ?>
					<tr>
						<td colspan="4">&nbsp;</td>
						<?php 
						 	$options = array(
                    		'label' => 'Guardar',
                    		'div' => array('class' => 'btn_acept'),
                    		'style' => 'margin-top:12px' 
                			); 
                		?>
						<td><?php echo $this->Form->end($options);?></td>
					</tr>
				</table>
			<?php echo '</div>';
		}else{
			
		}?> 
	</div>
	<div class="planpagos_dt">
		<table cellpadding="0" cellspacing="0" id="TablaListaPlanesDePago">
			<thead>
				<tr>
						<th>Cliente</th>
						<th>Plan</th>
						<th>Item</th>
						<th>CBU</th>
						<th>Periodo</th>
						<th>Vencimiento</th>
						<th>Cuota</th>
						<th>Realizado</th>
						<th>Pagado</th>
						<th class="actions"><?php echo __('Actions'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($plandepagos as $plandepago): ?>
				<tr>
					<td>
						<?php echo $this->Html->link($plandepago['Cliente']['nombre'], array('controller' => 'clientes', 'action' => 'view', $plandepago['Cliente']['id'])); ?>
					</td>
					<td><?php echo h($plandepago['Plandepago']['plan']); ?>&nbsp;</td>
					<td><?php echo h($plandepago['Plandepago']['item']); ?>&nbsp;</td>
					<td><?php echo h($plandepago['Plandepago']['cbu']); ?>&nbsp;</td>
					<td><?php echo h($plandepago['Plandepago']['periodo']); ?>&nbsp;</td>
					<td><?php echo h(date('d-m-Y',strtotime($plandepago['Plandepago']['fchvto']))); ?>&nbsp;</td>
					<td><?php echo h($plandepago['Plandepago']['montovto']); ?>&nbsp;</td>
					<td><?php echo h(date('d-m-Y',strtotime($plandepago['Plandepago']['fchrealizado']))); ?>&nbsp;</td>
					<td><?php echo h($plandepago['Plandepago']['montorealizado']); ?>&nbsp;</td>
					<td class="actions">
						<a href="#" onClick='pagarPlandePago(<?php echo $plandepago['Plandepago']['id'];?>)'>Pagar</a>
						<?php
						 echo $this->Form->postLink(__('Eliminar'), array('action' => 'delete', $plandepago['Plandepago']['id']), null, __('Are you sure you want to delete # %s?', $plandepago['Plandepago']['id'])); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</diV>
</div>
</div>
<!-- Inicio Popin Pagar Plan De Pago -->

<a href="#x" class="overlay" id="popinPagarPlandePago"></a>

<div class="popup">
        <div id="divPagarPlandePago" class="form" style="width: 400px;height: 318px; overflow: auto;">             
           
        </div>
    <a class="close" href="#close"></a>
</div>
    <!-- Fin Popin Pagar Plan De Pago --> 


