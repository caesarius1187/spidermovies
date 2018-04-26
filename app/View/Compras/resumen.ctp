<?php

//todo separar este informe por año fiscal
echo $this->Html->css('bootstrapmodal');
echo $this->Html->script('jquery-ui',array('inline'=>false));
echo $this->Html->script('Chart',array('inline'=>false));
echo $this->Html->script('jquery.dataTables.js',array('inline'=>false));
echo $this->Html->script('dataTables.altEditor.js',array('inline'=>false));
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
echo $this->Html->script('dataTables.buttons.min.js',array('inline'=>false));
echo $this->Html->script('buttons.print.min.js',array('inline'=>false));
echo $this->Html->script('buttons.flash.min.js',array('inline'=>false));
echo $this->Html->script('jszip.min.js',array('inline'=>false));
echo $this->Html->script('pdfmake.min.js',array('inline'=>false));
echo $this->Html->script('vfs_fonts.js',array('inline'=>false));
echo $this->Html->script('buttons.html5.min.js',array('inline'=>false));

echo $this->Form->input('mostrarInforme',['type'=>'hidden','value'=>$mostrarInforme?1:0]);
if($mostrarInforme){
    echo $this->Form->input('clientenombre',['type'=>'hidden','value'=>$cliente["Cliente"]['nombre']]);
    echo $this->Form->input('periododesde',['type'=>'hidden','value'=>$periodomesdesde.'-'.$periodoaniodesde]);
    echo $this->Form->input('periodohasta',['type'=>'hidden','value'=>$periodomeshasta.'-'.$periodoaniohasta]);
    echo $this->Form->input('mostrarInforme',['type'=>'hidden','value'=>$mostrarInforme?1:0]);
}
?>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.2/css/buttons.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.1.2/css/select.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.0.2/css/responsive.dataTables.min.css"/>

<script type="text/javascript">
    var mitablacompras;
    $(document).ready(function() {
        if($('#mostrarInforme').val()==1){
            var nombrecliente = $('#clientenombre').val();
            var periododesde = $('#periododesde').val();
            var periodohasta = $('#periodohasta').val();
            var movimientosbancarios = jQuery.parseJSON($("#movimientosbancarios").val());
            var values = [];
            var labels = [];
            $.each(movimientosbancarios, function(index, value) {
                labels.push(index);
                values.push(value.total);
            });

            var ctxbar = $("#myChart");
            var ctxline = $("#myChartline");
            var myChartbar = new Chart(ctxbar, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '$ Compras',
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
                        label: '$ Compras',
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
            mitablacompras = $('#ComprasDatatable').DataTable( {
                dom: 'Bfrtip',
                lengthMenu: [
                    [ 10, 25, 50, -1 ],
                    [ '10', '25', '50', 'todas' ]
                ],
                buttons: [
                    {
                        extend: 'collection',
                        text: 'Columnas',
                        autoClose: true,
                        buttons: [
                            {
                                text: 'Conmutar periodo',
                                action: function ( e, dt, node, config ) {
                                    dt.column( 0 ).visible( ! dt.column( 0 ).visible() );
                                }
                            },
                            {
                                text: 'Conmutar neto',
                                action: function ( e, dt, node, config ) {
                                    dt.column( 1 ).visible( ! dt.column( 1 ).visible() );
                                }
                            },
                            {
                                text: 'Conmutar iva',
                                action: function ( e, dt, node, config ) {
                                    dt.column( 2 ).visible( ! dt.column( 2 ).visible() );
                                }
                            },
                            {
                                text: 'Conmutar IVA percep',
                                action: function ( e, dt, node, config ) {
                                    dt.column( 3 ).visible( ! dt.column( 3 ).visible() );
                                }
                            },
                            {
                                text: 'Conmutar IIBB percep',
                                action: function ( e, dt, node, config ) {
                                    dt.column( 4 ).visible( ! dt.column( 4 ).visible() );
                                }
                            },
                            {
                                text: 'Conmutar Act. Vs. percep',
                                action: function ( e, dt, node, config ) {
                                    dt.column( 5 ).visible( ! dt.column( 5 ).visible() );
                                }
                            },
                            {
                                text: 'Conmutar Imp Internos',
                                action: function ( e, dt, node, config ) {
                                    dt.column( 6 ).visible( ! dt.column( 6 ).visible() );
                                }
                            },
                            {
                                text: 'Conmutar Imp Combustible',
                                action: function ( e, dt, node, config ) {
                                    dt.column( 7 ).visible( ! dt.column( 7 ).visible() );
                                }
                            },
                            {
                                text: 'Conmutar no gravados',
                                action: function ( e, dt, node, config ) {
                                    dt.column( 8 ).visible( ! dt.column( 8 ).visible() );
                                }
                            },
                            {
                                text: 'Conmutar exento iva',
                                action: function ( e, dt, node, config ) {
                                    dt.column( 9 ).visible( ! dt.column( 9 ).visible() );
                                }
                            },
                            {
                                text: 'Conmutar Total',
                                action: function ( e, dt, node, config ) {
                                    dt.column( -1 ).visible( ! dt.column( -1 ).visible() );
                                }
                            }
                        ]
                    },
                    {
                        extend: 'pageLength',
                        text: 'Ver',
                    },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        title: 'Resumen-Compras-'+nombrecliente+' '+periododesde+' hasta '+periodohasta,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        title: 'Resumen-Compras-'+nombrecliente+' '+periododesde+' hasta '+periodohasta,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        title: 'Resumen-Compras-'+nombrecliente+' '+periododesde+' hasta '+periodohasta,
                        exportOptions: {
                            columns: ':visible'
                        },
                        orientation: 'landscape',
                        download: 'open',
                        message: 'Resumen-Compras-'+nombrecliente+' '+periododesde+' hasta'+periodohasta,

                    },
                    {
                        extend: 'copy',
                        text: 'Copiar',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Imprimir',
                        exportOptions: {
                            columns: '.printable'
                        },
                        orientation: 'landscape',
                        footer: true,
                        autoPrint: true,
                        message: nombrecliente+"</br>"+' Periodo: '+periododesde+' hasta '+periodohasta+"</br>",
                        customize: function ( win ) {
                        },
                    },
                ],
            } );
            mitablacompras.columns( '.sum' ).every( function () {
                try {
                    var micolumndata = this.data();
                    var columnLength = this.data().length;
                    if(columnLength > 0){
                        var sum = this
                            .data()
                            .reduce( function (a,b) {
                                if (a != null && b != null) {

                                    if (typeof a === 'string') {
                                        a = a.replace('.', "");
                                        a = a.replace(',', ".");
                                    }
                                    a = Number(a);
                                    if (typeof b === 'string') {
                                        b = b.replace('.', "");
                                        b = b.replace(',', ".");
                                    }
                                    b = Number(b);
                                    var resultado = a + b;
                                    return resultado;
                                } else {
                                    return 0;
                                }
                            } );
                        if (typeof sum === 'string') {
                            sum = sum.replace('.', "");
                            sum = sum.replace(',', ".");
                            $( this.footer() ).html((sum*1).toFixed(2));
                        }else{
                            $( this.footer() ).html(sum.toFixed(2));
                        }
                    }
                }
                catch (e)
                {
                    alert(e.message);
                }
            } );
        }
         $('.chosen-select').chosen({search_contains:true});
    })
