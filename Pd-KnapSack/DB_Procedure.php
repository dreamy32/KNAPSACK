<!-- Fichier Php de tout ce qui concerne la BD -->

<?php 
    /* Fonction de connexion */
    /* Doit être appeler en premier dans chaque fonction suivis de global $pdo;*/
    function Connexion(){
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
    function AjouterJoueur($alias,$mdp,$nom,$prenom,$courriel){
        Connexion();
        global $pdo;
        try{
            $sqlProcedure = "CALL AjouterJoueur(:alias,:mdp,:nom,:prenom,:courriel,1000,100,50)";
            $stmt = $pdo->prepare($sqlProcedure);
            $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
            $stmt->bindParam(':mdp', $mdp, PDO::PARAM_STR);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindParam(':courriel', $courriel, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }
    // Ne fonctionne pas, Creation d'un select 
    function AfficherInfosJoueur($alias){
        Connexion();
        global $pdo;
        try{
            $stmt = $pdo->prepare("CALL AfficherInfosJoueur(:alias)",array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
            $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
            $stmt->execute();
            $info = [];
            while($donnee = $stmt->fetch(PDO::FETCH_NUM)){
                array_push($info,$donnee[0]);/* Id*/
                array_push($info,str_replace("'",'-',$donnee[1]));
                array_push($info,$donnee[2]);
                array_push($info,$donnee[3]);
                array_push($info,$donnee[4]);
                array_push($info,$donnee[5]);
                array_push($info,str_replace("\'",'-',$donnee[6]));
                array_push($info,$donnee[7]);
                array_push($info,$donnee[8]);
                array_push($info,$donnee[9]);
            }
            $stmt->closeCursor();
            return $info;
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }
    function AfficherItemsVente($type = '%'){
        Connexion();
        global $pdo;
        mysqli_set_charset($pdo, "utf8mb4");
        try{
            $stmt = $pdo->prepare("CALL AfficherItemsVente(:type)",array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
            $stmt->bindParam(':type', $type, PDO::PARAM_STR);
            $stmt->execute();
            $info = [];
            while($donnee = $stmt->fetch(PDO::FETCH_NUM)){
                $rangee = [];
                array_push($rangee,$donnee[0]);
                array_push($rangee,$donnee[1]);
                array_push($rangee,$donnee[2]);
                array_push($rangee,$donnee[3]);
                array_push($rangee,$donnee[4]);
                array_push($rangee,$donnee[5]);
                array_push($rangee,$donnee[6]);
                array_push($rangee,$donnee[7]);
                array_push($info,$rangee);
            }
            $stmt->closeCursor();
            return $info;
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }
    function AjouterItemPanier($idItem,$nbItem){
        Connexion();
        global $pdo;
        try{
            $sqlProcedure = "CALL AjouterItemPanier(:pAlias,:pQte,:pIdItem)";
            $stmt = $pdo->prepare($sqlProcedure);
            $stmt->bindParam(':pAlias', $_SESSION['alias'], PDO::PARAM_STR);
            $stmt->bindParam(':pQte', $nbItem, PDO::PARAM_INT);
            $stmt->bindParam(':pIdItem', $idItem, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }

    function ModifierItemPanier($qte, $numItem){
        Connexion();
        global $pdo;
        try{
            $sqlProcedure = "CALL ModifierItemPanier(:pAlias,:pQte,:pNumItem)";
            $stmt = $pdo->prepare($sqlProcedure);
            $alias = $_SESSION['alias'];
            $stmt->bindParam(':pAlias', $alias, PDO::PARAM_STR);
            $stmt->bindParam(':pQte', $qte, PDO::PARAM_INT);
            $stmt->bindParam(':pNumItem', $numItem, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }

    
    function SupprimerItemPanier($numItem){
        Connexion();
        global $pdo;
        try{
            $sqlProcedure = "CALL SupprimerItemPanier(:pAlias, :pNumItem)";
            $stmt = $pdo->prepare($sqlProcedure);
            $alias = $_SESSION['alias'];
            $stmt->bindParam(':pAlias', $alias, PDO::PARAM_STR);
            $stmt->bindParam(':pNumItem', $numItem, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }


    
    function AfficherPanier($alias){
        Connexion();
        global $pdo;
        
        try{
            
            $stmt = $pdo->prepare("CALL AfficherPanier(:alias)",array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
            $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
            $stmt->execute();
            
            $info = [];
            

            while($donnee = $stmt->fetch(PDO::FETCH_NUM)){
                
                $rangee = [];
                array_push($rangee,$donnee[0]);
                array_push($rangee,$donnee[1]);
                array_push($rangee,$donnee[2]);
                array_push($info,$rangee);
                
            }
            

            $stmt->closeCursor();
            return $info;
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }

    /*
    function MontantTotalPanier($idJoueur)
    {
        Connexion();
        global $pdo;
        try{
            $stmt = $pdo->prepare("CALL MontantTotalPanier(:IdJoueur)",array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
            $stmt->bindParam(':IdJoueur', $idJoueur, PDO::PARAM_STR);
            $stmt->execute();
            $info = [];
            while($donnee = $stmt->fetch(PDO::FETCH_NUM)){
                array_push($info,$donnee[0]);
            }
            $stmt->closeCursor();
            return $info;
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }
    */

    function PayerPanier($alias)
    {
        echo($alias);
        Connexion();
        global $pdo;
        try{
            echo('11111111');
            $stmt = $pdo->prepare("CALL PayerPanier(:pAlias)");
            echo('11111111222222222222');
            $stmt->bindParam(':pAlias', $alias, PDO::PARAM_STR);
            echo('111111112222222222233333333333333333333');
            $stmt->execute();
            echo('111111112222222222233333333333333333333444444444444444444444444444444');
            $stmt->closeCursor();
            echo('1111111122222222222333333333333333333334444444444444444444444444444445555555555555555555555555555555555555555');
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }




    function ChercherInfoItemSelonId($idItem){
        $Items = AfficherItemsVente('%');
        foreach($Items as $objet){
            if($objet[0] == $idItem){
                return $objet;
            }
        }
    }
?>