<?php
require_once 'db.php';
$con = connect();
$errors = [];
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $date = date('Y-m-d H:i:s');
        if ($title == '' || $price == '') {
            $errors['error'] = 'some field is not inserted';
        }

        if (!is_dir('images')) {
            mkdir('images');
        }
        if (empty($errors)) {
            $image = $_FILES['image'] ?? null;
            $imagePath = '';
//             if the image is exists, move it to another file and adding unique name
            if ($image && $image['name']) {
                $imagePath = 'images/' . randomString(10). '/' . $image['name'];
//              moving the uploading images in currently path
                mkdir(dirname($imagePath));
                move_uploaded_file($image['tmp_name'],$imagePath);
            }

            $stm = $con->prepare("INSERT INTO product (title, description, price, image, create_date) VALUES (:title, :description, :price, :image, :date)");

            $stm->bindValue(':title', $title);
            $stm->bindValue(':description', $description);
            $stm->bindValue(':price', $price);
            $stm->bindValue(':image', $imagePath);
            $stm->bindValue(':date', $date);
            $check = $stm->execute();
            if ($check !== false) {
                redirect('index');
                exit();
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
    </style>
    <title>Document</title>
</head>
<body>
<form action="" method="post" enctype="multipart/form-data">

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
        <input type="text" class="form-control" value="<?= $title ?? ''?>" name="title" id="title" placeholder="title">
    </div>
    <div class="form-group">
        <label for="description">description</label>
        <textarea class="form-control" name="description"  id="description"  placeholder="description"><?= $description ??''?></textarea>
    </div>
    <div class="form-group">
        <label  for="price">price</label>
        <input type="number" step="0.1" class="form-control"  value="<?= $price ?? ''?>" name="price" id="price">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
</body>
</html>