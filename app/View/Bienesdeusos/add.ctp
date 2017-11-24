<div class="bienesdeusos form">
<?php echo $this->Form->create('Bienesdeuso',
	[
		'class'=>'formTareaCarga formAddCompra'
	]);
		echo $this->Form->input('id',[
			'type'=>'hidden'
		]);
		echo $this->Form->input('compra_id',[
			'class'=>'all',
			'type'=>'hidden',
			'value'=>isset($compra['Compra']['id'])?$compra['Compra']['id']:0,
		]);
         echo $this->Form->input('venta_id',[
            'type'=>'hidden',
            'label'=>'Venta relacionada',
            'value'=>isset($venta['Venta']['id'])?$venta['Venta']['id']:0,
        ]);
        echo $this->Form->input('cliente_id',[
			'class'=>'all',
			'type'=>'hidden',
			'value'=>$cliente['Cliente']['id'],
		]);
        ?>
    <div class="index">
        <h3>Cuentas Relacionadas al Bien de Uso</h3>
        <?php     /*Estas son las cuentas que se asignaron/asignaran automaticamente a los bienes de uso
         *Si el bien de uso ya estaba creado vamos a necesitar editar estas cuentas y cambiarlas por las viejas
         * con la opciion de borrar las nuevas despues desde el plan de cuentas
         * si estas cuentas tiene valores vamos a darle la posibilidad de editarlas
         * sino tienen valores es por que no se debian cargar o se esta creando el bien de uso*/

        if(isset($this->request->data['Bienesdeuso'])&&!in_array($this->request->data['Bienesdeuso']['cuentaclientevalororigen_id'],[0,null,""])){
            echo $this->Form->input('cuentaclientevalororigen_id',[
                'class'=>'all chosen-select',
                'options'=>$cuentasclientes,
                'default'=>$this->request->data['Bienesdeuso']['cuentaclientevalororigen_id'],
                'label'=>'Cuenta Valor Origen',
                'style'=>'width:200px'
            ]);
        }else{
            echo $this->Form->input('cuentaclientevalororigen_id',[
                'class'=>'all',
                'type'=>'hidden',
            ]);
        }
        if(isset($this->request->data['Bienesdeuso'])&&!in_array($this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id'],[0,null,""])) {
            echo $this->Form->input('cuentaclienteactualizacion_id',[
                'class'=>'all chosen-select',
                'options'=>$cuentasclientes,
                'default'=>$this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id'],
                'label'=>'Cuenta Valor Actualizacion',
                'style'=>'width:200px'

            ]);
        }else{
            echo $this->Form->input('cuentaclienteactualizacion_id', [
                'class' => 'all',
                'type' => 'hidden',
            ]);
        }
        if(isset($this->request->data['Bienesdeuso'])&&!in_array($this->request->data['Bienesdeuso']['cuentaclienteterreno_id'],[0,null,""])) {
            echo $this->Form->input('cuentaclienteterreno_id',[
                'class'=>'all chosen-select',
                'options'=>$cuentasclientes,
                'default'=>$this->request->data['Bienesdeuso']['cuentaclienteterreno_id'],
                'label'=>'Cuenta %Terreno',
                'style'=>'width:200px'
            ]);
        }else{
            echo $this->Form->input('cuentaclienteterreno_id', [
                'class' => 'all',
                'type' => 'hidden',
            ]);
        }
        if(isset($this->request->data['Bienesdeuso'])&&!in_array($this->request->data['Bienesdeuso']['cuentaclienteedificacion_id'],[0,null,""])) {
            echo $this->Form->input('cuentaclienteedificacion_id',[
                'class'=>'all chosen-select',
                'options'=>$cuentasclientes,
                'default'=>$this->request->data['Bienesdeuso']['cuentaclienteedificacion_id'],
                'label'=>'Cuenta %Edificacion',
                'style'=>'width:200px'
            ]);
        }else{
            echo $this->Form->input('cuentaclienteedificacion_id', [
                'class' => 'all',
                'type' => 'hidden',
            ]);
        }

        if(isset($this->request->data['Bienesdeuso'])&&!in_array($this->request->data['Bienesdeuso']['cuentaclientemejora_id'],[0,null,""])) {
             echo $this->Form->input('cuentaclientemejora_id',[
                'class'=>'all chosen-select',
                 'options'=>$cuentasclientes,
                'defaults'=>$this->request->data['Bienesdeuso']['cuentaclientemejora_id'],
                 'label'=>'Cuenta Mejora',
                'style'=>'width:200px'
             ]);
        }else{
            echo $this->Form->input('cuentaclientemejora_id', [
                'class' => 'all',
                'type' => 'hidden',
            ]);
        }
        /**/
        ?>
    </div>
    <div class="index">
        <h3>Datos del Bien de Uso</h3>
        <?php
		if(isset($compra['Actividadcliente'])){
			$actividadCompra = $compra['Actividadcliente']['Cuentasganancia'][0]['categoria'];
			if($actividadCompra=='terceracateg'){
				$options= [
					'3ra Categoria'=>[
						'Inmueble'=>'Inmueble',
						'Rodado'=>'Rodado',
						'Instalaciones'=>'Instalaciones',
						'Otros bienes de uso Muebles'=>'Muebles',
						'Otros bienes de uso Maquinas'=>'Maquinas',
						'Otros bienes de uso Activos Biologicos'=>'Activos Biologicos',
					]
				];
			}else{
				$options= [
					'1ra,2da,3ra Auxiliar y 4ta Categoria'=>[
						'Inmuebles'=>'Inmuebles',
						'Automotor'=>'Automotor',
						'Naves, Yates y similares'=>'Naves, Yates y similares',
						'Aeronave'=>'Aeronave',
						'Bien mueble registrable'=>'Bien mueble registrable',
						'Otros bienes'=>'Otros bienes en el pais',
					],
				];
			}
		}else{
			$options= [
				'3ra Categoria'=>[
					'Inmueble'=>'Inmueble',
					'Rodado'=>'Rodado',
					'Instalaciones'=>'Instalaciones',
					'Otros bienes de uso Muebles'=>'Muebles',
					'Otros bienes de uso Maquinas'=>'Maquinas',
					'Otros bienes de uso Activos Biologicos'=>'Activos Biologicos',
				],
				'1ra,2da,3ra Auxiliar y 4ta Categoria'=>[
					'Inmuebles'=>'Inmuebles',
					'Automotor'=>'Automotor',
					'Naves, Yates y similares'=>'Naves, Yates y similares',
					'Aeronave'=>'Aeronave',
					'Bien mueble registrable'=>'Bien mueble registrable',
                    'Otros bienes'=>'Otros bienes en el pais',
				],
			];
		}

		echo $this->Form->input('tipo',[
            'options'=>$options,
				'class'=>'all',
            'style'=>'width:auto'
		])."</br>";
		echo $this->Form->input('periodo',[
			'class'=>'all datepicker-month-year',
			'value'=>isset($compra['Compra']['periodo'])?$compra['Compra']['periodo']:date('m-Y'),
			'class'=>'all'
		]);
		echo $this->Form->input('titularidad',[
            'label'=>'% de Titularidad',
            'class'=>'inmuebleFNE automotor naves aeronaves bienesmueblesregistrables'
		]);
        echo $this->Form->input('enelpais',[
            'label'=>'Situado en el Pais',
			'class'=>'rodadoPF inmuebleFNE automotor naves aeronaves bienesmueblesregistrables otrosbienes otrobiendeusoPF',
		]);
		/*echo $this->Form->input('marca',[
			'class'=>'automotor rodadoPF rodadoPJ aeronaves'
		]);
		echo $this->Form->input('modelo',[
            'class'=>'automotor rodadoPF rodadoPJ aeronaves',
			'type'=>'text'
		]);*/
        echo $this->Form->input('modelo_id',[
            'class'=>'automotor rodadoPF rodadoPJ aeronaves chosen-select'
        ]);
		/*echo $this->Form->input('fabrica',[
			'class'=>'automotor rodadoPF rodadoPJ'
		]);*/
		echo $this->Form->input('aniofabricacion',[
			'class'=>'automotor rodadoPF rodadoPJ',
            'label'=>'Año fabricacion'
		]);
		echo $this->Form->input('patente',[
			'class'=>'automotor rodadoPF rodadoPJ'
		]);
		echo $this->Form->input('valor',[
			'class'=>'automotor '
		]);
		echo $this->Form->input('amortizado',[
			'class'=>'automotor '
		]);
		echo $this->Form->input('tipoautomotor',[
            'options'=>['Auto'=>'Auto','Moto'=>'Moto','Camion'=>'Camion','Trans. pasajeros'=>'Trans. pasajeros'],
            'class'=>'automotor',
            'label'=>'Tipo automotor'
		]);
		echo $this->Form->input('fechaadquisicion',[
			'class'=>'inmuebleFNE inmuebleFE inmuebleJE bienesmueblesregistrables otrobiendeusoPF otrobiendeusoPJ otrosbienes instalacionesPJ datepicker ',
            'type'=>'text',
            'label'=>'Fecha adquisicion',
            'default'=>"",
            'readonly'=>'readonly',
            'required'=>true,
            'style'=>'width:60px;font:8px'
		]);
        $optionsinmuebletipo=[
            '1'=> 'Casa',
            '2'=> 'Departamento',
            '3'=> 'Departamento con cochera',
            '4'=> 'Cochera',
            '5'=> 'Local',
            '6'=> 'Lote de Terreno',
            '7'=> 'Country,quintas,etc.',
            '8'=> 'Mejoras, Construccion',
            '9'=> 'Rurales con vivienda',
            '10'=> 'Rurales sin vivienda',
            '99'=> 'Otros Inmuebles',
        ];
		echo $this->Form->input('tipoinmueble',[
			'class'=>'inmuebleFNE inmuebleFE inmuebleJE',
            'label'=>'Tipo inmueble',
            'type'=>'select',
            'options'=>$optionsinmuebletipo
		]);
        $optionsinmuebledestino=[
            '1'=> 'Alquiler',
            '2'=> 'Casa Habitacion',
            '3'=> 'Inversion',
            '4'=> 'Afectado a la Explotacion',
            '5'=> 'Recreo o Veraneo',
            '99'=> 'Otros',
        ];
        echo $this->Form->input('destino',[
            'class'=>'inmuebleFNE inmuebleFE inmuebleJE inmuebleJE',
            'type'=>'select',
            'options'=>$optionsinmuebledestino
        ]);
        echo $this->Form->input('calle',[
			'class'=>'inmuebleFNE inmuebleFE inmuebleJE'
		]);
		echo $this->Form->input('numero',[
			'class'=>'inmuebleFNE inmuebleFE inmuebleJE'
		]);
		echo $this->Form->input('piso',[
			'class'=>'inmuebleFNE inmuebleFE inmuebleJE'
		]);
		echo $this->Form->input('depto',[
			'class'=>'inmuebleFNE inmuebleFE inmuebleJE'
		]);
		echo $this->Form->input('localidade_id',[
			'class'=>'inmuebleFNE inmuebleFE inmuebleJE chosen-select',
			'style'=>'width:auto',
            'empty'=>'Localidad'
		]);
		echo $this->Form->input('codigopostal',[
			'class'=>'inmuebleFNE inmuebleFE inmuebleJE',
            'label'=>'Codigo postal'
		]);
		echo $this->Form->input('catastro',[
			'class'=>'inmuebleFNE inmuebleFE inmuebleJE'
		]);
        echo $this->Form->input('pocentajeterreno',[
			'label'=>'% Terreno',
			'class'=>'inmuebleFNE inmuebleFE inmuebleJE'
		]);echo $this->Form->input('pocentajeedificacion',[
			'label'=>'% Edificacion',
			'class'=>'inmuebleFNE inmuebleFE inmuebleJE'
		]);
//		echo $this->Form->input('partido',[
//			'class'=>'inmueble'
//		]);
		echo $this->Form->input('partida',[
			'class'=>'inmuebleFNE inmuebleFE'
		]);
		echo $this->Form->input('digito',[
			'class'=>'inmuebleFNE inmuebleFE'
		]);
		echo $this->Form->input('tipoembarcacion',[
            'class'=>'naves',
            'label'=>'Tipo embarcacion'
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
            'class'=>'naves',
            'label'=>'Tonelaje neto'
        ]);
		echo $this->Form->input('registro',[
            'class'=>'naves'
        ]);
		echo $this->Form->input('registrojur',[
            'class'=>'naves',
            'label'=>'Registro JUR'
        ]);
		echo $this->Form->input('otroregistro',[
            'class'=>'naves',
            'label'=>'Otro registro'
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
            'class'=>'naves',
            'label'=>'Modelo motor'
        ]);
		echo $this->Form->input('potencia',[
            'class'=>'naves'
        ]);
		echo $this->Form->input('numeromotor',[
            'class'=>'naves',
            'label'=>'N motor'
        ]);
		echo $this->Form->input('origen',[
            'class'=>'naves'
        ]);
        echo $this->Form->input('afectacion',[
			'label'=>'% Afectacion',
			'class'=>' rodadoPF instalacionesPF otrobiendeusoPF inmuebleFE'
        ]);
		echo $this->Form->input('participacion',[
			'label'=>'% Participacion',
			'class'=>' rodadoPJ inmuebleJE'
        ]);
        echo $this->Form->input('descripcion',[
            'class'=>'instalacionesPF instalacionesPJ bienesmueblesregistrables otrobiendeusoPF otrobiendeusoPJ otrosbienes '
        ]);
        echo $this->Form->input('datosidentificatorios',[
            'class'=>'bienesmueblesregistrables'
        ]);

        $optionsamortizacionsegunley=[
            '0'=> 'Ninguna Ley',
            '1'=> '24.196 Inversiones Mineras',
            '2'=> '25.924 Inversiones de bienes de capital nuevos y obras de infraestructura',
            '3'=> '25.080 Ley Actividad forestal',
            '8'=> '26.093 Produccion y uso sustentables de Biocombustibles',
            '9'=> '26.123 Promocion del Hidrogeno',
            '10'=> '26,154 Regimenes promocionales para la exploracion y explotacion de hidrocarburos',
            '11'=> '26.190 Regimen de Fomento Nacional para el uso de fuentes renovables de energia destinada a la produccion de energia electrica',
            '12'=> '26.270 Promocion del desarrollo y produccion de la biotecnologia moderna',
            '13'=> '26,360 Promocion de Inversiones en Bienes de Capital y Obras de Infraestructura',
        ];
        echo $this->Form->input('amortizacionaceleradasegunley',[
            'class'=>'  rodadoPF rodadoPJ instalacionesPF instalacionesPJ otrobiendeusoPF otrobiendeusoPJ inmuebleFE inmuebleJE',
            'label'=>'Amortizacion Acelerada segun Ley Nro',
            'type'=>'select',
            'options'=>$optionsamortizacionsegunley
        ]);
        //primero el valor guardado
        //sino esta el valor de la compra
        //sino 0
         $valororiginal = 0;
         if(isset($this->request->data['Bienesdeuso'])&&!in_array($this->request->data['Bienesdeuso']['valororiginal'],[0,null,""])){
            $valororiginal = $this->request->data['Bienesdeuso']['valororiginal'];
         }else{
            if(isset($compra['Compra']['neto'])){
                $valororiginal = $compra['Compra']['neto'];
             }
         }
         
        echo $this->Form->input('valororiginal',[
            'class'=>'rodadoPF rodadoPJ instalacionesPF instalacionesPJ otrobiendeusoPF otrobiendeusoPJ inmuebleFE inmuebleJE',
            'label'=>'Valor original',
            'value'=>$valororiginal
        ]);

        $optionsporcentajeamortizacion=[
            'Actividades Agropecuarias'=> [
                '10'=>'Maquinas (sembradoras, rastras, etc) %10',
                '33'=>'Alambrados y Tranqueras %3,03',
                '20'=>'Molinos y Aguadas/Galpones y Silos %5',
                '4'=> 'Automoviles y Camiones %25',
                '5'=> 'Tractores/Herramientas y Utiles/Tarros tambos/Alfalfares %20',
            ],
            'Reproductores Machos'=> [
                '5'=>'Toros y Carneros %5',
                '10'=>'Padrillos %10',
                '4'=> 'Cerdos %25',               
            ],
            'Reproductores Hembras'=> [
                '8'=>'Vacas %8',
                '5'=>'Ovejas %5',
                '10'=>'Yeguas %10',
            ],
            'Actividades Comerciales e Industriales'=> [
                '50'=>'Edificios de Mamposteria %2',
                '40'=>'Edificios y construcciones de otro tipo %2,5',
                '10'=>'Muebles, utiles e instalaciones/Maquinas en general %10',
                '3'=>'Computadoras y Accesorios de informatica %33,33',
                '5'=>'Autimoviles/Camiones/Instrumental medico %20',
            ],           
        ];
        
        echo $this->Form->input('porcentajeamortizacion',[
            'class'=>'all',
            'label'=>'Porcentaje Amortizacion',
            'type'=>'select',
            'options'=>$optionsporcentajeamortizacion
        ]);
        echo $this->Form->input('amortizacionacumulada',[
            'class'=>'all',
            'label'=>'Amortizacion Acumulada',
        ]);
        echo $this->Form->input('importeamorteizaciondelperiodo',[
            'class'=>'  rodadoPF rodadoPJ instalacionesPF instalacionesPJ otrobiendeusoPF otrobiendeusoPJ inmuebleFE inmuebleJE',
            'label'=>'Importe Amortizacion del Periodo',
        ]);
        echo $this->Form->input('importeamortizacionaceleradadelperiodo',[
            'class'=>'  rodadoPF rodadoPJ instalacionesPF instalacionesPJ inmuebleFE inmuebleJE otrobiendeusoPJ',
            'label'=>'Importe Amortizacion Acelerada del Periodo',
        ]);
        echo $this->Form->input('valuacionganancias',[
            'class'=>'  rodadoPF rodadoPJ  instalacionesPF instalacionesPJ otrobiendeusoPF inmuebleFE inmuebleJE otrobiendeusoPJ',
            'label'=>'Valuacion segun Impuesto a las Ganancias',
        ]);
        echo $this->Form->input('valuacionsinganancias',[
            'class'=>'   rodadoPF instalacionesPF otrobiendeusoPF inmuebleFE inmuebleJE',
            'label'=>'Valuacion s/ Impuesto a las Ganancias, % de afectacion',
        ]);
        ?>
	</div>
	<div class="index">
		<h3>Bienes Personales</h3>
		<?php
		$optionsmotivoexencion = [
			'0'=> 'No Exento',
			'2'=> 'Por Ley',
			'3'=> 'Bienes incluidos en otros bienes del hogar',
			'4'=> 'Computables en el impuesto a la ganancia',
			'5'=> 'Otros',
			'6'=> 'Titulares de la nuda propiedad',
			'7'=> 'Bienes Dados de Baja en el Patrimonio',
		];
		echo $this->Form->input('motivoexencion',[
			'class'=>'inmuebleFNE automotor naves aeronaves bienesmueblesregistrables otrosbienes',
			'label'=>'Motivo de exencion',
			'type'=>'select',
			'options'=>$optionsmotivoexencion
		]);
		echo $this->Form->input('valuacionfiscaltotal',[
			'class'=>'inmuebleFNE',
			'label'=>'Valuacion fiscal',
		]);
		echo $this->Form->input('valorcompraactualizado',[
			'class'=>'inmuebleFNE',
			'label'=>'Valor compra actualizado',
		]);
		echo $this->Form->input('importe',[
			'class'=>'aeronaves automotor naves bienesmueblesregistrables otrosbienes'
		]);
		echo $this->Form->input('importeexento',[
			'class'=>'inmuebleFNE automotor naves aeronaves bienesmueblesregistrables otrosbienes ',
			'label'=>'Importe exento',
		]);
		?>
	</div>
	<div class="index">
		<h3>Ganancias Persona Fisica</h3>
		<?php
		echo $this->Form->input('bienafectadoatercera',[
			'class'=>'inmuebleFNE automotor naves aeronaves bienesmueblesregistrables otrosbienes',
			'label'=>'Bien afectado a la 3ra categoria',
		]);
		echo $this->Form->input('bienafectadoacuarta',[
			'class'=>'inmuebleFNE automotor naves aeronaves bienesmueblesregistrables otrosbienes',
			'label'=>'Bien afectado a la 4ta categoria',
		]);
		echo $this->Form->input('gananciasimportetotalperiodoanterior',[
			'class'=>'inmuebleFNE automotor naves aeronaves bienesmueblesregistrables otrosbienes',
			'label'=>'importe Total al periodo Anterior',
		]);
		echo $this->Form->input('gananciasimportetotal',[
            'class'=>'inmuebleFNE automotor naves aeronaves bienesmueblesregistrables otrosbienes',
            'label'=>'importe Total al periodo Actual',
        ]);            
		?>
	</div>
<?php echo $this->Form->end(__('Aceptar')); ?>
</div>

