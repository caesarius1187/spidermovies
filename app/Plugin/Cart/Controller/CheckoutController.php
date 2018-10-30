<?php
App::uses('PaymentProcessors', 'Payments.Lib/Payment');
App::uses('CartAppController', 'Cart.Controller');
App::uses('CakeEventManager', 'Event');
App::uses('CakeEvent', 'Event');

/**
 * Checkout Controller
 *
 * @author Florian Krämer
 * @copyright 2012 - 2014 Florian Krämer
 * @deprecated Legacy code, use Omnipay or implement your own checkout logic
 */
class CheckoutController extends CartAppController {

/**
 * Uses
 *
 * @var array
 */
	public $uses = array(
		'Cart.Order',
		'Cart.Cart',
		'Cart.PaymentMethod',
		'Cart.ShippingAddress',
		'Cart.PaymentMethod',
	);

/**
 * Components
 *
 * @var array
 */
	public $components = array(
		'Cart.CartManager'
	);

/**
 * beforeFilter callback
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('callback', 'checkout', 'index', 'confirm', 'finish');
	}

/**
 *
 */
	public function index() {
		$this->set('paymentMethods', $this->PaymentMethod->getPaymentMethods());
	}

/**
 * Default callback entry point for API callbacks for payment processors
 *
 * @param null $token
 * @throws NotFoundException
 * @internal param string $processor
 * @return void
 */
	public function callback($token = null) {
		$order = $this->Order->find('first', array(
			'contain' => array(),
			'conditions' => array(
				'Order.token' => $token)));

		if (empty($order)) {
			throw new NotFoundException(__d('cart', 'Invalid payment token %s!', $token));
		}

		try {
			$Processor = $this->_loadPaymentProcessor($order['Order']['processor']);
			$status = $Processor->notificationCallback($order);
			$transactionId = $Processor->getTransactionId();

			if (empty($order['Order']['payment_reference']) && !empty($transactionId)) {
				$order['Order']['payment_reference'] = $transactionId;
			}

			$result = $this->Order->save(
				array(
					'Order' => array(
						'id' => $order['Order']['id'],
						'payment_status' => $status,
						'payment_reference' => $order['Order']['payment_reference'])),
				array(
					'validate' => false,
					'callbacks' => false
				)
			);
		} catch (Exception $e) {
			$this->log($e->getMessage(), 'payment-error');
			$this->log($this->request, 'payment-error');
		}

		$Event = new CakeEvent('Payment.callback', $this->request);
		CakeEventManager::dispatch($Event, $this, array($result));

		$this->_stop();
	}

/**
 * Triggers the checkout
 *
 * - checks if the cart is not empty
 * - checks if the payment processor is valid
 *
 * @param string
 * @param string
 * @return void
 */
	public function checkout($processor = null) {
		$cartData = $this->CartManager->content();
		if (empty($cartData['CartsItem'])) {
			$this->Session->setFlash(__d('cart', 'Your cart is empty.'));
			$this->redirect(array('action' => 'view'));
		}

		$this->_anonymousCheckoutIsAllowed();
		//si hardcodeamos efectivo aca? 
		
		$processor = 'efectivo';
		$processorClass = $this->_mapProcessorClass($processor);

		$Processor = $this->_loadPaymentProcessor($processorClass);
		die("Processor");
		die($Processor);
		$newOrder = $this->Order->createOrder($cartData, $processorClass);

		if ($newOrder) {
			$ApiLog = ClassRegistry::init('Cart.PaymentApiTransaction');
			$token = $ApiLog->initialize($processorClass, $newOrder['Order']['id']);

			$cancelUrl = Configure::read('Cart.paymentUrls.cancelUrl');
			$cancelUrl[] = $token;
			$Processor->cancelUrl = Router::url($cancelUrl, true);

			$returnUrl = Configure::read('Cart.paymentUrls.returnUrl');
			$returnUrl[] = $token;
			$Processor->returnUrl = Router::url($returnUrl, true);

			$callbackUrl = Configure::read('Cart.paymentUrls.callbackUrl');
			$callbackUrl[] = $token;
			$Processor->callbackUrl = Router::url($callbackUrl, true);

			$finishUrl = Configure::read('Cart.paymentUrls.finishUrl');
			$finishUrl[] = $token;
			$Processor->finishUrl = Router::url($finishUrl, true);

			$newOrder['Order']['token'] = $token;
			$this->Order->saveField('token', $token);

			$Processor->set('payment_reason', $newOrder['Order']['id']);
			$Processor->set('payment_reason2', $token);

			$status = $Processor->pay($newOrder['Order']['total']);

			$this->Order->save(
				array(
					'Order' => array(
						'id' => $newOrder['Order']['id'],
						'payment_status' => $status,
						'payment_reference' => $Processor->getTransactionId())),
				array(
					'validate' => false,
					'callbacks' => false));

			$this->CartManager->emptyCart();
		}

		$this->Session->setFlash(__d('cart', 'There was a problem creating your order.'));
		$this->redirect(array('action' => 'view'));
	}

/**
 * Last step for so called express checkout processors
 *
 * @param string $transactionToken
 * @return void
 */
	public function confirm($transactionToken = null) {
		if ($transactionToken != CakeSession::read('Payment.token')) {
			$this->Session->setFlash(__d('cart', 'Invalid Order'));
			$this->redirect('/');
		}

		$order = $this->Order->find('first', array(
			'contain' => array(),
			'conditions' => array(
				'Order.id' => CakeSession::read('Payment.orderId'))));

		$Processor = $this->_loadPaymentProcessor($order['Order']['processor']);

		if (!method_exists($Processor, 'confirmOrder')) {
			$this->Session->setFlash(__d('cart', 'Unsupported payment processor for this type of checkout!'));
			$this->redirect(array('action' => 'view'));
		}

		$this->set('cart', array_merge($order, $order['Order']['cart_snapshot']));
		$Processor->confirmOrder($order);

		if (!empty($this->request->data)) {
			if (isset($this->request->data['complete'])) {
				$result = $Processor->finishOrder($order);
				if ($result) {
					$this->Order->save($result, array('validate' => false, 'callbacks' => false));
					$this->redirect(array('action' => 'finish_order', $transactionToken));
				}
			} else {
				// @todo
			}
		}
	}

/**
 * @todo
 */
	public function finish($transactionToken = null) {
		$Order = ClassRegistry::init('Cart.Order');
		$this->set('order', $this->Order->find('first', array(
			'conditions' => array(
				'token' => $transactionToken))));
	}

/**
 * Loads the processor instance, handles errors and redirects in the case of an error
 *
 * @param string $processor
 * @return PaymentProcessor
 */
	protected function _loadPaymentProcessor($processor) {
		try {
			//

			list($plugin, $class) = pluginSplit($processor, true);

			if (substr($class, -9) != 'Processor') {
				$class = $class . 'Processor';
			}

			$sandboxMode = false;
		
			$config = Configure::read($class);
			if (isset($config['sandboxMode']) && isset($config['sandbox'])) {
				$config = $config['sandbox'];
				$sandboxMode = true;
			} elseif (isset($config['live'])) {
				$config = $config['live'];
			}
			$Processor = PaymentProcessors::load($processor, $config, array(
				'CakeRequest' => $this->request,
				'CakeResponse' => $this->response));
			$Processor->sandboxMode($sandboxMode);
			
			return $Processor;

		} catch (MissingPaymentProcessorException $e) {
			die("_loadPaymentProcessor 1".$processor);
			$this->Session->setFlash(__d('cart', 'The payment method does not exist!'));
			$this->redirect(array('action' => 'view'));
		} catch (Exception $e) {
			die("_loadPaymentProcessor 2".$e);
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'view'));
		}
	}

