<?php 
    $title = "Modifier Profile";
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
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        /* Valider information formulaire  
            Si incorect | afficher erreur & garder bonne information
            Si correct  |Commit changes et header('Location: profile.php');
        */
    }
?>
<body style="text-align: center;">
    <h1>Modifier Profile</h1>
    <form method="post">
        <h3>Nom d'utilisateur:</h3>
        <input type="text" value="<?= $profile[0] ?>">
        <h3>Prenom:</h3>
        <input type="text" value="<?= $profile[4] ?>">
        <h3>Nom:</h3>
        <input type="text" value="<?= $profile[5] ?>">
        <br>
        <br>
        <button aria-label="Normal" type="submit">Appliquer</button>
    </form>
<?php require('footer.php')?>