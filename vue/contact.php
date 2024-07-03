<?php include "entetedepage.php"; ?>
<?php require("../contenu/recaptcha/autoload.php"); ?>

<div class="row d-flex justify-content-center mt-5 pb-3">
    <div class="col-4 cadrage verticale_separation">
        <img class="logo-img" src="../contenu/images/Logo_smartrecup_noir_sans_fond2.png" alt="Image du logo de SmartRécup" />
    </div>

    <div class="col-8 cadrage">
        
        <div class="pt-3 mb-3">
            <h3>Contact</h3>
        </div>

        <!-- Gestion des messages et erreurs -->
            <?php
            // La div des messages à afficher est de base invisible
            $display = "display: none";
            if(isset($_GET["errorContact"]) || isset($_GET["successContact"])) {
                $error = $_GET["errorContact"];
                $success = $_GET["successContact"];
                $email = "";
                $email = $_GET["email"];
                $prenom = "";
                $prenom = $_GET["prenom"];
                $nom = "";
                $nom = $_GET["nom"];
                $titre = "";
                $titre = $_GET['titre'];
                $message = "";
	            $message = $_GET['message'];
                $message = urlencode($message);
                // La div des messages à afficher est visible
                $display = "display: block";

                if($error == "error") {
                    $messageContact = "Tous les champs doivent être remplis.";
                    $color = "alert-warning";
                } else if($error == "error2") {
                    $messageContact = "Une erreur inconnue c'est produite, veuillez contacter le support technique à l'adresse <a href='mailto:contact@smartrécup.fr'>contact@smartrécup.fr</a>";
                    $color = "alert-warning";
                } else if($error == "error3") {
                    $messageContact = "Vous devez valider le reCaptcha.</i>";
                    $color = "alert-warning";
                } else if($success == "success") {
                    $messageContact = "<b>Votre message a bien été envoyer</b>. L'équipe vous répondra dans les plus brefs délais.";
                    $color = "alert-success";
                }
            }
        ?>
        <div id="divMessage" style="<?= $display ?>" class="alert <?= $color; ?> text-center small">
            <i class="fa fa-exclamation-triangle"></i> <?= $messageContact; ?>
        </div>
        <!-- Fin de gestion des messages et erreurs -->

        <form action="../controlleur/controlleurContact.php" method="post" id="contact">
            <div class="mb-3">
                <input type="email" name="email" id="email" class="form-control" placeholder="Votre adresse email pour vous répondre" <?php if($email != "") { ?> value="<?= $email; ?>" <?php } ?> required>
            </div>
            
            <div class="row mb-3">
                <div class="col-6 mx-auto">
                    <input type="text" name="prenom" id="prenom" class="form-control" placeholder="Votre prénom" onChange="this.value=premierCaractereMaj(this.value);" <?php if($prenom != "") { ?> value="<?= $prenom; ?>" <?php } ?> required>
                </div>
                <div class="col-6 mx-auto">
                    <input type="text" name="nom" id="nom" class="form-control" placeholder="Votre nom" onkeyup="this.value=this.value.toUpperCase()" <?php if($nom != "") { ?> value="<?= $nom; ?>" <?php } ?> required>
                </div>
            </div>

            <div class="mb-3">
                <input type="text" name="titre" id="titre" class="form-control" placeholder="Titre du message" <?php if($titre != "") { ?> value="<?= $titre; ?>" <?php } ?> required>
            </div>

            <div class="mb-3">
                <textarea name="message" id="message" class="form-control" rows="8" placeholder="Votre message" onBlur="text_plein()" required><?php if(isset($message) && $message != "") { echo htmlspecialchars($message); } ?></textarea>
            </div>

            <div class="legendeCaptcha mb-4">
                <div class="g-recaptcha" data-sitekey="6LcilIcpAAAAAJ1n-hprVbOwKm_jsuzFnendK_ui"></div>
            </div>

            <div class="pt-4 d-grid d-flex col-12 mx-auto">
                <div class="col-6 d-grid mx-auto">
                    <a href="../" type="button" id="BtnRetour" class="btn btn-danger col-10 mx-auto"><i class="fa-sharp fa-solid fa-arrow-left"></i> Retour</i></a>
                </div>
                <div class="col-6 d-grid mx-auto">
                    <button type="submit" name="contact" id="BtnContact" class="btn btn-secondary col-10 mx-auto disabled" onClick="clickContact()">Envoyer <i class="fa-regular fa-paper-plane"></i></button>
                </div>
            </div>
        </form>

    </div>
</div>

<script src="../contenu/js/contact.js"></script>

<!-- Validation du ReCaptcha pour l'inscription -->
<script src="https://www.google.com/recaptcha/api.js"></script>

<br />
<br />
<br />
<?php include "pieddepage.php"; ?>