/**
 * Checks if the processor name is mapped in the static configure class or if
 * it is mapped in the PaymentMethod model.
 *
 * The payment method model gives you greater flexibility to en/disable processors
 * on the fly through the admin backend.
 *
 * If no mapped processor classname is found it will return the passed name and
 * the PaymentProcessors::load() method will throw an exception if it can't find
 * that processor.
 *
 * @param string $processorAlias
 * @return string
 */
	protected function _mapProcessorClass($processorAlias) {

		$processorClass = Configure::read('Cart.PaymentMethod.' . $processorAlias . '.processor');
		if (!empty($processorClass)) {
			die('Cart.PaymentMethod.' . $processorAlias . '.processor');
			return $processorClass;
		}

		$processorClass = $this->PaymentMethod->getMappedClassName($processorAlias);
		if (!empty($processorClass)) {
			return $processorClass;
		}
		die('Cart.PaymentMethod.' . $processorAlias . '.processor');
		return $processorAlias;
	}

/**
 * Checks if the user can do the checkout while not being logged in or not
 *
 * @param boolean $redirect
 * @return boolean
 */
	protected function _anonymousCheckoutIsAllowed($redirect = true) {
		if (Configure::read('Cart.anonymousCheckout') === false && is_null($this->Auth->user())) {
			$this->Session->setFlash(__d('cart', 'Sorry, but you have to login to check this cart out.'));
			if ($redirect) {
				$this->redirect(array('action' => 'view'));
			}
			return false;
		}
		return true;
	}

}