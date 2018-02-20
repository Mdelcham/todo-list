<?php
	// AJOUT D'UNE NOUVELLE CHOSE A FAIRE.
	// Si l'input nouvelle tâche existe et n'est pas vide
	if (ISSET($_POST["newTache"]) && strlen($_POST["newTache"]) >= 5 && strlen($_POST["newTache"]) <= 50)
	{
		// Import les données du fichier
    	$file = file_get_contents('./assets/db/todo.json');
    	// Si le fichier est vide, créer un objet vide "$taches" et deux tableaux vides.
    	if (EMPTY($file))
    	{
			$taches = new stdClass();
			$aFaire = array();
			$archives = array();
    	}
    	// Sinon, traduction du fichier en objet php contenant les deux tableaux.
    	else
    	{
    		$taches = json_decode($file);
			$aFaire = $taches->aFaire;
			$archives = $taches->archives;
    	}
    	// Copie de l'input nouvelle tâche.
		$newTache = $_POST["newTache"];
		// Nettoyage de l'input pour éviter les injections de base.
		$newTache = htmlspecialchars($newTache);
		// Ajout de la nouvelle tâche dans l'array (par le bas: index 0).
		array_unshift($aFaire, $newTache);
		// Ecraser l'ancien array "aFaire" par celui mis à jour et reinjection de l'array "archives".
		$taches->aFaire = $aFaire;
		$taches->archives = $archives;
		// Traduction de l'objet au format Json.
		$taches = json_encode($taches, JSON_PRETTY_PRINT);
		// Ecraser le fichier avec les nouvelles données.
		file_put_contents('./assets/db/todo.json', $taches);
	}
	// PASSER UNE/DES ACTIVITE(S) A FAIRE DANS LES ARCHIVES.
	if (ISSET($_POST["aFaireArray"]) && !EMPTY($_POST["aFaireArray"]))
	{
		$file = file_get_contents('./assets/db/todo.json');
		$taches = json_decode($file);
		$aFaire = $taches->aFaire;
		$archives = $taches->archives;
		$aFaireToArchive = $_POST["aFaireArray"];
		$aFaireToArchiveLength = count($GLOBALS["aFaireToArchive"]);
		// Parcours les activités validées pour passer aux archives.
		for ($i = 0; $i < $aFaireToArchiveLength; $i++) 
		{
			$aFaireLength = count($GLOBALS["aFaire"]);
			// Parcours des activités à faire pour en extraire le(s) élément(s) séléctionnés et les ajouter dans le tableau des archives.
			for ($j = 0; $j < $aFaireLength; $j++)
			{
				// Si l'activité validée pour passer aux archives sont identiques à l'une des entrées du tableau des activités...
				if ($GLOBALS["aFaireToArchive"][$i] == $GLOBALS["aFaire"][$j])
				{
					// Ajouter l'élément au tableau des archives.
					array_unshift($archives, $GLOBALS["aFaire"][$j]);
					// Effacer l'index du tableau "aFaire" qui vient d'être déplacé.
					unset($GLOBALS["aFaire"][$j]);
				}
				// Reindexer le tableau "aFaire".
				$GLOBALS["aFaire"] = array_values(array_filter($GLOBALS["aFaire"]));
			}
		}
		$taches->aFaire = $aFaire;
		$taches->archives = $archives;
		$file = json_encode($taches, JSON_PRETTY_PRINT);
		file_put_contents('./assets/db/todo.json', $file);
	}

	if (ISSET($_POST["ui"]) && !EMPTY($_POST["ui"]))
	{
		//importer données du fichier json.
		$file = file_get_contents('./assets/db/todo.json');
		// convertir l'objet provenant du fichier en object php '$taches'.
    	$taches = json_decode($file);
    	// transforme le string ui provenant du javascript en tableau php.
 		$newUi = explode(",", $_POST["ui"]);
 		// mise à jour de l'ordre du tableau 'aFaire' dans l'objet php '$taches'.
    	$taches->aFaire = $newUi;
    	// convertir l'object php '$taches' au format json.
    	$file = json_encode($taches, JSON_PRETTY_PRINT);
    	// écrasement du fichier todo.json.
		file_put_contents('./assets/db/todo.json', $file);
	}
	header('Location: index.php');
?>