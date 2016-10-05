<?php 
echo $this->Html->script(array('jquery.dataTables'), array('inline' => false)); 
echo $this->Html->script(array('tareasxclientesxestudios/index'), array('inline' => false)); 
echo $this->Html->css('demo_table')
;?>

<div class="tareas index">
	<h2><?php echo __('Tareas'); ?></h2>
	<table id="tablaTareasClientes" cellpadding="0" cellspacing="0" border="0" class="display">
	<thead>
			<tr>	
				<th>Descripcion</th>
	            <th>Estado</th>
	            <th>Tipo</th>
	            <th>Responsable</th>
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
		<?php foreach ($tareasxclientesxestudios as $tareasxclientesxestudio): ?>
	<tr>
		<td><?php echo h($tareasxclientesxestudio['Tareasxclientesxestudio']['descripcion']); ?>&nbsp;</td>
		<td>
			<?php 
				if (h($tareasxclientesxestudio['Tareasxclientesxestudio']['estado'])=="habilitado"){
					echo $this->Form->postLink(
				    $this->Html->image('check_box.png',
				       array("alt" => __('Clic aquí para deshabiliar tarea'), "title" => __('Clic aquí para deshabiliar tarea'))), 
				    array('action' => 'cambiarestado', $tareasxclientesxestudio['Tareasxclientesxestudio']['id'], $tareasxclientesxestudio['Tareasxclientesxestudio']['estado']), 
				    array('escape' => false, 'confirm' => __('¿Está seguro que quiere cambiar el estado de la tarea Nº %s?', $tareasxclientesxestudio['Tareasxclientesxestudio']['orden'])) 
					);
				}else{
					echo $this->Form->postLink(
				    $this->Html->image('check_box_blank.png',
				       array("alt" => __('Clic aquí para habiliar tarea'), "title" => __('Clic aquí para habiliar tarea'))), 
				    array('action' => 'cambiarestado', $tareasxclientesxestudio['Tareasxclientesxestudio']['id'], $tareasxclientesxestudio['Tareasxclientesxestudio']['estado']), 
				    array('escape' => false, 'confirm' => __('¿Está seguro que quiere cambiar el estado de la tarea Nº %s?', $tareasxclientesxestudio['Tareasxclientesxestudio']['orden']))
					);
				};
			?>&nbsp;
		</td>
		<td><?php echo h($tareasxclientesxestudio['Tareasxclientesxestudio']['tipo']); ?>&nbsp;</td>
   		<td><input type="button" style="width: 50%; font-size: 100%;background-color: transparent; border: 1px solid transparent; cursor:pointer" title="Cambiar Responsable" value="<?php echo $tareasxclientesxestudio['User']['nombre']?>" onclick="loadTareaId(<?php echo $tareasxclientesxestudio['Tareasxclientesxestudio']['id']?>)" /></td>

	</tr>
		<?php endforeach; ?>
	</tbody>
	</table>
	
</div>
<!--<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Tareasxclientesxestudio'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Tareasclientes'), array('controller' => 'tareasclientes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tareascliente'), array('controller' => 'tareasclientes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Estudios'), array('controller' => 'estudios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Estudio'), array('controller' => 'estudios', 'action' => 'add')); ?> </li>
	</ul>
</div>-->

<!-- Inicio Popin Cambiar responsable -->
<a href="#x" class="overlay" id="popincambiarresp"></a>
<div class="popup">
    <div class='section body'>
        
            <fieldset>
                <legend><?php echo __('Modificar responsable'); ?></legend>
                 
                 <?php echo $this->Form->create('tareasxclientesxestudio',array('action' => 'modificarResponsable')); 
 					echo $this->Form->input('idtarea', array('type' => 'hidden','value'=>''));
	                echo $this->Form->input('users', array(
	                    'type' => 'select',
						'label' => 'Seleccionar Responsable de realizar tarea' 
                ));?>
               
            </fieldset>
            <?php echo $this->Form->end(__('Modificar')); ?>
        
    </div>
    <a class="close" href="#close"></a>
</div>
<!-- Fin Popin Nuevo Impuesto --> 