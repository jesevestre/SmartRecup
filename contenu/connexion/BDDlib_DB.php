<?php
function connexion($HOSTNAME, $DBNAME, $USER, $PASSWORD) {
	$pdo = null;
	
	try{
	$pdo = new PDO('mysql:host='.$HOSTNAME.';dbname='.$DBNAME.';charset=utf8', $USER, $PASSWORD);

	$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	catch(Exception $e){
		die("Erreur: " .$e->getMessage());
	}

	return $pdo;
}


function select($pdo, $query) {
	
	try{
		$pdos = $pdo->query($query);
		$tab = $pdos->fetchAll(PDO::FETCH_ASSOC);
		$pdos->closeCursor();
		return $tab;
	}

	catch (PDOException $pdoe){
		echo "<p>Erreur: " .$pdoe->getMessage()."</p>\n";
		echo "<p>Numéro: " .$pdoe->getCode()."</p>\n";
	}
}
