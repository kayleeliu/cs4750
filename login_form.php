<?php
require("connect-db.php");
require("user.php");

// get previous session
session_start();

// if already logged in, redirect to home page
if ($_SESSION["userID"]){
  header("Location: index.php");
}


// $_SERVER is a standard PHP object
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  if ($_POST['actionBtn']){
    $results = login($_POST['username'], $_POST['password']);
    if($results[0] != 0){
      $userID = getUserID($_POST['username']);
      # save userID in session
      $_SESSION["userID"] = $userID;
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
<?php include("common-header.php"); ?>
<link rel="stylesheet" href="styles.css">
</head>
<body>  
<?php
include("navbar.php")
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
