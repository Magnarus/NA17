<?php

verifConnecte();

$res = pg_prepare($vConn,"isAuteur","SELECT 1 FROM auteur WHERE id_auteur=$1");
$res = pg_execute($vConn,"isAuteur",array($_SESSION['id']));

if(!pg_num_rows($res))
{
	// N'est pas un auteur
	MessagesService::ajouter(MessagesService::ERREUR, "Vous n'êtes pas un auteur");
	home();
}

// L'utilisateur est un auteur