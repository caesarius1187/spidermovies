    <div id="headerCarga" style="height:70px;text-align: center;">
        <div style="padding:0 0 0 39%">
          <label style="float:left">Cliente Pepe</label>
          <label style="float:left">Fecha</label>
          <button style="float:left">Marcar Como Realizado</button>
        </div>  
     </div>
     <div id="bodyCarga"  >
         <div class="" style="width:100%;height:30px;">
            <div class="cliente_view_tab"  onClick="" id="">
                <?php
                   echo $this->Form->label(null, $text = 'Ventas',array('style'=>'text-align:center;margin-top:5px;cursor:pointer')); 
                 ?>
            </div>
            <div class="cliente_view_tab"  onClick="" id="">
                  <?php
                      echo $this->Form->label(null, $text = 'Compras',array('style'=>'text-align:center;margin-top:5px;cursor:pointer'));
                   ?>
            </div>
            <div class="cliente_view_tab"  onClick="" id="">
                  <?php
                      echo $this->Form->label(null, $text = 'Novedades',array('style'=>'text-align:center;margin-top:5px;cursor:pointer'));
       
                      //$this->Html->image('cli_view.png', array('alt' => '','id'=>'imgcli','class'=>'','style'=>'margin-left: 50%;'));
                   ?>
            </div>
            <div class="cliente_view_tab"  onClick="" id="cliente_view_tab_compra">
                  <?php
                      echo $this->Form->label(null, $text = 'Otros',array('style'=>'text-align:center;margin-top:5px;cursor:pointer'));
       
                      //$this->Html->image('cli_view.png', array('alt' => '','id'=>'imgcli','class'=>'','style'=>'margin-left: 50%;'));
                   ?>
            </div>
        </div>
        <div id="form_venta" class="" style="overflow:auto;width:90%;margin: 0 0 0 5%;">             
          <?php
              echo $this->Form->input('cliente_id',array('default'=>'','type'=>'hidden'));
           ?>
            <table class="" style="border:1px solid blue">
                  <tr class="">
                      <td><?php
                          echo $this->Form->input('fecha', array(
                                  'class'=>'datepicker', 
                                  'type'=>'text',
                                  'label'=>'Fecha',
                                  'default'=>"",
                                  'readonly'=>'readonly')
                           );?>                                
                      </td>
                      <td><?php             
                          //Aca tenemos que sacar los tipos de comprobantes que el cliente puede emitir                              
                          echo $this->Form->input('tipocomprobante', array(
                              'options' => array(
                                  'Fa'=>'Fa', 
                                  'Fb'=>'Fb', 
                                  'Fc'=>'Fc',                                         
                                  ),
                              'empty' => 'Elegir tipo de comprobante',
                              'label'=> 'Tipo de Comprobante'
                          )); 
                          ?>
                      </td>
                      <td><?php                      
                          echo $this->Form->input('puntosdeventa_id'); 
                          ?>
                      </td>
                  
                      <td><?php                   
                          echo $this->Form->input('numerocomprobante');    
                          ?>
                      </td>
                      <td><?php                   
                          echo $this->Form->input('subcliente_id');    
                          ?>
                      </td>
                      <td><?php                   
                          echo $this->Form->input('localidade_id');    
                          ?>
                      </td>
                      <td><?php                   
                          echo $this->Form->input('alicuota');    
                          ?>
                      </td>
                      <td><?php                   
                          echo $this->Form->input('neto');    
                          ?>
                      </td> 
                   
                     
                      <td><?php                   
                          echo $this->Form->input('iva');    
                          ?>
                      </td>
                
                      <td><?php                   
                          echo $this->Form->input('ivapercep');    
                          ?>
                      </td>
                      <td><?php                   
                          echo $this->Form->input('actvspercep');    
                          ?>
                      </td>
                      <td><?php                   
                          echo $this->Form->input('impinternos');    
                          ?>
                      </td>
                 
                      <td><?php                   
                          echo $this->Form->input('nogravados');    
                          ?>
                      </td>
                      <td><?php                   
                          echo $this->Form->input('excentos');     
                          ?>
                      </td>
                      <td><?php                  
                          echo $this->Form->input('comercioexterior');      
                          ?>
                      </td>
                  
                      <td><?php                 
                          echo $this->Form->input('total');     
                          ?>
                      </td>
                      <td><?php                  
                          echo $this->Form->input('asiento');      
                          ?>
                      </td>
                      <td>
                  </tr>
            </table>  
            <?php  $this->Form->end(__('Aceptar')); ?>

        </div>
        <div style="overflow:auto;width:90%;margin: 0 0 0 5%;">
          <table class="" style="border:1px solid blue">
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Comprobante</th>
                <th>Punto de Venta</th>
                <th>Num Comprobante</th>
                <th>SubCliente</th>
                <th>Localidad</th>
                <th>Alicuota</th>
                <th>Neto</th>
                <th>IVA</th>
                <th>IVAPercep</th>
                <th>ActVsPerc</th>
                <th>Imp Internos</th>
                <th>No Gravados</th>
                <th>Escentos</th>
                <th>Comercio Exterior</th>
                <th>Total</th>
                <th>Asiento</th>
              </tr>
            </thead>
            <tbody>
              <tr class="">
                <td>   a                        
                </td>
                <td>
                </td>
                <td>
                </td>                  
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>                                                     
                <td>
                </td>                
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>                 
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>                  
                <td>
                </td>
                <td>
                </td>                      
              </tr>
            </tbody>
          </table>  
        </div> 
     </div>
</div>   