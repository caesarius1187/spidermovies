<?php
App::uses('AppController', 'Controller');
/**
 * Valorrecibos Controller
 *
 * @property Valorrecibo $Valorrecibo
 * @property PaginatorComponent $Paginator
 */
class ValorrecibosController extends AppController {

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
    public function guardardatosmasivos() {
        if (isset($this->request->data['Valorrecibo'])) {
            $this->loadModel('Valorrecibo');
            $this->Valorrecibo->create();
            $respuesta = [];
            $respuesta['respuesta']="";
            $respuesta['error']="";
            $respuesta['nuevos']=[];
            $respuesta['ya existian']=[];
            foreach ($this->request->data['Valorrecibo'] as $kvr => $valorRecibo) {
                $conditions = array(
                    'Valorrecibo.periodo' => $valorRecibo['periodo'],
                    'Valorrecibo.tipoliquidacion' => $valorRecibo['tipoliquidacion'],
                    'Valorrecibo.cctxconcepto_id' => $valorRecibo['cctxconcepto_id'],
                    'Valorrecibo.empleado_id' => $valorRecibo['empleado_id'],
                );
                if ($this->Valorrecibo->hasAny($conditions)){
                    $optionVR = [
                        'conditions'=>$conditions,
                        'contain'=>[]
                    ];
                    $myVR = $this->Valorrecibo->find('first',$optionVR);
                    $valorRecibo['id']=$myVR['Valorrecibo']['id'];
                    $respuesta['ya existian'][]=$valorRecibo;
                    $respuesta['ya existian'][]=$myVR;
                }else{
                    $respuesta['nuevos'][]=$valorRecibo;
                }
                if ($this->Valorrecibo->save($valorRecibo)) {
                    $respuesta['respuesta'].=" Si guarde";
                } else {
                    $respuesta['error'].=" no se pudo guardar un Valor del sueldo";
                }
            }
            $this->set('data',$respuesta);
            $this->autoRender=false;
            $this->layout = 'ajax';
            $this->render('serializejson');
        }
    }
}