<!DOCTYPE html>
<html><head>
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="author" content="your name">
    <meta name="description" content="include some description about your page">  
      
    <title>Foodbase</title>
    <?php include("common-header.php"); ?>
    <link rel="icon" type="image/png" href="http://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png">
    <link rel="stylesheet" href="styles.css">
    
         
  </head>
  
  <body>
  <?php 
    include("navbar.php");
  ?>
  <div class="jumbotron feature" style = "margin-bottom: 10px;">
      <div class="container">
          <h1>Foodbase</h1>
          <h2>Manage all of the foods you have at home, create recipes, and plan meals!</h2>
      </div>
  </div>
  <div class="container"> 
    <div class="row">
      <div class="col border border-primary index_box " style="margin-right: 2vw;">
        <div class="container">
          <h2> My Foods </h2>
          <a type="button" class="btn btn-success btn-lg go_btn" href="foods_page.php"> Go </a>
        </div>
      </div>
      <div class="col border border-primary index_box">
        <div class="container">
          <h2> My Recipes </h2>
          <a type="button"class="btn btn-success btn-lg go_btn" href="designed_recipes.php"> Go </a>
        </div>
      </div>
      <div class="col border border-primary index_box" style="margin-left: 2vw;">
        <div class="container">
          <h2> My Meals </h2>
          <a type="button"class="btn btn-success btn-lg go_btn" href="my-meals.php"> Go </a>
        </div>
      </div>
    </div>
  </div>
  </body>
  </body>
  </html>