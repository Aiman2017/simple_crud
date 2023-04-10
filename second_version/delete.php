<?php
require_once 'db.php';
require_once 'functions.php';
// fist способ
//$id = $_GET['id'] ?? null;
//if (!$id) {
//    redirect('index');
//    exit();
//}
//
//$query = connect()->prepare('DELETE FROM product WHERE id = :id');
//$query->bindValue(':id', $id);
//if ($query->execute()) {
//    redirect('index');
//    exit();
//}

// второй способ

// fist способ
$id = $_POST['id'] ?? null;
if (!$id) {
    redirect('index');
    exit();
}
$query = connect()->prepare('DELETE FROM product WHERE id = :id');
$query->bindValue(':id', $id);
if ($query->execute()) {
    redirect('index');
    exit();
}
