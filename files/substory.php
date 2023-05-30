<?php
include 'db.php';
include 'functions.php';

ob_start();
session_start();
$dbLink = new DB();

if(isset($_SESSION['storyId'])){
    unset($_SESSION['storyId']);
}

if(isset($_GET['storyId'])){
    #echo $_GET['storyId'];
    $currentStoryID = $_GET['storyId'];
    $storyResults = $dbLink->getStory($currentStoryID);
    $isPublished = $storyResults['isPublished'];
    $isHead = $storyResults['isHead'];
    if($isPublished == 0){
        if(isset($_SESSION['userToken'])){
            if($storyResults['isHead'] == 0){
                $searchResult = $dbLink->findAuthorDetails($_SESSION['userToken']);
                $userId = $searchResult['id'];
                if($userId != $storyResults['author']){
                    redirectTo('./');
                }
                $draftBody = $storyResults['story'];
                $draftChangelog = $storyResults['synopsis'];
                $draftGenre = $storyResults['genre'];
                $storyResults = $dbLink->getStory($storyResults['prevNode']);
                $isDraft = true;
            } else {
                redirectTo("createstory.php?openDraft=$currentStoryID");
            }

        } else {
            redirectTo('./');
        }
    }  else {
        $isDraft = false;
        $draftBody = "";
        $draftChangelog = "";
    }
    if($isHead==0){
        if(isset($searchResult['prevNode'])){
            $prevNode = $searchResult['prevNode'];
        }
    }

    if(isset($_SESSION['userToken'])){
        $searchResult = $dbLink->findAuthorDetails($_SESSION['userToken']);
        $upvotedList = explode(',', $searchResult['upvotes']);
        $downvotedList = explode(',', $searchResult['downvotes']);
        $followedList = explode(',', $searchResult['followedStories']);
        $isUpvoted = in_array($currentStoryID, $upvotedList);
        $isDownvoted = in_array($currentStoryID, $downvotedList);
        $isFollowed = in_array($currentStoryID, $followedList);
    } else {
        $isUpvoted = false;
        $isDownvoted = false;
        $isFollowed = false;
    }
    $storyTitle = $storyResults['title'];
    $storyBody = $storyResults['story'];
    $storyGenre = $storyResults['genre'];
    $storyUpvote = $storyResults['upvote'];
    $storyDownvote = $storyResults['downvote'];
    $storyGenreText = ucfirst($storyGenre);
    $dbLink->updateGenreVisit($storyResults['genre']);
    if($storyTitle == ''){
        $storyResults = $dbLink->findHead($currentStoryID);
        $storyResults = $dbLink->getStory($storyResults);
        $storyTitle = $storyResults['title'];
    }
} else {
    redirectTo('./');
}


if (isset($_SESSION['userToken'])) {
    $dbLink->updateLastViewedStory($_SESSION['userToken'],$_GET['storyId']);
    $authorDetails = $dbLink->findAuthorDetails($_SESSION['userToken']);
    $_SESSION['username'] = $authorDetails['user'];
    $_SESSION['emailAddress'] = $authorDetails['email'];
    $date = new DateTime($authorDetails['birthdate']);
    $_SESSION['birthDay'] = $date->format('F d Y');;
    $tempFullName = array($authorDetails['first_name'], $authorDetails['last_name']);
    $fullName = implode(' ', $tempFullName);
    $_SESSION['fullName'] = $fullName;
}

$_SESSION['formtoken'] = md5(session_id() . time());

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $storyTitle ?></title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/substory.css">
    <link rel="stylesheet" href="css/popup.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://kit.fontawesome.com/3204ead578.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include 'header.php' ?>
