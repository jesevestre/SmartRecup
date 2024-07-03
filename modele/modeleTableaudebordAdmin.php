<?php
function ajoutRendezvous($pdo, $type, $dateTime) {
    $sql = "INSERT INTO Reservations (id_utilisateur, id_type, id_etat, date, commentaireClient, commentaireAdmins) VALUES (NULL, ?, 1, ?, NULL, NULL)";    
    $req = $pdo->prepare($sql);
    $req->execute(array($type, $dateTime));
    $result = $req;

    if($result) {
        return true;
    } else {
        return false;
    }
}

function supprimerRendezvous($pdo, $id_reservation) {
    $sql = "DELETE FROM Reservations WHERE id = ?";    
    $req = $pdo->prepare($sql);
    $req->execute(array($id_reservation));
    $result = $req;

    if($result) {
        return true;
    } else {
        return false;
    }
}

function retirerRendezvous($pdo, $id_reservation) {
    $sql = "UPDATE Reservations SET id_utilisateur = NULL, id_type_massage = NULL, id_type_machine = NULL, id_etat = 1, commentaireClient = NULL, commentaireAdmins = NULL WHERE id = ?";
    $req = $pdo->prepare($sql);
    $req->execute(array($id_reservation));
    $result = $req;

    if($result) {
        return true;
    } else {
        return false;
    }
}

function editCommentaireAdmins($pdo, $commentaireAdmin, $id_reservation) {
    $sql = "UPDATE Reservations SET commentaireAdmins = ? WHERE id = ?";    
    $req = $pdo->prepare($sql);
    $req->execute(array($commentaireAdmin, $id_reservation));
    $result = $req;

    if($result) {
        return true;
    } else {
        return false;
    }
}

function bloquerDebloquerUtilisateur($pdo, $id_utilisateur) {
    $sql = "SELECT bloque FROM Utilisateurs WHERE id = ?";
    $req = $pdo->prepare($sql);
    $req->execute(array($id_utilisateur));
	$result = $req->fetchAll(PDO::FETCH_OBJ);

    if($result[0]->bloque == 0) {
        $sql = "UPDATE Utilisateurs SET bloque = 1 WHERE id = ?";    
        $req = $pdo->prepare($sql);
        $req->execute(array($id_utilisateur));
        $result = $req;
    
        if($result) {
            return true;
        } else {
            return false;
        }
    } else {
        $sql = "UPDATE Utilisateurs SET bloque = 0 WHERE id = ?";    
        $req = $pdo->prepare($sql);
        $req->execute(array($id_utilisateur));
        $result = $req;
    
        if($result) {
            return true;
        } else {
            return false;
        }
    }
}

function reservationPourClient($pdo, $id_utilisateur, $commentaireClient, $commentaireAdmin, $precision_type, $id_reservation) {
    $sql = "SELECT id, id_type, date FROM Reservations WHERE id = ?";    

    $req = $pdo->prepare($sql);
    $req->execute(array($id_reservation));
    $result = $req->fetchAll(PDO::FETCH_OBJ);
    $dateDomicile = $result[0]->date;

    if($result[0]->id_type == 1) {
        $id_type_precision = "id_type_massage";
    } else {
        $id_type_precision = "id_type_machine";
    }

    if(!empty($id_utilisateur)) {
        $id_utilisateur = $id_utilisateur;
    } else {
        $id_utilisateur = "64";
    }

    $sql = "UPDATE Reservations SET id_utilisateur = ?, $id_type_precision = ?, commentaireClient = ?, commentaireAdmins = ?, id_etat = 2 WHERE id = ?";
    $req = $pdo->prepare($sql);
    $req->execute(array($id_utilisateur, $precision_type, $commentaireClient, $commentaireAdmin, $id_reservation));
    $result = $req;

    // Si on est dans le cas réservation à domicile, on bloque aussi pour l'autre type de la même heure
    if($precision_type == 6){
        $sql = "SELECT id, id_type FROM Reservations WHERE id_etat = 1 AND date = ?";

        $req = $pdo->prepare($sql);
        $req->execute(array($dateDomicile));
        $results = $req->fetchAll(PDO::FETCH_OBJ);

        foreach($results as $result) {
            if($result->id_type == 1) {
                $id_type_precision = "id_type_massage";
            } else {
                $id_type_precision = "id_type_machine";
            }

            $sql = "UPDATE Reservations SET id_utilisateur = ?, $id_type_precision = 6, commentaireClient = ?, commentaireAdmins = ?, id_etat = 2 WHERE id = ?";
            $req = $pdo->prepare($sql);
            $req->execute(array($id_utilisateur, $commentaireClient, $commentaireAdmin, $result->id));
        } 
    }

    if($result) {
        return true;
    } else {
        return false;
    }
}

