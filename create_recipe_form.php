<?php
require("connect-db.php");
require("recipes.php");
require("foods.php");
session_start();

// $_SERVER is a standard PHP object
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Create Recipe")){
    // check if user is logged in
    if(empty($_SESSION["userID"])) {
        echo '<script>alert("You need to be logged in!")</script>';
    } else {
        $foodID = getFoodId($_POST['name']);
        echo createRecipe($_POST['prep_time'], $foodID, $_SESSION["userID"], $_POST['link']);
       
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
    <h1>Create Recipe</h1>
    <form name="mainForm" action="create_recipe_form.php" method="post">
      <div class="row mb-3 mx-3"> Food Made:
        <input type="text" class="form-control" name="name" placeholder="ex. shrimp scampi" required />     
      </div>  
      <div class="row mb-3 mx-3">Prep Time (min): 
        <input type="number" class="form-control" name="prep_time"/>    
      </div>  
      <div class="row mb-3 mx-3">Recipe Link: 
        <input type="text" class="form-control" name="link"/>
      </div>  
      <div class="row mb-3 mx-3">
        <input class="btn btn-primary" type="submit" name="actionBtn" value="Create Recipe" title="Create Recipe" />
      </div>
    </form>  
  </div>  
</body>
</html>