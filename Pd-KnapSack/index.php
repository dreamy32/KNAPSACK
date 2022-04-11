<?php
$title = "KnapSack";
require('header.php');
session_start();
include('DB_Procedure.php');
if ($_GET['deconnecter'] == 'true') {
    session_destroy();
    session_unset();
    setcookie("PHPSESSID", null, -1);
}
$estConnecter = FALSE;
if (!empty($_SESSION['alias']))
    $estConnecter = TRUE;

if (!empty($_GET["nbItem"])) {
    AjouterItemPanier($_GET["idItem"], $_GET["nbItem"]);
    echo "<script>alert('Vous avez ajouter cette item a votre panier!')</script>";
}
?>

<body>
    <div id="minetip-tooltip">
        <span class="minetip-title" id="minetip-text">Minecraft Tip</span>
    </div>
    <div class="container">
        <header class="header">
            <div class="profileInfo fullWidth">
                <div class="menu" onclick="afficherMenu()">
                    <button aria-label="Profil"></button>
                    <a href="inventaire.php" style="text-decoration: none; display: inherit; margin-left: 10px;">
                        <button type="submit" aria-label="Chest"></button>
                    </a>

                    <div class="contenuMenu" id="MenuPopUp">
                        <?php
                        $profile = AfficherInfosJoueur($_SESSION['alias']);
                        $solde = $profile[1];
                        $poidJoueur = "50"; /* Valeur qui sera chercher en fonction php selon le poid de linventaire */
                        $poidsMax = $profile[3];
                        /* Affiche le boutton profile, solde, et se deconnecter */
                        if ($estConnecter) {
                            echo '<a href="profile.php" style="text-decoration: none;"><div class="advancedSearch" style="margin:5%"><p>Profile</p></div></a>';
                            echo '<a href="demande_Argent.php" style="text-decoration: none;"><div class="advancedSearch" style="margin:5%"> Solde: ' . $solde . ' <img style="width: 20px;" src="images/icons/ask_money.png" alt="caps"></a></div>';
                            echo '<a href="index.php?deconnecter=true" style="text-decoration: none;"><div class="advancedSearch" style="margin:5%"><p>Se Deconnecter</p></div></a>';
                        } else {
                            echo '<a href="login.php" style="text-decoration: none;"><div class="advancedSearch" style="margin:5%"><p>Se Connecter</p></div></a>';
                        }
                        ?>
                    </div>
                </div>
                <!--<span style="font-size: small;"><i><?= $poidJoueur ?>/<?= $poidsMax ?> lb</i></span>-->
            </div>

            <!-- RECHERCHE AVANCÉE DÉBUT -->

            <div class="recherche" onclick="afficherRecherche()">
                <button>Recherche Avancée</button>
                <div style="background-image: none;" class="contenuRecherche" id="RecherchePopUp">
                    <div aria-label="Window" style="margin: auto; width: 1000px; height: 700px;">
                        <div id="window-container">
                            <h1 id="window-title">Recherche</h1>
                            <div class="search-container">
                                <div id="order-types">

                                    <label><input type="checkbox" name="tri" value="poids" id="search-box1"> <span>Poids</span></label>

                                    <label><input type="checkbox" name="tri" value="prixUnitaire" id="search-box2"><span>Prix</span></label>

                                    <label><input type="checkbox" name="tri" value="type" id="search-box3"><span>Type</span></label>


                                </div>
                                Astuce : le tri par défaut est par type, poids et prix.
                                <div id="star-rating">

                                    <input class="rating rating--nojs" id="nbEtoiles" name="nbEtoiles" max="5" step="1" type="range" value="0">

                                    <br><br>

                                    <button name="ordre" id="order-button" value="false" style="font-size: 1.8em;">Croissant</button>
                                    <br><br>
                                    <button onclick="trier()" style="font-size: 1.8em;">Confirmer</button>

                                </div>
                                <div id="item-types">
                                    <div>
                                        <input aria-label="Item-Frame" type="checkbox" class="minetext" data-mctitle="Armes" value="W" name="type" id="armes">

                                        <input aria-label="Item-Frame" type="checkbox" class="minetext" data-mctitle="Armures" value="A" name="type" id="armures">

                                        <input aria-label="Item-Frame" type="checkbox" class="minetext" data-mctitle="Rénitialiser" name="type" id="reset">
                                    </div>
                                    <div>
                                        <input aria-label="Item-Frame" type="checkbox" class="minetext" data-mctitle="Nourriture" value="N" name="type" id="nourriture">

                                        <input aria-label="Item-Frame" type="checkbox" class="minetext" data-mctitle="Munitions" value="M" name="type" id="munitions">

                                        <input aria-label="Item-Frame" type="checkbox" class="minetext" data-mctitle="Médicaments" value="D" name="type" id="medicaments">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RECHERCHE AVANCÉE FIN -->

            <div class="searchAndCart">
                <a href="panier.php" style="text-decoration: none;">
                    <button type="submit" aria-label="Panier" style="margin: 13px;"></button>
                </a>
                </span>
            </div>
        </header>
        <main style="display: flex;flex-direction: column;justify-content: space-evenly;" class="item item2">
            <h1>Informations</h1>
            <h2 id="infoNom" value="">Nom</h2>
            <div aria-label="Item-Slot" style="width: 120px; height: 120px;">
                <img src="" alt="objet" id="infoImageItem" style="width: 100px;height:100px;">
            </div>
            <h3 id="infoPrixItem" value=""></h3>
            <input type="number" id="infoNbItem" readonly aria-label="Alternative" value="" style="width: 80px;">
            <h3 id="infoPoidsItem" value=""></h3>
            <p id="infoDescriptionItem" value="" style="text-align: center;"></p>
        </main>
        <nav id="style-1" class="item3 minecraft-scrollbar">
            <section>
                <h2>Magasin</h2>
            </section>
            <section>
                <?php

                if (!empty($_GET["tri"]) || !empty($_GET["nbEtoiles"]) || !empty($_GET["type"])) {
                    $listeObjets = AfficherItemsVenteTri($_GET['tri'], $_GET['nbEtoiles'], $_GET['type'], $_GET['ordre']);
                } else {
                    $listeObjets = AfficherItemsVente('%');
                }

                foreach ($listeObjets as $objet) {
                    $nomItem = str_replace("'", "-", $objet[1]);
                    $descriptionItem = str_replace("'", "-", $objet[6]);
                    $objet[1] = $nomItem;
                    $objet[6] = $descriptionItem;
                    $temp = json_encode($objet);
                    echo "<article class='test shop-item' id='$objet[0]' onclick='ChangerInformation($temp)'> <img class='minetext' data-mctitle='$objet[1]&nbsp;$objet[5]lb' src='items_images/$objet[0].png'alt='Image de $objet[1]'>";
                    echo "<div class='testItem' id='itempPopUp$objet[0]'>";
                    if ($estConnecter) {
                        echo "<form method='get'>";
                        echo "<button aria-label='Plus' type='button' onclick='AugmenterNbItemChoisie($objet[0])'></button>";
                        echo "<input type='number' value='1' aria-label='Alternative' readonly id='nbItemChoisie$objet[0]' style='width:80px' name='nbItem'>";
                        echo "<button aria-label='Minus' type='button' onclick='ReduireNbItemChoisie($objet[0])'></button>";
                        echo "<button aria-label='normal' type='submit'>Ajouter au panier</button>";
                        echo "<input type='hidden' name='idItem' value='$objet[0]'>";
                        echo "</form>";
                    } else
                        echo '<a href="login.php" style="text-decoration: none;"><div class="advancedSearch" style="margin:5%"><p>Se Connecter</p></div></a>';
                    echo "</div></article>";
                }
                /*
                        $objet[0] : id
                        $objet[1] : nom
                        $objet[2] : qte
                        $objet[3] : type
                        $objet[4] : prix
                        $objet[5] : poids
                        $objet[6] : description
                        $objet[7] : est en vente*/
                for ($i = 1; $i < 96; $i++) {
                    echo "<article></article>";
                }
                ?>
            </section>
        </nav>
        <script defer>
            function ReduireNbItemChoisie(idItem) {
                var inputNbItemChoisie = document.getElementById("nbItemChoisie" + idItem);
                if (inputNbItemChoisie.value > 1)
                    inputNbItemChoisie.value = inputNbItemChoisie.value - 1;
            }

            function AugmenterNbItemChoisie(idItem) {
                var inputNbItemChoisie = document.getElementById("nbItemChoisie" + idItem);
                var nbInput = parseInt(inputNbItemChoisie.value);
                inputNbItemChoisie.value = nbInput + 1;
            }

            function ChangerInformation(idItem) {
                var infoNomItem = document.getElementById("infoNom");
                infoNomItem.innerHTML = idItem[1]/*.toUpperCase()*/;
                var infoNbItem = document.getElementById("infoNbItem");
                infoNbItem.value = idItem[2];
                var infoImageItem = document.getElementById("infoImageItem");
                infoImageItem.src = "items_images/" + idItem[0] + ".png";
                var infoPrixItem = document.getElementById("infoPrixItem");
                infoPrixItem.innerHTML = "Prix: " + idItem[4] + "$";
                var infoPoidsItem = document.getElementById("infoPoidsItem");
                infoPoidsItem.innerHTML = "Poids: " + idItem[5] + "lb";
                var infoDescriptionItem = document.getElementById("infoDescriptionItem");
                infoDescriptionItem.innerHTML = idItem[6];
                afficherMenuItem(idItem[0]);
            }
            // ChangerInformation = (idItem) => {
            //     let infoNomItem = $("#infoNom");
            //     let infoNbItem = $("#infoNbItem");
            //     let infoImageItem = $("#infoImageItem");
            //     let infoPrixItem = $("#infoPrixItem");
            //     let infoPoidsItem = $("#infoPoidsItem");
            //     let infoDescriptionItem = $("#infoDescriptionItem");
            //     //
            //     infoNomItem.innerHTML = idItem[1].toUpperCase();
            //     infoNbItem.value = idItem[2];
            //     infoImageItem.src = `items_images/${idItem[0]}.png`;
            //     infoPrixItem.innerHTML = `Prix: ${idItem[4]} $`;
            //     infoPoidsItem.innerHTML = `Poids: ${idItem[5]} lb`;
            //     infoDescriptionItem.innerHTML = idItem[6];
            //     //
            //     afficherMenuItem(idItem[0]);
            // }
            //Ne pas supprimer, jquery ne fonctionne pas dans le html. Dans le futur on va séparer les scripts.
        </script>
        <?php require('footer.php') ?>