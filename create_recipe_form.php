<?php
require("connect-db.php");
require("recipes.php");
require("foods.php");
session_start();

// $_SERVER is a standard PHP object
$foodID = "default";
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  
  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Create Recipe")){
    
    // check if user is logged in
    if(empty($_SESSION["userID"])) {
        echo '<script>alert("You need to be logged in!")</script>';
    } else {

        $foodID = getFoodId($_POST['name']);
        if ($foodID){
          createRecipe($_POST['prep_time'], $foodID, $_SESSION["userID"], $_POST['link']);
        }
        else {
          $foodID = "";
        }
    }
  }
  else if (!empty($_POST['addBtn']) && ($_POST['addBtn'] == "Add Food")){
    $food_entered = getFoodId($_POST['entered-food-name']);
    addFood($_POST['entered-food-name'], NULL, $_SESSION['userID'], $_POST['entered-food-location'], $_POST['entered-food-buy-date'], $_POST['entered-food-exp-date'], $_POST['entered-food-quantity']);
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

<div class="modal" id="addFoodModal" tabindex="-1" role="dialog" aria-labelledby="addFoodModalLabel" aria-hidden="true" display = "none;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addFoodModalLabel">Add Food Before Making a Recipe!</h5>
        <button type="button" class="close close_btn" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="foodForm" action="create_recipe_form.php" method="post" class="form-border">
        <div class="modal-body">
            <div class="form-group">
                <label for="entered-food-name">Food Name:</label>
                <input type="text" id="entered-food-name" class="form-control" name="entered-food-name">
            </div>
            <div class="form-group">
                <label for="entered-food-buy-date">Buy date:</label>
                <input type="date" id="entered-food-buy-date" class="form-control" name="entered-food-buy-date">
            </div>
            <div class="form-group">
                <label for="entered-food-exp-date">Expiration date:</label>
                <input type="date" id="entered-food-exp-date" class="form-control" name="entered-food-exp-date">
            </div>
            <div class="form-group">
                <label for="entered-food-quantity">Quantity:</label>
                <input type="number" id="entered-food-quantity" class="form-control" name="entered-food-quantity">
            </div>
            <div class="form-group">
              <label for="entered-food-location">Where are you putting it:</label>
              <input type="text" id="entered-food-location" class="form-control" name="entered-food-location">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary close_btn">Close</button>
          <input class="btn btn-primary" type="submit" name="addBtn" value="Add Food" title="Add Food" />
        </div>
      </form>
    </div>
  </div>
</div>


  <div class="container"> 
    <h1>Create Recipe</h1>
    <form name="mainForm" action="create_recipe_form.php" method="post">
      <div class="row mb-3 mx-3"> Food Made:
        <input type="text" class="form-control" name="name" id="name" placeholder="ex. shrimp scampi" required />     
      </div>  
      <div class="row mb-3 mx-3">Prep Time (min): 
        <input type="number" class="form-control" name="prep_time"/>    
      </div>  
      <div class="row mb-3 mx-3">Recipe Link: 
        <input type="text" class="form-control" name="link"/>
      </div>  
      <div class="row mb-3 mx-3">
        <input class="btn btn-primary" type="submit" name="actionBtn" value="Create Recipe" title="Create Recipe" id="actionBtn"/>
      </div>
    </form>  
  </div>  
</body>
</html>

<script>
var clicked = false;
document.getElementById('actionBtn').addEventListener("click", function() {
  clicked = true;
});

$(document).ready(function() {
  $(".close_btn").click(function() {
    $("#addFoodModal").hide();
  });

  console.log("<?php echo $foodID; ?>");
  console.log(clicked);

  if ("<?php echo $foodID; ?>" === "") {
    $("#addFoodModal").show();
  } else {
    $("#addFoodModal").hide();
  }
});


</script>