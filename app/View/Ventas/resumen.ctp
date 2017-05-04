<?php echo $this->Html->script('jquery-ui',array('inline'=>false));?>
<?php echo $this->Html->script('Chart',array('inline'=>false));?>
<?php //echo $this->Html->script('ventas/resumenventas',array('inline'=>false));?>

<script type="text/javascript">
    $(document).ready(function() {
        var movimientosbancarios = jQuery.parseJSON($("#movimientosbancarios").val());
        var values = [];
        var labels = [];
        $.each(movimientosbancarios, function(index, value) {
            labels.push(index);
            values.push(value);
        });

        var ctxbar = $("#myChart");
        var ctxline = $("#myChartline");
        var myChartbar = new Chart(ctxbar, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: '$ Ventas',
                    data: values,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
        var myChartline = new Chart(ctxline, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: '$ Ventas',
                    data: values,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
    })
</script>
        <div id="Formhead" class="clientes informefinancierotributario index" style="margin-bottom:10px; font-family: 'Arial'">
	<!--<input class="button" type="button" id="btnHiddeForm" onClick="hideForm()" value="Ocultar" style="float:right;"/>-->
	<?php echo $this->Form->create('ventas',array('controller'=>'ventas','action' => 'resumen')); ?>
    <table class="tbl_informefinancierotributario tblInforme">        
        <tr>
            <td>
              <?php
                echo $this->Form->input('cliente_id', array(
                    'type' => 'select',
                    'label' => 'Clientes',
                    'class'=>'chosen-select',
                ));?>
            </td>
        	<td>                      
                <?php
                echo $this->Form->input('periodomesdesde', array(
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
                        'placeholder' => 'Mes Desde',
                        'default' =>  date("m", strtotime("-1 month"))
                    ));
                echo $this->Form->input('periodoaniodesde', array(
                        'options' => array(
                            '2012'=>'2012',
                            '2013'=>'2013',
                            '2014'=>'2014',
                            '2015'=>'2015',
                            '2016'=>'2016',
                            '2017'=>'2017',
                            '2018'=>'2018',
                            '2019'=>'2019',
                        ),
                        'empty' => 'Elegir año',
                        'label'=> 'Año',
                        'required' => true,
                        'placeholder' => 'Año desde',
                        'default' =>  date("Y", strtotime("-1 month"))
                    )
                );
      	        ?>
            </td>
            <td>
                <?php
                echo $this->Form->input('periodomeshasta', array(
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
                        'placeholder' => 'Mes Hasta',
                        'default' =>  date("m", strtotime("-1 month"))
                    ));
                echo $this->Form->input('periodoaniohasta', array(
                        'options' => array(
                            '2012'=>'2012',
                            '2013'=>'2013',
                            '2014'=>'2014',
                            '2015'=>'2015',
                            '2016'=>'2016',
                            '2017'=>'2017',
                            '2018'=>'2018',
                            '2019'=>'2019',
                        ),
                        'empty' => 'Elegir año',
                        'label'=> 'Año',
                        'required' => true,
                        'placeholder' => 'Año hasta',
                        'default' =>  date("Y", strtotime("-1 month"))
                    )
                );
      	        ?>
            </td>
            <?php echo $this->Form->input('selectby',array('default'=>'none','type'=>'hidden'));//?>
            <?php 
                $options = array(
                    'label' => 'Aceptar',
                    'div' => array(
                        'class' => 'btn_acept',
                  )
                );
            ?>
            <!--<?php echo $this->Form->end(__('Aceptar')); ?>-->
            <td rowspan="1"><?php echo $this->Form->end($options); ?> </td>
            <?php if(isset($mostrarInforme)){?>
            <td rowspan="1">
                <?php echo $this->Form->button('Imprimir',
                                        array('type' => 'button',
                                            'class' =>"btn_imprimir",
                                            'onClick' => "imprimir()"
                                            )
                    );?>
            </td>
            <td rowspan="1">
                <?php echo $this->Form->button('Excel',
                                        array('type' => 'button',
                                            'id'=>"clickExcel",
                                            'class' =>"btn_imprimir",
                                            )
                    );?> 
            </td>
            <?php }?>
        </tr>
    </table>
</div> <!--End Clietenes_avance-->
<?php if(isset($mostrarInforme)){
    $ventasxPeriodo=[];
    foreach ($ventas as $venta) {
        $periodoVenta = $venta['Venta']['periodo'];
        if(!isset($ingresosActualesClientes[$periodoVenta])){
            $ventasxPeriodo[$periodoVenta]=0;
        }
        if($venta['Comprobante']["tipodebitoasociado"]=='Restitucion debito fiscal'){
            $ventasxPeriodo[$periodoVenta] -= $venta[0]['total'];
        }else{
            $ventasxPeriodo[$periodoVenta] += $venta[0]['total'];
        }

    }
    ?>
    <div class="index">
        <?php
        echo $this->Form->input('movimientosbancarios',[
            'value'=>json_encode($ventasxPeriodo),
            'type'=>'hidden'
            ]
        );
        ?>
        <canvas id="myChart" width="400" height="400"></canvas>
        <canvas id="myChartline" width="400" height="400"></canvas>
    </div>


    <?php
}?>

