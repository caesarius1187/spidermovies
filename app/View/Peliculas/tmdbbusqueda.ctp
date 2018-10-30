<?php
echo $this->Html->css('bootstrapmodal');
echo $this->Html->script('jquery-ui',array('inline'=>false));
echo $this->Html->script('bootstrapmodal.js',array('inline'=>false));
echo $this->Html->script('jquery.dataTables.js',array('inline'=>false));
echo $this->Html->script('dataTables.altEditor.js',array('inline'=>false));
echo $this->Html->script('dataTables.buttons.min.js',array('inline'=>false));
echo $this->Html->script('peliculas/add',array('inline'=>false));


/*Esta pantalla esta diseñada para buscar una pelicula y agregarla*/

//echo $string;


?>
<div class="peliculas index">
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
	?>
	<h2><?php 
	$busqueda =  str_replace("+", " ", $query);
	echo __('Resultado:'.$busqueda); ?></h2>
<?php
if($query!=null){
	$busquedaconID = "https://api.themoviedb.org/3/search/movie?api_key=".$api_key."&language=es&query=".$query;
	$string = file_get_contents($busquedaconID);

	//echo $string;

	$searchresults = json_decode($string);
	foreach ($searchresults as $key => $results) {
		if($key=='results'){
			foreach ($results as $key => $movie) {
				echo showMovieResult($this,$movie);
			}
		}
	}
}
?>

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
		      <div style="margin-left: 10px;float: left; width: 80%;">
		      	 <?php echo $context->Html->link("Importar", array('controller' => 'peliculas', 'action' => 'add', $id),
		      			['style'=>'margin-left: 10px;']); ?>
		      </div>
		   	</div>	  
	  	</div>
	<?php
}
?>

