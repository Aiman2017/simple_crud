
<?php
    require_once 'db.php';
    $con = connect();
    $search = $_GET['search'] ?? '';
    if ($search) {
        $find = $con->prepare('SELECT * FROM product WHERE title like :title or price like :price');
        $find->bindValue(':title', "%$search%");
        $find->bindValue(':price', "%$search%");
        $find->execute();
        $products = $find->fetchAll(PDO::FETCH_ASSOC);

    }else {
        $products = query();
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
            padding: 20px;
        }
        img {
            width: 100px;
        }
        .table>tbody {
            vertical-align: middle;
        }
    </style>
    <title>Document</title>
</head>
<body>
<h1>Products</h1>

<?php if ($search):?>
    <p>
        <a href="index.php" class="btn btn-secondary"> Go Back to Product</a>
    </p>
    <?php else :?>
    <a href="create.php" class="btn btn-success">Success</a>

<?php endif;?>
<form>
    <div class="input-group">
        <input type="text" class="form-control rounded" placeholder="Search for products" name="search" value="<?= $search ?? ''?>"/>
        <button type="submit" class="btn btn-outline-primary">search</button>
    </div>
</form>
<table class="table">
    <thead>
    <tr>
        <th scope="col">ID</th>
        <th scope="col">image</th>
        <th scope="col">title</th>
        <th scope="col">description</th>
        <th scope="col">price</th>
        <th scope="col">create date</th>
        <th scope="col">action</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($products)) :?>
    <?php foreach($products as $k => $product) :?>
        <tr>
            <th scope="row"><?php $i = 1; echo $k += $i++ ?></th>
            <td>
                <img src="<?= $product['image']?>" alt="">
            </td>
            <td><?= $product['title']?></td>
            <td><?= $product['description']?></td>
            <td><?= $product['price']?></td>
            <td><?= $product['create_date']?></td>
            <td>
                <a href="update.php?id=<?= $product['id']?>" type="button" class="btn btn-sm btn-outline-primary">Edit</a>
<!--                //first способ by using links-->

<!--                //<a href="delete.php?id=--><?//= $product['id']?><!--" type="button" class="btn btn-sm btn-outline-danger">delete</a>-->
<!--                второй способ-->
                <form style="display: inline-block" method="post" action="delete.php">
                    <input type="hidden" name="id" value="<?= $product['id']?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger">delete</button>
                </form>

            </td>
            <?php endforeach;?>
            <?php else :?>
                <td colspan="6" style="text-align: center; font-weight: bold">No products was added</td>
            <?php endif;?>
        </tr>

    </tbody>
</table>
</body>
</html>
