<?php

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

function ChoisirAléatoirement()
{
    Connexion();
    global $pdo;

    $stmt = $pdo->prepare("SELECT COUNT(idEnigme) FROM Enigme;");
        $stmt->execute(); 
        $count = $stmt->fetch(PDO::FETCH_NUM);

    $idEnigme = rand(1,$count);
}









?>