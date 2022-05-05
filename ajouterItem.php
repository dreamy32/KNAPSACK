<?php
require('DB_Procedure.php');


session_start();
$infoValid = true;
$mess = "";
$messSucces = "";
$messErreurFichier = "";
$messErreur = "";
$nom = "";
$qte = 1;
$type = "A";
$prixU = 1;
$poids = 1;
$description = "";

//Armure
$matiere = "";
$taille = 1;

//Arme
$typemunition = 1;
$efficacite = 1;
$genre = "";

//Medicament
$effet = "";
$dureeeffet = 1;

//Munition
$calibre = 1;

//Nourriture
$pointdevie = 1;

$listArme = AfficherListeArme();



if (!isset($_SESSION['alias'])) {
    header('Location: login.php');
}

if (AfficherInfosJoueur($_SESSION['alias'])[10] != 1) {
    echo "<script>window.location.href='index.php'</script>";
    echo "<script>alert('vous etes pas admin');</script>";
}

if (isset($_POST['nom'])) {
    $nom = $_POST['nom'];
    $qte = intval($_POST['quantite']);
    $type = $_POST['type'];

    //Armure
    $matiere = $_POST['matiere'];
    $taille = intval($_POST['taille']);

    //Arme
    $typemunition = intval($_POST['typemunition']);
    $efficacite = intval($_POST['efficacite']);
    $genre = $_POST['genre'];

    //Medicament
    $effet = $_POST['effet'];
    $dureeeffet = intval($_POST['dureeeffet']);

    //Munition
    $calibre = intval($_POST['calibre']);

    //Nourriture
    $pointdevie = intval($_POST['pointdevie']);



    $prixU = floatval($_POST['prixUnitaire']);
    $poids = floatval($_POST['poids']);
    $description = $_POST['description'];


    if (strlen(trim($nom)) == 0) {
        //$messErreurNom = "Le nom de l'item ne peut être vide.";
        $messErreur = "Le nom de l'item ne peut être vide.";
        $infoValid = false;
    }
    if (strlen(trim($description)) == 0) {
        //$messErreurNom = "Le nom de l'item ne peut être vide.";
        $messErreur = "Le description de l'item ne peut être vide.";
        $infoValid = false;
    }

    if ($type == "A" && strlen(trim($matiere)) == 0) {      
        $messErreur = "La matiere ne peut être vide.";
        $infoValid = false;
    }
    if ($type == "W" && strlen(trim($genre)) == 0) {      
        $messErreur = "La genre ne peut être vide.";
        $infoValid = false;
    }
    if ($type == "D" && strlen(trim($effet)) == 0) {      
        $messErreur = "La effet ne peut être vide.";
        $infoValid = false;
    }
   

    if ($prixU <= 0) {
        //$messErreurPrixUnitaire = "La prix doit etre positif";
        $messErreur = "La prix doit etre positif";
        $infoValid = false;
    }


    if (strlen(trim(basename($_FILES['image']['name']))) == 0) {
        //$messErreurFichier = "Aucun fichier n'a été sélectionné.";
        $messErreur = "Aucun fichier n'a été sélectionné.";
        $infoValid = false;
    }

    $imgFichType = strtolower(pathinfo(basename($_FILES['image']['name']), PATHINFO_EXTENSION));
    if ($imgFichType != "jpg" && $imgFichType != "png" && $imgFichType != "jpeg" && $imgFichType != "gif") {
        //$messErreurFichier = "Ce type de fichier n'est pas supporté.";
        $messErreur = "Ce type de fichier n'est pas supporté.";
        $infoValid = false;
    }


    if ($infoValid) {

        $rep = 'items_images/';

        try{
            if ($type == "A") {
                $id = AjouterArmureMagasin($nom, $qte, $matiere, $taille, $prixU, $poids, $description);
            } else if ($type == "W") {
                $id = AjouterArmeMagasin($nom, $qte, $efficacite, $genre, $prixU, $poids, $description, $typemunition);
            } else if ($type == "D") {
                $id = AjouterMedicamentMagasin($nom, $qte, $prixU, $poids, $description, $effet, $dureeeffet);
            } else if ($type == "M") {
                $id = AjouterMunitionMagasin($nom, $qte, $calibre, $prixU, $poids, $description);
            } else if ($type == "N") {
                $id = AjouterNourritureMagasin($nom, $qte, $prixU, $poids, $description, $pointdevie);
            }
        }catch(PDOException $e){
            $infoValid = false;
            $messErreur=$e->getMessage();
        }

        $name = basename($_FILES['image']['name']);
        $pos = strpos($name, ".");
        $fich = $rep . $id[0] . substr($name, $pos);

        if ($infoValid  && is_uploaded_file($_FILES['image']['tmp_name'])) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $fich)) {
                $messSucces = "L'item #" . $id[0] . " a été ajouté avec succès.";
                $nom = "";
                $qte = 1;
                $type = "A";
                $prixU = 1;
                $poids = 1;
                $description = "";
                //Armure
                $matiere = "";
                $taille = 1;
                //Arme
                $typemunition = 1;
                $efficacite = 1;
                $genre = "";
                //Medicament
                $effet = "";
                $dureeeffet = 1;
                //Munition
                $calibre = 1;
                //Nourriture
                $pointdevie = 1;
            } else {
                $mess = "Problème lors du téléchargement de votre image.";
            }
        }
        $infoValid = true;
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

    select {
        background-color: #373737;
    }

    textarea {
        background-color: #373737;
        font-size: 1rem;
    }

    textarea::placeholder {
        color: white;
        font-style: italic;
    }
