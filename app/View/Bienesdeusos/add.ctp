<div class="bienesdeusos form">
<?php echo $this->Form->create('Bienesdeuso',
	[
		'class'=>'formTareaCarga formAddCompra'
	]); ?>
	<fieldset>
	<?php
		echo $this->Form->input('compra_id',[
			'class'=>'all',
			'type'=>'text',
			'value'=>$compra['Compra']['id'],
		]);
		echo $this->Form->input('tipo',[
            'options'=>['Automotor'=>'Automotor','Inmueble'=>'Inmueble','Aeronave'=>'Aeronave','Naves, Yates y similares'=>'Naves, Yates y similares'],
            'class'=>'all'
		]);
		echo $this->Form->input('periodo',[
			'class'=>'all'
		]);
		echo $this->Form->input('titularidad',[
            'label'=>'% de Titularidad',
            'class'=>'all'
		]);
		echo $this->Form->input('marca',[
			'class'=>'automotor aeronaves'
		]);
		echo $this->Form->input('modelo',[
			'class'=>'automotor aeronaves'
		]);
		echo $this->Form->input('fabrica',[
			'class'=>'automotor'
		]);
		echo $this->Form->input('aniofabricacion',[
			'class'=>'automotor'
		]);
		echo $this->Form->input('patente',[
			'class'=>'automotor'
		]);
		echo $this->Form->input('valor',[
			'class'=>'automotor'
		]);
		echo $this->Form->input('amortizado',[
			'class'=>'automotor'
		]);
		echo $this->Form->input('tipoautomotor',[
            'options'=>['Auto'=>'Auto','Moto'=>'Moto','Camion'=>'Camion','Trans. pasajeros'=>'Trans. pasajeros'],
            'class'=>'automotor'
		]);
		echo $this->Form->input('fechaadquisicion',[
			'class'=>'inmueble datepicker',
            'type'=>'text',
            'label'=>'Fecha adquisicion',
            'default'=>"",
            'readonly'=>'readonly',
            'required'=>true,
            'style'=>'width:60px;font:8px'
		]);
		echo $this->Form->input('tipoinmueble',[
			'class'=>'inmueble'
		]);
		echo $this->Form->input('calle',[
			'class'=>'inmueble'
		]);
		echo $this->Form->input('destino',[
			'class'=>'inmueble'
		]);
		echo $this->Form->input('numero',[
			'class'=>'inmueble'
		]);
		echo $this->Form->input('piso',[
			'class'=>'inmueble'
		]);
		echo $this->Form->input('depto',[
			'class'=>'inmueble'
		]);
		echo $this->Form->input('localidade_id',[
			'class'=>'inmueble'
		]);
		echo $this->Form->input('codigopostal',[
			'class'=>'inmueble'
		]);
		echo $this->Form->input('catastro',[
			'class'=>'inmueble'
		]);
		echo $this->Form->input('partido',[
			'class'=>'inmueble'
		]);
		echo $this->Form->input('partida',[
			'class'=>'inmueble'
		]);
		echo $this->Form->input('digito',[
			'class'=>'inmueble'
		]);
		echo $this->Form->input('tipoembarcacion',[
            'class'=>'naves'
        ]);
		echo $this->Form->input('nombre',[
            'class'=>'naves'
        ]);
		echo $this->Form->input('eslora',[
            'class'=>'naves'
        ]);
		echo $this->Form->input('manga',[
            'class'=>'naves'
        ]);
		echo $this->Form->input('tonelajeneto',[
            'class'=>'naves'
        ]);
		echo $this->Form->input('registro',[
            'class'=>'naves'
        ]);
		echo $this->Form->input('registrojur',[
            'class'=>'naves'
        ]);
		echo $this->Form->input('otroregistro',[
            'class'=>'naves'
        ]);
		echo $this->Form->input('matricula',[
            'class'=>'naves aeronaves'
        ]);
		echo $this->Form->input('cantidadmotores',[
            'class'=>'naves'
        ]);
		echo $this->Form->input('marcamotor',[
            'class'=>'naves'
        ]);
		echo $this->Form->input('modelomotor',[
            'class'=>'naves'
        ]);
		echo $this->Form->input('potencia',[
            'class'=>'naves'
        ]);
		echo $this->Form->input('numeromotor',[
            'class'=>'naves'
        ]);
		echo $this->Form->input('origen',[
            'class'=>'naves'
        ]);
	?>
	</fieldset>
<?php echo $this->Form->end(__('Aceptar')); ?>
</div>

