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

function deleteFriend($friend_to_delete) {
    global $db;
    $query = "DELETE FROM friends WHERE name=:friend_to_delete";
    $statement = $db->prepare($query);
    $statement->bindValue(":friend_to_delete", $friend_to_delete);
    $statement->execute();
    $statement->closeCursor();
}

function getFriendByName($friend_name) {
    global $db;
    $query = "SELECT * FROM friends WHERE name=:friend_name";
    $statement = $db->prepare($query);
    $statement->bindValue(":friend_name", $friend_name);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    return $result;
}

function updateFriend($name, $major, $year) {
    global $db;
    $query = "UPDATE friends SET major=:major, year=:year WHERE name=:name";
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $name);
    $statement->bindValue(':major', $major);
    $statement->bindValue(':year', $year);
    $statement->execute();
    $statement->closeCursor();
}
?>