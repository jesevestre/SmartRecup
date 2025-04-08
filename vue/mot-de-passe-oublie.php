<?php include "entetedepage.php"; ?>
<?php require("../contenu/recaptcha/autoload.php"); ?>

<div class="row d-flex justify-content-center mt-5 pb-3">
    <div class="col-4 cadrage verticale_separation">
        <img class="logo-img" src="../contenu/images/Logo_smartrecup_noir_sans_fond2.png" alt="Image du logo de SmartRécup" />
    </div>

    <div class="col-8 cadrage">
        
        <div class="pt-3 mb-3">
            <h3>Mot de passe oublié</h3>
        </div>

        <!-- Gestion des messages et erreurs -->
            <?php
            // La div des messages à afficher est de base invisible
            $display = "display: none";
            if(isset($_GET["error"]) || isset($_GET["success"])) {
                $error = $_GET["error"];
                $success = $_GET["success"];
                $email = "";
                $email = $_GET["email"];
                // La div des messages à afficher est visible
                $display = "display: block";

                if($error == "error") {
                    $messageMdpOublie = "Le champs de l'adresse email doit être remplis.";
                    $color = "alert-warning";
                } else if($error == "error2") {
                    $messageMdpOublie = "Vous devez valider le reCaptcha.</i>";
                    $color = "alert-warning";
                } else if($error == "error3") {
                    $messageMdpOublie = "Cette adresse email n'existe pas.";
                    $color = "alert-warning";
                } else if($error == "error4") {
                    $messageMdpOublie = "Une erreur inconnue c'est produite, veuillez contacter le support technique à l'adresse <a href='mailto:contact@smartrécup.fr'>contact@smartrécup.fr</a>";
                    $color = "alert-warning";
                }
            }
        ?>
        <div id="divMessage" style="<?= $display ?>" class="alert <?= $color; ?> text-center small">
            <i class="fa fa-exclamation-triangle"></i> <?= $messageMdpOublie; ?>
        </div>
        <!-- Fin de gestion des messages et erreurs -->

        <form action="../controlleur/controlleurMdpOublie.php" method="post" id="MdpOublie">
            <div class="mb-3">
                <input type="email" name="email" id="email" class="form-control" placeholder="Adresse email de connexion" onBlur="text_plein()" <?php if($email != "") { ?> value="<?= $email; ?>" <?php } ?> required>
            </div>

            <div class="legendeCaptcha mb-4">
                <!-- Pour SmartRécup -->
                <!-- <div class="g-recaptcha" data-sitekey="6LcilIcpAAAAAJ1n-hprVbOwKm_jsuzFnendK_ui"></div> -->
                <!-- Pour SameSport -->
                <div class="g-recaptcha" data-sitekey="6Ldu28YpAAAAAH2tnroieIhssHx7aVEbuDqFpNGg"></div>
            </div>

            <div class="pt-4 d-grid d-flex col-12 mx-auto">
                <div class="col-6 d-grid mx-auto">
                    <a href="../" type="button" id="BtnRetour" class="btn btn-danger col-10 mx-auto"><i class="fa-sharp fa-solid fa-arrow-left"></i> Retour</i></a>
                </div>
                <div class="col-6 d-grid mx-auto">
                    <button type="submit" name="MdpOublie" id="BtnMdpOublie" class="btn btn-secondary col-10 mx-auto disabled" onClick="clickContact()">Retrouver <i class="fa-solid fa-unlock"></i></button>
                </div>
            </div>
        </form>

    </div>
</div>

<script src="../contenu/js/mot-de-passe-oublie.js"></script>

<!-- Validation du ReCaptcha pour l'inscription -->
<script src="https://www.google.com/recaptcha/api.js"></script>

<br />
<br />
<?php include "pieddepage.php"; ?>