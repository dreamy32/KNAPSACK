<?php
    require('DB_Procedure.php');
    
    
    session_start();
    $infoValid=true;
    $mess = "";
    $messSucces ="";
    $messErreurFichier = "";
    $messErreurNom="";
    $messErreurQuantite="";
    $messErreurPrixUnitaire="";
    $nom = "";
    $qte = 0;
    $type = "";
    $prixU = 0;
    $poids = 0;
    $description = "";

    //Armure
    $matiere="";
    $taille = 0;

    //Arme
    $efficacite=0;
    $genre=0;

    //Medicament
    $effet="";
    $dureeeffet=0;

    //Munition
    $calibre=0;

    //Nourriture
    $pointdevie=0;


    
    

    if(!isset($_SESSION['alias']))
    {
        header('Location: login.php');
    } 

    if (AfficherInfosJoueur($_SESSION['alias'])[10] != 1) {
        echo "<script>window.location.href='index.php'</script>";
        echo "<script>alert('vous etes pas admin');</script>";
    }

      if(isset($_POST['nom']))
    {
        $nom = $_POST['nom'];       
        $qte = intval($_POST['quantite']);
        $type = $_POST['type'];

        //Armure
        $matiere=$_POST['matiere'];
        $taille = intval($_POST['taille']);
        
        //Arme
        $efficacite=intval($_POST['efficacite']);
        $genre=intval($_POST['genre']);

        //Medicament
        $effet=$_POST['effet'];
        $dureeeffet=intval($_POST['dureeeffet']);
        
        //Munition
        $calibre=intval($_POST['calibre']);

        //Nourriture
        $pointdevie=intval($_POST['pointDeVie']);

        

        $prixU = floatval($_POST['prixUnitaire']);
        $poids = intval($_POST['poids']);
        $description= $_POST['description'];
        

        if(strlen(trim($nom)) == 0)
        {
            $messErreurNom ="Le nom de l'item ne peut être vide.";
            $infoValid=false;
        }        
        if($qte <= 0)
        {
            $messErreurQuantite ="La quantite doit etre un nombre positif";
            $infoValid=false;
        }
        if($prixU <= 0)
        {
            $messErreurPrixUnitaire ="La prix doit etre positif";
            $infoValid=false;
        }

        
        if(strlen(trim(basename($_FILES['image']['name']))) == 0)
        {
            $messErreurFichier="Aucun fichier n'a été sélectionné.";
            $infoValid=false;
        }

        $imgFichType = strtolower(pathinfo(basename($_FILES['image']['name']), PATHINFO_EXTENSION));
        if($imgFichType != "jpg" && $imgFichType != "png" && $imgFichType != "jpeg" && $imgFichType != "gif")
        {
            $messErreurFichier="Ce type de fichier n'est pas supporté.";
            $infoValid=false;
        }


        if ($infoValid) {    
            
            $rep = 'items_images/';
    
            echo"ajout";

            if ($type=="A") {
                $id = AjouterArmureMagasin($nom, $qte, $matiere, $taille, $prixU, $poids, $description);
            
            } else if ($type=="W") {
                $id = AjouterArmeMagasin($nom, $qte, $efficacite, $genre, $prixU, $poids, $description, $idMunition);
            
            } else if ($type=="D") {
                $id = AjouterMedicamentMagasin($nom, $qte, $prixU, $poids, $description, $effet, $dureeEffet);
            
            } else if ($type=="M") {
                $id = AjouterMunitionMagasin($nom, $qte, $calibre, $prixU, $poids, $description);
            
            } else if ($type=="N") {
                
            echo"ajout22"; 
                $id = AjouterNourritureMagasin($nom, $qte, $prixU, $poids, $description, $pointdevie);
                
            echo"ajout333";
            }
            
            
     
            $name = basename($_FILES['image']['name']);
            $pos = strpos($name, ".");
            $fich = $rep . $id[0] . substr($name, $pos);
            echo "Fichier:" . $fich;

            
            //echo"yo5";
    
            
            if ($infoValid  && is_uploaded_file($_FILES['image']['tmp_name'])) 
            {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $fich)) 
                {
                    $messSucces = "L'item a été ajoute avec succès.";
                    
                    if($result)
                    {
                        $messSucces = "Le fichier a été téléchargé avec succès.";
                    }
                    else 
                    {
                        $mess = "Problème lors de l'ajout de votre image.";            
                    }
                    
                } 
                else 
                {
                    $mess = "Problème lors du téléchargement de votre image.";
                }
                
            } 
            $infoValid=true;
        }
    }

   // require('header.php');

