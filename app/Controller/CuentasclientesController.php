<?php
App::uses('AppController', 'Controller');
/**
 * @property Cbus $Cbus
 * @property PaginatorComponent $Paginator
 */
class CuentasclientesController extends AppController {

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
	public function plancuentas($ClienteId = null) 
	{				
		$this->loadModel('Cliente');
		$options = array(
					'conditions' => array(
						'Cuentascliente.cliente_id'=> $ClienteId						
						)
					);
		$cuentasclientes = $this->Cuentascliente->find('all', $options);
		$this->set('cuentasclientes',$cuentasclientes);
		

		$clienteOpc = array(
							'conditions' => array(
								'Cliente.id'=> $ClienteId						
								),
							'recursive' => -1,
						);
		$cliente = $this->Cliente->find('first', $clienteOpc);
		$this->set('cliente',$cliente);
	}
	public function informesumaysaldo($clienteid = null, $periodo = null){
		$this->loadModel('Cliente');
		$this->loadModel('Movimiento');
		$optionCliente = [
			'contain' => [
				'Cuentascliente'=>[
					'Cuenta',
					'Saldocuentacliente'=>[
						'conditions'=>[
							'Saldocuentacliente.periodo'=>$periodo
						],
					],
					'Movimiento'
				],
			],
			'conditions' => ['Cliente.id'=>$clienteid]
		];
		$cliente = $this->Cliente->find('first',$optionCliente);
		$this->set('cliente',$cliente);
		$this->set('periodo',$periodo);
	}
}
