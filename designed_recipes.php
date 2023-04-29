<?php

require("connect-db.php");
require('recipes.php');
require('foods.php');

session_start();

if ($_SESSION["userID"] == 0){
  header("Location: login_form.php");
}
$recipes = getRecipeFoods($_SESSION["userID"]);
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
        <th width="30%"> Food Made     
        <th width="15%"> Prep Time
        <th width="40%"> Recipe Link
        <th width="15%"> Edit Recipe
      </tr>
      </thead>
    <?php foreach ($recipes as $recipe): ?>
    <?php 
     ?>
      <tr>
        <td><?php echo getFoodName($recipe['foodMade']); ?></td>
        <td><?php echo $recipe['prep_time'] ? $recipe['prep_time'] . " min." : "" ?></td>     
        <td><a href=<?php echo $recipe['link'] ?>><?php echo $recipe['link'] ?></a></td>  
        <td>
          <form name="Edit recipe" action="edit_recipe.php">
            <input type="hidden" name="recipeID" value=<?php echo $recipe['id'] ?>>
            <input class="btn btn-primary" type="submit" value="Edit">
          </form>
        </td>           
      </tr>
    <?php endforeach; ?>
    </table>
  </div> 
</body>
</html>
