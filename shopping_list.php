<?php 
require("connect-db.php");
require("foods.php");
require("wants_to_buy.php");

session_start();

# redirect to login form if not logged in yet
if ($_SESSION["userID"] == 0){
  header("Location: login_form.php");
}


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  if(!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Add to Shopping List")){
    $food_adding_id = getFoodId($_POST['food_name']);
    addFoodToShoppingList($_SESSION['userID'], $food_adding_id, $_POST['quantity']);
  }
  else if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Bought")){
    $food_bought_id= getFoodId($_POST['food_bought']);
    boughtFood($_SESSION["userID"], $food_bought_id[0] );
  }
  else if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Delete")){
    $food_delete_id = getFoodId($_POST['food_to_delete']);
    deleteFoodFromShoppingList($_SESSION["userID"], $food_delete_id[0]);
  }
  else if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Add Food")){
    $food_entered = getFoodId($_POST['entered-food-name']);
    $has_food = checkUserHasFood($_SESSION['userID'], $food_entered);
    if($has_food[0] == 0){
      addFoodToInventory($_SESSION['userID'], $food_entered, $_POST['entered-food-location'], $_POST['entered-food-buy-date'], $_POST['entered-food-exp-date'], $_POST['entered-food-quantity']);
    }
    else{
      updateUserFood($_SESSION["userID"], $food_entered, $_POST['entered-food-location'], $_POST['entered-food-buy-date'], $_POST['entered-food-exp-date'], $_POST['entered-food-quantity']);
    }
    deleteFoodFromShoppingList($_SESSION["userID"], $food_entered);
  }
}

$foods = getUserShoppingList($_SESSION["userID"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("common-header.php"); ?>
  <link rel="stylesheet" href="styles.css">
</head>
<body> 
<?php include("navbar.php"); ?>
<div class="modal" id="addFoodModal" tabindex="-1" role="dialog" aria-labelledby="addFoodModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addFoodModalLabel">Add Food</h5>
        <button type="button" class="close close_btn" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="foodForm" action="shopping_list.php" method="post" class="form-border">
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
          <input class="btn btn-primary" type="submit" name="actionBtn" value="Add Food" title="Add Food" />
        </div>
      </form>
    </div>
  </div>
</div>

<div class="row justify-content-center">  
    <table class="w3-table w3-bordered w3-card-4 center" style="width:70%">
      <thead>
      <tr style="background-color:#B0B0B0">
        <th width="50%"> Name     
        <th width="16%"> Quantity
        <th width="16%"> Bought
        <th width="16%"> Delete
      </tr>
      </thead>
    <?php foreach ($foods as $item): ?>
    <?php 
     ?>
      <tr>
        <td><?php echo $item['name']; ?></td>    
        <td><?php echo $item['quantity']; ?></td>  
        <td>
          <!-- <form action="shopping_list.php" method="post">
            <input type="submit" name="actionBtn" value="Bought" class="btn btn-success btn-sm" /> 
            <input type="hidden" name="food_bought", value="<?php echo $item['name']; ?>" />
          </form>  -->
          <button type="button" class="btn btn-success btn-sm bought_btn" onclick="openModal('<?php echo $item['name']; ?>' ,'<?php echo $item['quantity']; ?>', this);">Bought</button>
        </td>       
        <td>
          <form action="shopping_list.php" method="post">
            <input type="submit" name="actionBtn" value="Delete" class="btn btn-danger btn-sm" /> 
            <input type="hidden" name="food_to_delete", value="<?php echo $item['name']; ?>" />
          </form> 
        </td>            
      </tr>
    <?php endforeach; ?>
    </table>
  </div> 
</body>
<footer class="footer fixed-bottom">
    <button class="btn btn-outline-info btn-lg addShoppingListFooterBtn">Add Food to Shopping List</button>
</footer>
</html>

<script>
  
$(document).ready(function() {
  $(".close_btn").click(function(){
    $("#addFoodModal").hide();
  })
})

function openModal(food_name, food_quantity){
    console.log(food_name + food_quantity);
    $('#addFoodModal').show();
    $('#entered-food-name').val(food_name);  
    $('#entered-food-quantity').val(food_quantity);
  }



</script>