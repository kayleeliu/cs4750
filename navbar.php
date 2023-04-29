<?php
require_once("connect-db.php");
require_once("user.php");
session_start();
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light ps-3 pe-3">
    <a class="navbar-brand" href="index.php">Food Tracker</a>
    <a class="nav-item nav-link" href="foods_page.php">Foods</a>
    <a class="nav-item nav-link" href="shopping_list.php">Shopping List</a>
    <a class="nav-item nav-link" href="my-meals.php">My Meals</a>
    <div class="collapse navbar-collapse flex-row-reverse">
        <div class="flex-row-reverse justify-content-between">
            <button class="btn btn-outline-primary" onclick="window.location.href='create_meal_form.php'">Create Meal</a> 
            <button class="btn btn-outline-primary" onclick="window.location.href='designed_meals.php'">Meal Designer</a> 
            <button class="btn btn-outline-primary" onclick="window.location.href='all-meals.php'">All Meals</a> 
            <button class="btn btn-outline-primary" onclick="window.location.href='create_recipe_form.php'">Create Recipe</a> 
            <button class="btn btn-outline-primary" onclick="window.location.href='designed_recipes.php'">Designed Recipes</a> 
            <?php
                echo $_SESSION["userID"] ?
                "<button class=\"btn btn-outline-primary\" onclick=\"window.location.href='logout.php'\">Logout from ".getUsername($_SESSION["userID"])."</a>" :
                "
                    <button class=\"btn btn-outline-primary\" onclick=\"window.location.href='login_form.php'\">Login</a>
                    <button class=\"btn btn-outline-primary\" onclick=\"window.location.href='create_user_form.php'\">Create Account</a> 
                ";
            ?>
        </div>
    </div>
</nav>  