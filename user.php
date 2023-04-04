<?php
function addUser($username, $password, $daily_calorie_count=NULL, $meals_per_day=NULL) {
    global $db;
    $hash = password_hash(password, PASSWORD_DEFAULT);
    $query = "INSERT INTO User(username, password, daily_calorie_count, meals_per_day) VALUES (:username, :password, :daily_calorie_count, :meals_per_day)";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':password', $hash);
    $statement->bindValue(':daily_calorie_count', $daily_calorie_count);
    $statement->bindValue(':meals_per_day', $meals_per_day);
    $statement->execute();
    $statement->closeCursor();
}

function updateUser($username, $daily_calorie_count=NULL, $meals_per_day=NULL) {
    global $db;
    $query = "UPDATE User SET daily_calorie_count=:daily_calorie_count, meals_per_day=:meals_per_day WHERE username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(':daily_calorie_count', $daily_calorie_count);
    $statement->bindValue(':meals_per_day', $meals_per_day);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $statement->closeCursor();
}
?>