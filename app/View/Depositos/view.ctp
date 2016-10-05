<?php echo $this->Html->script('jquery');?>
<SCRIPT TYPE="text/javascript">
$(document).ready(function() {
	$('#domicilios').change(function ()
	{
	    //           ?
	    var label=$('#domicilios :selected').parent().attr('label');
	    if(label.length != 0){
	    	$('#tdLocalidad').html("Localidad : "+label);
	    }
	});
	$('#domicilios')
    .trigger('change');
});
function imprimir(){
    $('#header').hide();
    $('#btn_ot').hide();  
    $('#content').css('padding','0px'); 
    $('#tbl_vt_enc').css('font-size','9px');

    $('#tdDomicilio').html("Domicilio: "+$('#domicilios option:selected').text());

    window.print();
   	$('#content').css('padding',' 10px 14% 40px'); 
    $('#btn_ot').show();
    $('#tbl_vt_enc').css('font-size','13px');


}
</SCRIPT>
<div class="depositos ">
	<div class="cabeceraFactura ">
		<table class="1tbl_border" style="font-size: 80%">
			<tr>
				<td style="text-align:center;" rowspan="5">
               <?php
               $file = WWW_ROOT . 'img' . DS . 'estudios' . DS . $estudio['Estudio']['id'].'.png';
               if(file_exists($file)){
                  echo $this->Html->image('estudios/'.$estudio['Estudio']['id'].'.png',array('width'=>'249px','height'=>'150px')).' <br/> ';
                  echo "De ".$estudio['Estudio']['propietario'].' <br/> ';
                  echo $estudio['Estudio']['direccion']; 
               }else{
                  echo $estudio['Estudio']['nombre'].' <br/> ';
                  echo "De ".$estudio['Estudio']['propietario'].' <br/> ';
                  echo $estudio['Estudio']['direccion'];
               }
               ?>
				</td>
				<td style="text-align:center">
					Recibo 
				</td>
				<td style="text-align:right" colspan="2">
					Numero:0001-<?php 
               $cantdigitos = strlen($deposito['Deposito']['numero']);
               if($cantdigitos<8){
                  for($i=0; $i<8-$cantdigitos; $i++){
                     echo 0;                     
                  }
               }
               echo $deposito['Deposito']['numero']; ?>
				</td>
			</tr>
			<tr>
				<td style="text-align:center; ">
               <div style="margin: 0 auto;padding-bottom: 3px;width:60px;height: 60px;border: solid 1px black;font-size: 60px;">X</div>
					
				</td>           
				<td style="text-align:right" colspan="2">
					Fecha: <?php echo date('d-m-Y',strtotime($deposito['Deposito']['fecha'])); ?>
				</td>
			</tr>
			<tr>
				<td style="text-align: center" rowspan="3">
               <hr style="width: 1px; height: 90%; display: inline-block;">
            </td>
				<td style="text-align: right" >
					C.U.I.T: 
				</td>
            <td style="text-align: left; width:120px;" >
               <?php echo $estudio['Estudio']['cuit']; ?>
            </td>
			</tr>
			<tr>
				<td style="text-align: right" >
					Ingresos Brutos: 
				</td>
            <td style="text-align: left; width:120px;" >
               <?php echo $estudio['Estudio']['ingresosbrutos']; ?>
            </td>
			</tr>
			<tr>
				<td style="text-align: right; width:120px;" >
					Inicio de Actividades: 
				</td>
            <td style="text-align: left; width:120px;" >
               <?php echo date('d-m-Y',strtotime($estudio['Estudio']['inicioactividades'])); ?>
            </td>
			</tr>
         <tr>
            <td colspan="5">
               <hr >
            </td> 
         </tr>
			<tr>
				<td colspan="4">
					Nombre | Razon Social: <?php echo $deposito['Cliente']['nombre']; ?>
				</td>
			</tr>
			<tr>
				<td id="tdDomicilio">
					Domicilio:<?php 
					if(Count($domicilios)!=0){
						 echo $this->Form->input('domicilios', array(
			                'options' => $domicilios,
			                'label'=> false,
			                'div'=>false
			            ));
						}else{
							"";
						}
					 ?>
				</td>
				<td colspan="3" id="tdLocalidad">
					Localidad:
				</td>
			</tr>
			<tr>
				<td>
					C.U.I.T: <?php echo $deposito['Cliente']['cuitcontribullente']; ?>
				</td>
				<td colspan="3">
					Factura N&#176;: <?php echo $deposito['Deposito']['factura']; ?>
				</td>
			</tr>
         <tr>
            <td colspan="4">
               <hr >
            </td> 
         </tr>
			<tr>
				<td colspan="4">
					Recib&#237;(mos) la suma de pesos: 
					<label style="display: inline-block">
						<?php echo num2letras(number_format($deposito['Deposito']['monto'], 2, ".", ""))." ($".number_format($deposito['Deposito']['monto'],2,",",".");?>)
					</label>      
				</td>
			</tr>
			<tr>
				<td colspan="4">
					Pagado en concepto de: <?php echo h($deposito['Deposito']['descripcion']); ?> &nbsp;
				</td>
				
			</tr>
         <tr>
            <td colspan="4">
               <hr >
            </td> 
         </tr>
			<tr>
				<td>
					Total: <?php echo num2letras(number_format($deposito['Deposito']['monto'], 2, ".", ""))." ($".number_format($deposito['Deposito']['monto'],2,",",".");?>)
				</td>
				<td style=" border-left: thick double #ffffff; width:25%;">               
					Firma: 		
				</td>
				<td >
					Aclaracion:			
				</td>
			</tr>
         <tr>
            <td colspan="4">
               <hr >
            </td> 
         </tr>
		</table>
		<?php echo $this->Form->button('Imprimir', 
                                    array('type' => 'button',
                                          'onClick' => "imprimir()",
                                          'id' =>'btn_ot'

                                         )
            );?> 
	</div>
