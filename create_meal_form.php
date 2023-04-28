<?php
require("connect-db.php");
// include("connect-db.php) 
// use require if it matters if the db exists or not

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
        createMeal($_POST['name'], $_POST['num_of_servings'], $_POST['prep_time'], $_POST['calorie_count'], $_POST['time_of_day']);
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include("common-header.php"); ?>
</head>
<body>  
<?php include("navbar.php"); ?> 
  <div class="container"> 
    <h1>Create Meal</h1>
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
