<?php
$tdClass = "tdViewVenta".$this->data['Venta']["id"];
if(!$mostrarForm) { ?>
    <?php
    if(isset($data)){
      echo json_encode($data);
    }else{      
      ?>
            <td class="<?php echo $tdClass?>"><?php echo date('d',strtotime($venta['Venta']["fecha"]))?></td>
            <td class="<?php echo $tdClass?>" title="<?php echo $venta["Puntosdeventa"]['nombre']?>-
                <?php echo $venta['Venta']["numerocomprobante"]?>"><?php echo $venta["Comprobante"]['nombre']?>-
                <?php if(isset($venta["Puntosdeventa"]["nombre"])) echo $venta["Puntosdeventa"]['nombre']?>-
                <?php echo $venta['Venta']["numerocomprobante"]?></td>

            <td class="<?php echo $tdClass?>"><?php echo $venta["Subcliente"]["cuit"]?></td>
            <td class="<?php echo $tdClass?>" title="<?php echo $venta["Subcliente"]["nombre"]?>"><?php echo $venta["Subcliente"]["nombre"]?></td>
            <td class="<?php echo $tdClass?>" title=""><?php echo $venta["Venta"]["condicioniva"]?></td>

            <td class="<?php echo $tdClass?>" title="<?php echo $venta["Actividadcliente"]["Actividade"]["nombre"]?>"><?php echo $venta["Actividadcliente"]["Actividade"]["nombre"]?></td>
            <?php
            echo $this->Form->input('actividadescategorias', [
                'type'=>'select',
                'options'=>$actividadesCategorias,
                'id'=>'jsonactividadescategorias',
                'div'=>[
                    'style'=>'display:none'
                ]
            ]);
            $tipogasto = isset($venta["Tipogasto"]["nombre"])?$venta["Tipogasto"]["nombre"]:"";
            ?>
            <td class="<?php echo $tdClass?> notPrintable" title="<?php echo $tipogasto?>">
                <?php echo $tipogasto?>
            </td><!--7-->

            <td class="<?php echo $tdClass?>" title="<?php echo $venta["Localidade"]["nombre"]?>"><?php echo $venta["Localidade"]['Partido']["nombre"].'-'.$venta["Localidade"]["nombre"]?></td>
            <?php
            if(!filter_var($venta['Venta']["tieneMonotributo"], FILTER_VALIDATE_BOOLEAN)){ ?>
                <td class="<?php echo $tdClass?>"><?php echo "%".number_format($venta['Venta']["alicuota"], 2, ",", ".")?></td>
                <td class="<?php echo $tdClass?>"><?php echo number_format($venta['Venta']["neto"], 2, ",", ".")?></td>
                <td class="<?php echo $tdClass?>"><?php echo "$".number_format($venta['Venta']["iva"], 2, ",", ".")?></td>
          <?php
            //Hay que AGREGAR TIPO DE DEBITOOO!!!!!
            //Hay que agregar Condicion frente al IVA ??     
        }   
        if(filter_var($venta['Venta']["tieneIVAPercepciones"], FILTER_VALIDATE_BOOLEAN)){?>
          <td class="<?php echo $tdClass?>"><?php echo "$".number_format($venta['Venta']["ivapercep"], 2, ",", ".")?></td>
          <?php
        }
        if(filter_var($venta['Venta']["tieneAgenteDePercepcionIIBB"], FILTER_VALIDATE_BOOLEAN)){?>
          <td class="<?php echo $tdClass?>"><?php echo "$".number_format($venta['Venta']["iibbpercep"], 2, ",", ".")?></td>
          <?php
        }
        if(filter_var($venta['Venta']["tieneAgenteDePercepcionActividadesVarias"], FILTER_VALIDATE_BOOLEAN)){?>
          <td class="<?php echo $tdClass?>"><?php echo "$".number_format($venta['Venta']["actvspercep"], 2, ",", ".")?></td>
          <?php
        }
        if(filter_var($venta['Venta']["tieneImpuestoInterno"], FILTER_VALIDATE_BOOLEAN)){?>
          <td class="<?php echo $tdClass?>"><?php echo "$".number_format($venta['Venta']["impinternos"], 2, ",", ".")?></td>
          <?php
        }
        if(!filter_var($venta['Venta']["tieneMonotributo"], FILTER_VALIDATE_BOOLEAN)){ ?>
            ?>
        <td class="<?php echo $tdClass?>"><?php echo "$".number_format($venta['Venta']["nogravados"], 2, ",", ".")?></td>
        <td class="<?php echo $tdClass?>"><?php echo "$".number_format($venta['Venta']["excentos"], 2, ",", ".")?></td>
            <?php } ?>
        <td class="<?php echo $tdClass?>"><?php echo "$".number_format($venta['Venta']["exentosactividadeseconomicas"], 2, ",", ".")?></td>
        <td class="<?php echo $tdClass?>"><?php echo "$".number_format($venta['Venta']["exentosactividadesvarias"], 2, ",", ".")?></td>
        <td class="<?php echo $tdClass?>"><?php echo number_format($venta['Venta']["total"], 2, ",", ".")?></td>
      <td class="<?php echo $tdClass?>">
        <?php 
        $paramsVenta=$venta['Venta']["id"];
        echo $this->Html->image('edit_view.png',array('width' => '20', 'height' => '20','onClick'=>"modificarVenta(".$paramsVenta.")"));
          echo $this->Html->image('eliminar.png',array('width' => '20', 'height' => '20','onClick'=>"eliminarVenta(".$paramsVenta.")"));?>
      </td>
<?php }
  }else{ ?>
    
    </td>
    <td colspan="15" id="tdventa<?php echo $venid?>" class="editTD">
      <?php 
      echo $this->Form->create('Venta',array(
          'controller'=>'Venta',
          'action'=>'edit',
          'id'=>'VentaFormEdit'.$this->data['Venta']['id'],
          'class'=>'formTareaCarga'
          )
      ); 
      echo $this->Form->input('id',array('type'=>'hidden'));
     
      echo $this->Form->input('cliente_id',array('type'=>'hidden'));
      
      echo $this->Form->input('ventafecha'.$this->data['Venta']['id'], array(
              'class'=>'datepicker-dia', 
              'type'=>'text',
              'label'=>'Fecha',
              'readonly'=>'readonly',
              'default'=>date('d',strtotime($this->data['Venta']['fecha'])),
              'style'=>"width:20px"
              )
       );                               
      //Aca tenemos que sacar los tipos de comprobantes que el cliente puede emitir
      echo $this->Form->input('comprobante_id', array(
            'label'=> 'Comprobante',
            'style'=>"width: 80px;"
      )); 
      echo $this->Form->input('puntosdeventa_id', array(
            'options' => $puntosdeventas,                              
            'label'=> 'Punto de venta',
            'style'=>'width:56px;'
            )); 
      echo $this->Form->input('numerocomprobante', array(
          'label'=> 'N° Comprobante',
            'style'=>'max-width:95px;width:95px;'
          ));    
      echo $this->Form->input('subcliente_id', array(
                  'options' => $subclientes, 
                  'label' => 'Cliente',
                  'required' => true,                             
                  'class' => 'chosen-select',    
                  'style'=>'width:176px;'
                  )
              );    
      echo $this->Form->input('condicioniva',array('type'=>'select','label'=>'Cond. IVA','options'=>$condicionesiva));
      echo $this->Form->input('actividadcliente_id',array(
        'label'=>'Actividad',
        'type'=>'select', 
        'options'=>$actividades,
        'style'=>'width:130px' 
        ));
      //si no tiene categorizado ganancias no se debe poder cargar ventas
      //a menos que sea monotributista y no tenga activado ganancias
      //aca tengo que recorrer las actividades del cliente para relacionar las actividades con sus categorias de
      // ganancias para que por javascript  las limite solo a las que tienen q ser
      $actividadesCategorias = [];
      foreach ($cliente['Actividadcliente'] as $actividadcliente) {
          foreach ($actividadcliente['Cuentasganancia'] as $cuentaganancia){
              $cuentaGananciaTraducida = "";
              switch ($cuentaganancia['categoria']){
                  case 'primeracateg':
                      $cuentaGananciaTraducida = "primera";
                      break;
                  case 'segundacateg':
                      $cuentaGananciaTraducida = "segunda";
                      break;
                  case 'terceracateg':
                      $cuentaGananciaTraducida = "tercera";
                      break;
                  case 'terceracateg45':
                      $cuentaGananciaTraducida = "tercera otros";
                      break;
                  case 'cuartacateg':
                      $cuentaGananciaTraducida = "cuarta";
                      break;
              }
              $actividadesCategorias[$actividadcliente['id']]= $cuentaGananciaTraducida;
          }
      }
      echo $this->Form->input('actividadescategorias', [
          'type'=>'select',
          'options'=>$actividadesCategorias,
          'id'=>'jsonactividadescategorias',
          'div'=>[
              'style'=>'display:none'
          ]
      ]);
      $display="content;";
      if(!filter_var($impuestosActivos['contabiliza'], FILTER_VALIDATE_BOOLEAN)){
          $display="none;";
      }
      echo $this->Form->input('tipogasto_id',array(
          'type'=>'select',
          'label'=>'Tipo Ingreso',
          'class'=>"chosen-select",
          'style' => 'width:150px; ',
          'div' => ['style'=>'display:'.$display],
      ));
      echo $this->Form->input('localidade_id',array(
        'label'=>'Localidad',
        'class'=>"chosen-select",
        'style'=>'width:130px' 
        ));    
      if(!filter_var($impuestosActivos['monotributo'], FILTER_VALIDATE_BOOLEAN)){ 

          echo $this->Form->input('alicuota',array(
            'label'=>'Alicuota',
            'default'=> $this->data['Venta']['alicuota'],
            'style'=>'width:55px' 
            ));    
          echo $this->Form->input('neto',array(
            'label'=>'Neto',
            'style'=>'max-width: 70px;' 
            ));    
          echo $this->Form->input('iva',array(
            'label'=>'IVA',
            'style'=>'max-width: 70px;' 
             ));    
      }else{
          
          echo $this->Form->input('alicuota',array(
            'label'=>'Alicuota',
            'default'=> $this->data['Venta']['alicuota'],
            'style'=>'width:55px' ,
               'type'=>'hidden'
            ));    
          echo $this->Form->input('neto',array(
            'label'=>'Neto',
            'style'=>'max-width: 70px;' ,
               'type'=>'hidden'
            ));    
          echo $this->Form->input('iva',array(
            'label'=>'IVA',
            'style'=>'max-width: 70px;' ,
               'type'=>'hidden'
             ));    
      }   
      if(filter_var($impuestosActivos['ivapercepciones'], FILTER_VALIDATE_BOOLEAN)){
          echo $this->Form->input('ivapercep',array(
            'label'=>'IVA Percep.',
          'style'=>'max-width: 70px;' 
           ));    
      }
      if(filter_var($impuestosActivos['agenteDePercepcionIIBB'], FILTER_VALIDATE_BOOLEAN)){
          echo $this->Form->input('iibbpercep',array(
            'label'=>'IIBB Percep.',
            'style'=>'max-width: 70px;' 
             ));    
      }
      if(filter_var($impuestosActivos['agenteDePercepcionActividadesVarias'], FILTER_VALIDATE_BOOLEAN)){
          echo $this->Form->input('actvspercep',array(
            'label'=>'Act.Vs. Persep.',
            'style'=>'max-width: 70px;' 
             ));
      }
      if(filter_var($impuestosActivos['impuestointerno'], FILTER_VALIDATE_BOOLEAN)){
          echo $this->Form->input('impinternos',array(
            'label'=>'Imp. Internos',
            'style'=>'max-width: 70px;' 
             )); 
      }    
      /*AFIP*/
      echo $this->Form->input('periodo',array('type'=>'hidden'));
      echo $this->Form->input('tieneMonotributo',array('value'=>$impuestosActivos['monotributo'],'type'=>'hidden'));
      echo $this->Form->input('tieneIVA',array('value'=>$impuestosActivos['iva'],'type'=>'hidden'));
      echo $this->Form->input('tieneIVAPercepciones',array('value'=>$impuestosActivos['ivapercepciones'],'type'=>'hidden'));
      echo $this->Form->input('tieneImpuestoInterno',array('value'=>$impuestosActivos['impuestointerno'],'type'=>'hidden'));
      /*DGR*/
      echo $this->Form->input('tieneAgenteDePercepcionIIBB',array('value'=>$impuestosActivos['agenteDePercepcionIIBB'],'type'=>'hidden'));
      /*DGRM*/
      echo $this->Form->input('tieneAgenteDePercepcionActividadesVarias',array('value'=>$impuestosActivos['agenteDePercepcionActividadesVarias'],'type'=>'hidden'));
      if(!filter_var($impuestosActivos['monotributo'], FILTER_VALIDATE_BOOLEAN)) {
          echo $this->Form->input('nogravados', array(
              'label' => 'No Gravados',
              'style' => 'max-width: 70px;'
          ));
          echo $this->Form->input('excentos', array(
              'label' => 'Exentos',
              'style' => 'max-width: 70px;'
          ));
      }else{
          echo $this->Form->input('nogravados', array(
              'label' => 'No Gravados',
              'style' => 'max-width: 70px;',
              'type'=>'hidden'
          ));
          echo $this->Form->input('excentos', array(
              'label' => 'Exentos',
              'style' => 'max-width: 70px;',
              'type'=>'hidden'
          ));
      }
      echo $this->Form->input('exentosactividadeseconomicas',array(
          'label'=>'Act.Ec. Exentos',
          'style'=>'max-width: 70px;'
      ));
      echo $this->Form->input('exentosactividadesvarias',array(
          'label'=>'Act.Vs. Exentos',
          'style'=>'max-width: 70px;'
      ));
      echo $this->Form->input('total',array(
          'label'=>'Total',
          'style'=>'max-width: 70px;'
      ));
      echo $this->Form->submit('+', array(
                  'type'=>'image',
                  'src' => $this->webroot.'img/check.png',
                  'class'=>'imgedit',
                  'style'=>'width:25px;height:25px;')
                  );  
              echo $this->Form->end();  ?>  
    </td>     
<?php 
}//fin else del mostarinforme ?>                      
                    
                      
        