<?php
function getUserMeals($userID, $name, $number_of_servings, ){
    global $db; 
    $query = "select * from user_has_food where userID=:userID";
    $statement = $db->prepare($query);
    $statement->bindValue(':userID', $userID);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}

function getFoodName($foodID){
    global $db;
    $query = "select name from Food where id=:foodID";
    $statement = $db->prepare($query);
    $statement->bindValue(':foodID', $foodID);
    $statement->execute();
    $results = $statement->fetch();
    $statement->closeCursor();
    return $results;
}
?>