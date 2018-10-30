<?php
App::uses('CartAppController', 'Cart.Controller');
App::uses('CakeEventManager', 'Event');
App::uses('CakeEvent', 'Event');

/**
 * Carts Controller
 *
 * @author Florian Krämer
 * @copyright 2012 Florian Krämer
 */
class CartsController extends CartAppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array(
		'Cart.CartManager',
		'Session',
		'Paginator',
	);

/**
 * beforeFilter callback
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index', 'view', 'remove_item', 'checkout', 'callback', 'finish_order');

		if ($this->request->params['action'] == 'callback') {
			$this->Components->disable('Security');
		}
	}

/**
 * Display all carts a user has, active one first
 *
 * @return void
 */
	public function index() {
		$this->Paginator->settings = array(
			'contain' => array(),
			'order' => array('Cart.active DESC'),
			'conditions' => array(
				'Cart.user_id' => $this->Auth->user('id')));
		$this->set('carts', $this->Paginator->paginate());
	}

/**
 * Shows a cart for a user
 *
 * @param null $cartId
 * @internal param $string $cartId UUID
 * @return void
 */
	public function view($cartId = null) {
		if (!empty($this->request->data)) {
			$this->CartManager->updateItems($this->request->data['CartsItem']);
		}

		if (!empty($cartId)) {
			$cart = $this->Cart->view($cartId, $this->Auth->user('id'));
		} else {
			$cart = $this->CartManager->content();
		}

		$this->request->data = $cart;
		$this->set('cart', $cart);
		$this->set('requiresShipping', $this->CartManager->requiresShipping());
	}

/**
 * Adds a new cart
 *
 * @return boolean
 */
	public function add() {
		if (!$this->request->is('get')) {
			if ($this->Cart->add($this->request->data)) {
				$this->Session->setFlash(__d('cart', 'New cart created'));
				$this->redirect(array('action' => 'view', $this->Cart->data['Cart']['id']));
			}
		}
	}

/**
 * Removes an item from the cart
 *
 * @return void
 */
	public function remove_item() {
		if (!isset($this->request->named['model']) || !isset($this->request->named['id'])) {
			$this->Session->setFlash(__d('cart', 'Invalid cart item'));
			$this->redirect($this->referer());
		}

		$result = $this->CartManager->removeItem(array(
			'CartsItem' => array(
				'foreign_key' => $this->request->named['id'],
				'model' => $this->request->named['model']
			)
		));

		if ($result) {
			$this->Session->setFlash(__d('cart', 'Item removed'));
		} else {
			$this->Session->setFlash(__d('cart', 'Could not remove item'));
		}

		$this->redirect($this->referer());
	}

/**
 * Admin listing of carts
 *
 * @return void
 */
	public function admin_index() {
		$this->Paginator->settings = array(
			'contain' => array('User'));

		$this->set('carts', $this->Paginator->paginate());
	}

/**
 * View the content of any cart
 *
 * @param string $cartId Cart UUId
 * @return void
 */
	public function admin_view($cartId = null) {
		$this->set('cart', $this->Cart->view($cartId));
	}

/**
 * Deletes a cart
 *
 * @param string $cartId UUID
 * @return void
 */
	public function admin_delete($cartId = null) {
		if (!$this->request->is('get')) {
			if (!$this->Cart->delete($cartId)) {
				$this->Session->setFlash(__d('cart', 'Cart deleted.'));
				$this->redirect(array('action' => 'index'));
			}
		}
		$this->set('cart', $this->Cart->view($cartId));
	}

}