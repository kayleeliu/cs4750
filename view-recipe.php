<?php

require("connect-db.php");
require('recipes.php');
require('foods.php');

session_start();

$recipeID = $_GET["recipeID"];
if(!$recipeID) {
  header("Location: all-recipes.php");
}

$recipe = getRecipe($recipeID);
$foods = getFoodsRecipeUses($recipeID);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include("common-header.php"); ?>
</head>
<body>  
  <?php include("navbar.php"); ?> 
  <div class="container"> 
    <h1><?= getFoodName($recipe['foodMade']) ?></h1>
    Prep time: <?php echo $recipe['prep_time'] ? $recipe['prep_time'] . " min." : "" ?> <br>
    Link: <a href="<?php echo $recipe['link'] ?>"> <?php echo $recipe['link'] ?> </a> <br> <br>
    <h3> Ingredients: </h3>
    <table class="w3-table w3-bordered w3-card-4 center" style="width:30%">
      
    <?php foreach ($foods as $item): ?>
    <?php 
     ?>
      <tr>
        <td style="width:10%"><?php echo $item['quantity']; ?></td>  
        <td style="width:20%"><?php echo $item['units']; ?></td> 
        <td><?php echo $item['name']; ?></td>       
      </tr>
    <?php endforeach; ?>
    </table>
  </div> 
  </div> 
</body>
</html>