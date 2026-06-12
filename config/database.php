<?php
declare(strict_types=1);
return function (): PDO{
    $host = 'localhost';
$db = 'pharma_fefo';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try{
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

    return $pdo;
}
catch (\PDOException $e){
    throw new \PDOException("Erreur de connexion a la base de donnes : " . $e->getMessage());
}
};