function ajax_horairesReservations($pdo, $joursReservationsID) {
    $sql = "SELECT Reservations.id AS id_reservation, Reservations.id_utilisateur AS id_uti_reservation, date AS date_reservation, type.libelle AS type
            FROM Reservations
            JOIN Reservation_type AS type ON Reservations.id_type = type.id
            WHERE DATE_FORMAT(date, '%Y-%m-%d') = ?
            ORDER BY date ASC, id_reservation ASC";    

    $req = $pdo->prepare($sql);
    $req->execute(array($joursReservationsID));
    $horairesReservations = $req->fetchAll(PDO::FETCH_OBJ);

    $affichage = "";
    $affichage .="<option value='' selected disabled>Visualiser les horaires</option>";

    foreach ($horairesReservations as $horaireReservation) {
        $heure = substr($horaireReservation->date_reservation, 11, 2);
        $minute = substr($horaireReservation->date_reservation, 14, 2);
        $date = $heure . "h" . $minute;

        $affichage .="<option disabled value='$horaireReservation->id_reservation'>" . $date .  " " . $horaireReservation->type . ($horaireReservation->id_uti_reservation == NULL ? '' : ' - Réservé') . "</option>";
    }
    echo $affichage;
}

function ajax_ReservationsASupprimer($pdo, $joursReservationsASupprimerID) {
    $sql = "SELECT Reservations.id AS id_reservation, Reservations.id_utilisateur AS id_uti_reservation, date AS date_reservation, type.libelle AS type
            FROM Reservations
            JOIN Reservation_type AS type ON Reservations.id_type = type.id
            WHERE DATE_FORMAT(date, '%Y-%m-%d') = ?
            ORDER BY date ASC, id_reservation ASC";    

    $req = $pdo->prepare($sql);
    $req->execute(array($joursReservationsASupprimerID));
    $horairesReservations = $req->fetchAll(PDO::FETCH_OBJ);

    $affichage = "";
    $affichage .="<option value='' selected disabled>Sélectionner un horaire</option>";

    foreach ($horairesReservations as $horaireReservation) {
        $heure = substr($horaireReservation->date_reservation, 11, 2);
        $minute = substr($horaireReservation->date_reservation, 14, 2);
        $date = $heure . "h" . $minute;

        $affichage .="<option " . ($horaireReservation->id_uti_reservation == NULL ? '' : 'disabled') . " value='$horaireReservation->id_reservation'>" . $date .  " " . $horaireReservation->type . ($horaireReservation->id_uti_reservation == NULL ? '' : ' - Réservé') . "</option>";
    }
    echo $affichage;
}

function ajax_horairesReservations2($pdo, $joursReservationsID) {
    $sql = "SELECT Reservations.id AS id_reservation, Reservations.id_utilisateur AS id_uti_reservation, date AS date_reservation, type.libelle AS type
            FROM Reservations
            JOIN Reservation_type AS type ON Reservations.id_type = type.id
            WHERE DATE_FORMAT(date, '%Y-%m-%d') = ?
            ORDER BY date ASC, id_reservation ASC";    

    $req = $pdo->prepare($sql);
    $req->execute(array($joursReservationsID));
    $horairesReservations = $req->fetchAll(PDO::FETCH_OBJ);

    $affichage = "";
    $affichage .="<option value='' selected disabled>Visualiser les horaires</option>";

    foreach ($horairesReservations as $horaireReservation) {
        $heure = substr($horaireReservation->date_reservation, 11, 2);
        $minute = substr($horaireReservation->date_reservation, 14, 2);
        $date = $heure . "h" . $minute;

        $affichage .="<option " . ($horaireReservation->id_uti_reservation == NULL ? '' : 'disabled') . " value='$horaireReservation->id_reservation'>" . $date .  " " . $horaireReservation->type . ($horaireReservation->id_uti_reservation == NULL ? '' : ' - Réservé') . "</option>";
    }
    echo $affichage;
}

function ajax_typesPrecision($pdo, $horairesReservations2ID) {
    $sql = "SELECT id, id_type FROM Reservations WHERE id = ?";    

    $req = $pdo->prepare($sql);
    $req->execute(array($horairesReservations2ID));
    $result = $req->fetchAll(PDO::FETCH_OBJ);

    if($result[0]->id_type == 1) {
        $sql = "SELECT id AS id_type, libelle
        FROM Types_massage"; 
    } else {
        $sql = "SELECT id AS id_type, libelle
        FROM Types_machine";  
    }

    $req = $pdo->prepare($sql);
    $req->execute();
    $typesPrecision = $req->fetchAll(PDO::FETCH_OBJ);

    $affichage = "";
    $affichage .="<option value='' selected disabled>Sélectionner un type</option>";

    foreach ($typesPrecision as $typePrecision) {
        if($typePrecision->id_type == 6) {
            $affichage .="<option value='$typePrecision->id_type'>" . $typePrecision->libelle . " (tous les rdv de l'heure sélectionnée seront bloqués)</option>";
        } else {
            $affichage .="<option value='$typePrecision->id_type'>" . $typePrecision->libelle . "</option>";
        }
    }
    echo $affichage;
}

