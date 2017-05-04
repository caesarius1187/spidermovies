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
	public function index($asientoid)
	{		
		$this->loadModel('Cliente');
        $this->loadModel('Cuentascliente');        
        $this->loadModel('Asiento');

        $CuentasClientesopt = [
            'contain' => [
                'Cliente'=>[
                   // 'Cuentascliente'=>['Cuenta']
                ],
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
            'conditions'=>[
                'Asiento.id'=>$asientoid
            ]
        ];
        $asientos = $this->Asiento->find('all', $CuentasClientesopt);
        $this->set('asientos',$asientos);

        $CuentasClientesopt = [
            'contain' => [
                'Cuenta'
            ],
            'fields' => [
                'Cuentascliente.id',
                'Cuentascliente.nombre',
                'Cuenta.numero',

            ],
            'conditions' => [
                'Cuentascliente.cliente_id'=> $asientos[0]['Cliente']['id']
            ]
        ];
        $cuentasclientes = $this->Cuentascliente->find('list', $CuentasClientesopt);
        $this->set('cuentasclientes',$cuentasclientes);


        $this->layout = 'ajax';
	}
    public function delete ($moviid){
        $this->Movimiento->id = $moviid;
        if (!$this->Movimiento->exists()) {
            throw new NotFoundException(__('Invalid Movimiento'));
        }

        $this->request->onlyAllow('post', 'delete');
        $data=[];
        if ($this->Movimiento->delete()) {
           $data['respuesta']='El movimiento ha sido eliminado';
        } else {
            $data['respuesta']='El movimiento NO ha sido eliminado. Por favor intente de nuevo mas tarde';
        }
        $this->set('data',$data);
        $this->autoRender=false;
        $this->layout = 'ajax';
        $this->render('serializejson');
    }
}
