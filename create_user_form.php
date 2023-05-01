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
<?php include("common-header.php"); ?>
<link rel="stylesheet" href="styles.css">
</head>
<body>  
<?php include("navbar.php"); ?>
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
