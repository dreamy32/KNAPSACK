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
    <script src="js/menuPopUp.js"></script>
    <script src="js/snackbar.js"></script>
    <script src="js/sound.js"></script>
    <script src="js/avatar.js"></script>
    <script src="js/recherche.js"></script>
    <title><?= $title ?></title>
    <style>
        .eval-container{
            margin: auto;
            display: flex;
            flex-direction: column;
            overflow: auto;
            width: 93%;
            height: 78%;
        }
        #window-title {
                font-size: 2.7vh;
                margin-block-start: 1.3vh;
                padding-top: unset;
            }
        .menu {
            cursor: pointer;
            margin: 13px;
            display: flex;
            }
        .menu .contenuMenu{
            visibility: hidden;
            width: 200px;
            height: 200px;
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
            height: 225px;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px 0;
            position: absolute;
            z-index: 1;
            margin-top: 300px;
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
        *::first-letter{
            text-transform: capitalize;
        }
    </style>
</head>
<?php
/**
 *  An example CORS-compliant method.  It will allow any GET, POST, or OPTIONS requests from any
 *  origin.
 *
 *  In a production environment, you probably want to be more restrictive, but this gives you
 *  the general idea of what is involved.  For the nitty-gritty low-down, read:
 *
 *  - https://developer.mozilla.org/en/HTTP_access_control
 *  - http://www.w3.org/TR/cors/
 *
 */
// function cors() {

//     // Allow from any origin
//     if (isset($_SERVER['HTTP_ORIGIN'])) {
//         // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
//         // you want to allow, and if so:
//         header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
//         header('Access-Control-Allow-Credentials: true');
//         header('Access-Control-Max-Age: 86400');    // cache for 1 day
//     }

//     // Access-Control headers are received during OPTIONS requests
//     if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

//         if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
//             // may also be using PUT, PATCH, HEAD etc
//             header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

//         if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
//             header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

//         exit(0);
//     }

//     echo "<script>console.log('You have CORS!');</script>";
// }
?>