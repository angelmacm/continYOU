<?php
include 'db.php';
include 'functions.php';

ob_start();
session_start();
$dbLink = new DB();

if(isset($_GET['openDraft'])){
    $draftIdArg = $_GET['openDraft'];
    $searchResult = $dbLink->getStory($draftIdArg);
    $userResult = $dbLink->findAuthorDetails($_SESSION['userToken']);
    $userId = $userResult['id'];
    if($userId != $searchResult['author']){
        redirectTo('./');
    }
    $draftTitle = $searchResult['title'];
    $draftSynopsis = $searchResult['synopsis'];
    $draftGenre = $searchResult['genre'];
    $draftGenreText = ucfirst($draftGenre);
    $draftBody = $searchResult['story'];
} else {
    $draftSynopsis = '';
    $draftBody = '';
}

if(isset($_SESSION['userToken'])){

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
    <title>Create Your Story</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/createstory.css">
    <link rel="stylesheet" href="css/popup.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="css/footer.css">
    <script src="https://kit.fontawesome.com/3204ead578.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter&family=Open+Sans:wght@300&family=Spectral:wght@200;300&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'header.php' ?>
<!--Body Here-->
    <div class="page-container">
        <div class="content-wrap">
    <div class="page-title-container">
        <p class ='page-title'>Create your story</p>
    </div>
    <form method="post">
        <div class="story-genre">
            <div class="title-container">
                <label for="story-title" class="story-title-label">Story Title</label><br>
                <?php
                if(isset($_GET['openDraft'])){
                    echo "<input type='text' name='title' id='story-title' class='story-title' value = '$draftTitle' required>";
                } else {
                    echo "<input type='text' name='title' id='story-title' class='story-title' required>";
                }
                ?>
            </div>
            <div class="genre-container">
                <label for="genre" class="genre-label">Genre</label><br>
                <select id="genre" name="genre" class="genre" required>
                <?
                if(isset($_GET['openDraft'])){
                    echo "<option value='$draftGenre'>$draftGenreText</option>";
                } 
                ?>
                  <option value="romance">Romance</option>
                  <option value="comedy">Comedy</option>
                  <option value="drama">Drama</option>
                  <option value="fantasy">Fantasy</option>
                  <option value="horror">Horror</option>
                </select>
            </div>
            <div class="synopsis-container">
                <label for="synoysis-here" class="synopsis-here-label">Synopsis</label><br>
                <?php
                echo "<textarea id='synopsis-here' class ='synopsis-here' name='synopsis' required>$draftSynopsis</textarea>"
                ?>
            </div>
            <div class="body-container">
                <label for="story-here" class="story-here-label">Body</label><br>
                <div class='DiscardSavePublish'>
                    <a href="#discardConfirmation"><button type='button' class="discardButton">Discard</button></a>
                    <?php
                    if(isset($_GET['openDraft'])){
                        echo "  <a href='#deleteConfirmation'><button type='button' class='deleteButton'>Delete</button></a><input action='#' class='saveButton' name='updateDraft' type='submit' value='Update'>
                                <input action='#' class='publishButton' name='publishDraft' type='submit' value='Publish'>";
                    } else {
                        echo "  <input action='#' class='saveButton' name='saveButton' type='submit' value='Save'>
                                <input action='#' class='publishButton' name='publishButton' type='submit' value='Publish'>";
                    }
                    ?>
                    
                </div>
                <?php
                echo "<textarea id='story-here' class ='story-here' name='body' required>$draftBody</textarea>"
                ?>
            </div>
        </div>
    </form>
        <?php 
            if(isset($_POST['updateDraft'])){
                $dbLink->updateHeadDraft($draftIdArg, $_POST['title'], $_POST['body'], $_POST['genre'], $_POST['synopsis']);
                $_SESSION['storyId'] = $draftIdArg;
                redirectTo("#storyUpdate");
            }
            
            if(isset($_POST['publishDraft'])){
                $dbLink->updateHeadDraft($draftIdArg, $_POST['title'], $_POST['body'], $_POST['genre'], $_POST['synopsis']);
                $dbLink->publishDraft($draftIdArg, $isHeadArg = 1);
                $_SESSION['storyId'] = $draftIdArg;
                redirectTo("#draftPublished");
            }

            if(isset($_POST['saveButton'])){
                $dbLink->createHeadStory($_POST['title'], $_POST['synopsis'], $_POST['genre'], $_POST['body'], $publishArg = 0);
                $storyId = $dbLink->findExactStory($_POST['body'],$_POST['synopsis'],0);
                $_SESSION['storyId'] = $storyId['#'];
                redirectTo("#storySaved");
            }

            if(isset($_POST['publishButton'])){
                $dbLink->createHeadStory($_POST['title'], $_POST['synopsis'], $_POST['genre'], $_POST['body']);
                $storyId = $dbLink->findExactStory($_POST['body'],$_POST['synopsis'],0);
                $_SESSION['storyId'] = $storyId['#'];
                redirectTo("#storyPublished");
            }

            ?>
        <div>
            <?php
            if(isset($_SESSION['storyId'])){
                $storyId = $_SESSION['storyId'];
                successDialog('Story Successfully Saved', 'Story sucessfully saved in drafts', 'storySaved',"substory.php?storyId=$storyId");
                successDialog('Story Successfully Published', 'Story sucessfully published and is now public', 'storyPublished',"substory.php?storyId=$storyId");
                successDialog('Story Successfully Updated', 'Story sucessfully updated and saved in drafts', 'storyUpdate',"substory.php?storyId=$storyId");
                successDialog('Draft Successfully Published', 'Draft sucessfully published and is now public', 'draftPublished',"substory.php?storyId=$storyId");
            }
            successDialog('Draft Successfully Deleted', 'Draft sucessfully deleted and is removed from your account', 'draftDeleted',"#");
            confirmationDialog('Warning!', 'Do you really want to discard the changes in the draft?', 'discardConfirmation', "createstory.php");
            deleteDialog('Warning!', 'Do you really want to DELETE the story?', 'deleteConfirmation', "createstory.php");
            if(isset($_POST['confirmDelete'])){
                $dbLink->deleteDraft($draftIdArg);
                redirectTo("createstory.php#draftDeleted");
            }
            ?>
        </div>
    </div>
        <?php include 'footer.php' ?>
        </div>
</body>
</html>