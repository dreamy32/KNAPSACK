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
        return $e->getMessage();
    }
}

function AfficherItemsVenteTri($tri, $nbEtoiles, $type, $ordre)
{
    Connexion();
    global $pdo;
    mysqli_set_charset($pdo, "utf8mb4");

    $nbEtoilesWHERE = "";

    if ($nbEtoiles != null) {
        $nbEtoilesWHERE = "AND (SELECT MoyenneEvaluations(IdItems)) = $nbEtoiles";
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
        echo $e->getMessage();
        return $e->getMessage();
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
?>