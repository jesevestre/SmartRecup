<?php include "entetedepage.php"; ?>

<style>
body {
    background: url('../contenu/images/paysage.jpg');
}
</style>

<div class="row d-flex justify-content-center cadrage1">
    <div class="col-12 col-md-8 col-lg-8 col-xl-6 cadrage2">
        
        <div class="pt-3 mb-5">
            <h1>CONNEXION</h1>
        </div>

        <!-- Gestion des messages et erreurs -->
            <?php
            // La div des messages à afficher est de base invisible
            $display = "display: none";
            if (isset($_GET["error"]) || isset($_GET["success"])) {
                $error = $_GET["error"];
                $success = $_GET["success"];
                $email = "";
                $email = $_GET["email"];
                // La div des messages à afficher est visible
                $display = "display: block";

                if ($error == "error") {
                    $message = "Votre Email ou votre mot de passe sont incorrects.";
                    $color = "alert-warning";
                } else if ($error == "error2") {
                    $message = "Assurez-vous bien que votre Email et votre mot de passe soient renseignés.";
                    $color = "alert-warning";
                } else if ($error == "error3") {
                    $message = "Une erreur inconnue c'est produite, veuillez contacter le support technique à l'adresse <i><b>sevestre.jb@gmail.com</b></i>";
                    $color = "alert-warning";
                } else if ($success == "success") {
                    $message = "L'inscription à bien été prise en compte, vous pouvez à présent vous connecter.";
                    $color = "alert-success";
                }
            }
        ?>
        <div id="divMessage" style="<?= $display ?>" class="alert <?= $color; ?> text-center small">
            <i class="fa fa-exclamation-triangle"></i> <?= $message; ?>
        </div>
        <!-- Fin de gestion des messages et erreurs -->

        <form action="../controlleur/controlleurConnexion.php" method="post" id="connexion">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Adresse mail" <?php if ($email != "") { ?> value="<?= $email; ?>" <?php } ?> required>
            </div>

            <div class="mb-6">
                <input type="password" name="mdp" class="form-control" placeholder="Mot de passe" required>
            </div>

            <div class="pt-5 mb-3 d-grid d-flex col-12 mx-auto">
                <div class="col-6 d-grid mx-auto">
                    <a href="../index.php" type="button" class="btn btn-primary col-10 mx-auto"><i class="fa-sharp fa-solid fa-arrow-left"></i> Retour accueil</a>
                </div>
                <div class="col-6 d-grid mx-auto">
                    <button type="submit" name="connexion" class="btn btn-success col-10 mx-auto">Se connecter <i class="fa-sharp fa-solid fa-arrow-right"></i></button>
                </div>
                
            </div>
        </form>

    </div>
</div>
    
<?php include "pieddepage.php"; ?>