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

?>