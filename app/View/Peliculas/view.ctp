<?php

?>
<?php
$url = "https://image.tmdb.org/t/p/w185_and_h278_bestv2";
$url2 = "https://image.tmdb.org/t/p/w370_and_h556_bestv2";
$imagen = $pelicula['Pelicula']['poster'];
//si tiene otra imagen cargada, debemos mostrar la otra imagen no el poster



$myPeliculas = $this->webroot.'img/peliculas/'.$pelicula['Pelicula']['id'].'/';
$files = glob($folderPeliculas."/*.*");
for ($i = 0; $i < count($files); $i++) {
    $image = $files[$i];
    //echo basename($image) . "<br />"; // show only image name if you want to show full path then use this code // echo $image."<br />";
    //echo '<img src="' .$myPeliculas.basename($image). '" alt="Random image" style="width:185px;height:278px"/>' . "<br /><br />";
    $url = $myPeliculas;
    $url2 = $myPeliculas;
	$imagen = basename($image);
}		
$imgToShow = '<div class="image_content poster" style="height: 100%;width: 186px;min-width: 80px;margin: 16px;">
			<img class="lazyautosizes lazyloaded" data-sizes="auto" 
			data-src="'.$url.$imagen.'" 
			data-srcset="'.$url.$imagen.' 1x, '.$url2.$imagen.' 2x" 
			alt="Matrix" sizes="185px" 
			srcset="'.$url.$imagen.' 1x, '.$url2.$imagen.' 2x" 
			src="'.$url.$imagen.'"
			style="width: 186px;"
			>	
		</div>';	
?>

<div class="peliculas indexmovies">
	<div id="busqueda" style="width: 450px;float: right;">
    	<?php 
        echo $this->Form->create('Pelicula',array('action'=>'lista'));
        echo $this->Form->input('filter',[
            'placeholder'=>"Buscar..",
            'type'=>"text",
            'label'=>false,
            'class'=>"searchbar",
        ]);
        echo $this->Form->end();
    	?>
	</div>
	<div class="" style="width: auto;height: auto; margin-bottom: 10px;">
		<?php echo $imgToShow; ?>
			<div class="info"  >
		  	<div class="wrapper"  style="">	       
		    	<div class="flex" style="float: left;">
		    	<h2 style="display: inline;">
		      		<?php echo $this->Html->link($pelicula['Pelicula']['titulo'], array('controller' => 'peliculas', 'action' => 'view', $pelicula['Pelicula']['id']),
		  			['style'=>'margin-left: 10px;']); ?>
		  		</h2>
		  		<h3 style="display: inline;">
			    <?php echo $this->Html->link($pelicula['Formato']['nombre'], array('controller' => 'formatos', 'action' => 'view', $pelicula['Formato']['id'])); ?>
				</h3>
				<?php
				if ($this->Session->read('Auth.User.tipo') === 'administrador') { 
				    echo $this->Html->link(__('Edit'), array('action' => 'edit', $pelicula['Pelicula']['id']))." "; 
					echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $pelicula['Pelicula']['id']), array(), __('Are you sure you want to delete # %s?', $pelicula['Pelicula']['id']));
				}
				?>
				<span>(<?php echo $pelicula['Pelicula']['release'] ?>)</span>			    
		    	</div>
		    	<div style="margin-left: 10px;float: left; width: 80%;">
			  	 	<?php 
			      	echo $this->Cart->link(__('Agregar al carrito'), array(
						'item' => $pelicula['Pelicula']['id']
						),[]
					  );
					?>
			  	</div>
		  	</div>
		  	<div style="margin-left: 10px;float: left; width: 80%;">
			  	<h3>General</h3>
			  	<p class="overview" style="line-height: 20px;text-align: justify;"><?php echo $pelicula['Pelicula']['overview'] ?></p>
		  	</div>
		    <div class"videoContainer" style="margin-left: 10px;float: left;">
		  		<?php
					$url = $pelicula['Pelicula']['video'];
					parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
		  		?>
		  		<iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $my_array_of_vars['v'] ?>" allowfullscreen=""></iframe>
			</div>
		  	
		</div>	  
	</div>

</div>

