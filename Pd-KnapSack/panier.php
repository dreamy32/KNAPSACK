<?php 
    $title = "Panier";
    require('header.php');
    include("DB_Procedure.php");


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

  $tab = AfficherPanier('madzcandy');  
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
    <div id="d"></div>
    
    <div aria-label="Window" style="margin: auto; height: 98%; background: inherit;">
        <div id="window-container" style="margin-top: unset;">
            <h1 id="window-title">Panier</h1>
            <div class="cart-container">
                <div id="items-list">

                    
                    <?php
                    foreach($tab as $objet){
                        $idInput = "nbItemChoisie" . $objet[0];
                        ?>
                        <form action="panier.php" method="get">
                        <div class="item-holder">
                            <div aria-label="Item-Slot"> 
                                <img class="minetext" data-mctitle="Apple&nbsp;5lb"
                                    src='items_images/<?=$objet[0]?>.png'
                                    alt='Image de <?=$objet[2] ?>'
                                >
                            </div>
                            <span><?=$objet[2] ?></span>
                            <div class="input-number">
                            <button aria-label='Minus' type='button' onclick='ReduireNbItemChoisie(<?=$objet[0]?>)'></button>
                                <input type=hidden name="numItem" value="<?=$objet[0]?>">
                                <input type=hidden name="typeaction" value="">
                                <input readonly aria-label="Alternative" type="number" name="qte" id='<?= $idInput ?>' style="width: 85px; height: 100px; font-size: xx-large;" value="<?=$objet[1] ?>">
                                <button aria-label='Plus' type='button' onclick='AugmenterNbItemChoisie(<?=$objet[0]?>)'></button>
                            </div>
                        </div>
                        </form>
                         <?php
                    }       
                    ?>
                    


                    <br><br>
                </div>
                <div id="item-info"></div>
            </div>
        </div>
<script>
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
</script>

<?php require('footer.php')?>