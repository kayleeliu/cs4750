<?php
require("connect-db.php");
// include("connect-db.php) 
// use require if it matters if the db exists or not

require("meals.php");
session_start();

// $_SERVER is a standard PHP object
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Create Meal")){
    // check if user is logged in
    if(empty($_SESSION["userID"])) {
        echo '<script>alert("You need to be logged in!")</script>';
    } else {
        createMeal($_SESSION["userID"], $_POST['name'], $_POST['num_of_servings'], $_POST['prep_time'], $_POST['calorie_count'], $_POST['time_of_day']);
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>  
<nav class="navbar navbar-expand-lg navbar-light bg-light ps-3 pe-3">
  <a class="navbar-brand" href="index.html">Food Tracker</a>
  <a class="nav-item nav-link" href="foods_page.php">Foods</a>
  <div class="collapse navbar-collapse flex-row-reverse">
    <div class="flex-row-reverse justify-content-between">
      <button class="btn btn-outline-primary" onclick="window.location.href='create_user_form.php'">Create Account</a> 
      <button class="btn btn-outline-primary" onclick="window.location.href='login_form.php'">Login</a> 
    </div>
  </div>
</nav>  
  <div class="container"> 
    <h1>Create Account</h1>
    <form name="mainForm" action="create_meal_form.php" method="post">
      <div class="row mb-3 mx-3"> Name of meal:
        <input type="text" class="form-control" name="name" required />     
      </div>  
      <div class="row mb-3 mx-3">Number of servings: 
        <input type="number" class="form-control" name="num_of_servings"/>    
      </div>  
      <div class="row mb-3 mx-3">Prep time (min): 
        <input type="number" class="form-control" name="prep_time" />
      </div>  
      <div class="row mb-3 mx-3">Calorie count: 
        <input type="number" class="form-control" name="calorie_count" />
      </div>  
      <div class="row mb-3 mx-3">Time of day: 
        <input type="text" class="form-control" name="time_of_day" />
      </div>  
      <div class="row mb-3 mx-3">
        <input class="btn btn-primary" type="submit" name="actionBtn" value="Create Meal" title="Create Meal" />
      </div>
    </form>  
  </div>  
</body>
</html>
