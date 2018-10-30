<?php
/* ShippingMethodFixture
 *
 * @author Florian Krämer
 * @copyright 2012 - 2014 Florian Krämer
 * @license MIT
 */
class ShippingMethodFixture extends CakeTestFixture {
/**
 * Name
 *
 * @var string
 * @access public
 */
	public $name = 'ShippingMethod';

/**
 * Fields
 *
 * @var array
 * @access public
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'price' => array('type' => 'float', 'null' => true, 'default' => null),
		'currency' => array('type' => 'integer', 'null' => true, 'default' => null),
		'position' => array('type' => 'integer', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 * @access public
 */
	public $records = array(
		array(
			'id' => '5015b116-8150-433f-95c6-a444e1a32268',
			'name' => 'Lorem ipsum dolor sit amet',
			'price' => 1,
			'currency' => 1,
			'position' => 1,
			'created' => '2012-07-29 23:54:30',
			'modified' => '2012-07-29 23:54:30'
		),
	);

}
