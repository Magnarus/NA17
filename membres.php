<?php
	if(!isset($engineLoaded))
	{
		$needHTML = true;
		require("engine.php");
	}
	if(isset($needHTML))
	{
		$titre = "Liste des membres";
		require("header.php");
	}

	$res = pg_query($vConn,"SELECT id_utilisateur AS id, nom, prenom FROM utilisateur");
	if(isset($_SESSION['admin']))
	{
		if($_SESSION['admin'] == 'o')
		{
?>
			<!--
			 On affiche la liste des membres
			 En cliquant, cela envoie vers la page de moficiation d'informations
			-->
			<div class="row">
				<div class="col-md-3 col-md-offset-1">
					<div class="panel panel-default">
						<table class="table ">
							<tr>
								<th>id</th>
								<th>nom</th>
								<th>prenom</th>
							</tr>
							<?php
								while($row = pg_fetch_assoc($res))
								{
									echo '<tr>';
										echo '<td>'.'<a href="info.php?id='.$row['id'].'">'.$row['id'].'</a>'.'</td>';
										echo '<td>'.'<a href="info.php?id='.$row['id'].'">'.$row['nom'].'</a>'.'</td>';
										echo '<td>'.'<a href="info.php?id='.$row['id'].'">'.$row['prenom'].'</a>'.'</td>';
									echo '</tr>';
								}
							?>
						</table>
					</div>
					<a href="inscription.php"><button type="button" class="btn btn-default">Ajouter</button></a>
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
