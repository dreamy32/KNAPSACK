<?php 
    $title = "Demande Argent";
    require('header.php');
    /*
        include('DB_Procedure.php');
        $profile = chercherInfoJoueur()
        $profile[0] : Alias du joueur
        $profile[1] : Solder du joueur 
        $profile[2] : poids du joueur
        $profile[3] : poidsmax du joueur
        $profile[4] : Prenom du joueur
        $profile[5] : Nom du joueur
    */
    if(array_key_exists('bouttonModifier', $_POST)) {
        header('Location: modifier_Profile.php');
    }
    else if(array_key_exists('bouttonMotDePasse', $_POST)) {
        header('Location: modifier_MotDePasse.php');
    }
?>
<body style="text-align: center;">
    <h1>Requête d'argent</h1>
    <h2><em>Seul les dieux peuvent juger de votre requête et la rendre réalité!</em></h2>
    <form method="post">
        <h3>Montant voulus:</h3>
        <input value="0"  id="nbItemChoisie" style="width: 80px" aria-label="Alternative" type="number" value="<?= $profile[0] ?>" readonly>
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