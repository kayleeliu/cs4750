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
    return $db->lastInsertId();
    $statement->closeCursor();
}

function getRecipesByUser($userID) {
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

function addFoodToRecipe($recipeID, $foodID, $quantity, $units) {
    global $db;
    $db
        ->prepare("INSERT INTO is_made_from VALUES (?, ?, ?, ?)")
        ->execute([$recipeID, $foodID, $quantity, $units]);
}

function getFoodRecipeMakes($recipeID) {
    global $db;
    $stmt = $db->prepare("SELECT foodMade FROM Recipe WHERE id=?");
    $stmt->execute([$recipeID]);
    return $stmt->fetch()["foodMade"];
}

function getFoodsRecipeUses($recipeID) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM is_made_from JOIN Food on Food.id=is_made_from.foodID WHERE recipeID=?");
    $stmt->execute([$recipeID]);
    return $stmt->fetchAll();
}

function deleteIngredientFromRecipe($recipeID, $foodID) {
    global $db;
    $db
        ->prepare("DELETE FROM is_made_from WHERE foodID=? AND recipeID=?")
        ->execute([$foodID, $recipeID]);
}

?>