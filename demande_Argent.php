<?php 
    $title = "Demande Argent";
    require('header.php');
    $messageToastSucces =
    "<span id='snackbar'> 
        <img src='images/red_exclamation.png' alt='errorToastIcon'> &nbsp;
        Votre requête à été envoyé!
    </span>
    <script>Snackbar();</script>";
    include('DB_Procedure.php');
    if(array_key_exists('bouttonDemander', $_POST)) {
        echo $messageToastSucces;
    }
?>
<body style="text-align: center;">
    <h1>Requête d'argent</h1>
    <h2><em>Seul les dieux peuvent juger de votre requête et la rendre réalité!</em></h2>
    <form method="post">
        <h3>Montant voulus:</h3>
        <input value="0"  id="nbItemChoisie" style="width: 80px" aria-label="Alternative" type="number" readonly>
        <br><br>
        <button  type="button" aria-label="Minus" onclick="ModifierNbItemChoisie('reduire')"></button><button type="button" aria-label="Plus" onclick="ModifierNbItemChoisie('augmenter')"></button>
        <br><br>
        <button aria-label="Normal" type="submit" name="bouttonDemander">Demander</button>
    </form>

<script>
    function ModifierNbItemChoisie(option){
        var inputNbItemChoisie = document.getElementById("nbItemChoisie");
        if(option == "reduire" && parseInt(inputNbItemChoisie.value)>1){
            inputNbItemChoisie.value = inputNbItemChoisie.value-1;
        }
        else if(option == "augmenter"){
            var nbInput = parseInt(inputNbItemChoisie.value);
            inputNbItemChoisie.value = nbInput + 1;
        }
}
</script>
 <?php require('footer.php')?>