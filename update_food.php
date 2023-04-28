<?php
require("connect-db.php");
require('foods.php');
require('user.php');

session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Search")){
        if ($_POST['searchTerm'] != NULL){
        $foods = searchUserFood($_SESSION["userID"], $_POST['searchTerm']);
        }
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <link rel="stylesheet" href="styles.css">
</head>
<body>  
<?php include("navbar.html"); ?>


<div class="container">
  <h1>Update Food Item</h1>
  <form action="update_food.php" method="POST">

    <div class="form-group">
      <label for="location">Location:</label>
      <input type="text" class="form-control" id="location" name="location">
    </div>

    <div class="form-group">
      <label for="buyDate">Buy Date:</label>
      <input type="date" class="form-control" id="buyDate" name="buyDate">
    </div>

    <div class="form-group">
      <label for="expDate">Expiration Date:</label>
      <input type="date" class="form-control" id="expDate" name="expDate">
    </div>

    <div class="form-group">
      <label for="quantity">Quantity:</label>
      <input type="number" class="form-control" id="quantity" name="quantity" required>
    </div>

    <button type="submit" class="btn btn-primary">Update Food Item</button>
    <button class="btn btn-outline-danger" onclick="window.location.href='foods_page.php'">Cancel</a> 
  </form>
</div>
</body>
</html>
