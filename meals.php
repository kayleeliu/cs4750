<?php
function createMeal($userID, $name, $num_of_servings, $prep_time, $calorie_count, $time_of_day){
    if(num_of_servings == "") $num_of_servings = NULL;
    if(prep_time == "") $prep_time = NULL;
    if(calorie_count == "") $calorie_count = NULL;
    if(time_of_day == "") $time_of_day = NULL;
    // echo $name;
    // echo $num_of_servings;
    // echo $prep_time;
    // echo $calorie_count;
    // echo $time_of_day;

    global $db; 
    $query1 = "INSERT INTO Meal(num_of_servings, prep_time, calorie_count, time_of_day, name) VALUES (:num_of_servings, :prep_time, :calorie_count, :time_of_day, :name)";
    $query2 = "INSERT INTO designed VALUES (:userID, IDENT_CURRENT('Meal'))";

    $statement1 = $db->prepare($query1);
    $statement2 = $db->prepare($query2);

    $statement1->bindValue(':num_of_servings', $num_of_servings);
    $statement1->bindValue(':prep_time', $prep_time);
    $statement1->bindValue(':calorie_count', $calorie_count);
    $statement1->bindValue(':time_of_day', $time_of_day);
    $statement1->bindValue(':name', $name);
    $statement2->bindValue(':userID', $userID);

    $statement1->execute();
    $statement2->execute();

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