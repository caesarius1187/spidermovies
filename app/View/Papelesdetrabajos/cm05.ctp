<?php

//todo separar este informe por año fiscal
echo $this->Html->script('jquery-ui',array('inline'=>false));
echo $this->Html->css('bootstrapmodal');
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
//echo $this->Html->script('jquery.table2excel',array('inline'=>false));
echo $this->Html->script('table2excel',array('inline'=>false));

echo $this->Html->script('jquery.dataTables.js',array('inline'=>false));
echo $this->Html->script('dataTables.altEditor.js',array('inline'=>false));
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));

echo $this->Html->script('papelesdetrabajos/cm05',array('inline'=>false));

echo $this->Form->input('mostrarInforme',['type'=>'hidden','value'=>$mostrarInforme?1:0]);
if($mostrarInforme){
    echo $this->Form->input('clientenombre',['type'=>'hidden','value'=>$cliente["Cliente"]['nombre']]);
    echo $this->Form->input('periododesde',['type'=>'hidden','value'=>$periodomesdesde.'-'.$periodoaniodesde]);
    echo $this->Form->input('periodohasta',['type'=>'hidden','value'=>$periodomeshasta.'-'.$periodoaniohasta]);
    echo $this->Form->input('mostrarInforme',['type'=>'hidden','value'=>$mostrarInforme?1:0]);
}
?>
<div id="Formhead" class="clientes informefinancierotributario index" style="margin-bottom:10px; font-family: 'Arial'">
    <!--<input class="button" type="button" id="btnHiddeForm" onClick="hideForm()" value="Ocultar" style="float:right;"/>-->
    <?php echo $this->Form->create('papelesdetrabajos',array('controller'=>'papelesdetrabajos','action' => 'cm05', 'class'=>'formTareaCarga',)); ?>
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
            <td>
                <?php
                echo $this->Form->button('Excel',
                    array('type' => 'button',
                        'id'=>"clickExcel",
                        'class' =>"btn_imprimir",
                    )
                );
                ?>
            </td>
            <td>
                <?php
                echo $this->Form->button('Minimizar',
                    array('type' => 'button',
                        'id'=>"minimizar",
                        'onClick' =>"showhideBase()",
                    )
                );
                ?>
            </td>
        </tr>
    </table>
