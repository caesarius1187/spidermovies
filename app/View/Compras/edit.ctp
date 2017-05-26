<?php
$tdClass = "tdViewCompra".$this->data['Compra']["id"];
if(!$mostrarForm) { ?>
    <?php
    if(isset($data)){
      echo json_encode($data);
    }else{      
      ?>
      <td class="<?php echo $tdClass?>"><?php echo date('d-m-Y',strtotime($this->data['Compra']["fecha"]))?></td>
      <td class="<?php echo $tdClass?>"><?php echo $this->data['Comprobante']["nombre"]?>-
      <?php echo $this->data["Compra"]['puntosdeventa']?>-
      <?php echo $this->data['Compra']["numerocomprobante"]?></td>
      <td class="<?php echo $tdClass?>"><?php echo $this->data["Provedore"]["nombre"]?></td>
      <td class="<?php echo $tdClass?>"><?php echo $this->data['Compra']["condicioniva"]?></td>
      <td class="<?php echo $tdClass?>"><?php echo $this->data['Actividadcliente']["Actividade"]['nombre']?></td>
      <td class="<?php echo $tdClass?>"><?php echo $this->data["Localidade"]['Partido']["nombre"].'-'.$this->data["Localidade"]["nombre"]?></td>
      <td class="<?php echo $tdClass?>"><?php echo $this->data['Compra']["tipocredito"]?></td>
      <td class="<?php echo $tdClass?>"><?php echo $this->data["Tipogasto"]["nombre"]?></td>   
      <td class="<?php echo $tdClass?>"><?php echo $this->data['Compra']["tipoiva"]?></td>
      <td class="<?php echo $tdClass?>"><?php echo $this->data['Compra']["imputacion"]?></td>  
      <td class="<?php echo $tdClass?>"><?php echo "%".number_format($this->data['Compra']["alicuota"], 2, ",", ".")?></td>
      <td class="<?php echo $tdClass?>"><?php echo "$".number_format($this->data['Compra']["neto"], 2, ",", ".")?></td>
      <td class="<?php echo $tdClass?>"><?php echo "$".number_format($this->data['Compra']["iva"], 2, ",", ".")?></td>
      <td class="<?php echo $tdClass?>"><?php echo "$".number_format($this->data['Compra']["ivapercep"], 2, ",", ".")?></td>
      <td class="<?php echo $tdClass?>"><?php echo "$".number_format($this->data['Compra']["iibbpercep"], 2, ",", ".")?></td>
      <td class="<?php echo $tdClass?>"><?php echo "$".number_format($this->data['Compra']["actvspercep"], 2, ",", ".")?></td>
      <td class="<?php echo $tdClass?>"><?php echo "$".number_format($this->data['Compra']["impinternos"], 2, ",", ".")?></td>
      <td class="<?php echo $tdClass?>"><?php echo "$".number_format($this->data['Compra']["impcombustible"], 2, ",", ".")?></td>
      <td class="<?php echo $tdClass?>"><?php echo "$".number_format($this->data['Compra']["nogravados"], 2, ",", ".")?></td>
      <td class="<?php echo $tdClass?>"><?php echo "$".number_format($this->data['Compra']["exentos"], 2, ",", ".")?></td>
      <td class="<?php echo $tdClass?>"><?php echo number_format($this->data['Compra']["kw"], 2, ",", ".")."KW"?></td>
      <td class="<?php echo $tdClass?>"><?php echo "$".number_format($this->data['Compra']["total"], 2, ",", ".")?></td>
      <td class="<?php echo $tdClass?>"><?php echo "$".number_format($this->data['Compra']["total"], 2, ",", ".")?></td>
      <td class="<?php echo $tdClass?>">
        <?php 
        $paramsCompra=$this->data['Compra']["id"];
        echo $this->Html->image('edit_view.png',array('width' => '20', 'height' => '20','onClick'=>"modificarCompra(".$paramsCompra.")"));
        echo $this->Html->image('eliminar.png',array('width' => '20', 'height' => '20','onClick'=>"eliminarCompra(".$paramsCompra.")"));?>
      </td>
    <?php } ?>
<?php }else{ ?>
    <td colspan="20" id="tdcompra<?php echo $comid?>" style="overflow: inherit;">
        <div  >
      <?php echo $this->Form->create('Compra',array(
                            'controller'=>'Compra',
                            'action'=>'edit',
                            'id'=>'CompraFormEdit'.$this->data['Compra']['id'],
                            'class'=>'formTareaCarga'
                          )
      ); 
      echo $this->Form->input('id',array('type'=>'hidden'));
      echo $this->Form->input('cliente_id',array('type'=>'hidden'));
      echo $this->Form->input('fecha'.$this->data['Compra']['id'], array(
        'class'=>'datepicker', 
        'type'=>'text',
        'label'=>'Fecha',
        'value'=>date('d-m-Y',strtotime($this->data['Compra']["fecha"])),
        'readonly'=>'readonly',
        'required'=>true,
        'style'=>"width:64px;max-width:85px"
        )
         );            
      //Aca tenemos que sacar los tipos de comprobantes que el cliente puede emitir                              
      echo $this->Form->input('comprobante_id', array(
                  'style'=>'width:49px;padding:0;margin:0',
                  'options'=>$comprobantes
                  )
      ); 
      echo $this->Form->input('puntosdeventa', array(
          'style'=>'width:37px;padding:0;margin:0',
          'type'=>'text',
          'maxlength'=>'4',
          'label'=>'Punto de venta'
          )
      ); 
      echo $this->Form->input('numerocomprobante', array(
          'style'=>'width:53px;padding:0;margin:0',
          'label'=>'N° Comprobante'
          )
      );  
      echo $this->Form->input('provedore_id', array(
          'options' => $provedores,        
          'class'=>'chosen-select',  
          'label'=>'Provedor' ,
          'style'=>"width:101px"
          )
      );  
      echo $this->Form->input('condicioniva', array(                      
              'type'=>'select',
              'label'=>'Condicion IVA',
              'options'=>$condicionesiva,
              'style' => 'width:94px' 
              )
       );
      echo $this->Form->input('actividadcliente_id',array(
          'type'=>'select',
          'label'=>'Actividad',
          'options'=>$actividades,
          'style' => 'width:114px' 

          ));                
      echo $this->Form->input('localidade_id',array(
          'class'=>'chosen-select',     
          'label'=>'Localidad',
          'style' => 'width:84px' 
          )
      );    
      echo $this->Form->input('tipocredito',array(
            'label'=>'Tipo Credito',
            'options'=>$tipocreditos
        ));               
      echo $this->Form->input('tipogasto_id', array(                      
            'label'=>'Tipo Gasto',
            'style' => 'width:69px'
            )
      );   
      echo $this->Form->input('tipoiva',array(
          'label'=>'Tipo IVA',
          'options'=>array('directo'=>'Directo','prorateable'=>'Prorateable')
          ));    
      echo $this->Form->input('imputacion',array(
          'type'=>'select',
          'options'=>$imputaciones,
          ));
      echo $this->Form->input('alicuota',array(
          'options' => $alicuotas,
          'style'=>'width:60px'));
      echo $this->Form->input('neto',array(
          'style'=>'width: 85px; max-width: 68px;'
          ));    
      echo $this->Form->input('iva',array(
          'style'=>'width: 66px;max-width: 66px;'
          ));    
      echo $this->Form->input('ivapercep',array(
           'label'=> 'IVA percep.',
          ));    
      echo $this->Form->input('iibbpercep',array(
          'label'=> 'IIBB percep.',
        ));
      echo $this->Form->input('actvspercep',array(
          'label'=> 'Act.Vs. percep',
          'style'=>'max-width: 94px;width: 68px;'
        ));  
      echo $this->Form->input('impinternos',array(
         'label'=> 'Imp. internos',
        ));    
      echo $this->Form->input('impcombustible',array(
            'label'=> 'Imp. combustible',
          ));
      echo $this->Form->input('nogravados',array(
          'label'=> 'No gravados',
      ));
      echo $this->Form->input('exentos',array(
          'label'=> 'Exento',
      ));
      echo $this->Form->input('total',array(
          'label'=>'Total'
      ));
      echo $this->Form->input('kw',array(
            'label'=>'KW'
          ));
      $tipoautorizaciones=[
          'CAE'=>'CAE',
          'CAI'=>'CAI',
          'CAEA'=>'CAEA'
      ];
      echo $this->Form->input('tipoautorizacion',array(
          'options' => $tipoautorizaciones,
          'style'=>'width:60px'));
      echo $this->Form->input('autorizacion',array(
          'style'=>'width:100px'));
      echo $this->Form->input('asiento',array('type'=>'hidden'));
      echo $this->Form->input('periodo',array('type'=>'hidden'));
      /*AFIP*/
      echo $this->Form->input('tieneMonotributo',array('value'=>$tieneMonotributo,'type'=>'hidden'));
      echo $this->Form->input('tieneIVA',array('value'=>$tieneIVA,'type'=>'hidden'));
      echo $this->Form->input('tieneIVAPercepciones',array('value'=>$tieneIVAPercepciones,'type'=>'hidden'));
      echo $this->Form->input('tieneImpuestoInterno',array('value'=>$tieneImpuestoInterno,'type'=>'hidden'));
      /*DGR*/
      echo $this->Form->input('tieneAgenteDePercepcionIIBB',array('value'=>$tieneAgenteDePercepcionIIBB,'type'=>'hidden'));
      /*DGRM*/
      echo $this->Form->input('tieneAgenteDePercepcionActividadesVarias',array('value'=>$tieneAgenteDePercepcionActividadesVarias,'type'=>'hidden'));
      echo $this->Form->submit('+', array(
          'type'=>'image',
          'src' => $this->webroot.'img/check.png',
          'class'=>'imgedit',
          'style'=>'width:25px;height:25px;')
          );  
      echo $this->Form->end();  
   /* <a href="#" class="btn_cancelar" onClick="hideFormModCompra('<?php echo $this->data['Compra']['id'];?>')" style="float: left;width: 45px;margin: 0;">X</a>*/?>  
                  </div>
    </td>     
<?php } ?>                      
                    
                      
        