</div>

<?php 
/*! 
  @function num2letras () 
  @abstract Dado un n?mero lo devuelve escrito. 
  @param $num number - N?mero a convertir. 
  @param $fem bool - Forma femenina (true) o no (false). 
  @param $dec bool - Con decimales (true) o no (false). 
  @result string - Devuelve el n?mero escrito en letra. 

*/ 
      function num2letras($num, $fem = false, $dec = true) { 
         $matuni[2]  = "dos"; 
         $matuni[3]  = "tres"; 
         $matuni[4]  = "cuatro"; 
         $matuni[5]  = "cinco"; 
         $matuni[6]  = "seis"; 
         $matuni[7]  = "siete"; 
         $matuni[8]  = "ocho"; 
         $matuni[9]  = "nueve"; 
         $matuni[10] = "diez"; 
         $matuni[11] = "once"; 
         $matuni[12] = "doce"; 
         $matuni[13] = "trece"; 
         $matuni[14] = "catorce"; 
         $matuni[15] = "quince"; 
         $matuni[16] = "dieciseis"; 
         $matuni[17] = "diecisiete"; 
         $matuni[18] = "dieciocho"; 
         $matuni[19] = "diecinueve"; 
         $matuni[20] = "veinte"; 
         $matunisub[2] = "dos"; 
         $matunisub[3] = "tres"; 
         $matunisub[4] = "cuatro"; 
         $matunisub[5] = "quin"; 
         $matunisub[6] = "seis"; 
         $matunisub[7] = "sete"; 
         $matunisub[8] = "ocho"; 
         $matunisub[9] = "nove"; 

         $matdec[2] = "veint"; 
         $matdec[3] = "treinta"; 
         $matdec[4] = "cuarenta"; 
         $matdec[5] = "cincuenta"; 
         $matdec[6] = "sesenta"; 
         $matdec[7] = "setenta"; 
         $matdec[8] = "ochenta"; 
         $matdec[9] = "noventa"; 
         $matsub[3]  = 'mill'; 
         $matsub[5]  = 'bill'; 
         $matsub[7]  = 'mill'; 
         $matsub[9]  = 'trill'; 
         $matsub[11] = 'mill'; 
         $matsub[13] = 'bill'; 
         $matsub[15] = 'mill'; 
         $matmil[4]  = 'millones'; 
         $matmil[6]  = 'billones'; 
         $matmil[7]  = 'de billones'; 
         $matmil[8]  = 'millones de billones'; 
         $matmil[10] = 'trillones'; 
         $matmil[11] = 'de trillones'; 
         $matmil[12] = 'millones de trillones'; 
         $matmil[13] = 'de trillones'; 
         $matmil[14] = 'billones de trillones'; 
         $matmil[15] = 'de billones de trillones'; 
         $matmil[16] = 'millones de billones de trillones'; 
         
         //Zi hack
         $float=explode('.',$num);
         $num=$float[0];

         $num = trim((string)@$num); 
         if ($num[0] == '-') { 
            $neg = 'menos '; 
            $num = substr($num, 1); 
         }else 
            $neg = ''; 
         while ($num[0] == '0') $num = substr($num, 1); 
         if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num; 
         $zeros = true; 
         $punt = false; 
         $ent = ''; 
         $fra = ''; 
         for ($c = 0; $c < strlen($num); $c++) { 
            $n = $num[$c]; 
            if (! (strpos(".,'''", $n) === false)) { 
               if ($punt) break; 
               else{ 
                  $punt = true; 
                  continue; 
               } 

            }elseif (! (strpos('0123456789', $n) === false)) { 
               if ($punt) { 
                  if ($n != '0') $zeros = false; 
                  $fra .= $n; 
               }else 

                  $ent .= $n; 
            }else 

               break; 

         } 
         $ent = '     ' . $ent; 
         if ($dec and $fra and ! $zeros) { 
            $fin = ' coma'; 
            for ($n = 0; $n < strlen($fra); $n++) { 
               if (($s = $fra[$n]) == '0') 
                  $fin .= ' cero'; 
               elseif ($s == '1') 
                  $fin .= $fem ? ' una' : ' un'; 
               else 
                  $fin .= ' ' . $matuni[$s]; 
            } 
         }else 
            $fin = ''; 
         if ((int)$ent === 0) return 'con Cero centavos ' . $fin; 
         $tex = ''; 
         $sub = 0; 
         $mils = 0; 
         $neutro = false; 
         while ( ($num = substr($ent, -3)) != '   ') { 
            $ent = substr($ent, 0, -3); 
            if (++$sub < 3 and $fem) { 
               $matuni[1] = 'una'; 
               $subcent = 'as'; 
            }else{ 
               $matuni[1] = $neutro ? 'un' : 'uno'; 
               $subcent = 'os'; 
            } 
            $t = ''; 
            $n2 = substr($num, 1); 
            if ($n2 == '00') { 
            }elseif ($n2 < 21) 
               $t = ' ' . $matuni[(int)$n2]; 
            elseif ($n2 < 30) { 
               $n3 = $num[2]; 
               if ($n3 != 0) $t = 'i' . $matuni[$n3]; 
               $n2 = $num[1]; 
               $t = ' ' . $matdec[$n2] . $t; 
            }else{ 
               $n3 = $num[2]; 
               if ($n3 != 0) $t = ' y ' . $matuni[$n3]; 
               $n2 = $num[1]; 
               $t = ' ' . $matdec[$n2] . $t; 
            } 
            $n = $num[0]; 
            if ($n == 1) { 
               $t = ' ciento' . $t; 
            }elseif ($n == 5){ 
               $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t; 
            }elseif ($n != 0){ 
               $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t; 
            } 
            if ($sub == 1) { 
            }elseif (! isset($matsub[$sub])) { 
               if ($num == 1) { 
                  $t = ' mil'; 
               }elseif ($num > 1){ 
                  $t .= ' mil'; 
               } 
            }elseif ($num == 1) { 
               $t .= ' ' . $matsub[$sub] . '?n'; 
            }elseif ($num > 1){ 
               $t .= ' ' . $matsub[$sub] . 'ones'; 
            }   
            if ($num == '000') $mils ++; 
            elseif ($mils != 0) { 
               if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub]; 
               $mils = 0; 
            } 
            $neutro = true; 
            $tex = $t . $tex; 
         } 
         $tex = $neg . substr($tex, 1) . $fin; 
         //Zi hack --> return ucfirst($tex);
         $end_num1=ucfirst($tex).' pesos '/*.$float[1].'/100 M.N.'*/;
         
         $num=$float[1];

         $num = trim((string)@$num); 
         if ($num[0] == '-') { 
            $neg = 'menos '; 
            $num = substr($num, 1); 
         }else 
            $neg = ''; 
         while ($num[0] == '0') $num = substr($num, 1); 
         if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num; 
         $zeros = true; 
         $punt = false; 
         $ent = ''; 
         $fra = ''; 
         for ($c = 0; $c < strlen($num); $c++) { 
            $n = $num[$c]; 
            if (! (strpos(".,'''", $n) === false)) { 
               if ($punt) break; 
               else{ 
                  $punt = true; 
                  continue; 
               } 

            }elseif (! (strpos('0123456789', $n) === false)) { 
               if ($punt) { 
                  if ($n != '0') $zeros = false; 
                  $fra .= $n; 
               }else 

                  $ent .= $n; 
            }else 

               break; 

         } 
         $ent = '     ' . $ent; 
         if ($dec and $fra and ! $zeros) { 
            $fin = ' coma'; 
            for ($n = 0; $n < strlen($fra); $n++) { 
               if (($s = $fra[$n]) == '0') 
                  $fin .= ' cero'; 
               elseif ($s == '1') 
                  $fin .= $fem ? ' una' : ' un'; 
               else 
                  $fin .= ' ' . $matuni[$s]; 
            } 
         }else 
            $fin = ''; 
         if ((int)$ent === 0) return $end_num1.'con Cero Centavos' . $fin; 
         $tex = ''; 
         $sub = 0; 
         $mils = 0; 
         $neutro = false; 
         while ( ($num = substr($ent, -3)) != '   ') { 
            $ent = substr($ent, 0, -3); 
            if (++$sub < 3 and $fem) { 
               $matuni[1] = 'una'; 
               $subcent = 'as'; 
            }else{ 
               $matuni[1] = $neutro ? 'un' : 'uno'; 
               $subcent = 'os'; 
            } 
            $t = ''; 
            $n2 = substr($num, 1); 
            if ($n2 == '00') { 
            }elseif ($n2 < 21) 
               $t = ' ' . $matuni[(int)$n2]; 
            elseif ($n2 < 30) { 
               $n3 = $num[2]; 
               if ($n3 != 0) $t = 'i' . $matuni[$n3]; 
               $n2 = $num[1]; 
               $t = ' ' . $matdec[$n2] . $t; 
            }else{ 
               $n3 = $num[2]; 
               if ($n3 != 0) $t = ' y ' . $matuni[$n3]; 
               $n2 = $num[1]; 
               $t = ' ' . $matdec[$n2] . $t; 
            } 
            $n = $num[0]; 
            if ($n == 1) { 
               $t = ' ciento' . $t; 
            }elseif ($n == 5){ 
               $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t; 
            }elseif ($n != 0){ 
               $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t; 
            } 
            if ($sub == 1) { 
            }elseif (! isset($matsub[$sub])) { 
               if ($num == 1) { 
                  $t = ' mil'; 
               }elseif ($num > 1){ 
                  $t .= ' mil'; 
               } 
            }elseif ($num == 1) { 
               $t .= ' ' . $matsub[$sub] . '?n'; 
            }elseif ($num > 1){ 
               $t .= ' ' . $matsub[$sub] . 'ones'; 
            }   
            if ($num == '000') $mils ++; 
            elseif ($mils != 0) { 
               if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub]; 
               $mils = 0; 
            } 
            $neutro = true; 
            $tex = $t . $tex; 
         } 
         $tex = $neg . substr($tex, 1) . $fin; 
         //Zi hack --> return ucfirst($tex);
         $end_num = $end_num1.' con '.ucfirst($tex).' centavos ';
         return $end_num; 
      }  


?> 