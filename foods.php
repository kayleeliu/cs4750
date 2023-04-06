<?php
function getUserFood($userID){
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
    $query = "select * from Food where id=:foodID";
    $statement = $db->prepare($query);
    $statement->bindValue(':foodID', $foodID);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}
?>