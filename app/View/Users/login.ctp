<div class="page-container">
    <?php echo $this->Form->create('User',[
        'action'=>'login',
        'id'=>'UserLoginForm',
        'class'=>'form_login',
    ]); ?>
    <img src="../img/Spider-Movies-Logo-Blanco-Grande.png" width="400px" height="120px" style="margin-left: -43px;">
	<?php
		echo $this->Form->input('username',[
                    'label'=>false,
                    'placeholder'=>"Usuario"
                ]);
		echo $this->Form->input('password',[
                    'label'=>false,
                    'placeholder'=>"Contrase&ntilde;a"
                ]);
        echo $this->Form->button('Ingresar',[
            'type'=>'submit'
            ]);
	?>	        
    <div class="error"><span>+</span></div>
<?php echo $this->Form->end(); ?>
	                
</div>

