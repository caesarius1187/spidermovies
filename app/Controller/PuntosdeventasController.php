<?php
App::uses('AppController', 'Controller');
/**
 * Puntosdeventas Controller
 *
 * @property Puntosdeventa $Puntosdeventa
 * @property PaginatorComponent $Paginator
 */
class PuntosdeventasController extends AppController {

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
	public function index() {
		/*$this->Puntosdeventa->recursive = 0;
		$this->set('puntosdeventa', $this->Paginator->paginate());*/
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		/*if (!$this->Puntosdeventa->exists($id)) {
			throw new NotFoundException(__('Invalid Puntosdeventa'));
		}
		$options = array('conditions' => array('Contacto.' . $this->Contacto->primaryKey => $id));
		$this->set('contacto', $this->Contacto->find('first', $options));*/
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$data=array();
			$this->Puntosdeventa->create();
			if ($this->Puntosdeventa->save($this->request->data)) {
				$data['puntoid'] = $this->Puntosdeventa->getLastInsertID();
				$options = array(
					'conditions' => array('Puntosdeventa.' . $this->Puntosdeventa->primaryKey => $data['puntoid']),
					'contain' => array('Domicilio')
					);
				$data['puntodeventa'] = $this->Puntosdeventa->find('first', $options);
				$data['respuesta']='El Punto de venta ha sido Guardado.';
			} else {
				$data['respuesta']='El Puntosdeventa no ha sido Guardado. Por favor intente de nuevo mas tarde 0.';
			}
		}else{
				$data['respuesta']='El Puntosdeventa no ha sido Guardado. Por favor intente de nuevo mas tarde 1.';
		}
		$this->set('data',$data);
		$this->layout = 'ajax';
		$this->render('serializejson');
	}
	
/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$showform = true;
		if (!$this->Puntosdeventa->exists($id)) {
			throw new NotFoundException(__('Invalid Puntosdeventa'));
		}
		$data=array();
		if ($this->request->is('post')) {
			if ($this->Puntosdeventa->save($this->request->data)) {
				$options = array(
					'conditions' => array('Puntosdeventa.' . $this->Puntosdeventa->primaryKey => $id),
					'contain' => array('Domicilio')
					);
				$data['puntodeventa'] = $this->Puntosdeventa->find('first', $options);
				$data['respuesta']='El Punto de venta ha sido Guardado.';
			} else {
				$data['respuesta']='El Puntosdeventa no ha sido Guardado. Por favor intente de nuevo mas tarde 0.';
			}
			$showform = false;
		} else {
			
		}
		$options = array(
					'conditions' => array('Puntosdeventa.' . $this->Puntosdeventa->primaryKey => $id),
					'contain' => array('Domicilio')
					);
		$this->request->data = $this->Puntosdeventa->find('first', $options);
		$clientes = $this->Puntosdeventa->Cliente->find('list');
		$this->set(compact('clientes'));
		$optionsDoc = array(
					'conditions' => array('Domicilio.cliente_id'=>$this->request->data['Puntosdeventa']['cliente_id'] ),
			);			

		$domicilios = $this->Puntosdeventa->Domicilio->find('list',$optionsDoc);
		$this->set('domicilios', $domicilios);
			
		$optionSisFact=array(
			'Controlador Fiscal'=>'Controlador Fiscal',
			'Factuweb'=>'Factuweb (Imprenta) ',
			'RECE'=>'RECE para aplicativo y web services',
			'Factura en Linea'=>'Factura en Linea'
			);
		$this->set('optionSisFact', $optionSisFact);
		
		$this->set('showform',$showform);
		$this->layout = 'ajax';
		$this->render('edit');
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		/*$this->Contacto->id = $id;
		if (!$this->Contacto->exists()) {
			throw new NotFoundException(__('Invalid contacto'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Contacto->delete()) {
			$this->Session->setFlash(__('The contacto has been deleted.'));
		} else {
			$this->Session->setFlash(__('The contacto could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));*/
	}}
