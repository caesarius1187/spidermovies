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
                'contain' =>array(
                    'Eventosimpuesto'=>array(
                        'Impcli'=>array(
                            'Impuesto'
                        )
                    )
                ),
                'conditions' => array(
                    'Usosaldo.conceptosrestante_id' => $conid
                ),
            )
        );
        $this->set('usosaldos', $usosaldos);
        $this->layout = 'ajax';
    }
	public function add() {
        $this->loadModel('Conceptosrestante');
        $this->autoRender=false;
		if ($this->request->is('post')) {
            $respuesta = array();
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
                $respuesta['usosaldo']=$createdUsosaldo;
                $respuesta['respuesta']="Uso de saldo registrado con exito. ";
//                Si se guardo vamos a restar este improte en el "saldo"(monto) del concepto restante
                $miConceptosrestante = $this->Conceptosrestante->read(null, $this->request->data['Usosaldo']['conceptosrestante_id']);
//                Debugger::dump($miConceptosrestante);
                $saldoinicial =  $miConceptosrestante['Conceptosrestante']['monto'];
                $saldoinicial -=  $this->request->data['Usosaldo']['importe'];

                if($this->Conceptosrestante->saveField('monto', $saldoinicial)){
                    $respuesta['respuesta'].="Se redujo el saldo de libre disponibilidad del periodo. ";
                }else{
                    $respuesta['respuesta'].="NO se redujo el saldo de libre disponibilidad del periodo. ";
                }
			}
			else {
                $respuesta['respuesta']="Error al registrar el Uso de saldo. Por favor intente de nuevo mas tarde.";
            }
		}
        $this->set('data',$respuesta);
		$this->layout = 'ajax';
		$this->render('serializejson');
	}
}