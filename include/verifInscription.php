<?php
	
	if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['mdp']) && isset($_POST['mdp2']))
	{
		#sleep : ralenti considérablement les attaques bruteforce
		sleep(1);
		#htmlspecialchars : evite les failles par insertion de code dans les champs
		$nom = htmlspecialchars($_POST['nom']);
		$prenom = htmlspecialchars($_POST['prenom']);
		$email = htmlspecialchars($_POST['email']);
		$mdp = htmlspecialchars($_POST['mdp']);
		$mdp2 = htmlspecialchars($_POST['mdp2']);

		#si l'adresse mail a un format valide
		if(filter_var($email,FILTER_VALIDATE_EMAIL))
		{
			#prepare statement : evite les failles par injection SQL
			$res = pg_prepare($vConn,"email","SELECT count(*) AS nb FROM UTILISATEUR WHERE email=$1");
			$res = pg_execute($vConn,"email",array($email));
			$data = pg_fetch_object($res);

			#si elle n'est pas déjà utilisée
			if($data->nb == 0)
			{
				#si les mots de passes sont identiques
				if(strcmp($mdp,$mdp2) == 0 && $mdp != "")
				{
					#on crypte le mdp et on ajoute
					$mdp = sha1($mdp);
					$res = pg_prepare($vConn,"insert","INSERT INTO UTILISATEUR(nom,prenom,email,mdp) VALUES($1, $2, $3, $4)");
					$res = pg_execute($vConn,"insert",array($nom,$prenom,$email,$mdp));
					#s'il s'incrit en tant qu'auteur
					if(isset($_POST['auteur']))
					{
						#on récupère son id
						$res = pg_prepare($vConn,"id","SELECT id_utilisateur AS id FROM UTILISATEUR WHERE email=$1");
						$res = pg_execute($vConn,"id",array($email));
						$data = pg_fetch_object($res);
						#on l'ajoute aux auteurs
						$res = pg_prepare($vConn,"auteur","INSERT INTO auteur VALUES ($1)");
						$res = pg_execute($vConn,"auteur",array($data->id));
					}
					MessagesService::ajouter(MessagesService::OK, "Inscription réussite !");
					if(isset($_SESSION['admin']))
						redirection("membres.php");
					redirection("index.php");
				}
				else
				{
					MessagesService::ajouter(MessagesService::ERREUR, "Les mots de passes saisis sont differents ou sont vides");
				}
			}
			else
			{
				MessagesService::ajouter(MessagesService::ERREUR, "Cette adresse email est deja utilisée");
			}
		}
		else
		{
			MessagesService::ajouter(MessagesService::ERREUR, "L'adresse mail saisie n'est pas valide");
		}
	}

?>
