<?php echo $this->Html->script('http://code.jquery.com/ui/1.10.1/jquery-ui.js',array('inline'=>false)); 
echo $this->Html->script('jquery.table2excel',array('inline'=>false));
echo $this->Html->script('papelesdetrabajos/autonomo',array('inline'=>false));
echo $this->Form->input('periodoPDT',array('value'=>$periodo,'type'=>'hidden'));
echo $this->Form->input('impcliidPDT',array('value'=>$impcliid,'type'=>'hidden'));
echo $this->Form->input('clinombre',array('value'=>$impcli['Cliente']['nombre'],'type'=>'hidden'));
echo $this->Form->input('impclinombre',array('value'=>$impcli['Impuesto']['nombre'],'type'=>'hidden'));?>

<div class="index">
	<div id="Formhead" class="clientes papeldetrabajosindicato index" style="margin-bottom:10px;">
		<h2>Aportes Seg. Social Autónomos </h2>
		Contribuyente: <?php echo $impcli['Cliente']['nombre']; ?></br>
		CUIT: <?php echo $impcli['Cliente']['cuitcontribullente']; ?></br>
		Periodo: <?php echo $periodo; ?>
        <?php echo $this->Form->button('Imprimir',
            array('type' => 'button',
                'class' =>"btn_imprimir",
                'onClick' => "imprimir()"
            )
        );?>
        <?php echo $this->Form->button('Excel',
            array('type' => 'button',
                'id'=>"clickExcel",
                'class' =>"btn_imprimir",
            )
        );?>
	</div>
	<div id="sheetAutonomo" class="index">
        <?php
            if($impcli['Impcli']['autonomocategoria_id']*1==0){
                echo "<h3>No se ha seleccionado una categoria para el impuesto de este cliente</h3>";
            }
        ?>
		<table class="tblInforme tbl_border" cellspacing="0" id="tblSindicatos">
            <?php
            $montoAPagar=0;
            $montoCategoria=0;
            foreach ($autonomocategorias as $categoria){
                $trstyle="";
                if($categoria['Autonomocategoria']['id']==$impcli['Impcli']['autonomocategoria_id']){
                    $trstyle = "background-color: #1e88e5;";
                    if(count($categoria['Autonomoimporte'])>0){
                        $montoAPagar = $categoria['Autonomoimporte'][0]['importe'];
                    }
                }
                if(count($categoria['Autonomoimporte'])>0){
                    $montoCategoria = $categoria['Autonomoimporte'][0]['importe'];
                }
                ?>
                <tr style="<?php echo $trstyle?>">
                    <td><?php echo $categoria['Autonomocategoria']['rubro']?></td>
                    <td><?php echo $categoria['Autonomocategoria']['tipo']?></td>
                    <td><?php echo $categoria['Autonomocategoria']['tabla']?></td>
                    <td><?php echo $categoria['Autonomocategoria']['categoria']?></td>
                    <td><?php echo $categoria['Autonomocategoria']['codigo']?></td>
                    <td><?php echo $montoCategoria?></td>
                </tr>
            <?php
            }
            ?>
		</table>
	</div>
    <?php echo $this->Form->input('apagarAutonomo',array('value' => $montoAPagar,'type'=>'hidden' )); ?>
	<div id="divLiquidarAutonomo">
	</div>
    <div id="divContenedorContabilidad" style="margin-top:10px">
        <div class="index" id="AsientoAutomaticoDevengamientoAutonomo">
                <?php
                $Asientoid=0;
                $movId=[];
                if(isset($impcli['Asiento'])){
                    if(count($impcli['Asiento'])>0) {
                        $Asientoid = $impcli['Asiento'][0]['id'];
                        foreach ($impcli['Asiento'][0]['Movimiento'] as $mimovimiento) {
                            $movId[$mimovimiento['Cuentascliente']['cuenta_id']] = $mimovimiento['id'];
                        }
                    }
                }
                //ahora vamos a reccorer las cuentas relacionadas al Autonomo y las vamos a cargar en un formulario de Asiento nuevo
                echo $this->Form->create('Asiento',['class'=>'formTareaCarga formAsiento','controller'=>'asientos','action'=>'add']);
                echo $this->Form->input('Asiento.0.id',['value'=>$Asientoid]);
                $d = new DateTime( '01-'.$periodo );

                echo $this->Form->input('Asiento.0.fecha',array(
                    'class'=>'datepicker',
                    'type'=>'text',
                    'label'=>array(
                        'text'=>"Fecha:",
                    ),
                    'readonly','readonly',
                    'value'=>$d->format( 't-m-Y' ),
                    'div' => false,
                    'style'=> 'height:9px;display:inline'
                ));
                echo $this->Form->input('Asiento.0.nombre',['readonly'=>'readonly','value'=>"Devengamiento Autonomo" ,'style'=>'width:250px']);
                echo $this->Form->input('Asiento.0.descripcion',['readonly'=>'readonly','value'=>"Automatico",'style'=>'width:250px']);
                echo $this->Form->input('Asiento.0.cliente_id',['value'=>$impcli['Cliente']['id'],'type'=>'hidden']);
                echo $this->Form->input('Asiento.0.impcli_id',['value'=>$impcli['Impcli']['id'],'type'=>'hidden']);
                echo $this->Form->input('Asiento.0.periodo',['value'=>$periodo,'type'=>'hidden']);
                echo $this->Form->input('Asiento.0.tipoasiento',['value'=>'impuestos','type'=>'hidden'])."</br>";
                $i=0;
                foreach ($impcli['Impuesto']['Asientoestandare'] as $asientoestandarautonomo) {
                    if(!isset($movId[$asientoestandarautonomo['cuenta_id']])){
                        $movId[$asientoestandarautonomo['cuenta_id']]=0;
                    }
                    $cuentaclienteid=0;
                    $cuentaclientenombre=$asientoestandarautonomo['Cuenta']['nombre'];
                    foreach ($impcli['Cliente']['Cuentascliente'] as $cuentaclientaAutonomo){
                        if($cuentaclientaAutonomo['cuenta_id']==$asientoestandarautonomo['cuenta_id']){
                            $cuentaclienteid=$cuentaclientaAutonomo['id'];
                            $cuentaclientenombre=$cuentaclientaAutonomo['nombre'];
                            break;
                        }
                    }
                    echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.id',['value'=>$movId[$asientoestandarautonomo['cuenta_id']],]);
                    echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.fecha', array(
                        'readonly'=>'readonly',
                        'class'=>'datepicker',
                        'type'=>'hidden',
                        'label'=>array(
                            'text'=>"Vencimiento:",
                            "style"=>"display:inline",
                        ),
                        'readonly','readonly',
                        'value'=>date('d-m-Y'),
                        'div' => false,
                        'style'=> 'height:9px;display:inline'
                    ));
                    echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuentascliente_id',['readonly'=>'readonly','type'=>'hidden','value'=>$cuentaclienteid]);
                    echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.cuenta_id',['readonly'=>'readonly','type'=>'hidden','orden'=>$i,'value'=>$asientoestandarautonomo['cuenta_id'],'id'=>'cuenta'.$asientoestandarautonomo['cuenta_id']]);
                    echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.numero',['label'=>($i!=0)?false:'Numero','readonly'=>'readonly','value'=>$asientoestandarautonomo['Cuenta']['numero'],'style'=>'width:82px']);
                    echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.nombre',['label'=>($i!=0)?false:'Nombre','readonly'=>'readonly','value'=>$cuentaclientenombre,'type'=>'text','style'=>'width:250px']);
                    echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.debe',['label'=>($i!=0)?false:'Debe','value'=>0,]);
                    echo $this->Form->input('Asiento.0.Movimiento.'.$i.'.haber',['label'=>($i!=0)?false:'Haber','value'=>0,])."</br>";
                    $i++;
                }

                echo $this->Form->submit('Contabilizar',['style'=>'display:none']);
                echo $this->Form->end();
                ?>
        </div>
    </div>
</div>
