<?php
include 'db.php';
include 'functions.php';

$dbLink = new DB();
ob_start();
session_start();

    if (isset($_SESSION['userToken'])) {
        $setLoggedIn = true;
    } else {
        redirectTo("/");
    }

$followedStory = $dbLink->getFollowing($_SESSION['userToken']);
$likedStory = $dbLink->getLiked($_SESSION['userToken']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Collection</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://kit.fontawesome.com/3204ead578.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/followedstories.css">
    <link rel="stylesheet" href="css/footer.css">
    <script src="https://kit.fontawesome.com/3204ead578.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter&family=Open+Sans:wght@300&family=Spectral:wght@200;300&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'?>
    <!--Start of Body Here-->
    <div class="page-container">
        <div class="content-wrap">
        <div class = "main-container">
            <div class ="first-division">
                <p class="first-division-title">Followed Stories</p>
            </div>
            <div class = "second-division">
                <div class = "text-container">
                    <div class = "text-label-backg">
                        <p class = "text-label">Followed</p> <!--Comedy Section-->
                    </div>
                    <hr class = "broken-line">
                </div>
                <div class = "main-area  ">
                    <span></span>
                    <div class="slider">
                <!--
                  <a href="#slide-1">1</a>
                  <a href="#slide-2">2</a>
                  <a href="#slide-3">3</a>
                -->
                  <div class="slides">
                    <div id="slide-1">
                        <?php
                        foreach ($followedStory as $followedInfo) {
                            // code...
                        if($followedInfo['prevNode'] != 0){
                            
                        }
                        $storyAuthor = $followedInfo['author'];
                        $searchResult = $dbLink->findAuthorByID($storyAuthor);
                        $storyAuthor = $searchResult['user'];
                        $storyTitle = $followedInfo['title'];
                        $storyBody = $followedInfo['story'];
                        $storyId = $followedInfo['#'];
                        if($followedInfo['isHead']==0){
                            $searchResult = $dbLink->getHead($followedInfo['#']);
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
                    <span></span>
                <?php
                if(count($followedStory)>9){
                    echo "<div> 
                    <a href='allstories.php?category=following'><input class='SeeMorebutton' type='button' name='' value='SeeMore'></a>
                </div>";
                }
                ?>
                </div>
            </div>
            <div class = "remaining-second-division">
                <div class = "text-container  ">
                    <div class = "text-label-backg ">
                        <p class = "text-label ">Liked</p> <!--Drama Section-->
                    </div>
                    <hr class = "broken-line">
                </div>
                <div class = "main-area  ">
                    <span></span>
                    <div class="slider">
                <!--
                  <a href="#slide-1">1</a>
                  <a href="#slide-2">2</a>
                  <a href="#slide-3">3</a>
                -->
                  <div class="slides">
                    <div id="slide-1">
                        <?php
                        foreach ($likedStory as $likedInfo) {
                            // code...
                        #echo var_dump($storyInfo);
                        $storyAuthor = $likedInfo['author'];
                        $searchResult = $dbLink->findAuthorByID($storyAuthor);
                        $storyAuthor = $searchResult['user'];
                        $storyTitle = $likedInfo['title'];
                        $storyBody = $likedInfo['story'];
                        $storyId = $likedInfo['#'];
                        if($likedInfo['isHead']==0){
                            $searchResult = $dbLink->getHead($likedInfo['#']);
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
                    <span></span>
                <?php
                if(count($likedStory)>9){
                    echo "<div> 
                    <a href='allstories.php?category=liked'><input class='SeeMorebutton' type='button' name='' value='SeeMore'></a>
                </div>";
                }
                ?>
                </div>
            </div>
        </div>
    </div>
        <?php include 'footer.php' ?>
    </div>

</body>
</html>