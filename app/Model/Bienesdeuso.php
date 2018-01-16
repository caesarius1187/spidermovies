<?php
App::uses('AppModel', 'Model');
/**
 * Bienesdeuso Model
 *
 * @property Compra $Compra
 * @property Localidade $Localidade
 */
class Bienesdeuso extends AppModel {

	public $displayField = 'codname';


	var $virtualFields = array(
		'codname' => "CONCAT(
		IFNULL(tipo,''), 
		' ', 
		IFNULL(patente,''), 
		' ', 
		IFNULL(aniofabricacion,''), 
		' ', 
		IFNULL(tipoinmueble,''), 
		' ', 
		IFNULL(marca,''), 
		' ', 
		IFNULL(modelo, ''), 
		' ', 
		IFNULL(calle, ''), 
		' ', 
		IFNULL(numero, ''), 
		' ', 
		IFNULL(matricula,''), 
		' ', 
		IFNULL(fechaadquisicion,'')
		)"
	);

        public $hasMany = array(
		'Amortizacione' => array(
			'className' => 'Amortizacione',
			'foreignKey' => 'bienesdeuso_id',
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
/*' ', 	//The Associations below have been created with all possible keys, those that are not needed can be removed
        
/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Compra' => array(
			'className' => 'Compra',
			'foreignKey' => 'compra_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Venta' => array(
			'className' => 'Venta',
			'foreignKey' => 'venta_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Localidade' => array(
			'className' => 'Localidade',
			'foreignKey' => 'localidade_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Cuentaclientevalororigen' => array(
			'className' => 'Cuentascliente',
			'foreignKey' => 'cuentaclientevalororigen_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Cuentaclienteactualizacion' => array(
			'className' => 'Cuentascliente',
			'foreignKey' => 'cuentaclienteactualizacion_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Cuentaclienteterreno' => array(
			'className' => 'Cuentascliente',
			'foreignKey' => 'cuentaclienteterreno_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Cuentaclienteedificacion' => array(
			'className' => 'Cuentascliente',
			'foreignKey' => 'cuentaclienteedificacion_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Cuentaclientemejora' => array(
			'className' => 'Cuentascliente',
			'foreignKey' => 'cuentaclientemejora_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Modelo' => array(
			'className' => 'Modelo',
			'foreignKey' => 'modelo_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
