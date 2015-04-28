<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="cache-control" content="max-age=0" />
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
		<meta http-equiv="pragma" content="no-cache" />
		<title><?php isset($titre)?$titre.' - ':''?>WCMS - NA17</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
		<link rel='stylesheet' type='text/css' href='radmenu.css' />
		<script type='text/javascript' src='radmenu.js'></script>
		<script src="script.js"></script>
	</head>
	<body>
		<nav class="navbar navbar-default navbar-top">
			<div class="container">
			  	<h1>NA17 | WCMS</h1>
				<a href="index.php"><button type="button" class="btn btn-default navbar-btn">Menu principal</button></a>
				<?php if(!isset($_SESSION['id']))
				{
					?>
					<a href="connexion.php"><button type="button" class="btn btn-default navbar-btn">Se connecter</button></a>
					<a href="inscription.php"><button type="button" class="btn btn-default navbar-btn">S'inscrire</button></a>				
					<?php
				} else {
					?>					
					<a href="listeArticle.php"><button type="button" class="btn btn-default navbar-btn">Rechercher</button></a>
					<?php 

					// Auteur
					$res = pg_prepare($vConn,"isAuteurHeader","SELECT 1 FROM auteur WHERE id_auteur=$1");
					$res = pg_execute($vConn,"isAuteurHeader",array($_SESSION['id']));

					if(pg_num_rows($res))
					{
						?>
						<a href="rediger.php"><button type="button" class="btn btn-default navbar-btn">Rédiger</button></a>
						<?php
					}

					// Admin
					$res = pg_prepare($vConn,"isAdminHeader","SELECT 1 FROM admin WHERE id_admin = $1");
					$res = pg_execute($vConn,"isAdminHeader", array($_SESSION['id']));
					if(pg_num_rows($res))
					{
						?>
						<a href="accueilAdmin.php"><button type="button" class="btn btn-default navbar-btn">Administrer</button></a>
						<?php
					}
					?>
					<a href="deconnexion.php"><button type="button" class="btn btn-default navbar-btn">Se déconnecter</button></a>
					<?php
				}
				?>
			</div>
		</nav>
		<h2><?php echo isset($titre)?$titre:''?></h2>
	
		<?php
			MessagesService::afficher();
		?>
