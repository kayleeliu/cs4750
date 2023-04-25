<?php
function getUserFood($userID){
    global $db; 
    $query = "select * from user_has_food u join Food f on u.foodID = f.id where userID=:userID";
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->execute();
    $results = $statement->fetchAll();
    return $results;
}

function getUserFoodAlphabetical($userID){
    global $db; 
    $query = "select * from user_has_food u join Food f on u.foodID = f.id where userID=:userID order by name";
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->execute();
    $results = $statement->fetchAll();
    return $results;
}

function getUserFoodExpiration($userID){
    global $db; 
    $query = "select * from user_has_food u join Food f on u.foodID = f.id where userID=:userID order by exp_date";
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->execute();
    $results = $statement->fetchAll();
    return $results;
}

function getUserFoodBuyDateOldNew($userID){
    global $db; 
    $query = "select * from user_has_food u join Food f on u.foodID = f.id where userID=:userID order by buy_date";
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->execute();
    $results = $statement->fetchAll();
    return $results;
}

function getUserFoodBuyDateNewOld($userID){
    global $db; 
    $query = "select * from user_has_food u join Food f on u.foodID = f.id where userID=:userID order by buy_date DESC";
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->execute();
    $results = $statement->fetchAll();
    return $results;
}

// select id from user entered food from food
//select rows where foodID = selected id from getuserfoods

function getFoodId($foodName){
    global $db;
    $query = "select id from Food where name=:foodName";
    $statement = $db->prepare($query);
    $statement->bindValue(':foodName', $foodName);
    $statement->execute();
    $results = $statement->fetch();
    $statement->closeCursor();
    return $results;
}

function searchUserFood($userID, $fID){
    global $db;
    // $userFood = getUserFood($userID);
    // $query = "SELECT * FROM $userFood WHERE foodID=:fID"
    $query = "SELECT location, buy_date, exp_date, quantity from user_has_food u join Food f on u.foodID = f.id  WHERE userID=:userID AND foodID=:fID";
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->bindValue(':fID', $fID);
    $statement->execute();
    $results = $statement->fetch();
    $statement->closeCursor();
    return $results;
}

?>