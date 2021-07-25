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

//echo '<pre>';
//var_dump($_POST);
//echo '</pre>';

//echo '<pre>';
//var_dump($_SERVER);
//echo '</pre>';
//exit;
$title = '';
$price = '';
$description = '';
$imagePath = '';
if ($_SERVER['REQUEST_METHOD']==='POST'){
$title = $_POST['title'];
$description = $_POST['description'];
$price = $_POST['price'];
$date = date('Y-m-d H:i:s');


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

if ($image && $image['tmp_name']) {
  $imagePath = 'images/'.randomString(8).'/'.$image['name'];
  mkdir(dirname($imagePath));
  move_uploaded_file($image['tmp_name'], $imagePath);
}

$statement = $pdo->prepare("INSERT INTO products (title, image, description, price, date) 
              VALUES (:title, :image, :description, :price, :date)");

$statement->bindValue(':title', $title);
$statement->bindValue(':image', $imagePath);
$statement->bindValue(':description', $description);
$statement->bindValue(':price', $price);
$statement->bindValue(':date', $date);
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

    <title>Kao create</title>
  </head>
  <body>
    <h1>Kao create</h1>

<?php if(!empty($errors)): ?>
    <div class = "alert alert-danger">
    <?php foreach ($errors as $error): ?>
    <div><?php echo $error ?> </div>
    <?php endforeach; ?>
    </div>
    <?php endif; ?>

    
    
    <form action = "" method = "post" enctype = "multipart/form-data">
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