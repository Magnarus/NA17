<?php
	if(!isset($engineLoaded))
	{
		$needHTML = true;
		require("engine.php");
	}

	require("include/verifConnexion.php");

	if(isset($needHTML))
	{
		$titre = "Recherche";
		require("header.php");
	}

	if(isset($_GET['id']) && isset($_GET['version'])){
		$req ="SELECT a.id_pers as id FROM article a where a.id_article =".$_GET["id"].";";
		$query=pg_query($vConn, $req);
		$res = pg_fetch_array($query,null,PGSQL_ASSOC);

		if(isset($_SESSION['id']) && $res['id'] == $_SESSION['id']){
			$req ="SELECT * FROM version v where v.id_article =".$_GET["id"].";";
			$query=pg_query($vConn, $req);
			$res = pg_fetch_array($query,null,PGSQL_ASSOC);
			if(pg_num_rows($query)>1){
				$res=pg_query($vConn,"DELETE FROM texte WHERE id_article=".$_GET['id']."");
				$res=pg_query($vConn,"DELETE FROM image WHERE id_article=".$_GET['id']."");
				$res=pg_query($vConn,"DELETE FROM version WHERE id_article=".$_GET['id']." AND id_version=".$_GET['version']."");
				echo $res;
			}else if(pg_num_rows($query)==1){
				$res=pg_query($vConn,"DELETE FROM texte WHERE id_article=".$_GET['id']."");
				$res=pg_query($vConn,"DELETE FROM image WHERE id_article=".$_GET['id']."");
				$res=pg_query($vConn,"DELETE FROM version WHERE id_article=".$_GET['id']."");
				$res=pg_query($vConn,"DELETE FROM comporter_mc WHERE id_article=".$_GET['id']."");
				$res=pg_query($vConn,"DELETE FROM article WHERE id_article=".$_GET['id']."");
				echo $res;
			}
		}
	}
	MessagesService::ajouter(MessagesService::OK, "L'article est bien supprimé !");
	redirection("index.php");
?>