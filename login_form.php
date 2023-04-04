<?php
require("connect-db.php");
// include("connect-db.php) 
// use require if it matters if the db exists or not

require("friend-db.php");

$friends = getAllFriends();
$friend_info_to_update = null;

// $_SERVER is a standard PHP object
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Add friend"))
  {
    addFriend($_POST['friendname'], $_POST['major'], $_POST['year']);
    $friends = getAllFriends();
  }
  else if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Delete")){
    deleteFriend($_POST['friend_to_delete']);
    $friends = getAllFriends();
  }
  else if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Update")){
    $friend_info_to_update = getFriendByName($_POST['friend_to_update']);
  }
  else if(!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Confirm update")){
    updateFriend($_POST['friendname'], $_POST['major'], $_POST['year']);
    $friends = getAllFriends();
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
        <div class="row mb-3 mx-3">
          Username:
          <input type="text" class="form-control" name="username" required >     
        </div>  
        <div class="row mb-3 mx-3">
          Password: 
          <input type="text" class="form-control" name="password" required >    
        </div>  
        <div class="row mb-3 mx-3">
          Daily Caloric Intake: 
          <input type="number" class="form-control" name="year" required>
        </div>  
        <div class="row mb-3 mx-3">
          Number Meals Per Day: 
          <input type="number" class="form-control" name="year" required>
        </div>  
        <div class="row mb-3 mx-3">
          <input class="btn btn-primary" type="submit" name="actionBtn" value="Add friend" title="click to insert friend" />
        </div>
        <div class="row mb-3 mx-3">
          <input class="btn btn-secondary" type="submit" name="actionBtn" value="Confirm update" title="click to confirm updating friend" />
        </div>  
    </form>  
  </div>
  <div class="row justify-content-center">  
    <table class="w3-table w3-bordered w3-card-4 center" style="width:70%">
      <thead>
      <tr style="background-color:#B0B0B0">
        <th width="25%">Name        
        <th width="25%">Major        
        <th width="25%">Year 
        <th width="12.5%"> Update?
        <th width="12.5%"> Delete?
      </tr>
      </thead>
    <?php foreach ($friends as $item): ?>
      <tr>
        <td><?php echo $item['name']; ?></td>
        <td><?php echo $item['major']; ?></td>        
        <td><?php echo $item['year']; ?></td>  
        <td> 
          <form action="simpleform.php" method="post">
            <input type="submit" name="actionBtn" value="Update" class="btn btn-dark" /> 
            <input type="hidden" name="friend_to_update", value="<?php echo $item['name']; ?>" />
          </form> 
        </td> 
        <td>
          <form action="simpleform.php" method="post">
            <input type="submit" name="actionBtn" value="Delete" class="btn btn-danger" /> 
            <input type="hidden" name="friend_to_delete", value="<?php echo $item['name']; ?>" />
          </form> 
        </td>              
      </tr>
    <?php endforeach; ?>
    </table>
  </div>      
</body>
</html>
