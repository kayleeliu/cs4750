<?php

require("connect-db.php");
require('foods.php');
require('user.php');

session_start();

# redirect to login form if not logged in yet
if ($_SESSION["userID"] == 0){
  header("Location: login_form.php");
}

$foods = getUserFood($_SESSION["userID"]);
$foodID = "default";

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Apply Filter")){
    $filter_selected = $_POST["filter_item"];
    switch ($filter_selected) {
      case "alphabetical":
        $foods = getUserFoodAlphabetical($_SESSION["userID"]);
        break;
      case "expiration":
        $foods = getUserFoodExpiration($_SESSION["userID"]);
        break;
      case "buy_date_old_new":
        $foods = getUserFoodBuyDateOldNew($_SESSION["userID"]);
        break;
      case "buy_date_new_old":
        $foods = getUserFoodBuyDateNewOld($_SESSION["userID"]);
      break;
    }
  }
  else if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Search")){
    if ($_POST['searchTerm'] != NULL){
      $foods = searchUserFood($_SESSION["userID"], $_POST['searchTerm']);
    }
  }
  else if (!empty($_POST['foodActionBtn']) && ($_POST['foodActionBtn'] == "Enter New Food")){
      $foodID = getFoodId($_POST['name']);
      if ($foodID){
        addFood($_POST['name'], $_POST['cooked-status'], $_SESSION['userID'], $_POST['entered-food-location'], $_POST['entered-food-buy-date'], $_POST['entered-food-exp-date'], $_POST['entered-food-quantity']);
      }
      else {
        $foodID = "";
      }
  }
  else if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Delete")){
    $foodID = getFoodId($_POST['food_to_delete']);
    deleteFood($foodID);
    $foods = getUserFood($_SESSION["userID"]);
  }
  else if (!empty($_POST['updateFoodBtn']) && ($_POST['updateFoodBtn'] == "updateFood")){
    $foodID = getFoodId($_POST['updateFoodName']);
    $userID = $_SESSION["userID"];
    $updatedLocation = $_POST['updatedLocation'];
    $updatedExpDate = $_POST['updatedExpDate'];
    $updatedBuyDate = $_POST['updatedBuyDate'];
    $updatedQuantity = $_POST['updatedQuantity'];
    updateFood($userID, $foodID, $updatedLocation, $updatedQuantity, $updatedBuyDate, $updatedExpDate);
  }
  else if (!empty($_POST['addBtn']) && ($_POST['addBtn'] == "Add Food")){
    $foodID = addFoodCaloriesTempGroup($_POST['entered-food-name'], $_POST['cooked-status'], $_POST['calories'], $_POST['ideal_storage_temp'], $_POST['food_group']);
    addFoodToInventory($_SESSION['userID'], $foodID, $_POST['entered-food-location'], $_POST['entered-food-buy-date'], $_POST['entered-food-exp-date'], $_POST['entered-food-quantity']);
    $foods = getUserFood($_SESSION["userID"]);
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include("common-header.php"); ?>
  <link rel="stylesheet" href="styles.css">
</head>
<body> 
<?php include("navbar.php"); ?>
<div class="container">
  <button class="btn" onclick="toggle_filter_menu()" style="position:absolute; right: 1vw;">
    <img id=filter_icon src="filter_icon.png"/>
  </button>
</div>
<div id="filter_menu" style="display: none;">
  <div class="d-flex flex-column justify-content-center">
    <div class="container">
      <a class="closebtn" onclick="toggle_filter_menu()">&times;</a>
    </div>
    <div class="container">
      <h2 style="text-align: center;"><b>Sort By</b></h2>
    </div>
    <form name="filter_form" action="foods_page.php" method="post">  
      <div class="container">
        <ul id="filter_list" style="list-style-type: none"> 
            <li>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="filter_item" value="alphabetical" id="alphabetical_btn">
                <label class="form-check-label" for="alphabetical_btn">
                  Alphabetical(A-Z)
                </label>
              </div>
            </li>
            <li>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="filter_item" value = "expiration" id="expiration">
                <label class="form-check-label" for="expiration">
                  Expiration: Closest to Farthest
                </label>
              </div>
            </li>
            <li>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="filter_item" value="buy_date_old_new" id="buy_date_old_new">
                <label class="form-check-label" for="buy_date_old_new">
                  Buy Date: Oldest to Newest
                </label>
              </div>
            </li>
            <li>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="filter_item" value="buy_date_new_old" id="buy_date_new_old">
                <label class="form-check-label" for="buy_date_new_old">
                  Buy Date: Newest to Oldest
                </label>
              </div>
            </li>
        </ul>
      </div>
      <div class="container text-center">
        <input class="btn btn-primary" type="submit" name="actionBtn" value="Apply Filter" title="Apply Filter">
      </div>
    <form>
  </div>
</div> 
<div class="jumbotron feature">
        <div class="container">
            <h1>My Foods</h1>
            <p>Search, view, and enter new foods here!</p>
        </div>
</div>
<form name="mainForm" action="foods_page.php" method="post" >
  <div class="row justify-content-center" style = "padding-top: 10px; padding-bottom: 10px;">
    <div class="col-sm-6 col-md-4">
      <div class="input-group">
        <input type="text" class="form-control searchTerm" name="searchTerm" placeholder="Search for a food...">
        <div class="input-group-append">
          <button class="btn btn-primary" type="submit" name="actionBtn" title="Search">Search</button>
        </div>
      </div>
  </div> 
</div> 
  <div class="row justify-content-center" style = "padding-top: 10px; width: 95%;">  
    <table class="w3-table w3-bordered w3-card-4 center" style="width:70%">
      <thead>
      <tr style="background-color:#B0B0B0">
        <th width="20%"> Name     
        <th width="15%"> Location
        <th width="5%"> Quantity
        <th width="15%"> Buy Date
        <th width="15%"> Exp Date
        <th width="10%"> Delete
        <th width="10%"> Update Food
        <th width="10%"> Shopping List
      </tr>
      </thead>
    <?php foreach ($foods as $item): ?>
    <?php 
     ?>
      <tr>
        <td><?php echo $item['name']; ?></td>
        <td><?php echo $item['location']; ?></td>        
        <td><?php echo $item['quantity']; ?></td>  
        <td><?php echo $item['buy_date']; ?></td>  
        <td><?php echo $item['exp_date']; ?></td>     
        <td>
          <form action="foods_page.php" method="post">
            <input type="submit" name="actionBtn" value="Delete" class="btn btn-danger btn-sm" /> 
            <input type="hidden" name="food_to_delete", value="<?php echo $item['name']; ?>" />
          </form> 
        </td>
        <td>
        <button class="btn btn-primary btn-sm" id="update_food_btn" onclick="openUpdateFoodModal('<?php echo $item['name']; ?>', '<?php echo $item['location']; ?>', '<?php echo $item['quantity']; ?>', '<?php echo $item['buy_date']; ?>', '<?php echo $item['exp_date']; ?>', this)">Update</button> 
          <div class="modal" id="updateFoodModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="updateModalLabel">Update Food</h5>
                  <button type="button" class="close closeUpdateModalBtn" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div> 
                  <div class="modal-body">
                  <form action="foods_page.php" method="POST" > 
                    <input type="hidden" id="updateFoodName" name="updateFoodName">
                    <div class="form-group" style = "margin-top: 10px;">
                      <label for="updateLocation">Location:</label>
                      <input type="text" class="form-control" id="updatedLocation" name="updatedLocation" required>
                    </div>
                    <div class="form-group" style = "margin-top: 10px;">
                      <label for="quantity">Quantity:</label>
                      <input type="text" class="form-control" id="updatedQuantity" name="updatedQuantity" required>
                    </div>
                    <div class="form-group"  style = "margin-top: 10px;">
                      <label for="updateBuyDate">Buy Date:</label>
                      <input type="date" class="form-control" id="updatedBuyDate" name="updatedBuyDate" required>
                    </div>
                    <div class="form-group" style = "margin-top: 10px;">
                      <label for="updateExpDate">Expiration Date:</label>
                      <input type="date" class="form-control" id="updatedExpDate" value="<?php echo $item["exp_date"]; ?>" name="updatedExpDate" required>
                    </div>
                    <div class="modal-footer">
                      <button class="btn btn-primary float-right" type="submit" name="updateFoodBtn" value="updateFood" title="updateFoodBtn" style = "margin-top:10px">Update Food</button>
                    </div>
                  </form>
                  </div>
              </div>
            </div>
          </div>
       
        </td>
        <td> 
          <button class="btn btn-success" id="add_shopping_list_btn" onclick="openAddShoppingListModal('<?php echo $item['name']; ?>', this)">Add</button> 
          <div class="modal" id="addShoppingListModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="addModalLabel">Add to Shopping List</h5>
                  <button type="button" class="close closeAddModalBtn" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div> 
                <form action="shopping_list.php" method="POST"> 
                  <div class="modal-body">
                    <div class="form-group">
                      <label for="quantity">Quantity:</label>
                      <input type="text" class="form-control" id="quantity" name="quantity">
                      <input type="hidden" id="addShoppingListFoodName" name="food_name">
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary closeAddModalBtn">Close</button>
                      <input class="btn btn-primary" type="submit" name="actionBtn" value="Add to Shopping List" title="Add to Shopping List" />
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </td>              
      </tr>
    <?php endforeach; ?>
    </table>
  </div> 
</div>
<br>

<div class="modal" id="addFoodModal" tabindex="-1" role="dialog" aria-labelledby="addFoodModalLabel" aria-hidden="true" display = "none;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addFoodModalLabel">Add Food Before Adding to Your Inventory!</h5>
        <button type="button" class="close close_btn" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="foodForm" action="foods_page.php" method="post" class="form-border">
        <input type="hidden" name="name" value=<?php echo $_POST['name'] ?>>
        <input type="hidden" name="cooked-status" value=<?php echo $_POST['cooked-status'] ?>>
        <input type="hidden" name="entered-food-location" value=<?php echo $_POST['entered-food-location'] ?>>
        <input type="hidden" name="entered-food-buy-date" value=<?php echo $_POST['entered-food-buy-date'] ?>>
        <input type="hidden" name="entered-food-exp-date" value=<?php echo $_POST['entered-food-exp-date'] ?>>
        <input type="hidden" name="entered-food-quantity" value=<?php echo $_POST['entered-food-quantity'] ?>>
        <?php include("new_food_modal_form.php"); ?>
      </form>
    </div>
  </div>
</div>

  <div class="container mt-5">
      <div class="row justify-content-center">
          <div class="col-md-6">
              <h1 class="text-center mb-4">Enter new food!</h1>
              <form id="foodForm" action="foods_page.php" method="post" class="form-border">
                  <div class="form-group">
                      <label for="name">Food Name:</label>
                      <input type="text" id="name" class="form-control" name="name">
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
                  <div class="form-group">
                      <label for="cooked">Is it cooked?</label>
                      <select id="cooked-status" name="cooked-status" class="form-control cooked-status">
                          <option value="cooked">Cooked</option>
                          <option value="notCooked">Not Cooked</option>
                          <option value="NULL">N/A</option>
                      </select>
                  </div>
                  <div class="form-group text-center">
                      <input class="btn btn-primary" type="submit" name="foodActionBtn" value="Enter New Food" title="Enter New Food" />
                  </div>
              </form>
          </div>
      </div>
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

  function toggle_filter_menu(){
    $("#filter_menu").toggle();
  }
  const buttons = document.querySelectorAll('.update-button');
  buttons.forEach(function(button) {
    button.addEventListener('click', function() {
      let modal = this.nextElementSibling;
      modal.style.display = "block";
      positionModal(modal, this);
    });
  });
   function positionModal(modal, button) {
    let rect = button.getBoundingClientRect();
    let buttonTop = rect.top + window.pageYOffset;
    let buttonLeft = rect.left + window.pageXOffset;
    modal.style.top = buttonTop + button.offsetHeight + "px";
    modal.style.left = buttonLeft + "px";
  }

  const closeButtons = document.querySelectorAll('.closeModalBtn');
  closeButtons.forEach(function(button){
        button.addEventListener('click', function(){
          let modalName = this.getAttribute('data-food-name');
          console.log(modalName);
          closeModal(modalName);
        });
    }); 

  // Function to close the modal
  function closeModal(modalName) {
    console.log("closed Modal Function");
    let modalID = modalName+"myModal";
    console.log(modalID);
    let modal = document.getElementById(modalName+"myModal");
    modal.style.display = "none";
  }

  $(".closeAddModalBtn").click(function(){
    $("#addShoppingListModal").hide();
  })

  function openAddShoppingListModal(food_name){
    $("#addShoppingListFoodName").val(food_name);
    // change label to display food name
    $("#addModalLabel").html("Add " + food_name + " to Shopping List");
    $("#addShoppingListModal").show();
  }


  function openUpdateFoodModal(food_name, food_location, food_quantity, food_buy_date, food_exp_date){
    $("#updateFoodName").val(food_name);
    $("#updatedLocation").val(food_location);
    $("#updatedQuantity").val(food_quantity);
    $("#updatedBuyDate").val(food_buy_date);
    $("#updatedExpDate").val(food_exp_date);
    // change label to display food name
    $("#updateModalLabel").html("Update " + food_name);
    $("#updateFoodModal").show();
  }

  $(".closeUpdateModalBtn").click(function(){
    $("#updateFoodModal").hide();
  })

</script>
