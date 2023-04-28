<?php
function createMeal($name, $num_of_servings, $prep_time, $calorie_count, $time_of_day){
    global $db; 
    $userID = $_SESSION["userID"];
    $query1 = "INSERT INTO Meal(num_of_servings, prep_time, calorie_count, time_of_day, name) VALUES (:num_of_servings, :prep_time, :calorie_count, :time_of_day, :name)";
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
            $mealI
        ]);
} 

function deleteMeal($mealID) {
    global $db;
    $result = $db
        ->prepare("DELETE FROM Meal WHERE id=?")
        ->execute([$mealID]);
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

function deleteFoodFromMeal($foodID) {
    echo $foodID;
    global $db;
    $db
        ->prepare("DELETE FROM is_part_of WHERE foodID=?")
        ->execute([$foodID]);
}

function getFoodsOfMeal($mealID) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM is_part_of JOIN Food ON Food.id=is_part_of.FoodID WHERE mealID=?");
    $stmt->execute([$mealID]); 
    return $stmt->fetchAll();
}

?>