</style>
</head>




<script type="text/javascript">
    function changeType() {

        val = document.getElementById("type").value;
        document.getElementById("infoArmure").style.display = "none";
        document.getElementById("infoArme").style.display = "none";
        document.getElementById("infoMedicament").style.display="none";
        document.getElementById("infoMunition").style.display="none";
        document.getElementById("infoNourriture").style.display="none";

        if (val == "A") { //Armure 
            document.getElementById("infoArmure").style.display = "block"; //utiliser JQUERY
        } 
        else if (val == "W") { //Arme
            document.getElementById("infoArme").style.display = "block";
        }
        else if (val=="D") { //Medicament
            document.getElementById("infoMedicament").style.display="block";
        } 
        else if (val=="M") { //Munition
            document.getElementById("infoMunition").style.display="block";          
        } 
        else if (val=="N") { //Nourriture
            document.getElementById("infoNourriture").style.display="block";
        }
    }

    function IncrementNumber(increment, field)
    {
        let qte = parseInt(document.getElementById(field).value);
        if(qte + increment > 0)
        {
            document.getElementById(field).value = qte + increment;
        }
    }

    function VerifyDecimal(prixUnitaire)
    { 
        let prixU = parseInt(document.getElementById(prixUnitaire).value);
        if(prixU < 0)
        {
            document.getElementById(prixUnitaire).value = 0;
        }
    }

</script>

