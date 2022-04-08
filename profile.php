<?php 
    $title = "Profile";
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
    <h1>Profile</h1>
    <form method="post">
        <h3>Nom d'utilisateur:</h3>
        <input type="text" value="<?= $profile[0] ?>" READONLY>
        <h3>Prenom:</h3>
        <input type="text" value="<?= $profile[4] ?>" READONLY>
        <h3>Nom:</h3>
        <input type="text" value="<?= $profile[5] ?>" READONLY>
        <br>
        <br>
        <button aria-label="Normal" type="submit" name="bouttonModifier">Modifier</button>
        <button aria-label="Normal" type="submit" name="bouttonMotDePasse">Mot de passe</button>
    </form>
 <?php require('footer.php')?>