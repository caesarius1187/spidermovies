<?php echo $this->Html->script('jquery-ui',array('inline'=>false));?>
<?php echo $this->Html->script('jquery.table2excel',array('inline'=>false));?>
<?php echo $this->Html->script('clientes/informefinancierotributario',array('inline'=>false));?>
<div id="Formhead" class="clientes informefinancierotributario index" style="margin-bottom:10px; font-family: 'Arial'">
	<!--<input class="button" type="button" id="btnHiddeForm" onClick="hideForm()" value="Ocultar" style="float:right;"/>-->
	<?php echo $this->Form->create('clientes',array('action' => 'informefinancierotributario')); ?> 
    <table class="tbl_informefinancierotributario tblInforme">        
        <tr>
            <td>
              <?php
                echo $this->Form->input('gclis', array(
                    'type' => 'select',
                    'label' => 'Grupos de clientes',
                    'class'=>'chosen-select',
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
            <td>
             <?php echo $this->Form->input('periodoanio', array(
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
                                                    'placeholder' => 'Por favor seleccione año',
                                                    'default' =>  date("Y")
                                                    )
                                        );?>
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
    //Calculos de Montos Actuales
    $ingresosTotal=0;
    $ventasTotal=0;
    $comprasTotal=0;
    $sueldosTotal=0;
    $totalPlanificado=0;
    $honorariosTotal=0;
    $ingresosActualesClientes = array();
    $sueldosActualesClientes = array();
    $comprasActualesClientes = array();
    $sueldoActualesClientes = array();
    $deudasActuales = 0;
    $recibosActuales = 0;
    $honorariosActuales = 0;
    $ingresosActuales = 0;
    $sueldosActuales = 0;
    $comprasActuales = 0;
    foreach ($grupoclientesActual as $gcliActual ) {
        
        foreach ($gcliActual['Cliente'] as $cliente) {
            $ingresosActualesClientes[$cliente['id']] = 0;
            $comprasActualesClientes[$cliente['id']] = 0;
            $sueldosActualesClientes[$cliente['id']] = 0;
            $comprasActualesClientes[$cliente['id']] = 0;
            $sueldoActualesClientes[$cliente['id']] = 0;
            //calculo de Deuda
            foreach ($cliente['Impcli'] as $impcli) {
                $impuestoActivo = false;
                if (count($impcli['Periodosactivo'])>0) {
                    $impuestoActivo = true;
                    foreach ($impcli['Eventosimpuesto'] as $eventoimpuesto) {
                        $deudasActuales+=$eventoimpuesto['montovto'];
                    }
                }

            }
            foreach ($cliente['Plandepago'] as $plandepago) {
                   $deudasActuales+=$plandepago['montovto'];
            }
            //calculo de Depositos
            foreach ($cliente['Deposito'] as $deposito) {
                 $recibosActuales+=$deposito['monto'];
            }
            //Calculo de Honorarios
            foreach ($cliente['Honorario'] as $honorario) {
                $honorariosActuales+=$honorario['monto'];
            }
            //Calculo de Ingresos(Ventas)
            foreach ($cliente['Venta'] as $venta) {
                if($venta["tipodebito"]=='Restitucion debito fiscal'){
                    $ingresosActuales -= $venta['total'];
                    $ingresosActualesClientes[$cliente['id']] -= $venta['total'];
                }else{
                    $ingresosActuales += $venta['total'];
                    $ingresosActualesClientes[$cliente['id']] += $venta['total'];
                }

            }
            //Calculo de Egresos(Compras + Sueldos)
            foreach ($cliente['Compra'] as $compra) {
                if($compra["tipocredito"]=='Restitucion credito fiscal'){
                    $comprasActuales+=$compra['total'];
                    $comprasActualesClientes[$cliente['id']] -= $compra['total'];
                }else{
                    $comprasActuales+=$compra['total'];
                    $comprasActualesClientes[$cliente['id']] += $compra['total'];
                }
            }
            foreach ($cliente['Sueldo'] as $sueldo) {
                $sueldosActuales+=$sueldo['monto'];
                $sueldoActualesClientes[$cliente['id']] += $sueldo['monto'];
            }
        }                        
    }
    $ingresosTotal=$ingresosActuales;
    $comprasTotal=$comprasActuales;
    $sueldosTotal=$sueldosActuales;
    $honorariosTotal=$honorariosActuales;
    $totalActual=$recibosActuales*1-$deudasActuales*1-$honorariosActuales*1;
    $totalPlanificado=$deudasActuales;
    //Calculos de Montos Historicos
    $deudasHistoricos = 0;
    $recibosHistoricos = 0;
    $honorariosHistoricos = 0;
    $ingresosHistoricos = 0;
    $comprasHistoricos = 0;
    $sueldosHistoricos = 0;
    foreach ($grupoclientesHistorial as $gcliHistoricos ) {
     
        foreach ($gcliHistoricos['Cliente'] as $clienteh) {
            //calculo de Deuda
            foreach ($clienteh['Impcli'] as $impclih) {
                foreach ($impclih['Eventosimpuesto'] as $eventoimpuestoh) {
                   $deudasHistoricos+=$eventoimpuestoh['montovto'];
                }
            }
            foreach ($clienteh['Plandepago'] as $plandepagoh) {
                   $deudasHistoricos+=$plandepagoh['montovto'];
            }
            //calculo de Depositos
            foreach ($clienteh['Deposito'] as $depositoh) {
                 $recibosHistoricos+=$depositoh['monto'];
            }
            //Calculo de Honorarios
            foreach ($clienteh['Honorario'] as $honorarioh) {
                $honorariosHistoricos+=$honorarioh['monto'];
            }               
             //Calculo de Ingresos(Ventas)
            foreach ($clienteh['Venta'] as $ventah) {
                $ingresosHistoricos+=$ventah['total'];
            }
            //Calculo de Egresos(Compras + Sueldos)
            foreach ($clienteh['Compra'] as $comprah) {
                $comprasHistoricos+=$comprah['total'];
            }
            foreach ($clienteh['Sueldo'] as $sueldoh) {
                $sueldosHistoricos+=$sueldoh['monto'];
            } 
        }        
    }
    $totalAnterior=$recibosHistoricos*1-$deudasHistoricos*1-$honorariosHistoricos*1;

    $totalGral = $totalActual+ $totalAnterior
    ?>
<div class="index" id="index" style="font-size:14px;">
<table id="situacionIntegral" class="tblInforme tblTributarioFinanciero " cellpadding="0" cellspacing="0" style="margin-bottom: 0px;">
    <tr id="titulo">
        <td colspan="3">
            <label style='font-size:20px;font-weight:bold;text-align:center;'>Informe Tributario Financiero</label>
            
        </td>
    </tr><!-- fin titulo-->
    
    <tr id="periodo" style='text-align:left;'>
        <td style="width: 100%;" colspan="3">
            <table id="tblit" align="center" cellspacing="0" cellpadding="2"  >
                <tr>                    
                    <td width="150" style='text-align:left;background:#FFFFFF' colspan="2">
                        <span style="width:40px">Grupo:</span> <span style="width:100px"><?php echo $grupoclientesActual[0]['Grupocliente']["nombre"];?></span>
                    </td>
                    <td colspan="2" style='text-align:left;'>
                        Periodo: <?php echo $periodomes."-".$periodoanio;?>
                    </td>
                </tr> 
               
                <tr>
                    <td colspan="3">
                        <span id="lblit">
                            Totales Generales del Grupo
                        </span>
                    </td>
                </tr>
                <tr>
                     <td colspan="3">
                        <span title="Acumulado de Depositos del Periodo" style="display: inline-block;width:100px"> Depositos </span>-
                        <span title="Acumulado de Impuestos del Periodo" style="display: inline-block;width:100px"> Impuestos </span>-
                        <span title="Acumulado de Honorarios del Periodo" style="display: inline-block;width:100px">Honorarios</span>+
                        <span title="Acumulado de Depositos-Impuestos-Honorarios del los Periodos Anteriores" style="display: inline-block; width:100px">Periodo Ant.</span>=
                        <span title="Total a Pagar en el Periodo" style="display: inline-block;width:100px">
                        <?php if($totalGral>0){echo 'A Favor';}else{echo 'A Pagar';}?></span>
                    </td>
                </tr>                
                <tr>
                    <td colspan="3">
                        <span title="Acumulado de Depositos del Periodo" 
                        <?php if($recibosActuales>=0){echo "style='color:#0C0;display: inline-block;width:100px'";} 
                                else{echo "style='color:#FF0000'";}
                        ?>
                        width="100" >
                            <?php echo "$".number_format($recibosActuales, 2, ",", ".");?>
                        </span>-
                        <span title="Acumulado de Depositos del Periodo" style='color:#FF0000;display: inline-block;width:100px' width="100"><?php echo "$".number_format($deudasActuales, 2, ",", "."); ?></span>-
                        <span title="Acumulado de Honorarios del Periodo" style='color:#FF0000;display: inline-block;width:100px' width="100"><?php echo "$".number_format($honorariosActuales, 2, ",", "."); ?></span>+
                        <span  title="Acumulado de Depositos-Impuestos-Honorarios del los Periodos Anteriores" 
                        <?php if($totalAnterior>=0){
                            echo "style='color:#0C0;display: inline-block;width:100px'";} 
                        else{
                            echo "style='color:#FF0000;display: inline-block;width:100px'";}
                            ?>   width="100" >
                        <?php echo "$".number_format($totalAnterior, 2, ",", ".");?>
                        </span>=
                        <span title="Total a Pagar en el Periodo" 
                            <?php if($totalGral>=0){
                                echo ' style="color:#0C0;display: inline-block;width:100px">$'.number_format($totalGral, 2, ",", ".");}
                         else{
                                echo ' style="color:#FF0000;display: inline-block;width:100px">$'.number_format($totalGral*-1, 2, ",", ".");}
                                ?>   
                        </span>  
                    </td>  
                 </tr>
            </table> 
        </td>
    </tr><!-- fin periodo -->
    <?php
    foreach ($grupoclientesActual as $gcliActual ) {       
     if(count($gcliActual['Cliente'])>1) {?>
    <tr>
        <td>
            <span id="lblit" style='display: inline-block;width:100px'>Ventas</span>-
            <span id="lblit" style='display: inline-block;width:100px'>Compras</span>-
            <span id="lblit" style='display: inline-block;width:100px'>Sueldos</span>-
            <span id="lblit" style='display: inline-block;width:100px'>Impuestos</span>=
            <span id="lblit" style='display: inline-block;width:100px'>Mov. Neto</span>
        </td>
    </tr>
    <tr>
        <td>
            <span id="lblit" style='color:#0C0;display: inline-block;width:100px'><?php echo "$".number_format($ingresosTotal, 2, ",", ".")?></span>-
            <span id="lblit" style='color:#FF0000;display: inline-block;width:100px'><?php echo "$".number_format($comprasTotal, 2, ",", ".")?></span>-
            <span id="lblit" style='color:#FF0000;display: inline-block;width:100px'><?php echo "$".number_format($sueldosTotal, 2, ",", ".")?></span>-
            <span style='color:#FF0000;display: inline-block;width:100px'> <?php echo "$".number_format($totalPlanificado, 2, ",", ".")?></span>=
            <?php $saldoTotal=$ingresosTotal-$comprasTotal-$sueldosTotal-$totalPlanificado; 
            $color="0C0";
            if($saldoTotal<0)$color="FF0000";
            ?>
            <span id="lblit" style='color:#<?php echo $color;?>;display: inline-block;width:100px'><?php echo "$".number_format($saldoTotal, 2, ",", ".");?></span>
        </td>
    </tr>
    <?php } 
    }
?>
    

    <tr>
        <td id="tdTotGral" colspan="3">
            <table cellspacing="0" style='padding:0px 9px 0px;width: inherit;' id="tblit">
                    <tr>    
                        <td style='border:1px solid #333;text-align:left;' width="85"><span id="lblit" >Vencimientos</span></td>
                        <td style='border:1px solid #333;text-align:left;' width="115"><span id="lblit" >A Pagar</span></td>
                        <td style='border:1px solid #333;text-align:left;' width="115"><span id="lblit" >Pagado</span></td>
                        <td style='border:1px solid #333;text-align:left;' width="115"><span id="lblit" >Deuda</span></td>
                    </tr>  
                    <tr>
                        <td style='border:1px solid #333;text-align:left;text-align:left'><span id="lblit">Hasta d&iacute;a 2</span></td>
                        <?php
                         
                        $planificadoAl2=0;
                        $pagadoAl2=0;

                        $planificadoAl7=0;
                        $pagadoAl7=0;

                        $planificadoAl12=0;
                        $pagadoAl12=0;

                        $planificadoAl18=0;
                        $pagadoAl18=0;
                        foreach ($grupoclientesActual as $gcliActual ) {
                            foreach ($gcliActual['Cliente'] as $cliente) {
                                foreach ($cliente['Impcli'] as $impcli) {
                                    if (count($impcli['Periodosactivo'])>0) {
                                        foreach ($impcli['Eventosimpuesto'] as $eventosimpuesto) {
                                            if (date("d", strtotime($eventosimpuesto['fchvto'])) < 07) {
                                                //menor que 07
                                                $planificadoAl2 += $eventosimpuesto['montovto'];
                                                $pagadoAl2 += $eventosimpuesto['montorealizado'];
                                            } else if (date("d", strtotime($eventosimpuesto['fchvto'])) < 12) {
                                                //menor que 12 y mayor que 07
                                                $planificadoAl7 += $eventosimpuesto['montovto'];
                                                $pagadoAl7 += $eventosimpuesto['montorealizado'];
                                            } else if (date("d", strtotime($eventosimpuesto['fchvto'])) < 18) {
                                                //menor que 18 y mayor que 12
                                                $planificadoAl12 += $eventosimpuesto['montovto'];
                                                $pagadoAl12 += $eventosimpuesto['montorealizado'];
                                            } else {
                                                //mayor que 18
                                                $planificadoAl18 += $eventosimpuesto['montovto'];
                                                $pagadoAl18 += $eventosimpuesto['montorealizado'];
                                            }
                                        }
                                    }
                                }
                                foreach ($cliente['Plandepago'] as $plandepago) { 
                                    if(date("d",strtotime($plandepago['fchvto']))<07){
                                        //menor que 07
                                        $planificadoAl2+=$plandepago['montovto'];
                                        $pagadoAl2+=$plandepago['montorealizado'];
                                    }else if(date("d",strtotime($plandepago['fchvto']))<12){
                                        //menor que 12 y mayor que 07
                                        $planificadoAl7+=$plandepago['montovto'];
                                        $pagadoAl7+=$plandepago['montorealizado'];
                                    } else if(date("d",strtotime($plandepago['fchvto']))<18){
                                            //menor que 18 y mayor que 12
                                            $planificadoAl12+=$plandepago['montovto'];
                                            $pagadoAl12+=$plandepago['montorealizado'];
                                    }else {
                                        //mayor que 18
                                        $planificadoAl18+=$plandepago['montovto'];
                                        $pagadoAl18+=$plandepago['montorealizado'];
                                    }
                                } 
                            }
                        }?>                                                                                        
                        <td style='border:thin solid #333 ;text-align:right;'>
                            <span id="lblit" ><?php echo "$".number_format($planificadoAl2, 2, ",", ".")?></span>
                        </td>
                        <td style='border:thin solid #333 ;text-align:right;'> 
                            <span id="lblit" ><?php echo "$".number_format($pagadoAl2, 2, ",", ".")?></span>
                        </td>
                        <td style='border:thin solid #333 ;text-align:right;'> 
                            <span id="lblit" ><?php 
                            $diferencia2 = $planificadoAl2 - $pagadoAl2;
                            echo "$".number_format($diferencia2, 2, ",", ".")?></span>
                        </td>                                                                  
                    </tr>
                    <tr>
                        <td style='border:1px solid #333;text-align:left;text-align:left'>
                            <span id="lblit">
                                Hasta d&iacute;a 7
                            </span>
                        </td>
                                                                                                                  
                        <td style='border:thin solid #333 ;text-align:right;'>
                            <span id="lblit" ><?php echo "$".number_format($planificadoAl7, 2, ",", ".")?></span>
                        </td>
                        <td style='border:thin solid #333 ;text-align:right;'> 
                            <span id="lblit" ><?php echo "$".number_format($pagadoAl7, 2, ",", ".")?></span>
                        </td>
                        <td style='border:thin solid #333 ;text-align:right;'> 
                            <span id="lblit" ><?php $diferencia7 = $planificadoAl7 - $pagadoAl7;
                            echo "$".number_format($diferencia7, 2, ",", ".")?></span>
                        </td>                                                                  
                    </tr>
                    <tr>
                        <td style='border:1px solid #333;text-align:left;text-align:left'>
                            <span id="lblit">
                                Hasta d&iacute;a 12
                            </span>
                        </td>
                                                                                        
                        <td style='border:thin solid #333 ;text-align:right;'>
                            <span id="lblit" ><?php echo "$".number_format($planificadoAl12, 2, ",", ".")?></span>
                        </td>
                        <td style='border:thin solid #333 ;text-align:right;'> 
                            <span id="lblit" ><?php echo "$".number_format($pagadoAl12, 2, ",", ".")?></span>
                        </td>
                        <td style='border:thin solid #333 ;text-align:right;'> 
                            <span id="lblit" ><?php $diferencia12 = $planificadoAl12 - $pagadoAl12;
                            echo "$".number_format($diferencia12, 2, ",", ".")?></span>
                        </td>                                                                  
                    </tr>
                    <tr>
                        <td style='border:1px solid #333;text-align:left;text-align:left'>
                            <span id="lblit">
                                Hasta d&iacute;a 18
                            </span>
                        </td>
                                                                                         
                        <td style='border:thin solid #333 ;text-align:right;'>
                            <span id="lblit" ><?php echo "$".number_format($planificadoAl18, 2, ",", ".")?></span>
                        </td>
                        <td style='border:thin solid #333 ;text-align:right;'> 
                            <span id="lblit" ><?php echo "$".number_format($pagadoAl18, 2, ",", ".")?></span>
                        </td>
                        <td style='border:thin solid #333 ;text-align:right;'> 
                            <span id="lblit" ><?php $diferencia18 = $planificadoAl18 - $pagadoAl18;
                            echo "$".number_format($diferencia18, 2, ",", ".")?></span>
                        </td>                                                                  
                    </tr>
                    <tr>
                        <td style='border:1px solid #333;text-align:left;'>
                            <span id="lblit">
                                Totales
                            </span>
                        </td>
                        <td style='border:thin solid #333 ;text-align:right;'><?php echo  "$".number_format($planificadoAl2+$planificadoAl7+$planificadoAl12+$planificadoAl18, 2, ",", ".");?></td>
                        <td style='border:thin solid #333 ;text-align:right;'><?php echo  "$".number_format($pagadoAl2+$pagadoAl7+$pagadoAl12+$pagadoAl18, 2, ",", ".");?></td>
                        <td style='border:thin solid #333 ;text-align:right;'><?php echo  "$".number_format($diferencia2+$diferencia7+$diferencia12+$diferencia18, 2, ",", ".");?></td>
                    </tr>
            </table> 
        </td>
    </tr>
    <tr>
        <td align="center" colspan="6">
            <hr width="450px" color="#000000" style='width:100%' />
        </td>
    </tr>
   <?php  
   foreach ($grupoclientesActual as $gcliActual ) {       
        foreach ($gcliActual['Cliente'] as $cliente) { ?>

    <!-- Inicio Tabla Cliente -->
    <tr class="cliente"  >          
        <td width="100%" style='text-align:left;'>
            <span id="lblit" style="display: inline-block;width:40px">
                Cliente:
            </span>
            <span id="lblit" style="display: inline-block;">
                <?php echo $cliente["nombre"];?>
            </span>
            <span id="lblit" style="display: inline-block;width:35px;margin-left:10px">
                Cuit :
            </span> 
            <span id="lblit" style="display: inline-block;width:100px">
                <?php echo $cliente["cuitcontribullente"];?>
            </span>
        <td>
    </tr><!-- fin cliente -->
    <tr>
        <td>
            <span style='display: inline-block;width:100px' align="left">Ventas</span>-
            <span style='display: inline-block;width:100px' align="left">Compras</span>-
            <span style='display: inline-block;width:100px' align="left">Sueldos</span>-
            <span style='display: inline-block;width:100px' align="left">Impuestos</span>=
            <span style='display: inline-block;width:100px' align="left">Mov. Neto</span> 
                                       
        </td>   
    </tr>
    
    <!--INFORME RESUMEN TRIBUTARIO-->              
    <tr class="resultado" >
        <td> 
            <table style="padding: 0px 0px 0px 6px;font-size:10px" cellspacing="0"  id="tblit">
                <tr>
                    <td style='border:1px solid #333;text-align:left;' width="125px">
                        <span id="lblit">Tributario</span>
                    </td>
                    <td style='border:1px solid #333;text-align:left;' width="125px">
                        Datos Ad.
                    </td>
                    <td style='border:1px solid #333;text-align:left;' width="32px">
                        Form.
                    </td>
                    <td style='border:1px solid #333;text-align:left;' width="95px">
                        A Favor
                    </td>
                    <td style='border:1px solid #333;text-align:left;' width="95px">
                        A Pagar
                    </td>
                    <td style='border:1px solid #333;text-align:left;' width="95px">
                        Pagado
                    </td>
                    <td style='border:1px solid #333;text-align:left;' width="40px">
                        Vto.
                    </td>                        
                    <td style='border:1px solid #333;text-align:left;' width="60px">
                        Lugar
                    </td>                        
                </tr>
                <?php 
                $tpag=0;
                $tpla=0;
                $tcon=0;
                //Impuestos
                foreach ($cliente['Impcli'] as $impcli) {
                        $eventoimpuestomonc=0;
                        $eventoimpuestomontovto=0;
                        $eventoimpuestmontorealizado=0;
                        if (count($impcli['Periodosactivo'])>0) {
                            foreach ($impcli['Eventosimpuesto'] as $eventoimpuesto) {
                                $eventoimpuestomonc += $eventoimpuesto["monc"];
                                $tcon += $eventoimpuesto["monc"];
                                $eventoimpuestomontovto += $eventoimpuesto["montovto"];
                                $tpla += $eventoimpuesto["montovto"];
                                $eventoimpuestmontorealizado += $eventoimpuesto["montorealizado"];
                                $tpag += $eventoimpuesto["montorealizado"];
                            }
                            //Aca vamos a sumar el saldo a favor del periodo para el IVA (que es el SLD)
                            //Luego sumaremos otros impuestos que sumen saldos a favor de esta forma
                            //como economicas
                            if ($impcli['Impuesto']['id'] == '19') {
                                foreach ($impcli['Conceptosrestante'] as $saldoLibreDisponibilidad) {
                                    $eventoimpuestomonc += $saldoLibreDisponibilidad['montoretenido'];
                                    $tcon += $saldoLibreDisponibilidad['montoretenido'];
                                }
                            }

                        ?>
                        <tr  >
                            <td style='border:thin solid #333 ;text-align:left;' align="left">
                                <?php echo __($impcli['Impuesto']["abreviacion"]);?>
                            </td>
                            <td style='border:thin solid #333 ;text-align:left;' align="left">
                                <?php
                                if(isset($impcli['Eventosimpuesto'][0])){
                                    echo $impcli['Eventosimpuesto'][0]["descripcion"];
                                }?>
                            </td>
                            <td style='border:thin solid #333 ;text-align:left;' align="left">
                                <?php
                                echo $impcli['Impuesto']["descripcion"]?>
                            </td>
                            <td style='border:thin solid #333 ;text-align:right;'>
                                <?php echo "$".number_format($eventoimpuestomonc, 2, ",", "."); ?>
                            </td>
                            <td style='border:thin solid #333 ;text-align:right;'>
                                <?php echo "$".number_format($eventoimpuestomontovto, 2, ",", ".");
                                ?>
                            </td>
                            <td style='border:thin solid #333 ;text-align:right;'>
                                <?php echo "$".number_format($eventoimpuestmontorealizado, 2, ",", ".");
                                ?>
                            </td>
                            <td style='border:thin solid #333 ;text-align:right; padding-left:4px'>
                                <?php
                                if(isset($impcli['Eventosimpuesto'][0])){
                                    echo date("d/m",strtotime($impcli['Eventosimpuesto'][0]["fchvto"]));
                                }?>
                            </td>
                            <td style='border:thin solid #333 ;text-align:left;'>
                                <?php echo $impcli['Impuesto']["lugarpago"];?>
                            </td>
                        </tr>
                    <?php
                        }
                }
                //Planes de Pagos
                foreach ($cliente['Plandepago'] as $plandepago) {   ?>
                     <tr  >
                        <td style='border:thin solid #333 ;text-align:left;' align="left"> 
                           Plan N°<?php echo __($plandepago["plan"]);?>
                        </td>
                        <td style='border:thin solid #333 ;text-align:left;' align="left"> 
                           <?php echo $plandepago["organismo"]?>-<?php echo $plandepago["item"]?>
                        </td>
                        <td style='border:thin solid #333 ;text-align:left;' align="left"> 
                            <?php echo $plandepago["descripcion"]?>
                        </td>
                        <td style='border:thin solid #333 ;text-align:right;'> 
                            
                        </td>
                        <td style='border:thin solid #333 ;text-align:right;'> 
                            <?php echo "$".number_format($plandepago["montovto"], 2, ",", ".");
                                $tpla+=$plandepago["montovto"];
                            ?>
                        </td>
                        <td style='border:thin solid #333 ;text-align:right;'> 
                            <?php echo "$".number_format($plandepago["montorealizado"], 2, ",", ".");
                                $tpag+=$plandepago["montorealizado"];
                            ?>
                        </td> 
                        <td style='border:thin solid #333 ;text-align:right; padding-left:4px'> 
                            <?php echo date("d/m",strtotime($plandepago["fchvto"]));?>
                        </td>                            
                        <td style='border:thin solid #333 ;text-align:left;'> 
                            -
                        </td>  
                    </tr>
                    <?php } ?>                   
                <tr>
                    
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td style='border:thin solid #333 ;background:#CCCCCC;' align='left'>
                        Total 
                    </td>
                    <td style='border:thin solid #333 ;background:#CCCCCC;' align='right'>
                        $<?php echo number_format($tpla, 2, ",", ".");?>
                    </td>                               
                    <td style='border:thin solid #333 ;background:#CCCCCC;' align='right'>
                        $<?php echo number_format($tpag, 2, ",", ".");?>
                    </td>                        
                </tr>
                <tr>
                    <td style='text-align:left' colspan='1' >
                    </th>
                    <td style='text-align:right' >
                    </td>
                    <td ></td>
                    <td ></td>
                    <td style='border:thin solid #333 ;background:#CCCCCC;' align='left'>
                        Deuda 
                    </td>
                    <td style='border:thin solid #333 ;background:#CCCCCC;' align='right'>
                        $<?php 
                        $saldo= $tpla - $tpag;
                        echo number_format($saldo, 2, ",", ".");
                        ?>
                    </td>
                </tr>                    
           </table>
        </td>
    </tr>
    <tr class="rowMovimientoCliente">
        <td Style="width: 100%;">
            <span style='color:#0C0;width:100px;display:inline-block' align="left">
            <?php 
                $totalIngresoCliente=0; 
                echo "$".number_format($ingresosActualesClientes[$cliente['id']], 2, ",", ".");
                $totalIngresoCliente+=$ingresosActualesClientes[$cliente['id']];
            ?>
            </span>-
            <span style='color:#FF0000;width:100px;display:inline-block'> 
            <?php
                $totalEgresoCliente=0; 
                echo "$".number_format($comprasActualesClientes[$cliente['id']], 2, ",", ".");
                $totalEgresoCliente+=$comprasActualesClientes[$cliente['id']];
            ?>
            </span>-
            <span style='color:#FF0000;width:100px;display:inline-block'> 
            <?php
                echo "$".number_format($sueldoActualesClientes[$cliente['id']], 2, ",", ".");
                $totalEgresoCliente+=$sueldoActualesClientes[$cliente['id']];
            ?>
            </span>-
            <span style='color:#FF0000;width:100px;display:inline-block'> 
            <?php
                echo "$".number_format($tpla, 2, ",", ".");
            ?>
            </span>=
            <?php 
                $de = $totalIngresoCliente - $totalEgresoCliente - $tpla;
                $color="#0C0";
                if($de<0)$color="#FF0000";
            ?> 
            <span style='color:<?php echo $color;?>;display:inline-block' title='Ingreso: $<?php echo number_format($totalIngresoCliente, 2, ",", ".");?>,Egreso: $<?php echo number_format($totalEgresoCliente, 2, ",", ".");?>,Impuesto: $<?php echo number_format($tpla, 2, ",", ".");?>' >
                $<?php
                //$de = ingreso - egreso - impuestos 
                echo number_format($de, 2, ",", ".")?>
            </span>
        </td>
    </tr>
    <!-- fin Resumen Tributario -->
    <tr>
        <td align="center">
            <hr width="450px" color="#000000" style='width:100%' />
        </td>
    </tr>     

        <?php }
    } ?>       
</table><!--fin tabla situacion integral -->   
</div>
<?php } ?>