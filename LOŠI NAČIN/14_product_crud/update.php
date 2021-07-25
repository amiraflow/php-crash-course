<?php

function randomString($n)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $str .= $characters[$index];
    }

    return $str;
}

$errors = [];
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=products_crud', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = $_GET['id'] ?? null;

if (!$id){
    header('Location: index.php');
    exit;
}

$statement = $pdo->prepare('SELECT * FROM products WHERE id=:id');
$statement->bindValue(':id', $id);
$statement->execute();
$product = $statement->fetch(PDO::FETCH_ASSOC);

//echo '<pre>';
//var_dump($_SERVER);
//echo '</pre>';
//exit;
$title = $product['title'];
$price = $product['price'];
$description = $product['description'];
$imagePath = '';
if ($_SERVER['REQUEST_METHOD']==='POST'){
$title = $_POST['title'];
$description = $_POST['description'];
$price = $_POST['price'];



if(!$title){
  $errors[] = 'Fali naziv proizvoda';
}
if(!$price){
  $errors[] = 'Fali cijena';
}

//$pdo->exec("INSERT INTO products (title, image, description, price, datum) 
//              VALUE ('$title', '', '$description', $price, '$date')")

if (!is_dir('images')){
  mkdir('images');
}

if(empty($errors)){

$image = $_FILES['image'] ?? null; //ako nema slike ona je null
$imagePath = $product['image'];



if ($image && $image['tmp_name']) {
    if ($product['image']){
        unlink($product['image']);
    }
  $imagePath = 'images/'.randomString(8).'/'.$image['name'];
  mkdir(dirname($imagePath));
  move_uploaded_file($image['tmp_name'], $imagePath);
}

$statement = $pdo->prepare("UPDATE products SET title =:title, image = :image, description=:description, price=:price WHERE id=:id" );

$statement->bindValue(':title', $title);
$statement->bindValue(':image', $imagePath);
$statement->bindValue(':description', $description);
$statement->bindValue(':price', $price);
$statement->bindValue(':id', $id);
$statement->execute();
header('Location: index.php');
}
}

?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link href="app.css" rel="stylesheet"/>
    <title>Kao edit</title>
  </head>
  <body>
  <p>
    <a href="index.php" class="btn btn-secondary">Back to products</a>
</p>
    <h1>Kao edit <b><?php echo $product['title'] ?></b></h1>

<?php if(!empty($errors)): ?>
    <div class = "alert alert-danger">
    <?php foreach ($errors as $error): ?>
    <div><?php echo $error ?> </div>
    <?php endforeach; ?>
    </div>
    <?php endif; ?>

    
    
    <form action = "" method = "post" enctype = "multipart/form-data">

    <?php if($product['image']): ?>
        <img class="update-slika" src ="<?php echo $product['image']?>" >
    <?php endif; ?>
  <div class="mb-3">
    <label" class="form-label">Slika proizvoda</label>
    <input type="file" name = "image">
    </div>
    <div class="mb-3">
    <label" class="form-label">Ime proizvoda</label>
    <input type="text" name = "title" value = <?php echo $title?>>
    </div>
    <div class="mb-3">
    <label" class="form-label">Opis proizvoda</label>
    <input type="textarea" name = "description" value = <?php echo $description?>>
    </div>
    <div class="mb-3">
    <label" class="form-label">Cijena</label>
    <input type="number" step = ".01" name = "price" value = <?php echo $price?>>
    </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

    
  </body>
</html>