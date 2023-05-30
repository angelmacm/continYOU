<div class="total-header-container">
    <a href="./"><img class = "book-icon header-text" src = "./pics/book.png" alt = "Book-Icon"></a>
    <div class = "header-container">
        <div class = "left-side">
            <a href="./" class = "header-text">Home</a>
            <a href="trending.php" class = "header-text">Trending</a>
            <a href="newest.php" class = "header-text">Newest</a>
            <a href="genre.php" class = "header-text">Genre</a>
        </div>
        <div class="createStoryButton">
            <a href="createstory.php" class="header-text">Create your Story</a>
        </div>
        <div class = "right-side ">

            <?php
                if(isset($_SESSION['userToken'])){
                    echo "<a href='followedstories.php' class = 'header-text logged-in-text'>Followed Stories</a>";
                    echo "<a href='mystories.php' class = 'header-text my-story-text'>My Stories</a>";
                } else {
                    echo "<a href='login.php' class = 'header-text loginText'>Log in</a>";
                    echo "<a href='register.php' class = 'header-text'>Register</a>";
                }

            ?>
            <a href="profile.php"><img class ="acc-icon header-text" src = "./pics/account.png" alt = "Account Icon"></a>
        </div>
    </div>
</div>
    <!--End of Header-->