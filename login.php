<?php 
    $title = "Connexion";
    session_start();
    require('header.php');
    $errorToast =
    "<span id='snackbar'> 
        <img src='images/red_exclamation.png' alt='errorToastIcon'> &nbsp;
        L'alias ou le mot de passe est incorrect!
    </span>
    <script>Snackbar();</script>";
    if(array_key_exists('bouttonConnecter', $_POST)) {
        /* Valider information formulaire  
            Si incorect | afficher erreur & garder bonne information
            Si correct  |Commit changes et header('Location: profile.php');
        */
        $estValide  = false;
        $alias = $_POST["alias"];
        $mdp = $_POST["motDePasse"];
        include('DB_Procedure.php');
        $InfoJoueur = AfficherInfosJoueur($alias);
        if($InfoJoueur[4] == $alias && hash("sha512",$mdp) == $InfoJoueur[7]){
            $estValide = true;
        }
        else{
            $estValide = false;
        }
        if($estValide){
            $_SESSION['alias'] = $alias;
            $_SESSION['mdp'] = $mdp;
            $_SESSION['idJoueur'] = $InfoJoueur[0];
            //header('Location: index.php');
            echo "<script>window.location.href=index.php'</script>";
        }
        else
            echo $errorToast;
    }
    else if(array_key_exists('bouttoninscription', $_POST)){
        echo "<script>window.location.href=inscription.php'</script>";
        //header('Location: inscription.php');
    }
?>
<body style="text-align: center;">
    <a href="index.php"><img src="images/Knapsack.png"></img></a>
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
    </form>
 <?php require('footer.php')?>