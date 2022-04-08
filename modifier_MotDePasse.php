<?php 
    $title = "Changer Mot de Passe";
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
            Si incorect | afficher erreur 
            Si correct  |Commit changes et header('Location: profile.php');
        */
    }
?>
<body style="text-align: center;">
    <h1>Modifier Mot De Passe</h1>
    <form method="post">
        <h3>Actuel:</h3>
        <input type="password" name="mdpActuel" placeholder="Mot de passe actuel...">
        <h3>Nouveau:</h3>
        <input type="password" name="mdpNouveau" placeholder="Nouveau mot de passe...">
        <br><br>
        <input type="password" name="mdpConfirmation" placeholder="Confirmer mot de passe...">
        <br>
        <br>
        <button aria-label="Normal" type="submit">Appliquer</button>
    </form>
<?php require('footer.php')?>