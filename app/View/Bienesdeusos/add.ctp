<div class="bienesdeusos form">
<?php echo $this->Form->create('Bienesdeuso',
	[
		'class'=>'formTareaCarga formAddCompra'
	]);
        $id=0;
        $venta_id = "";
        $compra_id = $compraid;
        $localidade_id = "";
        $tipo = "";
        $modelo_id = "";
        $titularidad = "";
        $aniofabricacion = "";
        $patente = "";
        $valor = "";
        $amortizado = "";
        $enelpais = "";
        $tipoautomotor = "";
        $fechaadquisicion = "";
        $tipoinmueble = "";
        $calle = "";
        $destino = "";
        $numero = "";
        $piso = "";
        $depto = "";
        $codigopostal = "";
        $catastro = "";
        $porcentajeterreno = "";
        $porcentajeedificacion = "";
        $partido = "";
        $partida = "";
        $digito = "";
        $tipoembarcacion = "";
        $nombre = "";
        $eslora = "";
        $manga = "";
        $tonelajeneto = "";
        $registro = "";
        $registrojur = "";
        $otroregistro = "";
        $matricula = "";
        $cantidadmotores = "";
        $marcamotor = "";
        $modelomotor = "";
        $potencia = "";
        $numeromotor = "";
        $origen = "";
        $motivoexencion = "";
        $afectacion = "";
        $participacion = "";
        $descripcion = "";
        $datosidentificatorios = "";
        $amortizacionaceleradasegunley = "";
        $importeamorteizaciondelperiodo = "";
        $importeamortizacionaceleradadelperiodo = "";
        $amortizacionacumulada = "";
        $valuacionganancias = "";
        $valuacionsinganancias = "";
        $valuacionfiscaltotal = "";
        $valorcompraactualizado = "";
        $importe = "";
        $importeexento = "";
        $bienafectadoatercera = "";
        $bienafectadoacuarta = "";
        $bienusopersonal = "";
        $gananciasimportetotalperiodoanterior = "";
        $gananciasimportetotal = "";
        $valororiginal = "";
        $porcentajeamortizacion = "";
        if(isset($this->request->data['Bienesdeuso'])){
            $id = $this->request->data['Bienesdeuso']['id'];
            $venta_id = $this->request->data['Bienesdeuso']['venta_id'];
            $compra_id = $this->request->data['Bienesdeuso']['compra_id'];
            $localidade_id = $this->request->data['Bienesdeuso']['localidade_id'];
            $tipo = $this->request->data['Bienesdeuso']['tipo'];
            $modelo_id =$this->request->data['Bienesdeuso']['modelo_id'];
            $titularidad =$this->request->data['Bienesdeuso']['titularidad'];
            $aniofabricacion =$this->request->data['Bienesdeuso']['aniofabricacion'];
            $patente =$this->request->data['Bienesdeuso']['patente'];
            $valor =$this->request->data['Bienesdeuso']['valor'];
            $amortizado =$this->request->data['Bienesdeuso']['amortizado'];
            $enelpais =$this->request->data['Bienesdeuso']['enelpais'];
            $tipoautomotor =$this->request->data['Bienesdeuso']['tipoautomotor'];
            $fechaadquisicion =$this->request->data['Bienesdeuso']['fechaadquisicion'];
            $tipoinmueble =$this->request->data['Bienesdeuso']['tipoinmueble'];
            $calle =$this->request->data['Bienesdeuso']['calle'];
            $destino =$this->request->data['Bienesdeuso']['destino'];
            $numero =$this->request->data['Bienesdeuso']['numero'];
            $piso =$this->request->data['Bienesdeuso']['piso'];
            $depto =$this->request->data['Bienesdeuso']['depto'];
            $codigopostal =$this->request->data['Bienesdeuso']['codigopostal'];
            $catastro =$this->request->data['Bienesdeuso']['catastro'];
            $porcentajeterreno =$this->request->data['Bienesdeuso']['porcentajeterreno'];
            $porcentajeedificacion =$this->request->data['Bienesdeuso']['porcentajeedificacion'];
            $partido =$this->request->data['Bienesdeuso']['partido'];
            $partida =$this->request->data['Bienesdeuso']['partida'];
            $digito =$this->request->data['Bienesdeuso']['digito'];
            $tipoembarcacion =$this->request->data['Bienesdeuso']['tipoembarcacion'];
            $nombre =$this->request->data['Bienesdeuso']['nombre'];
            $eslora =$this->request->data['Bienesdeuso']['eslora'];
            $manga =$this->request->data['Bienesdeuso']['manga'];
            $tonelajeneto =$this->request->data['Bienesdeuso']['tonelajeneto'];
            $registro =$this->request->data['Bienesdeuso']['registro'];
            $registrojur =$this->request->data['Bienesdeuso']['registrojur'];
            $otroregistro =$this->request->data['Bienesdeuso']['otroregistro'];
            $matricula =$this->request->data['Bienesdeuso']['matricula'];
            $cantidadmotores =$this->request->data['Bienesdeuso']['cantidadmotores'];
            $marcamotor =$this->request->data['Bienesdeuso']['marcamotor'];
            $modelomotor =$this->request->data['Bienesdeuso']['modelomotor'];
            $potencia =$this->request->data['Bienesdeuso']['potencia'];
            $numeromotor =$this->request->data['Bienesdeuso']['numeromotor'];
            $origen =$this->request->data['Bienesdeuso']['origen'];
            $motivoexencion =$this->request->data['Bienesdeuso']['motivoexencion'];
            $afectacion =$this->request->data['Bienesdeuso']['afectacion'];
            $participacion =$this->request->data['Bienesdeuso']['participacion'];
            $descripcion =$this->request->data['Bienesdeuso']['descripcion'];
            $datosidentificatorios =$this->request->data['Bienesdeuso']['datosidentificatorios'];
            $amortizacionaceleradasegunley =$this->request->data['Bienesdeuso']['amortizacionaceleradasegunley'];
            $importeamorteizaciondelperiodo =$this->request->data['Bienesdeuso']['importeamorteizaciondelperiodo'];
            $importeamortizacionaceleradadelperiodo =$this->request->data['Bienesdeuso']['importeamortizacionaceleradadelperiodo'];
            $amortizacionacumulada =$this->request->data['Bienesdeuso']['amortizacionacumulada'];
            $valuacionganancias =$this->request->data['Bienesdeuso']['valuacionganancias'];
            $valuacionsinganancias =$this->request->data['Bienesdeuso']['valuacionsinganancias'];
            $valuacionfiscaltotal =$this->request->data['Bienesdeuso']['valuacionfiscaltotal'];
            $valorcompraactualizado =$this->request->data['Bienesdeuso']['valorcompraactualizado'];
            $importe =$this->request->data['Bienesdeuso']['importe'];
            $importeexento =$this->request->data['Bienesdeuso']['importeexento'];
            $bienafectadoatercera =$this->request->data['Bienesdeuso']['bienafectadoatercera'];
            $bienafectadoacuarta =$this->request->data['Bienesdeuso']['bienafectadoacuarta'];
            $bienusopersonal =$this->request->data['Bienesdeuso']['bienusopersonal'];
            $gananciasimportetotalperiodoanterior =$this->request->data['Bienesdeuso']['gananciasimportetotalperiodoanterior'];
            $gananciasimportetotal =$this->request->data['Bienesdeuso']['gananciasimportetotal'];
            $valororiginal =$this->request->data['Bienesdeuso']['valororiginal'];
            $porcentajeamortizacion =$this->request->data['Bienesdeuso']['porcentajeamortizacion'];
        }
        echo $this->Form->input('Bienesdeuso.0.id',[
                'type'=>'hidden',
                'value'=>$id
        ]);
        echo $this->Form->input('Bienesdeuso.0.compra_id',[
                'class'=>'all',
                'type'=>'hidden',
            'value'=>$compra_id!=null?$compra_id:0
        ]);
         echo $this->Form->input('Bienesdeuso.0.venta_id',[
            'type'=>'hidden',
            'label'=>'Venta relacionada',
            'value'=>$venta_id,
        ]);
        echo $this->Form->input('Bienesdeuso.0.cliente_id',[
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
            echo $this->Form->input('Bienesdeuso.0.cuentaclientevalororigen_id',[
                'class'=>'all chosen-select',
                'options'=>$cuentasclientes,
                'default'=>$this->request->data['Bienesdeuso']['cuentaclientevalororigen_id'],
                'label'=>'Cuenta Valor Origen',
                'style'=>'width:200px'
            ]);
        }else{
            echo $this->Form->input('Bienesdeuso.0.cuentaclientevalororigen_id',[
                'class'=>'all',
                'type'=>'hidden',
            ]);
        }
        if(isset($this->request->data['Bienesdeuso'])&&!in_array($this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id'],[0,null,""])) {
            echo $this->Form->input('Bienesdeuso.0.cuentaclienteactualizacion_id',[
                'class'=>'all chosen-select',
                'options'=>$cuentasclientes,
                'default'=>$this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id'],
                'label'=>'Cuenta Valor Actualizacion',
                'style'=>'width:200px'

            ]);
        }else{
            echo $this->Form->input('Bienesdeuso.0.cuentaclienteactualizacion_id', [
                'class' => 'all',
                'type' => 'hidden',
            ]);
        }
        if(isset($this->request->data['Bienesdeuso'])&&!in_array($this->request->data['Bienesdeuso']['cuentaclienteterreno_id'],[0,null,""])) {
            echo $this->Form->input('Bienesdeuso.0.cuentaclienteterreno_id',[
                'class'=>'all chosen-select',
                'options'=>$cuentasclientes,
                'default'=>$this->request->data['Bienesdeuso']['cuentaclienteterreno_id'],
                'label'=>'Cuenta %Terreno',
                'style'=>'width:200px'
            ]);
        }else{
            echo $this->Form->input('Bienesdeuso.0.cuentaclienteterreno_id', [
                'class' => 'all',
                'type' => 'hidden',
            ]);
        }
        if(isset($this->request->data['Bienesdeuso'])&&!in_array($this->request->data['Bienesdeuso']['cuentaclienteedificacion_id'],[0,null,""])) {
            echo $this->Form->input('Bienesdeuso.0.cuentaclienteedificacion_id',[
                'class'=>'all chosen-select',
                'options'=>$cuentasclientes,
                'default'=>$this->request->data['Bienesdeuso']['cuentaclienteedificacion_id'],
                'label'=>'Cuenta %Edificacion',
                'style'=>'width:200px'
            ]);
        }else{
            echo $this->Form->input('Bienesdeuso.0.cuentaclienteedificacion_id', [
                'class' => 'all',
                'type' => 'hidden',
            ]);
        }
        if(isset($this->request->data['Bienesdeuso'])&&!in_array($this->request->data['Bienesdeuso']['cuentaclientemejora_id'],[0,null,""])) {
             echo $this->Form->input('Bienesdeuso.0.cuentaclientemejora_id',[
                'class'=>'all chosen-select',
                 'options'=>$cuentasclientes,
                'defaults'=>$this->request->data['Bienesdeuso']['cuentaclientemejora_id'],
                 'label'=>'Cuenta Mejora',
                'style'=>'width:200px'
             ]);
        }else{
            echo $this->Form->input('Bienesdeuso.0.cuentaclientemejora_id', [
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
        echo $this->Form->input('Bienesdeuso.0.tipo',[
            'options'=>$options,
            'class'=>'all',
            'style'=>'width:auto',
            'value'=>$tipo
        ])."</br>";
        $periodo=isset($compra['Compra']['periodo'])?$compra['Compra']['periodo']:date('m-Y');
        if(isset($this->request->data['Bienesdeuso']['periodo'])){
            $periodo = $this->request->data['Bienesdeuso']['periodo'];                    
        }
        echo $this->Form->input('Bienesdeuso.0.periodo',[
            'class'=>'all datepicker-month-year',
            'value'=>$periodo,
            'class'=>'all'
        ]);
        echo $this->Form->input('Bienesdeuso.0.titularidad',[
            'label'=>'% de Titularidad',
            'value'=>$titularidad,
            'class'=>'inmuebleFNE automotor naves aeronaves bienesmueblesregistrables'
        ]);
        echo $this->Form->input('Bienesdeuso.0.enelpais',[
            'label'=>'Situado en el Pais',
            'class'=>'rodadoPF inmuebleFNE automotor naves aeronaves bienesmueblesregistrables otrosbienes otrobiendeusoPF',
            'checked'=>$enelpais?true:false, 
        ]);
		
        echo $this->Form->input('Bienesdeuso.0.modelo_id',[
            'class'=>'automotor rodadoPF rodadoPJ aeronaves chosen-select',
            'default'=>$modelo_id
        ]);
        echo $this->Form->input('Bienesdeuso.0.aniofabricacion',[
            'class'=>'automotor rodadoPF rodadoPJ',
            'label'=>'Año fabricacion',
            'value'=>$aniofabricacion
        ]);
        echo $this->Form->input('Bienesdeuso.0.patente',[
            'class'=>'automotor rodadoPF rodadoPJ',
            'value'=>$patente
        ]);
        echo $this->Form->input('Bienesdeuso.0.valor',[
            'class'=>'automotor ',
            'value'=>$valor
        ]);
        echo $this->Form->input('Bienesdeuso.0.amortizado',[
            'class'=>'automotor ',
             'value'=>$amortizado
        ]);
        echo $this->Form->input('Bienesdeuso.0.tipoautomotor',[
            'options'=>['Auto'=>'Auto','Moto'=>'Moto','Camion'=>'Camion','Trans. pasajeros'=>'Trans. pasajeros'],
            'class'=>'automotor',
            'label'=>'Tipo automotor',
            'default'=>$tipoautomotor
        ]);
        echo $this->Form->input('Bienesdeuso.0.fechaadquisicion',[
            'class'=>'inmuebleFNE inmuebleFE inmuebleJE bienesmueblesregistrables otrobiendeusoPF otrobiendeusoPJ otrosbienes instalacionesPJ datepicker ',
            'type'=>'text',
            'label'=>'Fecha adquisicion',
            'default'=>$fechaadquisicion,
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
        echo $this->Form->input('Bienesdeuso.0.tipoinmueble',[
                'class'=>'inmuebleFNE inmuebleFE inmuebleJE',
                'label'=>'Tipo inmueble',
                'type'=>'select',
                'default'=>$tipoinmueble,
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
        echo $this->Form->input('Bienesdeuso.0.destino',[
            'class'=>'inmuebleFNE inmuebleFE inmuebleJE inmuebleJE',
            'type'=>'select',
            'options'=>$optionsinmuebledestino,
            'default'=>$destino,
        ]);
        echo $this->Form->input('Bienesdeuso.0.calle',[
            'class'=>'inmuebleFNE inmuebleFE inmuebleJE',
            'value'=>$calle,
        ]);
        echo $this->Form->input('Bienesdeuso.0.numero',[
            'class'=>'inmuebleFNE inmuebleFE inmuebleJE',
            'value'=>$numero,
        ]);
        echo $this->Form->input('Bienesdeuso.0.piso',[
            'class'=>'inmuebleFNE inmuebleFE inmuebleJE',
            'value'=>$piso,
        ]);
        echo $this->Form->input('Bienesdeuso.0.depto',[
            'class'=>'inmuebleFNE inmuebleFE inmuebleJE',
            'value'=>$depto,
        ]);
        echo $this->Form->input('Bienesdeuso.0.localidade_id',[
            'class'=>'inmuebleFNE inmuebleFE inmuebleJE chosen-select',
            'style'=>'width:auto',
            'empty'=>'Localidad',
            'default'=>$localidade_id,
        ]);
        echo $this->Form->input('Bienesdeuso.0.codigopostal',[
            'class'=>'inmuebleFNE inmuebleFE inmuebleJE',
            'label'=>'Codigo postal',
            'value'=>$codigopostal,
        ]);
        echo $this->Form->input('Bienesdeuso.0.catastro',[
            'class'=>'inmuebleFNE inmuebleFE inmuebleJE',
            'value'=>$catastro,
        ]);
        echo $this->Form->input('Bienesdeuso.0.porcentajeterreno',[
            'label'=>'% Terreno',
            'class'=>'inmuebleFNE inmuebleFE inmuebleJE',
            'value'=>$porcentajeterreno,
        ]);echo $this->Form->input('Bienesdeuso.0.porcentajeedificacion',[
            'label'=>'% Edificacion',
            'class'=>'inmuebleFNE inmuebleFE inmuebleJE',
            'value'=>$porcentajeedificacion,
        ]);
//		echo $this->Form->input('Bienesdeuso.0.partido',[
//			'class'=>'inmueble'
//		]);
        echo $this->Form->input('Bienesdeuso.0.partida',[
            'class'=>'inmuebleFNE inmuebleFE',
            'value'=>$partida,
        ]);
        echo $this->Form->input('Bienesdeuso.0.digito',[
            'class'=>'inmuebleFNE inmuebleFE',
            'value'=>$digito,
        ]);
        echo $this->Form->input('Bienesdeuso.0.tipoembarcacion',[
            'class'=>'naves',
            'label'=>'Tipo embarcacion',
            'value'=>$tipoembarcacion,
        ]);
        echo $this->Form->input('Bienesdeuso.0.nombre',[
            'class'=>'naves',
            'value'=>$nombre,
        ]);
        echo $this->Form->input('Bienesdeuso.0.eslora',[
            'class'=>'naves',
            'value'=>$eslora,
        ]);
        echo $this->Form->input('Bienesdeuso.0.manga',[
            'class'=>'naves',
            'value'=>$manga,
        ]);
		echo $this->Form->input('Bienesdeuso.0.tonelajeneto',[
            'class'=>'naves',
            'label'=>'Tonelaje neto',
            'value'=>$tonelajeneto,
        ]);
		echo $this->Form->input('Bienesdeuso.0.registro',[
            'class'=>'naves',
            'value'=>$registro,
        ]);
		echo $this->Form->input('Bienesdeuso.0.registrojur',[
            'class'=>'naves',
            'label'=>'Registro JUR',
            'value'=>$registrojur,
        ]);
		echo $this->Form->input('Bienesdeuso.0.otroregistro',[
            'class'=>'naves',
            'label'=>'Otro registro',
            'value'=>$otroregistro,
        ]);
		echo $this->Form->input('Bienesdeuso.0.matricula',[
            'class'=>'naves aeronaves',
            'value'=>$matricula,
        ]);
		echo $this->Form->input('Bienesdeuso.0.cantidadmotores',[
            'class'=>'naves',
            'value'=>$cantidadmotores,
        ]);
		echo $this->Form->input('Bienesdeuso.0.marcamotor',[
            'class'=>'naves',
            'value'=>$marcamotor,
        ]);
		echo $this->Form->input('Bienesdeuso.0.modelomotor',[
            'class'=>'naves',
            'label'=>'Modelo motor',
            'value'=>$modelomotor,
        ]);
		echo $this->Form->input('Bienesdeuso.0.potencia',[
            'class'=>'naves',
            'value'=>$potencia,
        ]);
        echo $this->Form->input('Bienesdeuso.0.numeromotor',[
            'class'=>'naves',
            'label'=>'N motor',
            'value'=>$numeromotor,
        ]);
        echo $this->Form->input('Bienesdeuso.0.origen',[
            'class'=>'naves',
            'value'=>$origen,
        ]);
        echo $this->Form->input('Bienesdeuso.0.afectacion',[
            'label'=>'% Afectacion',
            'class'=>' rodadoPF instalacionesPF otrobiendeusoPF inmuebleFE',
            'value'=>$afectacion,
        ]);
        echo $this->Form->input('Bienesdeuso.0.participacion',[
            'label'=>'% Participacion',
            'class'=>' rodadoPJ inmuebleJE',
            'value'=>$participacion,
        ]);
        echo $this->Form->input('Bienesdeuso.0.descripcion',[
            'class'=>'instalacionesPF instalacionesPJ bienesmueblesregistrables otrobiendeusoPF otrobiendeusoPJ otrosbienes ',
            'value'=>$descripcion,
        ]);
        echo $this->Form->input('Bienesdeuso.0.datosidentificatorios',[
            'class'=>'bienesmueblesregistrables',
            'value'=>$datosidentificatorios,
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
        echo $this->Form->input('Bienesdeuso.0.amortizacionaceleradasegunley',[
            'class'=>'  rodadoPF rodadoPJ instalacionesPF instalacionesPJ otrobiendeusoPF otrobiendeusoPJ inmuebleFE inmuebleJE',
            'label'=>'Amortizacion Acelerada segun Ley Nro',
            'type'=>'select',
            'options'=>$optionsamortizacionsegunley,
            'default'=>$amortizacionaceleradasegunley,
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
         
        echo $this->Form->input('Bienesdeuso.0.valororiginal',[
            'class'=>'rodadoPF rodadoPJ instalacionesPF instalacionesPJ otrobiendeusoPF otrobiendeusoPJ inmuebleFE inmuebleJE',
            'label'=>'Valor original',
            'value'=>$valororiginal,
        ]);

        $optionsporcentajeamortizacion=[
            '0'=>'Sin amortizacion',
            'Actividades Agropecuarias'=> [
                '10'=>'Maquinas (sembradoras, rastras, etc) %10',
                '3.03'=>'Alambrados y Tranqueras %3,03',
                '5'=>'Molinos y Aguadas/Galpones y Silos %5',
                '25'=> 'Automoviles y Camiones %25',
                '20'=> 'Tractores/Herramientas y Utiles/Tarros tambos/Alfalfares %20',
            ],
            'Reproductores Machos'=> [
                '20'=>'Toros y Carneros %20',
                '10'=>'Padrillos %10',
                '25'=> 'Cerdos %25',               
            ],
            'Reproductores Hembras'=> [
                '12.5'=>'Vacas %12,5',
                '20'=>'Ovejas %20',
                '10'=>'Yeguas %10',
            ],
            'Actividades Comerciales e Industriales'=> [
                '2'=>'Edificios de Mamposteria %2',
                '2.5'=>'Edificios y construcciones de otro tipo %2,5',
                '10'=>'Muebles, utiles e instalaciones/Maquinas en general %10',
                '33.33'=>'Computadoras y Accesorios de informatica %33,33',
                '20'=>'Autimoviles/Camiones/Instrumental medico %20',
            ],           
        ];
        
        echo $this->Form->input('Bienesdeuso.0.porcentajeamortizacion',[
            'class'=>'all',
            'label'=>'Porcentaje Amortizacion',
            'type'=>'select',
            'options'=>$optionsporcentajeamortizacion,
            'value'=>$porcentajeamortizacion,
        ]);
        echo $this->Form->input('Bienesdeuso.0.amortizacionacumulada',[
            'class'=>'all',
            'label'=>'Amortizacion Acumulada',
            'value'=>$amortizacionacumulada,
        ]);
        echo $this->Form->input('Bienesdeuso.0.importeamorteizaciondelperiodo',[
            'class'=>'  rodadoPF rodadoPJ instalacionesPF instalacionesPJ otrobiendeusoPF otrobiendeusoPJ inmuebleFE inmuebleJE',
            'label'=>'Importe Amortizacion del Periodo',
            'value'=>$importeamorteizaciondelperiodo,
        ]);
        echo $this->Form->button('Habilitar Carga Amortizacion Especial',
            array('type' => 'button',
                'class' =>"",
                'label'=>' ',
                'onClick' => "document.getElementById('Bienesdeuso0Amortizacione0Periodo').disabled = false;"
                . "document.getElementById('Bienesdeuso0Amortizacione0Amortizacionejercicio').disabled = false;"
                . "document.getElementById('Bienesdeuso0Amortizacione0Amortizacionacumulada').disabled = false;"
                . "document.getElementById('amortizacionesespeciales').style.display = 'block';"
            )
        );
        echo $this->Form->input('Bienesdeuso.0.importeamortizacionaceleradadelperiodo',[
            'class'=>'  rodadoPF rodadoPJ instalacionesPF instalacionesPJ inmuebleFE inmuebleJE otrobiendeusoPJ',
            'label'=>'Importe Amortizacion Acelerada del Periodo',
            'value'=>$importeamortizacionaceleradadelperiodo,
        ]);
        echo $this->Form->input('Bienesdeuso.0.valuacionganancias',[
            'class'=>'  rodadoPF rodadoPJ  instalacionesPF instalacionesPJ otrobiendeusoPF inmuebleFE inmuebleJE otrobiendeusoPJ',
            'label'=>'Valuacion segun Impuesto a las Ganancias',
            'value'=>$importeamortizacionaceleradadelperiodo,
        ]);
        echo $this->Form->input('Bienesdeuso.0.valuacionsinganancias',[
            'class'=>'   rodadoPF instalacionesPF otrobiendeusoPF inmuebleFE inmuebleJE',
            'label'=>'Valuacion s/ Impuesto a las Ganancias, % de afectacion',
            'value'=>$importeamortizacionaceleradadelperiodo,
        ]);
        ?>
	</div>
        <div id="amortizacionesespeciales" class="index" style="display: none">
            <h3>Amortizaciones Especiales</h3>
            <label>Si se utiliza una amortizacion especial en este bien de uso, 
                deberá cargar este formulario periodo a periodo. Sino por 
                defecto se utilizan los campos anteriormente expuestos</label>
            <?php 
            echo $this->Form->input('Bienesdeuso.0.Amortizacione.0.periodo',[
                'class'=>'all datepicker-month-year inputAmortizacion',
                'disabled'=>'disabled',
            ]);
            echo $this->Form->input('Bienesdeuso.0.Amortizacione.0.amortizacionejercicio',[
                'class'=>'all inputAmortizacion',
                'label'=>'Amortizacion del ejercicio',
                'disabled'=>'disabled',
            ]);
            echo $this->Form->input('Bienesdeuso.0.Amortizacione.0.amortizacionacumulada',[
                'class'=>'all inputAmortizacion',
                'label'=>'Amortizacion Acumulada',
                'disabled'=>'disabled'
            ]);
            if(isset($this->request->data['Amortizacione'])&&count($this->request->data['Amortizacione'])>0){
            ?>
            <h3>Amortizaciones Especiales cargadas</h3>
            <table>
                 <tr>
                    <th>Periodo</th>
                    <th>Amortizaci&oacute;n del Ejercicio</th>
                    <th>Amortizaci&oacute;n Acumulada</th>
                    <th>Acciones</th>
                </tr>
                <?php 
                foreach ($this->request->data['Amortizacione'] as $km => $amortizacion) {
                    ?>
                    <tr  id="rowAmortizacion<?php echo $amortizacion['id']; ?>">
                        <td><?php echo $amortizacion['periodo'] ?></td>
                        <td><?php echo $amortizacion['amortizacionejercicio'] ?></td>
                        <td><?php echo $amortizacion['amortizacionacumulada'] ?></td>
                        <td> <?php 
                            echo $this->Form->postLink(
                                $this->Html->image('ic_delete_black_24dp.png', array(
                                    'alt' => 'Eliminar',
                                )),
                                array(
                                    'controller' => 'Amortizaciones',
                                    'action' => 'delete',
                                    $amortizacion["id"],
                                ),
                                array(
                                    'class'=>'deleteAmortizacion',
                                    'escape' => false, // Add this to avoid Cake from printing the img HTML code instead of the actual image
                                ),
                                __('Esta seguro que quiere eliminar esta amortizacion?')
                            ); ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php } ?>
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
		echo $this->Form->input('Bienesdeuso.0.motivoexencion',[
			'class'=>'inmuebleFNE automotor naves aeronaves bienesmueblesregistrables otrosbienes',
			'label'=>'Motivo de exencion',
			'type'=>'select',
			'options'=>$optionsmotivoexencion,
                        'default'=>$motivoexencion,
		]);
		echo $this->Form->input('Bienesdeuso.0.valuacionfiscaltotal',[
			'class'=>'inmuebleFNE',
			'label'=>'Valuacion fiscal',
                        'value'=>$valuacionfiscaltotal,
		]);
		echo $this->Form->input('Bienesdeuso.0.valorcompraactualizado',[
			'class'=>'inmuebleFNE',
			'label'=>'Valor compra actualizado',
                        'value'=>$valorcompraactualizado,
		]);
		echo $this->Form->input('Bienesdeuso.0.importe',[
			'class'=>'aeronaves automotor naves bienesmueblesregistrables otrosbienes',
                        'value'=>$importe,
		]);
		echo $this->Form->input('Bienesdeuso.0.importeexento',[
			'class'=>'inmuebleFNE automotor naves aeronaves bienesmueblesregistrables otrosbienes ',
			'label'=>'Importe exento',
                        'value'=>$importeexento,
		]);
		?>
	</div>
	<div class="index">
		<h3>Ganancias Persona Fisica</h3>
		<?php
                echo $this->Form->input('Bienesdeuso.0.bienusopersonal',[
                    'class'=>'all',
                    'type'=>'checkbox',
                    'label'=>'Bien de uso personal',
                    'title'=>'Señalar si este bien sera de uso personal o estará afectado por alguna actividad',
                    'checked'=>$bienusopersonal?true:false, 
		]);
		echo $this->Form->input('Bienesdeuso.0.bienafectadoatercera',[
                    'class'=>'inmuebleFNE automotor naves aeronaves bienesmueblesregistrables otrosbienes',
                    'label'=>'Bien afectado a la 3ra categoria',
                    'checked'=>$bienafectadoatercera?true:false, 
		]);
		echo $this->Form->input('Bienesdeuso.0.bienafectadoacuarta',[
                    'class'=>'inmuebleFNE automotor naves aeronaves bienesmueblesregistrables otrosbienes',
                    'label'=>'Bien afectado a la 4ta categoria',
                    'checked'=>$bienafectadoacuarta?true:false, 
		]);
		echo $this->Form->input('Bienesdeuso.0.gananciasimportetotalperiodoanterior',[
                    'class'=>'inmuebleFNE automotor naves aeronaves bienesmueblesregistrables otrosbienes',
                    'label'=>'importe Total al periodo Anterior',
                    'value'=>$gananciasimportetotalperiodoanterior,
		]);
		echo $this->Form->input('Bienesdeuso.0.gananciasimportetotal',[
                    'class'=>'inmuebleFNE automotor naves aeronaves bienesmueblesregistrables otrosbienes',
                    'label'=>'importe Total al periodo Actual',
                    'value'=>$gananciasimportetotal,
                ]);            
		?>
	</div>
<?php echo $this->Form->end(__('Aceptar')); ?>
</div>

