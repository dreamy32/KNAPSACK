<!DOCTYPE html>
<html lang="en">
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
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/enigma_style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Enigma</title>
    <script defer>
    function StartGame()
    {
        location.href = "#main";
        document.getElementById('play-text').innerHTML = "le jeu de bobux commence...";
    }
    </script>
</head>

<body>
    <header>
        <nav>
            <div id="navbar">
                <div id="logo" class="reverse">
                    <div class="mobile-btn" style="font-size:30px;cursor:pointer; font-weight:bold;"
                        onclick="openNav()">&#9776;</div>
                    <div class="logo">
                        <img src="https://www.svgrepo.com/show/98471/question-mark-inside-square.svg"
                            alt="Question Block" style="width: 20px; ">
                        En<span>ig</span>Ma
                        <img src="https://www.svgrepo.com/show/98471/question-mark-inside-square.svg"
                            alt="Question Block" style="width: 20px; ">
                        <?php    
                        
                        if (isset($_SESSION['alias']))
                            echo"<span style='color:Orange'>" . $_SESSION['alias']. "</span>";
                            if (AfficherInfosJoueur($_SESSION['alias'])[10] == 1) { echo "<span style='color:Orange'> ~ADMIN~</span>"; } 
                        ?>
                    </div>
                </div>
                <div id="links">
                    <?php
                        if (isset($_SESSION['alias']))
                            echo "<a href='index.php?deconnecter=true'>Se déconnecter</a>";
                        else
                            echo "<a href='../login.php?from=enigma'>Se connecter</a>";
                    ?>
                    <a href=".."><i class="fa-solid fa-angle-right"></i>&nbsp;KNAPSACK</a>
                </div>
            </div>

        </nav>
        <!-- Mobile Menu -->
        <div id="mySidenav" class="sidenav">
            <a style="cursor:pointer;" class="closebtn" onclick="closeNav()">&times;</a>
            <a href=""><i class="fa-solid fa-angle-right"></i>&nbsp;KNAPSACK</a>
            <br>
            <?php
                if (isset($_SESSION['alias']))
                    echo "<a href='index.php?deconnecter=true'>Se déconnecter</a>";
                else
                    echo "<a href='../login.php?from=enigma'>Se connecter</a>";
            ?>
        </div>
    </header>
    <main id="main">
        
    <div id='play-text'>
        <?php
            if (!isset($_SESSION['alias']))
                echo "Connectez-vous pour jouer";
            else
            {
                echo "<h2 id='title' style='text-align: center; font-family: system-ui;'>Bienvenue $_SESSION[alias] </h2>";
                echo "<div>";
                echo "<input onclick='StartGame()' class='start-button' type='submit' value='Jouer'></div>";
            }
        ?>
    </div>

    
    </main>
    <script defer>
        openNav = () => $("#mySidenav").css("width", 250);
        closeNav = () => $("#mySidenav").css("width", 0);
    </script>
    <script defer>
        let shouldMove = false
        const captcha = document.querySelector('#captcha')
        const handle = document.querySelector('#handle')
        const button = document.querySelector('#handle span')

        button.addEventListener('mousedown', (e) => {
            shouldMove = true
        })

        window.addEventListener('mousemove', (e) => {
            if (shouldMove) {
                const offsetLeft = handle.getBoundingClientRect().left
                const buttonWidth = button.getBoundingClientRect().width

                captcha.style.setProperty('--moved', `${e.clientX - offsetLeft - buttonWidth / 2}px`)
            }
        })

        window.addEventListener('mouseup', (e) => {
            if (shouldMove) {
                const finalOffset = e.clientX - handle.getBoundingClientRect().left

                if (finalOffset >= 430 && finalOffset <= 450) {
                    // pass
                    captcha.classList.add('passed')
                } else {
                    // failed
                    captcha.style.setProperty('--moved', '0px')
                }

                shouldMove = false
            }
        })
    </script>
</body>

</html>