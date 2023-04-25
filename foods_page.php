<?php

require("connect-db.php");
require('foods.php');
require('user.php');

session_start();

# redirect to login form if not logged in yet
if ($_SESSION["userID"] == 0){
  header("Location: login_form.php");
}

$foods = getUserFood($_SESSION["userID"]);

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Apply Filter")){
    $filter_selected = $_POST["filter_item"];
    switch ($filter_selected) {
      case "alphabetical":
        $foods = getUserFoodAlphabetical($_SESSION["userID"]);
        break;
      case "expiration":
        $foods = getUserFoodExpiration($_SESSION["userID"]);
        break;
      case "buy_date_old_new":
        $foods = getUserFoodBuyDateOldNew($_SESSION["userID"]);
        break;
      case "buy_date_new_old":
        $foods = getUserFoodBuyDateNewOld($_SESSION["userID"]);
      break;
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
<nav class="navbar navbar-expand-lg navbar-light bg-light ps-3 pe-3">
  <a class="navbar-brand" href="index.html">Food Tracker</a>  
  <a class="nav-item nav-link" href="foods_page.php">Foods</a>
  <div class="collapse navbar-collapse flex-row-reverse">
    <div class="flex-row-reverse justify-content-between">
      <button class="btn btn-outline-primary" onclick="window.location.href='create_user_form.php'">Create Account</button> 
      <button id="logout_btn" class = "btn btn-outline-secondary" onclick="window.location.href='login_form.php'">Logout</button>
    </div>
  </div>
</nav>  
<div class="container">
  <button class="btn" onclick="toggle_filter_menu()" style="position:absolute; right: 1vw;">
    <img id=filter_icon src="filter_icon.png"/>
  </button>
</div>
<div id="filter_menu" style="display: none;">
  <div class="d-flex flex-column justify-content-center">
    <div class="container">
      <a class="closebtn" onclick="toggle_filter_menu()">&times;</a>
    </div>
    <div class="container">
      <h2 style="text-align: center;"><b>Sort By</b></h2>
    </div>
    <form name="filter_form" action="foods_page.php" method="post">  
      <div class="container">
        <ul id="filter_list" style="list-style-type: none"> 
            <li>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="filter_item" value="alphabetical" id="alphabetical_btn">
                <label class="form-check-label" for="alphabetical_btn">
                  Alphabetical(A-Z)
                </label>
              </div>
            </li>
            <li>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="filter_item" value = "expiration" id="expiration">
                <label class="form-check-label" for="expiration">
                  Expiration: Closest to Farthest
                </label>
              </div>
            </li>
            <li>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="filter_item" value="buy_date_old_new" id="buy_date_old_new">
                <label class="form-check-label" for="buy_date_old_new">
                  Buy Date: Oldest to Newest
                </label>
              </div>
            </li>
            <li>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="filter_item" value="buy_date_new_old" id="buy_date_new_old">
                <label class="form-check-label" for="buy_date_new_old">
                  Buy Date: Newest to Oldest
                </label>
              </div>
            </li>
        </ul>
      </div>
      <div class="container text-center">
        <input class="btn btn-primary" type="submit" name="actionBtn" value="Apply Filter" title="Apply Filter">
      </div>
    <form>
  </div>
</div> 
<div class="row justify-content-center">  
    <table class="w3-table w3-bordered w3-card-4 center" style="width:70%">
      <thead>
      <tr style="background-color:#B0B0B0">
        <th width="20%"> Name     
        <th width="20%"> Location
        <th width="20%"> Quantity
        <th width="20%"> Buy Date
        <th width="20%"> Exp Date
      </tr>
      </thead>
    <?php foreach ($foods as $item): ?>
    <?php 
     ?>
      <tr>
        <td><?php echo $item['name']; ?></td>
        <td><?php echo $item['location']; ?></td>        
        <td><?php echo $item['quantity']; ?></td>  
        <td><?php echo $item['buy_date']; ?></td>  
        <td><?php echo $item['exp_date']; ?></td>             
      </tr>
    <?php endforeach; ?>
    </table>
  </div> 
</body>
</html>
<script>
  function toggle_filter_menu(){
    $("#filter_menu").toggle();
  }

</script>
