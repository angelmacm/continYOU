<?php
	class DB {

		private $conn;

		function __construct(){
			$servername = "127.0.0.1";
			$username = "root";
			$password = "";
			$dbname = "continyou";

			// Create connection
			try {
			$this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// Check connection
			}catch(PDOException $e) {
  			echo "Connection failed: " . $e->getMessage();
			}
		}


		function register($first_nameArg, $last_nameArg, $userArg, $passwordArg, $birthdateArg, $phoneArg, $continyouationArg, $storiesArg, $sha256DataArg, $emailArg){
			$stmt = $this->conn->prepare("INSERT INTO users (first_name, last_name, user, email, password, sha256, birthdate, phone, continyouation, stories) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$stmt->execute([$first_nameArg, $last_nameArg, $userArg, $emailArg, $passwordArg, $sha256DataArg, $birthdateArg, $phoneArg, $continyouationArg, $storiesArg]);
			}

		function checkLogin(){
			$userArg = $_POST['user'];
			$passwordArg = $_POST['password'];
			$sha256Arg = hash256($userArg . 'password' . $passwordArg);
			$stmt = $this->conn->prepare("SELECT user FROM users WHERE sha256 = ?");
			$stmt->execute([$sha256Arg]);
			$arr = $stmt->fetch();
			return $arr;
		}

		function findAuthorDetails($sha256Arg){
			$stmt = $this->conn->prepare("SELECT * FROM users WHERE sha256 = ?");
			$stmt->execute([$sha256Arg]);
			$result = $stmt->fetch(PDO::FETCH_NAMED);
			return $result;

		}

		function updateGenreVisit($genreArg){
			if(isset($_SESSION['userToken'])){
				$searchResult = $this->findAuthorDetails($_SESSION['userToken']);
				$lastGenreVisited = $searchResult['lastViewedGenre'];
				if($lastGenreVisited=='{}'){
					$lastGenreVisited = array($genreArg);
					$genreString = implode(',',$lastGenreVisited);
				} elseif (count(explode(',', $lastGenreVisited)) == 5) {
					$lastGenreVisited = explode(',', $lastGenreVisited);
					unset($lastGenreVisited[4]);
					$lastGenreVisited = array_reverse($lastGenreVisited);
					array_push($lastGenreVisited,$genreArg);
					$lastGenreVisited = array_reverse($lastGenreVisited);
					$genreString = implode(',', $lastGenreVisited);
				}
				 else {
					$lastGenreVisited = explode(',', $lastGenreVisited);
					$lastGenreVisited = array_reverse($lastGenreVisited);
					array_push($lastGenreVisited,$genreArg);
					$lastGenreVisited = array_reverse($lastGenreVisited);
					$genreString = implode(',', $lastGenreVisited);
				}
				$stmt = $this->conn->prepare("UPDATE `users` SET `lastViewedGenre` = ? WHERE `users`.`sha256` = ?");
				$stmt->execute([$genreString,$_SESSION['userToken']]);
				$_SESSION['lastGenreVisited'] = $lastGenreVisited;
			} else {
				if(isset($_SESSION['lastGenreVisited'])){
					$_SESSION['lastGenreVisited'] = array_reverse($_SESSION['lastGenreVisited']);
					array_push($_SESSION['lastGenreVisited'],$genreArg);
					$_SESSION['lastGenreVisited'] = array_reverse($_SESSION['lastGenreVisited']);
				} else {
					$_SESSION['lastGenreVisited'] = array();
					array_push($_SESSION['lastGenreVisited'],$genreArg);
				}
			}
		}

		function loginProcess(){
			$result = $this->checkLogin();
			if ($result){
				$_SESSION['userToken'] = $sha256Data;
				redirectTo('index.php');
			}
			else {
				echo "Incorrect Username/password";
			}
		}

		function getAllStories($isPublishedArg = false){
			if($isPublishedArg){
				$stmt = $this->conn->query("SELECT * FROM `stories` WHERE `isPublished` = 1");
			} else {
				$stmt = $this->conn->query("SELECT * FROM stories");
			}
			$result = $stmt->fetchAll();
			return $result;
		}

		function updateLastViewedStory($userToken, $newViewedStory){
			$stmt = $this->conn->prepare("UPDATE `users` SET `lastViewedStory` = ? WHERE `users`.`sha256` = ?");
			$stmt->execute([$newViewedStory,$userToken]);
		}

		function getTotalVote($storyIdArg){
			#find head, check totalUpvote and downvote
			#return headId, totalUp, totalDown
		}

		function addUpvote($storyIdArg){
			$searchResult = $this->getStory($storyIdArg);
			$totalUpvotes = (int)$searchResult['upvote'];
			$totalUpvotes = ++$totalUpvotes;
			
			$stmt = $this->conn->prepare("UPDATE `stories` SET `upvote` = ? WHERE `stories`.`#` = ?");
			$stmt->execute([$totalUpvotes,$storyIdArg]);

			$searchResult = $this->findAuthorDetails($_SESSION['userToken']);
			$likedStories = $searchResult['likedStories'];
			$searchResult = $searchResult['upvotes'];
			if($searchResult == '{}'){
				$upvoteArray = $storyIdArg;
			} else {
				$upvoteArray = explode(",",$searchResult);
				array_push($upvoteArray, $storyIdArg);
				$upvoteArray = implode(",", $upvoteArray);
			}
			$stmt = $this->conn->prepare("UPDATE `users` SET `upvotes` = ? WHERE `users`.`sha256` = ?");
			$stmt->execute([$upvoteArray,$_SESSION['userToken']]);

			if($likedStories == '{}' or $likedStories == ""){
				$likedArray = $storyIdArg;
			} else {
				if(in_array($storyIdArg, explode(",",$likedStories))){
					$likedArray = explode(",",$likedStories);
				} else {
					$likedArray = explode(",",$likedStories);
					array_push($likedArray, $storyIdArg);
					$likedArray = implode(",", $likedArray);
				}
			}
			$stmt = $this->conn->prepare("UPDATE `users` SET `likedStories` = ? WHERE `users`.`sha256` = ?");
			$stmt->execute([$likedArray,$_SESSION['userToken']]);


		}

		function removeUpvote($storyIdArg){
			$searchResult = $this->getStory($storyIdArg);
			$totalUpvotes = (int)$searchResult['upvote'];
			$totalUpvotes = --$totalUpvotes;
			
			$stmt = $this->conn->prepare("UPDATE `stories` SET `upvote` = ? WHERE `stories`.`#` = ?");
			$stmt->execute([$totalUpvotes,$storyIdArg]);

			$searchResult = $this->findAuthorDetails($_SESSION['userToken']);
			$likedStories = $searchResult['likedStories'];
			$searchResult = $searchResult['upvotes'];
			if($searchResult == $storyIdArg){
				$upvoteArray = '{}';
			} else {
				$upvoteArray = explode(",",$searchResult);
				$upvoteArray = array_diff($upvoteArray, array($storyIdArg));
				$upvoteArray = implode(",", $upvoteArray);
			}
			$stmt = $this->conn->prepare("UPDATE `users` SET `upvotes` = ? WHERE `users`.`sha256` = ?");
			$stmt->execute([$upvoteArray,$_SESSION['userToken']]);
			if($likedStories == $storyIdArg){
				$likedArray = '{}';
			} else {
				$likedArray = explode(",",$likedStories);
				$storyIndex = array_search($storyIdArg, $likedArray);
				unset($likedArray[$storyIndex]);
				$likedArray = implode(",", $likedArray);
			}
			$stmt = $this->conn->prepare("UPDATE `users` SET `likedStories` = ? WHERE `users`.`sha256` = ?");
			$stmt->execute([$likedArray,$_SESSION['userToken']]);
		}

		function addDownvote($storyIdArg){
			$searchResult = $this->getStory($storyIdArg);
			$totalDownvotes = (int)$searchResult['downvote'];
			$totalDownvotes = ++$totalDownvotes;
			
			$stmt = $this->conn->prepare("UPDATE `stories` SET `downvote` = ? WHERE `stories`.`#` = ?");
			$stmt->execute([$totalDownvotes,$storyIdArg]);

			$searchResult = $this->findAuthorDetails($_SESSION['userToken']);
			$searchResult = $searchResult['downvotes'];
			if($searchResult == '{}'){
				$downvoteArray = $storyIdArg;
			} else {
				$downvoteArray = explode(",",$searchResult);
				array_push($downvoteArray, $storyIdArg);
				$downvoteArray = implode(",", $downvoteArray);
			}
			$stmt = $this->conn->prepare("UPDATE `users` SET `downvotes` = ? WHERE `users`.`sha256` = ?");
			$stmt->execute([$downvoteArray,$_SESSION['userToken']]);
		}

		function removeDownvote($storyIdArg){
			$searchResult = $this->getStory($storyIdArg);
			$totalDownvotes = (int)$searchResult['downvote'];
			$totalDownvotes = --$totalDownvotes;
			
			$stmt = $this->conn->prepare("UPDATE `stories` SET `downvote` = ? WHERE `stories`.`#` = ?");
			$stmt->execute([$totalDownvotes,$storyIdArg]);

			$searchResult = $this->findAuthorDetails($_SESSION['userToken']);
			$searchResult = $searchResult['downvotes'];
			if($searchResult == $storyIdArg){
				$downvoteArray = '{}';
			} else {
				$downvoteArray = explode(",",$searchResult);
				$downvoteArray = array_diff($downvoteArray, array($storyIdArg));
				$downvoteArray = implode(",", $downvoteArray);
			}
			$stmt = $this->conn->prepare("UPDATE `users` SET `downvotes` = ? WHERE `users`.`sha256` = ?");
			$stmt->execute([$downvoteArray,$_SESSION['userToken']]);
		}

		function getStory($storyToGet){
			$stmt = $this->conn->prepare("SELECT * FROM `stories` WHERE `#` = ?");
			$stmt->execute([$storyToGet]);
			$result = $stmt->fetch(PDO::FETCH_NAMED);
			return $result;
		}

		function addLinkedNode($numberToEdit, $numberToBeAdded){
			$result = $this->getStory($numberToEdit); 
			if($result['linkNode'] == '{}'){
				$newLinkNode = "$numberToBeAdded";
			} else {
				$newLinkNode = $result['linkNode'] . ",$numberToBeAdded";
			}
			$stmt = $this->conn->prepare("UPDATE `stories` SET `linkNode` = ? WHERE `stories`.`#` = ?");
			$stmt->execute([$newLinkNode,$numberToEdit]);
		}

		function findExactStory($storyToFind, $synopsisArg, $prevNodeArg){
			$stmt = $this->conn->prepare("SELECT * FROM `stories` WHERE `story` = ? AND `synopsis` = ? AND `prevNode` = ?");
			$stmt->execute([$storyToFind, $synopsisArg, $prevNodeArg]);
			$result = $stmt->fetch(PDO::FETCH_NAMED);
			return $result;
		}
		function addSubStory($prevNode, $storyBody, $genreArg, $changeLogArg,$isPublished, $authorName = "Anonymous"){
			$stmt = $this->conn->prepare("INSERT INTO `stories` (`author`, `story`, `synopsis`, `genre`, `isHead`, `isPublished`, `prevNode`) VALUES (?,?,?,?,0,?,?)");
			$stmt->execute([$authorName, $storyBody, $changeLogArg, $genreArg, $isPublished, $prevNode]);
			$searchResult = $this->findExactStory($storyBody,$changeLogArg,$prevNode);
			$newStoryId = $searchResult['#'];
			$searchResult = $this->findAuthorDetails($_SESSION['userToken']);
			if($isPublished==1){
				$this->addLinkedNode($prevNode, $newStoryId);
				$this->addToStoryCol($searchResult['stories'],$newStoryId);
			} else {
				$this->addToDraftCol($searchResult['drafts'],$newStoryId);
			}
		}

		function toggleFollowed($storyIdArg){
			$searchResult = $this->findAuthorDetails($_SESSION['userToken']);
			$followedStories = $searchResult['followedStories'];
			if(in_array($storyIdArg, explode(",",$followedStories))){
				if($followedStories == $storyIdArg){
					$followedArray = '{}';
				} else {
					$followedArray = explode(",",$followedStories);
					$followedArray = array_diff($followedArray, array($storyIdArg));
					$followedArray = implode(",", $followedArray);
				}
			} else {
				if($followedStories == '{}'){
					$followedArray = $storyIdArg;
				} else {
					$followedArray = explode(",",$followedStories);
					array_push($followedArray, $storyIdArg);
					$followedArray = implode(",", $followedArray);
				}
			}
			$stmt = $this->conn->prepare("UPDATE `users` SET `followedStories` = ? WHERE `users`.`sha256` = ?");
			$stmt->execute([$followedArray,$_SESSION['userToken']]);
		}
		function findHead($storyIdArg){
			$currentStory = $storyIdArg;
			$searchResult = $this->getStory($currentStory);
			$isHead = (bool)$searchResult['isHead'];
			while(!$isHead){
				$currentStory = $searchResult['prevNode'];
				$searchResult = $this->getStory($currentStory);
				$isHead = (bool)$searchResult['isHead'];
				if($isHead){
					break;
				}
			}
			return $currentStory;
		}

		function fishStory(){
			$searchResult = $this->getAllStories(true);
			$maxIndex = count($searchResult) - 1;
			$currentStory = $searchResult[rand(0,$maxIndex)];
			return $currentStory['#'];
		}


		function findUserBasedOnCredentials($userArg, $emailArg){
			$stmt = $this->conn->prepare("SELECT * FROM `users` WHERE `user` = ? AND `email` = ?");
			$stmt->execute([$userArg, $emailArg]);
			$result = $stmt->fetchAll();
			return $result;
		}

		function resetPassword($userIdArg, $newSha256, $newPasswordArg){
			$stmt = $this->conn->prepare("UPDATE `users` SET `sha256` = ? WHERE `users`.`id` = ?");
			$stmt->execute([$newSha256, $userIdArg]);
			$stmt = $this->conn->prepare("UPDATE `users` SET `password` = ? WHERE `users`.`id` = ?");
			$stmt->execute([$newPasswordArg, $userIdArg]);

		}

		function getNewest(){
			$stmt = $this->conn->query("SELECT * FROM `stories` WHERE `isPublished` = 1 ORDER BY `stories`.`datePublished` DESC");
			$result = $stmt->fetchAll();
			return $result;
		}

		function getPublished(){
			$searchResult = $this->findAuthorDetails($_SESSION['userToken']);
			$publishedList = $searchResult['stories'];
			$publishedList = explode(",", $publishedList);
			$publishedList = array_reverse($publishedList);
			$result = array();
			foreach ($publishedList as $storyIndex) {
				$searchResult = $this->getStory($storyIndex);
				array_push($result, $searchResult);
			}
			return $result;
		}

		function getDrafts(){
			$searchResult = $this->findAuthorDetails($_SESSION['userToken']);
			$draftList = $searchResult['drafts'];
			$draftList = explode(",", $draftList);
			$draftList = array_reverse($draftList);
			$result = array();
			foreach ($draftList as $storyIndex) {
				$searchResult = $this->getStory($storyIndex);
				array_push($result, $searchResult);
			}
			return $result;
		}

		function forYouAlg(){#unfinished
			if(isset($_SESSION['lastGenreVisited'])){
				$genreToSubmit = "'" . implode("','",$_SESSION['lastGenreVisited']) ."'";
				$stmt = $this->conn->query("SELECT * FROM `stories` WHERE `genre` IN ({$genreToSubmit}) AND `isPublished` = 1 ORDER BY `stories`.`upvote` DESC");
				$result = $stmt->fetchAll();
				return $result;
			}
		}

		function getTrending(){
			$stmt = $this->conn->query("SELECT * FROM `stories` WHERE `isPublished` = 1 ORDER BY `stories`.`upvote` DESC");
			$result = $stmt->fetchAll();
			return $result;
		}

		function addToStoryCol($currentStories,$storyToBeAdded){
			if($currentStories == '{}'){
					$updatedPublishedStories = "$storyToBeAdded";
				} else {
					$explodedArray = explode(',', $currentStories);
					array_push($explodedArray, (int)$storyToBeAdded);
					$updatedPublishedStories = implode(',', $explodedArray);
				}
			$stmt = $this->conn->prepare("UPDATE `users` SET `stories` = ? WHERE `users`.`sha256` = ?");
			$stmt->execute([$updatedPublishedStories,$_SESSION['userToken']]);
		}

		function findAuthorByID($authorID){
			$stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
			$stmt->execute([$authorID]);
			$result = $stmt->fetch(PDO::FETCH_NAMED);
			return $result;
		}

		function addToDraftCol($currentStories,$storyToBeAdded){
			if($currentStories == '{}'){
					$updatedSavedStories = "$storyToBeAdded";
				} else {
					$explodedArray = explode(',', $currentStories);
					array_push($explodedArray, (int)$storyToBeAdded);
					$updatedSavedStories = implode(',', $explodedArray);
				}
				$stmt = $this->conn->prepare("UPDATE `users` SET `drafts` = ? WHERE `users`.`sha256` = ?");
				$stmt->execute([$updatedSavedStories,$_SESSION['userToken']]);
		}

		function getFollowing($userTokenArg){
			$result = $this->findAuthorDetails($userTokenArg);
			if($result == 0){
				$followedArray = array();
			} else {
				$followedArray = explode(",",$result['followedStories']);
			}
			$followingStories = array();
			foreach ($followedArray as $followedIndex) {
				$stmt = $this->conn->prepare("SELECT * FROM `stories` WHERE `#` = ? ");
				$stmt->execute([$followedIndex]);
				$result = $stmt->fetch(PDO::FETCH_NAMED);
				array_push($followingStories, $result);
			}
			if($followingStories[0]==false){
				$followingStories = $this->getGenre('returnNothing');
			}
			return $followingStories;
		}

		function getHead($storyBase){
			$isHeadArg = 0;
			$currentStory = $storyBase;
			while ($isHeadArg==0) {
				$searchResult = $this->getStory($currentStory);
				if($searchResult['isHead'] == 0){
					$currentStory = $searchResult['prevNode'];
				} else {
					break;
				}
			}

			return $currentStory;
		}

		function getGenre($genreArg){
			$stmt = $this->conn->prepare("SELECT * FROM `stories` WHERE `genre` = ? AND `isPublished` = 1 ORDER BY `stories`.`upvote` DESC");
			$stmt->execute([$genreArg]);
			$result = $stmt->fetchAll();
			return $result;
		}

		function getLiked($userTokenArg){
			$stmt = $this->conn->prepare("SELECT * FROM `users` WHERE `sha256` = ?");
			$stmt->execute([$userTokenArg]);
			$result = $stmt->fetch(PDO::FETCH_NAMED);
			if($result == 0){
				$likedArray = array();
			} else {
				$likedArray = explode(",",$result['likedStories']);
			}
			$likedStories = array();
			foreach ($likedArray as $likedIndex) {
				$stmt = $this->conn->prepare("SELECT * FROM `stories` WHERE `#` = ? ");
				$stmt->execute([$likedIndex]);
				$result = $stmt->fetch(PDO::FETCH_NAMED);
				array_push($likedStories, $result);
			}

			if($likedStories[0]==false){
				$likedStories = $this->getGenre('returnNothing');
			}
			return $likedStories;
		}

		function getAllLinked($numberToGet){
			$searchResult = $this->getStory($numberToGet);
			$linkedNodeArray = explode(",", $searchResult['linkNode']);
			return $linkedNodeArray;
		}

		function getLinkedStories($parentStoryArg){
			$searchResult = $this->getStory($parentStoryArg);
			if($searchResult['linkNode']=='{}'){
				$searchResult['linkNode'] = 0;
			}
			$stmt = $this->conn->prepare("SELECT * FROM `stories` WHERE `#` IN (" . $searchResult['linkNode'] . ") ORDER BY `stories`.`upvote` DESC");
			$stmt->execute();
			$searchResult = $stmt->fetchAll();
			return $searchResult;
		}

		function deleteDraft($storyIdArg){
			$searchResult = $this->findAuthorDetails($_SESSION['userToken']);
			$draftLists = $searchResult['drafts'];
			if(count(explode(',',$draftLists)) == 1){
				$newDraft = '{}';
			} else {
				$newDraft = explode(",",$draftLists);
				$newDraft = array_diff($newDraft, array($storyIdArg));
				$newDraft = implode(",", $newDraft);
			}
			$stmt = $this->conn->prepare("UPDATE `users` SET `drafts` = ? WHERE `users`.`sha256` = ?");
			$stmt->execute([$newDraft, $_SESSION['userToken']]);
			$stmt = $this->conn->prepare("DELETE FROM `stories` WHERE `stories`.`#` = ?");
			$stmt->execute([$storyIdArg]);
		}

		function deleteNode($numberToDelete){
			$searchResult = $this->getStory($numberToDelete);
			$prevNodeToEdit = $searchResult['prevNode'];
			$stmt = $this->conn->prepare("DELETE FROM `stories` WHERE `stories`.`#` = ?");
			$stmt->execute([$numberToDelete]);
			if($prevNodeToEdit == '{}'){
				return;
			} else {
				$prevNodeArray = explode(",", $prevNodeToEdit);
				$newLinkNode = array();
				foreach ($prevNodeArray as $value) {
					if($value==$prevNodeToEdit){
						continue;
					} else {
						array_push($newLinkNode, $value);
					}
				}
				$newLinkNode = implode(",", $newLinkNode);
				if($newLinkNode == ""){
					$newLinkNode = '{}';
				}
				$stmt = $this->conn->prepare("UPDATE `stories` SET `linkNode` = ? WHERE `stories`.`#` = ?");
				$stmt->execute([$newLinkNode,$prevNodeToEdit]);
			}
		}

		function createHeadStory($titleArg, $synopsisArg, $genreArg, $bodyArg, $publishArg = 1){
			$searchResult = $this->findAuthorDetails($_SESSION['userToken']);
			$currentPublishedStories = $searchResult['stories'];
			$currentSavedStories = $searchResult['drafts'];
			$authorArg = $searchResult['id'];
			$stmt = $this->conn->prepare("INSERT INTO `stories` (`author`, `title`, `story`, `synopsis` , `genre`, `isHead`, `isPublished`, `prevNode`) VALUES (?,?,?,?,?,1,?,0)");
			$stmt->execute([$authorArg,$titleArg, $bodyArg, $synopsisArg, $genreArg, $publishArg]);
			$searchResult = $this->findExactStory($bodyArg,$synopsisArg,0);
			$storyId = $searchResult['#'];
			if($publishArg == 1){
				$this->addToStoryCol($currentPublishedStories,$storyId);
			} else {
				if($currentSavedStories == '{}'){
					$updatedSavedStories = "$storyId";
				} else {
					$explodedArray = explode(',', $currentSavedStories);
					array_push($explodedArray, (int)$storyId);
					$updatedSavedStories = implode(',', $explodedArray);
				}
				$stmt = $this->conn->prepare("UPDATE `users` SET `drafts` = ? WHERE `users`.`sha256` = ?");
				$stmt->execute([$updatedSavedStories,$_SESSION['userToken']]);
			}
		}

		function updateDraft($draftIdArg, $storyBody, $genreArg, $changeLogArg){
			$stmt = $this->conn->prepare("UPDATE `stories` SET `story` = ?, `synopsis` = ?, `genre` = ? WHERE `stories`.`#` = ?");
			$stmt->execute([$storyBody, $changeLogArg, $genreArg, $draftIdArg]);
		}

		function updateHeadDraft($draftIdArg, $storyTitle, $storyBody, $genreArg, $synopsisArg){
			$stmt = $this->conn->prepare("UPDATE `stories` SET `title` = ?, `story` = ?, `synopsis` = ?, `genre` = ? WHERE `stories`.`#` = ?");
			$stmt->execute([$storyTitle, $storyBody, $synopsisArg, $genreArg, $draftIdArg]);
		}

		function publishDraft($draftIdArg, $isHeadArg = 0, $prevNodeArg = 0){
			$searchResult = $this->findAuthorDetails($_SESSION['userToken']);
			$draftList = $searchResult['drafts'];
			$currentPublishedStories = $searchResult['stories'];
			$draftList = explode(",", $draftList);
			if (($key = array_search($draftIdArg, $draftList)) !== false) {
			    unset($draftList[$key]);
			}
			if (count($draftList) == 0){
				$draftList = '{}';
			} else {
				$draftList = implode(',', $draftList);
			}
			$stmt = $this->conn->prepare("UPDATE `users` SET `drafts` = ? WHERE `users`.`sha256` = ?");
			$stmt->execute([$draftList,$_SESSION['userToken']]);
			$stmt = $this->conn->prepare("UPDATE `stories` SET `isPublished` = '1' WHERE `stories`.`#` = ?");
			$stmt->execute([$draftIdArg]);
			$this->addToStoryCol($currentPublishedStories, $draftIdArg);
			if($isHeadArg == 0){
				$result = $this->getStory($draftIdArg);
				$this->addLinkedNode($result['prevNode'], $draftIdArg);
			}

		}

	}

?>