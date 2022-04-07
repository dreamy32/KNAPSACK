<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="//db.onlinewebfonts.com/c/6ab539c6fc2b21ff0b149b3d06d7f97c?family=Minecraft" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="css/index.css" />
    <link rel="stylesheet" href="css/grid.css" />
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/toast.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="css/mc-tooltips.css">
    <link rel="stylesheet" href="css/minecraft_elements.css">
    <link rel="stylesheet" href="css/range.css">
    <script src="js/tri.js"></script>
    <script src="js/menuPopUp.js"></script>
    <script src="js/snackbar.js"></script>
    <script src="js/sound.js"></script>
    <script src="js/item-types_reset.js"></script>
    <title><?= $title ?></title>
    <style>
        .menu {
            cursor: pointer;
            margin: 13px;
            display: flex;
            }
        .menu .contenuMenu{
            visibility: hidden;
            width: 200px;
            height: 175px;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px 0;
            position: absolute;
            z-index: 1;
            top: 10%;
            margin-left: 10px;
            font-size: medium;
            background-image: url(images/oakBackground.jpg);
            }
        .menu .show {
            visibility: visible;
        }
        .menu .show {
            visibility: visible;
            -webkit-animation: fadeIn 0.5s;
            animation: fadeIn 0.5s
        }
        @-webkit-keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }
        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity:1 ;}
        }
        /* test pop up item */
        .test {
            cursor: pointer;
            display: flex;
            }
        .test .testItem{
            visibility: hidden;
            width: 200px;
            height: 200px;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px 0;
            position: absolute;
            z-index: 1;
            top: 40%;
            font-size: medium;
            background-image: url(images/oakBackground.jpg);
            }
        .test .show {
            visibility: visible;
        }
        .test .show {
            visibility: visible;
            -webkit-animation: fadeIn 0.5s;
            animation: fadeIn 0.5s
        }
        /*Menu Recherche */
        .recherche {
            cursor: pointer;
            margin: 13px;
            display: flex;
            }
        .recherche .contenuRecherche{
            visibility: hidden;
            width: 200px;
            height: 175px;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px 0;
            position: absolute;
            z-index: 1;
            margin-left: 10px;
            font-size: medium;
            background-image: url(images/oakBackground.jpg);
            }
        .recherche .show {
            visibility: visible;
        }
        .recherche .show {
            visibility: visible;
            -webkit-animation: fadeIn 0.5s;
            animation: fadeIn 0.5s
        }
    </style>
</head>