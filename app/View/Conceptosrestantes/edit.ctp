<?php
$tdClass = "tdViewConceptosrestante".$this->data['Conceptosrestante']["id"];
if(!$mostrarForm) { ?>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Impcli']['Impuesto']["nombre"]?></td>
    <td class="<?php echo $tdClass?>"><?php if(isset($this->data['Partido']["nombre"]))echo $this->data['Partido']["nombre"]?></td>
    <td class="<?php echo $tdClass?>"><?php if(isset($this->data['Localidade']['Partido']["nombre"]))echo $this->data['Localidade']['Partido']["nombre"]."-".$this->data['Localidade']["nombre"]?></td>
    <td class="<?php echo $tdClass?>"><?php
        if(
            $this->data['Impcli']['impuesto_id']=='19'/*IVA*/ &&
            $this->data['Conceptosrestante']['conceptostipo_id']==1 )
        {
            $this->data['Conceptostipo']["nombre"] = "Saldo de Libre Disponibilidad";
        }
        echo $this->data['Conceptostipo']["nombre"]?></td>
    <td class="<?php echo $tdClass?>"><?php if(isset($this->data['Comprobante']["nombre"]))echo $this->data["Comprobante"]["nombre"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Conceptosrestante']["numerocomprobante"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Conceptosrestante']["rectificativa"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Conceptosrestante']["razonsocial"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Conceptosrestante']["monto"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Conceptosrestante']["montoretenido"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Conceptosrestante']["cuit"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo date('d-m-Y',strtotime($this->data['Conceptosrestante']["fecha"]))?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Conceptosrestante']["numerodespachoaduanero"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Conceptosrestante']["anticipo"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Conceptosrestante']["cbu"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Conceptosrestante']["tipocuenta"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Conceptosrestante']["tipomoneda"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Conceptosrestante']["agente"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Conceptosrestante']["enterecaudador"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Conceptosrestante']["regimen"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Conceptosrestante']["descripcion"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Conceptosrestante']["numeropadron"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Conceptosrestante']["puntosdeventa"]?></td>
    <td class="<?php echo $tdClass?>"><?php echo $this->data['Conceptosrestante']["numerofactura"]?></td>
    <td class="<?php echo $tdClass?>">
        <?php
        $paramsConceptorestante=$this->data['Conceptosrestante']["id"];
        echo $this->Html->image('edit_view.png',array('width' => '20', 'height' => '20','onClick'=>"modificarConceptosrestante(".$paramsConceptorestante.")"));
        echo $this->Html->image('eliminar.png',array('width' => '20', 'height' => '20','onClick'=>"eliminarConceptosrestante(".$paramsConceptorestante.")"));
        echo $this->Form->end();  ?>
    </td>
<?php }else{ ?>
    <td colspan="20" id="tdconceptosrestante<?php echo $conid?>" class="editTD">
        <?php echo $this->Form->create('Conceptosrestante',array(
        	'controller'=>'Conceptosrestantes',
        	'action'=>'edit',
        	'id'=>'ConceptosRestanteFormEdit'.$conid,
          	'class'=>'formTareaCarga')
        ); 
            echo $this->Form->input('id',array('type'=>'hidden'));
            echo $this->Form->input('cliente_id',array('type'=>'hidden'));
			echo $this->Form->input('partido_id',array('class' => 'chosen-select' , ));
			echo $this->Form->input('localidade_id',array('class' => 'chosen-select' , ));
			echo $this->Form->input('impcli_id');
			echo $this->Form->input('periodo');
			echo $this->Form->input('comprobante_id');
			echo $this->Form->input('rectificativa');
			echo $this->Form->input('numerocomprobante');
			echo $this->Form->input('razonsocial');
			echo $this->Form->input('monto');
			echo $this->Form->input('montoretenido');
			echo $this->Form->input('cuit');
			echo $this->Form->input('conceptofecha'.$conid, array(
	              'class'=>'datepicker', 
	              'type'=>'text',
	              'label'=>'Fecha',
	              'default'=> date('d-m-Y',strtotime($this->request->data['Conceptosrestante']['fecha'])),
	              'readonly'=>'readonly',
	              'required'=>true
	              )
	       	);               
			echo $this->Form->input('conceptostipo_id');
			echo $this->Form->input('numerodespachoaduanero');
			echo $this->Form->input('anticipo');
			echo $this->Form->input('cbu');
			echo $this->Form->input('tipocuenta',array(
					'options'=>array('Caja de Ahorro'=>'Caja de Ahorro','Cuenta Corriente'=>'Cuenta Corriente','Otro'=>'Otro'),
					'empty'=>'Seleccionar Tipo de cuenta',
				)
			);
			echo $this->Form->input('tipomoneda',array(
					'options'=>array('Moneda Ext.'=>'Moneda Ext.','Peso Arg.'=>'Peso Arg.','Otro'=>'Otro'),
					'empty'=>'Seleccionar Moneda',
				)
			);
			echo $this->Form->input('agente');
			echo $this->Form->input('enterecaudador');
			echo $this->Form->input('regimen');
			echo $this->Form->input('descripcion');
			echo $this->Form->input('numeropadron');
			echo $this->Form->input('puntosdeventa');
			echo $this->Form->input('numerofactura');
			echo $this->Form->input('ordendepago');
      		echo $this->Form->submit('+', array(
	              'type'=>'image',
	              'src' => $this->webroot.'img/check.png',
	              'class'=>'img_edit',
	              'style'=>'width:25px;height:25px;')
	              );  
      		echo $this->Form->end();  ?>  
    </td>     
<?php } ?>