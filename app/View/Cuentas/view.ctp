
<?php
echo $this->Html->script('mark.min.js',array('inline'=>false));
?>
<style>
	mark{
		background: yellow;
		color: black;
	}
    mark.current {
        background: orange;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        // jQuery object to save <mark> elements
        var results,
        // the class that will be appended to the current
        // focused element
        currentClass = "current",
        // top offset for the jump (the search bar)
//		offsetTop = 50,
        // the current index of the focused element
        currentIndex = 0;

        var nextBtn=$("#nextButton");
        var prevBtn=$("#prevButton");

        var iTamPantalla = $(window).height();
        var iTamTabla = iTamPantalla - 170;
        iTamTabla = (iTamTabla < 100) ? 100 : iTamTabla;
        $("#divPlanCuentasStandard").attr("style", "max-height:" + iTamTabla + "px; width:96%; overflow:auto");
        var context = document.querySelector("#divPlanCuentasStandard");
        var instance = new Mark(context);
        $('#txtBuscarCuenta').change(function () {
//			instance.mark(searchVal);
            var searchVal = $(this).val();
            instance.unmark({
                done: function () {
                    instance.mark(searchVal, {
                        separateWordSearch: true,
                        done: function () {
                            results = $("#divPlanCuentasStandard").find("mark");
                            currentIndex = 0;
                            jumpTo();
                        }
                    });
                }
            });
        });
        $('#cbxOcultarcuentas').change(function () {
            if($(this).prop('checked') == true){
                $(".cuenta").hide();
            }else{
                $(".cuenta").show();
            }
        });

        /**
         * Jumps to the element matching the currentIndex
         */
        function jumpTo() {
                if (results.length) {
                    var position,
                    $current = results.eq(currentIndex);
                    results.removeClass(currentClass);
                    if ($current.length) {
                        $current.addClass(currentClass);
                        position = $current.offset().top - $("#divPlanCuentasStandard").offset().top + $("#divPlanCuentasStandard").scrollTop();
                        $("#divPlanCuentasStandard").animate({
                            scrollTop: position
                        }, 200 ,'swing');
        //					window.scrollTo(0, position);
                    }
                }
        }
        /**
         * Next and previous search jump to
         */
        nextBtn.add(prevBtn).on("click", function() {
            if (results.length) {
                currentIndex += $(this).is(prevBtn) ? -1 : 1;
                if (currentIndex < 0) {
                    currentIndex = results.length - 1;
                }
                if (currentIndex > results.length - 1) {
                    currentIndex = 0;
                }
                jumpTo();
            }
        });
    });
    function FnActivarCuenta(oObj)
    {
        //alert(oObj.checked)	;
        var sChecked = (oObj.checked) ? "1" : "0";
        var sClienteId = $("#hdnClienteId").val();
        var sCuentaId = (oObj.id).split("_")[1];
        var sCuentaclienteId = (oObj.id).split("_")[2];
        $.ajax({
            type: "post",  // Request method: post, get
            url: serverLayoutURL+"/cuentas/activar/"+sClienteId+"/"+sCuentaId+"/"+sChecked+"/"+sCuentaclienteId, // URL to request
            data: "",  // post data
            success: function(response) {
                var midata = jQuery.parseJSON(response);
                //callAlertPopint(midata.respuesta);
                //$('#rowImpcli'+impcliid).hide();
                
                if(midata.hasOwnProperty('error')){
                    //volvemos al estado anterior
                    if(oObj.checked){
                        $(oObj).prop('checked',false);
                    }else{
                        $(oObj).prop('checked',true);
                    }
                    callAlertPopint(midata.respuesta);
                }else{
                    callAlertPopint(midata.respuesta);
                    $(oObj).closest('tr').on('click',function(){
                         callAlertPopint("Para cambiarle el nombre a esta cuenta por favor reinicie la pagina");
                    });
                }
            },
            error:function (XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus);
                alert(XMLHttpRequest);
                alert(errorThrown);
            }
        });
    }
    function showhide(numero){
        $("."+numero).each(function(){
            if($(this).is(":visible")){
                $(this).hide();
            }else{
                $(this).show();
            }
        });
    }
    function EditarDescripcion(CuentaCliId)
	{
            var sDescripcion = $("#lblDescripcionCuenta_" + CuentaCliId).text();
            sDescripcion = (sDescripcion != "") ? sDescripcion.trim(): "";
            $("#lblDescripcionCuenta_" + CuentaCliId).attr("style","display:none");
            $("#txtDescripcionCuenta_" + CuentaCliId).val(sDescripcion);
            $("#txtDescripcionCuenta_" + CuentaCliId).attr("style","display:block; width:80%");		

            $("#lnkEditarDesc_" + CuentaCliId).css("display","none");
            $("#lnkGuardarDesc_" + CuentaCliId).css("display","block");
            $("#lnkCancelarDesc_" + CuentaCliId).css("display","block");		
	}
	function CancelarDescripcion(CuentaCliId)
	{
            $("#lblDescripcionCuenta_" + CuentaCliId).attr("style","display:block");
            $("#txtDescripcionCuenta_" + CuentaCliId).attr("style","display:none");

             $("#lnkEditarDesc_" + CuentaCliId).css("display","block");
            $("#lnkGuardarDesc_" + CuentaCliId).css("display","none");
            $("#lnkCancelarDesc_" + CuentaCliId).css("display","none");	
	}
	function GuardarDescripcion(CuentaCliId)
	{
            var sDescripcion = $("#txtDescripcionCuenta_" + CuentaCliId).val();
            sDescripcion = (sDescripcion != "") ? sDescripcion.trim(): "";		

            $.ajax({
                type: "post",  // Request method: post, get
                url: serverLayoutURL+"/Cuentasclientes/GuardarDescripcion/"+CuentaCliId+"/"+sDescripcion, 
                data: { descripcion : sDescripcion },
                success: function(response) {
                        var midata = jQuery.parseJSON(response);					
                        callAlertPopint(midata.respuesta);
                        $("#lblDescripcionCuenta_" + CuentaCliId).text(sDescripcion);
                        CancelarDescripcion(CuentaCliId);
                },
                error:function (XMLHttpRequest, textStatus, errorThrown) {
                        alert(textStatus);
                        alert(XMLHttpRequest);
                        alert(errorThrown);
                }
            });
	}
