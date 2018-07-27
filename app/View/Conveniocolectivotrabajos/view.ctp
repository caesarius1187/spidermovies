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
<div class="related index">
	<h3><?php echo __('Related Cctxconceptos'); ?></h3>
	<?php if (!empty($conveniocolectivotrabajo['Cctxconcepto'])): ?>
	<table cellpadding = "0" cellspacing = "0" id="relatedCctxconcepto">
        <thead>
            <tr>
                <th><?php echo __('Orden'); ?></th>
                <th><?php echo __('Seccion'); ?></th>
                <th><?php echo __('Codigo'); ?></th>
                <th><?php echo __('Nombre'); ?></th>
                <th><?php echo __('FuncionAAplicar'); ?></th>
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
        <?php foreach ($conveniocolectivotrabajo['Cctxconcepto'] as $cctxconcepto): ?>
            <tr>
                <td><?php echo $cctxconcepto['orden']; ?></td>
                <td><?php echo $cctxconcepto['Concepto']['seccion']; ?></td>
                <td><?php echo $cctxconcepto['Concepto']['codigo']; ?></td>
                <td><?php echo $cctxconcepto['nombre']; ?></td>
                <td><?php echo $cctxconcepto['funcionaaplicar']; ?></td>
               
            </tr>
        <?php endforeach; ?>
        </tbody>
	</table>
<?php endif; ?>

</div>
