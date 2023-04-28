<?php

function getFoodName($foodMade){
    global $db;
    $query = "SELECT name FROM Recipe r JOIN Food f on r.foodMade = f.id WHERE id=:foodMade";
    $statement = $db->prepare($query);
    $statement->bindValue(':foodMade', $foodMade);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}

function getFoodID($foodName){
    global $db;
    $query = "select * from Recipe r join Food f on r.foodMade = f.id where f.name=:foodName";
    $statement = $db->prepare($query);
    $statement->bindValue(':foodName', $foodName);
    $statement->execute();
    $results = $statement->fetchAll();
    echo count($results);
    $statement->closeCursor();
    return $results;
}

function createRecipe($prep_time, $foodMade, $link){
    global $db;
    $userID = $_SESSION["userID"];
    $query = "INSERT INTO Recipe(prep_time, foodMade, userID, link) VALUES (:prep_time, :foodMade, :userID, :link)";
    $statement = $db->prepare($query);
    $statement->bindValue(':prep_time', $prep_time);
    $statement->bindValue(':foodMade', $foodMade);
    $statement->bindValue(':link', $link);
    $statement->execute();
    $statement->closeCursor();
}
?>