?>


       <SCRIPT type="text/javascript">
        
        function changeType(){

            val = document.getElementById("type").value;

            
            document.getElementById("infoArmure").style.display="none";
            document.getElementById("infoArme").style.display="none";
            document.getElementById("infoMedicament").style.display="none";
            document.getElementById("infoMunition").style.display="none";
            document.getElementById("infoNourriture").style.display="none";


            if (val=="A") { //Armure 
                document.getElementById("infoArmure").style.display="block";

            } else if (val=="W") { //Arme
                document.getElementById("infoArme").style.display="block";

            } else if (val=="D") { //Medicament
                document.getElementById("infoMedicament").style.display="block";

            } else if (val=="M") { //Munition
                document.getElementById("infoMunition").style.display="block";
                
            } else if (val=="N") { //Nourriture
                document.getElementById("infoNourriture").style.display="block";

            }
            

        }

        
    

        </script>

       
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
                        
                            
                        
                        <table class="tabConn" WIDTH=100%>
                            <tr>
                                <td WIDTH=10%>
                                    <label for="nom">Nom de l'item : </label>
                                </td>
                                <td>
                                    <input type="text" name="nom" id="nom" minlength="1" maxlength="25" value="<?php echo $nom?>">
                                    <br>
                                    <span class="messErreur"><?php echo $messErreurNom?></span>
                                </td>
                                
                            </tr>
                            <tr>
                                <td>
                                    <label for="quantite">Quantité : </label>
                                </td>
                                <td>
                                    <input type="number" id="quantite" name="quantite" value="<?php echo $qte?>">
                                    <br>
                                    <span class="messErreur"> <?php echo $messErreurQuantite?></span>
                                </td>                             
                            </tr>

                            <tr>
                                <td>
                                    <label for="type">Type : </label>
                                </td>
                                <td>
                                    <select id="type" name="type" ONCHANGE="javascript:changeType();">
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
                            </TABLE>


                            
                            

                            <div id="infoArmure" style="display:block">
                                <table  width=100%>
                                    <tr>
                                        <td width=10%>
                                            <label for="matiere">Matiere : </label>
                                        </td>
                                        <td>
                                            <input name="matiere" maxlength="30" value="<?php echo $matiere?>"></input>
                                            <br>
                                            <span class="messErreur">
                                                <?php echo $messErreurMatiere?>
                                            </span>
                                        </td>                              
                                    </tr>
                                    <tr>
                                        <td width=10%>
                                            <label for="taille">Taille : </label>
                                        </td>
                                        <td>
                                            <input type="number" name="taille" value="<?php echo $taille?>">
                                                
                                            </input>
                                            <br>
                                            <span class="messErreur">
                                                <?php echo $messErreurTaille?>
                                            </span>
                                        </td>                              
                                    </tr>
                                </table> 
                            </div>

                            
                            
                            <div id="infoArme" style="display:none">
                                <table  width=100%>
                                    <tr>
                                        <td width=10%>
                                            <label for="efficacite">Efficacite : </label>
                                        </td>
                                        <td>
                                            <input type="number" name="efficacite" value="<?php echo $efficacite?>">
                                            <br>
                                            <span class="messErreur">
                                                <?php echo $messErreurEfficacite?>
                                            </span>
                                        </td>                              
                                    </tr>
                                    <tr>
                                        <td width=10%>
                                            <label for="genre">Genre : </label>
                                        </td>
                                        <td>
                                            <input type="number" name="genre" value="<?php echo $genre?>">
                                            <br>
                                            <span class="messErreur">
                                                <?php echo $messErreurGenre?>
                                            </span>
                                        </td>                              
                                    </tr>
                                </table> 
                            </div>
                            
                                   
                             

                            <div id="infoMedicament" style="display:none">
                                <table  width=100%>
                                    <tr>
                                        <td width=10%>
                                            <label for="effet">Effet : </label>
                                        </td>
                                        <td>
                                            <input name="effet" maxlength="150" value="<?php echo $effet?>">
                                            <br>
                                            <span class="messErreur">
                                                <?php echo $messErreurEffet?>
                                            </span>
                                        </td>                              
                                    </tr>
                                    <tr>
                                        <td width=10%>
                                            <label for="dureeeffet">Duree Effet : </label>
                                        </td>
                                        <td>
                                            <input type="number" name="dureeeffet" value="<?php echo $dureeeffet?>">
                                            <br>
                                            <span class="messErreur">
                                                <?php echo $messErreurDureeeffet?>
                                            </span>
                                        </td>                              
                                    </tr>
                                </table>    
                            </div>
                                   
                            <div id="infoMunition" style="display:none">
                                <table  width=100%>
                                    <tr>
                                        <td width=10%>
                                            <label for="calibre">Calibre : </label>
                                        </td>
                                        <td>
                                            <input type="number" name="calibre" value="<?php echo $calibre?>">
                                            <br>
                                            <span class="messErreur">
                                                <?php echo $messErreurCalibre?>
                                            </span>
                                        </td>                              
                                    </tr>
                                </table>    
                            </div>
                            
                             <div id="infoNourriture" style="display:none">
                                <table  width=100%>
                                    <tr>
                                        <td width=10%>
                                            <label for="pointdevie">Point De Vie : </label>
                                        </td>
                                        <td>
                                            <input type="number" name="pointdevie" value="<?php echo $pointdevie?>">
                                            <br>
                                            <span class="messErreur">
                                                <?php echo $messErreurPointdevie?>
                                            </span>
                                        </td>                              
                                    </tr>
                                </table>    
                            </div>

                            
                           
                            
                            
                            
                            
                            <TABLE  WIDTH=100%>
                            <tr>
                                <td WIDTH=10%>
                                    <label for="prixUnitaire">PrixUnitaire : </label>
                                </td>
                                <td>
                                    <input type="number" name="prixUnitaire" value="<?php echo $prixU?>">
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
                                    <input type="number" id="poids" name="poids" value="<?php echo $poids?>" maxlength="150">
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
                        </DIV>
                    </FORM>                 
                </div>
            </article>  
            <br> <br> <br> <br> <br> 
        </div>

        
 <?php require "footer.php"; ?>
