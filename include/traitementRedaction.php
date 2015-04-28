<?php

// Nouvelle article
if(isset($_POST['titre']) && isset($_POST['contenuType']) && isset($_POST['contenuValeur']) && isset($_POST['contenuTitre']))
{

	if(!empty($_POST['titre']))
	{
		$_POST['titre'] = trim($_POST['titre']);

		if(isset($_POST['contenuValeur'][0]) && !empty($_POST['contenuValeur'][0]))
		{

			if(isset($_POST['rubrique']))
			{
				if($_POST['rubrique'] != "")
				{
					// Rubrique
					$rubrique = ucfirst(substr(strtolower(trim($_POST['rubrique'])),0, 255));
					$res = pg_prepare($vConn,"rubrique","SELECT nom FROM RUBRIQUE WHERE nom = $1");
					$res = pg_execute($vConn,"rubrique",array($rubrique));
				}
				else
				{
					$rubrique = NULL;
				}

				if($rubrique == NULL || pg_num_rows($res) > 0)
				{
		
					// Article
					if(isset($article))
					{
						$idArticle = $article->id_article;
						$res = pg_prepare($vConn,"articleUpdate","UPDATE ARTICLE SET titre = $1, rubrique = $2 WHERE id_article = $3");
						$res = pg_execute($vConn,"articleUpdate",array($_POST['titre'],$rubrique, $idArticle));
					}
					else
					{
						// Nouvelle article
						$res = pg_prepare($vConn,"articleInsert","INSERT INTO ARTICLE(date_publi,rubrique,id_pers, titre) VALUES(current_timestamp, $1, $2, $3) RETURNING Currval('article_id_article_seq')");
						$res = pg_execute($vConn,"articleInsert",array($rubrique, $_SESSION['id'], $_POST['titre']));

						if($res)
						{
							$idArticle = pg_fetch_row($res);
							$idArticle = $idArticle[0];
						}					
					}

					if(isset($idArticle))
					{
						// Mots clés
						if($_POST['keywords'] != "")
							$keywords = explode(',', strtolower($_POST['keywords']));
						else
							$keywords = array();

						if(count($keywords) > 0)
						{

							$res = pg_prepare($vConn,"keywords","SELECT 1 FROM MOT_CLEF WHERE mot = $1");
							$res = pg_prepare($vConn,"keywordsInsert","INSERT INTO MOT_CLEF(mot) VALUES($1)");
							$res = pg_prepare($vConn,"keywordsDeleteArticle","DELETE FROM COMPORTER_MC WHERE id_article = $1");
							$res = pg_execute($vConn,"keywordsDeleteArticle",array($idArticle));
							$res = pg_prepare($vConn,"keywordsInsertArticle","INSERT INTO COMPORTER_MC(mot_clef, id_article) VALUES($1,$2)");
						
							foreach ($keywords as $key => $value) {
								$value = trim($value);

								$res = pg_execute($vConn,"keywords",array($value));

								if(pg_num_rows($res) == 0)
								{
									$res = pg_execute($vConn,"keywordsInsert",array($value));
								}
							
								$res = pg_execute($vConn,"keywordsInsertArticle",array($value, $idArticle));
							}



							// Version
							$res = pg_prepare($vConn,"versionSelect","SELECT MAX(id_version) FROM VERSION WHERE id_article = $1");
							$res = pg_execute($vConn,"versionSelect",array($idArticle));
							$idVersion = pg_fetch_row($res);
							$idVersion = $idVersion[0] + 1;
							var_dump($idVersion);

							$res = pg_prepare($vConn,"versionInsert", "INSERT INTO VERSION(id_article,id_version, date_modif) VALUES($1, $2, current_timestamp)");
							$res = pg_execute($vConn,"versionInsert", array($idArticle, $idVersion));

							if($res)
							{

								$res = pg_prepare($vConn,"contenuInserttexte", "INSERT INTO TEXTE(id_article,id_version,titre,contenu,position) VALUES($1, $2, $3, $4, $5)");
								$res = pg_prepare($vConn,"contenuInsertimage", "INSERT INTO IMAGE(id_article,id_version,titre,source,position) VALUES($1, $2, $3, $4, $5)");

								for ($i=0; $i < count($_POST['contenuType']); $i++)
								{
									if($_POST['contenuType'][$i] == "texte" || $_POST['contenuType'][$i] == "image")
									{
										$res = pg_execute($vConn,"contenuInsert".$_POST['contenuType'][$i], array($idArticle, $idVersion, $_POST['contenuTitre'][$i], $_POST['contenuValeur'][$i], $i));
									}
									else
									{
										MessagesService::ajouter(MessagesService::ERREUR, "Type de contenu innexistant");
									}
								}

								MessagesService::ajouter(MessagesService::OK, "Article ajouté/modifié !");
								redirection("rediger.php?id=".$idArticle);
							}
							else
							{
								MessagesService::ajouter(MessagesService::ERREUR, "Problème à l'insertion d'une nouvelle version");
							}
						}
						else
						{
							MessagesService::ajouter(MessagesService::ERREUR, "Mots clés non renseignés");
						}
					}
					else
					{
						MessagesService::ajouter(MessagesService::ERREUR, "Problème à l'insertion d'un nouvel article ou à la modification");
					}
				}
				else
				{
					MessagesService::ajouter(MessagesService::ERREUR, "Rubrique inconnue");
				}
			}
			else
			{
				MessagesService::ajouter(MessagesService::ERREUR, "La rubrique n'est pas renseignée");
			}
		}
		else
		{
			MessagesService::ajouter(MessagesService::ERREUR, "Le contenu de l'article est vide.");
		}
	}
	else
	{
		MessagesService::ajouter(MessagesService::ERREUR, "Le titre de l'article est vide.");
	}
}
