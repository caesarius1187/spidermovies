<?php echo $this->Html->script('jquery-ui',array('inline'=>false));?>
<?php echo $this->Html->script('Impclis/f801e',array('inline'=>false));?>


<table class="tbl_principal" id="tbl_principal" cellpadding="0" cellspacing="0"> <!--Fila 1 Encabezado-->
	<tr> <!--Fila 1-->
		<td> 
			<table class="tbl_1" cellpadding="0" cellspacing="0">
				<tr>
					<td style="border-top-left-radius: 15px; border-top-right-radius: 15px;">

						<?php echo $this->Html->image('f801_logo.jpg',array(
										'alt' 	 => '',
										'height' => '20',
										'width'	 =>	'90'));
						?>
					</td>
				</tr>
				<tr>
					<td style="text-align:center;">
						<label class="lbl_1">
							<p>VOLANTE PARA</p> 
							<p>PAGO DE OTROS</p>
							<p>CONCEPTOS</p>
						</label>
					</td>
				</tr>
				<tr>
					<td style="text-align:center; border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
						<label class="lbl_1">
							<p>S.U.S.S.</p>
							<p>SISTEMA NICO DE LA</p> 
							<P>SEGURIDAD SOCIAL</p>
						</lable>
					</td>

				</tr>
			</table>
		</td>
		<td>
			<table class="tbl_1">
				<tr>
					<td style="text-align:center; border-radius: 6px">
						<label class="lbl_1">
							<p>EL PRESENTE</p> 
							<p>INSTRUMENTO NO SERA</p>
							<p>CONSIDERADO CONSTAN-</p>
							<P>CIA DE PAGO SIENDO EL</p> 
							<p>TIQUE EL UNICO ELE-</p>
							<p>MENTO VALIDO</p>
						</lable>
					</td>
				</tr>
			</table>		
		</td>

		<td>
			<table cellpadding="0" cellspacing="0" class="tbl_1">
				<tr>
					<td style="border-top-left-radius: 6px;">F.801/E</td>
					<td style="border-top-right-radius: 6px;">CUIT</td>
				</tr>
				<tr>
					<td colspan = "2">Apellido y Nombres o Denominacion </td>
				</tr>
				<tr>
					<td colspan = "2" style="border-bottom-left-radius: 6px; border-bottom-right-radius: 6px;">Domicilio</td>
				</tr>
					
			</table>
		</td>
	</tr>

	<tr> <!--Fila 2-->
		<td colspan="3">
			<table class="tbl_1" cellpadding="0" cellspacing="0"> <!--Fila 2 Tipo de contribuyente-->
					<tr>
						<td colspan = "5" style="text-align:center; border-top-left-radius: 6px; border-top-right-radius: 6px;"><label class="lbl_tit_f801">TIPO DE CONTRIBUYENTE</label></td>
					</tr>

					<tr>
						<td style="border-bottom-left-radius: 6px; " >
							
							<?php echo $this->Form->checkbox('EMPLEADOR'); ?>
							EMPLEADOR
						</td>
						<td>
							<?php echo $this->Form->checkbox('AUTONOMO'); ?>
							AUTONOMO
						</td>
						<td>
							<?php echo $this->Form->checkbox('AUTONOMO JUBILADO'); ?>
							AUTONOMO JUBILADO
						</td>
						<td>
							<?php echo $this->Form->checkbox('EMPLEADOR'); ?>
							EMPLEADOR SERVICIO DOMESTICO
						</td>
						<td style="border-bottom-right-radius: 6px; ">
							<?php echo $this->Form->checkbox('EMPLEADOR'); ?>
							CUIL. Empleado Domestico:
						</td>
					</tr>
			</table>
		</td>
	</tr>

	<tr> <!--Fila 3 Rubro I Imputacion del pago  -->
		<td colspan="3">
			<table class="tbl_2" cellpadding="0" cellspacing="0"> 
				<tr>
					<td colspan="3" style="border-top-left-radius:6px;border-top-right-radius:6px">
						<label class="lbl_tit_f801">RUBRO I - IMPUTACION DEL PAGO (Impuesto)</label>
					</td>
				</tr>
				<tr>
					<td>AUTONOMOS (en actividad o Jubilados)</td>
					<td colspan = "2">EMPLEADORES (Incluido PyMES y Servicio Domestico)</td>
				</tr>
				<tr>		
					<td>
						<?php echo $this->Form->checkbox ('APORTES'); ?>
						308 - APORTES
					</td>
					<td>
						<?php echo $this->Form->checkbox ('CONTRIBUCION'); ?>
						351 - CONTRIBUCION SEGURIDAD SOCIAL</td>
					<td>
						<?php echo $this->Form->checkbox ('APORTE os'); ?>
						302 - APORTE OBRA SOCIAL
					</td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
					<td>
						<?php echo $this->Form->checkbox ('APORTE SS'); ?>
						301 - APORTE SEGURIDAD SOCIAL
					</td>
					<td>
						<?php echo $this->Form->checkbox ('VALES'); ?>
						270 - VALES ALIMENTARIOS
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<?php echo $this->Form->checkbox ('LEY'); ?>
						312 - LEY DE RIESGO DEL TRABAJO
					</td>
					<td>
						<?php echo $this->Form->checkbox ('LEY'); ?>
						360 - CONTRIBUCION RENATRE
					</td>
				</tr>
				<tr>
					<td style="border-bottom-left-radius:6px;">
						<?php echo $this->Form->checkbox ('LEY'); ?>				
						358 - CONTRIBUCIONES
					</td>
					<td>
						<?php echo $this->Form->checkbox ('LEY'); ?>
						352 - CONTRIBUCION OBRA SOCIAL
					</td>
					<td style="border-bottom-right-radius:6px;">..............................</td>

				</tr>
			</table>
		</td>
	</tr>
	<tr> <!--Fila 4 Rubro II-->
		<td colspan="3">
			<table class="tbl_1" cellpadding="0" cellspacing="0"> 
				<tr>
					<td colspan="6" style="border-top-left-radius:6px;border-top-right-radius:6px">
						<label class="lbl_tit_f801">RUBRO II - CONCEPTO QUE SERA APLICADO AL PAGO</label>
					</td>

				</tr>
				<tr>
					<td>
						<?php echo $this->Form->checkbox ('LEY'); ?>
						019 - OBLIG. MENSUAL
					</td>
					<td>
						<?php echo $this->Form->checkbox ('LEY'); ?>
						818 - DTO. 314/95 F. 818
					</td>
					<td>
						<?php echo $this->Form->checkbox ('LEY'); ?>
						819 - DTO. 314/95 F. 819
					</td>
					<td>
						<?php echo $this->Form->checkbox ('LEY'); ?>	
						820 - DTO. 314/95 F. 820
					</td>
					<td>
						<?php echo $this->Form->checkbox ('LEY'); ?>
						821 - DTO. 314/95 F. 821
					</td>
					<td>
						<?php echo $this->Form->checkbox ('LEY'); ?>
						823 - DTO. 314/95 F. 823
					</td>
					
				</tr>
				<tr>
					<td style="border-bottom-left-radius:6px;">
						<?php echo $this->Form->checkbox ('LEY'); ?>
						824 - DTO. 314/95 F. 824
					</td>
					<td>
						<?php echo $this->Form->checkbox ('LEY'); ?>
						825 - DTO. 314/95 F. 825
					</td>
					<td>
						<?php echo $this->Form->checkbox ('LEY'); ?>
						826 - DTO. 314/95 F. 826
					</td>
					<td>
						<?php echo $this->Form->checkbox ('LEY'); ?>
						828 - DTO. 314/95 F. 828
					</td>
					<td colspan="2" style="border-bottom-right-radius:6px;">
					........................</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table class="tbl_1" cellpadding="0" cellspacing="0"> <!--Fila 5 Rubro III-->
				<tr>
					<td colspan = "3" style="border-top-left-radius:6px;border-top-right-radius:6px">
						<label class="lbl_tit_f801">RUBRO III - SUBCONCEPTO A PAGAR</label>
					</td>
				</tr>

				<tr>
					<td>
						<?php echo $this->Form->checkbox ('LEY'); ?>
						051 - INTERESES RESARCITORIOS
					</td>
					<td>
						<?php echo $this->Form->checkbox ('LEY'); ?>
						094 - INTERESES PUNITORIOS
					</td>
					<td>
						<?php echo $this->Form->checkbox ('LEY'); ?>
						167 - MULTA R.G. 3756
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<?php echo $this->Form->checkbox ('LEY'); ?>
						108 - MULTA
					</td>
					<td>
						<?php echo $this->Form->checkbox ('LEY'); ?>
						204 - MULTA LRT
					</td>
				</tr>
				<tr>
					<td style="border-bottom-left-radius:6px;">
						<?php echo $this->Form->checkbox ('LEY'); ?>
						086 - BOLETA DE DEUDA</td>
					<td>
						<?php echo $this->Form->checkbox ('LEY'); ?>
						140 - MULTA AUTOMATIVA
					</td>
					<td style="border-bottom-right-radius:6px">....................</td>

				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table class="tbl_1" cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="6" >PERIODO</td>
				</tr>
				<tr>
					<td colspan="2">Mes</td>
					<td colspan="4">Año</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			
			</table>
		</td>
		<td>
			<table class="tbl_1" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="2">IMPORTE TOTAL DEPOSITADO</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
					</tr>
			</table>
		</td>
		<td>
			<table class="tbl_1">
					<tr>
						<td></td>
					</tr>
			</table>
		</td>
	
	</tr>
	<tr>
		<td>
			<?php echo $this->Form->button('Imprimir', 
                                    array('type' 	=> 'button',
                                          'class'	=> 'btn_imprimir',
                                          'onClick' => 'imprimir()',
                                          'id'		=> 'btn_imprimir'
                                            )
                    );?> 
		</td>
	</tr>
</table>