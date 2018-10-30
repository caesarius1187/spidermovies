<?php
class OrderAddressesController extends CartAppController {

/**
 * Index
 *
 * @return void
 */
	public function index() {
		$this->set('addresses', $this->OrderAddress->byUserId($this->Auth->user('id')));
	}

}