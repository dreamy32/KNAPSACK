<?php
    session_start();
    require('DB_Procedure.php');
    $mess = "";
    $messSucces ="";
    $messErreurFichier = "";
    $nom = "";
    $qte = 0;
    $type = "";
    $prixU = 0;
    $poids = 0;
    $description = "";

    if(!isset($_SESSION['alias']))
    {
        echo "<script>window.location.href='login.php'</script>";
        //header('Location: login.php');
    } 

      if(isset($_POST['nom']))
    {
        $nom = $_POST['nom'];       
        $qte = intval($_POST['quantite']);
        $type = $_POST['type'];
        $prixU = floatval($_POST['prixUnitaire']);
        $poids = intval($_POST['poids']);
        $description= $_POST['description'];
        
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
        
        if(strlen(trim(basename($_FILES['image']['name']))) == 0)
        {
            $messErreurFichier="Aucun fichier n'a été sélectionné.";
            $infoValid=false;
        }


        $imgFichType = strtolower(pathinfo($fich, PATHINFO_EXTENSION));

        
        if($imgFichType != "jpg" && $imgFichType != "png" && $imgFichType != "jpeg" && $imgFichType != "gif")
        {
            $messErreurFichier="Ce type de fichier n'est pas supporté.";
            $infoValid=false;
        }
        //echo"yo5";

        if ($infoValid  && is_uploaded_file($_FILES['image']['tmp_name'])) 
        {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $fich)) 
            {
                //echo"yo6";
                AjouterItemMagasin($nom, $qte, $type, $prixU, $poids, $description);
                $messSucces = "L'item a été ajoute avec succès.";
                //echo"yo7";
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
    //require('header.php');

?>

       
        <div class="page">
            <article>
                <h1>Ajouter un nouvel item</h1>
                <div class="upload">
                    <br>             
                    <?php  
                        if($mess != "" || $messSucces != "")
                        {
                            ?>
                                <div class="messUploadEnvoi">
                                    <span class="messErreur"><?php echo $mess?></span>
                                    <span class="messSucces"><?php echo $messSucces?></span>
                                </div>
                            <?php  
                        }
                    ?>

                
                    <br>
                    <FORM ACTION='ajouterItem.php' METHOD="POST"  enctype="multipart/form-data" class="formIdentification">
                        <table class="tabConn">
                            <tr>
                                <td>
                                    <label for="nom">Nom de l'item : </label>
                                </td>
                                <td>
                                    <input type="text" name="nom" id="nom" minlength="1" maxlength="25" value="<?php echo $nom?>">
                                    <br>
                                    <span class="messErreur"><?php echo $messErreur?></span>
                                </td>
                                
                            </tr>
                            <tr>
                                <td>
                                    <label for="quantite">Quantité : </label>
                                </td>
                                <td>
                                    <input type="number" id="quantite" name="quantite" rows="4" cols="40" maxlength="150">
                                        <?php echo $quantite?>
                                    </input>
                                    <br>
                                    <span class="messErreur"> <?php echo $messErreurQuantite?></span>
                                </td>                             
                            </tr>

                            <tr>
                                <td>
                                    <label for="type">Type : </label>
                                </td>
                                <td>
                                    <select id="type" name="type">
                                        <option value="A" selected>Armure</option>
                                        <option value="W">Arme</option>
                                        <option value="D">Médicament</option>
                                        <option value="M">Munition</option>
                                        <option value="N">Nourriture</option>
                                    </select>
                                    <br>
                                    <span class="messErreur">
                                        <?php echo $messErreurType?>
                                    </span>
                                </td>                              
                            </tr>
                            
                            <tr>
                                <td>
                                    <label for="prixUnitaire">PrixUnitaire : </label>
                                </td>
                                <td>
                                    <input type="number" name="prixUnitaire" rows="4" cols="40" maxlength="150">
                                        <?php echo $prixUnitaire?>
                                    </input>
                                    <br>
                                    <span class="messErreur">
                                        <?php echo $messErreurPrixUnitaire?>
                                    </span>
                                </td>                              
                            </tr>

                            <tr>
                                <td>
                                    <label for="poids">Poids : </label>
                                </td>
                                <td>
                                    <input type="number" id="poids" name="poids" rows="4" cols="40" maxlength="150">
                                        <?php echo $poids?>
                                    </input>
                                    <br>
                                    <span class="messErreur">
                                        <?php echo $messErreurPoids?>
                                    </span>
                                </td>                              
                            </tr>

                            <tr>
                                <td>
                                    <label for="description">Description : </label>
                                </td>
                                <td>
                                    <textarea id="description" name="description" rows="4" cols="40" maxlength="90"><?php echo $description?></textarea>
                                    <br>
                                    <span class="messErreur">
                                        <?php echo $messErreurDescription?>
                                    </span>
                                </td>
                                
                            </tr>

                            <tr>
                                <td>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="5000000">
                                    Photo : 
                                </td>
                                <td>                                
			                        <input name="image" size="35" type="file"> 
                                    <br>
                                    <span class="messErreur">
                                        <?php echo $messErreurFichier?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">                                
                                <br>
                                    <input type="submit" name="upload" id="upload" value="Ajouter">
                                </td>                               
                            </tr>
                        </table>                                         
                    </FORM>                 
                </div>
            </article>  
            <br> <br> <br> <br> <br> 
        </div>

        
   <?php require "footer.php"; ?>