<?php
?>
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
