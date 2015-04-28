<?php
	
if(!isset($engineLoaded))
{
	$needHTML = true;
	require("engine.php");
}

require("include/verifConnexion.php");

if(isset($needHTML))
{
	require("header.php");
}

//Test si l'id de l'article est passé en paramètre
if (isset($_GET['article']) && isset($_GET['version'])) {
	$id_article = htmlspecialchars($_GET['article']);
	$id_version = htmlspecialchars($_GET['version']);
	
	// Récupération de l'auteur et de l'article
	$sql = "SELECT * FROM article a, version v, utilisateur u WHERE a.id_article='".$id_article."'AND a.id_pers=u.id_utilisateur AND v.id_version='".$id_version."'AND a.id_article=v.id_article;";
	$query = pg_query($vConn,$sql);
	$res = pg_fetch_array($query,null,PGSQL_ASSOC);
	
	// Test si le résultat est vide
	if (!empty($res)) {
		// On stocke le résultat de la requête dans des variables
		$titre = $res['titre'];
		$date_publi = $res['date_publi'];
		$date_modif = $res['date_modif'];
		$rubrique = $res['rubrique'];
		$id_pers = $res['id_utilisateur'];
		$nom = $res['nom'];
		$prenom = $res['prenom'];
		
		// Récupération du contenu


		$contenu = array();

		$res = pg_prepare($vConn,"articleImageSelect","SELECT titre, source, position FROM IMAGE WHERE id_article = $1 AND id_version = $2");
		$res = pg_execute($vConn,"articleImageSelect",array($id_article, $id_version));

		while($c = pg_fetch_object($res))
		{
			$contenu[$c->position] = array('type' => 'image', 'titre' => $c->titre, 'contenu' => $c->source);
		}

		$res = pg_prepare($vConn,"articleTexteSelect","SELECT titre, contenu, position FROM TEXTE WHERE id_article = $1 AND id_version = $2");
		$res = pg_execute($vConn,"articleTexteSelect",array($id_article, $id_version));

		while($c = pg_fetch_object($res))
		{
			$contenu[$c->position] = array('type' => 'texte', 'titre' => $c->titre, 'contenu' => $c->contenu);
		}

		// Bouton de modification pour l'auteur de l'article
		if((isset($_SESSION['id']) && $_SESSION['id'] == $id_pers))
		{
			echo "<a class='button-article' href='rediger.php?id=".$id_article."&version=".$id_version."'><button type='button' class='btn btn-default navbar-btn'>Editer</button></a>";
		}
		
		if(isset($_SESSION['admin']))
		{
				echo "<a class='button-article' href='supprimer_article.php?id=".$id_article."&version=".$id_version."'><button type='button' class='btn btn-default navbar-btn'>Supprimer</button></a>";
		}
		
		//Récupération mots-clés
		$sql = "SELECT mot_clef as clef FROM comporter_mc mc WHERE mc.id_article=".$id_article.";";
		$query = pg_query($vConn,$sql);
		// Affichage du contenu
		echo "<div class='panel-article'>";
		echo "<h1>".$titre."</h1>";
		echo "<p class='article-ita'>";
		echo "Rédigé par : ".$nom." ".$prenom;
		echo ", publié le : ".$date_publi;
		echo ", modifié le : ".$date_modif;
		echo "<br>Rubrique : ".$rubrique;
		echo "<br>Mots-clés : ";
		while($result = pg_fetch_array($query, null, PGSQL_ASSOC)){
			echo $result['clef']." ";
		}
		
		echo "</p>";
		echo "</div>";

		for ($i=0;$i < count($contenu);$i++){
			if ($contenu[$i]['type'] == 'texte') {
				echo "<div class='panel panel-default panel-article'>";
				echo "<div class='panel-heading'>";
				echo "<h2 class='panel-title'>".$contenu[$i]['titre']."</h2>";
				echo "</div>";
				echo "<div class='panel-body'>";
				echo $contenu[$i]['contenu'];
				echo "</div></div>";
			} else if ( $contenu[$i]['type'] == 'image') {
				echo "<div class='panel panel-default panel-article'>";
				echo "<div class='panel-heading'>";
				echo "<h2 class='panel-title'>".$contenu[$i]['titre']."</h2>";
				echo "</div>";
				echo "<div class='panel-body'>";
				echo "<p>";
					echo "<img class='img-article'src=".$contenu[$i]['contenu']." alt=".$contenu[$i]['titre'].">";
				echo "</p>";
				echo "</div></div>";					
			}
		}
		
		
	} else {
		MessagesService::ajouter(MessagesService::ERREUR, "Cet article n'existe pas!");
	}
} else {
		MessagesService::ajouter(MessagesService::ERREUR, "Cet article n'existe pas!");
}

?>

<?php
if(isset($needHTML))
{
	require("footer.php");
}
?>
