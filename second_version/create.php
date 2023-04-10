<?php
require_once 'functions.php';
require_once 'db.php';
$con = connect();
$products = [
        'image'=> ''
];

$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $date = date('Y-m-d');
    $image = $_FILES['image'] ?? null;
    $nameFolderImage = substr($image['name'], 0, -4);

    if ($title == '' || $price == '') {
        $errors[] = 'some field is not inserted';
    }

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
    if (empty($errors)) {
        if ($image) {
            $nameFolderImage = (string) substr("IMG-${image['name']}", 0, -4);
            $imagePath = 'images/' . $nameFolderImage .'/' . $image['name'];
            mkdir(dirname($imagePath));
            move_uploaded_file($image['tmp_name'], $imagePath);
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

<?php require_once 'views/template/header.php'?>
<p>
    <a href="index.php" class="btn btn-secondary"> Go Back to Product</a>
</p>

<?php require_once 'views/products/form.php'?>
</body>
</html>