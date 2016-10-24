<div id="form_empleado" class="form index" style="width: 94%;">
	<?php echo $this->Form->create('Empleado',array('class'=>'formTareaCarga','controller'=>'Empelados','action'=>'add','id'=>'EmpleadoEditForm')); ?>
	<h3><?php echo __('Editar Empleado'); ?></h3>
	<?php
	echo $this->Form->input('id',array('type'=>'hidden'));
	echo $this->Form->input('cliente_id',array('type'=>'hidden'));
	echo $this->Form->input('nombre',array('style'=>'width:150px'));
	echo $this->Form->input('cuit',array('label'=>'CUIT',));
	echo $this->Form->input('dni',array('label'=>'DNI'));
	echo $this->Form->input('legajo',array('label'=>'Legajo'));
	echo $this->Form->input('categoria',array('label'=>'Categoria'));
	echo $this->Form->input('codigoafip',array('label'=>'Codigo Afip','options'=>array('0','1','2','3','4')));
	echo "</br>";
	echo $this->Form->input('fechaingreso', array(
			'class'=>'datepicker',
			'type'=>'text',
			'label'=>'Ingreso',
			'required'=>true,
			'default'=>$this->request->data['Empleado']['fechaingreso']?$this->request->data['Empleado']['fechaingreso']:null,
			'readonly'=>'readonly')
	);
	echo $this->Form->input('fechaegreso', array(
				'class'=>'datepicker',
				'type'=>'text',
				'label'=>'Egreso',
				'readonly'=>'readonly')
		);
	//Debugger::dump($puntosdeventas);
	echo $this->Form->input('domicilio_id',array('label'=>'Domicilio','options'=>$domicilios));
	echo $this->Form->input('conveniocolectivotrabajo_id',array('label'=>'Convenio Colectivo de Trabajo'));
	echo $this->Form->input('jornada',array('label'=>'Jornada','type'=>'select','options'=>array('0.5'=>"Media Jornada",'1'=>"Jornada Completa")));
	echo $this->Form->input('exentocooperadoraasistencial',array('label'=>'Excento Coop. Asistencial'));
	echo $this->Form->label("Liquidaciones:");
	echo $this->Form->input('liquidaprimeraquincena',array('label'=>'Primera Quincena'));
	echo $this->Form->input('liquidasegundaquincena',array('label'=>'Segunda Quincena'));
	echo $this->Form->input('liquidamensual',array('label'=>'Mensual'));
	echo $this->Form->input('liquidapresupuestoprimera',array('label'=>'Presupuesto 1'));
	echo $this->Form->input('liquidapresupuestosegunda',array('label'=>'Presupuesto 2'));
	echo $this->Form->input('liquidapresupuestomensual',array('label'=>'Presupuesto 3'));
	echo $this->Form->end(__('Aceptar')); ?>
</div>