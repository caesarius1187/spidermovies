$(document).ready(function() {
   setPrevNextButton();
   $("#liinformes").addClass("active");
   $('.chosen-select').chosen({search_contains:true});
});
function showAvance(){
   
}
function gcliPrevio(){
	$('#clientesGclis option:selected').prev().attr('selected', 'selected');
}
function gcliSiguiente(){
	$('#clientesGclis option:selected').next().attr('selected', 'selected');
}
function setPrevNextButton(){
	 if($('#clientesGclis').get(0).selectedIndex==0){
    	$('#tdGcliPrevio').hide();
    }else{
    	$('#tdGcliPrevio').show();
    }
    if($('#clientesGclis').get(0).selectedIndex == $('#clientesGclis').children('option').length-1){
    	$('#tdGcliSiguiente').hide();
    }else{
    	$('#tdGcliSiguiente').show();
    }
}