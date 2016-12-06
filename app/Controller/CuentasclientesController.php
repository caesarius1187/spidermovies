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
	public function GuardarDescripcion($CuentaClienteId, $Descripcion)
	{
		$this->Cuentascliente->id = $CuentaClienteId;
		if($this->Cuentascliente->saveField('descripcioncuenta', $Descripcion))
		{
			$data['respuesta']='Descripcion modificada exitosamente.';
		}
		else
		{
			$data['respuesta']='Error al modificar la descripcion.';
		}
		$this->set('data',$data);
		$this->layout = 'ajax';
		$this->render('serializejson');	
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
