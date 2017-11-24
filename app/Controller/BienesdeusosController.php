<?php
App::uses('AppController', 'Controller');
/**
 * Bienesdeusos Controller
 *
 * @property Bienesdeuso $Bienesdeuso
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class BienesdeusosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Bienesdeuso->recursive = 0;
		$this->set('bienesdeusos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Bienesdeuso->exists($id)) {
			throw new NotFoundException(__('Invalid bienesdeuso'));
		}
		$options = array('conditions' => array('Bienesdeuso.' . $this->Bienesdeuso->primaryKey => $id));
		$this->set('bienesdeuso', $this->Bienesdeuso->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add($cliid = null,$biendeusiid = null,$compraid = null) {
        $this->loadModel('Cliente');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Cuenta');

        $compra=[];
        if($compraid!=null){
            //puede ser que compraid sea null por que estamos cargando desde clientes/view
            $optioncompra = [
                'contain'=>[
                    'Actividadcliente'=>[
                        'Cuentasganancia'
                    ],
                    'Bienesdeuso',
                ],
                'conditions'=>[
                    'Compra.id'=>$compraid
                ]
            ];
            $compra = $this->Bienesdeuso->Compra->find('first',$optioncompra);
        }
        $optioncliente = [
            'contain'=>array(
            ),
            'conditions'=>[
                'Cliente.id'=>$cliid
            ]
        ];
        $cliente = $this->Cliente->find('first',$optioncliente);

		if ($this->request->is('post')||$this->request->is('put')) {
			$this->Bienesdeuso->create();
			if($this->request->data['Bienesdeuso']['fechaadquisicion']!=""){
				$this->request->data('Bienesdeuso.fechaadquisicion',date('Y-m-d',strtotime($this->request->data['Bienesdeuso']['fechaadquisicion'])));
			}
            $cliid = $this->request->data['Bienesdeuso']['cliente_id'];
            //hay que dar de alta la cuenta de bien de uso reemplazando XX
            //vamos a armar el nombre del Bien de uso
            $nombreBDU = " ";
            $nombreCuentaActivable = "";
            switch ($this->request->data['Bienesdeuso']['tipo']){
                //Empresa
                case 'Rodado':
                    if($this->request->data['Bienesdeuso']['patente']!="")
                        $nombreBDU  .= $this->request->data['Bienesdeuso']['patente'];
                    if($this->request->data['Bienesdeuso']['aniofabricacion']!="")
                        $nombreBDU  .= " -".$this->request->data['Bienesdeuso']['aniofabricacion'];
                    $prefijo = "120602";
                    if(
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==0||
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==null||
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==""
                    ){
                        $cuentasRodadoValorOrigen=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasRodadoValorOrigen,$nombreBDU );
                    }else{
                        $cuentasRodadoValorOrigen=$this->request->data['Bienesdeuso']['cuentaclientevalororigen_id'];
                    }
                    if(
                        $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']==0||
                        $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']==null||
                        $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']==""
                    )   {
                        $cuentasRodadoActualizacion=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasRodadoActualizacion,$nombreBDU );
                    }else{
                        $cuentasRodadoActualizacion=$this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id'];
                    }
                    $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']=$cuentasRodadoValorOrigen;
                    $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']=$cuentasRodadoActualizacion;
                    break;
                case 'Inmueble':
                    if($this->request->data['Bienesdeuso']['calle']!="")
                        $nombreBDU  .= $this->request->data['Bienesdeuso']['calle'];
                    if($this->request->data['Bienesdeuso']['numero']!="")
                        $nombreBDU  .= " -".$this->request->data['Bienesdeuso']['numero'];
                    $prefijo = "120601";
                    if(
                        $this->request->data['Bienesdeuso']['cuentaclienteterreno_id']==0||
                        $this->request->data['Bienesdeuso']['cuentaclienteterreno_id']==null||
                        $this->request->data['Bienesdeuso']['cuentaclienteterreno_id']==""
                    ){
                        $cuentasInmuebleTerreno=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasInmuebleTerreno,$nombreBDU );
                    }else{
                        $cuentasInmuebleTerreno=$this->request->data['Bienesdeuso']['cuentaclienteterreno_id'];
                    }
                    if(
                        $this->request->data['Bienesdeuso']['cuentaclienteedificacion_id']==0||
                        $this->request->data['Bienesdeuso']['cuentaclienteedificacion_id']==null||
                        $this->request->data['Bienesdeuso']['cuentaclienteedificacion_id']==""
                    ){
                        $cuentasInmuebleEdif=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasInmuebleEdif,$nombreBDU );
                    }else{
                        $cuentasInmuebleEdif=$this->request->data['Bienesdeuso']['cuentaclienteedificacion_id'];
                    }
                    if(
                        $this->request->data['Bienesdeuso']['cuentaclientemejora_id']==0||
                        $this->request->data['Bienesdeuso']['cuentaclientemejora_id']==null||
                        $this->request->data['Bienesdeuso']['cuentaclientemejora_id']==""
                    ){
                        $cuentasInmuebleMejora=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasInmuebleMejora,$nombreBDU );
                    }else{
                        $cuentasInmuebleMejora=$this->request->data['Bienesdeuso']['cuentaclientemejora_id'];
                    }
                    if(
                        $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']==0||
                        $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']==null||
                        $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']==""
                    ){
                        $cuentasInmuebleActualiz=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasInmuebleActualiz,$nombreBDU );
                    }else{
                        $cuentasInmuebleActualiz=$this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id'];
                    }
                    $this->request->data['Bienesdeuso']['cuentaclienteterreno_id']=$cuentasInmuebleTerreno;
                    $this->request->data['Bienesdeuso']['cuentaclienteedificacion_id']=$cuentasInmuebleEdif;
                    $this->request->data['Bienesdeuso']['cuentaclientemejora_id']=$cuentasInmuebleMejora;
                    $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']=$cuentasInmuebleActualiz;
                    break;
                case 'Instalaciones':
                    if($this->request->data['Bienesdeuso']['descripcion']!="")
                        $nombreBDU  .= $this->request->data['Bienesdeuso']['descripcion'];
                    $prefijo = "120603";
                    if(
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==0||
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==null||
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==""
                    ){
                        $cuentasInstalacionesValorOrigen=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasInstalacionesValorOrigen,$nombreBDU );
                    }else{
                        $cuentasInstalacionesValorOrigen=$this->request->data['Bienesdeuso']['cuentaclientevalororigen_id'];
                    }
                    if(
                        $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']==0||
                        $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']==null||
                        $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']==""
                    )   {
                        $cuentasInstalacionesActualizacion=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasInstalacionesActualizacion,$nombreBDU );
                    }else{
                        $cuentasInstalacionesActualizacion=$this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id'];
                    }
                    $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']=$cuentasInstalacionesValorOrigen;
                    $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']=$cuentasInstalacionesActualizacion;
                    break;
                case 'Otros bienes de uso Muebles':
                    if($this->request->data['Bienesdeuso']['descripcion']!="")
                        $nombreBDU  .= $this->request->data['Bienesdeuso']['descripcion'];
                    $prefijo = "120604";
                    if(
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==0||
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==null||
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==""
                    ){
                        $cuentasMueblesYUtilesValorOrigen=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasMueblesYUtilesValorOrigen,$nombreBDU );
                    }else{
                        $cuentasMueblesYUtilesValorOrigen=$this->request->data['Bienesdeuso']['cuentaclientevalororigen_id'];
                    }
                    if(
                        $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']==0||
                        $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']==null||
                        $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']==""
                    )   {
                        $cuentasMueblesYUtilesActualizacion=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasMueblesYUtilesActualizacion,$nombreBDU );
                    }else{
                        $cuentasMueblesYUtilesActualizacion=$this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id'];
                    }
                    $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']=$cuentasMueblesYUtilesValorOrigen;
                    $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']=$cuentasMueblesYUtilesActualizacion;
                    break;
                case 'Otros bienes de uso Maquinas':
                    if($this->request->data['Bienesdeuso']['descripcion']!="")
                        $nombreBDU  .= $this->request->data['Bienesdeuso']['descripcion'];
                    $prefijo = "120605";
                    if(
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==0||
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==null||
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==""
                    ){
                        $cuentasMaquinariasValorOrigen=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasMaquinariasValorOrigen,$nombreBDU );
                    }else{
                        $cuentasMaquinariasValorOrigen=$this->request->data['Bienesdeuso']['cuentaclientevalororigen_id'];
                    }
                    if(
                        $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']==0||
                        $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']==null||
                        $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']==""
                    )   {
                        $cuentasMaquinariasActualizacion=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasMaquinariasActualizacion,$nombreBDU );
                    }else{
                        $cuentasMaquinariasActualizacion=$this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id'];
                    }
                    $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']=$cuentasMaquinariasValorOrigen;
                    $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']=$cuentasMaquinariasActualizacion;
                    break;
                case 'Otros bienes de uso Activos Biologicos':
                    if($this->request->data['Bienesdeuso']['descripcion']!="")
                        $nombreBDU  .= $this->request->data['Bienesdeuso']['descripcion'];
                    $prefijo = "120606";
                    if(
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==0||
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==null||
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==""
                    ){
                        $cuentasActivosBiologicosValorOrigen=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasActivosBiologicosValorOrigen,$nombreBDU );
                    }else{
                        $cuentasActivosBiologicosValorOrigen=$this->request->data['Bienesdeuso']['cuentaclientevalororigen_id'];
                    }
                    if(
                        $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']==0||
                        $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']==null||
                        $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']==""
                    )   {
                        $cuentasActivosBiologicosActualizacion=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasActivosBiologicosActualizacion,$nombreBDU );
                    }else{
                        $cuentasActivosBiologicosActualizacion=$this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id'];
                    }
                    $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']=$cuentasActivosBiologicosValorOrigen;
                    $this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id']=$cuentasActivosBiologicosActualizacion;
                    break;
                //NO empresa
                case 'Inmuebles':
                    if($this->request->data['Bienesdeuso']['calle']!="")
                        $nombreBDU  .= $this->request->data['Bienesdeuso']['calle'];
                    if($this->request->data['Bienesdeuso']['numero']!="")
                        $nombreBDU  .= " -".$this->request->data['Bienesdeuso']['numero'];
                    if($this->request->data['Bienesdeuso']['enelpais']){
                        $prefijo = "130101";
                    }else{
                        $prefijo = "130201";
                    }
                    if(
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==0||
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==null||
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==""
                    ){
                        $cuentasInmuebles=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasInmuebles,$nombreBDU );
                    }else{
                        $cuentasInmuebles=$this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id'];
                    }
                    $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']=$cuentasInmuebles;
                    break;
                case 'Automotor':
                    if($this->request->data['Bienesdeuso']['patente']!="")
                        $nombreBDU  .= $this->request->data['Bienesdeuso']['patente'];
                    if($this->request->data['Bienesdeuso']['aniofabricacion']!="")
                        $nombreBDU  .= " -".$this->request->data['Bienesdeuso']['aniofabricacion'];
                    if($this->request->data['Bienesdeuso']['enelpais']){
                        $prefijo = "130103";
                    }else{
                        $prefijo = "130203";
                    }
                    if(
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==0||
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==null||
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==""
                    ){
                        $cuentasAutomotores=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasAutomotores,$nombreBDU );
                    }else{
                        $cuentasAutomotores=$this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id'];
                    }
                    $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']=$cuentasAutomotores;
                    break;
                case 'Naves, Yates y similares':
                    if($this->request->data['Bienesdeuso']['marca']!="")
                        $nombreBDU  .= $this->request->data['Bienesdeuso']['marca'];
                    if($this->request->data['Bienesdeuso']['modelo']!="")
                        $nombreBDU  .= " -".$this->request->data['Bienesdeuso']['modelo'];
                    if($this->request->data['Bienesdeuso']['enelpais']){
                        $prefijo = "130105";
                    }else{
                        $prefijo = "130203";
                    }
                    if(
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==0||
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==null||
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==""
                    ){
                        $cuentasNaves=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasNaves,$nombreBDU );
                    }else{
                        $cuentasNaves=$this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id'];
                    }
                    $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']=$cuentasNaves;
                    break;
                case 'Aeronave':
                    if($this->request->data['Bienesdeuso']['matricula']!="")
                        $nombreBDU  .= $this->request->data['Bienesdeuso']['matricula'];
                    if($this->request->data['Bienesdeuso']['fechaadquisicion']!="")
                        $nombreBDU  .= " -".$this->request->data['Bienesdeuso']['fechaadquisicion'];
                    if($this->request->data['Bienesdeuso']['enelpais']){
                        $prefijo = "130104";
                    }else{
                        $prefijo = "130203";
                    }
                    if(
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==0||
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==null||
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==""
                    ){
                        $cuentasAeronaves=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasAeronaves,$nombreBDU );
                    }else{
                        $cuentasAeronaves=$this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id'];
                    }
                    $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']=$cuentasAeronaves;
                    break;
                case 'Bien mueble registrable':
                    if($this->request->data['Bienesdeuso']['descripcion']!="")
                        $nombreBDU  .= $this->request->data['Bienesdeuso']['descripcion'];
                    if($this->request->data['Bienesdeuso']['enelpais']){
                        $prefijo = "130104";
                    }else{
                        $prefijo = "130206";
                    }
                    if(
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==0||
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==null||
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==""
                    ){
                        $cuentasBienesMueblesRegistrables=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasBienesMueblesRegistrables,$nombreBDU );
                    }else{
                        $cuentasBienesMueblesRegistrables=$this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id'];
                    }$this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']=$cuentasBienesMueblesRegistrables;
                    break;
                case 'Otros bienes':
                    if($this->request->data['Bienesdeuso']['descripcion']!="")
                        $nombreBDU  .= $this->request->data['Bienesdeuso']['descripcion'];
                    if($this->request->data['Bienesdeuso']['enelpais']){
                        $prefijo = "130115";
                    }else{
                        $prefijo = "130209";
                    }
                    if(
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==0||
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==null||
                        $this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']==""
                    ){
                        $cuentasOtrosbienes=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasOtrosbienes,$nombreBDU );
                    }else{
                        $cuentasOtrosbienes=$this->request->data['Bienesdeuso']['cuentaclienteactualizacion_id'];
                    }$this->request->data['Bienesdeuso']['cuentaclientevalororigen_id']=$cuentasOtrosbienes;
                    break;
            }
            if ($this->Bienesdeuso->save($this->request->data)) {

                $respuesta['respuesta']='El bien de uso ha sido guardado correctamente.';
                $id = $this->Bienesdeuso->getLastInsertID();
                $options = array(
                        'contain'=>[],
                        'conditions' => array(
                            'Bienesdeuso.' . $this->Bienesdeuso->primaryKey => $id
                            )
                        );
                $createdBDU = $this->Bienesdeuso->find('first', $options);
                $respuesta['bienesdeuso']=$createdBDU;

            } else {
				$respuesta['respuesta']='El bien de uso no se guardo correctamente.
				 Por favor intente de nuevo mas tarde.';
                 $respuesta['bienesdeuso']=[];
                 $respuesta['error']=['Error al salvar'];
			}
			$this->layout = 'ajax';
			$this->set('data', $respuesta);
			$this->render('serializejson');
			return;
		}
        if($biendeusiid!=0&&$biendeusiid!=null){
            $optionbiendeuso = [
                'contain'=>[

                ],
                'conditions'=>[
                    'Bienesdeuso.id'=>$biendeusiid
                ]
            ];
            $this->request->data = $this->Bienesdeuso->find('first',$optionbiendeuso);
        }else{
            if(isset($compra['Bienesdeuso'])&&count($compra['Bienesdeuso'])>0){
                $this->request->data = ['Bienesdeuso'=>$compra['Bienesdeuso'][0]];
            }
        }

		$conditionsLocalidades = array(
			'contain'=>'Partido',
			'fields'=>array('Localidade.id','Localidade.nombre','Partido.nombre'),
			'order'=>array('Partido.nombre','Localidade.nombre')
		);
		$localidades = $this->Bienesdeuso->Localidade->find('list',$conditionsLocalidades);

        $optioncuentascliente = [
            'fields'=>['Cuentascliente.id','Cuentascliente.nombre','Cuenta.numero'],
            'contain'=>'Cuenta',
            'conditions'=>[
                'Cuentascliente.cliente_id'=> $cliid,
            ]
        ];
        $cuentasclientes = $this->Cuentascliente->find('list',$optioncuentascliente);
        $this->set('cuentasclientes', $cuentasclientes);

        $optionmodelo = [
            'fields'=>['Modelo.id','Modelo.codname','Marca.nombre'],
            'contain'=>['Marca'],
            'conditions'=>[
            ]
        ];
        $modelos = $this->Bienesdeuso->Modelo->find('list',$optionmodelo);
        $this->set('modelos', $modelos);

		$this->set(compact('cliente','compra', 'localidades'));
		if($this->request->is('ajax')){
			$this->layout = 'ajax';
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Bienesdeuso->exists($id)) {
			throw new NotFoundException(__('Invalid bienesdeuso'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Bienesdeuso->save($this->request->data)) {
				$this->Session->setFlash(__('The bienesdeuso has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The bienesdeuso could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Bienesdeuso.' . $this->Bienesdeuso->primaryKey => $id));
			$this->request->data = $this->Bienesdeuso->find('first', $options);
		}
		$compras = $this->Bienesdeuso->Compra->find('list');
		$localidades = $this->Bienesdeuso->Localidade->find('list');
		$this->set(compact('compras', 'localidades'));
	}
        public function amortizar(){
            foreach ($this->request->data['Bienesdeuso'] as $bdu => $bienesdeuso) {
                $this->Bienesdeuso->id = $bienesdeuso['id'];
                $guardoamortizacionacumulada = $this->Bienesdeuso->saveField('amortizacionacumulada', $bienesdeuso['amortizacionacumulada']);
                $guardoamortizacion = $this->Bienesdeuso->saveField('importeamorteizaciondelperiodo', $bienesdeuso['amortizaciondelperiodo']);
                if ($guardoamortizacionacumulada&&$guardoamortizacion) {
                    $data['respuesta'] = 'Se amortizo el bien de uso.';
                    $data['error'] = 0;
                } else {
                    $data['respuesta'] = 'ERROR: No se amortizo el bien de uso. Por favor intente mas tarde.';
                    $data['error'] = 1;
                }
            }
            $this->layout = 'ajax';
            $this->set('data', $data);
            $this->render('serializejson');
            return;
        }    
        public function relacionarventa($cliid=null,$ventaid=null) {
        $this->loadModel('Venta');
        $data = array();
        if ($this->request->is('post')) {
            //vamos a eliminar todas las referencias a la venta que ya habiamos echo
            $this->Bienesdeuso->updateAll(
                array('Bienesdeuso.venta_id' => null),
                array('Bienesdeuso.venta_id =' => $this->request->data['Bienesdeuso']['venta_id'])
            );

            $ventaid = $this->request->data['Bienesdeuso']['venta_id'];
            $biendeusoid = $this->request->data['Bienesdeuso']['bienesdeuso_id'];

            $this->Bienesdeuso->id = $biendeusoid;
            if ($this->Bienesdeuso->saveField('venta_id', $ventaid)) {
                $data['respuesta'] = 'Se relaciono el bien de uso con la venta.';
                $data['error'] = 0;
            } else {
                $data['respuesta'] = 'No se relaciono el bien de uso con la venta. Por favor intente mas tarde.';
                $data['error'] = 1;
            }
            $this->layout = 'ajax';
            $this->set('data', $data);
            $this->render('serializejson');
            return;
        }
        //vamos a enviar una lista con todos los bienes de uso que no tengan ventas relacionadas
        //que tenemos por si se requiere relacionar una venta a un bien de uso.
        $bienesdeusos = $this->Bienesdeuso->find('list',array(
            'conditions' => array(
                'Bienesdeuso.cliente_id'=>$cliid,
                'OR'=>[
                    'Bienesdeuso.venta_id' => [$ventaid,"",0,null],
                    'Bienesdeuso.venta_id is null',
                ]
            ),
        ));
        $venta = $this->Venta->find('first',[
                'conditions'=>['Venta.id'=>$ventaid],
                'contain'=>[
                    'Bienesdeuso'
                ]
            ]
        );
        $biendeusoSeleccionado = 0;
        if(count($venta['Bienesdeuso'])>0){
            $biendeusoSeleccionado = $venta['Bienesdeuso'][0]['id'];
        }
        $this->set(compact('bienesdeusos','ventaid','biendeusoSeleccionado'));

        if($this->request->is('ajax')){
            $this->layout = 'ajax';
        }
    }
/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Bienesdeuso->id = $id;
        $cliid = "";
		if (!$this->Bienesdeuso->exists()) {
			throw new NotFoundException(__('Invalid bienesdeuso'));
		}
         $options = array('contain'=>[],'conditions' => array('Bienesdeuso.' . $this->Bienesdeuso->primaryKey => $id));
        $bienesdeuso = $this->Bienesdeuso->find('first', $options);
        $cliid = $bienesdeuso['cliente_id'];

		$this->request->allowMethod('post', 'delete');
		if ($this->Bienesdeuso->delete()) {
			$this->Session->setFlash(__('El bien de uso ha sido eliminado.'));
		} else {
			$this->Session->setFlash(__('El bien de uso NO ha sido eliminado. Por favor intente de nuevo mas tarde'));
		}
       
		return $this->redirect(array('controller' => 'cliente','action' => 'index',$cliid));
	}
}