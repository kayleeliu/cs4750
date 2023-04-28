<?php

require("connect-db.php");
require('meals.php');
require('foods.php');

session_start();

// if user is not logged in or doesn't own the mealm redirect
if($_SESSION["userID"] == 0) {
  header("Location: login_form.php");
}

$mealID = $_GET["mealID"] ? $_GET["mealID"] : $_POST["mealID"];
if(!$mealID || !madeMeal($mealID, $_SESSION["userID"])) {
  header("Location: designed_meals.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
  if($_POST["updateMeal"]) {
    updateMeal($mealID, $_POST["name"], $_POST["num_of_servings"], $_POST["prep_time"], $_POST["calorie_count"], $_POST["time_of_day"]);
  } else if($_POST["deleteMeal"]) {
    deleteMeal($mealID);
    header("Location: designed_meals.php");
  }
}

$meal = getMeal($mealID);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>  
  <?php include("navbar.html"); ?> 
  <div class="container"> 
    <h1>Edit Meal</h1>
    <form name="mainForm" action="edit_meal.php" method="post">
      <div class="row mb-3 mx-3"> Name of meal:
        <input type="text" class="form-control" name="name" value="<?php echo $meal["name"]; ?>" required />     
      </div>  
      <div class="row mb-3 mx-3">Number of servings: 
        <input type="number" class="form-control" value="<?php echo $meal["num_of_servings"]; ?>" name="num_of_servings"/>    
      </div>  
      <div class="row mb-3 mx-3">Prep time (min): 
        <input type="number" class="form-control" value="<?php echo $meal["prep_time"]; ?>" name="prep_time" />
      </div>  
      <div class="row mb-3 mx-3">Calorie count: 
        <input type="number" class="form-control" value="<?php echo $meal["calorie_count"]; ?>" name="calorie_count" />
      </div>  
      <div class="row mb-3 mx-3">Time of day: 
        <input type="text" class="form-control" value="<?php echo $meal["time_of_day"]; ?>" name="time_of_day" />
      </div>  
      <div class="row mb-3 mx-3">
        <input type="hidden" name="mealID" value=<?php echo $mealID ?>>
        <input class="btn btn-primary" type="submit" name="updateMeal" value="Update" title="Create Meal" />
        <input class="btn btn-danger" type="submit" name="deleteMeal" value="Delete" title="Create Meal" />
      </div>
    </form>  
  </div>  
  <div
</body>
</html>