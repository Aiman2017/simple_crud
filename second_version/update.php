<?php
require_once 'db.php';
require_once 'functions.php';

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
extract($products);
$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image'] ?? null;
    $date = date('Y-m-d H:s:i');
    $imagePath = $products['image'];

    if (!is_dir('images')) {
        mkdir('images');
    }
    if ($image['size'] > 1250000) {
        $errors[] = 'The file is too large';
    }
    $imgEx = pathinfo($image['name'], PATHINFO_EXTENSION);
    $imgExtension = ['jpg', 'png', 'jpeg'];
    if (!in_array($imgEx, $imgExtension) && !empty($image['name'])) {
        $errors[] = 'The file can\'\t be accept';
    }

    if ($image && $image['tmp_name']) {
        $nameFolderImage = (string) substr("IMG-${image['name']}", 0, -4);
        $imagePath = 'images/' . randomString(6).($nameFolderImage) .'/' . $image['name'];
        mkdir(dirname($imagePath));
        move_uploaded_file($image['tmp_name'], $imagePath);

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
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <style>
        * {
            padding: 10px;
        }
    </style>
    <title>Document</title>
</head>
<body>
<p>
    <a href="index.php" class="btn btn-secondary"> Go Back to Product</a>
</p>
<form action="" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label>Product Image</label><br>
        <input type="file" name="image">
    </div>
    <?php if (!empty($errors)) : ?>
        <div class="alert alert-danger" role="alert">
            <?php foreach ($errors as $error): ?>
                <?= $error . '<br>' ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="form-group">
        <label for="title">title</label>
        <input type="text" class="form-control" value="<?= $title ?? '' ?>" name="title" id="title" placeholder="title">
    </div>
    <div class="form-group">
        <label for="description">description</label>
        <textarea class="form-control" name="description" id="description"
                  placeholder="description"><?= $description ?? '' ?></textarea>
    </div>
    <div class="form-group">
        <label for="price">price</label>
        <input type="number" step="0.1" class="form-control" value="<?= $price ?? '' ?>" name="price" id="price">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
</body>
</html>
