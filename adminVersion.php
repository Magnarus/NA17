<?php
	if(!isset($engineLoaded))
	{
		$needHTML = true;
		require("engine.php");
	}
	if(isset($needHTML))
	{
		$titre = "Liste des versions";
		require("header.php");
	}
	if(isset($_SESSION['admin']))
	{
		if($_SESSION['admin'] == 'o')
		{
			///On récupère les versions de l'article sélectionné
			if(isset($_GET['id']))
			{
				$requete = "select id_version AS id, date_modif AS date FROM version WHERE id_article=$1";
				$res= pg_prepare($vConn,"versions",$requete);
				$res= pg_execute($vConn,"versions",array($_GET['id']));
			}
			
?>
			<!--
			 On affiche la liste des versions
			 En cliquant, cela envoie vers la page d'édition
			-->
			<div class="row">
				<div class="col-md-3 col-md-offset-1">
					<div class="panel panel-default">
						<table class="table">
							<tr>
								<th>id</th>
								<th>date</th>
							</tr>
							<?php
								while($row = pg_fetch_assoc($res))
								{
									echo '<tr>';
										echo '<td>'.'<a href="rediger.php?id='.$_GET['id'].'&version='.$row['id'].'">'.$row['id'].'</a>'.'</td>';
										echo '<td>'.'<a href="rediger.php?id='.$_GET['id'].'&version='.$row['id'].'">'.$row['date'].'</a>'.'</td>';
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
