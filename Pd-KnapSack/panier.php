<?php 
    $title = "Panier";
    require('header.php');
    include("DB_Procedure.php");
    session_start();

    $estConnecter = FALSE;
    if(!empty($_SESSION['alias']))
        $estConnecter = TRUE;

    if(!empty($_POST["typeaction"])) {
        $action = $_POST["typeaction"];
        $numitem = $_POST["numItem"];
        $qte = $_POST["qte"];
        $qteNum = 0;
        if ($action == 'SUBSTRACT') {
            $qteNum = ((int)$qte) - 1;           
        } else if ($action == 'ADD') {
            $qteNum = ((int)$qte) + 1;
        }
        ModifierItemPanier($qteNum, $numitem);  
    } 

    if(!empty($_POST["supprimer"]))
    {
        $action = $_POST["supprimer"];
        $numitem = $_POST["numItem"];
        $qteNum = 0;
        if ($action == 'TRUE') {
            SupprimerItemPanier($numitem);  
        }        
    }
  
    if(!empty($_POST["payer"]))
    {
        $action = $_POST["payer"];
        $alias = $_SESSION["alias"];
        $qteNum = 0;
        if ($action == 'TRUE') {
            PayerPanier($alias);
        }        
    }
 
    $poidsSac = PoidsSac($_SESSION['alias']);
    $poidsMax = PoidsMax($_SESSION['alias']);
    $totalPanier = MontantTotalPanier($_SESSION['idJoueur']);
    $dexterite = Dexterite($_SESSION['alias']);
    $tab = AfficherPanier($_SESSION['alias']);  

    $profile = AfficherInfosJoueur($_SESSION['alias']);
    $solde = $profile[1];
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
                            <form action="panier.php" method="post">
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

                        
                       
                        <br><br>
                    </div>                    
                    <div id="item-info">
                        <h1>&nbsp; Mes infos: </h1>
                        <div style="text-align: center;">
                            <span style="font-size: 25px;">
                                <span style="color:aquamarine">Poids du sac:</span>
                                <span class="red-alert"><?=$poidsSac?>/<?=$poidsMax?></span>
                            </span>
                            <br>
                            <br>
                            <span style="font-size: 25px;">
                                <span style="color:aquamarine">Dextérité: 
                                    <span><?=$dexterite?></span>
                                </span>
                            </span>
                            <br>
                            <br>
                            <span  style="font-size: 25px;">
                                <span style="color:aquamarine">Solde: 
                                    <span><?=$solde ?> <img style="width: 20px;" src="images/icons/ask_money.png" alt="caps"></span>
                                </span>
                            </span>
                            <br>
                            <br>
                            <br>
                            <br>
                            <span  style="font-size: 25px;">
                                <span style="color:aquamarine">Total du panier:
                                    <div style="display: inline-flex;">
                                        <span><?=$totalPanier?></span>
                                        <img style="width: 33px;" src="images/icons/ask_money.png" alt="caps">
                                    </div>
                                </span>
                            </span>
                        </div>
                


                        <form action="panier.php" method="post">
                           
                            <div style="text-align: center;">
                                <button type=submit onclick="this.form.payer.value='TRUE'">Payer</button>
                                <input type= hidden name="payer" value="">
                            </div>
                        </form>
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