<!-- Fichier Php de tout ce qui concerne la BD -->
<?php
/* Fonction de connexion */
/* Doit être appeler en premier dans chaque fonction suivis de global $pdo;*/
function Connexion()
{
    session_start();
    $host = '127.0.0.1';
    $db = 'KNAPSACKDB';
    $user = 'super';
    $pass = 'ourServer!';
    $charset = 'utf8';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        global $pdo; // Variable importante qui seras utile dans toute les fonctions 
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
    return null;
}
function AjouterJoueur($alias, $mdp, $nom, $prenom, $courriel)
{
    Connexion();
    global $pdo;
    try {
        $sqlProcedure = "CALL AjouterJoueur(:alias,:mdp,:nom,:prenom,:courriel,1000,100,50)";
        $stmt = $pdo->prepare($sqlProcedure);
        $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
        $stmt->bindParam(':mdp', $mdp, PDO::PARAM_STR);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $stmt->bindParam(':courriel', $courriel, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}






function ValiderIdentité($alias, $mdp)
{
    Connexion();
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT ValiderIdentité(:pAlias, :pMdp)", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':pAlias', $alias, PDO::PARAM_STR);
        $stmt->bindParam(':pMdp', $mdp, PDO::PARAM_STR);
        $stmt->execute();
        $etat = 0;

        if ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
            $etat = $donnee[0];
        }
        $stmt->closeCursor();
        return $etat;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}






// Ne fonctionne pas, Creation d'un select 
function AfficherInfosJoueur($alias)
{
    Connexion();
    global $pdo;
    try {
        $stmt = $pdo->prepare("CALL AfficherInfosJoueur(:alias)", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
        $stmt->execute();
        $info = [];
        while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
            array_push($info, $donnee[0]);/* Id*/
            array_push($info, str_replace("'", '-', $donnee[1]));
            array_push($info, $donnee[2]);
            array_push($info, $donnee[3]);
            array_push($info, $donnee[4]);
            array_push($info, $donnee[5]);
            array_push($info, str_replace("\'", '-', $donnee[6]));
            array_push($info, $donnee[7]);
            array_push($info, $donnee[8]);
            array_push($info, $donnee[9]);
            array_push($info, $donnee[10]);
            array_push($info, $info);
        }
        $stmt->closeCursor();
        return $info;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
function AfficherJoueurId($id)
{
    Connexion();
    global $pdo;
    try {
        $stmt = $pdo->prepare("CALL AfficherJoueurId(:id)", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $info = [];
        while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
            array_push($info, $donnee[0]);/* Id*/
            array_push($info, str_replace("'", '-', $donnee[1]));
            array_push($info, $donnee[2]);
            array_push($info, $donnee[3]);
            array_push($info, $donnee[4]);
            array_push($info, $donnee[5]);
            array_push($info, str_replace("\'", '-', $donnee[6]));
            array_push($info, $donnee[7]);
            array_push($info, $donnee[8]);
            array_push($info, $donnee[9]);
            array_push($info, $donnee[10]);
            array_push($info, $info);
        }
        $stmt->closeCursor();
        return $info;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

function AfficherItemsVente($type = '%')
{
    Connexion();
    global $pdo;
    mysqli_set_charset($pdo, "utf8mb4");
    try {
        $stmt = $pdo->prepare("CALL AfficherItemsVente(:type)", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->execute();
        $info = [];
        while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
            $rangee = [];
            array_push($rangee, $donnee[0]);
            array_push($rangee, $donnee[1]);
            array_push($rangee, $donnee[2]);
            array_push($rangee, $donnee[3]);
            array_push($rangee, $donnee[4]);
            array_push($rangee, $donnee[5]);
            array_push($rangee, $donnee[6]);
            array_push($rangee, $donnee[7]);
            array_push($info, $rangee);
        }
        $stmt->closeCursor();
        return $info;
    } catch (PDOException $e) {
        echo "<span id='snackbar'> 
        <img src='images/red_exclamation.png' alt='errorToastIcon'> &nbsp;
        Des erreurs sont survenus
    </span>
    <script>Snackbar();</script>";      
    }
}

function AfficherItemsVenteTri($tri, $nbEtoiles, $type, $ordre)
{
    Connexion();
    global $pdo;
    mysqli_set_charset($pdo, "utf8mb4");

    $nbEtoilesWHERE = "";

    if ($nbEtoiles != null) {
        $nbEtoilesWHERE = "AND (SELECT MoyenneEvaluationsCeil(IdItems)) = $nbEtoiles";
    }

    $typeWHERE = "";

    if ($type != null) {
        $typeArray = explode('-', $type);

        if (count($typeArray) >= 2) {
            $count = 0;

            foreach ($typeArray as $typeNom) {
                if ($count == 0) {
                    if ($nbEtoilesWHERE != null) {
                        $typeWHERE = " AND (type = '$typeNom'";
                    } else {
                        $typeWHERE = "AND (type = '$typeNom'";
                    }
                } else {
                    $typeWHERE .= " OR type = '$typeNom'";
                }

                $count++;
            }

            $typeWHERE .= ")";
        } else {
            if ($nbEtoilesWHERE != null) {
                $typeWHERE = " AND type = '$type'";
            } else {
                $typeWHERE = "AND type = '$type'";
            }
        }
    }

    $triORDERBY = "";

    if ($tri == null) {
        $triORDERBY = "ORDER BY type, poids, prixUnitaire";
        if (($nbEtoilesWHERE != null || $typeWHERE != null)) {
            $triORDERBY = " ORDER BY type, poids, prixUnitaire";
        }
    }

    if ($tri != null) {
        $triORDERBY = "ORDER BY " . $tri;
        if (($nbEtoilesWHERE != null || $typeWHERE != null)) {
            $triORDERBY = " ORDER BY " . $tri;
        }
    }

    if ($triORDERBY != "") {
        $ordre = " " . $ordre;
    } else {
        $ordre = "";
    }

    //echo $nbEtoilesWHERE . $typeWHERE . $triORDERBY . $ordre;

    try {
        $stmt = $pdo->query("SELECT * FROM Items WHERE estEnVente = 1 " . $nbEtoilesWHERE . $typeWHERE . $triORDERBY . $ordre);
        $stmt->setFetchMode(PDO::FETCH_NUM);

        $info = [];
        while ($donnee = $stmt->fetch()) {
            $rangee = [];
            array_push($rangee, $donnee[0]);
            array_push($rangee, $donnee[1]);
            array_push($rangee, $donnee[2]);
            array_push($rangee, $donnee[3]);
            array_push($rangee, $donnee[4]);
            array_push($rangee, $donnee[5]);
            array_push($rangee, $donnee[6]);
            array_push($rangee, $donnee[7]);
            array_push($info, $rangee);
        }
        $stmt->closeCursor();

        return $info;
    } catch (PDOException $e) {
        echo "<span id='snackbar'> 
        <img src='images/red_exclamation.png' alt='errorToastIcon'> &nbsp;
        Des erreurs sont survenus
    </span>
    <script>Snackbar();</script>";       
    }
}

function AjouterItemPanier($idItem, $nbItem)
{
    Connexion();
    global $pdo;
    try {
        $sqlProcedure = "CALL AjouterItemPanier(:pAlias,:pQte,:pIdItem)";
        $stmt = $pdo->prepare($sqlProcedure);
        $stmt->bindParam(':pAlias', $_SESSION['alias'], PDO::PARAM_STR);
        $stmt->bindParam(':pQte', $nbItem, PDO::PARAM_INT);
        $stmt->bindParam(':pIdItem', $idItem, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
        }catch(PDOException $e){
             $pos = strpos($e->getMessage(),">:");
             $message=$e->getMessage();
             if ($pos!=-1) {
                 $message=substr($e->getMessage(),$pos+7);
            }
            echo "<script type='text/javascript'>alert('$message');</script>";       
        }
}

function ModifierItemPanier($qte, $numItem)
{
    Connexion();
    global $pdo;
    try {
        $sqlProcedure = "CALL ModifierItemPanier(:pAlias,:pQte,:pNumItem)";
        $stmt = $pdo->prepare($sqlProcedure);
        $alias = $_SESSION['alias'];
        $stmt->bindParam(':pAlias', $alias, PDO::PARAM_STR);
        $stmt->bindParam(':pQte', $qte, PDO::PARAM_INT);
        $stmt->bindParam(':pNumItem', $numItem, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        $pos = strpos($e->getMessage(),">:");
        $message=$e->getMessage();
        if ($pos!=-1) {
            $message=substr($e->getMessage(),$pos+7);
        }
        throw new Exception($message);        
    }
}

function SupprimerItemPanier($numItem)
{
    Connexion();
    global $pdo;
    try {
        $sqlProcedure = "CALL SupprimerItemPanier(:pAlias, :pNumItem)";
        $stmt = $pdo->prepare($sqlProcedure);
        $alias = $_SESSION['alias'];
        $stmt->bindParam(':pAlias', $alias, PDO::PARAM_STR);
        $stmt->bindParam(':pNumItem', $numItem, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        $pos = strpos($e->getMessage(),">:");
        $message=$e->getMessage();
        if ($pos!=-1) {
            $message=substr($e->getMessage(),$pos+7);
        }
        throw new Exception($message); 
    }
}



function AfficherPanier($alias)
{
    Connexion();
    global $pdo;
    try {
        $stmt = $pdo->prepare("CALL AfficherPanier(:alias)", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
        $stmt->execute();
        $info = [];

        while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
            $rangee = [];
            array_push($rangee, $donnee[0]);
            array_push($rangee, $donnee[1]);
            array_push($rangee, $donnee[2]);
            array_push($info, $rangee);
        }

        $stmt->closeCursor();
        return $info;
    } catch (PDOException $e) {
        $pos = strpos($e->getMessage(),">:");
        $message=$e->getMessage();
        if ($pos!=-1) {
            $message=substr($e->getMessage(),$pos+7);
        }
        throw new Exception($message); 
    }
}



function AfficherInventaire($idJoueur)
{
    Connexion();
    global $pdo;
    try {
        $stmt = $pdo->prepare("CALL AfficherInventaire(:IdJoueurP)", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':IdJoueurP', $idJoueur, PDO::PARAM_INT);
        $stmt->execute();
        $info = [];

        while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
            $rangee = [];
            array_push($rangee, $donnee[0]);
            array_push($rangee, $donnee[1]);
            array_push($rangee, $donnee[2]);
            array_push($rangee, $donnee[3]);
            array_push($info, $rangee);
        }
        $stmt->closeCursor();
        return $info;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}





function MontantTotalPanier($idJoueur)
{
    Connexion();
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT MontantTotalPanier(:IdJoueur)", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':IdJoueur', $idJoueur, PDO::PARAM_INT);
        $stmt->execute();
        $totalPanier = 0;

        if ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
            $totalPanier = $donnee[0];
        }
        $stmt->closeCursor();
        return $totalPanier;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}



    function PayerPanier($alias)
    {
        Connexion();
        global $pdo;
        try{
            $stmt = $pdo->prepare("CALL PayerPanier(:pAlias)");
            $stmt->bindParam(':pAlias', $alias, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();
        }catch(PDOException $e){
            $pos = strpos($e->getMessage(),">:");
            $message=$e->getMessage();
            if ($pos!=-1) {
                $message=substr($e->getMessage(),$pos+7);
            }
            throw new Exception($message); 
        }
    }



function PoidsSac($alias)
{
    Connexion();
    global $pdo;

    try {

        $stmt = $pdo->prepare("SELECT PoidsSac(:pAlias)", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':pAlias', $alias, PDO::PARAM_STR);
        $stmt->execute();
        $poids = 0;

        if ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
            $poids = $donnee[0];
        }
        $stmt->closeCursor();
        return $poids;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}



function PoidsPanier($alias)
{
    Connexion();
    global $pdo;

    try {

        $stmt = $pdo->prepare("SELECT PoidsPanier(:pAlias)", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':pAlias', $alias, PDO::PARAM_STR);
        $stmt->execute();
        $poids = 0;

        if ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
            $poids = $donnee[0];
        }
        $stmt->closeCursor();
        return $poids;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}





function PoidsMax($alias)
{
    Connexion();
    global $pdo;

    try {

        $stmt = $pdo->prepare("SELECT poidsMaxTransport FROM Joueurs WHERE alias = :pAlias", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':pAlias', $alias, PDO::PARAM_STR);
        $stmt->execute();
        $poidsMax = 0;

        if ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
            $poidsMax = $donnee[0];
        }
        $stmt->closeCursor();
        return $poidsMax;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}



function Dexterite($alias)
{
    Connexion();
    global $pdo;

    try {

        $stmt = $pdo->prepare("SELECT dexterite FROM Joueurs WHERE alias = :pAlias", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':pAlias', $alias, PDO::PARAM_STR);
        $stmt->execute();
        $dexterite = 0;

        if ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
            $dexterite = $donnee[0];
        }
        $stmt->closeCursor();
        return $dexterite;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}


function ChercherInfoItemSelonId($idItem)
{
    $Items = AfficherItemsVente('%');
    foreach ($Items as $objet) {
        if ($objet[0] == $idItem) {
            return $objet;
        }
    }
}

function AjouterArgentToutLeMonde($soldeADonner){
    Connexion();
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE Joueurs SET Solde = Solde + :pSoldeADonner", array(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION));
        $stmt->bindParam(':pSoldeADonner', $soldeADonner, PDO::FETCH_NUM);
        $stmt->execute();
        return $stmt->rowCount();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}


function AjouterArmureMagasin($nom, $qte, $matiere, $taille, $prixU, $poids, $description)
{
    Connexion();
    global $pdo;
    $description=trim($description);
    $estEnVente = 1;
    try {
        $sqlProcedure = "CALL AjouterArmure(:pNom, :pQte, :pPrixU, :pPoids, :pDescription, :pEstEnVente, :pMatiere, :pTaille)";
        $stmt = $pdo->prepare($sqlProcedure);
        $stmt->bindParam(':pNom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':pQte', $qte, PDO::PARAM_INT);
        $stmt->bindParam(':pPrixU', $prixU, PDO::PARAM_STR); 
        $stmt->bindParam(':pPoids', $poids, PDO::PARAM_INT); 
        $stmt->bindParam(':pDescription', $description, PDO::PARAM_STR);
        $stmt->bindParam(':pEstEnVente', $estEnVente, PDO::PARAM_INT); //bit
        $stmt->bindParam(':pMatiere', $matiere, PDO::PARAM_STR);
        $stmt->bindParam(':pTaille', $taille, PDO::PARAM_INT); 
        $stmt->execute();
        $stmt->closeCursor();  
 
        $stmt = $pdo->prepare("SELECT IdItems from Items order by IdItems desc limit 1;");
        $stmt->execute(); 
        $id = $stmt->fetch(PDO::FETCH_NUM);
        return $id;
    }catch(PDOException $e){
        $pos = strpos($e->getMessage(),">:");
        $message=$e->getMessage();
        if ($pos!=-1) {
            $message=substr($e->getMessage(),$pos+7);
        }
        throw new \PDOException($message, (int)$e->getCode());
    }
}





function AjouterArmeMagasin($nom, $qte, $efficacite, $genre, $prixU, $poids, $description, $idMunition)
{
    Connexion();
    global $pdo;
    $description=trim($description);
    $estEnVente = 1;
    try {
        $sqlProcedure = "CALL AjouterArmes(:pNom, :pQte, :pPrixU, :pPoids, :pDescription, :pEstEnVente, :pEfficacite, :pGenre, :pIdMunition)";
        $stmt = $pdo->prepare($sqlProcedure);
        $stmt->bindParam(':pNom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':pQte', $qte, PDO::PARAM_INT);
        $stmt->bindParam(':pPrixU', $prixU, PDO::PARAM_STR); 
        $stmt->bindParam(':pPoids', $poids, PDO::PARAM_INT); 
        $stmt->bindParam(':pDescription', $description, PDO::PARAM_STR);
        $stmt->bindParam(':pEstEnVente', $estEnVente, PDO::PARAM_INT); 
        $stmt->bindParam(':pEfficacite', $efficacite, PDO::PARAM_INT); 
        $stmt->bindParam(':pGenre', $genre, PDO::PARAM_STR);
        $stmt->bindParam(':pIdMunition', $idMunition, PDO::PARAM_INT); 
        $stmt->execute();
        $stmt->closeCursor();   

        $stmt = $pdo->prepare("SELECT IdItems from Items order by IdItems desc limit 1;");
        $stmt->execute(); 
        $id = $stmt->fetch(PDO::FETCH_NUM);

        return $id;
    }catch(PDOException $e){
        $pos = strpos($e->getMessage(),">:");
        $message=$e->getMessage();
        if ($pos!=-1) {
            $message=substr($e->getMessage(),$pos+7);
        }
        throw new \PDOException($message, (int)$e->getCode());       
    }
}


function AjouterMunitionMagasin($nom, $qte, $calibre, $prixU, $poids, $description)
{
    Connexion();
    global $pdo;
    $description=trim($description);
    $estEnVente = 1;
    try {
        $sqlProcedure = "CALL AjouterMunitions(:pNom, :pQte, :pPrixU, :pPoids, :pDescription, :pEstEnVente, :pCalibre)";
        $stmt = $pdo->prepare($sqlProcedure);
        $stmt->bindParam(':pNom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':pQte', $qte, PDO::PARAM_INT);
        $stmt->bindParam(':pPrixU', $prixU, PDO::PARAM_STR); 
        $stmt->bindParam(':pPoids', $poids, PDO::PARAM_INT); 
        $stmt->bindParam(':pDescription', $description, PDO::PARAM_STR);
        $stmt->bindParam(':pEstEnVente', $estEnVente, PDO::PARAM_INT); 
        $stmt->bindParam(':pCalibre', $calibre, PDO::PARAM_INT); 
        $stmt->execute();
        $stmt->closeCursor();   

        $stmt = $pdo->prepare("SELECT IdItems from Items order by IdItems desc limit 1;");
        $stmt->execute(); 
        $id = $stmt->fetch(PDO::FETCH_NUM);
        return $id;
    }catch(PDOException $e){
        $pos = strpos($e->getMessage(),">:");
        $message=$e->getMessage();
        if ($pos!=-1) {
            $message=substr($e->getMessage(),$pos+7);
        }
        throw new \PDOException($message, (int)$e->getCode());
    }
}



function AjouterMedicamentMagasin($nom, $qte, $prixU, $poids, $description, $effet, $dureeEffet)
{
    Connexion();
    global $pdo;
    $description=trim($description);
    $estEnVente = 1;
    try {
        $sqlProcedure = "CALL AjouterMedicament(:pNom, :pQte, :pPrixU, :pPoids, :pDescription, :pEstEnVente, :pEffet, :pDureeEffet)";
        $stmt = $pdo->prepare($sqlProcedure);
        $stmt->bindParam(':pNom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':pQte', $qte, PDO::PARAM_INT);
        $stmt->bindParam(':pPrixU', $prixU, PDO::PARAM_STR); 
        $stmt->bindParam(':pPoids', $poids, PDO::PARAM_INT); 
        $stmt->bindParam(':pDescription', $description, PDO::PARAM_STR);
        $stmt->bindParam(':pEstEnVente', $estEnVente, PDO::PARAM_INT); 
        $stmt->bindParam(':pEffet', $effet, PDO::PARAM_INT); 
        $stmt->bindParam(':pDureeEffet', $dureeEffet, PDO::PARAM_INT); 
        $stmt->execute();
        $stmt->closeCursor();  

        $stmt = $pdo->prepare("SELECT IdItems from Items order by IdItems desc limit 1;");
        $stmt->execute(); 
        $id = $stmt->fetch(PDO::FETCH_NUM);
        return $id;
    }catch(PDOException $e){
        $pos = strpos($e->getMessage(),">:");
        $message=$e->getMessage();
        if ($pos!=-1) {
            $message=substr($e->getMessage(),$pos+7);
        }
        throw new \PDOException($message, (int)$e->getCode());       
    }
}



function AjouterNourritureMagasin($nom, $qte, $prixU, $poids, $description, $pointDeVie)
{
    Connexion();
    global $pdo;
    $description=trim($description);
    try {
        $estEnVente = 1;
        
        $sqlProcedure = "CALL AjouterNourriture(:pNom, :pQte, :pPrixU, :pPoids, :pDescription, :pEstEnVente, :pPointDeVie)";
        $stmt = $pdo->prepare($sqlProcedure);
        $stmt->bindParam(':pNom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':pQte', $qte, PDO::PARAM_INT);
        $stmt->bindParam(':pPrixU', $prixU, PDO::PARAM_STR); 
        $stmt->bindParam(':pPoids', $poids, PDO::PARAM_INT); 
        $stmt->bindParam(':pDescription', $description, PDO::PARAM_STR);
        $stmt->bindParam(':pEstEnVente', $estEnVente, PDO::PARAM_INT); //bit
        $stmt->bindParam(':pPointDeVie', $pointDeVie, PDO::PARAM_INT); 
        $stmt->execute();
        $stmt->closeCursor();   
        $stmt = $pdo->prepare("SELECT IdItems from Items order by IdItems desc limit 1;");
        $stmt->execute(); 
        $id = $stmt->fetch(PDO::FETCH_NUM);
        return $id;
    }catch(PDOException $e){
        $pos = strpos($e->getMessage(),">:");
        $message=$e->getMessage();
        if ($pos!=-1) {
            $message=substr($e->getMessage(),$pos+7);
        }
        throw new \PDOException($message, (int)$e->getCode());    
    }
}



function AfficherListeArme(){
    Connexion();
    global $pdo;
    mysqli_set_charset($pdo, "utf8mb4");

        $stmt = $pdo->prepare("SELECT IdItems, Nom FROM Items WHERE type='M'", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->execute();
        $info = [];
        while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
            $rangee = [];
            array_push($rangee, $donnee[0]);
            array_push($rangee, $donnee[1]);
            array_push($info, $rangee);
        }
        $stmt->closeCursor();
        return $info;
}







function AjouterÉvaluation($idJoueur, $idItem,$commentaire,$nbEtoile){
    Connexion();
    global $pdo;
    try {
        $sqlProcedure = "CALL AjouterÉvaluation(:pIdItem,:pIdJoueur,:pCommentaire,:pNbEtoile)";
        $stmt = $pdo->prepare($sqlProcedure);
        $stmt->bindParam(':pIdItem', $idItem, PDO::PARAM_INT);
        $stmt->bindParam(':pIdJoueur', $idJoueur, PDO::PARAM_INT);
        $stmt->bindParam(':pCommentaire', $commentaire, PDO::PARAM_STR);
        $stmt->bindParam(':pNbEtoile', $nbEtoile, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
        }catch(PDOException $e){
            $pos = strpos($e->getMessage(),">:");
            $message=$e->getMessage();
            if ($pos!=-1) {
                $message=substr($e->getMessage(),$pos+7);
            }
            echo "<script type='text/javascript'>alert('$message');</script>";       
        }
}
function HasAlreadyBought($id, $item)
{
    // 0 -> false
    // 1 -> true

    Connexion();
    global $pdo;

        $stmt = $pdo->prepare("SELECT EXISTS (SELECT Items_IdItems FROM Inventaire 
        WHERE Items_IdItems = :pIdItem and Joueurs_IdJoueur = :pIdJoueur);", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':pIdItem', $item, PDO::PARAM_INT);
        $stmt->bindParam(':pIdJoueur', $id, PDO::PARAM_INT);
        $stmt->execute();

        $hasBought = false;

        if ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
            $etat = $donnee[0];
        }

        if ($etat == 1){
            $hasBought = true;
        }
        $stmt->closeCursor();
        return $hasBought;
}

function AfficherEvaluations($idItem){
    Connexion();
    global $pdo;
    mysqli_set_charset($pdo, "utf8mb4");

        $stmt = $pdo->prepare("SELECT * FROM Evaluations WHERE Items_IdItems = :pIdItem ORDER BY idEvaluations DESC", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':pIdItem', $idItem, PDO::PARAM_INT);
        $stmt->execute();
        $info = [];
        while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
            $rangee = [];
            array_push($rangee, $donnee[0]);
            array_push($rangee, $donnee[1]);
            array_push($rangee, $donnee[2]);
            array_push($rangee, $donnee[3]);
            array_push($rangee, $donnee[4]);
            array_push($info, $rangee);
        }
        $stmt->closeCursor();
        return $info;
}

function SupprimerEvaluation($idItem)
{
    try{
    Connexion();
    global $pdo;
    mysqli_set_charset($pdo, "utf8mb4");

        $stmt = $pdo->prepare("DELETE FROM Evaluations WHERE Items_IdItems = :pIdItem", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':pIdItem', $idItem, PDO::PARAM_INT);
        $stmt->execute();

        
    }
    catch(PDOException $e){
    $pos = strpos($e->getMessage(),">:");
    $message=$e->getMessage();
    if ($pos!=-1) {
        $message=substr($e->getMessage(),$pos+7);
    }
    echo "<script type='text/javascript'>alert('$message');</script>";       
    }
}
function DeleteEval($idEval){
    Connexion();
    global $pdo;
    try {
        $sqlProcedure = "CALL DeleteEval(:pidEval)";
        $stmt = $pdo->prepare($sqlProcedure);
        $stmt->bindParam(':pidEval', $idEval, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
        }catch(PDOException $e){
            $pos = strpos($e->getMessage(),">:");
            $message=$e->getMessage();
            if ($pos!=-1) {
                $message=substr($e->getMessage(),$pos+7);
            }
            echo "<script type='text/javascript'>alert('$message');</script>";       
        }
}
function PeutDeleteEvaluation($idJoueur){
    $infoJoueur = AfficherInfosJoueur($_SESSION['alias']);
    if($infoJoueur[0]==$idJoueur or $infoJoueur[10] == 1){
        return True;
    }
    return False;
}
function MoyenneEvaluations($idItem)
{
    Connexion();
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT MoyenneEvaluations(:idItem)", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':idItem', $idItem, PDO::PARAM_INT);
        $stmt->execute();
        $moyenne = 0;

        if ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
            $moyenne = $donnee[0];
        }
        $stmt->closeCursor();
        return $moyenne;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
function NombreEvaluations($idItem)
{
    Connexion();
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT NombreEvaluations(:idItem)", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':idItem', $idItem, PDO::PARAM_INT);
        $stmt->execute();
        $nbEval = 0;

        if ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
            $nbEval = $donnee[0];
        }
        $stmt->closeCursor();
        return $nbEval;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
function PourcentageHistogramme($idItem,$nbEtoile){
    Connexion();
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT PourcentageHistogramme(:idItem,:nbEtoile)", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':idItem', $idItem, PDO::PARAM_INT);
        $stmt->bindParam(':nbEtoile', $nbEtoile, PDO::PARAM_INT);
        $stmt->execute();
        $nbEval = 0;

        if ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
            $nbEval = $donnee[0];
        }
        $stmt->closeCursor();
        return $nbEval;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
function AfficherAliasJoueur(){
    Connexion();
    global $pdo;
    mysqli_set_charset($pdo, "utf8mb4");

        $stmt = $pdo->prepare("SELECT * FROM Joueurs ORDER BY alias", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->execute();
        $info = [];
        while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
            $rangee = [];
            array_push($rangee, $donnee[4]);
            array_push($info, $rangee);
        }
        $stmt->closeCursor();
        return $info;
}
function AjouterArgentJoueur($soldeADonner,$aliasJoueur){
    Connexion();
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE Joueurs SET Solde = Solde + :pSoldeADonner WHERE alias = :pAliasJoueur", array(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION));
        $stmt->bindParam(':pSoldeADonner', $soldeADonner, PDO::FETCH_NUM);
        $stmt->bindParam(':pAliasJoueur', $aliasJoueur,PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
function ModifierAliasNomPrenom($aliasCourrant,$alias,$prenom,$nom){
    Connexion();
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE Joueurs SET alias = :pAlias, prenom = :pPrenom, nom = :pNom WHERE alias=:pAliasCourant", array(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION));
        $stmt->bindParam(':pAlias', $alias, PDO::PARAM_STR);
        $stmt->bindParam(':pPrenom', $prenom, PDO::PARAM_STR);
        $stmt->bindParam(':pNom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':pAliasCourant', $aliasCourrant, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
function ModifierMotPasse($aliasCourrant,$motDePasse){
    Connexion();
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE Joueurs SET motDePasse = :pMotDePasse WHERE alias=:pAliasCourant", array(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION));
        $stmt->bindParam(':pMotDePasse', $motDePasse, PDO::PARAM_STR);
        $stmt->bindParam(':pAliasCourant', $aliasCourrant, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

function ChoisirAléatoirementEnigme()
{
    Connexion();
    global $pdo;

    $stmt = $pdo->prepare("SELECT COUNT(idEnigme) FROM Enigme;");
        $stmt->execute(); 
        $count = $stmt->fetch(PDO::FETCH_NUM);

    $idEnigme = rand(1,$count);
}
?>