<?php
App::uses('AppModel', 'Model');
/**
 * Cbus Model
 *
 * @property Bancosysindicato $Bancosysindicato
 */
class Cuenta extends AppModel {
	public $displayField = 'codname';


	var $virtualFields = array(
		'codname' => 'CONCAT(Cuenta.numero, " ", Cuenta.nombre)'
	);


	//cuentas que se pueden usar para un cliente en un banco

	public $cuentasDeBancoActivables = ['17','18','19','20','21','22','23','24','25','26','27'];
	public $cuentasDeMovimientoBancarioAActivar = [
		'5','16','251','1215','1216','1217','1218','1219','3279','3350','2545','2546','298','318','317','3379','2799'];

	public $cuentasDeMovimientoBancario =
		['5','16','17','18','19','20','21','22','23','24','25','26','27','251','286','298','316','317','318','333','1215',
			'1216','1217',
			'1218','1219','1378','1383','1384','1419','1420','1421','1422','1423','1447','1448','1458','1459','1463',
			'1468','1477','1479','1492','1495','1496','1500','1505','1506','1507','1508','1509','1510','1511','1512',
			'1513','1518','1521','1522','1526','1529','1575','1576','1577','1578','1579','1580','1581','1582','1583',
			'1584','1585','1586','1587','1588','1589','1590','1591','1592','1593','1594','1595','1597','1598','1599',
			'1600','1601','1602','1604','1605','1606','1607','1608','1609','2386','2387','2388','2389','2390','2391',
			'2392','2393','2394','2395','2396','2400','2401','2402','2403','2404','2405','2410','2414','2415','2416','2419','2424','2428','2429','2430','2433','2438','2443',
			'2444','2447','2452','2456','2460','2468','2472','2480','2484','2492','2496','2504','2508','2523','2545',
			'2546','2585','3279','3350','3379','2799'];
	
	public $cuentasComisionGastosInteresesOtros = [
		'2386','2387','2388','2391','2396','2400','2401','2402','2405','2410','2414','2415','2416','2419','2424','2428',
		'2429','2430','2433','2438','2443','2444','2447','2452','2456','2460','2468','2472','2480','2484','2492','2496',
		'2504','2508'
	];


    public $cuentasdeIVA = ['1467','287','290','286','288','289','2344','1468','3332'];
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

    public $cuentasdeActEconomicas = ['2577','319','313','316','1492','3378'];
    //506210001	Ingresos Brutos Capital Federal	0,00	0
    //110404301	I.I.B.B. - Saldo a Favor Capital Federal	0,00	0
    //110404101	I.I.B.B. - Retenciones Capital Federal	0	0,00
    //110404301	I.I.B.B. - Saldo a Favor Capital Federal	0	0,00
    //210402101	Ingresos Brutos a Pagar	0,00	0,00

    public $cuentasdeSUSS = ['2250','2253','2254','2255','2256',
        '2257','2345','307','1383','1384','1419','1420','1421',
		'1422','1423','1500','1378','3338','1427','2277'];
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

	//Cuentas de Contribuciones de sindicatos en PASIVO
	public $cuentasdeSUSSContribucionesSindicatosPASIVO = [
		'1424','1425','1426','1427','1428','1429','1430','1431'
		,'1432','1433','1434','1435','1436','1437','1438','1439'
		,'1440','1441','1442','1443'
	];

	//Cuentas de Contribuciones de sindicatos en PERDIDA

    public $cuentasdeSUSSContribucionesSindicatos = [
		'2258','2259',
        '2260','2261','2262','2263','2264','2265','2266','2267','2268','2269',
        '2270','2271','2272','2273','2274','2275','2276','2278','2279',
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
