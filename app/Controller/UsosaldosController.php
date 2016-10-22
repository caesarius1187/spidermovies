<?php
App::uses('AppController', 'Controller');
/**
 * Usosaldos Controller
 *
 * @property Usosaldo $Usosaldo
 * @property PaginatorComponent $Paginator
 */
class UsosaldosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');


/**
 * add method
 *
 * @return void
 */
    public function index($conid){
        $this->loadModel('Conceptosrestante');
        $conceptosrestante=$this->Conceptosrestante->find('first',array(
            'conditions' => array('Conceptosrestante.' . $this->Conceptosrestante->primaryKey => $conid)
        ));
        $this->set('conceptosrestante',$conceptosrestante);
        $usosaldos = $this->Usosaldo->find('all',array(
                'contain' =>array(),
                'conditions' => array(
                    'Usosaldo.conceptosrestante_id' => $conid
                ),
            )
        );
        $this->set('usosaldos', $usosaldos);
        $this->layout = 'ajax';
    }
	public function add() {
		$this->autoRender=false;
		if ($this->request->is('post')) {
			$this->request->data('Usosaldo.fecha',date('Y-m-d',strtotime($this->request->data['Usosaldo']['fecha'])));
			$this->Usosaldo->create();
			if ($this->Usosaldo->save($this->request->data)) {
				$id = $this->Usosaldo->getLastInsertID();
				$options = array(
					'conditions' => array(
						'Usosaldo.' . $this->Usosaldo->primaryKey => $id
						)
					);
				$createdUsosaldo = $this->Usosaldo->find('first', $options);
				$this->set('usosaldo',$createdUsosaldo);
			}
			else {
				$this->set('respuesta','Error: NO se creo el sueldo. Intente de nuevo.');	
			}
		}
		$this->layout = 'ajax';
		$this->render('add');
	}
}