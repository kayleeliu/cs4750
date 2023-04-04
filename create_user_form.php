<?php
require("connect-db.php");
// include("connect-db.php) 
// use require if it matters if the db exists or not

require("user.php");

// $_SERVER is a standard PHP object
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Create Account")){
    addUser($_POST['username'], $_POST['password'], $_POST['daily_calorie_count'], $_POST['meals_per_day']);
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
  <div class="container"> 
    <h1>Create Account</h1>
    <form name="mainForm" action="simpleform.php" method="post">
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
