<?php
echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false));
echo $this->Html->css('bootstrapmodal');
echo $this->Html->css('progressbuttons');
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
echo $this->Html->script('clientes/avance',array('inline'=>false));
?>
<input class="button" type="button" id="btnShowForm" onClick="showForm()" value="Mostrar" style="display:none" />

<div id="Formhead" class="clientes avance" style="">

  <!--<input class="button" type="button" id="btnHiddeForm" onClick="hideForm()" value="Ocultar" style="float:right;"/>-->
  <?php 
  echo $this->Form->create('clientes',array('action' => 'avance')); ?> 
        <?php
        echo $this->Form->input('periodomes', array(
            'options' => array(
                '01'=>'Enero',
                '02'=>'Febrero',
                '03'=>'Marzo',
                '04'=>'Abril',
                '05'=>'Mayo',
                '06'=>'Junio',
                '07'=>'Julio',
                '08'=>'Agosto',
                '09'=>'Septiembre',
                '10'=>'Octubre',
                '11'=>'Noviembre',
                '12'=>'Diciembre',
            ),
            'empty' => 'Elegir mes',
            'label'=> 'Mes',
            'required' => true,
            'placeholder' => 'Por favor seleccione Mes',
            'default' =>  date("m", strtotime("-1 month"))
        ));
        ?>
        <?php echo $this->Form->input('periodoanio', array(
                'options' => array(
                    '2014'=>'2014',
                    '2015'=>'2015',
                    '2016'=>'2016',
                    '2017'=>'2017',
                    '2018'=>'2018',
                ),
                'empty' => 'Elegir año',
                'label'=> 'Año',
                'required' => true,
                'placeholder' => 'Por favor seleccione año',
                'default' =>  date("Y", strtotime("-1 month"))
            )
        );
        echo $this->Form->input('gclis', array(
          //'multiple' => 'multiple',
            'type' => 'select',
            'class'=>'chosen-select filtroAvance',
            'label' => false,
            'empty' => 'Filtrar por grupo de cliente'
        ));
          echo $this->Form->input('lclis', array(
            //'multiple' => 'multiple',
              'type' => 'select',
              'class'=>'chosen-select filtroAvance',
              'label' => false,
              'empty' => 'Filtrar por cliente'
          ));

//        echo $this->Form->input('filtrodesolicitar', array(
//          //'multiple' => 'multiple',
//          'type' => 'select',
//          'class'=>'chosen-select filtroAvance',
//          'label' => false,
//          'empty' => 'Filtrar por elementos a solicitar',
//          'options'=> array(
//            'banco'=>'Bancos',
//            'tarjetadecredito'=>'Tarj. de Credito',
//            'descargawebafip'=>'Descarga Web AFIP',
//            'libroivaventas'=>'Libro IVA Ventas',
//            'fcluz'=>'Fc. de Luz',
//            'sueldos'=>'Novedades Sueldos',
//            'librounico'=>'Libro Unico',
//            )
//        ));
        echo $this->Form->input('filtrodeimpuestos', array(
          //'multiple' => 'multiple',
          'type' => 'select',
          'class'=>'chosen-select filtroAvance',
          'label' => false,
          'empty' => 'Filtrar por impuestos', 
          'options'=> $impuestos
        )); ?>

        <?php echo $this->Form->input('selectby',array('default'=>'none','type'=>'hidden'));// ?>
        <?php echo $this->Form->end(__('Aceptar')); ?>

</div> <!--End Clietenes_avance-->
  <?php /**************************************************************************/  ?>
  <?php /*****************************Mostrar el informe**************************/  ?>
  <?php /**************************************************************************/ ?>
