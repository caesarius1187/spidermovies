<?php
App::uses('CakeSession', 'Model/Datasource');
App::uses('CartAppModel', 'Cart.Model');

/**
 * Payment Api Transaction Model
 *
 * This model is used to log all payment API transactions it can be called 
 * directly or used with a CakeLog engine that can load models. For the ease of
 * use there is a logger in the cart plugin: PaymentApiLogger.
 *
 * You want that data very likely for
 * - debugging
 * - legal reasons
 * - statistics
 *
 * @author Florian Krämer
 * @copyright 2012 - 2014 Florian Krämer
 * @license MIT
 * @link http://book.cakephp.org/2.0/en/core-libraries/logging.html#creating-and-configuring-log-streams
 */
class PaymentApiTransaction extends CartAppModel {

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Order' => array(
			'className' => 'Cart.Order'));

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * Initializes a new api transaction session
 *
 * @param string $processorClass 
 * @param string $orderId Order UUID
 * @return string
 */
	public function initialize($processorClass, $orderId) {
		$token = str_replace('-', '', String::uuid());
		CakeSession::write('Payment', array(
			'orderId' => $orderId,
			'token' => $token,
			'processor' => $processorClass
		));

		CakeSession::write('Payment.token', $token);
		CakeSession::write('Payment.processor', $processorClass);

		$this->write('payment', __d('cart', 'Payment process started'));

		return $token;
	}

/**
 * Finishes a payment transaction log ny deleting the session keys
 *
 * @return void
 */
	public function finish() {
		$this->write('payment', __d('cart', 'Payment process finished'));
		CakeSession::delete('Payment');
	}

/**
 * Writes to the log table
 *
 * @param string $type
 * @param mixed $message
 * @return mixed Array on success else false
 */
	public function write($type, $message) {
		$processorName = CakeSession::read('Payment.processor');
		$token = CakeSession::read('Payment.token');
		$orderId = CakeSession::read('Payment.orderId');

		if (empty($token) || empty($processorName) || empty($orderId)) {
			return false;
		}

		if (!is_string($message)) {
			$message = print_r($expression);
		}

		$this->create();
		return $this->save(array(
			$this->alias => array(
				'processor' => $processorName,
				'token' => $token,
				'order_id' => $orderId,
				'type' => $type,
				'message' => $message
			)
		));
	}

}
