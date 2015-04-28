<?php


// Ouverture d'un article
if(isset($_GET['id']))
{
	$res = pg_prepare($vConn,"articleSelect","SELECT id_article, titre, date_publi, rubrique FROM ARTICLE WHERE id_article = $1");
	$res = pg_execute($vConn,"articleSelect",array($_GET['id']));
	
	if(pg_num_rows($res))
	{
		$article = pg_fetch_object($res);
	}
	else
	{
		MessagesService::ajouter(MessagesService::ERREUR, "Article n°".$_GET['id']." non trouvé !");
	}
}

if(isset($article))
{
	if(isset($_GET['version']))
	{
		$res = pg_prepare($vConn,"articleVersionSelect","SELECT id_version, date_modif FROM VERSION WHERE id_article = $1 and id_version = $2");
		$res = pg_execute($vConn,"articleVersionSelect",array($article->id_article, $_GET['version']));
	}
	else
	{
		$res = pg_prepare($vConn,"articleVersionSelect","SELECT id_version, date_modif FROM VERSION WHERE id_article = $1 ORDER BY id_version DESC LIMIT 1");
		$res = pg_execute($vConn,"articleVersionSelect",array($article->id_article));
	}

	if(pg_num_rows($res))
	{
		$version = pg_fetch_object($res);

		$contenu = array();

		$res = pg_prepare($vConn,"articleImageSelect","SELECT titre, source, position FROM IMAGE WHERE id_article = $1 AND id_version = $2");
		$res = pg_execute($vConn,"articleImageSelect",array($article->id_article, $version->id_version));

		while($c = pg_fetch_object($res))
		{
			$contenu[$c->position] = array('type' => 'image', 'titre' => $c->titre, 'contenu' => $c->source);
		}

		$res = pg_prepare($vConn,"articleTexteSelect","SELECT titre, contenu, position FROM TEXTE WHERE id_article = $1 AND id_version = $2");
		$res = pg_execute($vConn,"articleTexteSelect",array($article->id_article, $version->id_version));

		while($c = pg_fetch_object($res))
		{
			$contenu[$c->position] = array('type' => 'texte', 'titre' => $c->titre, 'contenu' => $c->contenu);
		}
	}
	else
	{
		MessagesService::ajouter(MessagesService::ERREUR, "Version de l'article n°".$_GET['id']." non trouvé !");
	}

	// Keywords
	
	$res = pg_prepare($vConn,"articleKeywordsSelect","SELECT mot_clef FROM COMPORTER_MC WHERE id_article = $1");
	$res = pg_execute($vConn,"articleKeywordsSelect",array($article->id_article));
	
	$keywords = array();

	while($c = pg_fetch_object($res))
	{
		$keywords[] = $c->mot_clef;
	}
	
	
}
