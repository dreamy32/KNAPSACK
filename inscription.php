<?php
$title = "Inscription";
$errorToast =
    "<span id='snackbar'> 
        <img src='images/red_exclamation.png' alt='errorToastIcon'> &nbsp;
        Des erreurs sont survenus
    </span>
    <script>Snackbar();</script>";
$exclamationMark = "<img style='width: 11px;' src='images/orange_exclamation.png' alt='errorFieldIcon'>&nbsp;&nbsp;&nbsp;&nbsp;";
// $successToast =
// "<span id='snackbar'> 
//     <img src='images/book_and_quill.png' alt='toast'> &nbsp;
//     Vous êtes connectés
// </span>"; Inutile, car si connecté, il ne verra pas le post. Devrait se voir sur l'accueil.
require('header.php');
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    /* Il reste à implementer les RegEx et la verification d'alias et de courriel unique*/
    $estValide  = TRUE;
    if (!($_POST["mdp"] == $_POST["mdpConfirmation"]))
        $estValide = FALSE;
    if (empty($_POST["alias"]) || empty($_POST["mdp"]) || empty($_POST["nom"]) || empty($_POST["prenom"]) || empty($_POST["courriel"]))
        $estValide = FALSE;
    if ($estValide) {
        include('DB_Procedure.php');
        AjouterJoueur($_POST["alias"], $_POST["mdp"], $_POST["nom"], $_POST["prenom"], $_POST["courriel"]);
        header('Location: index.php');
        $errorToast = ""; //Si valide, on retire le contenu de la variable.
    }
    echo $errorToast;
}
?>

<body style="text-align: center;">
    <h1>S'inscrire</h1>
    <form method="post">
        <div class="inscripContainer">
            <div>
                <h3>Nom d'utilisateur:</h3>
                <input type="text" value="<?= $_POST["alias"] ?>" placeholder="ScrumMaster" name="alias" maxlength="30">
                <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && empty($_POST["alias"])) echo "<p>$exclamationMark Ce champs ne doit pas être vide.</p>"; ?>
                <h3>Prenom:</h3>
                <input type="text" value="<?= $_POST["prenom"] ?>" placeholder="Alain" name="prenom" maxlength="40">
                <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && empty($_POST["prenom"])) echo "<p>$exclamationMark Ce champs ne doit pas être vide.</p>"; ?>
                <h3>Nom:</h3>
                <input type="text" value="<?= $_POST["nom"] ?>" placeholder="Patoche" name="nom" maxlength="60">
                <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && empty($_POST["nom"])) echo "<p>$exclamationMark Ce champs ne doit pas être vide.</p>"; ?>
            </div>
            <div>
                <h3>Courriel:</h3>
                <input type="email" value="<?= $_POST["courriel"] ?>" placeholder="Patoche@hotmail.com" name="courriel" maxlength="100">
                <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && empty($_POST["courrriel"])) echo "<p>$exclamationMark Ce champs ne doit pas être vide.</p>"; ?>
                <h3>Nouveau:</h3>
                <input type="password" name="mdp" placeholder="Mot de passe..." maxlength="50" require>
                <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && empty($_POST["mdp"])) echo "<p>$exclamationMark Ce champs ne doit pas être vide.</p>"; ?>
                <h3>&nbsp;</h3>
                <input type="password" name="mdpConfirmation" placeholder="Confirmer mot de passe..." maxlength="50">
                <?php
                if ($_SERVER['REQUEST_METHOD'] == "POST" && empty($_POST["mdpConfirmation"])) echo "<p>Ce champs ne doit pas être vide.</p>";
                if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST["mdp"] != $_POST["mdpConfirmation"]) echo "<p>Les mots de passes doivent être identique.</p>";
                ?>
            </div>
        </div>
        <br><br>
        <button aria-label="Normal" type="submit" name="bouttonInscrire">S'inscrire</button>
        <br><br>
    </form>
    <?php require('footer.php') ?>