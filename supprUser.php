<?php
	if(!isset($engineLoaded))
	{
		$needHTML = true;
		require("engine.php");
	}
	if(isset($_SESSION['admin']))
	{
		if($_SESSION['admin'] == 'o')
		{
			if(isset($_GET['id']))
			{
				$res=pg_prepare($vConn,"admin","SELECT COUNT(*) AS nb FROM admin WHERE id_admin=$1");
				$res=pg_execute($vConn,"admin",array($_GET['id']));
				$data=pg_fetch_object($res);
				if($data->nb >0)
				{
					$res=pg_query($vConn,"DELETE FROM admin WHERE id_admin='".$_GET['id']."'");
					if(!$res)
					{
						MessagesService::ajouter(MessagesService::ERREUR, "Impossible de supprimer d'admin !");
						redirection("membres.php");
					}

				}
				
				$res=pg_prepare($vConn,"auteur","SELECT COUNT(*) AS nb FROM auteur WHERE id_auteur=$1");
				$res=pg_execute($vConn,"auteur",array($_GET['id']));
				$data=pg_fetch_object($res);
				if($data->nb >0)
				{
					$res=pg_query($vConn,"DELETE FROM auteur WHERE id_auteur='".$_GET['id']."'");
					if(!$res)
					{
						MessagesService::ajouter(MessagesService::ERREUR, "Impossible de supprimer l'auteur !");
						redirection("membres.php");
					}
				}

				$bool = 0;
				if($_SESSION['id'] == $_GET['id'])
					$bool = 1;
				$res=pg_query($vConn,"DELETE FROM utilisateur WHERE id_utilisateur='".$_GET['id']."'");
				if(!$res)
				{
					MessagesService::ajouter(MessagesService::ERREUR, "Impossible de supprimer l'utilisateur ! (un article doit être encore associé à cette utilisateur)");
					redirection("membres.php");
				}
				else
				{	
					if($bool)
					{
						MessagesService::ajouter(MessagesService::OK, "Votre compte est bien supprimé !");
						redirection("deconnexion.php");
					}	
					else
					{
						MessagesService::ajouter(MessagesService::OK, "utilisateur supprimé !");
						redirection("membres.php");
					}
				}
			}
			else
			{
				MessagesService::ajouter(MessagesService::ERREUR, "cet utilisateur n'existe pas !");
				redirection("membres.php");
			}
		}
		else
		{
			MessagesService::ajouter(MessagesService::ERREUR, "vous n'êtes pas administrateur !");
			redirection("index.php");
		}
	}
	else
	{
		MessagesService::ajouter(MessagesService::ERREUR, "vous n'êtes pas connecté !");
		redirection("index.php");
	}
?>
