<?php
function modele_envoi_email_un_jour_avant($pdo) {
    $sql = "SELECT id AS id_reservation, id_utilisateur, date AS date_reservation
            FROM Reservations 
            WHERE 
                (DATE_FORMAT(date, '%Y-%m-%d') = DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d'), INTERVAL 1 DAY) 
                AND id_utilisateur != 'NULL' 
                AND id_utilisateur != 64)
                OR 
                (DATE_FORMAT(date, '%Y-%m-%d') = DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d'), INTERVAL 1 DAY) 
                AND id_utilisateur = 64 
                AND commentaireAdmins != 'NULL')
            ";
    $req = $pdo->prepare($sql);
	$req->execute();
    
    $liste_reservations = $req->fetchAll(PDO::FETCH_OBJ);

	return $liste_reservations;
}

function modele_envoi_email_une_semaine_avant($pdo) {
    $sql = "SELECT id AS id_reservation, id_utilisateur, date AS date_reservation
            FROM Reservations 
            WHERE 
                (DATE_FORMAT(date, '%Y-%m-%d') = DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d'), INTERVAL 7 DAY)
                AND id_utilisateur != 'NULL' 
                AND id_utilisateur != 64)
                OR
                (DATE_FORMAT(date, '%Y-%m-%d') = DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d'), INTERVAL 7 DAY) 
                AND id_utilisateur = 64 
                AND commentaireAdmins != 'NULL')
            "; 
    $req = $pdo->prepare($sql);
	$req->execute();
    
    $liste_reservations = $req->fetchAll(PDO::FETCH_OBJ);

	return $liste_reservations;
}
?>