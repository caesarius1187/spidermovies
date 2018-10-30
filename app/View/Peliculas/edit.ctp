<div class="peliculas form">
<?php echo $this->Form->create('Pelicula',array('class'=>'formTareaCarga','enctype' => 'multipart/form-data')); ?>
	<fieldset>
		<legend><?php echo __('Edit Pelicula'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('formato_id');
		echo $this->Form->input('titulooriginal');
		echo $this->Form->input('titulo')."</br>";
		echo $this->Form->input('price');
		echo $this->Form->input('release')."</br>";
		echo $this->Form->input('overview',['label'=>'Descripcion','style'=>'width:450px',]);
		echo "</br>";
		echo $this->Form->input('video')."</br>";
		echo $this->Form->input('poster')."</br>";
		echo $this->Form->file('imagenpersonalizada',['label'=>'Seleccionar Imagen Nueva','style'=>'width:285px'])."</br>";
		echo $this->Form->input('actores')."</br>";
		echo $this->Form->input('idtmdb');
		echo $this->Form->input('idioma');	
		echo $this->Form->input('Genero.Genero',[
			'style'=>'width: 150px;height: auto;'
		])."</br></br>";	
		
		//echo $this->Html->image('peliculas/14/8.jpg', array('alt' => 'CakePHP'));
		//echo '<img src="'.$this->webroot.'/img/peliculas/14/8.jpg" alt="CakePHP">';
		$myPeliculas = $this->webroot.'img/peliculas/'.$pid.'/';
		$files = glob($folderPeliculas."/*.*");
		for ($i = 0; $i < count($files); $i++) {
		    $image = $files[$i];
		    //echo basename($image) . "<br />"; // show only image name if you want to show full path then use this code // echo $image."<br />";
		    echo '<img src="' .$myPeliculas.basename($image). '" alt="Random image" style="width:185px;height:278px"/>' . "<br /><br />";
		}
	?>
	</fieldset>
<?php echo $this->Form->end(__('Modificar')); ?>
</div>
