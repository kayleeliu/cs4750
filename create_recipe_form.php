<?php
require("connect-db.php");
require("recipes.php");
session_start();

// $_SERVER is a standard PHP object
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Create Recipe")){
    // check if user is logged in
    if(empty($_SESSION["userID"])) {
        echo '<script>alert("You need to be logged in!")</script>';
    } else {
        // echo $_POST['name'];
        $foodID = getFoodID($_POST['name']);
        echo $foodID;
        echo 'x';
        echo $foodID[0];
        // createRecipe($_POST['prep_time'], $foodID[0], $_POST['link']);
       
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
<?php include("navbar.html"); ?> 
  <div class="container"> 
    <h1>Create Recipe</h1>
    <form name="mainForm" action="create_recipe_form.php" method="post">
      <div class="row mb-3 mx-3"> Food Made:
        <input type="text" class="form-control" name="name" placeholder="ex. shrimp scampi" required />     
      </div>  
      <div class="row mb-3 mx-3">Prep Time (min): 
        <input type="number" class="form-control" name="prep_time"/>    
      </div>  
      <div class="row mb-3 mx-3">Recipe Link: 
        <input type="text" class="form-control" name="link"/>
      </div>  
      <div class="row mb-3 mx-3">
        <input class="btn btn-primary" type="submit" name="actionBtn" value="Create Recipe" title="Create Recipe" />
      </div>
    </form>  
  </div>  
</body>
</html>