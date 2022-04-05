<?php
    $title = "KnapSack";
    require('header.php');
    session_start();
    include('DB_Procedure.php');
    if(!empty($_GET['deconnecter'])){
        session_destroy();
        session_unset();
        setcookie("PHPSESSID",null,-1);
    }
    $estConnecter = FALSE;
    if(!empty($_SESSION['alias']))
        $estConnecter = TRUE;

    if(!empty($_GET["nbItem"]))
        AjouterItemPanier($_GET["idItem"],$_GET["nbItem"]);
        echo "<script>alert(test)</script>";
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
                            $poidsMax = $profile[4];
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
                <!-- <div style="text-align: center;">
                    <span>
                        <?= $_SESSION['alias'] ?>
                    </span>
                </div> -->
                <!-- <span style="font-size: small;"><i><?= $poidJoueur ?>/<?= $poidsMax ?> lb</i></span> -->
            </div>
            <div class="recherche" onclick="afficherRecherche()">
                <button>Recherche Avancée</button>
                <div class="contenuRecherche" id="RecherchePopUp"><h1>ALLOOOOOO</h1></div>
            </div>
            <div class="searchAndCart">
                <a href="panier.php" style="text-decoration: none;">
                    <button type="submit" aria-label="Panier" style="margin: 13px;"></button>
                </a>
                </span>
            </div>
        </header>
        <main class="item item2">
            <p>Informations</p>
            <h3>Number</h3>
            <input type="number" readonly aria-label="Alternative" value="6" style="width: 80px;">
            <h3>Checkbox</h3>
            <input type="checkbox" name="" id="">
        </main>
        <nav id="style-1" class="item3 minecraft-scrollbar">
            <section>
                <h2>Magasin</h2>
            </section>
            <section>
                <?php
                    $listeObjets = AfficherItemsVente('%');
                        foreach($listeObjets as $objet){
                            echo "<article class='test' id='$objet[0]' onclick='afficherMenuItem($objet[0])'> <img class='minetext' data-mctitle='$objet[1]&nbsp;$objet[5]lb' src='items_images/$objet[0].png'alt='Image de $objet[1]'>";
                            echo "<div class='testItem' id='itempPopUp$objet[0]'>";
                            echo "<form method='get'>";
                            echo "<button aria-label='Plus' type='button' onclick='AugmenterNbItemChoisie($objet[0])'></button>";
                            echo "<input type='number' value='1' aria-label='Alternative' readonly id='nbItemChoisie$objet[0]' style='width:80px' name='nbItem'>";
                            echo "<button aria-label='Minus' type='button' onclick='ReduireNbItemChoisie($objet[0])'></button>";
                            echo "<button aria-label='normal' type='submit'>Ajouter au panier</button>";
                            echo "<input type='hidden' name='idItem' value='$objet[0]'>";
                            echo "</form>";
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
                        for ($i = 1; $i < 100; $i++) {
                            echo "<article></article>";
                        }
                ?>
            </section>
        </nav>
<script defer> 
    function ReduireNbItemChoisie(idItem){
        var inputNbItemChoisie = document.getElementById("nbItemChoisie" + idItem);
        if(inputNbItemChoisie.value > 1)
            inputNbItemChoisie.value = inputNbItemChoisie.value-1;
    }
    function AugmenterNbItemChoisie(idItem){
        var inputNbItemChoisie = document.getElementById("nbItemChoisie" + idItem);
        var nbInput = parseInt(inputNbItemChoisie.value);
        inputNbItemChoisie.value = nbInput + 1;
    }
    function test(idItem){
        var nbItem = document.getElementById("nbItemChoisie" + idItem).value;
    }
</script>
<?php require('footer.php') ?>