<?php
    $title = "KnapSack";
    require('header.php');
    session_start();
    if(TRUE){
        session_destroy();
        session_unset();
        setcookie("PHPSESSID",null,-1);
    }
    $estConnecter = FALSE;
    if(!empty($_SESSION['alias']))
        $estConnecter = TRUE;
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
                            include('DB_Procedure.php');
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
            <button>Recherche Avancée</button>
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
                /* En attente de l'implementation des stored procedure 
                        include('DB_Procedure.php');
                        $listeObjets = chercherToutObjetVente();
                        foreach($listeObjets as $objet){
                            echo "<article class="test" onclick="afficherMenuItem($objet[2])"> <img class="minetext" data-mctitle="$objet[0]&nbsp;$objet[1]" src="images_Objet/$objet[0]$objet[2]"alt="Image de $objet[0]"></article>";
                        }
                        $objet[0] : nom de l'objet
                        $objet[1] : Poids de l'objet
                        $objet[2] : ID de l'objet
                    */
                ?>
                <article class="test" onclick="afficherMenuItem(1)" id="1">
                    <img class="minetext" data-mctitle="Apple&nbsp;5lb" src="https://www.pikpng.com/pngl/b/560-5600564_minecraft-green-apple-texture-clipart.png" alt="Image de apple">
                </article>
                <article class="test" onclick="afficherMenuItem(2)" id="2">
                    <img class="minetext" data-mctitle="Apple&nbsp;5lb" src="https://www.pikpng.com/pngl/b/560-5600564_minecraft-green-apple-texture-clipart.png" alt="Image de apple">
                </article>
                <article class="test" onclick="afficherMenuItem(3)" id="3">
                    <img class="minetext" data-mctitle="Apple&nbsp;5lb" src="https://www.pikpng.com/pngl/b/560-5600564_minecraft-green-apple-texture-clipart.png" alt="Image de apple">
                </article>
                <article class="test" onclick="afficherMenuItem(4)" id="4">
                    <img class="minetext" data-mctitle="Apple&nbsp;5lb" src="https://www.pikpng.com/pngl/b/560-5600564_minecraft-green-apple-texture-clipart.png" alt="Image de apple">
                </article>
                <article class="test" onclick="afficherMenuItem(5)" id="5">
                    <img class="minetext" data-mctitle="Apple&nbsp;5lb" src="https://www.pikpng.com/pngl/b/560-5600564_minecraft-green-apple-texture-clipart.png" alt="Image de apple">
                </article>
                <article class="test" onclick="afficherMenuItem(6)" id="6">
                    <img class="minetext" data-mctitle="Apple&nbsp;5lb" src="https://www.pikpng.com/pngl/b/560-5600564_minecraft-green-apple-texture-clipart.png" alt="Image de apple">
                </article>
                <?php for ($cpt; $cpt < 200; $cpt++) {
                    echo '<article></article>';
                } ?>
            </section>
        </nav>
        <?php require('footer.php') ?>