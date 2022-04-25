</div>
<script src="js/mc-tooltips.js"></script>

<?php
    $dateDerniereMAJ = "";
    $nomfichier = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/') +1); 

    $moisFr = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
    $jour = date("d", filemtime($nomfichier));
    $mois = date("m", filemtime($nomfichier));
    $annee = date("Y", filemtime($nomfichier));
    $date = "";
    $moisTxt = $moisFr[$mois - 1]; // Aller chercher le mois actuel en français
    $jour = str_replace("0", "", $jour);


    if($jour == 1)
    {
        $date = $jour . "<sup>er </sup>" . $moisTxt . " " . $annee;
    }
    else
    {
        $date = $jour . " " . $moisTxt . " " . $annee;
    }
?>

<footer>
    <div style="text-align:center">
        <p>&copy;
            Fait par Amélie Bouchard, Antony Collin-Desrochers, David Bérubé, Samy Tétrault

            <br>
            Dernière mise à jour du site: <?php echo $date ?> 
        </p>
    </div>
</footer>  
</body>
</html>