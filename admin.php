<?php 
    $title = "Panier";
    include("DB_Procedure.php");
    session_start();
  
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
        <script>
            function ModifierNbItemChoisie(option){

                console.log(option);
        var inputNbItemChoisie = document.getElementById("nbItemChoisie");
        if(option == "reduire" && parseInt(inputNbItemChoisie.value)>1){
            inputNbItemChoisie.value = inputNbItemChoisie.value-200;
        }
        else if(option == "augmenter" && inputNbItemChoisie.value < 600){
            var nbInput = parseInt(inputNbItemChoisie.value);
            inputNbItemChoisie.value = nbInput + 200;
        }
    }

        </script>
    </head>

    <body style="height: 95vh; margin: 3.3vh 5vw; margin-bottom: unset;">
    <a href="index.php"><img src="images/Knapsack.png"></img></a>
        <div style="color:red" id="d"><?php echo $messageErreur?></div>
        
        <div aria-label="Window" style="margin: auto; height: 98%; background: inherit;">
            <div id="window-container" style="margin-top: unset;">
                <h1 id="window-title">Panneau d'admin</h1>
                <div class="cart-container">
                    <div id="items-list">

                    <form action="" method="post">
                    <input readonly type="text" style="width:50%" value="Tout le monde">
                    
                    <input max="600" value="0"  id="nbItemChoisie" style="width: 120px" aria-label="Alternative" type="number" readonly>
                    <button  type="button" aria-label="Minus" onclick="ModifierNbItemChoisie('reduire')"></button><button type="button" aria-label="Plus" onclick="ModifierNbItemChoisie('augmenter')"></button>                        
                    <div onclick="this.parentNode.submit()" style="text-decoration: none; width:50%;"><div class="advancedSearch" style="margin:5%; height:75px"> Envoyer de l'argent <img style="width: 20px;" src="../images/emerald.png" alt="caps"></div></div>
                    </form>


                    <input placeholder="Patoche" type="text" style="width:50%">
                    <div style="text-decoration: none; width:50%;"><div class="advancedSearch" style="margin:5%; height:75px"> Envoyer de l'argent <img style="width: 20px;" src="../images/emerald.png" alt="caps"></div></div>

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
