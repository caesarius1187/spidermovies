<div id="form_empleado" class="" style="width: 94%;">
	<?php echo $this->Form->create('Empleado',array('class'=>'formTareaCarga','controller'=>'Empelados','action'=>'add','id'=>'EmpleadoEditForm')); ?>
	<h3><?php echo __('Editar Empleado'); ?></h3>
        <fieldset style="">
		<legend style="color:#1e88e5;font-weight:normal;">Datos Personales</legend>
		<?php
		echo $this->Form->input('id',array('type'=>'hidden'));
		echo $this->Form->input('cliente_id',array('type'=>'hidden'));
		echo $this->Form->input('legajo',array('label'=>'Legajo'));
		echo $this->Form->input('nombre',array('style'=>'width:150px'));
		echo $this->Form->input('cuit',array('label'=>'CUIT',));
		echo $this->Form->input('dni',array('label'=>'DNI'));
		echo $this->Form->input('localidade_id',array(
			'label'=>'Localidad',
                        'type'=>'select',
                        'class'=>'chosen-select',
                        'options'=>$localidades,
                        'style'=>'width:250px'
			)
                    );
		echo $this->Form->input('domicilio',array('label'=>'Domicilio','type'=>'text','style'=>'width:250px'));
		echo $this->Form->input('hijos',array('label'=>'Hijos','value'=>0));
                echo "</br>";
		echo $this->Form->input('titulosecundario',array('label'=>'Titulo Secundario'));
		echo $this->Form->input('titulouniversitario',array('label'=>'Titulo Universitario'));
                echo $this->Form->input('conyugue',array('label'=>'Conyugue','value'=>0));
		?>
	</fieldset>
	<fieldset style="">
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
		echo $this->Form->input('impuesto_id',array(
			'label'=>'Banco',
			'title'=>'Elija el banco donde se va a pagarle al empleado',
			'empty'=>'Efectivo',
			'options'=>$bancos,
			'default'=>$this->request->data['Empleado']['impuesto_id'],
		));
		echo $this->Form->input('conveniocolectivotrabajo_id',array('label'=>'Convenio Colectivo de Trabajo'));
		echo $this->Form->input('cargo_id',array('label'=>'Cargo', 'required'=>true,));
                echo $this->Form->input('jornada',array('label'=>'Jornada','type'=>'select','options'=>array('0.5'=>"Media Jornada",'1'=>"Jornada Completa")));

		echo $this->Form->label("Liquidaciones:");
		echo $this->Form->input('liquidaprimeraquincena',array('label'=>'Primera Quincena'));
		echo $this->Form->input('liquidasegundaquincena',array('label'=>'Segunda Quincena'));
		echo $this->Form->input('liquidamensual',array('label'=>'Mensual'));
		echo $this->Form->input('liquidasac',array('label'=>'SAC'));
		echo $this->Form->input('liquidapresupuestoprimera',array('label'=>'Presupuesto 1'));
		echo $this->Form->input('liquidapresupuestosegunda',array('label'=>'Presupuesto 2'));
		echo $this->Form->input('liquidapresupuestomensual',array('label'=>'Presupuesto 3'));
                echo "</br>";
                echo $this->Form->input('afiliadosindicato',array('label'=>'Afiliado al sindicato'));
                echo $this->Form->input('fallodecaja',array('label'=>'Paga Fallo de Caja'));

		?>
	</fieldset>	
	<fieldset style="">
		<legend style="color:#1e88e5;font-weight:normal;">Datos AFIP</legend>
		<?php

		echo $this->Form->input('codigoafip',array(
				'label'=>'Codigo Afip',
				'options'=>array(
					'0'=>'0%',
					'3'=>'25%',
					'1'=>'50%',
					'2'=>'75%',
					'4'=>'100%',
				)
			)
		);

		echo $this->Form->input('adherente',array('label'=>'Adherentes','value'=>0));
                echo "</br>";
                echo $this->Form->input('exentocooperadoraasistencial',array('label'=>'Exento Coop. Asistencial'));
		
		echo $this->Form->input('obrasocialsindical',array(
			'label'=>'Obra social Sindical',
			'title'=>'Indicar si el empleado tiene una obra social que no sea sindical'));
                echo "</br>";
                echo $this->Form->input('obrassociale_id',array(
                            'label'=>'Obra Social',
                            'class'=>'chosen-select',
                            )
                        );
		echo $this->Form->input('codigoactividad',array('label'=>'Codigo Actividad','options'=>$codigoactividad,'class'=>'chosen-select',));
                echo $this->Form->input('codigosituacion',array('label'=>'Codigo Situacion','options'=>$codigorevista,'class'=>'chosen-select',));
                echo $this->Form->input('codigocondicion',array('label'=>'Codigo Condicion','options'=>$codigocondicion,'class'=>'chosen-select',));
                echo $this->Form->input('codigozona',array('label'=>'Codigo Zona','options'=>$codigozona,'class'=>'chosen-select',));
                echo $this->Form->input('codigomodalidadcontratacion',array('label'=>'Codigo Modalidad Contratacion','options'=>$codigomodalidadcontratacion,'class'=>'chosen-select',));
                echo $this->Form->input('codigosiniestrado',array('label'=>'Codigo Siniestrado','options'=>$codigosiniestrado,'class'=>'chosen-select',));
                echo $this->Form->input('tipoempresa',array('label'=>'Tipo empresa','options'=>$tipoempresa,'class'=>'chosen-select',))."</br>";

	echo $this->Form->end(__('Aceptar')); ?>
</div>