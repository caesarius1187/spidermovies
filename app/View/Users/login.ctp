<div class="page-container">
	<form action="/contasynfotech/users/login" id="UserLoginForm" method="post" accept-charset="utf-8"
		  style="background-color: #99c8f2;padding: 40px;">
		<img src="../img/logo.png" width="300px" height="60px">
		<div style="display:none;">
			<input type="hidden" name="_method" value="POST">
		</div>
		<fieldset>
			<input type="text" name="data[User][username]" class="username" placeholder="Usuario">
	        <input type="password" name="data[User][password]" class="password" placeholder="Contraseña">
	        <button type="submit">INGRESAR</button>
	        <div class="error"><span>+</span></div>
		</fieldset>
	</form>
</div>