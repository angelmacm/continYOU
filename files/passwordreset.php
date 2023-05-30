<?php
include 'db.php';
include 'functions.php';

ob_start();
session_start();

        if (isset($_SESSION['userToken'])) {
                redirectTo('index.php');
        } else {
                $setLoggedIn = false;
        }
$dateToday = date_create();
$date = date_sub($dateToday,date_interval_create_from_date_string("18 years"));
$legalAgeDate = date_format($date,"Y-m-d");

$dbLink = new DB();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Password Reset</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/popup.css">
    <link rel="stylesheet" href="css/passwordreset.css">
    <script src="https://kit.fontawesome.com/3204ead578.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter&family=Open+Sans:wght@300&family=Spectral:wght@200;300&display=swap" rel="stylesheet">
</head>
<body>
    <div class="page-container">
        <div class="content-wrap">
        <center>
            <img src = "pics/login.png"class="logoPic">
        <br><br>
        <div class="formContainer">
            <form method="post">
                <div class="flex-container">
                    <div class='registerForm'>
                        <label><b>Username:</b></label>
                        <br>
                        <input type="text" name="user" placeholder="Enter Username" required>
                    </div>
                    <div class='registerForm'>
                        <label><b>Email Address:</b></label>
                        <br>
                        <input type="text" name="email" placeholder="Enter Email Address" required>
                    </div>

                    <div class='registerForm'>
                        <label><b>New Password:</b></label>
                        <br>
                        <input type="password" name="passwordTemp" placeholder="Enter Password" required>
                    </div>
                    <div class='registerForm'>
                        <label><b>Repeat Password:</b></label>
                        <br>
                        <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
                    </div>
                </div>
                <center>
                    <br>
                    <input type="submit" name="buttonSubmit" value="Reset Password" id="registerButton">
                    <br>
                    <div>
                        <a href="login.php">Have an account?</a>
                    </div>
                </center>
            </form> 
        </div>

        <?php
            if(isset($_POST['buttonSubmit'])){
                if($_POST['passwordTemp'] == $_POST['confirmPassword']){
                    $userArg = $_POST['user'];
                    $passwordArg = $_POST['confirmPassword'];
                    $emailArg = $_POST['email'];
                    $searchResult = $dbLink->findUserBasedOnCredentials($userArg, $emailArg);
                    if(count($searchResult)==0){
                        redirectTo('#incorrectCred');
                    } else {
                        $userId = $searchResult[0]['id'];
                        $sha256DataArg = hash256($userArg . 'password' . $passwordArg);
                        $dbLink->resetPassword($userId, $sha256DataArg, $passwordArg);
                        redirectTo('#resetSuccess');
                    }

                }

            }

        ?>



        </center>
    </div>
    <div>
        <?php
            successDialog('Success!', 'Password Reset Success! You can now try to login', 'resetSuccess', 'login.php');
            errorDialog('Warning!', 'Email and Username does not match in our system', 'incorrectCred', '#')
        ?>
    </div>
    <?php
        include 'footer.php';
    ?>
</body>
</html>