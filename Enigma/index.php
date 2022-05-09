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
    <title>CSS Grid Navigation Bar</title>
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
                            if (AfficherInfosJoueur($_SESSION['alias'])[10] == 1) { echo "<span style='color:Orange'> ~ ADMIN ~</span>"; } 
                              
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
                    <!-- <a href="">Choisir une énigme</a>
                    <a href="">Statistiques</a>
                    <a href="">Se déconnecter</a> -->
                    <a href=".."><i class="fa-solid fa-angle-right"></i>&nbsp;KNAPSACK</a>
                </div>
            </div>

        </nav>
        <!-- Mobile Menu -->
        <div id="mySidenav" class="sidenav">
            <a style="cursor:pointer;" class="closebtn" onclick="closeNav()">&times;</a>
            <a href=""><i class="fa-solid fa-angle-right"></i>&nbsp;KNAPSACK</a>
            <br>
            <a href="">Se connecter</a>
            <!-- <a href="">Choisir une énigme</a>
            <a href="">Statistiques</a>
            <a href="">Se déconnecter</a> -->
        </div>
    </header>
    <main>
        <!-- <div id="captcha">
            <div id="handle">
                <span></span>
            </div>
        </div> -->
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
    <script defer>
        // This needs refactoring but it works

        var puzzle = {
            complete: false,
            linksGenerated: false,
            empty: {
                emptyPos: 0,
                emptyRow: 0,
                emptyCol: 0,
                emptyOptions: [[], [], [], []],
                emptyFindOptions: function () {
                    // Top
                    puzzle.empty.emptyOptions[0].push(+puzzle.empty.emptyRow - 1);
                    puzzle.empty.emptyOptions[0].push(+puzzle.empty.emptyCol);
                    // Bottom
                    puzzle.empty.emptyOptions[1].push(+puzzle.empty.emptyRow + 1);
                    puzzle.empty.emptyOptions[1].push(+puzzle.empty.emptyCol);
                    // Left
                    puzzle.empty.emptyOptions[2].push(+puzzle.empty.emptyRow);
                    puzzle.empty.emptyOptions[2].push(+puzzle.empty.emptyCol - 1);
                    // Right
                    puzzle.empty.emptyOptions[3].push(+puzzle.empty.emptyRow);
                    puzzle.empty.emptyOptions[3].push(+puzzle.empty.emptyCol + 1);
                }
            },
            timer: {
                started: false,
                timerRef: undefined,
                startTimer: function () {
                    if (!this.started) {
                        this.started = true;
                        var start = new Date().getTime(), elapsed = '0.0', h1 = document.querySelector("h1.timer span");
                        this.timerRef = setInterval(function () {
                            var time = new Date().getTime() - start;
                            elapsed = Math.floor(time / 100) / 10;
                            if (Math.round(elapsed) == elapsed) { elapsed += '.0'; }
                            h1.innerText = elapsed;
                        }, 100);
                    }
                }
            },
            startPoints: [
                [12, 15, 3, 4, 10, 7, 0, 13, 5, 9, 8, 6, 11, 14, 2, 1],
                [13, 6, 8, 7, 14, 4, 12, 2, 10, 1, 3, 11, 9, 15, 5, 0],
                [14, 13, 5, 12, 2, 3, 15, 4, 8, 0, 11, 9, 10, 1, 7, 6],
                [3, 2, 1, 4, 5, 0, 11, 8, 9, 7, 10, 12, 13, 14, 6, 15],
                // [1,2,3,4,5,6,7,8,9,10,11,12,13,0,14,15]
            ],
            currentOrder: [],
            desiredOrder: [],
            checkVictory: function () {
                var tiles = document.querySelectorAll(".tile");
                this.currentOrder = [];
                for (i = 0; i < tiles.length; i++) {
                    this.currentOrder.push(tiles[i].dataset.position);
                }
                var desired = this.desiredOrder.slice(0);
                var a = desired.indexOf(0);
                desired.splice(a, 1);
                var a = desired.join("");
                var b = this.currentOrder.join("");

                if (a === b) {
                    return true;
                }
                else {
                    return false;
                }
            },
            checkSingle: function (el) {
                if (el.dataset.position === el.dataset.num) {
                    return true;
                }
                else {
                    return false;
                }
            },
            checkTile: function (num, col, row) {
                for (i = 0; i < this.empty.emptyOptions.length; i++) {
                    if (this.empty.emptyOptions[i][0] == row && this.empty.emptyOptions[i][1] == col) {
                        return true;
                    }
                }
                return false;
            },
            moveTile: function (el, col, row) {
                if (!this.complete) {
                    var num = el.dataset.position;
                    col = el.dataset.col;
                    row = el.dataset.row;
                    if (this.checkTile(+num, col, row)) {
                        el.dataset.position = this.empty.emptyPos;
                        el.dataset.col = this.empty.emptyCol;
                        el.dataset.row = this.empty.emptyRow;
                        this.empty.emptyPos = num;
                        this.empty.emptyRow = row;
                        this.empty.emptyCol = col;
                        this.empty.emptyOptions = [[], [], [], []];
                        this.empty.emptyFindOptions();

                        // Start timer
                        this.timer.startTimer();
                    }
                    // Check if match
                    this.checkSingle(el) ? el.classList.add("match") : el.classList.remove("match");
                    // Check if all match
                    if (this.checkVictory()) {
                        clearInterval(puzzle.timer.timerRef);
                        document.querySelector(".winMsg").classList.remove("hide");
                        this.complete = true;
                        document.querySelector(".inner-container").classList.add("complete");
                    }
                }
            },
            init: function (order) {
                var r, i, pos, tiles, col, row, entries, list, links, container = document.querySelector(".inner-container");

                // Stop and reset timer if running
                if (puzzle.timer.timerRef !== undefined) {
                    clearInterval(puzzle.timer.timerRef);
                    this.timer.started = false;
                    document.querySelector("h1.timer span").innerText = "0";
                }

                this.empty.emptyOptions = [[], [], [], []];
                this.currentOrder = [];
                this.desiredOrder = [];

                // Create links if not already there
                if (!this.linksGenerated) {
                    for (i = 0, list = document.querySelector(".puzzle-list"), entries = this.startPoints.length; i < entries; i++) {
                        list.insertAdjacentHTML('beforeend', '<li>[<a href="/" data-puzzle="' + (i + 1) + '">Puzzle ' + (i + 1) + '</a>]</li>');
                    }
                    this.linksGenerated = true;
                    // Attach event handlers to links
                    links = document.querySelectorAll(".puzzle-list li");
                    for (i = 0; i < links.length; i++) {
                        links[i].children[0].addEventListener("click", function (e) {
                            e.preventDefault();
                            puzzle.init(e.target.dataset.puzzle);
                        });
                    }
                }

                // Declare complete to be false
                this.complete = false;
                document.querySelector(".winMsg").classList.add("hide");
                document.querySelector(".inner-container").classList.remove("complete");

                // Get rid of any tiles if they exist
                while (container.firstChild) {
                    container.removeChild(container.firstChild);
                }

                // Get tile order from num we pass in
                var startOrder = this.startPoints[order - 1];

                // Sort Title
                document.querySelector("h1.title span").innerText = order;

                //Populate container
                for (i = 0, pos = 1, col = 1, row = 1; i < startOrder.length; pos++, i++) {

                    // If 0 found declare it as empty space and update this.empty accordingly
                    if (startOrder[i] === 0) {
                        this.empty.emptyPos = pos;
                        this.empty.emptyRow = row;
                        this.empty.emptyCol = col;
                        this.empty.emptyFindOptions();
                    }
                    else {
                        container.insertAdjacentHTML('beforeend', '<div class="tile" data-row="' + row + '" data-num="' + startOrder[i] + '" data-col="' + col + '" data-position="' + pos + '"><span>' + startOrder[i] + '</span></div>');
                    }
                    // Update row 
                    // Reset column every 4
                    pos % 4 === 0 ? (col = 1, row++) : col++;
                    this.desiredOrder.push(startOrder[i]);
                };

                // Attach event handlers to tiles
                tiles = document.getElementsByClassName("tile");
                for (i = 0; i < tiles.length; i++) {
                    this.checkSingle(tiles[i]) ? tiles[i].classList.add("match") : tiles[i].classList.remove("match");
                    tiles[i].addEventListener("click", function () {
                        puzzle.moveTile(this);
                    });
                }
            }
        }

        puzzle.init(1);
    </script>
</body>

</html>