<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro</title>
</head>
<body>
	<?php $this->showMessages(); ?>
<form action="<?php echo constant('URL'); ?>/signup/newUser" method="POST">
		<h2>Registrarse</h2>
		<p>
			<label for="username"> Username </label>
			<input type="text" name="username" id="username">
		</p>
		<p>
			<label for="password"> Password </label>
			<input type="password" name="password" id="password">
		</p>
		<p>
			<input type="submit" value="Iniciar sesi�n" />
		</p>
		<p>
		�Tienes una cuenta? <a href="<?php echo constant('URL'); ?>"> Iniciar sesi�n </a>
		</p>
	</form>
</body>
</html>
