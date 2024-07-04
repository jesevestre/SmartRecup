<?php
require("/home/xnsmari/www/contenu/connexion/BDDIntersection.php");
include("/home/xnsmari/www/service/serviceEnvoiEmail.php");
include("/home/xnsmari/www/modele/modeleCrontab.php");

// Crontab d'envoi d'email un jour avant le rendez-vous
$liste_reservations = modele_envoi_email_un_jour_avant($pdo);
foreach ($liste_reservations as $liste_reservation) {
    $id_reservation = $liste_reservation->id_reservation;
    $id_utilisateur = $liste_reservation->id_utilisateur;

    envoi_email_un_jour_avant($pdo, $id_utilisateur, $id_reservation);
}

// Crontab d'envoi d'email une semaine avant le rendez-vous
$liste_reservations = modele_envoi_email_une_semaine_avant($pdo);
foreach ($liste_reservations as $liste_reservation) {
    $id_reservation = $liste_reservation->id_reservation;
    $id_utilisateur = $liste_reservation->id_utilisateur;

    envoi_email_une_semaine_avant($pdo, $id_utilisateur, $id_reservation);
}
?>