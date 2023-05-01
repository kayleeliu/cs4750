<?php
function createMeal($name, $num_of_servings, $prep_time, $calorie_count, $time_of_day){
    global $db;
    $userID = $_SESSION["userID"];
    $query1 = "INSERT INTO Meal (num_of_servings, prep_time, calorie_count, time_of_day, name) VALUES (:num_of_servings, :prep_time, :calorie_count, :time_of_day, :name)";
    $query2 = "INSERT INTO designed VALUES (:userID, :mealID)";


    $statement1 = $db->prepare($query1);
    $statement2 = $db->prepare($query2);


    $statement1->bindValue(':num_of_servings', $num_of_servings ? $num_of_servings : null);
    $statement1->bindValue(':prep_time', $prep_time ? $prep_time : null);
    $statement1->bindValue(':calorie_count', $calorie_count ? $calorie_count : null);
    $statement1->bindValue(':time_of_day', $time_of_day);
    $statement1->bindValue(':name', $name);


    if($statement1->execute()) {
        echo "<script>alert('Meal creation successful!');</script>";
        $statement2->bindValue(':userID', $userID);
        $statement2->bindValue(":mealID", $db->lastInsertId());
        $statement2->execute();
    } else {
        echo "<script>alert('Meal creation failed!');</script>";
    }


    $statement1->closeCursor();
    $statement2->closeCursor();
}


function updateMeal($mealID, $name, $num_of_servings, $prep_time, $calorie_count, $time_of_day) {
    global $db;
    $db
        ->prepare("UPDATE Meal SET name= ?, num_of_servings=?, prep_time=?, calorie_count=?, time_of_day=? WHERE id=?")
        ->execute([
            $name,
            $num_of_servings ? $num_of_servings : NULL,
            $prep_time ? $prep_time : NULL,
            $calorie_count ? $calorie_count : NULL,
            $time_of_day, 
            $mealID
        ]);
}


function deleteMeal($mealID) {
    global $db;
    echo "mealID: ".$mealID."\n";
    echo
    $db
        ->prepare("DELETE FROM Meal WHERE id=?")
        ->execute([$mealID]) ? "success" : "fail";
}


function getMeal($mealID) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM Meal WHERE id=?");
    $stmt->execute([$mealID]);
    return $stmt->fetch();
}


function madeMeal($mealID, $userID) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM designed WHERE mealID=? AND userID=?");
    $stmt->execute([$mealID, $userID]);
    return $stmt->rowCount() > 0;
}


function getMealsUserDesigned($userID) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM designed JOIN Meal ON Meal.id=designed.mealID WHERE designed.userID=?");
    $stmt->execute([$userID]);
    return $stmt->fetchAll();
}


function addFoodToMeal($foodID, $mealID, $quantity){
    global $db;
    $query = "INSERT INTO is_part_of VALUES (:foodID, :mealID, :quantity)";
    $statement = $db->prepare($query);
    $statement->bindValue(':foodID', $foodID);
    $statement->bindValue(':mealID', $mealID);
    $statement->bindValue(':quantity', $quantity);
    $statement->execute();
    $statement->closeCursor();
}


function deleteFoodFromMeal($foodID, $mealID) {
    echo $foodID;
    global $db;
    $db
        ->prepare("DELETE FROM is_part_of WHERE foodID=? AND mealID=?")
        ->execute([$foodID, $mealID]);
}


function getFoodsOfMeal($mealID) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM is_part_of JOIN Food ON Food.id=is_part_of.FoodID WHERE mealID=?");
    $stmt->execute([$mealID]);
    return $stmt->fetchAll();
}


function getAllMeals($filterString="", $params=[]) {
    global $db;
    $filterString = $filterString ? "WHERE ".$filterString : "";
    $stmt = $db->prepare("SELECT * FROM Meal ".$filterString);
    $stmt->execute($params);
    return $stmt->fetchAll();
}


function getMealsUserHas($userID) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM Meal JOIN user_has_meal ON Meal.id=user_has_meal.mealID WHERE userID=?");
    $stmt->execute([$userID]);
    return $stmt->fetchAll();
    // $stmt = $db->prepare("SELECT * FROM Meal JOIN user_has_meal ON Meal.id=user_has_meal.mealID WHERE userID=?");
    // $stmt->execute([$userID]);
    // return $stmt->fetchAll();
}


function giveUserMeal($userID, $mealID) {
    global $db;
    $db
        ->prepare("INSERT INTO user_has_meal VALUES (?, ?, FALSE)")
        ->execute([$userID, $mealID]);
}


function setMealEaten($userID, $mealID, $eaten) {
    global $db;
    $db
        ->prepare("UPDATE user_has_meal SET eaten=? WHERE userID=? AND mealID=?")
        ->execute([$eaten ? 1 : 0, $userID, $mealID]);  
}


function deleteMealFromUser($userID, $mealID) {
    global $db;
    $db
        ->prepare("DELETE FROM user_has_meal WHERE userID=? AND mealID=?")
        ->execute([$userID, $mealID]);
}


function copyMealToUser($userID, $mealID) {
    global $db;
    echo $db
        ->prepare("INSERT INTO Meal (num_of_servings, prep_time, calorie_count, time_of_day, name) SELECT num_of_servings, prep_time, calorie_count, time_of_day, name FROM Meal WHERE id=?")
        ->execute([$mealID]) ? "success" : "fail";
    echo "\n";
    $newMealID = $db->lastInsertId();
    $db
        ->prepare("INSERT INTO designed VALUES (?, ?)")
        ->execute([$userID, $newMealID]);
    $db
        ->prepare("INSERT INTO is_part_of SELECT foodID, ?, quantity FROM is_part_of WHERE mealID=?")
        ->execute([$newMealID, $mealID]);
    return $newMealID;
}


?>