<div class="clientes_avance">
  <?php 
  if(isset($mostrarInforme)){?>
  <?php
  $periodoSel=$periodomes."-".$periodoanio;
  echo $this->Form->input('periodoSel',array('type'=>'hidden','value'=>$periodoSel)); ?>
  <table cellpadding="0" cellspacing="0" class="tbl_avance" id="tbl_tareas"> <!--Tbl 1-->
    <?php /**************************************************************************/ ?>
    <?php /*****************************Recorremos los clientes**********************/ ?>
    <?php /**************************************************************************/ ?>
    <tbody>
      <?php 
      foreach ($clientes as $cliente){ 
        echo $this->Form->input('cliid'+$cliente['Cliente']['id'],array('type'=>'hidden','value'=>$cliente['Cliente']['id']));
         ?>
        <tr>
          <td class="columnClienteHeader" colspan="20" style="">
              <div class="divClienteHeader">
                  <?php
                       echo $this->Html->link(
                        $cliente['Cliente']['nombre'],
                        array(
                          'controller' => 'clientes',
                          'action' => 'view',
                          $cliente['Cliente']['id']),
                        array()
                      ); ?>
              </div>
          </td >
        </tr>
        <tr>
          <?php
          $HayImpuestosHabilitados = false;
          //Aqui se pinta la caja que identifica a que impuesto pertenece cada row.
          foreach ($cliente["Impcli"] as $impcli){  
              if(Count($impcli['Periodosactivo'])!=0&&($impcli['Impuesto']['organismo']!='banco')){
                $HayImpuestosHabilitados = true;
              }  
            }; 
          /**************************************************************************/ 
          /****************Recorremos las tareas una ves por cada evento de impuesto**/ 
          /**cliente chekiando que la tarea este habilitada para el usuario logueado**/
          /**********************y que tipo de tarea es*******************************/
          foreach ($tareas as $tarea){
              $tareaNombre=$tarea["Tareascliente"]['nombre'];
              $tareaFild='tarea'.$tarea["Tareasxclientesxestudio"]["tareascliente_id"]; //nombre de la tarea que estoy recorriendo
            $Tareahabilitado=false;                                                   //por defecto no esta habilitada la tarea
            if($tarea["Tareasxclientesxestudio"]['user_id']==$this->Session->read('Auth.User.id')){ 
              $Tareahabilitado=true;
            }   
            if($tarea["Tareasxclientesxestudio"]['tipo']=="cliente"){
              /**************************************************************************/
              /*******************************tarea tipo cliente*************************/
              /**************************************************************************/
              $eventoCreado=false;
              foreach ($cliente["Eventoscliente"] as $evento){
                mostrarEventoCliente($this, $evento, $periodoSel, $tareaNombre, $tareaFild, $cliente,$Tareahabilitado,$HayImpuestosHabilitados);
                $eventoCreado=true;           
              }; 
              if(!$eventoCreado){
                mostrarEventoCliente($this, null, $periodoSel, $tareaNombre, $tareaFild, $cliente,$Tareahabilitado,$HayImpuestosHabilitados);
              }
            }
            if($tarea["Tareasxclientesxestudio"]['tipo']=="impuesto"){
              //tarea tipo impuesto
              if($tarea['Tareascliente']['id']=='5'/*Preparar Papeles de trabajo*/) {?>
                <td class="columnClienteBodyMedio tdBotonesImpuestos"> <!--Tbl 1.2-->
                  <div class="divtareas">
                      <div class="divTareaNombre">
                        <?php echo $tareaNombre; ?>                        
                      </div>
                      <div style="max-height:210px; overflow:auto">
                      <?php
                      $hayImpuestoRelacioado=false;
                      foreach ($cliente["Impcli"] as $impcli){
                        //Si es un banco no lo vamos a mostrar
                        if($impcli['Impuesto']['organismo']=='banco'){
                          continue;
                        }
                        //Recorremos los impuestos de cada cliente
                        $hayImpuestoRelacioado=true;
                        $eventoNoCreado=true;
                        //Recorremos los impuestos de cada cliente Chekiamos si el evento esta creado
                        $montovto = 0;
                        $montofavor = 0;
                        $pagado=true;
                        foreach ($impcli["Eventosimpuesto"] as $evento){
                          if($evento['periodo']==$periodoSel){
                            $eventoNoCreado=false;
                            $montovto += $evento['montovto'];
                            $montofavor += $evento['monc'];
                          }

                          if($evento['tarea13']=='pendiente'){
                              $pagado=false;
                          }
                        }
                        //Esta faltando sumar SLD si es iva
                        if($impcli['impuesto_id']==19/*IVA*/){
                          foreach ($impcli["Conceptosrestante"] as $conceptorestante) {
                            $montofavor += $conceptorestante['montoretenido'];
                          }
                        }

                        $tareaFild='tarea'.$tarea["Tareasxclientesxestudio"]["tareascliente_id"];
                       /*Como no podemos traer los impuestos ordenados por sql vamos a ordenarlos aqui*/
                        if($montovto>0){
                          $montovto = $montovto*-1;
                        }else{
                          $montovto = $montofavor;
                        }
                        mostrarBotonImpuesto($this,$cliente, $impcli,$montovto ,$periodoSel,$pagado,$eventoNoCreado) ;
                      }
                     ?>
                     </div>
                </div>
              </td>
            <?php
              }else if($tarea['Tareascliente']['id']=='19'/*Contabilizar*/) {
                  ?>
                <td class="columnClienteBodyFin tdBotonesContabilizar"> <!--Tbl 1.2-->
                    <div class="divtareas">
                        <div class="divTareaNombre">
                            <?php echo $tareaNombre; ?>
                        </div>
                        <div style="max-height:210px; overflow:auto">
                    <?php
                    if($cliente['impuestosactivos']['contabiliza']){
                        $paramsPrepPapeles="'".$cliente['Cliente']['id']."','".$periodoSel."'";
                        echo $this->Form->button(
                            'Sumas y Saldos',
                            array(
                                'class'=>"buttonImpcliRealizado progress-button state-loading",
                                'onClick'=>"abrirsumasysaldos(".$paramsPrepPapeles.")",
                                'style'=>'color:#1e88e5',
                                'id'=>'buttonPlanDeCuenta'.$cliente['Cliente']['id'],
                            ),
                            array());
                        $tieneasientodeventas=false;
                        $tieneasientodecompras=false;
                        $tieneasientoderetenciones=false;
                        foreach ($cliente["Asiento"] as $asiento ){
                            if($asiento['tipoasiento']=='ventas'){
                                $tieneasientodeventas=true;
                            }
                            if($asiento['tipoasiento']=='compras'){
                                $tieneasientodecompras=true;
                            }
                            if($asiento['tipoasiento']=='retencionessufridas'){
                                $tieneasientoderetenciones=true;
                            }
                        }
                        if($tieneasientodeventas){
                            $buttonclass="buttonImpcliRealizado";
                        }else{
                            $buttonclass="buttonImpcliListo";
                        }
                        echo $this->Form->button(
                            'As. Ventas',
                            array(
                                'class'=>$buttonclass." progress-button state-loading",
                                'onClick'=>"contabilizarventas(".$paramsPrepPapeles.")",
                                'id'=>'buttonAsVenta'.$cliente['Cliente']['id'],
                            ),
                            array());
                        if($tieneasientodecompras){
                            $buttonclass="buttonImpcliRealizado";
                        }else{
                            $buttonclass="buttonImpcliListo";
                        }
                        echo $this->Form->button(
                            'As. Compras',
                            array(
                                'onClick'=>"contabilizarcompras(".$paramsPrepPapeles.")",
                                'id'=>'buttonAsCompra'.$cliente['Cliente']['id'],
                                'data-style'=>"top-line",
                                'class'=>$buttonclass." progress-button state-loading",
                            ),
                            array());
                        if($tieneasientoderetenciones){
                            $buttonclass="buttonImpcliRealizado";
                        }else{
                            $buttonclass="buttonImpcliListo";
                        }
                        echo $this->Form->button(
                            'As. Retenciones sufridas',
                            array(
                                'onClick'=>"contabilizarretencionessufridas(".$paramsPrepPapeles.")",
                                'id'=>'buttonRetencionessufridas'.$cliente['Cliente']['id'],
                                'data-style'=>"top-line",
                                'class'=>$buttonclass." progress-button state-loading",
                            ),
                            array());
                        foreach ($cliente["Impcli"] as $impcli){
                            //Si es un banco vamos a buscar sus CBUs
                            if($impcli['Impuesto']['organismo']!='banco'){
                              continue;
                            }
                            foreach ($impcli["Cbu"] as $cbu){
                                $abreviacionCBUTipo = "";
                                switch ($cbu['tipocuenta']) {
                                  case 'Caja de Ahorro en Euros':
                                    $abreviacionCBUTipo = "CA €";
                                    break;
                                  case 'Caja de Ahorro en Moneda Local':
                                    $abreviacionCBUTipo = "CA $";
                                    break;
                                  case 'Caja de Ahorro en U$S':
                                    $abreviacionCBUTipo = "CA U$ S";
                                    break;
                                  case 'Cuenta Corriente en Euros':
                                    $abreviacionCBUTipo = "CC €";
                                    break;
                                  case 'Cuenta Corriente en Moneda Local':
                                    $abreviacionCBUTipo = "CC $";
                                    break;
                                  case 'Cuenta Corriente en U$S':
                                    $abreviacionCBUTipo = "CC U$ S";
                                    break;
                                  case 'Otras':
                                    $abreviacionCBUTipo = "Otras";
                                    break;
                                  case 'Plazo Fijo en Euros':
                                    $abreviacionCBUTipo = "PF €";
                                    break;
                                  case 'Plazo Fijo en U$S':
                                    $abreviacionCBUTipo = "PF U$ S";
                                    break;
                                  case 'Plazo Fijo en Moneda Local':
                                    $abreviacionCBUTipo = "PF $";
                                    break;
                                  default:
                                    $abreviacionCBUTipo = "cc $";
                                    break;
                                }
                                if(count($cbu['Asiento'])>0){
                                    $buttonclass="buttonImpcliRealizado";
                                }else{
                                    $buttonclass="buttonImpcliListo";
                                }
                                echo $this->Form->button(
                                    "As. ".$impcli['Impuesto']['nombre']." ".substr($cbu['numerocuenta'], -5)." ".$abreviacionCBUTipo,
                                    array(
                                        'class'=>$buttonclass.' progress-button state-loading',
                                        'onClick'=>"contabilizarBanco('".$cliente['Cliente']['id']."','".$periodoSel."','".$impcli['id']."','".$cbu['id']."')",
                                        'id'=>'buttonCbu'.$cbu['id'],
                                    ),
                                    array());
                            }
                        }
                    }
                    ?>
                        </div>
                    </div>
                </td>
                <?php
              }else{
                echo "<td><div class='divtareas'></div></td>";
              }
            }
          } ?>
        </tr>
                        
<!--        <tr><td colspan="50" style="height: 6px;"><hr color="#e8e8e8" width="100%" style="height: 2px;"></td> </tr>-->
        <?php 
      } ?>
    </tbody>
  </table>
    <p>
      <?php
      echo $this->Paginator->counter(array(
          'format' => __('Pagina {:page} de {:pages}, mostrando {:current} registros de {:count} en total, iniciando en el registro {:start}, finalizando en el registro{:end}')
      ));
      ?>
    </p>
    <div class="paging">
      <?php
      echo $this->Paginator->prev('< ' . __('anterior'), array(), null, array('class' => 'prev disabled'));
      echo $this->Paginator->numbers(array('separator' => ''));
      echo $this->Paginator->next(__('siguiente') . ' >', array(), null, array('class' => 'next disabled'));
      ?>
    </div>
  <?php } ?>
