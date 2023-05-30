<?php
include 'db.php';
include 'functions.php';

ob_start();
session_start();
$dbLink = new DB();

if (isset($_SESSION['userToken'])) {
    $authorDetails = $dbLink->findAuthorDetails($_SESSION['userToken']);
    $_SESSION['username'] = $authorDetails['user'];
    $_SESSION['emailAddress'] = $authorDetails['email'];
    $date = new DateTime($authorDetails['birthdate']);
    $_SESSION['birthDay'] = $date->format('F d Y');;
    $tempFullName = array($authorDetails['first_name'], $authorDetails['last_name']);

    $fullName = implode(' ', $tempFullName);

    $_SESSION['fullName'] = $fullName;
} else {
    redirectTo('login.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="css/profile.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/header.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="css/footer.css">
    <script src="https://kit.fontawesome.com/3204ead578.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter&family=Open+Sans:wght@300&family=Spectral:wght@200;300&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'?>
    <!--Body Here-->
    <div class="page-container">
        <div class="content-wrap">
    <div class = "body-container border">
        <div class = "first-row">
            <img class="icon" src="https://lh3.googleusercontent.com/f1IIoCx9_2PuErvmKQuOGwh5zM--0ChPQD88ztHTfnKx3CKlCgU0YYTjOHOzeCrqDC6jgaBWm6N3YKbhafJBx8maM-GrmwRoUbakkvpl0_r0CEjdbRfdvIz4GSLPrVqSeemKSRgXsQ=s250-p-k" alt="Icon Image">
        </div>
        <div class = "second-row border">
            <div class="title-container border">
                <p class ="title">My Profile</p>
            </div>
            <div class = "username-container">
                <p class = "username form-label">Username:</p>
            </div>
            <div class="userdata-text-container">
                <p><?php echo $_SESSION['username']; ?></p>
            </div>
            <div class = "address-container">
                <p class = "address form-label">Email Address:</p>
            </div>
            <div class="userdata-text-container">
                <p><?php echo $_SESSION['emailAddress']; ?></p>
            </div>
        </div>
        <div class ="third-row">
            <div class = "fullname-container ">
                <p class = "input-label form-label">Fullname:</p>
            </div>
            <div class="fullname-input userdata-text-container">
                <p><?php echo $_SESSION['fullName']; ?></p>
            </div>
            <div class = "birthdate-container">
                <p class = "input-label form-label">Birthdate:</p>
            </div>
            <div class="birthdate-input userdata-text-container">
                <p><?php echo $_SESSION['birthDay']; ?></p>
            </div>
            <div class="logout-bottom-container">
                <form action="logout.php">
                    <input type="submit" name="logoutButton" value="Log Out" class="logout-button">
                <?php 
                if(isset($_SESSION['logoutButton'])){
                    logoutUser();
                    redirectTo('/');
                }
                ?>
                </form>
            </div>
        </div>
    </div>
</div>
        <?php include 'footer.php' ?>
    </div>
</body>
</html>