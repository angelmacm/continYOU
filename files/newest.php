<?php
include 'db.php';
include 'functions.php';

ob_start();
session_start();
$dbLink = new DB();

    if (isset($_SESSION['userToken'])) {
        $setLoggedIn = true;
    } else {
        $setLoggedIn = false;
    }


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newest</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://kit.fontawesome.com/3204ead578.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/newest.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <script src="https://kit.fontawesome.com/3204ead578.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter&family=Open+Sans:wght@300&family=Spectral:wght@200;300&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'header.php' ?>
    <!--Start of Body Here-->
    <div class="page-container">
        <div class="content-wrap">
        <div class = "main-container border">
            <div class ="first-division">
                <p class="first-division-title">Newest</p>
            </div>
            <div class = "newest-container ">
                <div class = "text-container">
                </div>
                <div class = "main-area">
                    <div class="slider">
                <!--
                  <a href="#slide-1">1</a>
                  <a href="#slide-2">2</a>
                  <a href="#slide-3">3</a>
                -->
                  <div class="slides">
                    <div id="slide-1">
                        <?php
                        $newestStories = $dbLink->getNewest();
                        foreach ($newestStories as $newestInfo) {
                            // code...
                        #echo var_dump($storyInfo);
                        $storyAuthor = $newestInfo['author'];
                        $searchResult = $dbLink->findAuthorByID($storyAuthor);
                        $storyAuthor = $searchResult['user'];
                        $storyTitle = $newestInfo['title'];
                        $storyBody = $newestInfo['story'];
                        $storyId = $newestInfo['#'];
                        if($newestInfo['isHead']==0){
                            $searchResult = $dbLink->getHead($newestInfo['#']);
                            $searchResult = $dbLink->getStory($searchResult);
                            $storyTitle = $searchResult['title']; 
                        }
                        echo "<div><a href='substory.php?storyId=$storyId'>";
                        echo "<span class = 'title-block'><div class='story-title'><b> $storyTitle </b></div><div><br>by $storyAuthor</div></span>";
                        echo "<div class = 'body-block'> $storyBody </div></a></div>";

                        }
                        ?>

                  </div>
                </div>
                </div>
            </div>
        </div>
    </div>
        <?php include 'footer.php' ?>
        </div>
</body>
</html>