</script>

<div class="index">
	<div style="width:40%;float:left">
		<?php
			echo $this->Html->link(
	                                'Volver', 
	                                array(
	                                    'controller' => 'Cuentasclientes',
	                                    'action' => 'informesumaysaldo', $clienteId , date('m-Y')
	                                ),
	                                array(
	                                	'style' => 'float:left; margin-left:10px;margin-top:15px'
	                                )                               
	    				        )
			;  
	    ?>
	    <input type="hidden" id="hdnClienteId" value="<?php echo $clienteId; ?>"/>
	</div>
	<div style="width:60%;float:right">
		<input placeholder="Buscar Nro. Cuenta" id="txtBuscarCuenta" type="text" style="float:right; width:80%; margin:5px"/>
		<button data-search="next" style="float:right;" id="nextButton">&darr;</button>
		<button data-search="prev"style="float:right;" id="prevButton">&uarr;</button>
	</div>
</div>
<div id="divPlanCuentasStandard" class="index">
	<?php
	echo $this->Form->input('ocultarcuentas',[
		'id'=>'cbxOcultarcuentas',
		'label'=>'Ocultar cuentas',
		'type'=>'checkbox'
	]);
	?>

	<table id="tblPlanDeCuentas">
		<thead>
			<tr>
				<td style="text-align:left">Numero</td>
				<td style="text-align:left"> </td>
				<td style="text-align:left"> </td>
				<td style="text-align:left"> </td>
				<td style="text-align:left"> </td>
				<td style="text-align:left"> </td>
				<td style="text-align:left"> </td>
				<td style="width:60%;text-align:left">Descripcion</td>
				<td style="width:10%;text-align:left">
					<!--<input id="chkSelTodos" type="checkbox" value=""/>-->
					Activar
				</td>
			</tr>
		</thead>
		<tbody>
            <?php
            $trStyle="background-color:#2a36b1;color:white;";
            $lastRubro = "110000000";
            foreach ($cuentas as $cuenta)
            {
                switch ($cuenta['Cuenta']['level']){
                    case 1:
                        $trStyle="background-color:#2a36b1;color:white;";
                        break;
                    case 2:
                        $trStyle="background-color:#455ede;color:white;";
                        break;
                    case 3:
                        $trStyle="background-color:#5677fc;color:white;";
                        break;
                    case 4:
                        $trStyle="background-color:#91a7ff;color:black;";
                        break;
                    case 5:
                        $trStyle="background-color:#d0d9ff;color:black;";
                        break;
                    case 6:
                        $trStyle="background-color:#e7e9fd;color:black;";
                        break;
                    case 7:
                        $trStyle="background-color:#white;color:black;";
                        break;
                }

                $CuentaId = $cuenta['Cuenta']['id'];
                $CuentaClienteId = isset($cuenta['Cuentascliente']['id'])?$cuenta['Cuentascliente']['id']:0;
                if($cuenta['Cuenta']['tipo']=='rubro'){
                    $lastRubro=$cuenta['Cuenta']['numero'];
                }
                ?>
                <tr id="trCuenta<?php echo $cuenta['Cuenta']['numero'] ?>"
                    style="<?php echo $trStyle?>"
                    levelCuenta="<?php echo $cuenta['Cuenta']['level']?>"
                    <?php
                    $class =$cuenta['Cuenta']['tipo']." ";
                    if($cuenta['Cuenta']['tipo']=='cuenta'){
                        $class .= $lastRubro;
                    }
                    ?>
                    class="<?php echo $class; ?>"
                    onclick="showhide(<?php echo $cuenta['Cuenta']['numero']; ?>)">
                    <td style="">
                            <?php echo $cuenta['Cuenta']['level']==1?$cuenta['Cuenta']['numero']:""; ?>
                    </td>
                    <td style="">
                            <?php echo $cuenta['Cuenta']['level']==2?$cuenta['Cuenta']['numero']:""; ?>
                    </td>
                    <td style="">
                            <?php echo $cuenta['Cuenta']['level']==3?$cuenta['Cuenta']['numero']:""; ?>
                    </td>
                    <td style="">
                            <?php echo $cuenta['Cuenta']['level']==4?$cuenta['Cuenta']['numero']:""; ?>
                    </td>
                    <td style="">
                            <?php echo $cuenta['Cuenta']['level']==5?$cuenta['Cuenta']['numero']:""; ?>
                    </td>
                    <td style="">
                            <?php echo $cuenta['Cuenta']['level']==6?$cuenta['Cuenta']['numero']:""; ?>
                    </td>
                    <td style="">
                            <?php echo $cuenta['Cuenta']['level']==7?$cuenta['Cuenta']['numero']:""; ?>
                    </td>
                    <td style="width:60%">
                        <?php
                        $DescCuenta = h($cuenta['Cuentascliente']['nombre']==""?$cuenta['Cuenta']['nombre']:$cuenta['Cuentascliente']['nombre']);
                        $CuentaCliId = $cuenta['Cuentascliente']['id'];
                        if($cuenta['Cuenta']['tipo']=='cuenta'&&$CuentaCliId!=''){
                            //Debugger::dump($cuenta);
                            ?>
                            <label id="lblDescripcionCuenta_<?php echo $CuentaCliId;?>" onclick="EditarDescripcion('<?php echo $CuentaCliId;?>')" />
                            <?php echo $DescCuenta;?>
                            </label>				
                            <input id="txtDescripcionCuenta_<?php echo $CuentaCliId;?>" type="text" value="" style="display:none;"/>
                             <a id="lnkGuardarDesc_<?php echo $CuentaCliId;?>" style="<?php echo $trStyle?> cursor:pointer; display:none" onclick="GuardarDescripcion('<?php echo $CuentaCliId;?>')">
                                Guardar
                            </a>
                            <a id="lnkCancelarDesc_<?php echo $CuentaCliId;?>" style="<?php echo $trStyle?> cursor:pointer; display:none" onclick="CancelarDescripcion('<?php echo $CuentaCliId;?>')">Cancelar</a>
                            <?php
                        }else{
                            echo $DescCuenta;
                        }
                        ?>
                    </td>
                    <td style="width:10%">
                        <?php
                        if($cuenta['Cuenta']['tipo']=='cuenta'){
                            if ($CuentaClienteId!=0)
                                echo '<input id="chkCuenta_'.$CuentaId.'_'.$CuentaClienteId.'" checked="checked" onclick="FnActivarCuenta(this)" type="checkbox" value="'.$CuentaId.'" />';
                            else
                                echo '<input id="chkCuenta_'.$CuentaId.'_'.$CuentaClienteId.'" type="checkbox" onclick="FnActivarCuenta(this)" value="'.$CuentaId.'" />';
                        }
                        ?>
                    </td>
            </tr>
                    <?php
                    /*if($cuenta['Cuenta']['level']==5){
			die("me mori");
                    }*/
                } ?>
		</tbody>
		<tfoot>
			<tr>
				<td style="">

				</td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
			</tr>
		</tfoot>
	</table>
</div>