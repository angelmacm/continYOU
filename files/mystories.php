<?php
include 'db.php';
include 'functions.php';

ob_start();
session_start();

    if (isset($_SESSION['userToken'])) {
        $setLoggedIn = true;
    } else {
        redirectTo("/");
    }


$dbLink = new DB();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Stories</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://kit.fontawesome.com/3204ead578.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/mystories.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/popup.css">
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
                <center>
                <p class="first-division-title">My Stories</p>
                </center>
            </div>
            <div class = "second-division">
                <div class = "text-container">
                    <div class = "text-label-backg">
                        <p class = "text-label">Published</p> <!--Comedy Section-->
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
                        $publishedStory = $dbLink->getPublished();
                        if ($publishedStory[0]==False){

                        } else {
                        if(isset($publishedStory[9])){
                            $limitArray = $publishedStory[9];
                        } else {
                            $limitArray = false;
                        }
                        foreach ($publishedStory as $publishedInfo) {
                            if($publishedInfo == $limitArray){
                                break;
                            }
                            // code...
                        $storyAuthor = $publishedInfo['author'];
                        $searchResult = $dbLink->findAuthorByID($storyAuthor);
                        $storyAuthor = $searchResult['user'];
                        $storyTitle = $publishedInfo['title'];
                        $storyBody = $publishedInfo['story'];
                        $storyId = $publishedInfo['#'];
                        if($publishedInfo['isHead']==0){
                            $searchResult = $dbLink->getHead($publishedInfo['#']);
                            $searchResult = $dbLink->getStory($searchResult);
                            $storyTitle = $searchResult['title']; 
                        }
                        echo "<div><a href='substory.php?storyId=$storyId'>";
                        echo "<span class = 'title-block'><div class='story-title'><b> $storyTitle </b></div><div><br>by $storyAuthor</div></span>";
                        echo "<div class = 'body-block'> $storyBody </div></a></div>";
                        }
                    }
                        ?>
                    </div>
                  </div>
                </div>
                    <span></span>
                    <?php
                if(count($publishedStory)>9){
                    echo "<div> 
                    <a href='allstories.php?category=published'><input class='SeeMorebutton' type='button' name='' value='SeeMore'></a>
                </div>";
                }
                ?>
                </div>
            </div>
            <div class = "remaining-second-division">
                <div class = "text-container  ">
                    <div class = "text-label-backg ">
                        <p class = "text-label ">Draft</p> <!--Drama Section-->
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
                        $draftStory = $dbLink->getDrafts();
                        if ($draftStory[0]==False){

                        } else {
                        if(isset($draftStory[9])){
                            $limitArray = $draftStory[9];
                        } else {
                            $limitArray = false;
                        }
                        foreach ($draftStory as $draftInfo) {
                            if($draftInfo == $limitArray){
                                break;
                            }
                            // code...
                        $storyAuthor = $draftInfo['author'];
                        $searchResult = $dbLink->findAuthorByID($storyAuthor);
                        $storyAuthor = $searchResult['user'];
                        $storyTitle = $draftInfo['title'];
                        $storyBody = $draftInfo['story'];
                        $storyId = $draftInfo['#'];
                        if($draftInfo['isHead']==0){
                            $searchResult = $dbLink->getHead($draftInfo['#']);
                            $searchResult = $dbLink->getStory($searchResult);
                            $storyTitle = $searchResult['title']; 
                        }
                        echo "<div><a href='substory.php?storyId=$storyId'>";
                        echo "<span class = 'title-block'><div class='story-title'><b> $storyTitle </b></div><div><br>by $storyAuthor</div></span>";
                        echo "<div class = 'body-block'> $storyBody </div></a></div>";
                        }
                    }
                        ?>
                    </div>
                  </div>
                </div>
                    <span></span>
                    <?php
                if(count($draftStory)>9){
                    echo "<div> 
                    <a href='allstories.php?category=drafts'><input class='SeeMorebutton' type='button' name='' value='See More'></a>
                </div>";
                }
                ?>
                </div>
            </div>
        </div>
        <div>
            <?php 
            successDialog('Draft Successfully Deleted', 'Draft sucessfully deleted and is removed from your account', 'draftDeleted',"#");
            ?>
        </div>
    </div>
        <?php include 'footer.php' ?>
</div>
</body>
</html>