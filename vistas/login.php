<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/x-icon" href="../img/WhatsApp_Image_2025-11-29_at_00.05.29-removebg-preview.ico">
	<title>Iniciar Sesión - Colmado Gamer</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css">
	<link rel="stylesheet" href="css/colores_globales.css">
	<style>
		body {
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			background: linear-gradient(135deg, #192443 0%, #2a3a5e 100%);
		}
		
		.login-container {
			width: 100%;
			max-width: 420px;
		}

		.login-card {
			background: linear-gradient(to bottom, #2a3a5e, #1a2a4d);
			border: 2px solid #e7a923;
			border-radius: 15px;
			box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
			padding: 40px;
			animation: fadeIn 0.5s ease-in;
		}

		.logo-container {
			text-align: center;
			margin-bottom: 30px;
		}

		.logo-container img {
			width: 100px;
			height: 100px;
			border-radius: 50%;
			border: 3px solid #e7a923;
			box-shadow: 0 5px 15px rgba(230, 169, 35, 0.3);
		}

		.login-title {
			text-align: center;
			color: #bb1818;
			font-size: 28px;
			font-weight: 700;
			margin-bottom: 10px;
		}

		.login-subtitle {
			text-align: center;
			color: #e7a923;
			font-size: 14px;
			margin-bottom: 30px;
		}

		.form-group label {
			color: #e7a923;
			font-weight: 600;
			margin-bottom: 8px;
		}

		.form-control {
			background-color: #1a2a4d !important;
			border: 2px solid #e7a923 !important;
			color: white !important;
			padding: 12px 15px;
			border-radius: 8px;
			font-size: 14px;
		}

		.form-control:focus {
			background-color: #1a2a4d !important;
			color: white !important;
			border: 2px solid #fe952d !important;
			box-shadow: 0 0 8px rgba(254, 149, 45, 0.3) !important;
		}

		.form-control::placeholder {
			color: #999;
		}

		.input-group-text {
			background-color: #e7a923 !important;
			border: 2px solid #e7a923 !important;
			color: white !important;
		}

		.btn-login {
			background: linear-gradient(135deg, #e7a923 0%, #fe952d 100%);
			border: none;
			color: white;
			font-weight: 700;
			font-size: 16px;
			padding: 12px 30px;
			border-radius: 8px;
			width: 100%;
			transition: all 0.3s ease;
			margin-top: 20px;
		}

		.btn-login:hover {
			transform: translateY(-2px);
			box-shadow: 0 8px 16px rgba(230, 169, 35, 0.4);
			color: white;
		}

		.btn-login:active {
			transform: translateY(0);
		}

		.remember-me {
			color: #e7a923;
			font-size: 14px;
		}

		.remember-me input {
			accent-color: #e7a923;
		}

		.signup-link {
			text-align: center;
			margin-top: 20px;
			padding-top: 20px;
			border-top: 1px solid #e7a923;
		}

		.signup-link a {
			color: #e7a923;
			font-weight: 600;
			text-decoration: none;
			transition: all 0.3s ease;
		}

		.signup-link a:hover {
			color: #fe952d;
			text-decoration: underline;
		}

		.error-message {
			background-color: #5f1e1e;
			border: 2px solid #bb1818;
			color: #ff9090;
			padding: 12px;
			border-radius: 8px;
			margin-bottom: 20px;
			font-size: 14px;
		}

		@media (max-width: 480px) {
			.login-card {
				padding: 30px 20px;
			}

			.login-title {
				font-size: 22px;
			}
		}
	</style>
</head>
<body>
	<div class="login-container">
		<div class="login-card">
			<div class="logo-container">
				<img src="../img/WhatsApp_Image_2025-11-29_at_00.05.29-removebg-preview.ico" alt="Colmado Gamer Logo">
			</div>

			<h1 class="login-title">🎮 Colmado Gamer</h1>
			<p class="login-subtitle">Inicia sesión para acceder</p>

			<form action="../controladores/login_controlador.php" method="POST">
				<div class="form-group">
					<label for="usuario"><i class="fas fa-user"></i> Usuario</label>
					<input type="text" class="form-control" id="usuario" name="usuario" placeholder="Tu usuario" required>
				</div>

				<div class="form-group">
					<label for="pass"><i class="fas fa-lock"></i> Contraseña</label>
					<input type="password" class="form-control" id="pass" name="pass" placeholder="Tu contraseña" required>
				</div>

				<div class="form-group">
					<div class="remember-me">
						<input type="checkbox" id="remember" name="remember">
						<label for="remember">Recuérdame</label>
					</div>
				</div>

				<button type="submit" class="btn btn-login">
					<i class="fas fa-sign-in-alt"></i> Iniciar Sesión
				</button>
			</form>

			<div class="signup-link">
				¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
			</div>
		</div>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>
</html>