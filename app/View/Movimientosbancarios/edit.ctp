<?php
$tdClass = "tdViewMovimientosBancario".$this->data['Movimientosbancario']["id"];
if(!$mostrarForm) { ?>
    <?php
      echo json_encode($data);
  }else{ ?>
    </td>
    <td colspan="15" id="tdmovimientosbancario<?php echo $this->data['Movimientosbancario']["id"]?>" class="editTD">
      <?php 
      echo $this->Form->create('Movimientosbancario',array(
          'controller'=>'MovimientosBancario',
          'action'=>'edit',
          'id'=>'MovimientosBancarioFormEdit'.$this->data['Movimientosbancario']['id'],
          'class'=>'formTareaCarga'
          )
      ); 
      echo $this->Form->input('id',array('type'=>'hidden'));
     
      echo $this->Form->input('cbu_id',array('type'=>'hidden'));
      echo $this->Form->input('ordencarga', array('label'=>'Orden',));
      echo $this->Form->input('fecha'.$this->data['Movimientosbancario']['id'], array(
              'class'=>'datepicker',
              'type'=>'text',
              'label'=>'Fecha',
              'readonly'=>'readonly',
              'default'=>date('d-m-Y',strtotime($this->data['Movimientosbancario']['fecha'])),
              )
       );                               
      //Aca tenemos que sacar los tipos de comprobantes que el cliente puede emitir
      echo $this->Form->input('cuentascliente_id', array(
            'label'=> 'Cuenta',
            'type'=> 'select',
            'class'=> 'chosen-select',
            'options'=> $cuentasclientes,
      ));
      echo $this->Form->input('codigoafip', array(
            'label'=> 'Codigo AFIP',
      ));
      echo $this->Form->input('concepto', ['style'=>'width:250px;']);
      echo $this->Form->input('debito', []);
      echo $this->Form->input('credito', []);
      echo $this->Form->input('saldo', []);
      echo $this->Form->input('alicuota');

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
                    
                      
        