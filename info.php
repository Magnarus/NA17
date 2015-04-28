<?php
	if(!isset($engineLoaded))
	{
		$needHTML = true;
		require("engine.php");
	}
	if(isset($needHTML))
	{
		$titre = "Informations";
		require("header.php");
	}
	if(isset($_SESSION['admin']))
	{
		if($_SESSION['admin'] == 'o')
		{
			///On vérifie s'il s'agit d'un auteur/admin pour pré-check le formulaire (ou non)
			if(isset($_GET['id']))
			{
				$res = pg_prepare($vConn,"auteur","SELECT COUNT(*) AS nb FROM auteur WHERE id_auteur=$1");
				$res= pg_execute($vConn,"auteur",array($_GET['id']));
				$data = pg_fetch_object($res);
				if($data->nb == 1)
					$auteur = "checked";
				else
					$auteur ="";

				$res = pg_prepare($vConn,"admin","SELECT COUNT(*) AS nb FROM admin WHERE id_admin=$1");
				$res= pg_execute($vConn,"admin",array($_GET['id']));
				$data = pg_fetch_object($res);
				if($data->nb == 1)
					$admin =  "checked";
				else
					$admin ="";

				//Et on récupère les informations classiques
				$res = pg_prepare($vConn,"infos","SELECT nom,prenom,email FROM utilisateur WHERE id_utilisateur=$1");
				$res = pg_execute($vConn,"infos",array($_GET['id']));
				$data = pg_fetch_object($res);
				if(!$data)
				{
					MessagesService::ajouter(MessagesService::ERREUR, "Impossible de récupérer les utilisateurs !");
					redirection("index.php");
				}
			}
?>
			<!--
				Formulaire avec les informations du membre cliqué
				envoyer le formulaire modifie les informations
			-->
			<div class="row">
				<div class="col-md-3 col-md-offset-1">
					<form method="POST" action="editMembre.php">
						<div class="form-group">
							<input class="form-control" type="hidden" name="id" value=<?php echo $_GET['id']?> />
							<input class="form-control" type="text" name="nom" value=<?php echo $data->nom ?> /><br/>
							<input class="form-control" type="text" name="prenom" value=<?php echo $data->prenom ?> /><br/>
							<input class="form-control" type="text" name="email" value=<?php echo $data->email ?> /><br />
							<input class="form-control" type="checkbox" name="auteur" <?php echo $auteur?>>Auteur<br />
							<input class="form-control" type="checkbox" name="admin" <?php echo $admin?>>Admin<br />
							<input class="form-control" type="submit" value="envoyer"/>
						</div>
						<a href="supprUser.php?id=<?php echo $_GET['id']?>"><button type="button" class="btn btn-default">Supprimer</button></a>
					</form>
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
