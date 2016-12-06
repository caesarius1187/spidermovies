<?php
App::uses('AppModel', 'Model');
/**
 * Empleado Model
 *
 * @property Cliente $Cliente
 * @property Valorrecibo $Valorrecibo
 */
class Empleado extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'nombre';
	public $codigorevista = [
		'00'=>'Baja por Fallecimiento ',
		'01'=>'Activo ',
		'02'=>'Bajas otras causales ',
		'03'=>'Activo Decreto N°796/97 ',
		'04'=>'Baja otras causales Decreto N° 796/97 ',
		'05'=>'Licencia por maternidad ',
		'06'=>'Suspendido por otras causales ',
		'07'=>'Baja por despido ',
		'08'=>'Baja por despido Decreto N° 796/97 ',
		'09'=>'Suspendido. Ley N° 20744 art.223bis ',
		'10'=>'Licencia por excedencia ',
		'11'=>'Licencia por maternidad Down ',
		'12'=>'Licencia por vacaciones',
		'13'=>'Licencia sin goce de haberes',
		'14'=>'Reserva de puesto',
		];
	public $codigoactividad =[
		'00'=>'Zona de Desastre. Decreto 1386/01-excepto actividad agropecuaria-',
		'01'=>'Producción Primaria -excepto actividad agropecuaria-',
		'02'=>'Producción de bienes sin comercialización',
		'03'=>'Construcción de inmuebles',
		'04'=>'Turismo',
		'05'=>'Investigación Científica y Tecnológica',
		'06'=>'Administración Publica. CON OBRA SOCIAL 23660',
		'07'=>'Enseñanza Privada L.13047',
		'08'=>'Servicio Doméstico',
		'09'=>'Inc b) art 12 Ley 19316 ISSARA mod. Ley 22673',
		'10'=>'Personal de Dirección RG 4158 Art10',
		'11'=>'Personal Permanente Discont. Empresas de Servicios Eventuales',
		'12'=>'PIT -Programas Intensivos de Trabajo',
		'13'=>'Personal embarcado.',
		'14'=>'Personal embarcado Dec 1255 S/res SSS 18/99',
		'15'=>'L.R.T.-Directores SA, municipios, org, cent y descent. Emp mixt provin y otros-',
		'16'=>'No obligados con el SIJP (colegios, reciprocidad previsional y otros)',
		'17'=>'Obligados al SIJP -sin Obra Social Nacional (Adm Púb y otros)',
		'18'=>'Provincia incorporada al SIJP sin obra social nacional con ART',
		'19'=>'Provincia incorporada al SIJP sin obra social nacional sin ART',
		'20'=>'Ley N° 24.331 Zona Franca',
		'21'=>'Dec N° 1024/93 Empr del Estado, Org. y Entes Públicos con OS y FNE',
		'22'=> 'Dec N° 1024/93 Empr del Estado, Org. y Entes Públicos sin OS y con FNE',
		'23'=> 'ILT para actividad 21',
		'24'=> 'ILPPP o ILPPD para actividad 21',
		'25'=> 'ILPTP para actividad 21',
		'26'=> 'ILT para actividad 22',
		'27'=> 'ILPPP o ILPPD para actividad 22',
		'28'=> 'ILPTP para actividad 22',
		'29'=> 'Personal embarcado Dec. 1.255 S/res SSS 18/99 con Obra Social',
		'41'=> 'Trabajador de la Construcción Ley 25345 art. 36',
		'42'=> 'Asignaciones Familiares y FNE con Obra Social Nacional',
		'43'=> 'Asignaciones Familiares y FNE sin Obra Social Nacional',
		'44'=> 'Ley N° 24061 Dto.249/92',
		'45'=> 'Provincia incorporada al SIJP con obra social nacional con ART',
		'46'=> 'Provincia incorporada al SIJP con obra social nacional sin ART',
		'47'=> 'Ley Nº 15223 con obra social',
		'48'=> 'Régimen nacional sin obra social nacional',
		'49'=> 'Actividades no clasificadas',
		'50'=> 'ILT p/ actividades 01 * 02 * 03 * 04* 05 * 11* 13 * 49',
		'51'=> 'ILT p/ actividad 06 *92 * 93',
		'52'=> 'ILT p/ actividad 12',
		'53'=> 'ILT p/ actividad 16',
		'54'=> 'ILT p/ actividad 17',
		'55'=> 'ILT p/ actividad 18 * 19',
		'56'=>'ILT p/actividades 45 * 46',
		'57'=>'ILT p/ actividad 47',
		'58'=>'ILT p/ actividad 48',
		'59'=>'ILT p/actividad 95',
		'60'=>'ILPPP p/ actividades 01 * 02 * 03 * 04* 05 *11* 13 * 49',
		'61'=>'ILPPP o ILPPD p/actividades 06 * 92 * 93',
		'62'=>'ILPPP o ILPPD p/actividad 12',
		'63'=>'ILPPP o ILPPD p/actividad 16',
		'64'=>'ILPPP o ILPPD p/actividad 17',
		'65'=>'ILPPP o ILPPD p/actividades 18 *19',
		'66'=>'ILPPP o ILPPD p/actividades 45 * 46',
		'67'=>'ILPPPo ILPPD p/actividad 47',
		'68'=>'ILPPPo ILPPD p/actividad 48',
		'69'=>'ILPPP o ILPPD p/actividad 95',
		'70'=>'ILPPD p/ actividades 01 * 02 * 03 * 04* 05 *11* 13 * 49',
		'71'=>'ILPTP p/ actividades 18*19',
		'72'=>'ILPTP p/ actividades 45 * 46',
		'73'=>'ILT p/actividad 91',
		'74'=>'ILPPP o ILPPD p/actividad 91',
		'80'=>'ILPTP p/ actividades 01 * 02 * 03 * 04* 05 *11* 13 * 49 * 91',
		'81'=>'ILPTP p/ actividad 12',
		'82'=>'ILPTP p/ actividad 17',
		'83'=>'ILPTP p/ actividades 06 * 16 * 92 Y 93',
		'84'=>'ILPTP p/ actividad 47',
		'85'=>'ILPTP p/ actividad 48',
		'86'=>'ILPTP p/ actividad 95',
		'87'=>'ILT p/ actividad 97',
		'88'=>'ILPPP o ILPPD p/actividad 97',
		'89'=>'ILPTP p/ actividad 97',
		'90'=>'ILT o ILPPP o ILPPD o ILPTP p/actividad 15',
		'91'=>'Régimen previsional propio. Obra Social L.23660 Y 24714',
		'92'=>'Res 71/99 SSS y otros',
		'93'=>'UNIVERSIDADES PRIVADAS. Personal Docente D.1123/99',
		'94'=>'Decreto 953/99',
		'95'=>'Ley Nº 15223 sin obra social',
		'96'=>'Pers. Permanente Discontinuo Serv Eventuales Alcanzados p/ DtoN° 96/99',
		'97'=>'Trabajador Agrario. Ley 25191',
		'98'=>'Zona de desastre. Decreto N° 1386/01. Actividad agropecuaria Ley 25191',
		];
