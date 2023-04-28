<?php
function getUserShoppingList($userID){
    global $db; 
    $query = "select * from wants_to_buy w join Food f on w.foodID = f.id where userID=:userID";
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}

function boughtFood($userID, $foodID){
    global $db;
    $query = "DELETE FROM wants_to_buy WHERE userID=:userID AND foodID=:foodID"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->bindValue(':foodID', $foodID);
    $statement->execute();
    $statement->closeCursor(); 
}

function deleteFoodFromShoppingList($userID, $foodID){
    global $db;
    $query = "DELETE FROM wants_to_buy WHERE userID=:userID AND foodID=:foodID"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->bindValue(':foodID', $foodID);
    $statement->execute();
    $statement->closeCursor(); 
}

function addFoodToShoppingList($userID, $foodID, $quantity){
    global $db;
    $query = "insert into wants_to_buy (userID, foodID, quantity) values (:userID, :foodID, :quantity)"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->bindValue(':foodID', $foodID);
    $statement->bindValue(':quantity', $quantity);
    $statement->execute();
    $statement->closeCursor(); 
}
