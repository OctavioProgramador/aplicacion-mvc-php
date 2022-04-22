<!DOCTYPE html>
<html lang="en">
<head> 
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
</head>
<body>
    <p><?php 
        $this->showMessages();?>
    </p>
	<div id='login-main'>
		<form action="<?php echo constant('URL'); ?>/login/authenticate" method="POST">
			<div><?php (isset($this->errorMessage)) ? $this->errorMessage : '' ?></div>
			<h2> Iniciar sesi�n </h2>
			<p>
			<label for="username"> Username </label>
			<input type="text" name="username" id="username" autocomplete=off>
			</p>
			<p>
			<label for="password"> Password </label>
			<input type="password" name="password" id="password" autocomplete=off>
			</p>
			<p>
			<input type="submit" value="Iniciar Sesi�n"/>
			</p>
			<p>
			�No tienes cuenta? <a href="<?php echo constant('URL'); ?>/signup">Registrarse</a>
			</p>
		</form>	
	</div>
</body>
</html>