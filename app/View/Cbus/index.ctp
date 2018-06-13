<div class="cbuses">
    <h3><?php echo __('CBUs del banco: '.$impcli['Impuesto']['nombre']); ?></h3>
    <h3><?php echo __('Agregar uno nuevo');?></h3>
    <table cellpadding="0" cellspacing="0">
    	 <?php
        	echo $this->Form->create('Cbu',['action'=>'add']);
        	echo $this->Form->input('impcli_id',['default'=>$impcli['Impcli']['id'],'type'=>'hidden']);
     	?> 
    	<tr>
    		<td><?php echo $this->Form->input('numerocuenta',[]);?></td>
    		<td><?php echo $this->Form->input('cbu',['type'=>'text']);?></td>
    	</tr>
    	<tr>
    		<td>
    			<?php 
    			echo $this->Form->input('tipocuenta',[
					'options'=>[
					'Caja de Ahorro en Euros'=>'Caja de Ahorro en Euros',
					'Caja de Ahorro en Moneda Local'=>'Caja de Ahorro en Moneda Local',
					'Caja de Ahorro en U$S'=>'Caja de Ahorro en U$S',
					'Cuenta Corriente en Euros'=>'Cuenta Corriente en Euros',
					'Cuenta Corriente en Moneda Local'=>'Cuenta Corriente en Moneda Local',
					'Cuenta Corriente en U$S'=>'Cuenta Corriente en U$S',
					'Otras'=>'Otras',
					'Plazo Fijo en Euros'=>'Plazo Fijo en Euros',
					'Plazo Fijo en Moneda Local'=>'Plazo Fijo en Moneda Local',
					'Plazo Fijo en U$S'=>'Plazo Fijo en U$S'
					]
				]);
    			?>
    		</td>
    		<td><?php echo $this->Form->input('noasociadoaactividad',['label'=>'No Asociado a Actividad']);?></td>
    	</tr>
   		<tr>
   			<td></td>
   			<td><?php echo $this->Form->end('Aceptar');?></td>
   		</tr>	
        
	 
	</table>
	<table cellpadding="0" cellspacing="0" class="tbl_popup">
		<thead>
			<tr>
				<th><?php echo 'Cuenta asociada'; ?></th>
				<th><?php echo 'Nro cuenta'; ?></th>
				<th><?php echo 'CBU'; ?></th>
				<th><?php echo 'Tipo'; ?></th>
				<th class="actions"><?php echo __('Acciones'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($cbus as $cbu): ?>
			<tr>
				<td><?php echo $cbu['Cuentascliente']['nombre']; ?></td>
				<td><?php echo h($cbu['Cbu']['numerocuenta']); ?></td>
				<td><?php echo h($cbu['Cbu']['cbu']); ?></td>
				<td><?php echo h($cbu['Cbu']['tipocuenta']); ?></td>
				<td class="actions">
					<a href="#"  onclick="loadModificarCbu(<?php echo $impcli['Impcli']['id'];?>,<?php echo $cbu['Cbu']['id'];?>)" class="button_view">
						<?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'img_edit'));?>
					</a>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
