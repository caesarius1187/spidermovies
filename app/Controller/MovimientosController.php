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
	//public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index($ClienteId) 
	{		
		$this->loadModel('Cliente');
        $this->loadModel('Cuentascliente');        
        $this->loadModel('Asiento');        

        $options = array(
            'contain'=>array(
            	'Asiento' => [],
                'Cuentascliente'=>[
                    'Cuenta'=>[]
                ]
            ),
            'conditions' => array(),
        );

		$movimientos = $this->Movimiento->find('all', $options);
		$this->set('movimientos',$movimientos);				

        $clienteOpc = array(
                            'conditions' => array(
                                'Cliente.id'=> $ClienteId                       
                                ),
                            'recursive' => -1,
                        );
        $cliente = $this->Cliente->find('first', $clienteOpc);
        $this->set('cliente',$cliente);

        //$this->Cuentascliente->recursive = -1;
        $CuentasClientesopt = [
                                'contain' => [
                                    'Cuenta'                         
                                ],                                
                                'fields' => [
                                    'Cuentascliente.id',
                                    'Cuentascliente.nombre'
                                ],
                                'conditions' => [
                                    'Cuentascliente.cliente_id'=> $ClienteId                        
                                ]    
                              ];
        $cuentasclientes = $this->Cuentascliente->find('list', $CuentasClientesopt);
        $this->set('cuentasclientes',$cuentasclientes);

        $CuentasClientesopt = [
            'contain' => [
                'Movimiento'=>[
                    'Cuentascliente'=>[
                        'Cuenta'=>[],
                        'fields' => [
                            'Cuentascliente.id',
                            'Cuentascliente.nombre'
                        ],
                    ]
                ]
            ],
            'conditions'=>['Asiento.cliente_id'=>$ClienteId]
        ];
        $asientos = $this->Asiento->find('all', $CuentasClientesopt);
        $this->set('asientos',$asientos);
	}
}
