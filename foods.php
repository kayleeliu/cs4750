<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>  
<nav class="navbar navbar-expand-lg navbar-light bg-light ps-3 pe-3">
  <a class="navbar-brand" href="index.html">Food Tracker</a>
  <a class="nav-item nav-link" href="foods.php">Foods</a>
  <div class="collapse navbar-collapse flex-row-reverse">
    <div class="flex-row-reverse justify-content-between">
      <button class="btn btn-outline-primary" onclick="window.location.href='create_user_form.php'">Create Account</a> 
      <button class="btn btn-outline-primary" onclick="window.location.href='login_form.php'">Login</a> 
    </div>
  </div>
</nav>  
</body>
</html>