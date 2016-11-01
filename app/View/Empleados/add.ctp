<?php
$empleado = $respuesta['data']['Empleado'];
?>
<tr class="empleado" id="rowEmpleado<?php echo $empleado['id']; ?>">
	<td><?php echo $empleado['legajo']; ?></td>
	<td><?php echo $empleado['cuit']; ?></td>
	<td><?php echo $empleado['dni']; ?></td>
	<td><?php echo $empleado['nombre']; ?></td>
	<td><?php echo $empleado['fechaingreso']; ?></td>
	<td>
		<a href="#"  onclick="loadFormEmpleado(<?php echo $empleado['id']; ?>)" class="button_view">
			<?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'imgedit'));?>
		</a>
		<?php echo $this->Form->postLink(
			$this->Html->image('ic_delete_black_24dp.png', array(
				'alt' => 'Eliminar',
			)),
			array(
				'controller' => 'Empleados',
				'action' => 'delete',
				$empleado['id'],
			),
			array(
				'class'=>'deleteEmpleado',
				'escape' => false // Add this to avoid Cake from printing the img HTML code instead of the actual image
			),
			__('Esta seguro que quiere eliminar este provedor?')
		); ?>
	</td>
</tr>
