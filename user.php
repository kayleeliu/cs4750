<?php

function checkUsernameExists($username){
    global $db;
    $query = "select count(*) from User where username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $results = $statement->fetch();
    $statement->closeCursor();
    return $results;
}

function addUser($username, $password, $daily_calorie_count=NULL, $meals_per_day=NULL) {
    if($daily_calorie_count == "") $daily_calorie_count = NULL;
    if($meals_per_day == "") $meals_per_day = NULL;
    global $db;
    $hash = crypt($password, '$5$databaseencryption$');
    $query = "insert into User (username, password, daily_calorie_count, meals_per_day) values (:username, :password, :daily_calorie_count, :meals_per_day)";
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
    $query = "update User set daily_calorie_count=:daily_calorie_count, meals_per_day=:meals_per_day WHERE username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(':daily_calorie_count', $daily_calorie_count);
    $statement->bindValue(':meals_per_day', $meals_per_day);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $statement->closeCursor();
}

function login($username, $password){
    global $db;
    $query = "select count(*) from User where username=:username and password=:password";
    $hash = crypt($password, '$5$databaseencryption$');
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':password', $hash);
    $statement->execute();
    $results = $statement->fetch();
    $statement->closeCursor();
    return $results;
}

function getUserID($username){
    global $db;
    $query = "select id from User where username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $results = $statement->fetch();
    $statement->closeCursor();
    return $results["id"];
}

function getUsername($userID) {
    global $db;
    $stmt = $db->prepare("SELECT username FROM User WHERE id=?");
    $stmt->execute([$userID]);
    return $stmt->fetch()["username"];
}
?>