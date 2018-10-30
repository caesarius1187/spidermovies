<?php
App::uses('CartAppController', 'Cart.Controller');
/**
 * ShippingMethodsController
 *
 * @author Florian Krämer
 * @copyright 2012 - 2014 Florian Krämer
 * @license MIT
 */
class ShippingMethodsController extends CartAppController {

/**
 * beforeFilter callback
 *
 * - if the cart is configured to allow anonymous checkouts auth is allowed
 *   for all actions
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		if (Configure::read('Cart.anonymousCheckout') === true) {
			$this->Auth->allow('*');
		}
	}

/**
 * Index for shipping method.
 * 
 * @access public
 */
	public function index() {
		$this->set('shippingMethods', $this->Paginator->paginate());
	}

/**
 * View for shipping method.
 *
 * @param string $id, shipping method id 
 * @access public
 */
	public function view($id = null) {
		try {
			$shippingMethod = $this->ShippingMethod->view($id);
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}
		$this->set(compact('shippingMethod'));
	}

/**
 * Admin index for shipping method.
 * 
 * @access public
 */
	public function admin_index() {
		$this->ShippingMethod->recursive = 0;
		$this->set('shippingMethods', $this->Paginator->paginate());
	}

/**
 * Admin view for shipping method.
 *
 * @param string $id, shipping method id 
 * @access public
 */
	public function admin_view($id = null) {
		try {
			$shippingMethod = $this->ShippingMethod->view($id);
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}
		$this->set(compact('shippingMethod'));
	}

/**
 * Admin add for shipping method.
 * 
 * @access public
 */
	public function admin_add() {
		try {
			$result = $this->ShippingMethod->add($this->request->data);
			if ($result === true) {
				$this->Session->setFlash(__d('cart', 'The shipping method has been saved', true));
				$this->redirect(array('action' => 'index'));
			}
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}
	}

/**
 * Admin edit for shipping method.
 *
 * @param string $id, shipping method id 
 * @access public
 */
	public function admin_edit($id = null) {
		try {
			$result = $this->ShippingMethod->edit($id, $this->request->data);
			if ($result === true) {
				$this->Session->setFlash(__d('cart', 'Shipping Method saved', true));
				$this->redirect(array('action' => 'view', $this->ShippingMethod->data['ShippingMethod']['id']));
			} else {
				$this->request->data = $result;
			}
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect('/');
		}
	}

/**
 * Admin delete for shipping method.
 *
 * @param string $id, shipping method id 
 * @access public
 */
	public function admin_delete($id = null) {
		try {
			$result = $this->ShippingMethod->validateAndDelete($id, $this->request->data);
			if ($result === true) {
				$this->Session->setFlash(__d('cart', 'Shipping method deleted', true));
				$this->redirect(array('action' => 'index'));
			}
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->ShippingMethod->data['shippingMethod'])) {
			$this->set('shippingMethod', $this->ShippingMethod->data['shippingMethod']);
		}
	}

}
