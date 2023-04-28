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
  if ($_POST['actionBtn']){
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
<?php
include("navbar.html")
?> 

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
        <input class="btn btn-primary" type="submit" name="actionBtn" title="Login" />
      </div>
    </form>  
  </div>  
</body>
</html>
