<?php
echo $this->Html->css('bootstrapmodal');
echo $this->Html->script('jquery-ui',array('inline'=>false));
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
echo $this->Html->script('jquery.dataTables.js',array('inline'=>false));
echo $this->Html->script('dataTables.altEditor.js',array('inline'=>false));
echo $this->Html->script('dataTables.buttons.min.js',array('inline'=>false));
?>
<SCRIPT TYPE="text/javascript">
	var form_empleadoHTML = "";

jQuery(document).ready(function($) {
    $("#relatedCctxconcepto").DataTable();
});
</script>
<div class="conveniocolectivotrabajos view index">
<h2><?php echo __('Conveniocolectivotrabajo'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($conveniocolectivotrabajo['Conveniocolectivotrabajo']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Sindicato'); ?></dt>
		<dd>
			<?php echo $this->Html->link($conveniocolectivotrabajo['Impuesto']['nombre'], array('controller' => 'impuestos', 'action' => 'view', $conveniocolectivotrabajo['Impuesto']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($conveniocolectivotrabajo['Conveniocolectivotrabajo']['nombre']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Conveniocolectivotrabajo'), array('action' => 'edit', $conveniocolectivotrabajo['Conveniocolectivotrabajo']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Conveniocolectivotrabajo'), array('action' => 'delete', $conveniocolectivotrabajo['Conveniocolectivotrabajo']['id']), null, __('Are you sure you want to delete # %s?', $conveniocolectivotrabajo['Conveniocolectivotrabajo']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Conveniocolectivotrabajos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Conveniocolectivotrabajo'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Impuesto'), array('controller' => 'impuestos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Impuesto'), array('controller' => 'impuestos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cctxconceptos'), array('controller' => 'cctxconceptos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cctxconcepto'), array('controller' => 'cctxconceptos', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related index">
	<h3><?php echo __('Related Cctxconceptos'); ?></h3>
	<?php if (!empty($conveniocolectivotrabajo['Cctxconcepto'])): ?>
	<table cellpadding = "0" cellspacing = "0" id="relatedCctxconcepto">
        <thead>
            <tr>
                <th><?php echo __('Id'); ?></th>
                <th><?php echo __('Orden'); ?></th>
                <th><?php echo __('Seccion'); ?></th>
                <th><?php echo __('Concepto'); ?></th>
                <th><?php echo __('Codigo'); ?></th>
                <th><?php echo __('Nombre'); ?></th>
                <th><?php echo __('FuncionAAplicar'); ?></th>
                <th><?php echo __('Unidaddemedida'); ?></th>
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
            </tr>
        </tfoot>
        <tbody>
        <?php foreach ($conveniocolectivotrabajo['Cctxconcepto'] as $cctxconcepto): ?>
            <tr>
                <td><?php echo $cctxconcepto['id']; ?></td>
                <td><?php echo $cctxconcepto['orden']; ?></td>
                <td><?php echo $cctxconcepto['Concepto']['seccion']; ?></td>
                <td><?php echo $cctxconcepto['Concepto']['nombre']; ?></td>
                <td><?php echo $cctxconcepto['Concepto']['codigo']; ?></td>
                <td><?php echo $cctxconcepto['nombre']; ?></td>
                <td><?php echo $cctxconcepto['funcionaaplicar']; ?></td>
                <td><?php echo $cctxconcepto['unidaddemedida']; ?></td>
                <td class="actions">
                    <?php echo $this->Html->link(__('View'), array('controller' => 'cctxconceptos', 'action' => 'view', $cctxconcepto['id'])); ?>
                    <?php echo $this->Html->link(__('Edit'), array('controller' => 'cctxconceptos', 'action' => 'edit', $cctxconcepto['id'])); ?>
                    <?php echo $this->Form->postLink(__('Delete'), array('controller' => 'cctxconceptos', 'action' => 'delete', $cctxconcepto['id']), null, __('Are you sure you want to delete # %s?', $cctxconcepto['id'])); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
	</table>
<?php endif; ?>

</div>
