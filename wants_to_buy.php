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

function addFoodToDB($foodName, $cookedStatus){
    global $db;
    $query = "insert into Food (name, cooked) values (:foodName, :cooked)"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':foodName', $foodName);
    if($cookedStatus == "cooked"){
        $cookedStatusBool = true;
    }else if($cookedStatus == "notCooked"){
        $cookedStatusBool = false;
    }else{
        $cookedStatusBool = NULL;
    }
    $statement->bindValue(':cooked', $cookedStatusBool, PDO::PARAM_BOOL);
    $statement->execute();
    $statement->closeCursor(); 
}

function checkFoodInShoppingList($userID, $foodID){
    global $db;
    $query = "select count(*) from wants_to_buy where userID=:userID and foodID=:foodID"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->bindValue(':foodID', $foodID);
    $statement->execute();
    $results = $statement->fetch();
    $statement->closeCursor(); 
    return $results['count(*)'];
}

function updateUserShoppingList($userID, $foodID, $quantity){
    global $db;
    $query = "update wants_to_buy set quantity=:quan + quantity where userID=:userID and foodID=:foodID"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->bindValue(':foodID', $foodID);
    $statement->bindValue(':quan', $quantity);
    $statement->execute();
    $statement->closeCursor(); 
}

function addFoodToDBAndShoppingList($userID, $foodName, $cookedStatus, $quantity){
    global $db;
    $query = "call addFoodToDBAndShoppingList(:userID, :foodName, :cooked, :quantity)";
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->bindValue(':foodName', $foodName);
    if($cookedStatus == "cooked"){
        $cookedStatusBool = true;
    }else if($cookedStatus == "notCooked"){
        $cookedStatusBool = false;
    }else{
        $cookedStatusBool = NULL;
    }
    $statement->bindValue(':cooked', $cookedStatusBool, PDO::PARAM_BOOL);
    $statement->bindValue(':quantity', $quantity);
    $statement->execute();
    $statement->closeCursor(); 
}
