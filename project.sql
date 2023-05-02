		CREATE TABLE User (
	id INT NOT NULL AUTO_INCREMENT,
	username VARCHAR(30) NOT NULL,
	password VARCHAR(255) NOT NULL,
	daily_calorie_count INT,
	meals_per_day INT,
PRIMARY KEY (id)
);

CREATE TABLE Food (
	id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE NOT NULL,
	cooked BOOLEAN,
  	PRIMARY KEY (id) 
);  

CREATE TABLE food_calories_temp (
	name VARCHAR(100) NOT NULL,
	cooked BOOLEAN NOT NULL,
	calories INT,
	ideal_storage_temp INT,
	PRIMARY KEY (name, cooked),
	FOREIGN KEY (name) REFERENCES Food(name)
);

CREATE TABLE food_group (
	name VARCHAR(100) NOT NULL,
	food_group VARCHAR(30), 
	PRIMARY KEY (name),
	FOREIGN KEY (name) REFERENCES Food(name)
);

CREATE TABLE Meal (
	id INT NOT NULL AUTO_INCREMENT, 
num_of_servings INT,
prep_time INT, 
calorie_count INT, 
time_of_day VARCHAR(10),
name VARCHAR(100),
PRIMARY KEY (id)
);

CREATE TABLE Recipe (
	id INT NOT NULL AUTO_INCREMENT,
	prep_time INT,
	foodMade INT NOT NULL,
	userID INT NOT NULL,
	link VARCHAR(200),
	PRIMARY KEY (id),
	FOREIGN KEY (foodMade) REFERENCES Food(id) ON DELETE CASCADE,
	FOREIGN KEY (userID) REFERENCES User(id) ON DELETE CASCADE
);

CREATE TABLE user_has_food (
	userID INT NOT NULL,
	foodID INT NOT NULL,
	location VARCHAR(50),
	buy_date DATE,
	exp_date DATE,
quantity int NOT NULL,
 	PRIMARY KEY (userID, foodID, location),
	FOREIGN KEY (userID) REFERENCES User(id),
FOREIGN KEY (foodID) REFERENCES Food(id)
);

CREATE TABLE user_has_meal (
	userID INT NOT NULL,
	mealID INT NOT NULL,
	eaten BOOLEAN NOT NULL,
 	PRIMARY KEY (userID, mealID),
	FOREIGN KEY (userID) REFERENCES User(id) ON DELETE CASCADE,
	FOREIGN KEY (mealID) REFERENCES Meal(id) ON DELETE CASCADE
);

CREATE TABLE is_part_of (
	foodID INT NOT NULL,
	mealID INT NOT NULL,
	quantity INT NOT NULL,
PRIMARY KEY (foodID, mealID),
	FOREIGN KEY (foodID) REFERENCES Food(id) ON DELETE CASCADE,
	FOREIGN KEY (mealID) REFERENCES Meal(id) ON DELETE CASCADE
);

CREATE TABLE wants_to_buy (
	userID INT NOT NULL,
	foodID INT NOT NULL,
	quantity INT NOT NULL,
	PRIMARY KEY (userID, foodID),
	FOREIGN KEY (userID) REFERENCES User(id),
	FOREIGN KEY (foodID) REFERENCES Food(id)
);

CREATE TABLE is_made_from (
	recipeID INT NOT NULL,
	foodID INT NOT NULL,
	quantity INT NOT NULL,
	units VARCHAR(5) NOT NULL,
	PRIMARY KEY (recipeID, foodID),
	FOREIGN KEY (recipeID) REFERENCES Recipe(id) ON DELETE CASCADE,
	FOREIGN KEY (foodID) REFERENCES Food(id) ON DELETE CASCADE
);

CREATE TABLE designed (
	userID INT NOT NULL,
	mealID INT NOT NULL,
PRIMARY KEY (userID, mealID),
	FOREIGN KEY (userID) REFERENCES User(id) ON DELETE CASCADE,
	FOREIGN KEY (mealID) REFERENCES Meal(id) ON DELETE CASCADE
);

