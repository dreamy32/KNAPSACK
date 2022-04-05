<?php 
    $title = "Connexion";
    require('header.php');

    if(array_key_exists('bouttonConnecter', $_POST)) {
        /* Valider information formulaire  
            Si incorect | afficher erreur & garder bonne information
            Si correct  |Commit changes et header('Location: profile.php');
        */
        $estValide  = TRUE;
        $alias = $_POST["alias"];
        include('DB_Procedure.php');
        $InfoJoueur = AfficherInfosJoueur($alias);
        if(!($_POST["alias"] == $InfoJoueur[5]))
            $estValide = FALSE;
        if(!(hash("sha512", $_POST["motDePasse"]) == $InfoJoueur[7]))
            $estValide = FALSE;
        if(empty($_POST["alias"]) || empty($_POST["motDePasse"]))
            $estValide = FALSE;
        if($estValide){
            session_start();
            $_SESSION['alias'] = $InfoJoueur[5];
            $_SESSION['mdp'] = $InfoJoueur[8];
            $_SESSION['idJoueur'] = $InfoJoueur[1];
            header('Location: index.php');
        }
    }
    else if(array_key_exists('bouttoninscription', $_POST)){
        header('Location: inscription.php');
    }
    else if(array_key_exists('bouttonTemp', $_POST)) {
        header('Location: profile.php');
    }
?>
<body style="text-align: center;">
    <h1>Connexion</h1>
    <form method="post">
        <h3>Nom d'utilisateur:</h3>
        <input type="text" placeholder="Patoche" name="alias" value="<?= $_POST["alias"] ?>">
        <h3>Mot de passe:</h3>
        <input type="password" name="motDePasse" >
        <br>
        <br>
        <button aria-label="Normal" type="submit" name="bouttonConnecter">Se connecter</button>
        <h5>Pas de compte? Aucun problème! Inscrivez vous ici:</h5>
        <button aria-label="Normal" type="submit" name="bouttoninscription">S'inscrire</button>
        <button aria-label="Normal" type="submit" name="bouttonTemp">Login(Temporaire)</button>
    </form>
 <?php require('footer.php')?>