<?php 
    $title = "Modifier Profile";
    session_start();
    require('header.php');
    include('DB_Procedure.php');    
    $profile = AfficherInfosJoueur($_SESSION['alias']);
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        echo "<p>allo</p>";
        echo ModifierAliasNomPrenom($_SESSION['alias'],$_POST['modifAlias'],$_POST['modifPrenom'],$_POST['modifNom']);
        $_SESSION['alias'] = $_POST['modifAlias'];
        echo "<script>window.location.href='profile.php'</script>";
    }
?>
<body style="text-align: center;">
<a href="index.php"><img src="images/Knapsack.png"></img></a>
    <h1>Modifier Profile</h1>
    <form method="post">
        <h3>Nom d'utilisateur:</h3>
        <input type="text" name='modifAlias' value="<?= $profile[4] ?>">
        <h3>Prenom:</h3>
        <input type="text" name="modifPrenom"  value="<?= $profile[6] ?>">
        <h3>Nom:</h3>
        <input type="text" name="modifNom" value="<?= $profile[5] ?>">
        <br>
        <br>
        <button aria-label="Normal" type="submit">Appliquer</button>
    </form>
<?php require('footer.php')?>