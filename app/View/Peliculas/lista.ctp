<div class="peliculas indexmovies">
	<div id="busqueda" style="width: 100%;height: 45px">
		<?php
			if(isset($migenero)){
				?>
				<div style="width: 450px;display: inline;font-size: 20px"><?php echo __($migenero['Genero']['nombre']); ?></div>
				<?php
			}else if(isset($filter)){
				?>
				<div style="width: 450px;display: inline;font-size: 20px"><?php echo __($filter); ?></div>
				<?php
			}else{
				?>
				<div style="width: 450px;display: inline;font-size: 20px"><?php echo __('Novedades!'); ?></div>
				<?php
			}
		?>
		
    	<?php 
        echo $this->Form->create('Pelicula',
    		array(
    			'action'=>'lista',
    			'style'=>'width:auto;display:inline',
        	)
    	);
        echo $this->Form->input('filter',[
            'placeholder'=>"Buscar..",
            'type'=>"text",
            'label'=>false,
            'class'=>"searchbar",
            'div'=>['style'=>'display: inline;']
        ]);
        echo $this->Form->end();
    	?>
	</div>
	<?php 
	foreach ($peliculas as $pelicula){ 
		$url = "https://image.tmdb.org/t/p/w185_and_h278_bestv2";
		$url2 = "https://image.tmdb.org/t/p/w370_and_h556_bestv2";
		$imagen = $pelicula['Pelicula']['poster'];
		//si tiene otra imagen cargada, debemos mostrar la otra imagen no el poster

		$myPeliculas = $this->webroot.'img/peliculas/'.$pelicula['Pelicula']['id'].'/';
		$files = glob($folderPeliculas.$pelicula['Pelicula']['id']."/*.*");
		for ($i = 0; $i < count($files); $i++) {
		    $image = $files[$i];
		    //echo basename($image) . "<br />"; // show only image name if you want to show full path then use this code // echo $image."<br />";
		    //echo '<img src="' .$myPeliculas.basename($image). '" alt="Random image" style="width:185px;height:278px"/>' . "<br /><br />";
		    $url = $myPeliculas;
		    $url2 = $myPeliculas;
			$imagen = basename($image);
		}		

		$imgToShow = '
					<img class="lazyautosizes lazyloaded" data-sizes="auto" 
					data-src="'.$url.$imagen.'" 
					data-srcset="'.$url.$imagen.' 1x, '.$url2.$imagen.' 2x" 
					alt="Matrix" sizes="185px" 
					srcset="'.$url.$imagen.' 1x, '.$url2.$imagen.' 2x" 
					src="'.$url.$imagen.'"
					style="width: 185px;height: 278px;">';	
		?>
		<div class="item poster card" style="width: 200px;height: auto; margin-bottom: 10px;">
			<div class="image_content poster" style="height: 100%;width: auto;min-width: 80px;margin: 5px;">
			</div>
		    <?php
				echo $this->Html->link($imgToShow, 
					array(
						'controller' => 'peliculas', 
						'action' => 'view', 
						$pelicula['Pelicula']['id'],
					),
	      			['style'=>'margin-left: 10px;','escape'=>false]
	      		); ?>
		    <div class="info">
		      	<div class="wrapper"  style="">	       
			        <div class="flex" style="float: left;">
			        	<p style="display: inline;text-overflow: ellipsis;overflow: auto;white-space: nowrap;">
			          		<?php echo $this->Html->link($pelicula['Pelicula']['titulo'], array('controller' => 'peliculas', 'action' => 'view', $pelicula['Pelicula']['id']),
			      			['style'=>'margin-left: 10px;']); ?>
						</p>	</br>
						<p style="display: inline;white-space: nowrap;overflow: hidden;">
					    <?php echo $this->Html->link($pelicula['Formato']['nombre'], array('controller' => 'formatos', 'action' => 'view', $pelicula['Formato']['id']),
			      			['style'=>'margin-left: 10px;']); ?>
							(<?php echo date('d-m-Y',strtotime($pelicula['Pelicula']['release'])); ?>)
							<?php
							if(!empty($pelicula['Pelicula']['video'])){
							?>
					    		<a style="margin-left: 10px;" href="<?php echo $pelicula['Pelicula']['video']; ?>">Ver Trailer</a>
							<?php } ?>
						</p>
			        </div>
		      	</div>
		      <!--<div style="margin-left: 10px;float: left; width: 80%;">
		      	<p class="overview" style="line-height: 20px;text-align: justify;"><?php echo $pelicula['Pelicula']['overview'] ?></p>
		      </div>-->
		      <div style="margin-left: 10px;float: left; width: 80%;">
		      	 <?php 
			      echo $this->Cart->link(__('Agregar al carrito'), array(
						'item' => $pelicula['Pelicula']['id']
						),[]
					  );
					?>
		      </div>
		   	</div>	  
	  	</div>
	<?php } ?>  

	<div class="paging">
		<p>
		<?php
		echo $this->Paginator->counter(array(
		'format' => __('Pagina {:page} de {:pages}, mostrando {:current} peliculas de {:count} en total, empezando en la numero {:start}, terminando en la {:end}')
		));
		?>	</p>
	<?php
		echo $this->Paginator->prev('< ' . __('anterior'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('siguiente') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>