<body style="height: 95vh; margin: 3.3vh 5vw; margin-bottom: unset;">
    <a href="index.php"><img src="images/Knapsack.png"></img></a>
    <div aria-label="Window" style="margin: auto; height: 98%; background: url(../images/Oak_Wood_Planks.png) 0 0/15%;">
        <div id="window-container" style="margin-top: unset;">
            <h1 id="window-title">Ajouter Item</h1>
            <FORM ACTION='ajouterItem.php' METHOD="POST"  enctype="multipart/form-data" class="formIdentification">
                <div class="add-container">
                    <?php  
                        if($mess != "" || $messSucces != "")
                        {
                            ?>
                                <div style="background-color:#373737">
                                    <span class="messSucces"><?php echo $messSucces?></span>
                                </div>
                            <?php  
                        }
                    ?>
                    <div id="number-values" style="display: flex; justify-content: space-evenly; 
                        flex-direction: column; align-items: center; height: 100%;">
                        <table style="transform: scale(1);">
                            <tr>
                                <td>Poids:</td>
                                <td>
                                    <div>
                                        <input style="width:200px;" type="text" id="poids" name="poids" min="1" value="<?php echo $poids?>" maxlength="150">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Quantité:</td>
                                <td>
                                    <div class="input-number">
                                        <button type="button" aria-label="Minus" onclick="javascript:IncrementNumber(-1, 'quantite')"></button>
                                        <input readonly aria-label="Alternative"id="quantite" name="quantite" value="<?php echo $qte?>" type="number" style="width: 85px; height: 100px; font-size: xx-large;">
                                        <button type="button" aria-label="Plus" onclick="javascript:IncrementNumber(1, 'quantite')"></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Prix:</td>
                                <td>
                                    <div>
                                        <input style="width:200px;" type="text" name="prixUnitaire" id="prixUnitaire" min="1" value="<?php echo $prixU?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>      
                                <td colspan="2">                  
                                    <input type="hidden" name="MAX_FILE_SIZE" value="5000000">
                                    <input name="image" size="35" type="file" aria-label="Alternative" style="font-family: Minecraft; font-size: 15px;">
                                </td>
                            </tr>
                            <tr>      
                                <td colspan="2" align="center">  
                                    <br>   
                                    <button type="submit" >Ajouter</button>            
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div id="text-values" style="display: flex; justify-content: center; flex-direction: column; align-items: center; height: 100%;">
                        <table class="tabConn" WIDTH=100%>
                            <tr>
                                <td WIDTH=17%>
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
                                </td>
                            </tr>
                            <tr>
                                <td WIDTH=15%>
                                    <label for="nom">Nom : </label>
                                </td>
                                <td>
                                    <input type="text" name="nom" id="nom" minlength="1" maxlength="25" value="<?php echo $nom ?>">
                                    <br>
                                </td>

                            </tr>
                            <tr>
                                <td WIDTH=15% nowrap> 
                                    <label for="nom">Desc : </label>
                                </td>
                                <td>
                                    <textarea style="resize:none" name="description" id="description" cols="37" rows="3" placeholder="Description"><?php echo $description?></textarea>
                                    <br>
                                </td>
                            </tr>
                        </table>






                        <div id="infoArmure" style="display:block">
                            <table  width=100%>
                                <tr>
                                    <td width=15%>
                                        <label for="matiere">Matière :   </label>
                                    </td>
                                    <td>
                                        <input type="text" name="matiere" maxlength="30" value="<?php echo $matiere?>"></input>
                                        <br>
                                    </td>                              
                                </tr>
                                <tr>                             
                                    <td width=15%>
                                        <label for="taille">Taille : </label>
                                    </td>
                                    <td>
                                        <div class="input-number">
                                            <button type="button" aria-label="Minus" onclick="javascript:IncrementNumber(-1, 'taille')"></button>
                                            <input type="number" name="taille" id="taille" readonly aria-label="Alternative"  style="width: 85px; height: 100px; font-size: xx-large;" value="<?php echo $taille?>">
                                            <button type="button" aria-label="Plus" onclick="javascript:IncrementNumber(1, 'taille')"></button>
                                        </div>
                                    </td>
                                </tr>
                            </table> 
                        </div>


                        
                        <div id="infoArme" style="display:none">
                                    <table  width=100%>
                                        <tr>
                                            <td>
                                                <label for="typemunition">Type Munition: </label>
                                            </td>
                                            <td>
                                                <select id="typemunition" name="typemunition">
                                                <?php
                                                    foreach($listArme as $objet){
                                                        echo '<option value="'.$objet[0].'">'.$objet[1].'</option>';                                   
                                                    }
                                                ?>
                                                </select>
                                            </td>                              
                                        </tr>                             
                                        <tr>
                                            <td width=15%>
                                                <label for="genre">Genre : </label>
                                            </td>
                                            <td>
                                                <input type="text" name="genre" value="<?php echo $genre?>">
                                                <br>
                                            </td>                              
                                        </tr>
                                        <tr>
                                            <td width=15% nowrap>
                                                <label for="efficacite">Efficacite : </label>
                                            </td>
                                            <td>
                                                <div class="input-number">
                                                    <button type="button" aria-label="Minus" onclick="javascript:IncrementNumber(-1, 'efficacite')"></button>
                                                    <input type="number" name="efficacite" id="efficacite" readonly aria-label="Alternative"  style="width: 85px; height: 100px; font-size: xx-large;" value="<?php echo $efficacite?>">
                                                    <button type="button" aria-label="Plus" onclick="javascript:IncrementNumber(1, 'efficacite')"></button>
                                                </div>
                                            </td>
                                        </tr>
                                    </table> 
                                </div>



                        <div id="infoMedicament" style="display:none;">
                            <table width=100%>
                                <tr>
                                    <td width=15%>
                                        <label for="effet">Effet : </label>
                                    </td>
                                    <td>
                                        <input type="text" name="effet" maxlength="150" value="<?php echo $effet ?>">
                                        <br>
                                    </td>
                                </tr>
                                <tr>                   
                                    <td width=15%>
                                        <label for="dureeeffet">Duree Effet : </label>
                                    </td>
                                    <td>
                                        <div class="input-number">
                                            <button type="button" aria-label="Minus" onclick="javascript:IncrementNumber(-1, 'dureeeffet')"></button>
                                            <input type="number" name="dureeeffet" id="dureeeffet" readonly aria-label="Alternative" type="number" name="" id="wijfwi" style="width: 85px; height: 100px; font-size: xx-large;" value="<?php echo $dureeeffet ?>">
                                            <button type="button" aria-label="Plus" onclick="javascript:IncrementNumber(1, 'dureeeffet')"></button>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>


                        <div id="infoMunition" style="display:none">
                            <table  width=100%>
                                <tr>                                
                                    <td width=15% nowrap>
                                        <label for="calibre">Calibre : </label>
                                    </td>
                                    <td>
                                        <div class="input-number">
                                            <button type="button" aria-label="Minus" onclick="javascript:IncrementNumber(-1, 'calibre')"></button>
                                                <input type="number" name="calibre" id="calibre" readonly aria-label="Alternative" value="<?php echo $calibre?>">
                                            <button type="button" aria-label="Plus" onclick="javascript:IncrementNumber(1, 'calibre')"></button>
                                        </div>
                                    </td>
                                </tr>
                            </table>    
                        </div>
                        

                        <div id="infoNourriture" style="display:none">
                            <table  width=100%>
                                <tr>
                                    <td>
                                        <label for="pointdevie">Point De Vie : </label>
                                    </td>
                                    <td>
                                        <div class="input-number">
                                            <button type="button" aria-label="Minus" onclick="javascript:IncrementNumber(-1, 'pointdevie')"></button>
                                            <input type="number" name="pointdevie" id="pointdevie" readonly aria-label="Alternative" value="<?php echo $pointdevie?>">
                                            <button type="button" aria-label="Plus" onclick="javascript:IncrementNumber(1, 'pointdevie')"></button>
                                        </div>
                                    </td>
                                </tr>
                            </table>    
                        </div>


                        <script type="text/javascript">
                            document.getElementById("type").value = "<?php echo $type?>";
                            changeType();
                        </script> 
                        <div style="color:#ff9999; background-color:#373737">
                            <?php echo $messErreur?>
                        </div>


                    </div>
                </div>
            </form>
        </div>
</body>
</html>