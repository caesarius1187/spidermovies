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
        $this->loadModel('Amortizacione');

        $compra=[];
        if($compraid!=null){
            //puede ser que compraid sea null por que estamos cargando desde clientes/view
            $optioncompra = [
                'contain'=>[
                    'Actividadcliente'=>[
                        'Cuentasganancia'
                    ],
                    'Bienesdeuso'=>[
                        'Amortizacione'
                    ],
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
        $this->set(compact('compraid'));
        $cliente = $this->Cliente->find('first',$optioncliente);
        if ($this->request->is('post')||$this->request->is('put')) {
        $this->Bienesdeuso->create();
        if($this->request->data['Bienesdeuso'][0]['fechaadquisicion']!=""){
            $this->request->data('Bienesdeuso.0.fechaadquisicion',date('Y-m-d',strtotime($this->request->data['Bienesdeuso'][0]['fechaadquisicion'])));
        }
        $cliid = $this->request->data['Bienesdeuso'][0]['cliente_id'];
        //hay que dar de alta la cuenta de bien de uso reemplazando XX
        //vamos a armar el nombre del Bien de uso
        $nombreBDU = " ";
        $nombreCuentaActivable = "";
        $respuesta['respuesta']='';
        $respuesta['cuentasactivadas']='';
        switch ($this->request->data['Bienesdeuso'][0]['tipo']){
            //Empresa
            case 'Rodado':
                if($this->request->data['Bienesdeuso'][0]['patente']!="")
                    $nombreBDU  .= $this->request->data['Bienesdeuso'][0]['patente'];
                if($this->request->data['Bienesdeuso'][0]['aniofabricacion']!="")
                    $nombreBDU  .= " -".$this->request->data['Bienesdeuso'][0]['aniofabricacion'];
                $prefijo = "120602";
                if(
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==0||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==null||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==""
                ){
                    $cuentasRodadoValorOrigen=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasRodadoValorOrigen,$nombreBDU );
                    $respuesta['cuentasactivadas'].=$cuentasRodadoValorOrigen['respuesta'];
                }else{
                    $cuentasRodadoValorOrigen['cuenta_id']=$this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id'];
                }
                if(
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']==0||
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']==null||
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']==""
                )   {
                    $cuentasRodadoActualizacion=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasRodadoActualizacion,$nombreBDU );
                    $respuesta['cuentasactivadas'].=$cuentasRodadoActualizacion['respuesta'];
                }else{
                    $cuentasRodadoActualizacion['cuenta_id']=$this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id'];
                }
                $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']=$cuentasRodadoValorOrigen['cuenta_id'];
                $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']=$cuentasRodadoActualizacion['cuenta_id'];
                break;
            case 'Inmueble':
                if($this->request->data['Bienesdeuso'][0]['calle']!="")
                    $nombreBDU  .= $this->request->data['Bienesdeuso'][0]['calle'];
                if($this->request->data['Bienesdeuso'][0]['numero']!="")
                    $nombreBDU  .= " -".$this->request->data['Bienesdeuso'][0]['numero'];
                $prefijo = "120601";
                if(
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteterreno_id']==0||
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteterreno_id']==null||
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteterreno_id']==""
                ){
                    $cuentasInmuebleTerreno=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasInmuebleTerreno,$nombreBDU );
                    $respuesta['cuentasactivadas'].=$cuentasInmuebleTerreno['respuesta'];
                }else{
                    $cuentasInmuebleTerreno['cuenta_id']=$this->request->data['Bienesdeuso'][0]['cuentaclienteterreno_id'];
                }
                if(
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteedificacion_id']==0||
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteedificacion_id']==null||
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteedificacion_id']==""
                ){
                    $cuentasInmuebleEdif=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasInmuebleEdif,$nombreBDU );
                    $respuesta['cuentasactivadas'].=$cuentasInmuebleEdif['respuesta'];
                }else{
                    $cuentasInmuebleEdif['cuenta_id']=$this->request->data['Bienesdeuso'][0]['cuentaclienteedificacion_id'];
                }
                if(
                    $this->request->data['Bienesdeuso'][0]['cuentaclientemejora_id']==0||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientemejora_id']==null||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientemejora_id']==""
                ){
                    $cuentasInmuebleMejora=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasInmuebleMejora,$nombreBDU );
                    $respuesta['cuentasactivadas'].=$cuentasInmuebleMejora['respuesta'];
                }else{
                    $cuentasInmuebleMejora['cuenta_id']=$this->request->data['Bienesdeuso'][0]['cuentaclientemejora_id'];
                }
                if(
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']==0||
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']==null||
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']==""
                ){
                    $cuentasInmuebleActualiz=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasInmuebleActualiz,$nombreBDU );
                    $respuesta['cuentasactivadas'].=$cuentasInmuebleActualiz['respuesta'];
                }else{
                    $cuentasInmuebleActualiz['cuenta_id']=$this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id'];
                }
                $this->request->data['Bienesdeuso'][0]['cuentaclienteterreno_id']=$cuentasInmuebleTerreno['cuenta_id'];
                $this->request->data['Bienesdeuso'][0]['cuentaclienteedificacion_id']=$cuentasInmuebleEdif['cuenta_id'];
                $this->request->data['Bienesdeuso'][0]['cuentaclientemejora_id']=$cuentasInmuebleMejora['cuenta_id'];
                $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']=$cuentasInmuebleActualiz['cuenta_id'];
                break;
            case 'Instalaciones':
                if($this->request->data['Bienesdeuso'][0]['descripcion']!="")
                    $nombreBDU  .= $this->request->data['Bienesdeuso'][0]['descripcion'];
                $prefijo = "120603";
                if(
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==0||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==null||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==""
                ){
                    $cuentasInstalacionesValorOrigen=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasInstalacionesValorOrigen,$nombreBDU );
                    $respuesta['cuentasactivadas'].=$cuentasInstalacionesValorOrigen['respuesta'];
                }else{
                    $cuentasInstalacionesValorOrigen['cuenta_id']=$this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id'];
                }
                if(
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']==0||
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']==null||
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']==""
                )   {
                    $cuentasInstalacionesActualizacion=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasInstalacionesActualizacion,$nombreBDU );
                    $respuesta['cuentasactivadas'].=$cuentasInstalacionesActualizacion['respuesta'];
                }else{
                    $cuentasInstalacionesActualizacion['cuenta_id']=$this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id'];
                }
                $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']=$cuentasInstalacionesValorOrigen['cuenta_id'];
                $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']=$cuentasInstalacionesActualizacion['cuenta_id'];
                break;
            case 'Otros bienes de uso Muebles':
                if($this->request->data['Bienesdeuso'][0]['descripcion']!="")
                    $nombreBDU  .= $this->request->data['Bienesdeuso'][0]['descripcion'];
                $prefijo = "120604";
                if(
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==0||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==null||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==""
                ){
                    $cuentasMueblesYUtilesValorOrigen=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasMueblesYUtilesValorOrigen,$nombreBDU );
                    $respuesta['cuentasactivadas'].=$cuentasMueblesYUtilesValorOrigen['respuesta'];
                }else{
                    $cuentasMueblesYUtilesValorOrigen['cuenta_id']=$this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id'];
                }
                if(
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']==0||
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']==null||
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']==""
                )   {
                    $cuentasMueblesYUtilesActualizacion=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasMueblesYUtilesActualizacion,$nombreBDU );
                    $respuesta['cuentasactivadas'].=$cuentasMueblesYUtilesActualizacion['respuesta'];
                }else{
                    $cuentasMueblesYUtilesActualizacion['cuenta_id']=$this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id'];
                }
                $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']=$cuentasMueblesYUtilesValorOrigen['cuenta_id'];
                $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']=$cuentasMueblesYUtilesActualizacion['cuenta_id'];
                break;
            case 'Otros bienes de uso Maquinas':
                if($this->request->data['Bienesdeuso'][0]['descripcion']!="")
                    $nombreBDU  .= $this->request->data['Bienesdeuso'][0]['descripcion'];
                $prefijo = "120605";
                if(
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==0||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==null||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==""
                ){
                    $cuentasMaquinariasValorOrigen=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasMaquinariasValorOrigen,$nombreBDU );
                    $respuesta['cuentasactivadas'].=$cuentasMaquinariasValorOrigen['respuesta'];
                }else{
                    $cuentasMaquinariasValorOrigen['cuenta_id']=$this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id'];
                }
                if(
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']==0||
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']==null||
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']==""
                )   {
                    $cuentasMaquinariasActualizacion=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasMaquinariasActualizacion,$nombreBDU );
                    $respuesta['cuentasactivadas'].=$cuentasMaquinariasActualizacion['respuesta'];
                }else{
                    $cuentasMaquinariasActualizacion['cuenta_id']=$this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id'];
                }
                $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']=$cuentasMaquinariasValorOrigen['cuenta_id'];
                $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']=$cuentasMaquinariasActualizacion['cuenta_id'];
                break;
            case 'Otros bienes de uso Activos Biologicos':
                if($this->request->data['Bienesdeuso'][0]['descripcion']!="")
                    $nombreBDU  .= $this->request->data['Bienesdeuso'][0]['descripcion'];
                $prefijo = "120606";
                if(
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==0||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==null||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==""
                ){
                    $cuentasActivosBiologicosValorOrigen=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasActivosBiologicosValorOrigen,$nombreBDU );
                    $respuesta['cuentasactivadas'].=$cuentasActivosBiologicosValorOrigen['respuesta'];
                }else{
                    $cuentasActivosBiologicosValorOrigen['cuenta_id']=$this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id'];
                }
                if(
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']==0||
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']==null||
                    $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']==""
                )   {
                    $cuentasActivosBiologicosActualizacion=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasActivosBiologicosActualizacion,$nombreBDU );
                    $respuesta['cuentasactivadas'].=$cuentasActivosBiologicosActualizacion['respuesta'];
                }else{
                    $cuentasActivosBiologicosActualizacion['cuenta_id']=$this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id'];
                }
                $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']=$cuentasActivosBiologicosValorOrigen['cuenta_id'];
                $this->request->data['Bienesdeuso'][0]['cuentaclienteactualizacion_id']=$cuentasActivosBiologicosActualizacion['cuenta_id'];
                break;
            //NO empresa
            case 'Inmuebles':
                if($this->request->data['Bienesdeuso'][0]['calle']!="")
                    $nombreBDU  .= $this->request->data['Bienesdeuso'][0]['calle'];
                if($this->request->data['Bienesdeuso'][0]['numero']!="")
                    $nombreBDU  .= " -".$this->request->data['Bienesdeuso'][0]['numero'];
                if($this->request->data['Bienesdeuso'][0]['enelpais']){
                    $prefijo = "130101";
                }else{
                    $prefijo = "130201";
                }
                if(
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==0||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==null||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==""
                ){
                    $cuentasInmuebles=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasInmuebles,$nombreBDU );
                    $respuesta['cuentasactivadas'].=$cuentasInmuebles['respuesta'];
                }else{
                    $cuentasInmuebles['cuenta_id']=$this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id'];
                }
                $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']=$cuentasInmuebles['cuenta_id'];
                break;
            case 'Automotor':
                if($this->request->data['Bienesdeuso'][0]['patente']!="")
                    $nombreBDU  .= $this->request->data['Bienesdeuso'][0]['patente'];
                if($this->request->data['Bienesdeuso'][0]['aniofabricacion']!="")
                    $nombreBDU  .= " -".$this->request->data['Bienesdeuso'][0]['aniofabricacion'];
                if($this->request->data['Bienesdeuso'][0]['enelpais']){
                    $prefijo = "130103";
                }else{
                    $prefijo = "130203";
                }
                if(
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==0||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==null||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==""
                ){
                    $cuentasAutomotores=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasAutomotores,$nombreBDU );
                    $respuesta['cuentasactivadas'].=$cuentasAutomotores['respuesta'];
                }else{
                    $cuentasAutomotores['cuenta_id']=$this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id'];
                }
                $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']=$cuentasAutomotores['cuenta_id'];
                break;
            case 'Naves, Yates y similares':
                if($this->request->data['Bienesdeuso'][0]['marca']!="")
                    $nombreBDU  .= $this->request->data['Bienesdeuso'][0]['marca'];
                if($this->request->data['Bienesdeuso'][0]['modelo']!="")
                    $nombreBDU  .= " -".$this->request->data['Bienesdeuso'][0]['modelo'];
                if($this->request->data['Bienesdeuso'][0]['enelpais']){
                    $prefijo = "130104";
                }else{
                    $prefijo = "130203";
                }
                if(
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==0||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==null||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==""
                ){
                    $cuentasNaves=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasNaves,$nombreBDU );
                    $respuesta['cuentasactivadas'].=$cuentasNaves['respuesta'];
                }else{
                    $cuentasNaves['cuenta_id']=$this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id'];
                }
                $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']=$cuentasNaves['cuenta_id'];
                break;
            case 'Aeronave':
                if($this->request->data['Bienesdeuso'][0]['matricula']!="")
                    $nombreBDU  .= $this->request->data['Bienesdeuso'][0]['matricula'];
                if($this->request->data['Bienesdeuso'][0]['fechaadquisicion']!="")
                    $nombreBDU  .= " -".$this->request->data['Bienesdeuso'][0]['fechaadquisicion'];
                if($this->request->data['Bienesdeuso'][0]['enelpais']){
                    $prefijo = "130105";
                }else{
                    $prefijo = "130203";
                }
                if(
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==0||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==null||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==""
                ){
                    $cuentasAeronaves=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasAeronaves,$nombreBDU );
                    $respuesta['cuentasactivadas'].=$cuentasAeronaves['respuesta'];
                }else{
                    $cuentasAeronaves['cuenta_id']=$this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id'];
                }
                $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']=$cuentasAeronaves['cuenta_id'];
                break;
            case 'Bien mueble registrable':
                if($this->request->data['Bienesdeuso'][0]['descripcion']!="")
                    $nombreBDU  .= $this->request->data['Bienesdeuso'][0]['descripcion'];
                if($this->request->data['Bienesdeuso'][0]['enelpais']){
                    $prefijo = "130114";
                }else{
                    $prefijo = "130206";
                }
                if(
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==0||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==null||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==""
                ){
                    $cuentasBienesMueblesRegistrables=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasBienesMueblesRegistrables,$nombreBDU );
                    $respuesta['cuentasactivadas'].=$cuentasBienesMueblesRegistrables['respuesta'];
                }else{
                    $cuentasBienesMueblesRegistrables['cuenta_id']=$this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id'];
                }$this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']=$cuentasBienesMueblesRegistrables['cuenta_id'];
                break;
            case 'Otros bienes':
                if($this->request->data['Bienesdeuso'][0]['descripcion']!="")
                    $nombreBDU  .= $this->request->data['Bienesdeuso'][0]['descripcion'];
                if($this->request->data['Bienesdeuso'][0]['enelpais']){
                    $prefijo = "130115";
                }else{
                    $prefijo = "130209";
                }
                if(
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==0||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==null||
                    $this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']==""
                ){
                    $cuentasOtrosbienes=$this->Cuentascliente->altabiendeuso($cliid,$prefijo,$this->Cuenta->cuentasOtrosbienes,$nombreBDU );
                    $respuesta['cuentasactivadas'].=$cuentasOtrosbienes['respuesta'];
                }else{
                    $cuentasOtrosbienes['cuenta_id']=$this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id'];
                }$this->request->data['Bienesdeuso'][0]['cuentaclientevalororigen_id']=$cuentasOtrosbienes['cuenta_id'];
                break;
        }
        if ($this->Bienesdeuso->saveAll($this->request->data['Bienesdeuso'][0])) {
            $respuesta['respuesta'].='El bien de uso ha sido guardado correctamente.';
            $id = $this->Bienesdeuso->getLastInsertID();
            $options = array(
                    'contain'=>[],
                    'conditions' => array(
                        'Bienesdeuso.' . $this->Bienesdeuso->primaryKey => $id
                        )
                    );
            $createdBDU = $this->Bienesdeuso->find('first', $options);
            //ahora vamos a guardar las amortizaciones
            if(isset($this->request->data['Bienesdeuso'][0]['Amortizacione'])){
                if(
                    $this->request->data['Bienesdeuso'][0]['Amortizacione'][0]['periodo']!=""&&
                    ($this->request->data['Bienesdeuso'][0]['Amortizacione'][0]['periodo']!=""||
                    $this->request->data['Bienesdeuso'][0]['Amortizacione'][0]['periodo']!="")
                ){
                   /* if ($this->Amortizacione->saveAll($this->request->data['Bienesdeuso'][0]['Amortizacione'][0])) {
                         $respuesta['respuesta'].='Se guarto también la amortizacion especial.';
                    }*/
                }
            }
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
                     'Amortizacione'
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