//$codigomodalidadcontratacion = ['10','27','29','99','201','202','203','204','205','206','211','212','213','221','222','223','999'];
	public $codigomodalidadcontratacion = [
		'00' => 'Contrato modalidad promovida. Reducción 0% ',
		'01' => 'A tiempo parcial: Indeterminado ',
		'02' => 'Becarios ',
		'03' => 'De aprendizaje. Ley N° 25.013 ',
		'04' => 'Especial de Fomento del Empleo. Ley N° 24.465 ',
		'05' => 'Fomento del empleo. Leyes N° 24.013 y N° 24.465 ',
		'06' => 'Lanzamiento nueva actividad. Leyes N° 24.013 y N° 24.465 ',
		'07' => 'Período de prueba. Leyes N° 24.465 y N° 25.013 ',
		'08' => 'A tiempo completo indeterminado. ',
		'09' => 'Práctica laboral para jóvenes. ',
		'10' => 'Programa Nacional de pasantías. Decreto N° 340/92. Ley N° 25.165. ',
		'11' => 'Trabajo de temporada. ',
		'12' => 'Trabajo eventual.',
		'13' => 'Trabajo formación.',
		'14' => 'Nuevo período de prueba',
		'15' => 'Puesto Nuevo varones 25 a 44 y mujeres de 25 ó más años',
		'16' => 'Nuevo período de prueba. Trabajador Discapacitado. Art. 34. Ley N° 24.147',
		'17' => 'Puesto nuevo menor de 25, varón de 45 ó más y mujer jefe de familia s/límite/edad',
		'18' => 'Trabajador Discapacitado. Art. 34. Ley N° 24.147',
		'19' => 'Puesto nuevo varones 25 a 44 y mujeres de 25 ó más años. Art. 34. Ley N° 24.147',
		'20' => 'Puesto nuevo menor de 25. Varón de 45 ó más y mujer jefe de familia s/límite/edad. Art. 34. Ley N° 24.147',
		'21' => 'A tiempo parcial determinado (Contrato a plazo fijo)',
		'22' => 'A tiempo completo determinado (Contrato a plazo fijo)',
		'23' => 'Trabajador Agrario. Personal no Permanente. Ley N° 22.248',
		'24' => 'Personal de la construcción. Ley N° 22.250.',
		'25' => 'Empleo público provincial',
		'26' => 'Beneficiario de programa de empleo, capacitación y de recuperación productiva',
		'27' => 'Pasantías. Decreto N° 1.227/01',
		'28' => 'Programas jefes y jefas de hogar. Decreto N° 565/02.',
		'50' => 'Contrato modalidad promovida. Reducción 50%',
		'100' => 'Contrato modalidad promovida. Reducción 100%',
		];
