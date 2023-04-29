<?php
function checkFoodExists($foodName){
    global $db;
    $query = "select name from Recipe r join Food f on r.foodMade = f.id where f.name=:foodName";
    $statement = $db->prepare($query);
    $statement->bindValue(':foodName', $foodName);
    $statement->execute();
    $results = $statement->fetchAll();
    echo $results[0];
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
    echo "<script>alert('Recipe creation successful!');</script>";
    $statement->closeCursor();
}

function getRecipeFoods($userID) {
    global $db;
    $statement = $db->prepare("SELECT * FROM Recipe WHERE userID=:userID");
    $statement->bindValue(':userID', $userID);
    $statement->execute();
    return $statement->fetchAll();
}

function getRecipe($recipeID) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM Recipe WHERE id=?");
    $stmt->execute([$recipeID]);
    return $stmt->fetch();
}

function madeRecipe($recipeID, $userID) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM Recipe WHERE id=? AND userID=?");
    $stmt->execute([$recipeID, $userID]);
    return $stmt->rowCount() > 0;
}

function updateRecipe($prep_time, $link, $id) {
    global $db;
    $query = "UPDATE Recipe SET prep_time=:prep_time, link=:link WHERE id=:id";
    $statement = $db->prepare($query);
    $statement->bindValue(':prep_time', $prep_time);
    $statement->bindValue(':link', $link);
    $statement->bindValue(':id', $id);
    $statement->execute();
    $statement->closeCursor();
}

function deleteRecipe($id) {
    global $db;
    $result = $db
        ->prepare("DELETE FROM Recipe WHERE id=?")
        ->execute([$id]);
}
?>