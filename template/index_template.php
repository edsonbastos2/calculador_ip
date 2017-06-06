<!DOCTYPE html>
<html lang="pt_BR">
  <head>
    <meta charset="utf-8">

    <title>Calculadora IP para LAN (Rede Privada) IPv4</title>

    <!-- Bootstrap CSS -->
    <link href="template/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="template/login.css" rel="stylesheet">

  </head>

  <body>

	<?php
	if(!empty($msg)){	
		echo "  <br><br>
			<div class='row'>
				<div class='container'>
					<div class='alert alert-danger'>
						<strong> ".$msg." </strong>
					</div>
				</div>
			</div>
			<br><br>";
	}
	?>
    <div class="row">
      <div class="container">
	<?php
	if(!empty($resultado) && empty($msg)){	
		echo "<br><br><pre>";
		echo $resultado;
		echo "</pre>";
	}
	?>
      </div>
    </div>
    <div class="container">

      <form class="form-signin" method="post">
        <h2 class="form-signin-heading"><center>Calculadora IP <br><br> LAN (Rede Privada) <br><br> IPv4</center></h2><br><br>
        <label for="ip" class="sr-only">IP</label>
        <input type="text" id="ip" name="ip" class="form-control" value="<?php echo $_POST['ip']; ?>" placeholder="192.168.0.1" required autofocus><br><br>
        <label for="mascara" class="sr-only">M&aacute;scara</label>
        <input type="text" id="mascara" name="mascara" class="form-control" value="<?php echo $_POST['mascara']; ?>" placeholder="24 ou 255.255.255.0" required><br><br>
        <button class="btn btn-lg btn-primary btn-block" name="calcular" value=1 type="submit">Calcular</button>
      </form>

    </div>
    <div class="row">
    </div>
  </body>
</html>
