<?php
    require('DB_Procedure.php');
    
    session_start();
    $mess = "";
    $messSucces ="";
    /*
    $messErreurNom = "";
    $messErreurDescription = " ";
    */
    $messErreurFichier = "";
    $nom = "";
    $qte = 0;
    $type = "";
    $prixU = 0;
    $poids = 0;
    $description = "";

    echo "loll1a";
    if(!isset($_SESSION['alias']))
    {
        echo "<script>window.location.href='login.php'</script>";
        //header('Location: login.php');
    } 

    echo "debug2:". $_POST['nom'];
    echo "debug3:". $_POST['quantite'];
    if(isset($_POST['nom']))
    {
        echo"yo1";
        $nom = $_POST['nom'];       
        $qte = intval($_POST['quantite']);
        $type = $_POST['type'];
        $prixU = floatval($_POST['prixUnitaire']);
        $poids = intval($_POST['poids']);
        $description= $_POST['description'];
        echo"yo2";

        $rep = 'items_images/';
        $idUnique = rand(0,10000000);
        $fich = $rep . $pseudo . $idUnique . basename($_FILES['image']['name']);
        $infoValid=true;

        // enlever les espaces et les "+" dans les url des images
        // car sinon la page contient des erreurs dans le validateur de pages.
        $fich = str_replace(" ", "", $fich);
        $fich = str_replace("+", "", $fich);

        if(strlen(trim($nom)) == 0)
        {
            $messErreurNom ="Le nom de l'objet ne peut être vide.";
            $infoValid=false;
        }
        echo"yo3";
        if(strlen(trim(basename($_FILES['image']['name']))) == 0)
        {
            $messErreurFichier="Aucun fichier n'a été sélectionné.";
            $infoValid=false;
        }


        $imgFichType = strtolower(pathinfo($fich, PATHINFO_EXTENSION));

        echo"yo4";
        if($imgFichType != "jpg" && $imgFichType != "png" && $imgFichType != "jpeg" && $imgFichType != "gif")
        {
            $messErreurFichier="Ce type de fichier n'est pas supporté.";
            $infoValid=false;
        }
        echo"yo5";

        if ($infoValid  && is_uploaded_file($_FILES['image']['tmp_name'])) 
        {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $fich)) 
            {
                echo"yo6";
                AjouterItemMagasin($nom, $qte, $type, $prixU, $poids, $description);
                $messSucces = "Le fichier a été téléchargé avec succès.";
                echo"yo7";
                /*
                if($result)
                {
                    $messSucces = "Le fichier a été téléchargé avec succès.";
                }
                else 
                {
                    $mess = "Problème lors de l'ajout de votre image.";            
                }
                */
            } 
            else 
            {
                $mess = "Problème lors du téléchargement de votre image.";
            }
        }      
    } 
   // require('header.php');

?>

       
        <div class="page">
            <article>
                <h1>Ajouter une nouvelle image</h1>
                <div class="upload">
                    <br>
                   
                    <?php  
                        if($mess != "" || $messSucces != "")
                        {
                            ?>
                                <div class="messUploadEnvoi">
                                    <span class="messErreur">
                                        <?php echo $mess?>
                                    </span>
                                    <span class="messSucces">
                                        <?php echo $messSucces?>
                                    </span>
                                </div>
                            <?php  
                        }
                    ?>      
                </div>
            </article>  
            <br> <br> <br> <br> <br> 
        </div>

   <?php require "footer.php"; ?>