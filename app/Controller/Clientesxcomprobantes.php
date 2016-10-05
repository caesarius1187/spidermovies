<?php
App::uses('AppController', 'Controller');
/**
 * Clientesxcomprobantes Controller
 *
 * @property Clientesxcomprobantes $Clientesxcomprobantes
 * @property PaginatorComponent $Paginator
 */
class ClientesxcomprobantesController extends AppController {

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
		/*$this->Clientesxcomprobante->recursive = 0;
		$this->set('Clientesxcomprobantes', $this->Paginator->paginate());*/
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		/*if (!$this->Clientesxcomprobante->exists($id)) {
			throw new NotFoundException(__('Invalid Clientesxcomprobante'));
		}
		$options = array('conditions' => array('Clientesxcomprobante.' . $this->Clientesxcomprobante->primaryKey => $id));
		$this->set('clientesxcomprobante', $this->Clientesxcomprobante->find('first', $options));*/
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		/*if ($this->request->is('post')) {
			$this->Clientesxcomprobante->create();
			if ($this->Clientesxcomprobante->save($this->request->data)) {
				$this->Session->setFlash(__('The Clientesxcomprobante has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Clientesxcomprobante could not be saved. Please, try again.'));
			}
		}
		$clientes = $this->Clientesxcomprobante->Cliente->find('list');
		$localidades = $this->Clientesxcomprobante->Localidade->find('list');
		$this->set(compact('clientes', 'localidades'));*/
	}
	public function addajax(
		$cliid = null,$tipo = null,$sede= null,$nombrefantasia= null,$puntodeventa= null,
		$localidadeid = null,$calle= null,$numero= null,$piso= null,$ofidepto = null,
		$ruta= null,$kilometro= null,$torre= null,$manzana = null,$entrecalles= null,
		$codigopostal= null,$telefono= null,$movil= null,$fax = null,$email= null,
		$personacontacto= null,$observaciones= null) {

	 	$this->request->onlyAllow('ajax');

		$this->Domicilio->create();
		$this->Domicilio->set('cliente_id',$cliid);
		$this->Domicilio->set('tipo',$tipo);
		$this->Domicilio->set('sede',$sede);
		$this->Domicilio->set('nombrefantasia',$nombrefantasia);
		$this->Domicilio->set('puntodeventa',$puntodeventa);

		$this->Domicilio->set('localidade_id',$localidadeid);
		$this->Domicilio->set('calle',$calle);
		$this->Domicilio->set('numero',$numero);
		$this->Domicilio->set('piso',$piso);
		$this->Domicilio->set('ofidepto',$ofidepto);

		$this->Domicilio->set('ruta',$ruta);
		$this->Domicilio->set('kilometro',$kilometro);
		$this->Domicilio->set('torre',$torre);
		$this->Domicilio->set('manzana',$manzana);
		$this->Domicilio->set('entrecalles',$entrecalles);

		$this->Domicilio->set('codigopostal',$codigopostal);
		$this->Domicilio->set('telefono',$telefono);
		$this->Domicilio->set('movil',$movil);
		$this->Domicilio->set('fax',$fax);
		$this->Domicilio->set('email',$email);

		$this->Domicilio->set('personacontacto',$personacontacto);
		$this->Domicilio->set('observaciones',$observaciones);


		if ($this->Domicilio->save($this->request->data)) {
			$this->set('respuesta','El Domicilio ha sido creado.');	
			$this->set('domicilio_id',$this->Domicilio->getLastInsertID());
		}
		else{

		}
		$this->layout = 'ajax';
		$this->render('addajax');
	}
/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Domicilio->exists($id)) {
			throw new NotFoundException(__('Invalid direccione'));
		}
		if ($this->request->is('post')) {
			if ($this->Domicilio->save($this->request->data)) {
				$this->Session->setFlash(__('La direccion a sido modificada.'));
				return $this->redirect(array('controller'=>'clientes', 'action' => 'view',$this->request->data['Domicilio']['cliente_id']));
			} else {
				$this->Session->setFlash(__('La direccion no a podido ser modificada, por favor intente de nuevo.'));
			}
		} else {
			$options = array('conditions' => array('Domicilio.' . $this->Domicilio->primaryKey => $id));
			$this->request->data = $this->Domicilio->find('first', $options);
		}
		$clientes = $this->Domicilio->Cliente->find('list');
		$localidades = $this->Domicilio->Localidade->find('list');
		$this->set(compact('clientes', 'localidades'));
	}

	public function editajax(
				$id=null,$cliid = null) {

	 	//$this->request->onlyAllow('ajax');

		if (!$this->Domicilio->exists($id)) {
			throw new NotFoundException(__('Invalid direccione'));
		}
		
		$options = array('conditions' => array('Domicilio.' . $this->Domicilio->primaryKey => $id));
		$this->request->data = $this->Domicilio->find('first', $options);

		$optionsCli = array('conditions' => array('Cliente.id' => $cliid));
		$clientes = $this->Domicilio->Cliente->find('list', $optionsCli);

		$localidades = $this->Domicilio->Localidade->find('list');
		$this->set(compact('clientes', 'localidades'));

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
		$this->Domicilio->id = $id;
		if (!$this->Domicilio->exists()) {
			throw new NotFoundException(__('Invalid direccione'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Domicilio->delete()) {
			$this->Session->setFlash(__('The direccione has been deleted.'));
		} else {
			$this->Session->setFlash(__('The direccione could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