CREATE TABLE `user_has_food_backup` (
  `userID` int(11) NOT NULL,
  `foodID` int(11) NOT NULL,
  `location` varchar(50) NOT NULL,
  `buy_date` date DEFAULT NULL,
  `exp_date` date DEFAULT NULL,
  `quantity` int(11) NOT NULL
);

ALTER TABLE `user_has_food_backup`
  ADD CONSTRAINT `user_has_food_backup_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `User` (`id`),
  ADD CONSTRAINT `user_has_food_backup_ibfk_2` FOREIGN KEY (`foodID`) REFERENCES `Food` (`id`);

DELIMITER $$
CREATE TRIGGER `backupUserHasFood` BEFORE DELETE ON `user_has_food` FOR EACH ROW BEGIN
INSERT INTO user_has_food_backup
VALUES (OLD.userID, OLD.foodID, 
        OLD.location, OLD.buy_date, OLD.exp_date, OLD.quantity);
END
$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ktl4rt`@`%` PROCEDURE `makeRecipe`(
    IN prep_time INT, 
    IN foodName VARCHAR(100), 
    IN cooked BOOLEAN, 
    IN userID INT, 
    IN link VARCHAR(200)
)
BEGIN
    INSERT INTO Food (name, cooked) VALUES (foodName, cooked);
    SET @foodID := LAST_INSERT_ID();
    INSERT INTO Recipe(prep_time, foodMade, userID, link) VALUES (prep_time,@foodID, userID, link);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ktl4rt`@`%` PROCEDURE `addFoodToDBAndShoppingList`(IN `uID` INT, IN `foodName` VARCHAR(200), IN `cooked_status` BOOLEAN, IN `quan` INT)
BEGIN
INSERT INTO Food (name, cooked) VALUES (foodName, cooked_status);
	SET @fID := LAST_INSERT_ID();
INSERT INTO wants_to_buy (userID, foodID, quantity) VALUES (uID,@fID,quan);
END$$
DELIMITER ;

INSERT INTO `User` (`id`, `username`, `password`, `daily_calorie_count`, `meals_per_day`) VALUES
(1, 'kaylee', '$5$databaseencrypti$iEJgCh2fUhHmJsi8/4ytQaaY1hh0WGgdtt6sDrQqWDA', 500, 4),
(2, 'candace', '$5$databaseencrypti$34nppJEy9DsVoEbYaN4eqKENiHvpxFHn1bfw0U0kz71', 1322, 3),
(3, 'justin', '$5$databaseencrypti$IP5nAK2q8lE.VjoEPGdV85xCe8sYzTZ3udsz5NMv.I6', 4244, 33),
(4, 'alex', '$5$databaseencrypti$PpiIsS9eSdLrqywDPf.8VjHBgsZ0kZJaGwihmc24ps8', 242, 2),
(5, 'bob', '$5$databaseencrypti$x.mJpJYoiHEYcnPWv8Rtp0LVEmrO4Q66ptwuFjVlo39', 5000, 4),
(6, 'upsorn', '$5$databaseencrypti$csm4ljPbnIcdmM00pi0jkeHb.AXj8NnpM.ysjtDyT//', 9494, 2),
(7, 'databaseuser', '$5$databaseencrypti$IzUtxibJbjI8MhTICDYWcW3BYt9/bLYvXQICHqzvlb.', 1203, 5),
(8, 'user', '$5$databaseencrypti$34nppJEy9DsVoEbYaN4eqKENiHvpxFHn1bfw0U0kz71', 1000, 3),
(9, 'alice', '$5$databaseencrypti$qbIEKju5mgO0bZNrikNyXq8cWhvOrSerFXoTYZ9jmgC', 4444, 4),
(10, 'student', '$5$databaseencrypti$lyz9//tWGUnhpLCtC6CbwPzHmJ3rPyQPVNTpQwlxd15', 9000, 3);

-- INSERT INTO User(username, password, daily_calorie_count, meals_per_day) VALUES ("kaylee", 1234, 500, 4);
-- INSERT INTO User(username, password, daily_calorie_count, meals_per_day) VALUES ("candace", "password", 1322, 3);
-- INSERT INTO User(username, password, daily_calorie_count, meals_per_day) VALUES ("justin", "pass", 4244, 33);
-- INSERT INTO User(username, password, daily_calorie_count, meals_per_day) VALUES ("alex", "hi", 242, 2);
-- INSERT INTO User(username, password, daily_calorie_count, meals_per_day) VALUES ("bob", 12345, 5000, 4);
-- INSERT INTO User(username, password, daily_calorie_count, meals_per_day) VALUES ("upsorn", "passs", 9494, 2);
-- INSERT INTO User(username, password, daily_calorie_count, meals_per_day) VALUES ("databaseuser", "databasepass", 1203, 5);
-- INSERT INTO User(username, password, daily_calorie_count, meals_per_day) VALUES ("user", "password", 1000, 3);
-- INSERT INTO User(username, password, daily_calorie_count, meals_per_day) VALUES ("alice", "alicepassword", 4444, 4);
-- INSERT INTO User(username, password, daily_calorie_count, meals_per_day) VALUES ("student", 54321, 9000, 3);

INSERT INTO Food (name, cooked) VALUES ("Scrambled eggs", TRUE);
INSERT INTO Food (name, cooked) VALUES ("french toast", TRUE);
INSERT INTO Food (name, cooked) VALUES ("waffles", NULL);
INSERT INTO Food (name, cooked) VALUES ("syrup", NULL);
INSERT INTO Food (name, cooked) VALUES ("apples", NULL);
INSERT INTO Food (name, cooked) VALUES ("yogurt", NULL);
INSERT INTO Food (name, cooked) VALUES ("shrimp scampi", TRUE);
INSERT INTO Food (name, cooked) VALUES ("linguini", TRUE);
INSERT INTO Food (name, cooked) VALUES ("butter", NULL);
INSERT INTO Food (name, cooked) VALUES ("shrimp", FALSE);
INSERT INTO Food (name, cooked) VALUES ("breadsticks", TRUE);
INSERT INTO Food (name, cooked) VALUES ("italian wedding soup", TRUE);
INSERT INTO Food (name, cooked) VALUES ("orange chicken", TRUE);
INSERT INTO Food (name, cooked) VALUES ("raw chicken", FALSE);
INSERT INTO Food (name, cooked) VALUES ("vinegar", NULL);
INSERT INTO Food (name, cooked) VALUES ("sesame", NULL);
INSERT INTO Food (name, cooked) VALUES ("soy sauce", NULL);
INSERT INTO Food (name, cooked) VALUES ("garlic", FALSE);
INSERT INTO Food (name, cooked) VALUES ("white rice", TRUE);
INSERT INTO Food (name, cooked) VALUES ("beef and broccoli", TRUE);
INSERT INTO Food (name, cooked) VALUES ("raw skirt steak", FALSE);
INSERT INTO Food (name, cooked) VALUES ("broccoli", FALSE);
INSERT INTO Food (name, cooked) VALUES ("honey walnut shrimp", TRUE);
INSERT INTO Food (name, cooked) VALUES ("honey", NULL);
INSERT INTO Food (name, cooked) VALUES ("walnut", FALSE);
INSERT INTO Food (name, cooked) VALUES ("parfait", NULL);

INSERT INTO food_calories_temp VALUES ("Scrambled eggs", TRUE, 100, 70);
INSERT INTO food_calories_temp VALUES ("french toast", TRUE, 200, 40);
INSERT INTO food_calories_temp VALUES ("waffles", TRUE, 40, 40);
INSERT INTO food_calories_temp VALUES ("syrup", FALSE, 10, 70);
INSERT INTO food_calories_temp VALUES ("apples", FALSE, 20, 40);
INSERT INTO food_calories_temp VALUES ("yogurt", FALSE, 30, 50);
INSERT INTO food_calories_temp VALUES ("shrimp scampi", TRUE, 300, 50);
INSERT INTO food_calories_temp VALUES ("linguini", TRUE, 400, 30);
INSERT INTO food_calories_temp VALUES ("butter", FALSE, 100, 40);
INSERT INTO food_calories_temp VALUES ("shrimp", FALSE, 500, 20);
INSERT INTO food_calories_temp VALUES ("breadsticks", TRUE, 40, 50);
INSERT INTO food_calories_temp VALUES ("italian wedding soup", TRUE, 100, 40);
INSERT INTO food_calories_temp VALUES ("orange chicken", TRUE, 200, 50);
INSERT INTO food_calories_temp VALUES ("raw chicken", FALSE, 100, 30);
INSERT INTO food_calories_temp VALUES ("vinegar", FALSE, 40, 60);
INSERT INTO food_calories_temp VALUES ("sesame", FALSE, 10, 70);

INSERT INTO food_group VALUES ("Scrambled eggs", "protein");
INSERT INTO food_group VALUES ("french toast", "grains");
INSERT INTO food_group VALUES ("waffles", "grains");
INSERT INTO food_group VALUES ("syrup", NULL);
INSERT INTO food_group VALUES ("apples", "fruits");
INSERT INTO food_group VALUES ("yogurt", "dairy");
INSERT INTO food_group VALUES ("shrimp scampi", "protein");
INSERT INTO food_group VALUES ("linguini", "grains");
INSERT INTO food_group VALUES ("butter", "dairy");
INSERT INTO food_group VALUES ("shrimp", "protein");
INSERT INTO food_group VALUES ("breadsticks", "grains");
INSERT INTO food_group VALUES ("italian wedding soup", NULL);
INSERT INTO food_group VALUES ("orange chicken", "protein");
INSERT INTO food_group VALUES ("raw chicken", "protein");
INSERT INTO food_group VALUES ("vinegar", NULL);
INSERT INTO food_group VALUES ("sesame", NULL);
INSERT INTO food_group VALUES ("soy sauce", NULL);
INSERT INTO food_group VALUES ("garlic", "vegetables");
INSERT INTO food_group VALUES ("white rice", "grains");
INSERT INTO food_group VALUES ("beef and broccoli", NULL);
INSERT INTO food_group VALUES ("raw skirt steak", "protein");
INSERT INTO food_group VALUES ("broccoli", "vegetables");
INSERT INTO food_group VALUES ("honey walnut shrimp", "protein");
INSERT INTO food_group VALUES ("honey", NULL);

INSERT INTO Meal(num_of_servings, prep_time, calorie_count, time_of_day, name) VALUES(1, 15, 500, "Breakfast", "Continental breakfast");
INSERT INTO Meal(num_of_servings, prep_time, calorie_count, time_of_day, name) VALUES(2, 60, 1000, "Dinner", "Tour of Italy");
INSERT INTO Meal(num_of_servings, prep_time, calorie_count, time_of_day, name) VALUES(4, 30, 750, "Dinner", "Homemade Panda Express");
INSERT INTO Meal(num_of_servings, prep_time, calorie_count, time_of_day, name) VALUES(4, 20, 500, "Lunch", "Quick and easy meal prep");
INSERT INTO Meal(num_of_servings, prep_time, calorie_count, time_of_day, name) VALUES(1, 10, 750, "Lunch", "Just carbs");

INSERT INTO Recipe(prep_time, foodMade, userID, link) VALUES (20, 7, 5, "https://cooking.nytimes.com/recipes/9101-classic-shrimp-scampi");
INSERT INTO Recipe(prep_time, foodMade, userID, link) VALUES (15, 13, 6, "https://www.modernhoney.com/chinese-orange-chicken/");
INSERT INTO Recipe(prep_time, foodMade, userID, link) VALUES (15, 20, 1, "https://natashaskitchen.com/beef-and-broccoli/");
INSERT INTO Recipe(prep_time, foodMade, userID, link) VALUES (25, 23, 3, "https://www.allrecipes.com/recipe/93234/honey-walnut-shrimp/");
INSERT INTO Recipe(prep_time, foodMade, userID, link) VALUES (3, 26, 2, "https://www.jaroflemons.com/apple-crumble-parfait/");

INSERT INTO user_has_food VALUES (1, 1, 'fridge', NULL, NULL, 3);
INSERT INTO user_has_food VALUES (1, 2, 'fridge', NULL, NULL, 8);
INSERT INTO user_has_food VALUES (1, 18, 'fridge', '2023-04-01', '2024-05-01', 4);
INSERT INTO user_has_food VALUES (2, 21, 'freezer', '2023-02-18', '2023-02-22', 2);
INSERT INTO user_has_food VALUES (3, 24, 'cabinet', '2023-08-10', NULL, 1);

INSERT INTO user_has_meal VALUES (1, 1, True);
INSERT INTO user_has_meal VALUES (2, 3, False);
INSERT INTO user_has_meal VALUES (1, 2, True);
INSERT INTO user_has_meal VALUES (8, 5, True);
INSERT INTO user_has_meal VALUES (3, 4, False);

INSERT INTO is_part_of VALUES (1, 1, 3);
INSERT INTO is_part_of VALUES (2, 1, 2);
INSERT INTO is_part_of VALUES (3, 1, 4);
INSERT INTO is_part_of VALUES (5, 1, 2);
INSERT INTO is_part_of VALUES (6, 1, 1);
INSERT INTO is_part_of VALUES (7, 2, 5);
INSERT INTO is_part_of VALUES (11, 2, 4);
INSERT INTO is_part_of VALUES (12, 2, 6);
INSERT INTO is_part_of VALUES (13, 3, 4);
INSERT INTO is_part_of VALUES (19, 3, 2);
INSERT INTO is_part_of VALUES (20, 3, 6);
INSERT INTO is_part_of VALUES (23, 3, 7);

INSERT INTO wants_to_buy VALUES (1, 18, 9);
INSERT INTO wants_to_buy VALUES (2, 5, 10);
INSERT INTO wants_to_buy VALUES (8, 7, 1);
INSERT INTO wants_to_buy VALUES (4, 18, 1);
INSERT INTO wants_to_buy VALUES (4, 1, 2);

INSERT INTO is_made_from VALUES (1, 8, 1, "lb");
INSERT INTO is_made_from VALUES (1, 9, 2, "Tbsp");
INSERT INTO is_made_from VALUES (1, 10, 1, "lb");
INSERT INTO is_made_from VALUES (2, 14, 1, "lb");
INSERT INTO is_made_from VALUES (2, 15, 1, "Tbsp");
INSERT INTO is_made_from VALUES (2, 16, 2, "Tbsp");
INSERT INTO is_made_from VALUES (2, 17, 1, "Tbsp");
INSERT INTO is_made_from VALUES (2, 18, 3, "clove");
INSERT INTO is_made_from VALUES (3, 21, 1, "lb");
INSERT INTO is_made_from VALUES (3, 22, 1, "lb");
INSERT INTO is_made_from VALUES (3, 16, 3, "Tbsp");
INSERT INTO is_made_from VALUES (4, 24, 2, "Tbsp");
INSERT INTO is_made_from VALUES (4, 25, 4, "oz.");
INSERT INTO is_made_from VALUES (4, 10, 1, "lb");
INSERT INTO is_made_from VALUES (5, 5, 1, "apple");
INSERT INTO is_made_from VALUES (5, 6, 1, "cup");

INSERT INTO designed VALUES (1, 1);
INSERT INTO designed VALUES (2, 1);
INSERT INTO designed VALUES (3, 2);
INSERT INTO designed VALUES (3, 3);
INSERT INTO designed VALUES (4, 3);
INSERT INTO designed VALUES (9, 2);
INSERT INTO designed VALUES (6, 1);
INSERT INTO designed VALUES (4, 2);
INSERT INTO designed VALUES (10, 2);
INSERT INTO designed VALUES (1, 3);