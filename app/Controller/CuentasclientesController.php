<?php
App::uses('AppController', 'Controller');
/**
 * @property Cbus $Cbus
 * @property PaginatorComponent $Paginator
 */
class CuentasclientesController extends AppController {

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
	public function plancuentas($ClienteId = null) 
	{				
		$this->loadModel('Cliente');
		if($ClienteId==null){

			$containCli = array(
				'Grupocliente',
			);
			$clientes = $this->Cliente->find('list',array(
					'contain' =>$containCli,
					'conditions' => array(
						'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),
						'Cliente.estado' => 'habilitado' ,
						'Grupocliente.estado' => 'habilitado' ,
					),
					'order'=>array('Grupocliente.nombre','Cliente.nombre'),
					'fields'=>array('Cliente.id','Cliente.nombre','Grupocliente.nombre')
				)
			);
			$this->set('clientes',$clientes);
		}else{
			$options = array(
				'conditions' => array(
					'Cuentascliente.cliente_id'=> $ClienteId
				)
			);
			$cuentasclientes = $this->Cuentascliente->find('all', $options);
			$this->set('cuentasclientes',$cuentasclientes);


			$clienteOpc = array(
				'conditions' => array(
					'Cliente.id'=> $ClienteId
				),
				'recursive' => -1,
			);
			$cliente = $this->Cliente->find('first', $clienteOpc);
			$this->set('cliente',$cliente);
		}

	}
	public function GuardarDescripcion($CuentaClienteId, $Descripcion)
	{
		$this->Cuentascliente->id = $CuentaClienteId;
		if($this->Cuentascliente->saveField('nombre', $Descripcion))
		{
			$data['respuesta']='Descripcion modificada exitosamente.';
		}
		else
		{
			$data['respuesta']='Error al modificar la descripcion.';
		}
		$this->set('data',$data);
		$this->layout = 'ajax';
		$this->render('serializejson');	
	}
	public function informesumaysaldo($clienteid = null, $periodo = null){
		$this->loadModel('Cliente');
		$this->loadModel('Movimiento');
		$optionCliente = [
			'contain' => [
				'Cuentascliente'=>[
					'Cuenta',
					'Saldocuentacliente'=>[
						'conditions'=>[
							'Saldocuentacliente.periodo'=>$periodo
						],
					],
					'Movimiento',
					'conditions'=>[
					]
				],
			],
			'conditions' => ['Cliente.id'=>$clienteid]
		];
		$cliente = $this->Cliente->find('first',$optionCliente);
		for ($i=0;$i<count($cliente['Cuentascliente'])-1;$i++){
			for ($j=$i;$j<count($cliente['Cuentascliente']);$j++) {
				$burbuja = $cliente['Cuentascliente'][$i]['Cuenta']['numero'];
				$aux = $cliente['Cuentascliente'][$j]['Cuenta']['numero'];
				if($burbuja>$aux){
					$myaux=$cliente['Cuentascliente'][$i];
					$cliente['Cuentascliente'][$i]=$cliente['Cuentascliente'][$j];
					$cliente['Cuentascliente'][$j]=$myaux;
				}
			}
		}
		$this->set('cliente',$cliente);
		$this->set('periodo',$periodo);
	}

