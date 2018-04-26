<?php
App::uses('AppController', 'Controller');
/**
 * Liquidaciondetalles Controller
 *
 * @property Liquidaciondetalle $Liquidaciondetalle
 * @property PaginatorComponent $Paginator
 */
class LiquidaciondetallesController extends AppController {

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
	public function add() {
            $this->autoRender=false; 
            $data = array();
            if ($this->request->is('post')) {
               
                $this->Liquidaciondetalle->create();
                $this->request->data['liquidaciondetalle']['fechainforme']=date('Y-m-d',strtotime($this->request->data['liquidaciondetalle']['fechainforme']));
                if ($this->Liquidaciondetalle->save($this->request->data['liquidaciondetalle'])) {
                    $data['respuesta']='Guardado.';
                    $data['datos']=$this->request->data['liquidaciondetalle'];
                    $id = $this->Liquidaciondetalle->getLastInsertID();
                    $options = array('conditions' => array('Liquidaciondetalle.' . $this->Liquidaciondetalle->primaryKey => $id));
                    $data['provedor']=$this->Liquidaciondetalle->find('first', $options);
                } else {
                    $data['respuesta']='NO Guardado. Por favor intente de nuevo mas tarde';
                }
            }else{
                    $data['respuesta']='NO Guardado. Por favor intente de nuevo mas tarde';
            }
            $this->layout = 'ajax';
            $this->set('data', $data);
            $this->render('serializejson');
	}

}