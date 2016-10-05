<?php
App::uses('AppController', 'Controller');
/**
 * Empleados Controller
 *
 * @property Empleado $Empleado
 * @property PaginatorComponent $Paginator
 */
class EmpleadosController extends AppController {

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
		$this->Empleado->recursive = 0;
		$this->set('empleados', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Empleado->exists($id)) {
			throw new NotFoundException(__('Invalid empleado'));
		}
		$options = array('conditions' => array('Empleado.' . $this->Empleado->primaryKey => $id));
		$this->set('empleado', $this->Empleado->find('first', $options));
	}
	public function papeldetrabajosueldos($cliid=null,$periodo=null ,$empleadoamostrar=null, $tipoliquidacion=null) {
        $this->loadModel('Impcli');
		$this->loadModel('Empleado');
		$this->loadModel('Concepto');
		$this->loadModel('Cctxconcepto');
		if (isset($this->request->data['Cctxconcepto'])) {
			$this->loadModel('Cctxconcepto');
			$this->Cctxconcepto->create();
			//lo que vamos a guardar aca es un cctXConcepto personalizado
			//entonces tenemos que buscar el maximo codigo cargado y usar el siguiente
			$optionsConcepto=array(
				'fields'=>array(
					'MAX(SUBSTRING(Concepto.codigo,2)) as codigoMax'
				),
				'conditions'=>array(
					'SUBSTRING(Concepto.codigo,1,1)'=>'P'
				)
			);
			$maxCodigoConcepto= $this->Concepto->find('all',$optionsConcepto);

			$optionsCctxconcepto=array(
				'fields'=>array(
					'MAX(SUBSTRING(Cctxconcepto.codigopersonalizado,2)) as codigoMax'
				),
				'conditions'=>array(
					'SUBSTRING(Cctxconcepto.codigopersonalizado,1,1)'=>'P'
				)
			);
			$maxCodigoCctXConcepto= $this->Cctxconcepto->find('all',$optionsCctxconcepto);
			$this->set(compact('maxCodigoConcepto','maxCodigoCctXConcepto'));
			//Debugger::dump($maxCodigoConcepto);
			//Debugger::dump($maxCodigoCctXConcepto);
			$codigoMax = 0;
			if($maxCodigoConcepto[0][0]['codigoMax']*1>$maxCodigoCctXConcepto[0][0]['codigoMax']*1){
				$codigoMax = $maxCodigoConcepto[0][0]['codigoMax']+1;
			}else{
				$codigoMax = $maxCodigoCctXConcepto[0][0]['codigoMax']+1;
			}
			$this->request->data['Cctxconcepto']['codigopersonalizado'] = "P".$codigoMax;
			if ($this->Cctxconcepto->save($this->request->data['Cctxconcepto'])) {

			} else {
				$this->Session->setFlash(__('Error al guardar el concepto para el convenio por favor intente de nuevo mas tarde.'));
			}
		}
		if (isset($this->request->data['Valorrecibo'])) {
			$this->loadModel('Valorrecibo');
			$this->Valorrecibo->create();
			if ($this->Valorrecibo->saveAll($this->request->data['Valorrecibo'])) {

			} else {
				$this->Session->setFlash(__('Error al guardar el recibo de sueldo por favor intente de nuevo mas tarde.'));
			}
		}

		$this->set(compact('periodo'));
		if(!isset($tipoliquidacion)||$tipoliquidacion==null||$tipoliquidacion==""){
			$tipoliquidacion=1;
		}
		$optionsempleados = array(
			'contain'=>array(
					'Conveniocolectivotrabajo'=>array(
						'Cctxconcepto'=>array(
							'Concepto',
							'Valorrecibo'=>array(
								'conditions'=>array(
									'Valorrecibo.empleado_id'=>$empleadoamostrar,
                                    'Valorrecibo.tipoliquidacion'=>$tipoliquidacion,
                                    'Valorrecibo.periodo'=>$periodo,
								)
							),
							'conditions'=>array(
								'OR'=>array(
									'AND'=>array(
										'Cctxconcepto.cliente_id' => $cliid,
										'Cctxconcepto.campopersonalizado' => 1,
									),
									'Cctxconcepto.campopersonalizado' => 0,
								),
							),
							'order'=>array('Cctxconcepto.orden')
						),
					),
				),
			'conditions' => array('Empleado.' . $this->Empleado->primaryKey => $empleadoamostrar)
		);
		$empleado = $this->Empleado->find('first', $optionsempleados);

        $optionsLiquidaciones = array(
            'contain'=>array(
                'Conveniocolectivotrabajo'=>array(
                    'Cctxconcepto'=>array(
                        'Valorrecibo'=>array(
                            'conditions'=>array(
                                'Valorrecibo.empleado_id'=>$empleadoamostrar,
                            ),
                            'fields'=>array('Distinct(Valorrecibo.tipoliquidacion)'),
                        ),
                        'conditions'=>array(
                            'OR'=>array(
                                'AND'=>array(
                                    'Cctxconcepto.cliente_id' => $cliid,
                                    'Cctxconcepto.campopersonalizado' => 1,
                                ),
                                'Cctxconcepto.campopersonalizado' => 0,
                            ),
                        ),
                        'fields'=>array('id'),
						'limit' => 1
                    ),
                    'fields'=>array('id'),
                ),
            ),
            'fields'=>array('id'),
            'conditions' => array('Empleado.' . $this->Empleado->primaryKey => $empleadoamostrar)
        );
        $tieneLiquidacion = $this->Empleado->find('first', $optionsLiquidaciones);
		/*
         * Primero vamos a ordenar los cctxconcepto en base a la seccion a la que pertenece
         * */
		for ($i=0;$i<count($empleado['Conveniocolectivotrabajo']['Cctxconcepto'])-1;$i++){
			for ($j=$i;$j<count($empleado['Conveniocolectivotrabajo']['Cctxconcepto']);$j++) {
				if(!isset($empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$i]['Concepto'])){
					$seccionburbuja = $empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$i]['seccionpersonalizada'];
					$seccionaux = $empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$j]['seccionpersonalizada'];
				}else {
                    if(!isset($empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$i]['Concepto']['seccion'])){
                        Debugger::dump($empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$i]['Concepto']);
                    }
					$seccionburbuja = $empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$i]['Concepto']['seccion'];
					$seccionaux = $empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$j]['Concepto']['seccion'];
				}
				$burbuja=0;
				$aux=0;
				switch ($seccionburbuja){
					case 'Datos del Empleado':
						$burbuja=1;
						break;
					case 'REMUNERATIVOS':
						$burbuja=2;
						break;
					case 'NO REMUNERATIVOS':
						$burbuja=3;
						break;
					case 'APORTES':
						$burbuja=4;
						break;
					case 'TOTALES':
						$burbuja=5;
						break;
					case 'OBRA SOCIAL':
						$burbuja=6;
						break;
				}
				switch ($seccionaux){
					case 'Datos del Empleado':
						$aux=1;
						break;
					case 'REMUNERATIVOS':
						$aux=2;
						break;
					case 'NO REMUNERATIVOS':
						$aux=3;
						break;
					case 'APORTES':
						$aux=4;
						break;
					case 'TOTALES':
						$aux=5;
						break;
					case 'OBRA SOCIAL':
						$aux=6;
						break;
				}
				if($burbuja>$aux){
					$myaux=$empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$i];
					$empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$i]=$empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$j];
					$empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$j]=$myaux;
				}
			}
		}

		$this->set(compact('empleado','tipoliquidacion','tieneLiquidacion'));
		$this->autoRender=false;
		$this->layout = 'ajax';
		$this->render('papeldetrabajosueldos');
	}
	public function papeldetrabajolibrosueldo($empid=null,$periodo=null){

		$options = array(
			'contain'=>array(
				'Cliente'=>array(
					'Domicilio',
					'Actividadcliente'=>array(
						'Actividade'
					)
				),
				'Valorrecibo'=>array(
					'Cctxconcepto'=>array(
						'Concepto',
						'Conveniocolectivotrabajo'=>array(

						)
					),
					'conditions'=>array(
						'Valorrecibo.periodo'=>$periodo,
						'Valorrecibo.tipoliquidacion'=>array(1,2,3)
					)
				),
			),
			'conditions' => array('Empleado.id' => $empid)
		);
		$empleado = $this->Empleado->find('first', $options);
		$this->set('empleado',$empleado);
		$this->set(compact('empid','periodo'));
		$this->autoRender=false;
		$this->layout = 'ajax';
		$this->render('papeldetrabajolibrosueldo');
	}