</div> <!--End Clietenes_avance-->
<?php if($mostrarInforme){
    $actividadclientes = [];
    $articuloActividad = [];
     foreach ($cliente["Actividadcliente"] as $kac => $actividadcliente) {
         $actividadclientes[$actividadcliente['id']]=$actividadcliente['Actividade']['nombre'];
         $articuloActividad[$actividadcliente['id']] = $actividadcliente['Actividade']['articulo'];
     }
    $ventasxPeriodo=[];
    $provincias = [];
    $provinciasEjercicio = [];
    $impcliprovincias = [];
    foreach ($cliente['Impcli'] as $kic => $impcli) {
        foreach ($impcli['Impcliprovincia'] as $kicp => $impcliprovincia) {
            $provincias[$impcliprovincia['Partido']['id']]=$impcliprovincia['Partido']['nombre'];
            $provinciasEjercicio[$impcliprovincia['Partido']['id']]=$impcliprovincia['ejercicio'];
            $impcliprovincias[$impcliprovincia['Partido']['id']]=$impcliprovincia;
        }
    }    
    $totalVentasProvincia = [];
    $totalVentasActividad = [];
    foreach ($ventas as $kv=>$venta) {
        $periodoVenta = $venta['Venta']['periodo'];
        $periodoAnioVenta = $venta[0]['anio'];
        $periodoMesVenta = $venta[0]['mes'];
        
        $actividad = $venta['Venta']['actividadcliente_id'];
        $localidad = $venta['Localidade']['Partido']['nombre'];
       
        if(!isset($totalVentasProvincia[$localidad])){
            $totalVentasProvincia[$localidad]=0;      
        } 
        if(!isset($totalVentasProvincia[$periodoVenta])){
            $totalVentasProvincia[$periodoVenta]=[];      
        } 
        if(!isset($totalVentasProvincia[$periodoVenta][$localidad])){
            $totalVentasProvincia[$periodoVenta][$localidad]=0;      
        } 
        if(!isset($totalVentasActividad[$periodoVenta])){
            $totalVentasActividad[$periodoVenta]=[];      
        } 
        if(!isset($totalVentasActividad[$periodoVenta][$actividad])){
            $totalVentasActividad[$periodoVenta][$actividad]=0;      
        } 

        if(!isset($ventasxPeriodo[$periodoVenta])){
            $ventasxPeriodo[$periodoVenta]=[];
        }
        if(!isset($ventasxPeriodo[$periodoVenta][$actividad])){
            $ventasxPeriodo[$periodoVenta][$actividad]=[];      
        } 
        
        if(!isset($ventasxPeriodo[$periodoVenta][$actividad][$localidad])){
            $ventasxPeriodo[$periodoVenta][$actividad][$localidad]=0;            
        } 
        if(!isset($ventasxPeriodo[$periodoVenta][$actividad]['total'])){
            $ventasxPeriodo[$periodoVenta][$actividad]['total']=0;            
        } 
       
        $montoVenta = $venta[0]['neto']+$venta[0]['nogravados']+$venta[0]['excentos']-$venta[0]['exentosactividadeseconomicas'];
        if($venta['Comprobante']['tipodebitoasociado']=='Debito fiscal o bien de uso'){
            $ventasxPeriodo[$periodoVenta][$actividad][$localidad] +=$montoVenta;
            $ventasxPeriodo[$periodoVenta][$actividad]['total'] +=$montoVenta;
            $articuloVenta = $articuloActividad[$actividad];
            if($articuloVenta==2){
                $totalVentasProvincia[$localidad]+=$montoVenta;
                $totalVentasProvincia[$periodoVenta][$localidad]+=$montoVenta;
                //$totalVentasActividad[$actividad]+=$montoVenta;
            }
        }else if($venta['Comprobante']['tipodebitoasociado']=='Restitucion de debito fiscal'){
            $ventasxPeriodo[$periodoVenta][$actividad][$localidad] -=$montoVenta;
            $ventasxPeriodo[$periodoVenta][$actividad]['total'] -=$montoVenta;
             if($articuloVenta==2){
                $totalVentasProvincia[$localidad]-=$montoVenta;
                $totalVentasProvincia[$periodoVenta][$localidad]-=$montoVenta;
                //$totalVentasActividad[$actividad]-=$montoVenta;
            }
        }
    }  
    echo $this->Form->input('movimientosbancarios',[
            'value'=>json_encode($ventasxPeriodo),
            'type'=>'hidden'
        ]
    );
    ?>

    <div class="index" style="">
        <?php
        //esta tabla tendria que mostrar años fiscales uno al lado del otro
        //tal ves toda la consulta sea eligiendo años fiscales
        ?>
        <table id="VentasDatatable" class="tbl_border toExcelTable">
            <thead>
                <tr>
                    <td></td>
                    <td>Total Base Real</td>
                <?php
                    foreach ($ventasxPeriodo as $kperiodos =>$kperiodos) {  ?>                   
                    <td class="hiddable" colspan="<?php echo count ($ventasxPeriodo[$kperiodos])+1 ?>" style="text-align: center" mycolspan="<?php echo count ($ventasxPeriodo[$kperiodos])+1 ?>" ><?php echo $kperiodos; ?> </td><!--1-->                                            
                    <?php
                    }
                    ?>
                </tr><!--fin Cabecera Periodos-->
                <tr>
                    <td></td>
                    <td></td>
                <?php
                    foreach ($ventasxPeriodo as $kperiodos =>$kperiodos) {  ?>                   
                        <td class="hiddable" colspan="<?php echo count ($ventasxPeriodo[$kperiodos])+1 ?>" style="text-align: center"  mycolspan="<?php echo count ($ventasxPeriodo[$kperiodos])+1 ?>" >Base Real</td><!--1-->                                                                                                      
                    <?php
                    }
                    ?>
                </tr><!--fin Cabecera Periodos-->
                <tr>
                    <td></td>
                    <td></td>
                <?php
                    foreach ($ventasxPeriodo as $kperiodos => $periodos) {                 
                        foreach ($ventasxPeriodo[$kperiodos] as $kactividad =>$actividad) { ?>                   
                            <td class="hiddable"><?php echo $actividadclientes[$kactividad]; ?></td><!--1-->                                                                
                     <?php
                        }   
                        ?>
                        <td>Total</td><!--Total Base Real-->                             
                    <?php
                    }
                    ?>
                </tr><!--fin Cabecera Periodos-->
            </thead>
            <tbody>
                <?php
                $p = 1;
                foreach ($provincias as $kp => $provincia) {
                    if(!isset($totalVentasProvincia[$provincia])){
                        $totalVentasProvincia[$provincia]=0;      
                    } 
                    ?>
                    <tr>
                        <td><?php echo $provincia?></td>
                        <td><?php echo number_format($totalVentasProvincia[$provincia], 2, ",", ".")?></td>
                    <?php
                        foreach ($ventasxPeriodo as $kperiodos =>$periodos) {      
                            if(!isset($totalVentasProvincia[$kperiodos])){
                                $totalVentasProvincia[$kperiodos]=[];      
                            } 
                            if(!isset($totalVentasProvincia[$kperiodos][$provincia])){
                                $totalVentasProvincia[$kperiodos][$provincia]=0;      
                            } 
                            foreach ($ventasxPeriodo[$kperiodos] as $kactividad =>$actividad) {              
                                $monto = isset($ventasxPeriodo[$kperiodos][$kactividad][$provincia])?$ventasxPeriodo[$kperiodos][$kactividad][$provincia]:0; 
                                ?>                   
                                <td class="hiddable"><?php echo number_format($monto, 2, ",", "."); ?></td>
                                <?php
                            }   
                            ?>
                            <td><?php echo (isset($totalVentasProvincia[$kperiodos][$provincia]))?$totalVentasProvincia[$kperiodos][$provincia]:0;  ?></td>
                            <?php
                        }
                         if($provincia=='nBuenos Aires'||$provincia=='nSalta'||$provincia=='nCordoba')
                            die("die".$provincia);
                        ?>
                    </tr><!--fin Cabecera Periodos-->    
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <?php
}?>

