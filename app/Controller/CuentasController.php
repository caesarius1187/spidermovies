<?php
App::uses('AppController', 'Controller');
/**
 * Cbuses Controller
 *
 * @property Cbus $Cbus
 * @property PaginatorComponent $Paginator
 */
class CuentasController extends AppController {

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
	public function view($ClienteId) 
	{				
		/*$options = array(
					'conditions' => array(
						'Cuentascliente.cliente_id'=> $ClienteId						
						)
					);					
		$cuentasclientes = $this->Cuentascliente->find('all', $options);
		*/

		$options = array(
			'contain'=>array(),
			'conditions' => array('Cuenta.tipo' => 'rubro'), 
			'fields'=> array('Cuenta.id,Cuenta.numero,Cuenta.nombre,Cuenta.tipo,Cuenta.ajuste,cuentascliente.cuenta_id'),
			'joins'=>array(
				array('table'=>'cuentasclientes', 
	                  'alias' => 'cuentascliente',
	                  'type'=>'left',
	                  'conditions'=> array(
	                 		'cuentascliente.cuenta_id = Cuenta.id AND cuentascliente.cliente_id = '.$ClienteId
	           		   )
                 	),
				)
		);

		$cuentas = $this->Cuenta->find('all', $options);
		$this->set('cuentas',$cuentas);				
		$this->set('clienteId',$ClienteId);
	}

	public function activar($ClienteId,$CuentaId,$Activo)
	{
		$this->loadModel('Cuentascliente');

		if ($Activo == "1")
		{
			//Activar			
			$this->Cuentascliente->create();
			$this->Cuentascliente->set('cliente_id',$ClienteId);
			$this->Cuentascliente->set('cuenta_id',$CuentaId);
			if ($this->Cuentascliente->save()) 
			{ 				
				$data['respuesta']='Cuenta activada correctamente.';
			}
			else
			{				
				$data['respuesta']='Error al guardar cuenta. Por favor intente nuevamente.';			
			}
		}
		else
		{
			/*todo: Borrar Asientos
			Esto deberia disparar un alerta cuando hay Asientos relacionados a esta cuenta por que podriamos
			eliminarlos y desactivarlos horriblemente*/
			//Desactivar


			if ($this->Cuentascliente->deleteAll(array(
														'Cuentascliente.cliente_id' => $ClienteId,
														'Cuentascliente.cuenta_id' => $CuentaId
											   			),false
										 		)	
				)
				$data['respuesta']='Cuenta desactivada correctamente.';
			else
				$data['respuesta']='Error al desactivar cuenta.';				
		}


		$this->set('data',$data);
		$this->layout = 'ajax';
		$this->render('serializejson');		
	}
}
