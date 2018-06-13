<div class="page-container">
    
    
    <?php echo $this->Form->create('User',[
        'action'=>'login',
        'id'=>'UserLoginForm',
        'class'=>'form_login',
    ]); ?>
    <img src="../img/logo_conta_transparente.png" width="200px" height="200px">
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

