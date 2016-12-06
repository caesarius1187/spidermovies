<?php
App::uses('AppController', 'Controller');
/**
 * Cbus Controller
 *
 * @property Cbus $Cbus
 * @property PaginatorComponent $Paginator
 */
class MovimientosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() 
	{				
		$movimientos = $this->Movimiento->find('all');
		$this->set('movimientos',$movimientos);				
	}
}
