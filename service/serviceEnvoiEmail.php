<?php
use PHPMailer\PHPMailer\PHPMailer;

require("/home/xnsmari/www/contenu/PHPMailer/src/PHPMailer.php");
require("/home/xnsmari/www/contenu/PHPMailer/src/SMTP.php");

function entete() 
{
    return "<html>
                <head>
                    <meta name='viewport' content='width=device-width'>
                    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
                    <title>Simple Transactional Email</title>
                    <style>
                        @media only screen and (max-width: 620px) {
                            table[class=body] h1 {
                            font-size: 28px !important;
                            margin-bottom: 10px !important;
                            }
                            table[class=body] p,
                                table[class=body] ul,
                                table[class=body] ol,
                                table[class=body] td,
                                table[class=body] span,
                                table[class=body] a {
                            font-size: 16px !important;
                            }
                            table[class=body] .wrapper,
                                table[class=body] .article {
                            padding: 10px !important;
                            }
                            table[class=body] .content {
                            padding: 0 !important;
                            }
                            table[class=body] .container {
                            padding: 0 !important;
                            width: 100% !important;
                            }
                            table[class=body] .main {
                                border-left-width: 0 !important;
                                border-radius: 0 !important;
                                border-right-width: 0 !important;
                            }
                            table[class=body] .btn table {
                                width: 100% !important;
                            }
                            table[class=body] .btn a {
                                width: 100% !important;
                            }
                            table[class=body] .img-responsive {
                                height: auto !important;
                                max-width: 100% !important;
                                width: auto !important;
                            }
                        }
        
                        @media all {
                            .ExternalClass {
                            width: 100%;
                            }
                            .ExternalClass,
                                .ExternalClass p,
                                .ExternalClass span,
                                .ExternalClass font,
                                .ExternalClass td,
                                .ExternalClass div {
                            line-height: 100%;
                            }
                            .apple-link a {
                                color: inherit !important;
                                font-family: inherit !important;
                                font-size: inherit !important;
                                font-weight: inherit !important;
                                line-height: inherit !important;
                                text-decoration: none !important;
                            }
                            .btn-primary table td:hover {
                                background-color: #34495e !important;
                            }
                            .btn-primary a:hover {
                                background-color: #34495e !important;
                                border-color: #34495e !important;
                            }
                        }
                    </style>
                    </head>

                    <body class='' style='background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;'>
                    <table border='0' cellpadding='0' cellspacing='0' class='body' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;'>
                        <tr>
                            <td style='font-family: sans-serif; font-size: 14px; vertical-align: top;'>&nbsp;</td>
                            <td class='container' style='font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 90%; padding: 30px; width: 90%; background-color: #ffffff;'>
                            <a href='https://smartrécup.fr'><img alt='Logo de SmartRécup' src='https://smartrécup.fr/contenu/images/Logo_smartrecup_blanc.png' width='200' style='margin: auto; margin-bottom: 20px;' /></a>
                            <div class='content' style='box-sizing: border-box; display: block; Margin: 0 auto; max-width: 90%; padding: 10px;'>
                                <table class='main' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;'>
                        <tr>
                        <td class='wrapper' style='font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;'>
                            <table border='0' cellpadding='0' cellspacing='0' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;'>
                        <tr>";
    }

function pied() 
{// test en cours pour margin-left: auto; margin-right: auto; sur la div pour centrer text-align: center; 
    return "</table>
                </td>
                    </tr>
                        </table>
                            <div style='clear: both; margin-top: 10px; width: 100%;'>
                                <table border='0' cellpadding='0' cellspacing='0' style='border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;'>
                                    <tr>
                                            <td class='content-block' style='font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;'>
                                            <span class='apple-link' style='color: #999999; font-size: 12px; text-align: center;'><a href='https://smartrécup.fr' style='text-decoration: underline; color: #999999; font-size: 12px; text-align: center;'>SmartRécup</a><br />
                                            5 Avenue de la Créativité<br />
                                            59 650 Villneuve d'Asq - France</span><br />
                                            <a href='tel:+330175430602' style='text-decoration: underline; color: #999999; font-size: 12px; text-align: center;'>07 86 54 28 37</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </td>
                    <td style='font-family: sans-serif; font-size: 14px; vertical-align: top;'>&nbsp;</td>
                </tr>
            </table>
        </body>
    </html>";
}

