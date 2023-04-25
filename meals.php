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
?>