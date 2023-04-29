<?php

require("connect-db.php");
require('meals.php');

session_start();

$meals = getAllMeals();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("common-header.php"); ?>
  <link rel="stylesheet" href="styles.css">
</head>
<body>  

<?php include("navbar.php"); ?>
<div class="row justify-content-center">  
    <table class="w3-table w3-bordered w3-card-4 center" style="width:70%">
      <thead>
      <tr style="background-color:#B0B0B0">
        <th width="25%"> Name     
        <th width="15%"> Time of Day
        <th width="15%"> Servings
        <th width="15%"> Calorie Count
        <th width="15%"> Prep Time
        <th width="15%"> Add Meal
        <th width="15%"> Remix Meal    
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
        <td>
          <form name="Add meal" action="my-meals.php" method="post">
            <input type="hidden" name="mealID" value=<?php echo $meal['id'] ?>>
            <input class="btn btn-primary" type="submit" name="addMeal" value="Add meal">
          </form>
        </td>   
        <td>
          <form name="Remix meal" action="edit_meal.php" method="post">
            <input type="hidden" name="mealID" value=<?php echo $meal['id'] ?>>
            <input class="btn btn-primary" type="submit" name="copyMeal" value="Remix">
          </form>
        </td>         
      </tr>
    <?php endforeach; ?>
    </table>
  </div> 
</body>
</html>
