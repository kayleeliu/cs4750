<?php

require("connect-db.php");
require('meals.php');

session_start();

if($_SESSION["userID"] == 0) {
  header("Location: login_form.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if($_POST["addMeal"]) {
        giveUserMeal($_SESSION["userID"], $_POST["mealID"]);
    } else if($_POST["eatMeal"]) {
        setMealEaten($_SESSION["userID"], $_POST["mealID"], $_POST["eatMeal"] == "Eat");
    } else if($_POST["deleteMeal"]) {
        deleteMealFromUser($_SESSION["userID"], $_POST["mealID"]);
    }
}

$meals = getMealsUserHas($_SESSION["userID"]);

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
          <h1>My Meals</h1>
          <p>View meals you currently have and can eat</p>
      </div>
  </div>
<div class="row justify-content-center">  
    <table class="w3-table w3-bordered w3-card-4 center" style="width:70%">
      <thead>
      <tr style="background-color:#B0B0B0">
        <th width="20%"> Name     
        <th width="15%"> Time of Day
        <th width="15%"> Servings
        <th width="15%"> Calorie Count
        <th width="15%"> Prep Time
        <th width="15%"> Eaten
        <th width="15%"> Eat Meal
        <th width="15%"> Delete Meal
      </tr>
      </thead>
    <?php foreach ($meals as $meal): ?>
    <?php 
     ?>
      <tr>
        <td><?php echo $meal['name']; ?></td>
        <td><?php echo $meal['time_of_day']; ?></td>        
        <td><?php echo $meal['num_of_servings'] ? $meal['num_of_servings'] . " servings" : "" ?></td>  
        <td><?php echo $meal['calorie_count'] ? $meal['calorie_count'] . " calories" : "" ?></td>  
        <td><?php echo $meal['prep_time'] ? $meal['prep_time'] . " min." : "" ?></td>  
        <td><?= $meal['eaten'] ? "Yes" : "No" ?></td>  
        <td>
          <form name="Eat meal" action="my-meals.php" method="post">
            <input type="hidden" name="mealID" value=<?php echo $meal['id'] ?>>
            <input class="btn <?= $meal['eaten'] ? "btn-danger" : "btn-primary" ?>" type="submit" name="eatMeal" value="<?= $meal['eaten'] ? "Uneat" : "Eat" ?>">
          </form>
        </td>  
        <td>
          <form name="Delete meal" action="my-meals.php" method="post">
            <input type="hidden" name="mealID" value=<?php echo $meal['id'] ?>>
            <input class="btn btn-danger" type="submit" name="deleteMeal" value="Delete">
          </form>
        </td>           
      </tr>
    <?php endforeach; ?>
    </table>
  </div> 
</body>
</html>
