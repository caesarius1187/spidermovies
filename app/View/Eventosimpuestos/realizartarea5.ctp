<?php

if($error==0){
	if(isset($numero)){
			echo $error.'&&'.$respuesta.'&&'.$evento_id.'&&$'.number_format($numero, 2, ",", ".");
		}
		else{
			echo $error.'&&'.$respuesta.'&&'.$evento_id;
	}
}else{
	echo $error.'&&'.$respuesta;
}

?>