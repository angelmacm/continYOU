<?php

include_once 'functions.php';
include_once 'db.php';

$dbLink = new DB();

session_start();
$currentStoryID = $_GET['currentStoryId'];
$isDownvoted = (boolval($_GET['isDownvoted']) ? true : false);
$isUpvoted = (boolval($_GET['isUpvoted']) ? true : false);

echo var_dump($_GET);
if (isset($_SESSION['formtoken']))
{
    if (isset($_GET['token']))
    {
        if ($_GET['token'] != $_SESSION['formtoken'])
        {
            redirectTo("substory.php?storyId=$currentStoryID");
        } else {
            $_SESSION['formtoken'] = md5(session_id() . time());
            if(isset($_GET['downvote_x'])){
                if(isset($_SESSION['userToken'])){
                    if($isDownvoted){
                        $dbLink->removeDownvote($currentStoryID);
                    } else {
                        $dbLink->addDownvote($currentStoryID);
                    }
                    redirectTo("substory.php?storyId=$currentStoryID");
                } else {
                    $_SESSION['redirectAfterLogin'] = "substory.php?storyId=$currentStoryID";
                    redirectTo('login.php');
                }
            }
            if(isset($_GET['upvote_x'])){
                if(isset($_SESSION['userToken'])){
                    if($isUpvoted){
                        $dbLink->removeUpvote($currentStoryID);
                    } else {
                        $dbLink->addUpvote($currentStoryID);
                    }
                    redirectTo("substory.php?storyId=$currentStoryID");
                } else {
                    $_SESSION['redirectAfterLogin'] = "substory.php?storyId=$currentStoryID";
                    redirectTo('login.php');
                }
            }

            if(isset($_GET['followed'])){
                if(isset($_SESSION['userToken'])){
                    $dbLink->toggleFollowed($_GET['currentStoryId']);
                    redirectTo("substory.php?storyId=$currentStoryID");
                } else {
                    $_SESSION['redirectAfterLogin'] = "substory.php?storyId=$currentStoryID";
                    redirectTo("login.php");
                }
            }
        }
    }
}

?>
