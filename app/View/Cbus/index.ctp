<div class="cbuses index">
    <h2><?php echo __('CBUs del banco: '.$impcli['Impuesto']['nombre']); ?></h2>
    <h2><?php echo __('Agregar uno nuevo');?></h2>
    <?php
        echo $this->Form->create('Cbu',['action'=>'add']);
        echo $this->Form->input('impcli_id',['default'=>$impcli['Impcli']['id'],'type'=>'hidden']);
        echo $this->Form->input('numerocuenta',[]);
        echo $this->Form->input('cbu',['type'=>'text']);
        echo $this->Form->input('tipocuenta',['options'=>['Caja de ahorro'=>'Caja de ahorro','Cuenta corriente'=>'Cuenta corriente']]);
        echo $this->Form->end('Agregar');
    ?>
	<table cellpadding="0" cellspacing="0">
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

				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
