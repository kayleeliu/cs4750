<?php
require_once("connect-db.php");
require_once("user.php");
session_start();
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light ps-3 pe-3">
    <a class="navbar-brand" href="index.php">Foodbase</a>
    
        <div class="collapse navbar-collapse flex-row-reverse">
        
        <div class="flex-row-reverse justify-content-between">
            <?php
                echo $_SESSION["userID"] ?
                "<button class=\"btn btn-outline-primary\" onclick=\"window.location.href='logout.php'\">Logout from ".getUsername($_SESSION["userID"])."</a>" :
                "
                    <button class=\"btn btn-outline-primary\" onclick=\"window.location.href='login_form.php'\" style = \"margin-right:10px;\">Login</a>
                    <button class=\"btn btn-outline-primary\" onclick=\"window.location.href='create_user_form.php'\">Create Account</a> 
                ";
            ?>
        </div>
        <div class="dropdown" style = "padding-right: 10px">
            <button class="btn btn-primary dropdown-toggle" type="button" id="meals" data-bs-toggle="dropdown" aria-expanded="false">
                Meals
            </button>
            <ul class="dropdown-menu" aria-labelledby="meals">
                <li><a class="dropdown-item" href="my-meals.php">My Meals</a></li>
                <li><a class="dropdown-item" href="create_meal_form.php">Create Meal</a></li>
                <li><a class="dropdown-item" href="designed_meals.php">Designed Meals</a></li>
                <li><a class="dropdown-item" href="all-meals.php">All Meals</a></li>
            </ul>
        </div>
        <div class="dropdown" style = "padding-right: 10px">
            <button class="btn btn-primary dropdown-toggle" type="button" id="recipes" data-bs-toggle="dropdown" aria-expanded="false">
                Recipes
            </button>
            <ul class="dropdown-menu" aria-labelledby="meals">
                <li><a class="dropdown-item" href="create_recipe_form.php">Create Recipe</a></li>
                <li><a class="dropdown-item" href="designed_recipes.php">Designed Recipes</a></li>
                <li><a class="dropdown-item" href="all-recipes.php">All Recipes</a></li>
            </ul>
        </div>
        <div class="dropdown" style = "padding-right: 10px">
            <button class="btn btn-primary dropdown-toggle" type="button" id="foods" data-bs-toggle="dropdown" aria-expanded="false">
                Foods
            </button>
            <ul class="dropdown-menu" aria-labelledby="foods">
                <li><a class="dropdown-item" href="foods_page.php">My Foods</a></li>
                <li><a class="dropdown-item" href="shopping_list.php">Shopping List</a></li>
                <li><a class="dropdown-item" href="all-foods.php">All Foods</a></li>
            </ul>
        </div>
    </div>
</nav>  