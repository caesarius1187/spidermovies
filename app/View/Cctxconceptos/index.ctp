<?php
echo $this->Html->css('bootstrapmodal');
echo $this->Html->script('jquery-ui',array('inline'=>false));
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
echo $this->Html->script('jquery.dataTables.js',array('inline'=>false));
echo $this->Html->script('dataTables.altEditor.js',array('inline'=>false));
echo $this->Html->script('dataTables.buttons.min.js',array('inline'=>false));
echo $this->Html->script('cctxconceptos/index',array('inline'=>false));
?>
<div class="cctxconceptos index">
	<h2><?php echo __('Convenios x Conceptos'); ?></h2>
	<table cellpadding="0" cellspacing="0" id="cctxconceptosIndex">
        <thead>
            <tr>
                <th><?php echo 'id'; ?></th>
                <th><?php echo 'conveniocolectivotrabajo_id'; ?></th>
                <th><?php echo 'concepto_id'; ?></th>
                <th><?php echo 'nombre'; ?></th>
                <th><?php echo 'funcionaaplicar'; ?></th>
                <th><?php echo 'unidaddemedida'; ?></th>
                <th><?php echo 'calculado'; ?></th>
                <th><?php echo 'orden'; ?></th>
                <th><?php echo 'campopersonalizado'; ?></th>
                <th><?php echo 'cliente_id'; ?></th>
                <th><?php echo 'codigopersonalizado'; ?></th>
                <th><?php echo 'conporcentaje'; ?></th>
                <th><?php echo 'porcentaje'; ?></th>
                <th class="actions"><?php echo __('Actions'); ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach ($cctxconceptos as $cctxconcepto): ?>
            <tr>
		<td><?php echo h($cctxconcepto['Cctxconcepto']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($cctxconcepto['Conveniocolectivotrabajo']['nombre'], array('controller' => 'conveniocolectivotrabajos', 'action' => 'view', $cctxconcepto['Conveniocolectivotrabajo']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($cctxconcepto['Concepto']['nombre'], array('controller' => 'conceptos', 'action' => 'view', $cctxconcepto['Concepto']['id'])); ?>
		</td>
		<td><?php echo h($cctxconcepto['Cctxconcepto']['nombre']); ?>&nbsp;</td>
		<td><?php echo h($cctxconcepto['Cctxconcepto']['funcionaaplicar']); ?>&nbsp;</td>
		<td><?php echo h($cctxconcepto['Cctxconcepto']['unidaddemedida']); ?>&nbsp;</td>
		<td><?php echo h($cctxconcepto['Cctxconcepto']['calculado']); ?>&nbsp;</td>
		<td><?php echo h($cctxconcepto['Cctxconcepto']['orden']); ?>&nbsp;</td>
		<td><?php echo h($cctxconcepto['Cctxconcepto']['campopersonalizado']); ?>&nbsp;</td>
		<td><?php echo h($cctxconcepto['Cctxconcepto']['cliente_id']); ?>&nbsp;</td>
		<td><?php echo h($cctxconcepto['Cctxconcepto']['codigopersonalizado']); ?>&nbsp;</td>
		<td><?php echo h($cctxconcepto['Cctxconcepto']['conporcentaje']); ?>&nbsp;</td>
		<td><?php echo h($cctxconcepto['Cctxconcepto']['porcentaje']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $cctxconcepto['Cctxconcepto']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $cctxconcepto['Cctxconcepto']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $cctxconcepto['Cctxconcepto']['id']), null, __('Are you sure you want to delete # %s?', $cctxconcepto['Cctxconcepto']['id'])); ?>
		</td>
	</tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Cctxconcepto'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Conveniocolectivotrabajos'), array('controller' => 'conveniocolectivotrabajos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Conveniocolectivotrabajo'), array('controller' => 'conveniocolectivotrabajos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Conceptos'), array('controller' => 'conceptos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Concepto'), array('controller' => 'conceptos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Valorrecibos'), array('controller' => 'valorrecibos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Valorrecibo'), array('controller' => 'valorrecibos', 'action' => 'add')); ?> </li>
	</ul>
</div>
