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

		$cuentas4taCategRelacionGravadoNoGravado = [
			//'Gravado' Apunta a 'Nogravado'
			'3023'=>'3072',
			'3024'=>'3073',
			'3025'=>'3074',
			'3026'=>'3075',
			'3027'=>'3076',
			'3029'=>'3078',
			'3030'=>'3079',
			'3031'=>'3080',
			'3032'=>'3081',
			'3033'=>'3082',
			'3035'=>'3084',
			'3036'=>'3085',
			'3037'=>'3086',
			'3038'=>'3087',
			'3039'=>'3088',
			'3041'=>'3090',
			'3042'=>'3091',
			'3043'=>'3092',
			'3044'=>'3093',
			'3045'=>'3094',
			'3047'=>'3096',
			'3048'=>'3097',
			'3049'=>'3098',
			'3050'=>'3099',
			'3051'=>'3100',
			'3053'=>'3102',
			'3054'=>'3103',
			'3055'=>'3104',
			'3056'=>'3105',
			'3057'=>'3106',
			'3059'=>'3110',
			'3060'=>'3108',
			'3061'=>'3109',
			'3062'=>'3111',
			'3063'=>'3112',
			'3065'=>'3114',
			'3066'=>'3115',
			'3067'=>'3116',
			'3068'=>'3117',
			'3069'=>'3118',
		];

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
						//Si estamos en la 4ta categoria hay que relacionar tambien las cuentas que estan en el mismo tipo
						//pero siendo NO GRAVADOS EN IVA
						//para q el asiento pueda darse cuenta de q tiene no gravados, levantarlos y cargarlos
						//Vamos a hacer un array relacionando las cuentas
						if(array_key_exists ( $cuentaganancia['cuenta_id'] , $cuentas4taCategRelacionGravadoNoGravado )){
							$conditionsCuentasclienteAsociada = array(
								'Cuentascliente.cliente_id' => $cuentaganancia['cliente_id'],
								'Cuentascliente.cuenta_id' => $cuentas4taCategRelacionGravadoNoGravado['cuenta_id']
							);
							if (!$this->Cuentascliente->hasAny($conditionsCuentasclienteAsociada)) {
								/*Ahora si estamos seguro de que esta cuenta no esta activada y podemos activarla
                                para este cliente y relacionarla al CBU*/
								$conditionsCuentasAsociada = [
									'conditions' => ['Cuenta.id' => $cuentas4taCategRelacionGravadoNoGravado['cuenta_id']]
								];
								$cuentaACargarAsociada = $this->Cuenta->find('first', $conditionsCuentasAsociada);
								$nombreCuentaClieAsociada = $cuentaACargarAsociada['Cuenta']['nombre'];
								$this->Cuentascliente->create();
								$this->Cuentascliente->set('cliente_id', $cuentaganancia['cliente_id']);
								$this->Cuentascliente->set('cuenta_id', $cuentas4taCategRelacionGravadoNoGravado['cuenta_id']);
								$this->Cuentascliente->set('nombre', $nombreCuentaClieAsociada);
							}
						}

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
		//Si es persona fisica puede tener las 3 categorias si es persona juridica solo la 3ra
		$categorias=[];

		if($cliente['Cliente']['tipopersona']=='fisica'){
			$categorias = [
				'primeracateg'=>'primera',
				'segundacateg'=>'segunda',
				'terceracateg'=>'tercera empresas',
				'terceracateg45'=>'tercera otros',
				'cuartacateg'=>'cuarta'
			];
		}else{
			$categorias = [
				'terceracateg'=>'tercera empresas',
			];
		}
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

		//Aca no tendria que traer todo el asiento estandar por que solo la cuenta de "venta neta" es la que
		//se deberia poder  seleccionar
		$cuentascategoriatercera =	$this->Asientoestandare->find('list',$optionsAsientosestandares);
		$optionsAsientosestandares['conditions']['tipoasiento'] = 'terceracateg45';

		$cuentascategoriaterceraotros =	$this->Asientoestandare->find('list',$optionsAsientosestandares);
		
		$optionsAsientosestandares['conditions']['tipoasiento'] = 'cuartacateg';
		$cuentascategoriacuarta =	$this->Asientoestandare->find('list',$optionsAsientosestandares);

		$this->set(compact('cliente','categorias','cuentascategoriaprimera','cuentascategoriasegunda',
			'cuentascategoriatercera','cuentascategoriaterceraotros','cuentascategoriacuarta'));
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
