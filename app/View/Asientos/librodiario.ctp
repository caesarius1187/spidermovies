<?php
echo $this->Html->css('bootstrapmodal');
echo $this->Html->script('jquery-ui.js',array('inline'=>false));

echo $this->Html->script('jquery.table2excel',array('inline'=>false));
echo $this->Html->script('asientos/librodiario',array('inline'=>false));
?>
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>-->
<!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css"/>-->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.2/css/buttons.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.1.2/css/select.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.0.2/css/responsive.dataTables.min.css"/>

<!--<script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>-->

<script src="https://cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/select/1.1.2/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.0.2/js/dataTables.responsive.min.js"></script>
<div class="index" style="padding: 0px 1%; margin-bottom: 10px;" id="headerCliente">
    <div style="width:60%; float: left;padding-top:10px">
            Cliente: <?php echo $cliente["Cliente"]['nombre'];
            echo " Fecha de Nacimiento/Constitucion: ".date('d-m-Y', strtotime($cliente["Cliente"]['fchcumpleanosconstitucion'])); 
            echo $this->Form->input('clientenombre',['type'=>'hidden','value'=>$cliente["Cliente"]['nombre']]);?>
    </div>
    <div style="width:25%; float: left;padding-top:10px">
            Periodo: <?php echo $periodo;
            echo $this->Form->input('periododefault',['type'=>'hidden','value'=>$periodo]);
            $peanio = substr($periodo, 3);
            echo $this->Form->input('peranio',['type'=>'hidden','value'=>$peanio]);
            echo $this->Form->input('isajaxrequest',['type'=>'hidden','value'=>$isajaxrequest])?>
    </div>
    <table class="noExl" >
        <tr>
            <td style="text-align: left;">
                <h2>Libro diario</h2>
                <?php
                echo "<h3>del periodo  ".$fechaInicioConsulta." hasta ".$fechaFinConsulta."</h3>";
                ?>
            </td>
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
        </tr>
    </table>
</div>
<div class="index" style="float:none;">
	
	<table id="tblLibroDiario" cellpadding="0" cellspacing="0" border="0" class="tbl_border" >
		<thead>
			<tr>
                            <?php 
                            $diffAno = "";

                            $d1 = new DateTime($cliente["Cliente"]['fchcumpleanosconstitucion']);
                            $d2 = new DateTime($fechaFinConsulta);

                            $diff = $d2->diff($d1);

                            $diffAno = $diff->y;


                                    ?>
				<th colspan="2"><?php echo $cliente["Cliente"]['nombre']." EJ ".$diffAno." ".date('dmy',strtotime($fechaFinConsulta));?> </th>
			</tr>
			<tr>
				<th colspan="2"><?php echo $cliente["Domicilio"][0]['calle'];?></th>				
			</tr>
                        <tr>
                            <th colspan="4">Libro Diario &numero; - <?php echo "Del periodo  ".date('d-m-Y', strtotime($fechaInicioConsulta))." hasta ".date('d-m-Y',strtotime($fechaFinConsulta)); ?></th>				
			</tr>
                        <tr>
				<th>Cuenta </th>
                                <th>Descripcion </th>
                                <th>Debito </th>
                                <th>Credito </th>
			</tr>
		</thead>

		<tbody>
		<?php
		$totalDebe=0;
		$totalHaber=0;
		$saldoacumulado=0;
                $numAsiento = 1; 
		foreach ($asientos as $asiento)
		{
                    ?>
                    <tr class="rowasiento">
                        <td colspan="4">
                           Asiento: <?php echo $numAsiento." ".date("d-m-Y", strtotime($asiento['Asiento']['fecha']))." ".$asiento['Asiento']['nombre']; ?>
                        </td>
                    </tr>
                    <?php
			foreach ($asiento['Movimiento'] as $movimiento) { ?>
                            <tr>
				<td>
                                    <?php echo $movimiento['Cuentascliente']['Cuenta']['numero'];?>
				</td>
				<td>
                                    <?php echo $movimiento['Cuentascliente']['Cuenta']['nombre'];?>
				</td>
				<td class="tdWithNumber">
                                    <?php echo $movimiento['debe']; ?>
				</td>
				<td class="tdWithNumber">
                                    <?php echo $movimiento['haber']; ?>
				</td>
                            </tr>
                            <?php
			}
			?>
                    <tr class="rowasiento">
                        <td colspan="4">
                            <hr color="#000000" style='width:100%' />
                        </td>
                    </tr>
			<?php
                        $numAsiento++;
		}
		?>
		</tbody>
		<tfoot>
		<tr>
			
		</tr>
		</tfoot>
	</table>
	<div id="divEditarAsiento"></div>
</div>