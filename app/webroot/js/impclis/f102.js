function imprimir(){
    $('#header').hide();
    $('#tbl_principal').css('width','100%');
    $('#btn_imprimir').hide();
    
    /*$('#Formhead').hide();  
    $('#index').css('float','left');
    $('#padding').css('padding','0px');
    $('#index').css('font-size','10px');
    $('#index').css('border-color','#FFF');
    $('#situacionIntegral').css('padding','0px');*/
    window.print();
    /*$('#index').css('font-size','14px');*/
    $('#header').show();
    $('#tbl_principal').css('width','70%');
    $('#btn_imprimir').show();
    /*$('#Formhead').show();  
    $('#index').css('float','right');
    $('#padding').css('padding','10px 1%');
    $('#situacionIntegral').css('padding','0px 10%');*/
}