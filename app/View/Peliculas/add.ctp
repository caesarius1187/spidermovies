<?php
echo $this->Html->css('bootstrapmodal');
echo $this->Html->script('jquery-ui',array('inline'=>false));
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
echo $this->Html->script('jquery.dataTables.js',array('inline'=>false));
echo $this->Html->script('dataTables.altEditor.js',array('inline'=>false));
echo $this->Html->script('dataTables.buttons.min.js',array('inline'=>false));
echo $this->Html->script('peliculas/add',array('inline'=>false));

$imagen = "";
$titulo = "";
$original_title = "";
$id = "";
$releasedate = date('d-m-Y');
$overview = "";
$originalLanguage = "";

if(isset($busquedaconID)){
	$result = file_get_contents($busquedaconID);
	$moviefinded = json_decode($result);
	showMovieResult($this,$moviefinded);
	$imagen = $moviefinded->poster_path;
	$titulo = $moviefinded->title;
	$original_title = $moviefinded->original_title;
	$id = $moviefinded->id;
	$releasedate = $moviefinded->release_date;
	$overview = $moviefinded->overview;
	$originalLanguage = $moviefinded->original_language;
}


?>
<div class="peliculas form">

<?php 
 echo $this->Form->create('Pelicula',array('action'=>'tmdbbusqueda'));
                                echo $this->Form->input('filter',[
                                    'placeholder'=>"Buscar en TMDB..",
                                    'title'=>"Buscar en The Movie Data Base",
                                    'type'=>"text",
                                    'label'=>false,
                                    'class'=>"searchbar",
                                ]);
                                echo $this->Form->end();


echo $this->Form->create('Pelicula',array('class'=>'formTareaCarga','enctype' => 'multipart/form-data')); ?>
	<fieldset>
		<legend><?php echo __('Agregar Pelicula'); ?></legend>
	<?php
		echo $this->Form->input('formato_id');
		echo $this->Form->input('titulooriginal',['label'=>'Titulo original','value'=>$original_title]);
		echo $this->Form->input('titulo',['value'=>$titulo]);
		echo "</br>";
		echo $this->Form->input('price',['label'=>'Precio','value'=>0]);
		echo $this->Form->input('release',['type'=>'text','label'=>'Release','class'=>'datepicker-year','value'=>$releasedate]);
		echo "</br>";
		echo $this->Form->input('overview',['label'=>'Descripcion','style'=>'width:450px','value'=>$overview]);
		echo "</br>";		
		echo $this->Form->input('video')."</br>";
		echo $this->Form->input('poster',['value'=>$imagen])."</br>";
		echo $this->Form->file('imagenpersonalizada',['label'=>'Seleccionar Imagen Nueva','style'=>'width:285px'])."</br>";
		echo $this->Form->input('actores');
		echo "</br>";
		echo $this->Form->input('idtmdb',['value'=>$id]);
		echo $this->Form->input('idioma',['value'=>$originalLanguage]);
		//HABTM con generos
		echo $this->Form->input('Genero.Genero',[
			'style'=>'width: 150px;height: auto;',
			'required'=>true
		]);
	?>
	</fieldset>
<?php echo $this->Form->end(__('Agregar')); ?>
</div>
<?php
function showMovieResult($context,$movie){
	$imagen = $movie->poster_path;
	$titulo = $movie->title;
	$id = $movie->id;
	$releasedate = $movie->release_date;
	$overview = $movie->overview;
	?>
	<div class="movieitem poster moviecard" style="">
		    <div class="image_content poster" style="">
		    	<img class="fade lazyautosizes lazyloaded" data-sizes="auto" 
		    	data-src="https://image.tmdb.org/t/p/w185_and_h278_bestv2<?php echo $imagen;?>" 
		    	data-srcset="https://image.tmdb.org/t/p/w185_and_h278_bestv2<?php echo $imagen;?> 1x, https://image.tmdb.org/t/p/w370_and_h556_bestv2<?php echo $imagen;?> 2x" 
		    	alt="Matrix" sizes="185px" 
		    	srcset="https://image.tmdb.org/t/p/w185_and_h278_bestv2<?php echo $imagen;?> 1x, https://image.tmdb.org/t/p/w370_and_h556_bestv2<?php echo $imagen;?> 2x" 
		    	src="https://image.tmdb.org/t/p/w185_and_h278_bestv2<?php echo $imagen;?>"
		    	>	
		    </div>
		    <div class="info">
		      <div class="wrapper"  style="">	       
		        <div class="flex" style="float: left;">
		        	<h2 style="display: inline;">
		          		<?php echo $context->Html->link($titulo, array('controller' => 'peliculas', 'action' => 'add', $id),
		      			['style'=>'margin-left: 10px;']); ?>
		      		</h2>
					<span>(<?php echo $releasedate ?>)</span>
		        </div>
		      </div>
		      <div style="margin-left: 10px;float: left; width: 80%;">
		      	<h3>General</h3>
		      	<p class="overview" style="line-height: 20px;text-align: justify;"><?php echo $overview ?></p>
		      </div>
		   	</div>	  
	  	</div>
	<?php
}?>

