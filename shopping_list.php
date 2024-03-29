<?php 
require("connect-db.php");
require("foods.php");
require("wants_to_buy.php");

session_start();

# redirect to login form if not logged in yet
if ($_SESSION["userID"] == 0){
  header("Location: login_form.php");
}

echo $_POST['food_name'];

$showFoodTempModal = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  if(!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Add to Shopping List")){
    $food_adding_id = getFoodId($_POST['food_name']);
    if(checkFoodInShoppingList($_SESSION['userID'], $food_adding_id) == 0){
      addFoodToShoppingList($_SESSION['userID'], $food_adding_id, $_POST['quantity']);
    }
    else{
      updateUserShoppingList($_SESSION['userID'], $food_adding_id, $_POST['quantity']);
    }
  }
  // from shopping list
  else if(!empty($_POST['foodActionBtn']) && ($_POST['foodActionBtn'] == "Add to Shopping List")){
    $food_exists = foodNameExists($_POST['food_name']);
    if($food_exists == 0){
      addFoodToDBAndShoppingList($_SESSION['userID'], $_POST['food_name'], $_POST['cooked-status'], $_POST['quantity']);
      $showFoodTempModal = 1;
    }
    else{
      $food_adding_id = getFoodId($_POST['food_name']);
      if(checkFoodInShoppingList($_SESSION['userID'], $food_adding_id) == 0){
        addFoodToShoppingList($_SESSION['userID'], $food_adding_id, $_POST['quantity']);
      }
      else{
        updateUserShoppingList($_SESSION['userID'], $food_adding_id, $_POST['quantity']);
      }
    }
  }
  else if(!empty($_POST['addFoodTemp']) && ($_POST['addFoodTemp'] == "Add Food")){
    addFoodTemp($_POST['foodTemp-name'], $_POST['foodTemp-cooked-status'], $_POST['calories'], $_POST['ideal_storage_temp'],$_POST['food_group']);
  }
  else if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Bought")){
    $food_bought_id= getFoodId($_POST['food_bought']);
    boughtFood($_SESSION["userID"], $food_bought_id);
  }
  else if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Delete")){
    $food_delete_id = getFoodId($_POST['food_to_delete']);
    deleteFoodFromShoppingList($_SESSION["userID"], $food_delete_id);
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
<div class="jumbotron feature" style = "margin-bottom: 10px;">
      <div class="container">
          <h1>Shopping List</h1>
          <p>View all foods you want to buy</p>
      </div>
  </div>
<div class="modal" id="boughtFoodModal" tabindex="-1" role="dialog" aria-labelledby="boughtFoodModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="boughtFoodModalLabel">Add Food</h5>
        <button type="button" class="close close_bought_modal" aria-label="Close">
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
                <label for="entered-food-quantity">Servings:</label>
                <input type="number" id="entered-food-quantity" class="form-control" name="entered-food-quantity">
            </div>
            <div class="form-group">
              <label for="entered-food-location">Where are you putting it:</label>
              <input type="text" id="entered-food-location" class="form-control" name="entered-food-location">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary close_bought_modal">Close</button>
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
        <th width="30%"> Servings
        <th width="10%"> Bought
        <th width="10%"> Delete
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
          <button type="button" class="btn btn-success btn-sm bought_btn" onclick="openBoughtFoodModal('<?php echo $item['name']; ?>' ,'<?php echo $item['quantity']; ?>', this);">Bought</button>
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
    <button class="btn btn-outline-info btn-lg addShoppingListFooterBtn" onclick="openAddFoodModal()">Add Food to Shopping List</button>
</footer>
<div class="modal" id="addFoodModal" tabindex="-1" role="dialog" aria-labelledby="addFoodModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addFoodModalLabel">Add Food to Shopping List</h5>
        <button type="button" class="close close_add_modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="foodForm" action="shopping_list.php" method="post" class="form-border">
        <div class="modal-body">
            <div class="form-group">
                <label for="food_name">Food Name:</label>
                <input type="text" id="food_name" class="form-control" name="food_name">
            </div>
            <div class="form-group">
                <label for="quantity">Servings:</label>
                <input type="number" id="quantity" class="form-control" name="quantity">
            </div>
            <div class="form-group">
              <label for="cooked">Is it cooked?</label>
              <select id="cooked-status" name="cooked-status" class="form-control cooked-status">
                  <option value="cooked">Cooked</option>
                  <option value="notCooked">Not Cooked</option>
                  <option value="NULL">N/A</option>
              </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary close_add_modal">Close</button>
          <input class="btn btn-primary" type="submit" name="foodActionBtn" value="Add to Shopping List" title="Add to Shopping List"/>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal" id="foodTempModal" tabindex="-1" role="dialog" aria-labelledby="foodTempModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="foodTempModalLabel">Add more details</h5>
        <button type="button" class="close close_foodTemp_modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="shopping_list.php" method="post" class="form-border">
      <div class="modal-body">
        <div class="form-group">
          <label for="foodTemp-name">Food Name:</label>
          <input type="text" id="foodTemp-name" class="form-control" name="foodTemp-name" value="<?php echo $_POST['food_name'] ?>" readonly>
      </div>
      <div class="form-group">
          <label for="foodTemp-cooked-status">Is it cooked?</label>
          <select id="foodTemp-cooked-status" name="foodTemp-cooked-status" class="form-control cooked-status" value="<?php echo $_POST['cooked-status'] ?>" readonly>
              <option value="cooked">Cooked</option>
              <option value="notCooked">Not Cooked</option>
              <option value="NULL">N/A</option>
          </select>
      </div>
          <div class="form-group">
              <label for="calories">Calories per Serving:</label>
              <input type="number" id="calories" class="form-control" name="calories">
          </div>
          <div class="form-group">
              <label for="ideal_storage_temp">Ideal Storage Temperature (F):</label>
              <input type="number" id="ideal_storage_temp" class="form-control" name="ideal_storage_temp">
          </div>
          <div class="form-group">
              <label for="food_group">Food Group:</label>
              <select id="food_group" name="food_group" class="form-control food_group">
                  <option value="fruits">Fruits</option>
                  <option value="vegetable">Vegetables</option>
                  <option value="protein">Protein</option>
                  <option value="grains">Grains</option>
                  <option value="dairy">Dairy</option>
                  <option value="other">Other</option>
              </select>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary close_foodTemp_modal">Close</button>
              <input class="btn btn-primary" type="submit" name="addFoodTemp" value="Add Food" title="Add Food" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
</html>

<script>
  
$(document).ready(function() {
  
  console.log("<?php echo $showFoodTempModal; ?>");
  if("<?php echo $showFoodTempModal; ?>" == 1){
    $("#foodTempModal").show();
  }
  else{
    $("#foodTempModal").hide();
  }
  $(".close_bought_modal").click(function(){
    $("#boughtFoodModal").hide();
  })

  $(".close_add_modal").click(function(){
    $("#addFoodModal").hide();
  })
})

function openBoughtFoodModal(food_name, food_quantity){
    console.log(food_name + food_quantity);
    $('#boughtFoodModal').show();
    $('#entered-food-name').val(food_name);  
    $('#entered-food-quantity').val(food_quantity);
}

function openAddFoodModal(){
  $("#addFoodModal").show();
}




</script>