function ajax_informationsClient0($pdo, $id_utilisateur_infosID) {
    $sql = "SELECT email
            FROM Utilisateurs
            WHERE id = ?";    

    $req = $pdo->prepare($sql);
    $req->execute(array($id_utilisateur_infosID));
    $informationsReservations = $req->fetchAll(PDO::FETCH_OBJ);

    $affichage = "";
    $affichage .= "Email : " . $informationsReservations[0]->email . " " . $informationsReservations[0]->telephone;

    echo $affichage;
}
function ajax_informationsClient1($pdo, $id_utilisateur_infosID) {
    $sql = "SELECT indicatif_tel, telephone
            FROM Utilisateurs
            WHERE id = ?";    

    $req = $pdo->prepare($sql);
    $req->execute(array($id_utilisateur_infosID));
    $informationsReservations = $req->fetchAll(PDO::FETCH_OBJ);

    $affichage = "";
    $affichage .= "Numéro de tél : " . $informationsReservations[0]->indicatif_tel . " " . $informationsReservations[0]->telephone;

    echo $affichage;
}
function ajax_informationsClient2($pdo, $id_utilisateur_infosID) {
    $sql = "SELECT DATE_FORMAT(date_inscription, '%d/%m/%Y') AS date_inscription
            FROM Utilisateurs
            WHERE id = ?";    

    $req = $pdo->prepare($sql);
    $req->execute(array($id_utilisateur_infosID));
    $informationsReservations = $req->fetchAll(PDO::FETCH_OBJ);

    $affichage = "";
    $affichage .= "Date d'inscription : " . $informationsReservations[0]->date_inscription;

    echo $affichage;
}
function ajax_informationsClient3($pdo, $id_utilisateur_infosID) {
    $sql = "SELECT DATE_FORMAT(date_derniere_co, '%d/%m/%Y') AS date_derniere_co
            FROM Utilisateurs
            WHERE id = ?";    

    $req = $pdo->prepare($sql);
    $req->execute(array($id_utilisateur_infosID));
    $informationsReservations = $req->fetchAll(PDO::FETCH_OBJ);

    $affichage = "";
    $affichage .= "Date de dernière connexion : " . $informationsReservations[0]->date_derniere_co;

    echo $affichage;
}

function ajax_reservationsClient($pdo, $id_utilisateur_resaID) {
    $sql = "SELECT Reservations.id AS id_reservation, date AS date_reservation, type_machine.libelle AS libelle_type_machine, type_massage.libelle AS libelle_type_massage
    FROM Reservations
    JOIN Utilisateurs ON Reservations.id_utilisateur = Utilisateurs.id
    LEFT JOIN Types_machine AS type_machine ON Reservations.id_type_machine = type_machine.id
    LEFT JOIN Types_massage AS type_massage ON Reservations.id_type_massage = type_massage.id
    WHERE Utilisateurs.id = ?
    ORDER BY date ASC, id_reservation ASC";    

    $req = $pdo->prepare($sql);
    $req->execute(array($id_utilisateur_resaID));
    $reservationsClient = $req->fetchAll(PDO::FETCH_OBJ);

    $affichage = "";
    $affichage .="<option value='' selected disabled>Visualiser les réservations</option>";

    foreach ($reservationsClient as $reservationClient) {
        $annee = substr($reservationClient->date_reservation, 0, 4);
        $mois = substr($reservationClient->date_reservation, 5, 2);
        $jour = substr($reservationClient->date_reservation, 8, 2);
        $heure = substr($reservationClient->date_reservation, 11, 2);
        $minute = substr($reservationClient->date_reservation, 14, 2);
        $dateNumerique = $jour . "/" . $mois . "/" . $annee;

        $timestamp = mktime(0, 0, 0, $mois, $jour, $annee);
        $jourSemaineNum = date('N', $timestamp);
        $jourSemaine = array("", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");
        $moisAnnee = array("", "janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");
        list($jourNum, $moisNum, $anneeNum) = explode("/", $dateNumerique);
        $moisNum = ($moisNum == in_array($moisNum, [10, 11, 12]) ? $moisNum : str_replace(0, "", $moisNum));
        $date = $jourSemaine[$jourSemaineNum] . " " . $jour . " " . $moisAnnee[$moisNum] . " " . $anneeNum . " à " .  $heure . "h" . $minute;

        $affichage .="<option " . ($annee . "-" . $mois . "-" . $jour >= date("Y-m-d") ? '' : 'disabled') . " value='$reservationClient->id_reservation'>" . $date .  " - " . (!empty($reservationClient->libelle_type_machine) ? $reservationClient->libelle_type_machine : $reservationClient->libelle_type_massage) . "</option>";
    }
    echo $affichage;
}
?>