<div class="eventosclientes view">
<?php if(isset($error)){
	echo $error;
}else{
	switch ($tarea){
	
	default:
	//tipo de tareas por defecto
	
	echo $this->Form->create('Eventoscliente',array('action'=>'edit'));?>
	<table>	
		<tr>
			<td>
				<?php echo $this->Form->input('clienteid',array('type'=>'hidden','default'=>$cliid)); ?>
				Estado:
				<?php echo $this->Form->input($tarea,array(
                    'options' => array(
                        'realizado'=>'Realizado', 
                        'pendiente'=>'Pendiente',                                                
                        ),
                    'label'=> '',
                    'required' => true, 
                    'placeholder' => 'Por favor seleccione Estado'
                )); ?>
			</td>			
		</tr>
	</table>
	<?php echo $this->Form->end(__('Aceptar')); ?>
	<?php
	break;
	}

?> 

<?php }?>	