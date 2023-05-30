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
    <title>All Genres</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://kit.fontawesome.com/3204ead578.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/genre.css">
    <link rel="stylesheet" href="css/footer.css">
    <script src="https://kit.fontawesome.com/3204ead578.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter&family=Open+Sans:wght@300&family=Spectral:wght@200;300&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'header.php' ?>

    <!--Start of First Page Here-->
    <div class="page-container">
        <div class="content-wrap">
    <div class = "main-container">
        <div class ="first-division">
            <p class="first-division-title">Genre</p>
        </div>
        <div class = "first-page-carousel-container ">
            <div class = "text-container">
                <div class = "text-label-backg">
                    <p class = "text-label">Romance</p> <!--Drama Section-->
                </div>
                <hr class = "broken-line">
            </div>
            <div class = "main-area">
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
                        $romanceStories = $dbLink->getGenre('romance');
                        if(isset($romanceStories[9])){
                            $limitArray = $romanceStories[9];
                        } else {
                            $limitArray = false;
                        }
                        foreach ($romanceStories as $romanceInfo) {
                            if($romanceInfo == $limitArray){
                                break;
                            }
                            // code...
                        #echo var_dump($storyInfo);
                        $storyAuthor = $romanceInfo['author'];
                        $searchResult = $dbLink->findAuthorByID($storyAuthor);
                        $storyAuthor = $searchResult['user'];
                        $storyTitle = $romanceInfo['title'];
                        $storyBody = $romanceInfo['story'];
                        $storyId = $romanceInfo['#'];
                        if($romanceInfo['isHead']==0){
                            $searchResult = $dbLink->getHead($romanceInfo['#']);
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
                if(count($romanceStories)>9){
                    echo "<div> 
                    <a href='allstories.php?category=romance'><input class='SeeMorebutton' type='button' name='' value='See More'></a>
                </div>";
                }
                ?>
            </div>
        </div>
    </div>

    <!--Start of Second Page-->
    

    <!--First Division in Second Page-->
    <div class = "second-main-container">
        <div class = "first-division-container">
            <div class = "text-container">
                <div class = "text-label-backg">
                    <p class = "text-label">Comedy</p> <!--Comedy Section-->
                </div>
                <hr class = "broken-line">
            </div>
            <div class = "main-area ">
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
                        $comedyStories = $dbLink->getGenre('comedy');
                        if(isset($comedyStories[9])){
                            $limitArray = $comedyStories[9];
                        } else {
                            $limitArray = false;
                        }
                        foreach ($comedyStories as $comedyInfo) {
                            if($comedyInfo == $limitArray){
                                break;
                            }
                        #echo var_dump($storyInfo);
                        $storyAuthor = $comedyInfo['author'];
                        $searchResult = $dbLink->findAuthorByID($storyAuthor);
                        $storyAuthor = $searchResult['user'];
                        $storyTitle = $comedyInfo['title'];
                        $storyBody = $comedyInfo['story'];
                        $storyId = $comedyInfo['#'];
                        if($comedyInfo['isHead']==0){
                            $searchResult = $dbLink->getHead($comedyInfo['#']);
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
                if(count($comedyStories)>9){
                    echo "<div> 
                    <a href='allstories.php?category=comedy'><input class='SeeMorebutton' type='button' name='' value='See More'></a>
                </div>";
                }
                ?>
            </div>
        </div>

    <!--Second Division in Second Page-->
    <div class = "second-division-container ">
        <div class = "text-container">
            <div class = "text-label-backg">
                <p class = "text-label">Drama</p> <!--Drama Section-->
            </div>
            <hr class = "broken-line">
        </div>
        <div class = "main-area">
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
                        $dramaStories = $dbLink->getGenre('drama');
                        if(isset($dramaStories[9])){
                            $limitArray = $dramaStories[9];
                        } else {
                            $limitArray = false;
                        }
                        foreach ($dramaStories as $dramaInfo) {
                            if($dramaInfo == $limitArray){
                                break;
                            }
                        #echo var_dump($storyInfo);
                        $storyAuthor = $dramaInfo['author'];
                        $searchResult = $dbLink->findAuthorByID($storyAuthor);
                        $storyAuthor = $searchResult['user'];
                        $storyTitle = $dramaInfo['title'];
                        $storyBody = $dramaInfo['story'];
                        $storyId = $dramaInfo['#'];
                        if($dramaInfo['isHead']==0){
                            $searchResult = $dbLink->getHead($dramaInfo['#']);
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
                if(count($dramaStories)>9){
                    echo "<div> 
                    <a href='allstories.php?category=drama'><input class='SeeMorebutton' type='button' name='' value='See More'></a>
                </div>";
                }
                ?>
        </div>
    </div>

    <!--Start of Third Page-->
    

    <!--First Division in Third Page-->
    <div class = "second-main-container">
        <div class = "first-division-container">
            <div class = "text-container">
                <div class = "text-label-backg">
                    <p class = "text-label">Fantasy</p> <!--Fantasy Section-->
                </div>
                <hr class = "broken-line">
            </div>
            <div class = "main-area ">
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
                        $fantasyStories = $dbLink->getGenre('fantasy');
                        if(isset($fantasyStories[9])){
                            $limitArray = $fantasyStories[9];
                        } else {
                            $limitArray = false;
                        }
                        foreach ($fantasyStories as $fantasyInfo) {
                            if($fantasyInfo == $limitArray){
                                break;
                            }
                        #echo var_dump($storyInfo);
                        $storyAuthor = $fantasyInfo['author'];
                        $searchResult = $dbLink->findAuthorByID($storyAuthor);
                        $storyAuthor = $searchResult['user'];
                        $storyTitle = $fantasyInfo['title'];
                        $storyBody = $fantasyInfo['story'];
                        $storyId = $fantasyInfo['#'];
                        if($fantasyInfo['isHead']==0){
                            $searchResult = $dbLink->getHead($fantasyInfo['#']);
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
                if(count($fantasyStories)>9){
                    echo "<div> 
                    <a href='allstories.php?category=fantasy'><input class='SeeMorebutton' type='button' name='' value='See More'></a>
                </div>";
                }
                ?>
            </div>
        </div>

    <!--Second Division in Third Page-->
    <div class = "second-division-container ">
        <div class = "text-container">
            <div class = "text-label-backg">
                <p class = "text-label">Horror</p> <!--Horror Section-->
            </div>
            <hr class = "broken-line">
        </div>
        <div class = "main-area">
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
                        $horrorStories = $dbLink->getGenre('horror');
                        if(isset($horrorStories[9])){
                            $limitArray = $horrorStories[9];
                        } else {
                            $limitArray = false;
                        }
                        foreach ($horrorStories as $horrorInfo) {
                            if($horrorInfo == $limitArray){
                                break;
                            }
                        #echo var_dump($storyInfo);
                        $storyAuthor = $horrorInfo['author'];
                        $searchResult = $dbLink->findAuthorByID($storyAuthor);
                        $storyAuthor = $searchResult['user'];
                        $storyTitle = $horrorInfo['title'];
                        $storyBody = $horrorInfo['story'];
                        $storyId = $horrorInfo['#'];
                        if($horrorInfo['isHead']==0){
                            $searchResult = $dbLink->getHead($horrorInfo['#']);
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
            #echo count($horrorStories);
                if(count($horrorStories)>=9){
                    echo "<div> 
                        <a href='allstories.php?category=horror'><input class='SeeMorebutton' type='button' name='' value='See More'></a>
                    </div>";
                }
                ?>
        </div>
    </div>
</div>
</div>
</div>
        <?php include 'footer.php' ?>
</div>

</body>
</html>