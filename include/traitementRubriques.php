<?php

// Nouvelle article
if(isset($_POST['nom']) && isset($_POST['mere']))
{
	if(!empty($_POST['nom']))
	{
		// Rubrique
		$rubrique = ucfirst(substr(strtolower(trim($_POST['nom'])),0, 255));
		$res = pg_prepare($vConn,"rubrique","SELECT nom FROM RUBRIQUE WHERE nom = $1");
		$res = pg_execute($vConn,"rubrique",array($rubrique));

		if(pg_num_rows($res) == 0)
		{
			if($_POST['mere'] == "")
				$_POST['mere'] = NULL;
			$res = pg_prepare($vConn,"rubriqueInsert","INSERT INTO RUBRIQUE(nom, rub_mere) VALUES($1,$2)");
			$res = pg_execute($vConn,"rubriqueInsert",array($rubrique, $_POST['mere']));
		
			if(pg_affected_rows($res) > 0)
			{
				MessagesService::ajouter(MessagesService::OK, "Rubrique ajoutée");
				redirection("adminRubriques.php");
			}
			else
			{
				MessagesService::ajouter(MessagesService::ERREUR, "La rubrique mère ".$_POST['mere']." n'existe pas.");
			}
		}
		else
		{
			MessagesService::ajouter(MessagesService::ERREUR, "La rubrique ".$rubrique." existe déjà.");
		}
	}
	else
	{
		MessagesService::ajouter(MessagesService::ERREUR, "Le nom de la rubrique est vide.");
	}
}

if(isset($_GET['del']))
{
	$rubrique = ucfirst(substr(strtolower(trim($_GET['del'])),0, 255));

	$res = pg_prepare($vConn,"rubriqueDelete","DELETE FROM RUBRIQUE WHERE nom = $1");
	$res = pg_execute($vConn,"rubriqueDelete",array($rubrique));

	if(pg_affected_rows($res) > 0)
	{
		MessagesService::ajouter(MessagesService::OK, "Rubrique(s) supprimée(s)");
		redirection("adminRubriques.php");
	}
	else
	{
		MessagesService::ajouter(MessagesService::ERREUR, "Aucune rubrique n'a été supprimée");
	}
}