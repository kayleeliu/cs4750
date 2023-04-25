<?php
require("connect-db.php");
require("user.php");

// get previous session
session_start();
// remove all session variables
session_unset();
// destroy the session
session_destroy();

// start new session
session_start();


// $_SERVER is a standard PHP object
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Login")){
    $results = login($_POST['username'], $_POST['password']);
    if($results[0] != 0){
      $userID = getUserID($_POST['username']);
      # save userID in session
      $_SESSION["userID"] = $userID[0];
      header("Location: foods_page.php");
    }
    else {
      echo '<script>alert("Invalid login. Please try again.")</script>';
    }
  }
}
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
  <a class="nav-item nav-link" href="foods_page.php">Foods</a>
  <div class="collapse navbar-collapse flex-row-reverse">
    <div class="flex-row-reverse justify-content-between">
      <button class="btn btn-outline-primary" onclick="window.location.href='create_user_form.php'">Create Account</a> 
      <button class="btn btn-outline-primary" onclick="window.location.href='login_form.php'">Login</a> 
    </div>
  </div>
</nav>  

  <div class="container"> 
    <h1>Log In</h1>
    <form name="mainForm" action="login_form.php" method="post">
      <div class="row mb-3 mx-3"> Username:
        <input type="text" class="form-control" name="username" required />     
      </div>  
      <div class="row mb-3 mx-3">Password: 
        <input type="password" class="form-control" name="password" required />    
      </div>  
      <div class="row mb-3 mx-3">
        <input class="btn btn-primary" type="submit" name="actionBtn" value="Login" title="Login" />
      </div>
    </form>  
  </div>  
</body>
</html>
