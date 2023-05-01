<?php

require("connect-db.php");
require('foods.php');
require('user.php');

session_start();

$foods = getAllFoods();

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
          <h1>All Foods</h1>
          <p>View all the foods other people have used</p>
      </div>
  </div>
    <div class="container">
    <div class="row justify-content-center">  
        <table class="w3-table w3-bordered w3-card-4 center" style="width:70%">
        <thead>
        <tr style="background-color:#B0B0B0">
            <th width="20%"> Name     
            <th width="15%"> Cooked
            <th width="15%"> Calories
            <th width="15%"> Ideal Storage Temperature (F)
            <th width="15%"> Food Group
            <th width="15%"> Add to List
        </tr>
        </thead>
        <?php foreach ($foods as $food): ?>
        <?php 
        ?>
        <tr>
            <td><?php echo $food['name']; ?></td>
            <td><?php echo isset($food['cooked']) ? ($food['cooked'] ? "Yes" : "No") : "" ?></td>        
            <td><?php echo $food['calories'] ?></td>  
            <td><?php echo $food['ideal_storage_temp'] ?></td>  
            <td><?php echo $food['food_group'] ?></td>   
            <td>
            <button class="btn btn-success btn-sm" id="add_shopping_list_btn" onclick="openAddShoppingListModal('<?php echo $food['name']; ?>', this)">Add</button> 
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
                      <input type="text" class="form-control" id="quantity" name="quantity" required>
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
<body>

<script>
    function openAddShoppingListModal(food_name){
        $("#addShoppingListFoodName").val(food_name);
        // change label to display food name
        $("#addModalLabel").html("Add " + food_name + " to Shopping List");
        $("#addShoppingListModal").show();
    }

    $(".closeAddModalBtn").click(function(){
    $("#addShoppingListModal").hide();
  })
</script>