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
                array_push($info,$donnee[1]);
                array_push($info,$donnee[2]);
                array_push($info,$donnee[3]);
                array_push($info,$donnee[4]);
                array_push($info,$donnee[5]);
                array_push($info,$donnee[6]);
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
    function AfficherItemsVente(){
        Connexion();
        global $pdo;
        try{
            $stmt = $pdo->prepare("CALL AfficherItemsVente(:type)",array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
            $char = '%';
            $stmt->bindParam(':type', $char, PDO::PARAM_STR);
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

    $test = AfficherItemsVente();
    echo $test[0][0] . $test[0][1];
    /*
    echo $test;
    echo "<br>";
    echo $test[0];
    echo "<br>";
    echo $test[0][0] . $test[0][1] . $test[0][2] . $test[0][3] . $test[0][4];
    echo "<br>";
    echo $test[1][0] . $test[1][1] . $test[1][2] . $test[1][3] . $test[1][4];*/
?>