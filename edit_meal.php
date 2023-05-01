<?php

require("connect-db.php");
require('meals.php');
require('foods.php');

session_start();
$foodID = "default";

// if user is not logged in or doesn't own the mealm redirect
if($_SESSION["userID"] == 0) {
  header("Location: login_form.php");
}

if($_POST["copyMeal"]) {
  $_POST["mealID"] = copyMealToUser($_SESSION["userID"], $_POST["mealID"]);
}

$mealID = $_GET["mealID"] ? $_GET["mealID"] : $_POST["mealID"];
if(!$mealID || !madeMeal($mealID, $_SESSION["userID"])) {
  header("Location: designed_meals.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
  if($_POST["updateMeal"]) {
    updateMeal($mealID, $_POST["name"], $_POST["num_of_servings"], $_POST["prep_time"], $_POST["calorie_count"], $_POST["time_of_day"]);
  } else if($_POST["deleteMeal"]) {
    deleteMeal($mealID);
    header("Location: designed_meals.php");
  } else if($_POST["addFood"]) {
      $foodID = getFoodId($_POST['name']);
      if ($foodID){
        addFoodToMeal(getFoodId($_POST["name"]), $_POST["mealID"], $_POST["quantity"]);      }
      else {
        $foodID = "";
      }
  } else if($_POST["deleteFood"]) {
    deleteFoodFromMeal($_POST["foodID"], $mealID);
  }
  else if (!empty($_POST['addBtn']) && ($_POST['addBtn'] == "Add Food")){
    addFoodCaloriesTempGroup($_POST['entered-food-name'], $_POST['cooked-status'], $_POST['calories'], $_POST['ideal_storage_temp'], $_POST['food_group']);
    addFoodToMeal(getFoodId($_POST["entered-food-name"]), $_POST["mealID"], $_POST["quantity"]);
  }
}



$meal = getMeal($mealID);
$foods = getFoodsOfMeal($mealID);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include("common-header.php"); ?>
</head>
<body>  
  <?php include("navbar.php"); ?> 
  <div class="container"> 
    <h1>Edit Meal</h1>
    <form name="editMeal" action="edit_meal.php" method="post">
      <div class="row mb-3 mx-3"> Name of meal:
        <input type="text" class="form-control" name="name" value="<?php echo $meal["name"]; ?>" required />     
      </div>  
      <div class="row mb-3 mx-3">Number of servings: 
        <input type="number" class="form-control" value="<?php echo $meal["num_of_servings"]; ?>" name="num_of_servings"/>    
      </div>  
      <div class="row mb-3 mx-3">Prep time (min): 
        <input type="number" class="form-control" value="<?php echo $meal["prep_time"]; ?>" name="prep_time" />
      </div>  
      <div class="row mb-3 mx-3">Calorie count: 
        <input type="number" class="form-control" value="<?php echo $meal["calorie_count"]; ?>" name="calorie_count" />
      </div>  
      <div class="row mb-3 mx-3">Time of day: 
        <input type="text" class="form-control" value="<?php echo $meal["time_of_day"]; ?>" name="time_of_day" />
      </div>  
      <div class="row mb-3 mx-3">
        <input type="hidden" name="mealID" value=<?php echo $mealID ?>>
        <input class="btn btn-primary" type="submit" name="updateMeal" value="Update" />
        <input class="btn btn-danger" type="submit" name="deleteMeal" value="Delete" />
      </div>
    </form>  

    <div class="modal" id="addFoodModal" tabindex="-1" role="dialog" aria-labelledby="addFoodModalLabel" aria-hidden="true" display = "none;">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addFoodModalLabel">Add Food Before Adding to Meal!</h5>
          <button type="button" class="close close_btn" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="foodForm" action="edit_meal.php" method="post" class="form-border">
          <input type="hidden" name="mealID" value=<?php echo $mealID ?>>
          <input type="hidden" name="quantity" value=<?php echo $_POST['quantity'] ?>>
          <?php include("new_food_modal_form.php"); ?>
        </form>
      </div>
    </div>
    </div>

    <h1>Add food</h1>
    <form name="addFood" action="edit_meal.php" method="post">
      <div class="row mb-3 mx-3"> Name:
        <input type="text" class="form-control" name="name" required />     
      </div>  
      <div class="row mb-3 mx-3">Quantity: 
        <input type="number" class="form-control" name="quantity" required />    
      </div>  
      <div class="row mb-3 mx-3">
        <input type="hidden" name="mealID" value=<?php echo $mealID ?>>
        <input class="btn btn-primary" type="submit" name="addFood" value="Add Food" />
      </div>
    </form> 
  </div>  
  <div class="row justify-content-center">  
    <table class="w3-table w3-bordered w3-card-4 center" style="width:70%">
      <thead>
      <tr style="background-color:#B0B0B0">
        <th width="20%"> Name    
        <th width="20%"> Quantity
        <th width="20%"> Delete
      </tr>
      </thead>
    <?php foreach ($foods as $item): ?>
    <?php 
     ?>
      <tr>
        <td><?php echo $item['name']; ?></td>       
        <td><?php echo $item['quantity']; ?></td>     
        <td>
          <form action="edit_meal.php" method="post">
            <input type="submit" name="deleteFood" value="Delete" class="btn btn-danger btn-sm" /> 
            <input type="hidden" name="foodID", value="<?php echo $item['foodID']; ?>" />
            <input type="hidden" name="mealID", value="<?php echo $mealID; ?>" />
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
