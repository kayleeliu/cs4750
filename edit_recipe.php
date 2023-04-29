<?php

require("connect-db.php");
require('recipes.php');
require('foods.php');

session_start();

// if user is not logged in or doesn't own the mealm redirect
if($_SESSION["userID"] == 0) {
  header("Location: login_form.php");
}

$recipeID = $_GET["recipeID"] ? $_GET["recipeID"] : $_POST["recipeID"];
if(!$recipeID || !madeRecipe($recipeID, $_SESSION["userID"])) {
  header("Location: designed_recipes.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
  if($_POST["updateRecipe"]) {
    echo $_POST['prep_time'];
    echo "<br>";
    echo $_POST['link'];
    echo "<br>";
    echo $recipeID;
    updateRecipe($_POST['prep_time'], $_POST['link'], $recipeID);
  } else if($_POST["deleteRecipe"]) {
    deleteRecipe($recipeID);
    header("Location: designed_recipes.php");
  }
}

$recipe = getRecipe($recipeID);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include("common-header.php"); ?>
</head>
<body>  
  <?php include("navbar.php"); ?> 
  <div class="container"> 
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
        <input class="btn btn-primary" type="submit" name="updateRecipe" value="Update" />
        <input class="btn btn-danger" type="submit" name="deleteRecipe" value="Delete" />
      </div>
    </form>  
</body>
</html>