//                $codigosiniestrado=['00','01','02','03','04','05','06','07','08','09','10','11','12','13',''];
	public $codigosiniestrado=[
		'00' => 'No Incapacitado',
		'01' => 'ILT Incapacidad Laboral Temporaria',
		'02' => 'ILPPP Incapacidad Laboral Permanente Parcial Provisoria.',
		'03' => 'ILPPD Incapacidad Laboral Permanente Parcial Definitiva.',
		'04' => 'ILPTP Incapacidad Laboral Permanente Total Provisoria',
		'05' => 'Capital de recomposición Art. 15, ap. 3, Ley Nº 24.557',
		'06' => '"Ajuste Definitivo ILPPD de pago mensual"',
		'07' => 'RENTA PERIODICA ILPPD Inc Lab Perm Parc Def >50%<66%',
		'08' => 'SRT/SSN F.Garantía/F Reserva ILT Incapacidad Laboral Temporaria ',
		'09' => 'SRT/SSN F.Garantía/F Reserva ILPPP Inc Lab Perm Parc Prov',
		'10' => 'SRT/SSN F.Garantía/F Reserva ILPTP Inc Lab Perm Total Prov',
		'11' => 'SRT/SSN F.Garantía/F Reserva ILPPD Inc Laboral Perm Parc Definitiva',
		];
	public $tipoempresa = [
		'0'=>'Administración pública. 1Decreto N° 814/01 art. 2° inciso b).',
		'2'=>'Servicios eventuales. Decreto N° 814/01 art. 2° inciso b).',
		'3'=>'Provincias u otros.',
		'4'=>'Decreto N° 814/01 art. 2° inciso a).',
		'5'=>'Servicios eventuales. Decreto N° 814/01 art. 2° inciso a).',
		'6'=>'Decreto N° 814/01 art. 2° inciso b). Provincias. Ley N° 22.016.',
		'7'=>'Colegios privados.',
		];
	public $codigozona = [
		'02'=>'Buenos Aires - Almirante Brown 02',
		'02'=>'Buenos Aires - Avellaneda 02',
		'02'=>'Buenos Aires - Berazategui 02',
		'03'=>'Buenos Aires - Berisso 03',
		'03'=>'Buenos Aires - Cañuelas 03',
		'04'=>'Buenos Aires - Carmen de Patagones 04',
		'03'=>'Buenos Aires - Ensenada 03',
		'03'=>'Buenos Aires - Escobar 03',
		'02'=>'Buenos Aires - Estevan Echeverria 02',
		'02'=>'Buenos Aires - Florencio Varela 02',
		'03'=>'Buenos Aires - Gral. Rodriguez 03',
		'02'=>'Buenos Aires - Gral. San Martín 02',
		'02'=>'Buenos Aires - Gral. Sarmiento 02',
		'02'=>'Buenos Aires - Gran Buenos Aires 02',
		'02'=>'Buenos Aires - La Matanza 02',
		'03'=>'Buenos Aires - La Plata 03',
		'02'=>'Buenos Aires - Lanús 02',
		'02'=>'Buenos Aires - Lomas de Zamora 02',
		'03'=>'Buenos Aires - Marcos Paz 03',
		'02'=>'Buenos Aires - Merlo 02',
		'02'=>'Buenos Aires - Moreno 02',
		'02'=>'Buenos Aires - Morón 02',
		'05'=>'Buenos Aires - Patagones 05',
		'03'=>'Buenos Aires - Pilar 03',
		'02'=>'Buenos Aires - Quilmes 02',
		'07'=>'Buenos Aires - Resto de la Provincia 07',
		'02'=>'Buenos Aires - San Fernando 02',
		'02'=>'Buenos Aires - San Isidro 02',
		'03'=>'Buenos Aires - San Vicente 03',
		'02'=>'Buenos Aires - Tigre 02',
		'02'=>'Buenos Aires - Tres de Febrero 02',
		'02'=>'Buenos Aires - Vicente Lopez 02',
		'06'=>'Buenos Aires - Villarino 06',
		'01'=>'Capital Federal 01',
		'09'=>'Catamarca 09',
		'08'=>'Catamarca - Gran Catamarca 08',
		'27'=>'Chaco 27',
		'26'=>'Chaco - Gran Resistencia 26',
		'29',
		'28'=>'Chubut - Rawson 28',
		'28'=>'Chubut - Trelew 28',
		'13'=>'Córdoba - Cruz del Eje 13',
		'18'=>'Córdoba - Gran Córdoba 18',
		'14'=>'Córdoba - Minas 14',
		'15'=>'Córdoba - Pocho 15',
		'19'=>'Córdoba - Resto de la Provincia 19',
		'11'=>'Córdoba - Río Seco 11',
		'16'=>'Córdoba - San Alberto 16',
		'17'=>'Córdoba - San Javier 17',
		'10'=>'Córdoba - Sobremonte 10',
		'12'=>'Córdoba - Tulumba 12',
		'24'=>'Corrientes - Ciudad de Corriente 24',
		'22'=>'Corrientes - Curuzú-Cuatia 22',
		'20'=>'Corrientes - Esquina 20',
		'23'=>'Corrientes - Monte Caseros 23',
		'25'=>'Corrientes - Resto de la Provincia 25',
		'21'=>'Corrientes - Sauce 21',
		'30'=>'Entre Ríos - Federación 30',
		'31'=>'Entre Ríos - Feliciano 31',
		'32'=>'Entre Ríos - Paraná 32',
		'33'=>'Entre Ríos - Resto de la Provincia 33',
		'35'=>'Formosa 35',
		'34'=>'Formosa - Ciudad de Formosa 34',
		'37'=>'Jujuy 37',
		'36'=>'Jujuy - Ciudad de Jujuy 36',
		'39'=>'La Pampa - Chalileo 39',
		'38'=>'La Pampa - Chical-Co 38',
		'41'=>'La Pampa - Limay-Mahuilda 41',
		'0 '=>'La Pampa - Puelén 40 ',
		'42'=>'La Pampa - Curaco 42',
		'43'=>'La Pampa - Lihuel - Calel 43',
		'45'=>'La Pampa - Resto de la Provincia 45',
		'44'=>'La Pampa - Santa Rosa 44',
		'44'=>'La Pampa - Toay 44',
		'47'=>'La Rioja 47',
		'46'=>'La Rioja - Ciudad de La Rioja 46',
		'49'=>'Mendoza 49',
		'48'=>'Mendoza - Gran Mendoza 48',
		'51'=>'Misiones 51',
		'50'=>'Misiones - Posadas 50',
		'56'=>'Neuquén 56',
		'53'=>'Neuquén - Centenario 53',
		'52'=>'Neuquén - Ciudad de Neuquén 52',
		'54'=>'Neuquén - Cutral-Co 54',
		'55'=>'Neuquén - Plaza Huincul 55',
		'52'=>'Neuquén - Plottier 52',
		'59'=>'Río Negro - Alejando Stefenelli 59',
		'59'=>'Río Negro - Alto Valle 59',
		'59'=>'Río Negro - Allen 59',
		'59'=>'Río Negro - Cervantes 59',
		'59'=>'Río Negro - Chichinales 59',
		'59'=>'Río Negro - Cinco Saltos 59',
		'59'=>'Río Negro - Cipoletti 59',
		'59'=>'Río Negro - Contralmirante Cordero 59',
		'59'=>'Río Negro - Coronel Juan J. Gómez 59',
		'59'=>'Río Negro - Fernández Oro 59',
		'59'=>'Río Negro - Gral. Enrique Godoy 59',
		'59'=>'Río Negro - Gral. Roca 59',
		'59'=>'Río Negro - Ing. L. A. Huergo 59',
		'59'=>'Río Negro - Mainque 59',
		'58'=>'Río Negro - Viedma 58',
		'59'=>'Río Negro - Villa Regina 59',
		'60'=>'Río Negro - Zona Nº 1 60',
		'7 '=>'Río Negro - Zona Nº 2 57 ',
		'62'=>'Salta 62',
		'61'=>'Salta - Gran Salta 61',
		'64'=>'San Juan 64',
		'63'=>'San Juan - Gran San Juan 63',
		'66'=>'San Luis 66',
		'65'=>'San Luis - Ciudad de San Luis 65',
		'69'=>'Santa Cruz 69',
		'67'=>'Santa Cruz - Caleta Olivia 67',
		'68'=>'Santa Cruz - Río Gallegos 68',
		'73'=>'Santa Fe - 9 de Julio 73',
		'70'=>'Santa Fe - Gral. Obligado 70',
		'75'=>'Santa Fe - Resto de la Provincia 75',
		'71'=>'Santa Fe - San Javier 71',
		'72'=>'Santa Fe - Santo Tome 72',
		'74'=>'Santa Fe - Vera 74',
		'76'=>'Santiago del Estero - Cdad. de Santiago del Estero 76',
		'76'=>'Santiago del Estero - La Banda 76',
		'77'=>'Santiago del Estero - Ojo de Agua 77',
		'78'=>'Santiago del Estero - Quebrachos 78',
		'80'=>'Santiago del Estero - Resto de la Provincia 80',
		'79'=>'Santiago del Estero - Rivadavia 79',
		'83'=>'Tierra del Fuego 83',
		'81'=>'Tierra del Fuego - Río Grande 81',
		'82'=>'Tierra del Fuego - Ushuaia 82',
		'85'=>'Tucumán 85',
		'84'=>'Tucumán - Gran Tucumán 84',
		'86'=>'Identifica trabajador siniestrado hasta V 9.3 86',
		'87'=>'Formosa - Bermejo 87',
		'88'=>'Formosa - Ramón Lista 88',
		'89'=>'Formosa - Mataco 89',
		'90'=>'Mendoza - Las Heras - Las Cuevas 90',
		'91'=>'Mendoza - Resto Las Heras 91',
		'92'=>'Mendoza - Luján de Cuyo - Potrerillos 92',
		'3 '=>'Mendoza - Luján de Cuyo - Carrizal 93 ',
		'94'=>'Mendoza - Luján de Cuyo - Agrelo 94',
		'95'=>'Mendoza - Luján de Cuyo - Ugarteche 95',
		'96'=>'Mendoza - Luján de Cuyo - Perdriel 96',
		'97'=>'Mendoza - Luján de Cuyo - Las Compuertas 97',
		'98'=>'Mendoza - Resto Distritos Luján de Cuyo 98',
		'99'=>'Mendoza - Tupungato - Santa Clara 99',
		'A0'=>'Mendoza - Tupungato - Zapata A0',
		'A1'=>'Mendoza - Tupungato - San José A1',
		'A2'=>'Mendoza - Tupungato - Anchoris A2',
		'A3'=>'Mendoza - Resto Distritos Tupungato A3',
		'A4'=>'Mendoza - Tunuyán - Los Arboles A4',
		'A5'=>'Mendoza - Tunuyán - Los Chacayes A5',
		'A6'=>'Mendoza - Tunuyán - Campos de los Andes A6',
		'A7'=>'Mendoza - Resto Distritos Tunuyán A7',
		'A8'=>'Mendoza - San Carlos - Pareditas A8',
		'A9'=>'Mendoza - Resto Distritos San Carlos A9',
		'B0'=>'Mendoza - San Rafael - Cuadro Venegas B0',
		'B1'=>'Mendoza - Resto Distritos San Rafael B1',
		'B2'=>'Mendoza - Malargue - Malargue B2',
		'B3'=>'Mendoza - Malargue - Río Grande B3',
		'B4'=>'Mendoza - Malargue - Río Barrancas B4',
		'B5'=>'Mendoza - Malargue - Agua Escondida B5',
		'B6'=>'Mendoza - Resto Distritos Malargue B6',
		'B7'=>'Mendoza - Maipu - Russell B7',
		'B8'=>'Mendoza - Maipu - Cruz de Piedra B8',
		'B9'=>'Mendoza - Maipu - Lumlunta B9',
		'C0'=>'Mendoza - Maipú - Las Barrancas C0',
		'C1'=>'Mendoza. Resto Distritos Maipú C1',
		'C2'=>'Mendoza. Rivadavia - El Mirador C2',
		'C3'=>'Mendoza. Rivadavia - Los Campamentos C3',
		'C4'=>'Mendoza. Rivadavia - Los Arboles C4',
		'C5'=>'Mendoza. Rivadavia - Reducción C5',
		'C6'=>'Mendoza. Rivadavia - Medrano C6',
		'C7'=>'Mendoza - Resto Distritos Rivadavia C7',
		'C8'=>'Salta - Oran - San Ramón de la Nueva Oran y su ejido urbano C8',
		'C9'=>'Salta - Resto Distritos Oran C9',
		'D0'=>'Salta - Los Andes D0',
		'D1'=>'Salta - Santa Victoria D1',
		'D2'=>'Salta - Rivadavia D2',
		'D3'=>'Salta - Gral San Martín - Tartagal y su ejido urbano D3',
		'D4'=>'Salta - Resto Distritos Gral. San Martín D4',
		'D5'=>'Catamarca - Antofagasta de la Sierra Actividad Minera D5',
		'D6'=>'Catamarca - Antofagasta de la Sierra Resto Actividades D6',
		'D7'=>'Jujuy - Cochinoca D7',
		'D8'=>'Jujuy - Humahuaca D8',
		'D9'=>'Jujuy - Rinconada D9',
		'E0'=>'Jujuy - Santa Catalina E0',
		'E1'=>'Jujuy - Susques E1',
		'E2'=>'Jujuy - Yavi E2',
	];
	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Cliente' => array(
			'className' => 'Cliente',
			'foreignKey' => 'cliente_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Conveniocolectivotrabajo' => array(
			'className' => 'Conveniocolectivotrabajo',
			'foreignKey' => 'conveniocolectivotrabajo_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Puntosdeventa' => array(
			'className' => 'Puntosdeventa',
			'foreignKey' => 'puntosdeventa_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Domicilio' => array(
			'className' => 'Domicilio',
			'foreignKey' => 'domicilio_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Valorrecibo' => array(
			'className' => 'Valorrecibo',
			'foreignKey' => 'empleado_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
