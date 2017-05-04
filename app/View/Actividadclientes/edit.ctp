<?php
if(!$mostrarFormulario){ ?>
	<td><?php echo h($actividadcliente['Actividade']['descripcion']); ?></td>
	<td><?php echo h($actividadcliente['Actividade']['nombre']); ?></td>
	<td><?php echo h($actividadcliente['Actividadcliente']['descripcion']); ?></td>
	<td><?php echo h($actividadcliente['Actividadcliente']['baja']); ?></td>
	<td class="">
		<a href="#"  onclick="loadFormActividadcliente(<?php echo$actividadcliente['Actividadcliente']['id']; ?>,<?php echo $actividadcliente['Actividadcliente']['cliente_id'];?>)" class="button_view">
			<?php echo $this->Html->image('edit_view.png', array('alt' => 'open','class'=>'imgedit'));?>
		</a>
		<?php echo $this->Form->postLink(
			$this->Html->image('ic_delete_black_24dp.png', array(
				'alt' => 'Eliminar',
			)),
			array(
				'controller' => 'Actividadclientes',
				'action' => 'delete',
				$actividadcliente['Actividadcliente']['id'],
				$actividadcliente['Actividadcliente']['cliente_id']
			),
			array(
				'escape' => false // Add this to avoid Cake from printing the img HTML code instead of the actual image
			),
			__('Esta seguro que quiere eliminar esta actividad?')
		); ?>
	</td>
	<?php
}else{
	?>
	<?php echo $this->Form->create('Actividadcliente',array('action' => 'edit', )); ?>
	<?php
	echo $this->Form->input('id');
	echo $this->Form->input('cliente_id',array('type'=>'hidden'));
	?>
	<h3><?php echo __('Editar Actividad del Contribuyente'); ?></h3>
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td colspan="2">
				<?php echo $this->Form->input('actividadseleccionada',array(
						'label'=>'Actividad',
						'readonly'=>'readonly',
						'value'=>$this->request->data['Actividade']['descripcion']." ".$this->request->data['Actividade']['nombre'],
						'style'=>'width:95%'
					)
				);?>
			</td>
			<td colspan="2">
				<?php echo $this->Form->input('descripcion');?>
			</td>
			<td colspan="2">
				<?php
				$fechabaja = $this->request->data['Actividadcliente']['baja'];
				if($fechabaja==""||$fechabaja=="0000-00-00"){
					$fechabaja = "";
				}else{
					$fechabaja = $actividad['baja'];
				}
				echo $this->Form->input('baja'.$this->request->data['Actividadcliente']['id'], array(
						'class'=>'datepicker-month-year',
						'type'=>'text',
						'label'=>'Baja',
						'value'=>$fechabaja,
						'required'=>true,
						'readonly'=>'readonly')
				);
				?>
			</td>
		</tr>
		<tr>
			<td width="350">&nbsp;</td>
			<td>
				<a href="#close" onclick="" class="btn_cancelar" style="margin-top:15px">Cancelar</a>
			</td>
			<td>
				<?php echo $this->Form->end(__('Modficar',array('class' =>'btn_aceptar'))); ?>
			</td>
		</tr>
	</table>
<?php } ?>