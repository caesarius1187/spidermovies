<?php
App::uses('AppController', 'Controller');
/**
 * Plandepagos Controller
 *
 * @property Plandepago $Plandepago
 * @property PaginatorComponent $Paginator
 */
class PlandepagosController extends AppController {

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
		$this->Plandepago->recursive = 0;
		if ($this->request->is('post')) {
			$this->set('cuotas',$this->request->data['Plandepago']['cuotas']);
			$this->set('organismo',$this->request->data['Plandepago']['organismo']);
			$this->set('plan',$this->request->data['Plandepago']['plan']);
			$this->set('cbu',$this->request->data['Plandepago']['cbu']);
			$this->set('clienteid',$this->request->data['Plandepago']['cliente_id']);
			$this->set('dia',$this->request->data['Plandepago']['dia']);
			$this->set('montocuota',$this->request->data['Plandepago']['montocuota']);
		}
		$conditionsCli = array(
							 'Grupocliente',
							 );

		$clientes = $this->Plandepago->Cliente->find('list',array(
								'contain' =>$conditionsCli,
								'conditions' => array(
						 			'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id')
						 			),
								'fields'=>array('Cliente.id')
						 		)
							);
		$clientes2 = $this->Plandepago->Cliente->find('list',array(
								'contain' =>$conditionsCli,
								'conditions' => array(
						 			'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id')
						 			),
								'order'=>array('Grupocliente.nombre','Cliente.nombre'),
								'fields'=>array('Cliente.id','Cliente.nombre','Grupocliente.nombre')
						 		)
							);

		$user=$this->Session->read('Auth.User.estudio_id');
		$misorganismos = array ('afip'=>'AFIP','dgr'=>'DGR','dgrm'=>'DGRM','sindicato'=>'Sindicato','banco'=>'Banco','otros'=>'otros');
		$this->set(compact('user','misorganismos'));
		
		$options = array('conditions' => array(
					'Plandepago.cliente_id'=> $clientes
			));
		$this->set('plandepagos', $this->Plandepago->find('all', $options));
		$this->set('clientes', $clientes2);
	}
	
/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Plandepago->exists($id)) {
			throw new NotFoundException(__('Invalid plandepago'));
		}
		$options = array('conditions' => array('Plandepago.' . $this->Plandepago->primaryKey => $id));
		$this->set('plandepago', $this->Plandepago->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$data=array();
		if ($this->request->is('post')) {
			
			$result = 'Resultado ';
			foreach ($this->request->data['Plandepago'] as $planpago) {
				$this->Plandepago->create();
				$this->Plandepago->set($planpago);
				$this->Plandepago->set('fchvto',date('Y-m-d',strtotime($planpago['fchvto'])));
				$data['error'] = 0;
				if($this->Plandepago->save()){
					$result .= $planpago['item']." ok, ";
				}else{
					$result .= $planpago['item']." fallo, ";
					$data['error'] = 1;
				}
			}
			$data['resultado'] = $result;
		}
		$this->set('data', $data);
		$this->autoRender=false; 				
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
		if (!$this->Plandepago->exists($id)) {
			throw new NotFoundException(__('Invalid plandepago'));
		}
		if ($this->request->is('POST')) {
			$this->Plandepago->set($id);
			$this->request->data['Plandepago']['fchrealizado'] = date('Y-m-d',strtotime($this->request->data['Plandepago']['fchrealizado']));
			if ($this->Plandepago->save($this->request->data)) {
				$result ="El Plan de pago ha sido modificado";
				$data['resultado'] = $result;
				$data['error'] = 0;
			} else {
				$result ="El Plan de pago NO ha sido modificado";
				$data['resultado'] = $result;
				$data['error'] = 1;
			}
			$this->set('data', $data);
			$this->autoRender=false; 				
			$this->layout = 'ajax';
			$this->render('serializejson');
		} else {
			$options = array('conditions' => array('Plandepago.' . $this->Plandepago->primaryKey => $id));
			$this->request->data = $this->Plandepago->find('first', $options);
			$this->layout = 'ajax';
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Plandepago->id = $id;
		if (!$this->Plandepago->exists()) {
			throw new NotFoundException(__('Invalid plandepago'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Plandepago->delete()) {
			$this->Session->setFlash(__('La cuota del plan de pago ha sido eliminado.'));
		} else {
			$this->Session->setFlash(__('La cuota del plan de pago NO ha sido eliminado. Por favor intente nuevamente mas tarde'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
