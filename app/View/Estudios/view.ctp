<?php
echo $this->Html->css('bootstrapmodal');
echo $this->Html->script('jquery-ui',array('inline'=>false));
echo $this->Html->script('estudios/view',array('inline'=>false));
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
?>

<script type="text/javascript">
	$(document).ready(function() {
		$( "input.datepicker" ).datepicker({
			yearRange: "-100:+50",
			changeMonth: true,
			changeYear: true,
			constrainInput: false,
			dateFormat: 'dd-mm-yy',
		});
	});

</script>
<div class="estudios index" style="margin-bottom:5px">
	<div class="fab blue" style="float: right;">
		<core-icon icon="add" align="center" style="margin: 8px 14px;position: absolute;">
			<?php echo $this->Form->button(
				$this->Html->image(
					'edit_view_white_1.png',
					array(
						'alt' => 'edit',
						'class'=>'img_edit',
                        'style' => 'width: 27px; height: 27px; margin: -5px -10px;'
					)
				),
				array('type' => 'button',
					'style' =>"padding:0px;",
					'class' =>"btn_add",
					'escape' =>false,
					'onClick' => "$('#myModal').modal('show');"
						)
				);
			?>
		</core-icon>
		<paper-ripple class="circle recenteringTouch" fit></paper-ripple>
	</div>
