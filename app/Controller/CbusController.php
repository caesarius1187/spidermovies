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
                'contain'=>['Impuesto'],
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
                        $data['respuesta']='Cuenta de banco activada correctamente.';
                    }
                    else
                    {
                        $data['respuesta']='Error al guardar cuenta de banco. Por favor intente nuevamente.';
                    }
                    $CuentaClienteNuevaId = $this->Cuentascliente->getLastInsertId();
                    $this->request->data['Cbu']['cuentascliente_id'] = $CuentaClienteNuevaId;
                    break;
                }
            }
            /*Aparte de dar de alta la cuentacliente del banco tenemos que dar de alta las cuentascliente
                   a las que se va a relacionar los movimientos de estas cuentas*/
            $cuentasDeMovimientoBancario = $this->Cuenta->cuentasDeMovimientoBancario;
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
                    $nombreCuentaMovb = "Banco ".$impcli['Impuesto']['nombre']." - Cuenta ".$this->request->data['Cbu']['numerocuenta'];
                    $nombreCuentaMovb += " ".$cuentaACargar['Cuenta']['nombre'];

                    $this->Cuentascliente->create();
                    $this->Cuentascliente->set('cliente_id',$impcli['Impcli']['cliente_id']);
                    $this->Cuentascliente->set('cuenta_id',$cuentaMovimientoB);
                    $this->Cuentascliente->set('nombre',$nombreCuentaMovb);
                    if ($this->Cuentascliente->save())
                    {
                        $data['respuesta'].='Cuenta de movimiento activada correctamente.';
                    }
                    else
                    {
                        $data['respuesta'].='Error al guardar cuenta de movimiento. Por favor intente nuevamente.';
                    }
                    //NO TIENE BREAKE POR QUE SE TIENEN QUE RELACIONAR TODAS ESTAS CUENTAS
                }
            }
//            $cuentasAcreditacionTransferencia = ['1','2','3','4','5','7','8','9','10','11','12','13'];
//            //estas cuentas son individualizadas por cada CBU para relacionar Acreditaciones
//            foreach ($cuentasAcreditacionTransferencia as $cuentasAcreditacion){
//                $conditionsCuentascliente = array(
//                    'Cuentascliente.cliente_id' => $impcli['Impcli']['cliente_id'],
//                    'Cuentascliente.cuenta_id' => $cuentasAcreditacion
//                );
//                if (!$this->Cuentascliente->hasAny($conditionsCuentascliente)){
//                    /*Ahora si estamos seguro de que esta cuenta no esta activada y podemos activarla
//                    para este cliente y relacionarla al CBU*/
//                    $conditionsCuentas=[
//                        'conditions'=>['Cuenta.id'=>$cuentasAcreditacion]
//                    ];
//                    $cuentaACargar = $this->Cuenta->find('first', $conditionsCuentas);
//                    $nombreCuentaMovAc = "Banco ".$impcli['Impuesto']['nombre']." - Cuenta ".$this->request->data['Cbu']['numerocuenta'];
//                    $nombreCuentaMovAc += " ".$cuentaACargar['Cuenta']['nombre'];
//
//                    $this->Cuentascliente->create();
//                    $this->Cuentascliente->set('cliente_id',$impcli['Impcli']['cliente_id']);
//                    $this->Cuentascliente->set('cuenta_id',$cuentasAcreditacion);
//                    $this->Cuentascliente->set('nombre',$nombreCuentaMovAc);
//                    if ($this->Cuentascliente->save())
//                    {
//                        $data['respuesta']='Cuenta de Acreditacion activada correctamente.';
//                    }
//                    else
//                    {
//                        $data['respuesta']='Error al guardar cuenta de Acreditacion. Por favor intente nuevamente.';
//                    }
//                    $CuentaClienteNuevaId = $this->Cuentascliente->getLastInsertId();
//                    $this->request->data['Cbu']['cuentascliente_id'] = $CuentaClienteNuevaId;
//                    break;
//                }
//            }
//            $cuentasExtraccionTransferencia = ['1','2','3','4','5','7','8','9','10','11','12','13'];
//            //estas cuentas son individualizadas por cada CBU para relacionar Extracciones
//            foreach ($cuentasExtraccionTransferencia as $cuentasExtraccion){
//                $conditionsCuentascliente = array(
//                    'Cuentascliente.cliente_id' => $impcli['Impcli']['cliente_id'],
//                    'Cuentascliente.cuenta_id' => $cuentasExtraccion
//                );
//                if (!$this->Cuentascliente->hasAny($conditionsCuentascliente)){
//                    /*Ahora si estamos seguro de que esta cuenta no esta activada y podemos activarla
//                    para este cliente y relacionarla al CBU*/
//                    $conditionsCuentas=[
//                        'conditions'=>['Cuenta.id'=>$cuentasExtraccion]
//                    ];
//                    $cuentaACargar = $this->Cuenta->find('first', $conditionsCuentas);
//                    $nombreCuentaMovEx = "Banco ".$impcli['Impuesto']['nombre']." - Cuenta ".$this->request->data['Cbu']['numerocuenta'];
//                    $nombreCuentaMovEx += " ".$cuentaACargar['Cuenta']['nombre'];
//
//                    $this->Cuentascliente->create();
//                    $this->Cuentascliente->set('cliente_id',$impcli['Impcli']['cliente_id']);
//                    $this->Cuentascliente->set('cuenta_id',$cuentasExtraccion);
//                    $this->Cuentascliente->set('nombre',$nombreCuentaMovEx);
//                    if ($this->Cuentascliente->save())
//                    {
//                        $data['respuesta']='Cuenta de Extraccion activada correctamente.';
//                    }
//                    else
//                    {
//                        $data['respuesta']='Error al guardar cuenta de Extraccion. Por favor intente nuevamente.';
//                    }
//                    $CuentaClienteNuevaId = $this->Cuentascliente->getLastInsertId();
//                    $this->request->data['Cbu']['cuentascliente_id'] = $CuentaClienteNuevaId;
//                    break;
//                }
//            }

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

}