<?php

require("connect-db.php");
require('recipes.php');
require('foods.php');

session_start();

// if user is not logged in or doesn't own the meal redirect
if($_SESSION["userID"] == 0) {
  header("Location: login_form.php");
}

$recipeID = $_GET["recipeID"] ? $_GET["recipeID"] : $_POST["recipeID"];
if(!$recipeID || !madeRecipe($recipeID, $_SESSION["userID"])) {
  header("Location: designed_recipes.php");
}

$foodID = "default";

if($_SERVER["REQUEST_METHOD"] == "POST") {
  if($_POST["updateRecipe"]) {
    updateRecipe($_POST['prep_time'], $_POST['link'], $recipeID);
  } else if($_POST["deleteRecipe"]) {
    deleteRecipe($recipeID);
    header("Location: designed_recipes.php");
  } else if($_POST["addFood"]) {
      $foodID = getFoodId($_POST['name']);
      if ($foodID){
        addFoodToRecipe($recipeID, getFoodId($_POST["name"]), $_POST["quantity"], $_POST["units"]);      }
      else {
        $foodID = "";
      }
  } else if($_POST["deleteFood"]) {
    deleteIngredientFromRecipe($recipeID, $_POST["foodID"]);
  }
  else if (!empty($_POST['addBtn']) && ($_POST['addBtn'] == "Add Food")){
    addFoodCaloriesTempGroup($_POST['entered-food-name'], $_POST['cooked-status'], $_POST['calories'], $_POST['ideal_storage_temp'], $_POST['food_group']);
    addFoodToRecipe($recipeID, getFoodId($_POST["name"]), $_POST["quantity"], $_POST["units"]); 
  }
}

$recipe = getRecipe($recipeID);
$foods = getFoodsRecipeUses($recipeID);

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
          <h1>Edit Recipe</h1>
          <p>What foods are needed for your recipe</p>
      </div>
  </div>
  <div id = "outer_form_container" class = "outer_form_container">
    <div class="edit_recipe_form" id = "edit_recipe_form"> 
      <h1>Edit Recipe</h1>
      <form name="editRecipe" action="edit_recipe.php" method="post">
        <div class="row mb-3 mx-3"> Name of Recipe:
          <input type="text" class="form-control" name="name" value="<?php echo getFoodName($recipe['foodMade']); ?>" readonly />     
        </div>  
        <div class="row mb-3 mx-3">Prep time (min): 
          <input type="number" class="form-control" value="<?php echo $recipe["prep_time"]; ?>" name="prep_time" />
        </div> 
        
        <div class="row mb-3 mx-3">Link: 
          <input type="text" class="form-control" value="<?php echo $recipe["link"]; ?>" name="link" />
        </div>  
        <div class="row mb-3 mx-3">
          <input type="hidden" name="recipeID" value=<?php echo $recipeID ?>>
          <input class="btn btn-primary" type="submit" name="updateRecipe" value="Update" style = "margin-bottom:10px;" />
          <input class="btn btn-danger" type="submit" name="deleteRecipe" value="Delete" />
        </div>
      </form>  
    </div>

    <div class="modal" id="addFoodModal" tabindex="-1" role="dialog" aria-labelledby="addFoodModalLabel" aria-hidden="true" display = "none;">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addFoodModalLabel">Add Food Before Adding to Recipe!</h5>
          <button type="button" class="close close_btn" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="foodForm" action="edit_meal.php" method="post" class="form-border">
          <input type="hidden" name="mealID" value=<?php echo $recipeID ?>>
          <input type="hidden" name="quantity" value=<?php echo $_POST['quantity'] ?>>
          <?php include("new_food_modal_form.php"); ?>
        </form>
      </div>
    </div>
    </div>

    <div id = "add_food_for_edit_recipe_form" class = "add_food_for_edit_recipe_form">
      <h1>Add food</h1>
        <form name="addFood" action="edit_recipe.php" method="post">
          <div class="row mb-3 mx-3"> Name:
            <input type="text" class="form-control" name="name" required />     
          </div>  
          <div class="row mb-3 mx-3">Quantity: 
            <input type="number" class="form-control" name="quantity" required />    
          </div>  
          <div class="row mb-3 mx-3">Units: 
            <input type="text" class="form-control" name="units" required />    
          </div> 
          <div class="row mb-3 mx-3">
            <input type="hidden" name="recipeID" value=<?php echo $recipeID ?>>
            <input class="btn btn-primary" type="submit" name="addFood" value="Add Food" />
          </div>
        </form> 
    </div>
  </div>
  <div class = "container_for_food_in_recipe" id = "container_for_food_in_recipe">
    <table class="w3-table w3-bordered w3-card-4 center table_for_food_in_recipe"  id = "table_for_food_in_recipe"style="width:70%"> 
      <thead>
      <tr style="background-color:#B0B0B0">
        <th width="20%"> Name    
        <th width="20%"> Quantity
        <th width="20%"> Units
        <th width="20%"> Delete
      </tr>
      </thead>
    <?php foreach ($foods as $item): ?>
    <?php 
     ?>
      <tr>
        <td><?php echo $item['name']; ?></td>       
        <td><?php echo $item['quantity']; ?></td>  
        <td><?php echo $item['units']; ?></td>       
        <td>
          <form action="edit_recipe.php" method="post">
            <input type="submit" name="deleteFood" value="Delete" class="btn btn-danger btn-sm" /> 
            <input type="hidden" name="foodID", value="<?php echo $item['foodID']; ?>" />
            <input type="hidden" name="recipeID", value="<?php echo $recipeID; ?>" />
          </form> 
        </td>       
      </tr>
    <?php endforeach; ?>
    </table>
  </div>
</body>
</html>

<script>
$(document).ready(function() {
  $(".close_btn").click(function() {
    $("#addFoodModal").hide();
  });

  console.log("<?php echo $foodID; ?>");

  if ("<?php echo $foodID; ?>" === "") {
    $("#addFoodModal").show();
  } else {
    $("#addFoodModal").hide();
  }
});
</script>
