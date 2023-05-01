<?php
require("connect-db.php");
require("meals.php");
session_start();

if ($_SESSION["userID"] == 0){
  header("Location: login_form.php");
}

// $_SERVER is a standard PHP object
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Create Meal")){
    // check if user is logged in
    if(empty($_SESSION["userID"])) {
        echo '<script>alert("You need to be logged in!")</script>';
    } else {
        $mealID = createMeal($_POST['name'], $_POST['num_of_servings'], $_POST['prep_time'], $_POST['calorie_count'], $_POST['time_of_day']);
        header("Location: edit_meal.php?mealID=$mealID");
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include("common-header.php"); ?>
<link rel="stylesheet" href="styles.css">
</head>
<body>  
<?php include("navbar.php"); ?> 
<div class="jumbotron feature" style = "margin-bottom: 10px;">
      <div class="container">
          <h1>Create Meals</h1>
          <p>Combine different foods to eat all together</p>
      </div>
  </div>
  <div class="container"> 
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
