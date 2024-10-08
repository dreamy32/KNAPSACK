<?php
session_start();
require("../DB_Procedure.php");

if ($_GET['deconnecter'] == 'true') {
    session_destroy();
    session_unset();
    setcookie("PHPSESSID", null, -1);
    echo "<script>window.location.href='index.php'</script>";
    //header('Location: index.php');
}

if (isset($_POST['answer-buttons']) && $_POST['answer-buttons'] == 'good')
{
    $nbCaps;

    switch(AfficherInfosEnigme($_POST['idEnigme'])[3]) {
        case 'F': $nbCaps = 600;
        break;
        case 'M': $nbCaps = 800;
        break;
        case 'D': $nbCaps = 1000;
        break;
    }

    if ($nbCaps != null){
        AjouterArgentJoueur($nbCaps, $_SESSION['alias']);
        $_SESSION['bypass'] = true;
    }
    AjouterHistorique(1,$_SESSION['idJoueur'],$_POST['idEnigme']);
}
else{
    if (isset($_POST['answer-buttons']) && $_POST['answer-buttons'] == 'bad'){
        AjouterHistorique(0,$_SESSION['idJoueur'],$_POST['idEnigme']);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/enigma_style.css">
    <script src="./js/trivia.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Enigma</title>
    <script defer>
        function StartGame() {
            location.href = "#main";
            document.getElementById('play-text').innerHTML = "";
        }
    </script>
</head>

<body>
    <header>
        <nav>
            <div id="navbar">
                <div id="logo" class="reverse">
                    <div class="mobile-btn" style="font-size:30px;cursor:pointer; font-weight:bold;" onclick="openNav()">&#9776;</div>
                    <div class="logo">
                        <img src="https://www.svgrepo.com/show/98471/question-mark-inside-square.svg" alt="Question Block" style="width: 20px; ">
                        En<span>ig</span>Ma
                        <img src="https://www.svgrepo.com/show/98471/question-mark-inside-square.svg" alt="Question Block" style="width: 20px; ">
                        <?php
                        echo "<div style='font-size: 50%; '>";
                        if (isset($_SESSION['alias']))
                            echo "<span style='color:Orange'>" . $_SESSION['alias'] . "</span>";
                        if (AfficherInfosJoueur($_SESSION['alias'])[10] == 1) {
                            echo "<span style='color:Orange'> ~ADMIN~</span>";
                        }
                        if (isset($_SESSION['alias']))
                            echo "<span style='margin-left: 5px'>" . AfficherInfosJoueur($_SESSION['alias'])[1] . " caps </span></div>";

                        ?>
                    </div>
                </div>
                <div id="links">
                    <?php
                    if (isset($_SESSION['alias'])) {
                        echo "
                            <a href=>Choisir une énigme</a>
                            <a href='stats.php'>Statistiques</a>
                            <a href='index.php?deconnecter=true'>Se déconnecter</a>
                            ";
                    } else
                        echo "<a href='../login.php?from=enigma'>Se connecter</a>";
                    ?>
                    <a href=".."><i class="fa-solid fa-angle-right"></i>&nbsp;KNAPSACK</a>
                </div>
            </div>

        </nav>
        <!-- Mobile Menu -->
        <div id="mySidenav" class="sidenav">
            <a style="cursor:pointer;" class="closebtn" onclick="closeNav()">&times;</a>
            <a href=".."><i class="fa-solid fa-angle-right"></i>&nbsp;KNAPSACK</a>
            <br>
            <?php
            if (isset($_SESSION['alias']))
                echo "
                <a href='index.php?deconnecter=true'>Se déconnecter</a>
                <a href='index.php' >Choisir une énigme</a>
                <a href='stats.php'>Statistiques</a>
                    ";
            else
                echo "<a href='../login.php?from=enigma'>Se connecter</a>";
            ?>
        </div>
    </header>
    <main id="main" style="justify-content: space-evenly;">

        <div id='play-text'>
            <?php
            if (!isset($_SESSION['alias']))
                echo "Connectez-vous pour jouer";
            else if (!isset($_POST['answer-buttons'])){
                echo "<h2 id='title' style='text-align: center; font-family: system-ui;'>Bienvenue $_SESSION[alias] </h2>";
                echo "<div>";
                echo "<form method='post'>";
                echo "<input type='submit' class='start-button' type='submit' name='start' value='Jouer'></div>";
                echo "</form>";
            }

            ?>
        </div>
    <?php
        if (isset($_POST['start']) || isset($_POST['answer-buttons'])) {
            echo "<script>StartGame();</script>";
            do{
                $idEnigmeAleatoire = ChoisirAléatoirementEnigme();
            }while(EnigmeDejaRepondue($idEnigmeAleatoire,$_SESSION['idJoueur']) && (NombreEnigmeBD()[0] > NombreEnigmeRepondu($_SESSION['idJoueur'])[0]));
            AfficherEnigme($idEnigmeAleatoire);
            echo "<br><br>";
            echo "<a href='index.php'>Abandonner l'énigme en cours</a>";
        }
    ?>

    </main>

    <script defer>
        openNav = () => $("#mySidenav").css("width", 250);
        closeNav = () => $("#mySidenav").css("width", 0);
    </script>
</body>

</html>