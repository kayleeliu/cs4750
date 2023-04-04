<?php
function addUser($username, $password, $daily_calorie_count=NULL, $meals_per_day=NULL) {
    global $db;
    $query = "INSERT INTO User(username, password, daily_calorie_count, meals_per_day) VALUES (:username, :password, :daily_calorie_count, :meals_per_day)";
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $name);
    $statement->bindValue(':major', $major);
    $statement->bindValue(':year', $year);
    $statement->execute();
    $statement->closeCursor();
}
?>