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
$products_img = explode(',', $products['image']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image'] ?? null;
    $imagePath = $products['image'];

    if (!is_dir('images')) {
        mkdir('images');
    }
    $imgEx = pathinfo($image['name'], PATHINFO_EXTENSION);
    $imgExtension = ['jpg', 'png', 'jpeg'];
    if ($image['size'] > 125000) {
        $errors['error'] = 'The file is too large';
    }
    if (in_array($imgEx, $imgExtension)) {
        $imagePath = 'images/' . 'IMG-' . $image['name'];

        if ($image) {
            if ($products['image']) {
                unlink($products['image']);
            }

            move_uploaded_file($image['tmp_name'], $imagePath);
        }
    }else {
        $errors['error'] = 'The file can\'\t be accept';
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

        $check = $statement->execute();
        if ($check !== false) {
            header('Location:index.php?imagePath='.$imagePath);

        }
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
            padding:10px;
        }
        img {
            width: 80px;
        }
    </style>
    <title>Document</title>
</head>
<body>
<p>
    <a href="index.php" class="btn btn-secondary"> Go Back to Product</a>
</p>
<form action="" method="post" enctype="multipart/form-data">
    <?php if ($products['image']) :?>
        <img src="<?= $products['image']?>" alt="">
    <?php endif;?>
    <div class="form-group">
        <label>Product Image</label><br>
        <input type="file" name="image">
    </div>
    <?php if (!empty($errors)) :?>
        <div class="alert alert-danger" role="alert">
            <?= $errors['error']?>
        </div>
    <?php endif;?>
    <div class="form-group">
        <label for="title">title</label>
        <input type="text" class="form-control" value="<?= $products['title'] ?? ''?>" name="title" id="title" placeholder="title">
    </div>
    <div class="form-group">
        <label for="description">description</label>
        <textarea class="form-control" name="description"  id="description"  placeholder="description"><?= $products['description']  ??''?></textarea>
    </div>
    <div class="form-group">
        <label  for="price">price</label>
        <input type="number" step="0.1" class="form-control"  value="<?= $products['price'] ?? ''?>" name="price" id="price">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
</body>
</html>