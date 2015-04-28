<?php
	if(!isset($engineLoaded))
	{
		$needHTML = true;
		require("engine.php");
	}

	//On modifie les champs utilisateur
	if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['id']))
	{
		$res = pg_prepare($vConn,"modify","UPDATE utilisateur SET nom=$1, prenom=$2, email=$3 WHERE id_utilisateur=$4");
		$res = pg_execute($vConn,"modify",array($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['id']));
		if(!$res)
		{
			MessagesService::ajouter(MessagesService::ERREUR, "impossible de mettre à jour l'utilisateur !");
			redirection("membres.php");
		}
	}

	//Si auteur coché, on l'ajoute
	if(isset($_POST['auteur']))
	{
		$res = pg_prepare($vConn,"ajoutAuteur","INSERT INTO auteur VALUES($1)");
		$res = pg_execute($vConn,"ajoutAuteur",array($_POST['id']));
	}
	//Sinon, on vérifie s'il était un auteur
	else
	{
		$res = pg_prepare($vConn,"verifAuteur","SELECT COUNT(*) AS nb FROM auteur WHERE id_auteur=$1");
		$res = pg_execute($vConn,"verifAuteur",array($_POST['id']));
		$data = pg_fetch_object($res);
		//Et si oui, on le retire.
		if($data->nb >0)
		{
			$res = pg_prepare($vConn,"supprAuteur","DELETE FROM auteur WHERE id_auteur=$1");
			$res = pg_execute($vConn,"supprAuteur",array($_POST['id']));
			if(!$res)
			{
				MessagesService::ajouter(MessagesService::ERREUR, "impossible de supprimer l'auteur !");
				redirection("membres.php");
			}
		}
	}

	//Idem pour admin
	if(isset($_POST['admin']))
	{
		$res = pg_prepare($vConn,"ajoutAdmin","INSERT INTO admin VALUES($1)");
		$res = pg_execute($vConn,"ajoutAdmin",array($_POST['id']));
	}
	else
	{
		$res = pg_prepare($vConn,"verifAdmin","SELECT COUNT(*) AS nb FROM admin WHERE id_admin=$1");
		$res = pg_execute($vConn,"verifAdmin",array($_POST['id']));
		$data = pg_fetch_object($res);
		if($data->nb >0)
		{
			$res = pg_prepare($vConn,"supprAdmin","DELETE FROM admin WHERE id_admin=$1");
			$res = pg_execute($vConn,"supprAdmin",array($_POST['id']));
			if(!$res)
			{
				MessagesService::ajouter(MessagesService::ERREUR, "impossible de supprimer le droit d'admin !");
				redirection("membres.php");
			}
		}
	}
	MessagesService::ajouter(MessagesService::OK, "Membre modifié !");
	redirection("membres.php");
?>
