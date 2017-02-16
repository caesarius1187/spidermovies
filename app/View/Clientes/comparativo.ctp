<?php echo $this->Html->script('clientes/comparativo',array('inline'=>false));?>
<div id="Formhead" class="clientes avanse index">
    <?php echo $this->Form->create('clientes',array('action' => 'comparativolistacliente')); ?>
    <table class="tbl_avance">
        <tr>
            <td>
                <?php
                echo $this->Form->input('gclis', array(
                    //'multiple' => 'multiple',
                    'type' => 'select',
                    'label' => 'Clientes',
                    'class'=>'chosen-select'
                ));?>
                <?php
                echo $this->Form->input('shownombre', array(
                    'style' => 'display:none',
                    'type' => 'checkbox',
                    'value' => false,
                    'label'=>false
                ));?>
            </td>
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
            </td>
            <td id='tdmesSiguiente'>
                 <div class="submit">
                     <?php
                        echo $this->Form->input('->', array(
                            'onClick' => 'mesSiguiente();',
                            'type'=>'submit',
                            'label' => false,
                            'id'=>'btnGcliSiguiente',
                            'src'=>"/img/mas2.png"
                        ));?>
                </div>
            </td>
            </td>
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
                                        );?>
            </td>
            <td id='tdanoSiguiente'>
                 <div class="submit">
                     <?php
                        echo $this->Form->input('->', array(
                            'onClick' => 'anoSiguiente();',
                            'type'=>'submit',
                            'label' => false,
                            'id'=>'btnGcliSiguiente',
                            'src'=>"/img/mas2.png"
                        ));?>
                </div>
            </td>
            <td>
              <?php echo $this->Form->end(__('Aceptar')); ?>
            </td>
        </tr>
    </table>
</div>
<div id="carga" class="clientes avanse index">
    <div id="divContenedor">
        <table id="tblTabla" class="clsTabla">
            <thead id="thead1">
                <tr class="sigr">
                    <th class="sigr">Clientes</th>
                </tr>
            </thead>
            <tbody id="tblClienteBody">

            </tbody>
            <tfoot>
            </tfoot>
        </table>
        <div id="Botones" style="display:none">
            <form  id="FormularioExportacion" action="exportar.php"method="post" target="_blank">
                <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
                <input type="button" class="button grey" value="Exportar A Excel" onClick="exportar()"  id="botonExcel" />
                <input type="button" style="display:none" class="button grey" value="Imprimir" onclick="imprimirCO()" id="imprimir"/>
            </form>
        </div>
    </div>
</div>

