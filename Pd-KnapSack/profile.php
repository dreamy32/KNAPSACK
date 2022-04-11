<?php 
    $title = "Profile";
    require('header.php');
    session_start();
    include('DB_Procedure.php');    
    $profile = AfficherInfosJoueur($_SESSION['alias']);
    if(array_key_exists('bouttonModifier', $_POST)) {
        header('Location: modifier_Profile.php');
    }
    else if(array_key_exists('bouttonMotDePasse', $_POST)) {
        header('Location: modifier_MotDePasse.php');
    }
?>
<body style="text-align: center;">
<a href="index.php"><img src="images/Knapsack.png"></img></a>
    <h1>Profile</h1>
    <form method="post">
        <h3>Nom d'utilisateur:</h3>
        <input type="text" value="<?= $profile[4] ?>" READONLY>
        <h3>Prenom:</h3>
        <input type="text" value="<?= $profile[6] ?>" READONLY>
        <h3>Nom:</h3>
        <input type="text" value="<?= $profile[5] ?>" READONLY>
        <br>
        <br>
        <button aria-label="Normal" type="submit" name="bouttonModifier">Modifier</button>
        <button aria-label="Normal" type="submit" name="bouttonMotDePasse">Mot de passe</button>
    </form>
 <?php require('footer.php')?>