<?php
App::uses('AppController', 'Controller');
/**
 * Anexogastos Controller
 *
 * @property Anexogasto $Anexogasto
 * @property PaginatorComponent $Paginator
 */
class AnexogastosController extends AppController {
/**
 * Components
 *
 * @var array
 */
    //este array es el que se va a usar para relacionar las cuentas con los impuestos
    //no se si tendriamos que guardarlo en una tabla pero por ahora va a servir

    public $components = array('Paginator');
    public function add($ClienteId = null,$periodo = null,$cuentacliente = null)
    {
        $this->loadModel('Cliente');
        $this->loadModel('Cuentascliente');

        $pemes = substr($periodo, 0, 2);
        $peanio = substr($periodo, 3);
        $respuesta=[];
        $respuesta['error'] = '0';
        $respuesta['respuesta'] = '';

        if ($this->request->is('post')) {
            $sumaDistribucion = $this->request->data['Anexogasto']['porcentajeventa']*1;
            $sumaDistribucion += $this->request->data['Anexogasto']['porcentajecomercializacion']*1;
            $sumaDistribucion += $this->request->data['Anexogasto']['porcentajeadministracion']*1;
            if($sumaDistribucion==1){
                if ($this->Anexogasto->save($this->request->data)) {
                    $respuesta['anexogasto'] = $this->request->data;
                    if(!isset($respuesta['anexogasto']['Anexogasto']['id'])||$respuesta['anexogasto']['Anexogasto']['id']==''){
                            $respuesta['anexogasto']['Anexogasto']['id'] = $this->Anexogasto->getLastInsertID();
                    }
                    $respuesta['respuesta'] = 'Se ha guardado la distribucion de la cuenta con exito';
                } else {
                    $respuesta['error'] = '1';
                    $respuesta['respuesta'] = 'NO se ha guardado la distribucion de la cuenta. Por favor intente de nuevo mas tarde.';
                }
            }else{
                $respuesta['error'] = '1';
                $respuesta['respuesta'] = 'La distibucion no es igual a 1 por favor reintente.';
            }
            $this->set('data',$respuesta);
            $this->autoRender=false;
            $this->layout = 'ajax';
            $this->render('serializejson');
            return;
        }
                
        $clienteOpc = array(
            'conditions' => array(
                'Cliente.id'=> $ClienteId
            ),
            'contain' => [],
        );
        $cliente = $this->Cliente->find('first', $clienteOpc);
        $this->set('cliente',$cliente);
        $this->set('periodo',$periodo);

        $fechadeconsulta = date('Y/m/d',strtotime("01-".$pemes."-".$peanio));

        if(!isset($cliente['Cliente']['fchcorteejerciciofiscal'])||is_null($cliente['Cliente']['fchcorteejerciciofiscal'])||$cliente['Cliente']['fchcorteejerciciofiscal']==""){
            $this->Session->setFlash(__('No se ha configurado fecha decorte de ejercicio fiscal para este
			 ccontribuyente .'));
            $fechadecorteAñoActual = date('Y/m/d',strtotime("01-01-".$peanio));

        }else{
            $fechadecorteAñoActual = date('Y/m/d',strtotime($cliente['Cliente']['fchcorteejerciciofiscal']."-".$peanio));
        }
        $fechaInicioConsulta = "";
        $fechaFinConsulta = "";
        if($fechadeconsulta<$fechadecorteAñoActual){
            $fechaInicioConsulta =  date('Y/m/d',strtotime($fechadecorteAñoActual." - 1 Years + 1 days"));
        //			$fechaFinConsulta =  $fechadecorteAñoActual;
        }else {
            $fechaInicioConsulta = date('Y/m/d', strtotime($fechadecorteAñoActual . " + 1 days"));;
        //			$fechaFinConsulta = date('Y/m/d', strtotime($fechadecorteAñoActual . " + 1 Years"));
        }
        //la fecha fin consulta es esta por quesolo vamos a ver hasta el ultimo dia del periodo que estamos
        // consultando
        $fechaFinConsulta =  date('Y/m/t',strtotime($fechadeconsulta));
        $this->set('fechaInicioConsulta',$fechaInicioConsulta);
        $this->set('fechaFinConsulta',$fechaFinConsulta);
        
        //Ahora vamos a buscar los asientos que tengas movimientos que impacten en esta cuenta cliente
        //primero vamos a buscar los movimientos que tengan esta cuenta cliente
        //y vamos a recojer los id's de los asientos esos

        $this->layout = 'ajax';

        $optionsCuentacliente = [
            'contain'=>[
                'Cuenta',
                'Anexogasto'=>[
                    'conditions'=>[
                        'Anexogasto.periodo'=>$periodo
                    ]
                ]
            ],
            'conditions'=>[
                'Cuentascliente.id'=>$cuentacliente,
            ]
        ];
        $cuentasclienteseleccionada = $this->Cuentascliente->find('first',$optionsCuentacliente);
        $this->set('cuentasclienteseleccionada',$cuentasclienteseleccionada);
        
        if(count($cuentasclienteseleccionada['Anexogasto'])>0){
            $optionsAnexogasto = [
                'contain'=>[
                ],
                'conditions'=>[
                    'Anexogasto.periodo'=>$periodo,
                    'Anexogasto.cuentascliente_id'=>$cuentacliente
                ]
            ];
            $this->request->data = $this->Anexogasto->find('first',$optionsAnexogasto);
        }        
        if($this->request->is('ajax')){
            $this->set('isajaxrequest',1);
        }else{
            $this->set('isajaxrequest',0);
        }
    }
}