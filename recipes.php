<?php

// function getFoodName($foodMade){
//     global $db;
//     $query = "SELECT name FROM Recipe r JOIN Food f on r.foodMade = f.id WHERE id=:foodMade";
//     $statement = $db->prepare($query);
//     $statement->bindValue(':foodMade', $foodMade);
//     $statement->execute();
//     $results = $statement->fetchAll();
//     $statement->closeCursor();
//     return $results;
// }

function checkFoodExists($foodName){
    global $db;
    $query = "select name from Recipe r join Food f on r.foodMade = f.id where f.name=:foodName";
    $statement = $db->prepare($query);
    $statement->bindValue(':foodName', $foodName);
    $statement->execute();
    $results = $statement->fetchAll();
    echo count($results);
    $statement->closeCursor();
    return $results;
}

function createRecipe($prep_time, $foodID, $userID, $link){
    global $db;
    $query = "INSERT INTO Recipe(prep_time, foodMade, userID, link) VALUES (:prep_time, :foodID, :userID, :link)";
    $statement = $db->prepare($query);
    $statement->bindValue(':prep_time', $prep_time ? $prep_time : null);
    $statement->bindValue(':foodID', $foodID);
    $statement->bindValue(':userID', $userID);
    $statement->bindValue(':link', $link ? $link : null);
    $statement->execute();
    $statement->closeCursor();
}
?>