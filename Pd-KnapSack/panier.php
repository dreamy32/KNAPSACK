<?php 
    $title = "Panier";
    require('header.php');
    include("DB_Procedure.php");
    session_start();

    $estConnecter = FALSE;
    if(!empty($_SESSION['alias']))
        $estConnecter = TRUE;



    if(!empty($_GET["typeaction"])) {
        $action = $_GET["typeaction"];
        $numitem = $_GET["numItem"];
        $qte = $_GET["qte"];
        $qteNum = 0;
        if ($action == 'SUBSTRACT') {
            $qteNum = ((int)$qte) - 1;           
        } else if ($action == 'ADD') {
            $qteNum = ((int)$qte) + 1;
        }
        ModifierItemPanier($qteNum, $numitem);  
    } 



    if(!empty($_GET["supprimer"]))
    {
        $action = $_GET["supprimer"];
        $numitem = $_GET["numItem"];
        $qteNum = 0;
        if ($action == 'TRUE') {
            SupprimerItemPanier($numitem);  
        }        
    }



  
    if(!empty($_GET["payer"]))
    {
        $action = $_GET["payer"];
        $alias = $_SESSION["alias"];
        $qteNum = 0;
        if ($action == 'TRUE') {
            PayerPanier($alias);
        }        
    }

  
    $poidsSac = PoidsSac($_SESSION['alias']);
    $poidsMax = PoidsMax($_SESSION['alias']);
    $tab = AfficherPanier($_SESSION['alias']);  

    echo $poidsSac;
    echo $poidsMax;


    $profile = AfficherInfosJoueur($_SESSION['alias']);
    $solde = $profile[1];
    //$poidJoueur = "50"; /* Valeur qui sera chercher en fonction php selon le poid de linventaire */
    $poidsMax = $profile[3];
/* Affiche le boutton profile, solde, et se deconnecter */
/*
    if ($estConnecter) {
        echo '<a href="demande_Argent.php" style="text-decoration: none;"><div class="advancedSearch" style="margin:5%"> Solde: ' . $solde . ' <img style="width: 20px;" src="images/icons/ask_money.png" alt="caps"></a></div>';
    }
    */
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>CSS Conception</title>
        <link rel="stylesheet" href="css/minecraft_elements.css">
        <link rel="stylesheet" href="css/range.css">
        <link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" href="css/toast.css">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="js/sound.js"></script>
        <script src="js/item-types_reset.js"></script>

        <style>
            #window-title {
                font-size: 5.5vh;
                margin-block-start: 3vh;
                padding-top: unset;
                height: 9vh;
            }

            body {
                background: url(images/options_background.png) 0 0 / 10%;
            }

            article {
                margin: 3vh 2vw;
                width: 9vh;
                height: 9vh;
            }

            article img {
                width: 6vh;
            }

            
        </style>
    </head>

    <body style="height: 95vh; margin: 3.3vh 5vw; margin-bottom: unset;">
    <a href="index.php"><img src="images/Knapsack.png"></img></a>
        <div id="d"></div>
        
        <div aria-label="Window" style="margin: auto; height: 98%; background: inherit;">
            <div id="window-container" style="margin-top: unset;">
                <h1 id="window-title">Panier</h1>
                <div class="cart-container">
                    <div id="items-list">

                        
                        <?php
                        foreach($tab as $objet){
                            ?>
                            <form action="panier.php" method="get">
                            <div class="item-holder">
                                <div aria-label="Item-Slot">                                    
                                    <img class="minetext" id="img_<?=$objet[2]?>" data-mctitle="Apple&nbsp;5lb"
                                        src='items_images/<?=$objet[0]?>.png'
                                        alt='Image de <?=$objet[2] ?>'
                                    >                               
                                </div>
                                <span><?=$objet[2] ?></span>
                                <div class="input-number">
                                    <button type=submit aria-label="Minus" onclick="this.form.typeaction.value='SUBSTRACT'"></button>
                                    <input type=hidden name="numItem" value="<?=$objet[0]?>">
                                    <input type=hidden name="typeaction" value="">
                                    <input readonly aria-label="Alternative" type="number" name="qte" id="qte" style="width: 85px; height: 100px; font-size: xx-large;" value="<?=$objet[1] ?>">
                                    <button type=submit aria-label="Plus" onclick="this.form.typeaction.value='ADD'"></button>
                                </div>
                                <span>
                                    <button type=submit onclick="this.form.supprimer.value='TRUE'">X</button>
                                    <input type= hidden name="supprimer" value="">
                                </span>
                            </div>
                            </form>
                            <?php
                        }       
                        ?>

                        <br>
                        <br>
                        <br>

                        
                        <form action="panier.php" method="get">
                            <div>
                                 Solde: <?=$solde ?> <img style="width: 20px;" src="images/icons/ask_money.png" alt="caps">
                            </div>
                            <div>
                                <button type=submit onclick="this.form.payer.value='TRUE'">Payer</button>
                                <input type= hidden name="payer" value="">
                            </div>
                        </form>
                        <br><br>
                    </div>                    
                    <div id="item-info">
                        <h1>Total</h1>
                        <div style="text-align: center;">
                            <span style="font-size: 25px;">
                                <span style="color:aquamarine">Poids du sac:</span>
                                <span class="red-alert"><?=$poidsSac?>/<?=$poidsMax?></span>
                            </span>
                            <br>
                            <br>
                            <span style="font-size: 25px;">
                                <span style="color:aquamarine">Dextérité: 
                                    <span>25<sup class="red-alert">-1</sup></span>
                                </span>
                            </span>
                            <br>
                            <br>
                            <span>Prix</span>
                            <div style="display: inline-flex;">
                                <span>4'500</span>
                                <img style="width: 33px;" src="./images/emerald.png">
                            </div>
                        </div>
                        <button type="submit">Acheter</button>
                        <div style="text-align: center;">
                            <h3>Solde<br>après-achat:</h3>
                            <br>
                            <div style="display: inline-flex;">
                                <span><div>Solde: <?=$solde ?> <img style="width: 20px;" src="images/icons/ask_money.png" alt="caps"></span>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

<?php require('footer.php') ?>


<!--
<script type="text/javascript">
            function DeleteItem()
            {
                alert("joli");
            }

            alert("poli");
        </script>
-->