/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Empleado->create();
			$respuesta = array('respuesta'=>'');
			$this->request->data('Empleado.fechaingreso',date('Y-m-d',strtotime($this->request->data['Empleado']['fechaingreso'])));
			if($this->request->data['Empleado']['fechaegreso']) {
				$this->request->data('Empleado.fechaegreso', date('Y-m-d', strtotime($this->request->data['Empleado']['fechaegreso'])));
			}
			if ($this->Empleado->save($this->request->data)) {
				$respuesta['data'] = $this->request->data;
				if(!isset($respuesta['data']['Empleado']['id'])||$respuesta['data']['Empleado']['id']==''){
					$respuesta['data']['Empleado']['id'] = $this->Empleado->getLastInsertID();
				}
				$respuesta['respuesta'] = 'Se ah guardado el empleado con exito';
			} else {
				$respuesta['error'] = '1';
				$respuesta['respuesta'] = 'NO se ah guardado el empleado con exito. Por favor intente de nuevo mas tarde.';
			}
		}
		$this->set('respuesta',$respuesta);
		$this->autoRender=false;
		$this->layout = 'ajax';
		$this->render('add');
		return;
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->loadModel('Domicilio');
		$this->loadModel('Conveniocolectivotrabajo');
		if (!$this->Empleado->exists($id)) {
			throw new NotFoundException(__('Invalid empleado'));
		}
		$options = array('conditions' => array('Empleado.' . $this->Empleado->primaryKey => $id));
		$this->request->data = $this->Empleado->find('first', $options);
		
		$this->set(compact('cliid'));

		$optionsDomic = array(
			'conditions' => array('Domicilio.cliente_id' => $this->request->data['Domicilio']['cliente_id'])
		);
		$domicilios = $this->Domicilio->find('list',$optionsDomic);
		$this->set('domicilios', $domicilios);

		$conveniocolectivotrabajos = $this->Conveniocolectivotrabajo->find('list');
		$this->set('conveniocolectivotrabajos', $conveniocolectivotrabajos);
		$this->autoRender=false;
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
		$id = substr($id,0, -5);
		$this->Empleado->id = $id;
		if (!$this->Empleado->exists()) {
			throw new NotFoundException(__('Invalid Empleado'));
		}
		$this->request->onlyAllow('post', 'delete');
		$data=array();
		if ($this->Subcliente->delete()) {
			$data['respuesta'] = "El Empleado ha a sido eliminado.";
			if($this->Empleado->Valorrecibo->deleteAll(array('Valorrecibo.empleado_id' => $id), false)){
				$data['respuesta'] .= " Se han eliminado los recibos guardados para este empleado";
			}
		}else {
			$data['respuesta'] = "El Empleado NO ha sido eliminado.Por favor intente de nuevo mas tarde.";
		}
		$this->set(compact('data'));
		$this->layout = 'ajax';
		$this->render('serializejson');
	}}
