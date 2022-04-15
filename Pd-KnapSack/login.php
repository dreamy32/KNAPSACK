<?php 
    $title = "Connexion";
    require('header.php');

    if(array_key_exists('bouttonConnecter', $_POST)) {
        /* Valider information formulaire  
            Si incorect | afficher erreur & garder bonne information
            Si correct  |Commit changes et header('Location: profile.php');
        */
        $estValide  = FALSE;
        $alias = $_POST["alias"];
        $mdp = $_POST["motDePasse"];
        include('DB_Procedure.php');
        $InfoJoueur = AfficherInfosJoueur($alias);



        if(!($_POST["alias"] == $InfoJoueur[5]))
            $estValide = FALSE;


        if($alias != "" || $mdp != "")
        {
            $etat = ValiderIdentité($alias, $mdp);

            if($etat = 0)
            {
                $estValide = false;
            }
            else
            {
                $estValide = true;
            }
        }
       

        if($estValide){
            session_start();
            $_SESSION['alias'] = $alias;
            $_SESSION['mdp'] = $mdp;
            $_SESSION['idJoueur'] = $InfoJoueur[0];
            header('Location: index.php');
        }

        if(!$estValide && $alias != "")
        {
            echo "Erreur. Votre nom d'utilisateur ou mot de passe est invalide";
        }
    }
    else if(array_key_exists('bouttoninscription', $_POST)){
        header('Location: inscription.php');
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