</div>
<div id="popInCrearEventCli">

</div>
<!-- Inicio Popin Preparar papeles de Trabajo -->
<a href="#x" class="overlay" id="PIPrepararPapeles"></a>
<div class="popup">
  <div class='section body' style="width:600px">
      <div id="form_prepararPapeles" class="prepararPapeles form">
      <fieldset>
        <legend><?php echo __('Preparar Papeles de Trabajo'); ?></legend>
        <?php         
       
          echo $this->Form->input('eventId',array('default'=>"",'type'=>'hidden'));
          echo $this->Form->input('tarea',array('default'=>"",'type'=>'hidden'));
          echo $this->Form->input('clienteid',array('default'=>"",'type'=>'hidden'));
          echo $this->Form->input('periodo',array('default'=>"",'type'=>'hidden'));
          echo $this->Form->input('impcliid',array('default'=>"",'type'=>'hidden'));
          //,'type'=>'hidden'
           echo $this->Form->input('fchvto', array(
                      'class'=>'datepicker', 
                      'type'=>'text',
                      'label'=>'Fecha de Vencimiento',
                      'readonly','readonly')
           );

          echo $this->Form->input('montovto',array('label'=>'Monto a Pagar','default'=>"0"));
          echo $this->Form->input('monc',array('label'=>'Monto a Favor','default'=>"0"));
          echo $this->Form->input('descripcion',array('default'=>"-"));        
        ?>
      </fieldset>
      <a href="#"  onclick="enviarPrepararPapeles()" class="btn_aceptar">  Agregar </a>
    </div>
  </div>
  <a class="close" href="#close"></a>
</div>
<!-- Fin Popin Preparar papeles de Trabajo  --> 
<!-- Inicio Popin Pagar -->
  <a href="#x" class="overlay" id="PIPagar" ></a>
  <div class="popup" style="width:1200px" >
    <a class="close" href="#close"></a>
      <div id="form_pagar" class="pagar form" >
        
        <a href="#"  onclick="enviarPagar()" class="btn_aceptar">  Agregar </a>
    </div>
    <a class="close" href="#close"></a>
  </div>  
</div>
<!-- Fin Popin Pagar--> 

<!-- Inicio Popin getPrepararPapeles -->
<a href="#x" class="overlay" id="popInPapelesDeTrabajo"></a>
<div  class="popup" style="width:65%; padding:0px;" id="divpopPapelesDeTrabajo">

  <a class="close" href="#close"></a>
</div>
<a href="#x" class="overlay" id="popInSolicitar"></a>
<div  class="popup" style="width:auto; max-height:450px; overflow:auto" id="divpopSolicitar">
  <div class="honorarios form">
  
  </div>
  <div class="recibos form" id="divFormAddRecibo">

  </div>
  <a class="close" href="#close"></a>
</div>
<a href="#x" class="overlay" id="popInInformar"></a>
<div  class="popup" style="width:auto" id="divpopInformar">
  <div class="deposito form">
 
  </div>
  <a class="close" href="#close"></a>
