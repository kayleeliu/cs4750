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
    return $results["id"];
}

function getFoodName($foodID){
    global $db;
    $query = "select name from Food where id=:foodID";
    $statement = $db->prepare($query);
    $statement->bindValue(':foodID', $foodID);
    $statement->execute();
    $results = $statement->fetch();
    $statement->closeCursor();
    return $results["name"];
}

function foodNameExists($name) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM Food WHERE name=?");
    $stmt->execute([$name]);
    return $stmt->rowCount();
}

function addFood($name, $cookedStatus, $userID, $foodLocation, $foodBuyDate, $foodExpDate, $foodQuantity){
    global $db;
    $lastFoodID = 0;
    if(getFoodId($name)){ 
        //the food exists in Food table already
        $lastFoodID = getFoodId($name);
    }else{
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
    }   
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

function addFoodCaloriesTempGroup($name, $cookedStatus, $calories, $idealStorageTemp, $foodGroup){
    global $db;
    $lastFoodID = 0;
    if(getFoodId($name)){ 
        //the food exists in Food table already
        $lastFoodID = getFoodId($name);
    }else{
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
    }

    $foodCaloriesTempQuery = "INSERT INTO food_calories_temp (name, cooked, calories, ideal_storage_temp) VALUES (:name, :cookedStatus, :calories, :idealStorageTemp)";
    $foodCaloriesTempStatement = $db->prepare($foodCaloriesTempQuery);
    $foodCaloriesTempStatement->bindValue(':name', $name);
    if($cookedStatus == "cooked"){
        $cookedStatusBool = true;
    }else if($cookedStatus == "notCooked"){
        $cookedStatusBool = false;
    }else{
        $cookedStatusBool = NULL;
    }
    $foodCaloriesTempStatement->bindValue(':cookedStatus', $cookedStatusBool, PDO::PARAM_BOOL);


    $foodCaloriesTempStatement->bindValue(':calories', $calories);
    $foodCaloriesTempStatement->bindValue(':idealStorageTemp', $idealStorageTemp);
    $foodCaloriesTempStatement->execute();
    $foodCaloriesTempStatement->closeCursor();

    $foodGroupQuery = "INSERT INTO food_group (name, food_group) VALUES (:name, :foodGroup)";
    $foodGroupStatement = $db->prepare($foodGroupQuery);
    $foodGroupStatement->bindValue(':name', $name);
    $foodGroupStatement->bindValue(':foodGroup', $foodGroup);
    $foodGroupStatement->execute();
    $foodGroupStatement->closeCursor();
}

function deleteFood($id){
    global $db;
    $query = "DELETE FROM user_has_food WHERE foodID=:id"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id);
    $statement->execute();
    $statement->closeCursor(); 
}

function checkUserHasFood($userID, $foodID){
    global $db;
    $query = "select count(*) from user_has_food where userID=:userID and foodID=:foodID";
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->bindValue(':foodID', $foodID);
    $statement->execute();
    $results = $statement->fetch();
    $statement->closeCursor();
    return $results;
}

function updateUserFood($userID, $foodID, $location, $buy_date, $exp_date, $quantity){
    global $db;
    $query = "update user_has_food set location=:location, buy_date=:buy_date, exp_date=:exp_date, quantity=quantity+:quan where userID=:userID and foodID=:foodID";
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->bindValue(':foodID', $foodID);
    $statement->bindValue(':location', $location);
    $statement->bindValue(':buy_date', $buy_date);
    $statement->bindValue(':exp_date', $exp_date);
    $statement->bindValue(':quan', $quantity);
    $statement->execute();
    $statement->closeCursor();
}

function addFoodToInventory($userID, $foodID, $location, $buy_date, $exp_date, $quantity){
    global $db;
    $query = "insert into user_has_food (userID, foodID, location, buy_date, exp_date, quantity) values (:userID, :foodID, :location, :buy_date, :exp_date, :quantity)";
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->bindValue(':foodID', $foodID);
    $statement->bindValue(':location', $location);
    $statement->bindValue(':buy_date', $buy_date);
    $statement->bindValue(':exp_date', $exp_date);
    $statement->bindValue(':quantity', $quantity);
    $statement->execute();
    $statement->closeCursor();
}

function updateFood($userID, $foodID, $location, $quantity, $buy_date, $exp_date){
    global $db;
    $query = "UPDATE user_has_food SET location = :newLocation, quantity = :newQuantity, buy_date = :newBuyDate, exp_date = :newExpDate WHERE userID = :userID AND foodID = :foodID";
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->bindValue(':foodID', $foodID);
    $statement->bindValue(':newLocation', $location);
    $statement->bindValue(':newQuantity', $quantity);
    $statement->bindValue(':newBuyDate', $buy_date);
    $statement->bindValue(':newExpDate', $exp_date);
    $statement->execute();
    $statement->closeCursor();
}


?>