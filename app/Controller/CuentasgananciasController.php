<?php
App::uses('AppController', 'Controller');
/**
 * Cuentasganancias Controller
 *
 * @property CuentasgananciasController $CuentasgananciasController
 * @property PaginatorComponent $Paginator
 */
class CuentasgananciasController extends AppController {

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
	public function index($cliid = null) {
		$this->loadModel('Cliente');
		$this->loadModel('Asientoestandare');
		$this->loadModel('Cuentascliente');
		$this->loadModel('Cuenta');
		$respuesta=[];
		$respuesta['respuesta']="";
		if ($this->request->is('post')) {
			$this->Cuentasganancia->create();
			/*Aca tenemos que preguntar cual es la cuenta_id que selecciono y si existe una cuentasclietes relacionada*/
			/*Sino existe se la crea y si existe se usa ese ID para crear las Cuentas Ganancias*/
			foreach ($this->request->data['Cuentasganancia'] as $c => $cuentaganancia){
				$conditionsCuentascliente = array(
					'Cuentascliente.cliente_id' => $cuentaganancia['cliente_id'],
					'Cuentascliente.cuenta_id' => $cuentaganancia['cuenta_id']
				);
				if (!$this->Cuentascliente->hasAny($conditionsCuentascliente)){
					/*Ahora si estamos seguro de que esta cuenta no esta activada y podemos activarla
                    para este cliente y relacionarla al CBU*/
					$conditionsCuentas=[
						'conditions'=>['Cuenta.id'=> $cuentaganancia['cuenta_id']]
					];
					$cuentaACargar = $this->Cuenta->find('first', $conditionsCuentas);
					$nombreCuentaClie = $cuentaACargar['Cuenta']['nombre'];
					$this->Cuentascliente->create();
					$this->Cuentascliente->set('cliente_id',$cuentaganancia['cliente_id']);
					$this->Cuentascliente->set('cuenta_id',$cuentaganancia['cuenta_id']);
					$this->Cuentascliente->set('nombre',$nombreCuentaClie);
					if ($this->Cuentascliente->save())
					{
						$respuesta['respuesta'] .= 'Cuenta de banco activada correctamente.';
					}
					else
					{
						$respuesta['respuesta'] .= 'Error: Al guardar cuenta de impuesto. Por favor intente nuevamente.';
					}
					$CuentaClienteNuevaId = $this->Cuentascliente->getLastInsertId();
					$respuesta[$c.'1CuentaClienteNuevaId'] = $this->request->data['Cuentasganancia'][$c]['cuentascliente_id'];
					$this->request->data['Cuentasganancia'][$c]['cuentascliente_id'] = $CuentaClienteNuevaId;
					$respuesta[$c.'2CuentaClienteNuevaId'] = $this->request->data['Cuentasganancia'][$c]['cuentascliente_id'];
				}else{
					$cuentacliente = $this->Cuentascliente->find('first',['conditions'=>$conditionsCuentascliente]);
					$CuentaClienteNuevaId = $cuentacliente['Cuentascliente']['id'];
					$this->request->data['Cuentasganancia'][$c]['cuentascliente_id'] = $CuentaClienteNuevaId;
					$respuesta[$c.'La Modificada:'] = $this->request->data['Cuentasganancia'];
				}
			}
			if ($this->Cuentasganancia->saveAll($this->request->data['Cuentasganancia']))
			{
				$respuesta['respuesta'] .='Cuenta de de la actividad ha sido relacionada correctamente.';
			}else{
				$respuesta['respuesta'] .='Cuenta de de la actividad NO ha sido relacionada correctamente. Por favor 
				intentelo de nuevo mas tarde';
				$respuesta['error']=1;
			}
			$this->set('data',$respuesta);
			$this->autoRender=false;
			$this->layout = 'ajax';
			$this->render('serializejson');
			return;
		}
		$optionsCliente = [
			'contain'=>[
				'Actividadcliente'=>[
					'Cuentasganancia'=>[
						'Cuentascliente'=>[
							'Cuenta'
						]
					],
					'Actividade'
				]
			],
			'conditions'=>[
				'Cliente.id'=>$cliid
			]
		];
		$cliente = $this->Cliente->find('first',$optionsCliente);
		$categorias = [
			'primeracateg'=>'primera',
			'segundacateg'=>'segunda',
			'terceracateg'=>'tercera empresas',
			'terceracateg45'=>'tercera otros',
			'cuartacateg'=>'cuarta'
		];
		$optionsAsientosestandares = [
			'contain'=>[
				'Cuenta'
			],
			'fields'=>[
				'Asientoestandare.cuenta_id','Cuenta.nombre'
			],
			'conditions'=>[
				'tipoasiento'=>'primeracateg'
			]
		];
		$cuentascategoriaprimera =	$this->Asientoestandare->find('list',$optionsAsientosestandares);
		$optionsAsientosestandares['conditions']['tipoasiento'] = 'segundacateg';
		$cuentascategoriasegunda =	$this->Asientoestandare->find('list',$optionsAsientosestandares);
		$optionsAsientosestandares['conditions']['tipoasiento'] = 'terceracateg';
		$cuentascategoriatercera =	$this->Asientoestandare->find('list',$optionsAsientosestandares);
		$optionsAsientosestandares['conditions']['tipoasiento'] = 'terceracateg45';
		$cuentascategoriaterceraotros =	$this->Asientoestandare->find('list',$optionsAsientosestandares);
		$optionsAsientosestandares['conditions']['tipoasiento'] = 'cuartacateg';
		$cuentascategoriacuarta =	$this->Asientoestandare->find('list',$optionsAsientosestandares);

		$this->set(compact('cliente','categorias','cuentascategoriaprimera','cuentascategoriasegunda','cuentascategoriatercera','cuentascategoriaterceraotros','cuentascategoriacuarta'));
		$this->layout = 'ajax';

	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null,$cliid = null) {
		$this->Actividadcliente->id = $id;
		if (!$this->Actividadcliente->exists()) {
			throw new NotFoundException(__('Invalid actividadcliente'));
		}
		$this->request->onlyAllow('post');
		if ($this->Actividadcliente->delete()) {
			$this->Session->setFlash(__('La Actividad del Cliente ha sido eliminada.'));
		} else {
			$this->Session->setFlash(__('La Actividad del Cliente NO ha sido eliminada.'));
		}
		return $this->redirect(array('controller'=>'clientes', 'action' => 'view', $cliid));
	}}
