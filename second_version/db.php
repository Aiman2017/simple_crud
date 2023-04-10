<?php

function connect()
{
    return new PDO('mysql:hostname=localhost;dbname=crud', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

}

function query()
{
    $con = connect();
    $statement = $con->prepare('SELECT * FROM product');
    $statement->execute();
    if ($statement) {
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    return false;
}


