<?php 
function calculateRow($numRow,$iniRow,$finRow,$gcliActual,&$puedePagar,&$totLPago){
    $lugaresdepago=array('rapipago','pagofacil','nacion','macro');
    $colorRojo="color:#F00;";
    $colorAzul="color:#00CC00;";
    $colorVerde="color:#0000FF;";
    $totalRow=0;
    foreach ($lugaresdepago as $lugarpago){           ?>                
        <td id="<?php echo $numRow ?>" class="sithead" valign="top">
            <table cellspacing="0" align="top" style="text-align: center;width: 100%;">  
            <?php                        
            $showempty=true;
            foreach ($gcliActual['Cliente'] as $cliente) {
             foreach ($cliente['Impcli'] as $impcli) { 
                foreach ($impcli['Eventosimpuesto'] as $eventosimpuesto) {
                        if(
                            (date("d",strtotime($eventosimpuesto['fchvto']))>$iniRow)&&
                            (date("d",strtotime($eventosimpuesto['fchvto']))<=$finRow)
                            ){ 
                            if($impcli['Impuesto']['lugarpago']==$lugarpago ){ 
                                            $showempty=false;?>                            
                                 <tr>
                                    <?php 
                                            $color="";
                                            
                                            if($eventosimpuesto["tarea13"]=='realizado'){
                                                $color=$colorAzul;
                                            }
                                            else{ 
                                                $puedePagar-=$eventosimpuesto["montovto"];
                                                if($puedePagar>=-1){
                                                    $color=$colorVerde;                                        
                                                } 
                                                else{ $color=$colorRojo; }
                                            }
                                            $totalRow+=$eventosimpuesto["montovto"];
                                            $totLPago[$lugarpago]+=$eventosimpuesto["montovto"];
                                            $title=$cliente["nombre"]." - ".$impcli["Impuesto"]["nombre"]." - A Pag: ".$eventosimpuesto["montovto"]." - Pag: ".$eventosimpuesto["montorealizado"].$lugarpago ;

                                                    ?> 
                                            <td style="<?php echo $color; ?>" title="<?php echo $title?>">

                                                <?php echo 
                                                $this->Html->link("$".number_format($eventosimpuesto["montovto"],2,",","."),
                                                    array(

                                                        ))
                                                ?>
                                            </td>
                                 </tr>             
                            <?php }   
                             }                             
                    }
                }
            } 
             if($showempty){
                    echo "<tr><td >$0,00</td> </tr>";
                }
            ?></table>
        </td>
    <?php  }?>      
        <td class="sithead" style="text-align:center"><?php echo "$".number_format($totalRow,2,",",".") ?></td>
   <?php }?>
<input class="button" type="button" id="btnShowForm" onClick="showForm()" value="Mostrar" style="display:none" />

