<?php 
    $title = "Panier";
    include("DB_Procedure.php");
    session_start();

    echo ("lol");
  
    $messageErreur="";
    $estConnecter = FALSE;
    if(!empty($_SESSION['alias']))
    {
        $estConnecter = TRUE;
    }
    else
    {
        header("Location: login.php");
    }

    if(!empty($_POST["typeaction"])) {
        $action = $_POST["typeaction"];
        $numitem = $_POST["numItem"];
        $qte = $_POST["qte"];
        $qteNum = 0;
        
        if ($action == 'SUBSTRACT') {
            if((int)$qte > 1)
                $qteNum = ((int)$qte) - 1;           
        } else if ($action == 'ADD') {
            $qteNum = ((int)$qte) + 1;
        }

        if ($qteNum>=0) {
            try{
                ModifierItemPanier($qteNum, $numitem); 
            }
            catch (Exception $e) {
                $messageErreur=$e->getMessage();
            } 
        }
    } 

    if(!empty($_POST["supprimer"]))
    {
        $action = $_POST["supprimer"];
        $numitem = $_POST["numItem"];
        $qteNum = 0;
        if ($action == 'TRUE') {
            try{
                SupprimerItemPanier($numitem);  
               
            } catch (Exception $e) {
                $messageErreur=$e->getMessage();
            }         
        }        
    }
  
    if(!empty($_POST["payer"]))
    {
        $action = $_POST["payer"];
        $alias = $_SESSION["alias"];
        $qteNum = 0;
        if ($action == 'TRUE') {
            try{
                PayerPanier($alias);
            } catch (Exception $e) {
                $messageErreur=$e->getMessage();
            }
        }
    }
 

    echo "allo0000";
    try{
        echo "allo";
        $poidsSac = PoidsSac($_SESSION['alias']);
        $poidsPanier = PoidsPanier($_SESSION['alias']);
        $poidsMax = PoidsMax($_SESSION['alias']);
        $totalPanier = MontantTotalPanier($_SESSION['idJoueur']);
        $dexterite = Dexterite($_SESSION['alias']);
        $tab = AfficherPanier($_SESSION['alias']);  
        $profile = AfficherInfosJoueur($_SESSION['alias']);
    } catch (Exception $e) {
        $messageErreur=$e->getMessage();
    }
   
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

            .titleInfos{
                color: #112F5A;
            }
            
        </style>
    </head>

    <body style="height: 95vh; margin: 3.3vh 5vw; margin-bottom: unset;">
    <a href="index.php"><img src="images/Knapsack.png"></img></a>
        <div style="color:red" id="d"><?php echo $messageErreur?></div>
        
        <div aria-label="Window" style="margin: auto; height: 98%; background: inherit;">
            <div id="window-container" style="margin-top: unset;">
                <h1 id="window-title">Panier</h1>
                <div class="cart-container">
                    <div id="items-list">
                      
                        <?php
                        foreach($tab as $objet){
                            ?>
                            <form action="panier.php" method="post">
                            <div class="item-holder" style="width:100%; float:left;">
                                <div aria-label="Item-Slot" style="float:left;">                                    
                                    <img class="minetext" id="img_<?=$objet[2]?>" data-mctitle="Apple&nbsp;5lb"
                                        src='items_images/<?=$objet[0]?>.png'
                                        alt='Image de <?=$objet[2] ?>'
                                    >                               
                                </div>
                                <span><?=$objet[2] ?></span>
                                <div class="input-number" style="float:right;">
                                    <button type=submit aria-label="Minus" onclick="this.form.typeaction.value='SUBSTRACT'"></button>
                                    <input type=hidden name="numItem" value="<?=$objet[0]?>">
                                    <input type=hidden name="typeaction" value="">
                                    <input readonly aria-label="Alternative" type="number" name="qte" id="qte" style="width: 85px; height: 100px; font-size: xx-large;" value="<?=$objet[1] ?>">
                                    &nbsp;&nbsp;&nbsp;<button type=submit aria-label="Plus" onclick="this.form.typeaction.value='ADD'"></button>

                                    <span>&nbsp;&nbsp;&nbsp;
                                        <button type=submit onclick="this.form.supprimer.value='TRUE'">X</button>
                                        <input type= hidden name="supprimer" value="">
                                     </span>
                                </div>
                                
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
                        <h1>Infos</h1>
                        <div style="text-align: center;">
                            <span style="font-size: 25px;">
                                <span class="titleInfos" style="color: #112F5A;">Poids du sac:</span>
                                <span class="red-alert"><?=$poidsSac?><span>/<?=$poidsMax?></span></span>
                            </span>
                            <br>
                            <br>
                            <span style="font-size: 25px;">
                                <span class="titleInfos" style="color: #112F5A;">Poids du panier:</span>
                                <span class="red-alert"><?=$poidsPanier?><span>/<?=$poidsPanier?></span></span>
                            </span>
                            <br>
                            <br>
                            <span style="font-size: 25px;">
                                <span class="titleInfos" style="color: #112F5A;">Dextérité: 
                                    <span class="red-alert"><?=$dexterite?></span>
                                </span>
                            </span>
                            <br>
                            <br>
                            <span  style="font-size: 25px;">
                                <span  class="titleInfos" style="color: #112F5A;">Solde: 
                                    <span><?=$solde ?> <img style="width: 20px;" src="../images/emerald.png" alt="emerald"></span>
                                </span>
                            </span>
                            <br>
                            <br>
                            <br>
                            <br>
                            <span style="font-size: 25px;">
                                <span class="titleInfos" style="color: #112F5A;">Total du panier:
                                    <div style="display: inline-flex;">
                                        <span><?=$totalPanier?></span>
                                        <img style="width: 33px;" src="../images/emerald.png" alt="emerald">
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