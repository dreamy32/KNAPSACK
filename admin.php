<?php 
    $title = "Panier";
    session_start();
    require('header.php');
    require("DB_Procedure.php");
    $messageToastSucces =
    "<span id='snackbar'> 
        <img src='images/red_exclamation.png' alt='errorToastIcon'> &nbsp;
        Les caps ont été envoyé!
    </span>
    <script>Snackbar();</script>";

    $estConnecter = FALSE;
    if(!empty($_SESSION['alias']))
    {
        $estConnecter = TRUE;
    }
    else
    {
        echo "<script>window.location.href='login.php'</script>";
        //header("Location: login.php");
    }

    if (isset($_POST['nbCaps']) && $_POST['nbCaps'] != 0)
    {
        AjouterArgentToutLeMonde($_POST['nbCaps']);
        echo $messageToastSucces;
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
        if(option == "reduire" && parseInt(inputNbItemChoisie.value)>200){
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
                    
                    <input max="600" value="200" name="nbCaps" id="nbItemChoisie" style="width: 120px" aria-label="Alternative" type="number" readonly>
                    <button  type="button" aria-label="Minus" onclick="ModifierNbItemChoisie('reduire')"></button><button type="button" aria-label="Plus" onclick="ModifierNbItemChoisie('augmenter')"></button>                        
                    <div onclick="this.parentNode.submit()" style="text-decoration: none; width:50%;"><div class="advancedSearch" style="margin:5%; height:75px"> Envoyer de l'argent <img style="width: 20px;" src="../images/emerald.png" alt="caps"></div></div>
                    </form>
                    <a href="ajouterItem.php"> <div style="text-decoration: none; width:50%;"><div class="advancedSearch" style="margin:5%; height:75px"> Ajout d'item </div></div></a>
                    </div>                    
                </div>
            </div>

<?php require('footer.php') ?>
