<?php
	if(!isset($engineLoaded))
	{
		$needHTML = true;
		require("engine.php");
	}
	if(isset($needHTML))
	{
		$titre = "Administration";
		require("header.php");
	}
	///On vérifie que la personne qui accède à cette page soit admin
	if(isset($_SESSION['admin']))
	{
		//Si oui, on affiche le menu
		if($_SESSION['admin'] == 'o')
		{
			echo '<ul class="list-group list ul-accueil-admin">'.
					'<li class="list-group-item"><a href="membres.php">Membres</a></li>'.
					'<li class="list-group-item"><a href="adminArticles.php">Articles</a></li>'.
					'<li class="list-group-item"><a href="adminRubriques.php">Rubriques</a></li>'.
				 '</ul>';
		}
		else
		{
			MessagesService::ajouter(MessagesService::ERREUR, "Vous n'êtes pas administrateur !");
			redirection("index.php");
		}
	}
	else
	{
		MessagesService::ajouter(MessagesService::ERREUR, "Vous n'êtes pas connecté !");
		redirection("index.php");
	}
	if(isset($needHTML))
	{
		require("footer.php");
	}
?>
