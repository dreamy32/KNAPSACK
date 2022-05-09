<?php 
    $title = "Changer Mot de Passe";
    session_start();
    require('header.php');
    include('DB_Procedure.php');    
    $profile = AfficherInfosJoueur($_SESSION['alias']);
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        if(hash("sha512",$_POST['mdpActuel'] == $profile[7])){
            if($_POST['mdpNouveau'] == $_POST['mdpConfirmation']){
                ModifierMotPasse($_SESSION['alias'],hash("sha512",$_POST['mdpConfirmation']));
                echo "<script>window.location.href='profile.php'</script>";
            }
        }
    }
?>
<body style="text-align: center;">
    <a href="index.php"><img src="images/Knapsack.png"></img></a>
    <h1>Modifier Mot De Passe</h1>
    <form method="post">
        <h3>Actuel:</h3>
        <input type="password" name="mdpActuel" placeholder="Mot de passe actuel...">
        <h3>Nouveau:</h3>
        <input type="password" name="mdpNouveau" placeholder="Nouveau mot de passe...">
        <br><br>
        <input type="password" name="mdpConfirmation" placeholder="Confirmer mot de passe...">
        <br>
        <br>
        <button aria-label="Normal" type="submit">Appliquer</button>
    </form>
<?php require('footer.php')?>