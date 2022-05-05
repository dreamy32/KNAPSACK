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
    $typemunition=0;
    $efficacite=0;
    $genre=0;

    //Medicament
    $effet="";
    $dureeeffet=0;

    //Munition
    $calibre=0;

    //Nourriture
    $pointdevie=0;

    $listArme = AfficherListeArme();
    
    

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
        $typemunition=intval($_POST['typemunition']);
        $efficacite=intval($_POST['efficacite']);
        $genre=intval($_POST['genre']);

        //Medicament
        $effet=$_POST['effet'];
        $dureeeffet=intval($_POST['dureeeffet']);
        
        //Munition
        $calibre=intval($_POST['calibre']);

        //Nourriture
        $pointdevie=intval($_POST['pointdevie']);

        

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
    
            if ($type=="A") {
                $id = AjouterArmureMagasin($nom, $qte, $matiere, $taille, $prixU, $poids, $description);
            
            } else if ($type=="W") {
                $id = AjouterArmeMagasin($nom, $qte, $efficacite, $genre, $prixU, $poids, $description, $typemunition);
            
            } else if ($type=="D") {
                $id = AjouterMedicamentMagasin($nom, $qte, $prixU, $poids, $description, $effet, $dureeeffet);
            
            } else if ($type=="M") {
                $id = AjouterMunitionMagasin($nom, $qte, $calibre, $prixU, $poids, $description);
            
            } else if ($type=="N") {            
                $id = AjouterNourritureMagasin($nom, $qte, $prixU, $poids, $description, $pointdevie);             
            }
            
            
     
            $name = basename($_FILES['image']['name']);
            $pos = strpos($name, ".");
            $fich = $rep . $id[0] . substr($name, $pos);
            //echo "Fichier:" . $fich;

            
            //echo"yo5";
    
            
            if ($infoValid  && is_uploaded_file($_FILES['image']['tmp_name'])) 
            {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $fich)) 
                {
                    $messSucces = "L'item a été ajoute avec succès.";             
                } 
                else 
                {
                    $mess = "Problème lors du téléchargement de votre image.";
                }
                
            } 
            $infoValid=true;
        }
    }

    require('header.php');

?>
<style>
        #window-title {
            font-size: 5.5vh;
            margin-block-start: 3vh;
            padding-top: unset;
            height: 9vh;
        }

        body {
            background: url(images/options_background.png) 0 0 / 10%;
        }

        article {
            margin: 3vh 2vw;
            width: 9vh;
            height: 9vh;
        }

        article img {
            width: 6vh;
        }

        .input-number {
            display: flex;
            align-items: center;
        }

        .input-number input {
            /* font-size: 3em !important; */
            appearance: textfield;

        }

        #number-values {
            grid-area: numbers;
        }

        #text-values {
            grid-area: text;
        }
        select{
            background-color: #373737;
        }
        textarea{
            background-color: #373737;
            font-size: 1rem;
        }
        textarea::placeholder{
            color: white;
            font-style: italic;
        }
    </style>



<script type="text/javascript">
    function changeType(){
        
        val = document.getElementById("type").value;  
          alert("val:"+val)
        document.getElementById("infoArmure").style.display="none";
        document.getElementById("infoArme").style.display="none";
       // document.getElementById("infoMedicament").style.display="none";
       // document.getElementById("infoMunition").style.display="none";
        //document.getElementById("infoNourriture").style.display="none";

        if (val=="A") { //Armure 
            document.getElementById("infoArmure").style.display="block"; //utiliser JQUERY

        } else if (val=="W") { //Arme
            document.getElementById("infoArme").style.display="block";

        }/* else if (val=="D") { //Medicament
            document.getElementById("infoMedicament").style.display="block";

        } else if (val=="M") { //Munition
            document.getElementById("infoMunition").style.display="block";
            
        } else if (val=="N") { //Nourriture
            document.getElementById("infoNourriture").style.display="block";
        }*/
    }
</script>

       
<body style="height: 95vh; margin: 3.3vh 5vw; margin-bottom: unset;">
    <a href="index.php"><img src="images/Knapsack.png"></img></a>
    <div aria-label="Window" style="margin: auto; height: 98%; background: url(../images/Oak_Wood_Planks.png) 0 0/15%;">
        <div id="window-container" style="margin-top: unset;">
            <h1 id="window-title">Ajouter Item</h1>
            <div class="add-container">
                <FORM ACTION='ajouterItem.php' METHOD="POST"  enctype="multipart/form-data" class="formIdentification">
                    
                <div id="text-values"
                        style="display: flex; justify-content: center; flex-direction: column; align-items: center; height: 100%;">
                
                        
                        
                        
                        <br>
                            
                            <div style="display: flex; transform: scale(1.3);">
                                <label for="type">Type:&nbsp;&nbsp;</label>
                                <select id="type" name="type" ONCHANGE="javascript:changeType();">
                                    <option value="A" selected>Armure</option>
                                    <option value="W">Arme</option>
                                    <option value="D">Médicament</option>
                                    <option value="M">Munition</option>
                                    <option value="N">Nourriture</option>
                                </select>
                            </div>   
                            <br>  
                            <div style="display: flex; transform: scale(1);">
                                <label for="type">Nom:&nbsp;&nbsp;&nbsp;</label>
                                <input type="text" name="nom" id="nom" placeholder="Nom Item" minlength="1" maxlength="25" value="<?php echo $nom?>">
                            </div>   
                            <br> 
                            <div style="display: flex; transform: scale(1);">
                                <label for="type">Qte:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                <input type="number" id="quantite" name="quantite" value="<?php echo $qte?>">                            
                            </div>
                            <br>            
                                    
                                
                            
                            <div id="infoArmure" style="display:block; justify-content: center; flex-direction: column; align-items: center; height: 100%;">
                                <div style="display: flex; transform: scale(1);">
                                    <label for="type">Matiere:&nbsp;&nbsp;&nbsp;</label>
                                    <input type="text" name="matiere" maxlength="30" placeholder="Matiere" value="<?php echo $matiere?>"></input>
                                </div>
                                <br> 
                                <div style="display: flex; transform: scale(1);">
                                    <input type="number" name="taille" value="<?php echo $taille?>">
                                </div>    
                            </div>        
                            
                            
                            <div id="infoArme" style="display:none justify-content: center; flex-direction: column; align-items: center; height: 100%;">

                                <div style="display: flex; transform: scale(1.3);">
                                        <label for="typemunition">Munition:&nbsp;&nbsp;</label>
                                
                                        <select id="typemunition" name="typemunition">
                                        <?php
                                            foreach($listArme as $objet){
                                                echo '<option value="'.$objet[0].'">'.$objet[1].'</option>';                                   
                                            }
                                        ?>
                                        </select> <br> 
                                </div>  
                                <div style="display: flex; transform: scale(1);">
                                    <label for="type">Efficacite:&nbsp;&nbsp;&nbsp;</label>
                                    <input type="number" name="efficacite" value="<?php echo $efficacite?>">
                                </div>
                                <br>
                                <div style="display: flex; transform: scale(1);">
                                    <label for="type">Genre:&nbsp;&nbsp;&nbsp;</label>
                                        
                                    <input type="number" name="genre" value="<?php echo $genre?>">
                                </div>
                                                
                            </div> 
                </div>
                <div id="text-values"
                        style="display: flex; justify-content: center; flex-direction: column; align-items: center; height: 100%;">
                            
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
                            </table>   

                            
                    


                        
                        
                    
                </div>
                </form>
            </div>
        </div>
</body>

</html>