<div id="Formhead" class="clientes informepagosdelmes index" style="margin-bottom:10px;">

    <!--<input class="button" type="button" id="btnHiddeForm" onClick="hideForm()" value="Ocultar"/>-->
    <?php echo $this->Form->create('clientes',array('action' => 'informepagosdelmes')); ?> 
    <table class="tbl_informepagosdelmes tblInforme">
        <tr>
            <td><h3></h3> </td>
            <td><h3></h3> </td>
        </tr>
        <tr>                               
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
            ?></td>
            <td>
             <?php echo $this->Form->input('periodoanio', array(
                                                    'options' => array(
                                                        '2012'=>'2012',
                                                        '2013'=>'2013',
                                                        '2014'=>'2014', 
                                                        '2015'=>'2015',
                                                        '2016'=>'2016',     
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
            <td rowspan="2"><?php echo $this->Form->end(__('Aceptar')); ?></td>
        </tr>
    </table>
</div> <!--End Clietenes_avance-->

<?php if(isset($mostrarInforme)){
$colorRojo="color:#F00;";
$colorAzul="color:#00CC00;";
$colorVerde="color:#0000FF;";?>
<div class="index">
<table id="pagosdelmes" cellspacing="0" style="width:60%">
        <tr id="titulo" >
            <td colspan="0" height="30">
                <label class="lbltitulo">PAGOS DEL MES</label>
            </td>
        </tr><!-- fin titulo-->         
        <tr id="periodo" class="sigr">
            <td colspan="0">
                <table  >
                    <tr>                                                             
                        <th align="left"  class="sigr">Periodo: <?php echo $periodomes."-".$periodoanio;?> </th>
                    </tr>
                </table>
            </td>
        </tr><!-- fin periodo -->
    <?php  
    foreach ($grupoclientesActual as $gcliActual ) {?>
        <tr >
            <th class="sithead" colspan="0" align="left">GRUPO:<?php echo $gcliActual["Grupocliente"]["nombre"]?> </th>                        
        </tr>          
        <tr>
            <td rowspan="3" class="sithead" >Vto.</td>
        </tr>
        <tr>    
            <td class="sithead" style="text-align:center;" colspan="4">A Pagar </td>
            <td rowspan="2" class="sitotal" >Total</td>
        </tr>
        <tr>
            <td class='sithead'>RapiPago</td>
            <td class='sithead'>PagoFacil</td>
            <td class='sithead'>Banco Nacion</td>
            <td class='sithead'>Banco Macro</td>
        </tr>    
        <?php 
            //calcular cuanto puede pagar 
            $recibosActuales=0;
            $honorariosActuales=0;
            $pagadoActual=0;
            $honorarioPagado=false;
            $totLPago=array('rapipago'=>0,'pagofacil'=>0,'nacion'=>0,'macro'=>0);
            foreach ($gcliActual['Cliente'] as $cliente) {
              
                //calculo de Depositos
                foreach ($cliente['Deposito'] as $deposito) {
                     $recibosActuales+=$deposito['monto'];
                }
                //calculo de Deuda
                foreach ($cliente['Impcli'] as $impcli) {
                    foreach ($impcli['Eventosimpuesto'] as $eventoimpuesto) {
                        $pagadoActual+=$eventoimpuesto['montorealizado'];                    
                    }
                }
                //Calculo de Honorarios
                foreach ($cliente['Honorario'] as $honorario) {
                    $honorariosActuales+=$honorario['monto'];
                    if($honorario['estado']=='pagado'){
                        $honorarioPagado=true;
                    }
                }  

            }
            $deudasHistoricos = 0;
            $recibosHistoricos = 0;
            $honorariosHistoricos = 0;
            //Calculos de Montos Historicos
            
            foreach ($grupoclientesHistorial as $gcliHistoricos ) {
                if($gcliActual['Grupocliente']['id']==$gcliHistoricos['Grupocliente']['id']){
                    foreach ($gcliHistoricos['Cliente'] as $clienteh) {
                        //calculo de Deuda
                        foreach ($clienteh['Impcli'] as $impclih) {
                            foreach ($impclih['Eventosimpuesto'] as $eventoimpuestoh) {
                               $deudasHistoricos+=$eventoimpuestoh['montovto'];
                            }
                        }
                        //calculo de Depositos
                        foreach ($clienteh['Deposito'] as $depositoh) {
                             $recibosHistoricos+=$depositoh['monto'];
                        }
                        //Calculo de Honorarios
                        foreach ($clienteh['Honorario'] as $honorarioh) {
                            $honorariosHistoricos+=$honorarioh['monto'];
                        }                     
                    }        
                }
            }
            $totalAnterior=$recibosHistoricos*1-$deudasHistoricos*1-$honorariosHistoricos*1;

            // sumar deudaAnterior si es positivo 
            // sumar Agregar un campo a honorarios para ver si esta pagado y si esta pagado hay que restarlo de CAJA 
        
            if($honorarioPagado){
                $sc=$recibosActuales-$pagadoActual+$totalAnterior-$honorariosActuales;
            }else{
                $sc=$recibosActuales-$pagadoActual+$totalAnterior;
            }
            $puedePagar=$sc;
            if($sc<0){
                $sc=0;
            }
            $lugaresdepago=array('macro','nacion','pagofacil','rapipago');
        ?>
        <!-- Inicio ROW 2-->
        <tr id="r2">
            <td id="h2" class="sithead" style="text-align:center" >2</td>
            <?php 
            calculateRow(2,00,07,$gcliActual,$puedePagar,$totLPago);
            ?>
        </tr>
        <!--Fin row 2-->   
        <!-- Inicio ROW 7-->
         <tr id="r7">
            <td id="h7" class="sithead" style="text-align:center" >7</td>
            <?php 
            calculateRow(7,07,12,$gcliActual,$puedePagar,$totLPago);
             ?>
        </tr><!--Fin row 07-->   
        <!-- Inicio ROW 12-->
         <tr id="r12">
            <td id="h12" class="sithead" style="text-align:center" >12</td>
             <?php  
              calculateRow(12,12,18,$gcliActual,$puedePagar,$totLPago);
              ?>  
        </tr><!--Fin row 12-->   
        <!-- Inicio ROW 18-->
         <tr id="r18">
            <td id="h18" class="sithead" style="text-align:center" >18</td>
            <?php
             calculateRow(18,18,55,$gcliActual,$puedePagar,$totLPago);
             ?>
        </tr><!--Fin row 18-->   
        <tr id="rhono">
            <td class="sithead" >Honorarios</td>            
            <td class="sithead" style="text-align:center;" colspan="4"><?php echo "$".number_format($honorariosActuales,2,",",".") ;?> </td>
            <td class="sithead" style="text-align:center;" ><?php echo "$".number_format($honorariosActuales,2,",","."); ?> </td>
        </tr>
        <tr id="rtotales">
        <td class="sitotal" style="text-align:center;">totales</td>
        <?php
        $totpagos=0;
        foreach ($totLPago as $pago ) {?>
            <td class="sithead" style="text-align:center;" ><?php echo "$".number_format($pago,2,",",".");$totpagos+=$pago; ?> </td>
        <?php }
        ?>
        <td class="sitotal" style="text-align:center;"><?php echo "$".number_format($totpagos+$honorariosActuales,2,",","."); ?> </td>
        </tr>
        <tr id="deudas anteriores">
            <td class="sithead" colspan="3" >&nbsp;</td>   
            <td class="sithead" style="text-align:right;" colspan="2">Dinero Mes Anterior:</td>     
            <?php 
            $colorDA="";
            if($totalAnterior>=0){
                $colorDA=$colorVerde;
            }else{
                $colorDA=$colorRojo;
            } ?>
            <td class="sithead" style="text-align:center;<?php echo $colorDA; ?>"><?php echo "$".number_format($totalAnterior,2,",","."); ?></td>
        </tr>
        <tr id="Dinero Recibido">
            <td class="sithead" colspan="3" >&nbsp;</td>   
            <td class="sithead" style="text-align:right;" colspan="2">Dinero Recibido:</td>     
            <td class="sithead" style="text-align:center"><?php echo "$".number_format($recibosActuales,2,",","."); ?></td>
        </tr>
        <tr id="Pagado">
            <td class="sithead" colspan="3" >&nbsp;</td>   
            <td class="sithead" style="text-align:right;" colspan="2">Pagado:</td>      
            <td class="sithead" style="text-align:center"><?php echo "$".number_format($pagadoActual,2,",","."); ?></td>
        </tr>
       
        <tr id="Saldo de Caja">
            <td class="sithead" colspan="3" >&nbsp;</td>   
            <td class="sithead" style="text-align:right;" colspan="2">Saldo de Caja:</td>           
            <td class="sithead" style="text-align:center"><?php echo "$".number_format($sc,2,",","."); ?></td>
        </tr>
        <tr><td colspao="0">&nbsp</td></tr>
       <?php }  } ?>
</table>     
</div>