<h2><?php echo __('Mi Cuenta'); ?></h2>
	<dl>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($estudio['Estudio']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Propietario'); ?></dt>
		<dd>
			<?php echo h($estudio['Estudio']['propietario']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Direccion'); ?></dt>
		<dd>
			<?php echo h($estudio['Estudio']['direccion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('CUIT'); ?></dt>
		<dd>
			<?php echo h($estudio['Estudio']['cuit']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Ingresos Brutos'); ?></dt>
		<dd>
			<?php echo h($estudio['Estudio']['cuit']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Inicio de actividades'); ?></dt>
		<dd>
			<?php echo h($estudio['Estudio']['inicioactividades']); ?>
			&nbsp;
		</dd>
	</dl>
</div>

<div class="estudios index">
    <h2><?php echo __('Mi Estudio en el periodo '.$periodomes.'-'.$periodoanio); ?></h2>
    <dl>
        <dt><?php echo __('Monotributistas'); ?></dt>
        <dd>
            <?php
            $monotributistas = 0;
            $respInscriptos = 0;
            $topEmpleados=0;
            $convenios=[];
            $nombreClientes = [];
            foreach ($estudio['Grupocliente'] as $grupocliente) {
                foreach ($grupocliente['Cliente'] as $cliente) {
                    $nombreClientes[$cliente['id']]=$cliente['nombre'];
                    foreach ($cliente['Impcli'] as $impcli ) {
                        if($impcli['impuesto_id']==4&&count($impcli['Periodosactivo'])>0){
                            $monotributistas++;
                        }
                        if($impcli['impuesto_id']==19&&count($impcli['Periodosactivo'])>0){
                            $respInscriptos++;
                        }
                    }
                    //Debugger::dump($cliente['nombre'].": ".count($cliente['Empleado'])."</br>");
                    if($topEmpleados<count($cliente['Empleado'])){
                        $topEmpleados = count($cliente['Empleado']);
                        $nombreEmpleados = $cliente['nombre'];
                    }
                    foreach ($cliente['Empleado'] as $empleado ) {
                        if(!in_array($empleado['conveniocolectivotrabajo_id'], $convenios)){
                            $convenios[]=$empleado['conveniocolectivotrabajo_id'];
                        }
                    }
                }
            }
            ?>
                <?php echo h($monotributistas); ?>
                &nbsp;
        </dd>
        <dt><?php echo __('Responsables Inscriptos'); ?></dt>
        <dd>            
                <?php echo h($respInscriptos); ?>
                &nbsp;
        </dd>
        <dt><?php echo __('Maximas Ventas por contribuyente'); ?></dt>
           
            <?php
            $topVentas=0;
            $topVentas2=0;
            $topVentas3=0;
            $topVentas4=0;
            $topVentas5=0;
            $nombreVentas=0;
            $nombreVentas2=0;
            $nombreVentas3=0;
            $nombreVentas4=0;
            $nombreVentas5=0;
            $arrayMaximos = [];
            
            usort($ventas, function ($a, $b) { return $b[0]['total'] - $a[0]['total']; });
            $top5 = array_slice($ventas, 0, 5);
                      
            $topVentas = isset($ventas[0])?$ventas[0][0]['total']:"";
            $nombreVentas = isset($ventas[0])?$nombreClientes[$ventas[0]['Venta']['cliente_id']]:"";
            $topVentas2 = isset($ventas[1])?$ventas[1][0]['total']:"";
            $nombreVentas2 = isset($ventas[1])?$nombreClientes[$ventas[1]['Venta']['cliente_id']]:"";
            $topVentas3 = isset($ventas[2])?$ventas[2][0]['total']:"";
            $nombreVentas3 = isset($ventas[2])?$nombreClientes[$ventas[2]['Venta']['cliente_id']]:"";
            $topVentas4 = isset($ventas[3])?$ventas[3][0]['total']:"";
            $nombreVentas4 = isset($ventas[3])?$nombreClientes[$ventas[3]['Venta']['cliente_id']]:"";
            $topVentas5 = isset($ventas[4])?$ventas[4][0]['total']:"";
            $nombreVentas5 = isset($ventas[4])?$nombreClientes[$ventas[4]['Venta']['cliente_id']]:"";
            
            ?>
        <dd>         
                <?php echo h($nombreVentas.": ".$topVentas); ?>
                &nbsp;
        </dd>
        <dt>2&ordm;</dt>
        <dd>         
                <?php echo h($nombreVentas2.": ".$topVentas2); ?>
                &nbsp;
        </dd>
        <dt>3&ordm;</dt>
        <dd>         
                <?php echo h($nombreVentas3.": ".$topVentas3); ?>
                &nbsp;
        </dd>
        <dt>4&ordm;</dt>
        <dd>         
                <?php echo h($nombreVentas4.": ".$topVentas4); ?>
                &nbsp;
        </dd>
        <dt>5&ordm;</dt>
        <dd>         
                <?php echo h($nombreVentas5.": ".$topVentas5); ?>
                &nbsp;
        </dd>
        <dt><?php echo __('Maximas Compras por contribuyente'); ?></dt>
        <dd>            
            <?php
            $topCompras=0;
            $nombrecompra="";
            $topCompras2=0;
            $nombrecompra2="";
            $topCompras3=0;
            $nombrecompra3="";
            $topCompras4=0;
            $nombrecompra4="";
            $topCompras5=0;
            $nombrecompra5="";
            
            usort($compras, function ($a, $b) { return $b[0]['total'] - $a[0]['total']; });
            $topCompras = isset($compras[0])?$compras[0][0]['total']:"";
            $nombrecompra = isset($compras[0])?$nombreClientes[$compras[0]['Compra']['cliente_id']]:"";
            $topCompras2 = isset($compras[1])?$compras[1][0]['total']:"";
            $nombrecompra2 = isset($compras[1])?$nombreClientes[$compras[1]['Compra']['cliente_id']]:"";
            $topCompras3 = isset($compras[2])?$compras[2][0]['total']:"";
            $nombrecompra3 = isset($compras[2])?$nombreClientes[$compras[2]['Compra']['cliente_id']]:"";
            $topCompras4 = isset($compras[3])?$compras[3][0]['total']:"";
            $nombrecompra4 = isset($compras[3])?$nombreClientes[$compras[3]['Compra']['cliente_id']]:"";
            $topCompras5 = isset($compras[4])?$compras[4][0]['total']:"";
            $nombrecompra5 = isset($compras[4])?$nombreClientes[$compras[4]['Compra']['cliente_id']]:"";
            ?>
                <?php echo h($nombrecompra.": ".$topCompras); ?>
                &nbsp;
        </dd>
        <dt>2&ordm;</dt>
        <dd>         
                <?php echo h($nombrecompra2.": ".$topCompras2); ?>
                &nbsp;
        </dd>
        <dt>3&ordm;</dt>
        <dd>         
                <?php echo h($nombrecompra3.": ".$topCompras3); ?>
                &nbsp;
        </dd>
        <dt>4&ordm;</dt>
        <dd>         
                <?php echo h($nombrecompra4.": ".$topCompras4); ?>
                &nbsp;
        </dd>
        <dt>5&ordm;</dt>
        <dd>         
                <?php echo h($nombrecompra5.": ".$topCompras5); ?>
                &nbsp;
        </dd>
        
        <dt><?php echo __('Max Empleados x contribuyente'); ?></dt>
        <dd>                      
                <?php echo h($nombreEmpleados.': '.$topEmpleados); ?>
                &nbsp;
        </dd>
        <dt><?php echo __('Convenios Utilizados'); ?></dt>
        <dd>                      
                <?php echo h(count($convenios)); ?>
                &nbsp;
        </dd>
    </dl>
</div>
<!-- Popin Modal para edicion de ventas a utilizar por datatables-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
	<div class="modal-dialog" style="width:90%;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<!--					<span aria-hidden="true">&times;</span>-->
				</button>
				<h4 class="modal-title">Editar Datos Estudio</h4>
			</div>
			<div class="modal-body">
                    <?php echo $this->Form->create('Estudio',['class'=>'formTareaCarga']); ?>
                    <?php echo $this->Form->input('id',['type'=>'hidden']); ?>
                    <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td ><?php echo $this->Form->input('nombre',['style'=>'width:250px']); ?></td>
                                <td colspan="2"><?php echo $this->Form->input('propietario',['style'=>'width:250px']);?></td>   
                            </tr>
                            <tr>
                                <td><?php echo $this->Form->input('direccion',['style'=>'width:350px']);?></td>
                                <td  colspan="2"><?php echo $this->Form->input('cuit',['style'=>'width:100px']);?></td>   
                            </tr>
                            <tr>
                                <td><?php echo $this->Form->input('ingresosbrutos',['style'=>'width:100px','label'=>'Ingresos brutos']);?></td>
                                <td colspan="2"><?php   echo $this->Form->input('inicioactividades', array(
                                        'class'=>'datepicker',
                                        'type'=>'text',
                                        'label'=>'Inicio de Actividades',
                                        'required'=>true,
                                        'default'=>date('d-m-Y',strtotime($this->request->data['Estudio']['inicioactividades'])),
                                        'readonly'=>'readonly')
                                    );?>
                                </td>   
                            </tr>
                            <tr>
                                <td></td>
                                <td><a href="#close"  onclick="" class="btn_cancelar" style="margin-top:14px">Cancelar</a></td>
                                <td><?php echo $this->Form->end(__('ACEPTAR')); ?></td>

                            </tr>
                    </table>
					
            
                   
					
            
			<div class="modal-footer">
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