	public function activarcuentasdeimpuestos(){
		set_time_limit ( 1200 );
		$this->loadModel('Impcli');
		$this->loadModel('Cuenta');
		$this->loadModel('Cuentascliente');
		$optionsimpclis = [
			'contain'=>[]
		];
		$impclis=$this->Impcli->find('all',$optionsimpclis);
		$respuesta=[];
		foreach ($impclis as $impcli) {
			$id = $impcli['Impcli']['id'];
			$impuestoid = $impcli['Impcli']['impuesto_id'];
			$clienteid = $impcli['Impcli']['cliente_id'];
			$options = array(
				'contain'=>array(
					'Periodosactivo'=>array(
						'conditions' => array(
							'Periodosactivo.hasta' => null,
						),
					),
					'Impuesto'
				),
				'conditions' => array(
					'Impcli.' . $this->Impcli->primaryKey => $id
				)
			);
			$createdImp = $this->Impcli->find('first', $options);

			//Ahora aparte de crear el impuesto tenemos que relacionar las cuentas contables
			//relacionadas a cada Impuesto, vamos a preguntar que impuesto es y aca vamos a decir que cuentas hay q
			//dar de alta
			$cuentasImpuestoAActivar = [];
			$cuentasUnica1 = [];
			$cuentasUnica2 = [];
			$prenombres1 =[];
			$postnombres1 =[];
			$prenombres2 =[];
			$postnombres2 =[];
			switch ($impuestoid){
				case '19':/*IVA */
					$cuentasImpuestoAActivar = $this->Cuenta->cuentasdeIVA;
					break;
				case '6':/*Act. Varias*/
					$cuentasImpuestoAActivar = $this->Cuenta->cuentasdeActVarias;
					break;
				case '10':/*SUSS*/
					$cuentasImpuestoAActivar = $this->Cuenta->cuentasdeSUSS;
					break;
				case '21':/*Act. Economicas*/
					$cuentasImpuestoAActivar = $this->Cuenta->cuentasdeActEconomicas;
					break;
				/**Sindicatps**/
				case'24':/*INACAP*//*Aca solo los que tienen contribuciones*/
					//las contribuciones tienen una cuenta para el debe y otra para el haber
					$cuentasUnica1 = $this->Cuenta->cuentasdeSUSSContribucionesSindicatos;/*Contribuciones*/
					$prenombres1[0] = "Contribucion";
					$postnombres1[0] = "";
					$prenombres1[1] = "Contribucion";
					$postnombres1[1] = "A Pagar";

					break;
				case'11':/*SEC*//*Aca solo los que tienen seg vida obl y apórte*/
					$cuentasUnica1 = $this->Cuenta->cuentasdeSUSSContribucionesSindicatos;/*Contribuciones*/
					$prenombres1[0] = "Cont.Seg. De Vida Oblig. Mercantil";
					$postnombres1[0] = "";
					$prenombres1[1] = "Cont.Seg. De Vida Oblig. Mercantil";
					$postnombres1[1] = "A Pagar";

					$prenombres2[0] = "Aporte";
					$postnombres2[0] = "";
					$cuentasUnica2 = $this->Cuenta->cuentasdeSUSSAportesSindicatos;/*Aportes*/
					break;
				case'155':/*UOM*/
				case'25':/*UTHGRA*/
				case'41':/*UOCRA*//*Aca los que tienen contribucion y aporte*/
					$cuentasUnica1 = $this->Cuenta->cuentasdeSUSSContribucionesSindicatos;/*Contribuciones*/
					$prenombres1[0] = "Contribucion";
					$postnombres1[0] = "";
					$prenombres1[1] = "Contribucion";
					$postnombres1[1] = "A Pagar";

					$prenombres2[0] = "Aporte";
					$postnombres2[0] = "";
					$cuentasUnica2 = $this->Cuenta->cuentasdeSUSSAportesSindicatos;/*Aportes*/
					break;
				case'178':/*ACARA*/
				case'179':/*AOMA*/
				case'23':/*FAECYS*/
				case'153':/*IERIC*/
				case'180':/*Renatea*/
				case'177':/*SMATA*/
				case'176':/*Turismo*/
				case'26':/*UATRE*/
				case'42':/*UTA*//*Aca solo los que tienen aportes*/
					$prenombres2[0] = "Aporte";
					$postnombres2[0] = "";
					$cuentasUnica2 = $this->Cuenta->cuentasdeSUSSAportesSindicatos;/*Aportes*/
					//$cuentasUnica2 = $this->Cuenta->cuentasdeSUSSAportesSindicatos;/*Contribuciones*/
					break;
				/*Fin Sindicatos*/
				default:
					//Si es sindicato vamos a crear una cuenta ed aporte y conyt
					break;
			}
			foreach ($cuentasImpuestoAActivar as $cuentaactivable){
				$conditionsCuentascliente = array(
					'Cuentascliente.cliente_id' => $clienteid,
					'Cuentascliente.cuenta_id' => $cuentaactivable
				);
				if (!$this->Cuentascliente->hasAny($conditionsCuentascliente)){
					/*Ahora si estamos seguro de que esta cuenta no esta activada y podemos activarla
                    para este cliente y relacionarla al CBU*/
					$conditionsCuentas=[
						'conditions'=>['Cuenta.id'=>$cuentaactivable]
					];
					$cuentaACargar = $this->Cuenta->find('first', $conditionsCuentas);
					$nombreCuentaClie = $cuentaACargar['Cuenta']['nombre'];
					$this->Cuentascliente->create();
					$this->Cuentascliente->set('cliente_id',$clienteid);
					$this->Cuentascliente->set('cuenta_id',$cuentaactivable);
					$this->Cuentascliente->set('nombre',$nombreCuentaClie);

					if ($this->Cuentascliente->save())
					{
						$respuesta[]="se guardo la cuenta Cliente ".$clienteid.
							" Cuenta ".$cuentaactivable." Nombre ".$nombreCuentaClie;
					}
					else
					{
						$respuesta[]="NO se guardo la cuenta Cliente ".$clienteid.
							" Cuenta ".$cuentaactivable." Nombre ".$nombreCuentaClie;
					}
				}
			}
			$numnombre = 0;
			foreach ($cuentasUnica1 as $cuentaactivableunica1){
				/*vamos a activar una cuenta por cada prenombre/postnombre disponible*/
				$conditionsCuentascliente = array(
					'Cuentascliente.cliente_id' => $clienteid,
					'Cuentascliente.cuenta_id' => $cuentaactivableunica1
				);
				if (!$this->Cuentascliente->hasAny($conditionsCuentascliente)){
					/*Ahora si estamos seguro de que esta cuenta no esta activada y podemos activarla
                    para este cliente y relacionarla al Impuesto*/
//                            $conditionsCuentas=[
//                                'conditions'=>['Cuenta.id'=>$cuentaactivableunica1]
//                            ];
//                            $cuentaACargar = $this->Cuenta->find('first', $conditionsCuentas);
					$nombreCuentaClie = $prenombres1[$numnombre]."-".
						$createdImp['Impuesto']['nombre']."-".$postnombres1[$numnombre];
					$this->Cuentascliente->create();
					$this->Cuentascliente->set('cliente_id',$clienteid);
					$this->Cuentascliente->set('cuenta_id',$cuentaactivableunica1);
					$this->Cuentascliente->set('nombre',$nombreCuentaClie);
					if ($this->Cuentascliente->save())
					{
						$respuesta[]="se guardo la cuenta Cliente ".$clienteid.
							" Cuenta ".$cuentaactivableunica1." Nombre ".$nombreCuentaClie;
					}
					else
					{
						$respuesta[]="se guardo la cuenta Cliente ".$clienteid.
							" Cuenta ".$cuentaactivableunica1." Nombre ".$nombreCuentaClie;
					}
					$numnombre++;
					if($numnombre>=count($prenombres1)){
						break;//este break me garantiza que se cree solo 1 de estas cuentas relacionadas al impuesto
						//Ahora cuando haya prenombres voy a preguntar si ya use todos ya hihago el break;
					}
				}
			}
			$numnombre=0;
			foreach ($cuentasUnica2 as $cuentaactivableunica2){
				$conditionsCuentascliente = array(
					'Cuentascliente.cliente_id' => $clienteid,
					'Cuentascliente.cuenta_id' => $cuentaactivableunica2
				);
				if (!$this->Cuentascliente->hasAny($conditionsCuentascliente)){
					/*Ahora si estamos seguro de que esta cuenta no esta activada y podemos activarla
                    para este cliente y relacionarla al CBU*/
//                            $conditionsCuentas=[
//                                'conditions'=>['Cuenta.id'=>$cuentaactivableunica2]
//                            ];
					$nombreCuentaClie = $prenombres2[$numnombre]."-".
						$createdImp['Impuesto']['nombre']."-".$postnombres2[$numnombre];
					$this->Cuentascliente->create();
					$this->Cuentascliente->set('cliente_id',$clienteid);
					$this->Cuentascliente->set('cuenta_id',$cuentaactivableunica2);
					$this->Cuentascliente->set('nombre',$nombreCuentaClie);
					if ($this->Cuentascliente->save())
					{
						$respuesta[]="se guardo la cuenta Cliente ".$clienteid.
							" Cuenta ".$cuentaactivableunica2." Nombre ".$nombreCuentaClie;
					}
					else
					{
						$respuesta[]="NO se guardo la cuenta Cliente ".$clienteid.
							" Cuenta ".$cuentaactivableunica2." Nombre ".$nombreCuentaClie;
					}
					$numnombre++;
					if($numnombre>=count($prenombres2)){
						break;//este break me garantiza que se cree solo 1 de estas cuentas relacionadas al impuesto
						//Ahora cuando haya prenombres voy a preguntar si ya use todos ya hihago el break;
					}

				}
			}
		}

		$this->set('data',$respuesta);
		$this->layout = 'ajax';
		$this->render('serializejson');
	}
	public function activarcuentasdecbus(){




		/*Antes de guardar el CBU vamos a analizar una lista de cuentas
            que se pueden activar como cuentascliente para que esta cuenta del banco
            tenga su propia cuenta cliente*/
		$cuentasDeBancoActivables = $this->Cuenta->cuentasDeBancoActivables;
		$optionsImpcli = [
			'contain'=>[
				'Impuesto'
			],
			'conditions'=>[
				'Impcli.id' => $this->request->data['Cbu']['impcli_id']
			]
		];
		$impcli = $this->Impcli->find('first',$optionsImpcli);
		$CuentaClienteNuevaId = 0;
		foreach ($cuentasDeBancoActivables as $cuentaactivable){
			$conditionsCuentascliente = array(
				'Cuentascliente.cliente_id' => $impcli['Impcli']['cliente_id'],
				'Cuentascliente.cuenta_id' => $cuentaactivable
			);
			if (!$this->Cuentascliente->hasAny($conditionsCuentascliente)){
				/*Ahora si estamos seguro de que esta cuenta no esta activada y podemos activarla
                para este cliente y relacionarla al CBU*/
				$nombreCuentaClie = "Banco ".$impcli['Impuesto']['nombre']." - Cuenta ".$this->request->data['Cbu']['numerocuenta'];
				$this->Cuentascliente->create();
				$this->Cuentascliente->set('cliente_id',$impcli['Impcli']['cliente_id']);
				$this->Cuentascliente->set('cuenta_id',$cuentaactivable);
				$this->Cuentascliente->set('nombre',$nombreCuentaClie);
				if ($this->Cuentascliente->save())
				{
					$respuesta['respuesta'].='Cuenta de banco activada correctamente:'.$nombreCuentaClie."</br>";
				}
				else
				{
					$respuesta['respuesta'].='Error al guardar cuenta de banco. Por favor intente nuevamente:'.$nombreCuentaClie."</br>";
				}
				$CuentaClienteNuevaId = $this->Cuentascliente->getLastInsertId();
				$this->request->data['Cbu']['cuentascliente_id'] = $CuentaClienteNuevaId;
				break;
			}
		}
		/*Aparte de dar de alta la cuentacliente del banco tenemos que dar de alta las cuentascliente
               a las que se va a relacionar los movimientos de estas cuentas*/
		$cuentasDeMovimientoBancario = $this->Cuenta->cuentasDeMovimientoBancarioAActivar;
		foreach ($cuentasDeMovimientoBancario as $cuentaMovimientoB){
			$conditionsCuentasclienteMovBan = array(
				'Cuentascliente.cliente_id' => $impcli['Impcli']['cliente_id'],
				'Cuentascliente.cuenta_id' => $cuentaMovimientoB
			);
			if (!$this->Cuentascliente->hasAny($conditionsCuentasclienteMovBan)){
				$conditionsCuentas=[
					'conditions'=>['Cuenta.id'=>$cuentaMovimientoB]
				];
				$cuentaACargar = $this->Cuenta->find('first', $conditionsCuentas);
				$nombreCuentaMovb = $cuentaACargar['Cuenta']['nombre'];

				$this->Cuentascliente->create();
				$this->Cuentascliente->set('cliente_id',$impcli['Impcli']['cliente_id']);
				$this->Cuentascliente->set('cuenta_id',$cuentaMovimientoB);
				$this->Cuentascliente->set('nombre',$nombreCuentaMovb);
				if ($this->Cuentascliente->save())
				{
					$respuesta['respuesta'].='Cuenta de movimiento activada correctamente.'."</br>";
				}
				else
				{
					$respuesta['respuesta'].='Error al guardar cuenta de movimiento. Por favor intente nuevamente.'."</br>";
				}
				//NO TIENE BREAKE POR QUE SE TIENEN QUE RELACIONAR TODAS ESTAS CUENTAS
			}
		}
		$cuentasComisionGastosInteresesOtros = $this->Cuenta->cuentasComisionGastosInteresesOtros;
		$cantCreadas=0;
		foreach ($cuentasComisionGastosInteresesOtros as $cuentasDeComisionInteresGasto){

			$conditionsCuentasDeComisionInteresGasto = array(
				'Cuentascliente.cliente_id' => $impcli['Impcli']['cliente_id'],
				'Cuentascliente.cuenta_id' => $cuentasDeComisionInteresGasto
			);
			if (!$this->Cuentascliente->hasAny($conditionsCuentasDeComisionInteresGasto)){
				$conditionsCuentas=[
					'conditions'=>['Cuenta.id'=>$cuentasDeComisionInteresGasto]
				];
				$cuentaACargar = $this->Cuenta->find('first', $conditionsCuentas);
				$nombreCuentaCom = "Banco ".$impcli['Impuesto']['nombre']
					." - Cuenta ".$this->request->data['Cbu']['numerocuenta']
					."-".$cuentaACargar['Cuenta']['nombre'];

				$this->Cuentascliente->create();
				$this->Cuentascliente->set('cliente_id',$impcli['Impcli']['cliente_id']);
				$this->Cuentascliente->set('cuenta_id',$cuentasDeComisionInteresGasto);
				$this->Cuentascliente->set('nombre',$nombreCuentaCom);
				if ($this->Cuentascliente->save())
				{
					$respuesta['respuesta'].='Cuenta de movimiento activada correctamente.'."</br>";
				}
				else
				{
					$respuesta['respuesta'].='Error al guardar cuenta de movimiento. Por favor intente nuevamente.'."</br>";
				}
				//Aca se tienen q relacionar solamente 5 por banco y en el orden en las que trae el array
				if($cantCreadas<4){
					$cantCreadas++;
				}else{
					break;
				}
			}
		}
	}
}
