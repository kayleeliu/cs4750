<?php
function getUserFood($userID){
    global $db; 
    $query = "select * from user_has_food u join Food f on u.foodID = f.id where userID=:userID";
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}

function getUserFoodAlphabetical($userID){
    global $db; 
    $query = "select * from user_has_food u join Food f on u.foodID = f.id where userID=:userID order by name";
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}

function getUserFoodExpiration($userID){
    global $db; 
    $query = "select * from user_has_food u join Food f on u.foodID = f.id where userID=:userID order by exp_date";
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}

function getUserFoodBuyDateOldNew($userID){
    global $db; 
    $query = "select * from user_has_food u join Food f on u.foodID = f.id where userID=:userID order by buy_date";
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}

function getUserFoodBuyDateNewOld($userID){
    global $db; 
    $query = "select * from user_has_food u join Food f on u.foodID = f.id where userID=:userID order by buy_date DESC";
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}

function searchUserFood($userID, $name){
    global $db;
    $query = "select * from user_has_food u join Food f on u.foodID = f.id where userID=:userID and name=:name";
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->bindValue(':name', $name);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}

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

function addFood($name, $cookedStatus, $userID, $foodLocation, $foodBuyDate, $foodExpDate, $foodQuantity){
    global $db;
    $query = "INSERT INTO Food (name, cooked) VALUES (:foodName, :cooked)";
    $statement = $db->prepare($query);
    $statement->bindValue(':foodName', $name);
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
    $lastFoodID = $db->lastInsertId();
    $userHasFoodquery = "INSERT INTO user_has_food 
                        (userID, foodID, location, buy_date, exp_date, quantity) 
                        VALUES (:inputUserID, :inputFoodID, :inputLocation, :input_buy, :input_exp, :input_quant)";
    $userHasFoodStatement = $db->prepare($userHasFoodquery);
    
    $userHasFoodStatement->bindValue(':inputUserID', $userID);
    $userHasFoodStatement->bindValue(':inputFoodID', $lastFoodID);
    $userHasFoodStatement->bindValue(':inputLocation', $foodLocation);
    $userHasFoodStatement->bindValue(':input_buy', $foodBuyDate);
    $userHasFoodStatement->bindValue(':input_exp', $foodExpDate);
    $userHasFoodStatement->bindValue(':input_quant', $foodQuantity);

    $userHasFoodStatement->execute();
    $userHasFoodStatement->closeCursor();
}

function deleteFood($id){
    global $db;
    $query = "DELETE FROM user_has_food WHERE foodID=:id"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id);
    $statement->execute();
    $statement->closeCursor(); 
}

?>