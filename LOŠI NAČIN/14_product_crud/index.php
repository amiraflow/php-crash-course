<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=products_crud', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$search = $_GET['search'] ?? '';
if ($search){
  $statement = $pdo->prepare('SELECT * FROM products WHERE title LIKE :title ORDER BY date DESC');
  $statement->bindValue(':title', "%$search%");
} else{
  $statement = $pdo->prepare('SELECT * FROM products ORDER BY date DESC');
}

$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC);
//echo '<pre>';
//var_dump($products);
//echo '</pre>';

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

    <title>Kao products crud</title>
  </head>
  <body>
    <h1>Kao products crud</h1>
    <a href ="create.php" class="btn btn-info">Napravi</a>
<br>
    <form>
    <div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Pretrazi B)" name = "search" value="<?php echo $search?>">
  <div class="input-group-append">
  <button class="btn btn-outline-secondary" type="submit">Pretrazi</button>
  </div>
</div>
    </form>


    <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Image</th>
      <th scope="col">Title</th>
      <th scope="col">Price</th>
      <th scope="col">Create date</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($products as $i=>$product): ?>
    <tr>
      <th scope="row"><?php echo $i+1?></th>
      <td><img src = "<?php echo $product['image']?>" class = "slika"></td>
      <td><?php echo $product['title']?></td>
      <td><?php echo $product['price']?></td>
      <td><?php echo $product['date']?></td>
      <td><a href="update.php?id=<?php echo $product['id']?>" type="button" class="btn btn-warning">Edit</a>
      <form style = "display:inline-block" method = "post" action = "delete.php">
        <input type = "hidden" name="id" value = <?php echo $product['id']?>>
      <button type="submit" class="btn btn-dark">Delete</button>
      </form>  
    </td>
    </tr>
 
  <?php endforeach; ?>
 

  </tbody>
</table>


    
  </body>
</html>