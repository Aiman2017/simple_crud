<?php
require_once 'db.php';

$id = $_GET['id'];
if (!$id) {
    redirect('index');
    exit();
}
$con = connect();

$statement = $con->prepare(' SELECT * FROM product WHERE id =:id');
$statement->bindValue(':id', $id);
$statement->execute();


$products = $statement->fetch(PDO::FETCH_ASSOC);

$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image'] ?? null;
    $imagePath = $products['image'];

    if ($title == '' || $price == '') {
        $errors[] = 'some field is not inserted';
    }

    if (!is_dir('images')) {
        mkdir('images');
    }
    if ($image['size'] > 125000) {
        $errors[] = 'The file is too large';
    }
    $imgEx = pathinfo($image['name'], PATHINFO_EXTENSION);
    $imgExtension = ['jpg', 'png', 'jpeg'];
    if (!in_array($imgEx, $imgExtension) && !empty($image['name'])) {
        $errors[] = 'The file can\'\t be accept';
    }
    if (empty($errors)) {

        $statement = $con->prepare("UPDATE product SET title = :title, 
                                        image = :image, 
                                        description = :description, 
                                        price = :price WHERE id = :id");
        $statement->bindValue(':title', $title);
        $statement->bindValue(':image', $imagePath);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':price', $price);
        $statement->bindValue(':id', $id);
        $statement->execute();
        redirect('index');
        exit();

    }
}
?>
