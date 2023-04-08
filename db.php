<?php
function show($stuff)
{
    echo '<pre>';
    var_dump($stuff);
    echo '</pre>';
}

function redirect($path)
{
    header("Location: ${path}.php");
}

function randomString($str): string
{
    $string = '';
    $char ='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    for($i = 0 ; $i < $str; $i++) {
        $index = rand(0, strlen($char) - 1);
        $string .= $char[$index];
    }
    return $string;
}

function connect()
{
    return new PDO('mysql:hostname=localhost;dbname=crud', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

}

function query()
{
    $con = connect();
    $statement = $con->query('SELECT * FROM product');
    if ($statement) {
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    return false;
}
