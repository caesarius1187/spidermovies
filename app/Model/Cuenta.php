<?php
App::uses('AppModel', 'Model');
/**
 * Cbus Model
 *
 * @property Bancosysindicato $Bancosysindicato
 */
class Cuenta extends AppModel {
	//cuentas que se pueden usar para un cliente en un banco
	public $cuentasrelacionadasbancos = ['1','2','3','4','4','6','8','9'];
	public $cuentasDeBancoActivables = ['17','18','19','20','21','22','23','24','25','26','27'];
	public $cuentasDeMovimientoBancario = ['1','2','3','4','5','7','8','9','10','11','12','13'];

    public $cuentasdeIVA = ['1467','287','290','286','288','289','2344','1468'];/*falta el del decreto 814*/
    //210401401	IVA - Débito Fiscal General
    //110403402	IVA - Saldo a Favor Técnico
    //110403406	IVA - Saldo a Favor Libre Disp
    //110403401	IVA - Credito Fiscal General
    //504990015	IVA - No Computable
    //110403402	IVA - Saldo a Favor Técnico
    //110403406	IVA - Saldo a Favor Libre Disp
    //110403403	IVA - Percepciones
    //110403404	IVA - Reteniones
    //110403405	IVA - Decreto 814 *** ESTE FALTA ***
    //210401402	IVA - a Pagar

	public $cuentasdeActVarias = ['332','333','334','2589','1518'];
//    506311001	Act. Vs. 	578,56
//    110405103	Act. Vs. - Saldo a Favor 	0,00
//    110405101	Act. Vs. - Retenciones 		0,00
//    110405102	Act. Vs. - Percepciones 		43,73
//    210403101	Actividades Varias a Pagar		534,83

    public $cuentasdeActEconomicas = ['2577','319','313','316','1492'];
    //506210001	Ingresos Brutos Capital Federal	0,00	0
    //110404301	I.I.B.B. - Saldo a Favor Capital Federal	0,00	0
    //110404101	I.I.B.B. - Retenciones Capital Federal	0	0,00
    //110404301	I.I.B.B. - Saldo a Favor Capital Federal	0	0,00
    //210402101	Ingresos Brutos a Pagar	0,00	0,00

    public $cuentasdeSUSS = ['2250','2253','2254','2255','2256',
        '2257','2345'
        ,'307','1383','1384','1419','1420','1421','1422','1423',
        '1500','1378',
        '3338','1427'];
	//503020001 Mano de Obra Capital Federal
	//503030001	Contr. Seg. Social
	//503030002	Contr. Obra Social
	//503030003	ART
	//503030004	Seguro de Vida Colectivo
	//503030005	RENATRE
    //506220001	Cooperadora Asistencial/*Esto tiene que estar en las Cuentas De cooperadora Asistencial*/
    //504990016	Otros Gastos
    //110403901	Seg. Social - Retenciones
    //210302001	Ap. Seguridad Social a Pagar
    //210302002	Ap. Obra Social a Pagar
    //210303001	Contr. Seg. Social a Pagar
    //210303002	Contr. Obra Social a Pagar
    //210303003	ART a Pagar
    //210303004	Seguro de Vida Colectivo a Pag
    //210303005	RENATRE a pagar
    //210402201	Cooperadora Asistencial a Pagar
    //210301001	Sueldos - Personal XX

    //608100099	Beneficios Fiscales


    public $cuentasdeSUSSContribucionesSindicatos = [
		'2258','2259',
        '2260','2261','2262','2263','2264','2265','2266','2267','2268','2269',
        '2270','2271','2272','2273','2274','2275','2276','2277','2278','2279',
        '2280','2281','2282','2283'];
    //503030009	Contr. Seg. De Vida Oblig. Mercantil
    //503030042	Contr. UTHGRA
    //503030072	Contr. UOCRA
    //503030082	Contr. UOM


    public $cuentasdeSUSSAportesSindicatos = ['1389',
        '1390','1391','1392','1393','1394','1395','1396','1397','1398','1399',
        '1400','1401','1402','1403','1404','1405','1406','1407','1408','1409',
        '1410','1411','1412','1413','1414','1415'
    ];

    //210302021	Ap. SEC a Pagar
    //210302024	Ap. FAECYS a Pagar
    //210302031	Ap. Turismo a Pagar
    //210302038	Ap. UTA a Pagar
    //210302041	Ap. UTHGRA a Pagar
    //210302051	Ap. SMATA a Pagar
    //210302052	Ap. ACARA a Pagar
    //210302061	Ap. UATRE a Pagar
    //210302062	Ap. RENATEA a Pagar
    //210302071	Ap. UOCRA a Pagar
    //210302081	Ap. UOM a Pagar
    //210302091	Ap. AOMA a Pagar
    /**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		
	);

//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	
	public $hasMany = array(
		'Cuentascliente' => array(
			'className' => 'Cuentascliente',
			'foreignKey' => 'cuenta_id',
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
