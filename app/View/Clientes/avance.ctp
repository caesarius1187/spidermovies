<?php echo $this->Html->script('clientes/avance',array('inline'=>false)); ?>
<?php echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false)); ?>
<input class="button" type="button" id="btnShowForm" onClick="showForm()" value="Mostrar" style="display:none" />

<div id="Formhead" class="clientes avanse index" style="width:99%; margin:0px 0px 8px 0px">

  <!--<input class="button" type="button" id="btnHiddeForm" onClick="hideForm()" value="Ocultar" style="float:right;"/>-->
  <?php 
  echo $this->Form->create('clientes',array('action' => 'avance')); ?> 
  <table class="">
    <tr>
      <td style ="width: 283px;">
        <?php
        echo $this->Form->input('gclis', array(
          //'multiple' => 'multiple',
            'type' => 'select',
            'class'=>'chosen-select filtroAvance',
            'label' => 'Clientes',
            'empty' => 'Filtrar por grupo de cliente'
        ));
        echo $this->Form->input('lclis', array(
          //'multiple' => 'multiple',
            'type' => 'select',
            'class'=>'chosen-select filtroAvance',
            'label' => false,
            'empty' => 'Filtrar por cliente'
        )); ?>
      </td><!--Clientes--><!--Grupo de Clientes-->
      <td style ="width: 283px;">
        <?php
        echo $this->Form->input('filtrodesolicitar', array(
          //'multiple' => 'multiple',
          'type' => 'select',
          'class'=>'chosen-select filtroAvance',
          'label' => 'Solicitar', 
          'empty' => 'Filtrar por elementos a solicitar', 
          'options'=> array(
            'banco'=>'Bancos',
            'tarjetadecredito'=>'Tarj. de Credito',
            'descargawebafip'=>'Descarga Web AFIP',
            'libroivaventas'=>'Libro IVA Ventas',
            'fcluz'=>'Fc. de Luz',
            'sueldos'=>'Novedades Sueldos',
            'librounico'=>'Libro Unico',
            )
        )); ?>
      </td><!--Solicitar-->
      <td>
        <?php
        echo $this->Form->input('filtrodeimpuestos', array(
          //'multiple' => 'multiple',
          'type' => 'select',
          'class'=>'chosen-select filtroAvance',
          'label' => 'Impuestos', 
          'empty' => 'Filtrar por impuestos', 
          'options'=> $impuestos
        )); ?>
      </td><!--Impuestos-->
      <td>
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
      </td><!--Mes-->
      <td>
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
                'default' =>  date("Y")
            )
        ); ?>
      </td><!--Año-->
      <td>
        <?php echo $this->Form->input('selectby',array('default'=>'none','type'=>'hidden'));// ?>
        <?php echo $this->Form->end(__('Aceptar')); ?>
      </td>
    </tr>
  </table>
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
    <thead>
      <?php /**************************************************************************/  ?>
      <?php /*****************************Row de tareas*****************************/  ?>
      <?php /**************************************************************************/  ?>
      <tr>
        <th valign="top" style='width:80px'><label style="width:100px"><?php echo 'Clientes'; ?></label></th>
          <?php foreach ($tareas as $tarea){ 
            $tareaStyle = "style = 'width:80px;";
            if('tarea'.$tarea["Tareasxclientesxestudio"]["tareascliente_id"]=="tarea1"){
              $tareaStyle = "style = 'text-align: left; width:130px; ";
            }
            $tareaStyle .= "'";?>
            <th valign="top" class="<?php echo 'tarea'.$tarea["Tareasxclientesxestudio"]["tareascliente_id"]; ?>" <?php echo $tareaStyle;?>>
              <label style="width:70px">
              <?php echo h($tarea["Tareascliente"]['nombre']); ?>
              </label>
            </th> 
        <?php }; ?>
      </tr>
    </thead>
    <?php /**************************************************************************/ ?>
    <?php /*****************************Recorremos los clientes**********************/ ?>
    <?php /**************************************************************************/ ?>
    <tbody>
      <?php 
      foreach ($clientes as $cliente){ 
        echo $this->Form->input('cliid'+$cliente['Cliente']['id'],array('type'=>'hidden','value'=>$cliente['Cliente']['id']));
         ?>
        <tr style="height:30px">
          <td style="height:30px;">
            <?php 
            /*echo $this->Html->link(
                $cliente['Grupocliente']['nombre'], 
                array('controller' => 'grupoclientes', 
                'action' => 'index'),
                array('style'=>'float:left'));*/ ?>
              <?php 
                   echo $this->Html->link(
                    $cliente['Cliente']['nombre'], 
                    array(
                      'controller' => 'clientes', 
                      'action' => 'view', 
                      $cliente['Cliente']['id']),
                    array('style'=>'float:left')
                  ); ?>
          </td>
          <?php
          $HayImpuestosHabilitados = false;
          //Aqui se pinta la caja que identifica a que impuesto pertenece cada row.
          foreach ($cliente["Impcli"] as $impcli){  
              if(Count($impcli['Periodosactivo'])!=0){ 
                $HayImpuestosHabilitados = true;
              }  
            }; 
          /**************************************************************************/ 
          /****************Recorremos las tareas una ves por cada evento de impuesto**/ 
          /**cliente chekiando que la tarea este habilitada para el usuario logueado**/
          /**********************y que tipo de tarea es*******************************/
          foreach ($tareas as $tarea){ 
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
                mostrarEventoCliente($this, $evento, $periodoSel, $tareaFild, $cliente,$Tareahabilitado,$HayImpuestosHabilitados);
                $eventoCreado=true;           
              }; 
              if(!$eventoCreado){
                mostrarEventoCliente($this, null, $periodoSel, $tareaFild, $cliente,$Tareahabilitado,$HayImpuestosHabilitados);
              }
            }
            if($tarea["Tareasxclientesxestudio"]['tipo']=="impuesto"){
              //tarea tipo impuesto ?>
              <td class=""> <!--Tbl 1.2-->
                  <?php 
                  $hayImpuestoRelacioado=false;
                  foreach ($cliente["Impcli"] as $impcli){ 
                    //Recorremos los impuestos de cada cliente
                    $hayImpuestoRelacioado=true;
                    $eventoNoCreado=true; 
                    //Recorremos los impuestos de cada cliente Chekiamos si el evento esta creado
                    $montovto = 0;
                    $pagado=true;
                    foreach ($impcli["Eventosimpuesto"] as $evento){
                      if($evento['periodo']==$periodoSel){
                        $eventoNoCreado=false;
                        $montovto += $evento['montovto']; 
                      }
                      if($evento['tarea13']=='pendiente'){
                          $pagado=false;
                      }
                    }
                    $tareaFild='tarea'.$tarea["Tareasxclientesxestudio"]["tareascliente_id"]; 
                   /*Como no podemos traer los impuestos ordenados por sql vamos a ordenarlos aqui*/
                    mostrarBotonImpuesto($this, $impcli,$montovto ,$periodoSel,$pagado) ;
                  } 
                 ?>
              </td>       
            <?php 
            }  
          } ?>
        </tr>
                        
        <tr><td colspan="50" style="height: 6px;"><hr color="#E4E4E4" width="100%" style="height: 2px;"></td> </tr>
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
function mostrarEventoCliente($context, $evento, $periodoSel, $tareaFild, $cliente,$Tareahabilitado,$HayImpuestosHabilitados){
    //Recorremos el evento de este periodo (supuestamente vendra uno solo por cada impuesto del cliente)
    //Si el evento en esta tarea esta ""PENDIENTE""

    $eventoID = 0;
    $params="";
    if($evento!=null){
      $eventoID = $evento['id'];
    }
    $class = 'pendiente';
    if($evento==null||$evento[$tareaFild]=='pendiente'){ 
       $class = 'pendiente';
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
    <td 
      class="<?php echo $class.' '.$tareaFild;?>" 
      <?php echo $impuestoclienteStyle; ?> 
      id="cell<?php echo $cliente['Cliente']['id'].'-'.$tareaFild; ?>">  
      <?php 
      //Si hay impuestos habilitados para este cliente en este periodo
      if(!$HayImpuestosHabilitados){
         $confImg=array('width' => '20', 'height' => '20', 'title' => 'Activar Impuestos','onClick'=>"noHabilitado('Activar Impuestos')");
      }else{
        if($Tareahabilitado) {
          if($tareaFild=="tarea1"){ 

            /**Tarea Solicitar**/     
            echo $context->Form->create('Eventoscliente',array(
              'class'=>'formTareaCarga checkbox formTareaSolicitar',
              'controller'=>'Eventosclientes',
              'action'=>'tareasolicitar',
              'id'=>'formEventoscliente'));
            echo $context->Form->input('id', array('type'=>'hidden', 'value'=>$eventoID));
            echo $context->Form->input('periodo', array('type'=>'hidden', 'value'=>$periodoSel));
            echo $context->Form->input('cliente_id', array('type'=>'hidden', 'value'=>$cliente['Cliente']['id']));

            if($cliente['Cliente']['cargaFacturaCompras']){
              echo $context->Form->input('fccompra', array('type'=>'checkbox', 'div'=>false, 'label'=>false, 'style'=>'display:none','value'=>$evento['fccompra'],'class'=>$evento['fccompra']?'checked':''));?>
              <img src="https://cdn2.iconfinder.com/data/icons/windows-8-metro-style/128/unchecked_checkbox.png" width = '15' height = '15' title="Facturas de Compras solicitadas y recibidas" id="blr" class="imgcb" />
              Fc. de Compra
              </br>
              
            <?php
            }
            if($cliente['Cliente']['cargaLibroIVAVentas']){
              echo $context->Form->input('libroivaventas', array('type'=>'checkbox', 'div'=>false, 'label'=>false, 'style'=>'display:none','value'=>$evento['libroivaventas'],'class'=>$evento['libroivaventas']?'checked':''));?>
              <img src="https://cdn2.iconfinder.com/data/icons/windows-8-metro-style/128/unchecked_checkbox.png" width = '15' height = '15' title="Libro IVA Ventas solicitado y recibido" id="blr" class="imgcb" />
              Libro IVA Ventas
              </br>
            <?php 
            }
            if($cliente['Cliente']['cargaFacturaVentas']){
              echo $context->Form->input('fcventa', array('type'=>'checkbox', 'div'=>false, 'label'=>false, 'style'=>'display:none','value'=>$evento['fcventa'],'class'=>$evento['fcventa']?'checked':''));?>
              <img src="https://cdn2.iconfinder.com/data/icons/windows-8-metro-style/128/unchecked_checkbox.png" width = '15' height = '15' title="Facturas de Ventas solicitadas y recibidas" id="blr" class="imgcb" />
            Fc. de Venta
            </br>
            <?php
            } 
            if($cliente['Cliente']['cargaVentasWeb']){
              echo $context->Form->input('descargawebafip', array('type'=>'checkbox', 'div'=>false, 'label'=>false, 'style'=>'display:none','value'=>$evento['descargawebafip'],'class'=>$evento['descargawebafip']?'checked':''));?>
              <img src="https://cdn2.iconfinder.com/data/icons/windows-8-metro-style/128/unchecked_checkbox.png" width = '15' height = '15' title="Descarga de ventas Web AFIP realizada" id="blr" class="imgcb" />
            Descarg. Web AFIP
            </br>
            <?php
            } 
            if($cliente['Cliente']['cargaBanco']){
              echo $context->Form->input('banco', array('type'=>'checkbox', 'div'=>false, 'label'=>false, 'style'=>'display:none','value'=>$evento['banco'],'class'=>$evento['banco']?'checked':''));?>
              <img src="https://cdn2.iconfinder.com/data/icons/windows-8-metro-style/128/unchecked_checkbox.png" width = '15' height = '15' title="Resumenes Bancarios descargados" id="blr" class="imgcb" />
            Resumen Bco.
            </br>
            <?php 
            } 
            if($cliente['Cliente']['cargaTarjetasCredito']){
              echo $context->Form->input('tarjetadecredito', array('type'=>'checkbox', 'div'=>false, 'label'=>false, 'style'=>'display:none','value'=>$evento['tarjetadecredito'],'class'=>$evento['tarjetadecredito']?'checked':''));?>
              <img src="https://cdn2.iconfinder.com/data/icons/windows-8-metro-style/128/unchecked_checkbox.png" width = '15' height = '15' title="Resumenes de Tarjetas de Creditos descargados" id="blr" class="imgcb" />
            Resumen Tarj. de Cred.
            </br>
            <?php 
            } 
            if($cliente['Cliente']['cargaFacturaLuz']){
              echo $context->Form->input('fcluz', array('type'=>'checkbox', 'div'=>false, 'label'=>false, 'style'=>'display:none','value'=>$evento['fcluz'],'class'=>$evento['fcluz']?'checked':''));?>
              <img src="https://cdn2.iconfinder.com/data/icons/windows-8-metro-style/128/unchecked_checkbox.png" width = '15' height = '15' title="Facturas de Luz solicitadas y recibidas" id="blr" class="imgcb" />
            Fc de Luz
            </br>
            <?php 
            } 
            if($cliente['Cliente']['cargaNovedades']){
              echo $context->Form->input('sueldos', array('type'=>'checkbox', 'div'=>false, 'label'=>false, 'style'=>'display:none','value'=>$evento['sueldos'],'class'=>$evento['sueldos']?'checked':''));?>
              <img src="https://cdn2.iconfinder.com/data/icons/windows-8-metro-style/128/unchecked_checkbox.png" width = '15' height = '15' title="Recibos de sueldos solicitados y recibidos" id="blr" class="imgcb" />
            Novedades Sueldos
            </br>
            <?php 
            } 
            if($cliente['Cliente']['cargaLibroUnico']){
              echo $context->Form->input('librounico', array('type'=>'checkbox', 'div'=>false, 'label'=>false, 'style'=>'display:none','value'=>$evento['librounico'],'class'=>$evento['librounico']?'checked':''));?>
              <img src="https://cdn2.iconfinder.com/data/icons/windows-8-metro-style/128/unchecked_checkbox.png" width = '15' height = '15' title="Libro Unico Firmado" id="blr" class="imgcb" />
            Libro Unico
            <?php }
            echo $context->Form->end();            
          }else  if($tareaFild=="tarea3"){
              //En esta tarea vamos a cargar las ventas, compras y novedades del cliente en un periodo determinado
              $paramsCargar= $eventoID.",'".$periodoSel."','".$cliente['Cliente']['id']."'";
              $confImg=array('width' => '20', 'height' => '20', 'title' => 'Pendiente' ,'onClick'=>"verFormCargar(".$paramsCargar.")");
              echo $context->Html->image('add.png',$confImg); 
          } else if ($tareaFild=="tarea14"){
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
            echo $context->Html->image('add.png',$confImg); 
          } else {
            $confImg=array('width' => '20', 'height' => '20', 'title' => 'Pendiente','onClick'=>"realizarEventoCliente(".$params.")");
            echo $context->Html->image('add.png',$confImg); 
          }
        } else{
          $confImg=array('width' => '20', 'height' => '20', 'title' => 'Pendiente','onClick'=>"noHabilitado()");
          echo $context->Html->image('add.png',$confImg); 
        } 
      }?>
    </td>
    <?php 
}
function mostrarBotonImpuesto($context,$impcli,$montoevento, $periodoSel,$pagado){
  $impuestoActivo = false;
  foreach ($impcli['Periodosactivo'] as $periodoactivo) {
    $impuestoActivo = true;
  }
  if($impuestoActivo){
    $paramsPrepPapeles= "'".$periodoSel."','".$impcli['id']."'";
    $buttonclass="buttonImpcli0";

    if(count($impcli["Eventosimpuesto"])>0){
        if($pagado){
            $buttonclass="buttonImpcli4";
        }else{
            $buttonclass="buttonImpcli2";
        }
    }
    echo $context->Form->button(
      $impcli['Impuesto']['abreviacion'].'</br><label>$'.number_format($montoevento, 2, ",", ".").'</label>',
      array(
        'class'=>$buttonclass,
        'onClick'=>"papelesDeTrabajo(".$paramsPrepPapeles.")",
        'id'=>'buttonImpCli'.$impcli['id'],
        'data-sort'=> $impcli['Impuesto']['orden'],
        ),
      array());

  }else{
    //echo $context->Form->button($impcli['Impuesto']['nombre'].'-$'.number_format($montoevento, 2, ",", "."),array('class'=>'buttonImpcliDesactivado'),array());
  }
}
function mostrarEventoImpuesto($context, $evento,$montovto, $tareaFild, $periodoSel, $cliente, $impcli, $Tareahabilitado){
  $impuestoActivo = false;
  foreach ($impcli['Periodosactivo'] as $periodoactivo) {
    $impuestoActivo = true;
  }
  $impuestoclienteStyle = "";
  if(!$impuestoActivo){
     $impuestoclienteStyle .= "style = 'background-color:#F0F0F0;'";
  }
  echo $context->Form->input('eventoID-cliid-'+$cliente['Cliente']['id']+'impclid-'+ $impcli["id"],array('type'=>'hidden','value'=>0)); 
  $tdClass = 'pendiente';
  $miEventoId=0;
  if($evento != null){
    $miEventoId=$evento['id'];
    if($evento[$tareaFild]=='pendiente'){
      $tdClass = 'pendiente';
    }else if($evento[$tareaFild]=='realizado'){
      $tdClass = 'realizado';
    }
  }else{
    $tdClass = 'pendiente';
    $miEventoId=0;
  }
  if(!$impuestoActivo){
      $confImg=array('width' => '20', 'height' => '20', 'title' => 'Pendiente','onClick'=>"noHabilitado()");?>
      <td class="<?php echo $tdClass.' '.$tareaFild; ?>" <?php echo $impuestoclienteStyle; ?> id="cellimp<?php echo $cliente['Cliente']['id'].'-'.$tareaFild.'-'.$impcli['id']; ?>" >
        <?php 
             echo $context->Html->image('add.png',$confImg);
        ?>                            
      </td>
    </td>
  <?php
    return;
  }
  //Si el evento esta ""PENDIENTE"" ?>          
  <td class="<?php echo $tdClass.' '.$tareaFild; ?>" id="cellimp<?php echo $cliente['Cliente']['id'].'-'.$tareaFild.'-'.$impcli['id']; ?>" >
    <?php $params= $miEventoId.",'".$tareaFild."','".$periodoSel."','".$cliente['Cliente']['id']."','".$impcli['id']."','realizado'"; ?>
    <?php $paramsPrepPapeles= "'".$periodoSel."','".$impcli['id']."'"; ?>

    <?php 
      if($Tareahabilitado) {
        //Aqui controlo si el evento esta que siendo realizado es uno que debe mostrar un popin 
        if($tareaFild=="tarea5"){
          //Tarea5 es "Prepar Papeles de Trabajo" debe mostrar popin para inicializar variables del pagoa realiar del impuesto
          echo $context->Html->image('edit.png',array('width' => '20', 'title' => 'Papeles de Trabajo','height' => '20','onClick'=>"papelesDeTrabajo(".$paramsPrepPapeles.")"));
          echo $context->Form->label("$".number_format($montovto, 2, ",", "."));    
          echo $context->Form->input('montotarea5',array('type'=>'hidden','value'=>$montovto,'id'=>'montoTarea5'+$cliente['Cliente']['id']+'-'+ $impcli["id"])); 
        }else if ($tareaFild=="tarea13") {
          //Tarea13 es "A Pagar" debe mostrar popin para cargar variables del pago realizado del impuesto
          echo $context->Html->image('add.png',array('width' => '20', 'title' => 'Pagar' , 'height' => '20','onClick'=>"showPagar(".$paramsPrepPapeles.")"));

        }else{
          echo $context->Html->image('add.png',array('width' => '20', 'title' => 'Cargar','height' => '20','onClick'=>"realizarEventoImpuesto(".$params.")"));
        }

      } else {
        //El evento no esta habilitado
         echo $context->Html->image('add.png',array('width' => '20', 'height' => '20','onClick'=>"noHabilitado()"));
      }              
    ?> 
  </td>
<?php } ?>