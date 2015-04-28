<?php
	
	require("engine.php");

	require("header.php");
	
	
		$req ="SELECT a.id_article as id_article, a.titre as titre, a.date_publi as date, u.nom as nom, u.prenom as prenom FROM article a, 
		utilisateur u where u.id_utilisateur=a.id_pers;";
		$query=pg_query($vConn, $req);
	
		echo "<div class='row'>";
		echo "<div class='col-md-10 col-md-offset-1'>";
		if(pg_num_rows($query)==0){
			echo "<p>Aucun article n'a été trouvé</p>";
			
		}else{
			echo "<div class='panel panel-default'>";
			echo "<div class='panel-heading'>Articles récents :</div>";
			echo "<table class='table'>";
			echo "<tr>";
			echo "<th>Titre</th>";
			echo "<th>Date de publication</th>";
			echo "<th>Nom auteur</th>";
			echo "<th>Prenom auteur</th>";
			echo "<th>Version</th>";
			echo "</tr>";

			while ($result = pg_fetch_array($query, null, PGSQL_ASSOC)) {
				echo "<FORM method='get' action='article.php'><tr>";
				echo "<td>".$result['titre']."</td>";
				echo "<td>".$result['date']."</td>";
				echo "<td>".$result['nom']."</td>";
				echo "<td>".$result['prenom']."</td>";
				$req2 ="SELECT id_version, date_modif FROM version WHERE id_article=".$result['id_article'].";";
				$query2=pg_query($vConn, $req2);
				if(pg_num_rows($query)!=0){
					echo "<td><SELECT name='version' size='1'>";
					while ($result2 = pg_fetch_array($query2, null, PGSQL_ASSOC)) {
						echo "<OPTION value='".$result2['id_version']."'>".$result2['date_modif'];
					}
					echo "</SELECT></td>";
				}
				echo "<input style='visibility:hidden' name='article' value='".$result['id_article']."'/>";
				echo "<td><button type='submit'>Voir</button></td>";
				echo "</tr></FORM>";
			}
			echo "</table>";
			echo "</div>";
		}

		echo "</div></div>";

	require("footer.php");
?>
