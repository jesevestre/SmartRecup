<?php
function editerProfil($pdo, $prenom, $nom, $telephone, $email) {
    $sql = "UPDATE Utilisateurs SET prenom = ?, nom = ?, telephone = ? WHERE email = ?";    
    $req = $pdo->prepare($sql);
    $req->execute(array($prenom, $nom, $telephone, $email));
    $result = $req;

    if($result) {
        return true;
    } else {
        return false;
    }
}

function reserverRendezvous($pdo, $id_utilisateur, $id_reservation, $id_type_massage, $id_type_machine) {

    if(!empty($id_type_massage)){
        $sql = "UPDATE Reservations SET id_utilisateur = ?, id_etat = 2, id_type_massage = ? WHERE id = ?";    
        $req = $pdo->prepare($sql);
        $req->execute(array($id_utilisateur, $id_type_massage, $id_reservation));
        $result = $req;

        if($result) {
            return true;
        } else {
            return false;
        }
    } else {
        $sql = "UPDATE Reservations SET id_utilisateur = ?, id_etat = 2, id_type_machine = ? WHERE id = ?";    
        $req = $pdo->prepare($sql);
        $req->execute(array($id_utilisateur, $id_type_machine, $id_reservation));
        $result = $req;

        if($result) {
            return true;
        } else {
            return false;
        }
    }
}

function retirezRendezvous($pdo, $id_reservation) {
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

function editCommentaire($pdo, $commentaireClient, $id_reservation) {
    $sql = "UPDATE Reservations SET commentaireClient = ? WHERE id = ?";    
    $req = $pdo->prepare($sql);
    $req->execute(array($commentaireClient, $id_reservation));
    $result = $req;

    if($result) {
        return true;
    } else {
        return false;
    }
}

function verificationMdpActuel($pdo, $id_utilisateur, $mdp) {
    $mdp = sha1($mdp);

    $sql = "SELECT * FROM Utilisateurs WHERE id = ? AND motdepasse = ?";
    $req = $pdo->prepare($sql);
	$req->execute(array($id_utilisateur, $mdp));
	$result = $req->rowCount();

    if($result == 1) {
        return true;
    } else {
        return false;
    }
}

function editerMdp($pdo, $id_utilisateur, $newMdp) {
    $newMdp = sha1($newMdp);

    $sql = "UPDATE Utilisateurs SET motdepasse = ?, mdp_temp = NULL WHERE id = ?";
    $req = $pdo->prepare($sql);
	$req->execute(array($newMdp, $id_utilisateur));
	$result = $req;

    if($result == 1) {
        return true;
    } else {
        return false;
    }
}

function ajax_joursMassage($pdo, $joursMassageID) {
    $sql = "SELECT id AS id_reservation, Reservations.id_utilisateur AS id_uti_reservation, date AS date_reservation
            FROM Reservations 
            WHERE id_type = 1 AND DATE_FORMAT(date, '%Y-%m-%d') = ?
            ORDER BY date ASC";    

    $req = $pdo->prepare($sql);
    $req->execute(array($joursMassageID));
    $horairesMassage = $req->fetchAll(PDO::FETCH_OBJ);

    $affichage = "";
    $affichage .="<option value='' selected disabled>Sélectionner un horaire</option>";

    foreach ($horairesMassage as $horaireMassage) {
        $heure = substr($horaireMassage->date_reservation, 11, 2);
        $minute = substr($horaireMassage->date_reservation, 14, 2);
        $date = $heure . "h" . $minute;

        $affichage .="<option " . ($horaireMassage->id_uti_reservation == NULL ? '' : 'disabled') . " value='$horaireMassage->id_reservation'>" . $date . ($horaireMassage->id_uti_reservation == NULL ? '' : ' - Réservé') . "</option>";
    }
    echo $affichage;
}

function ajax_joursMachine($pdo, $joursMachineID) {
    $sql = "SELECT id AS id_reservation, Reservations.id_utilisateur AS id_uti_reservation, date AS date_reservation
            FROM Reservations 
            WHERE id_type = 2 AND DATE_FORMAT(date, '%Y-%m-%d') = ?
            ORDER BY date ASC";    

    $req = $pdo->prepare($sql);
    $req->execute(array($joursMachineID));
    $horairesMachine = $req->fetchAll(PDO::FETCH_OBJ);

    $affichage = "";
    $affichage .="<option value='' selected disabled>Sélectionner un horaire</option>";

    foreach ($horairesMachine as $horaireMachine) {
        $heure = substr($horaireMachine->date_reservation, 11, 2);
        $minute = substr($horaireMachine->date_reservation, 14, 2);
        $date = $heure . "h" . $minute;

        $affichage .="<option " . ($horaireMachine->id_uti_reservation == NULL ? '' : 'disabled') . " value='$horaireMachine->id_reservation'>" . $date . ($horaireMachine->id_uti_reservation == NULL ? '' : ' - Réservé') . "</option>";
    }
    echo $affichage;
}
?>