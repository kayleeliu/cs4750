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
      addFood($_POST['entered-food-name'], $_POST['cooked-status'], $_SESSION['userID'], $_POST['entered-food-location'], $_POST['entered-food-buy-date'], $_POST['entered-food-exp-date'], $_POST['entered-food-quantity']);
  }
  else if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Delete")){
    $foodID = getFoodId($_POST['food_to_delete']);
    deleteFood($foodID);
    $foods = getUserFood($_SESSION["userID"]);
  }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

  <link rel="stylesheet" href="styles.css">
</head>
<body>  
<?php include("navbar.html"); ?>
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
<form name="mainForm" action="foods_page.php" method="post">
  <div class="row justify-content-center" style="padding:20px;">
    <div class="search">
        <input type="text" class="searchTerm" name="searchTerm" placeholder="Search for a food...">
        <input class="btn-sm btn-primary" type="submit" name="actionBtn" value="Search" title="Search" />
    </div>
  </div>
</form>
<div class="row justify-content-center">  
    <table class="w3-table w3-bordered w3-card-4 center" style="width:70%">
      <thead>
      <tr style="background-color:#B0B0B0">
        <th width="20%"> Name     
        <th width="20%"> Location
        <th width="20%"> Quantity
        <th width="20%"> Buy Date
        <th width="20%"> Exp Date
        <th width="20%"> Delete
        <th width="20%"> Update Food
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
          <button id="update-button" class = "update-button">Update</button>
          <div id="<?php echo $item['name']; ?>myModal" style="display: none;">
          <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="updateModalLabel">Update Food Item</h5>
              <?php echo $item['name']; ?>
              <button type="button" class="closeModalBtn" data-dismiss="modal" aria-label="Close" id="closeModalBtn" data-food-name= "<?php echo $item['name']; ?>">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="update_food.php" method="POST">
                <div class="form-group">
                  <label for="location">Location:</label>
                  <input type="text" class="form-control" id="location" name="location">
                </div>

                <div class="form-group">
                  <label for="buyDate">Buy Date:</label>
                  <input type="date" class="form-control" id="buyDate" name="buyDate">
                </div>

                <div class="form-group">
                  <label for="expDate">Expiration Date:</label>
                  <input type="date" class="form-control" id="expDate" name="expDate">
                </div>

                <div class="form-group">
                  <label for="quantity">Quantity:</label>
                  <input type="number" class="form-control" id="quantity" name="quantity" required>
                </div>

                <button type="submit" class="btn btn-primary">Update Food Item</button>
              </form>
            </div>
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

  <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="text-center mb-4">Enter new food!</h1>
                <form id="foodForm" action="foods_page.php" method="post" class="form-border">
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
                        <label for="entered-food-location">Where're you putting it:</label>
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
</script>
