<?php
include 'db.php';
include 'functions.php';

ob_start();
session_start();

if(isset($_SESSION['redirectAfterLogin'])){
  $redirectLink = $_SESSION['redirectAfterLogin'];
  unset($_SESSION['redirectAfterLogin']);
} else {
  $redirectLink = './';
}

    if (isset($_SESSION['userToken'])) {
        redirectTo('./');
    } else {
        $setLoggedIn = false;
    }


$dbLink = new DB();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Log in</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/login.css">
  <link rel="stylesheet" href="css/popup.css">
  <link rel="stylesheet" href="css/footer.css">
  <script src="https://kit.fontawesome.com/3204ead578.js" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter&family=Open+Sans:wght@300&family=Spectral:wght@200;300&display=swap" rel="stylesheet">
</head>
<body>
  <div class="page-container">
    <div class="content-wrap">
      <nav>
       <img src = "./pics/login.png">
      </nav>
      <nav class="format">
        <form method="post">
        <p>Username</p>
        <input type="text" name="user" placeholder="Enter Username">
        <p>Password</p>
        <input type="password" name="password" placeholder="Enter Password">
        <br><br>
        <input type="submit" name="buttonSubmit">
          <?php
        if(isset($_POST['buttonSubmit'])) {
          $userDataPrep = $_POST['user'] . 'password' . $_POST['password'];
          $sha256Data = hash256($userDataPrep);
          $isRegistered = $dbLink->checkLogin();

          if ($isRegistered) {
            $_SESSION['userToken'] = $sha256Data;
            redirectTo('#loginSuccess');
          } else {
            redirectTo('#wrongCredentials');
          }
        }
         
          $conn = null;
        ?>
        <br>
        <a href="passwordreset.php">Lost Your Password</a>
        <br>
        <a href="register.php">Haven't registerd yet?</a>
      </form>
      </nav>
    </div>
    <div>
      <?php
      successDialog("Login Successfully", "User login was successful", 'loginSuccess', "$redirectLink");
      errorDialog("Crederentials Error", 'Credentials not found in the server', 'wrongCredentials', '#')
      ?>
    </div>
    <?php include 'footer.php' ?>
  </div>
</body>
</html>
  