</script>
<div id="Formhead" class="clientes informefinancierotributario index" style="margin-bottom:10px; font-family: 'Arial'">
<!--<input class="button" type="button" id="btnHiddeForm" onClick="hideForm()" value="Ocultar" style="float:right;"/>-->
<?php echo $this->Form->create('compras',array('controller'=>'compras','action' => 'resumen', 'class'=>'formTareaCarga',)); ?>
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
                        'label'=> 'Desde',
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
                        'label'=> '&nbsp;',
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
                        'label'=> 'Hasta',
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
                        'label'=>  '&nbsp;',
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
            <?php }?>
        </tr>
    </table>
</div> <!--End Clietenes_avance-->
<?php if($mostrarInforme){
    $comprasxPeriodo=[];
    foreach ($compras as $compra) {
        $periodoCompra = $compra['Compra']['periodo'];
        $periodoAnioCompra = $compra[0]['anio'];
        $periodoMesCompra = $compra[0]['mes'];
        if(!isset($comprasxPeriodo[$periodoCompra])){
            $comprasxPeriodo[$periodoCompra]['total']=0;
            $comprasxPeriodo[$periodoCompra]['neto']=0;
            $comprasxPeriodo[$periodoCompra]['iva']=0;
            $comprasxPeriodo[$periodoCompra]['ivapercep']=0;
            $comprasxPeriodo[$periodoCompra]['iibbpercep']=0;
            $comprasxPeriodo[$periodoCompra]['actvspercep']=0;
            $comprasxPeriodo[$periodoCompra]['impinternos']=0;
            $comprasxPeriodo[$periodoCompra]['impcombustible']=0;
            $comprasxPeriodo[$periodoCompra]['nogravados']=0;
            $comprasxPeriodo[$periodoCompra]['excentos']=0;
        }
        if($compra['Comprobante']["tipodebitoasociado"]=='Restitucion de debito fiscal'){
            $comprasxPeriodo[$periodoCompra]['total'] -= $compra[0]['total'];
            $comprasxPeriodo[$periodoCompra]['neto'] -= $compra[0]['neto'];
            $comprasxPeriodo[$periodoCompra]['iva'] -= $compra[0]['iva'];
            $comprasxPeriodo[$periodoCompra]['ivapercep'] -= $compra[0]['ivapercep'];
            $comprasxPeriodo[$periodoCompra]['iibbpercep'] -= $compra[0]['iibbpercep'];
            $comprasxPeriodo[$periodoCompra]['actvspercep'] -= $compra[0]['actvspercep'];
            $comprasxPeriodo[$periodoCompra]['impinternos'] -= $compra[0]['impinternos'];
            $comprasxPeriodo[$periodoCompra]['impcombustible'] -= $compra[0]['impcombustible'];
            $comprasxPeriodo[$periodoCompra]['nogravados'] -= $compra[0]['nogravados'];
            $comprasxPeriodo[$periodoCompra]['excentos'] -= $compra[0]['excentos'];
        }else{
            $comprasxPeriodo[$periodoCompra]['total'] += $compra[0]['total'];
            $comprasxPeriodo[$periodoCompra]['neto'] += $compra[0]['neto'];
            $comprasxPeriodo[$periodoCompra]['iva'] += $compra[0]['iva'];
            $comprasxPeriodo[$periodoCompra]['ivapercep'] += $compra[0]['ivapercep'];
            $comprasxPeriodo[$periodoCompra]['iibbpercep'] += $compra[0]['iibbpercep'];
            $comprasxPeriodo[$periodoCompra]['actvspercep'] += $compra[0]['actvspercep'];
            $comprasxPeriodo[$periodoCompra]['impinternos'] += $compra[0]['impinternos'];
            $comprasxPeriodo[$periodoCompra]['impcombustible'] += $compra[0]['impcombustible'];
            $comprasxPeriodo[$periodoCompra]['nogravados'] += $compra[0]['nogravados'];
            $comprasxPeriodo[$periodoCompra]['excentos'] += $compra[0]['excentos'];
        }

    }
        echo $this->Form->input('movimientosbancarios',[
                'value'=>json_encode($comprasxPeriodo),
                'type'=>'hidden'
            ]
        );
        ?>

    <div class="index" style="">
        <?php
        //esta tabla tendria que mostrar años fiscales uno al lado del otro
        //tal ves toda la consulta sea eligiendo años fiscales
        ?>
        <table id="ComprasDatatable">
            <thead>
                <tr>
                    <th class="printable" style="width: 89px;">Periodo</th><!--1-->
                    <th class="printable sum" style="width: 89px;">Neto</th><!--2-->
                    <th class="printable sum" style="width: 89px;">IVA</th>   <!--3-->
                    <th style="width: 89px;" class="sum printable" >IVA Percep</th><!--4-->
                    <th style="width: 89px;" class="sum printable" >IIBB Percep</th><!--5-->
                    <th style="width: 89px;" class="sum printable" >Act Vs Perc</th><!--6-->
                    <th style="width: 89px;" class="sum printable" >Imp Internos</th><!--7-->
                    <th style="width: 89px;" class="sum printable" >Imp Combustible</th><!--7-->
                    <th style="width: 89px;" class="sum printable" >No Gravados</th><!--8-->
                    <th style="width: 89px;" class="sum printable" >Exento</th><!--9-->
                    <th class="printable sum" style="width: 89px;" >Total</th><!--10-->
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th ></th><!--1-->
                    <th ></th><!--2-->
                    <th ></th><!--3-->
                    <th ></th><!--4-->
                    <th ></th><!--5-->
                    <th ></th><!--6-->
                    <th ></th><!--7-->
                    <th ></th><!--8-->
                    <th ></th><!--9-->
                    <th></th><!--10-->
                    <th></th><!--11-->
                </tr>
            </tfoot>
            <tbody>
                    <?php
                    foreach ($comprasxPeriodo as $v =>$compra) {?>
                    <tr>
                        <td class=""><?php echo substr($v, 3).'-'.substr($v, 0, 2); ?></td><!--1-->
                        <td class="numericTD"><?php echo number_format($compra["neto"], 2, ",", ".")?></td><!--2-->
                        <td class="numericTD"><?php echo number_format($compra["iva"], 2, ",", ".")?></td><!--3-->
                        <td class="numericTD"><?php echo number_format($compra["ivapercep"], 2, ",", ".")?></td><!--4-->
                        <td class="numericTD"><?php echo number_format($compra["iibbpercep"], 2, ",", ".")?></td><!--5-->
                        <td class="numericTD"><?php echo number_format($compra["actvspercep"], 2, ",", ".")?></td><!--6-->
                        <td class="numericTD"><?php echo number_format($compra["impinternos"], 2, ",", ".")?></td><!--7-->
                        <td class="numericTD"><?php echo number_format($compra["impcombustible"], 2, ",", ".")?></td><!--7-->
                        <td class="numericTD"><?php echo number_format($compra["nogravados"], 2, ",", ".")?></td><!--8-->
                        <td class="numericTD"><?php echo number_format($compra["excentos"], 2, ",", ".")?></td><!--9-->
                        <td class="numericTD"><?php echo number_format($compra["total"], 2, ",", ".")?></td>
                    </tr><!--12-->
                    <?php
                    }
                    ?>
            </tbody>
        </table>
    </div>
    <div class="index" style="width:45%;height:700px;float:left">
        <canvas id="myChart" width="400" height="400"></canvas>
    </div>
    <div class="index" style="width:45%;height:700px;float:right">
        <canvas id="myChartline" width="400" height="400"></canvas>
    </div>

    <?php
}

?>

