<?php
	if(!isset($engineLoaded))
	{
		$needHTML = true;
		require("engine.php");
	}
	if(isset($needHTML))
	{
		$titre = "Liste des articles";
		require("header.php");
	}

	$res = pg_query($vConn,"select id_article AS id, nom, titre, rubrique from article, utilisateur WHERE id_pers = id_utilisateur");
	if(isset($_SESSION['admin']))
	{
		if($_SESSION['admin'] == 'o')
		{
?>
			<!--
			 On affiche la liste des articles
			 En cliquant, cela envoie vers la page des versions
			-->
			<div class="row">
				<div class="col-md-4 col-md-offset-1">
					<div class="panel panel-default">
						<table class="table">
							<tr>
								<th>id</th>
								<th>nom</th>
								<th>titre</th>
								<th>rubrique</th>
							</tr>
							<?php
								while($row = pg_fetch_assoc($res))
								{
									echo '<tr>';
										echo '<td>'.'<a href="adminVersion.php?id='.$row['id'].'">'.$row['id'].'</a>'.'</td>';
										echo '<td>'.'<a href="adminVersion.php?id='.$row['id'].'">'.$row['nom'].'</a>'.'</td>';
										echo '<td>'.'<a href="adminVersion.php?id='.$row['id'].'">'.$row['titre'].'</a>'.'</td>';
										echo '<td>'.'<a href="adminVersion.php?id='.$row['id'].'">'.$row['rubrique'].'</a>'.'</td>';
									echo '</tr>';
								}
							?>
						</table>
					</div>
				</div>
			</div>
<?php
		}
		else
		{
			MessagesService::ajouter(MessagesService::ERREUR, "Vous n'êtes pas administrateur !");
			redirection("index.php");
		}
	}
	else
	{
		MessagesService::ajouter(MessagesService::ERREUR, "Vous n'êtes pas administrateur !");
		redirection("index.php");
	}

	if(isset($needHTML))
	{
		require("footer.php");
	}
?>
