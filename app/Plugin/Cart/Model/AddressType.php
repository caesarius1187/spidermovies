<?php
App::uses('CartAppModel', 'Cart.Model');

class AddressType extends CartAppModel {

/**
 * Table to use
 *
 * @var boolean|string
 */
	public $useTable = false;

/**
 * Type Constants
 */
	const SHIPPING = 'shipping';
	const BILLING = 'billing';

}