function envoyerEmail($entete, $message, $pied, $titre, $subject, $prenomUti, $nomUti, $emailUti){
    $message = $entete . $message . $pied;
    $email_origine = "info@smartrecup.fr";
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPSecure = "ssl";
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "smartrecup59@gmail.com";
    $mail->Password = "rgkrqryhszwulxqf";
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->setFrom($email_origine, $titre);
    $mail->addAddress($emailUti, $prenomUti . " " . $nomUti);
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->setLanguage("fr", "/optional/path/to/language/directory/");

    $mail->send();
}

function formatageDateHeureFr($dateHeure){
    $dateHeureFr = date("d/m/Y à H:i", strtotime($dateHeure));

    $jour = substr($dateHeureFr, 0, 2);
    $mois = substr($dateHeureFr, 3, 2);
    $annee = substr($dateHeureFr, 6, 4);
    $heure = substr($dateHeureFr, 14, 2);
    $minute = substr($dateHeureFr, 17, 2);
    $dateNumerique = substr($dateHeureFr, 0, 10);

    $timestamp = mktime(0, 0, 0, $mois, $jour, $annee);
    $jourSemaineNum = date('N', $timestamp);
    $jourSemaine = array("", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi", "dimanche");
    $moisAnnee = array("", "janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");
    list($jourNum, $moisNum, $anneeNum) = explode("/", $dateNumerique);

    $moisNum = ($moisNum == in_array($moisNum, [10, 11, 12]) ? $moisNum : str_replace(0, "", $moisNum));
    $dateHeureFrSortie = $jourSemaine[$jourSemaineNum] . " " . $jourNum . " " . $moisAnnee[$moisNum] . " " . $anneeNum . " à " . $heure . "h" . $minute;

    return $dateHeureFrSortie;
}

function envoi_email_inscription_client($prenomUti, $nomUti, $emailUti, $mdpTemp){
    $entete = entete();
    $pied = pied();

    $dateInscription = formatageDateHeureFr(date("Y-m-d H:i:s"));

    $titre = "SmartR" . chr(233) . "cup";
    $subject = "Confirmation de votre inscription";
    $message = "Bonjour " . $prenomUti . " " . $nomUti . "<br /><br />

                Votre inscription le <b>" . $dateInscription . "</b> a été réalisée.<br /><br />
                
                Votre identifiant et votre mot de passe sont :
                <ul>
                    <li>Identidiant : " . $emailUti . "</li>
                    <li>Mot de passe : " . $mdpTemp . "</li>
                </ul>

                Lors de votre première connexion en utilisant votre mot de passe temporaire, il vous sera demandé de le modifier.<br /><br />

                Vous pouvez à présent à tout moment effectuer une réservation dans votre espace personnel sur <a href='https://smartrécup.fr'>SmartRécup.fr</a> en cliquant sur le bouton \"Rendez-vous\", ou en cliquant <a href='https://smartrécup.fr/vue/connexion-inscription.php'>ici</a>.<br /><br />

                L'équipe de SmartRécup vous remercie pour votre confiance. Nous serons heureux de vous accueillir pour votre premier rendez-vous. Pour que la séance se déroule <b>dans les meilleures conditions possible</b>, nous vous invitons à prendre connaissance des <a href='https://smartrécup.fr/vue/info_rdv_faq.php'>informations importantes</a>.<br /><br /><br /><br />
    
                A bientôt,
    ";

    envoyerEmail($entete, $message, $pied, $titre, $subject, $prenomUti, $nomUti, $emailUti);    
}

function envoi_email_inscription_admins($prenomUti, $nomUti, $emailUti){
    $entete = entete();
    $pied = pied();

    $dateInscription = formatageDateHeureFr(date("Y-m-d H:i:s"));

    $titre = "SmartR" . chr(233) . "cup";
    $subject = "Nouvelle inscription r" . chr(233) . "alis" . chr(233) . "e";
    $message = "
                L'inscription de <b>" . $prenomUti . " " . $nomUti . "</b> d'adresse email " . $emailUti . " le <b>" . $dateInscription . "</b> a été réalisée.<br /><br />

                Vous pouvez à tout moment visualiser les personnes inscrites dans votre espace personnel sur <a href='https://smartrécup.fr'>SmartRécup.fr</a> en cliquant sur le bouton \"Rendez-vous\", ou en cliquant <a href='https://smartrécup.fr/vue/connexion-inscription.php'>ici</a> puis en allant sur le bouton \"Réservations et infos client\".<br /><br />
    
                Le service informatique,
    ";

    envoyerEmail($entete, $message, $pied, $titre, $subject, $prenomUti, $nomUti, "smartrecup59@gmail.com");    
}

function envoi_email_un_jour_avant($pdo, $id_utilisateur, $id_reservation){
    $entete = entete();
    $pied = pied();

    if($id_utilisateur == 64) {
        $sql = "SELECT commentaireAdmins
                FROM Reservations 
                WHERE id = ?";    
        $req = $pdo->prepare($sql);
        $req->execute(array($id_reservation));
        $utilisateur = $req->fetchAll(PDO::FETCH_OBJ);

        $emailUti = $utilisateur[0]->commentaireAdmins;
    } else {
        $sql = "SELECT prenom, nom, email
                FROM Utilisateurs 
                WHERE id = ?";    
        $req = $pdo->prepare($sql);
        $req->execute(array($id_utilisateur));
        $utilisateur = $req->fetchAll(PDO::FETCH_OBJ);

        $prenomUti = $utilisateur[0]->prenom;
        $nomUti = $utilisateur[0]->nom;
        $emailUti = $utilisateur[0]->email;
    }

    $sql = "SELECT Reservation_type.libelle AS libelle_type, type_machine.libelle AS libelle_machine, type_massage.libelle AS libelle_massage, date
            FROM Reservations
            JOIN Reservation_type ON Reservations.id_type = Reservation_type.id
            LEFT JOIN Types_machine AS type_machine ON Reservations.id_type_machine = type_machine.id
            LEFT JOIN Types_massage AS type_massage ON Reservations.id_type_massage = type_massage.id
            WHERE Reservations.id = ?";    
    $req = $pdo->prepare($sql);
	$req->execute(array($id_reservation));
	$reservation = $req->fetchAll(PDO::FETCH_OBJ);

    $dateReservation = formatageDateHeureFr($reservation[0]->date);

    $titre = "SmartR" . chr(233) . "cup";
    $subject = "Rappel de votre rendez-vous";
    $message = "Bonjour" . (!empty($prenomUti) ? " " . $prenomUti : ",") . " " . (!empty($nomUti) ? $nomUti : "") . "<br /><br />

                Votre réservation de " . (!empty($reservation[0]->libelle_machine) ? $reservation[0]->libelle_machine : $reservation[0]->libelle_massage) . " pour demain, soit le <b>" . $dateReservation . "</b> approche à grand pas.<br /><br />

                Vous pouvez à tout moment <b>consulter votre réservation</b> dans votre espace personnel sur <a href='https://smartrécup.fr'>SmartRécup.fr</a> en cliquant sur le bouton \"Rendez-vous\", ou en cliquant <a href='https://smartrécup.fr/vue/connexion-inscription.php'>ici</a>.<br /><br />
    
                L'équipe de SmartRécup vous remercie pour votre confiance. Nous serons heureux de vous accueillir pour votre premier rendez-vous. Pour que la séance se déroule <b>dans les meilleures conditions possible</b>, nous vous invitons à prendre connaissance des <a href='https://smartrécup.fr/vue/about.php'>informations importantes</a>.<br /><br /><br /><br />
    
                A bientôt,
    ";

    envoyerEmail($entete, $message, $pied, $titre, $subject, (!empty($prenomUti) ? $prenomUti : ""), (!empty($nomUti) ? $nomUti : ""), $emailUti);    
}

function envoi_email_une_semaine_avant($pdo, $id_utilisateur, $id_reservation){
    $entete = entete();
    $pied = pied();

    if($id_utilisateur == 64) {
        $sql = "SELECT commentaireAdmins
                FROM Reservations 
                WHERE id = ?";    
        $req = $pdo->prepare($sql);
        $req->execute(array($id_reservation));
        $utilisateur = $req->fetchAll(PDO::FETCH_OBJ);

        $emailUti = $utilisateur[0]->commentaireAdmins;
    } else {
        $sql = "SELECT prenom, nom, email
                FROM Utilisateurs 
                WHERE id = ?";    
        $req = $pdo->prepare($sql);
        $req->execute(array($id_utilisateur));
        $utilisateur = $req->fetchAll(PDO::FETCH_OBJ);

        $prenomUti = $utilisateur[0]->prenom;
        $nomUti = $utilisateur[0]->nom;
        $emailUti = $utilisateur[0]->email;
    }

    $sql = "SELECT Reservations.id AS id_reservation, Reservation_type.libelle AS reservation_libelle, type_machine.libelle AS libelle_machine, type_massage.libelle AS libelle_massage, date
            FROM Reservations
            JOIN Reservation_type ON Reservations.id_type = Reservation_type.id
            LEFT JOIN Types_machine AS type_machine ON Reservations.id_type_machine = type_machine.id
            LEFT JOIN Types_massage AS type_massage ON Reservations.id_type_massage = type_massage.id
            WHERE Reservations.id = ?";     
    $req = $pdo->prepare($sql);
	$req->execute(array($id_reservation));
	$reservation = $req->fetchAll(PDO::FETCH_OBJ);

    $dateReservation = formatageDateHeureFr($reservation[0]->date);

    $titre = "SmartR" . chr(233) . "cup";
    $subject = "Rappel de votre rendez-vous";
    $message = "Bonjour" . (!empty($prenomUti) ? " " . $prenomUti : ",") . " " . (!empty($nomUti) ? $nomUti : "") . "<br /><br />
      
                Votre réservation de " . (!empty($reservation[0]->libelle_machine) ? $reservation[0]->libelle_machine : $reservation[0]->libelle_massage) . " approche à grand pas. Elle est prévue pour dans une semaine, soit le <b>" . $dateReservation . "</b>.<br /><br />

                Vous pouvez à tout moment modifier votre réservation <b>jusqu'à 48 heures avant celle-ci</b> dans votre espace personnel sur <a href='https://smartrécup.fr'>SmartRécup.fr</a> en cliquant sur le bouton \"Rendez-vous\", ou en cliquant <a href='https://smartrécup.fr/vue/connexion-inscription.php'>ici</a>.<br /><br />
    
                L'équipe de SmartRécup vous remercie pour votre confiance. Nous serons heureux de vous accueillir pour votre premier rendez-vous. Pour que la séance se déroule <b>dans les meilleures conditions possible</b>, nous vous invitons à prendre connaissance des <a href='https://smartrécup.fr/vue/about.php'>informations importantes</a>.<br /><br /><br /><br />
    
                A bientôt,
    ";

    envoyerEmail($entete, $message, $pied, $titre, $subject, (!empty($prenomUti) ? $prenomUti : ""), (!empty($nomUti) ? $nomUti : ""), $emailUti);    
}

function envoi_email_confirmation_client($pdo, $id_utilisateur, $id_reservation){
    $entete = entete();
    $pied = pied();

    $sql = "SELECT prenom, nom, email
            FROM Utilisateurs 
            WHERE id = ?";    
    $req = $pdo->prepare($sql);
	$req->execute(array($id_utilisateur));
	$utilisateur = $req->fetchAll(PDO::FETCH_OBJ);

    $prenomUti = $utilisateur[0]->prenom;
    $nomUti = $utilisateur[0]->nom;
    $emailUti = $utilisateur[0]->email;

    $sql = "SELECT Reservations.id AS id_reservation, Reservation_type.libelle AS reservation_libelle, type_machine.libelle AS libelle_machine, type_massage.libelle AS libelle_massage, date
            FROM Reservations
            JOIN Reservation_type ON Reservations.id_type = Reservation_type.id
            LEFT JOIN Types_machine AS type_machine ON Reservations.id_type_machine = type_machine.id
            LEFT JOIN Types_massage AS type_massage ON Reservations.id_type_massage = type_massage.id
            WHERE Reservations.id = ?";
    $req = $pdo->prepare($sql);
	$req->execute(array($id_reservation));
	$reservation = $req->fetchAll(PDO::FETCH_OBJ);

    $dateReservation = formatageDateHeureFr($reservation[0]->date);

    $titre = "SmartR" . chr(233) . "cup";
    $subject = "Confirmation de votre r" . chr(233) . "servation";
    $message = "Bonjour " . $prenomUti . " " . $nomUti . "<br /><br />

                Votre réservation de " . (!empty($reservation[0]->libelle_machine) ? $reservation[0]->libelle_machine : $reservation[0]->libelle_massage) . " pour le <b>" . $dateReservation . "</b> a été prise en compte. N'hésitez pas à l'intègrer dans votre agenda afin de ne pas l'oublier.<br /><br />

                Vous pouvez à tout moment modifier votre réservation <b>jusqu'à 48 heures avant celle-ci</b> dans votre espace personnel sur <a href='https://smartrécup.fr'>SmartRécup.fr</a> en cliquant sur le bouton \"Rendez-vous\", ou en cliquant <a href='https://smartrécup.fr/vue/connexion-inscription.php'>ici</a>.<br /><br />
    
                L'équipe de SmartRécup vous remercie pour votre confiance. Nous serons heureux de vous accueillir pour votre premier rendez-vous. Pour que la séance se déroule <b>dans les meilleures conditions possible</b>, nous vous invitons à prendre connaissance des <a href='https://smartrécup.fr/vue/about.php'>informations importantes</a>.<br /><br /><br /><br />
    
                A bientôt,
    ";

    envoyerEmail($entete, $message, $pied, $titre, $subject, $prenomUti, $nomUti, $emailUti);    
}

function envoi_email_confirmation_admins($pdo, $id_utilisateur, $id_reservation){
    $entete = entete();
    $pied = pied();

    $sql = "SELECT prenom, nom
            FROM Utilisateurs 
            WHERE id = ?";    
    $req = $pdo->prepare($sql);
	$req->execute(array($id_utilisateur));
	$utilisateur = $req->fetchAll(PDO::FETCH_OBJ);

    $prenomUti = $utilisateur[0]->prenom;
    $nomUti = $utilisateur[0]->nom;

    $sql = "SELECT Reservations.id AS id_reservation, Reservation_type.libelle AS reservation_libelle, type_machine.libelle AS libelle_machine, type_massage.libelle AS libelle_massage, date
            FROM Reservations
            JOIN Reservation_type ON Reservations.id_type = Reservation_type.id
            LEFT JOIN Types_machine AS type_machine ON Reservations.id_type_machine = type_machine.id
            LEFT JOIN Types_massage AS type_massage ON Reservations.id_type_massage = type_massage.id
            WHERE Reservations.id = ?";
    $req = $pdo->prepare($sql);
	$req->execute(array($id_reservation));
	$reservation = $req->fetchAll(PDO::FETCH_OBJ);

    $dateReservation = formatageDateHeureFr($reservation[0]->date);

    $titre = "SmartR" . chr(233) . "cup";
    $subject = "Nouvelle r" . chr(233) . "servation le $dateReservation";
    $message = "
                Un rendez-vous de <b>" . (!empty($reservation[0]->libelle_machine) ? $reservation[0]->libelle_machine : $reservation[0]->libelle_massage) . "</b> pour le <b>" . $dateReservation . "</b> a été réservé par " . $prenomUti . " " . $nomUti .  ".<br /><br />
                
                Vous pouvez à tout moment visualiser les rendez-vous réservés dans votre espace personnel sur <a href='https://smartrécup.fr'>SmartRécup.fr</a> en cliquant sur le bouton \"Rendez-vous\", ou en cliquant <a href='https://smartrécup.fr/vue/connexion-inscription.php'>ici</a>.<br /><br />
    
                Le service informatique,
    ";

    envoyerEmail($entete, $message, $pied, $titre, $subject, $prenomUti, $nomUti, "smartrecup59@gmail.com");    
}

function envoi_email_annulation_client($pdo, $id_utilisateur, $id_reservation){
    $entete = entete();
    $pied = pied();

    $sql = "SELECT prenom, nom, email
            FROM Utilisateurs 
            WHERE id = ?";    
    $req = $pdo->prepare($sql);
	$req->execute(array($id_utilisateur));
	$utilisateur = $req->fetchAll(PDO::FETCH_OBJ);

    $prenomUti = $utilisateur[0]->prenom;
    $nomUti = $utilisateur[0]->nom;
    $emailUti = $utilisateur[0]->email;

    $sql = "SELECT Reservations.id AS id_reservation, Reservation_type.libelle AS reservation_libelle, type_machine.libelle AS libelle_machine, type_massage.libelle AS libelle_massage, date
            FROM Reservations
            JOIN Reservation_type ON Reservations.id_type = Reservation_type.id
            LEFT JOIN Types_machine AS type_machine ON Reservations.id_type_machine = type_machine.id
            LEFT JOIN Types_massage AS type_massage ON Reservations.id_type_massage = type_massage.id
            WHERE Reservations.id = ?";   
    $req = $pdo->prepare($sql);
	$req->execute(array($id_reservation));
	$reservation = $req->fetchAll(PDO::FETCH_OBJ);

    $dateReservation = formatageDateHeureFr($reservation[0]->date);

    $titre = "SmartR" . chr(233) . "cup";
    $subject = "Annulation de votre r" . chr(233) . "servation";
    $message = "Bonjour " . $prenomUti . " " . $nomUti . "<br /><br />

                Votre réservation de <b>" . (!empty($reservation[0]->libelle_machine) ? $reservation[0]->libelle_machine : $reservation[0]->libelle_massage) . "</b> pour le <b>" . $dateReservation . "</b> a été annulée.<br /><br />

                Vous pouvez à tout moment prendre une nouvelle réservation dans votre espace personnel sur <a href='https://smartrécup.fr'>SmartRécup.fr</a> en cliquant sur le bouton \"Rendez-vous\", ou en cliquant <a href='https://smartrécup.fr/vue/connexion-inscription.php'>ici</a> pour vous connecter à votre espace personnel.<br /><br /><br /><br />
    
                A bientôt,
    ";

    envoyerEmail($entete, $message, $pied, $titre, $subject, $prenomUti, $nomUti, $emailUti);    
}

function envoi_email_annulation_admins($pdo, $id_utilisateur, $id_reservation){
    $entete = entete();
    $pied = pied();

    $sql = "SELECT prenom, nom
            FROM Utilisateurs 
            WHERE id = ?";    
    $req = $pdo->prepare($sql);
	$req->execute(array($id_utilisateur));
	$utilisateur = $req->fetchAll(PDO::FETCH_OBJ);

    $prenomUti = $utilisateur[0]->prenom;
    $nomUti = $utilisateur[0]->nom;

    $sql = "SELECT Reservations.id AS id_reservation, Reservation_type.libelle AS reservation_libelle, type_machine.libelle AS libelle_machine, type_massage.libelle AS libelle_massage, date
            FROM Reservations
            JOIN Reservation_type ON Reservations.id_type = Reservation_type.id
            LEFT JOIN Types_machine AS type_machine ON Reservations.id_type_machine = type_machine.id
            LEFT JOIN Types_massage AS type_massage ON Reservations.id_type_massage = type_massage.id
            WHERE Reservations.id = ?";
    $req = $pdo->prepare($sql);
	$req->execute(array($id_reservation));
	$reservation = $req->fetchAll(PDO::FETCH_OBJ);

    $dateReservation = formatageDateHeureFr($reservation[0]->date);

    $titre = "SmartR" . chr(233) . "cup";
    $subject = "Annulation d'une r" . chr(233) . "servation";
    $message = "
                Un rendez-vous de <b>" . (!empty($reservation[0]->libelle_machine) ? $reservation[0]->libelle_machine : $reservation[0]->libelle_massage) . "</b> pour le <b>" . $dateReservation . "</b> a été annulée.<br /><br />
                
                Vous pouvez à tout moment visualiser les rendez-vous réservés dans votre espace personnel sur <a href='https://smartrécup.fr'>SmartRécup.fr</a> en cliquant sur le bouton \"Rendez-vous\", ou en cliquant <a href='https://smartrécup.fr/vue/connexion-inscription.php'>ici</a>.<br /><br />
    
                Le service informatique,
    ";

    envoyerEmail($entete, $message, $pied, $titre, $subject, $prenomUti, $nomUti, "smartrecup59@gmail.com");    
}

function envoi_email_contact($emailUti, $prenomUti, $nomUti, $titreUti, $messageUti){
    $entete = entete();
    $pied = pied();

    $titre = "SmartR" . chr(233) . "cup";
    $subject = "Message depuis le formulaire de contact";
    $message = "Titre du message : " . $titreUti . "<br /><br />

                Adresse email de réponse : " . $emailUti . "<br /><br />

                Message de l'utilisateur : <br /><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $messageUti . "<br /><br />
                
                <br /> Signature : " . $prenomUti . " " . $nomUti . "
    ";

    envoyerEmail($entete, $message, $pied, $titre, $subject, $prenomUti, $nomUti, "smartrecup59@gmail.com");
}

function envoi_email_mdp_oublie_client($pdo, $email, $mdp){
    $entete = entete();
    $pied = pied();

    $sql = "SELECT prenom, nom, email
            FROM Utilisateurs 
            WHERE email = ?";    
    $req = $pdo->prepare($sql);
	$req->execute(array($email));
	$utilisateur = $req->fetchAll(PDO::FETCH_OBJ);

    $prenomUti = $utilisateur[0]->prenom;
    $nomUti = $utilisateur[0]->nom;
    $emailUti = $utilisateur[0]->email;

    $dateReinitialise = formatageDateHeureFr(date("Y-m-d H:i:s"));

    $titre = "SmartR" . chr(233) . "cup";
    $subject = "Mot de passe oubli" . chr(233);
    $message = "Bonjour " . $prenomUti . " " . $nomUti . "<br /><br />

                Votre mot de passe a été correctement réinitialisé le <b>" . $dateReinitialise . "</b> .<br /><br />
                
                Votre identifiant et votre mot de passe sont :
                <ul>
                    <li>Identidiant : " . $emailUti . "</li>
                    <li>Mot de passe : " . $mdp . "</li>
                </ul>

                Lors de votre prochaine connexion en utilisant votre mot de passe temporaire, il vous sera demandé de le modifier.<br /><br />

                Nous vous invitons fortement à vous connecter à votre espace personnel dès maintenant sur <a href='https://smartrécup.fr'>SmartRécup.fr</a> en cliquant sur le bouton \"Rendez-vous\", ou en cliquant <a href='https://smartrécup.fr/vue/connexion-inscription.php'>ici</a> et à <b>changer le mot de passe</b> qui vous a été communiqué.<br /><br />

                A bientôt,
    ";

                envoyerEmail($entete, $message, $pied, $titre, $subject, $prenomUti, $nomUti, $emailUti);    
}

function envoi_email_nouveau_mdp_client($pdo, $id_utilisateur){
    $entete = entete();
    $pied = pied();

    $sql = "SELECT prenom, nom, email
            FROM Utilisateurs 
            WHERE id = ?";    
    $req = $pdo->prepare($sql);
	$req->execute(array($id_utilisateur));
	$utilisateur = $req->fetchAll(PDO::FETCH_OBJ);

    $prenomUti = $utilisateur[0]->prenom;
    $nomUti = $utilisateur[0]->nom;
    $emailUti = $utilisateur[0]->email;

    $dateReinitialise = formatageDateHeureFr(date("Y-m-d H:i:s"));

    $titre = "SmartR" . chr(233) . "cup";
    $subject = "Changement du mot de passe";
    $message = "Bonjour " . $prenomUti . " " . $nomUti . "<br /><br />

                Votre mot de passe a été correctement mis à jour le <b>" . $dateReinitialise . "</b> .<br /><br />
                Votre identifiant de connexion reste le même soit :
                    <ul>
                        <li>Identidiant : " . $emailUti . "</li>
                    </ul>

                Vous pouvez à tout moment effectuer une réservation dans votre espace personnel sur <a href='https://smartrécup.fr'>SmartRécup.fr</a> en cliquant sur le bouton \"Rendez-vous\", ou en cliquant <a href='https://smartrécup.fr/vue/connexion-inscription.php'>ici</a>.<br /><br />

                A bientôt,
    ";

                envoyerEmail($entete, $message, $pied, $titre, $subject, $prenomUti, $nomUti, $emailUti);    
}
?>