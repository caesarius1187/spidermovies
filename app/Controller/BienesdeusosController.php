<?php
App::uses('AppController', 'Controller');
/**
 * Bienesdeusos Controller
 *
 * @property Bienesdeuso $Bienesdeuso
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class BienesdeusosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Bienesdeuso->recursive = 0;
		$this->set('bienesdeusos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Bienesdeuso->exists($id)) {
			throw new NotFoundException(__('Invalid bienesdeuso'));
		}
		$options = array('conditions' => array('Bienesdeuso.' . $this->Bienesdeuso->primaryKey => $id));
		$this->set('bienesdeuso', $this->Bienesdeuso->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add($compraid = null) {
		if ($this->request->is('post')) {
			$this->Bienesdeuso->create();
			if($this->request->data['Bienesdeuso']['fechaadquisicion']!=""){
				$this->request->data('Bienesdeuso.fechaadquisicion',date('Y-m-d',strtotime($this->request->data['Bienesdeuso']['fechaadquisicion'])));
			}
			if ($this->Bienesdeuso->save($this->request->data)) {
				$respuesta['respuesta']='El bien de uso ha sido guardado correctamente.';
				//aca vamos a crear las relaciones con cuentas contables, que se van a generar en funcion de si tiene 3ra o no 
				$cliente=$this->Cliente->find('first', array(
					'contain'=>array(
						'Actividadcliente'=>array(
							'Cuentasganancia'
						),
					),
					'conditions' => array(
						'Cliente.id' => $this->request->data['Bienesdeuso']['cliente_id'], // <-- Notice this addition
					),
				));
				$tienetercera = false;
				foreach ($cliente['Actividadcliente'] as $actividadcliente){
					foreach ($actividadcliente['Cuentasganancia'] as $cuentasganancia){
						if($cuentasganancia['categoria']=='terceracateg'){
							$tienetercera = true;
						}
					}
				}
				if($tienetercera){
                    //hay que dar de alta la cuenta de bien de uso reemplazando XX
                    //vamos a armar el nombre del Bien de uso
                    switch ($this->request->data['Bienesdeuso']['cliente_id']){
                        case 'Automotor':
                            break;
                        case 'Inmueble':
                            break;
                        case 'Aeronave':
                            break;
                        case 'Naves, Yates y similares':
                            
                        break;
                    }
				}else{

				}
			} else {
				$respuesta['respuesta']='El bien de uso no se guardo correctamente.
				 Por favor intente de nuevo mas tarde.';
			}
			$this->layout = 'ajax';
			$this->set('data', $respuesta);
			$this->render('serializejson');
			return;
		}
		$optioncompra = [
			'contain'=>[
				'Bienesdeuso'
			],
			'conditions'=>[
				'Compra.id'=>$compraid
			]
		];
		$compra = $this->Bienesdeuso->Compra->find('first',$optioncompra);
		if(count($compra['Bienesdeuso'])>0){
			$this->request->data = ['Bienesdeuso'=>$compra['Bienesdeuso'][0]];
		}
		$conditionsLocalidades = array(
			'contain'=>'Partido',
			'fields'=>array('Localidade.id','Localidade.nombre','Partido.nombre'),
			'order'=>array('Partido.nombre','Localidade.nombre')
		);
		$localidades = $this->Bienesdeuso->Localidade->find('list',$conditionsLocalidades);
		$this->set(compact('compra', 'localidades'));
		if($this->request->is('ajax')){
			$this->layout = 'ajax';
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Bienesdeuso->exists($id)) {
			throw new NotFoundException(__('Invalid bienesdeuso'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Bienesdeuso->save($this->request->data)) {
				$this->Session->setFlash(__('The bienesdeuso has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The bienesdeuso could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Bienesdeuso.' . $this->Bienesdeuso->primaryKey => $id));
			$this->request->data = $this->Bienesdeuso->find('first', $options);
		}
		$compras = $this->Bienesdeuso->Compra->find('list');
		$localidades = $this->Bienesdeuso->Localidade->find('list');
		$this->set(compact('compras', 'localidades'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Bienesdeuso->id = $id;
		if (!$this->Bienesdeuso->exists()) {
			throw new NotFoundException(__('Invalid bienesdeuso'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Bienesdeuso->delete()) {
			$this->Session->setFlash(__('The bienesdeuso has been deleted.'));
		} else {
			$this->Session->setFlash(__('The bienesdeuso could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
