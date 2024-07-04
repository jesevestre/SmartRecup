<?php include "entetedepage.php"; ?>

<div class="row d-flex justify-content-center mt-5 pb-5">
    <div class="col-12 cadrage">
        
        <div class="mb-4">
            <h3>Les tarifs</h3>
        </div>

        <table class="table table-striped mb-5">
            <thead class="text-white" style="background-color: rgba(92, 92, 37, 0.7)">
                <tr>
                    <th scope="col" colspan="2">Pack d'engagement de 3 mois (peut évoluer en fonction des besoins)</th>
                    <th scope="col" class="text-center">Prix</th>
                    <th scope="col" class="text-center">Description</th>
                </tr>
            </thead>
            <tbody class="table table-striped table-secondary">
                <tr>
                    <th scope="row">Pack&nbsp;1</th>
                    <td>Tous les <b class="retirer_espace_balise_b">massages aux choix</b> + pressothérapie ou cryothérapie compressive</td>
                    <td class="text-center">80€</td>
                    <td class="text-center"><b class="retirer_espace_balise_b">2 séances</b> d'1h</td>
                </tr>
                <tr>
                    <th scope="row">Pack&nbsp;2</th>
                    <td>Tous les <b class="retirer_espace_balise_b">massages aux choix</b></td>
                    <td class="text-center">120€</td>
                    <td class="text-center"><b class="retirer_espace_balise_b">3 séances</b> d'1h</td>
                </tr>
                <tr>
                    <th scope="row">Pack&nbsp;3</th>
                    <td>Tous les <b class="retirer_espace_balise_b">massages aux choix</b> + pressothérapie ou cryothérapie compressive</td>
                    <td class="text-center">150€</td>
                    <td class="text-center"><b class="retirer_espace_balise_b">4 séances</b> d'1h</td>
                </tr>
            </tbody>
        </table>

        <table class="table table-striped mb-5">
            <thead class="text-white" style="background-color: rgba(33, 39, 93, 0.7) !important;">
                <tr>
                    <th scope="col" colspan="2">Les prestations</th>
                    <th scope="col" class="text-center">30&nbsp;min</th>
                    <th scope="col" class="text-center">60&nbsp;min</th>
                </tr>
            </thead>
            <tbody class="table table-striped table-secondary">
                <tr>
                    <th scope="row">➞</th>
                    <td><a href="massage-suedois.php" style="color: #222;">Massage suédois</a></td>
                    <td class="text-center">35€</td>
                    <td class="text-center">60€</td>
                </tr>
                <tr>
                    <th scope="row">➞</th>
                    <td><a href="massage-sportif.php" style="color: #222;">Massage sportif</a></td>
                    <td class="text-center">35€</td>
                    <td class="text-center">60€</td>
                </tr>
                <tr>
                    <th scope="row">➞</th>
                    <td><a href="massage-decontracturant.php" style="color: #222;">Massage deep tissue</a></td>
                    <td class="text-center">35€</td>
                    <td class="text-center">60€</td>
                </tr>
                <tr>
                    <th scope="row">➞</th>
                    <td><a href="massage-ventouses.php" style="color: #222;"><b>Massage ventouses / scrapping</b></a></td>
                    <td class="text-center"><b>30€</b></td>
                    <td class="text-center">----</td>
                </tr>
                <tr>
                    <th scope="row">➞</th>
                    <td><a href="pressotherapie.php" style="color: #222;">Pressothérapie</a></td>
                    <td class="text-center">30€</td>
                    <td class="text-center">----</td>
                </tr>
                <tr>
                    <th scope="row">➞</th>
                    <td><a href="cryotherapie-compressive.php" style="color: #222;">Cryothérapie compressive</a></td>
                    <td class="text-center">30€</td>
                    <td class="text-center">----</td>
                </tr>
            </tbody>
        </table>

        <div class="mt-5 mb-4">
            <h3>Méthode de paiement</h3>
        </div>

        <div class="row">
            <div class="col-md-6">
                <p class="justifie">Merci de prévenir <b>48 heures à l'avance</b> en cas de désistement ou en se désinscrivant sur <a href="connexion-inscription.php" target="_blank">l'application de réservation</a>. Toute réservation non annulée au moins 48 heures avant le rendez-vous <b>est concidérée comme dû</b>.</p>
            </div>

            <div class="col-md-6"> 
                <p class="justifie">Veuillez noter que tous les paiements doivent être effectués sur place au centre. Nous acceptons les paiements par <b>carte VISA</b> ainsi qu'en <b>espèces</b>. Nous n'acceptons pas les paiements par chèque. <i>Nous vous prions donc de vous assurer d'avoir une carte de paiement valide ou suffisamment de liquide lors de votre visite.</i></p>
            </div>
        </div>

        <div class="pt-4 mb-3 d-grid d-flex col-12 mx-auto">
            <div class="col-6 d-grid mx-auto">
                <a href="../" type="button" id="btnRetour" class="btn btn-danger col-10 mx-auto"><i class="fa-sharp fa-solid fa-arrow-left"></i> Retour</a>
            </div>
            <div class="col-6 d-grid mx-auto">
                <a href="connexion-inscription.php" type="button" id="btnInscription" class="btn btn-secondary col-10 mx-auto">Rendez-vous <i class="fa-sharp fa-solid fa-arrow-right"></i></a>
            </div>
        </div>

    </div>
</div>

<script src="../contenu/js/prestations.js"></script>

<?php include "pieddepage.php"; ?>