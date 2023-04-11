<?php
require("connect-db.php");
// include("connect-db.php) 
// use require if it matters if the db exists or not
require("user.php");


// $_SERVER is a standard PHP object
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Create Account")){
    $exists = checkUsernameExists($_POST['username']);
    if($exists[0] == 0){
      addUser($_POST['username'], $_POST['password'], $_POST['daily_calorie_count'], $_POST['meals_per_day']);
      header("Location: login_form.php");
    }
    else{
      echo '<script>alert("Username already being used. Please use another one.")</script>';
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
    <h1>Create Account</h1>
    <form name="mainForm" action="create_user_form.php" method="post">
      <div class="row mb-3 mx-3"> Username:
        <input type="text" class="form-control" name="username" required />     
      </div>  
      <div class="row mb-3 mx-3">Password: 
        <input type="text" class="form-control" name="password" required />    
      </div>  
      <div class="row mb-3 mx-3">Daily Caloric Intake: 
        <input type="number" class="form-control" name="daily_calorie_count" />
      </div>  
      <div class="row mb-3 mx-3">
        Number Meals Per Day: 
        <input type="number" class="form-control" name="meals_per_day" />
      </div>  
      <div class="row mb-3 mx-3">
        <input class="btn btn-primary" type="submit" name="actionBtn" value="Create Account" title="Create Account" />
      </div>
    </form>  
  </div>  
</body>
</html>