</div>
<?php 
function mostrarEventoCliente($context, $evento, $periodoSel, $tareaNombre, $tareaFild, $cliente,$Tareahabilitado,$HayImpuestosHabilitados){
    //Recorremos el evento de este periodo (supuestamente vendra uno solo por cada impuesto del cliente)
    //Si el evento en esta tarea esta ""PENDIENTE""
    $eventoID = 0;
    $params="";
    if($evento!=null){
      $eventoID = $evento['id'];
    }
    $class = 'pendiente';
    if($evento==null||$evento[$tareaFild]=='pendiente'){
        if($tareaFild=="tarea4"){
          $class = '';
        }else{
          $class = 'pendiente';
        }
    }  else if($evento[$tareaFild]=='realizado') {
       $class = 'realizado';
    }  
    $impuestoclienteStyle = "style = ' ";
    if($tareaFild=="tarea1"){
      $impuestoclienteStyle = "style = 'text-align: left; width:130px; border-right: solid 1px white;";
    }
    if(!$HayImpuestosHabilitados){
       $impuestoclienteStyle .= "background-color:#F0F0F0; ";
    }
    $impuestoclienteStyle .= "'";

    $params= $eventoID.",'".$tareaFild."','".$periodoSel."','".$cliente['Cliente']['id']."','realizado'"; ?>   

      <?php
      //Si hay impuestos habilitados para este cliente en este periodo
      if(!$HayImpuestosHabilitados){
          $classes = $tareaFild." ";
          if($tareaFild=="tarea3"){
              $classes .= " columnClienteBody";
          }else{
              $classes .= " columnClienteBodyMedio";
          }

          ?>
          <td class="<?php echo $classes ?> ">
              <div class="divtareas">
                  <div class="divTareaNombre">
                      <?php echo $tareaNombre; ?>
                  </div>
              </div>
          </td>
          <?php
      }else{
        if($Tareahabilitado) {
//          if($tareaFild=="tarea1"){
//            echo '<td class="'.$class.' '.$tareaFild.' columnClienteBody tdBotonesSolicitar" '.$impuestoclienteStyle.' id="cell'.$cliente['Cliente']['id'].'-'.$tareaFild.'">';
//            /**Tarea Solicitar**/
//            echo $context->Form->create('Eventoscliente',array(
//              'class'=>'formTareaCarga checkbox formTareaSolicitar',
//              'controller'=>'Eventosclientes',
//              'action'=>'tareasolicitar',
//              'id'=>'formEventoscliente'));
//            echo $context->Form->input('id', array('type'=>'hidden', 'value'=>$eventoID));
//            echo $context->Form->input('periodo', array('type'=>'hidden', 'value'=>$periodoSel));
//            echo $context->Form->input('cliente_id', array('type'=>'hidden', 'value'=>$cliente['Cliente']['id']));
//
//            if($cliente['Cliente']['cargaFacturaCompras']){
//              echo $context->Form->input('fccompra', array('type'=>'checkbox', 'div'=>false, 'label'=>false, 'style'=>'display:none','value'=>$evento['fccompra'],'class'=>$evento['fccompra']?'checked':''));?>
<!--              <img src="img/unchecked_checkbox.png" width = '15' height = '15' title="Facturas de Compras solicitadas y recibidas" id="blr" class="imgcb" />-->
<!--              Fc. de Compra-->
<!--              </br>-->
<!--              -->
<!--            --><?php
//            }
//            if($cliente['Cliente']['cargaLibroIVAVentas']){
//              echo $context->Form->input('libroivaventas', array('type'=>'checkbox', 'div'=>false, 'label'=>false, 'style'=>'display:none','value'=>$evento['libroivaventas'],'class'=>$evento['libroivaventas']?'checked':''));?>
<!--              <img src="img/unchecked_checkbox.png" width = '15' height = '15' title="Libro IVA Ventas solicitado y recibido" id="blr" class="imgcb" />-->
<!--              Libro IVA Ventas-->
<!--              </br>-->
<!--            --><?php //
//            }
//            if($cliente['Cliente']['cargaFacturaVentas']){
//              echo $context->Form->input('fcventa', array('type'=>'checkbox', 'div'=>false, 'label'=>false, 'style'=>'display:none','value'=>$evento['fcventa'],'class'=>$evento['fcventa']?'checked':''));?>
<!--              <img src="img/unchecked_checkbox.png" width = '15' height = '15' title="Facturas de Ventas solicitadas y recibidas" id="blr" class="imgcb" />-->
<!--            Fc. de Venta-->
<!--            </br>-->
<!--            --><?php
//            }
//            if($cliente['Cliente']['cargaVentasWeb']){
//              echo $context->Form->input('descargawebafip', array('type'=>'checkbox', 'div'=>false, 'label'=>false, 'style'=>'display:none','value'=>$evento['descargawebafip'],'class'=>$evento['descargawebafip']?'checked':''));?>
<!--              <img src="img/unchecked_checkbox.png" width = '15' height = '15' title="Descarga de ventas Web AFIP realizada" id="blr" class="imgcb" />-->
<!--            Descarg. Web AFIP-->
<!--            </br>-->
<!--            --><?php
//            }
//            if($cliente['Cliente']['cargaBanco']){
//              echo $context->Form->input('banco', array('type'=>'checkbox', 'div'=>false, 'label'=>false, 'style'=>'display:none','value'=>$evento['banco'],'class'=>$evento['banco']?'checked':''));?>
<!--              <img src="img/unchecked_checkbox.png" width = '15' height = '15' title="Resumenes Bancarios descargados" id="blr" class="imgcb" />-->
<!--            Resumen Bco.-->
<!--            </br>-->
<!--            --><?php //
//            }
//            if($cliente['Cliente']['cargaTarjetasCredito']){
//              echo $context->Form->input('tarjetadecredito', array('type'=>'checkbox', 'div'=>false, 'label'=>false, 'style'=>'display:none','value'=>$evento['tarjetadecredito'],'class'=>$evento['tarjetadecredito']?'checked':''));?>
<!--              <img src="img/unchecked_checkbox.png" width = '15' height = '15' title="Resumenes de Tarjetas de Creditos descargados" id="blr" class="imgcb" />-->
<!--            Resumen Tarj. de Cred.-->
<!--            </br>-->
<!--            --><?php //
//            }
//            if($cliente['Cliente']['cargaFacturaLuz']){
//              echo $context->Form->input('fcluz', array('type'=>'checkbox', 'div'=>false, 'label'=>false, 'style'=>'display:none','value'=>$evento['fcluz'],'class'=>$evento['fcluz']?'checked':''));?>
<!--              <img src="img/unchecked_checkbox.png" width = '15' height = '15' title="Facturas de Luz solicitadas y recibidas" id="blr" class="imgcb" />-->
<!--            Fc de Luz-->
<!--            </br>-->
<!--            --><?php //
//            }
//            if($cliente['Cliente']['cargaNovedades']){
//              echo $context->Form->input('sueldos', array('type'=>'checkbox', 'div'=>false, 'label'=>false, 'style'=>'display:none','value'=>$evento['sueldos'],'class'=>$evento['sueldos']?'checked':''));?>
<!--              <img src="img/unchecked_checkbox.png" width = '15' height = '15' title="Recibos de sueldos solicitados y recibidos" id="blr" class="imgcb" />-->
<!--            Novedades Sueldos-->
<!--            </br>-->
<!--            --><?php //
//            }
//            if($cliente['Cliente']['cargaLibroUnico']){
//              echo $context->Form->input('librounico', array('type'=>'checkbox', 'div'=>false, 'label'=>false, 'style'=>'display:none','value'=>$evento['librounico'],'class'=>$evento['librounico']?'checked':''));?>
<!--              <img src="img/unchecked_checkbox.png" width = '15' height = '15' title="Libro Unico Firmado" id="blr" class="imgcb" />-->
<!--            Libro Unico-->
<!--            --><?php //}
//            echo $context->Form->end();
//          }else
            if($tareaFild=="tarea3"){
                echo '<td class="'.$class.' '.$tareaFild.' columnClienteBody tdBotonesCarga" '.$impuestoclienteStyle.' id="cell'.$cliente['Cliente']['id'].'-'.$tareaFild.'">';                  
                    ?>
              <div class="divtareas">
                  <div class="divTareaNombre">
                      <?php echo $tareaNombre; ?>
                  </div>
                  <div style="max-height:210px; overflow:auto">
                    <?php
                    //En esta tarea vamos a cargar las ventas, compras y novedades del cliente en un periodo determinado
                  $paramsCargar= $eventoID.",'".$periodoSel."','".$cliente['Cliente']['id']."'";
                  $confImg=array('width' => '20', 'height' => '20', 'title' => 'Pendiente' ,'onClick'=>"verFormCargar(".$paramsCargar.")");
                  //echo $context->Html->image('ic_add_circle_outline_black_18dp.png',$confImg);
                    $onclickcompras = "verFormCargarCompras('".$cliente['Cliente']['id']."','".$periodoSel."')";
                    $onclickventas = "verFormCargarVentas('".$cliente['Cliente']['id']."','".$periodoSel."')";
                    $titlecompras = "Se puede cargar compras por que esta configurado Ganancias";
                    $titleventas = "Se puede cargar ventas por que esta configurado Ganancias";
                    //solo se debe poder hacer compras si se configuro ganancias, sino NO!
                    //a menos que sea monotributista y no tenga ganancias activado tonces si
                    $tieneGananciasConfigurado = 1;
                    foreach ($cliente['Actividadcliente'] as $actividadcliente) {
                        if(count($actividadcliente['Cuentasganancia'])>0){
                            $tieneGananciasConfigurado *= 1;
                        }else{
                            $tieneGananciasConfigurado *= 0;
                        }
                    }
                    if($tieneGananciasConfigurado==0){
                        //si tiene monotributo pero NO ganancias dejalo cargar
                        if($cliente['impuestosactivos']['monotributo']&&!$cliente['impuestosactivos']['ganancias']){
                            $titlecompras = "Se puede cargar compras por que es monotributista y no tiene ganancias";
                            $titleventas = "Se puede cargar ventas por que es monotributista y no tiene ganancias";
                        }else{
                            $titlecompras = "NO se puede cargar por que no tiene configurado Ganancias";
                            $titleventas = "NO se puede cargar por que no tiene configurado Ganancias";
                            $onclickcompras="callAlertPopint('Antes de cargar Compras Por Favor configurar el impuesto Ganancias para este contribuyente');";
                            $onclickventas="callAlertPopint('Antes de cargar Ventas Por Favor configurar el impuesto Ganancias para este contribuyente');";
                        }
                    }

                    $buttonclass = "buttonImpcliListo";
                    if($evento['ventascargadas']){
                      $buttonclass = "buttonImpcliRealizado";
                    }
                    echo $context->Form->button(
                        "Ventas",
                        array(
                            'title'=>$titleventas,
                            'class'=>$buttonclass.' progress-button state-loading',
                            'onClick'=>$onclickventas,
                            'id'=>'buttonCargaVenta'.$cliente['Cliente']['id'],
                        ),
                        array()
                    );

                    $buttonclass = "buttonImpcliListo";
                    if($evento['comprascargadas']){
                      $buttonclass = "buttonImpcliRealizado";
                    }

                    echo $context->Form->button(
                        "Compras",
                        array(
                            'title'=>$titlecompras,
                            'class'=>$buttonclass.' progress-button state-loading',
                            'onClick'=>$onclickcompras,
                            'id'=>'buttonCargaCompra'.$cliente['Cliente']['id'],
                        ),
                        array());
                    $buttonclass = "buttonImpcliListo";
                    if($evento['pagosacuentacarados']){
                      $buttonclass = "buttonImpcliRealizado";
                    }
                    echo $context->Form->button(
                        "Pagos a Cuentas",
                        array(
                            'class'=>$buttonclass.' progress-button state-loading',
                            'onClick'=>"verFormCargarPagosacuentas('".$cliente['Cliente']['id']."','".$periodoSel."')",
                            'id'=>'buttonPagosacuentas'.$cliente['Cliente']['id'],
                        ),
                        array());
                    $buttonclass = "buttonImpcliListo";
                    if($evento['novedadescargadas']){
                      $buttonclass = "buttonImpcliRealizado";
                    }
                    if($cliente['impuestosactivos']['tieneEmpleados']) {
                        echo $context->Form->button(
                            "Sueldos",
                            array(
                                'class' => $buttonclass . ' progress-button state-loading',
                                'onClick' => "verFormCargarNovedades('" . $cliente['Cliente']['id'] . "','" . $periodoSel . "')",
                                'id' => 'buttonCargaNovedade' . $cliente['Cliente']['id'],
                            ),
                            array());
                    }
                     if($cliente['impuestosactivos']['tienebanco']) {

                         $buttonclass = "buttonImpcliListo";
                         if ($evento['bancoscargados']) {
                             $buttonclass = "buttonImpcliRealizado";
                         }
                         echo $context->Form->button(
                             "Bancos",
                             array(
                                 'class' => $buttonclass . ' progress-button state-loading',
                                 'onClick' => "verFormCargarBancos('" . $cliente['Cliente']['id'] . "','" . $periodoSel . "')",
                                 'id' => 'buttonCargaBancos' . $cliente['Cliente']['id'],
                             ),
                             array());
                     }
                    $buttonclass = "buttonImpcliListo";
                    if($evento['honorarioscargador']){
                      $buttonclass = "buttonImpcliRealizado";
                    }
                    echo $context->Form->button(
                        "Honorarios",
                        array(
                            'class'=>$buttonclass.' progress-button state-loading',
                            'onClick'=>"verFormHonorarios('".$cliente['Cliente']['id']."','".$periodoSel."')",
                            'id'=>'buttonCargaHonorarios'.$cliente['Cliente']['id'],
                        ),
                        array());
                    $buttonclass = "buttonImpcliListo";
                    if($evento['reciboscargados']){
                      $buttonclass = "buttonImpcliRealizado";
                    }
                    echo $context->Form->button(
                        "Recibos",
                        array(
                            'class'=>$buttonclass.' progress-button state-loading',
                            'onClick'=>"verFormDepositos('".$cliente['Cliente']['id']."','".$periodoSel."')",
                            'id'=>'buttonCargaRecibos'.$cliente['Cliente']['id'],
                        ),
                        array());
                    ?>
                    </div>
              </div>
            </td>
            <?php
          }
          else  if($tareaFild=="tarea4"){

            echo '<td class="'.$class.' '.$tareaFild.' columnClienteBody tdBotonesControlar" '.$impuestoclienteStyle.' id="cell'.$cliente['Cliente']['id'].'-'.$tareaFild.'">';

          } else if ($tareaFild=="tarea14"){
            echo '<td class="'.$class.' '.$tareaFild.' columnClienteBodyMedio" '.$impuestoclienteStyle.' id="cell'.$cliente['Cliente']['id'].'-'.$tareaFild.'">';
              echo '<div class="divtareas">';
              ?>
              <div class="divTareaNombre">
                  <?php echo $tareaNombre; ?>
              </div>
              <div style="max-height:210px; overflow:auto">
              <?php
                //En esta tarea vamos a crear el Honorario
                $honorario = array();
                $honorarioCreado=false;

                foreach ($cliente["Honorario"] as $vhonorario){
                  $honorario = $vhonorario;
                  $honorarioCreado=true;
                };
                if($honorarioCreado){
                  $paramsSolicitar= $eventoID.",'".$tareaFild."','".$periodoSel."','".$cliente['Cliente']['id']."','".$cliente['Cliente']['nombre']."','realizado','".$honorario['id']."','".$honorario['monto']."','".$honorario['fecha']."','".$honorario['descripcion']."'";
                }else{
                  $paramsSolicitar= $eventoID.",'".$tareaFild."','".$periodoSel."','".$cliente['Cliente']['id']."','".$cliente['Cliente']['nombre']."','realizado','0','0','".date("d-m-Y")."',''";
                }

                $confImg=array('width' => '20', 'height' => '20', 'title' => 'Pendiente' ,'onClick'=>"verFormInformar(".$paramsSolicitar.")");
    //            echo $context->Html->image('ic_add_circle_outline_black_18dp.png',$confImg);

                $buttonclass = "buttonImpcliRealizado";

                 echo $context->Form->create('clientes',array('action' => 'informefinancierotributario','target'=>'_blank'));

                    echo $context->Form->input('gclis', array(
                        'type' => 'hidden',
                        'value' => $cliente['Cliente']['grupocliente_id']
                    ));

                    echo $context->Form->input('periodomes', array(
                        'type' => 'hidden',
                        'value' => substr($periodoSel, 0, 2)
                    ));

                    echo $context->Form->input('periodoanio', array(
                                'type' => 'hidden',
                                'value' => substr($periodoSel, 3, 6)
                            )
                        );
                    $options = array(
                      'label' => "Inf. Tributario Financ.",
                      'type' => 'button',
                      'div' => false,
                      'class' => $buttonclass.' progress-button state-loading',
                      'style'=>'color:#1e88e5',
                      'onClick' => 'submitparent(this)',
                    );
                echo $context->Form->end($options);

                if($evento['novedadescargadas']){
                  $buttonclass = "buttonImpcli4";
                }
              if($cliente['impuestosactivos']['tienebanco']){
                  $buttonclass="buttonImpcliRealizado  progress-button state-loading";
                  $paramsPrepPapeles= "'".$cliente['Cliente']['id']."','".$periodoSel."'";

                  echo $context->Form->button(
                      'Acreditaciones depuradas',
                      [
                          'class'=>$buttonclass,
                          'style'=>'color:#1e88e5',
                          'onClick'=>"verCuentasDepuradas(".$paramsPrepPapeles.")",
                          'id'=>'buttonCuentaDepurada'.$cliente['Cliente']['id'],
                      ],
                      []
                  );
                }
                ?>
                </div>
              </div>
            </td>
            <?php
          } else {
            echo '<td class="'.$class.' '.$tareaFild.' columnClienteBody" '.$impuestoclienteStyle.' id="cell'.$cliente['Cliente']['id'].'-'.$tareaFild.'">';
            $confImg=array('width' => '20', 'height' => '20', 'title' => 'Pendiente','onClick'=>"");
            echo $context->Html->image('ic_add_circle_outline_black_18dp.png',$confImg);
          }
        } else{
            echo '<td class="'.$class.' '.$tareaFild.' columnClienteBody" '.$impuestoclienteStyle.' id="cell'.$cliente['Cliente']['id'].'-'.$tareaFild.'">';
            $confImg=array('width' => '20', 'height' => '20', 'title' => 'Pendiente','onClick'=>"noHabilitado()");
            echo $context->Html->image('ic_add_circle_outline_black_18dp.png',$confImg);
        }
      }?>
      </div>
    </td>
    <?php 
}
function mostrarBotonImpuesto($context, $cliente, $impcli,$montoevento, $periodoSel,$pagado,$eventoNoCreado){
  $impuestoActivo = false;
  foreach ($impcli['Periodosactivo'] as $periodoactivo) {
    $impuestoActivo = true;
  }
    //todo mostrar los impuestos que si tienen vencimientos
    /*Lista de Impuestos Anuales y su vencimiento
        *Participaciones Societarias
        -igual que bienes personales(solo pers juridicas)
        // Anticipos [NO tiene]*/
   //Este switch case va a ver si hay q mostrar el impuesto evaluando si hay vencimiento
    $mostrarEnEstePeriodo=true;
    $diamesCorteEjFiscal = $cliente['Cliente']['fchcorteejerciciofiscal'];
    $pemes = substr($periodoSel, 0, 2);
    $peanio = substr($periodoSel, 3);
    $peDiaCorte = substr($diamesCorteEjFiscal, 0, 2);
    $peMesCorte = substr($diamesCorteEjFiscal, 3);
    $fecha = $peanio."-".$peMesCorte."-28";
    try{
        $DateTimeFecha=new DateTime($fecha);
        
        $DateTimeperiodo=new DateTime($peanio.'-'.$pemes.'-01');
        if( $DateTimeFecha> $DateTimeperiodo ){
            $fecha = ($peanio*1 -1)."-".$peMesCorte."-28";
        }
        //si la fecha es superior al dia 1 del periodo q estamos viendo esta to mal loco hay q volver 1 año a la fecha
        $periodoSelAprobado=false;
        $periodosAprobado = [];
        $periodosAprobado[4] = date("m-Y", strtotime($fecha." +4 month"));
        $periodosAprobado[5] = date("m-Y", strtotime($fecha." +5 month"));
        $periodosAprobado[6] = date("m-Y", strtotime($fecha." +6 month"));
        $periodosAprobado[7] = date("m-Y", strtotime($fecha." +7 month"));
        $periodosAprobado[8] = date("m-Y", strtotime($fecha." +8 month"));
        $periodosAprobado[9] = date("m-Y", strtotime($fecha." +9 month"));
        $periodosAprobado[10] = date("m-Y", strtotime($fecha." +10 month"));
        $periodosAprobado[11] = date("m-Y", strtotime($fecha." +11 month"));
        $periodosAprobado[12] = date("m-Y", strtotime($fecha." +12 month"));
        $periodosAprobado[13] = date("m-Y", strtotime($fecha." +13 month"));
        $periodosAprobado[14] = date("m-Y", strtotime($fecha." +14 month"));
        if (in_array($periodoSel, $periodosAprobado, true )){
            $periodoSelAprobado=true;
        }
        $esPersonaFisica=false;
        $esPersonaJuridica=false;
        if($cliente['Cliente']['tipopersona']=='fisica'){
            $esPersonaFisica=true;
        }
        if($cliente['Cliente']['tipopersona']=='juridica'){
            $esPersonaJuridica=true;
        }
        switch ($impcli['impuesto_id']){
            case '28':
                /*Ganancia Mínima Presunta
                -Junio (Si pers Fisica)  o 5to A partir del mes de cierre ( si pers juridica)
                // Anticipos [ Igual A Ganancias Fisica o Soc segun corresponda ]*/
                if($esPersonaFisica){
                    //vence Solo en Junio,pero se muestra un mes antes
                    if($periodoSel!='06-'.$peanio){
                        $mostrarEnEstePeriodo=false;
                    }
                }
                if($esPersonaJuridica){
                    if(!$periodoSelAprobado){
                        //entonces el periodo que queremos mostrar NO esta en uno de estos "meses aprobados"
                        // y no debo mostrarlo
                        $mostrarEnEstePeriodo=false;
                    }
                }
                break;
            case '16':/*BP - Acciones o Participaciones*
             BP - Acciones o Participaciones(Este impuesto solo se usa para pers juridicas)
             -Mayo*/
                // Anticipos [NO tiene]
                if($esPersonaJuridica){
                    //vence Solo en Junio,pero se muestra un mes antes
                    if($periodoSel!='05-'.$peanio){
                        $mostrarEnEstePeriodo=false;
                    }
                }
                break;
            case '5':/*Ganancias Sociedades
            -5to A partir del mes de cierre
            // Anticipos [10 Anticipos que empiezan a partir del mes siguiente en el que se paga el imp]*/
                if(!$periodoSelAprobado){
                    //entonces el periodo que queremos mostrar NO esta en uno de estos "meses aprobados"
                    // y no debo mostrarlo
                    $mostrarEnEstePeriodo=false;
                }
                break;
            case '160':/*Ganancias Personas Físicas*/
                $periodosAprobado = [];
                $periodosAprobado[] = "06-".$peanio;//por junio
                $periodosAprobado[] = "07-".$peanio;//por julio
                $periodosAprobado[] = "08-".$peanio;//por agosto
                $periodosAprobado[] = "10-".$peanio;//por Octubre
                $periodosAprobado[] = "12-".$peanio;//por diciembre
                $periodosAprobado[] = "02-".$peanio;//por febrero
                if (in_array($periodoSel, $periodosAprobado, true )){
                    $mostrarEnEstePeriodo=true;
                }
                break;
            case '159':/*Impto. s/Bienes Personales
                -Abril (si Part Soc)
                // Anticipos [5 Anticipos Junio - Agosto - Octubre - Diciembre - Febrero]*/
                $periodosAprobado = [];
                $periodosAprobado[] = "05-".$peanio;//por junio
                $periodosAprobado[] = "07-".$peanio;//por agosto
                $periodosAprobado[] = "09-".$peanio;//por Octubre
                $periodosAprobado[] = "11-".$peanio;//por diciembre
                $periodosAprobado[] = "01-".$peanio;//por febrero
                if (in_array($periodoSel, $periodosAprobado, true )){
                    $mostrarEnEstePeriodo=true;
                }
                break;
            case '172':/*Participaciones Societarias*/
                break;
        }
    }
    catch(Exception $e){
       //no se pudo procesar la fecha
    }
    if($impuestoActivo&&$mostrarEnEstePeriodo){
        $paramsPrepPapeles= "'".$periodoSel."','".$impcli['id']."'";
        $buttonclass="buttonImpcliListo";
        $imgagenpt = "ptgrey";
        $imgagenpago = "pesogrey";
        if(!$eventoNoCreado/*Evento creado*/){
//            if($montoevento>=0)$buttonclass="buttonImpcliSaldoPositivo";
            if($montoevento>=0){
                $buttonclass="buttonImpcliRealizado";
                $imgagenpt = "ptgreen";
                $imgagenpago = "pesogreen";
            }
            if($montoevento<0){
                $buttonclass="buttonImpcliSaldoNegativo";
                $imgagenpt = "ptblue";
                $imgagenpago = "pesoblue";
            }

        }
        $showlabel=true;
        if(count($impcli["Eventosimpuesto"])>0){
            if($pagado){
                $buttonclass="buttonImpcliRealizado";
                $imgagenpt = "ptgreen";
                $imgagenpago = "pesogreen";
    //          $widthProgressBar=94;
            }else{
    //          $buttonclass="buttonImpcli2";
    //          $widthProgressBar=50;
            }
        }else{
            $showlabel=false;
        }
      if($showlabel) {
          $textoAMostrar = $impcli['Impuesto']['abreviacion'] . ': 
          <label style="color: inherit;display: initial">
            $' . number_format($montoevento, 2, ",", "."). '
          </label>';
      }else{
          $textoAMostrar = $impcli['Impuesto']['abreviacion'] . ' 
          <label style="color: inherit;display: initial">
          </label>';
      }
    ?>
    <div id="divEventoImpuesto" style="width:120px;position: relative;margin: 5px;float: left;min-height: 46px">
    <?php
    $impcliid=$impcli['id'];
    $periodo=$periodoSel;
    $onclick = 'callAlertPopint("Este impuesto no tiene Papel de trabajo")';;
    switch ($impcli['Impuesto']['id']) {
        case 4/*Monotributo*/:
            $onclick = 'verPapelDeTrabajoMonotributo('."'".$periodo."'".','."'".$impcliid."'".')';
            break;
        case 6/*Actividades Varias*/:
            $onclick = 'verPapelDeTrabajoActividadesVarias('."'".$periodo."'".','."'".$impcliid."'".')';
            break;
        case 10/*SUSS*/:
            $onclick = 'verPapelDeTrabajoSUSS('."'".$periodo."'".','."'".$impcliid."'".')';
            break;
        case 12/*Cooperadora Asistencial*/:
            $onclick = 'verPapelDeTrabajoCooperadoraAsistencial('."'".$periodo."'".','."'".$impcliid."'".')';
            break;
        case 14/*Autonomo*/:
            $onclick = 'verPapelDeTrabajoAutonomo('."'".$periodo."'".','."'".$impcliid."'".')';
            break;
        case 19/*IVA*/:
            $onclick = 'verPapelDeTrabajoIVA('."'".$periodo."'".','."'".$cliente['Cliente']['id']."'".')';
            break;
        case 21/*Actividades Economicas*/:
        case 174/*Convenio Multilateral*/:
        $onclick = 'verPapelDeTrabajoConvenioMultilateral('."'".$periodo."'".','."'".$impcliid."'".')';
            break;
        case 37/*Casas Particulares*/:
            $onclick = 'verPapelDeTrabajoCasasParticulares('."'".$periodo."'".','."'".$cliente['Cliente']['id']."'".')';
            break;
        default:
            if($impcli['Impuesto']['organismo']=='sindicato'){
                $onclick = 'verPapelDeTrabajoSindicato('."'".$periodo."'".','."'".$impcliid."'".')';
            }
            break;
    }

    $onclickbotonimpcli = "papelesDeTrabajo(".$paramsPrepPapeles.")";
    $onclickbotonpagar = "loadPagar('".$periodo."','".$impcliid."','".$cliente['Cliente']['id']."')";
    //si el impuesto es informativo se deberia marcar como realizado nada mas por que no se paga
    //a menos que tenga el papel de trabajo echo
    if($impcli['Impuesto']['tipo']=='informativo'){
        $onclickbotonimpcli = "marcarImpcliComoRealizado(".$paramsPrepPapeles.")";
        $onclickbotonpagar = 'callAlertPopint("Este impuesto no se paga")';
        $textoAMostrar = $impcli['Impuesto']['abreviacion'] . ' 
          <label style="color: inherit;display: initial">
          </label>';
    }

    echo $context->Form->button(
      $textoAMostrar,
    array(
      'data-style'=>"top-line",
      'class'=>$buttonclass." progress-button state-loading",
      'onClick'=>$onclickbotonimpcli,
      'id'=>'buttonImpCli'.$impcli['id'],
      'data-sort'=> $impcli['Impuesto']['orden'],
      'style'=> 'float:left;margin:0px;min-height: 45px;padding-right: 22px;',
    ),
    array()
    );
    $confImgPdt=array(
        'id'=>'impPT'.$impcli['id'],
        'width' => '22',
        'height' => '22',
        'title' => 'Abrir papel de Trabajo',
        'onClick'=> $onclick,
        'style'=>' position: absolute;top: 0px;right: 0px;float:right',
    );
    echo $context->Html->image($imgagenpt.".png",$confImgPdt);
    $confImgPago=array(
        'id'=>'impPago'.$impcli['id'],
        'width' => '22',
        'height' => '22',
        'title' => 'Pagar este impuesto',
        'onClick'=>$onclickbotonpagar,
        'style'=>' position: absolute;bottom: 0px;right: 0px;float:right',
    );
    echo $context->Html->image($imgagenpago.".png",$confImgPago);
    ?>
    </div>
    <?php
  }else{
    //echo $context->Form->button($impcli['Impuesto']['nombre'].'-$'.number_format($montoevento, 2, ",", "."),array('class'=>'buttonImpcliDesactivado'),array());
  }
}
?>
<!-- Popin Modal para edicion de ventas a utilizar por datatables-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" style="width:90%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
<!--            <span aria-hidden="true">&times;</span>-->
        </button>
        <h4 class="modal-title">Modal title</h4>
      </div>
      <div class="modal-body">
        <p>One fine body&hellip;</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <!--     <button type="button" class="btn btn-primary">Save changes</button>-->
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
