<?php
include 'db.php';
include 'functions.php';

$dbLink = new DB();
ob_start();
session_start();

    if (isset($_SESSION['userToken'])) {
        $setLoggedIn = true;
    } else {
        $setLoggedIn = false;
    }

$trendingStory = $dbLink->getTrending();
$forYouStory = $dbLink->forYouAlg();
$newestStory = $dbLink->getNewest();
$fishedStory = $dbLink->fishStory();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Continyou</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <script src="https://kit.fontawesome.com/3204ead578.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter&family=Open+Sans:wght@300&family=Spectral:wght@200;300&display=swap" rel="stylesheet">

</head>
<body>
    <?php include 'header.php'?>
    <!--Start of Body-->
    <div class="page-container">
        <div class="content-wrap">
    <div class = "body-container">
        <?php 
         echo " <a href='substory.php?storyId=$fishedStory'  class = 'hook-container'>
                    <div class='fishMeFullContainer'>
                        <div class = 'hook-div'>
                            <img class = 'hook' src='./pics/hook.png' alt = 'Hook Picture'>
                        </div>
                        <div class = 'fishMe-container'>
                            <img class = 'fishMe' src = './pics/fishme.png' alt = 'Fish Me A Story'>
                        </div>
                    </div>
                </a>";
        ?>
        <div class = "title-container">
            <img class = "title" src = "./pics/name.png" alt = "Title">
        </div>
    </div>
    
    <div class = second-body>
        <div class = "tagline-container">
            <center>
            <img class = "tagline" src = "./pics/tagline.png" alt = "Tagline">
            </center>
        </div>
        <div></div>
    </div>

    <div class = "main-container border">
        <?php

            if(isset($_SESSION['lastGenreVisited'])){
                if(count($forYouStory)==0){

                } else {
                echo "<div class = 'for-you-container'>
                <div class = 'text-container border'>
                    <div class = 'text-label-backg border'>
                        <p class = 'text-label border'>For You</p>
                    </div>
                    <hr class = 'broken-line'>
                </div>
                <div class = 'main-area border'>
                    <span></span>
                    <div class='slider'>

                  <div class='slides'>
                    <div id='slide-1'>";
                        if(isset($forYouStory[9])){
                            $limitArray = $forYouStory[9];
                        } else {
                            $limitArray = false;
                        }
                        foreach ($forYouStory as $forYouInfo) {
                            if($forYouInfo == $limitArray){
                                break;
                            }
                            // code...
                        #echo var_dump($storyInfo);
                        $storyAuthor = $forYouInfo['author'];
                        $searchResult = $dbLink->findAuthorByID($storyAuthor);
                        $storyAuthor = $searchResult['user'];
                        $storyTitle = $forYouInfo['title'];
                        $storyBody = $forYouInfo['story'];
                        $storyId = $forYouInfo['#'];
                        if($forYouInfo['isHead']==0){
                            $searchResult = $dbLink->getHead($forYouInfo['#']);
                            $searchResult = $dbLink->getStory($searchResult);
                            $storyTitle = $searchResult['title']; 
                        }
                        echo "<div><a href='substory.php?storyId=$storyId'>";
                        echo "<span class = 'title-block'><div class='story-title'><b> $storyTitle </b></div><div><br>by $storyAuthor</div></span>";
                        echo "<div class = 'body-block'> $storyBody </div></a></div>";
                        }
                        echo "</div>
                  </div>
                </div>
                    <span></span>";
                if(count($forYouStory)>9){
                    echo "<div> 
                    <a href='allstories.php?category=recommendation'><input class='SeeMorebutton' type='button' name='' value='See More'></a>
                </div>";
                }
                echo "
                </div>
            </div>
        ";

            }
        }
            ?>
            <div class = "trending-container border ">
                <div class = "text-container border ">
                    <div class = "text-label-backg border">
                        <p class = "text-label border">Trending</p>
                    </div>
                    <hr class = "broken-line">
                </div>
                <div class = "main-area border ">
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
                        if(isset($trendingStory[9])){
                            $limitArray = $trendingStory[9];
                        } else {
                            $limitArray = false;
                        }
                        foreach ($trendingStory as $trendingInfo) {
                            if($trendingInfo == $limitArray){
                                break;
                            }
                            // code...
                        #echo var_dump($storyInfo);
                        $storyAuthor = $trendingInfo['author'];
                        $searchResult = $dbLink->findAuthorByID($storyAuthor);
                        $storyAuthor = $searchResult['user'];
                        $storyTitle = $trendingInfo['title'];
                        $storyBody = $trendingInfo['story'];
                        $storyId = $trendingInfo['#'];
                        if($trendingInfo['isHead']==0){
                            $searchResult = $dbLink->getHead($trendingInfo['#']);
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
                if(count($trendingStory)>9){
                    echo "<div> 
                    <a href='trending.php'><input class='SeeMorebutton' type='button' name='' value='See More'></a>
                </div>";
                }
                ?>

                </div>
            </div>
            
        <div class = "second-main-container border">
        <div class = "trending-container border ">
            <div class = "text-container border ">
                <div class = "text-label-backg border">
                    <p class = "text-label border">Newest</p>
                </div>
                <hr class = "broken-line">
            </div>
            <div class = "main-area border ">
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
                        if(isset($newestStory[9])){
                            $limitArray = $newestStory[9];
                        } else {
                            $limitArray = false;
                        }
                        foreach ($newestStory as $newestInfo) {
                            if($newestInfo == $limitArray){
                                break;
                            }
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
                <span></span>
                <?php
                if(count($newestStory)>9){
                    echo "<div> 
                    <a href='newest.php'><input class='SeeMorebutton' type='button' name='' value='See More'></a>
                </div>";
                }
                ?>
            </div>
        </div>
    </div>
    <div class ="contact-container border">
        <img class = "contacts border" src = "./pics/contacts.png" alt = "Contacts">
    </div>
</div>
</div>
        <?php include 'footer.php' ?>
</div>
    
</body>
</html>

