<?php
session_start();
require("header.php");
require("DB_Procedure.php");

$estConnecter = FALSE;
    if(!empty($_SESSION['alias']))
    {
        $estConnecter = TRUE;
    }
    else
    {
        echo "<script>window.location.href='login.php'</script>";
        //header("Location: login.php");
    }

    if (!isset($_POST['voirinventairealias']))
    echo "<script>window.location.href='admin.php'</script>";


echo "Inventaire de $_POST[voirinventairealias]";
echo "<a href='admin.php'> > Retour à la page admin</a>";

$tab = AfficherInventaire(AfficherInfosJoueur($_POST['voirinventairealias'])[0]);

echo "<div id='items-list'>";

foreach($tab as $objet){
    ?>
    <form action="voirInventaire.php" method="post">
    <div class="item-holder">
        <div aria-label="Item-Slot">                                    
            <img class="minetext" id="img_<?=$objet[2]?>" data-mctitle="Apple&nbsp;5lb"
                src='items_images/<?=$objet[2]?>.png'
                alt='Image de <?=$objet[2] ?>'
            >                               
        </div>
        <span><?= $objet[3] ?></span> 
        
        <div class="input-number">

            <input readonly aria-label="Alternative" type="number" name="qte" id="qte" style="width: 85px; height: 100px; font-size: xx-large;" value="<?=$objet[0] ?>">
           
        </div>

       
    </div>
    </form>
    <?php
}?>
</div>