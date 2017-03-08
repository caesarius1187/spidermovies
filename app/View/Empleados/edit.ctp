<div id="form_empleado" class="form" style="width: 94%;">
	<?php echo $this->Form->create('Empleado',array('class'=>'formTareaCarga','controller'=>'Empelados','action'=>'add','id'=>'EmpleadoEditForm')); ?>
	<h3><?php echo __('Editar Empleado'); ?></h3>
	<fieldset style="border: 1px solid #1e88e5;">
		<legend style="color:#1e88e5;font-weight:normal;">Datos Personales</legend>
		<?php
		echo $this->Form->input('id',array('type'=>'hidden'));
		echo $this->Form->input('cliente_id',array('type'=>'hidden'));
		echo $this->Form->input('legajo',array('label'=>'Legajo'));
		echo $this->Form->input('nombre',array('style'=>'width:150px'));
		echo $this->Form->input('cuit',array('label'=>'CUIT',));
		echo $this->Form->input('dni',array('label'=>'DNI'));
		?>
	</fieldset>
	<fieldset style="border: 1px solid #1e88e5;">
		<legend style="color:#1e88e5;font-weight:normal;">Laborales</legend>
		<?php
		echo $this->Form->input('fechaingreso', array('type'=>'hidden'));
		echo $this->Form->input('fechaegreso', array('type'=>'hidden'));
		echo $this->Form->input('fechaalta', array('type'=>'hidden'));

		echo $this->Form->input('fechaingresoedit', array(
				'class'=>'datepicker',
				'type'=>'text',
				'label'=>'Ingreso',
				'required'=>true,
				'default'=>$this->request->data['Empleado']['fechaingreso']?$this->request->data['Empleado']['fechaingreso']:null,
				'readonly'=>'readonly')
		);
		echo $this->Form->input('fechaaltaedit', array(
				'class'=>'datepicker',
				'type'=>'text',
				'label'=>'Alta',
				'required'=>true,
				'default'=>$this->request->data['Empleado']['fechaalta']?$this->request->data['Empleado']['fechaalta']:null,
				'readonly'=>'readonly')
		);
		echo $this->Form->input('fechaegresoedit', array(
				'class'=>'datepicker',
				'type'=>'text',
				'label'=>'Egreso',
				'default'=>$this->request->data['Empleado']['fechaegreso']?$this->request->data['Empleado']['fechaegreso']:null,
				'readonly'=>'readonly')
		);
	//Debugger::dump($puntosdeventas);
		echo $this->Form->input('domicilio_id',array('label'=>'Domicilio','options'=>$domicilios));
		echo $this->Form->input('conveniocolectivotrabajo_id',array('label'=>'Convenio Colectivo de Trabajo'));
		echo $this->Form->input('cargo_id',array('label'=>'Cargo', 'required'=>true,));
		echo $this->Form->label("Liquidaciones:");
		echo $this->Form->input('liquidaprimeraquincena',array('label'=>'Primera Quincena'));
		echo $this->Form->input('liquidasegundaquincena',array('label'=>'Segunda Quincena'));
		echo $this->Form->input('liquidamensual',array('label'=>'Mensual'));
		echo $this->Form->input('liquidapresupuestoprimera',array('label'=>'Presupuesto 1'));
		echo $this->Form->input('liquidapresupuestosegunda',array('label'=>'Presupuesto 2'));
		echo $this->Form->input('liquidapresupuestomensual',array('label'=>'Presupuesto 3'));
		?>
	</fieldset>
	<fieldset style="border: 1px solid #1e88e5;">
		<legend style="color:#1e88e5;font-weight:normal;">Familiares</legend>
		<?php
		echo $this->Form->input('conyugue',array('label'=>'Conyugue','value'=>0));
		echo $this->Form->input('hijos',array('label'=>'Hijos','value'=>0));
		?>
	</fieldset>
	<fieldset style="border: 1px solid #1e88e5;">
		<legend style="color:#1e88e5;font-weight:normal;">Datos AFIP</legend>
		<?php

		echo $this->Form->input('jornada',array('label'=>'Jornada','type'=>'select','options'=>array('0.5'=>"Media Jornada",'1'=>"Jornada Completa")));
		echo $this->Form->input('exentocooperadoraasistencial',array('label'=>'Exento Coop. Asistencial'));
		echo $this->Form->input('codigoafip',array('label'=>'Codigo Afip','options'=>array('0','1','2','3','4')));
		echo $this->Form->input('afiliadosindicato',array('label'=>'Afiliado al sindicato'));

		echo $this->Form->input('adherente',array('label'=>'Adherentes','value'=>0));
		echo $this->Form->input('codigoactividad',array('label'=>'Codigo Actividad','options'=>$codigoactividad));
		echo $this->Form->input('codigosituacion',array('label'=>'Codigo Situacion'));
		echo $this->Form->input('codigocondicion',array('label'=>'Codigo Condicion'));
		echo $this->Form->input('codigozona',array('label'=>'Codigo Zona','options'=>$codigozona));
		echo $this->Form->input('codigomodalidadcontratacion',array('label'=>'Codigo Modalidad Contratacion','options'=>$codigomodalidadcontratacion));
		echo $this->Form->input('codigosiniestrado',array('label'=>'Codigo Siniestrado','options'=>$codigosiniestrado));
		echo $this->Form->input('tipoempresa',array('label'=>'Tipo empresa','options'=>$tipoempresa))."</br>";

	echo $this->Form->end(__('Aceptar')); ?>
</div>