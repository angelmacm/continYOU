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
    <title>Register</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/popup.css">
    <link rel="stylesheet" href="css/register.css?v=<?php echo time(); ?>">
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
                            <label><b>Birthdate:</b></label>
                            <br>
                            <?php
                            echo "<input type='date' name='birthdate' max='$legalAgeDate' required>";
                            ?>
                        </div>
                        <div class='registerForm'>
                            <label><b>First Name:</b></label>
                            <br>
                            <input type="text" name="first_name" placeholder="Enter First Name" required>
                        </div>
                        <div class='registerForm'>
                            <label><b>Last Name:</b></label>
                            <br>
                            <input type="text" name="last_name" placeholder="Enter Last Name" required>
                        </div>
                        <div class='registerForm'>
                            <label><b>Mobile Number:</b></label>
                            <br>
                            <input type="tel" name="phone" placeholder="1236789012" required>
                        </div>
                        <div class='registerForm'>
                            <label><b>Password:</b></label>
                            <br>
                            <input type="password" name="passwordTemp" placeholder="Enter Password" required>
                        </div>
                        <div class='registerForm'>
                            <label><b>Confirm Password:</b></label>
                            <br>
                            <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
                        </div>
                    </div>
                    <center>
                        <br>
                        <input type="submit" name="buttonSubmit" value="Register" id="registerButton">
                        <br>
                        <div>
                            <a href="login.php">Already have an account?</a>
                        </div>
                    </center>
                </form> 
            </div>

            <?php
                if(isset($_POST['buttonSubmit'])){
                    if($_POST['passwordTemp'] == $_POST['confirmPassword']){
                        
                        $first_nameArg = $_POST['first_name'];
                        $last_nameArg = $_POST['last_name'];
                        $userArg = $_POST['user'];
                        $passwordArg = $_POST['confirmPassword'];
                        $emailArg = $_POST['email'];
                        $birthdateArg = $_POST['birthdate'];
                        $phoneArg = $_POST['phone'];
                        $continyouationArg = '{}';
                        $storiesArg = '{}';
                        $searchResult = $dbLink->findUserBasedOnCredentials($userArg, $emailArg);
                        echo $searchResult;
                        if(count($searchResult)!= 0){
                            redirectTo('#userExists');
                        } else {
                            $sha256DataArg = hash256($userArg . 'password' . $passwordArg);
                            $dbLink->register($first_nameArg, $last_nameArg, $userArg, $passwordArg, $birthdateArg, $phoneArg, $continyouationArg, $storiesArg, $sha256DataArg, $emailArg);
                            redirectTo('#userCreated');
                        }

                    }

                }

            ?>



            </center>
        <div>
            <?php

            successDialog('User Created Successfully','User Registration was successful, you may now login','userCreated','login.php');
            errorDialog('User already exists','Username and email combination already exists, please try different information','userExists','#');
            ?>
        </div>
        </div>
        <?php include 'footer.php' ?>
    </div>
    
</body>
</html>