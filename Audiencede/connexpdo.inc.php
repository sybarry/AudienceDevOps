<?php
function connexpdo(string $db)
{
    $sgbd = "mysql"; // choix de MySQL
    $host = getenv('db_host');
    $charset = "UTF8";
    $user =getenv('db_user'); // user id
    $pass =getenv('db_pass'); // password
    try {
        $pdo = new PDO("$sgbd:host=$host;dbname=$db;charset=$charset", $user, $pass);
        // force le lancement d'exception en cas d'erreurs d'exécution de requêtes SQL
        // via eg. $pdo->query()
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo $e->getMessage();
        echo $e->getCode();
        exit;
    }
}
?>
