<?php

require("connect-db.php");
require('recipes.php');

session_start();

$recipes = getAllRecipes();

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
          <h1>All Recipes</h1>
          <p>View all recipes other people have made</p>
      </div>
  </div>
<div class="row justify-content-center">  
    <table class="w3-table w3-bordered w3-card-4 center" style="width:70%">
      <thead>
      <tr style="background-color:#B0B0B0">
        <th width="25%"> Food Name     
        <th width="15%"> Prep Time
        <th width="15%"> Link
        <th width="15%"> Number of ingredients
        <th width="15%"> View Details
      </tr>
      </thead>
    <?php foreach ($recipes as $recipe): ?>
    <?php 
     ?>
      <tr>
        <td><?php echo $recipe['name'] ?></td>    
        <td><?php echo $recipe['prep_time'] ? $recipe['prep_time'] . " min." : "" ?></td>  
        <td><a href="<?php echo $recipe['link'] ?>"> <?php echo $recipe['link'] ?> </a> </td>  
        <td><?php echo $recipe['num_ingredients'] ?></td>  
        <td>
          <form name="View details" action="view-recipe.php" method="get">
            <input type="hidden" name="recipeID" value=<?php echo $recipe['id'] ?>>
            <input class="btn btn-primary" type="submit" name="viewRecipe" value="View details">
          </form>
        </td>          
      </tr>
    <?php endforeach; ?>
    </table>
  </div> 
</body>
</html>
