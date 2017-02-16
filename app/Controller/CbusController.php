<?php
App::uses('AppController', 'Controller');
/**
 * Eventosimpuestos Controller
 *
 * @property Cbus $Asiento
 * @property PaginatorComponent $Paginator
 */
class CbusController extends AppController {
/**
 * Components
 *
 * @var array
 */
    //este array es el que se va a usar para relacionar las cuentas con los impuestos
    //no se si tendriamos que guardarlo en una tabla pero por ahora va a servir

	public $components = array('Paginator');
    public function add(){
        $this->loadModel('Cbu');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Impcli');
        $this->loadModel('Cuenta');
        $resp ="";
        $this->autoRender=false;
        $respuesta=[];
        if ($this->request->is('post')) {
            $respuesta['respuesta']="";
            $this->Cbu->create();
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
                    $nombreCuentaClie = "Banco ".$impcli['Impuesto']['nombre']." - Cuenta ".$this->request->data['Cbu']['numerocuenta']." ".$this->request->data['Cbu']['tipocuenta'];
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
                        ." - Cuenta ".$this->request->data['Cbu']['numerocuenta']." ".$this->request->data['Cbu']['tipocuenta']
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

            /*FIN relacion de cuentas para asientos de movimientos bancarios*/
            if ($this->Cbu->save($this->request->data))
            {
                $respuesta['respuesta']="El CBU se ha guardado correctamente.";
            }
            else
            {
                $respuesta['respuesta']="El CBU se NO ha guardado correctamente. Por favor intentelo nuevamente mas tarde.";
            }
        }	else {
            $respuesta['respuesta']="Error. No se recibieron los datos.";
        }
        $respuesta['data']=$this->request->data;
        $this->set('data',$respuesta);
        $this->autoRender=false;
        $this->layout = 'ajax';
        $this->render('serializejson');
        return;
    }
    public function index($impcliid = null){
        $this->loadModel('Impcli');
        $this->loadModel('Cbu');
        $optionsCbu = [
            'contain'=>['Cuentascliente'=>['Cuenta']],
            'conditions'=>[
              'Cbu.impcli_id'=>$impcliid
          ]
        ];
        $cbus = $this->Cbu->find('all',$optionsCbu);
        $optionsImpcli = [
            'contain'=>['Impuesto'],
            'conditions'=>[
                'Impcli.id'=>$impcliid
            ]
        ];
        $impcli = $this->Impcli->find('first',$optionsImpcli);
        $this->set(compact('cbus','impcli'));
        $this->layout = 'ajax';
    }
    public function edit($cbuid = null,$impcliid = null){
        $this->loadModel('Cbu');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Impcli');
        $this->loadModel('Cuenta');
        $resp ="";
        $this->autoRender=false;
        $respuesta=[];
        if ($this->request->is('post')) {
            $respuesta['respuesta']="";
            $this->Cbu->create();



            if ($this->Cbu->save($this->request->data))
            {
                $respuesta['respuesta']="El CBU se ha modificado correctamente.";
            }
            else
            {
                $respuesta['respuesta']="El CBU se NO ha modificado correctamente. Por favor intentelo nuevamente mas tarde.";
            }
            $respuesta['data']=$this->request->data;
            $this->set('data',$respuesta);
            $this->autoRender=false;
            $this->layout = 'ajax';
            $this->render('serializejson');
            return;
        }	else {
            $optionsImpcli = [
                'contain'=>[
                    'Impuesto'
                ],
                'conditions'=>[
                    'Impcli.id' => $impcliid
                ]
            ];
            $impcli = $this->Impcli->find('first',$optionsImpcli);

            $this->request->data = $this->Cbu->find('first',[
                'conditions'=>[
                    'Cbu.id'=>$cbuid
                ]
            ]);
            $respuesta['respuesta']="Error. No se recibieron los datos.";
            $respuesta['data']=$this->request->data;
            $this->set('data',$respuesta);
            $this->set('impcli',$impcli);

            //Ahora vamosa  buscar las cuentas clientes de este CBU
            $optionsCuentascliente=[
                'conditions'=>[
                    'Cuentascliente.cliente_id'=>$impcli['Impcli']['cliente_id'],
                    'Cuentascliente.cuenta_id'=>$this->Cuenta->cuentasDeBancoActivables
                ]
            ];
            $cuentasclientes = $this->Cuentascliente->find('list',$optionsCuentascliente);
            $this->set('cuentasclientes',$cuentasclientes);


            $this->autoRender=false;
            $this->layout = 'ajax';
            $this->render('edit');
            return;
        }

    }

}