<!--Body Here-->
    <div class="page-container">
        <div class="content-wrap">
            <div class="title-container">
                <?php echo "<p class ='title'>$storyTitle</p>"; ?>
            </div>
            <div>
                <?php $formattedStory = nl2br($storyBody);
                echo "<p id='storyHere' class ='storyHere'> $formattedStory </p>" ?>
            </div>

            
            <div class = "bottom-interaction">
                <br><br>
                <form action="upvotedownvote.php">
                <div class = "vote-container">
                    <?php
                    $formtoken = $_SESSION['formtoken'];
                    echo "<input type='hidden' name='token' value='$formtoken'/><input type='hidden' name='currentStoryId' value='$currentStoryID'>";
                    if($isUpvoted){
                        echo "<input type='hidden' id='isUpvoted' name='isUpvoted' value='1'>
                        <input class = 'upDownVote upvote checked' type='image' name='upvote' src='./pics/upvote.png' alt='Submit'>";
                    } else {
                     echo "<input type='hidden' id='isUpvoted' name='isUpvoted' value='0'>
                     <input class = 'upDownVote upvote' type='image' name='upvote' src='./pics/upvote.png' alt='Submit'>";
                    }
                    ?>
                    <?php
                        if($storyUpvote == 0){

                        } else {
                            echo "<p class='upVoteCounter'>$storyUpvote</p>";
                        }
                    ?>
                    <?php
                    if($isDownvoted){
                        echo "<input type='hidden' id='isDownvoted' name='isDownvoted' value='1'>
                        <input class = 'upDownVote downvote checked' type='image' name='downvote' src='./pics/downvote.png' alt='Submit'>";
                    } else {
                     echo "<input type='hidden' id='isDownvoted' name='isDownvoted' value='0'>
                     <input class = 'upDownVote downvote' type='image' name='downvote' src='./pics/downvote.png' alt='Submit'>";
                    }
                    ?>

                    <?php
                        if($storyDownvote == 0){

                        } else {
                            echo "<p class='downVoteCounter'>$storyDownvote</p>";
                        }
                    ?>
                    <?php 
                    if($isFollowed){
                        echo "<input type='submit' name='followed' value='Followed' class='followButton checked'>";
                    } else {
                        echo "<input type='submit' name='followed' value='Follow' class='followButton'>";
                    }
                    ?>
                </div>
                    </form>
                <form method="post">
                    <?php
                        if($isPublished == 1){
                            echo "<input class='continYOUbutton' type='submit' name='continyouButton' value='ContinYOU the story'>";
                        } else {
                            echo "<input class='continYOUbutton' type='submit' name='continyouButton' value='Open Draft'>";
                        }
                    ?>
                </form>
            </div>
                <?php
                $linkedStory = $dbLink->getLinkedStories($currentStoryID);
                if ($linkedStory==False){

                } else {
                    echo "<div class = 'main-container border'>
                                    <div class ='first-division'>
                                        <p class='first-division-title'>Branching Stories</p>
                                    </div>
                                    <div class = 'sub-link-container '>
                                        <div class = 'main-area  '>
                                            <div class='slider'>
                                        <!--
                                          <a href='#slide-1'>1</a>
                                          <a href='#slide-2'>2</a>
                                          <a href='#slide-3'>3</a>
                                        -->
                                          <div class='slides'>
                                            <div id='slide-1'>";
                    if(isset($linkedStory[9])){
                        $limitArray = $linkedStory[9];
                    } else {
                        $limitArray = false;
                    }
                    foreach ($linkedStory as $linkedInfo) {
                        if($linkedInfo == $limitArray){
                            break;
                        }
                        // code...
                    $searchResult = $dbLink->findAuthorByID($linkedInfo['author']);
                    #echo $linkedInfo['author'];
                    #echo var_dump($searchResult);
                    $storyTitle = $searchResult['user'];
                    $storyBody = $linkedInfo['synopsis'];
                    $storyId = $linkedInfo['#'];
                    echo "<div><a href='substory.php?storyId=$storyId'>";
                    echo "<span class = 'title-block'><b>by $storyTitle</b></span>";
                    echo "<div class = 'body-block'> $storyBody </div></a></div>";
                    }
                    echo "</div>
                          </div>";

                        
                        if(count($linkedStory)>9){
                            echo "<div> 
                            <a href='allstories.php?category=branchstories&storyId=$currentStoryID'><input class='SeeMorebutton' type='button' name='' value='See More'></a>
                        </div>";
                        }
                        

                    echo "</div>
                        </div>
                    </div>";
                }
                ?>
                <br>
                <?php
                if(isset($_POST['continyouButton'])){
                    if(isset($_SESSION['userToken'])){
                        echo "<div>
                                <div>
                                    <a href='#storyHere'><i class='fa-solid fa-circle-arrow-up'></i></a> 
                                </div>
                                <br>
                            </div>
                            <form method='post'>
                                <div class='genre-container'>
                                    <label for='genre' class='genre-label'>Genre</label><br>
                                    <select id='genre' name='genre' class='genre' required>";
                                    if($isDraft){
                                    $draftGenreText = ucfirst($draftGenre);
                                     echo "<option value='$draftGenre'>$draftGenreText</option>";
                                    } else {
                                        echo "<option value='$storyGenre'>$storyGenreText</option>";
                                    }
                                    
                                    echo  "<option value='romance'>Romance</option>
                                      <option value='comedy'>Comedy</option>
                                      <option value='drama'>Drama</option>
                                      <option value='fantasy'>Fantasy</option>
                                      <option value='horror'>Horror</option>
                                    </select>
                                </div>
                                <div>
                                    <textarea id='writeHere' class='writeHere' name='storyBody'>$draftBody</textarea>
                                </div>
                                <div>
                                    <p class = 'change-log'>Change Log:</p>
                                    <textarea class='log' type = 'text' name='changeLog'>$draftChangelog</textarea>
                                </div>
                                <div class='DiscardSavePublish'>
                                    <div></div>
                                    <div class='actual-buttons'>
                                    <a href='#discardConfirmation'><button type='button' class='discardButton'>Discard</button></a>";
                                if($isDraft){
                                    echo "<a href='#deleteConfirmation'><button type='button' class='deleteButton'>Delete</button></a><input action='#' class='saveButton' name='updateButton' type='submit' value='Update'>
                                        <input action='#' class='publishButton' name='publishDraftButton' type='submit' value='Publish'>";
                                } else {
                                    echo "<input action='#' class='saveButton' name='saveButton' type='submit' value='Save'>
                                        <input action='#' class='publishButton' name='publishButton' type='submit' value='Publish'>";
                                }
                                

                                echo "
                                </div>
                                </div>
                            <br>
                            </form>";
                    } else {
                        $_SESSION['redirectAfterLogin'] = "substory.php?storyId=$currentStoryID";
                        redirectTo('login.php');
                    }
                    }
                    if(isset($_POST['publishDraftButton'])){
                        if(isset($prevNode)){
                        $dbLink->updateDraft($currentStoryID, $_POST['storyBody'], $_POST['genre'], $_POST['changeLog']);
                        $dbLink->publishDraft($currentStoryID, $isHeadArg = $isHead, $prevNodeArg = $prevNode);
                        } else {
                            $dbLink->updateDraft($currentStoryID, $_POST['storyBody'], $_POST['genre'], $_POST['changeLog']);
                            $dbLink->publishDraft($currentStoryID, $isHeadArg = $isHead);
                        }
                        
                        redirectTo('#draftPublished');
                    }

                    if(isset($_POST['updateButton'])){
                        $dbLink->updateDraft($currentStoryID, $_POST['storyBody'], $_POST['genre'], $_POST['changeLog']);
                            redirectTo("substory.php?storyId=$currentStoryID");
                            
                            redirectTo('#draftUpdated');
                    }
                    ?>
                    <?php 

                    if(isset($_POST['discardButton'])){
                        redirectTo("substory.php?storyId=$currentStoryID");
                    }

                    if(isset($_POST['saveButton'])){
                        
                        $searchResult = $dbLink->findAuthorDetails($_SESSION['userToken']);

                        $dbLink->addSubStory($currentStoryID, $_POST['storyBody'], $_POST['genre'], $_POST['changeLog'], 0, $searchResult['id']);
                        redirectTo('#draftSaved');
                    }

                    if(isset($_POST['publishButton'])){
                        $searchResult = $dbLink->findAuthorDetails($_SESSION['userToken']);
                        $dbLink->addSubStory($currentStoryID, $_POST['storyBody'], $_POST['genre'], $_POST['changeLog'], 1, $searchResult['id']);
                        
                        redirectTo('#storyCreated');
                    }

                    ?>
                    <div>
                    <?php
                    successDialog('Story Created!','Draft successfully published!', 'draftPublished',"substory.php?storyId=$currentStoryID");
                    successDialog('Story Created!','Story successfully updated!', 'draftUpdated',"substory.php?storyId=$currentStoryID");
                    successDialog('Story Created!','Story successfully saved!', 'draftSaved',"substory.php?storyId=$currentStoryID");
                    successDialog('Story Created!','Story successfully published!', 'storyCreated',"substory.php?storyId=$currentStoryID");
                    errorDialog('Story not Created', 'Please Check your Inputs', 'storyNotCreated', '#');
                    confirmationDialog('Warning!', 'Do you really want to discard the story?', 'discardConfirmation', "createstory.php");
                    deleteDialog('Warning!', 'Do you really want to DELETE the story?', 'deleteConfirmation', "mystories.php");
                    if(isset($_POST['confirmDelete'])){
                        $dbLink->deleteDraft($currentStoryID);
                        redirectTo("mystories.php#draftDeleted");
                    }
                    ?>
                    </div>
                </div>
    <?php include 'footer.php' ?>
        </div>
</body>
</html>