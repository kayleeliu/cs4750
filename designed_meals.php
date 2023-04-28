<?php

require("connect-db.php");
require('meals.php');

session_start();

if ($_SESSION["userID"] == 0){
  header("Location: login_form.php");
}
$meals = getMealsUserDesigned($_SESSION["userID"]);
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
        <th width="15%"> Edit Meal
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
          <form name="Edit meal" action="edit_meal.php">
            <input type="hidden" name="mealID" value=<?php echo $meal['mealID'] ?>>
            <input class="btn btn-primary" type="submit" value="Edit">
          </form>
        </td>           
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
