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

    return $lastFoodID;
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
    $statement->bindValue(':buy_date', $buy_date ? $buy_date : NULL);
    $statement->bindValue(':exp_date', $exp_date ? $exp_date : NULL);
    $statement->bindValue(':quan', $quantity ? $quantity : 0);
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
    $statement->bindValue(':buy_date', $buy_date ? $buy_date : NULL);
    $statement->bindValue(':exp_date', $exp_date ? $exp_date : NULL);
    $statement->bindValue(':quantity', $quantity);
    return $statement->execute();
    $statement->closeCursor();
}

function updateFood($userID, $foodID, $location, $quantity, $buy_date, $exp_date){
    global $db;
    $query = "UPDATE user_has_food SET location = :newLocation, quantity = :newQuantity, buy_date = :newBuyDate, exp_date = :newExpDate WHERE userID = :userID AND foodID = :foodID";
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->bindValue(':foodID', $foodID);
    $statement->bindValue(':newLocation', $location);
    $statement->bindValue(':newBuyDate', $buy_date ? $buy_date : NULL);
    $statement->bindValue(':newExpDate', $exp_date ? $exp_date : NULL);
    $statement->bindValue(':newQuantity', $quantity ? $quantity : NULL);
    return $statement->execute();
    $statement->closeCursor();
}

function getAllFoods() {
    global $db;
    $stmt = $db->prepare("WITH t1 AS 
    (SELECT Food.name, Food.cooked, food_calories_temp.calories, food_calories_temp.ideal_storage_temp FROM Food LEFT JOIN food_calories_temp ON Food.name=food_calories_temp.name AND Food.cooked=food_calories_temp.cooked), t2 AS (SELECT * FROM food_group)
    SELECT t1.name, t1.cooked, t1.calories, t1.ideal_storage_temp, t2.food_group FROM t1 LEFT JOIN t2 ON t1.name=t2.name");
    $stmt->execute();
    return $stmt->fetchAll();
}

function addFoodTemp($name, $cookedStatus, $calories, $idealStorageTemp, $foodGroup){
    global $db;
    $lastFoodID = 0;
    $lastFoodID = getFoodId($name);

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

?>